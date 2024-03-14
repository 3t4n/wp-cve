jQuery(document).ready(function()
{
	jQuery('.hpf-admin-settings-import-settings .settings-panel #format').select2({ allowClear: true, placeholder:"Select..." });
	jQuery('select[name*=\'field_mapping_rules\'][name*=\'[houzez_field]\']').select2({ allowClear: true, placeholder:"Select..." });
	jQuery('select[name*=\'field_mapping_rules\'][name*=\'[field]\']').select2({ allowClear: true, placeholder:"Select..." });
	
	jQuery('.hpf-admin-settings-import-settings .left-tabs ul li a').click(function(e)
	{
		e.preventDefault();

		var this_href = jQuery(this).attr('href');

		jQuery('.hpf-admin-settings-import-settings .left-tabs ul li').removeClass('active');
		jQuery(this).parent().addClass('active');

		jQuery('.hpf-admin-settings-import-settings .settings-panel').hide();
		jQuery(this_href).fadeIn('fast');
	});

	jQuery('body').on('click', '.settings-panels .rule-accordion-header > span:first-child', function()
	{
		if ( jQuery(this).parent().parent().find('.rule-accordion-contents').css('display') == 'none' )
		{
			jQuery(this).removeClass('dashicons-arrow-down-alt2').addClass('dashicons-arrow-up-alt2');
		}
		else
		{
			jQuery(this).removeClass('dashicons-arrow-up-alt2').addClass('dashicons-arrow-down-alt2');
		}
		jQuery(this).parent().parent().find('.rule-accordion-contents').slideToggle();
	});

	jQuery('.hpf-admin-settings-import-settings .settings-panel #format').change(function()
	{
		hpf_show_format_settings();
	});

	jQuery('.field-mapping-add-or-rule-button').click(function(e)
	{
		e.preventDefault();

		add_field_mapping_or_rule();
	});

	jQuery('body').on('click', '.rule-accordion-header .delete-rule', function(e)
	{
		e.preventDefault();

		var confirm_box = confirm( "Are you sure you want to delete this rule?" );

		if (!confirm_box)
		{
			return;
		}

		jQuery(this).parent().parent().parent().remove();

		build_field_mapping_rule_accordions();
	});

	jQuery('body').on('click', '.field-mapping-rule .rule-actions a.delete-action', function(e)
	{
		e.preventDefault();
		jQuery(this).parent().parent().remove();

		// clean up any empty AND groups
		jQuery('#field_mapping_rules .field-mapping-rule').each(function()
		{
			if ( jQuery(this).find('.and-rules').html().trim() == '' )
			{
				jQuery(this).remove();
			}
			jQuery(this).find('.and-rules .or-rule:nth-child(1) .and-label').remove();
		});

		jQuery('#field_mapping_rule_template .field-mapping-rule').each(function()
		{
			jQuery(this).find('.and-rules .or-rule:nth-child(1) .and-label').remove();
		});

		jQuery('.and-rules .or-rule .delete-action').show();
		jQuery('.and-rules').each(function()
		{
			if ( jQuery(this).find('.or-rule').length == 1 )
			{
				jQuery(this).find('.delete-action').hide();
			}
		});

		build_field_mapping_rule_accordions();
	});

	jQuery('body').on('change', '.field-mapping-rule input', function()
	{
		build_field_mapping_rule_accordions();
	});

	jQuery('body').on('change', '.field-mapping-rule select', function()
	{
		build_field_mapping_rule_accordions();
	});

	jQuery(this).find('.and-rules .or-rule:nth-child(1) .and-label').remove();

	jQuery('body').on('click', '.rule-actions a.add-and-rule-action', function(e)
	{
		e.preventDefault();

		jQuery('select[name*=\'field_mapping_rules\'][name*=\'[houzez_field]\']').select2("destroy");

		// clone previous rule
		var previous_rule_html = jQuery(this).parent().parent().html();
		var and_html = '<div style="padding:20px 0; font-weight:600" class="and-label">AND</div>';
		jQuery(this).parent().parent().parent().append( '<div class="or-rule">' + ( previous_rule_html.indexOf('>AND<') == -1 ? and_html : '' ) + previous_rule_html + '</div>' );
		jQuery(this).parent().parent().parent().find('.or-rule:last-child').find('input, select').each(function()
		{
			jQuery(this).val('');
		});

		jQuery('.and-rules .or-rule .delete-action').show();
		jQuery('.and-rules').each(function()
		{
			if ( jQuery(this).find('.or-rule').length == 1 )
			{
				jQuery(this).find('.delete-action').hide();
			}
		});

		jQuery('select[name*=\'field_mapping_rules\'][name*=\'[houzez_field]\']').select2({ allowClear: true, placeholder:"Select..." });

		build_field_mapping_rule_accordions();
	});

	/*if ( jQuery('#field_mapping_rules .field-mapping-rule').length == 0 )
	{
		add_field_mapping_or_rule();
	}*/

	jQuery('.and-rules .or-rule .delete-action').show();
	jQuery('.and-rules').each(function()
	{
		if ( jQuery(this).find('.or-rule').length == 1 )
		{
			jQuery(this).find('.delete-action').hide();
		}
	});

	hpf_show_format_settings();
	build_field_mapping_rule_accordions();
});

