<?php
use platy\etsy\orders\EtsyOrdersSyncer;
use platy\etsy\orders\EtsyOrder;
use platy\etsy\rest\orders\OrdersRestController;
use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;
use Automattic\WooCommerce\Utilities\OrderUtil;

class Platy_Syncer_Etsy_Order_Admin extends Platy_Syncer_Etsy_Admin {

	function __construct($plugin_name, $version){
		parent::__construct($plugin_name, $version);
		$this->syncer = new EtsyOrdersSyncer();
	}

	static function legacy_orders_table_enabled() {
		return !OrderUtil::custom_orders_table_usage_is_enabled();
	}

	static function get_orders_screen_id() {
		return wc_get_container()->get( CustomOrdersTableController::class )
			->custom_orders_table_usage_is_enabled()
				? wc_get_page_screen_id( 'shop-order' )
					: 'shop_order';

	}

	function filter_order_class($classname, $order_type, $order_id){
		$order = wc_get_order();

		if($classname=="WC_Order" && $order->get_meta( $order_id, "is_platy_etsy_order",true ) == "true"){
			return "platy\\etsy\\orders\\EtsyOrder";
		}
		return $classname;
	}

	function add_menus(){
		if ( ! defined( 'WC_VERSION' ) ) {
			add_action( 'admin_notices', function(){
				$class = 'notice notice-error';
				$message = __( 'Woocommerce is required to use Platy Syncer Etsy', 'sample-text-domain' );	
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
			} );
			return;
		}

		$shop_authenticated = $this->data_service->is_shop_authenticated();
		if($shop_authenticated){
			
			add_submenu_page( "platy-syncer-etsy", "Orders", "Orders", "manage_woocommerce", "platy-syncer-etsy-orders", [$this, "enqueue_orders_scripts"],2);
			
		}
	}

	function filter_woocommcerce_order_actions($actions, $order){
		$order_id = $order->get_id();
		if(EtsyOrdersSyncer::is_post_etsy_order($order_id)){
			return [];
		}
		return $actions;
	}

	function filter_order_item_class($classname, $item_type, $id){
		if(wc_get_order_item_meta( $id, "_is_platy_etsy_order_item", true ) == "true"){
			return "platy\\etsy\\orders\\EtsyOrderItem";
		}
		return $classname;
	}

	function init_rest_apis(){
		if($this->data_service->is_shop_authenticated()){

			require_once plugin_dir_path(  __FILE__ ) . './rest/orders/class-orders-api.php';
			$api = new OrdersRestController();
			$api->register_routes();

		}
	}

	function register_save_order_meta_hooks(){
		remove_action( "woocommerce_process_shop_order_meta", 'WC_Meta_Box_Order_Actions::save');
		add_action("woocommerce_process_shop_order_meta", 'PlatyEtsyOrderMetaBox::save', 1000, 2);
	}

	function filter_order_actions($actions){
		if(get_current_screen()->id != self::get_orders_screen_id()){
			return $actions;
		}
		$order = wc_get_order();

		if($order->get_meta( "is_platy_etsy_order", true ) == "true"){
			return [
				'resync_etsy_order' => __( 'Re-sync order', 'woocommerce' ),
				'complete_etsy_order' => __('Complete Etsy Order', "woocommerce")
		];
		}
		return $actions;
	}

	function remove_meta_boxes(){
		if(get_current_screen()->id != self::get_orders_screen_id()){
			return;
		}
		$order = wc_get_order();

		if($order->get_meta( "is_platy_etsy_order", true ) == "true"){
			remove_meta_box( 'woocommerce-order-items', self::get_orders_screen_id(), 'normal' );
			remove_meta_box( 'postcustom' , self::get_orders_screen_id() , 'normal' );
			remove_meta_box( 'woocommerce-order-downloads' , self::get_orders_screen_id() , 'normal' );	
		}
		
	}

