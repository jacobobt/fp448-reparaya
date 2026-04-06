<?php
require_once APP_PATH . '/models/Usuario.php';

class AuthController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function register()
    {
        $mensaje = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nombre = trim ($_POST['nombre'] ?? '');
            $email = trim ($_POST['email'] ?? '');
            $password = trim ($_POST['password'] ?? '');
            $telefono = trim ($_POST['telefono'] ?? '');

            $registerCorrecto = Usuario::registrar($this->pdo, $nombre, $email, $password, $telefono);

            if ($registroCorrecto) {
                $mensaje = 'Usuario registrado correctamente';
            } else {
                $mensaje = 'Error Al registrar el usuario';
            }
        }

        $view = APP_PATH . '/views/auth/register.php';
        require APP_PATH . '/views/layouts/main.php';
    }

    public function login()
    {
        $mensaje = '';

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim ($_POST['email'] ?? '');
            $password = trim ($_POST['password'] ?? '');

            $usuario = Usuario::buscarPorEmail($this->pdo, $email);

            if ($usuario && password_verify($password, $usuario['password'])) {
                $_SESSION['usuario'] = $usuario;
                header('Location: ' . BASE_URL);
                exit;
            } else {
                $mensaje = 'Email o contraseña incorrectos';
            }
        }

        $view = APP_PATH . '/views/auth/login.php';
        require APP_PATH . '/views/layouts/main.php';
    }

    public function profile()
    {
        if (empty($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }

        $mensaje = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $nombre = trim ($_POST['nombre'] ?? '');
            $email = trim ($_POST['email'] ?? '');
            $telefono = trim ($_POST['telefono'] ?? '');

            $actualizacionCorrecta = Usuario::actualizarPerfil(
                $this->pdo, 
                $_SESSION['usuario']['id'], 
                $nombre, 
                $email, 
                $telefono
            );

            if ($actualizacionCorrecta) {
                $_SESSION['usuario']['nombre'] = $nombre;
                $_SESSION['usuario']['email'] = $email;
                $_SESSION['usuario']['telefono'] = $telefono;
                $mensaje = 'Perfil actualizado correctamente';
            } else {
                $mensaje = 'Error al actualizar el perfil';
            }
        }
        $view = APP_PATH . '/views/auth/profile.php';
        require APP_PATH . '/views/layouts/main.php';
    }
    public function logout()
    {
        session_destroy();
        header('Location: ' . BASE_URL);
        exit;
    }

}
