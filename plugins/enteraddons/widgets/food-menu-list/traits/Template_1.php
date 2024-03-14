<?php 
namespace Enteraddons\Widgets\Food_Menu_List\Traits;
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
        <div class="ea-food-menu-list">
            <div class="ea-menu-list-items">
                <?php 
                if( !empty( $settings['menu_list'] ) ) {
                    foreach( $settings['menu_list'] as $list ) {
                        $is_link = $list['is_link'] == 'yes' ? true : false;
                        if( $is_link ) {
                            self::linkOpen( $list );
                        }
                        echo '<span class="menu-list-item" data-page="'.esc_attr( $list['price'] ).'">'.esc_html( $list['title'] ).'</span>';
                        if( $is_link ) {
                            self::linkClose();
                        }
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }

}