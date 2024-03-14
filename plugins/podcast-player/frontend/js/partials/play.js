import props from './variables';

class PlayEpisode {

	/**
	 * Currently clicked list item.
	 */
	listItem;

	/**
	 * Load more class object.
	 */
	loadMore;

	/**
	 * Stat time-out.
	 */
	statTimeOut = null;

	/**
	 * Manage podcast tabs elements.
	 * 
	 * @since 1.3
	 * 
	 * @param {string} id Podcast player ID.
	 */
	constructor(id) {

		this.id = id;
		this.podcast = props[id].podcast;
		this.list = props[id].list;
		this.episode = props[id].episode;
		this.player = props[id].player;
		this.mediaObj = props[id].mediaObj;
		this.media = this.mediaObj.media;
		this.instance = props[id].instance;
		this.modalObj = props[id].modal;
		this.single = props[id].single;
		this.singleWrap = props[id].singleWrap;
		this.data = props.podcastPlayerData;
		this.msgMediaObj = props[id].msgMediaObj;
		this.msgControls = this.msgMediaObj ? jQuery(this.msgMediaObj.controls) : false;
		this.msgMedia = this.msgMediaObj ? this.msgMediaObj.media : false;
		this.msgFreqCounter = 0;
		this.controls = jQuery(this.mediaObj.controls);
		this.layers = this.controls.prev('.ppjs__layers');
		this.plBtn = this.controls.find( '.ppjs__playpause-button > button' );
		this.prevBtn = this.podcast.find('.pp-prev-btn').attr('disabled', true);
		this.nxtBtn = this.podcast.find('.pp-next-btn');
		this.copylink = this.podcast.find('.ppsocial__copylink');
		this.copyField = this.podcast.find('input.pp-copylink');
		this.playingAmsg = false;
		this.playAmsg = false;
		this.played = false;
		this.settings = props[id].settings;
		this.isPremium = this.settings.isPremium;
		this.timeOut = false;
		this.playTime = false;
		this.runCookieUpdate = false;
		this.listItem = false;
		this.audioFirstPlay = true;
		setTimeout(() => {this.timeOut = true}, 3000);

		this.events();
	}

