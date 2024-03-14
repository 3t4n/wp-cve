import {Lightbox} from "./Lightbox";
import * as Util from "../Util";

export class PhotonicPhotoSwipe extends Lightbox {
	constructor() {
		super();
		this.pswpSelector = '.pswp';
		this.videoSelector = 'a.photoswipe-video, a.photoswipe-html5-video';
		this.pswp = document.querySelector(this.pswpSelector);
		if (this.pswp === null) {
			this.pswp = '<!-- Root element of PhotoSwipe. Must have class pswp. -->\n' +
				'<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">\n' +
				'\n' +
				'    <!-- Background of PhotoSwipe. \n' +
				'         It\'s a separate element as animating opacity is faster than rgba(). -->\n' +
				'    <div class="pswp__bg"></div>\n' +
				'\n' +
				'    <!-- Slides wrapper with overflow:hidden. -->\n' +
				'    <div class="pswp__scroll-wrap">\n' +
				'\n' +
				'        <!-- Container that holds slides. \n' +
				'            PhotoSwipe keeps only 3 of them in the DOM to save memory.\n' +
				'            Don\'t modify these 3 pswp__item elements, data is added later on. -->\n' +
				'        <div class="pswp__container">\n' +
				'            <div class="pswp__item"></div>\n' +
				'            <div class="pswp__item"></div>\n' +
				'            <div class="pswp__item"></div>\n' +
				'        </div>\n' +
				'\n' +
				'        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->\n' +
				'        <div class="pswp__ui pswp__ui--hidden">\n' +
				'\n' +
				'            <div class="pswp__top-bar">\n' +
				'                <!--  Controls are self-explanatory. Order can be changed. -->\n' +
				'                <div class="pswp__counter"></div>\n' +
				'                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>\n' +
				'                <button class="pswp__button pswp__button--share" title="Share"></button>\n' +
				'                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>\n' +
				'                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>\n' +
				'\n' +
				'                <!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR -->\n' +
				'                <!-- element will get class pswp__preloader--active when preloader is running -->\n' +
				'                <div class="pswp__preloader">\n' +
				'                    <div class="pswp__preloader__icn">\n' +
				'                      <div class="pswp__preloader__cut">\n' +
				'                        <div class="pswp__preloader__donut"></div>\n' +
				'                      </div>\n' +
				'                    </div>\n' +
				'                </div>\n' +
				'            </div>\n' +
				'\n' +
				'            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">\n' +
				'                <div class="pswp__share-tooltip"></div> \n' +
				'            </div>\n' +
				'\n' +
				'            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">\n' +
				'            </button>\n' +
				'\n' +
				'            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">\n' +
				'            </button>\n' +
				'\n' +
				'            <div class="pswp__caption">\n' +
				'                <div class="pswp__caption__center"></div>\n' +
				'            </div>\n' +
				'\n' +
				'        </div>\n' +
				'\n' +
				'    </div>\n' +
				'\n' +
				'</div>';

			document.body.insertAdjacentHTML('beforeend', this.pswp);
			this.pswp = document.querySelector(this.pswpSelector);
		}
	};

	hostedVideo(a) {
		const html5 = a.getAttribute('href').match(new RegExp(/(\.mp4|\.webm|\.ogg)/i));
		let css = a.classList.contains('photonic-lb');

		if (html5 !== null && !css) {
			a.classList.add(Photonic_JS.lightbox_library + "-html5-video");
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
			this.videoIndex++;
		}
	};

	changeVideoURL(element, regular, embed) {
		element.setAttribute('href', embed);
	};

	initialize(selector, selfSelect) {
		this.handleSolos();
		const self = this;

		self.items = {};
		self.solos = [];
		self.videos = [];

		const containers = document.querySelectorAll('.photonic-level-1-container');
		containers.forEach(function(container) {
			let parent = container.closest('.photonic-stream') || container.closest('.photonic-panel');
//			if (parent != null) {
				let galleryId = parent.getAttribute('id');

				const links = container.querySelectorAll('.photonic-lb');
				const gallery = [];
				links.forEach(function(link) {
					const deep = link.getAttribute('data-photonic-deep');
					const pid = deep.split('/');
					let item;
					if (link.getAttribute('data-html5-href') !== null) {
						item = {
							html: '<div class="photonic-video" id="ps-' + link.getAttribute('href').substring(1) + '">\n<video class="photonic" controls preload="none"><source src="' + link.getAttribute('data-html5-href') + '" type="video/mp4">Your browser does not support HTML5 videos</video>',
							title: link.getAttribute('data-title')
						};
					}
					else {
						item = {
							src: link.getAttribute('href'),
							w: 0,
							h: 0,
							title: link.getAttribute('data-title'),
							pid: pid[1]
						};
					}
					gallery.push(item);
				});
				self.items[galleryId] = gallery;
//			}
		});

		const a = document.querySelectorAll('a.photonic-photoswipe');
		const solos = Array.from(a).filter(elem => elem.closest('.photonic-level-1') === null);
		solos.forEach(link => {
			const item = {
				src: link.getAttribute('href'),
				w: 0,
				h: 0,
				title: Util.getText(link.getAttribute('data-title'))
			};
			self.solos.push([item]);
		});

		const videos = document.querySelectorAll(this.videoSelector);
		videos.forEach(function(link) {
			let item;
			if (link.classList.contains('photoswipe-video')) { // YouTube / Vimeo
				item = {
					html: '<div class="photonic-video"><iframe class="pswp__video" width="640" height="480" src="' + link.getAttribute('href') + '" frameborder="0" allowfullscreen></iframe></div>'
				};
			}
			else {
				let href = link.getAttribute('href');
				href = document.querySelector(href);

				item = {
					html: '<div class="photonic-video" id="ps-' + href.getAttribute('id') + '">\n<video class="photonic" controls preload="none"><source src="' + link.getAttribute('data-html5-href') + '" type="video/mp4">Your browser does not support HTML5 videos</video>',
					title: link.getAttribute('data-title') || link.getAttribute('title') || ''
				}
			}
			self.videos.push([item]);
		});
	};

