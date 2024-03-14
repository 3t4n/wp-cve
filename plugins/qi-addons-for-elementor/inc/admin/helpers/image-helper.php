<?php

if ( ! function_exists( 'qi_addons_for_elementor_framework_get_attachment_id_from_url' ) ) {
	/**
	 * Function that retrieves attachment id for passed attachment url
	 *
	 * @param string $attachment_url
	 *
	 * @return null|string
	 */
	function qi_addons_for_elementor_framework_get_attachment_id_from_url( $attachment_url ) {
		global $wpdb;
		$attachment_id = '';

		if ( '' !== $attachment_url ) {

			$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE guid=%s", $attachment_url ) );

			// Additional check for undefined reason when guid is not image src
			if ( empty( $attachment_id ) ) {
				$modified_url = substr( $attachment_url, strrpos( $attachment_url, '/' ) + 1 );

				//get attachment id
				$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_wp_attached_file' AND meta_value LIKE %s", '%' . $modified_url . '%' ) );
			}
		}

		return $attachment_id;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_get_attachment_thumb_url' ) ) {
	/**
	 * Function that gets attachment thumbnail url from attachment url
	 *
	 * @param string $attachment_url - url of the attachment
	 *
	 * @return bool|string
	 *
	 * @see qi_addons_for_elementor_framework_get_attachment_id_from_url()
	 */
	function qi_addons_for_elementor_framework_get_attachment_thumb_url( $attachment_url ) {
		$attachment_id = qi_addons_for_elementor_framework_get_attachment_id_from_url( $attachment_url );

		if ( ! empty( $attachment_id ) ) {
			return qi_addons_for_elementor_get_attachment_thumb_url( $attachment_id );
		} else {
			return $attachment_url;
		}
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_get_image_html_from_src' ) ) {
	/**
	 * Function that returns image tag from url and it's attributes.
	 *
	 * @param string $url
	 * @param array $attr
	 *
	 * @return string
	 */
	function qi_addons_for_elementor_framework_get_image_html_from_src( $url, $attr = array() ) {
		$html = '';

		$html .= '<img src="' . esc_url( $url ) . '"';
		foreach ( $attr as $name => $value ) {
			$html .= ' ' . $name . '="' . $value . '"';
		}
		$html .= ' />';

		return $html;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_resize_image' ) ) {
	/**
	 * Function that generates custom thumbnail for given attachment
	 *
	 * @param int|string $attachment - attachment id or url of image to resize
	 * @param int $width desired - height of custom thumbnail
	 * @param int $height desired - width of custom thumbnail
	 * @param bool $crop - whether to crop image or not
	 *
	 * @return array returns array containing img_url, width and height
	 *
	 * @see qi_addons_for_elementor_framework_get_attachment_id_from_url()
	 * @see get_attached_file()
	 * @see qi_addons_for_elementor_get_attachment_url()
	 * @see wp_get_image_editor()
	 */
	function qi_addons_for_elementor_framework_resize_image( $attachment, $width = null, $height = null, $crop = true ) {
		$return_array = array();

		if ( ! empty( $attachment ) ) {
			if ( is_int( $attachment ) ) {
				$attachment_id = $attachment;
			} else {
				$attachment_id = qi_addons_for_elementor_framework_get_attachment_id_from_url( $attachment );
			}

			if ( ! empty( $attachment_id ) && ( isset( $width ) && isset( $height ) ) ) {

				//get file path of the attachment
				$img_path = get_attached_file( $attachment_id );

				//get attachment url
				$img_url = qi_addons_for_elementor_get_attachment_url( $attachment_id );

				//break down img path to array so we can use it's components in building thumbnail path
				$img_path_array = pathinfo( $img_path );

				//build thumbnail path
				$new_img_path = $img_path_array['dirname'] . '/' . $img_path_array['filename'] . '-' . $width . 'x' . $height . '.' . $img_path_array['extension'];

				//build thumbnail url
				$new_img_url = str_replace( $img_path_array['filename'], $img_path_array['filename'] . '-' . $width . 'x' . $height, $img_url );

				//check if thumbnail exists by it's path
				if ( ! file_exists( $new_img_path ) ) {
					//get image manipulation object
					$image_object = wp_get_image_editor( $img_path );

					if ( ! is_wp_error( $image_object ) ) {
						//resize image and save it new to path
						$image_object->resize( $width, $height, $crop );
						$image_object->save( $new_img_path );

						//get sizes of newly created thumbnail.
						///we don't use $width and $height because those might differ from end result based on $crop parameter
						$image_sizes = $image_object->get_size();

						$width  = $image_sizes['width'];
						$height = $image_sizes['height'];
					}
				}

				//generate data to be returned
				$return_array = array(
					'img_url'    => $new_img_url,
					'img_width'  => $width,
					'img_height' => $height,
				);

				//attachment wasn't found in gallery but it is not empty
			} elseif ( '' !== $attachment && ( isset( $width ) && isset( $height ) ) ) {
				//generate data to be returned
				$return_array = array(
					'img_url'    => $attachment,
					'img_width'  => $width,
					'img_height' => $height,
				);
			}
		}

		return $return_array;
	}
}

if ( ! function_exists( 'qi_addons_for_elementor_framework_generate_thumbnail' ) ) {
	/**
	 * Generates thumbnail img tag. It calls qi_addons_for_elementor_framework_resize_image function for resizing image
	 *
	 * @param int|string $attachment - attachment id or url to generate thumbnail from
	 * @param int $width - width of thumbnail
	 * @param int $height - height of thumbnail
	 * @param bool $crop - whether to crop thumbnail or not
	 *
	 * @return string generated img tag
	 *
	 * @see qi_addons_for_elementor_framework_resize_image()
	 * @see qi_addons_for_elementor_framework_get_attachment_id_from_url()
	 */
	function qi_addons_for_elementor_framework_generate_thumbnail( $attachment, $width = null, $height = null, $crop = true ) {
		if ( ! empty( $attachment ) ) {
			if ( is_int( $attachment ) ) {
				$attachment_id = $attachment;
			} else {
				$attachment_id = qi_addons_for_elementor_framework_get_attachment_id_from_url( $attachment );
			}
			$img_info = qi_addons_for_elementor_framework_resize_image( $attachment_id, $width, $height, $crop );
			$img_alt  = ! empty( $attachment_id ) ? get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) : '';

			if ( is_array( $img_info ) && count( $img_info ) ) {
				$url            = esc_url( $img_info['img_url'] );
				$attr           = array();
				$attr['alt']    = esc_attr( $img_alt );
				$attr['width']  = esc_attr( $img_info['img_width'] );
				$attr['height'] = esc_attr( $img_info['img_height'] );

				return qi_addons_for_elementor_framework_get_image_html_from_src( $url, $attr );
			}
		}

		return '';
	}
}
