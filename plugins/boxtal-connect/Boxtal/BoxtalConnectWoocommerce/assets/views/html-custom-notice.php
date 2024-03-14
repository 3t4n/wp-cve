<?php
/**
 * Custom notice rendering
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Assets\Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Boxtal\BoxtalConnectWoocommerce\Branding;

?>
<div class="<?php echo esc_html( Branding::$branding_short ); ?>-notice <?php echo esc_attr( Branding::$branding_short . '-' . $notice->status ); ?>">
	<?php echo esc_html( $notice->message ); ?>

	<a class="button-secondary <?php echo esc_html( Branding::$branding_short ); ?>-hide-notice" data-action="<?php echo esc_html( Branding::$branding_short ); ?>_hide_notice" rel="<?php echo esc_attr( $notice->key ); ?>">
		<?php esc_html_e( 'Hide this notice', 'boxtal-connect' ); ?>
	</a>
</div>
