function ShowTab(TabName) {
	jQuery(".OptionTab").each(function() {
			jQuery(this).addClass("HiddenTab");
			jQuery(this).removeClass("ActiveTab");
	});
	jQuery("#"+TabName).removeClass("HiddenTab");
	jQuery("#"+TabName).addClass("ActiveTab");
	
	jQuery(".nav-tab").each(function() {
			jQuery(this).removeClass("nav-tab-active");
	});
	jQuery("#"+TabName+"_Menu").addClass("nav-tab-active");
}

function ShowOptionTab(TabName) {
	jQuery(".uwcf-option-set").each(function() {
		jQuery(this).addClass("uwcf-hidden");
	});
	jQuery("#"+TabName).removeClass("uwcf-hidden");
	
	// var activeContentHeight = jQuery("#"+TabName).innerHeight();
	// jQuery(".uwcf-options-page-tabbed-content").animate({
	// 	'height':activeContentHeight
	// 	}, 500);
	// jQuery(".uwcf-options-page-tabbed-content").height(activeContentHeight);

	jQuery(".options-subnav-tab").each(function() {
		jQuery(this).removeClass("options-subnav-tab-active");
	});
	jQuery("#"+TabName+"_Menu").addClass("options-subnav-tab-active");
	jQuery('input[name="Display_Tab"]').val(TabName);
}

jQuery(document).ready(function($) {
	jQuery('.ewd-uwcf-toggle').on('change', function() {
		if ((jQuery(this).val() == "Yes" && jQuery(this).is(':checked')) || (jQuery(this).val() == "No" && !jQuery(this).is(':checked'))) {
			var Type = jQuery(this).data('filtertype');
			jQuery('tr[data-filtertype="' + Type + '"]').removeClass('ewd-uwcf-hidden');
			jQuery('table[data-filtertype="' + Type + '"]').removeClass('no-min');

			if (Type == 'Table_Format') {
				jQuery('.ewd-uwcf-non-table-format').addClass('ewd-uwcf-hidden');
			}
		}
		else {
			var Type = jQuery(this).data('filtertype');
			jQuery('tr[data-filtertype="' + Type + '"]').addClass('ewd-uwcf-hidden');
			jQuery('table[data-filtertype="' + Type + '"]').addClass('no-min');

			if (Type == 'Table_Format') {
				jQuery('.ewd-uwcf-non-table-format').removeClass('ewd-uwcf-hidden');
			}
		}
	});

	jQuery('.ewd-uwcf-wc-format-field-displayed').on('change', function() {
		if ((jQuery(this).val() == "No" && jQuery(this).is(':checked')) || (jQuery(this).val() == "Yes" && !jQuery(this).is(':checked'))) { 
			var Input_Name = jQuery(this).prop('name');
			var Slug = Input_Name.substring(0, Input_Name.length - 10); 

			jQuery('input.ewd-uwcf-admin-option-toggle[data-inputname="' + Slug + '_enable_filtering"]').prop('checked', false).trigger('change');
		}
	});

	jQuery('.ewd-uwcf-wc-format-filter-type').on('change', function() {
		var Input_Name = jQuery(this).prop('name');
		var Slug = Input_Name.substring(0, Input_Name.length - 12);
		jQuery('input[name="' + Slug + '_display"][value="' + jQuery(this).val() + '"]').prop('checked', true);
	});
});

// Add the colors beside the color names on the WC product edit screen
jQuery( document ).ready(function( $ ) {
	
	jQuery( ewd_uwcf_color_data ).each( function(index, element) {
		jQuery('#product_color-' + element.term_id + ', #popular-product_color-' + element.term_id).prepend('<div style="width:20px;height:15px;background:' + element.color + ';float: left;margin: 5px 5px 0 0;"></div>');
	});

	var Color_Picker_HTML = '<div class="cf-color-filters ewd-uwcf-pp-color-select-div"><div id="ewd-uwcf-pp-color-picker"><div id="normal_fill_color_picker" class="colorSelector small-text"><div></div></div><input class="cf-color small-text" name="normal_fill" id="normal_fill_color" type="text" size="40" /></div></div>';
	jQuery('#newproduct_color').after(Color_Picker_HTML);
});