function build_field_mapping_rule_accordions()
{
	if ( jQuery('#field_mapping_rules').length > 0 )
	{
		if ( jQuery('#field_mapping_rules').children().length == 0 )
		{
			jQuery('#no_field_mappings').show();
		}
		else
		{
			jQuery('#no_field_mappings').hide();

			// loop through accordions and set rule descriptions
			jQuery('.rule-accordion').each(function()
			{
				var rule_description = '<span>If</span>';

				var field_in_houzez = jQuery(this).find('.and-rules .or-rule').eq(0).find('select[name*=\'field_mapping_rules\'][name*=\'[houzez_field]\'] option:selected').text();
				if ( field_in_houzez == '' ) { field_in_houzez = '<em>(no field specified)</em>'; }
				rule_description += '<span><code>' + field_in_houzez + '</code></span>';

				rule_description += '<span>is equal to</span>';

				var value_in_feed = jQuery(this).find('.and-rules .or-rule').eq(0).find('input[name*=\'field_mapping_rules\'][name*=\'[equal]\']').val();
				if ( value_in_feed == '' ) { value_in_feed = '<em>(no value specified)</em>'; }
				rule_description += '<span><code>' + value_in_feed + '</code></span>';

				var num_or_rules = jQuery(this).find('.and-rules .or-rule').length;
				if ( num_or_rules > 1 )
				{
					rule_description += '<span>(+ ' + (num_or_rules - 1) + ' rule' + ( (num_or_rules-1) != 1 ? 's' : '' ) + ')</span>';
				}

				rule_description += '<span>then set</span>';

				var field_in_feed = jQuery(this).find('select[name*=\'field_mapping_rules\'][name*=\'[field]\'] option:selected').text();
				if ( field_in_feed == '' ) { field_in_feed = '<em>(no field specified)</em>'; }
				rule_description += '<span><code>' + field_in_feed + '</code></span>';

				rule_description += '<span>to</span>';

				/*var result_type = jQuery(this).find('input[name*=\'field_mapping_rules\'][name*=\'[result_type]\']').val();

				if ( result_type == 'dropdown' )
				{
					var value_in_houzez = jQuery(this).find('select[name*=\'field_mapping_rules\'][name*=\'[result_option]\'] option:selected').text();
				}
				else
				{*/
					var value_in_houzez = jQuery(this).find('input[name*=\'field_mapping_rules\'][name*=\'[result]\']').val();
				//}

				if ( value_in_houzez == '' ) { value_in_houzez = '<em>(no value specified)</em>'; }
				rule_description += '<span><code>' + value_in_houzez + '</code></span>';

				jQuery(this).find('.rule-description').html(rule_description);
			});
		}
	}
}

