<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require './php/enviar_datos.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
        exit;
    }

    $enviar_datos = new enviar_datos();
    $response = $enviar_datos->saveForms($data);
    echo json_encode($response);
}
?>