<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * WCSTM Lite Admin Settings Class
 */
class WCSTM_Lite_Admin {

	const PAGE = 'wcstm-lite';

	const DEFAULT_TAB = 'wordpress';

	/**
	 * List of settings fields
	 */
	public $fields = array(
		'wordpress'   => array(
			'tab_title'   => 'WordPress Search',
			'title'       => 'WordPress Search Settings',
			'description' => 'Settings below will be applied to default WordPress search.',
			'fields'      => array(
				array(
					'name'  => 'in_excerpts',
					'title' => 'Search by Excerpts',
					'type'  => 'checkbox',
				),
				array(
					'name'  => 'in_comments',
					'title' => 'Search by Post Comments',
					'type'  => 'checkbox',
				),
				array(
					'name'  => 'in_tags',
					'title' => 'Search by Post Tags',
					'type'  => 'checkbox',
				),
				array(
					'name'  => 'in_categories',
					'title' => 'Search by Post Categories',
					'type'  => 'checkbox',
				)
			),
		),
		'woocommerce' => array(
			'tab_title'   => 'WooCommerce Search',
			'title'       => 'WooCommerce Search Settings',
			'description' => 'Settings below will be applied to WooCommerce search only.',
			'fields'      => array(
				array(
					'name'  => 'in_short_desc',
					'title' => 'Search by Short Description',
					'type'  => 'checkbox',
				),
				array(
					'name'  => 'in_comments',
					'title' => 'Search by Product Comments',
					'type'  => 'checkbox',
				),
				array(
					'name'  => 'in_sku',
					'title' => 'Search by SKU',
					'type'  => 'checkbox',
				),
				array(
					'name'  => 'in_tags',
					'title' => 'Search by Product Tags',
					'type'  => 'checkbox',
				),
				array(
					'name'  => 'in_categories',
					'title' => 'Search by Product Categories',
					'type'  => 'checkbox',
				)
			),
		),
		'go_premium' => array(
			'tab_title'   => 'Premium Features',
			'title'       => 'Search Manager PRO',
			'description' => 'The Search Manager PRO plugin is an all-in-one solution for managing your WordPress and WooCommerce search. This plugin gives you the power to track what your customers search for and to help them do it better and faster.',
		)
	);

