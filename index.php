<?php
// Configuración de CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Si la solicitud es OPTIONS (preflight), respondemos con los encabezados CORS y un código 200 OK.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Respuesta a la solicitud OPTIONS (preflight)
    http_response_code(200); // Asegura que se devuelva 200 OK
    exit; // Salimos aquí, no procesamos más
}

// Incluir el archivo para manejar el envío de datos (enviar_datos.php)
require './php/enviar_datos.php';

// Para otros métodos como POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decodificar los datos del formulario enviados en formato JSON
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        // Si no hay datos o están mal formateados, se devuelve un error
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }

    // Instanciamos la clase para guardar los datos y enviarlos por correo
    $enviar_datos = new enviar_datos();
    $response = $enviar_datos->saveForms($data);

    // Devolver la respuesta al frontend
    echo json_encode($response);
}
?>