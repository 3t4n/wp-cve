<?php
/**
 * Main class
 *
 * @package YITH Essential Kit for Woocommerce #1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'YITH_JetPack' ) ) {
	/**
	 * Manage all features of a YIT Theme
	 */
	class YITH_JetPack {

		const PLUGIN_LIST_FILTER_MODULE_NAME          = 'yith_jetpack_modules';
		const ACTIVATED_MODULES_OPTION_BASE_NAME      = 'yith_jetpack_active_modules';
		const DEACTIVATED_PLUGIN_OPTION_NAME          = 'yith_jetpack_deactivated_plugin';
		const MODULE_LIST_OPTION_NAME                 = 'yith_jetpack_inserted_modules';
		const MODULE_LIST_ACTIVATION_HOOK_OPTION_NAME = 'yith_jetpack_activation_hook';
		const MODULES_LIST_QUERY_VALUE                = 'yith-jetpack-modules';


		/**
		 * Index ID
		 *
		 * @var int $index
		 */
		protected $index = 0;

		/**
		 * Plugin path
		 *
		 * @var string $_plugin_path
		 */
		protected $_plugin_path = '';

		/**
		 * Package title
		 *
		 * @var string $_package_title
		 */
		protected $_package_title = '';

		/**
		 * Filtered module name
		 *
		 * @var string $_plugin_list_filter_module_name
		 */
		public $_plugin_list_filter_module_name = null;

		/**
		 * Activated modules list option name
		 *
		 * @var string $_activate_module_option_name
		 */
		protected $_activate_module_option_name = null;

		/**
		 * Deactivated modules list option name
		 *
		 * @var string $_deactivated_plugin_option_name
		 */
		protected $_deactivated_plugin_option_name = null;

		/**
		 * Module list option
		 *
		 * @var string $_module_list_option_name
		 */
		protected $_module_list_option_name = null;

		/**
		 * Module activation hook
		 *
		 * @var string $_module_activation_hook_option_name
		 */
		protected $_module_activation_hook_option_name = null;

		/**
		 * Modules list
		 *
		 * @var string $_modules_list_query_value
		 */
		protected $_modules_list_query_value = null;

		/**
		 * All modules to activate
		 *
		 * @var array $_modules
		 */
		protected $_modules = null;

		/**
		 * All modules ativated
		 *
		 * @var array $_active_modules
		 */
		protected $_active_modules = array();

		/**
		 * Constructor
		 *
		 * @param string $path module path.
		 * @param string $title module title.
		 * @param int    $index module array idex.
		 *
		 * @since 1.0.0
		 */
		public function __construct( $path, $title, $index ) {

			$this->$index                              = $index;
			$this->_plugin_path                        = $path;
			$this->_menu_title                         = $title;
			$this->_plugin_list_filter_module_name     = self::PLUGIN_LIST_FILTER_MODULE_NAME . $this->$index;
			$this->_activate_module_option_name        = self::ACTIVATED_MODULES_OPTION_BASE_NAME . $this->$index;
			$this->_deactivated_plugin_option_name     = self::DEACTIVATED_PLUGIN_OPTION_NAME . $this->$index;
			$this->_module_list_option_name            = self::MODULE_LIST_OPTION_NAME . $this->$index;
			$this->_module_activation_hook_option_name = self::MODULE_LIST_ACTIVATION_HOOK_OPTION_NAME . $this->$index;
			$this->_modules_list_query_value           = self::MODULES_LIST_QUERY_VALUE . $this->$index;

			add_action(
				'wp_ajax_activate_yith_essential_kit_module',
				array(
					$this,
					'activate_module',
				)
			);
			add_action(
				'wp_ajax_deactivate_yith_essential_kit_module',
				array(
					$this,
					'deactivate_module',
				)
			);
			add_action(
				'wp_ajax_install_yith_essential_kit_module',
				array(
					$this,
					'install_module',
				)
			);

			add_action(
				'init',
				array(
					$this,
					'yith_essential_kit_for_woocommerce_1_register_required_plugins',
				),
				10
			);

			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );

			// admin page.
			add_action( 'admin_menu', array( $this, 'add_admin_modules_page' ), 95 );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			// Add action links.
			add_filter(
				'plugin_action_links_' . plugin_basename( YJP_DIR . '/' . basename( $this->_plugin_path ) ),
				array(
					$this,
					'action_links',
				)
			);
		}

		/**
		 * Register the required modules.
		 */
		public function yith_essential_kit_for_woocommerce_1_register_required_plugins() {
			/**
			 * Plugins list array
			 *
			 * @var array $plugins_list
			 */
			require_once dirname( __FILE__ ) . '/modules.php';
			$this->modules = $plugins_list;
		}


		/**
		 * Reset YIT_Framework option
		 *
		 * @param string $plugin Plugin name.
		 * @return void
		 */
		public function reset_yith_jetpack_option( $plugin ) {

			if ( plugin_basename( $this->_plugin_path ) == $plugin ) {
				delete_option( $this->_activate_module_option_name );
				delete_option( $this->_deactivated_plugin_option_name );
				delete_option( $this->_module_list_option_name );
				delete_option( $this->_plugin_list_hide_notice_option_name );
			}

		}

		/**
		 * Load plugin framework
		 *
		 * @return void
		 * @since  1.0
		 */
		public function plugin_fw_loader() {
			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once $plugin_fw_file;
				}
			}
		}

		/**
		 * Action Links
		 *
		 * Add the action links to plugin admin page.
		 *
		 * @param array $links | links plugin array.
		 *
		 * @return   mixed Array
		 * @use      plugin_action_links_{$plugin_file_name}
		 * @since    1.0
		 */
		public function action_links( $links ) {

			$links[] = '<a href="' . esc_url( admin_url( 'admin.php?page=' . $this->_modules_list_query_value ) ) . '">' . esc_html__( 'Modules List', 'yith-essential-kit-for-woocommerce-1' ) . '</a>';

			return $links;
		}

		/**
		 * Get the plugin url.
		 *
		 * @return string
		 *
		 * @since      2.0.0
		 */
		public function plugin_url() {
			return trailingslashit( plugins_url( '/', $this->_plugin_path ) );
		}

		/**
		 * Get the plugin path.
		 *
		 * @return string
		 *
		 * @since      2.0.0
		 */
		public function plugin_path() {
			return trailingslashit( plugin_dir_path( $this->_plugin_path ) );
		}

		/**
		 * Retrieve the pathname to the module file
		 *
		 * @param string $module The module to find the file specified on second parameter.
		 * @param string $path   The relative path to a file.
		 *
		 * @return string
		 * @since 1.0.0
		 */
		public function module_path( $module, $path = '' ) {
			return trailingslashit( $this->plugin_path() . 'modules/' . $module ) . $path;
		}


		/**
		 * Add the admin page for modules management
		 *
		 * @since 1.0.0
		 */
		public function add_admin_modules_page() {

			global $admin_page_hooks;

			if ( ! isset( $admin_page_hooks['yith_plugin_panel'] ) ) {
				$position = apply_filters( 'yith_plugins_menu_item_position', '62.32' );
				add_menu_page( 'yith_plugin_panel', esc_html__( 'YITH', 'yith-essential-kit-for-woocommerce-1' ), 'nosuchcapability', 'yith_plugin_panel', null, yith_plugin_fw_get_default_logo(), $position );
			}

			$title = $this->_menu_title;

			add_submenu_page(
				'yith_plugin_panel',
				$title,
				$title,
				'install_plugins',
				$this->_modules_list_query_value,
				array(
					$this,
					'admin_modules_page',
				)
			);

			/* === Duplicate Items Hack === */
			remove_submenu_page( 'yith_plugin_panel', 'yith_plugin_panel' );

		}


		/**
		 * Show the admin page content
		 *
		 * @since 1.0.0
		 */
		public function admin_modules_page() {
			include YJP_TEMPLATE_PATH . '/yith-list-plugins.php';
		}


		/**
		 * Admin Enqueue Script
		 *
		 * Add scripts and styles to sidebar panel
		 *
		 * @return   void
		 */
		public function admin_enqueue_scripts() {
			if ( isset( $_GET['page'] ) && $_GET['page'] == $this->_modules_list_query_value ) {
				wp_enqueue_style( 'yith-layout', YJP_ASSETS_URL . '/css/list-layout.css', array(), YJP_VERSION );
				wp_enqueue_script(
					'yith-essential-kit',
					YJP_ASSETS_URL . '/js/yith-essential-kit-1.js',
					array(
						'jquery',
						'jquery-blockui',
					),
					YJP_VERSION,
					true
				);
			}
		}


		/**
		 * Check if a plugin is installed. Does not take must-use plugins into account.
		 *
		 * @param string $slug Plugin slug.
		 * @param bool   $migration Migration check.
		 *
		 * @return bool True if installed, false otherwise.
		 */
		public function is_plugin_installed( $slug, $migration = false ) {
			$found = false;
			if ( $migration && 'yith-color-and-label-variations-for-woocommerce' == $slug ) {
				$slug = 'yith-woocommerce-colors-labels-variations';
			}
			if ( isset( $this->modules[ $slug ] ) ) {
				$module = $this->modules[ $slug ];
				$found  = file_exists( plugin_dir_path( __DIR__ ) . $module['slug'] . '/plugin-fw/init.php' );

				return $found;
			}
		}

		/**
		 * Check if a premium plugin is installed. Does not take must-use plugins into account.
		 *
		 * @param string $slug Plugin slug.
		 *
		 * @return bool True if installed, false otherwise.
		 */
		public function is_premium_installed( $slug ) {
			$found = false;
			if ( isset( $this->modules[ $slug ] ) ) {
				$module      = $this->modules[ $slug ];
				$init        = isset( $module['init'] ) ? $module['init'] : 'init.php';
				$premium_dir = isset( $module['premium-dir'] ) ? $module['premium-dir'] : $module['slug'] . '-premium';
				$found       = file_exists( plugin_dir_path( __DIR__ ) . $premium_dir . '/plugin-fw/' . $init );

				return $found;
			}
		}

		/**
		 * Check if a plugin is active.
		 *
		 * @param string $slug Plugin slug.
		 *
		 * @return bool True if active, false otherwise.
		 */
		public function is_plugin_active( $slug ) {
			$active = false;

			if ( isset( $this->modules[ $slug ] ) ) {
				$module = $this->modules[ $slug ];
				$active = is_plugin_active( isset( $module['init'] ) ? $module['slug'] . '/' . $module['init'] : $module['slug'] . '/init.php' );
			}

			return $active;
		}

		/**
		 * Check if a plugin is active.
		 *
		 * @param string $slug Plugin slug.
		 *
		 * @return bool True if active, false otherwise.
		 */
		public function is_premium_active( $slug ) {

			$active = false;

			if ( isset( $this->modules[ $slug ] ) ) {
				$module = $this->modules[ $slug ];
				$active = defined( $module['premium'] );
			}

			return $active;
		}


		/**
		 * Activate Module in Ajax
		 */
		public function activate_module() {
			$slug = isset( $_GET['slug'] ) ? sanitize_text_field( wp_unslash( $_GET['slug'] ) ) : false;

			$active = $this->is_plugin_active( $slug );
			$result = array(
				'status'  => false,
				'message' => esc_html__( 'Error during request', 'yith-essential-kit-for-woocommerce-1' ),
			);
			if ( $slug && ! $active ) {
				if ( isset( $this->modules[ $slug ] ) ) {
					$module       = $this->modules[ $slug ];
					$message      = sprintf( esc_html__( 'Module %s enabled', 'yith-essential-kit-for-woocommerce-1' ), $module['name'] );
					$fail_message = sprintf( esc_html__( 'Activation error for plugin %s', 'yith-essential-kit-for-woocommerce-1' ), $slug );
					$module       = $this->modules[ $slug ];
					$init         = isset( $module['init'] ) ? $module['slug'] . '/' . $module['init'] : $module['slug'] . '/init.php';
					$action       = activate_plugin( $init );
					$status       = ! is_wp_error( $action );
					$button       = $this->print_action_buttons( $slug );

					$result = array(
						'status'  => ! is_wp_error( $action ),
						'button'  => $button,
						'message' => $status ? $message : $fail_message,
					);
				}
			}
			wp_send_json( $result );
		}

		/**
		 * Activate Module in Ajax
		 */
		public function deactivate_module() {
			$slug   = isset( $_GET['slug'] ) ? sanitize_text_field( wp_unslash( $_GET['slug'] ) ) : false;
			$active = $this->is_plugin_active( $slug );
			$result = array(
				'status'  => false,
				'message' => esc_html__( 'Error during request', 'yith-essential-kit-for-woocommerce-1' ),
			);
			if ( $slug && $active ) {
				if ( isset( $this->modules[ $slug ] ) ) {
					$module       = $this->modules[ $slug ];
					$message      = sprintf( esc_html__( 'Module %s disabled', 'yith-essential-kit-for-woocommerce-1' ), $module['name'] );
					$fail_message = sprintf( esc_html__( 'Deactivation error for module %s', 'yith-essential-kit-for-woocommerce-1' ), $slug );
					$init         = isset( $module['init'] ) ? $module['slug'] . '/' . $module['init'] : $module['slug'] . '/init.php';
					$action       = deactivate_plugins( $init );
					$status       = ! is_wp_error( $action );
					$button       = $this->print_action_buttons( $slug );

					$result = array(
						'status'  => ! is_wp_error( $action ),
						'button'  => $button,
						'message' => $status ? $message : $fail_message,
					);
				}
			}
			wp_send_json( $result );
		}

		/**
		 * Install Module in Ajax
		 */
		public function install_module() {
			$slug      = isset( $_GET['slug'] ) ? sanitize_text_field( wp_unslash( $_GET['slug'] ) ) : false;
			$installed = $this->is_plugin_installed( $slug );
			$result    = array(
				'status'  => false,
				'message' => esc_html__( 'Error during request', 'yith-essential-kit-for-woocommerce-1' ),
			);
			if ( $slug && ! $installed ) {
				if ( isset( $this->modules[ $slug ] ) ) {
					$module       = $this->modules[ $slug ];
					$message      = sprintf( esc_html__( 'Module %s installed', 'yith-essential-kit-for-woocommerce-1' ), $module['name'] );
					$fail_message = sprintf( esc_html__( 'Installation error for plugin %s', 'yith-essential-kit-for-woocommerce-1' ), $slug );
					$url          = 'https://downloads.wordpress.org/plugin/' . $slug . '.zip';
					$action       = $this->install_module_routine( $url );
					$status       = ! is_wp_error( $action );
					$button       = $this->print_action_buttons( $slug );

					$result = array(
						'status'  => ! is_wp_error( $action ),
						'button'  => $button,
						'message' => $status ? $message : $fail_message,
					);
				}
			}
			wp_send_json( $result );
		}

		/**
		 * Module Install procedure
		 */
		public function install_module_routine( $url ) {
			if ( ! function_exists( 'request_filesystem_credentials' ) ) {
				include_once ABSPATH . 'wp-admin/includes/file.php';
				include_once ABSPATH . 'wp-admin/includes/misc.php';
			}
			$upgrader = new Plugin_Upgrader( new YITH_Essential_Kit_Upgrader_Skin() );
			$install  = $upgrader->install( $url );

			return $install;
		}

		/**
		 * Non AJAX Module Install procedure
		 */
		public function non_ajax_install_module_routine( $url ) {
			if ( ! function_exists( 'request_filesystem_credentials' ) ) {
				include_once ABSPATH . 'wp-admin/includes/file.php';
				include_once ABSPATH . 'wp-admin/includes/misc.php';
			}
			$upgrader = new Plugin_Upgrader();
			$install  = $upgrader->install( $url );

			return $install;
		}


		/**
		 * Print Install/Activate/Deactivate button
		 *
		 * @param $slug
		 *
		 * @return string
		 */
		public function print_action_buttons( $slug ) {
			if ( isset( $this->modules[ $slug ] ) ) {
				$module = $this->modules[ $slug ];

				$is_module_installed = $this->is_plugin_installed( $slug );
				$is_module_active    = $this->is_plugin_active( $slug );

				$is_premium_active = $this->is_premium_active( $slug );
				$init              = isset( $module['init'] ) ? '/' . $module['init'] : '/init.php';

				$module_name  = $module['name'];
				$action_links = '';

				/**
				 * ENABLE/DISABLE FREE MODULE
				 */
				if ( ! $is_module_installed ) {

					// WordPress default behaviour.
					$url = wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'install-plugin',
								'plugin' => $slug,
							),
							admin_url( 'update.php' )
						),
						'install-plugin_' . $slug
					);

					$active_class = '';

					$action_links = '<a class="install-now button ' . $active_class . '" data-slug="' . $slug . '" href="' . $url . '" aria-label="' . sprintf( esc_html__( 'install %s now', 'yith-essential-kit-for-woocommerce-1' ), $slug ) . '" data-name="' . $module_name . '" >' . esc_html__( 'Install now', 'yith-essential-kit-for-woocommerce-1' ) . '</a>';
				} else {
					if ( $is_module_active ) {

						// WordPress default behaviour.
						$url = wp_nonce_url(
							add_query_arg(
								array(
									'action' => 'deactivate',
									'plugin' => $slug . $init,
								),
								admin_url( 'plugins.php' )
							),
							'deactivate-plugin_' . $slug . $init
						);

						$action_links = '<a class="deactivate-now button" data-slug="' . $slug . '" href="' . $url . '" aria-label="' . sprintf( esc_html__( 'Deactivate %s now', 'yith-essential-kit-for-woocommerce-1' ), $slug ) . '" data-name="' . $module_name . '" >' . esc_html__( 'Deactivate', 'yith-essential-kit-for-woocommerce-1' ) . '</a>';
					} else {
						if ( $is_premium_active ) {
							$url          = '#';
							$active_class = 'disabled';
							$action_links = '';
						} else {

							// WordPress default behaviour.
							$url = wp_nonce_url(
								add_query_arg(
									array(
										'action' => 'activate',
										'plugin' => $slug . $init,
									),
									admin_url( 'plugins.php' )
								),
								'activate-plugin_' . $slug . $init
							);

							$active_class = '';

							$action_links = '<a class="activate-now button ' . $active_class . '" data-slug="' . $slug . '" href="' . $url . '" aria-label="' . sprintf( esc_html__( 'activate %s now', 'yith-essential-kit-for-woocommerce-1' ), $slug ) . '" data-name="' . $module_name . '" >' . esc_html__( 'Activate', 'yith-essential-kit-for-woocommerce-1' ) . '</a>';
						}
					}
				}

				return $action_links;
			}
		}

		/**
		 * Check if any module is missing and them
		 */
		public function add_missing_modules() {
			$old_modules = get_option( 'yith_jetpack_active_modules1' );
			if ( ! empty( $old_modules ) ) {
				$num_elem = count( $old_modules );
				$i        = 0;
				foreach ( $old_modules as $old_module ) {

					if ( 'yith-woocommerce-colors-labels-variations' == $old_module ) {
						$old_module = 'yith-color-and-label-variations-for-woocommerce';
					}

					$installed = $this->is_plugin_installed( $old_module );
					$i ++;
					if ( ! $installed ) {
						echo '<div class="loading-bar-' . esc_attr( $i ) . '"><div class="loading-inner" style="width: ' . esc_attr( 100 * $i / $num_elem ) . '%">loading...</div></div>';
						esc_html_e( 'Please wait for next page to load...', 'yith-essential-kit-for-woocommerce-1' );
						$url = 'https://downloads.wordpress.org/plugin/' . $old_module . '.zip';
						$this->non_ajax_install_module_routine( $url );
						if ( isset( $this->modules[ $old_module ] ) ) {
							$module = $this->modules[ $old_module ];
							$init   = isset( $module['init'] ) ? $module['slug'] . '/' . $module['init'] : $module['slug'] . '/init.php';
							activate_plugin( $init );
						}
						?>
						<script>
							function pageReload() {
								location.reload();
							}

							setTimeout(pageReload, 2000);
						</script>
						<div style="clear: both"></div>
						<?php
						return;
					} elseif ( $installed && ! $this->is_plugin_active( $old_module ) ) {
						// module is installed.
						echo '<div class="loading-bar-' . esc_attr( $i ) . '"><div class="loading-inner" style="width: ' . esc_attr( 100 * $i / $num_elem ) . '%">loading...</div></div>';
						esc_html_e( 'Please wait for next page to load...', 'yith-essential-kit-for-woocommerce-1' );
						if ( isset( $this->modules[ $old_module ] ) ) {
							$module = $this->modules[ $old_module ];
							$init   = isset( $module['init'] ) ? $module['slug'] . '/' . $module['init'] : $module['slug'] . '/init.php';
							activate_plugin( $init );
						}
						?>
						<script>
							function pageReload() {
								location.reload();
							}

							setTimeout(pageReload, 2000);
						</script>
						<div style="clear: both"></div>
						<?php
						return;
					}
				}
				wp_safe_redirect( admin_url( 'admin.php?page=yith-jetpack-modules1' ) );
			} else {
				wp_safe_redirect( admin_url( 'admin.php?page=yith-jetpack-modules1' ) );

			}
		}

		/**
		 * Backward compatibility with version prior 2.0.0
		 *
		 * @param string $premium_constant Constant to check.
		 *
		 * @return bool
		 */
		public function deactivate_module_by_premium_constant( $premium_constant ) {
			return false;
		}
	}
}