	/**
	 * PodcastTabs event handling.
	 * 
	 * @since 1.3
	 */
	events() {

		const _this = this;
		const modal = _this.modalObj ? _this.modalObj.modal : false;
		if (_this.podcast.hasClass('modern')) {
			_this.list.on('click', '.pod-entry__mplay, .pod-entry__title', function(e) {
				e.preventDefault();
				_this.listItem = jQuery(this).closest('.episode-list__entry, .episode-list__search-entry');
				_this.modPrevBtn();
				_this.modNextBtn();
				_this.play(true);

				// Displable buttons if search window is opened.
				if (_this.podcast.hasClass('search-opened')) {
					_this.prevBtn.attr('disabled', true);
					_this.nxtBtn.attr('disabled', true);
				}
			});
			_this.list.on('click', '.pod-entry__mscript', function(e) {
				const $this = jQuery(this);
				if ($this.hasClass('pp-entry__mpost')) return;
				const listItem = $this.closest('.episode-list__entry, .episode-list__search-entry');
				e.preventDefault();
				_this.showModernEpisodeContent(listItem);
			});
		} else if (! _this.podcast.hasClass('postview')) {
			_this.list.on('click', '.episode-list__entry, .episode-list__search-entry', function(e) {
				e.preventDefault();
				_this.listItem = jQuery(this);
				_this.modPrevBtn();
				_this.modNextBtn();
				_this.play(true);

				// Displable buttons if search window is opened.
				if (_this.podcast.hasClass('search-opened')) {
					_this.prevBtn.attr('disabled', true);
					_this.nxtBtn.attr('disabled', true);
				}
			});
		} else {
			_this.list.on('click', '.pod-entry__title a, .pod-entry__featured', function(e) {
				const $this = jQuery(this);
				if ( $this.hasClass('fetch-post-title') ) return;
				const pid = `pp-podcast-${_this.instance}`;
				const info = _this.data[pid].load_info;
				let hideDescription = info ? (info.args ? info.args.hddesc : false) : false;
				hideDescription = hideDescription ? hideDescription : false;
				const isModalView = (! $this.hasClass('pod-entry__featured') && ! hideDescription) || _this.mediaObj.isVideo;
				e.preventDefault();
				_this.listItem = $this.closest('.pod-entry');
				_this.modPrevBtn();
				_this.modNextBtn();
				_this.playModal(isModalView);

				// Let's shift focus inside modal window for better keyboard navigation.
				if (isModalView && modal) {
					setTimeout(() => {
						modal.find('.ppjs__audio-controls button').first().focus();
					}, 200);
				}

				// Displable buttons if search window is opened.
				if (_this.podcast.hasClass('search-opened')) {
					_this.prevBtn.attr('disabled', true);
					_this.nxtBtn.attr('disabled', true);
				}
			});

			_this.list.on('click', '.pod-entry__excerpt a', function(e) {
				const $this = jQuery(this);
				if (! $this.hasClass('fetch-feed-title') ) return;
				e.preventDefault();
				_this.showEpisodeContent($this);
			});
		}

		// Play Modal playlist.
		if (modal) {
			const listWrapper = modal.find('.pp-modal-tabs-list');
			if (listWrapper.length) {
				listWrapper.on('click', '.pod-entry__mplay,.pod-entry__title a, .pod-entry__featured', function(e) {
					const entry = jQuery(this).closest('.pod-entry');
					const minstance = modal.find('.modal-' + _this.instance);
					if (minstance.length) {
						e.preventDefault();
						_this.listItem = entry;
						_this.modPrevBtn();
						_this.modNextBtn();
						_this.playModal(false);
					}
				});
			}
		}

		this.prevBtn.on('click', this.playPreviousEpisode.bind(this));
		this.nxtBtn.on('click', this.playNextEpisode.bind(this));

		if (this.msgMedia) {
			this.msgMedia.addEventListener('ended', this.msgMediaEnded.bind(this));
		}
		this.plBtn.on('click', this.playPauseBtn.bind(this));
		if (this.layers) {
			this.layers.find('.ppjs__overlay-play').on('click', this.playPauseBtn.bind(this));
		}
		this.copylink.on('click', function(e) {
			e.preventDefault();
			_this.copyLink();
		});
		jQuery(window).on( 'load', function() {
			if (! _this.podcast.parent().hasClass('pp-sticky-player') ) {
				return;
			}
			_this.listItem = _this.podcast.find('.pod-entry').first();
			_this.modPrevBtn();
			_this.modNextBtn();
			_this.showStickyPlayer();
		} );

		const isCookieEnabled = this.settings.cookies || false;
		// If cookie creation is allowed for podcast player.
		if (this.isPremium && isCookieEnabled) {
			this.mediaObj.media.addEventListener('playing', this.updateCookie.bind(this));
			this.mediaObj.media.addEventListener('paused', this.stopCookieUpdate.bind(this));
			this.mediaObj.media.addEventListener('ended', this.deleteCookie.bind(this));
		}

		this.mediaObj.media.addEventListener('ended', this.mediaEnded.bind(this));

		// If analytics creation is allowed for podcast player.
		if (this.settings.isPremium && this.settings.analytics) {
		    this.media.addEventListener('play', this.playAnalytics.bind(this));
		}
	}

	/**
	 * Start updating cookie if current podcast is playing.
	 *
	 * @since 6.4.0
	 */
	updateCookie() {
		if (! this.listItem) return;
		const pid = `pp-podcast-${this.instance}`;
		const rdata = this.data[pid] ? this.data[pid].rdata : false;
		const id = this.listItem.attr('id') || this.listItem.attr('data-pid');
		let details = false;

		// Update podcast data on single podcast wrapper.
		if ( this.listItem.hasClass( 'episode-list__search-entry' ) ) {
			details = this.data.search ? this.data.search[id] : false;
		} else {
			details = this.data[pid] ? this.data[pid][id] : false;
		}
		if ( this.playTime ) {
			this.mediaObj.media.currentTime = this.playTime;
			this.playTime = false;
		}

		if ( this.isPremium && false !== rdata && 'undefined' !== typeof details && details ) {
			if ( 'feedurl' === rdata.from ) {
				const query = {
					fmethod  : 'feed',
					ppplayer : rdata.fprint,
					ppepisode: details.key,
					time     : 0,
				}
				props.createCookie('ppCookie', query, 7);
				const src = this.mediaObj.getSrc();
				this.runCookieUpdate = true;
				this.updateEpisodeCookie(src);
			} else if ( 'posts' === rdata.from ) {
				const query = {
					fmethod: 'post',
					epid   : details.id,
					time   : 0,
				}
				props.createCookie('ppCookie', query, 7);
				const src = this.mediaObj.getSrc();
				this.runCookieUpdate = true;
				this.updateEpisodeCookie(src);
			} else if ( 'link' === rdata.from ) {
				const query = {
					fmethod   : 'link',
					audioSrc  : details.src,
					audioTitle: details.title,
					time      : 0,
				}
				props.createCookie('ppCookie', query, 7);
				const src = this.mediaObj.getSrc();
				this.runCookieUpdate = true;
				this.updateEpisodeCookie(src);
			}
		}
	}

