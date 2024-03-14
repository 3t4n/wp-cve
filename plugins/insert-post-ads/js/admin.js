jQuery(document).ready(function() {	
	jQuery(window).resize(function() {
		jQuery('.insert-ads .plugin-card').each(function() {	
			jQuery(this).css('height', 'auto');
		});
		jQuery('.insert-ads .plugin-card').each(function() {		
			var initialCard = jQuery(this);
			var rowTop = initialCard.position().top;
			jQuery('.insert-ads .plugin-card').each(function() {		
				if(rowTop == jQuery(this).position().top) {
					if(initialCard.height() < jQuery(this).height()) {
						initialCard.height(jQuery(this).height());
					}
				}
			});
		});
	});
	jQuery(window).resize();
	
	insert_ads_click_handler(
		'insert_ads_abtesting_configuration',
		'Multiple Ad Networks / A-B Testing : Configuration',
		'480',
		'480',
		function() { },
		function() {
			jQuery.post(
				jQuery('#insert_ads_admin_ajax').val(), {
					'action': "insert_ads_abtesting_configuration_form_save_action",
					'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
					'insert_ads_abtesting_mode': jQuery('input[name=insert_ads_abtesting_mode]:checked').val()
				}, function(response) { }			
			);
		},
		function() { }
	);
	
	insert_ads_click_handler(
		'insert_ads_trackingcodes_google_analytics',
		'Tracking Codes : Google Analytics',
		'480',
		'480',
		function() {
			jQuery('#insert_ads_trackingcodes_analytics_status').parent().css({'display': 'inline-block', 'margin': '5px 0 0'}).prependTo('.ui-dialog-buttonpane');
		},
		function() {
			jQuery.post(
				jQuery('#insert_ads_admin_ajax').val(), {
					'action': 'insert_ads_trackingcodes_google_analytics_form_save_action',
					'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
					'insert_ads_trackingcodes_analytics_status': jQuery('#insert_ads_trackingcodes_analytics_status').prop('checked'),
					'insert_ads_trackingcodes_analytics_code': jQuery('#insert_ads_trackingcodes_analytics_code').val(),
				}, function(response) { }
			);
		},
		function() { }
	);
	
	insert_ads_click_handler(
		'insert_ads_trackingcodes_header',
		'Tracking Codes : Header',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() {
			jQuery('#insert_ads_trackingcodes_header_code').css('height', (jQuery("body").height() * 0.5)+'px');
			jQuery('#insert_ads_trackingcodes_header_status').parent().css({'display': 'inline-block', 'margin': '5px 0 0'}).prependTo('.ui-dialog-buttonpane');
		},
		function() {
			jQuery.post(
				jQuery('#insert_ads_admin_ajax').val(), {
					'action': 'insert_ads_trackingcodes_header_form_save_action',
					'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
					'insert_ads_trackingcodes_header_status': jQuery('#insert_ads_trackingcodes_header_status').prop('checked'),
					'insert_ads_trackingcodes_header_code': jQuery('#insert_ads_trackingcodes_header_code').val(),
				}, function(response) { }
			);
		},
		function() { }
	);
	
	insert_ads_click_handler(
		'insert_ads_trackingcodes_footer',
		'Tracking Codes : Footer',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() {
			jQuery('#insert_ads_trackingcodes_footer_code').css('height', (jQuery("body").height() * 0.5)+'px');
			jQuery('#insert_ads_trackingcodes_footer_status').parent().css({'display': 'inline-block', 'margin': '5px 0 0'}).prependTo('.ui-dialog-buttonpane');
		},
		function() {
			jQuery.post(
				jQuery('#insert_ads_admin_ajax').val(), {
					'action': 'insert_ads_trackingcodes_footer_form_save_action',
					'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
					'insert_ads_trackingcodes_footer_status': jQuery('#insert_ads_trackingcodes_footer_status').prop('checked'),
					'insert_ads_trackingcodes_footer_code': jQuery('#insert_ads_trackingcodes_footer_code').val(),
				}, function(response) { }
			);
		},
		function() { }
	);
	
	insert_ads_click_handler(
		'insert_ads_legalpages_privacy_policy',
		'Legal Pages : Privacy Policy',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() { },
		function() {
			jQuery.post(
				jQuery('#insert_ads_admin_ajax').val(), {
					'action': 'insert_ads_legalpages_privacy_policy_form_save_action',
					'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
					'insert_ads_legalpages_privacy_policy_content': jQuery('#insert_ads_legalpages_privacy_policy_content').val(),
					'insert_ads_legalpages_privacy_policy_assigned_page': jQuery('#insert_ads_legalpages_privacy_policy_assigned_page').val(),
				}, function(response) { }
			);
		},
		function() { }
	);
	
	insert_ads_click_handler(
		'insert_ads_legalpages_terms_conditions',
		'Legal Pages : Terms and Conditions',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() { },
		function() {
			jQuery.post(
				jQuery('#insert_ads_admin_ajax').val(), {
					'action': 'insert_ads_legalpages_terms_conditions_form_save_action',
					'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
					'insert_ads_legalpages_terms_conditions_content': jQuery('#insert_ads_legalpages_terms_conditions_content').val(),
					'insert_ads_legalpages_terms_conditions_assigned_page': jQuery('#insert_ads_legalpages_terms_conditions_assigned_page').val(),
				}, function(response) { }
			);
		},
		function() { }
	);
	
	insert_ads_click_handler(
		'insert_ads_legalpages_disclaimer',
		'Legal Pages : Disclaimer',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() { },
		function() {
			jQuery.post(
				jQuery('#insert_ads_admin_ajax').val(), {
					'action': 'insert_ads_legalpages_disclaimer_form_save_action',
					'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
					'insert_ads_legalpages_disclaimer_content': jQuery('#insert_ads_legalpages_disclaimer_content').val(),
					'insert_ads_legalpages_disclaimer_assigned_page': jQuery('#insert_ads_legalpages_disclaimer_assigned_page').val(),
				}, function(response) { }
			);
		},
		function() { }
	);
	
	insert_ads_click_handler(
		'insert_ads_legalpages_copyright',
		'Legal Pages : Copyright Notice',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() { },
		function() {
			jQuery.post(
				jQuery('#insert_ads_admin_ajax').val(), {
					'action': 'insert_ads_legalpages_copyright_form_save_action',
					'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
					'insert_ads_legalpages_copyright_content': jQuery('#insert_ads_legalpages_copyright_content').val(),
					'insert_ads_legalpages_copyright_assigned_page': jQuery('#insert_ads_legalpages_copyright_assigned_page').val(),
				}, function(response) { }
			);
		},
		function() { }
	);
	
	insert_ads_click_handler(
		'insert_ads_adstxt_generate',
		'Create / Update ads.txt',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() {
			jQuery('#insert_ads_adstxt_content').css('height', (jQuery('body').height() * 0.5)+'px');
			jQuery('.ui-dialog-buttonset').find('button').first().unbind('click').click(function() {
				jQuery('.ui-dialog-buttonset').find('button').last().button('disable');
				jQuery('.ui-dialog-titlebar').find('button').last().button('disable');
				var insert_ads_adstxt_content =  jQuery('#insert_ads_adstxt_content').val();
				jQuery('.ui-dialog-content').html('<div class="insert_ads_ajaxloader"></div>');
				jQuery('.insert_ads_ajaxloader').show();
				jQuery.post(
					jQuery('#insert_ads_admin_ajax').val(), {
						'action': 'insert_ads_adstxt_generate_form_save_action',
						'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
						'insert_ads_adstxt_content': insert_ads_adstxt_content,
					}, function(response) {
						if(response == '###SUCCESS###') {
							jQuery('.ui-dialog-titlebar').find('button').last().button('enable').click();
						} else {
							jQuery('.ui-dialog-buttonset').find('button').first().button('disable');
							jQuery('.ui-dialog-buttonset').find('button').last().button('enable');
							jQuery('.ui-dialog-titlebar').find('button').last().button('enable');
							jQuery('.ui-dialog-content').html(response);
						}
					}
				);
			});
		},
		function() { },
		function() { }
	);
	
	insert_ads_inpostads_click_handler('above');
	insert_ads_inpostads_click_handler('middle');
	insert_ads_inpostads_click_handler('below');
	insert_ads_inpostads_click_handler('left');
	insert_ads_inpostads_click_handler('right');
	
	insert_ads_click_handler(
		'insert_ads_adwidgets_new',
		'Ad Widget : Add New',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() {
			jQuery('.insert_ads_adwidgets_status').parent().css({'display': 'inline-block', 'margin': '5px 0 0'}).prependTo('.ui-dialog-buttonpane');
		},
		function() {
			var identifier = jQuery(".insert_ads_adwidgets_identifier").val();
			var adwidgetLink = jQuery("<a></a>");
			adwidgetLink.attr("id", "insert_ads_adwidgets_"+identifier);
			adwidgetLink.attr("href", "javascript:;");
			adwidgetLink.attr("onClick", "insert_ads_adwidgets_click_handler(\'"+identifier+"\', \'"+jQuery("#insert_ads_adwidgets_"+identifier+"_title").val()+"\')");
			adwidgetLink.html("Ad Widget : "+jQuery("#insert_ads_adwidgets_"+identifier+"_title").val());
			var deleteButton = jQuery("<span></span>");
			deleteButton.attr("class", "dashicons dashicons-dismiss insert_ads_delete_icon");
			deleteButton.attr("onClick", "insert_ads_adwidgets_remove(\'"+identifier+"\')");
			jQuery("#insert_ads_adwidgets_new").parent().before(jQuery("<p></p>").append(adwidgetLink, deleteButton));
			insert_ads_adwidgets_update(identifier);
		},
		function() { }
	);
	
	insert_ads_click_handler(
		'insert_ads_inthemeads_new',
		'In-Theme Ad : Add New',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() {
			jQuery('.insert_ads_inthemeads_status').parent().css({'display': 'inline-block', 'margin': '5px 0 0'}).prependTo('.ui-dialog-buttonpane');
		},
		function() {
			var identifier = jQuery(".insert_ads_inthemeads_identifier").val();
			var inthemeadLink = jQuery("<a></a>");
			inthemeadLink.attr("id", "insert_ads_inthemeads_"+identifier);
			inthemeadLink.attr("href", "javascript:;");
			inthemeadLink.attr("onClick", "insert_ads_inthemeads_click_handler(\'"+identifier+"\', \'"+jQuery("#insert_ads_inthemeads_"+identifier+"_title").val()+"\')");
			inthemeadLink.html("Ad Widget : "+jQuery("#insert_ads_inthemeads_"+identifier+"_title").val());
			var deleteButton = jQuery("<span></span>");
			deleteButton.attr("class", "dashicons dashicons-dismiss insert_ads_delete_icon");
			deleteButton.attr("onClick", "insert_ads_inthemeads_remove(\'"+identifier+"\')");
			jQuery("#insert_ads_inthemeads_new").parent().before(jQuery("<p></p>").append(inthemeadLink, deleteButton));
			insert_ads_inthemeads_update(identifier);
		},
		function() { }
	);
	
	insert_ads_vi_signup_handler();	
	insert_ads_vi_login_handler();
	insert_ads_vi_logout_handler();
	insert_ads_vi_customize_adcode();
	insert_ads_vi_chart_draw()
});

