jQuery(document).ready(function($) {

	"use strict";

	var nsWmwConnectionModal = document.getElementById("ns-wmw-connection-modal");
	var btn = document.getElementById("nouvello-worker-connection-key");
	var span = document.getElementsByClassName("close")[0];
	var manualInstallationLink = document.getElementById("manual-installation-link");
	var activateButton = document.getElementById("activate-btn");
	var copyKeyButton = document.getElementById("copykey-btn");

	if (btn){
		// When the user clicks the button, open the ns-wmw-modal 
		btn.onclick = function(event) {
			event.stopPropagation();
			nsWmwConnectionModal.style.display = "block";
		}
	}

	if (span){
		span.onclick = function() {
			nsWmwConnectionModal.style.display = "none";
		}
	}

	if ( manualInstallationLink ) {
		manualInstallationLink.addEventListener("click", function(){
			// ajax call so we can set a time limit using transient.
			$.ajax({
				url: nvl_wemanage_options.ajax_url,
				type: 'POST',
				data: {
					'action': 'enable_manual_installation',
					'nonce': nvl_wemanage_options.ajax_nonce
				},
				cache: false,
				success: function(data) {
					if (data == 'enabled') {
						alert(nvl_wemanage_options.connection_key);
						copyToClipboard(nvl_wemanage_options.connection_key);
					} else {
						console.log(data);
						return;
					}
				}
			});
		}); // end of click event
	}

	if ( activateButton ) {
		activateButton.addEventListener("click", function(){
			// ajax call so we can set a time limit using transient.
			$.ajax({
				url: nvl_wemanage_options.ajax_url,
				type: 'POST',
				data: {
					'action': 'enable_manual_installation',
					'nonce': nvl_wemanage_options.ajax_nonce
				},
				cache: false,
				success: function(data) {
					if (data == 'enabled') {
						copyToClipboard(nvl_wemanage_options.connection_key);
					} else {
						console.log(data);
						return;
					}
				}
			});
		}); // end of click event
	}


	if ( copyKeyButton ) {
		copyKeyButton.addEventListener("click", function(){
			// ajax call so we can set a time limit using transient.
			$.ajax({
				url: nvl_wemanage_options.ajax_url,
				type: 'POST',
				data: {
					'action': 'enable_manual_installation',
					'nonce': nvl_wemanage_options.ajax_nonce
				},
				cache: false,
				success: function(data) {
					if (data == 'enabled') {
						copyToClipboard(nvl_wemanage_options.connection_key);
					} else {
						console.log(data);
						return;
					}
				}
			});
		}); // end of click event
	}

	function unsecuredCopyToClipboard(text) {
  	const textArea = document.createElement("textarea");
  	textArea.value = text;
  	document.body.appendChild(textArea);
  	textArea.focus();
  	textArea.select();
  	try {
    	document.execCommand('copy');
  	} catch (err) {
    	console.error('Unable to copy to clipboard', err);
  	}
  	document.body.removeChild(textArea);
	}

	function copyToClipboard(content){
  	if (window.isSecureContext && navigator.clipboard) {
    	navigator.clipboard.writeText(content);
  	} else {
    	unsecuredCopyToClipboard(content);
  	}
	};

}); // end of document ready