jQuery( document ).ready(function( $ ) {
	if ( jQuery().ColorPicker ) {
		jQuery( '.cf-color-filters' ).each( function () {
			var option_id = jQuery( this ).find( '.cf-color' ).attr( 'id' );
			var color = jQuery( this ).find( '.cf-color' ).val();
			var picker_id = option_id += '_picker';

			jQuery( '#' + picker_id ).children( 'div' ).css( 'backgroundColor', color );
			jQuery( '#' + picker_id ).ColorPicker({
				color: color,
				onShow: function ( colpkr ) {
					jQuery( colpkr ).fadeIn( 200 );
					return false;
				},
				onHide: function ( colpkr ) {
					jQuery( colpkr ).fadeOut( 200 );
					return false;
				},
				onChange: function ( hsb, hex, rgb ) {
					jQuery( '#' + picker_id ).children( 'div' ).css( 'backgroundColor', '#' + hex );
					jQuery( '#' + picker_id ).next( 'input' ).attr( 'value', '#' + hex );
				
				}
			});
		});
	}
});

jQuery(function() {
    jQuery(".wp-list-table.colors-list tbody").sortable({
    	items: '.parent-color',
    	stop: function( event, ui ) {
    		jQuery('.color-list-item').each(function(index, el) {
    			if (jQuery(this).data('parent') != 0) {jQuery(this).insertAfter('#color-item-' + jQuery(this).data('parent'));}
    		});
    		saveColorOrderClick();
    	}
    }).disableSelection();
});

jQuery(function() {
    jQuery(".ewd-uwcf-wc-table-format.premium tbody").sortable({
    	items: 'tr',
    	stop: function( event, ui ) {
    		var fields = [];

    		jQuery(".ewd-uwcf-wc-table-format tbody tr").each(function() {
    			fields.push(jQuery(this).data('field'));
    		});

    		jQuery('input[name="fields_array"]').val(fields.join());
    	}
    }).disableSelection();
});

function saveColorOrderClick() {
    // ----- Retrieve the li items inside our sortable list
    var items = jQuery(".wp-list-table.colors-list tbody tr");

    var linkIDs = [items.size()];
    var index = 0;

    // ----- Iterate through each li, extracting the ID embedded as an attribute
    items.each(
        function(intIndex) {
            linkIDs[intIndex] = jQuery(this).attr("id").substring(11);
            jQuery(this).find('.menu_order').html(intIndex);
            index++;
        });

    var params = {
        ids: JSON.stringify( linkIDs ),
        nonce: ewd_uwcf_admin_php_data.nonce,
        action: 'ewd_uwcf_update_color_order'
    };

    var data = jQuery.param( params );

    jQuery.post(ajaxurl, data, function(response) {});

    //$get("<%=txtExampleItemsOrder.ClientID %>").value = linkIDs.join(",");
}

jQuery(function() {
    jQuery(".wp-list-table.sizes-list tbody").sortable({
    	items: '.parent-size',
    	stop: function( event, ui ) {
    		jQuery('.size-list-item').each(function(index, el) {
    			if (jQuery(this).data('parent') != 0) {jQuery(this).insertAfter('#size-item-' + jQuery(this).data('parent'));}
    		});
    		saveSizeOrderClick();
    	}
    }).disableSelection();
});

