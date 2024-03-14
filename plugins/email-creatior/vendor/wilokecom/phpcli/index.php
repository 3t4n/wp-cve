<?php
ini_set('display_errors', 'ON');
error_reporting(-1);


require __DIR__ . '/vendor/autoload.php';
use Symfony\Component\Console\Application;
use \WilokeCommandLine\SetupPHPUNIT;

$application = new Application();

# add our commands
$application->add(new SetupPHPUNIT());
$application->run();
