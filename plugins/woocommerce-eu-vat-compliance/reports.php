<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access');

// Purpose: provide a report on VAT paid, using the configured reporting currencies and exchange rates

if (!class_exists('WC_VAT_Compliance_Reports_UI')) require_once(WC_VAT_COMPLIANCE_DIR.'/includes/reports-ui.php');

// The boolean constant WC_VAT_REPORTING_LOG_QUERIES can be used to log SQL queries with error_log() (to aid debugging)

class WC_EU_VAT_Compliance_Reports extends WC_VAT_Compliance_Reports_UI {

	// Public: is used in the CSV download code
	public $reporting_currency = '';
	public $conversion_providers = array();
	protected $reporting_currencies = array();
	public $last_rate_used = 1;
	protected $fallback_conversion_rates = array();
	protected $fallback_conversion_providers = array();
	
	public $chart_groupby;
	
	protected $pre_wc22_order_parsed = array();
	
	// Public: Used in HMRC reporting
	public $format_num_decimals;

	public $start_date;
	public $end_date;
	
	private $hpos_enabled;
	
	/**
	 * Class constructor
	 */
	public function __construct() {
		if (did_action('plugins_loaded')) {
			$this->hpos_enabled = WooCommerce_EU_VAT_Compliance()->woocommerce_custom_order_tables_enabled();
		} else {
			add_action('plugins_loaded', function() {
				$this->hpos_enabled = WooCommerce_EU_VAT_Compliance()->woocommerce_custom_order_tables_enabled();
			});
		}
		parent::__construct();
	}

	/**
	 * Get the total sales to the indicated VAT region in the year-to-date (excluding sales to the base country; i.e. cross-border sales only)
	 *
	 * @param String  $vat_region_code
	 * @param Boolean $can_use_transient - set to 'false' to force updating of the transient
	 *
	 * @return Array - keyed in a similar way to self::get_tabulated_results() - the first keys are the month (numeric, starting from 1), then order status codes, then country codes, then currency codes, then tax codes, then tax details (vat, vat_shipping, sales, vat_refunded)
	 */
	public function get_year_to_date_region_totals($vat_region_code, $can_use_transient = true) {
	
		$current_time = current_time('timestamp');
	
		$end = date('Y-m-d', 86400 + $current_time);

		$transient_key = 'wc_vat_year_to_date_'.$vat_region_code;
		
		if ($can_use_transient) {
			$transient_value = get_transient($transient_key);
			if (is_array($transient_value) && $transient_value['end'] == $end && isset($transient_value['results'])) {
				return $transient_value['results'];
			}
		}
		
		$this_year = (int) date('Y', $current_time);
		$this_month = (int) date('m', $current_time);
		
		$data_by_months = array();
		$results = array();
		
		$compliance = WooCommerce_EU_VAT_Compliance();
		$region_object = $compliance->get_vat_region_object($vat_region_code);
		$region_country_codes = $region_object->get_countries();
		
		// By default, unlisted statuses are included, on the assumption that when a shop adds its own manual statuses, these are post-payment statuses.
		$skip_statuses = apply_filters('wc_vat_compliance_report_year_to_date_totals_exclude_statuses', array('pending', 'cancelled', 'refunded', 'failed', 'authentication_required'));
		
		$keys_to_add = array('vat', 'vat_shipping', 'sales', 'vat_refunded');
		
		// We go in units of months to reduce the risk of timing out due to excessive data in one go
		for ($month = 1; $month <= $this_month; $month++) {
			$month_transient_key = $transient_key.$this_year.$month;
			$month_transient_value = get_transient($month_transient_key);
			if (is_array($month_transient_value)) {
				$data_by_months[$month] = $month_transient_value;
				continue;
			}
			// No transient data found; need to fetch it
			$month_start = date('Y-'.sprintf('%02d', $month).'-01', $current_time);
			$month_end = date('Y-'.sprintf('%02d', $month).'-'.sprintf('%02d', cal_days_in_month(CAL_GREGORIAN, $month, $this_year)));
			$month_results = $this->get_tabulated_results($month_start, $month_end, 'taxation');
			
			foreach ($month_results as $status => $status_results) {
				if (in_array($status, $skip_statuses)) {
					unset($month_results[$status]);
					continue;
				}
				foreach ($status_results as $country_code => $country_results) {
					if (!in_array($country_code, $region_country_codes)) {
						unset($status_results[$country_code]);
						$month_results[$status] = $status_results;
					}
				}
			}
			
			$data_by_months[$month] = $month_results;
			// We cache for longer the further back it goes, as the further in the past, the less likely a refund or other change is. Shop owners can always trigger a full recalculation by clearing all transients.
			$transient_expiry = ($month == $this_month) ? 40000 : (($month == $this_month - 1) ? 80000 : 172800);
			set_transient($month_transient_key, $month_results, 86400);
			
			// Now, amalgamate the results into the final results (this isn't really necessary now that we key by month
			foreach ($month_results as $status => $status_results) {
				foreach ($status_results as $country_code => $country_results) {
					foreach ($country_results as $currency_code => $currency_results) {
						foreach ($currency_results as $tax_code => $tax_details) {
						
							if (!isset($results[$month][$status][$country_code][$currency_code][$tax_code])) {
								$default = array();
								foreach ($keys_to_add as $key) {
									$default[$key] = 0;
								}
								$results[$month][$status][$country_code][$currency_code][$tax_code] = $default;
							}
							
							foreach ($keys_to_add as $key) {
								if (!isset($tax_details[$key])) continue;
								$results[$month][$status][$country_code][$currency_code][$tax_code][$key] += $tax_details[$key];
							}
						}
					}
				}
			}
			
		}
		
		// Though it can be used until then, hopefully 
		set_transient($transient_key, array('end' => $end, 'results' => $results), 86400);
		
		return $results;
	
	}
	
	/**
	 * Get data on taxes paid, by tax type, on items within the specified range for the specified status
	 *
	 * @param String $start_date
	 * @param String $end_date
	 * @param String $status
	 *
	 * @return Array
	 */
	protected function get_items_data($start_date, $end_date, $status) {
		global $wpdb;

		$fetch_more = true;
		$page = 0;
		$page_size = defined('WC_VAT_COMPLIANCE_ITEMS_PAGE_SIZE') ? WC_VAT_COMPLIANCE_ITEMS_PAGE_SIZE : 2880;

		$found_items = array();
		$final_results = array();
		
		$current_order_id = false;
		$current_order_item_id = false;
		$current_total = false;
		$current_line_tax_data = false;
		$subscriptio_potential_bug_case = false;
		
		while ($fetch_more) {
			$page_start = $page_size * $page;
			$results_this_time = 0;
			$sql = $this->get_items_sql($page_start, $page_size, $start_date, $end_date, $status);
			if (empty($sql)) break;
			if (defined('WC_VAT_REPORTING_LOG_QUERIES') && WC_VAT_REPORTING_LOG_QUERIES) error_log("WC_VAT_REPORTING_LOG_QUERIES get_items_data(page_start=$page_start, page_size=$page_size): ".preg_replace('/(\t|\n|  )/', ' ', $sql));
			$results = $wpdb->get_results($sql);
			if (!empty($results)) {
				$page++;

				foreach ($results as $r) {
					// Don't check on empty($r->v) - this causes orders 100% discounted (_line_total = 0) to be detected as non-WC-2.2 orders, as $current_total then never gets off (bool)false.
					if (empty($r->ID) || empty($r->k) || empty($r->oi)) continue;

					if ($r->oi != $current_order_item_id && $current_order_item_id !== false) {
						// A new order has begun: process the previous order
						$final_results = $this->add_order_to_final_results($final_results, $current_order_id, $current_line_tax_data, $current_total, $subscriptio_potential_bug_case);
					}
					
					$current_order_id = $r->ID;
					$current_order_item_id = $r->oi;
					
					if (!isset($found_items[$current_order_id][$current_order_item_id])) {
						$current_total = false;
						$current_line_tax_data = false;
						$found_items[$current_order_id][$current_order_item_id] = true;
						$subscriptio_potential_bug_case = false;
					}

					if ('_line_total' == $r->k) {
						$current_total = $r->v;
					} elseif ('_line_tax_data' == $r->k) {
						$current_line_tax_data = maybe_unserialize($r->v);
						// Don't skip - we want to know that some data was there (detecting pre-WC2.2 orders)
// 						if (empty($current_line_tax_data['total'])) continue;
					} elseif ('_line_tax' == $r->k) {
						// Added 9-Jan-2016 - the only use of this meta key/value is to detect a problem with Subscriptio (up to at least 2.1.3). If that is ever fixed, this can be removed (and the SELECTing of this key removed from the get_items_sql() method of this class, to improve performance).
						// Subscriptio can blank this out this value in repeat orders (instead of numerical zero), or put a zero
						// Only 'potential' at this stage, because what we're detecting ultimately is a missing _line_tax_data line - which is irrelevant if there was zero tax, and not something that needs warning about. Note that this will also cause the warning to suppress for actual pre-WC-2.2 orders; which is fine, as there's no need to worry the user about something that made no difference.
						$subscriptio_potential_bug_case = empty($r->v);
					}

				}
				
			}
			
			if (empty($results) || count($results) < $page_size) {
				$fetch_more = false;
			}
			
		}
		
		if (false !== $current_order_item_id) $final_results = $this->add_order_to_final_results($final_results, $current_order_id, $current_line_tax_data, $current_total, $subscriptio_potential_bug_case);

		// Parse results further
		foreach ($found_items as $order_id => $order_items) {
			if (!isset($final_results[$order_id])) {
				$this->pre_wc22_order_parsed[] = $order_id;
			}
		}

		return $final_results;

	}

