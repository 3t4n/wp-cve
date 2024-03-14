<?php
/*
Plugin Name: Data Source for Contact Form 7
Plugin URI: https://cf7-datasource.dwbooster.com/
Description: Data Source for Contact Form 7 plugin allows populating the "Contact Form 7" fields (text, email, URL, drop-down menu, ...) with data stored in external data sources, like a database, CSV file, users information, post data, taxonomies, and JSON objects.
Version: 1.1.11
Author: CodePeople.net
Author URI: https://cf7-datasource.dwbooster.com/about-us
License: GPL
*/

require_once dirname( __FILE__ ) . '/banner.php';
$codepeople_promote_banner_plugins['cf7-datasource'] = array(
	'plugin_name' => 'Data Source for Contact Form 7',
	'plugin_url'  => 'https://wordpress.org/support/plugin/cf7-data-source/reviews/#new-post',
);

// Feedback system
require_once 'feedback/cp-feedback.php';
new CF7DS_FEEDBACK( basename( dirname( __FILE__ ) ), __FILE__, 'https://cf7-datasource.dwbooster.com/contact-us' );

if ( ! class_exists( 'CF7_DATASOURCE' ) ) {
	class CF7_DATASOURCE {

		// Properties
		public static $plugin     = 'cf7-datasource';
		private static $plugin_id = 639;
		public static $version    = '1.1.11';
		private static $obj;

		private $debug = true;
		private $errors;
		private $cf7;    // CF7 id
		private $prefix; // Prefix for fields
		private $ds_manager;
		private $form_tags;
		private $test_shortcode = false;

		// Class methods
		public static function install( $networkwide ) {
			global $wpdb;
			$obj = new CF7_DATASOURCE();
			if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				if ( $networkwide ) {
					$old_blog = $wpdb->blogid;

					$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
					foreach ( $blogids as $blog_id ) {
						switch_to_blog( $blog_id );
						$obj->validL();
						$this->_install();
					}
					switch_to_blog( $old_blog );
					return;
				}
			}
			$obj->validL();
		} // End install

		public static function plugins_loaded() {
			// Create instance
			self::$obj = new CF7_DATASOURCE();
		}

		public static function init() {
			// This patch allows loading dynamic data in dropdown and checkbox fields.
			remove_action(	'wpcf7_swv_create_schema',	'wpcf7_swv_add_select_enum_rules', 20 );
			remove_action(	'wpcf7_swv_create_schema',	'wpcf7_swv_add_checkbox_enum_rules', 20 );

			// Check if it is calling data
			if ( ! empty( $_REQUEST['cf7_recordset'] ) ) {
				$data = self::sanitize_deep( $_REQUEST );
				self::$obj->get_data( $data );
			} else {
				self::$obj->deleteL();
			}
		}

		/********************** INSTANCE METHODS AND PROPERTIES **********************/
		public function __construct() {
			 // add_filter( 'wpcf7_autop_or_not', '__return_false' );
			$banner = dirname( __FILE__ ) . '/banner.php';
			if ( file_exists( $banner ) ) {
				require_once $banner;
			}

			// Integration with third-party plugins
			require_once dirname( __FILE__ ) . '/includes/cf7-datasource-integration.inc.php';

			require_once dirname( __FILE__ ) . '/datasources/cf7-datasource-manager.php';
			$this->ds_manager = new CF7_DATASOURCE_MANAGER( $this );
			$this->errors     = array();

			add_shortcode( 'cf7-recordset', array( $this, 'cf7_recordset' ) );
			add_shortcode( 'cf7-link-field', array( $this, 'cf7_link' ) );

			// Public form
			add_filter(
				'wpcf7_contact_form',
				function( $cf7 ) {
					$this->generate_prefix( $cf7->id() );
				}
			);
			add_filter(
				'wpcf7_form_elements',
				function( $content ) {
					$cf7 = WPCF7_ContactForm::get_current();
					$this->generate_prefix( $cf7->id() );
					return do_shortcode( $content );
				}
			);

			// Load add-ons
			require_once dirname( __FILE__ ) . '/includes/cf7-datasource-addons.inc.php';
			CF7_DATASOURCE_ADDONS::load();

			// Load editor
			if ( is_admin() ) {

				delete_option( self::$plugin . '_LICENSE' );
				delete_option( self::$plugin . '_last' );

				if ( $this->user_can() ) {
					$path = dirname( __FILE__ ) . '/admin/cf7-datasource-editor.php';
					if ( file_exists( $path ) ) {
						require_once $path;
						$cf7_datasource_editor = new CF7_DATASOURCE_EDITOR( $this->ds_manager );}
				}
				if ( current_user_can( get_option( 'cf7-datasource-capability', 'manage_options' ) ) ) {
					add_action( 'admin_menu', array( $this, 'admin_menu' ), 10, 0 );
				}
			}

			// Testing recordset
			if (
				is_admin() &&
				$this->user_can() &&
				isset( $_REQUEST['cf7-recordset-test'] ) &&
				! empty( $_REQUEST['post'] )
			) {
				$this->test_recordset( ( isset( $_REQUEST['cf7-recordset-test'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cf7-recordset-test'] ) ) : '' ), ( isset( $_REQUEST['post'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['post'] ) ) : '' ) );
			}
		} // End __construct

		/*************************** PUBLIC METHODS  ***************************/

		public function get_current_post_id() {
			 return ! empty( $_REQUEST['cf7_ds_post'] ) && is_numeric( $_REQUEST['cf7_ds_post'] )
					? intval( $_REQUEST['cf7_ds_post'] )
					: 0;

		} // End get_current_post

		public static function sanitize_html( $value ) {
			$allowed_tags = wp_kses_allowed_html( 'post' );
			$value        = stripslashes_deep( $value );
			$value        = wp_kses( $value, $allowed_tags );
			// the str_replace is a patch to solve an issue with the data: part in signature fields
			// that are removed by wp_kse.
			return str_replace(
				array( '"image/svg+xml;base64', '"image/png;base64' ),
				array( '"data:image/svg+xml;base64', '"data:image/png;base64' ),
				$value
			);
		} // End sanitize_html

		public static function sanitize_deep( $values ) {
			$values = stripslashes_deep( $values );
			if ( is_string( $values ) ) {
				$values = sanitize_text_field( $values );
			} elseif ( is_array( $values ) ) {
				foreach ( $values as $key => $value ) {
					$values[ $key ] = self::sanitize_deep( $value );
				}
			}
			return $values;
		} // End sanitize_deep

		public function get_data( $data ) {
			error_reporting( E_ERROR | E_PARSE );
			$this->generate_prefix( isset( $data['cf7'] ) ? @intval( $data['cf7'] ) : 0 );
			$recordset = sanitize_key( $data['cf7_recordset'] );
			$attrs     = get_transient( $this->prefix . $recordset );

			if ( false != $attrs ) {
				$attrs = $this->prefill_recordset( $attrs );
				array_walk(
					$attrs,
					function( &$v, $i ) {
						$v = html_entity_decode( $v );
					}
				);
				$this->debug = ! empty( $attrs['debug'] ) ? true : false;
				$records     = $this->ds_manager->ds( $attrs, $data );
			} else {
				$this->error_log( 'The recordset settings return false' );
			}

			$result = new stdClass();
			$errors = $this->error_message();

			if ( ! empty( $errors ) ) {
				$result->error = $errors;
			} else {
				$result->data = $records;
			}

			print( json_encode( $result ) );
			exit;
		} // End get_data

		public function test_recordset( $recordset, $cf7 ) {
			error_reporting( E_ERROR | E_PARSE );
			$this->test_shortcode = true;
			if ( empty( $recordset ) ) {
				print '{data:[]}';
			} else {
				remove_all_shortcodes();
				add_shortcode( 'cf7-recordset', array( $this, 'cf7_recordset' ) );
				$attrs = shortcode_parse_atts( $recordset );
				$this->generate_prefix( $cf7 );
				if ( is_array( $attrs ) && ! empty( $attrs['id'] ) ) {
					do_shortcode( $recordset );
					$this->get_data(
						array(
							'cf7_recordset' => $attrs['id'],
							'cf7'           => $cf7,
						)
					);
				}
			}
			exit;
		} // End test_recordset

		public function cf7_recordset( $attrs, $content = '' ) {
			if ( ! $this->test_shortcode ) {
				$this->css();
			}

			$attrs       = $this->prefill_recordset( $attrs );
			$this->debug = isset( $attrs['debug'] ) ? @intval( $attrs['debug'] ) : 0;
			$attrs['id'] = trim( $attrs['id'] );

			if ( ! empty( $attrs['id'] ) ) {
				delete_transient( $this->prefix . $attrs['id'] );
				if ( set_transient( $this->prefix . $attrs['id'], $attrs, 24 * 60 * 60 ) ) {
					// encode script output code
					if ( ! $this->test_shortcode ) {
						$this->js_recordset( $attrs );
					}
				} else {
					$this->error_log( 'The data source field ' . $attrs['id'] . ' could not be registered' );
				}
			} else {
				$this->error_log( 'Field id is required' );
			}

			return '';
		} // End cf7_recordset

		public function cf7_link( $attrs, $content = '' ) {
			$this->css();
			$attrs       = $this->prefill_link( $attrs );
			$this->debug = isset( $attrs['debug'] ) ? @intval( $attrs['debug'] ) : 0;

			$attrs['recordset'] = trim( $attrs['recordset'] );
			$attrs['field']     = trim( $attrs['field'] );

			if ( ! empty( $attrs['recordset'] ) && ! empty( $attrs['field'] ) ) {
				$attrs['default'] = $this->get_default( $attrs['field'] );

				// encode script output code
				$this->js_link( $attrs );
			} else {
				$this->error_log( 'The field and recordset attributes are required' );
			}
			return '';
		} // End cf7_link

		public function error_log( $mssg ) {
			$this->errors[] = $mssg;
			if (
				$this->debug &&
				! empty( $mssg )
			) {
				error_log( 'Data Source for Contact Form 7: ' . $mssg );
			}
		} // End error_log


		public function admin_menu() {
			add_submenu_page(
				'wpcf7',
				__( 'Extensions', 'cf7-datasource' ),
				__( 'Extensions', 'cf7-datasource' ),
				'manage_options',
				'cf7-datasource-addons',
				array( $this, 'addons_page' )
			);
		} // End admin_menu

		public function addons_page() {
			 wp_enqueue_style( 'cf7-addons', plugins_url( '/admin/add-ons.css', __FILE__ ), array(), self::$version );
			?>
			<div class="wrap">
				<h1 style="margin-bottom:20px;">CF7 Data Source  -  <?php esc_html_e( 'Extensions', 'cf7-datasource' ); ?></h1>
				<div class="cf7-addons-container">
				<?php

				if ( isset( $_REQUEST['cf7-data-source-addon'] ) ) {
					$addon_slug = sanitize_text_field( wp_unslash( $_REQUEST['cf7-data-source-addon'] ) );
				}

				if ( empty( $addon_slug ) || ! CF7_DATASOURCE_ADDONS::exists( $addon_slug ) ) {
					CF7_DATASOURCE_ADDONS::list();
				} else {
					CF7_DATASOURCE_ADDONS::settings( $addon_slug );
					print '<p><a href="admin.php?page=cf7-datasource-addons">' . esc_html__( '<< Returns to the add-ons list', 'cf7-datasource' ) . '</a></p>';
				}
				?>
				</div>
			</div>
			<?php
		} // End addons_page

		/*************************** PRIVATE METHODS ***************************/

		private function error_message() {
			return implode( ' ', $this->errors );
		} // End error_message

		private function get_default( $name ) {
			if ( empty( $this->form_tags ) ) {
				$wcf7 = WPCF7_ContactForm::get_current();
				if ( ! empty( $wcf7 ) ) {
					$this->form_tags = $wcf7->scan_form_tags();
				}
			}

			foreach ( $this->form_tags as $tag_obj ) {
				if ( $tag_obj->name == $name && method_exists( $tag_obj, 'get_default_option' ) ) {
					return $tag_obj->get_default_option( null, array( 'multiple' => true ) );
				}
			}

			return '';
		} // End get_default

		private function user_can() {
			/**
				 create_sites    // Super admin role
				 manage_options  // Administrator role
				 delete_pages    // Editor role
			 */

			return current_user_can( get_option( 'cf7-datasource-capability', 'delete_pages' ) );
		} // End user_can

		private function prefill_recordset( $settings ) {
			return shortcode_atts(
				$this->ds_manager->default_datasource_attributes(),
				$settings
			);
		} // End prefill_recordset

		private function prefill_link( $settings ) {

			// Processes the attributes with numeric indexes
			foreach ( $settings as $key => $value ) {
				if ( is_numeric( $key ) ) {
					if ( 'keep-options' == $value ) {
						$settings['keep-options'] = 1;
					}
				}
			}

			return shortcode_atts(
				array(
					'recordset' => '', // Recordset field name
					'field'     => '', // Namem of the field to populate
					'value'     => '', // Column name for values
					'text'      => '', // Column name for texts
					'other-attributes' => '', // Additional attributes
					'condition' => '', // Condition for filtering the rows
					'limit'     => '',  // Number of records
					'keep-options' => 0, // Keep options in radio buttons, checkboxes and drop-down menu
				),
				$settings
			);
		} // End prefill_link

		private function generate_prefix( $cf7 ) {
			$this->cf7    = $cf7;
			$this->prefix = 'cf7_' . $this->cf7 . '_';
		} // End generate_prefix

		private function js() {
			 wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'cf7_datasource_script', plugins_url( '/assets/script.js', __FILE__ ), array( 'jquery' ), self::$version );
		} // End js

		private function css() {
			wp_enqueue_style( 'cf7_datasource_style', plugins_url( '/assets/style.css', __FILE__ ), array(), self::$version );
		} // End css

		private function js_recordset( $attrs ) {
			$this->js();

			// Extract variables
			$str = implode(
				' ',
				array(
					isset( $attrs['query'] ) ? $attrs['query'] : '',
					isset( $attrs['condition'] ) ? $attrs['condition'] : '',
					isset( $attrs['variable'] ) ? $attrs['variable'] : '',
					isset( $attrs['url'] ) ? $attrs['url'] : '',
					isset( $attrs['parameters'] ) ? $attrs['parameters'] : '',
					isset( $attrs['in'] ) ? $attrs['in'] : '',
				)
			);

			$js_settings        = $this->extract_vars( $str );
			$js_settings['cf7'] = $this->cf7;

			if ( ! empty( $attrs['callback'] ) ) {
				$js_settings['callback'] = esc_js( $attrs['callback'] );
			}

			if ( ! empty( $attrs['type'] ) ) {
				$js_settings['type'] = esc_js( $attrs['type'] );

				if ( $attrs['type'] == 'client' ) {
					if ( ! empty( $attrs['function'] ) ) {
						$js_settings['function'] = esc_js( $attrs['function'] );
					}

					if ( ! empty( $attrs['parameters'] ) ) {
						$js_settings['parameters'] = esc_js( $attrs['parameters'] );
					}
				}
			}

			wp_add_inline_script( 'cf7_datasource_script', 'if(typeof cf7_datasource_recordsets == "undefined") cf7_datasource_recordsets=[]; cf7_datasource_recordsets.push({"id":"' . esc_js( $attrs['id'] ) . '", "settings":' . json_encode( $js_settings ) . ', "post":' . ( get_the_ID() * 1 ) . '});', 'before' );
		} // End js_recordset

		private function js_link( $attrs ) {
			$this->js();
			// Extract variables
			$str         = $attrs['condition'];
			$js_settings = $this->extract_vars( $str );
			$attrs       = array_merge( $attrs, $js_settings );
			wp_add_inline_script( 'cf7_datasource_script', 'if(typeof cf7_datasource_links == "undefined") cf7_datasource_links=[]; cf7_datasource_links.push({"settings":' . json_encode( $attrs ) . ', "post":' . ( get_the_ID() * 1 ) . '});', 'before' );
		} // End js_link

		private function extract_vars( $str ) {
			 $variables = array(
				 'fields'    => array(),
				 'variables' => array(),
			 );

			 if ( preg_match_all( '/\{field\.([^\}]+)\}/i', $str, $m_fields ) ) {
				 $variables['fields'] = $m_fields[1];
			 }
			 if ( preg_match_all( '/\{var\.([^\}]+)\}/i', $str, $m_vars ) ) {
				 $variables['variables'] = $m_vars[1];
			 }

			 return $variables;
		} // End extract_vars

		public function validL() {
			$l = get_option( self::$plugin . '_LICENSE', '' );
			if ( $l ) {
				$v = 'https://wordpress.dwbooster.com/licensesystem/code/v.php?id=' . self::$plugin_id . '&l=' . $l . '&w=' . ( isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '' ) . '&a=info';
				try {
					$contents = wp_remote_get( $v );
					if ( strpos( $contents['body'], 'not valid' ) ) {
						update_option( self::$plugin . '_LICENSE', '' ); } else {
						return true;
						}
				} catch ( Exception $e ) {
					error_log( $e->getMessage() );
				}
			}
			return false;
		} // End validL

		public function deleteL() {
			 $key = self::$plugin . '_fixlk';
			$val  = '';
			if ( ! empty( $_GET[ $key ] ) ) {
				$val = sanitize_text_field( wp_unslash( $_GET[ $key ] ) );
			} elseif ( ! empty( $_POST[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$val = sanitize_text_field( wp_unslash( $_POST[ $key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification
			}

			if ( ! empty( $val ) && get_option( self::$plugin . '_LICENSE', '' ) == $val ) {
				update_option( self::$plugin . '_LICENSE', '' );
				$key = self::$plugin . '_fixf';
				$val = '';
				if ( ! empty( $_GET[ $key ] ) ) {
					$val = sanitize_text_field( wp_unslash( $_GET[ $key ] ) );
				} elseif ( ! empty( $_POST[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
					$val = sanitize_text_field( wp_unslash( $_POST[ $key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification
				}

				if ( 'ok' == $val ) {
					@unlink( dirname( __FILE__ ) . '/datasources/cf7-datasource-advanced.php' );
				}
				echo 'Ok, LK fixed.';
				exit;
			}
		} // End deleteL
	} // End class CF7_DATASOURCE

	add_action( 'plugins_loaded', array( 'CF7_DATASOURCE', 'plugins_loaded' ), 11 );
	add_action( 'init', array( 'CF7_DATASOURCE', 'init' ), 11 );
	register_activation_hook( __FILE__, array( 'CF7_DATASOURCE', 'install' ) );
}
