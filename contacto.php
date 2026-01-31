<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  http_response_code(405);
  exit;
}

$nombre   = trim($_POST["nombre"] ?? '');
$apellido = trim($_POST["apellido"] ?? '');
$email    = trim($_POST["email"] ?? '');
$telefono = trim($_POST["telefono"] ?? '');
$mensaje  = trim($_POST["mensaje"] ?? '');
$tiempo   = $_POST["tiempo"] ?? '';

if (!$nombre || !$email || !$telefono) {
  http_response_code(400);
  echo "Datos incompletos";
  exit;
}

$contenido = "
Nombre: $nombre $apellido
Correo: $email
Teléfono: $telefono
Tiempo: $tiempo

Mensaje:
$mensaje
";

mail(
  "contacto@mauricioromero.com.mx",
  "Nuevo contacto Dentalrus",
  $contenido,
  "From: contacto@mauricioromero.com.mx"
);

echo "ok";
