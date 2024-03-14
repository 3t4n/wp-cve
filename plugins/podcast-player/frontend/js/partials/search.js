import props from './variables';

class SearchEpisodes {

	/**
	 * Check if Search results page is open.
	 */
	isSrchOpen = false;
	
	/**
	 * Local search time-out.
	 */
	localTimeOut = null;

	/**
	 * Live Search time-out.
	 */
	serverTimeOut = null;

	/**
	 * Check if spinning wheel is visible.
	 */
	hasLoading = false;

	/**
	 * Save previously entered search term.
	 */
	prevSearchTerm = '';

	/**
	 * Manage podcast tabs elements.
	 * 
	 * @since 1.3
	 * 
	 * @param {string} id Podcast player ID.
	 */
	constructor(id) {

		this.podcast = props[id].podcast;
		this.instance = props[id].instance;
		this.isStyleSupport = props.isStyleSupport;
		this.data = props.podcastPlayerData;
		this.list = props[id].list;
		this.episodes = props[id].episodes;
		this.settings = props[id].settings;
		this.loadbtn = this.podcast.find('.episode-list__load-more');
		this.prevBtn = this.podcast.find('.pp-prev-btn');
		this.nextBtn = this.podcast.find('.pp-next-btn');
		this.term = false;
		this.searchBox = this.list.find('.episode-list__search input');
		this.searchResults = this.list.find('.episode-search');
		this.searchClose = this.list.find('.episode-list__clear-search');
		this.filterDropdown = this.list.find('.episode-list__filter-select');

		this.events();
	}

	/**
	 * PodcastTabs event handling.
	 * 
	 * @since 1.3
	 */
	events() {

		this.searchBox.on('keyup paste', this.initSearch.bind(this));
		this.searchClose.on('click', this.clearSearch.bind(this));
		this.filterDropdown.on('change', this.initFilter.bind(this));
	}

	/**
	 * Create markup for additional set of episodes (if any).
	 * 
	 * @since 1.3
	 */
	initSearch() {

		let searchTerm = this.searchBox.val();
		if ( this.term === searchTerm ) {
			return;
		}

		this.term = searchTerm;

		// Open search results box. if not already opened.
		if ( false === this.isSrchOpen ) {
			this.podcast.addClass('search-opened');
			this.searchResults.addClass('toggled-on');
			this.loadbtn.hide();
			this.searchClose.show();
			this.isSrchOpen = true;
		}

		// Filter already loaded episodes.
		clearTimeout(this.localTimeOut);
		this.localTimeOut = setTimeout(function() {
			searchTerm = this.searchBox.val().trim().toLowerCase();
			if (searchTerm) {
				this.filterItems();
			}
		}.bind(this), 100);

		// Get live search reults from the server.
		clearTimeout(this.serverTimeOut);
		this.serverTimeOut = setTimeout(function() {
			if (this.prevSearchTerm === searchTerm) {
				return;
			} else {
				this.liveSearch();
			}
		}.bind(this), 500);
	}

	initFilter() {
		this.filterItems();
		this.liveSearch();
	}

	/**
	 * Filter Episodes by category dropdown.
	 *
	 * @since 6.6.0
	 */
	filterItems() {
		const filter = this.filterDropdown.val() || '';
		const searchTerm = this.searchBox.val().trim().toLowerCase();
		const isSearchTerm = (Boolean)(searchTerm && searchTerm.length > 0);
		const episodeList = this.episodes.find('.episode-list__entry');
		episodeList.each(function() {
			const $this = jQuery( this );
			const data = $this.data( 'cats' );
			if ( data.includes( filter ) || filter.length < 1 ) {
				if ( isSearchTerm ) {
					if ( $this.data( 'search-term' ).includes( searchTerm ) ) {
						$this.show();
					} else {
						$this.hide();
					}
				} else {
					$this.show();
				}
			} else {
				$this.hide();
			}
		});
	}

	/**
	 * Filter already displayed episode by search Term
	 * 
	 * @since 1.3
	 * 
	 * @param {string} searchTerm Current Search Term.
	 */
	filterEpisodes(searchTerm) {
		let episodeList = this.episodes.find('.episode-list__entry');
		episodeList.each(function() {
			const data = jQuery( this ).data( 'search-term' );
			if ( data.includes( searchTerm ) || searchTerm.length < 1 ) {
				jQuery( this ).show();
			} else {
				jQuery( this ).hide();
			}
		});
	}

