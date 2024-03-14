<?php

namespace Element_Ready\Widget_Controls\Skins;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Register {

    public function register() {
  
		add_action( 'elementor/widget/tabs/skins_init', [ $this , 'tab_icon_widget' ] );
	}
     /**
     * Add dark skin for "Google Maps" widget.
     *
     * @since 1.0.0
     * @param \Elementor\Widget_Base $widget The widget instance.
     */
    function tab_icon_widget( $widget ) {
       
        $widget->add_skin( new Tabs_Widget( $widget ) );
     
    }
}