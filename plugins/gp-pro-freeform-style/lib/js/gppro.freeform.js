//********************************************************************************************************************************
// load global preview CSS
//********************************************************************************************************************************
function gppro_freeform_preview( DPPFreeformVal, DPPViewport ) {

	// set a variable for the style set
	var framehead	= jQuery( '#gppro-preview-frame' ).contents().find( 'head' );

	// add the mobile only viewport
	if ( DPPViewport == 'mobile' ) {
		jQuery( framehead ).append( '<style class="gppro-preview-css" type="text/css">@media only screen and (max-width: 480px) {' + DPPFreeformVal + ' }</style>' );
	}

	// add the tablet only viewport
	if ( DPPViewport == 'tablet' ) {
		jQuery( framehead ).append( '<style class="gppro-preview-css" type="text/css">@media only screen and (max-width: 768px) {' + DPPFreeformVal + ' }</style>' );
	}

	// add the desktop only viewport
	if ( DPPViewport == 'desktop' ) {
		jQuery( framehead ).append( '<style class="gppro-preview-css" type="text/css">@media only screen and (min-width: 1024px) {' + DPPFreeformVal + ' }</style>' );
	}

	// add the global viewport
	if ( DPPViewport == 'global' ) {
		jQuery( framehead ).append( '<style class="gppro-preview-css" type="text/css">' + DPPFreeformVal + '</style>' );
	}
}

//********************************************************************************************************************************
// now start the engine
//********************************************************************************************************************************
jQuery(document).ready( function($) {

	var DPPTextWrap;
	var DPPViewport;
	var DPPFreeformVal;

//********************************************************************************************************************************
// load global preview
//********************************************************************************************************************************
	$( '.gppro-freeform-wrap' ).on( 'click', '.gppro-freeform-preview', function () {

		// get parent wrap
		DPPTextWrap = $( this ).parents( '.gppro-freeform-wrap' );

		// get viewport
		DPPViewport = $( this ).data( 'viewport' );

		// set viewport to global if not present
		if ( DPPViewport === '' ) {
			DPPViewport = 'global';
		}

		// get CSS values for preview reload
		DPPFreeformVal = $( DPPTextWrap ).find( 'textarea' ).val();

		// bail if no values being passed
		if ( DPPFreeformVal === '' ) {
			return;
		}

		// re process preview
		if ( $( '.gppro-frame-wrap' ).is( ':visible' ) ) {
			gppro_freeform_preview( DPPFreeformVal, DPPViewport );
		}
	});

//********************************************************************************************************************************
//  you're still here? it's over. go home.
//********************************************************************************************************************************
});
