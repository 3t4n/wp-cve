<?php
/**
 * WPLA_AmazonReport class
 *
 */

// class WPLA_AmazonReport extends WPLA_NewModel {
class WPLA_AmazonReport {

	const TABLENAME = 'amazon_reports';

	var $id;
	var $data;

    /**
     * Gets a FeedType from \SellingPartner\FeedType
     *
     * @param string $feed_type See WPLA_AmazonFeed::init() for the different types
     * @param string $fallback_content_type The content type value to return if the feed type isn't found
     * @return array
     */
    public static function getReportType( $report_type, $fallback_content_type = \SellingPartnerApi\ContentType::JSON ) {
        // remove the underscore from the prefix and suffix
        $report_type = trim( $report_type, '_' );

        if ( defined( '\SellingPartnerApi\ReportType::' . $report_type ) ) {
            return constant( '\SellingPartnerApi\ReportType::'. $report_type );
        }

        // feed_type not found.
        return [
            'name'          => $report_type,
            'contentType'   => $fallback_content_type,
            'restricted'    => false
        ];
    }

	function __construct( $id = null ) {

		$this->init();

		if ( $id ) {
			$this->id = $id;

			// load data into object
			$report = $this->getReport( $id );
			foreach( $report AS $key => $value ){
			    $this->$key = $value;
			}

			return $this;
		}

	}

	function init()	{

		$this->types_mws = array(
			'_GET_FLAT_FILE_OPEN_LISTINGS_DATA_'           => 'Open Listings Report',
			'_GET_MERCHANT_LISTINGS_DATA_'                 => 'Merchant Listings Report',
			'_GET_MERCHANT_LISTINGS_DATA_LITE_'            => 'Merchant Listings Lite Report',
			'_GET_MERCHANT_LISTINGS_DATA_LITER_'           => 'Merchant Listings Liter Report',
			'_GET_CONVERGED_FLAT_FILE_SOLD_LISTINGS_DATA_' => 'Sold Listings Report',
			'_GET_MERCHANT_CANCELLED_LISTINGS_DATA_'       => 'Cancelled Listings Report',
			'_GET_MERCHANT_LISTINGS_DEFECT_DATA_'          => 'Listing Quality and Suppressed Listing Report',

			'_GET_AFN_INVENTORY_DATA_'          		   => 'FBA Amazon Fulfilled Inventory Report',
			'_GET_FBA_MYI_UNSUPPRESSED_INVENTORY_DATA_'    => 'FBA Amazon Manage Inventory Report',
			'_GET_AFN_INVENTORY_DATA_BY_COUNTRY_'          => 'FBA Multi-Country Inventory Report',
			'_GET_AMAZON_FULFILLED_SHIPMENTS_DATA_'        => 'FBA Amazon Fulfilled Shipments Report',
			'_GET_FBA_MYI_UNSUPPRESSED_INVENTORY_DATA_'    => 'FBA Manage Inventory Report',
			'_GET_FBA_FULFILLMENT_INVENTORY_HEALTH_DATA_'  => 'FBA Inventory Health Report',

			'_GET_V2_SETTLEMENT_REPORT_DATA_'              => 'V2 Settlement Report (XML)',
			'_GET_V2_SETTLEMENT_REPORT_DATA_FLAT_FILE_'    => 'Flat File Settlement Report (v2)',
			'_GET_FLAT_FILE_PAYMENT_SETTLEMENT_DATA_'      => 'Flat File Settlement Report',
			'_GET_ORDERS_DATA_'                            => 'XML Order Report',
			'_GET_FLAT_FILE_ORDERS_DATA_'                  => 'Flat File Order Report',
			'_GET_XML_BROWSE_TREE_DATA_'                   => 'Browse Tree Report',
		);

        $this->types = array(
            'GET_FLAT_FILE_OPEN_LISTINGS_DATA'           => 'Open Listings Report',
            'GET_MERCHANT_LISTINGS_DATA'                 => 'Merchant Listings Report',
            'GET_MERCHANT_LISTINGS_DATA_LITE'            => 'Merchant Listings Lite Report',
            'GET_MERCHANT_LISTINGS_DATA_LITER'           => 'Merchant Listings Liter Report',
            'GET_CONVERGED_FLAT_FILE_SOLD_LISTINGS_DATA' => 'Sold Listings Report',
            'GET_MERCHANT_CANCELLED_LISTINGS_DATA'       => 'Cancelled Listings Report',
            'GET_MERCHANT_LISTINGS_DEFECT_DATA'          => 'Listing Quality and Suppressed Listing Report',

            'GET_AFN_INVENTORY_DATA'          		   => 'FBA Amazon Fulfilled Inventory Report',
            'GET_FBA_MYI_UNSUPPRESSED_INVENTORY_DATA'    => 'FBA Amazon Manage Inventory Report',
            'GET_AFN_INVENTORY_DATA_BY_COUNTRY'          => 'FBA Multi-Country Inventory Report',
            'GET_AMAZON_FULFILLED_SHIPMENTS_DATA_GENERAL'        => 'FBA Amazon Fulfilled Shipments Report',
            'GET_FBA_MYI_UNSUPPRESSED_INVENTORY_DATA'    => 'FBA Manage Inventory Report',
            'GET_FBA_FULFILLMENT_INVENTORY_HEALTH_DATA'  => 'FBA Inventory Health Report',

            'GET_V2_SETTLEMENT_REPORT_DATA'              => 'V2 Settlement Report (XML)',
            'GET_V2_SETTLEMENT_REPORT_DATA_FLAT_FILE'    => 'Flat File Settlement Report (v2)',
            'GET_FLAT_FILE_PAYMENT_SETTLEMENT_DATA'      => 'Flat File Settlement Report',
            'GET_ORDERS_DATA'                            => 'XML Order Report',
            'GET_FLAT_FILE_ORDER_REPORT_DATA_INVOICING'  => 'Flat File Order Report (Invoicing)',
            'GET_FLAT_FILE_ORDERS_DATA'                  => 'Flat File Order Report',
            'GET_FLAT_FILE_PENDING_ORDERS_DATA'          => 'Flat File Pending Order Report',
            'GET_XML_BROWSE_TREE_DATA'                   => 'Browse Tree Report',
        );

	}

