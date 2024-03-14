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

trait General{

    use Embed_Video;
    use Statistics;
    use System_Status;

    function general_tab_content() {

        ?>
        <div data-tab="general">
            <div class="container">
                <!-- Dashboard Content Wrapper -->
                <div class="dashboard-content-wrap">
                    <?php
                    if( ! \Enteraddons\Classes\Helper::is_pro_active() ) {
                        echo '<div class="pro-banner"><a target="_blank" href="https://enteraddons.com/pricing/"><img src="'.ENTERADDONS_DIR_ADMIN_ASSETS.'img/pro-banner.png'.'"></a></div>';
                    }
                    do_action( 'ea_general_tab_content_before' );
                    // Statistics
                    self::statistics_content();
                    // System Status
                    self::system_status_content();
                    ?>                        
                </div>
            </div>
        </div>
        <?php
    }

}