<?php namespace BDroppy\Services\WooCommerce;

use BDroppy\Init\Core;
use BDroppy\Models\Order;
use BDroppy\Models\ProductModel;

if ( ! defined( 'ABSPATH' ) ) exit;



class SyncOrder {

	const SOLD_API_LOCK_OP = 'lock';
	const SOLD_API_SET_OP = 'set';
	const SOLD_API_UNLOCK_OP = 'unlock';

	private $core;
	private $logger;
	private $config;
	private $remote;
	private $pending_cache = null;
	private $processing_cache = null;

	public function __construct( Core $core ) {
		$this->core   = $core;
		$this->logger   = $this->core->getLogger();
		$this->config = $this->core->getConfig();
		$this->remote = $this->core->getRemote();

		$loader = $this->core->getLoader();
		$loader->addAction( 'woocommerce_after_checkout_validation', $this, 'on_checkout_process', 999 );
		$loader->addAction( 'woocommerce_order_status_processing', $this, 'on_order_processing', 10, 1 );
		$loader->addAction( 'before_delete_post', $this, 'on_order_delete', 10, 1 );
	}

	public function on_checkout_process()
    {
		//Do not reserve if there are already checkout errors
		if(isset( $_POST['woocommerce_checkout_update_totals'] ) || wc_notice_count( 'error' ) > 0)
		{
			return;
		}
		$cart     = WC()->cart;
		$products = array();

		foreach ( $cart->cart_contents as $product ) {
			$item = array();
			if ( $product['data']->get_type() == 'simple' ) {
				$item['model_id'] = $this->get_rewix_id($product['product_id']);
			} else { // variable product
				$item['model_id'] = $this->get_rewix_id($product['variation_id']);
			}
			$item['qty']  = (int) $product['quantity'];
			$item['type'] = self::SOLD_API_LOCK_OP;
			
			if ( $item['model_id'] ) {
				$products[]   = $item;
			}
		}

		$errors = $this->modify_growing_order($products);

        if (count($products) > 0) {
            if (isset($errors['curl_error'])) {
                $this->logger->debug('test','test');
                $this->logger->debug('test',$errors['message']);
                $this->logger->debug('test',$errors['message']);

            wc_add_notice('Error while placing order ('. $errors['message'].').', 'error');
                foreach ($this->config->setting->getOrderErrorEmails() as $email){
                    wp_mail($email,'BDroppy Plugin : Error while placing order',$errors['message']);
                }
            } else if (count($errors) > 0) {
                foreach ($errors as $model_id => $qty) {
                    if (is_cart()) {
                        wc_print_notice(
                            sprintf('Error while placing order. Product %s is not available in quantity requested (%d).',
                                $this->get_product_name_from_rewix_model_id((int)$model_id),
                                $qty
                            ), 'error'
                        );
                        foreach ($this->config->setting->getOrderErrorEmails() as $email){
                            wp_mail($email,'BDroppy Plugin : Error while placing order',sprintf('Error while placing order. Product %s is not available in quantity requested (%d).',
                                $this->get_product_name_from_rewix_model_id((int)$model_id),
                                $qty
                            ));
                        }
                    } else {
                        wc_add_notice(
                            sprintf('Error while placing order. Product %s is not available in quantity requested (%d).',
                                $this->get_product_name_from_rewix_model_id((int)$model_id),
                                $qty
                            ), 'error'
                        );
                        foreach ($this->config->setting->getOrderErrorEmails() as $email){
                            wp_mail($email,'BDroppy Plugin : Error while placing order',sprintf('Error while placing order. Product %s is not available in quantity requested (%d).',
                                $this->get_product_name_from_rewix_model_id((int)$model_id),
                                $qty
                            ));
                        }
                    }
                }
            }
        }
	}

	/**
	 * @param int $model_id
	 *
	 * @return string
	 */
	private function get_product_name_from_rewix_model_id( $model_id ) {
		global $wpdb;
		$table_name    = $wpdb->prefix . ProductModel::$table;
		$wc_product_id = (int) $wpdb->get_var( "SELECT wc_product_id FROM $table_name WHERE rewix_model_id = $model_id" );

		return get_the_title( $wc_product_id );
	}