	function add_meta_boxes(){
		if(get_current_screen()->id != self::get_orders_screen_id()){
			return;
		}
		$order = wc_get_order();
		if($order->get_meta( "is_platy_etsy_order", true ) == "true"){
			$my_carriers = [];
			$carriers_string = $this->syncer->get_option("carriers", "");
			if(!empty($carriers_string)){
				$my_carriers = explode(",", $carriers_string);
			}
			add_meta_box( 'woocommerce-order-items', __( 'Items', 'woocommerce' ), 'PlatyEtsyOrderMetaBox::output', self::get_orders_screen_id(), 'normal', 'high' );
			add_meta_box( 'etsy-order-carrier-name', __( 'Etsy Carrier Name', 'woocommerce' ), function() use ($my_carriers){
				$carriers_link = menu_page_url( 'platy-syncer-etsy-orders', false ) . "&platy-main=settings&platy-settings=carriers";
				echo "
					<div style='margin-bottom: 10px;'>
						<a  href='$carriers_link'>Add Carriers Here</a>
					</div>
				";
				if(empty($my_carriers)){
					return;
				}
				$order = wc_get_order();

				$current_carrier = $order->get_meta( "plty_etsy_carrier", true );
				echo "<select name='etsy-carrier-name' style='width: 100%;'>";
				echo "<option value='" . '' . "'>" . "Choose Carrier" . "</option>";
				foreach(\EtsyCarriers::get_my_carriers($my_carriers) as $carrier){
					$selected = "";
					if($carrier == $current_carrier){
						$selected = "selected";
					}
					echo "<option value='" . $carrier['carrier_name'] . "' $selected>" . $carrier['Carrier'] . "</option>";
				}
				echo "</select>";
			}, self::get_orders_screen_id(), 'side', 'high' );
			add_meta_box( 'etsy-order-tracking-number', __( 'Etsy Tracking Number', 'woocommerce' ), function(){
				$order = wc_get_order();

				$tracking = $order->get_meta( "plty_etsy_tracking", true );
				echo "<input name='etsy-tracking-number' type='text' value='$tracking' style='width: 100%;'/>";
			
			}, self::get_orders_screen_id(), 'side', 'high' );
			
		}

	}
	
	public function filter_order_number($id, $order){
		if(!EtsyOrdersSyncer::is_post_etsy_order($id)){
			return $id;
		}
		$etsy_order = new EtsyOrder($id);
		return $etsy_order->get_order_number();
	}

    function edit_screen_title() {
		global $post, $title, $action, $current_screen;
	
		if( isset( $current_screen->post_type ) && $current_screen->post_type == self::get_orders_screen_id() && $action == 'edit' ){
			if(EtsyOrdersSyncer::is_post_etsy_order($post->ID)){
				$title = 'Edit Etsy Order';	
			}
		}
	}

	function remove_add_button() {
		if(empty($_GET['post'])) {
			return;
		}

		if(EtsyOrdersSyncer::is_post_etsy_order(@$_GET['post'])){
	 		global $wp_post_types;
		 	$wp_post_types['shop_order']->cap->create_posts = false;
		}
	 }

	 function enqueue_orders_scripts(){
		$platys = $this->get_platys();

		echo "<app-root></app-root>";

		$runtime = glob(plugin_dir_path( __FILE__ ). 'js/orders/runtime.*.js');
		$runtime = basename($runtime[0]);
		$polyfills = glob(plugin_dir_path( __FILE__ ). 'js/orders/polyfills.*.js');
		$polyfills = basename($polyfills[0]);
		$main = glob(plugin_dir_path( __FILE__ ). 'js/orders/main.*.js');
		$main = basename($main[0]);
		$style = glob(plugin_dir_path( __FILE__ ). 'css/orders/styles.*.css');
		$style = basename($style[0]);
		wp_enqueue_style( "platy_syncer_etsy_css",plugins_url( 'css/orders/' . $style, __FILE__ ));
		wp_enqueue_script( 'platy_bundle_runtime_js',plugins_url( 'js/orders/' . $runtime, __FILE__ )  ,array(), 1.0, true );
		wp_enqueue_script( 'platy_bundle_polyfills_js',plugins_url( 'js/orders/' . $polyfills, __FILE__ )  ,array(), 1.0, true );
		wp_enqueue_script( 'platy_bundle_main_js',plugins_url( 'js/orders/' . $main, __FILE__ )  ,array(), 1.1, true );
		wp_localize_script( 'platy_bundle_main_js', 'platySyncer',  
			[
				'options' => $this->data_service->get_options_grouped(),
				'carriers' => EtsyCarriers::as_data_entitites(),
				'api' => rest_url( "/platy-syncer/v1/"),
				'nonce' => wp_create_nonce( 'wp_rest' ),
				'logo-url' => PLATY_SYNCER_ETSY_DIR_URL . 'assets/images/logo.png',
				'platy' => $platys,
				'pro_link' => admin_url( 'admin.php?page=platy-syncer-etsy-pro', 'http' ),
				'help-link' => admin_url( 'admin.php?page=platy-syncer-etsy-help', 'http' )
			] 
		);
	 }
}