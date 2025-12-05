<?php
<<<<<<< HEAD
// Modelo Reporte.php
// Contiene la lógica para generar reportes analíticos basados en la información de otras tablas.
=======
// Incluir la clase de conexión a la base de datos
// require_once 'Database.php'; 
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

class Reporte {
    private $conn;

<<<<<<< HEAD
    // Propiedades que se usarán como filtros para los reportes
    public $fecha_inicio;
    public $fecha_fin;
    public $usuario_id;
=======
    // Propiedades para filtros de tiempo y usuario
    public $fecha_inicio;
    public $fecha_fin;
    public $usuario_id; // Para filtrar por vendedor
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

    public function __construct($db) {
        $this->conn = $db;
    }

<<<<<<< HEAD
    // --- MÉTODOS DE CONSULTA DE REPORTES ---

    /**
     * Reporte de Resumen Financiero (Ventas y Ganancias) en un rango de fechas.
     * @return array Array asociativo con los indicadores clave (KPIs).
     */
    public function getResumenVentas() {
        // Esta consulta es compleja ya que calcula costos, ventas y ganancias por producto
        // y luego los suma para el resumen.
        $query = "SELECT 
                    SUM(dv.cantidad * dv.precio_unitario) AS ingresos_netos,
                    SUM(dv.cantidad * p.costo) AS costo_total_mercancia,
                    SUM((dv.cantidad * dv.precio_unitario) - (dv.cantidad * p.costo)) AS ganancia_bruta,
                    COUNT(DISTINCT v.id_venta) AS total_transacciones
                  FROM venta v
                  JOIN detalle_venta dv ON v.id_venta = dv.id_venta
                  JOIN producto p ON dv.id_producto = p.id
                  WHERE v.fecha_venta BETWEEN :fecha_inicio AND :fecha_fin";

        // Filtro opcional por vendedor
        if ($this->usuario_id) {
            $query .= " AND v.id_vendedor = :usuario_id";
        }

        $stmt = $this->conn->prepare($query);

        // Sanear y vincular
        $fecha_inicio_db = htmlspecialchars(strip_tags($this->fecha_inicio)) . " 00:00:00";
        $fecha_fin_db = htmlspecialchars(strip_tags($this->fecha_fin)) . " 23:59:59";
        
        $stmt->bindParam(':fecha_inicio', $fecha_inicio_db);
        $stmt->bindParam(':fecha_fin', $fecha_fin_db);
        
        if ($this->usuario_id) {
            $usuario_id_saneado = htmlspecialchars(strip_tags($this->usuario_id));
            $stmt->bindParam(':usuario_id', $usuario_id_saneado);
        }

        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna una sola fila de resumen
    }

    /**
     * Reporte de Top 10 Productos más vendidos por cantidad en un rango de fechas.
     * @return PDOStatement Lista de productos ordenados por cantidad vendida.
     */
    public function getTopProductos() {
        $query = "SELECT 
                    p.id, p.nombre, p.unidad_medida, c.nombre AS categoria,
                    SUM(dv.cantidad) AS cantidad_vendida,
                    SUM(dv.cantidad * dv.precio_unitario) AS ingresos_producto
                  FROM detalle_venta dv
                  JOIN venta v ON dv.id_venta = v.id_venta
                  JOIN producto p ON dv.id_producto = p.id
                  JOIN categoria c ON p.categoria_id = c.id
                  WHERE v.fecha_venta BETWEEN :fecha_inicio AND :fecha_fin
                  GROUP BY p.id, p.nombre, p.unidad_medida, categoria
                  ORDER BY cantidad_vendida DESC
                  LIMIT 10";

        $stmt = $this->conn->prepare($query);
        
        // Sanear y vincular (Se reutilizan las propiedades del modelo)
        $fecha_inicio_db = htmlspecialchars(strip_tags($this->fecha_inicio)) . " 00:00:00";
        $fecha_fin_db = htmlspecialchars(strip_tags($this->fecha_fin)) . " 23:59:59";
        
        $stmt->bindParam(':fecha_inicio', $fecha_inicio_db);
        $stmt->bindParam(':fecha_fin', $fecha_fin_db);
        
        $stmt->execute();
        return $stmt;
    }

