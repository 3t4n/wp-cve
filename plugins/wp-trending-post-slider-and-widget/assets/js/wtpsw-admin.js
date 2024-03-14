( function($) {

	"use strict";

	/* Click to Copy the Text */
	$(document).on('click', '.wpos-copy-clipboard', function() {
		var copyText = $(this);
		copyText.select();
		document.execCommand("copy");
	});

	/* Drag widget event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.preview-rendered', wtpsw_render_preview );

	/* Save widget event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.layout-rendered', wtpsw_render_preview );

	/* Publish button event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.didSaveNodeSettings', wtpsw_render_preview );

})(jQuery);

/* Function to render shortcode preview for Beaver Builder */
function wtpsw_render_preview() {
	wtpsw_trending_slider_init();
	wtpsw_trending_carousel_init();
}