function saveSizeOrderClick() {
    // ----- Retrieve the li items inside our sortable list
    var items = jQuery(".wp-list-table.sizes-list tbody tr");

    var linkIDs = [items.size()];
    var index = 0;

    // ----- Iterate through each li, extracting the ID embedded as an attribute
    items.each(
        function(intIndex) {
            linkIDs[intIndex] = jQuery(this).attr("id").substring(10);
            jQuery(this).find('.menu_order').html(intIndex);
            index++;
        });

    var params = {
        ids: JSON.stringify( linkIDs ),
        nonce: ewd_uwcf_admin_php_data.nonce,
        action: 'ewd_uwcf_update_size_order'
    };

    var data = jQuery.param( params );

    jQuery.post(ajaxurl, data, function(response) {});

    //$get("<%=txtExampleItemsOrder.ClientID %>").value = linkIDs.join(",");
}

jQuery(document).ready(function($) {
	jQuery('a[href="edit-tags.php?taxonomy=pa_ewd_uwcf_colors&post_type=product"], a[href="edit-tags.php?taxonomy=pa_ewd_uwcf_sizes&post_type=product"]').parent().parent().css('display', 'none');
	jQuery('.woocommerce_attribute[data-taxonomy="pa_ewd_uwcf_colors"], .woocommerce_attribute[data-taxonomy="pa_ewd_uwcf_sizes"]').css('display', 'none');
	jQuery('select.attribute_taxonomy option[value="pa_ewd_uwcf_colors"], select.attribute_taxonomy option[value="pa_ewd_uwcf_sizes"]').css('display', 'none');
});

jQuery(document).ready(function($){
 
    var custom_uploader;
 
    jQuery( '#color_image_button' ).click(function(e) {
 
        e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            jQuery('input[name="color_image"]').val(attachment.url);
        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });
});

//NEW DASHBOARD MOBILE MENU AND WIDGET TOGGLING
jQuery(document).ready(function($){
	$('#ewd-uwcf-dash-mobile-menu-open').click(function(){
		$('.EWD_UWCF_Menu .nav-tab:nth-of-type(1n+2)').toggle();
		$('#ewd-uwcf-dash-mobile-menu-up-caret').toggle();
		$('#ewd-uwcf-dash-mobile-menu-down-caret').toggle();
		return false;
	});
	$(function(){
		$(window).resize(function(){
			if($(window).width() > 785){
				$('.EWD_UWCF_Menu .nav-tab:nth-of-type(1n+2)').show();
			}
			else{
				$('.EWD_UWCF_Menu .nav-tab:nth-of-type(1n+2)').hide();
				$('#ewd-uwcf-dash-mobile-menu-up-caret').hide();
				$('#ewd-uwcf-dash-mobile-menu-down-caret').show();
			}
		}).resize();
	});	
	$('#ewd-uwcf-dashboard-support-widget-box .ewd-uwcf-dashboard-new-widget-box-top').click(function(){
		$('#ewd-uwcf-dashboard-support-widget-box .ewd-uwcf-dashboard-new-widget-box-bottom').toggle();
		$('#ewd-uwcf-dash-mobile-support-up-caret').toggle();
		$('#ewd-uwcf-dash-mobile-support-down-caret').toggle();
	});
	$('#ewd-uwcf-dashboard-optional-table .ewd-uwcf-dashboard-new-widget-box-top').click(function(){
		$('#ewd-uwcf-dashboard-optional-table .ewd-uwcf-dashboard-new-widget-box-bottom').toggle();
		$('#ewd-uwcf-dash-optional-table-up-caret').toggle();
		$('#ewd-uwcf-dash-optional-table-down-caret').toggle();
	});
});



