<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 3/22/19
 * Time: 12:03 PM
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * @var $order WC_Order;
 */


$wfacp_id = $order->get_meta( '_wfacp_post_id' );
if ( empty( $wfacp_id ) ) {
	return;
}
$custom_field = WFACP_Common::get_checkout_fields( $wfacp_id );
if ( empty( $custom_field ) || ! isset( $custom_field['advanced'] ) ) {
	return;
}

$html = '';
foreach ( $custom_field['advanced'] as $key => $field ) {
	$show_field = false;
	if ( isset( $field['show_custom_field_at_thankyou'] ) && wc_string_to_bool( $field['show_custom_field_at_thankyou'] ) ) {
		$show_field = true;
	}
	if ( false == $show_field ) {
		continue;
	}
	$options    = isset( $field['options'] ) ? $field['options'] : [];
	$meta_value = WFACP_Common::map_meta_value_for_custom_fields( $order->get_meta( $key ), $field );
	if ( $field['type'] == 'date' ) {
		$meta_value = date( 'd-m-Y', strtotime( $meta_value ) );
	}
	if ( '' !== $meta_value || apply_filters( 'wfacp_print_blank_custom_field', false, $order, $wfacp_id ) ) {
		$html .= sprintf( '<tr class="woocommerce-table__line-item order_item"><th class="product-name">%s</th><td class="product-total">%s</td>', ( $field['label'] ), ( $meta_value ) );
	}
}

if ( '' !== $html ) {
	?>
    <table class="woocommerce-table woocommerce-table--order-details shop_table order_details" style="margin-top:10px; ">
		<?php echo $html ?>
    </table>
	<?php
}
?>