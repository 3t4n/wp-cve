jQuery(document).ready(function ($) {
	'use strict';

	function init_chat_engine() {
		// addEventListener support for IE8
		function bindEvent(element, eventName, eventHandler) {
			if (element.addEventListener) {
				element.addEventListener(eventName, eventHandler, false);
			} else if (element.attachEvent) {
				element.attachEvent('on' + eventName, eventHandler);
			}
		}

		let params = new URLSearchParams(nouvello_chat_engine_params).toString();
		let visitorTimezone =
			'&timezone=' + Intl.DateTimeFormat().resolvedOptions().timeZone;
		let direction =
			'&dir=' + (document.body.classList.contains('rtl') ? 'rtl' : 'ltr');
		let windowHeight = '&height=' + window.innerHeight;
		let iframeSource =
			nouvello_chat_engine_params.server_url +
			'/chat/wp-widget?' +
			params +
			visitorTimezone +
			direction +
			windowHeight;

		// Create the iframe
		var iframe = document.createElement('iframe');
		iframe.setAttribute('src', iframeSource);
		iframe.setAttribute('id', 'nouvello-chat-engine');

		// base iframe styling
		iframe.style.position = 'fixed';
		iframe.style.zIndex = '99999';
		iframe.style.right = '0px';
		iframe.style.bottom = '0px';
		iframe.style.border = '0';
		iframe.style.width = '100%';
		iframe.style.height = '100%';
		iframe.style.maxWidth = '0px';
		iframe.style.maxHeight = '0px';
		//

		document.body.appendChild(iframe);

		// send a message to the child iframe
		var iframeEl = document.getElementById('nouvello-chat-engine');

		// send a message to the child iframe
		var sendMessage = function (msg) {
			// sake sure you are sending a string, and to stringify JSON
			iframeEl.contentWindow.postMessage(msg, '*');
		};

		// listen to message from child window
		bindEvent(window, 'message', function (e) {
			// if we have a msg with type string.
			if (typeof e.data === 'string' || e.data instanceof String) {
				parseMessage(iframe, e.data);
			}
		});
	}

	init_chat_engine();

	function parseMessage(iframe, data) {
		try {
			let jsonData = JSON.parse(data);

			if (
				jsonData &&
				jsonData.target &&
				'nouvello_chat_widget' == jsonData.target &&
				jsonData.action
			) {
				switch (jsonData.action) {
					case 'widget_position':
						if (jsonData.top) {
							iframe.style.top = jsonData.top;
						}
						if (jsonData.bottom) {
							iframe.style.bottom = jsonData.bottom;
						}
						if (jsonData.left) {
							iframe.style.left = jsonData.left;
						}
						if (jsonData.right) {
							iframe.style.right = jsonData.right;
						}
						break;

					case 'show_widget':
						iframe.style.maxWidth = '55px';
						iframe.style.maxHeight = '55px';
						break;

					case 'hide_widget':
						iframe.style.maxWidth = '0px';
						iframe.style.maxHeight = '0px';
						break;

					case 'open_widget':
						// increase iframe size.
						iframe.style.maxWidth = '400px';
						iframe.style.maxHeight = '600px';

						// add iframe shadow
						setTimeout(function () {
							iframe.style.boxShadow = '0px 1px 9px -3px rgb(0 0 0 / 10%)';
						}, 380);
						break;

					case 'close_widget':
						iframe.style.boxShadow = 'none';
						setTimeout(function () {
							// decrease iframe size.
							iframe.style.maxWidth = '55px';
							iframe.style.maxHeight = '55px';
						}, 300);
						break;

					default:
						// do nothing.
						break;
				}
			}
		} catch (error) {
			console.log(error);
			console.log('Original event received', e.data);
		}
	}
});