	/*
		// TODO:
		// for some reason, Amazon uses different names on sellercentral than the API does:
		report type 								report label
		-----------									------------
		OpenListingReport							Inventory Report
		MerchantListingReport						Active Listings Report
		SSOFInventory								Amazon-fulfilled Inventory Report
		MerchantListingReportBackwardsCompatible	Open Listings Report
		MerchantListingReportLite					Open Listings Report Lite
		MerchantListingReportLiter					Open Listings Report Liter
		MerchantCancelledListingReport				Cancelled Listings Report
		MerchantListingReportSold					Sold Listings Report
		ListingQualityReport						Listing Quality and Suppressed Listings Report
	*/

	// get single report
	function getReport( $id )	{
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		$item = $wpdb->get_row( $wpdb->prepare("
			SELECT *
			FROM $table
			WHERE id = %d
		", $id
		), OBJECT);

		return $item;
	}

	// get single report by ReportRequestId
	static function getReportByRequestId( $ReportRequestId )	{
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		$item = $wpdb->get_row( $wpdb->prepare("
			SELECT *
			FROM $table
			WHERE ReportRequestId = %s
		", $ReportRequestId
		), OBJECT);

		return $item;
	}

	// get all reports
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

	// get all submitted reports that need to be checked for completion
	static function getSubmittedReportsForAccount( $account_id ) {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		$items = $wpdb->get_results( $wpdb->prepare("
			SELECT *
			FROM $table
			WHERE account_id = %s
			  AND ReportProcessingStatus IN ( '_SUBMITTED_', '_IN_PROGRESS_', 'IN_QUEUE', 'IN_PROGRESS' )
			ORDER BY SubmittedDate DESC
		", $account_id
		), OBJECT_K);

		return $items;
	}

