<?php

namespace LIBRARY;


class Helpers {

	public static $demo_import_start_time = '';

	public static function validate_import_file_info( $import_files ) {
		$filtered_import_file_info = array();

		foreach ( $import_files as $import_file ) {
			if ( self::is_import_file_info_format_correct( $import_file ) ) {
				$filtered_import_file_info[] = $import_file;
			}
		}

		return $filtered_import_file_info;
	}


	private static function is_import_file_info_format_correct( $import_file_info ) {
		if ( empty( $import_file_info['import_file_name'] ) ) {
			return false;
		}

		return true;
	}


	public static function download_import_files( $import_file_info ) {
		$downloaded_files = array(
			'content'    => '',
			'widgets'    => '',
			'customizer' => '',
			'redux'      => '',
		);
		$downloader = new Downloader();

		$import_file_info = self::apply_filters('library/pre_download_import_files', $import_file_info);

		if ( empty( $import_file_info['import_file_url'] ) ) {
			if ( file_exists( $import_file_info['local_import_file'] ) ) {
				$downloaded_files['content'] = $import_file_info['local_import_file'];
			}
		}
		else {
			$content_filename = self::apply_filters( 'library/downloaded_content_file_prefix', 'demo-content-import-file_' ) . self::$demo_import_start_time . self::apply_filters( 'library/downloaded_content_file_suffix_and_file_extension', '.xml' );

			$downloaded_files['content'] = $downloader->download_file( $import_file_info['import_file_url'], $content_filename );

			if ( is_wp_error( $downloaded_files['content'] ) ) {
				return $downloaded_files['content'];
			}
		}

		if ( ! empty( $import_file_info['import_widget_file_url'] ) ) {
			$widget_filename = self::apply_filters( 'library/downloaded_widgets_file_prefix', 'demo-widgets-import-file_' ) . self::$demo_import_start_time . self::apply_filters( 'library/downloaded_widgets_file_suffix_and_file_extension', '.json' );

			$downloaded_files['widgets'] = $downloader->download_file( $import_file_info['import_widget_file_url'], $widget_filename );

			if ( is_wp_error( $downloaded_files['widgets'] ) ) {
				return $downloaded_files['widgets'];
			}
		}
		else if ( ! empty( $import_file_info['local_import_widget_file'] ) ) {
			if ( file_exists( $import_file_info['local_import_widget_file'] ) ) {
				$downloaded_files['widgets'] = $import_file_info['local_import_widget_file'];
			}
		}

		if ( ! empty( $import_file_info['import_customizer_file_url'] ) ) {
			$customizer_filename = self::apply_filters( 'library/downloaded_customizer_file_prefix', 'demo-customizer-import-file_' ) . self::$demo_import_start_time . self::apply_filters( 'library/downloaded_customizer_file_suffix_and_file_extension', '.dat' );

			$downloaded_files['customizer'] = $downloader->download_file( $import_file_info['import_customizer_file_url'], $customizer_filename );

			if ( is_wp_error( $downloaded_files['customizer'] ) ) {
				return $downloaded_files['customizer'];
			}
		}
		else if ( ! empty( $import_file_info['local_import_customizer_file'] ) ) {
			if ( file_exists( $import_file_info['local_import_customizer_file'] ) ) {
				$downloaded_files['customizer'] = $import_file_info['local_import_customizer_file'];
			}
		}

		if ( ! empty( $import_file_info['import_redux'] ) && is_array( $import_file_info['import_redux'] ) ) {
			$redux_items = array();

			foreach ( $import_file_info['import_redux'] as $index => $redux_item ) {
				$redux_filename = self::apply_filters( 'library/downloaded_redux_file_prefix', 'demo-redux-import-file_' ) . $index . '-' . self::$demo_import_start_time . self::apply_filters( 'library/downloaded_redux_file_suffix_and_file_extension', '.json' );

				$file_path = $downloader->download_file( $redux_item['file_url'], $redux_filename );

				if ( is_wp_error( $file_path ) ) {
					return $file_path;
				}

				$redux_items[] = array(
					'option_name' => $redux_item['option_name'],
					'file_path'   => $file_path,
				);
			}

			$downloaded_files['redux'] = $redux_items;
		}
		else if ( ! empty( $import_file_info['local_import_redux'] ) ) {

			$redux_items = array();

			foreach ( $import_file_info['local_import_redux'] as $redux_item ) {
				if ( file_exists( $redux_item['file_path'] ) ) {
					$redux_items[] = $redux_item;
				}
			}

			$downloaded_files['redux'] = $redux_items;
		}

		return $downloaded_files;
	}


