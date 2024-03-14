<?php /** @noinspection ALL */

namespace src\fortnox\api;

if ( !defined( 'ABSPATH'  ) ) die();

use Exception;
use src\fortnox\WF_Plugin;
use src\fortnox\WF_Utils;
use src\help\WF_Help_Links;
use WC_Customer;
use WC_Order;


class WF_Orders {

    const EU_COUNTRIES = [ 'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'EL',
        'ES', 'FI', 'FR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV',
        'MT', 'NL', 'PL', 'PT', 'RO', 'SI', 'SK', 'HR' ];

    const FORTNOX_ERROR_CODE_CANNOT_FIND_ORDER = 2000439;
    const FORTNOX_ERROR_CODE_CANNOT_BOOKKEEP = 2003007;
    const FORTNOX_ERROR_CODE_ORDER_ALREADY_INVOICED = 2000496;
    const FORTNOX_ERROR_CODE_ORDER_ALREADY_INVOICED_2 = 2001242;

    public static function get_order_number( $order_id, $order ){
        return preg_replace( '/\D/', '', apply_filters( 'wf_order_number', apply_filters( 'woocommerce_order_number', $order_id, $order ) ) );
    }

    /**
     * @param \WC_Order $order
     * @param $customer
     * @return array
     * @throws \Exception
     */
    public static function format_order_rows( $order, $customer ){

        $order_rows = [];

        foreach( $order->get_items() as $item ) {
            $order_row = self::create_order_row( $item, $order, $customer );
            if ( isset( $order_row[0] ) && is_array( $order_row[0] ) ) {
                $func = function( $row ) {
                    return $row;
                };
                $order_rows = array_merge( $order_rows, array_map( $func, $order_row ) );
            }
            else{
                $order_rows[] = $order_row;
            }
        }
        return $order_rows;
    }

    /** Returns order payload
     * @param $order_id
     * @param $customer_number
     * @return mixed|void
     * @throws \Exception
     */
    public static function format_order_payload( $order_id, $customer_number, $customer )
    {
        $order = wc_get_order( $order_id );
        self::validate_order_items( $order );

        if ( has_action( 'fortnox_before_order_sync'  ) ) {
            wc_deprecated_function( 'The fortnox_before_order_sync action', '', 'wf_order_before_create_or_update'  );
            do_action( 'fortnox_before_order_sync', array( $order ) );
        } else {
            do_action( 'wf_order_before_create_or_update', array( $order ) );
        }

        try {

            $order_number = self::get_order_number( $order_id, $order );

            $fortnox_order = apply_filters( 'wf_order_payload_before_create_or_update', [
                'CustomerNumber' => $customer_number,
                'DocumentNumber' => $order_number,
                'YourOrderNumber' => apply_filters( 'woocommerce_order_number', $order_id, $order ),
                'ExternalInvoiceReference1' => apply_filters( 'woocommerce_order_number', $order_id, $order ),
                'OrderDate' => substr( $order->get_date_created(), 0, 10), # To cut off order time
                'VATIncluded' => apply_filters( 'wf_order_vat_included', false),
                'Currency' => $order->get_currency(),
            ], $order, $customer );

            if ( has_filter( 'wetail_fortnox_order'  ) ) {
                wc_deprecated_function( 'The wetail_fortnox_order filter', '', 'wf_order_payload_before_create_or_update'  );
                $fortnox_order = apply_filters( 'wetail_fortnox_order', [
                    'CustomerNumber' => $customer_number,
                    'DocumentNumber' => $order_number,
                    'YourOrderNumber' => apply_filters( 'woocommerce_order_number', $order_id, $order ),
                    'ExternalInvoiceReference1' => apply_filters( 'woocommerce_order_number', $order_id, $order ),
                    'OrderDate' => substr( $order->get_date_created(), 0, 10), # To cut off order time
                    'VATIncluded' => apply_filters( 'wetail_fortnox_sync_order_vat_included', false),
                    'Currency' => $order->get_currency(),
                ], $order );
            }

            $order_rows = self::format_order_rows( $order, $customer );
            $shipping = self::get_shipping( $order, $customer );

            if ( is_array( $shipping ) ) {
                $order_rows[] = $shipping;
                $fortnox_order['Freight'] = 0.0;
            } else {
                $fortnox_order['Freight'] = $shipping;
            }

            $fortnox_order['OrderRows'] = $order_rows;
            $fortnox_order = self::handle_currency( $fortnox_order, $order->get_currency() );
            $fortnox_order = self::handle_fees( $order, $fortnox_order );
            $fortnox_order['PriceList'] = WF_Products::fortnox_price_list();

            if ( get_option( 'fortnox_write_payment_type_to_ordertext'  ) )
                $fortnox_order['Remarks'] = $order->get_payment_method_title();

            if ( get_option( 'fortnox_add_customer_notes_to_order'  ) ){
                if( array_key_exists('Remarks', $fortnox_order ) ){
                    $fortnox_order['Remarks'] .= ' - ' . $order->get_customer_note();
                }
                else{
                    $fortnox_order['Remarks'] = $order->get_customer_note();
                }
            }

            if ( get_option( 'fortnox_copy_remarks_to_invoice'  ) )
                $fortnox_order['CopyRemarks'] = true;

            if ( $cost_center = get_option( 'fortnox_cost_center'  ) ) {
                $fortnox_order['CostCenter'] = $cost_center;
            }

            if ( get_option( 'fortnox_has_warehouse_module'  ) ) {
                if ( ! empty( get_option( 'fortnox_warehouse_delivery_status'  ) ) ) {
                    $fortnox_order['DeliveryState'] = get_option( 'fortnox_warehouse_delivery_status'  );
                } else {
                    $fortnox_order['DeliveryState'] = 'delivery';
                }

            }

            $fortnox_order = self::set_way_of_delivery( $order, $fortnox_order );
            $fortnox_order = self::set_payment_terms( $order, $fortnox_order );
            
            return $fortnox_order;
        }
        catch( \Exception $error ) {
            self::add_order_log( $order, $error );
            self::set_order_notice_flag( $order );

            throw new \Exception( $error->getMessage(), $error->getCode() );
        }
    }

