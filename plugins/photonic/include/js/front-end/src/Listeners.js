import {Core} from "./Core";
import * as Util from "./Util";
import * as Requests from "./Requests";
import {JustifiedGrid} from "./Layouts/Justified";
import {Mosaic} from "./Layouts/Mosaic";
import {Tooltip} from "./Components/Tooltip";

// .photonic-level-2-thumb:not(".gallery-page")
export const addLevel2ClickListener = () => {
	document.addEventListener('click', e => {
		if (!(e.target instanceof Element) || !e.target.closest('.photonic-level-2-thumb') || Photonic_JS.lightbox_library === 'none') {
			return;
		}

		const clicked = e.target.closest('.photonic-level-2-thumb');
		if (Util.hasClass(clicked, 'gallery-page')) {
			return;
		}

		e.preventDefault();
		const container = clicked.closest('.photonic-level-2-container');

		const galleryData = JSON.parse(container.getAttribute('data-photonic'));
		const provider = galleryData['platform'],
			singular = galleryData['singular'],
			query = container.getAttribute('data-photonic-query');

		const args = {
			"panel_id": clicked.getAttribute('id'),
			"popup": galleryData['popup'],
			"photo_count": galleryData['photo-count'],
			"photo_more": galleryData['photo-more'] || '',
			"query": query
		};

		if (provider === 'google' || provider === 'zenfolio') args.thumb_size = galleryData['thumb-size'];
		if (provider === 'flickr' || provider === 'smug' || provider === 'google' || provider === 'zenfolio') {
			args.overlay_size = galleryData['overlay-size'];
			args.overlay_video_size = galleryData['overlay-video-size'];
		}
		if (provider === 'google') {
			args.overlay_crop = galleryData['overlay-crop'];
		}
		Requests.displayLevel2(provider, singular, args);
	}, false);
};

// .photonic-password-submit
export const addPasswordSubmitListener = () => {
	document.addEventListener('click', e => {
		if (!(e.target instanceof Element) || !e.target.closest('.photonic-password-submit')) {
			return;
		}

		e.preventDefault();
		const clicked = e.target.closest('.photonic-password-submit');
		const modal = clicked.closest('.photonic-password-prompter'),
			container = clicked.closest('.photonic-level-2-container');

		const galleryData = JSON.parse(container.getAttribute('data-photonic'));

		let album_id = modal.getAttribute('id');
		const components = album_id.split('-');
		const provider = galleryData['platform'],
			singular_type = galleryData['singular'],
			album_key = components.slice(4).join('-'),
			thumb_id = `photonic-${provider}-${singular_type}-thumb-${album_key}`,
			thumb = document.getElementById(`${thumb_id}`),
			query = container.getAttribute('data-photonic-query');

		let password = modal.querySelector('input[name="photonic-' + provider + '-password"]');
		password = password.value;

		let prompter = Core.prompterList[`#photonic-${provider}-${singular_type}-prompter-${album_key}`];
		if (prompter !== undefined && prompter !== null) {
			prompter.hide();
		}

		Core.showSpinner();
		const args = {
			'panel_id': thumb_id,
			"popup": galleryData['popup'],
			"photo_count": galleryData['photo-count'],
			"photo_more": galleryData['photo-more'] || '',
			"query": query
		};
		if (provider === 'smug') {
			args.password = password;
			args.overlay_size = galleryData['overlay-size'];
		}
		else if (provider === 'zenfolio') {
			args.password = password;
			args.realm_id = thumb.getAttribute('data-photonic-realm');
			args.thumb_size = galleryData['thumb-size'];
			args.overlay_size = galleryData['overlay-size'];
			args.overlay_video_size = galleryData['overlay-video-size'];
		}
		Requests.processRequest(provider, singular_type, album_key, args);

	}, false);
};

// a.photonic-level-3-expand
export const addLevel3ExpandListener = () => {
	document.addEventListener('click', e => {
		if (!(e.target instanceof Element) || !e.target.closest('a.photonic-level-3-expand')) {
			return;
		}

		e.preventDefault();
		const current = e.target.closest('a.photonic-level-3-expand'),
			header = current.parentNode.parentNode.parentNode,
			stream = header.parentNode;

		if (current.classList.contains('photonic-level-3-expand-plus')) {
			Requests.processL3Request(current, header, {'view': 'collections', 'node': current.getAttribute('data-photonic-level-3'), 'layout': current.getAttribute('data-photonic-layout'), 'stream': stream.getAttribute('id')});
		}
		else if (current.classList.contains('photonic-level-3-expand-up')) {
			const display = Util.next(header, '.photonic-stream');
			Util.slideUpDown(display, 'hide');
			current.classList.remove('photonic-level-3-expand-up');
			current.classList.add('photonic-level-3-expand-down');
			current.setAttribute('title', Photonic_JS.maximize_panel === undefined ? 'Show' : Photonic_JS.maximize_panel);
		}
		else if (current.classList.contains('photonic-level-3-expand-down')) {
			const display = Util.next(header, '.photonic-stream');
			// Util.slideDown(display);
			Util.slideUpDown(display, 'show');
			current.classList.remove('photonic-level-3-expand-down');
			current.classList.add('photonic-level-3-expand-up');
			current.setAttribute('title', Photonic_JS.minimize_panel === undefined ? 'Hide' : Photonic_JS.minimize_panel);
		}
	}, false);
};

