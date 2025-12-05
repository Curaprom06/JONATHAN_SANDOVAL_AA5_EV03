<?php
// Controlador para manejar las peticiones del recurso /ventas
// Este controlador orquesta la transacción de la venta (Encabezado, Detalle y Stock)
// Y maneja la lectura (reportes y detalles de facturas)

// Incluir Modelos necesarios
require_once '../app/Models/Venta.php';
require_once '../app/Models/DetalleVenta.php';
<<<<<<< HEAD
require_once '../app/Models/Producto.php'; // Necesario para obtener precio/stock y actualizarlo
=======
require_once '../app/Models/Producto.php'; // Necesario para actualizar el stock
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

class VentaController {
    private $db;
    private $venta;
    private $detalleVenta;
    private $producto;

    public function __construct() {
        // Inicializa la conexión y los modelos
        $database = new Database();
        $this->db = $database->getConnection();
        $this->venta = new Venta($this->db);
        $this->detalleVenta = new DetalleVenta($this->db);
        $this->producto = new Producto($this->db);
    }

    public function handleRequest($method, $id) {
        switch ($method) {
            case 'POST':
                $this->createSale();
                break;
            case 'GET':
                 if ($id && is_numeric($id)) {
                    $this->readOne($id);
                } else {
                    $this->readAll();
                }
                break;
            default:
                http_response_code(405); 
                echo json_encode(["message" => "Método {$method} no permitido para el recurso Ventas."]);
                break;
        }
    }

<<<<<<< HEAD
    // [GET] /ventas - Lista todas las ventas
    private function readAll() {
        $stmt = $this->venta->read();
=======
    // --- Lógica de Lectura (GET) ---

    // [GET] /ventas - Obtiene la lista de todas las ventas
    private function readAll() {
        $stmt = $this->venta->readAll();
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        $num = $stmt->rowCount();

        if ($num > 0) {
            $ventas_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
<<<<<<< HEAD
                // Se incluye solo la información esencial de la venta
                $item = array(
                    "id_venta" => (int)$row['id_venta'],
                    "fecha_venta" => $row['fecha_venta'],
                    "total_venta" => (float)$row['total_venta'],
                    "nombre_cliente" => $row['nombre_cliente'] ?? 'N/A', // Nombre del cliente, si existe
                    "nombre_vendedor" => $row['nombre_vendedor'] ?? 'N/A' // Nombre del vendedor
                );
                array_push($ventas_arr, $item);
=======
                extract($row);
                $venta_item = array(
                    "id" => (int)$id,
                    "fecha_venta" => $fecha_venta,
                    "total" => (float)$total,
                    "estado" => $estado,
                    "cliente" => $nombre_cliente . ' ' . $apellido_cliente
                );
                array_push($ventas_arr, $venta_item);
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            }

            http_response_code(200);
            echo json_encode($ventas_arr);
        } else {
            http_response_code(404);
<<<<<<< HEAD
            echo json_encode(["message" => "No se encontraron ventas registradas."]);
        }
    }

    // [GET] /ventas/{id} - Muestra el detalle de una venta específica (factura)
    private function readOne($id) {
        $this->venta->id_venta = $id;

        // 1. Leer el Encabezado de Venta
        $venta_data = $this->venta->readOne();

        if (!$venta_data) {
            http_response_code(404);
            echo json_encode(["message" => "Venta con ID {$id} no encontrada."]);
            return;
        }

        // 2. Leer los Detalles de Venta (productos)
        $stmt_detalle = $this->detalleVenta->readByVentaId($id);
        $detalle_arr = array();
        while ($row = $stmt_detalle->fetch(PDO::FETCH_ASSOC)) {
            $item = array(
                "id_detalle" => (int)$row['id_detalle'],
                "id_producto" => (int)$row['id_producto'],
                "nombre_producto" => $row['nombre_producto'],
                "cantidad" => (int)$row['cantidad'],
                "precio_unitario" => (float)$row['precio_unitario'],
                "subtotal" => (float)$row['subtotal']
            );
            array_push($detalle_arr, $item);
        }

        // 3. Compilar la Respuesta
        $response = [
            "encabezado" => $venta_data,
            "detalle" => $detalle_arr
        ];

        http_response_code(200);
        echo json_encode($response);
    }


