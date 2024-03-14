<?php
/**
 * WPLA_CronActions
 *
 * This class contains action hooks that are usually trigger via wp_cron()
 *
 */

class WPLA_CronActions {

	var $dblogger;
	var $lockfile;

	public function __construct() {

		// add main cron handler
		add_action('wpla_update_schedule', 		array( &$this, 'cron_update_schedule' ) );
		add_action('wpla_daily_schedule', 		array( &$this, 'cron_daily_schedule' ) );
		add_action('wpla_fba_report_schedule', 	array( &$this, 'cron_fba_report_schedule' ) );

 		// handle external cron calls
		add_action('wp_ajax_wplister_run_scheduled_tasks', 			array( &$this, 'cron_update_schedule' ), 20 ); // wplister_run_scheduled_tasks
		add_action('wp_ajax_nopriv_wplister_run_scheduled_tasks', 	array( &$this, 'cron_update_schedule' ), 20 );
		add_action('wp_ajax_wpla_run_scheduled_tasks', 				array( &$this, 'cron_update_schedule' ), 20 ); // wpla_run_scheduled_tasks
		add_action('wp_ajax_nopriv_wpla_run_scheduled_tasks', 		array( &$this, 'cron_update_schedule' ), 20 );
		add_action('wp_ajax_wpla_request_inventory_report', 		array( &$this, 'request_daily_inventory_report' ) ); // wpla_request_inventory_report
		add_action('wp_ajax_nopriv_wpla_request_inventory_report', 	array( &$this, 'request_daily_inventory_report' ) );

		// add internal action hooks
		add_action('wpla_update_orders', 						array( &$this, 'action_update_orders' ) );
		add_action('wpla_update_reports', 						array( &$this, 'action_update_reports' ) );
		add_action('wpla_update_feeds', 						array( &$this, 'action_update_feeds' ) );
		add_action('wpla_submit_pending_feeds', 				array( &$this, 'action_submit_pending_feeds' ) );
		add_action('wpla_update_pricing_info',  				array( &$this, 'action_update_pricing_info' ) );
		add_action('wpla_update_missing_asins', 				array( &$this, 'action_update_missing_asins' ) );
		add_action('wpla_clean_log_table', 						array( &$this, 'action_clean_log_table' ) );
		add_action('wpla_clean_tables', 						array( &$this, 'action_clean_tables' ) );
		add_action('wpla_reprice_products', 					array( &$this, 'action_reprice_products' ) );
		add_action('wpla_autosubmit_fba_orders', 				array( &$this, 'action_autosubmit_fba_orders' ) );
		add_action('wpla_request_daily_quality_report', 		array( &$this, 'request_daily_quality_report' ) );
		add_action('wpla_request_daily_fba_report', 			array( &$this, 'request_daily_fba_report' ) );
		add_action('wpla_request_daily_inventory_report', 		array( &$this, 'request_daily_inventory_report' ) );
		add_action('wpla_request_daily_order_report', 		    array( &$this, 'request_daily_order_report' ) );
		add_action('wpla_request_daily_fba_shipments_report', 	array( &$this, 'request_daily_fba_shipments_report' ) );


		// add custom cron schedules
		add_filter( 'cron_schedules', array( &$this, 'cron_add_custom_schedules' ) );


	}

