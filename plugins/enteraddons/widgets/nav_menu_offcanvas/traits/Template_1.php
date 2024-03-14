<?php 
namespace Enteraddons\Widgets\Nav_Menu_Offcanvas\Traits;
/**
 * Enteraddons team template class
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

        if( !empty( $settings['nav_menu_select'] )  ) {
            $id = self::getWidgetObject()->get_id();
            echo '<div class="ea-offcanvas-nav-button-wrapper">';
                echo '<div class="ea-hamburger-offcanvas-menu menu-trigger" data-toggle="offCanvas" data-target="mobile_menu_'.esc_attr( $id ).'">
                  <span class="ea-menu-bar-btn">'.\Enteraddons\Classes\Helper::getElementorIcon( $settings['menu_icon'] ).'</span>
                </div>';
            echo '</div>';
        }
	}

}