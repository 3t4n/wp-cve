<?php
/**
 * Admin order edit page tracking rendering
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Assets\Views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Boxtal\BoxtalConnectWoocommerce\Branding;

?>
<div class="<?php echo esc_html( Branding::$branding_short ); ?>-order-tracking">
	<?php
		require 'html-admin-order-tracking.php';
	?>
</div>
