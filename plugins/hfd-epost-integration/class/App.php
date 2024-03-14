<?php
/**
 * Created by PhpStorm.
 * Date: 6/4/18
 * Time: 5:36 PM
 */
namespace Hfd\Woocommerce;

require 'AutoLoad.php';

class App
{
    protected $registry;
    /**
     * Init plugin
     */
    public function init()
    {
        $autoload = new AutoLoad();

        spl_autoload_register(function ($class) use ($autoload) {
            $autoload->load($class);
        });

        /**
         * Init plugin classes
         */
        $registry = Registry::getInstance();
        $this->registry = $registry;

        $registry->set('autoload', $autoload);
        $this->registerHook();
    }

    /**
     * Register hook for plugin
     */
    public function registerHook()
    {
        add_filter('woocommerce_shipping_methods', array($this, 'registerShippingMethod'));
        add_filter('woocommerce_hidden_order_itemmeta', array($this, 'hiddenPickupMeta'));
        add_filter('woocommerce_order_shipping_to_display', array($this, 'emailPickupInfo'), 10, 2);

        add_action( 'woocommerce_after_shipping_rate', array($this, 'renderAdditional' ) );
        add_action('woocommerce_checkout_order_processed', array($this, 'convertPickupToOrder'), 10, 3);
		add_action('woocommerce_new_order', array($this, 'convertPickupToOrderOptional'), 10, 2);
        add_action('woocommerce_before_order_itemmeta', array($this, 'adminRenderPickup'), 10, 3);
        add_action('woocommerce_before_checkout_process', array($this, 'validatePickupInfo'));
        add_action('wp_footer', array($this, 'renderPickupMap'));
        add_action('wp_ajax_save_pickup', array($this, 'saveCartPickup'));
        add_action('wp_ajax_nopriv_save_pickup', array($this, 'saveCartPickup'));
        add_action('wp_ajax_get_spots', array($this, 'getSpots'));
        add_action('wp_ajax_nopriv_get_spots', array($this, 'getSpots'));
        add_action('wp_enqueue_scripts', array($this, 'loadStyles'));
        add_action('wp_enqueue_scripts', array($this, 'loadScripts'));
        add_action('plugins_loaded', array($this, 'initAdmin'));
		
		//create a endpoint for print label
		add_filter( 'generate_rewrite_rules', array( $this, 'registerEndpointForPrintLabel' ) );
		
		//white list our endpoint
		add_filter( 'query_vars', array( $this, 'whitelistEndpointForPrintLabel' ) );
		
		//print details
		add_action( 'template_redirect', array( $this, 'epostPrintLabel' ) );
		
		//flush reqrite rules
		add_filter( 'admin_init', array( $this, 'flushRewriteUrls' ) );
		
		//add wordpress ron for auto sync
		add_filter( 'cron_schedules', array( $this, 'hfdAutoSyncOrderCron' ) );
		
		add_action( 'hfd_schedule_auto_sync', array( $this, 'hfdScheduleAutoSyncOrder' ) );
		
		// Schedule an action if it's not already scheduled
		if( !wp_next_scheduled( 'hfd_schedule_auto_sync' ) ){
			wp_schedule_event( time(), 'hfd_auto_sync', 'hfd_schedule_auto_sync' );
		}

        //update plugin settings if its not saved
        add_action( 'plugins_loaded', array( $this, 'hfdUpdatePluginsOptions' ) );
    }
	
