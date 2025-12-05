<?php
// Controlador para manejar todas las peticiones (CRUD y Autenticación) del recurso /usuarios
// y los endpoints /register y /login

// Incluir Modelo necesario (asumiendo que ya fue creado)
require_once '../app/Models/Usuario.php'; 

class UsuarioController {
    private $db;
    private $usuario;

    public function __construct() {
        // Inicializa la conexión y el modelo
        $database = new Database();
        $this->db = $database->getConnection();
<<<<<<< HEAD
        // Asumo que el modelo de usuario se llama 'Usuario'
        $this->usuario = new Usuario($this->db); 
=======
        $this->usuario = new Usuario($this->db);
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
    }

    // Maneja peticiones CRUD (GET, PUT, DELETE) para /usuarios
    public function handleRequest($method, $id) {
        switch ($method) {
            case 'GET':
                if ($id && is_numeric($id)) {
                    $this->readOne($id);
                } else {
                    $this->readAll();
                }
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
                echo json_encode(["message" => "Método {$method} no permitido para el recurso Usuarios."]);
                break;
        }
    }

    // Maneja peticiones de autenticación (POST)
<<<<<<< HEAD
    public function handleAuthRequest($method, $action) {
        if ($method !== 'POST') {
            http_response_code(405); 
            echo json_encode(["message" => "El método {$method} no es permitido para autenticación. Use POST."]);
            return;
        }

=======
    public function handleAuthRequest($action) {
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        switch ($action) {
            case 'register':
                $this->register();
                break;
            case 'login':
                $this->login();
                break;
            default:
<<<<<<< HEAD
                // Si la acción no es 'register' o 'login'
=======
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
                http_response_code(404);
                echo json_encode(["message" => "Endpoint de autenticación no válido."]);
                break;
        }
    }

<<<<<<< HEAD
    // --- MÉTODOS DE AUTENTICACIÓN (ADAPTADOS A CAMPOS DE DB) ---

    // [POST] /register - Crear un nuevo usuario
    public function register() {
        // Lectura segura de datos JSON
        $data = json_decode(file_get_contents("php://input"), true);
    
        // **VALIDACIÓN ADAPTADA:** Ahora se esperan 'usuario', 'contraseña', 'rol', 'nombre', y 'apellido'.
        if (!isset($data['usuario']) || !isset($data['contrasena']) || !isset($data['rol']) || !isset($data['nombre']) || !isset($data['apellido'])) {
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Faltan campos requeridos para el registro (usuario, contrasena, rol, nombre, apellido)."]);
            return;
        }
    
        $usuario = $data['usuario'];
        $contrasena = $data['contrasena'];
        $rol = $data['rol'];
        $nombre = $data['nombre'];
        $apellido = $data['apellido'];
    
        try {
            // 1. Verificar si el usuario ya existe (Asumiendo método 'findByUsuario' en el Modelo)
            $existingUser = $this->usuario->findByUsuario($usuario); 
            if ($existingUser) {
                http_response_code(409); // Conflict
                echo json_encode(["message" => "El nombre de usuario ya existe."]);
                return;
            }
    
            // 2. Hash de la contraseña
            $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);
    
            // 3. Crear el usuario (Asumiendo método 'create' en el Modelo con los nuevos campos)
            $result = $this->usuario->create($nombre, $apellido, $usuario, $hashedPassword, $rol);
    
            if ($result) {
                http_response_code(201); // Created
                // Devuelve el id_usuario
                echo json_encode(["message" => "Registro exitoso.", "id_usuario" => $result]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["message" => "Fallo al crear el usuario."]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Error interno del servidor: " . $e->getMessage()]);
        }
    }
    
    // [POST] /login - Iniciar sesión
    public function login() {
        // Lectura segura de datos JSON
        $data = json_decode(file_get_contents("php://input"), true);
    
        // **VALIDACIÓN ADAPTADA:** Se esperan 'usuario' y 'contraseña'.
        if (!isset($data['usuario']) || !isset($data['contrasena'])) {
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Faltan credenciales (usuario, contrasena)."]);
            return;
        }

        $usuario = $data['usuario'];
        $contrasena = $data['contrasena'];
        
        try {
            // Cargar el usuario por su nombre de usuario (Asumiendo método 'findByUsuario')
            $user = $this->usuario->findByUsuario($usuario); 
            
            // Lógica de verificación de usuario y contraseña (usando $user['contraseña'])
            if ($user && password_verify($contrasena, $user['contrasena'])) {
                // Login exitoso
                http_response_code(200);
                echo json_encode(["message" => "Login exitoso.", "id_usuario" => $user['id_usuario'], "rol" => $user['rol']]);
            } else {
                // Falla el usuario o la contraseña
                http_response_code(401); // Unauthorized
                echo json_encode(["message" => "Credenciales incorrectas."]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Error interno del servidor: " . $e->getMessage()]);
        }
    }

    // --- Lógica del CRUD (ADAPTADA A CAMPOS DE DB) ---
    
=======
    // --- Lógica de AUTENTICACIÓN ---

    // [POST] /register - Crear un nuevo usuario (Registro)
    private function register() {
        $data = json_decode(file_get_contents("php://input"));

        // Validaciones: Campos obligatorios y longitud mínima de password
        if (empty($data->nombre_usuario) || empty($data->password) || empty($data->nombre_completo)) {
            http_response_code(400); 
            echo json_encode(["message" => "Error. Datos incompletos. Se requieren nombre_usuario, password y nombre_completo."]);
            return;
        }
        if (strlen($data->password) < 6) {
             http_response_code(400); 
             echo json_encode(["message" => "Error. La contraseña debe tener al menos 6 caracteres."]);
             return;
        }
        
        // Asignar valores
        $this->usuario->nombre_usuario = $data->nombre_usuario;
        $this->usuario->password = $data->password;
        $this->usuario->nombre_completo = $data->nombre_completo;
        // Rol por defecto si no se especifica o si es inválido
        $this->usuario->rol = in_array($data->rol ?? '', ['administrador', 'vendedor']) ? $data->rol : 'vendedor';

        $result = $this->usuario->create();

        if ($result === true) {
            http_response_code(201);
            echo json_encode(["message" => "Usuario registrado exitosamente. Rol: " . $this->usuario->rol]);
        } elseif ($result === 'duplicate') {
            http_response_code(409); // Conflict
            echo json_encode(["message" => "Error. El nombre de usuario ya está en uso."]);
        } else {
            http_response_code(503); 
            echo json_encode(["message" => "No se pudo registrar el usuario."]);
        }
    }
    
    // [POST] /login - Iniciar Sesión
    private function login() {
        $data = json_decode(file_get_contents("php://input"));

        // Validaciones: Campos obligatorios
        if (empty($data->nombre_usuario) || empty($data->password)) {
            http_response_code(400); 
            echo json_encode(["message" => "Error. Se requieren nombre_usuario y password."]);
            return;
        }
        
        // Asignar nombre de usuario para buscar el registro
        $this->usuario->nombre_usuario = $data->nombre_usuario;

        // Intentar autenticar
        if ($this->usuario->authenticate($data->password)) {
            // Éxito en la autenticación. Aquí se podría generar un JWT
            http_response_code(200);
            echo json_encode([
                "message" => "Inicio de sesión exitoso.",
                "user" => [
                    "id" => (int)$this->usuario->id,
                    "nombre_completo" => $this->usuario->nombre_completo,
                    "rol" => $this->usuario->rol
                ],
                "token" => "JWT_GENERADO_AQUI_PARA_SEGURIDAD" // Placeholder para JWT
            ]);
        } else {
            // Fallo en la autenticación
            http_response_code(401); // Unauthorized
            echo json_encode(["message" => "Credenciales incorrectas o usuario inactivo."]);
        }
    }

    // --- Lógica del CRUD (Solo para Administradores) ---

    // [GET] /usuarios - Listar todos los usuarios activos
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
    private function readAll() {
        $stmt = $this->usuario->read();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $usuarios_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
<<<<<<< HEAD
                // **CAMPOS ADAPTADOS**
                $usuario_item = array(
                    "id_usuario" => (int)$row['id_usuario'],
                    "nombre" => $row['nombre'],
                    "apellido" => $row['apellido'],
                    "usuario" => $row['usuario'],
                    "rol" => $row['rol'],
                    "estado" => $row['estado'],
                );
                array_push($usuarios_arr, $usuario_item);
            }
=======
                // La contraseña no se expone ya que la consulta SQL la excluye
                $usuario_item = array(
                    "id" => (int)$row['id'],
                    "nombre_usuario" => $row['nombre_usuario'],
                    "nombre_completo" => $row['nombre_completo'],
                    "rol" => $row['rol'],
                    "estado" => $row['estado']
                );
                array_push($usuarios_arr, $usuario_item);
            }

>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            http_response_code(200);
            echo json_encode($usuarios_arr);
        } else {
            http_response_code(404);
<<<<<<< HEAD
            echo json_encode(["message" => "No se encontraron usuarios."]);
        }
    }

    private function readOne($id) {
        // **CAMPO ADAPTADO:** id_usuario
        $this->usuario->id_usuario = $id; 

        if ($this->usuario->readOneById()) {
            // **CAMPOS ADAPTADOS**
            $usuario_arr = array(
                "id_usuario" => (int)$this->usuario->id_usuario,
                "nombre" => $this->usuario->nombre,
                "apellido" => $this->usuario->apellido,
                "usuario" => $this->usuario->usuario,
                "rol" => $this->usuario->rol,
                "estado" => $this->usuario->estado,
=======
            echo json_encode(["message" => "No se encontraron usuarios activos."]);
        }
    }

    // [GET] /usuarios/{id} - Obtener detalles de un usuario específico
    private function readOne($id) {
        $this->usuario->id = $id;
        
        if ($this->usuario->readOneById()) {
            $usuario_arr = array(
                "id" => (int)$this->usuario->id,
                "nombre_usuario" => $this->usuario->nombre_usuario,
                "nombre_completo" => $this->usuario->nombre_completo,
                "rol" => $this->usuario->rol,
                "estado" => $this->usuario->estado
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            );
            http_response_code(200);
            echo json_encode($usuario_arr);
        } else {
            http_response_code(404);
<<<<<<< HEAD
            echo json_encode(["message" => "Usuario con ID {$id} no encontrado."]);
        }
    }

    private function update($id) {
        $data = json_decode(file_get_contents("php://input"));
        // **CAMPO ADAPTADO:** id_usuario
        $this->usuario->id_usuario = $id; 

        // 1. Verificar si el usuario existe
        if (!$this->usuario->readOneById()) {
            http_response_code(404);
            echo json_encode(["message" => "Usuario con ID {$id} no encontrado para actualizar."]);
            return;
        }

        // 2. Establecer propiedades a actualizar. (CAMPOS ADAPTADOS)
        $this->usuario->usuario = $data->usuario ?? $this->usuario->usuario;
        $this->usuario->nombre = $data->nombre ?? $this->usuario->nombre;
        $this->usuario->apellido = $data->apellido ?? $this->usuario->apellido;
        $this->usuario->rol = $data->rol ?? $this->usuario->rol;
        $this->usuario->estado = $data->estado ?? $this->usuario->estado;

        // Verificar si la contraseña se va a actualizar
        $update_password = false;
        // **CAMPO ADAPTADO:** contraseña
        if (isset($data->contrasena) && !empty($data->contrasena)) { 
            $this->usuario->contrasena = password_hash($data->contrasena, PASSWORD_DEFAULT);
            $update_password = true;
        }
        
=======
            echo json_encode(["message" => "Usuario con ID {$id} no existe o está inactivo."]);
        }
    }
    
    // [PUT/PATCH] /usuarios/{id} - Actualizar información o contraseña
    private function update($id) {
        $data = json_decode(file_get_contents("php://input"));
        $this->usuario->id = $id;
        
        // 1. Verificar si el usuario existe
        if (!$this->usuario->readOneById()) {
            http_response_code(404);
            echo json_encode(["message" => "Usuario con ID {$id} no encontrado."]);
            return;
        }

        // 2. Determinar si es una actualización de CONTRASEÑA o de DATOS GENERALES
        $update_password = false;
        
        if (isset($data->password) && !empty($data->password)) {
            if (strlen($data->password) < 6) {
                 http_response_code(400); 
                 echo json_encode(["message" => "Error. La nueva contraseña debe tener al menos 6 caracteres."]);
                 return;
            }
            $this->usuario->password = $data->password;
            $update_password = true;
        }

        // Si es actualización de datos generales
        if (!$update_password) {
            $this->usuario->nombre_completo = $data->nombre_completo ?? null;
            $this->usuario->rol = $data->rol ?? null;
        }

>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        // 3. Ejecutar actualización
        if ($this->usuario->update($update_password)) {
            http_response_code(200);
            echo json_encode(["message" => "Usuario actualizado exitosamente."]);
        } else {
<<<<<<< HEAD
=======
             // Si la actualización es exitosa pero no se modificaron datos, no retorna error 503
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            http_response_code(200); 
            echo json_encode(["message" => "Usuario actualizado (o sin cambios)." ]);
        }
    }

<<<<<<< HEAD
    private function delete($id) {
        // **CAMPO ADAPTADO:** id_usuario
        $this->usuario->id_usuario = $id; 
=======
    // [DELETE] /usuarios/{id} - Inactivar un usuario (Borrado lógico)
    private function delete($id) {
        $this->usuario->id = $id;
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

        // 1. Verificar si el usuario existe y está activo 
        if (!$this->usuario->readOneById() || $this->usuario->estado !== 'activo') {
            http_response_code(404);
            echo json_encode(["message" => "Usuario con ID {$id} no encontrado o ya está inactivo."]);
            return;
        }
        
        // 2. Ejecutar inactivación
        if ($this->usuario->delete()) {
            http_response_code(200);
            echo json_encode(["message" => "Usuario inactivado exitosamente."]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "No se pudo inactivar el usuario."]);
        }
    }
}
?>