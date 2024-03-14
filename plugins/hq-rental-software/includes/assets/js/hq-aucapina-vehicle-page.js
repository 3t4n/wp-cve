jQuery( document ).ready(function( $ ) {
    if($( '#vehicle-images-slider' )){
        $( '#vehicle-images-slider' ).sliderPro({
            autoHeight: true,
            arrows: true,
            width: '100%',
            buttons: false,
            autoplay: false
        });
        $('[data-fancybox="gallery"]').fancybox({
            // Close existing modals
            // Set this to false if you do not need to stack multiple instances
            closeExisting: false,

            // Enable infinite gallery navigation
            loop: false,

            // Horizontal space between slides
            gutter: 50,

            // Enable keyboard navigation
            keyboard: true,

            // Should allow caption to overlap the content
            preventCaptionOverlap: true,

            // Should display navigation arrows at the screen edges
            arrows: true,

            // Should display counter at the top left corner
            infobar: true,

            // Should display close button (using `btnTpl.smallBtn` template) over the content
            // Can be true, false, "auto"
            // If "auto" - will be automatically enabled for "html", "inline" or "ajax" items
            smallBtn: "auto",

            // Should display toolbar (buttons at the top)
            // Can be true, false, "auto"
            // If "auto" - will be automatically hidden if "smallBtn" is enabled
            toolbar: "auto",

            // What buttons should appear in the top right corner.
            // Buttons will be created using templates from `btnTpl` option
            // and they will be placed into toolbar (class="fancybox-toolbar"` element)
            buttons: [
                "zoom",
                //"share",
                "slideShow",
                //"fullScreen",
                //"download",
                "thumbs",
                "close"
            ],
        });
    }
});
jQuery(document).ready(function(){
    var campers = jQuery('#home-vehicle-types .owl-item:first a');
    var autocaravanas = jQuery('#home-vehicle-types .owl-item:nth-child(2) a');
    campers.attr('href','/campers/');
    autocaravanas.attr('href','/autocaravanas/');
    //jQuery('.c-header_book-now').attr('href','/reservations/');
});