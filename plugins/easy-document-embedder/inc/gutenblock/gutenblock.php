<?php

/**
 * @package easy-document-embedder
 */
namespace EDE\Inc\Gutenblock;

require_once \dirname(__FILE__,2) . '/Base/class-basecontroller.php';
use EDE\Inc\Base\BaseController;


class GutenBLock extends BaseController
{
    public function ede_register()
    {
        add_action( 'init', array($this,'ede_gutenberg_blocks') );
    }

    public function ede_gutenberg_blocks()
    {
        wp_register_script( 'ede_gutenblock_editor_js', $this->plugin_url . 'inc/gutenblock/build/index.js', array(
            'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-editor',
			'wp-components',
			'wp-compose',
			'wp-data',
			'wp-autop') );

        register_block_type( 'ede/ede-custom-block', array(
            'editor_script' => 'ede_gutenblock_editor_js'
        ) );
    }
}