//OPTIONS HELP/DESCRIPTION TEXT
jQuery(document).ready(function($) {
	$('.uwcf-option-set .form-table tr').each(function(){
		var thisOptionClick = $(this);
		thisOptionClick.find('th').click(function(){
			thisOptionClick.find('td p').toggle();
		});
	});
	$('.ewdOptionHasInfo').each(function(){
		var thisNonTableOptionClick = $(this);
		thisNonTableOptionClick.find('.ewd-uwcf-admin-styling-subsection-label').click(function(){
			thisNonTableOptionClick.find('fieldset p').toggle();
		});
	});
	$(function(){
		$(window).resize(function(){
			$('.uwcf-option-set .form-table tr').each(function(){
				var thisOption = $(this);
				if( $(window).width() < 783 ){
					if( thisOption.find('.ewd-uwcf-admin-hide-radios').length > 0 ) {
						thisOption.find('td p').show();			
						thisOption.find('th').css('background-image', 'none');			
						thisOption.find('th').css('cursor', 'default');			
					}
					else{
						thisOption.find('td p').hide();
						thisOption.find('th').css('background-image', 'url(../wp-content/plugins/color-filters/images/options-asset-info.png)');			
						thisOption.find('th').css('background-position', '95% 20px');			
						thisOption.find('th').css('background-size', '18px 18px');			
						thisOption.find('th').css('background-repeat', 'no-repeat');			
						thisOption.find('th').css('cursor', 'pointer');								
					}		
				}
				else{
					thisOption.find('td p').hide();
					thisOption.find('th').css('background-image', 'url(../wp-content/plugins/color-filters/images/options-asset-info.png)');			
					thisOption.find('th').css('background-position', 'calc(100% - 20px) 15px');			
					thisOption.find('th').css('background-size', '18px 18px');			
					thisOption.find('th').css('background-repeat', 'no-repeat');			
					thisOption.find('th').css('cursor', 'pointer');			
				}
			});
			$('.ewdOptionHasInfo').each(function(){
				var thisNonTableOption = $(this);
				if( $(window).width() < 783 ){
					if( thisNonTableOption.find('.ewd-uwcf-admin-hide-radios').length > 0 ) {
						thisNonTableOption.find('fieldset p').show();			
						thisNonTableOption.find('ewd-uwcf-admin-styling-subsection-label').css('background-image', 'none');			
						thisNonTableOption.find('ewd-uwcf-admin-styling-subsection-label').css('cursor', 'default');			
					}
					else{
						thisNonTableOption.find('fieldset p').hide();
						thisNonTableOption.find('ewd-uwcf-admin-styling-subsection-label').css('background-image', 'url(../wp-content/plugins/color-filters/images/options-asset-info.png)');			
						thisNonTableOption.find('ewd-uwcf-admin-styling-subsection-label').css('background-position', 'calc(100% - 30px) 15px');			
						thisNonTableOption.find('ewd-uwcf-admin-styling-subsection-label').css('background-size', '18px 18px');			
						thisNonTableOption.find('ewd-uwcf-admin-styling-subsection-label').css('background-repeat', 'no-repeat');			
						thisNonTableOption.find('ewd-uwcf-admin-styling-subsection-label').css('cursor', 'pointer');								
					}		
				}
				else{
					thisNonTableOption.find('fieldset p').hide();
					thisNonTableOption.find('ewd-uwcf-admin-styling-subsection-label').css('background-image', 'url(../wp-content/plugins/color-filters/images/options-asset-info.png)');			
					thisNonTableOption.find('ewd-uwcf-admin-styling-subsection-label').css('background-position', 'calc(100% - 30px) 15px');			
					thisNonTableOption.find('ewd-uwcf-admin-styling-subsection-label').css('background-size', '18px 18px');			
					thisNonTableOption.find('ewd-uwcf-admin-styling-subsection-label').css('background-repeat', 'no-repeat');			
					thisNonTableOption.find('ewd-uwcf-admin-styling-subsection-label').css('cursor', 'pointer');			
				}
			});
		}).resize();
	});	
});


