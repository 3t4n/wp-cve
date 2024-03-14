<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'famisp_get_all_options' ) ) {
	/**
	 * @param string $option_name
	 *
	 * @return null
	 */
	function famisp_get_all_options() {
		$default_all_options = array(
			'famisp_enable_sales_popup'          => 'yes',
			'famisp_disable_sales_popup_mobile'  => 'yes',
			'famisp_popup_text'                  => __( 'Someone in {address} purchased a {product_name} <small>About {purchased_time} {time_unit} ago</small>', 'famisp' ),
			'famisp_min_time'                    => 15000, // millisecond
			'famisp_max_time'                    => 25000, // millisecond
			'famisp_products_ids'                => '',
			'famisp_enable_ran_buy_time_in_sec'  => 'yes',
			'famisp_min_random_buy_time_in_sec'  => 0,
			'famisp_max_random_buy_time_in_sec'  => 59,
			'famisp_enable_ran_buy_time_in_min'  => 'yes',
			'famisp_min_random_buy_time_in_min'  => 1,
			'famisp_max_random_buy_time_in_min'  => 59,
			'famisp_enable_ran_buy_time_in_hour' => 'yes',
			'famisp_min_random_buy_time_in_hour' => 1,
			'famisp_max_random_buy_time_in_hour' => 47,
			'famisp_enable_ran_buy_time_in_day'  => 'yes',
			'famisp_min_random_buy_time_in_day'  => 2,
			'famisp_max_random_buy_time_in_day'  => 10,
			'all_addresses'                      => array()
		);
		$all_options         = get_option( 'famisp_all_settings', array() );
		if ( wp_is_mobile() && $all_options['famisp_disable_sales_popup_mobile'] == 'yes' ) {
			$all_options['famisp_enable_sales_popup'] = 'no';
		}
		$all_options = wp_parse_args( $all_options, $default_all_options );
		
		return $all_options;
	}
}

if ( ! function_exists( 'famisp_get_option' ) ) {
	/**
	 * @param string $option_name
	 *
	 * @return null
	 */
	function famisp_get_option( $option_name = '', $default = null ) {
		$all_options = famisp_get_all_options();
		$option_val  = isset( $all_options[ $option_name ] ) ? $all_options[ $option_name ] : $default;
		
		return $option_val;
	}
}

if ( ! function_exists( 'famisp_select_html' ) ) {
	function famisp_select_html( $args = array(), $selected = '', $class = '', $name = '', $id = '', $echo = true ) {
		$html = '';
		if ( empty( $args ) ) {
			return '';
		}
		
		foreach ( $args as $val => $display ) {
			$html .= '<option ' . selected( true, $val == $selected, false ) . ' value="' . esc_attr( $val ) . '">' . esc_html( $display ) . '</option>';
		}
		
		$html_atts = '';
		if ( trim( $id ) != '' ) {
			$html_atts .= 'id="' . esc_attr( $id ) . '" ';
		}
		if ( trim( $name ) != '' ) {
			$html_atts .= 'name="' . esc_attr( $name ) . '" ';
		}
		$html_atts .= 'class="famisp-select ' . esc_attr( $class ) . '" ';
		
		$html = '<select ' . $html_atts . '>' . $html . '</select>';
		
		if ( $echo ) {
			echo $html;
		}
		
		return $html;
	}
}

if ( ! function_exists( 'famisp_get_sales_popup_data' ) ) {
	function famisp_get_sales_popup_data() {
		$default_popup_text = __( 'Someone in {address} purchased a {product_name} <small>About {purchased_time} {time_unit} ago</small>', 'famisp' );
		$sales_popup_data   = array(
			'famisp_enable_sales_popup'          => 'no',
			'famisp_disable_sales_popup_mobile'  => 'yes',
			'famisp_popup_text'                  => $default_popup_text,
			'famisp_min_time'                    => 5000, // millisecond
			'famisp_max_time'                    => 20000, // millisecond
			'famisp_products'                    => array(),
			'famisp_products_ids'                => '',
			'famisp_enable_ran_buy_time_in_sec'  => 'yes',
			'famisp_min_random_buy_time_in_sec'  => 0,
			'famisp_max_random_buy_time_in_sec'  => 59,
			'famisp_enable_ran_buy_time_in_min'  => 'yes',
			'famisp_min_random_buy_time_in_min'  => 1,
			'famisp_max_random_buy_time_in_min'  => 59,
			'famisp_enable_ran_buy_time_in_hour' => 'yes',
			'famisp_min_random_buy_time_in_hour' => 1,
			'famisp_max_random_buy_time_in_hour' => 47,
			'famisp_enable_ran_buy_time_in_day'  => 'yes',
			'famisp_min_random_buy_time_in_day'  => 2,
			'famisp_max_random_buy_time_in_day'  => 10,
			'all_addresses'                      => array()
		);
		$all_options        = famisp_get_all_options();
		$sales_popup_data   = wp_parse_args( $all_options, $sales_popup_data );
		if ( trim( $sales_popup_data['famisp_popup_text'] ) == '' ) {
			$sales_popup_data['famisp_popup_text'] = $default_popup_text;
		}
		
		$products_ids = $all_options['famisp_products_ids'];
		if ( trim( $products_ids ) != '' ) {
			$products_ids = explode( ',', $products_ids );
			if ( ! empty( $products_ids ) ) {
				foreach ( $products_ids as $products_id ) {
					$famisp_product = wc_get_product( $products_id );
					if ( ! $famisp_product ) {
						continue;
					}
					$sales_popup_data['famisp_products'][] = array(
						'product_name' => $famisp_product->get_name(),
						'price_html'   => $famisp_product->get_price_html(),
						'url'          => get_permalink( $products_id ),
						'img'          => famisp_resize_image( get_post_thumbnail_id( $products_id ), null, 100, 100, true, true, false )
					);
				}
			}
		}
		
		return $sales_popup_data;
	}
}

