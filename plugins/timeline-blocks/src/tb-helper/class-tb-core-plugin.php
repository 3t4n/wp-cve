<?php

/**
 * TB Core Plugin.
 *
 * @package TB
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * TB_Core_Plugin.
 *
 * @package TB
 */
class TB_Core_Plugin {

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

        $this->includes();
    }

    /**
     * Includes.
     *
     * @since 1.0.0
     */
    private function includes() {

        // require( TB_DIR . 'lib/notices/class-astra-notices.php' );
        require( TB_DIR . 'src/tb-helper/class-tb-admin.php' );
        require( TB_DIR . 'src/tb-helper/class-tb-init-blocks.php' );
    }

}

/**
 *  Prepare if class 'TB_Core_Plugin' exist.
 *  Kicking this off by calling 'get_instance()' method
 */
TB_Core_Plugin::get_instance();
