import {Lightbox} from "./Lightbox";
import * as Util from "../Util";

export class PhotonicFeatherlight extends Lightbox {
	constructor($) {
		super();
		this.$ = $;
	}

	resizeContainer(elem) {
		const $ = this.$;
		const target = elem,
			video = $(target.attr('data-featherlight')),
			videoURL = $(video.children()[0]).attr('src');
		if (videoURL === undefined) {
			//videoURL = target.attr('data-html5-href');
		}
		this.getVideoSize(videoURL, {width: window.innerWidth * 0.85 - 50, height: window.innerHeight * 0.85 - 50}).then(function(dimensions) {
			$('.featherlight-content').find('video').attr('width', dimensions.newWidth).attr('height', dimensions.newHeight);
			target.attr('data-featherlight', $('<div/>').append(video).html());
		});
	};

	resizeImageContainer(elem) {
		const $ = this.$;
		const imageURL = $(elem).attr('href');
		this.getImageSize(imageURL, {width: window.innerWidth * 0.85 - 50, height: window.innerHeight * 0.85 - 50}).then(function(dimensions) {
			$('.featherlight-content').find('img').css({ width: dimensions.newWidth + 'px', height: dimensions.newHeight + 'px'});
		});
	};

	soloImages() {
		const $ = this.$;
		$('a[href]').filter(function() {
			return /(\.jpg|\.jpeg|\.bmp|\.gif|\.png)/i.test( $(this).attr('href'));
		}).filter(function() {
			const res = new RegExp('photonic-lb').test($(this).attr('class'));
			return !res;
		}).addClass("photonic-featherlight-solo");
	};

	changeVideoURL(element, regular, embed) {
		const $ = this.$;
		$(element).attr('href', embed);
		$(element).attr("data-featherlight", 'iframe').addClass('featherlight-video');
	};

	hostedVideo(a) {
		const $ = this.$;
		const html5 = $(a).attr('href').match(new RegExp(/(\.mp4|\.webm|\.ogg)/i));
		let css = $(a).attr('class');
		css = css !== undefined && css.includes('photonic-lb');

		if (html5 !== null && !css) {
			$(a).addClass(Photonic_JS.lightbox_library + "-html5-video");
			$(a).attr('data-featherlight', '<video controls preload="none"><source src="' + $(a).attr('href') + '" type="video/mp4">Your browser does not support HTML5 video.</video>');

			this.videoIndex++;
		}
	};

	initializeGallery(selector, self) {
		const $ = this.$;
		$(selector).featherlightGallery({
			gallery: {
				fadeIn: 300,
				fadeOut: 300
			},
			openSpeed: 300,
			closeSpeed: 300,
			afterContent: function() {
				this.$legend = this.$legend || $('<div class="legend"/>').insertAfter(this.$content.parent());
				this.$legend.html(this.$currentTarget.data('title'));
				this.$instance.find('.featherlight-previous img').remove();
				this.$instance.find('.featherlight-next img').remove();
				if (this.$currentTarget !== null && this.$currentTarget.length > 0) {
					self.setHash(this.$currentTarget[0]);
				}

				const shareable = {
					'url': location.href,
					'title': Util.getText(this.$currentTarget.data('title')),
					'image': this.$content.attr('src')
				};
				self.addSocial('.photonic-featherlight', shareable);
				$(window).resize();
			},
			afterClose: function() {
				self.unsetHash();
			},
			onResize: function() {
				//if ($(this.$currentTarget).attr('data-featherlight-type') == 'video' || $(this.$currentTarget).attr('data-html5-href') != undefined) {
				if ($(this.$currentTarget).attr('data-featherlight-type') === 'video') {
					self.resizeContainer($(this.$currentTarget));
				}
				else {
					self.resizeImageContainer($(this.$currentTarget));
				}
			},
			variant: 'photonic-featherlight'
		});
	};

	initialize(selector, group) {
		const $ = this.$;
		this.handleSolos();
		const self = this;

		$(selector).each(function() {
			const current = $(this),
				thumbs = current.find('a.photonic-featherlight');
			let rel = '';
			if (thumbs.length > 0) {
				rel = $(thumbs[0]).attr('rel');
			}
			self.initializeGallery('a[rel="' + rel + '"]', self);
		});

		$('a.photonic-featherlight-solo').featherlight({
			afterContent: function() {
				this.$legend = this.$legend || $('<div class="legend"/>').insertAfter(this.$content.parent());
				this.$legend.html(this.$currentTarget.data('title') || this.$currentTarget.attr('title') || this.$currentTarget.find('img').attr('title') || this.$currentTarget.find('img').attr('alt'));
			},
			type: 'image',
			variant: 'photonic-featherlight'
		});

		$('a.featherlight-video').featherlight({
			iframeWidth: 640,
			iframeHeight: 480,
			iframeFrameborder: 0,
			variant: 'photonic-featherlight'
		});

		$('a.featherlight-html5-video').featherlight({
			onResize: function() {
				self.resizeContainer($(this.$currentTarget));
			},
			variant: 'photonic-featherlight'
		});
	};

	initializeForNewContainer(containerId) {
		this.initialize(containerId);
	};
}
