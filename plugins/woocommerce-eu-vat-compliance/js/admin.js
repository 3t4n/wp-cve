/**
 * Initialise WooCommerce's enhanced selector widgets
 */
function wc_vat_compliance_initialise_woo_enhanced_selectors() {
	try {
		// The relevant code in WC is re-entrant; it excludes any widgets that were already initialised. Nevertheless we wrap in a try/catch block since there's no guarantee that they will maintain the interface in the same way in future.
		jQuery(document.body).trigger('wc-enhanced-select-init');
	} catch (e) {
		console.log(e);
	}
}

/**
 * Get the HTML for a currency selector select element
 *
 * @param String dom_id
 * @param String name
 * @param String selected_value
 *
 * @return String
 */
function wc_vat_compliance_currency_selector_dropdown(dom_id, name, selected_value) {
	
	// The width is done in a style attribute so that select2 can find it more easily
	html = '<select id="'+dom_id+'" name="'+name+'" class="wc_euvat_currency_selector wc-enhanced-select" style="width: 220px;">';
	
	var currencies = wc_vat_compliance.currency_list;
	
	for (const code in currencies) {
		var selected = (code == selected_value) ? ' selected="selected"' : '';
		html += '<option value="'+code+'"'+selected+'>'+currencies[code]+'</option>';
	}
	
	html += '</select>'+"\n";
	
	return html;
}