	/**
	 * Update currently playing episode cookie.
	 *
	 * @since 6.4.0
	 *
	 * @param {str} src Media src.
	 */
	updateEpisodeCookie(src) {
		const newSrc = this.mediaObj.getSrc();
		if (src !== newSrc || ! this.runCookieUpdate) return;
		const details = props.readCookie('ppCookie');
		details.time = this.media.currentTime;
		props.createCookie('ppCookie', details, 7);
		setTimeout(() => { this.updateEpisodeCookie(src) }, 4000);
	}

	/**
	 * Stop updating podcast if podcast episode is paused.
	 *
	 * @since 6.4.0
	 */
	stopCookieUpdate() {
		this.runCookieUpdate = false;
	}

	/**
	 * Delete the cookie once running episode has been ended.
	 *
	 * @since 6.4.0
	 */
	deleteCookie() {
		this.runCookieUpdate = false;
		props.eraseCookie('ppCookie');
	}

	/**
	 * Play previous Episode.
	 * 
	 * @since 2.0
	 */
	playPreviousEpisode() {
		this.prevBtn.attr('disabled', true);
		if (this.listItem && !this.listItem.hasClass('episode-list__search-entry')) {
			const item = this.listItem.prev('.episode-list__entry');
			if (item.length) {
				this.listItem = item;
				this.modNextBtn();
				if (this.controls.parents('#pp-modal-window').length) {
					this.playModal(this.modalObj.modal && this.modalObj.modal.hasClass('modal-view'));
				} else {
					this.play();
				}
				const newItem = this.listItem.prev('.episode-list__entry');
				if (newItem.length) this.prevBtn.attr('disabled', false);
				return;
			}
		}
		this.prevBtn.blur();
	}

	/**
	 * Modify status of previous Episode Button.
	 * 
	 * @since 2.0
	 */
	modPrevBtn() {
		this.prevBtn.attr('disabled', true);
		if (this.listItem && !this.listItem.hasClass('episode-list__search-entry')) {
			if (this.listItem.prev('.episode-list__entry').length) {
				this.prevBtn.attr('disabled', false);
			}
		}
	}

	/**
	 * Play Next Episode.
	 * 
	 * @since 2.0
	 */
	playNextEpisode() {
		if (this.listItem && !this.listItem.hasClass('episode-list__search-entry')) {
			const item = this.listItem.next('.episode-list__entry');
			if (item.length) {
				this.listItem = item;
				this.modPrevBtn();
				if (this.controls.parents('#pp-modal-window').length) {
					this.playModal(this.modalObj.modal && this.modalObj.modal.hasClass('modal-view'));
				} else {
					this.play();
				}
				const newItem = this.listItem.next('.episode-list__entry');
				if (!newItem.length) {
					this.checkforNextEpisode(false);
				}
			} else {
				this.checkforNextEpisode(true);
			}
		} else {
			const firstElem = this.list.find('.episode-list__entry').first();
			if (firstElem.length) {
				this.listItem = firstElem;
				if ( this.podcast.hasClass('postview') ) {
					this.playModal(this.modalObj.modal && this.modalObj.modal.hasClass('modal-view'));
				} else {
					this.play();
				}
			}
		}
		this.nxtBtn.blur();
	}

