( function( $ ) {
   $( document ).ready( function() {
      
   		// submit settings with ajax
   		$('#expand_divi_form').submit(function() {
   			$('#expand_divi_save').html("<div id='expand_divi_save_message'>saving...</div>");
	   		$(this).ajaxSubmit({
		   		success: function(){
		   			$('#expand_divi_save_message').text("Saved!");
		   		},
		   		timeout: 5000
	   		});
	   		setTimeout( function(){ $('#expand_divi_save_message').fadeOut('3000'); }, 5000 );
	   		return false;
   		});


   		// accordion style to plugin page options
   		var sections_wrap = $('.expand_divi_sections_wrap'),
   			table = sections_wrap.find('table.form-table');

   		table.not( ':eq(0)' ).hide();
   		sections_wrap.children('h2').on( 'click', function(){
   			sections_wrap.find('h2').removeClass('selected');
   			$(this).addClass('selected');
   			table.hide();
   			$(this).next().show();
   		});

         // active class to select when it's not disabled
         sections_wrap.find('select').each( function(){
            var text = $(this).find('option[selected="selected"]').text();
            if ( text == 'Disabled') {
               $(this).addClass('ed_option_disabled');
            }
         });

   });
})( jQuery );

