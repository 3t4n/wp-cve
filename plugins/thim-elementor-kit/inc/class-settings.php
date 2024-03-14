<?php
namespace Thim_EL_Kit;

class Settings {
	use SingletonTrait;

	const MENU_SLUG = 'thim_ekit_settings';

	const WIDGET_OPTIONS = 'thim_ekits_widget_settings';

	const MODULE_OPTIONS = 'thim_ekits_module_settings';

	const ADVANCED_OPTIONS = 'thim_ekits_advanced_settings';

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ), 900 );
		add_action( 'thim_ekit/rest_api/register_endpoints', array( $this, 'register_endpoints' ), 20, 1 );
		add_action( 'init', array( $this, 'register_settings' ) );
	}

	public function admin_enqueue_scripts( $hook ) {
		// Only load in settings page.
		if ( strpos( $hook, 'page_' . self::MENU_SLUG ) === false ) {
			return;
		}

		$file_info = include THIM_EKIT_PLUGIN_PATH . 'build/settings.asset.php';

		wp_enqueue_script( 'thim-ekit-settings', THIM_EKIT_PLUGIN_URL . 'build/settings.js', $file_info['dependencies'], $file_info['version'], true );
		wp_enqueue_style( 'thim-ekit-settings', THIM_EKIT_PLUGIN_URL . 'build/settings.css', array( 'wp-components' ), $file_info['version'] );
	}

	public function add_admin_menu() {
		add_submenu_page(
			Dashboard::MENU_SLUG,
			esc_html__( 'Settings', 'thim-elementor-kit' ),
			esc_html__( 'Settings', 'thim-elementor-kit' ),
			'manage_options',
			self::MENU_SLUG,
			function() {
				echo '<div id="thim-ekit-settings-app"></div>';
			}
		);
	}

	public function register_settings() {
		register_setting(
			self::MODULE_OPTIONS,
			self::MODULE_OPTIONS,
			array(
				'type'         => 'object',
				'description'  => esc_html__( 'Module Settings', 'thim-elementor-kit' ),
				'default'      => array(
					'megamenu' => true,
					'header'   => true,
					'footer'   => true,
				),
				'show_in_rest' => array(
					'schema' => array(
						'type'       => 'object',
						'properties' => array(
							'megamenu' => array(
								'type' => 'boolean',
							),
							'header'   => array(
								'type' => 'boolean',
							),
							'footer'   => array(
								'type' => 'boolean',
							),
						),
					),
				),
			)
		);
	}

	public function disable_widgets_settings( $widget ) {
		$disable_option = get_option( self::WIDGET_OPTIONS, array() );

		if ( ! empty( $disable_option ) && is_array( $disable_option ) ) {
			return in_array( $widget, $disable_option );
		}

		return false;
	}

	public function get_enable_modules( string $type ) {
		$enable_option = get_option( self::MODULE_OPTIONS );

		if ( ! empty( $type ) && ( ! $enable_option || ! empty( $enable_option[ $type ] ) ) ) {
			return true;
		}

		return false;
	}

	public function register_endpoints( $namespace ) {
		register_rest_route(
			$namespace,
			'/get-widget-settings',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_widget_settings' ),
				'permission_callback' => function() {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$namespace,
			'/save-widget-settings',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'save_widget_settings' ),
				'permission_callback' => function() {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$namespace,
			'/get-advanced-settings',
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_advanced_settings' ),
				'permission_callback' => function() {
					return current_user_can( 'manage_options' );
				},
			)
		);
	}

	public function get_advanced_settings( $request ) {
		// Get all system colors and custom colors.
		$colors = array();

		try {
			$kit = \Elementor\Plugin::$instance->kits_manager->get_active_kit_for_frontend();

			$system_items = $kit->get_settings_for_display( 'system_colors' );
			$custom_items = $kit->get_settings_for_display( 'custom_colors' );

			$advanced = get_option( self::ADVANCED_OPTIONS, array() );

			if ( empty( $advanced['enableDarkMode'] ) ) {
				$advanced['enableDarkMode'] = false;
			}
			$color_system = array(
				'system' => $system_items,
				'custom' => $custom_items,
			);
 			return rest_ensure_response(
				array(
					'colors'  =>  apply_filters( 'thim_ekits_system_color_dark_mode', $color_system ),
 					'advanced' => $advanced,
				)
			);

		} catch ( \Throwable $th ) {
			return rest_ensure_response(
				array(
					'success' => false,
					'message' => $th->getMessage(),
				)
			);
		}
	}

	public function get_widget_settings( $request ) {
		$widgets = \Thim_EL_Kit\Elementor\Widgets::instance()->widgets();
		$output  = array();

		$titles = array(
			'global'          => esc_html__( 'Global Widgets', 'thim-elementor-kit' ),
			'archive-course'  => esc_html__( 'Archive Course', 'thim-elementor-kit' ),
			'archive-product' => esc_html__( 'Archive Product', 'thim-elementor-kit' ),
			'archive-post'    => esc_html__( 'Archive Post', 'thim-elementor-kit' ),
			'single-course'   => esc_html__( 'Single Course', 'thim-elementor-kit' ),
			'single-post'     => esc_html__( 'Single Post', 'thim-elementor-kit' ),
			'single-product'  => esc_html__( 'Single Product', 'thim-elementor-kit' ),
		);

		$widget_list = array();

		unset( $widgets['loop-item'] );

		if ( ! empty( $widgets ) ) {
			foreach ( $widgets as $base => $widget ) {
				$widget_array = array();

				foreach ( $widget as $widget_value ) {
					$class = \Thim_EL_Kit\Elementor\Widgets::instance()->register_widget_class( $base, $widget_value );

					if ( class_exists( $class ) ) {
						$class = new $class();

						if ( method_exists( $class, 'get_title' ) ) {
							$widget_array['id']      = $base;
							$widget_array['title']   = $titles[ $base ];
							$widget_array['items'][] = array(
								'id'   => $widget_value,
								'name' => $class->get_title(),
							);
							$widget_list[]           = $widget_value;
						}
					}
				}

				$output[] = $widget_array;
			}
		}

		return rest_ensure_response(
			array(
				'widgets'     => $output,
				'settings'    => get_option( self::WIDGET_OPTIONS, array() ),
				'widget_list' => $widget_list,
			)
		);
	}

	public function save_widget_settings( $request ) {
		$widgets = $request->get_param( 'widgets' );
		$modules = $request->get_param( 'modules' );
		$advanced = $request->get_param( 'advanced' );

		if ( $widgets !== null ) {
			$update = update_option( self::WIDGET_OPTIONS, $widgets );
		}

		if ( $modules !== null ) {
			update_option( self::MODULE_OPTIONS, $modules );
		}

		if ( $advanced !== null ) {
			update_option( self::ADVANCED_OPTIONS, $advanced );
		}

		return rest_ensure_response(
			array(
				'success' => true,
				'message' => esc_html__( 'Settings saved.', 'thim-elementor-kit' ),
			)
		);
	}
}

Settings::instance();
