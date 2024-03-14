<?php 
namespace Enteraddons\Widgets\Advanced_Tabs\Traits;
/**
 * Enteraddons Advanced Tab template class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

trait Template_1 {
	
	public static function markup_style_1() {
        $settings = self::getSettings();

        echo '<div class="ea-atab-wrapper">';
           echo  '<div class="ea-atab-tabs '.esc_attr( $settings['active_line_position'] ).'">';  
                $i = 1;
                $contents = [];
                if( !empty( $settings['tabs_list'] ) ) {
                    foreach ( $settings['tabs_list'] as $item ) {
                        $contents[] = $item;
                        $current = ( $i == 1 ) ? 'current':'';
                        echo '<div class="ea-atab-tab-link '.esc_attr( $current ).'" data-tab="tab-'.esc_attr( $item['_id'].'-'.sanitize_title( $item['tab_title'] )).'">';
                            self::icon( $item );
                            self::title( $item );
                        echo '</div>';
                        $i++;
                    }
                }      
           echo  '</div>';

           echo '<div class="ea-atab-content_wrapper">';
                $j = 1;
                if( !empty( $contents ) ) {
                    foreach ( $contents as $item ) {
                        $current = ( $j == 1 ) ? 'current':'';
                        echo  '<div id="tab-'.esc_attr( $item['_id'].'-'.sanitize_title( $item['tab_title'] ) ).'" class="ea-atab-content '.esc_attr( $current ).'">';
                            self::content( $item );
                        echo '</div>'; 
                        $j++;
                    }
                }
            echo   '</div>';
        echo '</div>';
	}

}