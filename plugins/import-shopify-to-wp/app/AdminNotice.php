<?php

namespace S2WPImporter;

class AdminNotice
{
    public function init()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
        add_action('admin_notices', [$this, 'notice']);
    }

    public function enqueue()
    {
        $asset = include S2WP_IMPORTER_DIR . 'js/notice.min.asset.php';
        wp_enqueue_script(
                's2wp-importer-notice-script',
                S2WP_IMPORTER_URI . 'js/notice.min.js',
                $asset['dependencies'],
                $asset['version'],
                true
        );
    }

    public function notice()
    {
        if (
                (isset($_COOKIE['s2wp_notice_dismissed']) && $_COOKIE['s2wp_notice_dismissed'] === 'yes') ||
                !current_user_can('manage_options') ||
                //phpcs:ignore WordPress.Security.NonceVerification.Recommended, determining location in dashboard.
                (!empty($_GET['page']) && $_GET['page'] === 'import-shopify-to-wp')
        ) {
            return;
        }
        ?>
        <div class="notice notice-success is-dismissible s2wp-notice">
            <p><?php
                $message = __(
                        'Start importing the Shopify data to WordPress by going to the settings page under "Tools -> Shopify Importer".',
                        'import-shopify-to-wp'
                );

                $cta = sprintf(
                        '<a href="' . admin_url('tools.php?page=import-shopify-to-wp') . '" class="s2wp-notice__link">%s</a>',
                        __('Click here to go there', 'import-shopify-to-wp')
                );

                echo wp_kses_post( $message . ' ' . $cta );
                ?>
            </p>
        </div>
        <?php
    }
}
