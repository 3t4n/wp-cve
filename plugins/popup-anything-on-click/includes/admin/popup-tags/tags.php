<?php
/**
 * Handles Popup tags metabox HTML
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<!-- Start General Tags -->
<div class="paoc-popup-tags paoc-general-tags paoc-medium-popup-modal paoc-cnt-wrap">
	<div class="paoc-popup-modal-act-btn-wrp">
		<span class="paoc-popup-modal-act-btn paoc-popup-modal-close paoc-popup-close" title="<?php esc_attr_e('Close', 'popup-anything-on-click'); ?>"><i class="dashicons dashicons-no-alt"></i></span>
	</div>

	<div class="paoc-popup-modal-title-wrp">
		<span class="paoc-popup-modal-title"><?php esc_html_e('Popup Tags', 'popup-anything-on-click'); ?></span>
	</div>

	<div class="paoc-popup-modal-cnt">
		<?php do_action('popupaoc_general_tags'); // Action to display general tags ?>
	</div>
</div>
<!-- End - General Tags -->