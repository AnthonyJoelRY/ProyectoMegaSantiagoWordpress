<?php
// Model/Service/AuthService.php

require_once __DIR__ . "/../DAO/UsuarioDAO.php";
require_once __DIR__ . "/../DAO/RolDAO.php";

class AuthService {

    private UsuarioDAO $usuarioDAO;
    private RolDAO $rolDAO;

    public function __construct(private PDO $pdo) {
        $this->usuarioDAO = new UsuarioDAO($pdo);
        $this->rolDAO     = new RolDAO($pdo);
    }

    // ============================
    // REGISTRO CLIENTE
    // ============================
    public function registrarCliente(string $email, string $clave): array {

        if ($email === "" || $clave === "") {
            return ["error" => "Faltan datos de registro."];
        }

        if ($this->usuarioDAO->existePorEmail($email)) {
            return ["error" => "Este correo ya está registrado."];
        }

        $idRol = $this->rolDAO->obtenerIdCliente();
        $hash  = password_hash($clave, PASSWORD_BCRYPT);

        $idUsuario = $this->usuarioDAO->crear($idRol, $email, $hash);

        return [
            "exito" => true,
            "usuario" => [
                "id" => $idUsuario,
                "email" => $email,
                "rol" => $idRol
            ]
        ];
    }

    // ============================
    // LOGIN
    // ============================
    public function login(string $email, string $clave): array {

        if ($email === "" || $clave === "") {
            return ["error" => "Faltan datos de acceso."];
        }

        $usuario = $this->usuarioDAO->obtenerPorEmail($email);

        if (!$usuario || !password_verify($clave, $usuario->clave_hash)) {
            return ["error" => "Credenciales incorrectas"];
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION["id"]  = $usuario->id_usuario;
        $_SESSION["rol"] = $usuario->id_rol;
        $_SESSION["email"] = $usuario->email;

        return [
            "exito" => true,
            "usuario" => [
                "id" => $usuario->id_usuario,
                "email" => $usuario->email,
                "rol" => $usuario->id_rol
            ]
        ];
    }
}
