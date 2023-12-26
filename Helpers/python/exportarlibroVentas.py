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
with open(f'datos{datophp[0]}.json', 'r') as f:
    data = json.load(f)
    
with open(f'datos_nota_credito{datophp[0]}.json', 'r') as f:
    datos_nota_credito_totales = json.load(f)

# Crear un DataFrame de Pandas desde los datos
# ------------------------------------------------------BOLETA-FACTURA-NOTA VENTA---------------------------------------
df = pd.DataFrame(data)
num_fila = 0
num_fila_invertidos=0
if not df.empty:
    df_totales_invertidos = df.groupby('TIPO DOCUMENTO').agg({'N° DOCUMENTO': 'count' ,'EXENTO': 'sum', 'AFECTO': 'sum', 'IVA': 'sum', 'TOTAL': 'sum', })# Realizar la suma y contar por tipo de documento(N° documento) No suma sino contabiliza
    df_totales_invertidos.rename(columns={'N° DOCUMENTO': 'CANTIDAD'}, inplace=True)# Renombrar la columna 'N° DOCUMENTO' a 'CANTIDAD'
    num_fila = df.shape[0]  # cuantas filas posee
else:
    # Tu DataFrame TOTALES
    data = {'CANTIDAD': [0, 0, 0],
            'EXENTO': [0, 0, 0],
            'AFECTOS': [0, 0, 0],
            'IVA': [0, 0, 0],
            'TOTAL': [0, 0, 0]}
    index = ['BOLETA', 'NOTA VENTA', 'FACTURA']
    df_totales_invertidos = pd.DataFrame(data, index=index)


num_fila_invertidos = df_totales_invertidos.shape[0]
#  ------------------------------------------------------NOTA CREDITO--------------------------------------------------------   
df_nota_credito = pd.DataFrame(datos_nota_credito_totales)
total_iva_nota_credito = 0
total_afecto_nota_credito = 0
total_nota_credito = 0
cantidad_filas_nota_credito = 0
total_exento_credito=0
if not df_nota_credito.empty:
    columnas_a_eliminar = ['cliente_negocio_factura', 'cliente_negocio_boleta', 'id_factura', 'id_boleta',
                           'estado_nota_credito',
                           'numero_boleta',
                           'serie_boleta',
                           'numero_factura',
                           'serie_factura']
    df_nota_credito = df_nota_credito.drop(columns=columnas_a_eliminar)
    cantidad_filas_nota_credito = df_nota_credito.shape[0]
    total_iva_nota_credito = df_nota_credito['iva_nota_credito'].sum()
    total_afecto_nota_credito = df_nota_credito['valorafecto_nota_credito'].sum()
    total_nota_credito = df_nota_credito['total_nota_credito'].sum()
    total_exento_credito = df_nota_credito['valorexento_nota_credito'].sum()
    #AGREGAR LAS COLUMNAS AL TUPLA
    df_nota_credito['TIPO DOCUMENTO'] = 'NOTA CREDITO ELECTRONICO'
    df_nota_credito['RUC EMPRESA'] =datophp[4]

    df_nota_credito=df_nota_credito[['fechacreacion_nota_credito','TIPO DOCUMENTO','numero_nota_credito',
                                     'serie_nota_credito','RUC EMPRESA',"documento_referencia",'cliente',"numero_referencia","serie_referencia",
                                     "valorexento_nota_credito",'valorafecto_nota_credito','iva_nota_credito','total_nota_credito']] #ORDENAR LAS COLUMNAS
    #RENOMBAMOS LAS COLUMNAS
    nuevos_nombres={
        'fechacreacion_nota_credito':"FECHA",
        'numero_nota_credito':"N° DOCUMENTOS",
        'serie_nota_credito':"SERIE",
        'documento_referencia':"DOCUMENTO REFERENCIA",
        'numero_referencia':"N° DOCUMENTOS REFERENCIA",
        'serie_referencia':"SERIE REFERENCIA",
        'cliente':"NOMBRE CLIENTE",
        'valorexento_nota_credito':"EXENTO",
        'valorafecto_nota_credito':"AFECTO",
        'iva_nota_credito':"IVA",
        'total_nota_credito':"TOTAL"
    }
    df_nota_credito.rename(columns=nuevos_nombres,inplace=True)
      
      
