<?php
// Controlador para manejar todas las peticiones del recurso /reportes
<<<<<<< HEAD
// Maneja peticiones de tipo:
// - GET /reportes/resumen_ventas?fecha_inicio=...&fecha_fin=...
// - GET /reportes/top_productos?fecha_inicio=...&fecha_fin=...
// - GET /reportes/bajo_stock?limite=...

require_once '../app/Models/Reporte.php'; 
require_once '../app/Config/Database.php'; // Asegúrate de incluir la clase Database
=======
require_once '../app/Models/Reporte.php'; 
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

class ReporteController {
    private $db;
    private $reporte;

    public function __construct() {
        // Inicializa la conexión y el modelo
        $database = new Database();
        $this->db = $database->getConnection();
        $this->reporte = new Reporte($this->db);
    }

<<<<<<< HEAD
    /**
     * Función principal para manejar las peticiones GET a /reportes/{tipo_reporte}
     * El parámetro $action es el tipo de reporte (ej: resumen_ventas, top_productos, bajo_stock).
     */
    public function handleRequest($method, $action) {
        if ($method !== 'GET') {
            http_response_code(405); 
            echo json_encode(["message" => "Método {$method} no permitido para el recurso Reportes. Solo se permite GET."]);
            return;
        }

        // 1. Obtener filtros de la URL (Query Parameters)
        // Se establecen fechas por defecto para un rango razonable
=======
    // Función principal para manejar las peticiones GET a /reportes/{tipo_reporte}
    public function handleRequest($method, $action) {
        if ($method !== 'GET') {
            http_response_code(405); 
            echo json_encode(["message" => "Método {$method} no permitido para el recurso Reportes."]);
            return;
        }

        // Obtener filtros de la URL (Query Parameters)
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        $this->reporte->fecha_inicio = $_GET['fecha_inicio'] ?? date('Y-m-01'); // Inicio del mes actual por defecto
        $this->reporte->fecha_fin = $_GET['fecha_fin'] ?? date('Y-m-d');     // Hoy por defecto
        $this->reporte->usuario_id = $_GET['usuario_id'] ?? null;             // Filtro de vendedor opcional
        
<<<<<<< HEAD
        // 2. Validar fechas (mínimo)
        if (empty($this->reporte->fecha_inicio) || empty($this->reporte->fecha_fin)) {
            http_response_code(400); 
            echo json_encode(["message" => "Error. Las fechas de inicio y fin son obligatorias para reportes basados en tiempo."]);
            return;
        }

        // 3. Ejecutar la acción del reporte
=======
        // Validar fechas (mínimo)
        if (empty($this->reporte->fecha_inicio) || empty($this->reporte->fecha_fin)) {
            http_response_code(400); 
            echo json_encode(["message" => "Error. Se requieren fechas de inicio y fin."]);
            return;
        }

>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        switch ($action) {
            case 'resumen_ventas':
                $this->getResumenVentas();
                break;
            case 'top_productos':
                $this->getTopProductos();
                break;
            case 'bajo_stock':
<<<<<<< HEAD
                // Nota: bajo_stock NO usa las fechas, pero sí se maneja aquí.
=======
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
                $this->getBajoStock();
                break;
            default:
                http_response_code(404);
<<<<<<< HEAD
                echo json_encode(["message" => "Reporte '{$action}' no encontrado."]);
=======
                echo json_encode(["message" => "Reporte '{$action}' no válido."]);
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
                break;
        }
    }

<<<<<<< HEAD
    // [GET] /reportes/resumen_ventas?fecha_inicio=...&fecha_fin=...
    private function getResumenVentas() {
        $data = $this->reporte->getResumenVentas();
        
        // El método getResumenVentas devuelve un array con un único elemento (los totales) o null
        if ($data) {
            // Convertir a JSON
            http_response_code(200);
            echo json_encode($data);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "No se encontraron datos de ventas para el periodo seleccionado."]);
        }
    }

    // [GET] /reportes/top_productos?fecha_inicio=...&fecha_fin=...
    private function getTopProductos() {
        // Se puede pasar un límite de resultados, por defecto 10
        $limite = $_GET['limite'] ?? 10; 

        $stmt = $this->reporte->getTopProductos((int)$limite);
=======
    // [GET] /reportes/resumen_ventas
    private function getResumenVentas() {
        $result = $this->reporte->getKpiResumenVentas();
        
        if ($result && !empty($result['ingresos_totales'])) {
            // Formatear la respuesta (garantizando tipos numéricos para cálculos)
            $response = [
                "periodo_analizado" => "Desde " . $this->reporte->fecha_inicio . " hasta " . $this->reporte->fecha_fin,
                "ingresos_totales" => (float)($result['ingresos_totales'] ?? 0),
                "costo_total" => (float)($result['costo_total'] ?? 0),
                "ganancia_bruta" => (float)($result['ganancia_bruta'] ?? 0),
                "total_ventas_unidades" => (int)($result['total_ventas_unidades'] ?? 0),
                "promedio_venta_factura" => (float)($result['promedio_venta_factura'] ?? 0),
            ];
            
            http_response_code(200);
            echo json_encode($response);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "No se encontraron ventas para el periodo y filtros seleccionados."]);
        }
    }

    // [GET] /reportes/top_productos
    private function getTopProductos() {
        $stmt = $this->reporte->getTopProductosVendidos();
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        $num = $stmt->rowCount();

        if ($num > 0) {
            $productos_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $item = array(
<<<<<<< HEAD
                    // Se convierten los tipos de datos a int/float para consistencia con JSON
                    "id" => (int)$row['producto_id'],
                    "nombre_producto" => $row['nombre_producto'],
                    "cantidad_vendida" => (int)$row['cantidad_vendida'],
                    "total_ingresos" => (float)$row['total_ingresos']
=======
                    "nombre_producto" => $row['nombre'],
                    "cantidad_vendida" => (int)$row['cantidad_vendida'],
                    "ingresos_generados" => (float)$row['ingresos_producto']
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
                );
                array_push($productos_arr, $item);
            }

            http_response_code(200);
            echo json_encode($productos_arr);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "No se encontraron productos vendidos en el periodo seleccionado."]);
        }
    }

