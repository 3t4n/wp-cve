(function( $ ) {
	'use strict';
	
	 $( window ).on('load', function() {
 
       $("form.sform").on('submit', function(event) {
	     // Clear url parameters if the form has been submitted
	     if ( window.location.href.indexOf("sending") > -1 ) {
		    window.history.pushState({}, "", window.location.href.split("&sending")[0]);
	     } 
	     // Clear all invalid fields if any in all forms found on the page
	     $(".sform-field,label.checkbox,div.captcha").removeClass("is-invalid");
	     $("form.sform").removeClass("was-validated");
	     $("form.sform").addClass("needs-validation");
	     $(".msgoutside span").removeClass("v-visible");
	     var form = $(this).attr('form');
	     $(this).addClass("was-validated");
         var submit = true;
	     if( $(this).hasClass("needs-validation") ) { 
           if ($(this)[0].checkValidity() === false ) {
             submit = false;
             $(this).find('.sform-field').each(function(){
	           var parent = $(this).attr('parent');  
	           if ($(this).is(":invalid") && parent == form ) {
                 $(this).addClass("is-invalid");
                 $(this).parent('.captcha').addClass("is-invalid");
               }
             })
             $(this).find('#errors-' + form + ' span').addClass('v-visible');   
 	         if( $('#sform-consent-' + form).prop('required') == true && $('#sform-consent-' + form ).prop("checked") == false ) { 
                 $(this).find('#sform-consent-' + form).addClass("is-invalid");
                 $(this).find('label[for="sform-consent-' + form + '"]').addClass('is-invalid');
             }  
	         if( $(this).hasClass("needs-focus") ) { 
                 $(this).find(":invalid").first().trigger('focus');
	         }
	         else { 
                 $(this).find('#errors-' + form).trigger('focus');
	         }
           }
           else {
               $('#form-' + form).removeClass("needs-validation");
           }
         }
         if ( submit === false ) {
           event.preventDefault();
         }
       });
	
       $( "input,textarea" ).on("input", function() {
	     var form = $(this).attr('parent');
         var field = $(this).attr('id'); 
	     if ( $(this).is(":valid") ) {   
		    $(this).removeClass("is-invalid");
            if ( $(this).prop('required') ) {
               $('label[for='+field+'] span.mark').addClass("d-none"); 
            }
            $('label[for="' + $(this).attr('id') + '"]').removeClass("is-invalid");
            $(this).next().children("span.required-symbol").removeClass("d-block");
            $(this).parent('.captcha').removeClass("is-invalid");            
            
            if( $('#form-' + form).hasClass("needs-validation") ) { 
	          if ( ! $('#form-' + form).find(":invalid").length ) {     
		         $('.message').removeClass("v-visible");
       	      } 
       	    }
       	    else {     
		      if ( ! $('#form-' + form).find(".is-invalid").length ) {  
		         $('.message').removeClass("v-visible");
       	      } 
       	    }
    	 }
    	 else {   
	       if ( $('#form-' + form).hasClass("was-validated") ) {
		    $(this).addClass("is-invalid");
            $('label[for="' + $(this).attr('id') + '"]').addClass("is-invalid");
            $(this).parent('.captcha').addClass("is-invalid");                
            if ( ! $('.message').hasClass("v-visible") ) {
	            var msg = $("input[name=multiple-errors]").val();
   		        $('.message').addClass("v-visible");
    		    $('.message').text(msg);
  		    } 
           }   
           if ( $(this).prop('required') ) {
               $('label[for="'+field+'"] span.mark').removeClass("d-none"); 
               $(this).next().children("span.required-symbol").addClass("d-block");
           }
    	 }
       });

       $(":checkbox").on("click",function() {
  	     if( $(this).prop("checked") == false ) { $(this).val('false'); } 
         else { $(this).val('true'); }
       });  

       $(".sform-field.captcha").on('focus', function() {
         $(this).parent().addClass("focus");  
         $(this).prev().addClass("focus");
         }).on('blur', function() {
         $(this).parent().removeClass("focus");
         $(this).prev().removeClass("focus");
       })      
 
       $('.sform-field.question').on('click',function(){
         $(this).next().trigger('focus');
       })  
       
       $(".sform-field.captcha").on('input', function(e) {
	      var field = $(this).attr('id');
	      var form = $(this).attr('parent');   	 
		  if ( $(this).is(":valid") ) {
			$('label[for="'+field+'"] span.mark').addClass("d-none");
            $(this).removeClass("is-invalid");                      
	        $('#'+field).removeClass("is-invalid");  
            $('#captcha-error-' + form +' span').removeClass("d-block");
	      }
       });
 
       // Prevent zero to be entered as first value in captcha field
       $(".sform-field.captcha").on("keypress", function(e) { 
	     if (e.which === 48 && !this.value.length)
	     e.preventDefault();
	   });  
	   
       // Prevent space to be entered as first value
       $("input, textarea").on("keypress", function(e) {
	     if (e.which === 32 && !this.value.length)
	     e.preventDefault();
	   });    
	     
   	 });

})( jQuery );