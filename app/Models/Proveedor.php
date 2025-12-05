<?php
<<<<<<< HEAD
// Incluir la clase de conexión a la base de datos (se usa Autoload.php)

class Proveedor {
    // Conexión a la DB y nombre de la tabla
    private $conn;
    private $table_name = "proveedor";

    // Propiedades del objeto, coinciden con los campos de la tabla
    public $id_proveedor;
    public $nombre;
    public $telefono;
    public $email;
    public $direccion;
    public $estado = 'activo'; // Añadimos 'estado' para el borrado lógico
=======
// Definición de la clase Proveedor para interactuar con la tabla de proveedores

class Proveedor {
    private $conn;
    private $table_name = "proveedores";

    // Propiedades del objeto Proveedor (ajusta según tus campos de DB)
    public $id;
    public $nombre_empresa;
    public $contacto_principal;
    public $telefono;
    public $email;
    public $nit; 
    public $direccion;
    public $estado; // Para borrado lógico
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- MÉTODOS CRUD ---

<<<<<<< HEAD
    /**
     * [GET] Lee todos los proveedores activos.
     * @return PDOStatement
     */
    public function read() {
        // Selecciona todos los campos
        $query = "SELECT id_proveedor, nombre, telefono, email, direccion, estado 
                  FROM " . $this->table_name . " 
                  WHERE estado = 'activo' 
                  ORDER BY nombre ASC";
        
=======
    // [GET] Leer todos los proveedores
    public function read() {
        $query = "SELECT id, nombre_empresa, contacto_principal, telefono, email, nit, direccion, estado 
                  FROM " . $this->table_name . " 
                  WHERE estado = 'activo'
                  ORDER BY nombre_empresa ASC";
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
<<<<<<< HEAD

    /**
     * [POST] Crea un nuevo proveedor.
     * @return bool
     */
    public function create() {
        // La consulta de inserción
        $query = "INSERT INTO " . $this->table_name . "
                  SET 
                    nombre = :nombre,
                    telefono = :telefono,
                    email = :email,
                    direccion = :direccion,
                    estado = :estado"; // Incluimos el estado

        $stmt = $this->conn->prepare($query);
        
        // Saneamiento de datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        
        // Vinculación de valores
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":estado", $this->estado);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Guarda el ID generado automáticamente
            $this->id_proveedor = $this->conn->lastInsertId();
=======
    
    // [POST] Crear un nuevo proveedor
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET nombre_empresa=:nombre_empresa, contacto_principal=:contacto_principal, 
                  telefono=:telefono, email=:email, nit=:nit, direccion=:direccion, estado='activo'";

        $stmt = $this->conn->prepare($query);

        // Sanear y vincular valores
        $this->nombre_empresa = htmlspecialchars(strip_tags($this->nombre_empresa));
        $this->contacto_principal = htmlspecialchars(strip_tags($this->contacto_principal));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->nit = htmlspecialchars(strip_tags($this->nit));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));

        $stmt->bindParam(":nombre_empresa", $this->nombre_empresa);
        $stmt->bindParam(":contacto_principal", $this->contacto_principal);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":nit", $this->nit);
        $stmt->bindParam(":direccion", $this->direccion);

        if ($stmt->execute()) {
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            return true;
        }
        return false;
    }

<<<<<<< HEAD
    /**
     * [GET] Lee un solo proveedor por ID y carga sus propiedades.
     * @return bool
     */
    public function readOne() {
        $query = "SELECT id_proveedor, nombre, telefono, email, direccion, estado 
                  FROM " . $this->table_name . " 
                  WHERE id_proveedor = :id_proveedor 
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        // Vinculación del ID
        $stmt->bindParam(':id_proveedor', $this->id_proveedor);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Asigna los valores recuperados a las propiedades del objeto
            $this->nombre = $row['nombre'];
            $this->telefono = $row['telefono'];
            $this->email = $row['email'];
=======
    // [GET] Leer un solo proveedor
    public function readOne() {
        $query = "SELECT id, nombre_empresa, contacto_principal, telefono, email, nit, direccion, estado 
                  FROM " . $this->table_name . " 
                  WHERE id = ? AND estado = 'activo' LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Asignar valores a las propiedades del objeto
            $this->nombre_empresa = $row['nombre_empresa'];
            $this->contacto_principal = $row['contacto_principal'];
            $this->telefono = $row['telefono'];
            $this->email = $row['email'];
            $this->nit = $row['nit'];
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            $this->direccion = $row['direccion'];
            $this->estado = $row['estado'];
            return true;
        }
        return false;
    }
<<<<<<< HEAD

    /**
     * [PUT/PATCH] Actualiza los datos de un proveedor.
     * @return bool
     */
    public function update() {
        // Query de actualización, solo actualiza los campos proporcionados/existentes
        $query = "UPDATE " . $this->table_name . "
                  SET 
                    nombre = COALESCE(:nombre, nombre),
                    telefono = COALESCE(:telefono, telefono),
                    email = COALESCE(:email, email),
                    direccion = COALESCE(:direccion, direccion)
                  WHERE 
                    id_proveedor = :id_proveedor";

        $stmt = $this->conn->prepare($query);

        // Saneamiento y vinculación de valores (los campos pueden ser null si no se actualizan)
        $nombre = !empty($this->nombre) ? htmlspecialchars(strip_tags($this->nombre)) : null;
        $telefono = !empty($this->telefono) ? htmlspecialchars(strip_tags($this->telefono)) : null;
        $email = !empty($this->email) ? htmlspecialchars(strip_tags($this->email)) : null;
        $direccion = !empty($this->direccion) ? htmlspecialchars(strip_tags($this->direccion)) : null;
        
        $stmt->bindParam(':id_proveedor', $this->id_proveedor);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':direccion', $direccion);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Retorna true si se ejecutó y si se afectó al menos una fila (o si no hubo cambios, lo cual es aceptable para un PUT/PATCH)
            return true;
        }
        return false;
    }

    /**
     * [DELETE] Realiza un borrado lógico (cambia estado a 'inactivo').
     * @return bool
     */
    public function delete() {
        $query = "UPDATE " . $this->table_name . " 
                  SET estado = 'inactivo' 
                  WHERE id_proveedor = :id_proveedor";

        $stmt = $this->conn->prepare($query);
        
        // Vinculación del ID
        $stmt->bindParam(':id_proveedor', $this->id_proveedor);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Verifica si un proveedor con el mismo email ya existe.
     * @return bool
     */
    public function emailExists() {
        $query = "SELECT id_proveedor
                  FROM " . $this->table_name . " 
                  WHERE email = :email AND estado = 'activo'
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);
        
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
=======
    
    // [PUT] Actualizar un proveedor existente
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET nombre_empresa = :nombre_empresa, contacto_principal = :contacto_principal, 
                  telefono = :telefono, email = :email, nit = :nit, direccion = :direccion
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanear y vincular valores
        $this->nombre_empresa = htmlspecialchars(strip_tags($this->nombre_empresa));
        $this->contacto_principal = htmlspecialchars(strip_tags($this->contacto_principal));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->nit = htmlspecialchars(strip_tags($this->nit));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(':nombre_empresa', $this->nombre_empresa);
        $stmt->bindParam(':contacto_principal', $this->contacto_principal);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':nit', $this->nit);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // [DELETE] Inactivar un proveedor (borrado lógico)
    public function delete() {
        $query = "UPDATE " . $this->table_name . " SET estado = 'inactivo' WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
    }
}
?>