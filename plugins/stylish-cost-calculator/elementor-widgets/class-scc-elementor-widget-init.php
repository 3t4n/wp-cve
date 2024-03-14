<?php

namespace DF_SCC\ElementorIntegration;

use Elementor\Plugin as ElementorPlugin;

class SCC_Elementor_Widget_Init {

    /**
	 * Indicate if current integration is allowed to load.
	 *
	 * @return bool
	 */
	public function allow_load() {
		return (bool) did_action( 'elementor/loaded' );
	}

    public function load() {
        $this->hooks();
    }
    
    public function hooks() {
        // Skip if Elementor is not available.
		if ( ! class_exists( '\Elementor\Plugin' ) ) {
			return;
		}
        
        add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
    }

    public function register_widgets() {
        require SCC_DIR . '/elementor-widgets/class-scc-elementor-widget.php';
        ElementorPlugin::instance()->widgets_manager->register( new SCC_Elementor_Widget() );
        // \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new SCC_Elementor_Widget_Calculator() );
    }
}