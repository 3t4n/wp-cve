/*
 * CURRENCY
 */

function Currency(symbol, options) {
	if (!arguments.callee.instance || !arguments.callee.instance.symbol) {

		if (options === undefined) {
			options = {};
		}

		this.symbol    = symbol;
		this.position  = (options.hasOwnProperty('position')  ? options.position  : 1);
		this.decimals  = (options.hasOwnProperty('separator') ? options.separator : '.');
		this.thousands = (options.hasOwnProperty('thousands') ? options.thousands : ',');
		this.digits    = (options.hasOwnProperty('digits') ? parseInt(options.digits) : 2);

		arguments.callee.instance = this;
	}
	
	return arguments.callee.instance;
}

Currency.getInstance = function(symbol, options) {
	return new Currency(symbol, options);
};

Currency.prototype.format = function(price, dig) {
	if (dig === undefined) {
		dig = this.digits;
	}

	price = parseFloat(price);

	// check whether the price is negative
	const isNegative = price < 0;

	// adjust to given decimals
	price = Math.abs(price).toFixed(dig);

	var _d = this.decimals;
	var _t = this.thousands;

	// make sure the decimal separator is a valid character
	if (!_d.match(/[.,\s]/)) {
		// revert to default one
		_d = '.';
	}

	// make sure the thousands separator is a valid character
	if (!_t.match(/[.,\s]/)) {
		// revert to default one
		_t = ',';
	}

	// make sure both the separators are not equals
	if (_d == _t) {
		_t = _d == ',' ? '.' : ',';
	}

	price = price.split('.');

	price[0] = price[0].replace(/./g, function(c, i, a) {
		return i > 0 && (a.length - i) % 3 === 0 ? _t + c : c;
	});

	if (isNegative) {
		// re-add negative sign
		price[0] = '-' + price[0];
	}

	if (price.length > 1) {
		price = price[0] + _d + price[1];
	} else {
		price = price[0];
	}

	if (Math.abs(this.position) == 1) {
		// do not use space in case the position is "-1"
		return price + (this.position == 1 ? ' ' : '') + this.symbol;
	}

	// do not use space in case the position is "-2"
	return this.symbol + (this.position == 2 ? ' ' : '') + price;
}

Currency.prototype.sum = function(a, b) {
	// get rid of decimals for higher precision
	a *= Math.pow(10, this.digits);
	b *= Math.pow(10, this.digits);

	// do sum and go back to decimal
	return (Math.round(a) + Math.round(b)) / Math.pow(10, this.digits);
}

Currency.prototype.diff = function(a, b) {
	// get rid of decimals for higher precision
	a *= Math.pow(10, this.digits);
	b *= Math.pow(10, this.digits);

	// do difference and go back to decimal
	return (Math.round(a) - Math.round(b)) / Math.pow(10, this.digits);
}

Currency.prototype.multiply = function(a, b) {
	// get rid of decimals for higher precision
	a *= Math.pow(10, this.digits);
	b *= Math.pow(10, this.digits);

	// do multiplication and go back to decimal
	return (Math.round(a) * Math.round(b)) / Math.pow(10, this.digits * 2);
}

/*
 * TIME
 */

function getDateFromFormat(value, format, object) {
	if (!value) {
		return null;
	}

	// second char of format can be only [/.-]
	let separator = format.charAt(1);

	let formatChunks = format.split(separator);
	let dateChunks   = value.split(separator);
	
	if (formatChunks.length != dateChunks.length || formatChunks.length != 3)
	{
		// invalid date
		return null;
	}
	
	// create lookup to easily access the date components
	let lookup = {};

	for (let i = 0; i < formatChunks.length; i++)
	{
		let k = formatChunks[i].toLowerCase();

		lookup[k] = dateChunks[i];
	}

	// rebuild date by using the military format
	let date = lookup.y + '-' + lookup.m + '-' + lookup.d;

	if (object === false) {
		// return only the date string
		return date;
	}
	
	// instantiate date
	return new Date(date);
}

function getFormattedTime(hour, min, format, tz) {
	if (typeof format !== 'string') {
		format = 'H:i';
	}

	// use by default HH:ii format (24H)
	const options = {
		hour:   '2-digit',
		minute: '2-digit',
		hour12: false,
	};

	if (format.match(/^[Gg]/)) {
		// display hours as a number
		options.hour = 'numeric';
	}

	if (format.match(/A$/)) {
		// use AM/PM notation
		options.hour12 = true;
	}

	if (tz && typeof tz === 'string') {
		// display time according to the specified timezone
		options.timeZone = tz;
	}

	// create date time
	let dt = new Date();
	dt.setHours(hour);
	dt.setMinutes(min);

	// format time
	return dt.toLocaleTimeString([], options);
}

/*
 * EMAIL
 */

function isEmailCompliant(email) {
	if (typeof email !== 'string') {
		// the input field was passed, get specified e-mail
		var tmp = jQuery(email).val();
		// trim the e-mail address
		tmp = tmp.trim();
		// Update the input field.
		// Use the attr method in order force the update,
		// because val won't do anything as the passed value
		// will result equals to the previous one.
		jQuery(email).attr('value', tmp);

		// keep only the e-mail
		email = tmp;
	}

	// define regex for e-mail validation
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

	// make sure the e-mail is compliant
	return re.test(email);
}

/*
 * FORM VALIDATION
 */

function VikFormValidator(form, clazz) {
	this.form = form;

	if (typeof clazz === 'undefined') {
		clazz = 'invalid';
	}

	this.clazz     = clazz;
	this.labels    = {};
	this.callbacks = [];

	// prevent the form submission on enter keydown

	jQuery(this.form).on('keyup', function(e) {
		var keyCode = e.keyCode || e.which;
		
		if (keyCode === 13) { 
			e.preventDefault();
			return false;
		}
	});

	this.registerFields('.required');
}

