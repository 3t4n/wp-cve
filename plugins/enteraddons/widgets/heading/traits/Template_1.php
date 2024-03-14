<?php 
namespace Enteraddons\Widgets\Heading\Traits;
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
		?>
        <div class="enteraddons-section-title">
            <?php
            //Section Title
            self::title();
            // Section Sub Title
            if( !empty( $settings['show_subtitle'] ) ) {
            self::subTitle();
            }
            // Section description
            if( !empty( $settings['show_description'] ) ) {
            self::descriptions();
            }
            // Section Divider
            if( !empty( $settings['show_description_divider'] ) ) {
                self::divider();
            }
            ?>
        </div>
		<?php
	}

}