<?php
/**
 * A class that handles loading custom modules and custom
 * fields if the builder is installed and activated.
 */
if( !class_exists( "Xpro_Slider_Lite_for_wp_Init" ) ) {
    class Xpro_Slider_Lite_for_wp_Init
    {

        /**
         * Initializes the class once all plugins have loaded.
         */
        static public function init()
        {

            self::includes();

            add_action('init', __CLASS__ . '::load_modules');
        }

        /**
         * Loads our custom modules.
         */
        public static function load_modules()
        {
            require_once XPRO_SLIDER_FOR_BB_LITE_DIR . 'modules/xpro-multi-layer-slider/xpro-multi-layer-slider.php';
        }

        public static function includes()
        {
            require_once XPRO_SLIDER_FOR_BB_LITE_DIR . 'classes/class-xpro-plugins-helper.php';
        }

    }
}
Xpro_Slider_Lite_for_wp_Init::init();
