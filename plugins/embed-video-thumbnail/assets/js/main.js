(function ($) {
    var IKANAWEB_EVT = {
        init: function () {
            $(document).ready(function () {
                var $iframes = $('iframe.ikn-evt-iframe');

                $iframes.on('click', function () {
                    $(this).addClass('ikn-evt-loaded');
                });
                $iframes.load(function () {
                    var $iframe = $(this);
                    var playInterval = setInterval(function () {
                        try {
                            $iframe[0]
                                .contentWindow
                                .postMessage(
                                    '{"event":"command","func":"playVideo","args":""}',
                                    '*'
                                )
                            ;
                            clearInterval(playInterval)
                        } catch (e) {

                        }
                    }, 100);
                });

                IKANAWEB_EVT.resizeIframes($iframes);
            });

            $(document)
                .on('click', '.ikn-evt-ie .ikn-evt-container', function () {
                    IKANAWEB_EVT.ieDisplayEmbed($(this))
                })
            ;
        },
        resizeIframes: function ($iframes) {
            if ($iframes.length === 0) {
                return;
            }

            $iframes.each(function () {
                IKANAWEB_EVT.resizeIframe($(this));
            });
        },
        resizeIframe: function ($iframe) {
            if ($iframe.hasClass('ikn-evt-loaded')) {
                return;
            }
            try {
                $iframe.height($iframe.contents().height());
            } catch (e) {

            }
        },
        ieDisplayEmbed: function ($container) {
            var iframe = document.createElement("iframe"),
                parentElement = $container.parent();
            embed = parentElement.data('embed-url');
            iframe.setAttribute("src", embed);
            iframe.setAttribute("frameborder", "0");
            iframe.setAttribute("allowfullscreen", "1");
            iframe.setAttribute("allow", "accelerometer; encrypted-media; gyroscope; picture-in-picture");
            parentElement.html(iframe);
            parentElement.find('iframe').trigger('click');
        },
    };
    $(function () {
        IKANAWEB_EVT.init();
    });
})(jQuery);