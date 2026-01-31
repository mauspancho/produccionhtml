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
    $mail->Body = '
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nuevo contacto</title>
</head>
<body style="
  margin:0;
  padding:0;
  background-color:#f2f6fa;
  font-family: Arial, Helvetica, sans-serif;
">

  <table width="100%" cellpadding="0" cellspacing="0" style="padding:30px 0;">
    <tr>
      <td align="center">

        <!-- CONTENEDOR -->
        <table width="600" cellpadding="0" cellspacing="0" style="
          background:#ffffff;
          border-radius:12px;
          overflow:hidden;
          box-shadow:0 10px 30px rgba(0,0,0,0.08);
        ">

          <!-- HEADER -->
          <tr>
            <td style="
              background:#003865;
              padding:24px;
              text-align:center;
            ">
              <img
                src="https://dental1.mauricioromero.com.mx/assets/logos/logo.png"
                alt="Dentalrus"
                style="height:60px; display:block; margin:0 auto;"
              />
            </td>
          </tr>

          <!-- BODY -->
          <tr>
            <td style="padding:32px; color:#1f2937;">

              <h2 style="
                margin:0 0 24px 0;
                color:#003865;
                font-size:22px;
              ">
                Nuevo contacto recibido
              </h2>

              <table width="100%" cellpadding="6" cellspacing="0" style="font-size:15px;">
                <tr>
                  <td><strong>Nombre:</strong></td>
                  <td>'.$nombre.' '.$apellido.'</td>
                </tr>
                <tr>
                  <td><strong>Email:</strong></td>
                  <td>'.$email.'</td>
                </tr>
                <tr>
                  <td><strong>Teléfono:</strong></td>
                  <td>'.$telefono.'</td>
                </tr>
                <tr>
                  <td><strong>Posición:</strong></td>
                  <td>'.$posicion.'</td>
                </tr>
                <tr>
                  <td><strong>Tiempo:</strong></td>
                  <td>'.$tiempo.'</td>
                </tr>
              </table>

              <div style="
                margin-top:24px;
                padding:16px;
                background:#f2f6fa;
                border-left:4px solid #003865;
                border-radius:6px;
              ">
                <strong>Mensaje:</strong>
                <p style="margin:8px 0 0 0; line-height:1.6;">
                  '.nl2br(htmlspecialchars($mensaje)).'
                </p>
              </div>

            </td>
          </tr>

          <!-- FOOTER -->
          <tr>
            <td style="
              background:#f9fafb;
              padding:20px;
              text-align:center;
              font-size:12px;
              color:#6b7280;
            ">
              Este mensaje fue enviado desde el formulario de contacto de
              <strong>Dentalrus</strong>.<br>
              © '.date('Y').' Dentalrus. Todos los derechos reservados.
            </td>
          </tr>

        </table>

      </td>
    </tr>
  </table>

</body>
</html>
';

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
