<?php
class Especialidad
{
    public static function contarTotal($pdo)
    {
        $stmt= $pdo->query("SELECT COUNT(*) AS total FROM especialidades");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}