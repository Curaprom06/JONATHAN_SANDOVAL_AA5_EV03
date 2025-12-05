<?php
// Modelo: app/Models/Producto.php

// Definición de la clase Producto para interactuar con la tabla 'producto'

class Producto {
    // Conexión a la DB y nombre de la tabla
    private $conn;
    private $table_name = "producto"; 

    // Propiedades del objeto Producto (deben coincidir con los campos de la DB)
    public $id_producto;        // Clave primaria
    public $nombre;
    public $descripcion;
    public $precio;
    public $stock;
    public $unidad_medida;
    public $fecha_registro;
    public $id_categoria;       // Clave foránea
    public $estado_producto = 'Activo'; // Para borrado lógico
    public $categoria_nombre; // Propiedad auxiliar para JOINS

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- MÉTODOS CRUD ---

    /**
     * [GET] Leer todos los productos activos (incluye el nombre de la categoría)
     * @return PDOStatement
     */
    public function read() {
        // La consulta hace un JOIN con la tabla 'categoria' para obtener el nombre
        $query = "SELECT 
                    p.id_producto, p.nombre, p.descripcion, p.precio, p.stock, 
                    p.unidad_medida, p.fecha_registro, p.estado_producto, p.id_categoria, 
                    c.nombre_categoria as categoria_nombre 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                  WHERE p.estado_producto = 'Activo'
                  ORDER BY p.nombre ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
<<<<<<< HEAD

    /**
     * [GET] Leer un solo producto por ID (incluye el nombre de la categoría)
     * @return bool Retorna true si el producto existe, false si no. Los datos se cargan en las propiedades del objeto.
     */
    public function readOne() {
        $query = "SELECT 
                    p.id_producto, p.nombre, p.descripcion, p.precio, p.stock, 
                    p.unidad_medida, p.fecha_registro, p.estado_producto, p.id_categoria, 
                    c.nombre_categoria as categoria_nombre 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                  WHERE p.id_producto = :id_producto
                  LIMIT 0,1";
=======
    
    // [POST] Crear un nuevo producto
    public function create() {
        // Consulta para insertar un producto
        $query = "INSERT INTO " . $this->table_name . "
                  SET nombre=:nombre, precio=:precio, categoria_id=:categoria_id, 
                      stock=:stock, unidad_medida=:unidad_medida, estado='activo'";

        // Preparar la consulta
        $stmt = $this->conn->prepare($query);

        // Sanear y vincular valores
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->categoria_id = htmlspecialchars(strip_tags($this->categoria_id));
        $this->stock = htmlspecialchars(strip_tags($this->stock));
        $this->unidad_medida = htmlspecialchars(strip_tags($this->unidad_medida));

        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":categoria_id", $this->categoria_id);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":unidad_medida", $this->unidad_medida);

        // Ejecutar la consulta
        if($stmt->execute()) {
            return true;
        }

        return false;
    }
    
    // [GET] Leer un solo producto
    public function readOne() {
        // Consulta SQL para seleccionar un producto por ID
        $query = "SELECT p.id, p.nombre, p.precio, p.stock, p.unidad_medida, p.estado, p.categoria_id, c.nombre as categoria_nombre 
                  FROM " . $this->table_name . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.id = ? AND p.estado = 'activo' LIMIT 0,1";
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

        $stmt = $this->conn->prepare($query);

        // Sanear y vincular ID
        $this->id_producto = htmlspecialchars(strip_tags($this->id_producto));
        $stmt->bindParam(':id_producto', $this->id_producto);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Cargar datos en las propiedades del objeto
            $this->id_producto = (int)$row['id_producto']; // Aseguramos que el ID se cargue correctamente
            $this->nombre = $row['nombre'];
<<<<<<< HEAD
            $this->descripcion = $row['descripcion'];
            $this->precio = (float)$row['precio'];
            $this->stock = (int)$row['stock'];
            $this->unidad_medida = $row['unidad_medida'];
            $this->fecha_registro = $row['fecha_registro'];
            $this->id_categoria = (int)$row['id_categoria'];
            $this->estado_producto = $row['estado_producto'];
            $this->categoria_nombre = $row['categoria_nombre']; // Propiedad auxiliar
            return true;
        }

