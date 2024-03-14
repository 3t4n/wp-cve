<?php
/**
 * Configuration failure notice rendering
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Assets\Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Boxtal\BoxtalConnectWoocommerce\Branding;

?>

<div class="<?php echo esc_html( Branding::$branding_short ); ?>-notice <?php echo esc_html( Branding::$branding_short ); ?>-warning">
	<?php
	/* translators: 1) Company name 2) Company name */
	echo sprintf( esc_html__( 'There was a problem initializing the %s Connect plugin. You should contact our support team.', 'boxtal-connect' ), esc_html( Branding::$company_name ) );
	?>
</div>
