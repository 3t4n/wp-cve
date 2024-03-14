/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
var d4plib_admin;

;(function($, window, document, undefined) {
    d4plib_admin = {
        scroll_offset: 40,
        active_element: null,
        init: function() {
            $(".d4p-nav-button > a").click(function(e){
                e.preventDefault();

                $(this).next().slideToggle("fast");
            });

            setTimeout(function(){
                $(".d4p-wrap .updated").slideUp("slow");
            }, 4000);

            $(window).bind("load resize orientationchange", function(){
                if (document.body.clientWidth < 800) {
                    d4plib_admin.scroll_offset = 60;
                } else {
                    d4plib_admin.scroll_offset = 40;
                }

                if (document.body.clientWidth < 640) {
                    $(".d4p-panel-scroller").removeClass("d4p-scroll-active");
                } else {
                    $(".d4p-panel-scroller").addClass("d4p-scroll-active");
                }
            });

            $(".d4p-check-uncheck a").click(function(e){
                e.preventDefault();

                var checkall = $(this).attr("href").substring(1) === "checkall";

                $(this).parent().parent().find("input[type=checkbox]").prop("checked", checkall);
            });
        },
        settings: function() {
            if (typeof d4plib_media_image !== 'undefined' && d4plib_media_image !== null) {
                d4plib_media_image.init();
            }

            if ($.numeric) {
                $(".d4p-setting-number input, .d4p-field-number").numeric();
                $(".d4p-setting-integer input, .d4p-field-integer").numeric({decimalPlaces: 0, negative: false});
            }

            $(".d4p-color-picker").wpColorPicker();

            $(document).on("click", ".d4p-group h3 i.fa.fa-caret-down, .d4p-group h3 i.fa.fa-caret-up", function() {
                var closed = $(this).hasClass("fa-caret-down"),
                    content = $(this).parent().next();

                if (closed) {
                    $(this).removeClass("fa-caret-down").addClass("fa-caret-up");
                    content.slideDown(300);
                } else {
                    $(this).removeClass("fa-caret-up").addClass("fa-caret-down");
                    content.slideUp(300);
                }
            });

            $(document).on("click", ".d4p-section-toggle .d4p-toggle-title", function() {
                var icon = $(this).find("i.fa"),
                    closed = icon.hasClass("fa-caret-down"),
                    content = $(this).next();

                if (closed) {
                    icon.removeClass("fa-caret-down").addClass("fa-caret-up");
                    content.slideDown(300);
                } else {
                    icon.removeClass("fa-caret-up").addClass("fa-caret-down");
                    content.slideUp(300);
                }
            });

            $(document).on("click", ".d4p-setting-expandable_pairs .button-secondary", function(e){
                e.preventDefault();

                var li = $(this).parent();

                li.fadeOut(200, function(){
                    li.remove();
                });
            });

            $(".d4p-setting-expandable_pairs a.button-primary").click(function(e) {
                e.preventDefault();

                var list = $(this).closest(".d4p-setting-expandable_pairs"),
                    next = $(".d4p-next-id", list),
                    next_id = next.val(),
                    el = $(".pair-element-0", list).clone();

                $("input", el).each(function(){
                    var id = $(this).attr("id").replace("_0_", "_" + next_id + "_"),
                        name = $(this).attr("name").replace("[0]", "[" + next_id + "]");

                    $(this).attr("id", id).attr("name", name);
                });

                el.attr("class", "pair-element-" + next_id).fadeIn();
                $(this).before(el);

                next_id++;
                next.val(next_id);
            });

            $(document).on("click", ".d4p-setting-expandable_text .button-secondary", function(e){
                d4plib_admin.handlers.expendable_text_remove(this, e);
            });

            $(document).on("click", ".d4p-setting-expandable_raw .button-secondary", function(e){
                d4plib_admin.handlers.expendable_text_remove(this, e);
            });

            $(document).on("click", ".d4p-setting-expandable_regex .button-secondary", function(e){
                d4plib_admin.handlers.expendable_text_remove(this, e);
            });

            $(".d4p-setting-expandable_text a.button-primary").click(function(e) {
                d4plib_admin.handlers.expendable_text_add(this, e, ".d4p-setting-expandable_text");
            });

            $(".d4p-setting-expandable_raw a.button-primary").click(function(e) {
                d4plib_admin.handlers.expendable_text_add(this, e, ".d4p-setting-expandable_raw");
            });

            $(".d4p-setting-expandable_regex a.button-primary").click(function(e) {
                d4plib_admin.handlers.expendable_text_add(this, e, ".d4p-setting-expandable_regex");
            });
        },
        scroller: function() {
            var $sidebar = $(".d4p-panel-scroller"), 
                $window = $(window);

            if ($sidebar.length > 0) {
                var offset = $sidebar.offset();

                $window.scroll(function() {
                    if ($window.scrollTop() > offset.top && $sidebar.hasClass("d4p-scroll-active")) {
                        $sidebar.stop().animate({
                            marginTop: $window.scrollTop() - offset.top + d4plib_admin.scroll_offset
                        });
                    } else {
                        $sidebar.stop().animate({
                            marginTop: 0
                        });
                    }
                });
            }
        },
        handlers: {
            expendable_text_remove: function(ths, e) {
                e.preventDefault();

                var li = $(ths).parent();

                li.fadeOut(200, function(){
                    li.remove();
                });
            },
            expendable_text_add: function(ths, e, cls) {
                e.preventDefault();

                var list = $(ths).closest(cls),
                    next = $(".d4p-next-id", list), 
                    next_id = next.val(),
                    el = $(".exp-text-element-0", list).clone();

                $("input", el).each(function(){
                    var id = $(this).attr("id").replace("_0_", "_" + next_id + "_"),
                        name = $(this).attr("name").replace("[0]", "[" + next_id + "]");

                    $(this).attr("id", id).attr("name", name);
                });

                el.attr("class", "exp-text-element exp-text-element-" + next_id).fadeIn();
                $("ol", list).append(el);

                next_id++;
                next.val(next_id);
            }
        }
    };

    d4plib_admin.init();
    d4plib_admin.settings();
    d4plib_admin.scroller();
})(jQuery, window, document);
