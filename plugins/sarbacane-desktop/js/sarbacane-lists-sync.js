function sarbacaneNewsListChangePopup() {
	if ( !jQuery( "#sarbacane_news_list" ).is( ":checked" ) ) {
		jQuery( ".sarbacane_desktop_popup" ).show();
	}
}

function sarbacaneNewsListChangePopupOk() {
	jQuery( ".sarbacane_desktop_popup" ).hide();
}

function sarbacaneNewsListChangePopupNo() {
	jQuery( "#sarbacane_news_list" ).attr( "checked", "checked" );
	jQuery( ".sarbacane_desktop_popup" ).hide();
}
