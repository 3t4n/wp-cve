<?php
/**
 * Template used on WordPress post list as part of Surfer column with GSC data.
 *
 * @package SurferSEO
 */

?>

<div class="surfer-post-list-traffic" style="max-width: 230px;">
	<div class="surfer-traffic-details-box" 
		data-post-id="<?php echo isset( $post_id ) ? intval( $post_id ) : 0; ?>" 
		data-permalink-hash="<?php echo isset( $permalink_hash ) ? esc_html( $permalink_hash ) : ''; ?>" 
		data-draft-id="<?php echo isset( $draft_id ) && $draft_id > 0 ? intval( $draft_id ) : -1; ?>"  
		data-scrape-status="<?php echo isset( $scrape_status ) ? intval( $scrape_status ) : 0; ?>"  
		data-content="<?php echo isset( $content ) ? esc_html( base64_encode( $content ) ) : ''; ?>" 
		data-stats="<?php echo isset( $stats ) ? esc_html( wp_json_encode( $stats ) ) : ''; ?>" 
		data-current-period="<?php echo isset( $last_update_date ) ? esc_html( $last_update_date ) : ''; ?>" 
		data-previous-period="<?php echo isset( $previous_update_date ) ? esc_html( $previous_update_date ) : ''; ?>">
	</div>
</div>