    public function hfdUpdatePluginsOptions(){
        $track_shipment_url = get_option( 'betanet_epost_hfd_track_shipment_url' );
        $cancel_shipment_url = get_option( 'betanet_epost_hfd_cancel_shipment_url' );
        $print_label_url = get_option( 'betanet_epost_hfd_print_label_url' );
        $hfd_order_auto_sync = get_option( 'hfd_order_auto_sync' );
        $hfd_epost_service_url = get_option( 'betanet_epost_service_url' );
        if( strpos( $hfd_epost_service_url, "http://" ) !== false || empty( $hfd_epost_service_url ) ){
            update_option( 'betanet_epost_service_url', 'https://run.hfd.co.il/RunCom.Server/Request.aspx?APPNAME=run&PRGNAME=ws_spotslist&ARGUMENTS=-Aall' );
        }
        if( empty( $track_shipment_url ) ){
            update_option( 'betanet_epost_hfd_track_shipment_url', 'https://run.hfd.co.il/RunCom.Server/Request.aspx?APPNAME=run&PRGNAME=ship_locate_random&ARGUMENTS=-A{RAND}' );
        }
        if( empty( $cancel_shipment_url ) ){
            update_option( 'betanet_epost_hfd_cancel_shipment_url', 'https://run.hfd.co.il/RunCom.Server/Request.aspx?APPNAME=run&PRGNAME=bitul_mishloah&ARGUMENTS=-A{shipping_number},-A,-A,-A,-N' );
        }
        if( empty( $print_label_url ) ){
            update_option( 'betanet_epost_hfd_print_label_url', 'https://run.hfd.co.il/RunCom.Server/Request.aspx?APPNAME=run&PRGNAME=ship_print_ws&ARGUMENTS=-N{RAND}' );
        }
        if( empty( $hfd_order_auto_sync ) ){
            update_option( 'hfd_order_auto_sync', 'no' );
        }
		
		//replace old urls to new
		if( strpos( $hfd_epost_service_url, "uniscripts/MGrqispi.dll" ) !== false && filter_var( $hfd_epost_service_url, FILTER_VALIDATE_URL ) == true ){
			$hfd_epost_service_url = str_replace( 'uniscripts/MGrqispi.dll', 'RunCom.Server/Request.aspx', $hfd_epost_service_url );
			
			update_option( 'betanet_epost_service_url', $hfd_epost_service_url );
		}
		
		$betanet_epost_hfd_service_url = get_option( 'betanet_epost_hfd_service_url' );
		if( strpos( $betanet_epost_hfd_service_url, "uniscripts/MGrqispi.dll" ) !== false && filter_var( $betanet_epost_hfd_service_url, FILTER_VALIDATE_URL ) == true ){
			$betanet_epost_hfd_service_url = str_replace( 'uniscripts/MGrqispi.dll', 'RunCom.Server/Request.aspx', $betanet_epost_hfd_service_url );
			
			update_option( 'betanet_epost_hfd_service_url', $betanet_epost_hfd_service_url );
		}
		
		if( strpos( $track_shipment_url, "uniscripts/MGrqispi.dll" ) !== false && filter_var( $track_shipment_url, FILTER_VALIDATE_URL ) == true ){
			$track_shipment_url = str_replace( 'uniscripts/MGrqispi.dll', 'RunCom.Server/Request.aspx', $track_shipment_url );
			
			update_option( 'betanet_epost_hfd_track_shipment_url', $track_shipment_url );
		}
		
		if( strpos( $cancel_shipment_url, "uniscripts/MGrqispi.dll" ) !== false && filter_var( $cancel_shipment_url, FILTER_VALIDATE_URL ) == true ){
			$cancel_shipment_url = str_replace( 'uniscripts/MGrqispi.dll', 'RunCom.Server/Request.aspx', $cancel_shipment_url );
			
			update_option( 'betanet_epost_hfd_cancel_shipment_url', $cancel_shipment_url );
		}
		
		if( strpos( $print_label_url, "uniscripts/MGrqispi.dll" ) !== false && filter_var( $print_label_url, FILTER_VALIDATE_URL ) == true ){
			$print_label_url = str_replace( 'uniscripts/MGrqispi.dll', 'RunCom.Server/Request.aspx', $print_label_url );
			
			update_option( 'betanet_epost_hfd_print_label_url', $print_label_url );
		}
    }
    
	public function hfdScheduleAutoSyncOrder(){
		$hfd_auto_sync_time = get_option( 'hfd_auto_sync_time' );
		$hfd_auto_sync_status = get_option( 'hfd_auto_sync_status' );
		
		if( empty( $hfd_auto_sync_time ) || empty( $hfd_auto_sync_status ) )
			return;
		
		$orderIds = get_posts( array(
			'numberposts' => - 1,
			'post_type'   => array( 'shop_order' ),
			'post_status' => array( $hfd_auto_sync_status ),
			'date_query' => array(
				'after' => date( 'Y-m-d H:i:s', strtotime( '-'.$hfd_auto_sync_time.' minutes' ) ),
				'before' => date( 'Y-m-d H:i:s', time() ) 
			),
			'meta_query' => array(
				array(
					'key' => 'hfd_ship_number',
					'compare' => 'NOT EXISTS' // this should work...
				)
			),
			'fields' => 'ids'
		) );
		
		if( $orderIds ){
			/* @var \Hfd\Woocommerce\Helper\Hfd $hfdHelper */
			$hfdHelper = Container::create('Hfd\Woocommerce\Helper\Hfd');
			$result = $hfdHelper->sendOrders( $orderIds );
			$filesystem = Container::get('Hfd\Woocommerce\Filesystem');
			$filesystem->writeSession( serialize($result), 'sync_to_hfd' );
		}
	}
	
	public function hfdAutoSyncOrderCron( $schedules ){
		$hfd_auto_sync_time = get_option( 'hfd_auto_sync_time' );
		$hfd_order_auto_sync = get_option( 'hfd_order_auto_sync' );
		if( !empty( $hfd_auto_sync_time ) && $hfd_order_auto_sync == "yes" ){
			$schedules['hfd_auto_sync'] = array(
				'interval'  => 60,
				'display'   => sprintf( __( 'Every %s Minute', 'hfd-integration' ), 1 )
			);
		}
		return $schedules;
	}
	
