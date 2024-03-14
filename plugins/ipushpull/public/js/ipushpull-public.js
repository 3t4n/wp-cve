(function($) {
    "use strict";

    var iframes = {};

    window.addEventListener(
        "message",
        function(event) {
            if (event.data && event.data.event == "stage") {
                console.log("wordpress message", event);
                iframes[event.data.message.iframe] = event.data.message;
                setIframeHeight(event.data.message);
            }
        },
        false
    );

    window.addEventListener(
        "resize",
        function(event) {
            for (var i in iframes) {
                setIframeHeight(iframes[i]);
            }
        },
        false
    );

    function setIframeHeight(data) {
        var iframe = document.getElementById(data.iframe);
        var responsive = iframe.classList.contains("ipushpull-page-responsive");
        if (!responsive) return;
        var prop = data.stage.y.size / data.stage.x.size;
        var container = iframe.getBoundingClientRect();
        if (!container.width) {
            // loop back through parent until we find a width!
            var parent = iframe;
            while(parent.parentNode) {
                parent = parent.parentNode;
                container = parent.getBoundingClientRect();
                if (container.width && container.width < window.innerWidth) {
                    break;
                }
            }
        }
        var toolbar = data.toolbar ? 32 : 0;
        iframe.height = (Math.round(prop * container.width) + toolbar) + "px";
        iframe.style.height = (Math.round(prop * container.width) + toolbar) + "px";
        iframes[data.iframe] = data;
    }
})(jQuery);
