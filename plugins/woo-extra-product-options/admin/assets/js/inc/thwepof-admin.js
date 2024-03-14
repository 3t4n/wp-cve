var thwepof_settings = (function($, window, document) {
	'use strict';

	$(function() {
		var settings_form = $('#thwepof_product_fields_form');

		thwepof_base.setup_sortable_table(settings_form, '#thwepof_product_fields', '0');
		thwepof_base.setup_tiptip_tooltips();
		thwepof_base.setup_form_wizard();
	});
   
	function select_all_fields(elm){
		var checkAll = $(elm).prop('checked');
		$('#thwepof_product_fields tbody input:checkbox[name=select_field]').prop('checked', checkAll);
	}
   	
	function remove_selected_fields(){
		$('#thwepof_product_fields tbody tr').removeClass('strikeout');
		$('#thwepof_product_fields tbody input:checkbox[name=select_field]:checked').each(function () {
			var row = $(this).closest('tr');
			if(!row.hasClass("strikeout")){
				row.addClass("strikeout");
			}
			row.find(".f_deleted").val(1);
			row.find(".f_edit_btn").prop('disabled', true);
	  	});	
	}

	function enable_disable_selected_fields(enabled){
		$('#thwepof_product_fields tbody input:checkbox[name=select_field]:checked').each(function(){
			var row = $(this).closest('tr');

			if(enabled == 0){
				if(!row.hasClass("thwepof-disabled")){
					row.addClass("thwepof-disabled");
				}
			}else{
				row.removeClass("thwepof-disabled");				
			}
			
			row.find(".f_edit_btn").prop('disabled', enabled == 1 ? false : true);
			row.find(".td_enabled").html(enabled == 1 ? '<span class="dashicons dashicons-yes tips" data-tip="Yes"></span>' : '-');
			row.find(".f_enabled").val(enabled);
	  	});
	}

	function widgetPopUp() {
		var x = document.getElementById("myDIV");
    	var y = document.getElementById("myWidget");
    	var th_animation=document.getElementById("th_quick_border_animation")
    	var th_arrow = document.getElementById("th_arrow_head");

    	if (x.style.display === "none" || !x.style.display) {
        	x.style.display = "block";
//         	y.style.background = "#D34156";
        	th_arrow.style="transform:rotate(-12.5deg);";
        	th_animation.style="box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);";
        	th_animation.style.animation='none';
    	} else {
        	x.style.display = "none";
//         	y.style.background = "#000000";
        	th_arrow.style="transform:rotate(45deg);"
        	th_animation.style.animation='pulse 1.5s infinite';
    	}
	}
	function widgetClose() {
    	var z = document.getElementById("myDIV");
	    var za = document.getElementById("myWidget");
		var th_animation=document.getElementById("th_quick_border_animation")
	    var th_arrow = document.getElementById("th_arrow_head");
	    z.style.display = "none";
		th_arrow.style="transform:rotate(45deg);"
	    th_animation.style.animation='pulse 1.5s infinite';
	//     za.style.background = "black";
	}

	$(document).ready(function(){
	   setTimeout(function(){
	      $("#thwepof_review_request_notice").fadeIn(500);
	   }, 2000);
	});
	   				
	return {
		thwepofwidgetPopUp : widgetPopUp,
		thwepofwidgetClose : widgetClose,
		select_all_fields : select_all_fields,
		remove_selected_fields : remove_selected_fields,
		enable_disable_selected_fields : enable_disable_selected_fields,
   	};
}(window.jQuery, window, document));	

function thwepofSelectAllProductFields(elm){
	thwepof_settings.select_all_fields(elm);
}

function thwepofRemoveSelectedFields(){
	thwepof_settings.remove_selected_fields();
}

function thwepofEnableSelectedFields(){
	thwepof_settings.enable_disable_selected_fields(1);
}

function thwepofDisableSelectedFields(){
	thwepof_settings.enable_disable_selected_fields(0);
}

function thwepofwidgetPopUp(){
	thwepof_settings.thwepofwidgetPopUp();
}

function thwepofwidgetClose() {
	thwepof_settings.thwepofwidgetClose();
}