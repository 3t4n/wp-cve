<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_Admin_Post_Options {

	protected static $options_data = false;

	/**
	 * Option key, and option page slug
	 * @var string
	 */
	private static $key = 'xlwcty_post_option';

	/**
	 * Options page metabox id
	 * @var string
	 */
	private static $metabox_id = 'xlwcty_post_option_metabox';

	/**
	 * Setting Up CMB2 Fields
	 */
	public static function setup_fields() {

		// single builder main fields metabox
		$box_options         = array(
			'id'         => 'xlwcty_builder_settings',
			'title'      => __( 'Component Builder Settings', 'woo-thank-you-page-nextmove-lite' ),
			'classes'    => 'xlwcty_options_common',
			'show_names' => true,
			'context'    => 'normal',
			'priority'   => 'high',
		);
		$cmb2_builder_fields = new_cmb2_box( $box_options );
		$get_fields          = include plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'admin/includes/cmb2-wcthankyou-meta-config.php';

		$get_fields = self::parse_default_values( $get_fields );
		foreach ( $get_fields as $field_arr ) {
			if ( isset( $field_arr['is_multiple'] ) && $field_arr['is_multiple'] === true && isset( $field_arr['count'] ) && $field_arr['count'] > 0 ) {
				for ( $i = 1; $i <= $field_arr['count']; $i ++ ) {
					foreach ( $field_arr['fields'] as $field ) {
						$field = self::recursive_replace_index_tag( $field, $i );

						$cmb2_builder_fields->add_field( $field );
					}
				}
			} else {
				foreach ( $field_arr['fields'] as $field ) {
					$cmb2_builder_fields->add_field( $field );
				}
			}
		}
		$cmb2_builder_fields->add_field( array(
			'id'   => '_xlwcty_chosen_order_preview',
			'type' => 'hidden',
		) );

		$cmb2_builder_fields->add_field( array(
			'id'   => '_xlwcty_builder_layout',
			'type' => 'hidden',
		) );
		$cmb2_builder_fields->add_field( array(
			'id'   => '_xlwcty_builder_template',
			'type' => 'hidden',
		) );

		$cmb2_builder_fields->add_field( array(
			'id'   => '_wp_page_template',
			'type' => 'hidden',
		) );
		$box_options_global         = array(
			'id'      => 'xlwcty_global_settings',
			'title'   => __( 'Global Settings', 'woo-thank-you-page-nextmove-lite' ),
			'classes' => 'xlwcty_options_common',
			'hookup'  => false,
			'show_on' => array(
				'key'   => 'options-page',
				'value' => array( 'xlwcty' ),
			),
		);
		$cmb2_builder_fields_global = new_cmb2_box( $box_options_global );

		add_filter( 'intermediate_image_sizes', array( __CLASS__, 'xlwcty_modify_intermediate_image_sizes' ), 10, 1 );
		$get_fields = include plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'admin/includes/cmb2-settings-config.php';
		remove_filter( 'intermediate_image_sizes', array( __CLASS__, 'xlwcty_modify_intermediate_image_sizes' ) );

		foreach ( $get_fields as $field ) {
			$cmb2_builder_fields_global->add_field( self::settings_add_default_value( $field ) );
		}
		$box_options_debug          = array(
			'id'      => 'xlwcty_debug_settings',
			'title'   => __( 'Debug Settings', 'woo-thank-you-page-nextmove-lite' ),
			'classes' => 'xlwcty_options_common',
			'hookup'  => false,
			'show_on' => array(
				'key'   => 'options-page',
				'value' => array( 'xlwcty' ),
			),
		);
		$cmb2_builder_fields_global = new_cmb2_box( $box_options_debug );
		$get_fields                 = include plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'admin/includes/cmb2-debug-config.php';
		foreach ( $get_fields as $field ) {
			$cmb2_builder_fields_global->add_field( self::settings_add_default_value( $field ) );
		}
	}

	public static function parse_default_values( $field_config ) {

		foreach ( $field_config as $slug => $fields ) {

			$get_component = XLWCTY_Components::get_components( $slug );
			$defaults      = $get_component->get_defaults();
			$get_mapping   = $get_component->fields;

			foreach ( $field_config[ $slug ]['fields'] as $key => $field ) {

				//handling for the multiple field components
				$field['id'] = str_replace( '_{{index}}', '', $field['id'] );

				if ( $slug . '_enable' == $field['id'] || $slug . '_hide_mobile' == $field['id'] || $slug . '_hide_desktop' == $field['id'] ) {
					continue;
				}

				$continue = apply_filters( 'xlwcty_skip_field_for_default_values', false, $slug, $field );
				if ( $continue === true ) {
					continue;
				}

				$mapped_key = array_search( $field['id'], $get_mapping );

				if ( $mapped_key !== false && isset( $field_config[ $slug ]['fields'][ $key ] ) && isset( $defaults[ $mapped_key ] ) ) {
					$field_config[ $slug ]['fields'][ $key ]['default'] = $defaults[ $mapped_key ];
				}
			}
		}

		return $field_config;
	}

	public static function menu_order_metabox_fields() {

		$box_options = array(
			'id'           => 'xlwcty_menu_order_settings',
			'title'        => __( 'Thank You Page Priority', 'woo-thank-you-page-nextmove-lite' ),
			'classes'      => 'xlwcty_options_common',
			'object_types' => array( XLWCTY_Common::get_thank_you_page_post_type_slug() ),
			'show_names'   => true,
			'context'      => 'side',
			'priority'     => 'low',
		);
		$cmb         = new_cmb2_box( $box_options );

		$priority_desc      = __( 'Page Priority features come into play when multiple Thank You pages are set.', 'woo-thank-you-page-nextmove-lite' );
		$priority_desc      .= '<br/><br/>' . __( 'Priority works in ascending order. Lower the number higher the priority.', 'woo-thank-you-page-nextmove-lite' );
		$priority_desc_link = add_query_arg( array(
			'utm_source'   => 'nextmove-pro',
			'utm_campaign' => 'sidebar',
			'utm_medium'   => 'text-click',
			'utm_term'     => 'priority-usage',
		), 'https://xlplugins.com/documentation/nextmove-woocommerce-thank-you-page/page-priority/' );
		$priority_desc      .= '<br/><br/>' . __( 'Need Help with priority? ', 'woo-thank-you-page-nextmove-lite' ) . '<a href="' . $priority_desc_link . '" target="_blank">';
		$priority_desc      .= __( 'Read Docs', 'woo-thank-you-page-nextmove-lite' );
		$priority_desc      .= '</a>';

		$shortcode_box = array(
			array(
				'id'       => 'xlwcty_shortcode_metabox',
				'position' => 1,
				'fields'   => array(
					array(
						'name'       => 'Page Priority',
						'desc'       => $priority_desc,
						'id'         => '_xlwcty_menu_order',
						'type'       => 'text',
						'attributes' => array(
							'type'    => 'number',
							'pattern' => '\d*',
						),

						'sanitization_cb' => 'absint',
						'escape_cb'       => array( 'XLWCTY_Admin_CMB2_Support', 'escape_cb_to_consider_default' ),

					),

				),
			),
		);
		foreach ( $shortcode_box as $meta_box ) {
			foreach ( $meta_box['fields'] as $fields ) {
				$cmb->add_field( $fields );
			}
		}
	}

	public static function quick_view_metabox_fields() {

		$box_options   = array(
			'id'           => 'xlwcty_quick_view_settings',
			'title'        => __( 'Quick View', 'woo-thank-you-page-nextmove-lite' ),
			'classes'      => 'xlwcty_options_common',
			'object_types' => array( XLWCTY_Common::get_thank_you_page_post_type_slug() ),
			'show_names'   => true,
			'context'      => 'side',
			'priority'     => 'default',
		);
		$cmb           = new_cmb2_box( $box_options );
		$shortcode_box = array(
			array(
				'id'       => 'xlwcty_shortcode_metabox',
				'position' => 1,
				'fields'   => array(
					array(
						'content'     => apply_filters( 'xlwcty_get_qyuck_view', '' ),
						'id'          => '_xlwcty_qv_html',
						'type'        => 'xlwcty_html_content_field',
						'row_classes' => array( 'row_title_classes', 'xlwcty_small_text', 'xlwcty_label_gap', 'xlwcty_p0' ),
					),
				),
			),
		);
		foreach ( $shortcode_box as $meta_box ) {
			foreach ( $meta_box['fields'] as $fields ) {
				$cmb->add_field( $fields );
			}
		}
	}

	/**
	 * Setting up property `options_data` by options data saved.
	 */
	public static function prepere_default_config() {
		self::$options_data = XLWCTY_Common::get_default_settings();
	}

	public static function xlwcty_button_html() {
		$new_array = array();
		ob_start();
		$customization_fields = include plugin_dir_path( XLWCTY_PLUGIN_FILE ) . 'admin/includes/cmb2-wcthankyou-meta-config.php';
		if ( is_array( $customization_fields ) && count( $customization_fields ) > 0 ) {
			foreach ( $customization_fields as $val ) {
				if ( isset( $val['is_multiple'] ) && $val['is_multiple'] === true && isset( $val['count'] ) && $val['count'] > 0 ) {
					for ( $i = 1; $i <= $val['count']; $i ++ ) {
						$new_array[ $val['position'] ][ $i ]['title']           = $val['xlwcty_accordion_title'] . ' ' . $i;
						$new_array[ $val['position'] ][ $i ]['component_title'] = $val['xlwcty_accordion_title'];
						$new_array[ $val['position'] ][ $i ]['id']              = $val['id'];
						if ( isset( $val['xlwcty_accordion_head'] ) && $i === 1 ) {
							$new_array[ $val['position'] ][ $i ]['head'] = $val['xlwcty_accordion_head'];
						}
						if ( isset( $val['xlwcty_accordion_head_end'] ) && $i === $val['count'] ) {
							$new_array[ $val['position'] ][ $i ]['head_end'] = $val['xlwcty_accordion_head_end'];
							$new_array[ $val['position'] ][ $i ]['add_more'] = true;
						}
						if ( isset( $val['xlwcty_icon'] ) ) {
							$new_array[ $val['position'] ][ $i ]['icon'] = $val['xlwcty_icon'];
						}
					}
				} else {
					$new_array[ $val['position'] ][0]['title'] = $val['xlwcty_accordion_title'];
					$new_array[ $val['position'] ][0]['id']    = $val['id'];
					if ( isset( $val['xlwcty_accordion_head'] ) ) {
						$new_array[ $val['position'] ][0]['head'] = $val['xlwcty_accordion_head'];
					}
					if ( isset( $val['xlwcty_accordion_head_end'] ) ) {
						$new_array[ $val['position'] ][0]['head_end'] = $val['xlwcty_accordion_head_end'];
					}
					if ( isset( $val['xlwcty_icon'] ) ) {
						$new_array[ $val['position'] ][0]['icon'] = $val['xlwcty_icon'];
					}
					if ( isset( $val['xlwcty_disabled'] ) && $val['xlwcty_disabled'] == 'yes' ) {
						$new_array[ $val['position'] ][0]['disabled'] = 'yes';
					}
				}
			}

			ksort( $new_array );
			$is_multiple_arr_count        = array();
			$layout                       = get_post_meta( $_GET['id'], '_xlwcty_builder_layout', true );
			$template                     = get_post_meta( $_GET['id'], '_xlwcty_builder_template', true );
			$get_choosed_templ_components = array();
			if ( $layout != '' && $template != '' ) {
				$template_data = json_decode( $layout, true );
				if ( is_array( $template_data ) && count( $template_data ) > 0 ) {
					if ( array_key_exists( $template, $template_data ) ) {
						$get_choosed_templ_components = $template_data[ $template ];
					}
				}
			}

			$get_all_components = array();

			if ( count( $get_choosed_templ_components ) > 0 ) {
				foreach ( $get_choosed_templ_components as $key => $layout_val ) {
					if ( is_array( $layout_val ) && count( $layout_val ) > 0 ) {
						foreach ( $layout_val as $k => $v ) {
							$get_all_components[] = $v['slug'];
						}
					}
				}
			}

			if ( is_array( $new_array ) ) {
				foreach ( $new_array as $component_arr ) {
					$multiple = false;
					if ( is_array( $component_arr ) && count( $component_arr ) > 1 ) {
						$multiple = true;
					}
					$k = 1;

					foreach ( $component_arr as $key => $component ) {
						$unique_id               = '';
						$component_selected      = '';
						$component_selected_html = '';
						echo isset( $component['head'] ) ? '<div class="xlwcty_field_head">' . $component['head'] . '</div><div class="xlwcty_field_wrap">' : '';
						if ( isset( $component['title'] ) && $component['title'] != '' ) {
							if ( $multiple ) {
								$unique_id = '_' . $k;
							}
							$data_comp           = $component['id'];
							$is_mulpliple_gr_one = '';
							if ( $multiple && ! in_array( $data_comp . $unique_id, $get_all_components ) ) {
								if ( ! isset( $is_multiple_arr_count[ $data_comp ] ) ) {
									$is_multiple_arr_count[ $data_comp ]          = array();
									$is_multiple_arr_count[ $data_comp ]['count'] = 0;
								}
								$is_multiple_arr_count[ $data_comp ]['count'] ++;
								$is_multiple_arr_count[ $data_comp ]['title']                           = $component['component_title'];
								$is_multiple_arr_count[ $data_comp ]['slug'][ $data_comp . $unique_id ] = 1;
								$is_mulpliple_gr_one                                                    = 'xlwct_hide_this_componets';
							}

							if ( isset( $component['disabled'] ) ) {
								$component_selected .= ' xlwcty_lock';
							}

							echo '<div class="xlwcty_field_btn' . $component_selected . ' ' . $is_mulpliple_gr_one . '" data-slug="' . $data_comp . $unique_id . '" data-component="' . $data_comp . '" >';
							if ( isset( $component['icon'] ) ) {
								echo '<div class="xlwcty_field_icon"><i class="' . $component['icon'] . '"></i></div>';
							} else {
								echo '<div class="xlwcty_field_icon"><i class="xlwcty-fa xlwcty-fa-cog"></i></div>';
							}
							echo '<p>' . $component['title'] . '</p>';
							echo $component_selected_html;
							if ( isset( $component['disabled'] ) ) {
								echo '<div class="xlwcty_lockicon"><i class="xlwcty-fa xlwcty-fa-lock"></i></div>';
							}
							echo '</div>';
						}

						if ( isset( $component['head_end'] ) && $multiple == true ) {
							if ( is_array( $is_multiple_arr_count ) && count( $is_multiple_arr_count ) > 0 ) {
								global $template_previews;
								?>
                                <div class="xlwcty_field_btn xlwcty_add_more_ui">
                                    <div class="xlwcty_add_more_items">
                                        <i class="xlwcty-fa xlwcty-fa-plus"></i>
                                    </div>
                                    <div class="xlwcty_btn_layouts">
                                        <ul>
											<?php
											foreach ( $is_multiple_arr_count as $key => $val ) {
												?>
                                                <li class='xlwcty_add_more_componets' data-component="<?php echo $key; ?>" data-available-slug='<?php echo wp_json_encode( $val['slug'] ); ?>'
                                                    data-count="<?php echo 1; ?>" data-max="<?php echo $val['count'] + 1; ?>"><?php _e( $val['title'] ); ?>
                                                    (<strong><?php echo $val['count']; ?></strong>)
                                                </li>
												<?php
											}
											?>
                                        </ul>
                                    </div>
                                </div>
								<?php
							}
						}
						echo isset( $component['head_end'] ) ? '</div>' : '';
						$k ++;
					}
				}
			}
		}

		return ob_get_clean();
	}

	public static function xlwcty_builder_html() {
		global $template_previews;
		global $template_previews_template;
		$template_previews_template = get_post_meta( $_GET['id'], '_xlwcty_builder_template', true );
		$layouts                    = XLWCTY_Common::get_builder_layouts();
		ob_start();
		?>
        <div class="xlwcty_metabox_head"><span>3</span> Choose Layout</div>
        <div class="xlwcty_layouts">
            <ul>
				<?php
				foreach ( $layouts as $layout ) {
					$slug  = $layout['slug'];
					$title = $layout['name'];

					$class      = "{{builder_template=='" . $slug . "'?'layout_selected':''}}";
					$clickevent = sprintf( "xlwcty_change_template('" . $slug . "', %s)", '$event' );
					?>
                    <li class="<?php echo $class; ?>" data-temp="<?php echo $slug; ?>" ng-click="<?php echo $clickevent; ?>">
                        <img src="<?php echo $layout['preview']; ?>"/>
                        <p><?php echo $title; ?></p>
                        <div class="xlwcty_checked">
                            <div class="dashicons xl-dashicons-yes">&nbsp;</div>
                        </div>
                    </li>
					<?php
				}
				?>

            </ul>
        </div>
        <div class="xlwcty_clear_20"></div>
        <div class="xlwcty_metabox_head xlwcty_metabox_head_top"><span>4</span> Arrange Components</div>
        <div class="xlwcty_clear_20"></div>
        <div class="xlwcty_template_preview">
            <div class="xlwcty_template_wrap">
                <div class="xlwcty_layout_prev_header">Header</div>
                <div class="xlwcty_default_layout" ng-if="builder_template == 'basic'">
                    <ul class="xlwcty_layout_prev_content xlwcty_layout_prev_content_basic xlTemplateUi"
                        ng-if="builder_componets.basic">
                        <li class="xlwcty_layout_components" ng-repeat="comp in builder_componets.basic.first" data-slug="{{comp.slug}}" data-component="{{comp.component}}" data-title="{{comp.name}}">
                            {{comp.name}}
                        </li>
                    </ul>
                </div>
                <div ng-if="builder_template == 'two_column'">
                    <ul class="xlwcty_layout_prev_content_t xlTemplateUi">
                        <li class="xlwcty_layout_components" ng-repeat="comp in builder_componets.two_column.third" data-slug="{{comp.slug}}" data-component="{{comp.component}}"
                            data-title="{{comp.name}}">
                            {{comp.name}}
                        </li>
                    </ul>
                    <div style="clear:both"></div>
                    <div class="xlwcty_layout_prev_content two_column-2col">
                        <ul class="xlwcty_layout_prev_content_l xlTemplateUi">
                            <li class="xlwcty_layout_components" ng-repeat="comp in builder_componets.two_column.first" data-slug="{{comp.slug}}" data-component="{{comp.component}}"
                                data-title="{{comp.name}}">
                                {{comp.name}}
                            </li>
                        </ul>
                        <ul class="xlwcty_layout_prev_content_r xlTemplateUi">
                            <li class="xlwcty_layout_components" ng-repeat="comp in builder_componets.two_column.second" data-slug="{{comp.slug}}" data-component="{{comp.component}}"
                                data-title="{{comp.name}}">
                                {{comp.name}}
                            </li>
                        </ul>
                        <div style="clear:both"></div>
                    </div>

                </div>
                <div class="xlwcty_layout_prev_footer">Footer</div>
            </div>
        </div>
        <div class="xlwcty_clear_20"></div>

		<?php
		return ob_get_clean();
	}

	public static function recursive_replace_index_tag( $array, $index ) {

		if ( is_array( $array ) && count( $array ) > 0 ) {
			foreach ( $array as $key => $val ) {
				if ( is_array( $val ) ) {
					$array[ $key ] = self::recursive_replace_index_tag( $val, $index );
				} else {
					if ( is_string( $val ) && strpos( $val, '{{index}}' ) !== false ) {
						$array[ $key ] = str_replace( '{{index}}', $index, $val );
					}
				}
			}
		}

		return $array;
	}

	public static function settings_add_default_value( $field ) {
		$get_defaults = XLWCTY_Common::get_options_defaults();
		if ( array_key_exists( $field['id'], $get_defaults ) ) {
			$field['default'] = $get_defaults[ $field['id'] ];
		}

		return $field;
	}

	public static function xlwcty_modify_intermediate_image_sizes( $sizes ) {
		$new_array = array();
		if ( is_array( $sizes ) && count( $sizes ) > 0 ) {
			foreach ( $sizes as $key => $val ) {
				$new_array[ $key ] = ucwords( str_replace( '_', ' ', $val ) );
			}
		}

		return $new_array;
	}

}
