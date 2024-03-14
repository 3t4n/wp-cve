var wp_intel = wp_intel || { 'settings': {}, 'behaviors': {}, 'locale': {} };
(function( $) {

	wp_intel.attachBehaviors = function (context, settings) {
		context = context || document;
		settings = settings || wp_intel.settings;
		$.each(wp_intel.behaviors, function() {
			if ($.isFunction(this.attach)) {
				this.attach(context, settings);
			}
		});
	};

	wp_intel.detachBehaviors = function (context, settings, trigger) {
		context = context || document;
		settings = settings || wp_intel.settings;
		trigger = trigger || 'unload';
		// Execute all of them.
		$.each(wp_intel.behaviors, function () {
			if ($.isFunction(this.detach)) {
				this.detach(context, settings, trigger);
			}
		});
	};

	wp_intel.t = function (str, args, options) {
		options = options || {};
		options.context = options.context || '';

		// Fetch the localized version of the string.
		if (wp_intel.locale.strings && wp_intel.locale.strings[options.context] && wp_intel.locale.strings[options.context][str]) {
			str = wp_intel.locale.strings[options.context][str];
		}

		if (args) {
			str = wp_intel.formatString(str, args);
		}
		return str;
	};

	$(function () {
		wp_intel.attachBehaviors(document, wp_intel.settings);
	});

})( jQuery );
