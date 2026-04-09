<?php

class Usuario
{
    public static function registrar($pdo, $nombre, $email, $password, $telefono)
    {
        try {
            $sql = "INSERT INTO usuarios (nombre, email, password, rol, telefono)
                    VALUES (:nombre, :email, :password, 'particular', :telefono)";

            $stmt = $pdo->prepare($sql);

            return $stmt->execute([
                ':nombre' => $nombre,
                ':email' => $email,
                ':password' => password_hash($password, PASSWORD_DEFAULT),
                ':telefono' => $telefono
            ]);
        } catch (Throwable $e) {
            return false;
        }
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

    public static function buscarPorId($pdo, $id)
    {
        $sql = "SELECT * FROM usuarios WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function actualizarPerfil($pdo, $id, $nombre, $email, $telefono)
    {
        try {
            $sql = "UPDATE usuarios
                    SET nombre = :nombre, email = :email, telefono = :telefono
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);

            return $stmt->execute([
                ':id' => $id,
                ':nombre' => $nombre,
                ':email' => $email,
                ':telefono' => $telefono
            ]);
        } catch (Throwable $e) {
            return false;
        }
    }

    public static function actualizarPassword($pdo, $id, $passwordNueva)
    {
        try {
            $sql = "UPDATE usuarios
                    SET password = :password
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);

            return $stmt->execute([
                ':id' => $id,
                ':password' => password_hash($passwordNueva, PASSWORD_DEFAULT)
            ]);
        } catch (Throwable $e) {
            return false;
        }
    }
}