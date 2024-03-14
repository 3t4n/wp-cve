<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

if ( $payment_parameters['payment_brand'] == 'MBWAY' ){
	echo '<p> ' . __( 'Keep your phone close by as it will be necessary to complete the payment.', 'wc-sibs' ) . '</p>';
}

$isloggin = false;

if ( is_user_logged_in() ) {
	$isloggin = true;
}

?>

<script type="text/javascript">
	var wpwlOptions = {
		locale: "<?php echo esc_attr( strtolower( substr( get_bloginfo( 'language' ), 0, 2 ) ) ); ?>",
		style: "card",
		paymentTarget:"_top",
		onReady: function() {
			var sibsLogo = '<div class="sibs_logo"><img src="<?php echo esc_attr( $url_config['plugins_url'] . '/assets/images/sibs_logo.png' ); ?>" alt="SIBS" style="height:50px; margin-bottom:15px;" /></div>';
			var buttonCancel = "<a href='<?php echo esc_attr( $url_config['cancel_url'] ); ?>' class='wpwl-button btn_cancel'><?php echo esc_attr( __( 'FRONTEND_BT_CANCEL', 'wc-sibs' ) ); ?></a>";
			var ttTestMode = "<div class='testmode'><?php echo esc_attr( __( 'FRONTEND_TT_TESTMODE', 'wc-sibs' ) ); ?></div>";

			var isUserLoggin = "<?php echo $isloggin ?>"

			if ( isUserLoggin ) {
				var createRegistrationHtml = '<div class="customLabel"><?php echo esc_attr( __( "Store payment details?", "wc-sibs" ) ); ?></div><div class="customInput"><input type="checkbox" name="createRegistration" value="true" /></div>';
				jQuery('form.wpwl-form-card').find('.wpwl-button').before(createRegistrationHtml);
			}

			jQuery( "form.wpwl-form" ).find( ".wpwl-button" ).before( buttonCancel );
			jQuery( "form.wpwl-form" ).prepend(sibsLogo);
			<?php if (esc_attr( strtolower( substr( get_bloginfo( 'language' ), 0, 2 ) ) ) === "pt") : ?>
				jQuery("form.wpwl-form").find("input.wpwl-control.wpwl-control-expiry").attr("placeholder", "MM / AA");
			<?php endif; ?>
			<?php if ( 'TEST' === $settings['server_mode'] ) : ?>
				jQuery( ".wpwl-container" ).wrap( "<div class='frametest'></div>" );
				jQuery( ".wpwl-container" ).before( ttTestMode );
			<?php endif; ?>
		},
		registrations: {
			hideInitialPaymentForms: false,
			requireCvv: true
		}
	}
</script>

<form action="<?php echo esc_attr( $url_config['return_url'] ); ?>" class="paymentWidgets"><?php echo esc_attr( $payment_parameters['payment_brand'] ); ?></form>
