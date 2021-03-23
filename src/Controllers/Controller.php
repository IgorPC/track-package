<?php


namespace IgorPC\TrackPackage\Controllers;

require_once(__DIR__."/../Middleware/auth-middleware.php");
include (__DIR__.'/../autoload.php');

abstract class Controller
{
    public function resource()
    {
        $data = $this->getData();
        $way = $data['_'];
        unset($data['_']);

        $function = $this->ways($way);
        $auth = $function[1];
        unset($function[1]);
        $function = $function[0];

        if($function === '' || $function === NULL){
            return send(["error" => 'resource not found'], 404);
        }

        if(auth($auth)) return auth($auth);

        return $this->$function($data);
    }

    protected abstract function ways($ways);

    private function getData()
    {
        $data = $_POST;
        if(count($data) == 0){
            $data = file_get_contents('php://input');
            $data = json_decode($data);
            $temp[] = '';
            foreach ($data as $key => $value){
                $temp[$key] = $value;
            }
            $data = $temp;
            unset($temp);
            if(array_key_exists(0, $data)){
                unset($data[0]);
            }
        }
        return $data;
    }
}