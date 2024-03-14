<?php

/**
 * Framework options.class file.
 *
 * @link       https://shapedplugin.com/
 * @since      1.0.0
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/framework
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	die; } // Cannot access directly.

if ( ! class_exists( 'SP_WCS_Options' ) ) {
	/**
	 *
	 * Options Class
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	class SP_WCS_Options extends SP_WCS_Abstract {

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
		 * Default Arguments.
		 *
		 * @var array
		 */
		public $args = array(

			// framework title.
			'framework_title'         => 'ShapedPlugin',
			'framework_class'         => '',

			// menu settings.
			'menu_title'              => '',
			'menu_slug'               => '',
			'menu_type'               => 'menu',
			'menu_capability'         => 'manage_options',
			'menu_icon'               => null,
			'menu_position'           => null,
			'menu_hidden'             => false,
			'menu_parent'             => '',

			// menu extras.
			'show_bar_menu'           => true,
			'show_sub_menu'           => true,
			'show_network_menu'       => true,
			'show_in_customizer'      => false,

			'show_search'             => true,
			'show_reset_all'          => true,
			'show_reset_section'      => true,
			'show_footer'             => true,
			'show_all_options'        => true,
			'sticky_header'           => true,
			'save_defaults'           => true,
			'ajax_save'               => true,

			// admin bar menu settings.
			'admin_bar_menu_icon'     => '',
			'admin_bar_menu_priority' => 80,

			// footer.
			'footer_text'             => '',
			'footer_after'            => '',

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
			'theme'                   => 'dark',

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

			if ( ! empty( $this->args['show_network_menu'] ) ) {
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
						$result[] = $sub;
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

			if ( ! empty( $this->args['show_bar_menu'] ) && empty( $this->args['menu_hidden'] ) ) {

				global $submenu;

				$menu_slug = $this->args['menu_slug'];
				$menu_icon = ( ! empty( $this->args['admin_bar_menu_icon'] ) ) ? '<span class="spf-ab-icon ab-icon ' . $this->args['admin_bar_menu_icon'] . '"></span>' : '';

				$wp_admin_bar->add_node(
					array(
						'id'    => $menu_slug,
						'title' => $menu_icon . $this->args['menu_title'],
						'href'  => ( is_network_admin() ) ? network_admin_url( 'admin.php?page=' . $menu_slug ) : admin_url( 'admin.php?page=' . $menu_slug ),
					)
				);

				if ( ! empty( $submenu[ $menu_slug ] ) ) {
					foreach ( $submenu[ $menu_slug ] as $key => $menu ) {
						$wp_admin_bar->add_node(
							array(
								'parent' => $menu_slug,
								'id'     => $menu_slug . '-' . $key,
								'title'  => $menu[0],
								'href'   => ( is_network_admin() ) ? network_admin_url( 'admin.php?page=' . $menu[2] ) : admin_url( 'admin.php?page=' . $menu[2] ),
							)
						);
					}
				}

				if ( ! empty( $this->args['show_network_menu'] ) ) {
					$wp_admin_bar->add_node(
						array(
							'parent' => 'network-admin',
							'id'     => $menu_slug . '-network-admin',
							'title'  => $menu_icon . $this->args['menu_title'],
							'href'   => network_admin_url( 'admin.php?page=' . $menu_slug ),
						)
					);
				}
			}

		}

		/**
		 * Ajax_save
		 *
		 * @return void
		 */
		public function ajax_save() {

			if ( ! empty( $_POST['data'] ) ) {

				$_POST = json_decode( stripslashes( $_POST['data'] ), true ); // phpcs:ignore

				if ( ! empty( $_POST['spf_options_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['spf_options_nonce'] ) ), 'spf_options_nonce' ) ) {

					$this->set_options();

					wp_send_json_success(
						array(
							'success' => true,
							'notice'  => $this->notice,
							'errors'  => $this->errors,
						)
					);

				}
			}

			wp_send_json_error(
				array(
					'success' => false,
					'error'   => esc_html__(
						'Error while saving.',
						'woo-category-slider'
					),
				)
			);

		}

		/**
		 * Get default value
		 *
		 * @param  mixed $field field.
		 * @param  array $options options.
		 * @return mixed
		 */
		public function get_default( $field, $options = array() ) {

			$default = ( isset( $this->args['defaults'][ $field['id'] ] ) ) ? $this->args['defaults'][ $field['id'] ] : '';
			$default = ( isset( $field['default'] ) ) ? $field['default'] : $default;
			$default = ( isset( $options[ $field['id'] ] ) ) ? $options[ $field['id'] ] : $default;

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
					$this->options[ $field['id'] ] = $this->get_default( $field, $this->options );
				}
			}

			if ( $this->args['save_defaults'] && empty( $tmp_options ) ) {
				$this->save_options( $this->options );
			}

		}

		/**
		 * Set options.
		 *
		 * @return bool
		 */
		public function set_options() {

			if ( wp_verify_nonce( spf_get_var( 'spf_options_nonce' ), 'spf_options_nonce' ) ) {

				$request    = spf_get_var( $this->unique, array() );
				$transient  = spf_get_var( 'spf_transient' );
				$section_id = ( ! empty( $transient['section'] ) ) ? $transient['section'] : '';

				// import data.
				if ( ! empty( $transient['spf_import_data'] ) ) {

					$import_data = json_decode( stripslashes( trim( $transient['spf_import_data'] ) ), true );
					$request     = ( is_array( $import_data ) ) ? $import_data : array();

					$this->notice = esc_html__( 'Success. Imported backup options.', 'woo-category-slider-grid' );

				} elseif ( ! empty( $transient['reset'] ) ) {

					foreach ( $this->pre_fields as $field ) {
						if ( ! empty( $field['id'] ) ) {
							$request[ $field['id'] ] = $this->get_default( $field );
						}
					}

					$this->notice = esc_html__( 'Default options restored.', 'woo-category-slider-grid' );

				} elseif ( ! empty( $transient['reset_section'] ) && ! empty( $section_id ) ) {

					if ( ! empty( $this->pre_sections[ $section_id - 1 ]['fields'] ) ) {

						foreach ( $this->pre_sections[ $section_id - 1 ]['fields'] as $field ) {
							if ( ! empty( $field['id'] ) ) {
								$request[ $field['id'] ] = $this->get_default( $field );
							}
						}
					}

					$this->notice = esc_html__( 'Default options restored for only this section.', 'woo-category-slider-grid' );

				} else {

					// sanitize and validate.
					foreach ( $this->pre_fields as $field ) {

						if ( ! empty( $field['id'] ) ) {

							// sanitize.
							if ( ! empty( $field['sanitize'] ) ) {

								$sanitize                = $field['sanitize'];
								$value_sanitize          = isset( $request[ $field['id'] ] ) ? $request[ $field['id'] ] : '';
								$request[ $field['id'] ] = call_user_func( $sanitize, $value_sanitize );

							}

							// validate.
							if ( ! empty( $field['validate'] ) ) {

								$value_validate = isset( $request[ $field['id'] ] ) ? $request[ $field['id'] ] : '';
								$has_validated  = call_user_func( $field['validate'], $value_validate );

								if ( ! empty( $has_validated ) ) {
									$request[ $field['id'] ]      = ( isset( $this->options[ $field['id'] ] ) ) ? $this->options[ $field['id'] ] : '';
									$this->errors[ $field['id'] ] = $has_validated;
								}
							}

							// auto sanitize.
							if ( ! isset( $request[ $field['id'] ] ) || is_null( $request[ $field['id'] ] ) ) {
								$request[ $field['id'] ] = '';
							}
						}
					}
				}

				// ignore nonce requests.
				if ( isset( $request['_nonce'] ) ) {
					unset( $request['_nonce'] ); }

				$request = wp_unslash( $request );

				$request = apply_filters( "spf_{$this->unique}_save", $request, $this );

				do_action( "spf_{$this->unique}_save_before", $request, $this );

				$this->options = $request;

				$this->save_options( $request );

				do_action( "spf_{$this->unique}_save_after", $request, $this );

				if ( empty( $this->notice ) ) {
					$this->notice = esc_html__( 'Settings saved.', 'woo-category-slider-grid' );
				}
			}

			return true;

		}

		/**
		 * Save options database.
		 *
		 * @param  mixed $request Request.
		 * @return void
		 */
		public function save_options( $request ) {

			if ( 'transient' === $this->args['database'] ) {
				set_transient( $this->unique, $request, $this->args['transient_time'] );
			} elseif ( 'theme_mod' === $this->args['database'] ) {
				set_theme_mod( $this->unique, $request );
			} elseif ( 'network' === $this->args['database'] ) {
				update_site_option( $this->unique, $request );
			} else {
				update_option( $this->unique, $request );
			}

			do_action( "spf_{$this->unique}_saved", $request, $this );

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

			extract( $this->args ); // phpcs:ignore

			if ( 'submenu' === $menu_type ) {

				$menu_page = call_user_func( 'add_submenu_page', $menu_parent, $menu_title, $menu_title, $menu_capability, $menu_slug, array( &$this, 'add_options_html' ) );

			} else {

				$menu_page = call_user_func( 'add_menu_page', $menu_title, $menu_title, $menu_capability, $menu_slug, array( &$this, 'add_options_html' ), $menu_icon, $menu_position );

				if ( ! empty( $this->args['show_sub_menu'] ) && count( $this->pre_tabs ) > 1 ) {

					// create submenus.
					$tab_key = 1;
					foreach ( $this->pre_tabs as $section ) {

						call_user_func( 'add_submenu_page', $menu_slug, $section['title'], $section['title'], $menu_capability, $menu_slug . '#tab=' . $tab_key, '__return_null' );

						if ( ! empty( $section['subs'] ) ) {
							$tab_key += ( count( $section['subs'] ) - 1 );
						}

						$tab_key++;

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
		 * Add_page_on_load
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

			echo '<div class="spf spf-theme-' . esc_attr( $this->args['theme'] ) . ' spf-options' . esc_attr( $wrapper_class ) . '" data-slug="' . esc_attr( $this->args['menu_slug'] ) . '" data-unique="' . esc_attr( $this->unique ) . '">';

			$error_class = ( ! empty( $this->errors ) ) ? ' spf-form-show' : '';

			echo '<div class="spf-form-result spf-form-error' . esc_attr( $error_class ) . '">';
			if ( ! empty( $this->errors ) ) {
				foreach ( $this->errors as $error ) {
					echo '<i class="spf-label-error">!</i> ' . wp_kses_post( $error ) . '<br />';
				}
			}
			echo '</div>';

			echo '<div class="spf-container">';

			echo '<form method="post" action="" enctype="multipart/form-data" id="spf-form">';

			echo '<input type="hidden" class="spf-section-id" name="spf_transient[section]" value="1">';
			wp_nonce_field( 'spf_options_nonce', 'spf_options_nonce' );

			echo '<div class="spf-header' . esc_attr( $sticky_class ) . '">';
			echo '<div class="spf-header-inner">';

			echo '<div class="spf-header-left">';
			if ( $show_buttons ) {
				echo '<h1><img src="' . esc_attr( SP_WCS_URL . 'admin/img/wcs-icon-color.svg' ) . '" alt="">' . wp_kses_post( $this->args['framework_title'] ) . '</h1>';
			} else {
				echo '<h1><img src="' . SP_WCS_URL . 'admin/img/import-export.svg" alt="">' . wp_kses_post( $this->args['framework_title'] ) . '</h1>';
			}
			echo '</div>';
			echo '<div class="spf-header-right">';
			if ( $show_buttons ) {
				echo '<div class="spf-buttons">';
				echo '<input type="submit" name="' . esc_attr( $this->unique ) . '[_nonce][save]" class="button button-primary spf-save' . esc_attr( $ajax_class ) . '" value="' . esc_html__( 'Save Settings', 'woo-category-slider-grid' ) . '" data-save="' . esc_html__( 'Saving...', 'woo-category-slider-grid' ) . '">';
				echo ( $this->args['show_reset_section'] ) ? '<input type="submit" name="spf_transient[reset_section]" class="button button-secondary spf-reset-section spf-confirm" value="' . esc_html__( 'Reset Tab', 'woo-category-slider-grid' ) . '" data-confirm="' . esc_html__( 'Are you sure to reset all the settings of this tab?', 'woo-category-slider-grid' ) . '">' : '';
				echo ( $this->args['show_reset_all'] ) ? '<input type="submit" name="spf_transient[reset]" class="button button-secondary spf-warning-primary spf-reset-all spf-confirm" value="' . esc_html__( 'Reset All', 'woo-category-slider-grid' ) . '" data-confirm="' . esc_html__( 'Are you sure to reset all options?', 'woo-category-slider-grid' ) . '">' : '';
				echo '</div>';
			}
			echo '</div>';

			echo '<div class="clear"></div>';
			echo '</div>';
			echo '</div>';

			echo '<div class="spf-wrapper' . esc_attr( $show_all ) . '">';

			if ( $has_nav ) {
				echo '<div class="spf-nav spf-nav-options">';

				echo '<ul>';

				$tab_key = 1;

				foreach ( $this->pre_tabs as $tab ) {

					$tab_error = $this->error_check( $tab );
					$tab_icon  = ( ! empty( $tab['icon'] ) ) ? '<i class="' . $tab['icon'] . '"></i>' : '';

					if ( ! empty( $tab['subs'] ) ) {

						echo '<li class="spf-tab-depth-0">';

						echo '<a href="#tab=' . esc_attr( $tab_key ) . '" class="spf-arrow">' . wp_kses_post( $tab_icon . $tab['title'] . $tab_error ) . '</a>';

						echo '<ul>';

						foreach ( $tab['subs'] as $sub ) {

							$sub_error = $this->error_check( $sub );
							$sub_icon  = ( ! empty( $sub['icon'] ) ) ? '<i class="' . $sub['icon'] . '"></i>' : '';

							echo '<li class="spf-tab-depth-1"><a id="spf-tab-link-' . esc_attr( $tab_key ) . '" href="#tab=' . esc_attr( $tab_key ) . '">' . wp_kses_post( $sub_icon . $sub['title'] . $sub_error ) . '</a></li>';

							$tab_key++;
						}

						echo '</ul>';

						echo '</li>';

					} else {

						echo '<li class="spf-tab-depth-0"><a id="spf-tab-link-' . esc_attr( $tab_key ) . '" href="#tab=' . esc_attr( $tab_key ) . '">' . wp_kses_post( $tab_icon . $tab['title'] . $tab_error ) . '</a></li>';

						$tab_key++;
					}
				}

				echo '</ul>';

				echo '</div>';

			}

			echo '<div class="spf-content">';

			echo '<div class="spf-sections">';

			$section_key = 1;

			foreach ( $this->pre_sections as $section ) {

				$onload       = ( ! $has_nav ) ? ' spf-onload' : '';
				$section_icon = ( ! empty( $section['icon'] ) ) ? '<i class="spf-icon ' . $section['icon'] . '"></i>' : '';

				echo '<div id="spf-section-' . esc_attr( $section_key ) . '" class="spf-section' . esc_attr( $onload ) . '">';
				echo ( $has_nav ) ? '<div class="spf-section-title"><h3>' . wp_kses_post( $section_icon . $section['title'] ) . '</h3></div>' : '';
				echo ( ! empty( $section['description'] ) ) ? '<div class="spf-field spf-section-description">' . wp_kses_post( $section['description'] ) . '</div>' : '';

				if ( ! empty( $section['fields'] ) ) {

					foreach ( $section['fields'] as $field ) {

						$is_field_error = $this->error_check( $field );

						if ( ! empty( $is_field_error ) ) {
							$field['_error'] = $is_field_error;
						}

						$value = ( ! empty( $field['id'] ) && isset( $this->options[ $field['id'] ] ) ) ? $this->options[ $field['id'] ] : '';

						SP_WCS::field( $field, $value, $this->unique, 'options' );

					}
				} else {

					echo '<div class="spf-no-option spf-text-muted">' . esc_html__( 'No option provided by developer.', 'woo-category-slider-grid' ) . '</div>';

				}

				echo '</div>';

				$section_key++;
			}

			echo '</div>';

			echo '<div class="clear"></div>';

			echo '</div>';

			echo '<div class="spf-nav-background"></div>';

			echo '</div>';

			echo '</form>';

			echo '</div>';

			echo '<div class="clear"></div>';

			echo ( ! empty( $this->args['footer_after'] ) ) ? wp_kses_post( $this->args['footer_after'] ) : '';
			echo '</div>';

		}
	}
}
