<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}

?>

<script type="text/javascript">
	var wpwlOptions = {
		locale: "<?php echo esc_attr( strtolower( substr( get_bloginfo( 'language' ), 0, 2 ) ) ); ?>",
		style: "card",
		onReady: function() {
			var buttonCancel = "<a href='<?php echo esc_attr( $url_config['cancel_url'] ); ?>' class='wpwl-button btn_cancel'><?php echo esc_attr( __( 'FRONTEND_BT_CANCEL', 'wc-sibs' ) ); ?></a>";
			var ttTestMode = "<div class='testmode'><?php echo esc_attr( __( 'FRONTEND_TT_TESTMODE', 'wc-sibs' ) ); ?></div>";
			var clearFloat = "<div style='clear:both'></div>"
			var btnPayNow = "<button type='submit' name='pay' class='wpwl-button wpwl-button-pay'><?php echo esc_attr( __( 'FRONTEND_BT_PAYNOW', 'wc-sibs' ) ); ?></button>";
			var headerWidget = "<h3 id='deliveryHeader' style='text-align:center' style='text-align:center'><?php echo esc_attr( __( 'FRONTEND_RECURRING_WIDGET_HEADER2', 'wc-sibs' ) ); ?>";
			jQuery( 'form.wpwl-form' ).find( '.wpwl-button' ).after( buttonCancel );
			jQuery( 'form.wpwl-form-virtualAccount-PAYPAL' ).find( '.wpwl-button-brand' ).wrap( "<div class='payment-brand'></div>" );
			jQuery( 'form.wpwl-form-virtualAccount-PAYPAL' ).find( '.btn_cancel' ).after( btnPayNow );
			jQuery( 'form.wpwl-form-virtualAccount-PAYPAL' ).find( '.wpwl-button-pay' ).after( clearFloat );
			<?php if ( 'TEST' === $settings['server_mode'] ) : ?>
				jQuery( ".wpwl-container" ).wrap( "<div class='frametest'></div>" );
				jQuery( ".wpwl-container" ).before( ttTestMode );
			<?php endif; ?>
			<?php if ( $is_recurring && $is_one_click_payments ) : ?>
				var headerWidget = "<h3 id='deliveryHeader' style='text-align:center' class='blockHead'><?php echo esc_attr( __( 'FRONTEND_RECURRING_WIDGET_HEADER2', 'wc-sibs' ) ); ?></h3>";
				jQuery( "#wpwl-registrations" ).after( headerWidget );
			<?php endif; ?>
		},
		registrations: {
			hideInitialPaymentForms: false,
			requireCvv: false
		}
	}
</script>
<?php if ( $registered_paypal ) : ?>
	<script>
		jQuery( document ).ready( function() {
			jQuery( "input[type=radio][name=registrationId]" ).on( "click", function() {
			jQuery( ".wpwl-group-registration" ).removeClass( "wpwl-selected" );
			jQuery( ".regid"+this.value ).addClass( "wpwl-selected" );
			} );
		} );
	</script>
	<h3 id="deliveryHeader" style="text-align:center" class="blockHead"><?php echo esc_attr( __( 'FRONTEND_RECURRING_WIDGET_HEADER1', 'wc-sibs' ) ); ?></h3>
	<div id="wpwl-registrations">
		<div class="wpwl-container wpwl-container-registration wpwl-container-virtualAccount-PAYPAL wpwl-clearfix" style="display: block;">
			<form class="wpwl-form wpwl-form-registrations wpwl-form-has-inputs wpwl-clearfix" action="<?php echo esc_attr( $url_config['return_url'] ) . '&paypal_repeated=true'; ?>" method="POST" lang="en" accept-charset="UTF-8" data-action="submit-registration">
			<?php foreach ( $registered_paypal as $key => $value ) : ?>
				<?php if ( 1 === $value['payment_default'] ) : ?>
					<div class="regid<?php echo esc_attr( $value['reg_id'] ); ?> wpwl-group wpwl-group-registration wpwl-clearfix wpwl-selected ">
				<?php else : ?>
					<div class="regid<?php echo esc_attr( $value['reg_id'] ); ?> wpwl-group wpwl-group-registration wpwl-clearfix ">
				<?php endif; ?>
					<label class="wpwl-registration">
						<div class="wpwl-wrapper-registration wpwl-wrapper-registration-registrationId">
						<?php if ( 1 === $value['payment_default'] ) : ?>
							<input type="radio" name="registrationId" value="<?php echo esc_attr( $value['reg_id'] ); ?>" checked="checked" data-action="change-registration">
						<?php else : ?>
							<input type="radio" name="registrationId" value="<?php echo esc_attr( $value['reg_id'] ); ?>" data-action="change-registration">
						<?php endif; ?>
						</div>
						<div class="wpwl-wrapper-registration wpwl-wrapper-registration-details paypal-detail">
							<div class="wpwl-wrapper-registration wpwl-wrapper-registration-email"><?php echo esc_attr( $value['email'] ); ?></div>
						</div>
						<div class="wpwl-wrapper-registration wpwl-wrapper-registration-cvv"></div>
					</label>
					</div>
			<?php endforeach; ?>
					<div class="wpwl-group wpwl-group-submit wpwl-clearfix">
						<div class="wpwl-wrapper wpwl-wrapper-submit">
							<button type="submit" name="pay" class="wpwl-button wpwl-button-pay"><?php echo esc_attr( __( 'FRONTEND_BT_PAYNOW', 'wc-sibs' ) ); ?></button>
						</div>
					</div>
			</form>
			<iframe name="registrations-target" class="wpwl-target" src="about:blank" frameborder="0"></iframe>
		</div>
	</div>
<?php else : ?>
	<h3 id="deliveryHeader" style="text-align:center" style="text-align:center"><?php echo esc_attr( __( 'FRONTEND_MC_PAYANDSAFE', 'wc-sibs' ) ); ?></h3>
<?php endif; ?>
	<form action="<?php echo esc_attr( $url_config['return_url'] ); ?>" class="paymentWidgets"><?php echo esc_attr( $payment_parameters['payment_brand'] ); ?></form>
