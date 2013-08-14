<?php

class Core {
    private static $instance;

    public $debug = false;
    public $base_path;
    public $internal_config;
    public $config;
    public $page;
    public $routes;
    public $controller;



    // Singleton
    private function __construct() { }

    public static function get()
    {
        if(!isset(self::$instance))
        {
            self::$instance = new Core();
        }

        return self::$instance;
    }

    public function __clone() { }

    private function __wakeup() { }



    public function run($ajax = false, $debug = false) {
        if ($debug) {
            $this->debug = true;

            error_reporting(E_ALL);
            ini_set('display_errors', 1);
        }

        $this->config = $this->configurer->loadConfig(CONFIG);
        $this->internal_config = $this->configurer->loadConfig(INTERNAL_CONFIG);
        $this->routes = $this->configurer->loadConfig(ROUTES);

        session_start();

        $this->page = $this->router->getPage();
        $this->loadController($this->page);
        $this->controller->run();
    }

    private function loadClass($name) {
        $uppercase_name = mb_strtoupper(str_replace('_', '', $name));
        $path = CLASSES . '/' . $name . '.class.php';

        if (!file_exists($path)) {
            return false;
        }

        include_once $path;

        return $this->$name = new $uppercase_name;
    }

    private function loadController($controller) {
        require_once CONTROLLERS . '/' . $controller . '.controller.php';
        $controller_class = str_replace('-', '', $controller) . 'Controller';

        return $this->controller = new $controller_class;
    }

    public function __get($name) {
        if (!isset($this->$name) && $this->loadClass($name)) {
            return $this->$name;
        }

        return null;
    }
}

?>