function primary_ad_code_type_change() {
	var location = '';
	if(jQuery('#insert_ads_inpostads_above_primary_ad_code_type').length != 0) {
		location = 'above';
	} else if(jQuery('#insert_ads_inpostads_middle_primary_ad_code_type').length != 0) {
		location = 'middle';
	} else {
		location = '';
	}
	if(location != '') {
		jQuery('#insert_ads_inpostads_'+location+'_primary_ad_code_type').parent().hide();
		jQuery('#insert_ads_inpostads_'+location+'_primary_ad_code_type').change(function() {
			jQuery('.isSelectedIndicator').removeClass('active');
			jQuery('#primary_ad_code_type_'+jQuery(this).val()).addClass('active');
		});
		jQuery('#insert_ads_inpostads_'+location+'_primary_ad_code_type').change();
		
		jQuery('#primary_ad_code_type_generic').click(function() {
			jQuery('#insert_ads_inpostads_'+location+'_primary_ad_code_type').val('generic');
			jQuery('#insert_ads_inpostads_'+location+'_primary_ad_code_type').change();
		});
		jQuery('#primary_ad_code_type_generic').parent().click(function() {
			jQuery('#insert_ads_inpostads_'+location+'_primary_ad_code_type').val('generic');
			jQuery('#insert_ads_inpostads_'+location+'_primary_ad_code_type').change();
		});
		
		jQuery('#primary_ad_code_type_vicode').click(function() {
			if(!jQuery('#primary_ad_code_type_vicode').hasClass('disabled')) {
				jQuery('#insert_ads_inpostads_'+location+'_primary_ad_code_type').val('vicode');
				jQuery('#insert_ads_inpostads_'+location+'_primary_ad_code_type').change();			
			}
		});
		jQuery('#primary_ad_code_type_vicode').parent().click(function() {
			if(!jQuery('#primary_ad_code_type_vicode').hasClass('disabled')) {
				jQuery('#insert_ads_inpostads_'+location+'_primary_ad_code_type').val('vicode');
				jQuery('#insert_ads_inpostads_'+location+'_primary_ad_code_type').change();
			}
		});	
		jQuery('#primary_ad_code_type_vicode').parents('.smartlogixSectionTitle').on('click', function(){
			if (!jQuery(document).find('#insert_ads_inpostads_above_status').next().hasClass('checked')){
				jQuery(document).find('#insert_ads_inpostads_above_status').next().trigger('click');
			}
		})
	}
}

function insert_ads_inpostads_vi_customize_adcode() {
	jQuery('#insert_ads_inpostads_vi_customize_adcode').click(function() {
		jQuery('.ui-dialog-titlebar').find('button').last().button('enable').click();
		jQuery('#insert_ads_vi_customize_adcode').click();
	});
}

function insert_ads_inpostads_click_handler(location) {
	insert_ads_click_handler(
		'insert_ads_inpostads_'+location,
		'Inpost Ads : Ad '+location.charAt(0).toUpperCase()+location.slice(1)+' Post Content',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.9,
		function() {
			var toggleText = '';
			if (jQuery('#insert_ads_inpostads_above_status').is(':checked')){
				toggleText = 'Press to switch off vi stories';
			} else {
				toggleText = 'Press to activate vi stories';
			}
			jQuery('#insert_ads_inpostads_above_status + div').click(function(){
				if (jQuery('#insert_ads_inpostads_above_status').is(':checked')){
				toggleText = 'Press to switch off vi stories';
				jQuery('#toggletext').text(toggleText);
				
			} else {
				toggleText = 'Press to activate vi stories';
				jQuery('#toggletext').text(toggleText);
			}
			})
			jQuery('#insert_ads_inpostads_'+location+'_status').parent().addClass('pwithcheck').css({'display': 'block', 'margin': '10px 0'}).append('<span id="toggletext" style="padding-left:20px; position: relative; top: -10px;">' + toggleText + '</span>').appendTo('.ui-accordion-content:eq(0) .insert_ads_rule_block .smartlogixSectionInner');
		},
		function() {
			args = {};
			args['action'] = 'insert_ads_inpostads_'+location+'_form_save_action';
			args['insert_ads_nonce'] = jQuery('#insert_ads_nonce').val();
			args['insert_ads_inpostads_'+location+'_status'] = jQuery('#insert_ads_inpostads_'+location+'_status').prop('checked');
			
			if(location == 'above' || location == 'middle') {
				args['insert_ads_inpostads_'+location+'_primary_ad_code_type'] = jQuery('#insert_ads_inpostads_'+location+'_primary_ad_code_type').val();
			}
			args['insert_ads_inpostads_'+location+'_primary_ad_code'] = jQuery('#insert_ads_inpostads_'+location+'_primary_ad_code').val();
			args['insert_ads_inpostads_'+location+'_secondary_ad_code'] = jQuery('#insert_ads_inpostads_'+location+'_secondary_ad_code').val();
			args['insert_ads_inpostads_'+location+'_tertiary_ad_code'] = jQuery('#insert_ads_inpostads_'+location+'_tertiary_ad_code').val();
			
			args['insert_ads_inpostads_'+location+'_rules_exclude_loggedin'] = jQuery('#insert_ads_inpostads_'+location+'_rules_exclude_loggedin').prop('checked');
			args['insert_ads_inpostads_'+location+'_rules_exclude_mobile_devices'] = jQuery('#insert_ads_inpostads_'+location+'_rules_exclude_mobile_devices').prop('checked');
			args['insert_ads_inpostads_'+location+'_rules_exclude_404'] = jQuery('#insert_ads_inpostads_'+location+'_rules_exclude_404').prop('checked');
			args['insert_ads_inpostads_'+location+'_rules_exclude_home'] = jQuery('#insert_ads_inpostads_'+location+'_rules_exclude_home').prop('checked');
			args['insert_ads_inpostads_'+location+'_rules_home_instances'] = jQuery.map(jQuery('#insert_ads_inpostads_'+location+'_rules_home_instances :selected'), function(e) { return jQuery(e).val(); });
			args['insert_ads_inpostads_'+location+'_rules_exclude_archives'] = jQuery('#insert_ads_inpostads_'+location+'_rules_exclude_archives').prop('checked');
			args['insert_ads_inpostads_'+location+'_rules_archives_instances'] = jQuery.map(jQuery('#insert_ads_inpostads_'+location+'_rules_archives_instances :selected'), function(e) { return jQuery(e).val(); });
			args['insert_ads_inpostads_'+location+'_rules_exclude_search'] = jQuery('#insert_ads_inpostads_'+location+'_rules_exclude_search').prop('checked');
			args['insert_ads_inpostads_'+location+'_rules_search_instances'] = jQuery.map(jQuery('#insert_ads_inpostads_'+location+'_rules_search_instances :selected'), function(e) { return jQuery(e).val(); });
			args['insert_ads_inpostads_'+location+'_rules_exclude_page'] = jQuery('#insert_ads_inpostads_'+location+'_rules_exclude_page').prop('checked');
			args['insert_ads_inpostads_'+location+'_rules_page_exceptions'] = jQuery.map(jQuery('#insert_ads_inpostads_'+location+'_rules_page_exceptions :selected'), function(e) { return jQuery(e).val(); });
			args['insert_ads_inpostads_'+location+'_rules_exclude_post'] = jQuery('#insert_ads_inpostads_'+location+'_rules_exclude_post').prop('checked');
			args['insert_ads_inpostads_'+location+'_rules_post_exceptions'] = jQuery.map(jQuery('#insert_ads_inpostads_'+location+'_rules_post_exceptions :selected'), function(e) { return jQuery(e).val(); });
			args['insert_ads_inpostads_'+location+'_rules_post_categories_exceptions'] = jQuery.map(jQuery('#insert_ads_inpostads_'+location+'_rules_post_categories_exceptions :selected'), function(e) { return jQuery(e).val(); });
			args['insert_ads_inpostads_'+location+'_rules_exclude_categories'] = jQuery('#insert_ads_inpostads_'+location+'_rules_exclude_categories').prop('checked');
			args['insert_ads_inpostads_'+location+'_rules_categories_instances'] = jQuery.map(jQuery('#insert_ads_inpostads_'+location+'_rules_categories_instances :selected'), function(e) { return jQuery(e).val(); });
			args['insert_ads_inpostads_'+location+'_rules_categories_exceptions'] = jQuery.map(jQuery('#insert_ads_inpostads_'+location+'_rules_categories_exceptions :selected'), function(e) { return jQuery(e).val(); });
			
			args['insert_ads_inpostads_'+location+'_geo_group1_countries'] = jQuery.map(jQuery('#insert_ads_inpostads_'+location+'_geo_group1_countries :selected'), function(e) { return jQuery(e).val(); });
			args['insert_ads_inpostads_'+location+'_geo_group1_adcode'] = jQuery('#insert_ads_inpostads_'+location+'_geo_group1_adcode').val();
			args['insert_ads_inpostads_'+location+'_geo_group2_countries'] = jQuery.map(jQuery('#insert_ads_inpostads_'+location+'_geo_group2_countries :selected'), function(e) { return jQuery(e).val(); });
			args['insert_ads_inpostads_'+location+'_geo_group2_adcode'] = jQuery('#insert_ads_inpostads_'+location+'_geo_group2_adcode').val();
			
			args['insert_ads_inpostads_'+location+'_styles'] = jQuery('#insert_ads_inpostads_'+location+'_styles').val();
			
			args['insert_ads_inpostads_'+location+'_notes'] = jQuery('#insert_ads_inpostads_'+location+'_notes').val();
			
			if(location == 'middle') {
				args['insert_ads_inpostads_'+location+'_minimum_character_count'] = jQuery('#insert_ads_inpostads_'+location+'_minimum_character_count').val();
				args['insert_ads_inpostads_'+location+'_paragraph_buffer_count'] = jQuery('#insert_ads_inpostads_'+location+'_paragraph_buffer_count').val();
			}
			jQuery.post(
				jQuery('#insert_ads_admin_ajax').val(), args, function(response) {
					var sucdial = jQuery('<div id="sucdial">Succesfully updated!</div>').hide().fadeIn('1000');
					if(jQuery('#sucdial').length){
											jQuery(document).find('#sucdial').fadeIn('1000').delay('2000').fadeOut('1000');
										} else {
											jQuery('body').prepend(sucdial);
											jQuery(document).find('#sucdial').delay('2000').fadeOut('1000');
										}
				}
			);
		},
		function() { }
	);
}

