<?php
namespace LaStudioKitThemeBuilder\Modules\NestedElements;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Module extends \Elementor\Core\Base\Module {

    public static function is_active() {
        return lastudio_kit()->elementor()->experiments->is_feature_active('container');
    }

    public function get_name() {
        return 'nested-elements';
    }

    public function before_enqueue_scripts(){
        wp_register_script( $this->get_name(), lastudio_kit()->plugin_url('includes/modules/nested-elements/assets/js/nested-elements.min.js'), [
            'elementor-common',
        ], ELEMENTOR_VERSION, true );

        wp_enqueue_script( 'lakit-nested-tabs-editor', lastudio_kit()->plugin_url('includes/modules/nested-elements/assets/js/nested-tabs.min.js'), [
            $this->get_name()
        ], ELEMENTOR_VERSION, true );
    }

    public function __construct()
    {
        parent::__construct();

        add_action( 'elementor/controls/register', function ( $controls_manager ) {
            $controls_manager->register( new Controls\Control_Nested_Repeater() );
        } );

        add_action( 'elementor/widgets/register', function ($widgets_manager){
            $widgets_manager->register( new Widgets\NestedTabs() );
        } );

        add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'before_enqueue_scripts' ] );
    }
}