	public function on_order_processing( $order_id ) {

        $this->logger->debug('syncOrder','on_order_processing');
		$this->send_dropshipping_order( wc_get_order( $order_id ) );

		return $order_id;
	}

	private function modify_growing_order( $operations )
    {
		$xml            = new \SimpleXMLElement( '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><root></root>' );
		$operation_lock = $xml->addChild( 'operation' );
		$operation_lock->addAttribute( 'type', self::SOLD_API_LOCK_OP );
		$operation_set = $xml->addChild( 'operation' );
		$operation_set->addAttribute( 'type', self::SOLD_API_SET_OP );
		$operation_unlock = $xml->addChild( 'operation' );
		$operation_unlock->addAttribute( 'type', self::SOLD_API_UNLOCK_OP );

		foreach ( $operations as $op ) {
			switch ( $op['type'] ) {
				case self::SOLD_API_LOCK_OP:
					$model = $operation_lock->addChild( 'model' );
					break;
				case self::SOLD_API_SET_OP:
					$model = $operation_set->addChild( 'model' );
					break;
				case self::SOLD_API_UNLOCK_OP:
					$model = $operation_unlock->addChild( 'model' );
					break;
			}
			if ( isset( $model ) ) {
				$model->addAttribute( 'stock_id', $op['model_id'] );
				$model->addAttribute( 'quantity', $op['qty'] );
				$this->logger->info( 'bdroppy', "Model Ref.ID #{$op['model_id']}, qty: {$op['qty']}, operation type: {$op['type']}" );
			} else {
				$this->logger->info( 'bdroppy', 'Invalid operation type: ' . $op['type'] );
			}
		}

		$xml_text = $xml->asXML();

        $response = $this->remote->order->sendOrderSoldLock($xml_text);

        $this->logger->debug('syncOrder','======== modify_growing_order ===========');
        $this->logger->debug('syncOrder',$response['response']['code']);
        $this->logger->debug('syncOrder',$response['body']);
        $this->logger->debug('syncOrder','------');
        $this->logger->debug('syncOrder',$xml_text);
		if ( ! $this->handle_curl_error( $response['response']['code'] ) ) {
			return array( 'curl_error' => 1,'message' => $response['body'] );
		}

		$reader = new \XMLReader();
		$reader->xml( $response['body'] );
		$reader->read();
		update_option( 'bdroppy_growing_order_id', $reader->getAttribute( 'order_id' ) );

		$errors        = array();
		$growing_order = array();

		while ( $reader->read() ) {
			if ( $reader->nodeType == \XMLReader::ELEMENT && $reader->name == 'model' ) {
				$stock_id                   = $reader->getAttribute( 'stock_id' );
				$growing_order[ $stock_id ] = array(
					'stock_id'  => $stock_id,
					'locked'    => $reader->getAttribute( 'locked' ),
					'available' => $reader->getAttribute( 'available' ),
				);
			}
		}

		foreach ( $operations as $op ) {
			if ( isset( $growing_order[ $op['model_id'] ] ) ) {
				$product             = $growing_order[ $op['model_id'] ];
				$success             = true;
				$pending_quantity    = $this->get_pending_quantity_by_rewix_model( (int) $op['model_id'] );
				$processing_quantity = $this->get_processing_quantity_by_rewix_model( (int) $op['model_id'] );

				if ( $op['type'] == self::SOLD_API_LOCK_OP && $product['locked'] < ( $pending_quantity + $processing_quantity + $op['qty'] ) ) {
					$success = false;
				} else if ( $op['type'] == self::SOLD_API_UNLOCK_OP && $product['locked'] < ( $pending_quantity + $processing_quantity - $op['qty'] ) ) {
					$success = false;
				} else if ( $op['type'] == self::SOLD_API_SET_OP && $product['locked'] < $op['qty'] ) {
					$success = false;
				}

				if ( ! $success ) {
					$this->logger->error( 'bdroppy', 'Model Ref.ID #' . $op['model_id'] . ', looked: ' . $product['locked'] . ', qty: ' . $op['qty'] . ', pending: ' . $this->get_pending_quantity_by_rewix_model( $stock_id ) . ', processing: ' . $this->get_pending_quantity_by_rewix_model( $stock_id ) . ', operation type: ' . $op['type'] . ' : OPERATION FAILED!' );
					$errors[ $op['model_id'] ] = $op['qty'];
				} else {
					$this->logger->info( 'bdroppy', 'Model Ref.ID #' . $op['model_id'] . ', looked: ' . $product['locked'] . ', qty: ' . $op['qty'] . ', pending: ' . $this->get_pending_quantity_by_rewix_model( $stock_id ) . ', processing: ' . $this->get_pending_quantity_by_rewix_model( $stock_id ) . ', operation type: ' . $op['type'] );
				}
			} else {
				$errors[ $op['model_id'] ] = $op['qty'];
			}
		}

		return $errors;
	}



