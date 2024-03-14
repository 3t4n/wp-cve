<?php

class WPLA_ReportProcessor {


    // process  FBA Amazon Fulfilled Shipments Report
    // - not called via ajax right now
    public static function processAmazonShipmentsReportPage( $report, $rows, $job, $task ) {
        WPLA()->logger->debug('processAmazonShipmentsReportPage #'. $report->id);
        $wc_orders_processed = 0;

        // process rows
        foreach ($rows as $row) {

            // check for MCF order ID
            $order_id               = str_replace( '#','', $row['merchant-order-id'] );
            $order_item_id          = $row['merchant-order-item-id'];
            $is_mcf_order           = true;
            // if ( empty( $order_id ) ) continue;
            // if ( empty( $order_item_id ) ) continue;
            WPLA()->logger->debug( 'order_id: '. $order_id );

            // no merchant-order-id means this order was placed on Amazon - find WooCommerce order by reference
            if ( empty( $order_id ) ) {
                WPLA()->logger->debug( 'order_id is empty. Attempting to load from the amazon-order-id');

                $amazon_order_id = $row['amazon-order-id'];
                $is_mcf_order    = false;

                $om    = new WPLA_OrdersModel();
                $order = $om->getOrderByOrderID( $amazon_order_id );
                if ( $order ) $order_id = $order->post_id;
                WPLA()->logger->debug( 'order_id from amazon order: '. $order_id );
            };
            if ( empty( $order_id ) ) {
                WPLA()->logger->debug( 'order_id still empty. skipping');
                continue;
            }

            // get WooCommerce order
            $_order = wc_get_order( $order_id );

            // try Sequential Order Pro
            if ( ! $_order && function_exists('wc_seq_order_number_pro') ) {
                $seq_order_id = wc_seq_order_number_pro()->find_order_by_order_number( $order_id );
                if ( !empty( $seq_order_id ) ) {
                    $order_id = $seq_order_id;
                    $_order = wc_get_order( $order_id );
                }
            }

            if ( ! $_order ) {
                WPLA()->logger->debug( 'could not find order. skipping');
                continue;
            }

            // echo "<pre>";print_r($_order);echo"</pre>";#die();
            // echo "<pre>";print_r($row);echo"</pre>";die();

            $shipment_date          = $row['shipment-date'];
            $estimated_arrival_date = $row['estimated-arrival-date'];
            $ship_service_level     = $row['ship-service-level'];
            $tracking_number        = $row['tracking-number'];
            $carrier                = $row['carrier'];

            // update order meta fields

            $_order->update_meta_data( '_wpla_fba_submission_status',      'shipped' );
            $_order->update_meta_data( '_wpla_fba_shipment_date',          $shipment_date );
            $_order->update_meta_data( '_wpla_fba_estimated_arrival_date', $estimated_arrival_date );
            $_order->update_meta_data( '_wpla_fba_ship_service_level',     $ship_service_level );
            $_order->update_meta_data( '_wpla_fba_tracking_number',        $tracking_number );
            $_order->update_meta_data( '_wpla_fba_ship_carrier',           $carrier );

            // update meta fields for WooCommerce Shipment Tracking plugin
            $_order->update_meta_data( '_date_shipped',                    strtotime( $shipment_date ) ); // shipment-date column contains TZ offset
            $_order->update_meta_data( '_tracking_number',                 $tracking_number );
            $_order->update_meta_data( '_custom_tracking_provider',        $carrier );
            $_order->update_meta_data( '_tracking_provider',               '' ); // known providers - would require mapping ('usps' <=> 'USPS')

            $wc_orders_processed++;

            WPLA()->logger->debug( 'added tracking data to order #'. $order_id );

            // skip further processing for non-MCF orders - no need to to update orders placed on Amazon
            if ( ! $is_mcf_order ) {
                WPLA()->logger->debug( 'non-MCF order. Exiting.');
                continue;
            }

            // notify WPLE - mark order as shipped on eBay
            $args = array();
            $args['TrackingNumber']  = $tracking_number;
            $args['TrackingCarrier'] = $carrier;
            $args['ShippedTime']     = $shipment_date;
            // $args['FeedbackText']    = 'Thank You...';

            do_action( 'wple_complete_sale_on_ebay', $order_id, $args );

            // if order is already completed, no need to update the status #50697
            if ( $_order->get_status() != 'completed' ) {
                // complete order - after WPLE has submitted tracking details to eBay!
                $new_order_status = get_option( 'wpla_shipped_order_status', 'completed' );

                $_order->update_status( $new_order_status );
            }

            $_order->save();


        }

        // build response
        $response = new stdClass();
        $response->job      = $job;
        $response->task     = $task;
        $response->errors   = '';
        $response->success  = true;
        $response->count    = $wc_orders_processed;

        return $response;
    } // processAmazonShipmentsReportPage()


    // convert raw CSV data to PHP array
    public static function csv_to_array( $input, $query = false, $delimiter = "\t" ) {

        $header  = null;
        $data    = array();
        $csvData = str_getcsv( $input, "\n", '' );
        // $line = 0;

        // echo "<pre>";print_r($csvData);echo"</pre>";die();

        foreach( $csvData as $csvLine ) {

            if ( $csvLine == null ) continue; // skip empty lines

            if ( is_null($header) ) {
                $header = explode($delimiter, $csvLine);    
            } else {

                // handle query string
                if ( $query && false === stripos( $csvLine, $query ) ) continue;


                // split row into cells
                $items = explode($delimiter, $csvLine);

                // $line++;
                // echo "line $line <br>";

                for ( $n = 0, $m = count($header); $n < $m; $n++ ){
                    $prepareData[$header[$n]] = isset( $items[$n] ) ? $items[$n] : '';
                }

                $data[] = $prepareData;
            }
        }

        return $data;
    } // csv_to_array()
	

} // class WPLA_ReportProcessor
