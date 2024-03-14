<?php
defined( 'ABSPATH' ) || exit;
/**
 * All about asp dot net operations of oliver pos bridge plugin
 */
class Pos_Bridge_Config {
    private $asp_server_url;
    private $asp_hub_url;
    private $register_url;
	private $onboarding_url;
	private $account_url;
    function __construct() {
        $environment = include('environment.php');
        $this->asp_server_url = $environment['ASP_SERVER_URL'];
        $this->asp_hub_url = $environment['ASP_HUB_URL'];
        $this->ga_config_id = $environment['ga_config_id'];
        $this->register_url = $environment['REGISTER_URL'];
	    $this->onboarding_url = $environment['ONBOARDING_URL'];
	    $this->account_url = $environment['ACCOUNT_URL'];
        $this->oliver_pos_define_constant();
    }
    private $asp_bridge = 'api/WCBridge/';
    private $asp_subscription = 'api/Subscription/';
    private $asp_onboard = 'api/onboard/';
    private $asp_hub_admin = 'Account/';
    private $asp_onboard_page_url = 'onboard/';
    private $asp_hub_page_url = 'VerifyClient/';
    private $asp_pos_troubleshoot = 'troubleshoot/Index/';
    private $asp_pos_plugin_details = 'PluginDetails';
    private $asp_pos_payment_details = 'PaymentDetails';
    //Add 2.3.9.3
    private $asp_new_onboard = 'api/v1/';
    private $asp_register_access_url = 'GetAccessUrls/';
    private $asp_validate_version_url = 'ValidateVersion/';
    private $asp_immunity = 'Immunity/';
    private $asp_plan_subscriptions = 'Subcriptions/';
    private $asp_planinfo = 'planinfo/';
    private $asp_plan_warehouse = 'Warehouse/';
    private $asp_getall = 'getall/';
    private $op_order_status = array('wc-pending', 'wc-completed', 'wc-cancelled', 'wc-refunded', 'wc-processing', 'wc-on-hold', 'wc-failed');
    private $cost_of_goods_for_woo = false;
    private $yith_cost_of_goods_for_woo = false;
    private $asp_payment_type = 'PaymentType/';
	private $asp_initialization = 'Initialization/';
	private $op_hpos_enabled = false;
    public function oliver_pos_define_constant() {
        $active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
        if ( in_array( 'cost-of-goods-for-woocommerce/cost-of-goods-for-woocommerce.php', $active_plugins ) ) {
            $this->cost_of_goods_for_woo=true;
        }
        if ( in_array( 'yith-cost-of-goods-for-woocommerce-premium/init.php', $active_plugins ) ) {
            $this->yith_cost_of_goods_for_woo = true;
        }
        $this->oliver_pos_define('COST_OF_GOODS_FOR_WOO', $this->cost_of_goods_for_woo);
        $this->oliver_pos_define('YITH_COST_OF_GOODS_FOR_WOO', $this->yith_cost_of_goods_for_woo);
        // Plugin and payment details
        $this->oliver_pos_define('ASP_PLUGIN_DETAILS', $this->asp_pos_plugin_details);
        $this->oliver_pos_define('ASP_PAYMENT_DETAILS', $this->asp_pos_payment_details);
        // Server URL
        $this->oliver_pos_define('ASP_SERVER_URL', $this->asp_server_url);
        $this->oliver_pos_define('ASP_HUB_URL', $this->asp_hub_url);
        $this->oliver_pos_define('REGISTER_URL', $this->register_url);
        // plugin version
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        $plugin_details = get_plugin_data( OLIVER_POS_PLUGIN_FILE );
        define('OLIVER_POS_PLUGIN_VERSION_NUMBER', $plugin_details['Version']);
        // MVC actions
        //$this->oliver_define('ASP_LOGIN', $this->asp_server_url.$this->asp_pos_admin.'GetLogin/');
        // Google analytics key
        $this->oliver_pos_define('GA_CONFIG_ID', $this->ga_config_id);
        // Server pos admin base
        //$this->oliver_define('ASP_POS_ADMIN', $this->asp_pos_admin);
        $this->oliver_pos_define('ASP_HUB_ADMIN', $this->asp_hub_admin);
        // Server onboard page url
        $this->oliver_pos_define('ASP_ONBOARD_PAGE_URL', $this->asp_onboard_page_url);
        $this->oliver_pos_define('ASP_HUB_PAGE_URL', $this->asp_hub_page_url);
        // MVC actions
        $this->oliver_pos_define('ASP_TROUBLESHOOT', $this->asp_hub_url.$this->asp_pos_troubleshoot);
        // API actions
        // 1.1 Subscription methods
        $this->oliver_pos_define('ASP_AUTHENTICATION', $this->asp_server_url.$this->asp_subscription.'Authenticate/');
        /**
         * @since 2.3.8.6
         * Add new BridgeInfoPost
         * used for plugins and payments details
         */
        $this->oliver_pos_define('ASP_BRIDGEINFOPOST', $this->asp_server_url.$this->asp_bridge.'BridgeInfoPost/');
        /**
         * @since 2.3.8.6
         * no need
         */
        //$this->oliver_define('ASP_REGITERATION', $this->asp_server_url.$this->asp_subscription.'RegisterSubscrption/');
        $this->oliver_pos_define('ASP_REMOVE_SUBSCRIPTION', $this->asp_server_url.$this->asp_subscription.'RemoveSubscription/');
        $this->oliver_pos_define('ASP_CHECK_STATUS', $this->asp_server_url.$this->asp_subscription.'CheckStatus/');
        // 1.2 Onboarding (new flow) methods
        //Since 2.3.9.7
        //$this->oliver_define('ASP_TRY_CONNECT', $this->asp_server_url.$this->asp_onboard.'tryconnect/');
        //$this->oliver_define('ASP_TRY_DISCONNECT', $this->asp_server_url.$this->asp_onboard.'TryDisconnect/');
        //$this->oliver_pos_define('ASP_TRY_CONNECT', $this->asp_server_url.$this->asp_new_onboard.$this->asp_hub_admin.'Connect/');
	    //Update Since 2.4.1.1
	    //$this->oliver_pos_define('ASP_TRY_CONNECT', $this->asp_server_url.$this->asp_new_onboard.$this->asp_hub_admin.'Connect/');
        $this->oliver_pos_define('INIT_CONNECTION', $this->asp_server_url.$this->asp_new_onboard.$this->asp_hub_admin.'InitConnection/');
	    $this->oliver_pos_define('PING', $this->asp_server_url.$this->asp_new_onboard.$this->asp_hub_admin.'Ping/');
	    $this->oliver_pos_define('ASSIGNSUBSCRIPTION', $this->asp_server_url.$this->asp_new_onboard.$this->asp_hub_admin.'AssignSubscription/');
	    $this->oliver_pos_define('REMOVESUBSCRIPTION', $this->asp_server_url.$this->asp_new_onboard.$this->asp_hub_admin.'RemoveSubscription/');
		$this->oliver_pos_define('ASP_TRY_DISCONNECT', $this->asp_server_url.$this->asp_new_onboard.$this->asp_hub_admin.'Disconnect/');
        $this->oliver_pos_define('ASP_TRY_ONBOARD', $this->asp_hub_url.$this->asp_hub_admin.$this->asp_hub_page_url);
        //Add new api from 2.3.9.3
        $this->oliver_pos_define('ASP_LAUNCH_REGISTER', $this->asp_server_url.$this->asp_new_onboard.$this->asp_hub_admin.$this->asp_register_access_url);
        $this->oliver_pos_define('ASP_VALIDATE_VERSION', $this->asp_server_url.$this->asp_new_onboard.$this->asp_immunity.$this->asp_validate_version_url);
        $this->oliver_pos_define('ASP_PLANINFO', $this->asp_server_url.$this->asp_new_onboard.$this->asp_plan_subscriptions.$this->asp_planinfo);
        // 1.3 Trigger on update verion
        $this->oliver_pos_define('ASP_TRIGGER_CHANGE_BRIDGE_VERSION', $this->asp_server_url.$this->asp_bridge.'ChangeBridgeVersion/');
        // 1.4 Trigger after bridge connected
        $this->oliver_pos_define('ASP_TRIGGER_BRIDGE_DETAILS', $this->asp_server_url.$this->asp_bridge.'TriggerBridgeDetail/');
        // 2. Bridge methods
        // 2.1 miscellaneous methods
        //SInce 2.3.9.1 update
        //$this->oliver_define('ASP_TRIGGER_SETTING_SAVE', $this->asp_server_url.$this->asp_bridge.'TriggerSettingSaved/');
        $this->oliver_pos_define('ASP_TRIGGER_SETTING_SAVE', $this->asp_server_url.$this->asp_bridge.'TriggerSettingPost');
        //$this->oliver_define('ASP_TRIGGER_TAX_SETTING', $this->asp_server_url.$this->asp_bridge.'TriggerTaxSetting/');
        $this->oliver_pos_define('ASP_TRIGGER_TAX_SETTING', $this->asp_server_url.$this->asp_bridge.'TriggerTaxSettingPost');
        //2.3.9.0
        //$this->oliver_define('ASP_TRIGGER_CREATE_CATEGORY', $this->asp_server_url.$this->asp_bridge.'TriggerCreateCategory/');
        $this->oliver_pos_define('ASP_TRIGGER_CREATE_CATEGORY', $this->asp_server_url.$this->asp_bridge.'TriggerCreateCategoryPost');
        //2.3.9.0
        //$this->oliver_define('ASP_TRIGGER_UPDATE_CATEGORY', $this->asp_server_url.$this->asp_bridge.'TriggerUpdateCategory/');
        $this->oliver_pos_define('ASP_TRIGGER_UPDATE_CATEGORY', $this->asp_server_url.$this->asp_bridge.'TriggerUpdateCategoryPost');
        $this->oliver_pos_define('ASP_TRIGGER_REMOVE_CATEGORY', $this->asp_server_url.$this->asp_bridge.'TriggerRemoveCategory/');
        $this->oliver_pos_define('ASP_TRIGGER_CREATE_SUB_ATTRIBUTE', $this->asp_server_url.$this->asp_bridge.'TriggerCreateSubAttribute/');
        $this->oliver_pos_define('ASP_TRIGGER_UPDATE_SUB_ATTRIBUTE', $this->asp_server_url.$this->asp_bridge.'TriggerUpdateSubAttribute/');
        $this->oliver_pos_define('ASP_TRIGGER_REMOVE_SUB_ATTRIBUTE', $this->asp_server_url.$this->asp_bridge.'TriggerRemoveSubAttribute/');
        $this->oliver_pos_define('ASP_TRIGGER_BULK_ATTRIBUTE_PRODUCTS', $this->asp_server_url.$this->asp_bridge.'TriggerBulkAttributeProduct/');
        $this->oliver_pos_define('ASP_TRIGGER_CREATE_ATTRIBUTE', $this->asp_server_url.$this->asp_bridge.'TriggerCreateAttribute/');
        $this->oliver_pos_define('ASP_TRIGGER_UPDATE_ATTRIBUTE', $this->asp_server_url.$this->asp_bridge.'TriggerUpdateAttribute/');
        $this->oliver_pos_define('ASP_TRIGGER_REMOVE_ATTRIBUTE', $this->asp_server_url.$this->asp_bridge.'TriggerRemoveAttribute/');
        // 2.2 Order methods
        $this->oliver_pos_define('ASP_TRIGGER_REMOVE_ORDER', $this->asp_server_url.$this->asp_bridge.'TriggerRemoveOrder/');
	    //Since 2.4.1.0 Add
		$this->oliver_pos_define('ASP_TRIGGER_PERMANENT_REMOVE_ORDER', $this->asp_server_url.$this->asp_bridge.'TriggerPermanentRemoveOrder/');
        $this->oliver_pos_define('ASP_TRIGGER_ROLLBACK_ORDER', $this->asp_server_url.$this->asp_bridge.'TriggerRollBackOrder/');
        //Since 2.3.8.8
        //$this->oliver_define('ASP_TRIGGER_ORDER', $this->asp_server_url.$this->asp_bridge.'TriggerOrder/');
        $this->oliver_pos_define('ASP_TRIGGER_REFUND_ORDER', $this->asp_server_url.$this->asp_bridge.'TriggerOrderRefund/');
        $this->oliver_pos_define('ASP_TRIGGER_ORDER', $this->asp_server_url.$this->asp_bridge.'TriggerOrderPost');
        //$this->oliver_define('ASP_TRIGGER_REFUND_ORDER', $this->asp_server_url.$this->asp_bridge.'TriggerOrderRefundPost');
        $this->oliver_pos_define('ASP_TRIGGER_REFUND_ORDER_ITEM', $this->asp_server_url.$this->asp_bridge.'TriggerOrderRefundItem/');
        // 2.3 Product methods
        //$this->oliver_define('ASP_TRIGGER_CREATE_PRODUCT', $this->asp_server_url.$this->asp_bridge.'TriggerProduct/');
        //$this->oliver_define('ASP_TRIGGER_UPDATE_PRODUCT', $this->asp_server_url.$this->asp_bridge.'TriggerUpdateProduct/');
        $this->oliver_pos_define('ASP_TRIGGER_CREATE_PRODUCT', $this->asp_server_url.$this->asp_bridge.'TriggerProductCreatePost');
        $this->oliver_pos_define('ASP_TRIGGER_UPDATE_PRODUCT', $this->asp_server_url.$this->asp_bridge.'TriggerProductUpdatePost');
        $this->oliver_pos_define('ASP_TRIGGER_REMOVE_PRODUCT', $this->asp_server_url.$this->asp_bridge.'TriggerRemoveProduct/');
        $this->oliver_pos_define('ASP_TRIGGER_UPDATE_PRODUCT_QUANTITY', $this->asp_server_url.$this->asp_bridge.'TriggerUpdateProductQuantity/');
        $this->oliver_pos_define('ASP_TRIGGER_VARIATION_CREATE_PRODUCT', $this->asp_server_url.$this->asp_bridge.'TriggerSaveVariationProduct/');
        $this->oliver_pos_define('ASP_TRIGGER_VARIATION_UPDATE_PRODUCT', $this->asp_server_url.$this->asp_bridge.'TriggerUpdateVariationProduct/');
	    $this->oliver_pos_define('ASP_TRIGGER_ROLLBACK_PRODUCT', $this->asp_server_url.$this->asp_bridge.'TriggerRollBackProduct/');
        // 2.4 tAX methods
        // Since update 2.3.8.8
        //$this->oliver_define('ASP_TRIGGER_CREATE_TAX', $this->asp_server_url.$this->asp_bridge.'TriggerCreateTax/');
        // $this->oliver_define('ASP_TRIGGER_UPDATE_TAX', $this->asp_server_url.$this->asp_bridge.'TriggerUpdateTax/');
        $this->oliver_pos_define('ASP_TRIGGER_CREATE_TAX', $this->asp_server_url.$this->asp_bridge.'TriggerTaxesCreatePost');
        $this->oliver_pos_define('ASP_TRIGGER_UPDATE_TAX', $this->asp_server_url.$this->asp_bridge.'TriggerTaxesUpdatePost');
        $this->oliver_pos_define('ASP_TRIGGER_REMOVE_TAX', $this->asp_server_url.$this->asp_bridge.'TriggerRemoveTax/');
        // 2.5 USER methods
        // Since update 2.3.8.8
        //$this->oliver_define('ASP_TRIGGER_CREATE_USER', $this->asp_server_url.$this->asp_bridge.'TriggerRegisterUser/');
        //$this->oliver_define('ASP_TRIGGER_UPDATE_USER', $this->asp_server_url.$this->asp_bridge.'TriggerUpdateUser/');
        $this->oliver_pos_define('ASP_TRIGGER_CREATE_USER', $this->asp_server_url.$this->asp_bridge.'TriggerCustomerCreatePost');
        $this->oliver_pos_define('ASP_TRIGGER_UPDATE_USER', $this->asp_server_url.$this->asp_bridge.'TriggerCustomerUpdatePost');
        $this->oliver_pos_define('ASP_TRIGGER_REMOVE_USER', $this->asp_server_url.$this->asp_bridge.'TriggerRemoveUser/');
        // 2.5 TICKERA methods
        $this->oliver_pos_define('ASP_TRIGGER_CREATE_UPDATE_TICKERA_FORM', $this->asp_server_url.$this->asp_bridge.'TriggerMigrateTickeraFormById');
        $this->oliver_pos_define('ASP_TRIGGER_REMOVE_TICKERA_FORM', $this->asp_server_url.$this->asp_bridge.'TriggerRemoveTickeraFormById');
        $this->oliver_pos_define('ASP_TRIGGER_CREATE_UPDATE_TICKERA_EVENT', $this->asp_server_url.$this->asp_bridge.'TriggerMigrateTickeraEventById');
        $this->oliver_pos_define('ASP_TRIGGER_REMOVE_TICKERA_EVENT', $this->asp_server_url.$this->asp_bridge.'TriggerRemoveEventById');
        $this->oliver_pos_define('ASP_TRIGGER_CREATE_UPDATE_TICKERA_TICKET', $this->asp_server_url.$this->asp_bridge.'TriggerMigrateTickeraTicketById');
        $this->oliver_pos_define('ASP_TRIGGER_REMOVE_TICKERA_TICKET', $this->asp_server_url.$this->asp_bridge.'TriggerRemoveTicketById');
        $this->oliver_pos_define('ASP_TRIGGER_CREATE_UPDATE_TICKERA_SEATING_CHART', $this->asp_server_url.$this->asp_bridge.'TriggerMigrateTickeraSeatingChartById');
        $this->oliver_pos_define('ASP_TRIGGER_REMOVE_TICKERA_SEATING_CHART', $this->asp_server_url.$this->asp_bridge.'TriggerRemoveSeatingChartById');
        $this->oliver_pos_define('ASP_TRIGGER_TICKERA_SETTING', $this->asp_server_url.$this->asp_bridge.'TriggerMigrateTickeraSetting/');
        // connection
        $this->oliver_pos_define('ASP_CHECK_IS_CONNECTION_ALIVE', $this->asp_server_url.$this->asp_bridge.'GetConnectionAlive');
        //HPOS
		if(get_option( 'woocommerce_custom_orders_table_enabled' ) == 'yes') {
			$this->op_order_status[]='draft';
            $this->op_hpos_enabled = true;
		}
        $this->oliver_pos_define('OP_HPOS_ENABLED', $this->op_hpos_enabled);
        $this->oliver_pos_define('OP_ORDER_STATUS', $this->op_order_status);
        //warehouse
        $this->oliver_pos_define('ASP_GETALL', $this->asp_server_url . $this->asp_new_onboard . $this->asp_plan_warehouse . $this->asp_getall);
        // For re sync records
        //$this->oliver_define('ASP_RESYNC_REMAINING_RECORDS', $this->asp_base.$this->asp_bridge.'ResyncRemainingRecords/');
        //Since add 2.4.0.7
        $this->oliver_pos_define('ASP_TRIGGER_ORDER_FILE_CREATED', $this->asp_server_url.$this->asp_bridge.'TriggerOrderCreatedFile');
        $this->oliver_pos_define('ASP_PAYMENT_INFO', $this->asp_server_url.$this->asp_new_onboard.$this->asp_payment_type);
	    //Since add 2.4.1.3
	    $this->oliver_pos_define('ONBOARDING', $this->onboarding_url);
	    $this->oliver_pos_define('ASP_PAYMENT_INFO', $this->asp_server_url.$this->asp_new_onboard.$this->asp_payment_type);
	    $this->oliver_pos_define('ASP_SYNCSTATUS', $this->asp_server_url.$this->asp_new_onboard.$this->asp_initialization.'SyncStatus/');
	    //Since add 2.4.1.7
	    $this->oliver_pos_define('ACCOUNT', $this->account_url);
	    $this->oliver_pos_define('AUTHORIZATION', 'Basic ' . base64_encode( get_option( 'oliver_pos_subscription_client_id' ).":".get_option( 'oliver_pos_subscription_token' ) ));
        //@Since add 2.4.1.8
        $this->oliver_pos_define('ASP_TRIGGER_SAVE_EXTENTION', $this->asp_server_url.$this->asp_bridge.'SaveExtention');
        $this->oliver_pos_define('ASP_GETEXTENTION', $this->asp_server_url.$this->asp_bridge.'GetExtention');
        $this->oliver_pos_define('ASP_REMOVEEXTENTION', $this->asp_server_url.$this->asp_bridge.'RemoveExtention');
        //This is the address where the files for your WordPress site are actually located.
        $this->oliver_pos_define('GET_SITE_URL', get_site_url());
        //This is the address a user types in their address bar to reach your WordPress site.
        $this->oliver_pos_define('HOME_URL', home_url());
        //Since 2.4.1.9
		$this->oliver_pos_define('OP_POST_TYPE', array('shop_order', 'shop_order_placehold'));
    }
    /**
     * Create a new customer.
     *
     * @param  string $name     Constant name.
     * @param  string $value    Constant value.
     */
    private function oliver_pos_define($name, $value){
        if (!defined($name)){
            define( $name, $value );
        }
    }
}
