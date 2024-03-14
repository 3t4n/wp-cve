<?php

namespace Shop_Ready\extension\generalwidgets\base;

use Shop_Ready\base\elementor\Boot as Shop_Ready_Boot;
use Shop_Ready\extension\generalwidgets\deps\Menu_Item;
use Shop_Ready\extension\generalwidgets\base\Widgets_Settings as Widgets_Settings;
/**
* @since 1.0
* Elementor Extension Boot Base
*/
Abstract Class Extension_Base extends Shop_Ready_Boot{
  

    /****************************
	 * 	INIT WIDGETS
	 ****************************/
	public function init_widgets($widgets_manager) {
		$this->_widgets($widgets_manager);
	}

	public static function get_base(){

		return [
			Widgets_Settings::class
		];
	}

	public static function get_defs(){

		return [
			Menu_Item::class
		];
	}

	/** 
	* Elementor Editor Page | Site Document Settings
	* https://developers.elementor.com/elementor-document-settings/
	* @return array class
	*/
	public static function document(){
        
		$settings = [];
     	return $settings;
	}

    
   /****************************
	 * 	Register Widgets
	 ****************************/
	public function _widgets($widgets_manager){

     	/*
		** Autoload Widget class
		** 
		*/
	
        $modules = shop_ready_widgets_class_dir_list( SHOP_READY_DIR_PATH.'src/extension/generalwidgets/widgets' );
		$components_settings = shop_ready_widgets_config()->all();
		$components_settings = apply_filters( 'shop_ready_widgets_dashboard_options', $components_settings );
	 
		 if( is_array( $modules ) ){
	
			foreach($modules as $module=> $item){
				
				if( is_array( $item )){
                  
                   foreach($item as $widget_file){

						$module_slug_arr_key 	= strtolower($module.'_'.$widget_file);
						$cls = '\Shop_Ready\extension\generalwidgets\widgets\\'.$module.'\\'.$widget_file;
						$active_module		= true;
						
						if(isset($components_settings[$module_slug_arr_key])){
							$active_module = isset( $components_settings[$module_slug_arr_key]['show_in_panel'] ) ? $components_settings[$module_slug_arr_key]['show_in_panel'] : true;
                    	}
                 
						if( $active_module == true && class_exists( $cls ) && get_parent_class($cls) == 'Shop_Ready\extension\generalwidgets\Widget_Base' ):
							$widgets_manager->register( new $cls() );
						endif;
                       
                   }
                }
					
			}
		}
 
	}

    /*******************************
	 * 	ADD CUSTOM CATEGORY
	 *******************************/
	public function add_elementor_category()
	{

		
		$category_list = shop_ready_genwidget_meta_config()->all();
		$categories    = $category_list['categories'];
      
		if( is_array( $categories ) ) {
			
			foreach( $categories as $slug => $item ){
				
				\Elementor\Plugin::instance()->elements_manager->add_category( $slug , array(
					'title' => $item['name'],
					'icon'  => isset($item['name']) ? $item['name']: ' eicon-pro-icon',
				), 1 );
	
			} 

		}
		
	}
    

 
}