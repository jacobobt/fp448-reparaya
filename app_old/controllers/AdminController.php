<?php

require_once APP_PATH . '/models/Incidencia.php';

class AdminController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    private function requireAdmin()
    {
        if (empty($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
            header('Location: ' . BASE_URL);
            exit;
        }
    }

    public function dashboard()
    {
        $this->requireAdmin();

        $resumen = Incidencia::contarResumenAdmin($this->pdo);
        $incidencias = array_slice(Incidencia::listarTodasAdmin($this->pdo), 0, 5);

        $view = APP_PATH . '/views/admin/dashboard.php';
        require APP_PATH . '/views/layouts/main.php';
    }

    public function incidencias()
    {
        $this->requireAdmin();

        $incidencias = Incidencia::listarTodasAdmin($this->pdo);
        $mensaje = $_GET['msg'] ?? '';

        $view = APP_PATH . '/views/admin/incidencias/index.php';
        require APP_PATH . '/views/layouts/main.php';
    }

    public function create()
    {
        $this->requireAdmin();

        $incidencia = null;
        $clientes = Incidencia::listarClientes($this->pdo);
        $especialidades = Incidencia::listarEspecialidades($this->pdo);
        $tecnicos = Incidencia::listarTecnicosAsignables($this->pdo);
        $error = '';

        $view = APP_PATH . '/views/admin/incidencias/form.php';
        require APP_PATH . '/views/layouts/main.php';
    }

    public function store()
    {
        $this->requireAdmin();

        $clientes = Incidencia::listarClientes($this->pdo);
        $especialidades = Incidencia::listarEspecialidades($this->pdo);
        $tecnicos = Incidencia::listarTecnicosAsignables($this->pdo);
        $incidencia = null;
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $view = APP_PATH . '/views/admin/incidencias/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        $clienteId = (int) ($_POST['cliente_id'] ?? 0);
        $tecnicoId = !empty($_POST['tecnico_id']) ? (int) $_POST['tecnico_id'] : null;
        $especialidadId = (int) ($_POST['especialidad_id'] ?? 0);
        $descripcion = trim($_POST['descripcion'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $telefonoContacto = trim($_POST['telefono_contacto'] ?? '');
        $fecha = trim($_POST['fecha'] ?? '');
        $franjaHoraria = trim($_POST['franja_horaria'] ?? '');
        $tipoUrgencia = trim($_POST['tipo_urgencia'] ?? 'Estándar');
        $estado = trim($_POST['estado'] ?? 'Pendiente');

        $horaInicio = ($franjaHoraria === '16:00-20:00') ? '16:00:00' : '09:00:00';
        $fechaServicio = $fecha . ' ' . $horaInicio;

        if ($clienteId <= 0 || $especialidadId <= 0 || $descripcion === '' || $direccion === '' || $telefonoContacto === '' || $fecha === '' || $franjaHoraria === '') {
            $error = 'Todos los campos obligatorios deben estar completos.';
            $view = APP_PATH . '/views/admin/incidencias/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        $creada = Incidencia::crearAdmin(
            $this->pdo,
            $clienteId,
            $tecnicoId,
            $especialidadId,
            $descripcion,
            $direccion,
            $telefonoContacto,
            $fechaServicio,
            $franjaHoraria,
            $tipoUrgencia,
            $estado
        );

        if (!$creada) {
            $error = 'No se pudo crear el aviso.';
            $view = APP_PATH . '/views/admin/incidencias/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        header('Location: ' . BASE_URL . '/?page=admin_incidencias&msg=creada');
        exit;
    }

    public function edit()
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        $incidencia = Incidencia::obtenerPorId($this->pdo, $id);

        if (!$incidencia) {
            header('Location: ' . BASE_URL . '/?page=admin_incidencias');
            exit;
        }

        $clientes = Incidencia::listarClientes($this->pdo);
        $especialidades = Incidencia::listarEspecialidades($this->pdo);
        $tecnicos = Incidencia::listarTecnicosAsignables($this->pdo, $incidencia['tecnico_id']);
        $error = '';

        $view = APP_PATH . '/views/admin/incidencias/form.php';
        require APP_PATH . '/views/layouts/main.php';
    }

    public function update()
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        $incidencia = Incidencia::obtenerPorId($this->pdo, $id);

        if (!$incidencia) {
            header('Location: ' . BASE_URL . '/?page=admin_incidencias');
            exit;
        }

        $clientes = Incidencia::listarClientes($this->pdo);
        $especialidades = Incidencia::listarEspecialidades($this->pdo);
        $tecnicos = Incidencia::listarTecnicosAsignables($this->pdo, $incidencia['tecnico_id']);
        $error = '';

        $clienteId = (int) ($_POST['cliente_id'] ?? 0);
        $tecnicoId = !empty($_POST['tecnico_id']) ? (int) $_POST['tecnico_id'] : null;
        $especialidadId = (int) ($_POST['especialidad_id'] ?? 0);
        $descripcion = trim($_POST['descripcion'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $telefonoContacto = trim($_POST['telefono_contacto'] ?? '');
        $fecha = trim($_POST['fecha'] ?? '');
        $franjaHoraria = trim($_POST['franja_horaria'] ?? '');
        $tipoUrgencia = trim($_POST['tipo_urgencia'] ?? 'Estándar');
        $estado = trim($_POST['estado'] ?? 'Pendiente');

        $horaInicio = ($franjaHoraria === '16:00-20:00') ? '16:00:00' : '09:00:00';
        $fechaServicio = $fecha . ' ' . $horaInicio;

        if ($clienteId <= 0 || $especialidadId <= 0 || $descripcion === '' || $direccion === '' || $telefonoContacto === '' || $fecha === '' || $franjaHoraria === '') {
            $error = 'Todos los campos obligatorios deben estar completos.';
            $view = APP_PATH . '/views/admin/incidencias/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        $actualizada = Incidencia::actualizarAdmin(
            $this->pdo,
            $id,
            $clienteId,
            $tecnicoId,
            $especialidadId,
            $descripcion,
            $direccion,
            $telefonoContacto,
            $fechaServicio,
            $franjaHoraria,
            $tipoUrgencia,
            $estado
        );

        if (!$actualizada) {
            $error = 'No se pudo actualizar el aviso.';
            $view = APP_PATH . '/views/admin/incidencias/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        header('Location: ' . BASE_URL . '/?page=admin_incidencias&msg=actualizada');
        exit;
    }

    public function cancel()
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id > 0) {
            Incidencia::cancelarAdmin($this->pdo, $id);
        }

        header('Location: ' . BASE_URL . '/?page=admin_incidencias&msg=cancelada');
        exit;
    }

    public function assign()
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        $incidencia = Incidencia::obtenerPorId($this->pdo, $id);

        if (!$incidencia) {
            header('Location: ' . BASE_URL . '/?page=admin_incidencias');
            exit;
        }

        $tecnicos = Incidencia::listarTecnicosAsignables($this->pdo, $incidencia['tecnico_id']);
        $error = '';

        $view = APP_PATH . '/views/admin/incidencias/assign.php';
        require APP_PATH . '/views/layouts/main.php';
    }

    public function saveAssign()
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        $incidencia = Incidencia::obtenerPorId($this->pdo, $id);

        if (!$incidencia) {
            header('Location: ' . BASE_URL . '/?page=admin_incidencias');
            exit;
        }

        $tecnicos = Incidencia::listarTecnicosAsignables($this->pdo, $incidencia['tecnico_id']);
        $error = '';
        $tecnicoId = !empty($_POST['tecnico_id']) ? (int) $_POST['tecnico_id'] : null;

        $guardada = Incidencia::asignarTecnico($this->pdo, $id, $tecnicoId);

        if (!$guardada) {
            $error = 'No se pudo guardar la asignación.';
            $view = APP_PATH . '/views/admin/incidencias/assign.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        header('Location: ' . BASE_URL . '/?page=admin_incidencias&msg=asignada');
        exit;
    }

    public function calendar()
    {
        $this->requireAdmin();

        $modo = $_GET['mode'] ?? 'month';
        $fecha = $_GET['date'] ?? date('Y-m-d');

        $datosCalendario = Incidencia::obtenerEventosCalendario($this->pdo, $modo, $fecha);
        $eventos = $datosCalendario['eventos'];
        $inicio = $datosCalendario['inicio'];
        $fin = $datosCalendario['fin'];
        $modoActual = $datosCalendario['modo'];

        if ($modoActual === 'day') {
            $tituloRango = $inicio->format('d/m/Y');
            $fechaAnterior = (clone $inicio)->modify('-1 day')->format('Y-m-d');
            $fechaSiguiente = (clone $inicio)->modify('+1 day')->format('Y-m-d');
        } elseif ($modoActual === 'week') {
            $tituloRango = $inicio->format('d/m/Y') . ' - ' . $fin->format('d/m/Y');
            $fechaAnterior = (clone $inicio)->modify('-7 days')->format('Y-m-d');
            $fechaSiguiente = (clone $inicio)->modify('+7 days')->format('Y-m-d');
        } else {
            $tituloRango = $inicio->format('F Y');
            $fechaAnterior = (clone $inicio)->modify('-1 month')->format('Y-m-d');
            $fechaSiguiente = (clone $inicio)->modify('+1 month')->format('Y-m-d');
        }

        $view = APP_PATH . '/views/admin/calendar.php';
        require APP_PATH . '/views/layouts/main.php';
    }
}