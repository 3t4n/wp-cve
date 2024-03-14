jQuery(document).ready(function($) {
	if($("#frm_is_email_hook:checked").length == 0 || $("[type='checkbox'][id^='frm_']:checked").length > 1)
	{
		if($("#frm_is_email_hook:checked").length == 0)
			$('#frm_is_email_hook').attr('disabled', true);

		$("#frm_is_email_hook").attr('checked', true);
		$('#error_message').removeAttr("disabled");
		$('#error_msg_help_text').text('Set the custom error message for emails you want to reject.');
	}	
	else
	{
		$('#frm_is_email_hook').attr('disabled', false);
		$('#error_message').attr("disabled", "disabled");
		$('#error_msg_help_text').text('This plugin is not supporting custom error message with is_email() hook.');
	}

	$("form :checkbox").click(function () {
		if($("#frm_is_email_hook:checked").length == 0 || $("[type='checkbox'][id^='frm_']:checked").length > 1)
		{
			if($("[type='checkbox'][id^='frm_']:checked").length >= 1)
				$('#frm_is_email_hook').attr('disabled', true);

			if($("[type='checkbox'][id^='frm_']:checked").length == 0)
			{
				$("#frm_is_email_hook").attr('checked', true);
				$('#frm_is_email_hook').attr('disabled', false);
			}

			$('#error_message').removeAttr("disabled");
			$('#error_msg_help_text').text('Set the custom error message for emails you want to reject.');
		}	
		else
		{
			$('#error_message').attr("disabled", "disabled");
			$('#frm_is_email_hook').attr('disabled', false);
			$('#error_msg_help_text').text('This plugin is not supporting custom error message with is_email() hook.');
		}
	});
});