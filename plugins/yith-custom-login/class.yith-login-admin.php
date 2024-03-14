<?php
/**
 * Main admin class
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH Custom Login
 * @version 1.0.2
 */

if ( !defined( 'YITH_LOGIN' ) ) { exit; } // Exit if accessed directly

if( !class_exists( 'YITH_Login_Admin' ) ) {
    /**
     * YITH Custom Login Admin
     *
     * @since 0.9.0
     */
    class YITH_Login_Admin {
        /**
         * Plugin version
         *
         * @var string
         * @since 0.9.0
         */
        public $version;

        /**
         * Parameters for add_submenu_page
         *
         * @var array
         * @access public
         * @since 0.9.0
         */
        public $submenu = array();

        /**
         * Initial Options definition:
         *
         * @var array
         * @access public
         * @since 0.9.0
         */
        public $options = array();

        /**
         * Panel instance
         *
         * @var YITH_Panel
         * @since 0.9.0
         */
        public $panel;

        /**
         * Constructor
         *
         * @return YITH_Login_Admin
         * @since 0.9.0
         */
        public function __construct( $version ) {
            global $yith_login_options;

            $this->version = $version;
            $this->submenu = apply_filters( 'yith_login_submenu', array(
                'themes.php',
                __('YITH Custom Login', 'yith-custom-login'),
                __('Login Screen', 'yith-custom-login'),
                'administrator',
                'yith-custom-login'
            ) );
            $this->options = apply_filters( 'yith_login_options', $yith_login_options );

            add_action( 'init', array( $this, 'init_panel' ) );
            add_action( 'init', array( $this, 'default_options' ) );

            return $this;
        }

        /**
         * Default options
         *
         * Sets up the default options used on the settings page
         *
         * @access public
         * @return void
         * @since 0.9.0
         */
        public function default_options() {
            foreach ($this->options as $tab) {
                foreach( $tab['sections'] as $section ) {
                    foreach ( $section['fields'] as $id => $value ) {
                        if ( isset( $value['std'] ) && isset( $id ) ) {
                            add_option($id, $value['std']);
                        }
                    }
                }
            }
        }

        /**
         * Init the panel
         *
         * @return void
         * @since 0.9.0
         */
        public function init_panel() {
            $this->panel = new YITH_Panel(
                                    $this->submenu,
                                    $this->options,
                                    array(),
                                    'yith-custom-login-group',
                                    'yith-custom-login'
            );
        }
    }
}