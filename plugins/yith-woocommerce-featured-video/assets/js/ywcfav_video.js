jQuery(function ($) {

    var hide_gallery_trigger_and_onsale_icon = function (e) {
            $('.woocommerce span.onsale:first, .woocommerce-page span.onsale:first').hide();
            $(ywcfav_args.product_gallery_trigger_class).hide();
        },
        show_gallery_trigger_and_onsale_icon = function (e) {
            $('.woocommerce span.onsale:first, .woocommerce-page span.onsale:first').show();
            $(ywcfav_args.product_gallery_trigger_class).show();
        };

    var video_content = $('.ywcfav-video-content'),
        iframe = video_content.find('iframe'),
        iframe_id = iframe.attr('id'),
        player = null;

    if (video_content.length) {
        if (video_content.hasClass('youtube')) {

            window.onYouTubeIframeAPIReady = function () {

                if (typeof YT.Player !== 'undefined') {
                    player = new YT.Player('' + iframe_id + '', {
                        events: {
                            'onStateChange': onPlayerStateChange
                        }
                    });


                }
            };

            iframe.on('onload', onYouTubeIframeAPIReady());

            function onPlayerStateChange(event) {
                if (event.data === YT.PlayerState.PAUSED) {
                    show_gallery_trigger_and_onsale_icon(event);

                } else if (event.data == YT.PlayerState.ENDED) {
                    show_gallery_trigger_and_onsale_icon(event);

                } else if (event.data == YT.PlayerState.PLAYING) {

                    hide_gallery_trigger_and_onsale_icon(event);
                }
            }
        } else {

            if (typeof Vimeo.Player !== 'undefined') {
                player = new Vimeo.Player(iframe_id);


                player.on('play', function () {
                    hide_gallery_trigger_and_onsale_icon();
                });
                player.on('pause', function () {
                    show_gallery_trigger_and_onsale_icon();
                });
            }
        }

        $(document).on('ywcfav_found_variation', function (e, content, variation) {

            if (content.find('.ywcfav-video-content').hasClass('vimeo')) {

                player.pause();
            } else if (content.find('.ywcfav-video-content').hasClass('youtube')) {

                player.pauseVideo();
            }
        });
    }
});
