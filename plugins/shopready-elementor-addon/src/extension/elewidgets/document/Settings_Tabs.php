<?php

namespace Shop_Ready\extension\elewidgets\document;

use Shop_Ready\base\elementor\Document_Settings;
use \Elementor\Controls_Manage;
use Shop_Ready\extension\elewidgets\document\Global_Settings;
/*
* Settings_Tabs
* @since 1.0
* Page Settings in Elementor Editor
* usege in login register widgets
*/
Class Settings_Tabs extends Document_Settings{
    
    public function register(){
        add_action('elementor/kit/register_tabs',[ $this, 'register_kit_tabs' ],15,1  );
    }
 
    public function register_kit_tabs($element){

        $tabs = [
			'woo-ready-basic' => \Shop_Ready\extension\elewidgets\document\Global_Settings::class,
			'shop-ready-common' => \Shop_Ready\extension\elewidgets\document\Common_Settings::class,
		];

		foreach ( $tabs as $id => $class ) {
			$element->register_tab( $id, $class );
		}

    }
    
}