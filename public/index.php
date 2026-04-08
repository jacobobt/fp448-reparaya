<?php
session_start();

require_once '/var/www/config/config.php';
require_once '/var/www/config/database.php';
require_once APP_PATH . '/models/Usuario.php';

$page    = $_GET['page'] ?? 'home';
$mensaje = '';

// ── LOGOUT ────────────────────────────────────────────────────────
if ($page === 'logout') {
    session_destroy();
    header('Location: ' . BASE_URL);
    exit;
}

// ── REGISTER ──────────────────────────────────────────────────────
if ($page === 'register') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre   = trim($_POST['nombre']);
        $email    = trim($_POST['email']);
        $password = trim($_POST['password']);
        $telefono = trim($_POST['telefono']);

        if (Usuario::registrar($pdo, $nombre, $email, $password, $telefono)) {
            $mensaje = 'Usuario registrado correctamente. Ya puedes iniciar sesión.';
        } else {
            $mensaje = 'Error al registrar. El email puede estar ya en uso.';
        }
    }
    $view = APP_PATH . '/views/auth/register.php';

// ── LOGIN ─────────────────────────────────────────────────────────
} elseif ($page === 'login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email    = trim($_POST['email']);
        $password = trim($_POST['password']);
        $usuario  = Usuario::buscarPorEmail($pdo, $email);

        if ($usuario && password_verify($password, $usuario['password'])) {
            $_SESSION['usuario'] = $usuario;
            header('Location: ' . BASE_URL);
            exit;
        } else {
            $mensaje = 'Email o contraseña incorrectos.';
        }
    }
    $view = APP_PATH . '/views/auth/login.php';

// ── PERFIL ────────────────────────────────────────────────────────
} elseif ($page === 'profile') {
    $view = APP_PATH . '/views/auth/profile.php';

// ── ADMIN: DASHBOARD ──────────────────────────────────────────────
} elseif ($page === 'admin_dashboard') {
    require_once APP_PATH . '/controllers/Admin_Controller.php';
    (new AdminController($pdo))->index();
    exit;

// ── ADMIN: CALENDARIO ─────────────────────────────────────────────
} elseif ($page === 'admin_calendario') {
    require_once APP_PATH . '/controllers/Admin_Controller.php';
    (new AdminController($pdo))->calendario();
    exit;

// ── ADMIN: NUEVO AVISO ────────────────────────────────────────────
} elseif ($page === 'nuevo_aviso') {
    require_once APP_PATH . '/controllers/Admin_Controller.php';
    (new AdminController($pdo))->nuevoAviso();
    exit;

// ── ADMIN: EDITAR AVISO ───────────────────────────────────────────
} elseif ($page === 'editar_aviso') {
    require_once APP_PATH . '/controllers/Admin_Controller.php';
    (new AdminController($pdo))->editarAviso();
    exit;

// ── ADMIN: CANCELAR AVISO ─────────────────────────────────────────
} elseif ($page === 'cancelar_aviso') {
    require_once APP_PATH . '/controllers/Admin_Controller.php';
    (new AdminController($pdo))->cancelarAviso();
    exit;

// ── ADMIN: ASIGNAR TÉCNICO ────────────────────────────────────────
} elseif ($page === 'asignar_tecnico') {
    require_once APP_PATH . '/controllers/Admin_Controller.php';
    (new AdminController($pdo))->asignar();
    exit;

// ── HOME ──────────────────────────────────────────────────────────
} else {
    $view = APP_PATH . '/views/home/index.php';
}

require_once APP_PATH . '/views/layouts/main.php';
