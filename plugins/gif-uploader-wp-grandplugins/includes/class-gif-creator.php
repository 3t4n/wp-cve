<?php

namespace GPLSCore\GPLS_PLUGIN_WGR;

use GPLSCore\GPLS_PLUGIN_WGR\GIF_Base;

/**
 * GIF Creator GD.
 */
class GIF_Creator extends GIF_Base {

	/**
	 * GIF Creator Hooks.
	 *
	 * @return void
	 */
	public static function hooks() {
		add_action( 'wp_ajax_' . self::$plugin_info['name'] . '-gif-create', array( get_called_class(), 'ajax_create_gif' ) );
		add_action( 'wp_ajax_' . self::$plugin_info['name'] . '-gif-save', array( get_called_class(), 'ajax_save_gif' ) );
	}

	/**
	 * AJAX create GIF from Images.
	 *
	 * @return void
	 */
	public static function ajax_create_gif() {
		if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['nonce'] ), self::$plugin_info['name'] . '-ajax-nonce' ) ) {
			if ( ! empty( $_POST['frames'] ) && is_array( $_POST['frames'] ) ) {
				$frames        = wp_unslash( $_POST['frames'] );
				$speed         = ! empty( $_POST['speed'] ) ? absint( sanitize_text_field( wp_unslash( $_POST['speed'] ) ) ) : 15;
				$loop          = ! empty( $_POST['loop'] ) ? absint( sanitize_text_field( wp_unslash( $_POST['loop'] ) ) ) : 0;
				$images_paths  = array();
				$options       = array(
					'duration' => $speed,
					'loops'    => $loop,
				);
				foreach ( $frames as $frame_id ) {
					$img_id                  = absint( sanitize_text_field( $frame_id ) );
					$images_paths[ $img_id ] = wp_get_original_image_path( $img_id );
				}

				if ( ! extension_loaded( 'imagick' ) || ! class_exists( '\Imagick', false ) || ! class_exists( '\ImagickPixel', false ) ) {
					wp_send_json_error(
						array(
							'status' => 'danger',
							'msg'    => esc_html__( 'Imagick module is not enabled, please contact your hosting customer service to enable it!', 'wp-gif-editor' ),
						)
					);
				}

				$preview_gif_url = self::create_gif( self::$plugin_info, array_values( $images_paths ), $options, false );
				if ( is_wp_error( $preview_gif_url ) ) {
					wp_send_json_error(
						array(
							'status' => 'danger',
							'msg'    => $preview_gif_url->get_error_message(),
						)
					);
				}
				$preview_gif_url = add_query_arg(
					array(
						'refresh' => wp_generate_password( 5, false, false ),
					),
					$preview_gif_url
				);
				wp_send_json_success(
					array(
						'status' => 'success',
						'result' => $preview_gif_url,
					)
				);
			} else {
				wp_send_json_error(
					array(
						'status' => 'danger',
						'msg'    => esc_html__( 'No Frames selected', 'wp-gif-editor' ),
					)
				);
			}
		}
		wp_send_json_error(
			array(
				'status' => 'danger',
				'msg'    => esc_html__( 'The link has expired, please refresh the page!', 'wp-gif-editor' ),
			)
		);
	}

	/**
	 * Create GIF out of multiple images.
	 *
	 * @param array   $plugin_info Plugin Info Array.
	 * @param array   $imgs_arr   GIF images paths array.
	 * @param array   $options  Options Array.
	 * @param boolean $is_save  save the GIF or show it.
	 * @param string  $filename  Resulted GIF filename.
	 * @return \WP_Error|string
	 */
	public static function create_gif( $plugin_info, $imgs_arr, $options, $is_save = false, $filename = '' ) {
		set_time_limit( 0 );
		return self::create_gif_free( $plugin_info, $imgs_arr, $options );
	}

	/**
	 * Create GIF Free.
	 *
	 * @param array $plugin_info
	 * @param array $imgs_arr
	 * @param array $options
	 * @return string
	 */
	private static function create_gif_free( $plugin_info, $imgs_arr, $options ) {
		$first_frame        = new \Imagick( $imgs_arr[0] );
		$first_frame_width  = $first_frame->getImageWidth();
		$first_frame_height = $first_frame->getImageHeight();
		$first_frame_coal   = $first_frame->coalesceImages();
		$options['width']   = $first_frame_width;
		$options['height']  = $first_frame_height;

		$first_frame_coal->setImageDelay( $options['duration'] );
		$first_frame_coal->setImageIterations( $options['loops'] );

		array_shift( $imgs_arr );

		foreach ( $imgs_arr as $img_index => $img_path ) {
			$img        = new \Imagick( $img_path );
			$img_width  = $img->getImageWidth();
			$img_height = $img->getImageHeight();
			$img_coal   = $img->coalesceImages();

			do {
				if ( ( $options['width'] !== $img_width ) || ( $options['height'] !== $img_height ) ) {
					$img_coal->resizeImage( $options['width'], $options['height'], \Imagick::FILTER_BOX, 1 );
				}
				$img_coal->setImageDelay( $options['duration'] );
				$img_coal->setImageIterations( $options['loops'] );
				$current_frame = $img_coal->getImage();
				$first_frame_coal->addImage( $current_frame );
			} while ( $img_coal->nextImage() );

			$img->clear();
		}
		return self::save_final_gif( $first_frame_coal );
	}

	/**
	 * Save Final Created GIF.
	 *
	 * @param \Imagick $gif_obj GIF Imagick Object.
	 * @return string
	 */
	private static function save_final_gif( $gif_obj ) {
		$final_gif = $gif_obj->deconstructImages();
		$final_gif->setImageFormat( 'gif' );
		return self::save_preview_gif( $final_gif->getImagesBlob(), 'url' );
	}

	/**
	 * Init Static Vars.
	 *
	 * @param array $plugin_info Plugin Info Array.
	 * @return void
	 */
	public static function init( $plugin_info ) {
		self::hooks();
	}

	/**
	 * AJAX save GIF.
	 *
	 * @return void
	 */
	public static function ajax_save_gif() {
		if ( ! empty( $_POST['nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['nonce'] ), self::$plugin_info['name'] . '-ajax-nonce' ) ) {
			if ( ! empty( $_POST['gif'] ) ) {
				$title  = ! empty( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : '';
				$gif_id = self::save_gif( $title );
				if ( is_wp_error( $gif_id ) ) {
					wp_send_json_error(
						array(
							'status' => 'danger',
							'msg'    => $gif_id->get_error_message(),
						)
					);
				}
				wp_send_json_success(
					array(
						'msg'     => esc_html__( 'GIF has been saved successfully!', 'wp-gif-editor' ),
						'status'  => 'success',
						'display' => static::display_gif_icon_box( $gif_id ),
					)
				);
			}
		}
		wp_send_json_error(
			array(
				'status' => 'danger',
				'msg'    => esc_html__( 'The link has expired, please refresh the page!', 'wp-gif-editor' ),
			)
		);
	}
}
