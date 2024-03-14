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

	$(function () {
		$(".terms-modal-color-picker").wpColorPicker();

		$(".tpul_CSV_download_btn").on("click", function () {
			$(".tpul_CSV_download_btn_wait").removeClass("hidden");
		});

		$(".tpul_log_CSV_download_btn").on("click", function () {
			$(".tpul_log_CSV_download_btn_wait").removeClass("hidden");
		});
	});
})(jQuery);

/**
 * Reset Users
 */
function resetAllUsers(e) {
	e.preventDefault();

	// console.log(tpulApiSettings.root + 'terms-popup-on-user-login/v1/action/reset-users');

	jQuery
		.ajax({
			url:
				tpulApiSettings.root +
				"terms-popup-on-user-login/v1/action/reset-users",
			type: "POST",
			contentType: "application/json",
			beforeSend: function (xhr) {
				xhr.setRequestHeader("X-WP-Nonce", tpulApiSettings.tpul_nonce);

				console.log("...");

				jQuery(".tpul_script_button").prop("disabled", true);
				jQuery(".tpul_reset_button_msg").html("Script Running Please Wait...");
			},
			data: JSON.stringify({
				oo: "var",
			}),
			success: function (response) {},
		})
		.done(function (results) {
			console.log("SUCCESS");
			console.log(results);
			jQuery("#tpul__reset-all").prop("disabled", false);
			jQuery(".tpul_reset_button_msg").html("Script Ran Successfully!");
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			console.log("ERROR");
			console.log(jqXHR);
			console.log(textStatus);
			console.log(errorThrown);
			jQuery("#tpul__reset-all").prop("disabled", false);
			jQuery(".tpul_reset_button_msg").html(
				"Script Failed. Error: " + errorThrown
			);
		});
}

/**
 * Turn On Logging
 */
function purgeLog(e) {
	e.preventDefault();
	if (true || confirm("Are you sure you want to purge the log?")) {
		jQuery
			.ajax({
				url:
					tpulApiSettings.root +
					"terms-popup-on-user-login/v1/action/log/purge",
				type: "POST",
				contentType: "application/json",
				beforeSend: function (xhr) {
					xhr.setRequestHeader("X-WP-Nonce", tpulApiSettings.tpul_nonce);
					console.log("...");
					jQuery(".tpul_log_purge_btn").prop("disabled", true);
					jQuery(".tpul_log_purge_btn_msg").html(
						"Script Running Please Wait..."
					);
				},
				data: JSON.stringify({
					date: jQuery(".the_datepicker").val(),
				}),
				success: function (response) {
					jQuery(".tpul_log_purge_btn_msg").html(
						"Success.  Message: " + response.message
					);
				},
			})
			.done(function (results) {
				console.log("SUCCESS");
				console.log(results);

				jQuery(".tpul_log_purge_btn").removeAttr("disabled");
			})
			.fail(function (jqXHR, textStatus, errorThrown) {
				console.log("ERROR");
				console.log(jqXHR);
				console.log(textStatus);
				console.log(errorThrown);
				jQuery(".tpul_log_purge_btn_msg").html(
					"Script Failed. Error: " +
						errorThrown +
						" Message:" +
						jqXHR.responseJSON.message
				);
			});
	} else {
		return false;
	}
}
/**
 * Turn On Logging
 */
function enableAdvancedLogging(e) {
	e.preventDefault();

	jQuery
		.ajax({
			url: tpulApiSettings.root + "terms-popup-on-user-login/v1/action/log",
			type: "POST",
			contentType: "application/json",
			beforeSend: function (xhr) {
				xhr.setRequestHeader("X-WP-Nonce", tpulApiSettings.tpul_nonce);
				console.log("...");
				jQuery(".tpul_advanced_log_button").prop("disabled", true);
				jQuery(".tpul_log_button_msg").html("Script Running Please Wait...");
			},
			data: JSON.stringify({
				oo: "var",
			}),
			success: function (response) {},
		})
		.done(function (results) {
			console.log("SUCCESS");
			console.log(results);

			jQuery(".tpul_advanced_log_button").removeAttr("disabled");

			if (results !== null) {
				jQuery(".tpul_log_button_msg").html(results.message);
				jQuery(".tpul_logging_status").removeClass("tpul_logging_status--on");
				jQuery(".tpul_logging_status").removeClass("tpul_logging_status--off");
				if (results.tpul_addv_logging) {
					jQuery(".tpul_logging_status").addClass("tpul_logging_status--on");
					jQuery(".tpul_log_button_msg").html("Advanced Logging Enabled!");
				} else {
					jQuery(".tpul_logging_status").addClass("tpul_logging_status--off");
					jQuery(".tpul_log_button_msg").html("Advanced Logging Dissabled!");
				}
			} else {
				jQuery(".tpul_log_button_msg").html("Error 13.");
			}
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			console.log("ERROR");
			console.log(jqXHR);
			console.log(textStatus);
			console.log(errorThrown);
			// jQuery('#tpul__reset-all').prop('disabled', false);
			jQuery(".tpul_log_button_msg").html(
				"Script Failed. Error: " + errorThrown
			);
		});
}

