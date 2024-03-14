<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
 * Shop Ready
 * woocommece password reset email sent message
 * Lost_Pass_Msg.php template
 * @since 1.0
 *
 */

?>
<?php if ( \Elementor\Plugin::$instance->editor->is_edit_mode() && $settings['show_messege_in_editor'] == 'yes' ) : ?>

<?php $notices = function_exists( 'wc_get_notices' ) ? wc_get_notices() : array(); ?>
<?php if ( is_numeric( $settings['template_id'] ) && $settings['enable_template_message'] ) : ?>
<?php echo  \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $settings['template_id'], true ); ?>
<?php else : ?>

<div class="woocommerce-message" role="alert">
    <?php echo esc_html( $settings['lost_password_message'] ); ?>
</div>

<?php endif; ?>

<?php elseif ( is_wc_endpoint_url( 'lost-password' ) ) : ?>

<?php $notices = function_exists( 'wc_get_notices' ) ? wc_get_notices() : array(); ?>
<!-- Success message -->
<?php if ( empty( $notices ) && ! empty( $_GET['reset-link-sent'] ) ) : ?>

<?php if ( is_numeric( $settings['template_id'] ) && $settings['enable_template_message'] ) : ?>
<?php echo  \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $settings['template_id'], true ); ?>
<?php else : ?>
<?php echo wp_kses_post( wc_print_notice( $settings['lost_password_message'] ) ); ?>
<?php endif; ?>

<?php endif; // reset-link-sent ?>

<?php endif; ?>