<?php 
namespace Enteraddons\Widgets\Coupon_Code\Traits;
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

    public static function markup() {
        $settings = self::getSettings();

        if( $settings['code_card_style'] == 'style-4' ) {
            self::style_4();
        } else {
            self::style_entire();
        }
    }

    public static function style_entire() {
        $settings = self::getSettings();
        ?>
        <div class="ea-coupon-code-wrap ea-ccc-<?php echo esc_attr( $settings['code_card_style'] ); ?>">
            <div class="ea-coupon-code-inner">
                <?php 
                echo '<span class="ea-coupon-code">'.esc_html( self::couponcode() ).'</span>';
                ?>
                <span class="ea-ccb ea-get-code" data-target="1" data-copied="<?php echo esc_attr( self::copiedText() ); ?>"><?php echo self::icon().self::copyBtnText(); ?></span>
            </div>
        </div>
        <?php
    }

    public static function style_4() {
        $settings = self::getSettings();
        ?>
        <div class="ea-coupon-code-wrap ea-ccc-style-4">
            <div class="ea-coupon-code-inner ea-get-code" data-target="4" data-code="<?php echo esc_attr( self::couponcode() ); ?>" data-copied="<?php echo esc_attr( self::copiedText() ); ?>">
                <span class="ea-coupon--code"><?php echo self::icon().self::copyBtnText(); ?></span>
            </div>
        </div>
        <?php
    }

}