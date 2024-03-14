<?php
/**
 * Handles Post Setting metabox HTML
 *
 * @package WP Slick Slider and Image Carousel
 * @since 1.2.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

// Getting saved values
$read_more_link = get_post_meta( $post->ID, 'wpsisac_slide_link', true );
?>

<table class="form-table wpsisac-post-sett-table">
	<tbody>
		<tr valign="top">
			<th scope="row">
				<label for="wpsisac-more-link"><?php esc_html_e( 'Read More Link', 'wp-slick-slider-and-image-carousel' ); ?></label>
			</th>
			<td>
				<input type="text" value="<?php echo esc_url( $read_more_link ); ?>" class="large-text wpsisac-more-link" id="wpsisac-more-link" name="wpsisac_slide_link" /><br/>
				<span class="description"><?php esc_html_e( 'Enter read more link. eg. ', 'wp-slick-slider-and-image-carousel' ); ?>https://www.essentialplugin.com/</span>
			</td>
		</tr>
	</tbody>
</table><!-- end .wtwp-tstmnl-table -->