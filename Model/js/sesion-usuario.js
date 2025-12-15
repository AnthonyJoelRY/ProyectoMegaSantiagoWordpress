// Model/js/sesion-usuario.js
// Este script puede ser insertado dinámicamente (por fetch/layout-loader),
// así que NO debemos depender únicamente de DOMContentLoaded.

function initSesionUsuario() {
  const contenedor = document.getElementById("header-usuario");
  if (!contenedor) return;

  const usuario = JSON.parse(localStorage.getItem("usuarioMega"));

  // ❌ NO hay sesión
  if (!usuario) {
    contenedor.innerHTML = `
      <a href="/MegaSantiagoFront/View/pages/login.html" class="link-header">
        Acceder / Registrarse
      </a>
      <a href="/MegaSantiagoFront/View/pages/carrito.html" class="link-header">
        🛒 Carrito
      </a>
    `;
    return;
  }

// ✅ HAY sesión
let htmlSesion = `
  <span class="user-name">Hola, ${usuario.email}</span>
`;

// 👉 SOLO ADMINISTRADOR ve Dashboard
if (usuario.rol === 1) {
  htmlSesion += `
    <a href="/MegaSantiagoFront/admin/dashboard.php" class="link-header">
      📊 Dashboard
    </a>
  `;
}

// 👉 Todos los usuarios logueados ven carrito (icono + texto)
htmlSesion += `
  <a href="/MegaSantiagoFront/View/pages/carrito.html" class="link-header">
    🛒 Carrito
  </a>
  <a href="#" id="logout" class="link-header">
    Salir
  </a>
`;

contenedor.innerHTML = htmlSesion;


  document.getElementById("logout").addEventListener("click", () => {
    localStorage.removeItem("usuarioMega");
    window.location.href = "/MegaSantiagoFront/index.html";
  });
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initSesionUsuario);
} else {
  initSesionUsuario();
}