	protected function add_order_to_final_results($final_results, $current_order_id, $current_line_tax_data, $current_total, $subscriptio_potential_bug_case = false) {
	
		if (false !== $current_total && is_array($current_line_tax_data)) {
			$total = $current_line_tax_data['total'];
			if (empty($total)) {
				// Record something - it's used to confirm that all orders had data, later
				if (!isset($final_results[$current_order_id])) $final_results[$current_order_id] = array();
			} else {
				foreach ($total as $tax_rate_id => $item_amount) {
// 							if (!isset($final_results[$tax_rate_id])) $final_results[$tax_rate_id] = 0;
// 							$final_results[$tax_rate_id] += $current_total;
					// Needs to still be broken down by ID so that it can then be linked back to country
					if (!isset($final_results[$current_order_id][$tax_rate_id])) $final_results[$current_order_id][$tax_rate_id] = 0;
					$final_results[$current_order_id][$tax_rate_id] += $current_total;
				}
			}
		} elseif (false === $current_line_tax_data && !empty($subscriptio_potential_bug_case)) {
			// Set this, so that the "order from WC 2.1 or earlier (and hence no detailed tax data)" warning isn't triggered
			if (!isset($final_results[$current_order_id])) $final_results[$current_order_id] = array();
		}
	
		return $final_results;
	}
	
	// WC 2.2+ only (the _line_tax_data itemmeta only exists here)
	protected function get_items_sql($page_start, $page_size, $start_date, $end_date, $status) {

		global $wpdb;
		$table_prefix = $wpdb->prefix;

		// '_order_tax_base_currency', '_order_total_base_currency', 
// 			,item_meta.meta_key

		$order_table = $this->hpos_enabled ? $table_prefix.'wc_orders' : $wpdb->posts;
		$order_type_field = $this->hpos_enabled ? 'type' : 'post_type';
		$order_status_field = $this->hpos_enabled ? 'status' : 'post_status';
		$order_date_field = $this->hpos_enabled ? 'date_created_gmt' : 'post_date';
		
		// N.B. 2016-Jan-09: The '_line_tax' meta key was added to enable detection of zero-tax repeat orders created by Subscriptio - because Subscriptio erroneously blanks the _line_tax (instead of putting (int)0), and fails to copy the _line_tax_data array (which leads to the order being wrongly detected as a pre-WC-2.2 order)
		// The HPOS order table field name is id
		$sql = "SELECT
			orders.ID as ID
			,items.order_item_id AS oi
			,item_meta.meta_key AS k
			,item_meta.meta_value AS v
		FROM
			{$order_table} AS orders
		LEFT JOIN
			{$table_prefix}woocommerce_order_items AS items ON
				(orders.ID = items.order_id)
		LEFT JOIN
			{$table_prefix}woocommerce_order_itemmeta AS item_meta ON
				(item_meta.order_item_id = items.order_item_id)
		WHERE
			(orders.{$order_type_field} = 'shop_order')
			AND orders.{$order_status_field} = 'wc-$status'
			AND orders.{$order_date_field} >= '$start_date 00:00:00'
			AND orders.{$order_date_field} <= '$end_date 23:59:59'
			AND items.order_item_type = 'line_item'
			AND item_meta.meta_key IN('_line_tax_data', '_line_total', '_line_tax')
		ORDER BY orders.ID ASC
		LIMIT $page_start, $page_size
		";

		return apply_filters('woocommerce_vat_compliance_get_items_sql', $sql, $page_start, $page_size, $start_date, $end_date, $status);
	}

	/**
	 * Adjust times for the site timezone
	 *
	 * @param String $date1 - should be in the format YYYY-MM-DD; a start date
	 * @param String $date2 - should be in the format YYYY-MM-DD; an end date
	 *
	 * @return Array - the two adjusted times
	 */
	protected function adjust_times_for_site_timezone($date1, $date2) {
		
		// Number of hours that the site is ahead of GMT
		$gmt_offset = (float) get_option('gmt_offset');
		
		$time1 = strtotime("{$date1} 00:00:00 +0000");
		$time1 -= $gmt_offset * 3600;
		
		$time2 = strtotime("{$date2} 23:59:59 +0000");
		$time2 -= $gmt_offset * 3600;
		
		return array(date('Y-m-d H:i:s', $time1), date('Y-m-d H:i:s', $time2));
		
	}
	
	/**
	 *
	 * N.B. Supported on every WC version since 2.2 (the _line_tax_data itemmeta and order refunds feature in general began then)
	 *
	 * @param Boolean|Number - $page_start
	 * @param Boolean|Number - $page_size
	 * @param String		 - $start_date (in MySQL date format)
	 * @param String		 - $end_date (in MySQL date format)
	 * @param Boolean|String - $order_status (without the wc- prefix)
	 *
	 * @return String - the SQL statement to run
	 */
	protected function get_refunds_sql($page_start, $page_size, $start_date, $end_date, $order_status = false) {

		global $wpdb;
		$table_prefix = $wpdb->prefix;

		// , '_refunded_item_id'
		// '_order_tax_base_currency', '_order_total_base_currency', 
		// ,item_meta.meta_key
		// ,items.order_item_type AS ty

		// This does not work: refunds *always* have order status wc-completed: they do *not* reflect the order status of the parent order.
		// $status_extra = ($order_status !== false) ? "\t\t\tAND orders.post_status = 'wc-$order_status'" : '';
		$status_extra = '';

		$order_table = $this->hpos_enabled ? $table_prefix.'wc_orders' : $wpdb->posts;
		$order_type_field = $this->hpos_enabled ? 'type' : 'post_type';
		
		list($start_date_time, $end_date_time) = $this->adjust_times_for_site_timezone($start_date, $end_date);
		
		$order_date_field = $this->hpos_enabled ? 'date_created_gmt' : 'post_date_gmt'; // N.B.: No "post_date" (local time) field exists under HPOS; this now has to be converted from the GMT field by the caller
		
		$parent_id_field = $this->hpos_enabled ? 'parent_order_id' : 'post_parent';
		
		// HPOS: tested, the two queries produce identical results
		
		// N.B. The secondary sorting by oid is relied upon by the consumer
		$sql = "SELECT
			orders.{$parent_id_field} AS id
			,items.order_item_id AS oid
			,item_meta.meta_key AS k
			,item_meta.meta_value AS v
		FROM
			{$order_table} AS orders
		LEFT JOIN
			{$table_prefix}woocommerce_order_items AS items ON
				(orders.ID = items.order_id)
		LEFT JOIN
			{$table_prefix}woocommerce_order_itemmeta AS item_meta ON
				(item_meta.order_item_id = items.order_item_id)
		WHERE
			(orders.{$order_type_field} = 'shop_order_refund')
			AND orders.{$order_date_field} >= '{$start_date_time}'
			$status_extra
			AND orders.{$order_date_field} <= '{$end_date_time}'
			AND item_meta.meta_key IN('tax_amount', 'shipping_tax_amount', 'rate_id')
			AND items.order_item_type IN('tax')
			AND item_meta.meta_value != '0'
		ORDER BY
			id ASC, oid ASC, v ASC
		";

		if (false !== $page_start && false !== $page_size) $sql .= "		LIMIT $page_start, $page_size";

		return apply_filters('woocommerce_vat_compliance_get_refunds_sql', $sql, $page_start, $page_size, $start_date, $end_date, $order_status);
	}

