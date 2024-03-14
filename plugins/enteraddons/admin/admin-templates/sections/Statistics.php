<?php
namespace Enteraddons\Admin;
/**
 * Enteraddons admin
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

trait Statistics {

    public function statistics_content() {

        $statistics = \Enteraddons\Admin\Admin_Helper::enteraddons_statistic();
        
        ?>
        <div class="statistics">
            <?php 
            // Single Statistics
            foreach( $statistics as $statistic ) {
                echo '<div class="single-statistic"><p><span style="color: '.esc_attr( $statistic['color_code'] ).';">'.esc_html( $statistic['number'] ).'</span>'.esc_html( $statistic['title'] ).'</p></div>';
            }
            ?>
        </div>
        <?php
    }

}