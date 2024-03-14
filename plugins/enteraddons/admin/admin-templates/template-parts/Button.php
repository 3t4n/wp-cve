<?php
namespace Enteraddons\Admin;
/**
 * Enteraddons admin section
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

trait Button{

    function save_button() {
        ?>
        <div class="eddons-bottom-bar">
            <div class="container">
                <div class="eddons-bottom-inner">
                    <div class="eddons-bottom-left">
                        <p><strong>0</strong> <?php esc_html_e( 'Widgets are selected', 'enteraddons' ); ?></p>
                    </div>
                    <div class="eddons-bottom-right">
                        <button type="submit" class="btn s-btn enteraddons_save-btn"><?php esc_html_e( 'Save Settings', 'enteraddons' ); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>
