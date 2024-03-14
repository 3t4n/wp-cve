<?php

if ( ! function_exists( 'qi_addons_for_elementor_get_list_shortcode_item_image' ) ) {
	/**
	 * Function that generates thumbnail img tag for list shortcodes
	 *
	 * @param string $image_dimension
	 * @param int $attachment_id
	 *
	 * @return string generated img tag
	 *
	 * @see qi_addons_for_elementor_framework_generate_thumbnail()
	 */
	function qi_addons_for_elementor_get_list_shortcode_item_image( $image_dimension = 'full', $attachment_id = 0, $custom_image_width = 0, $custom_image_height = 0 ) {
		$item_id = get_the_ID();
		if ( 'custom' !== $image_dimension ) {
			if ( ! empty( $attachment_id ) ) {
				$html = qi_addons_for_elementor_get_attachment_image( $attachment_id, $image_dimension );
			} else {
				$html = get_the_post_thumbnail( $item_id, $image_dimension );
			}
		} else {
			if ( ! empty( $custom_image_width ) && ! empty( $custom_image_height ) ) {
				if ( ! empty( $attachment_id ) ) {
					$html = qi_addons_for_elementor_framework_generate_thumbnail( intval( $attachment_id ), $custom_image_width, $custom_image_height );
				} else {
					$html = qi_addons_for_elementor_framework_generate_thumbnail( intval( get_post_thumbnail_id( $item_id ) ), $custom_image_width, $custom_image_height );
				}
			} else {
				$html = get_the_post_thumbnail( $item_id, $image_dimension );
			}
		}

		return apply_filters( 'qi_addons_for_elementor_filter_list_shortcode_item_image', $html, $attachment_id );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_icon_load_assets' ) ) {
	function qi_addons_for_elementor_icon_load_assets() {

		wp_enqueue_style( 'elementor-icons-shared-0' );
		wp_enqueue_style( 'elementor-icons-fa-brands' );
		wp_enqueue_style( 'elementor-icons-fa-regular' );
		wp_enqueue_style( 'elementor-icons-fa-solid' );
	}
}


if ( ! function_exists( 'qi_addons_for_elementor_icon_necessary_styles' ) ) {
	function qi_addons_for_elementor_icon_necessary_styles() {

		$icon_styles = array(
			'elementor-icons-shared-0'   => array(
				'registered' => true,
			),
			'elementor-icons-fa-brands'  => array(
				'registered' => true,
			),
			'elementor-icons-fa-solid'   => array(
				'registered' => true,
			),
			'elementor-icons-fa-regular' => array(
				'registered' => true,
			),
		);

		return apply_filters( 'qi_addons_for_elementor_filter_icon_necessary_styles', $icon_styles );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_get_cpt_items' ) ) {
	/**
	 * Returns array of custom post items
	 *
	 * @param string $cpt_slug
	 * @param array $args
	 * @param bool $enable_default - add first element empty for default value
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_get_cpt_items( $cpt_slug, $args = array(), $enable_default = false ) {
		$options    = array();
		$query_args = array(
			'post_status'    => 'publish',
			'post_type'      => $cpt_slug,
			'posts_per_page' => '-1',
			'fields'         => 'ids',
		);

		if ( ! empty( $args ) ) {
			foreach ( $args as $key => $value ) {
				if ( ! empty( $value ) ) {
					$query_args[ $key ] = $value;
				}
			}
		}

		$cpt_items = new \WP_Query( $query_args );

		if ( $cpt_items->have_posts() ) {

			if ( $enable_default ) {
				$options[''] = esc_html__( 'Default', 'qi-addons-for-elementor' );
			}

			foreach ( $cpt_items->posts as $id ) :
				$options[ $id ] = get_the_title( $id );
			endforeach;
		}

		wp_reset_postdata();

		return $options;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_return_elementor_templates' ) ) {
	/**
	 * Function that returns all Elementor saved templates
	 */
	function qi_addons_for_elementor_return_elementor_templates() {
		if ( qi_addons_for_elementor_framework_is_installed( 'elementor' ) ) {
			$all_templates = qi_addons_for_elementor_get_cpt_items( 'elementor_library' );
			$templates     = array();

			foreach ( $all_templates as $id => $title ) {
				$template_type = get_post_meta( $id, '_elementor_template_type', true );
				$allowed_types = array( 'section', 'page' );
				if ( in_array( $template_type, $allowed_types, true ) ) {
					$title            = get_the_title( $id );
					$templates[ $id ] = $title . ' (' . $template_type . ')';
				}
			}

			return $templates;
		} else {
			return array();
		}
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_generate_elementor_templates_control' ) ) {
	/**
	 * Function that adds Template Elementor Control
	 */
	function qi_addons_for_elementor_generate_elementor_templates_control() {
		$templates = qi_addons_for_elementor_return_elementor_templates();

		if ( ! empty( $templates ) ) {
			$options = array(
				'0' => '— ' . esc_html__( 'Select', 'qi-addons-for-elementor' ) . ' —',
			);

			$options = $options + $templates;

			return $options;
		} else {
			return array();
		}
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_explode_link_custom_attributes' ) ) {
	/**
	 * Function that explodes custom_attributes string into an array
	 * @param string $custom_attributes
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_explode_link_custom_attributes( $custom_attributes ) {
		$custom_attrs_array = array();

		if ( ! empty( $custom_attributes ) ) {
			$custom_attributes_array = explode( ',', esc_attr( $custom_attributes ) );

			if ( count( $custom_attributes_array ) ) {
				foreach ( $custom_attributes_array as $attribute ) {
					$single_attribute = explode( '|', trim( $attribute ) );
					
					$single_attribute_key = mb_strtolower( $single_attribute[0] );
					
					//Leave only allowed characters
					preg_match( '/[-_a-z0-9]+/', $single_attribute_key, $single_key_matches );
					
					if ( empty( $single_key_matches[0] ) ) {
						continue;
					}
					
					$single_attribute_key = $single_key_matches[0];
					
					//  Remove unallowed js events
					if ( 'on' === substr( $single_attribute_key, 0, 2 ) || 'href' === $single_attribute_key ) {
						continue;
					}
					
					if ( 2 === count( $single_attribute ) ) {
						$custom_attrs_array[ $single_attribute_key ] = trim( $single_attribute[1] );
					}
					
				}
			}
		}

		return $custom_attrs_array;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_get_link_attributes' ) ) {
	function qi_addons_for_elementor_get_link_attributes( $link ) {
		$link_attributes   = array();
		$custom_attributes = array();

		if ( isset( $link['is_external'] ) && 'on' === $link['is_external'] ) {
			$link_attributes['target'] = '_blank';
		} else {
			$link_attributes['target'] = '_self';
		}

		if ( isset( $link['nofollow'] ) && ! empty( $link['nofollow'] ) ) {
			$link_attributes['rel'] = 'nofollow';
		}

		if ( isset( $link['custom_attributes'] ) && ! empty( $link['custom_attributes'] ) ) {
			$custom_attributes = qi_addons_for_elementor_explode_link_custom_attributes( esc_attr( $link['custom_attributes'] ) );
		}

		$link_attributes += $custom_attributes;

		return $link_attributes;
	}
}

