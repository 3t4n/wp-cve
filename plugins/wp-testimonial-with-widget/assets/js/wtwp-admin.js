(function($) {

	"use strict";

	/* Vertical Tab */
	$( document ).on( "click", ".wtwp-vtab-nav a", function() {

		$(".wtwp-vtab-nav").removeClass('wtwp-active-vtab');
		$(this).parent('.wtwp-vtab-nav').addClass("wtwp-active-vtab");

		var selected_tab = $(this).attr("href");
		$('.wtwp-vtab-cnt').hide();

		/* Show the selected tab content */
		$(selected_tab).show();

		/* Pass selected tab */
		$('.wtwp-selected-tab').val(selected_tab);
		return false;
	});

	/* Remain selected tab for user */
	if( $('.wtwp-selected-tab').length > 0 ) {
		
		var sel_tab = $('.wtwp-selected-tab').val();

		if( typeof(sel_tab) !== 'undefined' && sel_tab != '' && $(sel_tab).length > 0 ) {
			$('.wtwp-vtab-nav [href="'+sel_tab+'"]').click();
		} else {
			$('.wtwp-vtab-nav:first-child a').click();
		}
	}

	/* Click to Copy the Text */
	$(document).on('click', '.wpos-copy-clipboard', function() {
		var copyText = $(this);
		copyText.select();
		document.execCommand("copy");
	});

	/* Drag widget event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.preview-rendered', wtwp_fl_render_preview );

	/* Save widget event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.layout-rendered', wtwp_fl_render_preview );

	/* Publish button event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.didSaveNodeSettings', wtwp_fl_render_preview );

})( jQuery );

/* Function to render shortcode preview for Beaver Builder */
function wtwp_fl_render_preview() {
	wtwp_testimonial_slider_init();
	wtwp_testimonial_widget_init();
}