<?php

// Bring Block layouts/patterns to View

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'PT_CV_BlockToView' ) ) {

	class PT_CV_BlockToView {

		static function hybrid_hooks() {
			add_filter( PT_CV_PREFIX_ . 'view_type_settings', array( __CLASS__, 'filter_view_type_settings' ) );
			add_filter( PT_CV_PREFIX_ . 'layout_extra_settings', array( 'PT_CV_Settings', 'layout_shared_settings' ) );
			add_filter( PT_CV_PREFIX_ . 'viewtype_setting', array( __CLASS__, 'filter_viewtype_setting' ), 11 );

			add_action( PT_CV_PREFIX_ . 'admin_view_header', array( __CLASS__, 'action_admin_view_header' ) );
			add_filter( PT_CV_PREFIX_ . 'view_type', array( __CLASS__, 'filter_view_type' ), 11 );
			add_filter( PT_CV_PREFIX_ . 'responsive_columns', array( __CLASS__, 'filter_responsive_columns' ), 11 );

			// important
			add_filter( PT_CV_PREFIX_ . 'pre_save_view_data', array( __CLASS__, 'import_pattern_to_view' ) );
			add_filter( PT_CV_PREFIX_ . 'preview_settings', array( __CLASS__, 'filter_merge_settings' ), 10, 2 );
			add_filter( PT_CV_PREFIX_ . 'view_settings', array( __CLASS__, 'filter_hybrid_view_settings' ), 11 );

			add_filter( PT_CV_PREFIX_ . 'block_generated_css', array( __CLASS__, 'filter_block_generated_css' ), 11 );

			add_action( PT_CV_PREFIX_ . 'add_global_variables', array( __CLASS__, 'action_add_global_variables' ) );
			add_action( PT_CV_PREFIX_ . 'print_view_style', array( __CLASS__, 'action_print_view_style' ) );

			// extra
			add_filter( PT_CV_PREFIX_ . 'preview_footer', array( __CLASS__, 'action_preview_footer' ) );
			add_filter( PT_CV_PREFIX_ . 'topmeta_show', array( 'PT_CV_Settings', 'topmeta_show_settings' ), 10, 2 );
			add_filter( PT_CV_PREFIX_ . 'topmeta_settings', array( 'PT_CV_Settings', 'topmeta_settings' ), 10, 2 );
			add_filter( PT_CV_PREFIX_ . 'content_settings', array( 'PT_CV_Settings', 'content_settings' ), 10, 2 );
			add_filter( PT_CV_PREFIX_ . 'excerpt_extra_settings', array( 'PT_CV_Settings', 'excerpt_extra_settings' ), 10, 2 );
			add_filter( PT_CV_PREFIX_ . 'readmore_extra_settings', array( 'PT_CV_Settings', 'readmore_extra_settings' ) );
			add_filter( PT_CV_PREFIX_ . 'metafield_extra_settings', array( 'PT_CV_Settings', 'metafield_extra_settings' ) );
			add_filter( PT_CV_PREFIX_ . 'field_thumbnail_settings', array( 'PT_CV_Settings', 'thumbnail_extra_settings' ), 9, 2 );
			add_filter( PT_CV_PREFIX_ . 'field_thumbnail_settings', array( 'PT_CV_Settings', 'thumbnail_bottom_settings' ), 999, 2 );
			add_filter( PT_CV_PREFIX_ . 'thumbnail_position_depend', array( __CLASS__, 'filter_thumbnail_position_depend' ) );
			add_filter( PT_CV_PREFIX_ . 'thumbnail_position_extra', array( 'PT_CV_Settings', 'thumbnail_position_extra_settings' ) );
			add_filter( PT_CV_PREFIX_ . 'layout_format_depend', array( __CLASS__, 'filter_layout_format_depend' ) );
			add_filter( PT_CV_PREFIX_ . 'ppp_settings', array( 'PT_CV_Settings', 'ppp_settings' ) );

			add_filter( PT_CV_PREFIX_ . 'dargs_hybrid', array( __CLASS__, 'filter_dargs_hybrid' ) );
			add_filter( PT_CV_PREFIX_ . 'field_href_class', array( __CLASS__, 'filter_field_href_class' ), 9, 2 );
		}

		static function filter_view_type_settings( $args ) {
			foreach ( PT_CV_Values::combined_layouts() as $layout => $name ) {
				$args[ $layout ] = PT_CV_Settings::view_type_settings_hybrid( $layout );
			}
			return $args;
		}

		static function filter_viewtype_setting( $args ) {
			$new_options = [];
			foreach ( $args[ 'options' ] as $layout => $text ) {
				$img					 = plugins_url( 'block/assets/layouts/icons/', PT_CV_FILE ) . PT_CV_Values::lname( $layout ) . ($layout === 'grid1' ? '-hybrid' : '') . ".svg";
				$new_options[ $layout ]	 = PT_CV_Settings::layout_radio( $layout, false, $img, "<span>$text</span>" );
			}
			$args[ 'options' ] = $new_options;

			// changed to select in pro, reset back
			$args[ 'type' ] = 'radio';

			return $args;
		}

		static function action_admin_view_header() {
			echo sprintf( '<button type="button" id="view-library-button" class="btn btn-primary" data-toggle="modal" data-target="#cv-library-toview" style="float: right;"><image src="%s" style="max-height: 20px"/>%s </button>', plugins_url( 'block/assets/layouts/', PT_CV_FILE ) . 'icons/icon.svg', __( 'Content Views Library', 'content-views-query-and-display-post-page' ) );
			echo '<div class="modal fade" id="cv-library-toview" tabindex="-1"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-header"> <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button> </div> <div class="modal-body"></div> </div> </div> </div>';
		}

		static function action_preview_footer() {
			$layout = isset( $GLOBALS[ 'cv_blockname_origin' ] ) ? $GLOBALS[ 'cv_blockname_origin' ] : '';
			if ( $layout && PT_CV_Values::isprlayout( $layout ) ) {
				$url = esc_url( 'https://www.contentviewspro.com/?utm_source=client&utm_medium=preview&utm_campaign=proLayout&utm_content=' . $layout );
				$css = '<style>#pt-cv-preview-box .pt-cv-view {margin: 0 !important} .pt-cv-pagination-wrapper {display: none !important}</style>';
				printf( '<p class="cvgopro">To access this PRO layout, please %s upgrade now %s</p>%s', '<a href="' . $url . '" onclick="window.open(\'' . $url . '\')">', '</a>', $css );
			}
		}

		/* ---------- From Pro ---------- */
		static function filter_view_type( $args ) {
			return array_merge( $args, PT_CV_Values::combined_layouts() );
		}

		static function filter_responsive_columns( $args ) {
			$layouts = PT_CV_Values::column_layouts();
			return array_merge( $args, $layouts );
		}

		// convert imported pattern to view
		static function import_pattern_to_view( $args ) {
			if ( !empty( $args[ PT_CV_PREFIX . 'pattern-content' ] ) ) {
				$content = stripslashes( htmlspecialchars_decode( $args[ PT_CV_PREFIX . 'pattern-content' ] ) );

				preg_match( '/contentviews\/([a-z0-9]+)/', $content, $matches );
				$block_name = !empty( $matches[ 1 ] ) ? $matches[ 1 ] : null;

				self::block_to_view( $args, $block_name, $content, 'importing' );
			}

			return $args;
		}

		static function filter_merge_settings( $args, $view_id ) {
			$block_name							 = $args[ PT_CV_PREFIX . 'view-type' ];
			$GLOBALS[ 'cv_blockname_origin' ]	 = $block_name;

			$content = '<!-- wp:contentviews/grid1 {"blockId":"' . $view_id . '"} /-->';

			self::block_to_view( $args, $block_name, $content );

			return $args;
		}

		static function block_to_view( &$args, $block_name, $content, $importing = false ) {
			$should_check = $importing ? true : array_key_exists( $block_name, PT_CV_Values::hybrid_layouts() );
			if ( $should_check && isset( $GLOBALS[ 'contentviews_blocks' ][ $block_name ] ) ) {
				$blocks	 = parse_blocks( $content );
				$block	 = reset( $blocks );

				// from parseBlocks()
				$default_atts = [];
				foreach ( $GLOBALS[ 'contentviews_blocks' ][ $block_name ]->attributes as $key => $value ) {
					if ( isset( $value[ 'default' ] ) ) {
						$default_atts[ $key ] = $value[ 'default' ];
					}
				}
				$final_atts = array_merge( $default_atts, (array) $block[ 'attrs' ] );
				if ( !$importing ) {
					self::get_atts_from_view( $final_atts, $args, $block_name );
				}
				$block_data	 = $GLOBALS[ 'contentviews_blocks' ][ $block_name ]->get_attributes_and_settings( $final_atts );
				$settings	 = $block_data[ 1 ];

				$GLOBALS[ 'cv_block_atts' ] = $block_data[ 0 ];

				// in blocks, these options are included by default, must disable here
				if ( !isset( $args[ PT_CV_PREFIX . 'advanced-settings' ] ) ) {
					unset( $settings[ PT_CV_PREFIX . 'advanced-settings' ] );
				}

				// To use block attributes
				unset( $args[ PT_CV_PREFIX . 'layout-format' ] );
				unset( $args[ PT_CV_PREFIX . 'lf-nowrap' ] );
				unset( $args[ PT_CV_PREFIX . 'lf-mobile-disable' ] );
				unset( $args[ PT_CV_PREFIX . 'lf-alternate' ] );

				$arr = [
					'onebig2'	 => [ 'thumbPositionOthers' ],
				];
				if ( isset( $arr[ $block_name ] ) ) {
					foreach ( $arr[ $block_name ] as $key ) {
						unset( $args[ PT_CV_PREFIX . $key ] );
					}
				}
			
				$showf1		 = [ 'show-field-thumbnail', 'show-field-taxoterm', 'show-field-title', 'show-field-content', 'show-field-readmore', 'show-field-meta-fields', 'show-field-custom-fields', 'show-field-wooprice', 'show-field-wooatc' ];
				$showf2		 = [ 'show-field-taxoterm-Others', 'show-field-content-Others', 'show-field-readmore-Others', 'show-field-meta-fields-Others', ];
				$showf3		 = [ 'meta-fields-date', 'meta-fields-author', 'meta-fields-taxonomy', 'meta-fields-comment', 'showHeading' ];
				$show_fields = array_merge( $showf1, $showf2, $showf3 );
				if ( !$importing ) {
					foreach ( $show_fields as $field ) {
						// exclude block attributes, if fields unchecked
						if ( apply_filters( PT_CV_PREFIX_ . 'exclude_block_show_field', empty( $args[ PT_CV_PREFIX . $field ] ) ) ) {
							unset( $settings[ PT_CV_PREFIX . $field ] );
						}
					}
				} else {
					// when import
					foreach ( $show_fields as $field ) {
						if ( empty( $settings[ PT_CV_PREFIX . $field ] ) ) {
							unset( $args[ PT_CV_PREFIX . $field ] );
						} else {
							$args[ PT_CV_PREFIX . $field ] = 'yes';
						}
					}

					// update settings with attributes in pattern content
					$required_atts = [ 'whichLayout', 'alignment', 'taxoPosition', 'gridGap' ];
					foreach ( $required_atts as $att_name ) {
						if ( !isset( $block[ 'attrs' ][ $att_name ] ) && isset( $default_atts[ $att_name ] ) ) {
							$block[ 'attrs' ][ $att_name ] = $default_atts[ $att_name ];
						}
					}
					$exclude_atts = [ 'author', 'keyword', 'offset', 'parentPage', 'orderby', 'order', ];
					foreach ( $exclude_atts as $att_name ) {
						unset( $block[ 'attrs' ][ $att_name ] );
					}
					foreach ( (array) $block[ 'attrs' ] as $att_name => $value ) {
						self::change_settings_value( $args, $block_name, $att_name, $value );
					}

					// update settings with custom attributes in block definition
					foreach ( $GLOBALS[ 'contentviews_blocks' ][ $block_name ]->custom_attributes as $att_name => $info ) {
						if ( !isset( $block[ 'attrs' ][ $att_name ] ) ) {
							$value = isset( $info[ 'default' ] ) ? $info[ 'default' ] : null;
							self::change_settings_value( $args, $block_name, $att_name, $value );
						}
					}

					// readmore, do here to cover the case showContent=false in pattern content
					if ( !empty( $settings[ PT_CV_PREFIX . 'show-field-readmore' ] ) ) {
						$args[ PT_CV_PREFIX . 'field-excerpt-readmore' ] = 'yes';
						if ( empty( $args[ PT_CV_PREFIX . 'show-field-content' ] ) ) {
							$args[ PT_CV_PREFIX . 'show-field-content' ]	 = 'yes';
							$args[ PT_CV_PREFIX . 'field-content-show' ]	 = 'excerpt';
							$args[ PT_CV_PREFIX . 'field-excerpt-length' ]	 = '0';
						}
					}
				}

				$args = apply_filters( PT_CV_PREFIX_ . 'set_atts_to_view', $args, $block_data[ 0 ], $block_name, $importing );

				// posts per page
				if ( $importing && isset( $block[ 'attrs' ][ 'showPagination' ] ) ) {
					unset( $args[ PT_CV_PREFIX . 'pagination-items-per-page' ] );
					unset( $args[ PT_CV_PREFIX . 'limit' ] );
				} else {
					if ( in_array( $block_name, PT_CV_Values::fixed_ppp_layouts() ) ) {
						if ( !empty( $args[ PT_CV_PREFIX . 'enable-pagination' ] ) ) {
							unset( $args[ PT_CV_PREFIX . 'pagination-items-per-page' ] );
							unset( $args[ PT_CV_PREFIX . 'limit' ] );
						} else {
							$args[ PT_CV_PREFIX . 'limit' ] = $settings[ PT_CV_PREFIX . 'pagination-items-per-page' ];
						}
					} else {
						unset( $settings[ PT_CV_PREFIX . 'pagination-items-per-page' ] );
					}
				}

				// correct view type, for import/output
				if ( $importing ) {
					// view type in View UI = blockName in Block
					$args[ PT_CV_PREFIX . 'view-type' ] = $block_name;
				} else if ( !defined( 'PT_CV_VIEW_PAGE' ) ) {
					// view type in shortcode output = viewType in Block
					$args[ PT_CV_PREFIX . 'view-type' ] = $default_atts[ 'viewType' ];
				}

				// consider it as a block
				$args[ PT_CV_PREFIX . 'blockName' ]		 = $block_name;
				// use this value to distinguish from origin block
				$args[ PT_CV_PREFIX . 'hybridLayout' ]	 = true;

				$args = array_merge( $settings, $args );
			}

			$args = ContentViews_Block::topmeta_reposition( $args, true );
		}

		// Set field name/value to view from block data
		static function change_settings_value( &$args, $block_name, $att_name, $value ) {
			$defined_atts		 = $GLOBALS[ 'contentviews_blocks' ][ $block_name ]->attributes;
			$ignore_keys		 = [ 'postsPerPage' ];
			$special_keys		 = [ 'gridGap' => 'gridGap', 'showReadmore' => 'field-excerpt-readmore' ];
			$thumbwh			 = self::thumb_wh( true, true );
			$layout_prefix_keys	 = array_merge( [ 'whichLayout' => 'layout-variant', 'columns' => 'number-columns' ], array_combine( $thumbwh, $thumbwh ) );
			$info				 = $defined_atts[ $att_name ];

			// get the setting field (equivalent with "$att_name" attribute in block)
			if ( in_array( $att_name, $ignore_keys ) ) {
				$field = '';
			} else if ( isset( $special_keys[ $att_name ] ) ) {
				$field = $special_keys[ $att_name ];
			} else if ( isset( $layout_prefix_keys[ $att_name ] ) ) {
				// keys with layout prefix in field name
				$field = $block_name . '-' . $layout_prefix_keys[ $att_name ];
			} else {
				$att_key = isset( $info[ '__key' ] ) ? $info[ '__key' ] : '';
				$field	 = ($att_key === '__SAME__') ? $att_name : $att_key;
			}

			// correct the setting value when attribute value has different format/type
			$att_type = isset( $info[ 'type' ] ) ? $info[ 'type' ] : '';
			if ( $att_type === 'object' ) {
				$value	 = is_object( $value ) ? (array) $value : $value;
				$value	 = !empty( $value[ 'md' ] ) ? $value[ 'md' ] : $value;
			}
			if ( $att_type === 'boolean' ) {
				$value = $value ? 'yes' : '';
			}

			$args[ PT_CV_PREFIX . $field ] = $value;
		}

		// Use view settings to set block attributes
		static function get_atts_from_view( &$final_atts, $args, $block_name ) {
			$_prefix = PT_CV_PREFIX . $block_name . '-';

			$final_atts[ 'whichLayout' ] = 'layout1';

			if ( in_array( $block_name, PT_CV_Values::column_layouts() ) ) {
				$final_atts[ 'columns' ] = (object) [ 'md' => $args[ $_prefix . 'number-columns' ], 'sm' => $args[ PT_CV_PREFIX . 'resp-tablet-number-columns' ], 'xs' => $args[ PT_CV_PREFIX . 'resp-number-columns' ] ];
			}

			if ( isset( $args[ PT_CV_PREFIX . 'gridGap' ] ) ) {
				$final_atts[ 'gridGap' ] = (object) [ 'md' => $args[ PT_CV_PREFIX . 'gridGap' ] ];
			}

			foreach ( self::thumb_wh( true, true ) as $field ) {
				if ( isset( $args[ $_prefix . $field ] ) ) {
					$final_atts[ $field ] = (object) [ 'md' => $args[ $_prefix . $field ] ];
				}
			}

			if ( in_array( $block_name, PT_CV_Values::ovl_layouts() ) ) {
				$final_atts[ 'overlayPosition' ] = $args[ PT_CV_PREFIX . 'overlayPosition' ];
			}

			$final_atts = apply_filters( PT_CV_PREFIX_ . 'set_atts_from_view', $final_atts, $args, $block_name );
		}

		static function filter_hybrid_view_settings( $args ) {

			// for hybrid_layouts() that created in view/shortcode only
			if ( PT_CV_Functions::is_view( $args ) ) {
				// it is 0 for view just created (it only updated in second save)
				$view_id = !empty( $args[ PT_CV_PREFIX . 'view-id' ] ) ? $args[ PT_CV_PREFIX . 'view-id' ] : $GLOBALS[ 'cv_current_view' ];
				$args	 = self::filter_merge_settings( $args, $view_id );
			}

			return $args;
		}

		static function action_add_global_variables() {
			if ( isset( $GLOBALS[ 'cv_block_atts' ] ) ) {
				PT_CV_Functions::set_global_variable( 'blockAtts', $GLOBALS[ 'cv_block_atts' ] );
				unset( $GLOBALS[ 'cv_block_atts' ] );
			}
		}

		static function action_print_view_style() {
			ob_start();

			$style = self::get_view_style_hybrid();

			// Print inline style
			if ( !empty( $style ) ) {
				echo PT_CV_Html::inline_style( $style );
			}

			$view_style = ob_get_clean();

			if ( apply_filters( PT_CV_PREFIX_ . 'inline_view_style', 1 ) ) {
				echo $view_style;
			} else {
				global $cvp_view_css;
				if ( !$cvp_view_css ) {
					$cvp_view_css = array();
				}
				$cvp_view_css[] = $view_style;
			}
		}

		static function get_view_style_hybrid() {
			$args				 = '';
			$block_attributes	 = PT_CV_Functions::get_global_variable( 'blockAtts' );
			if ( $block_attributes ) {
				$args	 .= ContentViews_Block_Common::view_styles( $block_attributes );
				$args	 .= ContentViews_Block_Common::generate_styles( $block_attributes );
			}

			return $args;
		}

		static function filter_block_generated_css( $args ) {
			// only use some CSS from blocks
			if ( ContentViews_Block::is_hybrid() ) {
				$wanted_keys = [ 'thumbnail', 'thumbnailsm', 'thumbnailAll', 'grid_template' ];
				foreach ( array_keys( $args ) as $key ) {
					if ( !in_array( $key, $wanted_keys ) ) {
						unset( $args[ $key ] );
					}
				}
			}

			return $args;
		}

		// Extra
		static function thumb_wh( $with_sm = false, $key_only = false ) {
			$arr = [
				[ __( 'Width' ), 'thumbnailMaxWidth', 'thumbnailMaxWidthUnits' ],
				[ __( 'Height' ), 'thumbnailHeight', 'thumbnailHeightUnits' ],
			];
			if ( $with_sm ) {
				$arr[]	 = [ __( 'Width (For Other Posts)' ), 'thumbnailsmMaxWidth', 'thumbnailsmMaxWidthUnits' ];
				$arr[]	 = [ __( 'Height (For Other Posts)' ), 'thumbnailsmHeight', 'thumbnailsmHeightUnits' ];
			}

			if ( $key_only ) {
				return array_merge( array_column( $arr, 1 ), array_column( $arr, 2 ) );
			}
			return $arr;
		}

		static function filter_thumbnail_position_depend( $args ) {
			return array( $args, array( 'view-type', [ 'list1', 'onebig1' ] ) );
		}

		static function filter_layout_format_depend( $args ) {
			$args = array_keys( PT_CV_Values::hybrid_layouts() );
			return $args;
		}

		static function filter_dargs_hybrid( $args ) {
			if ( ContentViews_Block::is_hybrid() ) {
				// show/hide readmore for other posts
				$enable_readmore_main	 = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'field-excerpt-readmore' );
				$enable_readmore		 = $enable_readmore_main ? PT_CV_Functions::setting_value( PT_CV_PREFIX . 'show-field-readmore-Others' ) : false;
				$enable_content			 = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'show-field-content-Others' );
				if ( !$enable_readmore ) {
					unset( $args[ 'field-settings' ][ 'content' ][ 'readmore' ] );
				} else {
					$args[ 'field-settings' ][ 'content' ][ 'readmore' ] = 'yes';
					if ( !$enable_content ) {
						$args[ 'fields' ][]									 = 'content';
						$args[ 'field-settings' ][ 'content' ][ 'show' ]	 = 'excerpt';
						$args[ 'field-settings' ][ 'content' ][ 'length' ]	 = '0';
					}
				}

				// modify meta fields of other posts
				if ( isset( $args[ 'field-settings' ][ 'meta-fields' ] ) ) {
					foreach ( $args[ 'field-settings' ][ 'meta-fields' ] as $field => $val ) {
						if ( !PT_CV_Functions::setting_value( PT_CV_PREFIX . 'meta-fields-' . $field ) ) {
							unset( $args[ 'field-settings' ][ 'meta-fields' ] [ $field ] );
						}
					}
				}
			}

			return $args;
		}

		public static function filter_field_href_class( $args, $oargs ) {
			if ( !empty( $args[ 1 ] ) ) {
				if ( strpos( $args[ 1 ], PT_CV_PREFIX . 'href-thumbnail' ) !== false ) {
					// for classic layouts only, when enable effect
					if ( !ContentViews_Block::is_block() && PT_CV_Functions::setting_value( PT_CV_PREFIX . 'thumbnailEffect' ) ) {
						$args[] = PT_CV_PREFIX . 'thumb-wrapper';
					}
				}
			}

			return $args;
		}

	}

}

PT_CV_BlockToView::hybrid_hooks();
