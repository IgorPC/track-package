<?php

use IgorPC\TrackPackage\Middleware\AuthConfig;

require_once ("AuthConfig.php");

function auth($auth)
{

    if($auth === 'none'){
        return false;
    }

    if(!array_key_exists('HTTP_AUTHORIZATION', $_SERVER)) return send(["error" => 'Authorization header not found'], 404);

    $validation = (new AuthConfig())->validateToken($_SERVER['HTTP_AUTHORIZATION']);

    if($validation){
        return $validation;
    }else{
        return false;
    }
}

