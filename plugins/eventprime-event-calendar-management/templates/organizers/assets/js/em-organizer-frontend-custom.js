jQuery( function( $ ) {

    $( document ).on( 'click', '#ep-loadmore-event-organizers', function() {
        var max_page = $('#ep-loadmore-event-organizers').data('max');
        var paged = $('#ep-organizers-paged').val();
        var display_style = $('#ep-organizers-style').val();
        var limit = $('#ep-organizers-limit').val();
        var cols = $('#ep-organizers-cols').val();
        var featured = $('#ep-organizers-featured').val();
        var popular = $('#ep-organizers-popular').val();
        var search = $('#ep-organizers-search').val();
        var box_color = $('#ep-organizers-box-color').val();
        var formData = new FormData();
        formData.append('action', 'ep_load_more_event_organizer');
        formData.append('paged', paged);
        formData.append('display_style', display_style);
        formData.append('limit', limit);
        formData.append('cols', cols);
        formData.append('featured',featured);
        formData.append('popular',popular);
        formData.append('search',search);
        formData.append('box_color',box_color);
        if($('#ep_keyword').length && $('#ep_keyword').val()!= ''){
            formData.append('keyword', $('#ep_keyword').val());
            formData.append('ep_search', true);
        }
        $('.ep-spinner').addClass('ep-is-active');
        $("#ep-loadmore-event-organizers").attr("disabled", true);
        $.ajax({
            type : "POST",
            url : ep_frontend.ajaxurl,
            data: formData,
            contentType: false,
            processData: false,       
            success: function(response) {
                $('.ep-spinner').removeClass('ep-is-active');
                $("#ep-loadmore-event-organizers").attr("disabled", false);
                $('#ep-organizers-paged').val(response.data.paged);
                if(response.data.paged >= max_page){
                    $('.ep-organizers-load-more').hide();
                }
                $('#ep-event-organizers-loader-section').append(response.data.html);
                
            }
        }); 
    });

    // Load More
    $( document ).on( 'click', '#ep-loadmore-upcoming-event-organizer', function(e) {
        var max_page      = $( this ).attr('data-max');
        var paged         = $( this ).attr('data-paged');
        var display_style = $( this ).attr('data-style');
        var limit         = $( this ).attr('data-limit');
        var cols          = $( this ).attr('data-cols');
        var pastevent     = $( this ).attr('data-pastevent');
        var post_id       = $( this ).attr('data-id');
        var formData      = new FormData();
        formData.append( 'action', 'ep_load_more_upcomingevent_organizer' );
        formData.append( 'paged', paged );
        formData.append( 'event_style', display_style );
        formData.append( 'event_limit', limit );
        formData.append( 'event_cols', cols );
        formData.append( 'hide_past_events',pastevent );
        formData.append( 'post_id',post_id );
        $( '.ep-spinner' ).addClass( 'ep-is-active' );
        $( '.ep-register-response' ).html();
        $.ajax({
            type : "POST",
            url : ep_frontend.ajaxurl,
            data: formData,
            contentType: false,
            processData: false,       
            success: function(response) {
                $( '.ep-spinner' ).removeClass( 'ep-is-active' );
                $( '#ep-loadmore-upcoming-event-organizer' ).attr( 'data-paged', response.data.paged );
                if( response.data.paged >= max_page ) {
                    $( '#ep-loadmore-upcoming-event-organizer' ).hide();
                }
                $( '#ep-organizer-upcoming-events' ).append( response.data.html );
            }
        }); 
    });
});
