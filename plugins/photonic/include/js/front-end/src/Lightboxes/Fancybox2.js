import {Lightbox} from "./Lightbox";
import * as Util from "../Util";

export class PhotonicFancybox2 extends Lightbox {
	constructor($) {
		super();
		this.$ = $;
	}

	swipe(e) {
		const $ = this.$;
		$("#fancybox-wrap, .fancybox-wrap")
			.on('swipeleft', function() { $.fancybox.next(); })
			.on('swiperight', function() { $.fancybox.prev(); });
	};

	soloImages() {
		const $ = this.$;
		$('a[href]').filter(function() {
			return /(\.jpg|\.jpeg|\.bmp|\.gif|\.png)/i.test( $(this).attr('href'));
		}).addClass("photonic-fancybox").addClass(Photonic_JS.lightbox_library);
	};

	changeVideoURL(element, regular, embed) {
		const $ = this.$;
		$(element).attr('href', embed);
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
		this.handleSolos();
		const $ = this.$;
		const self = this;

		if (Photonic_JS.slideshow_mode) {
			setInterval($.fancybox.next, parseInt(Photonic_JS.slideshow_interval, 10));
		}

		$('a.photonic-fancybox').fancybox({
			wrapCSS: 'photonic-fancybox',
			autoPlay: Photonic_JS.slideshow_mode,
			playSpeed: parseInt(Photonic_JS.slideshow_interval, 10),
			//type: 'image',
			autoScale: true,
			autoResize: true,
			scrolling: 'no',
			afterShow: function(current, previous) {
				self.swipe();
				const shareable = {
					'url': location.href,
					'title': Util.getText($(this.element).data('title')),
					'image': $(this.element).attr('href')
				};
				self.addSocial('.fancybox-title', shareable);

				const videoID = $(this.element).attr('href');
				const videoURL = $(this.element).attr('data-html5-href');
				if (videoURL !== undefined) {
					self.getVideoSize(videoURL, {height: window.innerHeight * 0.85 - 30 - 40, width: window.innerWidth * 0.85 - 30}).then(function(dimensions) {
						$(videoID).find('video').attr('width', dimensions.newWidth).attr('height', dimensions.newHeight);
						$(videoID).css({width: dimensions.newWidth, height: dimensions.newHeight});
						$('.fancybox-skin .fancybox-inner').css({overflow: 'hidden'});
					});
				}
			},
			beforeLoad: function() {
				if (Photonic_JS.fbox_show_title) {
					this.title = $(this.element).data('title');
				}
				if (this.element !== null && this.element.length > 0) {
					self.setHash(this.element[0]);
				}
			},
			afterClose: function() {
				self.unsetHash();
			},
			helpers: {
				title: {
					type: Photonic_JS.fbox_title_position
				},
				thumbs	: {
					width	: 50,
					height	: 50
				},
				overlay: {
					css: {
						'background': 'rgba(0, 0, 0, 0.8)'
					}
				},
				buttons	: {}
			}
		});
		$('a.fancybox2-video').fancybox({type: 'iframe'});
		$('a.fancybox2-html5-video').each(function() {
			const videoID = $(this).attr('href');
			const videoURL = $(this).attr('data-html5-href');
			$(this).fancybox({
				type: 'inline',
				wrapCSS: 'photonic-fancybox',
				autoScale: true,
				scrolling: 'no',
				beforeLoad: function() {
					self.getVideoSize(videoURL, {height: window.innerHeight - 30 - 40, width: window.innerWidth - 30}).then(function(dimensions) {
						$(videoID).find('video').attr('width', dimensions.newWidth).attr('height', dimensions.newHeight);
						$(videoID).css({width: dimensions.newWidth, height: dimensions.newHeight});
						$('.fancybox-skin .fancybox-inner').css({overflow: 'hidden'});
					});
				},
				onComplete: function() {
					$.fancybox.update();
				}
			});
		});
	};
}
