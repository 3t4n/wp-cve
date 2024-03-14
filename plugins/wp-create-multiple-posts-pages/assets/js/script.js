jQuery( document ).ready( function( $ )
{
	$( ".new_line_number" ).linedtextarea( {
		selectedLine: 1
	} );

	$( "#wpcmp_new_post_category" ).select2();

	$( "#wpcmp_new_post_type" ).change( function( event )
	{
		if ( $( this ).val() !== 'post' )
		{
			$( "#wpcmp_new_post_category" ).prop( 'disabled', 'disabled' );
		}
		else
		{
			$( "#wpcmp_new_post_category" ).removeAttr( 'disabled' );
		}
	} );

	$( "#wpcmp_posts_titles" ).keyup( function( event )
	{
		if ( $( this ).val() == '' )
		{
			$( "#wpcmp_new_post_type" ).prop( 'disabled', 'disabled' );
			
			$( "#wpcmp_new_post_status" ).prop( 'disabled', 'disabled' );
			
			$( "#wpcmp_new_post_author" ).prop( 'disabled', 'disabled' );
			
			$( "#wpcmp_new_post_category" ).prop( 'disabled', 'disabled' );
			
			$( "#wpcmp_submit_for_create_posts" ).prop( 'disabled', 'disabled' );
		}
		else
		{
			$( "#wpcmp_new_post_type" ).removeAttr( 'disabled' );
			
			$( "#wpcmp_new_post_status" ).removeAttr( 'disabled' );
			
			$( "#wpcmp_new_post_author" ).removeAttr( 'disabled' );
			
			$( "#wpcmp_new_post_category" ).removeAttr( 'disabled' );
			
			$( "#wpcmp_submit_for_create_posts" ).removeAttr( 'disabled' );
		}
	} );
} );