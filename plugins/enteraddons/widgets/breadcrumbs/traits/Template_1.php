<?php
namespace Enteraddons\Widgets\Breadcrumbs\Traits;
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
        $settings = self::getSettings();
        ?>
        <div class="ea-breadcrumbs-wrap">
            <?php 
            if( $settings['breadcrumbs_type'] == 'custom' ) {
                self::customBreadcrumbs();
            } else {
                // Dynamic 
                \Enteraddons\Classes\Breadcrumbs::getBreadcrumb( $settings );
            }
            ?>
        </div>
        <?php
    }

}