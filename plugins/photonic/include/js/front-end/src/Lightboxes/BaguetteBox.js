import {Lightbox} from "./Lightbox";
import * as Util from "../Util";

export class PhotonicBaguetteBox extends Lightbox {
	constructor() {
		super();
	}

	soloImages() {
		const solos = super.soloImages();
		let idIndex = 1;
		solos.forEach(solo => {
			if (solo.getAttribute('id') == null) {
				solo.setAttribute('id', 'photonic-baguettebox-' + idIndex);
				idIndex++;
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


	modifyAdditionalVideoProperties(anchor) {
		if (anchor != null && anchor instanceof Element && anchor.tagName === 'A') {
			anchor.setAttribute('data-content-type', 'video');
			if (anchor.getAttribute('id') == null) {
				anchor.setAttribute('id', 'photonic-baguettebox-video-' + this.videoIndex);
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

		if (destroy) {
			baguetteBox.destroy();
		}

		selection.forEach(function (current) {
			const galleryId = current.getAttribute('id');
			baguetteBox.run('#' + galleryId, {
				captions: (a) => {
					return a.getAttribute('data-title') ? a.getAttribute('data-title') : (a.getAttribute('title') ? a.getAttribute('title') : '');
				},
				onChange: (idx, count) => {
					const links = current.querySelectorAll('.photonic-lb');
					const a = links.item(idx);
					if (a != null) {
						self.setHash(a);

						const shareable = {
							'url': location.href,
							'title': Util.getText(a.getAttribute('data-title')),
							'image': a.getAttribute('href')
						};
						self.addSocial('#baguetteBox-overlay', shareable);
					}
				},
				afterHide: () => {
					self.unsetHash();
				},
			});
		});
	};

	initializeForNewContainer(containerId) {
		this.initialize(containerId);
	}
}
