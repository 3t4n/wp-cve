/* global android, webkit, jQuery */

jQuery(function($) {
	if (typeof android !== "undefined" && typeof android.hideUserUI === "function")
		android.hideUserUI();

	if (
		typeof webkit !== "undefined" &&
		typeof webkit.messageHandlers !== "undefined" &&
		typeof webkit.messageHandlers.hideUserUI !== "undefined" &&
		typeof webkit.messageHandlers.hideUserUI.postMessage === "function"
	)
		webkit.messageHandlers.hideUserUI.postMessage("");

	localStorage.setItem("state", "");
	localStorage.setItem("log", "");
	localStorage.setItem("username", "");
	localStorage.setItem("version", "");
});
