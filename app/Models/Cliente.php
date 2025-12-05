<?php
<<<<<<< HEAD
// Modelo para la gestión de Clientes
class Cliente {
    // Conexión a la DB y nombre de la tabla
    private $conn;
    private $table_name = "cliente"; 

    // Propiedades del objeto Cliente, mapeadas a los campos de la tabla
    public $id_cliente;
    public $nombre;
    public $apellido;
    public $telefono;
    public $email;
    public $direccion;
    public $fecha_registro; 

    /**
     * Constructor que recibe la conexión a la base de datos.
     * @param PDO $db Conexión activa a la base de datos.
     */
=======
// Definición de la clase Cliente para interactuar con la tabla de clientes

class Cliente {
    // Conexión a la DB y nombre de la tabla
    private $conn;
    private $table_name = "clientes";

    // Propiedades del objeto Cliente (deben coincidir con tus campos de DB)
    public $id;
    public $nombre;
    public $apellido;
    public $documento;
    public $telefono;
    public $email;
    public $direccion;
    public $estado; // 'activo' o 'inactivo'

>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
    public function __construct($db) {
        $this->conn = $db;
    }

    // --- MÉTODOS CRUD ---

    /**
<<<<<<< HEAD
     * [GET] Leer todos los clientes.
     * @return PDOStatement Statement con los resultados.
     */
    public function read() {
        $query = "SELECT id_cliente, nombre, apellido, telefono, email, direccion, fecha_registro
                  FROM " . $this->table_name . " 
                  ORDER BY apellido, nombre ASC";
        
=======
     * [POST] Crea un nuevo cliente.
     * @return bool True si la creación fue exitosa.
     */
    public function create() {
        // Establecer el estado inicial
        $this->estado = 'activo';

        $query = "INSERT INTO " . $this->table_name . "
                  SET nombre=:nombre, apellido=:apellido, documento=:documento, 
                      telefono=:telefono, email=:email, direccion=:direccion, estado=:estado";

        $stmt = $this->conn->prepare($query);

        // Sanear y vincular valores
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->documento = htmlspecialchars(strip_tags($this->documento));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":apellido", $this->apellido);
        $stmt->bindParam(":documento", $this->documento);
        $stmt->bindParam(":telefono", $this->telefono);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":direccion", $this->direccion);
        $stmt->bindParam(":estado", $this->estado);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * [GET] Lee todos los clientes activos.
     * @return PDOStatement Statement con la lista de clientes.
     */
    public function read() {
        $query = "SELECT id, nombre, apellido, documento, telefono, email, direccion, estado 
                  FROM " . $this->table_name . " 
                  WHERE estado = 'activo' 
                  ORDER BY nombre ASC";

>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
<<<<<<< HEAD

    /**
     * [GET] Leer un solo cliente por ID y carga sus propiedades.
     * @return bool True si el cliente fue encontrado y cargado, False si no.
     */
    public function readOne() {
        $query = "SELECT id_cliente, nombre, apellido, telefono, email, direccion, fecha_registro
                  FROM " . $this->table_name . " 
                  WHERE id_cliente = :id_cliente 
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        
        // Sanear y vincular
        $this->id_cliente = htmlspecialchars(strip_tags($this->id_cliente));
        $stmt->bindParam(':id_cliente', $this->id_cliente, PDO::PARAM_INT);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Cargar propiedades del objeto
            $this->nombre = $row['nombre'];
            $this->apellido = $row['apellido'];
            $this->telefono = $row['telefono'];
            $this->email = $row['email'];
            $this->direccion = $row['direccion'];
            $this->fecha_registro = $row['fecha_registro'];
            return true;
        }

=======
    
    /**
     * [GET] Lee los datos de un único cliente por ID.
     * @return bool True si se encontró el cliente.
     */
    public function readOne() {
        $query = "SELECT id, nombre, apellido, documento, telefono, email, direccion, estado 
                  FROM " . $this->table_name . " 
                  WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nombre = $row['nombre'];
            $this->apellido = $row['apellido'];
            $this->documento = $row['documento'];
            $this->telefono = $row['telefono'];
            $this->email = $row['email'];
            $this->direccion = $row['direccion'];
            $this->estado = $row['estado'];
            return true;
        }
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        return false;
    }

    /**
<<<<<<< HEAD
     * Verifica si un email ya existe en la base de datos (excluyendo el ID actual).
     * @return bool True si el email existe, False si no.
     */
    public function emailExists() {
        $query = "SELECT id_cliente FROM " . $this->table_name . " 
                  WHERE email = :email 
                  " . ($this->id_cliente ? "AND id_cliente != :id_cliente" : "") . "
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);

