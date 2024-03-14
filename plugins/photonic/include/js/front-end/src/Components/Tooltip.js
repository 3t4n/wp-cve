/*
 * Photonic Tooltip
 * License: MIT
 */

export const Tooltip = function (selector, tooltip_element) {
	let tooltip, tooltipClass, elemEdges, tooltipElems;

	function create(tooltip, elm) {
		const tooltipText = elm.getAttribute('data-photonic-tooltip');
		if (tooltipText !== '') {
			elm.setAttribute('title', ''); // Blank out the regular title

			// elemEdges relative to the viewport.
			elemEdges = elm.getBoundingClientRect();

			const tooltipTextNode = document.createTextNode(tooltipText);
			tooltip.innerHTML = ''; // Reset, or upon refresh the node gets repeated
			tooltip.appendChild(tooltipTextNode);

			// Remove no-display + set the correct classname based on the position
			// of the elm.
			if (elemEdges.left > window.innerWidth - 100) {
				tooltip.className = 'photonic-tooltip-container tooltip-left';
			}
			else if ((elemEdges.left + (elemEdges.width / 2)) < 100) {
				tooltip.className = 'photonic-tooltip-container tooltip-right';
			}
			else {
				tooltip.className = 'photonic-tooltip-container tooltip-center';
			}
		}
	}

	function position(tooltip, elm) {
		const tooltipText = elm.getAttribute('data-photonic-tooltip');
		if (tooltipText !== '') {
			if (elemEdges === undefined) {
				elemEdges = elm.getBoundingClientRect();
			}

			// 10 = arrow height
			const elm_top = elemEdges.top + elemEdges.height + window.scrollY;
			const viewport_edges = window.innerWidth - 100;

			// Position tooltip on the left side of the elm if the elm touches
			// the viewports right edge and elm width is < 50px.
			if (elemEdges.left + window.scrollX > viewport_edges && elemEdges.width < 50) {
				tooltip.style.left = (elemEdges.left + window.scrollX - (tooltip.offsetWidth + elemEdges.width)) + 'px';
				tooltip.style.top = elm.offsetTop + 'px';
				// Position tooltip on the left side of the elm if the elm touches
				// the viewports right edge and elm width is > 50px.
			}
			else if (elemEdges.left + window.scrollX > viewport_edges && elemEdges.width > 50) {
				tooltip.style.left = (elemEdges.left + window.scrollX - tooltip.offsetWidth - 20) + 'px';
				tooltip.style.top = elm.offsetTop + 'px';
			}
			else if ((elemEdges.left + window.scrollX + (elemEdges.width / 2)) < 100) {
				// position tooltip on the right side of the elm.
				tooltip.style.left = (elemEdges.left + window.scrollX + elemEdges.width + 20) + 'px';
				tooltip.style.top = elm.offsetTop + 'px';
			}
			else {
				// Position the toolbox in the center of the elm.
				const centered = (elemEdges.left + window.scrollX + (elemEdges.width / 2)) - (tooltip.offsetWidth / 2);
				tooltip.style.left = centered + 'px';
				tooltip.style.top = elm_top + 'px';
			}
		}
	}

	function show(evt) {
		create(tooltip, evt.currentTarget);
		position(tooltip, evt.currentTarget);
	}

	function hide(evt) {
		tooltip.className = tooltipClass + ' no-display';
		if (tooltip.innerText !== '') {
			tooltip.removeChild(tooltip.firstChild);
			tooltip.removeAttribute('style');
			const element = evt.currentTarget;
			element.setAttribute('title', element.getAttribute('data-photonic-tooltip'));
		}
	}

	function init() {
		tooltipElems = document.documentElement.querySelectorAll(selector);
		tooltip = document.documentElement.querySelector(tooltip_element);
		tooltipClass = tooltip_element.replace(/^\.+/g, '');

		if (tooltip === null || tooltip.length === 0) {
			tooltip = document.createElement('div');
			tooltip.className = tooltipClass + ' no-display';
			document.body.appendChild(tooltip);
		}

		tooltipElems.forEach(elm => {
			elm.removeEventListener('mouseenter', show);
			elm.removeEventListener('mouseleave', hide);

			elm.addEventListener('mouseenter', show, false);
			elm.addEventListener('mouseleave', hide, false);
		});
	}

	init();
};

