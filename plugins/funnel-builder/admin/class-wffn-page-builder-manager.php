<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFFN_Page_Builder_Manager
 * Handles All the methods about page builder activities
 */
if ( ! class_exists( 'WFFN_Page_Builder_Manager' ) ) {
	#[AllowDynamicProperties]

class WFFN_Page_Builder_Manager {

		private static $ins = null;
		private $funnel = null;
		private $installed_plugins = null;

		public function __construct() {

		}

		/**
		 * @return WFFN_Page_Builder_Manager|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function builder_status( $builder ) {

			if ( ! function_exists( 'activate_plugin' ) ) {
				include_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$data                     = array();
			$pageBuildersTexts        = $this->localize_page_builder_texts();
			$pageBuildersOptions      = $this->get_plugins_groupby_page_builders();
			$data['builders_texts']   = isset( $pageBuildersTexts[ $builder ] ) ? $pageBuildersTexts[ $builder ] : array();
			$data['builders_options'] = isset( $pageBuildersOptions[ $builder ]['plugins'][0] ) ? $pageBuildersOptions[ $builder ]['plugins'][0] : array();

			return $data;
		}

		public function localize_page_builder_texts() {
			$get_all_opted_page_builders = WFFN_Core()->admin->get_all_active_page_builders();
			$pageBuildersTexts           = [];

			if ( empty( $get_all_opted_page_builders ) ) {
				return $pageBuildersTexts;
			}

			foreach ( $get_all_opted_page_builders as $builder ) {
				$page_builder    = $this->get_dependent_plugins_for_page_builder( $builder );
				$plugin_string   = sprintf( __( 'This template needs <strong>%s plugin</strong> activated.', 'funnel-builder' ), esc_html( $page_builder['title'] ) );
				$button_text     = __( 'Activate', 'funnel-builder' );
				$cancel_btn      = __( 'Cancel', 'funnel-builder' );
				$no_install      = 'no';
				$title           = __( 'Import Template', 'funnel-builder' );
				$install_fail    = __( 'We are unable to install the page builder plugin.', 'funnel-builder' );
				$activate_fail   = __( 'We are unable to activate the page builder plugin.', 'funnel-builder' );
				$show_cancel_btn = 'yes';
				$plugin_status   = isset( $page_builder['plugin-status'] ) ? $page_builder['plugin-status'] : '';
				$theme_status    = isset( $page_builder['theme-status'] ) ? $page_builder['theme-status'] : '';
				$string          = sprintf( __( ' Click the button to install and activate %s.', 'funnel-builder' ), esc_html( $page_builder['title'] ) );
				$install         = sprintf( __( ' Install and activate %s.', 'funnel-builder' ), esc_html( $page_builder['title'] ) );
				$builder_link    = '';


				/**
				 * If its a divi builder we need to handle few cases down there for best user experience
				 */
				if ( 'divi' === $builder ) {

					if ( 'activated' !== $theme_status && 'activate' === $plugin_status ) {
						$plugin_string .= $string;
					} else {
						$plugin_string .= $install;
						$button_text   = __( 'Install Divi Builder', 'funnel-builder' );
						$no_install    = 'yes';
						$builder_link  = esc_url( 'https://www.elegantthemes.com/' );
					}
				} else if ( 'oxy' === $builder ) {
					if ( 'install' === $plugin_status ) {
						$plugin_string .= $string;
						$button_text   = __( 'Install Oxygen Builder', 'funnel-builder' );
						$no_install    = 'yes';
						$builder_link  = esc_url( 'https://oxygenbuilder.com/' );
					} else {
						$plugin_string .= $string;
					}
				} else {
					$plugin_string .= $string;
				}

				$pageBuildersTexts[ $builder ] = array(
					'text'            => $plugin_string,
					'ButtonText'      => $button_text,
					'noInstall'       => $no_install,
					'title'           => $title,
					'install_fail'    => $install_fail,
					'activate_fail'   => $activate_fail,
					'plugin_status'   => $plugin_status,
					'show_cancel_btn' => $show_cancel_btn,
					'close_btn'       => $cancel_btn,
					'builder_link'    => $builder_link,
				);
			}

			return $pageBuildersTexts;
		}

		public function get_dependent_plugins_for_page_builder( $page_builder_slug = '', $default = 'elementor' ) {
			$plugins = $this->get_plugins_groupby_page_builders();

			if ( array_key_exists( $page_builder_slug, $plugins ) ) {
				return $plugins[ $page_builder_slug ];
			}

			return $plugins[ $default ];
		}

		/**
		 * Get Plugins list by page builder.
		 *
		 * @return array Required Plugins list.
		 * @since 1.1.4
		 *
		 */
		public function get_plugins_groupby_page_builders() {

			$divi_status  = WFFN_Common::get_plugin_status( 'divi-builder/divi-builder.php' );
			$theme_status = 'not-installed';
			if ( $divi_status ) {
				if ( true === $this->is_divi_theme_installed() ) {

					if ( false === $this->is_divi_theme_enabled() ) {
						$theme_status = 'deactivated';
					} else {
						$theme_status = 'activated';
						$divi_status  = '';
					}
				}
			}

			$oxygen_status = WFFN_Common::get_plugin_status( 'oxygen/functions.php' );
			$plugins       = array(
				'elementor' => array(
					'title'         => 'Elementor',
					'plugin-status' => WFFN_Common::get_plugin_status( 'elementor/elementor.php' ),
					'plugins'       => array(
						array(
							'slug'   => 'elementor', // For download from wordpress.org.
							'init'   => 'elementor/elementor.php',
							'status' => WFFN_Common::get_plugin_status( 'elementor/elementor.php' ),
						),
					),
				),
				'gutenberg' => array(
					'title'         => 'SlingBlocks',
					'plugin-status' => WFFN_Common::get_plugin_status( 'slingblocks/slingblocks.php' ),
					'plugins'       => array(
						array(
							'slug'   => 'slingblocks', // For download from wordpress.org.
							'init'   => 'slingblocks/slingblocks.php',
							'status' => WFFN_Common::get_plugin_status( 'slingblocks/slingblocks.php' ),
						),
					),
				),
				'divi'      => array(
					'title'         => 'Divi',
					'theme-status'  => $theme_status,
					'plugin-status' => $divi_status,
					'plugins'       => array(
						array(
							'slug'   => 'divi-builder', // For download from wordpress.org.
							'init'   => 'divi-builder/divi-builder.php',
							'status' => $divi_status,
						),
					),
				),
				'oxy'       => array(
					'title'         => 'Oxygen',
					'plugin-status' => $oxygen_status,
					'plugins'       => array(
						array(
							'slug'   => 'oxygen', // For download from wordpress.org.
							'init'   => 'oxygen/functions.php',
							'status' => $oxygen_status,
						),
					),
				),
			);

			$plugins['beaver-builder'] = array(
				'title'   => 'Beaver Builder',
				'plugins' => array(),
			);

			// Check if Pro Exist.
			if ( file_exists( WP_PLUGIN_DIR . '/' . 'bb-plugin/fl-builder.php' ) && ! is_plugin_active( 'beaver-builder-lite-version/fl-builder.php' ) ) {
				$plugins['beaver-builder']['plugin-status'] = WFFN_Common::get_plugin_status( 'bb-plugin/fl-builder.php' );
				$plugins['beaver-builder']['plugins'][]     = array(
					'slug'   => 'bb-plugin',
					'init'   => 'bb-plugin/fl-builder.php',
					'status' => WFFN_Common::get_plugin_status( 'bb-plugin/fl-builder.php' ),
				);
			} else {
				$plugins['beaver-builder']['plugin-status'] = WFFN_Common::get_plugin_status( 'beaver-builder-lite-version/fl-builder.php' );
				$plugins['beaver-builder']['plugins'][]     = array(
					'slug'   => 'beaver-builder-lite-version', // For download from wordpress.org.
					'init'   => 'beaver-builder-lite-version/fl-builder.php',
					'status' => WFFN_Common::get_plugin_status( 'beaver-builder-lite-version/fl-builder.php' ),
				);
			}
			$plugins['wp_editor']['plugins'][] = array(
				'slug'   => '',
				'status' => null,
			);

			return $plugins;
		}


		/**
		 * Check if Divi theme is install status.
		 *
		 * @return boolean
		 */
		public function is_divi_theme_installed() {
			foreach ( (array) wp_get_themes() as $theme ) {
				if ( 'Divi' === $theme->name || 'Divi' === $theme->parent_theme || 'Extra' === $theme->name || 'Extra' === $theme->parent_theme ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Check if divi theme enabled for post id.
		 *
		 * @param object $theme theme data.
		 *
		 * @return boolean
		 */
		public function is_divi_theme_enabled( $theme = false ) {

			if ( ! $theme ) {
				$theme = wp_get_theme();
			}

			if ( 'Divi' === $theme->name || 'Divi' === $theme->parent_theme || 'Extra' === $theme->name || 'Extra' === $theme->parent_theme ) {
				return true;
			}

			return false;
		}

		public function get_all_page_builders() {
			return array(
				array(
					'name'  => 'Elementor',
					'value' => 'elementor',
				),
				array(
					'name'  => 'Divi',
					'value' => 'divi',
				),
				/**array(
				 * 'name'  => 'Beaver Builder',
				 * 'value' => 'beaver-builder',
				 * ),
				 */
			);
		}

	}


	if ( class_exists( 'WFFN_Core' ) ) {
		WFFN_Core::register( 'page_builders', 'WFFN_Page_Builder_Manager' );
	}
}
