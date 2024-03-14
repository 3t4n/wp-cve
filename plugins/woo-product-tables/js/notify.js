(function($) {
	"use strict";
	$.sNotify = function(options) {
		if (!this.length) {
			return this;
		}

		var css = {
			position: 'fixed',
			display: 'none',
			padding: '1em',
			'background-color': 'white',
			'box-shadow': '0px 0px 6px 0px rgba(0,0,0,0.1)',
			'z-index': 1000000
		}

		if (options.position) {
			switch (options.position) {
				case 'top_right':
					css.right = '1.7em';
					css.top = '3.3em';
				break;
				case 'top_left':
					css.left = '1.7em';
					css.top = '3.3em';
				break;
				case 'bottom_right':
					css.right = '1.7em';
					css.bottom = '3.3em';
				break;
				case 'bottom_left':
					css.left = '1.7em';
					css.bottom = '3.3em';
				break;
				case 'center':
					css.top = '50%';
					css.left = '50%';
					css['margin-top'] = '-25px';
					css['margin-left'] = '-125px';
				break;
			}
		} else {
			css.right = '1.7em';
			css.top = '3.3em';
		}

		var $wrapper = $('<div class="s-notify">').css(css);

		$wrapper.wrapInner(this);
		$wrapper.appendTo('body');

		if (options.icon) {
			$('<i/>').addClass(options.icon).appendTo($wrapper);
		}

		if (options.content) {
			$('<div class="notify-content"></div>').css('display', 'inline-block').wrapInner(options.content).appendTo($wrapper);
		}

		setTimeout(function() {
			$wrapper.fadeIn();
			if (options.delay) {
				setTimeout(function() {
					$wrapper.fadeOut(function() {
						$wrapper.remove();
					});
				}, options.delay);
			}
		}, 200);

		return $.extend($wrapper, {
			close: function(timeout) {
				setTimeout(function() {
					$wrapper.fadeOut(function() {
						$wrapper.remove();
					});
				}, timeout || '0');
			},
			update: function(content, icon) {
				this.find('.notify-content').empty().append(content);
				if (icon) {
					this.find('i').removeClass().addClass(icon);
				}
				return this;
			}
		});
	};

})(jQuery);
