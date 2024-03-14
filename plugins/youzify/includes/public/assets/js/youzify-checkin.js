(function ($) {

    'use strict';

        var marker, map_dropdown,placesService,autocompleteService,map, mapOptions= {
            zoom: 16,
            center: new google.maps.LatLng(50, 50),panControl: false,
            zoomControl: false,
            mapTypeControl: false,
            scaleControl: false,
            streetViewControl: false,
            overviewMapControl: false,
            rotateControl: false,
            fullscreenControl: false,
            disableDefaultUI: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

    $( document ).ready( function () {

        // Init Auto Complete Services.
        autocompleteService = new google.maps.places.AutocompleteService();

        $( document).on( 'click', '.youzify-checkin-close-icon', function() {

            let button = $( this ),
                form = button.closest( '.youzify-checkin-form' );

            form.fadeOut( 200, function() {

                // Clear Fields.
                form.find( 'input' ).val( '' );

                // Clear Old Searches.
                form.find( '.youzify-wall-checkin-list' ).html( '' );

            });


        });

        $( document).on( 'click', '.youzify-remove-map', function() {

            let form = $( this ).closest( '.youzify-checkin-form' );

            // Hide GeoMap Content.
            form.find( '.youzify-geomap-content' ).fadeOut( 200, function() {

                // Hide Map
                form.find( '.youzify-geomap' ).html( '' );

                // Clear Search
                form.find( '.youzify-checkin-close-icon' ).click();

                // Restore Search Form.
                form.find( '.youzify-checkin-search-box,.youzify-wall-checkin-list' ).fadeIn();

            });

        });

        $( document).on( 'click', '.youzify-select-location', function() {

            let button = $( this ),
                item = button.closest( '.youzify-list-item' ),
                form = button.closest( '.youzify-checkin-form' ),
                placeId = item.attr( 'data-place_id' ),
                label = item.attr( 'data-label' );

            // Init Map
            map = new google.maps.Map( form.find('.youzify-geomap' )[0], mapOptions);

            // Init Places Services
            placesService = new google.maps.places.PlacesService(map);

            placesService.getDetails( { placeId: placeId }, function(place, status) {

            if (status === google.maps.places.PlacesServiceStatus.OK) {

                var center = place.geometry.location,
                    marker = new google.maps.Marker({
                        position: center,
                        map: map
                    });

                map.setCenter(center);

                }
            });

            form.find( 'input[name="checkin_place_id"]' ).val( placeId );
            form.find( 'input[name="checkin_label"]' ).val( label );

            // Hide Search & Lists.
            form.find( '.youzify-checkin-search-box,.youzify-wall-checkin-list' ).fadeOut( 200, function() {
                form.find( '.youzify-geomap-content' ).fadeIn();
            });


        });

        $( document).on( 'click', '.youzify-check-in-tool', function() {

            //load google map
            initialize( $( this ).closest( 'form' ) );

        });

        $( document).on( 'input', '.youzify-checkin-search-input', function() {

            let search_term = $( this ).val(),
                form =  $( this ).closest( '.youzify-checkin-form' );
                map_dropdown = form.find( '.youzify-wall-checkin-list' );

            if ( $.trim( search_term )  == '' ) {
                map_dropdown.html( '' );
                return;
            }

            youzify_map_autocomplete_options['input'] = search_term;

            autocompleteService.getPlacePredictions( youzify_map_autocomplete_options, youzify_getPlacePredictions_callback );

        });

        // Place search callback
        function youzify_getPlacePredictions_callback( predictions, status ) {

          // Empty results container
          map_dropdown.html( '' );

          // Place service status error
          // if (status != google.maps.places.PlacesServiceStatus.OK) {
          //   map_dropdown.append( '<div class="pac-item pac-item-error">Your search returned no result. Status: ' + status + '</div>' );
          //   return;
          // }

          // Build output for each prediction
          for (var i = 0, prediction; prediction = predictions[i]; i++) {
            map_dropdown.append( '<div class="youzify-list-item youzify-geo-item" data-label="' + prediction.description + '" data-place_id="' + prediction.place_id + '" > <div class="youzify-item-icon"><i class="fas fa-map-marker-alt"></i></div> <div class="youzify-item-content"> <div class="youzify-item-left"> <div href=">" class="youzify-item-title">' + prediction.description +'</div></div> <div class="youzify-item-right"> <div class="youzify-item-button youzify-select-location"><i class="fas fa-chevron-right"></i></div> </div> </div> </div>' );
          }

        }

    });

})( jQuery );