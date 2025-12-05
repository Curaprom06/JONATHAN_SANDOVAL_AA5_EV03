<?php
<<<<<<< HEAD
// Modelo para la tabla 'venta' (Encabezado de la venta)

class Venta {
    private $conn;
    private $table_name = "venta";

    // Propiedades de la tabla
    public $id_venta;
    public $fecha;
    public $total;
    public $id_cliente;
    public $id_usuario; // El usuario que realiza la venta
=======
// Definición de la clase Venta para el encabezado de la transacción

class Venta {
    private $conn;
    private $table_name = "ventas";

    // Propiedades del objeto Venta
    public $id;
    public $cliente_id;
    public $fecha_venta;
    public $total;
    public $estado; // 'activa', 'anulada'
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
<<<<<<< HEAD
     * Crea un nuevo encabezado de venta con total inicial en 0.
     * @return int|bool El ID de la venta insertada o false si falla.
     */
    public function create() {
        // La fecha y el total se gestionan en la transacción del Controller.
        // Aquí insertamos los datos obligatorios, dejando el total en 0
        // para ser actualizado después de insertar los detalles.
        $query = "INSERT INTO " . $this->table_name . " 
                  SET id_cliente = :id_cliente, 
                      id_usuario = :id_usuario, 
                      fecha = NOW(), 
                      total = 0.00"; 

        $stmt = $this->conn->prepare($query);

        // Sanear id_usuario (Obligatorio)
        $this->id_usuario = htmlspecialchars(strip_tags($this->id_usuario));
        $stmt->bindParam(":id_usuario", $this->id_usuario);

        // CORRECCIÓN CRÍTICA: Manejar id_cliente (Opcional) con tipo PDO::PARAM_NULL si es null
        // Esto resuelve el error SQLSTATE[HY093]
        $sanitized_id_cliente = $this->id_cliente !== null ? htmlspecialchars(strip_tags($this->id_cliente)) : null;

        if ($sanitized_id_cliente === null || $sanitized_id_cliente === '') {
            $stmt->bindValue(":id_cliente", null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(":id_cliente", $sanitized_id_cliente);
        }


        if ($stmt->execute()) {
            // Retorna el ID de la fila insertada (Clave primaria de la venta)
            return $this->conn->lastInsertId();
        }

        return false;
    }

    /**
     * Actualiza el campo 'total' de una venta después de procesar todos los detalles.
     * @param int $id_venta ID de la venta a actualizar.
     * @param float $total El total final calculado.
     * @return bool True si la actualización es exitosa, false en caso contrario.
     */
    public function updateTotal($id_venta, $total) {
        $query = "UPDATE " . $this->table_name . "
                  SET total = :total
                  WHERE id_venta = :id_venta";
=======
     * Inicia una nueva venta (encabezado) y obtiene el ID generado.
     * @return int|false El ID de la venta insertada o false si falla.
     */
    public function create() {
        // Establece la zona horaria y la fecha de la venta
        date_default_timezone_set('America/Bogota');
        $this->fecha_venta = date('Y-m-d H:i:s');
        $this->estado = 'activa';

        // La consulta inserta la venta y el total será actualizado después
        $query = "INSERT INTO " . $this->table_name . "
                  SET cliente_id=:cliente_id, fecha_venta=:fecha_venta, total=0, estado=:estado";
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

        $stmt = $this->conn->prepare($query);

        // Sanear y vincular
<<<<<<< HEAD
        $id_venta = htmlspecialchars(strip_tags($id_venta));
        // No es necesario sanear el total si ya se verificó que es un float

        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':id_venta', $id_venta);
=======
        $this->cliente_id = htmlspecialchars(strip_tags($this->cliente_id));
        
        $stmt->bindParam(":cliente_id", $this->cliente_id);
        $stmt->bindParam(":fecha_venta", $this->fecha_venta);
        $stmt->bindParam(":estado", $this->estado);

        if ($stmt->execute()) {
            // Devuelve el ID de la última inserción (el ID de la nueva venta)
            return $this->conn->lastInsertId();
        }
        return false;
    }
    
    /**
     * Actualiza el total de la venta después de calcularlo con los detalles.
     * @param int $venta_id El ID de la venta a actualizar.
     * @param float $nuevo_total El total final de la venta.
     * @return bool True si la actualización fue exitosa.
     */
    public function updateTotal($venta_id, $nuevo_total) {
        $query = "UPDATE " . $this->table_name . " SET total = :total WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":total", $nuevo_total);
        $stmt->bindParam(":id", $venta_id);
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

        if ($stmt->execute()) {
            return true;
        }
<<<<<<< HEAD

=======
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        return false;
    }

    /**
<<<<<<< HEAD
     * Lee el encabezado de una venta por ID.
     * @param int $id_venta ID de la venta.
     * @return bool True si encuentra la venta, y carga sus propiedades.
     */
    public function readOne($id_venta) {
        $query = "SELECT v.id_venta, v.fecha, v.total, v.id_cliente, v.id_usuario,
                         c.nombre AS nombre_cliente, u.usuario AS nombre_usuario
                  FROM " . $this->table_name . " v
                  LEFT JOIN cliente c ON v.id_cliente = c.id_cliente
                  LEFT JOIN usuario u ON v.id_usuario = u.id_usuario
                  WHERE v.id_venta = :id_venta
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        
        // Vincular y ejecutar
        $id_venta = htmlspecialchars(strip_tags($id_venta));
        $stmt->bindParam(':id_venta', $id_venta);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            // Cargar propiedades del objeto
            $this->id_venta = $row['id_venta'];
            $this->fecha = $row['fecha'];
            $this->total = $row['total'];
            $this->id_cliente = $row['id_cliente'];
            $this->id_usuario = $row['id_usuario'];
            // Se puede retornar el row completo si el controlador lo necesita para info extra
            return $row; 
        }

        return false;
    }

