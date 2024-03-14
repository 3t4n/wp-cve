<div class="wrap stul-wrap">
    <div class="stul-header stul-clearfix">
        <h1 class="stul-floatLeft">
            <img src="<?php echo STUL_URL . 'images/logo.png' ?>" class="stul-plugin-logo" />
            <span class="stul-sub-header"><?php esc_html_e('Help', 'subscribe-to-unlock-lite'); ?></span>
        </h1>
        <div class="stul-add-wrap">
            <a href="<?php echo admin_url('admin.php?page=add-subscription-form'); ?>"><input type="button" class="stul-button-white" value="<?php esc_html_e('Add New Form', 'subscribe-to-unlock-lite'); ?>"></a>
        </div>
        <div class="stul-social">
            <a href="https://www.facebook.com/wpshuffle/" target="_blank"><i class="dashicons dashicons-facebook-alt"></i></a>
            <a href="https://twitter.com/wpshuffle/" target="_blank"><i class="dashicons dashicons-twitter"></i></a>
        </div>
    </div>

    <div class="stul-form-wrap stul-form-add-block stul-clearfix">

        <div class="stul-block-wrap">
            <div class="stul-content-block">
                <h2><?php esc_html_e('Documentation', 'subscribe-to-unlock-lite'); ?></h2>
                <p><?php esc_html_e('You can check our detailed documentation in below link.', 'subscribe-to-unlock-lite'); ?></p>
                <p><a href="https://wpshuffle.com/wordpress-documentations/subscribe-to-unlock-lite/" target="_blank">https://wpshuffle.com/wordpress-documentations/subscribe-to-unlock-lite/</a></p>
            </div>
            <div class="stul-content-block">
                <h2><?php esc_html_e('Support', 'subscribe-to-unlock-lite'); ?></h2>
                <p><?php esc_html_e('In case you need any assistance regarding our plugin then you can contact us from below link. We will try to get back to you as soon as possible.', 'subscribe-to-unlock-lite'); ?></p>
                <a href="https://wpshuffle.com/contact-us/" target="_blank">https://wpshuffle.com/contact-us/</a>
            </div>
            <div class="stul-content-block">
                <h2><?php esc_html_e('Developer Documentation', 'subscribe-to-unlock-lite'); ?></h2>
                <p><?php esc_html_e('If you are developer and trying to add any functionality or customize our plugin through hooks then below are the list of actions and filters available in the plugin.', 'subscribe-to-unlock-lite'); ?></p>
                <div class="stul-content-sub-block">
                    <h3><?php esc_html_e('Available Actions', 'subscribe-to-unlock-lite'); ?></h3>
                    <div class="stul-hooks-wrap">
                        <pre>
    /**
     * <?php esc_html_e('Fires when Init hook is fired through plugin', 'subscribe-to-unlock-lite'); ?>
     *
     * @since 1.0.0
     */
    do_action('stul_init');
                        </pre>
                        <pre>
    /**
     * <?php esc_html_e('Triggers just before processing the subscription form', 'subscribe-to-unlock-lite'); ?>
     *
     * @since 1.0.0
     */
    do_action('stul_before_form_process');
                        </pre>
                        <pre>
    /**
     * <?php esc_html_e('Triggers at the end of processing the subscription form successfully', 'subscribe-to-unlock-lite'); ?>
     *
     * @param array $form_data
     *
     * @since 1.0.0
     */
     do_action('stul_end_form_process', $form_data, $form_details);
                        </pre>
                        <pre>
    /**
     * <?php esc_html_e('Triggers just before displaying the subscription form', 'subscribe-to-unlock-lite'); ?>
     *
     * @param object $form_row
     *
     * @since 1.0.0
     */
     do_action('stul_before_form', $form_row);
                        </pre>
                        <pre>
    /**
     * <?php esc_html_e('Triggers just after displaying the subscription form', 'subscribe-to-unlock-lite'); ?>
     *
     * @param object $form_row
     *
     * @since 1.0.0
     */
    do_action('stul_after_form', $form_row);
                        </pre>

                    </div>
                </div>
                <div class="stul-content-sub-block">
                    <h3><?php esc_html_e('Available Filters', 'subscribe-to-unlock-lite'); ?></h3>
                    <div class="stul-hooks-wrap">
                        <pre>
    /**
     * <?php esc_html_e('Filters csv rows', 'subscribe-to-unlock-lite'); ?>
     *
     * @param array $csv_rows
     *
     * @since 1.0.0
     */
    $csv_rows = apply_filters('stul_csv_rows', $csv_rows);
                        </pre>

                        <pre>
    /**
     * <?php esc_html_e('Filters email message', 'subscribe-to-unlock-lite'); ?>
     *
     * @param string $email_message
     * @param array $form_data
     *
     * @since 1.0.0
     */
    $email_message = apply_filters('stul_email_message', $email_message, $form_data);
                        </pre>
                    </div>
                </div>
                <p><?php esc_html_e('If you think there are any missing action or filters then please let us know from below link.', 'subscribe-to-unlock-lite'); ?></p>
                <a href="https://wpshuffle.com/contact-us/" target="_blank">https://wpshuffle.com/contact-us/</a>
            </div>
        </div>
        <?php include(STUL_PATH . 'inc/views/backend/upgrade-to-pro-sidebar.php'); ?>
    </div>
</div>
