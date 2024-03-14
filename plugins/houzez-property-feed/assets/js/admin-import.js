var hpf_original_property_node = ''; // used for XML format
var hpf_original_property_id_node = ''; // used for XML format
var hpf_original_property_id_field = ''; // used for CSV format
var hpf_original_image_field = ''; // used for CSV format, comma-delimited images
var hpf_original_floorplan_field = ''; // used for CSV format, comma-delimited floorplans
var hpf_original_document_field = ''; // used for CSV format, comma-delimited documents

jQuery(document).ready(function()
{
	jQuery('.hpf-admin-settings-import-settings .settings-panel #format').select2({ allowClear: true, placeholder:"Select..." });
	jQuery('select[name*=\'field_mapping_rules\'][name*=\'[houzez_field]\']').select2({ allowClear: true, placeholder:"Select..." });

	jQuery('.settings-panels input[name=\'image_field_arrangement\']').change(function()
	{
		jQuery('.hpf-admin-settings-import-settings .left-tabs ul li.active a').trigger('click');
	});
	jQuery('.settings-panels input[name=\'floorplan_field_arrangement\']').change(function()
	{
		jQuery('.hpf-admin-settings-import-settings .left-tabs ul li.active a').trigger('click');
	});
	jQuery('.settings-panels input[name=\'document_field_arrangement\']').change(function()
	{
		jQuery('.hpf-admin-settings-import-settings .left-tabs ul li.active a').trigger('click');
	});

	jQuery('.hpf-admin-settings-import-settings .left-tabs ul li a').click(function(e)
	{
		e.preventDefault();

		var this_href = jQuery(this).attr('href');

		jQuery('.hpf-admin-settings-import-settings .left-tabs ul li').removeClass('active');
		jQuery(this).parent().addClass('active');

		jQuery('.hpf-admin-settings-import-settings .settings-panel').hide();
		jQuery(this_href).show();

		hpf_set_xml_fields_size_properties();
		hpf_set_csv_fields_size_properties();

		var selected_format = jQuery('.hpf-admin-settings-import-settings .settings-panel #format').val();

		if ( selected_format == 'csv' )
		{
			hpf_create_csv_field_mapping_options();

			jQuery('.settings-panels .xml-tip').hide();
			jQuery('.settings-panels .csv-tip').show();

			if ( jQuery('.settings-panels input[name=\'image_field_arrangement\']:checked').val() == '' )
			{
				jQuery('.settings-panels .media-image-settings .media-comma-delimited-row').hide();
				jQuery('.settings-panels .media-image-settings .media-individual-row').show();
			}
			if ( jQuery('.settings-panels input[name=\'image_field_arrangement\']:checked').val() == 'comma_delimited' )
			{
				jQuery('.settings-panels .media-image-settings .media-comma-delimited-row').show();
				jQuery('.settings-panels .media-image-settings .media-individual-row').hide();
			}

			if ( jQuery('.settings-panels input[name=\'floorplan_field_arrangement\']:checked').val() == '' )
			{
				jQuery('.settings-panels .media-floorplan-settings .media-comma-delimited-row').hide();
				jQuery('.settings-panels .media-floorplan-settings .media-individual-row').show();
			}
			if ( jQuery('.settings-panels input[name=\'floorplan_field_arrangement\']:checked').val() == 'comma_delimited' )
			{
				jQuery('.settings-panels .media-floorplan-settings .media-comma-delimited-row').show();
				jQuery('.settings-panels .media-floorplan-settings .media-individual-row').hide();
			}

			if ( jQuery('.settings-panels input[name=\'document_field_arrangement\']:checked').val() == '' )
			{
				jQuery('.settings-panels .media-document-settings .media-comma-delimited-row').hide();
				jQuery('.settings-panels .media-document-settings .media-individual-row').show();
			}
			if ( jQuery('.settings-panels input[name=\'document_field_arrangement\']:checked').val() == 'comma_delimited' )
			{
				jQuery('.settings-panels .media-document-settings .media-comma-delimited-row').show();
				jQuery('.settings-panels .media-document-settings .media-individual-row').hide();
			}

			jQuery('#image_fields').attr('placeholder', '{Image 1}|{Image 1 Caption}' + "\n" + '{Image 2}');
			jQuery('#floorplan_fields').attr('placeholder', '{Floorplan 1}' + "\n" + '{Floorplan 2}');
			jQuery('#document_fields').attr('placeholder', '{Brochure}' + "\n" + '{EPC}' + "\n" + '{Document 1}|Brochure');
		}

		if ( selected_format == 'xml' )
		{
			jQuery('.settings-panels .xml-tip').show();
			jQuery('.settings-panels .csv-tip').hide();

			jQuery('.media-comma-delimited-row').hide();
			jQuery('.media-individual-row').show();

			jQuery('#image_fields').attr('placeholder', '{/images/image[1]}' + "\n" + '{/images/image[2]}' + "\n" + '{/images/image[3]/url}|{/images/image[3]/caption}' + "\n" + '{/image[1]}.jpg');
			jQuery('#floorplan_fields').attr('placeholder', '{/floorplans/floorplan[1]/url}|{/floorplans/floorplan[1]/caption}' + "\n" + '{/floorplans/floorplan[2]}');
			jQuery('#document_fields').attr('placeholder', '{/brochureURL}|Brochure' + "\n" + '{/epcs/epc[1]}' + "\n" + '{/documents/document[1]/url}|{/documents/document[1]/caption}');
		}
	});

	jQuery('.hpf-admin-settings-import-settings .settings-panel #format').change(function()
	{
		hpf_show_format_settings();
	});

	jQuery('select[name=\'xml_property_node\']').change(function()
	{
		hpf_create_xml_property_id_node_options();
		hpf_create_xml_field_mapping_options();
	});

	jQuery('.hpf-admin-settings-import-settings .settings-panel input[name=\'agent_display_option\']').change(function()
	{
		hpf_show_contact_info_rules();
	});

	jQuery('.hpf-admin-settings-import-settings input[name=\'email_reports\']').change(function()
	{
		hpf_show_email_reports_settings();
	});

	jQuery('.hpf-admin-settings-automatic-imports .automatic-imports-table .trash a').click(function()
	{
		var confirm_box = confirm( "Are you sure you want to delete this import?\n\nPLEASE NOTE: If any properties have been imported via this import they will remain in place and will need to be deleted manually" );

		return confirm_box;
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

	jQuery('.agent-display-option-add-rule-button').click(function(e)
	{
		e.preventDefault();

		var this_id = jQuery(this).attr('id');
		this_id = this_id.replace("agent_display_option_add_rule_button_", "");

		var template_html = jQuery('#agent_display_option_rule_template_' + this_id).html();

		jQuery('#agent_display_option_rules_' + this_id).append(template_html);
	});

	jQuery('body').on('click', '.agent-display-option-rule .delete-rule a', function(e)
	{
		e.preventDefault();
		jQuery(this).parent().parent().remove();
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

		last_safe_scroll = 0;
		build_field_mapping_rule_accordions();
		hpf_set_xml_fields_size_properties();
		hpf_set_csv_fields_size_properties();
	});

	jQuery('body').on('click', '.rule-accordion-header .duplicate-rule', function(e)
	{
		e.preventDefault();

		jQuery('input[name*=\'field_mapping_rules\'][name*=\'[field]\']').droppable( "destroy" );
		jQuery('input[name*=\'field_mapping_rules\'][name*=\'[result]\']').droppable( "destroy" );

		jQuery('select[name*=\'field_mapping_rules\'][name*=\'[houzez_field]\']').select2("destroy");

		jQuery(this).parent().parent().parent().find("input").each(function()
		{
		    jQuery(this).attr("value", jQuery(this).val());
		});
		jQuery(this).parent().parent().parent().find('select option').each(function()
		{ 
			this.defaultSelected = this.selected; 
		});

		/// get rule count of rule being cloned
		var field_to_look_at_for_id = jQuery(this).parent().parent().parent().find('select[name*=\'field_mapping_rules\'][name*=\'[houzez_field]\']');
		var field_name = jQuery(field_to_look_at_for_id).attr('name');
		//alert(field_name);
		var arrStr = field_name.split(/\[(.*?)\]/);
		var existing_rule_count = arrStr[1];

		var rule_html = jQuery(this).parent().parent().parent().html();

		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");
		rule_html = rule_html.replace("field_mapping_rules[" + existing_rule_count + "]", "field_mapping_rules[" + hpf_rule_count + "]");

		hpf_rule_count = hpf_rule_count + 1;

		jQuery('<div class="rule-accordion">' + rule_html + '</div>').insertAfter(jQuery(this).parent().parent().parent());

		// close any existing rules and open the new one
		jQuery('.rule-accordion-header > span:first-child').removeClass('dashicons-arrow-up-alt2').addClass('dashicons-arrow-down-alt2');
		jQuery(this).parent().parent().parent().next('.rule-accordion').find('.rule-accordion-header > span:first-child').removeClass('dashicons-arrow-down-alt2').addClass('dashicons-arrow-up-alt2');
		
		jQuery('.rule-accordion .rule-accordion-contents').slideUp();
		jQuery(this).parent().parent().parent().next('.rule-accordion').find('.rule-accordion-contents').slideDown();

		jQuery('input[name*=\'field_mapping_rules\'][name*=\'[field]\']').droppable({
		    drop: function (event, ui) {
		        this.value += jQuery(ui.draggable).text();
		    }
		});

		jQuery('input[name*=\'field_mapping_rules\'][name*=\'[result]\']').droppable({
		    drop: function (event, ui) {
		        this.value += '{' + jQuery(ui.draggable).text() + '}';
		    }
		});

		jQuery('select[name*=\'field_mapping_rules\'][name*=\'[houzez_field]\']').select2({ allowClear: true, placeholder:"Select..." });

		last_safe_scroll = 0;
		build_field_mapping_rule_accordions();
		hpf_set_xml_fields_size_properties();
		hpf_set_csv_fields_size_properties();
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

		last_safe_scroll = 0;
		build_field_mapping_rule_accordions();
		hpf_set_xml_fields_size_properties();
		hpf_set_csv_fields_size_properties();
	});

	jQuery('body').on('change', '.field-mapping-rule input', function()
	{
		build_field_mapping_rule_accordions();
	});

	jQuery('body').on('change', '.field-mapping-rule select', function()
	{
		build_field_mapping_rule_accordions();
	});

	jQuery('body').on('change', 'select[name*=\'[houzez_field]\']', function()
	{
		var selected_houzez_field = jQuery(this).val();
		var houzez_field_options = new Array();
		var houzez_field_delimited = false;

		if ( hpf_admin_object.houzez_fields_for_field_mapping )
		{
			for ( var i in hpf_admin_object.houzez_fields_for_field_mapping )
			{
				if ( i == selected_houzez_field )
				{
					if ( hpf_admin_object.houzez_fields_for_field_mapping[i].hasOwnProperty('options') )
					{
						houzez_field_options = hpf_admin_object.houzez_fields_for_field_mapping[i].options;
					}

					if ( hpf_admin_object.houzez_fields_for_field_mapping[i].hasOwnProperty('delimited') && hpf_admin_object.houzez_fields_for_field_mapping[i].delimited == true )
					{
						houzez_field_delimited = true;
					}
				}
			}
		}

		if (houzez_field_delimited)
		{
			jQuery(this).parent().parent().find('.delimited').show();
		}
		else
		{
			jQuery(this).parent().parent().find('.delimited').hide();
		}

		jQuery(this).parent().parent().find('.result-dropdown select').empty();

		if ( Object.keys(houzez_field_options).length > 0 )
		{
			jQuery(this).parent().parent().find('.result-dropdown select').append('<option value=""></option>');
			for ( var i in houzez_field_options )
			{
				jQuery(this).parent().parent().find('.result-dropdown select').append('<option value="' + i + '">' + houzez_field_options[i] + '</option>');
			}
			jQuery(this).parent().parent().find('.result-text').hide();
			jQuery(this).parent().parent().find('.result-dropdown').show();
			jQuery(this).parent().parent().find('input[name*=\'result_type\']').val('dropdown');
		}
		else
		{
			jQuery(this).parent().parent().find('.result-text').show();
			jQuery(this).parent().parent().find('.result-dropdown').hide();
			jQuery(this).parent().parent().find('input[name*=\'result_type\']').val('text');
		}

		build_field_mapping_rule_accordions();

		hpf_show_missing_mandatory_xml_field_mapping();
		hpf_show_missing_mandatory_csv_field_mapping();
		hpf_show_already_mapped_warning();
	});

	jQuery('body').on('change', 'input[name*=\'[delimited]\']', function()
	{
		if ( jQuery(this).is(':checked') )
		{
			jQuery(this).parent().parent().find('.delimited-character').show();
		}
		else
		{
			jQuery(this).parent().parent().find('.delimited-character').hide();
		}
	});

	jQuery(this).find('.and-rules .or-rule:nth-child(1) .and-label').remove();

	jQuery('body').on('click', '.rule-actions a.add-and-rule-action', function(e)
	{
		e.preventDefault();

		jQuery('input[name*=\'field_mapping_rules\'][name*=\'[field]\']').droppable( "destroy" );
		jQuery('input[name*=\'field_mapping_rules\'][name*=\'[result]\']').droppable( "destroy" );

		jQuery('select[name*=\'field_mapping_rules\'][name*=\'[houzez_field]\']').select2("destroy");

		// clone previous rule
		var previous_rule_html = jQuery(this).parent().parent().html();
		var and_html = '<div style="padding:20px 0; font-weight:600" class="and-label">AND</div>';
		jQuery(this).parent().parent().parent().append( '<div class="or-rule">' + ( previous_rule_html.indexOf('>AND<') == -1 ? and_html : '' ) + previous_rule_html + '</div>' );
		jQuery(this).parent().parent().parent().find('.or-rule:last-child').find('input').each(function()
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

		jQuery('input[name*=\'field_mapping_rules\'][name*=\'[field]\']').droppable({
		    drop: function (event, ui) {
		        this.value += jQuery(ui.draggable).text();
		    }
		});

		jQuery('input[name*=\'field_mapping_rules\'][name*=\'[result]\']').droppable({
		    drop: function (event, ui) {
		        this.value += '{' + jQuery(ui.draggable).text() + '}';
		    }
		});

		jQuery('select[name*=\'field_mapping_rules\'][name*=\'[houzez_field]\']').select2({ allowClear: true, placeholder:"Select..." });

		build_field_mapping_rule_accordions();
		hpf_set_xml_fields_size_properties();
		hpf_set_csv_fields_size_properties();
	});

	jQuery('.and-rules .or-rule .delete-action').show();
	jQuery('.and-rules').each(function()
	{
		if ( jQuery(this).find('.or-rule').length == 1 )
		{
			jQuery(this).find('.delete-action').hide();
		}
	});

	jQuery('body').on('click', '.hpf-admin-settings-import-settings a.add-additional-mapping', function(e)
	{
		e.preventDefault();
		
		var taxonomy = jQuery(this).attr('href').replace("#", "");

		var taxonomy_options = new Array();
		switch (taxonomy)
		{
			case "sales_status":
			case "lettings_status":
			{
				taxonomy_options = hpf_admin_object.statuses;
				break;
			}
			case "property_type":
			{
				taxonomy_options = hpf_admin_object.property_types;
				break;
			}
		}

		var row_html_dropdown = '';
		row_html_dropdown += '<select name="custom_mapping_value[' + taxonomy + '][]">';
		row_html_dropdown += '<option value=""></option>';
		if ( Object.keys(taxonomy_options).length > 0 )
		{	
			for ( var j in taxonomy_options )
			{
				row_html_dropdown += '<option value="' + j + '">' + taxonomy_options[j] + '</option>';
			}
		}
		row_html_dropdown += '</select>';

		var row_html = '';
		row_html += '<tr>';
		row_html += '<td style="padding-left:0"><input type="text" name="custom_mapping[' + taxonomy + '][]" value=""></td>';
		row_html += '<td style="padding-left:0">' + row_html_dropdown + '</td>';
		row_html += '</tr>';

		jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_table_' + taxonomy).append(row_html);

	});

	jQuery('a.hpf-fetch-xml-nodes').click(function(e)
	{
		e.preventDefault();

		hpf_original_property_node = jQuery('select[name=\'xml_property_node\']').val();
		hpf_original_property_id_node = jQuery('select[name=\'xml_property_id_node\']').val();
		
		jQuery('a.hpf-fetch-xml-nodes').text('Fetching...');
		jQuery('a.hpf-fetch-xml-nodes').attr('disabled', 'disabled');
		jQuery('select[name=\'xml_property_node\']').empty();
		jQuery('select[name=\'xml_property_node\']').append('<option value="">Fetching nodes...</option>');
		jQuery('select[name=\'xml_property_id_node\']').empty();
		jQuery('select[name=\'xml_property_id_node\']').append('<option value="">Fetching nodes...</option>');
		jQuery('input[name=\'xml_property_node_options\']').val('');

		jQuery.ajax({
        	url : ajaxurl,
        	data : {
        		action: "houzez_property_feed_fetch_xml_nodes", 
        		url : jQuery(this).parent().parent().parent().find('input[name=\'xml_xml_url\']').val(), 
        		ajax_nonce: hpf_admin_object.ajax_nonce
        	},
        	dataType : "json",
        	success: function(response) 
        	{
        		jQuery('a.hpf-fetch-xml-nodes').text('Fetch XML');
				jQuery('a.hpf-fetch-xml-nodes').attr('disabled', false);
				jQuery('select[name=\'xml_property_node\']').empty();
				jQuery('select[name=\'xml_property_id_node\']').empty();

            	if ( response.success == true )
            	{
            		var nodes = new Array();
            		for ( var i in response.nodes )
            		{
            			var selected_html = '';
            			if ( hpf_original_property_node == response.nodes[i] )
            			{
            				selected_html = ' selected';
            			}
            			jQuery('select[name=\'xml_property_node\']').append('<option value="' + response.nodes[i] + '"' + selected_html + '>' + response.nodes[i] + '</option>');
            			nodes.push(response.nodes[i]);
            		}
            		jQuery('input[name=\'xml_property_node_options\']').val(JSON.stringify(nodes));
            		hpf_create_xml_property_id_node_options();
            		hpf_create_xml_field_mapping_options();
            	}
            	else
            	{
            		alert(response.error);
            	}
         	}
      	})  
	});

	jQuery('a.hpf-fetch-csv-fields').click(function(e)
	{
		e.preventDefault();

		hpf_original_property_id_field = jQuery('select[name=\'csv_property_id_field\']').val();
		hpf_original_image_field = jQuery('select[name=\'image_field\']').val();
		hpf_original_floorplan_field = jQuery('select[name=\'floorplan_field\']').val();
		hpf_original_document_field = jQuery('select[name=\'document_field\']').val();
		
		jQuery('a.hpf-fetch-csv-fields').text('Fetching...');
		jQuery('a.hpf-fetch-csv-fields').attr('disabled', 'disabled');
		jQuery('select[name=\'csv_property_id_field\']').empty();
		jQuery('select[name=\'csv_property_id_field\']').append('<option value="">Fetching fields...</option>');
		jQuery('input[name=\'csv_property_field_options\']').val('');

		jQuery.ajax({
        	url : ajaxurl,
        	data : {
        		action: "houzez_property_feed_fetch_csv_fields", 
        		url : jQuery(this).parent().parent().parent().find('input[name=\'csv_csv_url\']').val(), 
        		delimiter : jQuery(this).parent().parent().parent().find('input[name=\'csv_csv_delimiter\']').val(), 
        		ajax_nonce: hpf_admin_object.ajax_nonce
        	},
        	dataType : "json",
        	success: function(response) 
        	{
        		jQuery('a.hpf-fetch-csv-fields').text('Fetch CSV');
				jQuery('a.hpf-fetch-csv-fields').attr('disabled', false);
				jQuery('select[name=\'csv_property_id_field\']').empty();

            	if ( response.success == true )
            	{
            		var fields = new Array();
            		for ( var i in response.fields )
            		{
            			fields.push(response.fields[i]);
            		}
            		jQuery('input[name=\'csv_property_field_options\']').val(JSON.stringify(fields));
            		hpf_create_csv_property_id_field_options();
            		hpf_create_csv_field_mapping_options();
            	}
            	else
            	{
            		alert(response.error);
            	}
         	}
      	})  
	});

	jQuery('body').on('click', '#xml-nodes-found a', function(e)
	{
		e.preventDefault();
	});

	hpf_show_email_reports_settings();
	hpf_show_format_settings();
	hpf_show_contact_info_rules();
	build_field_mapping_rule_accordions();

	jQuery('.xml-rules-available-fields a').draggable({
	    revert: true,
	    helper: 'clone',
	    appendTo: 'body'
	});

	jQuery('.csv-rules-available-fields a').draggable({
	    revert: true,
	    helper: 'clone',
	    appendTo: 'body'
	});

	jQuery('input[name*=\'field_mapping_rules\'][name*=\'[field]\']').droppable({
	    drop: function (event, ui) {
	        this.value += jQuery(ui.draggable).text();
	    }
	});

	jQuery('input[name*=\'field_mapping_rules\'][name*=\'[result]\']').droppable({
	    drop: function (event, ui) {
	        this.value += '{' + jQuery(ui.draggable).text() + '}';
	    }
	});

	/*if ( jQuery('#field_mapping_rules .field-mapping-rule').length == 0 )
	{
		add_field_mapping_or_rule();
	}*/
});

jQuery(window).on( "resize", function() 
{
	hpf_set_xml_fields_size_properties();
	hpf_set_csv_fields_size_properties();
});

jQuery(window).on( "scroll", function() 
{
	hpf_set_xml_fields_size_properties();
	hpf_set_csv_fields_size_properties();
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

				var field_in_feed = jQuery(this).find('.and-rules .or-rule').eq(0).find('input[name*=\'field_mapping_rules\'][name*=\'[field]\']').val();
				if ( field_in_feed == '' ) { field_in_feed = '<em>(no field specified)</em>'; }

				rule_description += '<span><code>' + field_in_feed + '</code></span>';

				rule_description += '<span>is</span>';

				var operator = jQuery(this).find('.and-rules .or-rule').eq(0).find('select[name*=\'field_mapping_rules\'][name*=\'[operator]\'] option:selected').text();
				rule_description += '<span><code>' + operator + '</code></span>';

				var value_in_feed = jQuery(this).find('.and-rules .or-rule').eq(0).find('input[name*=\'field_mapping_rules\'][name*=\'[equal]\']').val();
				if ( value_in_feed == '' ) { value_in_feed = '<em>(no value specified)</em>'; }
				rule_description += '<span><code>' + value_in_feed + '</code></span>';

				var num_or_rules = jQuery(this).find('.and-rules .or-rule').length;
				if ( num_or_rules > 1 )
				{
					rule_description += '<span>(+ ' + (num_or_rules - 1) + ' rule' + ( (num_or_rules-1) != 1 ? 's' : '' ) + ')</span>';
				}

				rule_description += '<span>then set</span>';

				var field_in_houzez = jQuery(this).find('select[name*=\'field_mapping_rules\'][name*=\'[houzez_field]\'] option:selected').text();
				if ( field_in_houzez == '' ) { field_in_houzez = '<em>(no field specified)</em>'; }
				rule_description += '<span><code>' + field_in_houzez + '</code></span>';

				rule_description += '<span>to</span>';

				var result_type = jQuery(this).find('input[name*=\'field_mapping_rules\'][name*=\'[result_type]\']').val();

				if ( result_type == 'dropdown' )
				{
					var value_in_houzez = jQuery(this).find('select[name*=\'field_mapping_rules\'][name*=\'[result_option]\'] option:selected').text();
				}
				else
				{
					var value_in_houzez = jQuery(this).find('input[name*=\'field_mapping_rules\'][name*=\'[result]\']').val();
				}

				if ( value_in_houzez == '' ) { value_in_houzez = '<em>(no value specified)</em>'; }
				rule_description += '<span><code>' + value_in_houzez + '</code></span>';

				jQuery(this).find('.rule-description').html(rule_description);
			});
		}
	}
}

