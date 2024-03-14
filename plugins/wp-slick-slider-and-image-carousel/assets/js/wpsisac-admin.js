( function( $ ) {

	"use strict";

	/* Vertical Tab */
	$( document ).on( "click", ".wpsisac-vtab-nav a", function() {

		$(".wpsisac-vtab-nav").removeClass('wpsisac-active-vtab');
		$(this).parent('.wpsisac-vtab-nav').addClass("wpsisac-active-vtab");

		var selected_tab = $(this).attr("href");
		$('.wpsisac-vtab-cnt').hide();

		/* Show the selected tab content */
		$(selected_tab).show();

		/* Pass selected tab */
		$('.wpsisac-selected-tab').val(selected_tab);
		return false;
	});

	/* Remain selected tab for user */
	if( $('.wpsisac-selected-tab').length > 0 ) {
		
		var sel_tab = $('.wpsisac-selected-tab').val();

		if( typeof(sel_tab) !== 'undefined' && sel_tab != '' && $(sel_tab).length > 0 ) {
			$('.wpsisac-vtab-nav [href="'+sel_tab+'"]').click();
		} else {
			$('.wpsisac-vtab-nav:first-child a').click();
		}
	}

	/* Click to Copy the Text */
	$(document).on('click', '.wpos-copy-clipboard', function() {
		var copyText = $(this);
		copyText.select();
		document.execCommand("copy");
	});

	/* Drag widget event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.preview-rendered', wpsisac_fl_render_preview );

	/* Save widget event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.layout-rendered', wpsisac_fl_render_preview );

	/* Publish button event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.didSaveNodeSettings', wpsisac_fl_render_preview );
})( jQuery );

/* Function to render shortcode preview for Beaver Builder */
function wpsisac_fl_render_preview() {
	wpsisac_slick_slider_init();
	wpsisac_slick_carousel_init();
}