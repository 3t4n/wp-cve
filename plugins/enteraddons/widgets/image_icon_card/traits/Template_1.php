<?php 
namespace Enteraddons\Widgets\Image_Icon_Card\Traits;
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
        $settings   = self::getSettings();
		?>
        <div class=eagrid-item>
            <div class="eacard eaapp-icon-card">
                <div class=eaapp-icon-image>
                    <?php
                    //Image
                    self::image();
                     ?>
                </div>
                <div class=eaapp-icon-details>
                    <?php 
                    //Title
                    self::title();
                    //Icon
                    if( 'yes' == $settings['icon_link_condition'] ) {
                        echo self::linkOpen(); 
                    }
                    self::icon();
                    if( 'yes' == $settings['icon_link_condition'] ) {
                        echo self::linkClose(); 
                    }
                    ?>
                </div>
            </div>
        </div>
		<?php
	}

}