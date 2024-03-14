<?php
/**
 * A class that handles loading custom modules and custom
 * fields if the builder is installed and activated.
 */

if ( ! class_exists( 'Xpro_Bundle_Lite_For_WP_Init' ) ) {
	class Xpro_Bundle_Lite_For_WP_Init {


		/**
		 * Initializes the class once all plugins have loaded.
		 */
		public static function init() {
			self::includes();

			add_action( 'admin_menu', __CLASS__ . '::xpro_addons_for_bb_dashboard_menu' );
			add_action( 'init', __CLASS__ . '::load_modules' );
            add_action( 'fl_builder_after_save_layout', __CLASS__ . '::clear_cache_for_all_sites' );
            add_filter( 'plugin_action_links_' . XPRO_ADDONS_FOR_BB_BASE, __CLASS__ . '::plugin_action_links' );
			add_filter( 'upload_mimes', __CLASS__ . '::xpro_add_custom_upload_mimes' );

			if ( isset( $_GET['page'] ) == 'xpro_dashboard_welcome' || isset( $_GET['page'] ) == 'xpro_settings_for_beaver' || isset( $_GET['page'] ) == 'xpro_dashboard_templates' ) {
				add_action( 'admin_enqueue_scripts', __CLASS__ . '::enqueue_xpro_admin_styles' );
			}
			add_action( 'admin_enqueue_scripts', __CLASS__ . '::enqueue_xpro_wp_admin_icon_styles' );
		}

		/**
		 * Loads our custom modules.
		 */
		public static function load_modules() {
			if ( ! class_exists( 'FLBuilder' ) ) {
				return;
			}
			$all_modules    = Xpro_Beaver_Modules_List::instance()->get_list();
			$active_modules = Xpro_Beaver_Dashboard_Utils::instance()->get_option( 'xpro_beaver_modules_list', array_keys( $all_modules ) );

			foreach ( $active_modules as $module_slug ) {
				if ( array_key_exists( $module_slug, $all_modules ) ) {
					if ( 'pro-disabled' !== $all_modules[ $module_slug ]['package'] && 'pro' !== $all_modules[ $module_slug ]['package'] ) {
						require_once XPRO_ADDONS_FOR_BB_DIR . 'modules/' . str_replace( '_', '-', $module_slug ) . '/' . str_replace( '_', '-', $module_slug ) . '.php';
					}
				}
			}

		}

        public static function clear_cache_for_all_sites() {

            // Clear builder cache.
            FLBuilderModel::delete_asset_cache_for_all_posts();

            // Clear theme cache.
            if ( class_exists( 'FLCustomizer' ) && method_exists( 'FLCustomizer', 'clear_all_css_cache' ) ) {
                FLCustomizer::clear_all_css_cache();
            }
        }

		public static function xpro_addons_for_bb_dashboard_menu() {
			add_menu_page( 'Xpro Addons BB', 'Xpro Addons', 'manage_options', 'xpro_dashboard_welcome', 'xpro_dashboard_welcome', plugins_url( 'xpro-addons-beaver-builder-elementor/assets/images/XproX.svg' ), 66 );
			add_submenu_page( 'xpro_dashboard_welcome', 'Xpro Addons For Beaver Builder', 'Welcome', 'manage_options', 'xpro_dashboard_welcome', 'xpro_dashboard_welcome' );
			add_submenu_page( 'xpro_dashboard_welcome', 'Templates', 'Templates', 'manage_options', 'xpro_dashboard_templates', 'xpro_dashboard_templates', 99 );
			if ( did_action( 'xpro_addons_for_bb_pro_loaded' ) || did_action( 'xpro_beaver_themer_loaded' ) || did_action( 'xpro_gallery_for_bb_pro_loaded' ) || did_action( 'xpro_portfolio_for_bb_pro_loaded' ) || did_action( 'xpro_slider_for_bb_pro_loaded' ) || did_action( 'xpro_themes_for_bb_pro_loaded' ) ) {
				add_submenu_page( 'xpro_dashboard_welcome', 'Settings', 'Settings', 'manage_options', 'xpro_settings_for_beaver', 'xpro_settings_for_beaver', 55 );

			}
		}

		public static function enqueue_xpro_admin_styles() {
			wp_enqueue_style( 'xpro-grid', XPRO_ADDONS_FOR_BB_URL . 'assets/css/xpro-grid.min.css', array(), XPRO_ADDONS_FOR_BB_VERSION );
			wp_enqueue_style( 'xpro-admin-style-lite', XPRO_ADDONS_FOR_BB_URL . 'assets/css/xpro-admin-style-lite.css', array(), XPRO_ADDONS_FOR_BB_VERSION );
			wp_enqueue_style( 'xpro-icons-min', XPRO_ADDONS_FOR_BB_URL . 'assets/css/xpro-icons.min.css', array(), XPRO_ADDONS_FOR_BB_VERSION );
			wp_enqueue_style( 'owl-theme-default', XPRO_ADDONS_FOR_BB_URL . 'assets/css/owl.theme.default.css', array(), XPRO_ADDONS_FOR_BB_VERSION );
			wp_enqueue_style( 'owl-carousel', XPRO_ADDONS_FOR_BB_URL . 'assets/css/owl.carousel.min.css', array(), XPRO_ADDONS_FOR_BB_VERSION );

			wp_enqueue_script( 'xpro-admin-script-lite', XPRO_ADDONS_FOR_BB_URL . 'assets/js/xpro-admin-script-lite.js', array( 'jquery' ), XPRO_ADDONS_FOR_BB_VERSION, true );
			wp_enqueue_script( 'isotope-pkgd-min', XPRO_ADDONS_FOR_BB_URL . 'assets/js/isotope.pkgd.min.js', array( 'jquery' ), XPRO_ADDONS_FOR_BB_VERSION, true );
			wp_enqueue_script( 'owlcarousel-filter', XPRO_ADDONS_FOR_BB_URL . 'assets/js/owlcarousel-filter.min.js', array( 'jquery' ), '2.3.4', true );
			wp_enqueue_script( 'owl-carousel', XPRO_ADDONS_FOR_BB_URL . 'assets/js/owl.carousel.min.js', array( 'jquery' ), '2.3.4', true );
			wp_enqueue_script( 'in-view', XPRO_ADDONS_FOR_BB_URL . 'assets/js/in-view.min.js', array( 'jquery' ), XPRO_ADDONS_FOR_BB_VERSION, true );
            wp_enqueue_script( 'lazyload-min', XPRO_ADDONS_FOR_BB_URL . 'assets/js/jquery.lazyload.min.js', array( 'jquery' ), XPRO_ADDONS_FOR_BB_VERSION, true );
            wp_enqueue_script('xpro-cloud-template', XPRO_ADDONS_FOR_BB_URL . 'assets/js/xpro-cloud-template.js', array('jquery'), null, true);
            $xprocloudtemplates = array(
                'ajaxurl'                => admin_url( 'admin-ajax.php' ),
                'errorMessage'           => __( 'Something went wrong!', 'xpro' ),
                'successMessage'         => __( 'Complete', 'xpro' ),
                'successMessageFetch'    => __( 'Refreshed!', 'xpro' ),
                'errorMessageTryAgain'   => __( 'Try Again!', 'xpro' ),
                'successMessageDownload' => __( 'Installed!', 'xpro' ),
                'btnTextDownload'        => __( 'Install', 'xpro' ),
                'btnTextInstall'         => __( 'Installed', 'xpro' ),
                'successMessageRemove'   => __( 'Removed!', 'xpro' ),
                'text'    => array(
                    'failed'        => esc_html__( 'Failed', 'xpro-import' ),
                    'error'         => esc_html__( 'Error', 'xpro-import' ),
                    'skip'          => esc_html__( 'Skipping', 'xpro-import' ),
                    'confirmImport' => array(
                        'title'             => esc_html__( 'Xpro Import! Just a step away', 'xpro-import' ),
                        'html'              => sprintf(
                        /* translators: 1: message 1, 2: message 2., 3: message 3., 4: message 4. */
                            __( 'Importing demo data is the easiest way to setup your theme. It will allow you to quickly edit everything instead of creating content from scratch. Also, read following points before importing the demo: %1$s %2$s %3$s %4$s', 'xpro-import' ),
                            '<ol><li class="warning">' . __( 'It is highly recommended to import demo on fresh WordPress installation to exactly replicate the theme demo. If no important data on your site, you can reset it from Reset Wizard at the top', 'xpro-import' ) . '</li>',
                            '<li>' . __( 'No existing posts, pages, categories, images, custom post types or any other data will be deleted or modified.', 'xpro-import' ) . '</li>',
                            '<li>' . __( 'It will install the plugins required for demo and activate them. Also posts, pages, images, widgets, & other data will get imported.', 'xpro-import' ) . '</li>',
                            '<li>' . __( 'Please click on the Import button and wait, it will take some time to import the data.', 'xpro-import' ) . '</li></ol>'
                        ),
                        'confirmButtonText' => esc_html__( 'Yes, Import Demo!', 'xpro-import' ),
                        'cancelButtonText'  => esc_html__( 'Cancel', 'xpro-import' ),
                    ),
                    'confirmReset'  => array(
                        'title'             => esc_html__( 'Are you sure?', 'xpro-import' ),
                        'text'              => __( "You won't be able to revert this!", 'xpro-import' ),
                        'confirmButtonText' => esc_html__( 'Yes, Reset', 'xpro-import' ),
                        'cancelButtonText'  => esc_html__( 'Cancel', 'xpro-import' ),
                    ),
                    'resetSuccess'  => array(
                        'title'             => esc_html__( 'Reset Successful', 'xpro-import' ),
                        'confirmButtonText' => esc_html__( 'Ok', 'xpro-import' ),
                    ),
                    'failedImport'  => array(
                        'code' => __( 'Error Code:', 'xpro-import' ),
                        'text' => __( 'Contact theme author or try again', 'xpro-import' ),
                    ),
                    'successImport' => array(
                        'confirmButtonText' => esc_html__( 'Visit My Site', 'xpro-import' ),
                        'cancelButtonText'  => esc_html__( 'Okay', 'xpro-import' ),
                    ),
                ),
            );
            wp_localize_script( 'xpro-cloud-template', 'XPROCloudTemplates ', $xprocloudtemplates );
		}

		public static function enqueue_xpro_wp_admin_icon_styles() {

			wp_enqueue_style( 'xpro-wp-admin-menu-icon', XPRO_ADDONS_FOR_BB_URL . 'assets/css/xpro-wp-admin-menu-icon.css', array(), XPRO_ADDONS_FOR_BB_VERSION );

		}

		public static function includes() {
            require_once XPRO_ADDONS_FOR_BB_DIR . 'dashboard/dashboard.php';
			require_once XPRO_ADDONS_FOR_BB_DIR . 'classes/class-xpro-plugins-helper.php';
            require_once XPRO_ADDONS_FOR_BB_DIR . 'classes/class-custom-post-type.php';
			require_once XPRO_ADDONS_FOR_BB_DIR . 'dashboard/xpro-dashboard-setting.php';
			require_once XPRO_ADDONS_FOR_BB_DIR . 'dashboard/xpro-modules-list.php';
			require_once XPRO_ADDONS_FOR_BB_DIR . 'dashboard/xpro-features-list.php';
            require_once XPRO_ADDONS_FOR_BB_DIR . 'classes/class-xpro-ui-panels.php';
			if ( ! did_action( 'xpro_themes_for_bb_pro_loaded' ) ) {
				require_once XPRO_ADDONS_FOR_BB_DIR . 'dashboard/xpro-templates-setting.php';
			}
			if ( ! did_action( 'xpro_addons_for_bb_pro_loaded' ) && ! did_action( 'xpro_beaver_themer_loaded' ) && ! did_action( 'xpro_gallery_for_bb_pro_loaded' ) && ! did_action( 'xpro_portfolio_for_bb_pro_loaded' ) && ! did_action( 'xpro_slider_for_bb_pro_loaded' ) && ! did_action( 'xpro_themes_for_bb_pro_loaded' ) ) {
				require_once XPRO_ADDONS_FOR_BB_DIR . 'classes/class-xpro-templates-liberary.php';
			}

		}

		/**
		 * Plugin action links.
		 *
		 * Adds action links to the plugin list table
		 *
		 * Fired by `plugin_action_links` filter.
		 *
		 * @param array $links An array of plugin action links.
		 *
		 * @return array An array of plugin action links.
		 * @since 1.5.0
		 * @access public
		 */
		public static function plugin_action_links( $links ) {

			$settings_link = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=xpro_dashboard_welcome' ), esc_html__( 'Settings', 'xpro-bb-addons' ) );

			array_unshift( $links, $settings_link );

			if ( did_action( 'xpro_addons_for_bb_pro_loaded' ) ) {
				$links['rate_us'] = sprintf( '<a href="%1$s" target="_blank" class="xpro-beaver-addons-gopro">%2$s</a>', 'https://wordpress.org/plugins/xpro-addons-beaver-builder-elementor/#reviews', esc_html__( 'Rate Us', 'xpro-bb-addons' ) );
			} else {
				$links['go_pro'] = sprintf( '<a href="%1$s" target="_blank" class="xpro-beaver-addons-gopro">%2$s</a>', 'https://beaver.wpxpro.com/bundle-pricing/', esc_html__( 'Go Pro', 'xpro-bb-addons' ) );
			}

			return $links;
		}

		public static function xpro_add_custom_upload_mimes( $existing_mimes ) {
			$existing_mimes['otf']   = 'application/x-font-otf';
			$existing_mimes['woff']  = 'application/x-font-woff';
			$existing_mimes['woff2'] = 'application/x-font-woff2';
			$existing_mimes['ttf']   = 'application/x-font-ttf';
			$existing_mimes['svg']   = 'image/svg+xml';
			$existing_mimes['eot']   = 'application/vnd.ms-fontobject';
			$existing_mimes['json']  = 'application/json';
			return $existing_mimes;
		}

	}
}

Xpro_Bundle_Lite_For_WP_Init::init();
