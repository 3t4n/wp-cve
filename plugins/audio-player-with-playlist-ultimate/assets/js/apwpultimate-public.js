jQuery(document).ready(function ($) {

	"use strict";

	/* Initilize Players View */ 
	$( '.apwp-audio-player-wrp' ).each(function( index ) {
		var player_id		= $(this).attr('data-palyer');
		var player_conf		= JSON.parse( $(this).closest('.apwp-audio-player-wrp').find('.apwpultimate-conf').text());
		var playlist		= {};
		var playlist_obj	= [];

		$( player_conf.playlist ).each(function( index, arr ) {
			playlist = {
				title	: arr.title,
				artist	: arr.artist_name,
				mp3		: arr.audio_file,
				poster	: arr.poster_image,
				duration: arr.duration,
			}
			playlist_obj.push(playlist);
		});

		new jPlayerPlaylist({
			jPlayer				: '#'+player_id,
			cssSelectorAncestor	: '.'+player_id+'-css-ance'
		},playlist_obj, {
			swfPath				: "dist/jplayer",
			supplied			: "webmv, ogv, m4v, oga, mp3",
			useStateClassSkin	: true,
			autoBlur			: true,
			smoothPlayBar		: false,
			keyEnabled			: true,
			loop				: true,
			audioFullScreen		: false
		});
	});

	/* Initilize Players Grid View */
	$( '.apwpultimate-audio-player-grid' ).each(function( index ) {
		var player_id		= $(this).attr('id');
		var player_conf		= JSON.parse( $(this).closest('.apwpultimate-audio-player-innr-wrap').find('.apwpultimate-conf').text());

		$("#"+player_id).jPlayer({
			ready: function () {
				$(this).jPlayer("setMedia", {
					title	: player_conf.title,
					artist	: player_conf.artist_name,
					mp3		: player_conf.audio_file,
					poster	: player_conf.poster_image,
					duration: player_conf.duration,
				});
			},
			play: function() { // To avoid multiple jPlayers playing together.
				$(this).jPlayer("pauseOthers");
			},
			swfPath				: "dist/jplayer",
			supplied			: "webmv, ogv, m4v, oga, mp3",
			wmode				: "window",
			cssSelectorAncestor	: "#"+player_id+"-cntrl",
			useStateClassSkin	: true,
			autoBlur			: true,
			smoothPlayBar		: false,
			keyEnabled			: true,
			loop				: true,
			audioFullScreen		: false
		});
	});

	/* Initilize Slimscroll */
	$('.playlist-block').slimScroll({
		height: '370px'
	});

	/* Toggole Settings */
	$(".toggleBlock").on('click', function () {
		$(this).toggleClass("active");
		var id = $(this).data("id");
		$("#playListOne-"+id).parent().slideToggle("slow");
	});
});
