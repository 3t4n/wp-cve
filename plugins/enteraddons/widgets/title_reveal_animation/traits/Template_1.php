<?php 
namespace Enteraddons\Widgets\Title_Reveal_Animation\Traits;
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
        <div class="eaathas-animation ea-reveal-animation eaatanimation-ltr" data-delay="<?php echo esc_html($settings['title_data_delay']); ?>">
            <?php 
            self::title();
            ?>
        </div>

        <div class="eaathas-animation ea-reveal-animation eaatanimation-rtl" data-delay="<?php echo esc_html($settings['Description_data_delay']); ?>">
            <?php 
            self::description();
            ?>
        </div>
		<?php
	}

}