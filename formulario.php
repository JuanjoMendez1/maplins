<?php

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$mensaje = $_POST['mensaje']; 

    $email_to = "juanjomendez029@gmail.com";
    // $email_to_maplins ="ventas@maplins.com.mx";
    $asunto = "Solicitud de Informacion Maplins";
    $asunto_cliente="Gracias por enviar su informacion" ;

    $comentario = "
        Nombre del cliente: $_POST[nombre]
        Email del cliente: $_POST[email]
        Telefono del cliente: $_POST[telefono]
        Mensaje o comentario: $_POST[mensaje]
    ";
    
    $comentario_cliente = "
        Su nombre es: $_POST[nombre]
        Su Email es: $_POST[email]
        Su Telefono es: $_POST[telefono]
        Su mensaje o comentario que nos dejo es: $_POST[mensaje]
        
        Si hay algun error en sus datos, favor de comunicarse con nosotros
    ";

    $headers = 'From: '.$email."\r\n". 
'Reply-To: '.$email_to."\r\n" .
'X-Mailer: PHP/' . phpversion();

if (mail($email_to, $asunto, $comentario, $headers)) {
    echo "Correo enviado";
} else {
    echo "Error";
}
        // mail($email_to,$asunto,$comentario,$headers);
        // mail($email_to_maplins,$asunto,$comentario,$headers);
        // include 'index.html';
?>