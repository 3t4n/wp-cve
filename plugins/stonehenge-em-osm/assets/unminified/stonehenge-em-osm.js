jQuery.noConflict();
(function(jQuery) {
jQuery(document).ready(function(){
	// Settings Page.
	if( jQuery('#osm_per_location_no').length > 0 ) {
		if( jQuery('#osm_per_location_no').is(':checked') ) {
			jQuery('.osm-per-admin').hide();
		}
		else {
			jQuery('.osm-per-admin').show();
		}
	}
		jQuery('[name="stonehenge_em_osm[per_location]"]').click(function() {
			jQuery('.osm-per-admin').toggle(500);
		});

	jQuery('#apply_filters .stonehenge-section-content').toggle();

// Edit Location Page.
	if( jQuery('#location_marker_color option:selected').index() == 9 ) {
		jQuery('#location_marker_color').attr('disabled', true);
	}
	if( jQuery('#location_map_type option:selected').index() == 13 ) {
		jQuery('#location_map_type').attr('disabled', true);
	}

	jQuery('#location_marker_color').change( function() {
		// Change the marker.
		selectedMarker = jQuery('#location_marker_color option:selected').val();
		if( selectedMarker.length == 0 ) {
			selectedMarker = OSM.defaultMarker;
		}
		thisIcon = new LeafIcon({iconUrl: pluginUrl + selectedMarker + '.png'});
		marker.setLatLng( [Lat,Lng] ).setIcon( thisIcon ).addTo(map).bindPopup(balloon).openPopup();
	}).trigger('focusout');

	jQuery('#location_map_type').change( function() {
		// Change the MapTile.
		selectedMapTile = jQuery('#location_map_type option:selected').val();
		if( selectedMapTile.length == 0 ) {
			selectedMapTile = OSM.defaultMap;
		}
		new L.tileLayer( selectedMapTile ).addTo(map);
	}).trigger('focusout');


// Location Select
	if( jQuery('#location-select-id').length > 0 ) {
		marker.dragging.disable();
		jQuery('#location-select-id').change( function() {
			var	LocID 		= jQuery("#location-select-id option:selected").val(),
				Coords	 	= jQuery("#location-select-id option:selected").attr('title').split(","),
				Lat 		= Coords[0],
				Lng 		= Coords[1],
				balloon 	= jQuery("#location-select-id option:selected").attr('balloon');

			jQuery('#location-id').val( LocID );
			jQuery('#location-latitude').val( Lat );
			jQuery('#location-longitude').val( Lng );

			getMapTile 	= jQuery("#location-select-id option:selected").attr('data-maptile');

			thisMapTile = (getMapTile.length > 0 ) ? getMapTile : OSM.defaultMap;

			jQuery('#location-map').val( thisMapTile );

			thisMarker 	= jQuery('#location-select-id option:selected').attr('data-marker');
			jQuery('#location-marker').val( thisMarker );

			// Change marker.
			if( thisMarker.indexOf("|") >= 0 ) {
				customMarker = thisMarker.split('|');
				thisIcon = L.ExtraMarkers.icon({
					shape: customMarker[0],
					markerColor: customMarker[1],
					icon: customMarker[2],
					iconColor: customMarker[3],
					prefix: customMarker[4]
				});
			} else {
				thisIcon = new LeafIcon({iconUrl: pluginUrl + thisMarker + '.png'});
			}

			if( LocID === '0' ) {
				new L.tileLayer( OSM.defaultMap ).addTo(map);
				marker.setLatLng( [0,0] ).setIcon( thisIcon ).bindPopup(balloon).openPopup();
				map.setView( [0,0], 1 );
			}
			else {
				new L.tileLayer( thisMapTile ).addTo(map);
				marker.setLatLng( [Lat,Lng] ).setIcon( thisIcon ).addTo(map).bindPopup(balloon).openPopup();
				map.setView( [Lat,Lng], zoomLevel );
			}
		}).trigger('change');
	}

// Edit Event Page.
	// Form Fields.
	if( jQuery('.location-form-where input#location-name').length > 0 ) {
		// Disable on Pageload?
		if( jQuery('input#location-id').val() != '0' && jQuery('input#location-id').val() != '' ) {
			jQuery('#osm-location-table input, #osm-location-table select').not(':input[type=button]').attr('disabled', true);
			jQuery('#osm-button').hide();
			jQuery('#osm-location-reset').show();

			// Pin down the marker.
			marker.dragging.disable();
		}
		else {
			// No location set, so reset map.
			new L.tileLayer( OSM.defaultMap ).addTo(map);

			if( OSM.defaultMarker.indexOf("|") >= 0 ) {
				customMarker = OSM.defaultMarker.split('|');
				thisIcon = L.ExtraMarkers.icon({
					shape: customMarker[0],
					markerColor: customMarker[1],
					icon: customMarker[2],
					iconColor: customMarker[3],
					prefix: customMarker[4]
				});
			} else { thisIcon = new LeafIcon({iconUrl: pluginUrl + OSM.defaultMarker + '.png', }); }


			marker.setLatLng([0,0]).setIcon( thisIcon ).bindPopup(balloon).closePopup();
			map.setView([0,0],1);

			// Make marker draggable.
			marker.dragging.enable();
			marker.on('dragend', function(e) {
				jQuery('#location-latitude').val( marker.getLatLng().lat );
				jQuery('#location-longitude').val( marker.getLatLng().lng );
			});
		}

		// OnClick Reset Form.
		jQuery('#osm-location-reset a').click( function(){
			jQuery('#location-id, #location-latitude, #location-longitude, #location-marker, #location-map').val('');
			jQuery('#osm-location-table input, #osm-location-table select').not(':input[type=button]').attr('disabled', false).val('');
			jQuery('#osm-button').show(150);
			jQuery('#osm-location-reset').hide(150);

			// Reset map.
			new L.tileLayer( OSM.defaultMap ).addTo(map);

			if( OSM.defaultMarker.indexOf("|") >= 0 ) {
				customMarker = OSM.defaultMarker.split('|');
				thisIcon = L.ExtraMarkers.icon({
					shape: customMarker[0],
					markerColor: customMarker[1],
					icon: customMarker[2],
					iconColor: customMarker[3],
					prefix: customMarker[4]
				});
			} else { thisIcon = new LeafIcon({iconUrl: pluginUrl + OSM.defaultMarker + '.png', }); }
			marker.setLatLng([0,0]).setIcon( thisIcon ).bindPopup(' ').closePopup();
			map.setView([0,0],1);

			// Make marker draggable.
			marker.dragging.enable();
			marker.on('dragend', function(e) {
				jQuery('#location-latitude').val( marker.getLatLng().lat );
				jQuery('#location-longitude').val( marker.getLatLng().lng );
			});
			return false;
		});

		// Autocomplete Ajax Search.
		jQuery('.location-form-where input#location-name').autocomplete({
			source: OSM.AutoComplete,
			delay: 500,
			minLength: 3,
			focus: function( event, ui ) {
				jQuery('input#location-id').val( ui.item.value );
				return false;
			},
			select: function( event, ui ) {
				var	LocID 		= ui.item.id,
					Lat 		= ui.item.latitude,
					Lng 		= ui.item.longitude,
					balloon 	= '<b>'+ ui.item.value +'</b><br>'+ ui.item.address +', '+ ui.item.town;

				jQuery('#location-id').val(LocID);
				jQuery('#location-latitude').val(Lat);
				jQuery('#location-longitude').val(Lng);
				jQuery("#location-name" ).val(ui.item.value);
				jQuery('#location-address').val(ui.item.address);
				jQuery('#location-town').val(ui.item.town);
				jQuery('#location-state').val(ui.item.state);
				jQuery('#location-region').val(ui.item.region);
				jQuery('#location-postcode').val(ui.item.postcode);
				jQuery('#location-country').val(ui.item.country);
				jQuery('#osm-location-table input, #osm-location-table select').not(':input[type=button]').attr('disabled', true);
				jQuery('#osm-button').hide();
				jQuery('#osm-location-reset').show();

				thisMarker = ui.item.marker;
				thisMaptile = ui.item.maptype;

				// Always fill.
				jQuery('#location-marker').val( thisMarker );
				jQuery('#location-map').val( thisMaptile );

				// Conditional fill => Marker
				if( thisMarker == OSM.defaultMarker ) {
					jQuery('location_marker_color').val( ui.item.marker );
				}

				else if( thisMarker.indexOf("|") >= 0 ) {
					jQuery('#location_marker_color').append(new Option("Filter Applied",thisMarker)).val( thisMarker) ;
				}

				else {
					jQuery('#location_marker_color').val( thisMarker );
				}

				// Change marker.
				if( thisMarker.indexOf("|") >= 0 ) {
					customMarker = thisMarker.split('|');
					thisIcon = L.ExtraMarkers.icon({
						shape: customMarker[0],
						markerColor: customMarker[1],
						icon: customMarker[2],
						iconColor: customMarker[3],
						prefix: customMarker[4]
					});
				} else {
					thisIcon = new LeafIcon({iconUrl: pluginUrl + thisMarker + '.png'});
				}
				marker.setLatLng( [Lat,Lng] ).setIcon( thisIcon ).addTo(map).bindPopup(balloon).openPopup();

				// Conditional fill => MapTiles
				if( ui.item.maptype === OSM.defaultMap ) {
					jQuery('#location_map_type').val('');

				} else {
					mapUrl = ui.item.maptype;
					jQuery('#location_map_type').append(new Option("Filter Applied", mapUrl));
					jQuery('#location_map_type').val(ui.item.maptype);
				}

				// Change the map tile server.
				new L.tileLayer( mapUrl ).addTo(map);
				// Move the Map.
				map.setView( [Lat,Lng], zoomLevel );
				// Pin down the marker.
				marker.dragging.disable();
				return false;
			}
		}).data('ui-autocomplete')._renderItem = function( ul, item ) {
			html_val = "<a>" + em_esc_attr(item.label) + '<br><span style="font-size:12px"><em>'+ em_esc_attr(item.address) + ', ' + em_esc_attr(item.town)+"</em></span></a>";
			return jQuery( "<li></li>" ).data( "item.autocomplete", item ).append(html_val).appendTo( ul );
		};
	}
});
})
(jQuery);

	// OSM OpenCage API Search
	function apiSearch() {
		geoAddress( jQuery('#location-address').val() +', '+ jQuery('#location-town').val() +', '+ jQuery('#location-postcode').val() +', '+ jQuery('#location-state').val() );
	}

	function geoAddress(query) {
		jQuery.ajax({
			url: 'https://api.opencagedata.com/geocode/v1/json',
			method: 'GET',
			data: {
				'key': OSM.apiKey,
				'q': query,
				'no_annotations': 1,
				'add_request': 1,
				'pretty': 1,
				'language': OSM.locale,
			},
			dataType: 'json',
			statusCode: {
				200: function(response) {
					var result = response.results[0];
					console.log(result);
					var formatted = result.formatted;
					var components = result['components']
					if( components.city ) 		{ jQuery('#location-town').val( components.city );}
					if( components.town ) 		{ jQuery('#location-town').val( components.town );}
					if( components.village ) 	{ jQuery('#location-town').val( components.village );}
					if( components.state ) 		{ jQuery('#location-state').val( components.state );}
					if( components.county ) 	{ jQuery('#location-region').val( components.county );}
					if( components.postcode ) 	{ jQuery('#location-postcode').val( components.postcode );}
					if( components.country_code ) {
					var countryCode = components.country_code.toUpperCase();
						jQuery('#location-country').val( countryCode );
					}
					var newLat = result['geometry']['lat'];
					var newLng = result['geometry']['lng'];
					var newLatLng = new L.LatLng(newLat, newLng);
					jQuery('#location-latitude').val( newLat );
					jQuery('#location-longitude').val( newLng );

					// Move map.
					marker.setLatLng(newLatLng).bindPopup(formatted).openPopup();
					map.setView(newLatLng, zoomLevel);

					marker.dragging.enable();
					marker.on('dragend', function(e) {
						jQuery('#location-latitude').val( marker.getLatLng().lat );
						jQuery('#location-longitude').val( marker.getLatLng().lng );
					});
				},
				402: function() {
					console.log('hit free-trial daily limit');
					console.log('become a customer: https://opencagedata.com/pricing');
				}
			}
		});
	}
