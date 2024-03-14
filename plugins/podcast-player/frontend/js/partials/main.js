import props from './variables';
import Podcast from './podcast';
import Modal from './modal';
import MediaElem from './mediaelem';

( $ => {

	'use strict';

	const podcasts = $( '.pp-podcast' );
	const spodcast = $( '.pp-social-shared' ).first();
	const settings = window.ppmejsSettings || {};
	const modal = settings.isPremium ? new Modal() : '';
	const isMejs = settings.isMeJs;
	let timeOut = false;

	setTimeout(() => {timeOut = true}, 3000);

	podcasts.each( function() {
		const podcast = $(this);
		createPodcast(podcast);
	} );

	document.addEventListener('animationstart', playerAdded, false); // Standard + firefox
	document.addEventListener('MSAnimationStart', playerAdded, false); // IE
	document.addEventListener('webkitAnimationStart', playerAdded, false); // Chrome + Safari

	function playerAdded(e) {
		if ('playerAdded' !== e.animationName) {
			return;
		}
		const podcast = $(e.target);

		if (!podcast.hasClass('pp-podcast')) {
			return;
		}

		if (podcast.hasClass('pp-podcast-added')) {
			return;
		}

		createPodcast(podcast);
	}

	function createPodcast(podcast) {

		// Return if podcast is inside another podcast player's description.
		const hasParentPodcast = podcast.parents('.pp-podcast');
		if (hasParentPodcast.length) {
			return;
		}

		// Return if podcast is already created.
		if (podcast.hasClass('pp-podcast-added')) {
			return;
		}

		// Remvoe any podcast markup inside current podcast.
		podcast.find('.pp-podcast').remove();

		// Wait for mediaElement js, if not already loaded.
		if (isMejs && 'undefined' === typeof(MediaElementPlayer)) {
			if (false === timeOut) {
				setTimeout(() => {createPodcast(podcast)}, 200);
			}
			return;
		}
		const id = podcast.attr('id');
		let mediaObj = false;
		if (isMejs) {
			mediaObj = new MediaElementPlayer( id + '-player', settings );
		} else {
			mediaObj = new MediaElem( id + '-player' );
		}
		if(!mediaObj) return;
		const list = podcast.find('.pod-content__list');
		const episode = podcast.find('.pod-content__episode');
		const episodes = list.find('.episode-list__wrapper');
		const single = episode.find('.episode-single__wrapper');
		const singleWrap = podcast.find('.pp-podcast__single').first();
		const player = podcast.find('.pp-podcast__player');
		const amsg = podcast.find('.pp-player__amsg');
		const fetched = false;
		let msgMediaObj = false;
		if (amsg.length) {
			if (isMejs) {
				msgMediaObj = new MediaElementPlayer( id + '-amsg-player', settings );
			} else {
				msgMediaObj = new MediaElem( id + '-amsg-player' );
			}
		}

		// If podcast player data is not available.
		if ( 'undefined' === typeof props.podcastPlayerData[id] ) {

			// Check if data is available as data attribute (for ajax requests).
			const pdata = podcast.data("ppsdata");
			if ( pdata ) {

				// Add podcast data.
				props.podcastPlayerData[id] = pdata;
			} else {
				// Return if podcast player data is not available.
				return;
			}
		}

		props[id] = {
			podcast, mediaObj, settings, list, episode, msgMediaObj,
			amsg, episodes, single, player, modal, singleWrap, fetched,
			instance: id.replace( 'pp-podcast-', '' ),
		};
		podcast.addClass('pp-podcast-added');
		new Podcast(id);
	}

	if ( spodcast.length ) $( 'html, body' ).animate({ scrollTop: spodcast.offset().top - 200 }, 400 );
	if ( settings.isPremium && settings.isSticky ) $(window).on('scroll', props.stickyonScroll.bind(props));
})(jQuery);