VikFormValidator.prototype.isValid = function(input) {
	if (jQuery(input).is(':checkbox')) {
		// make sure the checkbox if selected
		return jQuery(input).is(':checked') ? true : false;
	}

	var val = jQuery(input).val();

	if (val === null || val.length == 0) {
		return false;
	}

	// if we have an e-mail field, make sure the address is valid
	if (jQuery(input).attr('type') === 'email' && !isEmailCompliant(input)) {
		return false;
	}

	return true;
}

VikFormValidator.prototype.registerFields = function(selector) {

	var _this = this;

	jQuery(this.form).find(selector).each(function() {
		if (!jQuery(this).hasClass('required')) {
			jQuery(this).addClass('required');
		}

		jQuery(this).on('blur', function() {
			if (_this.isValid(this)) {
				_this.unsetInvalid(this);
			} else {
				_this.setInvalid(this);
			}
		});
	});

	return this;
}

VikFormValidator.prototype.unregisterFields = function(selector) {
	var _this = this;

	jQuery(this.form).find(selector).each(function() {
		if (jQuery(this).hasClass('required')) {
			jQuery(this).removeClass('required');
		}

		// unset invalid class from deregistered fields
		_this.unsetInvalid(this);

		jQuery(this).off('blur');
	});

	return this;
}

VikFormValidator.prototype.validate = function(callback) {
	var ok = true;

	var _this = this;

	this.clearInvalidTabPane();

	jQuery(this.form).find('.required').filter('input,select,textarea,checkbox').each(function() {
		if (_this.isValid(this)) {
			_this.unsetInvalid(this);
		} else {
			_this.setInvalid(this);
			ok = false;

			if (!jQuery(this).is(':visible')) {
				// the input is probably hidden behind
				// an unactive tab pane
				_this.setInvalidTabPane(this);
			}
		}
	});

	// execute registered callbacks
	for (var i = 0; i < this.callbacks.length; i++) {
		ok = this.callbacks[i](this) && ok;
	}

	// execute specified validation
	if (typeof callback !== 'undefined') {
		ok = callback(this) && ok;
	}

	return ok;
}

VikFormValidator.prototype.setLabel = function(input, label) {
	this.labels[jQuery(input).attr('name')] = label;

	return this;
}

VikFormValidator.prototype.getLabel = function(input) {
	var name = jQuery(input).attr('name');	

	if (this.labels.hasOwnProperty(name)) {
		return jQuery(this.labels[name]);
	}

	return jQuery(input).closest('.controls').prev().find('b,label');
}

VikFormValidator.prototype.setInvalid = function(input) {
	if (jQuery(input).is('input,textarea')) {
		// do not add "invalid" class to select
		jQuery(input).addClass(this.clazz);
	}

	this.getLabel(input).addClass(this.clazz);

	return this;
}

VikFormValidator.prototype.unsetInvalid = function(input) {
	jQuery(input).removeClass(this.clazz);
	this.getLabel(input).removeClass(this.clazz);

	return this;
}

VikFormValidator.prototype.isInvalid = function(input) {
	return jQuery(input).hasClass(this.clazz);
}

VikFormValidator.prototype.clearInvalidTabPane = function() {
	jQuery('ul.nav-tabs li a').removeClass(this.clazz);

	return this;
}

VikFormValidator.prototype.setInvalidTabPane = function(input) {
	var pane = jQuery(input).closest('.tab-pane');

	if (pane.length) {
		var id 	 = jQuery(pane).attr('id');
		var link = jQuery('ul.nav-tabs li a[href="#' + id + '"]');

		if (link.length) {
			link.addClass(this.clazz);
		}
	}

	return this;
}

VikFormValidator.prototype.addCallback = function(callback) {
	if (typeof callback === 'function') {
		this.callbacks.push(callback);
	}

	return this;
}

VikFormValidator.prototype.removeCallback = function(callback) {
	for (var i = 0; i < this.callbacks.length; i++) {
		if (this.callbacks[i] === callback) {
			this.callbacks.splice(i, 1);

			return true;
		}
	}

	return false;
}

/*
 * FORM OBSERVER
 */

function VikFormObserver(form, types, skip) {

	if (typeof types === 'undefined') {
		types = ['hidden', 'input', 'textarea', 'select', 'file'];
	}

	if (typeof skip === 'undefined') {
		skip = [];
	}

	// always exclude select2 search input
	skip.push('[id^="s2id_autogen"]');

	this.form     = form;
	this.types    = types;
	this.skipList = skip;
	this.custom   = {};
	this.cache    = {};
	this.force    = 0;
	this.debug    = false;
}

VikFormObserver.prototype.freeze = function() {
	this.cache = this.map();

	this.force = 0;

	return this;
}

VikFormObserver.prototype.isChanged = function() {
	if (this.force == 1) {
		return true;
	} else if (this.force == -1) {
		return false
	}

	var map = this.map();

	var keys1 = Object.keys(this.cache);
	var keys2 = Object.keys(map);

	if (keys1.length != keys2.length) {
		return true;
	}

	for (var i = 0; i < keys1.length; i++) {
		if (!map.hasOwnProperty(keys1[i])) {
			if (this.debug) {
				console.warn('missing property', keys1[i]);
			}
			return true;
		}

		let v1 = this.cache[keys1[i]];
		let v2 = map[keys1[i]];

		if (Array.isArray(v1) || Array.isArray(v2)) {
			v1 = JSON.stringify(v1);
			v2 = JSON.stringify(v2);
		}

		if (v1 != v2) {
			if (this.debug) {
				console.warn(keys1[i], v1, v2);
			}
			return true;
		}
	}

	return false;
}

VikFormObserver.prototype.changed = function() {
	this.force = 1;

	return this;
}

VikFormObserver.prototype.unchanged = function() {
	this.force = -1;

	return this;
}