    /**
     * Lee todos los encabezados de venta (para el reporte de listado).
     * @return PDOStatement Statement con el resultado.
     */
    public function readAll() {
        $query = "SELECT v.id_venta, v.fecha, v.total,
                         c.nombre AS nombre_cliente, 
                         u.usuario AS nombre_usuario
                  FROM " . $this->table_name . " v
                  LEFT JOIN cliente c ON v.id_cliente = c.id_cliente
                  LEFT JOIN usuario u ON v.id_usuario = u.id_usuario
                  ORDER BY v.fecha DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
=======
     * Lee todas las ventas activas.
     * @return PDOStatement Statement con la lista de ventas.
     */
    public function readAll() {
        $query = "SELECT 
                    v.id, v.fecha_venta, v.total, v.estado,
                    c.nombre AS nombre_cliente, c.apellido AS apellido_cliente
                  FROM " . $this->table_name . " v
                  LEFT JOIN clientes c ON v.cliente_id = c.id
                  WHERE v.estado = 'activa'
                  ORDER BY v.fecha_venta DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    /**
     * Lee el encabezado de una única venta.
     * @return bool True si se encontró la venta.
     */
    public function readOne() {
        $query = "SELECT 
                    v.id, v.fecha_venta, v.total, v.estado, v.cliente_id,
                    c.nombre AS nombre_cliente, c.apellido AS apellido_cliente
                  FROM " . $this->table_name . " v
                  LEFT JOIN clientes c ON v.cliente_id = c.id
                  WHERE v.id = ? AND v.estado = 'activa' LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->cliente_id = $row['cliente_id'];
            $this->fecha_venta = $row['fecha_venta'];
            $this->total = $row['total'];
            $this->estado = $row['estado'];
            // Se puede almacenar el nombre completo del cliente si es necesario
            $this->nombre_cliente_completo = $row['nombre_cliente'] . ' ' . $row['apellido_cliente']; 
            return true;
        }
        return false;
    }
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
}
?>