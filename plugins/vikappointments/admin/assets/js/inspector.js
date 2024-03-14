/**
 * jQuery add-on used to support a sidebar inspector.
 * Here's a list of supported options.
 *
 * - title  string  The title to use for the inspector.
 * - url    string  An optional URL to load the contents via AJAX.
 *
 * List of methods supported by the add-on.
 *
 * - open|show           Manually displays the popup menu.
 * - close|dismiss|hide  Manually disposes the popup menu.
 */
(function($) {
	$.fn.inspector = function(method, data) {
		// check if we should dismiss the inspector
		if (typeof method === 'string' && method.match(/^(close|dismiss|hide)$/i)) {
			if (!this.is(':visible')) {
				// do not propagate error in case the inspector is currently closed
				return this;
			}

			// trigger close event for subscribed listeners
			var event = $.Event('inspector.close');
			this.trigger(event);

			// check if we should prevent closure in case a subscribed
			// listener stopped the event propagation
			if (event.isPropagationStopped() === true) {
				// do not go ahead
				return this;
			}

			if (typeof $.fn.select2 !== 'undefined') {
				// immediately auto-dismiss any open Select2 dropdowns
				this.find('select.select2-offscreen').select2('close');
			}

			// hide class to slide out the inspector (takes 300ms for completion)
			this.removeClass('slide-in');

			// turn off events
			this.find('.inspector-head a.dismiss').off('click');
			this.closest('.record-inspector-overlay').off('mousedown');

			// hide overlay once the inspector has disappeared
			setTimeout(() => {
				this.closest('.record-inspector-overlay').hide();

				// restore body scroll once all the overlays are hidden
				if ($('.record-inspector-overlay:visible').length == 0) {
					$('body').css('overflow', 'auto');
				}
			}, 300);

			return this;
		}

		if (typeof data === 'string') {
			// URL was passed
			data = {url: data};
		} else if (typeof data !== 'object') {
			// use an empty object in case the second argument is not an object
			data = {};
		}

		// inject received parameters within the event to dispatch
		var event = $.Event('inspector.show');
		event.params = data;

		// fallback to opening method
		this.trigger(event);

		// check if we should prevent opening in case a subscribed
		// listener stopped the event propagation
		if (event.isPropagationStopped() === true) {
			// do not go ahead
			return this;
		}

		if (data.title) {
			this.find('.inspector-head h3').html(data.title);
		}

		var self = this;

		// find close button
		var closeButton = this.find('.inspector-head a.dismiss');
		// if exists, register click event to dismiss the inspector
		if (closeButton.length) {
			closeButton.on('click', (event) => {
				self.inspector('dismiss');
			});
		}

		// in case of close button or ESC key, make the overlay clickable
		if (closeButton.length || this.data('esc') == 1) {
			this.closest('.record-inspector-overlay').on('mousedown', function(event) {
				// close inspector only in case the clicked element was exactly the overlay
				if ($(event.originalEvent.srcElement).is($(this))) {
					self.inspector('dismiss');
				}
			});
		}

		// show the overlay
		this.closest('.record-inspector-overlay').show();

		// prevent body from being scrolled
		$('body').css('overflow', 'hidden');

		// slide in the inspector after a few milliseconds
		setTimeout(() => {
			this.addClass('slide-in');
		}, 32);

		// create promise
		return new Promise((resolve, reject) => {
			// in case a URL was passed, recover contents via AJAX with the given HREF
			if (data.url) {
				// add loading HTML to inspector body
				this.find('.inspector-body').html('<div class="inspectory-body-loading"></div>');

				UIAjax.do(
					// reach the specified end-point
					data.url,
					// do not use POST data
					{},
					// handle successful response
					function(resp) {
						// try to decode JSON response
						try {
							resp = JSON.parse(resp);

							if (Array.isArray(resp)) {
								// extract HTML from array
								resp = resp.shift();
							}
						} catch (err) {
							// no JSON, plain HTML was returned
						}

						// push HTML within the body inspector
						this.find('.inspector-body').html(resp);

						// resolve promise
						resolve();
					},
					// handle error
					function(error) {
						// reject promise
						reject(error);
					}
				);
			}
			// otherwise immediately resolve the promise
			else {
				resolve();
			}
		}).then(() => {
			// inject received parameters within the event to dispatch
			var event = $.Event('inspector.aftershow');
			event.params = data;
			
			// trigger event once the inspector is already visible
			this.trigger(event);

		}).catch((error, inspector) => {
			// remove loading HTML from inspector body
			this.find('.inspector-body').html('');

			// display error
			if (!error.responseText) {
				// use default connection lost error
				error.responseText = Joomla.JText._('VAPCONNECTIONLOSTERROR');
			}

			setTimeout(() => {
				// wait some milliseconds to let the process clears the
				// loading box before showing the alert
				alert(error.responseText);

				// then auto-close inspector
				this.inspector('dismiss');
			}, 32);

		});

		return this;
	}
})(jQuery);

/**
 * Shortcut to open the inspector.
 *
 * @param 	string  id    The id attribute of the inspector.
 * @param 	mixed   data  The inspector configuration.
 *
 * @return 	mixed   The inspector.
 */
function vapOpenInspector(id, data) {
	return jQuery('.record-inspector#' + id).inspector('show', data);
}

/**
 * Shortcut to close the inspector.
 *
 * @param 	string  id  The id attribute of the inspector.
 *
 * @return 	mixed   The inspector.
 */
function vapCloseInspector(id) {
	// shortcut to close the inspector
	return jQuery('.record-inspector#' + id).inspector('dismiss');
}
