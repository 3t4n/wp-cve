<?php

namespace luckywp\tableOfContents\plugin\editorBlock;

use luckywp\tableOfContents\core\base\BaseObject;
use luckywp\tableOfContents\core\Core;

class EditorBlock extends BaseObject
{

    public function init()
    {
        if (function_exists('register_block_type')) {
            add_action('init', [$this, 'wpInit']);
        }
    }

    public function wpInit()
    {
        wp_register_script('lwptoc-editorBlock', $this->getUrl() . '/editorBlock.min.js', [
            'wp-blocks',
            'wp-element',
            wp_script_is('wp-block-editor') ? 'wp-block-editor' : 'wp-editor',
            'wp-components',
            'lwptoc_adminMain'
        ], Core::$plugin->version);
        register_block_type('luckywp/tableofcontents', [
            'editor_script' => 'lwptoc-editorBlock',
            'render_callback' => function ($attrs) {
                return Core::$plugin->shortcode->make($attrs);
            },
        ]);
    }

    /**
     * @return string
     */
    protected function getUrl()
    {
        return Core::$plugin->url . '/plugin/editorBlock';
    }
}