	// get all inventory reports within the last 24 hours
	static function getRecentInventoryReports() {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		$items = $wpdb->get_results("
			SELECT *
			FROM $table
			WHERE ReportType IN ('_GET_MERCHANT_LISTINGS_DATA_', 'GET_MERCHANT_LISTINGS_DATA' )
			  AND ReportProcessingStatus IN ( '_DONE_', 'DONE' )
			  AND CompletedDate > DATE_SUB( DATE_ADD( NOW(), INTERVAL 1 DAY ), INTERVAL 3 DAY )
			  AND NOT data IS NULL
			ORDER BY SubmittedDate DESC
		", OBJECT_K);

		return $items;
	}


	// add report
	function add() {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		$fieldnames = array(
			'ReportRequestId',
			'ReportType',
			'ReportProcessingStatus',
			'results',
			'success',
			'SubmittedDate',
			'StartedProcessingDate',
			'CompletedDate',
			'GeneratedReportId',
			'account_id',
			'data'
		);

		$data = array();
		foreach ( $fieldnames as $key ) {
			if ( isset( $this->$key ) ) {
				$data[ $key ] = $this->$key;
			}
		}

		if ( sizeof( $data ) > 0 ) {
			$result = $wpdb->insert( $table, $data );
			echo $wpdb->last_error;

			$this->id = $wpdb->insert_id;

			return $wpdb->insert_id;
		}

	}

	// update report
	function update() {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		$fieldnames = array(
			'ReportRequestId',
			'ReportType',
			'ReportProcessingStatus',
			'results',
			'success',
			'SubmittedDate',
			'StartedProcessingDate',
			'CompletedDate',
			'GeneratedReportId',
			'account_id',
			'data'
		);

		$data = array();
		foreach ( $fieldnames as $key ) {
			if ( isset( $this->$key ) ) {
				$data[ $key ] = $this->$key;
			}
		}

		// check if MySQL server has gone away and reconnect if required - WP 3.9+
		if ( method_exists( $wpdb, 'check_connection') ) $wpdb->check_connection();


		if ( sizeof( $data ) > 0 ) {
			$result = $wpdb->update( $table, $data, array( 'id' => $this->id ) );
			echo $wpdb->last_error;

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

    /**
     * @param \SellingPartnerApi\Model\ReportsV20210630\Report $report
     * @param $account
     */
    public static function processOrderReportData( SellingPartnerApi\Model\ReportsV20210630\Report $report, $account, bool $is_new_request = false, bool $inventory_sync = false ) {
        $reports_in_progress = self::countReportsInProgress( $is_new_request, $inventory_sync );
        $new_report = self::recordReport( $report, $account );

        if ( !$new_report ) {
            return;
        }

        if ( ! $new_report->data && in_array( $report->getProcessingStatus(), array('_DONE_', 'DONE') ) ) {
            $report_completed_date = strtotime($new_report->CompletedDate.' UTC');
            $one_day_ago		   = time() - 3600 * 24;
            if ( $report_completed_date > $one_day_ago ) {
                $new_report->loadFromAmazon();
                $new_report = self::getReportByRequestId( $report->getReportId() );
            }
        }

        $new_report->processOrdersDataReport( $new_report );

        // check if report is in progress
        if ( in_array( $report->getProcessingStatus(), array('_SUBMITTED_','_IN_PROGRESS_', 'IN_PROGRESS', 'IN_QUEUE') ) ) {
            $reports_in_progress++;
        }

        self::updateReportsInProgress( $reports_in_progress, $inventory_sync );
    }

    /**
     * Record and process downloaded reports using the SP-API
     *
     *
     * @param \SellingPartnerApi\Model\ReportsV20210630\Report $report
     * @param WPLA_AmazonAccount $account
     * @param bool $is_new_request
     * @param bool $inventory_sync
     */
	public static function processReport( SellingPartnerApi\Model\ReportsV20210630\Report $report, $account, bool $is_new_request = false, bool $inventory_sync = false ) {
	    $reports_in_progress = self::countReportsInProgress( $is_new_request, $inventory_sync );
        $new_report = self::recordReport( $report, $account );


        // load data for new reports automatically (not older than 24 hours)
        if ( ! $new_report->data && in_array( $report->getProcessingStatus(), array('_DONE_', 'DONE') ) ) {
            $report_completed_date = strtotime($new_report->CompletedDate.' UTC');
            $one_day_ago		   = time() - 3600 * 24;
            if ( $report_completed_date > $one_day_ago ) {
                $new_report->loadFromAmazon();

                // **This check below is prevent shipment reports from being processed automatically #49877***
                // Do not process the report if we only need to check for inventory sync
                //if ( $inventory_sync ) {
                // Clear the update reports cron
                as_unschedule_all_actions( 'wpla_update_reports', array('inventory_sync' => 1) );

                //$new_report->autoProcessNewReport();
                $ic = new WPLA_InventoryCheck( 'wpla_bg_inventory_check_queue_data' );
                $ic->checkInventorySyncFromReport( $new_report );
                //} else {
                $new_report->autoProcessNewReport();
                //}
            }
            // $new_report->loadFromAmazon();
            // $new_report->processReportData();
        }

        // check if report is in progress
        if ( in_array( $report->getProcessingStatus(), array('_SUBMITTED_','_IN_PROGRESS_', 'IN_PROGRESS', 'IN_QUEUE') ) ) {
            $reports_in_progress++;
        }



        self::updateReportsInProgress( $reports_in_progress, $inventory_sync );
    }


	static public function processReportsRequestList( $reports, $account, $is_new_request = false, $inventory_sync = false ) {

		$reports_in_progress = self::countReportsInProgress( $is_new_request, $inventory_sync );

		foreach ($reports as $report) {
            $new_report = self::recordReport( $report, $account );

            if ( !$new_report ) {
                continue;
            }

			// load data for new reports automatically (not older than 24 hours)
			if ( ! $new_report->data && in_array( $report->ReportProcessingStatus, array('_DONE_') ) ) {
				$report_completed_date = strtotime($new_report->CompletedDate.' UTC');
				$one_day_ago		   = time() - 3600 * 24;
				if ( $report_completed_date > $one_day_ago ) {
					$new_report->loadFromAmazon();

					// **This check below is prevent shipment reports from being processed automatically #49877***
					// Do not process the report if we only need to check for inventory sync
					//if ( $inventory_sync ) {
                        // Clear the update reports cron
                        as_unschedule_all_actions( 'wpla_update_reports', array('inventory_sync' => 1) );

                        //$new_report->autoProcessNewReport();
                        $ic = new WPLA_InventoryCheck( 'wpla_bg_inventory_check_queue_data' );
                        $ic->checkInventorySyncFromReport( $new_report );
                    //} else {
                        $new_report->autoProcessNewReport();
                    //}
				}
				// $new_report->loadFromAmazon();
				// $new_report->processReportData();
			}

			// check if report is in progress
			if ( in_array( $report->ReportProcessingStatus, array('_SUBMITTED_','_IN_PROGRESS_') ) ) {
				$reports_in_progress++;
			}

		}

		self::updateReportsInProgress( $reports_in_progress, $inventory_sync );

	}

	private static function recordReport( SellingPartnerApi\Model\ReportsV20210630\Report $report, $account ) {
        // check if report exists
        $existing_record = WPLA_AmazonReport::getReportByRequestId( $report->getReportId() );
        if ( $existing_record ) {

            // skip existing report if it was requested using another "account" (different marketplace using the same account)
            if ( $existing_record->account_id != $account->id ) {
                WPLA()->logger->info('skipped existing report '.$existing_record->id.' for account '.$existing_record->account_id);
                return false;
            }

            $new_report = new WPLA_AmazonReport( $existing_record->id );

            $new_report->ReportRequestId        = $report->getReportId();
            $new_report->ReportType             = $report->getReportType();
            $new_report->ReportProcessingStatus = $report->getProcessingStatus();
            $new_report->SubmittedDate          = $report->getCreatedTime();
            $new_report->StartedProcessingDate  = $report->getProcessingStartTime() ? $report->getProcessingStartTime() : '';
            $new_report->CompletedDate          = $report->getProcessingEndTime() ? $report->getProcessingEndTime() : '';
            $new_report->GeneratedReportId      = $report->getReportDocumentId() ? $report->getReportDocumentId() : '';
            // $new_report->account_id             = $account->id;
            $new_report->results                = maybe_serialize( $report );

            // save new record
            $new_report->update();

        } else {

            // add new record
            $new_report = new WPLA_AmazonReport();
            $new_report->ReportRequestId        = $report->getReportId();
            $new_report->ReportType             = $report->getReportType();
            $new_report->ReportProcessingStatus = $report->getProcessingStatus();
            $new_report->SubmittedDate          = $report->getCreatedTime();
            $new_report->StartedProcessingDate  = $report->getProcessingStartTime() ? $report->getProcessingStartTime() : '';
            $new_report->CompletedDate          = $report->getProcessingEndTime() ? $report->getProcessingEndTime() : '';
            $new_report->GeneratedReportId      = $report->getReportDocumentId() ? $report->getReportDocumentId() : '';
            $new_report->account_id             = $account->id;
            $new_report->results                = maybe_serialize( $report );


            // save new record
            $new_report->add();

        }

        return $new_report;
    }


	function get_data_rows( $query = false ) {
		if ( ! $this->id ) return array();
		if ( ! $this->data ) return array();

		$rows = WPLA_ReportProcessor::csv_to_array( $this->data, $query );
		return $rows;
	}


	function loadFromAmazon() {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		if ( ! $this->id ) return;
		if ( ! $this->GeneratedReportId ) return;

		$api = new WPLA_Amazon_SP_API( $this->account_id );
		$this->data = $api->getReportDocumentBody( $this->GeneratedReportId );

		$wpdb->update( $table, array('line_count' => substr_count( $this->data, "\n" ) ), array('id' => $this->id ) );
		$wpdb->update( $table, array('data' => $this->data), array('id' => $this->id ) );
		if ( $wpdb->last_error ) {
			wpla_show_message( '<b>There was a problem storing the report content in the database.</b><br>MySQL said: ' . $wpdb->last_error, 'error' );
		}

	}

	// automatically process selected imports when they are loaded
	function autoProcessNewReport() {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		if ( ! $this->id ) return;

		// skip empty reports
		if ( $this->ReportProcessingStatus == '_DONE_NO_DATA_' ) {
			$wpdb->update( $table, array('success' => 'empty'), array('id' => $this->id ) );
			return;
		}
		if ( $this->ReportProcessingStatus == '_CANCELLED_' || $this->ReportProcessingStatus == 'CANCELLED' ) {
			$wpdb->update( $table, array('success' => 'empty'), array('id' => $this->id ) );
			return;
		}

		// _GET_AFN_INVENTORY_DATA_
       	if ( in_array($this->ReportType, [ '_GET_AFN_INVENTORY_DATA_', 'GET_AFN_INVENTORY_DATA'] ) ) {
	        $rows = $this->get_data_rows();
			WPLA_ImportHelper::processFBAReportPage( $this, $rows, null, null );
			$wpdb->update( $table, array('success' => 'yes'), array('id' => $this->id ) );
			return;
       	}

       	// _GET_FBA_MYI_UNSUPPRESSED_INVENTORY_DATA_
       	if ( in_array( $this->ReportType, [ '_GET_FBA_MYI_UNSUPPRESSED_INVENTORY_DATA_', 'GET_FBA_MYI_UNSUPPRESSED_INVENTORY_DATA' ] ) ) {
		    $rows = $this->get_data_rows();
		    WPLA_ImportHelper::processManageFBAReportPage( $this, $rows, null, null );
		    $wpdb->update( $table, array( 'success' => 'yes' ), array( 'id' => $this->id ) );
		    return;
        }

       	// inventory report - only process if daily report option is enabled
		// _GET_MERCHANT_LISTINGS_DATA_
       	if ( in_array( $this->ReportType, ['_GET_MERCHANT_LISTINGS_DATA_', 'GET_MERCHANT_LISTINGS_DATA'] ) ) {
       		if ( get_option('wpla_autofetch_inventory_report') != 1 ) return;
	        $rows = $this->get_data_rows();
			WPLA_ImportHelper::processInventoryReportPage( $this, $rows, null, null );
			$wpdb->update( $table, array('success' => 'yes'), array('id' => $this->id ) );
			return;
       	}

       	// FBA shipping report - only process if multichannel fulfillment is enabled
		// _GET_AMAZON_FULFILLED_SHIPMENTS_DATA_
       	if ( in_array( $this->ReportType, [ '_GET_AMAZON_FULFILLED_SHIPMENTS_DATA_', 'GET_AMAZON_FULFILLED_SHIPMENTS_DATA_GENERAL' ] ) ) {
       		if ( get_option('wpla_fba_autosubmit_orders') != 1 ) return;
	        $rows = $this->get_data_rows();
			WPLA_ReportProcessor::processAmazonShipmentsReportPage( $this, $rows, null, null );
			$wpdb->update( $table, array('success' => 'yes'), array('id' => $this->id ) );
			return;
       	}

		// _GET_MERCHANT_LISTINGS_DEFECT_DATA_
       	if ( in_array( $this->ReportType, [ '_GET_MERCHANT_LISTINGS_DEFECT_DATA_', 'GET_MERCHANT_LISTINGS_DEFECT_DATA' ] ) ) {
	        $rows = $this->get_data_rows();
			WPLA_ImportHelper::processQualityReportPage( $this, $rows, null, null );
			$wpdb->update( $table, array('success' => 'yes'), array('id' => $this->id ) );
			return;
       	}

		// _GET_FBA_FULFILLMENT_INVENTORY_HEALTH_DATA_
       	if ( in_array($this->ReportType, [ '_GET_FBA_FULFILLMENT_INVENTORY_HEALTH_DATA_', 'GET_FBA_FULFILLMENT_INVENTORY_HEALTH_DATA' ] ) ) {
	        $rows = $this->get_data_rows();
			WPLA_ImportHelper::processFBAInventoryHealthReportPage( $this, $rows, null, null );
			$wpdb->update( $table, array('success' => 'yes'), array('id' => $this->id ) );
			return;
       	}

	} // autoProcessNewReport()

    function processOrdersDataReport() {
        global $wpdb;
        $table = $wpdb->prefix . self::TABLENAME;

        if ( ! $this->id ) return;


        if ( $this->ReportProcessingStatus == 'CANCELLED' ) {
            $wpdb->update( $table, array('success' => 'empty'), array('id' => $this->id ) );
            return;
        }

        // _GET_FBA_FULFILLMENT_INVENTORY_HEALTH_DATA_
        if ( in_array($this->ReportType, [ 'GET_FLAT_FILE_ORDER_REPORT_DATA_INVOICING' ] ) ) {
            $rows = $this->get_data_rows();
            WPLA_ImportHelper::processOrderDataReportPage( $this, $rows, null, null );
            $wpdb->update( $table, array('success' => 'yes'), array('id' => $this->id ) );
            return;
        }
    }


	function getPageItems( $current_page, $per_page ) {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		$orderby  = (!empty($_REQUEST['orderby'])) ? esc_sql( wpla_clean($_REQUEST['orderby']) ) : 'SubmittedDate'; //If no sort, default to title
		$order    = (!empty($_REQUEST['order']))   ? esc_sql( wpla_clean($_REQUEST['order'])   ) : 'desc'; //If no order, default to asc
		$offset   = ( $current_page - 1 ) * $per_page;
		$per_page = esc_sql( $per_page );

        // handle filters
        $where_sql = ' WHERE 1 = 1 ';

        // views
        if ( isset( $_REQUEST['report_status'] ) ) {
            $status = esc_sql( wpla_clean($_REQUEST['report_status']) );
            // if ( in_array( $status, array('Success','Error','pending','unknown') ) ) {
            if ( $status ) {
                if ( $status == 'unknown' ) {
                    $where_sql .= " AND ReportProcessingStatus IS NULL ";
                } else {
                    $where_sql .= " AND ReportProcessingStatus = '$status' ";
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
                                    ( ReportRequestId = '$query' ) OR 
                                    ( GeneratedReportId = '$query' ) OR 
                                    ( ReportType = '$query' ) OR
                                    ( data LIKE '%$query%' ) OR
                                    ( results LIKE '%$query%' ) 
                                )
                            ";
        }


        // get items
		$items = $wpdb->get_results("
			SELECT *
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

		foreach( $items as &$preport ) {
			$preport['ReportTypeName'] = $this->getRecordTypeName( $preport['ReportType'] );
		}

		return $items;
	}



	static function getStatusSummary() {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLENAME;

		$result = $wpdb->get_results("
			SELECT ReportProcessingStatus as status, count(*) as total
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


    /**
     * Sets the reports_in_progress flag in the DB to 0
     * @param bool $inventory_sync
     */
    public static function resetReportsInProgress( $inventory_sync ) {
        if ( $inventory_sync ) {
            // reset counter - will be updated in processReportsRequestList()
            update_option( 'wpla_bg_reports_in_progress', 0 );
        } else {
            // reset counter - will be updated in processReportsRequestList()
            update_option( 'wpla_reports_in_progress', 0 );
        }
    }

    /**
     *
     * @param bool $is_new_request
     * @param bool $inventory_sync Whether this request was made via the Inventory Sync tool
     * @return bool|int|mixed|void
     */
    public static function countReportsInProgress( $is_new_request, $inventory_sync ) {
        // if this is a new report request, add to reports in progress - otherwise reset it
        // TODO: count reports in progress per account
        if ( $is_new_request ) {
            $reports_in_progress = ($inventory_sync) ? get_option( 'wpla_bg_reports_in_progress', 0 ) : get_option( 'wpla_reports_in_progress', 0 );
        } else {
            $reports_in_progress = 0;
        }

        return $reports_in_progress;
    }

    public static function updateReportsInProgress( $reports_count, $inventory_sync ) {
        if ( $inventory_sync ) {
            // update report progress status
            update_option( 'wpla_bg_reports_in_progress', $reports_count );
        } else {
            // update report progress status
            update_option( 'wpla_reports_in_progress', $reports_count );
        }
    }


} // WPLA_AmazonReport()


