/*
 * Photonic Modal
 * Used as the overlay panel
 * License: MIT
 */

export const Modal = function(modal, options) {
	const body = document.body;

	//Defaults
	const settings = Object.assign({
		modalTarget: 'photonicModal',
		closeCSS: '',
		closeFromRight: 0,
		width: '80%',
		height: '100%',
		top: '0px',
		left: '0px',
		zIndexIn: '9999',
		zIndexOut: '-9999',
		color: '#39BEB9',
		opacityIn: '1',
		opacityOut: '0',
		animationDuration: '.6s',
		overflow: 'auto',
	}, options);

	let overlay = document.querySelector('.photonicModalOverlay'),
		scrollable = document.querySelector('.photonicModalOverlayScrollable');

	if (!overlay) {
		overlay = document.createElement('div');
		overlay.className = 'photonicModalOverlay';

		scrollable = document.createElement('div');
		scrollable.className = 'photonicModalOverlayScrollable';

		overlay.appendChild(scrollable);
		body.appendChild(overlay);
	}

	let closeIcon = modal.querySelector('.photonicModalClose');
	if (!closeIcon) {
		closeIcon = document.createElement('a');
		closeIcon.className = 'photonicModalClose ' + settings.closeCSS;
		closeIcon.style.right = settings.closeFromRight;
		closeIcon.innerHTML = '&times;';
		closeIcon.setAttribute('href', '#');
		modal.insertAdjacentElement('afterbegin', closeIcon);
	}

	closeIcon = modal.querySelector('.photonicModalClose');

	const id = document.querySelector('#' + settings.modalTarget);

	// Default Classes
	// id.addClass('photonicModal');
	// id.addClass(settings.modalTarget+'-off');

	//Init styles
	const initStyles = {
		'width': settings.width,
		'height': settings.height,
		'top': settings.top,
		'left': settings.left,
		'background-color': settings.color,
		'overflow-y': settings.overflow,
		'z-index': settings.zIndexOut,
		'opacity': settings.opacityOut,
		'-webkit-animation-duration': settings.animationDuration,
		'-moz-animation-duration': settings.animationDuration,
		'-ms-animation-duration': settings.animationDuration,
		'animation-duration': settings.animationDuration
	};

	if (id) {
		id.classList.add('photonicModal');
		id.classList.add(settings.modalTarget + '-off');
		let style = '';
		for (let [key, value] of Object.entries(initStyles)) {
			style += `${key}: ${value}; `;
		}
		id.style.cssText += style; // initStyles.reduce((a, v, i) => a + i + ': ' + v + ';');
		open(id);
	}

	closeIcon.addEventListener('click', function(event) {
		event.preventDefault();
		document.documentElement.style.overflow = 'auto';
		document.body.style.overflow = 'auto';

		if (id.classList.contains(settings.modalTarget+'-on')) {
			id.classList.remove(settings.modalTarget+'-on');
			id.classList.add(settings.modalTarget+'-off');
		}

		if (id.classList.contains(settings.modalTarget+'-off')) {
			id.addEventListener('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', afterClose, {once: true});
		}

		id.style.overflowY = 'hidden';
		slideUp(id);
		// Util.slideUpDown(id.closest('.photonicModalOverlayScrollable'), 'hide');
		overlay.style.overflowY = 'hidden';
		// Util.fadeOut(overlay);
		overlay.style.display = 'none';
	});

	function slideDown(element) {
		element.style.height = 'auto';
		element.style.height = `${element.scrollHeight}px`;
		element.style.height = 'auto';
	}

	const slideUp = element => {
		element.style.height = 0;
		element.style.display = 'none';
	}

	function open(el) {
		document.documentElement.style.overflow = 'hidden';
		document.body.style.overflow = 'hidden';

		if (el.classList.contains(settings.modalTarget+'-off')) {
			el.classList.remove(settings.modalTarget+'-off');
			el.classList.add(settings.modalTarget+'-on');
		}

		if (el.classList.contains(settings.modalTarget+'-on')) {
			el.style.opacity = settings.opacityIn;
			el.style.zIndex = settings.zIndexIn;
		}

		overlay.style.overflowY = settings.overflow;
		overlay.style.display = 'block';
		// Util.fadeIn(overlay);

		scrollable.appendChild(el);
		el.style.display = 'block';
		el.style.overflowY = settings.overflow;
		slideDown(scrollable);
		// Util.slideUpDown(scrollable, 'show');
	}

	function afterClose() {
		id.style.zIndex = settings.zIndexOut;
	}
};
