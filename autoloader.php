<?php
spl_autoload_register("class_loader");
function class_loader($class_name) 
{
    if(file_exists('classes/'.$class_name . '.php'))
    {
        require_once 'classes/'.$class_name . '.php';
    }
    else
    {
        if(ENVIROMENT == 'development')
        {
            echo "<pre>";
            var_dump(debug_backtrace());
            die("The class name ".$class_name." does not exist");
        }
        else
        {
            die();
        }
    }
}