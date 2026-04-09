<?php

class Incidencia
{
    public static function listarEspecialidades($pdo)
    {
        $sql = "SELECT id, nombre_especialidad
                FROM especialidades
                ORDER BY nombre_especialidad ASC";

        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function generarLocalizador($pdo)
    {
        $anio = date('Y');

        $sql = "SELECT COUNT(*) + 1
                FROM incidencias
                WHERE YEAR(created_at) = :anio";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':anio' => $anio
        ]);

        $numero = (int) $stmt->fetchColumn();

        return 'REP-' . $anio . '-' . str_pad((string) $numero, 4, '0', STR_PAD_LEFT);
    }

    public static function crearCliente($pdo, $clienteId, $especialidadId, $descripcion, $direccion, $telefonoContacto, $fechaServicio, $franjaHoraria, $tipoUrgencia)
    {
        try {
            $localizador = self::generarLocalizador($pdo);

            $sql = "INSERT INTO incidencias
                    (localizador, cliente_id, especialidad_id, descripcion, direccion, telefono_contacto, fecha_servicio, franja_horaria, tipo_urgencia, estado)
                    VALUES
                    (:localizador, :cliente_id, :especialidad_id, :descripcion, :direccion, :telefono_contacto, :fecha_servicio, :franja_horaria, :tipo_urgencia, 'Pendiente')";

            $stmt = $pdo->prepare($sql);

            return $stmt->execute([
                ':localizador' => $localizador,
                ':cliente_id' => $clienteId,
                ':especialidad_id' => $especialidadId,
                ':descripcion' => $descripcion,
                ':direccion' => $direccion,
                ':telefono_contacto' => $telefonoContacto,
                ':fecha_servicio' => $fechaServicio,
                ':franja_horaria' => $franjaHoraria,
                ':tipo_urgencia' => $tipoUrgencia
            ]);
        } catch (Throwable $e) {
            return false;
        }
    }

    public static function listarPorCliente($pdo, $clienteId)
    {
        $sql = "SELECT
                    i.id,
                    i.localizador,
                    i.descripcion,
                    i.direccion,
                    i.telefono_contacto,
                    i.fecha_servicio,
                    i.franja_horaria,
                    i.tipo_urgencia,
                    i.estado,
                    e.nombre_especialidad,
                    t.nombre_completo AS tecnico_nombre
                FROM incidencias i
                INNER JOIN especialidades e ON i.especialidad_id = e.id
                LEFT JOIN tecnicos t ON i.tecnico_id = t.id
                WHERE i.cliente_id = :cliente_id
                ORDER BY i.fecha_servicio DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cliente_id' => $clienteId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerPorIdYCliente($pdo, $id, $clienteId)
    {
        $sql = "SELECT *
                FROM incidencias
                WHERE id = :id
                  AND cliente_id = :cliente_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':cliente_id' => $clienteId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function cancelarCliente($pdo, $id, $clienteId)
    {
        try {
            $sql = "UPDATE incidencias
                    SET estado = 'Cancelada'
                    WHERE id = :id
                      AND cliente_id = :cliente_id";

            $stmt = $pdo->prepare($sql);

            return $stmt->execute([
                ':id' => $id,
                ':cliente_id' => $clienteId
            ]);
        } catch (Throwable $e) {
            return false;
        }
    }

    public static function puedeCrearEstandar($fechaServicio)
    {
        $ahora = new DateTime();
        $fecha = new DateTime($fechaServicio);

        return ($fecha->getTimestamp() - $ahora->getTimestamp()) >= (48 * 60 * 60);
    }

    public static function puedeCancelarCliente($incidencia)
    {
        if (!$incidencia) {
            return false;
        }

        if ($incidencia['estado'] === 'Cancelada' || $incidencia['estado'] === 'Finalizada') {
            return false;
        }

        $ahora = new DateTime();
        $fecha = new DateTime($incidencia['fecha_servicio']);

        return ($fecha->getTimestamp() - $ahora->getTimestamp()) >= (48 * 60 * 60);
    }
}