<?php
/**
 * Admin View: Dashboard
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

$reamazeAccountId = get_option( 'reamaze_account_id' );
$reamazeSSOKey = get_option('reamaze_account_sso_key');

$path = isset($_GET['path']) && !empty($_GET['path']) ? sanitize_url($_GET['path']) : '/admin';
if ( ! strpos( $path, '?' ) ) {
  $path .= '?1=1';
}

global $reamaze;
$reamazeSettingsURL = menu_page_url( 'reamaze-settings', false );

if ( ! $reamazeAccountId || ! $reamazeSSOKey ) {
  $protocol = ( ( ! empty($_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) || $_SERVER['SERVER_PORT'] == 443 ) ? 'https://' : 'http://';
  $current_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

  $base_url = apply_filters( 'reamaze_signup_link', 'https://www.reamaze.com/extensions/identify/wordpress?referrer=wordpress' );
  $btn_link = $base_url . '&bounce_path=/admin/apps/wordpress/oauth_callback?return_url=' . $current_url;
  $btn_text = 'Connect or create your Re:amaze account';
} else {
  $btn_link = 'https://' . esc_attr( $reamazeAccountId ) . '.reamaze.com/admin';
  $btn_text = 'Open Re:amaze dashboard';
}

?>

<div id="reamaze-admin-welcome">
  <section class="hero" style="background: url(<?php echo esc_url( $reamaze->plugin_url() . '/assets/images/gravel.jpg' ); ?>)">
    <img alt="Reamaze" src="<?php echo esc_url( $reamaze->plugin_url() . '/assets/images/logo.png' ); ?>" style="height: 48px; margin: 20px auto;" />

    <h1 style="font-size: 30px;"><?php echo __( 'Better Conversations. Happier Customers.', 'reamaze' ); ?></h1>
    <h3><?php echo __( 'Get your team talking to customers, beyond just email.', 'reamaze' ); ?></h3>
    <p>
      <a class="button button-primary button-hero" target="_blank" href=<?php echo esc_url( $btn_link ) ?>><?php echo __( $btn_text, 'reamaze' ); ?></a>
    </p>
  </section>
  <div class="triple-hr"></div>
</div>
