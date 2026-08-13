<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');
header('X-Content-Type-Options: nosniff');

function responder(int $codigo, bool $ok, string $mensaje): void
{
    http_response_code($codigo);
    echo json_encode(
        ['ok' => $ok, 'mensaje' => $mensaje],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    responder(405, false, 'Método no permitido.');
}

// Campo invisible: los robots suelen llenarlo, las personas no.
if (trim((string) ($_POST['sitio_web'] ?? '')) !== '') {
    responder(200, true, 'Gracias. Recibimos tu solicitud.');
}

// Máximo de cinco intentos por dirección IP cada diez minutos.
$ip = (string) ($_SERVER['REMOTE_ADDR'] ?? 'desconocida');
$archivoLimite = sys_get_temp_dir() . '/maplins_' . hash('sha256', $ip) . '.json';
$ahora = time();
$intentos = [];
$manejador = @fopen($archivoLimite, 'c+');

if ($manejador !== false && flock($manejador, LOCK_EX)) {
    $contenido = stream_get_contents($manejador);
    $guardados = json_decode($contenido ?: '[]', true);
    if (is_array($guardados)) {
        $intentos = array_values(array_filter(
            $guardados,
            static function ($marca) use ($ahora): bool {
                return is_int($marca) && $marca > $ahora - 600;
            }
        ));
    }

    if (count($intentos) >= 5) {
        flock($manejador, LOCK_UN);
        fclose($manejador);
        responder(429, false, 'Recibimos varios intentos. Espera unos minutos antes de volver a enviar.');
    }

    $intentos[] = $ahora;
    rewind($manejador);
    ftruncate($manejador, 0);
    fwrite($manejador, (string) json_encode($intentos));
    fflush($manejador);
    flock($manejador, LOCK_UN);
    fclose($manejador);
}

$nombre = trim((string) ($_POST['nombre'] ?? ''));
$apellido = trim((string) ($_POST['apellido'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$telefono = trim((string) ($_POST['telefono'] ?? ''));
$mensaje = trim((string) ($_POST['mensaje'] ?? ''));

if ($nombre === '' || $apellido === '' || $email === '' || $telefono === '' || $mensaje === '') {
    responder(422, false, 'Completa todos los campos del formulario.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    responder(422, false, 'Escribe un correo electrónico válido.');
}

if (preg_match('/[\r\n]/', $email) || preg_match('/[^0-9+() .-]/', $telefono)) {
    responder(422, false, 'Revisa el correo electrónico y el teléfono.');
}

if (strlen($nombre) > 80 || strlen($apellido) > 100 || strlen($email) > 254 || strlen($telefono) > 30 || strlen($mensaje) > 2000) {
    responder(422, false, 'Uno de los campos excede la longitud permitida.');
}

$nombreCompleto = $nombre . ' ' . $apellido;
$destinatarios = 'sabino_andaluz@hotmail.com, ventas@maplins.com.mx';
// $destinatarios = 'juanjomendez029@gmail.com';
$asunto = 'Solicitud de información Maplins';
$comentario = implode("\n", [
    'Nombre del cliente: ' . $nombreCompleto,
    'Email del cliente: ' . $email,
    'Teléfono del cliente: ' . $telefono,
    '',
    'Mensaje o comentario:',
    $mensaje,
]);

// El remitente usa el propio dominio; el correo del cliente se conserva en Reply-To.
$headers = implode("\r\n", [
    'From: Sitio Maplins <no-reply@maplins.com.mx>',
    'Reply-To: ' . $email,
    'Content-Type: text/plain; charset=UTF-8',
    'X-Mailer: PHP/' . phpversion(),
]);

if (!mail($destinatarios, $asunto, $comentario, $headers)) {
    responder(500, false, 'No pudimos enviar tu solicitud. Inténtalo nuevamente o llámanos al (55) 1677 2700.');
}

responder(200, true, '¡Gracias! Recibimos tu solicitud y nos pondremos en contacto contigo pronto.');
