<?php

defined('ABSPATH') or die('No script kiddies please!!');
if (!class_exists('WPSF_Init')) {

    class WPSF_Init {

        function __construct() {
            add_action('init', array($this, 'init_tasks')); // executed on init hook
        }

        function init_tasks() {
            load_plugin_textdomain(WPSF_TD, false, WPSF_LANGUAUGE_PATH);  //loading of plugin's translation text domain

            /**
             * Fires when Init hook is fired through plugin
             *
             * @since 1.0.0
             */
            do_action('wpsf_init');
        }

    }

    new WPSF_Init();
}