        return false;
    }

    /**
     * [POST] Crear un nuevo producto en la base de datos
     * @return bool true si la creación es exitosa, false si falla.
     */
    public function create() {
        // Consulta INSERT
        $query = "INSERT INTO " . $this->table_name . "
                  SET 
                      nombre = :nombre, 
                      descripcion = :descripcion, 
                      precio = :precio, 
                      stock = :stock, 
                      unidad_medida = :unidad_medida, 
                      fecha_registro = NOW(), 
                      id_categoria = :id_categoria,
                      estado_producto = :estado_producto";

        $stmt = $this->conn->prepare($query);

        // Sanear y vincular valores
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->stock = htmlspecialchars(strip_tags($this->stock));
        $this->unidad_medida = htmlspecialchars(strip_tags($this->unidad_medida));
        $this->id_categoria = htmlspecialchars(strip_tags($this->id_categoria));
        $this->estado_producto = htmlspecialchars(strip_tags($this->estado_producto));
        
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':unidad_medida', $this->unidad_medida);
        $stmt->bindParam(':id_categoria', $this->id_categoria);
        $stmt->bindParam(':estado_producto', $this->estado_producto);

        if ($stmt->execute()) {
=======
            $this->precio = $row['precio'];
            $this->categoria_id = $row['categoria_id'];
            $this->stock = $row['stock'];
            $this->unidad_medida = $row['unidad_medida'];
            $this->estado = $row['estado'];
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
            return true;
        }

        return false;
    }
    
    /**
     * [PUT/PATCH] Actualizar un producto existente
     * @return bool true si la ejecución de la consulta es exitosa (sin errores de DB), false si falla.
     * * CORRECCIÓN: Se modificó para retornar true si $stmt->execute() es exitoso, 
     * independientemente de si $stmt->rowCount() > 0.
     */
    public function update() {
        $query = "UPDATE " . $this->table_name . "
<<<<<<< HEAD
                  SET 
                      nombre = :nombre, 
                      descripcion = :descripcion, 
                      precio = :precio, 
                      stock = :stock, 
                      unidad_medida = :unidad_medida, 
                      id_categoria = :id_categoria,
                      estado_producto = :estado_producto
                  WHERE id_producto = :id_producto";
=======
                  SET nombre = :nombre, precio = :precio, categoria_id = :categoria_id, stock = :stock, unidad_medida = :unidad_medida
                  WHERE id = :id";
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

        $stmt = $this->conn->prepare($query);

        // Sanear y vincular valores
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->precio = htmlspecialchars(strip_tags($this->precio));
        $this->categoria_id = htmlspecialchars(strip_tags($this->categoria_id));
        $this->stock = htmlspecialchars(strip_tags($this->stock));
        $this->unidad_medida = htmlspecialchars(strip_tags($this->unidad_medida));
<<<<<<< HEAD
        $this->id_categoria = htmlspecialchars(strip_tags($this->id_categoria));
        $this->estado_producto = htmlspecialchars(strip_tags($this->estado_producto));
        $this->id_producto = htmlspecialchars(strip_tags($this->id_producto)); 

=======
        $this->id = htmlspecialchars(strip_tags($this->id));
        
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':precio', $this->precio);
        $stmt->bindParam(':categoria_id', $this->categoria_id);
        $stmt->bindParam(':stock', $this->stock);
        $stmt->bindParam(':unidad_medida', $this->unidad_medida);
<<<<<<< HEAD
        $stmt->bindParam(':id_categoria', $this->id_categoria);
        $stmt->bindParam(':estado_producto', $this->estado_producto);
        $stmt->bindParam(':id_producto', $this->id_producto);
=======
        $stmt->bindParam(':id', $this->id);
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

        if ($stmt->execute()) {
            // Modificación: Retorna true si la consulta se ejecuta sin errores de DB.
            return true;
        }

        return false;
    }

    /**
     * [DELETE] Inactivar un producto (borrado lógico: estado_producto = 'inactivo')
     * @return bool true si la operación es exitosa, false si falla.
     */
    public function delete() {
        $query = "UPDATE " . $this->table_name . " SET estado_producto = 'Inactivo' WHERE id_producto = :id_producto";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanear y vincular ID
        $this->id_producto = htmlspecialchars(strip_tags($this->id_producto));
        $stmt->bindParam(':id_producto', $this->id_producto);

        if ($stmt->execute()) {
<<<<<<< HEAD
            return $stmt->rowCount() > 0;
=======
            return true;
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        }

        return false;
    }
    
<<<<<<< HEAD
    /**
     * Actualiza solo el stock de un producto (usado en transacciones de venta)
     * @param int $cantidad La cantidad a restar (o sumar, si el valor es negativo)
     * @return bool true si la operación es exitosa, false si falla.
     */
    public function updateStock($cantidad) {
        // CORRECCIÓN: Se renombraron los parámetros :cantidad a :cantidad_restar y :cantidad_check 
        // para evitar el error 'SQLSTATE[HY093]: Invalid parameter number'
        
        $query = "UPDATE " . $this->table_name . " SET stock = stock - :cantidad_restar WHERE id_producto = :id_producto AND stock >= :cantidad_check";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanear y vincular
        $cantidad_saneada = htmlspecialchars(strip_tags($cantidad));
        $this->id_producto = htmlspecialchars(strip_tags($this->id_producto));
        
        // Vincular la cantidad dos veces a los parámetros renombrados
        $stmt->bindParam(':cantidad_restar', $cantidad_saneada, PDO::PARAM_INT);
        $stmt->bindParam(':cantidad_check', $cantidad_saneada, PDO::PARAM_INT);
        $stmt->bindParam(':id_producto', $this->id_producto, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            // Verifica que se haya actualizado exactamente 1 fila (para asegurar que el stock era suficiente)
            return $stmt->rowCount() == 1;
=======
    // --- NUEVO MÉTODO PARA GESTIÓN DE VENTA ---

    /**
     * Actualiza el stock de un producto después de una venta.
     * @param int $producto_id ID del producto.
     * @param int $cantidad_vendida Cantidad a restar del stock.
     * @return bool True si la actualización fue exitosa.
     */
    public function updateStock($producto_id, $cantidad_vendida) {
        // SQL para restar la cantidad vendida al stock actual.
        // La condición 'stock >= :cantidad_vendida' evita que el stock se vuelva negativo.
        $query = "UPDATE " . $this->table_name . " 
                  SET stock = stock - :cantidad_vendida 
                  WHERE id = :id AND stock >= :cantidad_vendida";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":cantidad_vendida", $cantidad_vendida, PDO::PARAM_INT);
        $stmt->bindParam(":id", $producto_id, PDO::PARAM_INT);

        // Si la ejecución es exitosa Y se afectó al menos una fila, significa que la venta es válida.
        if ($stmt->execute() && $stmt->rowCount() > 0) {
            return true;
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        }
        return false;
    }
}
?>