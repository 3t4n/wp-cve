/** ----------------------------------------------------------------------------
 * Video Background */

 import {
	$,
	$window,
	$doc,
	$body
} from './utility';

var sightVideoPortfolio = {};

( function() {
	var $this;
	var YTdeferredDone = false;
	var initAPI = false;
	var process = false;
	var contex = [];
	var players = [];
	var attrs = [];

	// Create deferred object
	var YTdeferred = $.Deferred();

	if ( typeof window.onYouTubePlayerAPIReady !== 'undefined' ) {
		if ( typeof window.sightYTAPIReady === 'undefined' ) {
			window.sightYTAPIReady = [];
		}
		window.sightYTAPIReady.push( window.onYouTubePlayerAPIReady );
	}

	window.onYouTubePlayerAPIReady = function() {
		if ( typeof window.sightYTAPIReady !== 'undefined' ) {
			if ( window.sightYTAPIReady.length ) {
				window.sightYTAPIReady.pop()();
			}
		}

		// Resolve when youtube callback is called
		// passing YT as a parameter.
		YTdeferred.resolve( window.YT );
	};

	// Whenever youtube callback was called = deferred resolved
	// your custom function will be executed with YT as an argument.
	YTdeferred.done( function( YT ) {
		YTdeferredDone = true;
	} );

	// Embedding youtube iframe api.
	function embedYoutubeAPI() {
		if ( 'function' === typeof( window.onYTReady ) ) {
			return;
		}

		var tag = document.createElement( 'script' );
		tag.src = 'https://www.youtube.com/iframe_api';

		var firstScriptTag = document.getElementsByTagName( 'script' )[ 0 ];
		firstScriptTag.parentNode.insertBefore( tag, firstScriptTag );
	}

	$.fn.contexObject = function( id, type ) {
		if ( 'string' === typeof id ) {
			id = `[data-id="${id}"]`;
		} else {
			id = this;
		}

		if ( 'wrap' === type ) {
			return $( id ).closest( '.sight-portfolio-video-wrap' );
		} else if ( 'container' === type ) {
			return $( id ).closest( '.sight-portfolio-video-container' );
		} else if ( 'inner' === type ) {
			return $( id ).closest( '.sight-portfolio-video-wrap' ).find( '.sight-portfolio-video-inner' );
		} else {
			return $( id );
		}
	};

	// Object Video Portfolio.
	sightVideoPortfolio = {

		/** Initialize */
		init: function( e ) {
			if ( $( 'body' ).hasClass( 'wp-admin' ) ) {
				return;
			}

			$this = sightVideoPortfolio;

			// Init events.
			$this.events( e );
		},

		// Video rescale.
		rescaleVideoBackground: function() {
			$( '.sight-portfolio-video-init' ).each( function() {
				let w = $( this ).parent().width();
				let h = $( this ).parent().height();

				var hideControl = 400;

				let id = $( this ).attr( 'data-id' );

				if ( w / h > 16 / 9 ) {
					if ( 'youtube' === $( this ).parent().data( 'video-mode' ) ) {
						players[ id ].setSize( w, w / 16 * 9 + hideControl );
					} else {
						players[ id ].width( w );
						players[ id ].height( w / 16 * 9 + hideControl );
					}
				} else {
					if ( 'youtube' === $( this ).parent().data( 'video-mode' ) ) {
						players[ id ].setSize( h / 9 * 16, h + hideControl );
					} else {
						players[ id ].width( h / 9 * 16 );
						players[ id ].height( h + hideControl );
					}
				}
			} );
		},

		// Init video background.
		initVideoBackground: function( mode, object ) {

			$( '.sight-portfolio-video-inner' ).each( function() {

				// The mode.
				if ( !$( this ).parent().is( `[data-video-mode="${mode}"]` ) ) {
					return;
				}

				// The state.
				var isInit = $( this ).hasClass( 'sight-portfolio-video-init' );

				var id = null;

				// Generate unique ID.
				if ( !isInit ) {
					id = Math.random().toString( 36 ).substr( 2, 9 );
				} else {
					id = $( this ).attr( 'data-id' );
				}

				// Create contex.
				contex[ id ] = this;

				// The monitor.
				var isInView = $( contex[ id ] ).isInViewport();

				// The actived.
				var isActive = $( contex[ id ] ).hasClass( 'active' );

				// Get video attrs.
				var youtubeID = $( contex[ id ] ).parent().data( 'youtube-id' );
				var videoType = $( contex[ id ] ).parent().data( 'video-type' );
				var videoStart = $( contex[ id ] ).parent().data( 'video-start' );
				var videoEnd = $( contex[ id ] ).parent().data( 'video-end' );

				// Initialization.
				if ( isInView && !isInit ) {

					// Add init class.
					$( contex[ id ] ).addClass( 'sight-portfolio-video-init' );

					// Add unique ID.
					$( contex[ id ] ).attr( 'data-id', id );

					// Check video mode.
					if ( 'youtube' === mode ) {
						// Check video id.
						if ( typeof youtubeID === 'undefined' || !youtubeID ) {
							return;
						}

						// Video attrs.
						attrs[ id ] = {
							'videoId': youtubeID,
							'startSeconds': videoStart,
							'endSeconds': videoEnd,
							'suggestedQuality': 'hd720'
						};

						// Creating a player.
						players[ id ] = new YT.Player( contex[ id ], {
							playerVars: {
								autoplay: 0,
								autohide: 1,
								modestbranding: 1,
								rel: 0,
								showinfo: 0,
								controls: 0,
								disablekb: 1,
								enablejsapi: 0,
								iv_load_policy: 3,
								playsinline: 1,
								loop: 1
							},
							events: {
								'onReady': function() {

									players[ id ].cueVideoById( attrs[ id ] );
									players[ id ].mute();

									if ( 'always' === videoType ) {
										$this.playVideo( id );
									}
								},
								'onStateChange': function( e ) {
									if ( e.data === 1 ) {
										$( this ).contexObject( id ).closest( '.sight-portfolio-video-wrap' ).addClass( 'sight-portfolio-video-bg-init' );
										$( this ).contexObject( id ).addClass( 'active' );
									} else if ( e.data === 0 ) {
										players[ id ].seekTo( attrs[ id ].startSeconds );
									} else if ( e.data === 5 ) {
										players[ id ].videoReady = true;
									}
								}
							}
						} );
					} else {
						// Creating a player.
						players[ id ] = $( contex[ id ] );

						// Ready play.
						players[ id ].on( 'canplay', function() {
							players[ id ].videoReady = true;

							if ( true !== players[ id ].start ) {
								players[ id ].start = true;

								this.currentTime = videoStart ? videoStart : 0;

								if ( 'always' === videoType ) {
									$this.playVideo( id );
								}
							}
						} );

						// Play.
						players[ id ].on( 'play', function() {
							players[ id ].parents( '.sight-portfolio-video-wrap' ).addClass( 'sight-portfolio-video-bg-init' );
							players[ id ].addClass( 'active' );
						} );

						// Ended.
						players[ id ].on( 'timeupdate', function() {
							if ( videoEnd && this.currentTime >= videoEnd ) {
								players[ id ].trigger( 'pause' );

								this.currentTime = videoStart;

								players[ id ].trigger( 'play' );
							}
						} );

						players[ id ].trigger( 'load' );
					}

					$this.rescaleVideoBackground();
				}

				// Pause and play.
				if ( 'always' === videoType && isActive && isInit && ! $this.getVideoUpause( id ) ) {

					if ( isInView ) {
						$this.playVideo( id );
					}

					if ( ! isInView ) {
						$this.pauseVideo( id );
					}
				}
			} );
		},

		// Construct video background.
		constructVideoBackground: function( object ) {
			if ( $( 'body' ).hasClass( 'wp-admin' ) ) {
				return;
			}

			if ( process ) {
				return;
			}

			process = true;

			// Smart init API.
			if ( !initAPI ) {
				let elements = $( '[data-video-mode="youtube"][data-youtube-id]' );

				if ( elements.length ) {
					embedYoutubeAPI();

					initAPI = true;
				}
			}

			if ( !initAPI ) {
				process = false;
			}

			// Play Video.
			$this.initVideoBackground( 'local', object );

			if ( initAPI && YTdeferredDone ) {
				$this.initVideoBackground( 'youtube', object );
			}

			process = false;
		},

		// Get video type.
		getVideoType: function( id ) {
			return $( this ).contexObject( id, 'container' ).data( 'video-type' );
		},

		// Get video mode.
		getVideoMode: function( id ) {
			return $( this ).contexObject( id, 'container' ).data( 'video-mode' );
		},

		// Get video state.
		getVideoState: function( id ) {
			return players[ id ].videoState;
		},

		// Get video ready.
		getVideoReady: function( id ) {
			return players[ id ].videoReady ? players[ id ].videoReady : false;
		},

		// Get video upause.
		getVideoUpause: function( id ) {
			return players[ id ].videoUpause ? players[ id ].videoUpause : false;
		},

		// Get video volume.
		getVideoVolume: function( id ) {
			return players[ id ].videoVolume ? players[ id ].videoVolume : 'mute';
		},

		// Change video upause.
		changeVideoUpause: function( id, val ) {
			players[ id ].videoUpause = val;
		},

		// Play video.
		playVideo: function( id ) {
			if ( 'play' === players[ id ].videoState ) {
				return;
			}

			if ( ! players[ id ].videoReady ) {
				return setTimeout(function(){
					$this.playVideo( id );
				}, 100);
			}

			if ( 'youtube' === $this.getVideoMode( id ) ) {
				players[ id ].playVideo();
			} else {
				players[ id ].trigger( 'play' );
			}

			// Change control.
			let control = $( this ).contexObject( id, 'wrap' ).find( '.sight-portfolio-player-state' );

			$( control ).removeClass( 'sight-portfolio-player-pause' );
			$( control ).addClass( 'sight-portfolio-player-play' );

			// Set state.
			players[ id ].videoState = 'play';
		},

		// Pause video.
		pauseVideo: function( id ) {
			if ( 'pause' === players[ id ].videoState ) {
				return;
			}

			if ( ! players[ id ].videoReady ) {
				return;
			}

			if ( 'youtube' === $this.getVideoMode( id ) ) {
				players[ id ].pauseVideo();
			} else {
				players[ id ].trigger( 'pause' );
			}

			// Change control.
			let control = $( this ).contexObject( id, 'wrap' ).find( '.sight-portfolio-player-state' );

			$( control ).removeClass( 'sight-portfolio-player-play' );
			$( control ).addClass( 'sight-portfolio-player-pause' );

			// Set state.
			players[ id ].videoState = 'pause';
		},

		// Unmute video.
		unmuteVideo: function( id ) {
			if ( ! players[ id ].videoReady ) {
				return;
			}

			if ( 'youtube' === $this.getVideoMode( id ) ) {
				players[ id ].unMute();
			} else {
				players[ id ].prop( 'muted', false );
			}

			// Change control.
			let control = $( this ).contexObject( id, 'wrap' ).find( '.sight-portfolio-player-volume' );

			$( control ).removeClass( 'sight-portfolio-player-mute' );
			$( control ).addClass( 'sight-portfolio-player-unmute' );

			// Set state.
			players[ id ].videoVolume = 'unmute';
		},

		// Mute video.
		muteVideo: function( id ) {
			if ( ! players[ id ].videoReady ) {
				return;
			}

			if ( 'youtube' === $this.getVideoMode( id ) ) {
				players[ id ].mute();
			} else {
				players[ id ].prop( 'muted', true );
			}

			// Change control.
			let control = $( this ).contexObject( id, 'wrap' ).find( '.sight-portfolio-player-volume' );

			$( control ).removeClass( 'sight-portfolio-player-unmute' );
			$( control ).addClass( 'sight-portfolio-player-mute' );

			// Set state.
			players[ id ].videoVolume = 'muted';
		},

		// Toogle video state.
		toogleVideoState: function( id ) {
			if ( 'play' === $this.getVideoState( id ) ) {
				$this.pauseVideo( id );
			} else {
				$this.playVideo( id );
			}
		},

		// Toogle video volume.
		toogleVideoVolume: function( id ) {
			if ( 'unmute' === $this.getVideoVolume( id ) ) {
				$this.muteVideo( id );
			} else {
				$this.unmuteVideo( id );
			}
		},

		/** Events */
		events: function( e ) {
			// State Control.
			$doc.on( 'click', '.sight-portfolio-player-state', function() {
				let id = $( this ).contexObject( false, 'inner' ).attr( 'data-id' );

				$this.toogleVideoState( id );

				if ( 'play' === $this.getVideoState( id ) ) {
					$this.changeVideoUpause( id, false );
				} else {
					$this.changeVideoUpause( id, true );
				}
			} );

			// Stop Control.
			$doc.on( 'click', '.sight-portfolio-player-stop', function() {
				let id = $( this ).contexObject( false, 'inner' ).attr( 'data-id' );

				if ( 'play' === $this.getVideoState( id ) ) {
					$this.changeVideoUpause( id, true );
				}

				$this.pauseVideo( id );
			} );

			// Volume Control.
			$doc.on( 'click', '.sight-portfolio-player-volume', function() {
				let id = $( this ).contexObject( false, 'inner' ).attr( 'data-id' );

				$this.toogleVideoVolume( id );
			} );

			// Mouseover.
			$doc.on( 'mouseover mousemove', '.sight-portfolio-entry__thumbnail', function() {
				let id = $( this ).contexObject( false, 'inner' ).attr( 'data-id' );

				if ( 'hover' === $this.getVideoType( id ) ) {
					$this.playVideo( id );
				}
			} );

			// Mouseout.
			$doc.on( 'mouseout', '.sight-portfolio-entry__thumbnail', function() {
				let id = $( this ).contexObject( false, 'inner' ).attr( 'data-id' );

				if ( 'hover' === $this.getVideoType( id ) ) {
					$this.pauseVideo( id );
				}
			} );

			// Document scroll.
			$window.on( 'load scroll resize scrollstop', function() {
				$this.constructVideoBackground();
			} );

			// Document ready.
			$doc.ready( function() {
				$this.constructVideoBackground();
			} );

			// Post load.
			$body.on( 'post-load', function() {
				$this.constructVideoBackground();
			} );

			// Document resize.
			$window.on( 'resize', function() {
				$this.rescaleVideoBackground();
			} );

			// Init.
			$this.constructVideoBackground();
		}
	};
} )();

// Initialize.
sightVideoPortfolio.init();
