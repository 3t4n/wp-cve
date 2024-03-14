<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Feedback ratings.
 *
 * @package Dark_Mode_Toggle
 */

defined( 'ABSPATH' ) || die();
?>

<div class="dmt-mt-4 xl:dmt-mt-6 dmt-px-1">
	<a target="_blank" href="https://wordpress.org/support/plugin/dark-mode-toggle/reviews/#new-post" class="dmt-inline-block"><?php esc_html_e( 'Like this plugin? Leave a Review.', 'dark-mode-toggle' ); ?></a>
	<br>
	<a target="_blank" href="https://wordpress.org/support/plugin/dark-mode-toggle/reviews/#new-post" class="dmt-inline-block dmt-mt-2">
		<div class="dmt-rating dmt-pointer-events-none">
			<input type="radio" name="dmt-feedback-rating" class="dmt-mask dmt-mask-star-2 dmt-bg-orange-400">
			<input type="radio" name="dmt-feedback-rating" class="dmt-mask dmt-mask-star-2 dmt-bg-orange-400">
			<input type="radio" name="dmt-feedback-rating" class="dmt-mask dmt-mask-star-2 dmt-bg-orange-400">
			<input type="radio" name="dmt-feedback-rating" class="dmt-mask dmt-mask-star-2 dmt-bg-orange-400">
			<input type="radio" name="dmt-feedback-rating" class="dmt-mask dmt-mask-star-2 dmt-bg-orange-400" checked>
		</div>
	</a>
</div>
