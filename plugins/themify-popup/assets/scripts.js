(($, win, doc) => {
	'use strict';

	const plugin = {};

	plugin.windowLoad = () => {
		let is_working = false;

		const isMobile = win.matchMedia("only screen and (max-width: 760px)").matches,
			setCookie = (cname, cvalue, exdays) => {
				const d = new Date();
				d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
				const expires = 'expires=' + d.toUTCString();
				doc.cookie = cname + '=' + cvalue + ';' + expires + ';SameSite=strict;path=/';
			},
			ThemifyLazy = el => {
				if (typeof Themify !== 'undefined') {
					Themify.lazyScroll(Themify.convert(el.querySelectorAll('[data-lazy]')).reverse(), true);
				}
			},
			getCookie = (cname, def) => {
				const name = cname + '=',
					ca = decodeURIComponent(doc.cookie).split(';');
				for (let i = 0, len = ca.length; i < len; ++i) {
					let c = ca[i];
					while (c.charAt(0) === ' ') {
						c = c.substring(1);
					}
					if (c.indexOf(name) === 0) {
						return c.substring(name.length, c.length);
					}
				}
				if (def === undefined) {
					def = '';
				}
				return def;
			},
			apply_animation = (el, name, callback) => {
				if (name === 'none') {
					if (callback) {
						callback(el);
					}
				} else {
					el.addEventListener('animationend', function() {
						// remove the animation classes after animation ends
						// required in order to apply new animation on close
						this.classList.remove('animated');
						this.style.animationName = '';
						if (callback) {
							callback(this);
						}
					}, {
						passive: true,
						once: true
					});

					el.classList.add('animated');
					el.style.animationName = name;
				}
			},
			closeSlideOut = el => {
				const popup = el.closest('.themify-popup');
				if (popup) {
					apply_animation(popup, popup.getAttribute('data-animation-exit'), item => {
						item.style.display = 'none';
					});
				}
			},
			close = el => {
				const style = el.getAttribute('data-style');
				if (style === 'classic' || style === 'fullscreen') {
					const close = el.parentNode.getElementsByClassName('mfp-close')[0];
					if (close) {
						close.click();
					}
				} else if (style === 'slide-out') {
					closeSlideOut(el);
				}
			},
			initCloseButtons = el => {
				const buttons = el.getElementsByClassName( 'themify-popup-close' );
				/* run a loop for .themify-popup-close to account for custom close buttons added in popup */
				for ( let i = buttons.length - 1; i > -1; --i ) {
					if ( ! buttons[ i ].dataset.init ) {
						buttons[ i ].addEventListener( 'click', function( e ) {
							e.preventDefault();
							close( el );
						} );
					}
					buttons[ i ].dataset.init = 1;
				}
			},
			open = el => {
				// keep track of how many times visitor has seen this popup
				const id = el.id.replace('themify-popup-', ''),
					view_count = getCookie('themify-popup-' + id, 0) * 1 || 0,
					limit = el.getAttribute('data-limit-count');

				// visitor has seen this popup already? Bail!
				if (limit && view_count >= limit) {
					return;
				}

				setCookie('themify-popup-' + id, view_count + 1, el.getAttribute('data-cookie-expiration') || 1);

				const style = el.getAttribute('data-style'),
					auto_close = el.getAttribute('data-auto-close');

				if (style === 'classic' || style === 'fullscreen') {

					// do not display the popup if one is showing already
					if (is_working === true || doc.body.classList.contains('themify-popup-showing')) {
						return;
					}
					is_working = true;

					const exit_animation = el.getAttribute('data-animation-exit'),
						classes = 'themify-popup-showing themify-popup-style-' + style + ' themify-popup-showing-' + id + ' tf-popup-position-' + el.getAttribute('data-position'),
						magnificCallback = () => {
							$.magnificPopup.open({
								closeOnBgClick: (style === 'fullscreen' || el.getAttribute('data-close-overlay') === 'no') ? false : true,
								enableEscapeKey: el.getAttribute('data-enableescapekey') === 'yes',
								removalDelay: exit_animation === 'none' ? 0 : 1000,
								items: {
									src: el,
									type: 'inline'
								},
								callbacks: {
									open() {
										const closeBtn = this.content[0].getElementsByClassName('mfp-close')[0];
										closeBtn.classList.add('themify-popup-close');
										// move close button to the top-right corner of the screen
										this.contentContainer[0].appendChild(closeBtn);
										initCloseButtons( el );

										doc.body.classList.add(...classes.split(' '));

										this.content[0].style.display = 'block';
										ThemifyLazy(this.contentContainer[0]);
										apply_animation(this.contentContainer[0], el.getAttribute('data-animation'), popup => {
											ThemifyLazy(popup);
										});

										/* force elements inside the popup to respond to the popup being opened */
										win.dispatchEvent(new Event('resize'));

										is_working = false;
									},
									beforeClose() {
										apply_animation(this.contentContainer[0], exit_animation, popup => {
											popup.style.display = 'none';
											const media = popup.querySelectorAll('video,audio');
											for (let i = media.length - 1; i > -1; --i) {
												if (!media[i].paused) {
													media[i].pause();
												}
											}
										});
									},
									close() {
										doc.body.classList.remove(...classes.split(' '));
									}
								}
							});
						};
					if (typeof $.fn.magnificPopup === 'function') {
						magnificCallback();
					} else {
						$.getScript(themifyPopup.assets + '/lightbox.min.js', magnificCallback);
					}
				} else if (style === 'slide-out') {

					const ev = 'onorientationchange' in window ? 'orientationchange' : 'resize',
						slide_out_fix_position = el => {
							const cl = el.classList;
							let prop = '',
								v = '';
							if (cl.contains('bottom-center') || cl.contains('top-center')) {
								prop = 'margin-left';
								v = getComputedStyle(el).getPropertyValue('width');
							} else if (cl.contains('center-left') || cl.contains('center-right')) {
								prop = 'margin-top';
								v = getComputedStyle(el).getPropertyValue('height');
							}
							if (prop !== '') {
								v = parseFloat(v);
								el.style.setProperty(prop, ((v / 2) * -1) + 'px');
							}
						};
					let closeBtn = el.querySelector( ':scope > button.themify-popup-close' );
					if ( ! closeBtn ) {
						closeBtn = doc.createElement('button');
						closeBtn.className = 'themify-popup-close';
						closeBtn.textContent = 'x';
						el.appendChild(closeBtn);
						initCloseButtons( el );
					}

					el.style.display = 'block';

					slide_out_fix_position(el);
					let resizeTimer = null;
					win.addEventListener(ev, () => {
						if (resizeTimer) {
							cancelAnimationFrame(resizeTimer);
						}
						resizeTimer = requestAnimationFrame(() => {
							slide_out_fix_position(el);
						});
					}, {
						passive: true
					});

					ThemifyLazy(el);
					apply_animation(el, el.getAttribute('data-animation'), item => {
						ThemifyLazy(item);
					});

				} else {
					el.style.display = 'block';
				}

				if (auto_close) {
					setTimeout(() => {
						close(el);
					}, auto_close * 1000);
				}
			},
			items = doc.getElementsByClassName('themify-popup'),
			moveItems = [],
			scrollItems = [];


		if (typeof themifyPopup !== 'undefined' && themifyPopup.count_views === '1') {
			/**
			 * Keep track of how many pages of the website the visitor has seen.
			 * This is saved in the "themify_popup_page_view" cookie.
			 */
			setCookie('themify_popup_page_view', (getCookie('themify_popup_page_view', 1) + 1), 1);
		}
		doc.body.addEventListener('click', e => {
			if ( ! e.target ) {
				return;
			}
			let target = e.target.tagName === 'A' ? e.target : e.target.closest( 'a' );
			if ( target ) {
				const id = target.getAttribute('href');
				if (id && id.indexOf('#themify-popup-') === 0) {
					const el = doc.getElementById(id.replace('#', ''));
					if (el) {
						e.preventDefault();
						open(el);
					}
				}
			}
		});
		for (let i = items.length - 1; i > -1; --i) {
			let el = items[i],
				type = el.getAttribute('data-trigger'),
				display = el.getAttribute('data-display');

			if ((isMobile === false && display === 'mobile') || (isMobile === true && display === 'desktop')) {
				continue;
			}


			if (type === 'default' || type === 'pageview') {
				open(el);
			} else if (type === 'timedelay') {
				setTimeout(() => {
					open(el);
				}, el.getAttribute('data-time-delay') * 1000);
			} else if (type === 'scroll') {
				scrollItems.push({
					el: el,
					px: el.getAttribute('data-scroll-on') === 'px',
					pos: el.getAttribute('data-scroll-position')
				});
			} else if (type === 'exit') {
				moveItems.push(el);
			}
		}
		if (scrollItems[0] !== undefined) {
			let scrollTimer = null;
			const getScrollPercent = () => {
					const h = doc.documentElement,
						b = doc.body,
						st = 'scrollTop',
						sh = 'scrollHeight';
					return (h[st] || b[st]) / ((h[sh] || b[sh]) - h.clientHeight) * 100;
				},
				scrollCallback = function() {
					if (scrollTimer) {
						cancelAnimationFrame(scrollTimer);
					}
					scrollTimer = requestAnimationFrame(() => {
						const scroll = getScrollPercent();
						for (let i = scrollItems.length - 1; i > -1; --i) {
							if ((scrollItems[i].px === true && this.scrollY > scrollItems[i].pos) || (scrollItems[i].px === false && scroll > scrollItems[i].pos)) {
								open(scrollItems[i].el);
								scrollItems.splice(i, 1);
							}
						}
						if (scrollItems.length === 0) {
							this.removeEventListener('scroll', scrollCallback, {
								passive: true
							});
						}
					});
				};
			win.addEventListener('scroll', scrollCallback, {
				passive: true
			});
		}
		if (moveItems[0] !== undefined) {
			const ev = (('ontouchstart' in win) || navigator.msMaxTouchPoints > 0) ? 'touchstart' : 'mousemove',
				threshold = 100,
				moveCallback = function(e) {
					if ( is_working === true ) {
						return;
					}
					if (e.touches) {
						/* detect scrolling up, interpreted as user wanting to bring down the browser's address bar */
						const initialY = e.touches[0].clientY + threshold; /* how much scroll should occur to consider it an swipe */
						this.addEventListener('touchend', e => {
							if (e.changedTouches[0].clientY > initialY) {
								for (let i = moveItems.length - 1; i > -1; --i) {
									open(moveItems[i]);
									moveItems.splice(i, 1);
								}
							}
						}, {
							passive: true,
							once: true
						});
					} else {
						/* detect when mouse goes near the top-edge of the screen */
						const offset = (e.pageY - win.pageYOffset) < 7;
						for (let i = moveItems.length - 1; i > -1; --i) {
							if (offset === true) {
								open(moveItems[i]);
								moveItems.splice(i, 1);
							}
						}
					}
					if (moveItems.length === 0) {
						this.removeEventListener(e.type, moveCallback, {
							passive: true
						});
					}
				};
			doc.addEventListener(ev, moveCallback, {
				passive: true
			});

		}
	};
	if (doc.readyState === 'complete') {
		plugin.windowLoad();
	} else {
		win.addEventListener('load', plugin.windowLoad, {
			once: true,
			passive: true
		});
	}

	/* expose to public */
	win.ThemifyPopup = plugin;

})(jQuery, window, document);