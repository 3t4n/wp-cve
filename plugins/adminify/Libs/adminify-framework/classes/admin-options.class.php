<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access directly.
/**
 *
 * Options Class
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! class_exists( 'ADMINIFY_Options' ) ) {
	class ADMINIFY_Options extends ADMINIFY_Abstract {


		// constans
		public $unique       = '';
		public $notice       = '';
		public $abstract     = 'options';
		public $sections     = [];
		public $options      = [];
		public $errors       = [];
		public $pre_tabs     = [];
		public $pre_fields   = [];
		public $pre_sections = [];
		public $args         = [

			// framework title
			'framework_title'         => 'Adminify Framework <small>by Adminify</small>',
			'framework_class'         => '',

			// menu settings
			'menu_title'              => '',
			'menu_slug'               => '',
			'menu_type'               => 'menu',
			'menu_capability'         => 'manage_options',
			'menu_icon'               => null,
			'menu_position'           => null,
			'menu_hidden'             => false,
			'menu_parent'             => '',
			'sub_menu_title'          => '',

			// menu extras
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

			// admin bar menu settings
			'admin_bar_menu_icon'     => '',
			'admin_bar_menu_priority' => 50,

			// footer
			'footer_text'             => '',
			'footer_after'            => '',
			'footer_credit'           => '',

			// database model
			'database'                => '', // options, transient, theme_mod, network
			'transient_time'          => 0,

			// contextual help
			'contextual_help'         => [],
			'contextual_help_sidebar' => '',

			// typography options
			'enqueue_webfont'         => true,
			'async_webfont'           => false,

			// others
			'output_css'              => true,

			// theme
			'nav'                     => 'normal',
			'theme'                   => 'dark',
			'class'                   => '',

			// external default values
			'defaults'                => [],

		];

		// run framework construct
		public function __construct( $key, $params = [] ) {
			$this->unique   = $key;
			$this->args     = apply_filters( "adminify_{$this->unique}_args", wp_parse_args( $params['args'], $this->args ), $this );
			$this->sections = apply_filters( "adminify_{$this->unique}_sections", $params['sections'], $this );

			// run only is admin panel options, avoid performance loss
			$this->pre_tabs     = $this->pre_tabs( $this->sections );
			$this->pre_fields   = $this->pre_fields( $this->sections );
			$this->pre_sections = $this->pre_sections( $this->sections );

			$this->get_options();
			$this->set_options();
			$this->save_defaults();

			add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
			add_action( 'admin_bar_menu', [ $this, 'add_admin_bar_menu' ], $this->args['admin_bar_menu_priority'] );
			add_action( 'wp_ajax_adminify_' . $this->unique . '_ajax_save', [ $this, 'ajax_save' ] );

			if ( $this->args['database'] === 'network' && ! empty( $this->args['show_in_network'] ) ) {
				add_action( 'network_admin_menu', [ $this, 'add_admin_menu' ] );
			}

			// wp enqeueu for typography and output css
			parent::__construct();
		}

		// instance
		public static function instance( $key, $params = [] ) {
			return new self( $key, $params );
		}

		public function pre_tabs( $sections ) {
			$result  = [];
			$parents = [];
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
					$section['subs'] = wp_list_sort( $parents[ $section['id'] ], [ 'priority' => 'ASC' ], 'ASC', true );
				}
				$result[] = $section;
				$count++;
			}

			return wp_list_sort( $result, [ 'priority' => 'ASC' ], 'ASC', true );
		}

		public function pre_fields( $sections ) {
			$result = [];

			foreach ( $sections as $key => $section ) {
				if ( ! empty( $section['fields'] ) ) {
					foreach ( $section['fields'] as $field ) {
						$result[] = $field;
					}
				}
			}

			return $result;
		}

		public function pre_sections( $sections ) {
			$result = [];

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

		// add admin bar menu
		public function add_admin_bar_menu( $wp_admin_bar ) {
			if ( ! current_user_can( $this->args['menu_capability'] ) ) {
				return;
			}

			if ( is_network_admin() && ( $this->args['database'] !== 'network' || $this->args['show_in_network'] !== true ) ) {
				return;
			}

			if ( ! empty( $this->args['show_bar_menu'] ) && empty( $this->args['menu_hidden'] ) ) {
				global $submenu;

				$menu_slug = $this->args['menu_slug'];
				$menu_icon = ( ! empty( $this->args['admin_bar_menu_icon'] ) ) ? '<span class="adminify-ab-icon ab-icon ' . esc_attr( $this->args['admin_bar_menu_icon'] ) . '"></span>' : '';

				$wp_admin_bar->add_node(
					[
						'id'    => $menu_slug,
						'title' => $menu_icon . esc_attr( $this->args['menu_title'] ),
						'href'  => esc_url( ( is_network_admin() ) ? network_admin_url( 'admin.php?page=' . $menu_slug ) : admin_url( 'admin.php?page=' . $menu_slug ) ),
					]
				);

				if ( ! empty( $submenu[ $menu_slug ] ) ) {
					foreach ( $submenu[ $menu_slug ] as $menu_key => $menu_value ) {
						$wp_admin_bar->add_node(
							[
								'parent' => $menu_slug,
								'id'     => $menu_slug . '-' . $menu_key,
								'title'  => $menu_value[0],
								'href'   => esc_url( ( is_network_admin() ) ? network_admin_url( 'admin.php?page=' . $menu_value[2] ) : admin_url( 'admin.php?page=' . $menu_value[2] ) ),
							]
						);
					}
				}
			}
		}

		public function ajax_save() {
			$result = $this->set_options( true );

			if ( ! $result ) {
				wp_send_json_error( [ 'error' => esc_html__( 'Error while saving the changes.', 'adminify' ) ] );
			} else {
				wp_send_json_success(
					[
						'notice' => $this->notice,
						'errors' => $this->errors,
					]
				);
			}
		}

		// get default value
		public function get_default( $field ) {
			$default = ( isset( $field['default'] ) ) ? $field['default'] : '';
			$default = ( isset( $this->args['defaults'][ $field['id'] ] ) ) ? $this->args['defaults'][ $field['id'] ] : $default;

			return $default;
		}

		// save defaults and set new fields value to main options
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

		// set options
		public function set_options( $ajax = false ) {
			if ( $ajax && ! empty( $_POST['data'] ) ) {
				// sanitized after decode
				$response = wp_kses_post_deep( json_decode( wp_unslash( $_POST['data'] ), true ) );
			} else {
				$response = wp_kses_post_deep( wp_unslash( $_POST ) );
			}

			// Set variables.
			$data      = [];
			$noncekey  = 'adminify_options_nonce' . $this->unique;
			$nonce     = ( ! empty( $response[ $noncekey ] ) ) ? $response[ $noncekey ] : '';
			$options   = ( ! empty( $response[ $this->unique ] ) ) ? $response[ $this->unique ] : [];
			$transient = ( ! empty( $response['adminify_transient'] ) ) ? $response['adminify_transient'] : [];

			if ( wp_verify_nonce( $nonce, 'adminify_options_nonce' ) ) {
				$importing  = false;
				$section_id = ( ! empty( $transient['section'] ) ) ? $transient['section'] : '';

				if ( ! $ajax && ! empty( $response['adminify_import_data'] ) ) {

					// XSS ok.
					// No worries, This "POST" requests is sanitizing in the below foreach. see #L337 - #L341
					$import_data  = json_decode( wp_unslash( trim( $response['adminify_import_data'] ) ), true );
					$options      = ( is_array( $import_data ) && ! empty( $import_data ) ) ? $import_data : [];
					$importing    = true;
					$this->notice = esc_html__( 'Settings successfully imported.', 'adminify' );
				}

				if ( ! empty( $transient['reset'] ) ) {
					foreach ( $this->pre_fields as $field ) {
						if ( ! empty( $field['id'] ) ) {
							$data[ $field['id'] ] = $this->get_default( $field );
						}
					}

					$this->notice = esc_html__( 'Default settings restored.', 'adminify' );
				} elseif ( ! empty( $transient['reset_section'] ) && ! empty( $section_id ) ) {
					if ( ! empty( $this->pre_sections[ $section_id - 1 ]['fields'] ) ) {
						foreach ( $this->pre_sections[ $section_id - 1 ]['fields'] as $field ) {
							if ( ! empty( $field['id'] ) ) {
								$data[ $field['id'] ] = $this->get_default( $field );
							}
						}
					}

					$data = wp_parse_args( $data, $this->options );

					$this->notice = esc_html__( 'Default settings restored.', 'adminify' );
				} else {

					// sanitize and validate
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

				$data = apply_filters( "adminify_{$this->unique}_save", $data, $this );

				do_action( "adminify_{$this->unique}_save_before", $data, $this );

				$this->options = $data;

				$this->save_options( $data );

				do_action( "adminify_{$this->unique}_save_after", $data, $this );

				if ( empty( $this->notice ) ) {
					$this->notice = esc_html__( 'Settings saved.', 'adminify' );
				}

				return true;
			}

			return false;
		}

		// save options database
		public function save_options( $data ) {
			if ( $this->args['database'] === 'transient' ) {
				set_transient( $this->unique, $data, $this->args['transient_time'] );
			} elseif ( $this->args['database'] === 'theme_mod' ) {
				set_theme_mod( $this->unique, $data );
			} elseif ( $this->args['database'] === 'network' ) {
				update_site_option( $this->unique, $data );
			} else {
				update_option( $this->unique, $data );
			}

			do_action( "adminify_{$this->unique}_saved", $data, $this );
		}

		// get options from database
		public function get_options() {
			if ( $this->args['database'] === 'transient' ) {
				$this->options = get_transient( $this->unique );
			} elseif ( $this->args['database'] === 'theme_mod' ) {
				$this->options = get_theme_mod( $this->unique );
			} elseif ( $this->args['database'] === 'network' ) {
				$this->options = get_site_option( $this->unique );
			} else {
				$this->options = get_option( $this->unique );
			}

			if ( empty( $this->options ) ) {
				$this->options = [];
			}

			return $this->options;
		}

		// admin menu
		public function add_admin_menu() {
			extract( $this->args );

			if ( $menu_type === 'submenu' ) {
				$menu_page = call_user_func( 'add_submenu_page', $menu_parent, esc_attr( $menu_title ), esc_attr( $menu_title ), $menu_capability, $menu_slug, [ $this, 'add_options_html' ] );
			} else {
				$menu_page = call_user_func( 'add_menu_page', esc_attr( $menu_title ), esc_attr( $menu_title ), $menu_capability, $menu_slug, [ $this, 'add_options_html' ], $menu_icon, $menu_position );

				if ( ! empty( $sub_menu_title ) ) {
					call_user_func( 'add_submenu_page', $menu_slug, esc_attr( $sub_menu_title ), esc_attr( $sub_menu_title ), $menu_capability, $menu_slug, [ $this, 'add_options_html' ] );
				}

				if ( ! empty( $this->args['show_sub_menu'] ) && count( $this->pre_tabs ) > 1 ) {

					// create submenus
					foreach ( $this->pre_tabs as $section ) {
						call_user_func( 'add_submenu_page', $menu_slug, esc_attr( $section['title'] ), esc_attr( $section['title'] ), $menu_capability, $menu_slug . '#tab=' . sanitize_title( $section['title'] ), '__return_null' );
					}

					remove_submenu_page( $menu_slug, $menu_slug );
				}

				if ( ! empty( $menu_hidden ) ) {
					remove_menu_page( $menu_slug );
				}
			}

			add_action( 'load-' . $menu_page, [ $this, 'add_page_on_load' ] );
		}

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

			add_filter( 'admin_footer_text', [ $this, 'add_admin_footer_text' ] );
		}

		public function add_admin_footer_text() {
			$default = 'Thank you for creating with <a href="http://adminifyframework.com/" target="_blank">Adminify Framework</a>';
			if ( ! empty( $this->args['footer_credit'] ) ) {
				echo wp_kses_post( $this->args['footer_credit'] );
			} else {
				echo wp_kses_post( $default );
			}
		}

		public function error_check( $sections, $err = '' ) {
			if ( ! $this->args['ajax_save'] ) {
				if ( ! empty( $sections['fields'] ) ) {
					foreach ( $sections['fields'] as $field ) {
						if ( ! empty( $field['id'] ) ) {
							if ( array_key_exists( $field['id'], $this->errors ) ) {
								$err = '<span class="adminify-label-error">!</span>';
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

		// option page html output
		public function add_options_html() {
			$has_nav       = ( count( $this->pre_tabs ) > 1 ) ? true : false;
			$show_all      = ( ! $has_nav ) ? ' adminify-show-all' : '';
			$ajax_class    = ( $this->args['ajax_save'] ) ? ' adminify-save-ajax' : '';
			$sticky_class  = ( $this->args['sticky_header'] ) ? ' adminify-sticky-header' : '';
			$wrapper_class = ( $this->args['framework_class'] ) ? ' ' . $this->args['framework_class'] : '';
			$theme         = ( $this->args['theme'] ) ? ' adminify-theme-' . $this->args['theme'] : '';
			$class         = ( $this->args['class'] ) ? ' ' . $this->args['class'] : '';
			$nav_type      = ( $this->args['nav'] === 'inline' ) ? 'inline' : 'normal';
			$form_action   = ( $this->args['form_action'] ) ? $this->args['form_action'] : '';

			do_action( 'adminify_options_before' );

			echo '<div class="adminify adminify-options' . esc_attr( $theme . $class . $wrapper_class ) . '" data-slug="' . esc_attr( $this->args['menu_slug'] ) . '" data-unique="' . esc_attr( $this->unique ) . '">';

			echo '<div class="adminify-container">';

			echo '<form method="post" action="' . esc_attr( $form_action ) . '" enctype="multipart/form-data" id="adminify-form" autocomplete="off" novalidate="novalidate">';

			echo '<input type="hidden" class="adminify-section-id" name="adminify_transient[section]" value="1">';

			wp_nonce_field( 'adminify_options_nonce', 'adminify_options_nonce' . $this->unique );

			echo '<div class="adminify-header' . esc_attr( $sticky_class ) . '">';
			echo '<div class="adminify-header-inner">';

			echo '<div class="adminify-header-left">';
			echo '<h1>' . wp_kses_post( $this->args['framework_title'] ) . '</h1>';
			echo '</div>';

			echo '<div class="adminify-header-right">';

			$notice_class = ( ! empty( $this->notice ) ) ? 'adminify-form-show' : '';
			$notice_text  = ( ! empty( $this->notice ) ) ? $this->notice : '';

			echo '<div class="adminify-form-result adminify-form-success ' . esc_attr( $notice_class ) . '">' . wp_kses_post( $notice_text ) . '</div>';

			echo ( $this->args['show_form_warning'] ) ? '<div class="adminify-form-result adminify-form-warning">' . esc_html__( 'You have unsaved changes, save your changes!', 'adminify' ) . '</div>' : '';

			echo ( $has_nav && $this->args['show_all_options'] ) ? '<div class="adminify-expand-all" title="' . esc_html__( 'show all settings', 'adminify' ) . '"><i class="fas fa-outdent"></i></div>' : '';

			echo ( $this->args['show_search'] ) ? '<div class="adminify-search"><input type="text" name="adminify-search" placeholder="' . esc_html__( 'Search...', 'adminify' ) . '" autocomplete="off" /></div>' : '';

			echo '<div class="adminify-buttons">';
			echo '<input type="submit" name="' . esc_attr( $this->unique ) . '[_nonce][save]" class="button button-primary adminify-top-save adminify-save' . esc_attr( $ajax_class ) . '" value="' . esc_html__( 'Save', 'adminify' ) . '" data-save="' . esc_html__( 'Saving...', 'adminify' ) . '">';
			echo ( $this->args['show_reset_section'] ) ? '<input type="submit" name="adminify_transient[reset_section]" class="button button-secondary adminify-reset-section adminify-confirm" value="' . esc_html__( 'Reset Section', 'adminify' ) . '" data-confirm="' . esc_html__( 'Are you sure to reset this section options?', 'adminify' ) . '">' : '';
			echo ( $this->args['show_reset_all'] ) ? '<input type="submit" name="adminify_transient[reset]" class="button adminify-warning-primary adminify-reset-all adminify-confirm" value="' . ( ( $this->args['show_reset_section'] ) ? esc_html__( 'Reset All', 'adminify' ) : esc_html__( 'Reset', 'adminify' ) ) . '" data-confirm="' . esc_html__( 'Are you sure you want to reset all settings to default values?', 'adminify' ) . '">' : '';
			echo '</div>';

			echo '</div>';

			echo '<div class="clear"></div>';
			echo '</div>';
			echo '</div>';

			echo '<div class="adminify-wrapper' . esc_attr( $show_all ) . '">';

			if ( $has_nav ) {
				echo '<div class="adminify-nav adminify-nav-' . esc_attr( $nav_type ) . ' adminify-nav-options">';

				echo '<ul>';

				foreach ( $this->pre_tabs as $tab ) {
					$tab_id    = sanitize_title( $tab['title'] );
					$tab_error = $this->error_check( $tab );
					$tab_icon  = ( ! empty( $tab['icon'] ) ) ? '<i class="adminify-tab-icon ' . esc_attr( $tab['icon'] ) . '"></i>' : '';

					if ( ! empty( $tab['subs'] ) ) {
						echo '<li class="adminify-tab-item">';

						echo '<a href="#tab=' . esc_attr( $tab_id ) . '" data-tab-id="' . esc_attr( $tab_id ) . '" class="adminify-arrow">' . wp_kses_post( $tab_icon . $tab['title'] . $tab_error ) . '</a>';

						echo '<ul>';

						foreach ( $tab['subs'] as $sub ) {
							$sub_id    = $tab_id . '/' . sanitize_title( $sub['title'] );
							$sub_error = $this->error_check( $sub );
							$sub_icon  = ( ! empty( $sub['icon'] ) ) ? '<i class="adminify-tab-icon ' . esc_attr( $sub['icon'] ) . '"></i>' : '';

							echo '<li><a href="#tab=' . esc_attr( $sub_id ) . '" data-tab-id="' . esc_attr( $sub_id ) . '">' . wp_kses_post( $sub_icon . $sub['title'] . $sub_error ) . '</a></li>';
						}

						echo '</ul>';

						echo '</li>';
					} else {
						echo '<li class="adminify-tab-item"><a href="#tab=' . esc_attr( $tab_id ) . '" data-tab-id="' . esc_attr( $tab_id ) . '">' . wp_kses_post( $tab_icon . $tab['title'] . $tab_error ) . '</a></li>';
					}
				}

				echo '</ul>';

				echo '</div>';
			}

			echo '<div class="adminify-content">';

			echo '<div class="adminify-sections">';

			foreach ( $this->pre_sections as $section ) {
				$section_onload = ( ! $has_nav ) ? ' adminify-onload' : '';
				$section_class  = ( ! empty( $section['class'] ) ) ? ' ' . $section['class'] : '';
				$section_icon   = ( ! empty( $section['icon'] ) ) ? '<i class="adminify-section-icon ' . esc_attr( $section['icon'] ) . '"></i>' : '';
				$section_title  = ( ! empty( $section['title'] ) ) ? $section['title'] : '';
				$section_parent = ( ! empty( $section['ptitle'] ) ) ? sanitize_title( $section['ptitle'] ) . '/' : '';
				$section_slug   = ( ! empty( $section['title'] ) ) ? sanitize_title( $section_title ) : '';

				echo '<div class="adminify-section hidden' . esc_attr( $section_onload . $section_class ) . '" data-section-id="' . esc_attr( $section_parent . $section_slug ) . '">';
				echo ( $has_nav ) ? '<div class="adminify-section-title"><h3>' . wp_kses_post( $section_icon . $section_title ) . '</h3></div>' : '';
				echo ( ! empty( $section['description'] ) ) ? '<div class="adminify-field adminify-section-description">' . wp_kses_post( $section['description'] ) . '</div>' : '';

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

						ADMINIFY::field( $field, $value, $this->unique, 'options' );
					}
				} else {
					echo '<div class="adminify-no-option">' . esc_html__( 'No data available.', 'adminify' ) . '</div>';
				}

				echo '</div>';
			}

			echo '</div>';

			echo '<div class="clear"></div>';

			echo '</div>';

			echo ( $has_nav && $nav_type === 'normal' ) ? '<div class="adminify-nav-background"></div>' : '';

			echo '</div>';

			if ( ! empty( $this->args['show_footer'] ) ) {
				echo '<div class="adminify-footer">';

				echo '<div class="adminify-buttons">';
				echo '<input type="submit" name="adminify_transient[save]" class="button button-primary adminify-save' . esc_attr( $ajax_class ) . '" value="' . esc_html__( 'Save', 'adminify' ) . '" data-save="' . esc_html__( 'Saving...', 'adminify' ) . '">';
				echo ( $this->args['show_reset_section'] ) ? '<input type="submit" name="adminify_transient[reset_section]" class="button button-secondary adminify-reset-section adminify-confirm" value="' . esc_html__( 'Reset Section', 'adminify' ) . '" data-confirm="' . esc_html__( 'Are you sure to reset this section options?', 'adminify' ) . '">' : '';
				echo ( $this->args['show_reset_all'] ) ? '<input type="submit" name="adminify_transient[reset]" class="button adminify-warning-primary adminify-reset-all adminify-confirm" value="' . ( ( $this->args['show_reset_section'] ) ? esc_html__( 'Reset All', 'adminify' ) : esc_html__( 'Reset', 'adminify' ) ) . '" data-confirm="' . esc_html__( 'Are you sure you want to reset all settings to default values?', 'adminify' ) . '">' : '';
				echo '</div>';

				echo ( ! empty( $this->args['footer_text'] ) ) ? '<div class="adminify-copyright">' . wp_kses_post( $this->args['footer_text'] ) . '</div>' : '';

				echo '<div class="clear"></div>';
				echo '</div>';
			}

			echo '</form>';

			echo '</div>';

			echo '<div class="clear"></div>';

			echo ( ! empty( $this->args['footer_after'] ) ) ? wp_kses_post( $this->args['footer_after'] ) : '';

			echo '</div>';

			do_action( 'adminify_options_after' );
		}
	}
}
