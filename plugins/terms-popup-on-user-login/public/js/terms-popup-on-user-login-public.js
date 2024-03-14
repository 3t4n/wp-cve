(function ($) {
	"use strict";

	// alert("ddd");
	// console.log(tpulApiSettings.tpul_nonce);

	/**
	 * All of the code for your public-facing JavaScript source
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

	/**
	 * Helper Functions
	 */

	function __isGeoLocationTrackingEnabled() {
		var bodyElement = document.body;
		console.log(bodyElement.classList);
		return bodyElement.classList.contains("tpulGeoLocationTrackingEnabled");
	}

	function __tpul_determinGeoLocation() {
		let coord = {
			lat: "not Tracked",
			long: "not Tracked",
		};

		if (__isGeoLocationTrackingEnabled()) {
			console.log("attempt location read");
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(
					function (position) {
						// Success callback
						coord = {
							lat: position.coords.latitude,
							long: position.coords.longitude,
						};
						window.tpul_GeoLocationResult = JSON.stringify(coord);
					},
					function (error) {
						// Error callback
						console.error("Error getting geolocation:", error.message);
						coord = {
							lat: "Browser or OS denied",
							long: "Browser or OS denied",
						};
						window.tpul_GeoLocationResult = JSON.stringify(coord);
					}
				);
			} else {
				// Geolocation not supported
				coord = {
					lat: "browser Denied",
					long: "browser Denied",
				};
				window.tpul_GeoLocationResult = JSON.stringify(coord);
			}
		} else {
			// GeoLocationTracking is not enabled
			window.tpul_GeoLocationResult = JSON.stringify(coord);
		}
	}

	function __tpul_getGeolocation() {
		if (window.tpul_GeoLocationResult) {
			return window.tpul_GeoLocationResult;
		} else {
			let coord = {
				lat: "missing data",
				long: "missing data",
			};
			return JSON.stringify(coord);
		}
	}

	function __disable_esc() {
		$(document).keydown(function (event) {
			if (event.keyCode === 27) {
				event.stopImmediatePropagation();
				console.log("esc stopped");
			}
		});
	}

	function __update_body_is_show_modal() {
		$("body").addClass("is_show_terms_popup");
	}
	function __update_body_is_closed_modal() {
		$("body").removeClass("is_show_terms_popup");
	}

	function closeCompare(a, b, margin) {
		if (Math.abs(a - b) < margin) {
			return 1;
		}
		return 0;
	}

	function __show_accept_transient_message() {
		$(".modal__subtitle_wrapper").addClass("hide");
		$(".modal__terms_wrapper").addClass("hide");
		$(".modal__btn").addClass("hide");

		$(".modal__accepting_wrapper").removeClass("hide");
		$(".modal__loader_wrapper").removeClass("hide");
	}

	function __show_cancel_transient_message() {
		$(".modal__subtitle_wrapper").addClass("hide");
		$(".modal__terms_wrapper").addClass("hide");
		$(".modal__btn").addClass("hide");

		$(".modal__logginout_wrapper").removeClass("hide");
		$(".modal__loader_wrapper").removeClass("hide");
	}

	function __set_accept_session_cookkie() {
		Cookies.set("tpul_user_accepted", "true");
	}

	function __get_accept_session_cookkie() {
		let has_user_accepted = Cookies.get("tpul_user_accepted");
		if (
			typeof has_user_accepted !== "undefined" &&
			has_user_accepted == "true"
		) {
			return true;
		}
		return false;
	}

	/**
	 * Ajax Calls ---------------------------------------------------------------------------
	 */

	/**
	 * Handle Logout
	 */
	function logoutUser(e) {
		$.ajax({
			url:
				tpulApiSettings.root +
				"terms-popup-on-user-login/v1/action/logout-user",
			type: "POST",
			contentType: "application/json",
			beforeSend: function (xhr) {
				xhr.setRequestHeader("X-WP-Nonce", tpulApiSettings.tpul_nonce);

				__show_cancel_transient_message();

				console.log("...");
			},
			data: JSON.stringify({
				oo: "var",
			}),
			success: function (response) {},
		})
			.done(function (results) {
				console.log("SUCCESS");
				console.log(results);

				var redirectUrl = $("#tpul-modal-btn-decline-wait").attr(
					"data-redirectUrl"
				);
				console.log(redirectUrl);
				if (redirectUrl) {
					console.log("redirecting");
					window.location.replace(redirectUrl);
				} else {
					MicroModal.close();
				}
				if (results) {
				}
			})
			.fail(function (jqXHR, textStatus, errorThrown) {
				console.log("ERROR");
				console.log(jqXHR);
				console.log(textStatus);
				console.log(errorThrown);
				document.location.href = "/";
			});
	}

	/**
	 * Handle Accepted Terms
	 */

	function acceptTermsUser(e) {
		let tpul_visitor_id = Cookies.get("tpul_visitor_id");
		if (!tpul_visitor_id) {
			tpul_visitor_id = generateUniqueId();
			Cookies.set("tpul_visitor_id", tpul_visitor_id, {
				expires: 364,
			});
		}

		$.ajax({
			url:
				tpulApiSettings.root +
				"terms-popup-on-user-login/v1/action/accept-terms",
			type: "POST",
			dataType: "json",
			contentType: "application/json",
			beforeSend: function (xhr) {
				xhr.setRequestHeader("X-WP-Nonce", tpulApiSettings.tpul_nonce);

				__show_accept_transient_message();
			},
			data: JSON.stringify({
				oo: "var",
				useragent: navigator.userAgent,
				locationCoordinates: __tpul_getGeolocation(),
				tpul_visitor_id: tpul_visitor_id,
				currentURL: window.location.pathname,
			}),
			success: function (response) {},
		})
			.done(function (results) {
				console.log("SUCCESS");
				console.log(results);
				console.log(results.data.redirect);

				if (results) {
					console.log(results);

					__update_body_is_closed_modal();

					let data_is_logged_in = $(this).attr("data-isloggedin");
					if (results.data.redirect && data_is_logged_in == "true") {
						window.location.replace(results.data.redirect);
					} else {
						if (results.data.accepted) {
							MicroModal.close();
						}
					}
				} else {
					// No redirect
					MicroModal.close();
				}
			})
			.fail(function (jqXHR, textStatus, errorThrown) {
				console.log("ERROR");
				console.log(jqXHR);
				console.log(textStatus);
				console.log(errorThrown);
				document.location.href = "/";
			});
	}

	/**
	 * Test mode on Close
	 */
	function closeInTestmode(e) {
		__show_cancel_transient_message();

		setTimeout(function () {
			MicroModal.close();
			alert(
				"You would now be logged out. -- TEST MODE is still ON. Remember to turn it OFF at Settings > Terms Popup on User Login."
			);
		}, 3000);
		__update_body_is_closed_modal();
	}

	/**
	 * Generate a unique identifier
	 *
	 */
	function generateUniqueId() {
		return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(
			/[xy]/g,
			function (c) {
				const r = (Math.random() * 16) | 0;
				const v = c === "x" ? r : (r & 0x3) | 0x8;
				return v.toString(16);
			}
		);
	}

	/**
	 * Test mode on Accept
	 */
	function acceptInTestmode() {
		__show_accept_transient_message();

		setTimeout(function () {
			MicroModal.close();
			alert(
				"TEST MODE is still ON. Remember to Turn Test mode OFF at Settings > Terms Popup on User Login."
			);
		}, 3000);
		__update_body_is_closed_modal();
	}

	/**
	 * Setup and Call Modal ---------------------------------------------------------------------------
	 */

	$(function () {
		// https://micromodal.now.sh/

		/**
		 * Show Accept Terms on user login
		 */
		if (
			$("body.terms-popup-on-user-login-accept-terms").length ||
			$("body.terms-popup-on-user-login-accept-terms-testmode").length
		) {
			__tpul_determinGeoLocation();
			let data_check_cookie = $("#modal-accept-terms").attr("data-checkcookie");
			let show_modal = true;

			// should check cookie
			if (data_check_cookie.length && data_check_cookie == "true") {
				// cookie already present
				// anon user has already accepted this modal
				// modal was set to be remembered in cookie
				if (__get_accept_session_cookkie() == true) {
					show_modal = false;
				}
			}

			if (show_modal) {
				// dissable Esc key
				__disable_esc();
				console.log("init modl and show");

				MicroModal.init({
					onShow: (modal) => console.info(`${modal.id} is shown`), // [1]
					onClose: (modal) => console.info(`${modal.id} is hidden`), // [2]
					openTrigger: "data-custom-open", // [3]
					closeTrigger: "data-custom-close", // [4]
					openClass: "is-open", // [5]
					disableScroll: true, // [6]
					disableFocus: true, // [7]
					awaitOpenAnimation: false, // [8]
					awaitCloseAnimation: false, // [9]
					debugMode: false, // [10]
				});

				/**
				 * Show Modal
				 */

				MicroModal.show("modal-accept-terms");
				__update_body_is_show_modal();
			}
		}

		/***********************************************************************************
		 * Extra Functionality
		 ***********************************************************************************/
		/*
		 * Disable Accept button until User Scrolls down if content surpasses container and scroll is available
		 */

		if ($(".modal__terms__inner").height() > 577) {
			$(".modal__btn-primary.disabled-by-default").attr("disabled");
			$(".modal__btn-primary.disabled-by-default").addClass("disabled");

			$(".modal__terms_wrapper").scroll(function () {
				if (window.devicePixelRatio) {
					var browserZoomLevel = Math.round(window.devicePixelRatio * 100);
					var compare = closeCompare(scrollToptoBottom, scrollPosition, 25);
				}

				var scrollToptoBottom = $(this).scrollTop();
				var scrollPosition = $(this)[0].scrollHeight - $(this).height();

				if (closeCompare(scrollToptoBottom, scrollPosition, 25)) {
					$(".modal__btn-primary.disabled-by-default").removeAttr("disabled");
					$(".modal__btn-primary.disabled-by-default").removeClass("disabled");
				}
			});
		}

		/***********************************************************************************
		 * Accept and Decline button events for Terms Popup on User logn
		 ***********************************************************************************/

		/**
		 * Decline button was clicked
		 */
		$(".modal__close_login").click(function () {
			if ($("body.terms-popup-on-user-login-accept-terms-testmode").length) {
				closeInTestmode();
			} else {
				logoutUser();
			}
		});

		/**
		 * Accept button was clicked
		 */
		$(".modal_accept_login").click(function () {
			if (!$(this).hasClass("disabled")) {
				if ($("body.terms-popup-on-user-login-accept-terms-testmode").length) {
					acceptInTestmode();
				} else {
					acceptTermsUser();
				}
			}
		});

		/***********************************************************************************
		 * WooCommerce Accept and Close button functionality
		 ***********************************************************************************/

		/**
		 * Decline button was clicked
		 */
		$(".modal__close_woo").click(function () {
			if ($("body.terms-popup-on-user-login-accept-terms-testmode").length) {
				closeInTestmode();
			} else {
				let data_is_logged_in = $(this).attr("data-isloggedin");
				let data_should_logout = $(this).attr("data-logout");
				let data_decline_url = $(this).attr("data-declineredirect");

				if (
					data_is_logged_in == "true" &&
					data_should_logout.length &&
					data_should_logout == "logout"
				) {
					logoutUser();
					__show_cancel_transient_message();
				} else {
					setTimeout(function () {
						__show_cancel_transient_message();
						if (data_decline_url.length) {
							window.location.replace(data_decline_url);
						} else {
							window.location.replace("/");
						}
					}, 1000);
				}
			}
		});

		/**
		 * Accept button was clicked
		 */
		$(".modal_accept_woo").click(function () {
			if (!$(this).hasClass("disabled")) {
				if ($("body.terms-popup-on-user-login-accept-terms-testmode").length) {
					acceptInTestmode();
				} else {
					let data_is_logged_in = $(this).attr("data-isloggedin");
					let data_save_cookie = $(this).attr("data-savecookie");

					if (data_save_cookie == "true") {
						__set_accept_session_cookkie();
					}
					// send even anonymous users request to the backend
					acceptTermsUser();
				}
			}
		});
	});
})(jQuery);
