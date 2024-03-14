<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://prooffactor.com
 * @since      1.0.0
 *
 * @package    Proof_Factor_WP
 * @subpackage Proof_Factor_WP/admin/partials
 */

$options = get_option($this->plugin_name);
$proof_account_id = $options['account_id'];

$should_show_link = true;
$proof_account_exists = false;

if ($proof_account_id) {
    try {
        $request = wp_remote_get("https://api.prooffactor.com/v1/partners/wordpress/validate?account_id=" . $proof_account_id);
        $response_code = wp_remote_retrieve_response_code($request);
        if (!is_wp_error($request) && $response_code == 200) {
            $body = wp_remote_retrieve_body($request);
            if (!is_wp_error($body)) {
                $data = json_decode($body, true);
                if (isset($data) && !empty($data) && !is_null($data)) {
                    if (array_key_exists('valid', $data)) {
                        $should_show_link = ($data['valid'] == false);
                    }
                    if (array_key_exists('exists', $data)) {
                        $proof_account_exists = $data['exists'];
                    }
                }
            }
        }
    } catch (Exception $e) {
    }
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="proof-factor-wp-wrap">
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <hr>

    <?php
    if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        ?>
        <div class="proof-factor-wp-alert proof-factor-wp-alert-danger">
            &#9432; It looks like you have WooCommerce installed.
            <p>Please remove this plugin and install the <a
                        href="/wp-admin/plugin-install.php?s=prooffactor+woocommerce&tab=search&type=term">Proof
                    Factor WooCommerce Plugin</a> which will automatically handle your new and existing orders.
            </p>
        </div>
        <?php
    } else if ($proof_account_exists == false) { ?>
        <div class="proof-factor-wp-alert proof-factor-wp-alert-danger">
            &#9432; A Proof Factor Account is Required. Please Create an account on <a target=”_blank”
                                                                                       href="https://app.prooffactor.com/register">ProofFactor.com</a>
            <p>If you already have an account you can find your <b>API Key</b> from the Account
                Details section of the <a href="https://app.prooffactor.com/settings" target=”_blank”>Proof Factor
                    Settings Page</a>
            </p>
        </div>
    <?php } else { ?>
        <div class="proof-factor-wp-alert proof-factor-wp-alert-success">
            Proof Factor is Successfully Installed
        </div>
    <?php } ?>

    <form method="post" name="proof_plugin_options" action="options.php">
        <?php
        settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);
        ?>
        <div class="proof-factor-wp-form-group">
            <label for="proof-factor-wp[account_id]">API Key:</label>
            <input class="proof-input-account" type="text"
                   id="proof_account_id"
                   placeholder="Enter API Key"
                   value="<?= $options['account_id'] ?>"
                   name="<?php echo $this->plugin_name; ?>[account_id]">
        </div>
        <div class="proof-factor-wp-form-group">
            <input type="submit" name="submit" id="submit"
                   class="prooffactor-button prooffactor-button-sm prooffactor-button-secondary"
                   value="Save">
        </div>
        <?php
        if ($should_show_link) {
            ?>
            <p>&#9432; You can find your <b>API Key</b> from the Account Details section of the
                <a href="https://app.prooffactor.com/settings" target=”_blank”>Proof Factor Settings Page</a>
            </p>
            <?php
        }
        ?>
    </form>

    <?php
    if ($should_show_link == false && $proof_account_exists) {
        ?>
        <p>&#9432; You can configure additional notifications options at <a href="https://app.prooffactor.com"
                                                                            target=”_blank”>https://app.prooffactor.com</a>
        </p>
        <?php
    }
    ?>
</div>