<?php
/**
 * Custom filters/actions
 *
 * @package   PT_Content_Views
 * @author    PT Guy <http://www.contentviewspro.com/>
 * @license   GPL-2.0+
 * @link      http://www.contentviewspro.com/
 * @copyright 2014 PT Guy
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'PT_CV_Hooks' ) ) {

	/**
	 * @name PT_CV_Hooks
	 */
	class PT_CV_Hooks {

		/**
		 * Add custom filters/actions
		 */
		static function init() {
			add_filter( PT_CV_PREFIX_ . 'validate_settings', array( __CLASS__, 'filter_validate_settings' ), 10, 2 );
			add_filter( PT_CV_PREFIX_ . 'item_col_class', array( __CLASS__, 'filter_item_col_class' ), 20, 2 );
			add_filter( PT_CV_PREFIX_ . 'fields_html', array( __CLASS__, 'filter_fields_html' ), 20, 2 );

			if ( apply_filters( PT_CV_PREFIX_ . 'prevent_broken_excerpt', true ) ) {
				add_filter( PT_CV_PREFIX_ . 'before_trim_words', array( __CLASS__, 'filter_before_trim_words' ) );
				add_filter( PT_CV_PREFIX_ . 'after_trim_words', array( __CLASS__, 'filter_after_trim_words' ) );
			}

			/**
			 * @since 1.7.5
			 * able to disable responsive image of WordPress 4.4
			 */
			add_filter( 'wp_get_attachment_image_attributes', array( __CLASS__, 'filter_disable_wp_responsive_image' ), 1000, 3 );

			// Do action
			add_action( PT_CV_PREFIX_ . 'before_process_item', array( __CLASS__, 'action_before_process_item' ) );
			add_action( PT_CV_PREFIX_ . 'after_process_item', array( __CLASS__, 'action_after_process_item' ) );
			add_action( PT_CV_PREFIX_ . 'before_content', array( __CLASS__, 'action_before_content' ) );

			// for Block
			add_filter( PT_CV_PREFIX_ . 'field_meta_prefix_text', array( __CLASS__, 'filter_field_meta_prefix_text' ), 10, 2 );
			add_filter( PT_CV_PREFIX_ . 'field_meta_date_format', array( __CLASS__, 'filter_field_meta_date_format' ), 10, 2 );
			add_filter( PT_CV_PREFIX_ . 'view_class', array( __CLASS__, 'filter_view_class' ) );
			add_filter( PT_CV_PREFIX_ . 'page_class', array( __CLASS__, 'filter_page_class' ) );
			add_filter( PT_CV_PREFIX_ . 'content_item_class', array( __CLASS__, 'filter_content_item_class' ), 10, 2 );
			add_filter( PT_CV_PREFIX_ . 'dargs_others', array( __CLASS__, 'filter_dargs_others' ), 10, 2 );
			add_filter( PT_CV_PREFIX_ . 'fields_html', array( __CLASS__, 'filter_fields_html_overlay' ), 21, 2 );
			add_filter( PT_CV_PREFIX_ . 'block_settings', array( __CLASS__, 'filter_block_settings' ) );
			add_filter( PT_CV_PREFIX_ . 'pagination_data', array( __CLASS__, 'filter_pagination_data' ) );
			// From Pro
			add_filter( PT_CV_PREFIX_ . 'settings_args_offset', array( __CLASS__, 'filter_settings_args_offset' ) );
			add_filter( PT_CV_PREFIX_ . 'field_thumbnail_not_found', array( __CLASS__, 'filter_field_thumbnail_not_found' ), 8, 4 );
			add_filter( PT_CV_PREFIX_ . 'tax_list', array( __CLASS__, 'filter_tax_list' ) );
			add_filter( PT_CV_PREFIX_ . 'field_content_excerpt', array( __CLASS__, 'filter_field_content_excerpt' ), 9, 3 );
			add_filter( PT_CV_PREFIX_ . 'view_settings', array( __CLASS__, 'filter_view_settings' ), 9 );
			add_filter( PT_CV_PREFIX_ . 'field_content_readmore_enable', array( __CLASS__, 'filter_field_content_readmore_enable' ), 9, 2 );
		}

		/**
		 * Validate settings filter
		 *
		 * @param string $errors The error message
		 * @param array  $args  The Query parameters array
		 */
		public static function filter_validate_settings( $errors, $args ) {
			$dargs		 = PT_CV_Functions::get_global_variable( 'dargs' );
			$messages	 = array(
				'field'	 => array(
					'select' => __( 'Please select an option in', 'content-views-query-and-display-post-page' ) . ' : ',
					'text'	 => __( 'Please set value in', 'content-views-query-and-display-post-page' ) . ' : ',
				),
				'tab'	 => array(
					'filter'	 => __( 'Filter Settings', 'content-views-query-and-display-post-page' ),
					'display'	 => __( 'Display Settings', 'content-views-query-and-display-post-page' ),
				),
			);

			// Post type
			if ( empty( $args[ 'post_type' ] ) ) {
				$errors[] = $messages[ 'field' ][ 'select' ] . $messages[ 'tab' ][ 'filter' ] . ' > ' . __( 'Content type', 'content-views-query-and-display-post-page' );
			}

			// View type
			if ( empty( $dargs[ 'view-type' ] ) ) {
				$errors[] = $messages[ 'field' ][ 'select' ] . $messages[ 'tab' ][ 'display' ] . ' > ' . __( 'Layout', 'content-views-query-and-display-post-page' );
			}

			// Layout format
			if ( empty( $dargs[ 'layout-format' ] ) ) {
				$errors[] = $messages[ 'field' ][ 'select' ] . $messages[ 'tab' ][ 'display' ] . ' > ' . __( 'Format', 'content-views-query-and-display-post-page' );
			}

			// Field settings
			if ( !isset( $dargs[ 'fields' ] ) ) {
				$errors[] = $messages[ 'field' ][ 'select' ] . $messages[ 'tab' ][ 'display' ] . ' > ' . __( 'Fields settings', 'content-views-query-and-display-post-page' );
			}

			// Item per page
			if ( isset( $dargs[ 'pagination-settings' ] ) ) {
				if ( empty( $dargs[ 'pagination-settings' ][ 'items-per-page' ] ) ) {
					$errors[] = $messages[ 'field' ][ 'text' ] . $messages[ 'tab' ][ 'display' ] . ' > ' . __( 'Pagination', 'content-views-query-and-display-post-page' ) . ' > ' . __( 'Items per page', 'content-views-query-and-display-post-page' );
				}
			}

			if ( !empty( $dargs[ 'view-type' ] ) ) {
				switch ( $dargs[ 'view-type' ] ) {
					case 'grid':
						if ( empty( $dargs[ 'number-columns' ] ) ) {
							$errors[] = $messages[ 'field' ][ 'text' ] . $messages[ 'tab' ][ 'display' ] . ' > ' . __( 'Layout', 'content-views-query-and-display-post-page' ) . ' > ' . __( 'Items per row', 'content-views-query-and-display-post-page' );
						}
						break;
				}
			}

			return array_filter( $errors );
		}

		/**
		 * Filter span with
		 * @since 1.8.5
		 *
		 * @param array $args
		 * @param int $span_width
		 *
		 * @return array
		 */
		public static function filter_item_col_class( $args, $span_width ) {
			if ( PT_CV_Functions::get_global_variable( 'view_type' ) === 'grid' ) {
				$tablet_col	 = (int) PT_CV_Functions::setting_value( PT_CV_PREFIX . 'resp-tablet-number-columns' );
				$mobile_col	 = (int) PT_CV_Functions::setting_value( PT_CV_PREFIX . 'resp-number-columns' );

				$sm_class	 = 'col-sm-' . (int) ( 12 / ($tablet_col ? $tablet_col : 2) );
				$xs_class	 = 'col-xs-' . (int) ( 12 / ($mobile_col ? $mobile_col : 1) );

				if ( !in_array( $sm_class, $args ) ) {
					$args[] = $sm_class;
				}

				if ( !in_array( $xs_class, $args ) ) {
					$args[] = $xs_class;
				}
			}

			return $args;
		}

		/**
		 * Do not wrap text around image when show thumbnail on left/right of text
		 *
		 * @since 1.9.9
		 * @param array $args
		 * @param object $post
		 */
		public static function filter_fields_html( $args, $post ) {
			$format	 = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'layout-format' );
			$nowrap	 = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'lf-nowrap' );

			if ( !empty( $args[ 'thumbnail' ] ) && $format === '2-col' && $nowrap ) {
				$exclude_fields = apply_filters( PT_CV_PREFIX_ . '2col_nowrap_fields', array( 'thumbnail' ) );

				if ( PT_CV_Functions::get_global_variable( 'view_type' ) === 'collapsible' ) {
					$exclude_fields[] = 'title';
				}

				$others = array();
				foreach ( $args as $field => $value ) {
					if ( !in_array( $field, $exclude_fields ) ) {
						$others[ $field ] = $value;
						unset( $args[ $field ] );
					}
				}

				if ( $others ) {
					$args[ 'others-wrap' ] = '<div class="' . PT_CV_PREFIX . 'colwrap">' . implode( '', $others ) . '</div>';
				}
			}

			return $args;
		}

		public static function filter_before_trim_words( $content ) {
			if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'field-excerpt-allow_html' ) ) {
				global $cv_replaced_tags, $cv_replaced_idx;
				# reset for each post
				$cv_replaced_tags	 = array();
				$cv_replaced_idx	 = 0;

				$content = preg_replace_callback( '/<(\/?)[^>]+>/', array( __CLASS__, '_callback_before_trim_words' ), $content );
			}

			return $content;
		}

		/**
		 * Temporary replace HTML tag content by a simple code which doesn't impact trimming word function
		 * @since 1.9.4
		 *
		 * @global type $cv_replaced_tags
		 * @global type $cv_replaced_idx
		 * @param type $matches
		 * @return string
		 */
		public static function _callback_before_trim_words( $matches ) {
			global $cv_replaced_tags, $cv_replaced_idx;

			$return = $matches;
			if ( !empty( $matches[ 0 ] ) ) {
				$cv_replaced_tags[ ++$cv_replaced_idx ]	 = $matches[ 0 ];
				$return									 = "@$cv_replaced_idx@";
			}

			return $return;
		}

		public static function filter_after_trim_words( $content ) {
			if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'field-excerpt-allow_html' ) ) {
				$content = preg_replace_callback( '/@(\d+)@/', array( __CLASS__, '_callback_after_trim_words' ), $content );
			}

			return $content;
		}

		/**
		 * Revert HTML tag content
		 * @since 1.9.4
		 *
		 * @global type $cv_replaced_tags
		 * @param type $matches
		 * @return \type
		 */
		public static function _callback_after_trim_words( $matches ) {
			global $cv_replaced_tags;

			$return = $matches;
			if ( !empty( $matches[ 1 ] ) && isset( $cv_replaced_tags[ (int) $matches[ 1 ] ] ) ) {
				$return = $cv_replaced_tags[ (int) $matches[ 1 ] ];
			}

			return $return;
		}

		// Disable WP 4.4 responsive image
		public static function filter_disable_wp_responsive_image( $args, $attachment = null, $size = null ) {
			if ( PT_CV_Html::is_responsive_image_disabled() ) {
				if ( isset( $args[ 'sizes' ] ) )
					unset( $args[ 'sizes' ] );
				if ( isset( $args[ 'srcset' ] ) )
					unset( $args[ 'srcset' ] );
			}

			return $args;
		}

		public static function action_before_process_item() {
			// Disable View Shortcode in child page
			PT_CV_Functions::disable_view_shortcode();
		}

		public static function action_after_process_item() {
			// Enable View Shortcode again
			PT_CV_Functions::disable_view_shortcode( 'recovery' );
		}

		/**
		 * Issue: shortcode is visible in pagination, preview
		 * Solution: Backup shortcode tag in live page, to use for preview, pagination request
		 *
		 * @since 1.9.3
		 */
		public static function action_before_content() {
			if ( ContentViews_Block::is_pure_block() ) {
				return;
			}

			global $shortcode_tags, $cv_shortcode_tags_backup;

			if ( !$cv_shortcode_tags_backup ) {
				$trans_key = 'cv_shortcode_tags_193';
				if ( !defined( 'PT_CV_DOING_PAGINATION' ) && !defined( 'PT_CV_DOING_PREVIEW' ) ) {
					$tagnames					 = array_keys( $shortcode_tags );
					$cv_shortcode_tags_backup	 = join( '|', array_map( 'preg_quote', $tagnames ) );
					set_transient( $trans_key, $cv_shortcode_tags_backup, DAY_IN_SECONDS );
				} else {
					$cv_shortcode_tags_backup = get_transient( $trans_key );
				}
			}
		}

		// @since Block
		public static function filter_field_meta_prefix_text( $args, $meta_field ) {
			$use_icon = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'metaIcon' );

			// Use Icon
			if ( $use_icon ) {
				$class = '';

				switch ( $meta_field ) {
					case 'author':
						$class	 = 'user';
						break;
					case 'date':
						$class	 = 'calendar';
						break;
					case 'terms':
						$class	 = 'folder-open';
						break;
					case 'comment':
						$class	 = 'comment';
						break;
				}

				$args = sprintf( '<span class="glyphicon glyphicon-%s"></span>', $class );
			}

			if ( $meta_field === 'author' ) {
				if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'authorAvatar' ) ) {
					$args = '';
				}
			}

			return $args;
		}

		public static function filter_field_meta_date_format( $args, $post ) {
			$var = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'dateFormat' );
			if ( !empty( $var ) ) {
				if ( $var === 'custom' ) {
					$var = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'dateFormatCustom' );
				}
				$args = $var;
			}
			return $args;
		}


		public static function filter_view_class( $args ) {
			$view_type = PT_CV_Functions::get_global_variable( 'view_type' );


			if ( ContentViews_Block::is_block() ) {
				$args[] = 'iscvblock';

				if ( ContentViews_Block::is_hybrid() ) {
					$args[] = 'iscvhybrid';
				} else {
					$args[] = 'iscvreal';
				}
			}

			if ( strpos( $view_type, 'onebig' ) !== false ) {
				$args[]	 = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'onePosition' );
				$args[]	 = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'swapPosition' ) ? 'swap-position' : '';
			}

			if ( $view_type === 'overlaygrid' ) {
				if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'overOnHover' ) && PT_CV_Functions::setting_value( PT_CV_PREFIX . 'overlaid' ) ) {
					$args[] = PT_CV_PREFIX . 'onhover';
				}
				if ( ! PT_CV_Functions::setting_value( PT_CV_PREFIX . 'overlaid' ) ) {
					$args[] = PT_CV_PREFIX . 'nooverlay';
				}
				if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'overlayClickable' ) ) {
					$args[] = PT_CV_PREFIX . 'clickable';
				}
			}

			if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'pagingNoScroll' ) ) {
				$args[] = 'paging-noscroll';
			}

			$args[] = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'blockName' );

			$args[] = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'whichLayout' );

			$args[] = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'thumbnailEffect' );

			return $args;
		}

		public static function filter_page_class( $args ) {
			$npf = PT_CV_Functions::get_global_variable( 'no_post_found' );

			if ( $npf ) {
				$args .= ' cv-npf';
			}

			return $args;
		}

		public static function filter_content_item_class( $args, $post_id ){
			$mainp = PT_CV_Functions::get_global_variable( 'main_posts' );
			if ( is_array( $mainp ) && in_array( $post_id, $mainp ) ) {
				$args[] = 'cv-main-post';
			}
			return $args;
		}

		public static function filter_dargs_others( $args, $post_idx ) {
			global $pt_cv_glb, $pt_cv_id, $post;
			$view_type	 = PT_CV_Functions::get_global_variable( 'view_type' );
			$block_name	 = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'blockName' );
			$layout		 = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'whichLayout' );
			if ( ($block_name === 'overlay5' && $layout === 'layout3') || ($block_name === 'overlay7' && $layout === 'layout4') ) {
				$for_which = ($post_idx !== 1);
			} else if ( ($block_name === 'overlay5' && $layout === 'layout4') || ($block_name === 'overlay7' && $layout === 'layout2') ) {
				$for_which = ($post_idx !== 2);
			} else if ( $block_name === 'overlay8' && ($layout === 'layout1' || $layout === 'layout4') ) {
				$for_which = $post_idx > 1;
			} else if ( $block_name === 'overlay3' && $layout === 'layout3' ) {
				$for_which = $post_idx > 2;
			} else if ( $block_name === 'overlay4' && ($layout === 'layout4' || $layout === 'layout5') ) {
				$for_which = ($layout === 'layout4') ? $post_idx > 1 : $post_idx > 2;
			} else if ( $block_name === 'overlay8' && $layout === 'layout2' ) {
				$for_which = $post_idx !== 0 && $post_idx !== 5;
			} else if ( $block_name === 'overlay8' && $layout === 'layout3' ) {
				$for_which = $post_idx !== 0 && $post_idx !== 3;
			} else {
				$for_which = $post_idx > 0;
			}

			if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'hasOne' ) && $for_which ) {
				add_filter( PT_CV_PREFIX_ . 'field_title_class', array( __CLASS__, 'others_field_title_class' ) );

				if ( $view_type === 'onebig' && !( $block_name === 'onebig2' && $layout === 'layout4' ) ) {
					$args[ 'layout-format' ]													 = '2-col';
					$pt_cv_glb[ $pt_cv_id ][ 'view_settings' ][ PT_CV_PREFIX . 'layout-format' ] = $args[ 'layout-format' ];
				}

				foreach ( $args[ 'fields' ] as $idx => $field ) {
					if ( $field !== 'title' && strpos( $field, 'woo' ) === false && !PT_CV_Functions::setting_value( PT_CV_PREFIX . "show-field-$field-Others" ) ) {
						unset( $args[ 'fields' ][ $idx ] );
					}
				}

				$fields_to_show = $args[ 'fields' ];
				PT_CV_Functions::set_global_variable( 'fields_others', $fields_to_show );

				if ( in_array( 'thumbnail', $fields_to_show ) ) {
					$args[ 'field-settings' ][ 'thumbnail' ][ 'size' ]		 = PT_CV_Functions::setting_value( PT_CV_PREFIX . "imgSizeOthers" );
					$args[ 'field-settings' ][ 'thumbnail' ][ 'position' ]	 = PT_CV_Functions::setting_value( PT_CV_PREFIX . "thumbPositionOthers" );
					$args[ 'field-settings' ][ 'thumbnail' ][ 'extra_class' ] = PT_CV_PREFIX . 'thumbnailsm';
				}

				if ( in_array( 'meta-fields', $fields_to_show ) ) {
					unset( $args[ 'field-settings' ][ 'meta-fields' ] );

					$metaWhich	 = PT_CV_Functions::setting_value( PT_CV_PREFIX . "metaWhichOthers" );
					$meta		 = ContentViews_Block::values_from_block( array( 'tmp1' => $metaWhich ), 'tmp1', array() );
					foreach ( $meta as $field ) {
						$args[ 'field-settings' ][ 'meta-fields' ][ $field ] = 'yes';
					}
				}

				$excerp_length = PT_CV_Functions::setting_value( PT_CV_PREFIX . "excerptLengthOthers" );
				if ( $excerp_length !== NULL ) {
					$args[ 'field-settings' ][ 'content' ][ 'length' ] = $excerp_length;
				}

				$args = apply_filters( PT_CV_PREFIX_ . 'dargs_hybrid', $args );
			} else {
				remove_filter( PT_CV_PREFIX_ . 'field_title_class', array( __CLASS__, 'others_field_title_class' ) );

				if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'hasOne' ) ) {
					if ( !isset( $pt_cv_glb[ $pt_cv_id ][ 'main_posts' ] ) ) {
						$pt_cv_glb[ $pt_cv_id ][ 'main_posts' ] = [];
					}
					$pt_cv_glb[ $pt_cv_id ][ 'main_posts' ][] = $post->ID;
				}
			}

			return $args;
		}

		public static function others_field_title_class( $args ) {
			$args .= ' ' . PT_CV_PREFIX . 'titlesm';
			return $args;
		}

		public static function filter_fields_html_overlay( $args, $post ) {
			$view_type	 = PT_CV_Functions::get_global_variable( 'view_type' );
			$layout		 = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'whichLayout' );
			$is_overlay	 = ($view_type === 'overlaygrid');
			if ( $is_overlay || ($view_type === 'blockgrid' && $layout !== 'layout1') ) {
				if ( !empty( $args[ 'thumbnail' ] ) ) {
					$exclude_fields = apply_filters( PT_CV_PREFIX_ . 'overlay_exclude', array( 'thumbnail' ) );

					$others = array();
					foreach ( $args as $field => $value ) {
						if ( !in_array( $field, $exclude_fields ) ) {
							$others[ $field ] = $value;
							unset( $args[ $field ] );
						}
					}

					if ( $others ) {
						$args[ 'overlay-wrap' ] = '<div class="' . PT_CV_PREFIX . ($is_overlay ? 'overlay-wrapper' : 'remain-wrapper') . '">' . implode( '', $others ) . '</div>';
					}
				}
			}

			return $args;
		}

		public static function filter_block_settings( $attributes ) {
			$layout = $attributes[ 'whichLayout' ];


			if ( $attributes[ 'blockName' ] === 'overlay7' ) {
				if ( $layout === 'layout5' ) {
					$attributes[ 'postsPerPage' ] = 6;
				}
				if ( $layout === 'layout1' || $layout === 'layout2' ) {
					$attributes[ 'postsPerPage' ] = 7;
				}
			}

			if ( $attributes[ 'blockName' ] === 'overlay8' ) {
				if ( $layout === 'layout4' ) {
					$attributes[ 'postsPerPage' ] = 5;
				} else {
					$attributes[ 'postsPerPage' ] = 6;
				}
			}

			if ( $attributes[ 'blockName' ] === 'onebig1' && $layout === 'layout1' ) {
				$attributes[ 'showThumbnailOthers' ] = false;
			}

			if ( $attributes[ 'blockName' ] === 'list1' && $layout === 'layout2' ) {
				$attributes[ 'zigzag' ] = 'yes';
			}

			if ( $attributes[ 'viewType' ] === 'scrollable' ) {
				$columns						 = (array) $attributes[ 'columns' ];
				$rows							 = $attributes[ 'rowNum' ];
				$attributes[ 'postsPerPage' ]	 = (int) $columns[ 'md' ] * (int) $rows * (int) $attributes[ 'slideNum' ];

				if ( !$attributes[ 'scrollAuto' ] ) {
					$attributes[ 'scrollInterval' ] = 0;
				}
			}

			return $attributes;
		}

		// Add extra data to pagination for block
		public static function filter_pagination_data( $args ) {
			$isblock = ContentViews_Block::is_pure_block();
			$postid	 = isset( $GLOBALS[ 'cv_current_post' ] ) ? $GLOBALS[ 'cv_current_post' ] : '';
			$args	 = sprintf( 'data-isblock="%s" data-postid="%s"', esc_attr( $isblock ), esc_attr( $postid ) );
			return $args;
		}

		public static function filter_post_types_list( $args ) {
			if ( !get_option( 'pt_cv_version_pro' ) ) {
				$args[ 'attachment' ]	 = __( 'Media' );
				$args[ 'any' ]			 = __( 'All / Multi post types', 'content-views-query-and-display-post-page' );
			}

			unset( $args[ 'pt_view' ] );

			return $args;
		}

		public static function filter_pagination_styles( $args ) {
			if ( !get_option( 'pt_cv_version_pro' ) ) {
				$args[ 'infinite' ]	 = __( 'Infinite scrolling', 'content-views-query-and-display-post-page' );
				$args[ 'loadmore' ]	 = __( 'Load more button', 'content-views-query-and-display-post-page' );
			}
			return $args;
		}

		public static function filter_regular_orderby( $args ) {
			$args = array_merge( $args, ContentViews_Block_Common::pro_sortby() );
			return $args;
		}

		public static function filter_settings_args_offset( $offset ) {
			if ( !get_option( 'pt_cv_version_pro' ) ) {
				$offset_option = (int) PT_CV_Functions::setting_value( PT_CV_PREFIX . 'offset', null, 0 );
				$offset_option = ( $offset_option < 0 ) ? 0 : $offset_option;
				$offset        += $offset_option;
			}

			return $offset;
        }

		public static function filter_field_thumbnail_not_found( $args, $post, $dimensions, $gargs ) {
			if ( !get_option( 'pt_cv_version_pro' ) && PT_CV_Functions::setting_value( PT_CV_PREFIX . 'defaultImg' ) ) {
				$dimension_ready = $dimensions && !empty( $dimensions[ 0 ] ) && !empty( $dimensions[ 1 ] );				
				$width	 = $dimension_ready ? esc_attr( $dimensions[ 0 ] ) : '';
				$attr	 = array(
					'src'	 => apply_filters( PT_CV_PREFIX_ . 'default_image', plugins_url( 'public/assets/images/default_image.png', PT_CV_FILE ) ),
					'class'	 => $gargs[ 'class' ] . ' cv-default-img',
					'alt'	 => !empty( $post->cvp_img_alt ) ? esc_attr( $post->cvp_img_alt ) : esc_attr( $post->post_title ),
					'title'	 => !empty( $post->cvp_img_title ) ? esc_attr( $post->cvp_img_title ) : '',
				);
				$args = PT_CV_Html::image_output( $width, 0, $attr );
			}
			return $args;
		}

		/** Add Woocommerce hidden taxonomies to the list */
        public static function filter_tax_list( $args ) {
            if ( taxonomy_exists( 'product_visibility' ) ) {
                $args[ 'product_visibility' ] = __( 'Visibility', 'content-views-pro' );
            }

			// Get Woocommerce attributes taxonomies
			if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
				$attributes = wc_get_attribute_taxonomies();
				if ( !empty( $attributes ) ) {
					// Don't include all if too many attributes, that cause slow/unable saving
					if ( count( $attributes ) > 30 ) {
						$attributes = array_slice( $attributes, 0, 30, true );
					}

					foreach ( $attributes as $tax ) {
						$tslug = wc_attribute_taxonomy_name( $tax->attribute_name );
						if ( !array_key_exists( $tslug, $args ) ) {
							$args[ $tslug ] = $tax->attribute_name;
						}

					}
				}
			}

			return $args;
        }

		/**
         * Filter post excerpt
         * @return string
         */
        public static function filter_field_content_excerpt( $args, $fargs, $post ) {
            // Prevent recursive call
            if ( empty( $fargs ) ) {
                return $args;
            }

            // Get manual excerpt
            if ( !empty( $fargs[ 'content' ][ 'manual' ] ) && !empty( $post->post_excerpt ) ) {
                $args = $post->post_excerpt;
            }

            return $args;
        }

		 /**
         * Filter View settings, for compatible with older versions
         */
		static function filter_view_settings( $args ) {
			$view_version = !isset( $args[ PT_CV_PREFIX . 'version' ] ) ? 0 : ltrim( $args[ PT_CV_PREFIX . 'version' ], 'pro-' );

			if ( strpos( $view_version, 'free' ) !== false ) {
				$view_version = ltrim( $args[ PT_CV_PREFIX . 'version' ], 'free-' );
				if ( version_compare( $view_version, '3.0.2', '<=' ) ) {
					$args[ PT_CV_PREFIX . 'field-excerpt-readmore' ] = 'yes';
				}
			}

			return $args;
		}

		/**
         * Enable/Disable Read more button
         *
         * @param string $args  The readmore text
         * @param array  $fargs The settings of Content
         */
        public static function filter_field_content_readmore_enable( $args, $fargs ) {
            // not empty => true => show
            $args = !empty( $fargs[ 'readmore' ] );

            return $args;
        }

	}

}