	/**
	 * Check for next Next Episode.
	 * 
	 * @since 2.0
	 * 
	 * @param {bool} play
	 */
	checkforNextEpisode(play) {
		if (this.podcast.hasClass('single-audio') || this.podcast.hasClass('single-episode')) {
			this.nxtBtn.attr('disabled', true);
			return;
		}
		if (!this.loadMore) this.loadMore = props[this.id].loadMore;
		if (this.loadMore) {
			const fromModal = this.listItem.attr('data-pid') ? true : false;
			this.loadMore.loadEpisodes(fromModal);
			const item = this.listItem.next('.episode-list__entry');
			if (item.length) {
				if (play) {
					this.listItem = item;
					this.modPrevBtn();
					this.play();
				}
			} else {
				this.nxtBtn.attr('disabled', true);
			}
		}
	}

	/**
	 * Modify status of previous Episode Button.
	 * 
	 * @since 2.0
	 */
	modNextBtn() {
		if (this.nxtBtn.is(':disabled')) {
			if (this.listItem && !this.listItem.hasClass('episode-list__search-entry')) {
				if (this.listItem.next('.episode-list__entry').length) {
					this.nxtBtn.attr('disabled', false);
				}
			}
		}
		if (this.listItem && !this.listItem.hasClass('episode-list__search-entry')) {
			const newItem = this.listItem.next('.episode-list__entry');
			if (!newItem.length) {
				this.checkforNextEpisode();
			}
		}
	}

	/**
	 * Show episode excerpt in a modal window.
	 * 
	 * @since 5.0
	 * 
	 * @param {Object} elem
	 */
	showEpisodeContent(elem) {
		const entry = elem.closest('.pod-entry');
		const pid = `pp-podcast-${this.instance}`;
		const id = entry.attr('id');
		const wrapper = entry.find('.pod-entry__excerpt');
		let details;

		// Get podcast data.
		if ( entry.hasClass( 'episode-list__search-entry' ) ) {
			details = this.data.search[id];
		} else {
			details = this.data[pid][id];
		}

		wrapper.html(details.description).addClass('expanded');
	}

	/**
	 * Show episode content for modern player.
	 * 
	 * @since 5.3
	 * 
	 * @param {Object} elem
	 */
	showModernEpisodeContent(elem) {
		const pid = `pp-podcast-${this.instance}`;
		const id = elem.attr('id');
		let details;

		// Get podcast data.
		if ( elem.hasClass( 'episode-list__search-entry' ) ) {
			details = this.data.search[id];
		} else {
			details = this.data[pid][id];
		}

		this.single.find('.episode-single__title').html(details.title);
		this.single.find('.episode-single__author').html(details.author);
		this.single.find('.episode-single__description').html(details.description);

		this.episode.slideDown('fast');
	}

