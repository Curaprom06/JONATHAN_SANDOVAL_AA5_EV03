<?php
// Controlador para manejar todas las peticiones (GET, POST, PUT, DELETE) del recurso /productos

// Incluir Modelos necesarios
require_once '../app/Models/Producto.php'; 
<<<<<<< HEAD
// Asumimos que Database.php se carga en index.php o via Autoload, pero lo mencionamos para claridad.
// require_once '../app/Config/Database.php'; 
=======
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

class ProductoController {
    private $db;
    private $producto;

    public function __construct() {
        // Inicializa la conexión y el modelo
<<<<<<< HEAD
        $database = new Database(); // Asumiendo que Database está disponible
=======
        $database = new Database();
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        $this->db = $database->getConnection();
        // Asumimos que el modelo Producto ya ha sido definido
        $this->producto = new Producto($this->db);
    }

<<<<<<< HEAD
    /**
     * Función principal que enruta la solicitud HTTP al método CRUD apropiado.
     * @param string $method Método HTTP (GET, POST, PUT, DELETE).
     * @param int|string|null $id ID del producto si aplica (para readOne, update, delete).
     */
=======
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
    public function handleRequest($method, $id) {
        switch ($method) {
            case 'GET':
                if ($id && is_numeric($id)) {
                    $this->readOne($id);
                } else {
                    $this->readAll();
                }
                break;
            case 'POST':
                $this->create();
                break;
            case 'PUT':
            case 'PATCH':
                $this->update($id); 
                break;
            case 'DELETE':
                $this->delete($id);
                break;
            default:
                http_response_code(405); 
                echo json_encode(["message" => "Método {$method} no permitido para el recurso Productos."]);
                break;
        }
    }

    // --- Lógica del CRUD ---

<<<<<<< HEAD
    // [GET] /productos - Listar todos los productos
=======
    // [GET] /productos - Listar todos los productos activos
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
    private function readAll() {
        // Se asume que el método read() existe en Producto.php y hace un JOIN con categorías
        $stmt = $this->producto->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $productos_arr = array();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
<<<<<<< HEAD
                // Extraer la fila
=======
                // Extraer los datos de la fila
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
                extract($row);

                $producto_item = array(
                    "id" => (int)$id,
                    "nombre" => $nombre,
                    "precio" => (float)$precio,
                    "stock" => (int)$stock,
                    "unidad_medida" => $unidad_medida,
                    "estado" => $estado,
                    "id_categoria" => (int)$id_categoria, 
                    "categoria_nombre" => $categoria_nombre // Viene del JOIN
                );

                array_push($productos_arr, $producto_item);
            }

            http_response_code(200);
            echo json_encode($productos_arr);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "No se encontraron productos activos."]);
        }
    }

