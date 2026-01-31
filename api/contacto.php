<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/mailer/Exception.php';
require __DIR__ . '/mailer/PHPMailer.php';
require __DIR__ . '/mailer/SMTP.php';

// Solo aceptar POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit;
}

// Asignar datos enviados
$nombre   = trim($_POST["nombre"] ?? '');
$apellido = trim($_POST["apellido"] ?? '');
$email    = trim($_POST["email"] ?? '');
$telefono = trim($_POST["telefono"] ?? '');
$posicion = trim($_POST["posicion"] ?? '');
$mensaje  = trim($_POST["mensaje"] ?? '');
$tiempo   = $_POST["tiempo"] ?? '';

// Validar datos mínimos
if (!$nombre || !$email || !$telefono) {
    http_response_code(400);
    echo json_encode(["status" => "error", "msg" => "Datos incompletos"]);
    exit;
}

try {
    $mail = new PHPMailer(true);

    // Configuración SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'mautheisle@gmail.com';      // Tu correo
    $mail->Password   = 'yiiw ohjb pxkf jare';            // Contraseña de aplicación
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('mautheisle@gmail.com', 'Dentalrus Web');
    $mail->addAddress('contacto@mauricioromero.com.mx');     // Correo destino

    $mail->isHTML(true);
    $mail->Subject = 'Nuevo contacto desde Dentalrus';
    $mail->Body    = "
        <h3>Nuevo contacto</h3>
        <p><b>Nombre:</b> $nombre $apellido</p>
        <p><b>Email:</b> $email</p>
        <p><b>Teléfono:</b> $telefono</p>
        ,<p><b>Posición:</b> $posicion</p>
        <p><b>Tiempo:</b> $tiempo</p>
        <p><b>Mensaje:</b><br>$mensaje</p>
    ";

    // Enviar
    $mail->send();

    header("Content-Type: application/json");
    echo json_encode(["status" => "ok"]);

} catch (Exception $e) {

    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg"    => $mail->ErrorInfo
    ]);
}