	// run update schedule - called by wp_cron if activated
	public function cron_update_schedule() {
        WPLA()->logger->info("*** WP-CRON: cron_update_schedule()");

        // log cron run to db
		if ( get_option('wpla_log_to_db') == '1' ) {
			$dblogger = new WPLA_AmazonLogger();
			$dblogger->updateLog( array(
				'callname'    => 'cron_job_triggered',
				'request_url' => 'internal action hook',
				'request'     => maybe_serialize( $_REQUEST ),
				'response'    => 'last run: '.human_time_diff( get_option('wpla_cron_last_run') ).' ago',
				'success'     => 'Success'
	        ));
		}

        // check if this is a staging site
        if ( WPLA_Setup::isStagingSite() ) {
	        WPLA()->logger->info("WP-CRON: staging site detected! terminating execution...");

			update_option( 'wpla_cron_schedule', '' );
			update_option( 'wpla_create_orders', '' );

	        // remove scheduled event
		    $timestamp = wp_next_scheduled(  'wpla_update_schedule' );
	    	wp_unschedule_event( $timestamp, 'wpla_update_schedule' );

	        // remove scheduled event
		    $timestamp = wp_next_scheduled(  'wpla_fba_report_schedule' );
	    	wp_unschedule_event( $timestamp, 'wpla_fba_report_schedule' );

        	return;
        }

        // check if update is already running
        if ( ! $this->checkLock() ) {
	        WPLA()->logger->error("WP-CRON: already running! terminating execution...");
        	return;
        }

		// update reports - checks for submitted reports automatically now
		do_action('wpla_update_reports');

		// update feeds - checks for submitted feeds itself now
		do_action('wpla_update_feeds');

		// If Dedicated Orders Cron is enabled, wpla_update_orders will be called as an async task using ActionScheduler.
		if ( get_option( 'wpla_dedicated_orders_cron', 0 ) ) {
            // Create an async background task that checks for new orders
            wpla_enqueue_async_action( 'wpla_update_orders' );
        } else {
            // update orders
            do_action('wpla_update_orders');
        }

		// submit pending feeds - after processing orders!
		do_action('wpla_submit_pending_feeds');

		// run full cron job not more often than every 5 minutes (270s = 4.5min / 30s buffer)
		$ts_last_full_run = get_option( 'wpla_cron_last_full_run', 0 );
		if ( $ts_last_full_run < time() - 270 ) {

	        // update pricing info
			do_action('wpla_update_pricing_info');

	        // update missing ASINs
			do_action('wpla_update_missing_asins');


			// store timestamp
			update_option( 'wpla_cron_last_full_run', time() );

		}

		// If update interval is set to external cron, FBA Reports are only pulled every time the daily update schedule is run
        // Make sure we follow the Request FBA Reports schedule
        $fba_report_schedule = get_option( 'wpla_fba_report_schedule', 'daily' );
        $last_run            = get_option('wpla_fba_report_cron_last_run');
        $run_hours          = array( 'twelve_hours' => 12, 'six_hours' => 6, 'three_hours' => 3, 'daily' => 24 );
        $hours              = 24;
        if ( array_key_exists( $fba_report_schedule, $run_hours ) ) {
            $hours = $run_hours[ $fba_report_schedule ];
        }

        if ( $last_run < time() - ($hours * 3600) ) {
            do_action( 'wpla_fba_report_schedule' );
        }


		// check daily schedule - trigger now if not executed within 36 hours
        $last_run = get_option('wpla_daily_cron_last_run');
        if ( $last_run < time() - 36 * 3600 ) {
	        WPLA()->logger->warn('*** WP-CRON: Daily schedule has NOT run since '.human_time_diff( $last_run ).' ago');
			do_action( 'wpla_daily_schedule' );
			do_action( 'wpla_fba_report_schedule' ); // if the daily schedule didn't run, we can assume the FBA schedule didn't run either
        }


		// clean up
		$this->removeLock();

		// store timestamp
		update_option( 'wpla_cron_last_run', time() );

        WPLA()->logger->info("*** WP-CRON: cron_update_schedule() finished");
	}

	// run daily schedule - called by wp_cron
	public function cron_daily_schedule() {
        WPLA()->logger->info("*** WP-CRON: cron_daily_schedule()");

    	// check timestamp - do not run daily schedule more often than every 24 hours (except when triggered manually)
        $last_run = get_option('wpla_daily_cron_last_run');
        $manually = isset($_REQUEST['action']) && $_REQUEST['action'] == 'wpla_run_daily_schedule' ? true : false;
        if ( $last_run > time() - 24 * 3600 && ! $manually ) {
	        WPLA()->logger->warn('*** WP-CRON: cron_daily_schedule() EXIT - last run: '.human_time_diff( $last_run ).' ago');
	        return;
        }

		// request daily inventory report
		if ( get_option( 'wpla_autofetch_inventory_report' ) && ! WPLA_Setup::isStagingSite() )
			do_action('wpla_request_daily_inventory_report');

        // request daily order report
        if ( get_option( 'wpla_autofetch_order_report' ) && ! WPLA_Setup::isStagingSite() )
            do_action('wpla_request_daily_order_report');

		// request daily listing quality report
		if ( get_option( 'wpla_autofetch_listing_quality_feeds', 1 ) && ! WPLA_Setup::isStagingSite() )
			do_action('wpla_request_daily_quality_report');

		// clean tables
		do_action('wpla_clean_log_table');
		do_action('wpla_clean_tables');

		// store timestamp
		update_option( 'wpla_daily_cron_last_run', time() );

        WPLA()->logger->info("*** WP-CRON: cron_daily_schedule() finished");
        if ( $manually ) wpla_show_message('Daily maintenance schedule was executed successfully.');
	}

