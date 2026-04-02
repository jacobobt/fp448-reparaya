<?php

session_start();

require_once '/var/www/config/config.php';
require_once '/var/www/config/database.php';
require_once APP_PATH . '/models/Usuario.php';

$page = $_GET['page'] ?? 'home';
$mensaje = '';

if ($page === 'register') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $telefono = trim($_POST['telefono']);

        $registroCorrecto = Usuario::registrar($pdo, $nombre, $email, $password, $telefono);

        if ($registroCorrecto) {
            $mensaje = 'Usuario registrado correctamente';
        } else {
            $mensaje = 'Error al registrar el usuario';
        }
    }

    $view = APP_PATH . '/views/auth/register.php';

} elseif ($page === 'login') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $usuario = Usuario::buscarPorEmail($pdo, $email);

        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['usuario'] = $usuario;
            header('Location: ' . BASE_URL);
            exit;
        } else {
            $mensaje = 'Email o contraseña incorrectos';
        }
    }

    $view = APP_PATH . '/views/auth/login.php';

} else {
    $view = APP_PATH . '/views/home/index.php';
}

require_once APP_PATH . '/views/layouts/main.php';