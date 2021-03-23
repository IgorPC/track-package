<?php


namespace IgorPC\TrackPackage;

require_once "Routes/routes.php";
require_once "functions.php";

class Router
{
    private string $url;
    private string $method;

    public function __construct($url, $method)
    {
        $this->url = $url;
        $this->method = $method;
    }

    public function getAvailableRoutes()
    {
        return routes();
    }

    public function render()
    {
        if(!is_array(routes()[$this->url])){
            echo send(["error" => 'route not found'], 404);
        }elseif(routes()[$this->url]['method'] != $this->method){
            echo send(["error" => 'method not allowed'], 403);
        }else{
            $controller = explode('@', routes()[$this->url]['controller']);
            print_r ($this->execute($controller[0], $controller[1]));
        }
    }

    private function execute(String $controller, String $method)
    {
        $file = $_SERVER['DOCUMENT_ROOT'].'/src/Controllers/'.$controller.'.php';
        if(!file_exists($file)){
            return send(["error" => 'internal error'], 500);
        }

        $controller = "IgorPC\\TrackPackage\\Controllers\\".$controller;

        $control = new $controller();
        return $control->$method();
    }
}