VikFormObserver.prototype.map = function() {

	var map = {};

	var _this = this;

	jQuery(this.form)
		.find(this.types.join(', '))
		.not(this.skipList.join(', '))
		.each(function() {

		var key = jQuery(this).attr('name') || jQuery(this).attr('id');

		// stop observing the fields without a name/ID
		if (key) {
			if (_this.custom.hasOwnProperty(key))
			{
				map[key] = _this.custom[key]();
			}
			else if (jQuery(this).is(':checkbox'))
			{
				map[key] = jQuery(this).is(':checked');
			}
			else if (Joomla.editors && Joomla.editors.instances[key])
			{
				map[key] = Joomla.editors.instances[key].getValue();
			}
			else
			{
				map[key] = jQuery(this).val();
			}
		}
	});

	return map;
}

VikFormObserver.prototype.exclude = function(selector) {
	this.skipList.push(selector);

	return this;
}

VikFormObserver.prototype.push = function(selector) {
	this.types.push(selector);

	return this;
}

VikFormObserver.prototype.setCustom = function(selector, handler) {
	this.custom[jQuery(selector).attr('name')] = handler;

	return this;
}

/*
 * RENDERER
 */

function VikRenderer() {
	return this;
}

/**
 * Renders the specified select by using the Chosen jQuery plugin.
 *
 * @param 	string  selector  Either the container of the selects or the select tag itself.
 * @param 	string  width     An optional width to be applied to the select.
 * @param 	mixed   options   An object of options to be passed when initializing CHZN.
 *
 * @return 	void
 */
VikRenderer.chosen = function(selector, width, options) {
	var chzn;

	if (typeof options !== 'object') {
		// use empty options
		options = {};
	}

	// check if the specified selector is a select itself
	if (jQuery(selector).is('select')) {
		// render select with chosen plugin
		jQuery(selector).chosen(options);

		// find chzn next to select
		chzn = jQuery(selector).next('div.chzn-container');

		if (chzn.length == 0) {
			// No chosen, we are probably under WordPress...
			// So, lets try retrieving previous select2 container.
			chzn = jQuery(selector).prev('.select2-container');
		}
	} else {
		// render all select under the specified selector with chosen plugin
		jQuery(selector).find('select').chosen(options);

		// find chzn under selector
		chzn = jQuery(selector).find('div.chzn-container');

		if (chzn.length == 0) {
			// No chosen, we are probably under WordPress...
			// So, lets try retrieving all select2 containers.
			chzn = jQuery(selector).find('.select2-container');
		}
	}

	if (!width) {
		width = '200px';
	}

	// copy select classes into the chosen wrapper
	chzn.each(function() {
		// auto set default width
		jQuery(this).css('width', width);

		if (width == 'auto' && jQuery(this).hasClass('select2-container')) {
			// add a minimum width in order to avoid a small dropdown when
			// the multiple attribute is set (WP only)
			jQuery(this).css('min-width', '200px');
		}
		
		var select = jQuery(this).prev();

		jQuery(this).addClass(select.attr('class'));
	});
}

/**
 * Checks if the given box is currently visible within the monitor.
 *
 * @param 	object 	 box 	 The element to check.
 * @param 	integer  margin  An additional margin to use in order to ignore fixed elements.
 * @param 	integer  height  An optional box height to use. If not specified, the default
 *                           height of the box will be retrieved.
 *
 * @return 	integer  The pixels to scroll if the box is not visible, otherwise false.
 */
function isBoxOutOfMonitor(box, margin, height) {
	var box_y         = box.offset().top;
	var scroll        = jQuery(window).scrollTop();
	var screen_height = jQuery(window).height();
	var box_height    = height ? height : box.height();

	// check whether the height of the box exceeds 
	// the total height of the window
	if (box_height > screen_height) {
		// use a third of the screen height as reference
		box_height = screen_height / 3;
	}

	if (margin === undefined) {
		margin = 0;
	}

	// check if we should scroll down
	if (box_y - scroll + box_height + margin > screen_height) {
		return box_y - scroll + margin + box.height() - screen_height;
	}

	// check if we should scroll up
	if (scroll - margin > box_y + box_height) {
		return box_y - scroll - margin;
	}
	
	// the box is visible
	return false;
}

/**
 * Custom confirmation dialog.
 *
 * @since 1.6
 */
class VikConfirmDialog {

