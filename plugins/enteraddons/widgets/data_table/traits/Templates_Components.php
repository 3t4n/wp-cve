<?php 
namespace Enteraddons\Widgets\Data_Table\Traits;
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
    
    // Header 
    public static function header( $item ) {
        $altText     = \Elementor\Control_Media::get_image_alt( $item['dp_heading_image'] );

        if( !empty( $item['dp_heading_text'] || $item['dp_heading_icon']  ) || $item['dp_heading_image'] )  {
            echo "<th><div class='ea-heading-content'>";
                if ( 'yes' === $item['show_icon'] ) {
                    if( $item['heading_icon_type'] != 'img' ) {
                        echo \Enteraddons\Classes\Helper::getElementorIcon( $item['dp_heading_icon'] );
                    }
                    else {
                        echo '<img src="'.esc_url( $item['dp_heading_image']['url'] ).'" class="ea-heading-image" alt="'.esc_attr( $altText ).'">';
                    }
                }   
                echo  esc_html( $item['dp_heading_text']);
            echo "</div></th>"; 
        }
    }

    // Table Content 
    public static function Content( $item ) {
        $altText     = \Elementor\Control_Media::get_image_alt( $item['tbody_image'] );

        if( !empty( $item['content_title'] || $item['tbody_icon']  ) || $item['tbody_image'] ) {
            echo "<td><div class='ea-td-content'>";
                if ( 'yes' === $item['ea_show_icon'] ) {
                    if( $item['icon_type'] != 'img' ) {
                        echo \Enteraddons\Classes\Helper::getElementorIcon( $item['tbody_icon'] );
                    } else {
                        echo '<img src="'.esc_url( $item['tbody_image']['url'] ).'" class="ea-table-image" alt="'.esc_attr( $altText ).'">';
                    }
                }
                echo esc_html( $item['content_title'] );
            echo "</div></td>";
        }
    }

    // Button 
    public static function button( $item ) {
        
        if( !empty( $item['btn_title'] && $item['btn_links']) ) {
            echo "<td><div class='ea-td-content'><a href='". esc_url( $item['btn_links']['url'] ) ."' class='btn btn-custom-reverse'>".esc_html( $item['btn_title'] )."</a></div></td>";
        }
    }
}