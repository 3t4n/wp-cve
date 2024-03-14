(function() {
    /**
     * Connect integration function
     */
    function connectIntegration(testMode) {
        var $ = jQuery.noConflict();

        var data = {
            action: "furgonetka_connect_integration",
            test_mode: testMode ? 1 : 0,
        };

        $.post(
            furgonetka_connect_integration.ajax_url,
            data,
            function (response) {
                if (response.success && response.data && response.data.redirect_url) {
                    window.location.href = response.data.redirect_url;
                } else {
                    if (response.data && response.data.error_message) {
                        alert(response.data.error_message);
                    }
                }
            }
        );
    }

    /**
     * Post messages handler
     */
    window.addEventListener("message", function (event) {
        /**
         * Validate origin
         */
        if (event.origin !== furgonetka_connect_integration.furgonetka_shop_base_url) {
            return;
        }

        /**
         * Connect account
         */
        if (event.data.action === "connectAccount") {
            connectIntegration(event.data.testMode);
        }
    });
})();