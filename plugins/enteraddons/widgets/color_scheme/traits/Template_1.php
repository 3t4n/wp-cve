<?php 
namespace Enteraddons\Widgets\Color_Scheme\Traits;
/**
 * Enteraddons Color Scheme template class
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
        <div class="ea-color-palettes">
            <div class="ea-colors">
                <?php 
                    if( $settings['color_code_list'] ) {
                        foreach( $settings['color_code_list'] as $color ) {
                            self::color_code( $color );  
                        }
                    } 
                ?>   
            </div>
             <?php self::scheme_name(); ?>  
        </div>
		<?php
	}

}