var last_safe_scroll = 0;
function hpf_set_xml_fields_size_properties()
{
	var selected_format = jQuery('.hpf-admin-settings-import-settings .settings-panel #format').val();

	if ( selected_format == 'xml' )
	{
		jQuery('.xml-rules-available-fields').css('transform', 'translateY(0)').height('auto');

		var max_available_space = jQuery(window).height() - 120;

		jQuery('.xml-rules-available-fields').hide();
		var available_fields_container_height = jQuery('.rules-table').outerHeight() - 40;
		if ( available_fields_container_height > max_available_space )
		{
			available_fields_container_height = max_available_space;
		}
		jQuery('.xml-rules-available-fields').show().outerHeight(available_fields_container_height);

		// set top position
		var window_scroll = jQuery(window).scrollTop();
		
		if ( window_scroll > ( jQuery('.rules-table-available-fields').offset().top - 50 ) )
		{
			var target_top = window_scroll - ( jQuery('.rules-table-available-fields').offset().top - 50 );

			// make sure it's not going to go off the bottom of the screen
			var difference = ( target_top + jQuery('.xml-rules-available-fields').outerHeight() ) - (jQuery('.rules-table').outerHeight());
			if ( difference < -40 )
			{
				last_safe_scroll = target_top;
			}
			else
			{
				
			}
			jQuery('.xml-rules-available-fields').css('transform', 'translateY(' + last_safe_scroll + 'px)');
		}
		else
		{
			jQuery('.xml-rules-available-fields').css('transform', 'translateY(0)');
			last_safe_scroll = 0;
		}
	}
}

