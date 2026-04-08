<?php
class Admin_modelo {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    // ── INCIDENCIAS ────────────────────────────────────────────────

    public function listarIncidencias() {
        $sql = "SELECT 
                    i.id,
                    i.localizador,
                    i.descripcion,
                    i.direccion,
                    i.fecha_servicio,
                    i.tipo_urgencia,
                    i.estado,
                    i.created_at,
                    uc.nombre      AS cliente_nombre,
                    uc.telefono    AS cliente_telefono,
                    e.nombre_especialidad AS especialidad,
                    t.id           AS tecnico_id,
                    ut.nombre      AS tecnico_nombre
                FROM incidencias i
                INNER JOIN usuarios      uc ON i.cliente_id      = uc.id
                LEFT  JOIN tecnicos      t  ON i.tecnico_id      = t.id
                LEFT  JOIN usuarios      ut ON t.usuario_id      = ut.id
                INNER JOIN especialidades e  ON i.especialidad_id = e.id
                ORDER BY i.fecha_servicio ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerIncidencia($id) {
        $sql = "SELECT 
                    i.*,
                    uc.nombre      AS cliente_nombre,
                    e.nombre_especialidad AS especialidad,
                    t.id           AS tecnico_id,
                    ut.nombre      AS tecnico_nombre
                FROM incidencias i
                INNER JOIN usuarios      uc ON i.cliente_id      = uc.id
                LEFT  JOIN tecnicos      t  ON i.tecnico_id      = t.id
                LEFT  JOIN usuarios      ut ON t.usuario_id      = ut.id
                INNER JOIN especialidades e  ON i.especialidad_id = e.id
                WHERE i.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function guardarIncidencia($datos) {
    
    $localizador = 'REP-' . date('y') . '-' . strtoupper(substr(uniqid(), -4));
    

    
    $sql = "INSERT INTO incidencias (localizador, cliente_id, especialidad_id, descripcion, direccion, fecha_servicio, tipo_urgencia, estado) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'Pendiente')";
    
    $stmt = $this->db->prepare($sql);
    
    
    return $stmt->execute([
        $localizador,
        $datos['cliente_id'],
        $datos['especialidad_id'],
        $datos['descripcion'],
        $datos['direccion'],
        $datos['fecha_servicio'],
        $datos['tipo_urgencia']
    ]);
}

    public function editarIncidencia($id, $datos) {
        $sql = "UPDATE incidencias SET
                    especialidad_id = ?,
                    descripcion     = ?,
                    direccion       = ?,
                    fecha_servicio  = ?,
                    tipo_urgencia   = ?,
                    estado          = ?
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $datos['especialidad_id'],
            $datos['descripcion'],
            $datos['direccion'],
            $datos['fecha_servicio'],
            $datos['tipo_urgencia'],
            $datos['estado'],
            $id
        ]);
    }

    public function cancelarIncidencia($id) {
        $sql = "UPDATE incidencias SET estado = 'Cancelada' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // ── ASIGNACIÓN ─────────────────────────────────────────────────

   
    public function asignarTecnico($id_incidencia, $id_tecnico) {
        $sql = "UPDATE incidencias SET tecnico_id = ?, estado = 'Asignada' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_tecnico, $id_incidencia]);
    }

    public function desasignarTecnico($id_incidencia) {
        $sql = "UPDATE incidencias SET tecnico_id = NULL, estado = 'Pendiente' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id_incidencia]);
    }

    // ── TÉCNICOS ───────────────────────────────────────────────────

    
    public function listarTecnicos() {
        $sql = "SELECT 
                    t.id,
                    t.disponible,
                    u.nombre,
                    u.email,
                    u.telefono,
                    e.nombre_especialidad AS especialidad,
                    e.id AS especialidad_id,
                    (SELECT COUNT(*) FROM incidencias i 
                     WHERE i.tecnico_id = t.id AND i.estado = 'Asignada') AS avisos_activos
                FROM tecnicos t
                INNER JOIN usuarios      u ON t.usuario_id     = u.id
                LEFT  JOIN especialidades e ON t.especialidad_id = e.id
                ORDER BY u.nombre ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── ESPECIALIDADES ─────────────────────────────────────────────

    public function listarEspecialidades() {
        $sql = "SELECT id, nombre_especialidad FROM especialidades ORDER BY nombre_especialidad ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── CLIENTES ───────────────────────────────────────────────────

    public function listarClientes() {
        $sql = "SELECT id, nombre, email FROM usuarios WHERE rol = 'particular' ORDER BY nombre ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── ESTADÍSTICAS ───────────────────────────────────────────────

    public function obtenerEstadisticas() {
        $stats = [];
        $queries = [
            'total'       => "SELECT COUNT(*) FROM incidencias",
            'urgentes'    => "SELECT COUNT(*) FROM incidencias WHERE tipo_urgencia = 'Urgente'",
            'pendientes'  => "SELECT COUNT(*) FROM incidencias WHERE estado = 'Pendiente'",
            'asignadas'   => "SELECT COUNT(*) FROM incidencias WHERE estado = 'Asignada'",
            'finalizadas' => "SELECT COUNT(*) FROM incidencias WHERE estado = 'Finalizada'",
            'tecnicos'    => "SELECT COUNT(*) FROM tecnicos WHERE disponible = 1",
        ];
        foreach ($queries as $key => $sql) {
            $stats[$key] = (int) $this->db->query($sql)->fetchColumn();
        }
        return $stats;
    }
}
