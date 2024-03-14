<?php

namespace Woo_MP;

defined( 'ABSPATH' ) || die;

/**
 * Apply the 'readme.txt' 'Upgrade Notice' section to the Plugins page.
 *
 * This is similar to what is already done natively on the WordPress Updates page.
 */
class Upgrade_Notices {

    /**
     * Output upgrade notice.
     *
     * @param  object $plugin_data An array of plugin metadata.
     * @param  object $response    An array of metadata about the available plugin update.
     * @return void
     */
    public function output_upgrade_notice( $plugin_data, $response ) {

        if ( empty( $response->upgrade_notice ) ) {
            return;
        }

        ?>

        </p>

        <style>
            .woo-mp-upgrade-notice:before {
                float: left;
                margin: 0 6px 0 0;
                color: #f56e28;
            }

            .woo-mp-upgrade-notice p:before {
                display: none;
            }
        </style>

        <div class="woo-mp-upgrade-notice dashicons-before dashicons-info">
            <?= wp_kses_post( $response->upgrade_notice ) ?>
        </div>

        <p style="display: none;">

        <?php

    }

}
