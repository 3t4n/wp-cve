<?php
$icon = isset( $slider_navigation_arrow_next ) ? $slider_navigation_arrow_next : array();

if ( isset( $icon ) && ! empty( $icon['value'] ) ) {
	\Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
} else {
	qi_addons_for_elementor_render_svg_icon( 'slider-arrow-right', 'qodef-swiper-arrow-right' );
}
