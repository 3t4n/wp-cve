
jQuery( function( $ ){

	var $wp_inline_edit = inlineEditPost.edit;

	inlineEditPost.edit = function( id ) {

		$wp_inline_edit.apply( this, arguments );

		/*
		 * Get the post ID.
		 */
		var $post_id = 0;

		if ( typeof( id ) == 'object' ) {

			$post_id = parseInt( this.getId( id ) );
		}

		if ( $post_id > 0 ) {

			/*
			 * Define the edit row.
			 */
			var $edit_row = $( '#edit-' + $post_id );
			var $post_row = $( '#post-' + $post_id );
			var $allow_ratings_opt_key = 'rar_allow_ratings';

			/*
			 * Get the data. Hidden input value is 0 or 1.
			 */
			var $allow_ratings_value = $( 'input[name="' + $allow_ratings_opt_key + '"]', $post_row ).val();

			/*
			 * Populate the data.
			 */
			if ( $allow_ratings_value == 1 ) {

				$( ':input[name="' + $allow_ratings_opt_key + '"]', $edit_row ).prop( 'checked', true );
			}
		}
	};
});

