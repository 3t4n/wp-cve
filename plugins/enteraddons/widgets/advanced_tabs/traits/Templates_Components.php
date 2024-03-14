<?php 
namespace Enteraddons\Widgets\Advanced_Tabs\Traits;
/**
 * Enteraddons template class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

trait Templates_Components {
	
    // Set Settings options
    protected static function getSettings() {
        return self::getDisplaySettings();
    }

     // Title
     protected static function title( $tab ) {

        if( !empty( $tab['tab_title'] ) ) {
            echo '<span class="ea-atab-title">'.esc_html( $tab['tab_title'] ).'</span>';
        }
    }

    //Icon
    protected static function icon( $tab ) {     
        echo '<span class="ea-atab-icon">'.\Enteraddons\Classes\Helper::getElementorIcon( $tab['tab_icon'] ).'</span>';
    }

     // Content
     protected static function content( $tab ) {
        
        if( $tab['tab_content_type'] != 'template' ) {
            if( !empty( $tab['content'] ) ) {
                echo wpautop( $tab['content'] );
            }
        } else {
            if( !empty( $tab['template_id'] ) ) {
                echo \Enteraddons\Classes\Helper::elementor_content_display( absint( $tab['template_id'] ) );
            }
        }
    }
    

    
}