<?php

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$telefono = $_POST['tel'];
$mensaje = $_POST['mensaje']; 

    $email_to = "sabino_andaluz@hotmail.com";
    $email_to_maplins ="ventas@maplins.com.mx";
    $asunto = "Solicitud de Información Maplins";
    $asunto_cliente="Gracias por enviar su información" ;

    $comentario = "
        Nombre del cliente: $_POST[nombre]
        Email del cliente: $_POST[email]
        Telefono del cliente: $_POST[tel]
        Mensaje o comentario: $_POST[mensaje]
    ";
    
    $comentario_cliente = "
        Su nombre es: $_POST[nombre]
        Su Email es: $_POST[email]
        Su Telefono es: $_POST[tel]
        Su mensaje o comentario que nos dejo es: $_POST[mensaje]
        
        Si hay algun error en sus datos, favor de comunicarse con nosotros
    ";

    $headers = 'From: '.$email."\r\n". 
'Reply-To: '.$email_to."\r\n" .
'X-Mailer: PHP/' . phpversion();

        mail($email_to,$asunto,$comentario,$headers);
        mail($email_to_maplins,$asunto,$comentario,$headers);
        //mail($email, $asunto_cliente, $comentario_cliente, $headers
        //    );

        include 'index.html';
?>