<?php

namespace LIBRARY;

class CustomizerImporter {
	public static function import( $customizer_import_file_path ) {
		$library          = BorderlessLibraryImporter::get_instance();
		$log_file_path = $library->get_log_file_path();

		$results = self::import_customizer_options( $customizer_import_file_path );

		if ( is_wp_error( $results ) ) {
			$error_message = $results->get_error_message();

			$library->append_to_frontend_error_messages( $error_message );

			Helpers::append_to_file(
				$error_message,
				$log_file_path,
				esc_html__( 'Importing customizer settings', 'borderless' )
			);
		}
		else {
			$log_added = Helpers::append_to_file(
				esc_html__( 'Customizer settings import finished!', 'borderless' ),
				$log_file_path,
				esc_html__( 'Importing customizer settings' , 'borderless' )
			);
		}
	}


	public static function import_customizer_options( $import_file_path ) {
		global $wp_customize;

		$template = get_template();

		if ( ! file_exists( $import_file_path ) ) {
			return new \WP_Error(
				'missing_cutomizer_import_file',
				sprintf(
					esc_html__( 'Error: The customizer import file is missing! File path: %s', 'borderless' ),
					$import_file_path
				)
			);
		}

		$raw = Helpers::data_from_file( $import_file_path );

		if ( is_wp_error( $raw ) ) {
			return $raw;
		}

		$data = unserialize( $raw );

		if ( ! is_array( $data ) && ( ! isset( $data['template'] ) || ! isset( $data['mods'] ) ) ) {
			return new \WP_Error(
				'customizer_import_data_error',
				esc_html__( 'Error: The customizer import file is not in a correct format. Please make sure to use the correct customizer import file.', 'borderless' )
			);
		}
		if ( $data['template'] !== $template ) {
			return new \WP_Error(
				'customizer_import_wrong_theme',
				esc_html__( 'Error: The customizer import file is not suitable for current theme. You can only import customizer settings for the same theme or a child theme.', 'borderless' )
			);
		}

		if ( Helpers::apply_filters( 'library/customizer_import_images', true ) ) {
			$data['mods'] = self::import_customizer_images( $data['mods'] );
		}

		if ( isset( $data['options'] ) ) {
			if ( ! class_exists( '\WP_Customize_Setting' ) ) {
				require_once ABSPATH . 'wp-includes/class-wp-customize-setting.php';
			}

			foreach ( $data['options'] as $option_key => $option_value ) {
				$option = new CustomizerOption( $wp_customize, $option_key, array(
					'default'    => '',
					'type'       => 'option',
					'capability' => 'edit_theme_options',
				) );

				$option->import( $option_value );
			}
		}

		$use_wp_customize_save_hooks = Helpers::apply_filters( 'library/enable_wp_customize_save_hooks', false );

		if ( $use_wp_customize_save_hooks ) {
			do_action( 'customize_save', $wp_customize );
		}

		foreach ( $data['mods'] as $key => $val ) {
			if ( $use_wp_customize_save_hooks ) {
				do_action( 'customize_save_' . $key, $wp_customize );
			}

			set_theme_mod( $key, $val );
		}

		if ( $use_wp_customize_save_hooks ) {
			do_action( 'customize_save_after', $wp_customize );
		}
	}

	private static function import_customizer_images( $mods ) {
		foreach ( $mods as $key => $val ) {
			if ( self::customizer_is_image_url( $val ) ) {
				$data = self::customizer_sideload_image( $val );
				if ( ! is_wp_error( $data ) ) {
					$mods[ $key ] = $data->url;

					if ( isset( $mods[ $key . '_data' ] ) ) {
						$mods[ $key . '_data' ] = $data;
						update_post_meta( $data->attachment_id, '_wp_attachment_is_custom_header', get_stylesheet() );
					}
				}
			}
		}

		return $mods;
	}

	private static function customizer_sideload_image( $file ) {
		$data = new \stdClass();

		if ( ! function_exists( 'media_handle_sideload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
		}
		if ( ! empty( $file ) ) {
			preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );
			$file_array = array();
			$file_array['name'] = basename( $matches[0] );

			$file_array['tmp_name'] = download_url( $file );

			if ( is_wp_error( $file_array['tmp_name'] ) ) {
				return $file_array['tmp_name'];
			}

			$id = media_handle_sideload( $file_array, 0 );

			if ( is_wp_error( $id ) ) {
				unlink( $file_array['tmp_name'] );
				return $id;
			}

			$meta                = wp_get_attachment_metadata( $id );
			$data->attachment_id = $id;
			$data->url           = wp_get_attachment_url( $id );
			$data->thumbnail_url = wp_get_attachment_thumb_url( $id );
			$data->height        = $meta['height'];
			$data->width         = $meta['width'];
		}

		return $data;
	}

	private static function customizer_is_image_url( $string = '' ) {
		if ( is_string( $string ) ) {
			if ( preg_match( '/\.(jpg|jpeg|png|gif)/i', $string ) ) {
				return true;
			}
		}

		return false;
	}
}
