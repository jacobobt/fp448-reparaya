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

    public static function listarClientes($pdo)
    {
        $sql = "SELECT id, nombre, email, telefono
                FROM usuarios
                WHERE rol = 'particular'
                ORDER BY nombre ASC";

        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarTecnicosAsignables($pdo, $tecnicoSeleccionado = null)
    {
        $sql = "SELECT
                    t.id,
                    t.nombre_completo,
                    t.disponible,
                    e.nombre_especialidad
                FROM tecnicos t
                LEFT JOIN especialidades e ON t.especialidad_id = e.id
                WHERE t.disponible = 1";

        $params = [];

        if ($tecnicoSeleccionado !== null) {
            $sql .= " OR t.id = :tecnico_id";
            $params[':tecnico_id'] = $tecnicoSeleccionado;
        }

        $sql .= " ORDER BY t.nombre_completo ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerTecnicoPorUsuario($pdo, $usuarioId)
    {
        $sql = "SELECT
                    t.id,
                    t.usuario_id,
                    t.nombre_completo,
                    t.disponible,
                    e.nombre_especialidad
                FROM tecnicos t
                LEFT JOIN especialidades e ON t.especialidad_id = e.id
                WHERE t.usuario_id = :usuario_id
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':usuario_id' => $usuarioId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function contarResumenTecnicoPorUsuario($pdo, $usuarioId)
    {
        $sql = "SELECT
                    COUNT(*) AS total,
                    SUM(CASE WHEN i.estado = 'Asignada' THEN 1 ELSE 0 END) AS asignadas,
                    SUM(CASE WHEN i.estado = 'Pendiente' THEN 1 ELSE 0 END) AS pendientes,
                    SUM(CASE WHEN i.tipo_urgencia = 'Urgente' THEN 1 ELSE 0 END) AS urgentes
                FROM incidencias i
                INNER JOIN tecnicos t ON i.tecnico_id = t.id
                WHERE t.usuario_id = :usuario_id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':usuario_id' => $usuarioId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function listarAgendaTecnicoPorUsuario($pdo, $usuarioId)
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
                    c.nombre AS cliente_nombre,
                    c.email AS cliente_email,
                    c.telefono AS cliente_telefono
                FROM incidencias i
                INNER JOIN tecnicos t ON i.tecnico_id = t.id
                INNER JOIN especialidades e ON i.especialidad_id = e.id
                INNER JOIN usuarios c ON i.cliente_id = c.id
                WHERE t.usuario_id = :usuario_id
                ORDER BY i.fecha_servicio ASC, i.id ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':usuario_id' => $usuarioId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            die($e->getMessage());
            // return false;
        }
    }

    public static function crearAdmin($pdo, $clienteId, $tecnicoId, $especialidadId, $descripcion, $direccion, $telefonoContacto, $fechaServicio, $franjaHoraria, $tipoUrgencia, $estado)
    {
        try {
            $localizador = self::generarLocalizador($pdo);

            if (!empty($tecnicoId) && $estado === 'Pendiente') {
                $estado = 'Asignada';
            }

            if (empty($tecnicoId) && $estado === 'Asignada') {
                $estado = 'Pendiente';
            }

            $sql = "INSERT INTO incidencias
                    (localizador, cliente_id, tecnico_id, especialidad_id, descripcion, direccion, telefono_contacto, fecha_servicio, franja_horaria, tipo_urgencia, estado)
                    VALUES
                    (:localizador, :cliente_id, :tecnico_id, :especialidad_id, :descripcion, :direccion, :telefono_contacto, :fecha_servicio, :franja_horaria, :tipo_urgencia, :estado)";

            $stmt = $pdo->prepare($sql);

            return $stmt->execute([
                ':localizador' => $localizador,
                ':cliente_id' => $clienteId,
                ':tecnico_id' => $tecnicoId ?: null,
                ':especialidad_id' => $especialidadId,
                ':descripcion' => $descripcion,
                ':direccion' => $direccion,
                ':telefono_contacto' => $telefonoContacto,
                ':fecha_servicio' => $fechaServicio,
                ':franja_horaria' => $franjaHoraria,
                ':tipo_urgencia' => $tipoUrgencia,
                ':estado' => $estado
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

    public static function listarTodasAdmin($pdo)
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
                    c.nombre AS cliente_nombre,
                    c.email AS cliente_email,
                    t.nombre_completo AS tecnico_nombre
                FROM incidencias i
                INNER JOIN especialidades e ON i.especialidad_id = e.id
                INNER JOIN usuarios c ON i.cliente_id = c.id
                LEFT JOIN tecnicos t ON i.tecnico_id = t.id
                ORDER BY i.fecha_servicio DESC, i.id DESC";

        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerPorId($pdo, $id)
    {
        $sql = "SELECT *
                FROM incidencias
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
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

    public static function actualizarAdmin($pdo, $id, $clienteId, $tecnicoId, $especialidadId, $descripcion, $direccion, $telefonoContacto, $fechaServicio, $franjaHoraria, $tipoUrgencia, $estado)
    {
        try {
            if (!empty($tecnicoId) && $estado === 'Pendiente') {
                $estado = 'Asignada';
            }

            if (empty($tecnicoId) && $estado === 'Asignada') {
                $estado = 'Pendiente';
            }

            $sql = "UPDATE incidencias
                    SET cliente_id = :cliente_id,
                        tecnico_id = :tecnico_id,
                        especialidad_id = :especialidad_id,
                        descripcion = :descripcion,
                        direccion = :direccion,
                        telefono_contacto = :telefono_contacto,
                        fecha_servicio = :fecha_servicio,
                        franja_horaria = :franja_horaria,
                        tipo_urgencia = :tipo_urgencia,
                        estado = :estado
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);

            return $stmt->execute([
                ':id' => $id,
                ':cliente_id' => $clienteId,
                ':tecnico_id' => $tecnicoId ?: null,
                ':especialidad_id' => $especialidadId,
                ':descripcion' => $descripcion,
                ':direccion' => $direccion,
                ':telefono_contacto' => $telefonoContacto,
                ':fecha_servicio' => $fechaServicio,
                ':franja_horaria' => $franjaHoraria,
                ':tipo_urgencia' => $tipoUrgencia,
                ':estado' => $estado
            ]);
        } catch (Throwable $e) {
            return false;
        }
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

    public static function cancelarAdmin($pdo, $id)
    {
        try {
            $sql = "UPDATE incidencias
                    SET estado = 'Cancelada'
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);

            return $stmt->execute([
                ':id' => $id
            ]);
        } catch (Throwable $e) {
            return false;
        }
    }

    public static function asignarTecnico($pdo, $id, $tecnicoId)
    {
        try {
            $estado = !empty($tecnicoId) ? 'Asignada' : 'Pendiente';

            $sql = "UPDATE incidencias
                    SET tecnico_id = :tecnico_id,
                        estado = :estado
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);

            return $stmt->execute([
                ':id' => $id,
                ':tecnico_id' => $tecnicoId ?: null,
                ':estado' => $estado
            ]);
        } catch (Throwable $e) {
            return false;
        }
    }

    public static function contarResumenAdmin($pdo)
    {
        $sql = "SELECT
                    COUNT(*) AS total,
                    SUM(CASE WHEN estado = 'Pendiente' THEN 1 ELSE 0 END) AS pendientes,
                    SUM(CASE WHEN estado = 'Asignada' THEN 1 ELSE 0 END) AS asignadas,
                    SUM(CASE WHEN tipo_urgencia = 'Urgente' THEN 1 ELSE 0 END) AS urgentes
                FROM incidencias";

        return $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public static function obtenerEventosCalendario($pdo, $modo, $fechaBase)
    {
        $fecha = new DateTime($fechaBase ?: date('Y-m-d'));

        if ($modo === 'week') {
            $inicio = clone $fecha;
            $inicio->modify('monday this week')->setTime(0, 0, 0);

            $fin = clone $inicio;
            $fin->modify('+6 days')->setTime(23, 59, 59);
        } elseif ($modo === 'day') {
            $inicio = clone $fecha;
            $inicio->setTime(0, 0, 0);

            $fin = clone $fecha;
            $fin->setTime(23, 59, 59);
        } else {
            $modo = 'month';

            $inicio = new DateTime($fecha->format('Y-m-01 00:00:00'));
            $fin = new DateTime($fecha->format('Y-m-t 23:59:59'));
        }

        $sql = "SELECT
                    i.id,
                    i.localizador,
                    i.descripcion,
                    i.direccion,
                    i.fecha_servicio,
                    i.franja_horaria,
                    i.tipo_urgencia,
                    i.estado,
                    e.nombre_especialidad,
                    c.nombre AS cliente_nombre,
                    t.nombre_completo AS tecnico_nombre
                FROM incidencias i
                INNER JOIN especialidades e ON i.especialidad_id = e.id
                INNER JOIN usuarios c ON i.cliente_id = c.id
                LEFT JOIN tecnicos t ON i.tecnico_id = t.id
                WHERE i.fecha_servicio BETWEEN :inicio AND :fin
                ORDER BY i.fecha_servicio ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':inicio' => $inicio->format('Y-m-d H:i:s'),
            ':fin' => $fin->format('Y-m-d H:i:s')
        ]);

        return [
            'modo' => $modo,
            'inicio' => $inicio,
            'fin' => $fin,
            'eventos' => $stmt->fetchAll(PDO::FETCH_ASSOC)
        ];
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