	/**
	 * Common actions before plating podcast episode.
	 * 
	 * @since 2.0
	 */
	common() {
		const pid = `pp-podcast-${this.instance}`;
		const rdata = this.data[pid] ? this.data[pid].rdata : false;
		const info = this.data[pid] ? this.data[pid].load_info : false;
		const args = info ? info.args : false;
		const id = this.listItem.attr('id') || this.listItem.attr('data-pid');
		const modal = this.modalObj ? this.modalObj.modal : false;
		const pbr = props[this.id].playRate || 1;
		let share = this.controls.find('.ppshare__social');
		let active, details, ppurl, pptitle, src, excerpt, elen, eunit, teaser;

		// Remove active class from previously active episode.
		active = this.list.find('.activeEpisode')
		if ( 0 < active.length ) {
			active.removeClass( 'activeEpisode media-playing' );
		}

		// Remove active class from previously active modal episode.
		if (modal) {
			const listWrapper = modal.find('.pp-modal-tabs-list');
			if (listWrapper.length) {
				listWrapper.find('.activeEpisode').removeClass('activeEpisode media-playing');
			}
		}

		if (this.msgMediaObj) {
			if ( ! this.msgMediaObj.media.paused ) {
				this.msgMediaObj.media.pause();
			}
			this.msgMediaObj.media.currentTime = 0;
		}

		this.played = true;
		this.playingAmsg = false;
		this.player.removeClass('msg-playing');

		// Update podcast data on single podcast wrapper.
		if ( this.listItem.hasClass( 'episode-list__search-entry' ) ) {
			details = this.data.search[id];
		} else {
			details = this.data[pid][id];
		}

		// Generate social sharing links.
		ppurl   = encodeURIComponent(details.link);
		pptitle = encodeURIComponent(details.title);
		src = jQuery("<div>").html(details.src).html().replace(/&amp;/g, "&");

		if (this.isPremium && false !== rdata && 'feedurl' === rdata.from) {
			if ('undefined' !== typeof details.key) {
				const query = {
					ppplayer : rdata.fprint,
					ppepisode: details.key,
				}
				const qstr = jQuery.param( query );
				const plink = rdata.permalink;
				ppurl = plink ? (plink + ( plink.indexOf('?') < 0 ? '?' : '&') + qstr) : ppurl;
				ppurl = encodeURIComponent(ppurl);
			}
		} else if (this.isPremium) {
			const query = {
				sharedby : 'pplayer',
			}
			const qstr = jQuery.param( query );
			ppurl = ppurl + ( ppurl.indexOf('?') < 0 ? '?' : '&') + encodeURIComponent(qstr);
		}

		const fburl = "https://www.facebook.com/sharer.php?u=" + ppurl;
		const twurl = "https://twitter.com/intent/tweet?url=" + ppurl + "&text=" + pptitle;
		const liurl = "https://www.linkedin.com/shareArticle?mini=true&url=" + ppurl;
		const mail  = "mailto:?subject=" + pptitle + "&body=Link:" + ppurl;

		this.listItem.addClass( 'activeEpisode media-playing' );
		this.episode.find( '.episode-single__title' ).html( details.title );
		this.episode.find( '.episode-single__author > .single-author' ).html( details.author );
		this.controls.find('.ppjs__episode-title').html(details.title);
		this.episode.find( '.episode-single__description' ).html( details.description );

		if (this.modalObj) {
			const auxWrap = this.modalObj.modal.find('.pp-modal-aux-wrapper.aux-open');
			if (auxWrap.length) {
				auxWrap.find( '.episode-single__title' ).html( details.title );
				auxWrap.find( '.episode-single__author > .single-author' ).html( details.author );
				auxWrap.find( '.episode-single__description' ).html( details.description );
				auxWrap.find('.pp-modal-tabs-list').hide();
				auxWrap.find('.lists-tab').removeClass('selected');
				auxWrap.find('.content-tab').addClass('selected');
				auxWrap.find('.pp-modal-tabs-content').fadeIn();
				auxWrap.animate({ scrollTop: 0 }, 400 );
			}
		}

		share.find( '.ppsocial__facebook' ).attr( 'href', fburl );
		share.find( '.ppsocial__twitter' ).attr( 'href', twurl );
		share.find( '.ppsocial__linkedin' ).attr( 'href', liurl );
		share.find( '.ppsocial__email' ).attr( 'href', mail );
		share.find( 'input.pp-copylink' ).val(decodeURIComponent(ppurl));
		this.controls.find( '.ppshare__download' ).attr( 'href', src );
		teaser = this.podcast.data('teaser');
		elen = this.podcast.data('elength');
		eunit = this.podcast.data('eunit');
		if (!this.podcast.hasClass('special-style')) {
			excerpt = this.episode.find('.episode-single__description');
			if ('full' === teaser && excerpt.length) {
				this.controls.find('.ppjs__more').css('display', 'none');
				this.controls.find('.ppjs__episode-full-content').html(excerpt.html());
			} else if ('none' === teaser) {
				this.controls.find('.ppjs__episode-excerpt').hide();
			} else if ('' === teaser && elen) {
				excerpt = excerpt.length ? excerpt.text().trim() : false;
				if (excerpt && excerpt.length) {
					if (eunit) {
						if (excerpt.length > elen) {
							excerpt = excerpt.substr(0, elen);
							this.controls.find('.ppjs__more').css('display', 'inline');
						} else {
							this.controls.find('.ppjs__more').css('display', 'none');
						}
					} else if (excerpt.split(/\s+/).length > elen) {
						excerpt = excerpt.split(/\s+/).splice(0,elen).join(" ");
						this.controls.find('.ppjs__more').css('display', 'inline');
					} else {
						this.controls.find('.ppjs__more').css('display', 'none');
					}
					this.controls.find('.ppjs__excerpt-content').text(excerpt);
					this.controls.find('.ppjs__episode-excerpt').show();
				} else {
					this.controls.find('.ppjs__episode-excerpt').hide();
				}
			}
		}

		this.mediaObj.setSrc( src );
		this.audioFirstPlay = true;
		this.mediaObj.load();
		this.mediaObj.media.playbackRate = pbr;
		this.playMessage();
		return true;
	}