function insert_ads_adwidgets_click_handler(identifier, title) {
	jQuery('<div id="insert_ads_adwidgets_'+identifier+'_dialog"></div>').html('<div class="insert_ads_ajaxloader"></div>').dialog({
		'modal': true,
		'resizable': false,
		'width': jQuery("body").width() * 0.8,
		'maxWidth': jQuery("body").width() * 0.8,
		'maxHeight': jQuery("body").height() * 0.9,
		'title': 'Ad Widget : '+title,
		position: { my: 'center', at: 'center', of: window },
		open: function (event, ui) {
			jQuery('.ui-dialog').css({'z-index': 999999, 'max-width': '90%'});
			jQuery('.ui-widget-overlay').css({'z-index': 999998, 'opacity': 0.8, 'background': '#000000'});
			jQuery('.ui-dialog-buttonpane button:contains("Update")').button('disable');			
			jQuery.post(
				jQuery('#insert_ads_admin_ajax').val(), {
					'action': 'insert_ads_adwidgets_existing_form_get_content',
					'insert_ads_adwidgets_identifier': identifier,
					'insert_ads_nonce': jQuery('#insert_ads_nonce').val()
				}, function(response) {
					jQuery('.insert_ads_ajaxloader').hide();
					jQuery('.ui-dialog-content').html(response);
					jQuery('.ui-accordion .ui-accordion-content').css('max-height', (jQuery("body").height() * 0.45));
					jQuery('.ui-dialog-buttonpane button:contains("Update")').button('enable');
					jQuery('.insert_ads_adwidgets_status').parent().css({'display': 'inline-block', 'margin': '5px 0 0'}).prependTo('.ui-dialog-buttonpane');
					jQuery('.ui-dialog').css({'position': 'fixed'});
					jQuery('#insert_ads_adwidgets_'+identifier+'_dialog').delay(500).dialog({position: { my: 'center', at: 'center', of: window }});
				}			
			);
		},
		buttons: {
			'Update': function() {
				jQuery("#insert_ads_adwidgets_"+identifier).html("Ad Widget : "+jQuery("#insert_ads_adwidgets_"+identifier+"_title").val());
				jQuery("#insert_ads_adwidgets_"+identifier).attr("onClick", "insert_ads_adwidgets_click_handler(\'"+identifier+"\', \'"+jQuery("#insert_ads_adwidgets_"+identifier+"_title").val()+"\')");
				insert_ads_adwidgets_update(identifier);
				jQuery(this).dialog('close');
			},
			Cancel: function() {
				jQuery(this).dialog('close');
			}
		},
		close: function() {
			jQuery(this).dialog('destroy');
		}
	});
}

function insert_ads_adwidgets_update(identifier) {
	args = {};
	args['action'] = 'insert_ads_adwidgets_existing_form_save_action';
	args['insert_ads_nonce'] = jQuery('#insert_ads_nonce').val();
	args['insert_ads_adwidgets_identifier'] = identifier;
	args['insert_ads_adwidgets_'+identifier+'_status'] = jQuery('#insert_ads_adwidgets_'+identifier+'_status').prop('checked');
	
	args['insert_ads_adwidgets_'+identifier+'_title'] = jQuery('#insert_ads_adwidgets_'+identifier+'_title').val();
	
	args['insert_ads_adwidgets_'+identifier+'_primary_ad_code'] = jQuery('#insert_ads_adwidgets_'+identifier+'_primary_ad_code').val();
	args['insert_ads_adwidgets_'+identifier+'_secondary_ad_code'] = jQuery('#insert_ads_adwidgets_'+identifier+'_secondary_ad_code').val();
	args['insert_ads_adwidgets_'+identifier+'_tertiary_ad_code'] = jQuery('#insert_ads_adwidgets_'+identifier+'_tertiary_ad_code').val();
	
	args['insert_ads_adwidgets_'+identifier+'_rules_exclude_loggedin'] = jQuery('#insert_ads_adwidgets_'+identifier+'_rules_exclude_loggedin').prop('checked');
	args['insert_ads_adwidgets_'+identifier+'_rules_exclude_mobile_devices'] = jQuery('#insert_ads_adwidgets_'+identifier+'_rules_exclude_mobile_devices').prop('checked');
	args['insert_ads_adwidgets_'+identifier+'_rules_exclude_404'] = jQuery('#insert_ads_adwidgets_'+identifier+'_rules_exclude_404').prop('checked');
	args['insert_ads_adwidgets_'+identifier+'_rules_exclude_home'] = jQuery('#insert_ads_adwidgets_'+identifier+'_rules_exclude_home').prop('checked');
	args['insert_ads_adwidgets_'+identifier+'_rules_exclude_archives'] = jQuery('#insert_ads_adwidgets_'+identifier+'_rules_exclude_archives').prop('checked');
	args['insert_ads_adwidgets_'+identifier+'_rules_exclude_search'] = jQuery('#insert_ads_adwidgets_'+identifier+'_rules_exclude_search').prop('checked');
	args['insert_ads_adwidgets_'+identifier+'_rules_exclude_page'] = jQuery('#insert_ads_adwidgets_'+identifier+'_rules_exclude_page').prop('checked');
	args['insert_ads_adwidgets_'+identifier+'_rules_page_exceptions'] = jQuery.map(jQuery('#insert_ads_adwidgets_'+identifier+'_rules_page_exceptions :selected'), function(e) { return jQuery(e).val(); });
	args['insert_ads_adwidgets_'+identifier+'_rules_exclude_post'] = jQuery('#insert_ads_adwidgets_'+identifier+'_rules_exclude_post').prop('checked');
	args['insert_ads_adwidgets_'+identifier+'_rules_post_exceptions'] = jQuery.map(jQuery('#insert_ads_adwidgets_'+identifier+'_rules_post_exceptions :selected'), function(e) { return jQuery(e).val(); });
	args['insert_ads_adwidgets_'+identifier+'_rules_post_categories_exceptions'] = jQuery.map(jQuery('#insert_ads_adwidgets_'+identifier+'_rules_post_categories_exceptions :selected'), function(e) { return jQuery(e).val(); });
	args['insert_ads_adwidgets_'+identifier+'_rules_exclude_categories'] = jQuery('#insert_ads_adwidgets_'+identifier+'_rules_exclude_categories').prop('checked');
	args['insert_ads_adwidgets_'+identifier+'_rules_categories_exceptions'] = jQuery.map(jQuery('#insert_ads_adwidgets_'+identifier+'_rules_categories_exceptions :selected'), function(e) { return jQuery(e).val(); });
	
	args['insert_ads_adwidgets_'+identifier+'_geo_group1_countries'] = jQuery.map(jQuery('#insert_ads_adwidgets_'+identifier+'_geo_group1_countries :selected'), function(e) { return jQuery(e).val(); });
	args['insert_ads_adwidgets_'+identifier+'_geo_group1_adcode'] = jQuery('#insert_ads_adwidgets_'+identifier+'_geo_group1_adcode').val();
	args['insert_ads_adwidgets_'+identifier+'_geo_group2_countries'] = jQuery.map(jQuery('#insert_ads_adwidgets_'+identifier+'_geo_group2_countries :selected'), function(e) { return jQuery(e).val(); });
	args['insert_ads_adwidgets_'+identifier+'_geo_group2_adcode'] = jQuery('#insert_ads_adwidgets_'+identifier+'_geo_group2_adcode').val();
	
	args['insert_ads_adwidgets_'+identifier+'_styles'] = jQuery('#insert_ads_adwidgets_'+identifier+'_styles').val();
	
	args['insert_ads_adwidgets_'+identifier+'_notes'] = jQuery('#insert_ads_adwidgets_'+identifier+'_notes').val();
	
	jQuery.post(
		jQuery('#insert_ads_admin_ajax').val(), args, function(response) { }
	);
}

