<?php 
namespace Enteraddons\Widgets\Collection_Box\Traits;
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
        <?php
            if( 'yes' == $settings['wrapper_link'] ) {
                echo self::linkOpen(); 
            }
            ?>
        <div class="single-collection icon-<?php echo esc_attr( $settings['collection_box_icon_position']);?>" data-count="<?php echo esc_html($settings['collection_box_data_count']); ?>">
        
            <?php
            //Icon
            self::image();
            ?>
            <div class="content">
                     <?php
                     //content
                    self::title();
                    self::ammount();
                    ?>  
            </div>
            </div>
            <?php
            if( 'yes' == $settings['wrapper_link'] ) {
                echo self::linkClose(); 
            }
            ?>
        <?php
    }

}
