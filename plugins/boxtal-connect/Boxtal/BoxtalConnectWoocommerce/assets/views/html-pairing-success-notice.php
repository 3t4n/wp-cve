<?php
/**
 * Pairing success notice rendering
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Assets\Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Boxtal\BoxtalConnectWoocommerce\Branding;

?>

<div class="<?php echo esc_html( Branding::$branding_short ); ?>-notice <?php echo esc_html( Branding::$branding_short ); ?>-success">
	<a class="<?php echo esc_html( Branding::$branding_short ); ?>-close-link <?php echo esc_html( Branding::$branding_short ); ?>-hide-notice" data-action="<?php echo esc_html( Branding::$branding_short ); ?>_hide_notice" rel="pairing">x</a>
	<h2><?php esc_html_e( 'Congratulations, your shop is connected !', 'boxtal-connect' ); ?></h2>
	<p><?php esc_html_e( 'Finalize your settings to start shipping', 'boxtal-connect' ); ?></p>
	<p>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . Branding::$branding . '-connect-settings' ) ); ?>" class="button-primary" rel="pairing">
			<?php esc_html_e( 'Finalize the settings', 'boxtal-connect' ); ?>
		</a>
	</p>
</div>
