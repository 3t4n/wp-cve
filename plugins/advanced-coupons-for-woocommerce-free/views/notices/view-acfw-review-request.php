<?php if ( ! defined( 'ABSPATH' ) ) {
exit;} // Exit if accessed directly ?>

<div 
    class="<?php echo esc_attr( $notice_class ); ?> acfw-admin-notice notice-<?php echo esc_attr( $notice['type'] ); ?> is-dismissable"
    data-notice="<?php echo esc_attr( $notice['slug'] ); ?>"
    data-nonce="<?php echo esc_attr( wp_create_nonce( 'acfw_dismiss_notice_' . $notice['slug'] ) ); ?>"
    >

    <?php foreach ( $notice['content'] as $paragraph ) : ?>
        <p><?php echo wp_kses_post( $paragraph ); ?></p>
    <?php endforeach; ?>

    <p class="review-actions">
        <?php foreach ( $notice['actions'] as $action_link ) : ?>
            <a 
                class="<?php echo esc_attr( $action_link['key'] ); ?>"
                data-response="<?php echo esc_attr( $action_link['key'] ); ?>"
                href="<?php echo $action_link['link'] ? esc_attr( $action_link['link'] ) : 'javascript:void(0);'; ?>"
                <?php echo isset( $action_link['link'] ) && $action_link['link'] ? 'target="_blank"' : ''; ?>
            >
                <?php echo esc_html( $action_link['text'] ); ?>
            </a>
            <br>
        <?php endforeach; ?>
    </p>

    <?php if ( $notice['is_dismissable'] ) : ?>
        <button type="button" class="notice-dismiss" data-response="snooze"><span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice...', 'advanced-coupons-for-woocommerce-free' ); ?></span></button>
    <?php endif; ?>
</div>