//OPTIONS PAGE YES/NO TOGGLE SWITCHES
jQuery(document).ready(function($) {
	jQuery('.ewd-uwcf-admin-option-toggle').on('change', function() {
		var Input_Name = jQuery(this).data('inputname');
		if (jQuery(this).is(':checked')) {
			jQuery('input[name="' + Input_Name + '"][value="Yes"]').prop('checked', true).trigger('change');
			jQuery('input[name="' + Input_Name + '"][value="No"]').prop('checked', false);
		}
		else {
			jQuery('input[name="' + Input_Name + '"][value="Yes"]').prop('checked', false).trigger('change');
			jQuery('input[name="' + Input_Name + '"][value="No"]').prop('checked', true);
		}
	});
	$(function(){
		$(window).resize(function(){
			$('.uwcf-option-set .form-table tr').each(function(){
				var thisOptionTr = $(this);
				if( $(window).width() < 783 ){
					if( thisOptionTr.find('.ewd-uwcf-admin-switch').length > 0 ) {
						thisOptionTr.find('th').css('width', 'calc(90% - 50px');			
						thisOptionTr.find('th').css('padding-right', 'calc(5% + 50px');			
					}
					else{
						thisOptionTr.find('th').css('width', '90%');			
						thisOptionTr.find('th').css('padding-right', '5%');			
					}		
				}
				else{
					thisOptionTr.find('th').css('width', '200px');			
					thisOptionTr.find('th').css('padding-right', '46px');			
				}
			});
		}).resize();
	});	
});


//SPECTRUM

/* jQuery(document).ready(function() {
	jQuery('.ewd-uwcf-spectrum').spectrum({
		showInput: true,
		showInitial: true,
		preferredFormat: "hex",
		allowEmpty: true
	});

	jQuery('.ewd-uwcf-spectrum').css('display', 'inline');

	jQuery('.ewd-uwcf-spectrum').on('change', function() {
		if (jQuery(this).val() != "") {
			jQuery(this).css('background', jQuery(this).val());
			var rgb = EWD_UWCF_hexToRgb(jQuery(this).val());
			var Brightness = (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
			if (Brightness < 100) {jQuery(this).css('color', '#ffffff');}
			else {jQuery(this).css('color', '#000000');}
		}
		else {
			jQuery(this).css('background', 'none');
		}
	});

	jQuery('.ewd-uwcf-spectrum').each(function() {
		if (jQuery(this).val() != "") {
			jQuery(this).css('background', jQuery(this).val());
			var rgb = EWD_UWCF_hexToRgb(jQuery(this).val());
			var Brightness = (rgb.r * 299 + rgb.g * 587 + rgb.b * 114) / 1000;
			if (Brightness < 100) {jQuery(this).css('color', '#ffffff');}
			else {jQuery(this).css('color', '#000000');}
		}
	});
}); */

function EWD_UWCF_hexToRgb(hex) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return result ? {
        r: parseInt(result[1], 16),
        g: parseInt(result[2], 16),
        b: parseInt(result[3], 16)
    } : null;
}

/***********************************
TABLE FORMAT PAGE TOGGLE SWITCHES
***********************************/
jQuery(document).ready(function($){
	$('.wc-filters_page_ewd-uwcf-table-mode .sap-admin-option-toggle').on('change', function() {
		var Input_Name = $(this).data('inputname'); console.log(Input_Name+'HI');
		if ($(this).is(':checked')) {
			$('input[name="' + Input_Name + '"][value="1"]').prop('checked', true).trigger('change');
			$('input[name="' + Input_Name + '"][value="0"]').prop('checked', false);
		}
		else {
			$('input[name="' + Input_Name + '"][value="1"]').prop('checked', false).trigger('change');
			$('input[name="' + Input_Name + '"][value="0"]').prop('checked', true);
		}
	});
});


/***********************************
FILTERING PREMIUM SECTIONS
***********************************/
jQuery(document).ready(function(){

	var premium_heading = jQuery( 'h2:contains("Premium")' );
	premium_heading.css( 'display', 'none' );
	var prev_table = premium_heading.prev( '.form-table' );
	prev_table.css( 'margin-bottom', '0px' );
	jQuery( '<div class="ewd-uwcf-premium-table-buffer"></div>' ).insertAfter( prev_table );
});