    /**
     * Sync order to Fortnox
     * @throws \Exception
     * @param int $order_id
     * @return mixed
     */
    public static function sync( $order_id ) {
        $order = wc_get_order( $order_id );
        try{
            $customer_payload = WF_Customers::format_customer_payload( $order );
            $customer_number = WF_Customers::sync( $customer_payload, $order );

            $order_payload = self::format_order_payload( $order_id, $customer_number, $customer_payload );

            self::send_order_to_fortnox( apply_filters( 'wf_order_payload_before_send', $order_payload, $order ), self::get_order_number( $order_id, $order ) );

            self::set_order_as_synced( $order );

            $order->add_order_note( __( 'Order sent to Fortnox', WF_Plugin::TEXTDOMAIN ) );

            $invoice_number = null;

        } catch( \Exception $error ) {
            if( isset( $order ) ){
                self::add_order_log( $order, $error );
                self::set_order_notice_flag( $order );
            }

            throw new \Exception( $error->getMessage(), $error->getCode() );

        }

        if ( has_action( 'fortnox_after_order_sync'  ) ) {
            wc_deprecated_function( 'The fortnox_after_order_sync action', '', 'wf_order_after_create_or_update'  );
            do_action( 'fortnox_after_order_sync', array( $order ) );
        }
        else{
            do_action( 'wf_order_after_create_or_update', $order );
        }
        return true;
    }

    /**
     * Calculate item discount
     *
     * @param float $subtotal
     * @param float $total
     * @param int $quantity
     * @return mixed
     */
    public static function calculate_item_discount( $subtotal, $total, $quantity ){

        if ( $subtotal != $total ) {
            $item_discount = $subtotal - $total;

            if ( $quantity > 1 )
                $item_discount = $item_discount * $quantity;

            return $item_discount;
        }
        return 0.0;
    }

    /**
     * Add log to order
     *
     * @param WC_Order $order
     * @param \Exception $error
     */
    public static function add_order_log( $order, $error ){
        $order->add_order_note( 'Fortnox: Fel vid synkronisering </br>' . $error->getMessage() . ' ' . WF_Help_Links::get_error_log_text( $error->getCode() ) );
    }

