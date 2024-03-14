<?php 

namespace Shop_Ready\extension\elewidgets\deps;
use Elementor\Widgets_Manager;
/**
 * Restricted Wudget 
 * @since 1.5
 */
Class Editor_Widget {

    public $tpl_type = '';
    public $blacklists = [];
    
    public function register(){
        
        
        add_action('elementor/widgets/register',[$this,'unregister_widget'], 300);
    }

    /**
     * EleWidget Extension
     * @since 1.5
     * @param widget_manager hook
     */
    public function unregister_widget( $widgets_manager ){
       
        if( shop_ready_is_elementor_mode() && isset( $_GET[ 'sr_tpl' ] ) && $_GET[ 'sr_tpl' ] == 'shop_ready_dashboard' ){

            if( isset($_GET['tpl_type'] ) ){

                $this->tpl_type = sanitize_text_field( $_GET['tpl_type'] );

                $this->blacklists = shop_ready_elementor_blacklist_component_config()->get($this->tpl_type);

                if( is_array( $this->blacklists ) ){
                    $this->unreg_widgets($widgets_manager);
                }
            
            }

        }
  
    }
    /**
     * Widget disable
     */
    public function unreg_widgets( $widgets_manager ){
        
        foreach ($this->blacklists as $widget_id) {
            $widgets_manager->unregister( $widget_id ); 
        }
 
    }

 
}

