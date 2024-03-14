<?php

namespace Element_Ready\Document;
/*
* Settings_Tabs
* @since 1.0
* Page Settings in Elementor Editor
* 
*/
Class Settings_Tabs{

    public $configs = []; 
    public function get_configs(){
      
        return $this->configs;  
    }
    public function register(){

        add_action('elementor/kit/register_tabs',[ $this, 'register_kit_tabs' ],15,1  );
        add_action('elementor/kit/register_tabs',[ $this, 'register_kit_tabs' ],15,1  );
    }
  
    public function register_kit_tabs($element){

        $tabs = [
			'elements-ready-basic' => \Element_Ready\Document\Global_Settings::class,
		];

		foreach ( $tabs as $id => $class ) {
			$element->register_tab( $id, $class );
		}

    }
    
}