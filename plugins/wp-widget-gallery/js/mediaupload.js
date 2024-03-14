// JavaScript Document

jQuery(document).ready(function($) { 	

		var file_frame;
		var wp_thumb_post_id = wp.media.model.settings.post.id; // Store the old id
                var set_to_post_id = 0; // Set this
                var wpwidget_arrname;                                
                
                jQuery('#widget-media-container').masonry({
                        columnWidth: jQuery('.widget').width(),
                        itemSelector: 'item',
                        isFitWidth: true,
                        isAnimated: !Modernizr.csstransitions
                }).imagesLoaded(function() {
                        jQuery(this).masonry('reload');
                });
		    
		  jQuery('body').on('click','#wpwidget_media_upload', function( event ){
		 
			event.preventDefault(); 
                         var $this;
			 $this = jQuery(this);
			// If the thumb frame already exists, reopen it.
			if ( file_frame ) {
			  // Set the post ID to what we want
			  file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
			  // Open frame
			  file_frame.open();
			  return;
			} else {
			  // Set the wp.thumb post id so the uploader grabs the ID we want when initialised
			  wp.media.model.settings.post.id = set_to_post_id;
			}
		 
			// Create the thumb frame.
			file_frame = wp.media.frames.file_frame = wp.media({
			  title: jQuery( this ).data( 'uploader_title' ),
			  button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			  },
			  multiple: true  // Set to true to allow multiple files to be selected
			});
		 
			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
			  // We set multiple to false so only get one image from the uploader
			  //attachment = file_frame.state().get('selection').first().toJSON();
			  attachments = file_frame.state().get('selection').toJSON();	
		 
			  // Do something with attachment.id and/or attachment.url here
			  // Iterate through the attachments...
                          var _arr    = new Array();
                          var _cnt    = jQuery('.wpwidgetgallery li').length -1;
                          var _newval = getCookie('image_array');                                                 
			  jQuery.each(attachments, function(i, attachment){
                                if ( undefined === attachment.sizes.thumbnail ){
                                        var _attachment_url = attachment.url;
                                }else{
                                        var _attachment_url = attachment.sizes.thumbnail.url;
                                }
   
                                if ( !_newval ){
                                      _newval = attachment.id;
                                }else{                                                                                
                                      _newval = _newval + ',' + attachment.id;                                      
                                }

                                if ( jQuery('.wpwidgetgallery').length == 0 ){
                                    jQuery('<ul class="wpwidgetgallery"></ul>').appendTo('.wp-widget-gal');                                
                                    jQuery('.wpwidgetgallery').append('<li style="display:inline-block;padding:5px;"><img src="'+  _attachment_url +'" data-attachment_id="'+ attachment.id +'" width="80" height="80"><div class="wpwidgetoverlay"><a href="#" data-attachment_id ="'+attachment.id+'" id="'+_cnt+'" class="wpwidget_rem_img">remove</a></div></li>');                                                                    
                                    _cnt++;
                                }else{
                                    jQuery('.wpwidgetgallery').append('<li style="display:inline-block;padding:5px;"><img src="'+  _attachment_url +'" data-attachment_id="'+ attachment.id +'" width="80" height="80"><div class="wpwidgetoverlay"><a href="#" data-attachment_id ="'+attachment.id+'" id="'+_cnt+'" class="wpwidget_rem_img">remove</a></div></li>');                                                                    
                                    _cnt++;
                                }    
                                      
                          });
                          
                          _arr.push( _newval );                                        
                          setCookie ('image_array',_arr);
                          jQuery('.wpwidget_arr').val( getCookie('image_array')  );
                          // Restore the main post ID
			  wp.media.model.settings.post.id = wp_thumb_post_id;
			});
		 
			// Finally, open the modal
			file_frame.open();
		  });
	
		  // Restore the main ID when the add thumb button is pressed
		  jQuery('a.add_thumb').on('click', function() {
			wp.media.model.settings.post.id = wp_thumb_post_id;
		  });
	 
	 	 				
		jQuery('body').on('click','.wpwidget_rem_img', function(event){
			
			event.preventDefault();	                          
                        var _remid = jQuery(this).attr('data-attachment_id');

                        jQuery(this).parent().parent().fadeOut('slow', 'swing', function(){                             
                            jQuery(this).remove()
                                var _newarr = JSON.parse("[" + getCookie('image_array') + "]");                                                 
                                var index = _newarr.indexOf(parseInt(_remid));
                                _newarr.splice(index, 1);
                                setCookie ('image_array',_newarr);
                                jQuery('.wpwidget_arr').val( getCookie('image_array')  );                            
                        });
//				jQuery.ajax({
//					url 	: wpwidgetAjax.ajaxurl,
//					data : {
//						action 		: 'wpwidget_remove_attachment_thumb',
//						security 	: jQuery('#wpwidget_delete_attachment_nonce_thumb').val(),
//						postid 		: _id,
//						parent 		: _pid
//					}, 
//					success : function(response){
//						if (response){
//							$this.offsetParent().parent().fadeOut('slow', 'swing', function(){
//									jQuery(this).remove();
//							});
//						}else{
//							alert('Unable to remove image, please try again');
//						}
//					},
//					error : function(err){
//						alert( 'Error : ' + err );
//					}
//				});
		});	
        
        
                        
        function setCookie(c_name,value,exdays)
        {
            var exdate=new Date();
            exdate.setDate(exdate.getDate() + exdays);
            var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
            document.cookie= c_name + "=" + c_value;
        }
        
        function getCookie(c_name)
        {
            var c_value = document.cookie;
            var c_start = c_value.indexOf(" " + c_name + "=");
            if (c_start == -1)
              {
              c_start = c_value.indexOf(c_name + "=");
              }
            if (c_start == -1)
              {
              c_value = null;
              }
            else
              {
              c_start = c_value.indexOf("=", c_start) + 1;
              var c_end = c_value.indexOf(";", c_start);
              if (c_end == -1)
              {
            c_end = c_value.length;
            }
            c_value = unescape(c_value.substring(c_start,c_end));
            }
            return c_value;
        }
		
});//End of document ready