function insert_ads_adwidgets_remove(identifier) {
	jQuery("<p>Are you Sure you want to remove this Ad Unit?</p>").dialog({
		'modal': true,
		'resizable': false,
		'title': 'Deletion Confirmation',
		position: { my: 'center', at: 'center', of: window },
		open: function (event, ui) {
			jQuery('.ui-dialog').css({'z-index': 999999, 'max-width': '90%'});
			jQuery('.ui-widget-overlay').css({'z-index': 999998, 'opacity': 0.8, 'background': '#000000'});
		},
		buttons : {
			'Confirm': function() {
				jQuery("#insert_ads_adwidgets_"+identifier).parent().remove();
				jQuery.post(
					jQuery('#insert_ads_admin_ajax').val(), {
						'action': 'insert_ads_adwidgets_remove',
						'insert_ads_adwidgets_identifier': identifier,
						'insert_ads_nonce': jQuery('#insert_ads_nonce').val()
					}, function(response) {
					}			
				);
				jQuery(this).dialog("close");
			},
			'Cancel': function() {
				jQuery(this).dialog("close");
			}
		},
		close: function() {
			jQuery(this).dialog('destroy');
		}
	});
}

function insert_ads_inthemeads_click_handler(identifier, title) {
	jQuery('<div id="insert_ads_inthemeads_'+identifier+'_dialog"></div>').html('<div class="insert_ads_ajaxloader"></div>').dialog({
		'modal': true,
		'resizable': false,
		'width': jQuery("body").width() * 0.8,
		'maxWidth': jQuery("body").width() * 0.8,
		'maxHeight': jQuery("body").height() * 0.9,
		'title': 'In-Theme Ad : '+title,
		position: { my: 'center', at: 'center', of: window },
		open: function (event, ui) {
			jQuery('.ui-dialog').css({'z-index': 999999, 'max-width': '90%'});
			jQuery('.ui-widget-overlay').css({'z-index': 999998, 'opacity': 0.8, 'background': '#000000'});
			jQuery('.ui-dialog-buttonpane button:contains("Update")').button('disable');
			jQuery.post(
				jQuery('#insert_ads_admin_ajax').val(), {
					'action': 'insert_ads_inthemeads_existing_form_get_content',
					'insert_ads_inthemeads_identifier': identifier,
					'insert_ads_nonce': jQuery('#insert_ads_nonce').val()
				}, function(response) {
					jQuery('.insert_ads_ajaxloader').hide();
					jQuery('.ui-dialog-content').html(response);
					jQuery('.ui-accordion .ui-accordion-content').css('max-height', (jQuery("body").height() * 0.45));
					jQuery('.ui-dialog-buttonpane button:contains("Update")').button('enable');
					jQuery('.insert_ads_inthemeads_status').parent().css({'display': 'inline-block', 'margin': '5px 0 0'}).prependTo('.ui-dialog-buttonpane');
					jQuery('.ui-dialog').css({'position': 'fixed'});
					jQuery('#insert_ads_inthemeads_'+identifier+'_dialog').delay(500).dialog({position: { my: 'center', at: 'center', of: window }});
				}			
			);
		},
		buttons: {
			'Update': function() {
				jQuery("#insert_ads_inthemeads_"+identifier).html("In-Theme Ad : "+jQuery("#insert_ads_inthemeads_"+identifier+"_title").val());
				jQuery("#insert_ads_inthemeads_"+identifier).attr("onClick", "insert_ads_inthemeads_click_handler(\'"+identifier+"\', \'"+jQuery("#insert_ads_inthemeads_"+identifier+"_title").val()+"\')");
				insert_ads_inthemeads_update(identifier);
				jQuery(this).dialog('close');
			},
			Cancel: function() {
				jQuery(this).dialog('close');
			}
		},
		close: function() {
			jQuery(this).dialog('destroy');
		}
	});
}

function insert_ads_inthemeads_update(identifier) {
	args = {};
	args['action'] = 'insert_ads_inthemeads_existing_form_save_action';
	args['insert_ads_nonce'] = jQuery('#insert_ads_nonce').val();
	args['insert_ads_inthemeads_identifier'] = identifier;
	args['insert_ads_inthemeads_'+identifier+'_status'] = jQuery('#insert_ads_inthemeads_'+identifier+'_status').prop('checked');
	
	args['insert_ads_inthemeads_'+identifier+'_title'] = jQuery('#insert_ads_inthemeads_'+identifier+'_title').val();
	
	args['insert_ads_inthemeads_'+identifier+'_primary_ad_code'] = jQuery('#insert_ads_inthemeads_'+identifier+'_primary_ad_code').val();
	args['insert_ads_inthemeads_'+identifier+'_secondary_ad_code'] = jQuery('#insert_ads_inthemeads_'+identifier+'_secondary_ad_code').val();
	args['insert_ads_inthemeads_'+identifier+'_tertiary_ad_code'] = jQuery('#insert_ads_inthemeads_'+identifier+'_tertiary_ad_code').val();
	
	args['insert_ads_inthemeads_'+identifier+'_rules_exclude_loggedin'] = jQuery('#insert_ads_inthemeads_'+identifier+'_rules_exclude_loggedin').prop('checked');
	args['insert_ads_inthemeads_'+identifier+'_rules_exclude_mobile_devices'] = jQuery('#insert_ads_inthemeads_'+identifier+'_rules_exclude_mobile_devices').prop('checked');
	args['insert_ads_inthemeads_'+identifier+'_rules_exclude_404'] = jQuery('#insert_ads_inthemeads_'+identifier+'_rules_exclude_404').prop('checked');
	args['insert_ads_inthemeads_'+identifier+'_rules_exclude_home'] = jQuery('#insert_ads_inthemeads_'+identifier+'_rules_exclude_home').prop('checked');
	args['insert_ads_inthemeads_'+identifier+'_rules_exclude_archives'] = jQuery('#insert_ads_inthemeads_'+identifier+'_rules_exclude_archives').prop('checked');
	args['insert_ads_inthemeads_'+identifier+'_rules_exclude_search'] = jQuery('#insert_ads_inthemeads_'+identifier+'_rules_exclude_search').prop('checked');
	args['insert_ads_inthemeads_'+identifier+'_rules_exclude_page'] = jQuery('#insert_ads_inthemeads_'+identifier+'_rules_exclude_page').prop('checked');
	args['insert_ads_inthemeads_'+identifier+'_rules_page_exceptions'] = jQuery.map(jQuery('#insert_ads_inthemeads_'+identifier+'_rules_page_exceptions :selected'), function(e) { return jQuery(e).val(); });
	args['insert_ads_inthemeads_'+identifier+'_rules_exclude_post'] = jQuery('#insert_ads_inthemeads_'+identifier+'_rules_exclude_post').prop('checked');
	args['insert_ads_inthemeads_'+identifier+'_rules_post_exceptions'] = jQuery.map(jQuery('#insert_ads_inthemeads_'+identifier+'_rules_post_exceptions :selected'), function(e) { return jQuery(e).val(); });
	args['insert_ads_inthemeads_'+identifier+'_rules_post_categories_exceptions'] = jQuery.map(jQuery('#insert_ads_inthemeads_'+identifier+'_rules_post_categories_exceptions :selected'), function(e) { return jQuery(e).val(); });
	args['insert_ads_inthemeads_'+identifier+'_rules_exclude_categories'] = jQuery('#insert_ads_inthemeads_'+identifier+'_rules_exclude_categories').prop('checked');
	args['insert_ads_inthemeads_'+identifier+'_rules_categories_exceptions'] = jQuery.map(jQuery('#insert_ads_inthemeads_'+identifier+'_rules_categories_exceptions :selected'), function(e) { return jQuery(e).val(); });
	
	args['insert_ads_inthemeads_'+identifier+'_geo_group1_countries'] = jQuery.map(jQuery('#insert_ads_inthemeads_'+identifier+'_geo_group1_countries :selected'), function(e) { return jQuery(e).val(); });
	args['insert_ads_inthemeads_'+identifier+'_geo_group1_adcode'] = jQuery('#insert_ads_inthemeads_'+identifier+'_geo_group1_adcode').val();
	args['insert_ads_inthemeads_'+identifier+'_geo_group2_countries'] = jQuery.map(jQuery('#insert_ads_inthemeads_'+identifier+'_geo_group2_countries :selected'), function(e) { return jQuery(e).val(); });
	args['insert_ads_inthemeads_'+identifier+'_geo_group2_adcode'] = jQuery('#insert_ads_inthemeads_'+identifier+'_geo_group2_adcode').val();
	
	args['insert_ads_inthemeads_'+identifier+'_styles'] = jQuery('#insert_ads_inthemeads_'+identifier+'_styles').val();
	
	args['insert_ads_inthemeads_'+identifier+'_notes'] = jQuery('#insert_ads_inthemeads_'+identifier+'_notes').val();
	
	jQuery.post(
		jQuery('#insert_ads_admin_ajax').val(), args, function(response) { }
	);
}

function insert_ads_inthemeads_remove(identifier) {
	jQuery("<p>Are you Sure you want to remove this Ad Unit?</p>").dialog({
		'modal': true,
		'resizable': false,
		'title': 'Deletion Confirmation',
		position: { my: 'center', at: 'center', of: window },
		open: function (event, ui) {
			jQuery('.ui-dialog').css({'z-index': 999999, 'max-width': '90%'});
			jQuery('.ui-widget-overlay').css({'z-index': 999998, 'opacity': 0.8, 'background': '#000000'});
		},
		buttons : {
			'Confirm': function() {
				jQuery("#insert_ads_inthemeads_"+identifier).parent().remove();
				jQuery.post(
					jQuery('#insert_ads_admin_ajax').val(), {
						'action': 'insert_ads_inthemeads_remove',
						'insert_ads_inthemeads_identifier': identifier,
						'insert_ads_nonce': jQuery('#insert_ads_nonce').val()
					}, function(response) {
					}			
				);
				jQuery(this).dialog("close");
			},
			'Cancel': function() {
				jQuery(this).dialog("close");
			}
		},
		close: function() {
			jQuery(this).dialog('destroy');
		}
	});
}

