<?php
session_start();

if (!isset($_SESSION["rol"]) || $_SESSION["rol"] !== 1) {
    header("Location: /MegaSantiagoFront/index.html");
    exit;
}

require_once __DIR__ . "/../../Model/db.php";
$pdo = obtenerConexion();

/* =========================
   VALIDAR ID
========================= */
$id = $_GET["id"] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: index.php");
    exit;
}

/* =========================
   OBTENER PRODUCTO
========================= */
$stmt = $pdo->prepare("
SELECT 
    id_producto,
    id_categoria,
    nombre,
    descripcion_corta,
    descripcion_larga,
    precio,
    precio_oferta,
    sku,
    aplica_iva,
    activo
FROM productos
WHERE id_producto = ?

");

$stmt->execute([$id]);
$producto = $stmt->fetch();

if (!$producto) {
    header("Location: index.php");
    exit;
}

$categorias = $pdo->query("
    SELECT id_categoria, nombre
    FROM categorias
    WHERE activo = 1
    ORDER BY nombre
")->fetchAll();

?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Producto | MegaSantiago</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR -->
            <?php include __DIR__ . "/../PLANTILLAS/sidebar.php"; ?>

            <!-- CONTENIDO -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-5 py-4">

                <!-- CABECERA -->
                <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-4 shadow-sm">
                    <h2 class="fw-bold text-primary mb-0">✏️ Editar Producto</h2>
                    <a href="index.php" class="btn btn-outline-secondary">
                        Volver
                    </a>
                </div>

                <!-- FORMULARIO -->
                <div class="card shadow-sm rounded-4 border-0 bg-white p-4">

                    <form action="acciones.php" method="POST">
                        <input type="hidden" name="accion" value="editar">
                        <input type="hidden" name="id_producto" value="<?= $producto["id_producto"] ?>">

                        <div class="row">

                            <!-- NOMBRE -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nombre</label>
                                <input type="text"
                                    name="nombre"
                                    class="form-control"
                                    value="<?= htmlspecialchars($producto["nombre"]) ?>"
                                    required>
                            </div>

                            <!-- SKU -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">SKU</label>
                                <input type="text"
                                    name="sku"
                                    class="form-control"
                                    value="<?= htmlspecialchars($producto["sku"]) ?>"
                                    required>
                            </div>

                            <!-- CATEGORÍA -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Categoría</label>
                                <select name="id_categoria" class="form-select" required>
                                    <option value="">Seleccione una categoría</option>

                                    <?php foreach ($categorias as $c): ?>
                                        <option value="<?= $c["id_categoria"] ?>"
                                            <?= $c["id_categoria"] == $producto["id_categoria"] ? "selected" : "" ?>>
                                            <?= htmlspecialchars($c["nombre"]) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>


                            <!-- PRECIO -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Precio</label>
                                <input type="number"
                                    step="0.01"
                                    name="precio"
                                    class="form-control"
                                    value="<?= $producto["precio"] ?>"
                                    required>
                            </div>

                            <!-- PRECIO OFERTA -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Precio Oferta</label>
                                <input type="number"
                                    step="0.01"
                                    name="precio_oferta"
                                    class="form-control"
                                    value="<?= $producto["precio_oferta"] ?>">
                            </div>

                            <!-- IVA -->
                            <div class="col-md-4 mb-3 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input"
                                        type="checkbox"
                                        name="aplica_iva"
                                        id="aplicaIva"
                                        <?= $producto["aplica_iva"] ? "checked" : "" ?>>
                                    <label class="form-check-label fw-semibold" for="aplicaIva">
                                        Aplica IVA
                                    </label>
                                </div>
                            </div>

                            <!-- DESCRIPCIÓN CORTA -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Descripción corta</label>
                                <input type="text"
                                    name="descripcion_corta"
                                    class="form-control"
                                    value="<?= htmlspecialchars($producto["descripcion_corta"]) ?>"
                                    required>
                            </div>

                            <!-- DESCRIPCIÓN LARGA -->
                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-semibold">Descripción larga</label>
                                <textarea name="descripcion_larga"
                                    class="form-control"
                                    rows="4"><?= htmlspecialchars($producto["descripcion_larga"]) ?></textarea>
                            </div>

                        </div>

                        <!-- BOTÓN -->
                        <button class="btn btn-primary fw-semibold">
                            Guardar cambios
                        </button>

                    </form>

                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>