	// run FBA report schedule - called by wp_cron
	public function cron_fba_report_schedule() {
        WPLA()->logger->info("*** WP-CRON: cron_fba_report_schedule()");

    	// check timestamp - do not run FBA schedule more often than every 3 hours
        $last_run = get_option('wpla_fba_report_cron_last_run');
        if ( $last_run > time() -  3 * 3600 ) {
	        WPLA()->logger->warn('*** WP-CRON: cron_fba_report_schedule() EXIT - last run: '.human_time_diff( $last_run ).' ago');
	        return;
        }

		// request FBA shipments report
		if ( get_option( 'wpla_fba_enabled' ) && ! WPLA_Setup::isStagingSite() )
			do_action('wpla_request_daily_fba_shipments_report');

		// request daily FBA inventory report
		if ( get_option( 'wpla_fba_enabled' ) && ! WPLA_Setup::isStagingSite() )
			do_action('wpla_request_daily_fba_report');

		// store timestamp
		update_option( 'wpla_fba_report_cron_last_run', time() );

        WPLA()->logger->info("*** WP-CRON: cron_fba_report_schedule() finished");
	}


	// fetch missing ASINs - called by do_action()
	public function action_update_missing_asins() {
        WPLA()->logger->info("do_action: wpla_update_missing_asins");

		$accounts      = WPLA_AmazonAccount::getAll();
		$listingsModel = new WPLA_ListingsModel();
		$batch_size    = 10; // update 10 items at a time

		foreach ($accounts as $account ) {
            WPLA()->logger->info("Fetching listings for account #".$account->id);

			$account_id    = $account->id;
			$listings      = $listingsModel->getAllOnlineWithoutASIN( $account_id, 10, OBJECT_K );

			if ( empty($listings) ) {
                WPLA()->logger->info("No listings found for account");
			    continue;
            }

			// process one listing at a time (for now)
			foreach ( $listings as $listing ) {
				WPLA()->logger->info('fetching ASIN for SKU '.$listing->sku.' ('.$listing->id.') - type: '.$listing->product_type );

				$api        = new WPLA_Amazon_SP_API( $account->id );
				$result     = $api->searchCatalogItemsByIdentifier( [$listing->sku ], 'SKU' );

				if ( ! WPLA_Amazon_SP_API::isError( $result ) )  {
                    foreach( $result->getItems() as $product ) {
                        // update listing ASIN
                        $listingsModel->updateWhere( array( 'id' => $listing->id ), array( 'asin' => $product->getAsin() ) );

                        WPLA()->logger->info('new ASIN for listing #'.$listing->id . ': '.$product->getAsin() );
                    }
				} elseif ( $result->Error && $result->Error->Message ) {
					$errors  = sprintf( __( 'There was a problem fetching product details for %s.', 'wp-lister-for-amazon' ), $listing->sku ) .'<br>Error: '. $result->Error->Message;
					WPLA()->logger->error( $errors );
				} else {
					$errors  = sprintf( __( 'There was a problem fetching product details for %s.', 'wp-lister-for-amazon' ), $listing->sku );
					WPLA()->logger->error( $errors );
				}

			} // foreach listing

		} // each account

	} // action_update_missing_asins ()


	// fetch lowest prices - called by do_action()
	public function action_update_pricing_info() {
        WPLA()->logger->info("do_action: wpla_update_pricing_info");
        // WPLA()->logger->debug( print_r( debug_backtrace(), 1 ) );

		$accounts = WPLA_AmazonAccount::getAll();
		// $listingsModel = new WPLA_ListingsModel();
		$batch_size = 200; // 10 requests per batch (for now - maximum should be 20 requests = 400 items / max. 600 items per minute)

		foreach ($accounts as $account ) {

			$account_id    = $account->id;
			$listings      = WPLA_ListingQueryHelper::getItemsDueForPricingUpdateForAcccount( $account_id, $batch_size );
			$listing_ASINs = array();
			WPLA()->logger->info( sprintf( '%s items with outdated pricing info found for account %s.', sizeof($listings), $account->title ) );

			// build array of ASINs
        	foreach ($listings as $listing) {
        		// skip duplicate ASINs - they throw an error from Amazon
        		if ( in_array( $listing->asin, $listing_ASINs ) ) continue;

       			$listing_ASINs[] = $listing->asin;
        	}
        	if ( empty($listing_ASINs) ) continue;


        	// process batches of 20 ASINs
        	for ($page=0; $page < 10; $page++) {
        		$page_size = 19;

        		// splice ASINs
        		$offset = $page * $page_size;
        		$ASINs_for_this_batch = array_slice( $listing_ASINs, $offset, $page_size );
        		if ( empty($ASINs_for_this_batch) ) continue;

        		// run update
	        	$this->update_pricing_info_for_asins( $ASINs_for_this_batch, $account_id );

				WPLA()->logger->info( sprintf( '%s ASINs had their pricing info updated - account %s.', sizeof($listing_ASINs), $account->title ) );
				WPLA()->logger->info( 'Sleeping for 2s to prevent getting error 429 (rate limited)');
				sleep(2);
        	}


		} // each account

	} // action_update_pricing_info ()


