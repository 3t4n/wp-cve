<?php

$post_data = get_demo_post_data();

if ( ! isset( $post_data ) ) {
	return;
}

$animation = ( $settings['image_hover_animation'] ) ? ' elementor-animation-' . $settings['image_hover_animation'] : '';
$this->add_render_attribute( 'wrapper', 'class', 'xpro-featured-image' . $animation );

?>
<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
<?php
echo wp_get_attachment_image( get_post_thumbnail_id( $post_data->ID ), $settings['thumbnail_size'] );
?>
</div>
<?php
