<?php 
namespace Enteraddons\Widgets\Nav_Logo\Traits;
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

        //
        $target = '_self';
        if( !empty( $settings['site_link']['is_external'] ) && $settings['site_link']['is_external'] == 'on' ) {
            $target = '_blank';
        }
        //
        $url = site_url('/');
        if( !empty( $settings['site_link']['url'] ) ) {
            $url = $settings['site_link']['url'];
        }
        
        $hasSticky = !empty( $settings['active_sticky_logo'] ) ? ' ea-has-sticky' : '';

        echo '<a class="logo-wrap-link" href="'.esc_url( $url ).'" target="'.esc_attr( $target ).'">';
            echo '<div class="ea-logo-wrapper'.esc_attr( $hasSticky ).'">';
                self::mainLogo();
                if( !empty( $settings['active_sticky_logo'] ) && !empty( $settings['sticky_logo_img']['url'] ) ) {
                self::stickyLogo();
                }
            echo '</div>';
        echo '</a>';
	}

}