	private function handle_curl_error( $http_code ) {
		if ( $http_code == 401 ) {
			$this->logger->error( 'bdroppy', 'UNAUTHORIZED!!' );
			if (function_exists('wc_print_notice')){
				wc_print_notice('You are NOT authorized to access this service. <br/> Please check your configuration in System -> Configuration or contact your supplier.');
			}
			return false;
		} else if ( $http_code == 0 ) {
			$this->logger->error( 'bdroppy', 'HTTP Error 0!!' );
			if (function_exists('wc_print_notice')){
				wc_print_notice('There has been an error executing the request.<br/> Please check your configuration in System -> Configuration');
			}
			return false;
		} else if ( $http_code != 200 ) {
			$this->logger->error( 'bdroppy', 'HTTP Error ' . $http_code . '!!' );
			if (function_exists('wc_print_notice')){
				wc_print_notice('There has been an error executing the request.<br/> HTTP Error Code: ' . $http_code);
			}
			return false;
		}

		return true;
	}

	private function get_pending_quantity_by_rewix_model( $rewix_model_id ) {
		list( $product_id, $variation_id ) = $this->get_wc_id( (int) $rewix_model_id );

		return $this->get_pending_quantity( $product_id, $variation_id );
	}

	private function get_processing_quantity_by_rewix_model( $rewix_model_id ) {
		list ( $product_id, $variation_id ) = $this->get_wc_id( (int) $rewix_model_id );

		return $this->get_processing_quantity( $product_id, $variation_id );
}

