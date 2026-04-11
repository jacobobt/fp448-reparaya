<?php

define('APP_NAME', 'ReparaYa');

if ($_SERVER['SERVER_NAME'] === 'localhost') {
    // LOCAL (Docker)
    define('BASE_URL', 'http://localhost:8080');

    define('DB_HOST', 'db');
    define('DB_NAME', 'reparaya');
    define('DB_USER', 'reparaya_user');
    define('DB_PASS', 'reparaya_pass');
} else {
    // SERVIDOR (UOC)
    define('BASE_URL', 'https://fp064.techlab.uoc.edu/~uocx5');

    define('DB_HOST', 'localhost');
    define('DB_NAME', 'wordpress5');
    define('DB_USER', 'wordpress5');
    define('DB_PASS', '2ZNG53TdCaOoLpvp');
}

define('APP_PATH', '/var/www/app');
define('CONFIG_PATH', '/var/www/config');


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