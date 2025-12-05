<?php
// Controlador para manejar todas las peticiones (GET, POST, PUT, DELETE) del recurso /clientes

// Incluir Modelo necesario (asumiendo que ya fue creado)
<<<<<<< HEAD
// Se asume que Autoload.php ya incluyó la clase Database
=======
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
require_once '../app/Models/Cliente.php'; 

class ClienteController {
    private $db;
    private $cliente;

    public function __construct() {
        // Inicializa la conexión y el modelo
<<<<<<< HEAD
        // Asumimos que Database es visible (por Autoload o inclusión)
        $database = new Database(); 
=======
        $database = new Database();
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        $this->db = $database->getConnection();
        $this->cliente = new Cliente($this->db);
    }

<<<<<<< HEAD
    /**
     * Función principal que dirige la solicitud al método CRUD adecuado.
     * * @param string $method El método HTTP de la solicitud (GET, POST, PUT, DELETE).
     * @param int|null $id El ID del recurso, si está presente en la URI.
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
<<<<<<< HEAD
                // Se asume que la operación DELETE no existe sin la columna 'estado' 
                // ya que implicaría un borrado físico, lo cual no es recomendable en APIs.
                // Si la tabla no tiene 'estado', deshabilitamos el borrado lógico.
                http_response_code(405);
                echo json_encode(["message" => "Método DELETE no permitido para Clientes sin columna de estado."]);
                break;
            default:
                http_response_code(405); // Método no permitido
=======
                $this->delete($id);
                break;
            default:
                http_response_code(405); 
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
                echo json_encode(["message" => "Método {$method} no permitido para el recurso Clientes."]);
                break;
        }
    }

    // --- Lógica del CRUD ---

<<<<<<< HEAD
    /**
     * [GET] /clientes - Listar todos los clientes.
     */
=======
    // [GET] /clientes - Listar todos los clientes activos
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
    private function readAll() {
        $stmt = $this->cliente->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $clientes_arr = array();
<<<<<<< HEAD

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Extrae la fila para convertir las propiedades en variables 
                extract($row);
                
                $cliente_item = array(
                    // Corregido: Usa la variable $id_cliente creada por extract()
                    "id" => (int)$id_cliente,
                    "nombre" => $nombre,
                    "apellido" => $apellido,
                    "telefono" => $telefono,
                    "email" => $email,
                    "direccion" => $direccion,
                    // Corregido: Usa la columna real de la base de datos
                    "fecha_creacion" => $fecha_registro
                );

=======
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $cliente_item = array(
                    "id" => (int)$id,
                    "nombre_completo" => $nombre . ' ' . $apellido,
                    "documento" => $documento,
                    "telefono" => $telefono,
                    "email" => $email,
                    "direccion" => $direccion,
                    "estado" => $estado
                );
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
                array_push($clientes_arr, $cliente_item);
            }

