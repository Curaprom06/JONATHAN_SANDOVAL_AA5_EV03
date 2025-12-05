<?php
// Función para autocargar clases (Models, Controllers)
spl_autoload_register(function ($class_name) {
    // Definimos las carpetas donde buscar clases
    $folders = ['Models', 'Controllers', 'Config'];

    foreach ($folders as $folder) {
        $file = __DIR__ . "/../{$folder}/{$class_name}.php";
        
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
?>