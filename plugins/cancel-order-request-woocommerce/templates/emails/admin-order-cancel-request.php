<?php
/**
 * Admin new order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/admin-new-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails/HTML
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header_corw', $email_heading, $email ); ?>

<?php /* translators: %s: Customer billing full name */ ?>
<?php if(!empty($predefined_reason)): ?>
<p><strong><?php echo esc_html__('Selected reason:', 'cancel-order-request-woocommerce'); ?> </strong><?php echo esc_html($predefined_reason);  ?></p>
<?php endif; ?>

<?php if(!empty($cancellation_reason)): ?>
<p><strong><?php echo esc_html__('Order cancellation reason:', 'cancel-order-request-woocommerce'); ?> </strong><?php echo esc_html($cancellation_reason);  ?></p>
<?php endif; ?>
<?php

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );