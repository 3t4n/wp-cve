<?php

if( ! defined('ABSPATH') ) die('No Access to this page');

include_once('BingMapPro_ShortCodeLoader.php');

abstract class BingMapPro_ShortCodeScriptLoader extends BingMapPro_ShortCodeLoader {

    var $doAddScript;

    public function register($shortcodeName) {
        $this->registerShortcodeToFunction($shortcodeName, 'handleShortcodeWrapper');

 
        add_action('wp_footer', array($this, 'addScriptWrapper'));
    }

    public function handleShortcodeWrapper($atts) {
        // Flag that we need to add the script
        $this->doAddScript = true;
        return $this->handleShortcode($atts);
    }


    public function addScriptWrapper() {
        // Only add the script if the shortcode was actually called
        if ($this->doAddScript) {
            $this->addScript();
        }
    }

    public abstract function addScript();

}