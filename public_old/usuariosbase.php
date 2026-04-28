<?php


require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/models/Usuario.php';


// Insertar usuarios con password_hash, utilizarlo para crear usuarios por script
$usuarios = [
    ['Nacho Fontanero', 'nfontanero@reparaya.edu', '1234', 'Tecnico', '999999998'],
    ['Admin', 'admin@reparaya.edu', '1234', 'Admin', '999999999']
];

foreach ($usuarios as $u) {
    $stmt = $pdo->prepare(
        "INSERT INTO usuarios (nombre, email, password, rol, telefono) VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $u[0],
        $u[1],
        password_hash($u[2], PASSWORD_DEFAULT),
        $u[3],
        $u[4]
    ]);
}

echo "Usuarios insertados correctamente.\n";