jQuery(document).ready( function() {
	if( jQuery('.wmc-content').hasClass('wmc-products') ){
		jQuery('.wmc-content').css('height', 'auto');
	}

	jQuery(document).on( 'touchstart', function(e) 
	{
		var container = jQuery('.wmc-cart-wrapper');

    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) 
    {
    	jQuery('.wmc-content').hide();
    }
    else{
    	jQuery('.wmc-content').show();
    }
});

});