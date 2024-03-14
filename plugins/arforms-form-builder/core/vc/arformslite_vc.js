"use strict";
if ( typeof window.arf_vc_clicked == 'undefined' ) {
	window.arf_vc_clicked = false;
}
function arflitehasClass(el, className) {
	if (el == null) {
		return false;
	}
	if (el.classList) {
		return el.classList.contains( className )
	} else {
		return ! ! el.className.match( new RegExp( '(\\s|^)' + className + '(\\s|$)' ) )
	}
}

function arflitegetCookie(cname) {
	var name          = cname + "=";
	var decodedCookie = decodeURIComponent( document.cookie );
	var ca            = decodedCookie.split( ';' );
	for (var i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt( 0 ) == ' ') {
			c = c.substring( 1 );
		}
		if (c.indexOf( name ) == 0) {
			return c.substring( name.length, c.length );
		}
	}
	return "";
}

jQuery( '.ARFormslite_Shortode_arfield' ).each(
	function () {
		var fild_value = jQuery( this ).val();
		var fild_name  = jQuery( this ).attr( 'id' );
		if (fild_name == 'id') {
			var form_name = jQuery( '#arfaddformid_vc_popup' ).next( 'dl' ).children( 'dd' ).children( 'ul' ).children( 'li[data-value="' + fild_value + '"]' ).data( 'label' );
			jQuery( '#arfaddformid_vc_popup' ).next( 'dl' ).children( 'dt' ).children( 'span' ).html( form_name );
			jQuery( 'input#Arf_param_id' ).val( fild_value );
		}
	}
);

jQuery( '#arfaddformid_vc_popup' ).change(
	function () {
		var arformid = jQuery( this ).val();

		if (arformid) {
			jQuery( "#Arf_param_id" ).val( arformid );
		}
	}
);

jQuery( '#arfaddformid_vc' ).change(
	function () {
		var arformid = jQuery( this ).val();
		if (arformid) {
			jQuery( ".wpb_vc_param_value" ).val( arformid );
		}
	}
);

function showarfpopupfieldlist(){
	var fild_value = jQuery( 'input[name="shortcode_type"]:checked' ).val();
	var fild_name  = 'shortcode_type';

	if (fild_name == 'id') {
		jQuery( '#arfaddformid_vc_popup option[value="' + fild_value + '"]' ).prop( 'selected', true );
		jQuery( 'input#Arf_param_id' ).val( fild_value );
	}
}