    // [POST] /ventas - Registra una nueva venta
    private function createSale() {
        // Obtener datos del cuerpo de la petición (JSON)
        $data = json_decode(file_get_contents("php://input"));

        // Validar datos mínimos
        if (empty($data->id_usuario) || empty($data->productos) || !is_array($data->productos) || count($data->productos) === 0) {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos. Se requieren id_usuario y un array de productos."]);
            return;
        }

        // --- INICIO DE LA TRANSACCIÓN ---
        $this->db->beginTransaction();

        try {
            // 1. Crear Encabezado de Venta (se inicia con total 0)
            // Asigna id_cliente (puede ser null) y id_usuario (obligatorio) al objeto Venta
            $this->venta->id_cliente = $data->id_cliente ?? null; 
            $this->venta->id_usuario = (int)$data->id_usuario;

            // Se llama a create() UNA SOLA VEZ (se eliminó el bloque duplicado)
            $venta_id = $this->venta->create(); 
            
            if (!$venta_id) {
                throw new Exception("Error al crear el encabezado de venta.");
            }
            
            $total_venta = 0;
            $productos_vendidos = [];

            // 2. Procesar Detalle de Venta y Actualizar Stock
            foreach ($data->productos as $item) {
                // Validación básica del item. Se usa id_producto para coincidir con el JSON.
                if (empty($item->id_producto) || empty($item->cantidad) || !is_numeric($item->cantidad) || $item->cantidad <= 0) {
                    throw new Exception("Datos de producto inválidos: ID o cantidad nulos/cero.");
                }

                $id_producto = (int)$item->id_producto;
                $cantidad = (int)$item->cantidad;

                // A. Verificar Producto y Stock Disponible
                $this->producto->id_producto = $id_producto;
                
                // readOne() carga las propiedades del producto (incluyendo stock y precio)
                if (!$this->producto->readOne()) {
                    throw new Exception("El producto ID {$id_producto} no existe.");
                }

                // CORRECCIÓN: Se usa $this->producto->estado_producto y se compara con 'Activo' (con mayúscula inicial)
                if ($this->producto->estado_producto !== 'Activo') { 
                    throw new Exception("El producto ID {$id_producto} no está activo. Estado actual: {$this->producto->estado_producto}");
                }

                if ($this->producto->stock < $cantidad) {
                    throw new Exception("Stock insuficiente para el producto ID {$id_producto}. Stock disponible: {$this->producto->stock}.");
                }

                $precio_unitario = (float)$this->producto->precio;
                $subtotal = $precio_unitario * $cantidad;
                
                // B. Actualizar Stock
                $nuevo_stock = $this->producto->stock - $cantidad;
                if (!$this->producto->updateStock($id_producto, $nuevo_stock)) {
                    throw new Exception("Error al actualizar el stock para el producto ID {$id_producto}.");
                }

                // C. Crear Detalle de Venta
                if (!$this->detalleVenta->create($venta_id, $id_producto, $cantidad, $precio_unitario, $subtotal)) {
                    throw new Exception("Error al crear el detalle para el producto ID {$id_producto}.");
=======
            echo json_encode(["message" => "No se encontraron ventas activas."]);
        }
    }

    // [GET] /ventas/{id} - Obtiene el detalle completo de una factura
    private function readOne($id) {
        $this->venta->id = $id;

        // 1. Leer Encabezado de Venta
        if (!$this->venta->readOne()) {
            http_response_code(404);
            echo json_encode(["message" => "Venta con ID {$id} no encontrada o inactiva."]);
            return;
        }
        
        // 2. Leer Detalles de Venta
        $stmt_detalle = $this->detalleVenta->readByVentaId($id);
        $num_detalle = $stmt_detalle->rowCount();
        $detalles_arr = [];

        if ($num_detalle > 0) {
            while ($row = $stmt_detalle->fetch(PDO::FETCH_ASSOC)) {
                $detalles_arr[] = [
                    "producto_id" => (int)$row['producto_id'],
                    "nombre_producto" => $row['nombre_producto'],
                    "unidad_medida" => $row['unidad_medida'],
                    "cantidad" => (int)$row['cantidad'],
                    "precio_unitario" => (float)$row['precio_unitario'],
                    "subtotal" => (float)$row['subtotal']
                ];
            }
        }
        
        // 3. Compilar la respuesta final
        $venta_arr = array(
            "id" => (int)$id,
            "cliente_id" => (int)$this->venta->cliente_id,
            "nombre_cliente" => $this->venta->nombre_cliente_completo,
            "fecha_venta" => $this->venta->fecha_venta,
            "total_factura" => (float)$this->venta->total,
            "estado" => $this->venta->estado,
            "detalle_productos" => $detalles_arr
        );

        http_response_code(200);
        echo json_encode($venta_arr);
    }
    
    // --- Lógica de Creación (POST) ---

    // [POST] /ventas - Crea una nueva venta con lógica transaccional
    private function createSale() {
        $data = json_decode(file_get_contents("php://input"));
        $total_venta = 0;
        $productos_vendidos = [];

        // 1. Validaciones iniciales
        if (empty($data->cliente_id) || !isset($data->productos) || !is_array($data->productos) || empty($data->productos)) {
            http_response_code(400); 
            echo json_encode(["message" => "Error. Datos incompletos. Se requiere cliente_id y un array de productos."]);
            return;
        }

        // 2. Iniciar Transacción
        // Esto asegura que o todo se guarda o todo se revierte (Atomicidad)
        $this->db->beginTransaction();
        
        try {
            // 3. Crear Encabezado de Venta
            $this->venta->cliente_id = $data->cliente_id;
            $venta_id = $this->venta->create(); // Obtiene el ID generado
            
            if (!$venta_id) {
                throw new Exception("Error al crear el encabezado de la venta.");
            }

            // 4. Procesar Detalles y Actualizar Stock
            foreach ($data->productos as $item) {
                
                // Validación básica de campos requeridos en el detalle
                if (empty($item->producto_id) || empty($item->cantidad) || $item->cantidad <= 0) {
                     throw new Exception("Error: Producto o cantidad inválida en el detalle.");
                }

                $this->producto->id = $item->producto_id;
                
                // A. Obtener detalles del producto (principalmente precio)
                if (!$this->producto->readOne()) {
                    throw new Exception("Error: Producto con ID {$item->producto_id} no encontrado o inactivo.");
                }
                
                $precio_unitario = $this->producto->precio;
                $subtotal = $precio_unitario * $item->cantidad;
                
                // B. Actualizar Stock: Si la cantidad es mayor al stock disponible o falla, updateStock retorna false
                // Llama al método updateStock del modelo Producto (asumiendo que existe)
                if (!$this->producto->updateStock($item->producto_id, $item->cantidad)) {
                    // Esta condición se activa si el stock es insuficiente o la DB falla
                    throw new Exception("Error: Stock insuficiente para el producto ID {$item->producto_id}.");
                }
                
                // C. Crear Detalle de Venta
                $this->detalleVenta->venta_id = $venta_id;
                $this->detalleVenta->producto_id = $item->producto_id;
                $this->detalleVenta->cantidad = $item->cantidad;
                $this->detalleVenta->precio_unitario = $precio_unitario;
                $this->detalleVenta->subtotal = $subtotal;

                if (!$this->detalleVenta->create()) {
                    throw new Exception("Error al crear el detalle para el producto ID {$item->producto_id}.");
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
                }

                // D. Acumular Total
                $total_venta += $subtotal;
                
                $productos_vendidos[] = [
<<<<<<< HEAD
                    'id_producto' => $id_producto,
                    'nombre' => $this->producto->nombre,
                    'cantidad' => $cantidad
                ];
            }

            // 3. Actualizar el Total en el Encabezado de Venta
=======
                    'producto_id' => $item->producto_id,
                    'nombre' => $this->producto->nombre,
                    'cantidad' => $item->cantidad
                ];
            }

            // 5. Actualizar el Total en el Encabezado de Venta
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            if (!$this->venta->updateTotal($venta_id, $total_venta)) {
                throw new Exception("Error al actualizar el total de la venta ID {$venta_id}.");
            }

<<<<<<< HEAD
            // 4. Confirmar Transacción (COMMIT)
=======
            // 6. Confirmar Transacción (COMMIT)
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            $this->db->commit();

            http_response_code(201);
            echo json_encode([
                "message" => "Venta registrada exitosamente.",
                "venta_id" => $venta_id,
                "total" => $total_venta,
                "detalle" => $productos_vendidos
            ]);

        } catch (Exception $e) {
<<<<<<< HEAD
            // 5. Revertir Transacción (ROLLBACK) si hay cualquier error
=======
            // 7. Revertir Transacción (ROLLBACK) si hay cualquier error
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

<<<<<<< HEAD
            // Devolver respuesta de error al cliente
            http_response_code(400); 
            echo json_encode([
                "message" => "Transacción de venta fallida.",
                "error" => $e->getMessage()
            ]);
=======
            http_response_code(500);
            echo json_encode(["message" => "Transacción de venta fallida.", "error_detail" => $e->getMessage()]);
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        }
    }
}
?>