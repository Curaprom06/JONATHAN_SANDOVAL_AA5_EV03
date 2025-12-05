<?php
// Configuración de cabeceras para API REST
header("Access-Control-Allow-Origin: *"); // Permite acceso desde cualquier origen (ajustar en producción)
header("Content-Type: application/json; charset=UTF-8"); // La respuesta será JSON
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); // Headers permitidos

// Si la petición es OPTIONS, respondemos con 200 OK inmediatamente (para pre-vuelo CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Incluye el Autoloader (Asumo que existe)
require_once "../app/Config/Autoload.php";
// Incluye la clase de Conexión a la Base de Datos
require_once "../app/Config/Database.php";

// -----------------------------------------------------
// 1. OBTENER INFORMACIÓN DE LA PETICIÓN
// -----------------------------------------------------

// Obtiene el método HTTP (GET, POST, PUT, DELETE)
$method = $_SERVER['REQUEST_METHOD']; 

// Obtiene la URI y la divide en partes
// Ejemplo: /productos/123 -> ['','productos', '123']
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

<<<<<<< HEAD
// El recurso principal (ej: 'productos') se intenta obtener de la URI.
// La lógica aquí es sensible a la estructura de carpetas en XAMPP,
// por eso es mejor usar un Query Parameter o ajustar el índice.
// En este caso, buscaremos el recurso usando el Query Parameter 'resource'
// Si no está, intentamos inferirlo de la URI.
=======
// El recurso principal (ej: 'productos') es la segunda parte después de la ruta base
// Toca ajustar el índice dependiendo de la ruta base real en el servidor.
// Usaremos un enfoque simple para que funcione en la mayoría de entornos:
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
$resource = $_GET['resource'] ?? $uri[count($uri) - 2];
$id = $_GET['id'] ?? $uri[count($uri) - 1]; // El ID puede ser el último segmento

// Limpiamos el ID si no es numérico o si es el nombre del script (ej: index.php)
if (!is_numeric($id)) {
    $id = null;
}

// -----------------------------------------------------
// 2. ENRUTAMIENTO (Routing) BÁSICO
// -----------------------------------------------------

// El router central dirige la solicitud al controlador adecuado
switch ($resource) {
    case 'productos':
        require_once "../app/Controllers/ProductoController.php";
        $controller = new ProductoController();
        $controller->handleRequest($method, $id);
        break;
        
    case 'clientes':
        require_once "../app/Controllers/ClienteController.php";
        $controller = new ClienteController();
        $controller->handleRequest($method, $id);
        break;
        
    case 'proveedores':
<<<<<<< HEAD
=======
        // Lógica del controlador de Proveedores
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        require_once "../app/Controllers/ProveedorController.php";
        $controller = new ProveedorController();
        $controller->handleRequest($method, $id);
        break;
        
    case 'ventas':
<<<<<<< HEAD
=======
        // Lógica del controlador de Ventas (Transaccional)
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344
        require_once "../app/Controllers/VentaController.php";
        $controller = new VentaController();
        $controller->handleRequest($method, $id);
        break;

<<<<<<< HEAD
    case 'categorias':
        require_once "../app/Controllers/CategoriaController.php";
        $controller = new CategoriaController();
        $controller->handleRequest($method, $id);
        break;
        
    case 'reportes':
        require_once "../app/Controllers/ReporteController.php";
        $controller = new ReporteController();
        // Los reportes usan la acción (ej: resumen_ventas) como $id en este caso
        $controller->handleRequest($method, $id); 
        break;
=======
    // ... otros recursos (compras, usuarios, reportes)
>>>>>>> e5be0db1d6d9bdc0b841b1870048fdfc688b7344

    // --- ENRUTAMIENTO DE AUTENTICACIÓN Y USUARIOS ---
    case 'register':
    case 'login':
        // LLAMADA CORREGIDA: Usa UsuarioController.php y UsuarioController
        require_once "../app/Controllers/UsuarioController.php";
        $controller = new UsuarioController();
        // Usamos el recurso ('register' o 'login') como acción en handleAuthRequest
        $controller->handleAuthRequest($method, $resource); 
        break;
    
    case 'usuarios':
        // LLAMADA CORREGIDA: Usa UsuarioController.php y UsuarioController
        require_once "../app/Controllers/UsuarioController.php";
        $controller = new UsuarioController();
        // handleRequest maneja el CRUD (GET, PUT, DELETE)
        $controller->handleRequest($method, $id);
        break;
    // --- FIN ENRUTAMIENTO DE AUTENTICACIÓN Y USUARIOS ---
        
    default:
        // Recurso no encontrado (ej: /api/inexistente)
        http_response_code(404);
        echo json_encode(["message" => "Recurso no encontrado. La URI solicitada '{$resource}' no existe."]);
        break;
}

?>