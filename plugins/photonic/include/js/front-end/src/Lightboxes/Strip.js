import {Lightbox} from "./Lightbox";

export class PhotonicStrip extends Lightbox {
	constructor($) {
		super();
		this.$ = $;
	}

	changeVideoURL(element, regular, embed) {
		const $ = this.$;
		$(element).attr('href', regular);
		$(element).addClass('strip');
	};

	initialize = function(selector, group) {
		this.handleSolos();
		const $ = this.$;
		$('.photonic-strip.strip').each((idx, a) => {
			const hash = $(a).data('photonicDeep');
			let onShow, afterHide;
			if (hash !== undefined) {
				if (typeof(window.history.pushState) === 'function' && Photonic_JS.deep_linking === 'yes-history') {
					onShow = "onShow: function() { window.history.pushState({}, document.title, '#' + '" + hash + "');}";
				}
				else if (typeof(window.history.replaceState) === 'function' && Photonic_JS.deep_linking === 'no-history') {
					onShow = "onShow: function() { window.history.replaceState({}, document.title, '#' + '" + hash + "');}";
				}
				else {
					onShow = "onShow: function() { document.location.hash = '" + hash + "'; }";
				}

				if (window.history && 'replaceState' in window.history) {
					afterHide = ", afterHide: function() { history.replaceState({}, document.title, location.href.substr(0, location.href.length-location.hash.length));} ";
				}
				else {
					afterHide = ", afterHide: function() {window.location.hash = '';}";
				}
				$(a).attr('data-strip-options', onShow + afterHide);
			}
		});
	};
}