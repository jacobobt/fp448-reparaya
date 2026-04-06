<?php


require_once APP_PATH . '/models/Especialidad.php';

class HomeController
{
    private $pdo;
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function index()
    {
    
        $resultado = Especialidad::contarTotal($this->pdo);

        $view = APP_PATH . '/views/home/index.php';
        require APP_PATH . '/views/layouts/main.php';
    }
}