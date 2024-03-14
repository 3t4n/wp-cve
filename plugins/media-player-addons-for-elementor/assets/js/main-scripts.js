( function( $ ) {
	/**
 	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */ 
	// Make sure you run this code under Elementor.

	
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bplayer.default', function( scope, $){
			let plyerId = $(scope).find('#app');
			let plyer_opt = $(plyerId).data('settings');

			let player = new cplayer({
				element: plyerId[0],
				// element: document.getElementById('app'),
				playlist: [
					{
						src: plyer_opt.track_source.url,
						poster: plyer_opt.track_poster.url,
						name: plyer_opt.track_title,
						artist:plyer_opt.track_artist_name,
						album: plyer_opt.track_album,
						type: 'audio'

					}
				],
				dark: plyer_opt.dark_mode,
				big: plyer_opt.bplayer_size,
				zoomOutKana: true,
				shadowDom: true,
				showPlaylistButton: false,
				showPlaylist: false,
				dropDownMenuMode: 'none',
			})
        } );
	} );


	// Video Player
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bplayer-video.default', function( scope, $){
			let plyerId = $(scope).find('#app');
			let plyer_opt = $(plyerId).data('settings');

			console.log(plyer_opt.play_mode);
			let player = new cplayer({
				element: plyerId[0],
				// element: document.getElementById('app'),
				playlist: [
					{
						src: plyer_opt.track_source.url,
						poster: plyer_opt.track_poster.url,
						name: plyer_opt.track_title,
						artist:plyer_opt.track_artist_name,
						album: plyer_opt.track_album,
						type: 'video'

					}
				],
				dark: plyer_opt.dark_mode,
				big: true,
				zoomOutKana: false,
				shadowDom: false,
				showPlaylistButton: false,
				showPlaylist: false,
				dropDownMenuMode: 'none',
			})
        } );
	} );
} )( jQuery );


