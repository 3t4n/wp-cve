<?php 

class directorypress_field_price extends directorypress_field {
	public $currency_symbol = '$';
	public $decimal_separator = ',';
	public $thousands_separator = ' ';
	public $symbol_position = 1;
	public $hide_decimals = 0;
	public $has_input_options = 0;
	public $has_frontend_currency = 0;
	public $price_field_type = 1;
	public $range_options = array();
	public $data = array();
	protected $is_configuration_page = true;
	protected $is_search_configuration_page = true;
	protected $can_be_searched = true;
	
	public function is_field_not_empty($listing) {
		if ($this->data && !empty($this->data['value'])) {
			return true;
		} else {
			return false;
		}
	}
	
	public function __construct() {
		// adapted for WPML
		add_action('init', array($this, 'range_options_to_string'));
	}

	public function configure($id, $action = '') {
		global $wpdb, $directorypress_object;

		if ($action == 'config') {
			$validation = new directorypress_form_validation();
			$validation->set_rules('currency_symbol', __('Currency symbol', 'DIRECTORYPRESS'), 'required');
			$validation->set_rules('decimal_separator', __('Decimal separator', 'DIRECTORYPRESS'), 'required|max_length[1]');
			$validation->set_rules('thousands_separator', __('Thousands separator', 'DIRECTORYPRESS'), 'max_length[1]');
			$validation->set_rules('symbol_position', __('Currency symbol position', 'DIRECTORYPRESS'), 'integer');
			$validation->set_rules('hide_decimals', __('Hide decimals', 'DIRECTORYPRESS'), 'required');
			$validation->set_rules('has_input_options', __('Has input options', 'DIRECTORYPRESS'), 'is_checked');
			$validation->set_rules('has_frontend_currency', __('Has Frontend Currency', 'DIRECTORYPRESS'), 'is_checked');
			$validation->set_rules('price_field_type', __('Price Field Type', 'DIRECTORYPRESS'), 'integer');
			$validation->set_rules('range_options[]', __('Range options', 'DIRECTORYPRESS'));
			if ($validation->run()) {
				if ( current_user_can( 'manage_options' ) ) {
					$result = $validation->result_array();
					if ($wpdb->update($wpdb->directorypress_fields, array('options' => serialize(
							array(
									'currency_symbol' => $result['currency_symbol'],
									'decimal_separator' => $result['decimal_separator'],
									'thousands_separator' => $result['thousands_separator'],
									'symbol_position' => $result['symbol_position'],
									'hide_decimals' => $result['hide_decimals'],
									'has_input_options' => $result['has_input_options'],
									'has_frontend_currency' => $result['has_frontend_currency'],
									'price_field_type' => $result['price_field_type'],
									'range_options' => $result['range_options[]'],
							)
						)), array('id' => $id), null, array('%d'))) {
							directorypress_add_notification(__('Field configuration was updated successfully!', 'DIRECTORYPRESS'));
					}
				}else{
					directorypress_add_notification(__('no permission!', 'DIRECTORYPRESS'), 'error');
				}
			} else {
				$this->currency_symbol = $validation->result_array('currency_symbol');
				$this->decimal_separator = $validation->result_array('decimal_separator');
				$this->thousands_separator = $validation->result_array('thousands_separator');
				$this->symbol_position = $validation->result_array('symbol_position');
				$this->hide_decimals = $validation->result_array('hide_decimals');
				$this->has_input_options = $validation->result_array('has_input_options');
				$this->has_frontend_currency = $validation->result_array('has_frontend_currency');
				$this->price_field_type = $validation->result_array('price_field_type');
				$this->range_options = $validation->result_array('range_options[]');
				directorypress_add_notification($validation->error_array(), 'error');

				$field = $this;
				include('_html/configuration.php');
			}
		} else{
			$field = $this;
			include('_html/configuration.php');
		}
	}
	
