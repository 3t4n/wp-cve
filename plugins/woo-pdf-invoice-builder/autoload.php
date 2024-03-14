<?php


spl_autoload_register('RedNaoWCInvLoader');
function RedNaoWCInvLoader($className)
{
    if(strpos($className,'rnwcinv\\')!==false)
    {
        $DIR=dirname(__FILE__);
        $path=substr($className,7);
        $path=str_replace('\\','/', $path);
        include_once $DIR.$path.'.php';
    }
}