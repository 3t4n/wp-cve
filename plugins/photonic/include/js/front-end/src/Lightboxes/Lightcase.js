import {Lightbox} from "./Lightbox";
import * as Util from "../Util";

export class PhotonicLightcase extends Lightbox {
	constructor($) {
		super();
		this.$ = $;
	}

	soloImages() {
		const $ = this.$;
		$('a[href]').filter(function() {
			return /(\.jpg|\.jpeg|\.bmp|\.gif|\.png)/i.test( $(this).attr('href'));
		}).filter(function() {
			const res = new RegExp('photonic-lb').test($(this).attr('class'));
			return !res;
		}).attr("data-rel", 'photonic-lightcase');
	};

	changeVideoURL(element, regular, embed) {
		const $ = this.$;
		$(element).attr('href', embed);
		$(element).attr("data-rel", 'photonic-lightcase-video');
	};

	hostedVideo(a) {
		const $ = this.$;
		const html5 = $(a).attr('href').match(new RegExp(/(\.mp4|\.webm|\.ogg)/i));
		let css = $(a).attr('class');
		css = css !== undefined && css.includes('photonic-lb');

		if (html5 !== null && !css) {
			$(a).addClass(Photonic_JS.lightbox_library + "-html5-video");
			$(a).attr("data-rel", 'photonic-html5-video');

			this.videoIndex++;
		}
	};

	initialize(selector, group) {
		const $ = this.$;
		this.handleSolos();
		const self = this;

		$(selector).each(function(i, current) {
			let lightbox_selector,
				rel = $(current).find('a.photonic-lightcase');
			if (rel.length > 0) {
				rel = $(rel[0]).data('rel');
			}

			lightbox_selector = selector.indexOf('data-rel') > -1 ? selector : 'a[data-rel="' + rel + '"]';
			$(lightbox_selector).lightcase({
				showSequenceInfo: false,
				transition: Photonic_JS.lc_transition_effect,
				slideshow: Photonic_JS.slideshow_mode,
				timeout: Photonic_JS.slideshow_interval,
				navigateEndless: Photonic_JS.lightbox_loop === '1',
				disableShrink: Photonic_JS.lc_disable_shrink === '1',
				attrPrefix: '',
				caption: ' ',
				swipe: true,
				onStart: {
					getVideoSize: function() {
						const elem = this,
							videoURL = $(elem).attr('data-html5-href');// || $(elem).attr('href');
						if (lightbox_selector.indexOf('photonic-html5-video') > -1 || videoURL !== undefined) {
							self.getVideoSize(videoURL === undefined ? $(elem).attr('href') : videoURL, {height: window.innerHeight * 0.8, width: 800 }).then(function(dimensions) {
								$(elem).attr('data-lc-options', '{"width": ' + Math.round(dimensions.newWidth) + ', "height": ' + Math.round(dimensions.newHeight) + '}');
								$('#lightcase-content').find('video').attr({ width: Math.round(dimensions.newWidth), height: Math.round(dimensions.newHeight)}).css({ width: Math.round(dimensions.newWidth), height: Math.round(dimensions.newHeight)});
								lightcase.resize({ width: Math.round(dimensions.newWidth), height: Math.round(dimensions.newHeight) });
							});
						}
					}
				},
				onFinish: {
					setHash: function() {
						if (this.length > 0) {
							self.setHash(this[0]);
						}
						const shareable = {
							'url': location.href,
							'title': Util.getText($(this).data('title')),
							'image': $(this).attr('href')
						};
						self.addSocial('#lightcase-info', shareable);
					}
				},
				onClose: {
					unsetHash: self.unsetHash
				}
			});
		});
	};

	initializeForNewContainer(containerId) {
		this.initialize(containerId);
	};
}
