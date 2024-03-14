<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$notices = function_exists( 'wc_get_notices' ) ? wc_get_notices() : array();

?>
<?php if ( \Elementor\Plugin::$instance->editor->is_edit_mode() && $settings['show_messege_in_editor'] == 'yes' ) : ?>

<?php if ( is_numeric( $settings['template_id'] ) && $settings['enable_template_message'] ) : ?>
<?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $settings['template_id'], true ); ?>
<?php else : ?>
<ul class="woocommerce-error" role="alert">
    <li>
        <?php echo esc_html__( 'Invalid username or email.', 'shopready-elementor-addon' ); ?>
    </li>
</ul>
<?php endif; ?>

<?php elseif ( is_wc_endpoint_url( 'lost-password' ) || 1 == 1 ) : ?>

<?php if ( is_numeric( $settings['template_id'] ) && $settings['enable_template_message'] ) : ?>
<?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $settings['template_id'], true ); ?>
<?php else : ?>
<?php if ( ! empty( $notices ) ) : ?>
<?php wp_kses_post(\wc_print_notices()); ?>
<?php endif; ?>
<?php endif; ?>

<?php endif; ?>