<?php
/**
 * Lost Password
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

  
?>
<?php if( !is_wc_endpoint_url( 'lost-password' )): ?>
<div class="woo-ready-LostPassword <?php echo esc_attr($settings['preset']); ?>">

    <a class="woo-ready-lpass-link" href="<?php echo esc_url( wp_lostpassword_url() ); ?>">
        <?php if( $settings['icon_align'] == 'left' ): ?>
        <?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
        <?php endif; ?>
        <?php echo esc_html($settings['lost_password_title']); ?>
        <?php if( $settings['icon_align'] == 'right' ): ?>
        <?php \Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] ); ?>
        <?php endif; ?>
    </a>

</div>
<?php endif; ?>


<?php if( is_wc_endpoint_url( 'lost-password' ) || ( \Elementor\Plugin::$instance->editor->is_edit_mode() && $settings['show_form_in_editor'] == 'yes') ): ?>
<form method="post" class="woo-ready-lost-reset-password">

    <div class="woo-ready-lpass-heading">
        <?php echo esc_html(str_replace(['{','}'],['<span>','</span>'],$settings['lost_password_form_msg'])); ?>
    </div>

    <div class="woo-ready-form-label">
        <label for="woo_ready_user_login"><?php echo esc_html( $settings['lost_password_input_label'] ); ?></label>
    </div>

    <div class="woo-ready-form-username">
        <input class="woo-ready-linput-text" type="text" name="user_login" id="woo_ready_user_login"
            autocomplete="username" />
    </div>

    <div class="woo-ready-form-btn">
        <input type="hidden" name="wc_reset_password" value="true" />
        <button type="submit" class="woo-ready-lost-btn"
            value="<?php esc_attr_e( 'Reset password', 'shopready-elementor-addon' ); ?>"><?php echo esc_html( $settings['lost_password_btn_label'] ); ?></button>
    </div>

    <?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

</form>

<?php endif; ?>