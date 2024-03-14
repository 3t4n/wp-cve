<?php

namespace Wdr\App\Controllers\Admin\Addons;

use Wdr\App\Controllers\Base as BaseController;

if (!defined('ABSPATH')) exit;

abstract class Base extends BaseController
{
    public $name = NULL, $input, $base;
    protected $addon;

    /**
     * Base constructor.
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * render templates
     * @param null $page
     * @return mixed
     */
    abstract function render($page = NULL);
}