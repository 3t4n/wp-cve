<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!!' );
if ( !class_exists( 'STUL_Init' ) ) {

    class STUL_Init {

        function __construct() {
            add_action( 'init', array( $this, 'init_tasks' ) ); // executed on init hook
        }

        function init_tasks() {
            load_plugin_textdomain( 'subscribe-to-unlock-lite', false, STUL_LANGUAUGE_PATH );  //loading of plugin's translation text domain

            /**
             * Fires when Init hook is fired through plugin
             *
             * @since 1.0.0
             */
            do_action( 'stul_init' );
        }

    }

    new STUL_Init();
}