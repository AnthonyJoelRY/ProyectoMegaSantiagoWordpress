<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | MegaSantiago</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos personalizados muy sencillos para la barra lateral y asegurar que el contenido se vea */
        .sidebar {
            /* Asegura que el color de fondo de la barra lateral sea consistente */
            background-color: #212529 !important; /* Usando bg-dark */
        }
        .nav-link.active {
            /* Estilo para el enlace activo */
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 4px solid var(--bs-info); /* Línea de color para indicar activo */
        }
        .nav-link {
             padding-left: 1.5rem; /* Pequeño ajuste para el padding de los enlaces */
        }
        .card-body h2 {
            font-size: 2.5rem; /* Ajuste para las métricas clave */
        }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">

        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse min-vh-100 p-0 shadow-lg">
            <div class="position-sticky pt-4">
                <div class="d-flex align-items-center justify-content-center mb-4 pb-2 border-bottom border-light opacity-50 mx-3">
                     <h4 class="text-white fw-bolder my-0">
                         MegaSantiago
                     </h4>
                 </div>
                <ul class="nav flex-column px-2">
    <li class="nav-item">
        <a class="nav-link text-white rounded-2"
           href="/MegaSantiagoFront/admin/dashboard.php">
           🏠 Dashboard
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link text-white rounded-2"
           href="/MegaSantiagoFront/admin/productos/">
           📦 Productos
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link text-white rounded-2"
           href="/MegaSantiagoFront/admin/usuarios/">
           👥 Usuarios
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link text-white rounded-2"
           href="/MegaSantiagoFront/admin/pedidos/">
           🛒 Pedidos
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link text-white active rounded-2"
           href="/MegaSantiagoFront/admin/reportes/">
           📈 Reportes
        </a>
    </li>
</ul>
                
                <div class="px-3 mt-5">
                    <a class="nav-link text-white bg-danger bg-opacity-75 hover-bg-danger rounded-3 p-2 text-center fw-semibold" href="/MegaSantiagoFront/index.html">
                        <span class="me-2">↩️</span> Volver al sitio
                    </a>
                </div>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-5 py-4">

        




        

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>