	/**
	 * Fetch search filtered episodes from the server.
	 * 
	 * @since 1.3
	 */
	liveSearch() {
		// Open search results box. if not already opened.
		if ( false === this.isSrchOpen ) {
			this.podcast.addClass('search-opened');
			this.searchResults.addClass('toggled-on');
			this.loadbtn.hide();
			this.searchClose.show();
			this.isSrchOpen = true;
		}

		const filter = this.filterDropdown.val() || '';
		const searchTerm = this.searchBox.val().trim().toLowerCase();

		this.flushSearchResults();
		if ( searchTerm.length < 1 && filter.length < 1 ) {
			this.clearSearch();
			return;
		}

		// Display spinning wheel while we wait for results.
		if (false === this.hasLoading) {

			const wheelMarkup = jQuery('<div />', { class: 'episode-search__loading'})
				.html(this.settings.ppVidLoading);
			
			this.searchResults.html(wheelMarkup);
			this.hasLoading = true;
		}

		// Run live Ajax search and show results.
		this.fetchResults();
	}

	/**
	 * Fetch and display search results.
	 * 
	 * @since 1.3
	 */
	fetchResults() {
		const pid  = `pp-podcast-${this.instance}`;
		const load = this.data[pid].rdata;
		if ( 'feedurl' === load.from ) {
			this.fetchFromFeed();
		} else if ( 'posts' === load.from ) {
			this.fetchFromPosts();
		}
	}

	/**
	 * Search and Fetch results from the RSS feed.
	 * 
	 * @since 2.0
	 */
	fetchFromFeed() {
		const catfilter = this.filterDropdown.val() || '';
		const searchTerm = this.searchBox.val().trim().toLowerCase();
		const pid = `pp-podcast-${this.instance}`;
		let load = this.data[pid].load_info;
		let displayed = load.displayed;
		let ajax = this.data.ajax_info;
		const args = jQuery.extend( {}, load.args );
		if (catfilter && catfilter.length > 0) {
			args.catfilter = catfilter;
			displayed = this.episodes.find('.episode-list__entry[data-cats~="' + catfilter + '"]').length;
		}
		let data = {
			action   : 'pp_search_episodes',
			security : ajax.security,
			instance : 's', // Deprecated, keeping for compatibility.
			loaded   : load.displayed, // Deprecated, keeping for compatibility.
			displayed: displayed,
			maxItems : load.maxItems,
			feedUrl  : load.src,
			sortby   : load.sortby,
			filterby : load.filterby,
			offset   : load.offset,
			search   : searchTerm,
			args     : args
		};

		// Let's get next set of episodes.
		jQuery.ajax( {
			url: ajax.ajaxurl,
			data: data,
			type: 'POST',
			timeout: 4000,
			success: response => {
				let details = JSON.parse( response );
				if ( jQuery.isEmptyObject( details ) ) {
					this.flushSearchResults();
					this.searchAnalytics( load.src, searchTerm, [] );
				} else {
					this.data.search = details.episodes;
					this.showSearchResults(details.episodes);
					this.searchAnalytics( load.src, searchTerm, details.episodes );
				}
			},
			error: (jqXHR, textStatus, errorThrown) => {
				this.flushSearchResults();
				console.log( errorThrown );
			}
		} );
	}

	/**
	 * Search and Fetch results from posts.
	 * 
	 * @since 2.0
	 */
	fetchFromPosts() {
		const catfilter = this.filterDropdown.val() || '';
		const searchTerm = this.searchBox.val().trim().toLowerCase();
		const pid = `pp-podcast-${this.instance}`;
		let load = this.data[pid].load_info;
		let ajax = this.data.ajax_info;
		const args = jQuery.extend( {}, load.args );
		if (catfilter && catfilter.length > 0) {
			args.catfilter = catfilter;
		}
		let data = {
				action    : 'pp_search_posts',
				security  : ajax.security,
				offset    : load.displayed, // Deprecated, keeping for compatibility.
				displayed : load.displayed,
				args      : args,
				instance  : 's', // Deprecated, keeping for compatibility.
				search    : searchTerm,
				ids       : load.ids,
		};

		// Let's get next set of episodes.
		jQuery.ajax( {
			url: ajax.ajaxurl,
			data: data,
			type: 'POST',
			timeout: 4000,
			success: response => {
				let details = JSON.parse( response );
				if ( jQuery.isEmptyObject( details ) ) {
					this.flushSearchResults();
				} else {
					this.data.search = details.episodes;
					this.showSearchResults(details.episodes);
				}
			},
			error: () => {
				this.flushSearchResults();
			}
		} );
	}

	/**
	 * Clear search results.
	 * 
	 * @since 1.3
	 */
	clearSearch() {

		const pid = `pp-podcast-${this.instance}`;
		let load = this.data[pid].load_info;

		this.searchBox.val('');
		this.filterDropdown.val('');
		this.prevSearchTerm = '';
		this.isSrchOpen = false;
		this.term = false;

		this.podcast.removeClass('search-opened');
		this.searchResults.removeClass('toggled-on');
		this.searchClose.hide();
		this.episodes.find('.episode-list__entry').show();
		this.flushSearchResults();

		// If not all loaded episodes have been displayed.
		if ( load.displayed < load.loaded ) {
			this.loadbtn.show();
			this.nextBtn.attr('disabled', false);
		}
	}

