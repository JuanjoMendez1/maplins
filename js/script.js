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
            alert("Por favor, completa todos los campos del formulario para que podamos ponernos en contacto contigo posteriormente. Tu información es crucial para brindarte el mejor servicio posible.");
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
            alert("¡Gracias por elegirnos para tu proyecto! Hemos recibido tu formulario y nos pondremos en contacto contigo pronto para explorar juntos las emocionantes posibilidades que tu idea presenta. ¡Estamos ansiosos por comenzar esta colaboración!")
            window.location.reload();
        }
    } catch (error) {
        console.log(error);
    }
}

btn_enviar.addEventListener("click", enviar_email);