( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		// Init Vars.
		var youzify_atts_count = 0, youzify_nxt_atts_id = 0, youzify_atts_files = null;

		/**
		 * Append Attachments.
		 */
		$.ajaxPrefilter( function( options, originalOptions, jqXHR ) {

			if ( originalOptions.hasOwnProperty( 'data' ) && originalOptions.data.hasOwnProperty( 'action' ) ) {

				var action = originalOptions.data.action;

				if ( action == 'new_activity_comment' ) {

					var attachment = $( '#ac-form-' + originalOptions.data.form_id ).find( 'input[name="attachments_files[]"]' ).map(function(idx, elem) { return $( elem ).val(); }).get();

					if ( attachment.length != 0 ) {
				        options.data += '&attachments_files=' + attachment;

						if ( originalOptions.data.content == '' ) {
					        options.data += '&content={{{youzify_comment_attachment}}}';
						}
					}

				} else if ( action == 'messages_send_reply' ) {

					var attachment = $( '#send-reply' ).find( 'input[name="attachments_files[]"]' ).map( function(idx, elem) { return $( elem ).val(); }).get();

					if ( attachment.length != 0 ) {
				        options.data += '&attachments_files=' + attachment;

						if ( originalOptions.data.content == '' ) {
					        options.data += '&content={{{youzify_message_attachment}}}';
						}
					}

				}

			}

		});

		/**
		 * Delete Attachments After Success.
		 */
		$( document ).ajaxSuccess( function( event, xhr, settings ) {
			if ( Object.prototype.toString.call( settings.data ) == '[object String]' ) {
				var response = xhr.responseText;
				if ( response[0] + response[1] != '-1' ) {
					var action = $.youzify_getUrlParameter( settings.data, 'action' );
					if ( action == 'new_activity_comment' ) {
						$( '#ac-form-' + $.youzify_getUrlParameter( settings.data, 'form_id' ) ).find( '.youzify-delete-attachment' ).trigger( 'click' );
						$( '#ac-form-' + $.youzify_getUrlParameter( settings.data, 'form_id' ) ).find( '.youzify-wall-upload-btn' ).fadeIn();
			   		} else if ( action == 'messages_send_reply' ) {
						$( '#send-reply' ).find( '.youzify-delete-attachment' ).trigger( 'click' );
						$( '#send-reply' ).find( '.youzify-upload-btn' ).fadeIn();
			   		}
				}
		   	}
		});

		/**
		 * Submit form to Upload Files.
		 */
		$( document ).on( 'change', '.youzify-upload-attachments', function ( e ) {

		    e.preventDefault();

		    // Get Form.
			var $form = $( this ).closest( 'form' );

			// Hide Comment Upload Button.
			if ( $form.hasClass( 'ac-form' ) ) {
				$form.find( '.youzify-wall-upload-btn' ).fadeOut();
				if ( $form.find( '.youzify-attachment-item' )[0] ) {
					return false;
				}
			}

			// Get Attachment Parent Data.
			var attachments_parent = $( this ).closest( '.youzify-attachments' ),
				upload_button = attachments_parent.find( '.youzify-wall-item-upload' );

			// Hide Form Item Upload Button.
			if ( upload_button[0] ) {
				upload_button.fadeOut( 1 );
				if ( attachments_parent.find( '.youzify-attachment-item' )[0] ) {
					return false;
				}
			}

    		// Get Files.
    		youzify_atts_files = $( this ).get( 0 );

    		// Upload Files.
			$.youzify_UploadFiles( $form, attachments_parent, { 'attachments': youzify_atts_files } );

		});

		/**
		 * Upload Files.
		 */
		$.youzify_UploadFiles = function ( form, attachments_parent, options ) {

			// Get Options.
        	var qto = $.extend({
        		// allowed_extensions : Youzify_Wall.default_extentions,
        		// max_number : 3,
        		// max_size : 3
        	}, options ), dialog;

        	// Get Files.
        	var files = qto.attachments.files;

    		for ( var i = 0; i < files.length ; i++ ) {

				// Get File.
    			var file = files[i];

				// Check Files Number.
				if ( form.hasClass( 'youzify-wall-form' ) ) {
					if ( ! $.youzify_CheckFilesNumber( form ) ) {
						return false;
					}
				} else {
					if( form.find( '.youzify-attachment-item' )[0] ) {
						return false;
					}
				}

        		// Get Attachment Item Html Code.
        		var qt_AttachmentItem = $.youzifyAttachmentItem({
        			'file' : file,
        			'input_name' : attachments_parent.attr( 'data-name' ) ? attachments_parent.attr( 'data-name' ) : 'attachments_files[]',
        			'file_name': file.name
        		});

	        	if ( form.hasClass( 'ac-form' ) ) {
	        		form.find( '.youzify-wall-upload-btn' ).fadeOut(1);
	        	} else if ( form.attr( 'id' ) ==  'send-reply' || form.attr( 'id' ) ==  'send_message_form' ) {
	        		form.find( '.youzify-upload-btn' ).fadeOut(1);
	        	}

        		// Append Item To the Attachments List.
        		attachments_parent.find( '.youzify-form-attachments' ).append( qt_AttachmentItem );

        		// Upload File.
        		if ( i == 0 ) {
        			$.youzify_UploadFile( form, attachments_parent, file );
        		}

			}

		}

		/**
		 * Get Attachment Item HTML Code.
		 */
		$.youzifyAttachmentItem = function ( options ) {

			// Get Option.
			var qto = $.extend( {}, options ), file_code, image_code, file_name;

			// Get File Name.
			file_name = $.youzify_GetNameExcerpt( qto.file_name );

			// Get Files HTML Code.
			file_code =  '<div class="youzify-attachment-item youzify-file-preview">' +
							'<div class="youzify-attachment-details">' +
								'<i class="fas fa-hourglass-half youzify-file-icon"></i>' +
								'<span class="youzify-file-name">' + file_name + '</span>' +
							'</div>' +
							'<div class="youzify-file-progress">' +
								'<span class="youzify-file-upload"></span>' +
							'</div>' +
							'<input type="hidden" class="youzify-attachment-data" name="' + qto.input_name +'" />' +
						'</div>';

			// Get Image Preview HTML Code.
			image_code =  '<div class="youzify-attachment-item youzify-image-preview">' +
							'<div class="youzify-attachment-details">' +
								'<i class="fas fa-hourglass-half youzify-file-icon"></i>' +
							'</div>' +
							'<div class="youzify-file-progress">' +
								'<span class="youzify-file-upload"></span>' +
							'</div>' +
							'<input type="hidden" class="youzify-attachment-data" name="' + qto.input_name +'" />' +
						'</div>';

			// Return Item Code.
			if ( $.youzify_CheckIsFileImage( qto.file ) ) {
				return image_code;
			} else {
				return file_code;
			}

		}

		/**
		 * Upload Attachments.
		 */
		$.youzify_UploadFile = function ( form, attachments_parent, file ) {

			// Get Attachment Item.
			var item = attachments_parent.find( '.youzify-file-progress:first' ).parent( '.youzify-attachment-item' );

			// Create New Form Data.
		    var formData = new FormData();

		    // Fill Form with Data.
		    formData.append( 'file', file );

		    if ( form.hasClass( 'youzify-wall-form' ) ) {
		    	formData.append( 'target', 'activity' );
		    	formData.append( 'post_type', form.find( 'input:radio[name="post_type"]:checked' ).val() );
		    } else if ( form.hasClass( 'ac-form' ) ) {
		    	formData.append( 'target', 'comment' );
		    } else if ( form.closest( 'form' ).attr( 'id' ) == 'send-reply' || form.closest( 'form' ).attr( 'id' ) == 'send_message_form' ) {
		    	formData.append( 'target', 'message' );
		    }

		    formData.append( 'attachments_number', form.find( '.youzify-attachment-item' ).length );
		    formData.append( 'action', 'youzify_upload_wall_attachments' );
		    formData.append( 'security', Youzify.security_nonce );

		    // Upload File.
		    $.ajax({
		        type  : 'POST',
		        url   : Youzify.ajax_url,
		        data  : formData,
		        cache : false,
		        contentType: false,
		        processData: false,
		        xhr: function() {
	                var YouzifyXhr = $.ajaxSettings.xhr();
	                if ( YouzifyXhr.upload ) {

	                	// Disable submit button.
						form.find( '.youzify-wall-post,.youzify-update-post' ).attr( 'disabled', true );

	                    YouzifyXhr.upload.addEventListener( 'progress', function( e ) {
						    if ( e.lengthComputable ) {

						   		// Set up Variables.
						        var max = e.total,
						        	current = e.loaded,
						        	Percentage = ( current * 100 ) / max;

						        // Get Progress Bar
						       	var progress_bar = item.find( '.youzify-file-upload' );

						       	// Upload Started Class.
						       	var youzify_loading_icon = 'fas fa-spinner fa-spin youzify-file-icon';

						       	// Add loader icon
		        				item.find( '.youzify-file-icon' ).attr( 'class', youzify_loading_icon );

						       	// Update Upload status.
						        progress_bar.css( 'width', Percentage  + '%' );

						        if ( Percentage >= 100 ) {
						        	// Change Progress Bar Class .
						        	progress_bar.addClass( 'youzify-file-uploaded' );
						        }

				    		}

	                    });
	                }
	                return YouzifyXhr;
		        },

		        success: function( response ) {

					if ( response.success == false ) {

						// Show Error Message
		            	$.youzify_DialogMsg( 'error', response.data.error );

		            	if ( form.hasClass( 'ac-form' ) ) {
		            		form.find( '.youzify-wall-upload-btn' ).fadeIn();
		            	} else if ( form.attr( 'id' ) == 'send-reply' || form.attr( 'id' ) == 'send_message_form' ) {
		            		form.find( '.youzify-upload-btn' ).fadeIn();
		            	}

		            	// Show Form Custom Post Type Upload Button.
		            	if ( attachments_parent.hasClass( 'youzify-cf-attachment' ) ) {
		            		attachments_parent.find( '.youzify-wall-item-upload' ).fadeIn();
		            	}

		            	// Remove Item.
		            	item.remove();

						// Check Upload Progress to Enable Submit Field.
						$.youzify_CheckUploadProgress( form );

	            		return false;
					}

			        // Prepare Trash Icon
		        	var base_url = response.data.base_url;

		        	// Remove Progress Bar.
		        	item.find( '.youzify-file-progress' ).fadeOut( 400, function() {

		        		// Remove Progress Div.
		        		$( this ).remove();

		        		// Let's Upload Next File.
		        		$.youzify_upload_next_file( form, attachments_parent );

						// Check Upload Progress to Enable Submit Field.
						$.youzify_CheckUploadProgress( form );

		        	});

		        	// Delete Loader Icon.
					if ( $.youzify_CheckIsFileImage( file ) ) {
			        	item.find( '.youzify-file-icon' ).remove();
			        }

			   		// Change Loader Icon with paperclip icon.
		        	item.find( '.youzify-file-icon' ).attr( 'class', 'fas fa-paperclip youzify-file-icon' );

		        	// Add Trash Icon to the attachment item.
		        	item.find( '.youzify-attachment-details' ).append( '<i class="fas fa-trash-alt youzify-delete-attachment"></i>' );

					delete response.data.base_url;

		        	// Update Item Attachments Data.
					item.find( '.youzify-attachment-data' ).val( JSON.stringify( response.data ) );

					var fileReader = new FileReader();

					if ( file.type.match( 'video.*' ) ) {

					    fileReader.onload = function() {

							var blob = new Blob( [ fileReader.result ], { type: file.type });
							var url = URL.createObjectURL( blob );
							var video = document.createElement( 'video' );
							var timeupdate = function() {
								if ( youzify_video_snapImage() ) {
									video.removeEventListener( 'timeupdate', timeupdate );
									video.pause();
								}
							};

							video.addEventListener('loadeddata', function() {
								if ( youzify_video_snapImage() ) {
								 video.removeEventListener( 'timeupdate', timeupdate );
								}
							});

						    var youzify_video_snapImage = function() {
						        var canvas = document.createElement( 'canvas' );
						        canvas.width = video.videoWidth;
						        canvas.height = video.videoHeight;
						        canvas.getContext( '2d' ).drawImage( video, 0, 0, canvas.width, canvas.height );
						        var image = canvas.toDataURL( 'image/jpeg' );
						        var success = image.length > 100000;

						        if ( success ) {
									var video_data = JSON.parse( item.find( '.youzify-attachment-data' ).val() );
									video_data.video_thumbnail = image;
									item.find( '.youzify-attachment-data' ).val( JSON.stringify( video_data ) );
									URL.revokeObjectURL(url);
						        }

						        return success;
						    };

							video.addEventListener( 'timeupdate', timeupdate );
							video.preload = 'metadata';
							video.src = url;
							// Load video in Safari / IE11
							video.muted = true;
							video.playsInline = true;
							video.play();
					    };

					    fileReader.readAsArrayBuffer( file );

					}

					item.css( 'background-image', 'url(' + base_url + 'temp/' +  response.data.original + ')' );

		        },

		        error : function( XMLHttpRequest, textStatus, errorThrown ) {

	            	// Remove Item.
	            	item.remove();

					$.youzify_DialogMsg( 'error', textStatus );

	            	// Check Upload Progress to Enable Submit Field.
					$.youzify_CheckUploadProgress( form );

	            	$.youzify_upload_next_file( form, attachments_parent );

		        }

		    });

		}

		/**
		 * Upload Next File
		 */
		$.youzify_upload_next_file = function( form, attachments_parent ) {

    		// Let's Upload Next File.
    		youzify_atts_count++;

        	if ( youzify_atts_files !== null && typeof youzify_atts_files.files[ youzify_atts_count ] !== 'undefined' ) {
        		$.youzify_UploadFile( form, attachments_parent, youzify_atts_files.files[ youzify_atts_count ] );
        	}

		}


		/**
		 * Delete Attachment .
		 */
        $( document ).on( 'click', '.youzify-delete-attachment' , function( e ) {

        	// Get Form.
        	var form = $( this ).closest( 'form' );

        	// Show Comment / Message Upload Attachment Button.
        	if ( form.hasClass( 'ac-form' ) ) {
        		form.find( '.youzify-wall-upload-btn' ).fadeIn();
        	} else if ( form.attr( 'id' ) ==  'send-reply' || form.attr( 'id' ) ==  'send_message_form' ) {
        		form.find( '.youzify-upload-btn' ).fadeIn();
        	}

			// Show Form Item Upload Button.
			$( this ).closest( '.youzify-attachments' ).find( '.youzify-wall-item-upload' ).fadeIn();

        	// Get Attachment item.
        	var attachment = $( this ).closest( '.youzify-attachment-item' );

        	if ( attachment.find( '.youzify-attachment-data' ).get( 0 ) ) {

	        	// Get File Data.
				var data = $.parseJSON( attachment.find( '.youzify-attachment-data' ).val() );

				// Remove Attachment from Directory.
				$.youzify_DeleteAttachment( data.original );

        	}

			// Remove Attachment from Form.
			attachment.remove();

        });

		/**
		 * Delete Attachment File.
		 */
		$.youzify_DeleteAttachment = function( file ) {

			// Create New Form Data.
		    var formData = new FormData(), atts_nounce;

		    // Fill Form with Data.
		    formData.append( 'attachment', file );
			formData.append( 'security', Youzify.security_nonce );
		    formData.append( 'action', 'youzify_delete_wall_attachment' );

			$.ajax({
                type: "POST",
                data: formData,
                url: ajaxurl,
		        contentType: false,
		        processData: false
			});

		}

		/**
		 * Get File Name Excerpt.
		 */
		$.youzify_GetNameExcerpt = function ( name ) {

		    // Set up Variables.
			var strLen = 25,
		    	separator = '...';

		    // If file name not too long keep it.
		    if ( name.length <= strLen ) {
		    	return name;
		    }

		    // Set up Variables.
		    var sepLen = separator.length,
		        charsToShow = strLen - sepLen,
		        frontChars = Math.ceil(charsToShow/2),
		        backChars = Math.floor(charsToShow/2);

		    // Shorten File Name.
		    return name.substr( 0, frontChars ) + separator + name.substr(name.length - backChars);
		};

		/*
		 * Check If Uploaded File Is Image.
		 **/
		$.youzify_CheckIsFileImage = function( file ) {
			var fileType = file['type'];
			var ValidImageTypes = [ "image/gif", "image/jpeg", "image/png" ];
			if ( $.inArray( fileType, ValidImageTypes ) < 0 ) {
			    return false;
			}
			return true;
		}

		/*
		 * Check Upload Progress !!??
		 **/
		$.youzify_CheckUploadProgress = function( form ) {
			if ( ! form.find( '.youzify-file-progress' )[0] ) {
				form.find( '.youzify-upload-attachments' ).val( '' );
				form.find( '.youzify-wall-actions button[type="submit"]' ).attr( 'disabled' , false );
				// Reset Vars.
				youzify_atts_count = 0;
				youzify_atts_files = null;
			}
		}

		/*
		 * Check Files Number.
		 **/
		// $.youzify_CheckFilesNumber = function( form ) {
		// 	var activity_type = form.find( 'input:radio[name="post_type"]:checked' ).val();
		// 	if ( 'activity_photo' != activity_type && 'activity_poll' != activity_type && 'activity_slideshow' != activity_type && form.find( '.youzify-attachment-item' )[0] ) {
		// 		youzify_atts_files = null;
		// 		$.youzify_DialogMsg( 'error', Youzify_Wall.max_one_file );
		// 		return false;
		// 	}
		// 	return true;
		// }
		$.youzify_CheckFilesNumber = function( form ) {
			var activity_type = form.find( 'input:radio[name="post_type"]:checked' ).val();
			if ( 'activity_photo' != activity_type && 'activity_poll' != activity_type && 'activity_versus_poll'  != activity_type && 'activity_classic_poll'  != activity_type && 'activity_grid_poll'  != activity_type  && 'activity_slideshow' != activity_type && form.find( '.youzify-attachment-item' )[0] ) {
				youzify_atts_files = null;
				$.youzify_DialogMsg( 'error', Youzify_Wall.max_one_file );
				return false;
			}
			return true;
		}

		$.youzify_getUrlParameter = function( data, sParam ) {

		    var sURLVariables = data.split('&'),
		        sParameterName,
		        i;

		    for (i = 0; i < sURLVariables.length; i++) {
		        sParameterName = sURLVariables[i].split('=');

		        if (sParameterName[0] === sParam) {
		            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
		        }
		    }
		}
	});

})( jQuery );