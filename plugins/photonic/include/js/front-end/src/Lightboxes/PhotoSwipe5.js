import {Lightbox} from "./Lightbox";
import * as Util from "../Util";
import {Core} from "../Core";

export class PhotonicPhotoSwipe5 extends Lightbox {
	constructor() {
		super();
	}

	soloImages() {
		const a = document.querySelectorAll('a[href]');
		const solos = Array.from(a).filter(elem => /(\.jpg|\.jpeg|\.bmp|\.gif|\.png)/i.test(elem.getAttribute('href')))
			.filter(elem => !elem.classList.contains('photonic-lb'));
		solos.forEach(solo => {
			solo.classList.add("photonic-photoswipe");
			solo.classList.add("photonic-photoswipe-solo");
		});
		return solos;
	};

	hostedVideo(a) {
		const html5 = a.getAttribute('href').match(new RegExp(/(\.mp4|\.webm|\.ogg)/i));
		let css = a.classList.contains('photonic-lb');

		if (html5 !== null && !css) {
			a.classList.add("photoswipe-html5-video");
			let videos = document.querySelector('#photonic-html5-videos');
			if (videos === null) {
				videos = document.createElement('div');
				videos.style.display = 'none';
				videos.setAttribute('id', 'photonic-html5-videos');
				document.body.appendChild(videos);
			}

			videos.insertAdjacentHTML('beforeend', '<div id="photonic-html5-video-' + this.videoIndex + '"><video controls preload="none"><source src="' + a.getAttribute('href') + '" type="video/mp4">Your browser does not support HTML5 video.</video></div>');

			a.setAttribute('data-html5-href', a.getAttribute('href'));
			a.setAttribute('href', '#photonic-html5-video-' + this.videoIndex);
			if (!a.getAttribute('id')) {
				a.setAttribute('id', 'photonic-html5-video-a-' + this.videoIndex);
			}
			this.videoIndex++;
		}
	};

	changeVideoURL(element, regular, embed) {
		element.setAttribute('href', embed);
	};

	modifyAdditionalVideoProperties(anchor) {
		if (anchor != null && anchor instanceof Element && anchor.tagName === 'A') {
			if (anchor.classList.contains('photoswipe5-video')) {
				anchor.classList.remove('photoswipe5-video');
				anchor.classList.add('photoswipe-video');
			}

			if (anchor.getAttribute('id') == null) {
				anchor.setAttribute('id', 'photonic-photoswipe-video-' + this.videoIndex);
				this.videoIndex++;
			}
		}
	}

	setLightboxCaption(lightbox) {
		const self = this;
		lightbox.on('uiRegister', function() {
			lightbox.pswp.ui.registerElement({
				name: 'custom-caption',
				order: 9,
				isButton: false,
				appendTo: 'root',
				html: 'View',
				onInit: (el, pswp) => {
					lightbox.pswp.on('change', () => {
						const currSlideElement = lightbox.pswp.currSlide.data.element;
						let captionHTML = '';
						if (currSlideElement) {
							const hiddenCaption = currSlideElement.getAttribute('data-title');
							if (hiddenCaption) {
								captionHTML = hiddenCaption;
							}
							else if (currSlideElement.getAttribute('title')) {
								captionHTML = currSlideElement.getAttribute('title');
							}
							else {
								captionHTML = currSlideElement.querySelector('img').getAttribute('alt');
							}
						}

						if (captionHTML) {
							el.innerHTML = '<div class="photonic-pswp-caption">' + captionHTML + '</div>';
						}
						else {
							el.innerHTML = '';
						}
					});
				}
			});

			lightbox.pswp.ui.registerElement({
				name: 'download-button',
				order: 8,
				isButton: true,
				tagName: 'a',

				// SVG with outline
				html: {
					isCustomSVG: true,
					inner: '<path d="M18.62 17.09V19H5.38v-1.91zm-2.97-6.96L17 11.45l-5 4.87-5-4.87 1.36-1.32 2.68 2.64V5h1.92v7.77z" id="pswp__icn-download"/>',
					outlineID: 'pswp__icn-download'
				},

				onInit: (el, pswp) => {
					el.setAttribute('download', '');
					el.setAttribute('target', '_blank');
					el.setAttribute('rel', 'noopener');

					pswp.on('change', () => {
						if (pswp.currSlide.data.element) {
							el.href = pswp.currSlide.data.element.getAttribute('data-photonic-download') ? pswp.currSlide.data.element.getAttribute('data-photonic-download') : pswp.currSlide.data.src;
						}
					});
				}
			});

			lightbox.pswp.ui.registerElement({
				name: 'social-sharing',
				order: 7,
				isButton: false,
				html: 'Share',
				onInit: (el, pswp) => {
					lightbox.pswp.on('change', () => {
						if (Photonic_JS.social_media) {
							const currSlideElement = lightbox.pswp.currSlide.data.element;
							let url = location.href;
							if (currSlideElement) {
								let title = Util.getText(currSlideElement.getAttribute('data-title'));
								let image = currSlideElement.href;
								const social = self.socialIcons.replace(/{photonic_share_link}/g, encodeURIComponent(url))
									.replace(/{photonic_share_title}/g, encodeURIComponent(title))
									.replace(/{photonic_share_image}/g, encodeURIComponent(image));
								el.innerHTML = '<div class="photonic-pswp-social">' + social + '</div>';
							}
						}
						else {
							el.innerHTML = '';
						}
					});
				}
			});
		});
	}

