<?php

class Tecnico
{
    public static function listarTodos($pdo)
    {
        $sql = "SELECT
                    t.id,
                    t.usuario_id,
                    t.nombre_completo,
                    t.especialidad_id,
                    t.disponible,
                    u.nombre AS usuario_nombre,
                    u.email AS usuario_email,
                    u.telefono AS usuario_telefono,
                    e.nombre_especialidad
                FROM tecnicos t
                LEFT JOIN usuarios u ON t.usuario_id = u.id
                LEFT JOIN especialidades e ON t.especialidad_id = e.id
                ORDER BY t.nombre_completo ASC";

        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerPorId($pdo, $id)
    {
        $sql = "SELECT *
                FROM tecnicos
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function crear($pdo, $usuarioId, $nombreCompleto, $especialidadId, $disponible)
    {
        $sql = "INSERT INTO tecnicos (usuario_id, nombre_completo, especialidad_id, disponible)
                VALUES (:usuario_id, :nombre_completo, :especialidad_id, :disponible)";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':usuario_id' => $usuarioId ?: null,
            ':nombre_completo' => $nombreCompleto,
            ':especialidad_id' => $especialidadId ?: null,
            ':disponible' => $disponible
        ]);
    }

    public static function actualizar($pdo, $id, $usuarioId, $nombreCompleto, $especialidadId, $disponible)
    {
        $sql = "UPDATE tecnicos
                SET usuario_id = :usuario_id,
                    nombre_completo = :nombre_completo,
                    especialidad_id = :especialidad_id,
                    disponible = :disponible
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':usuario_id' => $usuarioId ?: null,
            ':nombre_completo' => $nombreCompleto,
            ':especialidad_id' => $especialidadId ?: null,
            ':disponible' => $disponible
        ]);
    }

    public static function cambiarDisponibilidad($pdo, $id)
    {
        $sql = "UPDATE tecnicos
                SET disponible = NOT disponible
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public static function usuarioYaAsignado($pdo, $usuarioId, $idExcluir = null)
    {
        if (empty($usuarioId)) {
            return false;
        }

        $sql = "SELECT id
                FROM tecnicos
                WHERE usuario_id = :usuario_id";

        $params = [':usuario_id' => $usuarioId];

        if ($idExcluir !== null) {
            $sql .= " AND id <> :id";
            $params[':id'] = $idExcluir;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function listarUsuariosTecnicoDisponibles($pdo, $idExcluir = null)
    {
        $sql = "SELECT u.id, u.nombre, u.email
                FROM usuarios u
                WHERE u.rol = 'tecnico'
                  AND (
                        u.id NOT IN (
                            SELECT t.usuario_id
                            FROM tecnicos t
                            WHERE t.usuario_id IS NOT NULL
                        )";

        $params = [];

        if ($idExcluir !== null) {
            $sql .= " OR u.id = :id_excluir";
            $params[':id_excluir'] = $idExcluir;
        }

        $sql .= ")
                ORDER BY u.nombre ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}