/***********************************
FILTERING CONDITIONAL SECTIONS
***********************************/
jQuery(document).ready(function(){

	jQuery( 'h2:contains("Sub Options")' ).each( function(){
		
		var sub_options_heading = jQuery(this);
		sub_options_heading.css( 'display', 'none' );
		var prev_table_sub = sub_options_heading.prev( '.form-table' );
		prev_table_sub.css( 'margin-bottom', '0px' );
		jQuery( '<div class="ewd-uwcf-premium-table-buffer"></div>' ).insertAfter( prev_table_sub );

		var prev_toggle = prev_table_sub.find( '.sap-admin-option-toggle' );
		var sub_next_tables = sub_options_heading.nextAll( '.form-table:lt(2)' );
		var next_premium_overlay = sub_options_heading.nextAll( '.sap-premium-options-table-overlay:lt(1)' )

		if ( prev_toggle.is( ':checked' ) ) {
			jQuery( sub_next_tables ).show();
			jQuery( next_premium_overlay ).show();
			prev_table_sub.css( 'margin-bottom', '0px' );
		}
		else {
			jQuery( sub_next_tables ).hide();
			jQuery( next_premium_overlay ).hide();
			prev_table_sub.css( 'margin-bottom', '16px' );
		}

		prev_toggle.on( 'change', function() {
			if ( jQuery(this).is( ':checked' ) ) {
				jQuery( sub_next_tables ).show();
				jQuery( next_premium_overlay ).show();
				prev_table_sub.css( 'margin-bottom', '0px' );
			}
			else {
				jQuery( sub_next_tables ).hide();
				jQuery( next_premium_overlay ).hide();
				prev_table_sub.css( 'margin-bottom', '16px' );
			}
		});
	});
});

// About Us Page
jQuery( document ).ready( function( $ ) {

	jQuery( '.ewd-uwcf-about-us-tab-menu-item' ).on( 'click', function() {

		jQuery( '.ewd-uwcf-about-us-tab-menu-item' ).removeClass( 'ewd-uwcf-tab-selected' );
		jQuery( '.ewd-uwcf-about-us-tab' ).addClass( 'ewd-uwcf-hidden' );

		var tab = jQuery( this ).data( 'tab' );

		jQuery( this ).addClass( 'ewd-uwcf-tab-selected' );
		jQuery( '.ewd-uwcf-about-us-tab[data-tab="' + tab + '"]' ).removeClass( 'ewd-uwcf-hidden' );
	} );

	jQuery( '.ewd-uwcf-about-us-send-feature-suggestion' ).on( 'click', function() {

		var feature_suggestion = jQuery( '.ewd-uwcf-about-us-feature-suggestion textarea' ).val();
		var email_address = jQuery( '.ewd-uwcf-about-us-feature-suggestion input[name="feature_suggestion_email_address"]' ).val();
    
    	var params = {};

    	params.nonce  				= ewd_uwcf_admin_php_data.nonce;
    	params.action 				= 'ewd_uwcf_send_feature_suggestion';
    	params.feature_suggestion   = feature_suggestion;
    	params.email_address 		= email_address;

    	var data = jQuery.param( params );
    	jQuery.post( ajaxurl, data, function() {} );

    	jQuery( '.ewd-uwcf-about-us-feature-suggestion' ).prepend( '<p>Thank you, your feature suggestion has been submitted.' );
	} );
} );


//SETTINGS PREVIEW SCREENS

jQuery( document ).ready( function() {

	jQuery( '.ewd-uwcf-settings-preview' ).prevAll( 'h2' ).hide();
	jQuery( '.ewd-uwcf-settings-preview' ).prevAll( '.sap-tutorial-toggle' ).hide();
	jQuery( '.ewd-uwcf-settings-preview .sap-tutorial-toggle' ).hide();
});
