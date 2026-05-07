const nombre = document.querySelector("#nombre");
const apellido = document.querySelector("#apellido");
const email = document.querySelector("#email");
const telefono = document.querySelector("#telefono");
const mensaje = document.querySelector("#mensaje");
const btn_enviar = document.querySelector("#btn_enviar");

const enviar_email = async (e) => {
    try {
        e.preventDefault();
        if(nombre.value == "" || email.value == "" || telefono.value == "" || mensaje.value == "") {
            alert("Por favor, completa todos los campos del formulario. Tu información es importante para nosotros.");
            return;
        }
        const data = new FormData();
        data.append("nombre", apellido.value == "" ? nombre.value : `${nombre.value} ${apellido.value}`);
        data.append("telefono", telefono.value);
        data.append("email", email.value);
        data.append("mensaje", mensaje.value);
        const response = await fetch(`/formulario.php`, {
            method: 'POST',
            body: data
        });
        const resultado = await response.text();
        console.log(resultado);
        if (resultado == "Correo enviado") {
            alert("¡Gracias por elegirnos! Hemos recibido tu solicitud y nos pondremos en contacto pronto para explorar tu proyecto.");
            document.querySelector("form").reset();
        }
    } catch (error) {
        console.log(error);
        alert("Hubo un error al enviar el formulario. Por favor intenta de nuevo.");
    }
}

if (btn_enviar) {
    btn_enviar.addEventListener("click", enviar_email);
}

// Smooth scroll mejorado
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && document.querySelector(href)) {
            e.preventDefault();
            const target = document.querySelector(href);
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
