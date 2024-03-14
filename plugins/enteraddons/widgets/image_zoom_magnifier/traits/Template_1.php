<?php 
namespace Enteraddons\Widgets\Image_Zoom_Magnifier\Traits;
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
        $settings = self::getDisplaySettings();
		?>
        <div class="ea-image-zoom-magnifier">
            <?php 
            //Image
            self::image();
            ?>
        </div>
		<?php
        
	}

}