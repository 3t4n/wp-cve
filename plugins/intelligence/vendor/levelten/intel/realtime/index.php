<?php
namespace LevelTen\Intel\Realtime;
/**
 * @file
 * @author  Tom McCracken <tomm@getlevelten.com>
 * @version 1.0
 * @copyright 2013 LevelTen Ventures
 * 
 * @section LICENSE
 * All rights reserved. Do not use without permission.
 * 
 */

// signals to other scripts that the bootstrap has been loaded
//define('L10I_ROOT', getcwd());
include_once 'main.php';
global $exec_mode;
$exec_mode = 'http';
init($_GET, $_POST);