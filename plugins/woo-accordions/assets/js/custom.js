(function ($) {

	var FirstChildExpand = wooatm.first_child_expand == 'yes' ? true : false;
	var MultipleExpand = wooatm.multiple_expand == 'yes' ? true : false;
  $( '#accordion-container' ).accordion({
  	firstChildExpand : FirstChildExpand,
    slideSpeed			 : 500,
    dropDownIcon		 : "&#9660",
    multiExpand 		 : MultipleExpand,
  });

	$( 'a.woocommerce-review-link' ).click( function(e) {
		e.preventDefault();
		if( $('#tab-reviews').is(':hidden') )
			$( '.reviews_tab' ).click();
		$('html, body').animate({
		    scrollTop: $("#tab-reviews").offset().top - 40
		}, 1000);
		return true;
	});

	var hash  = window.location.hash;
	var url   = window.location.href;
	if ( hash.toLowerCase().indexOf( 'comment-' ) >= 0 || hash === '#reviews' ) {
		$( 'li.reviews_tab' ).click();
	} else if ( url.indexOf( 'comment-page-' ) > 0 || url.indexOf( 'cpage=' ) > 0 ) {
		$( 'li.reviews_tab' ).click();
	}
})(jQuery);