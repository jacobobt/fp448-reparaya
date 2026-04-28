<?php

require_once APP_PATH . '/models/Tecnico.php';
require_once APP_PATH . '/models/Especialidad.php';

class MaestrosController
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

    public function tecnicos()
    {
        $this->requireAdmin();

        $tecnicos = Tecnico::listarTodos($this->pdo);
        $mensaje = $_GET['msg'] ?? '';

        $view = APP_PATH . '/views/maestros/tecnicos/index.php';
        require APP_PATH . '/views/layouts/main.php';
    }

    public function tecnicoCreate()
    {
        $this->requireAdmin();

        $tecnico = null;
        $especialidades = Especialidad::listarTodas($this->pdo);
        $usuariosTecnico = Tecnico::listarUsuariosTecnicoDisponibles($this->pdo);
        $error = '';

        $view = APP_PATH . '/views/maestros/tecnicos/form.php';
        require APP_PATH . '/views/layouts/main.php';
    }

    public function tecnicoStore()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/?page=tecnicos');
            exit;
        }

        $usuarioId = !empty($_POST['usuario_id']) ? (int) $_POST['usuario_id'] : null;
        $nombreCompleto = trim($_POST['nombre_completo'] ?? '');
        $especialidadId = !empty($_POST['especialidad_id']) ? (int) $_POST['especialidad_id'] : null;
        $disponible = isset($_POST['disponible']) ? 1 : 0;

        if ($nombreCompleto === '') {
            $tecnico = null;
            $especialidades = Especialidad::listarTodas($this->pdo);
            $usuariosTecnico = Tecnico::listarUsuariosTecnicoDisponibles($this->pdo);
            $error = 'El nombre completo es obligatorio.';
            $view = APP_PATH . '/views/maestros/tecnicos/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        if (Tecnico::usuarioYaAsignado($this->pdo, $usuarioId)) {
            $tecnico = null;
            $especialidades = Especialidad::listarTodas($this->pdo);
            $usuariosTecnico = Tecnico::listarUsuariosTecnicoDisponibles($this->pdo);
            $error = 'Ese usuario técnico ya está asignado a otro técnico.';
            $view = APP_PATH . '/views/maestros/tecnicos/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        Tecnico::crear($this->pdo, $usuarioId, $nombreCompleto, $especialidadId, $disponible);

        header('Location: ' . BASE_URL . '/?page=tecnicos&msg=creado');
        exit;
    }

    public function tecnicoEdit()
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        $tecnico = Tecnico::obtenerPorId($this->pdo, $id);

        if (!$tecnico) {
            header('Location: ' . BASE_URL . '/?page=tecnicos');
            exit;
        }

        $especialidades = Especialidad::listarTodas($this->pdo);
        $usuariosTecnico = Tecnico::listarUsuariosTecnicoDisponibles($this->pdo, $tecnico['usuario_id']);
        $error = '';

        $view = APP_PATH . '/views/maestros/tecnicos/form.php';
        require APP_PATH . '/views/layouts/main.php';
    }

    public function tecnicoUpdate()
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        $tecnico = Tecnico::obtenerPorId($this->pdo, $id);

        if (!$tecnico) {
            header('Location: ' . BASE_URL . '/?page=tecnicos');
            exit;
        }

        $usuarioId = !empty($_POST['usuario_id']) ? (int) $_POST['usuario_id'] : null;
        $nombreCompleto = trim($_POST['nombre_completo'] ?? '');
        $especialidadId = !empty($_POST['especialidad_id']) ? (int) $_POST['especialidad_id'] : null;
        $disponible = isset($_POST['disponible']) ? 1 : 0;

        if ($nombreCompleto === '') {
            $especialidades = Especialidad::listarTodas($this->pdo);
            $usuariosTecnico = Tecnico::listarUsuariosTecnicoDisponibles($this->pdo, $tecnico['usuario_id']);
            $error = 'El nombre completo es obligatorio.';
            $view = APP_PATH . '/views/maestros/tecnicos/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        if (Tecnico::usuarioYaAsignado($this->pdo, $usuarioId, $id)) {
            $especialidades = Especialidad::listarTodas($this->pdo);
            $usuariosTecnico = Tecnico::listarUsuariosTecnicoDisponibles($this->pdo, $tecnico['usuario_id']);
            $error = 'Ese usuario técnico ya está asignado a otro técnico.';
            $view = APP_PATH . '/views/maestros/tecnicos/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        Tecnico::actualizar($this->pdo, $id, $usuarioId, $nombreCompleto, $especialidadId, $disponible);

        header('Location: ' . BASE_URL . '/?page=tecnicos&msg=actualizado');
        exit;
    }

    public function tecnicoToggle()
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id > 0) {
            Tecnico::cambiarDisponibilidad($this->pdo, $id);
        }

        header('Location: ' . BASE_URL . '/?page=tecnicos&msg=estado');
        exit;
    }

    public function especialidades()
    {
        $this->requireAdmin();

        $especialidades = Especialidad::listarTodas($this->pdo);
        $mensaje = $_GET['msg'] ?? '';

        $view = APP_PATH . '/views/maestros/especialidades/index.php';
        require APP_PATH . '/views/layouts/main.php';
    }

    public function especialidadCreate()
    {
        $this->requireAdmin();

        $especialidad = null;
        $error = '';

        $view = APP_PATH . '/views/maestros/especialidades/form.php';
        require APP_PATH . '/views/layouts/main.php';
    }

    public function especialidadStore()
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/?page=especialidades');
            exit;
        }

        $nombreEspecialidad = trim($_POST['nombre_especialidad'] ?? '');

        if ($nombreEspecialidad === '') {
            $especialidad = null;
            $error = 'El nombre de la especialidad es obligatorio.';
            $view = APP_PATH . '/views/maestros/especialidades/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        if (Especialidad::existeNombre($this->pdo, $nombreEspecialidad)) {
            $especialidad = null;
            $error = 'Ya existe una especialidad con ese nombre.';
            $view = APP_PATH . '/views/maestros/especialidades/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }
        
        $precio = isset($_POST['precio']) ? (float) $_POST['precio'] : 0.00;

        if ($precio < 0) {
            $especialidad = null;
            $error = 'Revisa el precio. No puede ser negativo.';
            $view = APP_PATH . '/views/maestros/especialidades/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        Especialidad::crear($this->pdo, $nombreEspecialidad, $precio);

        header('Location: ' . BASE_URL . '/?page=especialidades&msg=creada');
        exit;
    }

    public function especialidadEdit()
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        $especialidad = Especialidad::obtenerPorId($this->pdo, $id);

        if (!$especialidad) {
            header('Location: ' . BASE_URL . '/?page=especialidades');
            exit;
        }

        $error = '';

        $view = APP_PATH . '/views/maestros/especialidades/form.php';
        require APP_PATH . '/views/layouts/main.php';
    }

    public function especialidadUpdate()
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);
        $especialidad = Especialidad::obtenerPorId($this->pdo, $id);

        if (!$especialidad) {
            header('Location: ' . BASE_URL . '/?page=especialidades');
            exit;
        }

        $nombreEspecialidad = trim($_POST['nombre_especialidad'] ?? '');

        if ($nombreEspecialidad === '') {
            $error = 'El nombre de la especialidad es obligatorio.';
            $view = APP_PATH . '/views/maestros/especialidades/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        if (Especialidad::existeNombreEnOtro($this->pdo, $id, $nombreEspecialidad)) {
            $error = 'Ya existe otra especialidad con ese nombre.';
            $view = APP_PATH . '/views/maestros/especialidades/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        $precio = isset($_POST['precio']) ? (float) $_POST['precio'] : 0.00;

        if ($precio < 0) {
            $error = 'Revisa el precio. No puede ser negativo.';
            $view = APP_PATH . '/views/maestros/especialidades/form.php';
            require APP_PATH . '/views/layouts/main.php';
            return;
        }

        Especialidad::actualizar($this->pdo, $id, $nombreEspecialidad, $precio);

        header('Location: ' . BASE_URL . '/?page=especialidades&msg=actualizada');
        exit;
    }

    public function especialidadDelete()
    {
        $this->requireAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id > 0) {
            if (Especialidad::tieneDependencias($this->pdo, $id)) {
                header('Location: ' . BASE_URL . '/?page=especialidades&msg=bloqueada');
                exit;
            }

            Especialidad::eliminar($this->pdo, $id);
        }

        header('Location: ' . BASE_URL . '/?page=especialidades&msg=eliminada');
        exit;
    }
}