	/**
	 * Class constructor.
	 *
	 * @param 	string 	message  The dialog body text/HTML.
	 * @param 	string 	id       The dialog unique ID (optional).
	 * @param 	string  class    A dialog additional class (optional).
	 */
	constructor(message, id, clazz) {
		if (id === undefined) {
			// use a default ID if not specified
			id = 'vik-confirm-dialog';

			var cont = 1;
			var tmp  = id;
			// add suffix and repeat as long as the ID already exists
			while (jQuery('#' + id).length) {
				id = tmp + '-' + cont;
				cont++;
			}
		}

		if (clazz === undefined) {
			clazz = '';
		}

		// check if the message is a DOM element
		if (typeof message === 'string' && message.match(/^[#.]/)) {
			try {
				if (jQuery(message).length) {
					// extract HTML from specified DOM element
					var tmp = jQuery(message).html();
					// unset HTML from original element
					jQuery(message).remove();
					// update message parameter
					message = tmp;
				}
			} catch (err) {
				// invalid selector, suppress error
			}
		}

		this.message = message;
		this.buttons = [];
		this.id 	 = id;
		this.clazz   = 'vik-confirm-dialog' + (clazz.length ? ' ' + clazz : '');
		this.built   = false;
		this.args 	 = null;
	}

	/**
	 * Updates the dialog message.
	 *
	 * @param 	string 	message  The dialog text/HTML.
	 *
	 * @return 	self
	 */
	setMessage(message) {
		this.message = message;

		// check if the dialog was already built
		if (this.built) {
			// destroy the dialog and re-create it in order
			// to use the new HTML message
			this.refresh();
		}

		return this;
	}

	/**
	 * Adds a button to the dialog.
	 *
	 * @param 	string    text      The button text.
	 * @param 	function  callback  The function to invoke when the button is clicked.
	 * @param 	boolean   dispose   False to avoid closing the dialog after clicking the
	 * 								button (Optional). If not specified, true by default.
	 * @param 	boolean   queue     True to push the button at the beginning of the 
	 * 								list (optional). If not specified, false by default.
	 *
	 * @return 	self
	 */
	addButton(text, callback, dispose, head) {
		if (dispose === undefined) {
			dispose = true;
		}

		var btn = {
			text:     text,
			callback: callback,
			dispose:  dispose,
		};

		// Push a button in the list.
		// The first button will be considered as primary.
		if (head) {
			// push as first
			this.buttons.unshift(btn);
		} else {
			// push as last
			this.buttons.push(btn);
		}

		// check if the dialog was already built
		if (this.built) {
			// destroy the dialog and re-create it in order
			// to support the new button
			this.refresh();
		}

		return this;
	}

	/**
	 * Gets the requested button.
	 *
	 * @param 	mixed  Either the button text or its position
	 * 				   in the list.
	 *
	 * @return 	mixed  The button object if exists, false otherwise.
	 * 				   Returns the button position in case the
	 * 				   button instance is passed.
	 */
	getButton(id) {
		// check if the we have a button matching the specified text
		for (var i = 0; i < this.buttons.length; i++) {
			// check if the argument matches the button object
			if (this.buttons[i] === id) {
				// return button position
				return i;
			}
			// check if the argument matches the button text
			else if (this.buttons[i].text === id) {
				return this.buttons[i];
			}
		}

		if (typeof id === 'number' || id.match(/^[\d]+$/)) {
			// a number was used, try to return the
			// button at the specified position
			if (this.buttons[id]) {
				return this.buttons[id];
			}
		}

		return false;
	}

	/**
	 * Make specified button as default.
	 *
	 * @param 	object 	 btn  The button to make primary.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	makeDefault(btn) {
		if (typeof btn !== 'object') {
			return false;
		}

		// get button position
		var index = this.getButton(btn);

		if (typeof index !== 'number' || index === 0) {
			// we got a non-numeric value or the button
			// is already at the initial position
			return false;
		}

		// remove button
		this.buttons.splice(index, 1);

		// re-push button as first (4th argument)
		this.addButton(btn.text, btn.callback, btn.dispose, true);

		return true;
	}

	/**
	 * Build the HTML of the dialog only if it hasn't been created yet.
	 *
	 * @return 	self
	 */
	build() {
		// build HTML only once
		if (!this.built) {
			var html = '';

			// open dialog body
			html += '<div id="' + this.id + '" class="' + this.clazz + '">\n';

			// set dialog message
			html += '<div class="vik-confirm-message">' + this.message + '</div>\n';

			// open buttons block
			html += '<div class="vik-confirm-buttons">\n';

			if (this.buttons.length == 0) {
				// no specified buttons, create default one
				this.addButton('Ok');	
			}

			// create buttons
			for (var i = 0; i < this.buttons.length; i++) {
				html += '<a data-index="' + i + '">' + this.buttons[i].text + '</a>\n';
			}

			// close buttons block
			html += '</div>\n';

			// close dialog body
			html += '</div>\n';

			// append overlay and dialog to the document
			jQuery('body').append('<div class="vik-confirm-overlay">' + html + '</div>');

			var _this = this;

			// assign an event to the dialog buttons
			jQuery('#' + this.id)
				.find('.vik-confirm-buttons a')
					.on('click', function(event) {
						// execute event when clicked
						_this.triggerEvent(this, event);
					});

			this.built = true;
		}

		return this;
	}

	/**
	 * Refreshes the HTML of the dialog.
	 * Useful to support any changes that are made when 
	 * the dialog was already built.
	 *
	 * NOTE: do not act if the dialog is visible.
	 *
	 * @return 	self
	 */
	refresh() {
		// make sure the dialog is not open
		if (!this.isOpen()) {
			// unset build flag
			this.built = false;
			// remove dialog from document
			jQuery('#' + this.id).parent().remove();
			
			// re-building will be made before showing the dialog
		}

		return this;
	}

	/**
	 * Triggers the callback assigned to the button that
	 * has been clicked. If specified, the dialog will be
	 * closed after the callback execution.
	 *
	 * @param 	mixed 	 btn    The clicked button.
	 * @param 	Event    event  The unleashed event.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	triggerEvent(btn, event) {
		// find clicked button
		var button = this.buttons[parseInt(jQuery(btn).data('index'))];

		if (!button) {
			// button not found
			return false;
		}

		// trigger event if owns a callback
		if (button.callback) {
			// pass the arguments that was set when showing the dialog
			button.callback(this.args, event);
		}

		// dispose dialog if needed
		if (button.dispose) {
			this.dispose();
		}

		return true;
	}

	/**
	 * Shows the dialog.
	 * Builds the HTML in case it wasn't yet rendered.
	 * Registers the shortcuts to handle ENTER and ESC keys.
	 *
	 * @param 	mixed 	args     The arguments to pass to the callback
	 * 						     when a button is clicked.
	 * @param 	object  options  An object of options.
	 *
	 * @return 	self
	 */
	show(args, options) {
		// build dialog if missing
		this.build();

		// register specified arguments for later use (trigger event)
		this.args = args;

		// create default options object
		options = jQuery.extend({
			/**
			 * Flag used to allow the user to dismiss the confirmation
			 * dialog by pressing ESC from the keyboard.
			 *
			 * @param 	boolean  True by default.
			 */
			esc: true,
			/**
			 * Flag used to allow the user to auto-submit the confirmation
			 * dialog by pressing ENTER from the keyboard.
			 *
			 * @param 	boolean  True by default.
			 */
			submit: true,
		}, options);

		// register KEY shortcuts before showing the dialog (inject dialog instance within event)
		jQuery(window).on('keydown', {dialog: this, options: options}, VikConfirmDialog.handleShortcut);

		// lock page scroll as long as the dialog is open
		jQuery('body').css('overflow', 'hidden');

		// trigger event before displaying the dialog
		jQuery('#' + this.id).trigger('beforeshow');

		// display dialog
		jQuery('#' + this.id).parent().show();

		// trigger event after displaying the dialog
		jQuery('#' + this.id).trigger('aftershow');

		return this;
	}

	/**
	 * Closes the dialog.
	 * Turns off the shortcuts that was used to handle keyboard events.
	 *
	 * @return 	self
	 */
	dispose() {
		// deregister KEY shortcuts before hiding the dialog
		jQuery(window).off('keydown', VikConfirmDialog.handleShortcut);

		// re-enable page scroll
		jQuery('body').css('overflow', 'auto');

		// hide dialog
		jQuery('#' + this.id).parent().hide();

		// trigger event after displaying the dialog
		jQuery('#' + this.id).trigger('dismiss');

		return this;
	}

	/**
	 * Checks whether the dialog is currently visible.
	 *
	 * @return 	boolean  True if visible, false otherwise.
	 */
	isOpen() {
		// check if the dialog is visible (open)
		return jQuery('#' + this.id).parent().is(':visible');
	}

	/**
	 * Initialises a keyboard event to catch ENTER and ESC
	 * when pressed. The ENTER key simulates a click to the
	 * primary button (the first added). The ESC key disposes
	 * the dialog without executing any events.
	 *
	 * @param 	Event  event  The keyboard event.
	 *
	 * @return 	false
	 */
	static handleShortcut(event) {
		// retrieve dialog instance from event data
		var dialog = event.data.dialog;
		// retrieve options from event data
		var options = event.data.options;

		// check for ENTER
		if (event.keyCode == 13) {
			// make sure auto-submit is allowed
			if (options.submit) {
				// find first button in dialog and simulate a 'click'
				jQuery('#' + dialog.id)
					.find('.vik-confirm-buttons a')
						.first()
							.trigger('click');
			}
		}
		// check for ESC
		else if (event.keyCode == 27) {
			// make sure ESC is allowed
			if (options.esc) {
				// hide dialog
				dialog.dispose();
			}
		}
	}
}

/*
 * Geolocation helper class.
 *
 * @since 1.7
 */
class VikGeo {
	
	/**
	 * Promise used to obtain the user coordinates.
	 *
	 * When it resolves, an object containing
	 * the latitude ("lat") and longitude ("lng")
	 * is returned.
	 *
	 * In case of rejection, an error code is returned.
	 *
	 * @param 	string   name  The cookie name. If not specified,
	 *                         the default one will be used.
	 *
	 * @return 	Promise
	 */
	static getCurrentPosition(name) {
		if (!name) {
			name = VikGeo.cookieName;
		}

		// create promise
		return new Promise((resolve, reject) => {
			// create base regex
			var regex = "(?:^|;)\\s*%s\\s*=\\s*([^;]*)(?:;|$)";
			// insert cookie name within regex (escape dots)
			regex = regex.replace(/%s/, name.replace(/\./g, '\\.'));
			// create pattern
			regex = new RegExp(regex, 'i');

			// check whether the cookie string contains our cookie
			var match = document.cookie.match(regex);

			if (match && match.length) {
				// split coordinates
				var coord = match[1].split(/,\s*/);

				coord = {
					lat: parseFloat(coord[0]),
					lng: parseFloat(coord[1]),
				};

				resolve(coord);
				return true;
			}

			// missing coordinates, we need to obtain them
			if (!navigator.geolocation) {
				// browser doesn't support geolocation
				reject({code: 0, message: 'Geolocation not supported'});
				return false;
			}

			// ask the user to retrieve the position
			navigator.geolocation.getCurrentPosition(function(position) {
				// create coordinates
				var coord = {
					lat: position.coords.latitude,
					lng: position.coords.longitude,
				};

				// store coordinates in a cookie for 1 week
				var date = new Date();
				date.setDate(date.getDate() + 7);

				document.cookie = name + '=' + coord.lat + ',' + coord.lng + '; expires=' + date.toUTCString() + '; path=/';

				resolve(coord);
			}, function(err) {
				// create config to make a GET request
				var url = {
					url:  'https://ipinfo.io/geo',
					type: 'get',
				};

				// retrieve position by using IPINFO service as fallback
				UIAjax.do(url, null, 
					function(resp) {
						if (!resp.loc) {
							// missing coordinates, reject with navigator error
							reject(err);
							return false;
						}

						// split coordinates
						var coord = resp.loc.split(/,\s*/);

						coord = {
							lat: parseFloat(coord[0]),
							lng: parseFloat(coord[1]),
						};

						// store coordinates in a cookie for 1 week
						var date = new Date();
						date.setDate(date.getDate() + 7);

						document.cookie = name + '=' + coord.lat + ',' + coord.lng + '; expires=' + date.toUTCString() + '; path=/';

						// complete process
						resolve(coord);
					}, function(failure) {
						// unable to retrieve the position
						reject(err);
					}
				);
			});
		});
	}

	/**
	 * Elaborates the data contained within the place instance
	 * and returns an object with details following the customer
	 * standards.
	 *
	 * @param 	object 	place  An object returned by Autocomplete.getPlace().
	 *
	 * @return 	object  An object containing a customer address.
	 */
	static extractDataFromPlace(place) {
		if (!place || !place.address_components) {
			// nothing to fetch
			return false;
		}

		var lookup = {};
		var data   = {};

		for (var i = 0; i < place.address_components.length; i++) {
			var comp = place.address_components[i];

			switch (comp.types[0]) {
				// cities
				case 'sublocality_level_1':
				case 'administrative_area_level_3':
				case 'locality':
				// state
				case 'administrative_area_level_2':
				case 'administrative_area_level_1':
				// country
				case 'country':
				// post code
				case 'postal_code':
				// street name
				case 'route':
				// street number
				case 'street_number':
				case 'premise':
					lookup[comp.types[0]] = comp.short_name;
					break;
			}
		}

		// fetch country
		if (lookup.country) {
			data.country = lookup.country;
		}

		// fetch components depending on country
		if (data.country == 'US') {
			// extract state from "administrative_area_level_1" for US
			data.state = lookup.administrative_area_level_1;
			// extract city from "locality" for US
			data.city  = lookup.locality;
		} else {
			// otherwise extract state from "administrative_area_level_2"
			data.state = lookup.administrative_area_level_2;
			// otherwise extract city from "administrative_area_level_3"
			data.city  = lookup.administrative_area_level_3 || lookup.locality;
		}

		// set post code
		data.zip = lookup.postal_code;

		// clean address field by leaving only the street name and number
		data.address = place.name;

		// fetch street data
		data.street = {
			name:   lookup.route,
			number: lookup.street_number || lookup.premise,
		};

		// fill latitude and longitude
		if (place.geometry) {
			data.lat = place.geometry.location.lat();
			data.lng = place.geometry.location.lng();
		}

		return data;
	}
}

VikGeo.cookieName = 'vik.position.coord';

/**
 * Google Maps events handler class.
 * Useful to handle errors triggered by GM.
 */
class VikMapsFailure {

	/**
	 * Turns off the Places/Autocomplete input field.
	 *
	 * @param 	mixed  input         The input selector to which the autocomplete is attached.
	 * @param 	mixed  autocomplete  The autocomplete instance returned by Google.
	 *
	 * @return 	void
	 */
	static disableAutocomplete(input, autocomplete) {
		// remove all attached events from input
		autocomplete.unbindAll();
		google.maps.event.clearInstanceListeners(input);
		jQuery('.pac-container').remove();

		/**
		 * Manually turn off all the  changes applied by google:
		 * - disabled
		 * - class
		 * - style (background)
		 * - placeholder
		 *
		 * We cannot replace the input because of unexpected behaviors
		 * that could be caused by any other attached events.
		 */
		jQuery(input)
			.css('background', 'none')
			.attr('placeholder', '')
			.prop('disabled', false)
			.removeClass('gm-err-autocomplete');
	}

	/**
	 * Listens the errors printed within the console to catch all the following
	 * errors triggered by Google API:
	 * - ApiNotActivatedMapError
	 *
	 * In case of detected errors, an event will be triggered.
	 *
	 * @return 	void
	 */
	static listenConsole() {
		// keep original "error" method
		var _error = console.error;

		// override "error" console method
		console.error = function() {
			// make sure the message is set
			if (arguments[0] && typeof arguments[0] === 'string') {
				// look for "ApiNotActivatedMapError" error and extract the related API Lib
				var match = arguments[0].match(/([a-zA-Z]+) API error: ApiNotActivatedMapError/);

				if (match) {
					// trigger error event
					jQuery(window).trigger('google.apidisabled.' + match[1].toLowerCase());
				}
			}

			if (_error.apply) {
				// do this for normal browsers
				_error.apply(console, arguments);
			} else {
				// IE backward compatibility
				var message = Array.prototype.slice.apply(arguments).join(' ')
				_error(message);
			}
		}
	}

	/**
	 * Returns true in case something went wrong with
	 * the authentication of the API Key.
	 *
	 * @return 	boolean  True in case the user is not authorised to use Google API services.
	 */
	static hasError() {
		return VikMapsFailure.error === true;
	}
}

/**
 * Google Maps Authentication Error.
 */
function gm_authFailure() {
	// register failure error
	VikMapsFailure.error = true;

	// inform all subscribers that the authentication with
	// Google Maps APIs failed
	jQuery(window).trigger('google.autherror');

	return false;
};

/**
 * Helper class used to play sounds.
 */
class SoundTry {
	/**
	 * Tries to play a sound.
	 * In case of success, it will be played
	 * only once within the specified milliseconds.
	 *
	 * @param 	string 	 src        The path of the audio to play.
	 * @param 	integer  threshold  The milliseconds in which the
	 * 								audio cannot be played again,
	 * 								since the last time is was played.
	 *
	 * @return 	mixed 	 The audio element on success, false otherwise.
	 */
	static playOnce(src, threshold) {
		let play = true;

		if (threshold) {
			// create pool of played sounds if undefined
			if (typeof SoundTry.pool === 'undefined') {
				SoundTry.pool = {};
			}

			// check if the audio is still in the pool
			if (SoundTry.pool.hasOwnProperty(src)) {
				// audio already played, don't play it again
				play = false;

				// reset current timer
				clearTimeout(SoundTry.pool[src]);
			}
			
			// mark sound as played
			SoundTry.pool[src] = setTimeout(function() {
				// auto-delete sound from pool on time expiration
				delete SoundTry.pool[src];
			}, Math.abs(threshold));
		}

		if (play) {
			// create audio element and auto-play
			return new Audio(src).play();
		}

		return null;
	}

	/**
	 * Tries to play a sound.
	 * In case the browser denies the action, a popup
	 * is displayed in order to inform the user that
	 * audio auto-play must be enabled from the 
	 * configuration of the browser.
	 *
	 * @param 	string 	src  The path of the audio to play.
	 *
	 * @return 	Audio   The audio element.
	 *
	 * @link	https://developer.mozilla.org/en-US/docs/Web/API/HTMLAudioElement
	 */
	static play(src) {
		// create audio element
		var audio = new Audio(src);

		// try to play the audio
		audio.play().catch((error) => {
			// make sure local storage is enabled in order
			// to avoid spamming the users every time an
			// error is faced
			if (typeof localStorage === 'undefined') {
				// Unable to cache whether the user was
				// already informed. Better to fail silently...
				return;
			}

			// check if the user was already informed
			if (localStorage.getItem('tryPlaySound.warned')) {
				// user already informed, do not proceed again
				return;
			}

			// Unable to play the audio.
			// Alert the user with the returned error.
			alert(error);

			// keep track that the user was already informed
			localStorage.setItem('tryPlaySound.warned', true);
		});

		// return audio element to let the caller use the sound
		return audio;
	}
}

/**
 * Helper class used to temporarily cache some data
 * for the whole page life.
 */
class VAPTempCache {
	/**
	 * Checks whether there's a cache for the specified signature.
	 *
	 * @param 	mixed 	key  The signature.
	 *
	 * @return 	mixed   The cached data or null.
	 */
	static get(key) {
		// empty cache
		if (typeof VAPTempCache.pool === 'undefined') {
			return null;
		}

		// create cache signature
		const sign = VAPTempCache.createSignature(key);

		if (!VAPTempCache.hasOwnProperty(sign)) {
			// cache not found
			return null;
		}

		// return cached data
		return VAPTempCache[sign];
	}

	/**
	 * Registers the specified data within the cache.
	 *
	 * @param 	mixed 	key   The signature.
	 * @param 	mixed   data  The data to cache.
	 *
	 * @return 	void
	 */
	static set(key, data) {
		// init cache if undefined
		if (typeof VAPTempCache.pool === 'undefined') {
			VAPTempCache.pool = {};
		}

		// create cache signature
		const sign = VAPTempCache.createSignature(key);

		// register data
		VAPTempCache[sign] = data;
	}

	/**
	 * Creates a normalized signature for the cache.
	 *
	 * @param 	mixed 	key   The signature.
	 *
	 * @return 	string
	 */
	static createSignature(key) {
		if (Array.isArray(key)) {
			return key.join(':');
		}

		if (typeof key === 'object') {
			return JSON.stringify(key);
		}

		return key;
	}
}

/**
 * Returns a promise that resolves when the height of the document
 * seems to be stabilized.
 *
 * @return 	Promise
 */
function onDocumentReady() {
	return new Promise((resolve) => {
		// register initial height
		var height = parseInt(jQuery('body').height());

		// prepare safe counter
		var count = 0;

		var callback = function() {
			// get new height
			var tmp = parseInt(jQuery('body').height());

			count++;

			if (tmp == height || count > 10) {
				// document ready
				resolve();
			} else {
				// register new height
				height = tmp;
				// check again
				setTimeout(callback, 32 + Math.floor(Math.random() * 128));
			}
		};

		// check
		setTimeout(callback, 32 + Math.floor(Math.random() * 128));
	});
}

/**
 * Returns a promise that resolves when the specified instance
 * gets defined.
 *
 * @param 	function  check      The callback to invoke to check whether the instance is ready.
 * @param   mixed     threshold  An optional threshold to establish the max number of attempts.
 *
 * @return 	Promise
 */
function onInstanceReady(check, threshold) {
	return new Promise((resolve, reject) => {
		// prepare safe counter
		var count = 0;

		var callback = function() {
			// increase counter
			count++;

			try {
				// check whether the instance is ready
				var instance = check();
			} catch (exception) {
				// reject because of an exception thrown by the condition callback
				reject(exception);
				return;
			}

			if (instance) {
				// object is now ready
				resolve(instance);
			} else {
				if (!threshold || count < Math.abs(threshold)) {
					// check again
					setTimeout(callback, 32 + Math.floor(Math.random() * 128));
				} else {
					// instance not ready
					reject();
				}
			}
		};

		// check
		callback();
	});
}

/**
 * Proxy used to invoke a function asynchronously.
 *
 * @return 	Promise
 */
function instantCallbackAsync() {
	// recover specified arguments
	var context = this;
	var args    = arguments;

	// create promise
	return new Promise((resolve) => {
		// instantly resolve the callback by passing the 
		// arguments that were specified
		resolve.apply(context, args);
	});
}

/**
 * Helper function used to copy the text of an
 * input element within the clipboard.
 *
 * Clipboard copy will take effect only in case the
 * function is handled by a DOM event explicitly
 * triggered by the user, such as a "click".
 *
 * @param 	mixed 	input  The input containing the text to copy.
 *
 * @return 	Promise
 */
function copyToClipboard(input) {
	// register and return promise
	return new Promise((resolve, reject) => {
		// define a fallback function
		var fallback = function(input) {
			// focus the input
			input.focus();
			// select the text inside the input
			input.select();

			try {
				// try to copy with shell command
				var copy = document.execCommand('copy');

				if (copy) {
					// copied successfully
					resolve(copy);
				} else {
					// unable to copy
					reject(copy);
				}
			} catch (error) {
				// unable to exec the command
				reject(error);
			}
		};

		// look for navigator clipboard
		if (!navigator.clipboard) {
			// navigator clipboard not supported, use fallback
			fallback(input);
			return;
		}

		// try to copy within the clipboard by using the navigator
		navigator.clipboard.writeText(input.value).then(() => {
			// copied successfully
			resolve(true);
		}).catch((error) => {
			// lets try with the fallback
			fallback(input);
		});
	});
}

/**
 * Checks if the current platform is Mac.
 *
 * @return 	boolean  True if Mac, otherwise false.
 */
Navigator.prototype.isMac = function() {
	return this.platform.toUpperCase().indexOf('MAC') === 0;
}

/**
 * Checks if the current platform is Windows.
 *
 * @return 	boolean  True if Windows, otherwise false.
 */
Navigator.prototype.isWin = function() {
	return this.platform.toUpperCase().indexOf('WIN') === 0;
}

/**
 * Checks if the current platform is iPhone.
 *
 * @return 	boolean  True if Android, otherwise false.
 */
Navigator.prototype.isiPhone = function() {
	return this.userAgent.toUpperCase().indexOf('iPhone') > -1;
}

/**
 * Checks if the current platform is iPhone.
 *
 * @return 	boolean  True if Android, otherwise false.
 */
Navigator.prototype.isiOS = function() {
	return [
		'iPad Simulator',
		'iPhone Simulator',
		'iPod Simulator',
		'iPad',
		'iPhone',
		'iPod'
	].includes(this.platform)
	// iPad on iOS 13 detection
	|| (this.userAgent.includes("Mac") && "ontouchend" in document)
}

/**
 * Checks if the current platform is Android.
 *
 * @return 	boolean  True if Android, otherwise false.
 */
Navigator.prototype.isAndroid = function() {
	return this.userAgent.toUpperCase().indexOf('ANDROID') > -1;
};

/**
 * @deprecated 1.8  Use UIAjax.do() instead.
 */
function doAjaxWithRetries(action, data, success, failure, attempt) {
	UIAjax.do(action, data, success, failure, attempt);
}

/**
 * @deprecated 1.8  Use UIAjax.isConnectionLost() instead.
 */
function isConnectionLostError(err) {
	return UIAjax.isConnectionLost(err);
}

/**
 * AJAX
 */

/**
 * UIAjax class.
 * Handles asynch server-side connections.
 */
class UIAjax {
	
	/**
	 * Normalizes the given argument to be sent via AJAX.
	 *
	 * @param 	mixed 	data  An object, an associative array or a serialized string.
	 *
	 * @return 	object 	The normalized object.
	 */
	static normalizePostData(data) {

		if (data === undefined) {
			data = {};
		} else if (Array.isArray(data)) {
			// the form data is serialized @see jQuery.serializeArray()
			var form = data;

			data = {};

			for (var i = 0; i < form.length; i++) {
				// if the field ends with [] it should be an array
				if (form[i].name.endsWith("[]")) {
					// if the field doesn't exist yet, create a new list
					if (!data.hasOwnProperty(form[i].name)) {
						data[form[i].name] = new Array();
					}

					// append the value to the array
					data[form[i].name].push(form[i].value);
				} else {
					// otherwise overwrite the value (if any)
					data[form[i].name] = form[i].value;
				}
			}
		}

		return data;
	}

	/**
	 * Makes the connection.
	 *
	 * @param 	string 	  url 		The URL to reach.
	 * @param 	mixed 	  data 		The data to post.
	 * @param 	function  success 	The callback to invoke on success.
	 * @param 	function  failure 	The callback to invoke on failure.
	 * @param 	integer   attempt 	The current attempt (optional).
	 *
	 * @return 	void
	 */
	static do(url, data, success, failure, attempt) {

		if (!UIAjax.concurrent && UIAjax.isDoing()) {
			return false;
		}

		if (attempt === undefined) {
			attempt = 1;
		}

		// return same object if data has been already normalized
		data = UIAjax.normalizePostData(data);

		var config = {};

		if (typeof url === 'object') {
			// we have a configuration object, use it
			Object.assign(config, url);
		} else {
			// use the default configuration
			config.type = 'post';
			config.url  = url;
		}

		// inject data within config
		config.data = data;

		var xhr = jQuery.ajax(
			// use fetched config
			config
		).done(function(resp) {

			UIAjax.pop(xhr);

			if (success !== undefined) {
				success(resp);
			}

		}).fail(function(err) {
			// If the error has been raised by a connection failure, 
			// retry automatically the same request. Do not retry if the
			// number of attempts is higher than the maximum number allowed.
			if (attempt < UIAjax.maxAttempts && UIAjax.isConnectionLost(err)) {

				// wait 256 milliseconds before launching the request
				setTimeout(function() {
					// relaunch same action and increase number of attempts by 1
					UIAjax.do(url, data, success, failure, attempt + 1);
				}, 256);

			} else {

				// otherwise raise the failure method
				if (failure !== undefined) {
					failure(err);
				}

			}

			UIAjax.pop(xhr);

			if (err.statusText != 'abort') {
				// display only in case the request hasn't been aborted by the user
				console.error(err);
			}

			if (err.status == 500) {
				console.error(err.responseText);
			}

		});

		UIAjax.push(xhr);

		return xhr;
	}

	/**
	 * Makes the connection with the server and start uploading the given data.
	 *
	 * @param 	string 	  url 		The URL to reach.
	 * @param 	mixed 	  data 		The data to upload.
	 * @param 	function  done 		The callback to invoke on success.
	 * @param 	function  failure 	The callback to invoke on failure.
	 * @param 	function  upload 	The callback to invoke to track the uploading progress.
	 *
	 * @return 	void
	 */
	static upload(url, data, done, failure, upload) {
		// define upload config
		var config = {
			url: 		 url,
			type: 		 'post',
			contentType: false,
			processData: false,
			cache: 		 false,
		};

		// define upload callback to keep track of progress
		if (typeof upload === 'function') {
			config.xhr = function() {
				var xhrobj = jQuery.ajaxSettings.xhr();

				if (xhrobj.upload) {
					// attach progress event
					xhrobj.upload.addEventListener('progress', function(event) {
						// calculate percentage
						var percent  = 0;
						var position = event.loaded || event.position;
						var total 	 = event.total;
						if (event.lengthComputable) {
							percent = Math.ceil(position / total * 100);
						}

						// trigger callback
						upload(percent);
					}, false);
				}

				return xhrobj;
			};
		}

		// invoke default do() method by using custom config
		return UIAjax.do(config, data, done, failure);
	}

	/**
	 * Checks if we own at least an active connection.
	 *
	 * @return 	boolean
	 */
	static isDoing() {
		return UIAjax.stack.length > 0 && UIAjax.count > 0;
	}

	/**
	 * Pushes the opened connection within the stack.
	 *
	 * @param 	mixed 	xhr  The connection resource.
	 *
	 * @return 	void
	 */
	static push(xhr) {
		UIAjax.stack.push(xhr);
		UIAjax.count++;
	}

	/**
	 * Removes the specified connection from the stack.
	 *
	 * @param 	mixed 	xhr  The connection resource.
	 *
	 * @return 	void
	 */
	static pop(xhr) {
		var index = UIAjax.stack.indexOf(xhr);

		if (index !== -1) {
			UIAjax.stack.splice(index, 1);
			UIAjax.count--;
		}
	}

	/**
	 * Checks if the given error can be intended as a loss of connection:
	 * generic error, no status and no response text.
	 * 
	 * @param 	object 	err 	The error object.
	 *
	 * @return 	boolean
	 */
	static isConnectionLost(err) {
		return (
			err.statusText == 'error'
			&& err.status == 0
			&& err.readyState == 0
			&& err.responseText == ''
		);
	}
}

UIAjax.stack 		= [];
UIAjax.count 		= 0;
UIAjax.concurrent 	= true;
UIAjax.maxAttempts 	= 3;

jQuery.parseJSON = function(data) {
	try {
		return JSON.parse(data);
	} catch (err) {
		console.log(err);
		console.log(data);
	}

	return null;
}