<<<<<<< HEAD
    // [GET] /reportes/bajo_stock?limite=...
=======
    // [GET] /reportes/bajo_stock
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
    private function getBajoStock() {
        // El límite de stock se puede pasar como parámetro, por defecto 5 unidades
        $limite = $_GET['limite'] ?? 5; 

        $stmt = $this->reporte->getBajoStock((int)$limite);
        $num = $stmt->rowCount();

        if ($num > 0) {
            $inventario_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $item = array(
<<<<<<< HEAD
                    // Se convierten los tipos de datos a int para consistencia con JSON
=======
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
                    "id" => (int)$row['id'],
                    "nombre" => $row['nombre'],
                    "stock_actual" => (int)$row['stock'],
                    "unidad_medida" => $row['unidad_medida'],
                    "categoria" => $row['categoria']
                );
                array_push($inventario_arr, $item);
            }

            http_response_code(200);
<<<<<<< HEAD
            echo json_encode([
                "limite_stock_analizado" => (int)$limite, 
                "productos_bajo_stock" => $inventario_arr
            ]);
        } else {
            http_response_code(200);
            echo json_encode(["message" => "Inventario óptimo. No hay productos con stock igual o inferior a {$limite} unidades."]);
=======
            echo json_encode(["limite_stock_analizado" => (int)$limite, "productos" => $inventario_arr]);
        } else {
            http_response_code(200);
            echo json_encode(["message" => "Excelente! No hay productos por debajo del límite de stock ({$limite})."]);
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        }
    }
}
?>