	/**
	 * Flush search results.
	 * 
	 * @since 1.3
	 */
	flushSearchResults() {

		this.searchResults.empty();
		this.hasLoading = false;
	}

	/**
	 * Show search results in the player.
	 * 
	 * @since 1.3
	 * 
	 * @param {string} results Fetched search results.
	 */
	showSearchResults(results) {
		const pid = `pp-podcast-${this.instance}`;
		const from = this.data[pid].rdata.from;
		const details = this.data[pid].load_info;
		const arr = Object.getOwnPropertyNames( results );
		const nextList = arr.length - 1;
		const teaser = this.data[pid].rdata.teaser;
		const excerptLength = this.data[pid].rdata.elen;
		const excerptUnit = this.data[pid].rdata.eunit;
		let i=0;
		let overallMarkup = jQuery( '<div />' );
		let epititle = 'fetch-feed-title';
		if ( 'posts' === from ) {
			epititle = 'fetch-post-title';
		}

		for( ; i <= nextList; i++  ) {

			let id = arr[i];
			let episode = results[id];
			let {title, description, author, date, link, featured} = episode;

			if ( !featured ) {
				featured = details.args.imgurl;
				featured = featured ? featured : '';
			}

			let linkMarkup = jQuery('<a />', { href: link, class: epititle }).html( title );
			let excerptLink = jQuery('<a />', { href: link, class: epititle }).html( '[...]' );
			let titleMarkup = jQuery('<div />', { class: 'pod-entry__title' }).html( linkMarkup );
			let dateMarkup = jQuery('<div />', { class: 'pod-entry__date' }).text( date );
			let authorMarkup = jQuery('<div />', { class: 'pod-entry__author' }).html( author );
			let markup;

			if (this.podcast.hasClass('postview')) {
				const style = details.args.display;
				const fullText = description ? jQuery(description).text() : '';
				const pplay = jQuery('<div />', { class: 'pod-entry__play' }).html( this.settings.ppPlayCircle + this.settings.ppPauseBtn );
				let imgMarkup, eHtml = '';
				if (style && this.isStyleSupport(style, 'playbtn')) {
					imgMarkup = '';
				} else {
					imgMarkup = featured ? jQuery('<img />', { class: 'pod-entry__image', src: featured, alt: title }) : '';
					imgMarkup = imgMarkup ? `<div class="pod-entry__thumb">${imgMarkup[0].outerHTML}</div>` : '';
				}
				if (style && this.isStyleSupport(style, 'excerpt')) {
					let excerpt;
					if ( 'none' === teaser ) {
						eHtml = '';
					} else if ( 'full' === teaser ) {
						const eMarkup = description ? jQuery('<div />', { class: 'pod-entry__excerpt' }).html( jQuery(description).html() ) : '';
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
				<div id="${id}" class="episode-list__search-entry pod-entry" data-search-term="${title.toLowerCase()}">
					<div class="pod-entry__wrapper">
						<div class="pod-entry__featured">
							${pplay[0].outerHTML}
							${imgMarkup}
						</div>
						<div class="pod-entry__content">
							${titleMarkup[0].outerHTML}${eHtml}${dateMarkup[0].outerHTML}${authorMarkup[0].outerHTML}
						</div>
					</div>
				</div>
				`;
			} else if (this.podcast.hasClass('modern')) {
				markup = this.episodes.find('.episode-list__entry').first().clone();
				markup.removeClass('activeEpisode media-playing episode-list__entry');
				markup.addClass('episode-list__search-entry');
				markup.attr( 'id', id );
				markup.attr( 'data-search-term', title.toLowerCase() );
				markup.find('.pod-entry__title').replaceWith( titleMarkup );
				markup.find('.pp-entry__mpost').attr( 'href', link );
				markup.css('display', 'block');
			} else {
				markup = `
				<div id="${id}" class="episode-list__search-entry pod-entry" data-search-term="${title.toLowerCase()}">
					<div class="pod-entry__content">
						${titleMarkup[0].outerHTML}${dateMarkup[0].outerHTML}${authorMarkup[0].outerHTML}
					</div>
				</div>
				`;
			}
			overallMarkup.append(jQuery(markup));
			this.searchResults.html( overallMarkup.html() );
			this.hasLoading = false;
		}
	}

	searchAnalytics( feed, term, results ) {
		if (! this.settings.isPremium || ! this.settings.analytics) {
			return;
		}
		const ajax = this.data.ajax_info;
		const episodeList = this.episodes.find('.episode-list__entry');
		// Count the visible items in episodeList
		const visibleItemCount = episodeList.filter(':not(:hidden)').length;
		let totalResults = Object.keys( results ).length || 0;
		totalResults = totalResults + visibleItemCount;
		const data = {
			action : 'pp_podcast_statistics',
			type: 'search',
			security: ajax.security,
			podcast: feed,
			term: term,
			count: totalResults,
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
			error: () => {
				console.log( 'Analytics recording failed.' );
			}
		} );
	}
}

export default SearchEpisodes;
