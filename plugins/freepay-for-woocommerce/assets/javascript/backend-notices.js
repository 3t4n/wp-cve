(function($, config) {
    $(function() {
        $(document.body).on('click', '.wcfp-notice .notice-dismiss', function() {
            $.post(config.flush)
        });
    });
})(jQuery, window.wcfpBackendNotices || {});