        // Sanear y vincular
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);
        
        if ($this->id_cliente) {
            $stmt->bindParam(':id_cliente', $this->id_cliente, PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
    
    /**
     * [POST] Crear un nuevo cliente.
     * @return int|bool El ID del cliente creado o False si falla.
     */
    public function create() {
        // CORRECCIÓN: Definir explícitamente la fecha y hora actual en PHP
        $this->fecha_registro = date('Y-m-d H:i:s');
        
        $query = "INSERT INTO " . $this->table_name . 
                 " (nombre, apellido, telefono, email, direccion, fecha_registro) 
                 VALUES (:nombre, :apellido, :telefono, :email, :direccion, :fecha_registro)"; // <-- Añadido fecha_registro

        $stmt = $this->conn->prepare($query);

        // Sanear datos
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        
        // Vincular valores
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':apellido', $this->apellido);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':fecha_registro', $this->fecha_registro); // <-- Vinculación de la nueva fecha

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    /**
     * [PUT/PATCH] Actualizar la información de un cliente existente.
     * @return bool True si la actualización fue exitosa.
     */
    public function update() {
        $fields = [];
        // Construir la parte SET solo con los campos que se hayan cargado.
        if (!empty($this->nombre)) $fields[] = "nombre = :nombre";
        if (!empty($this->apellido)) $fields[] = "apellido = :apellido";
        if (!empty($this->telefono)) $fields[] = "telefono = :telefono";
        if (!empty($this->email)) $fields[] = "email = :email";
        if (!empty($this->direccion)) $fields[] = "direccion = :direccion";
        // NOTA: fecha_registro no se actualiza, solo se establece al crear
        
        if (empty($fields)) {
            // No hay campos para actualizar
            return true; 
        }

        $query = "UPDATE " . $this->table_name . " SET " . implode(", ", $fields) . " WHERE id_cliente = :id_cliente";
        
        $stmt = $this->conn->prepare($query);

        // Vincular valores dinámicamente
        if (!empty($this->nombre)) $stmt->bindParam(':nombre', htmlspecialchars(strip_tags($this->nombre)));
        if (!empty($this->apellido)) $stmt->bindParam(':apellido', htmlspecialchars(strip_tags($this->apellido)));
        if (!empty($this->telefono)) $stmt->bindParam(':telefono', htmlspecialchars(strip_tags($this->telefono)));
        if (!empty($this->email)) $stmt->bindParam(':email', htmlspecialchars(strip_tags($this->email)));
        if (!empty($this->direccion)) $stmt->bindParam(':direccion', htmlspecialchars(strip_tags($this->direccion)));
        
        // Vincular el ID
        $stmt->bindParam(':id_cliente', $this->id_cliente, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true; 
        }

=======
     * [PUT/PATCH] Actualiza la información de un cliente existente.
     * @return bool True si la actualización fue exitosa.
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET nombre = :nombre, apellido = :apellido, documento = :documento, 
                      telefono = :telefono, email = :email, direccion = :direccion
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanear y vincular valores
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->apellido = htmlspecialchars(strip_tags($this->apellido));
        $this->documento = htmlspecialchars(strip_tags($this->documento));
        $this->telefono = htmlspecialchars(strip_tags($this->telefono));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->direccion = htmlspecialchars(strip_tags($this->direccion));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':apellido', $this->apellido);
        $stmt->bindParam(':documento', $this->documento);
        $stmt->bindParam(':telefono', $this->telefono);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':direccion', $this->direccion);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        return false;
    }

    /**
<<<<<<< HEAD
     * [DELETE] Borrado físico (Eliminar permanentemente) un cliente.
     * @return bool True si la eliminación fue exitosa (se eliminó al menos una fila).
     */
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_cliente = :id_cliente";
        
        $stmt = $this->conn->prepare($query);

        // Sanear y vincular
        $this->id_cliente = htmlspecialchars(strip_tags($this->id_cliente));
        $stmt->bindParam(':id_cliente', $this->id_cliente, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return $stmt->rowCount() > 0;
        }

=======
     * [DELETE] Inactiva un cliente (borrado lógico).
     * @return bool True si la inactivación fue exitosa.
     */
    public function delete() {
        $query = "UPDATE " . $this->table_name . " SET estado = 'inactivo' WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        return false;
    }
}
?>