    /**
     * Creates order row array
     *
     * @param \WC_Order_Item_Product $item
     * @param \WC_Order $order
     * @param WC_Customer $customer
     * @return mixed
     * @throws \Exception
     */
    public static function create_order_row( $item, $order, $customer ){

        $product = apply_filters( 'wf_order_row_product',( self::item_is_variation( $item ) ) ? wc_get_product( $item->get_variation_id() ) : wc_get_product( $item->get_product_id() ), $item, $order );
        $product_name = apply_filters(
            'wf_product_name',
            self::get_product_name( $item ),
            $item,
            $order
        );

        if ( ! $product ){
            throw new \Exception( __( "Product does not exist", WF_Plugin::TEXTDOMAIN ), 1000 );
        }

        if ( wc_get_product( $product->get_id() ) ) {
            WF_Products::sync( $product->get_id(), $sync_stock=false );
        }

        $subtotal = $order->get_item_subtotal( $item, false, false );
        $total = $order->get_item_total( $item, false, false );


        $order_row = apply_filters( 'wf_order_row_payload_before_create_or_update', [
            'ArticleNumber'     => WF_Products::sanitized_sku( $product->get_sku() ),
            'Description'       => WF_Products::sanitize_description( $product_name ),
            'DeliveredQuantity' => $item->get_quantity(),
            'OrderedQuantity'   => $item->get_quantity(),
            'Unit'              => "st",
            'Price'             => $subtotal,
            'Discount'          => self::calculate_item_discount( $subtotal, $total, $item->get_quantity() ),
            'DiscountType'      => "AMOUNT",
            'VAT'               => self::get_tax( $order, $product, $customer ),
        ], $product, $item, $order );

        if ( has_filter( 'wetail_fortnox_sync_modify_order_row'  ) ) {
            wc_deprecated_function( 'The wetail_fortnox_sync_modify_order_row filter', '', 'wf_order_row_payload_before_create_or_update'  );
            $order_row = apply_filters( 'wetail_fortnox_sync_modify_order_row', [
                'ArticleNumber'     => WF_Products::sanitized_sku( $product->get_sku() ),
                'Description'       => WF_Products::sanitize_description( $product_name ),
                'DeliveredQuantity' => $item->get_quantity(),
                'OrderedQuantity'   => $item->get_quantity(),
                'Unit'              => "st",
                'Price'             => $subtotal,
                'Discount'          => self::calculate_item_discount( $subtotal, $total, $item->get_quantity() ),
                'DiscountType'      => "AMOUNT",
                'VAT'               => self::get_tax( $order, $product, $customer ),
            ], $product, $item, $order );
        }

        $account_number = self::get_sales_account( $order, $item );

        if ( has_filter( 'wetail_fortnox_modify_order_row_sales_account'  ) ) {
            wc_deprecated_function( 'The wetail_fortnox_modify_order_row_sales_account filter', '', 'wf_order_row_sales_account'  );
            $account_number = apply_filters( 'wetail_fortnox_modify_order_row_sales_account', false, $order, $item );
        }

        if( $account_number ){
            $order_row['AccountNumber'] = $account_number;
        }

        return $order_row;

    }

    /*** Return Sales Account for given country
     * @param WC_Order $order
     * @param \WC_Order_Item $item
     * @return mixed
     */
    public static function get_sales_account( $order, $item ){
        $country_code = $order->get_billing_country() ? $order->get_billing_country() : $order->get_shipping_country();
        return apply_filters( 'wf_order_row_sales_account', get_option("wf_eu_sales_account_" . strtolower($country_code) ), $order, $item );
    }

    /**
     * Mark order as ready
     * @param int $order_number
     * @return array
     * @throws \Exception
     */
    public static function mark_as_ready( $order_number ){
        return WF_Request::put("/orders/" . preg_replace( '/\D/', '', $order_number ) . "/warehouseready" );
    }

