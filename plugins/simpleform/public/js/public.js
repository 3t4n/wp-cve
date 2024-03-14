(function( $ ) {
	'use strict';
	
	 $( window ).on('load', function() {

      $("form.sform").on("submit", function (e) { 
	      
	   if( ! $(this).hasClass("needs-validation") && ! $(this).hasClass("block-validation") && $(this).hasClass("ajax") ) {
		   
	      var form = $(this).attr('form');
	      
	      // Declare the variables added with inline scripts as global
  	 	  var outsideMsg = window["outside" + form]; 
	 	  var ajaxError = window["ajax_error" + form];
	      
 	      if ( $( '#spinner-' + form ).length ) {
	         $('#submission-' + form).addClass('d-none');
             $('#spinner-' + form).removeClass('d-none');
          }
         
	      if ( outsideMsg !== true ) {                                            
             $('#error-message-' + form).addClass("v-invisible");
          }
          
	      $('.sform-field, .sform, div.captcha').removeClass('is-invalid');
          $('#errors-' + form + ' span').removeClass('v-visible');   
          var postdata = $('form#form-' + form).serialize();

		  $.ajax({
            type: 'POST',
            dataType: 'json',
            url:ajax_sform_processing.ajaxurl, 
            data: postdata + '&action=formdata_ajax_processing',
            success: function(data){
              $('#spinner-' + form).addClass('d-none');
              $('#submission-' + form).removeClass('d-none');	
	          var error = data['error'];
	          var showerror = data['showerror'];
	          var notice = data['notice'];
	          var label = data['label'];
	          var field = data['field'];
	          var redirect = data['redirect'];
	          var redirect_url = data['redirect_url'];
  	          var field_focus = data['field_focus'];
              if ( error === true ) {
	            $.each(data, function(field, label) {
	            $('#sform-' + field + '-' + form).addClass('is-invalid');
                $('label[for="sform-' + field + '-' + form + '"].sform').addClass('is-invalid');
                $('div#' + field + '-field-' + form).addClass('is-invalid');  
                $('#' + field + '-error-' + form + ' span').text(label);
	            if( $('form#form-' + form).hasClass("needs-focus") ) { $('input.is-invalid, textarea.is-invalid').first().trigger('focus'); }
	            else { $('#errors-' + form).trigger('focus'); }
                });
	            $('#errors-' + form + ' span').addClass('v-visible'); 
	            if ( outsideMsg === true || ( outsideMsg !== true  && showerror === true ) ) {                                        
                 $('#errors-' + form + ' span').removeClass("v-invisible");
                 $('#errors-' + form + ' span').html(data.notice);
                }
	            if ( field_focus === false ) {                                            
                $('#errors-' + form).trigger('focus');
                }
              }
              if( error === false ){	              
                if( redirect === false ){
                  $('#form-' + form +', #sform-introduction-' + form +', #sform-bottom-' + form).addClass('d-none');
                  $('#sform-confirmation-' + form).html(data.notice);
                  $('#sform-confirmation-' + form).trigger('focus');
                }
                else {
	              document.location.href = redirect_url;
                  $('#errors-' + form + ' span').removeClass('v-visible');                                                
                }
              }
            },
 			error: function(data){
              $('#spinner-' + form).addClass('d-none');
              $('#submission-' + form).removeClass('d-none');	            
              $('#errors-' + form + ' span').removeClass("v-invisible");
              $('#errors-' + form + ' span').addClass('v-visible');
              $('#errors-' + form + ' span').html(ajaxError);
              $('#errors-' + form).trigger('focus');
	        } 	
		  });
		  e.preventDefault();
		  return false;
	   }	  
		  
	  });
   
   	 });

})( jQuery );