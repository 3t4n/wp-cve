(function($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

})(jQuery);
function wpvrhotspot(hotSpotDiv, args) {
    var argst = args.replace(/\\/g, '');
    jQuery(document).ready(function($) {
        $(hotSpotDiv.target).parent().siblings(".custom-ifram-wrapper").find('.custom-ifram').html(argst);
        $(hotSpotDiv.target).parent().siblings(".custom-ifram-wrapper").fadeToggle();
        $(hotSpotDiv.target).parent().parent(".pano-wrap").toggleClass("show-modal");
    });
}

function wpvrtooltip(hotSpotDiv, args) {
    hotSpotDiv.classList.add('custom-tooltip');
    var span = document.createElement('span');
    args = args.replace(/\\/g, "");
    span.innerHTML = args;
    hotSpotDiv.appendChild(span);
    span.style.marginLeft = -(span.scrollWidth - hotSpotDiv.offsetWidth) / 2 + 'px';
    span.style.marginTop = -span.scrollHeight - 12 + 'px';
}


jQuery(document).ready(function($) {

    $(".cross").on("click", function(e) {
        e.preventDefault();
        $(this).parent(".custom-ifram-wrapper").fadeOut();
        $(this).parents(".pano-wrap").removeClass("show-modal");

        $('.vr-iframe').attr('src', '');
        // $('iframe').attr('src', $('iframe').attr('src'));
        if ($('#wpvr-video').length != 0) {
            $('#wpvr-video').get(0).pause();
        }

    });

});

jQuery(document).ready(function($) {

    var notice_active = wpvr_public.notice_active;
    var notice = wpvr_public.notice;
    if (notice_active == "true") {
        if (!$.cookie("wpvr_mobile_notice")) {
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                if ($(".pano-wrap")[0]) {
                    $('body').append("<div class='wpvr-mobile-notice'><p>" + notice + "</p> <span class='notice-close'><i class='fa fa-times'></i></span></div>");
                }
            }
        }
    }

    $('.wpvr-mobile-notice .notice-close').on('click', function() {
        $('.wpvr-mobile-notice').fadeOut();
        $.cookie('wpvr_mobile_notice', 'true');
    });
});
