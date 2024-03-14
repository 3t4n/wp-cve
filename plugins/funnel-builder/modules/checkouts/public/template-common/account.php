<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}
$checkout       = WC()->checkout();
$account_Fields = $checkout->get_checkout_fields( 'account' );



?>
<?php if ( ! is_user_logged_in() && $checkout->is_registration_enabled() ) : ?>
    <div class="woocommerce-account-fields">
		<?php if ( ! $checkout->is_registration_required() ) : ?>
            <p class="form-row form-row-wide create-account">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox wfacp-form-control-label">
                    <input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1"/>
                    <span><?php _e( 'Create an account?', 'woocommerce' ); ?></span>
                </label>
            </p>
		<?php endif; ?>
		<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>
		<?php if ( count( $account_Fields ) > 0 ) : ?>
            <div class="create-account">
				<?php
				foreach ( $account_Fields as $key => $field ) :


					$field['input_class'][] = 'wfacp-create-account';
					$field['class'][]       = 'wfacp-form-control-wrapper';

					if ( $field['type'] != 'checkbox' ) {
						$field['input_class'][] = 'wfacp-form-control';
						$field['label_class'][] = 'wfacp-form-control-label';
					} else {
						$field['class'][] = 'wfacp_checkbox_field';
					}


					if ( 'account_password' == $key ) {
						$field['class'][] = 'wfacp-create-account-label';
					}
					?>
					<?php wfacp_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
				<?php endforeach; ?>
                <div class="clear"></div>
            </div>
		<?php endif; ?>
		<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
    </div>
<?php endif; ?>