// a.photonic-more-button.photonic-more-dynamic
export const addMoreButtonListener = () => {
	document.addEventListener('click', e => {
		if (!(e.target instanceof Element) || !e.target.closest('a.photonic-more-button.photonic-more-dynamic')) {
			return;
		}

		e.preventDefault();
		const clicked = e.target.closest('a.photonic-more-button.photonic-more-dynamic');
		const container = clicked.parentNode.querySelector('.photonic-level-1-container, .photonic-level-2-container');
		const query = container.getAttribute('data-photonic-query'),
			provider = container.getAttribute('data-photonic-platform'),
			level = container.classList.contains('photonic-level-1-container') ? 'level-1' : 'level-2',
			containerId = container.getAttribute('id');

		Core.showSpinner();
		Util.post(Photonic_JS.ajaxurl, { 'action': 'photonic_load_more', 'provider': provider, 'query': query }, data => {
			const ret = Util.getElement(data),
				images = ret.querySelectorAll(`.photonic-${level}`),
				more_button = ret.querySelector('.photonic-more-button'),
				one_existing = container.querySelector('a.photonic-lb');

			let anchors = [];
			if (one_existing !== null) {
				images.forEach((image) => {
					const a = image.querySelector('a');
					if (a !== null) {
						a.setAttribute('rel', one_existing.getAttribute('rel'));
						if (a.getAttribute('data-fancybox') != null) {
							a.setAttribute('data-fancybox', one_existing.getAttribute('data-fancybox'));
						}
						else if (a.getAttribute('data-rel') != null) {
							a.setAttribute('data-rel', one_existing.getAttribute('data-rel'));
						}
						else if (a.getAttribute('data-strip-group') != null) {
							a.setAttribute('data-strip-group', one_existing.getAttribute('data-strip-group'));
						}
						else if (a.getAttribute('data-gall') != null) {
							a.setAttribute('data-gall', one_existing.getAttribute('data-gall'));
						}
						anchors.push(a);
					}
				});
			}

			// Can't do this above, which is only for L1
			images.forEach(image => container.appendChild(image));

			Core.moveHTML5External();

			if (images.length === 0) {
				Core.hideLoading();
				Util.fadeOut(clicked);
				clicked.remove();
			}

			let lightbox = Core.getLightbox();

			if (Photonic_JS.lightbox_library === 'imagelightbox') {
				if (one_existing != null) {
					lightbox = Core.getLightboxList()['a[rel="' + one_existing.getAttribute('rel') + '"]'];
					if (level === 'level-1') {
						lightbox.addToImageLightbox(anchors);
					}
				}
			}
			else if (Photonic_JS.lightbox_library === 'lightcase') {
				if (one_existing != null) {
					lightbox.initialize('a[data-rel="' + one_existing.getAttribute('data-rel') + '"]');
				}
			}
			else if (Photonic_JS.lightbox_library === 'lightgallery') {
				if (one_existing !== null) {
					lightbox = Core.getLightboxList()[one_existing.getAttribute('rel')];

					let galleryItems = [...lightbox.galleryItems];
					images.forEach((image) => {
						let a = image.querySelector('a');
						let img = a.querySelector('img');
						let videoAttr;

						if (a.getAttribute('data-photonic-media-type') === 'video') {
							videoAttr = JSON.parse(a.getAttribute('data-video'));
						}

						galleryItems.push({
							"alt": img.getAttribute('alt'),
							"downloadUrl": a.getAttribute('data-download-url'),
							"src": (a.getAttribute('data-photonic-media-type') === 'video') ? videoAttr.source[0].src : a.getAttribute('href'),
							"subHtml": a.getAttribute('data-sub-html'),
							"thumb": img.getAttribute('src')
						});
					});

					lightbox.updateSlides(galleryItems, 0);
					lightbox.refresh();
				}
				else {
					lightbox.initialize(container);
				}
			}
			else if (Photonic_JS.lightbox_library === 'venobox') {
				if (one_existing !== null) {
					let gallId = one_existing.getAttribute('data-gall');
					if (one_existing.closest('.photonic-panel')) {
						gallId = one_existing.closest('.photonic-panel').getAttribute('id');
					}
					lightbox.initialize('#' + gallId, true);
				}
			}
			else if (['bigpicture', 'featherlight', 'glightbox', 'spotlight'].includes(Photonic_JS.lightbox_library)) {
				lightbox.initialize(container);
			}
			else if (Photonic_JS.lightbox_library === 'baguettebox') {
				lightbox.initialize(null, true);
			}
			else if (Photonic_JS.lightbox_library === 'fancybox3') {
				if (one_existing != null) {
					lightbox.initialize(null, one_existing.getAttribute('data-fancybox'));
				}
			}
			else if (Photonic_JS.lightbox_library === 'photoswipe') {
				lightbox.initialize();
			}

			function doImageLayout(images) {
				const new_query = ret.querySelector('.photonic-random-layout,.photonic-standard-layout,.photonic-masonry-layout,.photonic-mosaic-layout,.modal-gallery');
				if (new_query != null) {
					container.setAttribute('data-photonic-query', new_query.getAttribute('data-photonic-query'));
				}

				if (more_button == null) {
					Util.fadeOut(clicked);
					clicked.remove();
				}

				if (Util.hasClass(container, 'photonic-mosaic-layout')) {
					Mosaic(false, false, '#' + containerId);
				} else if (Util.hasClass(container, 'photonic-random-layout')) {
					JustifiedGrid(false, false, '#' + containerId, lightbox);
				} else if (Util.hasClass(container, 'photonic-masonry-layout')) {
					images.forEach(image => {
						const img = image.querySelector('img');
						Util.fadeIn(img);
						img.style.display = 'block';
					});
					Core.hideLoading();
				} else {
					container.querySelectorAll('.photonic-' + level).forEach(el => {
						el.style.display = 'inline-block';
					});
					Core.standardizeTitleWidths();
					Core.hideLoading();
				}

				Tooltip('[data-photonic-tooltip]', '.photonic-tooltip-container');
			}

			if (container.classList.contains('sizes-present')) {
				Core.watchForImages(images);
				doImageLayout(images);
			}
			else {
				Core.waitForImages(images).then(() => {
					doImageLayout(images);
				});
			}
		});
	});
};

