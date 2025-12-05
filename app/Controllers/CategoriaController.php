<?php
// Controlador para manejar todas las peticiones (GET, POST, PUT, DELETE) del recurso /categorias

// Incluir Modelo necesario (asumiendo que ya fue creado)
require_once '../app/Models/Categoria.php'; 
<<<<<<< HEAD
// Asumo que el archivo de conexión está en Config/Database.php
require_once '../app/Config/Database.php'; 
=======
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

class CategoriaController {
    private $db;
    private $categoria;

    public function __construct() {
        // Inicializa la conexión y el modelo
        $database = new Database();
        $this->db = $database->getConnection();
        $this->categoria = new Categoria($this->db);
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
                $this->update($id); 
                break;
            case 'DELETE':
                $this->delete($id);
                break;
            default:
                http_response_code(405); 
                echo json_encode(["message" => "Método {$method} no permitido para el recurso Categorías."]);
                break;
        }
    }

    // --- Lógica del CRUD ---

    // [GET] /categorias - Listar todas las categorías activas
    private function readAll() {
        $stmt = $this->categoria->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $categorias_arr = array();
<<<<<<< HEAD
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Extraer fila para evitar $row['id_categoria'], etc.
                extract($row); 

                $categoria_item = array(
                    "id_categoria" => (int)$id_categoria,
                    "nombre_categoria" => $nombre_categoria,
                    "descripcion" => $descripcion,
                    "estado" => $estado
                );

=======
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $categoria_item = array(
                    "id" => (int)$id,
                    "nombre" => $nombre,
                    "descripcion" => $descripcion,
                    "estado" => $estado
                );
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
                array_push($categorias_arr, $categoria_item);
            }

            http_response_code(200);
            echo json_encode($categorias_arr);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "No se encontraron categorías activas."]);
        }
    }

