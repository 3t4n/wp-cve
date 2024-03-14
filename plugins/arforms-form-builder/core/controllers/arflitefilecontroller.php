<?php

class arflitefilecontroller {

	var $file;
	var $check_cap;
	var $capabilities;
	var $check_nonce;
	var $nonce_data;
	var $nonce_action;
	var $check_only_image;
	var $check_specific_ext;
	var $allowed_ext;
	var $invalid_ext;
	var $compression_ext;
	var $error_message;
	var $default_error_msg;
	var $check_file_size;
	var $field_error_msg;
	var $field_size_error_msg;
	var $generate_thumb;
	var $thumb_path;
	var $import;
	var $file_size;
	var $image_exts;

	function __construct( $file, $import ) {

		if ( empty( $file ) && ! $import ) {
			$this->error_message = __( 'Please select a file to process', 'arforms-form-builder' );
			return false;
		}

		$this->file = $file;

		if ( ! $import ) {

			$this->file_size = $file['size'];

		}

		$this->import = $import;

		$this->invalid_ext = apply_filters( 'arflite_restricted_file_ext', array( 'php', 'php3', 'php4', 'php5', 'py', 'pl', 'jsp', 'asp', 'cgi', 'ext' ) );

		$this->compression_ext = apply_filters( 'arflite_exclude_file_check_ext', array( 'tar', 'zip', 'gz', 'gzip', 'rar', '7z' ) );

		$mimes = get_allowed_mime_types();

		$type_img = array();

		foreach ( $mimes as $ext => $type ) {
			if ( preg_match( '/(image\/)/', $type ) ) {
				if ( preg_match( '/(\|)/', $ext ) ) {
					$type_imgs = explode( '|', $ext );
					$type_img  = array_merge( $type_img, $type_imgs );
				} else {
					$type_img[] = $ext;
				}
			}
		}

		$this->image_exts = $type_img;

	}

