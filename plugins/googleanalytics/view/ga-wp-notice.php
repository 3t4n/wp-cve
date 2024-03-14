<?php
/**
 * WP Notice view.
 *
 * @package GoogleAnalytics
 */

$is_dismissable = isset( $is_dismissable ) ? $is_dismissable : false;

$msg = isset( $msg ) ? $msg : '';

$notice_type = isset( $notice_type ) ? $notice_type : '';
?>
<div class="notice notice-<?php echo esc_attr( $notice_type ); ?> <?php echo false === empty( $is_dismissable ) ? 'is-dismissible' : ''; ?>">
	<p><?php echo esc_html( $msg ); ?>
		<?php if ( ! empty( $action ) ) : ?>
			<button onclick="window.location.href='<?php echo esc_js( $action['url'] ); ?>'"
					class="button button-primary"><?php echo esc_html( $action['label'] ); ?></button>
		<?php endif; ?>
	</p>
</div>