<<<<<<< HEAD
    // [GET] /categorias/{id} - Obtener una categoría específica
    private function readOne($id) {
        // Asignar ID al modelo (USANDO EL CAMPO CORRECTO: id_categoria)
        $this->categoria->id_categoria = $id;

        if ($this->categoria->readOne()) {
            // Construir el array de respuesta
            $categoria_item = array(
                "id_categoria" => (int)$this->categoria->id_categoria,
                "nombre_categoria" => $this->categoria->nombre_categoria,
                "descripcion" => $this->categoria->descripcion,
                "estado" => $this->categoria->estado
            );

            http_response_code(200);
            echo json_encode($categoria_item);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Categoría con ID {$id} no encontrada."]);
=======
    // [GET] /categorias/{id} - Obtener detalles de una categoría específica
    private function readOne($id) {
        $this->categoria->id = $id;
        
        if ($this->categoria->readOne()) {
            $categoria_arr = array(
                "id" => (int)$this->categoria->id,
                "nombre" => $this->categoria->nombre,
                "descripcion" => $this->categoria->descripcion,
                "estado" => $this->categoria->estado
            );
            http_response_code(200);
            echo json_encode($categoria_arr);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Categoría con ID {$id} no existe o está inactiva."]);
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        }
    }

    // [POST] /categorias - Crear una nueva categoría
    private function create() {
<<<<<<< HEAD
        // 1. Obtener datos de la petición JSON
        $data = json_decode(file_get_contents("php://input"));

        // 2. Validar datos esenciales
        if (empty($data->nombre_categoria)) {
            http_response_code(400); 
            echo json_encode(["message" => "Error. Se requiere el nombre_categoria para crear la categoría."]);
            return;
        }

        // 3. Asignar valores al objeto Categoria (USANDO LOS CAMPOS CORRECTOS)
        $this->categoria->nombre_categoria = $data->nombre_categoria;
        $this->categoria->descripcion = $data->descripcion ?? ''; // La descripción es opcional

        // 4. Ejecutar creación
        if ($this->categoria->create()) {
            http_response_code(201); // 201 Created
            echo json_encode(["message" => "Categoría creada exitosamente."]);
        } else {
            http_response_code(503); // 503 Service Unavailable
            echo json_encode(["message" => "No se pudo crear la categoría."]);
        }
    }

    // [PUT/PATCH] /categorias/{id} - Actualizar una categoría existente
    private function update($id) {
        // 1. Asignar ID al modelo
        $this->categoria->id_categoria = $id;

        // 2. Verificar que la categoría exista antes de intentar actualizarla
        if (!$this->categoria->readOne()) {
            http_response_code(404);
            echo json_encode(["message" => "Categoría con ID {$id} no encontrada para actualizar."]);
            return;
        }

        // 3. Obtener datos de la petición JSON
        $data = json_decode(file_get_contents("php://input"));
        
        // 4. Asignar solo los campos que vienen en el body (USANDO LOS CAMPOS CORRECTOS)
        $this->categoria->nombre_categoria = $data->nombre_categoria ?? null; // Null si no viene para que el modelo lo ignore
        $this->categoria->descripcion = $data->descripcion ?? null; 
        
        // 5. Validación adicional: el nombre no puede ser una cadena vacía si se envía
        if (isset($data->nombre_categoria) && empty(trim($data->nombre_categoria))) {
=======
        $data = json_decode(file_get_contents("php://input"));

        // Validaciones: Campos obligatorios
        if (empty($data->nombre)) {
            http_response_code(400); 
            echo json_encode(["message" => "Error. Datos incompletos. Se requiere el nombre de la categoría."]);
            return;
        }
        
        // Asignar valores
        $this->categoria->nombre = $data->nombre;
        $this->categoria->descripcion = $data->descripcion ?? ''; // Permitir vacío

        if ($this->categoria->create()) {
            http_response_code(201);
            echo json_encode(["message" => "Categoría registrada exitosamente."]);
        } else {
            http_response_code(503); 
            echo json_encode(["message" => "No se pudo registrar la categoría."]);
        }
    }
    
    // [PUT/PATCH] /categorias/{id} - Actualizar una categoría existente
    private function update($id) {
        $data = json_decode(file_get_contents("php://input"));
        $this->categoria->id = $id;
        
        // 1. Verificar si la categoría existe
        if (!$this->categoria->readOne()) {
            http_response_code(404);
            echo json_encode(["message" => "Categoría con ID {$id} no encontrada."]);
            return;
        }

        // 2. Asignar datos del body o mantener los existentes para permitir PATCH
        $this->categoria->nombre = $data->nombre ?? $this->categoria->nombre;
        $this->categoria->descripcion = $data->descripcion ?? $this->categoria->descripcion;
        
        // 3. Validación de campos obligatorios al actualizar (si se pone vacío)
        if (empty($this->categoria->nombre)) {
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            http_response_code(400); 
            echo json_encode(["message" => "Error. El nombre de la categoría no puede estar vacío."]);
            return;
        }


<<<<<<< HEAD
        // 6. Ejecutar actualización
=======
        // 4. Ejecutar actualización
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        if ($this->categoria->update()) {
            http_response_code(200);
            echo json_encode(["message" => "Categoría actualizada exitosamente."]);
        } else {
            // Si la actualización es exitosa pero no se modificaron datos, no retorna error 503
            http_response_code(200);
            echo json_encode(["message" => "Categoría actualizada (o sin cambios)." ]);
        }
    }

    // [DELETE] /categorias/{id} - Inactivar una categoría (Borrado lógico)
    private function delete($id) {
<<<<<<< HEAD
        // Asignar ID al modelo
        $this->categoria->id_categoria = $id;
=======
        $this->categoria->id = $id;
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

        // 1. Verificar si la categoría existe y está activa 
        if (!$this->categoria->readOne() || $this->categoria->estado !== 'activo') {
            http_response_code(404);
            echo json_encode(["message" => "Categoría con ID {$id} no encontrada o ya está inactiva."]);
            return;
        }
        
        // 2. Ejecutar inactivación
        if ($this->categoria->delete()) {
            http_response_code(200);
<<<<<<< HEAD
            echo json_encode(["message" => "Categoría inactivada exitosamente (Borrado lógico)."]);
=======
            echo json_encode(["message" => "Categoría inactivada exitosamente."]);
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        } else {
            http_response_code(503);
            echo json_encode(["message" => "No se pudo inactivar la categoría."]);
        }
    }
<<<<<<< HEAD
}
=======
}
?>
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
