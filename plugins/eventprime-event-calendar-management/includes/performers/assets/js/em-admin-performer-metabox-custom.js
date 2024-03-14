jQuery( function( $ ) {

    $( document ).ready( function() {

        $( ".ep_performer_options_panel:first-of-type" ).show();

        // validate post before save
        var form = $("form[name='post']");
        $(form).find("input[type='submit']").click(function(e){
            if($("#post_type").val() != 'em_performer') return true;
            
            e.preventDefault();
            let formError = 0;
            // check validation for performer type field
            let em_type = $('input[name="em_type"]:checked').val();
            if( !em_type ) {
                let requireString = get_translation_string( 'required' );
                $( '#ep_performer_type_error' ).html( requireString );
                $( '.ep_performer_settings a' ).trigger( 'click' );
                $('.ep-performer-metabox-tab').removeClass('ep-tab-active');
                $('.ep-performer-metabox-tab.ep_performer_settings').addClass('ep-tab-active');
                $( ".ep_performer_options_panel" ).hide();
                $("#ep_performer_settings_data").show();
                formError = 1;
            }
            else
            {
                $( '#ep_performer_type_error' ).html('');
            }
            
            // check for valid phone no.
            $( '.ep-performers-phone input[type="tel"]' ).each(function() {
                let phoneValue = $( this ).val();
                if( phoneValue && !is_valid_phone(phoneValue ) ) {
                    let invalidPhone = get_translation_string( 'invalid_phone' );
                    if( $( this ).closest( '.ep-per-phone' ).find( '.ep-error-message' ).length < 1 ){
                        $( this ).closest( '.ep-per-phone' ).append('<div class="ep-error-message">'+invalidPhone+'</div>');
                    }
                    $( this ).focus();
                    formError = 1;
                    $('.ep-performer-metabox-tab').removeClass('ep-tab-active');
                    $('.ep-performer-metabox-tab.ep_performer_personal_info').addClass('ep-tab-active');
                    $( ".ep_performer_options_panel" ).hide();
                    $("#ep_performer_personal_data").show();
                    return false;
                } else{
                    $( this ).closest( '.ep-per-phone' ).find( '.ep-error-message' ).remove();
                }
            });

            // check for valid email
            $( '.ep-performers-email input[type="email"]' ).each(function() {
                let emailValue = $( this ).val();
                if( emailValue && !is_valid_email(emailValue ) ) {
                    let invalidEmail = get_translation_string( 'invalid_email' );
                    if( $( this ).closest( '.ep-per-email' ).find( '.ep-error-message' ).length < 1 ){
                        $( this ).closest( '.ep-per-email' ).append('<div class="ep-error-message">'+invalidEmail+'</div>');
                    }
                    $( this ).focus();
                    $('.ep-performer-metabox-tab').removeClass('ep-tab-active');
                    $('.ep-performer-metabox-tab.ep_performer_personal_info').addClass('ep-tab-active');
                    $( ".ep_performer_options_panel" ).hide();
                    $("#ep_performer_personal_data").show();
                    formError = 1;
                    return false;
                } else{
                    $( this ).closest( '.ep-per-email' ).find( '.ep-error-message' ).remove();
                }
            });

            // check for valid url
            $( '.ep-performers-website input[type="url"]' ).each(function() {
                let urlValue = $( this ).val();
                if( urlValue && !is_valid_url(urlValue ) ) {
                    let invalidUrl = get_translation_string( 'invalid_url' );
                    if( $( this ).closest( '.ep-per-website' ).find( '.ep-error-message' ).length < 1 ){
                        $( this ).closest( '.ep-per-website' ).append('<div class="ep-error-message">'+invalidUrl+'</div>');
                    }
                    $( this ).focus();
                    $('.ep-performer-metabox-tab').removeClass('ep-tab-active');
                    $('.ep-performer-metabox-tab.ep_performer_personal_info').addClass('ep-tab-active');
                    $( ".ep_performer_options_panel" ).hide();
                    $("#ep_performer_personal_data").show();
                    formError = 1;
                    return false;
                } else{
                    $( this ).closest( '.ep-per-website' ).find( '.ep-error-message' ).remove();
                }
            });

            if( formError == 1 ){
                return false;
            }
            $(form).submit();
        });

    });

    // show/hide panels
    $( document ).on( 'click', '.ep_performer_metabox_tabs li a', function(e) {
        e.preventDefault();
        let panelSrc = $( this ).data( 'src' );
        if( $( "#"+panelSrc ).length > 0 ) {
            $( '.ep_performer_metabox_tabs li' ).removeClass( 'ep-tab-active' );
            $( this ).closest( 'li' ).addClass( 'ep-tab-active' );
            $( ".ep_performer_options_panel" ).hide();
            $( "#"+panelSrc ).show();
        }
    });

    // add more option
    $( document ).on( 'click', '.ep-per-add-more', function() {
        let dataInput = $( this ).data( 'input' );
        let removeTitle = $( this ).data( 'remove_title' );
        let placeholder = $( this ).data( 'placeholder' );
        let max_field_count = 4;
        if( dataInput == 'phone' ) {
            let phone_count = $( '.ep-per-phone' ).length;
            if( phone_count < max_field_count ) {
                let fieldHtml = '<div class="ep-box-col-12 ep-mt-3 ep-per-phone ep-per-data-field">';
                fieldHtml += '<input type="tel" class="ep-per-data-input ep-mr-2" name="em_performer_phones[]" placeholder="'+placeholder+'">';
                fieldHtml += '<button type="button" class="ep-per-remove button button-primary" data-input="phone" title="'+removeTitle+'">-</button>';
                fieldHtml += '</div>';
                $( ".ep-performers-phone" ).append( fieldHtml );
            } else{
                show_toast('warning', em_performer_meta_box_object.max_field_warning + ' ' + dataInput );
            }
        }

        if( dataInput == 'email' ) {
            let email_count = $( '.ep-per-email' ).length;
            if( email_count < max_field_count ) {
                let fieldHtml = '<div class="ep-box-col-12 ep-mt-3 ep-per-email ep-per-data-field">';
                fieldHtml += '<input type="email" class="ep-per-data-input ep-mr-2" name="em_performer_emails[]" placeholder="'+placeholder+'">';
                fieldHtml += '<button type="button" class="ep-per-remove button button-primary" data-input="email" title="'+removeTitle+'">-</button>';
                fieldHtml += '</div>';
                $( ".ep-performers-email" ).append( fieldHtml );
            } else{
                show_toast('warning', em_performer_meta_box_object.max_field_warning + ' ' + dataInput );
            }
        }

        if( dataInput == 'website' ) {
            let website_count = $( '.ep-per-website' ).length;
            if( website_count < max_field_count ) {
                let fieldHtml = '<div class="ep-box-col-12 ep-mt-3 ep-per-website ep-per-data-field">';
                fieldHtml += '<input type="url" class="ep-per-data-input ep-mr-2" name="em_performer_websites[]" placeholder="'+placeholder+'">';
                fieldHtml += '<button type="button" class="ep-per-remove button button-primary" data-input="website" title="'+removeTitle+'">-</button>';
                fieldHtml += '</div>';
                $( ".ep-performers-website" ).append( fieldHtml );
            } else{
                show_toast('warning', em_performer_meta_box_object.max_field_warning + ' ' + dataInput );
            }
        }
    });

    // remove fields
    $( document ).on( 'click', '.ep-per-remove', function() {
        $( this ).closest( '.ep-per-data-field' ).remove();
    });

    var performer_gallery_frame;
	var $image_gallery_ids = $( '#em_performer_gallery' );
	var $product_images = $( '#ep_performer_gallery_container' ).find(
		'ul.ep_gallery_images'
	);
    // add gallery images
    $( '.ep_add_performer_gallery' ).on( 'click', 'a', function ( event ) {
		var $el = $( this );

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( performer_gallery_frame ) {
			performer_gallery_frame.open();
			return;
		}

		// Create the media frame.
		performer_gallery_frame = wp.media.frames.product_gallery = wp.media( {
			// Set the title of the modal.
			title: $el.data( 'choose' ),
			button: {
				text: $el.data( 'update' ),
			},
			states: [
				new wp.media.controller.Library( {
					title: $el.data( 'choose' ),
					filterable: 'all',
					multiple: true,
				} ),
			],
		} );

		// When an image is selected, run a callback.
		performer_gallery_frame.on( 'select', function () {
			var selection = performer_gallery_frame.state().get( 'selection' );
			var attachment_ids = $image_gallery_ids.val();

			selection.map( function ( attachment ) {
				attachment = attachment.toJSON();

				if ( attachment.id ) {
					attachment_ids = attachment_ids
						? attachment_ids + ',' + attachment.id
						: attachment.id;
					var attachment_image =
						attachment.sizes && attachment.sizes.thumbnail
							? attachment.sizes.thumbnail.url
							: attachment.url;

					$product_images.append(
						'<li class="ep-gal-img" data-attachment_id="' +
							attachment.id +
							'"><img src="' +
							attachment_image +
							'" /><div class="ep-gal-img-delete"><span class="em-performer-gallery-remove dashicons dashicons-trash"></span></div></li>'
					);
				}
			} );

			$image_gallery_ids.val( attachment_ids );
		} );

		// Finally, open the modal.
		performer_gallery_frame.open();
	} );
        $( document ).on( 'click', '.em-performer-gallery-remove', function(){
            var image_id = $(this).closest('li').data('attachment_id').toString();
            console.log(image_id);
            var gallery_ids = $('#em_performer_gallery').val();
            var galleryArr  = gallery_ids.split(',');
            for( var i = 0; i < galleryArr.length; i++){ 
                if ( galleryArr[i] === image_id) { 
                    galleryArr.splice(i, 1); 
                }
            }
            gallery_ids = galleryArr.toString();
            $('#em_performer_gallery').val(gallery_ids);
            $(this).closest('li').remove();

        });

});