<?php
/**
 * The main class of the plugin Gift upon purchase for WooCommerce
 *
 * @package	                Gift upon purchase for WooCommerce
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 1.3.7 (28-02-2024)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 * 
 * @param        
 *
 * @depends	                classes:    GUPFW_Data_Arr
 *                                      GUPFW_Settings_Page
 *                                      GUPFW_Debug_Page
 *                                      GUPFW_Error_Log
 *                          traits:     
 *                          methods:    
 *                          functions:  common_option_get
 *                                      common_option_upd
 *                          constants:  GUPFW_PLUGIN_VERSION
 *                                      GUPFW_PLUGIN_BASENAME
 *                                      GUPFW_PLUGIN_DIR_URL
 *                          options:    
 */
defined( 'ABSPATH' ) || exit;

final class GiftUponPurchaseForWooCommerce {
	const ALLOWED_HTML_ARR = [ 
		'a' => [ 
			'href' => true,
			'title' => true,
			'target' => true,
			'class' => true,
			'style' => true
		],
		'br' => [ 'class' => true ],
		'i' => [ 'class' => true ],
		'small' => [ 'class' => true ],
		'strong' => [ 'class' => true, 'style' => true ],
		'p' => [ 'class' => true, 'style' => true ]
	];

	private $plugin_version = GUPFW_PLUGIN_VERSION; // 1.0.0

	protected static $instance;
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Срабатывает при активации плагина (вызывается единожды)
	 * 
	 * @return void
	 */
	public static function on_activation() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$name_dir = GUPFW_SITE_UPLOADS_DIR_PATH . '/gift-upon-purchase-for-woocommerce';
		if ( ! is_dir( $name_dir ) ) {
			if ( ! mkdir( $name_dir ) ) {
				error_log( 'ERROR: Ошибка создания папки ' . $name_dir . '; Файл: gift-upon-purchase-for-woocommerce.php; Строка: ' . __LINE__, 0 );
			}
		}

