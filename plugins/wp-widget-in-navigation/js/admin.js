/*global console,ajaxurl,$,jQuery,ysplwin,document,window,bstw,alert,wp,this*/
/**
 * yspl Menu jQuery Plugin
 */
(function($) {
    "use strict";

    $.fn.ysplwin = function(options) {

        var panel = $("<div />");

        panel.settings = options;

        panel.log = function(message) {
            if (window.console && console.log) {
                console.log(message.data);
            }

            if (message.success !== true) {
                //alert(message.data);
            }
        };


        panel.init = function() {

            panel.log({
                success: true,
                data: ysplwin.debug_launched + " " + panel.settings.menu_item_id
            });

            $.colorbox.remove();

            $.colorbox({
                html: "",
                initialWidth: "75%",
                scrolling: true,
                fixed: true,
                top: "50px",
                initialHeight: "552",
                maxHeight: "570",
            });

            $.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "yspl_get_lightbox_html",
                    _wpnonce: ysplwin.nonce,
                    menu_item_id: panel.settings.menu_item_id,
                    menu_item_depth: panel.settings.menu_item_depth,
                    menu_id: panel.settings.menu_id
                },
                cache: false,
                beforeSend: function() {
                    $("#cboxLoadedContent").empty();
                    $("#cboxClose").empty();
                },
                complete: function() {
                    $("#cboxLoadingOverlay").remove();

                    // fix for WordPress 4.8 widgets when lightbox is opened, closed and reopened
                    if (wp.textWidgets !== undefined) {
                        wp.textWidgets.widgetControls = {}; // WordPress 4.8 Text Widget
                    }

                    if (wp.mediaWidgets !== undefined) {
                        wp.mediaWidgets.widgetControls = {}; // WordPress 4.8 Media Widgets
                    }

                    if (wp.customHtmlWidgets !== undefined) {
                        wp.customHtmlWidgets.widgetControls = {}; // WordPress 4.9 Custom HTML Widgets
                    }

                },
                success: function(response) {

                    $("#cboxLoadingGraphic").remove();

                    var json = $.parseJSON(response.data);

                    var tabs_container = $("<div class='yspl_tab_container' />");

                    var content_container = $("<div class='yspl_content_container' />");

                    if ( json === null ) {
                        content_container.html(response);
                    }

                    $.each(json, function(idx) {

                        var content = $("<div />").html(this.content);

                        var tab = $("<div />").html(this.title);

                        content.show();
                        tabs_container.append(tab);

                        content_container.append(content);
                    });

                    $("#cboxLoadedContent").append(tabs_container).append(content_container);
                    
                    $("#cboxLoadedContent").trigger("ysplwin_content_loaded");
                }
            });
        };
        var start_saving = function() {
            $(".yspl_saving").show();
        }

        var end_saving = function() {
            $(".yspl_saving").fadeOut("fast");
        }

        panel.init();

    };

}(jQuery));

/**
 *
 */
jQuery(function($) {
    "use strict";


    $(".menu").on("click", ".ysplwin_launch", function(e) {
        e.preventDefault();

        $(this).ysplwin();
    });

    $("#menu-to-edit li.menu-item").each(function() {

        var menu_item = $(this);
        var menu_id = $("input#menu").val();
        var title = menu_item.find(".menu-item-title").text();

        menu_item.data("ysplwin_has_button", "true");

        // fix for Jupiter theme
        if (!title) {
            title = menu_item.find(".item-title").text();
        }

        var id = parseInt(menu_item.attr("id").match(/[0-9]+/)[0], 10);

        var button = $("<span>").addClass("yspl_launch")
            .html(ysplwin.launch_lightbox)
            .on("click", function(e) {
                e.preventDefault();

                var depth = menu_item.attr("class").match(/\menu-item-depth-(\d+)\b/)[1];

                $(this).ysplwin({
                    menu_item_id: id,
                    menu_item_title: title,
                    menu_item_depth: depth,
                    menu_id: menu_id
                });
            });

        $(".item-title", menu_item).append(button);

        if (ysplwin.css_prefix === "true") {
            var custom_css_classes = menu_item.find(".edit-menu-item-classes");
            var css_prefix = $("<span>").addClass("yspl_prefix").html(ysplwin.css_prefix_message);
            custom_css_classes.after(css_prefix);
        }
    });
});