	/**
	 * Display btn image.
	 * 
	 * @since 2.3
	 */
	btnImage() {
		const pid = `pp-podcast-${this.instance}`;
		const id = this.listItem.attr('id') || this.listItem.attr('data-pid');
		const dsrc = this.player.find('.ppjs__img-btn').attr('data-src');
		let details;

		// Update podcast data on single podcast wrapper.
		if ( this.listItem.hasClass( 'episode-list__search-entry' ) ) {
			details = this.data.search[id];
		} else {
			details = this.data[pid][id];
		}

		const { title, featured, fset } = details;
		let fratio = details.fratio || 1;

		if (featured) {
			this.player.find('.ppjs__img-btn').attr('srcset', fset).attr('src', featured).attr('alt', title).closest('.ppjs__img-wrapper').removeClass('noimg');

			fratio = fratio * 100;
			fratio = fratio + '%';
			// Add image aspect ratio to the styling element.
			this.player.find('.ppjs__img-btn-style').css('padding-top', fratio);

			// Compatibility with lazy load plugins (Only if they use data-src attribute).
			if ( typeof dsrc !== typeof undefined && dsrc !== false ) {
				this.player.find('.ppjs__img-btn').attr('data-srcset', fset).attr('data-src', featured);
			}
			if (this.modalObj) {
				this.modalObj.modal.find('.ppjs__img-btn').attr('scrset', fset).attr('src', featured).attr('alt', title).closest('.ppjs__img-wrapper').removeClass('noimg');
			}
		} else {
			this.player.find('.ppjs__img-wrapper').addClass('noimg');
			if (this.modalObj) {
				this.modalObj.modal.find('.ppjs__img-wrapper').addClass('noimg');
			}
		}
	}

	/**
	 * Play/pause media on button click.
	 * 
	 * @since 1.3
	 */
	playPauseBtn() {

		// Playing the podcast for the first time after page load.
		if (false === this.played) {
			this.played = true;
			this.listItem = this.list.find('.episode-list__entry').first();

			if (! this.listItem.length) {
				const id = `ppe-${this.instance}-1`;
				this.listItem = jQuery('<div />', { class: 'episode-list__entry', id: id })
			}

			this.play();
			return;
		}

		if (!this.plBtn.parents('#pp-modal-window').length) {
			if (this.modalObj.modal && this.modalObj.modal.hasClass('pp-modal-open')) {
				this.modalObj.returnElem();
			}
		}

		if (this.mediaObj.media.paused) {
			this.mediaObj.media.play();
			if (this.listItem) this.listItem.addClass('activeEpisode media-playing');
		} else {
			this.mediaObj.media.pause();
			if (this.listItem) this.listItem.removeClass('activeEpisode media-playing');
		}
	}

	/**
	 * Play episode in player view.
	 * 
	 * @since 1.3
	 *
	 * @param {bool} isAnimate
	 */
	play(isAnimate = false) {
		const _this = this;

		// If clicked on the currently playing episode.
		if (this.listItem.hasClass( 'activeEpisode' )) {
			this.listItem.removeClass( 'activeEpisode' );
			this.mediaObj.media.pause();
			this.player.removeClass('msg-playing');
			this.playingAmsg = false;
			if (this.msgMediaObj) {
				this.msgMediaObj.media.pause();
				this.msgMediaObj.media.currentTime = 0;
			}
			return;
		}

		if (this.modalObj.modal && this.modalObj.modal.hasClass('pp-modal-open')) {
			this.modalObj.returnElem();
		}

		// Wait for data preload, if not already loaded.
		if (false === props[this.id].fetched) {
			if (false === this.timeOut) {
				setTimeout(() => {_this.play()}, 200);
			}
			return;
		}

		// Perform common actions before playing podcast.
		this.common();
		this.btnImage()

		this.singleWrap.addClass('activePodcast');

		// Auto play the media.
		if (!this.playingAmsg) this.mediaObj.media.play();

		// Scroll window to top only on mobile devices.
		if (isAnimate && !props.isLrgScrn) {
			// Scroll window to top of the single episode for better UX.
			jQuery( 'html, body' ).animate({ scrollTop: this.player.offset().top }, 400 );
		}
	}

