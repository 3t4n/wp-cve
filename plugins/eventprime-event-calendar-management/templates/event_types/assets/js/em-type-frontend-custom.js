jQuery( function( $ ) {

    $(document).on('click','#ep-loadmore-event-types',function(e){
        var max_page = $('#ep-loadmore-event-types').data('max');
        var paged = $('#ep-types-paged').val();
        var display_style = $('#ep-types-style').val();
        var limit = $('#ep-types-limit').val();
        var cols = $('#ep-types-cols').val();
        var featured = $('#ep-types-featured').val();
        var popular = $('#ep-types-popular').val();
        var search = $('#ep-types-search').val();
        var box_color = $('#ep-types-box-color').val();
        var formData = new FormData();
        formData.append('action', 'ep_load_more_event_types');
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
        $('.ep-register-response').html();
        $.ajax({
            type : "POST",
            url : ep_frontend.ajaxurl,
            data: formData,
            contentType: false,
            processData: false,       
            success: function(response) {
                $('.ep-spinner').removeClass('ep-is-active');
                $('#ep-types-paged').val(response.data.paged);
                if(response.data.paged >= max_page){
                    $('.ep-types-load-more').hide();
                }
                $('#ep-event-types-loader-section').append(response.data.html);
                
            }
        }); 
    });

    // load more
    $(document).on('click','#ep-loadmore-upcoming-event-eventtype',function(e){
        var max_page      = $( this ).attr('data-max');
        var paged         = $( this ).attr('data-paged');
        var display_style = $( this ).attr('data-style');
        var limit         = $( this ).attr('data-limit');
        var cols          = $( this ).attr('data-cols');
        var pastevent     = $( this ).attr('data-pastevent');
        var post_id       = $( this ).attr('data-id');
        var formData      = new FormData();
        formData.append( 'action', 'ep_load_more_upcomingevent_eventtype' );
        formData.append( 'paged', paged );
        formData.append( 'event_style', display_style );
        formData.append( 'event_limit', limit );
        formData.append( 'event_cols', cols );
        formData.append( 'hide_past_events',pastevent );
        formData.append( 'post_id',post_id );
        $('.ep-spinner').addClass('ep-is-active');
        $.ajax({
            type : "POST",
            url : ep_frontend.ajaxurl,
            data: formData,
            contentType: false,
            processData: false,       
            success: function(response) {
                $('.ep-spinner').removeClass('ep-is-active');
                $( '#ep-loadmore-upcoming-event-eventtype' ).attr( 'data-paged', response.data.paged );
                if( response.data.paged >= max_page ) {
                    $( '#ep-loadmore-upcoming-event-eventtype' ).hide();
                }
                $( '#ep-eventtype-upcoming-events' ).append( response.data.html );
            }
        }); 
    });
});

