jQuery(document).ready(function($) {
	
	// Dialog
	
	$('.simple-login-notification-reset-options').on('click', function(e) {
		e.preventDefault();
		$('.simple-login-notification-modal-dialog').dialog('destroy');
		var link = this;
		var button_names = {}
		button_names[simple_login_notification_reset_true]  = function() { window.location = link.href; }
		button_names[simple_login_notification_reset_false] = function() { $(this).dialog('close'); }
		$('<div class="simple-login-notification-modal-dialog">'+ simple_login_notification_reset_message +'</div>').dialog({
			title: simple_login_notification_reset_title,
			buttons: button_names,
			modal: true,
			width: 350,
			closeText: ''
		});
	});
	
});