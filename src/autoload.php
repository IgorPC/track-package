<?php

spl_autoload_register(function ($class){
    $class = str_replace('IgorPC\\TrackPackage\\', 'src/', $class);
    $class = str_replace('\\', '/', $class);
    $class .= '.php';

    if(file_exists($class)){
        require_once ($class);
    }else{
        throw new Exception($class . ' not found');
    }
});


