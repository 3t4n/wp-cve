wp.domReady( function() {
	
    // Post function
    function checkTitle(title, id,post_type) {
        var data = {
            action: 'title_check',
            post_title: title,
            post_type: post_type,
            post_id: id
        };
        
		$.ajax( {
			url     : ajaxurl,
			data    : data,
			dataType: 'json'
		} ).done( function ( data ) {
			
			(function ( wp ) {
				status = 'error' === data.status ? 'error' : 'success';
				wp.data.dispatch( 'core/notices' ).createNotice(
					status, 
					data.message, 
					{
						id: 'duplicate-message',
						isDismissible: true, 
					}
				);
			})( window.wp );			
		} );
    };

    // Click function to initiate post function
    $('#post-title-0').change(function() {
		var title = $('#post-title-0').val();
        var id = $('#post_ID').val();
        var post_type = $('#post_type').val();
        if(title!=' ')
        {
			checkTitle(title, id,post_type);
		}
    });
});
