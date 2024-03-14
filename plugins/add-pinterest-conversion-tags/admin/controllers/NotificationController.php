<?php
namespace Pagup\Pctag\Controllers;

use \Pagup\Pctag\Core\Plugin;

class NotificationController
{
    public function support() 
    {
        return Plugin::view('notices/support');

    }
}