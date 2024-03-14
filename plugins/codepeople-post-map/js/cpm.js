/**
 * CodePeople Post Map
 * Version: 1.0.1
 * Author: CodePeople
 * Plugin URI: http://wordpress.dwbooster.com
*/

var CodePeoplePostMapPublicCounter = 0;
function CodePeoplePostMapPublic()
{
	CodePeoplePostMapPublicCounter++;
	if( typeof jQuery == 'undefined' )
	{
		if( CodePeoplePostMapPublicCounter <= 6 ) setTimeout( function(){ CodePeoplePostMapPublic() }, 1000 );
		return;
	}
	jQuery(function( $ ){
		// Create a class with CodePeople Post Map functionalities and attributes
		$.CPM = function(id, config){
			this.data = $.extend(true, {}, this.defaults, config);
			this.id = id;
			this.markers = [];
			this.uniqueMarkers = [];
			if( typeof this.data[ 'center' ] == 'object' && typeof this.data.center.length != 'undefined' && this.data.center.length == 2 )
			{
				this.data.center = new google.maps.LatLng( this.data.center[ 0 ], this.data.center[ 1 ] );
			}
			else
			{
				this.data.center = null;
			}
		};

		$.CPM.prototype = {
			defaults : {
				markers 		: [],
				marker_title	: 'title',
				zoom			: 10,
				dynamic_zoom    : false,
				drag_map        : true,
				type			: 'ROADMAP',
				mousewheel 		: true,
				scalecontrol 	: true,
				zoompancontrol 	: true,
				fullscreencontrol: true,
				typecontrol 	: true,
				streetviewcontrol : true,
				trafficlayer 	: false,
				show_window     : true,
				show_default    : true,
				display			: 'map',
				highlight		: true,
				highlight_class : 'cpm_highlight'
			},

			// private methods to complete every steps in map generation
			_correct : function( v ){
				v = (new String(v)).replace(/[^\d\+\-\.]/g, '');
				return v*1;
			},

			_empty : function (v){
				return (!v || /^\s*$/.test(v));
			},

			_get_latlng : function(i){
				var me = this,
					g  = new google.maps.Geocoder(),
					m  = me.data.markers,
					a  = m[i]['address'];

				g.geocode({address:a}, function(result, status){
					me.counter--;
					if(status && status == "OK"){
						m[i]['latlng'] = new google.maps.LatLng(me._correct(result[0]['geometry']['location'].lat()), me._correct(result[0]['geometry']['location'].lng()));
					}else{
						m[i]['invalid'] = true;
					}

					// All points have been checked now is possible to load the map
					if(me.counter == 0){
						me._load_map();
					}
				});
			},
			_str_transform : function( t ){
				return t.replace( /&lt;/g, '<')
						.replace( /&gt;/g, '>')
						.replace( /&amp;/g, '&')
						.replace( /&quot;/g, '"')
						.replace(/\\'/g, "'")
						.replace(/\\"/g, '"' );
			},
			_unique : function( l ){
				var rtn = [];
				this.uniqueMarkers = [];
				for( var i = 0, h = l.length; i < h; i++ )
				{
					if( typeof this.uniqueMarkers[ l[ i ].position.toString() ] == 'undefined' )
					{
						this.uniqueMarkers[ l[ i ].position.toString() ] = [];
						rtn.push( l[ i ] );
					}
					else
					{
						l[ i ].visible = false;
					}
					this.uniqueMarkers[ l[ i ].position.toString() ].push( l[ i ] );
				}

				return rtn;
			},
			_load_map : function(){

				var me = this,
					m  = me.data.markers,
					h  = m.length,
					c  = 0,
					v  = 0; // Number of valid points

				while(c < h && m[c]['invalid']) c++;

				if(c < h){
					me.map = new google.maps.Map($('#'+me.id)[0], {
							zoom: me.data.zoom,
							center: ( typeof me.data.center != 'undefined' && me.data.center != null ) ? me.data.center : m[c].latlng,
							mapTypeId: google.maps.MapTypeId[me.data.type],
							gestureHandling: ((me.data.mousewheel) ? 'cooperative' : 'none'),
							draggable: me.data.drag_map,

							// Show / Hide controls
							scaleControl: me.data.scalecontrol,
							zoomControl: me.data.zoompancontrol,
							mapTypeControl: me.data.typecontrol,
							streetViewControl: me.data.streetviewcontrol,
							fullscreenControl: me.data.fullscreencontrol,
							backgroundColor: 'none'
					});

					var map = me.map,
						bounds = new google.maps.LatLngBounds(),
						default_point = -1;

					if(me.data.trafficlayer)
					{
						var trafficLayer = new google.maps.TrafficLayer();
						trafficLayer.setMap(me.map);
					}

					if( me.data.show_default ){
						google.maps.event.addListenerOnce(map, 'idle', function(){
							setTimeout(function(){
								if( me.markers.length ) google.maps.event.trigger( ( ( default_point < 0 ) ? me.markers[ 0 ] : me.markers[ default_point ] ), 'click' );
							}, 1000);
						});
					}
					me.infowindow = new google.maps.InfoWindow();
					var title = null;
					for (var i = c; i < h; i++){
						if(!m[i]['invalid']){
							if( typeof m[ i ][ 'default' ] != 'undefined' && m[ i ][ 'default' ] )
							{
								default_point = me.markers.length;
							}

							bounds.extend(m[i].latlng);

							if(/title/i.test(me['data']['marker_title']))
								title = me._str_transform($( m[i].info ).find('.title').text());
							else if(/address/i.test(me['data']['marker_title']))
								title = (m[i].address) ? me._str_transform(m[i].address) : '';

							var marker = new google.maps.Marker({
														  position: m[i].latlng,
														  map: map,
														  icon: new google.maps.MarkerImage(m[i].icon.replace(/^http:/i, '')),
														  title:title
														 });

							marker.id = i;
							me.markers.push(marker);
							google.maps.event.addListener(marker, 'click', function(){ me.open_infowindow(this); });
							google.maps.event.addListener(marker, 'mouseover', function(){ me.set_highlight(this); });
							google.maps.event.addListener(marker, 'mouseout', function(){ me.unset_highlight(this); });
						}
					}
					me._unique( me.markers );
					if (h > 1 && me.data.dynamic_zoom) {
						setTimeout( ( function( m, b ){ return function(){ m.fitBounds( b ); }; } )( map, bounds ), 500 );
					}
					else if (h == 1 || !me.data.dynamic_zoom) {
						if( typeof me.data.center != 'undefined' && me.data.center != null )
						{
							map.setCenter( me.data.center );
						}
						else
						{
							if( default_point != -1 )
							{
								map.setCenter( me.markers[ default_point ].getPosition() );
							}
							else
							{
								map.setCenter(bounds.getCenter());
							}
						}
						map.setZoom(me.data.zoom);
					}
				}
				else
				{
					$('#'+me.id).hide();
				}
			},

			// public methods
			set_map: function(){
				var me = this;
				if(me.data.markers.length){
					var m = me.data.markers,
						h = m.length,
						z = 0;

					me.counter = h; // Counter is used to know the momment where all latitudes or longitudes were calculated

					function _get_latlng()
					{
						var tmp = 0;
						for(var i=z; i < h; i++){
							z++;
							if(typeof m[i] == 'undefined')
							{
								m[i] = {'invalid':true};
								me.counter--;
								continue;
							}else if((me._empty(m[i].lat) || me._empty(m[i].lng)) && !me._empty(m[i].address)){
								me._get_latlng(i);
								tmp++;
								if(tmp == 20)
								{
									setTimeout(_get_latlng, 1500);
								}
							}else if(me._empty(m[i].lat) && me._empty(m[i].lng)){
								// The address is not present so the point may be removed from the list
								m[i]['invalid'] = true;
								me.counter--;
							}else{
								m[i]['latlng'] = new google.maps.LatLng(me._correct(m[i].lat), me._correct(m[i].lng));
								me.counter--;
							}
						}
					};

					_get_latlng();

					// All points have been checked now is possible to load the map
					if(me.counter == 0){
						me._load_map();
					}
				}
			},

			// Open the marker bubble
			open_infowindow : function(m){
				var me = this,
					info   = '',
					unique = me.uniqueMarkers[ m.position.toString() ];

				if ( !me.data.show_window ) return;

				// Get the information of all concident points
				for( var i = 0, h = unique.length; i < h; i++ )
				{
					info += ( i < h-1 ) ? $( '<div></div>').html( me.data.markers[ unique[ i ].id ].info ).find( '.cpm-infowindow-additional' ).remove().end().html() : me.data.markers[ unique[ i ].id ].info;
				}

				var c  = me._str_transform( info ),
					img = $( c.replace( '%additional%', '' ) ).find( 'img' );

				if( img.length )
				{
					var count = img.length;
					img.each( function(){
							$( '<img src="'+$(this).attr( 'src' ) +'">' ).on('load', (function( c, m ){
							return function(){
								count--;
								if( count == 0 )
								{
									me.infowindow.setContent( c );
									me.infowindow.open( me.map, m );
								}
							};
						} )( c, m ) );
					} );
				}
				else
				{
					c += '<style>.cpm-infowindow{ min-height:auto !important; } </style>';
					me.infowindow.setContent( c );
					me.infowindow.open( me.map, m );
				}
				},

			// Set the highlight class to the post with ID m['post']
			set_highlight : function(m){
				if(this.data.highlight){
					var id = this.data.markers[m.id]['post'];
					$('.post-'+id).addClass(this.data.highlight_class);
				}
			},

			// Remove the highlight class from the post with ID m['post_id']
			unset_highlight : function(m){
				if(this.data.highlight){
					var id = this.data.markers[m.id]['post'];
					$('.post-'+id).removeClass(this.data.highlight_class);
				}
			}
		};
		// End CPM class definition

		// Callback function to be called after loading the maps api
		function initialize( e )
		{
			var map_container = $( e ),
				map_id = map_container.attr('id');

			if( map_container.parent().is( ':hidden' ) )
			{
				setTimeout( function(){ initialize( e ); }, 500 );
				return;
			}

			if(cpm_global && cpm_global[map_id] && cpm_global[map_id]['markers'].length){
				// The maps data are defined
				var cpm = new $.CPM(map_id, cpm_global[map_id]);

				// Display map
				if(cpm_global[map_id]['display'] == 'map'){
					map_container.show();
					cpm.set_map();
				}else{
					// Insert a icon to display map
					var map_icon = $('<div class="cpm-mapicon"></div>');
					map_icon.on('click',function(){
						if(map_container.is( ':visible' ))
						{
							map_container.hide();
						}
						else
						{
							map_container.show();
							cpm.set_map();
						}
					});
					map_icon.insertBefore(map_container);
				}
			}
		};

		window['cpm_init'] = function(){
			$('.cpm-map').each(function(){
				if( $( this ).parent().is( ':hidden' ) )
				{
					setTimeout(
						( function ( e )
							{
								return function(){ initialize( e ); };
							} )( this ),
						500
					);
				}
				else
				{
					initialize( this );
				}
			});
		};

		var map = $('.cpm-map');
		if(map.length){
			if(typeof google == 'undefined' || google['maps'] == null){
				// Create the script tag and load the maps api
				var script=document.createElement('script');
				script.type  = "text/javascript";
				script.src=(( typeof window.location.protocol != 'undefined' ) ? window.location.protocol : 'http:' )+'//maps.google.com/maps/api/js?'+((typeof cpm_api_key != 'undefined' && cpm_api_key != '')? 'key='+cpm_api_key+'&' :'')+'callback=cpm_init'+((typeof cpm_language != 'undefined' && cpm_language.lng) ? '&language='+cpm_language.lng: '');
				document.body.appendChild(script);
			}else{
				cpm_init();
			}
		}
	});
}

window.addEventListener("load", function(event){
    function CodePeoplePostMapRun()
    {
		try {
			if(
				! ( 'BorlabsCookie' in window ) ||
				(
					'checkCookieConsent' in window.BorlabsCookie &&
					(
						window.BorlabsCookie.checkCookieConsent( 'googlemaps' ) ||
						window.BorlabsCookie.checkCookieConsent( 'maps' )
					)
				) ||
				(
					'Consents' in window.BorlabsCookie &&
					(
						window.BorlabsCookie.Consents.hasConsent( 'googlemaps' ) ||
						window.BorlabsCookie.Consents.hasConsent( 'maps' )
					)
				)
			) setTimeout(CodePeoplePostMapPublic,300);
		} catch ( err ) {
			setTimeout(CodePeoplePostMapPublic,300);
		}
    }

    CodePeoplePostMapRun();
    document.addEventListener('borlabs-cookie-code-unblocked-after-consent', function(){CodePeoplePostMapRun();});
    document.addEventListener('borlabs-cookie-consent-saved', function(){CodePeoplePostMapRun();});

});