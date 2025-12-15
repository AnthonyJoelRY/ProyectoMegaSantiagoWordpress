<?php
header("Content-Type: application/json");
require_once "../Model/db.php";

$pdo    = obtenerConexion();
$accion = $_GET["accion"] ?? "";

/**
 * Obtiene el id_rol del rol "Cliente".
 * Si no existe, por defecto 3.
 */
function obtenerIdRolCliente(PDO $pdo): int {
    $sql = "SELECT id_rol FROM roles WHERE LOWER(nombre) = 'cliente' LIMIT 1";
    $stmt = $pdo->query($sql);
    $fila = $stmt->fetch();
    if ($fila && isset($fila["id_rol"])) {
        return (int)$fila["id_rol"];
    }
    return 3; // valor de respaldo
}

switch ($accion) {

    /* ==========================================================
       REGISTRO DE USUARIO
    ========================================================== */
    case "registrar":
        $data = json_decode(file_get_contents("php://input"), true);

        $email = trim($data["email"] ?? "");
        $clave = trim($data["clave"] ?? "");

        if ($email === "" || $clave === "") {
            echo json_encode(["error" => "Faltan datos de registro."]);
            exit;
        }

        // ¿Ya existe ese correo?
        $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            echo json_encode(["error" => "Este correo ya está registrado."]);
            exit;
        }

        // Rol cliente
        $idRol = obtenerIdRolCliente($pdo);

        // Crear usuario
        $claveHash = password_hash($clave, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("
            INSERT INTO usuarios (id_rol, email, clave_hash, activo)
            VALUES (:id_rol, :email, :clave_hash, 1)
        ");
        $stmt->execute([
            ":id_rol"      => $idRol,
            ":email"      => $email,
            ":clave_hash" => $claveHash
        ]);

        $idNuevo = (int)$pdo->lastInsertId();

        echo json_encode([
            "exito"   => true,
            "usuario" => [
                "id"    => $idNuevo,
                "email" => $email,
                "rol"   => $idRol
            ]
        ]);
        break;

    /* ==========================================================
       LOGIN DE USUARIO
    ========================================================== */
    case "login":
    $data = json_decode(file_get_contents("php://input"), true);

    $email = trim($data["email"] ?? "");
    $clave = trim($data["clave"] ?? "");

    if ($email === "" || $clave === "") {
        echo json_encode(["error" => "Faltan datos de acceso."]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if (!$usuario || !password_verify($clave, $usuario["clave_hash"])) {
        echo json_encode(["error" => "Credenciales incorrectas"]);
        exit;
    }

    // 🔐 CREAR SESIÓN PHP
    session_start();
    $_SESSION["usuario"] = $usuario["email"];
    $_SESSION["rol"]     = (int)$usuario["id_rol"];
    $_SESSION["id"]      = (int)$usuario["id_usuario"];

    echo json_encode([
        "exito"   => true,
        "usuario" => [
            "id"    => (int)$usuario["id_usuario"],
            "email" => $usuario["email"],
            "rol"   => (int)$usuario["id_rol"]
        ]
    ]);
    break;

    default:
        echo json_encode(["error" => "Acción no válida"]);
}
