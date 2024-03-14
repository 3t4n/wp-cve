<?php

/**
 * @package easy-document-embedder
 */
namespace EDE\Inc\Admin;

require_once \dirname(__FILE__,2) . '/inc/Base/class-basecontroller.php';
require_once \dirname(__FILE__,2) . '/inc/Api/class-settings-api.php';
require_once \dirname(__FILE__,2) . '/inc/Callbacks/ManagerCallbacks.php';

use EDE\Inc\Api\SettingsApi;
use EDE\Inc\Base\BaseController;
use EDE\Inc\Callbacks\ManagerCallbacks;

class Admin extends BaseController
{
    public $settings;
    public $subpages = array();
    protected $callbacks_mngr;

    public function ede_register()
    {
        $this->settings = new SettingsApi();
        $this->callbacks_mngr = new ManagerCallbacks();
        $this->setSubPages();
        $this->settings->addSubPages($this->subpages)->ede_register();
    }

    public function setSubPages()
    {
        $this->subpages = [
            [
                "parent_slug"   =>  "edit.php?post_type=ede_embedder",
                "page_title"    =>  "About Us",
                "menu_title"    =>  "About Us",
                "capability"    =>  "manage_options",
                "menu_slug"     =>  "easy_embedder_about",
                "callback"      =>  array($this->callbacks_mngr,'adminDashboard')
            ],
        ];
    }
}