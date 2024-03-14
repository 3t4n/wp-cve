<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPCleverWoosg' ) ) {
	class WPCleverWoosg {
		public static $settings = [];
		public static $localization = [];
		protected static $image_size = 'woocommerce_thumbnail';
		protected static $types = [
			'bundle',
			'woosb',
			'composite',
			'wooco',
			'grouped',
			'woosg',
			'external',
			'variable',
			'variation'
		];

		protected static $instance = null;

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function __construct() {
			// settings
			self::$settings = (array) get_option( 'woosg_settings', [] );
			// localization
			self::$localization = (array) get_option( 'woosg_localization', [] );

			// Init
			add_action( 'init', [ $this, 'init' ] );

			// Settings
			add_action( 'admin_init', [ $this, 'register_settings' ] );
			add_action( 'admin_menu', [ $this, 'admin_menu' ] );

			// Enqueue frontend scripts
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 99 );

			// Enqueue backend scripts
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

			// Add image to variation
			add_filter( 'woocommerce_available_variation', [ $this, 'available_variation' ], 10, 3 );

			// Backend AJAX
			add_action( 'wp_ajax_woosg_update_search_settings', [ $this, 'ajax_update_search_settings' ] );
			add_action( 'wp_ajax_woosg_get_search_results', [ $this, 'ajax_get_search_results' ] );

			// Add to selector
			add_filter( 'product_type_selector', [ $this, 'product_type_selector' ] );

			// Product data tabs
			add_filter( 'woocommerce_product_data_tabs', [ $this, 'product_data_tabs' ] );

			// Product tab
			if ( WPCleverWoosg_Helper()::get_setting( 'position', 'above' ) === 'tab' ) {
				add_filter( 'woocommerce_product_tabs', [ $this, 'product_tabs' ] );
			}

			// Product data panels
			add_action( 'woocommerce_product_data_panels', [ $this, 'product_data_panels' ] );
			add_action( 'woocommerce_process_product_meta_woosg', [ $this, 'process_product_meta_woosg' ] );

			// Price html
			add_filter( 'woocommerce_get_price_html', [ $this, 'get_price_html' ], 99, 2 );

			// Product class
			add_filter( 'woocommerce_post_class', [ $this, 'product_class' ], 99, 2 );

			// Price class
			add_filter( 'woocommerce_product_price_class', [ $this, 'product_price_class' ] );

			// Add to cart form & button
			add_action( 'woocommerce_woosg_add_to_cart', [ $this, 'add_to_cart_form' ] );
			add_action( 'woocommerce_before_add_to_cart_button', [ $this, 'add_to_cart_button' ] );

			// Add to cart
			add_filter( 'woocommerce_add_to_cart_validation', [ $this, 'add_to_cart_validation' ], 10, 2 );
			add_filter( 'woocommerce_add_cart_item_data', [ $this, 'add_cart_item_data' ], 10, 2 );
			add_action( 'woocommerce_add_to_cart', [ $this, 'add_to_cart' ], 10, 6 );
			add_filter( 'woocommerce_get_cart_item_from_session', [
				$this,
				'get_cart_item_from_session'
			], 10, 2 );

			// Cart contents instead of woocommerce_before_calculate_totals, prevent price error on mini-cart
			add_filter( 'woocommerce_get_cart_contents', [ $this, 'get_cart_contents' ] );

			// Admin
			add_filter( 'display_post_states', [ $this, 'display_post_states' ], 10, 2 );

			// Add settings link
			add_filter( 'plugin_action_links', [ $this, 'action_links' ], 10, 2 );
			add_filter( 'plugin_row_meta', [ $this, 'row_meta' ], 10, 2 );

			// Search filters
			if ( WPCleverWoosg_Helper()::get_setting( 'search_sku', 'no' ) === 'yes' ) {
				add_filter( 'pre_get_posts', [ $this, 'search_sku' ], 99 );
			}

			if ( WPCleverWoosg_Helper()::get_setting( 'search_exact', 'no' ) === 'yes' ) {
				add_action( 'pre_get_posts', [ $this, 'search_exact' ], 99 );
			}

			if ( WPCleverWoosg_Helper()::get_setting( 'search_sentence', 'no' ) === 'yes' ) {
				add_action( 'pre_get_posts', [ $this, 'search_sentence' ], 99 );
			}

			// WPC Smart Messages
			add_filter( 'wpcsm_locations', [ $this, 'wpcsm_locations' ] );

			// WPML
			if ( function_exists( 'wpml_loaded' ) ) {
				add_filter( 'woosg_item_id', [ $this, 'wpml_item_id' ], 99 );
			}
		}

		function init() {
			self::$image_size = apply_filters( 'woosg_image_size', self::$image_size );

			// shortcode
			add_shortcode( 'woosg', [ $this, 'shortcode' ] );
			add_shortcode( 'woosg_form', [ $this, 'shortcode_form' ] );
		}

		function available_variation( $data, $variable, $variation ) {
			if ( $image_id = $variation->get_image_id() ) {
				$data['woosg_image'] = wp_get_attachment_image( $image_id, self::$image_size );
			}

			return $data;
		}

		function register_settings() {
			// settings
			register_setting( 'woosg_settings', 'woosg_settings' );

			// localization
			register_setting( 'woosg_localization', 'woosg_localization' );
		}

		function admin_menu() {
			add_submenu_page( 'wpclever', esc_html__( 'WPC Grouped Product', 'wpc-grouped-product' ), esc_html__( 'Grouped Product', 'wpc-grouped-product' ), 'manage_options', 'wpclever-woosg', [
				$this,
				'admin_menu_content'
			] );
		}

		function admin_menu_content() {
			add_thickbox();
			$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'settings';
			?>
            <div class="wpclever_settings_page wrap">
                <h1 class="wpclever_settings_page_title"><?php echo esc_html__( 'WPC Grouped Product', 'wpc-grouped-product' ) . ' ' . esc_html( WOOSG_VERSION ) . ' ' . ( defined( 'WOOSG_PREMIUM' ) ? '<span class="premium" style="display: none">' . esc_html__( 'Premium', 'wpc-grouped-product' ) . '</span>' : '' ); ?></h1>
                <div class="wpclever_settings_page_desc about-text">
                    <p>
                        Thank you for using our plugin! If you are satisfied, please reward it a full five-star
                        <span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span> rating. <br/>
                        <a href="<?php echo esc_url( WOOSG_REVIEWS ); ?>" target="_blank"><?php esc_html_e( 'Reviews', 'wpc-grouped-product' ); ?></a> |
                        <a href="<?php echo esc_url( WOOSG_CHANGELOG ); ?>" target="_blank"><?php esc_html_e( 'Changelog', 'wpc-grouped-product' ); ?></a> |
                        <a href="<?php echo esc_url( WOOSG_DISCUSSION ); ?>" target="_blank"><?php esc_html_e( 'Discussion', 'wpc-grouped-product' ); ?></a>
                    </p>
                </div>
				<?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) { ?>
                    <div class="notice notice-success is-dismissible">
                        <p><?php esc_html_e( 'Settings updated.', 'wpc-grouped-product' ); ?></p>
                    </div>
				<?php } ?>
                <div class="wpclever_settings_page_nav">
                    <h2 class="nav-tab-wrapper">
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-woosg&tab=how' ) ); ?>" class="<?php echo esc_attr( $active_tab === 'how' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
							<?php esc_html_e( 'How to use?', 'wpc-grouped-product' ); ?>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-woosg&tab=settings' ) ); ?>" class="<?php echo esc_attr( $active_tab === 'settings' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
							<?php esc_html_e( 'Settings', 'wpc-grouped-product' ); ?>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-woosg&tab=localization' ) ); ?>" class="<?php echo esc_attr( $active_tab === 'localization' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
							<?php esc_html_e( 'Localization', 'wpc-grouped-product' ); ?>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-woosg&tab=tools' ) ); ?>" class="<?php echo esc_attr( $active_tab === 'tools' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>">
							<?php esc_html_e( 'Tools', 'wpc-grouped-product' ); ?>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-woosg&tab=premium' ) ); ?>" class="<?php echo esc_attr( $active_tab === 'premium' ? 'nav-tab nav-tab-active' : 'nav-tab' ); ?>" style="color: #c9356e">
							<?php esc_html_e( 'Premium Version', 'wpc-grouped-product' ); ?>
                        </a>
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-kit' ) ); ?>" class="nav-tab">
							<?php esc_html_e( 'Essential Kit', 'wpc-grouped-product' ); ?>
                        </a>
                    </h2>
                </div>
                <div class="wpclever_settings_page_content">
					<?php if ( $active_tab === 'how' ) { ?>
                        <div class="wpclever_settings_page_content_text">
                            <p>
								<?php esc_html_e( 'When creating the product, please choose product data is "Smart grouped" then you can see the search field to start search and add products.', 'wpc-grouped-product' ); ?>
                            </p>
                            <p>
                                <img src="<?php echo esc_url( WOOSG_URI . 'assets/images/how-01.jpg' ); ?>"/>
                            </p>
                        </div>
					<?php } elseif ( $active_tab === 'settings' ) {
						$price_format        = WPCleverWoosg_Helper()::get_setting( 'price_format', 'from' );
						$archive_purchasable = WPCleverWoosg_Helper()::get_setting( 'archive_purchasable', 'no' );
						$position            = WPCleverWoosg_Helper()::get_setting( 'position', 'above' );
						$layout              = WPCleverWoosg_Helper()::get_setting( 'layout', 'list' );
						$variations_selector = WPCleverWoosg_Helper()::get_setting( 'variations_selector', 'default' );
						$show_thumb          = WPCleverWoosg_Helper()::get_setting( 'show_thumb', 'yes' );
						$show_description    = WPCleverWoosg_Helper()::get_setting( 'show_description', 'no' );
						$show_price          = WPCleverWoosg_Helper()::get_setting( 'show_price', 'yes' );
						$selector            = WPCleverWoosg_Helper()::get_setting( 'selector', 'quantity' );
						$show_plus_minus     = WPCleverWoosg_Helper()::get_setting( 'show_plus_minus', 'no' );
						$show_atc            = WPCleverWoosg_Helper()::get_setting( 'show_atc', 'all' );
						$link                = WPCleverWoosg_Helper()::get_setting( 'link', 'yes' );
						$change_image        = WPCleverWoosg_Helper()::get_setting( 'change_image', 'yes' );
						$change_price        = WPCleverWoosg_Helper()::get_setting( 'change_price', 'yes' );
						$including_main      = WPCleverWoosg_Helper()::get_setting( 'including_main', 'no' );
						$main_price          = WPCleverWoosg_Helper()::get_setting( 'main_price', 'zero' );
						?>
                        <form method="post" action="options.php">
                            <table class="form-table">
                                <tr class="heading">
                                    <th colspan="2">
										<?php esc_html_e( 'General', 'wpc-grouped-product' ); ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Price format', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <select name="woosg_settings[price_format]">
                                            <option value="normal" <?php selected( $price_format, 'normal' ); ?>><?php esc_html_e( 'Normal price', 'wpc-grouped-product' ); ?></option>
                                            <option value="from" <?php selected( $price_format, 'from' ); ?>><?php esc_html_e( 'From price', 'wpc-grouped-product' ); ?></option>
                                            <option value="auto" <?php selected( $price_format, 'auto' ); ?>><?php esc_html_e( 'Auto calculated price', 'wpc-grouped-product' ); ?></option>
                                            <option value="none" <?php selected( $price_format, 'none' ); ?>><?php esc_html_e( 'None', 'wpc-grouped-product' ); ?></option>
                                        </select>
                                        <p class="description"><?php esc_html_e( 'Choose the price format for grouped product on the shop/archive page. Using "Auto calculated price" can cause your site slow down.', 'wpc-grouped-product' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Purchasable on archive', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <select name="woosg_settings[archive_purchasable]">
                                            <option value="yes" <?php selected( $archive_purchasable, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-grouped-product' ); ?></option>
                                            <option value="no" <?php selected( $archive_purchasable, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-grouped-product' ); ?></option>
                                        </select>
                                        <p class="description"><?php esc_html_e( 'Allow buyers to add a group to the cart on the products archive page. All default sub-products will be added to the cart.', 'wpc-grouped-product' ); ?></p>
                                    </td>
                                </tr>
                                <tr class="heading">
                                    <th colspan="2">
										<?php esc_html_e( 'Grouped products', 'wpc-grouped-product' ); ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Position', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <select name="woosg_settings[position]">
                                            <option value="above" <?php selected( $position, 'above' ); ?>><?php esc_html_e( 'Above add to cart button', 'wpc-grouped-product' ); ?></option>
                                            <option value="below" <?php selected( $position, 'below' ); ?>><?php esc_html_e( 'Under add to cart button', 'wpc-grouped-product' ); ?></option>
                                            <option value="tab" <?php selected( $position, 'tab' ); ?>><?php esc_html_e( 'In a new tab', 'wpc-grouped-product' ); ?></option>
                                            <option value="no" <?php selected( $position, 'no' ); ?>><?php esc_html_e( 'No (hide it)', 'wpc-grouped-product' ); ?></option>
                                        </select>
                                        <p class="description"><?php esc_html_e( 'Choose the position to show the grouped product list. You also can use the shortcode [woosg] to show the list where you want.', 'wpc-grouped-product' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Layout', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <select name="woosg_settings[layout]">
                                            <option value="list" <?php selected( $layout, 'list' ); ?>><?php esc_html_e( 'List', 'wpc-grouped-product' ); ?></option>
                                            <option value="grid-2" <?php selected( $layout, 'grid-2' ); ?>><?php esc_html_e( 'Grid - 2 columns', 'wpc-grouped-product' ); ?></option>
                                            <option value="grid-3" <?php selected( $layout, 'grid-3' ); ?>><?php esc_html_e( 'Grid - 3 columns', 'wpc-grouped-product' ); ?></option>
                                            <option value="grid-4" <?php selected( $layout, 'grid-4' ); ?>><?php esc_html_e( 'Grid - 4 columns', 'wpc-grouped-product' ); ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Variations selector', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <select name="woosg_settings[variations_selector]">
                                            <option value="default" <?php selected( $variations_selector, 'default' ); ?>><?php esc_html_e( 'Default', 'wpc-grouped-product' ); ?></option>
                                            <option value="woovr" <?php selected( $variations_selector, 'woovr' ); ?>><?php esc_html_e( 'Use WPC Variations Radio Buttons', 'wpc-grouped-product' ); ?></option>
                                        </select>
                                        <p class="description">If you choose "Use WPC Variations Radio Buttons", please install
                                            <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=wpc-variations-radio-buttons&TB_iframe=true&width=800&height=550' ) ); ?>" class="thickbox" title="WPC Variations Radio Buttons">WPC Variations Radio Buttons</a> to make it work.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Show thumbnail', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <select name="woosg_settings[show_thumb]">
                                            <option value="yes" <?php selected( $show_thumb, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-grouped-product' ); ?></option>
                                            <option value="no" <?php selected( $show_thumb, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-grouped-product' ); ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Show short description', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <select name="woosg_settings[show_description]">
                                            <option value="yes" <?php selected( $show_description, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-grouped-product' ); ?></option>
                                            <option value="no" <?php selected( $show_description, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-grouped-product' ); ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Show price', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <select name="woosg_settings[show_price]">
                                            <option value="yes" <?php selected( $show_price, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-grouped-product' ); ?></option>
                                            <option value="no" <?php selected( $show_price, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-grouped-product' ); ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Product selector', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <select name="woosg_settings[selector]">
                                            <option value="quantity" <?php selected( $selector, 'quantity' ); ?>><?php esc_html_e( 'Quantity', 'wpc-grouped-product' ); ?></option>
                                            <option value="checkbox" <?php selected( $selector, 'checkbox' ); ?>><?php esc_html_e( 'Checkbox', 'wpc-grouped-product' ); ?></option>
                                        </select>
                                        <span class="description"><?php esc_html_e( 'You still can choose the selector for each grouped product in the product settings.', 'wpc-grouped-product' ); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Show plus/minus button', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <select name="woosg_settings[show_plus_minus]">
                                            <option value="yes" <?php selected( $show_plus_minus, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-grouped-product' ); ?></option>
                                            <option value="no" <?php selected( $show_plus_minus, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-grouped-product' ); ?></option>
                                        </select>
                                        <span class="description"><?php esc_html_e( 'Show the plus/minus button to increase/decrease the quantity.', 'wpc-grouped-product' ); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
										<?php esc_html_e( 'Show add to cart button', 'wpc-grouped-product' ); ?>
                                    </th>
                                    <td>
                                        <select name="woosg_settings[show_atc]">
                                            <option value="each" <?php selected( $show_atc, 'each' ); ?>><?php esc_html_e( 'Yes, for each subproduct', 'wpc-grouped-product' ); ?></option>
                                            <option value="all" <?php selected( $show_atc, 'all' ); ?>><?php esc_html_e( 'Yes, for the whole group', 'wpc-grouped-product' ); ?></option>
                                            <option value="no" <?php selected( $show_atc, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-grouped-product' ); ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Link to individual product', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <select name="woosg_settings[link]">
                                            <option value="yes" <?php selected( $link, 'yes' ); ?>><?php esc_html_e( 'Yes, open in the same tab', 'wpc-grouped-product' ); ?></option>
                                            <option value="yes_blank" <?php selected( $link, 'yes_blank' ); ?>><?php esc_html_e( 'Yes, open in the new tab', 'wpc-grouped-product' ); ?></option>
                                            <option value="yes_popup" <?php selected( $link, 'yes_popup' ); ?>><?php esc_html_e( 'Yes, open quick view popup', 'wpc-grouped-product' ); ?></option>
                                            <option value="no" <?php selected( $link, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-grouped-product' ); ?></option>
                                        </select>
                                        <p class="description">If you choose "Open quick view popup", please install
                                            <a href="<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin=woo-smart-quick-view&TB_iframe=true&width=800&height=550' ) ); ?>" class="thickbox" title="WPC Smart Quick View">WPC Smart Quick View</a> to make it work.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Change image', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <select name="woosg_settings[change_image]">
                                            <option value="yes" <?php selected( $change_image, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-grouped-product' ); ?></option>
                                            <option value="no" <?php selected( $change_image, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-grouped-product' ); ?></option>
                                        </select>
                                        <span class="description"><?php esc_html_e( 'Change the main product image when choosing the variation of grouped product.', 'wpc-grouped-product' ); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Change price', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <select name="woosg_settings[change_price]" class="woosg_change_price">
                                            <option value="yes" <?php selected( $change_price, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-grouped-product' ); ?></option>
                                            <option value="yes_custom" <?php selected( $change_price, 'yes_custom' ); ?>><?php esc_html_e( 'Yes, custom selector', 'wpc-grouped-product' ); ?></option>
                                            <option value="no" <?php selected( $change_price, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-grouped-product' ); ?></option>
                                        </select>
                                        <input type="text" name="woosg_settings[change_price_custom]" value="<?php echo WPCleverWoosg_Helper()::get_setting( 'change_price_custom', '.summary > .price' ); ?>" placeholder=".summary > .price" class="woosg_change_price_custom"/>
                                        <p class="description"><?php esc_html_e( 'Change the main product price when choosing the variation of grouped product. It uses JavaScript to change product price so it is very dependent on theme’s HTML. If it cannot find and update the product price, please contact us and we can help you adjust the JS file.', 'wpc-grouped-product' ); ?></p>
                                    </td>
                                </tr>
                                <tr class="heading">
                                    <th colspan="2">
										<?php esc_html_e( 'Cart & Checkout', 'wpc-grouped-product' ); ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Include main product', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <select name="woosg_settings[including_main]">
                                            <option value="yes" <?php selected( $including_main, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-grouped-product' ); ?></option>
                                            <option value="no" <?php selected( $including_main, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-grouped-product' ); ?></option>
                                        </select>
                                        <p class="description"><?php esc_html_e( 'Include main product on the cart. Helpful when you need to add some extra options for the main product, e.g WPC Frequently Bought Together, WPC Force Sells.', 'wpc-grouped-product' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Main product price', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <select name="woosg_settings[main_price]">
                                            <option value="zero" <?php selected( $main_price, 'zero' ); ?>><?php esc_html_e( 'Zero price', 'wpc-grouped-product' ); ?></option>
                                            <option value="price" <?php selected( $main_price, 'price' ); ?>><?php esc_html_e( 'Normal price', 'wpc-grouped-product' ); ?></option>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="heading">
                                    <th colspan="2">
										<?php esc_html_e( 'Search', 'wpc-grouped-product' ); ?>
                                    </th>
                                </tr>
								<?php self::search_settings(); ?>
                                <tr class="heading">
                                    <th colspan="2"><?php esc_html_e( 'Suggestion', 'wpc-grouped-product' ); ?></th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        To display custom engaging real-time messages on any wished positions, please install
                                        <a href="https://wordpress.org/plugins/wpc-smart-messages/" target="_blank">WPC Smart Messages</a> plugin. It's free!
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        Wanna save your precious time working on variations? Try our brand-new free plugin
                                        <a href="https://wordpress.org/plugins/wpc-variation-bulk-editor/" target="_blank">WPC Variation Bulk Editor</a> and
                                        <a href="https://wordpress.org/plugins/wpc-variation-duplicator/" target="_blank">WPC Variation Duplicator</a>.
                                    </td>
                                </tr>
                                <tr class="submit">
                                    <th colspan="2">
										<?php settings_fields( 'woosg_settings' ); ?><?php submit_button(); ?>
                                    </th>
                                </tr>
                            </table>
                        </form>
					<?php } elseif ( $active_tab === 'localization' ) {
						wp_enqueue_editor();
						?>
                        <form method="post" action="options.php">
                            <table class="form-table">
                                <tr class="heading">
                                    <th scope="row"><?php esc_html_e( 'General', 'wpc-grouped-product' ); ?></th>
                                    <td>
										<?php esc_html_e( 'Leave blank to use the default text and its equivalent translation in multiple languages.', 'wpc-grouped-product' ); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'From', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <input type="text" name="woosg_localization[from]" class="regular-text" value="<?php echo esc_attr( WPCleverWoosg_Helper()::localization( 'from' ) ); ?>" placeholder="<?php esc_attr_e( 'From', 'wpc-grouped-product' ); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Total', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <input type="text" name="woosg_localization[total]" class="regular-text" value="<?php echo esc_attr( WPCleverWoosg_Helper()::localization( 'total' ) ); ?>" placeholder="<?php esc_attr_e( 'Total:', 'wpc-grouped-product' ); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Default above text', 'wpc-grouped-product' ); ?></th>
                                    <td>
										<?php wp_editor( WPCleverWoosg_Helper()::localization( 'above_text' ), 'woosg_localization_above_text', [
											'textarea_name' => 'woosg_localization[above_text]',
											'textarea_rows' => 10
										] ); ?>
                                        <span class="description"><?php esc_html_e( 'The default text above products list. You can overwrite it in product settings.', 'wpc-grouped-product' ); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Default under text', 'wpc-grouped-product' ); ?></th>
                                    <td>
										<?php wp_editor( WPCleverWoosg_Helper()::localization( 'under_text' ), 'woosg_localization_under_text', [
											'textarea_name' => 'woosg_localization[under_text]',
											'textarea_rows' => 10
										] ); ?>
                                        <span class="description"><?php esc_html_e( 'The default text under products list. You can overwrite it in product settings.', 'wpc-grouped-product' ); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Tab name', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <input type="text" name="woosg_localization[tab]" class="regular-text" value="<?php echo esc_attr( WPCleverWoosg_Helper()::localization( 'tab' ) ); ?>" placeholder="<?php esc_attr_e( 'Grouped products', 'wpc-grouped-product' ); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Choose an attribute', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <input type="text" class="regular-text" name="woosg_localization[choose]" value="<?php echo esc_attr( WPCleverWoosg_Helper()::localization( 'choose' ) ); ?>" placeholder="<?php esc_attr_e( 'Choose %s', 'wpc-grouped-product' ); ?>"/>
                                        <span class="description"><?php esc_html_e( 'Use %s to show the attribute name.', 'wpc-grouped-product' ); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Clear', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <input type="text" class="regular-text" name="woosg_localization[clear]" value="<?php echo esc_attr( WPCleverWoosg_Helper()::localization( 'clear' ) ); ?>" placeholder="<?php esc_attr_e( 'Clear', 'wpc-grouped-product' ); ?>"/>
                                    </td>
                                </tr>
                                <tr class="heading">
                                    <th colspan="2">
										<?php esc_html_e( '"Add to cart" button labels', 'wpc-grouped-product' ); ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Shop/archive page', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <div style="margin-bottom: 5px">
                                            <input type="text" class="regular-text" name="woosg_localization[button_add]" value="<?php echo esc_attr( WPCleverWoosg_Helper()::localization( 'button_add' ) ); ?>" placeholder="<?php esc_attr_e( 'Add to cart', 'wpc-grouped-product' ); ?>"/>
                                        </div>
                                        <div style="margin-bottom: 5px">
                                            <input type="text" class="regular-text" name="woosg_localization[button_select]" value="<?php echo esc_attr( WPCleverWoosg_Helper()::localization( 'button_select' ) ); ?>" placeholder="<?php esc_attr_e( 'Select options', 'wpc-grouped-product' ); ?>"/>
                                            <span class="description"><?php esc_html_e( 'For purchasable grouped.', 'wpc-grouped-product' ); ?></span>
                                        </div>
                                        <div>
                                            <input type="text" class="regular-text" name="woosg_localization[button_read]" value="<?php echo esc_attr( WPCleverWoosg_Helper()::localization( 'button_read' ) ); ?>" placeholder="<?php esc_attr_e( 'Read more', 'wpc-grouped-product' ); ?>"/>
                                            <span class="description"><?php esc_html_e( 'For un-purchasable grouped.', 'wpc-grouped-product' ); ?></span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Single product page', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <input type="text" class="regular-text" name="woosg_localization[button_single]" value="<?php echo esc_attr( WPCleverWoosg_Helper()::localization( 'button_single' ) ); ?>" placeholder="<?php esc_attr_e( 'Add to cart', 'wpc-grouped-product' ); ?>"/>
                                    </td>
                                </tr>
                                <tr class="heading">
                                    <th colspan="2">
										<?php esc_html_e( 'Alert', 'wpc-grouped-product' ); ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Require selection', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <input type="text" class="large-text" name="woosg_localization[alert_selection]" value="<?php echo esc_attr( WPCleverWoosg_Helper()::localization( 'alert_selection' ) ); ?>" placeholder="<?php esc_attr_e( 'Please select a purchasable variation for [name] before adding this grouped product to the cart.', 'wpc-grouped-product' ); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e( 'Enforce a selection', 'wpc-grouped-product' ); ?></th>
                                    <td>
                                        <input type="text" class="large-text" name="woosg_localization[alert_empty]" value="<?php echo esc_attr( WPCleverWoosg_Helper()::localization( 'alert_empty' ) ); ?>" placeholder="<?php esc_attr_e( 'Please choose at least one of the listed products before adding this grouped product to the cart.', 'wpc-grouped-product' ); ?>"/>
                                    </td>
                                </tr>
                                <tr class="submit">
                                    <th colspan="2">
										<?php settings_fields( 'woosg_localization' ); ?><?php submit_button(); ?>
                                    </th>
                                </tr>
                            </table>
                        </form>
					<?php } elseif ( $active_tab === 'tools' ) { ?>
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php esc_html_e( 'Convert', 'wpc-grouped-product' ); ?></th>
                                <td>
									<?php
									esc_html_e( 'Press the Convert button to convert all native grouped products to smart grouped.', 'wpc-grouped-product' );

									echo '<p>';

									$num   = absint( isset( $_GET['num'] ) ? $_GET['num'] : 50 );
									$paged = absint( isset( $_GET['paged'] ) ? $_GET['paged'] : 0 );

									if ( isset( $_GET['act'] ) && ( $_GET['act'] === 'convert' ) ) {
										$args = [
											'type'  => [ 'grouped' ],
											'limit' => $num,
											'page'  => $paged,
										];

										$products = wc_get_products( $args );

									if ( ! empty( $products ) ) {
										foreach ( $products as $product_obj ) {
											$product_id = $product_obj->get_id();
											$children   = $product_obj->get_children();
											$ids        = [];

											if ( is_array( $children ) && ! empty( $children ) ) {
												foreach ( $children as $child ) {
													$key         = WPCleverWoosg_Helper()::generate_key();
													$ids[ $key ] = [
														'id'  => apply_filters( 'woosg_convert_item_id', $child ),
														'qty' => apply_filters( 'woosg_convert_item_qty', 1 )
													];
												}

												$ids = (array) apply_filters( 'woosg_convert_items', $ids );

												if ( ! empty( $ids ) ) {
													update_post_meta( $product_id, 'woosg_ids', WPCleverWoosg_Helper()::sanitize_array( $ids ) );
													wp_set_object_terms( $product_id, 'woosg', 'product_type' );
												}
											}
										}
										?>
                                        <span style="color: #2271b1; font-weight: 700"><?php esc_html_e( 'Converting...', 'wpc-grouped-product' ); ?></span>
                                        <p class="description"><?php esc_html_e( 'Please wait until it has finished!', 'wpc-grouped-product' ); ?></p>
                                        <script type="text/javascript">
                                          (function($) {
                                            $(function() {
                                              setTimeout(function() {
                                                window.location.href = '<?php echo esc_url( admin_url( 'admin.php?page=wpclever-woosg&tab=tools&act=convert&num=' . $num . '&paged=' . ( $paged + 1 ) ) ); ?>';
                                              }, 1000);
                                            });
                                          })(jQuery);
                                        </script><?php } else { ?>
                                        <span style="color: #2271b1; font-weight: 700"><?php esc_html_e( 'Finished!', 'wpc-grouped-product' ); ?></span><?php }
									} else { ?>
                                        <a class="button btn" href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-woosg&tab=tools&act=convert' ) ); ?>" onclick="return confirm('Are you sure? This action cannot be undone.');"><?php esc_html_e( 'Convert', 'wpc-grouped-product' ); ?></a>
									<?php }

									echo '</p>'; ?>
                                </td>
                            </tr>
                        </table>
					<?php } elseif ( $active_tab === 'premium' ) { ?>
                        <div class="wpclever_settings_page_content_text">
                            <p>
                                Get the Premium Version just $29!
                                <a href="https://wpclever.net/downloads/grouped-product?utm_source=pro&utm_medium=woosg&utm_campaign=wporg" target="_blank">https://wpclever.net/downloads/grouped-product</a>
                            </p>
                            <p><strong>Extra features for Premium Version:</strong></p>
                            <ul style="margin-bottom: 0">
                                <li>- Add a variable product or a specific variation to the grouped.</li>
                                <li>- Insert heading/paragraph into products list.</li>
                                <li>- Get the lifetime update & premium support.</li>
                            </ul>
                        </div>
					<?php } ?>
                </div>
            </div>
			<?php
		}

		function search_settings() {
			$search_sku      = WPCleverWoosg_Helper()::get_setting( 'search_sku', 'no' );
			$search_id       = WPCleverWoosg_Helper()::get_setting( 'search_id', 'no' );
			$search_exact    = WPCleverWoosg_Helper()::get_setting( 'search_exact', 'no' );
			$search_sentence = WPCleverWoosg_Helper()::get_setting( 'search_sentence', 'no' );
			$search_same     = WPCleverWoosg_Helper()::get_setting( 'search_same', 'no' );
			?>
            <tr>
                <th><?php esc_html_e( 'Search limit', 'wpc-grouped-product' ); ?></th>
                <td>
                    <input name="woosg_settings[search_limit]" type="number" min="1" max="500" class="woosg_search_limit" value="<?php echo esc_attr( WPCleverWoosg_Helper()::get_setting( 'search_limit', '5' ) ); ?>"/>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Search by SKU', 'wpc-grouped-product' ); ?></th>
                <td>
                    <select name="woosg_settings[search_sku]" class="woosg_search_sku">
                        <option value="yes" <?php selected( $search_sku, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-grouped-product' ); ?></option>
                        <option value="no" <?php selected( $search_sku, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-grouped-product' ); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Search by ID', 'wpc-grouped-product' ); ?></th>
                <td>
                    <select name="woosg_settings[search_id]" class="woosg_search_id">
                        <option value="yes" <?php selected( $search_id, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-grouped-product' ); ?></option>
                        <option value="no" <?php selected( $search_id, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-grouped-product' ); ?></option>
                    </select>
                    <span class="description"><?php esc_html_e( 'Search by ID when entering the numeric only.', 'wpc-grouped-product' ); ?></span>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Search exact', 'wpc-grouped-product' ); ?></th>
                <td>
                    <select name="woosg_settings[search_exact]" class="woosg_search_exact">
                        <option value="yes" <?php selected( $search_exact, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-grouped-product' ); ?></option>
                        <option value="no" <?php selected( $search_exact, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-grouped-product' ); ?></option>
                    </select>
                    <span class="description"><?php esc_html_e( 'Match whole product title or content?', 'wpc-grouped-product' ); ?></span>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Search sentence', 'wpc-grouped-product' ); ?></th>
                <td>
                    <select name="woosg_settings[search_sentence]" class="woosg_search_sentence">
                        <option value="yes" <?php selected( $search_sentence, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-grouped-product' ); ?></option>
                        <option value="no" <?php selected( $search_sentence, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-grouped-product' ); ?></option>
                    </select>
                    <span class="description"><?php esc_html_e( 'Do a phrase search?', 'wpc-grouped-product' ); ?></span>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Accept same products', 'wpc-grouped-product' ); ?></th>
                <td>
                    <select name="woosg_settings[search_same]" class="woosg_search_same">
                        <option value="yes" <?php selected( $search_same, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-grouped-product' ); ?></option>
                        <option value="no" <?php selected( $search_same, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-grouped-product' ); ?></option>
                    </select>
                    <span class="description"><?php esc_html_e( 'If yes, a product can be added many times.', 'wpc-grouped-product' ); ?></span>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e( 'Product types', 'wpc-grouped-product' ); ?></th>
                <td>
					<?php
					$search_types  = WPCleverWoosg_Helper()::get_setting( 'search_types', [ 'all' ] );
					$product_types = wc_get_product_types();
					$product_types = array_merge( [ 'all' => esc_html__( 'All', 'wpc-grouped-product' ) ], $product_types );

					$key_pos = array_search( 'variable', array_keys( $product_types ) );

					if ( $key_pos !== false ) {
						$key_pos ++;
						$second_array  = array_splice( $product_types, $key_pos );
						$product_types = array_merge( $product_types, [ 'variation' => esc_html__( ' → Variation', 'wpc-grouped-product' ) ], $second_array );
					}

					echo '<select name="woosg_settings[search_types][]" multiple style="width: 200px; height: 150px;" class="woosg_search_types">';

					foreach ( $product_types as $key => $name ) {
						echo '<option value="' . esc_attr( $key ) . '" ' . ( in_array( $key, $search_types, true ) ? 'selected' : '' ) . '>' . esc_html( $name ) . '</option>';
					}

					echo '</select>';
					?>
                </td>
            </tr>
			<?php
		}

		function enqueue_scripts() {
			wp_enqueue_style( 'woosg-frontend', WOOSG_URI . 'assets/css/frontend.css', [], WOOSG_VERSION );
			wp_enqueue_script( 'woosg-frontend', WOOSG_URI . 'assets/js/frontend.js', [ 'jquery' ], WOOSG_VERSION, true );
			wp_localize_script( 'woosg-frontend', 'woosg_vars', apply_filters( 'woosg_vars', [
					'change_image'             => WPCleverWoosg_Helper()::get_setting( 'change_image', 'yes' ),
					'change_price'             => WPCleverWoosg_Helper()::get_setting( 'change_price', 'yes' ),
					'price_selector'           => WPCleverWoosg_Helper()::get_setting( 'change_price_custom', '' ),
					'price_format'             => get_woocommerce_price_format(),
					'price_decimals'           => wc_get_price_decimals(),
					'price_thousand_separator' => wc_get_price_thousand_separator(),
					'price_decimal_separator'  => wc_get_price_decimal_separator(),
					'currency_symbol'          => get_woocommerce_currency_symbol(),
					'trim_zeros'               => apply_filters( 'woosg_price_trim_zeros', apply_filters( 'woocommerce_price_trim_zeros', false ) ),
					'total_text'               => WPCleverWoosg_Helper()::localization( 'total', esc_html__( 'Total:', 'wpc-grouped-product' ) ),
					'add_to_cart'              => WPCleverWoosg_Helper()::localization( 'button_single', esc_html__( 'Add to cart', 'wpc-grouped-product' ) ),
					'select_options'           => WPCleverWoosg_Helper()::localization( 'button_select', esc_html__( 'Select options', 'wpc-grouped-product' ) ),
					'alert_selection'          => WPCleverWoosg_Helper()::localization( 'alert_selection', esc_html__( 'Please select a purchasable variation for [name] before adding this grouped product to the cart.', 'wpc-grouped-product' ) ),
					'alert_empty'              => WPCleverWoosg_Helper()::localization( 'alert_empty', esc_html__( 'Please choose at least one of the listed products before adding this grouped product to the cart.', 'wpc-grouped-product' ) )
				] )
			);
		}

		function admin_enqueue_scripts( $hook ) {
			if ( apply_filters( 'woosg_ignore_backend_scripts', false, $hook ) ) {
				return null;
			}

			wp_enqueue_style( 'hint', WOOSG_URI . 'assets/css/hint.css' );
			wp_enqueue_style( 'woosg-backend', WOOSG_URI . 'assets/css/backend.css', [], WOOSG_VERSION );
			wp_enqueue_script( 'woosg-backend', WOOSG_URI . 'assets/js/backend.js', [
				'jquery',
				'jquery-ui-dialog',
				'jquery-ui-sortable'
			], WOOSG_VERSION, true );
			wp_localize_script( 'woosg-backend', 'woosg_vars', [
					'nonce'                    => wp_create_nonce( 'woosg-security' ),
					'price_decimals'           => wc_get_price_decimals(),
					'price_thousand_separator' => wc_get_price_thousand_separator(),
					'price_decimal_separator'  => wc_get_price_decimal_separator()
				]
			);
		}

		function action_links( $links, $file ) {
			static $plugin;

			if ( ! isset( $plugin ) ) {
				$plugin = plugin_basename( WOOSG_FILE );
			}

			if ( $plugin === $file ) {
				$settings             = '<a href="' . esc_url( admin_url( 'admin.php?page=wpclever-woosg&tab=settings' ) ) . '">' . esc_html__( 'Settings', 'wpc-grouped-product' ) . '</a>';
				$links['wpc-premium'] = '<a href="' . esc_url( admin_url( 'admin.php?page=wpclever-woosg&tab=premium' ) ) . '">' . esc_html__( 'Premium Version', 'wpc-grouped-product' ) . '</a>';
				array_unshift( $links, $settings );
			}

			return (array) $links;
		}

		function row_meta( $links, $file ) {
			static $plugin;

			if ( ! isset( $plugin ) ) {
				$plugin = plugin_basename( WOOSG_FILE );
			}

			if ( $plugin === $file ) {
				$row_meta = [
					'support' => '<a href="' . esc_url( WOOSG_DISCUSSION ) . '" target="_blank">' . esc_html__( 'Community support', 'wpc-grouped-product' ) . '</a>',
				];

				return array_merge( $links, $row_meta );
			}

			return (array) $links;
		}

		function add_to_cart_validation( $passed, $product_id ) {
			if ( isset( $_REQUEST['order_again'] ) ) {
				return $passed;
			}

			$product = wc_get_product( $product_id );

			if ( $product && $product->is_type( 'woosg' ) ) {
				$items = $product->get_items();

				if ( isset( $_REQUEST['woosg_ids'] ) ) {
					$items = self::get_items( $_REQUEST['woosg_ids'], $product );
				}

				if ( empty( $items ) ) {
					wc_add_notice( esc_html__( 'You cannot add this grouped to the cart.', 'wpc-grouped-product' ), 'error' );

					return false;
				}

				// check sub-products
				$purchasable = false;

				foreach ( $items as $item ) {
					if ( isset( $item['id'] ) && $item_product = wc_get_product( $item['id'] ) ) {
						if ( $item_product->is_in_stock() && $item_product->is_purchasable() && ! in_array( $item_product->get_type(), self::$types, true ) ) {
							$purchasable = true;
							break;
						}
					}
				}

				if ( ! $purchasable ) {
					// hasn't any purchasable product
					wc_add_notice( esc_html__( 'You cannot add this grouped to the cart.', 'wpc-grouped-product' ), 'error' );

					return false;
				}
			}

			return $passed;
		}

		function add_cart_item_data( $cart_item_data, $product_id ) {
			$item_product = wc_get_product( $product_id );

			if ( $item_product && $item_product->is_type( 'woosg' ) && ( $ids = get_post_meta( $product_id, 'woosg_ids', true ) ) ) {
				// make sure that is grouped
				if ( isset( $_REQUEST['woosg_ids'] ) ) {
					$ids = $_REQUEST['woosg_ids'];
					unset( $_REQUEST['woosg_ids'] );
				}

				$ids = WPCleverWoosg_Helper()::clean_ids( $ids );

				if ( ! empty( $ids ) ) {
					$cart_item_data['woosg_ids'] = $ids;
				}
			}

			return $cart_item_data;
		}

		function add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
			if ( ! empty( $cart_item_data['woosg_ids'] ) ) {
				if ( $items = self::get_items( $cart_item_data['woosg_ids'], $product_id ) ) {
					foreach ( $items as $item ) {
						$item_id           = $item['id'];
						$item_qty          = $item['qty'];
						$item_variation    = $item['attrs'];
						$item_variation_id = 0;
						$item_product      = wc_get_product( $item_id );

						if ( ! $item_product || ( $item_qty <= 0 ) || ! $item_product->is_in_stock() || ! $item_product->is_purchasable() ) {
							continue;
						}

						if ( in_array( $item_product->get_type(), self::$types, true ) ) {
							continue;
						}

						if ( $item_product instanceof WC_Product_Variation ) {
							// ensure we don't add a variation to the cart directly by variation ID
							$item_variation_id = $item_id;
							$item_id           = $item_product->get_parent_id();

							if ( empty( $item_variation ) ) {
								$item_variation = $item_product->get_variation_attributes();
							}
						}

						// add to cart
						$item_data = apply_filters( 'woosg_cart_item_data', [], $item, $product_id );
						$item_key  = WC()->cart->add_to_cart( $item_id, $item_qty * $quantity, $item_variation_id, $item_variation, $item_data );

						if ( $item_key ) {
							WC()->cart->cart_contents[ $item_key ]['woosg_parent_id']   = $product_id;
							WC()->cart->cart_contents[ $item_key ]['woosg_parent_key']  = $cart_item_key;
							WC()->cart->cart_contents[ $cart_item_key ]['woosg_keys'][] = $item_key;
							do_action( 'woosg_add_to_cart_item', $item_key, $item, $cart_item_key, $product_id );
						}
					} // end foreach
				}

				// remove grouped
				$including_main = get_post_meta( $product_id, 'woosg_including_main', true );

				if ( ( ( ! $including_main || ( $including_main === 'default' ) ) && ( WPCleverWoosg_Helper()::get_setting( 'including_main', 'no' ) !== 'yes' ) ) || ( $including_main === 'no' ) ) {
					WC()->cart->remove_cart_item( $cart_item_key );
				}
			}
		}

		function get_cart_contents( $cart_contents ) {
			foreach ( $cart_contents as $cart_item_key => $cart_item ) {
				if ( ! empty( $cart_item['woosg_ids'] ) && ( WPCleverWoosg_Helper()::get_setting( 'main_price', 'zero' ) === 'zero' ) ) {
					$cart_item['data']->set_price( 0 );
				}

				if ( ! empty( $cart_item['woosg_keys'] ) ) {
					$has_key = false;

					foreach ( $cart_item['woosg_keys'] as $key ) {
						if ( isset( $cart_contents[ $key ] ) ) {
							$has_key = true;
						}
					}

					if ( ! $has_key ) {
						WC()->cart->remove_cart_item( $cart_item_key );
						unset( $cart_contents[ $cart_item_key ] );
					}
				}
			}

			return $cart_contents;
		}

		function get_cart_item_from_session( $cart_item, $item_session_values ) {
			if ( ! empty( $item_session_values['woosg_ids'] ) ) {
				$cart_item['woosg_ids'] = $item_session_values['woosg_ids'];
			}

			return $cart_item;
		}

		function ajax_update_search_settings() {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woosg-security' ) || ! current_user_can( 'manage_options' ) ) {
				die( 'Permissions check failed!' );
			}

			$settings                    = (array) get_option( 'woosg_settings', [] );
			$settings['search_limit']    = (int) sanitize_text_field( $_POST['limit'] );
			$settings['search_sku']      = sanitize_text_field( $_POST['sku'] );
			$settings['search_id']       = sanitize_text_field( $_POST['id'] );
			$settings['search_exact']    = sanitize_text_field( $_POST['exact'] );
			$settings['search_sentence'] = sanitize_text_field( $_POST['sentence'] );
			$settings['search_same']     = sanitize_text_field( $_POST['same'] );
			$settings['search_types']    = array_map( 'sanitize_text_field', (array) $_POST['types'] );

			update_option( 'woosg_settings', $settings );
			wp_die();
		}

		function ajax_get_search_results() {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'woosg-security' ) ) {
				die( 'Permissions check failed!' );
			}

			$types     = WPCleverWoosg_Helper()::get_setting( 'search_types', [ 'all' ] );
			$keyword   = isset( $_POST['keyword'] ) ? sanitize_text_field( wp_unslash( $_POST['keyword'] ) ) : '';
			$added_ids = explode( ',', isset( $_POST['ids'] ) ? WPCleverWoosg_Helper()::clean_ids( $_POST['ids'] ) : '' );

			if ( ( WPCleverWoosg_Helper()::get_setting( 'search_id', 'no' ) === 'yes' ) && is_numeric( $keyword ) ) {
				// search by id
				$query_args = [
					'p'         => absint( $keyword ),
					'post_type' => 'product'
				];
			} else {
				$query_args = [
					'is_woosg'       => true,
					'post_type'      => 'product',
					'post_status'    => [ 'publish', 'private' ],
					's'              => $keyword,
					'posts_per_page' => WPCleverWoosg_Helper()::get_setting( 'search_limit', '5' )
				];

				if ( ! empty( $types ) && ! in_array( 'all', $types, true ) ) {
					$product_types = $types;

					if ( in_array( 'variation', $types, true ) ) {
						$product_types[] = 'variable';
					}

					$query_args['tax_query'] = [
						[
							'taxonomy' => 'product_type',
							'field'    => 'slug',
							'terms'    => $product_types,
						],
					];
				}

				if ( WPCleverWoosg_Helper()::get_setting( 'search_same', 'no' ) !== 'yes' ) {
					$query_args['post__not_in'] = array_map( 'absint', $added_ids );
				}
			}

			$query = new WP_Query( $query_args );

			if ( $query->have_posts() ) {
				echo '<ul>';

				while ( $query->have_posts() ) {
					$query->the_post();
					$item_product = wc_get_product( get_the_ID() );

					if ( ! $item_product || $item_product->is_type( 'woosg' ) ) {
						continue;
					}

					if ( ! $item_product->is_type( 'variable' ) || in_array( 'variable', $types, true ) || in_array( 'all', $types, true ) ) {
						self::product_data_li( $item_product, 0, true );
					}

					if ( $item_product->is_type( 'variable' ) && ( empty( $types ) || in_array( 'all', $types, true ) || in_array( 'variation', $types, true ) ) ) {
						// show all children
						$children = $item_product->get_children();

						if ( is_array( $children ) && count( $children ) > 0 ) {
							foreach ( $children as $child ) {
								$child_product = wc_get_product( $child );
								self::product_data_li( $child_product, 0, true );
							}
						}
					}
				}

				echo '</ul>';
				wp_reset_postdata();
			} else {
				echo '<ul><span>' . sprintf( esc_html__( 'No results found for "%s"', 'wpc-grouped-product' ), esc_html( $keyword ) ) . '</span></ul>';
			}

			wp_die();
		}

		function search_sku( $query ) {
			if ( $query->is_search && isset( $query->query['is_woosg'] ) ) {
				global $wpdb;

				$sku = sanitize_text_field( $query->query['s'] );
				$ids = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value = %s;", $sku ) );

				if ( ! $ids ) {
					return;
				}

				$posts = [];

				foreach ( $ids as $id ) {
					$post = get_post( $id );

					if ( $post->post_type === 'product_variation' ) {
						$posts[] = $post->post_parent;
					} else {
						$posts[] = $post->ID;
					}
				}

				unset( $query->query['s'], $query->query_vars['s'] );
				$query->set( 'post__in', $posts );
			}
		}

		function search_exact( $query ) {
			if ( $query->is_search && isset( $query->query['is_woosg'] ) ) {
				$query->set( 'exact', true );
			}
		}

		function search_sentence( $query ) {
			if ( $query->is_search && isset( $query->query['is_woosg'] ) ) {
				$query->set( 'sentence', true );
			}
		}

		function product_type_selector( $types ) {
			$types['woosg'] = esc_html__( 'Smart grouped', 'wpc-grouped-product' );

			return $types;
		}

		function product_data_tabs( $tabs ) {
			$tabs['woosg'] = [
				'label'  => esc_html__( 'Grouped Products', 'wpc-grouped-product' ),
				'target' => 'woosg_settings',
				'class'  => [ 'show_if_woosg' ],
			];

			return $tabs;
		}

		function product_tabs( $tabs ) {
			global $product;

			if ( ( WPCleverWoosg_Helper()::get_setting( 'position', 'above' ) === 'tab' ) && $product->is_type( 'woosg' ) ) {
				$tabs['woosg'] = [
					'title'    => WPCleverWoosg_Helper()::localization( 'tab', esc_html__( 'Grouped products', 'wpc-grouped-product' ) ),
					'priority' => 50,
					'callback' => [ $this, 'product_tab_woosg' ]
				];
			}

			return $tabs;
		}

		function product_tab_woosg() {
			self::show_items();
		}

		function product_data_panels() {
			global $post, $thepostid, $product_object;

			if ( $product_object instanceof WC_Product ) {
				$product_id = $product_object->get_id();
			} elseif ( is_numeric( $thepostid ) ) {
				$product_id = $thepostid;
			} elseif ( $post instanceof WP_Post ) {
				$product_id = $post->ID;
			} else {
				$product_id = 0;
			}

			if ( ! $product_id ) {
				?>
                <div id='woosg_settings' class='panel woocommerce_options_panel woosg_table'>
                    <p style="padding: 0 12px; color: #c9356e"><?php esc_html_e( 'Product wasn\'t returned.', 'wpc-grouped-product' ); ?></p>
                </div>
				<?php
				return;
			}

			$show_atc       = get_post_meta( $product_id, 'woosg_show_atc', true );
			$including_main = get_post_meta( $product_id, 'woosg_including_main', true );
			$selector       = get_post_meta( $product_id, 'woosg_selector', true );
			wp_enqueue_editor();
			?>
            <div id='woosg_settings' class='panel woocommerce_options_panel woosg_table'>
                <div id="woosg_search_settings" style="display: none" data-title="<?php esc_html_e( 'Search settings', 'wpc-grouped-product' ); ?>">
                    <table>
						<?php self::search_settings(); ?>
                        <tr>
                            <th></th>
                            <td>
                                <button id="woosg_search_settings_update" class="button button-primary">
									<?php esc_html_e( 'Update Options', 'wpc-grouped-product' ); ?>
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
                <table>
                    <tr>
                        <th><?php esc_html_e( 'Search', 'wpc-grouped-product' ); ?> (<a href="<?php echo esc_url( admin_url( 'admin.php?page=wpclever-woosg&tab=settings#search' ) ); ?>" id="woosg_search_settings_btn"><?php esc_html_e( 'settings', 'wpc-grouped-product' ); ?></a>)
                        </th>
                        <td>
                            <div class="w100">
                                <span class="loading" id="woosg_loading" style="display: none;"><?php esc_html_e( 'searching...', 'wpc-grouped-product' ); ?></span>
                                <input type="search" id="woosg_keyword" placeholder="<?php esc_html_e( 'Type any keyword to search', 'wpc-grouped-product' ); ?>"/>
                                <div id="woosg_results" class="woosg_results" style="display: none;"></div>
                            </div>
                        </td>
                    </tr>
                    <tr class="woosg_tr_space">
                        <th><?php esc_html_e( 'Selected', 'wpc-grouped-product' ); ?></th>
                        <td>
                            <div class="w100">
                                <div id="woosg_selected" class="woosg_selected">
                                    <ul>
										<?php
										if ( $ids = get_post_meta( $product_id, 'woosg_ids', true ) ) {
											if ( $items = self::get_items( $ids, $product_id ) ) {
												foreach ( $items as $item ) {
													if ( ! empty( $item['id'] ) ) {
														$item_product = wc_get_product( $item['id'] );

														if ( ! $item_product || $item_product->is_type( 'woosg' ) ) {
															continue;
														}

														self::product_data_li( $item_product, $item['qty'] );
													} else {
														// new version 4.0
														self::text_data_li( $item );
													}
												}
											}
										}
										?>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="woosg_tr_space">
                        <th></th>
                        <td>
                            <a href="https://wpclever.net/downloads/grouped-product?utm_source=pro&utm_medium=woosg&utm_campaign=wporg" target="_blank" class="woosg_add_txt" onclick="return confirm('This feature only available in Premium Version!\nBuy it now? Just $29')">
								<?php esc_html_e( '+ Add heading/paragraph', 'wpc-grouped-product' ); ?>
                            </a>
                        </td>
                    </tr>
                    <tr class="woosg_tr_space">
                        <th><?php echo esc_html__( 'Regular price', 'wpc-grouped-product' ) . ' (' . get_woocommerce_currency_symbol() . ')'; ?></th>
                        <td>
                            <span id="woosg_regular_price"></span>
                            <span class="woocommerce-help-tip" data-tip="<?php esc_attr_e( 'This price was used for displaying only. Always put a price in the General tab to display the Add to Cart button.', 'wpc-grouped-product' ); ?>"></span>
                        </td>
                    </tr>
                    <tr class="woosg_tr_space">
                        <th>
							<?php esc_html_e( 'Show add to cart button', 'wpc-grouped-product' ); ?>
                        </th>
                        <td>
                            <select name="woosg_show_atc">
                                <option value="default" <?php selected( $show_atc, 'default' ); ?>><?php esc_html_e( 'Default', 'wpc-grouped-product' ); ?></option>
                                <option value="each" <?php selected( $show_atc, 'each' ); ?>><?php esc_html_e( 'Yes, for each subproduct', 'wpc-grouped-product' ); ?></option>
                                <option value="all" <?php selected( $show_atc, 'all' ); ?>><?php esc_html_e( 'Yes, for the whole group', 'wpc-grouped-product' ); ?></option>
                                <option value="no" <?php selected( $show_atc, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-grouped-product' ); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr class="woosg_tr_space">
                        <th><?php esc_html_e( 'Include main product', 'wpc-grouped-product' ); ?></th>
                        <td>
                            <select name="woosg_including_main">
                                <option value="default" <?php selected( $including_main, 'default' ); ?>><?php esc_html_e( 'Default', 'wpc-grouped-product' ); ?></option>
                                <option value="yes" <?php selected( $including_main, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-grouped-product' ); ?></option>
                                <option value="no" <?php selected( $including_main, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-grouped-product' ); ?></option>
                            </select>
                            <span class="woocommerce-help-tip" data-tip="<?php esc_attr_e( 'Include main product on the cart. Helpful when you need to add some extra options for the main product, e.g WPC Frequently Bought Together, WPC Force Sells.', 'wpc-grouped-product' ); ?>"></span>
                        </td>
                    </tr>
                    <tr class="woosg_tr_space">
                        <th><?php esc_html_e( 'Product selector', 'wpc-grouped-product' ); ?></th>
                        <td>
                            <select name="woosg_selector">
                                <option value="default" <?php selected( $selector, 'default' ); ?>><?php esc_html_e( 'Default', 'wpc-grouped-product' ); ?></option>
                                <option value="quantity" <?php selected( $selector, 'quantity' ); ?>><?php esc_html_e( 'Quantity', 'wpc-grouped-product' ); ?></option>
                                <option value="checkbox" <?php selected( $selector, 'checkbox' ); ?>><?php esc_html_e( 'Checkbox', 'wpc-grouped-product' ); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr class="woosg_tr_space">
                        <th><?php esc_html_e( 'Custom display price', 'wpc-grouped-product' ); ?></th>
                        <td>
                            <input type="text" name="woosg_custom_price" value="<?php echo stripslashes( get_post_meta( $product_id, 'woosg_custom_price', true ) ); ?>"/> E.g:
                            <code>From $10 to $100</code>
                        </td>
                    </tr>
                    <tr class="woosg_tr_space">
                        <th><?php esc_html_e( 'Layout', 'wpc-grouped-product' ); ?></th>
                        <td>
							<?php $layout = get_post_meta( $product_id, 'woosg_layout', true ) ?: 'unset'; ?>
                            <select name="woosg_layout">
                                <option value="unset" <?php selected( $layout, 'unset' ); ?>><?php esc_html_e( 'Unset (default setting)', 'wpc-grouped-product' ); ?></option>
                                <option value="list" <?php selected( $layout, 'list' ); ?>><?php esc_html_e( 'List', 'wpc-grouped-product' ); ?></option>
                                <option value="grid-2" <?php selected( $layout, 'grid-2' ); ?>><?php esc_html_e( 'Grid - 2 columns', 'wpc-grouped-product' ); ?></option>
                                <option value="grid-3" <?php selected( $layout, 'grid-3' ); ?>><?php esc_html_e( 'Grid - 3 columns', 'wpc-grouped-product' ); ?></option>
                                <option value="grid-4" <?php selected( $layout, 'grid-4' ); ?>><?php esc_html_e( 'Grid - 4 columns', 'wpc-grouped-product' ); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr class="woosg_tr_space">
                        <th><?php esc_html_e( 'Above text', 'wpc-grouped-product' ); ?></th>
                        <td>
                            <div class="w100">
                                <textarea name="woosg_before_text"><?php echo stripslashes( get_post_meta( $product_id, 'woosg_before_text', true ) ); ?></textarea>
                            </div>
                        </td>
                    </tr>
                    <tr class="woosg_tr_space">
                        <th><?php esc_html_e( 'Under text', 'wpc-grouped-product' ); ?></th>
                        <td>
                            <div class="w100">
                                <textarea name="woosg_after_text"><?php echo stripslashes( get_post_meta( $product_id, 'woosg_after_text', true ) ); ?></textarea>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
			<?php
		}

		function product_data_li( $product, $qty = 0, $search = false ) {
			$key           = WPCleverWoosg_Helper()::generate_key();
			$product_id    = $product->get_id();
			$product_sku   = $product->get_sku();
			$product_class = 'woosg-li-product';

			if ( ! $product->is_in_stock() ) {
				$product_class .= ' out-of-stock';
			}

			if ( in_array( $product->get_type(), self::$types, true ) ) {
				$product_class .= ' disabled';
			}

			if ( class_exists( 'WPCleverWoopq' ) && ( get_option( '_woopq_decimal', 'no' ) === 'yes' ) ) {
				$step = '0.000001';
			} else {
				$step = 1;
			}

			$hidden_input = '<input type="hidden" name="woosg_ids[' . $key . '][id]" value="' . $product_id . '"/><input type="hidden" name="woosg_ids[' . $key . '][sku]" value="' . $product_sku . '"/>';
			$qty_input    = '<input type="number" name="woosg_ids[' . $key . '][qty]" value="' . esc_attr( $qty ) . '" min="0" step="' . esc_attr( $step ) . '"/>';

			if ( $search ) {
				$remove_btn = '<span class="woosg-remove hint--left" aria-label="' . esc_html__( 'Add', 'wpc-grouped-product' ) . '">+</span>';
			} else {
				$remove_btn = '<span class="woosg-remove hint--left" aria-label="' . esc_html__( 'Remove', 'wpc-grouped-product' ) . '">×</span>';
			}

			if ( $product->is_type( 'variation' ) ) {
				$edit_link = get_edit_post_link( $product->get_parent_id() );
			} else {
				$edit_link = get_edit_post_link( $product_id );
			}

			echo '<li class="' . esc_attr( $product_class ) . '" data-id="' . esc_attr( $product_id ) . '" data-price="' . esc_attr( $product->get_price() ) . '">' . $hidden_input . '<span class="move"></span><span class="qty hint--right" aria-label="' . esc_html__( 'Default quantity', 'wpc-grouped-product' ) . '">' . $qty_input . '</span><span class="img">' . $product->get_image( [
					30,
					30
				] ) . '</span><span class="data"><span class="name">' . wp_strip_all_tags( $product->get_name() ) . '</span> <span class="info">' . $product->get_price_html() . '</span> ' . ( $product->is_sold_individually() ? '<span class="info">sold individually</span> ' : '' ) . '</span> <span class="type"><a href="' . esc_url( $edit_link ) . '" target="_blank">' . esc_attr( $product->get_type() ) . '<br/>#' . esc_attr( $product_id ) . '</a></span> ' . $remove_btn . '</li>';
		}

		function text_data_li( $data = [] ) {
			$key  = WPCleverWoosg_Helper()::generate_key();
			$data = array_merge( [ 'type' => 'h1', 'text' => '' ], $data );
			$type = '<select name="woosg_ids[' . $key . '][type]"><option value="h1" ' . selected( $data['type'], 'h1', false ) . '>H1</option><option value="h2" ' . selected( $data['type'], 'h2', false ) . '>H2</option><option value="h3" ' . selected( $data['type'], 'h3', false ) . '>H3</option><option value="h4" ' . selected( $data['type'], 'h4', false ) . '>H4</option><option value="h5" ' . selected( $data['type'], 'h5', false ) . '>H5</option><option value="h6" ' . selected( $data['type'], 'h6', false ) . '>H6</option><option value="p" ' . selected( $data['type'], 'p', false ) . '>p</option><option value="span" ' . selected( $data['type'], 'span', false ) . '>span</option><option value="none" ' . selected( $data['type'], 'none', false ) . '>none</option></select>';

			echo '<li class="woosg-li-text"><span class="move"></span><span class="tag">' . $type . '</span><span class="data"><input type="text" name="woosg_ids[' . $key . '][text]" value="' . esc_attr( $data['text'] ) . '"/></span><span class="woosg-remove hint--left" aria-label="' . esc_html__( 'Remove', 'wpc-grouped-product' ) . '">×</span></li>';
		}

		function process_product_meta_woosg( $post_id ) {
			if ( isset( $_POST['woosg_ids'] ) ) {
				update_post_meta( $post_id, 'woosg_ids', WPCleverWoosg_Helper()::sanitize_array( $_POST['woosg_ids'] ) );
			}

			if ( isset( $_POST['woosg_show_atc'] ) ) {
				update_post_meta( $post_id, 'woosg_show_atc', sanitize_text_field( $_POST['woosg_show_atc'] ) );
			}

			if ( isset( $_POST['woosg_including_main'] ) ) {
				update_post_meta( $post_id, 'woosg_including_main', sanitize_text_field( $_POST['woosg_including_main'] ) );
			}

			if ( isset( $_POST['woosg_selector'] ) ) {
				update_post_meta( $post_id, 'woosg_selector', sanitize_text_field( $_POST['woosg_selector'] ) );
			}

			if ( isset( $_POST['woosg_custom_price'] ) ) {
				update_post_meta( $post_id, 'woosg_custom_price', addslashes( $_POST['woosg_custom_price'] ) );
			}

			if ( isset( $_POST['woosg_before_text'] ) ) {
				update_post_meta( $post_id, 'woosg_before_text', sanitize_post_field( 'post_content', $_POST['woosg_before_text'], $post_id, 'db' ) );
			}

			if ( isset( $_POST['woosg_after_text'] ) ) {
				update_post_meta( $post_id, 'woosg_after_text', sanitize_post_field( 'post_content', $_POST['woosg_after_text'], $post_id, 'db' ) );
			}

			if ( isset( $_POST['woosg_layout'] ) ) {
				update_post_meta( $post_id, 'woosg_layout', sanitize_text_field( $_POST['woosg_layout'] ) );
			}
		}

		function shortcode( $attrs ) {
			$attrs = shortcode_atts( [ 'id' => null ], $attrs );

			ob_start();
			self::show_items( $attrs['id'] );

			return ob_get_clean();
		}

		function shortcode_form( $attrs ) {
			global $product;

			$attrs      = shortcode_atts( [ 'id' => null ], $attrs );
			$product_id = $attrs['id'];

			if ( ! $product_id ) {
				if ( $product ) {
					$product_id = $product->get_id();
				}
			} else {
				$product = wc_get_product( $product_id );
			}

			if ( ! $product_id || ! $product ) {
				return '';
			}

			ob_start();

			if ( $product->has_variables() ) {
				wp_enqueue_script( 'wc-add-to-cart-variation' );
			}

			self::show_items( $product_id );

			wc_get_template( 'single-product/add-to-cart/simple.php' );

			return ob_get_clean();
		}

		function add_to_cart_form() {
			global $product;

			if ( $product ) {
				$product_id = $product->get_id();
			}

			if ( ! $product_id || ! $product ) {
				return;
			}

			if ( $product->has_variables() ) {
				wp_enqueue_script( 'wc-add-to-cart-variation' );
			}

			if ( ( WPCleverWoosg_Helper()::get_setting( 'position', 'above' ) === 'above' ) && apply_filters( 'woosg_show_items', true, $product_id ) ) {
				self::show_items( $product_id );
			}

			wc_get_template( 'single-product/add-to-cart/simple.php' );

			if ( ( WPCleverWoosg_Helper()::get_setting( 'position', 'above' ) === 'below' ) && apply_filters( 'woosg_show_items', true, $product_id ) ) {
				self::show_items( $product_id );
			}
		}

		function add_to_cart_button() {
			global $product;

			if ( $product && is_a( $product, 'WC_Product_Woosg' ) && ( $ids = $product->get_ids_str() ) ) {
				echo '<input name="woosg_ids" class="woosg-ids woosg-ids-' . esc_attr( $product->get_id() ) . '" type="hidden" value="' . esc_attr( $ids ) . '"/>';
			}
		}

		function show_items( $product = null ) {
			$product_id = null;

			if ( ! $product ) {
				global $product;

				if ( $product ) {
					$product_id = $product->get_id();
				}
			} elseif ( is_numeric( $product ) ) {
				$product_id = $product;
				$product    = wc_get_product( $product_id );
			}

			if ( ! $product_id || ! $product || ! $product->is_type( 'woosg' ) ) {
				return;
			}

			// settings
			$selector = WPCleverWoosg_Helper()::get_setting( 'selector', 'quantity' );
			$show_atc = WPCleverWoosg_Helper()::get_setting( 'show_atc', 'all' );

			if ( ( $_selector = get_post_meta( $product_id, 'woosg_selector', true ) ) && $_selector !== 'default' ) {
				$selector = $_selector;
			}

			if ( ( $_show_atc = get_post_meta( $product_id, 'woosg_show_atc', true ) ) && $_show_atc !== 'default' ) {
				$show_atc = $_show_atc;
			}

			$link             = WPCleverWoosg_Helper()::get_setting( 'link', 'yes' ) !== 'no';
			$show_thumb       = WPCleverWoosg_Helper()::get_setting( 'show_thumb', 'yes' ) !== 'no';
			$show_price       = WPCleverWoosg_Helper()::get_setting( 'show_price', 'yes' ) !== 'no';
			$show_description = WPCleverWoosg_Helper()::get_setting( 'show_description', 'no' ) === 'yes';
			$show_plus_minus  = WPCleverWoosg_Helper()::get_setting( 'show_plus_minus', 'no' ) === 'yes';
			$layout           = get_post_meta( $product_id, 'woosg_layout', true ) ?: 'unset';
			$layout           = $layout !== 'unset' ? $layout : WPCleverWoosg_Helper()::get_setting( 'layout', 'list' );
			$products_class   = apply_filters( 'woosg_products_class', 'woosg-table woosg-products woosg-products-layout-' . $layout . ' woosg-show-atc-' . $show_atc, $product );

			if ( $items = $product->get_items() ) {
				do_action( 'woosg_above_wrap', $product );

				echo '<div class="woosg-wrap" data-id="' . esc_attr( $product_id ) . '">';

				do_action( 'woosg_before_wrap', $product );

				if ( $before_text = apply_filters( 'woosg_before_text', get_post_meta( $product_id, 'woosg_before_text', true ) ?: WPCleverWoosg_Helper()::localization( 'above_text' ), $product_id ) ) {
					echo '<div class="woosg_before_text woosg-before-text woosg-text">' . do_shortcode( stripslashes( $before_text ) ) . '</div>';
				}

				do_action( 'woosg_before_table', $product );

				$including_main = get_post_meta( $product_id, 'woosg_including_main', true );

				if ( ( WPCleverWoosg_Helper()::get_setting( 'main_price', 'zero' ) === 'zero' ) || ( ( ! $including_main || ( $including_main === 'default' ) ) && ( WPCleverWoosg_Helper()::get_setting( 'including_main', 'no' ) !== 'yes' ) ) || ( $including_main === 'no' ) ) {
					$main_price         = 0;
					$main_regular_price = 0;
				} else {
					$main_price         = wc_get_price_to_display( $product );
					$main_regular_price = wc_get_price_to_display( $product, [ 'price' => $product->get_regular_price() ] );
				}
				?>
                <div class="<?php echo esc_attr( $products_class ); ?>" data-variables="<?php echo esc_attr( $product->has_variables() ? 'yes' : 'no' ); ?>" data-price="<?php echo esc_attr( $main_price ); ?>" data-regular-price="<?php echo esc_attr( $main_regular_price ); ?>">
					<?php
					do_action( 'woosg_before_items', $product );

					$order = 1;

					$global_product = $product;

					foreach ( $items as $item ) {
						if ( ! empty( $item['id'] ) ) {
							global $product;
							$product = wc_get_product( $item['id'] );

							if ( ! $product || ( $product->get_status() !== 'publish' && apply_filters( 'woosg_show_publish_product_only', true ) ) ) {
								continue;
							}

							if ( in_array( $product->get_type(), self::$types, true ) ) {
								continue;
							}

							$product_name       = apply_filters( 'woosg_item_product_name', $product->get_name(), $product, $global_product );
							$item_qty           = apply_filters( 'woosg_quantity_input_value', $item['qty'], $product );
							$item_price         = apply_filters( 'woosg_item_price', wc_get_price_to_display( $product ), $product );
							$item_regular_price = apply_filters( 'woosg_item_regular_price', wc_get_price_to_display( $product, [ 'price' => $product->get_regular_price() ] ), $product );
							$item_class         = 'woosg-item-product woosg-product product-type-' . $product->get_type();
							$item_id            = $product->is_type( 'variable' ) ? 0 : $item['id'];

							if ( $product->is_purchasable() && $product->is_in_stock() ) {
								$min = apply_filters( 'woosg_quantity_input_min', 0, $product );
								$max = apply_filters( 'woosg_quantity_input_max', $product->get_max_purchase_quantity(), $product );

								if ( $max < 0 ) {
									$max = 10000;
								}

								if ( $item_qty < $min ) {
									$item_qty = $min;
								}

								if ( ( $max > 0 ) && ( $item_qty > $max ) ) {
									$item_qty = $max;
								}

								if ( $item_qty && ( $selector === 'checkbox' ) ) {
									$item_qty = 1;
								}
							} else {
								$item_class         .= ' woosg-product-unpurchasable';
								$item_price         = 0;
								$item_regular_price = 0;
								$item_qty           = 0;
								$item_id            = - 1;
								$min                = 0;
								$max                = 0;
							}

							if ( function_exists( 'Wpcpq_Helper' ) ) {
								$item_wpcpq = Wpcpq_Helper()::get_pricing( $item_id );
							} else {
								$item_wpcpq = [];
							}
							?>
                            <div class="<?php echo esc_attr( apply_filters( 'woosg_item_class', $item_class, $product, $global_product ) ); ?>" data-name="<?php echo esc_attr( $product_name ); ?>" data-id="<?php echo esc_attr( $item_id ); ?>" data-price="<?php echo esc_attr( $item_price ); ?>" data-regular-price="<?php echo esc_attr( $item_regular_price ); ?>" data-qty="<?php echo esc_attr( $item_qty ); ?>" data-order="<?php echo esc_attr( $order ); ?>" data-wpcpq="<?php echo esc_attr( json_encode( $item_wpcpq ) ); ?>">

								<?php
								do_action( 'woosg_before_item', $product, $global_product, $order );

								if ( ( $selector === 'checkbox' ) && ( $show_atc === 'all' ) ) {
									?>
                                    <div class="woosg-choose">
										<?php if ( $product->is_purchasable() && $product->is_in_stock() && $item_qty ) {
											echo '<input class="woosg-checkbox" type="checkbox" checked/>';
										} else {
											echo '<input class="woosg-checkbox" type="checkbox"/>';
										} ?>
                                        <span class="checkmark"></span>
                                    </div>
									<?php
								}

								if ( $show_thumb ) {
									?>
                                    <div class="woosg-thumb">
										<?php
										do_action( 'woosg_before_item_thumb', $product, $global_product, $order );

										if ( $link ) {
											echo '<a class="woosg-product-link' . ( WPCleverWoosg_Helper()::get_setting( 'link', 'yes' ) === 'yes_popup' ? ' woosq-link' : '' ) . '" data-id="' . $item['id'] . '" data-context="woosg" href="' . get_permalink( $item['id'] ) . '" ' . ( WPCleverWoosg_Helper()::get_setting( 'link', 'yes' ) === 'yes_blank' ? 'target="_blank"' : '' ) . '>';
										} ?>
                                        <div class="woosg-thumb-ori">
											<?php echo wp_kses( apply_filters( 'woosg_item_thumbnail', $product->get_image( self::$image_size ), $product, $global_product, $order ), [
												'img' => [
													'width'       => [],
													'height'      => [],
													'sizes'       => [],
													'srcset'      => [],
													'data-src'    => [],
													'data-srcset' => [],
													'class'       => [],
													'src'         => [],
													'id'          => [],
													'alt'         => []
												]
											] ); ?>
                                        </div>
                                        <div class="woosg-thumb-new"></div>
										<?php if ( $link ) {
											echo '</a>';
										}

										do_action( 'woosg_after_item_thumb', $product, $global_product, $order ); ?>
                                    </div><!-- /woosg-thumb -->
									<?php
								} ?>

                                <div class="woosg-title">
									<?php
									do_action( 'woosg_before_item_name', $product, $global_product, $order );

									echo '<div class="woosg-name woosg-title-inner">';
									$item_name = '';

									if ( $link ) {
										$item_name .= '<a class="woosg-product-link' . ( WPCleverWoosg_Helper()::get_setting( 'link', 'yes' ) === 'yes_popup' ? ' woosq-link' : '' ) . '" data-id="' . $item['id'] . '" data-context="woosg" href="' . get_permalink( $item['id'] ) . '" ' . ( WPCleverWoosg_Helper()::get_setting( 'link', 'yes' ) === 'yes_blank' ? 'target="_blank"' : '' ) . '>';
									}

									if ( $product->is_in_stock() ) {
										$item_name .= $product_name;
									} else {
										$item_name .= '<s>' . $product_name . '</s>';
									}

									if ( $link ) {
										$item_name .= '</a>';
									}

									echo wp_kses( apply_filters( 'woosg_item_name', $item_name, $product, $order ), [
										'a'    => [
											'class'   => [],
											'data-id' => [],
											'href'    => [],
											'target'  => []
										],
										'span' => [
											'class' => []
										],
										's'    => []
									] );
									echo '</div>';

									do_action( 'woosg_after_item_name', $product, $global_product, $order );

									if ( $show_price ) { ?>
                                        <div class="woosg-price">
											<?php do_action( 'woosg_before_item_price', $product, $global_product, $order ); ?>
                                            <div class="woosg-price-ori">
												<?php echo wp_kses_post( $product->get_price_html() ); ?>
                                            </div>
                                            <div class="woosg-price-new"></div>
											<?php do_action( 'woosg_after_item_price', $product, $global_product, $order ); ?>
                                        </div>
									<?php }

									if ( $show_description ) {
										echo '<div class="woosg-description">' . apply_filters( 'woosg_item_description', $product->get_short_description(), $product ) . '</div>';
									}

									echo '<div class="woosg-availability">' . wc_get_stock_html( $product ) . '</div>';
									?>
                                </div><!-- /woosg-title -->

								<?php
								if ( ( $selector === 'quantity' ) && ( $show_atc === 'all' ) ) {
									if ( $product->is_purchasable() && $product->is_in_stock() ) {
										echo '<div class="' . esc_attr( $show_plus_minus ? 'woosg-quantity woosg-quantity-plus-minus' : 'woosg-quantity' ) . '" data-min="' . esc_attr( $min ) . '" data-max="' . esc_attr( $max ) . '" data-o_min="' . esc_attr( $min ) . '" data-o_max="' . esc_attr( $max ) . '">';

										do_action( 'woosg_before_item_qty', $product, $global_product, $order );

										if ( $show_plus_minus ) {
											echo '<div class="woosg-quantity-input">';
											echo '<span class="woosg-quantity-minus">-</span>';
										}

										$qty_args = [
											'input_value' => $item_qty,
											'min_value'   => $min,
											'max_value'   => $max,
											'woosg_qty'   => [
												'input_value' => $item_qty,
												'min_value'   => $min,
												'max_value'   => $max
											],
											'classes'     => [
												'input-text',
												'woosg-qty',
												'qty',
												'text'
											],
											'input_name'  => 'woosg_qty_' . $order
										];

										woocommerce_quantity_input( $qty_args, $product );

										if ( $show_plus_minus ) {
											echo '<span class="woosg-quantity-plus">+</span>';
											echo '</div>';
										}

										do_action( 'woosg_after_item_qty', $product, $global_product, $order );

										echo '</div><!-- /woosg-quantity -->';
									} else {
										echo '<div class="' . esc_attr( $show_plus_minus ? 'woosg-quantity woosg-quantity-plus-minus' : 'woosg-quantity' ) . '" data-min="' . esc_attr( $item_qty ) . '" data-max="' . esc_attr( $item_qty ) . '">';

										do_action( 'woosg_before_item_qty', $product, $global_product, $order );

										if ( $show_plus_minus ) {
											echo '<div class="woosg-quantity-input">';
											echo '<span class="woosg-quantity-minus">-</span>';
										}

										echo '<div class="quantity"><input type="number" class="input-text qty text" value="' . esc_attr( $item_qty ) . '" readonly/></div>';

										if ( $show_plus_minus ) {
											echo '<span class="woosg-quantity-plus">+</span>';
											echo '</div>';
										}

										do_action( 'woosg_after_item_qty', $product, $global_product, $order );

										echo '</div><!-- /woosg-quantity -->';
									}
								}

								if ( $show_atc === 'each' ) {
									?>
                                    <div class="woosg-atc">
										<?php if ( $product->is_purchasable() && $product->is_in_stock() ) { ?>
                                            <form class="cart woosg-cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $global_product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
                                                <input type="hidden" name="add-to-cart" value="<?php echo absint( $item['id'] ); ?>"/>
                                                <input type="hidden" name="product_id" value="<?php echo absint( $item['id'] ); ?>"/>
												<?php
												if ( $product->is_type( 'variable' ) ) {
													echo '<input type="hidden" name="variation_id" value="0"/>';

													$attributes = $product->get_variation_attributes();

													if ( is_array( $attributes ) && ( count( $attributes ) > 0 ) ) {
														foreach ( $attributes as $attribute_name => $options ) {
															echo '<input type="hidden" name="' . esc_attr( 'attribute_' . $attribute_name ) . '" value=""/>';
														}
													}
												}

												echo '<div class="' . esc_attr( $show_plus_minus ? 'woosg-quantity woosg-quantity-plus-minus' : 'woosg-quantity' ) . '" data-min="' . esc_attr( $min ) . '" data-max="' . esc_attr( $max ) . '" data-o_min="' . esc_attr( $min ) . '" data-o_max="' . esc_attr( $max ) . '">';

												do_action( 'woosg_before_item_qty', $product, $global_product, $order );

												if ( $show_plus_minus ) {
													echo '<div class="woosg-quantity-input">';
													echo '<span class="woosg-quantity-minus">-</span>';
												}

												$qty_args = [
													'input_value' => $item_qty,
													'min_value'   => $min,
													'max_value'   => $max,
													'woosg_qty'   => [
														'input_value' => $item_qty,
														'min_value'   => $min,
														'max_value'   => $max
													],
													'classes'     => [
														'input-text',
														'woosg-qty',
														'qty',
														'text'
													]
												];

												woocommerce_quantity_input( $qty_args, $product );

												if ( $show_plus_minus ) {
													echo '<span class="woosg-quantity-plus">+</span>';
													echo '</div>';
												}

												do_action( 'woosg_after_item_qty', $product, $global_product, $order );

												echo '</div><!-- /woosg-quantity -->';
												?>
                                                <button type="submit" name="add-to-cart" value="" class="single_add_to_cart_button woosg_single_add_to_cart_button button alt">
													<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
                                                </button>
                                            </form>
										<?php } ?>
                                    </div>
									<?php
								}

								do_action( 'woosg_after_item', $product, $global_product, $order );
								?>
                            </div><!-- /woosg-product -->
							<?php
						} elseif ( ! empty( $item['text'] ) ) {
							$item_class = 'woosg-item-text';

							if ( ! empty( $item['type'] ) ) {
								$item_class .= ' woosg-item-text-type-' . $item['type'];
							}

							echo '<div class="' . esc_attr( apply_filters( 'woosg_item_text_class', $item_class, $item, $global_product, $order ) ) . '">';

							if ( empty( $item['type'] ) || ( $item['type'] === 'none' ) ) {
								echo wp_kses_post( $item['text'] );
							} else {
								echo wp_kses_post( '<' . $item['type'] . '>' . $item['text'] . '</' . $item['type'] . '>' );
							}

							echo '</div>';
						}

						$order ++;
					}

					$product = $global_product;

					do_action( 'woosg_after_items', $product );
					?>
                </div>
				<?php
				do_action( 'woosg_before_total', $product );

				echo '<div class="woosg_total woosg-total woosg-text"></div>';

				do_action( 'woosg_before_alert', $product );

				echo '<div class="woosg-alert woosg-text" style="display: none"></div>';

				do_action( 'woosg_after_table', $product );

				if ( $after_text = apply_filters( 'woosg_after_text', get_post_meta( $product_id, 'woosg_after_text', true ) ?: WPCleverWoosg_Helper()::localization( 'under_text' ), $product_id ) ) {
					echo '<div class="woosg_after_text woosg-after-text woosg-text">' . do_shortcode( stripslashes( $after_text ) ) . '</div>';
				}

				do_action( 'woosg_after_wrap', $product );

				echo '</div>';

				do_action( 'woosg_below_wrap', $product );
			}
		}

		function get_price_html( $price, $product ) {
			if ( $product->is_type( 'woosg' ) ) {
				$product_id   = $product->get_id();
				$custom_price = get_post_meta( $product_id, 'woosg_custom_price', true );

				if ( ! empty( $custom_price ) ) {
					return $custom_price;
				}

				switch ( WPCleverWoosg_Helper()::get_setting( 'price_format', 'from' ) ) {
					case 'none':
						return '';
					case 'from':
						return WPCleverWoosg_Helper()::localization( 'from', esc_html__( 'From', 'wpc-grouped-product' ) ) . ' ' . wc_price( $product->get_price() );
					case 'auto':
						$including_main = get_post_meta( $product_id, 'woosg_including_main', true );
						$min_or_max     = apply_filters( 'woosg_auto_price_min_or_max', 'min' );

						if ( ( WPCleverWoosg_Helper()::get_setting( 'main_price', 'zero' ) === 'zero' ) || ( $including_main === 'no' ) || ( ( ! $including_main || ( $including_main === 'default' ) ) && ( WPCleverWoosg_Helper()::get_setting( 'including_main', 'no' ) !== 'yes' ) ) ) {
							$regular_price = $sale_price = 0;
						} else {
							$regular_price = $product->get_regular_price();
							$sale_price    = $product->get_price();
						}

						if ( $items = $product->get_items() ) {
							foreach ( $items as $item ) {
								if ( $item_product = wc_get_product( $item['id'] ) ) {
									if ( $item_product->is_type( 'variable' ) ) {
										if ( $item_product_variation = self::get_product_default_variation( $item_product ) ) {
											$regular_price += wc_get_price_to_display( $item_product_variation, [
												'price' => $item_product_variation->get_regular_price(),
												'qty'   => $item['qty']
											] );
											$sale_price    += wc_get_price_to_display( $item_product_variation, [
												'qty' => $item['qty']
											] );
										} else {
											$regular_price += wc_get_price_to_display( $item_product, [
												'price' => $item_product->get_variation_regular_price( $min_or_max ),
												'qty'   => $item['qty']
											] );

											if ( $item_sale_price = $item_product->get_variation_sale_price( $min_or_max ) ) {
												$sale_price += wc_get_price_to_display( $item_product, [
													'price' => $item_sale_price,
													'qty'   => $item['qty']
												] );
											} else {
												$sale_price += wc_get_price_to_display( $item_product, [
													'price' => $item_product->get_variation_regular_price( $min_or_max ),
													'qty'   => $item['qty']
												] );
											}
										}
									} else {
										$regular_price += wc_get_price_to_display( $item_product, [
											'price' => $item_product->get_regular_price(),
											'qty'   => $item['qty']
										] );

										if ( $item_sale_price = $item_product->get_sale_price() ) {
											$sale_price += wc_get_price_to_display( $item_product, [
												'price' => $item_sale_price,
												'qty'   => $item['qty']
											] );
										} else {
											$sale_price += wc_get_price_to_display( $item_product, [
												'price' => $item_product->get_regular_price(),
												'qty'   => $item['qty']
											] );
										}
									}
								}
							}

							if ( $sale_price && ( $sale_price < $regular_price ) ) {
								return wc_format_sale_price( wc_price( $regular_price ), wc_price( $sale_price ) );
							} else {
								return wc_price( $regular_price );
							}
						}
				}
			}

			return $price;
		}

		function get_product_default_variation( $product ) {
			$default_attributes   = $product->get_default_attributes();
			$prefixed_slugs       = array_map( function ( $pa_name ) {
				return 'attribute_' . $pa_name;
			}, array_keys( $default_attributes ) );
			$default_attributes   = array_combine( $prefixed_slugs, $default_attributes );
			$default_variation_id = ( new WC_Product_Data_Store_CPT() )->find_matching_product_variation( $product, $default_attributes );

			return wc_get_product( $default_variation_id );
		}

		function product_class( $classes, $product ) {
			if ( $product && $product->is_type( 'woosg' ) ) {
				$show_atc = WPCleverWoosg_Helper()::get_setting( 'show_atc', 'all' );

				if ( ( $_show_atc = get_post_meta( $product->get_id(), 'woosg_show_atc', true ) ) && $_show_atc !== 'default' ) {
					$show_atc = $_show_atc;
				}

				$classes[] = 'woosg-show-atc-' . $show_atc;
			}

			return $classes;
		}

		function product_price_class( $class ) {
			global $product;

			if ( $product && $product->is_type( 'woosg' ) ) {
				$class .= ' woosg-price-' . $product->get_id();
			}

			return $class;
		}

		function display_post_states( $states, $post ) {
			if ( ( 'product' == get_post_type( $post->ID ) ) && ( $product = wc_get_product( $post->ID ) ) && $product->is_type( 'woosg' ) ) {
				$count = 0;

				if ( $ids_str = $product->get_ids_str() ) {
					$ids_arr = explode( ',', $ids_str );
					$count   = count( $ids_arr );
				}

				$states[] = apply_filters( 'woosg_post_states', '<span class="woosg-state">' . sprintf( esc_html__( 'Grouped (%s)', 'wpc-grouped-product' ), $count ) . '</span>', $count, $product );
			}

			return $states;
		}

		function get_items( $ids, $product = null ) {
			$data = [];
			$ids  = WPCleverWoosg_Helper()::clean_ids( $ids );

			if ( ! empty( $ids ) ) {
				if ( is_array( $ids ) ) {
					// new version 4.0
					foreach ( $ids as $item ) {
						$item = array_merge( [
							'id'    => 0,
							'sku'   => '',
							'qty'   => 0,
							'attrs' => []
						], $item );

						if ( apply_filters( 'woosg_use_sku', false ) && ! empty( $item['sku'] ) && ( $new_id = wc_get_product_id_by_sku( $item['sku'] ) ) ) {
							// get product id by SKU for export/import
							$item['id'] = $new_id;
						}

						$data[] = $item;
					}
				} else {
					$items = explode( ',', $ids );

					if ( is_array( $items ) && count( $items ) > 0 ) {
						foreach ( $items as $item ) {
							$item_data = explode( '/', $item );
							$data[]    = [
								'id'    => apply_filters( 'woosg_item_id', absint( isset( $item_data[0] ) ? $item_data[0] : 0 ) ),
								'qty'   => apply_filters( 'woosg_item_qty', (float) ( isset( $item_data[1] ) ? $item_data[1] : 0 ) ),
								'attrs' => isset( $item_data[2] ) ? (array) json_decode( rawurldecode( $item_data[2] ) ) : []
							];
						}
					}
				}
			}

			return apply_filters( 'woosg_get_items', $data, $product );
		}

		function wpml_item_id( $id ) {
			return apply_filters( 'wpml_object_id', $id, 'product', true );
		}

		function wpcsm_locations( $locations ) {
			$locations['WPC Grouped Product'] = [
				'woosg_before_wrap'       => esc_html__( 'Before wrapper', 'wpc-grouped-product' ),
				'woosg_after_wrap'        => esc_html__( 'After wrapper', 'wpc-grouped-product' ),
				'woosg_before_items'      => esc_html__( 'Before products', 'wpc-grouped-product' ),
				'woosg_after_items'       => esc_html__( 'After products', 'wpc-grouped-product' ),
				'woosg_before_item'       => esc_html__( 'Before sub-product', 'wpc-grouped-product' ),
				'woosg_after_item'        => esc_html__( 'After sub-product', 'wpc-grouped-product' ),
				'woosg_before_item_thumb' => esc_html__( 'Before sub-product thumbnail', 'wpc-grouped-product' ),
				'woosg_after_item_thumb'  => esc_html__( 'After sub-product thumbnail', 'wpc-grouped-product' ),
				'woosg_before_item_name'  => esc_html__( 'Before sub-product name', 'wpc-grouped-product' ),
				'woosg_after_item_name'   => esc_html__( 'After sub-product name', 'wpc-grouped-product' ),
				'woosg_before_item_price' => esc_html__( 'Before sub-product price', 'wpc-grouped-product' ),
				'woosg_after_item_price'  => esc_html__( 'After sub-product price', 'wpc-grouped-product' ),
				'woosg_before_item_qty'   => esc_html__( 'Before sub-product quantity', 'wpc-grouped-product' ),
				'woosg_after_item_qty'    => esc_html__( 'After sub-product quantity', 'wpc-grouped-product' ),
			];

			return $locations;
		}

		// old version - moved to Helper

		public static function get_settings() {
			return apply_filters( 'woosg_get_settings', self::$settings );
		}

		public static function get_setting( $name, $default = false ) {
			if ( ! empty( self::$settings ) && isset( self::$settings[ $name ] ) ) {
				$setting = self::$settings[ $name ];
			} else {
				$setting = get_option( 'woosg_' . $name, $default );
			}

			return apply_filters( 'woosg_get_setting', $setting, $name, $default );
		}
	}

	function WPCleverWoosg() {
		return WPCleverWoosg::instance();
	}

	WPCleverWoosg();
}
