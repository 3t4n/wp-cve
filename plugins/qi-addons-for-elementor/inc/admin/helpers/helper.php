<?php

if ( ! function_exists( 'qi_addons_for_elementor_framework_template_part' ) ) {
	/**
	 * Echo module template part.
	 *
	 * @param string $root path of root folder to start templating from
	 * @param string $module name of the module from inc folder
	 * @param string $template full path of the template to load
	 * @param string $slug
	 * @param array $params array of parameters to pass to template
	 *
	 */
	function qi_addons_for_elementor_framework_template_part( $root, $module, $template, $slug = '', $params = array() ) {
		echo qi_addons_for_elementor_framework_get_template_part( $root, $module, $template, $slug, $params );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_get_template_part' ) ) {
	/**
	 * Loads module template part.
	 *
	 * @param string $root path of root folder to start templating from
	 * @param string $module name of the module from inc folder
	 * @param string $template full path of the template to load
	 * @param string $slug
	 * @param array $params array of parameters to pass to template
	 *
	 * @return string - string containing html of template
	 */
	function qi_addons_for_elementor_framework_get_template_part( $root, $module, $template, $slug = '', $params = array() ) {
		$available_characters = '/[^A-Za-z0-9\_\-\/]/';

		if ( is_scalar( $module ) ) {
			$module = preg_replace( $available_characters, '', $module );
		} else {
			$module = '';
		}

		if ( is_scalar( $template ) ) {
			$template = preg_replace( $available_characters, '', $template );
		} else {
			$template = '';
		}

		$temp = $root . '/' . $module . '/' . $template;

		$template = qi_addons_for_elementor_framework_get_template_with_slug( $temp, $slug );

		return qi_addons_for_elementor_framework_execute_template_with_params( $template, $params );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_list_sc_template_part' ) ) {
	/**
	 * Echo module template part.
	 *
	 * @param string $root path of root folder to start templating from
	 * @param string $module name of the module from inc folder
	 * @param string $template full path of the template to load
	 * @param string $slug
	 * @param array $params array of parameters to pass to template
	 *
	 */
	function qi_addons_for_elementor_framework_list_sc_template_part( $root, $module, $template, $slug = '', $params = array() ) {
		echo qi_addons_for_elementor_framework_get_list_sc_template_part( $root, $module, $template, $slug, $params );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_get_list_sc_template_part' ) ) {
	/**
	 * Echo module template part.
	 *
	 * @param string $root path of root folder to start templating from
	 * @param string $module name of the module from inc folder
	 * @param string $template full path of the template to load
	 * @param string $slug
	 * @param array $params array of parameters to pass to template
	 *
	 * @return string - string containing html of template
	 */
	function qi_addons_for_elementor_framework_get_list_sc_template_part( $root, $module, $template, $slug = '', $params = array() ) {
		$temp_in_variation = false;

		/* In order to use this way of templating, option for list item layout must be called layoyt */
		if ( isset( $params['layout'] ) ) {
			/* Check if folder for variation exists */
			$variation_path = apply_filters( 'qi_addons_for_elementor_filter_framework_list_sc_layout_path', $root . '/' . $module . '/variations/' . $params['layout'], $params );
			if ( file_exists( $variation_path ) ) {
				/* Check if template file in variation folder exists */
				$temp_file = qi_addons_for_elementor_framework_get_template_with_slug( $variation_path . '/' . $template, $slug );

				if ( ! empty( $temp_file ) && file_exists( $temp_file ) ) {
					$template          = $temp_file;
					$temp_in_variation = true;
				}
			}
		}

		/* Template doesn't exist in variation folder, use default one */
		if ( ! $temp_in_variation ) {
			$temp     = $root . '/' . $module . '/templates/' . $template;
			$template = qi_addons_for_elementor_framework_get_template_with_slug( $temp, $slug );
		}

		return qi_addons_for_elementor_framework_execute_template_with_params( $template, $params );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_get_template_with_slug' ) ) {
	/**
	 * Loads module template part.
	 *
	 * @param string $temp temp path to file that is being loaded
	 * @param string $slug slug that should be checked if exists
	 *
	 * @return string - string with template path
	 */
	function qi_addons_for_elementor_framework_get_template_with_slug( $temp, $slug ) {
		$template = '';

		if ( ! empty( $temp ) ) {
			if ( ! empty( $slug ) ) {
				$template = "{$temp}-{$slug}.php";

				if ( ! file_exists( $template ) ) {
					$template = $temp . '.php';
				}
			} else {
				$template = $temp . '.php';
			}
		}

		return $template;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_execute_template_with_params' ) ) {
	/**
	 * Loads module template part.
	 *
	 * @param string $template path to template that is going to be included
	 * @param array $params params that are passed to template
	 *
	 * @return string - template html
	 */
	function qi_addons_for_elementor_framework_execute_template_with_params( $template, $params ) {
		if ( ! empty( $template ) && file_exists( $template ) ) {
			//Extract params so they could be used in template
			if ( is_array( $params ) && count( $params ) ) {
				extract( $params, EXTR_SKIP ); // @codingStandardsIgnoreLine
			}

			ob_start();
			include( $template );
			$html = ob_get_clean();

			return $html;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_get_page_id' ) ) {
	/**
	 * Function that returns current page id
	 * Additional conditional is to check if current page is any wp archive page (archive, category, tag, date etc.) and returns -1
	 *
	 * @return int
	 */
	function qi_addons_for_elementor_framework_get_page_id() {
		$page_id = get_queried_object_id();

		if ( qi_addons_for_elementor_framework_is_wp_template() ) {
			$page_id = - 1;
		}

		return apply_filters( 'qi_addons_for_elementor_filter_framework_page_id', $page_id );
	}
}
if ( ! function_exists( 'qi_addons_for_elementor_framework_get_option_value' ) ) {
	/**
	 * Function that return option value
	 *
	 * @param array|string $scope - option key from database
	 * @param string $type - option type
	 * @param string $name - option key
	 * @param string $default_value
	 * @param int $post_id
	 *
	 * @return string|mixed
	 */
	function qi_addons_for_elementor_framework_get_option_value( $scope, $type, $name, $default_value = '', $post_id = null ) {
		if ( 'meta-box' === $type ) {
			if ( empty( $post_id ) && isset( $_GET['post'] ) && ! empty( $_GET['post'] ) ) {
				$post_id = intval( $_GET['post'] );
			}
			if ( ! empty( $post_id ) ) {
				$value = get_post_meta( $post_id, $name, true );
			}
		} elseif ( 'attachment' === $type ) {
			if ( ! empty( $post_id ) ) {
				$value = get_post_meta( $post_id, $name, true );
			}
		}

		$value = isset( $value ) && ( '0' === $value || ! empty( $value ) ) ? $value : $default_value;

		return $value;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_is_wp_template' ) ) {
	/**
	 * Function that checks if current page default wp page
	 *
	 * @return bool
	 */
	function qi_addons_for_elementor_framework_is_wp_template() {
		return is_archive() || is_search() || is_404() || ( is_front_page() && is_home() );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_inline_style' ) ) {
	/**
	 * Function that echoes generated style attribute
	 *
	 * @param string|array $value - attribute value
	 *
	 * @see qi_addons_for_elementor_framework_get_inline_style()
	 */
	function qi_addons_for_elementor_framework_inline_style( $value ) {
		echo qi_addons_for_elementor_framework_get_inline_style( $value );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_get_inline_style' ) ) {
	/**
	 * Function that generates style attribute and returns generated string
	 *
	 * @param string|array $value - value of style attribute
	 *
	 * @return string generated style attribute
	 *
	 * @see qi_addons_for_elementor_framework_get_inline_style()
	 */
	function qi_addons_for_elementor_framework_get_inline_style( $value ) {
		return qi_addons_for_elementor_framework_get_inline_attr( $value, 'style', ';' );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_class_attribute' ) ) {
	/**
	 * Function that echoes class attribute
	 *
	 * @param string|array $value - value of class attribute
	 *
	 * @see qi_addons_for_elementor_framework_get_class_attribute()
	 */
	function qi_addons_for_elementor_framework_class_attribute( $value ) {
		echo qi_addons_for_elementor_framework_get_class_attribute( $value );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_get_class_attribute' ) ) {
	/**
	 * Function that returns generated class attribute
	 *
	 * @param string|array $value - value of class attribute
	 *
	 * @return string generated class attribute
	 *
	 * @see qi_addons_for_elementor_framework_get_inline_attr()
	 */
	function qi_addons_for_elementor_framework_get_class_attribute( $value ) {
		return qi_addons_for_elementor_framework_get_inline_attr( $value, 'class', ' ' );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_get_inline_attr' ) ) {
	/**
	 * Function that generates html attribute
	 *
	 * @param string|array $value value of html attribute
	 * @param string $attr - name of html attribute to generate
	 * @param string $glue - glue with which to implode $attr. Used only when $attr is array
	 * @param bool $allow_zero_values - allow data to have zero value
	 *
	 * @return string generated html attribute
	 */
	function qi_addons_for_elementor_framework_get_inline_attr( $value, $attr, $glue = '', $allow_zero_values = false ) {
		if ( $allow_zero_values ) {
			if ( '' !== $value ) {

				if ( is_array( $value ) && count( $value ) ) {
					$properties = implode( $glue, $value );
				} else {
					$properties = $value;
				}

				return $attr . '="' . esc_attr( $properties ) . '"';
			}
		} else {
			if ( ! empty( $value ) ) {

				if ( is_array( $value ) && count( $value ) ) {
					$properties = implode( $glue, $value );
				} elseif ( '' !== $value ) {
					$properties = $value;
				} else {
					return '';
				}

				return $attr . '="' . esc_attr( $properties ) . '"';
			}
		}

		return '';
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_inline_attr' ) ) {
	/**
	 * Function that generates html attribute
	 *
	 * @param string|array $value value of html attribute
	 * @param string $attr - name of html attribute to generate
	 * @param string $glue - glue with which to implode $attr. Used only when $attr is array
	 *
	 */
	function qi_addons_for_elementor_framework_inline_attr( $value, $attr, $glue = '' ) {
		echo qi_addons_for_elementor_framework_get_inline_attr( $value, $attr, $glue );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_get_inline_attrs' ) ) {
	/**
	 * Generate multiple inline attributes
	 *
	 * @param array $attrs
	 * @param bool $allow_zero_values
	 *
	 * @return string
	 */
	function qi_addons_for_elementor_framework_get_inline_attrs( $attrs, $allow_zero_values = false ) {
		$output = '';
		if ( is_array( $attrs ) && count( $attrs ) ) {
			if ( $allow_zero_values ) {
				foreach ( $attrs as $attr => $value ) {
					$output .= ' ' . qi_addons_for_elementor_framework_get_inline_attr( $value, $attr, '', true );
				}
			} else {
				foreach ( $attrs as $attr => $value ) {
					$output .= ' ' . qi_addons_for_elementor_framework_get_inline_attr( $value, $attr );
				}
			}
		}

		$output = ltrim( $output );

		return $output;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_inline_attrs' ) ) {
	/**
	 * Echo multiple inline attributes
	 *
	 * @param array $attrs
	 * @param bool $allow_zero_values
	 */
	function qi_addons_for_elementor_framework_inline_attrs( $attrs, $allow_zero_values = false ) {
		echo qi_addons_for_elementor_framework_get_inline_attrs( $attrs, $allow_zero_values );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_string_ends_with' ) ) {
	/**
	 * Checks if $haystack ends with $needle and returns proper bool value
	 *
	 * @param string $haystack - to check
	 * @param string $needle - on end to match
	 *
	 * @return bool
	 */
	function qi_addons_for_elementor_framework_string_ends_with( $haystack, $needle ) {
		if ( '' !== $haystack && '' !== $needle ) {
			return ( substr( $haystack, - strlen( $needle ), strlen( $needle ) ) == $needle );
		}

		return false;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_string_ends_with_typography_units' ) ) {
	/**
	 * Checks if $haystack ends with predefined needles and returns proper bool value
	 *
	 * @param string $haystack - to check
	 *
	 * @return bool
	 */
	function qi_addons_for_elementor_framework_string_ends_with_typography_units( $haystack ) {
		$result  = false;
		$needles = array( 'px', 'em', 'rem' );

		if ( '' !== $haystack ) {
			foreach ( $needles as $needle ) {
				if ( qi_addons_for_elementor_framework_string_ends_with( $haystack, $needle ) ) {
					$result = true;
				}
			}
		}

		return $result;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_string_ends_with_space_units' ) ) {
	/**
	 * Checks if $haystack ends with predefined needles and returns proper bool value
	 *
	 * @param string $haystack - to check
	 * @param bool $additional_units - add additional needles
	 *
	 * @return bool
	 */
	function qi_addons_for_elementor_framework_string_ends_with_space_units( $haystack, $additional_units = false ) {
		$result  = false;
		$needles = array( 'px', '%' );

		if ( $additional_units ) {
			array_push( $needles, 'em', 'rem', ')', 'vh', 'vw' );
		}

		if ( '' !== $haystack ) {
			foreach ( $needles as $needle ) {
				if ( qi_addons_for_elementor_framework_string_ends_with( $haystack, $needle ) ) {
					$result = true;
				}
			}
		}

		return $result;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_wp_kses_html' ) ) {
	/**
	 * Function that does escaping of specific html.
	 * It uses wp_kses function with predefined attributes array.
	 *
	 * @param string $type - type of html element
	 * @param string $content - string to escape
	 *
	 * @return string escaped output
	 * @see wp_kses()
	 *
	 */
	function qi_addons_for_elementor_framework_wp_kses_html( $type, $content ) {
		switch ( $type ) {
			case 'img':
				$atts = array(
					'img' => apply_filters(
						'qi_addons_for_elementor_filter_framework_wp_kses_img_atts',
						array(
							'itemprop' => true,
							'id'       => true,
							'class'    => true,
							'width'    => true,
							'height'   => true,
							'src'      => true,
							'srcset'   => true,
							'sizes'    => true,
							'alt'      => true,
							'title'    => true,
						)
					),
				);
				break;
			case 'svg':
				$atts = apply_filters(
					'qi_addons_for_elementor_filter_framework_wp_kses_svg_atts',
					array(
						'svg'     => array(
							'xmlns'             => true,
							'version'           => true,
							'id'                => true,
							'class'             => true,
							'x'                 => true,
							'y'                 => true,
							'aria-hidden'       => true,
							'aria-labelledby'   => true,
							'role'              => true,
							'width'             => true,
							'height'            => true,
							'viewbox'           => true,
							'enable-background' => true,
							'focusable'         => true,
							'data-prefix'       => true,
							'data-icon'         => true,
						),
						'g'       => array(
							'stroke'       => true,
							'stroke-width' => true,
							'fill'         => true,
							'fill-opacity' => true,
						),
						'rect'    => array(
							'x'      => true,
							'y'      => true,
							'width'  => true,
							'height' => true,
						),
						'title'   => array(
							'title' => true,
						),
						'path'    => array(
							'd'              => true,
							'stroke'         => true,
							'stroke-width'   => true,
							'stroke-linecap' => true,
							'fill'           => true,
							'fill-opacity'   => true,
							'transform'      => true,
						),
						'polygon' => array(
							'points' => true,
						),
					)
				);
				break;
			case 'content':
				$atts = apply_filters(
					'qi_addons_for_elementor_filter_framework_wp_kses_content_atts',
					array(
						'div'    => array(
							'id'             => true,
							'class'          => true,
							'style'          => true,
							'aria-hidden'    => true,
							'data-tf-widget' => true,
						),
						'ol'     => array(
							'class' => true,
							'style' => true,
						),
						'ul'     => array(
							'class' => true,
							'style' => true,
						),
						'li'     => array(
							'class' => true,
							'style' => true,
						),
						'br'     => true,
						'strong' => array(
							'class' => true,
						),
						'b'      => array(
							'class' => true,
						),
						'em'     => array(
							'class' => true,
						),
						'h1'     => array(
							'class' => true,
							'style' => true,
						),
						'h2'     => array(
							'class' => true,
							'style' => true,
						),
						'h3'     => array(
							'class' => true,
							'style' => true,
						),
						'h4'     => array(
							'class' => true,
							'style' => true,
						),
						'h5'     => array(
							'class' => true,
							'style' => true,
						),
						'h6'     => array(
							'class' => true,
							'style' => true,
						),
						'p'      => array(
							'id'    => true,
							'class' => true,
							'style' => true,
						),
						'a'      => array(
							'itemprop' => true,
							'id'       => true,
							'class'    => true,
							'href'     => true,
							'target'   => true,
							'style'    => true,
							'rel'      => true,
							'data-rel' => true,
						),
						'span'   => array(
							'id'    => true,
							'class' => true,
							'style' => true,
						),
						'i'      => array(
							'class' => true,
						),
						'img'    => array(
							'itemprop' => true,
							'id'       => true,
							'class'    => true,
							'width'    => true,
							'height'   => true,
							'src'      => true,
							'srcset'   => true,
							'sizes'    => true,
							'alt'      => true,
							'title'    => true,
						),
						'form'   => array(
							'action' => true,
							'method' => true,
							'id'     => true,
							'name'   => true,
							'class'  => true,
							'target' => true,
						),
						'input'  => array(
							'type'        => true,
							'value'       => true,
							'id'          => true,
							'name'        => true,
							'class'       => true,
							'pattern'     => true,
							'placeholder' => true,
							'size'        => true,
							'minlength'   => true,
							'maxlength'   => true,
						),
						'label'  => array(
							'for' => true,
						),
						'select' => array(
							'id'    => true,
							'name'  => true,
							'class' => true,
						),
						'option' => array(
							'value' => true,
						),
						'sup'      => array(
							'class' => true,
						),
					)
				);
				break;
			case 'script':
				$atts = apply_filters(
					'qi_addons_for_elementor_filter_framework_wp_kses_script_atts',
					array(
						'script' => array(
							'src' => true,
						),
					)
				);
				break;
			default:
				return apply_filters( 'qi_addons_for_elementor_filter_framework_wp_kses_custom', $content, $type );
				break;
		}

		return wp_kses( $content, $atts );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_sanitize_tags' ) ) {
	/**
	 * Function that does escaping of specific html tag.
	 *
	 * @param string $tag - string to escape
	 * @param string $type - type of html element
	 *
	 * @return string escaped output
	 * @see wp_kses()
	 *
	 */
	function qi_addons_for_elementor_framework_sanitize_tags( $tag, $type = 'title', $exclude = array(), $include = array() ) {
		$allowed_tags = array();

		switch ( $type ) {
			case 'list':
				$allowed_tags['ul'] = 'ul';
				$allowed_tags['ol'] = 'ol';
				break;
			case 'title':
			default:
				$allowed_tags['h1']   = 'h1';
				$allowed_tags['h2']   = 'h2';
				$allowed_tags['h3']   = 'h3';
				$allowed_tags['h4']   = 'h4';
				$allowed_tags['h5']   = 'h5';
				$allowed_tags['h6']   = 'h6';
				$allowed_tags['p']    = 'p';
				$allowed_tags['span'] = 'span';
				break;
		}

		if ( ! empty( $exclude ) ) {
			foreach ( $exclude as $e ) {
				if ( array_key_exists( $e, $allowed_tags ) ) {
					unset( $allowed_tags[ $e ] );
				}
			}
		}

		if ( ! empty( $include ) ) {
			foreach ( $include as $key => $value ) {
				if ( ! array_key_exists( $key, $allowed_tags ) ) {
					$allowed_tags[ $key ] = $value;
				}
			}
		}

		$tag = strtolower( sanitize_key( $tag ) );

		if ( in_array( $tag, $allowed_tags ) ) {
			return $tag;
		}

		return '';
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_dynamic_style' ) ) {
	/**
	 * Outputs css based on passed selectors and properties
	 *
	 * @param array|string $selector
	 * @param array $properties
	 *
	 * @return string
	 */
	function qi_addons_for_elementor_framework_dynamic_style( $selector, $properties ) {
		$output = '';
		//check if selector and rules are valid data
		if ( ! empty( $selector ) && ( is_array( $properties ) && count( $properties ) ) ) {

			if ( is_array( $selector ) && count( $selector ) ) {
				$output .= implode( ', ', $selector );
			} else {
				$output .= $selector;
			}

			$output .= ' { ';
			foreach ( $properties as $prop => $value ) {
				if ( '' !== $prop ) {
					$output .= $prop . ': ' . esc_attr( $value ) . ';';
				}
			}

			$output .= '}';
		}

		return $output;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_dynamic_style_responsive' ) ) {
	/**
	 * Outputs css based on passed selectors and properties
	 *
	 * @param array|string $selector
	 * @param array $properties
	 * @param string $min_width
	 * @param string $max_width
	 *
	 * @return string
	 */
	function qi_addons_for_elementor_framework_dynamic_style_responsive( $selector, $properties, $min_width = '', $max_width = '' ) {
		$output = '';
		//check if min width or max width is set
		if ( ! empty( $min_width ) || ! empty( $max_width ) ) {
			$output .= '@media only screen';

			if ( ! empty( $min_width ) ) {
				$output .= ' and (min-width: ' . $min_width . 'px)';
			}

			if ( ! empty( $max_width ) ) {
				$output .= ' and (max-width: ' . $max_width . 'px)';
			}

			$output .= ' { ';

			$output .= qi_addons_for_elementor_framework_dynamic_style( $selector, $properties );

			$output .= '}';
		}

		return $output;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_is_installed' ) ) {
	/**
	 * Function check is some plugin/theme is installed
	 *
	 * @param string $plugin name
	 *
	 * @return bool
	 */
	function qi_addons_for_elementor_framework_is_installed( $plugin ) {
		switch ( $plugin ) :
			case 'elementor':
				return defined( 'ELEMENTOR_VERSION' );
			case 'woocommerce':
				return class_exists( 'WooCommerce' );
			case 'contact_form_7':
				return defined( 'WPCF7_VERSION' );
			case 'wp_forms':
				return defined( 'WPFORMS_VERSION' );
			case 'wpml':
				return defined( 'ICL_SITEPRESS_VERSION' );
			default:
				return apply_filters( 'qi_addons_for_elementor_filter_framework_is_plugin_installed', false, $plugin );

		endswitch;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_is_shortcode_on_page' ) ) {
	/**
	 * Function that checks does some shortcode appears in some field on page
	 *
	 * @param string $shortcode
	 * @param string $content . If content is empty, check current page content
	 *
	 * @return bool
	 */
	function qi_addons_for_elementor_framework_is_shortcode_on_page( $shortcode, $content = '' ) {
		$is_shortcode_on_page = false;

		if ( $shortcode ) {

			if ( '' == $content ) {
				//get content from current page
				$page_id = qi_addons_for_elementor_framework_get_page_id();
				if ( ! empty( $page_id ) ) {
					$current_post = get_post( $page_id );
					if ( is_object( $current_post ) && property_exists( $current_post, 'post_content' ) ) {
						$content = $current_post->post_content;
					}
				}
			}

			if ( has_shortcode( $content, $shortcode ) ) {
				$is_shortcode_on_page = true;
			}
		}

		return $is_shortcode_on_page;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_is_shortcode_on_page_elementor' ) ) {
	/**
	 * Function that checks does some shortcode appears in some field on page
	 *
	 * @param string $shortcode
	 * @param string $content . If content is empty, check current page content
	 *
	 * @return bool
	 */
	function qi_addons_for_elementor_framework_is_shortcode_on_page_elementor( $shortcode, $content = '' ) {
		$is_shortcode_on_page = false;

		if ( $shortcode ) {

			if ( '' == $content ) {
				//get content from current page
				$page_id = qi_addons_for_elementor_framework_get_page_id();
				if ( ! empty( $page_id ) ) {
					$current_elementor_page = get_post_meta( $page_id, '_elementor_data', true );
					$content                = json_decode( $current_elementor_page );
				}
			}

			if ( is_array( $content ) && count( $content ) ) {
				foreach ( $content as $section ) {
					foreach ( $section->elements as $column ) {
						foreach ( $column->elements as $item ) {
							if ( 'widget' === $item->elType && $item->widgetType == $shortcode ) {
								return true;
							} elseif ( 'section' === $item->el_type ) {
								foreach ( $item->elements as $inner_column ) {
									foreach ( $inner_column->elements as $inner_item ) {
										if ( 'widget' === $inner_item->elType && $inner_item->widgetType == $shortcode ) {
											return true;
										}
									}
								}
							}
						}
					}
				}
			}
		}

		return $is_shortcode_on_page;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_call_shortcode' ) ) {
	/**
	 * @param      $base - shortcode base
	 * @param      $params - shortcode parameters
	 * @param null $content - shortcode content
	 *
	 * @return mixed|string
	 */
	function qi_addons_for_elementor_framework_call_shortcode( $base, $params, $content = null ) {
		global $shortcode_tags;

		if ( ! isset( $shortcode_tags[ $base ] ) ) {
			return false;
		}

		if ( is_array( $shortcode_tags[ $base ] ) ) {
			$shortcode = $shortcode_tags[ $base ];

			return call_user_func(
				array(
					$shortcode[0],
					$shortcode[1],
				),
				$params,
				$content,
				$base
			);
		}

		return call_user_func( $shortcode_tags[ $base ], $params, $content, $base );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_map_shortcode_fields' ) ) {
	/**
	 * @param array $default_options - default supported options
	 * @param array $params - params set
	 *
	 * @return array - formatted array with merge default and passed options
	 */
	function qi_addons_for_elementor_framework_map_shortcode_fields( $default_options, $params ) {
		$atts = (array) $params;
		$out  = array();

		foreach ( $default_options as $name => $default ) {
			if ( array_key_exists( $name, $atts ) ) {
				$out[ $name ] = $atts[ $name ];
			} else {
				$out[ $name ] = $default;
			}
		}

		return $out;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_get_ajax_status' ) ) {
	/**
	 * Function that return status from ajax functions
	 *
	 * @param string $status - success or error
	 * @param string $message - ajax message value
	 * @param string|array $data - returned value
	 * @param string $redirect - url address
	 */
	function qi_addons_for_elementor_framework_get_ajax_status( $status, $message, $data = null, $redirect = '' ) {
		$response = array(
			'status'   => esc_attr( $status ),
			'message'  => esc_html( $message ),
			'data'     => $data,
			'redirect' => $redirect,
		);

		$response = apply_filters( 'qi_addons_for_elementor_filter_framework_ajax_status', $response );

		$output = json_encode( $response );

		exit( $output );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_get_pages' ) ) {
	/**
	 * Returns array of pages item
	 *
	 * @param bool $enable_default - add first element empty for default value
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_framework_get_pages( $enable_default = false ) {
		$options = array();

		$pages = get_pages();
		if ( ! empty( $pages ) ) {

			if ( $enable_default ) {
				$options[''] = esc_html__( 'Default', 'qi-addons-for-elementor' );
			}

			foreach ( $pages as $page ) {
				$options[ $page->ID ] = $page->post_title;
			}
		}

		return $options;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_svg_icon' ) ) {
	/**
	 * Function that echo svg html icon
	 *
	 * @param string $name - icon name
	 * @param string $class_name - custom html tag class name
	 */
	function qi_addons_for_elementor_framework_svg_icon( $name, $class_name = '' ) {
		echo qi_addons_for_elementor_framework_get_svg_icon( $name, $class_name );
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_get_svg_icon' ) ) {
	/**
	 * Returns svg html
	 *
	 * @param string $name - icon name
	 * @param string $class_name - custom html tag class name
	 *
	 * @return string|html
	 */
	function qi_addons_for_elementor_framework_get_svg_icon( $name, $class_name = '' ) {
		$html  = '';
		$class = isset( $class_name ) && ! empty( $class_name ) ? $class_name : '';

		switch ( $name ) {
			case 'expand':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="92px" height="92px" viewBox="0 0 92 92" enable-background="new 0 0 92 92" xml:space="preserve"><path d="M90,6l0,20c0,2.2-1.8,4-4,4l0,0c-2.2,0-4-1.8-4-4V15.7L58.8,38.9c-0.8,0.8-1.8,1.2-2.8,1.2c-1,0-2-0.4-2.8-1.2c-1.6-1.6-1.6-4.1,0-5.7L76.3,10H66c-2.2,0-4-1.8-4-4c0-2.2,1.8-4,4-4h20c1.1,0,2.1,0.4,2.8,1.2C89.6,3.9,90,4.9,90,6z M86,62c-2.2,0-4,1.8-4,4v10.3L59.2,53.7c-1.6-1.6-4.2-1.6-5.8,0c-1.6,1.6-1.6,4.1-0.1,5.7L75.9,82H65.6c0,0,0,0,0,0c-2.2,0-4,1.8-4,4s1.8,4,4,4l20,0l0,0c1.1,0,2.3-0.4,3-1.2c0.8-0.8,1.4-1.8,1.4-2.8V66C90,63.8,88.2,62,86,62zM32.8,53.5L10,76.3V66c0-2.2-1.8-4-4-4h0c-2.2,0-4,1.8-4,4l0,20c0,1.1,0.4,2.1,1.2,2.8C4,89.6,5,90,6.1,90h20c2.2,0,4-1.8,4-4c0-2.2-1.8-4-4-4H15.7l22.8-22.8c1.6-1.6,1.5-4.1,0-5.7C37,51.9,34.4,51.9,32.8,53.5z M15.7,10.4l10.3,0h0c2.2,0,4-1.8,4-4s-1.8-4-4-4l-20,0h0c-1.1,0-2.1,0.4-2.8,1.2C2.4,4.3,2,5.3,2,6.4l0,20c0,2.2,1.8,4,4,4c2.2,0,4-1.8,4-4V16l23.1,23.1c0.8,0.8,1.8,1.2,2.8,1.2c1,0,2-0.4,2.8-1.2c1.6-1.6,1.6-4.1,0-5.7L15.7,10.4z"/></svg>';
				break;
			case 'trash':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="92px" height="92px" viewBox="0 0 92 92" enable-background="new 0 0 92 92" xml:space="preserve"><path d="M78.4,30.4l-3.1,57.8c-0.1,2.1-1.9,3.8-4,3.8H20.7c-2.1,0-3.9-1.7-4-3.8l-3.1-57.8c-0.1-2.2,1.6-4.1,3.8-4.2c2.2-0.1,4.1,1.6,4.2,3.8l2.9,54h43.1l2.9-54c0.1-2.2,2-3.9,4.2-3.8C76.8,26.3,78.5,28.2,78.4,30.4zM89,17c0,2.2-1.8,4-4,4H7c-2.2,0-4-1.8-4-4s1.8-4,4-4h22V4c0-1.9,1.3-3,3.2-3h27.6C61.7,1,63,2.1,63,4v9h22C87.2,13,89,14.8,89,17zM36,13h20V8H36V13z M37.7,78C37.7,78,37.7,78,37.7,78c2,0,3.5-1.9,3.5-3.8l-1-43.2c0-1.9-1.6-3.5-3.6-3.5c-1.9,0-3.5,1.6-3.4,3.6l1,43.3C34.2,76.3,35.8,78,37.7,78z M54.2,78c1.9,0,3.5-1.6,3.5-3.5l1-43.2c0-1.9-1.5-3.6-3.4-3.6c-2,0-3.5,1.5-3.6,3.4l-1,43.2C50.6,76.3,52.2,78,54.2,78C54.1,78,54.1,78,54.2,78z"/></svg>';
				break;
			case 'search':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"><path d="M18.869 19.162l-5.943-6.484c1.339-1.401 2.075-3.233 2.075-5.178 0-2.003-0.78-3.887-2.197-5.303s-3.3-2.197-5.303-2.197-3.887 0.78-5.303 2.197-2.197 3.3-2.197 5.303 0.78 3.887 2.197 5.303 3.3 2.197 5.303 2.197c1.726 0 3.362-0.579 4.688-1.645l5.943 6.483c0.099 0.108 0.233 0.162 0.369 0.162 0.121 0 0.242-0.043 0.338-0.131 0.204-0.187 0.217-0.503 0.031-0.706zM1 7.5c0-3.584 2.916-6.5 6.5-6.5s6.5 2.916 6.5 6.5-2.916 6.5-6.5 6.5-6.5-2.916-6.5-6.5z"></path></svg>';
				break;
			case 'spinner':
				$html = '<svg class="' . esc_attr( $class ) . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg>';
				break;
		}

		return $html;
	}
}
if ( ! function_exists( 'qi_addons_for_elementor_extend_plugin_info' ) ) {

	/**
	 * Function that extended global plugin links on plugins page
	 *
	 * @param array $plugin_meta
	 * @param string $plugin_file
	 *
	 * @return array
	 */

	function qi_addons_for_elementor_extend_plugin_info( $plugin_meta, $plugin_file ) {

		if ( QI_ADDONS_FOR_ELEMENTOR_PLUGIN_BASE_FILE === $plugin_file ) {

			$additioanal_plugin_meta = array(
				'documentation' => '<a href="https://helpcenter.qodeinteractive.com/" target="_blank">' . esc_html__( 'Help Center', 'qi-addons-for-elementor' ) . '</a>',
				'video'         => '<a href="https://www.youtube.com/playlist?list=PLNypD600o6nK_5QYh--5K6B0ObmgVta6M" target="_blank">' . esc_html__( 'Video Tutorials', 'qi-addons-for-elementor' ) . '</a>',
			);

			$plugin_meta = array_merge( $plugin_meta, $additioanal_plugin_meta );
		}

		return $plugin_meta;
	}

	add_filter( 'plugin_row_meta', 'qi_addons_for_elementor_extend_plugin_info', 10, 2 );
}

if ( ! function_exists( 'qi_addons_for_elementor_extend_plugin_actions' ) ) {

	function qi_addons_for_elementor_extend_plugin_actions( $links ) {

		$links['upgrade'] = '<a href="https://qodeinteractive.com/qi-addons-for-elementor/?utm_source=upgrade&utm_medium=qi-addons&utm_campaign=gopremium" target="_blank">' . esc_html__( 'Upgrade', 'qi-addons-for-elementor' ) . '</a>';

		return apply_filters( 'qi_addons_for_elementor_filter_extend_plugin_actions', $links );
	}

	add_filter( 'plugin_action_links_' . QI_ADDONS_FOR_ELEMENTOR_PLUGIN_BASE_FILE, 'qi_addons_for_elementor_extend_plugin_actions' );
}
