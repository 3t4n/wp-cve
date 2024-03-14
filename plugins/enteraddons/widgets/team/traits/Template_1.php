<?php 
namespace Enteraddons\Widgets\Team\Traits;
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
		?>
        <div class="enteraddons-wid-con">
            <div class="enteraddons-single-team">
                <div class="enteraddons-team-image mb-0">
                    <?php
                    // Image
                    self::image();                    
                    ?>
                </div>
                <div class="enteraddons-team-content">
                    <?php
                    // experience
                    self::experience();
                    // Name
                    self::name();
                    // Designation
                    self::designation();
                    // descriptions
                    self::descriptions();
                    // Social Icon
                    self::socialIcons();
                    // Link
                    self::link();
                    ?>
                </div>
            </div>
        </div>
		<?php
	}

}