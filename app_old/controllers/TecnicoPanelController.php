<?php

require_once APP_PATH . '/models/Incidencia.php';

class TecnicoPanelController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    private function requireTecnico()
    {
        if (empty($_SESSION['usuario'])) {
            header('Location: ' . BASE_URL . '/?page=login');
            exit;
        }

        if ($_SESSION['usuario']['rol'] !== 'tecnico') {
            header('Location: ' . BASE_URL);
            exit;
        }
    }

    public function agenda()
    {
        $this->requireTecnico();

        $tecnico = Incidencia::obtenerTecnicoPorUsuario($this->pdo, $_SESSION['usuario']['id']);
        $resumen = Incidencia::contarResumenTecnicoPorUsuario($this->pdo, $_SESSION['usuario']['id']);
        $incidencias = Incidencia::listarAgendaTecnicoPorUsuario($this->pdo, $_SESSION['usuario']['id']);

        $view = APP_PATH . '/views/tecnico/agenda.php';
        require APP_PATH . '/views/layouts/main.php';
    }
}