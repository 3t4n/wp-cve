import {Core} from "../Core";
import {Lightbox} from "./Lightbox";

export class PhotonicVenoBox extends Lightbox {
	constructor() {
		super();
	}

	soloImages() {
		const solos = super.soloImages();
		let idIndex = 1;
		solos.forEach(solo => {
			if (solo.getAttribute('id') == null) {
				solo.setAttribute('id', 'photonic-venobox-' + idIndex);
				idIndex++;
			}
			if (solo.getAttribute('data-gall') == null) {
				solo.setAttribute('data-gall', 'photonic-solo-gallery');
			}
		});
	};

	hostedVideo(a) {
		const self = this;
		const html5 = a.getAttribute('href').match(new RegExp(/(\.mp4|\.webm|\.ogg)/i)),
			css = a.classList.contains('photonic-lb');

		if (html5 !== null && !css) {
			a.classList.add(Photonic_JS.lightbox_library + "-html5-video");
			self.modifyAdditionalVideoProperties(a);
		}
	};

	changeVideoURL(element, regular, embed) {
		element.setAttribute('href', embed); // Don't need this for Venobox, but embeds are the only type that support "start" or "t" parameters
	};

	modifyAdditionalVideoProperties(anchor) {
		if (anchor != null && anchor instanceof Element && anchor.tagName === 'A') {
			anchor.setAttribute('data-vbtype', 'video');
			if (anchor.getAttribute('id') == null) {
				anchor.setAttribute('id', 'photonic-venobox-video-' + this.videoIndex);
				this.videoIndex++;
			}
		}
	}

	initialize(selector, destroy) {
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

			if (destroy) {
			}

			const lightbox = new VenoBox({
				selector: '#' + galleryId + ' a.photonic-lb',
				titleattr: 'data-title',
				numeration: true,
				share: Photonic_JS.social_media,
				infinigall: Photonic_JS.lightbox_loop,
				customClass: Photonic_JS.vb_disable_vertical_scroll ? 'no-scroll' : '',
				titlePosition: Photonic_JS.vb_title_position,
				titleStyle: Photonic_JS.vb_title_style,
				onPreOpen: (a) => {
					self.setHash(a);
				},
				onNavComplete: (obj, idx, theNext, thePrev) => {
					self.setHash(obj);
				},
				onPreClose: () => {
					self.unsetHash();
				},
			});

			let thumbs;
			thumbs = current.querySelectorAll('a.photonic-lb');
			let rel = '';
			if (thumbs.length > 0) {
				rel = thumbs[0].getAttribute('data-gall');
				Core.addToLightboxList(rel, lightbox);
			}
		});

		if (document.querySelector('.photonic-venobox-solo, .venobox-video, .venobox-html5-video') && !destroy) {
			new VenoBox({
				selector: '.photonic-venobox-solo, .venobox-video, .venobox-html5-video',
				numeration: true,
				share: Photonic_JS.social_media,
				infinigall: Photonic_JS.lightbox_loop,
				customClass: Photonic_JS.vb_disable_vertical_scroll ? 'no-scroll' : '',
				titlePosition: Photonic_JS.vb_title_position,
				titleStyle: Photonic_JS.vb_title_style,
			});
		}
	};

	initializeForNewContainer(containerId) {
		this.initialize(containerId);
	}
}
