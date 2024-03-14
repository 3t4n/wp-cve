<?php

/**
 * Provide notice for message in admin view
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://webmuehle.at
 * @since      1.1.0
 *
 * @package    Courtres
 * @subpackage Courtres/admin/partials
 */
?>


<?php if ( isset( $message ) && $message ) : ?>
	<?php $message_type = isset( $message_type ) && $message_type ? $message_type : 'success'; ?>
	<div class="notice notice-<?php echo esc_attr( $message_type ); ?> is-dismissible"> 
		<p><?php echo esc_html($message); ?></p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text"><?php echo esc_html__( 'Dismiss this notice.', 'court-reservation' ); ?></span>
		</button>
	</div>
<?php endif; ?>
