jQuery( function( $ ) {

    // initilize map
    function setupMap() {
        var gmarkers = []; // To store all the markers
        // Initializing Map
        let lat = 40.731;
        let lng = -73.997;
        if( $( "#em_lat" ).length > 0 ) {
            if( $( "#em_lat" ).val() ) {
                lat = parseFloat( $( "#em_lat" ).val() );
            }
        }
        if( $( "#em_lng" ).length > 0 ) {
            if( $( "#em_lng" ).val() ) {
                lng = parseFloat( $( "#em_lng" ).val() );
            }
        }
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 8,
            center: {lat: lat, lng: lng}
        });
        if( $( '#em_zoom_level' ).length > 0 ) {
            if( $( '#em_zoom_level' ).val() ) {
                map.setZoom( parseInt( $( '#em_zoom_level' ).val() ) );
            }
        }
        var addressEl = $( "#em-pac-input" );
        input = document.getElementById('em-pac-input'); //Searchbox
        var types = document.getElementById('type-selector');
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);
        
        var geocoder = new google.maps.Geocoder;
        var infowindow = new google.maps.InfoWindow;
        var autocomplete = new google.maps.places.SearchBox(input);
        autocomplete.bindTo('bounds', map);
        var marker = new google.maps.Marker({
            map: map,
            anchorPoint: new google.maps.Point(0, -29)
        });
        if(input.value != ''){
            google.maps.event.trigger(autocomplete, 'places_changed');
        }

        // Listener for searchbox changes.
        autocomplete.addListener('places_changed', function () {
            //resetMarkers();
            var places = autocomplete.getPlaces();
            if(places.length == 0)
                return;
            var place = places[0];
            if (!place.geometry) {
                return;
            }

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                //map.setZoom(8);  // Why 17? Because it looks good.
            }
            var marker = new google.maps.Marker({
                position: place.geometry.location,
                map: map
            });
            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }
            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map,marker);
            gmarkers.push(marker);
            //updateLatLngInput(place.geometry.location.lat(),place.geometry.location.lng(),map.getZoom());
            updateLatLngInput(place, map);
        });
    }

    function updateLatLngInput(place, map){
        let lat = place.geometry.location.lat();
        let lang = place.geometry.location.lng();
        let zoom_level = map.getZoom();
        let place_id = place.place_id;
        let state = '', country = '', postal_code = '';
        $("#em_lat").val( lat );
        $("#em_lng").val( lang );
        $("#em_zoom_level").val( zoom_level );
        $("#em_place_id").val( place_id );
        let address_components = place.address_components;
        for( let i = 0; i < address_components.length; i++ ){
            let atype = address_components[i].types;
            if( atype.indexOf('locality') > -1 ){
                locality = address_components[i].long_name;
                $("#em_locality").val( locality );
            } else if( atype.indexOf('postal_town') > -1 ){
                locality = address_components[i].long_name;
                $("#em_locality").val( locality );
            }
            if( atype.indexOf('administrative_area_level_1') > -1 ){
                state = address_components[i].long_name;
                $("#em_state").val( state );
            }
            if( atype.indexOf('country') > -1 ){
                country = address_components[i].long_name;
                $("#em_country").val( country );
            }
            if( atype.indexOf('postal_code') > -1 ){
                postal_code = address_components[i].long_name;
                $("#em_postal_code").val( postal_code );
            }
        }
    }

    // fire on upload image button
    var file_frame;
    $( document ).on( 'click', '.upload_image_button', function( event ) {
        event.preventDefault();
        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.open();
            return;
        }
        // Create the media frame.
        file_frame = wp.media.frames.downloadable_file = wp.media({
            title: em_venue_object.media_title,
            button: {
                text: em_venue_object.media_button
            },
            multiple: true
        });
        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            attachments = file_frame.state().get('selection');
            attachment_ids = [];
            attachments.map(function (attachment) {
                attachment = attachment.toJSON();
                var imageObj = attachment.sizes.thumbnail === undefined ? {src: [attachment.sizes.full.url], id: attachment.id} : {src: [attachment.sizes.thumbnail.url], id: attachment.id};
                if( imageObj ) {
                    let imageSrc = imageObj.src[0];
                    let imageHtml = '<span class="ep-venue-gallery"><i class="remove-gallery-venue dashicons dashicons-trash ep-text-danger"></i><img src="'+imageSrc+'" data-image_id="'+attachment.id+'"></span>';
                    $( '#ep-venue-admin-image' ).append(imageHtml);
                }
                // Pushing attachment ID in model
                attachment_ids.push(attachment.id);
            });
            $( '#ep_venue_image_id' ).val( attachment_ids );
        });
        // Finally, open the modal.
        file_frame.open();
    });

    // remove image
    $( document ).on( 'click', '.remove_image_button', function(){
        $( '#ep-venue-admin-image' ).html('');
        $( '#ep_venue_image_id' ).val( '' );
    });
    $( document ).on( 'click', '.remove-gallery-venue', function(){
        var image_id = $(this).parent().find('img').data('image_id').toString();
        var gallery_ids = $('#ep_venue_image_id').val();
        var galleryArr  = gallery_ids.split(',');
        for( var i = 0; i < galleryArr.length; i++){ 
            if ( galleryArr[i] === image_id) { 
                galleryArr.splice(i, 1); 
            }
        }
        gallery_ids = galleryArr.toString();
        $('#ep_venue_image_id').val(gallery_ids);
        $(this).parent().remove();
        
    });
    // fire event on ajax complete
    $( document ).ajaxComplete( function( event, request, options ) {
        if ( request && 4 === request.readyState && 200 === request.status
            && options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

            var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
            if ( ! res || res.errors ) {
                return;
            }
            // Clear Thumbnail fields on submit
            $( '#ep-venue-admin-image' ).html('');
            $( '#ep_venue_image_id' ).val( '' );
            $( '#is_featured' ).prop( 'checked', false );
            return;
        }
    } );

    // reset the establishment value
    $( document ).on( 'click', '#em_venue_esh_reset', function() {
        $( '#em_established' ).val('');
    });

    $( document ).ready( function() {
        if( eventprime.global_settings.gmap_api_key ) {
            setupMap();
        }

        // established datepicker
        $( '#em_established' ).datepicker({
            changeYear: true,
            changeMonth: true,
            dateFormat: 'yy-mm-dd',
            gotoCurrent: true,
            yearRange: "-300:+0",
            maxDate: new Date,
        });
    });
});