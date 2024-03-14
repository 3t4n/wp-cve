<?php
/*
 * User generate After checkout
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( is_wc_endpoint_url( 'lost-password' ) ) {
	return;
}

$this->add_render_attribute(
	'woo_ready_username',
	array(

		'class'        => array( 'woo-ready-register-username-input' ),
		'type'         => 'text',
		'name'         => 'username',
		'id'           => 'reg_username',
		'placeholder'  => $settings['username_placeholder'],
		'autocomplete' => 'username',

	)
);

$this->add_render_attribute(
	'woo_ready_password',
	array(

		'class'        => array( 'woo-ready-register-password-input' ),
		'type'         => 'password',
		'name'         => 'password',
		'id'           => 'reg_password',
		'placeholder'  => $settings['password_placeholder'],
		'autocomplete' => 'new-password',

	)
);

$this->add_render_attribute(
	'woo_ready_email',
	array(

		'class'        => array( 'woo-ready-register-email-input' ),
		'type'         => 'email',
		'name'         => 'email',
		'id'           => 'reg_email',
		'placeholder'  => $settings['email_placeholder'],
		'autocomplete' => 'email',

	)
);

?>

<form method="post" class="woo-ready-form-register" <?php do_action( 'woocommerce_register_form_tag' ); ?>>

    <?php if ( 'yes' == $settings['woocommerce_registration_generate_username'] ) : ?>

    <div class="woo-ready-register-username">
        <?php if ( $settings['show_label'] == 'yes' ) : ?>
        <label for="reg_username">
            <?php echo esc_html( str_replace( array( '{', '}' ), array( '<span>', '</span>' ), $settings['username_label'] ) ); ?>
        </label>
        <?php endif; ?>
        <input <?php echo wp_kses_post( $this->get_render_attribute_string( 'woo_ready_username' ) ); ?>
            value="<?php echo esc_attr( ! empty( $_POST['username'] ) ) ? esc_attr( sanitize_user( $_POST['username'] )) : ''; ?>" />
    </div>

    <?php endif; ?>

    <div class="woo-ready-register-email">
        <?php if ( $settings['show_label'] == 'yes' ) : ?>
        <label for="reg_email">
            <?php echo esc_html( str_replace( array( '{', '}' ), array( '<span>', '</span>' ), $settings['email_label'] ) ); ?>
        </label>
        <?php endif; ?>
        <input <?php echo wp_kses_post( $this->get_render_attribute_string( 'woo_ready_email' ) ); ?>
            value="<?php echo esc_attr( ! empty( $_POST['email'] ) ) ? esc_attr( sanitize_email( $_POST['email'] ) ) : ''; ?>" />
    </div>

    <?php if ( 'yes' == $settings['woocommerce_registration_generate_password'] ) : ?>

    <div class="woo-ready-register-password">
        <?php if ( $settings['show_label'] == 'yes' ) : ?>
        <label for="reg_password">
            <?php echo esc_html( str_replace( array( '{', '}' ), array( '<span>', '</span>' ), $settings['password_label'] ) ); ?>
        </label>
        <?php endif; ?>
        <input <?php echo wp_kses_post( $this->get_render_attribute_string( 'woo_ready_password' ) ); ?> />
    </div>

    <?php else : ?>

    <?php if ( $settings['generate_pass_message'] != '' ) : ?>
    <div class="woo-ready-when-password-auto">
        <?php echo wp_kses_post( $settings['generate_pass_message'] ); ?>
    </div>
    <?php endif; ?>

    <?php endif; ?>

    <div class="woo-ready-register-btn-wrp">
        <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
        <button type="submit" class="woo-ready-form-register-submit" name="register"
            value="<?php esc_attr_e( 'Register', 'shopready-elementor-addon' ); ?>">
            <?php if ( $settings['icon_align'] == 'left' ) : ?>
            <?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], array( 'aria-hidden' => 'true' ) ); ?>
            <?php endif; ?>
            <?php echo esc_html( $settings['button_text'] ); ?>
            <?php if ( $settings['icon_align'] == 'right' ) : ?>
            <?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], array( 'aria-hidden' => 'true' ) ); ?>
            <?php endif; ?>
        </button>
    </div>



</form>