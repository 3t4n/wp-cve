<?php
//Invoice error email

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<p><?php printf( esc_html__( 'Document(%2$s - %3$s) for order #%1$s created successfully created. The PDF file is attached to this email.', 'wc-szamlazz' ), $order->get_order_number(), $document_type, $document_name ); ?></p>

<ul>
	<li><?php echo sprintf(__('<strong>Document type:</strong> %s', 'wc-szamlazz'), esc_html($document_type)); ?></li>
	<li><?php echo sprintf(__('<strong>Document name:</strong> %s', 'wc-szamlazz'), esc_html($document_name)); ?></li>
	<li><?php echo sprintf(__('<strong>Document download link:</strong> %s', 'wc-szamlazz'), '<a href="'.esc_url($document_link).'">'.esc_html($document_link).'</a>'); ?></li>
</ul>

<p><?php esc_html_e( 'As a reminder, here are your order details:', 'wc-szamlazz' ); ?></p>

<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

?>
<?php

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
