<?php
/**
 * Setup wizard notice rendering
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Assets\Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Boxtal\BoxtalConnectWoocommerce\Branding;

?>
<div class="<?php echo esc_html( Branding::$branding_short ); ?>-notice <?php echo esc_html( Branding::$branding_short ); ?>-info">
	<a class="<?php echo esc_html( Branding::$branding_short ); ?>-close-link <?php echo esc_html( Branding::$branding_short ); ?>-hide-notice" data-action="<?php echo esc_html( Branding::$branding_short ); ?>_hide_notice" rel="setup-wizard">x</a>
	<h2><?php esc_html_e( 'Welcome to Boxtal!', 'boxtal-connect' ); ?></h2>
	<p><?php esc_html_e( 'The adventure begins in a few clicks', 'boxtal-connect' ); ?></p>
	<p>
		<a href="<?php echo esc_url( $notice->onboarding_link ); ?>" target="_blank" class="button-primary">
			<?php esc_html_e( 'Connect my shop', 'boxtal-connect' ); ?>
		</a>
	</p>
</div>
