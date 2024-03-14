<?php
/**
 * Advanced Coupon - send coupon email.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-advanced-coupon.php.
 *
 * @version 4.5.3
 */
defined( 'ABSPATH' ) || exit;

$base       = get_option( 'woocommerce_email_base_color' );
$base_text  = wc_light_or_dark( $base, '#202020', '#ffffff' );
$coupon_url = 'yes' !== $coupon->get_advanced_prop( 'disable_url_coupon' ) ? $coupon->get_coupon_url() : get_permalink( wc_get_page_id( 'shop' ) );

do_action( 'acfw_email_header', $email_heading, $email );?>

<p style="text-align: center;"><?php echo wp_kses_post( $email->get_message() ); ?></p>

<h3 style="text-align: center; text-transform: uppercase">
    <span style="display:inline-block; padding: 10px 20px; margin: 0 auto 20px; border: 1px dotted #636363; ">
        <?php echo esc_html( $coupon->get_code() ); ?>
    </span>
</h3>

<p style="text-align:center;">
<a href="<?php echo esc_url( $coupon_url ); ?>" style="cursor: pointer;display: inline-block;padding: 0.6em 3.5em;text-decoration: none;font-weight: 600;background-color: <?php echo esc_attr( $base ); ?>;border-color: <?php echo esc_attr( $base_text ); ?>;color: #ffffff;font-size: 1.2em;">
        <?php echo esc_html( $email->get_button_text() ); ?>
    </a>
</p>

<?php if ( $additional_content ) : ?>
    <div style="text-align: center;">
        <?php echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) ); ?>
    </div>
<?php endif; ?>

<?php
do_action( 'acfw_email_footer', $email );
