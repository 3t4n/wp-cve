<?php
/**
 * The main class of the plugin XML for Google Merchant Center
 *
 * @package                 XML for Google Merchant Center
 * @subpackage              
 * @since                   0.1.0
 * 
 * @version                 1.0.0 (07-02-2023)
 * @author                  Maxim Glazunov
 * @link                    https://icopydoc.ru/
 * @see                     
 * 
 * @param		
 *
 * @depends                 classes:    XFGMC_Data_Arr
 *                                      XFGMC_Settings_Page
 *                                      XFGMC_Error_Log
 *                                      XFGMC_Generation_XML
 *                          traits:     
 *                          methods:    
 *                          functions:  common_option_get
 *                                      common_option_upd
 *                          constants:  XFGMC_PLUGIN_VERSION
 *                                      XFGMC_PLUGIN_BASENAME
 *                                      XFGMC_PLUGIN_DIR_URL
 *                          options:    
 */
defined( 'ABSPATH' ) || exit;

final class XmlforGoogleMerchantCenter {
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
	private $plugin_version = XFGMC_PLUGIN_VERSION; // 1.0.0

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

		$name_dir = XFGMC_SITE_UPLOADS_DIR_PATH . '/xfgmc';
		if ( ! is_dir( $name_dir ) ) {
			if ( ! mkdir( $name_dir ) ) {
				error_log( 'ERROR: Ошибка создания папки ' . $name_dir . '; Файл: xml-for-google-merchant-center.php; Строка: ' . __LINE__, 0 );
			}
		}
		$feed_id = '1'; // (string)
		$name_dir = XFGMC_SITE_UPLOADS_DIR_PATH . '/xfgmc/feed' . $feed_id;
		if ( ! is_dir( $name_dir ) ) {
			if ( ! mkdir( $name_dir ) ) {
				error_log( 'ERROR: Ошибка создания папки ' . $name_dir . '; Файл: xml-for-google-merchant-center.php; Строка: ' . __LINE__, 0 );
			}
		}

		xfgmc_optionADD( 'xfgmc_status_sborki', '-1', $feed_id ); // статус сборки файла

		$xfgmc_registered_feeds_arr = [ 
			0 => [ 'last_id' => '1' ],
			1 => [ 'id' => '1' ]
		];

		$def_plugin_date_arr = new XFGMC_Data_Arr();
		$xfgmc_settings_arr = [];
		$xfgmc_settings_arr['1'] = $def_plugin_date_arr->get_opts_name_and_def_date( 'all' );

		if ( is_multisite() ) {
			add_blog_option( get_current_blog_id(), 'xfgmc_version', XFGMC_PLUGIN_VERSION );
			add_blog_option( get_current_blog_id(), 'xfgmc_keeplogs', '0' );
			add_blog_option( get_current_blog_id(), 'xfgmc_disable_notices', '0' );
			add_blog_option( get_current_blog_id(), 'xfgmc_enable_five_min', '0' );
			add_blog_option( get_current_blog_id(), 'xfgmc_feed_content', '' );

			add_blog_option( get_current_blog_id(), 'xfgmc_settings_arr', $xfgmc_settings_arr );
			add_blog_option( get_current_blog_id(), 'xfgmc_registered_feeds_arr', $xfgmc_registered_feeds_arr );
		} else {
			add_option( 'xfgmc_version', XFGMC_PLUGIN_VERSION );
			add_option( 'xfgmc_keeplogs', '0' );
			add_option( 'xfgmc_disable_notices', '0' );
			add_option( 'xfgmc_enable_five_min', '0' );
			add_option( 'xfgmc_feed_content', '' );

			add_option( 'xfgmc_settings_arr', $xfgmc_settings_arr );
			add_option( 'xfgmc_registered_feeds_arr', $xfgmc_registered_feeds_arr );
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

		$xfgmc_registered_feeds_arr = xfgmc_optionGET( 'xfgmc_registered_feeds_arr' );
		for ( $i = 1; $i < count( $xfgmc_registered_feeds_arr ); $i++ ) { // с единицы, т.к инфа по конкретным фидам там
			$feed_id = $xfgmc_registered_feeds_arr[ $i ]['id'];
			wp_clear_scheduled_hook( 'xfgmc_cron_period', array( $feed_id ) ); // отключаем крон
			wp_clear_scheduled_hook( 'xfgmc_cron_sborki', array( $feed_id ) ); // отключаем крон
		}

		deactivate_plugins( 'xml-for-google-merchant-center-pro/xml-for-google-merchant-center-pro.php' );
	}

	public function __construct() {
		load_plugin_textdomain( 'xfgmc', false, XFGMC_PLUGIN_SLUG . '/languages/' ); // load translation
		$this->check_options_upd(); // проверим, нужны ли обновления опций плагина
		$this->init_classes();
		$this->init_hooks(); // подключим хуки
	}

	/**
	 * Initialization classes
	 * 
	 * @return void
	 */
	public function init_classes() {
		/*
		new XFGMC_Interface_Hoocked();
		new ICPD_Feedback( [ 
			'plugin_name' => 'XML for Google Merchant Center',
			'plugin_version' => $this->get_plugin_version(),
			'logs_url' => XFGMC_PLUGIN_UPLOADS_DIR_URL . '/plugin.log',
			'pref' => 'xfgmc',
		] );
		new ICPD_Promo( 'xfgmc' );
		*/
		return;
	}

	/**
	 * Initialization hooks
	 * 
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'admin_init', [ $this, 'listen_submits_func' ], 10 ); // ещё можно слушать чуть раньше на wp_loaded
		add_action( 'admin_menu', [ $this, 'add_admin_menu_func' ], 10, 1 );

		add_filter( 'upload_mimes', [ $this, 'add_mime_types_func' ], 99, 1 );
		add_filter( 'cron_schedules', [ $this, 'add_cron_intervals_func' ], 10, 1 );

		add_action( 'xfgmc_cron_sborki', [ $this, 'xfgmc_do_this_seventy_sec' ], 10, 1 );
		add_action( 'xfgmc_cron_period', [ $this, 'xfgmc_do_this_event' ], 10, 1 );

		// индивидуальные опции доставки товара
		// ! пришлось юзать save_post вместо save_post_product ибо wc блочит обновы	
		add_action( 'save_post', [ $this, 'xfgmc_save_post_product_function' ], 50, 3 );

		// https://wpruse.ru/woocommerce/custom-fields-in-products/
		// https://wpruse.ru/woocommerce/custom-fields-in-variations/
		add_filter( 'woocommerce_product_data_tabs', [ $this, 'xfgmc_added_wc_tabs' ], 10, 1 );
		add_filter( 'xfgmcp_request_string_filter', [ $this, 'for_сompatibility_with_yandex_zen_plug_func' ], 10, 3 );
		add_action( 'admin_footer', [ $this, 'xfgmc_art_added_tabs_icon' ], 10, 1 );
		add_action( 'woocommerce_product_data_panels', [ $this, 'xfgmc_art_added_tabs_panel' ], 10, 1 );

		add_action( 'woocommerce_variation_options', [ $this, 'woocommerce_variation_options_tax' ], 10, 3 );
		add_action( 'woocommerce_save_product_variation', [ $this, 'xfgmc_save_product_variation_func' ], 10, 2 );

		add_action( 'admin_notices', [ $this, 'xfgmc_admin_notices_function' ] );

		/* Регаем стили только для страницы настроек плагина	*/
		add_action( 'admin_init', function () {
			wp_register_style( 'xfgmc-admin-css', XFGMC_PLUGIN_DIR_URL . 'assets/css/xfgmc_style.css' );
		}, 9999 );