	private function get_pending_quantity( $product_id, $variation_id ) {
		if ( is_null($this->pending_cache) ) {
			global $wpdb;
			$query               = 'SELECT wc_product_id, wc_model_id, sum(qty) AS quantity FROM
					(SELECT DISTINCT order_item_id,
						(SELECT meta_value
							FROM ' . $wpdb->prefix . 'woocommerce_order_itemmeta im2
							WHERE im2.order_item_id = ' . $wpdb->prefix . 'woocommerce_order_items.order_item_id AND im2.meta_key = \'_qty\')          AS qty,
						(SELECT meta_value
							FROM ' . $wpdb->prefix . 'woocommerce_order_itemmeta im2
							WHERE im2.order_item_id = ' . $wpdb->prefix . 'woocommerce_order_items.order_item_id AND im2.meta_key = \'_product_id\')   AS wc_product_id,
						(SELECT meta_value
							FROM ' . $wpdb->prefix . 'woocommerce_order_itemmeta im2
							WHERE im2.order_item_id = ' . $wpdb->prefix . 'woocommerce_order_items.order_item_id AND im2.meta_key = \'_variation_id\') AS wc_model_id
					FROM ' . $wpdb->prefix . 'woocommerce_order_items, ' . $wpdb->prefix . 'posts
					WHERE order_id = ID AND post_type = \'shop_order\' AND post_status in (\'wc-pending\',\'wc-on-hold\')
					having wc_product_id IS NOT NULL) orders
					GROUP BY wc_product_id, wc_model_id';
			$this->pending_cache = $wpdb->get_results( $query, ARRAY_A );
		}
		foreach ( $this->pending_cache as $product ) {
			if ( $product['wc_product_id'] == $product_id && $product['wc_model_id'] == $variation_id ) {
				return (int) $product['quantity'];
			}
		}

		return 0;
	}

	private function get_processing_quantity( $product_id, $variation_id ) {
		if ( is_null($this->processing_cache) ) {
			global $wpdb;
			$query                  = 'SELECT wc_product_id, wc_model_id, sum(qty) AS quantity FROM
					(SELECT DISTINCT order_item_id,
						(SELECT meta_value
							FROM ' . $wpdb->prefix . 'woocommerce_order_itemmeta im2
							WHERE im2.order_item_id = ' . $wpdb->prefix . 'woocommerce_order_items.order_item_id AND im2.meta_key = \'_qty\')          AS qty,
						(SELECT meta_value
							FROM ' . $wpdb->prefix . 'woocommerce_order_itemmeta im2
							WHERE im2.order_item_id = ' . $wpdb->prefix . 'woocommerce_order_items.order_item_id AND im2.meta_key = \'_product_id\')   AS wc_product_id,
						(SELECT meta_value
							FROM ' . $wpdb->prefix . 'woocommerce_order_itemmeta im2
							WHERE im2.order_item_id = ' . $wpdb->prefix . 'woocommerce_order_items.order_item_id AND im2.meta_key = \'_variation_id\') AS wc_model_id
					FROM ' . $wpdb->prefix . 'woocommerce_order_items, ' . $wpdb->prefix . 'posts
					WHERE order_id = ID AND post_type = \'shop_order\' AND post_status in (\'wc-processing\')
					and not exists (
						SELECT * from ' . $wpdb->prefix . Order::$table . ' where order_id = wc_order_id
					)
					having wc_product_id IS NOT NULL) orders
					GROUP BY wc_product_id, wc_model_id';
			$this->processing_cache = $wpdb->get_results( $query, ARRAY_A );
		}
		foreach ( $this->processing_cache as $product ) {
			if ( $product['wc_product_id'] == $product_id && $product['wc_model_id'] == $variation_id ) {
				return (int) $product['quantity'];
			}
		}

		return 0;
	}

	/**
	 * @param int $rewix_model_id
	 *
	 * @return array
	 */
	private function get_wc_id( $rewix_model_id ) {
		global $wpdb;
		$table_name = $wpdb->prefix . ProductModel::$table;
		$result     = $wpdb->get_row( "SELECT wc_product_id, wc_model_id FROM $table_name WHERE rewix_model_id = $rewix_model_id" );
	
		return array( $result->wc_product_id, $result->wc_model_id );
	}

	/**
	 * @param int $wc_id
	 *
	 * @return int
	 */
    private function get_rewix_id( $wc_id ) {
        global $wpdb;
        $table_name = $wpdb->prefix . ProductModel::$table;
        $result     = $wpdb->get_var( "SELECT rewix_model_id FROM $table_name WHERE wc_model_id = $wc_id" );

        return (int) $result;
    }

    private function getCustomerData($order,$name)
    {
        if(!empty($order->{"get_shipping_".$name}()))
            return $order->{"get_shipping_".$name}();
        else
            return $order->{"get_billing_".$name}();
    }

	private function send_dropshipping_order( \WC_Order $order )
    {
		global $wpdb;
        $mixed = false;
		$items = $order->get_items();


        $catalog_id =  $this->config->catalog->get('catalog');
        $rewix_order_key = md5($catalog_id .$order->get_order_number() );
		$xml        = new \SimpleXMLElement( '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><root></root>' );
		$order_list = $xml->addChild( 'order_list' );
		$xml_order  = $order_list->addChild( 'order' );
		$item_list  = $xml_order->addChild( 'item_list' );
		$xml_order->addChild( 'key', $rewix_order_key );
		$xml_order->addChild( 'date', gmdate( 'Y/m/d H:i:s', $order->get_date_created()->getOffsetTimestamp() ) . ' +0000' );
		$xml_order->addChild( 'ref_id', $order->get_order_number() );
		$xml_order->addChild( 'user_catalog_id',$catalog_id );
		$xml_order->addChild( 'shipping_taxable', $order->get_shipping_total() );
		$xml_order->addChild( 'shipping_currency', $order->get_currency() );
		$xml_order->addChild( 'price_total', $order->get_total() );
		$xml_order->addChild( 'price_currency', $order->get_currency() );

        $xml_order->addChild('cash_on_delivery', $order->get_payment_method() == 'cod' ? $order->get_total() : '0' );

		$remote_products = 0;

        $new_items = [];
        foreach ($items as $item){

            if(is_null($new_items[$item['product_id'].'-'.$item['variation_id']])) {
                $new_items[$item['product_id'].'-'.$item['variation_id']] = $item;
            }else{
                $new_items[$item['product_id'].'-'.$item['variation_id']]['qty'] += $item['qty'];
            }
        }

		foreach ( $new_items as $item )
		{
			$product_id   = (int) $item['product_id'];
			$variation_id = (int) $item['variation_id'];
			$rewix_id     = $this->get_rewix_id( $variation_id > 0 ? $variation_id : $product_id);
//			if ( ! $rewix_id && $product_id > 0 ) {
//				$mixed = true;
//			}

			if ( $rewix_id ) {
				$remote_products ++;
				$item_node = $item_list->addChild( 'item' );
                $item_node->addChild( 'price_taxable', $item['total'] );
                $item_node->addChild( 'price_currency', $order->get_currency() );
				$item_node->addChild( 'stock_id', $rewix_id );
				$item_node->addChild( 'quantity', (int) $item['qty'] );
				$this->logger->info( 'bdroppy', 'Creating dropshipping order with model ID#' . $rewix_id . ' with quantity ' . $item['qty'] );
			}
		}

		if ( $remote_products == 0 ) {
			return false;
		}
		if ( $mixed ){
			$this->logger->error( 'bdroppy', 'Order #' . $order->get_order_number() . ': Mixed Order!!!' );
			
			return false;
		}
		$recipient_details = $xml_order->addChild( 'recipient_details' );
		$email = $recipient_details->addChild( 'email', $order->get_billing_email());
        $recipient = $recipient_details->addChild( 'recipient', $this->getCustomerData($order,'first_name') . ' ' . $this->getCustomerData($order,'last_name') );
        $careof = $recipient_details->addChild( 'careof', $this->getCustomerData($order,'company') );
        $cfpiva = $recipient_details->addChild( 'cfpiva' );
        $customer_key = $recipient_details->addChild( 'customer_key', $order->get_customer_id() );
        $notes = $recipient_details->addChild( 'notes', $order->get_customer_note() );

        $phone = $recipient_details->addChild( 'phone' );
        $phone_prifix = $phone->addChild( 'prefix');
        $phone_number = $phone->addChild( 'number', $order->get_billing_phone() );

        $address = $recipient_details->addChild( 'address' );
        $street_type = $address->addChild( 'street_type' );
        $street_name = $address->addChild( 'street_name', $this->getCustomerData($order,'address_1'). ' ' . $this->getCustomerData($order,'address_2') );
        $address_number = $address->addChild( 'address_number' );
        $zip = $address->addChild( 'zip', $this->getCustomerData($order,'postcode') );
        $city = $address->addChild( 'city', $this->getCustomerData($order,'city') );
        $province = $address->addChild( 'province', $this->getCustomerData($order,'state') );
        $country_code = $address->addChild( 'countrycode', $this->getCustomerData($order,'country') );

		$xml_text = $xml->asXML();

        $response = $this->remote->order->sendDropshippingOrder($xml_text);
		$this->logger->debug('syncOrder',"========= send_dropshipping_order =========");
		$this->logger->debug('syncOrder',$response['response']['code']);
		$this->logger->debug('syncOrder',json_encode($response['body']));
		$this->logger->debug('syncOrder',$xml_text);

		if($response['response']['code'] == 400)
		{
            $body   = json_decode($response['body']);
            if (isset($body->code) && $body->code == 'order_already_exists')
            {
                $remote_order_table_name = $wpdb->prefix . Order::$table;
                $wpdb->insert(
                    $remote_order_table_name,
                    [
                        'wc_order_id'     => (int) $order->get_order_number(),
                        'rewix_order_key' => $body->data->key,
                        'rewix_order_id'  => 0,
                        'status'          => 0
                    ]
                );
            }
            return false;
		}elseif( ! $this->handle_curl_error($response['response']['code']) ) {
			return false;
		}

		$this->logger->info( 'bdroppy', 'Rewix order key: ' . $rewix_order_key . ' ' . $response['body'] );


        $response = $this->remote->order->getOrderStatusByKey($rewix_order_key);

		if ( $response['response']['code'] == 401 ) {
			$this->logger->error( 'bdroppy', 'Send dropshipping order: UNAUTHORIZED!!' );
			return false;
		} else if ( $response['response']['code'] == 500 ) {
			$this->logger->error( 'bdroppy', 'Exception: Order #' . $order->get_order_number() . ' does not exists on rewix platform' );
			$this->logger->error( 'bdroppy', 'Dropshipping operation for order #' . $order->get_order_number() . ' failed!!' );
			$this->logger->error( 'bdroppy', json_encode($response));
			$remote_order_table_name = $wpdb->prefix . Order::$table;
			$wpdb->update(
				$remote_order_table_name,
				['status' => '-1'], ['wc_order_id' => $order->get_order_number()],
				['%s'], ['%d']
			);
			return false;
		} else if ( $response['response']['code'] != 200 ) {
            $this->logger->error( 'bdroppy', 'Url : ' . $rewix_order_key );

            $this->logger->error( 'bdroppy', 'Send dropshipping order: ERROR ' . $response['response']['code']  );
			return false;
		}

		$reader = new \XMLReader();
		$reader->xml( $response['body'] );
		$doc = new \DOMDocument( '1.0', 'UTF-8' );

		$this->logger->info( 'bdroppy', 'dropshipping order created successfully!!' );
		while ( $reader->read() ) {
			if ( $reader->nodeType == \XMLReader::ELEMENT && $reader->name == 'order' ) {
				$xml_order      = simplexml_import_dom( $doc->importNode( $reader->expand(), true ) );
				$rewix_order_id = (int) $xml_order->order_id;
				$status         = (int) $xml_order->status;
				$order_id       = (int) $order->get_order_number();

				$remote_order_table_name = $wpdb->prefix . Order::$table;
				$wpdb->insert(
					$remote_order_table_name,
					[
                        'wc_order_id'    => $order_id,
                        'rewix_order_key' => $rewix_order_key,
                        'rewix_order_id' => $rewix_order_id,
                        'status'         => $status
                    ]
				);
				$this->logger->info( 'bdroppy', 'Entry (' . $rewix_order_id . ',' . $order_id . ') in association table created' );
				$this->logger->info( 'bdroppy', 'Entries in association table created' );
				$this->logger->info( 'bdroppy', 'Supplier order created successfully!!' );
			}
		}
	}

	public function update_order_statuses() {
		global $wpdb;
		$this->logger->info( 'bdroppy', 'Order statuses update procedures STARTED!' );

		$remote_order_table_name = $wpdb->prefix . Order::$table;
		$status                  = Order::STATUS_DISPATCHED;
		$status2                  = Order::STATUS_FAILED;
		$status3                 = Order::STATUS_NOAVAILABILITY;
		$orders                  = $wpdb->get_results( "SELECT * FROM $remote_order_table_name WHERE status not in($status,$status2 ,$status3)" );

		foreach ( $orders as $bd_order ) {
		    global $order;
            $order = wc_get_order( (int) $bd_order->wc_order_id );
			if (is_bool($order)){
				continue;
			} else if ($order->get_type() != 'shop_order') { //Let's skip WC_Order_Refund
				continue;
			}
			
			$this->logger->info( 'bdroppy', 'Processing Order_id: #' . (int) $bd_order->wc_order_id);

            $response = $this->remote->order->getOrderStatusByKey($bd_order->rewix_order_key);

			if ( $response['response']['code'] == 401 ) {
				$this->logger->error( 'bdroppy', 'UNAUTHORIZED!!' );

				return false;
			} else if ( $response['response']['code'] == 500 ) {
				$this->logger->error( 'bdroppy', 'Exception: Order #' . $order->get_order_number() . ' does not exists on rewix platform' );
			} else if ( $response['response']['code'] != 200 ) {
				$this->logger->error( 'bdroppy', 'ERROR ' . $response['response']['code'] . ' ' . $response['response']['message'] . ' - Exception: Order #' . $order->get_order_number() );
			} else {
				$reader = new \XMLReader();
				$reader->xml( $response['body'] );
				$doc = new \DOMDocument( '1.0', 'UTF-8' );
	
				while ( $reader->read() ) {
					if ( $reader->nodeType == \XMLReader::ELEMENT && $reader->name == 'order' ) {
						$xml_order = simplexml_import_dom( $doc->importNode( $reader->expand(), true ) );
						$status    = (int) $xml_order->status;
						$order_id  = (int) $xml_order->order_id;
						$this->logger->info( 'bdroppy', 'Order_id: #' . $order_id . ' NEW Status:' . $status . ' OLD Status ' . $bd_order->status );
//						if ( (int) $bd_order->status != $status ) {

                                Order::where('id',$bd_order->id)->update([
                                'status' =>  $status,
                                'rewix_order_id' =>  $order_id
                            ]);

                            $this->logger->info( 'bdroppy', 'Order status Update: WC ID #' . $bd_order->wc_order_id . ': new status [' . $status . ']' );
	
							if ( $status == Order::STATUS_DISPATCHED ) {
                                update_post_meta( $bd_order->wc_order_id, 'tracking_url', (string) $xml_order->tracking_url );
                                update_post_meta($bd_order->wc_order_id,'_bdroppy_shipment_tracking_items',(string) $xml_order->tracking_url);
                                $order->update_status( 'completed' );
							}
//						}
					}
				}
			}			
		}
		$this->logger->info( 'bdroppy', 'Order statuses update procedures COMPLETED!' );
	}

	public function sync_with_supplier() {
		$this->sync_booked_products();
		$this->send_missing_orders(); // if dropshipper
	}

	private function sync_booked_products() {
		$booked_products = $this->get_growing_order_products();
		$rewix_products  = $this->get_local_rewix_products();

		$locked     = 0;
		$available  = 0;
		$operations = array();
		foreach ( $rewix_products as $rewix_product ) {
			$locked    = 0;
			$available = 0;
			if ( $booked_products ) {
				foreach ( $booked_products as $booked_product ) {
					if ( $booked_product['stock_id'] == $rewix_product['rewix_model_id'] ) {
						$locked    = $booked_product['locked'];
						$available = $booked_product['available'];
						break;
					}
				}
			}

			$processing_qty = $this->get_processing_quantity( (int) $rewix_product['wc_product_id'], (int) $rewix_product['wc_model_id'] );
			$pending_qty    = $this->get_pending_quantity( (int) $rewix_product['wc_product_id'], (int) $rewix_product['wc_model_id'] );

			if ( $processing_qty + $pending_qty != $locked ) {
				$operations[] = array(
					'type'     => self::SOLD_API_SET_OP,
					'model_id' => $rewix_product['rewix_model_id'],
					'qty'      => $processing_qty + $pending_qty,
				);
			}
		}
		if (count($operations) > 0){
			$this->modify_growing_order( $operations );
		}
	}

	private function send_missing_orders() {
		global $wpdb;
		$table_name = $wpdb->prefix . Order::$table;
		$orders     = $wpdb->get_col( "SELECT ID FROM {$wpdb->prefix}posts " .
		                              "WHERE post_status = 'wc-processing' AND ID NOT IN (SELECT wc_order_id FROM $table_name)" );
		foreach ( $orders as $order_id )
		{
			$this->send_dropshipping_order( wc_get_order( $order_id ) );
		}
	}

	private function get_growing_order_products()
    {
        $response = $this->remote->order->getOrderDropshippingLock();


		if ( ! $this->handle_curl_error( $response['response']['code'] ) ) {
			return false;
		}

		$reader = new \XMLReader();
		$reader->xml( $response['body'] );

		$doc = new \DOMDocument( '1.0', 'UTF-8' );
		$reader->read();
		update_option( 'bdroppy_growing_order_id', $reader->getAttribute( 'order_id' ) );

		$products = array();

		while ( $reader->read() ) {
			if ( $reader->nodeType == \XMLReader::ELEMENT && $reader->name == 'model' ) {
				$product              = array();
				$product['stock_id']  = $reader->getAttribute( 'stock_id' );
				$product['locked']    = $reader->getAttribute( 'locked' );
				$product['available'] = $reader->getAttribute( 'available' );
				$products[]           = $product;
			}
		}

		return $products;
	}

	private function get_local_rewix_products() {
		global $wpdb;

		return $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . ProductModel::$table, ARRAY_A );
	}

	public function on_order_delete( $post_id ) {
		// We check if the global post type isn't ours and just return
		global $post_type, $wpdb;
		if ( $post_type != 'shop_order' ) {
			return;
		}
		$table_name = $wpdb->prefix . Order::$table;
		$wpdb->delete( $table_name, array( 'wc_order_id' => $post_id ), array( '%d' ) );
	}
}
