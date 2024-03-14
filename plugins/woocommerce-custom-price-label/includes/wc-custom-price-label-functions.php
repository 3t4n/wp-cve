<?php
/**
 * WooCommerce Custom Price Labels - Functions
 *
 * @version 2.5.11
 */

if ( ! function_exists( 'alg_get_options_group_name' ) ) {
	/**
	 * alg_get_options_group_name.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 * @todo    rename all options
	 */
	function alg_get_options_group_name() {
		return 'simple_is_custom_pricing_label';
	}
}

if ( ! function_exists( 'alg_get_options_sections' ) ) {
	/**
	 * alg_get_options_sections.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function alg_get_options_sections() {
		return array (
			''                => __( 'Instead of the price', 'woocommerce-custom-price-label' ),
			'_before'         => __( 'Before the price', 'woocommerce-custom-price-label' ),
			'_between'        => __( 'Between regular and sale prices', 'woocommerce-custom-price-label' ),
			'_after'          => __( 'After the price', 'woocommerce-custom-price-label' ),
		);
	}
}

if ( ! function_exists( 'alg_get_options_sections_ids' ) ) {
	/**
	 * alg_get_options_sections_ids.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function alg_get_options_sections_ids() {
		return array(
			''                => 'instead',
			'_before'         => 'before',
			'_between'        => 'between',
			'_after'          => 'after',
		);
	}
}

if ( ! function_exists( 'alg_get_options_section_variations_main' ) ) {
	/**
	 * alg_get_options_section_variations_main.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function alg_get_options_section_variations_main() {
		return array(
			'_text'           => '',
			''                => __( 'Enable', 'woocommerce-custom-price-label' ),
		);
	}
}

if ( ! function_exists( 'alg_get_options_section_variations_visibility' ) ) {
	/**
	 * alg_get_options_section_variations_visibility.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function alg_get_options_section_variations_visibility() {
		return array (
			'_hide_on'        => __( 'Visibility: Hide on', 'woocommerce-custom-price-label' ),
			'_show_on'        => __( 'Visibility: Show only on', 'woocommerce-custom-price-label' ),
			'_roles_to_hide'  => __( 'User Roles: Hide for', 'woocommerce-custom-price-label' ),
			'_roles_to_show'  => __( 'User Roles: Show only for', 'woocommerce-custom-price-label' ),
		);
	}
}

if ( ! function_exists( 'alg_get_options_section_variations' ) ) {
	/**
	 * alg_get_options_section_variations.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function alg_get_options_section_variations() {
		return array_merge( alg_get_options_section_variations_main(), alg_get_options_section_variations_visibility() );
	}
}

if ( ! function_exists( 'alg_get_options_section_variations_visibility_options' ) ) {
	/**
	 * alg_get_options_section_variations_visibility_options.
	 *
	 * @version 2.4.0
	 * @since   2.3.0
	 */
	function alg_get_options_section_variations_visibility_options() {
		return array(
			'home'           => __( 'Homepage', 'woocommerce-custom-price-label' ),
			'products'       => __( 'Archives (e.g. categories)', 'woocommerce-custom-price-label' ),
			'single'         => __( 'Single product page', 'woocommerce-custom-price-label' ),
			'related'        => __( 'Single product page (e.g. related)', 'woocommerce-custom-price-label' ),
			'page'           => __( 'All pages (except homepage)', 'woocommerce-custom-price-label' ),
			'cart'           => __( 'Cart page', 'woocommerce-custom-price-label' ),
			'variable'       => __( 'Variable: main price', 'woocommerce-custom-price-label' ),
			'variation'      => __( 'Variable: all variations', 'woocommerce-custom-price-label' ),
		);
	}
}

