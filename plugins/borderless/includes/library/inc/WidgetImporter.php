<?php

namespace LIBRARY;

class WidgetImporter {
	public static function import( $widget_import_file_path ) {
		$results       = array();
		$library          = BorderlessLibraryImporter::get_instance();
		$log_file_path = $library->get_log_file_path();

		if ( ! empty( $widget_import_file_path ) ) {
			$results = self::import_widgets( $widget_import_file_path );
		}

		if ( is_wp_error( $results ) ) {
			$error_message = $results->get_error_message();

			$library->append_to_frontend_error_messages( $error_message );

			Helpers::append_to_file(
				$error_message,
				$log_file_path,
				esc_html__( 'Importing widgets', 'borderless' )
			);
		}
		else {
			ob_start();
				self::format_results_for_log( $results );
			$message = ob_get_clean();

			$log_added = Helpers::append_to_file(
				$message,
				$log_file_path,
				esc_html__( 'Importing widgets' , 'borderless' )
			);
		}

	}


	private static function import_widgets( $data_file ) {
		$data = self::process_import_file( $data_file );

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		return self::import_data( $data );
	}

	private static function process_import_file( $file ) {
		if ( ! file_exists( $file ) ) {
			return new \WP_Error(
				'widget_import_file_not_found',
				__( 'Error: Widget import file could not be found.', 'borderless' )
			);
		}

		$data = Helpers::data_from_file( $file );

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		return json_decode( $data );
	}

