// CONV

(function($){
  $.fn.extend({
    donetyping: function(callback,timeout){
      timeout = timeout || 1e3; // 1 second default timeout
      var timeoutReference,
          doneTyping = function(el){
            if (!timeoutReference) return;
            timeoutReference = null;
            callback.call(el);
          };
      return this.each(function(i,el){
        var $el = $(el);
        // Chrome Fix (Use keyup over keypress to detect backspace)
        // thank you @palerdot
        $el.is(':input') && $el.on('keyup keypress paste',function(e){
          // This catches the backspace and DEL button in chrome, but also prevents
          // the event from triggering too preemptively. Without this line,
          // using tab/shift+tab will make the focused element fire the callback.
          if (e.type=='keyup' && !([8,46].includes(e.keyCode))){return;}

          // Check if timeout has been set. If it has, "reset" the clock and
          // start over again.
          if (timeoutReference) clearTimeout(timeoutReference);
          timeoutReference = setTimeout(function(){
            // if we made it here, our timeout has elapsed. Fire the
            // callback
            doneTyping(el);
          }, timeout);
        }).on('blur',function(){
          // If we can, fire the event since we're leaving the field
          doneTyping(el);
        });
      });
    }
  });
})(jQuery);

jQuery( document ).ready( function($) {
	
	$( document ).on( 'change keyup', '.gfield input, .gfield select, .gfield textarea', function( event ) {
		
		if( ! $('.conv-form').length ) return;
		
		var $form = $(this).parents('form');
		cf_adjust_progress_bar($form);
	} );
	
	function cf_adjust_progress_bar($form) {
		setTimeout(function() {
			
			var styleValue = $form.find('.gf_progressbar_percentage').attr('style');
			$('.conv-form-footer-progress-completed').attr('style', styleValue);
			
			var percentage = $form.find('.gf_progressbar_percentage span').text();
			$('.conv-form-footer-progress-status-percentage span').text(percentage);
			
			
			$('.conv-form-footer-switch-step-up').removeClass('disabled');
			if( ! $form.find('.gform_page:visible').find('.gform_previous_button').length ) {
				$('.conv-form-footer-switch-step-up').addClass('disabled');
			}
			
		}, 200);
	}
	
	function add_alphabets( form_id, current_page ) {

		var choicesElements = $('#gform_wrapper_'+form_id+' .gfield_checkbox, #gform_wrapper_'+form_id+' .gfield_radio');

		$.each( choicesElements, function( fieldIndex, fieldElement ){
			//set the default value of i & j to print A to Z
			var startingAlphabetIndex = 65;

			var fieldChoices = $(fieldElement).find('.gchoice');

			$.each( fieldChoices, function( choiceIndex, choiceElement ){

				var alphabetCharacter = String.fromCharCode( startingAlphabetIndex );    
				$( choiceElement ).find( 'label' ).attr( 'data-alphabet-content', alphabetCharacter );
				startingAlphabetIndex++;  
			});
			
			var currentActivePage = $('#gform_page_'+form_id+'_'+current_page);

			var otherInput = $(currentActivePage).find('.gfield_radio .gchoice input[type="text"]');
			var nextButton = $(currentActivePage).find(' .gform_next_button');

		} )
	}
	
	$(document).on('click', '.conv-form-footer-switch-step-up', function() {
		
		$container = $(this).parents('.conv-form-container');
		if( ! $container.length ) {
			return;
		}
		
		$form = $container.find('form');
		if( ! $form.length ) {
			return;
		}
		
		if( $form.find('.gform_page:visible').find('.gform_previous_button').length ) {
			$(this).addClass('active');
			$form.find('.gform_page:visible').find('.gform_previous_button').trigger('click');
		}
	});
	
	$(document).on('click', '.conv-form-footer-switch-step-down', function() {
		
		$container = $(this).parents('.conv-form-container');
		if( ! $container.length ) {
			return;
		}
		
		$form = $container.find('form');
		if( ! $form.length ) {
			return;
		}
		
		if( $form.find('.gform_page:visible').find('.gform_next_button').length ) {
			$(this).addClass('active');
			$form.find('.gform_page:visible').find('.gform_next_button').trigger('click');
		}
		else if( $form.find('.gform_page:visible').find('.gform_button').length ) {
			$(this).addClass('active');
			$form.find('.gform_page:visible').find('.gform_button').trigger('click');
		}
		
	});
	
	
	
	$(document).keypress(function( event ) {
		
		
		if( ! $('body').hasClass('conv-form') ) {
			return;
		}

		var page = $( '.gform_page' );
		var keyCode = event.which;
		// when pressing enter click on next or submit button
		if ( keyCode == 13 && ! event.shiftKey ) {

			var intro = $('.conv-intro');
			// landing page
			if( intro.length && intro.is(':visible') ) {
				$('.conv-intro-btn').click();
			}
			else {
				
				$( '.gform_page' ).each(function() {
					
					if( $(this).is(':visible') ) {
						$(this).parents('form').trigger('submit', [true]);
					}
				});
			}

		}
		
		if ((keyCode >= 65 && keyCode <= 90) || (keyCode >= 97 && keyCode <= 122)) {
			
			// Other alphabets
			$page = $('.gform_page:visible')
			var otherInput = $page.find('.gfield_radio .gchoice input[type="text"]');
			if( $(otherInput).is(':visible') ){
				return;
			}
			
			var otherInput = $page.find('.gfield_checkbox .gchoice input[type="text"]');
			if( $(otherInput).is(':visible') ){
				return;
			}

			var keyAlphabet = event.key;
			keyAlphabet = keyAlphabet.toUpperCase();
			cap_code = keyAlphabet.charCodeAt(0);
			index = cap_code - 65;
				
			var choicesInsideActivePage = $page.find('.gfield_radio');
			if( choicesInsideActivePage.length > 0 ){
				
				$choices = choicesInsideActivePage.find('input[type="radio"]');
				if ( $choices[ index ] ) {
					$( $choices[ index ] ).trigger( 'click' );
					// $( $choices[ index ] ).change();
				}
			}
			
			var choicesInsideActivePage = $page.find('.gfield_checkbox');
			if( choicesInsideActivePage.length > 0 ){
				
				$choices = choicesInsideActivePage.find('input[type="checkbox"]');
				if ( $choices[ index ] ) {
					$( $choices[ index ] ).trigger( 'click' );
					// $( $choices[ index ] ).change();
				}
			}
			
			
		}
		
		
	});	
	
	
	
	$(document).bind('gform_post_render', function(e, form_id, active_page){
		
		
		var $form = $('form#gform_' + form_id);
		cf_adjust_progress_bar( $form );
		// add_alphabets( form_id, active_page );
		
		if( $('body').hasClass('leftimage') || $('body').hasClass('rightimage') ) {
			var $cover_image = $('body').data('cover-image');
			var $side_image = $('body').data('side-image');
			var $visible_fields = $('.gfield:visible');
			
			if( $visible_fields.length ) {
				$visible_fields.each(function() {
					$field_image = $(this).data('image');
					if( typeof $field_image != 'undefined' && $field_image != '' && $field_image ) {
						$side_image = $field_image;
					}
				});
			}
			
			$image_html = '';
						
			if( typeof $side_image != 'undefined' && $side_image != '' && $side_image ) {
				$image_html = '<img src="' + $side_image + '">';
			}
			else if( typeof $cover_image != 'undefined' && $cover_image != '' && $cover_image ) {
				$image_html = '<img src="' + $cover_image + '">';
			}
			else if( active_page == 1 && $form.parents('.conv-form').find('.show-intro').length ) {
				$page = $('.gform_page:first');
				if( $page.length ) {
					$page_fields = $page.find('.gfield');
					if( $page_fields.length ) {
						$page_fields.each(function() {
							$field_image = $(this).data('image');
							if( typeof $field_image != 'undefined' && $field_image != '' && $field_image ) {
								$side_image = $field_image;
							}
						});
					}
				}
				if( typeof $side_image != 'undefined' && $side_image != '' && $side_image ) {
					$image_html = '<img src="' + $side_image + '">';
				}
				
			}
			
			if( $('body').hasClass('leftimage') ) {
				$('.image-left').html( $image_html );
			}
			if( $('body').hasClass('rightimage') ) {
				$('.image-right').html( $image_html );
			}
			
			
		}
		
		if( ! $form.hasClass('has_animation' ) ) { 
			return;
		}
		
		$animations = {
			'fade' : {
				'next_in' : 'animate__fadeIn',
				'next_out' : 'animate__fadeOut',
				'prev_in' : 'animate__fadeIn',
				'prev_out' : 'animate__fadeOut',
			},
			/*'fadeslidehorizontal' : {
				'next_in' : 'animate__fadeInLeft',
				'next_out' : 'animate__fadeOutRight',
				'prev_in' : 'animate__fadeInRight',
				'prev_out' : 'animate__fadeOutLeft',
			},
			'fadeslidevertical' : {
				'next_in' : 'animate__fadeInDown',
				'next_out' : 'animate__fadeOutDown',
				'prev_in' : 'animate__fadeInUp',
				'prev_out' : 'animate__fadeOutUp',
			},*/
			'slidehorizontal' : {
				'next_in' : 'animate__slideInRight',
				'next_out' : 'animate__slideOutLeft',
				'prev_in' : 'animate__slideInLeft',
				'prev_out' : 'animate__slideOutRight',
			},
			'slidevertical' : {
				'next_in' : 'animate__slideInUp',
				'next_out' : 'animate__slideOutUp',
				'prev_in' : 'animate__slideInDown',
				'prev_out' : 'animate__slideOutDown',
			},
			/*'zoom' : {
				'next_in' : 'animate__zoomIn',
				'next_out' : 'animate__zoomOut',
				'prev_in' : 'animate__zoomIn',
				'prev_out' : 'animate__zoomIn',
			},
			'lightspeed' : {
				'next_in' : 'animate__lightSpeedInLeft',
				'next_out' : 'animate__lightSpeedOutRight',
				'prev_in' : 'animate__lightSpeedInRight',
				'prev_out' : 'animate__lightSpeedOutLeft',
			},*/
		};
		
		$animation_name = 'fade';
		$anim = $animations.fade;
		$animation_name = 'fade';
		$.each( $animations, function(key, value) {
			if( $form.hasClass( key ) ) {
				$anim = value;
				$animation_name = key;
			}
		} );
		
		$('.element-hidden').hide();
		$('.element-hidden').removeClass('element-hidden');
		
		if($form.parents('.conv-form').find('.show-intro').length) {
			$form.parents('.conv-form').find('.conv-intro-btn').on('click', function (e) {			
				$('.conv-intro').addClass( 'animate__animated' ).addClass('animate__fadeOut').hide();			
				$('.conv-form-container').addClass( 'animate__animated' ).addClass('animate__fadeIn').show();
				adjust_margins_for_template_wrapper();
			});
			
			$('.conv-intro').addClass( 'animate__animated' ).addClass($anim.next_in).show();
			$('.show-intro').removeClass('show-intro');
			adjust_margins_for_template_wrapper();
		}
		else {
			$('.conv-form-container').addClass( 'animate__animated' ).addClass('animate__fadeIn').show();
			adjust_margins_for_template_wrapper();
		}
		
		function adjust_margins_for_template_wrapper() {
			
			if( jQuery(window).width() <= 767 ) {
				// return;
			}
			
			var $header_height = $('.conv-form-container .conv-intro-title').outerHeight();
			$('.custom-template-wrap').css({
				'margin-top' : $header_height,
			});
			
			
			var $footer_height = $('.conv-form-container .conv-form-footer').outerHeight();
			$('.custom-template-wrap').css({
				'margin-bottom' : $footer_height,
			});
			
			$('html').addClass('cf-html');
		}
		
		$('#gform_ajax_frame_'+form_id).unbind('load').on('load', function() {
			
			var $form = $('form#gform_' + form_id);
			cf_adjust_progress_bar($form);
			
			$anim = $animations.fade;
			$animation_name = 'fade';
			$.each( $animations, function(key, value) {
				if( $form.hasClass( key ) ) {
					$anim = value;
					$animation_name = key;
				}
			} );
			
			var current_page = 1;			
			$('#gform_' + form_id).find('.gform_page').each(function(){
				if($(this).css('display') !== 'none'){
					current_page = $(this).attr('id').replace('gform_page_'+form_id+'_', '');
					return false
				}
			});
			
			var form_content = $(this).contents().find('#gform_wrapper_'+form_id);
			
			target_page = current_page + 1;
			form_content.find('.gform_page').each(function(){
				if($(this).css('display') !== 'none'){
					target_page = $(this).attr('id').replace('gform_page_'+form_id+'_', '');
					return false
				}
			});
			
			var contents = $(this).contents().find('*').html();
			var is_postback = contents.indexOf('GF_AJAX_POSTBACK') >= 0;
			if (!is_postback) {
				return;
			}
			
			adjust_margins_for_template_wrapper();
			
			var is_confirmation = $(this).contents().find('#gform_confirmation_wrapper_'+form_id).length > 0;
			var is_redirect = contents.indexOf('gformRedirect(){') >= 0;
			var is_form = form_content.length > 0 && !is_redirect && !is_confirmation;
			
			if ( is_form ) {
				if (form_content.hasClass('gform_validation_error')) {
					$('#gform_wrapper_' + form_id).addClass('gform_validation_error');					
					$('#gform_wrapper_' + form_id).html(form_content.html());
					
					$current_page_object = $( '#gform_page_'+form_id+'_' + current_page );
					$current_page_object.addClass( 'animate__animated' ).addClass('animate__shakeX');
					
					$('.conv-form-footer-switch-step-up').removeClass('active');
					$('.conv-form-footer-switch-step-down').removeClass('active');
				} 
				else {
										
					type = 'next';
					
					if( current_page > target_page ) {
						type = 'previous';
					}
					
					$current_page_object = $( '#gform_page_'+form_id+'_' + current_page );
					$target_page_object = $( '#gform_page_'+form_id+'_' + target_page );
					
					$current_page_height = $current_page_object.height();
					$target_page_height = $target_page_object.height();
					
					$form.find( '.gform_body' ).css({
						'overflow' : 'hidden',
						'position' : 'relative',
						'height' : $current_page_height > $target_page_height ? $current_page_height : $target_page_height
					});
					$current_page_object.css({
						'position' : 'absolute',
						'width' : '100%',
					});
					$target_page_object.css({
						'position' : 'absolute',
						'width' : '100%',
					});
					
					$target_page_object.find('.gform_page_footer').addClass('gfa-loading');
					
					if( type == 'previous' ) {		
						$current_page_object.addClass( 'animate__animated' ).addClass($anim.prev_out);
						
						if( $animation_name == 'zoom' ) {
							$current_page_object.addClass( 'animate__animated' ).hide();
						}
						
						$target_page_object.addClass( 'animate__animated' ).addClass($anim.prev_in).show();						
					}
					else {
						
						$current_page_object.addClass( 'animate__animated' ).addClass($anim.next_out);
						
						if( $animation_name == 'zoom' ) {
							$current_page_object.addClass( 'animate__animated' ).hide();
						}
						
						$target_page_object.addClass( 'animate__animated' ).addClass($anim.next_in).show();
					}
					
					$current_page_object.get(0).addEventListener('animationend', () => {
						$(this).hide();
					});
					$target_page_object.get(0).addEventListener('animationend', () => {
						
						form_content.find('.gform_page_footer').addClass('gfa-loading');
						form_content.find('.gf_progressbar_percentage').addClass('animate__animated').addClass('animate__slideInLeft');
						$('#gform_wrapper_' + form_id).html(form_content.html());
						
						$('.conv-form-footer-switch-step-up').removeClass('active');
						$('.conv-form-footer-switch-step-down').removeClass('active');
						
						setTimeout(function() {
							$( '#gform_page_'+form_id+'_' + target_page ).find('.gform_page_footer').removeClass('gfa-loading');
							
							$(document).trigger('gform_page_loaded', [form_id, current_page]);
							$(document).trigger('bg_page_loaded')
							$(document).trigger('gform_post_render', [form_id, current_page])

							if (window['gformInitDatepicker']) {
								gformInitDatepicker();
							}
							if (window['gformInitPriceFields']) {
								gformInitPriceFields();
							}
							
							window['gf_submitting_' + form_id] = false;
						}, 150);
					});
				}
				
				setTimeout(function () {						
					
				}, 1010);
			}
			
			else if (is_confirmation || !is_redirect) {
				var confirmation_content = $(this).contents().find('.GF_AJAX_POSTBACK').html();
				if (!confirmation_content) {
					confirmation_content = contents;
				}
				
				$( confirmation_content ).addClass( 'animate__animated' ).addClass($anim.next_in);
				$('#gform_wrapper_' + form_id).html(confirmation_content);
				
				$('.conv-form-footer-switch-step-up').removeClass('active');
				$('.conv-form-footer-switch-step-down').removeClass('active');
				$('.conv-form-footer-wrap').hide();
				

				setTimeout(function() {
					$(document).trigger('gform_confirmation_loaded', [1]);
					window['gf_submitting_' + form_id] = false;
				}, 50);
			}
			
			else if( is_redirect ){
				
				$redirect = $('#gform_ajax_frame_' + form_id).contents().find('body').find('script').html();
				$redirect = $redirect.replace('function gformRedirect(){', '');
				$redirect = $redirect.replace('}', '');
				eval($redirect);
			}
		
		});
		
	});
	
} );