    /**
     * Handle Custom Shipping
     *
     * @param WC_Order $order
     * @param mixed $shipping_sku
     * @return mixed
     * @throws \Exception
     */
    public static function get_custom_shipping( $order, $shipping_sku, $customer ){

        $shipping_product = WF_Products::get_product_by_sku( $shipping_sku );


        if ( $shipping_product ) {

            $tax_rate = self::get_tax( $order, $shipping_product, $customer );

            $address = $order->get_address();
            $shipping_account = self::get_shipping_account( $address['country'] );

            $shipping_account = apply_filters( 'wf_order_shipping_account', $shipping_account, $order );

            if ( has_filter( 'wetail_fortnox_shipping_account'  ) ) {
                wc_deprecated_function( 'The wetail_fortnox_shipping_account filter', '', 'wf_order_shipping_account'  );
                $shipping_account = apply_filters( 'wetail_fortnox_shipping_account', $shipping_account, $order );
            }

            return apply_filters( 'wf_custom_shipping',[
                'AccountNumber'     => $shipping_account,
                'Description'       => $shipping_product->get_title(),
                'DeliveredQuantity' => 1,
                'OrderedQuantity'   => 1,
                'Unit'              => "st",
                'Price'             => $order->get_total_shipping(),
                'Discount'          => 0,
                'DiscountType'      => "AMOUNT",
                'VAT'               => $tax_rate
            ], $order );
        }
        return false;
    }

    /**
     * @param \WC_Order_Item_Product $item
     * @return string
     */
    public static function get_product_name( $item ){
        return $item->get_name();
    }

    /**
     * Get shipping account from Fortnox
     * @throws \Exception
     * @return mixed
     */
	public static function get_shipping_account( $country_code ) {
		try {
		    if ( self::outside_eu( $country_code ) ){
                $shipping_account = '3522';
            }
            else{
                $shipping_account = WF_Request::get( "/predefinedaccounts/FREIGHT/" )
                    ->PreDefinedAccount->Account;
            }
		}
		catch( \Exception $error ) {
			throw new \Exception( $error->getMessage() );
		}
		
		return $shipping_account;
	}

    /**
     * @param \WC_Order $order
     * @param \WC_Product $product
     * @param $customer
     * @return int
     */
    public static function get_tax( $order, $product, $customer ){
        $vat_number = WF_Utils::get_vat_number( $order->get_id() );
        if ( $vat_number ) {
            if ( WF_Utils::vat_number_is_valid( $order ) ) {
                return 0;
            }
        }

        if ( WF_Utils::get_order_meta_compat( $order->get_id(), '_billing_vat_number' ) ) {
            if ( WF_Utils::vat_number_is_valid( $order ) ) {
                return 0;
            }
        }

        $vat_number = WF_Utils::get_order_meta_compat( $order->get_id(), apply_filters( 'wf_eu_vat_meta_key', '__'  ) );
        // Set customer VAT type based on country
        if ( ! empty( $vat_number ) ) {
            return 0;
        }

        return  WF_Utils::get_wc_tax_rate( $product, $customer['DeliveryCountryCode'] );
    }

    /**
     * Handle Currency
     *
     * @param mixed $fortnox_order
     * @param int $currency
     * @return mixed
     */
    public static function handle_currency( $fortnox_order, $currency ){

        // Attempt to get currency rate if currency isn't SEK
        if ( $currency !== 'SEK'  ) {
            try {

                if ( get_option( "fortnox_get_currency_rate" ) ){
                    $currency_rate = WF_Request::get( "/currencies/{$currency}")->Currency->BuyRate;
                    if ( isset( $currency_rate ) ){
                        $fortnox_order["CurrencyRate"] = $currency_rate;
                    }
                }

            } catch ( \Exception $e ) {
            }
        }
        return $fortnox_order;
    }

    /** Returns Administation Fee names
     * @return array
     */
    public static function administration_fee_names(){
        $administration_fee_string = preg_replace( '/\s+/', '', strtolower( get_option( 'fortnox_administration_fee_names'  ) ) );
        $administration_fee_names = explode(",", $administration_fee_string );
        $administration_fee_names[] = "faktura";
        return $administration_fee_names;
    }

    /**
     * Handle Fees
     *
     * @param \WC_Order $order
     * @param mixed $fortnox_order
     * @return mixed
     */
    public static function handle_fees( $order, $fortnox_order ){ #TODO TEST
        $administration_fee_names = self::administration_fee_names();
        $invoice_fee = array_reduce( $order->get_fees(), function( $invoice_fee, $fee ) use ( $administration_fee_names, $order ) {

            if( in_array( strtolower( $fee->get_name() ), $administration_fee_names ) ){
                return $order->get_item_total( $fee, false, false );
            }
            return $invoice_fee;
        });


        if ( isset( $invoice_fee ) )
            $fortnox_order['AdministrationFee'] = $invoice_fee;

        return $fortnox_order;
    }

