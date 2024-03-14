(function ($) {
	if (window.NodeList && !NodeList.prototype.forEach) {
		NodeList.prototype.forEach = Array.prototype.forEach;
	}

	if (!window.swiftControlOpt) return;

	var ajax = {};

	var menu = document.querySelector('.swift-control-widgets');
	var panelsWrapper = document.querySelector('.swift-control-helper-panels');
	var widgetSize = parseInt(swiftControlOpt.size, 10);

	var position = {
		x: swiftControlOpt.position.x,
		x_direction: swiftControlOpt.position.x_direction,
		y: swiftControlOpt.position.y,
		y_direction: swiftControlOpt.position.y_direction,
		y_percentage: swiftControlOpt.position.y_percentage
	};

	var docWidth;
	var halfHeight;

	function init() {
		if (!menu) return;
		setupMenu();
		checkDisabledWidget();
		checkInlineEditing();
		setupWallaceSupport();
		setupDraggable();
	}

	function setupMenu() {
		document.querySelector('.swift-control-widgets .swift-control-widget-setting a').addEventListener('click', function (e) {
			e.preventDefault();

			if (!this.parentNode.parentNode.classList.contains('is-dragging')) {
				this.parentNode.parentNode.classList.toggle('is-expanded');
			}
		});
	}

	function checkDisabledWidget() {
		var disabledItems = document.querySelectorAll('.swift-control-widgets .swift-control-widget-item.is-disabled a');
		if (!disabledItems.length) return;

		disabledItems.forEach(function (el) {
			el.addEventListener('click', function (e) {
				e.preventDefault();
			});
		});
	}

	function checkInlineEditing() {
		var inlineEdits = document.querySelectorAll('.swift-control-widgets .swift-control-widget-item.inline-edit a');
		if (!inlineEdits.length) return;

		inlineEdits.forEach(function (el) {
			el.addEventListener('click', function (e) {
				e.preventDefault();

				if (this.parentNode.classList.contains('wallace-edit')) {
					document.dispatchEvent(new CustomEvent("walInlineAdminButtonClicked"));
				}
			});
		});
	}

	function hideMenuOnInlineEditing() {
		menu.classList.remove('is-expanded');

		// Hide the whole menu after widget items hidden.
		setTimeout(function () {
			menu.classList.add('is-hidden-mode');
		}, swiftControlOpt.settingButton.hidingDelay);
	}

	function setupWallaceSupport() {
		document.addEventListener('WallaceInlineOpened', function (e) {
			hideMenuOnInlineEditing();
		});

		document.addEventListener('WallaceInlineClosed', function (e) {
			menu.classList.remove('is-hidden-mode');
		});
	}

	function setupDraggable() {
		setupInteract();
	}

	function setupInteract() {
		window.addEventListener('load', setupPosition);
		window.addEventListener('resize', setupPosition);

		var draggable = interact('.swift-control-widgets').draggable({
			modifiers: [
				interact.modifiers.restrictRect({
					restriction: 'parent'
				})
			]
		});

		draggable.on('dragstart', function (event) {
			event.target.classList.add('is-dragging');
			showHelperPanels();
		});

		draggable.on('dragmove', function (event) {
			position.x += event.dx;
			position.y += event.dy;

			docWidth = document.documentElement.clientWidth;

			event.target.style.webkitTransform = event.target.style.transform =
				'translate(' + position.x + 'px, ' + position.y + 'px)';

			if (position.x >= docWidth / 2) {
				event.target.classList.add('is-dragged-right');
				event.target.classList.remove('is-dragged-left');
			} else {
				event.target.classList.remove('is-dragged-right');
				event.target.classList.add('is-dragged-left');
			}

			// Adjust the left helper panel's width.
			if (position.x < 35) {
				panelsWrapper.querySelector('.left-panel').style.width = widgetSize + 'px';
			} else {
				panelsWrapper.querySelector('.left-panel').style.width = 35 + 'px';
			}

			// Adjust the right helper panel's width.
			if (position.x + widgetSize > docWidth - 35) {
				panelsWrapper.querySelector('.right-panel').style.width = widgetSize + 'px';
			} else {
				panelsWrapper.querySelector('.right-panel').style.width = 35 + 'px';
			}

			// Show/ hide the middle horizontal panel.
			if (position.y === 1) {
				panelsWrapper.querySelector('.middle-panel').classList.add('is-shown');
			} else {
				panelsWrapper.querySelector('.middle-panel').classList.remove('is-shown');
			}

		});

		draggable.on('dragend', function (event) {
			docWidth = document.documentElement.clientWidth;
			halfHeight = document.documentElement.clientHeight / 2;

			if (position.x >= docWidth / 2) {
				position.x = docWidth - widgetSize;
				position.x_direction = 'right';
				event.target.classList.add('is-pinned-right');
			} else {
				position.x = 0;
				position.x_direction = 'left';
				event.target.classList.remove('is-pinned-right');
			}

			if (position.y < 0) {
				position.y = position.y < (halfHeight * -1) ? (halfHeight - (swiftControlOpt.size / 2)) * -1 : position.y;
			} else {
				position.y = position.y > halfHeight ? halfHeight - (swiftControlOpt.size / 2) : position.y;
			}

			event.target.classList.add('is-adjusting');
			event.target.style.webkitTransform = event.target.style.transform =
				'translate(' + position.x + 'px, ' + position.y + 'px)';
			event.target.classList.remove('is-dragged-right');
			event.target.classList.remove('is-dragged-left');

			if (position.y < 0) {
				// Top means, negative.
				position.y_direction = 'top';
			} else {
				position.y_direction = 'bottom';
			}

			position.y_percentage = position.y_direction === 'top' ? position.y * -1 : position.y;
			position.y_percentage = (position.y_percentage / halfHeight) * 100;

			/**
			 * Wait for 100ms so that the widget's "collapse & expand" checking
			 * doesn't affected by the click triggered before the dragging.
			 */
			setTimeout(function () {
				event.target.classList.remove('is-dragging');
			}, 100);

			setTimeout(function () {
				event.target.classList.remove('is-adjusting');
			}, 350); // 350ms is to match the css transition duration.

			hideHelperPanels();
			ajax.savePosition();
		});
	}

	function setupPosition(e) {
		docWidth = document.documentElement.clientWidth;
		halfHeight = document.documentElement.clientHeight / 2;

		position.x = position.x_direction === 'left' ? position.x = 0 : docWidth - widgetSize;
		position.y = (position.y_percentage / 100) * halfHeight;
		position.y = position.y > halfHeight ? halfHeight - (swiftControlOpt.size / 2) : position.y;
		position.y = position.y_direction === 'top' ? position.y * -1 : position.y;

		menu.style.webkitTransform = menu.style.transform =
			'translate(' + position.x + 'px, ' + position.y + 'px)';

		if (e.type === 'load') {
			menu.classList.remove('is-invisible');
		}
	}

	function showHelperPanels() {
		var fragment = document.createDocumentFragment();
		var panels = [
			createElement('div', 'helper-panel vertical-panel left-panel'),
			createElement('div', 'helper-panel vertical-panel center-panel'),
			createElement('div', 'helper-panel vertical-panel right-panel'),

			createElement('div', 'helper-panel horizontal-panel top-middle-panel'),
			createElement('div', 'helper-panel horizontal-panel middle-panel'),
			createElement('div', 'helper-panel horizontal-panel bottom-middle-panel'),
		];

		for (var i = 0; i < panels.length; i++) {
			// Append child to fragment, not DOM.
			fragment.appendChild(panels[i]);
		}

		// Now append the fragment to the DOM.
		panelsWrapper.appendChild(fragment);

	}

	function hideHelperPanels() {
		while (panelsWrapper.firstChild) {
			panelsWrapper.removeChild(panelsWrapper.firstChild);
		}
	}

	function createElement(tagName, className, child) {
		var tag = document.createElement(tagName);
		tag.className = className;

		if (child) tag.appendChild(child);
		return tag;
	}

	ajax.savePosition = function () {
		var data = {};
		data.action = 'swift_control_save_position';
		data.nonce = swiftControlOpt.nonce;

		for (var prop in position) {
			if (position.hasOwnProperty(prop)) {
				data[prop] = position[prop];
			}
		}

		$.ajax({
			url: swiftControlOpt.ajaxUrl,
			type: 'post',
			dataType: 'json',
			data: data
		}).done(function (r) {
			// console.log(r.data);
		}).fail(function () {
			console.log('Failed to save widget position');
		});
	};

	init();
})(jQuery);