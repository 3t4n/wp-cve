<?php

/**
 * @package easy-document-embedder
 */
namespace EDE\Inc\Base;

require_once dirname(__FILE__,2) . '/Callbacks/PostMetaCallback.php';
require_once dirname(__FILE__,2) . '/Api/class-settings-api.php';
use EDE\Inc\Callbacks\PostMetaCallback;
use EDE\Inc\Api\SettingsApi;

class EDEMetabox
{
    public $postCallbacks;
    public $settings;
    public $add_metabox = array();

    public function ede_register()
    {
        $this->postCallbacks = new PostMetaCallback();
        $this->settings = new SettingsApi();
        $this->ede_metabox();
        $this->settings->addMetabox($this->add_metabox)->ede_register();
    }

    /**
     * @method ede_metabox
     * @param null
     * create custom meta box 
     */
    public function ede_metabox()
    {
        $this->add_metabox = [
            [
                'id' => 'ede_document_',
                'title' => __( 'Select Files', 'easy-document-embedder' ),
                'callback' => array($this->postCallbacks,'edeMetaboxForm'),
                'screen' => 'ede_embedder',
                'context' => 'normal',
                'priority' => 'high'
            ],
            
            [
                'id' => 'ede_document_settings_',
                'title' => __( 'Setting ( optional )', 'easy-document-embedder' ),
                'callback' => array($this->postCallbacks,'edeMetaboxSettings'),
                'screen' => 'ede_embedder',
                'context' => 'normal',
                'priority' => ''
            ],

        ];
    }
}