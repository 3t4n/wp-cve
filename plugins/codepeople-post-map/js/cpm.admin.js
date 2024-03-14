/**
 * CodePeople Post Map
 * Version: 1.0.1
 * Author: CodePeople
 * Plugin URI: http://wordpress.dwbooster.com
*/

(function ($) {
	var _latlng_btn, _api_key = '';

	if(
		typeof cpm_global != 'undefined' &&
		typeof cpm_global['api_key'] != 'undefined' &&
		cpm_global['api_key'] != ''
	) _api_key = 'key='+cpm_global['api_key']+'&';

	if(!('gm_authFailure' in window))
	{
		window['gm_authFailure'] = function()
		{
			$('.cpm_error_mssg').css({'font-weight': 'bold', 'font-size': '1.2em', 'color':'#FF0000', 'padding': '10px 0'}).html('Google maps failed to load! Be sure has been entered an API Key through the settings page of the plugin, and it can be used on this website.');
		};
	}

    window["cpm_thumbnail_selection"] = function(e){
        var thumbnail_field = $(e).parent().find('input[type="text"]');
        var media = wp.media({
                title: 'Select Point Thumbnail',
                button: {
                text: 'Select Image'
                },
                multiple: false
        }).on('select',
			(function( field ){
				return function() {
					var attachment = media.state().get('selection').first().toJSON();
					if( !/image/g.test( attachment.mime ) ) return;
					var fullSize = attachment.url;
					var imgUrl = (typeof attachment.sizes.thumbnail === "undefined") ? fullSize : attachment.sizes.thumbnail.url;
					field.val( imgUrl );
				};
			})( thumbnail_field )
		).open();
        return false;
    };

    //---------------------------------------------------------

    function _get_latlng(request, callback){
		var g = new google.maps.Geocoder();
		g.geocode(request, callback);
	};

	window['cpm_get_latlng'] = function (){
		function transform( v )
		{
			if( !isNaN( parseFloat( v ) ) && isFinite( v ) ) return v;
			v = v.replace(/[\W_]/g, " " ).replace(/\s+/g, ' ' ).replace( /^\s+/, '' ).replace( /\s+$/, '' ).toLowerCase();
			var ref = ( /[ne]/.test( v ) ) ? 1 : -1,
				parts = v.split( ' ' ),
				l = parts.length;

			if( l >= 3 ) return ref * ( parts[ l - 3 ]*1 + parts[ l - 2 ]*1 / 60 + parts[ l - 1 ]*1  / 3600 );
			return v;
		};

		var f 			= _latlng_btn.parents('.point_form'),
			a 			= $('#cpm_point_address').val(),
			longitude 	= $('#cpm_point_longitude').val(),
			latitude 	= $('#cpm_point_latitude').val(),
			language	= $('#cpm_map_language').val(),
			request		= {};

		// Remove unnecessary spaces characters
		longitude = longitude.replace(/^\s+/, '').replace(/\s+$/, '');
		latitude  = latitude.replace(/^\s+/, '').replace(/\s+$/, '');
		a = a.replace(/^\s+/, '').replace(/\s+$/, '');

		if(longitude.length && latitude.length){
			request['location'] = new google.maps.LatLng( transform( latitude ), transform( longitude ) );
		}else if(a.length){
			request['address'] = a.replace(/[\n\r]/g, '');
		}else{
			return false;
		}

		_get_latlng(
			request,
			(function( a, r )
				{
					return  function(result, status)
							{
								if(status && status == "OK"){
									// Update fields
									var address   = ( String( a ).trim().length && typeof  r[ 'location' ] != 'undefined' ) ? a : result[0]['formatted_address'],
										latitude  = result[0]['geometry']['location'].lat(),
										longitude = result[0]['geometry']['location'].lng();

									if(address && latitude && longitude){
										$('#cpm_point_address').val(address);
										$('#cpm_point_longitude').val(longitude);
										$('#cpm_point_latitude').val(latitude);

										// Load Map
										cpm_load_map(f.find('.cpm_map_container'),latitude, longitude);
									}
								}else{
									alert('The point is not located: Be sure the address is valid, has been entered an API Key through the settings page of the plugin, and the Geocoding API is enabled in the Google Project.');
								}
							};

				} )( a, request )
		);
	};

	// Check the point or address existence
	window['cpm_checking_point'] = function (e){
		var language = 'en';
		_latlng_btn = $(e);

		if(typeof google != 'undefined' && google.maps){
			cpm_get_latlng();
		}else{
			$('<script type="text/javascript" src="'+(( typeof window.location.protocol != 'undefined' ) ? window.location.protocol : 'http:' )+'//maps.google.com/maps/api/js?'+_api_key+'callback=cpm_get_latlng'+((language) ? '&language='+language: '')+'"></script>').appendTo('body');
		}
	};

	window['cpm_load_map'] = function(container, latitude, longitude){
		var c = container,
			f = c.parents('.point_form'),
			p = new google.maps.LatLng(latitude, longitude),
			m = new google.maps.Map(c[0], {
								zoom: 5,
								center: p,
								mapTypeId: google.maps.MapTypeId['ROADMAP'],

								// Show / Hide controls
								panControl: true,
								scaleControl: true,
								zoomControl: true,
								mapTypeControl: true,
								scrollWheel: true
						}),
			mk = new google.maps.Marker({
							  position: p,
							  map: m,
							  icon: new google.maps.MarkerImage(cpm_default_marker),
							  draggable: true
						 });

			google.maps.event.addListener(m, "click", function(e){
				var latLng = e.latLng;
				mk.setPosition(latLng);
			});

			google.maps.event.addListener(mk, 'position_changed', function(){
				f.find('#cpm_point_latitude').val(mk.getPosition().lat());
				f.find('#cpm_point_longitude').val(mk.getPosition().lng());
			});
	};

	window['cpm_set_map_flag'] = function(){
		var request = {};
		if(cpm_point['longitude'] && cpm_point['latitude']){
			request['location'] = new google.maps.LatLng(cpm_point['latitude'], cpm_point['longitude']);
		}else if(cpm_point['address']){
			request['address'] = cpm_point['address'].replace(/[\n\r]/g, '');
		}

		_get_latlng(request, function(result, status){
			if(status && status == "OK"){
				// Update fields
				var address   = result[0]['formatted_address'],
					latitude  = result[0]['geometry']['location'].lat(),
					longitude = result[0]['geometry']['location'].lng();

				if(address && latitude && longitude){
					// Load Map
					cpm_load_map($('.cpm_map_container'),latitude, longitude);
				}
			}
		});
	};

    window[ 'cpm_display_more_info' ] = function( e ){
        e = $( e );
        e.parent().hide().siblings( '.cpm_more_info' ).show();
    };

    window[ 'cpm_hide_more_info' ] = function( e ){
        e = $( e );
        e.parent().hide().siblings( '.cpm_more_info_hndl' ).show();
    };

    function enable_disable_fields(f, v){
        var p = f.parents('#map_data');
        p.find('input[type="text"]').attr({'DISABLED':v,'READONLY':v});
        p.find('select').attr({'DISABLED':v,'READONLY':v});
        p.find('input[type="checkbox"]').filter('[id!="cpm_map_single"]').attr({'DISABLED':v,'READONLY':v});
    };

	$(function(){
		// Actions for icons
		$(".cpm_icon").on('click',function(){
			var  i = $(this);
			$('.cpm_icon.cpm_selected').removeClass('cpm_selected');
			i.addClass('cpm_selected');
			$('#default_icon').val($('img', i).attr('src'));
		}).on('mouseover',function(){
			$(this).css({"border":"solid #BBBBBB 1px"})
		}).on('mouseout',function(){
			$(this).css({"border":"solid #F9F9F9 1px"})
		});

		window[ 'cpm_generate_shortcode' ] = function()
		{
			return '[codepeople-post-map]';
		};

		// Action for insert shortcode
		$('#cpm_map_shortcode').on('click',function(){
            if(window.cpm_send_to_editor_default)
                window.send_to_editor = window.cpm_send_to_editor_default;
        	if(send_to_editor){
        		send_to_editor(cpm_generate_shortcode());
			}
            var t = $('#content');
            if(t.length){
                var v= t.val()
                if(v.indexOf('codepeople-post-map') == -1)
                    t.val(v+'[codepeople-post-map]');
            }
        });

		// Create the script tag and load the maps api
		if($('.cpm_map_container').length){
            if(typeof google != 'undefined' && google.maps)
            {
                cpm_set_map_flag();
            }
            else
            {
                $('<script type="text/javascript" src="'+(( typeof window.location.protocol != 'undefined' ) ? window.location.protocol : 'http:' )+'//maps.google.com/maps/api/js?'+_api_key+'sensor=false&callback=cpm_set_map_flag"></script>').appendTo('body');
            }
		}

        $('#cpm_map_single').each(function(){
            var f = $(this);
            enable_disable_fields(f, !f[0].checked);
            f.on('click',function(){
                enable_disable_fields(f,!f[0].checked);
            });
        });

        // Show/hide map settings on single pages.
        $('#cpm_map_single').on('change',function(){
            $('.cpm-map-settings')[this.checked ? 'show' : 'hide']();
        });

	});
})(jQuery);