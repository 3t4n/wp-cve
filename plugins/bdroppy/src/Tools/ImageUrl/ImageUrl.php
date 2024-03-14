<?php
namespace BDroppy\Tools\ImageUrl;

use BDroppy\Init\Core;
use BDroppy\Tools\ImageUrl\Includes\Admin;
use BDroppy\Tools\ImageUrl\Includes\Common;

if ( ! defined( 'ABSPATH' ) ) exit;



class  ImageUrl
{

    public $core;
    public $loader;
    public $config;

    public function __construct(Core $core)
    {
        $this->core = $core;
        $this->loader = $core->getLoader();
        $this->config = $core->getConfig();
        if($this->checkActive()){
            $this->admin = new Admin($core);
            $this->common = new Common($core);
        }

    }

    public function checkActive()
    {

        if ($this->config->catalog->get('add-image-url-tools')) return false;
        return true;
    }












}