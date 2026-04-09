<?php

session_start();

require_once '/var/www/config/config.php';
require_once '/var/www/config/database.php';
require_once APP_PATH . '/models/Usuario.php';

$page = $_GET['page'] ?? 'home';
$mensaje = '';

if ($page === 'logout') {
    session_destroy();
    header('Location: ' . BASE_URL);
    exit;
}

if ($page === 'register') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');

        if ($nombre === '' || $email === '' || $password === '') {
            $mensaje = 'Nombre, email y contraseña son obligatorios';
        } else {
            $registroCorrecto = Usuario::registrar($pdo, $nombre, $email, $password, $telefono);

            if ($registroCorrecto) {
                $mensaje = 'Usuario registrado correctamente';
            } else {
                $mensaje = 'Error al registrar el usuario';
            }
        }
    }

    $view = APP_PATH . '/views/auth/register.php';

} elseif ($page === 'login') {

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $usuario = Usuario::buscarPorEmail($pdo, $email);

        if ($usuario && password_verify($password, $usuario['password'])) {
            session_regenerate_id(true);
            $_SESSION['usuario'] = $usuario;
            header('Location: ' . BASE_URL);
            exit;
        } else {
            $mensaje = 'Email o contraseña incorrectos';
        }
    }

    $view = APP_PATH . '/views/auth/login.php';

} elseif ($page === 'profile') {

    if (empty($_SESSION['usuario'])) {
        header('Location: ' . BASE_URL . '/?page=login');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $passwordActual = trim($_POST['password_actual'] ?? '');
        $passwordNueva = trim($_POST['password_nueva'] ?? '');

        if ($nombre === '' || $email === '') {
            $mensaje = 'Nombre y email son obligatorios';
        } else {
            $actualizacionCorrecta = Usuario::actualizarPerfil(
                $pdo,
                $_SESSION['usuario']['id'],
                $nombre,
                $email,
                $telefono
            );

            if ($actualizacionCorrecta) {
                $_SESSION['usuario']['nombre'] = $nombre;
                $_SESSION['usuario']['email'] = $email;
                $_SESSION['usuario']['telefono'] = $telefono;

                if ($passwordActual !== '' || $passwordNueva !== '') {
                    if ($passwordActual === '' || $passwordNueva === '') {
                        $mensaje = 'Para cambiar la contraseña debes rellenar ambos campos';
                    } else {
                        $usuarioActual = Usuario::buscarPorId($pdo, $_SESSION['usuario']['id']);

                        if ($usuarioActual && password_verify($passwordActual, $usuarioActual['password'])) {
                            $passwordActualizada = Usuario::actualizarPassword(
                                $pdo,
                                $_SESSION['usuario']['id'],
                                $passwordNueva
                            );

                            if ($passwordActualizada) {
                                $mensaje = 'Perfil y contraseña actualizados correctamente';
                            } else {
                                $mensaje = 'Perfil actualizado, pero hubo un error al cambiar la contraseña';
                            }
                        } else {
                            $mensaje = 'Perfil actualizado, pero la contraseña actual no es correcta';
                        }
                    }
                } else {
                    $mensaje = 'Perfil actualizado correctamente';
                }
            } else {
                $mensaje = 'Error al actualizar el perfil';
            }
        }
    }

    $view = APP_PATH . '/views/auth/profile.php';

} elseif ($page === 'tecnicos') {
    require_once APP_PATH . '/controllers/MaestrosController.php';
    (new MaestrosController($pdo))->tecnicos();
    exit;

} elseif ($page === 'tecnico_create') {
    require_once APP_PATH . '/controllers/MaestrosController.php';
    (new MaestrosController($pdo))->tecnicoCreate();
    exit;

} elseif ($page === 'tecnico_store') {
    require_once APP_PATH . '/controllers/MaestrosController.php';
    (new MaestrosController($pdo))->tecnicoStore();
    exit;

} elseif ($page === 'tecnico_edit') {
    require_once APP_PATH . '/controllers/MaestrosController.php';
    (new MaestrosController($pdo))->tecnicoEdit();
    exit;

} elseif ($page === 'tecnico_update') {
    require_once APP_PATH . '/controllers/MaestrosController.php';
    (new MaestrosController($pdo))->tecnicoUpdate();
    exit;

} elseif ($page === 'tecnico_toggle') {
    require_once APP_PATH . '/controllers/MaestrosController.php';
    (new MaestrosController($pdo))->tecnicoToggle();
    exit;

} elseif ($page === 'especialidades') {
    require_once APP_PATH . '/controllers/MaestrosController.php';
    (new MaestrosController($pdo))->especialidades();
    exit;

} elseif ($page === 'especialidad_create') {
    require_once APP_PATH . '/controllers/MaestrosController.php';
    (new MaestrosController($pdo))->especialidadCreate();
    exit;

} elseif ($page === 'especialidad_store') {
    require_once APP_PATH . '/controllers/MaestrosController.php';
    (new MaestrosController($pdo))->especialidadStore();
    exit;

} elseif ($page === 'especialidad_edit') {
    require_once APP_PATH . '/controllers/MaestrosController.php';
    (new MaestrosController($pdo))->especialidadEdit();
    exit;

} elseif ($page === 'especialidad_update') {
    require_once APP_PATH . '/controllers/MaestrosController.php';
    (new MaestrosController($pdo))->especialidadUpdate();
    exit;

} elseif ($page === 'especialidad_delete') {
    require_once APP_PATH . '/controllers/MaestrosController.php';
    (new MaestrosController($pdo))->especialidadDelete();
    exit;

} elseif ($page === 'mis_avisos') {
    require_once APP_PATH . '/controllers/IncidenciasController.php';
    (new IncidenciasController($pdo))->misAvisos();
    exit;

} elseif ($page === 'incidencia_create') {
    require_once APP_PATH . '/controllers/IncidenciasController.php';
    (new IncidenciasController($pdo))->create();
    exit;

} elseif ($page === 'incidencia_store') {
    require_once APP_PATH . '/controllers/IncidenciasController.php';
    (new IncidenciasController($pdo))->store();
    exit;

} elseif ($page === 'incidencia_cancelar') {
    require_once APP_PATH . '/controllers/IncidenciasController.php';
    (new IncidenciasController($pdo))->cancelar();
    exit;

} elseif ($page === 'admin_dashboard') {
    require_once APP_PATH . '/controllers/AdminController.php';
    (new AdminController($pdo))->dashboard();
    exit;

} elseif ($page === 'admin_incidencias') {
    require_once APP_PATH . '/controllers/AdminController.php';
    (new AdminController($pdo))->incidencias();
    exit;

} elseif ($page === 'admin_incidencia_create') {
    require_once APP_PATH . '/controllers/AdminController.php';
    (new AdminController($pdo))->create();
    exit;

} elseif ($page === 'admin_incidencia_store') {
    require_once APP_PATH . '/controllers/AdminController.php';
    (new AdminController($pdo))->store();
    exit;

} elseif ($page === 'admin_incidencia_edit') {
    require_once APP_PATH . '/controllers/AdminController.php';
    (new AdminController($pdo))->edit();
    exit;

} elseif ($page === 'admin_incidencia_update') {
    require_once APP_PATH . '/controllers/AdminController.php';
    (new AdminController($pdo))->update();
    exit;

} elseif ($page === 'admin_incidencia_cancel') {
    require_once APP_PATH . '/controllers/AdminController.php';
    (new AdminController($pdo))->cancel();
    exit;

} elseif ($page === 'admin_incidencia_asignar') {
    require_once APP_PATH . '/controllers/AdminController.php';
    (new AdminController($pdo))->assign();
    exit;

} elseif ($page === 'admin_incidencia_guardar_asignacion') {
    require_once APP_PATH . '/controllers/AdminController.php';
    (new AdminController($pdo))->saveAssign();
    exit;

} elseif ($page === 'admin_calendar') {
    require_once APP_PATH . '/controllers/AdminController.php';
    (new AdminController($pdo))->calendar();
    exit;

} else {
    $view = APP_PATH . '/views/home/index.php';
}

require_once APP_PATH . '/views/layouts/main.php';