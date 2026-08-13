const formulario = document.querySelector("#formulario-contacto");
const botonEnviar = document.querySelector("#btn_enviar");
const estadoFormulario = document.querySelector("#estado-formulario");
const anioActual = document.querySelector("#anio-actual");

if (anioActual) {
    anioActual.textContent = new Date().getFullYear();
}

const mostrarEstado = (mensaje, esError = false) => {
    estadoFormulario.textContent = mensaje;
    estadoFormulario.classList.toggle("error", esError);
};

const enviarFormulario = async (evento) => {
    evento.preventDefault();

    if (!formulario.reportValidity()) {
        return;
    }

    if (window.location.protocol === "file:") {
        mostrarEstado("El formulario solo puede enviarse desde el sitio publicado o desde un servidor local con PHP.", true);
        return;
    }

    botonEnviar.disabled = true;
    botonEnviar.value = "Enviando...";
    mostrarEstado("Enviando tu solicitud...");

    try {
        const response = await fetch(formulario.action, {
            method: 'POST',
            body: new FormData(formulario),
            headers: { "Accept": "application/json" }
        });

        const resultado = await response.json();
        if (!response.ok || !resultado.ok) {
            throw new Error(resultado.mensaje || "No pudimos enviar tu solicitud.");
        }

        mostrarEstado(resultado.mensaje);
        formulario.reset();

        if (typeof window.gtag === "function") {
            window.gtag('event', 'conversion', {
                'send_to': 'AW-461391455/MbRpCOvSh-0BEN-MgdwB'
            });
        }
    } catch (error) {
        console.error("No fue posible enviar el formulario:", error);
        mostrarEstado("No pudimos enviar tu solicitud. Inténtalo nuevamente o llámanos al (55) 1677 2700.", true);
    } finally {
        botonEnviar.disabled = false;
        botonEnviar.value = "Enviar";
    }
};

formulario.addEventListener("submit", enviarFormulario);