if ( ! function_exists( 'famisp_resize_image' ) ) {
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
	function famisp_resize_image( $attach_id = null, $img_url = null, $width, $height, $crop = false, $place_hold = true, $use_real_img_hold = true, $solid_img_color = null ) {
		/*If is singular and has post thumbnail and $attach_id is null, so we get post thumbnail id automatic*/
		if ( is_singular() && ! $attach_id ) {
			if ( has_post_thumbnail() && ! post_password_required() ) {
				$attach_id = get_post_thumbnail_id();
			}
		}
		/*this is an attachment, so we have the ID*/
		$image_src = array();
		if ( $attach_id ) {
			$image_src        = wp_get_attachment_image_src( $attach_id, 'full' );
			$actual_file_path = get_attached_file( $attach_id );
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
						'url'    => 'http://placehold.it/' . $width . 'x' . $height,
						'width'  => $width,
						'height' => $height,
					);
				}
				
				return $vt_image;
			}
		}
		
		return false;
	}
}


if ( ! function_exists( 'famisp_no_image' ) ) {
	/**
	 * No image generator
	 *
	 * @since 1.0
	 *
	 * @param $size : array, image size
	 * @param $echo : bool, echo or return no image url
	 **/
	function famisp_no_image( $size = array( 'width' => 500, 'height' => 500 ), $echo = false, $transparent = false
	) {
		$noimage_dir = FAMISP_PATH . 'assets';
		$noimage_uri = FAMISP_URI . 'assets';
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
}

if ( ! function_exists( 'famisp_img_lazy' ) ) {
	function famisp_img_lazy( $width = 1, $height = 1 ) {
		// $img_lazy = 'data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%20' . $width . '%20' . $height . '%27%2F%3E';
		// $img_lazy = 'https://via.placeholder.com/' . $width . 'x' . $height . '/fff/fff';
		$img_lazy = famisp_no_image(
			array(
				'width'  => $width,
				'height' => $height
			), false, true );
		
		return $img_lazy;
	}
}

if ( ! function_exists( 'famisp_img_output' ) ) {
	/**
	 * @param array  $img
	 * @param string $class
	 * @param string $alt
	 * @param string $title
	 *
	 * @return string
	 */
	function famisp_img_output( $img, $class = '', $alt = '', $title = '' ) {
		
		$img_default = array(
			'width'  => '',
			'height' => '',
			'url'    => ''
		);
		$img         = wp_parse_args( $img, $img_default );
		$enable_lazy = famisp_is_enable_lazy_load();
		
		if ( $enable_lazy ) {
			$img_lazy = famisp_img_lazy( $img['width'], $img['height'] );
			$img_html = '<img class="fami-img fami-lazy lazy ' . esc_attr( $class ) . '" width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" src="' . $img_lazy . '" data-src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $alt ) . '" title="' . esc_attr( $title ) . '" />';
		} else {
			$img_html = '<img class="fami-img ' . esc_attr( $class ) . '" width="' . esc_attr( $img['width'] ) . '" height="' . esc_attr( $img['height'] ) . '" src="' . esc_url( $img['url'] ) . '" alt="' . esc_attr( $alt ) . '" title="' . esc_attr( $title ) . '" />';
		}
		
		return $img_html;
	}
}

if ( ! function_exists( 'famisp_is_enable_lazy_load' ) ) {
	function famisp_is_enable_lazy_load() {
		return famisp_get_option( 'famisp_enable_lazy', true );
	}
}

if ( ! function_exists( 'famisp_clean' ) ) {
	/**
	 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
	 * Non-scalar values are ignored.
	 *
	 * @param string|array $var Data to sanitize.
	 *
	 * @return string|array
	 */
	function famisp_clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'famisp_clean', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}
}