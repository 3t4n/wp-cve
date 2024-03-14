<?php
/**
 *  Framework actions file.
 *
 * @package    team-free
 * @subpackage team-free/framework
 */

use ShapedPlugin\WPTeam\Admin\Framework\Classes\SPF_TEAM;

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'TEAMFW_Options' ) ) {
	/**
	 *
	 * Options Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class TEAMFW_Options extends TEAMFW_Abstract {

		/**
		 * Unique
		 *
		 * @var string
		 */
		public $unique = '';
		/**
		 * Notice
		 *
		 * @var string
		 */
		public $notice = '';
		/**
		 * Abstract
		 *
		 * @var string
		 */
		public $abstract = 'options';
		/**
		 * Sections
		 *
		 * @var array
		 */
		public $sections = array();
		/**
		 * Options
		 *
		 * @var array
		 */
		public $options = array();
		/**
		 * Errors
		 *
		 * @var array
		 */
		public $errors = array();
		/**
		 * Pre_tabs
		 *
		 * @var array
		 */
		public $pre_tabs = array();
		/**
		 * Pre_fields
		 *
		 * @var array
		 */
		public $pre_fields = array();
		/**
		 * Pre_sections
		 *
		 * @var array
		 */
		public $pre_sections = array();
		/**
		 * Default args.
		 *
		 * @var array
		 */
		public $args = array(

			// framework title.
			'framework_title'         => '',
			'framework_class'         => '',

			// menu settings.
			'menu_title'              => '',
			'menu_slug'               => '',
			'menu_type'               => 'menu',
			'menu_icon'               => null,
			'menu_position'           => null,
			'menu_hidden'             => false,
			'menu_parent'             => '',
			'sub_menu_title'          => '',

			// menu extras.
			'show_bar_menu'           => true,
			'show_sub_menu'           => true,
			'show_in_network'         => true,
			'show_in_customizer'      => false,

			'show_search'             => true,
			'show_reset_all'          => true,
			'show_reset_section'      => true,
			'show_footer'             => true,
			'show_all_options'        => true,
			'show_form_warning'       => true,
			'sticky_header'           => true,
			'save_defaults'           => true,
			'ajax_save'               => true,
			'form_action'             => '',

			// admin bar menu settings.
			'admin_bar_menu_icon'     => '',
			'admin_bar_menu_priority' => 50,

			// footer.
			'footer_text'             => '',
			'footer_after'            => '',
			'footer_credit'           => '',

			// database model.
			'database'                => '', // options, transient, theme_mod, network.
			'transient_time'          => 0,

			// contextual help.
			'contextual_help'         => array(),
			'contextual_help_sidebar' => '',

			// typography options.
			'enqueue_webfont'         => true,
			'async_webfont'           => false,

			// others.
			'output_css'              => true,

			// theme.
			'nav'                     => 'normal',
			'theme'                   => 'dark',
			'class'                   => '',

			// external default values.
			'defaults'                => array(),

		);

		/**
		 * Run framework construct.
		 *
		 * @param  mixed $key key.
		 * @param  mixed $params params.
		 * @return void
		 */
		public function __construct( $key, $params = array() ) {

			$this->unique   = $key;
			$this->args     = apply_filters( "spf_{$this->unique}_args", wp_parse_args( $params['args'], $this->args ), $this );
			$this->sections = apply_filters( "spf_{$this->unique}_sections", $params['sections'], $this );

			// run only is admin panel options, avoid performance loss.
			$this->pre_tabs     = $this->pre_tabs( $this->sections );
			$this->pre_fields   = $this->pre_fields( $this->sections );
			$this->pre_sections = $this->pre_sections( $this->sections );

			$this->get_options();
			$this->set_options();
			$this->save_defaults();

			add_action( 'admin_menu', array( &$this, 'add_admin_menu' ) );
			add_action( 'admin_bar_menu', array( &$this, 'add_admin_bar_menu' ), $this->args['admin_bar_menu_priority'] );
			add_action( 'wp_ajax_spf_' . $this->unique . '_ajax_save', array( &$this, 'ajax_save' ) );

			if ( 'network' === $this->args['database'] && ! empty( $this->args['show_in_network'] ) ) {
				add_action( 'network_admin_menu', array( &$this, 'add_admin_menu' ) );
			}

			// wp enqeueu for typography and output css.
			parent::__construct();

		}

		/**
		 * Instance
		 *
		 * @param  mixed $key key.
		 * @param  mixed $params params.
		 * @return statement
		 */
		public static function instance( $key, $params = array() ) {
			return new self( $key, $params );
		}

		/**
		 * Pre_tabs
		 *
		 * @param  array $sections sections.
		 * @return array
		 */
		public function pre_tabs( $sections ) {

			$result  = array();
			$parents = array();
			$count   = 100;

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['parent'] ) ) {
					$section['priority']             = ( isset( $section['priority'] ) ) ? $section['priority'] : $count;
					$parents[ $section['parent'] ][] = $section;
					unset( $sections[ $key ] );
				}
				$count++;
			}

			foreach ( $sections as $key => $section ) {
				$section['priority'] = ( isset( $section['priority'] ) ) ? $section['priority'] : $count;
				if ( ! empty( $section['id'] ) && ! empty( $parents[ $section['id'] ] ) ) {
					$section['subs'] = wp_list_sort( $parents[ $section['id'] ], array( 'priority' => 'ASC' ), 'ASC', true );
				}
				$result[] = $section;
				$count++;
			}

			return wp_list_sort( $result, array( 'priority' => 'ASC' ), 'ASC', true );
		}

		/**
		 * Pre_fields
		 *
		 * @param  mixed $sections sections.
		 * @return array
		 */
		public function pre_fields( $sections ) {

			$result = array();

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['fields'] ) ) {
					foreach ( $section['fields'] as $field ) {
						$result[] = $field;
					}
				}
			}

			return $result;
		}

		/**
		 * Pre_sections
		 *
		 * @param  mixed $sections section.
		 * @return array
		 */
		public function pre_sections( $sections ) {

			$result = array();

			foreach ( $this->pre_tabs as $tab ) {
				if ( ! empty( $tab['subs'] ) ) {
					foreach ( $tab['subs'] as $sub ) {
						$sub['ptitle'] = $tab['title'];
						$result[]      = $sub;
					}
				}
				if ( empty( $tab['subs'] ) ) {
					$result[] = $tab;
				}
			}

			return $result;
		}

		/**
		 * Add admin bar menu.
		 *
		 * @param object $wp_admin_bar admin bar.
		 * @return void
		 */
		public function add_admin_bar_menu( $wp_admin_bar ) {

			if ( is_network_admin() && ( 'network' === $this->args['database'] || true !== $this->args['show_in_network'] ) ) {
				return;
			}

			if ( ! empty( $this->args['show_bar_menu'] ) && empty( $this->args['menu_hidden'] ) ) {

				global $submenu;

				$menu_slug = $this->args['menu_slug'];
				$menu_icon = ( ! empty( $this->args['admin_bar_menu_icon'] ) ) ? '<span class="spf-ab-icon ab-icon ' . esc_attr( $this->args['admin_bar_menu_icon'] ) . '"></span>' : '';

				$wp_admin_bar->add_node(
					array(
						'id'    => $menu_slug,
						'title' => $menu_icon . esc_attr( $this->args['menu_title'] ),
						'href'  => esc_url( ( is_network_admin() ) ? network_admin_url( 'admin.php?page=' . $menu_slug ) : admin_url( 'admin.php?page=' . $menu_slug ) ),
					)
				);

				if ( ! empty( $submenu[ $menu_slug ] ) ) {
					foreach ( $submenu[ $menu_slug ] as $menu_key => $menu_value ) {
						$wp_admin_bar->add_node(
							array(
								'parent' => $menu_slug,
								'id'     => $menu_slug . '-' . $menu_key,
								'title'  => $menu_value[0],
								'href'   => esc_url( ( is_network_admin() ) ? network_admin_url( 'admin.php?page=' . $menu_value[2] ) : admin_url( 'admin.php?page=' . $menu_value[2] ) ),
							)
						);
					}
				}
			}

		}

		/**
		 * Ajax_save
		 *
		 * @return void
		 */
		public function ajax_save() {

			$result = $this->set_options( true );

			if ( ! $result ) {
				wp_send_json_error( array( 'error' => esc_html__( 'Error while saving the changes.', 'team-free' ) ) );
			} else {
				wp_send_json_success(
					array(
						'notice' => $this->notice,
						'errors' => $this->errors,
					)
				);
			}

		}

		/**
		 * Get default value
		 *
		 * @param  mixed $field field.
		 * @return mixed
		 */
		public function get_default( $field ) {

			$default = ( isset( $field['default'] ) ) ? $field['default'] : '';
			$default = ( isset( $this->args['defaults'][ $field['id'] ] ) ) ? $this->args['defaults'][ $field['id'] ] : $default;

			return $default;

		}

		/**
		 * Save defaults and set new fields value to main options.
		 *
		 * @return void
		 */
		public function save_defaults() {

			$tmp_options = $this->options;

			foreach ( $this->pre_fields as $field ) {
				if ( ! empty( $field['id'] ) ) {
					$this->options[ $field['id'] ] = ( isset( $this->options[ $field['id'] ] ) ) ? $this->options[ $field['id'] ] : $this->get_default( $field );
				}
			}

			if ( $this->args['save_defaults'] && empty( $tmp_options ) ) {
				$this->save_options( $this->options );
			}

		}

		/**
		 * Set options.
		 *
		 * @param bool $ajax ajax option.
		 * @return bool
		 */
		public function set_options( $ajax = false ) {

			// XSS ok.
			// No worries, This "POST" requests is sanitizing in the below foreach. see #L337 - #L341.
			$response = ( $ajax && ! empty( $_POST['data'] ) ) ? json_decode( wp_unslash( trim( $_POST['data'] ) ), true ) : $_POST; // phpcs:ignore

			// Set variables.
			$data      = array();
			$noncekey  = 'spf_options_nonce' . $this->unique;
			$nonce     = ( ! empty( $response[ $noncekey ] ) ) ? $response[ $noncekey ] : '';
			$options   = ( ! empty( $response[ $this->unique ] ) ) ? $response[ $this->unique ] : array();
			$transient = ( ! empty( $response['spf_transient'] ) ) ? $response['spf_transient'] : array();

			if ( wp_verify_nonce( $nonce, 'spf_options_nonce' ) ) {

				$importing  = false;
				$section_id = ( ! empty( $transient['section'] ) ) ? $transient['section'] : '';

				if ( ! $ajax && ! empty( $response['spf_import_data'] ) ) {

					// XSS ok.
					// No worries, This "POST" requests is sanitizing in the below foreach. see #L337 - #L341.
					$import_data  = json_decode( wp_unslash( trim( $response['spf_import_data'] ) ), true );
					$options      = ( is_array( $import_data ) && ! empty( $import_data ) ) ? $import_data : array();
					$importing    = true;
					$this->notice = esc_html__( 'Settings successfully imported.', 'team-free' );

				}

				if ( ! empty( $transient['reset'] ) ) {

					foreach ( $this->pre_fields as $field ) {
						if ( ! empty( $field['id'] ) ) {
							$data[ $field['id'] ] = $this->get_default( $field );
						}
					}

					$this->notice = esc_html__( 'Default settings restored.', 'team-free' );

				} elseif ( ! empty( $transient['reset_section'] ) && ! empty( $section_id ) ) {

					if ( ! empty( $this->pre_sections[ $section_id - 1 ]['fields'] ) ) {

						foreach ( $this->pre_sections[ $section_id - 1 ]['fields'] as $field ) {
							if ( ! empty( $field['id'] ) ) {
								$data[ $field['id'] ] = $this->get_default( $field );
							}
						}
					}

					$data = wp_parse_args( $data, $this->options );

					$this->notice = esc_html__( 'Default settings restored.', 'team-free' );

				} else {

					// sanitize and validate.
					foreach ( $this->pre_fields as $field ) {

						if ( ! empty( $field['id'] ) ) {

							$field_id    = $field['id'];
							$field_value = isset( $options[ $field_id ] ) ? $options[ $field_id ] : '';

							// Ajax and Importing doing wp_unslash already.
							if ( ! $ajax && ! $importing ) {
								$field_value = wp_unslash( $field_value );
							}

							// Sanitize "post" request of field.
							if ( ! isset( $field['sanitize'] ) ) {

								if ( is_array( $field_value ) ) {

									$data[ $field_id ] = wp_kses_post_deep( $field_value );

								} else {

									$data[ $field_id ] = wp_kses_post( $field_value );

								}
							} elseif ( isset( $field['sanitize'] ) && is_callable( $field['sanitize'] ) ) {

								$data[ $field_id ] = call_user_func( $field['sanitize'], $field_value );

							} else {

								$data[ $field_id ] = $field_value;

							}

							// Validate "post" request of field.
							if ( isset( $field['validate'] ) && is_callable( $field['validate'] ) ) {

								$has_validated = call_user_func( $field['validate'], $field_value );

								if ( ! empty( $has_validated ) ) {

									$data[ $field_id ]         = ( isset( $this->options[ $field_id ] ) ) ? $this->options[ $field_id ] : '';
									$this->errors[ $field_id ] = $has_validated;

								}
							}
						}
					}
				}

				$data = apply_filters( "spf_{$this->unique}_save", $data, $this );

				do_action( "spf_{$this->unique}_save_before", $data, $this );

				$this->options = $data;

				$this->save_options( $data );

				do_action( "spf_{$this->unique}_save_after", $data, $this );

				if ( empty( $this->notice ) ) {
					$this->notice = esc_html__( 'Settings saved.', 'team-free' );
				}

				return true;

			}

			return false;

		}

		/**
		 * Save options database.
		 *
		 * @param  array $data Request.
		 * @return void
		 */
		public function save_options( $data ) {

			if ( 'transient' === $this->args['database'] ) {
				set_transient( $this->unique, $data, $this->args['transient_time'] );
			} elseif ( 'theme_mod' === $this->args['database'] ) {
				set_theme_mod( $this->unique, $data );
			} elseif ( 'network' === $this->args['database'] ) {
				update_site_option( $this->unique, $data );
			} else {
				update_option( $this->unique, $data );
			}

			do_action( "spf_{$this->unique}_saved", $data, $this );

		}

		/**
		 * Get options from database.
		 *
		 * @return mixed
		 */
		public function get_options() {

			if ( 'transient' === $this->args['database'] ) {
				$this->options = get_transient( $this->unique );
			} elseif ( 'theme_mod' === $this->args['database'] ) {
				$this->options = get_theme_mod( $this->unique );
			} elseif ( 'network' === $this->args['database'] ) {
				$this->options = get_site_option( $this->unique );
			} else {
				$this->options = get_option( $this->unique );
			}

			if ( empty( $this->options ) ) {
				$this->options = array();
			}

			return $this->options;

		}

		/**
		 * Wp api: admin menu.
		 *
		 * @return void
		 */
		public function add_admin_menu() {
			// Show plugin setting menu as per user role. ShapedPlugin
			// Use the hook 'sp_wp_team_pro_ui_permission' to change user capability.
			$menu_capability = apply_filters( 'sp_wp_team_ui_permission', 'manage_options' );

			extract( $this->args ); // phpcs:ignore

			if ( 'submenu' === $menu_type ) {

				$menu_page = call_user_func( 'add_submenu_page', $menu_parent, esc_attr( $menu_title ), esc_attr( $menu_title ), $menu_capability, $menu_slug, array( &$this, 'add_options_html' ) );

			} else {

				$menu_page = call_user_func( 'add_menu_page', esc_attr( $menu_title ), esc_attr( $menu_title ), $menu_capability, $menu_slug, array( &$this, 'add_options_html' ), $menu_icon, $menu_position );

				if ( ! empty( $sub_menu_title ) ) {
					call_user_func( 'add_submenu_page', $menu_slug, esc_attr( $sub_menu_title ), esc_attr( $sub_menu_title ), $menu_capability, $menu_slug, array( &$this, 'add_options_html' ) );
				}

				if ( ! empty( $this->args['show_sub_menu'] ) && count( $this->pre_tabs ) > 1 ) {

					// create submenus.
					foreach ( $this->pre_tabs as $section ) {
						call_user_func( 'add_submenu_page', $menu_slug, esc_attr( $section['title'] ), esc_attr( $section['title'] ), $menu_capability, $menu_slug . '#tab=' . sanitize_title( $section['title'] ), '__return_null' );
					}

					remove_submenu_page( $menu_slug, $menu_slug );

				}

				if ( ! empty( $menu_hidden ) ) {
					remove_menu_page( $menu_slug );
				}
			}

			add_action( 'load-' . $menu_page, array( &$this, 'add_page_on_load' ) );

		}

		/**
		 * Page on load.
		 *
		 * @return void
		 */
		public function add_page_on_load() {

			if ( ! empty( $this->args['contextual_help'] ) ) {

				$screen = get_current_screen();

				foreach ( $this->args['contextual_help'] as $tab ) {
					$screen->add_help_tab( $tab );
				}

				if ( ! empty( $this->args['contextual_help_sidebar'] ) ) {
					$screen->set_help_sidebar( $this->args['contextual_help_sidebar'] );
				}
			}

		}

		/**
		 * Error check
		 *
		 * @param  mixed $sections Sections.
		 * @param  mixed $err error.
		 * @return statement
		 */
		public function error_check( $sections, $err = '' ) {

			if ( ! $this->args['ajax_save'] ) {

				if ( ! empty( $sections['fields'] ) ) {
					foreach ( $sections['fields'] as $field ) {
						if ( ! empty( $field['id'] ) ) {
							if ( array_key_exists( $field['id'], $this->errors ) ) {
								$err = '<span class="spf-label-error">!</span>';
							}
						}
					}
				}

				if ( ! empty( $sections['subs'] ) ) {
					foreach ( $sections['subs'] as $sub ) {
						$err = $this->error_check( $sub, $err );
					}
				}

				if ( ! empty( $sections['id'] ) && array_key_exists( $sections['id'], $this->errors ) ) {
					$err = $this->errors[ $sections['id'] ];
				}
			}

			return $err;
		}

		/**
		 * Option page html output.
		 *
		 * @return void
		 */
		public function add_options_html() {

			$has_nav       = ( count( $this->pre_tabs ) > 1 ) ? true : false;
			$show_buttons  = isset( $this->args['show_buttons'] ) ? $this->args['show_buttons'] : true;
			$show_all      = ( ! $has_nav ) ? ' spf-show-all' : '';
			$ajax_class    = ( $this->args['ajax_save'] ) ? ' spf-save-ajax' : '';
			$sticky_class  = ( $this->args['sticky_header'] ) ? ' spf-sticky-header' : '';
			$wrapper_class = ( $this->args['framework_class'] ) ? ' ' . $this->args['framework_class'] : '';
			$theme         = ( $this->args['theme'] ) ? ' spf-theme-' . $this->args['theme'] : '';
			$class         = ( $this->args['class'] ) ? ' ' . $this->args['class'] : '';
			$nav_type      = ( 'inline' === $this->args['nav'] ) ? 'inline' : 'normal';
			$form_action   = ( $this->args['form_action'] ) ? $this->args['form_action'] : '';

			$notice_class = ( ! empty( $this->notice ) ) ? 'spf-form-show' : '';
			$notice_text  = ( ! empty( $this->notice ) ) ? $this->notice : '';

			echo '<div class="spf-container">';

			echo '<div class="spf spf-options' . esc_attr( $theme . $class . $wrapper_class ) . '" data-slug="' . esc_attr( $this->args['menu_slug'] ) . '" data-unique="' . esc_attr( $this->unique ) . '">';
			if ( 'team_tools' === $this->args['menu_slug'] ) {
				echo '<div class="spf-form-result spf-form-success ' . esc_attr( $notice_class ) . '">' . wp_kses_post( $notice_text ) . '</div>';
			}
			echo '<div class="spf-container">';

			echo '<form method="post" action="' . esc_attr( $form_action ) . '" enctype="multipart/form-data" id="spf-form" autocomplete="off" novalidate="novalidate">';

			echo '<input type="hidden" class="spf-section-id" name="spf_transient[section]" value="1">';

			wp_nonce_field( 'spf_options_nonce', 'spf_options_nonce' . $this->unique );

			echo '<div class="spf-header' . esc_attr( $sticky_class ) . '">';
			echo '<div class="spf-header-inner">';
			echo '<div class="spf-header-left">';
			if ( $show_buttons ) {
				echo '<img class="spf-setting-logo" src="data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjI1NnB4IiBoZWlnaHQ9IjI1NnB4IiB2aWV3Qm94PSIwIDAgMjU2IDI1NiIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMjU2IDI1NiIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+ICA8aW1hZ2UgaWQ9ImltYWdlMCIgd2lkdGg9IjI1NiIgaGVpZ2h0PSIyNTYiIHg9IjAiIHk9IjAiIGhyZWY9ImRhdGE6aW1hZ2Uvc3ZnK3htbDtiYXNlNjQsUEQ5NGJXd2dkbVZ5YzJsdmJqMGlNUzR3SWlCbGJtTnZaR2x1WnowaWRYUm1MVGdpUHo0S1BDRXRMU0JIWlc1bGNtRjBiM0k2SUVGa2IySmxJRWxzYkhWemRISmhkRzl5SURJMExqTXVNQ3dnVTFaSElFVjRjRzl5ZENCUWJIVm5MVWx1SUM0Z1UxWkhJRlpsY25OcGIyNDZJRFl1TURBZ1FuVnBiR1FnTUNrZ0lDMHRQZ284YzNabklIWmxjbk5wYjI0OUlqRXVNU0lnYVdROUlreGhlV1Z5WHpFaUlIaHRiRzV6UFNKb2RIUndPaTh2ZDNkM0xuY3pMbTl5Wnk4eU1EQXdMM04yWnlJZ2VHMXNibk02ZUd4cGJtczlJbWgwZEhBNkx5OTNkM2N1ZHpNdWIzSm5MekU1T1RrdmVHeHBibXNpSUhnOUlqQndlQ0lnZVQwaU1IQjRJZ29KSUhacFpYZENiM2c5SWpBZ01DQTNOVFlnTnpVMklpQnpkSGxzWlQwaVpXNWhZbXhsTFdKaFkydG5jbTkxYm1RNmJtVjNJREFnTUNBM05UWWdOelUyT3lJZ2VHMXNPbk53WVdObFBTSndjbVZ6WlhKMlpTSStDanh6ZEhsc1pTQjBlWEJsUFNKMFpYaDBMMk56Y3lJK0Nna3VjM1F3ZTJacGJHdzZJelJGUVRrNE1EdDlDand2YzNSNWJHVStDanhuUGdvSlBHYytDZ2tKUEhCaGRHZ2dZMnhoYzNNOUluTjBNQ0lnWkQwaVRUTXlOQzQ0TXl3ME9UZ3VOalpzTFRBdU9ESXNNeTR5T1d3dE5UVXVNRFFzTVRZdU5ETmpMVEUxTGpZeExEUXVPVE10TXpNdU5qZ3NOVEF1T1RNdE5EY3VOalFzTVRJekxqSXhDZ2tKQ1dNME5pNDRNaXd5Tnk0NU15d3hNREV1TURNc05ESXVOekVzTVRVMkxqQTNMRFF5TGpjeFl6WXVOVGNzTUN3eE15NDVOaXd3TERJd0xqVTBMVEF1T0RKak5EY3VOalF0TXk0eU9TdzVOUzR5T0MweE55NHlOU3d4TXpZdU16WXROREV1T0RrS0NRa0pZeTB4TXk0NU5pMDNNUzQwTmkwek1pNHdOQzB4TVRrdU1URXRORGN1TmpRdE1USXpMakl4YkMwMU5TNHdOQzB4Tmk0ME0yd3RNQzQ0TWkwekxqSTVZeTB3TGpneUxUSXVORFl0TWk0ME5pMDBMamt6TFRVdU56VXROaTQxTjJ3dE5pNDFOeTAwTGpFeGJEUXVNVEV0TkM0NU13b0pDUWxqTkM0NU15MDBMamt6TERndU1qRXRPUzQ0Tml3NUxqZzJMVEV6TGpFMFl6WXVOVGN0T1M0d05Dd3hNUzQxTFRFNExqZzVMREUwTGpjNUxUSTRMamMxWXpFdU5qUXROQzR4TVN3ekxqSTVMVGd1TWpFc05DNDVNeTB4TWk0ek1td3dMamd5TFRFdU5qUnNNUzQyTkMwd0xqZ3lDZ2tKQ1dNMExqRXhMVE11TWprc05TNDNOUzAzTGpNNUxEVXVOelV0TVRJdU16SjJMVEUyTGpRell6QXRNeTR5T1Mwd0xqZ3lMVFl1TlRjdE15NHlPUzA1TGpnMmJDMHdMamd5TFRFdU5qUjJMVEkwTGpZMFl6QXRNell1T1RZdE16QXVNemt0TmpjdU16WXROamN1TXpZdE5qY3VNelpvTFRJekxqZ3lDZ2tKQ1dNdE16WXVPVFlzTUMwMk55NHpOaXd6TUM0ek9TMDJOeTR6Tml3Mk55NHpObll5TkM0Mk5Hd3RNQzQ0TWl3eExqWTBZeTB4TGpZMExESXVORFl0TXk0eU9TdzJMalUzTFRNdU1qa3NPUzQ0Tm5ZeE5pNDBNMk13TERRdU9UTXNNaTQwTml3NUxqZzJMRFV1TnpVc01USXVNekpzTVM0Mk5Dd3dMamd5Q2drSkNXd3dMamd5TERFdU5qUmpNQzQ0TWl3MExqRXhMREl1TkRZc09DNHlNU3cwTGpFeExERXhMalZqTkM0eE1TdzVMamcyTERrdU1EUXNNVGt1TnpFc01UVXVOakVzTWpndU56VmpNeTR5T1N3MExqa3pMRFl1TlRjc09TNHdOQ3c1TGpnMkxERXpMakUwYkRRdU1URXNOQzQ1TTJ3dE5TNDNOU3cwTGpFeENna0pDVU16TWpndU1URXNORGswTGpVMkxETXlOUzQyTlN3ME9UWXVNaXd6TWpRdU9ETXNORGs0TGpZMmVpSXZQZ29KQ1R4d1lYUm9JR05zWVhOelBTSnpkREFpSUdROUlrMHhPVE11TkN3Mk1qTXVOVEpqTVM0Mk5DMDVMakEwTERRdU1URXRNVGd1TURjc05pNDFOeTB5Tnk0NU0yTXlNQzQxTkMwNE5DNDJNU3cwTVM0NE9TMHhNREV1TURNc05Ua3VPVFl0TVRBMkxqYzRiRE14TGpJeExUa3VNRFFLQ1FrSll5MDBMamt6TFRndU1qRXRPUzQ0TmkweE55NHlOUzB4TXk0eE5DMHlOaTR5T1dNdE1DNDRNaTB5TGpRMkxUSXVORFl0TlM0M05TMHpMakk1TFRndU1qRnNNQ3d3YkMwek15NDJPQzA1TGpnMmJDMHdMamd5TFRNdU1qbGpMVEF1T0RJdE1pNDBOaTB5TGpRMkxUUXVPVE10TlM0M05TMDJMalUzQ2drSkNXd3ROUzQzTlMwMExqa3piRFF1TVRFdE5DNDVNMk16TGpJNUxUUXVNVEVzTmk0MU55MDRMakl4TERrdU9EWXRNVE11TVRSak5pNDFOeTA1TGpBMExERXhMalV0TVRndU9Ea3NNVFF1TnprdE1qY3VPVE5qTVM0Mk5DMDBMakV4TERNdU1qa3RPQzR5TVN3MExqa3pMVEV5TGpNeUNna0pDV3d3TGpneUxURXVOalJzTVM0Mk5DMHdMamd5WXpRdU1URXRNeTR5T1N3MUxqYzFMVGN1TXprc05TNDNOUzB4TWk0ek1uWXRNVFl1TkROak1DMHpMakk1TFRBdU9ESXROaTQxTnkwekxqSTVMVGt1T0Rac0xUQXVPREl0TVM0Mk5IWXRNalF1TmpRS0NRa0pZekF0TXpZdU9UWXRNekF1TXprdE5qY3VNell0TmpjdU16WXROamN1TXpab0xUSXpMamd5WXkwek5pNDVOaXd3TFRZM0xqTTJMRE13TGpNNUxUWTNMak0yTERZM0xqTTJkakkwTGpZMGJDMHdMamd5TERFdU5qUmpMVEV1TmpRc01pNDBOaTB6TGpJNUxEWXVOVGN0TXk0eU9TdzVMamcyQ2drSkNYWXhOaTQwTTJNd0xEUXVPVE1zTWk0ME5pdzVMamcyTERVdU56VXNNVEl1TXpKc01TNDJOQ3d3TGpneWJEQXVPRElzTVM0Mk5HTXdMamd5TERRdU1URXNNaTQwTml3NExqSXhMRFF1T1RNc01URXVOV00wTGpFeExEa3VPRFlzT1M0d05Dd3hPUzQzTVN3eE5TNDJNU3d5T0M0M05Rb0pDUWxqTXk0eU9TdzBMamt6TERZdU5UY3NPUzR3TkN3NUxqZzJMREV6TGpFMGJEUXVNVEVzTkM0NU0yd3ROUzQzTlN3MExqRXhZeTB6TGpJNUxESXVORFl0TkM0NU15dzBMakV4TFRVdU56VXNOaTQxTjJ3dE1DNDRNaXd6TGpJNWJDMDFOUzR3TkN3eE5pNDBNMGczT0M0MENna0pDVU01TlM0Mk5TdzFNakF1TURJc01UTTJMamN5TERVNE1DNDRNU3d4T1RNdU5DdzJNak11TlRKNklpOCtDZ2tKUEhCaGRHZ2dZMnhoYzNNOUluTjBNQ0lnWkQwaVRUVTNPQzQyTkN3eU1qWXVOemRvTFRJekxqZ3lZeTB6Tmk0NU5pd3dMVFkzTGpNMkxETXdMak01TFRZM0xqTTJMRFkzTGpNMmRqSTBMalkwYkMwd0xqZ3lMREV1TmpSakxURXVOalFzTXk0eU9TMHlMalEyTERZdU5UY3RNaTQwTml3NUxqZzJDZ2tKQ1hZeE5pNDBNMk13TERRdU9UTXNNaTQwTml3NUxqZzJMRFV1TnpVc01USXVNekpzTVM0Mk5Dd3dMamd5YkRBdU9ESXNNUzQyTkdNeExqWTBMRFF1TVRFc01pNDBOaXc0TGpJeExEUXVNVEVzTVRFdU5XTTBMakV4TERrdU9EWXNPUzR3TkN3eE9TNDNNU3d4TlM0Mk1Td3lPQzQzTlFvSkNRbGpNaTQwTml3ekxqSTVMRFV1TnpVc09DNHlNU3c1TGpnMkxERXpMakUwYkRRdU1URXNOQzQ1TTJ3dE5TNDNOU3cwTGpFeFl5MHpMakk1TERJdU5EWXROQzQ1TXl3MExqRXhMVFV1TnpVc05pNDFOMnd0TUM0NE1pd3pMakk1YkMwek15NDJPQ3c1TGpnMmJEQXNNQW9KQ1FsakxUQXVPRElzTXk0eU9TMHlMalEyTERVdU56VXRNeTR5T1N3NUxqQTBZeTB6TGpJNUxEZ3VNakV0T0M0eU1Td3hOeTR5TlMweE15NHhOQ3d5Tmk0eU9Xd3pNUzR5TVN3NUxqQTBZekU0TGpBM0xEUXVPVE1zTXprdU5ETXNNakl1TVRnc05Ua3VPVFlzTVRBMUxqRTBDZ2tKQ1dNeUxqUTJMRGt1T0RZc05DNDVNeXd4T1M0M01TdzJMalUzTERJNUxqVTNZekUzTGpJMUxURXpMakUwTERNekxqWTRMVEkzTGprekxEUTNMalkwTFRRMExqTTJZek15TGpBMExUTTJMamsyTERVMUxqZzJMVGd4TGpNeUxEWTNMak0yTFRFeU9DNHhOR2d0TUM0NE1td3ROVFV1TURRdE1UWXVORE1LQ1FrSmJDMHdMamd5TFRNdU1qbGpMVEF1T0RJdE1pNDBOaTB5TGpRMkxUUXVPVE10TlM0M05TMDJMalUzYkMwMUxqYzFMVE11TWpsc05DNDVNeTAwTGprell6TXVNamt0TkM0eE1TdzJMalUzTFRndU1qRXNPUzQ0TmkweE15NHhOQW9KQ1Fsak5pNDFOeTA1TGpBMExERXhMalV0TVRndU9Ea3NNVFF1TnprdE1qY3VPVE5qTVM0Mk5DMDBMakV4TERNdU1qa3RPQzR5TVN3MExqa3pMVEV5TGpNeWJEQXVPREl0TVM0Mk5Hd3hMalkwTFRBdU9ESmpOQzR4TVMwekxqSTVMRFV1TnpVdE55NHpPU3cxTGpjMUxURXlMak15ZGkweE5pNDBNd29KQ1Fsak1DMHpMakk1TFRBdU9ESXROaTQxTnkwekxqSTVMVGt1T0Rac0xUQXVPREl0TVM0Mk5IWXRNalF1TmpSRE5qUTJMREkxTnk0eE55dzJNVFl1TkRNc01qSTJMamMzTERVM09DNDJOQ3d5TWpZdU56ZDZJaTgrQ2drOEwyYytDZ2s4Wno0S0NRazhjR0YwYUNCamJHRnpjejBpYzNRd0lpQmtQU0pOTXpjM0xqZ3hMRGMwTlM0NU1VTXhOelF1TnpRc056UTFMamt4TERrdU5UUXNOVGd3TGpjc09TNDFOQ3d6TnpjdU5qUmpNQzB5TURNdU1EWXNNVFkxTGpJdE16WTRMakkzTERNMk9DNHlOeTB6TmpndU1qY0tDUWtKWXpJd015NHdOaXd3TERNMk9DNHlOeXd4TmpVdU1pd3pOamd1TWpjc016WTRMakkzUXpjME5pNHdPQ3cxT0RBdU55dzFPREF1T0Rjc056UTFMamt4TERNM055NDRNU3czTkRVdU9URjZJRTB6TnpjdU9ERXNORGd1T0FvSkNRbGpMVEU0TVM0ek1pd3dMVE15T0M0NE5Dd3hORGN1TlRJdE16STRMamcwTERNeU9DNDROR013TERFNE1TNHpNaXd4TkRjdU5USXNNekk0TGpnMExETXlPQzQ0TkN3ek1qZ3VPRFJqTVRneExqTXlMREFzTXpJNExqZzBMVEUwTnk0MU1pd3pNamd1T0RRdE16STRMamcwQ2drSkNVTTNNRFl1TmpVc01UazJMak15TERVMU9TNHhNeXcwT0M0NExETTNOeTQ0TVN3ME9DNDRlaUl2UGdvSlBDOW5QZ284TDJjK0Nqd3ZjM1puUGdvPSIgLz48L3N2Zz4=">';
				echo '<h1>' . wp_kses_post( $this->args['framework_title'] ) . '</h1>';
			} else {
				echo '<h1 class="export-import"><img src="' . esc_url( SPT_PLUGIN_ROOT . 'src/Admin/img/import-export.svg' ) . '">' . wp_kses_post( $this->args['framework_title'] ) . '</h1>';
			}
			echo '</div>';

			echo '<div class="spf-header-right">';

			echo ( $has_nav && $this->args['show_all_options'] ) ? '<div class="spf-expand-all" title="' . esc_html__( 'show all settings', 'team-free' ) . '"><i class="fa fa-outdent"></i></div>' : '';

			echo ( $this->args['show_search'] ) ? '<div class="spf-search"><input type="text" name="spf-search" placeholder="' . esc_html__( 'Search...', 'team-free' ) . '" autocomplete="off" /></div>' : '';
			if ( $show_buttons ) {
				echo '<div class="spf-buttons">';
				echo '<input type="submit" name="' . esc_attr( $this->unique ) . '[_nonce][save]" class="button button-primary spf-top-save spf-save' . esc_attr( $ajax_class ) . '" value="' . esc_html__( 'Save Settings', 'team-free' ) . '" data-save="' . esc_html__( 'Saving...', 'team-free' ) . '">';
				echo ( $this->args['show_reset_section'] ) ? '<input type="submit" name="spf_transient[reset_section]" class="button button-secondary spf-reset-section spf-confirm" value="' . esc_html__( 'Reset Tab', 'team-free' ) . '" data-confirm="' . esc_html__( 'Are you sure to reset all the settings of this tab?', 'team-free' ) . '">' : '';
				echo ( $this->args['show_reset_all'] ) ? '<input type="submit" name="spf_transient[reset]" class="button spf-warning-primary spf-reset-all spf-confirm" value="' . ( ( $this->args['show_reset_section'] ) ? esc_html__( 'Reset All', 'team-free' ) : esc_html__( 'Reset', 'team-free' ) ) . '" data-confirm="' . esc_html__( 'Are you sure you want to reset all settings to default values?', 'team-free' ) . '">' : '';
				echo '</div>';
			}
			echo '</div>';

			echo '<div class="clear"></div>';
			echo '</div>';
			echo '</div>';

			echo '<div class="spf-wrapper' . esc_attr( $show_all ) . '">';

			if ( $has_nav ) {

				echo '<div class="spf-nav spf-nav-' . esc_attr( $nav_type ) . ' spf-nav-options">';

				echo '<ul>';

				foreach ( $this->pre_tabs as $tab ) {

					$tab_id    = sanitize_title( $tab['title'] );
					$tab_error = $this->error_check( $tab );
					$tab_icon  = ( ! empty( $tab['icon'] ) ) ? '<i class="spf-tab-icon ' . esc_attr( $tab['icon'] ) . '"></i>' : '';

					if ( ! empty( $tab['subs'] ) ) {

						echo '<li class="spf-tab-item">';

						echo '<a href="#tab=' . esc_attr( $tab_id ) . '" data-tab-id="' . esc_attr( $tab_id ) . '" class="spf-arrow">' . wp_kses_post( $tab_icon . $tab['title'] . $tab_error ) . '</a>';

						echo '<ul>';

						foreach ( $tab['subs'] as $sub ) {

							$sub_id    = $tab_id . '/' . sanitize_title( $sub['title'] );
							$sub_error = $this->error_check( $sub );
							$sub_icon  = ( ! empty( $sub['icon'] ) ) ? '<i class="spf-tab-icon ' . esc_attr( $sub['icon'] ) . '"></i>' : '';

							echo '<li><a href="#tab=' . esc_attr( $sub_id ) . '" data-tab-id="' . esc_attr( $sub_id ) . '">' . wp_kses_post( $sub_icon . $sub['title'] . $sub_error ) . '</a></li>';

						}

						echo '</ul>';

						echo '</li>';

					} else {

						echo '<li class="spf-tab-item"><a href="#tab=' . esc_attr( $tab_id ) . '" data-tab-id="' . esc_attr( $tab_id ) . '">' . wp_kses_post( $tab_icon . $tab['title'] . $tab_error ) . '</a></li>';

					}
				}

				echo '</ul>';

				echo '</div>';

			}

			echo '<div class="spf-content">';

			echo '<div class="spf-sections">';

			foreach ( $this->pre_sections as $section ) {

				$section_onload = ( ! $has_nav ) ? ' spf-onload' : '';
				$section_class  = ( ! empty( $section['class'] ) ) ? ' ' . $section['class'] : '';
				$section_icon   = ( ! empty( $section['icon'] ) ) ? '<i class="spf-section-icon ' . esc_attr( $section['icon'] ) . '"></i>' : '';
				$section_title  = ( ! empty( $section['title'] ) ) ? $section['title'] : '';
				$section_parent = ( ! empty( $section['ptitle'] ) ) ? sanitize_title( $section['ptitle'] ) . '/' : '';
				$section_slug   = ( ! empty( $section['title'] ) ) ? sanitize_title( $section_title ) : '';

				echo '<div class="spf-section hidden' . esc_attr( $section_onload . $section_class ) . '" data-section-id="' . esc_attr( $section_parent . $section_slug ) . '">';
				echo ( $has_nav ) ? '<div class="spf-section-title"><h3>' . wp_kses_post( $section_icon . $section_title ) . '</h3></div>' : '';
				echo ( ! empty( $section['description'] ) ) ? '<div class="spf-field spf-section-description">' . wp_kses_post( $section['description'] ) . '</div>' : '';

				if ( ! empty( $section['fields'] ) ) {

					foreach ( $section['fields'] as $field ) {

						$is_field_error = $this->error_check( $field );

						if ( ! empty( $is_field_error ) ) {
							$field['_error'] = $is_field_error;
						}

						if ( ! empty( $field['id'] ) ) {
							$field['default'] = $this->get_default( $field );
						}

						$value = ( ! empty( $field['id'] ) && isset( $this->options[ $field['id'] ] ) ) ? $this->options[ $field['id'] ] : '';

						SPF_TEAM::field( $field, $value, $this->unique, 'options' );

					}
				} else {

					echo '<div class="spf-no-option">' . esc_html__( 'No data available.', 'team-free' ) . '</div>';

				}

				echo '</div>';

			}

			echo '</div>';

			echo '<div class="clear"></div>';

			echo '</div>';

			echo ( $has_nav && 'normal' === $nav_type ) ? '<div class="spf-nav-background"></div>' : '';

			echo '</div>';

			if ( ! empty( $this->args['show_footer'] ) ) {

				echo '<div class="spf-footer">';

				echo '<div class="spf-buttons">';
				echo '<input type="submit" name="spf_transient[save]" class="button button-primary spf-save' . esc_attr( $ajax_class ) . '" value="' . esc_html__( 'Save', 'team-free' ) . '" data-save="' . esc_html__( 'Saving...', 'team-free' ) . '">';
				echo ( $this->args['show_reset_section'] ) ? '<input type="submit" name="spf_transient[reset_section]" class="button button-secondary spf-reset-section spf-confirm" value="' . esc_html__( 'Reset Section', 'team-free' ) . '" data-confirm="' . esc_html__( 'Are you sure to reset this section options?', 'team-free' ) . '">' : '';
				echo ( $this->args['show_reset_all'] ) ? '<input type="submit" name="spf_transient[reset]" class="button spf-warning-primary spf-reset-all spf-confirm" value="' . ( ( $this->args['show_reset_section'] ) ? esc_html__( 'Reset All', 'team-free' ) : esc_html__( 'Reset', 'team-free' ) ) . '" data-confirm="' . esc_html__( 'Are you sure you want to reset all settings to default values?', 'team-free' ) . '">' : '';
				echo '</div>';

				echo ( ! empty( $this->args['footer_text'] ) ) ? '<div class="spf-copyright">' . wp_kses_post( $this->args['footer_text'] ) . '</div>' : '';

				echo '<div class="clear"></div>';
				echo '</div>';

			}

			echo '</form>';

			echo '</div>';

			echo '<div class="clear"></div>';

			echo ( ! empty( $this->args['footer_after'] ) ) ? wp_kses_post( $this->args['footer_after'] ) : '';

			echo '</div>';

			do_action( 'spf_options_after' );

		}
	}
}
