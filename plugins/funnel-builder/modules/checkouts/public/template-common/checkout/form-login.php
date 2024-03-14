<?php
if ( ! defined( 'WFACP_TEMPLATE_DIR' ) ) {
	return '';
}


$message_text = __( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'woocommerce' );
$vars         = array(
	'message'  => apply_filters( 'wfacp_login_message', $message_text ),
	'redirect' => wc_get_page_permalink( 'checkout' ),
	'hidden'   => true,
);

extract( $vars );
$template = wfacp_template();
?>
<div class="wfacp-coupon-section clearfix">
    <div class="woocommerce-form-login-toggle">
		<?php wc_print_notice( apply_filters( 'woocommerce_checkout_login_message', __( 'Returning customer?', 'woocommerce' ) ) . ' <a href="#" class="showlogin">' . __( 'Click here to login', 'woocommerce' ) . '</a>', 'notice' ); ?>
    </div>

    <div class="wfacp-login-wrapper">
		<?php
		$temp_slug_h = $template->get_template_slug();
		?>
        <form class="woocommerce-form woocommerce-form-login login <?php echo $temp_slug_h; ?>_login_wrap <?php echo apply_filters( 'wfacp_form_login_classes', 'hidden-form' ); ?>" method="post" <?php echo ( $hidden ) ? 'style="display:none;"' : ''; ?> >
            <input type="hidden" id="wfacp_login_hidden" name="wfacp_login_hidden" value="1">
            <div class="wfacp-col-full login_sec_content">
				<?php do_action( 'woocommerce_login_form_start' ); ?>
				<?php echo ( $message ) ? wpautop( wptexturize( $message ) ) : ''; // @codingStandardsIgnoreLine  ?>
            </div>
            <p class="form-row form-row-first wfacp-form-control-wrapper wfacp-col-full wfacp-input-form">
                <label for="username" class="wfacp-form-control-label"><?php esc_html_e( 'Username or email', 'woocommerce' ); ?>
                    &nbsp;<span class="required">*</span></label>
                <input type="text" class="input-text wfacp-form-control" name="username" id="username" autocomplete="username" placeholder="<?php esc_html_e( 'Username or email', 'woocommerce' ); ?>"/>
            </p>
            <p class="form-row form-row-last wfacp-form-control-wrapper wfacp-col-full wfacp-input-form">
                <label for="password" class="wfacp-form-control-label"><?php esc_html_e( 'Password', 'woocommerce' ); ?>
                    &nbsp;<span class="required">*</span></label>
                <input class="input-text wfacp-form-control" type="password" name="password" id="password" autocomplete="current-password" placeholder="<?php esc_html_e( 'Password', 'woocommerce' ); ?>"/>
            </p>

			<?php do_action( 'woocommerce_login_form' ); ?>

            <p class="form-row wfacp-form-control-wrapper wfacp-col-left-half wfacp-remember-me ">
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox inline">
                    <input class="woocommerce-form__input woocommerce-form__input-checkbox " name="rememberme" type="checkbox" id="rememberme" value="forever"/>
                    <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
                </label>
            </p>


            <p class="form-row wfacp-form-control-wrapper wfacp-col-left-half lost_password wfacp-text-align-right">
                <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
            </p>
            <div class="clear"></div>


            <p class="form-row wfacp-col-full ">
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
                <button type="submit" class="button wfacp-login-btn" name="login" value="<?php esc_attr_e( 'Login', 'woocommerce' ); ?>"><?php esc_html_e( 'Login', 'woocommerce' ); ?></button>

            </p>



			<?php do_action( 'woocommerce_login_form_end' ); ?>
        </form>
    </div>
</div>