function hpf_show_format_settings()
{
	var selected_format = jQuery('.hpf-admin-settings-import-settings .settings-panel #format').val();

	jQuery('.hpf-admin-settings-import-settings .settings-panel .import-settings-format').hide();
	jQuery('#export_settings_' + selected_format).fadeIn('fast');

	jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_property_type').hide();
	jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_status').hide();
	jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_price_qualifier').hide();

	jQuery('#export_name_row').hide();

	jQuery('.no-format-notice').hide();
	jQuery('.hpf-export-format-name').html('');

	jQuery('#export_setting_tab_frequency').hide();
	jQuery('#export_setting_tab_fieldmapping').hide();

	if ( selected_format == '' )
	{
		jQuery('.no-format-notice').show();
	}
	else
	{
		jQuery('#export_name_row').show();

		// set name
		if ( jQuery('#export_name').val() == '' )
		{
			jQuery('#export_name').val( jQuery('.hpf-admin-settings-import-settings .settings-panel #format').find(':selected').text() );
		}

		var has_taxonomy_values_status = false;
		var taxonomy_values_status = new Array();
		
		var has_taxonomy_values_property_type = false;
		var taxonomy_values_property_type = new Array();

		var has_taxonomy_values_price_qualifier = false;
		var taxonomy_values_price_qualifier = new Array();

		for ( var i in hpf_admin_object.formats )
		{
			if ( i == selected_format )
			{
				if ( hpf_admin_object.formats[i].method == 'cron' || hpf_admin_object.formats[i].method == 'url' )
				{
					jQuery('#export_setting_tab_frequency').show();
				}

				if ( hpf_admin_object.formats[i].taxonomy_values.hasOwnProperty('status') )
				{
					has_taxonomy_values_status = true;
					if ( Object.keys(hpf_admin_object.formats[i].taxonomy_values.status).length > 0 ) 
					{ 
						taxonomy_values_status = hpf_admin_object.formats[i].taxonomy_values.status; 
					}
				}
				
				if ( hpf_admin_object.formats[i].taxonomy_values.hasOwnProperty('property_type') )
				{
					has_taxonomy_values_property_type = true;
					if (Object.keys(hpf_admin_object.formats[i].taxonomy_values.property_type).length > 0 ) 
					{ 
						taxonomy_values_property_type = hpf_admin_object.formats[i].taxonomy_values.property_type; 
					}	
				} 

				if ( hpf_admin_object.formats[i].taxonomy_values.hasOwnProperty('price_qualifier') )
				{
					has_taxonomy_values_price_qualifier = true;
					if (Object.keys(hpf_admin_object.formats[i].taxonomy_values.price_qualifier).length > 0 ) 
					{ 
						taxonomy_values_price_qualifier = hpf_admin_object.formats[i].taxonomy_values.price_qualifier; 
					}	
				} 

				if ( hpf_admin_object.formats[i].hasOwnProperty('field_mapping_fields') && Object.keys(hpf_admin_object.formats[i].field_mapping_fields).length > 0 )
				{
					jQuery('#export_setting_tab_fieldmapping').show();

					jQuery('select[name*=\'field_mapping_rules\'][name*=\'[field]\']').select2("destroy");

					var k = 0;
					jQuery('select[name*=\'field_mapping_rules\'][name*=\'[field]\']').each(function()
					{
						jQuery(this).empty();

						jQuery(this).append('<option value=""></option>');

						for ( var j in hpf_admin_object.formats[i].field_mapping_fields )
						{
							selected_status = false;
							if ( jQuery(this).attr('name').indexOf('rule_count') == -1 ) //not the template rule
							{
								if ( hpf_admin_object.export_settings.hasOwnProperty('field_mapping_rules') && hpf_admin_object.export_settings.field_mapping_rules.length > 0 )
								{
									for ( var m in hpf_admin_object.export_settings.field_mapping_rules )
									{
										if ( m == k )
										{
											if ( hpf_admin_object.export_settings.field_mapping_rules[m].hasOwnProperty('field') )
											{
												if ( hpf_admin_object.export_settings.field_mapping_rules[m].field == j )
												{
													selected_status = true;
												}
											}
										}
									}
								}
							}
							jQuery(this).append('<option value="' + j + '"' + ( selected_status ? ' selected' : '' ) + '>' + hpf_admin_object.formats[i].field_mapping_fields[j] + '</option>');
						}

						k = k + 1;
					});

					jQuery('select[name*=\'field_mapping_rules\'][name*=\'[field]\']').select2({ allowClear: true, placeholder:"Select..." });
				}

				jQuery('.hpf-export-format-name').html(hpf_admin_object.formats[i].name);
				break;
			}
		}

		// Status taxonomy mapping
		if ( has_taxonomy_values_status )
		{
			jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_status').show();

			jQuery('select[name^=\'taxonomy_mapping[property_status]\']').each(function()
			{
				jQuery(this).empty();

				jQuery(this).append( '<option value=""></option>' );

				var term_id = jQuery(this).attr('name').replace("taxonomy_mapping[property_status][", "");
				term_id = term_id.replace("]", "");

				if ( Object.keys(taxonomy_values_status).length > 0 )
				{
					for ( var i in taxonomy_values_status )
					{
						selected_status = false;
						if ( 
							hpf_admin_object.export_settings.hasOwnProperty('mappings') && 
							hpf_admin_object.export_settings.mappings.hasOwnProperty('property_status') &&
							hpf_admin_object.export_settings.mappings.property_status.hasOwnProperty(term_id)
						)
						{
							if ( hpf_admin_object.export_settings.mappings.property_status[term_id] == i )
							{
								selected_status = true;
							}
						}
						if ( !selected_status )
						{
							// TO DO: set by default if match found
						}
						jQuery(this).append( '<option value="' + i + '"' + ( selected_status ? ' selected' : '' ) + '>' + taxonomy_values_status[i] + '</option>' );

					}
				}
			});
		}

		// Property type taxonomy mapping
		if ( has_taxonomy_values_property_type )
		{
			jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_property_type').show();

			jQuery('select[name^=\'taxonomy_mapping[property_type]\']').each(function()
			{
				jQuery(this).empty();

				jQuery(this).append( '<option value=""></option>' );

				var term_id = jQuery(this).attr('name').replace("taxonomy_mapping[property_type][", "");
				term_id = term_id.replace("]", "");

				if ( Object.keys(taxonomy_values_property_type).length > 0 )
				{
					for ( var i in taxonomy_values_property_type )
					{
						selected_status = false;
						if ( 
							hpf_admin_object.export_settings.hasOwnProperty('mappings') && 
							hpf_admin_object.export_settings.mappings.hasOwnProperty('property_type') &&
							hpf_admin_object.export_settings.mappings.property_type.hasOwnProperty(term_id)
						)
						{
							if ( hpf_admin_object.export_settings.mappings.property_type[term_id] == i )
							{
								selected_status = true;
							}
						}
						if ( !selected_status )
						{
							// TO DO: set by default if match found
						}
						jQuery(this).append( '<option value="' + i + '"' + ( selected_status ? ' selected' : '' ) + '>' + taxonomy_values_property_type[i] + '</option>' );

					}
				}
			});
		}

		// Price qualifier mapping
		if ( has_taxonomy_values_price_qualifier )
		{
			jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_price_qualifier').show();

			jQuery('select[name^=\'taxonomy_mapping[price_qualifier]\']').each(function()
			{
				jQuery(this).empty();

				jQuery(this).append( '<option value=""></option>' );

				var term_id = jQuery(this).attr('name').replace("taxonomy_mapping[price_qualifier][", "");
				term_id = term_id.replace("]", "");

				if ( Object.keys(taxonomy_values_price_qualifier).length > 0 )
				{
					for ( var i in taxonomy_values_price_qualifier )
					{
						selected_status = false;
						if ( 
							hpf_admin_object.export_settings.hasOwnProperty('mappings') && 
							hpf_admin_object.export_settings.mappings.hasOwnProperty('price_qualifier') &&
							hpf_admin_object.export_settings.mappings.price_qualifier.hasOwnProperty(term_id)
						)
						{
							if ( hpf_admin_object.export_settings.mappings.price_qualifier[term_id] == i )
							{
								selected_status = true;
							}
						}
						if ( !selected_status )
						{
							// TO DO: set by default if match found
						}
						jQuery(this).append( '<option value="' + i + '"' + ( selected_status ? ' selected' : '' ) + '>' + taxonomy_values_price_qualifier[i] + '</option>' );

					}
				}
			});
		}
	}
}

