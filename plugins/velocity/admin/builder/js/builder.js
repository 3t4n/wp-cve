var velocityBuilder = velocityBuilder || {};

(function($){ 

   velocityBuilder.speed = 200;
   
   var output_div = $('#velocity-shortcode-output'),
       output = '[velocity]',
       old_service_img_url = '';    
       
   output_div.text(output); //Init the shortcode output 
   
   
   // Toggle heading
   $(document).on('click', 'h3.clickable', function(){
		var el = $(this);
		if($(el).hasClass('open')){
			$(el).next('.expand-wrap').slideDown(velocityBuilder.speed, 'easeInOutQuad', function(){
				$(el).removeClass('open');
			});
		}else{
			$(el).next('.expand-wrap').slideUp(velocityBuilder.speed, 'easeInOutQuad', function(){
				$(el).addClass('open');
			});
		}
	});
	
	
	// Select Image
	$('#velocity_upload_btn').click(function(e) {
      e.preventDefault();
      var image = wp.media({ 
         title: window.parent.velocity_localize.image_select,
         multiple: false
      }).open()
      .on('select', function(e){
         
         // This will return the selected image from the Media Uploader, the result is an object
         var selected_image = image.state().get('selection').first();
         var image_id = selected_image.toJSON().id,
             image_size = $('#velocity_image_size').val();
             
         // Let's assign the url value to the input field 
         $('#velocity_image_id').val(image_id);  
          
         velocityBuilder.getImage(image_id, image_size); // Get the correct image         
      });
   });
   
   
   
   // Image size on change
   $('#velocity_image_size').on('change', function(){
      var image_size = $(this).val(),
          image_id = $('#velocity_image_id').val();
          
      if(image_id !== ''){
         velocityBuilder.getImage(image_id, image_size);
      }
   });
   
   
   
   /*
   *  velocityBuilder.build
   *  Build Shortcode
   *
   *  @since 1.0
   */
   velocityBuilder.build = function(){
      output = '[velocity';      
      
      // Get values
      var img = $('#velocity_image_path').val(),
          alt = $('#velocity_image_alt').val(),
          type = $('input[name="velocity_type"]:checked').val(),
          id = $('#velocity_type_id').val(),
          sc_playlist = $('#velocity_soundcloud_playlist').prop('checked'),
          options = $('#velocity_type_options').val(),
          btn = $('input[name="velocity_play_btn"]:checked').val(),
          color = $('#velocity_btn_color').val(),
          bkg_color = $('#velocity_bkg_color').val()
          custom_image = $('input[name="velocity_custom_preview"]:checked').val();   
          
      custom_image = 'true';
      

      if(type){
         output += ' type="'+type.toLowerCase()+'"';
         if(type === 'SoundCloud'){
	         $('#velocity_soundcloud_playlist_wrap').show();
         }else{
	         $('#velocity_soundcloud_playlist_wrap').hide();
	         $('#velocity_soundcloud_playlist').prop('checked', false);	         
         }
         
         $('#velocity_selected_media').text(type);         
      }   
      if(type === 'SoundCloud' && sc_playlist){
	      output += ' playlist="true"';
      }  
      if(options){
         output += ' options="'+options+'"';
      }   
      if(id){
         output += ' id="'+id+'"';
      }
      if(custom_image === 'true'){
         if(img){
            output += ' img="'+img+'"';
         }  
      }
      if(alt){
         output += ' alt="'+alt+'"';
      }  
      
      
      // Preview Image
      if(custom_image === 'true'){
         
         // Custom Image
         $('#velocity-default-preview').slideUp(velocityBuilder.speed, 'easeInOutQuad', function(){
            $('#velocity-custom').slideDown(velocityBuilder.speed, 'easeInOutQuad');
         });  
         
      } else {
         
         // Service Image
         $('#velocity-custom').slideUp(velocityBuilder.speed, 'easeInOutQuad', function(){
            $('#velocity-default-preview').slideDown(velocityBuilder.speed, 'easeInOutQuad', function(){   
               
               var img_url = velocityBuilder.getServiceImageURL(type, id, old_service_img_url);
               console.log(img_url);
               output += ' img="'+ img_url +'"';     
                
            });
         });
                  
      }
      
      
      // Play Button
      if(btn === 'true'){
         $('#velocity_play_btn_controls').slideDown(velocityBuilder.speed, 'easeInOutQuad');
         output += ' color="'+color+'"';
         output += ' bkg_color="'+bkg_color+'"';
         
         $('.velocity-play-btn').show();
         $('.velocity-arrow').css({'border-left-color': color});
         $('.velocity-play-btn').css({'background-color': bkg_color});
         
      }else{
         $('#velocity_play_btn_controls').slideUp(velocityBuilder.speed, 'easeInOutQuad');
         $('.velocity-play-btn').hide();
      }  
        
          
      output += ']';  //Close shortcode          
      output_div.text(output);     
   };  
   
   
   $(document).on('change', '.velocity_element', function() {     
	   var el = $(this); 
      el.addClass('changed');
      velocityBuilder.build();
   });

      
      
   
   /*
   *  velocityBuilder.getImage
   *  Get image src
   *
   *  @since 1.0
   */ 
   
   velocityBuilder.getImage = function(id, size){       	   
	   // Get value from Ajax
	   if(id && size){
   	   $('#velocity-img-selection .velocity-loading').fadeIn(velocityBuilder.speed);
   	   $.ajax({
      		type: 'GET',
      		url: window.parent.velocity_localize.ajaxurl,
      		data: {
      			action: 'velocity_get_image',
      			id: id,
      			size: size,
      			nonce: window.parent.velocity_localize.velocity_nonce,
      		},
      		success: function(data) {  
               $('.velocity-preview-img img').attr('src', data); // Change preview
               $('select#velocity_image_size').removeAttr('disabled'); // enable select
               $('#velocity_image_path').val(data); // Set path
               $('#velocity_image_id').val(id); // Set id
               $('#velocity-img-selection .velocity-loading').fadeOut(velocityBuilder.speed);
               $('.velocity-preview-img .clear-img').show();
               velocityBuilder.build();
      		},
      		error: function(xhr, status, error) {
         		console.log(status);
               $('#velocity-img-selection .velocity-loading').fadeOut(velocityBuilder.speed);
      		}
      	});
   	}
   };
   

   
   /*
   *  velocityBuilder.getServiceImage
   *  Get image from youtube, vimeo, soundcloud
   *
   *  @param type         The type of service
   *  @param id           The id of the video
   *
   *  @since 2.0
   */ 
   
   velocityBuilder.getServiceImageURL = function(type, id, old_service_img_url){
      var url = '';
      if(type && id){
         type = type.toLowerCase();
         if(type === 'youtube'){
            url = 'https://i1.ytimg.com/vi/'+ id +'/maxresdefault.jpg';
            if(url !== old_service_img_url){
               velocityBuilder.getServiceImage(url);
               old_service_img_url = url;
               return old_service_img_url;
            }
         }
         if(type === 'vimeo'){
            var vimeo_json = '//vimeo.com/api/v2/video/' + id + '.json';
            // Get JSON from Vimeo
            $.getJSON( vimeo_json + '?callback=?', {format: "json"}, function(data) {
               if(data){
                  url = data[0].thumbnail_large;
                  if(url !== old_service_img_url){
                     velocityBuilder.getServiceImage(url);
                     old_service_img_url = url;
							return old_service_img_url;
                  }
               }
            });
         }
         if(type === 'soundcloud'){
            
         }
      }        
         
   };
      
      
   
   /*
   *  velocityBuilder.getServiceImage
   *  Get image from youtube, vimeo, soundcloud
   *
   *  @param url         The URL to the img
   *
   *  @since 2.0
   */ 
   
   velocityBuilder.getServiceImage = function(url){
      	   
	   // Get value from Ajax
	   if(url){
   	   var serviceImgHolder = $('#velocity-default-preview');
   	   //$('#velocity-img-selection .velocity-loading').fadeIn(velocityBuilder.speed);
   	   
   	   $.ajax({
      		type: 'GET',
      		url: window.parent.velocity_localize.ajaxurl,
      		data: {
      			action: 'velocity_get_service_image',
      			url: url,
      			nonce: window.parent.velocity_localize.velocity_nonce,
      		},
      		success: function(data) {
         		
         		console.log(data);  
         		if(data){
            		$('#velocity-service-img', serviceImgHolder).html('<img src="'+ data +'" alt="Velocity Video Preview Image" />');
         		} else {
            		alert("cannot locate image");
         		}
               //velocityBuilder.build();
               
      		},
      		error: function(xhr, status, error) {
         		
         		console.log(status);
               $('#velocity-img-selection .velocity-loading').fadeOut(velocityBuilder.speed);
               
      		}
      	});
   	}
   }; 
   
   
   // Clear Preview Img
   $('.velocity-preview-img .clear-img').on('click', function(){
      var el = $(this);
      $('.velocity-preview-img img').attr('src', el.data('placeholder'));
      $('select#velocity_image_size').attr('disabled', 'disabled'); // Disable select
      $('#velocity_image_path').val(''); // Set path
      $('#velocity_image_id').val(''); // Set id
      $('.velocity-preview-img .clear-img').hide();
   }); 
   
   // On change
   $('#velocity_image_url').on('input',function(e){
      $('#img-preview img').attr('src', $('#image_url').val());
   });
	
	
	
	/*
   *  velocityBuilder.SelectText
   *  Click to select text
   *
   *  @since 2.0.0
   */  
   
   velocityBuilder.SelectText = function(element) {
       var doc = document, 
         text = doc.getElementById(element), 
         range, 
         selection;    
       if (doc.body.createTextRange) {
           range = document.body.createTextRange();
           range.moveToElementText(text);
           range.select();
       } else if (window.getSelection) {
           selection = window.getSelection();        
           range = document.createRange();
           range.selectNodeContents(text);
           selection.removeAllRanges();
           selection.addRange(range);
       }
   }
   $('#velocity-shortcode-output').click(function() {
     velocityBuilder.SelectText('velocity-shortcode-output');
   });
	
	
	
	/*
	*  _alm.copyToClipboard
	*  Copy shortcode to clipboard
	*
	*  @since 2.0.0
	*/     
	
	velocityBuilder.copyToClipboard = function(text) {
		window.prompt ("Copy link to your clipboard: Press Ctrl + C then hit Enter to copy.", text);
	}
	
	// Copy link on shortcode builder
	$('.velocity-shortcode-display .copy').click(function(){
		var c = $('#velocity-shortcode-output').html();
		velocityBuilder.copyToClipboard(c);
	});
	
	
	
	/*
   *  velocityBuilder.easeInOutQuad
   *  Easing
   *  @since 1.0.0
   */  
   
	$.easing.easeInOutQuad = function (x, t, b, c, d) {
      if ((t/=d/2) < 1) return c/2*t*t + b;
      return -c/2 * ((--t)*(t-2) - 1) + b;
   }
   
   		
})(jQuery);