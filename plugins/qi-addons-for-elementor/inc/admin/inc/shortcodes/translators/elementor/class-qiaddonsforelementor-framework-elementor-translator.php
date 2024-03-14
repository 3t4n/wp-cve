<?php

class QiAddonsForElementor_Framework_Elementor_Translator {
	private static $instance;

	public function __construct() {
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_widget_category' ) );
		add_filter( 'elementor/editor/localize_settings', array( $this, 'get_elementor_config' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'add_inline_style' ) );
	}

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function generate_option_params( $shortcode ) {
		$shortcode_options = $shortcode->get_options();
		$formatted_options = array();

		if ( $shortcode->get_is_parent_shortcode() ) {
			$children = $shortcode->get_child_elements();

			foreach ( $children as $child ) {
				$child_object = qi_addons_for_elementor_framework_get_framework_root()->get_shortcodes()->get_shortcode( $child );

				$shortcode_options['elements_of_child_widget'] = array(
					'field_type' => 'repeater',
					'name'       => 'elements_of_child_widget',
					'title'      => $child_object->get_name(),
					'items'      => array(),
				);

				foreach ( $child_object->get_options() as $child_option_key => $child_option ) {

					$visibility = isset( $child_option['visibility'] ) ? $child_option['visibility'] : array();
					if ( ! isset( $visibility['map_for_page_builder'] ) || ( isset( $visibility['map_for_page_builder'] ) && true === $visibility['map_for_page_builder'] ) ) {
						$shortcode_options['elements_of_child_widget']['items'][] = $child_option;
					}
				}

				if ( $child_object->get_is_parent_shortcode() ) {
					$shortcode_options['elements_of_child_widget']['items'][] = array(
						'field_type' => 'html',
						'name'       => 'content',
						'title'      => esc_html__( 'Content', 'qi-addons-for-elementor' ),
					);
				}
			}
		}

		foreach ( $shortcode_options as $option_key => $option ) {

			//generate default_value if not set
			if ( ! isset( $option['default_value'] ) ) {
				$default = $this->generate_default_value( $option );

				if ( ! empty( $default ) ) {
					$option['default_value'] = $default;
				}
			}

			$formatted_options = array_merge_recursive( $formatted_options, $this->generate_options_array( $option_key, $option ) );
		}

		return $formatted_options;
	}

