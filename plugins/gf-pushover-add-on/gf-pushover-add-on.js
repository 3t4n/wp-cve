jQuery(document).ready(function ($) {
	if ( $("#tab_notification").length > 0 && $("input[name=_gform_setting_service]").length > 0 ) {
		if ( $("input[name=_gform_setting_service][value=pushover]").is(":checked") ) {
			$( "#gform_setting_toType" ).hide();
			$( "#toEmail" ).val('{admin_email}');
			$( "#gform_setting_toEmail" ).hide();
			$( "#gform_setting_gform_pushover_user_token" ).insertBefore( $( "#gform_setting_toEmail" ) );
			$( "#gform_setting_fromName" ).hide();
			$( "#gform_setting_from" ).hide();
			$( "#gform_setting_replyTo" ).hide();
			$( "#gform_setting_bcc" ).hide();
		} else {
			$( "#gform_setting_gform_pushover_user_token" ).hide();
		}
	}
});