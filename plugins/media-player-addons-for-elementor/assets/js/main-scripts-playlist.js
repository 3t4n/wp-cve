( function( $ ) {
	/**
 	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */ 
	// Make sure you run this code under Elementor.

	
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bplayer-playlist.default', function( scope, $){
			let plyerId = $(scope).find('#app');
			let plyer_opt = $(plyerId).data('settings');

			
			console.log(plyer_opt);
			const songs = plyer_opt.media_source;
			//console.log('songs', songs)

			const playlist = songs.map(item => {
				const audio = {
					src: item.track_source.url,
					poster: item.track_poster.url,
					name: item.track_title,
					artist:item.track_artist_name,
					//lyric: item.track_lyrics,
					album: item.track_album,
					type: 'audio'
				}
				return audio;
			});
			let player = new cplayer({
				element: plyerId[0],
				// element: document.getElementById('app'),
				playlist,
				dark: plyer_opt.dark_mode,
				big: plyer_opt.bplayer_size,
				zoomOutKana: true,
				shadowDom: true,
			})
        } );
	} );

	// Video Playlist
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bplayer-vdo-playlist.default', function( scope, $){
			let plyerId = $(scope).find('#app');
			let plyer_opt = $(plyerId).data('settings');

			const songs = plyer_opt.media_source;
			//console.log('songs', songs)

			const playlist = songs.map(item => {
				const video = {
					src: item.track_source.url,
					poster: item.track_poster.url,
					name: item.track_title,
					artist:item.track_artist_name,
					//lyric: item.track_lyrics,
					album: item.track_album,
					type: 'video'
				}
				return video;
			});
			let player = new cplayer({
				element: plyerId[0],
				// element: document.getElementById('app'),
				playlist,
				dark: plyer_opt.dark_mode,
				big: true,
				zoomOutKana: true,
				shadowDom: true,
			})
        } );
	} );
} )( jQuery );


