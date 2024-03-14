/* ajax_loader jquery */
(function( $ ){
	'use strict';
	$.fn.ajax_loader = function( class_id ) {
		jQuery( class_id ).block({
			message: null,
			overlayCSS: {
				background: "#fff",
				opacity: 0.6
			}	
		});
		return this;
	}; 
})( jQuery );

jQuery(document).ready(function() {
	'use strict';
	var url;
	var $table = jQuery("#trackship_notifications_logs").DataTable({
		dom: "i<'table_scroll't><'datatable_footer'ilp>",
		searching: false,
		"ordering": false,
		"processing": true,
		"serverSide": true,
		"sPaginationType": "input",
		"order": [[ 5, "desc" ]],
		"ajax": {
			'type': 'POST',
			'url': ajaxurl+'?action=get_trackship_logs',
			'data': function ( d ) {
				d.ajax_nonce = jQuery("#nonce_trackship_logs").val();
				d.search_bar = jQuery("#search_bar").val();
				d.shipment_status = jQuery("#log_shipment_status").val();
				d.log_type = jQuery("#log_type").val();
			},
		},
		
		"lengthMenu": [[25, 50, 100, 200], [25, 50, 100, 200]],
		"pageLength":25,
		"drawCallback": function(settings) {
			jQuery(window).resize();
			jQuery("#trackship_notifications_logs").unblock();
		},		
		oLanguage: {
			sProcessing: '<div id=loader><div class="fa-3x"><i class="fas fa-sync fa-spin"></i></div>',
			"sEmptyTable": "No data is available for this status",
		},
		
		"columns":[
			{
				"width": "100px",
				'orderable': false,		
				'data': 'order_id',
			},			
			{
				"width": "160px",
				'orderable': false,	
				'data': 'shipment_status',									
			},	
			{
				"width": "185px",
				'orderable': false,	
				'data': 'date',				
			},	
			{
				"width": "185px",
				'orderable': false,		
				'data': 'to',
			},	
			{
				"width": "100px",
				'orderable': false,		
				'data': 'type',				
			},
			{
				"width": "100px",
				'orderable': false,
				'data': 'status',
			},
			{
				"width": "70px",
				'orderable': false,
				'data': 'action_button',
			},
		],
	});

	jQuery(document).on("click", ".serch_button", function(){
		jQuery(document).ajax_loader("#trackship_notifications_logs");
		$table.ajax.reload();		
	});
	jQuery(document).on("change", "#log_shipment_status", function(){
		jQuery(document).ajax_loader("#trackship_notifications_logs");
		$table.ajax.reload();		
	});
	jQuery(document).on("change", "#log_type", function(){
		jQuery(document).ajax_loader("#trackship_notifications_logs");
		$table.ajax.reload();		
	});
	jQuery(document).on("change", "#tab_trackship_logs", function(){
		jQuery(document).ajax_loader("#trackship_notifications_logs");
		$table.ajax.reload();		
	});
	jQuery("#search_bar").keyup(function(event) {
		if ( jQuery(this).val() ) {
			jQuery('.log_search_bar span').show();
		} else {
			jQuery('.log_search_bar span').hide();
		}
		if (event.keyCode === 13) {
			jQuery(".serch_button").click();
		}
	});
});

jQuery(document).on("click", ".trackship_logs .get_log_detail", function(){
	var order_id = jQuery(this).data('orderid');
	var rowid = jQuery(this).data('rowid');
	
	var ajax_data = {
		action: 'log_details_popup',
		order_id: order_id,
		rowid : rowid,
		security: jQuery("#nonce_trackship_logs").val(),
	};
	jQuery.ajax({
		url: ajaxurl,		
		data: ajax_data,		
		type: 'POST',
		success: function(response) {
			jQuery('.trackship_logs_details .order_id span').html(response.order_number);
			jQuery('.trackship_logs_details .shipment_status span').html(response.shipment_status);
			jQuery('.trackship_logs_details .tracking_number span').html(response.tracking_number);
			jQuery('.trackship_logs_details .time span').html(response.date);
			jQuery('.trackship_logs_details .to span').html(response.to);
			jQuery('.trackship_logs_details .type span').html(response.type);
			jQuery('.trackship_logs_details .status span').html(response.status_msg);
			jQuery('.trackship_logs_details').show();
		},
	});
});

jQuery(document).on("click", ".log_search_bar span", function(){
	jQuery(this).prev().val('').focus();
	jQuery(this).hide();
	jQuery(".serch_button").click();
});
