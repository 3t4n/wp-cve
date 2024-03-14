<?php

namespace Fab\Controller;

! defined( 'WPINC ' ) or die;

/**
 * Plugin hooks in a backend
 *
 * @package    Fab
 * @subpackage Fab/Controller
 */

use Fab\View;
use Fab\Wordpress\Hook\Action;
use Fab\Wordpress\Hook\Filter;
use Fab\Wordpress\Hook\Shortcode;

class Elementor extends Base {

    /**
     * Admin constructor
     *
     * @return void
     * @var    object   $plugin     Plugin configuration
     * @pattern prototype
     */
    public function __construct( $plugin ) {
        parent::__construct( $plugin );

        /** Only Execute Hook when Elementor Plugin is Active */
        if ( is_plugin_active( 'elementor/elementor.php' ) ) {
            /** @backend */
            $action = new Action();
            $action->setComponent( $this );
            $action->setHook( 'elementor/init' );
            $action->setCallback( 'elementor_support_post_type_fab' );
            $action->setAcceptedArgs( 0 );
            $action->setMandatory( true );
            $action->setDescription( 'Add support elementor builder post type fab' );
            $action->setFeature( $plugin->getFeatures()['elementor'] );
            $this->hooks[] = $action;

            /** @frontend */
            $action = clone $action;
            $action->setHook( 'widgets_init' );
            $action->setCallback( 'fab_register_widget' );
            $action->setMandatory( true );
            $action->setDescription( 'Setup elementor init widget' );
            $action->setFeature( $plugin->getFeatures()['elementor'] );
            $this->hooks[] = $action;

            /** @frontend */
            $action = clone $action;
            $action->setHook( 'wp_footer' );
            $action->setCallback( 'fab_display_widget' );
            $action->setDescription( 'Display the elementor modal' );
            $action->setFeature( $plugin->getFeatures()['elementor'] );
            $this->hooks[] = $action;

            /** @frontend */
            $action = clone $action;
            $action->setHook( 'wp_footer' );
            $action->setCallback( 'fab_display_widget' );
            $action->setDescription( 'Display the elementor modal' );
            $action->setFeature( $plugin->getFeatures()['elementor'] );
            $this->hooks[] = $action;

            /** @backend */
            $filter = new Filter();
            $filter->setComponent( $this );
            $filter->setHook( 'post_class' );
            $filter->setCallback( 'post_class_fab' );
            $filter->setAcceptedArgs( 3 );
            $filter->setMandatory( true );
            $filter->setDescription( 'Make elementor editor full width - post class' );
            $filter->setFeature( $plugin->getFeatures()['elementor'] );
            $this->hooks[] = $filter;

            /** @frontend */
            $shortcode = new Shortcode();
            $shortcode->setComponent( $this );
            $shortcode->setHook( 'fab_elementor' );
            $shortcode->setCallback( 'fab_elementor' );
            $shortcode->setAcceptedArgs( 1 );
            $shortcode->setMandatory( false );
            $shortcode->setDescription( 'Elementor Modal Init' );
            $shortcode->setFeature( $plugin->getFeatures()['elementor'] );
            $this->hooks[] = $shortcode;
        }
    }

    /** Add support elementor builder fab */
    public function elementor_support_post_type_fab() {
        add_post_type_support( 'fab', 'elementor' );
    }

    /** Make elementor editor full width */
    public function post_class_fab( $classes, $class, $ID ) {
        global $post;
        if ( isset( $post->post_type ) && $post->post_type == 'fab' ) {
            $classes[] = 'fab-fullwidth';
        }
        return $classes;
    }

    /** Register elementor widgets init */
    public function fab_register_widget() {
        /** Register Sidebar */
        register_sidebar(
            array(
                'name'          => __( 'FAB Elementor Modal' ),
                'id'            => 'fab-widget-elementor',
            )
        );
    }

    /** Display the elementor modal */
    public function fab_display_widget(){
        dynamic_sidebar( 'fab-widget-elementor' );
    }

    /**
     * [fab_elementor] Initiate elementor in page
     */
    public function fab_elementor(){
        global $post;

        /** Ignore in Pages */
        if ( is_single() && isset( $post->post_type ) && $post->post_type === 'fab' ) {
            return;
        }

        /** Grab Data */
        $Fab            = $this->Plugin->getModels()['Fab'];
        $fab_to_display = $Fab->get_lists_of_fab( array(
            'validateLocation' => true,
            'builder' => array('elementor')
        ) )['items'];

        /** Show FAB Button */
        if ( ! is_admin() && $fab_to_display ) {
            View::RenderStatic('Frontend.modal',
                compact( 'post', 'fab_to_display' )
            );
        }
    }

}
