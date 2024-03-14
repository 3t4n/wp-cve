<?php
/**
 * Invalid Login Credentials partial
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

$link = sprintf( wp_kses( __( 'Please provide your Reamaze Login and API Key <a href="%s">here</a>.', 'reamaze' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $reamazeSettingsURL . '&tab=personal' ) );
?>
<div style="text-align: center; padding: 20px;">
  <h2><?php echo __( "Reamaze Login Credentials Invalid", 'reamaze'); ?><h2>
  <p><?php echo esc_url( $link ) ?></p>
</div>
<?php
