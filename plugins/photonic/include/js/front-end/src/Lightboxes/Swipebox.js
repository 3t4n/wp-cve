import {Lightbox} from "./Lightbox";
import * as Util from "../Util";
import {Core} from "../Core";

export class PhotonicSwipebox extends Lightbox {
	constructor($) {
		super();
		this.$ = $;
	}

	changeSlide(thumb, idx) {
		const $ = this.$;
		if (thumb != null) {
			const rel = $(thumb).attr('rel'),
				all_thumbs = $('[rel="' + rel + '"]'),
				slide = all_thumbs[idx];
			this.setHash(slide);

			const videoID = $(slide).attr('href'),
				videoURL = $(slide).attr('data-html5-href');
			if (videoURL !== undefined) {
				this.getVideoSize(videoURL, {width: window.innerWidth, height: window.innerHeight - 50}).then(function(dimensions) {
					$(videoID).find('video').attr('width', dimensions.newWidth).attr('height', dimensions.newHeight);
					$('.swipebox-inline-container ' + videoID).find('video').attr({ width: dimensions.newWidth, height: dimensions.newHeight });
				});
			}

			const shareable = {
				'url': location.href,
				'title': Util.getText($(slide).data('title')),
				'image': $(slide).attr('href')
			};
			this.addSocial('#swipebox-arrows', shareable);
		}
	};

	changeVideoURL(element, regular, embed) {
		this.$(element).attr('href', regular);
	};

	hostedVideo(a) {
		const $ = this.$;
		const html5 = $(a).attr('href').match(new RegExp(/(\.mp4|\.webm|\.ogg)/i));
		let css = $(a).attr('class');
		css = css !== undefined && css.includes('photonic-lb');

		if (html5 !== null && !css) {
			$(a).addClass(Photonic_JS.lightbox_library + "-html5-video");
			let $videos = $('#photonic-html5-videos');
			$videos = $videos.length ? $videos : $('<div style="display:none;" id="photonic-html5-videos"></div>').appendTo(document.body);
			$videos.append('<div id="photonic-html5-video-' + this.videoIndex + '"><video controls preload="none"><source src="' + $(a).attr('href') + '" type="video/mp4">Your browser does not support HTML5 video.</video></div>');
			$(a).attr('data-html5-href', $(a).attr('href'));
			$(a).attr('href', '#photonic-html5-video-' + this.videoIndex);
			this.videoIndex++;
		}
	};

	initialize(selector, group) {
		const $ = this.$;
		this.handleSolos();
		const self = this;

		$('a.photonic-swipebox, a.swipebox-video, a.swipebox-html5-video').swipebox({
			hideBarsDelay: Photonic_JS.sb_hide_bars_delay,
			removeBarsOnMobile: !(Photonic_JS.enable_swipebox_mobile_bars === '1'),
			hideCloseButtonOnMobile: Photonic_JS.sb_hide_mobile_close,
			loopAtEnd: Photonic_JS.lightbox_loop === '1',
			currentThumb: null,
			videoURL: null,
			videoID: null,
			selector: 'a.photonic-swipebox',
			beforeOpen: function(e) {
				const evt = e || window.event;
				if (evt !== undefined) {
					const clicked = $(evt.target).parents('.photonic-swipebox');
					if (clicked.length > 0) {
						this.currentThumb = clicked[0];
					}
					else {
						const all_matches = $('[data-photonic-deep="' + Core.getDeep().substr(1) + '"]');
						if (all_matches.length > 0) {
							this.currentThumb = all_matches[0];
						}
					}
				}
				this.videoURL = $(this.currentThumb).attr('data-html5-href');
				this.videoID = $(this.currentThumb).attr('href');
			},
			afterOpen: function(idx) {
				if (this.videoURL) {
					const videoID = this.videoID;
					self.getVideoSize(this.videoURL, {
						width: window.innerWidth,
						height: window.innerHeight - 50
					}).then(function (dimensions) {
						$(videoID).find('video').attr('width', dimensions.newWidth).attr('height', dimensions.newHeight);
						$('.swipebox-inline-container ' + videoID).find('video').attr('width', dimensions.newWidth).attr('height', dimensions.newHeight);
					});
				}
				self.changeSlide(this.currentThumb, idx);
			},
			prevSlide: function(idx) {
				self.changeSlide(this.currentThumb, idx);
			},
			nextSlide: function(idx) {
				self.changeSlide(this.currentThumb, idx);
			},
			afterClose: function() {
				self.unsetHash();
			}
		});
	};

}