	// fetch and process lowest price info for up to 20 ASINs
	public function update_pricing_info_for_asins( $listing_ASINs, $account_id ) {
		$listingsModel = new WPLA_ListingsModel();

    	// fetch Buy Box pricing info and process result
		$api = new WPLA_Amazon_SP_API( $account_id );
		$result = $api->getCompetitivePricing( $listing_ASINs );
        $listingsModel->processBuyBoxPricingResult( $result, $account_id );

		// return if lowest offers are disabled
		// if ( ! get_option('wpla_repricing_use_lowest_offer') ) return;

    	// fetch Lowest Offer info and process result
		$api = new WPLA_Amazon_SP_API( $account_id );
        $result = $api->getItemOffers( $listing_ASINs );
        $listingsModel->processLowestOfferPricingResult( $result, $account_id );

	} // update_pricing_info_for_asins ()


	// apply lowest prices - called by do_action()
	public function action_reprice_products() {
	} // action_reprice_products ()


	// auto submit FBA orders - called by do_action()
	public function action_autosubmit_fba_orders() {
	} // action_autosubmit_fba_orders ()


	// fetch new orders - called by do_action()
	public function action_update_orders() {
        WPLA()->logger->info("do_action: wpla_update_orders");
        WPLA()->logger->startTimer( 'action_update_orders' );

		$accounts = WPLA_AmazonAccount::getAll();

		foreach ($accounts as $account ) {

			$api = new WPLA_Amazon_SP_API( $account->id );

			// get date of last order
			$om = new WPLA_OrdersModel();
			$lastdate = $om->getDateOfLastOrder( $account->id );
			WPLA()->logger->info('getDateOfLastOrder() returned: '.$lastdate);

			$days = isset($_REQUEST['days']) && ! empty($_REQUEST['days']) ? wpla_clean($_REQUEST['days']) : false;
			if ( ! $lastdate && ! $days ) $days = 1;

			// get orders
			$orders = $api->getOrders( $lastdate, $days );
			// echo "<pre>";print_r($orders);echo"</pre>";#die();

			if ( is_array( $orders ) ) {

				// run the import
				$importer = new WPLA_OrdersImporter();
				$importer->importOrders( $orders, $account );

				$msg  = sprintf( __( '%s order(s) were processed for account %s.', 'wp-lister-for-amazon' ), sizeof($orders), $account->title );
				if ( $importer->updated_count  > 0 ) $msg .= "\n".'Updated orders: '.$importer->updated_count ;
				if ( $importer->imported_count > 0 ) $msg .= "\n".'Created orders: '.$importer->imported_count;
				WPLA()->logger->info( $msg );
				$this->showMessage( nl2br($msg),0,1 );

			} elseif ( isset($orders->ErrorMessage) ) {
				$msg = sprintf( __( 'There was a problem downloading orders for account %s.', 'wp-lister-for-amazon' ), $account->title ) .' - Error: '. $orders->ErrorMessage;
				WPLA()->logger->error( $msg );
				$this->showMessage( nl2br($msg),1,1 );
			} else {
				$msg = sprintf( __( 'There was a problem downloading orders for account %s.', 'wp-lister-for-amazon' ), $account->title );
				WPLA()->logger->error( $msg );
				$this->showMessage( nl2br($msg),1,1 );
			}

		}
		$this->message = '';

		WPLA()->logger->endTimer( 'action_update_orders' );
		WPLA()->logger->logSpentTime( 'action_update_orders' );

        // store timestamp
        update_option( 'wpla_orders_cron_last_run', time() );

	} // action_update_orders()


