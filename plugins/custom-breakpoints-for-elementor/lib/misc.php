<?php

namespace MasterCustomBreakPoint;

use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;
use Elementor\Plugin as Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Misc {

    public function __construct() {
        
        add_action( 'elementor/editor/wp_head', [$this, 'print_custom_scripts'] );

        add_action( 'init', [$this, 'update_jltma_mcb_breakpoints'], 0 );

    }

    public function update_jltma_mcb_breakpoints() {

        if ( get_option( 'jltma_mcb_3_2_4_data_updated' ) == 'true' ) return;

        $breakpoints = Breakpoints_Manager::get_additional_breakpoints();

        $breakpoints = array_map( function( $breakpoint ) {
            return [
                'label' => $breakpoint['name'],
                'default_value' => $breakpoint['input2'],
                'direction' => 'max'
            ];
        }, $breakpoints );

        update_option( 'jltma_mcb', $breakpoints );
        update_option( 'jltma_mcb_3_2_4_data_updated', 'true' );

    }

    public function print_custom_scripts() {

        $this->print_custom_css();
        $this->print_custom_js();

    }

    public function print_custom_css() {

        $style = '';

        $custom_devices = array_keys( Breakpoints_Manager::get_additional_breakpoints() );

        $style .= $this->get_panel_responsive_fields_css( $custom_devices );
        $style .= $this->get_panel_responsive_fields_devices_css( $custom_devices );
        $style .= $this->get_panel_responsive_fields_active_device_css( $custom_devices );

        printf( '<style>%s</style>', $style );

    }

    public function get_panel_responsive_fields_css( $custom_devices ) {

        $css_selector = [];

		foreach ( $custom_devices as $custom_device ) {
            $css_selector[] = sprintf( 'body:not(.elementor-device-%1$s) .elementor-control.elementor-control-responsive-%1$s', $custom_device );
        }

        $css_selector = implode( ',', $css_selector );

        if ( $css_selector ) {
            return $css_selector .= '{
                display: none;
            }';
        }

        return '';

    }

    public function get_panel_responsive_fields_devices_css( $custom_devices ) {

        $css_selector = [];

		foreach ( $custom_devices as $custom_device ) {
            $css_selector[] = sprintf( '.elementor-device-%1$s .elementor-responsive-switcher.elementor-responsive-switcher-%1$s', $custom_device );
        }

        $css_selector = implode( ',', $css_selector );

        if ( $css_selector ) {
            return $css_selector .= '{
                height: 2em;
                -webkit-transform: scale(1);
                -ms-transform: scale(1);
                transform: scale(1);
                opacity: 1;
            }';
        }

        return '';

    }

    public function get_panel_responsive_fields_active_device_css( $custom_devices ) {

        $css_selector = [];

		foreach ( $custom_devices as $custom_device ) {
            $css_selector[] = sprintf( '.elementor-device-%1$s .elementor-responsive-switchers-open:not(:hover) .elementor-responsive-switcher.elementor-responsive-switcher-%1$s', $custom_device );
        }

        $css_selector = implode( ',', $css_selector );

        if ( $css_selector ) {
            return $css_selector .= '{ 
                color: #71d7f7;
            }';
        }

        return '';

    }

    public function print_custom_js() {

        $script = '';

        $custom_devices = Breakpoints_Manager::get_additional_breakpoints();
        $active_breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();

        $script .= $this->get_panel_responsive_fields_devices_js( $custom_devices, array_keys($active_breakpoints) );

        printf( '<script>jQuery(function($) { %s });</script>', $script );

    }

    public function get_panel_responsive_fields_devices_js( $custom_devices, $active_breakpoints ) {

        ob_start();
        
        ?>

        var custom_devices = <?php echo json_encode($custom_devices); ?>;
        var active_breakpoints = <?php echo json_encode($active_breakpoints); ?>;

        function get_next_device_name( currentMode ) {
            var currentIndex = active_breakpoints.indexOf(currentMode);
            return active_breakpoints.find(function( br, i ) {
                return ! (br in custom_devices) && i > currentIndex;
            });
        }

        elementor.channels.deviceMode.on( 'change', function() {

            var currentMode = elementor.channels.deviceMode.request('currentMode');

            if ( currentMode in custom_devices ) {
                var next_device = get_next_device_name( currentMode ) || 'tablet';
                var resizeOption = elementor.getBreakpointResizeOptions( next_device );
                document.getElementById('elementor-preview-responsive-wrapper').style.setProperty('--e-editor-preview-height', resizeOption.height + 'px');
            }
            
        });

        <?php

        return ob_get_clean();

    }

}

new Misc();