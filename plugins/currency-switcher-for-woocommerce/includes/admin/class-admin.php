<?php
class PMCS_Admin {
	// protected $parent_slug = 'pressmaximum';
	protected $parent_slug            = 'woocommerce';
	protected $menu_slug              = 'pm_currency_switcher';
	protected $capability             = 'manage_options';
	protected $tabs                   = array();
	protected $current_tab            = null;
	protected $show_submit_btn        = true;
	public $wc_currency_fields        = array(
		'woocommerce_currency'           => 'currency_code',
		'woocommerce_currency_pos'       => 'sign_position',
		'woocommerce_price_thousand_sep' => 'thousand_separator',
		'woocommerce_price_decimal_sep'  => 'decimal_separator',
		'woocommerce_price_num_decimals' => 'num_decimals',
	);

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu_pages' ), 90 );

		add_filter( 'woocommerce_screen_ids', array( $this, 'screen_ids' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_custom_wp_admin_style' ) );
		add_filter( 'woocommerce_general_settings', array( $this, 'change_woocommerce_general_settings' ), 35 );
		$this->includes();

	}

	public function get_menu_slug() {
		return $this->menu_slug;
	}

	public function change_woocommerce_general_settings( $list ) {
		$url = admin_url( 'admin.php?page=' . $this->menu_slug . '&tab=currencies' );
		foreach ( $list as $index => $setting ) {
			if ( isset( $this->wc_currency_fields[ $setting['id'] ] ) ) {
				unset( $list[ $index ] );
			} elseif ( 'pricing_options' == $setting['id'] ) {
				$list[ $index ]['desc'] = sprintf( __( 'The following options affect how prices are displayed on the frontend. <strong>Currency Switcher for WooCommerce</strong> plugin is working. Please go to <a href="%1$s">Currency Switcher</a> setting page to set default currency.', 'pmcs' ), $url );
			}
		}

		return $list;
	}

	public function set_show_submit_btn( $state = true ) {
		$this->show_submit_btn = (bool) $state;
	}

	public function is_show_submit_btn() {
		return $this->show_submit_btn;
	}

	public function includes() {
		require_once PMCS_INC . 'metabox/order-item-meta.php';
		require_once PMCS_INC . 'metabox/product-pricing-meta.php';
		require_once PMCS_INC . 'metabox/coupon-meta.php';
		require_once PMCS_INC . 'class-pmcs-report.php';
	}

	public function load_custom_wp_admin_style() {
		wp_enqueue_style( 'pmcs-admin', PMCS_URL . 'assets/css/admin.css', array(), false );
		wp_enqueue_script( 'pmcs-admin', PMCS_URL . 'assets/js/admin.js', array( 'jquery', 'jquery-ui-sortable' ), false, true );
		$currency_code_options = get_woocommerce_currencies();
		$list = array();

		foreach ( $currency_code_options as $code => $name ) {
			$list[ $code ] = array(
				'label' => $name,
				'code' => get_woocommerce_currency_symbol( $code ),
			);
		}

		wp_localize_script( 'jquery', 'PMCS_List_Currency', $list );

	
			$args = array(
				'nonce' => wp_create_nonce( 'pmcs_settings' ),
			);
		

		wp_localize_script(
			'jquery',
			'PMCS_Admin_Args',
			$args
		);
	}

	public function screen_ids( $screen_ids ) {

		$screen_ids[] = 'toplevel_page_' . $this->parent_slug;
		$screen_ids[] = $this->parent_slug . '_page_' . $this->menu_slug;

		return $screen_ids;
	}

	/**
	 * Register a custom menu page.
	 */
	public function add_menu_pages() {
		$this->maybe_add_parent_menu();
		$this->add_submenu();
	}

	public function maybe_add_parent_menu() {
		global $menu, $admin_page_hooks, $_registered_pages, $_parent_pages;
		if ( ! isset( $_parent_pages[ $this->parent_slug ] ) ) {
			$title = __( 'PressMaximum', 'pmcs' );
			add_menu_page(
				$title,
				$title,
				$this->capability,
				$this->parent_slug,
				array( $this, 'sub_page' ),
				'',
				6
			);
		}
	}

	public function add_submenu() {
		$title = __( 'Currency Switcher', 'pmcs' );
		add_submenu_page(
			$this->parent_slug,
			$title,
			$title,
			$this->capability,
			$this->menu_slug,
			array( $this, 'sub_page' )
		);
	}

	public function locate_template( $admin_template ) {
		$file = PMCS_INC . 'admin/templates/' . $admin_template;
		return apply_filters( 'pmcs_admin_locate_template', $file );
	}

	public function load_template( $admin_template, $args = array() ) {
		extract( $args, EXTR_SKIP ); // @codingStandardsIgnoreLine
		$file = $this->locate_template( $admin_template );
		if ( file_exists( $file ) ) {
			include $file;
		}
	}

	public function main_page() {

	}

	public function add_tab( $class_name ) {
		if ( class_exists( $class_name ) ) {
			$c = new $class_name();
			if ( $c instanceof PMCS_Setting_Abstract ) {
				$this->tabs[ $c->id ] = $c;
			}
		}
	}

	protected function init_fields() {
		require_once PMCS_INC . 'admin/fields/currency-list.php';
		require_once PMCS_INC . 'admin/fields/geoip-rulers.php';
		require_once PMCS_INC . 'admin/fields/custom-select.php';
		require_once PMCS_INC . 'admin/fields/html.php';
	}


	protected function init_tabs() {
		require_once PMCS_INC . 'admin/class-pmcs-settings-abstract.php';
		require_once PMCS_INC . 'admin/class-pmcs-settings-general.php';
		require_once PMCS_INC . 'admin/class-pmcs-settings-currencies.php';
		require_once PMCS_INC . 'admin/class-pmcs-settings-geoip.php';
		require_once PMCS_INC . 'admin/class-pmcs-settings-exchange-rate.php';
		require_once PMCS_INC . 'admin/class-pmcs-settings-switcher.php';

		$this->add_tab( 'PMCS_Settings_General' );
		$this->add_tab( 'PMCS_Settings_Currencies' );
		$this->add_tab( 'PMCS_Settings_Geoip' );
		$this->add_tab( 'PMCS_Settings_Exchange_Rate' );
		$this->add_tab( 'PMCS_Settings_switcher' );

	

	}

	/**
	 * Get registered setting tabs
	 *
	 * @return array
	 */
	public function get_tabs() {
		return $this->tabs;
	}

	public function get_current_tab( $list_tabs = array() ) {
		if ( $this->current_tab ) {
			return $this->current_tab;
		}
		$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'general';
		if ( ! empty( $list_tabs ) ) {
			if ( ! isset( $list_tabs[ $current_tab ] ) ) {
				reset( $list_tabs );
				$current_tab = key( $array );
			}
		}
		$this->current_tab = $current_tab;
		return $current_tab;
	}

	public function sub_page() {
		$this->init_tabs();
		$this->init_fields();
		$registered_tabs = $this->get_tabs();
		$this->get_current_tab();
		if ( isset( $_POST['save'] ) ) {
			if ( isset( $registered_tabs[ $this->current_tab ] ) ) {
				$registered_tabs[ $this->current_tab ]->save();
			}
		}

		$data = array(
			'registered_tabs' => $registered_tabs,
			'current_tab' => $this->current_tab,
		);

		$this->load_template( 'html-settings.php', $data );
	}

}
