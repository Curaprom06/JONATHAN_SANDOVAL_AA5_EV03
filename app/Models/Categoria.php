<?php
<<<<<<< HEAD
// Clase para el Modelo de Categoría
// Define la estructura de la tabla 'categoria' y sus métodos de acceso a la DB.
=======
// Incluir la clase de conexión a la base de datos (asumiendo que Database.php está disponible)
// require_once 'Database.php'; 
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

class Categoria {
    // Conexión a la DB y nombre de la tabla
    private $conn;
<<<<<<< HEAD
    private $table_name = "categoria"; 

    // Propiedades del objeto (CAMPOS DE LA TABLA)
    public $id_categoria;       // Clave primaria
    public $nombre_categoria;
    public $descripcion;
    // Se elimina la propiedad $estado
    
=======
    private $table_name = "categorias";

    // Propiedades del objeto Categoria
    public $id;
    public $nombre;
    public $descripcion;
    public $estado = 'activo'; // Por defecto

>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
    public function __construct($db) {
        $this->conn = $db;
    }

    // --- MÉTODOS CRUD ---

<<<<<<< HEAD
    // [GET] Leer todas las categorías
    public function read() {
        // Consulta simple para leer todas las categorías (no hay filtro 'estado')
        $query = "SELECT id_categoria, nombre_categoria, descripcion
                  FROM " . $this->table_name . " 
                  ORDER BY nombre_categoria ASC";
=======
    // [GET] Leer todas las categorías activas
    public function read() {
        // Selecciona todas las categorías activas
        $query = "SELECT id, nombre, descripcion, estado 
                  FROM " . $this->table_name . " 
                  WHERE estado = 'activo'
                  ORDER BY nombre ASC";
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
<<<<<<< HEAD

    // [GET] Leer una sola categoría por ID
    public function readOne() {
        $query = "SELECT id_categoria, nombre_categoria, descripcion
                  FROM " . $this->table_name . " 
                  WHERE id_categoria = :id_categoria
                  LIMIT 0,1"; // Limitar a un solo resultado
        
        $stmt = $this->conn->prepare($query);
        
        // Vincular el ID
        $stmt->bindParam(':id_categoria', $this->id_categoria);
        
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Asignar valores a las propiedades del objeto
            $this->nombre_categoria = $row['nombre_categoria'];
            $this->descripcion = $row['descripcion'];
            // Se elimina la asignación de $this->estado
=======
    
    // [GET] Leer una categoría por ID
    public function readOne() {
        // Consulta para leer un registro único
        $query = "SELECT nombre, descripcion, estado 
                  FROM " . $this->table_name . " 
                  WHERE id = ? 
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Asigna valores a las propiedades del objeto
            $this->nombre = $row['nombre'];
            $this->descripcion = $row['descripcion'];
            $this->estado = $row['estado'];
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            return true;
        }

        return false;
    }
<<<<<<< HEAD

    // [POST] Crear una nueva categoría
    public function create() {
        // Consulta INSERT (sin el campo 'estado')
        $query = "INSERT INTO " . $this->table_name . "
                  SET nombre_categoria = :nombre_categoria,
                      descripcion = :descripcion"; // CORREGIDO: sin coma ni :estado

        $stmt = $this->conn->prepare($query);

        // Sanear datos (limpiar de etiquetas HTML y sanitizar)
        $this->nombre_categoria = htmlspecialchars(strip_tags($this->nombre_categoria));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        
        // Vincular valores
        $stmt->bindParam(':nombre_categoria', $this->nombre_categoria);
        $stmt->bindParam(':descripcion', $this->descripcion);
        // Se elimina el bind de :estado

        // Ejecutar la consulta
=======
    
    // [POST] Crear una nueva categoría
    public function create() {
        // Evita el error si la descripción es nula
        if ($this->descripcion === null) {
            $this->descripcion = ''; 
        }

        $query = "INSERT INTO " . $this->table_name . "
                  SET nombre = :nombre, descripcion = :descripcion, estado = 'activo'";

        $stmt = $this->conn->prepare($query);

        // Sanear y vincular valores
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));

        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);

>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // [PUT/PATCH] Actualizar una categoría existente
    public function update() {
<<<<<<< HEAD
        // Inicia la consulta
        $query = "UPDATE " . $this->table_name . " SET ";
        $updates = [];

        // Verifica y añade campos a actualizar
        if (!empty($this->nombre_categoria)) {
            $updates[] = "nombre_categoria = :nombre_categoria";
        }
        if (isset($this->descripcion)) { // Permite actualizar a un valor vacío
            $updates[] = "descripcion = :descripcion";
        }
        // Se elimina la referencia al manejo del estado

        // Si no hay campos a actualizar
        if (empty($updates)) {
            return true; // No hay error, pero tampoco hubo cambios
        }

        // Completa la consulta
        $query .= implode(", ", $updates);
        $query .= " WHERE id_categoria = :id_categoria";

        $stmt = $this->conn->prepare($query);

        // Vincular parámetros
        // Clave primaria
        $stmt->bindParam(':id_categoria', $this->id_categoria);

        // Campos a actualizar
        if (!empty($this->nombre_categoria)) {
            $nombre_categoria_saneado = htmlspecialchars(strip_tags($this->nombre_categoria));
            $stmt->bindParam(':nombre_categoria', $nombre_categoria_saneado);
        }
        if (isset($this->descripcion)) {
            $descripcion_saneada = htmlspecialchars(strip_tags($this->descripcion));
            $stmt->bindParam(':descripcion', $descripcion_saneada);
        }

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verifica si realmente se modificó alguna fila (rowCount > 0)
=======
        $query = "UPDATE " . $this->table_name . "
                  SET nombre = :nombre, descripcion = :descripcion
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Sanear y vincular valores
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->id = htmlspecialchars(strip_tags($this->id));
        
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            // Comprueba si realmente se actualizó alguna fila
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            return $stmt->rowCount() > 0;
        }

        return false;
    }

<<<<<<< HEAD

    // [DELETE] Borrado Físico: Elimina la fila de la tabla
    public function delete() {
        // Se cambia la consulta a DELETE FROM
        $query = "DELETE FROM " . $this->table_name . " 
                  WHERE id_categoria = :id_categoria";

        $stmt = $this->conn->prepare($query);

        // Sanear y vincular el ID
        $this->id_categoria = htmlspecialchars(strip_tags($this->id_categoria));
        $stmt->bindParam(':id_categoria', $this->id_categoria);

        if ($stmt->execute()) {
            // Retorna true si se afectó al menos una fila (la categoría existía)
=======
    // [DELETE] Inactivar una categoría (Borrado lógico)
    public function delete() {
        $query = "UPDATE " . $this->table_name . " SET estado = 'inactivo' WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanear
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Vincular ID
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            // Comprueba si realmente se inactivó alguna fila
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            return $stmt->rowCount() > 0;
        }

        return false;
    }
}
?>