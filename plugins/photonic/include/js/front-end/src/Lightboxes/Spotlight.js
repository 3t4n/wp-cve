import {Lightbox} from "./Lightbox";
import {Core} from "../Core";
import * as Util from "../Util";

export class PhotonicSpotlight extends Lightbox {
	constructor() {
		super();
	}

	hostedVideo(a) {
		const self = this;
		const html5 = a.getAttribute('href').match(new RegExp(/(\.mp4|\.webm|\.ogg)/i)),
			css = a.classList.contains('photonic-lb');

		if (html5 !== null && !css) {
			a.classList.add(Photonic_JS.lightbox_library + '-video');
			self.modifyAdditionalVideoProperties(a);
		}
	};

	changeVideoURL(element, regular, embed, poster) {
		element.setAttribute('href', embed);
		if (poster) {
			element.setAttribute('data-poster', poster);
		}
		element.classList.remove(Photonic_JS.lightbox_library + "-video");
		element.classList.add(Photonic_JS.lightbox_library + "-embed-video");
	};

	modifyAdditionalVideoProperties(anchor) {
		if (anchor != null && anchor instanceof Element && anchor.tagName === 'A') {
			if (anchor.getAttribute('data-poster') == null) {
				if (anchor.querySelector('img') != null) {
					anchor.setAttribute('data-poster', anchor.querySelector('img').getAttribute('src'));
				}
				else {
					anchor.setAttribute('data-poster', Photonic_JS.plugin_url + 'include/images/clear.png');
				}
			}

			if (anchor.getAttribute('data-src-mp4') == null) {
				anchor.setAttribute('data-src-mp4', anchor.getAttribute('href'));
			}
		}
	}

	initialize(selector) {
		this.handleSolos();
		const self = this;

		Spotlight; // Without this, a call to Spotlight's methods fails, for some reason.
		let spotlight = window.Spotlight;

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
			const links = current.querySelectorAll('.photonic-lb');
			if (links.length > 0) {
				const rel = links.item(0).getAttribute('rel'); // Get "rel" from first "a" element
				let gallery = [];
				links.forEach((link) => {
					if (link.getAttribute('data-photonic-media-type') === 'image') {
						gallery.push({
							src: link.getAttribute('href'),
							title: link.getAttribute('data-title'),
							deep: link.getAttribute('data-photonic-deep'),
						});
					}
					else {
						gallery.push({
							media: 'video',
							"src-mp4": link.getAttribute('href'),
							poster: link.getAttribute('data-poster'),
							title: link.getAttribute('data-title'),
							deep: link.getAttribute('data-photonic-deep'),
						});
					}
				});
				Core.addToLightboxList(rel, gallery);
			}
		});

		document.addEventListener('click', e => {
			if (!(e.target instanceof Element) || !e.target.closest('.photonic-lb')) {
				return;
			}
			e.preventDefault();

			const clicked = e.target.closest('.photonic-lb');
			const container = clicked.closest('.photonic-level-1-container');
			const siblings = container.querySelectorAll('.photonic-lb');
			const index = Array.from(siblings).indexOf(clicked);
			const rel = clicked.getAttribute('rel');
			const gallery = Core.getLightboxList()[rel];
			spotlight.show(gallery, {
				index: index + 1,
				infinite: Photonic_JS.lightbox_loop === '1',
				autoslide: Photonic_JS.slideshow_mode === '1',
				play: parseInt(Photonic_JS.slideshow_interval, 10) / 1000,
				download: Photonic_JS.sp_download === '1',
				autohide: Photonic_JS.sp_hide_bars === '1',
				onchange: (idx, options) => {
					const current = gallery[idx - 1]; // idx is 1-based, not 0-based
					const title = document.querySelector("#spotlight .spl-title");
					title.innerHTML = current.title;
					self.setHash(current.deep);

					const shareable = {
						'url': location.href,
						'title': Util.getText(current.title),
						'image': current.src
					};
					self.addSocial('#spotlight .spl-header', shareable);
				},
				onclose: (idx) => {
					self.unsetHash();
				}
			});
		}, false);

		document.addEventListener('click', e => {
			if (!(e.target instanceof Element) || !e.target.closest('.spotlight-embed-video')) {
				return;
			}
			e.preventDefault();

			const clicked = e.target.closest('.spotlight-embed-video');
			spotlight.show([{
				media: "node",
				src: (function(){
					const iframe = document.createElement("iframe");
					iframe.width = 560;
					iframe.height = 315;
					iframe.style = 'height: 315px; width: 560px;';
					iframe.src = clicked.href;
					return iframe;
				}())
			}]);
		}, false);

		document.addEventListener('click', e => {
			if (!(e.target instanceof Element) || !e.target.closest('.spotlight-video')) {
				return;
			}
			e.preventDefault();

			const clicked = e.target.closest('.spotlight-video');
			spotlight.show([{
				media: "video",
				"src-mp4": clicked.getAttribute('href'),
				poster: clicked.getAttribute('data-poster')
			}]);
		}, false);
	}


	initializeForNewContainer(containerId) {
		this.initialize(containerId);
	}
}
