<?php 
namespace Enteraddons\Widgets\Timeline\Traits;
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
        <div class="enteraddons-timeline-wrapper timeline-<?php echo esc_attr( $settings['line_position'] ); ?>">
            <div class="enteraddons-single-timeline">
                <div class="single-timeline-inner enteraddons-d-flex enteraddons-align-items-center nunito">
                    <?php 
                    // Time
                    self::date();
                    ?>
                    <div class="timeline-text">
                        <?php
                        // Title
                        self::title();
                        // Descriptions
                        self::descriptions();
                        ?>                        
                    </div>
                    <?php
                    // Icon
                    self::icon();
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

}