	public function build_field_options() {
		if (isset($this->options['currency_symbol'])) {
			$this->currency_symbol = $this->options['currency_symbol'];
		}
		if (isset($this->options['decimal_separator'])) {
			$this->decimal_separator = $this->options['decimal_separator'];
		}
		if (isset($this->options['thousands_separator'])) {
			$this->thousands_separator = $this->options['thousands_separator'];
		}
		if (isset($this->options['symbol_position'])) {
			$this->symbol_position = $this->options['symbol_position'];
		}
		if (isset($this->options['hide_decimals'])) {
			$this->hide_decimals = $this->options['hide_decimals'];
		}
		if (isset($this->options['has_input_options'])) {
			$this->has_input_options = $this->options['has_input_options'];
		}
		if (isset($this->options['has_frontend_currency'])) {
			$this->has_frontend_currency = $this->options['has_frontend_currency'];
		}	
		if(isset($this->options['price_field_type'])) {
			$this->price_field_type = $this->options['price_field_type'];
		}
		if(isset($this->options['range_options'])) {
			$this->range_options = $this->options['range_options'];
		}
		
	}
	
	public function renderInput() {
		if(!isset($this->data['value'])){
			$this->data['value'] = '';
		}
		if(!isset($this->data['frontend_currency'])){
			$this->data['frontend_currency'] = '';
		}
		if(!isset($this->data['option_selection'])){
			$this->data['option_selection'] = '';
		}
		$field = $this;
		
		if($this->price_field_type == 1){
			include('_html/input.php');
		}else{
			include('_html/input-range.php');
		}
	}
	
	public function validate_field_values(&$errors, $data) {
		$field_index = 'directorypress-field-input-' . $this->id;
		if($this->price_field_type == 2){
			$field_index_2 = 'directorypress-field-input-max-' . $this->id;
		}
		$field_options = 'directorypress-field-price-options-' . $this->id;
		$field_frontend_currency = 'directorypress-field-price-frontend-currency-' . $this->id;
		$range_options = 'directorypress-field-price-range-options-' . $this->id;
		$validation = new directorypress_form_validation();
		$validation->set_rules($field_options, $this->name);
		$validation->set_rules($field_frontend_currency, $this->name);
		$validation->set_rules($range_options, $this->name);
		$rules = 'numeric';
		if ($this->is_this_field_requirable() && $this->is_required)
			$rules .= '|required';
		$validation->set_rules($field_index, $this->name, $rules);
		if($this->price_field_type == 2){
			$validation->set_rules($field_index_2, $this->name, $rules);
		}
		if (!$validation->run()) {
			$errors[] = implode("", $validation->error_array());
		}
		$data = array();
		$data['value'] = $validation->result_array($field_index);
		if($this->price_field_type == 2){
			$data['value_2'] = $validation->result_array($field_index_2);
		}
		$data['option_selection'] = $validation->result_array($field_options);
		$data['frontend_currency'] = $validation->result_array($field_frontend_currency);
		$data['range_options'] = $validation->result_array($range_options);
		return $data;
	}
	
	public function save_field_value($post_id, $validation_results) {
		//return update_post_meta($post_id, '_field_' . $this->id, $validation_results);
		if ($validation_results && is_array($validation_results)) {
			update_post_meta($post_id, '_field_' . $this->id, $validation_results['value']);
			update_post_meta($post_id, '_field_' . $this->id . '_max', $validation_results['value_2']);
			update_post_meta($post_id, '_field_' . $this->id . '_range_options', $validation_results['range_options']);
			update_post_meta($post_id, '_field_' . $this->id . '_option_selection', $validation_results['option_selection']);
			update_post_meta($post_id, '_field_' . $this->id . '_frontend_currency', $validation_results['frontend_currency']);
			return true;
		}
	}
	
	public function load_field_value($post_id) {
		
		$this->data['value'] = get_post_meta($post_id, '_field_' . $this->id, true);
		$this->data['value_2'] = get_post_meta($post_id, '_field_' . $this->id . '_max', true);
		$this->data['range_options'] = get_post_meta($post_id, '_field_' . $this->id . '_range_options', true);
		$this->data['option_selection'] = get_post_meta($post_id, '_field_' . $this->id . '_option_selection', true);
		$this->data['frontend_currency'] = get_post_meta($post_id, '_field_' . $this->id . '_frontend_currency', true);
		
		$this->data = apply_filters('directorypress_field_load', $this->data, $this, $post_id);
		return $this->data;
	}
	
	public function display_output($listing = null) {
		$field = $this;
		if($this->price_field_type == 1){
			include('_html/output.php');
		}else{
			include('_html/output_range.php');
		}
	}
	
