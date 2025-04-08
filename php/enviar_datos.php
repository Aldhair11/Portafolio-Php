<?php
// Habilitar CORS por si el frontend está en otro origen (Astro u otro)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Cargar PHPMailer
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Solo permitir método POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener los datos JSON de la solicitud
    $data = json_decode(file_get_contents("php://input"), true);

    // Validar que los datos hayan llegado correctamente
    if (!$data || !isset($data["names"]) || !isset($data["email"]) || !isset($data["phone"]) || !isset($data["message"])) {
        http_response_code(400);
        echo json_encode(["error" => "Todos los campos son obligatorios."]);
        exit;
    }
    if (!is_numeric($phone)) {
        http_response_code(400);
        echo json_encode(["error" => "El teléfono debe ser numérico."]);
        exit;
    }

    // Sanitizar y asignar variables
    $names = htmlspecialchars($data["names"]);
    $email = htmlspecialchars($data["email"]);
    $phone = htmlspecialchars($data["phone"]);
    $message = htmlspecialchars($data["message"]);

    // Validación de email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(["error" => "Correo electrónico inválido."]);
        exit;
    }

    try {
        $mail = new PHPMailer(true);

        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host = getenv('EMAIL_HOST') ?: $_ENV['EMAIL_HOST'] ?? null; 
        $mail->SMTPAuth = true;
        $mail->Username = getenv('EMAIL_USER') ?: $_ENV['EMAIL_USER'] ?? null; 
        $mail->Password = getenv('EMAIL_PASS') ?: $_ENV['EMAIL_PASS'] ?? null;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom('aldhajo.1101@gmail.com', 'Formulario Web'); 
        $mail->addAddress('aldha.110102@gmail.com', 'Destinatario');
        $mail->addReplyTo($email, $names);

        $mail->isHTML(true);
        $mail->Subject = 'Nuevo mensaje de contacto de Portafolio Web';
        $mail->Body = "<h2>Nuevo mensaje recibido</h2>
                       <p><strong>Nombre:</strong> $names</p>
                       <p><strong>Correo:</strong> $email</p>
                       <p><strong>Teléfono:</strong> $phone</p>
                       <p><strong>Mensaje:</strong> $message</p>";

        $mail->send();
        echo json_encode(["success" => "Mensaje enviado correctamente."]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Error al enviar correo: {$mail->ErrorInfo}"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido."]);
}
?>
