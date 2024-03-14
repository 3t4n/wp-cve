<?php
namespace Pagup\BetterRobots\Controllers;

use Pagup\BetterRobots\Core\Plugin;

class NotificationController
{
    public function support() 
    {
        return Plugin::view('notices/support');

    }
}