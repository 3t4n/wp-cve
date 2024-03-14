( function( $ ) {

    'use strict';

	$( document ).ready( function() {

		if ( jQuery().niceSelect ) {
			$( '.youzify select' ).not( '[multiple="multiple"]' ).niceSelect();
		}

	    /**
	     * Show Files Browser
	     */
		$( document ).on( 'click', '.youzify-upload-photo', function( e ) {
			e.preventDefault();
			var uploader = $( this ).closest( '.youzify-uploader-item' );
			uploader.find( '.youzify_upload_file' ).attr( 'data-source', $( 'input[name=youzify_widget_source]' ).val() );
			uploader.find( '.youzify_upload_file' ).attr( 'data-user-id', $( 'input[name=youzify_widget_user_id]' ).val() );
			uploader.find( '.youzify_upload_file' ).trigger( 'click' );
		});

	    /*
	     * Images Uploader
	     */
		$( document ).on( 'change', '.youzify_upload_file', function( e ) {

	        e.stopPropagation();

	        var formData = new FormData(),
		  		file 	  = $( this ),
		  		field 	  = $( this ).closest( '.youzify-uploader-item' ),
		  		preview   = field.find( '.youzify-photo-preview' ),
		  		old_attachment_id = field.find( '.youzify-photo-url' ).val();

		  	// Append Data.
		  	formData.append( 'nonce', $( this ).closest( 'form' ).find( "input[name='security']" ).val() );
	       	formData.append( 'file', $( this )[0].files[0] );
	       	formData.append( 'user_id', $( this ).attr( 'data-user-id' ) );
	       	formData.append( 'source', $( this ).attr( 'data-source' ) );
		  	formData.append( 'action', 'upload_files' );

	        $.ajax( {
	            url         : Youzify.ajax_url,
	            type        : "POST",
	            data        : formData,
	            contentType : false,
	            cache       : false,
	            processData : false,
	            beforeSend  : function() {
	            	// Display Loader.
	            	var loader = '<div class="youzify-load-photo"><i class="fas fa-spinner fa-spin"></i></div>';
	            	$( loader ).hide().appendTo( preview ).fadeIn( 800 );
	            },
	            success : function( response ) {

	            	// Remove File From Input.
	            	file.val( '' );

	            	// Get Response Data.
		            if ( response.success == false ) {
	            		// Hide Loader.
	            		preview.find( '.youzify-load-photo' ).fadeOut( 300 ).remove();
		            	// Show Error Message
                		$.youzify_DialogMsg( 'error', response.data.error );
	            		return;
		            }

				  	// Delete The Old Photo.
				  	if ( old_attachment_id ) {
				  		$.youzify_DeletePhoto( old_attachment_id );
				  	}

		   			// Save Photo.
	            	preview.find( '.youzify-load-photo' ).fadeOut( 300, function() {
	            		// Hide Loader.
	            		$( this ).remove();
	            		// Display Photo Preview.
	            		preview.fadeOut( 100, function() {
		            		$( this ).css( 'background-image', 'url(' + response.data.url + ')' ).fadeIn( 400 );
		            		// Update Photo Url
		            		field.find( '.youzify-photo-url' ).val( response.data.attachment_id ).change();
		            		// Activate Trash Icon.
		            		field.find( '.youzify-delete-photo' ).addClass( 'youzify-show-trash' );
			        		// Save Form Data
			        		$.post( Youzify.ajax_url, field.closest( 'form' ).serialize() + '&die=true' );
	            		});
	            	});
	            }
	        });
	    });

        // Open Items.
        $( '.youzify-account-menu' ).on( 'click', function( e ) {

        	var next_menu = $( this ).next( '.youzify-account-menus' );

        	if ( next_menu.hasClass( 'youzify-show-account-menus' ) ) {
        		next_menu.removeClass( 'youzify-show-account-menus' ).css( 'display', 'block' );
        	}

        	// Show / Hide Menu.
            $( this ).next( '.youzify-account-menus' ).slideToggle();

        });

	    /**
	     * Remove Image
	     */
		$( document ).on( 'click', '.youzify-delete-photo', function( e ) {

			// Set up Variables.
			var uploader = $( this ).closest( '.youzify-uploader-item' );

			// Remove Image from Directory.
			$.youzify_DeletePhoto( uploader.find( '.youzify-photo-url' ).val() );

			// Remove Image Url
			uploader.find( '.youzify-photo-url' ).val( '' ).trigger( 'change' );

			// Reset Preview Image.
		    uploader.find( '.youzify-photo-preview' ).css( 'background-image', 'url(' + Youzify_Account.default_img + ')' );

		    // Hide Trash Icon.
		    $( this ).removeClass( 'youzify-show-trash' );

    		// Save Form Data
    		$.post( Youzify.ajax_url, uploader.closest( 'form' ).serialize() + '&die=true' );

		});

	    /*
	     * Delete Photo
	     */
		$.youzify_DeletePhoto = function( attachment_id ) {

			// Create New Form Data.
		    var formData = new FormData();

		    // Fill Form with Data.
		    formData.append( 'attachment_id', attachment_id );
		    formData.append( 'action', 'youzify_delete_attachment' );

			$.ajax({
                type: "POST",
                data: formData,
                url: Youzify.ajax_url,
		        contentType: false,
		        processData: false
			});
	    }

	    // Update Account Photo with the new uploaded photo.
	    $( '.youzify-account-photo .youzify-photo-url' ).on( 'change' , function( e ) {
			e.preventDefault();
			// Get Account Photo url.
			var account_photo = $( this ).val();
			// If Input Value Empty Use Default Image
			if ( ! account_photo ) {
				account_photo = Youzify_Account.default_img;
			}
			// Change Account Photo.
		    $( '.youzify-account-img' ).fadeOut( 200, function() {
		    	$( this ).css( 'background-image', 'url(' + account_photo + ')' ).fadeIn( 200 );
		    });
		});

    	$( '.youzify-user-provider-unlink' ).on( 'click', function( e ) {

    		e.preventDefault();

    		// Disable Click On Processing Unlinking.
    		if ( $( this ).hasClass( 'loading' ) ) {
    			return false;
    		}

    		// Init Vars.
    		var youzify_provider_parent = $( this ).closest( '.youzify-user-provider-connected' ),
    			youzify_curent_unlink_btn = $( this );

    		// Add Loading Class.
    		youzify_curent_unlink_btn.addClass( 'loading' );

    		// Get Button Data.
			var data = {
				action: 'youzify_unlink_provider_account',
				provider: $( this ).data( 'provider' ),
				security: $( this ).data( 'nonce')
			};

			// Process Ajax Request.
			$.post( ajaxurl, data, function( response ) {

            	// Get Response Data.
            	var res = $.parseJSON( response );

				if ( res.error ) {

		    		// Remove Loading Class.
		    		youzify_curent_unlink_btn.removeClass( 'loading' );

	            	// Show Error Message
	            	$.youzify_DialogMsg( 'error', res.error );

					return false;

				} else if ( res.action ) {

		    		// Remove Loading Class.
		    		youzify_curent_unlink_btn.removeClass( 'loading' );

		    		// Clear Token input.
		    		youzify_provider_parent.find( '.youzify-user-provider-token' ).val( '' );

					// Remove Provider.
					youzify_provider_parent.find( '.youzify-user-provider-box' ).remove();
					youzify_provider_parent.removeClass().addClass( 'youzify-user-provider-unconnected' );

	            	// Show Error Message
	            	$.youzify_DialogMsg( 'success', res.msg );

					return false;
				}

			}).fail( function( xhr, textStatus, errorThrown ) {

				// Remove Loading Class.
	    		youzify_curent_unlink_btn.removeClass( 'loading' );

            	// Show Error Message
            	$.youzify_DialogMsg( 'error', Youzify.unknown_error );

				return false;

    		});

		});

	});

})( jQuery );