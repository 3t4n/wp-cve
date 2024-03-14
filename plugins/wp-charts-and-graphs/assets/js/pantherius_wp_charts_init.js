jQuery( document ).ready( function() {
	( function( jQuery ){
		if ( typeof pwpc_params !== 'undefined' ) {
			jQuery.each( pwpc_params, function( key, value ) {
				if ( typeof value.max == undefined ) {
					value.max = 0;
				}
				if ( jQuery( "#pwp-charts-" + key ).length > 0 ) {
					jQuery( "#pwp-charts-" + key ).pmsresults({ "style": value.style, "datas": value.datas });
				}
			});
		}
	})( jQuery );
});