	public function renderValueOutput($listing = null) {
		$field = $this;
		if($this->price_field_type == 1){
			return $this->formatPrice();
		}else{
			return $this->formatPriceRange();
		}
	}
	
	public function display_outputTooltip($listing = null) {
		
		echo '<span class="price-range simptip simptip-position-top simptip-movable" data-tooltip="'. wp_kses_post($this->formatPriceRange()) .'" data-placement="auto">'. wp_kses_post($this->range_options_out()) .'</span>';
	
	}
	
	public function order_params() {
		global $DIRECTORYPRESS_ADIMN_SETTINGS;
		$order_params = array('orderby' => 'meta_value_num', 'meta_key' => '_field_' . $this->id);
		if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_orderby_exclude_null']){
			$order_params['meta_query'] = array(
				array(
					'key' => '_field_' . $this->id,
					'value'   => array(''),
					'compare' => 'NOT IN'
				)
			);
		}
		return $order_params;
	}
	
	/* public function validate_csv_values($value, &$errors) {
		if (!is_numeric($value)) {
			$errors[] = sprintf(__('The %s field must contain only numbers.', 'DIRECTORYPRESS'), $this->name);
		}

		return $value;
	} */
	
	public function disaply_output_on_map($location, $listing) {
		
		if (is_numeric($this->value)) {
			return $this->formatPrice();
		}
	}
	// adapted for WPML
	public function range_options_to_string() {
		global $sitepress;

		if (function_exists('wpml_object_id_filter') && $sitepress) {
			foreach ($this->range_options AS $key=>&$item) {
				$item = apply_filters('wpml_translate_single_string', $item, 'DirectoryPress', 'The option #' . $key . ' of field #' . $this->id);
			}
		}
	}
	// adapted for WPML
	public function range_options_out() {
		//global $sitepress;

		//if (function_exists('wpml_object_id_filter') && $sitepress) {
			$out = '';
			foreach ($this->range_options AS $key=>&$item) {
				if(selected($this->data['range_options'], $key, false)){
					$out .= $item;
				}
			}
		//}
		return $out;
	}
	public function formatPriceSearch($value = null) {
		if (is_null($value)) {
			$value = $this->data['value'];
		}
		if ($this->hide_decimals) {
			$decimals = 0;
		} else {
			$decimals = 2;
		}
		if(is_numeric($value)){
			$formatted_price = number_format($value, $decimals, $this->decimal_separator, $this->thousands_separator);
		}else{
			$formatted_price = '';
		}
		$out = $formatted_price;
		
		$symbol = $this->currency_symbol;
		$currency_symbol = $symbol;
		switch ($this->symbol_position) {
			case 1:
				$out = $currency_symbol . $out;
				break;
			case 2:
				$out = $currency_symbol. ' ' . $out;
				break;
			case 3:
				$out = $out . $currency_symbol;
				break;
			case 4:
				$out = $out . ' ' . $currency_symbol;
				break;
		}
		
		return $out;
		
		
	}
	public function formatPrice($value = null) {
		if (is_null($value)) {
			$value = $this->data['value'];
		}
		if ($this->hide_decimals) {
			$decimals = 0;
		} else {
			$decimals = 2;
		}
		if(is_numeric($value)){
			$formatted_price = number_format($value, $decimals, $this->decimal_separator, $this->thousands_separator);
		}else{
			$formatted_price = '';
		}
		$out = $formatted_price;
		
		$oncall_string = esc_html__('On Call', 'DIRECTORYPRESS');
		if($this->has_frontend_currency && (isset($this->data['frontend_currency']) && !empty($this->data['frontend_currency']))){
			$symbol = $this->data['frontend_currency'];
		}else{
			$symbol = $this->currency_symbol;
		}
		$currency_symbol = '<span class="currency-symbol">'. esc_html($symbol) .'</span>';
		if($this->has_input_options){
			
			if(isset($this->data['option_selection']) && !empty($this->data['option_selection'])){
				$selection_string = '';
				if($this->data['option_selection'] == 'fixed'){
					$selection_string = esc_html__('Fixed', 'DIRECTORYPRESS');
				}elseif($this->data['option_selection'] == 'negotiable'){
					$selection_string = esc_html__('Negotiable', 'DIRECTORYPRESS');
				}elseif($this->data['option_selection'] == 'oncall'){
					$selection_string = esc_html__('Oncall', 'DIRECTORYPRESS');
				}
				$price_options = '<span class="price-options">'. $selection_string.'</span>';
			}else{
				$price_options = '<span class="price-options">' . esc_html__('Fixed', 'DIRECTORYPRESS') . '</span>';
			}
		}else{
			$price_options = '';
		}
		switch ($this->symbol_position) {
			case 1:
				$out = $currency_symbol . $out . ' ' . $price_options;
				break;
			case 2:
				$out = $currency_symbol. ' ' . $out . $price_options;
				break;
			case 3:
				$out = $price_options . ' ' . $out . $currency_symbol;
				break;
			case 4:
				$out = $price_options . ' ' . $out . ' ' . $currency_symbol;
				break;
		}
		
		if($this->has_input_options && (isset($this->data['option_selection']) && $this->data['option_selection'] == 'oncall')){
			return $oncall_string;
		}elseif(is_numeric($value)){
			return $out;
		}
		
	}
	public function formatPriceRange($value = null, $value_2 = null) {
		if (is_null($value)) {
			$value = $this->data['value'];
		}
		if (is_null($value_2)) {
			$value_2 = $this->data['value_2'];
		}
		if ($this->hide_decimals) {
			$decimals = 0;
		} else {
			$decimals = 2;
		}
		if(!empty($value) && !empty($value_2)){
			$range_split = ' - ';
		}else{
			$range_split = '';
		}
		if(is_numeric($value)){
			$formatted_min_price = number_format($value, $decimals, $this->decimal_separator, $this->thousands_separator);
		}else{
			$formatted_min_price = '';
		}
		if(is_numeric($value_2)){
			$formatted_max_price = number_format($value_2, $decimals, $this->decimal_separator, $this->thousands_separator);
		}else{
			$formatted_max_price = '';
		}
		$min = $formatted_min_price;
		$max = $formatted_max_price;
		
		$oncall_string = esc_html__('On Call', 'DIRECTORYPRESS');
		if($this->has_frontend_currency && (isset($this->data['frontend_currency']) && !empty($this->data['frontend_currency']))){
			$symbol = $this->data['frontend_currency'];
		}else{
			$symbol = $this->currency_symbol;
		}
		$currency_symbol = $symbol;
		/* if($this->has_input_options){
			if(isset($this->data['option_selection']) && !empty($this->data['option_selection'])){
				$price_options = '<span class="price-options">'. $this->data['option_selection'].'</span>';
			}else{
				$price_options = '<span class="price-options">' . esc_html__('Fixed', 'DIRECTORYPRESS') . '</span>';
			}
		}else{
			$price_options = '';
		} */
		$out = '';
		switch ($this->symbol_position) {
			case 1:
				$min_out = $currency_symbol . $min;
				$max_out = (!empty($value_2))? $currency_symbol . $max : '';
				$out = $min_out . $range_split . $max_out;
				break;
			case 2:
				$min_out = (!empty($value))? $currency_symbol. ' ' . $min : '';
				$max_out = (!empty($value_2))? $currency_symbol .' '. $max : '';
				$out = $min_out . $range_split . $max_out;
				break;
			case 3:
				$min_out = (!empty($value))? $min . $currency_symbol : '';
				$max_out = (!empty($value_2))? $max . $currency_symbol : '';
				$out = $max_out . $range_split . $min_out;
				break;
			case 4:
				$min_out = (!empty($value))? $min . ' ' . $currency_symbol : '';
				$max_out = (!empty($value_2))? $max . ' ' . $currency_symbol : '';
				$out = $max_out . $range_split . $min_out;
				break;
		}
		
		
		return $out;
		
		
	}
	public function validate_csv_values($value, &$errors) {
		$output = array();
		$data = explode('-', $value);
		if (isset($data[0])) {
			$output['value'] = $data[0];
			if (isset($data[1])) {
				$output['value_2'] = $data[1];
			}
		}
		return $output;
	}
	public function export_field_to_csv() {
		
		if($this->price_field_type == 1){
			if ($this->data['value']) {
				return $this->data['value'];
			}
		}elseif($this->price_field_type == 2){
			if ($this->data['value'] && $this->data['value_2']) {
				return $this->data['value'].' - '. $this->data['value_2'];
			}
		}
	}
}
?>