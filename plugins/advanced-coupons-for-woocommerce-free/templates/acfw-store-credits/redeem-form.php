<?php
/**
 * Store credits redeem form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/acfw-store-credits/redeem-form.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package ACFWF\Templates
 * @version 4.2.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}?>

<div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $classes ); ?>">
    <p><?php echo esc_html__( 'Enter the amount of store credits you want to apply as discount for this order.', 'advanced-coupons-for-woocommerce-free' ); ?></p>
    <input type="text" class="input-text wc_input_price" value="<?php echo esc_attr( $value ?? '' ); ?>" placeholder="<?php echo esc_html__( 'Enter amount', 'advanced-coupons-for-woocommerce-free' ); ?>" />
    <button type="button" class="button alt"><?php echo esc_html__( 'Apply', 'advanced-coupons-for-woocommerce-free' ); ?></button>
</div>
