<?php
// 2023-10-07 upd: 2023-10-16
if (!defined('ABSPATH')) {
	die('Invalid request.');
}
if(is_multisite())
  return;

if(!function_exists('bill_catch_javascript_errors') and !function_exists('logplugin_add_to_page')){
    function bill_catch_javascript_errors(){
	    ?>
	    <script>

		//console.log('16');

		/*
		function areCookiesAndJavaScriptEnabled() {
			document.cookie = "test_cookie=enabled";
			const cookiesEnabled = document.cookie.includes("test_cookie=enabled");

			const javascriptEnabled = typeof navigator !== "undefined" && typeof navigator.cookieEnabled !== "undefined" && navigator.cookieEnabled;

			return cookiesEnabled && javascriptEnabled;
		}

		if (areCookiesAndJavaScriptEnabled()) {
			// ok
			console.log('enabled');
		} else {
			// not ok
			//return;
			console.log('disabled');
		}
		*/



		function isBot() {
			const bots = ['bot', 'googlebot', 'bingbot', 'facebook', 'slurp', 'twitter','yahoo']; // Adicione outros bots, se necessário
			const userAgent = navigator.userAgent.toLowerCase();
			return bots.some(bot => userAgent.includes(bot));
		}

		/*
		if (isBot()) {
			//return;
			console.log('is bot');
        }
		else{
			console.log('not is bot');
		}
		*/

		//console.log(navigator.userAgent);

		var errorQueue = []; // Initialize an array to store errors
		var timeout;

		window.onerror = function (msg, url, line) {



			var errorMessage = [
					'Message: ' + msg,
					'URL: ' + url,
					'Line: ' + line
				].join(' - ');


			// Filter bots errors...
			if (isBot()) {
			    return;
            }


			errorQueue.push(errorMessage); // Add the error to the queue

			// console.log(errorMessage);

			if (errorQueue.length >= 5) { // Send errors in batches of 5 or adjust the batch size as needed
				sendErrorsToServer();
			} else {
				// Set a timeout to send the errors after a delay (e.g., 5 seconds)
				clearTimeout(timeout);
				timeout = setTimeout(sendErrorsToServer, 5000); // Adjust the delay as needed (5 seconds in this example)
			}
		};

		function sendErrorsToServer() {
			if (errorQueue.length > 0) {
				// Combine errors into a single message
				var message = errorQueue.join(' | ');

				var xhr = new XMLHttpRequest();
				var nonce = '<?php echo esc_js(wp_create_nonce('bill-catch-js-errors')); ?>';
				xhr.open('POST', '<?php echo admin_url('admin-ajax.php'); ?>');
				xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				xhr.onload = function () {
					if (200 === xhr.status) {
						try {
							// response = JSON.parse( xhr.response );
							// console.log(xhr.response);
						} catch (e) {
							console.log(xhr.response);
						}
					} else {
						console.log(xhr.status);
					}
				};
				xhr.send(encodeURI('action=bill_js_error_catched&_wpnonce=' + nonce + '&bill_js_error_catched=' + message));

				errorQueue = []; // Clear the error queue after sending
			}
  		}

        // Send any remaining errors when the page unloads
        window.addEventListener('beforeunload', sendErrorsToServer);

		</script>
		<?php

    }
	add_action('admin_head', 'bill_catch_javascript_errors');
	add_action('wp_head', 'bill_catch_javascript_errors');


	if(!function_exists("bill_is_action_registered")){
		function bill_is_action_registered($hook_name, $callback_function) {
			global $wp_filter;
			if (isset($wp_filter[$hook_name])) {
				foreach ($wp_filter[$hook_name] as $priority => $actions) {
					foreach ($actions as $action) {
						if (is_array($action['function']) && $action['function'][0] === $callback_function) {
							return true;
						}
					}
				}
			}
			return false;
		}
	}

	// call only if needs it.
	if (!bill_is_action_registered('wp_ajax_bill_get_js_errors', 'bill_js_error_catched')) {
		add_action('wp_ajax_bill_js_error_catched', 'bill_js_error_catched');
		add_action('wp_ajax_nopriv_bill_js_error_catched', 'bill_js_error_catched');
	}

}


if(!function_exists("bill_js_error_catched")){
    function bill_js_error_catched()
	{
		if (isset($_REQUEST)) {
			if (!isset($_REQUEST['bill_js_error_catched']))
				die("empty error");
			if (!wp_verify_nonce($_POST['_wpnonce'], 'bill-catch-js-errors')) {
				status_header(406, 'Invalid nonce');
				die();
			}
			$bill_js_error_catched = sanitize_text_field($_REQUEST['bill_js_error_catched']);
			$bill_js_error_catched = trim($bill_js_error_catched);
			if (!empty($bill_js_error_catched)) {
				$parts = explode(" | ", $bill_js_error_catched);
				for ($i = 0; $i < count($parts); $i++) {
					$txt = 'Javascript ' . $parts[$i];
			    	error_log($txt);
				    add_option( 'bill_javascript_error', time() );
				}
				die('OK!!!');
			}
		}
		die('NOT OK!');
	}
}