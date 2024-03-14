window.FurgonetkaAdmin = {
    /**
     * Contains handle for currently pending quick action AJAX request
     */
    quickActionAjaxHandle: null,

    /**
     * Quick action handler
     *
     * @param event
     */
    quickAction: function (event) {
        var $ = jQuery.noConflict();

        event.preventDefault();

        if (event.currentTarget) {
            var target = $(event.currentTarget);

            if (target.attr("data-order-id")) {
                var data = {
                    action: "furgonetka_quick_action_init",
                    order_id: target.attr("data-order-id")
                };

                $(".furgonetka-modal #furgonetka-iframe iframe").attr("src", "about:blank");
                $(".furgonetka-modal").removeClass("furgonetka-modal-hidden");

                window.FurgonetkaAdmin.quickActionAjaxHandle = $.post(
                    furgonetka_quick_action.ajax_url,
                    data,
                    function (response) {
                        window.FurgonetkaAdmin.quickActionAjaxHandle = null;

                        if (response.success && response.data && response.data.url) {
                            $(".furgonetka-modal #furgonetka-iframe iframe").attr("src", response.data.url);
                        } else {
                            if (response.data && response.data.error_message) {
                                alert(response.data.error_message);
                            }

                            $(".furgonetka-modal").addClass("furgonetka-modal-hidden");

                            if (response.data && response.data.redirect_url) {
                                window.location.href = response.data.redirect_url;
                            }
                        }
                    }
                );
            }
        }
    }
};

jQuery(document).ready(function($) {
    /**
     * Quick action modal close listener
     */
    $("#furgonetka-iframe-exit").click(function (e) {
        e.preventDefault();

        if (window.FurgonetkaAdmin.quickActionAjaxHandle) {
            window.FurgonetkaAdmin.quickActionAjaxHandle.abort();
            window.FurgonetkaAdmin.quickActionAjaxHandle = null;
        }

        $(".furgonetka-modal").addClass("furgonetka-modal-hidden");
        $(".furgonetka-modal #furgonetka-iframe iframe").attr("src", "about:blank");
    });
});
