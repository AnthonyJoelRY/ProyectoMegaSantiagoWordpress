<?php
require_once __DIR__ . "/../../Model/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$accion = $_POST["accion"] ?? "";

$pdo = obtenerConexion();

switch ($accion) {

    case "crear":
        /* ======================
   LEER DATOS
====================== */
        $nombre            = trim($_POST["nombre"]);
        $idCategoria       = $_POST["id_categoria"];
        $descripcionCorta  = trim($_POST["descripcion_corta"]);
        $descripcionLarga  = trim($_POST["descripcion_larga"]);
        $precio            = str_replace(',', '.', $_POST["precio"]);
        $precioOferta      = str_replace(',', '.', $_POST["precio_oferta"] ?? null);
        $sku               = trim($_POST["sku"]);
        $aplicaIva         = isset($_POST["aplica_iva"]) ? 1 : 0;
        $imagen = trim($_POST["imagen"] ?? "");

        /* ======================
   VALIDAR
====================== */
        if (
            $nombre === "" ||
            $idCategoria === "" ||
            $descripcionCorta === "" ||
            !is_numeric($precio) ||
            $precio <= 0 ||
            $sku === "" ||
            $imagen === ""
        ) {
            die("❌ Datos inválidos");
        }

        /* ======================
   TRANSACTION
====================== */
        $pdo->beginTransaction();

        try {

            /* 1️⃣ PRODUCTO */
            $stmt = $pdo->prepare("
        INSERT INTO productos
        (id_categoria, nombre, descripcion_corta, descripcion_larga,
         precio, precio_oferta, sku, aplica_iva, activo)
        VALUES
        (:id_categoria, :nombre, :descripcion_corta, :descripcion_larga,
         :precio, :precio_oferta, :sku, :aplica_iva, 1)
    ");

            $stmt->execute([
                ":id_categoria"       => $idCategoria,
                ":nombre"             => $nombre,
                ":descripcion_corta"  => $descripcionCorta,
                ":descripcion_larga"  => $descripcionLarga,
                ":precio"             => $precio,
                ":precio_oferta"      => $precioOferta ?: null,
                ":sku"                => $sku,
                ":aplica_iva"         => $aplicaIva
            ]);

            $idProducto = $pdo->lastInsertId();



            $pdo->prepare("
    INSERT INTO producto_imagenes (id_producto, url_imagen, es_principal)
    VALUES (?, ?, 1)
")->execute([
                $idProducto,
                $imagen
            ]);



            /* 3️⃣ INVENTARIO */
            $pdo->prepare("
    INSERT INTO inventario
    (id_producto, stock_actual, ubicacion_almacen, ultima_actualizacion)
    VALUES (?, 0, 'Bodega principal', NOW())
")->execute([$idProducto]);


            $pdo->commit();

            header("Location: index.php?ok=1");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            die("❌ Error al guardar producto: " . $e->getMessage());
        }
        break;

    case "editar":

        /* ======================
       LEER DATOS
    ====================== */
        $idProducto        = (int)$_POST["id_producto"];
        $nombre            = trim($_POST["nombre"]);
        $idCategoria       = $_POST["id_categoria"];
        $descripcionCorta  = trim($_POST["descripcion_corta"]);
        $descripcionLarga  = trim($_POST["descripcion_larga"]);
        $precio            = str_replace(',', '.', $_POST["precio"]);
        $precioOferta      = str_replace(',', '.', $_POST["precio_oferta"] ?? null);
        $sku               = trim($_POST["sku"]);
        $aplicaIva         = isset($_POST["aplica_iva"]) ? 1 : 0;

        /* ======================
       VALIDAR
    ====================== */
        if (
            $idProducto <= 0 ||
            $nombre === "" ||
            !is_numeric($precio) ||
            $precio <= 0
        ) {
            die("❌ Datos inválidos para editar");
        }

        /* ======================
       UPDATE PRODUCTO
    ====================== */
        $stmt = $pdo->prepare("
    INSERT INTO productos (
        id_categoria,
        nombre,
        descripcion_corta,
        descripcion_larga,
        precio,
        precio_oferta,
        aplica_iva,
        sku,
        stock_minimo,
        activo,
        fecha_creacion,
        fecha_actualizacion
    ) VALUES (
        :id_categoria,
        :nombre,
        :descripcion_corta,
        :descripcion_larga,
        :precio,
        :precio_oferta,
        :aplica_iva,
        :sku,
        0,
        1,
        NOW(),
        NOW()
    )
");


        $stmt->execute([
            ":id_categoria"      => $idCategoria,
            ":nombre"            => $nombre,
            ":descripcion_corta" => $descripcionCorta,
            ":descripcion_larga" => $descripcionLarga,
            ":precio"            => $precio,
            ":precio_oferta"     => $precioOferta ?: null,
            ":sku"               => $sku,
            ":aplica_iva"        => $aplicaIva,
            ":id_producto"       => $idProducto
        ]);

        header("Location: index.php?edit=1");
        exit;

        break;

    case "desactivar":

        $idProducto = (int)$_POST["id_producto"];

        if ($idProducto <= 0) {
            header("Location: index.php");
            exit;
        }

        $stmt = $pdo->prepare("
        UPDATE productos
        SET activo = 0
        WHERE id_producto = ?
    ");
        $stmt->execute([$idProducto]);

        header("Location: index.php?estado=off");
        exit;


    case "activar":

        $idProducto = (int)$_POST["id_producto"];

        if ($idProducto <= 0) {
            header("Location: index.php");
            exit;
        }

        $stmt = $pdo->prepare("
        UPDATE productos
        SET activo = 1
        WHERE id_producto = ?
    ");
        $stmt->execute([$idProducto]);

        header("Location: index.php?estado=on");
        exit;

    default:
        header("Location: index.php");
        exit;
}
