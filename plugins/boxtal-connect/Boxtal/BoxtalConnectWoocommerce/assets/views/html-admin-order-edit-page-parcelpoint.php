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

$network       = $parcelpoint->network;
$networks_name = isset( $parcelpoint_networks->$network )
	? implode( ', ', $parcelpoint_networks->$network ) : null;

?>
<div class="<?php echo esc_html( Branding::$branding_short ); ?>-order-parcelpoint">
	<p>
		<?php
		echo wp_kses(
			sprintf(
			/* translators: %1$s : parcelpoint code, %2$s : parcelpoint network name */
				__( 'Your client chose the pickup point %1$s from %2$s.', 'boxtal-connect' ),
				'<b>' . $parcelpoint->code . '</b>',
				$networks_name
			),
			array( 'b' => array() )
		);
		?>
	</p>
	<?php
		require 'html-admin-order-parcelpoint.php';
	?>
</div>
