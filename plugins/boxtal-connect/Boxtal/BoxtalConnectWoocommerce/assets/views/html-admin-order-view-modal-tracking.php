<?php
/**
 * Admin order view modal tracking rendering
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Assets\Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Boxtal\BoxtalConnectWoocommerce\Branding;

?>
<div class="<?php echo esc_html( Branding::$branding_short ); ?>-order-tracking">
	<h2><?php esc_html_e( 'Tracking details', 'boxtal-connect' ); ?></h2>
	<?php
		require 'html-admin-order-tracking.php';
	?>
</div>
