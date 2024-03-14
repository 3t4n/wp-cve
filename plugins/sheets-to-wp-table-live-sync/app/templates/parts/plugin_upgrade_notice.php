<?php
/**
 * Displays affiliate notices.
 *
 * @package SWPTLS
 */

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

$pink_diamond = SWPTLS_BASE_URL . 'assets/public/icons/top-banner/pink-diamond.svg';
?>

<div class="gswpts-upgrade-banner">
	<span class="gswpts-upgrade-close"></span>
	<div class="banner-content">
		<div class="image-icon">
			<img class="gswpts-image-icon" src="<?php echo esc_url($pink_diamond); ?>" alt="">
		</div>
		
		<div class="content">
			<h3><?php esc_html_e('Advance sheets design and many more premium features are available in ', 'sheetstowptable'); ?> <span><?php esc_html_e('Sheets To WP Table Live Sync Pro', 'sheetstowptable'); ?></span> <?php esc_html_e('plugin 😍', 'sheetstowptable'); ?></h3>

			<div class="upgrade-btn-wrapper">
				<a href="<?php echo esc_url('https://go.wppool.dev/KfVZ'); ?>" target="_blank" class="upgrade-button"><?php esc_html_e('Upgrade Now', 'sheetstowptable'); ?> <span></span></a>
			</div>

		</div>
		
	</div>
	
</div>

<script>
jQuery(document).ready(function($) {
	$(document).on('click', '.gswpts-upgrade-close', (e) => {
		console.log("Click")
		e.preventDefault();

		let target = $(e.currentTarget);
		let dataValue = 'hide_notice';

		$.ajax({
			type: "POST",
			url: "<?php echo esc_url(admin_url( 'admin-ajax.php' )); ?>", // phpcs:ignore
			data: {
				action: 'gswpts_notice_action',
				nonce: '<?php echo esc_attr( wp_create_nonce( 'swptls_notices_nonce' ) ); ?>',
				info: {
					type: 'hide_notice',
					value: dataValue
				},
				actionType: 'upgrade_notice'
			},
			success: response => {
				if (response.data.response_type === 'success') {
					$('.gswpts-upgrade-banner').slideUp();
				}
			}
		});
	})
});
</script>
