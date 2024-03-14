<?php
/**
 * Displays affiliate notices.
 *
 * @package SWPTLS
 */

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

$purple_thumbs_up = SWPTLS_BASE_URL . 'assets/public/icons/top-banner/purple-thumbs-up.svg';
?>

<div class="gswpts-influencer-banner">
			
	<span class="gswpts-influencer-close"></span>

	<div class="banner-content" data-value="hide_notice">
		<div class="gswpts-influencer-image">
			<img class="gswpts-image-icon" src="<?php echo esc_url($purple_thumbs_up); ?>" alt="">
		</div>
		<div class="gswpts-influencer-wrapper">
			<h3><?php esc_html_e('Hey! Enjoying the Sheets To WP Table Live Sync plugin? 😍 Join our ', 'sheetstowptable'); ?>
			<span><?php printf('<a style="text-decoration:none; color:#7C3AED; font-family:inherit; cursor: pointer;" href="%s" target="_blank">%s</a>', esc_url('https://go.wppool.dev/VggE'), esc_html('Influencer Program ', 'sheetstowptable')); ?></span>
			<?php esc_html_e('to make money from your social media content. You can also check our', 'sheetstowptable'); ?>
			<span><?php printf('<a style="text-decoration:none; color:#7C3AED; font-family:inherit; cursor:pointer;" href="%s" target="_blank">%s</a>', esc_url('https://go.wppool.dev/Qgfq'), esc_html('Affiliate Program ', 'sheetstowptable')); ?></span> 
			<?php esc_html_e('to get a ', 'sheetstowptable'); ?>
			<span style="font-weight:600; font-size:inherit; color: #1f2937"><?php esc_html_e('25% commission ', 'sheetstowptable'); ?></span>
			<?php esc_html_e('on every sale!', 'sheetstowptable'); ?>
		
		</h3>
			<div class="link-wrapper">
				<a href="<?php echo esc_url('https://go.wppool.dev/Qgfq'); ?>" target="_blank" class="affiliate-button"><?php esc_html_e('Affiliate Program', 'sheetstowptable'); ?></a>
				<a href="<?php echo esc_url('https://go.wppool.dev/VggE'); ?>" target="_blank" class="influencer-button" style=""><?php esc_html_e('Influencer Program', 'sheetstowptable'); ?> <span></span></a>
			</div>
		</div>
	</div>
	
</div>

<script>
jQuery(document).ready(function($) {
	$(document).on('click', '.gswpts-influencer-close', (e) => {
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
					// type: 'reminder', // use for review notice
					type: 'hide_notice',
					value: dataValue
				},
				actionType: 'affiliate_notice'
			},
			success: response => {
				if (response.data.response_type === 'success') {
					$('.gswpts-influencer-banner').slideUp();
				}
			}
		});
	})
});
</script>
