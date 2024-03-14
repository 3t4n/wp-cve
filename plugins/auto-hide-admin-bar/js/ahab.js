jQuery(document).ready(function ($) {

	/** Generic functions **/

	// function to set a cookie
	function setCookie(cName, cValue, expDays) {
		let date = new Date();
		date.setTime(date.getTime() + (expDays * 24 * 60 * 60 * 1000));
		const expires = "expires=" + date.toUTCString();
		document.cookie = cName + "=" + cValue + "; " + expires + "; SameSite=Strict; path=/";
	}

	// function to get a cookie - you want one too?
	function getCookie(cName) {
		const name = cName + "=";
		const cDecoded = decodeURIComponent(document.cookie); //to be careful
		const cArr = cDecoded.split('; ');
		let res;
		cArr.forEach(val => {
			if (val.indexOf(name) === 0) res = val.substring(name.length);
		})
		return res;
	}

	// function to add arrow to DOM
	function addArrow(pos, radius) {
		return '<div style="' + pos + radius + '" id="arrow"><span style="color:#a7aaad;" class="dashicons dashicons-arrow-down-alt"></span></div>';
	}

	// return arrow position styles
	function arrowPosStyle(arrowPos) {
		if (arrowPos) {
			if ('left' == arrowPos) {
				return 'left: 0;';
			}
			if ('right' == arrowPos) {
				return 'right: 0;';
			}
		}
	}

	// return arrow border radius style
	function arrowBorderRadiusStyle(arrowPos) {

		if (arrowPos) {
			if ('left' == arrowPos) {
				return 'border-radius: 0 0 4px 0;';
			}
			if ('right' == arrowPos) {
				return 'border-radius: 0 0 0 4px;';
			}
		}
	}

	function ahadMain() {

		// doNothing function is for enabling hoverIntent to work with two layers.
		function doNothing() {
		}

		// Show the Admin Bar
		function adminBarShow() {
			$('#wpadminbar').animate({ 'top': '0px' }, ahab['ahab_anim_speed']);
			$('body').animate({ 'margin-top': '0px' }, ahab['ahab_anim_speed']);
			$('body').animate({ 'background-position-y': '0px' }, ahab['ahab_anim_speed']);
			if ('twentyfourteen' == themeName) {
				$('.admin-bar.masthead-fixed .site-header').animate({ 'top': '32px' }, ahab['ahab_anim_speed'])
			}
		}

		// Hide the Admin Bar
		function adminBarHide() {
			// do not hide if toggle cookie has t a certain value
			if (getCookie('toggle') != "locked") {
				if (windowSize > 782) {
					$('#wpadminbar').animate({ 'top': '-32px' }, ahab['ahab_anim_speed']);
					$('body').animate({ 'margin-top': '-32px' }, ahab['ahab_anim_speed']);
					$('body').animate({ 'background-position-y': '-32px' }, ahab['ahab_anim_speed']);
					if ('twentyfourteen' == themeName) {
						$('.admin-bar.masthead-fixed .site-header').animate({ 'top': '0px' }, ahab['ahab_anim_speed'])
					}
				} else {
					if (1 == ahabMobile) {
						$('#wpadminbar').animate({ 'top': '-46px' }, ahab['ahab_anim_speed']);
						$('body').animate({ 'margin-top': '-46px' }, ahab['ahab_anim_speed']);
						$('body').animate({ 'background-position-y': '-46px' }, ahab['ahab_anim_speed']);
						if ('twentyfourteen' == themeName) {
							$('.admin-bar.masthead-fixed .site-header').animate({ 'top': '-46px' }, ahab['ahab_anim_speed'])
						}
					}
				}
			}
		}

		// Arrow CSS when admin bar visible
		function arrowCSSAdminBarIn() {
			var ahabArrowPos = ahab['ahab_arrow_pos'];
			if (windowSize > 782) {
				$('#arrow').css('top', '30px');
			}
			else {
				$('#arrow').css('top', '46px');
			}
			$('#arrow').css('z-index', '99998');
			$('#arrow span').css('transform', 'rotate(180deg)');
			$('#arrow').css(arrowBorderRadiusStyle(ahabArrowPos));
			$('#arrow').css(arrowPosStyle(ahabArrowPos));
		}

		// Arrow CSS when admin bar invisible
		function arrowCSSAdminBarOut() {
			var ahabArrowPos = ahab['ahab_arrow_pos'];

			$('#arrow').css('top', '0px');
			$('#arrow').css('z-index', '99999');
			$('#arrow span').css('transform', 'rotate(0deg)');
			$('#arrow').css(arrowBorderRadiusStyle(ahabArrowPos));
			$('#arrow').css(arrowPosStyle(ahabArrowPos));
		}

		// check if page is in iframe & user is logged in - if so, customizer is active
		var isInIframe = (window.location != window.parent.location) ? true : false;

		var beaverBuilderActive = $('html').hasClass("fl-builder-edit");

		/** Start a MutationObserver to keep an eye on the change of the body classes,
		 *  which indicates Beaver Builder editor is closed.
		 */
		if (beaverBuilderActive) {
			// element to watch
			var element, observerConfig, bodyObserver;
			element = $('html');
			// only look for attribute changes
			observerConfig = { attributes: true };
			bodyObserver = new MutationObserver(function (mutations) {
				mutations.forEach(function (mutation) {
					var newVal = $(mutation.target).prop(mutation.attributeName);
					if (mutation.attributeName === "class") {
						// check if  html class has changed, check if fl-builder-edit is in it
						if (!$('html').hasClass("fl-builder-edit")) {
							ahadMain();
						}
					}
				})
			})
			// Observe. And protect.
			bodyObserver.observe(element[0], observerConfig);

		}

		if (!isInIframe && ($('#wpadminbar').length === 1) && !beaverBuilderActive) {

			var themeName = ahab['theme_name'];
			var windowSize = $(window).width();
			var ahabMobile = parseInt(ahab['ahab_mobile'], 10);
			var ahabArrow = parseInt(ahab['ahab_arrow'], 10);
			var ahabArrowPos = ahab['ahab_arrow_pos'];

			if (windowSize > 782) {
				$('#wpadminbar').css('top', '-32px');
				$('body').css('margin-top', '-32px');
				if ('twentyfourteen' == themeName) {
					$('.admin-bar.masthead-fixed .site-header').css('top', '0px');
				}

			} else {
				if (1 == ahabMobile) {
					$('#wpadminbar').css('z-index', '99999 !important');
					$('#wpadminbar').css('cssText', 'z-index: 99999 !important; top: -46px;');
					$('body').css('margin-top', '-46px');
				} else {
					$('#wpadminbar').css('top', '0px');
					$('body').css('margin-top', '0px');
				}
			}

			/// check if toggle switch is not checked
			if (!$('#toggle-checkbox').prop("checked")) {
				// then add arrow div
				if (($('div#arrow').length === 0) && (2 == ahabArrow)) {
					$('#wpadminbar').append(addArrow(arrowPosStyle(ahabArrowPos), arrowBorderRadiusStyle(ahabArrowPos)));
				}
			}

			if ($('#hiddendiv').length === 0) {
				$('body').append('<div id=\'hiddendiv\'></div>');
			}

			// hiddendiv should exist now so let's do some magic with it.
			autohide = $('#hiddendiv');

			autohide.css('width', '100%');
			if ((windowSize < 782) && (1 == ahabMobile)) {
				autohide.css('min-height', '46px');
			} else {
				autohide.css('min-height', '32px');
			}
			autohide.css('z-index', '99998'); // admin bar is at z-index: 99999;
			autohide.css('position', 'fixed');
			autohide.css('top', '0px');
			var configIn = {
				over: adminBarShow, // function = onMouseOver callback (REQUIRED)
				sensitivity: 6,
				out: doNothing // function = onMouseOut callback (REQUIRED)
			};
			var configOut = {
				over: doNothing, // function = onMouseOver callback (REQUIRED)
				timeout: ahab['ahab_delay'], // number = milliseconds delay before onMouseOut
				interval: ahab['ahab_interval'], // number = millseconds interval for mouse polling
				out: adminBarHide // function = onMouseOut callback (REQUIRED)
			};

			// check if arrow is visible
			if (2 == ahabArrow) {
				$('#arrow').click(function () {
					if ($('#wpadminbar').css('top') == '0px') {
						adminBarHide();
						arrowCSSAdminBarOut();
					} else {
						adminBarShow();
						arrowCSSAdminBarIn();
					}
				});
			}
			else {
				if (getCookie('toggle') == "locked") {
					// Lock the admin bar while the toggle switch is checked

					$('#toggle-checkbox').prop('checked', true);
					console.log(getCookie('toggle'));
					adminBarShow();
				}
				else {
					// default behaviour (hover and show/hide)
					autohide.hoverIntent(configIn);
					$('#wpadminbar').hoverIntent(configOut);
				}
			}
		}

		// do something when key pressed - using jquery.hotkeys.js library
		// https://github.com/jeresig/jquery.hotkeys
		// and it's included in WordPress :)

		// build string for hotkey to add
		var hotKey = new Array();

		if (ahab['ahab_keyboard_alt'] == 'Alt') {
			hotKey.push('alt')
		}

		if (ahab['ahab_keyboard_ctrl'] == 'Ctrl') {
			hotKey.push('ctrl')
		}

		if (ahab['ahab_keyboard_shift'] == 'Shift') {
			hotKey.push('shift')
		}

		if (ahab['ahab_keyboard_char']) {
			hotKey.push(ahab['ahab_keyboard_char'])
		}

		$.hotkeys.add(hotKey.join('+'), function () {

			if ($('#wpadminbar').css('top') == '0px') {
				adminBarHide()
			} else {
				adminBarShow();
			}
		});

		// Set the cookie based on the toggle checkbox status
		$('#toggle-checkbox').click(function () {

			if ($(this).prop("checked")) {
				// Toggle is checked - so lock the admin bar
				setCookie('toggle', 'locked', 30);
				adminBarShow();

				// check if arrow visibility is set
				if (parseInt(ahab['ahab_arrow'], 10) == 2) {
					// remove the arrow
					$('#arrow').remove();
				}
			}

			if (!$(this).prop("checked")) {
				// Toggle is unchecked - so lock the admin bar
				setCookie('toggle', 'unlocked', 30);
				adminBarHide();

				// check if arrow visibility is set
				if (parseInt(ahab['ahab_arrow'], 10) == 2) {
					// add arrow div
					if (($('div#arrow').length === 0) && (2 == ahabArrow)) {
						$('#wpadminbar').append(addArrow(arrowPosStyle(ahabArrowPos), arrowBorderRadiusStyle(ahabArrowPos)));
					}
					ahadMain();
				}
				else {

					// Restore default behaviour
					autohide.hoverIntent(configIn);
					$('#wpadminbar').hoverIntent(configOut);
				}
			}
		});

		// Lock the admin bar while the toggle is checked/locked
		if (getCookie('toggle') == "locked") {
			$('#toggle-checkbox').prop('checked', true);
			adminBarShow();
		}
	}

	$(document).ready(ahadMain);
});
