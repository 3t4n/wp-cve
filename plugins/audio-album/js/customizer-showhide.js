jQuery(function($) {

	//* The controls to be hidden
	var controls = $( '#customize-control-bgcol, #customize-control-playr, #customize-control-tvcol, #customize-control-txtbt' );

	//* Hide/show the controls depending on whether the 'manual CSS' checkbox is checked on load,
	if( $( '#customize-control-manualcss input' ).prop( "checked" ) ){
		controls.hide();
	} else {
		controls.show();
	}

	//* Hide/show the controls when the 'manual CSS' checkbox is checked/unchecked
	$( '#customize-control-manualcss input' ).change(function(){
		if( $( '#customize-control-manualcss input' ).prop("checked") ) {
			controls.slideUp();
		} else {
			controls.slideDown();
		}
	});

});
