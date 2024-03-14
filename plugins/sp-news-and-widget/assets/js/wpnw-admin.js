(function($) {

	"use strict";

	/* Vertical Tab */
	$( document ).on( "click", ".wpnw-vtab-nav a", function() {

		$(".wpnw-vtab-nav").removeClass('wpnw-active-vtab');
		$(this).parent('.wpnw-vtab-nav').addClass("wpnw-active-vtab");

		var selected_tab = $(this).attr("href");
		$('.wpnw-vtab-cnt').hide();

		/* Show the selected tab content */
		$(selected_tab).show();

		/* Pass selected tab */
		$('.wpnw-selected-tab').val(selected_tab);
		return false;
	});

	/* Remain selected tab for user */
	if( $('.wpnw-selected-tab').length > 0 ) {
		
		var sel_tab = $('.wpnw-selected-tab').val();

		if( typeof(sel_tab) !== 'undefined' && sel_tab != '' && $(sel_tab).length > 0 ) {
			$('.wpnw-vtab-nav [href="'+sel_tab+'"]').click();
		} else {
			$('.wpnw-vtab-nav:first-child a').click();
		}
	}

	/* Click to Copy the Text */
	$(document).on('click', '.wpos-copy-clipboard', function() {
		var copyText = $(this);
		copyText.select();
		document.execCommand("copy");
	});

	/* Drag widget event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.preview-rendered', wpnw_fl_render_preview );

	/* Save widget event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.layout-rendered', wpnw_fl_render_preview );

	/* Publish button event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.didSaveNodeSettings', wpnw_fl_render_preview );

})(jQuery);

/* Function to render shortcode preview for Beaver Builder */
function wpnw_fl_render_preview() {
	news_scrolling_slider_init();
}