<?php
/**
 * Connect page details.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$adminClass = 'OmnivoreAdmin';

if (!class_exists($adminClass)) {
  wp_die(__('Plugin Administation class is missing.'));
}

$citybeach_key = $adminClass::connection_key();

$registered_connection_key = get_option($adminClass::flavour . '_connection_key');
$registered_connection_key_has = !empty($registered_connection_key);
$registered_connection_key = $registered_connection_key_has ? $registered_connection_key : $adminClass::generate_key();

$registered_connection_endpoint = get_option($adminClass::flavour . '_connection_endpoint');
$registered_connection_endpoint_has = !empty($registered_connection_endpoint);
$registered_connection_endpoint = $registered_connection_endpoint_has ? $registered_connection_endpoint : get_rest_url();

$registered_connection_email = get_option($adminClass::flavour . '_connection_email');
$registered_connection_email_has = !empty($registered_connection_email);
$registered_connection_email = $registered_connection_email_has ? $registered_connection_email : get_bloginfo('admin_email');

$registered_connection_name = get_option($adminClass::flavour . '_connection_name');
$registered_connection_name_has = !empty($registered_connection_name);
$registered_connection_name = $registered_connection_name_has ? $registered_connection_name : get_bloginfo('name');

$registered_connection_saved = $registered_connection_key_has && $registered_connection_endpoint_has && $registered_connection_email_has && $registered_connection_name_has;

$google_ads_enable = get_option($adminClass::flavour . '_google_ads_enable');
$google_ads_account_id = get_option($adminClass::flavour . '_google_ads_account_id');
$google_ads_conversion_id = get_option($adminClass::flavour . '_google_ads_conversion_id');

?>

<h2><?php echo esc_html($adminClass::flavourLabel); ?></h2>

<div class="wrap">
  <?php if ($registered_connection_saved): ?>
      <a class="button button-primary" href="<?php menu_page_url( $adminClass::flavour . '_connect' ); ?>" target="_blank">Go To <?php echo esc_html($adminClass::flavourLabel); ?></a>
  <?php else: ?>
      <p>You must save your connection details before your first connection to <?php echo esc_html($adminClass::flavourLabel); ?>.</p>
  <?php endif; ?>

    <h2>Connection Settings</h2>
    <form method="post" action="options.php">
      <?php settings_fields( $adminClass::flavour . '-connection' ); ?>
      <?php do_settings_sections( $adminClass::flavour . '-connection' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Store Name</th>
                <td><input type="text" name="<?php echo esc_attr($adminClass::flavour); ?>_connection_name" <?php if ($registered_connection_name_has): ?>disabled <?php endif; ?>value="<?php echo esc_attr($registered_connection_name); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Email address</th>
                <td><input type="text" name="<?php echo esc_attr($adminClass::flavour); ?>_connection_email" <?php if ($registered_connection_email_has): ?>disabled <?php endif; ?>value="<?php echo esc_attr($registered_connection_email); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Account Key</th>
                <td><input type="text" name="<?php echo esc_attr($adminClass::flavour); ?>_connection_key" <?php if ($registered_connection_key_has): ?>disabled <?php endif; ?>value="<?php echo esc_attr($registered_connection_key); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">API endpoint</th>
                <td><input type="text" name="<?php echo esc_attr($adminClass::flavour); ?>_connection_endpoint" <?php if ($registered_connection_endpoint_has): ?>disabled <?php endif; ?>value="<?php echo esc_attr($registered_connection_endpoint); ?>" /></td>
            </tr>
        </table>

      <?php if (!$registered_connection_saved): ?>
        <?php submit_button(); ?>
      <?php endif; ?>
    </form>

    <?php if ($registered_connection_saved): ?>
        <h2>Google Ads Settings</h2>
        <div style="background: #fff; border: 1px solid #ccd0d4; border-left-width: 4px; margin: 5px 15px 2px; padding: 1px 12px;">
            <p>
                Enable the Google Ads Settings if:
                <ol style="list-style: inside;">
                    <li>You are using Google Ads from <?php echo esc_html($adminClass::flavourLabel); ?> and</li>
                    <li>You have not added the Global Site Tag and Event Snippet for tracking Google ads via Omnivore in another way</li>
                </ol>
            </p>
        </div>
        <form method="post" action="options.php">
            <?php settings_fields( $adminClass::flavour . '-google-ads' ); ?>
            <?php do_settings_sections( $adminClass::flavour . '-google-ads' ); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Enable</th>
                    <td><input type="checkbox" name="<?php echo esc_attr($adminClass::flavour); ?>_google_ads_enable" <?php print boolval($google_ads_enable) ? 'checked' : ''; ?>/></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Account ID</th>
                    <td><input type="text" name="<?php echo esc_attr($adminClass::flavour); ?>_google_ads_account_id" value="<?php echo esc_attr($google_ads_account_id); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Conversion Event ID</th>
                    <td><input type="text" name="<?php echo esc_attr($adminClass::flavour); ?>_google_ads_conversion_id" value="<?php echo esc_attr($google_ads_conversion_id); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    <?php endif; ?>

</div>
