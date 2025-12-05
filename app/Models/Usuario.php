<?php
// Incluir la clase de conexión a la base de datos (asumiendo que Database.php está disponible)
// require_once 'Database.php'; 

class Usuario {
    // Conexión a la DB y nombre de la tabla
    private $conn;
<<<<<<< HEAD
    private $table_name = "usuario"; // Asumiendo tabla 'usuario'

    // **PROPIEDADES CORREGIDAS PARA COINCIDIR CON LA DB**
    public $id_usuario;     // Clave primaria
    public $nombre;         // Nombre de la persona
    public $apellido;       // Apellido de la persona
    public $usuario;        // Nombre de usuario (usado para login)
    public $contrasena;     // Contraseña hasheada (Campo 'contraseña' en la DB)
=======
    private $table_name = "usuarios"; // Asumiendo tabla 'usuarios'

    // Propiedades del objeto Usuario
    public $id;
    public $nombre_usuario; // (e.g., username)
    public $password;       // Contraseña hasheada
    public $nombre_completo;
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
    public $rol;            // 'administrador' o 'vendedor'
    public $estado = 'activo'; // Por defecto

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- MÉTODOS CRUD ---

    // [GET] Leer todos los usuarios activos
    public function read() {
        // Selecciona todos los usuarios, excluyendo la contraseña
<<<<<<< HEAD
        $query = "SELECT id_usuario, nombre, apellido, usuario, rol, estado 
                  FROM " . $this->table_name . " 
                  WHERE estado = 'activo'
                  ORDER BY nombre ASC"; // Ordenado por nombre
=======
        $query = "SELECT id, nombre_usuario, nombre_completo, rol, estado 
                  FROM " . $this->table_name . " 
                  WHERE estado = 'activo'
                  ORDER BY nombre_completo ASC";
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
<<<<<<< HEAD
    // [GET] Leer un usuario por ID
    public function readOneById() {
        $query = "SELECT id_usuario, nombre, apellido, usuario, contrasena, rol, estado
                  FROM " . $this->table_name . "
                  WHERE id_usuario = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        // Usa id_usuario
        $stmt->bindParam(1, $this->id_usuario);
=======
    // [GET] Leer un usuario por nombre_usuario (útil para login)
    public function readOneByUsername() {
        $query = "SELECT id, password, nombre_completo, rol, estado 
                  FROM " . $this->table_name . " 
                  WHERE nombre_usuario = ? 
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        // Sanear y vincular nombre de usuario
        $this->nombre_usuario = htmlspecialchars(strip_tags($this->nombre_usuario));
        $stmt->bindParam(1, $this->nombre_usuario);
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
<<<<<<< HEAD
            // Asigna los valores a las propiedades del objeto
            $this->nombre = $row['nombre'];
            $this->apellido = $row['apellido'];
            $this->usuario = $row['usuario'];
            $this->contrasena = $row['contrasena']; // Carga el hash
=======
            // Asigna valores a las propiedades del objeto (incluida la contraseña hasheada)
            $this->id = $row['id'];
            $this->password = $row['password'];
            $this->nombre_completo = $row['nombre_completo'];
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            $this->rol = $row['rol'];
            $this->estado = $row['estado'];
            return true;
        }
<<<<<<< HEAD
        return false;
    }

    // [PUT/PATCH] Actualizar un usuario
    public function update($update_password = false) {
        $query = "UPDATE " . $this->table_name . "
                  SET nombre = :nombre, 
                      apellido = :apellido, 
                      usuario = :usuario, 
                      rol = :rol, 
                      estado = :estado";
        
        if ($update_password) {
            $query .= ", contrasena = :contrasena"; // Campo corregido
        }
        
        $query .= " WHERE id_usuario = :id_usuario"; // Campo corregido
        
        $stmt = $this->conn->prepare($query);

        // Sanear datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->usuario = htmlspecialchars(strip_tags($this->usuario));
        $this->rol = htmlspecialchars(strip_tags($this->rol));
        $this->estado = htmlspecialchars(strip_tags($this->estado));
        $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));

