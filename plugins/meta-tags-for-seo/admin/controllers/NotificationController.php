<?php
namespace Pagup\MetaTags\Controllers;

use Pagup\MetaTags\Core\Plugin;

class NotificationController
{
    public function support() 
    {
        return Plugin::view('notices/support');
    }
}