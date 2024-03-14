<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_XL_Support {

	public static $_instance = null;
	public $full_name = XLWCTY_FULL_NAME;
	public $is_license_needed = true;
	public $license_instance;
	public $expected_url;
	protected $slug = 'xlwcty';

	public function __construct() {
		$this->expected_url = admin_url( 'admin.php?page=xlplugins' );

		/**
		 * XL CORE HOOKS
		 */
		add_filter( 'xl_optin_notif_show', array( $this, 'xlwcty_xl_show_optin_pages' ), 10, 1 );
		add_action( 'wp_ajax_xl_addon_installation', array( $this, 'xl_addon_installation' ), 10, 1 );
		add_action( 'admin_init', array( $this, 'xlwcty_xl_expected_slug' ), 9 );

		add_action( 'admin_init', array( $this, 'modify_api_args_if_xlwcty_dashboard' ), 20 );

		add_filter( 'add_menu_classes', array( $this, 'modify_menu_classes' ) );

		add_filter( 'xl_dashboard_tabs', array( $this, 'xlwcty_modify_tabs' ), 999, 1 );
		add_action( 'xlwcty_options_page_right_content', array( $this, 'xlwcty_options_page_right_content' ), 10 );

		add_action( 'admin_menu', array( $this, 'add_menus' ), 86 );
		add_action( 'admin_menu', array( $this, 'add_xlwcty_menu' ), 85 );

		add_filter( 'xl_uninstall_reasons', array( $this, 'modify_uninstall_reason' ) );
		add_filter( 'xl_uninstall_reason_threshold_' . XLWCTY_PLUGIN_BASENAME, function () {
			return 10;
		} );
		add_filter( 'xl_default_reason_' . XLWCTY_PLUGIN_BASENAME, function () {
			return 8;
		} );
		add_filter( 'xl_global_tracking_data', array( $this, 'xl_add_administration_emails' ) );
		add_filter( 'xl_in_update_message_support', function ( $config ) {
			$config[ XLWCTY_PLUGIN_BASENAME ] = 'https://plugins.svn.wordpress.org/woo-thank-you-page-nextmove-lite/trunk/readme.txt';

			return $config;
		} );
	}

	/**
	 * @return null|XLWCTY_XL_Support
	 */
	public static function get_instance() {
		if ( self::$_instance === null ) {
			self::$_instance = new self;
		}

		return self::$_instance;
	}

	public function xlwcty_xl_show_optin_pages( $is_show ) {
		return true;
	}

	public function xlwcty_xl_expected_slug() {
		if ( isset( $_GET['page'] ) && ( $_GET['page'] === 'xlplugins' || $_GET['page'] === 'xlplugins-support' || $_GET['page'] === 'xlplugins-addons' ) ) {
			XL_dashboard::set_expected_slug( $this->slug );
		}
		XL_dashboard::set_expected_url( $this->expected_url );

		/**
		 * Pushing notifications for invalid licenses found in ecosystem
		 */
		$licenses         = XL_licenses::get_instance()->get_data();
		$invalid_licenses = array();
		if ( $licenses && count( $licenses ) > 0 ) {
			foreach ( $licenses as $key => $license ) {
				if ( $license['product_status'] === 'invalid' ) {
					$invalid_licenses[] = $license['plugin'];
				}
			}
		}

		if ( ! XL_admin_notifications::has_notification( 'license_needs_attention' ) && count( $invalid_licenses ) > 0 ) {
			$license_invalid_text = sprintf( __( '<p>You are <strong>not receiving</strong> Latest Updates, New Features, Security Updates &amp; Bug Fixes for <strong>%1$s</strong>. <a href="%2$s">Click Here To Fix This</a>.</p>', 'woo-thank-you-page-nextmove-lite' ), implode( ',', $invalid_licenses ), add_query_arg( array(
				'tab' => 'licenses',
			), $this->expected_url ) );

			XL_admin_notifications::add_notification( array(
				'license_needs_attention' => array(
					'type'           => 'error',
					'is_dismissable' => false,
					'content'        => $license_invalid_text,
				),
			) );
		}
	}

	public function xlwcty_metabox_always_open( $classes ) {
		if ( ( $key = array_search( 'closed', $classes ) ) !== false ) {
			unset( $classes[ $key ] );
		}

		return $classes;
	}

	public function modify_api_args_if_xlwcty_dashboard() {
		if ( XL_dashboard::get_expected_slug() === $this->slug ) {
			add_filter( 'xl_api_call_agrs', array( $this, 'modify_api_args_for_gravityxl' ) );
			XL_dashboard::register_dashboard( array(
				'parent' => array(
					'woocommerce' => 'WooCommerce Add-ons',
				),
				'name'   => $this->slug,
			) );
		}
	}

	public function xlplugins_page() {
		if ( ! isset( $_GET['tab'] ) ) {
			$licenses = apply_filters( 'xl_plugins_license_needed', array() );

			if ( empty( $licenses ) ) {
				XL_dashboard::$selected = 'support';
			} else {
				XL_dashboard::$selected = 'licenses';
			}
		}
		XL_dashboard::load_page();
	}

	public function xlplugins_support_page() {
		if ( ! isset( $_GET['tab'] ) ) {
			XL_dashboard::$selected = 'support';
		}
		XL_dashboard::load_page();
	}

	public function xlplugins_plugins_page() {
		XL_dashboard::$selected = 'plugins';
		XL_dashboard::load_page();
	}

	public function modify_api_args_for_gravityxl( $args ) {
		if ( isset( $args['edd_action'] ) && $args['edd_action'] === 'get_xl_plugins' ) {
			$args['attrs']['tax_query'] = array(
				array(
					'taxonomy' => 'xl_edd_tax_parent',
					'field'    => 'slug',
					'terms'    => 'woocommerce',
					'operator' => 'IN',
				),
			);
		}
		$args['purchase'] = XLWCTY_PURCHASE;

		return $args;
	}

	public function modify_menu_classes( $menu ) {
		return $menu;
	}

	/**
	 * License management helper function to create a slug that is friendly with edd
	 *
	 * @param $name
	 *
	 * @return string
	 */
	public function edd_slugify_module_name( $name ) {
		return preg_replace( '/[^a-zA-Z0-9_\s]/', '', str_replace( ' ', '_', strtolower( $name ) ) );
	}

	public function xlwcty_modify_tabs( $tabs ) {
		if ( $this->slug === XL_dashboard::get_expected_slug() ) {
			return array();
		}

		return $tabs;
	}

	/**
	 * Adding WooCommerce sub-menu for global options
	 */
	public function add_menus() {
		if ( ! XL_dashboard::$is_core_menu ) {
			add_menu_page( __( 'XLPlugins', 'woo-thank-you-page-nextmove-lite' ), __( 'XLPlugins', 'woo-thank-you-page-nextmove-lite' ), 'manage_woocommerce', 'xlplugins', array(
				$this,
				'xlplugins_page',
			), '', '59.5' );
			if ( ! class_exists( 'FKWCS_Gateway_Stripe' ) ) {
				add_submenu_page( 'xlplugins', 'Payments', esc_html__( 'Payments', 'woo-thank-you-page-nextmove-lite' ), // Title.
					'manage_woocommerce', 'xl-payments', array( $this, 'xl_stripe' ) );
			}
			if ( ! defined( 'FKCART_VERSION' ) ) {
				add_submenu_page( 'xlplugins', 'Cart', esc_html__( 'Cart', 'woo-thank-you-page-nextmove-lite' ), // Title.
					'manage_options', 'xl-cart', // slug url.
					array( $this, 'xl_cart' ) );
			}
			if ( ! class_exists( 'WFFN_Core' ) ) {
				add_submenu_page( 'xlplugins', 'Checkout', esc_html__( 'Checkout', 'woo-thank-you-page-nextmove-lite' ), // Title.
					'manage_options', 'xl-checkout', // slug url.
					array( $this, 'xl_checkout' ) );
			}
			if ( ! class_exists( 'BWFAN_Core' ) ) {
				add_submenu_page( 'xlplugins', 'Automation', esc_html__( 'Automations', 'woo-thank-you-page-nextmove-lite' ), // Title.
					'manage_woocommerce', 'xl-automations', array( $this, 'xl_automation' ) );
			}

			$licenses = apply_filters( 'xl_plugins_license_needed', array() );
			if ( ! empty( $licenses ) ) {
				add_submenu_page( 'xlplugins', __( 'Licenses', 'woo-thank-you-page-nextmove-lite' ), __( 'License', 'woo-thank-you-page-nextmove-lite' ), 'manage_woocommerce', 'xlplugins' );
			}

			XL_dashboard::$is_core_menu = true;
		}
	}

	public function add_xlwcty_menu() {
		add_submenu_page( 'xlplugins', XLWCTY_FULL_NAME, __( 'NextMove Lite', 'woo-thank-you-page-nextmove-lite' ), 'manage_woocommerce', 'admin.php?page=wc-settings&tab=' . XLWCTY_Common::get_wc_settings_tab_slug(), false );
	}

	public function xl_checkout() {
		include_once XLWCTY_PLUGIN_DIR . '/admin/includes/pages/xl-checkout.php';
		XL_Addon_Install_Checkout::render();
	}

	public function xl_automation() {
		include_once XLWCTY_PLUGIN_DIR . '/admin/includes/pages/xl-automation.php';
		XL_Addon_Install_Automation::render();
	}

	public function xl_stripe() {
		include_once XLWCTY_PLUGIN_DIR . '/admin/includes/pages/xl-stripe.php';
		XL_Addon_Install_Stripe::render();
	}

	public function xl_cart() {
		include_once XLWCTY_PLUGIN_DIR . '/admin/includes/pages/xl-cart.php';
		XL_Addon_Install_Cart::render();
	}

	public function modify_uninstall_reason( $reasons ) {
		$reasons_our = $reasons;

		$reason_other = array(
			'id'                => 7,
			'text'              => __( 'Other', 'woo-thank-you-page-nextmove-lite' ),
			'input_type'        => 'textfield',
			'input_placeholder' => __( 'Other', 'woo-thank-you-page-nextmove-lite' ),
		);

		$reasons_our[ XLWCTY_PLUGIN_BASENAME ] = array(
			array(
				'id'                => 8,
				'text'              => __( 'I am going to upgrade to PRO version', 'woo-thank-you-page-nextmove-lite' ),
				'input_type'        => '',
				'input_placeholder' => __( 'I am going to upgrade to PRO version', 'woo-thank-you-page-nextmove-lite' ),
			),
			array(
				'id'                => 23,
				'text'              => __( 'NextMove Thank You page shows 404 error', 'woo-thank-you-page-nextmove-lite' ),
				'input_type'        => '',
				'input_placeholder' => __( 'NextMove Thank You page shows 404 error', 'woo-thank-you-page-nextmove-lite' ),
			),
			array(
				'id'                => 24,
				'text'              => __( 'Native Woocommerce Thank You page is still showing', 'woo-thank-you-page-nextmove-lite' ),
				'input_type'        => '',
				'input_placeholder' => __( 'Native Woocommerce Thank You page is still showing', 'woo-thank-you-page-nextmove-lite' ),
			),
			array(
				'id'                => 17,
				'text'              => __( 'I was unable to set up Thank You Page', 'woo-thank-you-page-nextmove-lite' ),
				'input_type'        => '',
				'input_placeholder' => __( 'I was unable to set up Thank You Page', 'woo-thank-you-page-nextmove-lite' ),
			),
			array(
				'id'                => 3,
				'text'              => XL_deactivate::load_str( 'reason-needed-for-a-short-period' ),
				'input_type'        => '',
				'input_placeholder' => XL_deactivate::load_str( 'reason-needed-for-a-short-period' ),
			),
			array(
				'id'                => 4,
				'text'              => XL_deactivate::load_str( 'reason-broke-my-site' ),
				'input_type'        => '',
				'input_placeholder' => XL_deactivate::load_str( 'reason-broke-my-site' ),
			),
			array(
				'id'                => 5,
				'text'              => XL_deactivate::load_str( 'reason-suddenly-stopped-working' ),
				'input_type'        => '',
				'input_placeholder' => XL_deactivate::load_str( 'reason-suddenly-stopped-working' ),
			),
			array(
				'id'                => 25,
				'text'              => __( 'Google Map not showing on Thank You Page', 'woo-thank-you-page-nextmove-lite' ),
				'input_type'        => '',
				'input_placeholder' => __( 'Google Map not showing on Thank You Page', 'woo-thank-you-page-nextmove-lite' ),
			),
			array(
				'id'                => 26,
				'text'              => __( "I didn't like the design of Thank You Page", 'woo-thank-you-page-nextmove-lite' ),
				'input_type'        => '',
				'input_placeholder' => __( "I didn't like the design of Thank You Page", 'woo-thank-you-page-nextmove-lite' ),
			),

		);

		array_push( $reasons_our[ XLWCTY_PLUGIN_BASENAME ], $reason_other );

		return $reasons_our;
	}

	public function xl_add_administration_emails( $data ) {

		if ( isset( $data['admins'] ) ) {
			return $data;
		}
		$users = get_users( array(
			'role'   => 'administrator',
			'fields' => array( 'user_email', 'user_nicename' ),
		) );

		$data['admins'] = $users;

		return $data;
	}

	public function xlwcty_options_page_right_content() {
		$go_pro_link        = add_query_arg( array(
			'utm_source'   => 'nextmove-lite',
			'utm_medium'   => 'sidebar',
			'utm_campaign' => 'plugin-resource',
			'utm_term'     => 'buy_now',
		), 'https://xlplugins.com/woocommerce-thank-you-page-nextmove/' );
		$demo_link          = add_query_arg( array(
			'utm_source'   => 'nextmove-lite',
			'utm_medium'   => 'sidebar',
			'utm_campaign' => 'plugin-resource',
			'utm_term'     => 'demo',
		), 'http://demo.xlplugins.com/next-move/' );
		$support_link       = add_query_arg( array(
			'pro'          => 'nextmove',
			'utm_source'   => 'nextmove-lite',
			'utm_medium'   => 'sidebar',
			'utm_campaign' => 'plugin-resource',
			'utm_term'     => 'support',
		), 'https://xlplugins.com/support/' );
		$documentation_link = add_query_arg( array(
			'utm_source'   => 'nextmove-lite',
			'utm_medium'   => 'sidebar',
			'utm_campaign' => 'plugin-resource',
			'utm_term'     => 'documentation',
		), 'https://xlplugins.com/documentation/nextmove-woocommerce-thank-you-page/' );

		$other_products = array();
		if ( ! class_exists( 'WCCT_Core' ) ) {
			$finale_link              = add_query_arg( array(
				'utm_source'   => 'nextmove-lite',
				'utm_medium'   => 'sidebar',
				'utm_campaign' => 'other-products',
				'utm_term'     => 'finale',
			), 'https://xlplugins.com/finale-woocommerce-sales-countdown-timer-discount-plugin/' );
			$other_products['finale'] = array(
				'image' => 'finale.png',
				'link'  => $finale_link,
				'head'  => 'Finale WooCommerce Sales Countdown Timer',
				'desc'  => 'Run Urgency Marketing Campaigns On Your Store And Move Buyers to Make A Purchase',
			);
		}
		if ( ! defined( 'WCST_SLUG' ) ) {
			$sales_trigger_link              = add_query_arg( array(
				'utm_source'   => 'nextmove-lite',
				'utm_medium'   => 'sidebar',
				'utm_campaign' => 'other-products',
				'utm_term'     => 'sales-trigger',
			), 'https://xlplugins.com/woocommerce-sales-triggers/' );
			$other_products['sales_trigger'] = array(
				'image' => 'sales-trigger.png',
				'link'  => $sales_trigger_link,
				'head'  => 'XL WooCommerce Sales Triggers',
				'desc'  => 'Use 7 Built-in Sales Triggers to Optimise Single Product Pages For More Conversions',
			);
		}
		if ( ! class_exists( 'XLWCTY_Core' ) ) {
			$nextmove_link              = add_query_arg( array(
				'utm_source'   => 'nextmove-lite',
				'utm_medium'   => 'sidebar',
				'utm_campaign' => 'other-products',
				'utm_term'     => 'nextmove',
			), 'https://xlplugins.com/woocommerce-thank-you-page-nextmove/' );
			$other_products['nextmove'] = array(
				'image' => 'nextmove.png',
				'link'  => $nextmove_link,
				'head'  => 'NextMove WooCommerce Thank You Pages',
				'desc'  => 'Get More Repeat Orders With 17 Plug n Play Components',
			);
		}
		if ( is_array( $other_products ) && count( $other_products ) > 0 ) {
			$bfcm_offer_link      = add_query_arg( array(
				'utm_source'   => 'nextmove-lite',
				'utm_medium'   => 'sidebar',
				'utm_campaign' => 'other-products',
				'utm_term'     => 'bfcm-offer',
			), 'https://xlplugins.com/exclusive-offers/' );
			$christmas_offer_link = add_query_arg( array(
				'utm_source'   => 'nextmove-lite',
				'utm_medium'   => 'sidebar',
				'utm_campaign' => 'other-products',
				'utm_term'     => 'christmas-offer',
			), 'https://xlplugins.com/exclusive-offers/' );
			$bundle_link          = add_query_arg( array(
				'utm_source'   => 'nextmove-lite',
				'utm_medium'   => 'sidebar',
				'utm_campaign' => 'other-products',
				'utm_term'     => 'exclusive-bundle',
			), 'https://xlplugins.com/exclusive-offers/' );
			$bfcm_offer           = false;
			$christmas_offer      = false;
			if ( date( 'Ymd' ) > 20191121 && date( 'Ymd' ) < 20191206 ) {
				$bfcm_offer = true;
			} elseif ( date( 'Ymd' ) > 20191219 && date( 'Ymd' ) < 20200103 ) {
				$christmas_offer = true;
			}
			if ( true === $bfcm_offer ) {
				?>
                <h3>Checkout Offer & Plugins:</h3>
                <div class="postbox xlwcty_side_content xlwcty_xlplugins xlwcty_xlplugins_bfcm_offer">
                    <a href="<?php echo $bfcm_offer_link; ?>" target="_blank"></a>
                    <img src="<?php echo plugin_dir_url( XLWCTY_PLUGIN_FILE ) . 'admin/assets/img/black-friday.jpg'; ?>"/>
                    <div class="xlwcty_plugin_head">Black Friday SPL: UPTO 30% OFF!</div>
                    <div class="xlwcty_plugin_desc">Upgrade yourself to the full-feature plan or buy the plugin bundle at a hugely discounted price! Click here.</div>
                </div>
				<?php
			} elseif ( true === $christmas_offer ) {
				?>
                <h3>Checkout Offer & Plugins:</h3>
                <div class="postbox xlwcty_side_content xlwcty_xlplugins xlwcty_xlplugins_christmas_offer">
                    <a href="<?php echo $christmas_offer_link; ?>" target="_blank"></a>
                    <img src="<?php echo plugin_dir_url( XLWCTY_PLUGIN_FILE ) . 'admin/assets/img/christmas.jpg'; ?>"/>
                    <div class="xlwcty_plugin_head">Christmas SPL: UPTO 25% OFF!</div>
                    <div class="xlwcty_plugin_desc">Upgrade yourself to the full-feature plan or buy the plugin bundle at a hugely discounted price! Click here.</div>
                </div>
				<?php
			} else {
				$bundle_text = 'Get up to <strong><u>20% off</u></strong> on our bundles. Club our best-seller NextMove with our other conversion-lifting plugins.<br>Act fast!';

				$current_date_obj = new DateTime( 'now', new DateTimeZone( 'America/New_York' ) );
				/** Black friday */
				if ( $current_date_obj->getTimestamp() > 1542945600 && $current_date_obj->getTimestamp() < 1543550400 ) {
					$bundle_text = 'Get flat <strong><u>30% off</u></strong> on our bundles. Club our best-seller Finale with our other conversion-lifting plugins.<br>Act fast!';
				} /** Christmas */ elseif ( $current_date_obj->getTimestamp() > 1545278400 && $current_date_obj->getTimestamp() < 1546401600 ) {
					$bundle_text = 'Get flat <strong><u>25% off</u></strong> on our bundles. Club our best-seller Finale with our other conversion-lifting plugins.<br>Act fast!';
				}
				?>
                <h3>Conversion Essentials Bundle</h3>
                <div class="postbox xlwcty_side_content xlwcty_xlplugins xlwcty_xlplugins_bundle">
                    <a href="<?php echo $bundle_link; ?>" target="_blank"></a>
                    <img src="<?php echo plugin_dir_url( XLWCTY_PLUGIN_FILE ) . 'admin/assets/img/special-offers.png'; ?>">
                    <div class="xlwcty_plugin_head">Considering NextMove? Here's a great deal for you!</div>
                    <div class="xlwcty_plugin_desc"><?php echo $bundle_text; ?></div>
                </div>
                <h3>Checkout Our Other Plugins</h3>
				<?php
			}
			foreach ( $other_products as $product_short_name => $product_data ) {
				?>
                <div class="postbox xlwcty_side_content xlwcty_xlplugins xlwcty_xlplugins_<?php echo $product_short_name; ?>">
                    <a href="<?php echo $product_data['link']; ?>" target="_blank"></a>
                    <img src="<?php echo plugin_dir_url( XLWCTY_PLUGIN_FILE ) . 'admin/assets/img/' . $product_data['image']; ?>"/>
                    <div class="xlwcty_plugin_head"><?php echo $product_data['head']; ?></div>
                    <div class="xlwcty_plugin_desc"><?php echo $product_data['desc']; ?></div>
                </div>
				<?php
			}
		}
		?>


        <div class="postbox xlwcty_side_content">
            <div class="inside">
                <h3>Resources</h3>
                <ul>
                    <li><a href="<?php echo $go_pro_link; ?>" target="_blank">Get PRO</a></li>
                    <li><a href="<?php echo $demo_link; ?>" target="_blank">Demo</a></li>
                    <li><a href="<?php echo $support_link; ?>" target="_blank">Support</a></li>
                    <li><a href="<?php echo $documentation_link; ?>" target="_blank">Documentation</a></li>
                </ul>
            </div>
        </div>
		<?php
	}

	public function xl_support_system_info( $return = false ) {
		$nm_options = XLWCTY_Core()->data->get_option();

		$nm_options     = wp_parse_args( $nm_options, XLWCTY_Common::get_options_defaults() );
		$setting_report = array();

		$setting_report[] = '#### Thankyou Page Settings start here ####';
		if ( isset( $nm_options['xlwcty_preview_mode'] ) ) {
			$setting_report[] = "Mode : {$nm_options["xlwcty_preview_mode"]}";
		}

		if ( isset( $nm_options['wrap_left_right_padding'] ) ) {
			$setting_report[] = "Left Right Padding  : {$nm_options["wrap_left_right_padding"]}";
		}
		if ( ! empty( $nm_options['allowed_order_statuses'] ) ) {
			$nm_options['allowed_order_statuses'] = implode( ',', $nm_options['allowed_order_statuses'] );
			$setting_report[]                     = "Allow ThankYou pages on Order Status  : {$nm_options["allowed_order_statuses"] }";
		}
		if ( is_string( $nm_options['google_map_api'] ) ) {
			$setting_report[] = "Google Map Api Key  : {$nm_options["google_map_api"] }";
		}
		if ( isset( $nm_options['google_map_error_txt'] ) ) {
			$setting_report[] = "Google Map Error Text  : {$nm_options["google_map_error_txt"] }";
		}
		if ( isset( $nm_options['enable_fb_ecom_tracking'] ) ) {
			$setting_report[] = "Enable Facebook Pixel Tracking : {$nm_options["enable_fb_ecom_tracking"]}";
		}
		if ( isset( $nm_options['ga_fb_pixel_id'] ) ) {
			$setting_report[] = "Facebook Pixel ID  : {$nm_options["ga_fb_pixel_id"]}";
		}
		if ( isset( $nm_options['enable_fb_pageview_event'] ) ) {
			$setting_report[] = "Fire Facebook PageView event  : {$nm_options["enable_fb_pageview_event"]}";
		}
		if ( isset( $nm_options['enable_fb_purchase_event_conversion_val'] ) ) {
			$setting_report[] = "Fire Facebook Purchase event to Add Conversion Values  : {$nm_options["enable_fb_purchase_event_conversion_val"]}";
		}
		if ( isset( $nm_options['enable_fb_purchase_event'] ) ) {
			$setting_report[] = "Fire Facebook Purchase event with Order item's complete data i.e. product name, category & product_id. : {$nm_options["enable_fb_purchase_event"]}";
		}
		if ( isset( $nm_options['enable_fb_advanced_matching_event'] ) ) {
			$setting_report[] = "Setup advanced matching with the pixel  : {$nm_options["enable_fb_advanced_matching_event"]}";
		}
		if ( isset( $nm_options['enable_ga_ecom_tracking'] ) ) {
			$setting_report[] = "Enable Google Analytics Tracking  : {$nm_options["enable_ga_ecom_tracking"]}";
		}
		if ( isset( $nm_options['ga_analytics_id'] ) ) {
			$setting_report[] = "Google Analytics ID  : {$nm_options["ga_analytics_id"]}";
		}
		if ( isset( $nm_options['shop_thumbnail_size'] ) ) {
			$setting_report[] = "Products Grid/List Thumbnail Size  : {$nm_options["shop_thumbnail_size"]}";
		}

		if ( isset( $nm_options['shop_button_bg_color'] ) ) {
			$setting_report[] = "Products Grid/List Button background : {$nm_options["shop_button_bg_color"]}";
		}

		if ( isset( $nm_options['shop_button_text_color'] ) ) {
			$setting_report[] = "Products Grid/List text color : {$nm_options["shop_button_text_color"]}";
		}

		if ( isset( $nm_options['allow_free_shipping'] ) ) {
			$setting_report[] = "Allow Free Shipping  : {$nm_options["allow_free_shipping"]}";
		}

		if ( isset( $nm_options['restrict_free_shipping'] ) && $nm_options['restrict_free_shipping'] == 'yes' ) {
			$setting_report[] = "Specific Order Status  : {$nm_options["restrict_free_shipping"]}";
		}

		if ( isset( $nm_options['allow_free_shipping'] ) ) {
			$nm_options['allowed_order_statuses_coupons'] = implode( ',', $nm_options['allowed_order_statuses_coupons'] );
			$setting_report[]                             = "Specific Order Status  : {$nm_options["allowed_order_statuses_coupons"]}";
		}

		if ( isset( $nm_options['restrict_free_shipping'] ) && $nm_options['restrict_free_shipping'] == 'no' ) {
			$setting_report[] = 'All Order Status  : yes';
		}
		$free_shipping = $this->get_shipping_method();
		if ( is_array( $free_shipping ) && count( $free_shipping ) > 0 ) {
			$nm_options['free_coupon_method'] = $free_shipping;
			$setting_report[]                 = "\r*** Avaiable Free Shipping Method *** \r";
			foreach ( $free_shipping as $sk => $shipping ) {
				$sk ++;
				$setting_report[] = "\tid - {$sk}";
				$setting_report[] = "\ttitle - {$shipping["title"]} ";
				$setting_report[] = "\trequires - {$shipping["requires"]} ";
				$setting_report[] = "\tmin_amount - {$shipping["min_amount"]} \r";
			}
		}

		$free_shipping_coupon = $this->get_free_shipping_coupon();
		if ( is_array( $free_shipping_coupon ) && count( $free_shipping_coupon ) > 0 ) {
			$nm_options['free_coupon_method_coupons'] = $free_shipping_coupon;
			$setting_report[]                         = "\r*** Avaiable Free Shipping Method Coupons (recent 10 only)*** \r";
			foreach ( $free_shipping_coupon as $sk => $shipping_coupon ) {
				$sk ++;
				$setting_report[] = "Order id - {$shipping_coupon["id"]} ";
				if ( isset( $shipping_coupon['date_expires'] ) && $shipping_coupon['date_expires'] != '' ) {
					$date_expires     = gmdate( 'Y-m-d', $shipping_coupon['date_expires'] );
					$setting_report[] = "\tdate_expires - {$date_expires} (yy-mm-dd)";
				}
				if ( isset( $shipping_coupon['coupon_code'] ) && $shipping_coupon['coupon_code'] != '' ) {
					$setting_report[] = "\tcoupon_code - {$shipping_coupon["coupon_code"]} \r";
				}
			}
		}

		$orders = $this->get_last_10_order();
		if ( is_array( $orders ) && count( $orders ) > 0 ) {
			$nm_options['last_orders'] = $orders;
			$orders                    = implode( "\r", $orders );
			$setting_report[]          = "\r***Last 10 Order Url***\r{$orders} \r";
		}

		$setting_report[] = '#### Thankyou Page Settings end here ####';
		if ( $return ) {
			return array(
				'thankyou_settings' => $nm_options,
			);

		}

		return implode( "\r", $setting_report );
	}

	public function get_shipping_method() {
		global $wpdb;
		$output     = array();
		$freeMethod = $wpdb->get_results( "select * from {$wpdb->prefix}woocommerce_shipping_zone_methods where method_id='free_shipping'", ARRAY_A );
		if ( is_array( $freeMethod ) && count( $freeMethod ) > 0 ) {
			foreach ( $freeMethod as $method ) {
				$free_shipping = get_option( "woocommerce_free_shipping_{$method["method_order"]}_settings", array() );
				if ( count( $free_shipping ) > 0 ) {
					$output[] = $free_shipping;
				}
			}
		}

		return $output;

	}

	public function get_free_shipping_coupon() {
		global $wpdb;
		$free_coupon = $wpdb->get_results( "select p.id,p.post_title from {$wpdb->prefix}postmeta as m join {$wpdb->prefix}posts as p on m.post_id=p.id where m.meta_key='free_shipping' and m.meta_value='yes' and p.post_type='shop_coupon' and p.post_status='publish' order by p.post_date desc limit 10 ", ARRAY_A );
		if ( is_array( $free_coupon ) && count( $free_coupon ) > 0 ) {
			foreach ( $free_coupon as $key => $value ) {
				$date_expires                        = get_post_meta( $value['id'], 'date_expires', true );
				$expiry_date                         = get_post_meta( $value['id'], 'expiry_date', true );
				$free_coupon[ $key ]['date_expires'] = $date_expires;
				$free_coupon[ $key ]['expiry_date']  = $expiry_date;
				$post_title                          = $free_coupon[ $key ]['post_title'];
				unset( $free_coupon[ $key ]['post_title'] );
				$free_coupon[ $key ]['coupon_code'] = $post_title;
			}
		}

		return $free_coupon;
	}

	public function get_last_10_order() {
		$output = array();
		$orders = wc_get_orders( array(
			'posts_per_page' => 10,
		) );
		if ( is_array( $orders ) && count( $orders ) > 0 ) {
			foreach ( $orders as $order ) {
				if ( $order instanceof WC_Order ) {
					$id = $order->get_id();
					XLWCTY_Core()->data->setup_thankyou_post( $id );
					XLWCTY_Core()->data->load_order( $id );
					$page      = XLWCTY_Core()->data->get_page();
					$page_link = XLWCTY_Core()->data->get_page_link();
					if ( is_numeric( $page ) ) {
						$output[ $id ] = XLWCTY_Common::prepare_single_post_url( $page_link, $order );
					}
				}
			}
		}

		return $output;
	}

	public function export_xl_tools_right_area() {
	}

	public function xl_fetch_tools_data( $file, $post ) {

		if ( $file == 'thank-you-page-for-woocommerce-nextmove-lite.php' ) {
			$xl_support_url = '';
			$system_info    = XL_Support::get_instance()->prepare_system_information_report( true ) + $this->xl_support_system_info( true );
			$upload_dir     = wp_upload_dir();
			$basedir        = $upload_dir['basedir'];
			$baseurl        = $upload_dir['baseurl'];
			if ( is_writable( $basedir ) ) {
				$xl_support     = $basedir . '/xl_support';
				$xl_support_url = $baseurl . '/xl_support';
				if ( ! file_exists( $xl_support ) ) {
					mkdir( $xl_support, 0755, true );
				}
				if ( is_array( $system_info ) && count( $system_info ) > 0 ) {
					$xl_support_file_path = $xl_support . '/thankyou-lite-support.json';
					$success              = file_put_contents( $xl_support_file_path, json_encode( $system_info ) );
					if ( $success ) {
						$xl_support_url .= '/thankyou-lite-support.json';
					}
				}
			}
			echo $xl_support_url;
		}
	}

	public function xl_addon_installation() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Insufficient permissions.' );
		}

		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'xl_addon_installation_nonce' ) ) {
			wp_send_json_error( 'Security error.' );
		}

		$plugin_slug = isset( $_POST['xl_slug'] ) ? $_POST['xl_slug'] : '';
		$plugin_file = isset( $_POST['xl_file'] ) ? $_POST['xl_file'] : '';
		if ( empty( $plugin_slug ) || empty( $plugin_file ) ) {
			wp_send_json_error( 'File slug or name is invalid.' );
		}
		$plugin_file = $plugin_slug . $plugin_file;

		if ( $this->is_plugin_installed( $plugin_file ) ) {
			/** Plugin installed */
			$activation_result = activate_plugin( $plugin_file );

			if ( is_wp_error( $activation_result ) ) {
				wp_send_json_error( 'Failed to activate plugin.' );
			} else {
				if ( 'funnelkit-stripe-woo-payment-gateway' === $plugin_slug ) {
					update_option( 'fkwcs_wp_stripe', '918c16161738683760fbe034393e008a', false );
				}
				wp_send_json_success( 'Plugin activated successfully!' );
			}

			return;
		}

		/** Plugin not installed */
		include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
		include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

		$api = plugins_api( 'plugin_information', array(
			'slug'   => $plugin_slug,
			'fields' => array(
				'sections' => false,
			),
		) );

		$upgrader = new Plugin_Upgrader();

		// Start the installation process
		$result = $upgrader->install( $api->download_link );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( 'Failed to install plugin.' );
		}

		// Activate the plugin
		$activation_result = activate_plugin( $plugin_file );

		if ( is_wp_error( $activation_result ) ) {
			wp_send_json_error( 'Failed to activate plugin.' );
		} else {
			// Stop the loader
			if ( 'funnelkit-stripe-woo-payment-gateway' === $plugin_slug ) {
				update_option( 'fkwcs_wp_stripe', '918c16161738683760fbe034393e008a', false );
			}
			wp_send_json_success( 'Plugin installed and activated successfully!' );
		}
	}

	public function is_plugin_installed( $plugin_file ) {
		$installed_plugins = get_plugins();

		foreach ( $installed_plugins as $installed_plugin_file => $plugin_data ) {
			if ( $installed_plugin_file === $plugin_file ) {
				return true;
			}
		}

		return false;
	}
}

if ( class_exists( 'XLWCTY_XL_Support' ) ) {
	XLWCTY_Core::register( 'xl_support', 'XLWCTY_XL_Support' );
}