function insert_ads_legalpages_generate_page(target, title) {
	jQuery('.ui-dialog-buttonpane button:contains("Update")').button('disable');
	jQuery('#'+target+'_generate_page').hide();
	jQuery('#'+target+'_accordion .insert_ads_ajaxloader_flat').show();
	jQuery.post(
		jQuery('#insert_ads_admin_ajax').val(), {
			'action': target+'_form_generate_page_action',
			'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
		}, function(response) {
			if(response != '0') {
				jQuery('#'+target+'_assigned_page').append(jQuery('<option>', {value: response, text: title})).val(response);
			}
			jQuery('#'+target+'_generate_page').show();
			jQuery('#'+target+'_accordion .insert_ads_ajaxloader_flat').hide();
			jQuery('.ui-dialog-buttonpane button:contains("Update")').button('enable');
		}
	);	
}
jQuery(document).on('click', '#insert_ads_vi_signup2', function(){
	//console.log(location.search);
	if(location.search == '?post_type=insertpostads&page=insert-post-ads'){
		jQuery('#insert_ads_vi_signup').trigger('click');
	} else {
		location.href = '/wp-admin/edit.php?post_type=insertpostads&page=insert-post-ads';
	}
})
function insert_ads_click_handler(target, title, width, height, openAction, UpdateAction, closeAction) {
	jQuery('#'+target).click(function() {
		//console.log(jQuery('#insert_ads_nonce').val(), jQuery('#insert_ads_admin_ajax').val());
		//console.log(target);
		jQuery('<div id="'+target+'_dialog"></div>').html('<div class="insert_ads_ajaxloader"></div>').dialog({
			'modal': true,
			'resizable': false,
			'width': width,
			'maxWidth': width,
			'maxHeight': height,
			'title': title,
			position: { my: 'center', at: 'center', of: window },
			open: function (event, ui) {
				jQuery('.ui-dialog').css({'z-index': 999999, 'max-width': '90%'});
				jQuery('.ui-widget-overlay').css({'z-index': 999998, 'opacity': 0.8, 'background': '#000000'});
				jQuery('.ui-dialog-buttonpane button:contains("Update")').button('disable');
			
				jQuery.post(
					jQuery('#insert_ads_admin_ajax').val(), {
						'action': target+'_form_get_content',
						'insert_ads_nonce': jQuery('#insert_ads_nonce').val()
					}, function(response) {
						jQuery('.insert_ads_ajaxloader').hide();
						jQuery('.ui-dialog-content').html(response);
						jQuery('.ui-accordion .ui-accordion-content').css('max-height', (jQuery("body").height() * 0.45));
						jQuery('.ui-dialog-buttonpane button:contains("Update")').button('enable');
						openAction();
						jQuery('.ui-dialog').css({'position': 'fixed'});
						jQuery('#'+target+'_dialog').delay(500).dialog({position: { my: 'center', at: 'center', of: window }});
						
					}			
				);
			},
			buttons: {
				'Update': {
					text: 'Update',
					icons: { primary: "ui-icon-gear" },
					click: function() {
						if(UpdateAction() != 'false') {
							jQuery(this).dialog('close');
						}
					}
				},
				Cancel: {
					text: 'Cancel',
					icons: { primary: "ui-icon-cancel" },
					click: function() {
						if(closeAction() != 'false') {
							jQuery(this).dialog('close');
						}
					}
				}
			},
			close: function() {
				closeAction();
				jQuery(this).dialog('destroy');
			}
		})
	});
}
function insert_ads_adstxt_add_entry() {
	var insert_ads_adstxt_new_entry_domain = jQuery("#insert_ads_adstxt_new_entry_domain").val();
	var insert_ads_adstxt_new_entry_pid = jQuery("#insert_ads_adstxt_new_entry_pid").val();
	var insert_ads_adstxt_new_entry_type = jQuery("#insert_ads_adstxt_new_entry_type").val();
	var insert_ads_adstxt_new_entry_certauthority = jQuery("#insert_ads_adstxt_new_entry_certauthority").val();
	var insert_ads_adstxt_content = jQuery("#insert_ads_adstxt_content").val();
	var defaultBorderColor = jQuery("#insert_ads_adstxt_new_entry_domain").css("border-color");
	var defaultLabelColor = jQuery("#insert_ads_adstxt_new_entry_domain").parent().find("small").css("color");
	
	var isValidated = true;
	jQuery("#insert_ads_adstxt_new_entry_domain").css({"border-color": defaultBorderColor}).parent().find("small").css({"color": defaultLabelColor});
	jQuery("#insert_ads_adstxt_new_entry_pid").css({"border-color": defaultBorderColor}).parent().find("small").css({"color": defaultLabelColor});
	
	
	if(insert_ads_adstxt_new_entry_domain == '') {
		jQuery("#insert_ads_adstxt_new_entry_domain").css({"border-color": "#B20303"}).parent().find("small").css({"color": "#B20303"});
		isValidated = false;
	}
	if(insert_ads_adstxt_new_entry_pid == '') {
		jQuery("#insert_ads_adstxt_new_entry_pid").css({"border-color": "#B20303"}).parent().find("small").css({"color": "#B20303"});
		isValidated = false;
	}
	
	if(isValidated) {
		if((insert_ads_adstxt_content != '') && (jQuery.inArray((insert_ads_adstxt_content[insert_ads_adstxt_content.length -1]), ["\r", "\n"]) == -1)) {
			insert_ads_adstxt_content += '\r\n';
		}
		insert_ads_adstxt_content += insert_ads_adstxt_new_entry_domain + ', ' + insert_ads_adstxt_new_entry_pid + ', ' + insert_ads_adstxt_new_entry_type;
		if(insert_ads_adstxt_new_entry_certauthority != '') {
			insert_ads_adstxt_content += ', ' + insert_ads_adstxt_new_entry_certauthority;
		}
		jQuery("#insert_ads_adstxt_content").val(insert_ads_adstxt_content);
		
		jQuery("#insert_ads_adstxt_new_entry_domain").val('');
		jQuery("#insert_ads_adstxt_new_entry_pid").val('');
		jQuery("#insert_ads_adstxt_new_entry_type").val('DIRECT');
		jQuery("#insert_ads_adstxt_new_entry_certauthority").val('');
		
		jQuery("#insert_ads_adstxt_accordion").accordion({active: 0});
		jQuery("#insert_ads_adstxt_content").focus();
	}
}

function insert_ads_adstxt_content_download() {
	var blob = new Blob([jQuery("#insert_ads_adstxt_content").val()], {type: 'text/csv'});
	if(window.navigator.msSaveOrOpenBlob) {
		window.navigator.msSaveBlob(blob, 'ads.txt');
	}
	else{
		var elem = window.document.createElement('a');
		elem.href = window.URL.createObjectURL(blob);
		elem.download = 'ads.txt';        
		document.body.appendChild(elem);
		elem.click();        
		document.body.removeChild(elem);
	}
}

function insert_ads_vi_signup_handler() {
	insert_ads_click_handler(
		'insert_ads_vi_signup',
		'video intelligence: Signup',
		'870',
		'554',
		function() { },
		function() { },
		function() { }
	);
}

function insert_ads_vi_login_handler() {
	insert_ads_click_handler(
		'insert_ads_vi_login',
		'video intelligence: Login',
		'540',
		'540',
		function() {
			jQuery('.ui-dialog-buttonset').find('button').first().unbind('click').click(function() {
				if((jQuery('#insert_ads_vi_login_username').val() != '') && (jQuery('#insert_ads_vi_login_password').val() != '')) {
					jQuery('.ui-dialog-buttonset').find('button').first().button('disable');
					jQuery('.ui-dialog-buttonset').find('button').last().button('disable');
					jQuery('.ui-dialog-titlebar').find('button').last().button('disable');
					var insert_ads_vi_login_username = jQuery('#insert_ads_vi_login_username').val();
					var insert_ads_vi_login_password = jQuery('#insert_ads_vi_login_password').val();
					jQuery('.ui-dialog-content').html('<div class="insert_ads_ajaxloader"></div>');
					jQuery('.insert_ads_ajaxloader').show();
					jQuery.post(
						jQuery('#insert_ads_admin_ajax').val(), {
							'action': 'insert_ads_vi_login_form_save_action',
							'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
							'insert_ads_vi_login_username': insert_ads_vi_login_username,
							'insert_ads_vi_login_password': insert_ads_vi_login_password,
						}, function(response) {
							
							if(response.indexOf('###SUCCESS###') !== -1) {

								jQuery.post(
									jQuery('#insert_ads_admin_ajax').val(), {
										'action': 'insert_ads_vi_update_adstxt',
										'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
									}, function(response) {
										if(response.indexOf('###SUCCESS###') !== -1) {
											//jQuery('.wrap #poststuff').before(response.replace('###SUCCESS###', ''));
											//console.log('succ');
											location.reload();
										} else if(response.indexOf('###FAIL###') !== -1) {
											jQuery('.wrap #poststuff').before(response.replace('###FAIL###', ''));
											//console.log('fail')
											//setTimeout(function(){location.reload()}, 2000);
										} else {
										}
										
									}
								);
								jQuery('.vi-card .plugin-card-bottom, .vi-card .plugin-card-top-content').animate({'opacity': 0}, 1000);
								jQuery('.vi-card').html(response.replace('###SUCCESS###', ''));
								insert_ads_vi_logout_handler();
								insert_ads_vi_customize_adcode();
								insert_ads_vi_chart_draw();
								jQuery(window).resize();
								jQuery('.vi-card .plugin-card-bottom, .vi-card .plugin-card-top-content').animate({'opacity': 1}, 1000);
								jQuery('.ui-dialog-titlebar').find('button').last().button('enable').click();								
							} else {
								jQuery('.ui-dialog-buttonset').find('button').first().button('enable');
								jQuery('.ui-dialog-buttonset').find('button').last().button('enable');
								jQuery('.ui-dialog-titlebar').find('button').last().button('enable');
								jQuery('.ui-dialog-content').html(response);
							}
						}
					);
				} else {
					jQuery('#insert_ads_vi_login_username').css('border-color', '#dddddd');
					jQuery('#insert_ads_vi_login_password').css('border-color', '#dddddd');
					if(jQuery('#insert_ads_vi_login_username').val() == '') {
						jQuery('#insert_ads_vi_login_username').css('border-color', '#ff0000');
					}
					if(jQuery('#insert_ads_vi_login_password').val() == '') {
						jQuery('#insert_ads_vi_login_password').css('border-color', '#ff0000');
					}
				}
			});
		},
		function() { },
		function() { }
	);
}