	function generate_options_array( $option_key, $option ) {
		$formatted_options = array();

		/*** Visibility Options ***/
		$visibility = isset( $option['visibility'] ) ? $option['visibility'] : array();
		$group      = isset( $option['group'] ) ? str_replace( ' ', '-', strtolower( $option['group'] ) ) . '-elementor' : 'general';

		if ( ! isset( $visibility['map_for_page_builder'] ) || ( isset( $visibility['map_for_page_builder'] ) && true === $visibility['map_for_page_builder'] ) ) {
			$formatted_options[ $group ]['fields'][ $option_key ]['field_type'] = $option['field_type'];
			$formatted_options[ $group ]['fields'][ $option_key ]['label']      = $option['title'];

			$dynamic_allowed_types = array( 'image', 'text', 'textarea', 'link' );

			if ( in_array( $option['field_type'], $dynamic_allowed_types, true ) ) {
				if ( isset( $option['dynamic'] ) ) {
					$formatted_options[ $group ]['fields'][ $option_key ]['dynamic'] = $option['dynamic'];
				} else {
					$formatted_options[ $group ]['fields'][ $option_key ]['dynamic'] = array( 'active' => true );
				}
			}

			if ( isset( $option['default_value'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['default'] = $option['default_value'];
			}
			if ( isset( $option['options'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['options'] = $option['options'];

				if ( ! isset( $option['default_value'] ) && ! empty( key( $option['options'] ) ) ) {
					$formatted_options[ $group ]['fields'][ $option_key ]['default'] = key( $option['options'] );
				}
			}
			if ( isset( $option['description'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['description'] = $option['description'];
			}
			if ( isset( $option['multiple'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['multiple'] = $option['multiple'];
			}
			if ( isset( $option['alpha'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['alpha'] = $option['alpha'];
			}
			if ( isset( $option['size_units'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['size_units'] = $option['size_units'];
			}
			if ( isset( $option['range'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['range'] = $option['range'];
			}
			if ( isset( $option['allowed_dimensions'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['allowed_dimensions'] = $option['allowed_dimensions'];
			}
			if ( isset( $option['placeholder'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['placeholder'] = $option['placeholder'];
			}
			if ( isset( $option['selector'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['selector'] = $option['selector'];
			}
			if ( isset( $option['selectors'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['selectors'] = $option['selectors'];
			}
			if ( isset( $option['selectors_dictionary'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['selectors_dictionary'] = $option['selectors_dictionary'];
			}
			if ( isset( $option['responsive'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['responsive_enabled'] = $option['responsive'];
			}
			if ( isset( $option['devices'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['devices'] = $option['devices'];
			}
			if ( isset( $option['prefix_class'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['prefix_class'] = $option['prefix_class'];
			}
			if ( isset( $option['exclude_options'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['exclude'] = $option['exclude_options'];
			}
			if ( isset( $option['media_types'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['media_types'] = $option['media_types'];
			}
			if ( isset( $option['types'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['types'] = $option['types'];
			}
			if ( isset( $option['picker_options'] ) ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['picker_options'] = $option['picker_options'];
			}
			/*** Dependency Options ***/

			if ( isset( $option['dependency'] ) ) {
				if ( isset( $option['dependency']['show'] ) ) {
					$dependency     = $option['dependency']['show'];
					$dependency_key = key( $dependency );

					if ( '' === $dependency[ $dependency_key ]['values'] ) {
						$option['condition'] = array(
							$dependency_key => array( '' ),
						);
					} elseif ( 1 === count( $dependency ) ) {
						$option['condition'] = array(
							$dependency_key => $dependency[ $dependency_key ]['values'],
						);
					} else {
						$option['condition']['terms'] = array();
						foreach ( $dependency as $key => $value ) {
							$operator = '==';

							if ( is_array( $value['values'] ) && 1 < count( $value['values'] ) ) {
								$operator = 'in';
							}
							$option['condition']['terms'][] = array(
								'name'     => $key,
								'operator' => $operator,
								'value'    => $value['values'],
							);
						}
						if ( isset( $option['dependency']['relation'] ) ) {
							$option['condition']['relation'] = $option['dependency']['relation'];
						}
					}

					if ( 1 < count( $dependency ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['conditions'] = $option['condition'];
					} else {
						$formatted_options[ $group ]['fields'][ $option_key ]['condition'] = $option['condition'];
					}
				}

				if ( isset( $option['dependency']['hide'] ) ) {
					$dependency     = $option['dependency']['hide'];
					$dependency_key = key( $dependency );

					if ( '' === $dependency[ $dependency_key ]['values'] ) {
						$option['condition'] = array(
							$dependency_key . '!' => array( '' ),
						);
					} elseif ( 1 === count( $dependency ) ) {
						$option['condition'] = array(
							$dependency_key . '!' => $dependency[ $dependency_key ]['values'],
						);
					} else {
						$option['condition']['terms'] = array();
						foreach ( $dependency as $key => $value ) {
							$operator = '!==';

							if ( is_array( $value['values'] ) && 1 < count( $value['values'] ) ) {
								$operator = '!in';
							}
							$option['condition']['terms'][] = array(
								'name'     => $key,
								'operator' => $operator,
								'value'    => $value['values'],
							);
						}
						if ( isset( $option['dependency']['relation'] ) ) {
							switch ( $option['dependency']['relation'] ) {
								case 'or':
									$option['condition']['relation'] = 'and';
									break;
								case 'and':
									$option['condition']['relation'] = 'or';
									break;
							}
						}
					}

					if ( 1 < count( $dependency ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['conditions'] = $option['condition'];
					} else {
						$formatted_options[ $group ]['fields'][ $option_key ]['condition'] = $option['condition'];
					}
				}
			}

			/*** Repeater Options ***/
			if ( 'repeater' === $option['field_type'] ) {
				$formatted_options[ $group ]['fields'][ $option_key ]['title_field'] = esc_html__( 'Item', 'qi-addons-for-elementor' );
				foreach ( $option['items'] as $item_key => $item_value ) {
					$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['label']      = $item_value['title'];
					$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['field_type'] = $item_value['field_type'];

					$dynamic_allowed_types = array( 'image', 'text', 'textarea', 'link' );

					if ( in_array( $item_value['field_type'], $dynamic_allowed_types, true ) ) {
						if ( isset( $item_value['dynamic'] ) ) {
							$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['dynamic'] = $item_value['dynamic'];
						} else {
							$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['dynamic'] = array( 'active' => true );
						}
					}

					if ( isset( $item_value['default_value'] ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['default'] = $item_value['default_value'];
					}

					if ( isset( $item_value['multiple'] ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['multiple'] = $item_value['multiple'];
					}

					if ( isset( $item_value['alpha'] ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['alpha'] = $item_value['alpha'];
					}

					if ( isset( $item_value['options'] ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['options'] = $item_value['options'];
					}

					if ( isset( $item_value['description'] ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['description'] = $item_value['description'];
					}

					if ( isset( $item_value['size_units'] ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['size_units'] = $item_value['size_units'];
					}

					if ( isset( $item_value['range'] ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['range'] = $item_value['range'];
					}

					if ( isset( $item_value['allowed_dimensions'] ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['allowed_dimensions'] = $item_value['allowed_dimensions'];
					}

					if ( isset( $item_value['placeholder'] ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['placeholder'] = $item_value['placeholder'];
					}

					if ( isset( $item_value['selector'] ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['selector'] = $item_value['selector'];
					}

					if ( isset( $item_value['selectors'] ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['selectors'] = $item_value['selectors'];
					}
					if ( isset( $item_value['selectors_dictionary'] ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['selectors_dictionary'] = $item_value['selectors_dictionary'];
					}
					if ( isset( $item_value['responsive'] ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['responsive_enabled'] = $item_value['responsive'];
					}
					if ( isset( $item_value['exclude_options'] ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['exclude'] = $item_value['exclude_options'];
					}
					if ( isset( $item_value['devices'] ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['devices'] = $item_value['devices'];
					}
					if ( isset( $item_value['media_types'] ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['media_types'] = $item_value['media_types'];
					}
					if ( isset( $item_value['types'] ) ) {
						$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['types'] = $item_value['types'];
					}

					if ( isset( $item_value['dependency'] ) ) {
						if ( isset( $item_value['dependency']['show'] ) ) {
							$dependency     = $item_value['dependency']['show'];
							$dependency_key = key( $dependency );

							if ( '' === $dependency[ $dependency_key ]['values'] ) {
								$item_value['condition'] = array(
									$dependency_key => array( '' ),
								);
							} else {
								$item_value['condition'] = array(
									$dependency_key => $dependency[ $dependency_key ]['values'],
								);
							}

							$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['condition'] = $item_value['condition'];
						}

						if ( isset( $item_value['dependency']['hide'] ) ) {
							$dependency     = $item_value['dependency']['hide'];
							$dependency_key = key( $dependency );

							if ( '' === $dependency[ $dependency_key ]['values'] ) {
								$item_value['condition'] = array(
									$dependency_key . '!' => array( '' ),
								);
							} else {
								$item_value['condition'] = array(
									$dependency_key . '!' => $dependency[ $dependency_key ]['values'],
								);
							}

							$formatted_options[ $group ]['fields'][ $option_key ]['items'][ $item_value['name'] ]['condition'] = $item_value['condition'];
						}
					}
				}
			}
		}

		return $formatted_options;
	}

	function generate_default_value( $option ) {
		$default_value = array();

		switch ( $option['field_type'] ) {
			case 'image':
				if ( ! isset( $option['multiple'] ) || 'yes' !== $option['multiple'] ) {
					$default_value = get_option( 'qi_addons_for_elementor_placeholder_image' );
				}
				break;
			case 'icons':
				$default_value = array(
					'value'   => 'far fa-paper-plane',
					'library' => 'fa-regular',
				);
				break;
		}

		return $default_value;
	}

	function enqueue_scripts() {
		// Enqueue page builder global style
		wp_enqueue_style( 'qodef-qi-framework-elementor', QI_ADDONS_FOR_ELEMENTOR_ADMIN_URL_PATH . '/inc/shortcodes/translators/elementor/assets/css/elementor.css' );

		// Get shortcodes styles and register it during the front-end loading, scripts are enqueued on shortcodes loading
		$shortcodes = qi_addons_for_elementor_framework_get_framework_root()->get_shortcodes()->get_shortcodes();

		if ( ! empty( $shortcodes ) && is_array( $shortcodes ) ) {
			foreach ( $shortcodes as $key => $shortcode ) {
				$shortcode_styles = $shortcode->get_necessary_styles();

				if ( is_array( $shortcode_styles ) && count( $shortcode_styles ) > 0 ) {
					foreach ( $shortcode_styles as $style_key => $style ) {

						if ( ! $style['registered'] ) {
							wp_register_style( $style_key, $style['url'] );
						}
					}
				}
			}
		}
	}

	function add_inline_style() {
		$shortcodes = qi_addons_for_elementor_framework_get_framework_root()->get_shortcodes()->get_shortcodes();
		$style      = apply_filters( 'qi_addons_for_elementor_filter_framework_add_elementor_inline_style', $style = '' );

		if ( ! empty( $shortcodes ) && is_array( $shortcodes ) ) {
			ksort( $shortcodes );

			foreach ( $shortcodes as $key => $shortcode ) {
				$shortcode_path = $shortcode->get_shortcode_path();

				if ( isset( $shortcode_path ) && ! empty( $shortcode_path ) ) {
					$icon      = $shortcode->get_is_child_shortcode() ? 'dashboard_child_icon' : 'dashboard_icon';
					$icon_path = $shortcode_path . '/assets/img/' . esc_attr( $icon ) . '.png';

					$style .= '.qodef-custom-elementor-icon.' . str_replace( '_', '-', $key ) . '{
						background-image: url("' . $icon_path . '") !important;
					}';
				}
			}
		}

		if ( ! empty( $style ) ) {
			wp_add_inline_style( 'qodef-qi-framework-elementor', $style );
		}
	}

	function add_elementor_widget_category( $elements_manager ) {
		$elements_manager->add_category(
			'qi-addons',
			array(
				'title' => esc_html__( 'QI Addons', 'qi-addons-for-elementor' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}

	function format_params( $params, $object ) {
		$image_params = $object->get_options_key_by_type( 'image' );

		if ( is_array( $image_params ) && count( $image_params ) > 0 ) {
			foreach ( $image_params as $image_param ) {
				if ( ! empty( $params[ $image_param ] ) ) {
					$option = $object->get_option( $image_param );

					if ( isset( $option['multiple'] ) && 'yes' === $option['multiple'] ) {
						$gallery_array = array();

						foreach ( $params[ $image_param ] as $gallery_item_key => $gallery_item ) {
							$gallery_array[] = $gallery_item['id'];
						}

						$params[ $image_param ] = implode( ',', $gallery_array );
					} else {
						$params[ $image_param ] = $params[ $image_param ]['id'];
					}
				}
			}
		}

		$repeater_params = $object->get_options_key_by_type( 'repeater' );

		if ( is_array( $repeater_params ) && count( $repeater_params ) > 0 ) {
			foreach ( $repeater_params as $repeater_param ) {

				if ( ! empty( $params[ $repeater_param ] ) ) {
					$option = $object->get_option( $repeater_param );

					foreach ( $option['items'] as $item_key => $item ) {

						if ( 'image' == $item['field_type'] ) {

							if ( ! isset( $item['multiple'] ) || ( isset( $item['multiple'] ) && 'yes' !== $item['multiple'] ) ) {
								foreach ( $params[ $repeater_param ] as $repeater_item_key => $repeater_item ) {
									if ( isset( $params[ $repeater_param ][ $repeater_item_key ][ $item['name'] ]['id'] ) ) {
										$params[ $repeater_param ][ $repeater_item_key ][ $item['name'] ] = $params[ $repeater_param ][ $repeater_item_key ][ $item['name'] ]['id'];
									}
								}
							} else {
								foreach ( $params[ $repeater_param ] as $repeater_item_key => $repeater_item ) {
									$gallery_repeater_array = array();
									if ( isset( $params[ $repeater_param ][ $repeater_item_key ][ $item['name'] ] ) ) {
										foreach ( $params[ $repeater_param ][ $repeater_item_key ][ $item['name'] ] as $gallery_repeater_item_key => $gallery_repeater_item ) {
											$gallery_repeater_array[] = $gallery_repeater_item['id'];
										}
									}

									$params[ $repeater_param ][ $repeater_item_key ][ $item['name'] ] = implode( ',', $gallery_repeater_array );
								}
							}
						}
					}
				}

				$params[ $repeater_param ] = urlencode( json_encode( $params[ $repeater_param ] ) );
			}
		}

		if ( ! empty( $params['elements_of_child_widget'] ) ) {
			foreach ( $object->get_child_elements() as $child ) {
				$params['content'] = '';

				foreach ( $params['elements_of_child_widget'] as $child_elements ) {
					$params['content'] .= '[';
					$params['content'] .= $child;
					$params['content'] .= ' ';

					foreach ( $child_elements as $child_element_key => $child_element ) {
						if ( 'content' != $child_element_key ) {
							$params['content'] .= $child_element_key . '="' . $child_element . '" ';
						}
					}

					if ( isset( $child_elements['content'] ) ) {
						$params['content'] .= ']' . $child_elements['content'];
					}

					$params['content'] .= '[/';
					$params['content'] .= $child;
					$params['content'] .= ']';
				}
			}
		}

		return $params;
	}

	function convert_options_types_to_elementor_types( $option ) {
		$type = $option['field_type'];

		switch ( $type ) :
			case 'text':
				$elementor_type = \Elementor\Controls_Manager::TEXT;
				break;
			case 'link':
				$elementor_type = \Elementor\Controls_Manager::URL;
				break;
			case 'textarea':
			case 'textarea_html':
				$elementor_type = \Elementor\Controls_Manager::TEXTAREA;
				break;
			case 'html':
				$elementor_type = \Elementor\Controls_Manager::WYSIWYG;
				break;
			case 'code':
				$elementor_type = \Elementor\Controls_Manager::CODE;
				break;
			case 'select':
				$elementor_type = \Elementor\Controls_Manager::SELECT;
				break;
			case 'choose':
				$elementor_type = \Elementor\Controls_Manager::CHOOSE;
				break;
			case 'checkbox':
				$elementor_type = \Elementor\Controls_Manager::SWITCHER;
				break;
			case 'color':
				$elementor_type = \Elementor\Controls_Manager::COLOR;
				break;
			case 'hidden':
				$elementor_type = \Elementor\Controls_Manager::HIDDEN;
				break;
			case 'image':
				if ( isset( $option['multiple'] ) && 'yes' === $option['multiple'] ) {
					$elementor_type = \Elementor\Controls_Manager::GALLERY;
				} else {
					$elementor_type = \Elementor\Controls_Manager::MEDIA;
				}
				break;
			case 'date':
				$elementor_type = \Elementor\Controls_Manager::DATE_TIME;
				break;
			case 'icons':
				$elementor_type = \Elementor\Controls_Manager::ICONS;
				break;
			case 'slider':
				$elementor_type = \Elementor\Controls_Manager::SLIDER;
				break;
			case 'dimensions':
				$elementor_type = \Elementor\Controls_Manager::DIMENSIONS;
				break;
			case 'repeater':
				$elementor_type = \Elementor\Controls_Manager::REPEATER;
				break;
			case 'divider':
				$elementor_type = \Elementor\Controls_Manager::DIVIDER;
				break;
			case 'number':
				$elementor_type = \Elementor\Controls_Manager::NUMBER;
				break;
			case 'select2':
				$elementor_type = \Elementor\Controls_Manager::SELECT2;
				break;
			case 'typography':
				$elementor_type = \Elementor\Group_Control_Typography::get_type();
				break;
			case 'fonts':
				$elementor_type = \Elementor\Controls_Manager::FONT;
				break;
			case 'text_shadow':
				$elementor_type = \Elementor\Group_Control_Text_Shadow::get_type();
				break;
			case 'box_shadow':
				$elementor_type = \Elementor\Group_Control_Box_Shadow::get_type();
				break;
			case 'border':
				$elementor_type = \Elementor\Group_Control_Border::get_type();
				break;
			case 'background':
				$elementor_type = \Elementor\Group_Control_Background::get_type();
				break;
			case 'image_size':
				$elementor_type = \Elementor\Group_Control_Image_Size::get_type();
				break;
			case 'heading':
				$elementor_type = \Elementor\Controls_Manager::HEADING;
				break;
			case 'note':
				$elementor_type = \Elementor\Controls_Manager::RAW_HTML;
				break;
			default:
				$elementor_type = \Elementor\Controls_Manager::TEXT;
				break;
		endswitch;

		return $elementor_type;
	}

	function create_controls( $elementor_object, $shortcode_object ) {
		$controls = $this->generate_option_params( $shortcode_object );

		foreach ( $controls as $control_key => $control ) {
			$tab = \Elementor\Controls_Manager::TAB_CONTENT;

			// If options group contain Style word put that options inside Elementor Style tab
			if ( strpos( $control_key, 'style' ) !== false ) {
				$tab = \Elementor\Controls_Manager::TAB_STYLE;
			}

			$elementor_object->start_controls_section(
				$control_key,
				array(
					'label' => ucwords( str_replace( array( '-elementor', '-' ), array( '', ' ' ), $control_key ) ),
					'tab'   => $tab,
				)
			);

			foreach ( $control['fields'] as $field_key => $field ) {
				if ( isset( $field['field_type'] ) && 'repeater' === $field['field_type'] ) {
					$repeater = new \Elementor\Repeater();

					foreach ( $field['items'] as $item_key => $item ) {
						$item['type'] = $this->convert_options_types_to_elementor_types( $item );

						if ( isset( $item['field_type'] ) && in_array( $item['field_type'], qi_addons_for_elementor_framework_elementor_get_group_types(), true ) ) {
							$repeater->add_group_control(
								$item['type'],
								array_merge(
									array(
										'name' => $item_key,
									),
									$item
								)
							);
						} elseif ( isset( $item['responsive_enabled'] ) && true === $item['responsive_enabled'] ) {
							$repeater->add_responsive_control(
								$item_key,
								$item
							);
						} else {
							$repeater->add_control(
								$item_key,
								$item
							);
						}
					}

					$field['fields'] = $repeater->get_controls();
					unset( $field['items'] );
				}

				if ( isset( $field['field_type'] ) && in_array( $field['field_type'], qi_addons_for_elementor_framework_elementor_get_tab_controls_types(), true ) ) {
					$tabs_settings = array();

					if ( isset( $field['label'] ) && ! empty( $field['label'] ) ) {
						$tabs_settings['label'] = $field['label'];
					}
					
					if ( isset( $field['condition'] ) && ! empty( $field['condition'] ) ) {
						$tabs_settings['condition'] = $field['condition'];
					}
					
					$function = $field['field_type'];
					$elementor_object->$function(
						$field_key,
						$tabs_settings
					);
				} else {

					$field['type'] = $this->convert_options_types_to_elementor_types( $field );

					if ( isset( $field['field_type'] ) && in_array( $field['field_type'], qi_addons_for_elementor_framework_elementor_get_group_types(), true ) ) {
						$elementor_object->add_group_control(
							$field['type'],
							array_merge(
								array(
									'name' => $field_key,
								),
								$field
							)
						);
					} elseif ( isset( $field['responsive_enabled'] ) && true === $field['responsive_enabled'] ) {
						$elementor_object->add_responsive_control(
							$field_key,
							$field
						);
					} else {
						$elementor_object->add_control(
							$field_key,
							$field
						);
					}
				}
			}

			$elementor_object->end_controls_section();
		}

		// Add predefined developer tab content for each shortcode element
		$elementor_object->start_controls_section(
			'developer_tools',
			array(
				'label' => esc_html__( 'Developer Tools', 'qi-addons-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$elementor_object->add_control(
			'shortcode_snippet',
			array(
				'label'   => esc_html__( 'Show Shortcode Snippet', 'qi-addons-for-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'no',
				'options' => array(
					'no'  => esc_html__( 'No', 'qi-addons-for-elementor' ),
					'yes' => esc_html__( 'Yes', 'qi-addons-for-elementor' ),
				),
			)
		);

		$elementor_object->end_controls_section();

		$theme_style = Elementor\Core\Settings\Manager::get_settings_managers( 'editorPreferences' )->get_model()->get_settings( 'ui_theme' );

		// Add predefined developer tab content for each shortcode element
		$elementor_object->start_controls_section(
			'widget_help_documentation_section',
			array(
				'label' => esc_html__( 'Help', 'qi-addons-for-elementor' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);
		if ( '' !== $shortcode_object->get_demo() ) {
			$elementor_object->add_control(
				'widget_help_showcase',
				array(
					'type'            => \Elementor\Controls_Manager::RAW_HTML,
					/* translators: %s admin link */
					'raw'             => sprintf( esc_html__( '%1$s Widget Showcase %2$s', 'qi-addons-for-elementor' ), '<a href="' . $shortcode_object->get_demo() . '" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'qodef-elementor-admin-widget-help qodef-elementor-admin-style-' . $theme_style,
				)
			);
		}

		$elementor_object->add_control(
			'widget_help_demos',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				/* translators: %s admin link */
				'raw'             => sprintf( esc_html__( '%1$s Live Demos %2$s', 'qi-addons-for-elementor' ), '<a href="https://qodeinteractive.com/qi-theme/#demos" target="_blank" rel="noopener">', '</a>' ),
				'content_classes' => 'qodef-elementor-admin-widget-help qodef-elementor-admin-style-' . $theme_style,
			)
		);

		if ( '' !== $shortcode_object->get_documentation() ) {
			$elementor_object->add_control(
				'widget_help_documentation',
				array(
					'type'            => \Elementor\Controls_Manager::RAW_HTML,
					/* translators: %s admin link */
					'raw'             => sprintf( esc_html__( '%1$s Documentation %2$s', 'qi-addons-for-elementor' ), '<a href="' . $shortcode_object->get_documentation() . '" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'qodef-elementor-admin-widget-help qodef-elementor-admin-style-' . $theme_style,
				)
			);
		}

		if ( '' !== $shortcode_object->get_video() ) {
			$elementor_object->add_control(
				'widget_help_video',
				array(
					'type'            => \Elementor\Controls_Manager::RAW_HTML,
					/* translators: %s admin link */
					'raw'             => sprintf( esc_html__( '%1$s Video Tutorial %2$s', 'qi-addons-for-elementor' ), '<a href="' . $shortcode_object->get_video() . '" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'qodef-elementor-admin-widget-help qodef-elementor-admin-style-' . $theme_style,
				)
			);
		}

		$elementor_object->add_control(
			'widget_help_help_center',
			array(
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				/* translators: %s admin link */
				'raw'             => sprintf( esc_html__( '%1$s Help Center %2$s', 'qi-addons-for-elementor' ), '<a href="https://helpcenter.qodeinteractive.com/" target="_blank" rel="noopener">', '</a>' ),
				'content_classes' => 'qodef-elementor-admin-widget-help qodef-elementor-admin-style-' . $theme_style,
			)
		);

		$elementor_object->end_controls_section();

		$premium_active = apply_filters( 'qi_addons_for_elementor_filter_elementor_promotion_tab', false );

		if ( false === $premium_active ) {
			// Add promotion widgets box
			$elementor_object->start_controls_section(
				'widget_promotion_section',
				array(
					'label' => esc_html__( 'Get More Features', 'qi-addons-for-elementor' ),
					'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
				)
			);

			$elementor_object->add_control(
				'widget_promotion',
				array(
					'type'            => \Elementor\Controls_Manager::RAW_HTML,
					/* translators: %s admin link */
					'raw'             => sprintf( esc_html__( ' %1$sQi Addons for Elementor Premium %2$s is now available with another 40+ advanced widgets.', 'qi-addons-for-elementor' ), '<a href="https://qodeinteractive.com/qi-addons-for-elementor/?utm_source=getmore&utm_medium=qi-addons&utm_campaign=gopremium" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'qodef-elementor-admin-widget-promotion qodef-elementor-admin-style-' . $theme_style,
				)
			);
			
			$elementor_object->end_controls_section();
		}
	}

	private function get_shortcode_snippet( $shortcode_object, $params ) {
		$atts = array();

		if ( empty( $shortcode_object ) || ! is_object( $shortcode_object ) ) {
			return '';
		}

		if ( ! empty( $params ) ) {
			foreach ( $params as $key => $value ) {
				if ( is_array( $value ) || 'shortcode_snippet' === $key ) {
					continue;
				}

				$atts[] = $key . '="' . esc_attr( $value ) . '"';
			}
		}

		return sprintf(
			'<textarea rows="3" readonly>[%s %s]</textarea>',
			$shortcode_object->get_base(),
			implode( ' ', $atts )
		);
	}

	function create_render( $shortcode_object, $params ) {
		$params = $this->format_params( $params, $shortcode_object );

		if ( isset( $params['shortcode_snippet'] ) && 'yes' === $params['shortcode_snippet'] ) {
			echo $this->get_shortcode_snippet( $shortcode_object, array_filter( $params ) );
		} elseif ( isset( $params['content'] ) ) { // Handle nested shortcodes
			echo $shortcode_object->render( $params, $params['content'] );
		} else {
			echo $shortcode_object->render( $params );
		}
	}

	function set_scripts( $shortcode ) {
		$shortcode_deps = array();

		if ( is_array( $shortcode->get_scripts() ) && count( $shortcode->get_scripts() ) > 0 ) {
			foreach ( $shortcode->get_scripts() as $handle_key => $handle ) {
				$shortcode_deps[] = $handle_key;
			}
		}

		return $shortcode_deps;
	}

	function set_necessary_styles( $shortcode ) {
		$shortcode_deps = array();

		if ( is_array( $shortcode->get_necessary_styles() ) && count( $shortcode->get_necessary_styles() ) > 0 ) {
			foreach ( $shortcode->get_necessary_styles() as $handle_key => $handle ) {
				$shortcode_deps[] = $handle_key;
			}
		}

		return $shortcode_deps;
	}

	function get_elementor_config( $config ) {

		$widgets = qi_addons_for_elementor_promotion_shortcodes_list();

		if ( ! empty( $widgets ) ) {
			foreach ( $widgets as $widget_key => $widget_value ) {

				$widget_help_url = $widget_value['demo'];

				$widget_help_url_params = array(
					'utm_source'   => strtolower( str_replace( ' ', '-', $widget_value['title'] ) ) . '-widget',
					'utm_medium'   => 'qi-addons',
					'utm_campaign' => 'gopremium',
				);

				$widget_help_url = add_query_arg( $widget_help_url_params, $widget_help_url );

				$config['promotionWidgets'][] = array(
					'name'       => $widget_key,
					'title'      => $widget_value['title'],
					'icon'       => $widget_value['icon'],
					'categories' => '["qi-addons"]',
					'helpUrl'    => $widget_help_url,
				);

			}
		}

		return $config;

	}

}

if ( ! function_exists( 'qi_addons_for_elementor_framework_get_elementor_translator' ) ) {
	/**
	 * Function that return page builder module instance
	 */
	function qi_addons_for_elementor_framework_get_elementor_translator() {
		if ( qi_addons_for_elementor_framework_is_installed( 'elementor' ) ) {
			return QiAddonsForElementor_Framework_Elementor_Translator::get_instance();
		}
	}
}
qi_addons_for_elementor_framework_get_elementor_translator();

if ( ! function_exists( 'qi_addons_for_elementor_framework_elementor_get_group_types' ) ) {
	function qi_addons_for_elementor_framework_elementor_get_group_types() {
		$group_types = array(
			'typography',
			'text_shadow',
			'box_shadow',
			'border',
			'background',
			'image_size',
		);

		return $group_types;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_elementor_get_tab_controls_types' ) ) {
	function qi_addons_for_elementor_framework_elementor_get_tab_controls_types() {
		$tabs_types = array(
			'start_controls_tabs',
			'start_controls_tab',
			'end_controls_tab',
			'end_controls_tabs',
		);

		return $tabs_types;
	}
}
