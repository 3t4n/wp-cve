<?php

/**
 * TB Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package TB
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * TB_Init_Blocks.
 *
 * @package TB
 */
class TB_Init_Blocks {

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
        
    }

    /**
     * Enqueue Gutenberg block assets for both frontend + backend.
     *
     * @since 1.0.0
     */
    function block_assets() {
        
    }

// End function editor_assets().

    /**
     * Enqueue Gutenberg block assets for backend editor.
     *
     * @since 1.0.0
     */
    function editor_assets() {

        wp_localize_script(
            'tb-block-editor-js', 'tb_blocks_info', array(
                'blocks' => TB_Config::get_block_attributes(),
                'category' => 'timeline-blocks',
                'ajax_url' => admin_url('admin-ajax.php'),
                'image_sizes' => TB_Helper::get_image_sizes(),
                'post_types' => TB_Helper::get_post_types(),
                'all_taxonomy' => TB_Helper::get_related_taxonomy(),
            )
        );
    }

// End function editor_assets().
}

/**
 *  Prepare if class 'TB_Init_Blocks' exist.
 *  Kicking this off by calling 'get_instance()' method
 */
TB_Init_Blocks::get_instance();
