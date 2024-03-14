import {Lightbox} from "./Lightbox";

export class PhotonicPrettyPhoto extends Lightbox {
	constructor($) {
		super();
		this.$ = $;
	}

	swipe() {
		const $ = this.$;
		$('.pp_hoverContainer').remove();
		$("#pp_full_res")
			.on('swipeleft', function() { $.prettyPhoto.changePage('next'); })
			.on('swiperight', function() { $.prettyPhoto.changePage('previous'); });
	};

	soloImages() {
		const $ = this.$;
		$('a[href]').filter(function() {
			return /(\.jpg|\.jpeg|\.bmp|\.gif|\.png)/i.test( $(this).attr('href'));
		}).filter(function() {
			const res = new RegExp('photonic-prettyPhoto').test($(this).attr('rel'));
			return !res;
		}).attr("rel", 'photonic-prettyPhoto');
	};

	changeVideoURL(element, regular, embed) {
		this.$(element).attr('href', regular);
	};

	initialize(e) {
		const $ = this.$;
		// this.handleSolos(); // Can't do this here since initialize is not called directly
		const self = this;

		$("a[rel^='photonic-prettyPhoto']").prettyPhoto({
			theme: Photonic_JS.pphoto_theme,
			autoplay_slideshow: Photonic_JS.slideshow_mode,
			slideshow: Photonic_JS.slideshow_interval,
			show_title: false,
			social_tools: '',
			deeplinking: false,
			changepicturecallback: function() {
				const img = $('#fullResImage');
				if (e !== undefined && e['deep'] === undefined) {
					const clicked_thumb = $(e.target).parent(),
						clicked_div = $(clicked_thumb).parent(),
						current_stream = $(clicked_div).parent();

					let active_node = $(current_stream).find('a[href="' + $(img).attr('src') + '"]');

					if (active_node.length === 0) {
						$.each($('div.title-display-regular, div.title-display-below, div.title-display-tooltip, div.title-display-hover-slideup-show, div.title-display-slideup-stick, '+
							'ul.title-display-regular, ul.title-display-below, ul.title-display-tooltip, ul.title-display-hover-slideup-show, ul.title-display-slideup-stick'), function(key, value) {
							active_node = $(this).find('a[href="' + $(img).attr('src') + '"]');
							if (active_node.length !== 0) {
								return false;
							}
						});
					}

					if (active_node.length > 0) {
						self.setHash(active_node[0]);
					}
				}
				else if (e['deep'] !== undefined) {
					const idx = e['images'].indexOf($(img).attr('src'));
					if (idx > -1) {
						self.setHash(e['deep'][idx]);
					}
				}

				const shareable = {
					'url': location.href,
					'title': $('.pp_description').text(),
					'image': img.attr('src')
				};
				self.addSocial('#pp_full_res', shareable);

				self.swipe();
			},
			callback: function() {
				self.unsetHash();
			}
		});
	};

	initializeForExisting() {
		const $ = this.$;
		const self = this;

		$(document).on('click', "a[rel^='photonic-prettyPhoto']", function(e) {
			e.preventDefault();
			self.initialize(e);
			this.click();
		});

		$("a[rel^='photonic-prettyPhoto-video'],a[rel^='photonic-prettyPhoto-html5-video']").prettyPhoto({
			theme: Photonic_JS.pphoto_theme,
			autoplay_slideshow: Photonic_JS.slideshow_mode,
			slideshow: Photonic_JS.slideshow_interval,
			show_title: false,
			social_tools: '',
			deeplinking: false
		});
	};
}