# importar la libreria pandas 2.0.1
# importar la libreria xlsWrite 3.1.0
from datetime import date
import json
import pandas as pd
import sys

# DATOS ENVIADO DESDE PHP
labeltoles = sys.argv[1]
datophp=labeltoles.split(',')
# Leer el JSON pasado desde PHP
with open(f'datos{datophp[0]}.txt', 'r') as f:
    data = json.load(f)


with open(f'datos_totales{datophp[0]}.txt', 'r') as f:
    datos_totales = json.load(f)


# Crear un DataFrame de Pandas desde los datos
df = pd.DataFrame(data)
df_totales = pd.DataFrame(datos_totales)
df_totales_invertidos = df_totales.transpose()  # invertimos las columnas y filas    
if df.empty:
    num_fila = 0
else:
    num_fila = df.shape[0]  # cuantas filas posee


if df_totales.empty:
    num_fila_invertidos = 0
    totales=0
else:
    num_fila_invertidos = df_totales_invertidos.shape[0]  # cuantas filas posee
    df_totales_invertidos.columns.name = 'TIPO DOCUMENTO'
    # Selecciona la columnas total y lo sumamos
    totales = df_totales_invertidos.loc[:, 'TOTAL'].sum()





# Crear un archivo de Excel con el DataFrame
ruta_archivo = f'datos{datophp[0]}.xlsx'

writer = pd.ExcelWriter(ruta_archivo, engine='xlsxwriter')
# Crear un formato para la cabecera
workbook = writer.book
header_format = workbook.add_format({
    'bold': True,
    'text_wrap': True,
    'valign': 'top',
    'fg_color': '#D7E4BC',  # Color de fondo
    'border': 1  # Borde alrededor de la celda
})
body_format = workbook.add_format({
    'border': 1  # Borde alrededor de la celda
})
fila_body = 11
df.to_excel(writer, sheet_name='Sheet1',
            startrow=fila_body, index=False, header=False)
df_totales_invertidos.to_excel(
    writer, sheet_name='Sheet1', startrow=num_fila+12, index=True, header=True)

# Obtener la hoja de cálculo generada por Pandas
worksheet = writer.sheets['Sheet1']
# Ajustar automáticamente el ancho de las celdas
for i, col in enumerate(df.columns):
    column_len = df[col].astype(str).map(len).max()
    # Tomar en cuenta el ancho de la cabecera
    column_len = max(column_len, len(col))
    worksheet.set_column(i, i, column_len + 2)  # Agregar un margen de 2


# Escribir las celdas de la cabecera usando el formato creado
for col_num, value in enumerate(df.columns.values):
    worksheet.write(10, col_num, value, header_format)

# Apply the cell format to body cells
# QUE PINTA LAS CELDAS DESPUES DE LA FILA 11
for row_num in range(fila_body, len(df) + fila_body):
    for col_num, value in enumerate(df.iloc[row_num - fila_body]):
        worksheet.write(row_num, col_num, value, body_format)


filatotales = num_fila + 13
for row_num in range(filatotales, len(df_totales_invertidos) + (filatotales)):
    for col_num, value in enumerate(df_totales_invertidos.iloc[row_num - (filatotales)][1:], start=1):
        worksheet.write(row_num, col_num, value, body_format)


# worksheet.merge_range() se utiliza para combinar las celdas desde
# la columna 0 hasta la columna 3 en la fila "TOTAL".
# Luego, la función worksheet.write() se utiliza
# para escribir el valor de totales en la columna 4
# de la fila "TOTAL".
# Unir las cuatro columnas de la fila "TOTAL" en una sola celda

worksheet.merge_range(
    2, 0, 2, 1, f"Fecha de informe : {date.today()}", header_format)
worksheet.merge_range(4, 0, 4, 1, "LIBRO DE VENTAS", header_format)
worksheet.merge_range(5, 0, 5, 1, f"Tipo Documento {datophp[3]}", body_format)
worksheet.merge_range(
    6, 0, 6, 1, f"Desde: {datophp[1]}  Hasta: {datophp[2]}", body_format)
worksheet.merge_range(num_fila+num_fila_invertidos + 13, 0,
                      num_fila+num_fila_invertidos + 13, 3, "TOTAL", header_format)
worksheet.write(num_fila+num_fila_invertidos + 13, 4, totales, header_format)
# Close the Excel writer
writer.close()
print("Archivo exportado exitosamente en:", ruta_archivo)
