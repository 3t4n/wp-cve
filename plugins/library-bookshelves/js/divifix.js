// Fixes Bookshelves rendering issues in Divi tabs. Now with multiple Bookshelves in each tab!
jQuery( document ).ready( function($) {
// Add click event to tabs
$('[class^=et_pb_tab]').click( function() {
	// Get the current tab class
	target = $(this).attr("class");
	
	// Wait until the tab has finished transitioning to visible
	setTimeout( function() {
		// Find all the Bookshelves within the current tab
		$( 'div .' + target + ' [class^=bookshelf]' ).each( function() {
			// Get the unqiue IDs of each Bookshelf
			target = '#' + $(this).attr('id');
			// Use the Slick refresh method to make each bookshelf display as expected
			$(target).slick('refresh');
		});
	}, 750);
});
});