jQuery(document).bind('gform_post_render', function(){ 
	perform_hiding_operations();
});


function perform_hiding_operations() {
	
	if( jQuery('.gform_page').length > 0 ) {		
		jQuery('.gform_page').each(function() {						
			if( jQuery(this).find('.hide-next-button').length > 0 ) {
				jQuery(this).find('.gform_next_button').removeClass('make_visible');
				jQuery(this).find('.gform_next_button').addClass('keep_hidden');				
			}
			else {
				jQuery(this).find('.gform_next_button').removeClass('keep_hidden');	
			}
			
			if( jQuery(this).find('.hide-previous-button').length > 0 ) {				
				jQuery(this).find('.gform_previous_button').removeClass('make_visible');				
				jQuery(this).find('.gform_previous_button').addClass('keep_hidden');				
			}
			else {
				jQuery(this).find('.gform_previous_button').removeClass('keep_hidden');	
				jQuery(this).find('.gform_previous_button').addClass('make_visible');	
			}
			
			if( jQuery(this).find('.hide-next-button').length > 0 ) {				
				jQuery(this).find('.gform_button').removeClass('make_visible');				
				jQuery(this).find('.gform_button').addClass('keep_hidden');				
			}
			else {
				jQuery(this).find('.gform_button').removeClass('keep_hidden');	
				jQuery(this).find('.gform_button').addClass('make_visible');	
			}
		});
	}
	
	if( jQuery('.gform_wrapper').length > 0 && ! jQuery('.gform_page').length > 0 ){	
	
		jQuery('.gform_wrapper').each(function() {									
			if( jQuery(this).find('.hide-submit-button').length > 0 ) {				
				jQuery(this).find('.gform_button').removeClass('make_visible');				
				jQuery(this).find('.gform_button').addClass('keep_hidden');				
			}
			else {
				jQuery(this).find('.gform_button').removeClass('keep_hidden');	
				jQuery(this).find('.gform_button').addClass('make_visible');	
			}
		});
		
	}

}