if ( ! function_exists( 'alg_get_options_section_variations_deprecated' ) ) {
	/**
	 * alg_get_options_section_variations_deprecated.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function alg_get_options_section_variations_deprecated() {
		return array(
			// Options deprecated since v2.3.0
			'_home'           => __( 'Hide on homepage', 'woocommerce-custom-price-label' ),
			'_products'       => __( 'Hide on archives (e.g. categories)', 'woocommerce-custom-price-label' ),
			'_single'         => __( 'Hide on single product page', 'woocommerce-custom-price-label' ),
			'_page'           => __( 'Hide on all pages', 'woocommerce-custom-price-label' ),
			'_cart'           => __( 'Hide on cart page', 'woocommerce-custom-price-label' ),
			'_variable'       => __( 'Hide for main price', 'woocommerce-custom-price-label' ),
			'_variation'      => __( 'Hide for all variations', 'woocommerce-custom-price-label' ),
		);
	}
}

if ( ! function_exists( 'alg_get_user_roles' ) ) {
	/**
	 * alg_get_user_roles.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 */
	function alg_get_user_roles() {
		global $wp_roles;
		$all_roles = ( isset( $wp_roles ) && is_object( $wp_roles ) ) ? $wp_roles->roles : array();
		$all_roles = apply_filters( 'editable_roles', $all_roles );
		$all_roles = array_merge( array(
			'guest' => array(
				'name'         => __( 'Guest', 'price-by-user-role-for-woocommerce' ),
				'capabilities' => array(),
			) ), $all_roles );
		return $all_roles;
	}
}

if ( ! function_exists( 'alg_get_user_roles_options' ) ) {
	/**
	 * alg_get_user_roles_options.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 */
	function alg_get_user_roles_options() {
		$all_roles = alg_get_user_roles();
		$all_roles_options = array();
		foreach ( $all_roles as $_role_key => $_role ) {
			$all_roles_options[ $_role_key ] = $_role['name'];
		}
		return $all_roles_options;
	}
}

if ( ! function_exists( 'alg_get_table_html' ) ) {
	/**
	 * alg_get_table_html.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function alg_get_table_html( $data, $args = array() ) {
		$defaults = array(
			'table_class'        => '',
			'table_style'        => '',
			'row_styles'         => '',
			'table_heading_type' => 'horizontal',
			'columns_classes'    => array(),
			'columns_styles'     => array(),
		);
		$args = array_merge( $defaults, $args );
		extract( $args );
		$table_class = ( '' == $table_class ) ? '' : ' class="' . $table_class . '"';
		$table_style = ( '' == $table_style ) ? '' : ' style="' . $table_style . '"';
		$row_styles  = ( '' == $row_styles )  ? '' : ' style="' . $row_styles  . '"';
		$html = '';
		$html .= '<table' . $table_class . $table_style . '>';
		$html .= '<tbody>';
		foreach( $data as $row_number => $row ) {
			$html .= '<tr' . $row_styles . '>';
			foreach( $row as $column_number => $value ) {
				$th_or_td = ( ( 0 === $row_number && 'horizontal' === $table_heading_type ) || ( 0 === $column_number && 'vertical' === $table_heading_type ) ) ? 'th' : 'td';
				$column_class = ( ! empty( $columns_classes ) && isset( $columns_classes[ $column_number ] ) ) ? ' class="' . $columns_classes[ $column_number ] . '"' : '';
				$column_style = ( ! empty( $columns_styles ) && isset( $columns_styles[ $column_number ] ) ) ? ' style="' . $columns_styles[ $column_number ] . '"' : '';
				$html .= '<' . $th_or_td . $column_class . $column_style . '>';
				$html .= $value;
				$html .= '</' . $th_or_td . '>';
			}
			$html .= '</tr>';
		}
		$html .= '</tbody>';
		$html .= '</table>';
		return $html;
	}
}

if ( ! function_exists( 'wccpl_get_pro_message' ) ) {
	/**
	 * wccpl_get_pro_message.
	 *
	 * @version 2.5.11
	 */
	function wccpl_get_pro_message() {
		return sprintf( __( 'Get <a href="%s">Custom Price Labels for WooCommerce Pro</a> plugin to change value.', 'woocommerce-custom-price-label' ), 'https://wpwham.com/products/custom-price-labels-for-woocommerce/?utm_source=wccpl_get_pro_message&utm_campaign=free&utm_medium=custom_price_label' );
	}
}