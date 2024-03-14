<?php 
namespace Enteraddons\Widgets\Nav_Menu\Traits;
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

            $is_before_active = !empty( $settings['active_menu_before'] ) ? ' active-before-line': '';
            $is_after_active  = !empty( $settings['active_menu_after'] ) ? ' active-after-line': '';
            $line_animation  = !empty( $settings['line_animation'] ) ? ' '.$settings['line_animation']: '';

            echo '<div class="ea-nav-menu-wrapper">';
                echo '<div class="ea-hamburger-menu">
                  <span class="ea-menu-bar-btn">'.\Enteraddons\Classes\Helper::getElementorIcon( $settings['menu_icon'] ).'</span>
                  <span class="ea-menu-close-btn">'.\Enteraddons\Classes\Helper::getElementorIcon( $settings['menu_close_icon'] ).'</span>
                </div>';
                echo '<div class="ea-nav-wrap-inner ea-desktop-nav">';
                    wp_nav_menu( array(
                        'menu'              => esc_html( $settings['nav_menu_select'] ),
                        'menu_class'        => "ea-nav-menu-items".esc_attr( $is_before_active.$is_after_active.$line_animation ),
                        'container'         => "" 
                    ) );
                echo '</div>';
                // For Mobile menu
                echo '<div class="ea-nav-wrap-inner ea-hamburger-nav-wrap">';
                    wp_nav_menu( array(
                        'menu'              => esc_html( $settings['nav_menu_select'] ),
                        'menu_class'        => "ea-nav-menu-items".esc_attr( $is_before_active.$is_after_active.$line_animation ),
                        'container'         => "" 
                    ) );
                echo '</div>';
                
            echo '</div>';
        }
	}

}