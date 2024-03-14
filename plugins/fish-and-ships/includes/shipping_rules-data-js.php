<?php
/**
 * The Javascript data object. 
 *
 * @package Fish and Ships
 * @version 1.0.0
 * @version 1.4.13
 */

defined( 'ABSPATH' ) || exit;

global $Fish_n_Ships;

// get all selection methods
$selection_methods = apply_filters('wc_fns_get_selection_methods', array());
$actions = apply_filters('wc_fns_get_actions', array());

// get an empty row HTML for the "add new rule" action

	// request the cells info 
	$empty_row = $Fish_n_Ships->shipping_rules_table_cells();

	// The new selector rule type since 1.4.0
	$empty_row['check-column']['content'] = str_replace( '[rule_type_selector]', $Fish_n_Ships->get_rule_type_selector_html(0), $empty_row['check-column']['content'] );

	//the [selectors] and [actions] tokens will be removed here:
	$empty_row['selection-rules-column']['content'] = str_replace('[selectors]', '', $empty_row['selection-rules-column']['content']);
	$empty_row['selection-rules-column']['content'] = str_replace('[logical_operators]', $Fish_n_Ships->get_logical_operator_html( 0, array() ), $empty_row['selection-rules-column']['content']);
	$empty_row['special-actions-column']['content'] = str_replace('[actions]', '', $empty_row['special-actions-column']['content']);

	//the cost method and fields will be inserted:
	$empty_row['shipping-costs-column']['content'] = str_replace(
								'[cost_input_fields]', 
								apply_filters( 'wc_fns_get_html_price_fields', '', 0, array() ),
								$empty_row['shipping-costs-column']['content']);

	$empty_row['shipping-costs-column']['content'] = str_replace(
								'[cost_method_field]',
								$Fish_n_Ships->get_cost_method_html(0, ''), 
								$empty_row['shipping-costs-column']['content']);


	$wrapper = array ( 'class' => 'fns-ruletype-unknown' );

	//...and parse it as HTML
	$empty_row = apply_filters('wc_fns_shipping_rules_table_row_html', array( 'wrapper' => $wrapper, 'cells' => $empty_row ) ); 


