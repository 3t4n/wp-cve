(function($) {
	'use strict';

	/**
	 * VikAppointments Card
	 */

	$.fn.vapcard = function(key, value) {
		if (!key) {
			throw 'Missing key';
		}

		if (value !== undefined) {
			// setter
			switch (key) {
				case 'image':
					if (value && value.match(/^<img/)) {
						$(this).find('.vap-card-image img').replaceWith(value);
					} else {
						$(this).find('.vap-card-image img').attr('src', value);
					}

					if (value) {
						$(this).find('.vap-card-image').show();
					} else {
						$(this).find('.vap-card-image').hide();
					}
					break;

				case 'badge':
					$(this).find('.card-badge-icon').html(value);
					break;

				case 'primary':
					$(this).find('.card-text-primary').html(value);
					break;

				case 'secondary':
					$(this).find('.card-text-secondary').html(value);
					break;

				case 'edit':
					if (value && (typeof value === 'number' || value.match(/^\d+$/))) {
						$(this).find('button.card-edit').attr('data-id', value).attr('onclick', '');
					} else {
						$(this).find('button.card-edit').attr('onclick', value).attr('data-id', '');
					}
					break;

				default:
					throw 'Unsupported parameter ' + key;
			}
		} else {
			// getter
			switch (key) {
				case 'image':
					return $(this).find('.vap-card-image img').attr('src');

				case 'badge':
					return $(this).find('.card-badge-icon').html();

				case 'primary':
					return $(this).find('.card-text-primary').html();

				case 'secondary':
					return $(this).find('.card-text-secondary').html();

				case 'edit':
					return $(this).find('button.card-edit');

				default:
					throw 'Unsupported parameter ' + key;
			}
		}
	}

	/**
	 * Percentage circle.
	 */

	$.fn.percentageCircle = function(method, data) {
		if (typeof method !== 'string') {
			// we probably have data as first argument 
			data = typeof method === 'object' ? method : {};
			// auto-create percentage circle
			method = 'create';
		}

		// define internal function to validate progress
		var getProgress = function(progress) {
			// validate progress
			progress = parseInt(progress);
			return isNaN(progress) ? 0 : Math.min(100, Math.abs(progress));
		};

		var animationTimeout = null;
		var _this = this;

		// define function to set and animate progress
		var animateProgress = function() {
			// get current progress
			var progress = getProgress($(_this).data('tmp'));
			var ceil     = getProgress($(_this).data('progress'));
			var factor   = progress <= ceil ? 1 : -1;
			var updated  = progress + 1 * factor;

			// fetch animation steps timer
			var timer = parseInt($(_this).data('timer'));
			timer = isNaN(timer) ? 16 : Math.abs(timer);

			if (timer == 0) {
				// bypass progress animation
				updated = ceil;
			}

			// make sure the progress didn't exceed the maximum/minimum amount
			if (factor == 1) {
				updated = Math.min(updated, ceil);
			} else {
				updated = Math.max(updated, ceil);
			}

			// go to next animation step
			$(_this).removeClass('p' + progress).addClass('p' + updated);
			// update percentage text
			$(_this).find('.amount').text(updated + '%');

			// update progress
			$(_this).data('tmp', updated);

			// re-launch animation recursively, in case it didn't end
			if ((factor == 1 && updated < ceil) || (factor == -1 && updated > ceil)) {
				
				// register timeout
				animationTimeout = setTimeout(animateProgress, timer);
			} else {
				// trigger done event
				$(_this).trigger('done');

				if (ceil == 100) {
					// trigger complete event
					$(_this).trigger('complete');
				}
			}
		};

		if (method.match(/^create$/i)) {
			// create default properties
			data = $.extend({
				progress: 0,
				size:     null,
				color:    null,
				darkMode: false,
				timer:    16,
			}, data);

			// validate progress
			data.progress = getProgress(data.progress);

			// get original classes
			var classes = $(this).attr('class');

			// instantiate only once
			if (!$(this).hasClass('c100')) {
				$(this).addClass('c100 p' + data.progress)
					.data('progress', data.progress)
					.data('tmp', data.progress)
					.data('class', classes)
					.data('size', data.size)
					.data('color', data.color)
					.data('darkMode', data.darkMode)
					.data('timer', data.timer)
					.html('<span class="amount">' + data.progress + '%</span><div class="slice">\n<div class="bar"></div>\n<div class="fill"></div>\n</div>');

				if (data.size) {
					$(this).addClass(data.size);
				}

				if (data.color) {
					$(this).addClass(data.color);
				}

				if (data.darkMode) {
					$(this).addClass('dark');
				}
			}
		} else if (method.match(/^destroy$/i)) {
			// make sure the progress exists
			if ($(this).hasClass('c100')) {
				$(this).html('').attr('class', $(this).data('class'));
			}
		} else if (method.match(/^progress$/i)) {
			// look for getter or setter
			if (typeof data !== 'undefined') {
				// get previous
				var prev = $(this).data('progress');
				// setter
				$(this).data('progress', getProgress(data));

				// make sure something has changed
				if ($(this).data('progress') != prev) {
					// trigger change event
					$(this).trigger('change');
					// animate progress
					animateProgress();
				}
			} else {
				// getter
				return getProgress($(this).data('progress'));
			}
		} else if (method.match(/^color$/i)) {
			// look for getter or setter
			if (typeof data !== 'undefined') {
				// get previous
				var prev = $(this).data('color');
				// setter
				$(this).data('color', data);
				// refresh color
				$(this).removeClass(prev).addClass(data);
			} else {
				// getter
				var color = $(this).data('color');
				return color ? color : 'blue';
			}
		} else if (method.match(/^size$/i)) {
			// look for getter or setter
			if (typeof data !== 'undefined') {
				// get previous
				var prev = $(this).data('size');
				// setter
				$(this).data('size', data);
				// refresh size
				$(this).removeClass(prev).addClass(data);
			} else {
				// getter
				var size = $(this).data('size');
				return size ? size : 'normal';
			}
		} else if (method.match(/^darkMode$/i)) {
			// look for getter or setter
			if (typeof data !== 'undefined') {
				// setter
				$(this).data('darkMode', data ? true : false);
				// refresh darkMode
				if (data) {
					// enable
					$(this).addClass('dark');
				} else {
					// disable
					$(this).removeClass('dark');
				}
			} else {
				// getter
				return $(this).data('darkMode') ? true : false;
			}
		} else if (method.match(/^timer$/i)) {
			// look for getter or setter
			if (typeof data !== 'undefined') {
				// get previous
				var prev = $(this).data('timer');
				// setter
				$(this).data('timer', data);
				// refresh timer
				$(this).removeClass(prev).addClass(data);
			} else {
				// getter
				var timer = parseInt($(this).data('timer'));
				return timer && !isNaN(timer) ? timer : 16;
			}
		}

		return $(this);
	}

	/*
	 * OVERLAYS
	 */

	window['openLoadingOverlay'] = (lock, message) => {
		var _html = '';

		if (message !== undefined) {
			_html += '<div class="vap-loading-box-message">' + message + '</div>';
		}

		$('#content').append('<div class="vap-loading-overlay' + (lock ? ' lock' : '') + '">' + _html + '<div class="vap-loading-box"></div></div>');
	}

	window['closeLoadingOverlay'] = () => {
		$('.vap-loading-overlay').remove();
	}

	/*
	 * SYSTEM UTILS
	 */

	$.fn.updateChosen = function(value, active) {
		$(this).val(value).trigger('chosen:updated').trigger('liszt:updated');

		if (active) {
			$(this).next().addClass('active');
		} else {
			$(this).next().removeClass('active');
		}
	}

	window['debounce'] = (func, wait, immediate) => {
		var timeout;
		return function() {
			var context = this, args = arguments;
			var later = function() {
				timeout = null;
				if (!immediate) {
					func.apply(context, args);
				}
			};
			var callNow = immediate && !timeout;
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
			if (callNow) {
				func.apply(context, args);
			}
		};
	}

	window['vapToggleSearchToolsButton'] = (btn, suffix) => {
		if (suffix === undefined) {
			suffix = '';
		} else {
			suffix = '-' + suffix;
		}

		if ($(btn).hasClass('btn-primary')) {
			$('#vap-search-tools' + suffix).slideUp('fast');

			$(btn).removeClass('btn-primary');
			
			$('#vap-tools-caret' + suffix).removeClass('fa-caret-up').addClass('fa-caret-down');
		} else {
			$('#vap-search-tools' + suffix).slideDown('fast');

			$(btn).addClass('btn-primary');

			$('#vap-tools-caret' + suffix).removeClass('fa-caret-down').addClass('fa-caret-up');
		}
	}

	/*
	 * LEFTBOARD MENU
	 */

	 window['leftBoardMenuItemClicked'] = (elem, callee) => {
		var wrapper = $(elem).next('.wrapper');

		if (!wrapper.length) {
			// find wrapper within the container
			wrapper = $(elem).find('.wrapper');
		}

		var has = !wrapper.hasClass('collapsed');

		if (has && callee == 'out')
		{
			// do not proceed as we are facing a loading delay,
			// because the 'hover' event wasn't yet ready
			return;
		}

		$('#vap-main-menu .parent .wrapper').removeClass('collapsed');

		$('.vap-angle-dir').removeClass('fa-angle-up');
		$('.vap-angle-dir').addClass('fa-angle-down');
		$('#vap-main-menu .parent .title').removeClass('focus');
		
		if (has) {
			wrapper.addClass('collapsed');
			var angle = $(elem).find('.vap-angle-dir');
			angle.addClass('fa-angle-up');
			angle.removeClass('fa-angle-down');
			wrapper.closest('.title').addClass('focus');
		}
	}

	window['leftBoardMenuToggle'] = () => {

		// restore arrows
		$('.vap-angle-dir').removeClass('fa-angle-up');
		$('.vap-angle-dir').addClass('fa-angle-down');
		$('#vre-main-menu .parent .title').removeClass('focus');

		var status;

		if (isLeftBoardMenuCompressed()) {
			$('.vap-leftboard-menu').removeClass('compressed');
			$('.vap-task-wrapper').removeClass('extended');
			status = 1;
		} else {
			$('.vap-leftboard-menu').addClass('compressed');
			$('.vap-task-wrapper').addClass('extended');

			$('.vap-leftboard-menu.compressed .parent .title.selected').removeClass('collapsed');
			$('.vap-leftboard-menu.compressed .parent .wrapper.collapsed').removeClass('collapsed');

			status = 2;
		}

		leftBoardMenuRegisterStatus(status);
		$(window).trigger('resize');

	}

	window['leftBoardMenuRegisterStatus'] = (status) => {
		/**
		 * Store the main menu status with the browser cookie,
		 * so that each administrator will be able to use its
		 * preferred layout.
		 *
		 * Keep the main menu status for 1 year.
		 *
		 * @since 1.7
		 */
		var date = new Date();
		date.setYear(date.getFullYear() + 1);

		document.cookie = 'vikappointments.mainmenu.status=' + status + '; expires=' + date.toUTCString() + '; path=/';
	}

	window['isLeftBoardMenuCompressed'] = () => {
		return $('.vap-leftboard-menu').hasClass('compressed') && $(window).width() >= 768;
	}

	$(function() {
		if (typeof VIKAPPOINTMENTS_MENU_INIT === 'undefined') {
			// avoid to re-init menu again
			window['VIKAPPOINTMENTS_MENU_INIT'] = true;

			if (isLeftBoardMenuCompressed()) {
				$('.vap-leftboard-menu.compressed .parent .title.selected').removeClass('collapsed');
				$('.vap-leftboard-menu.compressed .parent .wrapper.collapsed').removeClass('collapsed');
			}

			$('#vap-main-menu .parent .title').disableSelection();

			$('#vap-main-menu .parent .title').on('click', function() {
				if (!isLeftBoardMenuCompressed()) {
					leftBoardMenuItemClicked(this, 'click');
				}
			});

			$('#vap-main-menu .parent .title').hover(function() {
				if (isLeftBoardMenuCompressed() && !$(this).hasClass('collapsed')) {
					leftBoardMenuItemClicked(this, 'hover');

					$('#vap-main-menu.compressed .parent .title').removeClass('collapsed');
					$(this).addClass('collapsed');
				}

				if ($(this).hasClass('has-href') && $(this).find('.wrapper').length) {
					leftBoardMenuItemClicked(this, 'hover');

					$('#vap-main-menu .parent .title').removeClass('collapsed');
					$(this).addClass('collapsed');
				}
			}, function() {
				if ($(this).hasClass('has-href') && $(this).find('.wrapper').length) {
					leftBoardMenuItemClicked(this, 'out');

					$('#vap-main-menu .parent .title').removeClass('collapsed');
				}
			});
			
			$('.vap-leftboard-menu').hover(function() {
				
			}, function() {
				if ($(window).width() >= 768) {
					$('.vap-leftboard-menu.compressed .parent .title').removeClass('collapsed');
					$('.vap-leftboard-menu.compressed .parent .wrapper').removeClass('collapsed');
				}
			});

			$('.vap-leftboard-menu .custom').hover(function() {
				if ($(window).width() >= 768) {
					$('.vap-leftboard-menu.compressed .parent .title').removeClass('collapsed');
					$('.vap-leftboard-menu.compressed .parent .wrapper').removeClass('collapsed');
				}
			}, function() {

			});

			$('#vap-menu-toggle-phone').on('click', function() {
				$('.vap-leftboard-menu').slideToggle();
			});
		}
	});

	/*
	 * DOCUMENT CONTENT RESIZE
	 */

	$(function() {
		// Statement to quickly disable doc resizing.
		// Do not proceed in case of small devices (< 768).
		if (true && $(window).width() >= 768) {
			var task     = $('.vap-task-wrapper');
			var lfb_menu = $('.vap-leftboard-menu');
			var _margin  = 15;

			$(window).resize(function() {
				// var p = (lfb_menu.width() + _margin) * 100 / $(document).width();
				// task.css('width', (100 - Math.ceil(p + 1)) + '%');
				let p = lfb_menu.outerWidth() + _margin * 2;
				task.css('width', 'calc(100% - ' + p + 'px)');
			});
		}

		$(window).trigger('resize');
	});
})(jQuery);

