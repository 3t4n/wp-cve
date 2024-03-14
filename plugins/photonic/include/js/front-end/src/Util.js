// Utilities for Photonic
export const hasClass = (element, className) => {
	if (element.classList) {
		return element.classList.contains(className);
	}
	else {
		return new RegExp('(^| )' + className + '( |$)', 'gi').test(element.className);
	}
};

function ajax(method, url, args, callback) {
	const xhr = new XMLHttpRequest();
	xhr.open(method, url);
	xhr.onreadystatechange = function() {
		if (xhr.readyState === 4) {
			if (xhr.status === 200) {
				const data = xhr.responseText;
				callback(data);
			}
		}
	};
	let form = new FormData();
	for (const [key, value] of Object.entries(args)) {
		form.append(key, value);
	}
	xhr.send(form);
}

export const post = (url, args, callback) => {
	ajax('POST', url, args, callback);
};

export const get = (url, args, callback) => {
	ajax('GET', url, args, callback);
};

export const next = (elem, selector) => {
	let sibling = elem.nextElementSibling;

	if (!selector) return sibling;
	while (sibling) {
		if (sibling.matches(selector)) return sibling;
		sibling = sibling.nextElementSibling;
	}
};

export const getElement = value => {
	const parser = new DOMParser();
	const doc = parser.parseFromString(value, 'text/html');
	return doc.body;
};

export const getText = value => {
	const txt = document.createElement("div");
	txt.innerHTML = value;
	return txt.innerText;
};

export const slideUpDown = (element, state) => {
	if (element != null && element.classList) {
		if (!element.classList.contains('photonic-can-slide')) {
			element.classList.add('photonic-can-slide');
		}
		if ('show' === state) {
			element.classList.remove('photonic-can-slide-hide');
			element.style.height = `${element.scrollHeight}px`;
		}
		else {
			element.classList.add('photonic-can-slide-hide');
			element.style.height = 0
		}
	}
};

export const slideUpTitle = (element, state) => {
	if (element && element.classList) {
		if ('show' === state) {
			let currentPadding = 0;
			if (element.offsetHeight) {
				currentPadding = parseInt(getComputedStyle(element).paddingTop.slice(0, -2)) * 2;
			}
			element.style.height = (element.scrollHeight + 6 - currentPadding) + 'px';
			element.classList.add('slideup-show');
		}
		else {
			element.style.height = '';
			element.classList.remove('slideup-show');
		}
	}
}

export const fadeIn = (el) => {
	if (!hasClass(el, 'fade-in')) {
		el.style.display = 'block';
		el.style.visibility = 'visible';
		el.classList.add('fade-in');
	}
}

export const fadeOut = (el, duration) => {
	let s = el.style,
		step = 25/(duration || 500);
	s.opacity = s.opacity || 1;
	(function fade() {
		s.opacity -= step;
		if (s.opacity < 0) {
			s.display = "none";
			el.classList.remove('fade-in');
		}
		else {
			setTimeout(fade, 25);
		}
	})();
}

// get the default display style of an element
const defaultDisplay = tag => {
	const iframe = document.createElement('iframe');
	iframe.setAttribute('frameborder', 0);
	iframe.setAttribute('width', 0);
	iframe.setAttribute('height', 0);
	document.documentElement.appendChild(iframe);

	const doc = (iframe.contentWindow || iframe.contentDocument).document;

	// IE support
	doc.write();
	doc.close();

	const testEl = doc.createElement(tag);
	doc.documentElement.appendChild(testEl);
	const display = (window.getComputedStyle ? getComputedStyle(testEl, null) : testEl.currentStyle).display;
	iframe.parentNode.removeChild(iframe);
	return display;
};

// actual show/hide function used by show() and hide() below
const showHide = (el, show) => {
	let value = el.getAttribute('data-olddisplay'),
		display = el.style.display,
		computedDisplay = (window.getComputedStyle ? getComputedStyle(el, null) : el.currentStyle).display;

	if (show) {
		if (!value && display === 'none') el.style.display = '';
		if (el.style.display === '' && (computedDisplay === 'none')) value = value || defaultDisplay(el.nodeName);
	}
	else {
		if (display && display !== 'none' || !(computedDisplay === 'none'))
			el.setAttribute('data-olddisplay', (computedDisplay === 'none') ? display : computedDisplay);
	}
	if (!show || el.style.display === 'none' || el.style.display === '')
		el.style.display = show ? value || '' : 'none';
};

// helper functions
export const show = (el) => showHide(el, true);
export const hide = (el) => showHide(el);