	public static function write_to_file( $content, $file_path ) {
		$verified_credentials = self::check_wp_filesystem_credentials();

		if ( is_wp_error( $verified_credentials ) ) {
			return $verified_credentials;
		}

		global $wp_filesystem;

		if ( ! $wp_filesystem->put_contents( $file_path, $content ) ) {
			return new \WP_Error(
				'failed_writing_file_to_server',
				sprintf( 
					__( 'An error occurred while writing file to your server! Tried to write a file to: %1$s%2$s.', 'borderless' ),
					'<br>',
					$file_path
				)
			);
		}

		return $file_path;
	}


	public static function append_to_file( $content, $file_path, $separator_text = '' ) {
		$verified_credentials = self::check_wp_filesystem_credentials();

		if ( is_wp_error( $verified_credentials ) ) {
			return $verified_credentials;
		}

		global $wp_filesystem;

		$existing_data = '';
		if ( file_exists( $file_path ) ) {
			$existing_data = $wp_filesystem->get_contents( $file_path );
		}

		$separator = PHP_EOL . '---' . $separator_text . '---' . PHP_EOL;

		if ( ! $wp_filesystem->put_contents( $file_path, $existing_data . $separator . $content . PHP_EOL ) ) {
			return new \WP_Error(
				'failed_writing_file_to_server',
				sprintf( 
					__( 'An error occurred while writing file to your server! Tried to write a file to: %1$s%2$s.', 'borderless' ),
					'<br>',
					$file_path
				)
			);
		}

		return true;
	}


	public static function data_from_file( $file_path ) {
		$verified_credentials = self::check_wp_filesystem_credentials();

		if ( is_wp_error( $verified_credentials ) ) {
			return $verified_credentials;
		}

		global $wp_filesystem;

		$data = $wp_filesystem->get_contents( $file_path );

		if ( ! $data ) {
			return new \WP_Error(
				'failed_reading_file_from_server',
				sprintf( 
					__( 'An error occurred while reading a file from your server! Tried reading file from path: %1$s%2$s.', 'borderless' ),
					'<br>',
					$file_path
				)
			);
		}

		return $data;
	}


	private static function check_wp_filesystem_credentials() {
		if ( ! ( 'direct' === get_filesystem_method() ) ) {
			return new \WP_Error(
				'no_direct_file_access',
				sprintf(
					__( 'This WordPress page does not have %1$sdirect%2$s write file access. This plugin needs it in order to save the demo import xml file to the upload directory of your site. You can change this setting with these instructions: %3$s.', 'borderless' ),
					'<strong>',
					'</strong>',
					'<a href="http://gregorcapuder.com/wordpress-how-to-set-direct-filesystem-method/" target="_blank">How to set <strong>direct</strong> filesystem method</a>'
				)
			);
		}

		$plugin_page_setup = self::get_plugin_page_setup_data();
		$demo_import_page_url = wp_nonce_url( $plugin_page_setup['parent_slug'] . '?page=' . $plugin_page_setup['menu_slug'], $plugin_page_setup['menu_slug'] );

		if ( false === ( $creds = request_filesystem_credentials( $demo_import_page_url, '', false, false, null ) ) ) {
			return new \WP_error(
				'filesystem_credentials_could_not_be_retrieved',
				__( 'An error occurred while retrieving reading/writing permissions to your server (could not retrieve WP filesystem credentials)!', 'borderless' )
			);
		}

		if ( ! WP_Filesystem( $creds ) ) {
			return new \WP_Error(
				'wrong_login_credentials',
				__( 'Your WordPress login credentials don\'t allow to use WP_Filesystem!', 'borderless' )
			);
		}

		return true;
	}


	public static function get_log_path() {
		$upload_dir  = wp_upload_dir();
		$upload_path = self::apply_filters( 'library/upload_file_path', trailingslashit( $upload_dir['path'] ) );

		$log_path = $upload_path . self::apply_filters( 'library/log_file_prefix', 'log_file_' ) . self::$demo_import_start_time . self::apply_filters( 'library/log_file_suffix_and_file_extension', '.txt' );

		self::register_file_as_media_attachment( $log_path );

		return $log_path;
	}


