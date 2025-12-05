API REST para Gestión de Punto de Venta (Panadería Celeste)

Esta API permite la gestión de ventas, inventario, clientes y proveedores, siguiendo un diseño RESTful implementado en PHP nativo con PDO.

<<<<<<< HEAD
ENDPOINTS de la API (Definitivos)
Este archivo debe ser entregado aparte, con la URL base definida en tu entorno.
Variable de Entorno Base: {{BASE_URL}} (http://localhost/JONATHAN_SANDOVAL_AA5_EV03/public/index.php)
 Autenticación y Usuarios
Recurso	Función	Método	Ruta (Endpoint)
Autenticación	Login	POST	{{BASE_URL}}?resource=login
Autenticación	Registro	POST	{{BASE_URL}}?resource=register
Usuarios	Listar Todos	GET	{{BASE_URL}}?resource=usuarios
Usuarios	Actualizar	PUT/PATCH	{{BASE_URL}}?resource=usuarios&id={ID}
Usuarios	Inactivar	DELETE	{{BASE_URL}}?resource=usuarios&id={ID}

 Inventario (Productos y Categorías)
Recurso	Función	Método	Ruta (Endpoint)
Categorías	Crear	POST	{{BASE_URL}}?resource=categorias
Productos	Crear	POST	{{BASE_URL}}?resource=productos
Productos	Listar Todos	GET	{{BASE_URL}}?resource=productos
Productos	Actualizar	PUT/PATCH	{{BASE_URL}}?resource=productos&id={ID}
Productos	Inactivar	DELETE	{{BASE_URL}}?resource=productos&id={ID}

 Clientes y Ventas (Transacciones)
Recurso	Función	Método	Ruta (Endpoint)
Clientes	Crear	POST	{{BASE_URL}}?resource=clientes
Ventas	Registrar Venta	POST	{{BASE_URL}}?resource=ventas
Ventas	Ver Detalle	GET	{{BASE_URL}}?resource=ventas&id={ID}


1.  Configuración Inicial en Postman
El primer paso es crear un entorno para definir la URL base de tu API, lo cual simplifica todas las pruebas.
Paso 1.1: Crear Entorno
1.	Abre Postman y haz clic en Environments (Entornos) en el menú lateral izquierdo.
2.	Haz clic en + para crear un nuevo entorno.
3.	Asígnale el nombre: Panaderia Celeste DEV.
Paso 1.2: Crear Variable BASE_URL
Dentro del entorno Panaderia Celeste DEV, define la variable que contiene la ruta a tu front controller (index.php):
Variable	Initial Value (Valor Inicial)	Current Value (Valor Actual)	Observaciones
BASE_URL	http://localhost/JONATHAN_SANDOVAL_AA5_EV03/public/index.php	(Mismo valor)	Esto apunta directamente a tu archivo router.
4.	Haz clic en Save (Guardar).
5.	IMPORTANTE: Asegúrate de seleccionar el entorno Panaderia Celeste DEV en el dropdown de entornos (esquina superior derecha de Postman) antes de empezar a probar.
=======
Estructura General de la API

Recurso

Método

Endpoint

Descripción

Autenticación

POST

/register

Registra un nuevo usuario (Vendedor o Administrador).

Autenticación

POST

/login

Inicia sesión y autentica un usuario existente.

Usuarios

GET

/usuarios

Lista todos los usuarios activos (Solo para Admin).

Usuarios

GET

/usuarios/{id}

Obtiene los detalles de un usuario específico (Solo para Admin).

Usuarios

PUT/PATCH

/usuarios/{id}

Actualiza datos (nombre, rol, contraseña) de un usuario (Solo para Admin).

Usuarios

DELETE

/usuarios/{id}

Inactiva (borrado lógico) un usuario (Solo para Admin).

Categorías

GET

/categorias

Lista todas las categorías activas.

Categorías

GET

/categorias/{id}

Obtiene los detalles de una categoría específica.

Categorías

POST

/categorias

Crea una nueva categoría.

Categorías

PUT/PATCH

/categorias/{id}

Actualiza el nombre o descripción de una categoría.

Categorías

DELETE

/categorias/{id}

Inactiva (borrado lógico) una categoría.

Clientes

GET

/clientes

Lista todos los clientes activos.

Clientes

GET

/clientes/{id}

Obtiene los detalles de un cliente específico.

Clientes

POST

/clientes

Crea un nuevo cliente.

Clientes

PUT/PATCH

/clientes/{id}

Actualiza los datos de un cliente (nombre, teléfono, etc.).

Clientes

DELETE

/clientes/{id}

Inactiva (borrado lógico) un cliente.

Productos

GET

/productos

Lista todos los productos activos con detalles de categoría.

Productos

GET

/productos/{id}

Obtiene los detalles de un producto específico.

Productos

POST

/productos

Crea un nuevo producto en el inventario.

Productos

PUT/PATCH

/productos/{id}

Actualiza los datos (nombre, precio, stock, categoría) de un producto.

Productos

DELETE

/productos/{id}

Inactiva (borrado lógico) un producto.

Ventas

POST

/ventas

Crea una nueva transacción de venta (transaccional).

Ventas

GET

/ventas

Lista todas las ventas activas registradas.

Ventas

GET

/ventas/{id}

Obtiene el detalle completo de una factura/venta por ID.

Proveedores

GET

/proveedores

Lista todos los proveedores activos.

Proveedores

POST

/proveedores

Crea un nuevo proveedor.

Proveedores

PUT/PATCH

/proveedores/{id}

Actualiza los datos de un proveedor.

Proveedores

DELETE

/proveedores/{id}

Inactiva (borrado lógico) un proveedor.

Reportes

GET

/reportes/resumen_ventas

Calcula KPIs (Ingresos, Costo, Ganancia, Promedio) filtrados por fecha y vendedor.

Reportes

GET

/reportes/top_productos

Obtiene el Top 10 de productos más vendidos en un periodo.

Reportes

GET

/reportes/bajo_stock

Lista productos activos con stock por debajo de un límite (por defecto 5).

1. Módulo de Reportes y Analíticas

1.1 Resumen de Ventas y KPIs

Endpoint: GET /reportes/resumen_ventas?fecha_inicio=YYYY-MM-DD&fecha_fin=YYYY-MM-DD[&usuario_id=ID]

Descripción: Proporciona un resumen ejecutivo de las ventas en el periodo especificado, incluyendo métricas clave.

Parámetros de Consulta (Query Params):

fecha_inicio (obligatorio)

fecha_fin (obligatorio)

usuario_id (opcional, para filtrar por vendedor)

Respuesta Exitosa (200 OK):

{
    "periodo_analizado": "Desde 2025-11-01 00:00:00 hasta 2025-11-30 23:59:59",
    "ingresos_totales": 1500000.00,
    "costo_total": 450000.00,
    "ganancia_bruta": 1050000.00,
    "total_ventas_unidades": 85,
    "promedio_venta_factura": 17647.05
}


1.2 Top Productos Vendidos

Endpoint: GET /reportes/top_productos?fecha_inicio=YYYY-MM-DD&fecha_fin=YYYY-MM-DD[&usuario_id=ID]

Descripción: Lista los 10 productos más vendidos en cantidad dentro del periodo.

Respuesta Exitosa (200 OK):

[
    {
        "nombre_producto": "Pan Francés",
        "cantidad_vendida": 250,
        "ingresos_generados": 250000.00
    },
    {
        "nombre_producto": "Croissant de Mantequilla",
        "cantidad_vendida": 180,
        "ingresos_generados": 90000.00
    }
]
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344


1.3 Productos con Bajo Stock

Endpoint: GET /reportes/bajo_stock[&limite=N]

Descripción: Genera una alerta listando los productos activos cuyo stock está igual o por debajo del límite especificado.

Parámetros de Consulta (Query Params):

limite (opcional, por defecto 5)

Respuesta Exitosa (200 OK - Alerta):

{
    "limite_stock_analizado": 5,
    "productos": [
        {
            "id": 15,
            "nombre": "Leche Entera 1L",
            "stock_actual": 3,
            "unidad_medida": "unidad",
            "categoria": "Bebidas y Lácteos"
        }
    ]
}


2. Módulo de Autenticación y Usuarios

<<<<<<< HEAD






2.  Pruebas de Autenticación y Usuarios
2.1.  Registro de Nuevo Usuario (POST /register)
Esta prueba te dará el id_usuario necesario para el resto de transacciones.
Método	Endpoint	Body (JSON)	Resultado Esperado
POST	{{BASE_URL}}?resource=register	(Ver JSON)	201 Created. Retorna el id_usuario insertado.
JSON
{
  "nombre": "Jonathan",
  "apellido": "Sandoval",
  "usuario": "jsandoval",
  "contrasena": "MiClaveSegura123",
  "rol": "administrador"
}


2.2.  Login de Usuario (POST /login)
Método	Endpoint	Body (JSON)	Resultado Esperado
POST	{{BASE_URL}}?resource=login	(Ver JSON)	200 OK. Retorna los datos del usuario (sin contraseña).
JSON
{
  "usuario": "jsandoval",
  "contrasena": "MiClaveSegura123"
}
________________________________________
3.  Pruebas de Gestión de Inventario (Productos)
3.1.  Crear Categoría (POST /categorias)
Método	Endpoint	Body (JSON)	Resultado Esperado
POST	{{BASE_URL}}?resource=categorias	{"nombre_categoria": "Pan Básico", "descripcion": "Panes de consumo diario"}	201 Created. Guarda el id_categoria devuelto (Ej: 1).

3.2. ➕ Crear Producto (POST /productos)
Método	Endpoint	Body (JSON)	Resultado Esperado
POST	{{BASE_URL}}?resource=productos	(Ver JSON)	201 Created. Guarda el id_producto devuelto (Ej: 101).
JSON
{
  "nombre": "Baguette",
  "descripcion": "Pan largo y crujiente",
  "precio": 1.25,
  "stock": 100,
  "unidad_medida": "unidad",
  "id_categoria": 1 
}
3.3.  Actualizar Producto (PUT /productos/{id})
Método	Endpoint	Body (JSON)	Resultado Esperado
PUT	{{BASE_URL}}?resource=productos&id=101	{"precio": 1.30, "descripcion": "Baguette recién horneada."}	200 OK. Mensaje de actualización exitosa.

















4.  Prueba de Transacción Completa (Venta)
Esta prueba verifica que los controladores (VentaController, DetalleVenta.php) y modelos (Producto.php) realicen una transacción atómica correcta (crear venta, crear detalle, reducir stock).
4.1. ➕ Crear Cliente (POST /clientes)
Método	Endpoint	Body (JSON)	Resultado Esperado
POST	{{BASE_URL}}?resource=clientes	{
    "nombre": "Maria",
     "apellido": "Gomez",
     "telefono": "3224567890", 
     "email": "m.gomez@mail.com", 
     "direccion": "cra 3 # 12-22"}
	201 Created. Guarda el id_cliente devuelto (Ej: 200).
4.2.  Registrar Venta (POST /ventas)
La venta debe usar el id_usuario (de tu registro), el id_cliente (de 4.1) y el id_producto (de 3.2).
Método	Endpoint	Body (JSON)	Resultado Esperado
POST	{{BASE_URL}}?resource=ventas	(Ver JSON)	201 Created. Retorna venta_id, total y la lista de productos vendidos.
JSON
{
  "id_cliente": 14,
  "id_usuario": 2, 
  "productos": [
    {
      "id_producto": 14,
      "cantidad": 5
    }
  ]
}


Verificación clave: Después de esta prueba, si hiciste un GET /productos&id=101, el stock de Baguette debe haber bajado de 100 a 95.
=======
2.1 Registrar Nuevo Usuario

Endpoint: POST /register

Descripción: Crea un nuevo usuario. La contraseña se hashea usando password_hash (BCRYPT).

Cuerpo de la Petición (JSON):

nombre_usuario, password, nombre_completo son obligatorios.

rol es opcional, por defecto es 'vendedor'.

{
    "nombre_usuario": "admin.celeste",
    "password": "miPasswordSeguro123",
    "nombre_completo": "Carlos Administrador",
    "rol": "administrador"
}


Respuesta Exitosa (201 Created):

{
    "message": "Usuario registrado exitosamente. Rol: administrador"
}


2.2 Iniciar Sesión (Login)

Endpoint: POST /login

Descripción: Autentica al usuario verificando la contraseña.

Cuerpo de la Petición (JSON):

{
    "nombre_usuario": "admin.celeste",
    "password": "miPasswordSeguro123"
}


Respuesta Exitosa (200 OK):

{
    "message": "Inicio de sesión exitoso.",
    "user": {
        "id": 1,
        "nombre_completo": "Carlos Administrador",
        "rol": "administrador"
    },
    "token": "JWT_GENERADO_AQUI_PARA_SEGURIDAD" 
}


3. Módulo de Categorías (/categorias)

3.1 Listar Categorías Activas

Endpoint: GET /categorias

Descripción: Recupera la lista de todas las categorías de producto cuyo estado es 'activo'.

Respuesta Exitosa (200 OK):

[
    {
        "id": 1,
        "nombre": "Panadería Tradicional",
        "descripcion": "Panes básicos y de molde.",
        "estado": "activo"
    },
    {...}
]


3.2 Obtener Detalle de Categoría por ID

Endpoint: GET /categorias/{id}

Descripción: Obtiene los detalles de una categoría específica.

Respuesta Exitosa (200 OK):

{
    "id": 2,
    "nombre": "Pastelería Fina",
    "descripcion": "Tortas, postres y dulces especiales.",
    "estado": "activo"
}


3.3 Crear Nueva Categoría

Endpoint: POST /categorias

Cuerpo de la Petición (JSON):

nombre es obligatorio. descripcion es opcional.

{
    "nombre": "Bebidas Calientes",
    "descripcion": "Café, chocolate y aromáticas."
}


Respuesta Exitosa (201 Created):

{
    "message": "Categoría registrada exitosamente."
}


3.4 Actualizar Categoría

Endpoint: PUT/PATCH /categorias/{id}

Descripción: Permite actualizar el nombre y/o la descripción.

Cuerpo de la Petición (JSON - Ejemplo PATCH):

{
    "nombre": "Bebidas y Jugos"
}


Respuesta Exitosa (200 OK):

{
    "message": "Categoría actualizada exitosamente."
}


3.5 Inactivar Categoría (Borrado Lógico)

Endpoint: DELETE /categorias/{id}

Descripción: Cambia el estado de la categoría a 'inactivo'.

Respuesta Exitosa (200 OK):

{
    "message": "Categoría inactivada exitosamente."
}


4. Módulo de Productos (/productos)

4.1 Listar Productos Activos

Endpoint: GET /productos

Descripción: Recupera la lista de todos los productos cuyo estado es 'activo', incluyendo el nombre de su categoría.

Respuesta Exitosa (200 OK):

[
    {
        "id": 1,
        "nombre": "Pan Francés",
        "precio": 1000.00,
        "stock": 150,
        "unidad_medida": "unidad",
        "categoria_nombre": "Panadería Tradicional",
        "estado": "activo"
    },
    {...}
]


4.2 Obtener Detalle de Producto por ID

Endpoint: GET /productos/{id}

Descripción: Obtiene los detalles completos de un producto.

Respuesta Exitosa (200 OK):

{
    "id": 5,
    "nombre": "Torta de Zanahoria",
    "precio": 35000.00,
    "stock": 5,
    "categoria_id": 3,
    "unidad_medida": "porción",
    "estado": "activo"
}


4.3 Crear Nuevo Producto

Endpoint: POST /productos

Cuerpo de la Petición (JSON):

precio y stock deben ser números positivos.

{
    "nombre": "Croissant de Chocolate",
    "precio": 4500.00,
    "stock": 80,
    "categoria_id": 4,
    "unidad_medida": "unidad"
}


Respuesta Exitosa (201 Created):

{
    "message": "Producto registrado exitosamente."
}


4.4 Actualizar Producto

Endpoint: PUT/PATCH /productos/{id}

Descripción: Permite actualizar uno o varios campos del producto.

Cuerpo de la Petición (JSON - Ejemplo PATCH):

{
    "precio": 4800.00,
    "stock": 100
}


Respuesta Exitosa (200 OK):

{
    "message": "Producto actualizado exitosamente."
}


4.5 Inactivar Producto (Borrado Lógico)

Endpoint: DELETE /productos/{id}

Descripción: Cambia el estado del producto a 'inactivo'.

Respuesta Exitosa (200 OK):

{
    "message": "Producto inactivado exitosamente."
}


5. Módulo de Ventas (/ventas)

5.1 Registrar Nueva Venta (Transacción)

Endpoint: POST /ventas

Descripción: Inicia una transacción de venta. Crea el encabezado, los detalles de venta y actualiza el stock de productos de forma atómica (transaccional).

Cuerpo de la Petición (JSON):

cliente_id: ID del cliente al que se le realiza la venta.

productos: Array de objetos con el ID del producto y la cantidad a vender.

{
    "cliente_id": 5,
    "productos": [
        {
            "producto_id": 10,
            "cantidad": 2
        }
    ]
}


Respuestas Exitosa (201 Created):

{
    "message": "Venta registrada exitosamente.",
    "venta_id": 123,
    "total": 35500.00,
    "detalle": [
        {"producto_id": 10, "nombre": "Pan Francés", "cantidad": 2}
    ]
}


Respuesta de Error (500 Internal Server Error):

{
    "message": "Transacción de venta fallida.",
    "error_detail": "Error: Stock insuficiente para el producto ID 10."
}


5.2 Listar Todas las Ventas

Endpoint: GET /ventas

Descripción: Obtiene un listado de los encabezados de todas las ventas activas, ordenadas por fecha reciente.

Respuesta Exitosa (200 OK):

[
    {
        "id": 123,
        "fecha_venta": "2025-12-03 16:15:00",
        "total": 35500.00,
        "estado": "activa",
        "cliente": "Laura García"
    }
]


5.3 Obtener Detalle de una Venta/Factura

Endpoint: GET /ventas/{id}

Descripción: Recupera la información completa de una venta, incluyendo el encabezado y el listado de productos vendidos.

Respuesta Exitosa (200 OK):

{
    "id": 123,
    "cliente_id": 5,
    "nombre_cliente": "Laura García",
    "fecha_venta": "2025-12-03 16:15:00",
    "total_factura": 35500.00,
    "estado": "activa",
    "detalle_productos": [
        {
            "producto_id": 10,
            "nombre_producto": "Pan Francés",
            "unidad_medida": "unidad",
            "cantidad": 2,
            "precio_unitario": 10000.00,
            "subtotal": 20000.00
        }
    ]
}


6. Módulo de Proveedores (/proveedores)

6.1 Listar Proveedores Activos

Endpoint: GET /proveedores

Descripción: Recupera la lista de todos los proveedores cuyo estado es 'activo'.

Respuesta Exitosa (200 OK):

[
    {
        "id": "1",
        "nombre_empresa": "Distribuidora La Vaca",
        "contacto_principal": "Juan Pérez",
        "telefono": "3001234567",
        "email": "contacto@vaca.com",
        "nit": "900111222-3",
        "direccion": "Calle 10 # 5-40",
        "estado": "activo"
    }
]


6.2 Crear Nuevo Proveedor

Endpoint: POST /proveedores

Cuerpo de la Petición (JSON):

{
    "nombre_empresa": "Granos Colombia S.A.S",
    "contacto_principal": "Maria Lopez",
    "telefono": "3109876543",
    "email": "maria@granoscol.com",
    "nit": "890123456-7",
    "direccion": "Carrera 5 # 2-10"
}


Respuesta Exitosa (201 Created):

{
    "message": "Proveedor registrado exitosamente."
}


6.3 Actualizar Proveedor

Endpoint: PUT/PATCH /proveedores/{id}

Descripción: Permite actualizar uno o varios campos del proveedor.

Cuerpo de la Petición (JSON - Ejemplo PATCH):

{
    "telefono": "3019876543"
}


Respuesta Exitosa (200 OK):

{
    "message": "Proveedor actualizado exitosamente."
}


6.4 Inactivar Proveedor (Borrado Lógico)

Endpoint: DELETE /proveedores/{id}

Descripción: Cambia el estado del proveedor a 'inactivo'.

Respuesta Exitosa (200 OK):

{
    "message": "Proveedor inactivado exitosamente."
}


7. Módulo de Clientes (/clientes)

7.1 Listar Clientes Activos

Endpoint: GET /clientes

Descripción: Recupera la lista de todos los clientes cuyo estado es 'activo'.

Respuesta Exitosa (200 OK):

[
    {
        "id": 1,
        "nombre_completo": "Laura García",
        "documento": "1002345678",
        "telefono": "3128765432",
        "email": "laura.garcia@email.com",
        "direccion": "Calle 10 # 15-20",
        "estado": "activo"
    },
    {...}
]


7.2 Obtener Detalle de Cliente por ID

Endpoint: GET /clientes/{id}

Descripción: Obtiene los detalles completos de un cliente específico.

Respuesta Exitosa (200 OK):

{
    "id": 5,
    "nombre": "Pedro",
    "apellido": "Martínez",
    "documento": "9876543210",
    "telefono": "3001234567",
    "email": "pedro.martinez@email.com",
    "direccion": "Carrera 20 # 5-50",
    "estado": "activo"
}


Respuesta de Error (404 Not Found):

{
    "message": "Cliente con ID 999 no existe o está inactivo."
}


7.3 Crear Nuevo Cliente

Endpoint: POST /clientes

Cuerpo de la Petición (JSON):

nombre, apellido, documento y telefono son obligatorios.

email y direccion son opcionales.

{
    "nombre": "Ana",
    "apellido": "Pérez",
    "documento": "1234567890",
    "telefono": "3015551234",
    "email": "ana.perez@cliente.com"
}


Respuesta Exitosa (201 Created):

{
    "message": "Cliente registrado exitosamente."
}


Respuesta de Error (400 Bad Request):

{
    "message": "Error. Datos incompletos. Se requiere nombre, apellido, documento y teléfono."
}


7.4 Actualizar Cliente

Endpoint: PUT/PATCH /clientes/{id}

Descripción: Permite actualizar uno o varios campos del cliente.

Cuerpo de la Petición (JSON - Ejemplo PATCH):

{
    "telefono": "3209998877",
    "direccion": "Avenida 34 No 10-20"
}


Respuesta Exitosa (200 OK):

{
    "message": "Cliente actualizado exitosamente."
}


7.5 Inactivar Cliente (Borrado Lógico)

Endpoint: DELETE /clientes/{id}

Descripción: Cambia el estado del cliente a 'inactivo'.

Respuesta Exitosa (200 OK):

{
    "message": "Cliente inactivado exitosamente."
}
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
