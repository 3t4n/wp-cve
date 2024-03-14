<?php
/**
 * WPLA_AmazonFeed class
 *
 */

class WPLA_AmazonFeed {

	const TABLENAME = 'amazon_feeds';

	const STATUS_PENDING    = 'pending';
	const STATUS_SUBMITTED  = 'submitted';
	const STATUS_PROCESSING = 'processing';

	var $id                 = null;
	var $data               = null;
	var $feedOptions        = null;
	var $MarketplaceIdList  = null;
	var $results            = null;
	var $types              = array();

	function __construct( $id = null ) {
		
		$this->init();

		if ( $id ) {
			$this->id = $id;
			
			// load data into object
			$feed = self::getFeed( $id );
			foreach( $feed AS $key => $value ){
			    $this->$key = $value;
			}

			return $this;
		}

	}

	function init()	{

		$this->types = array(
			'_POST_FLAT_FILE_PRICEANDQUANTITYONLY_UPDATE_DATA_' => 'Price and Quantity Update Feed',
			'POST_FLAT_FILE_PRICEANDQUANTITYONLY_UPDATE_DATA'   => 'Price and Quantity Update Feed',
			'_POST_FLAT_FILE_LISTINGS_DATA_'                 	=> 'Listings Data Feed',
			'POST_FLAT_FILE_LISTINGS_DATA'                 	    => 'Listings Data Feed',
			'_CHECK_FLAT_FILE_LISTINGS_DATA_'                 	=> 'Listings Data Feed (check only)',
			'_POST_FLAT_FILE_FULFILLMENT_DATA_'      			=> 'Order Fulfillment Feed',
			'POST_FLAT_FILE_FULFILLMENT_DATA'      			    => 'Order Fulfillment Feed',
			'_POST_FLAT_FILE_FULFILLMENT_ORDER_REQUEST_DATA_'   => 'FBA Shipment Fulfillment Feed',
			'POST_FLAT_FILE_FULFILLMENT_ORDER_REQUEST_DATA'     => 'FBA Shipment Fulfillment Feed',
			'_POST_FLAT_FILE_INVLOADER_DATA_'                   => 'Inventory Loader Feed',
			'POST_FLAT_FILE_INVLOADER_DATA'                     => 'Inventory Loader Feed',
			'_UPLOAD_VAT_INVOICE_'                              => 'Upload VAT Invoice',
			'UPLOAD_VAT_INVOICE'                                => 'Upload VAT Invoice',
		);

		$this->fieldnames = array(
			'FeedSubmissionId',
			'FeedType',
			'template_name',
			'FeedProcessingStatus',
			'results',
			'success',
			'status',
			'SubmittedDate',
			'StartedProcessingDate',
			'CompletedProcessingDate',
			'GeneratedFeedId',
			'date_created',
			'account_id',
			'line_count',
			'data',
            'feedOptions',
            'MarketplaceIdList'
		);

	} // init()

    /**
     * Gets a FeedType from \SellingPartner\FeedType
     *
     * @param string $feed_type See WPLA_AmazonFeed::init() for the different types
     * @param string $fallback_content_type The content type value to return if the feed type isn't found
     * @return array
     */
    static function getFeedType( $feed_type, $fallback_content_type = 'XML' ) {
        // remove the underscore from the prefix and suffix
        $feed_type = trim( $feed_type, '_' );

	    if ( defined( '\SellingPartnerApi\FeedType::' . $feed_type ) ) {
	        return constant( '\SellingPartnerApi\FeedType::'. $feed_type );
        }

	    // feed_type not found.
        return [
            'name'          => $feed_type,
            'contentType'   => $fallback_content_type
        ];
    }

    /**
     * Get a single feed from the DB
     * @param int $id
     * @return object|null
     */
	static function getFeed( $id )	{
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

//        $lock_row_sql  = apply_filters( 'wpla_lock_feeds_table', false ) ? ' FOR UPDATE ' : '';
        $lock_row_sql  = '';

		$item = $wpdb->get_row( $wpdb->prepare("
			SELECT *
			FROM $table
			WHERE id = %d
			$lock_row_sql
		", $id
		), OBJECT);

        if ( $item ) {
            if ( $item->FeedType == 'POST_FLAT_FILE_FULFILLMENT_DATA' && empty( $item->data ) ) {
                $rows = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$wpdb->prefix}amazon_fulfillment_feed_items WHERE feed_id = %d", $item->id ), ARRAY_A );

                // build csv
                $columns = array(
                    'order-id', 		// required
                    'order-item-id',
                    'quantity',
                    'ship-date', 		// required
                    'carrier-code',
                    'carrier-name',
                    'tracking-number',
                    'ship-method',
                    'ship_from_address_name',
                    'ship_from_address_line1',
                    'ship_from_address_line2',
                    'ship_from_address_city',
                    'ship_from_address_state_or_region',
                    'ship_from_address_postalcode',
                    'ship_from_address_countrycode',
                );
                $csv_header = join( "\t", $columns ) . "\n";
                $csv_body = '';

                foreach ( $rows as $row ) {
                    $csv_body .= $row['order_id'] ."\t".
                        $row['order_item_id'] ."\t".
                        $row['quantity'] ."\t".
                        $row['ship_date'] ."\t".
                        $row['carrier_code'] ."\t".
                        $row['carrier_name'] ."\t".
                        $row['tracking_number'] ."\t".
                        $row['ship_method'] ."\t".
                        $row['ship_from_address_name'] ."\t".
                        $row['ship_from_address_line1'] ."\t".
                        $row['ship_from_address_line2'] ."\t".
                        $row['ship_from_address_city'] ."\t".
                        $row['ship_from_address_state'] ."\t".
                        $row['ship_from_address_postal'] ."\t".
                        $row['ship_from_address_country'] ."\n";
                }

                $item->data = $csv_header . $csv_body;
                $item->line_count = count( $rows );
            }
        }

