<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! empty( $param['menu_1']['item_type'] ) ) {
	$count_i = count( $param['menu_1']['item_type'] );
} else {
	$count_i = 0;
}


if ( $count_i > 0 ) {
	$position = isset( $param['menu'] ) ? 'is-' . $param['menu'] : ' is-left';
	$align    = isset( $param['align'] ) ? ' -' . $param['align'] : ' -center';

	$menu_add_classes = 'side-menu ' . $position . ' -center';

	$menu = '<div class="' . esc_attr( $menu_add_classes ) . '" id="side-menu-' . absint( $id ) . '">';

	$menu .= '<ul class="sm-list">';

	for ( $i = 0; $i < $count_i; $i ++ ) {

		$menu .= '<li class="sm-item">';

		$icon_class = $param['menu_1']['item_icon'][ $i ];
		$icon       = '<span class="sm-icon ' . esc_attr( $icon_class ) . '"></span>';

		// Update to version 4.0
		$link_type = isset( $param['menu_1']['item_type'][ $i ] ) ? $param['menu_1']['item_type'][ $i ] : '';
		$class_id  = '';
		if ( $link_type === 'id' ) {
			$class_id                           = ! empty( $param['menu_1']['item_modal'][ $i ] ) ? $param['menu_1']['item_modal'][ $i ] : '';
			$param['menu_1']['button_id'][ $i ] = $class_id;
			$param['menu_1']['item_type'][ $i ] = 'link';
		} elseif ( $link_type === 'class' ) {
			$class_id                              = ! empty( $param['menu_1']['item_modal'][ $i ] ) ? $param['menu_1']['item_modal'][ $i ] : '';
			$param['menu_1']['button_class'][ $i ] = $class_id;
			$param['menu_1']['item_type'][ $i ]    = 'link';
		}

		$button_class = $param['menu_1']['button_class'][ $i ];
		$class_add    = ! empty( $button_class ) ? ' class="sm-link ' . esc_attr($button_class) . '"' : ' class="sm-link"';
		$button_id    = $param['menu_1']['button_id'][ $i ];
		$id_add       = ! empty( $button_id ) ? ' id="' . esc_attr($button_id) . '"' : "";
		$link_rel     = ! empty( $param['menu_1']['link_rel'][ $i ] ) ? ' rel="' . esc_attr( $param['menu_1']['link_rel'][ $i ] ) . '"' : '';
		$link_param   = esc_html( $id_add . $class_add .$link_rel );

		$tooltip_text = $param['menu_1']['item_tooltip'][ $i ];

		$tooltip = ! empty( $tooltip_text ) ? '<span class="sm-label">' . esc_attr( $tooltip_text ) . '</span>' : '';

		$item_type = $param['menu_1']['item_type'][ $i ];


		$target = ! empty( $param['menu_1']['new_tab'][ $i ] ) ? '_blank' : '_self';
		$link   = ! empty( $param['menu_1']['item_link'][ $i ] ) ? $param['menu_1']['item_link'][ $i ] : '#';
		$menu   .= '<a href="' . esc_attr( $link ) . '" target="' . esc_attr( $target ) . '" '
		           . wp_specialchars_decode( $link_param, 'double' ) . '>';
		$menu   .= $icon . $tooltip;
		$menu   .= '</a>';
		$menu .= '</li>';
	}
	$menu .= '</ul>';
	$menu .= '</div>';
}