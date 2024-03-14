import props from './variables';

class LoadmoreEpisodes {

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
		this.settings = props[id].settings;
		this.instance = props[id].instance;
		this.episodes = props[id].episodes;
		this.mediaObj = props[id].mediaObj;
		this.list = props[id].list;
		this.modalObj = props[id].modal;
		this.modal = this.modalObj ? this.modalObj.modal : false;
		this.fetched = props[id].fetched;
		this.isStyleSupport = props.isStyleSupport;
		this.data = props.podcastPlayerData;
		this.loadbtn = this.podcast.find('.episode-list__load-more');
		this.auxList = this.modal ? this.modal.find('.pp-modal-tabs-list') : false;

		this.events();
	}

	/**
	 * PodcastTabs event handling.
	 * 
	 * @since 1.3
	 */
	events() {

		const _this = this;
		this.preLoadEpisodes();
		this.loadbtn.on('click', function() {
			_this.loadEpisodes(false);
		});

		// Play Modal playlist.
		if (this.auxList && this.auxList.length) {
			this.auxList.on('click', '.episode-list__load-more', function() {
				const minstance = _this.modal.find('.modal-' + _this.instance);
				if (minstance.length) {
					_this.loadEpisodes(true);
				}
			});
		}
	}

	/**
	 * Preload initial set of episodes.
	 * 
	 * @since 1.4.4
	 */
	preLoadEpisodes() {

		const pid = `pp-podcast-${this.instance}`;
		const from = this.data[pid] ? this.data[pid].rdata.from : false;
		const load = this.data[pid] ? this.data[pid].load_info : false;
		const loaded = load ? load.loaded : false;
		if (from && 'link' !== from) {
			
			// Pre fetch items only if they are already not loaded.
			if (0 != loaded) {
				props[this.id].fetched = true;
			} else {
				this.fetchEpisodes();
			}
		} else if (from) {
			this.fetchMediaURL();
		}
	}

	/**
	 * Create markup for additional set of episodes (if any).
	 * 
	 * @since 1.3
	 *
	 * @param {bool} fromModal
	 */
	loadEpisodes(fromModal = false) {
		const pid = `pp-podcast-${this.instance}`;
		if (! this.data[pid]) return;
		const fid = this.episodes.find('.episode-list__entry').first();
		const from = this.data[pid].rdata.from;
		let details = this.data[pid].load_info;
		const teaser = this.data[pid].rdata.teaser;
		const excerptLength = this.data[pid].rdata.elen;
		const excerptUnit = this.data[pid].rdata.eunit;
		let overallMarkup = jQuery( '<div />' );
		const feat = fid.find('.pod-entry__image');
		const sizes = feat.length ? feat.attr('sizes') : '';
		let firstElem = false, displayed;

		if (fromModal) {
			displayed = this.auxList.find('.pod-entry').length;
		} else {
			displayed = this.list.find('.pod-entry').length;
		}

		const nextList = Math.min(displayed + details.step, details.loaded);
		let i = displayed + 1;
		let epititle = 'fetch-feed-title';
		if ( 'posts' === from ) {
			epititle = 'fetch-post-title';
		}

		for( ; i <= nextList; i++  ) {

			let id = `ppe-${this.instance}-${i}`;
			let epid = fromModal ? 'data-pid="' + id + '"' : 'id="' + id + '"';
			let episode = this.data[pid][id];
			firstElem = firstElem ? firstElem : id;

			if ( 'undefined' !== typeof(episode) ) {
				let {title, description, author, date, link, featured, fset, categories} = episode;
				const cats = categories ? Object.keys(categories).join(' ') : '';
				let linkMarkup = jQuery('<a />', { href: link, class: epititle }).html( title );
				let excerptLink = jQuery('<a />', { href: link, class: epititle }).html( '[...]' );
				let titleMarkup = jQuery('<div />', { class: 'pod-entry__title' }).html( linkMarkup );
				let dateMarkup = jQuery('<div />', { class: 'pod-entry__date' }).text( date );
				let authorMarkup = jQuery('<div />', { class: 'pod-entry__author' }).html( author );
				let fMarkup = '';
				let markup;

				if (this.podcast.hasClass('postview')) {
					const style = details.args.display;
					const hideFeatured = 0 != details.args.hdfeat;
					const fullText = description ? jQuery(description).text() : '';
					const pplay = jQuery('<div />', { class: 'pod-entry__play' }).html( this.settings.ppPlayCircle + this.settings.ppPauseBtn );
					let imgMarkup, eHtml = '';
					if (style && this.isStyleSupport(style, 'playbtn')) {
						imgMarkup = '';
					} else {
						imgMarkup = featured ? jQuery('<img />', { class: 'pod-entry__image', src: featured, srcset: fset, sizes: sizes, alt: title }) : '';
						imgMarkup = imgMarkup ? `<div class="pod-entry__thumb">${imgMarkup[0].outerHTML}</div>` : '';
					}
					if (!hideFeatured || this.isStyleSupport(style, 'playbtn')) {
						fMarkup = `<div class="pod-entry__featured">${pplay[0].outerHTML}${imgMarkup}</div>`;
					}
					if (style && this.isStyleSupport(style, 'excerpt')) {
						let excerpt;
						if ( 'none' === teaser ) {
							eHtml = '';
						} else if ( 'full' === teaser ) {
							const eMarkup = description ? jQuery('<div />', { class: 'pod-entry__excerpt' }).html( description ) : '';
							eHtml = eMarkup ? eMarkup[0].outerHTML : '';
						} else {
							if ( excerptUnit ) {
								excerpt = fullText ? fullText.substr(0, excerptLength) : '';
							} else {
								excerpt = fullText ? fullText.split(/\s+/).splice(0,excerptLength).join(' ') : '';
							}
							const eMarkup = excerpt ? jQuery('<div />', { class: 'pod-entry__excerpt' }).html( excerpt + excerptLink[0].outerHTML ) : '';
							eHtml = eMarkup ? eMarkup[0].outerHTML : '';
						}
					}
					markup = `
					<div ${epid} class="episode-list__entry pod-entry" data-search-term="${title.toLowerCase()}" data-cats="${cats}">
						<div class="pod-entry__wrapper">
							${fMarkup}
							<div class="pod-entry__content">
								${titleMarkup[0].outerHTML}${eHtml}${dateMarkup[0].outerHTML}${authorMarkup[0].outerHTML}
							</div>
						</div>
					</div>
					`;
				} else if (this.podcast.hasClass('modern')) {
					markup = this.episodes.find('.episode-list__entry').first().clone();
					markup.removeClass('activeEpisode media-playing');
					markup.attr( 'id', id );
					markup.attr( 'data-search-term', title.toLowerCase() );
					markup.attr( 'data-cats', cats )
					markup.find('.pod-entry__title').replaceWith( titleMarkup );
					markup.find('.pp-entry__mpost').attr( 'href', link );
				} else {
					markup = `
					<div id="${id}" class="episode-list__entry pod-entry" data-search-term="${title.toLowerCase()}" data-cats="${cats}">
						<div class="pod-entry__content">
							${titleMarkup[0].outerHTML}${dateMarkup[0].outerHTML}${authorMarkup[0].outerHTML}
						</div>
					</div>
					`;
				}
				overallMarkup.append(jQuery(markup));
			}
		}

		if (!fromModal) {
			this.loadbtn.parent().before(overallMarkup.html());
			if (details.maxItems && nextList >= details.maxItems) this.loadbtn.hide();
			if (details.ids && nextList >= details.ids.length) this.loadbtn.hide();
		} else {
			const lbtn = this.auxList.find('.episode-list__load-more');
			lbtn.parent().before(overallMarkup.html());
			if (details.maxItems && nextList >= details.maxItems) lbtn.hide();
			if (details.ids && nextList >= details.ids.length) lbtn.hide();
		}

		// Better keyboard navigation.
		// Keyboard focus to first element of newly loaded set of episodes.
		if (firstElem) {
			const fEle = this.podcast.find('#' + firstElem);
			if (fEle.length) {
				fEle.find('a').first().focus();
				if (this.podcast.hasClass('postview')) {
					jQuery( 'html, body' ).animate({ scrollTop: fEle.offset().top - 150 }, 400 );
				}
			}
		}
		

		// Update number of post displayed in the podcast player.
		if (!fromModal) details.displayed = nextList;

		// Fetch more episodes using Ajax.
		if (details.loaded - nextList <= details.step) {
			this.fetchEpisodes();
		}
	}

	/**
	 * Fetch more episodes from the server using Ajax.
	 * 
	 * @since 1.3
	 */
	fetchEpisodes() {
		const pid  = `pp-podcast-${this.instance}`;
		const load = this.data[pid].rdata;
		if ( 'feedurl' === load.from ) {
			this.fetchFromFeed();
		} else if ( 'posts' === load.from ) {
			this.fetchFromPosts();
		}
	}

	/**
	 * Fetch URL for single episode player from audio/video link.
	 * 
	 * @since 1.4.4
	 */
	fetchMediaURL() {
		const pid = `pp-podcast-${this.instance}`;
		const id = `ppe-${this.instance}-1`;
		const data = this.data[pid][id];
		const ajax = this.data.ajax_info;
		const src = data.src;
		
		// Return if src already seems to be a valid URL.
		if (0 == src.indexOf("http://") || 0 == src.indexOf("https://")) {
			props[this.id].fetched = true;
			return;
		}
		const adata = {
			action  : 'pp_fetch_media_url',
			security: ajax.security,
			src: src
		};

		// Let's get next set of episodes.
		jQuery.ajax( {
			url: ajax.ajaxurl,
			data: adata,
			type: 'POST',
			timeout: 4000,
			success: response => {
				const details = JSON.parse( response );

				if (data.src === data.link) {
					data.link = details;
				}
				data.src = details;
				props[this.id].fetched = true;

				// Update media src.
				this.mediaObj.setSrc(data.src);
			},
			error: () => {
				data.src = '#';
				data.link = '#';
			}
		} );
	}

	/**
	 * Fetch more episodes from the RSS feed.
	 * 
	 * @since 2.0
	 */
	fetchFromFeed() {
		const pid = `pp-podcast-${this.instance}`;
		let load = this.data[pid].load_info;
		let ajax = this.data.ajax_info;
		let counter = load.step;
		
		// Load twice of the required episodes for first time.
		if (! props[this.id].fetched) {
			counter = 2 * counter;
		}
		let data = {
				action  : 'pp_fetch_episodes',
				security: ajax.security,
				instance: this.instance,
				loaded  : load.loaded,
				maxItems: load.maxItems,
				feedUrl : load.src,
				step    : counter,
				sortby  : load.sortby,
				filterby: load.filterby,
				args    : load.args,
				offset  : load.offset
			};

		// If all required episodes have already been loaded.
		if ( load.loaded >= load.maxItems ) {

			// If all loaded episodes have already been displayed.
			if ( load.displayed >= load.loaded ) {
				this.loadbtn.slideUp( 'slow' );
			}

			// No need to run ajax request.
			return;
		}

		// Let's get next set of episodes.
		jQuery.ajax( {
			url: ajax.ajaxurl,
			data: data,
			type: 'POST',
			timeout: 4000,
			success: response => {
				const details = JSON.parse( response );

				// Update total number of episodes fetched.
				load.loaded = details.loaded;

				// Update episodes collection object.
				jQuery.extend( true, this.data[pid], details.episodes );

				props[this.id].fetched = true;
			},
			error: () => {
				this.loadbtn.hide();
			}
		} );
	}

	/**
	 * Fetch more episodes from the Posts.
	 * 
	 * @since 2.0
	 */
	fetchFromPosts() {
		const pid = `pp-podcast-${this.instance}`;
		let load = this.data[pid].load_info;
		let ajax = this.data.ajax_info;
		let ajaxArgs = jQuery.extend(true, {}, load.args);

		// Load twice of the required episodes for first time.
		if (! props[this.id].fetched) {
			ajaxArgs.number = 2 * ajaxArgs.number;
		}

		let data = {
				action  : 'pp_fetch_posts',
				security: ajax.security,
				instance: this.instance,
				offset  : load.offset, // Deprecated, keeping for compatibility.
				loaded  : load.loaded,
				args    : ajaxArgs,
				ids     : load.ids,
			};

		// If all required episodes have already been loaded.
		// This method has been deprecated. Keeping for compatibiltiy.
		if ( load.offset && 0 === load.offset ) {
			//this.loadbtn.slideUp( 'slow' );

			// No need to run ajax request.
			return;
		}

		// New method to know if all required episodes have been loaded.
		if ( load.ids && Array.isArray( load.ids ) ) {
			if ( load.loaded >= load.ids.length ) {
				//this.loadbtn.slideUp( 'slow' );

				// No need to run ajax request.
				return;
			}
		}

		// Let's get next set of episodes.
		jQuery.ajax( {
			url: ajax.ajaxurl,
			data: data,
			type: 'POST',
			timeout: 4000,
			success: response => {
				const details = JSON.parse( response );
				if (jQuery.isEmptyObject(details)) {
					load.offset = 0;
					this.loadbtn.slideUp( 'slow' );
				} else {
					// Update total number of episodes fetched.
					load.loaded = details.loaded;

					// Update overall items ids.
					load.ids = details.ids;

					// Update episodes collection object.
					jQuery.extend( true, this.data[pid], details.episodes );
					
					// This method has been deprecated. Keeping for compatibiltiy.
					if ( load.offset ) {
						load.offset += load.step;
					}
				}
				props[this.id].fetched = true;
			},
			error: () => {
				this.loadbtn.hide();
			}
		} );
	}
}

export default LoadmoreEpisodes;