	function arflite_process_upload( $destination ) {

		if ( $this->check_cap ) {
			$capabilities = $this->capabilities;

			if ( ! empty( $capabilities ) ) {
				if ( is_array( $capabilities ) ) {
					$isFailed = false;
					foreach ( $capabilities as $caps ) {
						if ( ! current_user_can( $caps ) ) {
							$isFailed            = true;
							$this->error_message = __( "Sorry, you don't have permission to perform this action.", 'arforms-form-builder' );
							break;
						}
					}

					if ( $isFailed ) {
						return false;
					}
				} else {
					if ( ! current_user_can( $capabilities ) ) {
						$this->error_message = __( "Sorry, you don't have permission to perform this action.", 'arforms-form-builder' );
					}
				}
			} else {
				$this->error_message = __( "Sorry, you don't have permission to perform this action.", 'arforms-form-builder' );
				return false;
			}
		}

		if ( $this->check_nonce ) {
			if ( empty( $this->nonce_data ) || empty( $this->nonce_action ) ) {
				$this->error_message = __( 'Sorry, Your request could not be processed due to security reasons.', 'arforms-form-builder' );
				return false;
			}

			if ( ! wp_verify_nonce( $this->nonce_data, $this->nonce_action ) ) {
				$this->error_message = __( 'Sorry, Your request could not be processed due to security reasons.', 'arforms-form-builder' );
				return false;
			}
		}

		if ( $this->import ) {
			$ext_data = explode( '.', $this->file );
		} else {
			$ext_data = explode( '.', $this->file['name'] );
		}

		$ext = end( $ext_data );
		$ext = strtolower( $ext );

		if ( in_array( $ext, $this->invalid_ext ) ) {
			$this->error_message = __( 'The file could not be uploaded due to security reasons.', 'arforms-form-builder' );
			return false;
		}

		if ( $this->check_only_image ) {

			if ( ! $this->import && ! preg_match( '/(image\/)/', $this->file['type'] ) ) {
				$this->error_message = __( 'Please select image file only.', 'arforms-form-builder' );
				if ( ! empty( $this->default_error_msg ) ) {
					$this->error_message = esc_html( $this->default_error_msg );
				}
				return false;
			}

			if ( $this->import ) {
				if ( ! in_array( $ext, $this->image_exts ) ) {
					$this->error_message = __( 'Please select image file only.', 'arforms-form-builder' );
					if ( ! empty( $this->default_error_msg ) ) {
						$this->error_message = esc_html( $this->default_error_msg );
					}
					return false;
				}
			}
		}

		if ( $this->check_specific_ext ) {
			if ( empty( $this->allowed_ext ) ) {
				$this->error_message = esc_html__( 'Please set extensions to validate file.', 'arforms-form-builder' );
				return false;
			}
			if ( ! in_array( $ext, $this->allowed_ext ) ) {
				$this->error_message = __( 'Invalid file extension. Please select valid file', 'arforms-form-builder' );
				if ( ! empty( $this->default_error_msg ) ) {
					$this->error_message = esc_html( $this->default_error_msg );
				}

				if ( ! empty( $this->field_error_msg ) ) {
					$this->error_message = esc_html( $this->field_error_msg );
				}

				return false;
			}
		}

		if ( $this->check_file_size ) {

			$size_in_bytes = $this->arflite_convert_to_bytes();

			if ( $size_in_bytes < $this->file_size ) {
				$this->error_message = __( 'Invalid File Size.', 'arforms-form-builder' );

				if ( ! empty( $this->field_size_error_msg ) ) {
					$this->error_message = esc_html( $this->field_size_error_msg );
				}
				return false;
			}
		}

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		WP_Filesystem();
		global $wp_filesystem;

		if ( $this->import ) {
			$file_content = $wp_filesystem->get_contents( $this->file );
		} else {
			$file_content = $wp_filesystem->get_contents( $this->file['tmp_name'] );
		}

		$is_valid_file = $this->arflite_read_file( $file_content, $ext );

		if ( ! $is_valid_file ) {
			return false;
		}

		if ( '' == $file_content || ! $wp_filesystem->put_contents( $destination, $file_content, 0777 ) ) {
			$this->error_message = __( 'There is an issue while uploading a file. Please try again', 'arforms-form-builder' );
			return false;
		}

		if ( $this->generate_thumb && preg_match( '/(image\/)/', $this->file['type'] ) ) {

			require_once ARFLITE_FORMPATH . '/js/filedrag/simple_image.php';

			$image = new SimpleImage();
			$image->load( $destination );
			$image->resizeToHeight( 100 );
			$image->save( $this->thumb_path );

		}

		return true;
	}

	function arflite_read_file( $file_content, $ext ) {

		if ( '' == $file_content ) {
			return true;
		}

		if ( in_array( $ext, $this->compression_ext ) ) {
			return true;
		}

		$file_bytes = $this->file_size;

		$file_size = number_format( $file_bytes / 1048576, 2 );

		if ( $file_size > 10 ) {
			return true;
		}

		$arflite_valid_pattern = '/(\<\?(php))/';

		if ( preg_match( $arflite_valid_pattern, $file_content ) ) {
			$this->error_message = __( 'The file could not be uploaded due to security reason as it contains malicious code', 'arforms-form-builder' );
			return false;
		}

		return true;

	}

	function arflite_convert_to_bytes() {

		$units_arr = array(
			'B'  => 0,
			'K'  => 1,
			'KB' => 1,
			'M'  => 2,
			'MB' => 2,
			'G'  => 3,
			'GB' => 3,
			'T'  => 4,
			'TB' => 4,
			'P'  => 5,
			'PB' => 5,
		);

		$numbers = preg_replace( '/[^\d.]/', '', $this->max_file_size );

		$suffix = preg_replace( '/[\d.]+/', '', $this->max_file_size );

		if ( is_numeric( substr( $suffix, 0, 1 ) ) ) {
			return preg_replace( '/[^\d.]/', '', $this->max_file_size );
		}

		$exponent = ! empty( $units_arr[ $suffix ] ) ? $units_arr[ $suffix ] : null;

		if ( null == $exponent ) {
			return null;
		}

		return $numbers * ( 1024 ** $exponent );

	}

}
