'use strict';

(function( $ ) {

	/**
     * Init map.
	 */
	const initMap = () => {
		document.querySelectorAll( '.acadp-map:not(.acadp-map-loaded)' ).forEach(( mapEl ) => {		
			createMap( mapEl );
		});
	}

	/**
     * Create map.
	 */
	const createMap = ( mapEl ) => {		
		mapEl.classList.add( 'acadp-map-loaded' );

		// Vars
		const markersEl = mapEl.querySelectorAll( '.marker' );

		// Create map
		const options = {
			zoom: parseInt( acadp.zoom_level ),
			center: new google.maps.LatLng( 0, 0 ),
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			zoomControl: true,
			scrollwheel: false
		}

		let map = new google.maps.Map( mapEl, options );

		// Set map type.
		map.type = mapEl.dataset.type;		
	
		// Add markers.				
		map.markers = [];

		markersEl.forEach(( markerEl ) => {										   
			addMarker( markerEl, map );			
		});

		// Center map.
		if ( map.type === 'archive' ) {
			// Try HTML5 geolocation.
			if ( acadp.snap_to_user_location && navigator.geolocation ) {
				navigator.geolocation.getCurrentPosition(function( position ) {
				  	map.setCenter({
						lat: position.coords.latitude,
						lng: position.coords.longitude
					});
				}, function() {
					centerMap( map );
				});
			} else {
				// Browser doesn't support Geolocation.
				centerMap( map );
			}			
		} else {
			centerMap( map );
		}

		// MarkerClusterer
		if ( map.type === 'archive' ) {
			new MarkerClusterer( map, map.markers, { imagePath: acadp.plugin_url + 'vendor/markerclusterer/images/m' } );
		}
		
		// Update map when the contact fields are edited.
		if ( map.type === 'form' ) {
			const contactDetailsEl = document.querySelector( '#acadp-panel-contact-details' );			
			const geoCoder = new google.maps.Geocoder();
			
			const onAddressChange = () => {
				let query = [];
				
				let address = document.querySelector( '#acadp-form-control-address' ).value;
				if ( address ) {
					address = address.replace( /(?:\r\n|\r|\n)/g, ',' );
					address = address.replace( ',,', ',' );
					address = address.replace( ', ', ',' );

					query.push( address );
				}

				const regionEl = contactDetailsEl.querySelector( 'input[name=acadp_location]:checked' );
				if ( regionEl !== null ) {
					const region = regionEl.closest( '.acadp-term-label' ).querySelector( '.acadp-term-name' ).innerHTML;
					query.push( region );
				}				

				const zipcode = document.querySelector( '#acadp-form-control-zipcode' ).value;
				if ( zipcode ) {
					query.push( zipcode );
				}

				query = query.filter( function( v ) { return v !== '' } );
				query = query.join();
			
				geoCoder.geocode({ 'address': query }, function( results, status ) {															
					if ( status == google.maps.GeocoderStatus.OK ) {						
						const point = results[0].geometry.location;
	
						map.markers[0].setPosition( point );
						centerMap( map );
						updateLatLng( point.lat(), point.lng() );						
					}				
				});
			}
			
			$( contactDetailsEl ).on( 'blur', '.acadp-form-control-map', function() {
				onAddressChange();
			});
				
			if ( markersEl.length > 0 ) {
				const latitude  = markersEl[0].dataset.latitude;
				const longitude = markersEl[0].dataset.longitude;

				if ( ! latitude || ! longitude ) {
					onAddressChange();
				}
			}
		}
	}	
	
	/**
	 * Add Marker.
	 */
	const addMarker = ( markerEl, map ) => {
		// Vars
		let latLng = new google.maps.LatLng( markerEl.dataset.latitude, markerEl.dataset.longitude );

		// Check to see if any of the existing markers match the latlng of the new marker.
		if ( map.markers.length ) {
    		for ( let i = 0; i < map.markers.length; i++ ) {
        		let existingMarker = map.markers[ i ];
        		let position = existingMarker.getPosition();

        		// If a marker already exists in the same position as this marker.
        		if ( latLng.equals( position ) ) {
            		// update the position of the coincident marker by applying a small multipler to its coordinates.
            		let latitude  = latLng.lat() + ( Math.random() - 0.5 ) / 1500; // * (Math.random() * (max - min) + min);
            		let longitude = latLng.lng() + ( Math.random() - 0.5 ) / 1500; // * (Math.random() * (max - min) + min);
            		
					latLng = new google.maps.LatLng( latitude, longitude );
        		}
    		}
		}

		// Create marker.
		const marker = new google.maps.Marker({
			position: latLng,
			map: map,
			draggable: ( map.type === 'form' ) ? true : false
		});

		// Add to array.
		map.markers.push( marker );

		// If marker contains HTML, add it to an infoWindow.
		if ( markerEl.innerHTML ) {
			// Create info window.
			let infowindow = new google.maps.InfoWindow({
				content: markerEl.innerHTML
			});

			// Show info window when marker is clicked.
			google.maps.event.addListener( marker, 'click', function() {	
				infowindow.open( map, marker );
			});
		}
		
		// Update latitude and longitude values when the marker is moved.
		if ( map.type === 'form' ) {
			google.maps.event.addListener( marker, 'dragend', function() {																  
				const point = marker.getPosition();

				map.panTo( point );
				updateLatLng( point.lat(), point.lng() );			
			});	
		}
	}

	/**
	 * Center the map.
	 */
	const centerMap = ( map ) => {
		// Vars
		let bounds = new google.maps.LatLngBounds();

		// Loop through all markers and create bounds.
		map.markers.forEach(( marker ) => {
			const latLng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );
			bounds.extend( latLng );
		});

		if ( map.markers.length === 1 ) {			
			// Set center of map.
	    	map.setCenter( bounds.getCenter() );
	    	map.setZoom( parseInt( acadp.zoom_level ) );			
		} else {			
			// Fit to bounds.
			map.fitBounds( bounds );			
		}
	}
	
	/**
	 * Update the latitude and longitude field values.
	 */
	const updateLatLng = ( latitude, longitude ) => {		
		document.querySelector( '#acadp-form-control-latitude' ).value  = latitude;
		document.querySelector( '#acadp-form-control-longitude' ).value = longitude;
	}	
	
	/**
	 * Called when the page has loaded.
	 */
	$(function() {	

		// Init map.
		if ( acadp.show_cookie_consent ) {
			document.addEventListener( 'acadp.cookie.consent', initMap );
		} else {
			initMap();			
		}
		
		// Gutenberg: Refresh map.
		if ( 'undefined' !== typeof wp && 'undefined' !== typeof wp['hooks'] ) {
			let intervalHandler;
			let retryCount;

			const loadMapBlock = () => {
				if ( retryCount > 0 ) {
					clearInterval( intervalHandler );
				}	

				retryCount = 0;

				intervalHandler = setInterval(() => {
					retryCount++;

					if ( document.querySelectorAll( '.acadp-map:not(.acadp-map-loaded)' ) !== null || retryCount >= 10 ) {
						clearInterval( intervalHandler );
						retryCount = 0;

						initMap();
					}
				}, 1000	);
			}

			wp.hooks.addAction( 'acadp_init_listings', 'acadp/listings', function( attributes ) {
				if ( 'map' === attributes.view ) {
					loadMapBlock();
				}
			});

			wp.hooks.addAction( 'acadp_init_listing_form', 'acadp/listing-form', function() {
				loadMapBlock();
			});
		}
		
	});

})( jQuery );