	/**
	 * Play episode in post view.
	 * 
	 * Episodes will be played in a Modal window.
	 * 
	 * @since 2.0
	 * 
	 * @param {bool} isModalView
	 */
	playModal(isModalView) {
		const _this = this;
		if (! this.modalObj) return;
		// If current episode is already playing. Let's pause it.
		if (this.listItem.hasClass('activeEpisode')) {
			if (isModalView) {
				this.modalObj.modal.removeClass('inline-view').addClass('modal-view');
				this.modalObj.scrollDisable();
				if (!this.playingAmsg) {
					const wrapper = this.modalObj.modal.find('.episode-primary__title');
					let customTitle = wrapper.find('.episode-single__title');
					customTitle.html(this.episode.find( '.episode-single__title' ).html());
					//this.mediaObj.play();
					//this.modalObj.modal.removeClass('media-paused');
					//this.listItem.addClass('media-playing');
				}
			} else {
				if (this.msgMediaObj && this.playingAmsg) {
					if (!this.msgMediaObj.media.paused) {
						this.msgMediaObj.media.pause();
						this.modalObj.modal.addClass('media-paused');
						this.listItem.removeClass('media-playing');
					} else {
						this.msgMediaObj.media.play();
						this.modalObj.modal.removeClass('media-paused');
						this.listItem.addClass('media-playing');
					}
				} else if (!this.mediaObj.media.paused) {
					this.mediaObj.media.pause();
					this.modalObj.modal.addClass('media-paused');
					this.listItem.removeClass('media-playing');
				} else {
					this.mediaObj.media.play();
					this.modalObj.modal.removeClass('media-paused');
					this.listItem.addClass('media-playing');
				}
			}
			return;
		}

		// Wait for data preload, if not already loaded.
		if (false === props[this.id].fetched) {
			if (false === this.timeOut) {
				setTimeout(() => {_this.playModal()}, 200);
			}
			return;
		}

		// Perform common actions before playing podcast.
		this.common();

		if (!this.singleWrap.hasClass('activePodcast')) {
			if (this.modalObj.modal.hasClass('pp-modal-open')) {
				this.modalObj.returnElem();
			}
			this.modalObj.create(this.singleWrap, this.mediaObj, this.msgMediaObj, isModalView);
			this.singleWrap.addClass('activePodcast');
		} else {
			if (isModalView) {
				const wrapper = this.modalObj.modal.find('.episode-primary__title');
				let customTitle = wrapper.find('.episode-single__title');
				customTitle.html(this.episode.find( '.episode-single__title' ).html());
				this.modalObj.modal.removeClass('inline-view').addClass('modal-view');
				this.modalObj.scrollDisable();
			}
		}

		// Auto play the media.
		if (! isModalView) {
			if (!this.playingAmsg) this.mediaObj.media.play();
			this.modalObj.modal.removeClass('media-paused');
		} else {
			this.listItem.removeClass('media-playing');
		}
		this.btnImage();
	}

	/**
	 * Show sticky player on page load.
	 * 
	 * @since 4.0.0
	 */
	showStickyPlayer() {
		const _this = this;
		if (! this.modalObj) return;

		// Wait for data preload, if not already loaded.
		if (false === props[this.id].fetched) {
			if (false === this.timeOut) {
				setTimeout(() => {_this.showStickyPlayer()}, 200);
			}
			return;
		}

		const cookie = props.readCookie('ppCookie') || {};
		this.playTime = cookie.time || false;

		// Perform common actions before playing podcast.
		this.common();

		if (!this.singleWrap.hasClass('activePodcast')) {
			if (this.modalObj.modal.hasClass('pp-modal-open')) {
				this.modalObj.returnElem();
			}
			this.modalObj.create(this.singleWrap, this.mediaObj, this.msgMediaObj, false);
			this.singleWrap.addClass('activePodcast');
		}
	}

	/**
	 * Play appropriate media.
	 * 
	 * @since 2.5.0
	 */
	playMessage() {
		const pid  = `pp-podcast-${this.instance}`;
		const rdata = this.data[pid] ? this.data[pid].rdata : false;

		// Set episode src, if custom audio message is not set.
		if (!rdata || 'undefined' === typeof(rdata.audiomsg)) {
			return;
		}

		if (rdata.playfreq <= this.msgFreqCounter || false === this.played) {
			this.msgFreqCounter = 0;
			if ('start' === rdata.msgstart) {
				this.playingAmsg = true;
				this.player.addClass('msg-playing');
				if (this.msgMediaObj) this.msgMediaObj.media.play();
			} else if ('end' === rdata.msgstart) {
				this.playAmsg = true;
			} else if ('custom' === rdata.msgstart) {
				const time = rdata.msgtime[0] * 60 * 60 + rdata.msgtime[1] * 60 + rdata.msgtime[2];
				this.deferredPlay(time);
			}
		} else {
			this.msgFreqCounter++;
			this.playingAmsg = false;
		}
	}