	/**
	 * N.B. Because some items from the old postmeta table are now in the wc_orders table, the SQL below will not produce identical query results on HPOS/non-HPOS setups, so the caller must handle that; and also the fact that billing/shipping country/states will not be present in HPOS results: those must be fetched separately by the caller. tax_amount | total_amount | order_currency  will all be separate columns, found in each row of the results.
	 */
	protected function get_report_sql($page_start, $page_size, $start_date, $end_date, $tax_extra_fields) {

		global $wpdb;
		$table_prefix = $wpdb->prefix;
		
		// Redundant, unless there are other statuses; and incompatible with plugins adding other statuses: AND (term.slug IN ('completed', 'processing', 'on-hold', 'pending', 'refunded', 'cancelled', 'failed'))
		
		// _order_number_formatted is from Sequential Order Numbers Pro
		$order_table = $this->hpos_enabled ? $table_prefix.'wc_orders' : $wpdb->posts;
		$order_meta_table = $this->hpos_enabled ? $table_prefix.'wc_orders_meta' : $wpdb->postmeta;
		$order_type_field = $this->hpos_enabled ? 'type' : 'post_type';
		
		$order_date_field = $this->hpos_enabled ? 'date_created_gmt' : 'post_date_gmt'; // N.B.: No "post_date" (local time) field exists under HPOS; this now has to be converted from the GMT field
		$order_status_field = $this->hpos_enabled ? 'status' : 'post_status';
		$parent_id_field = $this->hpos_enabled ? 'parent_order_id' : 'post_parent';
		$post_id_field = $this->hpos_enabled ? 'order_id' : 'post_id';
		
		// Keys are the HPOS columns
		$hpos_column_mappings = $this->get_hpos_column_mappings(false);
		// Keys are postmeta keys
		$post_column_mappings = array_flip($hpos_column_mappings);
		
		// '_order_tax_base_currency', '_order_total_base_currency', 
		// This SQL is valid from WooCommerce 2.2 onwards
		$sql = "SELECT
			orders.ID as ID
			,orders.{$order_date_field} AS date
			,order_meta.meta_key
			,order_meta.meta_value
			,orders.{$order_status_field} AS order_status
			";
		
		if ($this->hpos_enabled) {
			$using_operational_table = false;
			$sql .= ",orders.tax_amount
			,orders.total_amount
			,orders.currency AS currency
			";
			// Special case handling of some items that under HPOS are in the "wc_order_operational_data" table
			// Not currently handled: date_paid_gmt, date_completed_gmt
			if (in_array('_order_shipping', $tax_extra_fields)) {
				$using_operational_table = true;
				$sql .= "\n,order_data.shipping_total_amount";
			}
			if (in_array('_order_shipping_tax', $tax_extra_fields)) {
				$using_operational_table = true;
				$sql .= "\n,order_data.shipping_tax_amount";
			}
			if (in_array('_cart_discount', $tax_extra_fields)) {
				$using_operational_table = true;
				$sql .= "\n,order_data.discount_total_amount";
			}
			if (in_array('_cart_discount_tax', $tax_extra_fields)) {
				$using_operational_table = true;
				$sql .= "\n,order_data.discount_tax_amount";
			}
		}
		// _addresses.country AS country
		// dresses.address_type
		// dresses.state AS state
		
		if ($this->hpos_enabled) {
			// _shipping_country is fetched/handled in the after-result HPOS conversion
			foreach ($tax_extra_fields as $extra_meta_field) {
				if (isset($post_column_mappings[$extra_meta_field])) {
					$sql .= ",orders.{$post_column_mappings[$extra_meta_field]}";
				}
			}
		}
		
		$sql .= "
		FROM
			{$order_table} AS orders
		LEFT JOIN
			{$order_meta_table} AS order_meta ON
				(order_meta.{$post_id_field} = orders.ID)
		";
		
		if ($this->hpos_enabled && $using_operational_table) {
			$sql .= "LEFT JOIN
				{$table_prefix}wc_order_operational_data AS order_data ON
				(order_data.order_id = orders.ID)
			";
		}
		
		list($start_date_time, $end_date_time) = $this->adjust_times_for_site_timezone($start_date, $end_date);
		
		$sql .= "WHERE
			(orders.{$order_type_field} = 'shop_order')
			AND orders.{$order_date_field} >= '{$start_date_time}'
			AND orders.{$order_date_field} <= '{$end_date_time}'
			AND order_meta.meta_key IN (";

		// These are not the actual column names, but post storage equivalents
		// $hpos_columns = array('_billing_state', '_billing_country', '_order_currency', '_order_tax', '_order_total', '_shipping_country', '_billing_email');
		
		if ($this->hpos_enabled) {
			// Under HPOS there is the danger that no meta entries will be found/needed for the order (on orders for which the plugin was notinstalled). To avoid this, we include a key/value pair which should always exist on all orders. i.e. Slightly ugly (we don't want the data from this field), but should always work.
			$sql .= "'_billing_address_index', ";
		} else {
			$sql .= "'_billing_state', '_billing_country', '_order_currency', '_order_tax', '_order_total', ";
		}
		
		$sql_meta_fields_fetch_extra = '';
		foreach ($tax_extra_fields as $extra_meta_field) {
			if (!$this->hpos_enabled || !isset($post_column_mappings[$extra_meta_field])) {
				// Add it to the meta key names to be fetched from the DB
				$sql_meta_fields_fetch_extra .= ", '{$extra_meta_field}'";
			}
		}
			
		$sql .= "'vat_compliance_country_info', 'vat_compliance_vat_paid', 'Valid VAT Number', 'VAT Number', 'VAT number validated', '_order_number_formatted', 'order_time_order_number', 'wceuvat_conversion_rates' $sql_meta_fields_fetch_extra)
		ORDER BY
			orders.ID DESC
		LIMIT $page_start, $page_size
		";

		return apply_filters('woocommerce_vat_compliance_get_report_sql', $sql, $page_start, $page_size, $start_date, $end_date, $tax_extra_fields);
	}

	// We assume that the total number of refunds won't be enough to cause memory problems - so, we just get them all and then filter them afterwards
	// Returns an array of arrays of arrays: keys: $order_id -> $tax_rate_id -> (string)"items_vat"|"shipping_vat" -> (numeric)amount - or, in combined format, the last array is dropped out and you just get a total amount.
	// We used to have an $order_status parameter, but refunds always have status "wc-completed", and to get the status of the parent order (i.e. the order that the refund was against), it's better for the caller to do its own processing
	public function get_refund_report_results($start_date, $end_date, $combined_format = false) {

		global $wpdb;

		$compliance = WooCommerce_EU_VAT_Compliance();

		$normalised_results = array();

		// N.B. The previously-used order_status parameter here does nothing, as the order status for a refund is always wc-completed. So, the returned results need filtering later, rather than being able to get the order status at this stage with a single piece of SQL (which is what we're using for efficiency)
		$sql = $this->get_refunds_sql(false, false, $start_date, $end_date);

		if (!$sql) return array();

		if (defined('WC_VAT_REPORTING_LOG_QUERIES') && WC_VAT_REPORTING_LOG_QUERIES) error_log("WC_VAT_REPORTING_LOG_QUERIES get_refund_report_results(): ".preg_replace('/(\t|\n|  )/', ' ', $sql));
		$results = $wpdb->get_results($sql);
		if (!is_array($results)) return array();

		$current_order_item_id = false;

		// This forces the loop to go round one more time, so that the last object in the DB results gets processed
		$res_terminator = new stdClass;
		$res_terminator->oid = -1;
		$res_terminator->id = -1;
		$res_terminator->v = false;
		$res_terminator->k = false;
		$results[] = $res_terminator;

		$default_result = $combined_format ? 0 : array('items_vat' => 0, 'shipping_vat' => 0);
		// The search results are sorted by order item ID (oid) and then by meta_key. We rely on both these facts in the following loop.
		foreach ($results as $res) {
			$order_id = $res->id;
			$order_item_id = $res->oid;
			$meta_value = $res->v;
			$meta_key = $res->k;

			if ($current_order_item_id !== $order_item_id) {
				if ($current_order_item_id !== false) {
					// Process previous record
					if (false !== $current_rate_id) {
						if (false != $current_tax_amount) {
							if (!isset($normalised_results[$current_order_id][$current_rate_id])) $normalised_results[$current_order_id][$current_rate_id] = $default_result;
							if ($combined_format) {
								$normalised_results[$current_order_id][$current_rate_id] += $current_tax_amount;
							} else {
								$normalised_results[$current_order_id][$current_rate_id]['items_vat'] += $current_tax_amount;
							}
						}
						if (false != $current_shipping_tax_amount) {
							if (!isset($normalised_results[$current_order_id][$current_rate_id])) $normalised_results[$current_order_id][$current_rate_id] = $default_result;
							if ($combined_format) {
								$normalised_results[$current_order_id][$current_rate_id] += $current_shipping_tax_amount;
							} else {
								$normalised_results[$current_order_id][$current_rate_id]['shipping_vat'] += $current_shipping_tax_amount;
							}
						}
					}
				}

				// Reset other values for the new item
				$current_order_item_id = $order_item_id;
				$current_order_id = $order_id;
				$current_rate_id = false;
				$current_tax_amount = false;
				$current_shipping_tax_amount = false;

			}

			// These come from the itemmeta table, and thus there's no HPOS issue
			if ('rate_id' == $meta_key) {
				$current_rate_id = $meta_value;
			} elseif ('tax_amount' == $meta_key) {
				$current_tax_amount = $meta_value;
			} elseif ('shipping_tax_amount' == $meta_key) {
				$current_shipping_tax_amount = $meta_value;
			}

		}
		return $normalised_results;

	}

	/**
	 * Return a list of HPOS column names and their post equivalents
	 *
	 * Not intended to be comprehensive, but to include those used in the reports and which are not handled by another mechanism.
	 *
	 * @param Boolean $include_operational_table
	 *
	 * @return Array - keys are the HPOS column names, values are the postmeta names. N.B. It may no longer be ordered by order ID.
	 */
	private function get_hpos_column_mappings($include_operational_table = false) {
		$columns = array(
			'payment_method_title' => '_payment_method_title',
			'currency' => '_order_currency',
			'payment_method' => '_payment_method',
			'user_agent' => '_customer_user_agent',
			'customer_note' => '_purchase_note',
			'ip_address' => '_customer_ip_address',
			'tax_amount' => '_order_tax',
			'total_amount' => '_order_total',
			'ip_address' => '_customer_ip_address',
			'date_updated_gmt' => 'post_date_gmt',
		);
		
		if ($include_operational_table) {
			$columns = array_merge($columns, array(
				// These next 4 are in the orders operational table, upon which we form a left join to bring them in
				'shipping_tax_amount' => '_order_shipping_tax',
				'shipping_total_amount' => '_order_shipping',
				'discount_tax_amount' => '_cart_discount_tax',
				'discount_total_amount' => '_cart_discount',
			));
		};
		
		return $columns;
	}
	
	/**
	 * Convert HPOS query results to post format. This means converting from HPOS columns to meta key/values.
	 * 
	 * Rows with meta key _billing_address_index will be removed if present
	 *
	 * @param Array $results		  - input; note that objects in this array may be altered (the caller cannot assume they have not been)
	 * @param Array $tax_extra_fields - extra meta fields that were fetched
	 *
	 * @param Array - converted results
	 */
	private function convert_hpos_results_format_to_post($results, $tax_extra_fields = array()) {

		$hpos_columns_to_convert = $this->get_hpos_column_mappings(true);
		
		$add_to_results = array();
		$already_added = array();
		
		$order_ids = array();
		
		foreach ($results as $row_key => $row) {
			
			if ('_billing_address_index' == $row->meta_key) {
				unset($results[$row_key]);
				continue;
			}
			
			foreach ($hpos_columns_to_convert as $hpos_column_name => $meta_key) {
				
				if (!isset($row->$hpos_column_name)) continue;
				
				$row_value = $row->$hpos_column_name;
				unset($results[$row_key]->$hpos_column_name);

				if (!in_array($row->ID, $order_ids)) $order_ids[] = (int) $row->ID;
				
				$add_id = $row->ID.'-'.$meta_key;
				if (in_array($add_id, $already_added)) continue;
				
				$already_added[] = $add_id;
				
				$new_pair = new stdClass;
				$new_pair->ID = $row->ID;
				$new_pair->meta_key = $meta_key;
				$new_pair->meta_value = $row_value;
				
				$add_to_results[] = $new_pair;
			}
			
		}
		
		$results = array_merge($results, $add_to_results);

		global $wpdb;

		// Now insert the addresses for these orders
		while (!empty($order_ids)) {
			$fetch_order_ids_sql = '';
			while (!empty($order_ids) && strlen($fetch_order_ids_sql) < 2048) {
				$fetch_order_ids = array_splice($order_ids, 0, 256);
				if ($fetch_order_ids_sql) $fetch_order_ids_sql .= ',';
				// These are sanitised above to all be integers
				$fetch_order_ids_sql .= implode(',', $fetch_order_ids);
			}

			$fields_to_fetch = array('state', 'country');
			$fields_to_fetch_by_type = array('billing' => array(), 'shipping' => array());
			$address_sql = "SELECT order_id AS ID, address_type";
			$shipping_fields_present = false;
			
			foreach ($tax_extra_fields as $meta_key) {
				if (preg_match('/^_(shipping|billing)_(.*)$/', $meta_key, $matches)) {
					if ('shipping' == $matches[1]) {
						$shipping_fields_present = true;
					}
					if (!in_array($matches[2], $fields_to_fetch)) {
						$fields_to_fetch_by_type[$matches[1]][] = $matches[2];
						$fields_to_fetch[] = $matches[2];
					}
				}
			}
			
			$address_sql .= ', '.implode(', ', $fields_to_fetch);
			
			$address_sql .= " FROM {$wpdb->prefix}wc_order_addresses WHERE order_id IN ({$fetch_order_ids_sql}) AND address_type IN ('billing'";
			
			if ($shipping_fields_present) $address_sql .= ", 'shipping'";
			$address_sql .= ")";

			if (defined('WC_VAT_REPORTING_LOG_QUERIES') && WC_VAT_REPORTING_LOG_QUERIES) error_log("WC_VAT_REPORTING_LOG_QUERIES convert_hpos_results_format_to_post(): ".preg_replace('/(\t|\n|  )/', ' ', $address_sql));
			$address_results = $wpdb->get_results($address_sql);
			if (!is_array($address_results)) continue;

			foreach ($address_results as $address_result) {

				if ('' !== $address_result->state && ('billing' == $address_result->address_type || in_array('_shipping_state', $tax_extra_fields))) {
					$new_pair = new stdClass;
					$new_pair->ID = $address_result->ID;
					$new_pair->meta_key = '_'.$address_result->address_type.'_state';
					$new_pair->meta_value = $address_result->state;
					$results[] = $new_pair;
				}
				
				$new_pair = new stdClass;
				$new_pair->ID = $address_result->ID;
				$new_pair->meta_key = '_'.$address_result->address_type.'_country';
				$new_pair->meta_value = $address_result->country;
				$results[] = $new_pair;
				
				if (isset($fields_to_fetch_by_type[$address_result->address_type])) {
					foreach ($fields_to_fetch_by_type[$address_result->address_type] as $field_to_fetch) {
						$new_pair = new stdClass;
						$new_pair->ID = $address_result->ID;
						$new_pair->meta_key = '_'.$address_result->address_type.'_'.$field_to_fetch;
						$new_pair->meta_value = $address_result->$field_to_fetch;
						$results[] = $new_pair;
					}
				}
				
			}
		}

		return $results;
		
	}
	
	/**
	 * @param String $start_date
	 * @param String $end_date
	 * @param Boolean $remove_non_eu_countries
	 * @param Boolean $print_as_csv
	 *
	 * @return Array
	 */
	public function get_report_results($start_date, $end_date, $remove_non_eu_countries = true, $print_as_csv = false) {
		global $wpdb;

		$compliance = WooCommerce_EU_VAT_Compliance();

		$default_rates_provider = $compliance->get_conversion_provider();

		// The thinking here is that even after the UK leaves the EU VAT area, it will still be desirable to include it in reports of past periods
		$reporting_countries = array_merge(
			$compliance->get_vat_region_countries('eu'),
			$compliance->get_vat_region_countries('uk')
		);

		$page = 0;
		// This used to be 1000; then up to Sep 2020, 7500. But we get a big speedup with a larger value. 20000 rows should be less than 2MB.
		$page_size = defined('WC_VAT_COMPLIANCE_REPORT_PAGE_SIZE') ? WC_VAT_COMPLIANCE_REPORT_PAGE_SIZE : 20000;
		$fetch_more = true;

		$normalised_results = array();

		$tax_based_on = get_option('woocommerce_tax_based_on');

		$tax_extra_fields = array();
		
		// N.B. Billing country is always fetched
		if ($print_as_csv) {
			$tax_extra_fields = array('_wcpdf_invoice_number', '_shipping_country', '_customer_ip_address', '_payment_method_title');
		} elseif ('shipping' == $tax_based_on) {
			$tax_extra_fields = array('_shipping_country');
		}
		
		// Legacy filter (deprecated May 2023 with the addition of HPOS support)
		$add_from_filter = apply_filters('wc_eu_vat_compliance_report_meta_fields', '', $print_as_csv);
		
		// New filter to use instead of wc_eu_vat_compliance_report_meta_fields. N.B. The fields should be name according to the post naming convention (not HPOS column names)
		$tax_extra_fields = apply_filters('wc_eu_vat_compliance_report_extra_meta_fields', $tax_extra_fields, $print_as_csv);
		
		if ('' !== $add_from_filter) {
			// May 2023. A notice also appears in the plugin's dashboard page
			error_log("WC VAT Compliance: your site is customised to use the DEPRECATED filter wc_eu_vat_compliance_report_meta_fields. Please stop using it. Consult the plugin changelog and code to find the proper replacement. This filter will stop being available, and hence your code will break in future unless you take action (and may be broken already depending on how you are using it).");
			
			$add_from_filter = preg_split('/,(\s+)?/', $add_from_filter);
			
			foreach ($add_from_filter as $meta_key_to_add) {
				$meta_key_to_add = trim($meta_key_to_add, '" ');
				$tax_extra_fields[] = $meta_key_to_add;
			}
		}
		
		$tax_extra_fields = array_unique($tax_extra_fields);
		
		while ($fetch_more) {
			$page_start = $page_size * $page;
			$results_this_time = 0;
			$sql = $this->get_report_sql($page_start, $page_size, $start_date, $end_date, $tax_extra_fields);

			if (empty($sql)) break;

			if (defined('WC_VAT_REPORTING_LOG_QUERIES') && WC_VAT_REPORTING_LOG_QUERIES) error_log("WC_VAT_REPORTING_LOG_QUERIES get_report_results(page_start=$page_start, page_size=$page_size): ".preg_replace('/(\t|\n|  )/', ' ', $sql));
			$results = $wpdb->get_results($sql);
			$remove_order_id = false;

			if (empty($results) || count($results) < $page_size) {
				$fetch_more = false;
				if (empty($results)) continue;
			}

			$page++;

			// These are found as *columns* under HPOS (and hence present in every row for separate meta key/value pairs, so there is some added inefficiency): tax_amount | total_amount | order_currency; and addresses are now fetched separately
			// Under post storage, they are meta key/value pairs; the keys are: _order_tax | _order_total | _billing_country | _billing_state | _order_currency
			
			// Under HPOS, several values that were meta values are now column values. We handle this via this one-time conversion (rather than adjusting the code below to handle multiple formats, which would be uglier). The conversion also performs the SQL query to add in the data from the wc_order_addresses table
			if ($this->hpos_enabled) $results = $this->convert_hpos_results_format_to_post($results, $tax_extra_fields);

			$order_statuses = array();
			
			foreach ($results as $res) {
				if (empty($res->ID)) continue;
				
				$order_id = $res->ID;
				
				// Used to be present in $res always before HPOS changes
				if (isset($order_statuses[$order_id])) {
					$order_status = $order_statuses[$order_id];
				} else {
					$order_status = preg_replace('/^wc-/', '', $res->order_status);
					$order_statuses[$order_id] = $order_status;
				}
				
				
				if (empty($normalised_results[$order_status][$order_id]['date_gmt']) && isset($res->date)) {
					$normalised_results[$order_status][$order_id] = array('date_gmt' => $res->date);
					if ($print_as_csv) $normalised_results[$order_status][$order_id]['date'] = get_date_from_gmt($res->date); // Post storage stores this as post_date, but HPOS doesn't store it. In both cases we convert from the GMT date.
				}

				switch ($res->meta_key) {
					case 'vat_compliance_country_info':
						$cinfo = maybe_unserialize($res->meta_value);
						if ($print_as_csv) $normalised_results[$order_status][$order_id]['vat_compliance_country_info'] = $cinfo;
						$vat_country = empty($cinfo['taxable_address']) ? '??' : $cinfo['taxable_address'];
						if (!empty($vat_country[0])) {
							if ($remove_non_eu_countries && !in_array($vat_country[0], $reporting_countries)) {
								$remove_order_id = $order_id;
								unset($normalised_results[$order_status][$order_id]);
								continue(2);
							}
							$normalised_results[$order_status][$order_id]['taxable_country'] = $vat_country[0];
						}
						if (!empty($vat_country[1])) $normalised_results[$order_status][$order_id]['taxable_state'] = $vat_country[1];
					break;
					case 'vat_compliance_vat_paid':
						$vat_paid = maybe_unserialize($res->meta_value);
						if (is_array($vat_paid)) {
							// Trying to minimise memory usage for large shops
							unset($vat_paid['currency']);
// 								unset($vat_paid['items_total']);
// 								unset($vat_paid['items_total_base_currency']);
// 								unset($vat_paid['shipping_total']);
// 								unset($vat_paid['shipping_total_base_currency']);
						}
						$normalised_results[$order_status][$order_id]['vat_paid'] = $vat_paid;
					break;
					// N.B. The next 4 are columns under HPOS (but are converted before arriving here)
					case '_billing_country':
					case '_shipping_country':
					case '_order_total':
					case '_order_currency':
					case '_order_total_base_currency':
						$normalised_results[$order_status][$order_id][$res->meta_key] = $res->meta_value;
					break;
					case '_payment_method_title':
						// Under HPOS we get an empty value, whereas previously we got no value at all; we normalise that behaviour here
						if ('' !== $res->meta_value) $normalised_results[$order_status][$order_id][$res->meta_key] = $res->meta_value;
					break;
						// If other plugins provide invoice numbers through other keys, we can use this to get them all into the right place in the end
					case '_wcpdf_invoice_number':
						$normalised_results[$order_status][$order_id]['invc_no'] = $res->meta_value;
					break;
					case 'Valid VAT Number':
						$normalised_results[$order_status][$order_id]['vatno_valid'] = $res->meta_value;
					break;
					case '_order_number_formatted':
						// This comes from WooCommerce Sequential Order Numbers Pro, and we prefer it
						$normalised_results[$order_status][$order_id]['order_number'] = $res->meta_value;
					case 'order_time_order_number':
						if (!isset($normalised_results[$order_status][$order_id]['order_number'])) $normalised_results[$order_status][$order_id]['order_number'] = $res->meta_value;
					break;
					case 'VAT Number':
						$normalised_results[$order_status][$order_id]['vatno'] = $res->meta_value;
					break;
					case 'VAT number validated':
						$normalised_results[$order_status][$order_id]['vatno_validated'] = $res->meta_value;
					break;
					case 'wceuvat_conversion_rates':
						$rates = maybe_unserialize($res->meta_value);
						$normalised_results[$order_status][$order_id]['conversion_rates'] = isset($rates['rates']) ? $rates['rates'] : array();
						$normalised_results[$order_status][$order_id]['conversion_provider'] = isset($rates['meta']['provider']) ? $rates['meta']['provider'] : $default_rates_provider;
					break;
					// N.B. Under HPOS, this is the order table column ip_address, but is converted before arriving here
					case '_customer_ip_address':
						if ($print_as_csv) $normalised_results[$order_status][$order_id][$res->meta_key] = $res->meta_value;
					break;
					default:
						// Allow inclusion of other data via filter
						if (false !== ($store_key = apply_filters('wc_eu_vat_compliance_get_report_results_store_key', false, $res))) {
							$normalised_results[$order_status][$order_id][$store_key] = $res->meta_value;
						}
					break;
					
				}

				if ($remove_order_id === $order_id) {
					unset($normalised_results[$order_status][$order_id]);
				}

			}

			// Parse results;
		}

		// Loop again, to make sure that we've got the VAT paid recorded.
		foreach ($normalised_results as $order_status => $orders) {
			foreach ($orders as $order_id => $res) {
				if (empty($res['taxable_country'])) {
					// Legacy orders
					switch ( $tax_based_on ) {
						case 'billing' :
						$res['taxable_country'] = isset($res['_billing_country']) ? $res['_billing_country'] : '';
						break;
						case 'shipping' :
						$res['taxable_country'] = isset($res['_shipping_country']) ? $res['_shipping_country'] : '';
						break;
						default:
						unset($normalised_results[$order_status][$order_id]);
						break;
					}
					if (!$print_as_csv) {
						unset($res['_billing_country']);
						unset($res['_shipping_country']);
					}
				}

				if (!isset($res['vat_paid'])) {
					// This is not good for performance. It was de-activated until version 1.14.22 (when a problem with metadata saving meant that there could be missing historic data that needed reconstructing).
					$vat_paid = $compliance->get_vat_paid($order_id, true, true, false);
					$res['vat_paid'] = $vat_paid;
					$normalised_results[$order_status][$order_id]['vat_paid'] = $vat_paid;
				}

				// N.B. Use of empty() means that those with zero VAT are also excluded at this point
				if (empty($res['vat_paid'])) {
					unset($normalised_results[$order_status][$order_id]);
				} elseif (!isset($res['order_number'])) {
					// This will be database-intensive, the first time, if they had a lot of orders before this bit of meta began to be recorded at order time (plugin version 1.7.2)
					$order = wc_get_order($order_id);
					$order_number = $order->get_order_number();
					$normalised_results[$order_status][$order_id]['order_number'] = $order_number;
					$order->update_meta_data('order_time_order_number', $order_number);
				}
			}
		}

		/* Interesting keys:
			_order_currency
			_order_shipping_tax
			_order_shipping_tax_base_currency
			_order_tax
			_order_tax_base_currency
			_order_total
			_order_total_base_currency
			vat_compliance_country_info
			Valid VAT Number (true)
			VAT Number
			VAT number validated (true)
		*/

		return $normalised_results;

	}

	/**
	 * Populates $this::conversion_providers
	 */
	public function initialise_rate_providers() {
		$compliance =  WooCommerce_EU_VAT_Compliance();
		$providers = $compliance->get_rate_providers();
		$conversion_provider = $compliance->get_conversion_provider();

		if (!is_array($providers) || !isset($providers[$conversion_provider])) throw new Exception('Default conversion provider not found: '.$conversion_provider);

		$this->conversion_providers = $providers;
	}

	/**
	 * Get report results; this is the main entry point for (internally) fetching report data.
	 *
	 * @uses self::get_report_results()
	 *
	 * @param String $start_date		  - in format YYYY-MM-DD
	 * @param String $end_date			  - in format YYYY-MM-DD
	 * @param String $country_of_interest - either 'reporting' (to whom tax is payable, most useful for generating tax reports) or 'taxation' (the country from the customer's taxable address, useful for evaluating thresholds)
	 *
	 * @return Array - keyed by order status string then tabulation country (according to the parameter $country_of_interest) then reporting currency, then rate key, then item (e.g. 'vat', 'vat_shipping')
	 */
	public function get_tabulated_results($start_date, $end_date, $country_of_interest = 'reporting') {

		global $wpdb;

		$compliance = WooCommerce_EU_VAT_Compliance();
		
		$results = $this->get_report_results($start_date, $end_date);

		// Further processing. Need to do currency conversions and index the results by country
		$tabulated_results = array();

		$base_country = $compliance->wc->countries->get_base_country();
		$base_currency = get_option('woocommerce_currency');
		$base_currency_symbol = get_woocommerce_currency_symbol($base_currency);
		
		// The thinking here is that even after the UK has left the EU VAT area, it is still desirable to include it in reports of past periods
		$reporting_countries = array_merge(
			$compliance->get_vat_region_countries('eu'),
			$compliance->get_vat_region_countries('uk')
		);
	
		$this->initialise_rate_providers();

		$this->reporting_currencies = $compliance->get_vat_recording_currencies('reporting');
		if (empty($this->reporting_currencies)) $this->reporting_currencies = array($base_currency);
		
		$default_reporting_currency = $this->reporting_currency = $this->reporting_currencies[0];

		// We need to make sure that the outer foreach() loop does go round for each status, because otherwise refunds on orders made in different accounting periods may be missed
		// These have the wc- prefix.
		$all_possible_statuses = $compliance->order_status_to_text(true);
		foreach ($all_possible_statuses as $wc_status => $status_text) {
			$order_status = substr($wc_status, 3);
			if (!isset($results[$order_status])) $results[$order_status] = array();
		}

		// Refunds data is keyed by ID, and then by tax-rate. This isn't maximally efficient for the reports table, but since we are not expecting tens of thousands of refunds, this should have no significant performance or memory impact.
		// N.B. This gets refunds for orders of all statuses (which is easiest, because WooCommerce doesn't mark the refund post's status to folllow the parent post's status - instead, it marks all refunds as wc-completed)
		$refunds_data = $this->get_refund_report_results($start_date, $end_date, true);

		$order_ids_with_refunds = array_keys($refunds_data);
		$order_statuses = array();

		if (!empty($order_ids_with_refunds)) {
			
			// This method is less efficient, though should work in any database-storage scenario
			//foreach ($order_ids_with_refunds as $order_id) {
			//	$order = wc_get_order($order_id);
			//	$order_statuses[$order_id] = $order->get_status();
			//}
			
			// Note that the SQL must return the same results (or move the processing inside the branch)
			// HPOS
			if ($compliance->woocommerce_custom_order_tables_enabled()) {
				$get_order_statuses_sql = "SELECT orders.id as order_id, orders.status AS order_status FROM ".$wpdb->prefix."wc_orders AS orders";
			} else {
				// Process refunds to work out their parent order's order status
				$get_order_statuses_sql = "SELECT orders.ID as order_id, orders.post_status AS order_status FROM ".$wpdb->posts." AS orders";
			}
			
			$get_order_statuses_sql .= " WHERE orders.ID IN (".implode(',', $order_ids_with_refunds).")";
			
			if (defined('WC_VAT_REPORTING_LOG_QUERIES') && WC_VAT_REPORTING_LOG_QUERIES) error_log("WC_VAT_REPORTING_LOG_QUERIES get_tabulated_results(): ".preg_replace('/(\t|\n|  )/', ' ', $get_order_statuses_sql));
			$order_status_results = $wpdb->get_results($get_order_statuses_sql);
			if (is_array($order_status_results)) {
				foreach ($order_status_results as $r) {
					if (empty($r->order_id)) continue;
					$order_statuses[$r->order_id] = substr($r->order_status, 3);
				}
			}
		}
		
		// Then, we need to filter the refunds that are checked in the next loop, below
		
		foreach ($results as $order_status => $result_set) {

			// This returns an array of arrays; keys = order IDs; second key = tax rate IDs, values = total amount of orders taxed at these rates
			// N.B. The "total" column potentially has no meaning when totaling item totals, as a single item may have attracted multiple taxes (theoretically). Note also that the totals are *for orders with VAT*.
			$get_items_data = $this->get_items_data($start_date, $end_date, $order_status);

			// We need to make sure that refunds still get processed when they are from a different account period (i.e. when the order is not in the results set)
			foreach ($refunds_data as $order_id => $refunds_by_rate) {
				if (empty($result_set[$order_id])) {
					// Though this taxes the database more, it should be a very rare occurrence

					$refunded_order = wc_get_order($order_id);
					
					if (false == $refunded_order) {
						error_log("WC_EU_VAT_Compliance_Reports::get_main_chart(): get_order failed for order with refund, id=$order_id");
						continue;
					}

					$post_id = $refunded_order->get_id();
					
					$rates = $refunded_order->get_meta('wceuvat_conversion_rates', true);
					
					$cinfo = $refunded_order->get_meta('vat_compliance_country_info', true);
					$vat_compliance_vat_paid = $refunded_order->get_meta('vat_compliance_vat_paid', true);

					$by_rates = array();
					foreach ($refunds_by_rate as $tax_rate_id => $tax_refunded) {
						if (isset($vat_compliance_vat_paid['by_rates'][$tax_rate_id])) {
							$by_rates[$tax_rate_id] = array(
								'is_variable_eu_vat' => isset($vat_compliance_vat_paid['by_rates'][$tax_rate_id]) ? $vat_compliance_vat_paid['by_rates'][$tax_rate_id] : true,
								'items_total' => 0,
								'shipping_total' => 0,
								'rate' => $vat_compliance_vat_paid['by_rates'][$tax_rate_id]['rate'],
								'name' => $vat_compliance_vat_paid['by_rates'][$tax_rate_id]['name'],
							);
						}
					}

					$result_set[$order_id] = array(
						'vat_paid' => array(
							'total' => 0,
							'by_rates' => $by_rates
						),
						'_order_currency' => $refunded_order->get_currency()
					);

					$vat_country = empty($cinfo['taxable_address']) ? '??' : $cinfo['taxable_address'];
					if (!empty($vat_country[0])) {
						if (in_array($vat_country[0], $reporting_countries)) {
							$result_set[$order_id]['taxable_country'] = $vat_country[0];
						}
					}

					if (is_array($rates) && isset($rates['rates'])) $result_set[$order_id]['conversion_rates'] = $rates['rates'];

				}
			}
			
			foreach ($result_set as $order_id => $order_info) {

				// Don't test empty($order_info['vat_paid']['total']), as this can cause refunds to be not included
				if (!is_array($order_info) || empty($order_info['taxable_country']) || empty($order_info['vat_paid']) || !is_array($order_info['vat_paid']) || !isset($order_info['vat_paid']['total'])) continue;

				$order_currency = isset($order_info['_order_currency']) ? $order_info['_order_currency'] : $base_currency;
				// The country that the order is taxable for
				$taxable_country = $order_info['taxable_country'];

				$conversion_rates = isset($order_info['conversion_rates']) ? $order_info['conversion_rates'] : array();
				// Convert the 'vat_paid' array so that its values in the reporting currency, according to the conversion rates stored with the order

				$get_items_data_for_order = isset($get_items_data[$order_id]) ? $get_items_data[$order_id] : array();
				$refunds_data_for_order = (isset($refunds_data[$order_id]) && $order_statuses[$order_id] == $order_status) ? $refunds_data[$order_id] : array();

				$order_reporting_currency = $default_reporting_currency;
				if (!empty($order_info['conversion_rates'])) {
					$order_reporting_currencies = array_keys($order_info['conversion_rates']);
					$order_reporting_currency = apply_filters('wc_vat_order_reporting_currency', $order_reporting_currencies[0], $order_id, $order_info, $order_reporting_currencies, $get_items_data_for_order);
				}
				
				$converted_order_data = $this->get_currency_converted_order_data($order_info, $order_currency, $conversion_rates, $get_items_data_for_order, $refunds_data_for_order, $order_reporting_currency);
				
				$order_info_converted = $converted_order_data['order_data'];
				$converted_items_data_for_order = $converted_order_data['items_data'];
				$converted_refunds_data_for_order = $converted_order_data['refunds_data'];

				$vat_paid = $order_info_converted['vat_paid'];

				$by_rate = array();
				if (isset($vat_paid['by_rates'])) {
					foreach ($vat_paid['by_rates'] as $tax_rate_id => $rate_info) {

						// The country where the tax is payable
						$tabulation_country = $taxable_country;
					
						$rate = sprintf('%0.2f', $rate_info['rate']);
						$rate_key = $rate;
						// !isset implies 'legacy - data produced before the plugin set this field: assume it is variable, because at that point the plugin did not officially support mixed shops with non-variable VAT'
						if (!isset($rate_info['is_variable_eu_vat']) || !empty($rate_info['is_variable_eu_vat'])) {
							// Variable VAT
							$rate_key = 'V-'.$rate_key;
						} elseif ('reporting' == $country_of_interest) {
							// Non-variable VAT: should be attribute to the base country, for reporting purposes, unless keying by taxation country was requested
							// We started to record the order-time base country from 1.14.25. If it's not there, we assume it is the current shop base country (changing will be rare).
							$tabulation_country = isset($vat_paid['base_country']) ? $vat_paid['base_country'] : $base_country;
						}
						
						$by_rate[$rate_key]['tabulation_country'] = $tabulation_country;

						$check_keys = array('vat', 'vat_shipping', 'sales', 'vat_refunded');
						if (!isset($by_rate[$rate_key])) $by_rate[$rate_key] = array();
						foreach ($check_keys as $check_key) {
							if (!isset($by_rate[$rate_key][$check_key])) $by_rate[$rate_key][$check_key] = 0;
						}
						
						if (isset($rate_info['items_total'])) $by_rate[$rate_key]['vat'] += $rate_info['items_total'];
						
						if (isset($rate_info['shipping_total'])) {
							$by_rate[$rate_key]['vat'] += $rate_info['shipping_total'];
							$by_rate[$rate_key]['vat_shipping'] += $rate_info['shipping_total'];
						}

						// Add sales from items totals
						if (isset($converted_items_data_for_order[$tax_rate_id])) {
							$by_rate[$rate_key]['sales'] += $converted_items_data_for_order[$tax_rate_id];
						}

						// Add refunds data
						// If no VAT was paid at this rate in the accounting period, then that means that the order itself can't have been in this accounting period - and so, the "missing order" detector above will add the necessary blank data. Thus, this code path will be active
						if (isset($converted_refunds_data_for_order[$tax_rate_id])) {
							$by_rate[$rate_key]['vat_refunded'] += $converted_refunds_data_for_order[$tax_rate_id];
						}
					}

				} else {
					// Legacy: no "by_rates" plugin versions also only allowed variable VAT
					$rate_key = 'V-'.__('Unknown', 'woocommerce-eu-vat-compliance');
					if (!isset($by_rate[$rate_key])) $by_rate[$rate_key] = array(
						'vat' => 0,
						'vat_shipping' => 0,
						'sales' => 0,
						'tabulation_country' => $taxable_country
					);
					
					$by_rate[$rate_key]['vat'] += $vat_paid['total'];
					$by_rate[$rate_key]['vat_shipping'] += $vat_paid['shipping_total'];

					foreach ($converted_items_data_for_order as $tax_rate_id => $sales_amount) {
						$by_rate[$rate_key]['sales'] += $sales_amount;
					}

					foreach ($converted_refunds_data_for_order as $tax_rate_id => $refund_amount) {
						$by_rate[$rate_key]['vat_refunded'] += $refund_amount;
					}
				}

				foreach ($by_rate as $rate_key => $rate_data) {
				
					$tabulation_country = $rate_data['tabulation_country'];
				
					// VAT (items)
					if (empty($tabulated_results[$order_status][$tabulation_country][$order_reporting_currency][$rate_key]['vat'])) $tabulated_results[$order_status][$tabulation_country][$order_reporting_currency][$rate_key]['vat'] = 0;
					$tabulated_results[$order_status][$tabulation_country][$order_reporting_currency][$rate_key]['vat'] += $rate_data['vat'];

					// VAT (shipping)
					if (empty($tabulated_results[$order_status][$tabulation_country][$order_reporting_currency][$rate_key]['vat_shipping'])) $tabulated_results[$order_status][$tabulation_country][$order_reporting_currency][$rate_key]['vat_shipping'] = 0;
					$tabulated_results[$order_status][$tabulation_country][$order_reporting_currency][$rate_key]['vat_shipping'] += $rate_data['vat_shipping'];
					
					// Items total, using the data got from the (current) order_itemmeta and order_items tables
					if (empty($tabulated_results[$order_status][$tabulation_country][$order_reporting_currency][$rate_key]['sales'])) $tabulated_results[$order_status][$tabulation_country][$order_reporting_currency][$rate_key]['sales'] = 0;
					if (isset($rate_data['sales'])) {
						$tabulated_results[$order_status][$tabulation_country][$order_reporting_currency][$rate_key]['sales'] += $rate_data['sales'];
					}
					
					// Refunds total, using the data got from the (current) order_itemmeta and order_items tables
					if (empty($tabulated_results[$order_status][$tabulation_country][$order_reporting_currency][$rate_key]['vat_refunded'])) $tabulated_results[$order_status][$tabulation_country][$order_reporting_currency][$rate_key]['vat_refunded'] = 0;
					if (isset($rate_data['vat_refunded'])) $tabulated_results[$order_status][$tabulation_country][$order_reporting_currency][$rate_key]['vat_refunded'] += $rate_data['vat_refunded'];
				}

			}
		}
		
		return $tabulated_results;
	}

	/**
	 * Format an amount
	 *
	 * @param Number $amount
	 *
	 * @uses self::format_num_decimals
	 *
	 * @return String
	 */
	public function format_amount($amount) {
		return apply_filters('wc_eu_vat_compliance_reports_format_amount', sprintf("%0.".$this->format_num_decimals."f", $amount), $amount, $this->format_num_decimals);
	}

	public function get_converted_refunds_data($refunds_for_order, $order_currency, $conversion_rates, $reporting_currency = null) {

		if (!is_array($refunds_for_order)) return $refunds_for_order;

		$use_provider = '';
		$passed_reporting_currency = $reporting_currency;
		if (null == $reporting_currency) $reporting_currency = $this->reporting_currency;
		
		if (isset($conversion_rates[$reporting_currency])) {
			$use_rate = $conversion_rates[$reporting_currency];
			if (null !== $passed_reporting_currency && !empty($refunds_for_order['conversion_provider'])) $use_provider = $refunds_for_order['conversion_provider'];
		} elseif (isset($this->fallback_conversion_rates[$order_currency])) {
			$use_rate = $this->fallback_conversion_rates[$order_currency];
			$use_provider = $this->fallback_conversion_providers[$order_currency];
		} else {
			// Returns the conversion for 1 unit of the order currency.
			$conversion_provider_code = WooCommerce_EU_VAT_Compliance()->get_conversion_provider();
			$conversion_provider = $this->conversion_providers[$conversion_provider_code];
			$use_rate = $conversion_provider->convert($order_currency, $this->reporting_currency, 1);
			$use_provider = $conversion_provider_code;
			$this->fallback_conversion_rates[$order_currency] = $use_rate;
			$this->fallback_conversion_providers[$order_currency] = $conversion_provider_code;
		}

		foreach ($refunds_for_order as $tax_rate_id => $refunded_amount) {
			$refunds_for_order[$tax_rate_id] = $refunded_amount * $use_rate;
		}
		
		return $refunds_for_order;

	}

	/**
	 * This takes one or two arrays of order data, and converts the amounts in them to the requested currency
	 * 
	 * public: used also in the CSV download
	 *
	 * @return Array
	 */
	public function get_currency_converted_order_data($raw, $order_currency, $conversion_rates, $get_items_data_for_order = array(), $refunds_data_for_order = array(), $reporting_currency = null) {

		$use_provider = '';
		$passed_reporting_currency = $reporting_currency;
		if (null == $reporting_currency) $reporting_currency = $this->reporting_currency;

		if (isset($conversion_rates[$reporting_currency]) && apply_filters('wc_vat_compliance_currency_converted_order_data_use_order_saved_data', true, $raw, $order_currency, $conversion_rates, $get_items_data_for_order, $refunds_data_for_order, $reporting_currency)) {
			$use_rate = $conversion_rates[$reporting_currency];
			if (null !== $passed_reporting_currency && !empty($raw['conversion_provider'])) $use_provider = $raw['conversion_provider'];
		} elseif (isset($this->fallback_conversion_rates[$order_currency])) {
			$use_rate = $this->fallback_conversion_rates[$order_currency];
			$use_provider = $this->fallback_conversion_providers[$order_currency];
		} else {
			// Returns the conversion for 1 unit of the order currency.
			$conversion_provider_code = WooCommerce_EU_VAT_Compliance()->get_conversion_provider();
			$conversion_provider = $this->conversion_providers[$conversion_provider_code];
			
			$use_conversion_time = apply_filters('wc_vat_compliance_currency_converted_order_data_use_conversion_time', false, $raw, $order_currency, $conversion_rates, $get_items_data_for_order, $refunds_data_for_order, $reporting_currency);
			
			$use_rate = $conversion_provider->convert($order_currency, $this->reporting_currency, 1, $use_conversion_time);
			$use_provider = $conversion_provider_code;
			$this->fallback_conversion_rates[$order_currency] = $use_rate;
			$this->fallback_conversion_providers[$order_currency] = $conversion_provider_code;
		}
		
		// Allow filters to use a different rate and indicate a different provider
		$use_rate = apply_filters('wc_vat_compliance_currency_converted_order_data_use_rate', $use_rate, $raw, $order_currency, $conversion_rates, $get_items_data_for_order, $refunds_data_for_order, $reporting_currency);
		$use_provider = apply_filters('wc_vat_compliance_currency_converted_order_data_use_provider', $use_provider, $raw, $order_currency, $conversion_rates, $get_items_data_for_order, $refunds_data_for_order, $reporting_currency);
		
		$this->last_rate_used = $use_rate;

		$convert_keys = array('_order_total', 'shipping_amount');
		foreach ($convert_keys as $key) {
			if (isset($raw[$key])) {
				$raw[$key] = $raw[$key] * $use_rate;
			}
		}

		$convert_vat_paid_keys = array('items_total', 'shipping_total', 'total');
		foreach ($convert_vat_paid_keys as $key) {
			if (isset($raw['vat_paid'][$key])) {
				$raw['vat_paid'][$key] = $raw['vat_paid'][$key] * $use_rate;
			}
		}
		if (isset($raw['vat_paid']['by_rates'])) {
			foreach ($raw['vat_paid']['by_rates'] as $rate_id => $rate) {
				foreach ($convert_keys as $key) {
					if (isset($rate[$key])) {
						$raw['vat_paid']['by_rates'][$rate_id][$key] = $raw['vat_paid']['by_rates'][$rate_id][$key] * $use_rate;
					}
				}
			}
		}

		foreach ($get_items_data_for_order as $tax_rate_id => $amount) {
			$get_items_data_for_order[$tax_rate_id] = $amount * $use_rate;
		}

		foreach ($refunds_data_for_order as $tax_rate_id => $amount) {
			$refunds_data_for_order[$tax_rate_id] = $amount * $use_rate;
		}

		$raw['conversion_provider'] = $use_provider;
		
		return array(
			'order_data' => $raw,
			'items_data' => $get_items_data_for_order,
			'refunds_data' => $refunds_data_for_order
		);
	}

}
