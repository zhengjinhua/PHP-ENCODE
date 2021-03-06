<?php
/**
 *注册自动加载
 */
define('LIB_PATH', '/var/www/html/');
spl_autoload_register( function ( $className){
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $filepath = LIB_PATH.$className.'.php';
    $binpath = $filepath.'.bin';
    if (is_file($binpath)) {
        apc_bin_loadfile($binpath);
        require $filepath;
    }
}, true);