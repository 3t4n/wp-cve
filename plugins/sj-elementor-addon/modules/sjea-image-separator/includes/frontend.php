<?php

if ( empty( $settings['image']['url'] ) ) {
	return;
}

$class = $name .' sjea-image-separator-' . $node_id . ' sjea-image-separator-' . $settings['position']. ' sjea-image-separator-' . $settings['align'] . ' sjea-image-separator-' . $settings['position'] . '-' . $settings['align'];

$this->add_render_attribute( 'wrapper', 'class', $class );

$link = $this->get_link_url( $settings );

if ( $link ) {
	$this->add_render_attribute( 'link', 'href', $link['url'] );

	if ( ! empty( $link['is_external'] ) ) {
		$this->add_render_attribute( 'link', 'target', '_blank' );
	}
}

$output = '<div '. $this->get_render_attribute_string( 'wrapper' ) . '>';
	
	if ( $link ) :
		$output .= '<a '. $this->get_render_attribute_string( 'link' ) . '>';
	endif;

	$output .= $this->get_attachment_image_html( $settings );

	if ( $link ) :
		$output .= '</a>';
	endif;
$output .= '</div>';

echo $output;