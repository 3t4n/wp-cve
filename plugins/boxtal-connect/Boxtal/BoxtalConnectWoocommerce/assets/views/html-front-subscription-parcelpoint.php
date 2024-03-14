<?php
/**
 * Front subscription parcelpoint rendering
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Assets\Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Boxtal\BoxtalConnectWoocommerce\Branding;

?>
<div class="<?php echo esc_html( Branding::$branding_short ); ?>-subscription-parcelpoint">
	<h2><?php esc_html_e( 'Chosen pickup point', 'boxtal-connect' ); ?></h2>

	<?php
		require 'html-admin-order-parcelpoint.php';
	?>
</div>