# AGREGAR UNA FILA MAS CON SUS DATOS   
df_totales_invertidos.loc['NOTA CREDITO']=[cantidad_filas_nota_credito,total_exento_credito,total_afecto_nota_credito, total_iva_nota_credito, total_nota_credito]
num_fila_invertidos = df_totales_invertidos.shape[0]  # cuantas filas posee
# ----------------------------------------------------------------------------------------------------------------------------------

totales = df_totales_invertidos.loc[:,'TOTAL'].sum()#sumamos los totales , todos los documento
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
fila_totales_invertidos=num_fila+12+cantidad_filas_nota_credito+4
fila_nota_credito=fila_body+num_fila+2

df.to_excel(writer, sheet_name='Sheet1',startrow=fila_body, index=False, header=False)
df_nota_credito.to_excel(writer, sheet_name='Sheet1',startrow=fila_nota_credito, index=False, header=False)
df_totales_invertidos.to_excel(writer, sheet_name='Sheet1', startrow=fila_totales_invertidos, index=True, header=True)

# Obtener la hoja de cálculo generada por Pandas
worksheet = writer.sheets['Sheet1']
# PARA LAS CELDAS PRINCIPAL--------------------------------------
# Ajustar automáticamente el ancho de las celdas
for i, col in enumerate(df.columns):
    column_len = df[col].astype(str).map(len).max()
    # Tomar en cuenta el ancho de la cabecera
    column_len = max(column_len, len(col))
    worksheet.set_column(i, i, column_len + 3)  # Agregar un margen de 2


# Escribir las celdas de la cabecera usando el formato creado
for col_num, value in enumerate(df.columns.values):
    worksheet.write(10, col_num, value, header_format)
    
# Apply the cell format to body cells
# QUE PINTA LAS CELDAS DESPUES DE LA FILA 11
for row_num in range(fila_body, len(df) + fila_body):
    for col_num, value in enumerate(df.iloc[row_num - fila_body]):
        worksheet.write(row_num, col_num, value, body_format)    
# ---------------------------------------------------------------------
# PARA LAS CELDAS NOTA CREDITO---------------------------------------
# Ajustar automáticamente el ancho de las celdas
for i, col in enumerate(df_nota_credito.columns):
    column_len = df_nota_credito[col].astype(str).map(len).max()
    # Tomar en cuenta el ancho de la cabecera
    column_len = max(column_len, len(col))
    worksheet.set_column(i, i, column_len +2)  # Agregar un margen de 2    

for col_num, value in enumerate(df_nota_credito.columns.values):
    worksheet.write(fila_nota_credito-1, col_num, value, header_format)    
    
for row_num in range(fila_nota_credito, len(df_nota_credito) + fila_nota_credito):
    for col_num, value in enumerate(df_nota_credito.iloc[row_num - fila_nota_credito]):
        worksheet.write(row_num, col_num, value, body_format)
        
# -----------------------------------------------------------------------------------
# worksheet.merge_range() se utiliza para combinar las celdas desde
# la columna 0 hasta la columna 3 en la fila "TOTAL".
# Luego, la función worksheet.write() se utiliza
# para escribir el valor de totales en la columna 4
# de la fila "TOTAL".
# Unir las cuatro columnas de la fila "TOTAL" en una sola celda
fila_suma_total=cantidad_filas_nota_credito+num_fila+fila_body+10
worksheet.merge_range(
    2, 0, 2, 1, f"Fecha de informe : {date.today()}", header_format)
worksheet.merge_range(4, 0, 4, 1, "LIBRO DE VENTAS", header_format)
worksheet.merge_range(5, 0, 5, 1, f"Tipo Documento {datophp[3]}", body_format)
worksheet.merge_range(
    6, 0, 6, 1, f"Desde: {datophp[1]}  Hasta: {datophp[2]}", body_format)
worksheet.merge_range(fila_suma_total, 0,fila_suma_total, 4, "TOTAL", header_format)
worksheet.write(fila_suma_total, 5, totales, header_format)

# Close the Excel writer
writer.close()
print("Archivo exportado exitosamente en:", ruta_archivo)