	initialize(selector) {
		this.handleSolos();
		const self = this;

		let selection;
		if (selector == null) {
			selection = document.querySelectorAll('.photonic-level-1-container');
		}
		else if (selector instanceof NodeList) {
			selection = selector;
		}
		else if (selector instanceof Element) {
			selection = [selector];
		}
		else {
			selection = document.querySelectorAll(selector);
		}

		selection.forEach(function (current) {
			const galleryId = current.getAttribute('id');

			const lightbox = new PhotoSwipeLightbox({
				gallery: '#' + galleryId,
				children: 'a.photonic-lb',
				pswpModule: PhotoSwipe,
			});

			lightbox.on('itemData', (e) => {
				let thumbnail = e.itemData.element;
				if (thumbnail && thumbnail.dataset.photonicMediaType === 'video') {
					let href = thumbnail.getAttribute('href');
					let html = document.querySelector(href).cloneNode(true);
					html.removeAttribute('id');
					html.classList.add('photonic-video');
					e.itemData = {
						html: html.outerHTML
					}
				}

				self.setHash(thumbnail);
			});

			lightbox.on('close', () => {
				self.unsetHash();
			});

			self.setLightboxCaption(lightbox);

			lightbox.init();
		});

		document.querySelectorAll('.photonic-photoswipe-solo').forEach((current, idx) => {
			const href = current.href;
			let itemData = {
				src: href
			};
			if (current.getAttribute('data-pswp-width') && current.getAttribute('data-pswp-height')) {
				itemData.width = current.getAttribute('data-pswp-width');
				itemData.height = current.getAttribute('data-pswp-height');
			}

			const lightbox = new PhotoSwipeLightbox({
				dataSource: [
					itemData
				],
				pswpModule: PhotoSwipe,
			});

			lightbox.on('beforeOpen', (e) => {
				const { pswp } = lightbox;

				let itemWidth, itemHeight;

				function soloImageDim() {
					return new Promise(resolve => {
						const img = new Image();

						img.onload = () => {
							itemWidth = img.naturalWidth;
							itemHeight = img.naturalHeight;
							resolve();
						};

						img.src = href;
					});
				}

				async function waitForDim() {
					await soloImageDim();
				}

				waitForDim().then(() => {
					pswp.options.dataSource = [
						{
							src: href,
							width: itemWidth,
							height: itemHeight,
						},
					];
					pswp.refreshSlideContent(pswp.currSlide.index);
				});
			});

			self.setLightboxCaption(lightbox);

			lightbox.init();
			current.onclick = (e) => {
				e.preventDefault();
				lightbox.loadAndOpen(0);
			};
		});

		document.querySelectorAll('.photoswipe-video, .photoswipe-html5-video').forEach((current, idx) => {
			const href = current.href;
			const anchorId = current.getAttribute('id');

			const lightbox = new PhotoSwipeLightbox({
				gallery: '#' + anchorId,
				pswpModule: PhotoSwipe,
			});

			let html;
			if (current.classList.contains('photoswipe-video')) {
				html = '<div class="photonic-video"><iframe class="pswp__video" width="640" height="480" src="' + href + '" frameborder="0" allowfullscreen></iframe></div>';
			}
			else {
				html = document.querySelector(current.getAttribute('href')).cloneNode(true);
				html.removeAttribute('id');
				html.classList.add('photonic-video');
				html = html.outerHTML;
			}

			lightbox.on('itemData', (e) => {
				e.itemData = {
					html: html
				};
			});

			self.setLightboxCaption(lightbox);

			lightbox.init();
		});
	}

	initializeForNewContainer(containerId) {
		this.initialize(containerId);
	}
}
