<?php 
namespace Enteraddons\Widgets\Newsletter\Traits;
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
        <div class="<?php echo esc_attr( 'enteraddons-newsletter-wrapper style-'.$settings['style'] ); ?>">
            <form action="#" method="post" data-list-id="<?php echo esc_attr( $settings['newsletter_list_id'] ); ?>" class="<?php echo esc_attr( 'enteraddons-newsletter-form layout-'.$settings['newsletter_type'] ); ?>">
                <div class="enteraddons-newsletter-inner">
                <?php
                // Input Field
                self::input();
                // Button
                self::searchButton();
                ?>
                </div>
                <div class="enteraddons-newsletter-response"></div>
            </form>
        </div>
        <?php
    }

}