function hpf_set_csv_fields_size_properties()
{
	var selected_format = jQuery('.hpf-admin-settings-import-settings .settings-panel #format').val();

	if ( selected_format == 'csv' )
	{
		jQuery('.csv-rules-available-fields').css('transform', 'translateY(0)').height('auto');

		var max_available_space = jQuery(window).height() - 120;

		jQuery('.csv-rules-available-fields').hide();
		var available_fields_container_height = jQuery('.rules-table').outerHeight() - 40;
		if ( available_fields_container_height > max_available_space )
		{
			available_fields_container_height = max_available_space;
		}
		jQuery('.csv-rules-available-fields').show().outerHeight(available_fields_container_height);

		// set top position
		var window_scroll = jQuery(window).scrollTop();
		
		if ( window_scroll > ( jQuery('.rules-table-available-fields').offset().top - 50 ) )
		{
			var target_top = window_scroll - ( jQuery('.rules-table-available-fields').offset().top - 50 );

			// make sure it's not going to go off the bottom of the screen
			var difference = ( target_top + jQuery('.csv-rules-available-fields').outerHeight() ) - (jQuery('.rules-table').outerHeight());
			if ( difference < -40 )
			{
				last_safe_scroll = target_top;
			}
			else
			{
				
			}
			jQuery('.csv-rules-available-fields').css('transform', 'translateY(' + last_safe_scroll + 'px)');
		}
		else
		{
			jQuery('.csv-rules-available-fields').css('transform', 'translateY(0)');
			last_safe_scroll = 0;
		}
	}
}

