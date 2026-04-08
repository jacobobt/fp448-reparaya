<?php
require_once '/var/www/config/database.php';
require_once APP_PATH . '/models/Admin_modelo.php';

class AdminController {
    private $modelo;

    public function __construct($pdo) {
        // CORRECCIÓN: eliminado el require_once duplicado que causaba "class already declared"
        $this->modelo = new Admin_modelo($pdo);
    }

    // ── DASHBOARD ──────────────────────────────────────────────────

    public function index() {
        $incidencias    = $this->modelo->listarIncidencias();
        $tecnicos       = $this->modelo->listarTecnicos();
        $especialidades = $this->modelo->listarEspecialidades();
        $stats          = $this->modelo->obtenerEstadisticas();

        $view = APP_PATH . '/views/admin/dashboard.php';
        require_once APP_PATH . '/views/layouts/main.php';
    }

    // ── CALENDARIO ─────────────────────────────────────────────────

    public function calendario() {
        $incidencias = $this->modelo->listarIncidencias();

        $view = APP_PATH . '/views/admin/calendario.php';
        require_once APP_PATH . '/views/layouts/main.php';
    }

    // ── NUEVO AVISO (GET = form, POST = guardar) ───────────────────

    public function nuevoAviso() {
        $especialidades = $this->modelo->listarEspecialidades();
        $clientes       = $this->modelo->listarClientes();
        $incidencia     = null; // null = modo creación
        $error          = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validación básica
            if (empty($_POST['cliente_id']) || empty($_POST['descripcion']) || empty($_POST['direccion']) || empty($_POST['fecha_servicio'])) {
                $error = 'Por favor, rellena todos los campos obligatorios.';
            } else {
                $datos = [
                    'cliente_id'      => (int) $_POST['cliente_id'],
                    'especialidad_id' => (int) $_POST['especialidad_id'],
                    'descripcion'     => htmlspecialchars(trim($_POST['descripcion'])),
                    'direccion'       => htmlspecialchars(trim($_POST['direccion'])),
                    'fecha_servicio'  => $_POST['fecha_servicio'] . ' ' . ($_POST['hora_servicio'] ?? '09:00') . ':00',
                    'tipo_urgencia'   => $_POST['tipo_urgencia'] ?? 'Estándar',
                ];

                if ($this->modelo->guardarIncidencia($datos)) {
                    header('Location: ' . BASE_URL . '?page=admin_dashboard&msg=creado');
                    exit;
                } else {
                    $error = 'No se pudo guardar la incidencia. Inténtalo de nuevo.';
                }
            }
        }

        $view = APP_PATH . '/views/admin/nuevo_aviso.php';
        require_once APP_PATH . '/views/layouts/main.php';
    }

    // ── EDITAR AVISO (GET = form con datos, POST = actualizar) ─────

    public function editarAviso() {
        $id = (int) ($_GET['id'] ?? 0);
        if (!$id) {
            header('Location: ' . BASE_URL . '?page=admin_dashboard');
            exit;
        }

        $incidencia     = $this->modelo->obtenerIncidencia($id);
        $especialidades = $this->modelo->listarEspecialidades();
        $clientes       = $this->modelo->listarClientes();
        $error          = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = [
                'especialidad_id' => (int) $_POST['especialidad_id'],
                'descripcion'     => htmlspecialchars(trim($_POST['descripcion'])),
                'direccion'       => htmlspecialchars(trim($_POST['direccion'])),
                'fecha_servicio'  => $_POST['fecha_servicio'] . ' ' . ($_POST['hora_servicio'] ?? '09:00') . ':00',
                'tipo_urgencia'   => $_POST['tipo_urgencia'] ?? 'Estándar',
                'estado'          => $_POST['estado'],
            ];

            if ($this->modelo->editarIncidencia($id, $datos)) {
                header('Location: ' . BASE_URL . '?page=admin_dashboard&msg=editado');
                exit;
            } else {
                $error = 'No se pudo actualizar la incidencia.';
            }
        }

        $view = APP_PATH . '/views/admin/nuevo_aviso.php'; // mismo form, reutilizado
        require_once APP_PATH . '/views/layouts/main.php';
    }

    // ── CANCELAR AVISO ─────────────────────────────────────────────

    public function cancelarAviso() {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id) {
            $this->modelo->cancelarIncidencia($id);
        }
        header('Location: ' . BASE_URL . '?page=admin_dashboard&msg=cancelado');
        exit;
    }

    // ── ASIGNAR TÉCNICO (GET = form, POST = guardar) ───────────────

    public function asignar() {
        // Acepta el id tanto por GET (al abrir el form) como por POST (al enviar)
        $id = (int) ($_GET['id'] ?? $_POST['id_incidencia'] ?? 0);
        if (!$id) {
            header('Location: ' . BASE_URL . '?page=admin_dashboard');
            exit;
        }

        $incidencia = $this->modelo->obtenerIncidencia($id);
        $tecnicos   = $this->modelo->listarTecnicos();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_tecnico = (int) $_POST['id_tecnico'];

            if ($id_tecnico === 0) {
                $ok = $this->modelo->desasignarTecnico($id);
            } else {
                $ok = $this->modelo->asignarTecnico($id, $id_tecnico);
            }

            if ($ok) {
                header('Location: ' . BASE_URL . '?page=admin_dashboard&msg=asignado');
                exit;
            }
        }

        $view = APP_PATH . '/views/admin/asignar.php';
        require_once APP_PATH . '/views/layouts/main.php';
    }
}
