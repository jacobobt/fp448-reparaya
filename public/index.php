<?php

session_start();

require_once '/var/www/config/config.php';
require_once '/var/www/config/database.php';


$page = $_GET['page'] ?? 'home';
switch ($page) {
    case 'login':
        require_once APP_PATH . '/controllers/AuthController.php';
        (new AuthController($pdo))->login();
        break;
    case 'register':
        require_once APP_PATH . '/controllers/AuthController.php';
        (new AuthController($pdo))->register();
        break;
    case 'profile':
        require_once APP_PATH . '/controllers/AuthController.php';
        (new ProfileController($pdo))->show();
        break;
    case 'logout':
        require_once APP_PATH . '/controllers/AuthController.php';
        (new AuthController($pdo))->logout();
        break;
    default:
        require_once APP_PATH . '/controllers/HomeController.php';
        (new HomeController($pdo))->index();
        break;
}