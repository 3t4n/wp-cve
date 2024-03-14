/**
 * Admin Enqueue Script
 *
 * @package wpsyncsheets-elementor
 */

(function($) {
	"use strict";
	$( document ).ready(
		function(){
			$( "#authlink" ).on(
				'click',
				function(e){
					$( '#authbtn' ).hide();
					document.getElementById( "authtext" ).style.display = "inline-block";
				}
			);
			$( "#revoke" ).on(
				'click',
				function(e){
					document.getElementById( "authtext" ).style.display     = "none";
					document.getElementById( "client_token" ).style.display = "none";
				}
			);
			$( "#reset_settings" ).on(
				"click",
				function(e){
					e.preventDefault();

					jQuery.ajax(
						{
							url : admin_ajax_object.ajaxurl,
							type : 'post',
							data :"action=wpssle_reset_settings",
							beforeSend:function(){
								if (confirm( "Are you sure you want to reset settings?" )) {

								} else {
									return false;
								}
							},
							success : function( response ) {
								if (String( response ) === 'successful') {
									location.reload();
								} else {
									alert( response );
								}
							},
							error: function (s) {
								alert( 'Error' );
							}
						}
					);
				}
			);
			$( '.wpssle-support' ).parent().attr( 'target','_blank' );
		}
	);
	$( document ).ready(
		function(){
			var activetab = getParameterByName( 'tab' );
			if ( activetab != null) {
				wpssletab( event, activetab );
				var classnm = "button." + activetab;
				$( classnm ).addClass( 'active' );
			} else {
				var classnm = "button.googleapi-settings";
				$( classnm ).addClass( 'active' );
			}
		}
	);
})( jQuery );
function wpssletab(evt, tabName) {
	var i, tabcontent, tablinks;
	tabcontent           = document.getElementsByClassName( "tabcontent" );
	var tabcontentlength = tabcontent.length;
	for (i = 0; i < tabcontentlength; i++) {
		tabcontent[i].style.display = "none";
	}
	tablinks           = document.getElementsByClassName( "tablinks" );
	var tablinkslength = tablinks.length;
	for (i = 0; i < tablinkslength; i++) {
		tablinks[i].className = tablinks[i].className.replace( " active", "" );
	}
	document.getElementById( tabName ).style.display = "block";
	evt.currentTarget.className                     += " active";
}

function getParameterByName(name, url) {
	if ( ! url) {
		url = window.location.href;
	}
	name        = name.replace( /[\[\]]/g, '\\$&' );
	var regex   = new RegExp( '[?&]' + name + '(=([^&#]*)|&|#|$)' ),
		results = regex.exec( url );
	if ( ! results) {
		return null;
	}
	if ( ! results[2]) {
		return '';
	}
	return decodeURIComponent( results[2].replace( /\+/g, ' ' ) );
}
function wpssle_copy( id, targetid ) {
	var copyText   = document.getElementById( id );
	var textArea   = document.createElement( "textarea" );
	textArea.value = copyText.textContent;
	document.body.appendChild( textArea );
	textArea.select();
	document.execCommand( "Copy" );
	textArea.remove();
}
