/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
var d4plib_metabox;

;(function($, window, document, undefined) {
    d4plib_metabox = {
        init: function() {
            $(".d4plib-metabox-wrapper .wp-tab-bar a").click(function(e){
                e.preventDefault();

                var tab = $(this).attr("href").substring(1),
                    wrap = $(this).closest(".d4plib-metabox-wrapper");

                $(this).closest("ul").find("li").removeClass("wp-tab-active");
                $(this).parent().addClass("wp-tab-active");

                wrap.find(".wp-tab-panel")
                    .removeClass("tabs-panel-active")
                    .addClass("tabs-panel-inactive");

                wrap.find("#" + tab)
                    .removeClass("tabs-panel-inactive")
                    .addClass("tabs-panel-active");
            });

            $(".d4plib-metabox-check-uncheck a").click(function(e){
                e.preventDefault();

                var checkall = $(this).attr("href").substring(1) === "checkall";

                $(this).parent().parent().find("input[type=checkbox]").prop("checked", checkall);
            });
        }
    };

    d4plib_metabox.init();
})(jQuery, window, document);
