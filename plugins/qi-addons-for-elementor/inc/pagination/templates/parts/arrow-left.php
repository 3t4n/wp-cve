<?php
$icon = isset( $pagination_arrow_prev ) ? $pagination_arrow_prev : array();

if ( isset( $icon ) && ! empty( $icon['value'] ) ) {
	\Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
} else {
	qi_addons_for_elementor_render_svg_icon( 'pagination-arrow-left', 'qodef-m-pagination-icon' );
}
