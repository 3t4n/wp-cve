<?php
/**
 * Product table for emails.
 *
 * @package YITH\PreOrder\Templates\Emails
 * @author  YITH <plugins@yithemes.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Vars used on this template.
 *
 * @var WC_Product $_product The product object.
 * @var WC_Order   $_order The order object.
 * @var string     $item_id The order item ID.
 * @var string     $old_release_date The previous release date in timestamp format.
 * @var string     $new_release_date The new release date in timestamp format.
 * @var string     $context For which email template is the table.
 */

$item_release_date = ! empty( $item_id ) && $_order instanceof WC_Order ? $_order->get_item( $item_id )->get_meta( '_ywpo_item_for_sale_date' ) : '';
$item              = ! empty( $item_id ) ? new WC_Order_Item_Product( $item_id ) : '';
$item_price        = ! empty( $item_id ) ? wc_price( $item->get_total() ) : '';

$show_release_date = in_array( $context, array( 'pre-order-confirmed', 'new-pre-order' ), true );

$product_title = $_product->get_title();
if ( in_array( $context, array( 'pre-order-confirmed', 'new-pre-order' ), true ) ) {
	$product_title .= '&nbsp;&times;&nbsp;' . $item->get_quantity();
}

$product_release_date = ywpo_get_release_date( $_product );

$price = $_product->get_price();
if ( $item instanceof WC_Order_Item_Product && in_array( $context, array( 'pre-order-confirmed', 'new-pre-order' ), true ) ) {
	$price = (float) $item->get_total() + (float) $item->get_total_tax();
}

?>
	<table style="background-color: #f6f6f6;">
		<tr>
			<td style="padding: 20px;"><?php echo wp_kses_post( $_product->get_image( array( 120, 120 ) ) ); ?></td>
			<td style="padding: 20px; width: 75%;">
				<div style="margin-bottom: 10px;">
					<strong><?php echo esc_html( $product_title ); ?></strong>
				</div>
				<div style="margin-bottom: 10px;">
					<span><?php echo wp_kses_post( wc_price( $price ) ); ?></span>
				</div>
				<div style="margin-bottom: 10px; text-transform: uppercase; color: #bc501c; font-size: 10px;">
					<?php if ( ! empty( $item_release_date ) && $show_release_date ) : ?>
						<div>
							<strong><?php echo esc_html__( 'Availability date:', 'yith-pre-order-for-woocommerce' ); ?></strong>
							<span><?php echo '&nbsp;' . esc_html( apply_filters( 'ywpo_email_item_release_date_output', ywpo_print_date( $item_release_date ), $_product, $item_id, $item_release_date ) ); ?></span>
						</div>
					<?php endif; ?>
				</div>
			</td>
		</tr>
	</table>
<?php
