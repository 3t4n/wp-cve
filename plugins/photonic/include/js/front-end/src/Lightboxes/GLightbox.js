import {Lightbox} from "./Lightbox";
import {Core} from "../Core";
import * as Util from "../Util";

export class PhotonicGLightbox extends Lightbox {
	constructor() {
		super();
	}

	hostedVideo(a) {
		const self = this;

		const html5 = a.getAttribute('href').match(new RegExp(/(\.mp4|\.webm|\.ogg)/i)),
			css = a.classList.contains('photonic-lb');

		if (html5 !== null && !css) {
			a.classList.add(Photonic_JS.lightbox_library + "-html5-video");
		}
	};

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

		selection.forEach((gallery) => {
			const galleryItems = gallery.querySelectorAll('.photonic-lb');
			if (galleryItems.length > 0) {
				const rel = galleryItems.item(0).getAttribute('rel'); // Get "rel" from first "a" element
				if (rel != null) {
					let lightbox;
					lightbox = Core.getLightboxList()[rel];
					if (lightbox) {
						lightbox.destroy();
					}
					lightbox = GLightbox({
						selector: '[rel="' + rel + '"]',
						loop: Photonic_JS.lightbox_loop,
					});
					lightbox.on('slide_changed', ({prev, current}) => {
						const idx = current.index;
						const thumb = galleryItems.item(idx);
						let social = document.querySelector('#photonic-social');
						if (social) {
							social.parentNode.removeChild(social);
						}
						self.setHash(thumb);

						const shareable = {
							'url': location.href,
							'title': Util.getText(thumb.getAttribute('data-title')),
							'image': thumb.getAttribute('href')
						};
						self.addSocial('.gslide.loaded.current .ginner-container', shareable);
					});
					lightbox.on('close', () => {
						self.unsetHash();
					});
					Core.addToLightboxList(rel, lightbox);
				}
			}
			else if (!gallery.classList.contains('photonic-level-2-container') && !gallery.querySelector('.photonic-level-2-container') && !gallery.querySelector('.photonic-tree')) { // Probably a solo item
				GLightbox({
					selector: selector,
				});
			}
		});
	};


	initializeForNewContainer(containerId) {
		this.initialize(containerId);
	}
}
