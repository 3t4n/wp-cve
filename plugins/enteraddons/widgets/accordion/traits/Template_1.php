<?php 
namespace Enteraddons\Widgets\Accordion\Traits;
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
            <div class="enteraddons-accordion-style-1 enteraddons-faq">
                <?php 
                if( !empty( $settings['accordion'] ) ):
                    foreach( $settings['accordion'] as $accordion ):
                        $active = !empty( $accordion['accordion_active'] ) && $accordion['accordion_active'] == 'yes' ? ' active' : '';
                ?>
                <div class="enteraddons-single-faq <?php echo esc_attr( $active ); ?>" data-accordion-tab="toggle">
                    <?php 
                    if( !empty( $accordion['accordion_title'] ) ):
                    ?>
                    <h3 class="enteraddons-faq-title">
                        <?php
                        // Left Icon
                        self::leftIcon();
                        // Title Number Count
                        self::numberCount();
                        // Title
                        echo esc_html( $accordion['accordion_title'] );
                        // Right Icon
                        self::rightIcon();
                        ?>
                    </h3>
                    <?php
                    endif;
                    //
                    if( !empty( $accordion['accordion_desc'] ) ) {
                        echo '<div class="enteraddons-faq-content">'.wp_kses( $accordion['accordion_desc'], 'post' ).'</div>';
                    }
                    ?>
                </div>
                <?php 
                    endforeach;
                endif;
                ?>
            </div>
        </div>

        <?php
    }


}