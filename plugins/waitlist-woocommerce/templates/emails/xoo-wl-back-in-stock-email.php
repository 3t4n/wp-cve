<?php
/**
 *
 * This template can be overridden by copying it to yourtheme/templates/waitlist-woocommerce/emails/xoo-wl-back-in-stock-email.php.
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

<?php do_action( 'xoo_wl_email_header', $emailObj ); ?>

<table cellpadding="0" border="0" cellspacing="0" width="100%">
	<?php if( $heading ): ?>
		<tr>
			<td style="color: #000000; font-weight: bold; font-size: 19px; padding-bottom: 40px;" align="center"><?php echo esc_html( $heading ); ?></td>
		</tr>
	<?php endif; ?>

	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" width="100%" align="center">
				<tr>
					<td align="center" width="100%">

						<?php if( $show_pimage ): ?>
						<table width="200" class="xoo-wl-table-full" align="right" cellpadding="0" border="0" cellspacing="0">
							<tr>
								<td align="center">
									<img height="auto" width="100%"  border="0" alt="<?php echo esc_attr( $product_name ); ?>" src="<?php echo esc_url( $product_image );  ?>" style="display: block; margin-left: auto; margin-right: auto;" class="xoo-wl-em-pimg"/>
								</td>
							</tr>
						</table>
						<?php endif; ?>

						<table width="325" class="xoo-wl-table-full xoo-wl-bist-content" align="<?php echo $show_pimage ? 'left' : 'center' ?>" cellpadding="0" border="0" cellspacing="0" style="padding: 20px 10px">

							<tr>
								<td>
									<?php echo wp_kses_post( $body_text ); ?>
								</td>
							</tr>
							
							<tr>
								<td style="padding-top: 15px;" align="center">
									<?php $emailObj->button_markup( $buy_now_text, $product_link ); ?>
								</td>
							</tr>

						</table>

					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?php do_action( 'xoo_wl_email_footer', $emailObj ); ?>