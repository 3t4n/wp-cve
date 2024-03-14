<?php

#[AllowDynamicProperties]

 abstract class WFOP_Divi_HTML_BLOCK extends WFOP_Divi_Field {

	public function __construct() {
		parent::__construct();

		add_action( 'wp_footer', [ $this, 'localize_array' ] );
	}


	final public function render( $attrs, $content = null, $render_slug = '' ) {

		$this->prepare_css( $attrs, $content, $render_slug );

		if ( apply_filters( 'wfop_print_divi_widget', true, $this->get_slug(), $this ) ) {

			return "<div id='{$this->slug}'>" . $this->html( $attrs, $content, $render_slug ) . '</div>';
		}

	}

	protected function html( $attrs, $content = null, $render_slug = '' ) { //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedParameter,VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		return '';
	}


	protected function available_html_block() {
		$block = [ 'product_switching', 'order_total' ];

		return apply_filters( 'wfop_html_block_elements', $block );
	}


	public function localize_array() {
		global $post;

		if ( ! is_null( $post ) && $post->post_type !== WFOPP_Core()->optin_pages->get_post_type_slug() ) {
			return;
		}
		$fields              = array_merge( $this->modules_fields, $this->tab_array );
		$border_data         = [];
		$box_data            = [];
		$border_start        = false;
		$margin_padding_data = [];
		$normal_data         = [];
		$typography_data     = [];
		$box_start           = false;

		foreach ( $fields as $key => $field ) {
			if ( isset( $field['c_type'] ) && 'wfop_start_border' === $field['c_type'] ) {
				$border_start                       = true;
				$border_data[ $field['field_key'] ] = $field['selector'];
				continue;
			}
			if ( isset( $field['c_type'] ) && 'wfop_end_border' === $field['c_type'] ) {
				$border_start = false;
				continue;
			}
			if ( isset( $field['c_type'] ) && 'wfop_start_box_shadow' === $field['c_type'] ) {
				$box_start                       = true;
				$box_data[ $field['field_key'] ] = $field['selector'];
				continue;
			}
			if ( isset( $field['c_type'] ) && 'wfop_end_box_shadow' === $field['c_type'] ) {
				$box_start = false;
				continue;
			}


			if ( true === $border_start || true === $box_start ) {
				continue;
			}

			if ( ! isset( $field['selector'] ) ) {
				continue;
			}
			$type     = isset( $fields[ $key ]['c_type'] ) ? $fields[ $key ]['c_type'] : ( isset( $fields[ $key ]['type'] ) ? $fields[ $key ]['type'] : '' );
			$property = $this->create_css_property( $key, $type );

			if ( empty( $property ) ) {
				continue;
			}


			if ( false !== strpos( $key, '_margin' ) || false !== strpos( $key, '_padding' ) ) {
				$margin_padding_data[ $key ] = $field['selector'];
				continue;
			} else {
				$normal_data[ $key ] = [ 'selector' => $field['selector'], 'property' => $property['property'] ];
			}
			if ( isset( $this->typography[ $key ] ) ) {
				$typography_data[ $key ] = $field['selector'];
			}
		}
		?>
        <script>
            function <?php echo $this->get_slug();//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>_fields(utils, props) {
                let data = {};
                data.typography =<?php echo count( $this->typography ) > 0 ? json_encode( $this->typography ) : '{}';//phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode ?>;
                data.margin_padding =<?php echo count( $margin_padding_data ) > 0 ? json_encode( $margin_padding_data ) : '{}'; //phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode?>;
                data.normal_data =<?php echo count( $normal_data ) > 0 ? json_encode( $normal_data ) : '{}'; //phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode?>;
                data.typography_data =<?php echo count( $typography_data ) > 0 ? json_encode( $typography_data ) : '{}'; //phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode?>;
                data.border_data =<?php echo count( $border_data ) > 0 ? json_encode( $border_data ) : '{}'; //phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode?>;
                data.box_shadow =<?php echo count( $box_data ) > 0 ? json_encode( $box_data ) : '{}'; //phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode?>;
                return wfop_prepare_divi_css(data, utils, props);
            }
        </script>
		<?php
	}

	public function prepare_css( $attrs, $content, $render_slug ) {


		$fields = array_merge( $this->modules_fields, $this->tab_array );

		if ( empty( $fields ) ) {
			return;
		}

		$border_data  = [];
		$box_data     = [];
		$border_start = false;
		$box_start    = false;
		foreach ( $fields as $key => $field ) {
			if ( isset( $field['c_type'] ) && 'wfop_start_border' === $field['c_type'] ) {
				$border_start                       = true;
				$border_data[ $field['field_key'] ] = $field['selector'];
				continue;
			}
			if ( isset( $field['c_type'] ) && 'wfop_end_border' === $field['c_type'] ) {
				$border_start = false;
				continue;
			}


			if ( isset( $field['c_type'] ) && 'wfop_start_box_shadow' === $field['c_type'] ) {
				$box_start                       = true;
				$box_data[ $field['field_key'] ] = $field['selector'];
				continue;
			}
			if ( isset( $field['c_type'] ) && 'wfop_end_box_shadow' === $field['c_type'] ) {
				$box_start = false;
				continue;
			}


			if ( true === $border_start || true === $box_start ) {
				continue;
			}

			if ( ! isset( $field['selector'] ) ) {
				continue;
			}

			$type     = isset( $fields[ $key ]['c_type'] ) ? $fields[ $key ]['c_type'] : ( isset( $fields[ $key ]['type'] ) ? $fields[ $key ]['type'] : '' );
			$property = $this->create_css_property( $key, $type );


			if ( empty( $property ) ) {
				continue;
			}


			$css_prop = $property['property'];

			if ( false !== strpos( $key, '_margin' ) || false !== strpos( $key, '_padding' ) ) {
				ET_Builder_Element::set_style( $render_slug, array(
					'selector'    => $field['selector'],
					'declaration' => et_builder_get_element_style_css( $this->props[ $key ], $type, true ),
				) );


				$slug_value_tablet            = $this->props[ $key . '_tablet' ];
				$slug_value_phone             = $this->props[ $key . '_phone' ];
				$slug_value_last_edited       = $this->props[ $key . '_last_edited' ];
				$slug_value_responsive_active = et_pb_get_responsive_status( $slug_value_last_edited );

				if ( isset( $slug_value_tablet ) && ! empty( $slug_value_tablet ) && $slug_value_responsive_active ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => $field['selector'],
						'declaration' => et_builder_get_element_style_css( $slug_value_tablet, $type, true ),
						'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
					) );
				}

				if ( isset( $slug_value_phone ) && ! empty( $slug_value_phone ) && $slug_value_responsive_active ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => $field['selector'],
						'declaration' => et_builder_get_element_style_css( $slug_value_phone, $type, true ),
						'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),

					) );
				}


			} elseif ( isset( $this->props[ $key ] ) && '' !== $this->props[ $key ] ) {




				ET_Builder_Element::set_style( $render_slug, array(
					'selector'    => $field['selector'],
					'declaration' => sprintf( '' . $css_prop . ': %1$s;', $this->props[ $key ] . " !important" ),
				) );

				if ( et_pb_responsive_options()->is_responsive_enabled( $this->props, $key ) ) {
					$responsive_value = et_pb_responsive_options()->get_property_values( $this->props, $key );

					if ( isset( $responsive_value['tablet'] ) ) {
						ET_Builder_Element::set_style( $render_slug, array(
							'selector'    => $field['selector'],
							'declaration' => sprintf( '' . $css_prop . ': %1$s;', $responsive_value['tablet'] . " !important" ),
							'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
						) );

					}
					if ( isset( $responsive_value['phone'] ) ) {

						ET_Builder_Element::set_style( $render_slug, array(
							'selector'    => $field['selector'],
							'declaration' => sprintf( '' . $css_prop . ': %1$s;', $responsive_value['phone'] . " !important" ),
							'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
						) );
					}

				}

				if ( $key === 'wfop_form_fields_focus_color' ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => $field['selector'],
						'declaration' => sprintf( '' . $css_prop . ': %1$s;', "0 0 0 1px " . $this->props[ $key ] ),
					) );

				}
			}


			if ( is_array( $this->typography ) && count( $this->typography ) > 0 && isset( $this->typography[ $key ] ) ) {
				$typography = $this->typography[ $key ];


				if( isset( $this->props[ $typography ] )) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => $field['selector'],
						'declaration' => et_builder_set_element_font( $this->props[ $typography ] ),
					) );
				}
			}
		}


		if ( count( $border_data ) > 0 ) {

			foreach ( $border_data as $key => $selector ) {
				$type         = isset( $this->props[ $key . '_border_type' ] ) ? $this->props[ $key . '_border_type' ] : $fields[ $key . '_border_type' ]['default'];
				$width_top    = isset( $this->props[ $key . '_border_width_top' ] ) ? $this->props[ $key . '_border_width_top' ] : $fields[ $key . '_border_width_top' ]['default'];
				$width_bottom = isset( $this->props[ $key . '_border_width_bottom' ] ) ? $this->props[ $key . '_border_width_bottom' ] : $fields[ $key . '_border_width_bottom' ]['default'];
				$width_left   = isset( $this->props[ $key . '_border_width_left' ] ) ? $this->props[ $key . '_border_width_left' ] : $fields[ $key . '_border_width_left' ]['default'];
				$width_right  = isset( $this->props[ $key . '_border_width_right' ] ) ? $this->props[ $key . '_border_width_right' ] : $fields[ $key . '_border_width_right' ]['default'];

				$border_color = isset( $this->props[ $key . '_border_color' ] ) ? $this->props[ $key . '_border_color' ] : $fields[ $key . '_border_color' ]['default'];

				$radius_top_left     = isset( $this->props[ $key . '_border_radius_top' ] ) ? $this->props[ $key . '_border_radius_top' ] : $fields[ $key . '_border_radius_top' ]['default'];
				$radius_bottom_left  = isset( $this->props[ $key . '_border_radius_right' ] ) ? $this->props[ $key . '_border_radius_right' ] : $fields[ $key . '_border_radius_right' ]['default'];
				$radius_top_right    = isset( $this->props[ $key . '_border_radius_bottom' ] ) ? $this->props[ $key . '_border_radius_bottom' ] : $fields[ $key . '_border_radius_bottom' ]['default'];
				$radius_bottom_right = isset( $this->props[ $key . '_border_radius_left' ] ) ? $this->props[ $key . '_border_radius_left' ] : $fields[ $key . '_border_radius_left' ]['default'];


				if ( 'none' === $type ) {
					ET_Builder_Element::set_style( $render_slug, [
						'selector'    => $selector,
						'declaration' => 'border-style:none !important;'
					] );
					ET_Builder_Element::set_style( $render_slug, [
						'selector'    => $selector,
						'declaration' => 'border-radius:0px !important;'
					] );
				} else {
					ET_Builder_Element::set_style( $render_slug, [
						'selector'    => $selector,
						'declaration' => sprintf( 'border-color:%s !important;;', $border_color )
					] );
					ET_Builder_Element::set_style( $render_slug, [
						'selector'    => $selector,
						'declaration' => sprintf( 'border-style:%s !important;', $type )
					] );
					ET_Builder_Element::set_style( $render_slug, [
						'selector'    => $selector,
						'declaration' => sprintf( 'border-top-width:%spx !important;', $width_top )
					] );
					ET_Builder_Element::set_style( $render_slug, [
						'selector'    => $selector,
						'declaration' => sprintf( 'border-bottom-width:%spx !important;', $width_bottom )
					] );
					ET_Builder_Element::set_style( $render_slug, [
						'selector'    => $selector,
						'declaration' => sprintf( 'border-left-width:%spx !important;', $width_left )
					] );
					ET_Builder_Element::set_style( $render_slug, [
						'selector'    => $selector,
						'declaration' => sprintf( 'border-right-width:%spx !important;', $width_right )
					] );

					ET_Builder_Element::set_style( $render_slug, [
						'selector'    => $selector,
						'declaration' => sprintf( 'border-top-left-radius:%spx !important;', $radius_top_left )
					] );
					ET_Builder_Element::set_style( $render_slug, [
						'selector'    => $selector,
						'declaration' => sprintf( 'border-top-right-radius:%spx !important;', $radius_bottom_left )
					] );
					ET_Builder_Element::set_style( $render_slug, [
						'selector'    => $selector,
						'declaration' => sprintf( 'border-bottom-right-radius:%spx !important;', $radius_top_right )
					] );
					ET_Builder_Element::set_style( $render_slug, [
						'selector'    => $selector,
						'declaration' => sprintf( 'border-bottom-left-radius:%spx !important;', $radius_bottom_right )
					] );
				}



			}

			foreach ( $box_data as $key => $selector ) {

				$enabled    = isset( $this->props[ $key . '_shadow_enable' ] ) ? $this->props[ $key . '_shadow_enable' ] : $fields[ $key . '_shadow_enable' ]['default'];
				$type       = isset( $this->props[ $key . '_shadow_type' ] ) ? $this->props[ $key . '_shadow_type' ] : $fields[ $key . '_shadow_type' ]['default'];
				$horizontal = isset( $this->props[ $key . '_shadow_horizontal' ] ) ? $this->props[ $key . '_shadow_horizontal' ] : $fields[ $key . '_shadow_horizontal' ]['default'];
				$vertical   = isset( $this->props[ $key . '_shadow_vertical' ] ) ? $this->props[ $key . '_shadow_vertical' ] : $fields[ $key . '_shadow_vertical' ]['default'];
				$blur       = isset( $this->props[ $key . '_shadow_blur' ] ) ? $this->props[ $key . '_shadow_blur' ] : $fields[ $key . '_shadow_blur' ]['default'];
				$spread     = isset( $this->props[ $key . '_shadow_spread' ] ) ? $this->props[ $key . '_shadow_spread' ] : $fields[ $key . '_shadow_spread' ]['default'];
				$box_color  = isset( $this->props[ $key . '_shadow_color' ] ) ? $this->props[ $key . '_shadow_color' ] : $fields[ $key . '_shadow_color' ]['default'];

				if ( 'on' === $enabled ) {
					ET_Builder_Element::set_style( $render_slug, [
						'selector'    => $selector,
						'declaration' => sprintf( 'box-shadow:%spx %spx %spx %spx %s %s !important;', $horizontal, $vertical, $blur, $spread, $box_color, $type )
					] );
				} else {
					ET_Builder_Element::set_style( $render_slug, [
						'selector'    => $selector,
						'declaration' => 'box-shadow:none !important;'
					] );
				}
			}
		}

	}

	/**
	 * @param $field STring
	 * @param $this \Elementor\Widget_Base
	 */
	protected function generate_html_block( $field_key ) {
		if ( method_exists( $this, $field_key ) ) {
			$this->{$field_key}( $field_key );
		}
	}

}