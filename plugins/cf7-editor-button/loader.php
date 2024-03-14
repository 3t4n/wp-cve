<?php
defined( 'ABSPATH' ) or die( 'Access forbidden!' );

if ( ! function_exists( 'ari_cf7_button_init' ) ) {
    function ari_cf7_button_init() {
        if ( defined( 'ARICF7BUTTON_INITED' ) )
            return ;

        define( 'ARICF7BUTTON_INITED', true );

        require_once ARICF7BUTTON_PATH . 'includes/defines.php';
        require_once ARICF7BUTTON_PATH . 'libraries/arisoft/loader.php';

        Ari_Loader::register_prefix( 'Ari_Cf7_Button', ARICF7BUTTON_PATH . 'includes' );

        $plugin = new \Ari_Cf7_Button\Plugin(
            array(
                'class_prefix' => 'Ari_Cf7_Button',

                'version' => ARICF7BUTTON_VERSION,

                'path' => ARICF7BUTTON_PATH,

                'url' => ARICF7BUTTON_URL,

                'assets_url' => ARICF7BUTTON_ASSETS_URL,

                'view_path' => ARICF7BUTTON_PATH . 'includes/views/',

                'main_file' => __FILE__,

                'page_prefix' => 'contact-form-7-editor-button',
            )
        );
        $plugin->init();
    }
}