	/**
	 * Deferred play media message.
	 * 
	 * @since 2.5.0
	 * 
	 * @param int time
	 */
	deferredPlay(time) {
		if (time) {
			const currentTime = this.mediaObj.media.currentTime;
			if (currentTime && currentTime >= time) {
				this.playingAmsg = true;
				this.mediaObj.media.pause();
				if (this.msgMediaObj) this.msgMediaObj.media.play();
				this.player.addClass('msg-playing');
			} else {
				setTimeout(() => { this.deferredPlay(time) }, 1000);
			}
		}
	}

	/**
	 * Actions when current media has ended.
	 * 
	 * @since 2.5.0
	 */
	mediaEnded() {

		if (true === this.playAmsg) {
			this.playingAmsg = false;
			this.player.addClass('msg-playing');
			if (this.msgMediaObj) this.msgMediaObj.media.play();
		} else {
			if (this.listItem && !this.listItem.hasClass('episode-list__search-entry')) {
				this.playNextEpisode();
			}
		}
	}

	/**
	 * Actions when current media has ended.
	 * 
	 * @since 2.5.0
	 */
	msgMediaEnded() {

		if (true === this.playingAmsg) {
			this.player.removeClass('msg-playing');
			this.playingAmsg = false;
			this.mediaObj.media.play();
		} else if (true === this.playAmsg) {
			if (this.listItem && !this.listItem.hasClass('episode-list__search-entry')) {
				this.playNextEpisode();
			}
		}
	}

	/**
	 * Copy episode link to clipboard.
	 * 
	 * @since 3.7.0
	 */
	copyLink() {

		if ( ! this.copyField.length ) return;
		this.copyField.show();
		this.copyField[0].select();
		this.copyField[0].setSelectionRange(0, 99999);
		document.execCommand("copy");
		this.copylink.addClass('pp-link-copied');
	}

	playAnalytics() {
		if (! this.audioFirstPlay) return;
		this.audioFirstPlay = false;
		if (! this.listItem || ! this.data) return;
		// Delay time should be at least 10 seconds or audio duration (if less than 10 seconds).
		// Analytics will be recorded only if the audio play for at least 10 seconds.
		let delayTime = this.settings.stat_threshold;
		if ( ! delayTime && 0 !== delayTime ) {
			delayTime = 10;
		}
		clearTimeout(this.statTimeOut);
		this.statTimeOut = setTimeout(() => {
			if (! this.media.paused) {
				this.recordAnalytics();
			}
		}, delayTime * 1000);
	}

	recordAnalytics() {
		const pid = `pp-podcast-${this.instance}`;
		const rdata = this.data[pid] ? this.data[pid].rdata : false;
		const id = this.listItem.attr('id') || this.listItem.attr('data-pid');
		const ajax = this.data.ajax_info;
		let details = {};
		// Update podcast data on single podcast wrapper.
		if ( this.listItem.hasClass( 'episode-list__search-entry' ) ) {
			details = this.data.search[id];
		} else {
			details = this.data[pid][id];
		}

		const podcast = rdata.fprint || rdata.podcast || false;
		const episode = details.key || rdata.episode || false;

		if ( ! podcast || ! episode ) {
			console.log( 'Analytics could not be recorded.' );
			return;
		}

		const data = {
			action : 'pp_podcast_statistics',
			type: 'play',
			security: ajax.security,
			podcast,
			episode,
		};

		// Let's get next set of episodes.
		jQuery.ajax( {
			url: ajax.ajaxurl,
			data: data,
			type: 'POST',
			timeout: 4000,
			success: response => {
				const details = JSON.parse( response );

				if ( details.success ) {
					console.log( 'Analytics recorded successfully.' );
				}
			},
			error: (jqXHR, textStatus, errorThrown) => {
				console.log( errorThrown );
			}
		} );
	}
}

export default PlayEpisode;
