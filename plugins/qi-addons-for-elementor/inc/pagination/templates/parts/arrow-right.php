<?php
$icon = isset( $pagination_arrow_next ) ? $pagination_arrow_next : array();

if ( isset( $icon ) && ! empty( $icon['value'] ) ) {
	\Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
} else {
	qi_addons_for_elementor_render_svg_icon( 'pagination-arrow-right', 'qodef-m-pagination-icon' );
}
