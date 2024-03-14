/* Contact Form X */

jQuery(document).ready(function($) {
	
	$('#sortable').sortable();
	
	$('.cfx-reset-widget').on('click', function(e) {
		e.preventDefault();
		$('.cfx-modal-dialog').dialog('destroy');
		var link = this;
		var button_names = {}
		button_names[alert_delete_data_true]  = function() { window.location = link.href; }
		button_names[alert_delete_data_false] = function() { $(this).dialog('close'); }
		$('<div class="cfx-modal-dialog">'+ alert_delete_data_message +'</div>').dialog({
			title: alert_delete_data_title,
			buttons: button_names,
			modal: true,
			width: 350,
			closeText: ''
		});
	});
	
	$('.cfx-reset-options').on('click', function(e) {
		e.preventDefault();
		$('.cfx-modal-dialog').dialog('destroy');
		var link = this;
		var button_names = {}
		button_names[alert_reset_options_true]  = function() { window.location = link.href; }
		button_names[alert_reset_options_false] = function() { $(this).dialog('close'); }
		$('<div class="cfx-modal-dialog">'+ alert_reset_options_message +'</div>').dialog({
			title: alert_reset_options_title,
			buttons: button_names,
			modal: true,
			width: 350,
			closeText: ''
		});
	});
	
	$('.cfx-recipient-delete-link').on('click', function(e) {
		e.preventDefault();
		$('.cfx-modal-dialog').dialog('destroy');
		var link = this;
		var button_names = {}
		button_names[alert_delete_recip_true]  = function() { window.location = link.href; }
		button_names[alert_delete_recip_false] = function() { $(this).dialog('close'); }
		$('<div class="cfx-modal-dialog">'+ alert_delete_recip_message +'</div>').dialog({
			title: alert_delete_recip_title,
			buttons: button_names,
			modal: true,
			width: 350,
			closeText: ''
		});
	});
	
});