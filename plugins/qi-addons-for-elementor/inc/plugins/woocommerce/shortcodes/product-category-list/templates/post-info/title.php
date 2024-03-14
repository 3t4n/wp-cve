<?php
$title_tag = isset( $title_tag ) && ! empty( $title_tag ) ? $title_tag : 'h2';
$with_link = isset( $with_link ) && ! empty( $with_link ) ? $with_link : false;
?>
<<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?> class="qodef-e-title woocommerce-loop-category__title">
	<?php if ( $with_link ) { ?>
		<a href="<?php echo get_term_link( $category_slug, 'product_cat' ); ?>">
	<?php } ?>
	<?php echo esc_html( $category_name ); ?>
	<?php if ( $with_link ) { ?>
		</a>
	<?php } ?>
</<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?>>
