<?php

if ( ! function_exists( 'qi_addons_for_elementor_is_qode_theme_installed' ) ) {
	/**
	 * Function that check is Qode theme installed
	 *
	 * @return bool
	 */
	function qi_addons_for_elementor_is_qode_theme_installed() {
		return apply_filters( 'qi_addons_for_elementor_filter_is_qode_theme_installed', false );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_add_mobile_class' ) ) {
	/**
	 * Function that check is mobile device and adds class for it
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_mobile_class( $classes ) {

		if ( wp_is_mobile() ) {
			$classes[] = 'qodef-qi--touch';
		} else {
			$classes[] = 'qodef-qi--no-touch';
		}
		return $classes;
	}

	add_filter( 'body_class', 'qi_addons_for_elementor_add_mobile_class' );
}

if ( ! function_exists( 'qi_addons_for_elementor_list_sc_template_part' ) ) {
	/**
	 * Echo module template part.
	 *
	 * @param string $module name of the module from inc folder
	 * @param string $template full path of the template to load
	 * @param string $slug
	 * @param array $params array of parameters to pass to template
	 */
	function qi_addons_for_elementor_list_sc_template_part( $module, $template, $slug = '', $params = array() ) {
		echo qi_addons_for_elementor_get_list_sc_template_part( $module, $template, $slug, $params );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_get_list_sc_template_part' ) ) {
	/**
	 * Echo module template part.
	 *
	 * @param string $module name of the module from inc folder
	 * @param string $template full path of the template to load
	 * @param string $slug
	 * @param array $params array of parameters to pass to template
	 *
	 * @return string - string containing html of template
	 */
	function qi_addons_for_elementor_get_list_sc_template_part( $module, $template, $slug = '', $params = array() ) {
		$root = QI_ADDONS_FOR_ELEMENTOR_INC_PATH;

		return qi_addons_for_elementor_framework_get_list_sc_template_part( $root, $module, $template, $slug, $params );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_template_part' ) ) {
	/**
	 * Echo module template part.
	 *
	 * @param string $module name of the module from inc folder
	 * @param string $template full path of the template to load
	 * @param string $slug
	 * @param array $params array of parameters to pass to template
	 *
	 */
	function qi_addons_for_elementor_template_part( $module, $template, $slug = '', $params = array() ) {
		echo qi_addons_for_elementor_get_template_part( $module, $template, $slug, $params );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_get_template_part' ) ) {
	/**
	 * Loads module template part.
	 *
	 * @param string $module name of the module from inc folder
	 * @param string $template full path of the template to load
	 * @param string $slug
	 * @param array $params array of parameters to pass to template
	 *
	 * @return string - string containing html of template
	 */
	function qi_addons_for_elementor_get_template_part( $module, $template, $slug = '', $params = array() ) {
		$root = QI_ADDONS_FOR_ELEMENTOR_INC_PATH;

		return qi_addons_for_elementor_framework_get_template_part( $root, $module, $template, $slug, $params );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_get_query_params' ) ) {
	/**
	 * Function that return query parameters
	 *
	 * @param array $atts - options value
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_get_query_params( $atts ) {
		$post_type      = isset( $atts['post_type'] ) && ! empty( $atts['post_type'] ) ? $atts['post_type'] : 'post';
		$posts_per_page = isset( $atts['posts_per_page'] ) && ! empty( $atts['posts_per_page'] ) ? $atts['posts_per_page'] : 12;

		$args = array(
			'post_status'         => 'publish',
			'post_type'           => esc_attr( $post_type ),
			'posts_per_page'      => $posts_per_page,
			'orderby'             => esc_attr( $atts['orderby'] ),
			'order'               => esc_attr( $atts['order'] ),
			'ignore_sticky_posts' => 1,
		);

		if ( isset( $atts['next_page'] ) && ! empty( $atts['next_page'] ) ) {
			$args['paged'] = intval( $atts['next_page'] );
		} elseif ( ! empty( max( 1, get_query_var( 'paged' ) ) ) ) {
			if ( is_front_page() ) {
				$args['paged'] = max( 1, get_query_var( 'page' ) );
			} else {
				$args['paged'] = max( 1, get_query_var( 'paged' ) );
			}
		} else {
			$args['paged'] = 1;
		}

		if ( isset( $atts['additional_query_args'] ) && ! empty( $atts['additional_query_args'] ) ) {
			foreach ( $atts['additional_query_args'] as $key => $value ) {
				$args[ esc_attr( $key ) ] = $value;
			}
		}

		return apply_filters( 'qi_addons_for_elementor_filter_query_params', $args, $atts );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_get_grid_gutter_classes' ) ) {
	/**
	 * Function that returns classes for the gutter when sidebar is enabled
	 *
	 * @return string
	 */
	function qi_addons_for_elementor_get_grid_gutter_classes() {
		return apply_filters( 'qi_addons_for_elementor_filter_grid_gutter_classes', 'qodef-gutter--huge' );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_render_svg_icon' ) ) {
	/**
	 * Function that print svg html icon
	 *
	 * @param string $name - icon name
	 * @param string $class_name - custom html tag class name
	 */
	function qi_addons_for_elementor_render_svg_icon( $name, $class_name = '' ) {
		echo qi_addons_for_elementor_get_svg_icon( $name, $class_name );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_get_svg_icon' ) ) {
	/**
	 * Returns svg html
	 *
	 * @param string $name - icon name
	 * @param string $class_name - custom html tag class name
	 *
	 * @return string
	 */
	function qi_addons_for_elementor_get_svg_icon( $name, $class_name = '' ) {
		$html  = '';
		$class = isset( $class_name ) && ! empty( $class_name ) ? 'class="' . esc_attr( $class_name ) . '"' : '';

		switch ( $name ) {
			case 'menu':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="13" x="0px" y="0px" viewBox="0 0 21.3 13.7" xml:space="preserve" aria-hidden="true"><rect x="10.1" y="-9.1" transform="matrix(-1.836970e-16 1 -1 -1.836970e-16 11.5 -9.75)" width="1" height="20"/><rect x="10.1" y="-3.1" transform="matrix(-1.836970e-16 1 -1 -1.836970e-16 17.5 -3.75)" width="1" height="20"/><rect x="10.1" y="2.9" transform="matrix(-1.836970e-16 1 -1 -1.836970e-16 23.5 2.25)" width="1" height="20"/></svg>';
				break;
			case 'search':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 29.5 29.4" xml:space="preserve"><g><path d="M29.3,27.3L27.5,29l-8.4-8.4c-2,1.6-4.4,2.4-7.1,2.4c-3.1,0-5.8-1.1-8-3.3c-2.2-2.2-3.3-4.9-3.3-8s1.1-5.8,3.3-8c2.2-2.2,4.9-3.3,8-3.3s5.8,1.1,8,3.3c2.2,2.2,3.3,4.9,3.3,8c0,2.7-0.8,5-2.4,7.1L29.3,27.3z M4.9,19c1.9,1.9,4.3,2.9,7.1,2.9c2.8,0,5.1-1,7.1-3c2-2,3-4.4,3-7.1c0-2.8-1-5.1-3-7.1c-2-2-4.4-3-7.1-3c-2.8,0-5.1,1-7.1,3c-2,2-3,4.4-3,7.1C1.9,14.6,2.9,17,4.9,19z"/></g></svg>';
				break;
			case 'plus':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 29.5 29.4" xml:space="preserve"><polygon points="28.8,12.7 16.8,12.7 16.8,0.7 12.8,0.7 12.8,12.7 0.8,12.7 0.8,16.7 12.8,16.7 12.8,28.7 16.8,28.7 16.8,16.7 28.8,16.7 "/></svg>';
				break;
			case 'close':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 9.1 9.1" xml:space="preserve"><g><path d="M8.5,0L9,0.6L5.1,4.5L9,8.5L8.5,9L4.5,5.1L0.6,9L0,8.5L4,4.5L0,0.6L0.6,0L4.5,4L8.5,0z"/></g></svg>';
				break;
			case 'star':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="15" x="0px" y="0px" viewBox="0 0 16.2 15.2" xml:space="preserve"><g><g><path d="M16.1,5.8l-5,3.5l1.9,5.7l-4.9-3.6l-4.9,3.6l1.9-5.7l-5-3.5h6.1l1.9-5.7L10,5.8H16.1z"/></g></g></svg>';
				break;
			case 'menu-arrow-right':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 32 32" aria-hidden="true"><g><path d="M 13.8,24.196c 0.39,0.39, 1.024,0.39, 1.414,0l 6.486-6.486c 0.196-0.196, 0.294-0.454, 0.292-0.71 c0-0.258-0.096-0.514-0.292-0.71L 15.214,9.804c-0.39-0.39-1.024-0.39-1.414,0c-0.39,0.39-0.39,1.024,0,1.414L 19.582,17 L 13.8,22.782C 13.41,23.172, 13.41,23.806, 13.8,24.196z"></path></g></svg>';
				break;
			case 'menu-arrow-bottom':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 7.3 4.1" xml:space="preserve" aria-hidden="true"><polyline class="st0" points="3.6,4.1 0.1,0.1 7.1,0.1 3.6,4.1 "/></svg>';
				break;
			case 'slider-arrow-left':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 34.2 32.3" xml:space="preserve" style="stroke-width: 2;"><line x1="0.5" y1="16" x2="33.5" y2="16"/><line x1="0.3" y1="16.5" x2="16.2" y2="0.7"/><line x1="0" y1="15.4" x2="16.2" y2="31.6"/></svg>';
				break;
			case 'slider-arrow-right':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 34.2 32.3" xml:space="preserve" style="stroke-width: 2;"><line x1="0" y1="16" x2="33" y2="16"/><line x1="17.3" y1="0.7" x2="33.2" y2="16.5"/><line x1="17.3" y1="31.6" x2="33.5" y2="15.4"/></svg>';
				break;
			case 'pagination-arrow-left':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 34.2 32.3" xml:space="preserve" style="stroke-width: 2;"><line x1="0.5" y1="16" x2="33.5" y2="16"/><line x1="0.3" y1="16.5" x2="16.2" y2="0.7"/><line x1="0" y1="15.4" x2="16.2" y2="31.6"/></svg>';
				break;
			case 'pagination-arrow-right':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 34.2 32.3" xml:space="preserve" style="stroke-width: 2;"><line x1="0" y1="16" x2="33" y2="16"/><line x1="17.3" y1="0.7" x2="33.2" y2="16.5"/><line x1="17.3" y1="31.6" x2="33.5" y2="15.4"/></svg>';
				break;
			case 'pagination-burger':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="27" height="27" x="0px" y="0px" viewBox="0 0 29 29" xml:space="preserve"><rect x="1" y="1" width="10" height="10"/><rect x="18" y="1" width="10" height="10"/><rect x="1" y="18" width="10" height="10"/><rect x="18" y="18" width="10" height="10"/></svg>';
				break;
			case 'spinner':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512"><path d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg>';
				break;
			case 'link':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 248.5 126.8" xml:space="preserve"><g><path d="M171.8,32.1c2.6,6.4,3.8,13.2,3.8,20.5v20.5h-30.8V52.6c0-6-1.9-10.9-5.8-14.7s-8.8-5.8-14.7-5.8H52.6c-6,0-10.9,1.9-14.7,5.8s-5.8,8.8-5.8,14.7v20.5c0,6,1.9,10.9,5.8,14.7s8.7,5.8,14.7,5.8h10.3c1.3,6,3.6,11.5,7,16.7c3.4,5.1,6.6,8.8,9.6,10.9l3.8,3.2H52.6c-14.1,0-26.2-5-36.2-15.1c-10-10-15.1-22.1-15.1-36.2V52.6c0-14.1,5-26.2,15.1-36.2c10-10,22.1-15.1,36.2-15.1h71.8c10.3,0,19.7,2.9,28.5,8.6C161.6,15.8,167.9,23.2,171.8,32.1z M196.1,1.4c14.1,0,26.2,5,36.2,15.1c10,10,15.1,22.1,15.1,36.2v20.5c0,14.1-5,26.2-15.1,36.2c-10,10-22.1,15.1-36.2,15.1h-71.8c-21.8,0-37.4-10.3-46.8-30.8c-3-7.3-4.5-14.1-4.5-20.5V52.6h30.8v20.5c0,6,1.9,10.9,5.8,14.7s8.7,5.8,14.7,5.8h71.8c6,0,10.9-1.9,14.7-5.8s5.8-8.7,5.8-14.7V52.6c0-6-1.9-10.9-5.8-14.7s-8.8-5.8-14.7-5.8h-10.3c-1.3-6-3.6-11.5-7-16.7c-3.4-5.1-6.6-8.7-9.6-10.9l-3.8-3.2H196.1z"/></g></svg>';
				break;
			case 'quote':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 190.5 148" xml:space="preserve"><g><path d="M37.7,146.3L2.1,124.6C19.3,100,28.2,74.1,28.8,46.7V2.3H90v38.8c0,19.3-5,38.8-15.1,58.4C64.9,119,52.5,134.6,37.7,146.3z M133.7,146.3l-35.6-21.7c17.2-24.5,26.2-50.5,26.8-77.9V2.3h61.2v38.8c0,19.3-5,38.8-15.1,58.4C160.9,119,148.5,134.6,133.7,146.3z"/></g></svg>';
				break;
			case 'date':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 14.6 14.6" xml:space="preserve"><path d="M10.9,1.3V0.2h-0.6v1.2H4.3V0.2H3.7v1.2H0.2v13.1h14.3V1.3H10.9z M10.9,1.9v1.2h-0.6V1.9H10.9z M4.3,1.9v1.2H3.7V1.9H4.3z M13.8,13.8H0.8V4.9h13.1V13.8z"/></svg>';
				break;
			case 'category':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16.1 14.9" xml:space="preserve"><path d="M14.6,0.3c0.3,0,0.6,0.1,0.9,0.3s0.4,0.5,0.4,0.9v10.6c0,0.3-0.1,0.6-0.4,0.9s-0.5,0.4-0.9,0.4H9.3c-0.6,0-0.9,0.2-0.9,0.7v0.5H8H7.8v-0.5c0-0.5-0.3-0.7-0.9-0.7H1.5c-0.3,0-0.6-0.1-0.9-0.4c-0.2-0.2-0.4-0.5-0.4-0.9V1.5c0-0.3,0.1-0.6,0.4-0.9c0.2-0.2,0.5-0.3,0.9-0.3h5.6c0.4,0,0.7,0.1,1,0.4c0.2-0.3,0.6-0.4,1-0.4H14.6z M7.8,13.2V1.7c0-0.2-0.1-0.4-0.3-0.5C7.3,1,7,0.9,6.8,0.9H1.5c-0.4,0-0.6,0.2-0.6,0.6v10.6c0,0.2,0.1,0.3,0.2,0.5s0.3,0.2,0.4,0.2h5.3C7.3,12.8,7.6,12.9,7.8,13.2zM15.2,12.1V1.5c0-0.4-0.2-0.6-0.6-0.6h-1.2v4.9l-1.8-1.2L9.8,5.7V0.9H9.3C9,0.9,8.8,1,8.6,1.2C8.4,1.3,8.3,1.5,8.3,1.7v11.5c0.1-0.3,0.4-0.4,0.9-0.4h5.3c0.2,0,0.3-0.1,0.4-0.2S15.2,12.3,15.2,12.1z M10.4,0.9v3.7l0.9-0.5l0.3-0.2l0.3,0.2l0.9,0.5V0.9H10.4z"/></svg>';
				break;
			case 'author':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 15.9 15.9" xml:space="preserve"><path d="M2.5,2.5C4,1,5.8,0.2,7.9,0.2c2.1,0,3.9,0.8,5.5,2.3c1.5,1.5,2.3,3.3,2.3,5.5c0,2.1-0.8,3.9-2.3,5.5c-1.5,1.5-3.3,2.3-5.5,2.3c-2.1,0-3.9-0.8-5.5-2.3C1,11.9,0.2,10,0.2,7.9C0.2,5.8,1,4,2.5,2.5z M12.9,2.9c-1.4-1.4-3.1-2.1-5-2.1c-2,0-3.6,0.7-5,2.1C1.5,4.3,0.9,6,0.9,7.9c0,1.7,0.6,3.2,1.7,4.5c1-0.4,2.1-0.8,3.3-1.2c0.1,0,0.1-0.2,0.1-0.4c0-0.4,0-0.7-0.1-0.9C5.7,9.7,5.6,9.3,5.5,8.8C5.3,8.5,5.1,8.1,5,7.6c-0.1-0.4-0.1-0.7,0-1V6.5c0.1-0.2,0-0.7-0.1-1.4C4.8,4.5,5,3.8,5.5,3.2c0.5-0.6,1.2-1,2.2-1h0.7c1,0,1.7,0.4,2.2,1c0.5,0.6,0.7,1.3,0.6,1.9c-0.1,0.7-0.2,1.2-0.1,1.4c0,0,0,0,0,0.1c0.1,0.2,0.1,0.6,0,1c-0.1,0.5-0.3,0.9-0.5,1.2c-0.1,0.5-0.2,0.9-0.3,1.2c-0.1,0.3-0.2,0.6-0.2,0.9c0,0.2,0,0.4,0.1,0.4c1.2,0.4,2.4,0.8,3.5,1.2c1.1-1.3,1.7-2.8,1.7-4.5C15,6,14.3,4.3,12.9,2.9z"/></svg>';
				break;
			case 'comment-reply':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 15.4 12.7" xml:space="preserve"><g><path d="M15.2,11v1.3h-0.4L14,11c-0.9-1.5-1.9-2.5-2.9-3C10,7.5,8.9,7.2,7.7,7.2v3.1l-7.5-5l7.5-5v3.1c2.4,0.1,4.2,0.8,5.6,2.2C14.5,7,15.2,8.7,15.2,11z M14.5,10.7c0-0.2,0-0.4,0-0.7c0-0.3-0.1-0.8-0.4-1.6c-0.2-0.8-0.6-1.4-1.1-2c-0.5-0.6-1.2-1.1-2.3-1.6C9.8,4.3,8.5,4.1,7,4.1V1.6L1.3,5.3L7,9.1V6.6c1.8,0,3.3,0.3,4.4,0.9C12.6,8.1,13.6,9.2,14.5,10.7z"/></g></svg>';
				break;
			case 'comment-edit':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 14.3 13.3" xml:space="preserve"><path d="M11.2,12.6V5.2l0.6-0.6v8.5H0.2V2.4h9.3L8.9,3H0.7v9.6H11.2z M6.5,7.9l6.2-6l0.4,0.4L6.6,8.6H5.4V7.5L12,1.2l0.4,0.4l-6.2,6L6.5,7.9z M14,0.7c0.1,0.1,0.1,0.3,0.1,0.4c0,0.1,0,0.2-0.1,0.4l-0.4,0.4l-0.8-0.7l-0.4-0.4l0.4-0.4c0.1-0.1,0.3-0.1,0.4-0.1c0.1,0,0.2,0,0.4,0.1L14,0.7z"/></svg>';
				break;
			case 'button-arrow':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1015 771" xml:space="preserve"><polygon points="34.5,307.5 684.2,307.5 513,136.4 629,20.4 882.1,273.5 998.1,389.5 882.1,505.5 629,758.6 513,642.7 684.2,471.5 34.5,471.5 "/></svg>';
				break;
		}

		return apply_filters( 'qi_addons_for_elementor_filter_svg_icon', $html, $name, $class_name );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_get_variations_options_map' ) ) {
	/**
	 * Function that return options map for module variations
	 *
	 * @param array $variations
	 * @param boolean $default_empty
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_get_variations_options_map( $variations, $default_empty = false ) {
		$map = array();

		if ( ! empty( $variations ) ) {
			$map['visibility'] = sizeof( $variations ) > 1;

			reset( $variations );
			$map['default_value'] = key( $variations );

			if ( $default_empty ) {
				$map['default_value'] = '';
			}
		} else {
			$map['visibility']    = false;
			$map['default_value'] = '';
		}

		return $map;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_get_select_type_options_pool' ) ) {
	/**
	 * Function that returns array with pool of options for select fields in framework
	 *
	 *
	 * @param string $type - type of select field
	 * @param bool $enable_default - add first element empty for default value
	 * @param array $exclude - array of items to exclude
	 * @param array $include - array of items to include
	 *
	 * @return array escaped output
	 */
	function qi_addons_for_elementor_get_select_type_options_pool( $type, $enable_default = true, $exclude = array(), $include = array() ) {
		$options = array();
		if ( $enable_default ) {
			$options[''] = esc_html__( 'Default', 'qi-addons-for-elementor' );
		}
		switch ( $type ) {
			case 'content_width':
				$options['1400'] = esc_html__( '1400px', 'qi-addons-for-elementor' );
				$options['1300'] = esc_html__( '1300px', 'qi-addons-for-elementor' );
				$options['1200'] = esc_html__( '1200px', 'qi-addons-for-elementor' );
				$options['1100'] = esc_html__( '1100px', 'qi-addons-for-elementor' );
				$options['1000'] = esc_html__( '1000px', 'qi-addons-for-elementor' );
				$options['800']  = esc_html__( '800px', 'qi-addons-for-elementor' );
				break;
			case 'title_tag':
				$options['h1'] = 'H1';
				$options['h2'] = 'H2';
				$options['h3'] = 'H3';
				$options['h4'] = 'H4';
				$options['h5'] = 'H5';
				$options['h6'] = 'H6';
				$options['p']  = 'P';
				break;
			case 'link_target':
				$options['_self']  = esc_html__( 'Same Window', 'qi-addons-for-elementor' );
				$options['_blank'] = esc_html__( 'New Window', 'qi-addons-for-elementor' );
				break;
			case 'border_style':
				$options['solid']  = esc_html__( 'Solid', 'qi-addons-for-elementor' );
				$options['dashed'] = esc_html__( 'Dashed', 'qi-addons-for-elementor' );
				$options['dotted'] = esc_html__( 'Dotted', 'qi-addons-for-elementor' );
				break;
			case 'font_weight':
				$options['100'] = esc_html__( 'Thin (100)', 'qi-addons-for-elementor' );
				$options['200'] = esc_html__( 'Extra Light (200)', 'qi-addons-for-elementor' );
				$options['300'] = esc_html__( 'Light (300)', 'qi-addons-for-elementor' );
				$options['400'] = esc_html__( 'Normal (400)', 'qi-addons-for-elementor' );
				$options['500'] = esc_html__( 'Medium (500)', 'qi-addons-for-elementor' );
				$options['600'] = esc_html__( 'Semi Bold (600)', 'qi-addons-for-elementor' );
				$options['700'] = esc_html__( 'Bold (700)', 'qi-addons-for-elementor' );
				$options['800'] = esc_html__( 'Extra Bold (800)', 'qi-addons-for-elementor' );
				$options['900'] = esc_html__( 'Black (900)', 'qi-addons-for-elementor' );
				break;
			case 'font_style':
				$options['normal']  = esc_html__( 'Normal', 'qi-addons-for-elementor' );
				$options['italic']  = esc_html__( 'Italic', 'qi-addons-for-elementor' );
				$options['oblique'] = esc_html__( 'Oblique', 'qi-addons-for-elementor' );
				$options['initial'] = esc_html__( 'Initial', 'qi-addons-for-elementor' );
				$options['inherit'] = esc_html__( 'Inherit', 'qi-addons-for-elementor' );
				break;
			case 'text_transform':
				$options['none']       = esc_html__( 'None', 'qi-addons-for-elementor' );
				$options['capitalize'] = esc_html__( 'Capitalize', 'qi-addons-for-elementor' );
				$options['uppercase']  = esc_html__( 'Uppercase', 'qi-addons-for-elementor' );
				$options['lowercase']  = esc_html__( 'Lowercase', 'qi-addons-for-elementor' );
				$options['initial']    = esc_html__( 'Initial', 'qi-addons-for-elementor' );
				$options['inherit']    = esc_html__( 'Inherit', 'qi-addons-for-elementor' );
				break;
			case 'text_decoration':
				$options['none']         = esc_html__( 'None', 'qi-addons-for-elementor' );
				$options['underline']    = esc_html__( 'Underline', 'qi-addons-for-elementor' );
				$options['overline']     = esc_html__( 'Overline', 'qi-addons-for-elementor' );
				$options['line-through'] = esc_html__( 'Line-Through', 'qi-addons-for-elementor' );
				$options['initial']      = esc_html__( 'Initial', 'qi-addons-for-elementor' );
				$options['inherit']      = esc_html__( 'Inherit', 'qi-addons-for-elementor' );
				break;
			case 'list_behavior':
				$options['columns'] = esc_html__( 'Gallery', 'qi-addons-for-elementor' );
				$options['masonry'] = esc_html__( 'Masonry', 'qi-addons-for-elementor' );
				break;
			case 'columns_number':
				$options['1'] = esc_html__( 'One', 'qi-addons-for-elementor' );
				$options['2'] = esc_html__( 'Two', 'qi-addons-for-elementor' );
				$options['3'] = esc_html__( 'Three', 'qi-addons-for-elementor' );
				$options['4'] = esc_html__( 'Four', 'qi-addons-for-elementor' );
				$options['5'] = esc_html__( 'Five', 'qi-addons-for-elementor' );
				$options['6'] = esc_html__( 'Six', 'qi-addons-for-elementor' );
				$options['8'] = esc_html__( 'Eight', 'qi-addons-for-elementor' );
				break;
			case 'items_space':
				$options['huge']   = esc_html__( 'Huge (34)', 'qi-addons-for-elementor' );
				$options['large']  = esc_html__( 'Large (25)', 'qi-addons-for-elementor' );
				$options['medium'] = esc_html__( 'Medium (20)', 'qi-addons-for-elementor' );
				$options['normal'] = esc_html__( 'Normal (15)', 'qi-addons-for-elementor' );
				$options['small']  = esc_html__( 'Small (10)', 'qi-addons-for-elementor' );
				$options['tiny']   = esc_html__( 'Tiny (5)', 'qi-addons-for-elementor' );
				$options['no']     = esc_html__( 'No (0)', 'qi-addons-for-elementor' );
				break;
			case 'order_by':
				$options['date']       = esc_html__( 'Date', 'qi-addons-for-elementor' );
				$options['ID']         = esc_html__( 'ID', 'qi-addons-for-elementor' );
				$options['menu_order'] = esc_html__( 'Menu Order', 'qi-addons-for-elementor' );
				$options['name']       = esc_html__( 'Post Name', 'qi-addons-for-elementor' );
				$options['rand']       = esc_html__( 'Random', 'qi-addons-for-elementor' );
				$options['title']      = esc_html__( 'Title', 'qi-addons-for-elementor' );
				break;
			case 'order':
				$options['DESC'] = esc_html__( 'Descending', 'qi-addons-for-elementor' );
				$options['ASC']  = esc_html__( 'Ascending', 'qi-addons-for-elementor' );
				break;
			case 'columns_responsive':
				$options['predefined'] = esc_html__( 'Predefined', 'qi-addons-for-elementor' );
				$options['custom']     = esc_html__( 'Custom', 'qi-addons-for-elementor' );
				break;
			case 'yes_no':
				$options['yes'] = esc_html__( 'Yes', 'qi-addons-for-elementor' );
				$options['no']  = esc_html__( 'No', 'qi-addons-for-elementor' );
				break;
			case 'no_yes':
				$options['no']  = esc_html__( 'No', 'qi-addons-for-elementor' );
				$options['yes'] = esc_html__( 'Yes', 'qi-addons-for-elementor' );
				break;
			case 'sidebar_layouts':
				$options['no-sidebar']       = esc_html__( 'No Sidebar', 'qi-addons-for-elementor' );
				$options['sidebar-33-right'] = esc_html__( 'Sidebar 1/3 Right', 'qi-addons-for-elementor' );
				$options['sidebar-25-right'] = esc_html__( 'Sidebar 1/4 Right', 'qi-addons-for-elementor' );
				$options['sidebar-33-left']  = esc_html__( 'Sidebar 1/3 Left', 'qi-addons-for-elementor' );
				$options['sidebar-25-left']  = esc_html__( 'Sidebar 1/4 Left', 'qi-addons-for-elementor' );
				break;
			case 'icon_source':
				$options['icon_pack']  = esc_html__( 'Icon Pack', 'qi-addons-for-elementor' );
				$options['svg_path']   = esc_html__( 'SVG Path', 'qi-addons-for-elementor' );
				$options['predefined'] = esc_html__( 'Predefined', 'qi-addons-for-elementor' );
				break;
			case 'list_image_dimension':
				$options['full']      = esc_html__( 'Original', 'qi-addons-for-elementor' );
				$options['thumbnail'] = esc_html__( 'Thumbnail', 'qi-addons-for-elementor' );
				$options['medium']    = esc_html__( 'Medium', 'qi-addons-for-elementor' );
				$options['large']     = esc_html__( 'Large', 'qi-addons-for-elementor' );
				$options['custom']    = esc_html__( 'Custom', 'qi-addons-for-elementor' );
				$options              = apply_filters( 'qi_addons_for_elementor_filter_framework_pool_list_image_dimension', $options );
				break;
			case 'masonry_images_proportion':
				$options['original'] = esc_html__( 'Original', 'qi-addons-for-elementor' );
				$options['fixed']    = esc_html__( 'Fixed', 'qi-addons-for-elementor' );
				break;
			case 'alignment_icons':
				$options['left']   = array(
					'title' => esc_html__( 'Left', 'qi-addons-for-elementor' ),
					'icon'  => 'eicon-text-align-left',
				);
				$options['center'] = array(
					'title' => esc_html__( 'Center', 'qi-addons-for-elementor' ),
					'icon'  => 'eicon-text-align-center',
				);
				$options['right']  = array(
					'title' => esc_html__( 'Right', 'qi-addons-for-elementor' ),
					'icon'  => 'eicon-text-align-right',
				);
				break;
			case 'alignment_icons_flex':
				$options['flex-start'] = array(
					'title' => esc_html__( 'Flex Start', 'qi-addons-for-elementor' ),
					'icon'  => 'eicon-h-align-left',
				);
				$options['center']     = array(
					'title' => esc_html__( 'Center', 'qi-addons-for-elementor' ),
					'icon'  => 'eicon-h-align-center',
				);
				$options['flex-end']   = array(
					'title' => esc_html__( 'Flex End', 'qi-addons-for-elementor' ),
					'icon'  => 'eicon-h-align-right',
				);
				break;
			case 'alignment_flex':
				$options['flex-start'] = esc_html__( 'Flex Start', 'qi-addons-for-elementor' );
				$options['center']     = esc_html__( 'Center', 'qi-addons-for-elementor' );
				$options['flex-end']   = esc_html__( 'Flex End', 'qi-addons-for-elementor' );
				break;
			case 'alignment_icons_flex_vertical':
				$options['flex-start'] = array(
					'title' => esc_html__( 'Flex Start', 'qi-addons-for-elementor' ),
					'icon'  => 'eicon-v-align-top',
				);
				$options['center']     = array(
					'title' => esc_html__( 'Center', 'qi-addons-for-elementor' ),
					'icon'  => 'eicon-v-align-middle',
				);
				$options['flex-end']   = array(
					'title' => esc_html__( 'Flex End', 'qi-addons-for-elementor' ),
					'icon'  => 'eicon-v-align-bottom',
				);
				break;
			case 'appear_animation':
				$options['none']        = esc_html__( 'None', 'qi-addons-for-elementor' );
				$options['from-bottom'] = esc_html__( 'From Bottom', 'qi-addons-for-elementor' );
				$options['from-top']    = esc_html__( 'From Top', 'qi-addons-for-elementor' );
				$options['from-left']   = esc_html__( 'From Left', 'qi-addons-for-elementor' );
				$options['from-right']  = esc_html__( 'From Right', 'qi-addons-for-elementor' );
				$options['fade']        = esc_html__( 'Fade In', 'qi-addons-for-elementor' );
				break;
			case 'appear_delay':
				$options['random'] = esc_html__( 'Random', 'qi-addons-for-elementor' );
				$options['ms']     = esc_html__( 'Set ms', 'qi-addons-for-elementor' );
				break;
		}

		if ( ! empty( $exclude ) ) {
			foreach ( $exclude as $e ) {
				if ( array_key_exists( $e, $options ) ) {
					unset( $options[ $e ] );
				}
			}
		}

		if ( ! empty( $include ) ) {
			foreach ( $include as $key => $value ) {
				if ( ! array_key_exists( $key, $options ) ) {
					$options[ $key ] = $value;
				}
			}
		}

		return apply_filters( 'qi_addons_for_elementor_filter_select_type_option', $options, $type, $enable_default, $exclude );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_render_page_builder_post_content' ) ) {
	/**
	 * Function that print post content unmodified by page builder
	 *
	 * @param int $id post id
	 */
	function qi_addons_for_elementor_render_page_builder_post_content( $id ) {

		if ( qi_addons_for_elementor_framework_is_installed( 'elementor' ) ) {
			echo qi_addons_for_elementor_get_elementor_instance()->frontend->get_builder_content( $id, true );
		} else {
			the_content();
		}
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_get_custom_post_type_taxonomy_query_args' ) ) {
	/**
	 * Function that return query parameters
	 *
	 * @param array $params - options value
	 * @param array $include - additional query arguments
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_get_custom_post_type_taxonomy_query_args( $params, $include = array() ) {
		$args = array();

		if ( isset( $params['taxonomy'] ) && ! empty( $params['taxonomy'] ) ) {
			$args['taxonomy'] = $params['taxonomy'];
		}

		if ( isset( $params['posts_per_page'] ) && ! empty( $params['posts_per_page'] ) ) {
			$args['number'] = $params['posts_per_page'];
		}

		if ( isset( $params['orderby'] ) && ! empty( $params['orderby'] ) ) {
			$args['orderby'] = $params['orderby'];
		}

		if ( isset( $params['order'] ) && ! empty( $params['order'] ) ) {
			$args['order'] = $params['order'];
		}

		$args['hide_empty'] = isset( $params['hide_empty'] ) && 'yes' === $params['hide_empty'];

		if ( isset( $params['taxonomy_ids'] ) && ! empty( $params['taxonomy_ids'] ) ) {
			$args['include'] = explode( ',', trim( $params['taxonomy_ids'] ) );
		}

		if ( ! empty( $include ) ) {
			foreach ( $include as $key => $value ) {
				if ( ! array_key_exists( $key, $args ) ) {
					$args[ $key ] = $value;
				}
			}
		}

		return $args;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_get_example_text' ) ) {
	function qi_addons_for_elementor_get_example_text( $type = '' ) {
		switch ( $type ) {
			case 'title':
				return esc_html__( 'Example Title', 'qi-addons-for-elementor' );
				break;
			case 'subtitle':
				return esc_html__( 'Example Subtitle', 'qi-addons-for-elementor' );
				break;
			case 'excerpt_short':
				return esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris tempus nisl vitae magna pulvinar laoreet.', 'qi-addons-for-elementor' );
				break;
			case 'excerpt_long':
				return esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris tempus nisl vitae magna pulvinar laoreet. Nullam erat ipsum, mattis nec mollis ac, accumsan a enim. Nunc at euismod arcu. Aliquam ullamcorper eros justo, vel mollis neque facilisis vel. Proin augue tortor, condimentum id sapien a, tempus venenatis massa. Aliquam egestas eget diam sed sagittis. Vivamus consectetur purus vel felis molestie sollicitudin. Vivamus sit amet enim nisl. Cras vitae varius metus, a hendrerit ex. Sed in mi dolor. Proin pretium nibh non volutpat efficitur.', 'qi-addons-for-elementor' );
				break;
			default:
				return esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris tempus nisl vitae magna pulvinar laoreet. Nullam erat ipsum, mattis nec mollis ac, accumsan a enim. Nunc at euismod arcu. Aliquam ullamcorper eros justo, vel mollis neque facilisis vel.', 'qi-addons-for-elementor' );
				break;
		}
	}
}
if ( ! function_exists( 'qi_addons_for_elementor_is_widget_disabled' ) ) {

	/**
	 * Function that returns array with disabled widgets
	 *
	 *
	 * @param string $type - type of select field
	 * @param bool $enable_default - add first element empty for default value
	 * @param array $exclude - array of items to exclude
	 * @param array $include - array of items to include
	 *
	 * @return bool
	 */

	function qi_addons_for_elementor_is_widget_disabled( $widget ) {
		$disabled_widgets = get_option( QI_ADDONS_FOR_ELEMENTOR_DISABLED_WIDGETS );

		$disabled_widgets_folders = array();

		if ( is_array( $disabled_widgets ) && count( $disabled_widgets ) > 0 ) {
			foreach ( $disabled_widgets as $disabled_widget_key => $disabled_widget_value ) {
				$disabled_widgets_folders[] = $disabled_widget_value;
			}
		}

		if ( in_array( basename( $widget ), $disabled_widgets_folders, true ) ) {
			return true;
		}

		return false;

	}
}

if ( ! function_exists( 'qi_addons_for_elementor_get_elementor_icon' ) ) {
	/**
	 * Function that returns icon from Elementor render
	 *
	 * @param array $icon - array of icon params
	 *
	 * @return string
	 */
	function qi_addons_for_elementor_get_elementor_icon( $icon ) {

		ob_start();
		\Elementor\Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
		$html = ob_get_clean();

		return $html;
	}
}

if( ! function_exists( 'qi_addons_for_elementor_get_attachment_image' ) ) {
	/**
	 * Function that returns attachment image with applied wpml object id filter to enable WPML media translation
	 *
	 * @return string
	 */
	function qi_addons_for_elementor_get_attachment_image( $attachment_id, $size = 'thumbnail', $icon = false, $attr = '' ): string {
		$attachment_id = apply_filters( 'wpml_object_id', $attachment_id, 'attachment', true );
		
		return wp_get_attachment_image( $attachment_id, $size, $icon, $attr );
	}
}

if( ! function_exists( 'qi_addons_for_elementor_get_attachment_image_src' ) ) {
	/**
	 * Function that returns attachment image src with applied wpml object id filter to enable WPML media translation
	 *
	 * @return array|false
	 */
	function qi_addons_for_elementor_get_attachment_image_src( $attachment_id, $size = 'thumbnail', $icon = false ) {
		$attachment_id = apply_filters( 'wpml_object_id', $attachment_id, 'attachment', true );
		
		return wp_get_attachment_image_src( $attachment_id, $size, $icon );
	}
}

if( ! function_exists( 'qi_addons_for_elementor_get_attachment_image_url' ) ) {
	/**
	 * Function that returns attachment image url with applied wpml object id filter to enable WPML media translation
	 *
	 * @return string|false
	 */
	function qi_addons_for_elementor_get_attachment_image_url( $attachment_id, $size = 'thumbnail', $icon = false ) {
		$attachment_id = apply_filters( 'wpml_object_id', $attachment_id, 'attachment', true );
		
		return wp_get_attachment_image_url( $attachment_id, $size, $icon );
	}
}

if( ! function_exists( 'qi_addons_for_elementor_get_attachment_url' ) ) {
	/**
	 * Function that returns attachment url with applied wpml object id filter to enable WPML media translation
	 *
	 * @return string|false
	 */
	function qi_addons_for_elementor_get_attachment_url( $attachment_id ) {
		$attachment_id = apply_filters( 'wpml_object_id', $attachment_id, 'attachment', true );
		
		return wp_get_attachment_url( $attachment_id );
	}
}

if( ! function_exists( 'qi_addons_for_elementor_get_attachment_thumb_url' ) ) {
	/**
	 * Function that returns attachment thumb url with applied wpml object id filter to enable WPML media translation
	 *
	 * @return string|false
	 */
	function qi_addons_for_elementor_get_attachment_thumb_url( $attachment_id ) {
		$attachment_id = apply_filters( 'wpml_object_id', $attachment_id, 'attachment', true );
		
		return wp_get_attachment_thumb_url( $attachment_id );
	}
}
