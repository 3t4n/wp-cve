/**
 * Define object to easily access the supported status codes.
 * The codes will be grouped by category:
 * - appointments
 * - packages
 * - subscriptions
 *
 * @var object
 */
if (typeof VIKAPPOINTMENTS_STATUS_CODES_MAP === 'undefined') {
	var VIKAPPOINTMENTS_STATUS_CODES_MAP = {};
}

/**
 * Reservation status codes context menu.
 */
(function($) {
	/**
	 * Click event implementor for context menu buttons.
	 *
	 * @param 	object  handle  The popup handler.
	 * @param 	Event   event   The triggered event.
	 *
	 * @return 	void
	 */
	var statusCodesPopupButtonClicked = function(handle, event) {
		// get selected order
		var id_order = parseInt($(handle).attr('data-id'));
		// get selected status
		var status = $(handle).attr('data-status');

		// do not go ahead if the status didn't change
		if (status == this.id) {
			return false;
		}

		// replace HTML with loading icon
		$(handle).css('opacity', 0.5);

		// make request
		UIAjax.do(
			this.url,
			{
				id:     id_order,
				status: this.id,
				layout: this.layout
			},
			(resp) => {
				// inject ID order in response
				resp.id_order = id_order;

				// update status HTML
				$(handle).html(resp.html);

				// reset opacity
				$(handle).css('opacity', 'inherit');

				// update root with new status
				$(handle).attr('data-status', resp.code);

				// trigger change method, if supported
				if (this.onChange) {
					this.onChange(resp, handle);
				}

				// trigger status code changed event
				$(window).trigger('statuscode.changed', [resp, handle]);
			},
			(error) => {
				// trigger status code error event
				$(window).trigger('statuscode.error', [error, handle]);

				// reset opacity
				$(handle).css('opacity', 'inherit');
			}
		);
	};

	/**
	 * Register jQuery plugin.
	 *
	 * @param 	mixed 	method  A configuration object or a method to invoke.
	 *
	 * @return 	self    The given root to support chaining.
	 */
	$.fn.statusCodesPopup = function(method) {
		var root = this;

		if (!method) {
			method = {};
		}

		// initialize popup events
		if (typeof method === 'object') {
			// create default object
			var data = $.extend({
				url:      null,
				group:    null,
				layout:   null,
				onShow:   null,
				onHide:   null,
				onChange: null,
			}, method);

			// iterate all elements
			$(root).each(function() {
				var buttons = [];

				// check if we have some codes for this group
				if (VIKAPPOINTMENTS_STATUS_CODES_MAP.hasOwnProperty(data.group)) {
					// iterate all status codes
					VIKAPPOINTMENTS_STATUS_CODES_MAP[data.group].forEach((status) => {
						// create new button
						let btn = {
							id:       status.code,
							text:     status.name,
							action:   statusCodesPopupButtonClicked,
							url:      data.url,
							layout:   data.layout,
							onChange: data.onChange,
							icon: function(handle, config) {
								// display a colored icon next to the status code
								return $('<i class="far fa-circle"></i>').css('color', '#' + status.color);
							},
							visible: function(handle, config) {
								// get selected status
								var code = $(handle).attr('data-status');

								// hide in case the status code is already assigned
								return this.id != code;
							},
						};

						// add button to list
						buttons.push(btn);
					});
				}

				// init context menu
				$(this).vikContextMenu({
					buttons: buttons,
					class:   'statuscodes-context-menu',
					onShow:  data.onShow,
					onHide:  data.onHide,
				});
			});
		}
		// check if we should destroy the popup
		else if (typeof method === 'string' && method.match(/^(destroy)$/i)) {
			$(this).vikContextMenu('destroy');
		}
		// check if we should dismiss the popup
		else if (typeof method === 'string' && method.match(/^(close|dismiss|hide)$/i)) {
			$(this).vikContextMenu('hide');
		}
		// otherwise open the popup
		else {
			$(this).vikContextMenu('show');
		}

		return this;
	};
})(jQuery);