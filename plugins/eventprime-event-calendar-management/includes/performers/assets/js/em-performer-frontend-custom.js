jQuery( function( $ ) {
    $(document).ready(function(e){
        // Load More
        $(document).on('click','#ep-loadmore-event-performers',function(e){
            var max_page = $('#ep-loadmore-event-performers').data('max');
            var paged = $('#ep-performers-paged').val();
            var display_style = $('#ep-performers-style').val();
            var limit = $('#ep-performers-limit').val();
            var cols = $('#ep-performers-cols').val();
            var featured = $('#ep-performers-featured').val();
            var popular = $('#ep-performers-popular').val();
            var orderby = $('#ep-performers-orderby').val();
            var search = $('#ep-performers-search').val();
            var box_color = $('#ep-performers-box-color').val();
            var formData = new FormData();
            formData.append('action', 'ep_load_more_event_performer');
            formData.append('paged', paged);
            formData.append('display_style', display_style);
            formData.append('limit', limit);
            formData.append('cols', cols);
            formData.append('featured',featured);
            formData.append('popular',popular);
            formData.append('orderby',orderby);
            formData.append('search',search);
            formData.append('box_color',box_color);
            if($('#ep_keyword').length && $('#ep_keyword').val() !=''){
                formData.append('keyword', $('#ep_keyword').val());
                formData.append('ep_search', true);
            }
            $('.ep-spinner').addClass('ep-is-active');
            $("#ep-loadmore-event-performers").attr("disabled", true);
            $('.ep-register-response').html();
            $.ajax({
                type : "POST",
                url : ep_frontend.ajaxurl,
                data: formData,
                contentType: false,
                processData: false,       
                success: function(response) {
                    $('.ep-spinner').removeClass('ep-is-active');
                    $("#ep-loadmore-event-performers").attr("disabled", false);
                    $('#ep-performers-paged').val(response.data.paged);
                    if(response.data.paged >= max_page){
                        $('.ep-performers-load-more').hide();
                    }
                    $('#ep-event-performers-loader-section').append(response.data.html);
                    
                }
            }); 
        });
        
        // Load More for upcoming events
        $( document ).on( 'click', '#ep-loadmore-upcoming-event-performers', function(e) {
            var max_page      = $( this ).attr('data-max');
            var paged         = $( this ).attr('data-paged');
            var display_style = $( this ).attr('data-style');
            var limit         = $( this ).attr('data-limit');
            var cols          = $( this ).attr('data-cols');
            var pastevent     = $( this ).attr('data-pastevent');
            var post_id       = $( this ).attr('data-id');
            var formData      = new FormData();
            formData.append( 'action', 'ep_load_more_upcomingevent_performer' );
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
                    $( '#ep-loadmore-upcoming-event-performers' ).attr( 'data-paged', response.data.paged );
                    if(response.data.paged >= max_page){
                        $('#ep-loadmore-upcoming-event-performers').hide();
                    }
                    $('#ep-performer-upcoming-events').append(response.data.html);
                }
            }); 
        });

        // gallery slider
        $( '.ep_perfomer_gallery_modal_container' ).hide();
        // open venue gallery modal
        if( $( '#ep_perfomer_gal_modal' ).length > 0 ) {
            $( '#ep_perfomer_gal_modal' ).responsiveSlides({
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
                manualControls: '#ep_perfomer_gal_thumbs',
                namespace: "ep-rslides"
            });
        }
    });

    // show gallery modal
    $( document ).on( 'click', '.ep_open_gal_modal', function() {
        $( '.ep_perfomer_gallery_modal_container' ).show();
    });

    // close the modal if pretty url
    $( document ).on( 'click', '#ep_performer_gallery_modal_close', function() {
        setTimeout( function() {
            let check_modal_status = $( '.ep_perfomer_gallery_modal_container' ).css( 'display' );
            if( check_modal_status == 'block' ) {
                $( '.ep_perfomer_gallery_modal_container' ).hide();
            }
        }, 300 );
    });
});
