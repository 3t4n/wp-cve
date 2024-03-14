<?php
/**
 * Class Functions
 */

$pluginNameClass = pluginNameClass();

if (class_exists($pluginNameClass)) {


if ( ! function_exists( 'WPSECTION_Functions' ) ) {
	class WPSECTION_Functions {

		public $active_elements = null;

		/**
		 * WPSECTION_Functions constructor.
		 */
		function __construct() {
			$this->set_active_elements();
		}


		/**
		 * Return if an element is active or not
		 *
		 * @param string $element_id
		 *
		 * @return bool
		 */
		function is_element_active( $element_id = '' ) {

			if ( empty( $element_id ) ) {
				return false;
			}

			return in_array( sprintf( 'wpsection_%s', $element_id ), $this->get_active_elements() );
		}


		/**
		 * Return active elements
		 *
		 * @return null
		 */
		function get_active_elements() {
			return $this->active_elements;
		}


		/**
		 * Set all active elements
		 */
		function set_active_elements() {
			$elements_ext_active = $this->get_option( 'wpsection_elements_ext_active', array_keys( $this->get_widgets_options( 'external' ) ) );
			$elements_ext_active = empty( $elements_ext_active ) || ! is_array( $elements_ext_active ) ? array() : $elements_ext_active;
			$elements_active     = $this->get_option( 'wpsection_elements_active', array_keys( $this->get_widgets_options( 'self' ) ) );
			$elements_active     = array_merge( $elements_active, $elements_ext_active );

			$this->active_elements = empty( $elements_active ) || ! is_array( $elements_active ) ? array() : $elements_active;
		}


		/**
		 * Return template by id and group name
		 *
		 * @param string $template_id
		 * @param string $template_group
		 *
		 * @return mixed|void
		 */
		function get_template_by_id( $template_id = '', $template_group = '' ) {

			$templates = wpsection()->get_plugin_data( 'templates' );
			$template  = isset( $templates[ $template_group ]['pages'][ $template_id ] ) ? $templates[ $template_group ]['pages'][ $template_id ] : array();

			return apply_filters( 'wpsection_filters_template_by_id', $template, $template_id, $template_group );
		}


		/**
		 * Return template group thumbnail
		 *
		 * @param array $template_group
		 *
		 * @return mixed|void
		 */
		
		
		
		function get_template_group_thumb( $template_group = array() ) {

			$pages    = wpsection()->get_settings_atts( 'pages', array(), $template_group );
			$template = reset( $pages );
			$thumb    = wpsection()->get_settings_atts( 'thumb', '', $template );

			return apply_filters( 'wpsection_filters_get_template_group_thumb', $thumb );
		}


		/**
		 * Return required data from api response
		 *
		 * @param string $data_for
		 *
		 * @return mixed|string
		 */

		function get_plugin_data( $data_for = 'templates' ) {

			// Check option for saved data
			$wpsection_api_response = wpsection()->get_option( 'wpsection_api_response', array() );
			$wpsection_api_response = ! is_array( $wpsection_api_response ) ? array() : $wpsection_api_response;

			// If empty get from remote server
			if ( empty( $wpsection_api_response ) ) {
				$wpsection_api_response = wpsection()->get_plugin_data_from_api();

				update_option( 'wpsection_api_response', $wpsection_api_response );
			}

			return wpsection()->get_settings_atts( $data_for, array(), $wpsection_api_response );
		}


		/**
		 * Return plugin data from remote server
		 *
		 * @return array
		 */
		function get_plugin_data_from_api() {
			if ( is_wp_error( $api_response = wp_remote_get( sprintf( '%s/wp-json/wpsection/plugin-data', WPSECTION_API_URL ) ) ) ) {
				return array();
			}

			// Parsing response data
			$response_data = wp_remote_retrieve_body( $api_response );
			$response_data = json_decode( $response_data, true );

			return json_decode( $response_data, true );
		}


		/**
		 * include widget class
		 *
		 * @param string $widget_slug
		 */
		function include_widget_class( $widget_slug = '' ) {

			if ( empty( $widget_slug ) || ! $widget_slug ) {
				return;
			}

			global $current_widget;

			$widget_class_file = sprintf( '%swidgets/%s/widget.php', WPSECTION_PLUGIN_DIR, $widget_slug );
			$widget_class_file = apply_filters( 'wpsection_filters_widget_class_file', $widget_class_file, $widget_slug );

			if ( file_exists( $widget_class_file ) ) {
				include_once( $widget_class_file );
			}
		}


		/**
		 * Return arrays of widgets
		 *
		 * @return mixed|void
		 */
		function get_widgets() {

			$widgets = [
				

			];
		

			return apply_filters( 'wpsection_filters_widgets', $widgets );
		}


		/**
		 * Return widget options for specific type
		 *
		 * @param string $for
		 *
		 * @return mixed|void
		 */
		function get_widgets_options( $for = '' ) {

			$widgets = array();
			foreach ( $this->get_widgets() as $widget_slug => $widget ) {
				if ( $for === $this->get_settings_atts( 'type', '', $widget ) ) {
					$widgets[ $widget_slug ] = $this->get_settings_atts( 'title', '', $widget );
				} else if ( empty( $for ) ) {
					$widgets[ $widget_slug ] = $this->get_settings_atts( 'title', '', $widget );
				}
			}

			return apply_filters( 'wpsection_filters_widgets_options', $widgets, $for );
		}


		/**
		 * Return post ids
		 *
		 * @param string $content_type
		 *
		 * @return array|int[]|WP_Post[]
		 */
		function get_post_ids( $content_type = '' ) {

			$post_ids = array();

			if ( $content_type == 'by_posts_category' ) {

				foreach ( wpsection()->get_settings_atts( '_category' ) as $category_id ) {
					$query_string = sprintf( 'fields=ids&posts_per_page=-1&category=%s', $category_id );
					$post_ids     = array_merge( $post_ids, get_posts( $query_string ) );
				}
			} elseif ( $content_type == 'by_posts_tags' ) {
				echo '<pre>';
				print_r( wpsection()->get_settings_atts( 'tag_ids' ) );
				echo '</pre>';

				$post_ids = get_posts( array(
					'post_type'      => 'post',
					'posts_per_page' => - 1,
					'fields'         => 'ids',
					'tax_query'      => array(
						array(
							'taxonomy' => 'post_tag',
							'field'    => 'term_id',
							'terms'    => wpsection()->get_settings_atts( 'tag_ids' ),
						),
					)
				) );

			} elseif ( $content_type == 'by_latest_posts' ) {
				$post_ids = get_posts( array(
					'posts_per_page' => - 1,
					'offset'         => 0,
					'orderby'        => 'post_date',
					'fields'         => 'ids',
					'order'          => 'DESC',
					'post_type'      => 'post',
					'post_status'    => 'publish'
				) );

			} elseif ( $content_type == 'by_custom' ) {
				$post_ids = explode( ',', wpsection()->get_settings_atts( 'custom_post_ids' ) );
			} else {
				$post_ids = wpsection()->get_settings_atts( 'post_ids' );
			}

			return $post_ids;

		}


		/**
		 * Return advanced addons as array
		 *
		 * @return mixed|void
		 */
		function get_advanced_addons() {
			return apply_filters( 'wpsection_filters_advanced_addons', array(
				'sales_notification' => esc_html__( 'Sales Notification', 'element-plus' ),
				'post_duplicator'    => esc_html__( 'Post Duplicator', 'element-plus' ),
			) );
		}


		/**
		 * Print notice to the admin bar
		 *
		 * @param string $message
		 * @param bool $is_success
		 * @param bool $is_dismissible
		 */
		function print_notice( $message = '', $is_success = true, $is_dismissible = true ) {

			if ( empty ( $message ) ) {
				return;
			}

			if ( is_bool( $is_success ) ) {
				$is_success = $is_success ? 'success' : 'error';
			}

			printf( '<div class="notice notice-%s %s"><p>%s</p></div>', $is_success, $is_dismissible ? 'is-dismissible' : '', $message );
		}


		/**
		 * Return Shortcode Arguments
		 *
		 * @param string $key
		 * @param string $default
		 * @param array $args
		 *
		 * @return mixed|string
		 */
		function get_settings_atts( $key = '', $default = '', $args = array() ) {

			global $widget_settings;

			$args    = empty( $args ) ? $widget_settings : $args;
			$default = is_array( $default ) && ! empty( $default ) ? array() : $default;
			$default = ! is_array( $default ) && empty( $default ) ? '' : $default;
			$key     = empty( $key ) ? '' : $key;

			if ( isset( $args[ $key ] ) && ! empty( $args[ $key ] ) ) {
				return $args[ $key ];
			}

			return $default;
		}


		/**
		 * Return option value
		 *
		 * @param string $option_key
		 * @param string $default_val
		 *
		 * @return mixed|string|void
		 */
		function get_option( $option_key = '', $default_val = '' ) {

			if ( empty( $option_key ) ) {
				return '';
			}

			$option_val = get_option( $option_key, $default_val );
			$option_val = empty( $option_val ) ? $default_val : $option_val;

			return apply_filters( 'wpsection_filters_option_' . $option_key, $option_val );
		}


		/**
		 * Return Post Meta Value
		 *
		 * @param bool $meta_key
		 * @param bool $post_id
		 * @param string $default
		 *
		 * @return mixed|string|void
		 */
		function get_meta( $meta_key = false, $post_id = false, $default = '' ) {

			if ( ! $meta_key ) {
				return '';
			}

			$post_id    = ! $post_id ? get_the_ID() : $post_id;
			$meta_value = get_post_meta( $post_id, $meta_key, true );
			$meta_value = empty( $meta_value ) ? $default : $meta_value;

			return apply_filters( 'woc_filters_get_meta', $meta_value, $meta_key, $post_id, $default );
		}


		/**
		 * PB_Settings Class
		 *
		 * @param array $args
		 *
		 * @return PB_Settings
		 */
		function PB_Settings( $args = array() ) {

			return new PB_Settings( $args );
		}


		/**
		 * Generate and return widget styles
		 *
		 * @param int $style_count
		 *
		 * @return array
		 */
		function load_widget_styles( $style_count = 1 ) {

			$styles = array();
			for ( $index = 1; $index <= $style_count; $index ++ ) {
				$styles[ $index ] = sprintf( esc_html__( 'Style %s', 'wpsection' ), $index );
			}

			return $styles;
		}
	}
	
	new WPSECTION_Functions();
}
	
}