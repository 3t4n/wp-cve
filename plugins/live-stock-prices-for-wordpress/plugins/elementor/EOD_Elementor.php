<?php


class EOD_Elementor
{
    public function __construct()
    {
        /**
         * Controls
         */
        add_action( 'elementor/controls/register', function ( $controls_manager ){
            require_once( __DIR__ . '/controls/Elementor_Smart_Select.php' );
            $controls_manager->register( new Elementor_Smart_Select() );
        } );

        /**
         * Widget groups
         */
        add_action( 'elementor/elements/categories_registered', function( $widgets_manager ){
            $widgets_manager->add_category('eodhd', ['title' => 'EODHD']);
        } );

        /**
         * Widgets
         */
        add_action( 'elementor/widgets/register', function( $widgets_manager ){
            require_once( __DIR__ . '/widgets/Elementor_EOD_Ticker.php' );
            $widgets_manager->register( new Elementor_EOD_Ticker() );

            require_once( __DIR__ . '/widgets/Elementor_EOD_Fundamental.php' );
            $widgets_manager->register( new Elementor_EOD_Fundamental() );

            require_once( __DIR__ . '/widgets/Elementor_EOD_Financial.php' );
            $widgets_manager->register( new Elementor_EOD_Financial() );

            require_once( __DIR__ . '/widgets/Elementor_EOD_News.php' );
            $widgets_manager->register( new Elementor_EOD_News() );

            require_once( __DIR__ . '/widgets/Elementor_EOD_Converter.php' );
            $widgets_manager->register( new Elementor_EOD_Converter() );
        } );

        /**
         * Register scripts and styles
         */
        add_action( 'wp_enqueue_scripts', function() {
            wp_register_script( 'eod_stock-prices-plugin', EOD_URL . 'js/eod-stock-prices.js' );
        } );
    }
}

if ( EOD_ELEMENTOR_INSTALLED ) {
    global $eod_elementary;
    $eod_elementary = new EOD_Elementor();
}