	// update submitted reports - called by do_action()
	public function action_update_reports( $inventory_sync = false ) {
        WPLA()->logger->info("do_action: wpla_update_reports");

        WPLA_AmazonReport::resetReportsInProgress( $inventory_sync );

		$accounts = WPLA_AmazonAccount::getAll();

		foreach ($accounts as $account ) {

			// get all submitted reports for this account
			$submitted_reports = WPLA_AmazonReport::getSubmittedReportsForAccount( $account->id );
			$ReportRequestIds = array();
			foreach ($submitted_reports as $report) {
				$ReportRequestIds[] = $report->ReportRequestId;
			}

			// do nothing if no submitted reports are found (disable to fetch all recent reports)
			if ( empty( $ReportRequestIds ) ) {
				$msg  = sprintf( __( 'No pending report request(s) for account %s.', 'wp-lister-for-amazon' ), $account->title );
				WPLA()->logger->info( $msg );

				if ( !$inventory_sync ) {
                    $this->showMessage( nl2br($msg),0,1 );
                }

				continue;
			}

            $api = new WPLA_Amazon_SP_API( $account->id );
            $reports = [];
			foreach ( $ReportRequestIds as $report_id ) {
			    $report = $api->getReport( $report_id );

                if ( isset( $report->ErrorMessage ) ) {
                    $msg = sprintf( __( 'There was a problem fetching report #%s for account %s.', 'wp-lister-for-amazon' ), $report_id, $account->title ) .' - Error: '. $report->ErrorMessage;
                    WPLA()->logger->error( $msg );

                    if ( !$inventory_sync ) {
                        $this->showMessage( nl2br($msg),1,1 );
                    }
                    continue;
                }

                WPLA_AmazonReport::processReport( $report, $account, true, $inventory_sync );
                $reports[] = $report;
            }

            $msg  = sprintf( __( '%s report request(s) were found for account %s.', 'wp-lister-for-amazon' ), sizeof($reports), $account->title );
            WPLA()->logger->info( $msg );

            if ( !$inventory_sync ) {
                $this->showMessage( nl2br($msg),0,1 );
            }

		}

	} // action_update_reports()


	// update submitted feeds - called by do_action()
	public function action_update_feeds() {
        WPLA()->logger->info("do_action: wpla_update_feeds");

		$accounts = WPLA_AmazonAccount::getAll();
		$feeds_in_progress = 0;

		foreach ( $accounts as $account ) {
            $api = new WPLA_Amazon_SP_API( $account->id );

			// get all submitted feeds for this account
			$submitted_feeds = WPLA_AmazonFeed::getSubmittedFeedsForAccount( $account->id );

			// Test Data
            /*$test_feed = new stdClass();
            $test_feed->FeedSubmissionId = 'feedId1';
            $submitted_feeds = [$test_feed];*/


			$feeds = array();

			foreach ($submitted_feeds as $feed_row) {
			    $feed = $api->getFeed( $feed_row->FeedSubmissionId );

			    if ( WPLA_Amazon_SP_API::isError( $feed ) ) {
			        $msg = sprintf( __( 'Unable to retrieve feed from Amazon: %s. Reason: %s', 'wp-lister-for-amazon' ), $feed_row->FeedSubmissionId, $feed->ErrorMessage );
			        WPLA()->logger->error( $msg );
			        $this->showMessage( $msg, true, true );
			        continue;
                }

			    // TEST DATA
                //$feed->setResultFeedDocumentId( '0356cf79-b8b0-4226-b4b9-0ee058ea5760' );
			    //$feed->setProcessingStatus( 'DONE' );

                $feeds[] = $feed;

			}

			// do nothing if no submitted feeds are found (disable to fetch all recent feeds)
			if ( empty( $feeds ) ) {
				$msg  = sprintf( __( 'No submitted feeds found for account %s.', 'wp-lister-for-amazon' ), $account->title );
				WPLA()->logger->info( $msg );
				$this->showMessage( nl2br($msg),0,1 );
				continue;
			}

			if ( is_array( $feeds ) )  {
				// process feed submission list
				$feeds_in_progress += WPLA_AmazonFeed::processFeedsSubmissionList( $feeds, $account );

				$msg  = sprintf( __( '%s feed submission(s) were found for account %s.', 'wp-lister-for-amazon' ), sizeof($feeds), $account->title );
				WPLA()->logger->info( $msg );
				$this->showMessage( nl2br($msg),0,1 );

			} elseif ( !empty( $feeds->Error->Message ) ) {
				$msg = sprintf( __( 'There was a problem fetching feed submissions for account %s.', 'wp-lister-for-amazon' ), $account->title ) .' - Error: '. $feeds->ErrorMessage;
				WPLA()->logger->error( $msg );
				$this->showMessage( nl2br($msg),1,1 );
			} else {
				$msg = sprintf( __( 'There was a problem fetching feed submissions for account %s.', 'wp-lister-for-amazon' ), $account->title );
				WPLA()->logger->error( $msg );
				$this->showMessage( nl2br($msg),1,1 );
			}

		}

		// update feed progress status
		update_option( 'wpla_feeds_in_progress', $feeds_in_progress );

	} // action_update_feeds()


