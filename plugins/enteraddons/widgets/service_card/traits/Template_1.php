<?php 
namespace Enteraddons\Widgets\Service_Card\Traits;
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
        <div class="enteraddons-wid-con enteraddons-service-card">
            <?php
            if( 'yes' == $settings['wrapper_link'] ) {
                echo self::linkOpen(); 
            }
            ?>
            <div class="enteraddons-card">
                <?php
                // Image
                self::image();
                ?>
                <div class="card--body enteraddons-align-items-center">
                    <?php
                    // Content
                    self::icon();
                    self::title();
                    ?>
                </div>
            </div>
            <?php 
            if( 'yes' == $settings['wrapper_link'] ) {
                echo self::linkClose();
            }
            ?>
        </div>
		<?php
	}

}