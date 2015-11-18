<?php
function autoload($className)
{
<<<<<<< HEAD
    //Писати шлях до кореневої папки проекту
    $rd='';
=======
    //Повний шлях до папки
    $rd='/';
>>>>>>> 78b4624dd05e214fe21943d70270e0e059b706cf
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
<<<<<<< HEAD
    $fileName .= $className . '.php';
=======
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
>>>>>>> 78b4624dd05e214fe21943d70270e0e059b706cf

    require $rd.$fileName;
}
spl_autoload_register('autoload');
?>