	// submit pending feeds - called by do_action()
	public function action_submit_pending_feeds() {
        WPLA()->logger->info("do_action: wpla_submit_pending_feeds");

		$accounts = WPLA_AmazonAccount::getAll();

		foreach ($accounts as $account ) {

			// refresh feeds
			WPLA_AmazonFeed::updatePendingFeedForAccount( $account );

			// get pending feeds for account
			$feeds = WPLA_AmazonFeed::getAllPendingFeedsForAccount( $account->id );
	        WPLA()->logger->info("found ".sizeof($feeds)." pending feeds for account {$account->id}");

			foreach ($feeds as $feed) {

				$autosubmit_feeds = array(
					'_POST_FLAT_FILE_PRICEANDQUANTITYONLY_UPDATE_DATA_',	// Price and Quantity Update Feed
					'_POST_FLAT_FILE_LISTINGS_DATA_',						// Flat File Listings Feed
					'_POST_FLAT_FILE_FULFILLMENT_DATA_',					// Order Fulfillment Feed
					'_POST_FLAT_FILE_FULFILLMENT_ORDER_REQUEST_DATA_',		// Flat File FBA Shipment Injection Fulfillment Feed
					'_POST_FLAT_FILE_INVLOADER_DATA_',						// Inventory Loader Feed (Product Removal)
                    '_UPLOAD_VAT_INVOICE_',                                  // VAT Invoice uploading
                    // SP-API Feed types
                    'POST_FLAT_FILE_PRICEANDQUANTITYONLY_UPDATE_DATA',	// Price and Quantity Update Feed
					'POST_FLAT_FILE_LISTINGS_DATA',						// Flat File Listings Feed
					'POST_FLAT_FILE_FULFILLMENT_DATA',					// Order Fulfillment Feed
					'POST_FLAT_FILE_FULFILLMENT_ORDER_REQUEST_DATA',		// Flat File FBA Shipment Injection Fulfillment Feed
					'POST_FLAT_FILE_INVLOADER_DATA',						// Inventory Loader Feed (Product Removal)
                    'UPLOAD_VAT_INVOICE',                                  // VAT Invoice uploading
				);

				if ( ! in_array( $feed->FeedType, $autosubmit_feeds ) ) {
			        WPLA()->logger->info("skipped pending feed {$feed->id} ({$feed->FeedType}) for account {$account->id} - autosubmit disabled for feed type");
			        continue;
				}

				// submit feed
				$feed->submit();

		        WPLA()->logger->info("submitted pending feed {$feed->id} ({$feed->FeedType}) for account {$account->id}");
			}

		}

	} // action_submit_pending_feeds()




	// request listing quality reports for all active accounts
	public function request_daily_quality_report() {

		$report_type = 'GET_MERCHANT_LISTINGS_DEFECT_DATA';
		$accounts    = WPLA_AmazonAccount::getAll();

		foreach ($accounts as $account ) {

			$api = new WPLA_Amazon_SP_API( $account->id );

			// request report - returns request list as array on success
			$reports = $api->getReports( [$report_type] );

			if ( WPLA_Amazon_SP_API::isError( $reports ) ) {
			    WPLA()->logger->error( 'getReports error: '. $reports->ErrorMessage );
            } elseif ( is_array( $reports ) )  {
                foreach ( $reports as $report ) {
                    // process the result
                    WPLA_AmazonReport::processReport( $report, $account, true );
                }
            }

		} // foreach account

	} // request_daily_quality_report()


	// request FBA inventory reports for all active accounts
	public function request_daily_fba_report() {

		//$report_type = '_GET_AFN_INVENTORY_DATA_';
        $report_type = 'GET_FBA_MYI_UNSUPPRESSED_INVENTORY_DATA'; // Use Manage FBA Inventory Report #32733
		$accounts    = WPLA_AmazonAccount::getAll();

		foreach ($accounts as $account ) {

			$api = new WPLA_Amazon_SP_API( $account->id );

            $report_id = $api->createReport( $report_type );

            if ( is_object($report_id) && isset( $report_id->ErrorMessage ) ) {
                WPLA()->logger->error( sprintf( __( 'There was a problem requesting the report for account %s.', 'wp-lister-for-amazon' ), $account->title ) .'<br>Error: '. $report_id->ErrorMessage );
            } else {
                $report = $api->getReport( $report_id );
                WPLA_AmazonReport::processReport( $report, $account, true );
                WPLA()->logger->info( sprintf( __( 'Report requested for account %s.', 'wp-lister-for-amazon' ), $account->title ) );
            }

		} // foreach account

	} // request_daily_fba_report()


