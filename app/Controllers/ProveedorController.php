<?php
// Controlador para manejar todas las peticiones (GET, POST, PUT, DELETE) del recurso /proveedores

<<<<<<< HEAD
// Incluir Modelo necesario
require_once '../app/Models/Proveedor.php'; 

=======
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
class ProveedorController {
    private $db;
    private $proveedor;

    public function __construct() {
<<<<<<< HEAD
        // Asumo que la clase Database ya se cargó vía Autoload o require_once
        $database = new Database();
        $this->db = $database->getConnection();
        // Inicializa el modelo
=======
        $database = new Database();
        $this->db = $database->getConnection();
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        $this->proveedor = new Proveedor($this->db);
    }

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
<<<<<<< HEAD
                // Se asume que $id se pasa en la URL, ej: /proveedores/123
=======
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
                $this->update($id); 
                break;
            case 'DELETE':
                $this->delete($id);
                break;
            default:
                http_response_code(405); 
<<<<<<< HEAD
                echo json_encode(["message" => "Método {$method} no permitido para el recurso Proveedores."]);
=======
                echo json_encode(["message" => "Método {$method} no permitido."]);
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
                break;
        }
    }

    // --- Lógica del CRUD ---

<<<<<<< HEAD
    // [GET] /proveedores - Listar todos los proveedores activos
=======
    // [GET] /proveedores
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
    private function readAll() {
        $stmt = $this->proveedor->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $proveedores_arr = array();
<<<<<<< HEAD
            // Recorre los resultados y los formatea
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row); // Extrae las variables: $id_proveedor, $nombre, etc.
                
                $proveedor_item = array(
                    "id_proveedor" => (int)$id_proveedor,
                    "nombre" => $nombre,
                    "telefono" => $telefono,
                    "email" => $email,
=======
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $proveedor_item = array(
                    "id" => $id,
                    "nombre_empresa" => $nombre_empresa,
                    "contacto_principal" => $contacto_principal,
                    "telefono" => $telefono,
                    "email" => $email,
                    "nit" => $nit,
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
                    "direccion" => $direccion,
                    "estado" => $estado
                );
                array_push($proveedores_arr, $proveedor_item);
            }

            http_response_code(200);
            echo json_encode($proveedores_arr);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "No se encontraron proveedores activos."]);
        }
    }

