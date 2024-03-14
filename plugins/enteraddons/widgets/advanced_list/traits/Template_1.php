<?php 
namespace Enteraddons\Widgets\Advanced_List\Traits;
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

trait Template_1 {
    
    public static function markup_style_1() {

        $settings = self::getDisplaySettings();
        ?>
        <div class="ea-list-top-wrap">
            <ul class="ea-list-wrap">
                <?php 
                if( !empty( $settings['list'] ) ) {
                    foreach( $settings['list'] as $list ) {
                        echo '<li class="elementor-repeater-item-'.esc_attr( $list['_id'] ).'">';
                            // Anchor open
                            if( !empty( $list['link']['url'] ) ) {
                                echo self::linkOpen( $list['link'] ); 
                            }
                            // List type
                            if( !empty( $list['list_type'] ) && $list['list_type'] == 'icon' ) {
                                // icon
                                self::icon( $list );
                            } else if( $list['list_type'] == 'number' ) {
                                // Number
                                self::number( $list );
                            }
                            
                            // Text
                            self::title( $list );
                            // Anchor close
                            if( !empty( $list['link']['url'] ) ) {
                                echo self::linkClose();
                            }
                        echo '</li>';
                    }
                }
                ?>
            </ul>
        </div>
        <?php
    }

}