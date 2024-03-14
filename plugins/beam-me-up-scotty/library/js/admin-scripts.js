( function( $ ) {
    
    $( document ).ready( function() {
    	
	    // Handle clicking a purchase link
		$( 'a.purchase' ).click( function(e) {
	    	e.preventDefault();
		    window.open( $(this).attr( 'href' ), '_blank', 'width=960,height=800,resizeable,scrollbars' );
		    return false;
		});
    	
    });
    
    $(window).resize(function () {
    }).resize();
    
    $(window).on('load', function() {
    });
    
} )( jQuery );