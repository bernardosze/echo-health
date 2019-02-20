<?php
/**
 * Default app ClassLoader.
 * As long as the namespace is correctly declared, it will load automatically the class ad-hoc.
 *
 * Author: Leonardo Otoni
 */

//define("ROOT_PATH", dirname(__FILE__, 2) . "/");

spl_autoload_register(function ($class) {
    $classPath = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    $classPath = ROOT_PATH . $classPath . '.php';

    if (!file_exists($classPath)) {
        throw new Exception("Class '{$class}' not found.");
    } else {
        require_once $classPath;
    }
});

//TODO: Delete legacy code:
/*
spl_autoload_register(function ($className) {
$directories = array("routes/", "controllers/", "database/", "models/", "util/");
foreach ($directories as $directory) {
//see if the file exsists
if (file_exists(ROOT_PATH . $directory . $className . '.php')) {
require_once (ROOT_PATH . $directory . $className . '.php');
//only require the class once, so quit after to save effort (if you got more, then name them something else
return;
}
}
});
 */