		add_filter( 'plugin_action_links', [ $this, 'xfgmc_plugin_action_links' ], 10, 2 );

		add_filter( 'xfgmcp_args_filter', [ $this, 'xfgmc_args_filter_func' ], 10, 3 );

		/* Мета-поля для категорий товаров */
		add_action( "product_cat_edit_form_fields", [ $this, 'xfgmc_add_meta_product_cat' ], 10, 1 );
		add_action( 'edited_product_cat', [ $this, 'xfgmc_save_meta_product_cat' ], 10, 1 );
		add_action( 'create_product_cat', [ $this, 'xfgmc_save_meta_product_cat' ], 10, 1 );
	}

	public function check_options_upd() {
		if ( false == common_option_get( 'xfgmc_version' ) ) { // это первая установка
			if ( is_multisite() ) {
				update_blog_option( get_current_blog_id(), 'xfgmc_version', $this->plugin_version );
			} else {
				update_option( 'xfgmc_version', $this->plugin_version );
			}
		} else {
			$this->set_new_options();
		}
	}

	public function get_plugin_version() {
		if ( is_multisite() ) {
			$v = get_blog_option( get_current_blog_id(), 'xfgmc_version' );
		} else {
			$v = get_option( 'xfgmc_version' );
		}
		return $v;
	}

	public function set_new_options() {
		// Если предыдущая версия плагина меньше текущей
		if ( version_compare( $this->get_plugin_version(), $this->plugin_version, '<' ) ) {

		} else { // обновления не требуются
			return;
		}
		/*		wp_clean_plugins_cache();
			  wp_clean_update_cache();
			  add_filter('pre_site_transient_update_plugins', '__return_null');
			  wp_update_plugins();
			  remove_filter('pre_site_transient_update_plugins', '__return_null');
								*/
		// Если предыдущая версия плагина меньше 2.5.0
		if ( version_compare( xfgmc_VER, '2.5.0', '<' ) ) {
			if ( ! defined( 'xfgmc_ALLNUMFEED' ) ) {
				define( 'xfgmc_ALLNUMFEED', '3' );
			}
			if ( is_multisite() ) {
				if ( get_blog_option( get_current_blog_id(), 'xfgmc_settings_arr' ) === false ) {
					$allNumFeed = (int) xfgmc_ALLNUMFEED;
					xfgmc_add_settings_arr( $allNumFeed );
				}
			} else {
				if ( get_option( 'xfgmc_settings_arr' ) === false ) {
					$allNumFeed = (int) xfgmc_ALLNUMFEED;
					xfgmc_add_settings_arr( $allNumFeed );
				}
			}
		}

		$xfgmc_data_arr_obj = new XFGMC_Data_Arr();
		$opts_arr = $xfgmc_data_arr_obj->get_opts_name_and_def_date_obj( 'all' ); // список дефолтных настроек
		// проверим, заданы ли дефолтные настройки
		$xfgmc_settings_arr = xfgmc_optionGET( 'xfgmc_settings_arr' );
		$xfgmc_settings_arr_keys_arr = array_keys( $xfgmc_settings_arr );
		for ( $i = 0; $i < count( $xfgmc_settings_arr_keys_arr ); $i++ ) {
			$feed_id = (string) $xfgmc_settings_arr_keys_arr[ $i ];
			for ( $n = 0; $n < count( $opts_arr ); $n++ ) {
				$name = $opts_arr[ $n ]->get_name();
				$value = $opts_arr[ $n ]->get_value();
				if ( ! isset( $xfgmc_settings_arr[ $feed_id ][ $name ] ) ) {
					xfgmc_optionUPD( $name, $value, $feed_id, 'yes', 'set_arr' );
				}
			}
		}

		if ( is_multisite() ) {
			update_blog_option( get_current_blog_id(), 'xfgmc_version', $this->plugin_version );
		} else {
			update_option( 'xfgmc_version', $this->plugin_version );
		}
	}

	/*	public function fixed_func($v) {
						if (is_array($v)) {
							return $v;
						} else {
							$xfgmc_registered_feeds_arr = array(
								0 => array('last_id' => '1'),
								1 => array('id' => '1')
							);
							$def_plugin_date_arr = new XFGMC_Data_Arr();
							$xfgmc_settings_arr = array();
							$xfgmc_settings_arr['1'] = $def_plugin_date_arr->get_opts_name_and_def_date('all');
							if (is_multisite()) {
								add_blog_option(get_current_blog_id(), 'xfgmc_settings_arr', $xfgmc_settings_arr);
								add_blog_option(get_current_blog_id(), 'xfgmc_registered_feeds_arr', $xfgmc_registered_feeds_arr);
							} else {
								add_option('xfgmc_settings_arr', $xfgmc_settings_arr);
								add_option('xfgmc_registered_feeds_arr', $xfgmc_registered_feeds_arr);
							}

							$xfgmc_settings_arr = xfgmc_optionGET('xfgmc_settings_arr');

							return $xfgmc_settings_arr;		
						}
					} */

	public function listen_submits_func() {
		do_action( 'xfgmc_listen_submits' );

		if ( isset( $_REQUEST['xfgmc_submit_action'] ) ) {
			$message = __( 'Updated', 'xml-for-google-merchant-center' );
			$class = 'notice-success';
			if ( isset( $_POST['xfgmc_run_cron'] ) && sanitize_text_field( $_POST['xfgmc_run_cron'] ) !== 'off' ) {
				$message .= '. ' . __( 'Creating the feed is running. You can continue working with the website', 'xml-for-google-merchant-center' );
			}

			add_action( 'admin_notices', function () use ($message, $class) {
				$this->admin_notices_func( $message, $class );
			}, 10, 2 );
		}

		if ( isset( $_REQUEST['xfgmc_submit_clear_logs'] ) ) {
			$upload_dir = (object) wp_get_upload_dir();
			$name_dir = $upload_dir->basedir . "/xfgmc";
			$filename = $name_dir . '/xfgmc.log';
			if ( file_exists( $filename ) ) {
				$res = unlink( $filename );
			} else {
				$res = false;
			}
			if ( $res == true ) {
				$message = __( 'Logs were cleared', 'xml-for-google-merchant-center' );
				$class = 'notice-success';
			} else {
				$message = __( 'Error accessing log file. The log file may have been deleted previously', 'xml-for-google-merchant-center' );
				$class = 'notice-warning';
			}

			add_action( 'admin_notices', function () use ($message, $class) {
				$this->admin_notices_func( $message, $class );
			}, 10, 2 );
		}
	}

	public static function xfgmc_add_meta_product_cat( $term ) { ?>
		<tr class="form-field">
			<th scope="row" valign="top"><label>
					<?php _e( 'Google product category', 'xml-for-google-merchant-center' ); ?>
				</label></th>
			<td>
				<input id="xfgmc_google_product_category" type="text" name="xfgmc_cat_meta[xfgmc_google_product_category]"
					value="<?php echo esc_attr( get_term_meta( $term->term_id, 'xfgmc_google_product_category', 1 ) ) ?>" /><br />
				<span class="description">
					<?php _e( 'Optional element', 'xml-for-google-merchant-center' ); ?>
					<strong>google_product_category</strong>.
					<a href="//support.google.com/merchants/answer/6324436" target="_blank">
						<?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?>
					</a>
				</span>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label>tax_category</label></th>
			<td>
				<input id="xfgmc_tax_category" type="text" name="xfgmc_cat_meta[xfgmc_tax_category]"
					value="<?php echo esc_attr( get_term_meta( $term->term_id, 'xfgmc_tax_category', 1 ) ) ?>" /><br />
				<span class="description">
					<?php _e( 'Optional element', 'xml-for-google-merchant-center' ); ?> <strong>tax_category</strong>. <a
						href="//support.google.com/merchants/answer/7569847" target="_blank">
						<?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?>
					</a>
				</span>
			</td>
		</tr>
		<tr class="form-field">
			<?php $xfgmc_size = esc_attr( get_term_meta( $term->term_id, 'xfgmc_size', 1 ) ); ?>
			<th scope="row" valign="top"><label for="xfgmc_size">
					<?php _e( 'Size', 'xml-for-google-merchant-center' ); ?>
				</label></th>
			<td>
				<select name="xfgmc_cat_meta[xfgmc_size]" id="xfgmc_size">
					<option value="default" <?php selected( $xfgmc_size, 'default' ); ?>><?php _e( 'Default', 'xml-for-google-merchant-center' ); ?></option>
					<?php foreach ( xfgmc_get_attributes() as $attribute ) : ?>
						<option value="<?php echo $attribute['id']; ?>" <?php selected( $xfgmc_size, $attribute['id'] ); ?>><?php echo $attribute['name']; ?></option>
					<?php endforeach; ?>
				</select><br />
				<span class="description">
					<?php _e( 'Optional element', 'xml-for-google-merchant-center' ); ?> <strong>g:size</strong>. <a
						href="//support.google.com/merchants/answer/6324492" target="_blank">
						<?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?>
					</a>.
					<?php _e( 'These settings take precedence over those you specify on the "XML for Google Merchant Center" plugin settings page', 'xml-for-google-merchant-center' ); ?>.
				</span>
			</td>
		</tr>
		<tr class="form-field">
			<?php
			$xfgmc_size_type = esc_attr( get_term_meta( $term->term_id, 'xfgmc_size_type', 1 ) );
			$xfgmc_size_type_alt = esc_attr( get_term_meta( $term->term_id, 'xfgmc_size_type_alt', 1 ) );
			?>
			<th scope="row" valign="top"><label for="xfgmc_size_type">
					<?php _e( 'Size type', 'xml-for-google-merchant-center' ); ?>
				</label></th>
			<td class="overalldesc">
				<select name="xfgmc_cat_meta[xfgmc_size_type]" id="xfgmc_size_type">
					<option value="default" <?php selected( $xfgmc_size_type, 'default' ); ?>><?php _e( 'Default', 'xml-for-google-merchant-center' ); ?></option>
					<?php foreach ( xfgmc_get_attributes() as $attribute ) : ?>
						<option value="<?php echo $attribute['id']; ?>" <?php selected( $xfgmc_size_type, $attribute['id'] ); ?>>
							<?php echo $attribute['name']; ?></option>
					<?php endforeach; ?>
				</select><br />
				<?php _e( 'In the absence of a substitute', 'xml-for-google-merchant-center' ); ?>:<br />
				<select name="xfgmc_cat_meta[xfgmc_size_type_alt]">
					<option value="default" <?php selected( $xfgmc_size_type_alt, 'default' ); ?>><?php _e( 'Default', 'xml-for-google-merchant-center' ); ?></option>
					<option value="regular" <?php selected( $xfgmc_size_type_alt, 'regular' ); ?>>Regular</option>
					<option value="petite" <?php selected( $xfgmc_size_type_alt, 'petite' ); ?>>Petite</option>
					<option value="plus" <?php selected( $xfgmc_size_type_alt, 'plus' ); ?>>Plus</option>
					<option value="bigandtall" <?php selected( $xfgmc_size_type_alt, 'bigandtall' ); ?>>Big and tall</option>
					<option value="maternity" <?php selected( $xfgmc_size_type_alt, 'maternity' ); ?>>Maternity</option>
				</select><br />
				<span class="description">
					<?php _e( 'Optional element', 'xml-for-google-merchant-center' ); ?> <strong>g:size_type</strong>.
					<?php _e( 'These settings take precedence over those you specify on the "XML for Google Merchant Center" plugin settings page', 'xml-for-google-merchant-center' ); ?>.
				</span>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label>
					<?php _e( 'Facebook product category', 'xml-for-google-merchant-center' ); ?>
				</label></th>
			<td>
				<input id="xfgmc_fb_product_category" type="text" name="xfgmc_cat_meta[xfgmc_fb_product_category]"
					value="<?php echo esc_attr( get_term_meta( $term->term_id, 'xfgmc_fb_product_category', 1 ) ) ?>" /><br />
				<span class="description">
					<?php _e( 'Optional element', 'xml-for-google-merchant-center' ); ?> <strong>fb_product_category</strong>.
					<?php _e( 'Used only if "Yes" is selected in the "Adapt for Facebook" field in the plugin settings', 'xml-for-google-merchant-center' ); ?>.
					<a href="//www.facebook.com/business/help/120325381656392?id=725943027795860&recommended_by=2041876302542944"
						target="_blank">
						<?php _e( 'Read more', 'xml-for-google-merchant-center' ); ?>
					</a>
				</span>
			</td>
		</tr>
		<?php
	}
	/* Сохранение данных в БД */
	function xfgmc_save_meta_product_cat( $term_id ) {
		if ( ! isset( $_POST['xfgmc_cat_meta'] ) ) {
			return;
		}
		$xfgmc_cat_meta = array_map( 'sanitize_text_field', $_POST['xfgmc_cat_meta'] );
		foreach ( $xfgmc_cat_meta as $key => $value ) {
			if ( empty( $value ) ) {
				delete_term_meta( $term_id, $key );
				continue;
			}
			update_term_meta( $term_id, $key, $value );
		}
		return $term_id;
	}

	public static function xfgmc_plugin_action_links( $actions, $plugin_file ) {
		if ( false === strpos( $plugin_file, XFGMC_PLUGIN_BASENAME ) ) {
			// проверка, что у нас текущий плагин
			return $actions;
		}
		$settings_link = '<a style="color: green; font-weight: 700;" href="/wp-admin/admin.php?page=xfgmcextensions">' . __( 'More features', 'xml-for-google-merchant-center' ) . '</a>';
		array_unshift( $actions, $settings_link );
		$settings_link = '<a href="/wp-admin/admin.php?page=xfgmcexport">' . __( 'Settings', 'xml-for-google-merchant-center' ) . '</a>';
		array_unshift( $actions, $settings_link );
		return $actions;
	}

	public static function xfgmc_args_filter_func( $args, $order_id, $order_email ) {
		$xfgmc_version = xfgmc_optionGET( 'xfgmc_version' );
		$args['basic_version'] = $xfgmc_version;
		return $args;
	}

	public static function xfgmc_admin_css_func() {
		/* Ставим css-файл в очередь на вывод */
		wp_enqueue_style( 'xfgmc-admin-css' );
	}

	public static function xfgmc_admin_head_css_func() {
		/* печатаем css в шапке админки */
		print '<style>/* XML for Google Merchant Center */
			.metabox-holder .postbox-container .empty-container {height: auto !important;}
			.icp_img1 {background-image: url(' . XFGMC_PLUGIN_DIR_URL . '/assets/img/sl1.jpg);}
			.icp_img2 {background-image: url(' . XFGMC_PLUGIN_DIR_URL . '/assets/img/sl2.jpg);}
			.icp_img3 {background-image: url(' . XFGMC_PLUGIN_DIR_URL . '/assets/img/sl3.jpg);}
			.icp_img4 {background-image: url(' . XFGMC_PLUGIN_DIR_URL . '/assets/img/sl4.jpg);}
			.icp_img5 {background-image: url(' . XFGMC_PLUGIN_DIR_URL . '/assets/img/sl5.jpg);}
			.icp_img6 {background-image: url(' . XFGMC_PLUGIN_DIR_URL . '/assets/img/sl6.jpg);}
			.icp_img7 {background-image: url(' . XFGMC_PLUGIN_DIR_URL . '/assets/img/sl7.jpg);}
		</style>';
	}

	// Добавляем пункты меню
	public function add_admin_menu_func() {
		$page_suffix = add_menu_page(
			null,
			__( 'Export Google Merchant Center', 'xml-for-google-merchant-center' ),
			'manage_options',
			'xfgmcexport',
			[ $this, 'get_export_page_func' ],
			'dashicons-redo',
			51
		);
		// создаём хук, чтобы стили выводились только на странице настроек
		add_action( 'admin_print_styles-' . $page_suffix, [ $this, 'xfgmc_admin_css_func' ] );
		add_action( 'admin_print_styles-' . $page_suffix, [ $this, 'xfgmc_admin_head_css_func' ] );

		$page_suffix = add_submenu_page(
			'xfgmcexport',
			__( 'Debug', 'xml-for-google-merchant-center' ),
			__( 'Debug page', 'xml-for-google-merchant-center' ),
			'manage_options',
			'xfgmcdebug',
			'xfgmc_debug_page'
		);
		require_once XFGMC_PLUGIN_DIR_PATH . '/debug.php';
		add_action( 'admin_print_styles-' . $page_suffix, [ $this, 'xfgmc_admin_css_func' ] );
		add_submenu_page(
			'xfgmcexport',
			__( 'Add Extensions',
				'xml-for-google-merchant-center' ),
			__( 'Extensions', 'xml-for-google-merchant-center' ),
			'manage_options',
			'xfgmcextensions',
			'xfgmc_extensions_page'
		);
		require_once XFGMC_PLUGIN_DIR_PATH . '/extensions.php';
	}

	// вывод страницы настроек плагина
	public function get_export_page_func() {
		new XFGMC_Settings_Page();
		return;
	}

	// Разрешим загрузку xml и csv файлов
	public function add_mime_types_func( $mimes ) {
		$mimes['csv'] = 'text/csv';
		$mimes['xml'] = 'text/xml';
		return $mimes;
	}

	// добавляем интервалы крон в 70 секунд и 6 часов 
	public function add_cron_intervals_func( $schedules ) {
		$schedules['fifty_sec'] = array(
			'interval' => 61, // 50
			'display' => '61 sec'
		);
		$schedules['seventy_sec'] = array(
			'interval' => 70,
			'display' => '70 sec'
		);
		$schedules['five_min'] = array(
			'interval' => 300,
			'display' => '5 min'
		);
		$schedules['six_hours'] = array(
			'interval' => 21600,
			'display' => '6 hours'
		);
		$schedules['every_two_days'] = array(
			'interval' => 172800,
			'display' => __( 'Every two days', 'xml-for-google-merchant-center' )
		);
		return $schedules;
	}

	// Сохраняем данные блока, когда пост сохраняется
	function xfgmc_save_post_product_function( $post_id, $post, $update ) {
		new XFGMC_Error_Log( 'Стартовала функция xfgmc_save_post_product_function! Файл: xml-for-google-merchant-center.php; Строка: ' . __LINE__ );

		if ( $post->post_type !== 'product' ) {
			return;
		} // если это не товар вукомерц
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		} // если это ревизия
		// проверяем nonce нашей страницы, потому что save_post может быть вызван с другого места.
		//	if (!wp_verify_nonce($_POST['xfgmc_noncename'], plugin_basename(__FILE__))) {return;}
		// если это автосохранение ничего не делаем
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// проверяем права юзера
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		// Все ОК. Теперь, нужно найти и сохранить данные
		// Очищаем значение поля input.
		new XFGMC_Error_Log( 'Работает функция xfgmc_save_post_product_function! Файл: xml-for-google-merchant-center.php; Строка: ' . __LINE__ );

		// Убедимся что поле установлено.
		if ( isset( $_POST['xfgmc_google_product_category'] ) ) {
			$xfgmc_google_product_category = sanitize_text_field( $_POST['xfgmc_google_product_category'] );
			$xfgmc_fb_product_category = sanitize_text_field( $_POST['_xfgmc_fb_product_category'] );
			$xfgmc_tax_category = sanitize_text_field( $_POST['_xfgmc_tax_category'] );
			$xfgmc_identifier_exists = sanitize_text_field( $_POST['xfgmc_identifier_exists'] );
			$xfgmc_adult = sanitize_text_field( $_POST['xfgmc_adult'] );
			$xfgmc_condition = sanitize_text_field( $_POST['xfgmc_condition'] );
			$xfgmc_is_bundle = sanitize_text_field( $_POST['_xfgmc_is_bundle'] );
			$xfgmc_multipack = sanitize_text_field( $_POST['_xfgmc_multipack'] );
			$xfgmc_unit_pricing_measure = sanitize_text_field( $_POST['_xfgmc_unit_pricing_measure'] );
			$xfgmc_unit_pricing_base_measure = sanitize_text_field( $_POST['_xfgmc_unit_pricing_base_measure'] );
			$xfgmc_shipping_label = sanitize_text_field( $_POST['_xfgmc_shipping_label'] );
			$xfgmc_return_rule_label = sanitize_text_field( $_POST['_xfgmc_return_rule_label'] );
			$xfgmc_store_code = sanitize_text_field( $_POST['_xfgmc_store_code'] );
			$xfgmc_min_handling_time = sanitize_text_field( $_POST['_xfgmc_min_handling_time'] );
			$xfgmc_max_handling_time = sanitize_text_field( $_POST['_xfgmc_max_handling_time'] );
			$xfgmc_custom_label_0 = sanitize_text_field( $_POST['xfgmc_custom_label_0'] );
			$xfgmc_custom_label_1 = sanitize_text_field( $_POST['xfgmc_custom_label_1'] );
			$xfgmc_custom_label_2 = sanitize_text_field( $_POST['xfgmc_custom_label_2'] );
			$xfgmc_custom_label_3 = sanitize_text_field( $_POST['xfgmc_custom_label_3'] );
			$xfgmc_custom_label_4 = sanitize_text_field( $_POST['xfgmc_custom_label_4'] );
			// Обновляем данные в базе данных
			update_post_meta( $post_id, 'xfgmc_google_product_category', $xfgmc_google_product_category );
			update_post_meta( $post_id, '_xfgmc_fb_product_category', $xfgmc_fb_product_category );
			update_post_meta( $post_id, '_xfgmc_tax_category', $xfgmc_tax_category );
			update_post_meta( $post_id, 'xfgmc_identifier_exists', $xfgmc_identifier_exists );
			update_post_meta( $post_id, 'xfgmc_adult', $xfgmc_adult );
			update_post_meta( $post_id, 'xfgmc_condition', $xfgmc_condition );
			update_post_meta( $post_id, '_xfgmc_is_bundle', $xfgmc_is_bundle );
			update_post_meta( $post_id, '_xfgmc_multipack', $xfgmc_multipack );
			update_post_meta( $post_id, '_xfgmc_shipping_label', $xfgmc_shipping_label );
			update_post_meta( $post_id, '_xfgmc_unit_pricing_measure', $xfgmc_unit_pricing_measure );
			update_post_meta( $post_id, '_xfgmc_unit_pricing_base_measure', $xfgmc_unit_pricing_base_measure );
			update_post_meta( $post_id, '_xfgmc_return_rule_label', $xfgmc_return_rule_label );
			update_post_meta( $post_id, '_xfgmc_store_code', $xfgmc_store_code );
			update_post_meta( $post_id, '_xfgmc_min_handling_time', $xfgmc_min_handling_time );
			update_post_meta( $post_id, '_xfgmc_max_handling_time', $xfgmc_max_handling_time );
			update_post_meta( $post_id, 'xfgmc_custom_label_0', $xfgmc_custom_label_0 );
			update_post_meta( $post_id, 'xfgmc_custom_label_1', $xfgmc_custom_label_1 );
			update_post_meta( $post_id, 'xfgmc_custom_label_2', $xfgmc_custom_label_2 );
			update_post_meta( $post_id, 'xfgmc_custom_label_3', $xfgmc_custom_label_3 );
			update_post_meta( $post_id, 'xfgmc_custom_label_4', $xfgmc_custom_label_4 );
		}
		do_action( 'xfgmc_save_post_product', $post_id, $post, $update );

		$feed_id = '1'; // (string) создадим строковую переменную
		// нужно ли запускать обновление фида при перезаписи файла
		$xfgmc_settings_arr = xfgmc_optionGET( 'xfgmc_settings_arr' );
		$xfgmc_settings_arr_keys_arr = array_keys( $xfgmc_settings_arr );
		for ( $i = 0; $i < count( $xfgmc_settings_arr_keys_arr ); $i++ ) {
			$feed_id = $xfgmc_settings_arr_keys_arr[ $i ];

			new XFGMC_Error_Log( 'FEED № ' . $feed_id . '; Шаг $i = ' . $i . ' цикла по формированию кэша файлов; Файл: xml-for-google-merchant-center.php; Строка: ' . __LINE__ );

			$result_get_unit_obj = new XFGMC_Get_Unit( $post_id, $feed_id ); // формируем фид товара
			$result_xml = $result_get_unit_obj->get_result();
			$ids_in_xml = $result_get_unit_obj->get_ids_in_xml();

			//			if (class_exists('WOOCS')) {global $WOOCS; $WOOCS->reset_currency();}
			xfgmc_wf( $result_xml, $post_id, $feed_id, $ids_in_xml ); // записываем кэш-файл

			// нужно ли запускать обновление фида при перезаписи файла
			$xfgmc_ufup = xfgmc_optionGET( 'xfgmc_ufup', $feed_id, 'set_arr' );
			if ( $xfgmc_ufup !== 'on' ) {
				$feed_id++;
				continue; /*return;*/
			}
			$status_sborki = (int) xfgmc_optionGET( 'xfgmc_status_sborki', $feed_id );
			if ( $status_sborki > -1 ) {
				$feed_id++;
				continue; /*return;*/
			} // если идет сборка фида - пропуск

			$xfgmc_date_save_set = xfgmc_optionGET( 'xfgmc_date_save_set', $feed_id, 'set_arr' );
			$xfgmc_date_sborki = xfgmc_optionGET( 'xfgmc_date_sborki', $feed_id, 'set_arr' );

			// !! т.к у нас работа с array_keys, то в $feed_id может быть int, а не string значит двойное равенство лучше
			if ( $feed_id == '1' ) {
				$prefFeed = '';
			} else {
				$prefFeed = $feed_id;
			}
			if ( is_multisite() ) {
				/*
				 *	wp_get_upload_dir();
				 *   'path'    => '/home/site.ru/public_html/wp-content/uploads/2016/04',
				 *	'url'     => 'http://site.ru/wp-content/uploads/2016/04',
				 *	'subdir'  => '/2016/04',
				 *	'basedir' => '/home/site.ru/public_html/wp-content/uploads',
				 *	'baseurl' => 'http://site.ru/wp-content/uploads',
				 *	'error'   => false,
				 */
				$upload_dir = (object) wp_get_upload_dir();
				$filenamefeed = $upload_dir->basedir . "/" . $prefFeed . "feed-xml-" . get_current_blog_id() . ".xml";
			} else {
				$upload_dir = (object) wp_get_upload_dir();
				$filenamefeed = $upload_dir->basedir . "/" . $prefFeed . "feed-xml-0.xml";
			}
			if ( ! file_exists( $filenamefeed ) ) {
				new XFGMC_Error_Log( 'FEED № ' . $feed_id . '; WARNING: Файла filenamefeed = ' . $filenamefeed . ' не существует! Пропускаем быструю сборку; Файл: xml-for-google-merchant-center.php; Строка: ' . __LINE__ );
				$feed_id++;
				continue; /*return;*/
			} // файла с фидом нет

			clearstatcache(); // очищаем кэш дат файлов
			$last_upd_file = filemtime( $filenamefeed );
			new XFGMC_Error_Log( 'FEED № ' . $feed_id . '; $xfgmc_date_save_set=' . $xfgmc_date_save_set . ';$filenamefeed=' . $filenamefeed, 0 );
			new XFGMC_Error_Log( 'FEED № ' . $feed_id . '; Начинаем сравнивать даты! Файл: xml-for-google-merchant-center.php; Строка: ' . __LINE__ );
			if ( $xfgmc_date_save_set > $last_upd_file ) {
				// настройки фида сохранялись позже, чем создан фид		
				// нужно полностью пересобрать фид
				$arr_maybe = array( 'off', 'five_min', 'hourly', 'six_hours', 'twicedaily', 'daily', 'week' );
				$xfgmc_run_cron = xfgmc_optionGET( 'xfgmc_status_cron', $feed_id, 'set_arr' );
				if ( in_array( $xfgmc_run_cron, $arr_maybe ) ) {
					if ( $xfgmc_run_cron === 'off' ) {
					} else {
						$feedid = (string) $feed_id; // для правильности работы важен тип string!
						$recurrence = $xfgmc_run_cron;
						wp_clear_scheduled_hook( 'xfgmc_cron_period', array( $feedid ) );
						wp_schedule_event( time(), $recurrence, 'xfgmc_cron_period', array( $feedid ) );
						new XFGMC_Error_Log( 'FEED № ' . $feedid . '; Для полной пересборки после быстрого сохранения xfgmc_cron_period внесен в список заданий; Файл: xml-for-google-merchant-center.php; Строка: ' . __LINE__ );
					}
				} else {
					new XFGMC_Error_Log( 'FEED № ' . $feed_id . '; ERROR: Крон ' . $xfgmc_run_cron . ' не зарегистрирован. Файл: xml-for-google-merchant-center.php; Строка: ' . __LINE__ );
				}
			} else { // нужно лишь обновить цены	
				$feed_id = (string) $feed_id;
				new XFGMC_Error_Log( 'FEED № ' . $feed_id . '; NOTICE: Настройки фида сохранялись раньше, чем создан фид. Нужно лишь обновить цены; Файл: xml-for-google-merchant-center.php; Строка: ' . __LINE__ );
				$generation = new XFGMC_Generation_XML( $feed_id );
				$generation->clear_file_ids_in_xml( $feed_id );
				$generation->onlygluing();
			}
		}
		return;
	}

	/* функции крона */
	public function xfgmc_do_this_seventy_sec( $feed_id ) {
		new XFGMC_Error_Log( 'Cтартовала крон-задача do_this_seventy_sec' );
		$generation = new XFGMC_Generation_XML( $feed_id ); // делаем что-либо каждые 70 сек
		$generation->run();
	}
	public function xfgmc_do_this_event( $feed_id = '1' ) {
		new XFGMC_Error_Log( 'FEED № ' . $feed_id . '; Крон xfgmc_do_this_event включен. Делаем что-то каждый час; Файл: xml-for-google-merchant-center.php; Строка: ' . __LINE__ );
		$step_export = (int) xfgmc_optionGET( 'xfgmc_step_export', $feed_id, 'set_arr' );
		if ( $step_export === 0 ) {
			$step_export = 500;
		}
		xfgmc_optionUPD( 'xfgmc_status_sborki', 1, $feed_id );

		wp_clear_scheduled_hook( 'xfgmc_cron_sborki', array( $feed_id ) );

		// Возвращает nul/false. null когда планирование завершено. false в случае неудачи.
		$res = wp_schedule_event( time(), 'seventy_sec', 'xfgmc_cron_sborki', array( $feed_id ) );
		if ( $res === false ) {
			new XFGMC_Error_Log( 'FEED № ' . $feed_id . '; ERROR: Не удалось запланировань CRON seventy_sec; Файл: xml-for-google-merchant-center.php; Строка: ' . __LINE__ );
		} else {
			new XFGMC_Error_Log( 'FEED № ' . $feed_id . '; CRON seventy_sec успешно запланирован; Файл: xml-for-google-merchant-center.php; Строка: ' . __LINE__ );
		}
	}
	/* end функции крона */

	public static function xfgmc_added_wc_tabs( $tabs ) {
		$tabs['xfgmc_special_panel'] = array(
			'label' => __( 'XML for Google Merchant Center', 'xml-for-google-merchant-center' ), // название вкладки
			'target' => 'xfgmc_added_wc_tabs', // идентификатор вкладки
			'class' => array( 'hide_if_grouped' ), // классы управления видимостью вкладки в зависимости от типа товара
			'priority' => 70, // приоритет вывода
		);
		return $tabs;
	}

	public static function xfgmc_art_added_tabs_icon() { ?>
		<style>
			#woocommerce-coupon-data ul.wc-tabs li.special_panel_options a::before,
			#woocommerce-product-data ul.wc-tabs li.special_panel_options a::before,
			.woocommerce ul.wc-tabs li.special_panel_options a::before {
				content: "\f172";
			}
		</style>
		<?php
	}

	public static function xfgmc_art_added_tabs_panel() {
		global $post; ?>
		<div id="xfgmc_added_wc_tabs" class="panel woocommerce_options_panel">
			<?php do_action( 'xfgmc_before_options_group', $post ); ?>
			<div class="options_group">
				<h2><strong>
						<?php
						_e( 'Individual product settings for XML for Google Merchant Center', 'xml-for-google-merchant-center' );
						?>
					</strong></h2>
				<?php do_action( 'xfgmc_prepend_options_group', $post ); ?>
				<?php
				// текстовое поле
				woocommerce_wp_text_input( [ 
					'id' => 'xfgmc_google_product_category',
					'label' => __( 'Google product category', 'xml-for-google-merchant-center' ),
					'placeholder' => '',
					// 'desc_tip'			=> 'true',
					// 'custom_attributes' => array('required' => 'required'),
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>google_product_category</strong>. <a href="//support.google.com/merchants/answer/6324436" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
				] );

				woocommerce_wp_text_input( array(
					'id' => '_xfgmc_tax_category',
					'label' => 'tax_category',
					'placeholder' => '',
					// 'desc_tip'			=> 'true',
					// 'custom_attributes' => array('required' => 'required'),
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>tax_category</strong>. <a href="//support.google.com/merchants/answer/7569847" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
				) );

				woocommerce_wp_text_input( array(
					'id' => '_xfgmc_fb_product_category',
					'label' => __( 'Facebook product category', 'xml-for-google-merchant-center' ),
					'placeholder' => '',
					// 'desc_tip'			=> 'true',
					// 'custom_attributes' => array('required' => 'required'),
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>fb_product_category</strong>. ' . __( 'Used only if "Yes" is selected in the "Adapt for Facebook" field in the plugin settings', 'xml-for-google-merchant-center' ) . '. <a href="//www.facebook.com/business/help/120325381656392?id=725943027795860&recommended_by=2041876302542944" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
				) );

				woocommerce_wp_select( [ 
					'id' => 'xfgmc_identifier_exists',
					'label' => __( 'Identifier exists', 'xml-for-google-merchant-center' ),
					'options' => [ 
						'default' => __( 'Default', 'xml-for-google-merchant-center' ),
						'disabled' => __( 'Disabled', 'xml-for-google-merchant-center' ),
						'no' => __( 'No', 'xml-for-google-merchant-center' ) . ' (no)',
						'yes' => __( 'Yes', 'xml-for-google-merchant-center' ) . ' (yes)',
					],
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>identifier_exists</strong>. <a href="//support.google.com/merchants/answer/6324478" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
				] );

				woocommerce_wp_select( array(
					'id' => 'xfgmc_adult',
					'label' => __( 'Adult', 'xml-for-google-merchant-center' ),
					'options' => array(
						'off' => __( 'Off', 'xml-for-google-merchant-center' ),
						'no' => __( 'No', 'xml-for-google-merchant-center' ),
						'yes' => __( 'Yes', 'xml-for-google-merchant-center' ),
					),
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>adult</strong>. <a href="//support.google.com/merchants/answer/6324508" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
				) );

				woocommerce_wp_select( array(
					'id' => 'xfgmc_condition',
					'label' => __( 'Сondition', 'xml-for-google-merchant-center' ),
					'options' => array(
						'default' => __( 'Default', 'xml-for-google-merchant-center' ),
						'off' => __( 'Off', 'xml-for-google-merchant-center' ),
						'new' => __( 'New', 'xml-for-google-merchant-center' ),
						'refurbished' => __( 'Refurbished', 'xml-for-google-merchant-center' ),
						'used' => __( 'Used', 'xml-for-google-merchant-center' ),
					),
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>condition</strong>',
				) );

				woocommerce_wp_select( array(
					'id' => '_xfgmc_is_bundle',
					'label' => __( 'Kit', 'xml-for-google-merchant-center' ),
					'options' => array(
						'default' => __( 'Default', 'xml-for-google-merchant-center' ),
						'off' => __( 'Off', 'xml-for-google-merchant-center' ),
						'no' => __( 'No', 'xml-for-google-merchant-center' ),
						'yes' => __( 'Yes', 'xml-for-google-merchant-center' ),
					),
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>is_bundle</strong>. <a href="//support.google.com/merchants/answer/6324449" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
				) );

				woocommerce_wp_text_input( array(
					'id' => '_xfgmc_multipack',
					'label' => __( 'Multipack', 'xml-for-google-merchant-center' ),
					'placeholder' => '',
					'type' => 'number',
					'custom_attributes' => array(
						'step' => '1',
						'min' => '0',
					),
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>multipack</strong>. <a href="//support.google.com/merchants/answer/6324488" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
				) );

				woocommerce_wp_text_input( array(
					'id' => '_xfgmc_unit_pricing_measure',
					'label' => __( 'Unit pricing measure', 'xml-for-google-merchant-center' ),
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>unit_pricing_measure</strong>. <a href="//support.google.com/merchants/answer/6324455" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
					//				'desc_tip'		=> 'true', // Всплывающая подсказка
					'type' => 'text', // Тип поля
				) );

				woocommerce_wp_text_input( array(
					'id' => '_xfgmc_unit_pricing_base_measure',
					'label' => __( 'Unit pricing base measure', 'xml-for-google-merchant-center' ),
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>unit_pricing_base_measure</strong>. <a href="//support.google.com/merchants/answer/6324490" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
					//				'desc_tip'		=> 'true', // Всплывающая подсказка
					'type' => 'text', // Тип поля
				) );

				woocommerce_wp_text_input( array(
					'id' => '_xfgmc_shipping_label',
					'label' => __( 'Definition', 'xml-for-google-merchant-center' ) . ' shipping_label',
					'placeholder' => '',
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>shipping_label</strong>. <a href="//support.google.com/merchants/answer/6324504" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
				) );

				woocommerce_wp_text_input( array(
					'id' => '_xfgmc_return_rule_label',
					'label' => __( 'Definition', 'xml-for-google-merchant-center' ) . ' return_rule_label',
					'placeholder' => '',
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>return_rule_label</strong>. <a href="//support.google.com/merchants/answer/9673755" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
				) );

				woocommerce_wp_text_input( array(
					'id' => '_xfgmc_store_code',
					'label' => __( 'Definition', 'xml-for-google-merchant-center' ) . ' store_code',
					'placeholder' => '',
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>store_code</strong>. <a href="//support.google.com/merchants/answer/3061342" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
				) );

				woocommerce_wp_text_input( array(
					'id' => '_xfgmc_min_handling_time',
					'label' => __( 'Definition', 'xml-for-google-merchant-center' ) . ' min_handling_time',
					'placeholder' => '',
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>min_handling_time</strong>. <a href="//support.google.com/merchants/answer/7388496" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
				) );

				woocommerce_wp_text_input( array(
					'id' => '_xfgmc_max_handling_time',
					'label' => __( 'Definition', 'xml-for-google-merchant-center' ) . ' max_handling_time',
					'placeholder' => '',
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>max_handling_time</strong>. <a href="//support.google.com/merchants/answer/7388496" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
				) );

				woocommerce_wp_text_input( array(
					'id' => 'xfgmc_custom_label_0',
					'label' => __( 'Definition', 'xml-for-google-merchant-center' ) . ' custom_label_0',
					'placeholder' => '',
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>custom_label_0</strong>. <a href="//support.google.com/merchants/answer/6324473" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
				) );

				woocommerce_wp_text_input( array(
					'id' => 'xfgmc_custom_label_1',
					'label' => __( 'Definition', 'xml-for-google-merchant-center' ) . ' custom_label_1',
					'placeholder' => '',
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>custom_label_1</strong>. <a href="//support.google.com/merchants/answer/6324473" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
				) );

				woocommerce_wp_text_input( array(
					'id' => 'xfgmc_custom_label_2',
					'label' => __( 'Definition', 'xml-for-google-merchant-center' ) . ' custom_label_2',
					'placeholder' => '',
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>custom_label_2</strong>. <a href="//support.google.com/merchants/answer/6324473" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
				) );

				woocommerce_wp_text_input( array(
					'id' => 'xfgmc_custom_label_3',
					'label' => __( 'Definition', 'xml-for-google-merchant-center' ) . ' custom_label_3',
					'placeholder' => '',
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>custom_label_3</strong>. <a href="//support.google.com/merchants/answer/6324473" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
				) );

				woocommerce_wp_text_input( array(
					'id' => 'xfgmc_custom_label_4',
					'label' => __( 'Definition', 'xml-for-google-merchant-center' ) . ' custom_label_4',
					'placeholder' => '',
					'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>custom_label_4</strong>. <a href="//support.google.com/merchants/answer/6324473" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
				) );
				?>
				<?php do_action( 'xfgmc_append_options_group', $post ); ?>
			</div>
			<?php do_action( 'xfgmc_after_options_group', $post ); ?>
		</div>
		<?php
	}

	public static function woocommerce_variation_options_tax( $loop, $variation_data, $variation ) {
		woocommerce_wp_text_input( array(
			'id' => '_xfgmc_unit_pricing_measure[' . $variation->ID . ']',
			'label' => __( 'Unit pricing measure', 'xml-for-google-merchant-center' ) . ' ' . __( 'for Google XML feed', 'xml-for-google-merchant-center' ),
			'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>unit_pricing_measure</strong>. <a href="//support.google.com/merchants/answer/6324455" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
			'type' => 'text', // Тип поля
			'custom_attributes' => array(),
			'value' => get_post_meta( $variation->ID, '_xfgmc_unit_pricing_measure', true ),
		) );

		woocommerce_wp_text_input( array(
			'id' => '_xfgmc_unit_pricing_base_measure[' . $variation->ID . ']',
			'label' => __( 'Unit pricing base measure', 'xml-for-google-merchant-center' ) . ' ' . __( 'for Google XML feed', 'xml-for-google-merchant-center' ),
			'description' => __( 'Optional element', 'xml-for-google-merchant-center' ) . ' <strong>unit_pricing_measure</strong>. <a href="//support.google.com/merchants/answer/6324490" target="_blank">' . __( 'Read more', 'xml-for-google-merchant-center' ) . '</a>',
			'type' => 'text', // Тип поля
			'custom_attributes' => array(),
			'value' => get_post_meta( $variation->ID, '_xfgmc_unit_pricing_base_measure', true ),
		) );
	}

	public static function xfgmc_save_product_variation_func( $post_id ) {
		// обращаем внимание на двойное подчёркивание в $woocommerce__xfgmc_unit_pricing_measure
		$woocommerce__xfgmc_unit_pricing_measure = $_POST['_xfgmc_unit_pricing_measure'][ $post_id ];
		if ( isset( $woocommerce__xfgmc_unit_pricing_measure ) && ! empty( $woocommerce__xfgmc_unit_pricing_measure ) ) {
			update_post_meta( $post_id, '_xfgmc_unit_pricing_measure', esc_attr( $woocommerce__xfgmc_unit_pricing_measure ) );
		} else {
			update_post_meta( $post_id, '_xfgmc_unit_pricing_measure', '' );
		}

		$woocommerce__xfgmc_unit_pricing_base_measure = $_POST['_xfgmc_unit_pricing_base_measure'][ $post_id ];
		if ( isset( $woocommerce__xfgmc_unit_pricing_base_measure ) && ! empty( $woocommerce__xfgmc_unit_pricing_base_measure ) ) {
			update_post_meta( $post_id, '_xfgmc_unit_pricing_base_measure', esc_attr( $woocommerce__xfgmc_unit_pricing_base_measure ) );
		} else {
			update_post_meta( $post_id, '_xfgmc_unit_pricing_base_measure', '' );
		}
	}

	// совместимость с палгином RSS for Yandex Zen
	public function for_сompatibility_with_yandex_zen_plug_func( $dwl_link, $order_id, $order_email ) {
		if ( xfgmcp_license_status() == 'ok' ) {
			if ( empty( $order_id ) || empty( $order_email ) ) {
				xfgmc_optionUPD( 'yzen_yandex_zeng_rss', 'enabled' );
			} else {
				xfgmc_optionUPD( 'yzen_yandex_zeng_rss', 'disabled' );
			}
		}
		return $dwl_link;
	}

	public function admin_notices_func( $message, $class ) {
		$xfgmc_disable_notices = xfgmc_optionGET( 'xfgmc_disable_notices' );
		if ( $xfgmc_disable_notices === 'on' ) {
			return;
		} else {
			printf( '<div class="notice %1$s"><p>%2$s</p></div>', $class, $message );
			return;
		}
	}

	// Вывод различных notices
	public function xfgmc_admin_notices_function() {
		$feed_id = '1'; // (string) создадим строковую переменную
		// нужно ли запускать обновление фида при перезаписи файла

		$xfgmc_disable_notices = xfgmc_optionGET( 'xfgmc_disable_notices' );
		if ( $xfgmc_disable_notices !== 'on' ) {
			$xfgmc_settings_arr = xfgmc_optionGET( 'xfgmc_settings_arr' );
			$xfgmc_settings_arr_keys_arr = array_keys( $xfgmc_settings_arr );
			for ( $i = 0; $i < count( $xfgmc_settings_arr_keys_arr ); $i++ ) {
				$feed_id = $xfgmc_settings_arr_keys_arr[ $i ];
				$status_sborki = xfgmc_optionGET( 'xfgmc_status_sborki', $feed_id );
				if ( $status_sborki == false ) {
					continue;
				} else {
					$status_sborki = (int) $status_sborki;
				}
				if ( $status_sborki !== -1 ) {
					$count_posts = wp_count_posts( 'product' );
					$vsegotovarov = $count_posts->publish;
					$step_export = (int) xfgmc_optionGET( 'xfgmc_step_export', $feed_id, 'set_arr' );
					if ( $step_export === 0 ) {
						$step_export = 500;
					}
					//					$vobrabotke = $status_sborki-$step_export;

					$vobrabotke = ( ( $status_sborki - 1 ) * $step_export ) - $step_export;

					if ( $vsegotovarov > $vobrabotke ) {
						$vyvod = 'FEED № ' . $feed_id . ' ' . __( 'Progress', 'xml-for-google-merchant-center' ) . ': ' . $vobrabotke . ' ' . __( 'from', 'xml-for-google-merchant-center' ) . ' ' . $vsegotovarov . ' ' . __( 'products', 'xml-for-google-merchant-center' ) . '.<br />' . __( 'If the progress indicators have not changed within 20 minutes, try reducing the "Step of export" in the plugin settings', 'xml-for-google-merchant-center' );
					} else if ( $step_export == 1 ) {
						$vyvod = 'FEED № ' . $feed_id . ' ' . __( 'Category list import ', 'xml-for-google-merchant-center' );
					} else {
						$vyvod = 'FEED № ' . $feed_id . ' ' . __( 'Prior to the completion of less than 70 seconds', 'xml-for-google-merchant-center' );
					}
					print '<div class="updated notice notice-success is-dismissible"><p>' . __( 'We are working on automatic file creation. XML will be developed soon', 'xml-for-google-merchant-center' ) . '. ' . $vyvod . '.</p></div>';
				}
			}
		}

		/* сброс настроек */
		/*		if (isset($_REQUEST['xfgmc_submit_reset'])) { 
											if (!empty($_POST) && check_admin_referer('xfgmc_nonce_action_reset', 'xfgmc_nonce_field_reset')) {
												$this->on_uninstall();
												$this->on_activation();
												print '<div class="updated notice notice-success is-dismissible"><p>'. __('The settings have been reset', 'xml-for-google-merchant-center'). '.</p></div>';
											}
										} */
	}

	public static function set_html_content_type() {
		return 'text/html';
	}

} /* end class XmlforGoogleMerchantCenter */