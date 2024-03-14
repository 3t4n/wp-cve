<?php
/**
 * Pairing update notice rendering
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Assets\Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Boxtal\BoxtalConnectWoocommerce\Branding;

?>

<div class="<?php echo esc_html( Branding::$branding_short ); ?>-notice <?php echo esc_html( Branding::$branding_short ); ?>-warning">
	<?php esc_html_e( 'Security alert: someone is trying to pair your site with Boxtal. Was it you?', 'boxtal-connect' ); ?>
	<button class="button-secondary <?php echo esc_html( Branding::$branding_short ); ?>-pairing-update-validate" <?php echo esc_html( Branding::$branding_short ); ?>-pairing-update-validate="1" href="#"><?php esc_html_e( 'yes', 'boxtal-connect' ); ?></button>
	<button class="button-secondary <?php echo esc_html( Branding::$branding_short ); ?>-pairing-update-validate" <?php echo esc_html( Branding::$branding_short ); ?>-pairing-update-validate="0" href="#"><?php esc_html_e( 'no', 'boxtal-connect' ); ?></button>
</div>
