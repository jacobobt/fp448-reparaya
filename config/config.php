<?php

define('APP_NAME', 'ReparaYa');

// Conexión según el entorno
if ($_SERVER['SERVER_NAME'] === 'localhost') {

    // Local
    define('BASE_URL', 'http://localhost:8080');

    define('DB_HOST', 'db');
    define('DB_NAME', 'reparaya');
    define('DB_USER', 'reparaya_user');
    define('DB_PASS', 'reparaya_pass');

    define('APP_PATH', '/var/www/app');
    define('CONFIG_PATH', '/var/www/config');

} else {

    // Servidor
    define('BASE_URL', 'https://fp064.techlab.uoc.edu/~uocx5');

    define('DB_HOST', 'localhost');
    define('DB_NAME', 'wordpress5');
    define('DB_USER', 'wordpress5');
    define('DB_PASS', '2ZNG53TdCaOoLpvp');

    define('APP_PATH', __DIR__ . '/../app');
    define('CONFIG_PATH', __DIR__);
}


/*
<?php

define('APP_NAME', 'ReparaYa');
define('BASE_URL', 'http://localhost:8080');
define('APP_PATH', '/var/www/app');
define('CONFIG_PATH', '/var/www/config');

define('DB_HOST', 'db');
define('DB_NAME', 'reparaya');
define('DB_USER', 'reparaya_user');
define('DB_PASS', 'reparaya_pass');
*/