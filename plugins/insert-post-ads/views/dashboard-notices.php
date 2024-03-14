<?php
/**
 * Notices template
 */
?>
<div class="notice notice-success is-dismissible <?php echo $this->plugin->name; ?>-notice-welcome">
	<p><?php _e( 'Thank you for installing Insert Ads!', $this->plugin->name ); ?> <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=' . $this->plugin->posttype ) ); ?>">Create</a> your first Advert now.</p>
</div>
<script type="text/javascript">
	jQuery(document).ready( function($) {
		$(document).on('click', '.<?php echo $this->plugin->name; ?>-notice-welcome button.notice-dismiss', function( event ) {
			event.preventDefault();
			$.post( ajaxurl, {
				action: '<?php echo $this->plugin->name . '_dismiss_dashboard_notices'; ?>',
				nonce: '<?php echo wp_create_nonce( $this->plugin->name . '-nonce' ); ?>'

			});
			$('.<?php echo $this->plugin->name; ?>-notice-welcome').remove();
		});
	});
</script>