	// request FBA fulfilled shipment reports for all active accounts
	public function request_daily_fba_shipments_report() {

		$report_type = 'GET_AMAZON_FULFILLED_SHIPMENTS_DATA_GENERAL';
		$accounts    = WPLA_AmazonAccount::getAll();

		foreach ($accounts as $account ) {

			$api = new WPLA_Amazon_SP_API( $account->id );

            $report_id = $api->createReport( $report_type );

            if ( is_object($report_id) && isset( $report_id->ErrorMessage ) ) {
                WPLA()->logger->error( sprintf( __( 'There was a problem requesting the report for account %s.', 'wp-lister-for-amazon' ), $account->title ) .'<br>Error: '. $report_id->ErrorMessage );
            } else {
                $report = $api->getReport( $report_id );
                WPLA_AmazonReport::processReport( $report, $account, true );
                WPLA()->logger->info( sprintf( __( 'Report requested for account %s.', 'wp-lister-for-amazon' ), $account->title ) );
            }

		} // foreach account

	} // request_daily_fba_shipments_report()


	// request merchant inventory reports for all active accounts
	public function request_daily_inventory_report() {

		$report_type = 'GET_MERCHANT_LISTINGS_DATA';
		$accounts    = WPLA_AmazonAccount::getAll();

		foreach ($accounts as $account ) {

			$api = new WPLA_Amazon_SP_API( $account->id );

            $report_id = $api->createReport( $report_type );

            if ( is_object($report_id) && isset( $report_id->ErrorMessage ) ) {
                WPLA()->logger->error( sprintf( __( 'There was a problem requesting the report for account %s.', 'wp-lister-for-amazon' ), $account->title ) .'<br>Error: '. $report_id->ErrorMessage );
            } else {
                $report = $api->getReport( $report_id );
                WPLA_AmazonReport::processReport( $report, $account, true );
                WPLA()->logger->info( sprintf( __( 'Report requested for account %s.', 'wp-lister-for-amazon' ), $account->title ) );
            }

		} // foreach account

	} // request_daily_inventory_report()

    // request order reports for all active accounts
    public function request_daily_order_report() {

        $report_type = 'GET_FLAT_FILE_ORDER_REPORT_DATA_INVOICING';
        $accounts    = WPLA_AmazonAccount::getAll();

        foreach ($accounts as $account ) {

            $api = new WPLA_Amazon_SP_API( $account->id );

            $report_id = $api->createReport( $report_type );

            if ( is_object($report_id) && isset( $report_id->ErrorMessage ) ) {
                WPLA()->logger->error( sprintf( __( 'There was a problem requesting the report for account %s.', 'wp-lister-for-amazon' ), $account->title ) .'<br>Error: '. $report_id->ErrorMessage );
            } else {
                $report = $api->getReport( $report_id );
                WPLA_AmazonReport::processOrderReportData( $report, $account, true );
                WPLA()->logger->info( sprintf( __( 'Report requested for account %s.', 'wp-lister-for-amazon' ), $account->title ) );
            }

        } // foreach account

    } // request_daily_inventory_report()




	public function action_clean_log_table() {
		global $wpdb;

		// clean log table
		$days_to_keep = get_option( 'wpla_log_days_limit', 30 );
		$rows = $wpdb->query('DELETE FROM '.$wpdb->prefix.'amazon_log WHERE timestamp < DATE_SUB(NOW(), INTERVAL '.intval($days_to_keep).' DAY )');
		WPLA()->logger->info('Cleaned table amazon_log - affected rows: ' . $rows);

		WPLA()->logger->deleteOldLogs( $days_to_keep );

		// clean stock log table
		$days_to_keep = get_option( 'wpla_stock_days_limit', 180 );
		$rows = $wpdb->query('DELETE FROM '.$wpdb->prefix.'amazon_stock_log WHERE timestamp < DATE_SUB(NOW(), INTERVAL '.$days_to_keep.' DAY )');
		WPLA()->logger->info('Cleaned table amazon_stock_log - affected rows: ' . $rows);

		// Optimize the tables
        $wpdb->query('OPTIMIZE TABLE '. $wpdb->prefix.'amazon_log');
        $wpdb->query('OPTIMIZE TABLE '. $wpdb->prefix.'amazon_stock_log');

	} // action_clean_log_table()