    /**
     * Handle Regular Shipping
     *
     * @param WC_Order $order
     * @return mixed
     */
    public static function get_regular_shipping( $order ){

        $callback = function ( $shipping ){
            return floatval( $shipping->get_total() );
        };

        return array_sum( array_map( $callback, $order->get_shipping_methods() ) );
    }

    /**
     * Handle Shipping
     *
     * @param WC_Order $order
     * @param array $customer
     * @return mixed
     * @throws \Exception
     */
    public static function get_shipping( $order, $customer ){

        $address = $order->get_address();

        if ( self::outside_eu( $address['country'] ) ) {
            $shipping_sku = get_option( 'fortnox_shipping_product_sku_non_eu'  );
        }
        else{
            $shipping_sku = get_option( 'fortnox_shipping_product_sku'  );
        }

        # If exists, then the product regulating the VAT for delivery will be added to the order
        if( $shipping_sku ) {

            return self::get_custom_shipping( $order, $shipping_sku, $customer );
        }
        else{
            return self::get_regular_shipping( $order );
        }
    }

    /**
     * Check whether order synced to Fortnox

     *
     * @param int $order_id
     * @return mixed
     */
    public static function is_synced( $order_id ) {
        return WF_Utils::get_order_meta_compat( $order_id, '_fortnox_order_synced' );
    }

    /**
     * Check whether order has notices

     *
     * @param int $order_id
     * @return mixed
     */
    public static function has_notices( $order_id ) {
        return WF_Utils::get_order_meta_compat( $order_id, '_fortnox_order_notices' );
    }

    /**
     * Checks if cart item is a variation
     *
     * @param mixed $item
     * @return boolean
     */
    public static function item_is_variation( $item ){
        if( ! empty( $item->get_variation_id() ) && wc_get_product( $item->get_product_id() ) ) {
            return true;
        }
        return false;
    }

    /**
     * Returns true if order exists in Fortnox.
     *
     * @param int $order_number
     * @return boolean
     */
    public static function order_exists( $order_number ){

        try {
            $response = WF_Request::get( "/orders/{$order_number}" )->Order->DocumentNumber;
        }
        catch( \Exception $error ) {
            if( $error->getCode() === self::FORTNOX_ERROR_CODE_CANNOT_FIND_ORDER ){
                return false;
            }
        }

        return (  isset( $response ) && $response == $order_number ? true : false );
    }

    /**
     * Returns true if country_code is EU
     *
     * @param string $country_code
     * @return bool
     */
    public static function outside_eu( $country_code ) {
        if ( $country_code == "SE" || in_array( $country_code, self::EU_COUNTRIES ) ) {
            return false;
        }
        return true;
    }

    /**
     * Set Payment terms.
     *
     * @param WC_Order $order
     * @param mixed $fortnox_order
     * @return mixed
     */
    public static function set_payment_terms( $order, $fortnox_order ){

        $payment_term = get_option( 'fortnox_invoice_payment_terms_' . $order->get_payment_method() );

        if( $payment_term ) {
            $fortnox_order['TermsOfPayment'] = $payment_term;
        }

        return $fortnox_order;
    }
    /**
     * Set Way of Delivery.
     *
     * @param WC_Order $order
     * @param mixed $fortnox_order
     * @return mixed
     */
    public static function set_way_of_delivery( $order, $fortnox_order ){

        $zone_id = WF_Utils::get_zone_id( $order->get_shipping_country(), $order->get_shipping_postcode() );

        if( $shipping = $order->get_items( 'shipping' ) ) {
            $shipping = reset( $shipping );

            $fortnox_shipping_code = self::get_way_of_delivery( $shipping, $zone_id );

            if( $fortnox_shipping_code ) {
	            $fortnox_order['WayOfDelivery'] = $fortnox_shipping_code;
            }
        }

        $fortnox_order['WayOfDelivery'] = apply_filters( 'wf_order_way_of_delivery', array_key_exists( 'WayOfDelivery', $fortnox_order ) ? $fortnox_order['WayOfDelivery'] : '', $shipping );

        if ( has_filter( 'wetail_fortnox_way_of_delivery'  ) ) {
            wc_deprecated_function( 'The wetail_fortnox_way_of_delivery filter', '', 'wf_order_way_of_delivery'  );
            $fortnox_order['WayOfDelivery'] = apply_filters( 'wetail_fortnox_way_of_delivery', array_key_exists( 'WayOfDelivery', $fortnox_order ) ? $fortnox_order['WayOfDelivery'] : '', $shipping );
        }

        $fortnox_order['TermsOfDelivery'] = apply_filters( 'wf_order_terms_of_delivery', array_key_exists( 'TermsOfDelivery', $fortnox_order ) ? $fortnox_order['TermsOfDelivery'] : '' , $shipping );

        if ( has_filter( 'wetail_fortnox_terms_of_delivery'  ) ) {
            wc_deprecated_function( 'The wetail_fortnox_terms_of_delivery filter', '', 'wf_order_terms_of_delivery'  );
            $fortnox_order['TermsOfDelivery'] = apply_filters( 'wetail_fortnox_terms_of_delivery', array_key_exists( 'TermsOfDelivery', $fortnox_order ) ? $fortnox_order['TermsOfDelivery'] : '' , $shipping );
        }

        return $fortnox_order;
    }

