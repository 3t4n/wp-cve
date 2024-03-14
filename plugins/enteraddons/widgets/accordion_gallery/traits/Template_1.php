<?php 
namespace Enteraddons\Widgets\Accordion_Gallery\Traits;

/**
 * Enteraddons accordion Gallery template class
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
    $icon = \Enteraddons\Classes\Helper::getElementorIcon( $settings['button_icon'] );
    ?>
        <div class="ea-accordion-gallery">
            <?php 
                if( $settings['gallery_item_list'] ) :
                foreach( $settings['gallery_item_list'] as $item ) :
            ?>
                <div class= "ea-gallery-item">
                    <div class="ea-gallery-overlay"></div>
                    <div class="ea-vg-info">
                        <?php 
                            self::gallery_title( $item );  
                            self::gallery_subtitle( $item ); 
                            echo '<a href="#" class="ea-vg-btn">';
                            self::trigger_button( $item );
                            echo $icon;  
                            echo  '</a>'; 
                            echo '<div class="ea-gallery-toggle">'; 
                            self::gallery_description( $item );  
                            self::gallery_button( $item );
                            echo '</div>';
                        ?> 
                    </div>
                    <div class="ea-bg-img"> 
                        <?php  self::gallery_image( $item ); ?>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    <?php
}

}