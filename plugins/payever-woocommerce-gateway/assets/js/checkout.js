(function( $ ) {
    "use strict";

    $('body').on('change', 'input[name="payment_method"]', function() { $('body').trigger('update_checkout'); });

    function sendCheckoutNewScrollOffset() {
        var iframe = $('#payever_iframe');
        if (iframe.length) {
            var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            var offsetTop = iframe.offset().top;
            iframe[0].contentWindow.postMessage(
                {
                    'event': 'sendPayeverCheckoutScrollOffset',
                    'scrollTop': scrollTop,
                    'offsetTop': offsetTop,
                    'windowHeight': window.innerHeight,
                }

                , "*");
        }
    }

    if (window.addEventListener) {
        window.addEventListener("message", onMessagePayever, false);
        window.addEventListener('scroll', sendCheckoutNewScrollOffset, false);
        window.addEventListener('resize', sendCheckoutNewScrollOffset, false);
    }
    else if (window.attachEvent) {
        window.attachEvent("onmessage", onMessagePayever, false);
        window.attachEvent('onscroll', sendCheckoutNewScrollOffset, false);
        window.attachEvent('onresize', sendCheckoutNewScrollOffset, false);
    }

    function onMessagePayever(event)
    {
        var payeverIframe = $('#payever_iframe');
        if (event && event.data) {
            switch (event.data.event) {
                case 'payeverCheckoutHeightChanged':
                    payeverIframe.css('height', Math.max(0, parseInt(event.data.value)));
                    break;
                case 'payeverCheckoutScrollOffsetRequested':
                    sendCheckoutNewScrollOffset();

            }
        }
    }
})(jQuery);