class VikTimer {
	static debounce(key, func, wait, immediate) {
		if (!VikTimer.debounceLookup) {
			VikTimer.debounceLookup = {};
		}

		return function() {
			var context = this, args = arguments;
			var later = function() {
				VikTimer.debounceLookup[key] = null;
				if (!immediate) func.apply(context, args);
			};
			var callNow = immediate && !VikTimer.debounceLookup[key];
			clearTimeout(VikTimer.debounceLookup[key]);
			VikTimer.debounceLookup[key] = setTimeout(later, wait);
			if (callNow) func.apply(context, args);
		};
	}

	static isRunning(key) {
		return VikTimer.debounceLookup && VikTimer.debounceLookup[key];
	}
}

/**
 * @deprecated 1.8  Use onInstanceReady() instead.
 */
function callbackOn(on, callback) {
	onInstanceReady(on).then(callback);
}

/*
 * SEARCH BAR - editconfig
 */

function SearchBar(matches) {
	this.setMatches(matches);
}

SearchBar.prototype.setMatches = function(matches) {
	this.matches = matches;
	this.currIndex = 0;
}

SearchBar.prototype.clear = function() {
	this.setMatches(false);
}

SearchBar.prototype.isNull = function() {
	return this.matches === false;
}

SearchBar.prototype.isEmpty = function() {
	return !this.isNull() && this.matches.length == 0;
}

SearchBar.prototype.getElement = function() {
	if (this.matches === false) {
		return null;
	}
	return this.matches[this.currIndex];
}

SearchBar.prototype.getCurrentIndex = function() {
	return this.currIndex;
}

SearchBar.prototype.size = function() {
	if (this.matches === false) {
		return 0;
	}
	return this.matches.length;
}

SearchBar.prototype.next = function() {
	if (this.matches === false) {
		return null;
	}
	this.currIndex++;
	if (this.currIndex >= this.matches.length) {
		this.currIndex = 0;
	}
	return this.matches[this.currIndex];
}

SearchBar.prototype.previous = function() {
	if (this.matches === false) {
		return null;
	}
	this.currIndex--;
	if (this.currIndex < 0) {
		this.currIndex = this.matches.length-1;
	}
	return this.matches[this.currIndex];
}
