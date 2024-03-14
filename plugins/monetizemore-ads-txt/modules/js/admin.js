jQuery(document).ready(function($){
	
	$('#submit_shortcodes').submit(function( e ){
		
		// add index to cehckboxes

		$field_1_error = 0;
		$field_2_error = 0;
		$field_3_error = 0;
		
		$('tr:not(.cliche_row_block) .domain_input_field').each(function(){			 
			if( !validateDomain( $(this).val() ) ){
				$(this).addClass('is_error');
				$field_1_error = 1;
			}else{
				$(this).removeClass('is_error');
			}						
		})
		
		
		$('tr:not(.cliche_row_block) .account_id_input_field').each(function(){			 
			if( $(this).val().trim() == ""){
				$(this).addClass('is_error');
				$field_2_error = 1;
			}else{
				$(this).removeClass('is_error');
			}						
		})
		
		$('tr:not(.cliche_row_block) .type_id_input_field').each(function(){			 
			if( $(this).val().trim() == ""){
				$(this).addClass('is_error');
				$field_3_error = 1;
			}else{
				$(this).removeClass('is_error');
			}						
		})
		
		$('.errors_block .alert').slideUp();
		if( $field_1_error == 1 ){
			$('.errors_block .error_block_1').slideDown();
		}
		if( $field_2_error == 1 ){
			$('.errors_block .error_block_2').slideDown();
		}
		if( $field_3_error == 1 ){
			$('.errors_block .error_block_3').slideDown();
		}
		if( $field_1_error == 1  || $field_2_error == 1 || $field_3_error == 1 ){
			e.preventDefault();
		}
		
	})
	
	$('.show_info_block').click(function(){
		$('.shortcode_helper .info_data').slideToggle();
	})
	$('body').on('click', '.delete_row', function(){
		var pnt = $(this).parents('tr');
		pnt.fadeOut(function(){
			pnt.replaceWith('');
		})
	})
 
	
	
	$('body').on('click', '.add_row', function(){
		var first_line = $('.cliche_row_block').clone();
		console.log( first_line );
		first_line.removeClass('cliche_row_block').addClass('ssss');
		$('.editor_content').append( first_line );
	})

	
	function validateDomain(the_domain)
	  {  
		// strip off "http://" and/or "www."
		the_domain = the_domain.replace("http://","");
		the_domain = the_domain.replace("www.","");

		var reg = /^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/;
		return reg.test(the_domain);
	  } // end validateDomain()
	  
	$('.info_cont[title]').qtip({
			position: {
			my: 'bottom left',  // Position my top left...
			at: 'top right', // at the bottom right of...
 
		},
		style: {
			classes: 'qtip-blue qtip-shadow'
		}
	});  
	  
});

