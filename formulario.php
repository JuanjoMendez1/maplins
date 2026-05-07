<?php
require_once 'config.php';

// Validar que sea una solicitud POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo MSG_ERROR;
    exit;
}

// Sanitizar y validar datos
$nombre = isset($_POST['nombre']) ? htmlspecialchars(trim($_POST['nombre']), ENT_QUOTES, 'UTF-8') : '';
$email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8') : '';
$telefono = isset($_POST['telefono']) ? htmlspecialchars(trim($_POST['telefono']), ENT_QUOTES, 'UTF-8') : '';
$mensaje = isset($_POST['mensaje']) ? htmlspecialchars(trim($_POST['mensaje']), ENT_QUOTES, 'UTF-8') : '';

// Validar campos requeridos
if (empty($nombre) || empty($email) || empty($telefono) || empty($mensaje)) {
    http_response_code(400);
    echo MSG_ERROR;
    exit;
}

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo MSG_ERROR;
    exit;
}

// Validar teléfono (solo números y caracteres permitidos)
if (!preg_match('/^[0-9\+\(\)\s\-]{7,}$/', $telefono)) {
    http_response_code(400);
    echo MSG_ERROR;
    exit;
}

// Preparar mensaje para administrador
$comentario = "
    Nombre del cliente: " . $nombre . "
    Email del cliente: " . $email . "
    Telefono del cliente: " . $telefono . "
    Mensaje o comentario: " . $mensaje . "
";

// Preparar mensaje para cliente
$comentario_cliente = "
    Su nombre es: " . $nombre . "
    Su Email es: " . $email . "
    Su Telefono es: " . $telefono . "
    Su mensaje o comentario que nos dejo es: " . $mensaje . "
    
    Si hay algun error en sus datos, favor de comunicarse con nosotros
";

// Headers de correo
$headers = 'From: ' . $email . "\r\n" .
    'Reply-To: ' . EMAIL_ADMIN . "\r\n" .
    'Content-Type: text/plain; charset=UTF-8' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

// Enviar correos
$mail_admin = mail(EMAIL_ADMIN, SUBJECT_ADMIN, $comentario, $headers);
$mail_business = mail(EMAIL_BUSINESS, SUBJECT_ADMIN, $comentario, $headers);

if ($mail_admin && $mail_business) {
    // Opcional: enviar correo al cliente
    mail($email, SUBJECT_CLIENT, $comentario_cliente, $headers);
    echo MSG_SUCCESS;
} else {
    http_response_code(500);
    echo MSG_ERROR;
}
?>
