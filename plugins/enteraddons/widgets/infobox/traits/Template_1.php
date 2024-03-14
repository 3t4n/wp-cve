<?php 
namespace Enteraddons\Widgets\Infobox\Traits;
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
        <div class="enteraddons-wid-con">
            <?php
            //
            if( 'yes' == $settings['wrapper_link'] ) {
                echo self::linkOpen(); 
            }
            ?>
            <div class="enteraddons-info-box text-center icon-<?php echo esc_attr( $settings['infobox_icon_position'].' '.$settings['infobox_hover_effect'] ); ?>">
                <?php
                if( !empty( $settings['infobox_ribbon_switch'] ) && $settings['infobox_ribbon_switch'] == 'yes' ) {
                    echo '<div class="infobox-card-ribbon">'.esc_html( $settings['infobox_ribbon_title'] ).'</div>';
                }
                //Icon
                self::icon();
                // Content
                ?>
                <div class="enteraddons-info-box-content">
                    <?php
                    self::title();
                    self::descriptions();
                    self::button();
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