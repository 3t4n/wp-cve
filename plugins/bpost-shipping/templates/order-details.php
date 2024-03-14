<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * @var string[] $bpost_meta
 * @var bool $has_shipping_map
 * @var string $map_provider
 * @var string $geo6_map_url
 */

?>
<h3><?php echo bpost__( 'bpost shipping details' ); ?></h3>

<table class="shop_table order_details">
	<tfoot>
	<?php
	foreach ( $bpost_meta as $bpost_meta_item ) {
		?>
		<tr>
			<th scope="row"><?php echo esc_html($bpost_meta_item['translation']); ?> :</th>
			<td><?php echo esc_html($bpost_meta_item['value']); ?></td>
		</tr>
		<?php
	}
	?>

	<?php if ( $has_shipping_map ) { ?>
		<tr>
			<th scope="row"><?php echo bpost__( 'Shipping address' ) ?> :</th>
			<?php switch ( $map_provider ) {
				case WC_BPost_Shipping_Order_Details_Controller::MAP_PROVIDER_GOOGLE: ?>
					<td>
						<div id="bpost-shipping-map"></div>
					</td>
					<?php
					break;

				case WC_BPost_Shipping_Order_Details_Controller::MAP_PROVIDER_GEO6:
					?>
					<td></td>
					</tr>
					<tr>
					<td colspan="2">
						<embed src="<?php echo esc_url($geo6_map_url); ?>" height="700px" width="100%"></embed>
					</td>
					<?php
					break;
			} ?>
		</tr>
	<?php } ?>
	</tfoot>
</table>
