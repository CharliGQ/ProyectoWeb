<?php

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtener el controlador y la acción de la URL
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Construir el nombre del controlador
$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = "app/controllers/{$controllerName}.php";

// Verificar si existe el controlador
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerName();
    
    // Verificar si existe la acción
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        // Acción no encontrada
        header("HTTP/1.0 404 Not Found");
        echo "Acción no encontrada";
    }
} else {
    // Controlador no encontrado
    header("HTTP/1.0 404 Not Found");
    echo "Controlador no encontrado";
}