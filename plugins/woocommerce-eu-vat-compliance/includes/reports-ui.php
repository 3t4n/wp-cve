<?php

if (!defined('WC_VAT_COMPLIANCE_DIR')) die('No direct access');

// Purpose: abstract UI elements out of reports.php for easier maintenance

class WC_VAT_Compliance_Reports_UI {

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action('wc_eu_vat_compliance_cc_tab_reports', array($this, 'wc_eu_vat_compliance_cc_tab_reports'));
		add_action('admin_init', array($this, 'admin_init'));
	}

	/**
	 * Runs upon the WP action admin_init
	 */
	public function admin_init() {
		add_filter('woocommerce_admin_reports', array($this, 'eu_vat_report'));
	}
	
	/**
	 * Hook into control centre and add a tab
	 */
	public function wc_eu_vat_compliance_cc_tab_reports($full = false) {
		echo '<h2>'.__('VAT Report', 'woocommerce-eu-vat-compliance').'</h2>';
		$this->wc_eu_vat_compliance_report();
	}

	/**
	 * WordPress filter woocommerce_admin_reports - add our report into the 'Taxes' report tab
	 */
	public function eu_vat_report($reports) {
		if (isset($reports['taxes'])) {
			$reports['taxes']['reports']['eu_vat_report'] = array(
				'title'       => __('VAT Report', 'woocommerce-eu-vat-compliance'),
				'description' => '',
				'hide_title'  => false,
				'callback'    => array($this, 'wc_eu_vat_compliance_report')
			);
		}
		return $reports;
	}
	
	/**
	 * This is called by woocommerce/includes/admin/views/html-report-by-date.php in order to print out the actual report table itself.
	 */
	public function get_main_chart() {

		global $wpdb; // No interaction with HPOS
		if ($wpdb->last_error) {
			echo htmlspecialchars($wpdb->last_error);
			return;
		}

		$start_date = date('Y-m-d', $this->start_date);
		$end_date = date('Y-m-d', $this->end_date);

		$compliance =  WooCommerce_EU_VAT_Compliance();

		// Remove the 'sales' column if there are items with no line tax data (i.e. pre-WC 2.2 sales) OR (better?) display a warning about the data being incomplete.
		if (!empty($this->pre_wc22_order_parsed)) {
			if (is_array($this->pre_wc22_order_parsed)) $pre_wc22_orders = implode(', ', array_unique($this->pre_wc22_order_parsed));
			?>
			<p>
			<span style="font-weight:bold; color:red;" <?php if (isset($pre_wc22_orders)) echo 'title="'.esc_attr($pre_wc22_orders).'"'; ?>><?php _e('Note:', 'woocommerce-eu-vat-compliance');?></span> <?php echo __('The selected time period contains orders originally placed under WooCommerce 2.1 or earlier, or which for some other reason are missing tax data (e.g. they were created in a wrong manner by an extension).', 'woocommerce-eu-vat-compliance').' '.__('These WooCommerce versions did not record the data used to display the "Items" column, which is therefore incomplete and has been hidden.', 'woocommerce-eu-vat-compliance');?> <a href="#" onclick="jQuery('.wceuvat_itemsdata').slideDown(); wceuvat_itemsdata_show=true; jQuery(this).parent().remove(); return false;"><?php _e('Show', 'woocommerce-eu-vat-compliance');?></a>
			</p>
			<?php
		}
		?>
		<script>
			var wceuvat_itemsdata_show = false;
			jQuery(function($) {
				$('.wceuvat_itemsdata').hide();
			});
		</script>
		<?php

		$this->report_table_header();
		
		$country_mode = empty($_REQUEST['country_mode']) ? 'reporting' : $_REQUEST['country_mode'];
		if ('taxation' != $country_mode) $country_mode = 'reporting';
		
		// $tabulated_results[$order_status][$tabulation_country][$order_reporting_currency][$rate_key]['vat'] = ...
		$tabulated_results = $this->get_tabulated_results($start_date, $end_date, $country_mode);

		$eu_total = 0;

		$countries = $compliance->wc->countries;
		$all_countries = $countries->countries;

		$reporting_currency_symbols = array();
		$reporting_currency_symbols[$this->reporting_currency] = get_woocommerce_currency_symbol($this->reporting_currency);

		// These are keyed by reporting currency
		$total_vat_items = array($this->reporting_currency => 0);
		$total_vat_shipping = array($this->reporting_currency => 0);
		$total_vat_refunds = array($this->reporting_currency => 0);
		$total_vat = array($this->reporting_currency => 0);
		$total_vatable_supplies = array($this->reporting_currency => 0);

		$total_items = array($this->reporting_currency => 0);
		$total_sales = array($this->reporting_currency => 0);

		$this->format_num_decimals = get_option('woocommerce_price_num_decimals', 2);
		
		foreach ($tabulated_results as $order_status => $results) {
		
			$status_text = $compliance->order_status_to_text($order_status);

			foreach ($results as $country => $per_currency_totals) {
			
				foreach ($per_currency_totals as $reporting_currency => $per_rate_totals) {
			
					foreach ($per_rate_totals as $rate_key => $totals) {

						$country_label = isset($all_countries[$country]) ? $all_countries[$country] : __('Unknown', 'woocommerce-eu-vat-compliance').' ('.$country.')';
						$country_label = '<span title="'.$country.'">'.$country_label.'</span>';

						$vat_items_amount = $compliance->round_amount($totals['vat']-$totals['vat_shipping']);
						$vat_shipping_amount = $compliance->round_amount($totals['vat_shipping']);
						$vat_total_amount = $compliance->round_amount($totals['vat']+$totals['vat_refunded']);
						$vat_refund_amount = $compliance->round_amount($totals['vat_refunded']);

						$items_amount = $compliance->round_amount($totals['sales']);

						if (!isset($total_vat_items[$reporting_currency])) {
							$total_vat_items[$reporting_currency] = 0;
							$total_vat_shipping[$reporting_currency] = 0;
							$total_vat_refunds[$reporting_currency] = 0;
							$total_vat[$reporting_currency] = 0;
							$total_vatable_supplies[$reporting_currency] = 0;

							$total_items[$reporting_currency] = 0;
							$total_sales[$reporting_currency] = 0;
						}
						
						$total_vat[$reporting_currency] += $vat_total_amount;
						$total_vat_items[$reporting_currency] += $vat_items_amount;
						$total_items[$reporting_currency] += $items_amount;
						$total_vat_shipping[$reporting_currency] += $vat_shipping_amount;
						$total_vat_refunds[$reporting_currency] += $vat_refund_amount;

						if (preg_match('/^(V-)?([\d\.]+)$/', $rate_key, $matches)) {
							$vat_rate = $matches[2];
							$vat_rate_label = str_replace('.00', '.0', $matches[2].'%');
							if (empty($matches[1])) {
								$vat_rate_label .= '<span title="'.esc_attr(__('Fixed - i.e., traditional non-variable VAT', 'woocommerce-eu-vat-compliance')).'"> ('.__('fixed', 'woocommerce-eu-vat-compliance').')</span>';
							}
						} else {
							$vat_rate_label = htmlspecialchars($rate_key);
							$vat_rate = (float)$rate_key;
						}
						
						if (0 == $vat_rate) continue;

						if (!isset($reporting_currency_symbols[$reporting_currency])) 
						$reporting_currency_symbols[$reporting_currency] = get_woocommerce_currency_symbol($reporting_currency);

						$reporting_currency_symbol = $reporting_currency_symbols[$reporting_currency];
						
						$extra_col_items = '<td class="wceuvat_itemsdata">'.$reporting_currency_symbol.' '.$this->format_amount($items_amount).'</td>';
						$extra_col_refunds = '<td class="wceuvat_refundsdata">'.$reporting_currency_symbol.' '.$this->format_amount($vat_refund_amount).'</td>';

						// $vat_rate is known to be non-zero; 
						$vatable_supplies = 100 * $vat_total_amount / $vat_rate;
						$total_vatable_supplies[$reporting_currency] += $vatable_supplies;
						
						// This chunk is just to see whether it'd potentially be easier to use the 'items' amount instead of the calculated one
						$vat_from_items = $items_amount * $vat_rate / 100;
						if ($compliance->round_amount($vat_from_items) == $compliance->round_amount($vat_total_amount)) $vatable_supplies = $items_amount;
						
						//data-items=\"".sprintf('%.05f', $totals['sales']-$totals['vat'])."\"
						echo "<tr data-reporting-currency=\"".esc_attr($reporting_currency)."\" data-vatable-supplies=\"".$compliance->round_amount($vatable_supplies)."\" data-vat-items=\"".$compliance->round_amount($vat_items_amount)."\" data-vat-refunds=\"".$compliance->round_amount($vat_refund_amount)."\" data-vat-shipping=\"".$compliance->round_amount($vat_shipping_amount)."\" data-items=\"".$compliance->round_amount($items_amount)."\" class=\"statusrow status-$order_status\">
							<td>$status_text</td>
							<td>$country_label</td>".$extra_col_items."
							<td>$reporting_currency_symbol ".$this->format_amount($vatable_supplies)."</td>
							<td>$vat_rate_label</td>
							<td>$reporting_currency_symbol ".$this->format_amount($vat_items_amount)."</td>
							<td>$reporting_currency_symbol ".$this->format_amount($vat_shipping_amount)."</td>".$extra_col_refunds."
							<td>$reporting_currency_symbol ".$this->format_amount($vat_total_amount)."</td>
						</tr>";

					}
				}
			}
		}

		echo '</tbody>';

		foreach ($reporting_currency_symbols as $reporting_currency => $reporting_currency_symbol) {
			?>
			<tr class="wc_eu_vat_compliance_totals" id="wc_eu_vat_compliance_total">
				<td><strong><?php echo __('Grand Total', 'woocommerce-eu-vat-compliance');?></strong></td>
				<td>-</td>
				<td class="wceuvat_itemsdata"><strong><?php echo $reporting_currency_symbol.' '.sprintf('%.2f', $total_items[$reporting_currency]); ?></strong></td>
				<td><strong><?php echo $reporting_currency_symbol.' '.sprintf('%.2f', $total_vatable_supplies[$reporting_currency]); ?></strong></td>
				<td>-</td>
				<td><strong><?php echo $reporting_currency_symbol.' '.sprintf('%.2f', $total_vat_items[$reporting_currency]); ?></strong></td>
				<td><strong><?php echo $reporting_currency_symbol.' '.sprintf('%.2f', $total_vat_shipping[$reporting_currency]); ?></strong></td>
				<td><strong><?php echo $reporting_currency_symbol.' '.sprintf('%.2f', $total_vat_refunds[$reporting_currency]); ?></strong></td>
				<td><strong><?php echo $reporting_currency_symbol.' '.sprintf('%.2f', $total_vat[$reporting_currency]); ?></strong></td>
			</tr>
		<?php
		}
		
		$this->report_table_footer($reporting_currency_symbols);

	}
	
	/**
	 * Output the export button. Called by WooCommerce core.
	 */
	public function get_export_button() {
		do_action('wc_eu_vat_compliance_csv_export_button');
		echo '<a
			class="wceuvat_downloadcsv_summary export_csv"
			href="#"
		>'.__('Export CSV (this table)', 'woocommerce-eu-vat-compliance').'</a>';
	}
	
	/**
	 * Called by WooCommerce core.
	 */
	public function get_chart_legend() {
		return array();
	}

	/**
	 * Called by WooCommerce core.
	 */
	public function get_chart_widgets() {
		return array();
	}
	
	/**
	 * Output the page scaffolding and the report as HTML. This is the main entry point for output.
	 *
	 * @uses $_GET, $_REQUEST - for the start/end/range parameters
	 */
	public function wc_eu_vat_compliance_report() {

		$ranges = $this->get_report_ranges();
		$current_range = empty($_GET['range']) ? 'quarter' : sanitize_text_field($_GET['range']);
		if (!in_array($current_range, array_merge(array_keys($ranges), array('custom')))) $current_range = 'quarter';

		// Populate $this->start_date and $this->end_date
		$this->calculate_current_range($current_range);

		echo "<ul style=\"list-style-type: disc; list-style-position: inside;\">";
		echo '<li>'.sprintf(__("The report below indicates the taxes actually charged on orders, when they were processed at the checkout (subject to later refunds): it does not take into account later alterations manually made to order data, nor manually created orders. This is because it is an audit report. If you want to take into account manual actions/orders, then you should use WooCommerce's built-in report at %s.", 'woocommerce-eu-vat-compliance'), '<a href="'.admin_url('admin.php?page=wc-reports&tab=taxes&report=taxes_by_code').'">'.htmlspecialchars(__('WooCommerce -> Report -> Taxes -> Taxes by code', 'woocommerce-eu-vat-compliance')).'</a>').'</li>';

		echo '<li>'.$this->country_mode_explanation_and_switcher().' '.__('The two can differ for an order in certain circumstances; for example, for cross-border orders if a "Local Pickup" method was used or if you are able to treat the place of supply as the store country up to a threshold.', 'woocommerce-eu-vat-compliance').'</li>';

		$csv_message = apply_filters('wc_eu_vat_compliance_csv_message', '<a href="https://www.simbahosting.co.uk/s3/product/woocommerce-eu-vat-compliance/">'.__('Downloading all orders with VAT data in CSV format is a feature of the Premium version of this plugin.', 'woocommerce-eu-vat-compliance').'</a>');

		echo "<li>$csv_message</li>";

		echo "<li>".__('The refund column in the table and CSV download is calculated from WooCommerce refunds.', 'woocommerce-eu-vat-compliance').' <a href="#" onclick="jQuery(this).hide(); jQuery(\'#wceuvat_refunds_moreexplanation\').fadeIn(); return false;">'.ucfirst(__('more information', 'woocommerce-eu-vat-compliance')).'...</a>'.'<span id="wceuvat_refunds_moreexplanation" style="display:none;"> '.__('These can be complete or partial refunds, and are separate to whether or not you have marked the order status as "refunded"', 'woocommerce-eu-vat-compliance').' (<a href="http://docs.woothemes.com/document/woocommerce-refunds/">'.__('more information', 'woocommerce-eu-vat-compliance').'</a>). '.__('Note that the refund column only includes refunds made within the chosen date range.', 'woocommerce-eu-vat-compliance')." ".__('i.e. This is a true VAT report for the chosen period.', 'woocommerce-eu-vat-compliance')." ".__('If you want to download data that includes refunds made at any time, then the best option is to choose a date range up until the current time, download the data by CSV, and perform spreadsheet calculations on the rows whose order date matches the period you are interested in.', 'woocommerce-eu-vat-compliance')."</span></li>";
		
		echo "<li>".sprintf(__('The "Items (pre-VAT)" column (which is hidden until you press %s) indicates the total of items found in the order, and does not take account of whether any of those items were refunded (this is related to the fact that in WooCommerce, refunds can be made that are against the order and not against any particular items). As such, it is not necessarily equal to the total amount that VAT is liable on.', 'woocommerce-eu-vat-compliance'), '<a href="#" id="show-items-pre-vat-column" onclick="jQuery(\'.wceuvat_itemsdata\').slideDown(); wceuvat_itemsdata_show=true; return false;">'.__('here', 'woocommerce-eu-vat-compliance').'</a>')."</li>";
		
		// N.B. Not 100% true... if the "items" column is close enough, we use that, to avoid people getting confused about the rounding.
		echo "<li>".__('The "VAT-able supplies" column may (depending on various complexities relating to how WooCommerce handles refunds) be a calculated column, derived by dividing the "Total VAT" column by the VAT rate.', 'woocommerce-eu-vat-compliance')."</li>";

		do_action('wc_eu_vat_compliance_report_notes', $this);
		
		echo "</ul>";

		$this->enqueue_tablesorter_and_datepicker_files();
		
		?>

		<form id="wceuvat_report_form" method="post" style="padding-bottom:8px;">
			<?php
				$print_fields = array('page', 'tab', 'report', 'chart');
				$hidden_tab_value = 'reports';
				foreach ($print_fields as $field) {
					if (isset($_REQUEST[$field])) {
						if ('tab' == $field) $hidden_tab_value = 'taxes';
						echo '<input type="hidden" name="'.$field.'" value="'.esc_attr($_REQUEST[$field]).'">'."\n";
					}
				}

				echo '<input type="hidden" name="tab" value="'.$hidden_tab_value.'">'."\n";

				if (empty($this->start_date))
					$this->start_date = strtotime(date('Y-01-01', current_time('timestamp')));
				if (empty($this->end_date))
					$this->end_date = strtotime(date('Y-m-d 23:59:59', current_time('timestamp')));

			?>

			<p>

				<input type="checkbox" id="wc_vat_csv_anonymised" value="1" checked="checked"><label for="wc_vat_csv_anonymised"><?php _e('Anonymize any personal data when downloading a CSV?', 'woocommerce-eu-vat-compliance');?></label><br>
			
				<?php
			
				// Paint the status-selector checkboxes
			
				_e('Include statuses (updates instantly):', 'woocommerce-eu-vat-compliance');

				$statuses = WooCommerce_EU_VAT_Compliance()->order_status_to_text(true);

				$default_statuses = array('wc-processing', 'wc-completed');

				foreach ($statuses as $label => $text) {

					$use_label = ('wc-' === substr($label, 0, 3)) ? substr($label, 3) : $label;
					$checked = (!isset($_REQUEST['wceuvat_go']) && !isset($_REQUEST['range'])) ? (in_array($label, $default_statuses) ? ' checked="checked"' : '') : ((isset($_REQUEST['order_statuses']) && is_array($_REQUEST['order_statuses']) && in_array($use_label, $_REQUEST['order_statuses'])) ? ' checked="checked"' : '');

					echo "\n".'<input type="checkbox"'.$checked.' class="wceuvat_report_status" name="order_statuses[]" id="order_status_'.$use_label.'" value="'.$use_label.'"><label for="order_status_'.$use_label.'" style="margin-right: 10px;">'.$text.'</label> ';
				}

				echo '<br>';
				
				echo $this->country_mode_explanation_and_switcher();
				
			?>
			
			</p>

		</form>

		<div style="max-width:1160px;">

		<?php

		$this->include_report($ranges, $current_range);

		echo '</div>';

	}

	/**
	 * Return the HTML for the country mode explanation and switching link.
	 *
	 * @return String
	 */
	private function country_mode_explanation_and_switcher() {
		$country_mode = empty($_REQUEST['country_mode']) ? 'reporting' : $_REQUEST['country_mode'];
		if ('taxation' != $country_mode) $country_mode = 'reporting';
		$country_other_mode = ('taxation' == $country_mode) ? 'reporting' : 'taxation';
		
		$country_used = ('taxation' == $country_mode) ? __("the customer's taxation country", 'woocommerce-eu-vat-compliance') : __("the country which the tax is due to", 'woocommerce-eu-vat-compliance');
		
		$country_switch = ('taxation' == $country_mode) ? __("Press here to switch to the country which the tax is due to.", 'woocommerce-eu-vat-compliance') : __("Press here to switch to the customer's taxation country.", 'woocommerce-eu-vat-compliance');
		
		$ret = sprintf(__('The "country" shown below is %s.', 'woocommerce-eu-vat-compliance').' ', $country_used);
		
		$ret .= sprintf('<a href="#" data-mode="%s" class="wc_vat_country_mode_switch">%s</a>', $country_other_mode, $country_switch);
		
		return $ret;
	}
	
	/**
	 * Enqueue JavaScript and CSS for tablesorter and datepicker
	 */
	private function enqueue_tablesorter_and_datepicker_files() {
		$script = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? 'jquery.tablesorter.js' : 'jquery.tablesorter.min.js';
		$widgets_script = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? 'jquery.tablesorter.widgets.js' : 'jquery.tablesorter.widgets.min.js';
		$widget_output_script = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? 'widget-output.js' : 'widget-output.min.js';

		wp_register_script('jquery-tablesorter', WC_VAT_COMPLIANCE_URL.'/js/'.$script, array('jquery'), '2.31.3', true);
		wp_register_script('jquery-tablesorter-widgets', WC_VAT_COMPLIANCE_URL.'/js/'.$widgets_script, array('jquery-tablesorter'), '2.30.0', true);
		wp_register_script('jquery-tablesorter-widget-output', WC_VAT_COMPLIANCE_URL.'/js/'.$widget_output_script, array('jquery-tablesorter-widgets'), '2.29.0', true);

		wp_enqueue_style('tablesorter-style-jui', WC_VAT_COMPLIANCE_URL.'/css/tablesorter-theme.jui.css', array(), '2.31.3');
		wp_enqueue_script('jquery-tablesorter-widget-output');
		
		wp_enqueue_script('jquery-ui-datepicker', array('jquery'), 1, true);

	}
	
	/**
	 * A public function, so that it can be called externally, whilst having $this set up correctly for the things that the included PHP will call.
	 * This method indirectly prints the actual report table itself (not the outer-scaffolding); WooCommerce will call back into get_main_chart().
	 *
	 * @param Array	 $ranges
	 * @param String $current_range
	 */
	public function include_report($ranges, $current_range) {
		// This variable is used by the included WC file below, as are the two parameters, so do not remove any on account of apparent non-use.
		$hide_sidebar = true;
		include(WooCommerce_EU_VAT_Compliance()->wc->plugin_path().'/includes/admin/views/html-report-by-date.php');
	}
	
	/**
	 * Get the current range and calculate the start and end dates. Method from Diego Zanella. Populates $this->start_date and $this->end_date.
	 *
	 * @param String $current_range
	 */
	public function calculate_current_range($current_range) {
		$this->chart_groupby = 'month';
		switch ($current_range) {
			case 'year_to_date':
				$this->start_date = strtotime(date('Y-01-01', current_time('timestamp')));
				$this->end_date = strtotime(date('Y-m-d', 86400+current_time('timestamp')));
				return;
			break;
			case 'quarter_before_previous':
				$month = date('m', strtotime('-6 MONTH', current_time('timestamp')));
				$year  = date('Y', strtotime('-6 MONTH', current_time('timestamp')));
			break;
			case 'previous_quarter':
				$month = date('m', strtotime('-3 MONTH', current_time('timestamp')));
				$year  = date('Y', strtotime('-3 MONTH', current_time('timestamp')));
			break;
			case 'quarter':
				$month = date('m', current_time('timestamp'));
				$year  = date('Y', current_time('timestamp'));
			break;
			default:
				$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-01-01', current_time('timestamp'));
				$this->start_date = strtotime($start_date);
				$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : date('Y-m-d', 86400+current_time('timestamp'));
				$this->end_date = strtotime($end_date);
				return;
			break;
		}

		if ($month <= 3) {
			$this->start_date = strtotime($year . '-01-01');
			$this->end_date = strtotime(date('Y-m-t', strtotime($year . '-03-01')));
		} elseif ($month > 3 && $month <= 6) {
			$this->start_date = strtotime($year . '-04-01');
			$this->end_date = strtotime(date('Y-m-t', strtotime($year . '-06-01')));
		} elseif ($month > 6 && $month <= 9) {
			$this->start_date = strtotime($year . '-07-01');
			$this->end_date = strtotime(date('Y-m-t', strtotime($year . '-09-01')));
		} elseif ($month > 9) {
			$this->start_date = strtotime($year . '-10-01');
			$this->end_date = strtotime(date('Y-m-t', strtotime($year . '-12-01')));
		}
	}
	
	/**
	 * Returns an array of ranges that are used to produce the reports. This method from Diego Zanella.
	 *
	 * Public because of external code (i.e. outside this plugin) which calls into it.
	 *
	 * @return Array
	 */
	public function get_report_ranges() {
		$ranges = array('custom' => __('Custom', 'woocommerce-eu-vat-compliance'));

		$current_time = current_time('timestamp');
		$label_fmt = _x('Q%d %d', 'Q for quarter (date); e.g. Q1 2014', 'woocommerce-eu-vat-compliance');

		// Current quarter
		$quarter = ceil(date('m', $current_time) / 3);
		$year = date('Y');
		$ranges['quarter'] = sprintf($label_fmt, $quarter, $year);

		// Quarter before this one
		$month = date('m', strtotime('-3 MONTH', $current_time));
		$year  = date('Y', strtotime('-3 MONTH', $current_time));
		$quarter = ceil($month / 3);
		$ranges['previous_quarter'] = sprintf($label_fmt, $quarter, $year);

		// Two quarters ago
		$month = date('m', strtotime('-6 MONTH', $current_time));
		$year  = date('Y', strtotime('-6 MONTH', $current_time));
		$quarter = ceil($month / 3);
		$ranges['quarter_before_previous'] = sprintf($label_fmt, $quarter, $year);

		// Year-to-date: added for convenience for users with annual thresholds to monitor
		$month = 1;
		$year  = date('Y', $current_time);
		$ranges['year_to_date'] = __('This year', 'woocommerce-eu-vat-compliance');
		
		return array_reverse($ranges);
	}
	
	/**
	 * Called internally to output the HTML for the report table's footer, including JavaScript for interacting with tablesorter.
	 *
	 * @uses $_REQUEST
	 *
	 * @param Array $reporting_currency_symbols - array of currencies and symbols
	 */
	protected function report_table_footer($reporting_currency_symbols) {

		WooCommerce_EU_VAT_Compliance()->enqueue_jquery_ui_style();

		?>
		</tbody>
		</table>

		<script>
			jQuery(function($) {

				$('.wc_vat_country_mode_switch').on('click', function() {
					var country_mode = $(this).data('mode');
					var href = window.location.href;
					if (href.includes('?')) {
						href += '&';
					} else {
						href += '?';
					}
					href = href.replace(/\&country_mode=([a-z]+)/, '');
					href += "country_mode="+country_mode;
					window.location.assign(href);
					return false;
				});
			
				$('.stats_range .wceuvat_downloadcsv_summary').on('click', function() {
					$('#wc_eu_vat_compliance_report').trigger('outputTable');
					return false;
				});

				var currency_symbols = <?php echo json_encode($reporting_currency_symbols); ?>;
				var tablesorter_created = 0;

				// This function updates the table based on what order statuses were chosen; it also copies the order status checkboxes into the form in the table, so that they are retained when that form is submitted.
				function update_table() {
					// Hide them all, then selectively re-show
					$('#wc_eu_vat_compliance_report tbody tr.statusrow').hide();
					// Get the checked statuses
					// These values are keyed by reporting currency
					var total_vat_items = [];
					var total_vat_shipping = [];
					var total_vat = [];
					var total_vat_refunds = [];
					var total_items = [];
					var total_vatable_supplies = [];
					$('.stats_range input[name="order_statuses[]"]').remove();
					$('#wceuvat_report_form input.wceuvat_report_status').each(function(ind, item) {
						var status_id = $(item).attr('id');
						if (status_id.substring(0, 13) == 'order_status_' && $(item).prop('checked')) {
							var status_label = status_id.substring(13);
							$('.stats_range form').append('<input class="wceuvat_report_status_hidden" type="hidden" name="order_statuses[]" value="'+status_label+'">');
							var row_items = $('#wc_eu_vat_compliance_report tbody tr.status-'+status_label);
							$(row_items).show();
							$(row_items).each(function(cind, citem) {
								var currency = $(citem).data('reporting-currency');
								
								if (!total_vat.hasOwnProperty(currency)) {
									total_vat_items[currency] = 0;
									total_vat_shipping[currency] = 0;
									total_vat[currency] = 0;
									total_vat_refunds[currency] = 0;
									total_items[currency] = 0;
									total_vatable_supplies[currency] = 0;
								}
								
								var items = parseFloat($(citem).data('items'));
								var vatable_supplies = parseFloat($(citem).data('vatable-supplies'));
								var vat_items = parseFloat($(citem).data('vat-items'));
								var vat_shipping = parseFloat($(citem).data('vat-shipping'));
								var vat_refunds = parseFloat($(citem).data('vat-refunds'));
								var vat = vat_items + vat_shipping;
								total_items[currency] += items;
								total_vat[currency] += vat;
								total_vat[currency] += vat_refunds;
								total_vat_items[currency] += vat_items;
								total_vat_shipping[currency] += vat_shipping;
								total_vat_refunds[currency] += vat_refunds;
								total_vatable_supplies[currency] += vatable_supplies;
							});
						};
					});

					// Rebuild totals
					$('.wc_eu_vat_compliance_totals').remove();
					$('#wc_eu_vat_compliance_report').append('<tbody class="avoid-sort wc_eu_vat_compliance_totals"></tbody>');
					
					for (const currency in currency_symbols) {
					
						var currency_symbol = currency_symbols[currency];
					
						if (!total_items.hasOwnProperty(currency)) { continue; }
					
						var totals_html = '\
			<tr class="wc_eu_vat_compliance_total" id="wc_eu_vat_compliance_total">\
				<td><strong><?php echo __('Grand Total', 'woocommerce-eu-vat-compliance');?> ('+currency_symbol+')</strong></td>\
				<td>-</td>\
				<?php
					echo "<td class=\"wceuvat_itemsdata\"><strong>'+currency_symbol+' '+parseFloat(total_items[currency]).toFixed(2)+'</strong></td>\\";
				?>
				<td><strong>'+currency_symbol+' '+parseFloat(total_vatable_supplies[currency]).toFixed(2)+'</strong></td>\
				<td>-</td>\
				<td><strong>'+currency_symbol+' '+parseFloat(total_vat_items[currency]).toFixed(2)+'</strong></td>\
				<td><strong>'+currency_symbol+' '+parseFloat(total_vat_shipping[currency]).toFixed(2)+'</strong></td>\
				<?php
					echo "<td class=\"wceuvat_refundsdata\"><strong>'+currency_symbol+' '+parseFloat(total_vat_refunds[currency]).toFixed(2)+'</strong></td>\\";
				?>
				<td><strong>'+currency_symbol+' '+parseFloat(total_vat[currency]).toFixed(2)+'</strong></td>\
			</tr>\
						';
						
					$('#wc_eu_vat_compliance_report tbody.wc_eu_vat_compliance_totals').append(totals_html);
	// 			<td><strong>'+currency_symbol+' '+parseFloat(total_items).toFixed(2)+'</strong></td>\
				}

					if (typeof wceuvat_itemsdata_show != 'undefined' && wceuvat_itemsdata_show) {
						$('.wceuvat_itemsdata').show();
					} else {
						$('.wceuvat_itemsdata').hide();
					}

					if (!tablesorter_created) {
						$('#wc_eu_vat_compliance_report').tablesorter({
							cssInfoBlock : "avoid-sort",
							theme: 'jui',
							headerTemplate : '{content} {icon}', // needed to add icon for jui theme
							widgets : ['uitheme', 'output'],
							widgetOptions : {
								output_separator     : ',',         // ',' 'json', 'array' or separator (e.g. ';')
// 									output_ignoreColumns : [0],          // columns to ignore [0, 1,... ] (zero-based index)
// 									output_hiddenColumns : false,       // include hidden columns in the output
								output_includeFooter : false,        // include footer rows in the output
// 									output_dataAttrib    : 'data-name', // data-attribute containing alternate cell text
								output_headerRows    : true,        // output all header rows (multiple rows)
								output_delivery      : 'd',         // (p)opup, (d)ownload
								output_saveRows      : 'v',         // (a)ll, (v)isible, (f)iltered or jQuery filter selector
								output_duplicateSpans: true,        // duplicate output data in tbody colspan/rowspan
								output_replaceQuote  : '\u201c;',   // change quote to left double quote
// 								output_includeHTML   : true,        // output includes all cell HTML (except the header cells)
								output_trimSpaces    : true,       // remove extra white-space characters from beginning & end
								output_wrapQuotes    : false,       // wrap every cell output in quotes
// 								output_popupStyle    : 'width=580,height=310',
								output_saveFileName  : 'woocommerce-vat-summary.csv',
								// callbackJSON used when outputting JSON & any header cells has a colspan - unique names required
// 									output_callbackJSON  : function($cell, txt, cellIndex) { return txt + '(' + cellIndex + ')'; },
								// callback executed when processing completes
								// return true to continue download/output
								// return false to stop delivery & do something else with the data
// 									output_callback      : function(config, data) { return true; },

								// the need to modify this for Excel no longer exists
// 									output_encoding      : 'data:application/octet-stream;charset=utf8,'

							}
						});
						tablesorter_created = 1;
					}

				};

				update_table();

				$('#wceuvat_report_form .wceuvat_report_status').on('change', function() { update_table(); });
				<?php
					$base_url = esc_url(admin_url('admin.php?page='.$_REQUEST['page']));
					if ('wc_eu_vat_compliance_cc' == $_REQUEST['page']) $base_url .= '&tab=reports';
					// WC 4.0 wants to see these +
					if ('wc-reports' == $_REQUEST['page']) $base_url .= '&tab=taxes';
					if (isset($_REQUEST['report']) && 'eu_vat_report' == $_REQUEST['report']) $base_url .= '&report=eu_vat_report';
				?>
				$('.stats_range li a').on('click', function(e) {
					var href = $(this).attr('href');
					var get_range = href.match(/range=([_A-Za-z0-9]+)/);

					if (get_range instanceof Array) {
						var range = get_range[1];
						var newhref = '<?php echo $base_url;?>&range='+range;
// 						e.preventDefault();
						var st_id = 0;
						$('#wceuvat_report_form input.wceuvat_report_status').each(function(ind, item) {
							var status_id = $(item).attr('id');
							if ('order_status_' == status_id.substring(0, 13) && $(item).prop('checked')) {
								var status_label = status_id.substring(13);
								newhref += '&order_statuses['+st_id+']='+status_label;
								st_id++;
							}
						});
						// This feels hacky, but appears to be acceptable
						$(this).attr('href', newhref);
					}
				});
			});
		</script>
	<?php
	}

	/**
	 * Called internally to output the HTML for the report table's header
	 */
	protected function report_table_header() {
	/* <th><?php _e('Items (pre-VAT)', 'woocommerce-eu-vat-compliance');?></th> */
	?>
		<table class="widefat" id="wc_eu_vat_compliance_report">
		<thead>
			<tr>
				<th><?php _e('Order Status', 'woocommerce-eu-vat-compliance');?></th>
				<th><?php _e('Country', 'woocommerce-eu-vat-compliance');?></th>
				<th class="wceuvat_itemsdata"><?php _e('Items (pre-VAT)', 'woocommerce-eu-vat-compliance');?></th>
				<th><?php _e('VAT-able supplies', 'woocommerce-eu-vat-compliance');?></th>
				<th><?php _e('VAT rate', 'woocommerce-eu-vat-compliance');?></th>
				<th><?php _e('VAT (items)', 'woocommerce-eu-vat-compliance');?></th>
				<th><?php _e('VAT (shipping)', 'woocommerce-eu-vat-compliance');?></th>
				<th class="wceuvat_refundsdata" title="<?php echo esc_attr(__("N.B. This column shows (only) amounts that were refunded using WooCommerce's refunds feature within the chosen date range - whether the WooCommerce order status is 'refunded' or not, and independently of whether the order that the refund corresponds to is within the same date range.", 'woocommerce-eu-vat-compliance'));?>"><?php _e('VAT refunded', 'woocommerce-eu-vat-compliance');?></th>
				<th><?php _e('Total VAT', 'woocommerce-eu-vat-compliance');?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th><?php _e('Order Status', 'woocommerce-eu-vat-compliance');?></th>
				<th><?php _e('Country', 'woocommerce-eu-vat-compliance');?></th>
				<th class="wceuvat_itemsdata"><?php _e('Items (pre-VAT)', 'woocommerce-eu-vat-compliance');?></th>
				<th><?php _e('VAT-able supplies', 'woocommerce-eu-vat-compliance');?></th>
				<th><?php _e('VAT rate', 'woocommerce-eu-vat-compliance');?></th>
				<th><?php _e('VAT (items)', 'woocommerce-eu-vat-compliance');?></th>
				<th><?php _e('VAT (shipping)', 'woocommerce-eu-vat-compliance');?></th>
				<th class="wceuvat_refundsdata" title="<?php echo esc_attr(__("N.B. This column shows (only) amounts that were refunded using WooCommerce's refunds feature - whether the WooCommerce order status is 'refunded' or not, and independently of whether the order that the refund corresponds to is within the same date range.", 'woocommerce-eu-vat-compliance'));?>"><?php _e('VAT refunded', 'woocommerce-eu-vat-compliance');?></th>
				<th><?php _e('Total VAT', 'woocommerce-eu-vat-compliance');?></th>
			</tr>
		</tfoot>
		<tbody>
	<?php
	}
	
}
