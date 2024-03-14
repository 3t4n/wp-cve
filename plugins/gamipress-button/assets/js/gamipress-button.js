(function( $ ) {

    $('body').on('click', '.gamipress-button', function( e ) {

        // Setup vars
        var $this = $(this);
        var type = $this.attr('type');
        var id = $this.attr('id');
        var classes = $this.attr('class');
        var form = $this.attr('form');
        var name = $this.attr('name');
        var value = $this.attr('value');
        var url = $this.data('url');
        var post = $this.data('post');
        var comment = $this.data('comment');


        $.ajax({
            url: gamipress_button.ajaxurl,
            method: 'POST',
            data: {
                action: 'gamipress_button_click',
                nonce: gamipress_button.nonce,
                type: type,
                id: id,
                class: classes,
                form: form,
                name: name,
                value: value,
                post: post,
                comment: comment
            },
            success: function( response ) {
                if( url !== undefined || url.length !== 0 ) {
                    location.href = url;
                }
            }
        }); 

    });

})( jQuery );