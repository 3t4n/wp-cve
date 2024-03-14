<?php
/**
 * Demo Importer Plus Site Helper
 *
 * @since  1.0.0
 * @package Demo Importer Plus Sites
 */

if ( ! class_exists( 'Demo_Importer_Plus_Sites_Helper' ) ) :

	/**
	 * Demo_Importer_Plus_Sites_Helper
	 */
	class Demo_Importer_Plus_Sites_Helper {

		/**
		 * Instance
		 *
		 * @access private
		 * @var object Instance
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_filter( 'wie_import_data', array( $this, 'custom_menu_widget' ) );
			add_filter( 'wp_prepare_attachment_for_js', array( $this, 'add_svg_image_support' ), 10, 3 );
		}

		/**
		 * Add svg image support
		 *
		 * @param array  $response    Attachment response.
		 * @param object $attachment Attachment object.
		 * @param array  $meta        Attachment meta data.
		 */
		public function add_svg_image_support( $response, $attachment, $meta ) {
			if ( ! function_exists( 'simplexml_load_file' ) ) {
				return $response;
			}

			if ( ! empty( $response['sizes'] ) ) {
				return $response;
			}

			if ( 'image/svg+xml' !== $response['mime'] ) {
				return $response;
			}

			$svg_path = get_attached_file( $attachment->ID );

			$dimensions = self::get_svg_dimensions( $svg_path );

			$response['sizes'] = array(
				'full' => array(
					'url'         => $response['url'],
					'width'       => $dimensions->width,
					'height'      => $dimensions->height,
					'orientation' => $dimensions->width > $dimensions->height ? 'landscape' : 'portrait',
				),
			);

			return $response;
		}

		/**
		 * Get SVG Dimensions
		 *
		 * @param  string $svg SVG file path.
		 * @return array      Return SVG file height & width for valid SVG file.
		 */
		public static function get_svg_dimensions( $svg ) {

			$svg = simplexml_load_file( $svg );

			if ( false === $svg ) {
				$width  = '0';
				$height = '0';
			} else {
				$attributes = $svg->attributes();
				$width      = (string) $attributes->width;
				$height     = (string) $attributes->height;
			}

			return (object) array(
				'width'  => $width,
				'height' => $height,
			);
		}

		/**
		 * Custom Menu Widget
		 *
		 * @param  object $all_sidebars Widget data.
		 * @return object               Set custom menu id by slug.
		 */
		public function custom_menu_widget( $all_sidebars ) {

			// Get current menu ID & Slugs.
			$menu_locations = array();
			$nav_menus      = (object) wp_get_nav_menus();
			if ( isset( $nav_menus ) ) {
				foreach ( $nav_menus as $menu_key => $menu ) {
					if ( is_object( $menu ) ) {
						$menu_locations[ $menu->term_id ] = $menu->slug;
					}
				}
			}

			// Import widget data.
			$all_sidebars = (object) $all_sidebars;
			foreach ( $all_sidebars as $widgets_key => $widgets ) {
				foreach ( $widgets as $widget_key => $widget ) {

					// Found slug in current menu list.
					if ( isset( $widget->nav_menu ) ) {
						$menu_id = array_search( $widget->nav_menu, $menu_locations, true );
						if ( ! empty( $menu_id ) ) {
							$all_sidebars->$widgets_key->$widget_key->nav_menu = $menu_id;
						}
					}
				}
			}

			return $all_sidebars;
		}

		/**
		 * Download File Into Uploads Directory
		 *
		 * @param  string $file Download File URL.
		 * @param  array  $overrides Upload file arguments.
		 * @param  int    $timeout_seconds Timeout in downloading the XML file in seconds.
		 * @return array        Downloaded file data.
		 */
		public static function download_file( $file = '', $overrides = array(), $timeout_seconds = 300 ) {

			require_once ABSPATH . 'wp-admin/includes/file.php';

			$temp_file = download_url( $file, $timeout_seconds );

			if ( is_wp_error( $temp_file ) ) {
				return array(
					'success' => false,
					'data'    => $temp_file->get_error_message(),
				);
			}

			$file_args = array(
				'name'     => basename( $file ),
				'tmp_name' => $temp_file,
				'error'    => 0,
				'size'     => filesize( $temp_file ),
			);

			$defaults = array(
				'test_form'   => false,
				'test_size'   => true,
				'test_upload' => true,
				'mimes'       => array(
					'xml'  => 'text/xml',
					'json' => 'text/plain',
				),
			);

			$overrides = wp_parse_args( $overrides, $defaults );

			$results = wp_handle_sideload( $file_args, $overrides );

			demo_importer_plus_error_log( wp_json_encode( $results ) );

			if ( isset( $results['error'] ) ) {
				return array(
					'success' => false,
					'data'    => $results,
				);
			}

			return array(
				'success' => true,
				'data'    => $results,
			);
		}

		/**
		 * Downloads an image from the specified URL.
		 *
		 * @param string $file The image file path.
		 * @return array An array of image data.
		 */
		public static function sideload_image( $file ) {
			$data = new stdClass();

			if ( ! function_exists( 'media_handle_sideload' ) ) {
				require_once ABSPATH . 'wp-admin/includes/media.php';
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/image.php';
			}

			if ( ! empty( $file ) ) {

				// Set variables for storage, fix file filename for query strings.
				preg_match( '/[^\?]+\.(jpe?g|jpe|svg|gif|png)\b/i', $file, $matches );
				$file_array         = array();
				$file_array['name'] = basename( $matches[0] );

				// Download file to temp location.
				$file_array['tmp_name'] = download_url( $file );

				// If error storing temporarily, return the error.
				if ( is_wp_error( $file_array['tmp_name'] ) ) {
					return $file_array['tmp_name'];
				}

				// Do the validation and storage stuff.
				$id = media_handle_sideload( $file_array, 0 );

				// If error storing permanently, unlink.
				if ( is_wp_error( $id ) ) {
					unlink( $file_array['tmp_name'] );
					return $id;
				}

				$meta                = wp_get_attachment_metadata( $id );
				$data->attachment_id = $id;
				$data->url           = wp_get_attachment_url( $id );
				$data->thumbnail_url = wp_get_attachment_thumb_url( $id );
				$data->height        = isset( $meta['height'] ) ? $meta['height'] : '';
				$data->width         = isset( $meta['width'] ) ? $meta['width'] : '';
			}

			return $data;
		}

		/**
		 * Checks to see whether a string is an image url or not.
		 *
		 * @param string $string The string to check.
		 * @return bool Whether the string is an image url or not.
		 */
		public static function is_image_url( $string = '' ) {
			if ( is_string( $string ) ) {

				if ( preg_match( '/\.(jpg|jpeg|svg|png|gif)/i', $string ) ) {
					return true;
				}
			}

			return false;
		}

	}

	/**
	 * Starting this by calling 'get_instance()' method
	 */
	Demo_Importer_Plus_Sites_Helper::get_instance();

endif;
