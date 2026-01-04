<?php

class App
{
    protected $router;

    public function __construct()
    {
        $this->router = new Router();
        $this->loadRoutes();
    }

    private function loadRoutes()
    {
        require_once __DIR__ . '/../routes/web.php';
    }

    public function run()
    {
        $url = isset($_GET['url']) ? $_GET['url'] : '';
        $this->router->dispatch($url);
    }

    public function getRouter()
    {
        return $this->router;
    }
}
