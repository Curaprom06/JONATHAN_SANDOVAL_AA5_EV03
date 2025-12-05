<?php
// Clase para manejar la conexión segura a la base de datos usando PDO

class Database {
    // --- Configuración de la Base de Datos (Asegúrate que coincida con tu MySQL/MariaDB) ---
    private $host = "localhost"; 
    private $db_name = "panaderia_celeste_api"; // <<<< VERIFICAR ESTO
    private $username = "root";     // <<<< VERIFICAR ESTO
    private $password = ""; // <<<< VERIFICAR ESTO (En XAMPP suele ser "" o "root")
    private $conn;

    /**
     * Obtiene la conexión a la base de datos usando PDO.
     * @return PDO|null La conexión PDO o null si falla.
     */
    public function getConnection() {
        $this->conn = null;
        
        try {
            // **IMPORTANTE: USAMOS EL DRIVER 'mysql' para XAMPP/MariaDB**
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8";
            
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Opcional: Deshabilitar emulación de consultas preparadas para mayor seguridad
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
            
        } catch(PDOException $exception) {
            // En caso de error de conexión, se detiene la ejecución y se devuelve un JSON de error.
            
            // Puedes usar una variable global para entornos de producción
            $is_development = true; 

            if ($is_development) {
                // Muestra un mensaje detallado en desarrollo (incluye el error de la DB)
                $error_message = "Error de conexión a la base de datos: " . $exception->getMessage();
            } else {
                 // Muestra un mensaje genérico en producción
                $error_message = "Error interno del servidor. Intente más tarde.";
            }
            
            http_response_code(500); // Internal Server Error
            echo json_encode(["message" => $error_message]);
            
            // Terminar la ejecución
            exit(); 
        }

        return $this->conn;
    }
}
?>