import {Core} from "./Core";
import * as Util from "./Util";
import {JustifiedGrid} from "./Layouts/Justified";
import {Masonry} from "./Layouts/Masonry";
import {Mosaic} from "./Layouts/Mosaic";
import {Tooltip} from "./Components/Tooltip";
import {Modal} from "./Components/Modal";

let spinners = 0;

const bypassPopup = data => {
	Core.hideLoading();
	let panel;
	if (data instanceof Element) {
		panel = data;
	}
	else {
		panel = Util.getElement(data).firstElementChild;
	}

	Util.hide(panel);
	const images = panel.querySelectorAll('img');
	images.forEach(image => {
		if ((image.getAttribute('src') === null || image.getAttribute('src') === '') && image.getAttribute('data-src') !== null) {
			image.setAttribute('src', image.getAttribute('data-src'));
			image.removeAttribute('data-src');
		}
	});
	document.body.appendChild(panel);
	Core.moveHTML5External();
	const lightbox = Core.getLightbox();
	if (lightbox !== undefined && lightbox !== null) {
		lightbox.initializeForNewContainer('#' + panel.getAttribute('id'));
	}

	const thumbs = panel.querySelectorAll('.photonic-lb');
	if (thumbs.length > 0) {
		Core.setDeep('#' + thumbs[0].getAttribute('data-photonic-deep'));

		const evt = new MouseEvent('click', {
			bubbles: true,
			cancelable: true,
			view: window
		});
		// If cancelled, don't dispatch our event
		!thumbs[0].dispatchEvent(evt);
	}
};

const displayPopup = (data, provider, popup, panelId) => {
	const safePanelId = panelId.replace('.', '\\.'); // FOR EXISTING ELEMENTS WHICH NEED SANITIZED PANELID
	const div = Util.getElement(data).firstElementChild;
	const grid = div.querySelector('.modal-gallery');

	const doImageLayout = () => {
		const popupPanel = document.querySelector('#photonic-' + provider + '-' + popup + '-' + safePanelId);
		if (popupPanel) {
			popupPanel.appendChild(div);
			Util.show(popupPanel);
		}

		Modal(div, {
			modalTarget: 'photonic-' + provider + '-panel-' + safePanelId,
			color: '#000',
			width: Photonic_JS.gallery_panel_width + '%',
			closeFromRight: ((100 - Photonic_JS.gallery_panel_width) / 2) + '%'
		});
		Core.moveHTML5External();
		const lightbox = Core.getLightbox();
		if (lightbox !== undefined && lightbox !== null) {
			lightbox.initializeForNewContainer('#' + div.getAttribute('id'));
		}

		Tooltip('[data-photonic-tooltip]', '.photonic-tooltip-container');
		Core.hideLoading();
	};

	if (grid.classList.contains('sizes-present')) {
		Core.watchForImages(grid);
		doImageLayout();
	}
	else {
		Core.waitForImages(grid).then(() => {
			doImageLayout();
		});
	}
};

const redisplayPopupContents = (provider, panelId, panel, args) => {
	const panelEl = Util.getElement(panel);
	if ('show' === args['popup']) {
		Core.hideLoading();
		Modal(panelEl, {
			modalTarget: 'photonic-' + provider + '-panel-' + panelId,
			color: '#000',
			width: Photonic_JS.gallery_panel_width + '%',
			closeFromRight: ((100 - Photonic_JS.gallery_panel_width) / 2) + '%'
		});
	}
	else {
		bypassPopup(document.getElementById('photonic-' + provider + '-panel-' + panelId));
	}
};

export const processRequest = (provider, type, identifier, args) => {
	args['action'] = 'photonic_display_level_2_contents';
	Util.post(Photonic_JS.ajaxurl, args, function(data) {
		if (data.substr(0, Photonic_JS.password_failed.length) === Photonic_JS.password_failed) {
			Core.hideLoading();
			const prompter = '#photonic-' + provider + '-' + type + '-prompter-' + identifier;
			const prompterDialog = Core.prompterList[prompter];
			if (prompterDialog !== undefined && prompterDialog !== null) {
				prompterDialog.show();
			}
		}
		else {
			if ('show' === args['popup']) {
				displayPopup(data, provider, type, identifier);
			}
			else {
				if (data !== '') {
					bypassPopup(data);
				}
				else {
					Core.hideLoading();
				}
			}
		}
	});
};

export const displayLevel2 = (provider, type, args) => {
	const identifier = args['panel_id'].substr(('photonic-' + provider + '-' + type + '-thumb-').length);
	const panel = '#photonic-' + provider + '-panel-' + identifier;

	let existing = document.getElementById('photonic-' + provider + '-panel-' + identifier);

	if (existing == null) {
		existing = document.getElementById(args['panel_id']);
		if (existing.classList.contains('photonic-' + provider + '-passworded')) {
			Core.initializePasswordPrompter(`#photonic-${provider}-${type}-prompter-${identifier}`);
		}
		else {
			Core.showSpinner();
			processRequest(provider, type, identifier, args);
		}
	}
	else {
		Core.showSpinner();
		redisplayPopupContents(provider, identifier, panel, args);
	}
};