<<<<<<< HEAD
    // [GET] /productos/{id} - Obtener un producto por ID
    private function readOne($id) {
        $this->producto->id_producto = $id;

        if ($this->producto->readOne()) {
            // Producto encontrado, devolver sus propiedades.
=======
    // [GET] /productos/{id} - Obtener detalles de un producto específico
    private function readOne($id) {
        $this->producto->id = $id;
        
        // Se asume que readOne() existe en Producto.php y carga las propiedades del objeto
        if ($this->producto->readOne()) {
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            $producto_arr = array(
                "id" => (int)$this->producto->id,
                "nombre" => $this->producto->nombre,
                "precio" => (float)$this->producto->precio,
                "id_categoria" => (int)$this->producto->id_categoria,
                "stock" => (int)$this->producto->stock,
<<<<<<< HEAD
                "unidad_medida" => $this->producto->unidad_medida,
                "estado" => $this->producto->estado,
                "categoria_nombre" => $this->producto->categoria_nombre 
=======
                "categoria_id" => (int)$this->producto->categoria_id,
                "unidad_medida" => $this->producto->unidad_medida,
                "estado" => $this->producto->estado
                // Nota: categoría_nombre solo está disponible en read() por el JOIN
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            );
            http_response_code(200);
            echo json_encode($producto_arr);
        } else {
            http_response_code(404);
<<<<<<< HEAD
            echo json_encode(["message" => "Producto con ID {$id} no encontrado."]);
=======
            echo json_encode(["message" => "Producto con ID {$id} no existe o está inactivo."]);
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        }
    }

    // [POST] /productos - Crear un nuevo producto
    private function create() {
<<<<<<< HEAD
        // Obtener datos enviados en formato JSON
        $data = json_decode(file_get_contents("php://input"));

        // 1. Validar datos requeridos y tipos
        if (
            !isset($data->nombre) || empty($data->nombre) ||
            !isset($data->precio) || !is_numeric($data->precio) ||
            !isset($data->stock) || !is_numeric($data->stock) ||
            !isset($data->id_categoria) || !is_numeric($data->id_categoria) ||
            !isset($data->unidad_medida) || empty($data->unidad_medida)
        ) {
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Error. Datos incompletos o inválidos para crear producto. Verifique nombre, precio, stock, id_categoria y unidad_medida."]);
=======
        $data = json_decode(file_get_contents("php://input"));

        // Validaciones: Campos obligatorios
        if (empty($data->nombre) || !isset($data->precio) || !isset($data->stock) || empty($data->categoria_id) || empty($data->unidad_medida)) {
            http_response_code(400); 
            echo json_encode(["message" => "Error. Datos incompletos. Se requiere nombre, precio, stock, categoria_id y unidad_medida."]);
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            return;
        }
        
        // Validación de tipo de datos
        if (!is_numeric($data->precio) || !is_numeric($data->stock) || $data->precio < 0 || $data->stock < 0) {
             http_response_code(400); 
             echo json_encode(["message" => "Error. Precio y stock deben ser números positivos."]);
             return;
        }

<<<<<<< HEAD
        // 2. Asignar propiedades al objeto Producto
=======
        // Asignar valores
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        $this->producto->nombre = $data->nombre;
        $this->producto->descripcion = $data->descripcion; 
        $this->producto->precio = $data->precio;
        $this->producto->stock = $data->stock;
<<<<<<< HEAD
        $this->producto->id_categoria = $data->id_categoria;
        $this->producto->unidad_medida = $data->unidad_medida;
        // El estado se asume 'activo' por defecto en el modelo

        // 3. Intentar crear el producto
        if ($this->producto->create()) {
            http_response_code(201); // Created
            echo json_encode(["message" => "Producto creado exitosamente."]);
        } else {
            http_response_code(503); // Service Unavailable
            echo json_encode(["message" => "No se pudo crear el producto. Problema con la base de datos o datos inválidos."]);
        }
    }

=======
        $this->producto->categoria_id = $data->categoria_id;
        $this->producto->unidad_medida = $data->unidad_medida;
        // El estado se asume 'activo' por defecto en el modelo o la DB

        // Se asume que create() existe en Producto.php
        if ($this->producto->create()) {
            http_response_code(201);
            echo json_encode(["message" => "Producto registrado exitosamente."]);
        } else {
            http_response_code(503); 
            echo json_encode(["message" => "No se pudo registrar el producto."]);
        }
    }
    
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
    // [PUT/PATCH] /productos/{id} - Actualizar un producto existente
    private function update($id) {
        // Obtener datos enviados
        $data = json_decode(file_get_contents("php://input"));

        // 1. Validar ID
        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(["message" => "Error. ID de producto no proporcionado o inválido para actualizar."]);
            return;
        }
        
<<<<<<< HEAD
        $this->producto->id_producto = $id;

        // 2. Cargar los datos existentes para permitir actualizaciones parciales (PATCH)
=======
        // 1. Verificar si el producto existe
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        if (!$this->producto->readOne()) {
            http_response_code(404);
            echo json_encode(["message" => "Producto con ID {$id} no encontrado."]);
            return;
        }

<<<<<<< HEAD
        // 3. Asignar nuevos valores (si se proporcionaron)
=======
        // 2. Asignar datos del body o mantener los existentes
        // Esto permite actualizaciones parciales (PATCH)
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        $this->producto->nombre = $data->nombre ?? $this->producto->nombre;
        $this->producto->descripcion = $data->descripcion ?? $this->producto->descripcion;
        $this->producto->precio = $data->precio ?? $this->producto->precio;
        $this->producto->stock = $data->stock ?? $this->producto->stock;
<<<<<<< HEAD
        $this->producto->id_categoria = $data->id_categoria ?? $this->producto->id_categoria;
        $this->producto->unidad_medida = $data->unidad_medida ?? $this->producto->unidad_medida;
        $this->producto->estado_producto = $data->estado_producto ?? $this->producto->estado_producto;
        
        // ** Validaciones Adicionales **
        // Verificar que los campos numéricos sigan siendo numéricos y obligatorios
        if (
            empty($this->producto->nombre) || 
            !is_numeric($this->producto->precio) || 
            !is_numeric($this->producto->stock) || 
            !is_numeric($this->producto->id_categoria) || 
            empty($this->producto->unidad_medida)
        ) {
             http_response_code(400); 
             echo json_encode(["message" => "Error. Todos los campos requeridos (nombre, precio, stock, id_categoria, unidad_medida) deben tener un valor válido."]);
             return;
        }
        
        // 4. Ejecutar actualización
=======
        $this->producto->categoria_id = $data->categoria_id ?? $this->producto->categoria_id;
        $this->producto->unidad_medida = $data->unidad_medida ?? $this->producto->unidad_medida;
        
        // 3. Validación de datos después de la asignación
        if ((isset($data->precio) && !is_numeric($data->precio)) || (isset($data->stock) && !is_numeric($data->stock))) {
            http_response_code(400); 
            echo json_encode(["message" => "Error. Precio y stock deben ser valores numéricos."]);
            return;
        }
        
        // 4. Ejecutar actualización
        // Se asume que update() existe en Producto.php
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        if ($this->producto->update()) {
            http_response_code(200);
            echo json_encode(["message" => "Producto actualizado exitosamente."]);
        } else {
            // Si no hubo cambios o error interno
            http_response_code(200);
            echo json_encode(["message" => "Producto actualizado (o sin cambios)." ]);
        }
    }

    // [DELETE] /productos/{id} - Inactivar un producto (Borrado lógico)
    private function delete($id) {
<<<<<<< HEAD
        // 1. Validar ID
        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(["message" => "Error. ID de producto no proporcionado o inválido para inactivar."]);
            return;
        }

        $this->producto->id_producto = $id;

        // 2. Verificar si el producto existe y está activo
        if (!$this->producto->readOne() || $this->producto->estado !== 'Activo') {
            http_response_code(404);
            echo json_encode(["message" => "Producto con ID {$id} no encontrado o ya está inactivo."]);
            return;
        }
        
        // 3. Ejecutar inactivación
=======
        $this->producto->id = $id;

        // 1. Verificar si el producto existe y está activo
        if (!$this->producto->readOne() || $this->producto->estado !== 'activo') {
            http_response_code(404);
            echo json_encode(["message" => "Producto con ID {$id} no encontrado o ya está inactivo."]);
            return;
        }
        
        // 2. Ejecutar inactivación
        // Se asume que delete() existe en Producto.php y cambia el estado a 'inactivo'
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        if ($this->producto->delete()) {
            http_response_code(200);
            echo json_encode(["message" => "Producto inactivado exitosamente."]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "No se pudo inactivar el producto."]);
        }
    }
}
?>