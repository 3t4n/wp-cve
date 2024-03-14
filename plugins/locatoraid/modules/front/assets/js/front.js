// (function($) {
var lctr = {};

var locatoraidEvent = function( eventName, payload ){
	var event;

// console.log( eventName );

	if( document.createEvent ){
		event = document.createEvent("HTMLEvents");
		event.initEvent( eventName, true, true );
	}
	else {
		event = document.createEventObject();
		event.eventType = eventName;
	}
	event.eventName = eventName;
	event.payload = payload;

	if( document.createEvent ){
		document.dispatchEvent( event );
	}
	else {
		document.fireEvent( "on" + event.eventType, event );
	}
}

var locatoraidFront = (function($) {
var self = this;
var search_coordinates;
var markerCluster;

var observers = function()
{
	var observers = [];

	this.add = function( item )
	{
		observers.push( item );
	}
	this.notify = function( what, payload )
	{
		new locatoraidEvent( 'locatoraid-' + what, payload );
		for( var ii = 0; ii < observers.length; ii++ ){
			observers[ii].trigger( what, payload );
		}
	}
}

// document.addEventListener('DOMContentLoaded', function(){
	// jQuery('#hclc_search_form').on( 'submit', function(){
		// var searchString = jQuery(this).find('input[name=hc-search]').val();
		// console.log( searchString );
	// });
// });


// this.form = function( html_id )
this.form = function( $this )
{
	var html_id = $this.attr('id');
	// alert( html_id );
	// var $this = jQuery( '#' + html_id );

	var self = this;
	this.observers = new observers;

	this.next_links = [];
	this.add_my_bias = true;

	this.more_results = function()
	{
		hc2_set_loader( $this );
		var next_link = self.next_links.shift();
		if( next_link ){
			self.do_search( next_link, '' );
		}
		else {
			hc2_unset_loader( $this );
		}
	}

	this.more_results_link = $this.find('.hcj2-more-results');
	this.more_results_link.on('click', function(e){
		self.more_results();
	});

	this.radius_search = function( search )
	{
		hc2_set_loader( $this );
		self.next_links = [];

		var search_url = $this.data('radius-link');

		var search_string = search.search;
		search_string = search_string + '';

		var knowCoord = { lat: '', lng: '' };
		if( search.hasOwnProperty('locatelat') ){
			knowCoord.lat = search.locatelat;
			knowCoord.lng = search.locatelng;
		}

		for( var k in search ){
			var to_replace = '_' + k.toUpperCase() + '_';
			var replace_to = search[k];
			if( Array.isArray(replace_to) ){
				replace_to = replace_to.join('_');
			}
			search_url = search_url.replace( to_replace, replace_to );
		}

		if( search.hasOwnProperty('country') ){
			search_string = search_string.length ? search_string + ', ' + search.country : search.country;
		}

		var where = $this.data('where');
		if( where && self.add_my_bias && search_string.length ){
			search_string = search_string + ' ' + where;
		}

// console.log( search_url );
// return false;

		if( knowCoord.lat && knowCoord.lng ){
			search_url = search_url.replace( '_LAT_', knowCoord.lat );
			search_url = search_url.replace( '_LNG_', knowCoord.lng );

			self.observers.notify( 'get-search', [knowCoord.lat, knowCoord.lng, '-autolocate-'] );
			self.do_radius_search( search_url );
		}
		else if( ! search_string.length ){
			search_url = search_url.replace( '_LAT_', '' );
			search_url = search_url.replace( '_LNG_', '' );

			self.do_radius_search( search_url );
		}
		else {
		// now try to geocode the search
			var try_this = {
				'address': search_string
			};
			if( hc2_lc_front_vars['search_bias_country'] ){
				var search_bias_country = "" + hc2_lc_front_vars['search_bias_country'];
				if( search_bias_country.includes(',') ){
				}
				else {
					try_this['componentRestrictions'] = {
						country: search_bias_country,
					};
				}
			}

			hc2_geocode(
				try_this,
				function( success, results, return_status )
				{
					if( success ){
						search_url = search_url.replace( '_LAT_', results.lat );
						search_url = search_url.replace( '_LNG_', results.lng );

						self.observers.notify( 'get-search', [results.lat, results.lng, search_string] );
					}
					else {
						search_url = search_url.replace( '_LAT_', '' );
						search_url = search_url.replace( '_LNG_', '' );
					}
					self.do_radius_search( search_url );
				}
			);
		}
	}

	this.do_radius_search = function( search_url )
	{
		console.log( search_url );

		jQuery.ajax({
			type: 'GET',
			url: search_url,
			dataType: "json",
			success: function(data, textStatus){
				// hc2_unset_loader( $this );

			// search links
				if( data.length ){
					for( var ii = 0; ii < data.length; ii++ ){
						var this_link = data[ii];
						self.next_links.push( this_link[0] );
					}
				}

				var next_link = self.next_links.shift();
				if( next_link ){
					self.do_search( next_link, '' );
				}
				else {
					self.observers.notify( 'get-results', {} );
					hc2_unset_loader( $this );
				}
			}
			})
			.fail( function(jqXHR, textStatus, errorThrown){
				hc2_unset_loader( $this );
				alert( 'Ajax Error' );
				console.log( 'Ajax Error: ' + errorThrown + "\n" + jqXHR.responseText );
				})
			;
	}

	this.search = function( search )
	{
		hc2_set_loader( $this );
		var search_string = search.search;
		search_string = search_string + '';
		var search_url = $this.attr('action');

		var knowCoord = { lat: '', lng: '' };
		if( search.hasOwnProperty('locatelat') ){
			knowCoord.lat = search.locatelat;
			knowCoord.lng = search.locatelng;
		}

		for( var k in search ){
			var to_replace = '_' + k.toUpperCase() + '_';
			var replace_to = search[k];
			if( Array.isArray(replace_to) ){
				replace_to = replace_to.join('_');
			}
			search_url = search_url.replace( to_replace, replace_to );

			if( 'product' == k ){
				search_url += encodeURIComponent( '/product2/' + replace_to );
			}

			if( 'radius' == k ){
				search_url += encodeURIComponent( '/radius/' + replace_to );
			}
		}

		// search_url = search_url.replace( '_SEARCH_', search_string );

		if( search.hasOwnProperty('country') ){
			search_string = search_string.length ? search_string + ', ' + search.country : search.country;
		}

		var where = $this.data('where');
		if( where && self.add_my_bias && search_string.length ){
			search_string = search_string + ' ' + where;
		}

// console.log( search );
// console.log( search_url );
// return;

		if( knowCoord.lat && knowCoord.lng ){
			search_url = search_url.replace( '_LAT_', knowCoord.lat );
			search_url = search_url.replace( '_LNG_', knowCoord.lng );

			self.observers.notify( 'get-search', [knowCoord.lat, knowCoord.lng, '-autolocate-'] );
			self.do_search( search_url, search_string );
		}
		else if( ! search_string.length ){
			search_url = search_url.replace( '_LAT_', '' );
			search_url = search_url.replace( '_LNG_', '' );

			self.do_search( search_url, search_string );
		}
		else {
		// now try to geocode the search
			var try_this = {
				'address': search_string
			};
			if( hc2_lc_front_vars['search_bias_country'] ){
				var search_bias_country = "" + hc2_lc_front_vars['search_bias_country'];
				if( search_bias_country.includes(',') ){
				}
				else {
					try_this['componentRestrictions'] = {
						country: search_bias_country,
					};
				}
			}

			hc2_geocode(
				try_this,
				function( success, results, return_status )
				{
					if( success ){
						search_url = search_url.replace( '_LAT_', results.lat );
						search_url = search_url.replace( '_LNG_', results.lng );

						self.observers.notify( 'get-search', [results.lat, results.lng, search_string] );
					}
					else {
						search_url = search_url.replace( '_LAT_', '' );
						search_url = search_url.replace( '_LNG_', '' );
					}
					self.do_search( search_url, search_string );
				}
			);
		}
	}

	this.do_search = function( search_url, search_string )
	{
		console.log( search_url );

		jQuery.ajax({
			type: 'GET',
			url: search_url,
			// dataType: "json",

			success: function(data, textStatus){
				data = data.replace( /\\ \/\>/g, "\/>" );

				var ok_data = hc2_try_parse_json( data );
				if( ok_data ){
					hc2_unset_loader( $this );

					if( ok_data.announce ){
						var $no_results_view = ok_data.announce;

						var $more_results_link = jQuery('.hcj2-more-results');
						$more_results_link.html('');
						$more_results_link.hide();

						var $container = jQuery('#locatoraid-map-list-container');
						$container.html( $no_results_view );
					}
					else {
						var real_coord = ok_data.search_coordinates;
						if( real_coord.length ){
							var pass_coord = [real_coord[0], real_coord[1], search_string];
						}
						else {
							var pass_coord = [];
						}

						self.observers.notify( 'get-search', pass_coord );
						self.observers.notify( 'get-results', ok_data );

					// more results link
						if( self.next_links.length ){
							self.more_results_link.show();
						}
						else {
							self.more_results_link.hide();
						}
					}
				}
				else {
					hc2_unset_loader( $this );
					alert( 'Ajax Error' );
					console.log( 'Ajax Error: ' + 'json parse error' + "\n" + data );
				}
			}
			})

			.fail( function(jqXHR, textStatus, errorThrown){
				hc2_unset_loader( $this );
				alert( 'Ajax Error' );
				console.log( 'Ajax Error: ' + errorThrown + "\n" + jqXHR.responseText );
				})
			;
	}

	this.submit = function( event )
	{
		event.stopPropagation();
		event.preventDefault();

		this_data = {};
		var this_form_array = $this.find('select, textarea, input, checkbox').serializeArray();

		for( var ii = 0; ii < this_form_array.length; ii++ ){
			var name = this_form_array[ii]['name'];
			name = name.substr(3); // strip 'hc-'
			if( name.substr(-2) == '[]' ){
				name = name.substr(0, name.length-2);
				if( ! this_data[name] ){
					this_data[name] = [];
				}
				this_data[name].push( this_form_array[ii]['value'] );
			}
			else {
				this_data[name] = this_form_array[ii]['value'];
			}
		}
		// self.resetLocateMe();

		if( this_data.hasOwnProperty('radius') ){
			self.search( this_data );
		}
		else {
			var radius_search_url = $this.data('radius-link');
			var search_string = this_data.search;
			search_string = search_string + '';

			if( (search_string.length || self.coordInputs.lat.val() )&& radius_search_url.length ){
				self.radius_search( this_data );
			}
			else {
				self.search( this_data );
			}
		}
	}

	$this.on('submit', this.submit );
	$this.find("input[type='checkbox']").on('change', this.submit );
	$this.find("input[type='radio']").on('change', this.submit );
	$this.find("select").on('change', this.submit );

	// var default_search = $this.find('input[name=hc-search]').val();
	var where = $this.data('where');
	var start = $this.data('start');
	var autoLocate = $this.data('locate');

	// if( default_search || where ){
	if( where || (start != null) ){
		var radius_search_url = $this.data('radius-link');
		start = "" + start;
		if( start.length && radius_search_url.length ){
			this.radius_search( {'search': start} );
		}
		else {
			this.search( {'search': start} );
		}
	}

/* LOCATE ME */
	this.coordInputs = {};
	this.coordInputs.lat = $this.find('input[name=hc-locatelat]');
	this.coordInputs.lng = $this.find('input[name=hc-locatelng]');

	this.resetLocateMe = function(){
		self.coordInputs.lat.val( '' );
		self.coordInputs.lng.val( '' );
	}

	this.locateMe = function(){
		var geoTimeout = 10000;

		hc2_set_loader( $this );
		setTimeout( function(){
			hc2_unset_loader( $this );
			}, geoTimeout );

		navigator.geolocation.getCurrentPosition(
			function(position)
			{
				hc2_unset_loader( $this );
// console.log( position );
// console.log( 'found ' + position.coords.latitude + ',' + position.coords.longitude );

				self.coordInputs.lat.val( position.coords.latitude );
				self.coordInputs.lng.val( position.coords.longitude );

				$this.trigger( 'submit' );

			// hide form
				jQuery( '#locatoraid-search-form-button' ).hide();

				var $searchInput = $this.find('input[name=hc-search]');
				$searchInput.prop( 'disabled', true );

				var label = jQuery( '#locatoraid-search-form-my-location' ).html();
				$searchInput.val( label );

				jQuery( '#locatoraid-search-form-locate-me' ).hide();
				jQuery( '#locatoraid-search-form-reset-location' ).show();
			},
			function( error )
			{
				var err_msg = 'Sorry your device could not get your location';
				switch( error.code )
				{
					case error.PERMISSION_DENIED:
						err_msg = "User denied the request for Geolocation.";
						break;
					case error.POSITION_UNAVAILABLE:
						err_msg = "Location information is unavailable.";
						break;
					case error.TIMEOUT:
						err_msg = "";
						break;
					case error.UNKNOWN_ERROR:
						err_msg = "An unknown error occurred.";
						break;
				}
				hc2_unset_loader( $this );
				if( err_msg ) {
					alert( err_msg );
				}
			},
			{
//				enableHighAccuracy: true, 
//				maximumAge        : 30000, 
				enableHighAccuracy	:false,
				timeout				: geoTimeout,
			}
		);

		return false;
	}

	this.locateMeLink = $this.find('.hcj2-locate-me');
	if( ! navigator.geolocation ){
		this.locateMeLink.hide();
	}

	this.locateMeLink.on('click', function(e){
		self.locateMe();
		return false;
	});

	if( 'auto' == autoLocate ){
		self.locateMe();
	}

	this.resetMyLocation = $this.find('.hcj2-reset-my-location');
	this.resetMyLocation.on('click', function(e){
		self.resetLocateMe();

	// show form
		jQuery( '#locatoraid-search-form-button' ).show();

		var $searchInput = $this.find('input[name=hc-search]');
		$searchInput.prop( 'disabled', false );
		$searchInput.val( '' );

		jQuery( '#locatoraid-search-form-locate-me' ).show();
		jQuery( '#locatoraid-search-form-reset-location' ).hide();

		return false;
	});
}

// this.list = function( html_id )
this.list = function( $this )
{
	var html_id = $this.attr('id');
	// var $this = jQuery( '#' + html_id );
	var self = this;
	this.observers = new observers;

	this.params = {
		'group'	:	$this.data('group'),
		'groupJump'	:	$this.data('group-jump'),
		'sort' 	:	$this.data('sort')
	};

	self.template = jQuery( '#' + html_id + '_template' ).html();
// decode
	var elem = document.createElement('textarea');
	elem.innerHTML = self.template;
	self.template = elem.value;

	self.template_no_results = jQuery( '#' + html_id + '_template_no_results' ).html();
	elem.innerHTML = self.template_no_results;
	self.template_no_results = elem.value;

	var toStrip = ['/*<![CDATA[*/', '/*]]>*/', '/*]]&gt;*/' ];
	for( var ii = 0; ii < toStrip.length; ii++ ){
		if( self.template ){
			self.template = self.template.replace( toStrip[ii], '' );
		}
		if( self.template_no_results ){
			self.template_no_results = self.template_no_results.replace( toStrip[ii], '' );
		}
	}

	this.entries = {};

	this.trigger = function( what, payload )
	{
		if( ! $this.length ){
			return;
		}

		switch( what ){
			case 'get-results':
				this.render( payload );
				break;
			case 'select-location':
				this.highlight( payload );
				this.scroll_to( payload );
				break;
		}
	}

	this.render = function( results )
	{
		if( ! $this.is(":visible") ){
			$this.show();
		}
		$this.scrollTop(0);

		self.entries = {};

		var entries = [];
		if( results.hasOwnProperty('results') ){
			entries = results['results'];
		}

		$this.html('');

		var group_by = this.params['group'];
		var showJump = this.params['groupJump'] ? true : false;
		var groups = {};

		if( group_by ){
			for( var ii = 0; ii < entries.length; ii++ ){
				var this_loc = entries[ii];
				var this_group_label = this_loc[group_by];
				if( ! groups.hasOwnProperty(this_group_label) ){
					groups[this_group_label] = [];
				}
				groups[this_group_label].push(ii);
			}
		}
		else {
			var this_group_label = '';
			groups[this_group_label] = [];
			for( var ii = 0; ii < entries.length; ii++ ){
				groups[this_group_label].push(ii);
			}
		}

		var group_labels = Object.keys( groups );

	// if have empty label then it should come last
		var fullCount = group_labels.length;
		group_labels = group_labels.filter( function(a){
			return (a.length > 0);
		});
		var realCount = group_labels.length;

		group_labels.sort(function(a, b){
			return a.localeCompare(b);
		})

		if( fullCount > realCount ){
			group_labels.push( '' );
		}

		if( showJump && (group_labels.length > 1) ){
			var groupSelector = '';

			groupSelector += '<select id="locatoraid-list-group" onchange="document.getElementById(\'' + html_id + '\').scrollTop = document.getElementById(this.value).offsetTop">';
			groupSelector += '<option value="locatoraid-list-group">' + ' &darr; ' + '</option>';

			for( var kk = 0; kk < group_labels.length; kk++ ){
				var groupLabel = group_labels[kk];
				if( groupLabel.length ){
					groupSelector += '<option value="locatoraid-list-group-' + kk + '">' + groupLabel + '</option>';
				}
			}
			groupSelector += '</select>';

			$this.append( groupSelector );
		}

		for( var kk = 0; kk < group_labels.length; kk++ ){
			var group_label = group_labels[kk];

			if( group_label.length ){
				if( showJump ){
					var groupLabelView = '<h4 id="locatoraid-list-group-' + kk + '">' + '<a href="#" onclick="document.getElementById(\'' + html_id + '\').scrollTop=0; return false;">&uarr; ' + group_label + '</a>' + '</h4>';
				}
				else {
					var groupLabelView = '<h4 id="locatoraid-list-group-' + kk + '">' + group_label + '</h4>';
				}
				$this.append( groupLabelView );
			}

			for( var jj = 0; jj < groups[group_label].length; jj++ ){
				var ii = groups[group_label][jj];
				var this_loc = entries[ii];

				var template = new Hc2Template( self.template );
				var template_vars = this_loc;
				var this_loc_view = template.render(template_vars);

				// var $this_loc_view = jQuery( this_loc_view );
				var $thisLocView = jQuery('<div>').html( this_loc_view );
				$thisLocView
					.data( 'location-id', this_loc['id'] )
					.data( 'location', this_loc )
					;

				self.entries[ this_loc['id'] ] = $thisLocView;
				$this.append( $thisLocView );

				$thisLocView.on('click', function(e){
					var location_id = jQuery(this).data('location-id');
					self.highlight( location_id );
					self.observers.notify( 'select-location', location_id );
				});

				$thisLocView.render = function(){
				};

				new locatoraidEvent( 'locatoraid-render-in-list', $thisLocView );
			}
		}

		if( ! entries.length ){
			if( results.announce ){
				var $no_results_view = results.announce;

				var $more_results_link = jQuery('.hcj2-more-results');
				$more_results_link.html('');
				$more_results_link.hide();

				var $container = jQuery('#locatoraid-map-list-container');
				$container.html( results.announce );
			}
			else {
				var no_results_view = self.template_no_results;
				var $no_results_view = jQuery('<div>').html( no_results_view );
			}
			$this.append( $no_results_view );
		}
	}

	this.scroll_to = function( id )
	{

		var ids = Array.isArray(id) ? id : [id];
		var thisId = ids[0];

		var $container = self.entries[ thisId ];
		var new_top = $this.scrollTop() + $container.position().top;
		$this.scrollTop( new_top );
	}

	this.highlight = function( id )
	{
		var hl_class = 'hc-outlined';

		for( var iid in self.entries ){
			self.entries[iid].removeClass( hl_class );
		}

		var ids = Array.isArray(id) ? id : [id];
		for( var ii = 0; ii < ids.length; ii++ ){
			var container = self.entries[ids[ii]];
			container.addClass( hl_class );
		}
	}
}

this.map = function( $this )
// this.map = function( html_id )
{
	var html_id = $this.attr('id');
	// var $this = jQuery( '#' + html_id );
	var self = this;
	this.observers = new observers;
	$this.hide();

	self.template = jQuery( '#' + html_id + '_template' ).html();

// decode
	var elem = document.createElement('textarea');
	elem.innerHTML = self.template;
	self.template = elem.value;

	var toStrip = ['/*<![CDATA[*/', '/*]]>*/', '/*]]&gt;*/' ];
	for( var ii = 0; ii < toStrip.length; ii++ ){
		if( self.template ){
			self.template = self.template.replace( toStrip[ii], '' );
		}
	}

	this.markers = {};
	this.markersByPosition = {};
	this.entries = {};
	$this.map = null;

	var maxZoom = $this.data( 'max-zoom' );
	if( typeof maxZoom !== 'undefined' ){
		this.max_zoom = maxZoom;
	}
	else {
		this.max_zoom = 14;
	}
	this.max_zoom_no_entries = 4;

	this.infowindow = new google.maps.InfoWindow({
		});

	this.trigger = function( what, payload )
	{
		if( ! $this.length ){
			return;
		}

		switch( what ){
			case 'get-search':
				this.render_search( payload );
				break;
			case 'get-results':
				this.render( payload );
				break;
			case 'select-location':
				this.render_info( payload );
				break;
		}
	}

	this.init_map = function( html_id )
	{
		if( ! this.map ){
			this.map = hc2_init_gmaps( html_id );
			jQuery(document).trigger('hc2-lc-map-init', this.map);
		}
	}

	this.render_search = function( coord )
	{
		if( ! $this.is(":visible") ){
			$this.show();
		}
		this.init_map( html_id );

		for( var id in this.markers ){
			this.markers[id].setMap(null);
		}
		this.markers = {};

		if( coord.length > 1 ){
			search_coordinates = new google.maps.LatLng(coord[0], coord[1]);

			var searched_marker = new google.maps.Marker({
				position: search_coordinates,
				icon: {
					path: google.maps.SymbolPath.CIRCLE,
					scale: 6
					},
				// icon: "//maps.google.com/mapfiles/arrow.png",
				draggable: false,
				map: this.map,
				title: coord[2],
			});

			self.markers[-1] = searched_marker;
		}

		// this.map.setCenter( search_coordinates );
		// if( this.map.getZoom() > this.max_zoom_no_entries ){
			// this.map.setZoom(this.max_zoom_no_entries);
		// }
		// this.map.fitBounds( bound );
		// this.map.setZoom(6);
	}

	this.render = function( results )
	{
		if( ! $this.is(":visible") ){
			$this.show();
		}
		this.init_map( html_id );

		this.markersByPosition = {};
		for( var id in this.markers ){
			if( id > 0 ){
				this.markers[id].setMap(null);
			}
		}

		var entries = [];
		if( results.hasOwnProperty('results') ){
			entries = results['results'];
		}

		for( var ii = 0; ii < entries.length; ii++ ){
			var id = entries[ii]['id'];
			self.entries[id] = entries[ii];
		}

		var $map = jQuery( '#' + html_id );
		var hideLocTitle = $map.data( 'hide-loc-title' ) ? true : false;

	// place locations on map
		for( var ii = 0; ii < entries.length; ii++ ){
			var this_loc = entries[ii];
			var id = entries[ii]['id'];

			var location_position = new google.maps.LatLng( this_loc['latitude'], this_loc['longitude'] );
			var positionIndex = this_loc['latitude'] + '_' + this_loc['longitude'];

			if( self.markersByPosition.hasOwnProperty(positionIndex) ){
				var location_marker = self.markersByPosition[ positionIndex ];
				location_marker.locationId.push( id );
				if( ! hideLocTitle ){
					location_marker.title += ", " + this_loc['name'];
				}
				location_marker.setLabel( '' + location_marker.locationId.length );
			}
			else {
				var location_marker = new google.maps.Marker( {
					map: self.map,
					position: location_position,
					title: hideLocTitle ? null : this_loc['name'],
					draggable: false,
					visible: true,
					animation: google.maps.Animation.DROP,
					locationId: [],
					});

				location_marker.locationId.push( id );
				self.markersByPosition[ positionIndex ] = location_marker;
			}

			if( typeof locatoraidMapIcon !== 'undefined' ){
				location_marker.setIcon( locatoraidMapIcon );
			}
			else if( this_loc['mapicon'] && this_loc['mapicon'].length ){
				location_marker.setIcon( this_loc['mapicon'] );
			}
			else {
				if( hc2_gmaps_vars.hasOwnProperty('icon') && hc2_gmaps_vars['icon'] ){
					location_marker.setIcon( hc2_gmaps_vars['icon'] );
				}
			}

			location_marker.addListener( 'click', function(){
				self.render_info( this.locationId );
				self.observers.notify( 'select-location', this.locationId );
			});

			self.markers[id] = location_marker;
		}

	// cluster if needed
		if( typeof hc2_gmapsclusterer_vars !== 'undefined' ){
			if( typeof markerCluster !== 'undefined' ){
				markerCluster.clearMarkers();
			}
			var minCount = hc2_gmapsclusterer_vars['count'];
			if( entries.length >= minCount ){
				var clusterImgPath = hc2_gmapsclusterer_vars['img_path'];
				var clusterStyles = [];
				if( typeof locatoraidClusterStyles !== 'undefined' ){
					clusterStyles = locatoraidClusterStyles;
					// var locatoraidClusterStyles = [
						// {
							// url: 'http://localhost/testclustering/m1.png',
							// height: 35,
							// width: 35,
							// textColor: '#fff',
						// },
						// {
							// url: 'http://localhost/testclustering/m1.png',
							// height: 50,
							// width: 50,
							// textColor: '#fff',
						// },
					// ];
				}

				markerCluster = new MarkerClusterer( self.map, self.markers,
					{ imagePath: clusterImgPath, styles: clusterStyles }
				);
			}
		}

		var startMapZoom = $map.data('start-zoom');

	// zoom map accordingly
		var bound = new google.maps.LatLngBounds();
		for( var id in this.markers ){
			bound.extend( this.markers[id].position );
		}

		if( entries.length ){
			if( entries.length > 1 ){
			// just starting?
				if( (typeof startMapZoom !== 'undefined') && (typeof search_coordinates == 'undefined') ){
					this.map.setZoom( startMapZoom );
				}
				else {
					this.map.fitBounds( bound );
				}
			}
			else {
				if( typeof search_coordinates !== 'undefined' ){
					bound.extend( search_coordinates );
					this.map.fitBounds( bound );
				}
			}
		}

		this.map.setCenter( bound.getCenter() );

	// prepare zoom
		var current_zoom = this.map.getZoom();

		if( entries.length ){
			if( (entries.length > 1) || (typeof search_coordinates !== 'undefined')  ){
				if( current_zoom > this.max_zoom ){
// console.log( 'RESET ZOOM FROM ' + current_zoom + ' TO ' + this.max_zoom );
					this.map.setZoom(this.max_zoom);
				}
				else {
					// alert( 'reset zoom: ' + current_zoom);
					// bound = new google.maps.LatLngBounds(null);
					// this.map.setZoom( current_zoom + 1 );
				}
			}
			else {
				var $map = jQuery( '#' + html_id );
				if( typeof startMapZoom !== 'undefined' ){
					this.map.setZoom( startMapZoom );
				}
				else {
					this.map.setZoom( this.max_zoom );
				}
			}
		}
		else {
			if( current_zoom > this.max_zoom_no_entries ){
				this.map.setZoom(this.max_zoom_no_entries);
			}
		}
	}

	this.map_start = function( search_string, zoom )
	{
		if( ! $this.is(":visible") ){
			$this.show();
		}
		this.init_map( html_id );

	// try to geocode
		var map = this.map;
		var try_this = {
			'address': search_string
		};
		if( hc2_lc_front_vars['search_bias_country'] ){
			var search_bias_country = "" + hc2_lc_front_vars['search_bias_country'];
			if( search_bias_country.includes(',') ){
			}
			else {
				try_this['componentRestrictions'] = {
					country: search_bias_country,
				};
			}
		}

		hc2_geocode(
			try_this,
			function( success, results, return_status )
			{
				if( success ){
					var center_coord = new google.maps.LatLng( results.lat, results.lng );
					self.map.setCenter( center_coord );
				}
				self.map.setZoom( zoom );
			}
		);
	}

	this.render_info = function( thisId )
	{
		var template = new Hc2Template( self.template );

		if( Array.isArray(thisId) ){
			var thisMarker;
			var thisLocsView = [];

			for( var ii = 0; ii < thisId.length; ii++ ){
				var thisThisId = thisId[ ii ];
				var thisLoc = self.entries[ thisThisId ];

				var positionIndex = thisLoc['latitude'] + '_' + thisLoc['longitude'];
				thisMarker = self.markersByPosition[ positionIndex ];

				var templateVars = thisLoc;
				var thisLocView = template.render( templateVars );

				var $thisLocView = jQuery('<div>').html( thisLocView );
				$thisLocView
					.data( 'location-id', thisThisId )
					.data( 'location', thisLoc )
					;

				$thisLocView.render = function(){
					var locsView = [];
					for( var jj = 0; jj < thisLocsView.length; jj++ ){
						locsView.push( thisLocsView[jj].html() );
					}
					locsView = locsView.join('');
					self.infowindow.setContent( locsView );
				};

				thisLocsView.push( $thisLocView );
				// thisLocsView.push( thisLocView );
				new locatoraidEvent( 'locatoraid-render-on-map', $thisLocView );
			}

			// thisLocsView = thisLocsView.join('');
			// this.infowindow.setContent( thisLocsView );
			$thisLocView.render();
			this.infowindow.open( self.map, thisMarker );
		}
		else {
			var thisMarker = self.markers[ thisId ];

			var thisLoc = self.entries[ thisId ];
			var templateVars = thisLoc;
			var thisLocView = template.render( templateVars );

			var $thisLocView = jQuery('<div>').html( thisLocView );
			$thisLocView
				.data( 'location-id', thisId )
				.data( 'location', thisLoc )
				;

			$thisLocView.render = function(){
				self.infowindow.setContent( $thisLocView.html() );
			}

			$thisLocView.render();

			// this.infowindow.setContent( $thisLocView );
			// this.infowindow.setContent( thisLocView );

			this.infowindow.open( self.map, thisMarker );
			new locatoraidEvent( 'locatoraid-render-on-map', $thisLocView );
		}
	}

	new locatoraidEvent( 'locatoraid-map-init', self );
	jQuery(document).trigger( 'locatoraid-map-init', self );
}

jQuery(document).on('hc2-gmaps-loaded', function()
{
	var ii = 0;
	var forms = jQuery( '.hclc_search_form_class' );
	var lists = jQuery( '.hclc_list_class' );
	var maps = jQuery( '.hclc_map_class' );

	for( ii = 0; ii < forms.length; ii++ ){
		var form = new self.form( jQuery(forms[ii]) );
		var list = new self.list( jQuery(lists[ii]) );
		var map = new self.map( jQuery(maps[ii]) );

		form.observers.add( map );
		form.observers.add( list );

		list.observers.add( map );
		map.observers.add( list );

		var $map = jQuery( jQuery(maps[ii]) );
		var start_map_address = $map.data('start-address');
		if( start_map_address ){
			var start_map_zoom = $map.data('start-zoom');
			if( ! start_map_zoom ){
				start_map_zoom = 5;
			}
			map.map_start( start_map_address, start_map_zoom );
		}
	}
});

// }());
}).bind(lctr);
locatoraidFront();