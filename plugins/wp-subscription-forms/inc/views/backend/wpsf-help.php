<div class="wrap wpsf-wrap">
    <div class="wpsf-header wpsf-clearfix">
        <h1 class="wpsf-floatLeft">
            <img src="<?php echo WPSF_URL . 'images/logo.png' ?>" class="wpsf-plugin-logo" />
            <span class="wpsf-sub-header"><?php esc_html_e('Help', 'wp-subscription-forms'); ?></span>
        </h1>
        <div class="wpsf-add-wrap">
            <a href="<?php echo admin_url('admin.php?page=add-subscription-form'); ?>"><input type="button" class="wpsf-button-primary" value="<?php esc_html_e('Add New Form', 'wp-subscription-forms'); ?>"></a>
        </div>
    </div>

    <div class="wpsf-form-wrap wpsf-form-add-block wpsf-left-wrap wpsf-clearfix">
        <div class="wpsf-content-block">
            <h2><?php esc_html_e('Documentation', 'wp-subscription-forms'); ?></h2>
            <p><?php esc_html_e('You can check our detailed documentation from below link.', 'wp-subscription-forms'); ?></p>
            <p><a href="http://wpshuffle.com/wordpress-documentations/wp-subscription-forms" target="_blank">http://wpshuffle.com/wordpress-documentations/wp-subscription-forms</a></p>
        </div>

        <div class="wpsf-content-block">
            <h2><?php esc_html_e('Developer Documentation', 'wp-subscription-forms'); ?></h2>
            <p><?php esc_html_e('If you are developer and trying to add any functionality or customize our plugin through hooks then below are the list of actions and filters available in the plugin.', 'wp-subscription-forms'); ?></p>
        </div>

        <div class="wpsf-content-block">
            <h3><?php esc_html_e('Available Actions', 'wp-subscription-forms'); ?></h3>
            <div class="wpsf-hooks-wrap">
                <pre>
/**
 * <?php esc_html_e('Fires when Init hook is fired through plugin', 'wp-subscription-forms'); ?>
 *
 * @since 1.0.0
 */
do_action('wpsf_init');
                </pre>
                <pre>
/**
 * <?php esc_html_e('Triggers just before processing the subscription form', 'wp-subscription-forms'); ?>
 *
 * @since 1.0.0
 */
do_action('wpsf_before_form_process');
                </pre>
                <pre>
/**
 * <?php esc_html_e('Triggers at the end of processing the subscription form successfully', 'wp-subscription-forms'); ?>
 *
 * @param array $form_data
 *
 * @since 1.0.0
 */
 do_action('wpsf_end_form_process', $form_data, $form_details);
                </pre>
                <pre>
/**
 * <?php esc_html_e('Triggers just before displaying the subscription form', 'wp-subscription-forms'); ?>
 *
 * @param object $form_row
 *
 * @since 1.0.0
 */
 do_action('wpsf_before_form', $form_row);
                </pre>
                <pre>
/**
 * <?php esc_html_e('Triggers just after displaying the subscription form', 'wp-subscription-forms'); ?>
 *
 * @param object $form_row
 *
 * @since 1.0.0
 */
do_action('wpsf_after_form', $form_row);
                </pre>
                <pre>
/**
 * <?php esc_html_e('Triggers just before displaying the download button', 'wp-subscription-forms'); ?>
 *
 * @param object $form_row
 *
 * @since 1.0.0
 */
do_action('wpsf_before_download', $form_row);
                </pre>
                <pre>
/**
 * <?php esc_html_e('Triggers just after displaying the download button', 'wp-subscription-forms'); ?>
 *
 * @param object $form_row
 *
 * @since 1.0.0
 */
do_action('wpsf_after_download', $form_row);
                </pre>
            </div>
        </div>

        <div class="wpsf-content-block">
            <h3><?php esc_html_e('Available Filters', 'wp-subscription-forms'); ?></h3>
            <div class="wpsf-hooks-wrap">
                <pre>
/**
 * <?php esc_html_e('Filters csv rows', 'wp-subscription-forms'); ?>
 *
 * @param array $csv_rows
 *
 * @since 1.0.0
 */
$csv_rows = apply_filters('wpsf_csv_rows', $csv_rows);
                </pre>
                <pre>
/**
 * <?php esc_html_e('Filters download path before starting the download', 'wp-subscription-forms'); ?>
 *
 * @param string $download_path
 * @param string $encryption_key
 *
 * @since 1.0.0
 */
 $download_path = apply_filters('wpsf_download_path', $download_path, $encryption_key);
                </pre>
                <pre>
/**
 * <?php esc_html_e('Filters post parameters being sent to Maichimp', 'wp-subscription-forms'); ?>
 *
 * @param array $post_parameters
 * @param array $form_data
 * @param array $form_details
 */
 $post_parameters = apply_filters('wpsf_mc_post_parameters', $post_parameters, $form_data, $form_details);
                </pre>
                <pre>
/**
 * <?php esc_html_e('Filters parameters being sent to Constant Contact', 'wp-subscription-forms'); ?>
 *
 * @param array $post_parameters
 * @param array $form_data
 * @param array $form_details
 */
 $post_parameters = apply_filters('wpsf_cc_post_parameters', $post_parameters, $form_data, $form_details);
                </pre>
                <pre>
/**
 * <?php esc_html_e('Filters email message', 'wp-subscription-forms'); ?>
 *
 * @param string $email_message
 * @param array $form_data
 *
 * @since 1.0.0
 */
$email_message = apply_filters('wpsf_email_message', $email_message, $form_data);
                </pre>
            </div>
            <p><?php esc_html_e('If you think there are any missing action or filters then please let us know from below link.', 'wp-subscription-forms'); ?></p>
            <a href="https://wpshuffle.com/contact-us/" target="_blank">https://wpshuffle.com/contact-us/</a>
        </div>

    </div>

    <?php include(WPSF_PATH . 'inc/views/backend/upgrade-to-pro.php'); ?>




</div>
