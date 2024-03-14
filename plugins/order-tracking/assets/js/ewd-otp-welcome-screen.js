jQuery(document).ready(function() {
	jQuery('.ewd-otp-welcome-screen-box h2').on('click', function() {
		var page = jQuery(this).parent().data('screen');
		EWD_OTP_Toggle_Welcome_Page(page);
	});

	jQuery('.ewd-otp-welcome-screen-next-button').on('click', function() {
		var page = jQuery(this).data('nextaction');
		EWD_OTP_Toggle_Welcome_Page(page);
	});

	jQuery('.ewd-otp-welcome-screen-previous-button').on('click', function() {
		var page = jQuery(this).data('previousaction');
		EWD_OTP_Toggle_Welcome_Page(page);
	});

	jQuery('.ewd-otp-welcome-screen-add-status-button').on('click', function() {
		var status_name = jQuery('.ewd-otp-welcome-screen-add-status-name input').val();
		var status_percentage = jQuery('.ewd-otp-welcome-screen-add-status-percentage input').val();

		var Status_HTML = '<tr class="list-item edit-status-item">';
		Status_HTML += "<td class='status'><input type='text' class='ewd-otp-welcome-edit-status-input' name='status[]' value='" + status_name + "' disabled /></td>";
		Status_HTML += "<td class='status-completed'><input type='text' class='ewd-otp-welcome-edit-status-input ewd-otp-edit-status-percentage-input' name='status_percentages[]' value='" + status_percentage + "' disabled /></td>";
		Status_HTML += "</tr>";

		jQuery('.ewd-otp-welcome-screen-statuses-table table tbody').append(Status_HTML);

		jQuery( '.ewd-otp-welcome-screen-add-order-status select' ).append( '<option value="' + status_name + '">' + status_name + '</option>' );

		var params = {
			status_name: status_name,
			status_percentage: status_percentage,
			nonce: ewd_otp_getting_started.nonce,
			action: 'ewd_otp_welcome_add_status'
		};

		var data = jQuery.param( params );

		jQuery.post(ajaxurl, data, function(response) {});
	});

	jQuery('.ewd-otp-welcome-screen-add-tracking-page-button').on('click', function() {
		var tracking_page_title = jQuery('.ewd-otp-welcome-screen-add-tracking-page-name input').val();

		EWD_OTP_Toggle_Welcome_Page('options');

		var params = {
			tracking_page_title: tracking_page_title,
			nonce: ewd_otp_getting_started.nonce,
			action: 'ewd_otp_welcome_add_tracking_page'
		};

		var data = jQuery.param( params );

		jQuery.post(ajaxurl, data, function(response) {});
	});

	jQuery('.ewd-otp-welcome-screen-save-options-button').on('click', function() {

		var order_information = [];

		jQuery('input[name="order_information[]"]:checked').each(function() {order_information.push(jQuery(this).val());});

		var email_frequency = jQuery('input[name="email_frequency"]:checked').val(); 
		var form_instructions = jQuery('textarea[name="form_instructions"]').val(); 
		var hide_blank_fields = jQuery('input[name="hide_blank_fields"]:checked').val();

		var params = {
			order_information: JSON.stringify(order_information),
			email_frequency: email_frequency,
			form_instructions: form_instructions,
			hide_blank_fields: hide_blank_fields,
			nonce: ewd_otp_getting_started.nonce,
			action: 'ewd_otp_welcome_set_options'
		};

		var data = jQuery.param( params );

		jQuery.post(ajaxurl, data, function(response) {

			jQuery('.ewd-otp-welcome-screen-save-options-button').after('<div class="ewd-otp-save-message"><div class="ewd-otp-save-message-inside">Options have been saved.</div></div>');
			jQuery('.ewd-otp-save-message').delay(2000).fadeOut(400, function() {jQuery('.ewd-otp-save-message').remove();});
		});
	});

	jQuery('.ewd-otp-welcome-screen-add-order-button').on('click', function() {

		jQuery('.ewd-otp-welcome-screen-show-created-orders').show();

		var order_name = jQuery('.ewd-otp-welcome-screen-add-order-name input').val();
		var order_number = jQuery('.ewd-otp-welcome-screen-add-order-number input').val();
		var order_email = jQuery('.ewd-otp-welcome-screen-add-order-email input').val();
		var order_status = jQuery('.ewd-otp-welcome-screen-add-order-status select').val();

		jQuery('.ewd-otp-welcome-screen-add-order-name input').val('');
		jQuery('.ewd-otp-welcome-screen-add-order-number input').val('');
		jQuery('.ewd-otp-welcome-screen-add-order-email input').val('');

		var params = {
			order_name: order_name,
			order_number: order_number,
			order_email: order_email,
			order_status: order_status,
			nonce: ewd_otp_getting_started.nonce,
			action: 'ewd_otp_welcome_add_order'
		};

		var data = jQuery.param( params );
		
		jQuery.post(ajaxurl, data, function(response) {

			var HTML = '<tr class="ewd-otp-welcome-screen-order">';
			HTML += '<td class="ewd-otp-welcome-screen-order-name">' + order_name + '</td>';
			HTML += '<td class="ewd-otp-welcome-screen-order-number">' + order_number + '</td>';
			HTML += '<td class="ewd-otp-welcome-screen-order-status">' + order_status + '</td>';
			HTML += '</tr>';

			jQuery('.ewd-otp-welcome-screen-show-created-orders').append(HTML);
		});
	});
});

function EWD_OTP_Toggle_Welcome_Page(page) {
	jQuery('.ewd-otp-welcome-screen-box').removeClass('ewd-otp-welcome-screen-open');
	jQuery('.ewd-otp-welcome-screen-' + page).addClass('ewd-otp-welcome-screen-open');
}