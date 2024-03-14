<?php 
namespace Enteraddons\Widgets\Photo_Hanger\Traits;
/**
 * Enteraddons template class
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
        <div class="ea-photo-frame-wrapper">
          <div class="ea-photo-frame">
            <?php 
                self::thumbnail( $settings['image'] ); 
                if( ! empty( $settings['title'] ) ) {
                    self::title( $settings['title'] );
                }
            ?>
          </div>
        </div>
        <?php
    }


}