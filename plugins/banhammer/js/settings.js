/* Banhammer Settings */

jQuery(document).ready(function($){
	
	$('.banhammer-reset-options').on('click', function(e){
		e.preventDefault();
		$('.banhammer-dialog').dialog('destroy');
		var link = this;
		var button_names = {};
		button_names[banhammer_alert_options_true]  = function() { window.location = link.href; }
		button_names[banhammer_alert_options_false] = function() { $(this).dialog('close'); }
		$('<div class="banhammer-dialog">'+ banhammer_alert_options_message +'</div>').dialog({
			title: banhammer_alert_options_title,
			buttons: button_names,
			modal: true,
			width: 350,
			closeText: ''
		});
	});
	
});