	/**
	 * Hook into the appropriate actions when the class is constructed
	 */
	public function __construct() {

		$this->fields = apply_filters( 'seo_cleaner_admin_fields', $this->fields );

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'settings_init' ) );
		add_filter( 'plugin_action_links_' . WCSTM_LITE_BASENAME, array( $this, 'add_action_links' ) );

		require_once WCSTM_LITE_PLUGIN_DIR . 'admin/class-wcstm-lite-admin-widget.php';

		// Add custom styling.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	}

	/**
	 * Add admin css
	 */
	public function enqueue_scripts( $hook ) {

		if ( $hook != 'settings_page_wcstm-lite' ) {
			return;
		}

		//css
		wp_register_style( 'search-manager-lite-css', WCSTM_LITE_PLUGIN_URL . 'admin/css/search-manager-admin.css', false, '1.2' );
		wp_enqueue_style( 'search-manager-lite-css' );

	}

	/**
	 * Add settings link
	 */
	function add_action_links( $links ) {

		$links[] = '<a href="' . admin_url( 'options-general.php?page=' . self::PAGE ) . '">' . __( 'Settings', 'wcstm-lite' ) . '</a>';
		$links[] = '<a href="https://codecanyon.net/item/search-manager-plugin-for-woocommerce-and-wordpress/15589890?ref=teamdev-ltd" target="_blank">' . __( 'Go Premium', 'wcstm-lite' ) . '</a>';

		return $links;

	}

	/**
	 * Add menu page for plugin settings
	 */
	public function add_admin_menu() {

		add_submenu_page(
			'options-general.php',
			'Search Manager Lite',
			'Search Manager Lite',
			'manage_options',
			self::PAGE,
			array( $this, 'wcstm_lite_options_page' )
		);

	}

	/**
	 * Retrieve current tab
	 */
	public function get_current_tab() {

		if ( isset( $_GET['tab'] ) ) {

			$_tab = (string) $_GET['tab'];

			if ( ! empty( $this->fields[ $_tab ] ) ) {
				return $_tab;
			}

		}

		return self::DEFAULT_TAB;

	}

	/**
	 * Register plugin settings
	 */
	public function settings_init() {

		$_tab = $this->get_current_tab();

		foreach ( $this->fields as $key => $value ) {
			register_setting( 'wcstm_lite_settings_' . $key, 'wcstm_lite_settings_' . $key );
		}

		if ( ! empty( $this->fields[ $_tab ] ) ) {

			add_settings_section(
				'wcstm_lite_settings_' . $_tab,
				__( $this->fields[ $_tab ]['title'], 'wcstm_lite' ),
				array( $this, 'wcstm_lite_settings_section_callback' ),
				'wcstm_lite_settings_' . $_tab
			);

			if( ! empty( $this->fields[ $_tab ]['fields'] ) ) {

				foreach ( $this->fields[ $_tab ]['fields'] as $field ) {

					add_settings_field(
						$field['name'],
						__( $field['title'], 'wcstm_lite' ),
						array( $this, 'render_field' ),
						'wcstm_lite_settings_' . $_tab,
						'wcstm_lite_settings_' . $_tab,
						$field
					);

				}

			}

		}

	}

	/**
	 * Callback for setting tab description
	 */
	public function wcstm_lite_settings_section_callback() {

		$_tab = $this->get_current_tab();

		if ( ! empty( $this->fields[ $_tab ] ) ) {
			_e( $this->fields[ $_tab ]['description'], 'wcstm_lite' );
		}

	}

	/**
	 * URL for tab link
	 */
	public function get_tab_url( $tab ) {

		return add_query_arg( array(
				'page' => self::PAGE,
				'tab'  => $tab
			), admin_url( 'options-general.php' )
		);

	}

	/**
	 * Settings page output
	 */
	public function wcstm_lite_options_page() {

		$_tab = $this->get_current_tab();
		?>
		<div class="search-manager-lite-wrapper">
			<div id="search-manager-lite-primary">
				<form class="search-manager-lite" action="options.php" method="post">

					<h1><?php _e( 'Search Manager Lite', 'wcstm_lite' ); ?></h1>

					<div class="nav-tab-wrapper">
						<?php foreach ( $this->fields as $name => $label ): ?>
							<a href="<?php echo $this->get_tab_url( $name ) ?>"
							   class="nav-tab <?php echo( $this->get_current_tab() == $name ? 'nav-tab-active' : '' ) ?>">
								<?php echo $label['tab_title'] ?>
							</a>
						<?php endforeach; ?>
					</div>

					<?php
					if( $_tab == 'woocommerce' AND !WCSTM_Lite::is_woocommerce() ) {
						$this->woocommerce_notice();
					} else {
						settings_fields( 'wcstm_lite_settings_' . $_tab );
						do_settings_sections( 'wcstm_lite_settings_' . $_tab );

						if(  'go_premium' == $this->get_current_tab() ) {
							$this->get_premium_tab_info();
						} else {
							submit_button();
						}
					}
					?>

				</form>
			</div>

			<div id="search-manager-lite-sidebar">
				<?php load_template( dirname( __FILE__ ) . '/partials/settings-sidebar.php' ) ?>
			</div>
		</div>
		<?php

	}

	/**
	 * Generate field
	 */
	public function render_field( $args ) {

		$_tab          = $this->get_current_tab();
		$checked_value = isset( WCSTM_Lite::$settings[ $_tab ][ $args['name'] ] ) ? WCSTM_Lite::$settings[ $_tab ][ $args['name'] ] : 0;
		?>
		<label>
			<input
				type="checkbox"
				name="wcstm_lite_settings_<?php echo $_tab . '[' . esc_attr( $args['name'] ) . ']' ?>"
				value="1"
				<?php checked( $checked_value, 1 ) ?>
			>
		</label>
		<?php

	}

	/**
	 * Add WooCommerce admin notice
	 */
	public function woocommerce_notice() {
		?>
		<h2><?php _e( 'WooCommerce Search Settings', 'wcstm_lite' ) ?></h2>
		<div class="notice notice-warning notice-woo">
			<p><?php _e( 'You should have WooCommerce plugin enabled to use these features', 'wcstm_lite' ) ?>.</p>
		</div>
		<?php
	}

	public function get_premium_tab_info() {

		load_template( dirname( __FILE__ ) . '/partials/tab-premium.php' );

	}

}