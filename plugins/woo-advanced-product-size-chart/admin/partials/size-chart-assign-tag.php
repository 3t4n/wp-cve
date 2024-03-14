<?php

/**
 * Provide a admin area form view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    size-chart-for-woocommerce
 * @subpackage size-chart-for-woocommerce/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
// Use get_post_meta to retrieve an existing value of chart from the database.
global $post;
$size_cart_tag_id = scfw_size_chart_get_tags( $post->ID );
?>
<div id="size-chart-meta-fields">
	<div id="assign-tag">
		<div class="field-item">
			<select name="chart-tags[]" id="chart-tags" multiple="multiple">
				<?php
				$size_cart_term = get_terms( 'product_tag', array('hide_empty' => false) );				
				if ( ! empty( $size_cart_term ) ) {
					foreach ( $size_cart_term as $size_cart_tag ) {
						printf(
							"<option value='%s' %s>%s</option>",
							esc_attr( $size_cart_tag->term_id ),
							selected( true, in_array( $size_cart_tag->term_id, $size_cart_tag_id, true ) ),
							esc_html( $size_cart_tag->name )
						);
					}
				} ?>
			</select>
		</div>
	</div>
</div>