// GEN
jQuery( document ).ready( function($) {
	
	var click_perform = true;
	var aafg_timeout = 0;
	
	function lookup_fields(element) { 
						
		var returnvalue = {'process' : true, 'buttonshow' : false };
		
		if( typeof gf_form_conditional_logic != 'undefined' ) {
			
			var formWrapper = element.closest( '.gform_wrapper' );
			var formID = formWrapper.attr( 'id' ).split( '_' )[ 2 ];
			
			var fieldwrapper = element.closest( '.gfield' );
			var fieldId = gformGetFieldId( '#' + fieldwrapper.attr('id') );
									
			var dependentFieldIds = rgars( gf_form_conditional_logic, [ formID, 'fields', gformExtractFieldId( fieldId ) ].join( '/' ) );
			
			if( dependentFieldIds ) {
				var fields = dependentFieldIds;
				for(var i=0; i < fields.length; i++){
					var action = gf_check_field_rule(formID, fields[i]);
					if( action == 'show' ) {
						if( element.parents('.gform_page').length > 0 ) {
							if( element.parents('.gform_page').find('#field_'+formID+'_'+fields[i]).length > 0){
								returnvalue.process = false;
								returnvalue.buttonshow = true;		
							}
						}
						else {
							returnvalue.process = false;
							returnvalue.buttonshow = true;	
						}
					}
				}
			}
		}
		
		return returnvalue;
	}
	
	
	function process_auto_advanced( element ) {
		
		var process = true;
		var buttonshow = false;
		var $this = element;
		
		if( typeof lookup_fields == 'function' ) {
			lookup = lookup_fields($this);
			process = lookup.process;
			buttonshow = lookup.buttonshow;
		}
		
		if(process) {
			setTimeout( function() {				
				$this.parents('form').trigger('submit', [true]);
				
				if( $('[data-js=gform-conversational-nav-next]').length ) {
					$('[data-js=gform-conversational-nav-next]').trigger('click');
				}
				else if( $('.gform-conversational__nav-button--next').length ) {
					$('.gform-conversational__nav-button--next').trigger('click');
				}
			}, 200 );
		}
		
		
		if( buttonshow ) {
			var parents;
			if( $this.parents('.gform_page').length > 0 ) {
				parents = $this.parents('.gform_page');				
			}
			else {
				parents = $this.parents('.gform_wrapper');			
			}
			
			if(parents.find('.gform_next_button').length > 0) {
				parents.find('.gform_next_button').removeClass('keep_hidden');
			}
			else if(parents.find("input[type='submit']").length > 0) {
				parents.find("input[type='submit']").removeClass('keep_hidden');
			}
		}
		else {
			perform_hiding_operations();
		}
	}
	
	
	function process_texting_field( $field, $input ) {
		
		if( aafg_timeout ) {
			clearTimeout(aafg_timeout);
		}
		
		aafg_timeout = setTimeout(function(){
			
			var $inputnumberkeys = $field.data('inputnumberkeys');
			
			console.log( $inputnumberkeys + ' --- ' + $input.val() + ' --- ' + $input.val().length );
			
			if( typeof $inputnumberkeys != 'undefined' && $inputnumberkeys ) {
				if( $input.val() && $input.val().length >= $inputnumberkeys ) {
					
					$input.attr('readonly', 'readonly');
					process_auto_advanced( $input );
					
				}
			}
			
		}, 100);
	}
	
	
	function process_check_field( $field, $input ) {
		
		if( aafg_timeout ) {
			clearTimeout(aafg_timeout);
		}
		
		aafg_timeout = setTimeout(function(){
			
			var $inputnumberkeys = $field.data('inputnumberkeys');
			
			if( typeof $inputnumberkeys != 'undefined' && $inputnumberkeys ) {
				if( $input.val() && $field.find('input:checked').length >= $inputnumberkeys ) {
					process_auto_advanced( $input );
				}
			}
			
		}, 100);
	}
	
	
	$(document).bind('gform_post_render', function(event, form_id, current_page){ 
						
		page_id = '#gform_page_' + form_id + '_' + current_page;
		
		setTimeout(function() {
			if( $(page_id).length > 0 && $(page_id).find('.has-input-name.populated').length > 0 ) {
				if( $('.has-input-name.populated').find('select').length > 0 ) {
					$('.has-input-name.populated').find('select').trigger('change');
				}
				if( $('.has-input-name.populated').find('input[type=radio]').length > 0 ) {
					$('.has-input-name.populated').find('input[type=radio]').trigger('change');
				}
				
			}
		}, 200);
		
	});
	
	
	$(document).on('click', ".trigger-next-zzd input[type='radio']", function() {
	   var $this = $(this);
	   setTimeout(function() {
		   
			if(click_perform) {
				$this.trigger('change');
			}
			click_perform = true;
	   }, 100);
	   
   });
   

	$(document).on ('change', 
		".trigger-next-zzd input[type='radio'], .trigger-next-zzd select," + 
		'.gfied--dummy-select'
		, function() {
		
		var $this = $(this);
		click_perform = false;
		
		process_auto_advanced( $this );
   });
	
	
	$(document).on ('keyup paste', 
		
		'.gfield--type-text[data-inputnumberkeys] input, ' + 
		'.gfield--type-textarea[data-inputnumberkeys] textarea, ' + 
		'.gfield--type-number[data-inputnumberkeys] input, ' + 
		'.gfied--dummy-input'
		,function() {
		
		var $field = $(this).parents('[data-inputnumberkeys]');
		
		if( $field.hasClass('trigger-next-zzd') ) {
			process_texting_field( $field, $(this) );
		}
	});
	
	
	$(document).on ('change', 		
		'.gfield--type-checkbox input, ' + 
		'.gfied--dummy-input'
		,function() {
		
		var $field = $(this).parents('[data-inputnumberkeys]');
		if( $field.hasClass('trigger-next-zzd') ) {
			process_check_field( $field, $(this) );
		}
		
	});
	
	
	$(document).on ('change', 		
		'.gfield--type-address .ginput_address_country select, ' + 
		'.gfied--dummy-input'
		,function() {
		
		var $this = $(this);
		click_perform = false;
		
		if( $field.hasClass('trigger-next-zzd') ) {
			process_auto_advanced( $this );
		}
	});
	
	
	$(document).on ('keyup paste', 		
		'.gfield--type-address .ginput_address_zip input, ' + 
		'.gfied--dummy-input'
		,function() {
		
		var $this = $(this);
		
		var $field = $(this).parents('[data-inputnumberkeys]');
		
		if( $field.hasClass('trigger-next-zzd') && ! $field.find('.ginput_address_country').length ) { 
			process_texting_field( $field, $(this) );
		}
	});
	
	
});


