<?php
$title_tag = isset( $title_tag ) && ! empty( $title_tag ) ? $title_tag : 'h1';
?>
<<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?> itemprop="name" class="qodef-e-title entry-title">
	<a itemprop="url" class="qodef-e-title-link" href="<?php the_permalink(); ?>">
		<?php the_title(); ?>
	</a>
</<?php echo qi_addons_for_elementor_framework_sanitize_tags( $title_tag ); ?>>