	public function flushRewriteUrls(){
		$rules = $GLOBALS['wp_rewrite']->wp_rewrite_rules();
		if( !isset( $rules['printLabel/(\d+)/?$'] ) ){
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}
	}
	
	public function whitelistEndpointForPrintLabel( $query_vars ){
		$query_vars[] = 'epost-ship-number';
		return $query_vars;
	}
	
	public function registerEndpointForPrintLabel( $wp_rewrite ){
		$wp_rewrite->rules = array_merge(
			['printLabel/(\d+)/?$' => 'index.php?epost-ship-number=$matches[1]'],
			$wp_rewrite->rules
		);
	}
	public function epostPrintLabel(){
		$epost_ship_number = intval( get_query_var( 'epost-ship-number' ) );
		if( $epost_ship_number ){
			$helper = \Hfd\Woocommerce\Container::get('Hfd\Woocommerce\Setting');
			$printLabelUrl = $helper->get( 'betanet_epost_hfd_print_label_url' );
			$authToken = $helper->get( 'betanet_epost_hfd_auth_token' );
			if( !empty( $authToken ) ){
				$args = array(
					'headers' => array(
						'Authorization' => 'Bearer '.$authToken
					)
				);
				$printLabelUrl = str_replace( "{RAND}", $epost_ship_number, $printLabelUrl );
				$response = wp_remote_get( $printLabelUrl, $args );
				if( !is_wp_error( $response ) ){
					$responseBody = wp_remote_retrieve_body( $response );
					$fileName = $epost_ship_number.".pdf";
					header('Content-Type: application/pdf');
					header('Content-Length: '.strlen( $responseBody ));
					header('Content-disposition: inline; filename="'.$fileName.'"');
					header('Cache-Control: public, must-revalidate, max-age=0');
					header('Pragma: public');
					header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
					header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
					print $responseBody;
					exit;
				}
			}
		}
	}
    public function initAdmin()
    {
        $path = basename(HFD_EPOST_PATH). '/languages';
        load_plugin_textdomain('hfd-integration', false, $path);
        /* @var \Hfd\Woocommerce\Admin $admin */
        $admin = Container::get('Hfd\Woocommerce\Admin');
        $admin->init();
    }

    /**
     * @param array $methods
     * @return array
     */
    public function registerShippingMethod($methods)
    {
        $methods['betanet_epost'] = new \Hfd\Woocommerce\Shipping\Epost();
        $methods['betanet_govina'] = new \Hfd\Woocommerce\Shipping\Govina();
        $methods['betanet_home_delivery'] = new \Hfd\Woocommerce\Shipping\Home_Delivery();

        return $methods;
    }

    /**
     * Save pickup information into cart
     */
    public function saveCartPickup()
    {
        if( isset( $_POST['spot_info'] ) ){
            $spotInfo = array_map( 'sanitize_text_field', $_POST['spot_info'] );
            /* @var \Hfd\Woocommerce\Cart\Pickup $cartPickup */
            $cartPickup = Container::get('Hfd\Woocommerce\Cart\Pickup');
            $cartPickup->saveSpotInfo( $spotInfo );
        }
    }

    /**
     * Retrieve list spots
     */
    public function getSpots()
    {
        if (isset($_GET['city'])) {
            return $this->getSpotsByCity( sanitize_text_field( $_GET['city'] ) );
        }

        $helper = Container::get('Hfd\Woocommerce\Helper\Spot');
        $spots = $helper->getSpots();
        header('Content-type: application/json');
        echo json_encode($spots);
        exit;
    }

    public function getSpotsByCity($city)
    {
        $helper = Container::get('Hfd\Woocommerce\Helper\Spot');
        $spots = $helper->getSpotsByCity($city);
        header('Content-type: application/json');
        echo json_encode($spots);
        exit;
    }

    /**
     * @param int $orderId
     * @param array $data
     * @param \WC_Order $order
     */
    public function convertPickupToOrder($orderId, $data, $order)
    {
        /* @var \Hfd\Woocommerce\Cart\Pickup $cartPickup */
        $cartPickup = Container::get('Hfd\Woocommerce\Cart\Pickup');
        $cartPickup->convertToOrder($order);
    }
	
	/**
     * @param int $orderId
     * @param array $data
     * @param \WC_Order $order
     */
	 
	public function convertPickupToOrderOptional( $orderId, $order )
	{
		/* @var \Hfd\Woocommerce\Cart\Pickup $cartPickup */
        $cartPickup = Container::get('Hfd\Woocommerce\Cart\Pickup');
        $cartPickup->convertToOrder($order);
	}
	
