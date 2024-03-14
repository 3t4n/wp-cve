<?php

/**
 * @package easy-document-embedder
 */
namespace EDE\Inc\Callbacks;

require_once \dirname(__FILE__,2) . '/Base/class-basecontroller.php';
use EDE\Inc\Base\BaseController;

class ManagerCallbacks extends BaseController
{
    public function adminDashboard()
    {
        return require_once "$this->plugin_path/template/dashboard.php";
    }
}