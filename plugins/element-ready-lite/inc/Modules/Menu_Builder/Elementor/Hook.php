<?php

namespace Element_Ready\Modules\Menu_Builder\Elementor;

class Hook {

    public function register() {
      add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
    }
    
    function init_widgets(){
		
        $menu_widget_path = ELEMENT_READY_DIR_PATH."/inc/Modules/Menu_Builder/Widgets";
		$menu_widgets     = element_ready_widgets_class_list($menu_widget_path);
		if( is_array($menu_widgets) ){
			// Register Widgets
			foreach( $menu_widgets as $menu_widget_cls ){
				$cls = '\Element_Ready\Modules\Menu_Builder\Widgets'.'\\'.$menu_widget_cls;
				if( class_exists( $cls ) ):
					\Elementor\Plugin::instance()->widgets_manager->register( new $cls() );
				endif;	
			}
		} 
    }

}   