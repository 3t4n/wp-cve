<?php 
namespace Enteraddons\Widgets\Video_Button\Traits;
/**
 * Enteraddons Video Button template class
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
        $overlay = !empty( $settings['overly_active'] ) && $settings['overly_active'] == 'yes' ? 'video-overlay' : '';
		?>
        <div class="enteraddons-video-wrap <?php echo esc_attr( $overlay ); ?>">
            <?php
            self::animation();
            //
            self::image();
            //
            self::button();
            ?>
        </div>
		<?php
	}

}