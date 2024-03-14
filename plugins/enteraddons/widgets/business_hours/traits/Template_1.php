<?php 
namespace Enteraddons\Widgets\Business_Hours\Traits;
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
        <div class="ea-business-hours-wrap">
            <?php 
            if( !empty( $settings['show_title'] ) ){
                echo  '<div class="ea-business-hours-header">';
                    self::header();
                echo '</div>';
            }
            ?>
            <div class="business-hours-inner">
                <?php 
                if( !empty( $settings['business_hours_list'] ) ){
                    foreach( $settings['business_hours_list'] as $item ) {
                        
                        echo  '<div class="ea-single-item elementor-repeater-item-'.esc_attr( $item['_id'] ).'">';
                            self::day($item);
                            self::time($item);
                        echo '</div>';
                    }
                }
               ?>
            </div>
        </div>
<?php
	}

}