	private static function import_data( $data ) {
		global $wp_registered_sidebars;

		if ( empty( $data ) || ! is_object( $data ) ) {
			return new \WP_Error(
				'corrupted_widget_import_data',
				__( 'Error: Widget import data could not be read. Please try a different file.', 'borderless' )
			);
		}

		Helpers::do_action( 'library/widget_importer_before_widgets_import' );
		$data = Helpers::apply_filters( 'library/before_widgets_import_data', $data );

		$available_widgets = self::available_widgets();

		$widget_instances = array();

		foreach ( $available_widgets as $widget_data ) {
			$widget_instances[ $widget_data['id_base'] ] = get_option( 'widget_' . $widget_data['id_base'] );
		}

		$results = array();

		foreach ( $data as $sidebar_id => $widgets ) {
			if ( 'wp_inactive_widgets' == $sidebar_id ) {
				continue;
			}

			if ( isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
				$sidebar_available    = true;
				$use_sidebar_id       = $sidebar_id;
				$sidebar_message_type = 'success';
				$sidebar_message      = '';
			}
			else {
				$sidebar_available    = false;
				$use_sidebar_id       = 'wp_inactive_widgets';
				$sidebar_message_type = 'error';
				$sidebar_message      = __( 'Sidebar does not exist in theme (moving widget to Inactive)', 'borderless' );
			}

			$results[ $sidebar_id ]['name']         = ! empty( $wp_registered_sidebars[ $sidebar_id ]['name'] ) ? $wp_registered_sidebars[ $sidebar_id ]['name'] : $sidebar_id;
			$results[ $sidebar_id ]['message_type'] = $sidebar_message_type;
			$results[ $sidebar_id ]['message']      = $sidebar_message;
			$results[ $sidebar_id ]['widgets']      = array();

			foreach ( $widgets as $widget_instance_id => $widget ) {
				$fail = false;

				$id_base            = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
				$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

				if ( ! $fail && ! isset( $available_widgets[ $id_base ] ) ) {
					$fail                = true;
					$widget_message_type = 'error';
					$widget_message      = __( 'Site does not support widget', 'borderless' );
				}


				$widget = Helpers::apply_filters( 'library/widget_settings', $widget ); 
				$widget = json_decode( json_encode( $widget ), true );
				$widget = Helpers::apply_filters( 'library/widget_settings_array', $widget );

				if ( ! $fail && isset( $widget_instances[ $id_base ] ) ) {

					$sidebars_widgets = get_option( 'sidebars_widgets' );
					$sidebar_widgets  = isset( $sidebars_widgets[ $use_sidebar_id ] ) ? $sidebars_widgets[ $use_sidebar_id ] : array(); 
					$single_widget_instances = ! empty( $widget_instances[ $id_base ] ) ? $widget_instances[ $id_base ] : array();
					foreach ( $single_widget_instances as $check_id => $check_widget ) {
						if ( in_array( "$id_base-$check_id", $sidebar_widgets ) && (array) $widget == $check_widget ) {
							$fail                = true;
							$widget_message_type = 'warning';
							$widget_message      = __( 'Widget already exists', 'borderless' );

							break;
						}
					}
				}

				if ( ! $fail ) {
					$single_widget_instances   = get_option( 'widget_' . $id_base ); 
					$single_widget_instances   = ! empty( $single_widget_instances ) ? $single_widget_instances : array( '_multiwidget' => 1 ); 
					$single_widget_instances[] = $widget; 
					end( $single_widget_instances );
					$new_instance_id_number = key( $single_widget_instances );
					if ( '0' === strval( $new_instance_id_number ) ) {
						$new_instance_id_number                           = 1;
						$single_widget_instances[ $new_instance_id_number ] = $single_widget_instances[0];
						unset( $single_widget_instances[0] );
					}

					if ( isset( $single_widget_instances['_multiwidget'] ) ) {
						$multiwidget = $single_widget_instances['_multiwidget'];
						unset( $single_widget_instances['_multiwidget'] );
						$single_widget_instances['_multiwidget'] = $multiwidget;
					}

					update_option( 'widget_' . $id_base, $single_widget_instances );

					$sidebars_widgets = get_option( 'sidebars_widgets' ); 
					if ( ! $sidebars_widgets ) {
						$sidebars_widgets = array();
					}

					$new_instance_id = $id_base . '-' . $new_instance_id_number; 
					$sidebars_widgets[ $use_sidebar_id ][] = $new_instance_id; 
					update_option( 'sidebars_widgets', $sidebars_widgets ); 

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
					Helpers::do_action( 'library/widget_importer_after_single_widget_import', $after_widget_import );

					if ( $sidebar_available ) {
						$widget_message_type = 'success';
						$widget_message      = __( 'Imported', 'borderless' );
					}
					else {
						$widget_message_type = 'warning';
						$widget_message      = __( 'Imported to Inactive', 'borderless' );
					}
				}

				$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['name']         = isset( $available_widgets[ $id_base ]['name'] ) ? $available_widgets[ $id_base ]['name'] : $id_base; 
				$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['title']        = ! empty( $widget['title'] ) ? $widget['title'] : __( 'No Title', 'borderless' );
				$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message_type'] = $widget_message_type;
				$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message']      = $widget_message;

			}
		}


		Helpers::do_action( 'library/widget_importer_after_widgets_import' );

		return Helpers::apply_filters( 'library/widget_import_results', $results );
	}

	private static function available_widgets() {
		global $wp_registered_widget_controls;

		$widget_controls   = $wp_registered_widget_controls;
		$available_widgets = array();

		foreach ( $widget_controls as $widget ) {
			if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[ $widget['id_base'] ] ) ) {
				$available_widgets[ $widget['id_base'] ]['id_base'] = $widget['id_base'];
				$available_widgets[ $widget['id_base'] ]['name']    = $widget['name'];
			}
		}

		return Helpers::apply_filters( 'library/available_widgets', $available_widgets );
	}


	private static function format_results_for_log( $results ) {
		if ( empty( $results ) ) {
			esc_html_e( 'No results for widget import!', 'borderless' );
		}

		foreach ( $results as $sidebar ) {
			echo esc_html( $sidebar['name'] ) . ' : ' . esc_html( $sidebar['message'] ) . PHP_EOL . PHP_EOL;
			foreach ( $sidebar['widgets'] as $widget ) {
				echo esc_html( $widget['name'] ) . ' - ' . esc_html( $widget['title'] ) . ' - ' . esc_html( $widget['message'] ) . PHP_EOL;
			}
			echo PHP_EOL;
		}
	}
}