function hpf_create_xml_property_id_node_options()
{
	jQuery('select[name=\'xml_property_id_node\']').empty();

	var nodes = jQuery('input[name=\'xml_property_node_options\']').val();

	if ( nodes == '' )
	{
		return;
	}

	var property_node = jQuery('select[name=\'xml_property_node\']').val();

	if ( property_node != '' )
	{
		nodes = JSON.parse(nodes);

		for ( var i in nodes )
		{
			var selected_html = '';
			var node = nodes[i]/*.replace(/\/\//g, "/")*/;
			if ( hpf_original_property_id_node == node )
			{
				selected_html = ' selected';
			}

			if ( node.indexOf(property_node) == -1 )
			{
				continue;
			}

			node = node.replace(property_node, "");
			if ( node == '' )
			{
				continue;
			}

			jQuery('select[name=\'xml_property_id_node\']').append('<option value="' + node + '"' + selected_html + '>' + node + '</option>');
		}
	}
}

function hpf_create_csv_property_id_field_options()
{
	jQuery('select[name=\'csv_property_id_field\']').empty();
	jQuery('select[name=\'image_field\']').empty();
	jQuery('select[name=\'floorplan_field\']').empty();
	jQuery('select[name=\'document_field\']').empty();

	var fields = jQuery('input[name=\'csv_property_field_options\']').val();

	if ( fields == '' )
	{
		return;
	}

	fields = JSON.parse(fields);

	for ( var i in fields )
	{
		var selected_html = '';
		var field = fields[i];
		if ( hpf_original_property_id_field == field )
		{
			selected_html = ' selected';
		}
		jQuery('select[name=\'csv_property_id_field\']').append('<option value="' + field + '"' + selected_html + '>' + field + '</option>');

		var selected_html = '';
		var field = fields[i];
		if ( hpf_original_image_field == field )
		{
			selected_html = ' selected';
		}
		jQuery('select[name=\'image_field\']').append('<option value="' + field + '"' + selected_html + '>' + field + '</option>');

		var selected_html = '';
		var field = fields[i];
		if ( hpf_original_floorplan_field == field )
		{
			selected_html = ' selected';
		}
		jQuery('select[name=\'floorplan_field\']').append('<option value="' + field + '"' + selected_html + '>' + field + '</option>');

		var selected_html = '';
		var field = fields[i];
		if ( hpf_original_document_field == field )
		{
			selected_html = ' selected';
		}
		jQuery('select[name=\'document_field\']').append('<option value="' + field + '"' + selected_html + '>' + field + '</option>');
	}
}

