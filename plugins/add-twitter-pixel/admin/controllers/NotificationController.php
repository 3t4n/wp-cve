<?php
namespace Pagup\Twitter\Controllers;

use Pagup\Twitter\Core\Plugin;

class NotificationController
{
    public function support() 
    {

        $text_domain = Plugin::domain();

        return Plugin::view('notices/support', compact('text_domain'));
    }
}