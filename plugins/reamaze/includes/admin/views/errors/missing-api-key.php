<?php
/**
 * Missing API Key partial
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

$link = __( 'Please provide your Reamaze API Key <a href="%s">here</a>.', 'reamaze' );
$allowed_html = array( 'a' => array( 'href' => array() ) );
$link = wp_kses( $link, $allowed_html );
$link = sprintf( $link, esc_url( $reamazeSettingsURL . '&tab=personal' ) );
?>
<div style='text-align: center; padding: 20px;'>
  <h2><?php echo __( 'API Key Not Found', 'reamaze'); ?></h2>
  <p><?php echo $link; ?></p>
</div>
<?php
