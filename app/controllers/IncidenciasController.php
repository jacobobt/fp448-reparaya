<?php

require_once APP_PATH . '/models/Incidencia.php';

class IncidenciasController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    private function requireCliente()
    {
        if (empty($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/?page=login');
            exit;
        }

        if ($_SESSION['usuario']['rol'] !== 'particular') {
            header('Location: ' . BASE_URL);
            exit;
        }
    }

    public function misAvisos()
    {
        $this->requireCliente();

        $incidencias = Incidencia::listarPorCliente($this->pdo, $_SESSION['usuario']['id']);
        $mensaje = $_GET['msg'] ?? '';

        $view = APP_PATH . '/views/incidencias/index.php';
        require APP_PATH . '/views/layouts/main.php';
    }

    public function create()
    {
        $this->requireCliente();

        $especialidades = Incidencia::listarEspecialidades($this->pdo);
        $error = '';

        $view = APP_PATH . '/views/incidencias/form.php';
        require APP_PATH . '/views/layouts/main.php';
    }

    public function store()
    {
        $this->requireCliente();

        $especialidades = Incidencia::listarEspecialidades($this->pdo);
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $view = APP_PATH . '/views/incidencias/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        $especialidadId = (int) ($_POST['especialidad_id'] ?? 0);
        $descripcion = trim($_POST['descripcion'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $telefonoContacto = trim($_POST['telefono_contacto'] ?? '');
        $fecha = trim($_POST['fecha'] ?? '');
        $franjaHoraria = trim($_POST['franja_horaria'] ?? '');
        $tipoUrgencia = trim($_POST['tipo_urgencia'] ?? 'Estandar');

        $horaInicio = '09:00:00';
        if ($franjaHoraria === '16:00-20:00') {
            $horaInicio = '16:00:00';
        }

        $fechaServicio = $fecha . ' ' . $horaInicio;

        if ($especialidadId <= 0 || $descripcion === '' || $direccion === '' || $telefonoContacto === '' || $fecha === '' || $franjaHoraria === '') {
            $error = 'Todos los campos son obligatorios.';
            $view = APP_PATH . '/views/incidencias/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        if ($tipoUrgencia === 'Estandar' && !Incidencia::puedeCrearEstandar($fechaServicio)) {
            $error = 'Los avisos estándar deben solicitarse con al menos 48 horas de antelación.';
            $view = APP_PATH . '/views/incidencias/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        $creada = Incidencia::crearCliente(
            $this->pdo,
            $_SESSION['usuario']['id'],
            $especialidadId,
            $descripcion,
            $direccion,
            $telefonoContacto,
            $fechaServicio,
            $franjaHoraria,
            $tipoUrgencia
        );

        if (!$creada) {
            $error = 'No se pudo crear la solicitud.';
            $view = APP_PATH . '/views/incidencias/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        header('Location: ' . BASE_URL . '/?page=mis_avisos&msg=creada');
        exit;
    }

    public function cancelar()
    {
        $this->requireCliente();

        $id = (int) ($_GET['id'] ?? 0);
        $incidencia = Incidencia::obtenerPorIdYCliente($this->pdo, $id, $_SESSION['usuario']['id']);

        if (!$incidencia) {
            header('Location: ' . BASE_URL . '/?page=mis_avisos&msg=no_encontrada');
            exit;
        }

        if (!Incidencia::puedeCancelarCliente($incidencia)) {
            header('Location: ' . BASE_URL . '/?page=mis_avisos&msg=bloqueada');
            exit;
        }

        Incidencia::cancelarCliente($this->pdo, $id, $_SESSION['usuario']['id']);

        header('Location: ' . BASE_URL . '/?page=mis_avisos&msg=cancelada');
        exit;
    }
}