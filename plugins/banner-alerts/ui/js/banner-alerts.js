(function ($) {

	var bannerAlerts = {
		latestTimestamp: 0,

		$alertsBannerContainer: null,
		$alertsBanner: null,
		$alerts: null,
		$controls: null,

		options: [],

		init: function () {
			var cookieData = (this.getCookie('last-alert-timestamp') || '0:0').split(':');
			this.latestTimestamp = cookieData[0] || 0;

			if ((Math.floor(new Date().getTime() / 1000) - (cookieData[1] || 0)) >= 1800) {
				this.retrieveAlerts(this.latestTimestamp);
			}
		},

		retrieveAlerts: function (timestamp) {
			$.post(banner_alerts_vars.ajaxurl, { 
				'action': 'get_banner_alerts',
				'timestamp': timestamp
			}, function(data) {
				bannerAlerts.options = data.options;
				bannerAlerts.addAlertsContainer();
				bannerAlerts.addAlerts(data.alerts);

				if (data.alerts == '') {
					bannerAlerts.saveLatestTimestamp();
				}
			});
		},

		setCookie: function (name, value, days) {
			var expires = '';

			if (days) {
				var date = new Date();
				date.setTime(date.getTime() + (days * 86400000));
				expires = '; expires=' + date.toUTCString();
			}

			document.cookie = encodeURIComponent(name) + '=' + encodeURIComponent(value) + expires + '; path=/';
		},

		getCookie: function (name) {
			var cookie_name = name + '=';
			var decoded_cookie = decodeURIComponent(document.cookie);
			var cookie_array = decoded_cookie.split(';');

			for (var i = 0; i < cookie_array.length; i++) {
				var current_cookie = cookie_array[i];

				while (current_cookie.charAt(0) == ' ') {
					current_cookie = current_cookie.substring(1);
				}

				if (current_cookie.indexOf(cookie_name) === 0) {
					return current_cookie.substring(cookie_name.length, current_cookie.length);
				}
			}

			return null;
		},

		saveLatestTimestamp: function () {
			this.setCookie('last-alert-timestamp', this.latestTimestamp + ':' + Math.floor(new Date().getTime() / 1000), 365);
		},

		addAlertsContainer: function () {
			this.$alertsBannerContainer = $('<div/>', {
				'class': 'banner-alerts-container'
			});

			this.$alertsBanner = $('<div/>', {
				'id': 'banner-alerts',
				'class': 'banner-alerts',
			}).appendTo(this.$alertsBannerContainer);

			this.$alerts = $('<div/>', {
				'class': 'alerts'
			}).appendTo(this.$alertsBanner);

			this.$controls = $('<div/>', {
				'class': 'controls'
			}).appendTo(this.$alertsBanner);

			this.$alertsBannerContainer.hide();

			$('body').prepend(this.$alertsBannerContainer);
		},
 
		addAlerts: function (alerts) {
			if (this.$alerts === null)
				this.addAlertsContainer();

			var i;
			for (i = 0; i < alerts.length; i++)
			{
				this.buildAlertDom(alerts[i]).appendTo(this.$alerts);

				// Update latest timestamp
				if (alerts[i].date_modified_gmt > this.latestTimestamp)
					this.latestTimestamp = alerts[i].date_modified_gmt;
			}

			if (alerts.length > 0) {
				this.addControls(alerts.length);

				if (this.options['display-speed'] == "0") {
					this.$alertsBannerContainer.show(0);
				} else {
					this.$alertsBannerContainer.slideDown(this.options['display-speed']);
				}
			}
		},

		addControls: function (count) {
			if (this.options['display-styles'] != '') {
				$('<style/>', {
					'text': this.options['display-styles'],
					'type': 'text/css'
				}).prependTo(this.$alertsBannerContainer);
			}

			if (count > 1 && this.options['use-slider'] == '1') {
				var container = $('div.banner-alerts > div.alerts');

				$('<style/>', {
					'text': '#banner-alerts .alert { display: none; } #banner-alerts .alert:first-child { display: block; }',
					'type': 'text/css'
				}).prependTo(this.$alertsBannerContainer);

				this.$navigation = $('<div/>', {
					'class': 'navigation'
				}).appendTo(this.$controls);

				$('<a/>', {
					'text': '<',
					'class': 'previous',
					'href': '#',
				}).on('click', function() {
					container.find('.alert:last-child').prependTo(container);
					return false;
				}).appendTo(this.$navigation);

				$('<a/>', {
					'text': '>',
					'class': 'next',
					'href': '#',
				}).on('click', function() {
					container.find('.alert:first-child').appendTo(container);
					return false;
				}).appendTo(this.$navigation);
			}

			if (this.options['display-dismiss'] == '1') {
				$('<a/>', {
					'html': banner_alerts_vars.dismissText,
					'class': 'dismiss',
					'href': '#',
				}).on('click', function() {
					bannerAlerts.dismissAll();
					return false;
				}).appendTo(this.$controls);
			}
		},

		buildAlertDom: function (alert) {
			var container = $('<div/>', {
				'class': 'alert',
				'data-id': alert.id
			});

			if (this.options['display-title'] == '1')
			{
				$('<h2/>').html(alert.title).appendTo(container);
			}
			else if (this.options['display-title'] == '2')
			{
				$('<h2/>').append($('<a/>', {
					href: alert.permalink
				}).html(alert.title)).appendTo(container);
			}

			if (this.options['display-mode'] == '1')
			{
				$('<div/>').html(alert.content).appendTo(container);
			}
			else if (this.options['display-mode'] == '2')
			{
				$('<div/>').html(alert.excerpt).appendTo(container);
			}

			if (this.options['display-readmore'] == '1')
			{
				var readmore = $('<a/>', {
					'text': banner_alerts_vars.readMoreText,
					'class': 'read-more',
					'href': alert.permalink
				}).appendTo(container);
			}

			return container;
		},

		dismissAll: function () {
			if (this.options['display-speed'] == "0") {
				this.$alertsBannerContainer.hide(0);
			} else {
				this.$alertsBannerContainer.slideUp(this.options['display-speed']);
			}

			var timeout;
			if (typeof bannerAlerts.options['display-speed'] == 'number') {
				timeout = bannerAlerts.options['display-speed'];
			} else {
				timeout = 400;
			}

			setTimeout(function() {
				bannerAlerts.$alertsBannerContainer.find('style').remove();
			}, timeout);

			this.saveLatestTimestamp();
		}
	};

	var isCrawler = navigator.userAgent.match(/(googlebot)|(yandexbot)|(baiduspider)|(bingbot)|(slurp)/i);
	if (!isCrawler) {
		bannerAlerts.init();
	}

})(jQuery);