	initializeForNewContainer(containerId) {
		this.initialize(containerId);
	};

	parsePhotoSwipeHash() {
		const hash = window.location.hash.substring(1);
		const params = {};

		const vars = hash.split('&');
		for (let i = 0; i < vars.length; i++) {
			if(!vars[i]) {
				continue;
			}
			const pair = vars[i].split('=');
			if(pair.length < 2) {
				continue;
			}
			params[pair[0]] = pair[1];
		}

		if (params.gid && params.gid.indexOf('photonic') !== 0) { // Not a Photonic hash
			return {};
		}

		return params;
	};

	openPhotoSwipe(index, galleryId, fromURL, isVideo) {
		let idx;
		const self = this;
		if (fromURL) {
			const gallery = document.querySelector('#' + galleryId);
			const a = gallery.querySelector('a[data-photonic-deep="gallery[' + galleryId + ']/' + index + '/"]');
			idx = [...a.parentNode.children].indexOf(a);
		}

		const deepLinking = !(Photonic_JS.deep_linking === undefined || Photonic_JS.deep_linking === 'none' || galleryId === undefined || galleryId.indexOf('-stream') < 0);
		let shareButtons = [];
		if (!(Photonic_JS.social_media === undefined || Photonic_JS.social_media === '')) {
			shareButtons = [
				{id:'facebook', label:'Share on Facebook', url:'https://www.facebook.com/sharer/sharer.php?u={{url}}&title={{text}}'},
				{id:'twitter', label:'Share on Twitter', url:'https://twitter.com/share?url={{url}}&text={{text}}'},
				{id:'pinterest', label:'Pin it', url:'https://www.pinterest.com/pin/create/button/?url={{url}}&media={{image_url}}&description={{text}}'}
			];
		}
		shareButtons.push({id:'download', label:'Download image', url:'{{raw_image_url}}', download:true});

		const options = {
			index: (fromURL && deepLinking) ? idx : index,
			history: deepLinking,
			shareButtons: shareButtons,
			loop: Photonic_JS.lightbox_loop,
			galleryUID: galleryId,
			galleryPIDs: deepLinking
		};

		const galleryItems = isVideo ? self.videos[index] : (galleryId !== undefined ? self.items[galleryId] : self.solos[index]);
		const gallery = new PhotoSwipe(this.pswp, PhotoSwipeUI_Default, galleryItems, options);
		gallery.listen('gettingData', function(i, item) {
			if (item.src !== undefined && (item.w < 1 || item.h < 1)) { // unknown size
				const img = new Image();
				img.onload = function() { // will get size after load
					item.w = this.width; // set image width
					item.h = this.height; // set image height
					item.needsUpdate = true;
					gallery.updateSize(true); // reinit Items
				};
				img.src = item.src; // let's download image
			}
			else if (item.html !== undefined && (item.w < 1 || item.h < 1)) {
				let html = document.createElement("div");
				html.innerHTML = item.html;

				const video = html.querySelector('video');
				if (video !== null) {
					let videoSrc = html.querySelector('source');
					if (videoSrc !== null) {
						videoSrc = videoSrc.getAttribute('src');
						self.getVideoSize(videoSrc, {width: window.innerWidth, height: window.innerHeight}).then(dimensions => {
							item.h = dimensions.newHeight;
							item.w = dimensions.newWidth;

							const videoContainer = document.querySelector(html.getAttribute('id'));
							if (videoContainer) {
								const containedVideo = videoContainer.querySelector('video');
								containedVideo.width = dimensions.newWidth;
								containedVideo.height = dimensions.newHeight;
							}
						});
					}
				}
			}
		});
		gallery.init();
	};

	initializeForExisting() {
		const self = this;

		document.addEventListener('click', e => {
			if (!(e.target instanceof Element) || !e.target.closest('a.photonic-photoswipe')) {
				return;
			}

			e.preventDefault();
			const clicked = e.target.closest('a.photonic-photoswipe'); //e.currentTarget;
			const parent = clicked.closest('.photonic-stream') || clicked.closest('.photonic-panel');
			let index;
			if (parent !== null) {
				const node = clicked.closest('.photonic-level-1');
				const galleryId = parent.getAttribute('id');
				if (node) {
					index = [...node.parentNode.children].indexOf(node);
					self.openPhotoSwipe(index, galleryId);
				}
			}
			else {
				const a = document.querySelectorAll('a.photonic-photoswipe');
				const solos = Array.from(a).filter(elem => elem.closest('.photonic-level-1') === null);
				index = solos.indexOf(clicked);
				self.openPhotoSwipe(index, undefined);
			}
		});

		document.addEventListener('click', e => {
			if (!(e.target instanceof Element) || !e.target.closest(self.videoSelector)) {
				return;
			}

			e.preventDefault();
			const clicked = e.target.closest(self.videoSelector); //e.currentTarget;
			const videos = document.querySelectorAll(self.videoSelector);
			const index = Array.from(videos).indexOf(clicked);
			self.openPhotoSwipe(index, undefined, false, true);
		});
	};
}