        // Vincular parámetros
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':apellido', $this->apellido);
        $stmt->bindParam(':usuario', $this->usuario);
        $stmt->bindParam(':rol', $this->rol);
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':id_usuario', $this->id_usuario);

        if ($update_password) {
             // Vincular nueva contraseña hasheada
            $stmt->bindParam(':contrasena', $this->contrasena);
=======

        return false;
    }
    
    // [GET] Leer un usuario por ID
    public function readOneById() {
        $query = "SELECT id, nombre_usuario, nombre_completo, rol, estado 
                  FROM " . $this->table_name . " 
                  WHERE id = ? 
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nombre_usuario = $row['nombre_usuario'];
            $this->nombre_completo = $row['nombre_completo'];
            $this->rol = $row['rol'];
            $this->estado = $row['estado'];
            return true;
        }

        return false;
    }

    // [POST] Crear un nuevo usuario
    public function create() {
        // Valida que el rol sea uno de los permitidos
        if (!in_array($this->rol, ['administrador', 'vendedor'])) {
            $this->rol = 'vendedor'; // Valor por defecto si es inválido
        }
        
        $query = "INSERT INTO " . $this->table_name . "
                  SET nombre_usuario = :nombre_usuario, 
                      password = :password, 
                      nombre_completo = :nombre_completo, 
                      rol = :rol, 
                      estado = 'activo'";

        $stmt = $this->conn->prepare($query);

        // Hashing de la contraseña antes de guardar
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        // Sanear y vincular valores
        $this->nombre_usuario = htmlspecialchars(strip_tags($this->nombre_usuario));
        $this->nombre_completo = htmlspecialchars(strip_tags($this->nombre_completo));
        
        $stmt->bindParam(':nombre_usuario', $this->nombre_usuario);
        $stmt->bindParam(':password', $this->password); // Ya hasheada
        $stmt->bindParam(':nombre_completo', $this->nombre_completo);
        $stmt->bindParam(':rol', $this->rol);

        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            // Error de duplicidad (por ejemplo, nombre_usuario único)
            if ($e->getCode() == 23000) { 
                return 'duplicate'; 
            }
            error_log("Error al crear usuario: " . $e->getMessage());
        }

        return false;
    }

    // [PUT/PATCH] Actualizar usuario (excluyendo password, o solo password)
    public function update($update_password = false) {
        $query_parts = [];

        if (!$update_password) {
            // Actualizar información general (nombre, rol)
            if (!empty($this->nombre_completo)) $query_parts[] = "nombre_completo = :nombre_completo";
            if (!empty($this->rol)) {
                 // Valida que el rol sea uno de los permitidos
                if (in_array($this->rol, ['administrador', 'vendedor'])) {
                    $query_parts[] = "rol = :rol";
                }
            }

            if (empty($query_parts)) {
                // No hay nada que actualizar
                return true; 
            }
            $query = "UPDATE " . $this->table_name . " SET " . implode(', ', $query_parts) . " WHERE id = :id";
        } else {
            // Actualizar solo la contraseña
            if (empty($this->password)) return false; 
            $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id = :id";
            // Hashing de la nueva contraseña
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        }
        
        $stmt = $this->conn->prepare($query);

        // Vincular ID
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if (!$update_password) {
            // Vincular datos generales
            if (isset($this->nombre_completo)) {
                $this->nombre_completo = htmlspecialchars(strip_tags($this->nombre_completo));
                $stmt->bindParam(':nombre_completo', $this->nombre_completo);
            }
            if (isset($this->rol) && in_array($this->rol, ['administrador', 'vendedor'])) {
                $stmt->bindParam(':rol', $this->rol);
            }
        } else {
            // Vincular nueva contraseña hasheada
            $stmt->bindParam(':password', $this->password);
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        }

        if($stmt->execute()) {
            return $stmt->rowCount() > 0;
        }

        return false;
    }

    // [DELETE] Inactivar un usuario (Borrado lógico)
    public function delete() {
<<<<<<< HEAD
        $query = "UPDATE " . $this->table_name . " SET estado = 'inactivo' WHERE id_usuario = ?"; // Campo corregido
=======
        $query = "UPDATE " . $this->table_name . " SET estado = 'inactivo' WHERE id = ?";
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        
        $stmt = $this->conn->prepare($query);
        
        // Sanear
<<<<<<< HEAD
        $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));

        // Vincular ID
        $stmt->bindParam(1, $this->id_usuario);
=======
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular ID
        $stmt->bindParam(1, $this->id);
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

        if($stmt->execute()) {
            return $stmt->rowCount() > 0;
        }

        return false;
    }

<<<<<<< HEAD
    // --- MÉTODOS DE AUTENTICACIÓN (ADAPTADOS) ---

    /**
     * Crea un nuevo usuario en la base de datos (Usado por register())
     * @param string $nombre
     * @param string $apellido
     * @param string $usuario
     * @param string $hashedPassword
     * @param string $rol
     * @return int|bool El ID del nuevo usuario o false si falla.
     */
    public function create($nombre, $apellido, $usuario, $hashedPassword, $rol) {
        $query = "INSERT INTO " . $this->table_name . "
                  SET nombre=:nombre, 
                      apellido=:apellido,
                      usuario=:usuario, 
                      contrasena=:contrasena, 
                      rol=:rol,
                      estado='activo'";

        $stmt = $this->conn->prepare($query);
        
        // Sanear datos
        $nombre = htmlspecialchars(strip_tags($nombre));
        $apellido = htmlspecialchars(strip_tags($apellido));
        $usuario = htmlspecialchars(strip_tags($usuario));
        $rol = htmlspecialchars(strip_tags($rol));
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':contrasena', $hashedPassword); // Campo corregido
        $stmt->bindParam(':rol', $rol);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }
    
    /**
     * Busca un usuario por nombre de usuario (Usado por register y login)
     * @param string $usuario
     * @return array|bool Un array asociativo con los datos del usuario (incluye el hash de contraseña) o false.
     */
    public function findByUsuario($usuario) {
        $query = "SELECT id_usuario, nombre, apellido, usuario, contrasena, rol, estado 
                  FROM " . $this->table_name . " 
                  WHERE usuario = :usuario
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        
        // Sanear y vincular
        $usuario = htmlspecialchars(strip_tags($usuario));
        $stmt->bindParam(':usuario', $usuario);

        $stmt->execute();
        
        // Devuelve el array de datos del usuario o false
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
=======
    // --- MÉTODOS DE AUTENTICACIÓN ---

    // Verificar si la contraseña proporcionada coincide con el hash almacenado
    public function authenticate($input_password) {
        // 1. Cargar el hash de la DB (se hace en readOneByUsername, cargando $this->password)
        if ($this->readOneByUsername() && $this->estado === 'activo') {
            // 2. Verificar la contraseña
            if (password_verify($input_password, $this->password)) {
                // Contraseña correcta
                return true;
            }
        }
        // Usuario no encontrado, inactivo o contraseña incorrecta
        return false;
    }
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
}
?>