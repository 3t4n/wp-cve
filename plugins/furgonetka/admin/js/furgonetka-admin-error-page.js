(function() {
    /**
     * Post messages handler
     */
    window.addEventListener("message", function (event) {
        /**
         * Validate origin
         */
        if (event.origin !== furgonetka_error_page.furgonetka_shop_base_url) {
            return;
        }

        /**
         * Redirect
         */
        if (event.data.action === "furgonetkaRedirect") {
            window.location.href = furgonetka_error_page.furgonetka_module_settings_page_url;
        }
    });
})();