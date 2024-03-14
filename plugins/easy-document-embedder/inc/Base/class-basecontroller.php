<?php

/**
 * @package easy-document-embedder
 */
namespace EDE\Inc\Base;

class BaseController
{
    public $plugin_path;
    public $plugin_url;
    public $plugin_name;
    
    function __construct()
    {
        $this->plugin_path = plugin_dir_path( dirname(__FILE__,2) );
        $this->plugin_url = plugin_dir_url( dirname( __FILE__, 2) );
        $this->plugin_name = plugin_basename( dirname(__FILE__,3) ) . '/easy-document-embedder.php';
    }
}

