<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.webtoffee.com
 * @since      2.0.0
 *
 * @package    Webtoffee_Product_Feed_Sync
 * @subpackage Webtoffee_Product_Feed_Sync/admin
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Webtoffee_Product_Feed_Sync
 * @subpackage Webtoffee_Product_Feed_Sync/admin
 * @author     WebToffee <info@webtoffee.com>
 */
if(!class_exists('Webtoffee_Product_Feed_Sync_Admin')){
class Webtoffee_Product_Feed_Sync_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	
		
	public static $modules=array(	
		'history',
		'export', 
		'cron', 
	);
	
	public static $existing_modules=array();

	public static $addon_modules=array();
        	
	/** @var string|null the generated external merchant settings ID */
	private $external_business_id;

	/** @var string the product sync to FB batch limit */
	public $batch_limit = 10;

	/** @var array the page handles that with the plugin views */
	public $wt_pages = array( 'woocommerce_page_webtoffee-product-feed', 'webtoffee-product-feed_page_webtoffee-product-feed' );

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name	 = $plugin_name;
		$this->version		 = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Webtoffee_Product_Feed_Sync_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Webtoffee_Product_Feed_Sync_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$current_screen = get_current_screen();

		if ( isset( $current_screen->id ) && in_array( $current_screen->id, $this->wt_pages ) ) {

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/webtoffee-product-feed-admin.css', array(), $this->version, 'all' );
		}
		if(Webtoffee_Product_Feed_Sync_Common_Helper::wt_is_screen_allowed()){
                wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wt-product-feed-admin.css', array(), $this->version, 'all' );
        }
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Webtoffee_Product_Feed_Sync_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Webtoffee_Product_Feed_Sync_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$current_screen = get_current_screen();

		if ( isset( $current_screen->id ) && in_array( $current_screen->id, $this->wt_pages ) ) {

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/webtoffee-product-feed-admin.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-steps', plugin_dir_url( __FILE__ ) . 'js/jquery.steps.js', array( 'jquery' ), $this->version, false );
			$params=array(
			'nonces' => array(
		        'main' => wp_create_nonce(WEBTOFFEE_PRODUCT_FEED_ID),
		     ),
			'ajax_url' => admin_url('admin-ajax.php'),
			'plugin_id' =>WEBTOFFEE_PRODUCT_FEED_ID,
			'msgs'=>array(
				
				'error'=>__('Error.', 'webtoffee-product-feed'),
				'success'=>__('Success.', 'webtoffee-product-feed'),
				'loading'=>__('Loading...', 'webtoffee-product-feed'),
				'process' => __('Processing Sync...', 'webtoffee-product-feed'),
                                'sync_completed_success' => __('All the products have been synced successfully.', 'webtoffee-product-feed'),

			)
		);
		wp_localize_script($this->plugin_name, 'wt_feed_params', $params);
		}
		


            if(Webtoffee_Product_Feed_Sync_Common_Helper::wt_is_screen_allowed()){
		/* enqueue scripts */
		if(!function_exists('is_plugin_active'))
		{
			include_once(ABSPATH.'wp-admin/includes/plugin.php');
		}
		if(is_plugin_active('woocommerce/woocommerce.php'))
		{
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wt-product-feed-admin.js', array( 'jquery', 'jquery-tiptip'), $this->version, false );
		}else
		{
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wt-product-feed-admin.js', array( 'jquery'), $this->version, false );
			wp_enqueue_script(WEBTOFFEE_PRODUCT_FEED_ID.'-tiptip', WT_PRODUCT_FEED_PLUGIN_URL.'admin/js/tiptip.js', array('jquery'), WEBTOFFEE_PRODUCT_FEED_SYNC_VERSION, false);
		}
		
		$order_addon_active_status = false;
		$user_addon_active_status = false;
		if(is_plugin_active( 'order-import-export-for-woocommerce/order-import-export-for-woocommerce.php' )){
			$order_addon_active_status = true;
		}
		if(is_plugin_active( 'users-customers-import-export-for-wp-woocommerce/users-customers-import-export-for-wp-woocommerce.php' )){
			$user_addon_active_status = true;
		}
		

		$params=array(
			'nonces' => array(
		        'main' => wp_create_nonce(WEBTOFFEE_PRODUCT_FEED_ID),
		     ),
			'ajax_url' => admin_url('admin-ajax.php'),
			'plugin_id' =>WEBTOFFEE_PRODUCT_FEED_ID,
			'msgs'=>array(
				'settings_success'=>__('Settings updated.', 'webtoffee-product-feed'),
				'all_fields_mandatory'=>__('All fields are mandatory', 'webtoffee-product-feed'),
				'settings_error'=>__('Unable to update Settingss.', 'webtoffee-product-feed'),
                                'template_del_error'=>__('Unable to delete template', 'webtoffee-product-feed'),
                                'template_del_loader'=>__('Deleting template...', 'webtoffee-product-feed'),                             
				'value_empty'=>__('Value is empty.', 'webtoffee-product-feed'),
				'error'=> __('Something went wrong. Please reload the page and check', 'webtoffee-product-feed'),
				'success'=>__('Success.', 'webtoffee-product-feed'),
				'loading'=>__('Loading...', 'webtoffee-product-feed'),
				'sure'=>__('Are you sure?', 'webtoffee-product-feed'),
				'use_expression'=>__('Use expression as value.', 'webtoffee-product-feed'),
				'cancel'=>__('Cancel', 'webtoffee-product-feed'),
				'export_canceled' => __('Feed creation cancelled', 'webtoffee-product-feed'),
                                'copied_msg' => __('URL copied to clipboard', 'webtoffee-product-feed')
			)
                );
		wp_localize_script($this->plugin_name, 'wt_pf_basic_params', $params);
            }
		
		
		
	}

	/**
	 * Adds the Facebook menu item.
	 *
	 * @since 2.0.0
	 */
	public function add_menu_item() {

		//add_submenu_page( 'woocommerce', __( 'Facebook Catalog Manager', 'webtoffee-product-feed' ), __( 'Facebook Catalog', 'webtoffee-product-feed' ), 'manage_woocommerce', WT_Fb_Catalog_Manager_Settings::PAGE_ID, [ $this, 'render' ], 5 );
		//add_submenu_page( 'woocommerce', __( 'Facebook Category Mapping', 'webtoffee-product-feed' ), __( 'Facebook Category Mapping', 'webtoffee-product-feed' ), 'manage_woocommerce', 'wt-fbfeed-category-mapping', [ $this, 'wt_fbfeed_category_mapping' ], 6 );
		//add_submenu_page( 'woocommerce', __( 'Facebook Attribute Mapping', 'webtoffee-product-feed' ), __( 'Facebook Attribute Mapping', 'webtoffee-product-feed' ), 'manage_woocommerce', 'wt-fbfeed-attribute-mapping', [ $this, 'wt_fbfeed_attribute_mapping' ], 7 );
	}

	
	
	/**
	 * Show action links on the plugin screen.
	 *
	 * @param mixed $links Plugin action links.
	 *
	 * @return array
	 */
	public function add_productfeed_action_links( $links ) {

	$plugin_links = array(
		'<a href="' . esc_url( admin_url( 'admin.php?page=webtoffee_product_feed_main_export' ) ) . '">' . __( 'Settings', 'webtoffee-product-feed' ) . '</a>',
		'<a href="' . esc_url( admin_url( 'admin.php?page=webtoffee-product-feed' ) ) . '">' . __( 'FB Sync', 'webtoffee-product-feed' ) . '</a>',
		'<a target="_blank" href="https://wordpress.org/support/plugin/webtoffee-product-feed/">' . __( 'Support', 'webtoffee-product-feed' ) . '</a>',
		'<a target="_blank" href="https://www.webtoffee.com/webtoffee-product-feed-user-guide/">' . __( 'Documentation', 'webtoffee-product-feed' ) . '</a>',
		'<a target="_blank" href="https://wordpress.org/support/plugin/webtoffee-product-feed/reviews#new-post">' . __( 'Review', 'webtoffee-product-feed' ) . '</a>',
		'<a target="_blank" href="'. esc_url("https://www.webtoffee.com/product/woocommerce-product-feed/?utm_source=free_plugin_listing&utm_medium=feed_basic&utm_campaign=WooCommerce_Product_Feed&utm_content=" . WEBTOFFEE_PRODUCT_FEED_SYNC_VERSION).'" style="color:#3db634;">' . __( 'Upgrade', 'webtoffee-product-feed' ) . '</a>',
	);
	if ( array_key_exists( 'deactivate', $links ) ) {
		$links[ 'deactivate' ] = str_replace( '<a', '<a class="productfeed-deactivate-link"', $links[ 'deactivate' ] );
	}
	return array_merge( $plugin_links, $links );
}

	
	
	
	
	/**
	 * Gets the available tabs.
	 *
	 * @since 2.0.0
	 *
	 * @return tabs[]
	 */
	public function get_tabs() {

		$tabs			 = [
			'connection-manager' => __( 'Manage Connection', 'webtoffee-product-feed' ),
		];
		$is_connected	 = $this->is_connected();
		if ( $is_connected || !empty( ($_GET[ 'fb_access_token' ]) ) ) { // phpcs:ignore csrf ok.
			$tabs[ 'sync-products' ]	 = __( 'Sync Products', 'webtoffee-product-feed' );
			$tabs[ 'map-categories' ]	 = __( 'FB Category map', 'webtoffee-product-feed' );
			$tabs[ 'logs' ]			 = __( 'Logs', 'webtoffee-product-feed' );
		}
		return $tabs;
	}
	
		
	public function update_fb_connected_time( $value ) {

		update_option( WT_Fb_Catalog_Manager_Settings::OPTION_FB_CONNECTED_TIME, $value );
	}

	public function get_fb_connected_time() {

		return get_option( WT_Fb_Catalog_Manager_Settings::OPTION_FB_CONNECTED_TIME );
	}

	public function update_fb_access_token( $value ) {

		update_option( WT_Fb_Catalog_Manager_Settings::OPTION_ACCESS_TOKEN, $value );
	}

	public function update_fb_user_id( $value ) {

		update_option( WT_Fb_Catalog_Manager_Settings::OPTION_USER_ID, $value );
	}

	public function get_fb_user_id() {

		return get_option( WT_Fb_Catalog_Manager_Settings::OPTION_USER_ID );
	}

	public function update_fb_business_id( $value ) {

		update_option( WT_Fb_Catalog_Manager_Settings::OPTION_FB_BUSINESS_ID, $value );
	}

	public function update_fb_catalog_id( $value ) {

		update_option( WT_Fb_Catalog_Manager_Settings::OPTION_FB_CATALOG_ID, $value );
	}

	public function get_access_token() {

		$access_token = get_option( WT_Fb_Catalog_Manager_Settings::OPTION_ACCESS_TOKEN, '' );

		return $access_token;
	}

	public function get_fb_catalog_id() {

		return get_option( WT_Fb_Catalog_Manager_Settings::OPTION_FB_CATALOG_ID, '' );
	}

	public function is_connected() {

		return (bool) $this->get_access_token();
	}

	public function get_fb_catalog_details() {


		return $this->get_fb_catalog_id();

	}

	/**
	 * Disconnects the integration using the Graph API.
	 *
	 * @internal
	 *
	 * @since 2.0.0
	 */
	public function handle_disconnect() {

		check_admin_referer( WT_Fb_Catalog_Manager_Settings::DISCONNECT_ACTION );

		if ( !current_user_can( 'manage_woocommerce' ) ) {
			wp_die( __( 'You do not have permission to uninstall Facebook Business Extension.', 'webtoffee-product-feed' ) );
		}

		$user_id		 = $this->get_fb_user_id();
		$access_token	 = $this->get_access_token();

		$permission_revoke_url = "https://graph.facebook.com/{$user_id}/permissions?access_token={$access_token}";

		$response = wp_remote_request( $permission_revoke_url, array(
			'method' => 'DELETE'
		)
		);

		$this->update_fb_access_token( '' );
		$this->update_fb_user_id( '' );
		$this->update_fb_business_id( '' );
		$this->update_fb_catalog_id( '' );



		wp_safe_redirect( $this->get_settings_url() );
		exit;
	}

	public function get_settings_url() {

		return admin_url( 'admin.php?page=webtoffee-product-feed' );
	}

	/**
	 * Renders the settings page.
	 *
	 * @since 2.0.0
	 */
	public function render() {

		
		if ( !current_user_can( 'manage_woocommerce' ) ) {
			wp_die( __( 'You do not have permission to view', 'webtoffee-product-feed' ) );
		}
		
		$tabs = $this->get_tabs();

		$current_tab = !empty( ($_GET[ 'fbtab' ]) ) ? sanitize_text_field($_GET[ 'fbtab' ]) : '';


		if ( !$current_tab ) {
			$current_tab = current( array_keys( $tabs ) );
		}


		if ( !empty( ($_GET[ 'fb_access_token' ]) ) ) { // phpcs:ignore csrf ok.


			$fb_access_tkn =  isset($_GET[ 'fb_access_token' ]) ? sanitize_text_field( trim( $_GET[ 'fb_access_token' ] ) )  : '';
			$this->update_fb_access_token( $fb_access_tkn );
			$fb_access_uid =  isset($_GET[ 'fb_user_id' ]) ? sanitize_text_field( trim( $_GET[ 'fb_user_id' ] ) )  : '';
			$this->update_fb_user_id( $fb_access_uid );
			$fb_access_buid =  isset($_GET[ 'fb_business_id' ]) ? sanitize_text_field( trim( $_GET[ 'fb_business_id' ] ) )  : '';
			$this->update_fb_business_id( $fb_access_buid );

			if ( !empty( ($_GET[ 'fb_catalog_id' ]) ) ) { // phpcs:ignore csrf ok.
				$catalogs_data = isset( $_GET[ 'fb_catalog_id' ] ) ? (array) $_GET[ 'fb_catalog_id' ] : array();

				$this->update_fb_catalog_id( $catalogs_data );
			}
                        $this->update_fb_connected_time(time());
		}

		$is_connected = $this->is_connected();
		?>

		<div class="woocommerce ">


			<nav class="nav-tab-wrapper woo-nav-tab-wrapper" style="margin:0px;">

				<?php foreach ( $tabs as $id => $label ) : ?>
					<a href="<?php echo esc_html( admin_url( 'admin.php?page=' . WT_Fb_Catalog_Manager_Settings::PAGE_ID . '&fbtab=' . esc_attr( $id ) ) ); ?>" class="nav-tab wt-nav-tab <?php echo $current_tab === $id ? 'nav-tab-active wt-nav-tab-act' : ''; ?>"><?php echo esc_html( $label ); ?></a>
				<?php endforeach; ?>

			</nav>
			<div class="wt-fbfeed-tab-container">				

				<?php if ( 'connection-manager' === $current_tab ): ?>
				<h2 style="text-align:center;"><?php _e( 'Grow your store with Facebook Shops & Dynamic Ads', 'webtoffee-product-feed' ); ?></h2>
					<div class="actions">
						<?php
						if ( !$is_connected ):
							?>
						<div class="wt-fbfeed-tab-content" style="text-align:center;">
								<p><?php esc_html_e( 'You must connect with your FB business account as a pre-requisite to start synchronizing your products with Facebook.', 'webtoffee-product-feed' ); ?></p>
								<div class="not-connected-doc">
									<p><?php esc_html_e( 'If you haven\'t already set up a Facebook shop in your business account visit', 'webtoffee-product-feed' ); ?> <a target="_blank" href="https://www.facebook.com/business/help/268860861184453?id=1077620002609475"> <b><?php esc_html_e( 'this link', 'webtoffee-product-feed'); ?></b> </a> <?php esc_html_e( 'to set up one.', 'webtoffee-product-feed' ); ?> </p>									
									<p><?php esc_html_e( 'Use', 'webtoffee-product-feed'); ?> <a target="_blank" href="https://developers.facebook.com/docs/marketing-api/catalog-batch/reference#supported-fields-items-batch"><b><?php esc_html_e( 'this'); ?></b></a> <?php esc_html_e( 'reference to see which product fields will be synchronised by this plugin.', 'webtoffee-product-feed'); ?></p>
								</div>


								<?php
								$actions = [
									'get-started' => [
										'label'	 => __( 'Connect Facebook', 'webtoffee-product-feed' ),
										'type'	 => 'primary',
										'url'	 => $this->get_connect_url(),
									],
								];
								?>

								<img src="<?php echo esc_url(WT_PRODUCT_FEED_PLUGIN_URL.'/assets/images/undraw_social.svg'); ?>" alt="alt"/><br/>
								<?php foreach ( $actions as $action_id => $action ) : ?>

									<a
										href="<?php echo esc_url( $action[ 'url' ] ); ?>"
										style="background:#1877f2 !important; margin-top:5px;" class="button button-<?php echo esc_attr( $action[ 'type' ] ); ?>"
										<?php echo ( 'get-started' !== $action_id ) ? 'target="_blank"' : ''; ?>
										>
											<?php echo esc_html( $action[ 'label' ] ); ?>
									</a>
									<p></p>

								<?php endforeach; ?>

							</div>

						<?php endif; ?>


						<?php
						if ( $is_connected ) :
							$catalog_details = $this->get_fb_catalog_details();
							?>

						<div class="catalog-connected-section" style="background: #ebf3f7;padding: 10px;">
							<!--<p><?php //esc_html_e( 'You are currently connected to your FB account.', 'webtoffee-product-feed' ); ?></p>-->
							<?php if ( isset( $catalog_details ) ): ?>
							<div class="catalogs-list-section">
								<div class="dashicons-before dashicons-cart" style="float:left; width: 25%;"><?php esc_html_e( 'Catalogs associated with your FB account:', 'webtoffee-product-feed'); ?></div>
							<div class="fb-catalog-list"  style="float:right; width: 75%;">	
								<?php
									if ( !empty( $catalog_details ) ) {
										$ic = 0;
										foreach ( $catalog_details as $catalog_id => $catalog_name ):
											if ( $ic !== 0 )
												echo '<br/>';
											?>

									<b><a target="_blank" href="<?php echo "https://facebook.com/products/catalogs/" . $catalog_id; ?>"><?php echo esc_attr($catalog_name); ?></a></b>								
											<?php
											$ic++;
										endforeach;
									}
									?>
								</div>
							</div>
							<?php else: ?>
								<p><?php esc_html_e( 'Something went wrong with the connection establishment to catalogs, please refresh or try disconnecting and connect again to FB', 'webtoffee-product-feed'); ?></p>
							<?php endif; ?>

							<br/>
							<div class="clearfix"></div>
							<div class="catalog-doc-section">
							<p><?php esc_html_e( 'Use', 'webtoffee-product-feed'); ?> <a target="_blank" href="https://developers.facebook.com/docs/marketing-api/catalog-batch/reference#supported-fields-items-batch"><b><?php esc_html_e( 'this'); ?></b></a> <?php esc_html_e( 'reference to see which product fields will be synchronised by this plugin.', 'webtoffee-product-feed'); ?></p>
							<br/>
							<p></p>
							<p></p>
							</div>
						</div>
						<div class="catalog-diconnect-btn" style="padding:10px;float: right;">
							
							<?php 
							$revoke = false;
							$fb_connected_time = $this->get_fb_connected_time();
							if ('' != $fb_connected_time && ($fb_connected_time + (86400 * 60)) <= time()) {
								$revoke = __('The connection with your Facebook business account has expired. Please reconnect to enable product syncing.', 'webtoffee-product-feed');
							}
							if($revoke){ ?>
							<p style="float:left;margin-right: 10px;padding:5px;" class="notice notice-error"><?php echo esc_html($revoke);?></p>
							<?php }
							?>
							
							<a href="<?php echo esc_url( $this->get_disconnect_url() ); ?>" class="uninstall button button-add-media" style="margin-top:5px; padding-top: 1px;padding-bottom: 1px;">
								<?php 													
								if ($revoke) {
									esc_html_e('Reconnect FB', 'webtoffee-product-feed');
								} else {
									esc_html_e('Disconnect FB', 'webtoffee-product-feed');
								}
								?>
							</a>
						</div>

						<?php endif; ?>
					</div>




				<?php elseif ( 'sync-products' === $current_tab ): ?>

					<?php
					if ( $is_connected ) :
						$wc_path			 = self::wt_get_wc_path();
						wp_enqueue_script( 'wc-enhanced-select' );
						wp_enqueue_style( 'woocommerce_admin_styles', $wc_path . '/assets/css/admin.css' );
						?>
						<p id="sync-loader" style="text-align:center">
							<i><?php esc_html_e( 'Fetching the product categories, tags for sync with your FB Catalog ...', 'webtoffee-product-feed' ); ?></i>
							<span class="spinner is-active" style="float:none; margin-top: -3px;"></span>
						<p>
						<div class="sync-product-tab" style="display: none;">


							<div style="float:left;width:60%;">
							<div id="example-basic">

								<h3><?php esc_html_e( 'Filter Products', 'webtoffee-product-feed' ); ?></h3>

								<section>
									<h2><?php esc_html_e( 'Filter products for FB sync', 'webtoffee-product-feed' ); ?></h2>
									<h4><?php esc_html_e( 'Filter data necessary for sync with my FB Catalog as per the below criteria by excluding non-relevant products.', 'webtoffee-product-feed' ); ?></h4>
									<form action="" name="sync_products" id="sync_products" class="sync_products" method="post" autocomplete="off">	
										<?php wp_nonce_field( 'wt-sync-products' ); ?>
										<input type="hidden" name="wt_batch_hash_key" id="wt_batch_hash_key" value="<?php echo wp_generate_uuid4(); ?>"/>					
										<table class="form-table">
											<tr>
												<th><?php esc_html_e( 'Select FB Catalog', 'webtoffee-product-feed' ); ?></th>
												<td>
													<select name="wt_sync_selected_catalog"  >
														<?php
														$product_catalogs	 = $this->get_fb_catalog_details();
														foreach ( $product_catalogs as $catalog_id => $catalog_name ) {
															?>
														<option value="<?php echo $catalog_id; ?>" ><?php echo esc_attr($catalog_name); ?></option>								
															<?php
														}
														?>

													</select>
												</td>
											</tr>
											<tr>
												<th><?php esc_html_e( 'Exclude Product Categories', 'webtoffee-product-feed' ); ?></th>
												<td>
													<select name="wt_sync_exclude_category[]" class="wc-enhanced-select" multiple="multiple" data-placeholder ="<?php echo __( 'Search for a product category&hellip;', 'webtoffee-product-feed' ); ?>" >
														<?php
														$product_categories = $this->get_product_categories();
														foreach ( $product_categories as $category_id => $category_name ) {
															?>
														<option value="<?php echo $category_id; ?>" ><?php echo esc_attr($category_name); ?></option>								
															<?php
														}
														?>

													</select>
												</td>
											</tr>
                                                                                        <tr>
												<th><?php esc_html_e( 'Only include specific categories', 'webtoffee-product-feed' ); ?></th>
												<td>
													<select name="wt_sync_include_category[]" class="wc-enhanced-select" multiple="multiple" data-placeholder ="<?php echo __( 'Search for a product category&hellip;', 'webtoffee-product-feed' ); ?>" >
														<?php														
														foreach ( $product_categories as $category_id => $category_name ) {
															?>
														<option value="<?php echo $category_id; ?>" ><?php echo esc_attr($category_name); ?></option>								
															<?php
														}
														?>

													</select>
												</td>
											</tr>

											<tr>
												<th><?php esc_html_e( 'Exclude Product Tags', 'webtoffee-product-feed' ); ?></th>
												<td>
													<select name="wt_sync_exclude_tags[]" class="wc-enhanced-select" multiple="multiple" data-placeholder ="<?php echo __( 'Search for a product tag&hellip;', 'webtoffee-product-feed' ); ?>" >
														<?php
														$product_tags = $this->get_product_tags();
														foreach ( $product_tags as $product_tag_id => $product_tag_name ) {
															?>
															<option value="<?php echo $product_tag_id; ?>" ><?php echo esc_attr($product_tag_name); ?></option>								
															<?php
														}
														?>

													</select>
												</td>
											</tr>
											
									<tr>
										<th><label><?php esc_html_e('Product description type', 'webtoffee-product-feed'); ?></label>
										</th>
										<td>
											<?php
											$product_descriptions = apply_filters('wt_pf_catalog_product_description_type', array(
												'short' => __('Short', 'webtoffee-product-feed'),
												'long' => __('Long', 'webtoffee-product-feed'),
											));
											?>
											<select name="wt_sync_product_desc_type" id="wt_sync_product_desc_type">
												<?php
												foreach ($product_descriptions as $key => $value) {
													?>
													<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
													<?php
												}
												?>
											</select>
										</td>
									</tr>											
											
											<tr>
												<th><?php esc_html_e( 'Products per batch', 'webtoffee-product-feed' ); ?></th>
												<td>

													<input type="text" name="wt_sync_batch_count" value="10" /><br/><br/>
													<i><?php esc_html_e( 'The number of records that the server will process for every iteration within the available server timeout interval. If the process fails you can lower this number accordingly and try again. Defaulted to 10 records. Maximum number allowed as per the Facebook limits is 5000.', 'webtoffee-product-feed' ); ?></i>
												</td>
											</tr>
				<!--									<tr>
												<td style="padding-left:0px;">
													<button name="syncproducts" id="syncproducts" type="submit" class="button button-large button-primary edd-export-form"><?php //esc_html_e( 'Sync Products', 'webtoffee-product-feed' ); ?></button>
												</td>
											</tr>-->
										</table>

									</form>

								</section>
								<h3><?php esc_html_e( 'Map Categories', 'webtoffee-product-feed' ); ?></h3>
								<section id="category-section">													
									<?php
									$ajax_render = true;
									require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/fbcatalog/partials/wt-fbfeed-category-mapping.php';
									?>
								</section>
								<!--												<h3>Pager</h3>
																				<section>
																					<p>The next and previous buttons help you to navigate through your content.</p>
																					<button name="syncproducts" id="syncproducts" type="submit" class="button button-large button-primary edd-export-form"><?php esc_html_e( 'Sync Products', 'webtoffee-product-feed' ); ?></button>
																				</section>-->
							</div>





							<style>
								.edd-progress div{width:0px;background:#1877f2;height:10px}
							</style>
						</div>
							<div style="float:right;border: 1px solid #ccc;width:25%">
							<?php 
							$fb_sync_tab = true;
							include plugin_dir_path(WT_PRODUCT_FEED_PLUGIN_FILENAME).'admin/views/market.php'; 							
							?>
							</div>
						</div>

					<?php endif; ?>
					<?php elseif ( 'map-categories' === $current_tab ):     
                                                /* enqueue scripts */
                                                if(!function_exists('is_plugin_active'))
                                                {
                                                        include_once(ABSPATH.'wp-admin/includes/plugin.php');
                                                }
                                                if(is_plugin_active('woocommerce/woocommerce.php'))
                                                { 
                                                        wp_enqueue_script('wc-enhanced-select');
                                                        wp_enqueue_style('woocommerce_admin_styles', WC()->plugin_url().'/assets/css/admin.css');
                                                }else
                                                {
                                                        wp_enqueue_style(WEBTOFFEE_PRODUCT_FEED_ID.'-select2', WT_PRODUCT_FEED_PLUGIN_URL. 'admin/css/select2.css', array(), WEBTOFFEE_PRODUCT_FEED_SYNC_VERSION, 'all' );
                                                        wp_enqueue_script(WEBTOFFEE_PRODUCT_FEED_ID.'-select2', WT_PRODUCT_FEED_PLUGIN_URL.'admin/js/select2.js', array('jquery'), WEBTOFFEE_PRODUCT_FEED_SYNC_VERSION, false );
                                                }							
						$this->wt_fbfeed_category_mapping();
					?>
					
							<?php else: ?>
					<div class="wt-fbfeed-tab-content">			
						<?php

						$this->list_batch_logs();
						
						?>
					</div>
		<?php endif; ?>
			</div>
		</div>

		<?php
	}

	/**
	 * Get WC Plugin path without fail on any version
	 */
	public static function wt_get_wc_path() {
		
		
		return ( function_exists( 'WC' ) ) ? WC()->plugin_url() : plugins_url() . '/woocommerce';
	}

	/**
	 * Gets the product categories.
	 * 
	 * @return array
	 */
	public function get_product_categories() {

		$term_query = new \WP_Term_Query( [
			'taxonomy'	 => 'product_cat',
			'hide_empty' => false,
			'fields'	 => 'id=>name',
		] );

		$product_categories = $term_query->get_terms();
		return is_array( $product_categories ) ? $product_categories : [];
	}

	/**
	 * Gets the product tags.
	 *
	 * @return array
	 */
	public function get_product_tags() {


		$term_query = new \WP_Term_Query( [
			'taxonomy'		 => 'product_tag',
			'hide_empty'	 => false,
			'hierarchical'	 => false,
			'fields'		 => 'id=>name',
		] );

		$product_tags = $term_query->get_terms();
		return is_array( $product_tags ) ? $product_tags : [];
	}

	/**
	 * Gets the URL for connecting.
	 * 
	 * @return string
	 */
	public function get_connect_url() {

		return add_query_arg( rawurlencode_deep( $this->get_connect_parameters() ), WT_Fb_Catalog_Manager_Settings::OAUTH_URL );
	}

	/**
	 * Gets the URL for disconnecting.
	 *
	 * @return string
	 */
	public function get_disconnect_url() {

		return wp_nonce_url( add_query_arg( 'action', WT_Fb_Catalog_Manager_Settings::DISCONNECT_ACTION, admin_url( 'admin.php' ) ), WT_Fb_Catalog_Manager_Settings::DISCONNECT_ACTION );
	}

	public function get_connect_parameters() {

		/**
		 * Filters the connection parameters.
		 *
		 * @param array $parameters connection parameters
		 */
		return apply_filters( 'wt_facebook_connection_parameters', [
			'client_id'		 => $this->get_client_id(),
			'redirect_uri'	 => "https://fbconnect.webtoffee.com/",
			'state'			 => admin_url( 'admin.php?page=webtoffee-product-feed' ), //?nonce=' ).wp_create_nonce( WT_Fb_Catalog_Manager_Settings::CONNECT_ACTION ), //$this->get_redirect_url(),
			'display'		 => 'page',
			'response_type'	 => 'code',
			'scope'			 => implode( ',', $this->get_scopes() ),
			'extras'		 => json_encode( $this->get_connect_parameters_extras() ),
		] );
	}

	public function wt_fbfeed_attribute_mapping() {


		if ( count( $_POST ) && isset( $_POST[ 'map_to_attr' ] ) ) {

			check_admin_referer( 'wt-attribute-mapping' );

			$mapping_option = 'wt_fbfeed_attribute_mapping';

			$mapping_data = array_map( 'absint', ($_POST[ 'map_to_attr' ]) );
			foreach ( $mapping_data as $local_attribute_id => $fb_attribute_id ) {
				if ( $fb_attribute_id )
					update_term_meta( $local_category_id, 'wt_fb_category', $fb_attribute_id );
			}

			// Delete product categories dropdown cache
			wp_cache_delete( 'wt_fbfeed_dropdown_product_attributes' );

			if ( update_option( $mapping_option, $mapping_data, false ) ) { // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
				update_option( 'wt_mapping_message', esc_html__( 'Mapping Added Successfully', 'webtoffee-product-feed' ), false );
				wp_safe_redirect( admin_url( 'admin.php?page=wt-fbfeed-attribute-mapping&wt_mapping_message=success' ) );
				die();
			} else {
				update_option( 'wt_mapping_message', esc_html__( 'Failed To Add Mapping', 'webtoffee-product-feed' ), false );
				wp_safe_redirect( admin_url( 'admin.php?page=wt-fbfeed-attribute-mapping&wt_mapping_message=error' ) );
				die();
			}
		}
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/fbcatalog/partials/wt-fbfeed-attribute-mapping.php';
	}

	private function get_client_id() {

		return WT_Fb_Catalog_Manager_Settings::CLIENT_ID;
	}

	public function get_batch_limit() {

		return apply_filters( "wt_fbfeed_upload_limit", $this->batch_limit, $this );
	}

	public function get_total_exported() {
		return ( ( $this->get_page() - 1 ) * $this->get_limit() ) + $this->exported_row_count;
	}

	public function get_percent_complete( $found_posts, $step, $limit ) {
		return $found_posts ? floor( ( ($step * $limit) / $found_posts ) * 100 ) : 100;
	}

	/**
	 * Gets the scopes that will be requested during the connection flow.
	 *
	 * @since 2.0.0
	 *
	 * @link https://developers.facebook.com/docs/marketing-api/access/#access_token
	 *
	 * @return string[]
	 */
	public function get_scopes() {

		$scopes = [
			'catalog_management',
			'business_management',
		//'ads_management',
		//'ads_read',
		];


		return (array) apply_filters( 'wt_facebook_connection_scopes', $scopes, $this );
	}

	private function get_connect_parameters_extras() {

		$parameters = [
			'setup'				 => [
				'external_business_id'	 => $this->get_external_business_id(),
				'timezone'				 => $this->get_timezone_string(),
				'currency'				 => get_woocommerce_currency(),
				'business_vertical'		 => 'ECOMMERCE',
			],
			'business_config'	 => [
				'business' => [
					'name' => $this->get_business_name(),
				],
			],
			'repeat'			 => false,
		];


		return $parameters;
	}

	public function get_external_business_id() {

		if ( !is_string( $this->external_business_id ) ) {

			$value = get_option( WT_Fb_Catalog_Manager_Settings::OPTION_EXTERNAL_BUSINESS_ID );

			if ( !is_string( $value ) ) {

				$value = uniqid( sanitize_title( $this->get_business_name() ) . '-', false );

				update_option( WT_Fb_Catalog_Manager_Settings::OPTION_EXTERNAL_BUSINESS_ID, $value );
			}

			$this->external_business_id = $value;
		}


		return (string) apply_filters( 'wt_facebook_external_business_id', $this->external_business_id, $this );
	}

	public function get_business_name() {

		$business_name = get_bloginfo( 'name' );

		$business_name = trim( (string) apply_filters( 'wt_facebook_connection_business_name', is_string( $business_name ) ? $business_name : '' ) );

		if ( empty( $business_name ) ) {
			$business_name = get_bloginfo( 'url' );
		}

		return html_entity_decode( $business_name, ENT_QUOTES, 'UTF-8' );
	}

	private function get_timezone_string() {

		$timezone = wc_timezone_string();

		if ( preg_match( '/([+-])(\d{2}):\d{2}/', $timezone, $matches ) ) {

			$hours		 = (int) $matches[ 2 ];
			$timezone	 = "Etc/GMT{$matches[ 1 ]}{$hours}";
		}

		return $timezone;
	}

		public function wt_fbfeed_category_mapping() {


		if ( count( $_POST ) && isset( $_POST[ 'map_to' ] ) ) {

			check_admin_referer( 'wt-category-mapping' );

			$mapping_option = 'wt_fbfeed_category_mapping';

			$mapping_data = array_map( 'absint', ($_POST[ 'map_to' ]) );
			foreach ( $mapping_data as $local_category_id => $fb_category_id ) {
				if ( $fb_category_id )
					update_term_meta( $local_category_id, 'wt_fb_category', $fb_category_id );
			}

			// Delete product categories dropdown cache
			wp_cache_delete( 'wt_fbfeed_dropdown_product_categories' );

			if ( update_option( $mapping_option, $mapping_data, false ) ) { // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
				update_option( 'wt_mapping_message', esc_html__( 'Mapping Added Successfully', 'webtoffee-product-feed' ), false );
				wp_safe_redirect( admin_url( 'admin.php?page=webtoffee-product-feed&fbtab=map-categories&wt_mapping_message=success' ) );
				die();
			} else {
				update_option( 'wt_mapping_message', esc_html__( 'Failed To Add Mapping', 'webtoffee-product-feed' ), false );
				wp_safe_redirect( admin_url( 'admin.php?page=webtoffee-product-feed&fbtab=map-categories&wt_mapping_message=error' ) );
				die();
			}
		}
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/fbcatalog/partials/wt-fbfeed-category-mapping.php';
	}

	/**
	 * Process batch exports via ajax
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function wt_fbfeed_ajax_upload() {


		parse_str( $_POST[ 'form' ], $form );

		$_REQUEST	 = $form		 = (array) $form;
		check_admin_referer( 'wt-sync-products' );
		$step		 = absint( $_POST[ 'step' ] );

		$wt_batch_hash_key = sanitize_text_field( $_REQUEST[ 'wt_batch_hash_key' ] );

		$wt_sync_selected_catalog = sanitize_text_field( $_REQUEST[ 'wt_sync_selected_catalog' ] );
		$wt_sync_product_desc_type		 = isset( $_REQUEST[ 'wt_sync_product_desc_type' ] ) ? sanitize_text_field( $_REQUEST[ 'wt_sync_product_desc_type' ] ) : 'short';


		$wt_sync_exclude_category	 = isset( $_REQUEST[ 'wt_sync_exclude_category' ] ) ? array_map( 'absint', ($_REQUEST[ 'wt_sync_exclude_category' ]) ) : [];
                $wt_sync_include_category	 = isset( $_REQUEST[ 'wt_sync_include_category' ] ) ? array_map( 'absint', ($_REQUEST[ 'wt_sync_include_category' ]) ) : [];
  
		$wt_sync_exclude_tags		 = isset( $_REQUEST[ 'wt_sync_exclude_tags' ] ) ? array_map( 'absint', ($_REQUEST[ 'wt_sync_exclude_tags' ]) ) : [];
		$wt_sync_batch_count		 = isset( $_REQUEST[ 'wt_sync_batch_count' ] ) ? absint( $_REQUEST[ 'wt_sync_batch_count' ] ) : $this->get_batch_limit();

		if ( $wt_sync_batch_count == 0 || $wt_sync_batch_count > 5000 ) {
			$wt_sync_batch_count = $this->get_batch_limit();
		}


		$product_data	 = [];
		$wc_fbfeed		 = new WT_Facebook_Catalog_Product();
		$wc_fbfeed->sync_description_type = $wt_sync_product_desc_type;
		$args			 = array(
			'post_type'		 => array( 'product', 'product_variation' ),
			'post_status'	 => array( 'publish' ),
			'posts_per_page' => $wt_sync_batch_count,
			'offset'		 => ($step - 1) * $wt_sync_batch_count,
			'fields'		 => 'ids',
		/*
		  'tax_query'		 => array(
		  array(
		  'taxonomy'	 => 'product_type',
		  'field'		 => 'slug',
		  'terms'		 => array('simple', 'variable', 'variation'),
		  'operator'	 => 'IN'
		  ) )
		 * 
		 */
		);

		if ( !empty( $wt_sync_exclude_category ) ) {
			$args[ 'tax_query' ][] = array(
				'taxonomy'	 => 'product_cat',
				'terms'		 => $wt_sync_exclude_category, // Term ids to be excluded
				'operator'	 => 'NOT IN' // Excluding terms
			);
		}
                if ( !empty( $wt_sync_include_category ) ) {
			$args[ 'tax_query' ][] = array(
				'taxonomy'	 => 'product_cat',
				'terms'		 => $wt_sync_include_category, // Term ids to be included
				'operator'	 => 'IN' // Including terms
			);
		}


		if ( !empty( $wt_sync_exclude_tags ) ) {
			$args[ 'tax_query' ][] = array(
				'taxonomy'	 => 'product_tag',
				'terms'		 => $wt_sync_exclude_tags, // Term ids to be excluded
				'operator'	 => 'NOT IN' // Excluding terms
			);
		}
		if ( !empty( $wt_sync_exclude_category ) || !empty( $wt_sync_exclude_tags ) ) {
			$args[ 'tax_query' ][ 'relation' ] = 'AND';
		}

		$loop = new WP_Query( $args );
		$process_products = apply_filters('wt_facebook_sync_products', $loop->posts);

                foreach ( $process_products as $product_id ) {
                    
			$product_item_data = $wc_fbfeed->process_item_update( $product_id );

			if ( !empty( $product_item_data[ 'data' ][ 'price' ] ) ) {
				$product_data[] = $product_item_data;
			}
                        if($wt_sync_include_category){
                            $product = wc_get_product($product_id);
                            if (($product->is_type('variable') || $product->has_child())) {
                                $children_ids = $product->get_children();
                                if (!empty($children_ids)) {
                                    foreach ($children_ids as $id) {                                
                                        if(!in_array($id, $process_products)){  // skipping if alredy processed in $products_ids  
                                            
                                            $product_item_data = $wc_fbfeed->process_item_update( $id );

                                            if ( !empty( $product_item_data[ 'data' ][ 'price' ] ) ) {
                                                    $product_data[] = $product_item_data;
                                            }

                                        }
                                    }
                                }                        
                            }
                        }
		}
                                                               
		$catalog_access_token = $this->get_access_token();

		$request_body	 = [
			"headers"	 => [
				"Authorization"	 => "Bearer {$catalog_access_token}",
				"Content-type"	 => "application/json",
				"accept"		 => "application/json" ],
			"body"		 => json_encode( [
				"allow_upsert"	 => true,
				"item_type"		 => "PRODUCT_ITEM",
				"requests"		 => json_encode( $product_data )
			] ),
		];
		$catalog_id		 = $wt_sync_selected_catalog; //$this->get_fb_catalog_id();
                
		// Each bacth process the batch_limit		
		if ( !empty( $product_data ) ) {

			$this->wt_log_data_change( 'wt-feed-upload', 'Requested Product Data:' );
			$this->wt_log_data_change( 'wt-feed-upload', print_r( $product_data, 1 ) );

			//$catalog_id				 = $this->get_fb_catalog_id();
			#$batch_url				 = "https://graph.facebook.com/v17.0/$catalog_id/batch";
			$items_batch			 = "https://graph.facebook.com/v17.0/$catalog_id/items_batch";
			#$single_product_url	 = "https://graph.facebook.com/v17.0/$catalog_id/products";
			$batch_response			 = wp_remote_post( $items_batch, $request_body );
			$this->wt_log_data_change( 'wt-feed-upload', 'Batch Response:' );
			$this->wt_log_data_change( 'wt-feed-upload', print_r( $batch_response, 1 ) );
			$batch_response_details	 = wp_remote_retrieve_body( $batch_response );
			$batch_response_details	 = json_decode( $batch_response_details );

                        if ( isset( $batch_response_details->handles[ 0 ] ) ) {
                            global $wpdb;
                            $table_name = $wpdb->prefix.'wt_pf_fbsync_log';

                            // First batch insert log
                            $batch_pocess_log = array();
                            if($step <= 1){
                                $batch_pocess_log[ $wt_batch_hash_key ][] = [
                                            'batch_time'	 => date( 'Y-m-d: H:i:s' ),
                                            'batch_handle'	 => $batch_response_details->handles[ 0 ],
                                            'catalog_id'	 => $catalog_id
                                    ];
                                $insert_data=array(
                                        'catalog_id'=>$catalog_id,
                                        'data'=>maybe_serialize($batch_pocess_log),
                                        'start_time'=>date( 'Y-m-d H:i:s' ), 

                                );
                                $insert_data_type=array('%s', '%s', '%s');

                                $wpdb->insert($table_name, $insert_data, $insert_data_type);
                            }else{
                                // All other batch update last log row
                                $last_log = $wpdb->get_row( 'SELECT * FROM ' . $table_name . ' ORDER BY id DESC LIMIT 1');
                                $batch_pocess_log = maybe_unserialize($last_log->data);

                                $batch_pocess_log[ $wt_batch_hash_key ][] = [
                                            'batch_time'	 => date( 'Y-m-d: H:i:s' ),
                                            'batch_handle'	 => $batch_response_details->handles[ 0 ],
                                            'catalog_id'	 => $catalog_id
                                    ];
                                $update_data=array(
                                        'id' => $last_log->id,
                                        'catalog_id'=>$catalog_id,
                                        'data'=>maybe_serialize($batch_pocess_log),
                                        'start_time'=>date( 'Y-m-d H:i:s' )

                                );
                                $wpdb->update($table_name, $update_data, array('id' => $last_log->id));
                            }    				
				
			}
		}
		//exit;

		$percentage_completed = $this->get_percent_complete( $loop->found_posts, $step, $wt_sync_batch_count );

		if ( $percentage_completed !== 100 ) {

			$step += 1;
			echo json_encode( array( 'step' => $step, 'percentage' => $percentage_completed, 'products' => $loop->found_posts, ) );
			exit;
		} else {


			echo json_encode( array( 'step' => 'done', 'percentage' => 100, 'url' => admin_url( 'admin.php?page=webtoffee-product-feed' ), 'catalog' => 'https://facebook.com/products/catalogs/' . $catalog_id . '/products', 'url' => admin_url( 'admin.php?page=webtoffee-product-feed' ) ) );
			exit;
		}
	}

	public function wt_log_data_change( $content = 'wt-feed-upload', $data = '' ) {


		if ( WC()->version < '2.7.0' ) {
			$this->log = new WC_Logger();
		} else {
			$this->log = wc_get_logger();
		}

		if ( WC()->version < '2.7.0' ) {
			$this->log->add( $content, $data );
		} else {
			$context = array( 'source' => $content );
			$this->log->log( "debug", $data, $context );
		}
	}

	public function get_batch_status( $handle, $fb_catalog_id ) {

		$access_token = $this->get_access_token();

		$batch_status_handle_check_url	 = "https://graph.facebook.com/v17.0/$fb_catalog_id/check_batch_request_status?handle=$handle&access_token=$access_token&load_ids_of_invalid_requests=1";
		$batch_status_response			 = wp_remote_get( $batch_status_handle_check_url );
		$batch_status_response_details	 = wp_remote_retrieve_body( $batch_status_response );
		$batch_status_response_details	 = json_decode( $batch_status_response_details );

		$this->wt_log_data_change( 'wt-feed-upload', 'Batch Status Response:' );
		$this->wt_log_data_change( 'wt-feed-upload', print_r( $batch_status_response, 1 ) );

		return $batch_status_response_details;
	}

	public function list_batch_logs() {

		?>

		<div class="wt_fbfeed_history_page">
			<h2 class="wp-heading-inline"><?php _e( 'Logs', 'webtoffee-product-feed' ); ?></h2>
			<p>
		<?php _e( 'Lists log of failed product syncs, mostly required for debugging purposes.', 'webtoffee-product-feed' ); ?>
				<span><a target="_blank" href="<?php echo admin_url( 'admin.php?page=wc-status&tab=logs' ); ?>"><?php _e( 'Uploaded data logs', 'webtoffee-product-feed' ); ?></a> ( <i><?php _e( 'The log file name starts with wt-feed-upload', 'webtoffee-product-feed' ); ?></i> )</span>
			</p>

		<?php
                // List all batch logs here
                global $wpdb;
                $table_name = $wpdb->prefix.'wt_pf_fbsync_log';
                $sync_log_exist = true;
                if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                    $sync_log_exist = false;
                }
                $log_list = ($sync_log_exist) ? $wpdb->get_results( 'SELECT * FROM ' . $table_name . ' ORDER BY id DESC') : array();

                if ( is_array( $log_list ) && count( $log_list ) > 0 ) {
			?>
				<table class="wp-list-table widefat fixed striped history_list_tb log_list_tb">
					<thead>
						<tr>
							<th class="log_file_name_col"><?php _e( "Batch Started at" ); ?></th>
							<th><?php _e( "Action" , 'webtoffee-product-feed' ); ?></th>
						</tr>
					</thead>
                                        <tbody>
			<?php
                         foreach ($log_list as $key => $log_list_handles) {

                             ?>
                            <tr><td></td><td></td></tr>
                            <?php 
                            $catalog_details = $this->get_fb_catalog_details();
                            $catalog_name = $log_list_handles->catalog_id; 
                            if(isset($catalog_details[$log_list_handles->catalog_id])){
                                $catalog_name = $catalog_details[$log_list_handles->catalog_id]; 
                            }
                            ?>
                            <tr><td><?php esc_html_e('Catalog: '); echo esc_html( $catalog_name ); ?></td><td><?php esc_html_e('Started at:'); echo esc_html( $log_list_handles->start_time ); ?></td></tr>                               
                                            
                           <?php
                             $log_list_single_batch = maybe_unserialize($log_list_handles->data); 
                                foreach ( $log_list_single_batch as $h_key => $log_list_details ) :
                                foreach ( $log_list_details as $key => $single_batch_log ) :
                                ?>						
				<?php

					if ( isset( $single_batch_log[ 'batch_handle' ] ) ) {
						?>
									<tr>
										<td class="log_file_name_col"><span class="wt_fbfeed_view_log_name" data-log-file="<?php echo esc_attr( $single_batch_log[ 'batch_handle' ] ); ?>"><?php echo esc_attr( $single_batch_log[ 'batch_time' ] ); ?></span></td>
										<td>

											<a class="wt_fbfeed_view_log_btn" data-batch-handle="<?php echo esc_attr( $single_batch_log[ 'batch_handle' ] ); ?>" data-batch-handle-catalog="<?php echo esc_attr( $single_batch_log[ 'catalog_id' ] ); ?>"><?php _e( "View Status", 'webtoffee-product-feed' ); ?></a>

										</td>
									</tr>
						<?php
					}
				
				?>
						
			<?php   endforeach;
                            endforeach; ?>
                         <?php
                         }
                        ?>
                         </tbody>
				</table>
						<?php
					} else {
						?>
				<h4 class="wt_fbfeed_history_no_records"><?php _e( "No logs found.", 'webtoffee-product-feed' ); ?></h4>
					<?php
				}
				?>

			<div class="wt_fbfeed_view_log wt_fbfeed_popup">
				<div class="wt_fbfeed_popup_hd">
					<span style="line-height:40px;" class="dashicons dashicons-media-text"></span>
					<span class="wt_fbfeed_popup_hd_label"><?php esc_html_e( 'View log', 'webtoffee-product-feed' ); ?></span>
					<div class="wt_fbfeed_popup_close">X</div>
				</div>
				<div class="wt_fbfeed_log_container">

				</div>
			</div>



		</div>

		<?php
	}

	public function wt_fbfeed_batch_status() {

		if ( !empty( $_POST[ 'batch_handle' ] ) ) {
			$nonce = (isset($_POST['_wpnonce']) ? sanitize_text_field($_POST['_wpnonce']) : '');
			if(!wp_verify_nonce($nonce, WEBTOFFEE_PRODUCT_FEED_ID)){
				return false;
			}
			$handle				 = sanitize_text_field( $_POST[ 'batch_handle' ] );
			$catalog_id			 = sanitize_text_field( $_POST[ 'catalog_id' ] );
			$batch_status_data	 = $this->get_batch_status( $handle, $catalog_id );

			if ( isset( $batch_status_data->data ) ) {
				wp_send_json( [ "errors" => $batch_status_data->data[ 0 ]->errors, "status" => $batch_status_data->data[ 0 ]->status, 'batch_response' => $batch_status_data->data[ 0 ], 'ids_of_invalid_requests' => $batch_status_data->data[ 0 ]->ids_of_invalid_requests ] );
                                
			}elseif (isset($batch_status_data->error)) {
                            
                                wp_send_json( [ "errors" => __( $batch_status_data->error->message, 'webtoffee-product-feed'), "status" => 'failed', 'batch_response' => '']);
                        } else {

				wp_send_json( [ "errors" => __( 'The request could not be handled', 'webtoffee-product-feed' ), "status" => 'cancelled' , 'batch_response' => ''] );			
			}

			exit;
		}
	}

	/**
	 * Process batch exports via ajax
	 *
	 * @since 1.0.0
	 * @return void
	 */
	function wt_fbfeed_ajax_save_category() {


		parse_str( $_POST[ 'form' ], $form );

		$_REQUEST	 = $form		 = (array) $form;
		check_admin_referer( 'wt-category-mapping' );


		$mapping_option = 'wt_fbfeed_category_mapping';

		$map_to_cats = !empty(($_REQUEST[ 'map_to' ])) ? ($_REQUEST[ 'map_to' ]) : array();
		$mapping_data = array_map( 'absint',  $map_to_cats);
		
		foreach ( $mapping_data as $local_category_id => $fb_category_id ) {
			if ( $fb_category_id )
				update_term_meta( $local_category_id, 'wt_fb_category', $fb_category_id );
		}

		// Delete product categories dropdown cache
		wp_cache_delete( 'wt_fbfeed_dropdown_product_categories' );

		if ( update_option( $mapping_option, $mapping_data, false ) ) { // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
			update_option( 'wt_mapping_message', esc_html__( 'Mapping Added Successfully', 'webtoffee-product-feed' ), false );
			echo json_encode( array( 'step' => 'done', 'message' => 'success' ) );
		} else {
			update_option( 'wt_mapping_message', esc_html__( 'Failed To Add Mapping', 'webtoffee-product-feed' ), false );
			echo json_encode( array( 'step' => 'done', 'message' => 'failure' ) );
		}

		exit();
	}
	
	
	
	
	
	
	
	
	/**
	 * Registers menu options
	 * Hooked into admin_menu
	 *
	 * @since    2.0.0
	 */
	public function admin_menu()
	{
		$menus=array(
			'general-settings'=>array(
				'menu',
				__('General Settings', 'webtoffee-product-feed'),
				__('General Settings', 'webtoffee-product-feed'),
				apply_filters('wt_import_export_allowed_capability', 'import'),
				WEBTOFFEE_PRODUCT_FEED_ID,
				array($this,'admin_settings_page'),
				'dashicons-controls-repeat',
				56
			)
		);
		$menus=apply_filters('wt_pf_admin_menu_basic',$menus);

		$menu_order=array("export","export-sub","import","history","history_log");
		$this->wt_menu_order_changer($menus,$menu_order);                                            

		$main_menu = reset($menus); //main menu must be first one

		$parent_menu_key=$main_menu ? $main_menu[4] : WEBTOFFEE_PRODUCT_FEED_ID;

                
		/* adding general settings menu */
		$menus['general-settings-sub']=array(
			'submenu',
			$parent_menu_key,
			__('General Settings', 'webtoffee-product-feed'),
			__('General Settings', 'webtoffee-product-feed'), 
			apply_filters('wt_import_export_allowed_capability', 'import'),
			WEBTOFFEE_PRODUCT_FEED_ID,
			array($this, 'admin_settings_page')
		);
		
		$menus['fb_catalog_manage']=array(
			'submenu',
			$parent_menu_key,
			__( 'Facebook/Instagram Catalog Sync', 'webtoffee-product-feed' ),
			__( 'Facebook/Instagram Catalog Sync', 'webtoffee-product-feed' ), 
			apply_filters('wt_import_export_allowed_capability', 'import'),
			WT_Fb_Catalog_Manager_Settings::PAGE_ID,
			array($this, 'render')
		);
		
		if(count($menus)>0)
		{
			foreach($menus as $menu)
			{
				if($menu[0]=='submenu')
				{
					/* currently we are only allowing one parent menu */
					add_submenu_page($parent_menu_key,$menu[2],$menu[3],$menu[4],$menu[5],$menu[6]);
				}else
				{
					add_menu_page($menu[1],$menu[2],$menu[3],$menu[4],$menu[5],$menu[6],$menu[7]);	
				}
			}
		}
		if(function_exists('remove_submenu_page')){
			//remove_submenu_page(WT_PIEW_POST_TYPE, WT_PIEW_POST_TYPE);
		}
	}
	
	public function wt_menu_order_changer( &$arr, $index_arr ) {
			$arr_t = array();
			foreach ( $index_arr as $i => $v ) {
				foreach ( $arr as $k => $b ) {
					if ( $k == $v )
						$arr_t[ $k ] = $b;
				}
			}
			$arr = $arr_t;
	}

		public function admin_settings_page()
	{	
		include(plugin_dir_path( __FILE__ ).'partials/webtoffee-product-feed-admin-display.php');
	}

	/**
	* 	Save admin settings and module settings ajax hook
	*/
	public function save_settings()
	{
		$out=array(
			'status'=>false,
			'msg'=>__('Error'),
		);

		if(Wt_Pf_Sh::check_write_access(WEBTOFFEE_PRODUCT_FEED_ID)) 
    	{
    		$advanced_settings=Webtoffee_Product_Feed_Sync_Common_Helper::get_advanced_settings();
    		$advanced_fields=Webtoffee_Product_Feed_Sync_Common_Helper::get_advanced_settings_fields();
    		$validation_rule=Webtoffee_Product_Feed_Sync_Common_Helper::extract_validation_rules($advanced_fields);
    		$new_advanced_settings=array();
    		foreach($advanced_fields as $key => $value) 
	        {
	            $form_field_name = isset($value['field_name']) ? $value['field_name'] : '';
				$field_name=(substr($form_field_name,0,8)!=='wt_pf_' ? 'wt_pf_' : '').$form_field_name;
	            $validation_key=str_replace('wt_pf_', '', $field_name);
	            if(isset($_POST[$field_name]))
	            {      	
	            	$new_advanced_settings[$field_name]=Wt_Pf_Sh::sanitize_data($_POST[$field_name], $validation_key, $validation_rule);
	            }
	        }
			
			$checkbox_items = array( 'wt_pf_enable_import_log', 'wt_pf_enable_history_auto_delete', 'wt_pf_include_bom', 'wt_pf_all_shipping_zone' );
			foreach ( $checkbox_items as $checkbox_item ){
				$new_advanced_settings[$checkbox_item] = isset( $new_advanced_settings[$checkbox_item] ) ? $new_advanced_settings[$checkbox_item] : 0;
			}
			
	        Webtoffee_Product_Feed_Sync_Common_Helper::set_advanced_settings($new_advanced_settings);
	        $out['status']=true;
	        $out['msg']=__('Settings Updated', 'webtoffee-product-feed');
	        do_action('wt_pf_after_advanced_setting_update_basic', $new_advanced_settings);        
    	}
		echo json_encode($out);
		exit();
	}


        /**
	* 	Delete pre-saved temaplates entry from DB - ajax hook
	*/
        public function delete_template() {
            $out = array(
                'status' => false,
                'msg' => __('Error', 'webtoffee-product-feed'),
            );

            if (Wt_Pf_Sh::check_write_access(WEBTOFFEE_PRODUCT_FEED_ID)) {
                if (isset($_POST['template_id'])) {

                    global $wpdb;
                    $template_id = absint($_POST['template_id']);
                    $tb = $wpdb->prefix . Webtoffee_Product_Feed_Sync::$template_tb;
                    $where = "=%d";
                    $where_data = array($template_id);
                    $wpdb->query($wpdb->prepare("DELETE FROM $tb WHERE id" . $where, $where_data));
                    $out['status'] = true;
                    $out['msg'] = __('Template deleted successfully');
                    $out['template_id'] = $template_id;
                }
            }
            wp_send_json($out);

        }        
                
	/**
	 Registers modules: admin	 
	 */
	public function admin_modules()
	{ 
		$wt_pf_admin_modules=get_option('wt_pf_admin_modules');
		if($wt_pf_admin_modules===false)
		{
			$wt_pf_admin_modules=array();
		}
		foreach (self::$modules as $module) //loop through module list and include its file
		{
			$is_active=1;
			if(isset($wt_pf_admin_modules[$module]))
			{
				$is_active=$wt_pf_admin_modules[$module]; //checking module status
			}else
			{
				$wt_pf_admin_modules[$module]=1; //default status is active
			}
			$module_file=plugin_dir_path( __FILE__ )."modules/$module/$module.php";
			if(file_exists($module_file) && $is_active==1)
			{
				self::$existing_modules[]=$module; //this is for module_exits checking
				require_once $module_file;
			}else
			{
				$wt_pf_admin_modules[$module]=0;	
			}
		}
		$out=array();
		foreach($wt_pf_admin_modules as $k=>$m)
		{
			if(in_array($k, self::$modules))
			{
				$out[$k]=$m;
			}
		}

		update_option('wt_pf_admin_modules',$out);


		/**
		*	Add on modules 
		*/
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );                 
		foreach (self::$addon_modules as $module) //loop through module list and include its file
		{                   
			$plugin_file="webtoffee-product-feed-$module/wwebtoffee-product-feed-$module.php";
			if(is_plugin_active($plugin_file))
			{
				$module_file=WP_PLUGIN_DIR."/webtoffee-product-feed-$module/$module/$module.php";
				if(file_exists($module_file))
				{
					self::$existing_modules[]=$module;
					require_once $module_file;
				}				
			}
		}
                
                
                $addon_modules_basic = array(
 
                    'google' => 'webtoffee-product-feed',                    
                    'facebook' => 'webtoffee-product-feed',
                    'tiktok' => 'webtoffee-product-feed',
                    'tiktokshop' => 'webtoffee-product-feed',
                    'pinterest' => 'webtoffee-product-feed',
                    'snapchat' => 'webtoffee-product-feed',
                    'bing' => 'webtoffee-product-feed',
                    'idealo' => 'webtoffee-product-feed',                    
                    'google_local_product_inventory' => 'webtoffee-product-feed',
                    'google_promotions' => 'webtoffee-product-feed',
                    'buyon_google' => 'webtoffee-product-feed',                            
                    'pricespy' => 'webtoffee-product-feed',
                    'pricerunner' => 'webtoffee-product-feed',
                    'skroutz' => 'webtoffee-product-feed',
                    'shopzilla' => 'webtoffee-product-feed',                    
                );

                foreach ($addon_modules_basic as $module_key => $module_path)
                {
                        if(is_plugin_active("{$module_path}/{$module_path}.php"))
                        {
                                $module_file=WP_PLUGIN_DIR."/{$module_path}/admin/modules/$module_key/$module_key.php";
                                if(file_exists($module_file))
                            {
                            self::$existing_modules[]=$module_key;
                            require_once $module_file;
                            }
                        }		
                }             

	}

	public static function module_exists($module)
	{
		return in_array($module, self::$existing_modules);
	}

	/**
	 * Envelope settings tab content with tab div.
	 * relative path is not acceptable in view file
	 */
	public static function envelope_settings_tabcontent($target_id,$view_file="",$html="",$variables=array(),$need_submit_btn=0)
	{
		extract($variables);
	?>
		<div class="wt-pfd-tab-content" data-id="<?php echo $target_id;?>">
			<?php
			if($view_file!="" && file_exists($view_file))
			{
				include_once $view_file;
			}else
			{
				echo $html;
			}
			?>
			<?php 
			if($need_submit_btn==1)
			{
				include WT_PRODUCT_FEED_PLUGIN_PATH."admin/views/admin-settings-save-button.php";
			}
			?>
		</div>
	<?php
	}
                
        
        /**
         * Delete products from FB
         * 
         * @param int $postid
         */
        public function delete_product_from_fb($postid) {

            $wt_pf_default_catalog = Webtoffee_Product_Feed_Sync_Common_Helper::get_advanced_settings('default_catalog');

            if ($wt_pf_default_catalog) {
                $access_token = get_option(WT_Fb_Catalog_Manager_Settings::OPTION_ACCESS_TOKEN, '');

                $product = wc_get_product($postid);
                if ($product) {
                    $url = "https://graph.facebook.com/v17.0/{$wt_pf_default_catalog}/items_batch?access_token=" . $access_token;

                    $request_body = array();

                    if ('variable' === $product->get_type()) {
                        $fb_retailer_id = $product->get_sku() ? $product->get_sku() . '_' . $postid : 'wc_post_id_' . $postid;
                        $request_body[] = array(
                            'method' => 'DELETE',
                            'data' => array(
                                'id' => $fb_retailer_id,
                            ),
                        );
                        $all_variations = $product->get_children();
                        foreach ($all_variations as $variant_id) {
                            $variant_product = wc_get_product($variant_id);
                            $fb_retailer_id = $variant_product->get_sku() ? $variant_product->get_sku() . '_' . $variant_id : 'wc_post_id_' . $variant_id;
                            $request_body[] = array(
                                'method' => 'DELETE',
                                'data' => array(
                                    'id' => $fb_retailer_id,
                                ),
                            );
                        }
                    } else {

                        $fb_retailer_id = $product->get_sku() ? $product->get_sku() . '_' . $postid : 'wc_post_id_' . $postid;
                        $request_body = array(
                            array(
                                'method' => 'DELETE',
                                'data' => array(
                                    'id' => $fb_retailer_id,
                                ),
                            ),
                        );
                    }

                    $body = array(
                        'access_token' => $access_token,
                        'item_type' => 'PRODUCT_ITEM',
                        'requests' => $request_body,
                    );

                    $args = array(
                        'method' => 'POST',
                        'headers' => array(
                            'Content-Type' => 'application/json',
                        ),
                        'body' => wp_json_encode($body),
                    );

                    $response = wp_remote_request($url, $args);

                    if (is_wp_error($response)) {
                         
                        $this->wt_log_data_change('wt-feed-upload', 'Failed to process the requests. Error: '.$response->get_error_message() .' ID='. $postid );                        
                    } else {
                        $body = wp_remote_retrieve_body($response);
                        $result = json_decode($body);

                        if (isset($result->success)) {
                            $this->wt_log_data_change('wt-feed-upload', 'Requests processed successfully. ID='.$postid );                        
                        } elseif (isset($result->errors)) {
                            $this->wt_log_data_change('wt-feed-upload', 'Failed to process the requests. Error: ' . $body .' ID='. $postid );                        
                        } else {
                            $this->wt_log_data_change('wt-feed-upload', 'Unexpected response: ' . $body .' ID='. $postid );                        
                        }
                    }
                }
            }

        }

    }
}