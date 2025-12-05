<?php
<<<<<<< HEAD
// Modelo para la tabla 'detalle_venta'

class DetalleVenta {
    private $conn;
    private $table_name = "detalle_venta";

    // Propiedades de la tabla
    public $id_detalle;
    public $id_venta;
    public $id_producto;
    public $cantidad;
    public $precio_unitario;
=======
// Definición de la clase DetalleVenta para las líneas de la factura

class DetalleVenta {
    private $conn;
    private $table_name = "detalle_ventas";

    // Propiedades del objeto DetalleVenta
    public $venta_id;
    public $producto_id;
    public $cantidad;
    public $precio_unitario; // Precio al momento de la venta
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
    public $subtotal;

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
<<<<<<< HEAD
     * Crea un nuevo registro de detalle de venta.
     * @param int $id_venta
     * @param int $id_producto
     * @param int $cantidad
     * @param float $precio_unitario
     * @param float $subtotal
     * @return bool True si la inserción es exitosa, false en caso contrario.
     */
    public function create($id_venta, $id_producto, $cantidad, $precio_unitario, $subtotal) {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET id_venta = :id_venta, 
                      id_producto = :id_producto, 
                      cantidad = :cantidad, 
                      precio_unitario = :precio_unitario, 
                      subtotal = :subtotal";

        $stmt = $this->conn->prepare($query);

        // Sanear datos (id_venta, id_producto)
        $id_venta = htmlspecialchars(strip_tags($id_venta));
        $id_producto = htmlspecialchars(strip_tags($id_producto));
        // No es necesario sanear floats/ints si ya se verificó que son numéricos

        // Vincular parámetros
        $stmt->bindParam(":id_venta", $id_venta);
        $stmt->bindParam(":id_producto", $id_producto);
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":precio_unitario", $precio_unitario);
        $stmt->bindParam(":subtotal", $subtotal);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Obtiene todos los detalles para un ID de venta específico.
     * @param int $id_venta
     * @return PDOStatement Statement con el resultado.
     */
    public function readByVentaId($id_venta) {
        $query = "SELECT dv.id_detalle, dv.id_producto, dv.cantidad, dv.precio_unitario, dv.subtotal,
                         p.nombre AS nombre_producto
                  FROM " . $this->table_name . " dv
                  INNER JOIN producto p ON dv.id_producto = p.id_producto
                  WHERE dv.id_venta = :id_venta
                  ORDER BY dv.id_detalle ASC";
=======
     * Inserta una línea de detalle para una venta específica.
     * @return bool True si la inserción fue exitosa.
     */
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET venta_id=:venta_id, producto_id=:producto_id, cantidad=:cantidad, 
                  precio_unitario=:precio_unitario, subtotal=:subtotal";
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

        $stmt = $this->conn->prepare($query);

        // Sanear y vincular
<<<<<<< HEAD
        $id_venta = htmlspecialchars(strip_tags($id_venta));
        $stmt->bindParam(':id_venta', $id_venta);
=======
        $this->venta_id = htmlspecialchars(strip_tags($this->venta_id));
        $this->producto_id = htmlspecialchars(strip_tags($this->producto_id));
        $this->cantidad = htmlspecialchars(strip_tags($this->cantidad));
        $this->precio_unitario = htmlspecialchars(strip_tags($this->precio_unitario));
        $this->subtotal = htmlspecialchars(strip_tags($this->subtotal));

        $stmt->bindParam(":venta_id", $this->venta_id);
        $stmt->bindParam(":producto_id", $this->producto_id);
        $stmt->bindParam(":cantidad", $this->cantidad);
        $stmt->bindParam(":precio_unitario", $this->precio_unitario);
        $stmt->bindParam(":subtotal", $this->subtotal);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Obtiene todos los detalles de una venta por su ID.
     * Hace un JOIN con la tabla de productos para obtener el nombre.
     * @param int $venta_id ID de la venta.
     * @return PDOStatement Statement con las líneas de detalle.
     */
    public function readByVentaId($venta_id) {
        $query = "SELECT 
                    dv.producto_id, dv.cantidad, dv.precio_unitario, dv.subtotal, 
                    p.nombre AS nombre_producto, p.unidad_medida
                  FROM " . $this->table_name . " dv
                  LEFT JOIN productos p ON dv.producto_id = p.id
                  WHERE dv.venta_id = :venta_id
                  ORDER BY p.nombre ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":venta_id", $venta_id, PDO::PARAM_INT);
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        $stmt->execute();

        return $stmt;
    }
}
?>