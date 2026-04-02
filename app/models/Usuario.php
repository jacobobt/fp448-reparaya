<?php

class Usuario
{
    public static function registrar($pdo, $nombre, $email, $password, $telefono)
    {
        $sql = "INSERT INTO usuarios (nombre, email, password, rol, telefono)
                VALUES (:nombre, :email, :password, 'particular', :telefono)";

        $stmt = $pdo->prepare($sql);

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        return $stmt->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':password' => $passwordHash,
            ':telefono' => $telefono
        ]);
    }

    public static function buscarPorEmail($pdo, $email)
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':email' => $email
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}