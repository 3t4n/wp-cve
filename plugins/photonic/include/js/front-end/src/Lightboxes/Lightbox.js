export class Lightbox {
	deep;
	lastDeep;
	constructor() {
		this.socialIcons = "<div id='photonic-social'>" +
			"<a class='photonic-share-fb' href='https://www.facebook.com/sharer/sharer.php?u={photonic_share_link}&amp;title={photonic_share_title}&amp;picture={photonic_share_image}' target='_blank' title='Share on Facebook'><div class='icon-facebook'></div></a>" +
			"<a class='photonic-share-twitter' href='https://twitter.com/share?url={photonic_share_link}&amp;text={photonic_share_title}' target='_blank' title='Share on Twitter'><div class='icon-twitter'></div></a>" +
			"<a class='photonic-share-pinterest' data-pin-do='buttonPin' href='https://www.pinterest.com/pin/create/button/?url={photonic_share_link}&media={photonic_share_image}&description={photonic_share_title}' data-pin-custom='true' target='_blank' title='Share on Pinterest'><div class='icon-pinterest'></div></a>" +
			"</div>";
		this.videoIndex = 1;
	};

	getVideoSize(url, baseline) {
		return new Promise(resolve => {
			// create the video element
			const video = document.createElement('video');

			// place a listener on it
			video.addEventListener("loadedmetadata", function () {
				// retrieve dimensions
				const height = this.videoHeight,
					width = this.videoWidth;

				const videoAspectRatio = this.videoWidth / this.videoHeight,
					baseAspectRatio = baseline.width / baseline.height;

				let newWidth, newHeight;
				if (baseAspectRatio > videoAspectRatio) {
					// Window is wider than it needs to be ... constrain by window height
					newHeight = baseline.height;
					newWidth = width * newHeight / height;
				} else {
					// Window is narrower than it needs to be ... constrain by window width
					newWidth = baseline.width;
					newHeight = height * newWidth / width;
				}

				// send back result
				resolve({
					height: height,
					width: width,
					newHeight: newHeight,
					newWidth: newWidth
				});
			}, false);

			// start download meta-data
			video.src = url;
		});
	}

	getImageSize(url, baseline) {
		return new Promise(function (resolve) {
			const image = document.createElement('img');

			// place a listener on it
			image.addEventListener("load", function () {
				// retrieve dimensions
				const height = this.height,
					width = this.width,
					imageAspectRatio = this.width / this.height,
					baseAspectRatio = baseline.width / baseline.height;

				let newWidth, newHeight;
				if (baseAspectRatio > imageAspectRatio) {
					// Window is wider than it needs to be ... constrain by window height
					newHeight = baseline.height;
					newWidth = width * newHeight / height;
				} else {
					// Window is narrower than it needs to be ... constrain by window width
					newWidth = baseline.width;
					newHeight = height * newWidth / width;
				}

				// send back result
				resolve({
					height: height,
					width: width,
					newHeight: newHeight,
					newWidth: newWidth
				});
			}, false);

			// start download meta-data
			image.src = url;
		});
	}

	addSocial(selector, shareable, position) {
		if ((Photonic_JS.social_media === undefined || Photonic_JS.social_media === '') && shareable['buy'] === undefined) {
			return;
		}

		const socialEl = document.getElementById('photonic-social');
		if (socialEl !== null) {
			socialEl.parentNode.removeChild(socialEl);
		}

		let addTo;
		if (position === undefined || position === null) {
			addTo = 'beforeend';
		}
		else {
			addTo = position;
		}

		if (location.hash !== '') {
			const social = this.socialIcons.replace(/{photonic_share_link}/g, encodeURIComponent(shareable['url']))
				.replace(/{photonic_share_title}/g, encodeURIComponent(shareable['title']))
				.replace(/{photonic_share_image}/g, encodeURIComponent(shareable['image']));

			let selectorEl;
			if (typeof selector === 'string') {
				selectorEl = document.documentElement.querySelector(selector);
			}
			else {
				selectorEl = selector;
			}

			if (selectorEl !== null) {
				selectorEl.insertAdjacentHTML(addTo, social);
			}

			if (Photonic_JS.social_media === undefined || Photonic_JS.social_media === '') {
				const socialMediaIcons = document.documentElement.querySelectorAll('.photonic-share-fb, .photonic-share-twitter, .photonic-share-pinterest');
				Array.prototype.forEach.call(socialMediaIcons, function(socialIcon) {
					socialIcon.parentNode.removeChild(socialIcon);
				});
			}
		}
	};

	setHash(a) {
		if (Photonic_JS.deep_linking === undefined || Photonic_JS.deep_linking === 'none' || a === null || a === undefined) {
			return;
		}

		const hash = typeof a === 'string' ? a : a.getAttribute('data-photonic-deep');
		if (hash === undefined) {
			return;
		}

		if (typeof(window.history.pushState) === 'function' && Photonic_JS.deep_linking === 'yes-history') {
			window.history.pushState({}, document.title, '#' + hash);
		}
		else if (typeof(window.history.replaceState) === 'function' && Photonic_JS.deep_linking === 'no-history') {
			window.history.replaceState({}, document.title, '#' + hash);
		}
		else {
			document.location.hash = hash;
		}
	};

	unsetHash() {
		this.lastDeep = (this.lastDeep === undefined || this.deep !== '') ? location.hash : this.lastDeep;
		if (window.history && 'replaceState' in window.history) {
			history.replaceState({}, document.title, location.href.substr(0, location.href.length-location.hash.length));
		}
		else {
			window.location.hash = '';
		}
	};

	changeHash(e) {
		if (e.type === 'load') {
			let hash = window.location.hash;
			hash = hash.substr(1);
			if (hash && hash !== '') {
				const allMatches = document.querySelectorAll('[data-photonic-deep="' + hash + '"]');
				if (allMatches.length > 0) {
					const thumbToClick = allMatches[0];
					const event = document.createEvent('HTMLEvents');
					event.initEvent('click', true, false);
					thumbToClick.dispatchEvent(event);
				}
			}
		}
		else {

			let node = this.deep;

			if (node != null) {
				if (node.length > 1) {
					if (window.location.hash && node.indexOf('#access_token=') !== -1) {
						this.unsetHash();
					}
					else {
						node = node.substr(1);
						const allMatches = document.querySelectorAll('[data-photonic-deep="' + node + '"]');
						if (allMatches.length > 0) {
							const thumbToClick = allMatches[0];
							const event = document.createEvent('HTMLEvents');
							event.initEvent('click', true, false);
							thumbToClick.dispatchEvent(event);
							this.setHash(node);
						}
					}
				}
			}
		}
	};

	catchYouTubeURL(url) {
		const regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*)(?:(\?t|&t|\?start|&start)=(\d+))?.*/,
			match = url.match(regExp);
		if (match && match[2].length === 11) {
			let obj = {
				url: match[2]
			};
			if (match[4] !== undefined) {
				obj.timestamp = match[4];
			}
			return obj;
		}
	};

	catchVimeoURL(url) {
		const regExp = /(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|album\/(?:\d+)\/video\/|video\/|)(\d+)(?:[a-zA-Z0-9_\-]+)?/,
			match = url.match(regExp);
		if (match) {
			return match[1];
		}
	};

	soloImages() {
		const a = document.querySelectorAll('a[href]');
		const solos = Array.from(a).filter(elem => /(\.jpg|\.jpeg|\.bmp|\.gif|\.png)/i.test(elem.getAttribute('href')))
			.filter(elem => !elem.classList.contains('photonic-lb'));
		solos.forEach(solo => {
			solo.classList.add("photonic-" + Photonic_JS.lightbox_library);
			solo.classList.add("photonic-" + Photonic_JS.lightbox_library + '-solo');
			solo.classList.add(Photonic_JS.lightbox_library);
		});
		return solos;
	};

	changeVideoURL(element, regular, embed, poster) {
		// Implemented in individual lightboxes. Empty for unsupported lightboxes
	};

	hostedVideo(a) {
		// Implemented in individual lightboxes. Empty for unsupported lightboxes
	};

	soloVideos() {
		const self = this;
		if (Photonic_JS.lightbox_for_videos) {
			const a = document.querySelectorAll('a[href]');
			a.forEach(function(anchor) {
				let regular, embed, poster;
				const href = anchor.getAttribute('href'),
					youTube = self.catchYouTubeURL(href),
					vimeo = self.catchVimeoURL(href);
				if ((youTube) !== undefined) {
					let ytSrc = youTube.url, ytTimeStamp = youTube.timestamp, regularT = '', embedT = '';
					if (ytTimeStamp !== undefined) {
						regularT = '&t=' + ytTimeStamp;
						embedT = '?start=' + ytTimeStamp;
					}
					regular = 'https://youtube.com/watch?v=' + ytSrc + regularT;
					embed = 'https://youtube.com/embed/' + ytSrc + embedT;
					poster = 'https://img.youtube.com/vi/' + ytSrc + '/hddefault.jpg';
				}
				else if (vimeo !== undefined) {
					regular = 'https://vimeo.com/' + vimeo;
					embed = 'https://player.vimeo.com/video/' + vimeo;
				}

				if (regular !== undefined) {
					anchor.classList.add(Photonic_JS.lightbox_library + "-video");
					self.changeVideoURL(anchor, regular, embed, poster);
					self.modifyAdditionalVideoProperties(anchor);
				}
				self.hostedVideo(anchor);
			});
		}
	};

	handleSolos() {
		if (Photonic_JS.lightbox_for_all) {
			this.soloImages();
		}
		this.soloVideos();

		if (Photonic_JS.deep_linking !== undefined && Photonic_JS.deep_linking !== 'none') {
			window.addEventListener('load', this.changeHash);
			window.addEventListener('hashchange', this.changeHash);
		}
	};

	initialize() {
		this.handleSolos();
		// Implemented by child classes
	};

	initializeForNewContainer(containerId) {
		// Implemented by individual lightboxes. Empty for cases where not required
	};

	initializeForExisting() {
		// Implemented by child classes
	};

	modifyAdditionalVideoProperties(anchor) {
		// Implemented by individual lightboxes. Empty for cases where not required
	}
}