function activateKey(e) {
	e.preventDefault();

	jQuery
		.ajax({
			url:
				tpulApiSettings.root +
				"terms-popup-on-user-login/v1/action/activatekey",
			type: "POST",
			contentType: "application/json",
			beforeSend: function (xhr) {
				xhr.setRequestHeader("X-WP-Nonce", tpulApiSettings.tpul_nonce);

				console.log("...");

				jQuery(".tpul_script_button").prop("disabled", true);
				jQuery(
					".tpul_activate_button_msg, .tpul_activate_button_loader"
				).removeClass("hide");
				jQuery(".tpul_activate_button_msg").html(
					"Script Running Please Wait..."
				);

				console.log(
					jQuery(
						'[name="tpul_settings_general_options[tplu_license_key]"]'
					).val()
				);
			},
			data: JSON.stringify({
				license_key: jQuery(
					'[name="tpul_settings_general_options[tplu_license_key]"]'
				).val(),
			}),
			success: function (response) {
				// jQuery('[name="tpul_settings_general_options[tplu_license_key]"]').get(0).type = "password";
				jQuery(".tpul_script_button").prop("disabled", false);
			},
		})
		.done(function (results) {
			console.log("SUCCESS");
			console.log(results);

			jQuery("#tpul__activate-key").type = "password";
			jQuery("#tpul__activate-key").prop("disabled", false);
			jQuery(".tpul_activate_button_loader").addClass("hide");

			if (results !== null) {
				jQuery(".tpul_activate_button_msg").html(results.message);
			} else {
				jQuery(".tpul_activate_button_msg").html(
					"Error: results is null. Please contact support."
				);
			}

			// if (results.code == 'activated'){
			jQuery("#tpul__activate-key").addClass("hide");
			// }
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			console.log("ERROR");
			console.log(jqXHR);
			console.log(textStatus);
			console.log("Script Failed. Error: " + errorThrown);

			jQuery("#tpul__activate-key").prop("disabled", false);
			jQuery(".tpul_activate_button_loader").addClass("hide");
			jQuery(".tpul_activate_button_msg").html(
				"ERROR: " + jqXHR.responseJSON.message
			);
		});
}

function deactivateKey(e) {
	e.preventDefault();

	jQuery
		.ajax({
			url:
				tpulApiSettings.root +
				"terms-popup-on-user-login/v1/action/deactivatekey",
			type: "POST",
			contentType: "application/json",
			beforeSend: function (xhr) {
				xhr.setRequestHeader("X-WP-Nonce", tpulApiSettings.tpul_nonce);

				console.log("...");

				jQuery(".tpul_script_button").prop("disabled", true);
				jQuery(
					".tpul_deactivate_button_msg, .tpul_deactivate_button_loader"
				).removeClass("hide");
				jQuery(".tpul_deactivate_button_msg").html(
					"Script Running Please Wait..."
				);

				console.log(
					jQuery(
						'[name="tpul_settings_general_options[tplu_license_key]"]'
					).val()
				);
			},
			data: JSON.stringify({
				license_key: jQuery(
					'[name="tpul_settings_general_options[tplu_license_key]"]'
				).val(),
			}),
			success: function (response) {
				// jQuery('[name="tpul_settings_general_options[tplu_license_key]"]').get(0).type = "password";
				jQuery(".tpul_script_button").prop("disabled", false);
			},
		})
		.done(function (results) {
			console.log("SUCCESS");
			console.log(results);

			jQuery("#tpul__deactivate-key").type = "password";
			jQuery("#tpul__deactivate-key").prop("disabled", false);
			jQuery("#tpul__deactivate-key-input").val("");
			jQuery(".tpul_deactivate_button_loader").addClass("hide");

			if (results !== null) {
				jQuery(".tpul_deactivate_button_msg").html(results.message);
			} else {
				jQuery(".tpul_deactivate_button_msg").html(
					"Error: results is null. Please contact support."
				);
			}

			// if (results.code == 'deactivated'){
			jQuery("#tpul__deactivate-key").addClass("hide");
			// }
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			console.log("ERROR");
			console.log(jqXHR);
			console.log(textStatus);
			console.log("Script Failed. Error: " + errorThrown);

			jQuery("#tpul__deactivate-key").prop("disabled", false);
			jQuery(".tpul_deactivate_button_loader").addClass("hide");
			jQuery(".tpul_deactivate_button_msg").html(
				"ERROR: " + jqXHR.responseJSON.message
			);
		});
}

