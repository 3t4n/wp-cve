<?php
/**
 * Widget Data exporter class.
 *
 * @package Demo Importer Plus
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Widget Data exporter class.
 */
class Demo_Importer_Plus_Widget_Importer {

	/**
	 * Instance of Demo_Importer_Plus_Widget_Importer
	 *
	 * @var Demo_Importer_Plus_Widget_Importer
	 */
	private static $instance = null;

	/**
	 * Instance
	 *
	 * @return object
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Available widgets
	 */
	public function wie_available_widgets() {

		global $wp_registered_widget_controls;

		$widget_controls = $wp_registered_widget_controls;

		$available_widgets = array();

		foreach ( $widget_controls as $widget ) {

			if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[ $widget['id_base'] ] ) ) {

				$available_widgets[ $widget['id_base'] ]['id_base'] = $widget['id_base'];
				$available_widgets[ $widget['id_base'] ]['name']    = $widget['name'];

			}
		}

		return apply_filters( 'wie_available_widgets', $available_widgets );
	}

	/**
	 * Import widget JSON data
	 *
	 * @param object $data JSON widget data from .wie file.
	 */
	public function import_widgets_data( $data ) {

		global $wp_registered_sidebars;

		if ( empty( $data ) || ! is_object( $data ) ) {
			wp_die(
				esc_html__( 'Import data could not be read. Please try a different file.', 'demo-importer-plus' ),
				'',
				array(
					'back_link' => true,
				)
			);
		}

		do_action( 'wie_before_import' );
		$data = apply_filters( 'wie_import_data', $data );

		$available_widgets = $this->wie_available_widgets();

		$widget_instances = array();
		foreach ( $available_widgets as $widget_data ) {
			$widget_instances[ $widget_data['id_base'] ] = get_option( 'widget_' . $widget_data['id_base'] );
		}

		$results = array();

		foreach ( $data as $sidebar_id => $widgets ) {

			if ( 'wp_inactive_widgets' === $sidebar_id ) {
				continue;
			}

			if ( isset( $wp_registered_sidebars[ $sidebar_id ] ) ) {
				$sidebar_available    = true;
				$use_sidebar_id       = $sidebar_id;
				$sidebar_message_type = 'success';
				$sidebar_message      = '';
			} else {
				$sidebar_available    = false;
				$use_sidebar_id       = 'wp_inactive_widgets';
				$sidebar_message_type = 'error';
				$sidebar_message      = esc_html__( 'Widget area does not exist in theme (using Inactive)', 'demo-importer-plus' );
			}

			$results[ $sidebar_id ]['name']         = ! empty( $wp_registered_sidebars[ $sidebar_id ]['name'] ) ? $wp_registered_sidebars[ $sidebar_id ]['name'] : $sidebar_id; // sidebar name if theme supports it; otherwise ID.
			$results[ $sidebar_id ]['message_type'] = $sidebar_message_type;
			$results[ $sidebar_id ]['message']      = $sidebar_message;
			$results[ $sidebar_id ]['widgets']      = array();

			$terms_mappings = get_option( '_demo_importer_terms_mapping', array() );

			$nav_menu_terms = isset( $terms_mappings['nav_menu'] ) ? (array) $terms_mappings['nav_menu'] : array();

			foreach ( $widgets as $widget_instance_id => $widget ) {

				$fail = false;

				$id_base            = preg_replace( '/-[0-9]+$/', '', $widget_instance_id );
				$instance_id_number = str_replace( $id_base . '-', '', $widget_instance_id );

				if ( ! $fail && ! isset( $available_widgets[ $id_base ] ) ) {
					$fail                = true;
					$widget_message_type = 'error';
					$widget_message      = esc_html__( 'Site does not support widget', 'demo-importer-plus' ); // explain why widget not imported.
				}

				$widget = apply_filters( 'wie_widget_settings', $widget ); // object.

				$widget = json_decode( wp_json_encode( $widget ), true );

				$widget = apply_filters( 'wie_widget_settings_array', $widget );

				if ( ! $fail && isset( $widget_instances[ $id_base ] ) ) {

					$sidebars_widgets = get_option( 'sidebars_widgets' );
					$sidebar_widgets  = isset( $sidebars_widgets[ $use_sidebar_id ] ) ? $sidebars_widgets[ $use_sidebar_id ] : array(); // check Inactive if that's where will go.

					$single_widget_instances = ! empty( $widget_instances[ $id_base ] ) ? $widget_instances[ $id_base ] : array();
					foreach ( $single_widget_instances as $check_id => $check_widget ) {

						if ( in_array( "$id_base-$check_id", $sidebar_widgets, true ) && (array) $widget === $check_widget ) {

							$fail                = true;
							$widget_message_type = 'warning';
							$widget_message      = esc_html__( 'Widget already exists', 'demo-importer-plus' ); // explain why widget not imported.

							break;

						}
					}
				}

				if ( ! $fail ) {

					$single_widget_instances   = get_option( 'widget_' . $id_base ); // all instances for that widget ID base, get fresh every time.
					$single_widget_instances   = ! empty( $single_widget_instances ) ? $single_widget_instances : array(
						'_multiwidget' => 1,
					);
					$single_widget_instances[] = $widget;

					end( $single_widget_instances );
					$new_instance_id_number = key( $single_widget_instances );

					if ( '0' === strval( $new_instance_id_number ) ) {
						$new_instance_id_number                             = 1;
						$single_widget_instances[ $new_instance_id_number ] = $single_widget_instances[0];
						unset( $single_widget_instances[0] );
					}

					if ( isset( $single_widget_instances['_multiwidget'] ) ) {
						$multiwidget = $single_widget_instances['_multiwidget'];
						unset( $single_widget_instances['_multiwidget'] );
						$single_widget_instances['_multiwidget'] = $multiwidget;
					}

					if ( is_array( $single_widget_instances  ) ) {
						foreach( $single_widget_instances as &$widget_instance ) {
							if ( isset( $widget_instance[ 'nav_menu' ] ) && isset( $nav_menu_terms[ $widget_instance[ 'nav_menu' ] ] ) ) {
								$widget_instance['nav_menu'] = $nav_menu_terms[ $widget_instance[ 'nav_menu' ] ];
							}
						}
					}

					$result = update_option( 'widget_' . $id_base, $single_widget_instances );

					$sidebars_widgets = get_option( 'sidebars_widgets' ); // which sidebars have which widgets, get fresh every time.

					if ( ! $sidebars_widgets ) {
						$sidebars_widgets = array();
					}

					$new_instance_id                       = $id_base . '-' . $new_instance_id_number;
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
					do_action( 'wie_after_widget_import', $after_widget_import );

					if ( $sidebar_available ) {
						$widget_message_type = 'success';
						$widget_message      = esc_html__( 'Imported', 'demo-importer-plus' );
					} else {
						$widget_message_type = 'warning';
						$widget_message      = esc_html__( 'Imported to Inactive', 'demo-importer-plus' );
					}
				}

				$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['name']         = isset( $available_widgets[ $id_base ]['name'] ) ? $available_widgets[ $id_base ]['name'] : $id_base; // widget name or ID if name not available (not supported by site).
				$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['title']        = ! empty( $widget['title'] ) ? $widget['title'] : esc_html__( 'No Title', 'demo-importer-plus' ); // show "No Title" if widget instance is untitled.
				$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message_type'] = $widget_message_type;
				$results[ $sidebar_id ]['widgets'][ $widget_instance_id ]['message']      = $widget_message;

			}
		}

		do_action( 'wie_after_import' );

		return apply_filters( 'wie_import_results', $results );

	}

}
