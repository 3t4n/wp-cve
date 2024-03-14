/*!
 * venomaps admin scripts
 *
 * Copyright 2020 Nicola Franchini
 */
 jQuery(document).ready(function($){
	'use strict';

	/*
	 * Load media library
	 */
	var om_metaImageFrame;
	$(document).on('click', '.venomaps_marker_upload_btn', function(e){
		var field = $( this ).prev('.venomaps_custom_marker-wrap').find( '.venomaps_custom_marker' );
		e.preventDefault();
		om_metaImageFrame = wp.media.frames.om_metaImageFrame = wp.media();
		om_metaImageFrame.on('select', function() {
			var media_attachment = om_metaImageFrame.state().get('selection').first().toJSON();
			$( field ).val(media_attachment.url);
		});
		om_metaImageFrame.open();
	});

	$(document).on('click', '.venomaps_marker_remove_btn', function(e){
		var field = $( this ).parent().find( '.venomaps_custom_marker' );
		e.preventDefault();
		$( field ).val('');
	});

	/*
	 * Initialize wysiwyg editor
	 */
	var om_editor_settings = {
	    tinymce: true,
	    quicktags: {
	        'buttons': 'strong,em,link,ul,ol,li,del,close'
	    }
	}
	$('.venomaps_marker_editor').each(function(){
		var editorid = $(this).find('textarea').attr('id');
		wp.editor.initialize( editorid, om_editor_settings );
	});

	/*
	 * Clone marker box
	 */
	$('.wpol-new-marker').on('click', function(){

		if ($('.wrap-clone').length) {
			var clone = $('.wrap-clone').last().find('.clone-marker').clone();
		} else {
			var clone = $('.clone-marker').last().clone();
		}

		var cloneindex = parseFloat(clone.data('index'));
		var cloneindexnew = cloneindex + 1;

		var wrapclone = '<div class="wrap-clone" id="wrap-clone-'+cloneindexnew+'"><strong class="wpol-badge"> #'+cloneindexnew+'</strong></div>';

		clone.attr('data-index', cloneindexnew);
		clone.find('input').attr('value', '');
		clone.find('option:selected').removeAttr('selected');
		clone.find('input:checked').prop('checked', false);

		clone.find('input').each(function() {
		    this.name = this.name.replace('['+cloneindex+']', '['+cloneindexnew+']');
		});

		$(wrapclone).appendTo('.wrap-marker');

		clone.appendTo('#wrap-clone-'+cloneindexnew);

		var text_editor = '<div class="wp-editor-container venomaps_marker_editor"><textarea id="venomaps_infobox_'+cloneindexnew+'" name="venomaps_marker['+cloneindexnew+'][infobox]" class="wp-editor-area" rows="4"></textarea></div>';
		$(text_editor).appendTo('#wrap-clone-'+cloneindexnew);

		wp.editor.initialize( 'venomaps_infobox_'+cloneindexnew, om_editor_settings );

		var removebtn = '<div class="wpol-remove-marker wpol-btn-link"><span class="dashicons dashicons-no"></span></div>';
		$(removebtn).appendTo('#wrap-clone-'+cloneindexnew);

	});

	$(document).on('click', '.wpol-remove-marker', function(){
		$(this).parent('.wrap-clone').remove();
	});

	/*
	 * Geolocation
	 */
	function loadGeolocator(geomap_id, geomarker_id) {

		var timer;
		var latinput = $('.venomaps-get-lat');
		var loninput = $('.venomaps-get-lon');

        if (typeof ol === 'undefined' || ol === null) {
          console.log('WARNING: OpenLayers Library not loaded');
          return false;
        }

		var om_map_pos = ol.proj.fromLonLat([-74.005974, 40.712776]);
		var view = new ol.View({
			  center: om_map_pos,
			  zoom: 2
			});

		// Init map
		var map = new ol.Map({
			target: 'wpol-admin-map',
			view: view,
			layers: [
			  new ol.layer.Tile({
			    source: new ol.source.OSM()
			  })
			],
			interactions: ol.interaction.defaults.defaults({mouseWheelZoom:false})
		});

	    // Add Marker
	    var marker_el = document.getElementById(geomarker_id);
	    var infomarker = new ol.Overlay({
			position: om_map_pos,
			positioning: 'center-center',
			// offset: [0, -20],
			element: marker_el,
			stopEvent: false,
			dragging: false
	    });
	    map.addOverlay(infomarker);

	    // Update marker position and lat lon fields

		// map.on('click', function(evt) {
		// 	var coordinate = evt.coordinate;
		// 	var lonlat = ol.proj.toLonLat(coordinate);
		// 	$('.venomaps-get-lat').val(lonlat[1]);
		// 	$('.venomaps-get-lon').val(lonlat[0]);
		// 	infomarker.setPosition(coordinate);
		// });

		var dragPan;
		map.getInteractions().forEach(function(interaction){
			if (interaction instanceof ol.interaction.DragPan) {
				dragPan = interaction;  
		  }
		});

		marker_el.addEventListener('mousedown', function(evt) {
		  dragPan.setActive(false);
		  infomarker.set('dragging', true);
		});

		map.on('pointermove', function(evt) {
			if (infomarker.get('dragging') === true) {
		  	infomarker.setPosition(evt.coordinate);
		  }
		});

		map.on('pointerup', function(evt) {
			if (infomarker.get('dragging') === true) {
		    dragPan.setActive(true);
		    infomarker.set('dragging', false);
			var coordinate = evt.coordinate;
			var lonlat = ol.proj.toLonLat(coordinate);
			$('.venomaps-get-lat').val(lonlat[1]);
			$('.venomaps-get-lon').val(lonlat[0]);
		  }
		});

		// Update lat lon fields
		function georesponse(response){
			var lat, lon;
			$('.venomaps-response').html('');
			if (response[0]) {
				lat = response[0].lat;
				lon = response[0].lon;
				var newcoord = ol.proj.fromLonLat([lon, lat]);
				infomarker.setPosition(newcoord);
				view.setCenter(newcoord);
				view.setZoom(6);
			} else {
				lat = '';
				lon = '';
				$('.venomaps-response').html('Nothing Found');
			}
			$('.venomaps-get-lat').val(lat);
			$('.venomaps-get-lon').val(lon);
		}

		// Get coordinates from Address.
		$('.venomaps-get-coordinates').on('click', function(){

			var button = $(this);
			var address = $('.venomaps-set-address').val()

			if ( address.length > 3 ) {
				button.hide();
				var encoded = encodeURIComponent(address);
				$.ajax({
			        url: 'https://nominatim.openstreetmap.org/search?q='+encoded+'&format=json',
			        type: 'GET',
			    }).done(function(res) {
				    georesponse(res);
				})
				.fail(function() {
				    button.fadeIn();
				})
				.always(function() {
				    button.fadeIn();
				});
			}
		});

		$('.venomaps-get-lat, .venomaps-get-lon').on('input', function(){
			clearTimeout(timer);
			timer = setTimeout(function(){
				var lat = latinput.val();
				var lon = loninput.val();
				var newcoord = ol.proj.fromLonLat([lon, lat]);
				infomarker.setPosition(newcoord);
				view.setCenter(newcoord);
				view.setZoom(6);
			}, 1000);
		});
	}

	if ( $('#wpol-admin-map').length && $('#infomarker_admin').length ) {
		loadGeolocator( 'wpol-admin-map', 'infomarker_admin' );
	}

	/*
	 * Options page repeatable items
	 */
	$('.wpol-call-repeat').on('click', function(){
		var repgroup = $('.wpol-repeatable-group');
		var lastitemnum = $('.wpol-repeatable-item').last().data('number');
		var newnum = parseFloat(lastitemnum) + 1;
		var newitem = '<div class="wpol-repeatable-item wpol-form-group" data-number="'+newnum+'"><input type="text" class="all-options" name="venomaps_settings[style]['+newnum+'][name]" value="map style '+newnum+'"> <input type="url" class="regular-text" name="venomaps_settings[style]['+newnum+'][url]" value="" placeholder="https://provider.ext/{z}/{x}/{y}.png?api_key=..."></div>';
		$('.wpol-repeatable-group').append(newitem);
	});

	/*
	 * Disable submission with enter key
	 */
	function omDisableEnterKey(evt) {
		var evt = (evt) ? evt : ((event) ? event : null);
		var elem = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
		if ((evt.keyCode == 13) && (elem.type =='text' || elem.type =='url' || elem.type =='number'))  {
			return false;
		}
	}
	document.onkeypress = omDisableEnterKey;
});