		if ( is_multisite() ) {
			add_blog_option( get_current_blog_id(), 'gupfw_version', '1.3.4' );
			add_blog_option( get_current_blog_id(), 'gupfw_keeplogs', '0' );
			add_blog_option( get_current_blog_id(), 'gupfw_disable_notices', '0' );
			add_blog_option( get_current_blog_id(), 'gupfw_errors', '' );
			add_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_category_status', 'show' );
			add_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_category_content', 'gift for purchase' );
			add_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_category_color', '#000000' );
			add_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_category_fsize', '12' );
			add_blog_option( get_current_blog_id(), 'gupfw_hook_name_for_gift_in_category_info', 'woocommerce_shop_loop_item_title' );
			add_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_cart_status', 'show' );
			add_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_cart_content', 'gift for purchase' );
			add_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_cart_color', '#000000' );
			add_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_cart_fsize', '12' );
			add_blog_option( get_current_blog_id(), 'gupfw_displaying_accept_remove_button', 'show' );
			add_blog_option( get_current_blog_id(), 'gupfw_tgfp_remove_gift_in_cart', __( 'Remove gifts', 'gift-upon-purchase-for-woocommerce' ) );
			add_blog_option( get_current_blog_id(), 'gupfw_tgfp_accept_gift_in_cart', __( 'Accept gifts', 'gift-upon-purchase-for-woocommerce' ) );
			add_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_product_status', 'show' );
			add_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_product_content', 'gift for purchase' );
			add_blog_option( get_current_blog_id(), 'gupfw_gift_for_any_product_in_cart_content', 'gift' );
			add_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_product_color', '#000000' );
			add_blog_option( get_current_blog_id(), 'gupfw_tgfp_in_product_fsize', '12' );
			add_blog_option( get_current_blog_id(), 'gupfw_hook_name_for_gift_info', 'woocommerce_product_meta_start' );
			add_blog_option( get_current_blog_id(), 'gupfw_gift_for_any_product_arr', [] );
			add_blog_option( get_current_blog_id(), 'gupfw_hide_or_remove', 'full_cost' );
			add_blog_option( get_current_blog_id(), 'gupfw_cart_total_price', '0' );
			add_blog_option( get_current_blog_id(), 'gupfw_rules_for_cart_price', 'total' );
			add_blog_option( get_current_blog_id(), 'gupfw_whose_price_exceeds', '0' );
			add_blog_option( get_current_blog_id(), 'gupfw_days_of_the_week', [] );
			add_blog_option( get_current_blog_id(), 'gupfw_days_of_the_hours', [] );
		} else {
			add_option( 'gupfw_version', '1.3.4', '', 'no' );
			add_option( 'gupfw_keeplogs', '0', '' );
			add_option( 'gupfw_disable_notices', '0', '', 'no' );
			add_option( 'gupfw_errors', '', '', 'no' );
			add_option( 'gupfw_tgfp_in_category_status', 'show' );
			add_option( 'gupfw_tgfp_in_category_content', 'gift for purchase' );
			add_option( 'gupfw_tgfp_in_category_color', '#000000' );
			add_option( 'gupfw_tgfp_in_category_fsize', '12' );
			add_option( 'gupfw_hook_name_for_gift_in_category_info', 'woocommerce_shop_loop_item_title' );
			add_option( 'gupfw_tgfp_in_cart_status', 'show' );
			add_option( 'gupfw_tgfp_in_cart_content', 'gift for purchase' );
			add_option( 'gupfw_tgfp_in_cart_color', '#000000' );
			add_option( 'gupfw_tgfp_in_cart_fsize', '12' );
			add_option( 'gupfw_displaying_accept_remove_button', 'show' );
			add_option( 'gupfw_tgfp_remove_gift_in_cart', __( 'Remove gifts', 'gift-upon-purchase-for-woocommerce' ) );
			add_option( 'gupfw_tgfp_accept_gift_in_cart', __( 'Accept gifts', 'gift-upon-purchase-for-woocommerce' ) );
			add_option( 'gupfw_tgfp_in_product_status', 'show' );
			add_option( 'gupfw_tgfp_in_product_content', 'gift for purchase' );
			add_option( 'gupfw_gift_for_any_product_in_cart_content', 'gift' );
			add_option( 'gupfw_tgfp_in_product_color', '#000000' );
			add_option( 'gupfw_tgfp_in_product_fsize', '12' );
			add_option( 'gupfw_hook_name_for_gift_info', 'woocommerce_product_meta_start' );
			add_option( 'gupfw_gift_for_any_product_arr', [] );
			add_option( 'gupfw_hide_or_remove', 'full_cost' );
			add_option( 'gupfw_cart_total_price', '0' );
			add_option( 'gupfw_rules_for_cart_price', 'total' );
			add_option( 'gupfw_whose_price_exceeds', '0' );
			add_option( 'gupfw_days_of_the_week', [] );
			add_option( 'gupfw_days_of_the_hours', [] );
		}
	}

	/**
	 * Срабатывает при отключении плагина (вызывается единожды)
	 * 
	 * @return void
	 */
	public static function on_deactivation() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

	}

	public function __construct() {
		$this->check_options_upd(); // проверим, нужны ли обновления опций плагина 
		$this->init_classes();
		$this->init_hooks(); // подключим хуки
	}

	/**
	 * Checking whether the plugin options need to be updated
	 * 
	 * @return void
	 */
	public function check_options_upd() {
		$plugin_version = $this->get_plugin_version();
		if ( $plugin_version == false ) { // вероятно, у нас первичная установка плагина
			if ( is_multisite() ) {
				update_blog_option( get_current_blog_id(), 'gupfw_version', GUPFW_PLUGIN_VERSION );
			} else {
				update_option( 'gupfw_version', GUPFW_PLUGIN_VERSION );
			}
		} else if ( $plugin_version !== $this->plugin_version ) {
			add_action( 'init', array( $this, 'set_new_options' ), 10 ); // автообновим настройки, если нужно
		}
	}

	public function set_new_options() {
		// удаление старых опций
		// if (gupfw_optionGET('gupfw_debug') !== false) {gupfw_optionDEL('gupfw_debug');}

		// добавление новых опций
		if ( gupfw_optionGET( 'gupfw_tgfp_in_category_status' ) === false ) {
			gupfw_optionUPD( 'gupfw_tgfp_in_category_status', 'show', '' );
		}
		if ( gupfw_optionGET( 'gupfw_tgfp_in_category_content' ) === false ) {
			gupfw_optionUPD( 'gupfw_tgfp_in_category_content', 'gift for purchase', '' );
		}
		if ( gupfw_optionGET( 'gupfw_tgfp_in_category_color' ) === false ) {
			gupfw_optionUPD( 'gupfw_tgfp_in_category_color', '#000000', '' );
		}
		if ( gupfw_optionGET( 'gupfw_tgfp_in_category_fsize' ) === false ) {
			gupfw_optionUPD( 'gupfw_tgfp_in_category_fsize', '12', '' );
		}
		if ( gupfw_optionGET( 'gupfw_hook_name_for_gift_in_category_info' ) === false ) {
			gupfw_optionUPD( 'gupfw_hook_name_for_gift_in_category_info', 'woocommerce_shop_loop_item_title', '' );
		}
		if ( gupfw_optionGET( 'gupfw_tgfp_in_cart_status' ) === false ) {
			gupfw_optionUPD( 'gupfw_tgfp_in_cart_status', 'show', '' );
		}
		if ( gupfw_optionGET( 'gupfw_tgfp_in_cart_content' ) === false ) {
			gupfw_optionUPD( 'gupfw_tgfp_in_cart_content', 'gift for purchase', '' );
		}
		if ( gupfw_optionGET( 'gupfw_tgfp_in_cart_color' ) === false ) {
			gupfw_optionUPD( 'gupfw_tgfp_in_cart_color', '#000000', '' );
		}
		if ( gupfw_optionGET( 'gupfw_tgfp_in_cart_fsize' ) === false ) {
			gupfw_optionUPD( 'gupfw_tgfp_in_cart_fsize', '12', '' );
		}
		if ( gupfw_optionGET( 'gupfw_tgfp_in_product_status' ) === false ) {
			gupfw_optionUPD( 'gupfw_tgfp_in_product_status', 'show', '' );
		}
		if ( gupfw_optionGET( 'gupfw_tgfp_in_product_content' ) === false ) {
			gupfw_optionUPD( 'gupfw_tgfp_in_product_content', 'gift for purchase', '' );
		}
		if ( gupfw_optionGET( 'gupfw_gift_for_any_product_in_cart_content' ) === false ) {
			gupfw_optionUPD( 'gupfw_gift_for_any_product_in_cart_content', 'gift', '' );
		}
		if ( gupfw_optionGET( 'gupfw_tgfp_in_product_color' ) === false ) {
			gupfw_optionUPD( 'gupfw_tgfp_in_product_color', '#000000', '' );
		}
		if ( gupfw_optionGET( 'gupfw_tgfp_in_product_fsize' ) === false ) {
			gupfw_optionUPD( 'gupfw_tgfp_in_product_fsize', '12', '' );
		}
		if ( gupfw_optionGET( 'gupfw_hook_name_for_gift_info' ) === false ) {
			gupfw_optionUPD( 'gupfw_hook_name_for_gift_info', 'woocommerce_product_meta_start', '' );
		}
		if ( gupfw_optionGET( 'gupfw_gift_for_any_product' ) === false ) {
			gupfw_optionUPD( 'gupfw_gift_for_any_product', [], '' );
		}
		if ( gupfw_optionGET( 'gupfw_cart_total_price' ) === false ) {
			gupfw_optionUPD( 'gupfw_cart_total_price', '0', '' );
		}
		if ( gupfw_optionGET( 'gupfw_whose_price_exceeds' ) === false ) {
			gupfw_optionUPD( 'gupfw_whose_price_exceeds', '0', '' );
		}
		if ( gupfw_optionGET( 'gupfw_days_of_the_week' ) === false ) {
			gupfw_optionUPD( 'gupfw_days_of_the_week', [], '' );
		}
		if ( gupfw_optionGET( 'gupfw_days_of_the_hours' ) === false ) {
			gupfw_optionUPD( 'gupfw_days_of_the_hours', [], '' );
		}
		if ( gupfw_optionGET( 'gupfw_tgfp_remove_gift_in_cart' ) === false ) {
			gupfw_optionUPD( 'gupfw_tgfp_remove_gift_in_cart', __( 'Remove gifts', 'gift-upon-purchase-for-woocommerce' ), '' );
		}
		if ( gupfw_optionGET( 'gupfw_tgfp_accept_gift_in_cart' ) === false ) {
			gupfw_optionUPD( 'gupfw_tgfp_accept_gift_in_cart', __( 'Accept gifts', 'gift-upon-purchase-for-woocommerce' ), '' );
		}
		if ( gupfw_optionGET( 'gupfw_displaying_accept_remove_button' ) === false ) {
			gupfw_optionUPD( 'gupfw_displaying_accept_remove_button', 'show', '' );
		}
		if ( gupfw_optionGET( 'gupfw_hide_or_remove' ) === false ) {
			gupfw_optionUPD( 'gupfw_hide_or_remove', 'full_cost', '' );
		}
		do_action( 'gupfw_after_set_new_options' );

		if ( is_multisite() ) {
			update_blog_option( get_current_blog_id(), 'gupfw_version', GUPFW_PLUGIN_VERSION );
		} else {
			update_option( 'gupfw_version', GUPFW_PLUGIN_VERSION );
		}
		return;
	}

	/**
	 * Initialization classes
	 * 
	 * @return void
	 */
	public function init_classes() {
		return;
	}

	/**
	 * Get plugin version
	 * 
	 * @return string
	 */
	public function get_plugin_version() {
		if ( is_multisite() ) {
			$v = get_blog_option( get_current_blog_id(), 'gupfw_version' );
		} else {
			$v = get_option( 'gupfw_version' );
		}
		return (string) $v;
	}

	/**
	 * Initialization hooks
	 * 
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'admin_init', array( $this, 'listen_submits_func' ), 10 ); // ещё можно слушать чуть раньше на wp_loaded

		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_notices', array( $this, 'print_admin_notices_func' ) );
		add_filter( 'plugin_action_links', array( $this, 'add_plugin_action_links' ), 10, 2 );

		// https://wpruse.ru/woocommerce/custom-fields-in-products/
		// https://wpruse.ru/woocommerce/custom-fields-in-variations/
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'gupfw_added_wc_tabs' ), 10, 1 );
		add_action( 'admin_footer', array( $this, 'gupfw_art_added_tabs_icon' ), 10, 1 );
		add_action( 'woocommerce_product_data_panels', array( $this, 'gupfw_art_added_tabs_panel' ), 10, 1 );
		add_action( 'woocommerce_process_product_meta', array( $this, 'gupfw_art_woo_custom_fields_save' ), 10, 1 );

		// https://github.com/woocommerce/selectWoo
		// https://rudrastyh.com/wordpress/select2-for-metaboxes-with-ajax.html	
		add_action( 'wp_ajax_gupfwselect2', array( $this, 'gupfw_get_posts_ajax_callback' ), 10, 1 ); // wp_ajax_{action}	

		// https://www.kobzarev.com/wordpress/woo-visual-hook/
		// https://businessbloomer.com/woocommerce-visual-hook-guide-single-product-page/ 

		if ( is_multisite() ) {
			$r = get_blog_option( get_current_blog_id(), 'gupfw_hook_name_for_gift_info' );
		} else {
			$r = get_option( 'gupfw_hook_name_for_gift_info' );
		}
		switch ( $r ) {
			case 'woocommerce_before_add_to_cart_button':
				add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'gupfw_art_get_text_field_after_add_card' ), 10, 1 );
				break;
			case 'woocommerce_before_quantity_input_field':
				add_action( 'woocommerce_before_quantity_input_field', array( $this, 'gupfw_art_get_text_field_after_add_card' ), 10, 1 );
				break;
			case 'woocommerce_after_quantity_input_field':
				add_action( 'woocommerce_after_quantity_input_field', array( $this, 'gupfw_art_get_text_field_after_add_card' ), 10, 1 );
				break;
			case 'woocommerce_product_meta_end':
				add_action( 'woocommerce_product_meta_end', array( $this, 'gupfw_art_get_text_field_after_add_card' ), 10, 1 );
				break;
			case 'woocommerce_product_meta_start':
				add_action( 'woocommerce_product_meta_start', array( $this, 'gupfw_art_get_text_field_after_add_card' ), 10, 1 );
				break;
			default:
				add_action( 'woocommerce_product_meta_start', array( $this, 'gupfw_art_get_text_field_after_add_card' ), 10, 1 );
		}

		if ( is_multisite() ) {
			$r = get_blog_option( get_current_blog_id(), 'gupfw_hook_name_for_gift_in_category_info' );
		} else {
			$r = get_option( 'gupfw_hook_name_for_gift_in_category_info' );
		}
		switch ( $r ) {
			case 'woocommerce_shop_loop_item_title':
				add_action( 'woocommerce_shop_loop_item_title', array( $this, 'gift_in_category_info' ) );
				break;
			case 'woocommerce_after_shop_loop_item_title':
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'gift_in_category_info' ) );
				break;
			case 'woocommerce_after_shop_loop_item':
				add_action( 'woocommerce_after_shop_loop_item', array( $this, 'gift_in_category_info' ) );
				break;
			default:
				add_action( 'woocommerce_shop_loop_item_title', array( $this, 'gift_in_category_info' ) );
		}

		// https://www.pandoge.com/haki/skidka-v-procentah-na-vsyu-korzinu-bez-kupona-v-woocommerce
		add_action( 'template_redirect', array( $this, 'add_product_to_cart' ), 10, 1 );
		// https://rudrastyh.com/woocommerce/change-product-prices-in-cart.html
		add_action( 'woocommerce_before_calculate_totals', array( $this, 'change_recalc_price' ), 10, 1 );
		add_action( 'woocommerce_before_mini_cart_contents', array( $this, 'add_gift_to_minicart' ), 11 );

		// хук изменяет URL ссылки удаления товара из корзины
		add_filter( 'woocommerce_cart_item_remove_link', array( $this, 'change_remove_link' ), 99, 4 );
		// хук срабатывает после удаления товара из корзины
		add_action( 'woocommerce_cart_item_removed', array( $this, 'after_remove_product' ), 10, 2 );

		add_filter( 'woocommerce_cart_item_name', array( $this, 'gupfw_add_excerpt_in_cart_item_name' ), 10, 4 );

		// отказ от подарков
		add_action( 'set_current_user', array( $this, 'set_new_cookie' ), 10, 1 );
		add_action( 'woocommerce_cart_coupon', array( $this, 'gupfw_before_add_to_cart_btn' ), 10, 1 );

		/* Регаем стили только для страницы настроек плагина */
		add_action( 'admin_init', function () {
			wp_register_style( 'gupfw-admin-css', GUPFW_PLUGIN_DIR_URL . 'css/gupfw.css' );
		}, 9999 );

		// add_filter('woocommerce_sale_flash', array($this, 'gupfw_my_custom_sale_flash'), 10, 3);
	}

	public function listen_submits_func() {
		do_action( 'gupfw_listen_submits' );

		if ( isset( $_REQUEST['gupfw_submit_action'] ) ) {
			$message = __( 'Updated', 'gift-upon-purchase-for-woocommerce' );
			$class = 'notice-success';

			add_action( 'admin_notices', function () use ($message, $class) {
				$this->admin_notices_func( $message, $class );
			}, 10, 2 );
		}
	}

	/**
	 * Add items to admin menu
	 * 
	 * @return void
	 */
	public function add_admin_menu() {
		$page_suffix = add_menu_page(
			null,
			__( 'Settings Gift', 'gift-upon-purchase-for-woocommerce' ),
			'unfiltered_html',
			'gupfw-settings',
			[ $this, 'get_settings_page_func' ],
			'dashicons-buddicons-groups',
			51
		);
		// создаём хук, чтобы стили выводились только на странице настроек
		add_action( 'admin_print_styles-' . $page_suffix, [ $this, 'enqueue_style_admin_css_func' ] );

		$page_suffix = add_submenu_page(
			'gupfw-settings',
			__( 'Debug', 'gift-upon-purchase-for-woocommerce' ),
			__( 'Debug page',
				'gift-upon-purchase-for-woocommerce' ),
			'unfiltered_html',
			'gupfwdebug',
			[ $this, 'get_debug_page_func' ]
		);
		add_action( 'admin_print_styles-' . $page_suffix, [ $this, 'enqueue_style_admin_css_func' ] );
	}

	/**
	 * Вывод страницы настроек плагина
	 * 
	 * @return void
	 */
	public function get_settings_page_func() {
		new GUPFW_Settings_Page();
		return;
	}

	/**
	 * Вывод страницы отладки плагина
	 * 
	 * @return void
	 */
	public function get_debug_page_func() {
		new GUPFW_Debug_Page();
		return;
	}

	public function enqueue_style_admin_css_func() {
		/* Ставим css-файл в очередь на вывод */
		wp_enqueue_style( 'gupfw-admin-css' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		add_action( 'admin_footer', [ $this, 'gupfw_admin_footer_script' ], 99 );

		wp_enqueue_style( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' );
		wp_enqueue_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', [ 'jquery' ] );

		// please create also an empty JS file in your theme directory and include it too
		wp_enqueue_script( 'wplspms_orders', GUPFW_PLUGIN_DIR_URL . 'js/select2.js', [ 'jquery', 'select2' ] );
	}

	// Подключаем свой скрпит в подвал 
	public function gupfw_admin_footer_script() {
		// https://wp-kama.ru/id_4621/vyibora-tsveta-iris-color-picker-v-wordpress.html 
		// http://automattic.github.io/Iris/
		?>
		<script type="text/javascript">jQuery(document).ready(function ($) {
				var myOptions = {
					// устанавливает цвет по умолчанию, также цвет по умолчанию из атрибута value у input
					defaultColor: false,
					// функция обратного вызова, срабатывающая каждый раз при выборе цвета (когда водите мышкой по палитре)
					change: function (event, ui) { },
					// функция обратного вызова, срабатывающая при очистке (сбросе) цвета
					clear: function () { },
					// спрятать ли выбор цвета при загрузке палитра будет появляться при клике
					hide: true,
					// показывать ли группу стандартных цветов внизу палитры 
					// можно добавить свои цвета указав их в массиве: ['#125', '#459', '#78b', '#ab0', '#de3', '#f0f']
					palettes: true
				}
				$('#gupfw_tgfp_in_category_color').wpColorPicker(myOptions);
				$('#gupfw_tgfp_in_cart_color').wpColorPicker(myOptions);
				$('#gupfw_tgfp_in_product_color').wpColorPicker(myOptions);
			});</script>
		<?php
	}

	// Вывод различных notices
	public function print_admin_notices_func() {

	}

	/**
	 * Summary of add_plugin_action_links
	 * 
	 * @param array $actions
	 * @param string $plugin_file
	 * 
	 * @return array
	 */
	public function add_plugin_action_links( $actions, $plugin_file ) {
		if ( false === strpos( $plugin_file, GUPFW_PLUGIN_BASENAME ) ) {
			// проверка, что у нас текущий плагин
			return $actions;
		}
		$settings_link = sprintf( '<a href="/wp-admin/admin.php?page=%s">%s</a>',
			'gupfw-settings',
			__( 'Settings', 'gift-upon-purchase-for-woocommerce' )
		);
		array_unshift( $actions, $settings_link );
		return $actions;
	}

	public function gupfw_added_wc_tabs( $tabs ) {
		$tabs['gupfw_special_panel'] = [ 
			'label' => __( 'Add Gift', 'gift-upon-purchase-for-woocommerce' ), // название вкладки
			'target' => 'gupfw_added_wc_tabs', // идентификатор вкладки
			'class' => [ 'hide_if_grouped' ], // классы управления видимостью вкладки в зависимости от типа товара
			'priority' => 71 // приоритет вывода
		];
		return $tabs;
	}

	public function gupfw_art_added_tabs_icon() { ?>
		<style>
			#woocommerce-coupon-data ul.wc-tabs li.gupfw_special_panel_options a::before,
			#woocommerce-product-data ul.wc-tabs li.gupfw_special_panel_options a::before,
			.woocommerce ul.wc-tabs li.gupfw_special_panel_options a::before {
				content: "\f456";
			}
		</style>
		<?php
	}

	public function gupfw_art_added_tabs_panel() {
		global $post;
		?>
		<div id="gupfw_added_wc_tabs" class="panel woocommerce_options_panel">
			<?php do_action( 'gupfw_before_options_group', $post ); ?>
			<div class="options_group">
				<h2><strong>
						<?php _e( 'Here you can customize the gifts that the user will receive', 'gift-upon-purchase-for-woocommerce' ); ?>
					</strong></h2>
				<?php do_action( 'gupfw_prepend_options_group', $post ); ?>
				<?php // Чекбокс
						woocommerce_wp_checkbox( array(
							'id' => '_gupfw_stopgift',
							//	'wrapper_class' => 'show_if_simple',
							'label' => __( 'Disable gifts', 'gift-upon-purchase-for-woocommerce' ),
							'description' => __( 'Disable gifts for this product', 'gift-upon-purchase-for-woocommerce' ),
						) );
						woocommerce_wp_checkbox( array(
							'id' => '_two_price_one',
							'wrapper_class' => 'show_if_simple', // не показываем если товар вариативный
							'label' => __( 'Two for the price of one', 'gift-upon-purchase-for-woocommerce' ),
							'description' => '<a href="https://icopydoc.ru/kak-dobavit-dva-tovara-po-tsene-odnogo-v-woocommerce/?utm_source=gift-upon-purchase-for-woocommerce&utm_medium=organic&utm_campaign=in-plugin-gift-upon-purchase-for-woocommerce&utm_content=edit_product&utm_term=two-price-one-instruction" target="_blank">' . __( 'How it works', 'gift-upon-purchase-for-woocommerce' ) . '</a>',
						) );
						gupfw_woocommerce_wp_select_multiple( array(
							'id' => '_days_of_the_week',
							//	'wrapper_class' => 'show_if_simple', 
							'label' => __( 'Gift only on certain days', 'gift-upon-purchase-for-woocommerce' ),
							'options' => array(
								'Monday' => __( 'Monday', 'gift-upon-purchase-for-woocommerce' ),
								'Tuesday' => __( 'Tuesday', 'gift-upon-purchase-for-woocommerce' ),
								'Wednesday' => __( 'Wednesday', 'gift-upon-purchase-for-woocommerce' ),
								'Thursday' => __( 'Thursday', 'gift-upon-purchase-for-woocommerce' ),
								'Friday' => __( 'Friday', 'gift-upon-purchase-for-woocommerce' ),
								'Saturday' => __( 'Saturday', 'gift-upon-purchase-for-woocommerce' ),
								'Sunday' => __( 'Sunday', 'gift-upon-purchase-for-woocommerce' ),
							)
						) );
						$res_arr = [];
						for ( $i = 1; $i < 25; $i++ ) {
							$res_arr[ $i ] = $i;
						}
						gupfw_woocommerce_wp_select_multiple( array(
							'id' => '_days_of_the_hours',
							//	'wrapper_class' => 'show_if_simple',
							'label' => __( 'Gift only at certain hours', 'gift-upon-purchase-for-woocommerce' ),
							'options' => $res_arr
						) );
						?>
				<p class="form-field product_field_type">
					<label for="product_field_type">
						<?php _e( 'Product selection', 'gift-upon-purchase-for-woocommerce' ); ?>
					</label> <select id="product_field_type" name="product_field_type[]" class="wc-product-search"
						multiple="multiple" style="width: 50%;"
						data-placeholder="<?php esc_attr_e( 'Search for a product…', 'gift-upon-purchase-for-woocommerce' ); ?>"
						data-action="woocommerce_json_search_products_and_variations"
						data-exclude="<?php echo intval( $post->ID ); ?>">
						<?php /* 
																												  <select id="product_field_type" name="product_field_type[]" class="wc-product-search" multiple="multiple" style="width: 50%;" data-placeholder="<?php esc_attr_e('Search for a product…', 'gift-upon-purchase-for-woocommerce'); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval($post->ID); ?>"> 
																												  data-exclude="<?php echo intval($post->ID); ?>" - конструкция исключает из поиска товары с определённым id (подарок за самого себя)
																												  */
						$gupfw_product_gift_ids = get_post_meta( $post->ID, '_gupfw_product_gift_ids', true );
						$product_ids = ! empty( $gupfw_product_gift_ids ) && isset( $gupfw_product_gift_ids ) ? array_map( 'absint', $gupfw_product_gift_ids ) : [];
						if ( $product_ids ) {
							foreach ( $product_ids as $product_id ) {
								$product = wc_get_product( $product_id );
								$product_name = $product->get_formatted_name();
								echo '<option value="' . esc_attr( $product_id ) . '" ' . selected( true, true, false ) . '>' . esc_html( $product->get_formatted_name() ) . '</option>';
							}
						}
						?>
					</select><span class="woocommerce-help-tip"
						data-tip="<?php _e( 'Select the products that the user will receive as a gift', 'gift-upon-purchase-for-woocommerce' ); ?>"></span>
				</p>
				<?php do_action( 'gupfw_append_options_group', $post ); ?>
			</div>
			<?php do_action( 'gupfw_after_options_group', $post ); ?>
		</div>
		<?php
	}

	public function gupfw_art_woo_custom_fields_save( $post_id ) {
		// Сохранение произвольного поля по выбору товаров с поиском
		if ( isset( $_POST['product_field_type'] ) && ! empty( $_POST['product_field_type'] ) ) {
			// Проверяем данные, если они существуют и не пустые, то записываем данные в поле
			update_post_meta( $post_id, '_gupfw_product_gift_ids', array_map( 'absint', (array) $_POST['product_field_type'] ) );
		} else {
			// Иначе удаляем созданное поле из бд
			delete_post_meta( $post_id, '_gupfw_product_gift_ids' );
		}
		if ( isset( $_POST['_gupfw_stopgift'] ) && ! empty( $_POST['_gupfw_stopgift'] ) ) {
			// Проверяем данные, если они существуют и не пустые, то записываем данные в поле
			update_post_meta( $post_id, '_gupfw_stopgift', sanitize_text_field( $_POST['_gupfw_stopgift'] ) );
		} else {
			// Иначе удаляем созданное поле из бд
			update_post_meta( $post_id, '_gupfw_stopgift', '' );
		}
		if ( isset( $_POST['_days_of_the_week'] ) && ! empty( $_POST['_days_of_the_week'] ) ) {
			update_post_meta( $post_id, '_days_of_the_week', $_POST['_days_of_the_week'] );
		} else {
			update_post_meta( $post_id, '_days_of_the_week', [] );
		}
		if ( isset( $_POST['_days_of_the_hours'] ) && ! empty( $_POST['_days_of_the_hours'] ) ) {
			update_post_meta( $post_id, '_days_of_the_hours', $_POST['_days_of_the_hours'] );
		} else {
			update_post_meta( $post_id, '_days_of_the_hours', [] );
		}
		if ( isset( $_POST['_two_price_one'] ) && ! empty( $_POST['_two_price_one'] ) ) {
			// Проверяем данные, если они существуют и не пустые, то записываем данные в поле
			update_post_meta( $post_id, '_two_price_one', sanitize_text_field( $_POST['_two_price_one'] ) );
		} else {
			// Иначе удаляем созданное поле из бд
			update_post_meta( $post_id, '_two_price_one', '' );
		}
	}

	public function gupfw_get_posts_ajax_callback() {
		// we will pass post IDs and titles to this array
		$return = [];

		// you can use WP_Query, query_posts() or get_posts() here - it doesn't matter
		$search_results = new WP_Query( [ 
			's' => $_GET['q'], // the search query
			'post_status' => 'publish', // if you don't want drafts to be returned
			'post_type' => [ 'product', 'product_variation' ],
			'ignore_sticky_posts' => 1,
			'posts_per_page' => 50 // how much to show at once
		] );
		if ( $search_results->have_posts() ) :
			while ( $search_results->have_posts() ) :
				$search_results->the_post();
				// shorten the title a little
				$title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
				$return[] = array( $search_results->post->ID, $title . ' (' . $search_results->post->post_name . ')' ); // array( Post ID, Post Title )
			endwhile;
		endif;
		echo json_encode( $return );
		die;
	}

	// текст перед метаданными в карточке товара
	public function gupfw_art_get_text_field_after_add_card() {
		$gupfw_tgfp_in_product_status = gupfw_optionGET( 'gupfw_tgfp_in_product_status' );
		if ( $gupfw_tgfp_in_product_status === 'hide' ) {
			return;
		}
		$gupfw_tgfp_in_product_content = stripslashes( htmlspecialchars( gupfw_optionGET( 'gupfw_tgfp_in_product_content' ) ) );
		$gupfw_tgfp_in_product_color = gupfw_optionGET( 'gupfw_tgfp_in_product_color' );
		$gupfw_tgfp_in_product_fsize = gupfw_optionGET( 'gupfw_tgfp_in_product_fsize' );

		global $post;
		$gupfw_stopgift = get_post_meta( $post->ID, '_gupfw_stopgift', true );
		if ( $gupfw_stopgift === 'yes' ) {
			return;
		}

		if ( get_post_meta( $post->ID, '_days_of_the_week', true ) !== '' ) {
			$curTime = current_time( 'l' );
			$days_of_the_week = get_post_meta( $post->ID, '_days_of_the_week', true );
			if ( is_array( $days_of_the_week ) && ! empty( $days_of_the_week ) ) {
				if ( ! in_array( $curTime, $days_of_the_week ) ) {
					return;
				}
			}
		}
		if ( get_post_meta( $post->ID, '_days_of_the_hours', true ) !== '' ) {
			$curTime = current_time( 'H' );
			$days_of_the_hours = get_post_meta( $post->ID, '_days_of_the_hours', true );
			if ( is_array( $days_of_the_hours ) && ! empty( $days_of_the_hours ) ) {
				if ( ! in_array( $curTime, $days_of_the_hours ) ) {
					return;
				}
			}
		}

		$gupfw_product_gift_ids_arr = get_post_meta( $post->ID, '_gupfw_product_gift_ids', true );
		if ( ! empty( $gupfw_product_gift_ids_arr ) ) {
			for ( $i = 0; $i < count( $gupfw_product_gift_ids_arr ); $i++ ) {
				$gift_id = $gupfw_product_gift_ids_arr[ $i ];
				$gift_product = wc_get_product( $gift_id );
				if ( $gift_product == null ) {
					continue;
				}
				echo '<span style="color: ' . $gupfw_tgfp_in_product_color . '; font-size: ' . $gupfw_tgfp_in_product_fsize . 'px;">' . $gupfw_tgfp_in_product_content . '</span>: <a href="' . get_permalink( $gift_product->get_id() ) . '" class="gupfw_link_to_gift" style="font-size: ' . $gupfw_tgfp_in_product_fsize . 'px;">' . $gift_product->get_title() . '</a><br />';
			}
		}

		// надпись Два товара по цене одного
		if ( get_post_meta( $post->ID, '_two_price_one', true ) === 'yes' ) {
			echo '<span style="color: ' . $gupfw_tgfp_in_product_color . '; font-size: ' . $gupfw_tgfp_in_product_fsize . 'px;">' . __( 'Two products for the price of one', 'gift-upon-purchase-for-woocommerce' ) . '</span><br />';
		}
	}

	public function gift_in_category_info() {
		$gupfw_tgfp_in_product_status = gupfw_optionGET( 'gupfw_tgfp_in_category_status' );
		if ( $gupfw_tgfp_in_product_status === 'hide' ) {
			return;
		}
		$gupfw_tgfp_in_category_content = stripslashes( htmlspecialchars( gupfw_optionGET( 'gupfw_tgfp_in_category_content' ) ) );
		$gupfw_tgfp_in_category_color = gupfw_optionGET( 'gupfw_tgfp_in_category_color' );
		$gupfw_tgfp_in_category_fsize = gupfw_optionGET( 'gupfw_tgfp_in_category_fsize' );

		global $post;
		$gupfw_stopgift = get_post_meta( $post->ID, '_gupfw_stopgift', true );
		if ( $gupfw_stopgift === 'yes' ) {
			return;
		}

		if ( get_post_meta( $post->ID, '_days_of_the_week', true ) !== '' ) {
			$curDate = current_time( 'l' );
			$days_of_the_week = get_post_meta( $post->ID, '_days_of_the_week', true );
			if ( is_array( $days_of_the_week ) && ! empty( $days_of_the_week ) ) {
				if ( ! in_array( $curDate, $days_of_the_week ) ) {
					return;
				}
			}
		}
		if ( get_post_meta( $post->ID, '_days_of_the_hours', true ) !== '' ) {
			$curTime = current_time( 'H' );
			$days_of_the_hours = get_post_meta( $post->ID, '_days_of_the_hours', true );
			if ( is_array( $days_of_the_hours ) && ! empty( $days_of_the_hours ) ) {
				if ( ! in_array( $curTime, $days_of_the_hours ) ) {
					return;
				}
			}
		}

		$gupfw_product_gift_ids_arr = get_post_meta( $post->ID, '_gupfw_product_gift_ids', true );
		if ( ! empty( $gupfw_product_gift_ids_arr ) ) {
			printf( '<p class="gupfw_gift_cat_info"><span style="color: %s; font-size: %spx;">%s</span></p>',
				$gupfw_tgfp_in_category_color,
				$gupfw_tgfp_in_category_fsize,
				$gupfw_tgfp_in_category_content
			);
		}

		// надпись Два товара по цене одного
		if ( get_post_meta( $post->ID, '_two_price_one', true ) === 'yes' ) {
			printf( '<p class="gupfw_gift_cat_info_two_price_one"><span style="color: %s; font-size: %spx;">%s</span></p>',
				$gupfw_tgfp_in_category_color,
				$gupfw_tgfp_in_category_fsize,
				__( 'Two products for the price of one', 'gift-upon-purchase-for-woocommerce' )
			);
		}
	}

	// ниже этой линии нужны правки

	// функция добавляет подарок в корзину
	public function add_product_to_cart() {
		new GUPFW_Error_Log( 'Стартовала add_product_to_cart; Файл: gift-upon-purchase-for-woocommerce.php; Строка: ' . __LINE__ );
		if ( ! is_admin() ) {
			$gift_ids_arr = []; // id полагающихся пользователю подарокв
			// проверяем все товары в корзине на полагающиеся подарки	
			$products_in_cart_arr = WC()->cart->get_cart(); // товары в корзине
			$cart_totals_arr = WC()->cart->get_totals();
			$cart_total = $cart_totals_arr['total'];

			new GUPFW_Error_Log( 'add_product_to_cart вызывает gupfw_gift_check; Файл: gift-upon-purchase-for-woocommerce.php; Строка: ' . __LINE__ );
			if ( gupfw_gift_check( $products_in_cart_arr, $cart_total ) ) {
				$gupfw_gift_for_any_product_arr = gupfw_optionGET( 'gupfw_gift_for_any_product_arr' );
				$gift_ids_arr = array_merge( $gift_ids_arr, $gupfw_gift_for_any_product_arr );
			} else {
				$gupfw_hide_or_remove = htmlspecialchars( gupfw_optionGET( 'gupfw_hide_or_remove' ) );
				$gupfw_gift_for_any_product_arr = gupfw_optionGET( 'gupfw_gift_for_any_product_arr' );
				if ( $gupfw_hide_or_remove == 'remove' ) {
					if ( ! empty( $gupfw_gift_for_any_product_arr ) ) {
						for ( $i = 0; $i < count( $gupfw_gift_for_any_product_arr ); $i++ ) {
							$product_id_for_gift = $gupfw_gift_for_any_product_arr[ $i ];
							// check if product already in cart
							if ( sizeof( $products_in_cart_arr ) > 0 ) {
								foreach ( $products_in_cart_arr as $cart_item_key => $values ) {
									$_product = $values['data'];
									if ( $_product->get_id() == $product_id_for_gift ) {
										$cart_item_key_for_del = $cart_item_key;
										WC()->cart->remove_cart_item( $cart_item_key_for_del ); // удаляем подарочный товар из корзины
										// эта кука нужна нам на случай если удалялся из корзины другой товар, не подарочный
										// а хук woocommerce_cart_item_removed при это всё равно сработал и прописал нам куку
										$cookie_name = 'ignore_refuse_product_' . $product_id_for_gift;
										$expires_time = (int) current_time( 'timestamp' ) + 600; // установить cookie на 10 минут
										wc_setcookie( $cookie_name, $expires_time, $expires_time, '/' ); // устанавливаем куку на весь сайт (слэш)
									}
								}
							}
						}
					}
				}
			}

			if ( sizeof( $products_in_cart_arr ) > 0 ) {
				foreach ( $products_in_cart_arr as $cart_item_key => $values ) {
					if ( $values['variation_id'] == 0 ) {
						$_product = $values['data'];
						$product_id_in_cart = $_product->get_id();
					} else {
						$product_id_in_cart = $values['product_id'];
						new GUPFW_Error_Log( 'В корзине лежит вариация. id товара = ' . $product_id_in_cart . ', id вариации = ' . $values['variation_id'] . '; Файл: gift-upon-purchase-for-woocommerce.php; Строка: ' . __LINE__ );
					}
					if ( get_post_meta( $product_id_in_cart, '_gupfw_product_gift_ids', true ) !== '' ) {
						$gupfw_product_gift_ids_arr = get_post_meta( $product_id_in_cart, '_gupfw_product_gift_ids', true );
						if ( ! empty( $gupfw_product_gift_ids_arr ) ) {

							$curDate = current_time( 'l' );
							$curTime = current_time( 'H' );

							for ( $i = 0; $i < count( $gupfw_product_gift_ids_arr ); $i++ ) {

								if ( get_post_meta( $product_id_in_cart, '_days_of_the_week', true ) !== '' ) {
									$days_of_the_week = get_post_meta( $product_id_in_cart, '_days_of_the_week', true );
									if ( is_array( $days_of_the_week ) && ! empty( $days_of_the_week ) ) {
										if ( ! in_array( $curDate, $days_of_the_week ) ) {
											continue;
										}
									}
								}
								if ( get_post_meta( $product_id_in_cart, '_days_of_the_hours', true ) !== '' ) {
									$days_of_the_hours = get_post_meta( $product_id_in_cart, '_days_of_the_hours', true );
									if ( is_array( $days_of_the_hours ) && ! empty( $days_of_the_hours ) ) {
										if ( ! in_array( $curTime, $days_of_the_hours ) ) {
											continue;
										}
									}
								}

								$gift_ids_arr[] = $gupfw_product_gift_ids_arr[ $i ];
							}
						}
					}

					// добавляем в корзину подарок за самого себя
					if ( get_post_meta( $product_id_in_cart, '_two_price_one', true ) === 'yes' ) {
						// $cart_item_quantity = $values['quantity'] + 1;
						if ( $values['quantity'] == 1 ) {
							WC()->cart->set_quantity( $cart_item_key, 2 );
						}
					}
				}
			}

			// добавим товары-подарки в корзину, если они полагаются
			if ( ! empty( $gift_ids_arr ) ) {
				for ( $i = 0; $i < count( $gift_ids_arr ); $i++ ) {
					$product_id_for_gift = $gift_ids_arr[ $i ];
					$found = false;
					//check if product already in cart
					if ( sizeof( $products_in_cart_arr ) > 0 ) {
						foreach ( $products_in_cart_arr as $cart_item_key => $values ) {
							$_product = $values['data'];
							if ( $_product->get_id() == $product_id_for_gift ) {
								$found = true;
								$cart_item_key_for_del = $cart_item_key;
							}
						}
						// if product not found, add it
						if ( $found !== true && ! isset( $_COOKIE["gupfw_no_gift"] ) ) {
							if ( $this->is_refuse( $product_id_for_gift ) == true ) {
							} else {
								WC()->cart->add_to_cart( $product_id_for_gift );
							}
						} else if ( $found === true ) {
							if ( isset( $_REQUEST['gupfw_refuse_gifts'] ) ) {
								WC()->cart->remove_cart_item( $cart_item_key_for_del );
							}
						}
					}
				}
			}
		}
	}

	// добавляем в ссылку удаления товара из корзины get-параметр по которому будем отслеживать отказ от подарка
	public function change_remove_link( $sprintf, $cart_item_key ) {
		$sprintf = str_replace( $cart_item_key, $cart_item_key . '&refuse_product=1', $sprintf );
		return $sprintf;
	}

	// устанавливаем куку отказа от конкретного подарка
	public function after_remove_product( $cart_item_key, $cart ) {
		$product_id = $cart->removed_cart_contents[ $cart_item_key ]['product_id'];
		$variation_id = $cart->removed_cart_contents[ $cart_item_key ]['variation_id'];
		// если у нас вариация вариативного товара, то создаём куку для данного случая
		if ( $variation_id > 0 ) {
			$product_id = $variation_id;
		}

		$cookie_name = 'refuse_product_' . $product_id;

		// создадим куку отказа от конкретного подарка
		if ( isset( $_GET['refuse_product'] ) ) {
			$expires_time = (int) current_time( 'timestamp' ) + 600; // установить cookie на 10 минут
			wc_setcookie( $cookie_name, $expires_time, $expires_time, '/' ); // устанавливаем куку на весь сайт (слэш)
			$_COOKIE[ $cookie_name ] = $expires_time; // пришлось добавить чтобы кнопка "Принять/убрать подарки" работала синхронно
		}
	}

	// функция проверки отказа от конкретного подарка
	// true - отказался от подарка
	// false - не отказывался либо время отказа истекло
	public function is_refuse( $product_id ) {
		$cookie_name = 'refuse_product_' . $product_id;
		if ( isset( $_COOKIE[ $cookie_name ] ) && ( (int) $_COOKIE[ $cookie_name ] > (int) current_time( 'timestamp' ) ) ) {
			// эта кука нужна нам на случай если удалялся из корзины другой товар, не подарочный
			// а хук woocommerce_cart_item_removed при это всё равно сработал и прописал нам куку 
			// (см строки 682 и 912)			
			$cookie_name = 'ignore_refuse_product_' . $product_id;
			if ( isset( $_COOKIE[ $cookie_name ] ) && ( (int) $_COOKIE[ $cookie_name ] > (int) current_time( 'timestamp' ) ) ) {
				return false;
			}
			return true; // отказался
		} else {
			return false; // не отказывался
		}
	}

	public function change_recalc_price( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		// you can always print_r() your object and look what's inside
		// print_r($cart); exit;
		$gift_ids_arr = []; // id полагающихся пользователю подарокв
		$products_in_cart_arr = $cart->get_cart(); // массив всех товаров в корзине
		if ( empty( $products_in_cart_arr ) ) {
			return;
		}

		$curDate = current_time( 'l' );
		$curTime = current_time( 'H' );

		foreach ( $products_in_cart_arr as $cart_item ) {
			$product_id_in_cart = $cart_item["product_id"];
			$gupfw_stopgift = get_post_meta( $product_id_in_cart, '_gupfw_stopgift', true );
			if ( $gupfw_stopgift === 'yes' ) {
				continue;
			}

			if ( get_post_meta( $product_id_in_cart, '_days_of_the_week', true ) !== '' ) {
				$days_of_the_week = get_post_meta( $product_id_in_cart, '_days_of_the_week', true );
				if ( is_array( $days_of_the_week ) && ! empty( $days_of_the_week ) ) {
					if ( ! in_array( $curDate, $days_of_the_week ) ) {
						continue;
					}
				}
			}
			if ( get_post_meta( $product_id_in_cart, '_days_of_the_hours', true ) !== '' ) {
				$days_of_the_hours = get_post_meta( $product_id_in_cart, '_days_of_the_hours', true );
				if ( is_array( $days_of_the_hours ) && ! empty( $days_of_the_hours ) ) {
					if ( ! in_array( $curTime, $days_of_the_hours ) ) {
						continue;
					}
				}
			}

			if ( get_post_meta( $product_id_in_cart, '_gupfw_product_gift_ids', true ) !== '' ) {
				$gupfw_product_gift_ids_arr = get_post_meta( $product_id_in_cart, '_gupfw_product_gift_ids', true );
				if ( ! empty( $gupfw_product_gift_ids_arr ) ) {
					for ( $i = 0; $i < count( $gupfw_product_gift_ids_arr ); $i++ ) {
						$gift_ids_arr[] = $gupfw_product_gift_ids_arr[ $i ];
					}
				}
			}

			// уменьшим стоимость товара
			if ( get_post_meta( $cart_item["product_id"], '_two_price_one', true ) === 'yes' ) {
				if ( $cart_item['quantity'] > 1 ) {
					// https://www.pandoge.com/haki/skidka-v-procentah-na-vsyu-korzinu-bez-kupona-v-woocommerce
					// https://misha.agency/woocommerce/dobavlenie-kuponov-programmno.html 
					$discount = $cart_item['data']->get_price();
					$cart->add_fee( '100% ' . __( 'discount for one item', 'gift-upon-purchase-for-woocommerce' ) . ' "' . $cart_item['data']->get_name() . '"', -$discount );
				}
			}
		}
		foreach ( $products_in_cart_arr as $hash => $cart_item ) {
			new GUPFW_Error_Log( $cart_item );
			if ( in_array( $cart_item["product_id"], $gift_ids_arr ) || in_array( $cart_item["variation_id"], $gift_ids_arr ) ) { // Если в корзине есть товары с ID = подарка
				if ( $cart_item['quantity'] < 2 ) {
					if ( ! isset( $_COOKIE["gupfw_no_gift"] ) ) { // нет отказа от всех подарков
						if ( true === $this->is_refuse( $cart_item["product_id"] )
							|| true === $this->is_refuse( $cart_item["variation_id"] ) ) {
						} else {
							$zero_price = (float) 0;
							$zero_price = apply_filters(
								'gupfw_f_zero_price',
								$zero_price,
								[ 
									'cart_item' => $cart_item
								]
							);
							$cart_item['data']->set_price( $zero_price );
						}
					}
				}
			}
		}

		$cart_totals_arr = $cart->get_totals();
		$cart_total = $cart_totals_arr['total'];
		new GUPFW_Error_Log( 'change_recalc_price вызывает gupfw_gift_check; Файл: gift-upon-purchase-for-woocommerce.php; Строка: ' . __LINE__ );
		if ( gupfw_gift_check( $products_in_cart_arr, $cart_total, $gift_ids_arr ) === true ) {
			$gupfw_gift_for_any_product_arr = gupfw_optionGET( 'gupfw_gift_for_any_product_arr' );
			$gift_ids_arr = array_merge( $gift_ids_arr, $gupfw_gift_for_any_product_arr );
		}

		foreach ( $products_in_cart_arr as $hash => $cart_item ) {
			new GUPFW_Error_Log( $cart_item );
			if ( in_array( $cart_item["product_id"], $gift_ids_arr ) || in_array( $cart_item["variation_id"], $gift_ids_arr ) ) { // Если в корзине есть товары с ID = подарка
				if ( $cart_item['quantity'] < 2 ) {
					if ( ! isset( $_COOKIE["gupfw_no_gift"] ) ) { // нет отказа от всех подарков
						if ( true === $this->is_refuse( $cart_item["product_id"] )
							|| true === $this->is_refuse( $cart_item["variation_id"] ) ) {
						} else {
							$zero_price = (float) 0;
							$zero_price = apply_filters(
								'gupfw_f_zero_price',
								$zero_price,
								[ 
									'cart_item' => $cart_item
								]
							);
							$cart_item['data']->set_price( $zero_price );
						}
					}
				}
			}
		}

		$gupfw_hide_or_remove = htmlspecialchars( gupfw_optionGET( 'gupfw_hide_or_remove' ) );
		if ( $gupfw_hide_or_remove == 'remove' ) {
			if ( gupfw_gift_check( $products_in_cart_arr, $cart_total ) ) {

			} else {
				$products_in_cart_arr = WC()->cart->get_cart();
				$gupfw_gift_for_any_product_arr = gupfw_optionGET( 'gupfw_gift_for_any_product_arr' );
				if ( ! empty( $gupfw_gift_for_any_product_arr ) ) {
					for ( $i = 0; $i < count( $gupfw_gift_for_any_product_arr ); $i++ ) {
						$product_id_for_gift = $gupfw_gift_for_any_product_arr[ $i ];
						// check if product already in cart
						if ( sizeof( $products_in_cart_arr ) > 0 ) {
							foreach ( $products_in_cart_arr as $cart_item_key => $values ) {
								$_product = $values['data'];
								if ( $_product->get_id() == $product_id_for_gift ) {
									$cart_item_key_for_del = $cart_item_key;
									WC()->cart->remove_cart_item( $cart_item_key_for_del ); // удаляем подарочный товар из корзины									
									// эта кука нужна нам на случай если удалялся из корзины другой товар, не подарочный
									// а хук woocommerce_cart_item_removed при это всё равно сработал и прописал нам куку
									$cookie_name = 'ignore_refuse_product_' . $product_id_for_gift;
									$expires_time = (int) current_time( 'timestamp' ) + 600; // установить cookie на 10 минут
									wc_setcookie( $cookie_name, $expires_time, $expires_time, '/' ); // устанавливаем куку на весь сайт (слэш)
								}
							}
						}
					}
				}
			}
		}

	}

	public function add_gift_to_minicart() {
		// моментально добавляем в мини-корзину подарок
		$this->add_product_to_cart();
	}

	function gupfw_add_excerpt_in_cart_item_name( $item_name, $cart_item, $cart_item_key ) {
		new GUPFW_Error_Log( 'Стартовала gupfw_add_excerpt_in_cart_item_name; Файл: functions.php; Строка: ' . __LINE__ );
		$have_gift = false; // полагаются ли подарки (не важно, отказался от них пользователь или нет)

		$gupfw_tgfp_in_cart_status = gupfw_optionGET( 'gupfw_tgfp_in_cart_status' );
		if ( $gupfw_tgfp_in_cart_status === 'hide' ) {
			return $item_name;
		}
		$gupfw_tgfp_in_cart_content = stripslashes( htmlspecialchars( gupfw_optionGET( 'gupfw_tgfp_in_cart_content' ) ) );
		$gupfw_gift_for_any_product_in_cart_content = stripslashes( htmlspecialchars( gupfw_optionGET( 'gupfw_gift_for_any_product_in_cart_content' ) ) );
		$gupfw_tgfp_in_cart_color = gupfw_optionGET( 'gupfw_tgfp_in_cart_color' );
		$gupfw_tgfp_in_cart_fsize = gupfw_optionGET( 'gupfw_tgfp_in_cart_fsize' );

		global $woocommerce;
		$products_in_cart_arr = $woocommerce->cart->get_cart();
		$gift_ids_arr = []; // массив id подарокв

		$curDate = current_time( 'l' );
		$curTime = current_time( 'H' );
		$additionaltext = '';
		foreach ( $products_in_cart_arr as $cur_cart_item ) {
			$product_id_in_cart = $cur_cart_item["product_id"];
			$gupfw_stopgift = get_post_meta( $product_id_in_cart, '_gupfw_stopgift', true );
			if ( $gupfw_stopgift === 'yes' ) {
				continue;
			}

			if ( get_post_meta( $product_id_in_cart, '_days_of_the_week', true ) !== '' ) {
				$days_of_the_week = get_post_meta( $product_id_in_cart, '_days_of_the_week', true );
				if ( is_array( $days_of_the_week ) && ! empty( $days_of_the_week ) ) {
					if ( ! in_array( $curDate, $days_of_the_week ) ) {
						continue;
					}
				}
			}
			if ( get_post_meta( $product_id_in_cart, '_days_of_the_hours', true ) !== '' ) {
				$days_of_the_hours = get_post_meta( $product_id_in_cart, '_days_of_the_hours', true );
				if ( is_array( $days_of_the_hours ) && ! empty( $days_of_the_hours ) ) {
					if ( ! in_array( $curTime, $days_of_the_hours ) ) {
						continue;
					}
				}
			}

			// текст Один товар бесплатно
			if ( get_post_meta( $product_id_in_cart, '_two_price_one', true ) === 'yes' ) {
				$have_gift = true;
				$additionaltext = '<br /><span style="font-size: ' . $gupfw_tgfp_in_cart_fsize . 'px; color: ' . $gupfw_tgfp_in_cart_color . ';">' . __( 'One item for free. See details below', 'gift-upon-purchase-for-woocommerce' ) . '</span>';
			}

			if ( get_post_meta( $product_id_in_cart, '_gupfw_product_gift_ids', true ) !== '' ) {
				$gupfw_product_gift_ids_arr = get_post_meta( $product_id_in_cart, '_gupfw_product_gift_ids', true );
				if ( ! empty( $gupfw_product_gift_ids_arr ) ) {
					for ( $i = 0; $i < count( $gupfw_product_gift_ids_arr ); $i++ ) {
						$gift_ids_arr[] = $gupfw_product_gift_ids_arr[ $i ];
					}
				}
			}
		}

		if ( in_array( $cart_item["product_id"], $gift_ids_arr ) || in_array( $cart_item["variation_id"], $gift_ids_arr ) ) { // Если в корзине есть товары с ID = подарку
			if ( $cart_item['quantity'] < 2 ) {
				$have_gift = true;
				if ( ! isset( $_COOKIE["gupfw_no_gift"] ) ) {
					if ( $this->is_refuse( $cart_item["product_id"] ) == true || $this->is_refuse( $cart_item["variation_id"] ) == true ) {
					} else {
						// текст в корзине (для индивидуальных подарков)
						$additionaltext = sprintf( '<br /><span style="font-size: %1$spx; color: %2$s;">%3$s %4$s</span>',
							$gupfw_tgfp_in_cart_fsize,
							$gupfw_tgfp_in_cart_color,
							$gupfw_tgfp_in_cart_content,
							$cart_item['data']->name /*,
		  apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			  'woocommerce_cart_item_remove_link',
			  sprintf(
				  '<small><a href="%s&refuse_product=1" return false;" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a></small>',
				  esc_url( wc_get_cart_remove_url( $cart_item["key"] ) ),
				  esc_html__( 'Remove this item', 'woocommerce' ),
				  esc_attr( $cart_item["product_id"] ),
				  '', //esc_attr( $cart_item["sku"] ),
				  __('Remove this gift', 'gift-upon-purchase-for-woocommerce')
			  ),
			  $cart_item["key"]
		  )*/
						);
					}
				}
			}
		}

		$cart_totals_arr = $woocommerce->cart->get_totals();
		//   [subtotal] => (string)2990
		//   [subtotal_tax] => (integer)0
		//   [shipping_total] => (string)0
		//   [shipping_tax] => (integer)0
		//   [shipping_taxes] => (array)
		//   [discount_total] => (integer)0
		//   [discount_tax] => (integer)0
		//   [cart_contents_total] => (string)2990
		//   [cart_contents_tax] => (integer)0
		//   [cart_contents_taxes] => (array)
		//   [fee_total] => (string)0
		//   [fee_tax] => (integer)0
		//   [fee_taxes] => (array)
		//   [total] => (string)2990.00
		//   [total_tax] => (double)0

		$gupfw_rules_for_cart_price = gupfw_optionGET( 'gupfw_rules_for_cart_price' );
		if ( $gupfw_rules_for_cart_price === 'subtotal' ) {
			$cart_total = $cart_totals_arr['subtotal'];
		} else {
			$cart_total = $cart_totals_arr['total'];
		}

		new GUPFW_Error_Log( 'gupfw_add_excerpt_in_cart_item_name вызывает gupfw_gift_check; Файл: gift-upon-purchase-for-woocommerce.php; Строка: ' . __LINE__ );
		if ( gupfw_gift_check( $products_in_cart_arr, $cart_total ) ) {
			$gupfw_gift_for_any_product_arr = gupfw_optionGET( 'gupfw_gift_for_any_product_arr' );
			if ( in_array( $cart_item["product_id"], $gupfw_gift_for_any_product_arr )
				|| in_array( $cart_item["variation_id"], $gupfw_gift_for_any_product_arr ) ) { // Если в корзине есть товары с ID = подарку			
				if ( $cart_item['quantity'] < 2 ) {
					$gupfw_cart_total_price = gupfw_optionGET( 'gupfw_cart_total_price' );
					$cart_total = gupfw_cart_total_without_gifts( $products_in_cart_arr, $gupfw_gift_for_any_product_arr, $gift_ids_arr );
					if ( $cart_total > $gupfw_cart_total_price ) {
						$have_gift = true;
						// текст в корзине (для общих подарков)
						if ( ! isset( $_COOKIE["gupfw_no_gift"] ) ) {
							if ( $this->is_refuse( $cart_item["product_id"] ) == true || $this->is_refuse( $cart_item["variation_id"] ) == true ) {
							} else {
								$additionaltext = sprintf( '<br /><span style="font-size: %1$spx; color: %2$s;">%3$s</span>',
									$gupfw_tgfp_in_cart_fsize,
									$gupfw_tgfp_in_cart_color,
									$gupfw_gift_for_any_product_in_cart_content /*,
									apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
									'<small><a href="%s&refuse_product=1" return false;" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a></small>',
									esc_url( wc_get_cart_remove_url( $cart_item["key"] ) ),
									esc_html__( 'Remove this item', 'woocommerce' ),
									esc_attr( $cart_item["product_id"] ),
									'', //esc_attr( $cart_item["sku"] ),
									__('Remove this gift', 'gift-upon-purchase-for-woocommerce')
									),
									$cart_item["key"]
									)*/
								);
							}
						}
					}
				}
			}
		}

		if ( false === $have_gift ) {
			// делаем через хук иначе будет сыпать нотис о ранее переданных заголовках
			add_action( 'plugins_loaded', function () {
				// установить cookie на 1 час
				$expires_time = (int) current_time( 'timestamp' ) + 3600;
				wc_setcookie( 'gupfw_have_gift', 'true', $expires_time, false );
			} );
			// пришлось добавить чтобы кнопка "Принять/убрать подарки" работала синхронно
			$_COOKIE["gupfw_have_gift"] = 'true';
		} else {
			add_action( 'plugins_loaded', function () {
				// установить cookie на 1 час
				$expires_time = (int) current_time( 'timestamp' ) + 3600;
				wc_setcookie( 'gupfw_have_gift', 'false', $expires_time, false );
			} );
			// пришлось добавить чтобы кнопка "Принять/убрать подарки" работала синхронно
			$_COOKIE["gupfw_have_gift"] = 'false';
		}

		return $item_name . $additionaltext;
	}

	// установка куки отказа от подарков
	public function set_new_cookie() { // setting your cookies there
		if ( isset( $_REQUEST['gupfw_refuse_gifts'] ) ) {
			if ( ! isset( $_COOKIE["gupfw_no_gift"] ) ) {
				$value = 'on';
				$expires_time = (int) current_time( 'timestamp' ) + 3600; // установить cookie на 1 час
				wc_setcookie( 'gupfw_no_gift', $value, $expires_time, false ); // устанавливаем куку на весь сайт (слэш)
				$_COOKIE["gupfw_no_gift"] = 'on'; // пришлось добавить чтобы кнопка "Принять/убрать подарки" работала синхронно
			}
		}
		if ( isset( $_REQUEST['gupfw_accept_gifts'] ) ) {
			$expires_time = (int) current_time( 'timestamp' ) - 3600; // установить cookie на -1 час
			wc_setcookie( 'gupfw_no_gift', '', $expires_time, false ); // удаляем куку со всего сайта
			unset( $_COOKIE["gupfw_no_gift"] );
		}
	}

	// кнопка отказа от подарков
	public function gupfw_before_add_to_cart_btn() {
		$gupfw_displaying_accept_remove_button = gupfw_optionGET( 'gupfw_displaying_accept_remove_button' );
		if ( $gupfw_displaying_accept_remove_button === 'hide' ) {
			return;
		}

		// если подарки не полагаются - скрываем кнопку убрать/показать подарки.
		if ( isset( $_COOKIE["gupfw_have_gift"] ) && $_COOKIE["gupfw_have_gift"] == 'true' ) {
			$display = '';
		} else {
			$display = 'none;';
		}

		if ( isset( $_COOKIE["gupfw_no_gift"] ) ) {
			$gupfw_tgfp_accept_gift_in_cart = gupfw_optionGET( 'gupfw_tgfp_accept_gift_in_cart' );
			$v = $gupfw_tgfp_accept_gift_in_cart;
			$name = 'gupfw_accept_gifts';
		} else {
			$gupfw_tgfp_remove_gift_in_cart = gupfw_optionGET( 'gupfw_tgfp_remove_gift_in_cart' );
			$v = $gupfw_tgfp_remove_gift_in_cart;
			$name = 'gupfw_refuse_gifts';
		}

		printf( '<button id="%1$s" type="submit" class="button gupfw_accept_refuse_button" name="%1$s" style="%2$s">%3$s</button>',
			$name,
			$display,
			$v
		);
	}

	// добавить инофрмацию о подарке на страницу категории
	public function gupfw_my_custom_sale_flash( $text, $post, $_product ) {
		$result = '';
		$result = apply_filters( 'gupfw_add_info_about_gift_to_category_f', $result, $text, $post, $_product );
		return $result;
	}

	private function admin_notices_func( $message, $class ) {
		printf( '<div class="notice %1$s"><p>%2$s</p></div>', $class, $message );
		return;
	}
} /* end class GiftUponPurchaseForWooCommerce */