            http_response_code(200);
            echo json_encode($clientes_arr);
        } else {
            http_response_code(404);
<<<<<<< HEAD
            echo json_encode(["message" => "No se encontraron clientes."]);
        }
    }

    /**
     * [GET] /clientes/{id} - Obtener un único cliente por ID.
     * * @param int $id El ID del cliente.
     */
    private function readOne($id) {
        // Corregido: Asigna el parámetro $id, no $id_cliente (que no existe aquí)
        $this->cliente->id_cliente = $id; 
        
        if ($this->cliente->readOne()) {
            // Cliente encontrado, sus propiedades ya están cargadas en $this->cliente
            $cliente_item = array(
                // Corregido: Devuelve la propiedad correcta
                "id" => (int)$this->cliente->id_cliente,
                "nombre" => $this->cliente->nombre,
                "apellido" => $this->cliente->apellido,
                "telefono" => $this->cliente->telefono,
                "email" => $this->cliente->email,
                "direccion" => $this->cliente->direccion,
                // Eliminado: La línea "estado"
                // Corregido: Usa la propiedad cargada del modelo
                "fecha_creacion" => $this->cliente->fecha_registro
            );

            http_response_code(200);
            echo json_encode($cliente_item);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Cliente con ID {$id} no encontrado."]);
        }
    }

    /**
     * [POST] /clientes - Crear un nuevo cliente.
     */
    private function create() {
        // 1. Obtener datos del cuerpo de la solicitud
        $data = json_decode(file_get_contents("php://input"));

        // 2. Validar datos esenciales
        if (empty($data->nombre) || empty($data->telefono)) {
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Error. Faltan datos esenciales (nombre y teléfono)."]);
            return;
        }

        // 3. Asignar propiedades al objeto Cliente
        $this->cliente->nombre = $data->nombre;
        $this->cliente->apellido = $data->apellido;
        $this->cliente->telefono = $data->telefono;
        // Campos opcionales
        $this->cliente->email = $data->email ?? null;
        $this->cliente->direccion = $data->direccion ?? null;

        // ** Validaciones Adicionales **
        // Validar formato de email si se proporciona
        if (!empty($this->cliente->email) && !filter_var($this->cliente->email, FILTER_VALIDATE_EMAIL)) {
             http_response_code(400); 
             echo json_encode(["message" => "Error. El formato del correo electrónico es inválido."]);
             return;
        }

        // 4. Ejecutar creación
        $new_id = $this->cliente->create();
        if ($new_id) {
            http_response_code(201); // Created
            echo json_encode([
                "message" => "Cliente creado exitosamente.",
                "id" => (int)$new_id
            ]);
        } else {
            http_response_code(503); // Service Unavailable
            echo json_encode(["message" => "No se pudo crear el cliente. (Posible duplicado de teléfono o error de DB)."]);
        }
    }

    /**
     * [PUT/PATCH] /clientes/{id} - Actualizar un cliente.
     * * @param int $id El ID del cliente a actualizar.
     */
    private function update($id) {
        // 1. Obtener datos
        $data = json_decode(file_get_contents("php://input"));
        $this->cliente->id_cliente = $id;

        // 2. Verificar si el cliente existe y cargar datos actuales
        if (!$this->cliente->readOne()) {
            http_response_code(404);
            echo json_encode(["message" => "Cliente con ID {$id} no encontrado para actualizar."]);
            return;
        }

        // 3. Asignar nuevas propiedades (usa el valor actual si el nuevo es null o no existe en el payload)
        $this->cliente->nombre = $data->nombre ?? $this->cliente->nombre;
        $this->cliente->apellido = $data->apellido ?? $this->cliente->apellido;
        $this->cliente->telefono = $data->telefono ?? $this->cliente->telefono;
        $this->cliente->email = $data->email ?? $this->cliente->email;
        $this->cliente->direccion = $data->direccion ?? $this->cliente->direccion;
        // Eliminado: La línea de asignación de $data->estado

        // ** Validaciones Adicionales **
        // Validar que los campos obligatorios sigan llenos
        if (empty($this->cliente->nombre) || empty($this->cliente->telefono)) {
            http_response_code(400); 
            echo json_encode(["message" => "Error. El nombre completo y el teléfono no pueden estar vacíos."]);
            return;
        }
        
        // Validar formato de email si se proporciona
=======
            echo json_encode(["message" => "No se encontraron clientes activos."]);
        }
    }

    // [GET] /clientes/{id} - Obtener detalles de un cliente específico
    private function readOne($id) {
        $this->cliente->id = $id;
        
        if ($this->cliente->readOne()) {
            $cliente_arr = array(
                "id" => (int)$this->cliente->id,
                "nombre" => $this->cliente->nombre,
                "apellido" => $this->cliente->apellido,
                "documento" => $this->cliente->documento,
                "telefono" => $this->cliente->telefono,
                "email" => $this->cliente->email,
                "direccion" => $this->cliente->direccion,
                "estado" => $this->cliente->estado
            );
            http_response_code(200);
            echo json_encode($cliente_arr);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Cliente con ID {$id} no existe o está inactivo."]);
        }
    }

    // [POST] /clientes - Crear un nuevo cliente
    private function create() {
        $data = json_decode(file_get_contents("php://input"));

        // Validaciones: Campos obligatorios
        if (empty($data->nombre) || empty($data->apellido) || empty($data->documento) || empty($data->telefono)) {
            http_response_code(400); 
            echo json_encode(["message" => "Error. Datos incompletos. Se requiere nombre, apellido, documento y teléfono."]);
            return;
        }
        
        // Validación de formato de email (si se proporciona)
        if (!empty($data->email) && !filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
             http_response_code(400); 
             echo json_encode(["message" => "Error. El formato del correo electrónico no es válido."]);
             return;
        }

        // Asignar valores
        $this->cliente->nombre = $data->nombre;
        $this->cliente->apellido = $data->apellido;
        $this->cliente->documento = $data->documento;
        $this->cliente->telefono = $data->telefono;
        $this->cliente->email = $data->email ?? null; // Aceptar nulo si no se envía
        $this->cliente->direccion = $data->direccion ?? null;

        if ($this->cliente->create()) {
            http_response_code(201);
            echo json_encode(["message" => "Cliente registrado exitosamente."]);
        } else {
            http_response_code(503); 
            echo json_encode(["message" => "No se pudo registrar el cliente."]);
        }
    }
    
    // [PUT/PATCH] /clientes/{id} - Actualizar un cliente existente
    private function update($id) {
        $data = json_decode(file_get_contents("php://input"));
        $this->cliente->id = $id;
        
        // 1. Verificar si el cliente existe
        if (!$this->cliente->readOne()) {
            http_response_code(404);
            echo json_encode(["message" => "Cliente con ID {$id} no encontrado."]);
            return;
        }

        // 2. Asignar datos del body o mantener los existentes para permitir PATCH
        $this->cliente->nombre = $data->nombre ?? $this->cliente->nombre;
        $this->cliente->apellido = $data->apellido ?? $this->cliente->apellido;
        $this->cliente->documento = $data->documento ?? $this->cliente->documento;
        $this->cliente->telefono = $data->telefono ?? $this->cliente->telefono;
        $this->cliente->email = $data->email ?? $this->cliente->email;
        $this->cliente->direccion = $data->direccion ?? $this->cliente->direccion;
        
        // 3. Validación de formato de email al actualizar
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        if (!empty($this->cliente->email) && !filter_var($this->cliente->email, FILTER_VALIDATE_EMAIL)) {
             http_response_code(400); 
             echo json_encode(["message" => "Error. El formato del correo electrónico es inválido en la actualización."]);
             return;
        }

        // 4. Ejecutar actualización
        if ($this->cliente->update()) {
            http_response_code(200);
            echo json_encode(["message" => "Cliente actualizado exitosamente."]);
        } else {
<<<<<<< HEAD
            // Si la actualización es exitosa pero no se modificaron datos, se reporta como éxito (o sin cambios)
            http_response_code(200);
            echo json_encode(["message" => "Cliente actualizado (o sin cambios)." ]);
        }
    }
    
    // Eliminado el método private function delete($id)
=======
            http_response_code(503);
            echo json_encode(["message" => "No se pudo actualizar el cliente."]);
        }
    }

    // [DELETE] /clientes/{id} - Inactivar un cliente (Borrado lógico)
    private function delete($id) {
        $this->cliente->id = $id;

        // 1. Verificar si el cliente existe y está activo (readOne carga sus datos)
        if (!$this->cliente->readOne() || $this->cliente->estado !== 'activo') {
            http_response_code(404);
            echo json_encode(["message" => "Cliente con ID {$id} no encontrado o ya está inactivo."]);
            return;
        }
        
        // 2. Ejecutar inactivación
        if ($this->cliente->delete()) {
            http_response_code(200);
            echo json_encode(["message" => "Cliente inactivado exitosamente."]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "No se pudo inactivar el cliente."]);
        }
    }
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
}
?>