function insert_ads_vi_customize_adcode() {
	insert_ads_click_handler(
		'insert_ads_vi_customize_adcode',
		'video intelligence: Customize vi Code',
		jQuery("body").width() * 0.8,
		jQuery("body").height() * 0.8,
		function() {
			jQuery('#insert_ads_vi_code_settings_keywords').attr('maxlength', '200');
			jQuery('#insert_ads_vi_code_settings_optional_1').attr('maxlength', '200');
			jQuery('#insert_ads_vi_code_settings_optional_2').attr('maxlength', '200');
			jQuery('#insert_ads_vi_code_settings_optional_3').attr('maxlength', '200');
			jQuery('.ui-dialog-buttonset').find('button').first().unbind('click').click(function() {
				var keywordsRegex = /[ ,a-zA-Z0-9-’'‘\u00C6\u00D0\u018E\u018F\u0190\u0194\u0132\u014A\u0152\u1E9E\u00DE\u01F7\u021C\u00E6\u00F0\u01DD\u0259\u025B\u0263\u0133\u014B\u0153\u0138\u017F\u00DF\u00FE\u01BF\u021D\u0104\u0181\u00C7\u0110\u018A\u0118\u0126\u012E\u0198\u0141\u00D8\u01A0\u015E\u0218\u0162\u021A\u0166\u0172\u01AFY\u0328\u01B3\u0105\u0253\u00E7\u0111\u0257\u0119\u0127\u012F\u0199\u0142\u00F8\u01A1\u015F\u0219\u0163\u021B\u0167\u0173\u01B0y\u0328\u01B4\u00C1\u00C0\u00C2\u00C4\u01CD\u0102\u0100\u00C3\u00C5\u01FA\u0104\u00C6\u01FC\u01E2\u0181\u0106\u010A\u0108\u010C\u00C7\u010E\u1E0C\u0110\u018A\u00D0\u00C9\u00C8\u0116\u00CA\u00CB\u011A\u0114\u0112\u0118\u1EB8\u018E\u018F\u0190\u0120\u011C\u01E6\u011E\u0122\u0194\u00E1\u00E0\u00E2\u00E4\u01CE\u0103\u0101\u00E3\u00E5\u01FB\u0105\u00E6\u01FD\u01E3\u0253\u0107\u010B\u0109\u010D\u00E7\u010F\u1E0D\u0111\u0257\u00F0\u00E9\u00E8\u0117\u00EA\u00EB\u011B\u0115\u0113\u0119\u1EB9\u01DD\u0259\u025B\u0121\u011D\u01E7\u011F\u0123\u0263\u0124\u1E24\u0126I\u00CD\u00CC\u0130\u00CE\u00CF\u01CF\u012C\u012A\u0128\u012E\u1ECA\u0132\u0134\u0136\u0198\u0139\u013B\u0141\u013D\u013F\u02BCN\u0143N\u0308\u0147\u00D1\u0145\u014A\u00D3\u00D2\u00D4\u00D6\u01D1\u014E\u014C\u00D5\u0150\u1ECC\u00D8\u01FE\u01A0\u0152\u0125\u1E25\u0127\u0131\u00ED\u00ECi\u00EE\u00EF\u01D0\u012D\u012B\u0129\u012F\u1ECB\u0133\u0135\u0137\u0199\u0138\u013A\u013C\u0142\u013E\u0140\u0149\u0144n\u0308\u0148\u00F1\u0146\u014B\u00F3\u00F2\u00F4\u00F6\u01D2\u014F\u014D\u00F5\u0151\u1ECD\u00F8\u01FF\u01A1\u0153\u0154\u0158\u0156\u015A\u015C\u0160\u015E\u0218\u1E62\u1E9E\u0164\u0162\u1E6C\u0166\u00DE\u00DA\u00D9\u00DB\u00DC\u01D3\u016C\u016A\u0168\u0170\u016E\u0172\u1EE4\u01AF\u1E82\u1E80\u0174\u1E84\u01F7\u00DD\u1EF2\u0176\u0178\u0232\u1EF8\u01B3\u0179\u017B\u017D\u1E92\u0155\u0159\u0157\u017F\u015B\u015D\u0161\u015F\u0219\u1E63\u00DF\u0165\u0163\u1E6D\u0167\u00FE\u00FA\u00F9\u00FB\u00FC\u01D4\u016D\u016B\u0169\u0171\u016F\u0173\u1EE5\u01B0\u1E83\u1E81\u0175\u1E85\u01BF\u00FD\u1EF3\u0177\u00FF\u0233\u1EF9\u01B4\u017A\u017C\u017E\u1E93]/g;
				if(
				(jQuery('#insert_ads_vi_code_settings_ad_unit_type').val() != 'select') && 
				(jQuery('#insert_ads_vi_code_settings_iab_category_child').val() != 'select') && 
				(jQuery('#insert_ads_vi_code_settings_language').val() != 'select') && 
				((jQuery('#insert_ads_vi_code_settings_keywords').val() == '') || ((jQuery(jQuery('#insert_ads_vi_code_settings_keywords').val().match(/./g)).not(jQuery('#insert_ads_vi_code_settings_keywords').val().match(keywordsRegex)).get().length == 0) && (jQuery('#insert_ads_vi_code_settings_keywords').val().length < 200)))
				) {
					jQuery('.ui-dialog-buttonset').find('button').first().button('disable');
					jQuery('.ui-dialog-buttonset').find('button').last().button('disable');
					jQuery('.ui-dialog-titlebar').find('button').last().button('disable');
					var insert_ads_vi_code_settings_ad_unit_type = jQuery('#insert_ads_vi_code_settings_ad_unit_type').val();
					var insert_ads_vi_code_settings_keywords = jQuery('#insert_ads_vi_code_settings_keywords').val();
					var insert_ads_vi_code_settings_iab_category_parent = jQuery('#insert_ads_vi_code_settings_iab_category_parent').val();
					var insert_ads_vi_code_settings_iab_category_child = jQuery('#insert_ads_vi_code_settings_iab_category_child').val();
					var insert_ads_vi_code_settings_language = jQuery('#insert_ads_vi_code_settings_language').val();
					var insert_ads_vi_code_settings_native_bg_color = jQuery('#insert_ads_vi_code_settings_native_bg_color').val();
					var insert_ads_vi_code_settings_native_text_color = jQuery('#insert_ads_vi_code_settings_native_text_color').val();
					var insert_ads_vi_code_settings_font_family = jQuery('#insert_ads_vi_code_settings_font_family').val();
					var insert_ads_vi_code_settings_font_size = jQuery('#insert_ads_vi_code_settings_font_size').val();
					var insert_ads_vi_code_settings_optional_1 = jQuery('#insert_ads_vi_code_settings_optional_1').val();
					var insert_ads_vi_code_settings_optional_2 = jQuery('#insert_ads_vi_code_settings_optional_2').val();
					var insert_ads_vi_code_settings_optional_3 = jQuery('#insert_ads_vi_code_settings_optional_3').val();
					var insert_ads_vi_code_settings_show_gdpr_authorization = jQuery('#insert_ads_vi_code_settings_show_gdpr_authorization').prop('checked');
					jQuery('.ui-dialog-content').html('<div class="insert_ads_ajaxloader"></div>');
					jQuery('.insert_ads_ajaxloader').show();
					jQuery.post(
						jQuery('#insert_ads_admin_ajax').val(), {
							'action': 'insert_ads_vi_customize_adcode_form_save_action',
							'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
							'insert_ads_vi_code_settings_ad_unit_type': insert_ads_vi_code_settings_ad_unit_type,
							'insert_ads_vi_code_settings_keywords': insert_ads_vi_code_settings_keywords,
							'insert_ads_vi_code_settings_iab_category_parent': insert_ads_vi_code_settings_iab_category_parent,
							'insert_ads_vi_code_settings_iab_category_child': insert_ads_vi_code_settings_iab_category_child,
							'insert_ads_vi_code_settings_language': insert_ads_vi_code_settings_language,
							'insert_ads_vi_code_settings_native_bg_color': insert_ads_vi_code_settings_native_bg_color,
							'insert_ads_vi_code_settings_native_text_color': insert_ads_vi_code_settings_native_text_color,
							'insert_ads_vi_code_settings_font_family': insert_ads_vi_code_settings_font_family,
							'insert_ads_vi_code_settings_font_size': insert_ads_vi_code_settings_font_size,
							'insert_ads_vi_code_settings_optional_1': insert_ads_vi_code_settings_optional_1,
							'insert_ads_vi_code_settings_optional_2': insert_ads_vi_code_settings_optional_2,
							'insert_ads_vi_code_settings_optional_3': insert_ads_vi_code_settings_optional_3,
                            'insert_ads_vi_code_settings_show_gdpr_authorization': insert_ads_vi_code_settings_show_gdpr_authorization,
						}, function(response) {
							if(response.indexOf('###SUCCESS###') !== -1) {
								jQuery('.ui-dialog-titlebar').find('button').last().button('enable').click();
								var successText = '<div id="sucessText" class="notice notice-success is-dismissible insert-post-ads-notice-welcome"><p>Settings saved!</p></div>';
								jQuery('#poststuff').before(successText);
								jQuery(document).find('#sucessText').delay('2000').fadeOut('1000');
							} else {
								jQuery('.ui-dialog-buttonset').find('button').first().button('disable');
								jQuery('.ui-dialog-buttonset').find('button').last().button('enable');
								jQuery('.ui-dialog-titlebar').find('button').last().button('enable');
								jQuery('.ui-dialog-content').html(response.replace('###FAIL###', ''));								
							}
						}						
					);					
				} else {
					jQuery('#insert_ads_vi_customize_adcode_keywords_required_error').hide();
					jQuery('#insert_ads_vi_customize_adcode_keywords_error').hide();
					jQuery('#insert_ads_vi_customize_adcode_required_error').hide();
					jQuery('#insert_ads_vi_code_settings_ad_unit_type').css('border-color', '#dddddd');
					jQuery('#insert_ads_vi_code_settings_iab_category_parent').css('border-color', '#dddddd');
					jQuery('#insert_ads_vi_code_settings_iab_category_child').css('border-color', '#dddddd');
					jQuery('#insert_ads_vi_code_settings_language').css('border-color', '#dddddd');
					jQuery('#insert_ads_vi_code_settings_keywords').css('border-color', '#dddddd');
					var insert_ads_vi_customize_adcode_keywords_error = false;
					var insert_ads_vi_customize_adcode_required_error = false;
					if(jQuery('#insert_ads_vi_code_settings_ad_unit_type').val() == 'select') {						
						jQuery('#insert_ads_vi_code_settings_ad_unit_type').css('border-color', '#ff0000');
						insert_ads_vi_customize_adcode_required_error = true;
					}
					if(jQuery('#insert_ads_vi_code_settings_iab_category_parent').val() == 'select') {
						jQuery('#insert_ads_vi_code_settings_iab_category_parent').css('border-color', '#ff0000');
						insert_ads_vi_customize_adcode_required_error = true;
					}
					if(jQuery('#insert_ads_vi_code_settings_iab_category_child').val() == 'select') {
						jQuery('#insert_ads_vi_code_settings_iab_category_child').css('border-color', '#ff0000');
						insert_ads_vi_customize_adcode_required_error = true;
					}
					if(jQuery('#insert_ads_vi_code_settings_language').val() == 'select') {
						jQuery('#insert_ads_vi_code_settings_language').css('border-color', '#ff0000');
						insert_ads_vi_customize_adcode_required_error = true;
					}
					if(jQuery('#insert_ads_vi_code_settings_keywords').val() != '') {
						if(jQuery('#insert_ads_vi_code_settings_keywords').val().length > 200) {
							jQuery('#insert_ads_vi_code_settings_keywords').css('border-color', '#ff0000');
							insert_ads_vi_customize_adcode_keywords_error = true;
						}
						if(jQuery(jQuery('#insert_ads_vi_code_settings_keywords').val().match(/./g)).not(jQuery('#insert_ads_vi_code_settings_keywords').val().match(keywordsRegex)).get().length != 0) {
							jQuery('#insert_ads_vi_code_settings_keywords').css('border-color', '#ff0000');
							insert_ads_vi_customize_adcode_keywords_error = true;
						}
					}
					if(insert_ads_vi_customize_adcode_keywords_error && insert_ads_vi_customize_adcode_required_error) {
						jQuery('#insert_ads_vi_customize_adcode_keywords_required_error').show();
					} else if(insert_ads_vi_customize_adcode_keywords_error) {
						jQuery('#insert_ads_vi_customize_adcode_keywords_error').show();
					} else if(insert_ads_vi_customize_adcode_required_error) {
						jQuery('#insert_ads_vi_customize_adcode_required_error').show();
					} else {}
				}
			});
		},
		function() { },
		function() { }
	);
}

function insert_ads_vi_code_iab_category_parent_change() {
	jQuery('#insert_ads_vi_code_settings_iab_category_parent').change(function() {
		var insert_ads_vi_code_iab_category = jQuery(this).val();
		jQuery('#insert_ads_vi_code_settings_iab_category_child option').prop('disabled', true).hide();
		jQuery('#insert_ads_vi_code_settings_iab_category_child option').each(function() {
			if((jQuery(this).attr('data-parent') == insert_ads_vi_code_iab_category) || (jQuery(this).val() == 'select')) {
				jQuery(this).prop('disabled', false).show();
			}
		});
		if(jQuery('#insert_ads_vi_code_settings_iab_category_child option:selected').prop('disabled') != false) {
			jQuery('#insert_ads_vi_code_settings_iab_category_child').val('select');
		}
	});
	jQuery('#insert_ads_vi_code_settings_iab_category_parent').change();
}

function insert_ads_vi_logout_handler() {
	jQuery('#insert_ads_vi_logout').click(function() {
		jQuery.post(
			jQuery('#insert_ads_admin_ajax').val(), {
				'action': 'insert_ads_vi_logout_action',
				'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
			}, function(response) {
				if(response.indexOf('###SUCCESS###') !== -1) {
					location.reload();
					jQuery('.vi-card').html(response.replace('###SUCCESS###', ''));
					insert_ads_vi_signup_handler();	
					insert_ads_vi_login_handler();
					jQuery(window).resize();
				}
				jQuery('.vi-card .plugin-card-bottom, .vi-card .plugin-card-top-content').animate({'opacity': 1}, 1000);
			}
		);
		jQuery('.vi-card .plugin-card-bottom, .vi-card .plugin-card-top-content').animate({'opacity': 0}, 1000);
		
	});
}

function insert_ads_vi_chart_draw() {
	if(jQuery('#insert_ads_vi_earnings_wrapper').length) {
		jQuery.post(
			jQuery('#insert_ads_admin_ajax').val(), {
				'action': 'insert_ads_vi_get_chart',
				'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
			}, function(response) {
				if(response.indexOf('###SUCCESS###') !== -1) {
					jQuery('#insert_ads_vi_earnings_wrapper').html(response.replace('###SUCCESS###', ''));
					if(jQuery('#insert_ads_vi_chart_data').length) {
						var ctx = document.getElementById("myChart");
						var insert_ads_vi_chart = new Chart(jQuery('#insert_ads_vi_chart'), {
							type: 'line',
							data: {
								datasets: [{
									data: JSON.parse(jQuery('#insert_ads_vi_chart_data').val()),
									backgroundColor: '#EDF5FB',
									borderColor: '#186EAE',/*E8EBEF*/
									borderWidth: 1
								}]
							},
							options: {
								title: {
									display: false,
									backgroundColor: '#EDF5FB'
								},
								legend: {
									display: false,
								},
								scales: {
									xAxes: [{
										type: "time",
										display: true,
										scaleLabel: {
											display: false
										},
										gridLines: {
											display: false,
											drawTicks: false
										},
										ticks: {
											display: false
										}
									}],
									yAxes: [{
										display: true,
										scaleLabel: {
											display: false
										},
										gridLines: {
											display: true,
											drawTicks: false
										},
										ticks: {
											display: false
										}
									}]
								},
								tooltips: {
									displayColors: false,
									callbacks: {
										label: function(tooltipItem, data) {
											return '$ '+parseFloat(tooltipItem.yLabel).toFixed(2);
										},
										title: function(tooltipItem, data) {
											var monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
											var dateParts = tooltipItem[0].xLabel.split('/');
											var date = new Date(dateParts[2], dateParts[0]-1, dateParts[1]);
											return monthNames[date.getMonth()]+' '+date.getDate();
										}
									}
								}
							}
						});
					}
				} else {
					jQuery('#insert_ads_vi_earnings_wrapper').parent().html(response);
				}
				jQuery(window).resize();
			}
		);
	}
}
jQuery(document).ready(function() {
	jQuery('.post-new-php.post-type-insertpostads select[name="ad_position"] option[value="top"]').attr('selected', true);
	jQuery('.post-new-php.post-type-insertpostads select[name="ad_position"] option[value=""]').attr('selected', false);
	jQuery('.post-new-php.post-type-insertpostads #paragraph_number').hide();
	
	jQuery('.post-type-insertpostads #search-submit').val('Search').addClass('button-primary');
	jQuery('.post-type-insertpostads #post-search-input').attr('placeholder', 'Enter advert title');
	
	jQuery('.post-type-insertpostads #bulk-action-selector-top').before('<p class="bulk">Bulk actions:</p>');
	jQuery('.post-type-insertpostads #bulk-action-selector-top option[value="-1"]').text('Select action');
	
	var getTitle = jQuery('.post-new-php.post-type-insertpostads #title').remove();
	jQuery('#ipa_meta .inside').prepend('<p id="place-title"><label>Advert Title:</label></p>');
	jQuery(getTitle).appendTo('#place-title');
	
    var publishBtn = jQuery('body.post-new-php.post-type-insertpostads #publish').remove();
    jQuery('#ipa_meta').after(publishBtn);
	
	var viBlock = jQuery('.postbox.vi-choose').remove();
	jQuery('.insert_ads_vi_popup_left_column p:last').prev().before(viBlock).before('<div style="clear:both"></div>');
	
	var btnMore = jQuery('<a href="#" id="field-more"><i class="fa fa-plus-circle" aria-hidden="true"></i><text>Add field</text></a>');
	jQuery('textarea[id*=insert_ads_vi_code_settings_optional]:not(#insert_ads_vi_code_settings_optional_1)').parent().hide();
	jQuery('#insert_ads_vi_code_settings_optional_1').parent().append(btnMore);
	jQuery(document).on('click', '#field-more', function(e){
		if(jQuery('#insert_ads_vi_code_settings_optional_2').parent().is(':hidden')){
			e.preventDefault();
			jQuery(this).parent().next().show();
			jQuery(this).parent().next().append(btnMore);
			//jQuery(this).detach();	
		} else if (jQuery('#insert_ads_vi_code_settings_optional_2').is(':visible')) {
			jQuery(this).parent().next().show();
			jQuery(this).detach();
			return false
		} else {
			jQuery(this).detach();
			return false
		}
	}) 
	
	/*function showInp(){
		var loadSel = jQuery('input[name="cur-pos-rad"]:checked').val();
		if(loadSel == 'above'){
			jQuery('.vi-choose .insert_ads_popup_content_wrapper:first').show();
			jQuery('.vi-choose .insert_ads_popup_content_wrapper:last').hide();
		} else if(loadSel == 'middle') {
			jQuery('.vi-choose .insert_ads_popup_content_wrapper:first').hide();
			jQuery('.vi-choose .insert_ads_popup_content_wrapper:last').show();
		} else {
			jQuery('.vi-choose .insert_ads_popup_content_wrapper').hide();
		}
	}*/
	function notifyInp(){
		jQuery('.funcnot').each(function(){
			var getCheck = jQuery(this).parent('div').find('input:checkbox').is(':checked'),
				thisPos = jQuery(this).attr('data-pos');
			if(getCheck === true){
				jQuery(this).text('Press to switch off vi stories on ' + thisPos + ' of post content.');
			} else {
				jQuery(this).text('Press to activate vi stories on ' + thisPos + ' of post content.');
			}
		})
	}
	notifyInp();
	//showInp();
	jQuery('#cur-pos-sel input').on('click', function(){
		//showInp();
		//console.log(jQuery('input[name="cur-pos-rad"]:checked').val());
	})
	jQuery('.postbox.vi-choose .ipcheckbox').on('click', function(){
		notifyInp();
	})
	/*jQuery('#insert_ads_vi_code_settings_status + div').click(function(){
		if(jQuery(this).prev().val() == 'true'){
			jQuery(this).prev().val('false')
		} else {
			jQuery(this).prev().val('true')
		}
		console.log(jQuery(this).prev().val());
	})*/
	jQuery('.insert_ads_notice').on('click', '.notice-dismiss', function() {		
		jQuery.post(
			jQuery('#insert_ads_admin_notice_ajax').val(), {
				'action': 'insert_ads_admin_notice_dismiss',
				'insert_ads_admin_notice_nonce': jQuery('#insert_ads_admin_notice_nonce').val(),
			}, function(response) { }
		);
	});
	
	jQuery('#insert_ads_inpostads_above_status + div').click(function(){
		if(jQuery('#insert_ads_inpostads_above_status').is(':checked')){
			//console.log('true');
		} else {
			//console.log('false')
		}
	})
	jQuery('body').on('click', '#set-update', function(){
									jQuery.post(
									jQuery('#insert_ads_admin_ajax').val(), {
										'action':'insert_ads_inpostads_above_form_save_action',
										'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
										'insert_ads_inpostads_above_status':'false',
										'insert_ads_inpostads_above_primary_ad_code_type':'vicode'
									})
									jQuery.post(
									jQuery('#insert_ads_admin_ajax').val(), {
										'action':'insert_ads_inpostads_middle_form_save_action',
										'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
										'insert_ads_inpostads_middle_status':'false',
										'insert_ads_inpostads_middle_primary_ad_code_type':'vicode'
									})
		//console.log('fdsfs');
		var insert_ads_vi_code_settings_ad_unit_type = jQuery('#insert_ads_vi_code_settings_ad_unit_type').val();
					var insert_ads_vi_code_settings_keywords = jQuery('#insert_ads_vi_code_settings_keywords').val();
					var insert_ads_vi_code_settings_iab_category_parent = jQuery('#insert_ads_vi_code_settings_iab_category_parent').val();
					var insert_ads_vi_code_settings_iab_category_child = jQuery('#insert_ads_vi_code_settings_iab_category_child').val();
					var insert_ads_vi_code_settings_language = jQuery('#insert_ads_vi_code_settings_language').val();
					var insert_ads_vi_code_settings_native_bg_color = jQuery('#insert_ads_vi_code_settings_native_bg_color').val();
					var insert_ads_vi_code_settings_native_text_color = jQuery('#insert_ads_vi_code_settings_native_text_color').val();
					var insert_ads_vi_code_settings_font_family = jQuery('#insert_ads_vi_code_settings_font_family').val();
					var insert_ads_vi_code_settings_font_size = jQuery('#insert_ads_vi_code_settings_font_size').val();
					var insert_ads_vi_code_settings_optional_1 = jQuery('#insert_ads_vi_code_settings_optional_1').val();
					var insert_ads_vi_code_settings_optional_2 = jQuery('#insert_ads_vi_code_settings_optional_2').val();
					var insert_ads_vi_code_settings_optional_3 = jQuery('#insert_ads_vi_code_settings_optional_3').val();
        var insert_ads_vi_code_settings_show_gdpr_authorization = jQuery('#insert_ads_vi_code_settings_show_gdpr_authorization').prop('checked');
					var curLoc = jQuery('input[name="cur-pos-rad"]:checked').val();
					var disLoc = jQuery('input[name="cur-pos-rad"]').not(':checked').val();
					var curStat = jQuery('#insert_ads_inpostads_above_status').is(':checked') ? true : false;
					jQuery('.ui-dialog-content').html('<div class="insert_ads_ajaxloader"></div>');
					jQuery('.insert_ads_ajaxloader').show();
					jQuery.post(
						jQuery('#insert_ads_admin_ajax').val(), {
							'action': 'insert_ads_vi_customize_adcode_form_save_action',
							'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
							'insert_ads_vi_code_settings_ad_unit_type': insert_ads_vi_code_settings_ad_unit_type,
							'insert_ads_vi_code_settings_keywords': insert_ads_vi_code_settings_keywords,
							'insert_ads_vi_code_settings_iab_category_parent': insert_ads_vi_code_settings_iab_category_parent,
							'insert_ads_vi_code_settings_iab_category_child': insert_ads_vi_code_settings_iab_category_child,
							'insert_ads_vi_code_settings_language': insert_ads_vi_code_settings_language,
							'insert_ads_vi_code_settings_native_bg_color': insert_ads_vi_code_settings_native_bg_color,
							'insert_ads_vi_code_settings_native_text_color': insert_ads_vi_code_settings_native_text_color,
							'insert_ads_vi_code_settings_font_family': insert_ads_vi_code_settings_font_family,
							'insert_ads_vi_code_settings_font_size': insert_ads_vi_code_settings_font_size,
							'insert_ads_vi_code_settings_optional_1': insert_ads_vi_code_settings_optional_1,
							'insert_ads_vi_code_settings_optional_2': insert_ads_vi_code_settings_optional_2,
							'insert_ads_vi_code_settings_optional_3': insert_ads_vi_code_settings_optional_3,
                            'insert_ads_vi_code_settings_show_gdpr_authorization': insert_ads_vi_code_settings_show_gdpr_authorization,
						}, function(response) {
							if(/*response.indexOf('###SUCCESS###') !== -1*/true) {
								jQuery('.ui-dialog-titlebar').find('button').last().button('enable').click();
								var successText = '<div id="sucessText" class="notice notice-success is-dismissible insert-post-ads-notice-welcome"><p>Settings saved!</p></div>';
								jQuery('#poststuff').before(successText);
								jQuery(document).find('#sucessText').delay('2000').fadeOut('1000');
								//console.log(curLoc, disLoc);
								var ajData= {},
									ajAction = 'action',
									ajActionVal = 'insert_ads_inpostads_'+curLoc+'_form_save_action',
									ajNonce = 'insert_ads_nonce',
									ajNonceVal = jQuery('#insert_ads_nonce').val(),
									ajStatus = 'insert_ads_inpostads_'+curLoc+'_status',
									ajStatusVal = curStat,
									ajType = 'insert_ads_inpostads_'+curLoc+'_primary_ad_code_type',
									ajTypeVal = 'vicode';
								ajData[ajAction] = ajActionVal;
								ajData[ajNonce] = ajNonceVal;
								ajData[ajStatus] = ajStatusVal;
								ajData[ajType] = ajTypeVal;
								jQuery.post(
									jQuery('#insert_ads_admin_ajax').val(), ajData, function(response){
										//console.log('saved');
										var sucdial = jQuery('<div id="sucdial">Succesfully updated!</div>').hide().fadeIn('1000');
										if(jQuery('#sucdial').length){
											jQuery(document).find('#sucdial').fadeIn('1000').delay('2000').fadeOut('1000');
										} else {
											jQuery('body').prepend(sucdial);
											jQuery(document).find('#sucdial').delay('2000').fadeOut('1000');
										}
										
										
										jQuery.post(
										jQuery('#insert_ads_admin_ajax').val(), {
										'action': 'single_post',
										'insert_ads_nonce': jQuery('#insert_ads_nonce').val(),
										'title' : jQuery('#insert_ads_vi_code_settings_keywords').val(),
										'post_type' : 'insertpostads'
									})
									})
									
							} else {
								jQuery('.ui-dialog-buttonset').find('button').first().button('disable');
								jQuery('.ui-dialog-buttonset').find('button').last().button('enable');
								jQuery('.ui-dialog-titlebar').find('button').last().button('enable');
								jQuery('.ui-dialog-content').html(response.replace('###FAIL###', ''));								
							}
						}						
					);
	})
	
	jQuery('#set-submit').click(function(e){
		e.preventDefault();
		var dataForm = jQuery(this).parents('form').serializeArray();
		dataForm.push({name: 'submit', value: 'Save Settings'});
		//console.log(dataForm);
		jQuery.ajax({
			url : 'edit.php?post_type=insertpostads&page=insert-post-ads',
			method : 'POST',
			data : dataForm,
			success : function(){
				var sucdial = jQuery('<div id="sucdial">Succesfully updated!</div>').hide().fadeIn('1000');
										if(jQuery('#sucdial').length){
											jQuery(document).find('#sucdial').fadeIn('1000').delay('2000').fadeOut('1000');
										} else {
											jQuery('body').prepend(sucdial);
											jQuery(document).find('#sucdial').delay('2000').fadeOut('1000');
										}
			}
		})
	})
	
	var statAbove = jQuery('#insert_ads_inpostads_above_status').attr('checked');
	var statMiddle = jQuery('#insert_ads_inpostads_middle_status').attr('checked');
	console.log(statAbove, statMiddle);
	
	if(statMiddle === 'checked'){
		jQuery('#insert_ads_inpostads_above_status').attr('checked', true);
		jQuery('#insert_ads_inpostads_above_status + div').addClass('checked');
		jQuery('#cur-pos-above').attr('checked', false);
		jQuery('#cur-pos-middle').attr('checked', true);
	}
});
