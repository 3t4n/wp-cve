import {Lightbox} from "./Lightbox";
import * as Util from "../Util";

export class PhotonicColorbox extends Lightbox {
	constructor($) {
		super();
		this.$ = $;
	}

	soloImages() {
		const $ = this.$;
		$('a[href]').filter(function () {
			return /(\.jpg|\.jpeg|\.bmp|\.gif|\.png)/i.test($(this).attr('href'));
		}).addClass("photonic-" + Photonic_JS.lightbox_library).addClass(Photonic_JS.lightbox_library).attr('data-photonic-media-type', 'image');
	};

	changeVideoURL(element, regular, embed) {
		this.$(element).attr('href', embed);
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
		if ($.colorbox) {
			$(document).on('click', 'a.photonic-colorbox', function (e) {
				e.preventDefault();
				$('a.photonic-colorbox[data-photonic-media-type="image"]').each(function () {
					$(this).colorbox({
						opacity: 0.8,
						maxWidth: '95%',
						maxHeight: '95%',
						photo: true,
						title: $(this).data('title'),
						transition: Photonic_JS.cb_transition_effect,
						speed: Photonic_JS.cb_transition_speed,
						slideshow: Photonic_JS.slideshow_mode === '1',
						slideshowSpeed: Photonic_JS.slideshow_interval,
						loop: Photonic_JS.lightbox_loop === '1',
						onLoad: function () {
							self.setHash(this);
							const shareable = {
								'url': location.href,
								'title': Util.getText($(this).data('title')),
								'image': $(this).attr('href')
							};
							self.addSocial('#cboxContent', shareable);
						},
						onClosed: function () {
							self.unsetHash();
						}
					});
				});

				$('a.photonic-colorbox[data-photonic-media-type="video"]').each(function () {
					$(this).colorbox({
						opacity: 0.8,
						maxWidth: '90%',
						maxHeight: '90%',
						inline: true,
						title: $(this).data('title'),
						transition: Photonic_JS.cb_transition_effect,
						speed: Photonic_JS.cb_transition_speed,
						slideshow: Photonic_JS.slideshow_mode,
						slideshowSpeed: Photonic_JS.slideshow_interval,
						loop: Photonic_JS.lightbox_loop === '1',
						scrolling: false,
						onLoad: function () {
							self.setHash(this);
							const shareable = {
								'url': location.href,
								'title': Util.getText($(this).data('title')),
								'image': $(this).attr('href')
							};
							self.addSocial('#cboxContent', shareable);
							const videoID = $(this).attr('href');
							self.getVideoSize($(this).attr('data-html5-href'), {
								height: window.innerHeight * 0.90 - 50,
								width: window.innerWidth * 0.90
							}).then(function (dimensions) {
								$(videoID).find('video').attr('width', dimensions.newWidth).attr('height', dimensions.newHeight);
								$(videoID).css({width: dimensions.newWidth, height: dimensions.newHeight});
							});
						},
						onComplete: function () {
							$(this).colorbox.resize({
								innerWidth: $($(this).attr('href')).width(),
								innerHeight: $($(this).attr('href')).height()
							});
						},
						onClosed: function () {
							self.unsetHash();
						}
					});
				});
				this.click();
			});

			$('.colorbox-video').colorbox({
				opacity: 0.8,
				maxWidth: '95%',
				maxHeight: '95%',
				title: $(this).data('title'),
				iframe: true, innerWidth: 640, innerHeight: 390, scrolling: false
			});

			$('a.colorbox-html5-video').colorbox({
				opacity: 0.8,
				maxWidth: '95%',
				maxHeight: '95%',
				title: $(this).data('title'),
				inline: true, href: $(this).attr('href'),
				scrolling: false,
				onLoad: function () {
					const videoID = $(this).attr('href');
					self.getVideoSize($(this).attr('data-html5-href'), {
						height: window.innerHeight * 0.95 - 50,
						width: window.innerWidth * 0.95
					}).then(function (dimensions) {
						$(videoID).find('video').attr('width', dimensions.newWidth).attr('height', dimensions.newHeight);
						$(videoID).css({width: dimensions.newWidth, height: dimensions.newHeight});
					});
				},
				onComplete: function () {
					$(this).colorbox.resize({
						innerWidth: $($(this).attr('href')).width(),
						innerHeight: $($(this).attr('href')).height()
					});
				}
			});

			$(document).bind('cbox_open', function () {
				$("#colorbox")
					.on('swipeleft', function () {
						$.colorbox.next();
					})
					.on('swiperight', function () {
						$.colorbox.prev();
					});
			});
		}
	}
}
