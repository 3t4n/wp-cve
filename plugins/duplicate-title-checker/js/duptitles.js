jQuery(document).ready(function($){
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
			
			$('#message').remove();
			$('#poststuff').prepend('<div id=\"message\" class=\"updated fade '+data.status+'\"><p>'+data.message+'</p></div>');
			
		} );

    };

    // Click function to initiate post function
    $('#title').change(function() {
        var title = $('#title').val();
        var id = $('#post_ID').val();
        var post_type = $('#post_type').val();
        if(title!='')
        {
			checkTitle(title, id,post_type);
		}
    });

});
