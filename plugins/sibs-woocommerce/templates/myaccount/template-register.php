<?php
/**
 * Sibs Payments Form
 *
 * The file is for displaying the Sibs register form
 * Copyright (c) SIBS
 *
 * @package     Sibs/Templates
 * @located at  /template/ckeckout/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

?>
<?php if ( 'sibs_paypalsaved' === $payment_id ) : ?>

	<style>
		body, button.wpwl-button-brand {
			display:none;
		}
	</style>
	<script type="text/javascript">
		var wpwlOptions = {
			onReady: function() {
				jQuery( ".wpwl-form" ).submit();
			}
		}
	</script>
	<input type="submit" value="Submit" style="display:none" />

<?php else : ?>

	<h2 class="header-title header-center"><?php echo esc_attr( $form_title ); ?></h2>
	<script type="text/javascript">
		var wpwlOptions = {
				locale: "<?php echo esc_attr( strtolower( substr( get_bloginfo( 'language' ), 0, 2 ) ) ); ?>",
				style: "card",
				onReady: function() {
					var buttonCancel = "<a href='<?php echo esc_attr( $url_config['cancel_url'] ); ?>' class='wpwl-button btn_cancel'><?php echo esc_attr( __( 'FRONTEND_BT_CANCEL', 'wc-sibs' ) ); ?></a>";
					var buttonConfirm = "<?php echo esc_attr( $confirm_button ); ?></a>";
					var ttTestMode = "<div class='testmode'><?php echo esc_attr( __( 'FRONTEND_TT_TESTMODE', 'wc-sibs' ) ); ?></div>";
					var ttRegistration = "<div class='register-tooltip'><?php echo esc_attr( __( 'FRONTEND_TT_REGISTRATION', 'wc-sibs' ) ); ?></div>";
					jQuery( "form.wpwl-form" ).find( ".wpwl-button" ).before( buttonCancel );
					jQuery( ".wpwl-button-pay" ).html( buttonConfirm );
					jQuery( ".wpwl-container" ).after( ttRegistration );
					<?php if ( 'TEST' === $register_parameters['server_mode'] ) : ?>
						jQuery( ".wpwl-container" ).wrap( "<div class='frametest'></div>" );
						jQuery( '.wpwl-container' ).before( ttTestMode );
					<?php endif; ?>
				},
				registrations: {
					hideInitialPaymentForms: false,
					requireCvv: false
				}
			}
	</script>
<?php endif; ?>

<form action="<?php echo esc_attr( $url_config['return_url'] ); ?>" class="paymentWidgets"><?php echo esc_attr( $payment_brand ); ?></form>