    /**
     * @param int $itemId
     * @param \WC_Order_Item_Shipping $item
     */
    public function adminRenderPickup($itemId, $item)
    {
        if ($item->get_type() != 'shipping') {
            return;
        }

        /* @var \Hfd\Woocommerce\Order\Pickup $orderPickup */
        $orderPickup = Container::create('Hfd\Woocommerce\Order\Pickup');
        echo $orderPickup->renderAdminInfo($item);
    }

    /**
     * @param string $text
     * @param \WC_Order $order
     * @return string
     */
    public function emailPickupInfo($text, $order)
    {
        /* @var \Hfd\Woocommerce\Order\Pickup $orderPickup */
        $orderPickup = Container::create('Hfd\Woocommerce\Order\Pickup');
        $shippingItem = $orderPickup->getShippingItem($order);

        if ($shippingItem) {
            $spotInfo = $shippingItem->get_meta('epost_pickup_info');
            if ($spotInfo) {
                $spotInfo = unserialize($spotInfo);

                $html = '<p>';
                $html .= sprintf(
                    '<strong>%s:</strong> %s<br />',
                    __('Branch name', 'hfd-integration'),
                    $spotInfo['name']
                );
                $html .= sprintf(
                    '<strong>%s:</strong> %s %s, %s<br />',
                    __('Branch address', 'hfd-integration'),
                    $spotInfo['street'],
                    $spotInfo['house'],
                    $spotInfo['city']
                );
                $html .= sprintf(
                    '<strong>%s:</strong> %s',
                    __('Operating hours', 'hfd-integration'),
                    $spotInfo['remarks']
                );
                $html .= '</p>';

                $text .= $html;
            }

        }

        return $text;
    }

    public function validatePickupInfo()
    {
        $message = '<ul class="woocommerce-error" role="alert"><li>%s</li></ul>';
        $response = array(
            'messages'  => '',
            'refresh'   => false,
            'reload'    => false,
            'result'    => 'failure'
        );

        if( !isset( $_POST['shipping_method'] ) ){
            return;
        }

        $shippingMethods = array_map( 'sanitize_text_field', $_POST['shipping_method'] );
        $isEpost = false;
        /* @var \Hfd\Woocommerce\Shipping\Epost $epostShipping */
        $epostShipping = Container::get('Hfd\Woocommerce\Shipping\Epost');
        foreach ($shippingMethods as $shippingMethod) {
            if ($epostShipping->isEpost($shippingMethod)) {
                $isEpost = true;
                break;
            }
        }

        if ($isEpost) {
            /* @var \Hfd\Woocommerce\Cart\Pickup $cartPickup */
            $cartPickup = Container::get('Hfd\Woocommerce\Cart\Pickup');
            $spotInfo = $cartPickup->getSpotInfo();
            if (!$spotInfo || !$spotInfo['n_code']) {
                $response['messages'] = sprintf($message, __('Please choose pickup branch', 'hfd-integration'));
                header('Content-type: application/json');
                echo json_encode($response);
                exit;
            }
        }
    }

    /**
     * @param array $metaKeys
     * @return array
     */
    public function hiddenPickupMeta($metaKeys)
    {
        $metaKeys[] = 'epost_pickup_info';

        return $metaKeys;
    }

    /**
     * Load plugin styles
     */
    public function loadStyles()
    {
        wp_enqueue_style('betanet-epost-jqueryui', HFD_EPOST_PLUGIN_URL . '/css/jquery-ui.min.css');
        wp_enqueue_style('betanet-epost-style', HFD_EPOST_PLUGIN_URL . '/css/style.css');
    }

    public function loadScripts()
    {
        wp_enqueue_script( 'jquery-ui-dialog' );
    }

    /**
     * Render pickup button
     * @param \WC_Shipping_Rate $method
     * @return void
     */
    public function renderAdditional( $method )
    {
        if( $method->get_method_id() != 'betanet_epost' ){
            return;
        }
				
        /* @var \Hfd\Woocommerce\Shipping\Additional $additionalBLock */
        $additionalBLock = Container::create('Hfd\Woocommerce\Shipping\Additional');
        echo $additionalBLock->render();
        return;
    }

    public function renderPickupMap()
    {
        $template = Container::create('Hfd\Woocommerce\Template');
        echo $template->fetchView('cart/footer.php');
    }

    public function pluginActivation()
    {
        /* @var \Hfd\Woocommerce\Setting $setting */
        $setting = Container::get( 'Hfd\Woocommerce\Setting' );
        $setting->initDefaultSetting();
    }

    public function pluginDeactivation()
    {
        //
    }
}