// all into a data array:
$data = array(
	
	'id' => $Fish_n_Ships->id,
	'version' => WC_FNS_VERSION,
	'im_pro' => $Fish_n_Ships->im_pro(),
	'empty_row_html' => $empty_row,
	'new_selection_method_html' => str_replace('[selection_details]', '', $Fish_n_Ships->get_selector_method_html(0, $selection_methods)),
	'new_action_html' => str_replace('[action_details]', '', $Fish_n_Ships->get_action_method_html(0, $actions)),
	
	'i18n_where' => _x('WHERE', 'VERY shorted, logic operator (maybe better leave in english)', 'fish-and-ships'),
	'i18n_and' => _x('AND', 'VERY shorted, logic operator (maybe better leave in english)', 'fish-and-ships'),
	'i18n_or' => _x('OR', 'VERY shorted, logic operator (maybe better leave in english)', 'fish-and-ships'),
	'i18n_unsaved' => __('You have unsaved changes. If you proceed, they will be lost.'),
	
	'i18n_fns_integer_error' => __('Please enter without decimals or thousand separators.', 'fish-and-ships'),
	'i18n_fns_groupby_error' => __('Attention: the selection criterion "no grouping" in "cart items" will compare recursively quantity = 1 in the rules: [ %rules% ]. Please change the grouping criterion (generally to "all grouped together"). Save anyway?', 'fish-and-ships'),
	'i18n_fns_always_error'  => __('Attention: "Always" selector combined with any other in the same rule are superfluous, in the rules: [ %rules% ]. Save anyway?', 'fish-and-ships'),
	'i18n_fns_math_ignore'   => __('Attention: Math expression will overwrite previous the calculated rates in the same rule. Affected rules: [ %rules% ]. You can use the variable "rule_cost" in the expression. Save anyway?', 'fish-and-ships'),
	
	'i18n_min_val_info_ge' =>  _x('Will match values EQUAL TO OR GREATER THAN, e.g., put 0 and it will match from 0, but not -0.1 or -1', 'Min field tip, greater or equal option', 'fish-and-ships'),
	'i18n_min_val_info_greater' =>  _x('Will match values GREATER THAN, e.g. put 0 and it will match from 0.1 or 1, but not 0', 'Min field tip, greather than option', 'fish-and-ships'),
	'i18n_max_val_info_less' =>  _x('Will match values LESS THAN, e.g., put 100 to match from Min to 99.99, not 100', 'Max field tip, less option', 'fish-and-ships'),
	'i18n_max_val_info_le' =>  _x('Will match values LESS THAN OR EQUAL TO, e.g., put 100 and it will match From Min to 100, not 100.01', 'Max field tip, less or equal option', 'fish-and-ships'),

	'i18n_min_val_info_daymonth_ge'      => _x('Put a number between 1 and 31. Zero acts as a wildcard.', 'Day of the month selector', 'fish-and-ships'),
	'i18n_min_val_info_daymonth_greater' => _x('Put a number between 1 and 31. Zero acts as a wildcard.', 'Day of the month selector', 'fish-and-ships'),
	'i18n_max_val_info_daymonth_less'    => _x('Put a number between 1 and 31. Zero acts as a wildcard.', 'Day of the month selector', 'fish-and-ships'),
	'i18n_max_val_info_daymonth_le'      => _x('Put a number between 1 and 31. Zero acts as a wildcard.', 'Day of the month selector', 'fish-and-ships'),
	
	'i18n_min_val_info_time_ge'      => _x('Put a time between 00:00 and 23:59', 'Time selector', 'fish-and-ships'),
	'i18n_min_val_info_time_greater' => _x('Put a time between 00:00 and 23:59', 'Time selector', 'fish-and-ships'),
	'i18n_max_val_info_time_less'    => _x('Put a time between 00:00 and 23:59', 'Time selector', 'fish-and-ships'),
	'i18n_max_val_info_time_le'      => _x('Put a time between 00:00 and 23:59', 'Time selector', 'fish-and-ships'),
	
	'i18n_min_val_info_year_ge'      => sprintf(_x('Four digits year, like %s. Zero acts as a wildcard.', 'Day of the year selector', 'fish-and-ships'), date('Y')),
	'i18n_min_val_info_year_greater' => sprintf(_x('Four digits year, like %s. Zero acts as a wildcard.', 'Day of the year selector', 'fish-and-ships'), date('Y')),
	'i18n_max_val_info_year_less'    => sprintf(_x('Four digits year, like %s. Zero acts as a wildcard.', 'Day of the year selector', 'fish-and-ships'), date('Y')),
	'i18n_max_val_info_year_le'      => sprintf(_x('Four digits year, like %s. Zero acts as a wildcard.', 'Day of the year selector', 'fish-and-ships'), date('Y')),
	
	
	'i18n_min_val_info_action' =>  _x('If the calculated rule cost is less than this, it will be set to this value.', 'Min field tip', 'fish-and-ships'),
	'i18n_max_val_info_action' =>  _x('If the calculated rule cost is greater than this, it will be set to this value.', 'Max field tip', 'fish-and-ships'),
	
	'i18n_export_tit' => _x('Export shipping settings', 'Title', 'fish-and-ships'),
	'i18n_export_ins' => _x('Copy this code, and paste on another Fish and Ships method:', 'Export instructions', 'fish-and-ships'),
	'i18n_import_tit' => _x('Import shipping settings', 'Title', 'fish-and-ships'),
	'i18n_import_ins' => _x('Paste the exported code here and click import button:', 'Import instructions', 'fish-and-ships'),
	'i18n_import_att' => _x('Current settings will be overwritten', 'Import warning', 'fish-and-ships'),
	'i18n_import_err' => _x('Error: maybe the code is incomplete or corrupted?
Parser error message:', 'Import error', 'fish-and-ships'),
		
	'i18n_import_bt' => __('Import settings', 'fish-and-ships'),
	'i18n_cancel_bt' => __('Cancel', 'woocommerce'),
	'i18n_close_bt'  => __('Close', 'fish-and-ships'), 
	
	'ajax_url_main_lang' => $Fish_n_Ships->get_unlocalised_ajax_url(), // main site lang attribute will be added on multiligual
	'admin_lang' => function_exists('get_user_locale') ? get_user_locale() : get_locale(), // The preferred language to show the help
	'help_url' => WC_FNS_URL . 'help/'
);

// the html code details for each selection method
foreach ($selection_methods as $method_id=>$method) {
	if ( $Fish_n_Ships->im_pro() || !$method['onlypro'] ) {
		$data['selection_' . $method_id . '_html'] = apply_filters('wc_fns_get_html_details_method', '', 0, 0, $method_id, array(), true );
	}
}

// the html code details for each action
foreach ($actions as $action_id=>$action) {
	if ( $Fish_n_Ships->im_pro() || !$action['onlypro'] ) {
		$data['action_' . $action_id . '_html'] = apply_filters('wc_fns_get_html_details_action', '', 0, 0, $action_id, array() );
	}
}

return $data;