    /**
     * Reporte de Inventario Bajo Stock.
     * @param int $limite El umbral de stock para considerarse "bajo".
     * @return PDOStatement Lista de productos con stock menor o igual al límite.
     */
    public function getBajoStock($limite) {
        $query = "SELECT 
                    p.id, p.nombre, p.stock, p.unidad_medida, c.nombre AS categoria
                  FROM producto p
                  JOIN categoria c ON p.categoria_id = c.id
                  WHERE p.stock <= :limite AND p.estado = 'activo'
                  ORDER BY p.stock ASC";

        $stmt = $this->conn->prepare($query);
        
        // Sanear y vincular
        $limite_saneado = htmlspecialchars(strip_tags($limite));
        $stmt->bindParam(':limite', $limite_saneado, PDO::PARAM_INT);
        
        $stmt->execute();
=======
    // Método principal para el reporte de ventas resumido (KPIs)
    public function getKpiResumenVentas() {
        // La consulta calcula el total de ingresos, el costo total (asumiendo que hay un costo asociado a productos)
        // y la ganancia bruta.
        // NOTA: Se asume que la tabla 'detalle_venta' tiene una columna para el costo del producto al momento de la venta. 
        // Si no existe 'costo_unitario' en detalle_venta, se debe ajustar esta consulta.

        $query = "SELECT 
                    SUM(dv.cantidad * dv.precio_unitario) AS ingresos_totales,
                    SUM(dv.cantidad * p.costo_unitario) AS costo_total, -- Asumiendo columna costo_unitario en Productos
                    (SUM(dv.cantidad * dv.precio_unitario) - SUM(dv.cantidad * p.costo_unitario)) AS ganancia_bruta,
                    COUNT(DISTINCT v.id) AS total_ventas_unidades,
                    AVG(v.total) AS promedio_venta_factura
                  FROM 
                    ventas v
                  JOIN 
                    detalle_venta dv ON v.id = dv.venta_id
                  JOIN
                    productos p ON dv.producto_id = p.id
                  WHERE 
                    v.estado = 'activa' 
                    AND v.fecha_venta >= :fecha_inicio 
                    AND v.fecha_venta <= :fecha_fin";
        
        // Agregar filtro por usuario si está definido
        if (!empty($this->usuario_id)) {
            $query .= " AND v.usuario_id = :usuario_id";
        }
        
        $stmt = $this->conn->prepare($query);

        // Sanear y vincular fechas
        $this->fecha_inicio = htmlspecialchars(strip_tags($this->fecha_inicio)) . " 00:00:00";
        $this->fecha_fin = htmlspecialchars(strip_tags($this->fecha_fin)) . " 23:59:59";

        $stmt->bindParam(':fecha_inicio', $this->fecha_inicio);
        $stmt->bindParam(':fecha_fin', $this->fecha_fin);

        // Vincular usuario si aplica
        if (!empty($this->usuario_id)) {
            $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));
            $stmt->bindParam(':usuario_id', $this->usuario_id);
        }

        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Reporte de Top Productos Vendidos (Cantidad)
    public function getTopProductosVendidos() {
        $query = "SELECT 
                    p.nombre, 
                    SUM(dv.cantidad) AS cantidad_vendida,
                    SUM(dv.cantidad * dv.precio_unitario) AS ingresos_producto
                  FROM 
                    detalle_venta dv
                  JOIN 
                    productos p ON dv.producto_id = p.id
                  JOIN 
                    ventas v ON dv.venta_id = v.id
                  WHERE 
                    v.estado = 'activa' 
                    AND v.fecha_venta >= :fecha_inicio 
                    AND v.fecha_venta <= :fecha_fin";

        // Agregar filtro por usuario si está definido
        if (!empty($this->usuario_id)) {
            $query .= " AND v.usuario_id = :usuario_id";
        }

        $query .= " GROUP BY p.nombre
                    ORDER BY cantidad_vendida DESC
                    LIMIT 10"; // Top 10

        $stmt = $this->conn->prepare($query);

        // Sanear y vincular fechas y usuario
        $this->fecha_inicio = htmlspecialchars(strip_tags($this->fecha_inicio)) . " 00:00:00";
        $this->fecha_fin = htmlspecialchars(strip_tags($this->fecha_fin)) . " 23:59:59";
        
        $stmt->bindParam(':fecha_inicio', $this->fecha_inicio);
        $stmt->bindParam(':fecha_fin', $this->fecha_fin);
        
        if (!empty($this->usuario_id)) {
            $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));
            $stmt->bindParam(':usuario_id', $this->usuario_id);
        }

        $stmt->execute();
        
        return $stmt;
    }

    // Reporte de Productos con Bajo Stock (Inventario)
    public function getBajoStock($limite = 5) {
        $query = "SELECT 
                    p.id, 
                    p.nombre, 
                    p.stock, 
                    p.unidad_medida, 
                    c.nombre AS categoria
                  FROM 
                    productos p
                  JOIN 
                    categorias c ON p.categoria_id = c.id
                  WHERE 
                    p.estado = 'activo' 
                    AND p.stock <= :limite
                  ORDER BY 
                    p.stock ASC";

        $stmt = $this->conn->prepare($query);
        
        // Vincular límite de stock
        $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
        
        $stmt->execute();
        
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        return $stmt;
    }
}
?>