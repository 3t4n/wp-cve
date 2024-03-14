/* JavaScript Cookie v2.2.1 | Copyright 2006, 2015 Klaus Hartl & Fagner Brack | MIT license */
!(function (t) {
	var e, i, _;
	'function' == typeof define && define.amd && (define(t), (e = !0)),
		'object' == typeof exports && ((module.exports = t()), (e = !0)),
		e ||
			((i = window.CookiesAFL),
			((_ = window.CookiesAFL = t()).noConflict = function () {
				return (window.CookiesAFL = i), _;
			}));
})(function () {
	function o() {
		for (var t = 0, e = {}; t < arguments.length; t++) {
			var i,
				_ = arguments[t];
			for (i in _) e[i] = _[i];
		}
		return e;
	}
	function r(t) {
		return t.replace(/(%[0-9A-Z]{2})+/g, decodeURIComponent);
	}
	return (function t(a) {
		function c() {}
		function i(t, e, i) {
			if ('undefined' != typeof document) {
				'number' == typeof (i = o({ path: '/' }, c.defaults, i)).expires &&
					(i.expires = new Date(+new Date() + 864e5 * i.expires)),
					(i.expires = i.expires ? i.expires.toUTCString() : '');
				try {
					var _ = JSON.stringify(e);
					/^[\{\[]/.test(_) && (e = _);
				} catch (t) {}
				(e = a.write
					? a.write(e, t)
					: encodeURIComponent(String(e)).replace(
							/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g,
							decodeURIComponent
					  )),
					(t = encodeURIComponent(String(t))
						.replace(/%(23|24|26|2B|5E|60|7C)/g, decodeURIComponent)
						.replace(/[\(\)]/g, escape));
				var u,
					n = '';
				for (u in i)
					i[u] &&
						((n += '; ' + u), !0 !== i[u] && (n += '=' + i[u].split(';')[0]));
				return (document.cookie = t + '=' + e + n);
			}
		}
		function e(t, e) {
			if ('undefined' != typeof document) {
				for (
					var i = {},
						_ = document.cookie ? document.cookie.split('; ') : [],
						u = 0;
					u < _.length;
					u++
				) {
					var n = _[u].split('='),
						c = n.slice(1).join('=');
					e || '"' !== c.charAt(0) || (c = c.slice(1, -1));
					try {
						var o = r(n[0]),
							c = (a.read || a)(c, o) || r(c);
						if (e)
							try {
								c = JSON.parse(c);
							} catch (t) {}
						if (((i[o] = c), t === o)) break;
					} catch (t) {}
				}
				return t ? i[t] : i;
			}
		}
		return (
			(c.set = i),
			(c.get = function (t) {
				return e(t, !1);
			}),
			(c.getJSON = function (t) {
				return e(t, !0);
			}),
			(c.remove = function (t, e) {
				i(t, '', o(e, { expires: -1 }));
			}),
			(c.defaults = {}),
			(c.withConverter = t),
			c
		);
	})(function () {});
}),
	(function () {
		'use strict';
		function g(t) {
			var e = null;
			return (
				'' !== t &&
					(e = (t = t.replace(/(^\w+:|^)\/\//, '')).substr(
						0,
						location.hostname.length
					)),
				location.hostname === e
			);
		}
		function h() {
			var e,
				t =
					void 0 !== nouvello_utm_tracker.attr_first_non_utm &&
					'1' == nouvello_utm_tracker.attr_first_non_utm,
				i = c('sess_landing'),
				_ = c('sess_referer'),
				u =
					((nouvello_utm_tracker.attribution = {
						status: '1',
						landing_page: { url: i },
						referer: { url: _ },
						utm_first_touch: {},
						utm_last_touch: {},
					}),
					{
						url: '',
						utm_source: '',
						utm_medium: '',
						utm_campaign: '',
						utm_term: '',
						utm_content: '',
						utm_id: '',
					}),
				n = c('utm_1st_url'),
				_ =
					(n
						? (u = {
								url: n,
								utm_source: y(n, 'utm_source'),
								utm_medium: y(n, 'utm_medium'),
								utm_campaign: y(n, 'utm_campaign'),
								utm_term: y(n, 'utm_term'),
								utm_content: y(n, 'utm_content'),
								utm_id: y(n, 'utm_id'),
						  })
						: t &&
						  ((u = {
								url: i,
								utm_source: y(i, 'utm_source'),
								utm_medium: y(i, 'utm_medium'),
								utm_campaign: y(i, 'utm_campaign'),
								utm_term: y(i, 'utm_term'),
								utm_content: y(i, 'utm_content'),
								utm_id: y(i, 'utm_id'),
						  }),
						  '' == _ || g(_)
								? ('' == u.utm_source && (u.utm_source = 'direct'),
								  '' == u.utm_medium && (u.utm_medium = 'none'))
								: ((i =
										(i = n =
											(n = '') !== (t = _)
												? (t = t.match(
														/^https?\:\/\/([^\/:?#]+)(?:[\/:?#]|$)/i
												  )) && t[1]
												: n) || 'unknown'),
								  '' == u.utm_source && (u.utm_source = i),
								  '' == u.utm_medium &&
										(u.utm_medium = (function (t) {
											var e,
												i = [
													/^(www\.)?google\.\w+/i,
													/^(www\.)?bing\.\w+/i,
													/(search.yahoo\.\w+)$/i,
													/^(www\.)?yahoo\.\w+/i,
													/^(www\.)?baidu\.\w+/i,
													/^(www\.)?yandex\.\w+/i,
													/^(www\.)?duckduckgo\.\w+/i,
													/(search.brave\.\w+)$/i,
												];
											for (e in i) if (t.match(i[e])) return 1;
										})(i)
											? 'organic'
											: 'referral'))),
					(nouvello_utm_tracker.attribution.utm_first_touch = u),
					c('utm_url'));
			nouvello_utm_tracker.attribution.utm_last_touch = _
				? {
						url: _,
						utm_source: y(_, 'utm_source'),
						utm_medium: y(_, 'utm_medium'),
						utm_campaign: y(_, 'utm_campaign'),
						utm_term: y(_, 'utm_term'),
						utm_content: y(_, 'utm_content'),
						utm_id: y(_, 'utm_id'),
				  }
				: u;
			try {
				e = new CustomEvent('nouvello_utm_event_attribution_set', {
					detail: { attribution: nouvello_utm_tracker.attribution },
				});
			} catch (t) {
				(e = document.createEvent('Event')).initEvent(
					'nouvello_utm_event_attribution_set',
					!1,
					!0
				),
					(e.detail = { attribution: nouvello_utm_tracker.attribution });
			}
			document.dispatchEvent(e);
		}
		function v(t) {
			return void 0 === CookiesAFL.get(t) || '' === CookiesAFL.get(t);
		}
		function k() {
			var t;
			(nouvello_utm_tracker.has_cookie_renewed = !0),
				(nouvello_utm_tracker.has_cookie_update = !1),
				(void 0 !== nouvello_utm_tracker.cookie_renewal &&
					'0' === nouvello_utm_tracker.cookie_renewal) ||
					((t = new XMLHttpRequest()).open(
						'POST',
						nouvello_utm_tracker.ajax_url,
						!0
					),
					t.setRequestHeader(
						'Content-type',
						'application/x-www-form-urlencoded'
					),
					t.send(
						'action=' +
							encodeURIComponent(nouvello_utm_tracker.action) +
							'&nonce=' +
							encodeURIComponent(nouvello_utm_tracker.nonce)
					));
		}
		function c(t) {
			t = CookiesAFL.get(nouvello_utm_tracker.cookie_prefix + t);
			return void 0 !== t ? t : '';
		}
		function y(t, e) {
			if ('string' == typeof t && '' != t) {
				var i;
				if (!('URLSearchParams' in window))
					return (
						(e = e.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]')),
						null === (i = new RegExp('[\\?&]' + e + '=([^&#]*)').exec(t))
							? ''
							: i[1]
					);
				try {
					var _ = new URL(t),
						u = new URLSearchParams(_.search).get(e);
					return null === u ? '' : encodeURIComponent(u);
				} catch (t) {}
			}
			return '';
		}
		function _(t) {
			var _, u, n, c, e, i, o, a, r, m, s, l, p, d, f, w, b;
			!(function () {
				var t =
					'(googlebot|bingbot|yandexbot|slurp|spider|robot|bot.html|bot.htm|facebookbot|facebookexternalhit|twitterbot|storebot|microsoftpreview)';
				'bots' in nouvello_utm_tracker &&
					nouvello_utm_tracker.bots &&
					(t = nouvello_utm_tracker.bots);
				if (
					0 < (t = String(t)).length &&
					'navigator' in window &&
					'userAgent' in window.navigator
				)
					if (
						-1 !==
						window.navigator.userAgent.toLowerCase().search(new RegExp(t, 'i'))
					)
						return 1;
				return;
			})() &&
				(('init' != t && 'current_timestamp' in nouvello_utm_tracker) ||
					((nouvello_utm_tracker.current_timestamp = Math.floor(
						new Date().getTime() / 1e3
					)),
					(nouvello_utm_tracker.has_cookie_renewed = !1),
					(nouvello_utm_tracker.has_cookie_update = !1),
					(nouvello_utm_tracker.attribution = { status: '0' })),
				(t = (_ = nouvello_utm_tracker.cookie_prefix) + 'cookie_expiry'),
				void 0 !== nouvello_utm_tracker.wp_consent_api_enabled &&
				nouvello_utm_tracker.wp_consent_api_enabled &&
				void 0 !== nouvello_utm_tracker.cookie_consent_category &&
				'function' == typeof wp_has_consent &&
				!wp_has_consent(nouvello_utm_tracker.cookie_consent_category)
					? ([
							'cookie_expiry',
							'sess_visit',
							'sess_landing',
							'sess_referer',
							'utm_1st_url',
							'utm_1st_visit',
							'utm_url',
							'utm_visit',
							'gclid_url',
							'gclid_visit',
							'fbclid_url',
							'fbclid_visit',
							'msclkid_url',
							'msclkid_visit',
							'main',
					  ].forEach(function (t, e) {
							t = nouvello_utm_tracker.cookie_prefix + t;
							CookiesAFL.remove(t, {
								secure: !0,
								domain: nouvello_utm_tracker.domain_info.domain,
								path: nouvello_utm_tracker.domain_info.path,
							});
					  }),
					  'user_has_active_attribution' in nouvello_utm_tracker &&
							1 == nouvello_utm_tracker.user_has_active_attribution &&
							((nouvello_utm_tracker.user_has_active_attribution = 0), k()))
					: ((u = CookiesAFL),
					  (n = nouvello_utm_tracker.current_timestamp),
					  (c = document.location.href),
					  (e = document.referrer),
					  (i = _ + 'main'),
					  (f = _ + 'sess_visit'),
					  (w = _ + 'sess_landing'),
					  (b = _ + 'sess_referer'),
					  (o = (m = _ + 'utm_') + '1st_url'),
					  (a = m + '1st_visit'),
					  (r = m + 'url'),
					  (m = m + 'visit'),
					  void (d = 0) !== u.get(t) &&
							((p = u.get(t)),
							(d = isNaN(p)
								? 'medium' === p
									? 30
									: 'short' === p
									? 7
									: 90
								: parseInt(p))),
					  (isNaN(d) || d <= 0) &&
							(d =
								void 0 !== nouvello_utm_tracker.cookie_expiry.days
									? nouvello_utm_tracker.cookie_expiry.days
									: 90),
					  (s = {
							expires: parseInt(d),
							secure: !0,
							domain: nouvello_utm_tracker.domain_info.domain,
							path: nouvello_utm_tracker.domain_info.path,
					  }),
					  (l = !1),
					  void 0 !== nouvello_utm_tracker.last_touch_window &&
							(l = parseInt(nouvello_utm_tracker.last_touch_window)),
					  (isNaN(l) || l <= 0) && (l = 1800),
					  v(t) &&
							(u.set(t, d, s), (nouvello_utm_tracker.has_cookie_update = !0)),
					  (v(f) || v(w)) &&
							(u.set(f, n, s),
							u.set(w, c, s),
							'' === e || g(e) || u.set(b, e, s),
							(nouvello_utm_tracker.has_cookie_update = !0)),
					  y(c, 'utm_source') &&
							((p = parseInt(u.get(m))),
							isNaN(p) && (p = 0),
							v(o)
								? (u.set(o, c, s),
								  u.set(a, n, s),
								  u.set(r, c, s),
								  u.set(m, n, s),
								  (nouvello_utm_tracker.has_cookie_update = !0))
								: (u.get(r) !== c || (u.get(r) === c && l < n - p)) &&
								  (u.set(r, c, s),
								  u.set(m, n, s),
								  (nouvello_utm_tracker.has_cookie_update = !0))),
					  ['gclid', 'fbclid', 'msclkid'].forEach(function (t, e) {
							var i = _ + t;
							'' !== y(c, t) &&
								((t = parseInt(u.get(i + '_visit'))),
								isNaN(t) && (t = 0),
								(u.get(i + '_url') !== c ||
									(u.get(i + '_url') === c && l < n - t)) &&
									(u.set(i + '_url', c, s),
									u.set(i + '_visit', n, s),
									(nouvello_utm_tracker.has_cookie_update = !0)));
					  }),
					  (t = { updated_ts: nouvello_utm_tracker.current_timestamp }),
					  void 0 !== u.get(i) &&
							void 0 !== (d = JSON.parse(u.get(i))).updated_ts &&
							((f = parseInt(d.updated_ts)),
							Number.isInteger(f) && (t.updated_ts = f)),
					  0 != nouvello_utm_tracker.has_cookie_update ||
							(void 0 !== nouvello_utm_tracker.cookie_renewal &&
								'force' !== nouvello_utm_tracker.cookie_renewal) ||
							((w = new Date(1e3 * nouvello_utm_tracker.current_timestamp)),
							(b = new Date(1e3 * t.updated_ts)),
							(86400 <=
								Math.abs(
									nouvello_utm_tracker.current_timestamp - t.updated_ts
								) ||
								0 < Math.abs(w.getDate() - b.getDate())) &&
								(nouvello_utm_tracker.has_cookie_update = !0)),
					  h(),
					  nouvello_utm_tracker.has_cookie_update &&
							((nouvello_utm_tracker.has_cookie_renewed = !1), k())));
		}
		function t(t) {
			var e,
				i = t.detail;
			if (void 0 === nouvello_utm_tracker.cookie_consent_category) return !0;
			for (e in i)
				i.hasOwnProperty(e) &&
					e === nouvello_utm_tracker.cookie_consent_category &&
					_('change');
		}
		var e;
		'undefined' != typeof nouvello_utm_tracker &&
			(document.addEventListener
				? document.addEventListener('wp_listen_for_consent_change', t)
				: document.attachEvent &&
				  document.attachEvent('wp_listen_for_consent_change', t),
			(e = function () {
				_('init');
			}),
			'complete' === document.readyState
				? e()
				: document.addEventListener
				? document.addEventListener('DOMContentLoaded', e)
				: document.attachEvent('onreadystatechange', function () {
						'complete' === document.readyState && e();
				  }));
	})();
