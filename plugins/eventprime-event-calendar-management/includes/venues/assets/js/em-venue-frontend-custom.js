jQuery( function( $ ) {

    $(document).on('click','#ep-loadmore-event-venues',function(e){
        var max_page = $('#ep-loadmore-event-venues').data('max');
        var paged = $('#ep-venues-paged').val();
        var display_style = $('#ep-venues-style').val();
        var limit = $('#ep-venues-limit').val();
        var cols = $('#ep-venues-cols').val();
        var featured = $('#ep-venues-featured').val();
        var popular = $('#ep-venues-popular').val();
        var search = $('#ep-venues-search').val();
        var box_color = $('#ep-venues-box-color').val();
        var formData = new FormData();
        formData.append('action', 'ep_load_more_event_venue');
        formData.append('paged', paged);
        formData.append('display_style', display_style);
        formData.append('limit', limit);
        formData.append('cols', cols);
        formData.append('featured',featured);
        formData.append('popular',popular);
        formData.append('search',search);
        formData.append('box_color',box_color);
        if($('#ep_keyword').length && $('#ep_keyword').val() !=''){
            formData.append('keyword', $('#ep_keyword').val());
            formData.append('ep_search', true);
        }
        $('.ep-spinner').addClass('ep-is-active');
        $("#ep-loadmore-event-venue").attr("disabled", true);
        $('.ep-register-response').html();
        $.ajax({
            type : "POST",
            url : ep_frontend.ajaxurl,
            data: formData,
            contentType: false,
            processData: false,       
            success: function(response) {
                $('.ep-spinner').removeClass('ep-is-active');
                $("#ep-loadmore-event-venue").attr("disabled", false);
                $('#ep-venues-paged').val(response.data.paged);
                if(response.data.paged >= max_page){
                    $('.ep-venues-load-more').hide();
                }
                $('#ep-event-venues-loader-section').append(response.data.html);
            }
        }); 
    });

    // Load More
    $( document ).on( 'click', '#ep-loadmore-upcoming-event-venue', function(e) {
        var max_page      = $( this ).attr('data-max');
        var paged         = $( this ).attr('data-paged');
        var display_style = $( this ).attr('data-style');
        var limit         = $( this ).attr('data-limit');
        var cols          = $( this ).attr('data-cols');
        var pastevent     = $( this ).attr('data-pastevent');
        var post_id       = $( this ).attr('data-id');
        var formData      = new FormData();
        formData.append( 'action', 'ep_load_more_upcomingevent_venue' );
        formData.append( 'paged', paged );
        formData.append( 'event_style', display_style );
        formData.append( 'event_limit', limit );
        formData.append( 'event_cols', cols );
        formData.append( 'hide_past_events',pastevent );
        formData.append( 'post_id',post_id );
        $('.ep-spinner').addClass('ep-is-active');
        $('.ep-register-response').html();
        $.ajax({
            type : "POST",
            url : ep_frontend.ajaxurl,
            data: formData,
            contentType: false,
            processData: false,       
            success: function(response) {
                $('.ep-spinner').removeClass('ep-is-active');
                $( '#ep-loadmore-upcoming-event-venue' ).attr( 'data-paged', response.data.paged );
                if( response.data.paged >= max_page ) {
                    $('#ep-loadmore-upcoming-event-venue').hide();
                }
                $('#ep-venue-upcoming-events').append(response.data.html);
            }
        }); 
    });

    $( document ).ready( function(e) {
        $( '.ep_venue_gallery_modal_container' ).hide();
        // open venue gallery modal
        if( $( '#ep_venue_gal_modal' ).length > 0 ) {
            $( '#ep_venue_gal_modal' ).responsiveSlides({
                auto: false, 
                speed: 500, 
                timeout: 4000, 
                pager: true, 
                nav: true, 
                random: false, 
                pause: true, 
                pauseControls: true, 
                prevText: "", 
                nextText: "", 
                maxwidth: "", 
                navContainer: ".ep-single-event-nav", 
                manualControls: '#ep_venue_gal_thumbs',
                namespace: "ep-rslides"
            });
        }

        // Map
        setTimeout( function() {
            if( eventprime.global_settings.gmap_api_key ) {
                ep_load_google_map( 'em_single_venue_map_canvas' );
            }
        }, 1000);

    });

    // show gallery modal
    $( document ).on( 'click', '.ep_open_gal_modal', function() {
        $( '.ep_venue_gallery_modal_container' ).show();
    });

    // load google map on venue detail page
    function ep_load_google_map( element_id ) {
        if( $( '#ep_venue_load_map_data' ).length > 0 ) {
            let venue_detail = $( '#ep_venue_load_map_data' ).data( 'venue' );
            if( venue_detail ) {
                if( venue_detail.em_lat ) {
                    let address = venue_detail.em_address;
                    let lat = parseFloat( venue_detail.em_lat );
                    let lng = parseFloat( venue_detail.em_lng );
                    let zoom_level = parseInt( venue_detail.em_zoom_level );
                    if( lat && lng ) {
                        let coordinates = { lat: lat, lng: lng };
                        if( !zoom_level ) {
                            zoom_level = 16;
                        }
                        var map = new google.maps.Map( document.getElementById( 'em_single_venue_map_canvas' ), {
                            center: coordinates,
                            zoom: zoom_level
                        });

                        const marker = new google.maps.Marker({
                            position: coordinates,
                            map: map,
                        });
                    } else{
                        $( '#ep_venue_load_map_data' ).hide();
                    }
                } else{
                    $( '#ep_venue_load_map_data' ).hide();
                }
            }
        }
    }

    // close the modal if pretty url
    $( document ).on( 'click', '#ep_venue_gallery_modal_close', function() {
        setTimeout( function() {
            let check_modal_status = $( '.ep_venue_gallery_modal_container' ).css( 'display' );
            if( check_modal_status == 'block' ) {
                $( '.ep_venue_gallery_modal_container' ).hide();
            }
        }, 300 );
    });

});