		return $item;
	}

    /**
     * get single feed by FeedSubmissionId
     *
     * @param $feed_submission_id
     * @return array|object|void|null
     */
	static function getFeedBySubmissionId( $feed_submission_id )	{
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;
		
		$item = $wpdb->get_row( $wpdb->prepare("
			SELECT *
			FROM $table
			WHERE FeedSubmissionId = %s
		", $feed_submission_id
		), OBJECT);

		return $item;
	}

    /**
     * Get all feeds
     *
     * @return object[]
     */
	static function getAll() {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		$items = $wpdb->get_results("
			SELECT *
			FROM $table
			ORDER BY sort_order ASC
		", OBJECT_K);

		return $items;
	}

    /**
     * Get all submitted feeds that need to be checked and eventually processed
     *
     * @param $account_id
     * @return object[]
     */
	static function getSubmittedFeedsForAccount( $account_id ) {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		$items = $wpdb->get_results( $wpdb->prepare("
			SELECT *
			FROM $table
			WHERE account_id = %s
			  AND ( FeedProcessingStatus = '_SUBMITTED_' 
			  	 OR FeedProcessingStatus = '_IN_PROGRESS_' 
			  	 OR FeedProcessingStatus = 'IN_PROGRESS' 
			  	 OR FeedProcessingStatus = 'IN_QUEUE' 
			  	 OR status = %s )
			ORDER BY SubmittedDate DESC
		", $account_id, self::STATUS_SUBMITTED
		), OBJECT_K);

		return $items;
	}

    /**
     * Get the ID of the latest pending feed for the template and account
     *
     * @param string $feed_type
     * @param string $template_name
     * @param int $account_id
     * @return string|null
     */
	static function getPendingFeedId( $feed_type, $template_name, $account_id ) {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;
		$template_name = esc_sql( $template_name );
		$where_sql     = $template_name ? "AND template_name = '$template_name'" : '';

		$item = $wpdb->get_var( $wpdb->prepare("
			SELECT id
			FROM $table
			WHERE status     = %s
			  AND account_id = %d
			  AND FeedType   = %s
			  $where_sql
		    ",
            self::STATUS_PENDING,
		    $account_id,
		    $feed_type
		));

		return $item;
	}

    /**
     * Get all pending feeds for account
     *
     * @param $account_id
     * @return WPLA_AmazonFeed[]
     */
	static function getAllPendingFeedsForAccount( $account_id ) {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		$item_ids = $wpdb->get_col( $wpdb->prepare("
			SELECT id
			FROM $table
			WHERE status     = %s
			  AND account_id = %d
		", self::STATUS_PENDING,  $account_id ));

		$feeds = array();
		foreach ( $item_ids as $feed_id ) {
			$feeds[] = new WPLA_AmazonFeed( $feed_id );
		}

		return $feeds;
	}

    /**
     * Get IDs of pending feeds
     *
     * @return int[]
     */
	static function getAllPendingFeeds() {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		$item_ids = $wpdb->get_col($wpdb->prepare("
			SELECT id
			FROM $table
			WHERE status = %s
		", self::STATUS_PENDING ));

		return $item_ids;
	}

	/**
     * Get all Price&Quantity feed IDs for a specific SKU
     *
     * @param string $sku
     * @return int[]
     */
	static function getAllPnqFeedsForSKU( $sku ) {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		$item_ids = $wpdb->get_col( $wpdb->prepare("
			SELECT id
			FROM $table
			WHERE FeedType IN ('_POST_FLAT_FILE_PRICEANDQUANTITYONLY_UPDATE_DATA_', 'POST_FLAT_FILE_PRICEANDQUANTITYONLY_UPDATE_DATA' ) 
			AND data LIKE %s
			ORDER BY id DESC
			LIMIT 20
		", '%'.$sku.'%' ) );

		return $item_ids;
	}

    /**
     * Returns the data from the feed without the extra and unnecessary rows
     *
     * @return array
     */
	function getDataArray() {
		if ( ! $this->data || empty( $this->data ) ) return array();

		$feed_data = $this->data;
		if ( in_array( $this->FeedType, array('_POST_FLAT_FILE_LISTINGS_DATA_','_CHECK_FLAT_FILE_LISTINGS_DATA_', 'POST_FLAT_FILE_LISTINGS_DATA') ) ) {
			// remove first two rows - headers are in 3rd row
			$rows_to_remove = 2;

			// check for number fo header rows
			$rows_tmp = explode("\n", $feed_data);

			// handle legacy ListingLoader feeds
			if ( in_array( $this->template_name, array('Offer','LiLo') ) && strpos($feed_data,'Version=1.4') && substr($rows_tmp[2],0,3) != 'sku' ) {
				$rows_to_remove = 1; // LiLo/Offer 1.4 uses a single header row, product data starts in 3rd row
			}

			$feed_data = implode("\n", array_slice(explode("\n", $feed_data), $rows_to_remove ));
		}

		$rows = WPLA_ReportProcessor::csv_to_array( $feed_data );
		return $rows;		
	}

    /**
     * Gets the data for a specific SKU
     * @param $sku
     * @return bool|array
     */
	function getDataRowForSKU( $sku ) {
		if ( ! $this->data || empty( $this->data ) ) return false;

		$data_rows = $this->getDataArray();
		foreach ( $data_rows as $row ) {
			if ( $row['sku'] == $sku )
				return $row;
		}

		return false;
	}

	function getFeedVersion() {
		if ( preg_match('/Version=(.*)\t/U', $this->data, $matches) ) {
			return $matches[1];
		}
		return null;
	}

	function getFeedCategory() {
		if ( preg_match('/Category=(.*)\t/U', $this->data, $matches) ) {
			return $matches[1];
		}
		return null;
	}

	function getFeedSignature() {
		if ( preg_match('/TemplateSignature=(.*)\t/U', $this->data, $matches) ) {
			return $matches[1];
		}
		return null;
	}


	function createCheckFeed() {
		if ( ! $this->id ) return;
		if ( ! $this->data ) return;

		// clone feed
		$this->id = null;
		$this->FeedType = str_replace( '_POST_', '_CHECK_', $this->FeedType );
		$this->add();
		
		// submit cloned feed
		$result = $this->submit();

		return $result;
	}

	function isCheckFeed() {
		if ( ! $this->FeedType ) return false;
		if ( substr( $this->FeedType, 0, 7 ) == '_CHECK_' ) return true;
		return false;
	}


	function cancel() {
		if ( ! $this->id ) return;

		$api = new WPLA_Amazon_SP_API( $this->account_id );
		$result = $api->cancelFeed( $this->FeedSubmissionId );
		// echo "<pre>";print_r($result);echo"</pre>";die();

		if ( $result->success ) {
			
			// update feed status
			// $this->FeedSubmissionId     = $result->FeedSubmissionId;
			// $this->FeedProcessingStatus = $result->FeedProcessingStatus;
			// $this->SubmittedDate        = $result->SubmittedDate;
			// $this->status 		    	= 'cancelled';
			// $this->update();

		} // success

		return $result;
	} // cancel()

    /**
     * Submit the current feed to Amazon via SP-API
     */
	function submit() {
		if ( ! $this->id ) return;
		if ( ! $this->data ) return;
        if ( $this->status != self::STATUS_PENDING ) return;

        $api = new WPLA_Amazon_SP_API( $this->account_id );

		// adjust feed encoding
		$feed_content = $this->data;

		// Feed content is in utf-8 encoding so there's no need to decode
		$decode_feed = false;

		// If the selected feed encoding is ISO-8859, we need to decode from utf8
        // but only if the FeedType is not UPLOAD_VAT_INVOICE because Invoice PDFs need to be in UTF8
        if ( get_option( 'wpla_feed_encoding' ) != 'UTF-8' && $this->FeedType != 'UPLOAD_VAT_INVOICE' ) {
            $decode_feed = true;
        }

		if ( apply_filters( 'wpla_utf8_decode_feed_content', $decode_feed, $this ) ) {
			$feed_content = utf8_decode( $feed_content );
		}

        $feed_type = self::getFeedType( $this->FeedType );
        $result = $api->submitFeed( $feed_type['name'], $feed_content, $this->feedOptions, $this->MarketplaceIdList );
		// echo "<pre>";print_r($result);echo"</pre>";die();

        if ( !WPLA_Amazon_SP_API::isError( $result ) ) {
            // update feed status
            $this->FeedSubmissionId     = $result->FeedSubmissionId;
            $this->FeedProcessingStatus = $result->FeedProcessingStatus;
            $this->SubmittedDate        = $result->SubmittedDate;
            $this->status 		    	= self::STATUS_SUBMITTED;
            $this->update();

            // increase feeds in progress
            $feeds_in_progress = get_option( 'wpla_feeds_in_progress', 0 );
            update_option( 'wpla_feeds_in_progress', $feeds_in_progress + 1 );


            // update status of submitted products - except for check feeds
            if ( ! $this->isCheckFeed() && 'POST_FLAT_FILE_FULFILLMENT_DATA' != $this->FeedType ) {

                $lm = new WPLA_ListingsModel();
                // $rows = WPLA_ReportProcessor::csv_to_array( $this->data );
                $rows = $this->getDataArray();
                foreach ($rows as $row) {
                    $listing_sku      = false;

                    if ( !empty( $row['sku'] ) ) {
                        $listing_sku = $row['sku'];
                    } elseif ( !empty( $row['item_sku'] ) ) {
                        $listing_sku = $row['item_sku'];
                    }

                    $listing_item = $lm->getItemBySkuAndAccount( $listing_sku, $this->account_id );

                    if ( $listing_item ) {
                        $listing_data = array(); // initialize to prevent error messages

                        // check feed type
                        switch ($this->FeedType) {

                            // Listing Data feed
                            case '_POST_FLAT_FILE_LISTINGS_DATA_':
                            case 'POST_FLAT_FILE_LISTINGS_DATA':

                                $listing_data = array();
                                $listing_data['status']  = 'submitted';
                                $listing_data['history'] = '';
                                WPLA()->logger->info('changing status to submitted for SKU '.$listing_sku);

                                // update date_published - only if not set
                                if ( ! $listing_item->date_published )
                                    $listing_data['date_published'] = gmdate('Y-m-d H:i:s');

                                break;

                            // Price And Quantity feed
                            case '_POST_FLAT_FILE_PRICEANDQUANTITYONLY_UPDATE_DATA_':
                            case 'POST_FLAT_FILE_PRICEANDQUANTITYONLY_UPDATE_DATA':

                                $listing_data = array();
                                $listing_data['pnq_status'] = '2'; // submitted
                                WPLA()->logger->info('changing PNQ status to 2 (submitted) for SKU '.$listing_sku);

                                break;

                            // Inventory Loader (delete) feed
                            case '_POST_FLAT_FILE_INVLOADER_DATA_':
                            case 'POST_FLAT_FILE_INVLOADER_DATA':

                                // first we need to check whether a product was marked for deletion or not
                                // then either change status to trashed or submitted
                                $logger = new WC_Logger();
                                $logger->add( 'wpla', 'deleting listing '. $listing_sku .' (status: '. $listing_item->status .')' );

                                $listing_data = array();
                                $listing_data['history'] = '';

                                if ( $listing_item->status == 'trash' ) {
                                    $listing_data['status']  = 'trashed';	// submitted for deletion
                                    WPLA()->logger->info('changing status to trashed for SKU '.$listing_sku);
                                } else {
                                    $listing_data['status']  = 'submitted';	// submitted as InventoryLoader listing
                                    WPLA()->logger->info('changing status to submitted for SKU '.$listing_sku);
                                }


                                // update date_published - only if not set
                                if ( ! $listing_item->date_published )
                                    $listing_data['date_published'] = gmdate('Y-m-d H:i:s');

                                break;

                            default:
                                WPLA()->logger->warn('nothing to process for feed type '.$this->FeedType.' - SKU '.$listing_sku);
                                break;
                        }

                        // update database
                        $where_array = array( 'sku' => $listing_sku, 'account_id' => $this->account_id );
                        $lm->updateWhere( $where_array, $listing_data );
                    } else {
                        WPLA()->logger->warn('no listing found for SKU '.$listing_sku);
                    } // if $listing_item

                } // for each row

            } // not check feed
        }

		return $result;
	} // submit()

	function loadSubmissionResult() {
		if ( ! $this->id ) return;
		if ( ! $this->FeedSubmissionId ) return;
		if ( ! $this->FeedDocumentId ) return;
		if ( $this->FeedProcessingStatus != 'DONE' ) return;

		$api    = new WPLA_Amazon_SP_API( $this->account_id );
		$result = $api->getFeedDocument( $this->FeedDocumentId );

		if ( !WPLA_Amazon_SP_API::isError( $result ) ) {
			$this->results = utf8_encode( $result ); // required for amazon.fr
			$this->update();
		}

		return $result;
	} // loadSubmissionResult()

	function processSubmissionResult() {
		WPLA()->logger->info('processSubmissionResult() - feed '.$this->id);
		if ( ! $this->id ) return;
		if ( ! $this->results ) return;

		$this->errors   = array();
		$this->warnings = array();

		// fetch list of submitted product SKUs
		$feed_rows = $this->getDataArray();
		WPLA()->logger->info('data rows   for feed '.$this->FeedSubmissionId.' ('.$this->id.'): '.sizeof($feed_rows));

		// extract result csv data
		$result_content = implode("\n", array_slice(explode("\n", $this->results), 4)); // remove summary rows
		$result_rows = WPLA_ReportProcessor::csv_to_array( $result_content );
		WPLA()->logger->info('result rows for feed '.$this->FeedSubmissionId.' ('.$this->id.'): '.sizeof($result_rows));
		WPLA()->logger->info('result rows '.print_r($result_rows,1));

		/* @todo Process UPLOAD_VAT_INVOICE results */
		// process results
		if ( $this->FeedType == 'POST_FLAT_FILE_FULFILLMENT_DATA' ) {
			$this->processOrderFulfillmentResults( $feed_rows, $result_rows );
		} elseif ( $this->FeedType == 'POST_FLAT_FILE_FULFILLMENT_ORDER_REQUEST_DATA' ) {
			$this->processOrderFbaResults( $feed_rows, $result_rows );
		} elseif ( $this->FeedType == 'POST_FLAT_FILE_PRICEANDQUANTITYONLY_UPDATE_DATA' ) {
			$this->processListingPnqResults( $feed_rows, $result_rows );
		} else {
			$this->processListingDataResults( $feed_rows, $result_rows );			
		}

		// update feed status
		$this->success = sizeof( $this->warnings ) > 0 ? 'warning' : 'success';
		$this->success = sizeof( $this->errors ) > 0 ? 'error' : $this->success;
		$this->status = 'processed';
		$this->update();
		WPLA()->logger->info('feed has been processed');

		if ( sizeof( $this->errors ) && get_option( 'wpla_feed_failure_emails', 0 ) ) {
            $this->sendFeedFailureEmail();
        }

		return true;
	} // processSubmissionResult()

	public function processListingDataResults( $feed_rows, $result_rows ) {

		$lm = new WPLA_ListingsModel();

		// index results by SKU
		$results = array();
		foreach ( $result_rows as $r ) {
			if ( ! isset( $r['sku'] ) && isset( $r['SKU'] ) ) $r['sku'] = $r['SKU']; // translate column SKU -> sku
			if ( ! isset( $r['sku'] ) || empty( $r['sku'] ) ) continue;
			$results[ $r['sku'] ][] = $r;
			WPLA()->logger->info('result sku: '.$r['sku']);
		}

		// process each result row
		foreach ($feed_rows as $row) {
			$listing_data = array();
            $row_sku      = false;

            if ( !empty( $row['item_sku'] ) ) {
                $row_sku = $row['item_sku'];
            } elseif ( !empty( $row['sku'] ) ) {
                $row_sku = $row['sku'];
            }

			if ( ! $row_sku ) {
				WPLA()->logger->warn('skipping row without SKU: '.print_r($row,1));
				continue;
			}

			$row_results = isset( $results[ $row_sku ] ) ? $results[ $row_sku ] : false;
			WPLA()->logger->info('processing feed sku: '.$row_sku);

			// check if this is a delete feed (Inventory Loader)
			$add_delete_column = isset($row['add-delete']) ? $row['add-delete'] : '';
			$is_delete_feed = $add_delete_column == 'x' ? true : false;

			// if there are no result rows for this SKU, set status to 'online'
			if ( ! $row_results ) {
                $listing = $lm->getItemBySkuAndAccount( $row_sku, $this->account_id );

				if ( $is_delete_feed ) {
					//$listing = $lm->getItemBySKU( $row_sku );

					if ( ! $listing ) continue;
					if ( $listing->status == 'trashed' ) {
						$lm->deleteItem( $listing->id );
						WPLA()->logger->info('DELETED listing ID '.$listing->id.' SKU: '.$row_sku);
					} else {					
						WPLA()->logger->warn('INVALID listing status for deletion - ID '.$listing->id.' / SKU: '.$row_sku.' / status: '.$listing->status);
					}
					continue;
				}

				$listing_data['status']  = 'online';
				$listing_data['history'] = '';
				$lm->updateWhere( array( 'sku' => $row_sku, 'account_id' => $this->account_id ), $listing_data );
				WPLA()->logger->info('changed status to online: '.$row_sku);

				// if this listing is part of a non-variation profile, mark the parent listing as online so it doesn't
                // get stuck as prepared #53568
                //$lm->maybeUpdateParentStatus( $listing );


				continue;

			}

			// handle errors and warnings
			$errors         = array();
			$warnings       = array();
			$processed_keys = array();
			foreach ($row_results as $row_result) {

				// translate error-type
				if ( $row_result['error-type'] == 'Fehler' ) 		$row_result['error-type'] = 'Error';	// amazon.de
				if ( $row_result['error-type'] == 'Warnung' ) 		$row_result['error-type'] = 'Warning';
				if ( $row_result['error-type'] == 'Erreur' ) 		$row_result['error-type'] = 'Error';	// amazon.fr
				if ( $row_result['error-type'] == 'Avertissement' ) $row_result['error-type'] = 'Warning';

				// compute hash to identify duplicate errors
				$row_key = md5( $row_result['sku'] . $row_result['error-code'] . $row_result['error-type'] . $row_result['original-record-number'] );

				// store feed id in error array
				$row_result['feed_id'] = $this->id;

				if ( 'Error' == $row_result['error-type'] ) {

					WPLA()->logger->info('error: '.$row_sku.' - '.$row_key.' - '.$row_result['error-message']);
					if ( ! in_array($row_key, $processed_keys) ) {
						$errors[]         = $row_result;
						$processed_keys[] = $row_key;
					}

				} elseif ( 'Warning' == $row_result['error-type'] ) {

					WPLA()->logger->info('warning: '.$row_sku.' - '.$row_key.' - '.$row_result['error-message']);
					if ( ! in_array($row_key, $processed_keys) ) {
						$warnings[]       = $row_result;
						$processed_keys[] = $row_key;
					}

				}

			} // foreach result row

			// update listing
			if ( ! empty( $errors ) ) {

				$listing_data['status']  = 'failed';
				$listing_data['history'] = serialize( array( 'errors' => $errors, 'warnings' => $warnings ) );
				$lm->updateWhere( array( 'sku' => $row_sku, 'account_id' => $this->account_id ), $listing_data );				
				WPLA()->logger->info('changed status to FAILED: '.$row_sku);

				$this->errors   = array_merge( $this->errors, $errors);
				$this->warnings = array_merge( $this->warnings, $warnings);

			} elseif ( ! empty( $warnings ) ) {

				$listing_data['status']  = $is_delete_feed ? 'trashed' : 'online';
				$listing_data['history'] = serialize( array( 'errors' => $errors, 'warnings' => $warnings ) );
				$lm->updateWhere( array( 'sku' => $row_sku, 'account_id' => $this->account_id ), $listing_data );				

				WPLA()->logger->info('changed status to online: '.$row_sku);
				$this->warnings = array_merge( $this->warnings, $warnings);

			}

		} // foreach row

	} // processListingDataResults()

	public function processListingPnqResults( $feed_rows, $result_rows ) {

		$lm = new WPLA_ListingsModel();

		// index results by SKU
		$results = array();
		foreach ( $result_rows as $r ) {
			if ( ! isset( $r['sku'] ) || empty( $r['sku'] ) ) continue;
			$results[ $r['sku'] ][] = $r;
			WPLA()->logger->info('result sku: '.$r['sku']);
		}

		// process each result row
		foreach ($feed_rows as $row) {
			$listing_data = array();

			$row_sku = $row['sku'];
			if ( ! $row_sku ) {
				WPLA()->logger->warn('skipping row without SKU: '.print_r($row,1));
				continue;
			}

			$row_results = isset( $results[ $row_sku ] ) ? $results[ $row_sku ] : false;
			WPLA()->logger->info('processing feed sku: '.$row_sku);

			// if there are no result rows for this SKU, set status to 'online'
			if ( ! $row_results ) {

				$listing_data['pnq_status']  = '0';
				$lm->updateWhere( array( 'sku' => $row_sku, 'pnq_status' => '2', 'account_id' => $this->account_id ), $listing_data );
				WPLA()->logger->info('changed status to online: '.$row_sku);
				continue;

			}

			// handle errors and warnings
			$errors         = array();
			$warnings       = array();
			$processed_keys = array();
			foreach ($row_results as $row_result) {

				// translate error-type
				if ( $row_result['error-type'] == 'Fehler' ) 		$row_result['error-type'] = 'Error';	// amazon.de
				if ( $row_result['error-type'] == 'Warnung' ) 		$row_result['error-type'] = 'Warning';
				if ( $row_result['error-type'] == 'Erreur' ) 		$row_result['error-type'] = 'Error';	// amazon.fr
				if ( $row_result['error-type'] == 'Avertissement' ) $row_result['error-type'] = 'Warning';

				// compute hash to identify duplicate errors
				$row_key = md5( $row_result['sku'] . $row_result['error-code'] . $row_result['error-type'] . $row_result['original-record-number'] );

				if ( 'Error' == $row_result['error-type'] ) {

					WPLA()->logger->info('error: '.$row_sku.' - '.$row_key.' - '.$row_result['error-message']);
					if ( ! in_array($row_key, $processed_keys) ) {
						$errors[]         = $row_result;
						$processed_keys[] = $row_key;
					}

				} elseif ( 'Warning' == $row_result['error-type'] ) {

					WPLA()->logger->info('warning: '.$row_sku.' - '.$row_key.' - '.$row_result['error-message']);
					if ( ! in_array($row_key, $processed_keys) ) {
						$warnings[]       = $row_result;
						$processed_keys[] = $row_key;
					}

				}

			} // foreach result row

			// update listing
			if ( ! empty( $errors ) ) {

				$listing_data['pnq_status']  = '-1';
				$lm->updateWhere( array( 'sku' => $row_sku, 'pnq_status' => '2', 'account_id' => $this->account_id ), $listing_data );
				WPLA()->logger->info('changed PNQ status to FAILED (-1): '.$row_sku);

				$this->errors   = array_merge( $this->errors, $errors);
				$this->warnings = array_merge( $this->warnings, $warnings);

			} elseif ( ! empty( $warnings ) ) {

				$listing_data['pnq_status']  = '0';
				$lm->updateWhere( array( 'sku' => $row_sku, 'pnq_status' => '2', 'account_id' => $this->account_id ), $listing_data );

				WPLA()->logger->info('changed PNQ status to 0: '.$row_sku);
				$this->warnings = array_merge( $this->warnings, $warnings);

			}

		} // foreach row

	} // processListingPnqResults()

	public function processOrderFulfillmentResults( $feed_rows, $result_rows ) {

		$om = new WPLA_OrdersModel();

		// index results by OrderID
		$results = array();
		foreach ( $result_rows as $r ) {
			if ( ! isset( $r['order-id'] ) || empty( $r['order-id'] ) ) continue;
			$results[ $r['order-id'] ][] = $r;
			WPLA()->logger->info('result order_id: '.$r['order-id']);
		}

		// process each result row
		foreach ($feed_rows as $row) {
			$order_data = array();

			$row_order_id = $row['order-id'];
			if ( ! $row_order_id ) {
				WPLA()->logger->warn('skipping row without OrderID: '.print_r($row,1));
				continue;
			}

			$row_results = isset( $results[ $row_order_id ] ) ? $results[ $row_order_id ] : false;
			WPLA()->logger->info('processing feed OrderID: '.$row_order_id);

			$order = $om->getOrderByOrderID( $row_order_id );
			$post_id = $order->post_id;
			$wc_order = wc_get_order( $post_id );

			// if there are no result rows for this OrderID, set status to 'Shipped'
			if ( ! $row_results ) {

				$order_data['status']  = 'Shipped';
				// $order_data['history'] = '';
				// $om->updateWhere( array( 'order_id' => $row_order_id, 'account_id' => $this->account_id ), $order_data );				
				WPLA()->logger->info('changed status to Shipped: '.$row_order_id);
				if ( $wc_order ) {
				    $wc_order->update_meta_data( '_wpla_submission_result', 'success' );
                }
				continue;

			}

			// handle errors and warnings
			$errors = array();
			$warnings = array();
			WPLA()->logger->info('processing row results: '.print_r($row_results,1));
			foreach ($row_results as $row_result) {

				if ( 'Error' == $row_result['error-type'] ) {

					WPLA()->logger->info('error: '.$row_order_id.' - '.$row_result['error-message']);
					$errors[] = $row_result;

				} elseif ( 'Warning' == $row_result['error-type'] ) {
				    // Ignore missing tracking number error #32219 #32212
                    if ( strpos( $row_result['error-message'], 'does not match the expected format of the carrier' ) !== false ) {
                        continue;
                    }

					WPLA()->logger->info('warning: '.$row_order_id.' - '.$row_result['error-message']);
					$warnings[] = $row_result;

				}

			} // foreach result row

			// update order
			if ( ! empty( $errors ) ) {

				$order_data['status']  = 'failed';
				// $order_data['history'] = serialize( array( 'errors' => $errors, 'warnings' => $warnings ) );
				// $om->updateWhere( array( 'order_id' => $row_order_id, 'account_id' => $this->account_id ), $order_data );				
				if ( $wc_order ) {
				    $wc_order->update_meta_data( '_wpla_submission_result', serialize( array( 'errors' => $errors, 'warnings' => $warnings ) ) );
                }

				WPLA()->logger->info('changed status to FAILED: '.$row_order_id);
				$this->errors   = array_merge( $this->errors, $errors);
				$this->warnings = array_merge( $this->warnings, $warnings);

			} elseif ( ! empty( $warnings ) ) {

				$order_data['status']  = 'Shipped';
				// $order_data['history'] = serialize( array( 'errors' => $errors, 'warnings' => $warnings ) );
				// $om->updateWhere( array( 'order_id' => $row_order_id, 'account_id' => $this->account_id ), $order_data );				
				if ( $wc_order ) {
				    $wc_order->update_meta_data( '_wpla_submission_result', serialize( array( 'errors' => $errors, 'warnings' => $warnings ) ) );
                }

				WPLA()->logger->info('changed status to Shipped: '.$row_order_id);
				$this->warnings = array_merge( $this->warnings, $warnings);

			}

            // if there are no errors and warnings for this OrderID, set status to 'Shipped' #32324
            if ( empty( $errors ) && empty( $warnings ) ) {
                $order_data['status']  = 'Shipped';
                // $order_data['history'] = '';
                // $om->updateWhere( array( 'order_id' => $row_order_id, 'account_id' => $this->account_id ), $order_data );
                WPLA()->logger->info('changed status to Shipped: '.$row_order_id);
                if ( $wc_order ) {
                    $wc_order->update_meta_data( '_wpla_submission_result', 'success' );
                }
                continue;

            }

		} // foreach row

        if ( $wc_order ) {
            $wc_order->save();
        }

	} // processOrderFulfillmentResults()

	public function processOrderFbaResults( $feed_rows, $result_rows ) {
		$om = new WPLA_OrdersModel();

		// index results by "original-record-number" (feed row index)
		$results = array();
		foreach ( $result_rows as $r ) {
			if ( ! isset( $r['original-record-number'] ) || empty( $r['original-record-number'] ) ) continue;
			$results[ $r['original-record-number'] ][] = $r;
			WPLA()->logger->info('result row found for row: '.$r['original-record-number']);
		}

		// process each result row
		$row_index = 0;
		foreach ($feed_rows as $row) {
			$order_data = array();
			$row_index++;

			$row_order_id = $row['MerchantFulfillmentOrderID'];
			if ( ! $row_order_id ) {
				WPLA()->logger->warn('skipping row without OrderID: '.print_r($row,1));
				continue;
			}

			// find order's $post_id based on MerchantFulfillmentOrderID - required if this site uses custom order numbers
			if ( $post_id = WPLA_OrdersModel::getWooOrderIdByMerchantFulfillmentOrderID( $row_order_id ) ) {
				WPLA()->logger->info('found order post_id '.$post_id.' for Order '.$row_order_id);
			} else {
				$post_id = str_replace( '#', '', $row_order_id ); // fall back to old behavior
			}

			$row_results = isset( $results[ $row_index ] ) ? $results[ $row_index ] : false;
			WPLA()->logger->info('processing feed row '.$row_index.' for Order #'.$post_id);

            $_order = wc_get_order( $post_id );

			// if there are no result rows for this OrderID, set FBA submission status to 'success'
			if ( ! $row_results ) {

				$submission_status = 'success';

				// if the order status is on-hold, set submission status to 'hold'.
                // Otherwise, mark the order as completed #48438
				if ( $_order->get_status() == 'on-hold' ) {
				    $submission_status = 'hold';
                } elseif ( get_option( 'wpla_fba_complete_shipped_orders', 0 ) ) {
                    $_order->update_status( get_option( 'wpla_shipped_order_status', 'completed' ) );
                }


				WPLA()->logger->info('changed FBA submission status to '.$submission_status.': '.$row_order_id);
				$_order->update_meta_data( '_wpla_fba_submission_status', $submission_status );
				continue;
			}

			// handle errors and warnings
			$errors = array();
			$warnings = array();
			WPLA()->logger->info('processing row results: '.print_r($row_results,1));
			foreach ($row_results as $row_result) {
			    $error_type = !empty( $row_result['type'] ) ? $row_result['type'] : $row_result['error-type'];

				if ( 'Error' == $error_type ) {
                    // Add an order note about this failure #43498
                    if ( $_order ) {
                        $_order->add_order_note( 'FBA Submission Error: ['. $row_order_id .'] - '. $row_result['error-message'] );
                    }

					WPLA()->logger->info('error: '.$row_order_id.' - '.$row_result['error-message']);
					$errors[] = $row_result;


				} elseif ( 'Warning' == $error_type ) {

					WPLA()->logger->info('warning: '.$row_order_id.' - '.$row_result['error-message']);
					$warnings[] = $row_result;

				}

			} // foreach result row

			// update order
			if ( ! empty( $errors ) ) {

				$_order->update_meta_data( '_wpla_fba_submission_status', 'failed' );
				$_order->update_meta_data( '_wpla_fba_submission_result', array( 'errors' => $errors, 'warnings' => $warnings ) );
				WPLA()->logger->info("changed FBA submission status to FAILED: $row_order_id (ID $post_id)");

				$this->errors   = array_merge( $this->errors, $errors);
				$this->warnings = array_merge( $this->warnings, $warnings);

			} else {
			    if ( ! empty( $warnings ) ) {
                    $this->warnings = array_merge($this->warnings, $warnings);
                }

			    $submission_status = 'success';

                if ( $_order && get_option( 'wpla_fba_complete_shipped_orders', 0 ) ) {
                    // if the order status is on-hold, set submission status to 'hold'.
                    // Otherwise, mark the order as completed #48438
                    if ( $_order->get_status() == 'on-hold' ) {
                        $submission_status = 'hold';
                    } else {
                        $_order->update_status( get_option( 'wpla_shipped_order_status', 'completed' ) );
                    }
                }

                $_order->update_meta_data( '_wpla_fba_submission_status', $submission_status );
                $_order->update_meta_data( '_wpla_fba_submission_result', array( 'errors' => $errors, 'warnings' => $warnings ) );
                WPLA()->logger->info("changed FBA submission status to '. $submission_status .': $row_order_id (ID $post_id)");
            }

			if ( $_order ) {
                $_order->save();
            }

		} // foreach row

	} // processOrderFbaResults()

    /**
     * Update the Feeds table with data from the Feeds API
     * @param \SellingPartnerApi\Model\FeedsV20210630\Feed[] $feeds
     * @param $account
     * @return int
     */
	static public function processFeedsSubmissionList( $feeds, $account ) {
		WPLA()->logger->info( 'processFeedsSubmissionList() - processing '.sizeof($feeds).' feeds for account '.$account->id) ;

		$feeds_in_progress = 0;

		foreach ($feeds as $feed) {
			if ( !$feed || !is_callable( array( $feed, 'getFeedId' ) ) ) {
                WPLA()->logger->info('Invalid feed object. Skipping.' );
                continue;
            }

			// check if feed exists
			$existing_record = WPLA_AmazonFeed::getFeedBySubmissionId( $feed->getFeedId() );
			if ( $existing_record ) {

				// skip existing feed if it was submitted using another "account" (different marketplace using the same account)
				if ( $existing_record->account_id != $account->id ) {
					WPLA()->logger->info('skipped existing feed '.$existing_record->id.' for account '.$existing_record->account_id);
					continue;
				}

				$new_feed = new WPLA_AmazonFeed( $existing_record->id );

				$new_feed->FeedSubmissionId        = $feed->getFeedId();
				$new_feed->FeedType                = $feed->getFeedType();
				$new_feed->FeedProcessingStatus    = $feed->getProcessingStatus();
				$new_feed->SubmittedDate           = $feed->getProcessingStartTime();
				$new_feed->CompletedProcessingDate = $feed->getProcessingEndTime();
				$new_feed->FeedDocumentId           = $feed->getResultFeedDocumentId();
				// $new_feed->results                 = maybe_serialize( $feed );

				// save new record
				$new_feed->update();

			} else {

				// add new record
				$new_feed = new WPLA_AmazonFeed();
				$new_feed->FeedSubmissionId        = $feed->getFeedId();
				$new_feed->FeedType                = $feed->getFeedType();
				$new_feed->FeedProcessingStatus    = $feed->getProcessingStatus();
				$new_feed->SubmittedDate           = $feed->getProcessingStartTime();
				$new_feed->CompletedProcessingDate = $feed->getProcessingEndTime();
				$new_feed->date_created            = $feed->getCreatedTime();
				$new_feed->account_id              = $account->id;
                $new_feed->FeedDocumentId          = $feed->getResultFeedDocumentId();
				// $new_feed->results                 = maybe_serialize( $feed );

				// save new record
				$new_feed->add();
			}

			if ( ! $new_feed->results ) {
				$new_feed->loadSubmissionResult();
				$new_feed->processSubmissionResult();				
			}

			// check if feed is in progress
			if ( in_array( $feed->getProcessingStatus(), array('IN_QUEUE','IN_PROGRESS') ) ) {
				$feeds_in_progress++;
			}			

		}

		// // update feed progress status
		// update_option( 'wpla_feeds_in_progress', $feeds_in_progress );

		return $feeds_in_progress;
	} // static processFeedsSubmissionList()


	static function updatePendingFeeds() {
		WPLA()->logger->info('updatePendingFeeds()');

		$accounts = WPLA_AmazonAccount::getAll();
		// WPLA()->logger->info('found accounts: '.print_r($accounts,1));

		foreach ($accounts as $account ) {
			self::updatePendingFeedForAccount( $account );
		}


		if ( get_option( 'wpla_autosubmit_inventory_feeds', 0 ) ) {
		    foreach ( $accounts as $account ) {
                self::submitInventoryFeedsForAccount( $account );
            }
        }

	} // updatePendingFeeds()


	static function updatePendingFeedForAccount( $account ) {
		WPLA()->logger->info('updatePendingFeedForAccount('.$account->id.') - '.$account->title);
		WPLA()->logger->info('------------------------------');
		$lm = new WPLA_ListingsModel();

		// build feed(s) for updated (changed,prepared,matched) products
		WPLA()->logger->start('getGroupedPendingProductsForAccount');
		$grouped_items = $lm->getPendingProductsForAccount_GroupedByTemplateType( $account->id );
	   	WPLA()->logger->logTime('getGroupedPendingProductsForAccount');
		WPLA()->logger->info('found '.sizeof($grouped_items).' different templates to process...');
		// WPLA()->logger->info('grouped items: '.print_r($grouped_items,1));
		// echo "<pre>";print_r($grouped_items);echo"</pre>";#die();

		// each template
		$processed_tpl_types = array();
		foreach ( $grouped_items as $tpl_id => $grouped_inner_items ) {

			// get template
			$template      = $tpl_id ? new WPLA_AmazonFeedTemplate( $tpl_id ) : false;
			$template_type = $template ? $template->name : 'LiLo';

			// each profile
			foreach ( $grouped_inner_items as $profile_id => $items ) {

				WPLA()->logger->info('building listing items feed for profile_id: '.$profile_id);
				WPLA()->logger->info('TemplateType: '.$template_type.' - tpl_id: '.$tpl_id);
				WPLA()->logger->info('number of items: '.sizeof($items));

				// get profile
				$profile  = new WPLA_AmazonProfile( $profile_id );

				// Since most of the templates will now have the fptcustom template type, compare using
                // the template's name AND title instead to see if we should append to an existing feed #30382 #30551
                if ( $template ) {
                    $tpl_title   = $template->name . '-'. $template->title;
                    $append_feed = in_array( $tpl_title, $processed_tpl_types );
                } else {
                    // append if a feed with the same template type has been generated just now
                    $tpl_title   = $template_type;
                    $append_feed = in_array( $tpl_title, $processed_tpl_types );
                }



				// adjust feed type for Inventory Loader
				$feed_type = 'POST_FLAT_FILE_LISTINGS_DATA';
				// if ( $template_type == 'InventoryLoader' ) {
				// 	$feed_type = '_POST_FLAT_FILE_INVLOADER_DATA_';
				// }

				// build Listing Data or ListingLoader feed
				WPLA()->logger->start('buildFeed');
				$success = WPLA_AmazonFeed::buildFeed( $feed_type, $items, $account, $profile, $append_feed, $template_type );
			   	WPLA()->logger->logTime('buildFeed');
				
				// if a feed was created, add template type to list of processed templates
				//if ( $success ) $processed_tpl_types[] = $template_type;
				if ( $success ) $processed_tpl_types[] = $tpl_title;

			}

			// WPLA()->logger->logSpentTime('parseProductColumn');
			// WPLA()->logger->logSpentTime('parseProfileShortcode');
			// WPLA()->logger->logSpentTime('parseVariationAttributeColumn');
			// WPLA()->logger->logSpentTime('processAttributeShortcodes');
			// WPLA()->logger->logSpentTime('processCustomMetaShortcodes');

		} // foreach $grouped_items


		// build Price and Quantity feed for this account
		$items = $lm->getAllProductsForAccountByPnqStatus( $account->id, 1 );
		WPLA()->logger->info('number of PNQ items: '.sizeof($items));
		WPLA_AmazonFeed::buildFeed( 'POST_FLAT_FILE_PRICEANDQUANTITYONLY_UPDATE_DATA', $items, $account );


		// build delete products feed for this account
		$items = $lm->getAllProductsInTrashForAccount( $account->id );
		WPLA()->logger->info('listings in trash: '.sizeof($items));
		WPLA_AmazonFeed::buildFeed( 'POST_FLAT_FILE_INVLOADER_DATA', $items, $account, false, false, 'ProductRemoval' );


	} // updatePendingFeedForAccount()

    static function submitInventoryFeedsForAccount( $account ) {
	    WPLA()->logger->info( 'submitInventoryFeedsForAccount #'. $account->id );
	    $inventory_feed_types = array( 'POST_FLAT_FILE_INVLOADER_DATA', 'POST_FLAT_FILE_PRICEANDQUANTITYONLY_UPDATE_DATA' );
	    $feeds = self::getAllPendingFeedsForAccount( $account->id );

	    if ( $feeds ) {
            foreach ( $feeds as $feed ) {
                if ( in_array( $feed->FeedType, $inventory_feed_types ) ) {
                    WPLA()->logger->info( 'auto-submitting inventory feed #'. $feed->id );
                    $feed->submit();
                }
            }
        }
    }


	// build feed for updated products
	static function buildFeed( $feed_type, $items, $account, $profile = false, $append_feed = false, $template_type = false ) {
		WPLA()->logger->info('buildFeed() '.$feed_type.' - account id: '.$account->id);
		WPLA()->logger->info('items count: '.sizeof($items));
		// WPLA()->logger->info('items: '.print_r($items,1));

        // run 3rd-party code prior to building feeds (added for #17160)
        do_action( 'wpla_build_feed', $feed_type, $items, $account );

        /**
         * Defer this check and filtering to make sure all invalid listings (e.g. No SKUs) get removed first before limiting the row count
         * @since 2.5.1
         */
		// limit feed size to prevent timeout
//		$max_feed_size = get_option( 'wpla_max_feed_size', 1000 );
//		if ( sizeof($items) > $max_feed_size ) {
//			$items = array_slice( $items, 0, $max_feed_size );
//		}

		// adjust feed type for Inventory Loader
		if ( $template_type == 'InventoryLoader' ) {
			$feed_type = 'POST_FLAT_FILE_INVLOADER_DATA';
		}
		if ( $template_type == 'ProductRemoval' ) {
			$feed_type = 'POST_FLAT_FILE_INVLOADER_DATA__PRODUCT_REMOVAL';
		}

		// added for #42312
		$feed_type = apply_filters( 'wpla_build_feed_type', $feed_type, $items, $account, $profile );
	
		// generate CSV data
		switch ( $feed_type ) {
			case 'POST_FLAT_FILE_PRICEANDQUANTITYONLY_UPDATE_DATA':
				# price and quantity feed
				WPLA()->logger->info('building price and quantity feed...');			
				WPLA()->logger->start('buildPriceAndQuantityFeedData');
				$csv_object = WPLA_FeedDataBuilder::buildPriceAndQuantityFeedData( $items, $account->id );
			   	WPLA()->logger->logTime('buildPriceAndQuantityFeedData');
				break;
			
			case 'POST_FLAT_FILE_LISTINGS_DATA':
				# new products feed
				WPLA()->logger->info('building new products feed...');			
				WPLA()->logger->start('buildNewProductsFeedData');
				$csv_object = WPLA_FeedDataBuilder::buildNewProductsFeedData( $items, $account->id, $profile, $append_feed );
			   	WPLA()->logger->logTime('buildNewProductsFeedData');
				break;
			
			case 'POST_FLAT_FILE_INVLOADER_DATA':
				// regular Inventory Loader feed
				WPLA()->logger->info('building Inventory Loader feed...');			
				WPLA()->logger->start('buildInventoryLoaderFeedData');
				$csv_object = WPLA_FeedDataBuilder::buildInventoryLoaderFeedData( $items, $account->id, $profile, $append_feed );
			   	WPLA()->logger->logTime('buildInventoryLoaderFeedData');
				break;
			
			case 'POST_FLAT_FILE_INVLOADER_DATA__PRODUCT_REMOVAL':
				// delete products feed (Inventory Loader)
				WPLA()->logger->info('building Product Removal feed...');			
				WPLA()->logger->start('buildProductRemovalFeedData');
				$csv_object = WPLA_FeedDataBuilder::buildProductRemovalFeedData( $items, $account->id );
			   	WPLA()->logger->logTime('buildProductRemovalFeedData');
   				$feed_type = 'POST_FLAT_FILE_INVLOADER_DATA';
				break;

			default:
				# default
				WPLA()->logger->error('unsupported feed type '.$feed_type);
				$csv_object = false;
				break;
		}

		if ( ! $csv_object || empty( $csv_object->data ) ) {
			WPLA()->logger->warn('no feed data - not creating feed');
			return false;
		}
		// WPLA()->logger->info('CSV: '.$csv_object->data);

		// // extract TemplateType from listing data feed
		// $template_name = '';
		// if ( preg_match('/TemplateType=(.*)\t/U', $csv_object->data, $matches) ) {
		// 	$template_name = $matches[1];
		// 	WPLA()->logger->info('TemplateType: '.$template_name);
		// }

		// get template name/type
		$template_name = $template_type;
		WPLA()->logger->info('TemplateType: '.$template_name);

		// get template name / type from CSV object (no longer required?)
		if ( 'POST_FLAT_FILE_LISTINGS_DATA' == $feed_type ) {
			$template_name = $csv_object->template_type;
			WPLA()->logger->info('TemplateType: '.$template_name);
		}
		// if ( '_POST_FLAT_FILE_INVLOADER_DATA_' == $feed_type ) {
		// 	$template_name = $template_type;
		// }

		// set feed properties (required since $this is recycled here...)
		$new_feed = new WPLA_AmazonFeed();
		$new_feed->data                 = $csv_object->data;
		$new_feed->line_count           = $csv_object->line_count;
		$new_feed->FeedType             = $feed_type;
		$new_feed->template_name        = $template_name;
		$new_feed->FeedProcessingStatus = 'pending';
		$new_feed->status               = self::STATUS_PENDING;
		$new_feed->account_id           = $account->id;
		$new_feed->date_created         = gmdate('Y-m-d H:i:s');

		// check if a pending feed of this type already exists
		$existing_feed_id = self::getPendingFeedId( $feed_type, $template_name, $account->id );
		// echo "<pre>template name: ";print_r($template_name);echo"</pre>";
		// echo "<pre>existing feed: ";print_r($existing_feed_id);echo"</pre>";

		if ( $existing_feed_id && $append_feed ) {

			// update existing feed (append)
			$existing_feed           = self::getFeed( $existing_feed_id );
			$new_feed->data          = $existing_feed->data ."\n" . $csv_object->data;
			$new_feed->id            = $existing_feed_id;
			$new_feed->template_name = $existing_feed->template_name;
			$new_feed->line_count   += $existing_feed->line_count;
			$new_feed->update();
			WPLA()->logger->info('appended content to existing feed '.$new_feed->id);			

		} elseif ( $existing_feed_id && ! $append_feed ) {

			// update existing feed (replace)
			$new_feed->id = $existing_feed_id;
			$new_feed->update();
			WPLA()->logger->info('updated existing feed '.$new_feed->id);			

		} else {

			// add new feed
			$new_feed->id = null;
			$new_feed->add();
			WPLA()->logger->info('added NEW feed - id '.$new_feed->id);

		}

		WPLA()->logger->info('feed was built - '.$new_feed->id);	
		WPLA()->logger->info('------');

		return true;
	} // buildFeed()

    /**
     * @param WC_Order $order
     * @param string $invoice_file_path
     * @param string $invoice_id
     * @param int $account_id
     *
     * @return void
     */
    static function buildInvoiceUploadFeed( $order, $invoice_file_path, $invoice_id, $account_id ) {
        WPLA()->logger->info( 'buildInvoiceUploadFeed for '. $order->get_id() );

        $amazon_order_id = $order->get_meta( '_wpla_amazon_order_id', true );

        if ( ! $amazon_order_id ) return;

        // Get the marketplace ID of the site where the order was placed
        $marketplaceIdList = null;

        $om = new WPLA_OrdersModel();
        $amazon_order = $om->getOrderByOrderID( $amazon_order_id );
        $details =  json_decode($amazon_order->details);

        if ( $details && isset( $details->MarketplaceId ) ) {
            $marketplaceIdList = serialize( array( $details->MarketplaceId ) );
        }

        // build the FeedOptions
//        $options = 'metadata:OrderId='. $amazon_order_id .';';
//        $options .= 'metadata:TotalAmount='. $order->get_total() .';';
//        $options .= 'metadata:TotalVATAmount='. $order->get_total_tax() .';';
//        $options .= 'metadata:InvoiceNumber='. $invoice_id;

        // Testing if SP-API wants the metadata keys to be lowercased
        $options = 'metadata:orderid='. $amazon_order_id .';';
        $options .= 'metadata:totalamount='. $order->get_total() .';';
        $options .= 'metadata:totalvatamount='. $order->get_total_tax() .';';
        $options .= 'metadata:invoicenumber='. $invoice_id;

        $pdf = ( file_get_contents( $invoice_file_path ) );

        if ( get_option('wpla_log_level') >= 7 ) {
            $uploads = wp_upload_dir();
            $uploaddir = $uploads['basedir'];
            $logdir = $uploaddir . '/wp-lister';

            @copy( $invoice_file_path, $logdir .'/' . $amazon_order_id .'.pdf' );
        }


        // set feed properties (required since $this is recycled here...)
        $new_feed = new WPLA_AmazonFeed();
        $new_feed->data                 = $pdf;
        $new_feed->line_count           = 1;
        $new_feed->FeedType             = 'UPLOAD_VAT_INVOICE';
        $new_feed->feedOptions          = $options;
        $new_feed->template_name        = 'Invoice Upload';
        $new_feed->FeedProcessingStatus = 'pending';
        $new_feed->status               = self::STATUS_PENDING;
        $new_feed->account_id           = $account_id;
        $new_feed->date_created         = gmdate('Y-m-d H:i:s');
        $new_feed->MarketplaceIdList    = $marketplaceIdList;


        // add new feed
        $new_feed->id = null;
        $new_feed->add();
        WPLA()->logger->info('added NEW feed - id '.$new_feed->id);


        WPLA()->logger->info('feed was built - '.$new_feed->id);
        WPLA()->logger->info('------');

        return true;
    }

    /**
     * Build feed for shipped orders
     *
     * Since this process reads a row from the amazon_feeds table and appends a new shipment row, it is important
     * that a table or row lock is in place to prevent race conditions from occurring. Otherwise, only the last appended
     * shipment feed will be saved which causes missing rows in the shipment feed.
     *
     * @param $post_id int Internal WooCommerce Order ID
     */
	function updateShipmentFeed( $post_id ) {
	    global $wpdb;
	    $use_feed_items_table = get_option( 'wpla_feed_items_table', 0 );
	    $wc_order = wc_get_order( $post_id );

		$feed_type = 'POST_FLAT_FILE_FULFILLMENT_DATA';
		$order_id  = $wc_order->get_meta( '_wpla_amazon_order_id', true );

		$om        = new WPLA_OrdersModel();
		$order     = $om->getOrderByOrderID( $order_id );

		$account   = new WPLA_AmazonAccount( $order->account_id );
		// echo "<pre>";print_r($account);echo"</pre>";die();

		WPLA()->logger->info('updateShipmentFeed() '.$feed_type.' - order id: '.$order_id);
		WPLA()->logger->info('updateShipmentFeed() - post id: '.$post_id.' - account id: '.$account->id);

        $csv = '';
		// create pending feed if it doesn't exist
		if ( ! $this->id = self::getPendingFeedId( $feed_type, null, $account->id ) ) {

		    if ( !$use_feed_items_table ) {
                # build feed data
                WPLA()->logger->info('building shipment data feed...');
                $csv = WPLA_FeedDataBuilder::buildShippingFeedData( $post_id, $order_id, $account->id, true );

                if ( ! $csv ) {
                    WPLA()->logger->warn('no feed data - not creating feed');
                    return;
                }
            }

			// add new feed
			$this->FeedType      = $feed_type;
			$this->status        = self::STATUS_PENDING;
			$this->account_id    = $account->id;
			$this->date_created  = gmdate('Y-m-d H:i:s');
			$this->data          = $csv;
            $this->line_count           = substr_count( $this->data, "\n" );
            $this->FeedProcessingStatus = 'pending';
			$this->add();

            if ( $use_feed_items_table ) {
                WPLA()->logger->info('adding shipment data feed to feed #'. $this->id );
                WPLA_FeedDataBuilder::addShippingFeedData( $this->id, $post_id, $order_id, $account->id );
                WPLA()->logger->info('added NEW feed - id '.$this->id);
            }

		} else {
//            if ( apply_filters( 'wpla_lock_feeds_table', false ) ) {
//                //$wpdb->query("LOCK TABLES {$wpdb->prefix}amazon_feeds WRITE");
//                WPLA()->logger->info( 'amazon_feeds table transaction started' );
//                $wpdb->query( 'START TRANSACTION' );
//            }

			WPLA()->logger->info('found existing feed '.$this->id);			
			$existing_feed = new WPLA_AmazonFeed( $this->id );

			if ( $use_feed_items_table ) {
                # append feed data
                WPLA()->logger->info('addShippingFeedData');
                WPLA_FeedDataBuilder::addShippingFeedData( $this->id, $post_id, $order_id, $account->id );
            } else {
                $csv = WPLA_FeedDataBuilder::buildShippingFeedData( $post_id, $order_id, $account->id, false );
                WPLA()->logger->info('New feed data to add: '. $csv );
			    $this->data          = $existing_feed->data . $csv;
                $this->line_count    = substr_count( $this->data, "\n" );
                WPLA()->logger->info('Merged with the existing feed: '. $this->data);
            }

            // update feed
//            $this->line_count           = substr_count( $this->data, "\n" );
            $this->FeedProcessingStatus = 'pending';
            $this->date_created         = gmdate('Y-m-d H:i:s');
            $this->update();
            WPLA()->logger->info('feed was built and updated - '.$this->id);
            //WPLA()->logger->info('Row cound: '.$this->line_count);

//            if ( apply_filters( 'wpla_lock_feeds_table', false ) ) {
//                //$wpdb->query( "UNLOCK TABLES" );
//                $wpdb->query( 'COMMIT' );
//                WPLA()->logger->info( 'Query committed' );
//            }

        }



		// add history record
		$shipping_date   = $wc_order->get_meta( '_wpla_date_shipped', true );
        $history_message = 'Added to Order Fulfillment feed #' . $this->id . ' - shipment date: ' . $shipping_date;
		$history_details = array( 'post_id' => $post_id, 'shipping_date' => $shipping_date, 'feed_id' => $this->id, 'user_id' => get_current_user_id() );
		WPLA_OrdersImporter::addHistory( $order_id, 'marked_as_shipped', $history_message, $history_details );
	} // updateShipmentFeed()




	// build feed for shipping WooCommerce orders via FBA - $order_post_id refers to the internal WooCommerce order ID
	function updateFbaSubmissionFeed( $order_post_id ) {

		// get order and items
		$_order      = wc_get_order( $order_post_id );
		$order_items = $_order->get_items();
		WPLA()->logger->info('updateFbaSubmissionFeed() - no. of items: '.count($order_items) );

		foreach ( $order_items as $order_item ) {
			$this->processFbaSubmissionOrderItem( $order_item, $_order );
		}

	} // updateFbaSubmissionFeed()

	function processFbaSubmissionOrderItem( $order_item, $_order ) {

		// Flat File FBA Shipment Injection Fulfillment Feed
		$feed_type = 'POST_FLAT_FILE_FULFILLMENT_ORDER_REQUEST_DATA';

		// use account from first order item (for now)
		$lm = new WPLA_ListingsModel();
		$post_id    = $order_item['variation_id'] ? $order_item['variation_id'] : $order_item['product_id'];

		$listings   = $lm->getAllItemsByPostID( $post_id );

        // For products linked to multiple listings, attempt to use the one linked to the default account when processing FBA submissions #24051
		if ( count( $listings ) == 1 ) {
		    $listing = array_shift( $listings );
        } else {
		    $listing = false;
		    $default_account_id = get_option( 'wpla_default_account_id' );

		    foreach ( $listings as $item ) {
		        if ( $item->account_id == $default_account_id ) {
		            $listing = $item;
		            break;
                }
            }

            // If a listing for the defaul account doesn't exist, use the first one it finds
            if ( !$listing ) {
		        $listing = array_shift( $listings );
            }
        }

		$account_id = $listing->account_id;
		$account    = new WPLA_AmazonAccount( $account_id );

		WPLA()->logger->info('updateFbaSubmissionFeed() '.$feed_type.' - post id: '.$post_id.' - account id: '.$account->id);

		// create pending feed if it doesn't exist
		if ( ! $this->id = self::getPendingFeedId( $feed_type, null, $account->id ) ) {

			# build feed data
			WPLA()->logger->info('building FBA submission feed...');			
			$csv = WPLA_FeedDataBuilder::buildFbaSubmissionFeedData( $post_id, $_order, $order_item, $listing, $account->id, true );

			if ( ! $csv ) {
				WPLA()->logger->warn('no feed data - not creating feed');
				return;
			}

			// add new feed
			$this->FeedType      = $feed_type;
			$this->status        = self::STATUS_PENDING;
			$this->account_id    = $account->id;
			$this->date_created  = gmdate('Y-m-d H:i:s');
			$this->data          = $csv;
			$this->add();
			WPLA()->logger->info('added NEW feed - id '.$this->id);

		} else {
			WPLA()->logger->info('found existing feed '.$this->id);			
			$existing_feed = new WPLA_AmazonFeed( $this->id );

			# append feed data
			WPLA()->logger->info('updating FBA submission feed...');			
			$csv = WPLA_FeedDataBuilder::buildFbaSubmissionFeedData( $post_id, $_order, $order_item, $listing, $account->id, false );
			$this->data          = $existing_feed->data . $csv ."\n";

		}

		// update feed
		$this->line_count           = substr_count( $this->data, "\n" );
		$this->FeedProcessingStatus = 'pending';
		$this->date_created         = gmdate('Y-m-d H:i:s');
		$this->update();
		WPLA()->logger->info('feed was built and updated - '.$this->id);			

	} // processFbaSubmissionOrderItem()

    /**
     * Sends an email to the WP Admin email when a feed submission result returns an error
     */
    protected function sendFeedFailureEmail() {
        // get feed permalink

        $feed_permalink = admin_url( 'admin-ajax.php?action=wpla_feed_details' ) . '&id='.$this->id.'&sig='.md5( $this->id . get_option('wpla_instance') );
        $subject = __('WP-Lister Feed Submission Failure #'. $this->id, 'wp-lister-for-amazon');
        $body = '<p>WP-Lister has detected an error in your feed submission with Feed ID #'. $this->id .'</p><ul>';

        foreach ( $this->errors as $error ) {
            $body .= '<li>'. $error['sku'] .': '. $error['error-message'] .'</li>';
        }
        $body .= '</ul>';
        $body .= '<p><a href="'. $feed_permalink .'">Click here to view the feed</a></p>';

        $mail = WC()->mailer();
        $body = $mail->wrap_message( $subject, $body );
        $mail->send( get_bloginfo('admin_email'), $subject, $body );
    }


	// add feed
	function add() {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		$data = array();
		foreach ( $this->fieldnames as $key ) {
			if ( isset( $this->$key ) ) {
				$data[ $key ] = $this->$key;
			} 
		}

		if ( sizeof( $data ) > 0 ) {
			$result = $wpdb->insert( $table, $data );
			echo $wpdb->last_error;

			$this->id = $wpdb->insert_id;
			return $this->id;		
		}

	}

	// update feed
	function update() {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		$data = array();
		foreach ( $this->fieldnames as $key ) {
			if ( isset( $this->$key ) ) {
				$data[ $key ] = $this->$key;
			} 
		}

		if ( sizeof( $data ) > 0 ) {
			$result = $wpdb->update( $table, $data, array( 'id' => $this->id ) );
			WPLA()->logger->info( 'update error: '. $wpdb->last_error );
			echo $wpdb->last_error;
			// echo "<pre>";print_r($wpdb->last_query);echo"</pre>";#die();

			// return $wpdb->insert_id;		
		}

	}


	function delete() {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		if ( ! $this->id ) return;

		$wpdb->delete( $table, array( 'id' => $this->id ), array( '%d' ) );
		echo $wpdb->last_error;
	}


	function getRecordTypeName( $type ) {
		if ( isset( $this->types[$type] ) ) {
			return $this->types[$type];
		}
		return $type;
	}



	function getPageItems( $current_page, $per_page ) {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		$orderby  = (!empty($_REQUEST['orderby'])) ? esc_sql( wpla_clean($_REQUEST['orderby']) ) : 'date_created DESC, SubmittedDate'; //If no sort, default to title
		$order    = (!empty($_REQUEST['order']))   ? esc_sql( wpla_clean($_REQUEST['order'])   ) : 'desc'; //If no order, default to asc
		$offset   = ( $current_page - 1 ) * $per_page;
		$per_page = esc_sql( $per_page );

        // handle filters
        $where_sql = ' WHERE 1 = 1 ';

        // views
        if ( isset( $_REQUEST['feed_status'] ) ) {
            $status = esc_sql( wpla_clean($_REQUEST['feed_status']) );
            // if ( in_array( $status, array('Success','Error','pending','unknown') ) ) {
            if ( $status ) {
                if ( $status == 'unknown' ) {
                    $where_sql .= " AND status IS NULL ";
                } else {
                    $where_sql .= " AND status = '$status' ";
                }
            }
        }

        // filter account_id
		$account_id = ( isset($_REQUEST['account_id']) ? esc_sql( wpla_clean($_REQUEST['account_id']) ) : false);
		if ( $account_id ) {
			$where_sql .= "
				 AND account_id = '".$account_id."'
			";
		} 

        // search box
        if ( isset( $_REQUEST['s'] ) ) {
            $query = esc_sql( wpla_clean($_REQUEST['s']) );
            $where_sql .= " AND ( 
                                    ( id = '$query' ) OR
                                    ( FeedSubmissionId = '$query' ) OR 
                                    ( FeedType = '$query' ) OR
                                    ( data LIKE '%$query%' ) OR
                                    ( results LIKE '%$query%' ) OR
                                    ( FeedProcessingStatus LIKE '%$query%' ) OR
                                    ( success LIKE '%$query%' ) 
                                )
                            /* AND NOT amazon_id = 0 */
                            ";
        }

        // get items
		$items = $wpdb->get_results("
			SELECT id
			FROM $table
            $where_sql
			ORDER BY $orderby $order
            LIMIT $offset, $per_page
		", ARRAY_A);

		// get total items count - if needed
		if ( ( $current_page == 1 ) && ( count( $items ) < $per_page ) ) {
			$this->total_items = count( $items );
		} else {
			$this->total_items = $wpdb->get_var("
				SELECT COUNT(*)
				FROM $table
	            $where_sql
				ORDER BY $orderby $order
			");			
		}

		$results = [];
		foreach( $items as $item ) {
		    $row = self::getFeed( $item['id'] );
			$row->FeedTypeName = $this->getRecordTypeName( $row->FeedType );
			$results[] = (array)$row;
		}

		return $results;
	} // getPageItems()

	static function getStatusSummary() {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		$result = $wpdb->get_results("
			SELECT status, count(*) as total
			FROM $table
			GROUP BY status
		");

		$summary = new stdClass();
		foreach ($result as $row) {
            $status = $row->status ? $row->status : 'unknown';
			$summary->$status = $row->total;
		}

		// count total items as well
		$total_items = $wpdb->get_var("
			SELECT COUNT( id ) AS total_items
			FROM $table
		");
		$summary->total_items = $total_items;

		return $summary;
	} // getStatusSummary()


} // WPLA_AmazonFeed()