jQuery(document).ready(function($) {
	const colorPanel = $('#color-panel');
	const colorPanelToggle = $('#color-panel-toggle');
	
	var cf_form_bg_color_input = $('#cf_form_bg_color_input');
	
	var cf_primary_color_input = $('#cf_primary_color_input');
	var cf_secondary_color_input = $('#cf_secondary_color_input');
	var cf_progressbar_color_input = $('#cf_progressbar_color_input');
	// var cf_border_color_input = $('#cf_border_color_input');
	
	var cf_button_bg_input = $('#cf_button_bg_input');
	var cf_button_text_input = $('#cf_button_text_input');
	var cf_button_hover_bg_input = $('#cf_button_hover_bg_input');
	var cf_button_hover_text_input = $('#cf_button_hover_text_input');
	var cf_confirmation_text_input = $('#cf_confirmation_color');

	function saveColors(e) {
		e.preventDefault();
		
		var cf_form_bg_color = cf_form_bg_color_input.val();
		
		var cf_primary_color = cf_primary_color_input.val();
		var cf_secondary_color = cf_secondary_color_input.val();
		var cf_progressbar_color = cf_progressbar_color_input.val();
		// var cf_border_color = cf_border_color_input.val();
		
		var cf_button_bg = cf_button_bg_input.val();
		var cf_button_text = cf_button_text_input.val();
		var cf_button_hover_bg = cf_button_hover_bg_input.val();
		var cf_button_hover_text = cf_button_hover_text_input.val();
		var cf_confirmation_text = cf_confirmation_text_input.val();


		$('body').css('--cf-bg-color', cf_form_bg_color);
		
		$('body').css('--cf-primary-color', cf_primary_color);
		$('body').css('--cf-secondary-color', cf_secondary_color);
		$('body').css('--cf-progressbar-color', cf_progressbar_color);
		// $('body').css('--cf-border-color', cf_border_color);
		
		$('body').css('--cf-button-bg', cf_button_bg);
		$('body').css('--cf-button-text', cf_button_text);
		$('body').css('--cf-button-hover-bg', cf_button_hover_bg);
		$('body').css('--cf-button-hover-text', cf_button_hover_text);
		$('body').css('--cf-confirmation-text', cf_confirmation_text);
	}

	function setColors() {
		var cf_form_bg_color = $('body').css('--cf-bg-color');
		
		var cf_primary_color = $('body').css('--cf-primary-color');
		var cf_secondary_color = $('body').css('--cf-secondary-color');
		var cf_progressbar_color = $('body').css('--cf-progressbar-color');
		// var cf_border_color = $('body').css('--cf-border-color');
		
		var cf_button_bg = $('body').css('--cf-button-bg');
		var cf_button_text = $('body').css('--cf-button-text');
		var cf_button_hover_bg = $('body').css('--cf-button-hover-bg');
		var cf_button_hover_text = $('body').css('--cf-button-hover-text');
		var cf_confirmation_text = $('body').css('--cf-confirmation-text');
				
		cf_form_bg_color_input.val(cf_form_bg_color);
		
		cf_primary_color_input.val(cf_primary_color);
		cf_secondary_color_input.val(cf_secondary_color);
		cf_progressbar_color_input.val(cf_progressbar_color);
		// cf_border_color_input.val(cf_border_color);
		
		cf_button_bg_input.val(cf_button_bg);
		cf_button_text_input.val(cf_button_text);
		cf_button_hover_bg_input.val(cf_button_hover_bg);
		cf_button_hover_text_input.val(cf_button_hover_text);
		cf_confirmation_text_input.val(cf_confirmation_text);
	}

	function toggleColorPanel() {
		colorPanel.toggleClass('open');
		colorPanelToggle.toggleClass('active');
	}

	colorPanelToggle.on('click', toggleColorPanel);
	colorPanel.on('submit', function(e) {
		
		saveColors(e);
		
		colorPanel.find('#save-colors-btn').text( colorPanel.find('#save-colors-btn').data('saving') );
		var formData = $(this).serialize();
		$.ajax({
			url: aafg.ajaxurl,
			type: 'POST',
			data: {
				action: 'aafg_save_form_colors',
				form_data: formData
			},
			success: function(response) {
				
				if( response.status == 'success' ) {
					colorPanel.find('#save-colors-btn').text( colorPanel.find('#save-colors-btn').data('saved') );
				}
				else {
					colorPanel.find('#save-colors-btn').text( colorPanel.find('#save-colors-btn').data('error') );
				}
				
				setTimeout(function() {
					colorPanel.find('#save-colors-btn').text( colorPanel.find('#save-colors-btn').data('default') );
				}, 4000);
			}
		});

	});
	
	jQuery(document).on( 'input', '.color-option input', function(e) {
		saveColors(e);
	} );
	
	jQuery('.color-option input').change(function(e) {
		saveColors(e);		
	});

	setColors();
});