function add_field_mapping_or_rule()
{
	if ( jQuery('#field_mapping_rules').length > 0 )
	{
		jQuery('select[name*=\'field_mapping_rules\'][name*=\'[houzez_field]\']').select2("destroy");
		jQuery('select[name*=\'field_mapping_rules\'][name*=\'[field]\']').select2("destroy");

		jQuery("#field_mapping_rule_template input").each(function()
		{
		    jQuery(this).attr("value", jQuery(this).val());
		});
		jQuery('#field_mapping_rule_template select option').each(function()
		{ 
			this.defaultSelected = this.selected; 
		});

		var template_html = jQuery('#field_mapping_rule_template').html();

		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);
		template_html = template_html.replace("{rule_count}", hpf_rule_count);

		hpf_rule_count = hpf_rule_count + 1;

		jQuery('#field_mapping_rules').append('<div class="rule-accordion" style="display:none"><div class="rule-accordion-header"><span class="dashicons dashicons-arrow-down-alt2"></span>&nbsp; <span class="rule-description">Rule description here</span><div class="icons"><span class="delete-rule dashicons dashicons-trash" title="Delete Rule"></span></div></div><div class="rule-accordion-contents">' + template_html + '</div></div>');
		jQuery('#field_mapping_rules .rule-accordion:last-child').slideDown();

		// empty template fields
		jQuery("#field_mapping_rule_template input").each(function()
		{
		    jQuery(this).val('');
		});
		jQuery('#field_mapping_rule_template select').each(function()
		{ 
			jQuery(this).val('');
		});

		jQuery('select[name*=\'field_mapping_rules\'][name*=\'[houzez_field]\']').select2({ allowClear: true, placeholder:"Select..." });
		jQuery('select[name*=\'field_mapping_rules\'][name*=\'[field]\']').select2({ allowClear: true, placeholder:"Select..." });
	}

	build_field_mapping_rule_accordions();
}