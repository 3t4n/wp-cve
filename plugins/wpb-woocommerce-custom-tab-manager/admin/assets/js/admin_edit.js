(function($) {

	// we create a copy of the WP inline edit post function
	var $wp_inline_edit = inlineEditPost.edit;

	// and then we overwrite the function with our own code
	inlineEditPost.edit = function( id ) {

		// "call" the original WP edit function
		// we don't want to leave WordPress hanging
		$wp_inline_edit.apply( this, arguments );

		// now we take care of our business

		// get the post ID
		var $post_id = 0;
		if ( typeof( id ) == 'object' ) {
			$post_id = parseInt( this.getId( id ) );
		}

		if ( $post_id > 0 ) {
			// define the edit row
			var $edit_row = $( '#edit-' + $post_id );
			var $post_row = $( '#post-' + $post_id );

			// get the data
			var $wpb_wctm_priority = $( '.column-priority', $post_row ).text();
			var $wpb_wctm_active_tab = $('.column-visibility .wpb-wctm-icon', $post_row ).attr('data-wpb-wctm-visibility-show');

			// populate the data
			$( ':input[name="wpb_wctm_priority"]', $edit_row ).val( $wpb_wctm_priority );
			if( $wpb_wctm_active_tab == 'true' ){
				$( ':input[name="wpb_wctm_active_tab"]', $edit_row ).prop('checked', $wpb_wctm_active_tab );
			}
			
		}
	};

})(jQuery);