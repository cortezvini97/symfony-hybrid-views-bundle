<?php

function autoload(string $dir, $services_array) {
    global $services;
    $services = $services_array;
    $files = scandir($dir);
    $files = array_slice($files, 2);
    foreach ($files as $file)
    {
        $file = $dir.DIRECTORY_SEPARATOR.$file;
        require_once $file;
    }
}

