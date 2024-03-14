// Namespace
var mejs = mejs || {},
	codepeople_avp_generator = function()
	{
		if('undefined' != typeof codepeople_avp_generator_flag) return;
		codepeople_avp_generator_flag = true;

		var $ = jQuery;
		if(parseInt($.fn.jquery.replace(/\./g, '')) < 183)
		{
			$.ajax(
				{
				  url: 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js',
				  dataType: "script",
				  success: function(data)
				  {
					codepeople_avp(jQuery.noConflict());
				  }
				}
			);
		}
		else codepeople_avp($);
	};

function codepeople_avp($){
	var jQuery = $;

	function shuffle(a)
	{
		var c = a.length, t, i;
		while (c > 0)
		{
			i = Math.floor(Math.random() * c);
			c--;
			t = a[c];
			a[c] = a[i];
			a[i] = t;
		}
		return a;
	};

	/***  PLAYLIST CONTROLS  ***/
	mejs.Playlist = function(player){

		var me 		 = this,
			c 		 = player.container,
			n  		 = player.$node,
			id 		 = (n.closest('mediaelementwrapper').length) ? n.closest('mediaelementwrapper').attr('id') : n.attr('id'),
			clss 	 = n.attr('class').split(/\s+/),
			playlist = $('[id="'+id+'-list"]');

        // The playlist loop was activated
        me.loop = (n.attr('loop')) ? true : false;
		n.removeAttr('loop');

		me.items = playlist.find('li');

		// Player size
        me.playerWidth  = c.width();
        me.playerHeight = c.height();

        // Set the player object associated to the playlist
        me.player = player;

        // Set the player id
        me.playerId = id;

        function updateTranslateX(cpm)
        {
            var h = cpm.find('.mejs-time-handle'),
                w = h.width(),
                t = cpm.find('div.mejs-time-rail').width() - w,
                ct = h.css('transform').split(/[()]/)[1].replace(/[^\d\,]+/g,'').split(',')[4];

            t = Math.min(t,ct);
            h.attr('style', 'transform:translateX('+t+'px)');
        }

        me.player.media.addEventListener('timeupdate', function (e) {
            try
            {
                var cpm = $(e.detail.target).closest('div.codepeople-media');
                updateTranslateX(cpm);
            } catch(err) {}
        });

        // Playback the next playlist item
		me.player.media.addEventListener('ended', function (e) {
			try{me.player.pause();}catch(err){me.player.node.pause();}

            var pl = me.playlist,
                l  = pl.find('li').length;

            if(pl && pl.find('li').index(pl.find('.current'))+1<l)
                me.playNext();
            else if(me.loop){
                pl.find('li:first').click();
            }
            else if(
                typeof cpmp_general_settings != 'undefined' &&
                'play_all' in cpmp_general_settings &&
                parseInt(cpmp_general_settings['play_all'])
            )
            {
                try
                {
                    var cpm = $(e.detail.target).closest('div.codepeople-media'),
                        flag = false;

                    updateTranslateX(cpm);

                    $(document).find('div.codepeople-media').each(function(){
                        if(flag)
                        {
                            $(this).next('.emjs-playlist').find('li:first').click();
                            flag = false;
                        }
                        else if(this == cpm[0]) flag = true;
                    });
                } catch (err) {}
            }
        }, false);

		// There is a playlist associated to the player
		if(playlist.length){
			// Set the playlist node
			me.playlist = playlist;

			me.playlist.on(
				'click',
				function(evt){
					if ( $(evt.target).hasClass('cpmp-playlist-download-link') ) return;
					me.random = !me.random;
					me.history = [];
					me.items = playlist.find('li');
					me.playlist.removeClass('random-active');
					if(me.random)
					{
						me.playlist.addClass('random-active');
						me.items = shuffle(me.items);
					}
				}
			);

			// Set the playlist class
			me.playlist.addClass('emjs-playlist');
			if(!me.playlist.hasClass('no-playlist')) me.playlist.show();

			// Set the skin class to playlist
			for(var i = 0, h = clss.length; i < h; i++){
				if(/\-skin/i.test(clss[i])){
					me.skin = clss[i];
					me.playlist.addClass(me.skin);
					break;
				}
			}

			// Set the playlist width
			me.playlist.width(c.width());

			// Associate click events to the playlist items
			$('li', me.playlist).click(function(evt){
				if ( $(evt.target).hasClass('cpmp-playlist-download-link') ) return;
				evt.stopPropagation(); me.selectItem($(this));
			});

			// Associate the playist to the music player
			me.player.playlist = me;

            // Set random
            if(typeof me.player.node.attributes['shuffle'] != 'undefined') me.playlist.click();
		}
	};

	mejs.Playlist.prototype = {
		playlist 		: null,
		random			: false,
		player 			: null,
		playerId 		: '',
		items			: [],
		history			: [],
		playerWidth 	: null,
		playerHeight 	: null,
		attributes 		:{
			show : true
		},
		skin 			: null,

		parseItem : function(item){
			var e;
			try{
				return $.parseJSON(item);
			}catch(e){
				return item;
			}

		},

		parseSrc : function(src){
			function adjustedSrc(v){
				var d = new Date();
				v += ((v.indexOf('?') == -1) ? '?' : '&cpmp=')+d.getTime();
				return v;
			};

			var source = {};

			if(typeof src != 'string'){ // The src is an object
				if(src.type) source['type'] = src.type;
				if(src.src)  source['src']  = adjustedSrc(src.src);
			}else { // The src is a string with media location
				source['src'] = adjustedSrc(src);
			}
			return source;
		},

		parseTrack : function(trck){
			var track = '<track';

			if(typeof src != 'string'){ // The trck is an object
				track += ' srclang = "' + ((trck.srclang) ? trck.srclang : 'en') + '"';
				track += ' kind = "'    + ((trck.kind)    ? trck.kind    : 'subtitles') + '"';
				if(trck.src) track += ' src="' + trck.src + '"';
			}else{ // The trck is a string with caption location
				track += ' kind="subtitles" srclang="en" src="' + trck + '"';
			}

			track += ' />';
			return track;
		},

		addHistory : function(e){
			var me = this,
				i  = $.inArray(e, me.history);

			if(i == -1)
			{
				i = me.history.length;
				me.history.push(e);
			}

			return i;
		},

		playItem : function(item){
			var me = this, player = me.player, node = player.node, poster = '', srcTags, trackTags = '';

			if(typeof item != 'string')
			{
				srcTags = [];
				if(item.poster) player.setPoster(item.poster);
				if(item.source)
				{
					if($.isArray(item.source))
						// many source formats
						$.each(item.source, function(i, src){ srcTags.push(me.parseSrc(src)); });
					else
						// only one source
						srcTags.push(me.parseSrc(item.source));
				}
				// Assign tracks
				if(item.track)
				{
					if($.isArray(item.track))
						// many captions
						$.each(item.track, function(i, track){ trackTags += me.parseTrack(track); });
					else
						trackTags += me.parseTrack(item.track);
				}
			}
			else srcTags = item;
			if(srcTags.length)
			{
				$(node).find('track').remove();
				if(trackTags != '')
				{
					$(node).append(trackTags);
					player.rebuildtracks();
				}

				try
				{
					player.setSrc( srcTags );
					player.load();
					player.play();
				}
				catch(err)
				{
					node.setSrc( srcTags[0]['src'] );
					node.load();
					node.play();
				}
			}

		},

		/**
		 * playNext allow to play the next and previous items from playlist
		 * if next argument is false the previous item is selected
		 */
		playNext : function(is_next){
			var me = this,
				current_item = me.playlist.find('li.current:first'), // get the .current song
				next_item,
				valid = false,
				l = me.items.length,
				i,
				j;

			// Initialization
			if(typeof is_next == 'undefined') is_next = true;
			if(current_item.length == 0) current_item = me.playlist.find('li:first'); // get :first if we don't have .current class

			if(l)
			{ // If playlist is not empty

				i = $.inArray(current_item[0], me.items);

				me.addHistory(i);

				if(me.random)
				{
					var found = false;
					j = i;
					do
					{
						if(l-1 <= j) j = 0;
						else j++;
						if($.inArray(j, me.history) == -1) found = true;
					}
					while(j != i && !found);

					if(found) valid = true;
					else
					{
						if(me.loop)
						{
							me.history = [];
							j = 0;
							valid = true;
						}
					}
				}
				else
				{
					if((l-1 == i && is_next) || (i == 0 && !is_next))
					{ // if it is last - stop playing or jump to the first item
						if(me.loop)
						{
							if(is_next) j = 0;
							else j = l-1;
							valid = true;
						}
					}
					else
					{ // take the next item to playback
						j = (is_next) ? i+1 : i-1;
						valid = true;
					}
				}

				if(valid)
				{
					$(current_item).removeClass('current');
					me.addHistory(j);
					next_item = $(me.items[j]).addClass('current')[0].getAttribute('value');
					me.playItem(me.parseItem(next_item));
				}
			}
		},

		selectItem : function(item){
			var me = this;
            $(".mejs-pause").trigger('click');
            item.addClass('current').siblings().removeClass('current');

            if(item.siblings().length){
                var the_item = item[0].getAttribute('value');
                me.playItem(me.parseItem(the_item));
            }else{
                item.parents('#ms_avp').find('.mejs-play').click();
            }
		}
	};

	/***  NEXT BUTTON CONTROL  ***/
	MediaElementPlayer.prototype.buildnext = function(player, controls, layers, media) {
		if(jQuery('[id="'+media.id+'-list"] li').length < 2) return;
        var
            // create the loop button
            next =
            $('<div class="mejs-button mejs-next-button">' +
                '<button title="Next"></button>' +
            '</div>')
            // append it to the toolbar
            .appendTo(controls)
            // add a click toggle event
            .click(function() {
				if(player.playlist)
					player.playlist.playNext();
            });
    };

	/***  PREVIOUS BUTTON CONTROL  ***/
	MediaElementPlayer.prototype.buildprevious = function(player, controls, layers, media) {
		if(jQuery('[id="'+media.id+'-list"] li').length < 2) return;
        var
            // create the loop button
            next =
            $('<div class="mejs-button mejs-previous-button">' +
                '<button title="Previous"></button>' +
            '</div>')
            // append it to the toolbar
            .appendTo(controls)
            // add a click toggle event
            .click(function() {
				if(player.playlist)
					player.playlist.playNext(false);
            });
    };

	/***  EQ CONTROL  ***/
	MediaElementPlayer.prototype.buildeq = function(player, controls, layers, media) {
        var
            // create the eq bars
            eq =
            $('<div class="eq" style="display:none">'+
				'<span class="bar"></span>'+
				'<span class="bar"></span>'+
				'<span class="bar"></span>'+
				'<span class="bar"></span>'+
				'<span class="bar"></span>'+
			  '</div>')
            // append it to the toolbar
            .appendTo(controls);


		// Animate bars
		function fluctuate(bar, h) {
			var v = player.media.volume || 0,
				hgt = (Math.random()) * h * v,
				t = (hgt+1) * 30;

			if(media.paused || media.ended) {
				eq.hide();
			}else
				if(media.currentTime){
					eq.show();
				}

			bar.animate({
				height: hgt
			}, t, function() {
				fluctuate($(this), h);
			});
		}

		controls.find('.bar').each(function(i, bar){
			var b = $(bar),
				w = b.width(),
				h = b.height();

			b.css('left', (w*i+2*i)+'px');
			fluctuate(b, h);
		});
	};
	$('.codepeople-media').each(function(){
		var me 		 = $(this),
			device_player = me.hasClass('device-player-skin'),
			settings = {
				videoVolume: 'horizontal',
				hideVideoControlsOnLoad: true,
				success: function(media, node,  player) {
					var cp = $(node).parents('.codepeople-media');

					if(!cp.length) cp = $(node);
					if(media.pluginType && media.pluginType == 'silverlight') cp.addClass('silverlight');

					// Set titles
					cp.find( '.mejs-time-handle' ).attr( 'title', 'Seek' );
					cp.find( '.mejs-horizontal-volume-current,.mejs-vertical-volume-current' ).attr( 'title', 'Volume' );

					// Get skin
					var cls = cp.attr('class');
					cls = cls.replace(/^\s+/, '').replace(/\s+$/, '').split(/\s+/);

					for(var i = 0, h = cls.length; i < h; i++){
						if(/\-skin$/.test(cls[i])){
							if( typeof cp_skin_js != 'undefined' && cp_skin_js[cls[i]]){
								cp_skin_js[cls[i]]($);
							}
							break;
						}
					}

					media.addEventListener( 'play', function(){
						var p = $( node ).parents( '#ms_avp' );
						if( p.length && p.find( '.current' ).length == 0 ){
							p.find( '.emjs-playlist li:first' ).addClass( 'current' );
						}

					} );

					new mejs.Playlist(player);

					// Move caption when show/hide controls
					if (  typeof MutationObserver != 'undefined' ) {
						try {
							var targetNode = jQuery(player.$media).closest('#ms_avp').find('.mejs-controls');
							if ( targetNode.length ) {
								var observer = new MutationObserver(function(){
									var caption_layer = targetNode.siblings('.mejs-layers').find('.mejs-captions-position');
									if ( caption_layer.length ) {
										if(targetNode.is(':visible')){
											caption_layer.css('bottom', (targetNode.height()+10)+'px');
										} else {
											caption_layer.css('bottom', '10px' );
										}
									}
								});
								observer.observe(targetNode[0], { attributes: true, childList: true });
							}
						} catch ( err ) {}
					}
				}
			};

		settings[ 'defaultVideoHeight' ] = settings[ 'audioHeight' ] = settings[ 'videoHeight' ] = me.height();
		settings[ 'defaultVideoWidth' ] = settings[ 'audioWidth' ] = settings[ 'videoWidth' ] = me.parent('#ms_avp').width();

		settings[ 'iPadUseNativeControls' ] =
		settings[ 'iPhoneUseNativeControls' ] =
		settings[ 'AndroidUseNativeControls' ] = device_player;

		if(device_player) settings['features'] = ['playpause','fullscreen','tracks','current','progress','duration','volume'];
		else settings['features'] = ['previous','playpause','next','fullscreen','tracks','eq','current','progress','duration','volume'];

		me.mediaelementplayer( settings );
	});
}

// Main app
jQuery(codepeople_avp_generator);
jQuery(window).on('load',codepeople_avp_generator);