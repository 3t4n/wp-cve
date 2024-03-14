jQuery(document).ready(function($) {
	
	if ($('#ABp_latest_portfolio').length) {
		$('#ABp_latest_portfolio').carouFredSel({
			prev: '#portfolio_prev',
			next: '#portfolio_next',
			auto: false,
			width: '100%',
			scroll: 1,
		});		
	}





// select

    $('#item_type').on('change', function (e) {
            if(this.value=='slider'){

                $('#gsp-gallery').show();
                $('#gsp-media').hide();
                
            }

            if(this.value=='single-image'){

                $('#gsp-gallery').hide();
                $('.video-url').hide();
                $('.audio-url').hide();
               
            }

            
            if(this.value=='video'){

                $('#gsp-gallery').hide();
                $('.video-url').show();
                $('.audio-url').hide();
               
            }
            if(this.value=='audio'){

                $('#gsp-gallery').hide();
                $('.video-url').hide();
                $('.audio-url').show();
               
            }
            
        });

         var value = $('select#item_type option:selected').val();
             if(value=='slider'){

                $('#gsp-gallery').show();
                $('.video-url').hide();
                $('.audio-url').hide();
                
            }

            if(value=='single-image'){

                $('#gsp-gallery').hide();
                $('.video-url').hide();
                $('.audio-url').hide();
                
            }

           
            if(value=='video'){

                $('#gsp-gallery').hide();
                $('.video-url').show();
                $('.audio-url').hide();
               
            }
            if(value=='audio'){

                $('#gsp-gallery').hide();
                $('.video-url').hide();
                $('.audio-url').show();
                
            }






// gallery
    $('.gsp-field-gallery').each(function() {

        var $this   = $(this),
        $edit   = $this.find('.gsp-edit'),
        $remove = $this.find('.gsp-remove'),
        $list   = $this.find('ul'),
        $input  = $this.find('input'),
        $img    = $this.find('img'),
        wp_media_frame,
        wp_media_click;

        $this.on('click', '.gsp-add, .gsp-edit', function( e ) {

            var $el   = $(this),
            what  = ( $el.hasClass('gsp-edit') ) ? 'edit' : 'add',
            state = ( what === 'edit' ) ? 'gallery-edit' : 'gallery-library';

            e.preventDefault();

            // Check if the `wp.media.gallery` API exists.
            if ( typeof wp === 'undefined' || ! wp.media || ! wp.media.gallery ) {
                return;
            }

            // If the media frame already exists, reopen it.
            if ( wp_media_frame ) {
                wp_media_frame.open();
                wp_media_frame.setState(state);
                return;
            }

            // Create the media frame.
            wp_media_frame = wp.media({
                library: {
                type: 'image'
                },
                frame: 'post',
                state: 'gallery',
                multiple: true
            });

            // Open the media frame.
            wp_media_frame.on('open', function() {

                var ids = $input.val();

                if ( ids ) {

                    var get_array = ids.split(',');
                    var library   = wp_media_frame.state('gallery-edit').get('library');

                    wp_media_frame.setState(state);

                    get_array.forEach(function(id) {
                        var attachment = wp.media.attachment(id);
                        library.add( attachment ? [ attachment ] : [] );
                    });

                }
            });

            // When an image is selected, run a callback.
            wp_media_frame.on( 'update', function() {

                var inner  = '';
                var ids    = [];
                var images = wp_media_frame.state().get('library');

                images.each(function(attachment) {

                    var attributes = attachment.attributes;
                    var thumbnail  = ( typeof attributes.sizes.thumbnail !== 'undefined' ) ? attributes.sizes.thumbnail.url : attributes.url;

                    inner += '<li><img src="'+ thumbnail +'"></li>';
                    ids.push(attributes.id);

                });

                $input.val(ids).trigger('change');
                $list.html('').append(inner);
                $remove.removeClass('hidden');
                $edit.removeClass('hidden');

            });

            // Finally, open the modal.
            wp_media_frame.open();
            wp_media_click = what;

        });

        // Remove image
        $remove.on('click', function( e ) {
            e.preventDefault();
            $list.html('');
            $input.val('').trigger('change');
            $remove.addClass('hidden');
            $edit.addClass('hidden');
        });

    });


});