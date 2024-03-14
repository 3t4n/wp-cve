import {Lightbox} from "./Lightbox";
import * as Util from "../Util";

export class PhotonicBigPicture extends Lightbox {
	constructor() {
		super();
	}

	soloImages() {
		const solos = super.soloImages();
		solos.forEach(solo => {
			solo.addEventListener('click', e => {
				e.preventDefault();
				const img = solo.querySelector('img');
				let title = solo.getAttribute('data-title') || solo.getAttribute('title') || img.getAttribute('alt');
				if (title != null) {
					img.setAttribute('data-caption', title);
				}
				BigPicture({
					el: img,
				});
			});
		});
	};

	hostedVideo(a) {
		const self = this;
		const html5 = a.getAttribute('href').match(new RegExp(/(\.mp4|\.webm|\.ogg)/i)),
			css = a.classList.contains('photonic-lb');

		let options = {
			el: a,
			onError: () => {
				console.log('There was an error loading the video ' + a.getAttribute('href'));
			},
		};
		if (html5 !== null && !css) {
			a.classList.add(Photonic_JS.lightbox_library + "-html5-video");
			options.vidSrc = a.getAttribute('href');
		}
		else if (a.classList.contains(Photonic_JS.lightbox_library + '-video')) {
			// YouTube or Vimeo
			const youTube = self.catchYouTubeURL(a.getAttribute('href')),
				vimeo = self.catchVimeoURL(a.getAttribute('href'));
			if (youTube) {
				options.ytSrc = youTube.url;
			}
			else if (vimeo) {
				options.vimeoSrc = vimeo;
			}
		}
		if (Object.keys(options).length > 2) {
			if (a.getAttribute('title') != null) {
				a.setAttribute('data-caption', a.getAttribute('title'));
			}
			a.addEventListener('click', e => {
				e.preventDefault();
				BigPicture(options);
			});
		}
	};

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

		selection.forEach(current => {
			const galleryId = current.getAttribute('id');
			const items = current.querySelectorAll('.photonic-lb');
			items.forEach(item => {
				item.setAttribute('data-caption', item.getAttribute('data-title'));
				item.addEventListener('click', e => {
					e.preventDefault();
					let options = {
						el: item,
						gallery: '#' + galleryId,
						loop: Photonic_JS.lightbox_loop,
						onError: () => {
							console.log('There was an error loading the image, ' + item.getAttribute('href'))
						},
						animationStart: () => {
							self.setHash(item);
							const shareable = {
								'url': location.href,
								'title': Util.getText(item.getAttribute('data-title')),
								'image': item.getAttribute('href')
							};
							self.addSocial('#bp_container', shareable);
						},
						onChangeImage: (a) => {
							if (a.length === 2) {
								if (a[1].el && a[1].el.getAttribute('data-photonic-deep') !== undefined) {
									self.setHash(a[1].el);
									const shareable = {
										'url': location.href,
										'title': Util.getText(item.getAttribute('data-title')),
										'image': item.getAttribute('href')
									};
									self.addSocial('#bp_container', shareable);
								}
							}
						},
						onClose: () => {
							self.unsetHash();
						}
					};
					if (item.getAttribute('data-photonic-media-type') === 'video') {
						options.vidSrc = item.getAttribute('href');
					}

					BigPicture(options);
				});
			});
		});
	};

	initializeForNewContainer(containerId) {
		this.initialize(containerId);
	}
}
