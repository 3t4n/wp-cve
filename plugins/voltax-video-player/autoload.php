<?php
/**
 * PSR-4 Autoloader
 */
require_once(dirname(__FILE__)."/src/MinuteMedia/Autoload.php");
spl_autoload_register(function ($class) {
   MinuteMedia\Autoload::loadClass($class);
});
