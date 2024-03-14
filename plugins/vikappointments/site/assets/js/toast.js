(function($, w) {
	'use strict';

	/**
	 * Class used to handle screen messages displayed 
	 * using a "toast" layout.
	 *
	 * In case the system is able to display several messages, 
	 * it is suggested to always enqueue the messages instead
	 * of immediately dispatching them.
	 *
	 * How to init the toast message: 
	 *
	 *   VAPToast.create();
	 *
	 *   VAPToast.create(VAPToast.POSITION_BOTTOM_RIGHT);
	 *
	 * How to dispatch/enqueue a message:
	 *
	 *   VAPToast.dispatch('This is a message');
	 *
	 *   VAPToast.enqueue({
	 *       text:   'This is a successful message',
	 *       status: 1,	
	 *       delay:  2000,
	 *   });
	 */
	w['VAPToast'] = class VAPToast {
		/**
		 * Initiliazes the class for being used.
		 * Creates the HTML of the toast.
		 * This method is executed only once.
		 *
		 * In case this method is invoked in the head of the
		 * document, it must be placed within a "onready" statement.
		 *
		 * @param 	string  position   The position of the toast.
		 * @param 	string  container  The container to which append the toast.
		 *
		 * @return 	void
		 *
		 * @see 	changePosition() in case it is needed to change the 
		 * 							 position of the toast if it has been
		 * 							 already loaded.
		 */
		static create(position, container) {
			// check if the toast message has been already created
			if ($('#vap-toast-wrapper').length == 0) {

				if (!position) {
					// use default position in case the parameter was not specified
					position = VAPToast.defaultPosition;
				} else {
					// overwrite default position
					VAPToast.defaultPosition = position;
				}

				if (!container || $(container).length == 0) {
					// fallback to body
					container = 'body';
				}

				// append toast HTML to body
				$(container).append(
					'<div class="vap-toast-wrapper ' + position + '" id="vap-toast-wrapper">\n'+
					'	<div class="toast-message">\n'+
					'		<div class="toast-message-content"></div>\n'+
					'	</div>\n'+
					'</div>\n'
				);

				// handle hover/leave events to prevent the toast
				// disposes itself when the mouse is focusing it
				$('#vap-toast-wrapper').hover(() => {
					// register flag when hovering the mouse
					// above the toast message
					VAPToast.mouseHover = true;
				}, () => {
					if (VAPToast.mouseHover && VAPToast.disposeDelay) {
						// reset timeout
						clearTimeout(VAPToast.timerHandler);

						// schedule timeout again to dispose the toast
						VAPToast.timerHandler = setTimeout(VAPToast.dispose, VAPToast.disposeDelay);
					}

					// clear flag
					VAPToast.mouseHover = false;
				});
			}
		}

		/**
		 * Changes the position of the toast.
		 *
		 * @param 	string  position  The position in which the toast message will be displayed.
		 * 							  See class constants to check all the supported positions.
		 * @param 	bool    save      Whether to use the specified position by default.
		 *
		 * @return 	void
		 */
		static changePosition(position, save) {
			if (position) {
				$('#vap-toast-wrapper').attr('class', 'vap-toast-wrapper ' + position);

				if (save !== false) {
					// update default position
					VAPToast.defaultPosition = position;
				}
			}
		}

		/**
		 * Immediately displays the message.
		 * In case the toast was already visible when calling
		 * this method, it will perform a shake effect.
		 *
		 * @param 	mixed 	message  The message to display or an object with the data to use:
		 * 							 - text      string    The message to display.
		 * 							 - status    string    The message status: 0 for error, 
		 * 												   1 for success, 2 for warning, 3 for notice.
		 * 							 - delay     integer   The time for which the toast remains open.
		 *                                                 Use false, null or 0 to prevent auto-disposal.
		 * 							 - action    function  If specified, the function to invoke when
		 * 												   clicking the toast box.
		 * 							 - callback  function  If specified, the callback to invoke after
		 * 												   displaying the toast message.
		 * 							 - style     mixed     Either a string or an object of styles to be
		 * 												   applied to the toast message content box.
		 *                           - sound     string    The source path of the audio file to play.
		 *                           - position  string    The position in which the toast should be
		 *                                                 displayed (see supported constants).
		 *                           - class     string    An additional class to be appended to the
		 *                                                 toast element (useful for individual styling).
		 *
		 * @return 	void
		 */
		static dispatch(message) {
			var toast   = $('#vap-toast-wrapper');
			var content = toast.find('.toast-message-content');

			// clear any action previously set
			toast.off('click');

			// create message object in case a string was passed
			if (typeof message === 'string') {
				message = {
					text: message,
					status: 1,
				};
			}

			if (!message.text) {
				// invalid text, nothing to display
				throw 'ToastInvalidTextMessage';
			}

			if (!message.position) {
				// use default toast position
				message.position = VAPToast.defaultPosition;
			}

			// reset position before show
			VAPToast.changePosition(message.position, false);

			// check if we have to play a custom class for individual styling
			if (message.class) {
				// append the specified class, which will be automatically removed
				// by "changePosition" the next time we dispatch a toast
				toast.addClass(message.class);
			}

			// attach click event to toast message if specified
			if (message.hasOwnProperty('action') && typeof message.action === 'function') {
				toast.addClass('clickable').on('click', message.action);
			} else {
				toast.removeClass('clickable');
			}

			// perform a "shake" effect in case the toast is already visible
			if (VAPToast.timerHandler) {
				clearTimeout(VAPToast.timerHandler);

				toast.removeClass('do-shake').delay(200).queue(function(next) {
					$(this).addClass('do-shake');
					next();
				});
			}

			try {
				// try to append specified text as HTML
				content.html(message.text);
			} catch (err) {
				// an error occurred, display generic message
				console.warn('toast.dispatch.sethtml', err);
				content.html('Unknown error.');
				message.status = 0;
			}

			// remove all classes that might have been previosuly set
			content.removeClass('error');
			content.removeClass('success');
			content.removeClass('warning');
			content.removeClass('notice');

			var delay = 0;

			// fetch status class and related delay
			switch (message.status) {
				case VAPToast.ERROR_STATUS:
					content.addClass('error');
					delay = 4500;
					break;

				case VAPToast.SUCCESS_STATUS:
					content.addClass('success');
					delay = 2500;
					break;

				case VAPToast.WARNING_STATUS:
					content.addClass('warning');
					delay = 3500;
					break;

				case VAPToast.NOTICE_STATUS:
					content.addClass('notice');
					delay = 3500;
					break;
			}

			// fetch message content style
			var style = '';

			if (message.hasOwnProperty('style')) {
				// check if we received an object
				if (typeof message.style === 'object') {
					// iterate style properties
					style = [];

					for (var k in message.style) {
						if (message.style.hasOwnProperty(k)) {
							// append rule to string
							style.push(k + ':' + message.style[k] + ';');
						}
					}

					// implode the style array
					style = style.join(' ');
				}
				// otherwise cast to string what we received
				else {
					style = message.style.toString();
				}
			}

			content.attr('style', style);

			// overwrite delay in case it was specified
			if (message.hasOwnProperty('delay')) {
				let ms = parseInt(message.delay);

				if (isNaN(delay)) {
					// do not auto-dispose
					delay = 0;
				} else {
					// use the given delay
					delay = Math.abs(message.delay);
				}
			}

			// register delay
			VAPToast.disposeDelay = delay;

			if (VAPToast.disposeDelay) {
				// register timer to dispose the toast message once the specified
				// delay is passed
				VAPToast.timerHandler = setTimeout(VAPToast.dispose, delay);
			} else {
				// flag timer handler to properly process the queue
				VAPToast.timerHandler = true;

				// dispose only after clicking the notification
				toast.addClass('clickable').on('click', () => {
					// force closure because we are above the toast
					// and the mouseHover flag could be active
					VAPToast.dispose(true);
				});
			}

			setTimeout(() => {
				// slide in the toast message
				toast.addClass('toast-slide-in ready');
			}, 32);

			// execute callback, if specified
			if (message.hasOwnProperty('callback') && typeof message.callback === 'function') {
				message.callback(message);
			}

			if (message.sound) {
				// auto-play the sound when the message slide in
				SoundTry.playOnce(message.sound, delay * 2);
			}
		}

		/**
		 * Enqueues the message for being displayed once the
		 * current queue of messages is dispatched.
		 * In case the queue is empty, the message will be
		 * immediately displayed.
		 *
		 * @param 	mixed  message  The message to display or an object
		 * 							with the data to use.
		 *
		 * @return 	void
		 */
		static enqueue(message) {
			if (VAPToast.timerHandler == null) {
				// dispatch directly as there is no active messages
				VAPToast.dispatch(message);
				return;
			}

			// push the message within the queue
			VAPToast.queue.push(message);
		}

		/**
		 * Schedule the message to be enqueued at the specified date and time.
		 * 
		 * @param 	mixed  message  The message to display or an object
		 * 							with the data to use.
		 * 
		 * @return 	void
		 */
		static schedule(message) {
			if (message.datetime && !(message.datetime instanceof Date)) {
				// create date instance
				message.datetime = new Date(message.datetime);
			}

			if (!message.datetime || isNaN(message.datetime.getTime())) {
				// invalid date time
				throw 'ToastInvalidScheduleTime';
			}

			// calculate remaining seconds
			let ms = message.datetime.getTime() - new Date().getTime();

			if (ms > Math.pow(2, 31)) {
				// do not schedule notifications in case the difference is higher than the 32-bit INI limit,
				// which should be around 25 days
				return;
			}

			if (ms <= 0) {
				// immediately enqueue the message
				VAPToast.enqueue(message);
			} else {
				setTimeout(() => {
					// schedule the message
					VAPToast.enqueue(message);
				}, ms)
			}
		}

		/**
		 * Disposes the current visible message.
		 * Once a message is closed, it will pop the
		 * first message in the queue, if any, for
		 * being immediately displayed.
		 *
		 * @param 	boolean  force  True to force the closure.
		 * 
		 * @return 	void
		 */
		static dispose(force) {
			// do not dispose in case the mouse is above the toast
			if (!VAPToast.mouseHover || force) {
				// fade out the toast message
				$('#vap-toast-wrapper').removeClass('toast-slide-in').removeClass('do-shake');
				// reset handler
				clearTimeout(VAPToast.timerHandler);
				VAPToast.timerHandler = null;
				VAPToast.mouseHover = false;

				// check if the queue is not empty
				if (VAPToast.queue.length) {
					// wait some time before displaying the new message
					VAPToast.timerHandler = setTimeout(() => {
						// get first message added
						let message = VAPToast.queue.shift();

						// unset timer to avoid adding shake effect
						VAPToast.timerHandler = null;

						// dispatch the message
						VAPToast.dispatch(message);
					}, 1000);
				}
			}
		}
	}

	/**
	 * Toast positions constants.
	 */
	VAPToast.POSITION_TOP_LEFT      = 'top-left';
	VAPToast.POSITION_TOP_CENTER    = 'top-center';
	VAPToast.POSITION_TOP_RIGHT     = 'top-right';
	VAPToast.POSITION_BOTTOM_LEFT   = 'bottom-left';
	VAPToast.POSITION_BOTTOM_CENTER = 'bottom-center';
	VAPToast.POSITION_BOTTOM_RIGHT  = 'bottom-right';

	/**
	 * Toast status constants.
	 */
	VAPToast.ERROR_STATUS   = 0;
	VAPToast.SUCCESS_STATUS = 1;
	VAPToast.WARNING_STATUS = 2;
	VAPToast.NOTICE_STATUS  = 3;

	/**
	 * Environment variables.
	 */
	VAPToast.timerHandler    = null;
	VAPToast.mouseHover      = false;
	VAPToast.disposeDelay    = 0;
	VAPToast.queue 		     = [];
	VAPToast.defaultPosition = VAPToast.POSITION_BOTTOM_CENTER;

	/**
	 * Toast message decorator.
	 * 
	 * |--------------------------------|
	 * |      | Title                   |
	 * | Icon --------------------------|
	 * |      | Description             |
	 * |--------------------------------| 
	 * 
	 * In conjunction with the arguments supported by VAPToast.dispatch(), here's
	 * a list of properties that can be used to create a message template:
	 * 
	 * - icon   string|Image|null  An optional icon to display on the left side.
	 *                             In case of a string, the system will auto-detect if
	 *                             we are dealing with an image path or with a font icon.
	 * - title  string|null        An optional plain title for the message.
	 * - body   string|null        An optional HTML body text for the message.
	 * 
	 * How to create a new message decorator:
	 *
	 *   new VAPToastMessage({
	 *       title: 'Message title',
	 *       body: 'This is the body of the message',
	 *       icon: 'fas fa-bell',
	 *       // icon: '/path/to/image.png',
	 *       status: VAPToastMessage.NOTICE_STATUS, // (default)	
	 *       delay:  3500, // (default)
	 *       sound: '/path/to/audio.mp3',
	 *       action: () => { VAPToast.dispose(true); },
	 *       callback: (message) => { console.log('message displayed', message); },
	 *       style: { padding: '10px' },
	 *   });
	 */
	w['VAPToastMessage'] = class VAPToastMessage {
		/**
		 * Class constructor.
		 * 
		 * @param 	object  data  The message data.
		 */
		constructor(data) {
			if (typeof data !== 'object') {
				throw 'ToastMessageInvalidArgument';
			}

			// assign the specified properties to this class
			Object.assign(this, data);

			// text not specified, construct it
			if (!this.text) {
				// create message wrapper
				this.text = $('<div class="vap-pushnotif-wrapper"></div>');

				if (this.icon) {
					let icon;

					if (this.icon instanceof Image) {
						// we have an image instance
						icon = $(this.icon);
					} else if (typeof this.icon === 'string') {
						if (this.icon.indexOf('/') !== -1) {
							// we have an image URL
							icon = $('<img>').attr('src', this.icon);
						} else {
							// we probably have a font icon
							icon = $('<i></i>').addClass(this.icon);
						}
					}

					if (icon) {
						// append icon to message wrapper
						this.text.append($('<div class="push-notif-icon"></div>').append(icon));
					}
				}

				// create message inner box
				let inner = $('<div class="push-notif-text"></div>');

				if (this.title) {
					// append message title to message wrapper
					inner.append($('<div class="push-notif-title"></div>').text(this.title));
				}

				if (this.body) {
					// append message body to message wrapper
					inner.append($('<div class="push-notif-body"></div>').html(this.body));
				}

				// append inner message
				this.text.append(inner);
			}

			if (this.status === undefined) {
				// use default notice status
				this.status = VAPToast.NOTICE_STATUS;
			}

			if (this.delay === 'auto') {
				this.delay = {
					// use by default a tolerance of 2.5 seconds
					tolerance: 2500,
				};
			}

			if (this.delay !== null && typeof this.delay === 'object') {
				this.delay = this.fetchReadingTime(this.delay);
			}
		}

		/**
		 * Calculates the estimated reading time based on the specified title, body and configuration options.
		 * 
		 * @param 	object  opts  A registry of options.
		 *                        - tolerance  int   The milliseconds to add to the estimated time.
		 *                        - min        int   The reading time cannot be lower than this amount.
		 *                        - max        int   The reading time cannot be higher than this amount.
		 *                        - debug      bool  True to display some information within the console.
		 * 
		 * @return 	int     The reading time in milliseconds.
		 */
		fetchReadingTime(opts) {
			// build readable message
			let text = [
				this.title,
				this.body,
			].filter(str => str).join(' ');

			// split the words delimited by the punctuation
			let words = text.match(/[\s,.;:-]+.(?!$)/g);

			// count total number of words
			let wordsCount = words ? words.length + 1 : 0;

			// divide the words count by 200, a good compromise related to the
			// average reading rate (238 words per minute)
			let division = wordsCount / 200;

			// multiply the result by 60 to convert the resulting minutes in seconds
			let seconds = Math.floor(division) * 60;

			// take the decimal points and multiply that number by 0.60 to
			// obtain the remaining seconds
			seconds += (division % 1) * 0.6 * 100;

			if (opts.tolerance) {
				// convert milliseconds in seconds
				seconds += opts.tolerance / 1000;
			}

			// convert in milliseconds and get rid of decimals
			let ms =  Math.round(seconds * 1000);

			if (opts.min) {
				// cannot be lower than the specified amount
				ms = Math.max(opts.min, ms);
			}

			if (opts.max) {
				// cannot be higher than the specified amount
				ms = Math.min(opts.max, ms);
			}

			if (opts.debug) {
				// display debug info
				console.log('words count: ' + wordsCount);
				console.log('estimated reading time (seconds): ', seconds);
				console.log('fetched delay (milliseconds): ', ms);
			}

			return ms;
		}
	}

	/**
	 * Push notification decorator.
	 * 
	 * |--------------------------------|
	 * | Icon | Title            | date |
	 * |--------------------------------|
	 * | Description                    |
	 * |--------------------------------| 
	 * 
	 * In conjunction with the arguments supported by VAPToastMessage, here's
	 * a list of properties that can be used to create a message template:
	 * 
	 * - creation   string  An optional text used to display the creation date.
	 * 
	 * How to create a new message decorator:
	 *
	 *   new VAPToastMessageOS({
	 *       title: 'Message title',
	 *       body: 'This is the body of the message',
	 *       icon: 'fas fa-bell',
	 *       delay:  3500, // (default)
	 *       creation: 'just now',
	 *   });
	 */
	w['VAPToastMessageOS'] = class VAPToastMessageOS extends VAPToastMessage {
		/**
		 * Class constructor.
		 * 
		 * @param 	object  data  The message data.
		 */
		constructor(data) {
			// construct template through parent
			super(data);

			// use a custom status
			this.status = 'custom';

			if (typeof this.class !== 'string') {
				// initialize class attribute
				this.class = '';
			}

			// append custom class
			this.class = (this.class + ' push-notif').trim();

			if (typeof this.text !== 'string') {
				// find title in template
				let title = $(this.text).find('.push-notif-title');

				if (title.length) {
					// wrap title in a parent box
					title.wrap($('<div class="push-notif-header"></div>'));
				}

				// find icon in template
				let icon = $(this.text).find('.push-notif-icon');

				if (icon.length) {
					// move icon at the beginning of the header
					icon.detach().prependTo($(this.text).find('.push-notif-header'));
				}

				if (this.creation) {
					// append creation date to the header
					$(this.text).find('.push-notif-header')
						.append($('<small></small>').text(this.creation));
				}

				// extract body HTML
				let body = $(this.text).find('.push-notif-body').html();

				// wrap body in a container and append it to the text element
				$(this.text).find('.push-notif-body').html(
					$('<div class="push-notif-body-inner"></div>').html(body)
				);
			}
		}
	}

	/**
	 * Backward compatibility to the old class name.
	 * 
	 * @deprecated 1.8
	 */
	w['ToastMessage'] = w['VAPToast'];
	
})(jQuery, window);