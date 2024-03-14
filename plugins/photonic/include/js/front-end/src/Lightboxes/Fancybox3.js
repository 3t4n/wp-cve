import {Lightbox} from "./Lightbox";
import * as Util from "../Util";

export class PhotonicFancybox3 extends Lightbox {
	constructor($) {
		super();

		this.$ = $;
		this.buttons = [];
		if (Photonic_JS.fb3_zoom) {
			this.buttons.push('zoom');
		}
		if (Photonic_JS.fb3_slideshow) {
			this.buttons.push('slideShow');
		}
		if (Photonic_JS.fb3_fullscreen_button) {
			this.buttons.push('fullScreen');
		}
		if (Photonic_JS.fb3_download) {
			this.buttons.push('download');
		}
		if (Photonic_JS.fb3_thumbs_button) {
			this.buttons.push('thumbs');
		}
		this.buttons.push('close');
	}

	soloImages() {
		const $ = this.$;
		$('a[href]').filter(function() {
			return /(\.jpg|\.jpeg|\.bmp|\.gif|\.png)/i.test( $(this).attr('href'));
		}).addClass("photonic-fancybox").addClass(Photonic_JS.lightbox_library);
	};

	hostedVideo(a) {
		const html5 = a.getAttribute('href').match(new RegExp(/(\.mp4|\.webm|\.ogg)/i)),
			css = a.classList.contains('photonic-lb');

		if (html5 !== null && !css) {
			a.classList.add(Photonic_JS.lightbox_library + "-html5-video");
			this.videoIndex++;
		}
	};

	changeVideoURL(element, regular, embed) {
		element.setAttribute('href', embed); // Don't need this for Fancybox3, but short URLs don't support "start" or "t" parameters; embed and regular URLs do.
	};

	initialize(selector, group) {
		this.handleSolos();
		const self = this;
		const $ = self.$;

		let lightbox_selector;
		if (group !== null && group !== undefined) {
			lightbox_selector = 'a[data-fancybox="' + group + '"]';
		}
		else if (selector !== null && selector !== undefined) {
			lightbox_selector = selector + ' a.photonic-fancybox';
		}
		else {
			lightbox_selector = 'a.photonic-fancybox';
		}

		$(lightbox_selector).fancybox({
			defaultType: 'image',
			hash: false,
			caption: function(instance, item) {
				return $(this).data('title');
			},
			loop: Photonic_JS.lightbox_loop === '1',
			buttons: self.buttons,
			slideShow: {
				autoStart: Photonic_JS.slideshow_mode,
				speed: parseInt(Photonic_JS.slideshow_interval, 10)
			},
			thumbs: {
				autoStart: Photonic_JS.fb3_thumbs === '1',
				hideOnClose: true
			},
			fullScreen: {
				autoStart: Photonic_JS.fb3_fullscreen === '1'
			},
			protect: Photonic_JS.fb3_disable_right_click === '1',
			transitionEffect: Photonic_JS.fb3_transition_effect,
			transitionDuration: Photonic_JS.fb3_transition_speed,
			afterShow: function(instance, slide) {
				const shareable = {
					'url': location.href,
					'title': $(slide).length > 0 && $(slide)[0].opts !== undefined && $(slide)[0].opts.$orig !== undefined ? Util.getText($(slide)[0].opts.$orig.attr('title')) : '',
					'image': $(slide).length > 0 && $(slide)[0].opts !== undefined && $(slide)[0].opts.$orig !== undefined ? $(slide)[0].opts.$orig.attr('href') : ''
				};
				self.addSocial('.fancybox-toolbar', shareable, 'afterbegin');
			},
			beforeShow: function() {
				const videoID = this.src,
					videoURL = this.opts.html5Href;
				if (videoURL !== undefined) {
					self.getVideoSize(videoURL, {height: window.innerHeight * 0.85, width: window.innerWidth * 0.85}).then(function(dimensions) {
						$(videoID).find('video').attr('width', dimensions.newWidth).attr('height', dimensions.newHeight);
						$(videoID).css({width: dimensions.newWidth, height: dimensions.newHeight});
					});
				}
				self.setHash(this.opts.photonicDeep);
			},
			afterClose: function() {
				self.unsetHash();
			}
		});

		$('a.fancybox3-video,a.fancybox3-html5-video').fancybox({
			youtube: { }
		});
	};

	initializeForNewContainer(containerId) {
		this.initialize(containerId);
	};

}
