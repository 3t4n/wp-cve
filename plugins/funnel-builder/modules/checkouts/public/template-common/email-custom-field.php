<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 3/22/19
 * Time: 12:03 PM
 */
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
/**
 * @var $order WC_Order;
 */


$wfacp_id = $order->get_meta( '_wfacp_post_id' );
if ( empty( $wfacp_id ) || $wfacp_id == 0 ) {
	return;
}
$custom_field = WFACP_Common::get_checkout_fields( $wfacp_id );
if ( empty( $custom_field ) || ! isset( $custom_field['advanced'] ) || empty( $custom_field['advanced'] ) ) {
	return;
}
$html = '';
foreach ( $custom_field['advanced'] as $key => $field ) {
	if ( ! ( isset( $field['show_custom_field_at_email'] ) && wc_string_to_bool( $field['show_custom_field_at_email'] ) ) ) {
		continue;
	}
	$meta_value = WFACP_Common::map_meta_value_for_custom_fields( $order->get_meta( $key ), $field );
	if ( '' !== $meta_value || apply_filters( 'wfacp_print_blank_custom_field', false, $order, $wfacp_id ) ) {
		$meta_value = apply_filters( 'wfacp_email_custom_field_value', $meta_value, $key, $field );
		$html       .= sprintf( '<tr class="woocommerce-table__line-item order_item"><td class="product-name"><b>%s</b></td><td class="product-total">%s</td></tr>', ( $field['label'] ), ( $meta_value ) );
	}
}

if ( empty( $html ) ) {
	return;
}
?>
<div style="margin-bottom: 40px;">
    <table class="woocommerce-table woocommerce-table--order-details shop_table order_details wfacp_email_custom_field" style="color: #69696a;border: 1px solid #e5e5e5;vertical-align: middle;text-align: left;padding: 0px;width: 100%;">
		<?php echo $html ?>
    </table>
</div>
