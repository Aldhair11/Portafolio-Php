<?php
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $names = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $phone = htmlspecialchars($_POST["phone"]);
    $message = htmlspecialchars($_POST["message"]);
    $messageStatus = "El mensaje se envió correctamente.";

    if (!empty($names) && !empty($email) && !empty($phone) && !empty($message)) {
        // Validación del correo electrónico
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Correo electrónico inválido.'); window.history.back();</script>";
            exit;
        }

        try {
            ([
                "name" => $names,
                "email" => $email,
                "phone" => $phone,
                "message" => $message,
                
            ]);

            if ($names && $email && $phone && $message) {
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = getenv('EMAIL_HOST') ?: $_ENV['EMAIL_HOST'] ?? null;
                    $mail->SMTPAuth = true;
                    $mail->Username = getenv('EMAIL_USER') ?: $_ENV['EMAIL_USER'] ?? null;
                    $mail->Password = getenv('EMAIL_PASS') ?: $_ENV['EMAIL_PASS'] ?? null;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('aldhajo.1101@gmail.com', 'Nombre Remitente'); 
                    $mail->addAddress('aldha.110102@gmail.com', 'Destinatario'); 
                    $mail->addReplyTo($email, $names);

                    $mail->isHTML(true);
                    $mail->Subject = 'Nuevo mensaje de contacto de Portafolio Web';
                    $mail->Body = "<h2>Nuevo mensaje de contacto web</h2>"
                                  ."<p><strong>Nombre:</strong> $name</p>"
                                  ."<p><strong>Correo:</strong> $email</p>"
                                  ."<p><strong>Teléfono:</strong> $phone</p>"
                                  ."<p><strong>Mensaje:</strong> $message</p>";

                    $mail->send();
                    echo "<script>alert('El mensaje se envió correctamente.'); window.location.href = '../index.html';</script>";
                } catch (Exception $e) {
                    echo "<script>alert('Error al enviar el correo: {$mail->ErrorInfo}'); window.history.back();</script>";
                }
            } else {
                echo "<script>alert('Error al guardar en la base de datos.'); window.history.back();</script>";
            }
        } catch (Exception $e) {
            echo "<script>alert('Error al guardar en la base de datos: {$e->getMessage()}'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Por favor, completa todos los campos.'); window.history.back();</script>";
    }
} else {
    echo "Acceso no permitido.";
}   

?>