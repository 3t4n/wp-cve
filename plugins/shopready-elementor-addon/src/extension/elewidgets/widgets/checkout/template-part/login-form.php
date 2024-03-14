<?php
/**
 * Login Form for checkout 
 * @since 1.0
 * 
 */

	defined( 'ABSPATH' ) || exit;

	if ( is_user_logged_in() && !shop_ready_is_elementor_mode() ) {

		return;
	}

	$redirect = wc_get_checkout_url();
	$message  = $settings[ 'message' ];
	$hidden   = $settings[ 'wready_form_collapsible' ] == 'yes' ? true : false;

	if( $settings['redirect_custom'] == 'yes' && $settings['website_link']['url'] !='' ){
		$redirect = $settings['website_link']['url'];
	}

?>
    <?php if( $hidden ): ?>
		<div class="woocommerce-form-login-toggle">
			<?php wc_print_notice( $settings['return_customer']  . ' <a href="#" class="woo-ready-show-login">' . $settings["toggle_content"] . '</a>', 'notice' ); ?>
		</div>
     <?php endif; ?>

	<form class="woocommerce-form woocommerce-form-login login" method="post" <?php echo wp_kses_post( $hidden  ? 'style="display:none;"' : ''); ?>>

		<?php echo wp_kses_post( $message  ? wp_kses_post(wpautop( wptexturize( $message ) )) : ''); // @codingStandardsIgnoreLine ?>

		<p class="form-row form-row-first">
			<label for="username"><?php esc_html_e( 'Username or email', 'shopready-elementor-addon' ); ?>&nbsp;<span class="required">*</span></label>
			<input type="text" class="input-text" name="username" id="username" autocomplete="username" />
		</p>
		<p class="form-row form-row-last">
			<label for="password"><?php esc_html_e( 'Password', 'shopready-elementor-addon' ); ?>&nbsp;<span class="required">*</span></label>
			<input class="input-text" type="password" name="password" id="password" autocomplete="current-password" />
		</p>
		<div class="clear"></div>

		<?php do_action( 'woocommerce_login_form' ); ?>

		<p class="form-row">
			<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
				<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'shopready-elementor-addon' ); ?></span>
			</label>
			<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
			<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ); ?>" />
			<button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Login', 'shopready-elementor-addon' ); ?>"><?php esc_html_e( 'Login', 'shopready-elementor-addon' ); ?></button>
		</p>
		<?php if($settings['wready_form_lost_pass'] == 'yes'): ?>
			<p class="lost_password">
				<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'shopready-elementor-addon' ); ?></a>
			</p>
		<?php endif; ?>
	</form>