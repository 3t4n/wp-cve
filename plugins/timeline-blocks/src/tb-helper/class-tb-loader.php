<?php

/**
 * TB Loader.
 *
 * @package TB
 */
if (!class_exists('TB_Loader')) {

    /**
     * Class TB_Loader.
     */
    final class TB_Loader {

        /**
         * Member Variable
         *
         * @var instance
         */
        private static $instance;

        /**
         *  Initiator
         */
        public static function get_instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Constructor
         */
        public function __construct() {

            $this->loader();

            add_action('plugins_loaded', array($this, 'load_plugin'));
        }

        /**
         * Loads Other files.
         *
         * @since 1.0.0
         *
         * @return void
         */
        public function loader() {
            require( TB_DIR . 'src/tb-helper/class-tb-helper.php' );
            require( TB_DIR . 'src/tb-helper/class-tb-core-plugin.php' );
        }

    }

    /**
     *  Prepare if class 'TB_Loader' exist.
     *  Kicking this off by calling 'get_instance()' method
     */
    TB_Loader::get_instance();
}
