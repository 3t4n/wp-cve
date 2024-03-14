<?php
/**
 * Hester Demo Library. Install a copy of a Hester demo to your website.
 *
 * @package Hester Core
 * @author  Peregrine Themes <peregrinethemes@gmail.com>
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hester Core Widgets Import/Export.
 *
 * @since 1.0.0
 * @package Hester Core
 */
final class Hester_Widgets_Import_Export {

	/**
	 * Singleton instance of the class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	private static $instance;

	/**
	 * Main Hester_Widgets_Import_Export Instance.
	 *
	 * @since 1.0.0
	 * @return Hester_Widgets_Import_Export
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Hester_Widgets_Import_Export ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 */
	public function __construct() {
	}

	/**
	 * Import widgets.
	 *
	 * @since  1.0.0
	 * @param  (Array) $data widgets data from the demo.
	 */
	public static function import( $data ) {

		global $wp_registered_sidebars;

		// Have valid data?
		// If no data or could not decode.
		if ( empty( $data ) || ! is_array( $data ) ) {
			return new WP_Error( esc_html__( 'Import data could not be read. Please try a different file.', 'hester-core' ) );
		}

		// Hook before import.
		do_action( 'hester_core_before_widgets_import' );

		$data = apply_filters( 'hester_core_widgets_import_data', $data );

		// Get all available widgets site supports.
		$available_widgets = self::get_available_widgets();

		// Get all existing widget instances.
		$widget_instances = array();
		foreach ( $available_widgets as $widget_data ) {
			$widget_instances[ $widget_data['id_base'] ] = get_option( 'widget_' . $widget_data['id_base'] );
		}

		// Begin results.
		$results = array();

		// Loop import data's sidebars.
		foreach ( $data as $sidebar_id => $widgets ) {

			// Skip inactive widgets (should not be in export file).
			if ( 'wp_inactive_widgets' === $sidebar_id ) {
				continue;
			}

			// Check if sidebar is available on this site.
			// Otherwise add widgets to inactive, and say so.
			if ( isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
				$sidebar_available    = true;
				$use_sidebar_id       = $sidebar_id;
				$sidebar_message_type = 'success';
				$sidebar_message      = '';
			} else {
				$sidebar_available    = false;
				$use_sidebar_id       = 'wp_inactive_widgets'; // Add to inactive if sidebar does not exist in theme.
				$sidebar_message_type = 'error';
				$sidebar_message      = esc_html__( 'Widget area does not exist in theme (using Inactive)', 'hester-core' );
			}

			// Result for sidebar
			// Sidebar name if theme supports it; otherwise ID.
			$results[ $sidebar_id ]['name']         = ! empty( $wp_registered_sidebars[ $sidebar_id ]['name'] ) ? $wp_registered_sidebars[ $sidebar_id ]['name'] : $sidebar_id;
			$results[ $sidebar_id ]['message_type'] = $sidebar_message_type;
			$results[ $sidebar_id ]['message']      = $sidebar_message;
			$results[ $sidebar_id ]['widgets']      = array();

			// Loop widgets.
			foreach ( $widgets as $widget_instance_id => $widget ) {

				$fail = false;

				// Get id_base (remove -# from end) and instance ID number.
				$id_base            = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
				$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

				// Does site support this widget?
				if ( ! $fail && ! isset( $available_widgets[ $id_base ] ) ) {
					$fail                = true;
					$widget_message_type = 'error';
					$widget_message      = esc_html__( 'Site does not support widget', 'hester-core' ); // Explain why widget not imported.
				}

				// Filter to modify settings object before conversion to array and import
				// Leave this filter here for backwards compatibility with manipulating objects (before conversion to array below)
				// Ideally the newer hester_core_widget_settings_array below will be used instead of this.
				$widget = apply_filters( 'hester_core_widget_settings', $widget );

				// Convert multidimensional objects to multidimensional arrays
				// Some plugins like Jetpack Widget Visibility store settings as multidimensional arrays
				// Without this, they are imported as objects and cause fatal error on Widgets page
				// If this creates problems for plugins that do actually intend settings in objects then may need to consider other approach: https://wordpress.org/support/topic/problem-with-array-of-arrays
				// It is probably much more likely that arrays are used than objects, however.
				$widget = json_decode( wp_json_encode( $widget ), true );

				// Convert navigation menu slug to menu ID.
				if ( 'nav_menu' === $id_base && isset( $widget['nav_menu'] ) ) {
					$nav_menu_object = wp_get_nav_menu_object( $widget['nav_menu'] );
					if ( $nav_menu_object ) {
						$widget['nav_menu'] = $nav_menu_object->term_id;
					}
				}

				// Sideload Image widget.
				if ( 'media_image' === $id_base && isset( $widget['url'] ) ) {
					$image = (object) hester_demo_importer()->sideload_image( $widget['url'] );

					if ( ! is_wp_error( $image ) ) {
						if ( isset( $image->attachment_id ) && ! empty( $image->attachment_id ) ) {
							$widget['attachment_id'] = $image->attachment_id;
							$widget['url']           = $image->url;
						}
					}
				}

				// Sideload images in text widget.
				if ( 'text' === $id_base && isset( $widget['text'] ) ) {

					preg_match( '@src="([^"]+)"@', $widget['text'], $image_src );

					if ( isset( $image_src[1] ) ) {
						$image_src = $image_src[1];
					} elseif ( isset( $image_src[0] ) ) {
						$image_src = $image_src[0];
					} else {
						$image_src = false;
					}

					if ( $image_src ) {

						$image = (object) hester_demo_importer()->sideload_image( $image_src );

						if ( ! is_wp_error( $image ) ) {
							if ( isset( $image->url ) && ! empty( $image->url ) ) {
								$widget['text'] = str_replace( $image_src, $image->url, $widget['text'] );
							}
						}
					}
				}

				// Filter to modify settings array
				// This is preferred over the older hester_core_widget_settings filter above
				// Do before identical check because changes may make it identical to end result (such as URL replacements).
				$widget = apply_filters( 'hester_core_widget_settings_array', $widget );

				// Does widget with identical settings already exist in same sidebar?
				if ( ! $fail && isset( $widget_instances[ $id_base ] ) ) {

					// Get existing widgets in this sidebar.
					$sidebars_widgets = get_option( 'sidebars_widgets' );
					$sidebar_widgets  = isset( $sidebars_widgets[ $use_sidebar_id ] ) ? $sidebars_widgets[ $use_sidebar_id ] : array(); // Check Inactive if that's where will go.

					// Loop widgets with ID base.
					$single_widget_instances = ! empty( $widget_instances[ $id_base ] ) ? $widget_instances[ $id_base ] : array();
					foreach ( $single_widget_instances as $check_id => $check_widget ) {

						// Is widget in same sidebar and has identical settings?
						if ( in_array( "$id_base-$check_id", $sidebar_widgets, true ) && (array) $widget === $check_widget ) {

							$fail = true;

							$widget_message_type = 'warning';

							// Explain why widget not imported.
							$widget_message = esc_html__( 'Widget already exists', 'hester-core' );

							break;

						}
					}
				}

				// No failure.
				if ( ! $fail ) {

					// Add widget instance.
					$single_widget_instances   = get_option( 'widget_' . $id_base ); // All instances for that widget ID base, get fresh every time.
					$single_widget_instances   = ! empty( $single_widget_instances ) ? $single_widget_instances : array(
						'_multiwidget' => 1, // Start fresh if have to.
					);
					$single_widget_instances[] = $widget; // Add it.

					// Get the key it was given.
					end( $single_widget_instances );
					$new_instance_id_number = key( $single_widget_instances );

					// If key is 0, make it 1
					// When 0, an issue can occur where adding a widget causes data from other widget to load,
					// and the widget doesn't stick (reload wipes it).
					if ( '0' === strval( $new_instance_id_number ) ) {
						$new_instance_id_number = 1;

						$single_widget_instances[ $new_instance_id_number ] = $single_widget_instances[0];
						unset( $single_widget_instances[0] );
					}

					// Move _multiwidget to end of array for uniformity.
					if ( isset( $single_widget_instances['_multiwidget'] ) ) {
						$multiwidget = $single_widget_instances['_multiwidget'];
						unset( $single_widget_instances['_multiwidget'] );
						$single_widget_instances['_multiwidget'] = $multiwidget;
					}

					// Update option with new widget.
					update_option( 'widget_' . $id_base, $single_widget_instances );

					// Assign widget instance to sidebar.
					// Which sidebars have which widgets, get fresh every time.
					$sidebars_widgets = get_option( 'sidebars_widgets' );

					// Avoid rarely fatal error when the option is an empty string
					// https://github.com/churchthemes/widget-importer-exporter/pull/11.
					if ( ! $sidebars_widgets ) {
						$sidebars_widgets = array();
					}

					// Use ID number from new widget instance.
					$new_instance_id = $id_base . '-' . $new_instance_id_number;

					// Add new instance to sidebar.
					$sidebars_widgets[ $use_sidebar_id ][] = $new_instance_id;

					// Save the amended data.
					update_option( 'sidebars_widgets', $sidebars_widgets );

					// After widget import action.
					$after_widget_import = array(
						'sidebar'           => $use_sidebar_id,
						'sidebar_old'       => $sidebar_id,
						'widget'            => $widget,
						'widget_type'       => $id_base,
						'widget_id'         => $new_instance_id,
						'widget_id_old'     => $widget_instance_id,
						'widget_id_num'     => $new_instance_id_number,
						'widget_id_num_old' => $instance_id_number,
					);
					do_action( 'hester_core_after_widget_import', $after_widget_import );

					// Success message.
					if ( $sidebar_available ) {
						$widget_message_type = 'success';
						$widget_message      = esc_html__( 'Imported', 'hester-core' );
					} else {
						$widget_message_type = 'warning';
						$widget_message      = esc_html__( 'Imported to Inactive', 'hester-core' );
					}
				}

				// Result for widget instance.
				$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['name']         = isset( $available_widgets[ $id_base ]['name'] ) ? $available_widgets[ $id_base ]['name'] : $id_base; // Widget name or ID if name not available (not supported by site).
				$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['title']        = ! empty( $widget['title'] ) ? $widget['title'] : esc_html__( 'No Title', 'hester-core' ); // Show "No Title" if widget instance is untitled.
				$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message_type'] = $widget_message_type;
				$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message']      = $widget_message;
			}
		}

		// Hook after import.
		do_action( 'hester_core_after_widgets_import' );

		// Return results.
		return apply_filters( 'hester_core_widgets_import_results', $results );
	}

