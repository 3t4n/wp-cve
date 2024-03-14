(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(function() {

	});

})( jQuery );


function resetSingleUser(e){

	e.preventDefault();	
	let id = e.target.id;
	let btn = e.target;
	let msg = document.querySelector('.'+id+'__msg');
	let userId = btn.dataset.userId;
	console.log("userdata being sent");
	console.log(userId);

	
	jQuery.ajax({
		url: tpulApiSettings.root + 'terms-popup-on-user-login/v1/action/reset-single-user',
		type: 'POST',
		contentType: 'application/json',
		beforeSend: function (xhr) {
			xhr.setRequestHeader('X-WP-Nonce', tpulApiSettings.tpul_nonce);
			btn.disabled = true;
			msg.innerHTML = msg.dataset.waitMsg;
		},
		data: JSON.stringify( {
			userId: userId,
		})
		,
		success: function (response){
		}

	}).done(function (results) {

		console.log( results );
		
		btn.disabled = false;
		msg.innerHTML = msg.dataset.successMsg;

	}).fail(function (jqXHR, textStatus, errorThrown) {
		
		console.log(jqXHR);
		console.log(textStatus);
		console.log(errorThrown);

		btn.disabled = false;
		msg.innerHTML = 'Script Failed. Error: ' + errorThrown + " " + jqXHR.responseJSON.message;

	});
}