export const processL3Request = (clicked, header, args) => {
	args['action'] = 'photonic_display_level_3_contents';
	Core.showSpinner();
	const lightbox = Core.getLightbox();
	Util.post(Photonic_JS.ajaxurl, args, function(data){
		const insert = Util.getElement(data);
		if (header) {
			const layout = insert.querySelector('.photonic-level-2-container');

			const container = header.parentNode;
			let returnedStream = insert.firstElementChild;
			const collectionId = args.node.substr('flickr-collection-'.length);
			returnedStream.setAttribute('id', args.stream + '-' + collectionId);
			container.insertBefore(returnedStream, header.nextSibling);

			if (layout.classList.contains('photonic-random-layout')) {
				JustifiedGrid(false, false, null, lightbox);
			}
			else if (layout.classList.contains('photonic-mosaic-layout')) {
				Mosaic(false, false);
			}
			else if (layout.classList.contains('photonic-masonry-layout')) {
				Masonry(false, false);
			}

			const level2 = returnedStream.querySelectorAll('.photonic-level-2');

			level2.forEach(function(item) {
				item.style.display = 'inline-block';
			});

			Tooltip('[data-photonic-tooltip]', '.photonic-tooltip-container');

			clicked.classList.remove('photonic-level-3-expand-plus');
			clicked.classList.add('photonic-level-3-expand-up');
			clicked.setAttribute('title', Photonic_JS.minimize_panel === undefined ? 'Hide' : Photonic_JS.minimize_panel);
		}
		Core.hideLoading();
	});
};

export const lazyLoad = evt => {
	spinners++;
	Core.showSpinner();
	const clicked = evt.currentTarget;
	const shortcode = clicked.getAttribute('data-photonic-shortcode');
	const args = {
		'action': 'photonic_lazy_load',
		'shortcode': shortcode
	};

	const countdownSpinners = () => {
		spinners--;

		if (spinners <= 0) {
			Core.hideLoading();
		}
	};

	Util.post(Photonic_JS.ajaxurl, args, data => {
		let div = document.createElement('div');
		div.innerHTML = data;
		div = div.firstElementChild;
		if (div) {
			const divId = div.getAttribute('id');
			const divClass = divId.substring(0, divId.lastIndexOf('-'));

			const streams = document.documentElement.querySelectorAll('.' + divClass);
			let max = 0;
			streams.forEach(stream => {
				let streamId = stream.getAttribute('id');
				streamId = streamId.substring(streamId.lastIndexOf('-') + 1);
				streamId = parseInt(streamId, 10);
				max = Math.max(max, streamId);
			});
			max = max + 1;

			const regex = new RegExp(divId, 'gi');
			div.innerHTML = data.replace(regex, divClass + '-' + max)
				.replace('photonic-slideshow-' + divId.substring(divId.lastIndexOf('-') + 1), 'photonic-slideshow-' + max);
			div = div.firstElementChild;
			// Level 2 elements get their own ids, which need to be readjusted because the back-end always assigns them a gallery_index of 1
			div.querySelectorAll('figure.photonic-level-2').forEach((figure) => {
				if (figure.getAttribute('id') != null) {
					let figId = figure.getAttribute('id');
					let modId = figId.substring(0, figId.lastIndexOf('-') + 1) + max; // Replace last part of id with the "max"
					figure.setAttribute('id', modId);

					let anchor = figure.querySelector('a');
					if (anchor.getAttribute('id') != null) {
						let anchorId = anchor.getAttribute('id');
						let modAnchorId = anchorId.substring(0, anchorId.lastIndexOf('-') + 1) + max; // Replace last part of id with the "max"
						anchor.setAttribute('id', modAnchorId);
					}

					let prompter = figure.querySelector('.photonic-password-prompter');
					if (prompter != null && prompter.getAttribute('id') != null) {
						let prompterId = prompter.getAttribute('id');
						let modPrompterId = prompterId.substring(0, prompterId.lastIndexOf('-') + 1) + max; // Replace last part of id with the "max"
						prompter.setAttribute('id', modPrompterId);
					}
				}
			});

			clicked.insertAdjacentElement('afterend', div);

			const newDivId = divClass + '-' + max;

			let lightbox = Core.getLightbox();
			if (lightbox !== undefined && lightbox !== null) {
				lightbox.initializeForNewContainer('#' + div.getAttribute('id'));
			}

			if (document.querySelectorAll('#' + newDivId + ' .photonic-random-layout').length > 0) {
				JustifiedGrid(false, true, '#' + newDivId + ' .photonic-random-layout', lightbox);
				spinners--;
			}
			else if (document.querySelectorAll('#' + newDivId + ' .photonic-masonry-layout').length > 0) {
				Masonry(false, true, '#' + newDivId + ' .photonic-masonry-layout');
				spinners--;
			}
			else if (document.querySelectorAll('#' + newDivId + ' .photonic-mosaic-layout').length > 0) {
				Mosaic(false, true, '#' + newDivId + ' .photonic-mosaic-layout');
				spinners--;
			}
			// Slider(document.querySelector('#photonic-slideshow-' + max));

			if (div.classList.contains('sizes-present') || (div.querySelector('.sizes-present') !== null)) {
				Core.watchForImages(div);
				Core.standardizeTitleWidths();
				countdownSpinners();
			}
			else {
				Core.waitForImages(div).then(() => {
					const standard = document.documentElement.querySelectorAll('#' + newDivId + ' .photonic-standard-layout .photonic-level-1, ' + '#' + newDivId + ' .photonic-standard-layout .photonic-level-2');
					standard.forEach(image => {
						image.style.display = 'inline-block';
					});
					Core.standardizeTitleWidths();
					countdownSpinners();
				});
			}
			Core.moveHTML5External();

			clicked.parentNode.removeChild(clicked);

			Tooltip('[data-photonic-tooltip]', '.photonic-tooltip-container');

			if (spinners <= 0) {
				Core.hideLoading();
			}
		}
	});
};

