jQuery(document).ready(function ($) {
	'use strict';

	$(document).on('click','#calculation_fee_setting_enable',function(){		
		if(jQuery('input[id="calculation_fee_setting_enable"]').is(':checked') ) {
			jQuery(".calculation-fee-display-section").show();
		} else {
			jQuery(".calculation-fee-display-section").hide();
		}
	});

	$(document).on('click','#enable_repayment_chart',function(){	
		if(jQuery('input[id="enable_repayment_chart"]').is(':checked') ) {
			jQuery(".repayment_chart_heading_lbl").show();
		} else {
			jQuery(".repayment_chart_heading_lbl").hide();
		}
	});
	$(document).on('click','#enable_video_tab',function(){	
		if(jQuery('input[id="enable_video_tab"]').is(':checked') ) {
			jQuery(".video_heading_lbl").show();
		} else {
			jQuery(".video_heading_lbl").hide();
		}
	});
	$(document).on('click','#enable_loan_mortisation_tab',function(){	
		if(jQuery('input[id="enable_loan_mortisation_tab"]').is(':checked') ) {
			jQuery(".loan_table_heading_lbl").show();
		} else {
			jQuery(".loan_table_heading_lbl").hide();
		}
	});
	$(document).on('click','#print_option_enable',function(){
		if(jQuery('input[id="print_option_enable"]').is(':checked') ) {
			jQuery(".print-option-heading").show();
		} else {
			jQuery(".print-option-heading").hide();
		}
	});
	$(document).on('click','#delete_setting',function(){
		if(jQuery('input[id="delete_setting"]').is(':checked') ) {
			if(!confirm("Are you sure you want to delete data on plugin uninstall/deactivae?")){
				return false;
			}
		}
	});
	$(document).on('click','.contact-type-btn',function(){
		if($(this).val() == "popup"){
			jQuery("#contact-popup-section").show();
			jQuery("#contact-url-section").hide();
		}else{
			jQuery("#contact-popup-section").hide();
			jQuery("#contact-url-section").show();
		}

	});
});