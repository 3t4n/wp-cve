jQuery(document).ready(function ($) {
    var parallaxSection = $('.parallax-section, .parallax-mobile');
    if (parallaxSection.length != 0) {
        var pxcontentOffset = parallaxSection.offset().left;
        $('.parallax-content').css('left', '-' + pxcontentOffset + 'px');
        $(".px-mobile-container").each(function () {
            var i = $(this).attr("data-factor") * $(window).width();
            $(this).css("height", i + "px")
        })
    }
});