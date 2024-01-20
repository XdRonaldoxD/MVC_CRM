from datetime import datetime
import hashlib
import json
import numpy as np
import pandas as pd
import sys
import mysql.connector
import pytz

#DATOS PHP---------------------------------------
datosphp = sys.argv[1]
datosphp=datosphp.split(';')
host=datosphp[0]
user=datosphp[1]
passw=datosphp[2]
database=datosphp[3]
fecha_inicio=f'{datosphp[4]} 00:00:00'
fecha_fin=f'{datosphp[5]} 23:59:59'
id_usuario=datosphp[6]

# ---------------------CONEXION--------------------------
# Configurar la conexión
config = {
    'user': user,
    'password': passw,
    'host': host,
    'database': database
}
# Conectar a la base de datos
conn = mysql.connector.connect(**config)
cursor = conn.cursor(buffered=True)
fechas={
    'fecha_inicio':fecha_inicio,
    'fecha_fin':fecha_fin
}
consulta="""
SELECT  codigo_producto,glosa_producto,glosa_sucursal,
            IF(apellidopaterno_cliente IS NOT NULL AND apellidomaterno_cliente IS NOT NULL, 
            CONCAT(nombre_cliente,' ',apellidopaterno_cliente,' ',apellidomaterno_cliente),
            IF(apellidopaterno_cliente IS NOT NULL, CONCAT(nombre_cliente, ' ', apellidopaterno_cliente), nombre_cliente)) as cliente,
            DATE_FORMAT(fechacreacion_negocio_detalle, '%d/%m/%Y %h:%i %p') as fechacreacion_negocio_detalle,
            numero_negocio,
            CONCAT(nombre_staff,' ',apellidopaterno_staff,' ',IF(apellidomaterno_staff IS NULL, '', apellidomaterno_staff)) as usuario_venta,
            cantidad_negocio_detalle,
            total_negocio_detalle
        cantidad_negocio_detalle
        FROM negocio_detalle
        inner join producto using (id_producto)
        inner join negocio using (id_negocio)
        left join sucursal using (id_sucursal)
        inner join cliente using (id_cliente)
        inner join usuario using (id_usuario)
        inner join staff using (id_staff)
        WHERE vigente_negocio=1  and fechacreacion_negocio_detalle>= %(fecha_inicio)s and fechacreacion_negocio_detalle<= %(fecha_fin)s
        ORDER BY id_negocio_detalle desc
"""
cursor.execute(consulta, fechas)
negocio_detalle = cursor.fetchall()

# Obtener los resultados de la consulta y asignar nombres a las columnas
columnas = ['CODIGO', 'PRODUCTO','SUCURSAL', 'CLIENTE', 'FECHA','N° PEDIDO', 'USUARIO VENTA', 'CANTIDAD', 'TOTAL VENDIDO']
df_negocio_detalle = pd.DataFrame(negocio_detalle, columns=columnas)
fila_negocio_detalle=0
sucursales_cadena=''
if not df_negocio_detalle.empty:
    # # EXTRAEMOS LA SUCURSAL DEL DATAFRAME-------------------------
    arreglo_sucursales = df_negocio_detalle['SUCURSAL'].to_numpy()
    arreglo_sucursales = [sucursal for sucursal in arreglo_sucursales if sucursal is not None]
    if arreglo_sucursales:
        sucursales_unicas = np.unique(arreglo_sucursales)
        sucursales_cadena = ', '.join(sucursales_unicas)
    # # ------------------------------------------------------------
    fila_negocio_detalle=df_negocio_detalle.shape[0]
    df_resumen_usuarios = df_negocio_detalle.groupby('USUARIO VENTA').agg({# Agregar información resumida sobre los usuarios de venta
        'CANTIDAD': 'sum',
        'TOTAL VENDIDO': 'sum'
    }).reset_index()


# Obtener la fecha actual para incluirla en el nombre del archivo Excel
fecha_actual = datetime.now().strftime("%Y%m%d")

# Exportar el DataFrame a un archivo Excel
nombre_archivo_excel = f"venta_producto_{id_usuario}_{fecha_actual}.xlsx"
writer = pd.ExcelWriter(nombre_archivo_excel, engine='xlsxwriter')
df_negocio_detalle.to_excel(writer, sheet_name='Sheet1', startrow=7, index=False)

# Agregar la información resumida al archivo Excel
if(fila_negocio_detalle>0):  
    df_resumen_usuarios.to_excel(writer, sheet_name='Sheet1', startrow=10+fila_negocio_detalle, index=False)
# Obtener el objeto xlsxwriter workbook y worksheet
workbook  = writer.book
header_format = workbook.add_format({
    'bold': True,
    'text_wrap': True,
    'valign': 'top',
    'fg_color': '#D7E4BC',  # Color de fondo
    'border': 1  # Borde alrededor de la celda
})
worksheet = writer.sheets['Sheet1']
# Ajustar automáticamente el ancho de las columnas
for i, col in enumerate(df_negocio_detalle.columns):
    column_len = max(df_negocio_detalle[col].astype(str).apply(len).max(), len(col) + 2)
    worksheet.set_column(i, i, column_len)

worksheet.merge_range(2, 0, 2, 1, "REPORTE DE VENTA", header_format)
worksheet.merge_range(3, 0, 3, 1, f"FECHA DEL REPORTE : {datosphp[4]} al {datosphp[5]}", header_format)
worksheet.merge_range(4, 0, 4, 1, f"SUCURSAL: {sucursales_cadena}", header_format)



writer.close()# Guardar y cerrar el archivo Excel
# Cerrar la conexión a la base de datos
cursor.close()
conn.close()
print("Exportado exitosamente")