(function($) {

    /**
     * Resizes all rumble videos to fit into the container in 16:9 ratio.
     *
     * @returns {void}
     */
    function resize() {
        var rumbleVideos = $("iframe[src*='rumble.com/embed']");

        for (i = 0; i < rumbleVideos.length; i++) {
            if (jQuery(rumbleVideos[i]).parent().is('div.videoWrapper') === true) {
                continue;
            }

            var rumbleVideo = $(rumbleVideos[i]);
            var parentContainer = rumbleVideo.parent();

            rumbleVideo.width(parentContainer.width());
            rumbleVideo.height(parentContainer.width() / 16 * 9);

            // Resize it for the second time to solve the problem with the scrollbar.
            rumbleVideo.width(parentContainer.width());
            rumbleVideo.height(parentContainer.width() / 16 * 9);
        }
    }

    $().ready(function() {
        resize();

        $(window).resize(function() {
            resize();
        });
    });
})(jQuery);