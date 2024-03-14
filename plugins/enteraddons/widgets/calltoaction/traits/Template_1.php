<?php 
namespace Enteraddons\Widgets\Calltoaction\Traits;
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

        echo '<div class="enteraddons-call-to-action-wrapper layout-'.esc_attr( $settings['layout'] ).'">';
            echo '<div class="cta-content-wrapper">';
                //
                self::title();
                //
                self::descriptions();
            echo '</div>';
            // Button
            self::button();
        echo '</div>';
	}

}