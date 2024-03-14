(function($){
	
	
	function initialize_field( $el ) {
		var id = $el.attr('data-key');
		// $el.doStuff();
		// console.log('button field initialized', $el);
		// console.log('button field id', id);

		//add listeners for field visibility
		//toggle url field with page link value = null
		$('select#' + id + '_type').on('change blur load', function(e){
			var id = $(this).parents('.acf-field-button').attr('data-key');
			var selected_val = $(this).val();
			// console.log('selected', id, selected_val );

			switch ( selected_val ) {
				case 'custom':
					$(this).parents('.acf-field-button').find('.acf-button-link').hide();
					$(this).parents('.acf-field-button').find('.acf-button-url').show();
					break;
				default: // != custom
					$(this).parents('.acf-field-button').find('.acf-button-link').hide();
					$(this).parents('.acf-field-button').find('.acf-button-' + selected_val).show();
			}

		}).blur();
		//set initial states based on values by triggering a blur on select
		
	}
	
	
	if( typeof acf.add_action !== 'undefined' ) {
	
		/*
		*  ready append (ACF5)
		*
		*  These are 2 events which are fired during the page load
		*  ready = on page load similar to $(document).ready()
		*  append = on new DOM elements appended via repeater field
		*
		*  @type	event
		*  @date	20/07/13
		*
		*  @param	$el (jQuery selection) the jQuery element which contains the ACF fields
		*  @return	n/a
		*/
		
		acf.add_action('ready append', function( $el ){
			
			// search $el for fields of type 'button'
			acf.get_fields({ type : 'button'}, $el).each(function(){
				
				initialize_field( $(this) );

				
			});
			
		});
		
		
	} else {
		
		
		/*
		*  acf/setup_fields (ACF4)
		*
		*  This event is triggered when ACF adds any new elements to the DOM. 
		*
		*  @type	function
		*  @since	1.0.0
		*  @date	01/01/12
		*
		*  @param	event		e: an event object. This can be ignored
		*  @param	Element		postbox: An element which contains the new HTML
		*
		*  @return	n/a
		*/
		
		$(document).on('acf/setup_fields', function(e, postbox){
			
			$(postbox).find('.field[data-field_type="button"]').each(function(){
				
				initialize_field( $(this) );
				
			});
		
		});
	
	
	}


})(jQuery);
