<?php

/**
 * Handling WooCommerce hooks.
 *
 * @link       silvasoft.nl
 * @since      1.0.0
 *
 * @package    Silvasoft
 * @subpackage Silvasoft/public
 */

class Silvasoft_WooHooks {

	private $plugin_name;
	private $version;
	private $silvalogger;
	private $apiconnector;
	private $status_sale;
	private $status_refund;
	protected $silvasoft_debugmode;
	protected $whenToSendOrder;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $silvalogger, $apiconnector ) {
		//PROPS
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->silvalogger = $silvalogger;
		$this->apiconnector = $apiconnector;	
		
		//get admin settings
		$silvasoftSettings = get_option('silva_settings');	
		$this->status_sale = $silvasoftSettings['silvasoft_status_sale'];
		$this->status_refund = $silvasoftSettings['silvasoft_status_credit'];
		$this->silvasoft_debugmode = (isset($silvasoftSettings['silvasoft_debugmode']) && !is_null($silvasoftSettings['silvasoft_debugmode'])) ? ($silvasoftSettings['silvasoft_debugmode'] == 'yes' ? true : false) : false;
		
		$this->whenToSendOrder = (isset($silvasoftSettings['silvasoft_directorcron']) && !is_null($silvasoftSettings['silvasoft_directorcron'])) ? $silvasoftSettings['silvasoft_directorcron'] : 'direct';
	}
	
	/*
	* Hook fired when clicked resend order button on woocommerce order overview page
	*/
	function resend_order_to_silvasoft_custom( $actions, $order ) {
			//get order ID and order status
			$order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
			$order_status = method_exists( $order, 'get_status' ) ? $order->get_status() : $order->status;
			
			//check if creditorder
			$creditorder = ($order_status ==	$this->status_refund || 'wc-'.$order_status == $this->status_refund) ? 1 : 0;
			
			//check if the order has already been sent to Silvasoft
			$alreadysentNormal = get_post_meta($order_id,'silva_send_sale',true);
		
			//check if the order has already been sent to Silvasoft
			$alreadysentCredit = get_post_meta($order_id,'silva_send_creditnota',true);
			
			
			$alreadySentNormalClass = '';
			if($alreadysentNormal == true || $alreadysentNormal === 1) $alreadySentNormalClass = 'alreadySentSilva';
		
			$alreadySentCreditClass = '';
			if($alreadysentCredit == true || $alreadysentCredit === 1) $alreadySentCreditClass = 'alreadySentSilva';
			
			$partialRefund = 0;
			if($order->get_total_refunded() > 0) {
				$creditorder = 1;
				$notRefunded = wc_format_decimal( $order->get_total() - $order->get_total_refunded() );
				if($notRefunded > 0) {
					$partialRefund = 1;
				}
			}
		
			$redirect_to = urlencode($_SERVER['REQUEST_URI']);
			
		
			// Set the action button
			$actions['resend_silvasoft'] = array(
					'url'       => wp_nonce_url(
									admin_url( '/admin.php?page=silvasoft-boekhouden/admin/class-silvasoft-admin.php/log&grid_action=resend&woo_order_id='.$order_id.
												'&creditorder=0&partialrefund=0&r='.$redirect_to)),					
					'name'      => __( 'Handmatig versturen naar Silvasoft', 'woocommerce' ),
					'action'    => "view resend_silvasoft " . $alreadySentNormalClass,
				);
			
			$stillRefundToSend = false;
			foreach ( $order->get_refunds() as $refund ) {
				$refund_id = $refund->get_data()['id'];
				$sentRefund = get_post_meta($order_id,'silva_send_refund_'.$refund_id,true);
				if($sentRefund == null || ($sentRefund !== 1 && $sentRefund != true)) {
				  	$stillRefundToSend = true;
					$alreadySentCreditClass = '';
					break;
				}
			}
			
			//show creditnota button if order is credit status
			if($creditorder === 1 || $stillRefundToSend) {
				$actions['resend_silvasoft_credit'] = array(
					'url'       => wp_nonce_url(
									admin_url( '/admin.php?page=silvasoft-boekhouden/admin/class-silvasoft-admin.php/log&grid_action=resend&woo_order_id='.$order_id.
												'&creditorder=1&partialrefund='.$partialRefund.'&r='.$redirect_to)),					
					'name'      => __( 'Handmatig versturen naar Silvasoft', 'woocommerce' ),
					'action'    => "view resend_silvasoft_credit " . $alreadySentCreditClass,
				);	
			}
		
		
			
				
		
		return $actions;
	}
	function add_custom_order_status_actions_button_css() {
    	echo '<style>.resend_silvasoft{ width: 75px !important; } .resend_silvasoft::after { font-family: dashicons,verdana; content: "\f504 Silvasoft" !important; } .alreadySentSilva{opacity: 0.4 !important;}</style>';
		
		echo '<style>.resend_silvasoft_credit{ width: 115px !important; } .resend_silvasoft_credit::after { font-family: dashicons,verdana; content: "\f504 Silvasoft (credit)" !important; } .alreadySentSilva{opacity: 0.4 !important;}</style>';
	}
	
	/**
	 * Hook fired when status of WooCommerce order is changed
	 */	
	function woo_order_status_change_silvasoft($order_id,$old_status,$new_status) {
		$statuslog = $new_status;
		if(substr( $statuslog, 0, 2 ) !== "wc") {
			$statuslog = 'wc-'.$new_status;
		}
		if($this->silvasoft_debugmode) {
			$this->silvalogger->doLog('DEBUG','Order: #' . $order_id . ' - Status wijziging ontvangen WooCommerce. Nieuwe status: "' . $statuslog . '". Status nodig voor versturen als verkoop: "'.$this->status_sale.'", status nodig voor versturen als creditnota: "'.$this->status_refund . '"');
		}
		
		global $woocommerce;
		//construct order
       	$order = new WC_Order( $order_id );		
		//process order if match with settings
		
		if($order->get_total() <= 0) {
			//this order is free and will not be sent to Silvasoft	
		} else {
			
			if(($new_status == $this->status_sale) || ('wc-'.$new_status == $this->status_sale)) {			
				//sales transaction
				if($this->silvasoft_debugmode) {
					$this->silvalogger->doLog('DEBUG','Order: #' . $order_id . ' - Order versturen als verkoop naar Silvasoft.');
				}
				
				
				if($this->whenToSendOrder == 'cron') {
					$existingMeta = get_post_meta($order_id,'silva_cron',true);
					if($existingMeta == null || $existingMeta == '') {
						update_post_meta($order_id, 'silva_cron', 'pending');	
					}
				} else if($this->whenToSendOrder == 'direct') {
					//directly
					$this->apiconnector->transferOrderToSilvasoft($order_id);	
				} else { 
					//manually
				}
				
				
			} else if(($new_status == $this->status_refund) || ('wc-'.$new_status == $this->status_refund)) {
				//creditnota
				if($this->silvasoft_debugmode) {
					$this->silvalogger->doLog('DEBUG','Order: #' . $order_id . ' - Order versturen als creditnota naar Silvasoft.');
				}
				
				if($this->whenToSendOrder == 'cron') {
					$existingMeta = get_post_meta($order_id,'silva_cron_refund',true);
					if($existingMeta == null || $existingMeta == '') {
						update_post_meta($order_id, 'silva_cron_refund', 'pending');	
					}
				} else if($this->whenToSendOrder == 'direct') {
					//directly
					$this->apiconnector->transferOrderToSilvasoft($order_id,true);	
				} else { 
					//manually
				}
				
			}
		}
		
		
	}
	
	
	/* 
	* Bulk order actions to send orders to Silvasoft
	*/
	
	function sendorderstosilva_bulk_actions_edit_product( $actions ) {
    	$actions['sendorderstosilva'] = __( 'Verstuur orders naar Silvasoft (bulk)', 'woocommerce' );
		return $actions;
	}
	
	function bulk_actions_sendorderstosilva( $redirect_to, $action, $post_ids ) {
		if ( $action !== 'sendorderstosilva' )
			return $redirect_to; // Exit
		
		$processed_ids = array();
		$skipped_ids = array();
		$errored_ids = array();

		foreach ( $post_ids as $post_id ) {
			$order = wc_get_order( $post_id );
			$order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
			$order_status = method_exists( $order, 'get_status' ) ? $order->get_status() : $order->status;
			
			//check if creditorder
			$creditorder = ($order_status ==	$this->status_refund || 'wc-'.$order_status == $this->status_refund) ? 1 : 0;
			
			//check if the order has already been sent to Silvasoft
			$alreadysentNormal = get_post_meta($order_id,'silva_send_sale',true);
		
			//check if the order has already been sent to Silvasoft
			$alreadysentCredit = get_post_meta($order_id,'silva_send_creditnota',true);
			
			//partial creditnota?
			$partialRefund = 0;
			if($order->get_total_refunded() > 0) {
				$creditorder = 1;
				$notRefunded = wc_format_decimal( $order->get_total() - $order->get_total_refunded() );
				if($notRefunded > 0) {
					$partialRefund = 1;
				}
			}
			
			$status = array();
			if(($order_status == $this->status_sale) || ('wc-'.$order_status == $this->status_sale)) {			
				if($alreadysentNormal) {
					$skipped_ids[] = $post_id;
					continue;
				}
				//sales transaction
				$status = $this->apiconnector->transferOrderToSilvasoft($order_id);
				
			} 
			if(($order_status == $this->status_refund) || ('wc-'.$order_status == $this->status_refund) || $partialRefund === 1) {
				if($alreadysentCredit) {
					$skipped_ids[] = $post_id;
					continue;
				}
				//creditnota
				$status = $this->apiconnector->transferOrderToSilvasoft($order_id,true,$partialRefund === 1);
			}	
			
			if($status['status'] == "ok") {
				$processed_ids[] = $post_id;
			} else {
				$errored_ids[] = $post_id;
			}
		}

		return $redirect_to = add_query_arg( array(
			'sendorderstosilva' => '1',
			'processed_count' => count( $processed_ids ),
			'skipped_count' => count($skipped_ids ),
			'error_count' => count($errored_ids ),
		), $redirect_to );
	}

	// The results notice from bulk action on orders
	function sendorderstosilva_bulk_action_admin_notice() {
		if ( empty( $_REQUEST['sendorderstosilva'] ) ) return; // Exit

		$count = intval( $_REQUEST['processed_count'] );
		$countSkipped = intval( $_REQUEST['skipped_count'] );
		$countErrors = intval( $_REQUEST['error_count'] );

		printf( '<div id="message" class="updated fade"><p>' .
			_n( '%s orders zijn succesvol verstuurd naar Silvasoft. ',
			'%s orders zijn succesvol verstuurd naar Silvasoft. ',
			$count,
			'sendorderstosilva'
		) . '</p></div>', $count );
		
		if($countSkipped > 0) {
			printf( '<div id="message2" class="notice-warning notice fade"><p>' .
			_n( '%s orders zijn overgeslagen omdat ze reeds verstuurd zijn.',
			'%s orders zijn overgeslagen omdat ze reeds verstuurd zijn.',
			$countSkipped,
			'sendorderstosilva'
			) . '</p></div>', $countSkipped );
		}
		
		if($countErrors > 0) {
			printf( '<div id="message3" class="error notice fade"><p>' .
				_n( '%s orders zijn niet verstuurd naar Silvasoft omdat er fouten zijn opgetreden. Dit kan meerdere redenen hebben. <ul><li>- Uw API plan staat het niet toe om veel orders tegelijk te versturen. Voor het versturen van bulk orders kunt u het beste (tijdelijk) het grootste plan aanzetten.</li><li>- Uw API connectie is niet actief. Controleer uw instellingen via het menu: Silvasoft > Instellingen.</li><li>- De orders hebben niet de juiste status. Enkel afgeronde orders of gecrediteerde orders worden verstuurd via de bulk actie. U kunt niet afgeronde orders wel 1-voor-1 versturen via de knop "Silvasoft" onder de kolom acties. Indien u deze kolom niet ziet kunt u deze activeren door bovenin op "Schermopties" te klikken. </li></ul>',
				'%s orders zijn niet verstuurd naar Silvasoft omdat er fouten zijn opgetreden. Dit kan meerdere redenen hebben. <ul><li>- Uw API plan staat het niet toe om veel orders tegelijk te versturen. Voor het versturen van bulk orders kunt u het beste (tijdelijk) het grootste plan aanzetten.</li><li>- Uw API connectie is niet actief. Controleer uw instellingen via het menu: Silvasoft > Instellingen.</li><li>- De orders hebben niet de juiste status. Enkel afgeronde orders of gecrediteerde orders worden verstuurd via de bulk actie. U kunt niet afgeronde orders wel 1-voor-1 versturen via de knop "Silvasoft" onder de kolom acties. Indien u deze kolom niet ziet kunt u deze activeren door bovenin op "Schermopties" te klikken. </li></ul>',
				$countErrors, 
				'sendorderstosilva'
			) . '</p></div>', $countErrors );
		}
		
	}
	
}
