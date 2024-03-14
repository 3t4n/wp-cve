/*
 * Custom Analytics Tracker scripts
 */
;
(function ($) {
	//"use strict";

	var analyticstracker_ga = {

		// Start Functions
		startAt: function () {
			analyticstracker_ga.EventUrl();
		},

		//Event for Download, Email, Phone number, Outbound links
		EventUrl: function () {
			$('a').click(function(e) {
				var this_at = $(this);
				var href_at = this_at.prop('href').split('?')[0];
				var ext_at = href_at.split('.').pop();
				if ('7z, ai, avi, cbr, csv, doc, docx, exe, gz, jar, midi, mov, mp3, pdf, pdn, pez, pot, ppt, pptx, psd, pub, rar, tar, torrent, tsv, txt, wav, wma, wmv, wwf, xls, xlsx, zip'.indexOf(ext_at) !== -1) {
					gtag('event', ext_at, {
						'event_category' : 'Download',
						'event_label' : href_at,
					});
				}
				else if (href_at.toLowerCase().indexOf('mailto:') === 0) {
					gtag('event', 'email', {
						'event_category' : 'Email',
						'event_label' :  href_at.substr(7),
					});
				}
				else if (href_at.toLowerCase().indexOf('tel:') === 0) {
					gtag('event', 'tel', {
						'event_category' : 'Phone number',
						'event_label' :  href_at.substr(4),
					});
				}
				else if ((this.protocol === 'http:' || this.protocol === 'https:') && this.hostname.indexOf(document.location.hostname) === -1) {
					gtag('event', 'url', {
						'event_category' : 'Outbound',
						'event_label' : href_at,
					});
				}
			});
			//Event for Error 404
			if( $('body').hasClass('error404') ) {
				gtag('event', '404', {
					'event_category' : 'Error',
					'event_label' : 'page: ' + document.location.pathname + document.location.search + ' ref: ' + document.referrer,
				});
			}
			//Event for Scroll Depth
			$.scrollDepth({
				pixelDepth: false,
				userTiming: false,
				gtmOverride: false,
			});
		},
	};

	$(document).ready(function () {
		analyticstracker_ga.startAt();
	});
})(jQuery);