	public static function register_file_as_media_attachment( $log_path ) {
		$log_mimes = array( 'txt' => 'text/plain' );
		$filetype  = wp_check_filetype( basename( $log_path ), self::apply_filters( 'library/file_mimes', $log_mimes ) );

		$attachment = array(
			'guid'           => self::get_log_url( $log_path ),
			'post_mime_type' => $filetype['type'],
			'post_title'     => self::apply_filters( 'library/attachment_prefix', esc_html__( 'One Click Demo Import - ', 'borderless' ) ) . preg_replace( '/\.[^.]+$/', '', basename( $log_path ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attach_id = wp_insert_attachment( $attachment, $log_path );
	}


	public static function get_log_url( $log_path ) {
		$upload_dir = wp_upload_dir();
		$upload_url = self::apply_filters( 'library/upload_file_url', trailingslashit( $upload_dir['url'] ) );

		return $upload_url . basename( $log_path );
	}


	public static function verify_ajax_call() {
		check_ajax_referer( 'library-ajax-verification', 'security' );

		if ( ! current_user_can( 'import' ) ) {
			wp_die(
				sprintf( 
					__( '%1$sYour user role isn\'t high enough. You don\'t have permission to import demo data.%2$s', 'borderless' ),
					'<div class="notice  notice-error"><p>',
					'</p></div>'
				)
			);
		}
	}


	public static function process_uploaded_files( $uploaded_files, $log_file_path ) {
		$selected_import_files = array(
			'content'    => '',
			'widgets'    => '',
			'customizer' => '',
			'redux'      => '',
		);

		$upload_overrides = array(
			'test_form' => false,
		);

		add_filter( 'upload_mimes', function ( $defaults ) {
			$custom = [
				'xml'  => 'text/xml',
				'json' => 'application/json',
				'wie'  => 'application/json',
				'dat'  => 'text/plain',
			];

			return array_merge( $custom, $defaults );
		} );

		$file_not_provided_error = array(
			'error' => esc_html__( 'No file provided.', 'borderless' )
		);

		$content_file_info = isset( $_FILES['content_file'] ) ?
			wp_handle_upload( $_FILES['content_file'], $upload_overrides ) :
			$file_not_provided_error;

		$widget_file_info = isset( $_FILES['widget_file'] ) ?
			wp_handle_upload( $_FILES['widget_file'], $upload_overrides ) :
			$file_not_provided_error;

		$customizer_file_info = isset( $_FILES['customizer_file'] ) ?
			wp_handle_upload( $_FILES['customizer_file'], $upload_overrides ) :
			$file_not_provided_error;

		$redux_file_info = isset( $_FILES['redux_file'] ) ?
			wp_handle_upload( $_FILES['redux_file'], $upload_overrides ) :
			$file_not_provided_error;

		if ( $content_file_info && ! isset( $content_file_info['error'] ) ) {
			$selected_import_files['content'] = $content_file_info['file'];
		}
		else {
			$log_added = self::append_to_file(
				sprintf(
					__( 'Content file was not uploaded. Error: %s', 'borderless' ),
					$content_file_info['error']
				),
				$log_file_path,
				esc_html__( 'Upload files' , 'borderless' )
			);
		}

		if ( $widget_file_info && ! isset( $widget_file_info['error'] ) ) {
			$selected_import_files['widgets'] = $widget_file_info['file'];
		}
		else {
			$log_added = self::append_to_file(
				sprintf( 
					__( 'Widget file was not uploaded. Error: %s', 'borderless' ),
					$widget_file_info['error']
				),
				$log_file_path,
				esc_html__( 'Upload files' , 'borderless' )
			);
		}

		if ( $customizer_file_info && ! isset( $customizer_file_info['error'] ) ) {
			$selected_import_files['customizer'] = $customizer_file_info['file'];
		}
		else {
			$log_added = self::append_to_file(
				sprintf(
					__( 'Customizer file was not uploaded. Error: %s', 'borderless' ),
					$customizer_file_info['error']
				),
				$log_file_path,
				esc_html__( 'Upload files' , 'borderless' )
			);
		}

		if ( $redux_file_info && ! isset( $redux_file_info['error'] ) ) {
			if ( isset( $_POST['redux_option_name'] ) && empty( $_POST['redux_option_name'] ) ) {
				self::log_error_and_send_ajax_response(
					esc_html__( 'Missing Redux option name! Please also enter the Redux option name!', 'borderless' ),
					$log_file_path,
					esc_html__( 'Upload files', 'borderless' )
				);
			}

			$selected_import_files['redux'] = array(
				array(
					'option_name' => sanitize_text_field( $_POST['redux_option_name'] ),
					'file_path'   => $redux_file_info['file'],
				),
			);
		}
		else {
			$log_added = self::append_to_file(
				sprintf(
					__( 'Redux file was not uploaded. Error: %s', 'borderless' ),
					$redux_file_info['error']
				),
				$log_file_path,
				esc_html__( 'Upload files' , 'borderless' )
			);
		}

		$log_added = self::append_to_file(
			__( 'The import files were successfully uploaded!', 'borderless' ) . self::import_file_info( $selected_import_files ),
			$log_file_path,
			esc_html__( 'Upload files' , 'borderless' )
		);

		return $selected_import_files;
	}


	public static function import_file_info( $selected_import_files ) {
		$redux_file_string = '';

		if ( ! empty( $selected_import_files['redux'] ) ) {
			$redux_file_string = array_reduce( $selected_import_files['redux'], function( $string, $item ) {
				return sprintf( '%1$s%2$s -> %3$s %4$s', $string, $item['option_name'], $item['file_path'], PHP_EOL );
			}, '' );
		}

		return PHP_EOL .
		sprintf(
			__( 'Initial max execution time = %s', 'borderless' ),
			ini_get( 'max_execution_time' )
		) . PHP_EOL .
		sprintf(
			__( 'Files info:%1$sSite URL = %2$s%1$sData file = %3$s%1$sWidget file = %4$s%1$sCustomizer file = %5$s%1$sRedux files:%1$s%6$s', 'borderless' ),
			PHP_EOL,
			get_site_url(),
			empty( $selected_import_files['content'] ) ? esc_html__( 'not defined!', 'borderless' ) : $selected_import_files['content'],
			empty( $selected_import_files['widgets'] ) ? esc_html__( 'not defined!', 'borderless' ) : $selected_import_files['widgets'],
			empty( $selected_import_files['customizer'] ) ? esc_html__( 'not defined!', 'borderless' ) : $selected_import_files['customizer'],
			empty( $redux_file_string ) ? esc_html__( 'not defined!', 'borderless' ) : $redux_file_string
		);
	}


	public static function log_error_and_send_ajax_response( $error_text, $log_file_path, $separator = '' ) {
		$log_added = self::append_to_file(
			$error_text,
			$log_file_path,
			$separator
		);

		wp_send_json( $error_text );
	}


	public static function set_demo_import_start_time() {
		self::$demo_import_start_time = date( self::apply_filters( 'library/date_format_for_file_names', 'Y-m-d__H-i-s' ) );
	}


	public static function get_all_demo_import_categories( $demo_imports ) {
		$categories = array();

		foreach ( $demo_imports as $item ) {
			if ( ! empty( $item['categories'] ) && is_array( $item['categories'] ) ) {
				foreach ( $item['categories'] as $category ) {
					$categories[ sanitize_key( $category ) ] = $category;
				}
			}
		}

		if ( empty( $categories ) ) {
			return false;
		}

		return $categories;
	}


	public static function get_demo_import_item_categories( $item ) {
		$sanitized_categories = array();

		if ( isset( $item['categories'] ) ) {
			foreach ( $item['categories'] as $category ) {
				$sanitized_categories[] = sanitize_key( $category );
			}
		}

		if ( ! empty( $sanitized_categories ) ) {
			return implode( ' ', $sanitized_categories );
		}

		return false;
	}


	public static function set_library_import_data_transient( $data ) {
		set_transient( 'library_importer_data', $data, 0.1 * HOUR_IN_SECONDS );
	}


	public static function apply_filters( $hook, $default_data ) {
		$new_data = apply_filters( $hook, $default_data );

		if ( $new_data !== $default_data ) {
			return $new_data;
		}

		$old_data = apply_filters( "pt-$hook", $default_data );

		if ( $old_data !== $default_data ) {
			return $old_data;
		}

		return $default_data;
	}

	public static function do_action( $hook, ...$arg ) {
		if ( has_action( $hook ) ) {
			do_action( $hook, ...$arg );
		} else if ( has_action( "pt-$hook" ) ) {
			do_action( "pt-$hook", ...$arg );
		}
	}

	public static function has_action( $hook, $function_to_check = false ) {
		if ( has_action( $hook ) ) {
			return has_action( $hook, $function_to_check );
		} else if ( has_action( "pt-$hook" ) ) {
			return has_action( "pt-$hook", $function_to_check );
		}

		return false;
	}

	public static function get_plugin_page_setup_data() {
		return Helpers::apply_filters( 'library/plugin_page_setup', array(
			'parent_slug' => 'borderless.php',
			'page_title'  => esc_html__( 'Library' , 'borderless' ),
			'menu_title'  => esc_html__( 'Library' , 'borderless' ),
			'capability'  => 'import',
			'menu_slug'   => 'borderless-library',
		) );
	}
}