function hpf_create_xml_field_mapping_options()
{	
	jQuery('.xml-rules-available-fields a').draggable( "destroy" );

	jQuery('#no_nodes_found').hide();
	jQuery('#xml-nodes-found').empty()

	var nodes = jQuery('input[name=\'xml_property_node_options\']').val();

	if ( nodes == '' )
	{
		jQuery('#no_nodes_found').show();
		return;
	}

	var property_node = jQuery('select[name=\'xml_property_node\']').val();

	if ( property_node == '' )
	{
		jQuery('#no_nodes_found').show();
		return;
	}

	nodes = JSON.parse(nodes);

	for ( var i in nodes )
	{
		var node = nodes[i]/*.replace(/\/\//g, "/")*/;

		if ( node.indexOf(property_node) == -1 )
		{
			continue;
		}

		node = node.replace(property_node, "");
		if ( node == '' )
		{
			continue;
		}

		jQuery('#xml-nodes-found').append('<a href="#">' + node + '</a>');
	}

	hpf_set_xml_fields_size_properties();

	jQuery('.xml-rules-available-fields a').draggable({
	    revert: true,
	    helper: 'clone',
	    appendTo: 'body'
	});
}

function hpf_create_csv_field_mapping_options()
{	
	jQuery('.csv-rules-available-fields a').draggable( "destroy" );

	jQuery('#no_fields_found').hide();
	jQuery('#csv-fields-found').empty()

	var fields = jQuery('input[name=\'csv_property_field_options\']').val();

	if ( fields == '' )
	{
		jQuery('#no_fields_found').show();
		return;
	}

	fields = JSON.parse(fields);

	for ( var i in fields )
	{
		var field = fields[i];

		jQuery('#csv-fields-found').append('<a href="#">' + field + '</a>');
	}

	hpf_set_csv_fields_size_properties();

	jQuery('.csv-rules-available-fields a').draggable({
	    revert: true,
	    helper: 'clone',
	    appendTo: 'body'
	});
}

function add_field_mapping_or_rule()
{
	if ( jQuery('#field_mapping_rules').length > 0 )
	{
		jQuery('input[name*=\'field_mapping_rules\'][name*=\'[field]\']').droppable( "destroy" );
		jQuery('input[name*=\'field_mapping_rules\'][name*=\'[result]\']').droppable( "destroy" );

		jQuery('select[name*=\'field_mapping_rules\'][name*=\'[houzez_field]\']').select2("destroy");

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

		hpf_rule_count = hpf_rule_count + 1;

		jQuery('#field_mapping_rules').append('<div class="rule-accordion" style="display:none"><div class="rule-accordion-header"><span class="dashicons dashicons-arrow-down-alt2"></span>&nbsp; <span class="rule-description">Rule description here</span><div class="icons"><span class="duplicate-rule dashicons dashicons-admin-page" title="Duplicate Rule"></span><span class="delete-rule dashicons dashicons-trash" title="Delete Rule"></span></div></div><div class="rule-accordion-contents">' + template_html + '</div></div>');
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

		jQuery('input[name*=\'field_mapping_rules\'][name*=\'[field]\']').droppable({
		    drop: function (event, ui) {
		        this.value += jQuery(ui.draggable).text();
		    }
		});

		jQuery('input[name*=\'field_mapping_rules\'][name*=\'[result]\']').droppable({
		    drop: function (event, ui) {
		        this.value += '{' + jQuery(ui.draggable).text() + '}';
		    }
		});

		jQuery('select[name*=\'field_mapping_rules\'][name*=\'[houzez_field]\']').select2({ allowClear: true, placeholder:"Select..." });
	}

	build_field_mapping_rule_accordions()
	hpf_set_xml_fields_size_properties();
	hpf_set_csv_fields_size_properties();
}

function hpf_show_email_reports_settings()
{
	jQuery('.hpf-admin-settings-import-settings #email_reports_to_row').hide();

	if ( jQuery('.hpf-admin-settings-import-settings input[name=\'email_reports\']').is(':checked') )
	{
		jQuery('.hpf-admin-settings-import-settings #email_reports_to_row').show();
	}
}

function hpf_show_missing_mandatory_xml_field_mapping()
{
	jQuery('#missing_mandatory_xml_field_mapping').hide();
	jQuery('#field_mapping_warning').hide();

	var selected_format = jQuery('.hpf-admin-settings-import-settings .settings-panel #format').val();

	if ( selected_format == 'xml' )
	{
		var found_title_excerpt_or_content = false;
		
		// loop through field mapping and ensure at least title, excerpt or content set
		jQuery('select[name*=\'field_mapping_rules\'][name*=\'houzez_field\']').each(function()
		{
			if ( jQuery(this).attr('name') != 'field_mapping_rules[{rule_count}][houzez_field]' )
			{
				if ( jQuery(this).val() == 'post_title' || jQuery(this).val() == 'post_excerpt' || jQuery(this).val() == 'post_content' )
				{
					found_title_excerpt_or_content = true;
				}
			}
		});

		if ( !found_title_excerpt_or_content )
		{
			jQuery('#missing_mandatory_xml_field_mapping').show();
			jQuery('#field_mapping_warning').show();
		}
	}
}

function hpf_show_missing_mandatory_csv_field_mapping()
{
	jQuery('#missing_mandatory_csv_field_mapping').hide();
	jQuery('#field_mapping_warning').hide();

	var selected_format = jQuery('.hpf-admin-settings-import-settings .settings-panel #format').val();

	if ( selected_format == 'csv' )
	{
		var found_title_excerpt_or_content = false;
		
		// loop through field mapping and ensure at least title, excerpt or content set
		jQuery('select[name*=\'field_mapping_rules\'][name*=\'houzez_field\']').each(function()
		{
			if ( jQuery(this).attr('name') != 'field_mapping_rules[{rule_count}][houzez_field]' )
			{
				if ( jQuery(this).val() == 'post_title' || jQuery(this).val() == 'post_excerpt' || jQuery(this).val() == 'post_content' )
				{
					found_title_excerpt_or_content = true;
				}
			}
		});

		if ( !found_title_excerpt_or_content )
		{
			jQuery('#missing_mandatory_csv_field_mapping').show();
			jQuery('#field_mapping_warning').show();
		}
	}
}

