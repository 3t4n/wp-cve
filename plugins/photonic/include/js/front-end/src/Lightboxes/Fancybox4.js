import {Lightbox} from "./Lightbox";
import * as Util from "../Util";

export class PhotonicFancybox4 extends Lightbox {
	constructor() {
		super();
	}

	soloImages() {
		const solos = super.soloImages();
		let idIndex = 1;
		solos.forEach(solo => {
			if (solo.getAttribute('id') == null) {
				solo.setAttribute('id', 'photonic-fancybox4-' + idIndex);
				idIndex++;
			}
			if (solo.getAttribute('data-fancybox') == null) {
				solo.setAttribute('data-fancybox', 'photonic-solo-gallery');
			}
		});
	};

	hostedVideo(a) {
		const html5 = a.getAttribute('href').match(new RegExp(/(\.mp4|\.webm|\.ogg)/i)),
			css = a.classList.contains('photonic-lb');

		if (html5 !== null && !css) {
			a.classList.add(Photonic_JS.lightbox_library + "-html5-video");
			this.videoIndex++;
		}
	};

	initialize(selector, group) {
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

		selection.forEach((current) => {
			const galleryId = current.getAttribute('id');
			Fancybox.bind('#' + galleryId + ' a.photonic-lb', {
				groupAll: true,
			});
		});
	};

	initializeForNewContainer(containerId) {
		this.initialize(containerId);
	};

}
