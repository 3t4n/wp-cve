function generate_the_eddmp(isOnLoadEvent)
{
	if(
		typeof isOnLoadEvent !== 'boolean' &&
		typeof eddmp_global_settings != 'undefined' &&
		eddmp_global_settings['onload']*1
	) return;

	if('undefined' !== typeof generated_the_eddmp) return;
	generated_the_eddmp = true;

	var $ = jQuery;
	$('.eddmp-player-container').on('click', '*', function(evt){evt.preventDefault();evt.stopPropagation();return false;});

	/**
	 * Play next player
	 */
	function _playNext( playerNumber, loop )
	{
		if( playerNumber+1 < player_counter || loop)
		{
			var toPlay = playerNumber+1;

            if(
                loop &&
                (
                    toPlay == player_counter ||
                    $('[playerNumber="'+toPlay+'"]').closest('[data-loop]').length == 0 ||
                    $('[playerNumber="'+toPlay+'"]').closest('[data-loop]')[0] != $('[playerNumber="'+playerNumber+'"]').closest('[data-loop]')[0]
                )
            )
            {
                toPlay = $('[playerNumber="'+playerNumber+'"]').closest('[data-loop]').find('[playerNumber]:first').attr('playerNumber');
            }

			if( players[ toPlay ] instanceof $ && players[ toPlay ].is( 'a' ) ) players[ toPlay ].trigger('click');
			else players[ toPlay ].play();
		}
	};

	$.expr.pseudos.regex = function(elem, index, match) {
		var matchParams = match[3].split(','),
			validLabels = /^(data|css):/,
			attr = {
				method: matchParams[0].match(validLabels) ?
							matchParams[0].split(':')[0] : 'attr',
				property: matchParams.shift().replace(validLabels,'')
			},
			regexFlags = 'ig',
			regex = new RegExp(matchParams.join('').replace(/^\s+|\s+$/g,''), regexFlags);
		return regex.test($(elem)[attr.method](attr.property));
	}

	//------------------------ MAIN CODE ------------------------
	var players = [],
		player_counter = 0,
		play_all = (typeof eddmp_global_settings != 'undefined') ? eddmp_global_settings[ 'play_all' ] : true, // Play all songs on page
		fade_out = (typeof eddmp_global_settings != 'undefined') ? eddmp_global_settings['fade_out']*1 : true,
		pause_others = (typeof eddmp_global_settings != 'undefined') ? !(eddmp_global_settings['play_simultaneously']*1) : true,
		ios_controls = (
			typeof eddmp_global_settings != 'undefined' &&
			('ios_controls' in eddmp_global_settings) &&
			eddmp_global_settings['ios_controls']*1
		) ? true : false,
		s = $('audio.eddmp-player:not(.track):not([playerNumber])'),
		m = $('audio.eddmp-player.track:not([playerNumber])'),
		c = {
				pauseOtherPlayers: pause_others,
				iPadUseNativeControls: ios_controls,
				iPhoneUseNativeControls: ios_controls,
				success: function( media, dom ){
                    var duration = $(dom).data('duration'),
                        estimated_duration = $(dom).data('estimated_duration'),
                        player_index = $(dom).attr('playerNumber');

                    if(typeof estimated_duration != 'undefined')
                    {
                        media.getDuration = function(){
                            return estimated_duration;
                        };
                    }

                    if(typeof duration != 'undefined')
                    {
                        setTimeout((function(player_index, duration){
                            return function(){
                                players[ player_index ].updateDuration = function(){
                                    $(this.media).closest('.eddmp-player')
                                     .find('.mejs-duration')
                                     .html(duration);
                                };
                                players[ player_index ].updateDuration();
                            };
                        })(player_index, duration), 50);
                    }

					if($(dom).attr('volume'))
                    {
                        media.setVolume(parseFloat($(dom).attr('volume')));
                        if(media.volume == 0) media.setMuted(true);
                    }

					media.addEventListener( 'timeupdate', function( evt ){
                        var e = media, duration = e.getDuration();
						if(!isNaN( e.currentTime ) && !isNaN( duration ))
						{
							if( fade_out && duration - e.currentTime < 4 )
							{
								e.setVolume( e.volume - e.volume / 3 );
							}
							else
							{
								if( typeof e[ 'bkVolume' ] == 'undefined' )
                                    e[ 'bkVolume' ] = parseFloat( $(e).find('audio,video').attr('volume') || e.volume);
								e.setVolume( e.bkVolume );
                                if(e.bkVolume == 0) e.setMuted(true);
							}

						}
					});

					media.addEventListener( 'volumechange', function( evt ){
                        var e = media, duration = e.getDuration();
						if(!isNaN( e.currentTime ) && !isNaN( duration ))
						{
							if( ( duration - e.currentTime > 4 || !fade_out ) && e.currentTime )  e[ 'bkVolume' ] = e.volume;
						}
					});

					media.addEventListener( 'ended', function( evt ){
						var e = media,
                            c = $(e).closest('[data-loop="1"]');
                         e.currentTime = 0;

						if( play_all*1 || c.length)
						{
							var playerNumber = $(e).attr('playerNumber')*1;
                            if(isNaN(playerNumber))
                                playerNumber = $(e).find('[playerNumber]').attr('playerNumber')*1;
							_playNext( playerNumber, c.length );
						}
					});
				}
			};

	s.each(function(){
		var e 	= $(this),
			src = e.find( 'source' ).attr( 'src' );

		e.attr('playerNumber', player_counter);

		c['audioVolume'] = 'vertical';
		try{
			players[ player_counter ] = new MediaElementPlayer(e[0], c);
		}
		catch(err)
		{
			if('console' in window) console.log(err);
		}
		player_counter++;
	});


	m.each(function(){
		var e = $(this),
			src = e.find( 'source' ).attr( 'src' );

		e.attr('playerNumber', player_counter);

		c['features'] = ['playpause'];
		try{
			players[ player_counter ] = new MediaElementPlayer(e[0], c);
		}
		catch(err)
		{
			if('console' in window) console.log(err);
		}
		player_counter++;
	});
}

function eddmp_force_init()
{
	delete window.generated_the_eddmp;
	generate_the_eddmp(true);
}

jQuery(generate_the_eddmp);
jQuery(window).on('load', function(){
	generate_the_eddmp(true);
	var $ = jQuery,
		ua = window.navigator.userAgent;

	$('[data-lazyloading]').each(function(){ var e = $(this); e.attr('preload', e.data('lazyloading'));});
	if(ua.match(/iPad/i) || ua.match(/iPhone/i))
	{
		var p = (typeof eddmp_global_settings != 'undefined') ? eddmp_global_settings[ 'play_all' ] : true;
		if(p) // Solution to the play all in Safari iOS
		{
			$('.eddmp-player .mejs-play button').one('click', function(){

				if('undefined' != typeof eddmp_preprocessed_players) return;
				eddmp_preprocessed_players = true;

				var e = $(this);
				$('.eddmp-player audio').each(function(){
					this.play();
					this.pause();
				});
				setTimeout(function(){e.trigger('click');}, 500);
			});
		}
	}
}).on('popstate', function(){
	if(jQuery('audio[data-download]:not([playerNumber])').length) eddmp_force_init();
});

jQuery(document).on('scroll', eddmp_force_init);