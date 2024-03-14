<?php
/**
 * Custom blocks registration.
 *
 * @package Canvas
 */

if ( ! class_exists( 'CNVS_Gutenberg_Blocks_Registration' ) ) {
	class CNVS_Gutenberg_Blocks_Registration {
		/**
		 * Contains all custom blocks.
		 *
		 * @var array
		 */
		public $all_blocks = array();

		function __construct() {
			add_action( 'init', array( $this, 'register_block_type' ), 12 );
			add_action( 'canvas_blocks_dynamic_css', array( $this, 'blocks_dynamic_css' ), 10, 2 );

			add_filter( 'canvas_block_convert_fields_to_attributes', array( $this, 'convert_fields_to_attributes' ) );
			add_filter( 'canvas_block_prepare_server_render_attributes', array( $this, 'prepare_server_render_attributes' ), 10, 2 );
		}

		/**
		 * Get default settings.
		 *
		 * @return array
		 */
		public function get_custom_blocks_default_settings() {
			return apply_filters(
				'canvas_register_block_type_default_settings', array(
					'name'          => '',
					'title'         => '',
					'description'   => '',
					'category'      => '',
					'keywords'      => array(),
					'icon'          => '',
					'supports'      => array(
						'className' => true,
						'anchor'    => true,
					),
					'styles'        => array(),
					'location'      => array(),
					'layouts'       => array(),
					'sections'      => array(),
					'fields'        => array(),
					'template'      => '',
					'style'         => '',
					'script'        => '',
					'editor_style'  => '',
					'editor_script' => '',
				)
			);
		}

		/**
		 * Get custom blocks array.
		 *
		 * @return array
		 */
		public function get_custom_blocks() {
			if ( ! empty( $this->all_blocks ) ) {
				return $this->all_blocks;
			}

			$blocks           = apply_filters( 'canvas_register_block_type', array() );
			$default_settings = $this->get_custom_blocks_default_settings();

			// add default settings to all blocks.
			foreach ( $blocks as $k => $block ) {
				$block['sections'] = apply_filters( 'canvas_block_sections_' . $block['name'], $block['sections'] );
				$block['fields']   = apply_filters( 'canvas_block_fields_' . $block['name'], $block['fields'] );
				$block['layouts']  = apply_filters( 'canvas_block_layouts_' . $block['name'], $block['layouts'] );

				// prepare layouts fields.
				if ( isset( $block['layouts'] ) && ! empty( $block['layouts'] ) ) {
					$layouts = $this->get_layouts_fields( $block['name'], $block['layouts'] );

					// hide default fields if layout selected.
					foreach ( $block['layouts'] as $layout => $data ) {
						if ( ! isset( $data['hide_fields'] ) ) {
							continue;
						}

						// check all default fields.
						foreach ( $block['fields'] as $f => $field ) {
							if ( ! isset( $field['key'] ) ) {
								continue;
							}

							if ( ! in_array( $field['key'], $data['hide_fields'] ) ) {
								continue;
							}

							if ( ! isset( $block['fields'][ $f ]['active_callback'] ) ) {
								$block['fields'][ $f ]['active_callback'] = array();
							}

							// hide default field.
							$block['fields'][ $f ]['active_callback'][] = array(
								'field'    => 'layout',
								'operator' => '!==',
								'value'    => $layout,
							);
						}
					}

					// Set sections.
					$block['sections'] = array_merge(
						$block['sections'],
						$layouts['sections']
					);

					// Multisort sections.
					if ( is_array( $block['sections'] ) && $block['sections'] ) {
						foreach ( $block['sections'] as $key => $value ) {
							if ( is_string( $value ) ) {
								$block['sections'][ $key ] = array(
									'title'    => $value,
									'priority' => 10,
								);
							}
						}

						$priority = array_column( $block['sections'], 'priority' );

						array_multisort( $priority, SORT_ASC, $block['sections'] );
					}

					// Set fields.
					$block['fields'] = array_merge(
						$block['fields'],
						$layouts['fields']
					);
				}

				$blocks[ $k ] = array_merge(
					$default_settings,
					$block
				);
			}

			$this->all_blocks = $blocks;

			return $this->all_blocks;
		}

		/**
		 * Get custom block data.
		 *
		 * @param string $name block name.
		 * @return array
		 */
		public function get_custom_block( $name ) {
			$blocks = $this->get_custom_blocks();
			$result = false;

			foreach ( $blocks as $block ) {
				if ( $name === $block['name'] ) {
					$result = $block;
				}
			}

			return $result;
		}

		/**
		 * Parse layouts and prepare fields for block.
		 *
		 * For example, if we have layouts with name 'grid' and fields inside with the name 'columns'
		 * will be added field with the name 'layout_grid_columns'
		 *
		 * @param string $block_name block name.
		 * @param array  $layouts block layouts.
		 * @return array fields and sections used in the block.
		 */
		public function get_layouts_fields( $block_name, $layouts ) {
			$block_sections = array();
			$block_fields   = array();

			// look at all layouts.
			foreach ( $layouts as $layout => $data ) {
				$layout_prefix = 'layout_' . $layout . '_';

				// add new sections.
				if ( isset( $data['sections'] ) && ! empty( $data['sections'] ) ) {
					$block_sections = array_merge( $block_sections, $data['sections'] );
				}

				// look at all layout fields.
				if ( isset( $data['fields'] ) && is_array( $data['fields'] ) ) {
					foreach ( $data['fields'] as $field ) {
						if ( ! isset( $field['key'] ) ) {
							continue;
						}

						// convert field names.
						$field['key'] = $layout_prefix . $field['key'];

						// process recursive.
						if ( isset( $field['active_callback'] ) ) {
							$field['active_callback'] = $this->process_recursive( $field['active_callback'], $layout_prefix );
						}

						// show fields only for selected layout.
						$field['active_callback'][] = array(
							'field'    => 'layout',
							'operator' => '===',
							'value'    => $layout,
						);

						$block_fields[] = $field;
					}
				}
			}

			$block_fields   = apply_filters( 'canvas_block_layouts_fields_' . $block_name, $block_fields );
			$block_sections = apply_filters( 'canvas_block_layouts_sections_' . $block_name, $block_sections );

			return array(
				'sections' => $block_sections,
				'fields'   => $block_fields,
			);
		}


		/**
		 * Add prefixes to field names in active_callback array.
		 *
		 * @param array  $fields fields array from 'active_callback'.
		 * @param string $prefix prefix.
		 * @return array
		 */
		public function process_recursive( $fields, $prefix ) {
			$changed_fields = array();

			foreach ( $fields as $key => $item ) {
				if ( isset( $item['field'] ) ) {
					// Add prefix to new item.
					$item['field'] = str_replace( '$#', $prefix, $item['field'] );

					$changed_fields[] = $item;

				} elseif ( is_array( $item ) ) {

					$changed_fields[] = $this->process_recursive( $item, $prefix );
				}
			}

			return $changed_fields;
		}

		/**
		 * Register custom blocks.
		 */
		public function register_block_type() {
			if ( ! function_exists( 'register_block_type' ) ) {
				return;
			}

			$blocks = $this->get_custom_blocks();

			$schemes     = cnvs_gutenberg()->get_schemes_data();
			$breakpoints = cnvs_gutenberg()->get_breakpoints_data();

			if ( empty( $blocks ) ) {
				return;
			}

			$editor_script_deps = array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-editor' );

			// custom editor scripts.
			foreach ( $blocks as $block ) {
				if ( isset( $block['editor_script'] ) && $block['editor_script'] ) {
					$editor_script_deps[] = $block['editor_script'];
				}
			}

			// Script.
			wp_register_script(
				'canvas-custom-blocks-editor-script',
				CNVS_URL . 'gutenberg/custom-blocks/index.js',
				$editor_script_deps,
				filemtime( CNVS_PATH . 'gutenberg/custom-blocks/index.js' )
			);

			// Add additional data to scripts.
			wp_localize_script(
				'canvas-custom-blocks-editor-script', 'pk_custom_blocks_localize', array(
					'blocks' => $blocks,
				)
			);

			// Register all blocks.
			foreach ( $blocks as $block ) {
				// Default attributes, that will be used in render callback.
				$attributes = array(
					'canvasBlockName' => array(
						'type'    => 'string',
						'default' => $block['name'],
					),
					'canvasClassName' => array(
						'type' => 'string',
					),
					'canvasLocation'  => array(
						'type' => 'string',
					),
				);

				// Add support block widget.
				$attributes['__internalWidgetId'] = array(
					'type' => 'string',
				);

				// Supports.
				if ( isset( $block['supports'] ) && ! empty( $block['supports'] ) ) {

					foreach ( $block['supports'] as $support => $is_true ) {
						if ( $is_true ) {
							switch ( $support ) {
								case 'className':
									$attributes['className'] = array(
										'type' => 'string',
									);
									break;
								case 'anchor':
									$attributes['anchor'] = array(
										'type' => 'string',
									);
									break;
								case 'align':
									$attributes['align'] = array(
										'type' => 'string',
									);
								case 'canvasBackgroundImage':
									$background_attrs = array();

									foreach ( $breakpoints as $name => $breakpoint ) {
										$suffix = '';

										if ( $name && 'desktop' !== $name ) {
											$suffix = '_' . $name;
										}

										// background-image.
										$background_attrs[ 'backgroundImage' . $suffix ] = array(
											'type' => 'object',
										);

										// background-position.
										$background_attrs[ 'backgroundPosition' . $suffix ] = array(
											'type' => 'string',
										);

										// background-position-unit.
										$background_attrs[ 'backgroundPositionXUnit' . $suffix ] = array(
											'type' => 'string',
										);

										// background-position-number.
										$background_attrs[ 'backgroundPositionXVal' . $suffix ] = array(
											'type' => 'number',
										);

										// background-position-unit.
										$background_attrs[ 'backgroundPositionYUnit' . $suffix ] = array(
											'type' => 'string',
										);

										// background-position-number.
										$background_attrs[ 'backgroundPositionYVal' . $suffix ] = array(
											'type' => 'number',
										);

										// background-attachment.
										$background_attrs[ 'backgroundAttachment' . $suffix ] = array(
											'type' => 'string',
										);

										// background-repeat.
										$background_attrs[ 'backgroundRepeat' . $suffix ] = array(
											'type' => 'string',
										);

										// background-size.
										$background_attrs[ 'backgroundSize' . $suffix ] = array(
											'type' => 'string',
										);

										// background-size-unit.
										$background_attrs[ 'backgroundSizeUnit' . $suffix ] = array(
											'type' => 'string',
										);

										// background-size-number.
										$background_attrs[ 'backgroundSizeVal' . $suffix ] = array(
											'type' => 'number',
										);
									}

									$attributes = array_merge(
										$attributes,
										$background_attrs
									);
									break;
								case 'canvasSpacings':
									$all_spacings = array(
										'marginTop'     => 'margin-top',
										'marginBottom'  => 'margin-bottom',
										'marginLeft'    => 'margin-left',
										'marginRight'   => 'margin-right',
										'paddingTop'    => 'padding-top',
										'paddingBottom' => 'padding-bottom',
										'paddingLeft'   => 'padding-left',
										'paddingRight'  => 'padding-right',
									);

									$spacing_attrs = array();

									foreach ( $breakpoints as $name => $breakpoint ) {
										$suffix = '';

										if ( $name && 'desktop' !== $name ) {
											$suffix = '_' . $name;
										}

										// spacings.
										foreach ( $all_spacings as $spacing_name => $spacing_val ) {
											$spacing_attrs[ $spacing_name . $suffix ] = array(
												'type' => 'number',
											);
										}

										// link.
										$spacing_attrs[ 'marginLink' . $suffix ] = array(
											'type' => 'boolean',
										);
										$spacing_attrs[ 'paddingLink' . $suffix ] = array(
											'type' => 'boolean',
										);

										// unit.
										$spacing_attrs[ 'marginUnit' . $suffix ] = array(
											'type' => 'string',
										);
										$spacing_attrs[ 'paddingUnit' . $suffix ] = array(
											'type' => 'string',
										);
									}

									$attributes = array_merge(
										$attributes,
										$spacing_attrs
									);
									break;
								case 'canvasBorder':
									$all_borders = array(
										'borderWidthTop'    => 'border-top-width',
										'borderWidthBottom' => 'border-bottom-width',
										'borderWidthLeft'   => 'border-left-width',
										'borderWidthRight'  => 'border-right-width',
									);

									$all_borders_radius = array(
										'borderRadiusTopLeft'     => 'border-top-left-radius',
										'borderRadiusTopRight'    => 'border-top-right-radius',
										'borderRadiusBottomLeft'  => 'border-bottom-left-radius',
										'borderRadiusBottomRight' => 'border-bottom-right-radius',
									);

									$border_attrs = array();

									foreach ( $schemes as $name => $scheme ) {
										$suffix = '';

										if ( $name && 'default' !== $name ) {
											$suffix = '_' . $name;
										}

										// border color.
										$border_attrs[ 'borderColor' . $suffix ] = array(
											'type' => 'string',
										);
									}

									foreach ( $breakpoints as $name => $breakpoint ) {
										$suffix = '';

										if ( $name && 'desktop' !== $name ) {
											$suffix = '_' . $name;
										}

										// borders.
										foreach ( $all_borders as $border_name => $border_val ) {
											$border_attrs[ $border_name . $suffix ] = array(
												'type' => 'number',
											);
										}

										// link.
										$border_attrs[ 'borderWidthLink' . $suffix ] = array(
											'type' => 'boolean',
										);

										// unit.
										$border_attrs[ 'borderWidthUnit' . $suffix ] = array(
											'type' => 'string',
										);

										// borders radius.
										foreach ( $all_borders_radius as $border_name => $border_val ) {
											$border_attrs[ $border_name . $suffix ] = array(
												'type' => 'number',
											);
										}

										// link.
										$border_attrs[ 'borderRadiusLink' . $suffix ] = array(
											'type' => 'boolean',
										);

										// unit.
										$border_attrs[ 'borderRadiusUnit' . $suffix ] = array(
											'type' => 'string',
										);
									}

									$attributes = array_merge(
										$attributes,
										array(
											'borderStyle' => array(
												'type' => 'string',
											),
										),
										$border_attrs
									);
									break;
								case 'canvasResponsive':
									foreach ( $breakpoints as $name => $breakpoint ) {
										$attributes[ 'canvasResponsiveHide_' . $name ] = array(
											'type' => 'boolean',
										);
									}
									break;
								case 'canvasUniqueClass':
									$attributes['canvasClassName'] = array(
										'type' => 'string',
									);
									break;
							}
						}
					}
				}

				// Layouts.
				if ( isset( $block['layouts'] ) && ! empty( $block['layouts'] ) ) {
					$attributes['layout'] = array(
						'type'    => 'string',
						'default' => '',
					);

					// set default value if only 1 layout present.
					if ( 1 === count( $block['layouts'] ) ) {
						$attributes['layout']['default'] = array_keys( $block['layouts'] )[0];
					}
				}

				// Convert fields to attributes.
				if ( isset( $block['fields'] ) ) {
					$attributes = array_merge(
						$attributes,
						apply_filters( 'canvas_block_convert_fields_to_attributes', $block['fields'] )
					);
				}

				$block_data = array(
					'attributes'      => $attributes,
					'editor_script'   => 'canvas-custom-blocks-editor-script',
					'render_callback' => array( $this, 'render_custom_block' ),
				);

				// assets.
				if ( isset( $block['style'] ) && $block['style'] ) {
					$block_data['style'] = $block['style'];
				}
				if ( isset( $block['script'] ) && $block['script'] ) {
					$block_data['script'] = $block['script'];
				}
				if ( isset( $block['editor_style'] ) && $block['editor_style'] ) {
					$block_data['editor_style'] = $block['editor_style'];
				}

				// Editor script added as dependency in `canvas-custom-blocks-editor-script`.
				register_block_type( $block['name'], $block_data );
			}
		}

		/**
		 * Convert custom block fields to gutenberg block attributes.
		 *
		 * @param array $fields block fields.
		 * @return array attributes.
		 */
		public function convert_fields_to_attributes( $fields ) {
			$schemes         = cnvs_gutenberg()->get_schemes_data();
			$breakpoints     = cnvs_gutenberg()->get_breakpoints_data();
			$all_breakpoints = cnvs_gutenberg()->get_all_breakpoints_data();

			$attributes = array();

			foreach ( $fields as $field ) {
				$field_data = array(
					'type' => 'string',
				);

				// defaults.
				if ( isset( $field['default'] ) ) {
					$field_data['default'] = $field['default'];
				}

				// custom type.
				if ( isset( $field['type'] ) ) {
					switch ( $field['type'] ) {
						case 'toggle':
						case 'type-boolean':
							$field_data['type'] = 'boolean';
							break;
						case 'number':
						case 'type-number':
							$field_data['type'] = 'number';
							break;
						case 'image':
							$field_data['type']    = 'object';
							$field_data['default'] = array(
								'url' => '',
								'id'  => 0,
							);
							break;
						case 'gallery':
							$field_data['type'] = 'array';
							if ( isset( $field['items'] ) ) {
								$field_data['items'] = $field['items'];
							}
							break;
						case 'select':
						case 'react-select':
							if ( ! isset( $field['multiple'] ) || ! $field['multiple'] ) {
								break;
							}
						case 'type-array':
							$field_data['type'] = 'array';
							if ( isset( $field['items'] ) ) {
								$field_data['items'] = $field['items'];
							}
							break;
						case 'toggle-list':
						case 'query':
							$field_data['type'] = 'object';
							break;
					}
				}

				// General attributes.
				if ( isset( $field['key'] ) ) {
					$attributes[ $field['key'] ] = $field_data;

					// Schemes attributes.
					if ( $schemes && ( 'color' === $field['type'] ) ) {
						foreach ( $schemes as $name => $scheme ) {
							if ( $name && 'default' !== $name ) {
								$scheme_field_data = $field_data;

								if ( isset( $field[ 'default_' . $name ] ) ) {
									$scheme_field_data['default'] = $field[ 'default_' . $name ];
								} elseif ( isset( $repsonsive_field_data['default'] ) ) {
									unset( $scheme_field_data['default'] );
								}

								$attributes[ $field['key'] . '_' . $name ] = $scheme_field_data;
							}
						}
					}

					// Responsive attributes.
					if ( isset( $field['responsive'] ) && $field['responsive'] ) {
						foreach ( $all_breakpoints as $name => $breakpoint ) {
							if ( $name && 'desktop' !== $name ) {
								$repsonsive_field_data = $field_data;

								if ( isset( $field['default_' . $name] ) ) {
									$repsonsive_field_data['default'] = $field['default_' . $name];
								} else if ( isset( $repsonsive_field_data['default'] ) ) {
									unset( $repsonsive_field_data['default'] );
								}

								$attributes[ $field['key'] . '_' . $name ] = $repsonsive_field_data;
							}
						}
					}
				}
			}

			return $attributes;
		}

		/**
		 * Prepare server render attributes.
		 * For example, we need to remove field attributes, that hidden because of `active_callback`.
		 *
		 * @param array $attributes Values of attributes.
		 * @param array $data Fields data.
		 * @return array
		 */
		public function prepare_server_render_attributes( $attributes, $data ) {
			if ( isset( $data['fields'] ) ) {
				// remove attributes depending on `active_callback`.
				foreach ( $data['fields'] as $field ) {
					if (
						isset( $field['active_callback'] ) &&
						isset( $attributes[ $field['key'] ] ) &&
						! CNVS_Gutenberg_Utils_Is_Field_Visible::check( $field, $attributes, $data['fields'], false )
					) {
						unset( $attributes[ $field['key'] ] );
					}
				}
			}

			return $attributes;
		}

		/**
		 * Get block template file path
		 *
		 * @param array $block      Block data.
		 * @param array $attributes Block attributes.
		 */
		public function get_block_template( $block, $attributes ) {
			$result = '';

			// find block template.
			if ( isset( $block['template'] ) && file_exists( $block['template'] ) ) {
				$result = $block['template'];
			}

			// find current layout template.
			if ( ! empty( $attributes['layout'] ) && isset( $block['layouts'][ $attributes['layout'] ] ) ) {
				$layout = $block['layouts'][ $attributes['layout'] ];

				if (
					isset( $layout['template'] ) &&
					file_exists( $layout['template'] )
				) {
					$result = $layout['template'];

					// Include fallback.
				} elseif ( isset( $layout['fallback'] ) ) {
					$find_fallback = $this->get_block_template(
						$block, array_merge(
							$attributes,
							array(
								'layout' => isset( $layout['fallback']['layout'] ) ? $layout['fallback']['layout'] : 'undefined',
							),
							isset( $layout['fallback']['fields'] ) ? $layout['fallback']['fields'] : array()
						)
					);

					if ( $find_fallback ) {
						$result = $find_fallback;
					}
				}
			}

			return $result;
		}

		/**
		 * Include file and pass variables array.
		 *
		 * @param string $template file path.
		 * @param array  $template_variables array of variables.
		 */
		public function include_file( $template, $template_variables ) {
			if ( file_exists( $template ) ) {
				extract( $template_variables );
				include $template;
			}
		}

		/**
		 * Render custom block callback.
		 *
		 * @param array $attributes block attributes.
		 * @param array $content block inner content.
		 * @return string block output.
		 */
		public function render_custom_block( $attributes, $content ) {
			if ( ! isset( $attributes['canvasBlockName'] ) ) {
				return '';
			}

			$block_name = $attributes['canvasBlockName'];

			$block = $this->get_custom_block( $block_name );

			if ( empty( $block ) ) {
				return '';
			}

			$template = $this->get_block_template( $block, $attributes );

			if ( ! $template || ! file_exists( $template ) ) {
				return esc_html__( 'Block template is not available :(', 'canvas' );
			}

			// Generated HTML classes for blocks follow the `cnvs-block-{name}` nomenclature.
			// Blocks provided by Canvas drop the prefixes 'canvas/'.
			$block_class_name_prefix = $block_name;
			$block_class_name_prefix = preg_replace( '/\//', '-', $block_class_name_prefix );
			$block_class_name_prefix = preg_replace( '/^canvas-/', '', $block_class_name_prefix );
			$block_class_name_prefix = 'cnvs-block-' . $block_class_name_prefix;

			$class_name = $block_class_name_prefix;

			// add classnames.
			if ( isset( $attributes['canvasClassName'] ) && $attributes['canvasClassName'] ) {
				$class_name .= ' ' . $attributes['canvasClassName'];
			}

			if ( isset( $attributes['layout'] ) ) {
				$class_name .= ' ' . $block_class_name_prefix . '-layout-' . $attributes['layout'];
			}

			if ( isset( $attributes['className'] ) && $attributes['className'] ) {
				$class_name .= ' ' . $attributes['className'];
			}

			if ( isset( $attributes['align'] ) && $attributes['align'] ) {
				$class_name .= ' align' . $attributes['align'];
			}

			$attributes['className'] = trim( $class_name );

			// Filter to prepare some attributes.
			$attributes = apply_filters( 'canvas_block_prepare_server_render_attributes', $attributes, $block );

			// Current layout fields options.
			$options = array();

			if ( isset( $attributes['layout'] ) ) {
				foreach ( $attributes as $name => $val ) {
					$layout_prefix = 'layout_' . $attributes['layout'] . '_';

					if ( strpos( $name, $layout_prefix ) === 0 ) {
						$options[ str_replace( $layout_prefix, '', $name ) ] = $val;
					}
				}
			}

			ob_start();

			$template_variables = apply_filters(
				'canvas_block_template_variables_' . $block_name, array(
					'attributes' => $attributes,
					'options'    => $options,
					'content'    => $content,
				), $block
			);

			$this->include_file( $template, $template_variables );

			do_action( 'canvas_block_server_rendered_template_' . $block_name, $attributes );

			$output = ob_get_clean();

			return apply_filters( 'canvas_block_server_rendered_template_output', $output, $attributes, $options );
		}

		/**
		 * Add fields styles.
		 *
		 * @param string $block Block data.
		 * @param string $fields All available block fields.
		 * @return string
		 */
		public function get_custom_block_fields_output_styles( $block, $fields ) {
			$return = '';

			if ( isset( $block['attrs']['canvasClassName'] ) ) {
				$canvas_classname = '.' . $block['attrs']['canvasClassName'];

				$return = CNVS_Gutenberg_Fields_CSS_Output::get(
					$canvas_classname,
					$fields,
					$block['attrs']
				);
			}

			return $return;
		}

		/**
		 * Output custom styles for all `output` fields of custom blocks.
		 *
		 * @param string $styles The styles.
		 * @param array  $blocks The blocks.
		 */
		public function blocks_dynamic_css( $styles = '', $blocks = array() ) {
			// parse custom blocks and add fields output styles.
			$custom_blocks = $this->get_custom_blocks();

			if ( ! empty( $custom_blocks ) && ! empty( $blocks ) ) {
				foreach ( $blocks as $block ) {
					foreach ( $custom_blocks as $custom_block ) {
						if ( $custom_block['name'] === $block['blockName'] ) {
							$styles .= $this->get_custom_block_fields_output_styles(
								$block,
								isset( $custom_block['fields'] ) ? $custom_block['fields'] : array()
							);
							break;
						}
					}
				}
			}

			return $styles;
		}
	}
	new CNVS_Gutenberg_Blocks_Registration();
}
