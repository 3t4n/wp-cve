(function ($) {
	"use strict";

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

	$(function () {});
})(jQuery);

function ajaxButtonExample(e) {
	e.preventDefault();
	let id = e.target.id;
	let btn = e.target;
	let msg = document.querySelector("." + id + "__msg");

	jQuery
		.ajax({
			url: bbrcApiSettings.root + "bb-report-content/report/comment",
			type: "POST",
			contentType: "application/json",
			beforeSend: function (xhr) {
				xhr.setRequestHeader("X-WP-Nonce", bbrcApiSettings.nonce);
				btn.disabled = true;
				msg.innerHTML = msg.dataset.waitMsg;
			},
			data: JSON.stringify({
				comment_id: "123",
			}),
			success: function (response) {},
		})
		.done(function (results) {
			btn.disabled = false;
			msg.innerHTML = msg.dataset.successMsg;
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			let message = "no response";
			if (jqXHR.responseJSON) {
				message = jqXHR.responseJSON.message;
			}
			btn.disabled = false;
			msg.innerHTML =
				"Script Failed. Error: " + errorThrown + " Message: " + message;
		});
}

function bbrc_report_click(e) {
	e.preventDefault();

	// Get the current page URL
	const currentPageURL = window.location.href;
	// Remove hash values from the URL
	const urlWithoutHash = currentPageURL.split("#")[0];

	let id = e.target.id;
	let btn = e.target;
	let dataset = e.target.dataset.vals;
	let msg = document.querySelector("." + id + "__msg");

	jQuery
		.ajax({
			url: bbrcApiSettings.root + "bb-report-content/report/comment",
			type: "POST",
			contentType: "application/json",
			beforeSend: function (xhr) {
				xhr.setRequestHeader("X-WP-Nonce", bbrcApiSettings.nonce);
				btn.disabled = true;
				// btn.classList.add("hidden");

				msg.innerHTML = msg.dataset.waitMsg;
				msg.classList.remove("hidden");
				msg.classList.remove("msg-success");
				msg.classList.remove("msg-error");
				msg.classList.add("msg-waiting");
			},
			data: JSON.stringify({
				data: dataset,
				pageUrl: urlWithoutHash,
			}),
			success: function (response) {
				msg.classList.add("msg-success");
				msg.innerHTML = msg.dataset.successMsg;
			},
		})
		.done(function (results) {
			// btn.disabled = false;
			msg.classList.remove("msg-waiting");
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			msg.classList.add("msg-error");
			let message = "no response";
			if (jqXHR.responseJSON) {
				message = jqXHR.responseJSON.message;
			}
			btn.disabled = false;
			msg.innerHTML =
				"Script Failed. Error: " + errorThrown + " Message: " + message;
		});
}
