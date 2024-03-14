( function( $ ) {

    "use strict";

    /* Vertical Tab */
    $( document ).on( "click", ".bdpw-vtab-nav a", function() {

        $(".bdpw-vtab-nav").removeClass('bdpw-active-vtab');
        $(this).parent('.bdpw-vtab-nav').addClass("bdpw-active-vtab");

        var selected_tab = $(this).attr("href");
        $('.bdpw-vtab-cnt').hide();

        /* Show the selected tab content */
        $(selected_tab).show();

        /* Pass selected tab */
        $('.bdpw-selected-tab').val(selected_tab);
        return false;
    });

    /* Remain selected tab for user */
    if( $('.bdpw-selected-tab').length > 0 ) {

        var sel_tab = $('.bdpw-selected-tab').val();

        if( typeof(sel_tab) !== 'undefined' && sel_tab != '' && $(sel_tab).length > 0 ) {
            $('.bdpw-vtab-nav [href="'+sel_tab+'"]').click();
        } else {
            $('.bdpw-vtab-nav:first-child a').click();
        }
    }

    /* Click to Copy the Text */
    $(document).on('click', '.wpos-copy-clipboard', function() {
        var copyText = $(this);
        copyText.select();
        document.execCommand("copy");
    });

    /* Drag widget event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.preview-rendered', bdpw_fl_render_preview );

	/* Save widget event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.layout-rendered', bdpw_fl_render_preview );

	/* Publish button event to render layout for Beaver Builder */
	$('.fl-builder-content').on( 'fl-builder.didSaveNodeSettings', bdpw_fl_render_preview );
})( jQuery );

/* Function to render shortcode preview for Beaver Builder */
function bdpw_fl_render_preview() {
	bdpw_post_slider_init();
}