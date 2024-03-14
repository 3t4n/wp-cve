<?php
/**
 * Email template
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
 * @var string $email_heading The email heading.
 * @var YITH_Pre_Order_Confirmed_Email $email The WC_Email object.
 */

do_action( 'woocommerce_email_header', $email_heading, $email );

$body     = ! empty( $email->email_body ) ? $email->email_body : '';
$_product = wc_get_product( $email->data['product'] );
$_order   = $email->data['order'];
$item_id  = $email->data['item_id'];

$product_table = wc_get_template_html(
	'emails/ywpo-product-table.php',
	array(
		'_product' => $_product,
		'_order'   => $_order,
		'item_id'  => $item_id,
		'context'  => 'pre-order-confirmed',
	),
	'',
	YITH_WCPO_TEMPLATE_PATH
);

do_action( 'ywpo_pre_order_confirmed_email_before_body', $email, $body, $_product, $_order, $item_id, $product_table );

echo wp_kses_post(
	apply_filters(
		'ywpo_pre_order_confirmed_email_body',
		'<p>' . str_replace( array( '{product_table}' ), array( $product_table ), nl2br( $body ) ) . '</p>',
		$email,
		$body,
		$_product,
		$_order,
		$item_id,
		$product_table
	)
);

do_action( 'ywpo_pre_order_confirmed_email_after_body', $email, $body, $_product, $_order, $item_id, $product_table );

do_action( 'woocommerce_email_footer', $email );