function btnGenerateReport(e) {
	e.preventDefault();

	jQuery
		.ajax({
			url:
				tpulApiSettings.root +
				"terms-popup-on-user-login/v1/action/log/report-generate",
			type: "POST",
			contentType: "application/json",
			beforeSend: function (xhr) {
				xhr.setRequestHeader("X-WP-Nonce", tpulApiSettings.tpul_nonce);

				jQuery(".btn_generate_report").attr("disabled", true);
				jQuery(".btn_generate_report_msg").removeClass("hide");

				console.log("...");
			},
			data: JSON.stringify({}),
			success: function (response) {},
		})
		.done(function (results) {
			console.log("SUCCESS");
			console.log(results);

			if (results !== null) {
				jQuery(".btn_generate_report_msg").html(results.message);

				jQuery(".link_download_report_container").removeClass("hide");
				jQuery(".link_download_report").attr("href", results.url);
			} else {
				jQuery(".btn_generate_report_msg").html(
					"Error: results is null. Please contact support."
				);
			}
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			console.log("ERROR");
			console.log(jqXHR);
			console.log(textStatus);
			console.log("Script Failed. Error: " + errorThrown);

			jQuery(".btn_generate_report_msg").html(
				"ERROR: " + jqXHR.responseJSON.message
			);
		});
}

function btnGenerateLog(e) {
	e.preventDefault();

	jQuery
		.ajax({
			url:
				tpulApiSettings.root +
				"terms-popup-on-user-login/v1/action/log/log-generate",
			type: "POST",
			contentType: "application/json",
			beforeSend: function (xhr) {
				xhr.setRequestHeader("X-WP-Nonce", tpulApiSettings.tpul_nonce);

				jQuery(".btn_generate_log").attr("disabled", true);
				jQuery(".btn_generate_log_msg").removeClass("hide");

				console.log("...");
			},
			data: JSON.stringify({}),
			success: function (response) {},
		})
		.done(function (results) {
			console.log("SUCCESS");
			console.log(results);

			if (results !== null) {
				jQuery(".btn_generate_log_msg").html(results.message);
				jQuery(".link_download_log_container").removeClass("hide");
				jQuery(".link_download_log").attr("href", results.url);
			} else {
				jQuery(".btn_generate_report_msg").html(
					"Error: results is null. Please contact support."
				);
			}
		})
		.fail(function (jqXHR, textStatus, errorThrown) {
			console.log("ERROR");
			console.log(jqXHR);
			console.log(textStatus);
			console.log("Script Failed. Error: " + errorThrown);

			jQuery(".btn_generate_report_msg").html(
				"ERROR: " + jqXHR.responseJSON.message
			);
		});
}
/**
 * Send Test Email
 */

function tpul_send_test_email(e) {
	e.preventDefault();

	let id = e.target.id;
	let btn = e.target;
	let dataset = e.target.dataset.vals;
	let msg = document.querySelector("." + id + "__msg");
	btn.disabled = true;

	jQuery
		.ajax({
			url:
				tpulApiSettings.root +
				"terms-popup-on-user-login/v1/action/email/test-email",
			type: "POST",
			contentType: "application/json",
			beforeSend: function (xhr) {
				xhr.setRequestHeader("X-WP-Nonce", tpulApiSettings.tpul_nonce);
				console.log(btn);

				msg.innerHTML = msg.dataset.waitMsg;
				msg.classList.remove("hidden");
				msg.classList.remove("msg-success");
				msg.classList.remove("msg-error");
				msg.classList.add("msg-waiting");
			},
			data: JSON.stringify({
				data: "dataset",
			}),
			success: function (response) {
				msg.classList.add("msg-success");
				msg.innerHTML = msg.dataset.successMsg;
			},
		})
		.done(function (results) {
			btn.disabled = false;
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
