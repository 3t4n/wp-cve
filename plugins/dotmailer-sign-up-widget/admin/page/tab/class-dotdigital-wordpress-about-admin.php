<?php

/**
 * About tab
 *
 * This file is used to display the about tab
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Admin\Page\Tab;

use Dotdigital_WordPress\Admin\Page\Dotdigital_WordPress_Page_Tab_Interface;
use Dotdigital_WordPress\Includes\Setting\Dotdigital_WordPress_Config;
class Dotdigital_WordPress_About_Admin implements Dotdigital_WordPress_Page_Tab_Interface
{
    /**
     * @inheritDoc
     */
    public function render()
    {
        require_once DOTDIGITAL_WORDPRESS_PLUGIN_PATH . 'admin/view/tabs/dotdigital-wordpress-about-admin.php';
    }
    /**
     * @inheritDoc
     */
    public function get_slug() : string
    {
        return Dotdigital_WordPress_Config::DEFAULT_TAB;
    }
    /**
     * @inheritDoc
     */
    public function get_url_slug() : string
    {
        return Dotdigital_WordPress_Config::DEFAULT_TAB;
    }
    /**
     * @inheritDoc
     */
    public function get_title() : string
    {
        return __('About');
    }
    /**
     * @inheritDoc
     */
    public function initialise()
    {
    }
}
