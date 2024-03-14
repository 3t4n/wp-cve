import {Core} from "../Core";
import {Lightbox} from "./Lightbox";
import * as Util from "../Util";

export class PhotonicImageLightbox extends Lightbox {
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
		}).attr("rel", 'photonic-' + Photonic_JS.lightbox_library);
	};

	initialize(selector, group) {
		const $ = this.$;
		this.handleSolos();
		const self = this;

		$(selector).each(function(i, current) {
			let lightbox_selector;
			let rel = $(current).find('a.photonic-imagelightbox');
			if (rel.length > 0) {
				rel = $(rel[0]).attr('rel');
			}

			lightbox_selector = selector.indexOf('rel') > -1 ? selector : 'a[rel="' + rel + '"]';

			const photonicImageLightbox = $(lightbox_selector).imageLightbox(lightbox_selector, {
				onLoadStart: function () {
					imageLightboxCaptionOff();
					imageLightboxLoadingOn();
				},
				onLoadEnd: function () {
					imageLightboxCaptionOn();
					$('#imagelightbox-loading').remove();
					$('.imagelightbox-arrow').css('display', 'block');
					const lightbox = $('#imagelightbox');
					const base = $(current).find('a[href="' + lightbox.attr('src') + '"]');
					if (base.length > 0) {
						self.setHash(base[0]);
					}
					const shareable = {
						'url': location.href,
						'title': Util.getText($(base).data('title')),
						'image': lightbox.attr('src')
					};
					self.addSocial('#imagelightbox-overlay', shareable);
				},
				onStart: function () {
					$('<div id="imagelightbox-overlay"></div>').appendTo('body');
					imageLightboxArrowsOn(photonicImageLightbox, lightbox_selector);
					imageLightboxCloseButtonOn(photonicImageLightbox);
				},
				onEnd: function () {
					imageLightboxCaptionOff();
					$('#imagelightbox-overlay').remove();
					$('#imagelightbox-loading').remove();
					imageLightboxArrowsOff();
					imageLightboxCloseButtonOff();
					self.unsetHash();
				}
			});
			Core.addToLightboxList(lightbox_selector, photonicImageLightbox);
		});
	};

	initializeForNewContainer(containerId) {
		this.initialize(containerId);
	};

}
