(function($) {
    
    var $wp_inline_edit = inlineEditPost.edit;    
    inlineEditPost.edit = function( id ) {
        $wp_inline_edit.apply( this, arguments );
        var $post_id = 0;
        if ( typeof( id ) == 'object' )
            $post_id = parseInt( this.getId( id ) );
        if ( $post_id > 0 ) {
            var $edit_row = $( '#edit-' + $post_id );
            var $post_row = $( '#post-' + $post_id );
            var $post_hits = $( '.column-st4_post_hits > span', $post_row ).html();            
            var $post_id_rel = $( '.column-st4_post_hits > span', $post_row ) . attr("rel");
            $( ':input[name="st4_post_id"]', $edit_row ).val( $post_id_rel );
            $( ':input[name="st4_post_hits"]', $edit_row ).val( $post_hits );
        }
    };

    $( '#bulk_edit' ).live( 'click', function() {
        var $bulk_row = $( '#bulk-edit' );
        var $post_ids = new Array();
        $bulk_row.find( '#bulk-titles' ).children().each( function() {
            $post_ids.push( $( this ).attr( 'id' ).replace( /^(ttle)/i, '' ) );
        });
        var $st4_post_hits = $bulk_row.find( 'input[name="st4_post_hits"]' ).val();
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            async: false,
            cache: false,
            data: {
                action: 'ST4_most_read_save_bulk_edit',
                post_ids: $post_ids,
                st4_post_hits: $st4_post_hits
            }
        });
       
    });
    

})(jQuery);