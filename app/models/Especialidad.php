<?php

class Especialidad
{
    public static function listarTodas($pdo)
    {
        $sql = "SELECT 
                    e.id,
                    e.nombre_especialidad,
                    (SELECT COUNT(*) FROM tecnicos t WHERE t.especialidad_id = e.id) AS total_tecnicos,
                    (SELECT COUNT(*) FROM incidencias i WHERE i.especialidad_id = e.id) AS total_incidencias
                FROM especialidades e
                ORDER BY e.nombre_especialidad ASC";

        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerPorId($pdo, $id)
    {
        $sql = "SELECT * FROM especialidades WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function crear($pdo, $nombreEspecialidad)
    {
        $sql = "INSERT INTO especialidades (nombre_especialidad)
                VALUES (:nombre_especialidad)";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':nombre_especialidad' => $nombreEspecialidad
        ]);
    }

    public static function actualizar($pdo, $id, $nombreEspecialidad)
    {
        $sql = "UPDATE especialidades
                SET nombre_especialidad = :nombre_especialidad
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':nombre_especialidad' => $nombreEspecialidad
        ]);
    }

    public static function eliminar($pdo, $id)
    {
        $sql = "DELETE FROM especialidades WHERE id = :id";
        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public static function existeNombre($pdo, $nombreEspecialidad)
    {
        $sql = "SELECT id
                FROM especialidades
                WHERE LOWER(nombre_especialidad) = LOWER(:nombre_especialidad)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre_especialidad' => $nombreEspecialidad
        ]);

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function existeNombreEnOtro($pdo, $id, $nombreEspecialidad)
    {
        $sql = "SELECT id
                FROM especialidades
                WHERE LOWER(nombre_especialidad) = LOWER(:nombre_especialidad)
                  AND id <> :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':nombre_especialidad' => $nombreEspecialidad
        ]);

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function tieneDependencias($pdo, $id)
    {
        $sqlTecnicos = "SELECT COUNT(*) FROM tecnicos WHERE especialidad_id = :id";
        $stmtTecnicos = $pdo->prepare($sqlTecnicos);
        $stmtTecnicos->execute([':id' => $id]);
        $totalTecnicos = (int) $stmtTecnicos->fetchColumn();

        $sqlIncidencias = "SELECT COUNT(*) FROM incidencias WHERE especialidad_id = :id";
        $stmtIncidencias = $pdo->prepare($sqlIncidencias);
        $stmtIncidencias->execute([':id' => $id]);
        $totalIncidencias = (int) $stmtIncidencias->fetchColumn();

        return ($totalTecnicos > 0 || $totalIncidencias > 0);
    }
}