	public function action_clean_tables() {
		global $wpdb;

		// clean feeds table (date_created)
		$days_to_keep = get_option( 'wpla_feeds_days_limit', 90 );
		if ( $days_to_keep ) {
			$rows = $wpdb->query('DELETE FROM '.$wpdb->prefix.'amazon_feeds WHERE date_created < DATE_SUB(NOW(), INTERVAL '.$days_to_keep.' DAY )');
			WPLA()->logger->info('Cleaned table amazon_feeds - affected rows: ' . $rows);
		}

		// clean reports table (SubmittedDate)
		$days_to_keep = get_option( 'wpla_reports_days_limit', 90 );
		if ( $days_to_keep ) {
			$rows = $wpdb->query('DELETE FROM '.$wpdb->prefix.'amazon_reports WHERE SubmittedDate < DATE_SUB(NOW(), INTERVAL '.$days_to_keep.' DAY )');
			WPLA()->logger->info('Cleaned table amazon_reports - affected rows: ' . $rows);
		}

        // clean jobs table (date_created)
        $days_to_keep = get_option( 'wpla_reports_days_limit', '' );
        if ( $days_to_keep ) {
            $rows = $wpdb->query('DELETE FROM '.$wpdb->prefix.'amazon_jobs WHERE date_created < DATE_SUB(NOW(), INTERVAL '.$days_to_keep.' DAY )');
            WPLA()->logger->info('Cleaned table amazon_jobs - affected rows: ' . $rows);
        }

		// clean orders table (date_created)
		$days_to_keep = get_option( 'wpla_orders_days_limit', '' );
		if ( $days_to_keep ) {
			$rows = $wpdb->query('DELETE FROM '.$wpdb->prefix.'amazon_orders WHERE date_created < DATE_SUB(NOW(), INTERVAL '.$days_to_keep.' DAY )');
			WPLA()->logger->info('Cleaned table amazon_orders - affected rows: ' . $rows);
		}

	} // action_clean_tables()


	public function checkLock() {

		// get full path to lockfile
		$uploads        = wp_upload_dir();
		$lockfile       = $uploads['basedir'] . '/' . 'wpla_sync.lock';
		$this->lockfile = $lockfile;

		// skip locking if lockfile is not writeable
		if ( ! is_writable( $lockfile ) && ! is_writable( dirname( $lockfile ) ) ) {
	        WPLA()->logger->error("lockfile not writable: ".$lockfile);
	        return true;
		}

		// create lockfile if it doesn't exist
        // using is_readable checks that the lockfile exists AND is readable  #36512
		if ( ! is_readable( $lockfile ) ) {
			$ts = time();
			file_put_contents( $lockfile, $ts );
	        WPLA()->logger->info("lockfile created at TS $ts: ".$lockfile);
	        return true;
		}

		// lockfile exists - check TS
		$ts = (int) file_get_contents($lockfile);

		// check if TS is outdated (after 10min.)
		if ( $ts < ( time() - 600 ) ) {
	        WPLA()->logger->info("stale lockfile found for TS ".$ts.' - '.human_time_diff( $ts ).' ago' );

	        // update lockfile
			$ts = time();
			file_put_contents( $lockfile, $ts );

	        WPLA()->logger->info("lockfile updated for TS $ts: ".$lockfile);
	        return true;
		} else {
			// process is still alive - can not run twice
	        WPLA()->logger->info("SKIP CRON - sync already running with TS ".$ts.' - '.human_time_diff( $ts ).' ago' );
			return false;
		}

		return true;
	} // checkLock()

	public function removeLock() {
		if ( file_exists( $this->lockfile ) ) {
			unlink( $this->lockfile );
	        WPLA()->logger->info("lockfile was removed: ".$this->lockfile);
		}
	}

	public function cron_add_custom_schedules( $schedules ) {
		$schedules['five_min'] = array(
			'interval' => 60 * 5,
			'display' => 'Once every five minutes'
		);
		$schedules['ten_min'] = array(
			'interval' => 60 * 10,
			'display' => 'Once every ten minutes'
		);
		$schedules['fifteen_min'] = array(
			'interval' => 60 * 15,
			'display' => 'Once every fifteen minutes'
		);
		$schedules['thirty_min'] = array(
			'interval' => 60 * 30,
			'display' => 'Once every thirty minutes'
		);
		$schedules['three_hours'] = array(
			'interval' => 60 * 60 * 3,
			'display' => 'Once every three hours'
		);
		$schedules['six_hours'] = array(
			'interval' => 60 * 60 * 6,
			'display' => 'Once every six hours'
		);
		$schedules['twelve_hours'] = array(
			'interval' => 60 * 60 * 12,
			'display' => 'Once every twelve hours'
		);
		return $schedules;
	}

	public function showMessage($message, $errormsg = false, $echo = false) {

		// don't output message when doing cron
		if ( defined('DOING_CRON') && DOING_CRON ) return;

		if ( defined('WPLISTER_RESELLER_VERSION') ) $message = apply_filters( 'wpla_tooltip_text', $message );

		$class = ($errormsg) ? 'error' : 'updated fade';
		$class = ($errormsg == 2) ? 'updated update-nag notice notice-warning' : $class; 	// warning
		$message = '<div id="message" class="'.$class.'" style="display:block !important"><p>'.$message.'</p></div>'."\n";
		if ($echo) {
			echo $message;
		} else {
			$this->message .= $message;
		}
	}

}
// $WPLA_CronActions = new WPLA_CronActions();
