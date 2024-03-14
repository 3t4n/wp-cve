<?php 
namespace Enteraddons\Widgets\Counter\Traits;
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
        <div class="enteraddons-single-counter counter-icon-<?php echo esc_attr( $settings['icon_position'] ); ?>">
            <?php
            // Icon
            self::icon();
            ?>
            <div class="enteraddons-counter-content counter-number-<?php echo esc_attr( $settings['number_position'] ); ?>">
                <?php
                // Icon
                self::title();
                // Number
                self::number();
                ?>
            </div>
        </div>
		<?php
	}

}