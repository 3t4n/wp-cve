<?php
namespace Shop_Ready\extension\elefinder\category;
use Elementor\Core\Common\Modules\Finder\Categories_Manager;
use Shop_Ready\extension\elefinder\category\WR_Settings_Finder_Category;
/**
* WooCommerece Dashboard Finder From Elementor Editor
* @see https://developers.elementor.com/elementor-finder/
* @since 1.0  
*/

Class Dashboard{

    public function register(){
        
        add_action('elementor/finder/register',[ $this,'add_links' ],12);
        
    }

    public function add_links($finder_categories_manager ){
        
        $finder_categories_manager->register( new WR_Settings_Finder_Category() );
        
    }
 

}
