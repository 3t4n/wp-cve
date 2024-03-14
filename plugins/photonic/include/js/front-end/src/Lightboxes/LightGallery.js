import {Core} from "../Core";
import {Lightbox} from "./Lightbox";
import * as Util from "../Util";

export class PhotonicLightGallery extends Lightbox {
	constructor() {
		super();
	}

	soloImages() {
		const a = document.querySelectorAll('a[href]');
		const solos = Array.from(a).filter(elem => /(\.jpg|\.jpeg|\.bmp|\.gif|\.png)/i.test(elem.getAttribute('href')))
			.filter(elem => !elem.classList.contains('photonic-lb'));
		solos.forEach(solo => {
			solo.classList.add("photonic-" + Photonic_JS.lightbox_library);
			solo.classList.add(Photonic_JS.lightbox_library);
			solo.setAttribute('rel', 'photonic-' + Photonic_JS.lightbox_library);
		});
	};

	changeVideoURL(element, regular, embed) {
		element.setAttribute('href', regular);
	};

	hostedVideo(a) {
		const html5 = a.getAttribute('href').match(new RegExp(/(\.mp4|\.webm|\.ogg)/i)),
			css = a.classList.contains('photonic-lb');

		if (html5 !== null && !css) {
			let videoSrc = {
				"source": [
					{
						"src": a.getAttribute('href'),
						"type": "video/mp4",
						"attributes": {
							"preload": false,
							"playsinline": true,
							"controls": true
						}
					}
				]
			}

			a.classList.add(Photonic_JS.lightbox_library + "-html5-video");

			a.setAttribute('data-html5-href', a.getAttribute('href'));
			a.setAttribute('href', '');
			a.setAttribute('data-video', JSON.stringify(videoSrc));
			a.setAttribute('data-sub-html', (a.getAttribute('title') ? a.getAttribute('title') : ''));

			this.videoIndex++;
		}
	};

	initialize(selector, selfSelect) {
		this.handleSolos();
		const self = this;

		let selection;
		if (selector instanceof NodeList) {
			selection = selector;
		}
		else if (selector instanceof Element) {
			selection = [selector];
		}
		else {
			selection = document.querySelectorAll(selector);
		}

		const plugins = Photonic_JS.lg_plugins.split(',');

		selection.forEach(function (current) {
			const lguid = current.getAttribute('lg-uid');
			if (lguid != null && lguid !== '') {
				window.lgData[lguid].destroy(true);
			}

			let thumbs;
			thumbs = current.querySelectorAll('a.photonic-lightgallery');
			let rel = '';
			if (thumbs.length > 0) {
				rel = thumbs[0].getAttribute('rel');
			}

			current.addEventListener('lgAfterSlide', event => {
				thumbs = current.querySelectorAll('a.photonic-lightgallery'); // Need to fetch again, since the next line causes issues after "Add More".
				let thumb;
				if (thumbs.length !== 0) {
					thumb = thumbs[event.detail.index];
				}
				else if (current.classList.contains('photonic-lightgallery')) {
					thumb = current;
				}

				let lgId = event.target.getAttribute('data-photonic-lg-uid');
				if (thumb != null && lgId != null) {
					self.setHash(thumb);
					const shareable = {
						'url': location.href,
						'title': Util.getText(thumb.getAttribute('data-title')),
						'image': thumb.getAttribute('href')
					};
					self.addSocial('#lg-toolbar-' + lgId, shareable);
				}
			}, false);

			current.addEventListener('lgAfterClose', function () {
				self.unsetHash();
			});

			let options = {
				selector: (selfSelect === undefined || !selfSelect) ? 'a[rel="' + rel + '"]' : 'this',
				speed: 500,
				counter: selfSelect === undefined || !selfSelect,
				pause: Photonic_JS.slideshow_interval,
				mode: Photonic_JS.lg_transition_effect,
				download: Photonic_JS.lg_enable_download,
				loop: Photonic_JS.lightbox_loop,
				hideBarsDelay: Photonic_JS.lg_hide_bars_delay,
				getCaptionFromTitleOrAlt: false,
				mobileSettings: {
					controls: Photonic_JS.lg_mobile_controls,
					showCloseIcon: Photonic_JS.lg_mobile_close,
					download: Photonic_JS.lg_mobile_download
				}
			};

			let lgPlugins = [lgVideo];

			if (plugins.indexOf('thumbnail') > -1) {
				lgPlugins.push(lgThumbnail);
				options.thumbnail = true;
			}

			if (plugins.indexOf('zoom') > -1) {
				lgPlugins.push(lgZoom);
			}

			if (plugins.indexOf('fullscreen') > -1) {
				lgPlugins.push(lgFullscreen);
			}

			if (plugins.indexOf('autoplay') > -1) {
				lgPlugins.push(lgAutoplay);
				options.slideShowInterval = Photonic_JS.lg_transition_speed;
			}

			options.plugins = lgPlugins;

			const lightbox = lightGallery(current,options);
			if (!selfSelect === undefined || !selfSelect) {
				Core.addToLightboxList(rel, lightbox);
			}
			current.setAttribute('data-photonic-lg-uid', lightbox.lgId);
		});
	};

	initializeForNewContainer(containerId) {
		this.initialize(containerId);
	};
}
