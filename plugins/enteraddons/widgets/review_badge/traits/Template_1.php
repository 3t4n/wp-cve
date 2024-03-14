<?php 
namespace Enteraddons\Widgets\Review_Badge\Traits;
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
        <div class="enteraddons-customer-review">
            <?php
            self::ratings_image();
            self::star();
            self::ratings();
            self::text();
            ?>
        </div>
		<?php
	}

}