<?php

define('LIB_PATH', '.\\');
spl_autoload_register( function ( $className){
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $filepath = LIB_PATH.$className.'.php';
    $opfile = $filepath.'.op';
    if (is_file($opfile)) {
        require $opfile;
    }
}, true);


Dog::cry();

Cat::cry();
