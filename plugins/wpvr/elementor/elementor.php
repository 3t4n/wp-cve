<?php

namespace WpvrElement;

use WpvrElement\Elements\Wpvr\Wpvr_Widget;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Plugin Class
 *
 * Register new elementor widget.
 *
 * @since 1.0.0
 */
class WpvrElementor {

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function __construct() {
        $this->add_actions();
    }

    /**
     * Add Actions
     *
     * @since 1.0.0
     *
     * @access private
     */
    private function add_actions() {
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'on_widgets_registered' ] );

        //font css per icone
        add_action( 'elementor/editor/before_enqueue_scripts', function () {
            wp_enqueue_style( 'Wpvr-elementor',plugins_url( '../assets/admin.css', __FILE__ ) );
        } );

        add_action( 'elementor/frontend/after_register_scripts', function() {
            wp_register_script( 'Wpvr-elementor', 'script path', [ 'jquery' ], false, true );
        } );

        
    }

    /**
     * On Widgets Registered
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function on_widgets_registered() {
        $this->includes();
        $this->register_widget();
    }

    /**
     * Includes
     *
     * @since 1.0.0
     *
     * @access private
     */
    private function includes() {
        /*
         * Return Wpvr widget file path
         * i.e. PATH.'elementor/elements/Wpvr-element.php'
         */
        require __DIR__.'/elements/Wpvr-widget.php';
    }

    /**
     * Register Widget
     *
     * @since 1.0.0
     *
     * @access private
     */
    private function register_widget() {
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Wpvr_Widget() );
    }
}

new WpvrElementor();