function hpf_show_already_mapped_warning()
{
	var selected_format = jQuery('.hpf-admin-settings-import-settings .settings-panel #format').val();

	jQuery('.already-mapped-warning').hide();

	for ( var i in hpf_admin_object.formats )
	{
		if ( i == selected_format )
		{
			if ( hpf_admin_object.formats[i].hasOwnProperty('houzez_fields_imported_by_default') )
			{
				var houzez_fields_imported_by_default = hpf_admin_object.formats[i].houzez_fields_imported_by_default;

				jQuery('select[name*=\'field_mapping_rules\'][name*=\'[houzez_field]\']').each(function()
				{
					if ( Object.values(houzez_fields_imported_by_default).indexOf(jQuery(this).val()) !== -1 ) 
					{
						if ( jQuery(this).val() != '' )
						{
							// We found it already mapped. Show warning
							jQuery(this).parent().find('.already-mapped-field').html( jQuery(this).children(':selected').text().toLowerCase() );
							jQuery(this).parent().find('.already-mapped-warning').show();
						}
					}
				});
			}
		}
	}
}

function hpf_show_format_settings()
{
	var selected_format = jQuery('.hpf-admin-settings-import-settings .settings-panel #format').val();

	jQuery('.hpf-admin-settings-import-settings .settings-panel .import-settings-format').hide();
	jQuery('#import_settings_' + selected_format).fadeIn('fast');

	jQuery('.no-format-notice').hide();
	jQuery('.hpf-import-format-name').html('');

	jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_property_type').hide();
	jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_sales_status').hide();
	jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_lettings_status').hide();

	jQuery('.hpf-admin-settings-import-settings .xml-rules-available-fields').hide();
	jQuery('.hpf-admin-settings-import-settings .csv-rules-available-fields').hide();

	jQuery('#import_setting_tab_taxonomies').show();
	jQuery('#import_setting_tab_contactinfo').show();
	jQuery('#import_setting_tab_media').hide();
	jQuery('#import_setting_tab_enquiries').hide();

	jQuery('#missing_mandatory_xml_field_mapping').hide();
	jQuery('#missing_mandatory_csv_field_mapping').hide();

	jQuery('.hpf-admin-settings-import-settings #property_city_address_field').empty();
	jQuery('.hpf-admin-settings-import-settings #property_area_address_field').empty();
	jQuery('.hpf-admin-settings-import-settings #property_state_address_field').empty();

	jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_table_property_type tr').not(':nth-child(1)').remove();

	jQuery('.hpf-admin-settings-import-settings select[name=\'author_info_rules_field[]\']').empty();
	jQuery('.hpf-admin-settings-import-settings select[name=\'agent_info_rules_field[]\']').empty();
	jQuery('.hpf-admin-settings-import-settings select[name=\'agency_info_rules_field[]\']').empty();

	if ( selected_format == '' )
	{
		jQuery('.no-format-notice').show();
	}
	else
	{
		hpf_show_missing_mandatory_xml_field_mapping();
		hpf_show_missing_mandatory_csv_field_mapping();
		hpf_show_already_mapped_warning();

		if ( selected_format == 'xml' )
		{
			jQuery('#import_setting_tab_taxonomies').hide();
			jQuery('#import_setting_tab_contactinfo').hide();
			jQuery('#import_setting_tab_media').show();
			jQuery('.hpf-admin-settings-import-settings .xml-rules-available-fields').show();
		}

		if ( selected_format == 'csv' )
		{
			jQuery('#import_setting_tab_taxonomies').hide();
			jQuery('#import_setting_tab_contactinfo').hide();
			jQuery('#import_setting_tab_media').show();
			jQuery('.hpf-admin-settings-import-settings .csv-rules-available-fields').show();
		}

		var has_taxonomy_values_sales_status = false;
		var taxonomy_values_sales_status = new Array();

		var has_taxonomy_values_lettings_status = false;
		var taxonomy_values_lettings_status = new Array();
		
		var has_taxonomy_values_property_type = false;
		var taxonomy_values_property_type = new Array();

		var address_fields = new Array();
		var contact_information_fields = new Array();

		for ( var i in hpf_admin_object.formats )
		{
			if ( i == selected_format )
			{
				if ( hpf_admin_object.formats[i].taxonomy_values.hasOwnProperty('sales_status') )
				{
					has_taxonomy_values_sales_status = true;
					if ( Object.keys(hpf_admin_object.formats[i].taxonomy_values.sales_status).length > 0 ) 
					{ 
						taxonomy_values_sales_status = hpf_admin_object.formats[i].taxonomy_values.sales_status; 
					}
				}
				
				if ( hpf_admin_object.formats[i].taxonomy_values.hasOwnProperty('lettings_status') )
				{
					has_taxonomy_values_lettings_status = true;
					if ( Object.keys(hpf_admin_object.formats[i].taxonomy_values.lettings_status).length > 0 ) 
					{ 
						taxonomy_values_lettings_status = hpf_admin_object.formats[i].taxonomy_values.lettings_status; 
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
				
				address_fields = hpf_admin_object.formats[i].address_fields;
				if ( hpf_admin_object.formats[i].hasOwnProperty('contact_information_fields') && Object.keys(hpf_admin_object.formats[i].contact_information_fields).length > 0 ) { contact_information_fields = hpf_admin_object.formats[i].contact_information_fields; }

				jQuery('.hpf-import-format-name').html(hpf_admin_object.formats[i].name);

				if ( hpf_admin_object.formats[i].hasOwnProperty('export_enquiries') && hpf_admin_object.formats[i].hasOwnProperty('export_enquiries') == true )
				{
					jQuery('#import_setting_tab_enquiries').show();
				}

				break;
			}
		}

		// Sales status taxonomy mapping
		if ( has_taxonomy_values_sales_status )
		{
			jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_sales_status').show();

			if ( Object.keys(taxonomy_values_sales_status).length > 0 )
			{
				for ( var i in taxonomy_values_sales_status )
				{
					var row_html_dropdown = '';
					row_html_dropdown += '<select name="taxonomy_mapping[sales_status][' + i + ']">';
					row_html_dropdown += '<option value=""></option>';
					if ( Object.keys(hpf_admin_object.statuses).length > 0 )
					{	
						for ( var j in hpf_admin_object.statuses )
						{
							var selected_status = false;
							if ( 
								hpf_admin_object.import_settings.hasOwnProperty('mappings') && 
								hpf_admin_object.import_settings.mappings.hasOwnProperty('sales_status') &&
								hpf_admin_object.import_settings.mappings.sales_status.hasOwnProperty(i)
							)
							{
								if ( hpf_admin_object.import_settings.mappings.sales_status[i] == j )
								{
									selected_status = true;
								}
							}
							if ( !selected_status )
							{
								// TO DO: set by default if match found
							}
							row_html_dropdown += '<option value="' + j + '"' + ( selected_status ? ' selected' : '' ) + '>' + hpf_admin_object.statuses[j] + '</option>';
						}
					}
					row_html_dropdown += '</select>';

					var row_html = '';
					row_html += '<tr>';
					row_html += '<td style="padding-left:0">' + i + ( taxonomy_values_sales_status[i] != i ? ' - <span style="color:#999">' + taxonomy_values_sales_status[i] + '</span>' : '' )  + '</td>';
					row_html += '<td style="padding-left:0">' + row_html_dropdown + '</td>';
					row_html += '</tr>';

					jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_table_sales_status').append(row_html);
				}
			}

			// add any custom mappings
			if ( hpf_admin_object.import_settings.hasOwnProperty('mappings') && hpf_admin_object.import_settings.mappings.hasOwnProperty('sales_status') && Object.keys(hpf_admin_object.import_settings.mappings.sales_status).length > 0 )
			{
				for ( var i in hpf_admin_object.import_settings.mappings.sales_status )
				{
					var found_in_standard_list = false;
					for ( var j in taxonomy_values_sales_status )
					{
						if ( i == j )
						{
							found_in_standard_list = true;
							break;
						}
					}
					if ( !found_in_standard_list )
					{
						var row_html_dropdown = '';
						row_html_dropdown += '<select name="custom_mapping_value[sales_status][' + i + ']">';
						row_html_dropdown += '<option value=""></option>';
						if ( Object.keys(hpf_admin_object.statuses).length > 0 )
						{	
							for ( var j in hpf_admin_object.statuses )
							{
								var selected_status = false;
								if ( 
									hpf_admin_object.import_settings.hasOwnProperty('mappings') && 
									hpf_admin_object.import_settings.mappings.hasOwnProperty('sales_status') &&
									hpf_admin_object.import_settings.mappings.sales_status.hasOwnProperty(i)
								)
								{
									if ( hpf_admin_object.import_settings.mappings.sales_status[i] == j )
									{
										selected_status = true;
									}
								}
								if ( !selected_status )
								{
									// TO DO: set by default if match found
								}
								row_html_dropdown += '<option value="' + j + '"' + ( selected_status ? ' selected' : '' ) + '>' + hpf_admin_object.statuses[j] + '</option>';
							}
						}
						row_html_dropdown += '</select>';

						var row_html = '';
						row_html += '<tr>';
						row_html += '<td style="padding-left:0"><input type="text" name="custom_mapping[sales_status][' + i + ']" value="' + i + '"></td>';
						row_html += '<td style="padding-left:0">' + row_html_dropdown + '</td>';
						row_html += '</tr>';

						jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_table_sales_status').append(row_html);
					}
				}
			}
		}

		// Lettings status taxonomy mapping
		if ( has_taxonomy_values_lettings_status )
		{
			jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_lettings_status').show();

			if ( Object.keys(taxonomy_values_lettings_status).length > 0 )
			{
				for ( var i in taxonomy_values_lettings_status )
				{
					var row_html_dropdown = '';
					row_html_dropdown += '<select name="taxonomy_mapping[lettings_status][' + i + ']">';
					row_html_dropdown += '<option value=""></option>';
					if ( Object.keys(hpf_admin_object.statuses).length > 0 )
					{	
						for ( var j in hpf_admin_object.statuses )
						{
							var selected_status = false;
							if ( 
								hpf_admin_object.import_settings.hasOwnProperty('mappings') && 
								hpf_admin_object.import_settings.mappings.hasOwnProperty('lettings_status') &&
								hpf_admin_object.import_settings.mappings.lettings_status.hasOwnProperty(i)
							)
							{
								if ( hpf_admin_object.import_settings.mappings.lettings_status[i] == j )
								{
									selected_status = true;
								}
							}
							if ( !selected_status )
							{
								// TO DO: set by default if match found
							}
							row_html_dropdown += '<option value="' + j + '"' + ( selected_status ? ' selected' : '' ) + '>' + hpf_admin_object.statuses[j] + '</option>';
						}
					}
					row_html_dropdown += '</select>';

					var row_html = '';
					row_html += '<tr>';
					row_html += '<td style="padding-left:0">' + i + ( taxonomy_values_lettings_status[i] != i ? ' - <span style="color:#999">' + taxonomy_values_lettings_status[i] + '</span>' : '' )  + '</td>';
					row_html += '<td style="padding-left:0">' + row_html_dropdown + '</td>';
					row_html += '</tr>';

					jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_table_lettings_status').append(row_html);
				}
			}

			// add any custom mappings
			if ( hpf_admin_object.import_settings.hasOwnProperty('mappings') && hpf_admin_object.import_settings.mappings.hasOwnProperty('lettings_status') && Object.keys(hpf_admin_object.import_settings.mappings.lettings_status).length > 0 )
			{
				for ( var i in hpf_admin_object.import_settings.mappings.lettings_status )
				{
					var found_in_standard_list = false;
					for ( var j in taxonomy_values_lettings_status )
					{
						if ( i == j )
						{
							found_in_standard_list = true;
							break;
						}
					}
					if ( !found_in_standard_list )
					{
						var row_html_dropdown = '';
						row_html_dropdown += '<select name="custom_mapping_value[lettings_status][' + i + ']">';
						row_html_dropdown += '<option value=""></option>';
						if ( Object.keys(hpf_admin_object.statuses).length > 0 )
						{	
							for ( var j in hpf_admin_object.statuses )
							{
								var selected_status = false;
								if ( 
									hpf_admin_object.import_settings.hasOwnProperty('mappings') && 
									hpf_admin_object.import_settings.mappings.hasOwnProperty('lettings_status') &&
									hpf_admin_object.import_settings.mappings.lettings_status.hasOwnProperty(i)
								)
								{
									if ( hpf_admin_object.import_settings.mappings.lettings_status[i] == j )
									{
										selected_status = true;
									}
								}
								if ( !selected_status )
								{
									// TO DO: set by default if match found
								}
								row_html_dropdown += '<option value="' + j + '"' + ( selected_status ? ' selected' : '' ) + '>' + hpf_admin_object.statuses[j] + '</option>';
							}
						}
						row_html_dropdown += '</select>';

						var row_html = '';
						row_html += '<tr>';
						row_html += '<td style="padding-left:0"><input type="text" name="custom_mapping[lettings_status][' + i + ']" value="' + i + '"></td>';
						row_html += '<td style="padding-left:0">' + row_html_dropdown + '</td>';
						row_html += '</tr>';

						jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_table_lettings_status').append(row_html);
					}
				}
			}
		}

		// Property type taxonomy mapping
		if ( has_taxonomy_values_property_type )
		{
			jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_property_type').show();

			if ( Object.keys(taxonomy_values_property_type).length > 0 )
			{
				for ( var i in taxonomy_values_property_type )
				{
					var row_html_dropdown = '';
					row_html_dropdown += '<select name="taxonomy_mapping[property_type][' + i + ']">';
					row_html_dropdown += '<option value=""></option>';
					if ( Object.keys(hpf_admin_object.property_types).length > 0 )
					{	
						for ( var j in hpf_admin_object.property_types )
						{
							var selected_status = false;
							if ( 
								hpf_admin_object.import_settings.hasOwnProperty('mappings') && 
								hpf_admin_object.import_settings.mappings.hasOwnProperty('property_type') &&
								hpf_admin_object.import_settings.mappings.property_type.hasOwnProperty(i)
							)
							{
								if ( hpf_admin_object.import_settings.mappings.property_type[i] == j )
								{
									selected_status = true;
								}
							}
							if ( !selected_status )
							{
								// TO DO: set by default if match found
							}
							row_html_dropdown += '<option value="' + j + '"' + ( selected_status ? ' selected' : '' ) + '>' + hpf_admin_object.property_types[j] + '</option>';
						}
					}
					row_html_dropdown += '</select>';

					var row_html = '';
					row_html += '<tr>';
					row_html += '<td style="padding-left:0">' + i + ( taxonomy_values_property_type[i] != i ? ' - <span style="color:#999">' + taxonomy_values_property_type[i] + '</span>' : '' )  + '</td>';
					row_html += '<td style="padding-left:0">' + row_html_dropdown + '</td>';
					row_html += '</tr>';

					jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_table_property_type').append(row_html);
				}
			}

			// add any custom mappings
			if ( hpf_admin_object.import_settings.hasOwnProperty('mappings') && hpf_admin_object.import_settings.mappings.hasOwnProperty('property_type') && Object.keys(hpf_admin_object.import_settings.mappings.property_type).length > 0 )
			{
				for ( var i in hpf_admin_object.import_settings.mappings.property_type )
				{
					var found_in_standard_list = false;
					for ( var j in taxonomy_values_property_type )
					{
						if ( i == j )
						{
							found_in_standard_list = true;
							break;
						}
					}
					if ( !found_in_standard_list )
					{
						var row_html_dropdown = '';
						row_html_dropdown += '<select name="custom_mapping_value[property_type][' + i + ']">';
						row_html_dropdown += '<option value=""></option>';
						if ( Object.keys(hpf_admin_object.property_types).length > 0 )
						{	
							for ( var j in hpf_admin_object.property_types )
							{
								var selected_status = false;
								if ( 
									hpf_admin_object.import_settings.hasOwnProperty('mappings') && 
									hpf_admin_object.import_settings.mappings.hasOwnProperty('property_type') &&
									hpf_admin_object.import_settings.mappings.property_type.hasOwnProperty(i)
								)
								{
									if ( hpf_admin_object.import_settings.mappings.property_type[i] == j )
									{
										selected_status = true;
									}
								}
								if ( !selected_status )
								{
									// TO DO: set by default if match found
								}
								row_html_dropdown += '<option value="' + j + '"' + ( selected_status ? ' selected' : '' ) + '>' + hpf_admin_object.property_types[j] + '</option>';
							}
						}
						row_html_dropdown += '</select>';

						var row_html = '';
						row_html += '<tr>';
						row_html += '<td style="padding-left:0"><input type="text" name="custom_mapping[property_type][' + i + ']" value="' + i + '"></td>';
						row_html += '<td style="padding-left:0">' + row_html_dropdown + '</td>';
						row_html += '</tr>';

						jQuery('.hpf-admin-settings-import-settings #taxonomy_mapping_table_property_type').append(row_html);
					}
				}
			}
		}

		// Address taxonomy options
		if ( address_fields.length > 0 )
		{
			jQuery('.hpf-admin-settings-import-settings #property_city_address_field').append('<option value=""></option');
			for ( var i in address_fields )
			{
				var selected_status = false;
				if ( hpf_admin_object.import_settings.hasOwnProperty('property_city_address_field') )
				{
					if ( hpf_admin_object.import_settings.property_city_address_field == address_fields[i] )
					{
						selected_status = true;
					}
				}
				jQuery('.hpf-admin-settings-import-settings #property_city_address_field').append('<option value="' + address_fields[i] + '"' + ( selected_status ? ' selected' : '' ) + '>' + address_fields[i] + '</option');
			}

			jQuery('.hpf-admin-settings-import-settings #property_area_address_field').append('<option value=""></option');
			for ( var i in address_fields )
			{
				var selected_status = false;
				if ( hpf_admin_object.import_settings.hasOwnProperty('property_area_address_field') )
				{
					if ( hpf_admin_object.import_settings.property_area_address_field == address_fields[i] )
					{
						selected_status = true;
					}
				}
				jQuery('.hpf-admin-settings-import-settings #property_area_address_field').append('<option value="' + address_fields[i] + '"' + ( selected_status ? ' selected' : '' ) + '>' + address_fields[i] + '</option');
			}

			jQuery('.hpf-admin-settings-import-settings #property_state_address_field').append('<option value=""></option');
			for ( var i in address_fields )
			{
				var selected_status = false;
				if ( hpf_admin_object.import_settings.hasOwnProperty('property_state_address_field') )
				{
					if ( hpf_admin_object.import_settings.property_state_address_field == address_fields[i] )
					{
						selected_status = true;
					}
				}
				jQuery('.hpf-admin-settings-import-settings #property_state_address_field').append('<option value="' + address_fields[i] + '"' + ( selected_status ? ' selected' : '' ) + '>' + address_fields[i] + '</option');
			}
		}

		if ( Object.keys(contact_information_fields).length > 0 )
		{
			var rules = hpf_admin_object.import_settings.agent_display_option_rules;

			var rule_i = -1;
			jQuery('.hpf-admin-settings-import-settings select[name=\'author_info_rules_field[]\']').each(function()
			{
				jQuery(this).append('<option value=""></option>');

				for ( var i in contact_information_fields )
				{
					var selected_status = false;
					
					var option_value = contact_information_fields[i];

					// get selected field at this rule
					for ( var j in rules )
					{
						if ( j == rule_i )
						{
							if ( option_value == rules[j].field )
							{
								selected_status = true;
							}
						}
					}

					jQuery(this).append('<option value="' + contact_information_fields[i] + '"' + ( selected_status ? ' selected' : '' ) + '>' + contact_information_fields[i] + '</option>');
				}

				rule_i = rule_i + 1;
			});

			var rule_i = -1;
			jQuery('.hpf-admin-settings-import-settings select[name=\'agent_info_rules_field[]\']').each(function()
			{
				jQuery(this).append('<option value=""></option>');

				for ( var i in contact_information_fields )
				{
					var selected_status = false;
					
					var option_value = contact_information_fields[i];

					// get selected field at this rule
					for ( var j in rules )
					{
						if ( j == rule_i )
						{
							if ( option_value == rules[j].field )
							{
								selected_status = true;
							}
						}
					}

					jQuery(this).append('<option value="' + contact_information_fields[i] + '"' + ( selected_status ? ' selected' : '' ) + '>' + contact_information_fields[i] + '</option>');
				}

				rule_i = rule_i + 1;
			});

			var rule_i = -1;
			jQuery('.hpf-admin-settings-import-settings select[name=\'agency_info_rules_field[]\']').each(function()
			{
				jQuery(this).append('<option value=""></option>');

				for ( var i in contact_information_fields )
				{
					var selected_status = false;
					
					var option_value = contact_information_fields[i];

					// get selected field at this rule
					for ( var j in rules )
					{
						if ( j == rule_i )
						{
							if ( option_value == rules[j].field )
							{
								selected_status = true;
							}
						}
					}
					
					jQuery(this).append('<option value="' + contact_information_fields[i] + '"' + ( selected_status ? ' selected' : '' ) + '>' + contact_information_fields[i] + '</option>');
				}

				rule_i = rule_i + 1;
			});
		}
	}
}

function hpf_show_contact_info_rules()
{
	var selected_agent_display_option = jQuery('.hpf-admin-settings-import-settings .settings-panel input[name=\'agent_display_option\']:checked').val();

	jQuery('.hpf-admin-settings-import-settings .agent-display-option-rules').hide();
	jQuery('#agent_display_option_rules_container_' + selected_agent_display_option).fadeIn('fast');
}