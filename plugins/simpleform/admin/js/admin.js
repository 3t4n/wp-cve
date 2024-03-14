(function( $ ) {
	'use strict';
	
	 $( window ).on('load', function() {

       $("ul#submissions-data").on('mouseenter', function() {
          $('#last-submission').addClass('unseen');
          $('#submissions-notice').removeClass('unseen');
       });
       
       $("ul#submissions-data").on('mouseleave', function() {
          $('#last-submission').removeClass('unseen');
          $('#submissions-notice').addClass('unseen');
       });
       
       $('#shortcode-copy').on( 'click', function() {
	     event.preventDefault();
         var tempInput = document.createElement('input');
         tempInput.style = "position: absolute; left: -1000px; top: -1000px";
         document.body.appendChild(tempInput);
         tempInput.value = $('#shortcode').text();
         tempInput.select();
         document.execCommand("copy");
         document.body.removeChild(tempInput);
         $('#shortcode-tooltip').text(ajax_sform_settings_options_object.copied);
         $('#shortcode-tooltip').removeClass('unseen');
         setTimeout(function(){ $('#shortcode-tooltip').addClass('unseen'); $('#shortcode-tooltip').text(ajax_sform_settings_options_object.copy); }, 2000); 
       });  
       
       $( "#shortcode-copy" ).on('mouseenter', function() { $('#shortcode-tooltip').removeClass('unseen'); } );     
       
       $( "#shortcode-copy" ).on('mouseleave', function() { $('#shortcode-tooltip').addClass('unseen'); } );     
       
	   $('#show-for').on('change', function() {
         var user = $(this).val();
         var url = $(location).attr('href');
         var redirect_url = url + '&showfor=' + user;
         document.location.href = redirect_url;
       });

       $('#name-field').on('change', function() {
         var selectVal = $("#name-field option:selected").val();
         if ( selectVal == 'hidden' ) { $('.trname').addClass('unseen'); }
         else { $('.trname').removeClass('unseen'); 
	        if($('#namelabel').prop('checked') == true) { 
            $('tr.namelabel').addClass('unseen'); 
            } else { 
	        $('tr.namelabel').removeClass('unseen'); 
            }
	     }
       });          

       $('#lastname-field').on('change', function () {
         var selectVal = $("#lastname-field option:selected").val();
         if ( selectVal == 'hidden' ) { $('.trlastname').addClass('unseen'); }
         else { $('.trlastname').removeClass('unseen');
	        if($('#lastnamelabel').prop('checked') == true) { 
            $('tr.lastnamelabel').addClass('unseen'); 
            } else { 
	        $('tr.lastnamelabel').removeClass('unseen'); 
            }
	     }
       });          

       $('#email-field').on('change', function () {
         var selectVal = $("#email-field option:selected").val();
         if ( selectVal == 'hidden' ) { $('.tremail').addClass('unseen'); }
         else { $('.tremail').removeClass('unseen'); 
	        if($('#emaillabel').prop('checked') == true) { 
            $('tr.emaillabel').addClass('unseen'); 
            } else { 
	        $('tr.emaillabel').removeClass('unseen'); 
            }
         }
       });          

       $('#phone-field').on('change', function () {
         var selectVal = $("#phone-field option:selected").val();
         if ( selectVal == 'hidden' ) { $('.trphone').addClass('unseen'); }
         else { $('.trphone').removeClass('unseen'); 
	        if($('#phonelabel').prop('checked') == true) { 
            $('tr.phonelabel').addClass('unseen'); 
            } else { 
	        $('tr.phonelabel').removeClass('unseen'); 
            }
	     }
       });          

       $('#subject-field').on('change', function () {
         var selectVal = $("#subject-field option:selected").val();
         if ( selectVal == 'hidden' ) { $('.trsubject').addClass('unseen'); }
         else { $('.trsubject').removeClass('unseen'); 
	        if($('#subjectlabel').prop('checked') == true) { 
            $('tr.subjectlabel').addClass('unseen'); 
            } else { 
	        $('tr.subjectlabel').removeClass('unseen'); 
            }
         }
       });          

       $('#captcha-field').on('change', function () {
         var selectVal = $("#captcha-field option:selected").val();
         if ( selectVal == 'hidden' ) { $('.trcaptchalabel').addClass('unseen'); }
         else { $('.trcaptchalabel').removeClass('unseen'); }
       });          
       
       $('#preference-field').on('change', function () {
         var selectVal = $("#preference-field option:selected").val();
         if ( selectVal == 'hidden' ) { $('.trpreference').addClass('unseen'); }
         else { $('.trpreference').removeClass('unseen'); }
       }); 
       
       $('#consent-field').on('change', function () {
         var selectVal = $("#consent-field option:selected").val();
         if ( selectVal == 'hidden' ) { $('.trconsent').addClass('unseen'); }
         else { $('.trconsent').removeClass('unseen'); 
           if($('#privacy-link').prop('checked') == true) { 
           $('.trpage').removeClass('unseen'); 
           } 
           else { 
	       $('.trpage').addClass('unseen'); 
           }
         }
       });
       
       $("#privacy-link").on("click", function() {
 	     var label = $('#consent-label').val();
         var string = ajax_sform_settings_options_object.privacy;
         if($(this).prop('checked') == true) { 
          $('.trpage').removeClass('unseen'); 
         } 
         else { 
	      $('.trpage').addClass('unseen'); 
          var pattern = new RegExp('<a [^>]*>' + string + '<\/a>', 'i');
          var nolink = label.replace(pattern, string);
          $('#consent-label').val(nolink);
          $('#privacy-page').val('');
          $('#set-page, #set-page-icon').addClass('unseen'); 
          $('#set-page, #set-page-icon').attr('page',0);
          $('#post-status').html('&nbsp;');
         }
       });
       
       $('#privacy-page').on('change', function () {
          var selectVal = $(this).val();
          var page = $('#set-page').attr('page');
          if ( selectVal != '' ) { 
	        $('#page-id').val(selectVal);       
	        if ( selectVal == page ) { $('#set-page, #set-page-icon').addClass('unseen'); } 
	        else { $('#set-page, #set-page-icon').removeClass('unseen'); } 
	      } 
          else { $('#set-page, #set-page-icon').addClass('unseen'); $('#privacy-link').trigger('click'); }
       });          
              
       $('#set-page').on( 'click', function(e) {
          $('#label-error').html('');
          var string = $('textarea[name="consent-label"]').val();
          var id = $('input[name="page-id"]').val();
          var nonce = $('input[name="verification_nonce"]').val();
		  $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_sform_settings_options_object.ajaxurl,
            data: {
              action: "setting_privacy",
              'verification_nonce': nonce,
	          'page-id': id,
	          'consent-label': string,
            },    
            success: function(data){
	          if( data.error === true ){
                $('#label-error').html('Error occurred during creation of the link');
              }
	          if( data.error === false ){                
                $('#consent-label').val(data.label);
                $('#set-page').addClass('unseen');
                $('#set-page').attr('page',id);
              }
            },
 			error: function(data){
              $('#label-error').html('Error occurred during creation of the link');
	        } 	
		  });
		  e.preventDefault();
		  return false;
	   });

       $('#set-page-icon').on( 'click', function(e) {
          var string = $('textarea[name="consent-label"]').val();
          var id = $('input[name="page-id"]').val();
          var nonce = $('input[name="verification_nonce"]').val();
		  $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_sform_settings_options_object.ajaxurl,
            data: {
              action: "setting_privacy",
              'verification_nonce': nonce,
	          'page-id': id,
	          'consent-label': string,
            },    
            success: function(data){
	          if( data.error === true ){
                $('#label-error-top').html('Error occurred during creation of the link');
              }
	          if( data.error === false ){                
                $('#consent-label').val(data.label);
                $('#set-page-icon').addClass('unseen');
                $('#set-page-icon').attr('page',id);
              }
            },
 			error: function(data){
              $('#label-error-top').html('Error occurred during creation of the link');
	        } 	
		  });
		  e.preventDefault();
		  return false;
	   });
	   
       $(".field-label").on("click", function() {
         var labelID = $(this).attr('id')
         if($(this).prop('checked') == true) { 
         $('tr.'+labelID).addClass('unseen'); 
         } 
         else { 
	     $('tr.'+labelID).removeClass('unseen'); 
         }
       });
       
       $("#required-sign").on("click", function() {
          if($(this).prop('checked') == true) { 
	      $('.trsign').addClass('unseen');
          } 
          else { 
          $('.trsign').removeClass('unseen'); 
          } 
       });
       
        $("#optional-labelling").on("click", function() { 
	      var word = $('#required-word').val();
          if( word == ajax_sform_settings_options_object.required ) { 
	        $('#required-word').val(ajax_sform_settings_options_object.optional); 
          }
       });      
       
        $("#required-labelling").on("click", function() { 
	      var word = $('#required-word').val();
          if( word == ajax_sform_settings_options_object.optional ) { 
	        $('#required-word').val(ajax_sform_settings_options_object.required); 
          }
       });      
       
       $(".nav-tab").on("click", function() {
          var SettingsID = $(this).attr('id')
	      $( ".nav-tab-active" ).removeClass( "nav-tab-active" );
	      $( ".navtab" ).addClass('unseen');  
          $( '#tab-' + SettingsID ).removeClass('unseen');	   
	      $( this ).addClass( "nav-tab-active" );
	      if ( SettingsID == 'appearance' ) { $(".editorpage").text(ajax_sform_settings_options_object.appearance); }
	      else { $(".editorpage").text(ajax_sform_settings_options_object.builder); }
       });
       
       $("#widget-editor").on("click", function() {
          if($(this).prop('checked') == true) { 
	       $('.trwidget').addClass('unseen');
         } 
          else { 
           $('.trwidget').removeClass('unseen'); 
        } 
       });

       $('#form-template').on('change', function () {
          var selectVal = $("#form-template option:selected").val();
	      // if ( selectVal == 'transparent' ) { $("#form-borders").removeClass('unseen'); }
          // else { $("#form-borders").addClass('unseen'); }
	      if ( selectVal == 'customized' ) { $("#template-notice").text(ajax_sform_settings_options_object.notes); }
          else { $("#template-notice").html('&nbsp;'); }
       });          
       
       $("#stylesheet").on("click", function() {
          if($(this).prop('checked') == true) { 
	      $('.trstylesheet').removeClass('unseen');
          } 
          else { 
          $('.trstylesheet').addClass('unseen'); 
          } 
       });
      
	   $("#stylesheet-file").on("click", function() {
         if($(this).prop('checked') == true) { 
			$('#stylesheet-description').html(ajax_sform_settings_options_object.cssenabled); 
	     } 
	     else { 
		    $('#stylesheet-description').html(ajax_sform_settings_options_object.cssdisabled); 
		 }
       });
       
	   $("#javascript").on("click", function() {
         if($(this).prop('checked') == true) { 
			$('#javascript-description').html(ajax_sform_settings_options_object.jsenabled); 
	     } 
	     else { 
		    $('#javascript-description').html(ajax_sform_settings_options_object.jsdisabled); 
		 }
       });
       
       $('#outside-error').on('change', function () {
          var selectVal = $("#outside-error option:selected").val();
	      var text = $("label#focusout").html();
          if ( selectVal != 'none' ) {
			  $('.out').each(function(i, obj) {
              var elem = $( obj );
  	          if ( selectVal == 'top' ) { var placeholder = elem.attr("placeholder").replace(ajax_sform_settings_options_object.bottom, ajax_sform_settings_options_object.top); }
	          if ( selectVal == 'bottom' ) { var placeholder = elem.attr("placeholder").replace(ajax_sform_settings_options_object.top, ajax_sform_settings_options_object.bottom); }
    	      console.log(elem.attr("placeholder"));
	          elem.attr("placeholder", placeholder);
          });
          }
	      if ( selectVal == 'top' ) {
                 $("label#focusout").html(text.replace(ajax_sform_settings_options_object.nofocus, ajax_sform_settings_options_object.focusout));
		         $("#outside-notice").text(ajax_sform_settings_options_object.topnotes);
                 $('.trout').removeClass('removed');
                 if ( $('#trcaptcha').hasClass('unseen') ) { 
    	           $('.messagecell').removeClass('last'); 
	             }
	             else { 
	               $('.captchacell').removeClass('last'); 
	             }
		  }
	      else if ( selectVal == 'bottom' ) {
                 $("label#focusout").html(text.replace(ajax_sform_settings_options_object.nofocus, ajax_sform_settings_options_object.focusout));
		         $("#outside-notice").text(ajax_sform_settings_options_object.bottomnotes);
	             $('.trout').removeClass('removed');
                 if ( $('#trcaptcha').hasClass('unseen') ) { $('.messagecell').removeClass('last'); }
	             else { $('.captchacell').removeClass('last'); }
		  }
          else { 
                 $("label#focusout").html(text.replace(ajax_sform_settings_options_object.focusout, ajax_sform_settings_options_object.nofocus));
                 $("#outside-notice").html('&nbsp;');
	             $('.trout').addClass('removed');
                 if ( $('#trcaptcha').hasClass('unseen') ) {  $('.messagecell').addClass('last'); }
	             else { $('.captchacell').addClass('last'); }
	      }		
       });      
       
	   $("#characters-length").on("click", function() {
         if($(this).prop('checked') == true) { 
			$('#characters-description').html(ajax_sform_settings_options_object.showcharacters); 
		    $('#incomplete-name').val(ajax_sform_settings_options_object.numnamer);
		    $('#incomplete-lastname').val(ajax_sform_settings_options_object.numlster);
		    $('#incomplete-subject').val(ajax_sform_settings_options_object.numsuber);
		    $('#incomplete-message').val(ajax_sform_settings_options_object.nummsger);
	     } 
	     else { 
		    $('#characters-description').html(ajax_sform_settings_options_object.hidecharacters); 
		    $('#incomplete-name').val(ajax_sform_settings_options_object.gennamer);
		    $('#incomplete-lastname').val(ajax_sform_settings_options_object.genlster);
		    $('#incomplete-subject').val(ajax_sform_settings_options_object.gensuber);
		    $('#incomplete-message').val(ajax_sform_settings_options_object.genmsger);
		 }
       });
       
       $("#ajax-submission").on("click", function() {
          if($(this).prop('checked') == true) { 
	       $('.trajax').removeClass('unseen');
         } 
          else { 
           $('.trajax').addClass('unseen'); 
        } 
       });

       $("#confirmation-message").on("click", function() {
         if($(this).prop('checked') == true) { 
         $('.trsuccessmessage').removeClass('unseen'); 
         $('.trsuccessredirect').addClass('unseen'); 
         $('#confirmation-page').val('');
         $('#post-status').html('&nbsp;');
         } 
       });

       $("#success-redirect").on("click", function() {
         if($(this).prop('checked') == true) { 
         $('.trsuccessmessage').addClass('unseen'); 
         $('.trsuccessredirect').removeClass('unseen'); 
         } 
       });

       $('#confirmation-page, #privacy-page').on('change', function () {
	     var element = $(this).find('option:selected'); 
         var value = element.attr("value"); 
         var Tag = element.attr("Tag"); 
         if ( Tag == 'draft' ) { 
             $("#post-status").html(ajax_sform_settings_options_object.status + ' - <strong><a href="'+ ajax_sform_settings_options_object.adminurl +'post.php?post=' + value + '&action=edit" target="_blank" style="text-decoration: none; color: #9ccc79;">' + ajax_sform_settings_options_object.publish + '</a></strong>');
	     }
         else { 
	       if ( value != '' ) {
             var editlink = '<strong><a href="'+ ajax_sform_settings_options_object.adminurl +'post.php?post=' + value + '&action=edit" target="_blank" style="text-decoration: none;">' + ajax_sform_settings_options_object.edit + '</a></strong>';
             var viewlink = '<strong><a href="'+ ajax_sform_settings_options_object.pageurl +'/?page_id=' + value + '" target="_blank" style="text-decoration: none;">' + ajax_sform_settings_options_object.view + '</a></strong>';
             var link = ajax_sform_settings_options_object.pagelinks.replace(ajax_sform_settings_options_object.edit, editlink);
             var links = link.replace(ajax_sform_settings_options_object.view, viewlink);
             $("#post-status").html(links);
           }
           else {
             $("#post-status").html('&nbsp;');
           }
	     }		         
       });          

       $(".notice-toggle").on("click", function() {
         if( $('.smpt-warnings').hasClass('unseen') ) { $("#smpt-warnings").text(ajax_sform_settings_options_object.hide); $('.smpt-settings').addClass('unseen'); $('.smpt-warnings').removeClass('unseen'); } 
         else { 
	       $("#smpt-warnings").text(ajax_sform_settings_options_object.show); 
	       $('#trsmtpon').removeClass('unseen'); 
	       $('.smpt-warnings').addClass('unseen'); 
	       if( $('#server-smtp').prop('checked') == true ){ 
		       $('.trsmtp').removeClass('unseen'); 
		       if( $('#smtp-authentication').prop('checked') == true ){ $('.trauthentication').removeClass('unseen'); } 
		       else { $('.trauthentication').addClass('unseen'); } 
		   } 
		   else { $('.trsmtp').addClass('unseen'); } 
         }
       });

       $("#server-smtp").on("click", function() {
         if($(this).prop('checked') == true) {
           $('.trsmtp').removeClass('unseen'); 
           $('#tdsmtp').removeClass('last');
           $('#smtp-notice').text(ajax_sform_settings_options_object.smtpnotes);           
           if ($('#smtp-authentication').prop('checked') == true) { $('.trauthentication').removeClass('unseen'); } 
           else { $('.trauthentication').addClass('unseen'); } 
         } 
         else { 
           $('.trsmtp').addClass('unseen'); 
           $('#tdsmtp').addClass('last');
           $('#smtp-notice').text('');          
         }
       });

       $("#smtp-authentication").on("click", function() {
          if($(this).prop('checked') == true) { 
	      $('#tdauthentication').removeClass('last'); 
	      $('.trauthentication').removeClass('unseen'); 
          } 
          else { 
	      $('#tdauthentication').addClass('last'); 
	      $('.trauthentication').addClass('unseen'); 
          }  
       });
       
       $("#notification").on("click", function() {
          if($(this).prop('checked') == true) { 
          $('.trnotification').removeClass('unseen'); 
          $('#tdnotification').removeClass('last');
            if ($('#custom-name').prop('checked') == true) { $('.trcustomname').removeClass('unseen'); } 
            else { $('.trcustomname').addClass('unseen'); } 
            if( $('#default-subject').prop('checked') == true){ $('.trcustomsubject').removeClass('unseen'); } 
            else { $('.trcustomsubject').addClass('unseen'); } 
          } 
          else { 
          $('.trnotification').addClass('unseen'); 
          $('#tdnotification').addClass('last');
          }
       });

       $("#requester-name").on("click", function() {
         if($(this).prop('checked') == true) { $('.trcustomname').addClass('unseen'); } 
         else { $('.trcustomname').removeClass('unseen'); }
       });
       
       $("#form-name").on("click", function() {
         if($(this).prop('checked') == true) { $('.trcustomname').addClass('unseen'); } 
         else { $('.trcustomname').removeClass('unseen'); }
       });
       
       $("#custom-name").on("click", function() {
         if($(this).prop('checked') == true) { $('.trcustomname').removeClass('unseen'); } 
         else { $('.trcustomname').addClass('unseen'); }
       });

       $("#request-subject").on("click", function() {
         if($(this).prop('checked') == true) { $('.trcustomsubject').addClass('unseen'); } 
         else { $('.trcustomsubject').removeClass('unseen'); }
       });
       
       $("#default-subject").on("click", function() {
         if($(this).prop('checked') == true) { $('.trcustomsubject').removeClass('unseen'); } 
         else { $('.trcustomsubject').addClass('unseen'); }
       });

       $("#autoresponder").on("click", function() {
         if($(this).prop('checked') == true) { 
           $('.trauto').removeClass('unseen'); 
           $('#tdconfirmation').removeClass('last');
        } 
         else { 
	       $('.trauto').addClass('unseen'); 
           $('#tdconfirmation').addClass('last');
         }
       });
       
	   $('#save-settings').on( 'click', function(e) {
	      $('.message').removeClass('error success unchanged');
	      $('.message').addClass('seen');
          $('.message').html(ajax_sform_settings_options_object.loading);
          var formData = $('form#settings').serialize();
		  $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_sform_settings_options_object.ajaxurl,
            data: formData + '&action=sform_edit_options', 
            success: function(data){
	          var error = data['error'];
	          var message = data['message'];
	          var update = data['update'];
	          if( error === true ){
	            $('.message').addClass('error');
                $('.message').html(data.message);
              }
	          if( error === false ){
                $('.message').html(data.message);
	            if( update === false ) { 
		            $('.message').addClass('unchanged');
		        }
	            if( update === true ) { 
		            $('.message').addClass('success');
		        }
              }
            },
 			error: function(data){
              $('.message').html('AJAX call failed');
	        } 	
		  });	
		  e.preventDefault(); 
		  return false;
	   });

       $(document).on('change', 'input[type=checkbox], input[type=radio], select', function() {
	      $('.message').removeClass('seen error success unchanged');
       });

       $(document).on('input', 'input[type=text], input[type=email], textarea', function() {
	      $('.message').removeClass('seen error success unchanged');
       });

       $('#save-attributes').on( 'click', function(e) {
	      $('.message').removeClass('error success unchanged');
	      $('.message').addClass('seen');
          $('.message').text(ajax_sform_settings_options_object.saving);
          var formData = $('form#attributes').serialize();
		  $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_sform_settings_options_object.ajaxurl,
            data: formData + '&action=shortcode_costruction', 
            success: function(data){
	          var error = data['error'];
	          var message = data['message'];
	          var update = data['update'];
	          var redirect = data['redirect'];
	          var url = data['url'];
	          if( error === true ){
	            $('.message').addClass('error');
                $('.message').html(data.message);
              }
	          if( error === false ){
                $('.message').html(data.message);
	            if( update === false ){
	              $('.message').addClass('unchanged');
                }
	            if( update === true ){
	              if( redirect === true ){
                    document.location.href = url;	              
		          }
		          else {
	                $('.message').addClass('success');
		          }
                }
              }
            },
 			error: function(data){
              $('.message').html('AJAX call failed');
	        } 	
		  });
		  e.preventDefault();
		  return false;
	   });
	
	   $(document).on('change','.sform-target',function(){
         var selectVal = $(this).val();
         var field = $(this).attr('field');
         if ( selectVal === 'in' ) { 
	         $('p#usertype.role-'+field).removeClass('unseen');
 	     }
         else {
	         $('p#usertype.role-'+field).addClass('unseen'); 
	     }
       });       

	   $(document).on('change','.sfwidget',function(){
 	     var box = $(this).attr('box');
         var selectVal = $(this).val();
         if ( selectVal === 'all' ) { 
	         $('div#sform-widget-hidden-pages.'+box).addClass('unseen');
 	         $('div#sform-widget-visible-pages.'+box).addClass('unseen');
 	         $('p#visibility-notes').removeClass('unseen');
 	         $('p#visibility').addClass('visibility');
 	     }
         else {
	         $('p#visibility-notes').addClass('unseen'); 
 	         $('p#visibility').removeClass('visibility');
 
	         if ( selectVal === 'hidden' ) {
	         $('div#sform-widget-hidden-pages.'+box).removeClass('unseen'); 
	         $('div#sform-widget-visible-pages.'+box ).addClass('unseen');
	         }
	         else {
	         $('div#sform-widget-hidden-pages.'+box).addClass('unseen'); 
	         $('div#sform-widget-visible-pages.'+box ).removeClass('unseen');
	         }
	     }
       });       
       
	   $('#form').on('change', function() {
         var id = $(this).val();
         var url = $(location).attr('href');
         var urlParams = new URLSearchParams(url);
         var currentid = urlParams.get('form');
         if (url.indexOf('form=') > -1) {
	       if (id) {
           var redirect_url = url.replace('&form=' + currentid, '&form=' + id);
           }
           else {
           var redirect_url = url.replace('&form=' + currentid, '');
           }
         } else {
	       if (id) {
           var redirect_url = url + '&form=' + id;
           }
           else {
           var redirect_url = url;
           }
         }
         var currentpage = urlParams.get('paged');
         if (redirect_url.indexOf('paged=') > -1) {
           var clean_url = redirect_url.replace('&paged=' + currentpage, '');
         } else {
           var clean_url = redirect_url;
         }
         document.location.href = clean_url;
        });
        
       $(".cbfield").on("click", function() {
	     var field = $(this).attr('field');
         if($(this).prop('checked') == true) { 
         $('.tr'+field).addClass('secret'); 
         } 
         else { $('.tr'+field).removeClass('secret'); 
	        if($('#'+field+'label').prop('checked') == true) { 
            $('tr.'+field+'label').addClass('unseen'); 
            } else { 
	        $('tr.'+field+'label').removeClass('unseen'); 
            }
	     }
       });

	   $("#admin-notices").on("click", function() {
         if($(this).prop('checked') == true) { 
			$('.admin-notices').addClass('invisible'); 
	     } 
	     else { 
		    $('.admin-notices').removeClass('invisible'); 
		 }
       });
       
       $("#duplicate").on("click", function() {
         if($(this).prop('checked') == true) { 
	       $('.trduplicate').removeClass('unseen');
         } 
         else { 
           $('.trduplicate').addClass('unseen'); 
         } 
       });
       
       $(".sform-switch").on("click",function () {
	     if($(this).prop('checked') == false ) { $(this).val('false'); } 
         else { $(this).val('true'); }
       });  
       
       $('#admin-color').on('change', function(e){
	     var selectVal = $(this).val();
         var usedColor = $("#settings").attr("class");
         var nonce = $('input[name="verification_nonce"]').val();
		 $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_sform_settings_options_object.ajaxurl,
            data: {
              action: "admin_color_scheme",
              'verification_nonce': nonce,
	          'admin-color': selectVal,
            },    
            success: function(data){
	          var error = data['error'];
	          var color = data['color'];
	            
	          if( data.error === true ){
                $('#label-error').html('Error occurred during creation of the link');
              }
	          if( data.error === false ){                
                $('.full-width-bar, h1.title, select#form, .form-button, #settings, #smpt-warnings, .icon-button').removeClass(usedColor); 
                $('.full-width-bar, h1.title, select#form, .form-button, #settings, #smpt-warnings, .icon-button').addClass(color); 
              }
            },
 			error: function(data){
                $('#label-error').html('Error occurred during creation of the link');
	        } 	
		  });
		  e.preventDefault();
		  return false;
	   });

       $("span.heading").on("click", function() {
 	     var section = $(this).attr('section');
         $('.section.' + section).toggleClass('collapsed');
	      if ( ! $('.section.' + section).hasClass('collapsed') ) {  
            $('span.toggle.' + section).removeClass('dashicons-arrow-down-alt2');
            $('span.toggle.' + section).addClass('dashicons-arrow-up-alt2');
            $('#h2-' + section).removeClass('closed');
          } 
          else{
            $('span.toggle.' + section).removeClass('dashicons-arrow-up-alt2');
            $('span.toggle.' + section).addClass('dashicons-arrow-down-alt2');
            $('#h2-' + section).addClass('closed');
          }
       });
       
       if( window.location.href.indexOf("#css") > -1 ) {
          document.getElementById('appearance').click();
          document.getElementById('additional-css').focus();
       }
       
       $("#deletion-toggle, .cancel.delete").on("click", function() {
         if( $('#deletion-notice').hasClass('unseen') ) { 
	         $('#alert-wrap').addClass('unseen'); 
	         $('#form-buttons').addClass('unseen'); 
	         $('#deletion-toggle').addClass('unseen'); 
	         $('#deletion-notice').removeClass('unseen'); 
 	         $('span#confirm').addClass('unseen');
	     } 
         else { 
	       $('#alert-wrap').removeClass('unseen'); 
	       $('#form-buttons').removeClass('unseen'); 
	       $('#deletion-toggle').removeClass('unseen');
	       $('#deletion-notice').addClass('unseen'); 
 	       $('span#default').removeClass('unseen');
 	       $('#deletion-notice').removeClass('confirm');
 	       $('#confirmation').val('');
        }
       });
       
       $("#deletion-form").on("click", function() {
         if($(this).prop('checked') == true) { 
	       $('#deletion-toggle').removeClass('unseen');
         } 
         else { 
           $('#deletion-toggle').addClass('unseen'); 
	       $('#alert-wrap').removeClass('unseen'); 
	       $('#form-buttons').removeClass('unseen'); 
	       $('#deletion-notice').addClass('unseen'); 
 	       $('span#default').removeClass('unseen');
 	       $('#deletion-notice').removeClass('confirm');
 	       $('#confirmation').val('');
         } 
       });
 
       $('#deletion-confirm').on( 'click', function(e) {
         var formData = $('form#deletion').serialize();
		 $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_sform_settings_options_object.ajaxurl,
            data: formData + '&action=sform_delete_form', 
            success: function(data){
	          var error = data['error'];
	          var message = data['message'];
	          var redirect_url = data['redirect_url'];
	          var img = data['img'];
	          var confirm = data['confirm'];
	          if( data.error === true ){
                $('span#default').addClass('unseen');
                $('span#confirm').removeClass('unseen');
                $('span#confirm').html(message);
                if ( confirm ) {
 	              $('#deletion-notice').addClass('confirm'); 
                  $('#hidden-confirm').html(confirm);
                }
             }
	          if( data.error === false ){                
                $('.disclaimer').html(message);
                $('#deletion-buttons').addClass('unseen');
 	            $('#deletion-notice').removeClass('confirm');
 	            $('#deletion-notice, .disclaimer').addClass('success'); 
                $('h3.deletion').text(message);
                $('.disclaimer').html(img);
                setTimeout(function(){ document.location.href = redirect_url; }, 3000);
              }
            },
 			error: function(data){
                $('.disclaimer').html('AJAX call failed');
	        } 	
		  });
		  e.preventDefault();
		  return false;
	   });

       $("#relocation").on("click", function() { 
         if($(this).prop('checked') == true) { 
		   $('.trmoving').removeClass('unseen'); 
	       if ( $("#moveto").val() != '' ) { 
		      $('.trmoveto').removeClass('unseen');
	          if ( $("#starting").val() != '' && $("#starting").val() != 'next' ) { 
		         $('.tronetime').removeClass('unseen'); 
		      }
	          if ( $("#starting").val() == 'next' ) { 
		         $('.trsettings').removeClass('unseen'); 
		      }
		   } 
         } 
         else { 
           $('.trmoving').addClass('unseen'); 
           $('.trmoveto').addClass('unseen'); 
           $('.tronetime').addClass('unseen');
           $('.trsettings').addClass('unseen');
           $( "#settings" ).prop( "checked", false ); 
         } 
       });

       $('#moveto').on('change', function () {
          var selectVal = $(this).val();
          var selectName = $('#moveto option[value="' + selectVal +'"]').text();
          $('#starting').val('');
	  	  $( "#onetime" ).prop( "checked", true );
	  	  $('.description.onetime').addClass('invisible');
		  $('.tronetime').addClass('unseen'); 
          $('.trsettings').addClass('unseen');
          $( "#settings" ).prop( "checked", false );		  
          if ( selectVal == '' ) {
             $('.trmoveto').addClass('unseen'); 
             $('#starting').val('');
             $( "#onetime" ).prop( "checked", true );
	      } 
          else { 
             $('#form-to').val(selectName);
	         $('.trmoveto').removeClass('unseen'); 
          }
       });          

       $('#starting').on('change', function () {
          var selectVal = $(this).val();
          if ( selectVal == 'next' || selectVal == '' ) {
	          $('.tronetime').addClass('unseen'); 
	          $( "#onetime" ).prop( "checked", false ); 
	          $('.description.onetime').removeClass('invisible');
              if ( selectVal == 'next' ) {
		        $('.trsettings').removeClass('unseen'); 
	          }
	          else {
		        $('.trsettings').addClass('unseen');
		        $( "#settings" ).prop( "checked", false ); 
	          }
	      } 
          else { 
	          $('.tronetime').removeClass('unseen'); 
	          $( "#onetime" ).prop( "checked", true );
	          $('.description.onetime').addClass('invisible');
		      $('.trsettings').addClass('unseen');
		      $( "#settings" ).prop( "checked", false ); 
         }
       });          

       $("#onetime").on("click", function() {
         if($(this).prop('checked') == true) { 
	       $('.description.onetime').addClass('invisible');
	       $('.trsettings').addClass('unseen');
	       $( "#settings" ).prop( "checked", false );
         } 
         else { 
           $('.description.onetime').removeClass('invisible');
           $('.trsettings').removeClass('unseen'); 
         } 
       });
       
       $("#settings").on("click", function() {
         if($(this).prop('checked') == true) { 
	       $('.description.settings').addClass('invisible');
         } 
         else { 
           $('.description.settings').removeClass('invisible'); 
         } 
       });
       
        $('#save-card').on( 'click', function(e) {
	      $('.message').removeClass('error success unchanged');
	      $('.message').addClass('seen');
          $('.message').text(ajax_sform_settings_options_object.saving);
          var formData = $('form#card').serialize();
		  $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_sform_settings_options_object.ajaxurl,
            data: formData + '&action=form_update', 
            success: function(data){
	          var error = data['error'];
	          var message = data['message'];
	          var update = data['update'];
	          var redirect = data['redirect'];
	          var moving = data['moving'];
	          var restore = data['restore'];
	          var messages = data['messages'];
	          var moved = data['moved'];
	          var select = data['select'];
	          var onetime = data['onetime'];
	          var url = data['url'];
	          if( error === true ){
	            $('.message').addClass('error');
                $('.message').html(data.message);
	            if( redirect === true ) { setTimeout(function(){ document.location.href = url; }, 1000); }
              }
	          if( error === false ){
                $('.message').html(data.message);
	            if( update === false ) {
	              $('.message').addClass('unchanged');
                }
	            else {
	              $('.message').addClass('success');
	              if ( moving === true ) {
			        $('#starting').html(select);
		            $('#tdentries').removeClass('last');
		            $('.trmoved, .trrestore').removeClass('unseen');
		            $('#entries').text(messages);
		            $('#submissions').val(messages);
		            $('#moved-entries').text(moved);
			        $('#moved-submissions').val(moved);
                    if( onetime === false ){
                      $('#starting').val('next');
		              $('.tronetime').addClass('unseen');
			        } 
			        else {	                     
 		              $('#relocation').prop( "checked", false );
                      $('#moveto').val('');
                      $('#starting').val('');
		              $('.trmoving, .trmoveto, .tronetime').addClass('unseen');
	 	            } 
		          }
		          if ( moving === false ) {
			        if ( restore === true ) {
			 		  $('#starting').html(select);
			          $('#tdentries').addClass('last');
	                  $('.trrestore').removeClass('unseen'); 		              
			          $('#entries').text(messages);
		              $('#submissions').val(messages);
		              $('#moved-entries').text(moved);
	                  $('#moved-submissions').val(moved);
		              $( "#restore" ).prop( "checked", false );
		              $('.trmoved, .trrestore').addClass('unseen');
	                 }
		            if ( onetime === false ) {
			           $('#starting').val('next');
			        }
			       }
                }
              }
            },
 			error: function(data){
              $('.message').html('AJAX call failed');
	        } 	
		  });
		  e.preventDefault();
		  return false;
	   });
	   
	   if ( document.getElementById("new-release") != null ){
	     var currentText = document.getElementById("new-release").innerHTML;   
         $("#storing-notice").on("click", function() {
           if ( document.getElementById("new-release").innerHTML === currentText) {
             document.getElementById("new-release").innerHTML = ajax_sform_settings_options_object.storing_notice;
           } else {
             document.getElementById("new-release").innerHTML = currentText;
           }  
	     });
       }
                     
   	 });
   	    	 
})( jQuery );