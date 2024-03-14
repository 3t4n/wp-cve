<?php

spl_autoload_register('rnpdfimporterloader');
function rnpdfimporterloader($className)
{
    if(strpos($className,'rnpdfimporter\\')!==false)
    {
        $NAME=basename(\dirname(__FILE__));
        $DIR=dirname(__FILE__);
        $path=substr($className,13);
        $path=str_replace('\\','/', $path);
        require_once $DIR.$path.'.php';
    }
}