    /** Gets way of delivery for shipping and zone
     * Backwards compatible
     * @param $shipping
     * @param $zone_id
     * @return mixed|void
     */
    public static function get_way_of_delivery( $shipping, $zone_id ){
        $fortnox_shipping_code = get_option( "fortnox_shipping_code_" . $shipping['method_id'] . ":" . $shipping['instance_id'] .":" . $zone_id );
        if( $fortnox_shipping_code ) {
            return $fortnox_shipping_code;
        }

        $fortnox_shipping_code = get_option( "fortnox_shipping_code_" . $shipping['method_id'] . ":" . $zone_id );
        if( $fortnox_shipping_code ) {
            return $fortnox_shipping_code;
        }
    }

    /**
     * Sets postmeta '_fortnox_order_synced' of shop_order to true
     * @param int $order_id
     */
    public static function set_order_as_synced( $wc_order ) {
        $wc_order->update_meta_data( '_fortnox_order_synced', 1 );
        $wc_order->save();
    }

    /**
     * Sets postmeta 'set_order_notice_flag' of shop_order to true
     * @param int $order_id
     */
    public static function set_order_notice_flag( $wc_order ) {
        if( ! self::is_synced( $wc_order->get_id() ) ){
            $wc_order->update_meta_data( '_fortnox_order_notices', 1 );
            $wc_order->save();
        }

    }

    /**
     * Sends order to Fortnox either as POST or PUT
     * @param mixed $fortnox_order
     * @param mixed $order_number
     * @return mixed
     * @throws \Exception
     */
    public static function send_order_to_fortnox( $fortnox_order, $order_number ){
        # Create Order in Fortnox or update existing one
        if ( self::order_exists( $order_number ) ) {
            $response = WF_Request::put("/orders/{$order_number}", [
                'Order' => $fortnox_order
            ] );
        }
        else {
            $response = WF_Request::post( "/orders", [
                'Order' => $fortnox_order
            ] );
        }
        return $response;
    }

    /**
     * Checks every cart item for skus. Throws exception if not
     * @throws \Exception
     * @param WC_Order $order
     * @return mixed
     */
    public static function validate_order_items( $order ){

        if( get_option( 'fortnox_auto_generate_sku'  ) ){
            return true;
        }

        foreach( $order->get_items() as $item ) {
            $product = wc_get_product( $item->get_product_id() );
            if ( ! $product->get_sku() ) {
                if( ! get_option( 'fortnox_sync_master_product'  ) && $product->get_type() == 'variable'  ){
                    continue;
                }

                throw new \Exception( __( "Product ID {$product->get_id()} is missing SKU.", WF_Plugin::TEXTDOMAIN ),
                    "2000166" );
            }
        }
        return true;
    }

    /**
     * Voids order
     * @throws \Exception
     * @param WC_Order $order
     * @return mixed
     */
    public static function cancel_order( $order ){
        $order_number = self::get_order_number( $order->get_id(), $order );
        if ( self::order_exists( $order_number ) ) {
            $response = WF_Request::put("/orders/{$order_number}/cancel", [] );
        }
    }
}
