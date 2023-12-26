# importar la libreria pandas 2.0.1
# importar la libreria xlsWrite 3.1.0
import json
import pandas as pd

# Leer el JSON pasado desde PHP
with open('datos.txt', 'r') as f:
    data = json.load(f)


# Crear un DataFrame de Pandas desde los datos
df = pd.DataFrame(data)

# Crear un archivo de Excel con el DataFrame
ruta_archivo = 'datos.xlsx'

writer = pd.ExcelWriter(ruta_archivo, engine='xlsxwriter')
df.to_excel(writer, index=False)

# Obtener la hoja de cálculo generada por Pandas
workbook = writer.book
worksheet = writer.sheets['Sheet1']

# Ajustar automáticamente el ancho de las celdas
for i, col in enumerate(df.columns):
    column_len = df[col].astype(str).map(len).max()
    column_len = max(column_len, len(col))  # Tomar en cuenta el ancho de la cabecera
    worksheet.set_column(i, i, column_len + 2)  # Agregar un margen de 2


# Crear un formato para la cabecera
header_format = workbook.add_format({
    'bold': True,
    'text_wrap': True,
    'valign': 'top',
    'fg_color': '#D7E4BC',  # Color de fondo
    'border': 1  # Borde alrededor de la celda
})

# Escribir las celdas de la cabecera usando el formato creado
for col_num, value in enumerate(df.columns.values):
    worksheet.write(0, col_num, value, header_format)

writer.close()
print("Archivo exportado exitosamente en:", ruta_archivo)