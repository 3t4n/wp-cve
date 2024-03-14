import {Lightbox} from "./Lightbox";
import * as Util from "../Util";

export class PhotonicMagnific extends Lightbox {
	constructor($) {
		super();
		this.$ = $;

		this.$.expr[':'].parents = function(a,i,m){
			return jQuery(a).parents(m[3]).length < 1;
		};
	}

	initialize(selector, group) {
		this.handleSolos();
		const self = this;
		const $ = self.$;

		$('a.photonic-magnific').each(function (i, a) {
			const $a = $(a);
			if ($a.attr('data-photonic-media-type') === 'video') {
				$a.removeClass('mfp-image');
				$a.addClass('mfp-inline');
			}
			else if ($a.attr('data-photonic-media-type') === 'image') {
				$a.addClass('mfp-image');
				$a.removeClass('mfp-inline');
			}

		});

		$(selector).each(function(idx, obj) {
			$(obj).magnificPopup({
				delegate: 'a.photonic-magnific',
				type: 'image',
				gallery: {
					enabled: true
				},
				image: {
					titleSrc: 'data-title'
				},
				callbacks: {
					change: function () {
						const $content = $(this.content),
							videoId = $content.attr('id');
						if (videoId !== undefined && videoId.indexOf('photonic-video') > -1) {
							const videoURL = $content.find('video').find('source').attr('src');
							if (videoURL !== undefined) {
								self.getVideoSize(videoURL, {height: window.innerHeight * 0.8, width: window.innerWidth * 0.8 }).then(function(dimensions) {
									$content.find('video').attr({
										height: dimensions.newHeight,
										width: dimensions.newWidth
									});
								});
							}
						}
						if (this.currItem.el.length > 0) {
							self.setHash(this.currItem.el[0]);
						}
						if (this.currItem.type === 'inline') {
							$(this.content).append($('<div></div>').html($(this.currItem.el).data('title')));
						}
					},
					imageLoadComplete: function() {
						const shareable = {
							'url': location.href,
							'title': Util.getText($(this.currItem.el).data('title')),
							'image': $(this.currItem.el).attr('href')
						};
						self.addSocial('.mfp-figure', shareable);
					},
					close: function() {
						self.unsetHash();
					}
				}
			});
		});
	};

	initializeForNewContainer(selector) {
		this.initialize(selector);
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

	initializeSolos() {
		const self = this;
		const $ = self.$;

		if (Photonic_JS.lightbox_for_all) {
			$('a.photonic-magnific').filter(':parents(.photonic-level-1)').each(function(idx, obj) { // Solo images
				$(obj).magnificPopup({
					type: 'image'
				});
			});
		}

		if (Photonic_JS.lightbox_for_videos) {
			$('.magnific-video').each(function(idx, obj) {
				$(obj).magnificPopup({
					type: 'iframe'
				});
			});

			$('.magnific-html5-video').each(function(idx, obj) {
				$(obj).magnificPopup({
					type: 'inline',
					callbacks: {
						change: function () {
							const $content = $(this.content),
								videoId = $content.attr('id');
							if (videoId !== undefined && videoId.indexOf('photonic-html5-video') > -1) {
								const videoURL = $content.find('video').find('source').attr('src');
								if (videoURL !== undefined) {
									self.getVideoSize(videoURL, {height: window.innerHeight * 0.8, width: window.innerWidth * 0.8 }).then(function(dimensions) {
										$content.find('video').attr({
											height: dimensions.newHeight,
											width: dimensions.newWidth
										});
									});
								}
							}
						}
					}
				});
			});
		}
	};
}
