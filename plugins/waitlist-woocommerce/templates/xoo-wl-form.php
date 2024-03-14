<?php
/**
 *
 * This template can be overridden by copying it to yourtheme/templates/waitlist-woocommerce/xoo-wl-form.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://docs.xootix.com/waitlist-for-woocommerce/
 * @version 2.4
 */

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


?>

<?php do_action( 'xoo_wl_before_form' ); ?>

<div class="xoo-wl-header">
	<span class="xwh-heading"><?php echo esc_html( xoo_wl_helper()->get_general_option( 'txt-head' ) ); ?></span>
	<span class="xwh-subheading"><?php echo esc_html( xoo_wl_helper()->get_general_option( 'txt-subhead' ) ); ?></span>
</div>

<?php if( !is_user_logged_in() && xoo_wl_helper()->get_general_option( 'm-en-guest' ) !== "yes" ): ?>

	<div class="xoo-wl-notloggedin-cont">
		<span><?php _e( 'You need to Login for joining waitlist.','waitlist-woocommerce' ); ?></span>
		<div class="xoo-wl-nlc-btns">

			<a target="_blank" href="<?php echo esc_url( get_permalink(get_option('woocommerce_myaccount_page_id')) ); ?>" class="button xoo-wl-action-btn xoo-wl-register-btn"><?php _e( 'Login', 'waitlist-woocommerce' ); ?></a>

			<a target="_blank" href="<?php echo esc_url( get_permalink(get_option('woocommerce_myaccount_page_id')) ); ?>" class="button xoo-wl-action-btn xoo-wl-register-btn"><?php _e( 'Register', 'waitlist-woocommerce' ); ?></a>

		</div>
	</div>

<?php else: ?>

	<div class="xoo-wl-notices"></div>

	<form class="xoo-wl-form" method="post">

		<?php do_action( 'xoo_wl_form_start' ); ?>

		<?php xoo_wl()->aff->fields->get_fields_layout(); ?>

		<input type="hidden" name="_xoo_wl_form" value="1">

		<input type="hidden" name="_xoo_wl_product_id" value="<?php echo (int) isset( $product_id ) ? $product_id : 0; ?>">

		<?php do_action('xoo_wl_add_fields'); ?>

		<button type="submit" class="xoo-wl-submit-btn xoo-wl-action-btn button btn"><?php echo esc_html( xoo_wl_helper()->get_general_option( 'txt-btn' ) ); ?></button>

		<?php do_action( 'xoo_wl_form_end' ); ?>

	</form>
<?php endif; ?>

<?php do_action( 'xoo_wl_after_form' ); ?>