<?php
/*
* class: Properties_Plugin_Admin
* since: 0.0.1
* description: Creates admin interface in WordPress
* version: 0.1.4
* text-domain: properties
*/
if( !class_exists('Properties_Plugin_Admin') ) {

	class Properties_Plugin_Admin {

		public static $_instance;
		public static $version;
		public static $ns;
		public static $screen;

		public function __construct() {

			/* define base variables */
			self::$version = '0.1.4';
			self::$ns = 'properties_plugin';

			/* load admin */
			add_action( 'admin_menu', array( __CLASS__, 'add_settings_page' ), 10, 0 );
			/* register settings */
			add_action( 'admin_init', array( __CLASS__, 'register_settings' ), 0, 0 );

			/* property columns */
			add_filter( 'manage_property_posts_columns', array( __CLASS__, 'add_property_image_column' ), 10, 1 );
			add_filter( 'manage_property_posts_custom_column', array( __CLASS__, 'display_property_image_column' ), 10, 2 );
			/* property table scripts */
			add_action( 'load-edit.php', array( __CLASS__, 'hook_property_list_table_scripts' ), 10, 0 );

			/* properties_plugin_admin__construct hook */
			do_action('properties_plugin_admin__construct');
		}

		/* register settings */
		public static function register_settings() {
			/* property permalinks */
			foreach( array_keys( self::get_permalink_fields() ) as $permalink_field ) {
				register_setting( self::$ns, "pp_{$permalink_field}_slug", array( __CLASS__, 'sanitize_slug' ) );
			}
			/* uninstall */
			register_setting( self::$ns, 'pp_uninstall', 'absint' );
			register_setting( self::$ns, 'pp_reset_settings', array( __CLASS__, 'reset_settings_on_sanitize' ) );

			/* properties_plugin_admin_register_settings hook */
			do_action( 'properties_plugin_admin_register_settings', self::$ns );
		}

		/* hooks property list table scripts */
		public static function hook_property_list_table_scripts() {
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'load_property_list_table_scripts' ), 10, 0 );
		}

		/* hooks setting page scripts */
		public static function hook_setting_page_scripts() {
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'load_settings_page_scripts' ), 10, 0 );
		}

		/* loads property list table scripts */
		public static function load_property_list_table_scripts() {
			global $post_type;
			if( 'property' === $post_type ) {
				wp_enqueue_style( 'property-list-table', PRPRTS_URL . 'admin/css/property-list-table.css', null, self::$version, 'all' );
			}
		}

		/* loads settings page scripts */
		public static function load_settings_page_scripts() {
			wp_enqueue_script( 'settings-tabs', PRPRTS_URL . 'admin/js/settings-tabs.js', array('jquery'), self::$version, true );
			wp_enqueue_style( 'settings-tabs', PRPRTS_URL . 'admin/css/settings-tabs.css', null, self::$version, 'all' );
			/* fire hook */
			do_action('properties_plugin_admin_load_settings_page_scripts');
		}

		/* registers admin page */
		public static function add_settings_page() {

			/* add settings page */
			self::$screen = add_submenu_page(
				'edit.php?post_type=property',
				__( 'Properties Plugin Settings', 'properties' ),
				__( 'Settings', 'properties' ),
				'manage_options',
				self::$ns,
				array( __CLASS__, 'settings_page' )
			);

			/* load admin tabs script and styles */
			add_action( 'load-'. self::$screen, array( __CLASS__, 'hook_setting_page_scripts' ), 10, 0 );

			/* display settings errors */
			add_action( 'admin_footer-'. self::$screen, 'settings_errors', 10, 0 );

			/* CREATE SETTINGS SECTIONS */

			/* fire properties_plugin_admin_add_settings_page hook to register sections by addons */
			do_action( 'properties_plugin_admin_add_settings_page', self::$ns, self::$screen );

			/* permalinks */
			add_settings_section(
				'permalinks',
				__( 'Property Permalinks', 'properties' ),
				array( __CLASS__, 'property_permalinks_section' ),
				self::$ns
			);
			$permalink_fields = self::get_permalink_fields();
			foreach( $permalink_fields as $permalink_field => $permalink_field_label ) {
				$permalink_field_id = "pp_{$permalink_field}_slug";
				add_settings_field(
					$permalink_field_id,
					$permalink_field_label,
					array( __CLASS__, 'display_permalink_field' ),
					self::$ns,
					'permalinks',
					array(
						'label_for' => $permalink_field_id,
						'label' => $permalink_field_label,
						'key' => $permalink_field
					)
				);
			}

			/* uninstall */
			add_settings_section(
				'uninstall',
				__( 'Uninstall', 'properties' ),
				array( __CLASS__, 'property_uninstall_section' ),
				self::$ns
			);
			add_settings_field(
				'pp_uninstall',
				__( 'Clean Database', 'properties' ),
				array( __CLASS__, 'display_checkbox_field' ),
				self::$ns,
				'uninstall',
				array(
					'label_for' => 'pp_uninstall',
					'label' => __( 'Clean database upon plugin deinstallation?', 'properties' ),
					'description' => __( 'DO NOT CHECK THIS BOX! Unless you are ready to get rid of this plugin.', 'properties' )
				)
			);
			add_settings_field(
				'pp_reset_settings',
				__( 'Reset Settings', 'properties' ),
				array( __CLASS__, 'display_checkbox_field' ),
				self::$ns,
				'uninstall',
				array(
					'label_for' => 'pp_reset_settings',
					'label' => __( 'Reset plugin settings?', 'properties' ),
					'description' => __( 'If you wish to reset plugin settings to their defaults, please check this box and save changes.', 'properties' )
				)
			);
		}

		/* displays description for the property_uninstall_section */
		public static function property_uninstall_section() {
			printf( '<p>%s</p>',
				__( 'Here you may reset plugin settings or prepare the plugin for removal.', 'properties' )
			);
		}

		/* displays permalink field */
		public static function display_permalink_field( $args ) {
			extract( $args, EXTR_SKIP );
			$default_slugs = apply_filters( 'pp_display_permalink_field_default_slugs', array(
				'property' => _x( 'properties', 'URL friendly slug', 'properties' ),
				'property_type' => _x( 'property-types', 'URL friendly slug', 'properties' ),
				'property_area' => _x( 'property-areas', 'URL friendly slug', 'properties' ),
				'property_complex' => _x( 'property-complexes', 'URL friendly slug', 'properties' ),
				'property_collection' => _x( 'property-collections', 'URL friendly slug', 'properties' )
			) );
			printf( '%1$s/<input id="%2$s" type="text" name="%2$s" value="%3$s" class="medium-text code" placeholder="%4$s" />/%5$s/',
				get_bloginfo('url'),
				$label_for,
				esc_attr( get_option( $label_for ) ),
				esc_attr( ( ( empty( $default_slugs[ $key ] ) ) ? '' : $default_slugs[ $key ] ) ),
				str_replace( '_', '-', "%{$key}%" )
			);
		}

		/* returns permalink fields array */
		public static function get_permalink_fields() {
			return array_merge(
				array( 'property' => _x( 'Properties', 'Post Type General Name', 'properties' ) ),
				Properties_Plugin_Content_Type::get_property_taxonomy_full_names()
			);
		}

		/* displays description for property permalinks section and flushes permalinks */
		public static function property_permalinks_section() {
			flush_rewrite_rules();
			printf( '<p>%s</p>',
				__( 'Here you may adjust permalinks for your property pages.', 'properties' )
			);
		}

		/* displays setting sections */
		public static function do_settings_sections( $page ) {
			global $wp_settings_sections, $wp_settings_fields;
			if ( ! isset( $wp_settings_sections[$page] ) ) {
				return;
			}
			echo '<div class="settings-sections-container">';
			foreach ( (array) $wp_settings_sections[$page] as $section ) {
				printf( '<div class="settings-section" id="settings-section-%1$s">',
					$section['id']
				);
				if ( $section['title'] ) {
					echo "<h2>{$section['title']}</h2>\n";
				}
				if ( $section['callback'] ) {
					call_user_func( $section['callback'], $section );
				}
				if ( isset( $wp_settings_fields ) && isset( $wp_settings_fields[$page] ) && isset( $wp_settings_fields[$page][$section['id']] ) ) {
					echo '<table class="form-table">';
					do_settings_fields( $page, $section['id'] );
					echo '</table>';
				}
				echo '</div>';
			}
			echo '</div>';
		}

		/* displays admin page */
		public static function settings_page() {
			printf( '<div class="wrap %1$s-settings-page"><h2>%2$s</h2><form action="%3$s" method="post">',
				self::$ns,
				esc_html( get_admin_page_title() ),
				admin_url('options.php')
			);
			settings_fields( self::$ns );
			self::do_settings_sections( self::$ns );
			submit_button();
			echo '</form></div>';
		}

		/* displays image column in property list table */
		public static function display_property_image_column( $column_name, $id ) {
			if( 'image' === $column_name ) {
				echo ( 0 < ( $img_id = absint( get_post_thumbnail_id( $id ) ) ) ) ? wp_get_attachment_image( $img_id, array( 44, 44 ), false, array( 'class' => 'post-image' ) ) : '';
			}
		}

		/* adds image column to property list table */
		public static function add_property_image_column( $columns = array() ) {
			$term_image_column = array(
				'image' => sprintf( '<span class="vers dashicons dashicons-format-image" title="%1$s"><span class="screen-reader-text">%1$s</span></span>', esc_attr( __( 'Image', 'properties' ) ) )
			);
			return array_merge( array_slice( $columns, 0, 2, true ), $term_image_column, array_slice( $columns, 2, ( count( $columns ) - 2 ), true ) );
		}

		/*** HELPERS ***/

		/* displays checkbox field */
		public static function display_checkbox_field( $args ) {
			extract( $args, EXTR_SKIP );
			printf( '<input id="%1$s" type="checkbox" name="%1$s" value="1" %2$s/> <label for="%1$s">%3$s</label>%4$s',
				$label_for,
				checked( 1, absint( get_option( $label_for ) ), false ),
				$label,
				( empty( $description ) ? '' : sprintf( '<p class="description">%s</p>', $description ) )
			);
		}

		public static function reset_settings() {
			global $new_whitelist_options;
			foreach( $new_whitelist_options[ self::$ns ] as $option ) {
				delete_option( $option );
			}
		}

		public static function reset_settings_on_sanitize( $data ) {
			if( !empty( $data ) ) {
				self::reset_settings();
				add_settings_error(
					self::$ns,
					esc_attr( 'settings_updated' ),
					__( 'Settings reset the their defaults.', 'properties' ),
					'updated'
				);
			}
			return null;
		}

		public static function sanitize_slug( $input = '' ) {
			return self::is_empty( $slug = sanitize_title_with_dashes( $input ) ) ? null : $slug;
		}

		public static function is_empty( $data = null ) {
			return empty( $data );
		}

		public static function load_admin() {
			return self::getInstance();
		}

		public static function getInstance() {
			if ( !isset( self::$_instance ) ) {
				self::$_instance = new self();
			}
			/* properties_plugin_admin_init hook */
			do_action( 'properties_plugin_admin_init', self::$_instance );
			/* return instance */
			return self::$_instance;
		}

	}

}