jQuery(function($) {
	
	var $container = $('#wceuvat_settings_accordion input[name="woocommerce_prices_include_tax"]').parents('table').first();
	$container.find( 'input, select').prop('disabled', true).css('opacity', 1);
	
	var vat_number_override_index = 0;
	
	/**
	 * Get the HTML for a table row for deleting the row
	 *
	 * @param String label - text to be used
	 *
	 * @return String
	 */
	function delete_row(label) {
		var html = '<a href="#" class="wcvat_delete_table_row">'+label+"\n";
		return html;
	}
	
	$('#wcvat-number-entry-overrides').on('click', '.wcvat_delete_table_row', function() {
		$(this).parents('.wcvat-number-entry-override').first().slideUp(function() { $(this).remove(); });
		return false;
	});
	
	$('#wcvat-exemption-based-on-value-rules').on('click', '.wcvat_delete_table_row', function() {
		$(this).parents('.wcvat-value-based-exemption').first().slideUp(function() { $(this).remove(); });
		return false;
	});
	
	$('#wcvat-tax-class-translations').on('click', '.wcvat_delete_table_row', function() {
		$(this).parents('.wc_vat_tax_class_translation').first().slideUp(function() {
			$(this).remove();
			if (0 == $('#wcvat-tax-class-translations .wc_vat_tax_class_translation').length) { $('#wcvat-tax-class-translations-none').show(); }
		});
		return false;
	});
	
	/**
	 * Return the HTML for a region selector dropdown
	 *
	 * @param String dom_id
	 * @param String name
	 * @param String selected_value
	 *
	 * @return String
	 */
	function region_selector_dropdown(dom_id, name, selected_value) {
		
		html = '<select id="'+dom_id+'" name="'+name+'" class="wc_vat_region_selector">';
		
		for (const region_code in wc_vat_compliance.region_list) {
			var selected = (region_code == selected_value) ? ' selected="selected"' : '';
			html += '<option value="'+region_code+'"'+selected+'>'+wc_vat_compliance.region_list[region_code]+'</option>';
		}
		
		html += '</select>'+"\n";
		
		return html;
	}
	
	/**
	 * Get the HTML for a region selector widget
	 *
	 * @param Integer index
	 * @param String  selected_value
	 *
	 * @return String
	 */
	function region_selector_widget(index, selected_value) {
		
		index = index.toString();
		
		var dom_id = 'wc_vat_region_selector_'+index;
		
		var html = wc_vat_compliance.in_region_use_policy.replace('%s', '<span class="forminp forminp-select">'+region_selector_dropdown(dom_id, 'woocommerce_eu_vat_number_entry_overrides['+index+'][region]', selected_value)+'</span>')+"\n";
		
		return html;
		
	}
	
	/**
	 * Return the HTML for a VAT number policy selector dropdown
	 *
	 * @param String dom_id
	 * @param String name
	 * @param String selected_value
	 *
	 * @return String
	 */
	function vat_number_entry_policy_selector_dropdown(dom_id, name, selected_value) {
		
		html = '<select id="'+dom_id+'" name="'+name+'" class="wc_vat_vat_number_policy_selector">';
		
		for (const policy_id in wc_vat_compliance.vat_number_policies) {
			var selected = (policy_id == selected_value) ? ' selected="selected"' : '';
			html += '<option value="'+policy_id+'"'+selected+'>'+wc_vat_compliance.vat_number_policies[policy_id]+'</option>';
		}
		
		html += '</select>'+"\n";
		
		return html;
	}
	
	/**
	 * Get the HTML for a VAT number policy selector widget
	 *
	 * @param Integer index
	 * @param String  selected_value
	 *
	 * @return String
	 */
	function region_vat_number_entry_policy_widget(index, selected_value) {
		
		index = index.toString();
		
		var dom_id = 'wc_vat_region_selector_'+index;
		
		var html = '<span class="forminp forminp-select">'+"\n";
		
		html += vat_number_entry_policy_selector_dropdown(dom_id, 'woocommerce_eu_vat_number_entry_overrides['+index+'][policy]', selected_value);
		
		html += "</span>\n";
		
		return html;
		
	}
	
	$('#wcvat-new-number-entry-override').on('click', function() {
		
		vat_number_override_index++;
		
		var new_number_override = '<div class="wcvat-number-entry-override">' + region_selector_widget(vat_number_override_index) + region_vat_number_entry_policy_widget(vat_number_override_index) + delete_row(wc_vat_compliance.delete_this_override) + '</div>';
		
		$('#wcvat-number-entry-overrides').append(new_number_override);
		
		return false;
	});
	
	var existing_overrides = $('#wcvat-new-number-entry-override').data('existing-overrides');
	
	if ('object' == typeof existing_overrides) {
		for (const region_code in existing_overrides) {
			
			var new_number_override = '<div class="wcvat-number-entry-override">' + region_selector_widget(vat_number_override_index, region_code) + region_vat_number_entry_policy_widget(vat_number_override_index, existing_overrides[region_code]) + delete_row(wc_vat_compliance.delete_this_override) + '</div>';
			
			$('#wcvat-number-entry-overrides').append(new_number_override);
			
			vat_number_override_index++;
		}
		
		
	}
	
	var value_exemption_index = 0;
	
	if (wc_vat_compliance.hasOwnProperty('vat_exempt_above_options') && wc_vat_compliance.vat_exempt_above_options.hasOwnProperty('cart_vat_exempt_above')) {
		
		for (var i=0; i<wc_vat_compliance.vat_exempt_above_options.cart_vat_exempt_above.length; i++) {
			// This one is painted from the back-end, so should be already present
			if (0 == i) { continue; }
			
			var exempt_above = wc_vat_compliance.vat_exempt_above_options.cart_vat_exempt_above[i];
			var countries = wc_vat_compliance.vat_exempt_above_options.vat_exempt_above_countries[i];
			var currency = wc_vat_compliance.vat_exempt_above_options.vat_exempt_above_currency[i];
			var based_upon = (typeof wc_vat_compliance.vat_exempt_above_options.based_upon[i] === 'undefined') ? 'items_total' : wc_vat_compliance.vat_exempt_above_options.based_upon[i];
			new_value_exemption(exempt_above, currency, countries, based_upon);
		}
		
		if (wc_vat_compliance.vat_exempt_above_options.cart_vat_exempt_above.length > 0) {
			wc_vat_compliance_initialise_woo_enhanced_selectors();
		}
	}
	
	/**
	 * Get the HTML for a country selector select element
	 *
	 * @param String name
	 * @param Array  selected_values
	 *
	 * @return String
	 */
	function country_selector_widget(name, selected_values) {
		
		var ret = wc_vat_compliance.country_menu.replace('__REPLACE__', name+'[]');
		
		if ('undefined' !== typeof selected_values) {
			for (var i=0; i<selected_values.length; i++) {
				var value = selected_values[i];
				ret = ret.replace(' value="'+value+'"', ' value="'+value+'" selected="selected"');
			}
		}
		
		return ret;
		
	}
	
	/**
	 * Get the HTML for a "based upon" selector select element
	 *
	 * @param String index
	 * @param Array  selected_value
	 *
	 * @return String
	 */
	function based_upon_selector_widget(index, selected_value) {
		
		var ret = '<select class="exempt_based_upon" name="woocommerce_eu_vat_cart_vat_exempt_based_upon['+index.toString()+']">'+"\n";
		
		ret += '<option value="items_total"';
		if ('any_item_above' !== selected_value) { ret += ' selected="selected"'; }
		ret += '>'+wc_vat_compliance.items_total+'</option>'+"\n";
		
		ret += '<option value="any_item_above"';
		if ('any_item_above' === selected_value) { ret += ' selected="selected"'; }
		ret += '>'+wc_vat_compliance.any_item_above+'</option>'+"\n";
		
		ret += '</select>'+"\n";
		
		return ret;
		
	}
	
	/**
	 * Get the HTML for a currency selector select element
	 *
	 * @param String dom_id
	 * @param String name
	 * @param String selected_amount
	 * @param String selected_currency
	 *
	 * @return String
	 */
	function value_selector_widgetry(dom_id, name, selected_amount, selected_currency) {
		
		var ret = '<input name="'+name+'" id="'+dom_id+'" type="number" style="width: 84px;" value="'+selected_amount+'" class="" placeholder="">';
		
		ret += ' ';
		
		var currency_dom_id = dom_id.replace(/_(\d+)$/, '_currency_$1');
		if (currency_dom_id == dom_id) { currency_dom_id = dom_id+'_currency'; }
		
		var currency_name = name.replace(/(\[\d+\])$/, '_currency$1');
		
		ret += wc_vat_compliance_currency_selector_dropdown(currency_dom_id, currency_name, selected_currency);
		
		return ret;
		
	}
	
	/**
	 * Add a new value exemption rule
	 *
	 * @param String value
	 * @param String currency_code
	 * @param Array  countries
	 * @param String based_upon - can be 'order' or 'item'
	 */
	function new_value_exemption(value, currency_code, countries, based_upon) {
		
		value_exemption_index++;
		var dom_base = 'woocommerce_eu_vat_cart_vat_exempt_above';
		
		var new_value_exemption = '<div class="wcvat-value-based-exemption">' + value_selector_widgetry(dom_base+'_'+value_exemption_index, dom_base+'['+value_exemption_index+']', value, currency_code) + country_selector_widget('woocommerce_eu_vat_cart_vat_exempt_above_countries['+value_exemption_index+']', countries) + ' ' + based_upon_selector_widget(value_exemption_index, based_upon) + ' ' + delete_row(wc_vat_compliance.delete_this_exemption) + '</div>';
		
		$('#wcvat-exemption-based-on-value-rules').append(new_value_exemption);
		
	}
	
	$('#wcvat-exemption-based-on-value-add').on('click', function() {
		new_value_exemption(150, '');
		wc_vat_compliance_initialise_woo_enhanced_selectors();
		return false;
	});
	
	var tax_class_translation_index = 0;
	
	/**
	 * Add a new tax-class translation rule
	 *
	 * @param String class_from
	 * @param String class_to
	 * @param String currency_code
	 * @param String threshold_value
	 * @param String vat_region
	 */
	function new_tax_class_translation(class_from, class_to, currency_code, threshold_value, vat_region) {
		tax_class_translation_index++;
		
		var new_translation_rule = tax_class_translation_widgetry(tax_class_translation_index, class_from, class_to, currency_code, threshold_value, vat_region);
		
		$('#wcvat-tax-class-translations-none').hide();
		$('#wcvat-tax-class-translations').append(new_translation_rule);
		
	}
	
	/**
	 * Get the HTML for a tax-class translation rule
	 *
	 * @param Integer index
	 * @param String  class_from
	 * @param String  class_to
	 * @param String  currency_code
	 * @param String  threshold_value
	 * @param String  vat_region
	 */
	function tax_class_translation_widgetry(index, class_from, class_to, currency_code, threshold_value, vat_region) {
		
		var tax_class_list = wc_vat_compliance.tax_class_list;

		var ret = '<div class="wc_vat_tax_class_translation">';
		
		var currency_selector = '<input class="threshold_value" name="woocommerce_vat_compliance_tax_class_translations['+index.toString()+'][threshold]" value="'+threshold_value+'">';
		
		currency_selector += ' ';
		
		currency_selector += wc_vat_compliance_currency_selector_dropdown('woocommerce_vat_compliance_tax_class_translations_'+index.toString()+'_currency', 'woocommerce_vat_compliance_tax_class_translations['+index.toString()+'][currency]', currency_code);
		
		var class_from_selector = '<select name="woocommerce_vat_compliance_tax_class_translations['+index.toString()+'][class_from]">';
		for (const tax_class in tax_class_list) {
			class_from_selector += '<option value="'+tax_class+'"';
			if (tax_class === class_from) class_from_selector += ' selected="selected"';
			class_from_selector += '>'+tax_class_list[tax_class]+'</option>'+"\n";
		}
		class_from_selector += '</select>';
		
		var class_to_selector = '<select name="woocommerce_vat_compliance_tax_class_translations['+index.toString()+'][class_to]">';
		for (const tax_class in tax_class_list) {
			class_to_selector += '<option value="'+tax_class+'"';
			if (tax_class === class_to) class_to_selector += ' selected="selected"';
			class_to_selector += '>'+tax_class_list[tax_class]+'</option>'+"\n";
		}
		class_to_selector += "</select>\n";
		
		var zone_selector = region_selector_dropdown('woocommerce_vat_compliance_tax_class_translations_'+index.toString()+'_vat_region', 'woocommerce_vat_compliance_tax_class_translations['+index.toString()+'][vat_region]', vat_region);
		
		ret += wc_vat_compliance.tax_class_translation.replace('%s', class_from_selector).replace('%s', zone_selector).replace('%s', currency_selector).replace('%s', class_to_selector);
		
		ret += '<br>'+delete_row(wc_vat_compliance.delete_this_translation_rule);
		
		ret += "</div>\n";
		
		return ret;
		
	}
	
	$('#wcvat_tax_class_translation_create_new').on('click', function() {
		new_tax_class_translation('standard', 'standard', 'EUR', 10000, 'eu');
		return false;
	});
		
	var based_upon = ('undefined' === typeof  wc_vat_compliance.vat_exempt_above_options.based_upon[0]) ? 'items_total' : wc_vat_compliance.vat_exempt_above_options.based_upon[0];
	
	var value_exemption_selector = wc_vat_compliance_currency_selector_dropdown('woocommerce_eu_vat_cart_vat_exempt_above_currency_0', 'woocommerce_eu_vat_cart_vat_exempt_above_currency[0]', wc_vat_compliance.selected_recording_currency)+' '+based_upon_selector_widget(0, based_upon);
	$(value_exemption_selector).insertAfter('#woocommerce_eu_vat_cart_vat_exempt_above_0');
	wc_vat_compliance_initialise_woo_enhanced_selectors();
	
	if (wc_vat_compliance.hasOwnProperty('tax_class_translations')) {
		for (var i=0; i<wc_vat_compliance.tax_class_translations.length; i++) {
			var translation = wc_vat_compliance.tax_class_translations[i];
			new_tax_class_translation(translation.class_from, translation.class_to, translation.currency, translation.threshold, translation.vat_region);
		}
	}
	
});
