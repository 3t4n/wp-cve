<?php 
namespace Enteraddons\Widgets\Image_Hotspot\Traits;
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
        $hotspotsettings = self::hotspotsettings();
        ?>

        <div class="ea-image-hotspot responsive-hotspot-wrap" data-hotspotsettings="<?php echo htmlspecialchars( $hotspotsettings, ENT_QUOTES, 'UTF-8'); ?>">
            <?php 
            self::background_image();

            if( !empty( $settings['image_hotspot_list'] ) ):
            foreach( $settings['image_hotspot_list'] as $item ): 

                $ValueX = !empty( $item['value_x']['size'] ) ? $item['value_x']['size'] : '';
                $ValueY = !empty( $item['value_y']['size'] ) ? $item['value_y']['size'] : '';
            ?>

                <div class="ea-hot-spot" x="<?php echo esc_html( $ValueX ); ?>" y="<?php echo esc_html( $ValueY ); ?>">
                    <div class="ea-circle"></div>
                    <div class="ea-tooltip">
                        <?php self::image($item); ?>
                        <div class="ea-text-row">
                         <?php
                            self::title($item);
                            self::description($item);
                         ?>
                        </div>
                    </div>
                </div>

            <?php 
            endforeach; 
            endif; 
            ?>

        </div> 
<?php         
	}
}