// input[type="button"].photonic-helper-more
export const addHelperMoreButtonListener = () => {
	document.addEventListener('click', e => {
		if (!(e.target instanceof Element) || !e.target.closest('input[type="button"].photonic-helper-more')) {
			return;
		}

		e.preventDefault();
		Core.showSpinner();
		const clicked = e.target.closest('input[type="button"].photonic-helper-more');
		const table = clicked.closest('table');

		const nextToken = clicked.getAttribute('data-photonic-token') === undefined ? null : clicked.getAttribute('data-photonic-token'),
			provider = clicked.getAttribute('data-photonic-platform'), accessType = clicked.getAttribute('data-photonic-access');

		let args = {'action': 'photonic_helper_shortcode_more', 'provider': provider, 'access': accessType};
		if (nextToken) {
			args.nextPageToken = nextToken;
		}

		if (provider === 'google') {
			Util.post(Photonic_JS.ajaxurl, args, data => {
				let ret = Util.getElement(data);
				ret = Array.from(ret.getElementsByTagName('tr'));
				if (ret.length > 0) {
					const tr = clicked.closest('tr');
					if (tr) {
						tr.remove();
					}
					ret.forEach((node, i) => {
						if (i !== 0) {
							table.appendChild(node);
						}
					});
				}

				Tooltip('[data-photonic-tooltip]', '.photonic-tooltip-container');
				Core.hideLoading();
			});
		}
	});
};

export const addSlideUpEnterListener = () => {
	document.addEventListener('mouseover', e => {
		const slideup = '.title-display-hover-slideup-show a, .photonic-slideshow.title-display-hover-slideup-show li';

		if (e.target instanceof Element && e.target.closest(slideup)) {
			let node = e.target.closest(slideup);
			let title = node.querySelector('.photonic-title');
			Util.slideUpTitle(title, 'show');
			node.setAttribute('title', '');
		}
	}, true);
};

export const addSlideUpLeaveListener = () => {
	document.addEventListener('mouseout', e => {
		const slideup = '.title-display-hover-slideup-show a, .photonic-slideshow.title-display-hover-slideup-show li';

		if (e.target instanceof Element && e.target.closest(slideup)) {
			let node = e.target.closest(slideup);
			let title = node.querySelector('.photonic-title');
			Util.slideUpTitle(title, 'hide');
			node.setAttribute('title', Util.getText(node.getAttribute('data-title')));
		}
	}, true);
};

export const addLazyLoadListener = () => {
	let buttons = document.documentElement.querySelectorAll('input.photonic-show-gallery-button');
	Array.prototype.forEach.call(buttons, button => {
		button.addEventListener('click', Requests.lazyLoad);
	});

	buttons = document.documentElement.querySelectorAll('input.photonic-js-load-button');
	Array.prototype.forEach.call(buttons, button => {
		button.addEventListener('click', Requests.lazyLoad);
		button.click();
	});
};

export const addAllListeners = () => {
	addLevel2ClickListener();
	addPasswordSubmitListener();
	addLevel3ExpandListener();
	addMoreButtonListener();
	addHelperMoreButtonListener();
	addSlideUpEnterListener();
	addSlideUpLeaveListener();
	addLazyLoadListener();
};
