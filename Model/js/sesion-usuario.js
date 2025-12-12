// Model/js/sesion-usuario.js

document.addEventListener("DOMContentLoaded", () => {
    const cont = document.getElementById("header-usuario");
    if (!cont) return;

    // ¿Estoy en /View/ o en la raíz (index)?
    const enView = window.location.pathname.includes("/View/");
    const loginHref   = enView ? "login.html"   : "View/login.html";
    const carritoHref = enView ? "carrito.html" : "View/carrito.html";

    let usuario = null;
    try {
        usuario = JSON.parse(localStorage.getItem("usuarioMega") || "null");
    } catch (e) {
        usuario = null;
    }

    // Si NO hay sesión → mostrar Acceder / Registrarse + Carrito
    if (!usuario) {
        cont.innerHTML = `
            <a href="${loginHref}" class="link-header">Acceder / Registrarse</a>
            <a href="${carritoHref}" class="link-header">🛒 Carrito</a>
        `;
        return;
    }

    // Si hay usuario → mostrar saludo + carrito + cerrar sesión
    const email = usuario.email || "Usuario";

    cont.innerHTML = `
        <span class="link-header user-welcome">Hola, ${email}</span>
        <a href="${carritoHref}" class="link-header">🛒 Carrito</a>
        <button type="button" id="btnLogout" class="link-header btn-link">
            Cerrar sesión
        </button>
    `;

    const btnLogout = document.getElementById("btnLogout");
    if (btnLogout) {
        btnLogout.addEventListener("click", () => {
            localStorage.removeItem("usuarioMega");
            // opcional limpiar también el carrito si quieres
            // localStorage.removeItem("carritoMega");
            window.location.reload();
        });
    }
});