	/**
	 * Export widgets.
	 *
	 * @since 1.0.0
	 */
	public static function export() {

		// Export data.
		$data = self::generate_export_data();
		$data = apply_filters( 'hester_widgets_export_data', $data );
		$data = wp_json_encode( $data );

		$filesize = strlen( $data );

		// Set the download headers.
		nocache_headers();
		header( 'Content-disposition: attachment; filename=widgets.json' );
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Expires: 0' );
		header( 'Content-Length: ' . $filesize );

		// Output file contents.
		echo $data; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		// Start the download.
		die();
	}

	/**
	 * Generate export data.
	 *
	 * @since 1.0.0
	 * @return string Export file contents
	 */
	public static function generate_export_data() {

		// Get all available widgets site supports.
		$available_widgets = self::get_available_widgets();

		// Get all widget instances for each widget.
		$widget_instances = array();

		// Loop widgets.
		foreach ( $available_widgets as $widget_data ) {

			// Get all instances for this ID base.
			$instances = get_option( 'widget_' . $widget_data['id_base'] );

			// Have instances.
			if ( ! empty( $instances ) ) {

				// Loop instances.
				foreach ( $instances as $instance_id => $instance_data ) {

					// Key is ID (not _multiwidget).
					if ( is_numeric( $instance_id ) ) {

						// Change menu ID to menu slug for Navigation Menu widget.
						if ( 'nav_menu' === $widget_data['id_base'] ) {
							if ( isset( $instance_data['nav_menu'] ) ) {
								$nav_menu_object = wp_get_nav_menu_object( $instance_data['nav_menu'] );
								if ( $nav_menu_object ) {
									$instance_data['nav_menu'] = $nav_menu_object->slug;
								}
							}
						}

						$unique_instance_id = $widget_data['id_base'] . '-' . $instance_id;

						$widget_instances[ $unique_instance_id ] = $instance_data;
					}
				}
			}
		}

		// Gather sidebars with their widget instances.
		$sidebars_widgets          = get_option( 'sidebars_widgets' );
		$sidebars_widget_instances = array();
		foreach ( $sidebars_widgets as $sidebar_id => $widget_ids ) {

			// Skip inactive widgets.
			if ( 'wp_inactive_widgets' === $sidebar_id ) {
				continue;
			}

			// Skip if no data or not an array (array_version).
			if ( ! is_array( $widget_ids ) || empty( $widget_ids ) ) {
				continue;
			}

			// Loop widget IDs for this sidebar.
			foreach ( $widget_ids as $widget_id ) {

				// Is there an instance for this widget ID?
				if ( isset( $widget_instances[ $widget_id ] ) ) {

					// Add to array.
					$sidebars_widget_instances[ $sidebar_id ][ $widget_id ] = $widget_instances[ $widget_id ];

				}
			}
		}

		return $sidebars_widget_instances;
	}

	/**
	 * Available widgets.
	 *
	 * Gather site's widgets into array with ID base, name, etc.
	 * Used by export and import functions.
	 *
	 * @since 1.0.0
	 * @global array $wp_registered_widget_updates
	 * @return array Widget information
	 */
	public static function get_available_widgets() {

		global $wp_registered_widget_controls;

		$widget_controls = $wp_registered_widget_controls;

		$available_widgets = array();

		foreach ( $widget_controls as $widget ) {

			// No duplicates.
			if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[ $widget['id_base'] ] ) ) {
				$available_widgets[ $widget['id_base'] ]['id_base'] = $widget['id_base'];
				$available_widgets[ $widget['id_base'] ]['name']    = $widget['name'];
			}
		}

		return apply_filters( 'hester_core_available_widgets', $available_widgets );
	}
}
