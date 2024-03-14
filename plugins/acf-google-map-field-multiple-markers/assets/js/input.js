(function($){

	"use strict";
	
	/**
	*  initialize_field
	*
	*  This function will initialize the $field.
	*
	*  @date	30/11/17
	*  @since	5.6.5
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function initialize_field( $field ) {
		// Nothing here yet.
	}
	
	
	if( typeof acf.add_action !== 'undefined' ) {
	
		/*
		*  ready & append (ACF5)
		*
		*  These two events are called when a field element is ready for initizliation.
		*  - ready: on page load similar to $(document).ready()
		*  - append: on new DOM elements appended via repeater field or other AJAX calls
		*
		*  @param	n/a
		*  @return	n/a
		*/
		
		acf.add_action('ready_field/type=google_map_multi', initialize_field);
		acf.add_action('append_field/type=google_map_multi', initialize_field);
		
		
	} else {
		
		/*
		*  acf/setup_fields (ACF4)
		*
		*  These single event is called when a field element is ready for initizliation.
		*
		*  @param	event		an event object. This can be ignored
		*  @param	element		An element which contains the new HTML
		*  @return	n/a
		*/
		
		$(document).on('acf/setup_fields', function(e, postbox){
			
			// find all relevant fields
			$(postbox).find('.field[data-field_type="google_map_multi"]').each(function(){
				
				// initialize
				initialize_field( $(this) );
				
			});
		
		});
	
	}

	var Field = acf.Field.extend({

		type: 'google_map_multi',

		map: false,

		wait: 'load',

		events: {
			'click a[data-name="clear"]': 		'onClickClear',
			'click a[data-name="locate"]': 		'onClickLocate',
			'click a[data-name="search"]': 		'onClickSearch',
			'keydown .search': 					'onKeydownSearch',
			'keyup .search': 					'onKeyupSearch',
			'focus .search': 					'onFocusSearch',
			'blur .search': 					'onBlurSearch',
			'showField':						'onShow',
		},

		// List of values in parsed JSON.
		// Used only for the hidden ACF field that holds the field's values.
		vals: [],

		markers: [],

		draggedMarkerIndex: '',

		$control: function(){
			return this.$('.acf-google-map-multi');
		},

		$search: function(){
			return this.$('.search');
		},

		$canvas: function(){
			return this.$('.canvas');
		},

		$markerList: function(){
			return this.$('.gmm-markers-list');
		},

		setState: function( state ){

			// Remove previous state classes.
			this.$control().removeClass( '-value -loading -searching' );

			// Determine auto state based of current value.
			if( state === 'default' ) {
				state = this.val() ? 'value' : '';
			}

			// Update state class.
			if( state ) {
				this.$control().addClass( '-' + state );
			}
		},

		getValue: function(){
			var val = this.$input().val();
			if( val ) {
				return JSON.parse( val )
			} else {
				return false;
			}
		},

		setValue: function( val, silent ){

			// Convert input value.
			var valAttr = '';
			if( val ) {
				valAttr = JSON.stringify( val );
			}

			// Update input (with change).
			acf.val( this.$input(), valAttr );

			// Bail early if silent update.
			if( silent ) {
				return;
			}

			// Render.
			this.renderVal( val );

			/**
			 * Fires immediately after the value has changed.
			 *
			 * @date	12/02/2014
			 * @since	5.0.0
			 *
			 * @param	object|string val The new value.
			 * @param	object map The Google Map isntance.
			 * @param	object field The field instance.
			 */
			acf.doAction('google_map_multi_change', val, this.map, this);
		},

		renderVal: function( val ){

			// Value.
			if( val ) {
				this.setState( 'value' );
				this.$search().val( val.address );
				// this.setPosition( val.lat, val.lng );

				// No value.
			} else {
				this.setState( '' );
				this.$search().val( '' );
				this.map.marker.setVisible( false );
			}
		},

		newLatLng: function( lat, lng ){
			return new google.maps.LatLng( parseFloat(lat), parseFloat(lng) );
		},

		initialize: function(){
			// Ensure Google API is loaded and then initialize map.
			gmmWithAPI( this.initializeMap.bind(this) );
		},

		initializeMap: function(){
			if (!gmmGeocoder) {
				gmmGeocoder = new google.maps.Geocoder();
			}

			// Get value ignoring conditional logic status.
			var val = this.getValue();

			// Construct default args.
			var args = acf.parseArgs(val, {
				zoom: this.get('zoom'),
				lat: this.get('lat'),
				lng: this.get('lng')
			});

			// Create Map.
			var mapArgs = {
				scrollwheel:	false,
				mapTypeId:		google.maps.MapTypeId.ROADMAP,
				marker:			{
					draggable: 		true,
					raiseOnDrag: 	true
				},
				autocomplete: {}
			};
			mapArgs = acf.applyFilters( 'google_map_multi_args', mapArgs, this );
			var map = new google.maps.Map( this.$canvas()[0], mapArgs );

			// Maybe Create Autocomplete.
			var autocomplete = false;
			if( acf.isset(google, 'maps', 'places', 'Autocomplete') ) {
				var autocompleteArgs = mapArgs.autocomplete || {};
				autocompleteArgs = acf.applyFilters('google_map_autocomplete_args', autocompleteArgs, this);
				autocomplete = new google.maps.places.Autocomplete( this.$search()[0], autocompleteArgs );
				autocomplete.bindTo('bounds', map);
			}

			// Add map events.
			this.addMapEvents( this, map, autocomplete );

			// Remove any residual data from our marker list. At this point, we shouldn't have anything in the list.
			this.markers = [];

			// Sets up all the markers if this field has values.
			if( val ) {
				// Create bounds.
				var bounds = new google.maps.LatLngBounds();

				this.vals = val;
				for (let i = 0; i < this.vals.length; i++) {
					var latLng = { lat: this.vals[i].lat, lng: this.vals[i].lng };

					this.markers.push({
						values: this.vals[i],
						marker: {},
					});

					this.addMarker( latLng, map );
					bounds.extend( latLng );
				}

				if (this.vals.length === 1) {
					// If we only have 1 marker, set our map's zoom and center to that marker's values.
					map.setZoom(this.vals[0].zoom);
					map.setCenter( {lat: this.vals[0].lat, lng: this.vals[0].lng} );
				}
				else {
					// Tell our map to fit the bounds defined by our markers.
					map.fitBounds( bounds );
				}

			}
			else {
				// We set the zoom and center to default values if there are no markers.
				map.setZoom(8);
				map.setCenter( {lat: 51.4934, lng: 0.0098} );

				// Reset our vals storage for the new field with no data yet.
				this.vals = [];
			}

			// Append references.
			map.acf = this;
			map.autocomplete = autocomplete;

			// Render the initial markers list under the map.
			this.renderMarkerList();

			// Set this object's map property to the map we've just created.
			// Don't change the map in this function after this line.
			this.map = map;

			/**
			 * Fires immediately after the Google Map has been initialized.
			 *
			 * @date	12/02/2014
			 * @since	5.0.0
			 *
			 * @param	object map The Google Map instance.
			 * @param	object marker The Google Map marker instance.
			 * @param	object field The field instance.
			 */
			acf.doAction('google_map_multi_init', map, this);

			// Remove the loading icon.
			$('.acf-field-google-map-multi .acf-loading').remove();
		},

		addMapEvents: function( field, map, autocomplete ){

			// Click map.
			google.maps.event.addListener( map, 'click', function( e ) {
				if (field.validateMaxPins()) {
					var lat = e.latLng.lat();
					var lng = e.latLng.lng();

					field.searchPosition( lat, lng );
					field.addMarker( e.latLng );
				}
			});

			// Autocomplete search.
			if( autocomplete ) {
				google.maps.event.addListener(autocomplete, 'place_changed', function() {
					if (field.validateMaxPins()) {
						var place = this.getPlace();
						field.searchPlace(place);

						// If places.geometry doesn't exist, it means we've had to geocode an address. Adding the marker and
						// the rest would be done in that geocode callback.
						if (place.geometry !== undefined) {
							var latLng = {lat: place.geometry.location.lat(), lng: place.geometry.location.lng()};
							field.addMarker(latLng);

							// Add the new place just searched to the bounds and fit the map to these new bounds so the map
							// expands to show the new marker that's just been added.
							map.fitBounds(field.createMapBounds(field));

							// Re-render the list of markers under the map.
							field.renderMarkerList();
						}
					}
				});
			}

			// Detect zoom change.
			google.maps.event.addListener( map, 'zoom_changed', function(){
				var val = field.val();
				if( val ) {
					val.zoom = map.getZoom();
					field.setValue( val, true );
				}
			});
		},

		/**
		 *
		 * @param latLng    latLng object.
		 * @param map       The Google Map object. If not given, it will try to use this field object's map property.
		 * @param field
		 * @returns {*}
		 */
		addMarker: function( latLng, map = this.map, field = this ) {
			let markerArgs = {
				draggable: true,
				raiseOnDrag: true,
				position: latLng,
				map: map
			};
			markerArgs = acf.applyFilters('google_map_multi_marker_args', markerArgs, field);
			let marker = new google.maps.Marker( markerArgs );

			// Add the new marker to our list of markers.
			field.markers[field.markers.length - 1].marker = marker;

			// Add a right-click event to allow users to remove this marker.
			marker.addListener('rightclick', function() {
				field.removeMarker( marker, field );
			});

			// Remove the marker from the data at the start of dragging a marker.
			marker.addListener('dragstart', function () {
				let markerIndex = field.markers.findIndex(element => marker === element.marker );
				field.removeMarkerDataFromFieldData( marker, field, markerIndex );
				field.markers.splice( markerIndex, 1 );
			});

			// Add a drag-end event to update the marker's data when it's dragged on the map.
			marker.addListener('dragend', function () {
				// TODO Try to update the existing marker that's being dragged instead of creating a new item in the list.
				field.searchPosition( marker.position.lat(), marker.position.lng() );
				field.markers[field.markers.length - 1].marker = marker;
			});

			return marker;
		},

		removeMarker: function( marker, field = this ) {
			// Remove the marker from this object's markersArray property.
			var markerIndex = field.markers.findIndex(element => marker === element.marker );

			// Check if an index was found before we remove anything.
			if (markerIndex >= 0) {
				// Remove the marker's data from the hidden field of JSON.
				field.removeMarkerDataFromFieldData(marker, field, markerIndex);
				field.markers.splice(markerIndex, 1);

				// Re-render the list of markers under the map after removing it from our values.
				this.renderMarkerList();
			}

			// Remove the marker from the map.
			marker.setMap(null);
		},

		removeMarkerDataFromFieldData: function( marker, field = this, markerIndex = false ) {
			if (!markerIndex) {
				// If the markerIndex hasn't been provided, we need to find it.
				markerIndex = field.markers.findIndex(element => marker === element.marker );
			}
			field.vals.splice(markerIndex, 1);
			field.val( this.vals );
		},

		/**
		 * Checks if the maximum number of pins has been added to the current map.
		 *
		 * @return boolean
		 */
		validateMaxPins: function() {
			// Check for no limit.
			if (this.data.maxPins <= 0) {
				return true;
			}

			// Check the limit against the current amount of markers.
			if (this.markers.length < this.data.maxPins) {
				return true;
			}
			else {
				this.showNotice({
					text: acf.__('You can\'t add anymore pins. The maximum number of pins is ' + this.data.maxPins),
					type: 'warning'
				});

				return false;
			}
		},

		/**
		 * Creates Google Map LatLngBounds for all the markers in this object's markersArray property.
		 *
		 * @param field
		 */
		createMapBounds: function( field = this ) {
			var newBounds = new google.maps.LatLngBounds();

			for ( let i = 0; i < field.markers.length; i++ ) {
				newBounds.extend( {lat: field.markers[i].marker.position.lat(), lng: field.markers[i].marker.position.lng()} );
			}

			return newBounds;
		},

		renderMarkerList: function() {
			this.$markerList().empty();
			for (let i = 0; i < this.markers.length; i++) {
				var $markerWrap = $('<div>', {
					'class': 'gmm-marker'
				}).mouseover({field: this}, function (event) {
					event.data.field.markers[i].marker.setOptions({
						icon: pluginData.pluginUrl + 'assets/images/marker-big.png',
					});
					acf.doAction('google_map_multi_marker_list_mouseover', event.data.field.markers[i], event.data.field);
				}).mouseout({field: this}, function (event) {
					event.data.field.markers[i].marker.setOptions({
						icon: null
					});
					acf.doAction('google_map_multi_marker_list_mouseout', event.data.field.markers[i], event.data.field);
				});

				var $address = $('<span>', {
					'class': 'gmm-marker--address',
				}).text(this.markers[i].values.address).appendTo($markerWrap);

				var $deleteIcon = $('<span>', {
					'class': 'gmm-marker--delete-icon dashicons dashicons-trash',
				}).click({field: this}, function (event) {
					event.data.field.removeMarker(event.data.field.markers[i].marker);
				}).appendTo($markerWrap);

				acf.doAction('google_map_multi_render_marker_list', $markerWrap, this);

				this.$markerList().append($markerWrap);
			}

			// Remove any notices from the field.
			this.removeNotice();
		},

		searchPosition: function( lat, lng ){
			// Start Loading.
			this.setState( 'loading' );

			// Set up the new marker object to contain the values and marker object.
			this.markers.push({
				values: {},
				marker: {}
			});

			// Query Geocoder.
			var latLng = { lat: lat, lng: lng };
			gmmGeocoder.geocode({ location: latLng }, function( results, status ){
				console.log('geocode callback');
				// End Loading.
				this.setState( '' );

				// Status failure.
				if( status !== 'OK' ) {
					this.showNotice({
						text: acf.__('Location not found: %s').replace('%s', status),
						type: 'warning'
					});

					// Remove the empty item we just added to this.markers.
					this.markers.splice(this.markers.length - 1, 1);

					// Re-render the list of markers under the map.
					this.renderMarkerList();

				// Success.
				} else {
					var val = this.parseResult( results[0] );

					// Override lat/lng to match user defined marker location.
					// Avoids issue where marker "snaps" to nearest result.
					val.lat = lat;
					val.lng = lng;

					// Pushing the newest place to the list of values.
					this.vals.push( val );

					this.markers[this.markers.length - 1].values = val;

					// Re-render the list of markers under the map.
					this.renderMarkerList();

					// Setting the value of the hidden field to the JSON list of markers.
					this.val( this.vals );
				}

			}.bind( this ));
		},

		searchPlace: function( place ){
			// Bail early if no place.
			if( !place ) {
				return;
			}

			// Set up the new marker object to contain the values and marker object.
			this.markers.push({
				values: {},
				marker: {}
			});

			// Selecting from the autocomplete dropdown will return a rich PlaceResult object.
			// Be sure to over-write the "formatted_address" value with the one displayed to the user for best UX.
			if( place.geometry ) {
				place.formatted_address = this.$search().val();
				var val = this.parseResult( place );

				this.markers[this.markers.length - 1].values = val;

				// Pushing the newest place to the list of values.
				this.vals.push( val );

				// Setting the value of the hidden field to the JSON list of markers.
				this.val( this.vals );

				// Searching a custom address will return an empty PlaceResult object.
			} else if( place.name ) {
				this.searchAddress( place.name );
			}
		},

		searchAddress: function( address ){
			// Bail early if no address.
			if( !address ) {
				return;
			}

			// Allow "lat,lng" search.
			var latLng = address.split(',');
			if( latLng.length == 2 ) {
				var lat = parseFloat(latLng[0]);
				var lng = parseFloat(latLng[1]);
				if( lat && lng ) {
					return this.searchPosition( lat, lng );
				}
			}

			// Start Loading.
			this.setState( 'loading' );

			// Query Geocoder.
			gmmGeocoder.geocode({ address: address }, function( results, status ){
				// End Loading.
				this.setState( '' );

				// Status failure.
				if( status !== 'OK' ) {
					this.showNotice({
						text: acf.__('Location not found: %s').replace('%s', status),
						type: 'warning'
					});

					// Success.
				} else {
					var val = this.parseResult( results[0] );

					// Override address data with parameter allowing custom address to be defined in search.
					val.address = address;

					// Pushing the newest place to the list of values.
					this.vals.push( val );

					this.markers[this.markers.length - 1].values = val;
					this.addMarker({lat: val.lat, lng: val.lng});

					// Add the new place just searched to the bounds and fit the map to these new bounds so the map
					// expands to show the new marker that's just been added.
					this.map.fitBounds( this.createMapBounds() );

					// Re-render the list of markers under the map.
					this.renderMarkerList();

					// Setting the value of the hidden field to the JSON list of markers.
					this.val( this.vals );
				}

			}.bind( this ));
		},

		searchLocation: function(){
			// Check HTML5 geolocation.
			if( !navigator.geolocation ) {
				return alert( acf.__('Sorry, this browser does not support geolocation') );
			}

			// Start Loading.
			this.setState( 'loading' );

			// Query Geolocation.
			navigator.geolocation.getCurrentPosition(

				// Success.
				function( results ){

					// End Loading.
					this.setState( '' );

					// Search position.
					var lat = results.coords.latitude;
					var lng = results.coords.longitude;
					this.searchPosition( lat, lng );

				}.bind(this),

				// Failure.
				function( error ){
					this.setState( '' );
				}.bind(this)
			);
		},

		/**
		 * parseResult
		 *
		 * Returns location data for the given GeocoderResult object.
		 *
		 * @date	15/10/19
		 * @since	5.8.6
		 *
		 * @param	object obj A GeocoderResult object.
		 * @return	object
		 */
		parseResult: function( obj ) {

			// Construct basic data.
			var result = {
				address: obj.formatted_address,
				lat: obj.geometry.location.lat(),
				lng: obj.geometry.location.lng(),
			};

			// Add zoom level.
			result.zoom = this.map.getZoom();

			// Add place ID.
			if( obj.place_id ) {
				result.place_id = obj.place_id;
			}

			// Add place name.
			if( obj.name ) {
				result.name = obj.name;
			}

			// Create search map for address component data.
			var map = {
				street_number: [ 'street_number' ],
				street_name: [ 'street_address', 'route' ],
				city: [ 'locality' ],
				state: [
					'administrative_area_level_1',
					'administrative_area_level_2',
					'administrative_area_level_3',
					'administrative_area_level_4',
					'administrative_area_level_5'
				],
				post_code: [ 'postal_code' ],
				country: [ 'country' ]
			};

			// Loop over map.
			for( var k in map ) {
				var keywords = map[ k ];

				// Loop over address components.
				for( var i = 0; i < obj.address_components.length; i++ ) {
					var component = obj.address_components[ i ];
					var component_type = component.types[0];

					// Look for matching component type.
					if( keywords.indexOf(component_type) !== -1 ) {

						// Append to result.
						result[ k ] = component.long_name;

						// Append short version.
						if( component.long_name !== component.short_name ) {
							result[ k + '_short' ] = component.short_name;
						}
					}
				}
			}

			/**
			 * Filters the parsed result.
			 *
			 * @date	18/10/19
			 * @since	5.8.6
			 *
			 * @param	object result The parsed result value.
			 * @param	object obj The GeocoderResult object.
			 */
			return acf.applyFilters('google_map_multi_result', result, obj, this.map, this);
		},

		onClickClear: function(){
			this.val( false );
		},

		onClickLocate: function(){
			this.searchLocation();
		},

		onClickSearch: function(){
			this.searchAddress( this.$search().val() );
		},

		onFocusSearch: function( e, $el ){
			this.setState( 'searching' );
		},

		onBlurSearch: function( e, $el ){

			// Get saved address value.
			var val = this.val();
			var address = val ? val.address : '';

			// Remove 'is-searching' if value has not changed.
			if( $el.val() === address ) {
				this.setState( 'default' );
			}
		},

		onKeyupSearch: function( e, $el ){

			// Clear empty value.
			if( !$el.val() ) {
				this.val( false );
			}
		},

		// Prevent form from submitting.
		onKeydownSearch: function( e, $el ){
			if( e.which == 13 ) {
				e.preventDefault();
				$el.blur();
			}
		},

		// Center map once made visible.
		onShow: function(){
			if( this.map ) {
				this.setTimeout( this.center );
			}
		},
	});

	acf.registerFieldType( Field );

	// Vars.
	var gmmLoading = false;
	var gmmGeocoder = false;

	/**
	 * gmmWithAPI
	 *
	 * Loads the Google Maps API library and triggers callback.
	 *
	 * @date	28/3/19
	 * @since	5.7.14
	 *
	 * @param	function callback The callback to excecute.
	 * @return	void
	 */

	function gmmWithAPI( callback ) {
		// Check if geocoder exists.
		if( gmmGeocoder ) {
			return callback();
		}

		// Check if geocoder API exists.
		if( acf.isset(window, 'google', 'maps', 'Geocoder') ) {
			gmmGeocoder = new google.maps.Geocoder();
			return callback();
		}

		// Check if we're trying to load 1 or more Google Map fields. We need to execute our callback differently
		// depending on this.
		let otherMapFields = acf.findFields({
			type: 'google_map'
		});

		// Check if the google_map_api_loaded action has been added.
		if ( !otherMapFields.length ) {
			// In this case, the there's no other Google Map field used, meaning we need to load the maps API ourselves.
			acf.addAction( 'google_map_multi_api_loaded', callback );

			// Bail early if already loading API.
			if( gmmLoading ) {
				return;
			}

			// load api
			var url = acf.get('google_map_api');
			if( url ) {

				// Set loading status.
				gmmLoading = true;

				// Load API
				$.ajax({
					url: url,
					dataType: 'script',
					cache: true,
					success: function() {
						gmmGeocoder = new google.maps.Geocoder();
						acf.doAction('google_map_multi_api_loaded');
					}
				});
			}
		}
		else {
			// The Maps API will be loaded by the original Google Map field.
			// All we need to do is add our callback to the the action used in the original Google Map field so it gets
			// triggered together with that.
			acf.addAction( 'google_map_api_loaded', callback );
		}
	}

})(jQuery);
