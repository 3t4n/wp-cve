<?php


spl_autoload_register('rednaoformpdfbuilder');
function rednaoformpdfbuilder($className)
{
    if(strpos($className,'rednaoformpdfbuilder\\')!==false)
    {
        $NAME=basename(\dirname(__FILE__));
        $DIR=dirname(__FILE__);
        $path=substr($className,20);
        $path=str_replace('\\','/', $path);
        require_once $DIR.$path.'.php';
    }
}