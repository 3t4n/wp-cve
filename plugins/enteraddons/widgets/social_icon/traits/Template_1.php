<?php 
namespace Enteraddons\Widgets\Social_Icon\Traits;
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

        $settings = self::getDisplaySettings();

        ?>
        <div class="enteraddons-social-icon-wrap">
            <div class="enteraddons-social-icon-inner-wrap">
                <?php
                // Section Title 
                self::heading();
                $iconStyle = $settings['icon_style'] =='1'  ? 'ea_sicon_default_style' : '';
                ?>
                <div class="enteraddons-social-icon icon-style--three <?php echo esc_attr( $iconStyle )?>">
                    <?php 
                    if( !empty( $settings['social_icon'] ) ) {
                        foreach( $settings['social_icon'] as $icons ) {
                            self::linkOpen($icons);
                            self::icon($icons);
                            self::linkClose();
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

}