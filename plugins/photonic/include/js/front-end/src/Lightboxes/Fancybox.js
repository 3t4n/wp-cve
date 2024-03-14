import {Lightbox} from "./Lightbox";

export class PhotonicFancybox extends Lightbox {
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

	formatTitle(title, currentArray, currentIndex, currentOpts) {
		if (currentArray[currentIndex].getAttribute('data-title') !== undefined && currentArray[currentIndex].getAttribute('data-title') !== '') {
			return currentArray[currentIndex].getAttribute('data-title');
		}
		return title;
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
		const $ = this.$;
		const self = this;
		this.handleSolos();

		if (Photonic_JS.slideshow_mode) {
			setInterval($.fancybox.next, parseInt(Photonic_JS.slideshow_interval, 10));
		}

		$(document).on('click', 'a.photonic-fancybox', function(e) {
			e.preventDefault();
			let videoID = $(this).attr('href');
			let videoURL = $(this).attr('data-html5-href');
			let $vclone;

			$('a.photonic-fancybox').fancybox({
				overlayShow		:	true,
				overlayColor	:	'#000',
				overlayOpacity	: 0.8,
				cyclic			: true,
				titleShow		: Photonic_JS.fbox_show_title === '1',
				titleFormat		: self.formatTitle,
				titlePosition	: Photonic_JS.fbox_title_position,
				type : $(this).attr('data-photonic-media-type') === 'image' ? 'image' : false,
				autoScale: true,
				scrolling: 'no',
				onStart: function(selectedArray, selectedIndex, selectedOpts) {
					const currentItem = selectedArray[selectedIndex];
					videoID = $(currentItem).attr('href');
					videoURL = $(currentItem).attr('data-html5-href');
				},
				onClosed	: function() {
					$('#photonic-html5-external-videos').append($vclone);
					$('.fancybox-inline-tmp').remove();
				},
				onComplete		: function() {
					if (videoURL !== undefined) {
						$vclone = $(videoID).clone(true);
						self.getVideoSize(videoURL, {height: window.innerHeight * 0.85, width: window.innerWidth * 0.85}).then(function(dimensions) {
							$(videoID).find('video').attr('width', dimensions.newWidth).attr('height', dimensions.newHeight);
							$(videoID).css({width: dimensions.newWidth, height: dimensions.newHeight});
							$('#fancybox-content').css({width: dimensions.newWidth, height: dimensions.newHeight});
							$('#fancybox-wrap').css({width: 'auto', height: 'auto' });
							$.fancybox.resize();
						});
					}
					self.swipe(e);
				}
			});
			this.click();
		});

		$('a.fancybox-video').fancybox({ type: 'iframe' });
		$('a.fancybox-html5-video').each(function() {
			const videoID = $(this).attr('href');
			const videoURL = $(this).attr('data-html5-href');
			let $vclone;
			$(this).fancybox({
				overlayShow		:	true,
				overlayColor	:	'#000',
				overlayOpacity	: 0.8,
				type: 'inline',
				titleShow		: Photonic_JS.fbox_show_title === '1',
				titleFormat		: self.formatTitle,
				titlePosition	: Photonic_JS.fbox_title_position,
				autoScale: true,
				scrolling: 'no',
				onStart: function() {
					$vclone = $(videoID).clone(true);
					self.getVideoSize(videoURL, {height: window.innerHeight * 0.85, width: window.innerWidth * 0.85}).then(function(dimensions) {
						$(videoID).find('video').attr('width', dimensions.newWidth).attr('height', dimensions.newHeight);
						$(videoID).css({width: dimensions.newWidth, height: dimensions.newHeight});
						$('#fancybox-content').css({width: dimensions.newWidth + 'px', height: dimensions.newHeight + 'px'});
						$('#fancybox-wrap').css({width: (dimensions.newWidth + 20) + 'px', height: (dimensions.newHeight + 20) + 'px'});
					});
				},
				onClosed	: function() {
					$('#photonic-html5-videos').append($vclone);
					$('.fancybox-inline-tmp').remove();
				},
				onComplete: function() {
					$.fancybox.resize();
				}
			});
		});
	};
}
