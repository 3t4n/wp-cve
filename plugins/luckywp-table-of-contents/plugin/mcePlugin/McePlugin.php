<?php

namespace luckywp\tableOfContents\plugin\mcePlugin;

use luckywp\tableOfContents\core\base\BaseObject;
use luckywp\tableOfContents\core\Core;

class McePlugin extends BaseObject
{

    public function init()
    {
        parent::init();
        add_action('admin_init', [$this, 'adminInit'], 20);
    }

    public function adminInit()
    {
        if (current_user_can('edit_posts') || current_user_can('edit_pages')) {
            add_filter('mce_css', [$this, 'mceCss']);
            add_action('enqueue_block_editor_assets', [$this, 'editorCss']);
            add_filter('mce_external_plugins', [$this, 'plugin']);
            add_filter('mce_buttons', [$this, 'button']);
        }
    }

    /**
     * @param string $css
     * @return string
     */
    public function mceCss($css)
    {
        if (!empty($css)) {
            $css .= ',';
        }
        $css .= $this->getUrl() . '/mce.min.css';
        return $css;
    }

    /**
     * CSS для Gutenberg
     */
    public function editorCss()
    {
        wp_enqueue_style('lwptoc-editor-css', Core::$plugin->mcePlugin->getUrl() . '/mce.min.css');
    }

    /**
     * @param array $plugins
     * @return array
     */
    public function plugin($plugins)
    {
        $plugins['lwptoc'] = $this->getUrl() . '/plugin.min.js';
        return $plugins;
    }

    /**
     * @param array $buttons
     * @return array
     */
    public function button($buttons)
    {
        array_push($buttons, 'lwptocButton');
        return $buttons;
    }

    /**
     * @return string
     */
    protected function getUrl()
    {
        return Core::$plugin->url . '/plugin/mcePlugin';
    }
}