<<<<<<< HEAD
    // [GET] /proveedores/{id} - Mostrar un proveedor
    private function readOne($id) {
        $this->proveedor->id_proveedor = $id;

        if ($this->proveedor->readOne()) {
            // Formatea el proveedor para la respuesta
            $proveedor_arr = array(
                "id_proveedor" => (int)$this->proveedor->id_proveedor,
                "nombre" => $this->proveedor->nombre,
                "telefono" => $this->proveedor->telefono,
                "email" => $this->proveedor->email,
                "direccion" => $this->proveedor->direccion,
                "estado" => $this->proveedor->estado
            );

=======
    // [GET] /proveedores/{id}
    private function readOne($id) {
        $this->proveedor->id = $id;
        
        if ($this->proveedor->readOne()) {
            $proveedor_arr = array(
                "id" => $this->proveedor->id,
                "nombre_empresa" => $this->proveedor->nombre_empresa,
                "contacto_principal" => $this->proveedor->contacto_principal,
                "telefono" => $this->proveedor->telefono,
                "email" => $this->proveedor->email,
                "nit" => $this->proveedor->nit,
                "direccion" => $this->proveedor->direccion
            );
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            http_response_code(200);
            echo json_encode($proveedor_arr);
        } else {
            http_response_code(404);
<<<<<<< HEAD
            echo json_encode(["message" => "Proveedor con ID {$id} no encontrado."]);
        }
    }

    // [POST] /proveedores - Crear nuevo proveedor
    private function create() {
        // 1. Obtener los datos enviados
        $data = json_decode(file_get_contents("php://input"));

        // 2. Validar datos mínimos
        if (empty($data->nombre) || empty($data->telefono) || empty($data->email)) {
            http_response_code(400); 
            echo json_encode(["message" => "Error. Faltan campos obligatorios: nombre, telefono y email."]);
            return;
        }
        
        // 3. Asignar propiedades al modelo
        $this->proveedor->nombre = $data->nombre;
        $this->proveedor->telefono = $data->telefono;
        $this->proveedor->email = $data->email;
        $this->proveedor->direccion = $data->direccion ?? null; // Dirección es opcional
        $this->proveedor->estado = 'activo';

        // 4. Validar formato de email
        if (!filter_var($this->proveedor->email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400); 
            echo json_encode(["message" => "Error. El formato del correo electrónico es inválido."]);
            return;
        }

        // 5. Verificar si el email ya existe (evitar duplicados)
        if ($this->proveedor->emailExists()) {
            http_response_code(409); // Conflict
            echo json_encode(["message" => "Error. Ya existe un proveedor activo con este correo electrónico."]);
            return;
        }

        // 6. Ejecutar creación
        if ($this->proveedor->create()) {
            http_response_code(201); // Created
            echo json_encode([
                "message" => "Proveedor creado exitosamente.",
                "id_proveedor" => (int)$this->proveedor->id_proveedor
            ]);
        } else {
            http_response_code(503); // Service Unavailable
            echo json_encode(["message" => "No se pudo crear el proveedor."]);
        }
    }

    // [PUT/PATCH] /proveedores/{id} - Actualizar proveedor
    private function update($id) {
        // 1. Verificar si el ID existe y es numérico
        if (!$id || !is_numeric($id)) {
            http_response_code(400); 
            echo json_encode(["message" => "Error. ID de proveedor no proporcionado o inválido."]);
            return;
        }

        // 2. Cargar datos existentes y preparar para actualizar
        $this->proveedor->id_proveedor = $id;

        // Si readOne falla, el proveedor no existe
        if (!$this->proveedor->readOne()) {
            http_response_code(404); 
            echo json_encode(["message" => "Proveedor con ID {$id} no encontrado."]);
            return;
        }
        
        $data = json_decode(file_get_contents("php://input"));

        // Asignar solo si el dato está presente en el cuerpo de la petición (PATCH/PUT)
        $this->proveedor->nombre = $data->nombre ?? null;
        $this->proveedor->telefono = $data->telefono ?? null;
        $this->proveedor->email = $data->email ?? null;
        $this->proveedor->direccion = $data->direccion ?? null;
=======
            echo json_encode(["message" => "Proveedor con ID {$id} no existe o está inactivo."]);
        }
    }

    // [POST] /proveedores
    private function create() {
        $data = json_decode(file_get_contents("php://input"));

        // ** VALIDACIONES DE VERIFICACIÓN (Puntaje 25%) **
        if (empty($data->nombre_empresa) || empty($data->nit) || empty($data->telefono)) {
            http_response_code(400); 
            echo json_encode(["message" => "Error. Datos incompletos. Se requiere nombre de empresa, NIT y teléfono."]);
            return;
        }
        
        if (!empty($data->email) && !filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
             http_response_code(400); 
             echo json_encode(["message" => "Error. El formato del correo electrónico no es válido."]);
             return;
        }

        // Asignar valores
        $this->proveedor->nombre_empresa = $data->nombre_empresa;
        $this->proveedor->contacto_principal = $data->contacto_principal ?? null;
        $this->proveedor->telefono = $data->telefono;
        $this->proveedor->email = $data->email ?? null;
        $this->proveedor->nit = $data->nit;
        $this->proveedor->direccion = $data->direccion ?? null;

        if ($this->proveedor->create()) {
            http_response_code(201);
            echo json_encode(["message" => "Proveedor registrado exitosamente."]);
        } else {
            http_response_code(503); 
            echo json_encode(["message" => "No se pudo registrar el proveedor."]);
        }
    }
    
    // [PUT] /proveedores/{id}
    private function update($id) {
        $data = json_decode(file_get_contents("php://input"));
        $this->proveedor->id = $id;
        
        if (!$this->proveedor->readOne()) {
            http_response_code(404);
            echo json_encode(["message" => "Proveedor con ID {$id} no encontrado."]);
            return;
        }

        // Asignar datos del body o mantener los existentes
        $this->proveedor->nombre_empresa = $data->nombre_empresa ?? $this->proveedor->nombre_empresa;
        $this->proveedor->contacto_principal = $data->contacto_principal ?? $this->proveedor->contacto_principal;
        $this->proveedor->telefono = $data->telefono ?? $this->proveedor->telefono;
        $this->proveedor->email = $data->email ?? $this->proveedor->email;
        $this->proveedor->nit = $data->nit ?? $this->proveedor->nit;
        $this->proveedor->direccion = $data->direccion ?? $this->proveedor->direccion;
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        
        // ** VALIDACIÓN ADICIONAL al actualizar **
        if (!empty($this->proveedor->email) && !filter_var($this->proveedor->email, FILTER_VALIDATE_EMAIL)) {
             http_response_code(400); 
             echo json_encode(["message" => "Error. El formato del correo electrónico es inválido en la actualización."]);
             return;
        }

<<<<<<< HEAD
        // 3. Ejecutar actualización
        if ($this->proveedor->update()) {
            // El modelo usa COALESCE, así que puede retornar true incluso si no hay cambios.
            http_response_code(200);
            echo json_encode(["message" => "Proveedor actualizado exitosamente (o sin cambios)."]);
=======
        if ($this->proveedor->update()) {
            http_response_code(200);
            echo json_encode(["message" => "Proveedor actualizado exitosamente."]);
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        } else {
            http_response_code(503);
            echo json_encode(["message" => "No se pudo actualizar el proveedor."]);
        }
    }

<<<<<<< HEAD
    // [DELETE] /proveedores/{id} - Inactivar un proveedor (Borrado lógico)
    private function delete($id) {
        $this->proveedor->id_proveedor = $id;

        // 1. Verificar si el proveedor existe y está activo (readOne carga sus datos)
        if (!$this->proveedor->readOne() || $this->proveedor->estado !== 'activo') {
            http_response_code(404);
            echo json_encode(["message" => "Proveedor con ID {$id} no encontrado o ya está inactivo."]);
            return;
        }
        
        // 2. Ejecutar inactivación
=======
    // [DELETE] /proveedores/{id} (Inactivar)
    private function delete($id) {
        $this->proveedor->id = $id;

        if (!$this->proveedor->readOne()) {
            http_response_code(404);
            echo json_encode(["message" => "Proveedor con ID {$id} no encontrado para inactivar."]);
            return;
        }

>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        if ($this->proveedor->delete()) {
            http_response_code(200);
            echo json_encode(["message" => "Proveedor inactivado exitosamente."]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "No se pudo inactivar el proveedor."]);
        }
    }
}
?>