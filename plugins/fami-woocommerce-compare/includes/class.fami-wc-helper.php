<?php
/**
 *
 * @author  Fami Themes
 * @package Fami WooCommerce Compare
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Fami_Woocompare_Helper' ) ) {
	
	/**
	 *
	 * @since 1.0.0
	 */
	class Fami_Woocompare_Helper {
		
		public static function get_all_settings() {
			$all_settings_deault = array(
				'show_in_single_product'         => 'yes',
				'show_in_products_list'          => 'yes',
				'show_compare_panel'             => 'yes',
				'single_product_hook'            => 'woocommerce_single_product_summary',
				'products_loop_hook'             => 'woocommerce_after_shop_loop_item',
				'compare_page'                   => 0,
				'panel_img_size_w'               => 150,
				'panel_img_size_h'               => 150,
				'compare_img_size_w'             => 180,
				'compare_img_size_h'             => 200,
				'compare_fields_attrs'           => implode( ',', self::default_selected_compare_fields() ),
				'all_compare_fields_attrs_order' => '',
				'compare_slider'                 => 'owl', // owl|slick
				'enqueue_owl_js'                 => 'yes',
				'enqueue_slick_js'               => 'yes',
			);
			$all_settings        = get_option( 'fami_wccp_all_settings', array() );
			
			return wp_parse_args( $all_settings, $all_settings_deault );
		}
		
		public static function get_selected_compare_fields() {
			$all_settings = self::get_all_settings();
			
			return explode( ',', $all_settings['compare_fields_attrs'] );
		}
		
		public static function get_selected_compare_fields_with_texts() {
			$selected_fields       = self::get_selected_compare_fields();
			$all_fields_with_texts = self::compare_fields();
			
			$return_args = array();
			if ( ! empty( $selected_fields ) ) {
				foreach ( $selected_fields as $selected_field ) {
					if ( isset( $all_fields_with_texts[ $selected_field ] ) ) {
						$return_args[ $selected_field ] = $all_fields_with_texts[ $selected_field ];
					} else {
						if ( taxonomy_exists( $selected_field ) ) {
							$return_args[ $selected_field ] = wc_attribute_label( $selected_field );
						}
					}
				}
			}
			
			return $return_args;
		}
		
		/**
		 * Set the image size used in the comparison table
		 *
		 * @since 1.0.0
		 */
		public static function set_image_size() {
			$all_settings = self::get_all_settings();
			
			$size = array(
				'width'  => $all_settings['compare_img_size_w'],
				'height' => $all_settings['compare_img_size_h'],
				'crop'   => true
			);
			
			$size['crop'] = isset( $size['crop'] ) ? true : false;
			add_image_size( 'fami_wccp_image', $size['width'], $size['height'], $size['crop'] );
		}
		
		/**
		 * @param string $size_name compare|panel
		 *
		 * @return array
		 */
		public static function get_image_size( $size_name = 'compare' ) {
			$all_settings = self::get_all_settings();
			$default_size = array( 'width' => 180, 'height' => 200 );
			$size         = $default_size;
			if ( $size_name == 'compare' ) {
				$size['width']  = $all_settings['compare_img_size_w'];
				$size['height'] = $all_settings['compare_img_size_h'];
			}
			if ( $size_name == 'panel' ) {
				$size['width']  = $all_settings['panel_img_size_w'];
				$size['height'] = $all_settings['panel_img_size_h'];
			}
			
			return $size;
		}
		
		public static function default_selected_compare_fields() {
			$default_fields = array(
				'image',
				'title',
				'price',
				'add-to-cart'
			);
			
			return $default_fields;
		}
		
		/*
		 * The list of standard fields
		 *
		 * @since 1.0.0
		 * @access public
		 * @param Boolean $with_attr
		 */
		public static function compare_fields() {
			$all_settings                   = self::get_all_settings();
			$all_compare_fields_attrs_order = $all_settings['all_compare_fields_attrs_order'];
			
			$fields = array(
				'image'       => esc_html__( 'Image', 'fami-woocommerce-compare' ),
				'title'       => esc_html__( 'Title', 'fami-woocommerce-compare' ),
				'price'       => esc_html__( 'Price', 'fami-woocommerce-compare' ),
				'add-to-cart' => esc_html__( 'Add to cart', 'fami-woocommerce-compare' ),
				'description' => esc_html__( 'Description', 'fami-woocommerce-compare' ),
				'sku'         => esc_html__( 'Sku', 'fami-woocommerce-compare' ),
				'stock'       => esc_html__( 'Availability', 'fami-woocommerce-compare' ),
				'weight'      => esc_html__( 'Weight', 'fami-woocommerce-compare' ),
				'dimensions'  => esc_html__( 'Dimensions', 'fami-woocommerce-compare' )
			);
			
			$fields = array_merge( $fields, Fami_Woocompare_Helper::attribute_taxonomies() );
			
			// Order fields
			if ( $all_compare_fields_attrs_order != '' ) {
				$all_compare_fields_attrs_order = explode( ',', $all_compare_fields_attrs_order );
				$fields_order_tmp               = array();
				foreach ( $all_compare_fields_attrs_order as $field_key ) {
					if ( isset( $fields[ $field_key ] ) ) {
						$fields_order_tmp[ $field_key ] = $fields[ $field_key ];
					}
				}
				$fields = array_merge( $fields_order_tmp, array_diff( $fields, $fields_order_tmp ) );
			}
			
			return $fields;
		}
		
		/**
		 * @param string $page_name - compare
		 *
		 * @return int
		 */
		public static function get_page( $page_name = '' ) {
			$page_id = 0;
			if ( trim( $page_name ) != 'compare' ) {
				return $page_id;
			}
			
			$all_settings = self::get_all_settings();
			
			$page_id = isset( $all_settings['compare_page'] ) ? intval( $all_settings['compare_page'] ) : 0;
			
			return $page_id;
		}
		
		public static function get_page_link( $page_name = '' ) {
			$page_id = self::get_page( $page_name );
			$url     = '';
			if ( $page_id ) {
				$url = get_permalink( $page_id );
			}
			
			return $url;
		}
		
		public static function all_single_product_hooks_select_html( $selected = 'woocommerce_single_product_summary', $class = '', $name = '', $id = '', $echo = true ) {
			$all_single_hooks = array(
				'woocommerce_single_product_summary'       => 'woocommerce_single_product_summary',
				'woocommerce_before_add_to_cart_form'      => 'woocommerce_before_add_to_cart_form',
				'woocommerce_before_add_to_cart_button'    => 'woocommerce_before_add_to_cart_button',
				'woocommerce_after_add_to_cart_button'     => 'woocommerce_after_add_to_cart_button',
				'woocommerce_after_add_to_cart_form'       => 'woocommerce_after_add_to_cart_form',
				'woocommerce_product_meta_end'             => 'woocommerce_product_meta_end',
				'woocommerce_share'                        => 'woocommerce_share',
				'woocommerce_after_single_product_summary' => 'woocommerce_after_single_product_summary'
			);
			
			$html = '<option value="fami_wccp_single_product">' . esc_html__( 'Custom hook (fami_wccp_single_product)', 'fami-woocommerce-compare' ) . '</option>';
			
			if ( ! empty( $all_single_hooks ) ) {
				foreach ( $all_single_hooks as $hook ) {
					$html .= '<option ' . selected( $hook == $selected, true, false ) . ' value="' . esc_attr( $hook ) . '">' . esc_html( $hook ) . '</option>';
				}
			}
			
			$html_atts = '';
			if ( trim( $id ) != '' ) {
				$html_atts .= 'id="' . esc_attr( $id ) . '" ';
			}
			if ( trim( $name ) != '' ) {
				$html_atts .= 'name="' . esc_attr( $name ) . '" ';
			}
			$html_atts .= 'class="fami-wccp-select fami-wccp-single-product-hook-select ' . esc_attr( $class ) . '" ';
			
			$html = '<select ' . $html_atts . '>' . $html . '</select>';
			
			if ( $echo ) {
				echo $html;
			}
			
			return $html;
			
		}
		
		public static function all_products_list_hooks_select_html( $selected = 'woocommerce_after_shop_loop_item', $class = '', $name = '', $id = '', $echo = true ) {
			$all_single_hooks = array(
				'woocommerce_before_shop_loop_item'       => 'woocommerce_before_shop_loop_item',
				'woocommerce_before_shop_loop_item_title' => 'woocommerce_before_shop_loop_item_title',
				'woocommerce_shop_loop_item_title'        => 'woocommerce_shop_loop_item_title',
				'woocommerce_after_shop_loop_item_title'  => 'woocommerce_after_shop_loop_item_title',
				'woocommerce_after_shop_loop_item'        => 'woocommerce_after_shop_loop_item'
			);
			
			$html = '<option value="fami_wccp_shop_loop">' . esc_html__( 'Custom hook (fami_wccp_shop_loop)', 'fami-woocommerce-compare' ) . '</option>';
			
			if ( ! empty( $all_single_hooks ) ) {
				foreach ( $all_single_hooks as $hook ) {
					$html .= '<option ' . selected( $hook == $selected, true, false ) . ' value="' . esc_attr( $hook ) . '">' . esc_html( $hook ) . '</option>';
				}
			}
			
			$html_atts = '';
			if ( trim( $id ) != '' ) {
				$html_atts .= 'id="' . esc_attr( $id ) . '" ';
			}
			if ( trim( $name ) != '' ) {
				$html_atts .= 'name="' . esc_attr( $name ) . '" ';
			}
			$html_atts .= 'class="fami-wccp-select fami-wccp-products-list-hook-select ' . esc_attr( $class ) . '" ';
			
			$html = '<select ' . $html_atts . '>' . $html . '</select>';
			
			if ( $echo ) {
				echo $html;
			}
			
			return $html;
			
		}
		
		/**
		 * Display/Return page select html, exclude blog page, frontpage, shop page, cart, checkout, my account page, terms page
		 *
		 * @param string $selected
		 * @param string $class
		 * @param string $name
		 * @param string $id
		 * @param array  $exception
		 * @param bool   $echo
		 *
		 * @return string
		 */
		public static function all_pages_select_html( $selected = '', $class = '', $name = '', $id = '', $exception = array(), $echo = true ) {
			if ( is_numeric( $exception ) || is_string( $exception ) ) {
				$exception = array( $exception );
			}
			
			$pages_exclude           = $exception;
			$blog_page_id            = get_option( 'page_for_posts', 0 ); // Blog page
			$front_page_id           = get_option( 'page_on_front', 0 ); // Front page
			$page_for_privacy_policy = get_option( 'wp_page_for_privacy_policy', 0 );
			
			$pages_exclude[] = $blog_page_id;
			$pages_exclude[] = $front_page_id;
			$pages_exclude[] = $page_for_privacy_policy;
			if ( class_exists( 'WooCommerce' ) ) {
				$myaccount_page_id = wc_get_page_id( 'myaccount' );
				$shop_page_id      = wc_get_page_id( 'shop' );
				$cart_page_id      = wc_get_page_id( 'cart' );
				$checkout_page_id  = wc_get_page_id( 'checkout' );
				$terms_page_id     = wc_get_page_id( 'terms' );
				
				$pages_exclude[] = $myaccount_page_id;
				$pages_exclude[] = $shop_page_id;
				$pages_exclude[] = $cart_page_id;
				$pages_exclude[] = $checkout_page_id;
				$pages_exclude[] = $terms_page_id;
			}
			
			$pages_args = array(
				'sort_order'   => 'asc',
				'sort_column'  => 'post_title',
				'hierarchical' => 1,
				'exclude'      => $pages_exclude,
				'include'      => '',
				'meta_key'     => '',
				'meta_value'   => '',
				'authors'      => '',
				'child_of'     => 0,
				'parent'       => - 1,
				'exclude_tree' => '',
				'number'       => '',
				'offset'       => 0,
				'post_type'    => 'page',
				'post_status'  => 'publish'
			);
			$all_pages  = get_pages( $pages_args );
			
			$html = '<option value="">' . esc_html__( 'Select Page', 'fami-woocommerce-compare' ) . '</option>';
			
			if ( ! empty( $all_pages ) ) {
				foreach ( $all_pages as $page ) {
					$html .= '<option ' . selected( true, $page->ID == $selected, false ) . ' value="' . esc_attr( $page->ID ) . '">' . esc_html( $page->post_title ) . '</option>';
				}
			}
			
			$html_atts = '';
			if ( trim( $id ) != '' ) {
				$html_atts .= 'id="' . esc_attr( $id ) . '" ';
			}
			if ( trim( $name ) != '' ) {
				$html_atts .= 'name="' . esc_attr( $name ) . '" ';
			}
			$html_atts .= 'class="fami-wccp-select fami-wccp-page-select ' . esc_attr( $class ) . '" ';
			
			$html = '<select ' . $html_atts . '>' . $html . '</select>';
			
			if ( $echo ) {
				echo $html;
			}
			
			return $html;
			
		}
		
		/*
		 * Get WooCommerce Attribute Taxonomies
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public static function attribute_taxonomies() {
			global $woocommerce;
			
			if ( ! isset( $woocommerce ) ) {
				return array();
			};
			
			$attributes = array();
			
			if ( function_exists( 'wc_get_attribute_taxonomies' ) && function_exists( 'wc_attribute_taxonomy_name' ) ) {
				$attribute_taxonomies = wc_get_attribute_taxonomies();
				if ( empty( $attribute_taxonomies ) ) {
					return array();
				}
				foreach ( $attribute_taxonomies as $attribute ) {
					$tax = wc_attribute_taxonomy_name( $attribute->attribute_name );
					if ( taxonomy_exists( $tax ) ) {
						$attributes[ $tax ] = ucfirst( $attribute->attribute_label );
					}
				}
			} else {
				$attribute_taxonomies = $woocommerce->get_attribute_taxonomies();
				if ( empty( $attribute_taxonomies ) ) {
					return array();
				}
				foreach ( $attribute_taxonomies as $attribute ) {
					$tax = $woocommerce->attribute_taxonomy_name( $attribute->attribute_name );
					if ( taxonomy_exists( $tax ) ) {
						$attributes[ $tax ] = ucfirst( $attribute->attribute_label );
					}
				}
			}
			
			return $attributes;
		}
		
		public static function compare_admin_fields_cb_html() {
			$fields          = self::compare_fields();
			$selected_fields = self::get_selected_compare_fields();
			$html            = '';
			if ( $fields ) {
				foreach ( $fields as $field_key => $field_val ) {
					// $checked = in_array($field_key, $selected_fields) ? 'checked=""'
					$html .= '<li><label><input type="checkbox" ' . checked( in_array( $field_key, $selected_fields ), true, false ) . ' class="compare-field-cb" name="fami_wccp_fields_attrs[]" id="fami_wccp_fields_attrs_' . esc_attr( $field_key ) . '" value="' . esc_attr( $field_key ) . '"> ' . esc_html( $field_val ) . '</label></li>';
				}
				
				$html = '<ul class="fami-wccp-fields-list fami-wccp-fields-attrs-list fami-wccp-sortable">' . $html . '</ul>';
			}
			
			$html .= '<input type="hidden" name="compare_fields_attrs" class="fami-wccp-field fami-wccp-field-hidden" value="' . esc_attr( implode( ',', $selected_fields ) ) . '" />';
			$html .= '<input type="hidden" name="all_compare_fields_attrs_order" class="fami-wccp-field fami-wccp-field-hidden" value="" />';
			
			return apply_filters( 'fami_admin_compare_fields_cb_html', $html, $fields );
		}
		
		public static function template_path() {
			return apply_filters( 'fami_wccp_template_path', 'fami-wccp/' );
		}
		
		public static function get_template_part( $slug, $name = '' ) {
			// Look in yourtheme/fami-wccp/slug-name.php and yourtheme/fami-wccp/slug.php
			$template = locate_template( array(
				                             self::template_path() . "{$slug}-{$name}.php",
				                             self::template_path() . "{$slug}.php"
			                             ) );
			
			$template_path = apply_filters( 'fami_wccp_set_template_path', FAMI_WCP_PATH . '/templates', $template );
			
			// Get default slug-name.php
			if ( ! $template && $name && file_exists( $template_path . "/{$slug}-{$name}.php" ) ) {
				$template = $template_path . "/{$slug}-{$name}.php";
			}
			
			if ( ! $template && file_exists( $template_path . "/{$slug}.php" ) ) {
				$template = $template_path . "/{$slug}.php";
			}
			
			// Allow 3rd party plugin filter template file from their plugin
			$template = apply_filters( 'fami_wccp_get_template_part', $template, $slug, $name );
			
			if ( $template ) {
				include( $template );
			}
			
		}
		
		/**
		 * Get other templates (e.g. product attributes) passing attributes and including the file.
		 *
		 * @access public
		 *
		 * @param mixed  $template_name
		 * @param array  $args          (default: array())
		 * @param string $template_path (default: '')
		 * @param string $default_path  (default: '')
		 *
		 * @return void
		 */
		public static function get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
			if ( $args && is_array( $args ) ) {
				extract( $args );
			}
			
			$located = self::locate_template( $template_name, $template_path, $default_path );
			
			if ( ! file_exists( $located ) ) {
				_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '1.0' );
				
				return;
			}
			
			do_action( 'fami_wccp_before_template_part', $template_name, $template_path, $located, $args );
			
			include( $located );
			
			do_action( 'fami_wccp_after_template_part', $template_name, $template_path, $located, $args );
		}
		
		/**
		 * Locate a template and return the path for inclusion.
		 *
		 * This is the load order:
		 *
		 *      yourtheme       /   $template_path  /   $template_name
		 *      yourtheme       /   $template_name
		 *      $default_path   /   $template_name
		 *
		 * @access public
		 *
		 * @param mixed  $template_name
		 * @param string $template_path (default: '')
		 * @param string $default_path  (default: '')
		 *
		 * @return string
		 */
		public static function locate_template( $template_name, $template_path = '', $default_path = '' ) {
			
			if ( ! $template_path ) {
				$template_path = self::template_path();
			}
			
			if ( ! $default_path ) {
				$default_path = FAMI_WCP_PATH . '/templates/';
			}
			
			// Look within passed path within the theme - this is priority
			$template = locate_template(
				array(
					trailingslashit( $template_path ) . $template_name,
				)
			);
			
			// Get default template
			if ( ! $template ) {
				$template = $default_path . $template_name;
			}
			
			// Return what we found
			return apply_filters( 'fami_wcpc_locate_template', $template, $template_name, $template_path );
		}
		
		/**
		 * No image generator
		 *
		 * @since 1.0
		 *
		 * @param $size : array, image size
		 * @param $echo : bool, echo or return no image url
		 **/
		public static function no_images(
			$size = array(
				'width'  => 500,
				'height' => 500
			), $echo = false, $transparent = false
		) {
			$noimage_dir = FAMI_WCP_PATH . '/assets';
			$noimage_uri = FAMI_WCP_URL . '/assets';
			$suffix      = ( $transparent ) ? '_transparent' : '';
			if ( ! is_array( $size ) || empty( $size ) ):
				$size = array( 'width' => 500, 'height' => 500 );
			endif;
			if ( ! is_numeric( $size['width'] ) && $size['width'] == '' || $size['width'] == null ):
				$size['width'] = 'auto';
			endif;
			if ( ! is_numeric( $size['height'] ) && $size['height'] == '' || $size['height'] == null ):
				$size['height'] = 'auto';
			endif;
			
			if ( file_exists( $noimage_dir . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png' ) ) {
				if ( $echo ) {
					echo esc_url( $noimage_uri . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png' );
				}
				
				return esc_url( $noimage_uri . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png' );
			}
			
			// base image must be exist
			$img_base_fullpath = $noimage_dir . '/images/noimage/no_image' . $suffix . '.png';
			$no_image_src      = $noimage_uri . '/images/noimage/no_image' . $suffix . '.png';
			// Check no image exist or not
			if ( ! file_exists( $noimage_dir . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png' ) && is_writable( $noimage_dir . '/images/noimage/' ) ):
				$no_image = wp_get_image_editor( $img_base_fullpath );
				if ( ! is_wp_error( $no_image ) ):
					$no_image->resize( $size['width'], $size['height'], true );
					$no_image_name = $no_image->generate_filename( $size['width'] . 'x' . $size['height'], $noimage_dir . '/images/noimage/', null );
					$no_image->save( $no_image_name );
				endif;
			endif;
			// Check no image exist after resize
			$noimage_path_exist_after_resize = $noimage_dir . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png';
			if ( file_exists( $noimage_path_exist_after_resize ) ):
				$no_image_src = $noimage_uri . '/images/noimage/no_image' . $suffix . '-' . $size['width'] . 'x' . $size['height'] . '.png';
			endif;
			
			if ( $echo ) {
				echo esc_url( $no_image_src );
			}
			
			return esc_url( $no_image_src );
		}
		
		/**
		 * @param int    $attach_id
		 * @param string $img_url
		 * @param int    $width
		 * @param int    $height
		 * @param bool   $crop
		 * @param bool   $place_hold        Using place hold image if the image does not exist
		 * @param bool   $use_real_img_hold Using real image for holder if the image does not exist
		 * @param string $solid_img_color   Solid placehold image color (not text color). Random color if null
		 *
		 * @since 1.0
		 * @return array
		 */
		public static function resize_image( $attach_id = null, $img_url = null, $width, $height, $crop = false, $place_hold = true, $use_real_img_hold = true, $solid_img_color = null ) {
			$img_on_curent_host = true;
			$remote_img_url     = '';
			
			/*If is singular and has post thumbnail and $attach_id is null, so we get post thumbnail id automatic*/
			if ( is_singular() && ! $attach_id ) {
				if ( has_post_thumbnail() && ! post_password_required() ) {
					$attach_id = get_post_thumbnail_id();
				}
			}
			/*this is an attachment, so we have the ID*/
			$image_src = array();
			if ( $attach_id ) {
				if ( has_image_size( "fami_img_size_{$width}x{$height}" ) ) {
					$image_src = wp_get_attachment_image_src( $attach_id, "fami_img_size_{$width}x{$height}" );
					if ( ! empty( $image_src ) ) {
						
						return array(
							'url'    => $image_src[0],
							'width'  => $image_src[1],
							'height' => $image_src[2]
						);
					}
				} else {
					$image_src = wp_get_attachment_image_src( $attach_id, 'full' );
				}
				$actual_file_path = get_attached_file( $attach_id );
				
				if ( isset( $image_src[0] ) ) {
					$remote_img_url      = $image_src[0];
					$img_parse           = parse_url( $image_src[0] );
					$current_host_domain = sanitize_text_field( $_SERVER['SERVER_NAME'] );
					if ( $current_host_domain != $img_parse['host'] ) {
						$img_on_curent_host = false;
					}
				}
				/*this is not an attachment, let's use the image url*/
			} else if ( $img_url ) {
				$file_path        = str_replace( get_site_url(), get_home_path(), $img_url );
				$actual_file_path = rtrim( $file_path, '/' );
				if ( ! file_exists( $actual_file_path ) ) {
					$file_path        = parse_url( $img_url );
					$actual_file_path = rtrim( ABSPATH, '/' ) . $file_path['path'];
				}
				if ( file_exists( $actual_file_path ) ) {
					$orig_size    = getimagesize( $actual_file_path );
					$image_src[0] = $img_url;
					$image_src[1] = $orig_size[0];
					$image_src[2] = $orig_size[1];
				} else {
					$image_src[0] = '';
					$image_src[1] = 0;
					$image_src[2] = 0;
				}
			}
			if ( ! empty( $actual_file_path ) && file_exists( $actual_file_path ) ) {
				$file_info = pathinfo( $actual_file_path );
				$extension = '.' . $file_info['extension'];
				/*the image path without the extension*/
				$no_ext_path      = $file_info['dirname'] . '/' . $file_info['filename'];
				$cropped_img_path = $no_ext_path . '-' . $width . 'x' . $height . $extension;
				/*checking if the file size is larger than the target size*/
				/*if it is smaller or the same size, stop right here and return*/
				if ( $image_src[1] > $width || $image_src[2] > $height ) {
					/*the file is larger, check if the resized version already exists (for $crop = true but will also work for $crop = false if the sizes match)*/
					if ( file_exists( $cropped_img_path ) ) {
						$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );
						$vt_image        = array(
							'url'    => $cropped_img_url,
							'width'  => $width,
							'height' => $height,
						);
						
						return $vt_image;
					}
					
					if ( $crop == false ) {
						/*calculate the size proportionaly*/
						$proportional_size = wp_constrain_dimensions( $image_src[1], $image_src[2], $width, $height );
						$resized_img_path  = $no_ext_path . '-' . $proportional_size[0] . 'x' . $proportional_size[1] . $extension;
						/*checking if the file already exists*/
						if ( file_exists( $resized_img_path ) ) {
							$resized_img_url = str_replace( basename( $image_src[0] ), basename( $resized_img_path ), $image_src[0] );
							$vt_image        = array(
								'url'    => $resized_img_url,
								'width'  => $proportional_size[0],
								'height' => $proportional_size[1],
							);
							
							return $vt_image;
						}
					}
					/*no cache files - let's finally resize it*/
					$img_editor = wp_get_image_editor( $actual_file_path );
					if ( is_wp_error( $img_editor ) || is_wp_error( $img_editor->resize( $width, $height, $crop ) ) ) {
						return array(
							'url'    => '',
							'width'  => '',
							'height' => '',
						);
					}
					$new_img_path = $img_editor->generate_filename();
					if ( is_wp_error( $img_editor->save( $new_img_path ) ) ) {
						return array(
							'url'    => '',
							'width'  => '',
							'height' => '',
						);
					}
					if ( ! is_string( $new_img_path ) ) {
						return array(
							'url'    => '',
							'width'  => '',
							'height' => '',
						);
					}
					$new_img_size = getimagesize( $new_img_path );
					$new_img      = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );
					/*resized output*/
					$vt_image = array(
						'url'    => $new_img,
						'width'  => $new_img_size[0],
						'height' => $new_img_size[1],
					);
					
					return $vt_image;
				}
				/*default output - without resizing*/
				$vt_image = array(
					'url'    => $image_src[0],
					'width'  => $image_src[1],
					'height' => $image_src[2],
				);
				
				return $vt_image;
			} else {
				if ( ! $img_on_curent_host && $remote_img_url != '' ) {
					$vt_image = array(
						'url'    => $remote_img_url,
						'width'  => $width,
						'height' => $height,
					);
					
					return $vt_image;
					
				} else {
					if ( $place_hold ) {
						$width  = intval( $width );
						$height = intval( $height );
						/*Real image place hold (https://unsplash.it/)*/
						if ( $use_real_img_hold ) {
							$random_time = time() + rand( 1, 100000 );
							$vt_image    = array(
								'url'    => 'https://unsplash.it/' . $width . '/' . $height . '?random&time=' . $random_time,
								'width'  => $width,
								'height' => $height,
							);
						} else {
							$vt_image = array(
								'url'    => 'https://placehold.it/' . $width . 'x' . $height,
								'width'  => $width,
								'height' => $height,
							);
						}
						
						return $vt_image;
					}
				}
			}
			
			return false;
		}
		
		/**
		 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
		 * Non-scalar values are ignored.
		 *
		 * @param string|array $var Data to sanitize.
		 *
		 * @return string|array
		 */
		public static function clean( $var ) {
			if ( is_array( $var ) ) {
				return array_map( array( 'Fami_Woocompare_Helper', 'clean' ), $var );
			} else {
				return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
			}
		}
		
	}
}