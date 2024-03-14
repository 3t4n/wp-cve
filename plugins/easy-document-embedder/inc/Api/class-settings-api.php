<?php

/**
 * @package easy-document-embedder
 */
namespace EDE\Inc\Api;

require_once \dirname(__FILE__,2) . '/Base/class-basecontroller.php';
require_once \dirname(__FILE__,2) . '/Callbacks/PostMetaCallback.php';

use EDE\Inc\Base\BaseController;
use EDE\Inc\Callbacks\PostMetaCallback;

class SettingsApi extends BaseController
{
    public $admin_subpages = array();
    public $add_metabox = array();
    protected $callback;
    // initalize method of each class
    public function ede_register()
    {
        $this->callback = new PostMetaCallback();
        if (!empty($this->admin_subpages)) {
            add_action( 'admin_menu', array($this,'createAdminMenu') );
        }
        
        if( !empty( $this->add_metabox ) ){
            add_action( 'add_meta_boxes', array($this,'CreateMetaBox') );
            $this->callback->save_meta();
        }
    }

    public function addSubPages(array $subpage)
    {
        $this->admin_subpages = $subpage;
        return $this;
    }

    public function addMetabox(array $metafield)
    {
        $this->add_metabox = $metafield;
        return $this;
    }

    /**
     * @method CreateAdminMenu
     * create sub menu
     */
    public function createAdminMenu()
    {
        foreach ( $this->admin_subpages as $page ) {
			add_submenu_page( $page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'] );
		}
    }

    /**
     * @method CreateMetaBox
     * create metabox
     */
    public function CreateMetaBox()
    {
        foreach ($this->add_metabox as $metabox) {
            add_meta_box( $metabox['id'], $metabox['title'], $metabox['callback'], $metabox['screen'], $metabox['context'], $metabox['priority'] );
        }
        
    }


}