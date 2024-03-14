<?php
$taxonomy_image_meta = get_term_meta( $category_id, 'thumbnail_id', true );
$taxonomy_image      = ! empty( $taxonomy_image_meta ) ? $taxonomy_image_meta : get_option( 'woocommerce_placeholder_image', 0 );

if ( ! empty( $taxonomy_image ) ) {?>
	<div class="qodef-e-img-holder">
		<?php
			echo qi_addons_for_elementor_get_list_shortcode_item_image( $images_proportion, $taxonomy_image, $custom_image_width, $custom_image_height );
		?>
	</div>
	<?php
}
