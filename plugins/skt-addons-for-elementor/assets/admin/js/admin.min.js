"use strict";
!(function (n) {
    n(function () {
        var a = n(".sktjs-clear-cache"),
            e = n("#toplevel_page_skt-addons .toplevel_page_skt-addons .wp-menu-name"),
            c = e.text();
        e.text(c.replace(/\s/, " ")),
            a.on("click", "a", function (a) {
                a.preventDefault();
                var e = "all",
                    c = n(a.delegateTarget);
                c.hasClass("skt-clear-page-cache") && (e = "page"),
                    c.addClass("skt-clear-cache--init"),
                    n.post(SktAdmin.ajax_url, { action: "skt_addons_elementor_clear_cache", type: e, nonce: SktAdmin.nonce, post_id: SktAdmin.post_id }).done(function (a) {
                        c.removeClass("skt-clear-cache--init").addClass("skt-clear-cache--done");
                    });
            });
    });
})(jQuery);
