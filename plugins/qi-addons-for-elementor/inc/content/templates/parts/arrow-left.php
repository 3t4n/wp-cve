<?php
$icon = isset( $slider_navigation_arrow_prev ) ? $slider_navigation_arrow_prev : array();

if ( isset( $icon ) && ! empty( $icon['value'] ) ) {
	\Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
} else {
	qi_addons_for_elementor_render_svg_icon( 'slider-arrow-left', 'qodef-swiper-arrow-left' );
}
