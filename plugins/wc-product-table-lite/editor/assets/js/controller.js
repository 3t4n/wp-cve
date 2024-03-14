if (typeof wcpt === "undefined") {
    var wcpt = {
        controller: {},
        data: {}
    };
}

jQuery(function ($) {
    var controller = wcpt.controller,
        data = wcpt.data;

    /* handler functions */

    // update table title/name
    controller.update_table_title = function () {
        var $this = $(this),
            new_title = $this.val();
        $('.wcpt-editor-save-table [name="title"]').val(new_title);
    };

    // switch editor tabs
    controller.switch_editor_tabs = function () {
        var $this = $(this),
            tab = $this.attr("data-wcpt-tab"),
            $labels = $this.siblings(".wcpt-tab-label"),
            $contents = $this.siblings(".wcpt-tab-content"),
            $target_content = $contents.filter("[data-wcpt-tab=" + tab + "]"),
            active_class = "active";

        $labels.removeClass(active_class);
        $this.addClass(active_class);

        $contents.removeClass(active_class);
        $target_content.addClass(active_class);

        window.location.hash = tab;
    };

    // toggle sub categories
    controller.toggle_sub_categories = function () {
        var $this = $(this);
        $this.parent().toggleClass("wcpt-show-sub-categories");
    };

    // auto select on click
    controller.auto_select_on_click = function () {
        var node = this;
        if (document.body.createTextRange) {
            const range = document.body.createTextRange();
            range.moveToElementText(node);
            range.select();
        } else if (window.getSelection) {
            const selection = window.getSelection();
            const range = document.createRange();
            range.selectNodeContents(node);
            selection.removeAllRanges();
            selection.addRange(range);
        }
    };

    // // checklist
    // //-- saved
    // $('body').on('wcpt_save', function(){
    //   $('[data-wcpt-ck="saved"]').addClass('wcpt-done');
    // })
    // //-- query_selected
    // $('body').on('change', '.wcpt-editor > [wcpt-model-key="query"]', function(){
    //   if(
    //     ( typeof wcpt.data.query.category !== 'undefined' && wcpt.data.query.category.length ) ||
    //     wcpt.data.query.ids ||
    //     wcpt.data.query.skus
    //   ){
    //     $('[data-wcpt-ck="query_selected"]').addClass('wcpt-done');
    //   }else{
    //     $('[data-wcpt-ck="query_selected"]').removeClass('wcpt-done');
    //   }
    // })
    // //-- column_element_created
    // $('body').on('change', '.wcpt-editor > [wcpt-model-key="columns"]', function(){
    //   var column_element_created = false;
    //   if( wcpt.data.columns.laptop.length ){
    //     $.each(wcpt.data.columns.laptop, function(i_col, col){
    //       // heading content element
    //       if( col.heading.content[0].elements.length ){
    //         column_element_created = true;
    //         return false;
    //       }
    //       // cell template[row] element
    //       $.each(col.cell.template, function( i_row, row ){
    //         if( row.elements.length ){
    //           column_element_created = true;
    //           return false;
    //         }
    //       })
    //     })
    //   }

    //   if(  column_element_created ){
    //     $('[data-wcpt-ck="column_element_created"]').addClass('wcpt-done');
    //   }else{
    //     $('[data-wcpt-ck="column_element_created"]').removeClass('wcpt-done');
    //   }
    // })

    // save JSON data to server
    controller.save_data = function (e) {
        e.preventDefault();

        // ensure change is triggered on any focused input
        var $focused_input = $("input:focus, textarea:focus");
        if ($focused_input.length && $focused_input.attr("wcpt-model-key")) {
            $focused_input.trigger("change");
        }

        $("body").trigger("wcpt_save");

        data.version = window.wcpt_version;

        var $this = $(this), // form
            post_id = $this.find("input[name='post_id']").val(),
            title = $this.find("input[name='title']").val(),
            nonce = $this.find("input[name='nonce']").val(),
            json_data = JSON.stringify(data),
            $button = $this.find(".wcpt-save"),
            action = $this.attr("action");

        if (!$this.hasClass("wcpt-saving")) {
            $.ajax({
                type: "POST",
                url: ajaxurl,

                beforeSend: function () {
                    $this.addClass("wcpt-saving");
                    $button.addClass("disabled");
                },

                data: {
                    action: action,
                    wcpt_post_id: post_id,
                    wcpt_title: title,
                    wcpt_nonce: nonce,
                    wcpt_data: json_data
                },

                success: function (data) {
                    $this.removeClass("wcpt-saving");
                    $button.removeClass("disabled");

                    // success
                    if (
                        typeof data == "string" &&
                        -1 !== data.indexOf("WCPT success:")
                    ) {
                        console.log(data);

                        // failure
                    } else {
                        alert(data);
                    }
                }
            });
        }
    };

    /* dynamic input wrapper */

    controller.open_dynamic_input_wrapper = function () {
        var $input = $(this);

        if (
            $input.parent().hasClass("wcpt-diw") ||
            $input.hasClass("wcpt-diw--disabled")
        ) {
            return;
        }

        var prev_style = $input.attr("style"),
            style = { width: $input.outerWidth() };

        $.each(
            ["float", "margin", "top", "right", "bottom", "left"],
            function (key, prop) {
                style[prop] = $input.css(prop);
            }
        );

        if ($input.css("position") == "absolute") {
            style["position"] = "absolute";
        }

        var $wrap = $('<div class="wcpt-diw">');
        $wrap.css(style);
        $input.wrap($wrap);

        $input.focus();

        $("body").on(
            "blur mousedown keydown",
            controller.close_dynamic_input_wrapper
        );

        $input.after('<div class="wcpt-diw-tray">');
        var $tray = $input.next(".wcpt-diw-tray");

        if (
            $input.attr("wcpt-model-key").indexOf("color") !== -1 ||
            $input.attr("wcpt-model-key") == "background" ||
            $input.attr("wcpt-model-key") == "fill"
        ) {
            $tray.append('<input type="color">');
            $tray.css({ height: 0 });
            var $color = $tray.find('input[type="color"]');

            $color.spectrum({
                color: $input.val(),
                flat: true,
                allowEmpty: true,
                showAlpha: true,
                preferredFormat: "rgba",
                clickoutFiresChange: true,
                showInput: false,
                showButtons: false,
                move: function (color) {
                    $input.val(color.toRgbString()).change();
                }
            });
        }
    };

    controller.close_dynamic_input_wrapper = function (e) {
        var $origin = $(e.target),
            $wrap = $origin.closest(".wcpt-diw");

        if (!$wrap.length) {
            $(".wcpt-diw").each(function () {
                var $this = $(this),
                    $input = $this.children("input");

                $this.replaceWith($input);
                $("body").off(
                    "blur mousedown keydown",
                    controller.close_dynamic_input_wrapper
                );

                $input.change();
            });
        }
    };

    /* increase/decrease number with arrow keys */
    $("body").on("keydown", "input[wcpt-model-key]", function (e) {
        if (!e.key || -1 === $.inArray(e.key, ["ArrowUp", "ArrowDown"])) {
            return;
        }

        if (
            -1 ===
            $.inArray($(this).attr("wcpt-model-key"), [
                "font-size", // permitted
                "custom_zoom_scale",
                "line-height",
                "letter-spacing",
                "stroke-width",
                "top",
                "left",
                "right",
                "bottom",
                "width",
                "max-width",
                "min-width",
                "height",
                "max-height",
                "min-height",
                "border-radius",
                "border-width",
                "border-left-width",
                "border-right-width",
                "border-top-width",
                "border-bottom-width",
                "divider-border-width",
                "padding",
                "padding-left",
                "padding-right",
                "padding-top",
                "padding-bottom",
                "section-padding",
                "section-padding-left",
                "section-padding-right",
                "section-padding-top",
                "section-padding-bottom",
                "margin",
                "margin-left",
                "margin-right",
                "margin-top",
                "margin-bottom",
                "gap",
                "row_gap"
            ])
        ) {
            return;
        }

        if (!e.target.value) {
            e.target.value = "0px";
        }

        var suffix = e.target.value.slice(-2),
            val = e.target.value;

        if (val.length > 2 && isNaN(suffix)) {
            val = val.substring(0, val.length - 2);
        } else {
            suffix = "";
        }

        var is_float = !((parseInt(val) + "").length === (val + "").length);

        if (e.key == "ArrowUp") {
            e.target.value = (val * 10 + (is_float ? 1 : 10)) / 10;
        } else if (e.key == "ArrowDown") {
            e.target.value = (val * 10 - (is_float ? 1 : 10)) / 10;
        }

        // convert '2' back to float: '2.0'
        val = e.target.value;
        if (is_float) {
            if ((parseInt(val) + "").length === (val + "").length) {
                // float turned to int, let's fix this
                e.target.value = e.target.value + ".0";
            }
        }

        e.target.value += suffix;
    });

    /* attach event handlers */

    // switch editor tabs
    $("body").on("click", ".wcpt-tab-label", controller.switch_editor_tabs);

    // title
    $("body").on("blur", ".wcpt-table-title", controller.update_table_title);

    // toggle sub categories
    $("body").on(
        "click",
        ".wcpt-toggle-sub-categories",
        controller.toggle_sub_categories
    );

    // dynamic input wrapper
    $("body").on(
        "focus",
        'input[type="text"][wcpt-model-key], input[type="number"][wcpt-model-key]',
        controller.open_dynamic_input_wrapper
    );

    // auto select
    $("body").on(
        "click",
        ".wcpt-auto-select-on-click",
        controller.auto_select_on_click
    );

    // data hook up
    dominator_ui.init($(".wcpt-editor, .wcpt-settings"), data);

    // other toggle
    $("body").on("click", ".wcpt-toggle-label", function () {
        var $this = $(this),
            $container = $this.closest(".wcpt-toggle-options"),
            toggle = $container.attr("wcpt-model-key");

        $container.toggleClass("wcpt-open");
        if (toggle && $container.parent().hasClass("wcpt-settings")) {
            window.location.hash = toggle;
        }
    });

    // toggle option rows
    $("body").on("click", ".wcpt-editor-row-handle", function (e) {
        var $target = $(e.target),
            $row = $target.closest(".wcpt-editor-row");
        if (
            !$target.closest(".wcpt-editor-row-no-toggle").length &&
            $target.closest(
                ".wcpt-editor-row-handle-data, .wcpt-editor-row-toggle"
            ).length
        ) {
            $row.toggleClass("wcpt-editor-row-toggle-opened");
        }
    });

    // toggle
    $("body")
        .on("click", ".wcpt-toggle > .wcpt-toggle-trigger", function (e) {
            var $toggle = $(this).closest(".wcpt-toggle");
            $toggle.toggleClass("wcpt-toggle-on wcpt-toggle-off");
            $("body").off("click.wcpt_toggle_blur");
            if ($toggle.hasClass("wcpt-toggle-on")) {
                // blurrable toggle is opened
                // close on blur
                // add this to array
                $("body").on("click.wcpt_toggle_blur", function (e) {
                    if (!$(e.target).closest($toggle).length) {
                        $toggle.children(".wcpt-toggle-trigger").click();
                        $("body").off("click.wcpt_toggle_blur");
                    }
                });
            }
        })
        .on("click", ".wcpt-toggle-x", function (e) {
            $(this)
                .closest(".wcpt-toggle")
                .toggleClass("wcpt-toggle-on wcpt-toggle-off");
        });

    // resume editor tab
    if (window.location.hash) {
        $(
            '[data-wcpt-tab="' +
                window.location.hash.substr(1) +
                '"].wcpt-tab-label'
        ).trigger("click");
        $(
            '.wcpt-settings > [wcpt-model-key="' +
                window.location.hash.substr(1) +
                '"] > .wcpt-toggle-label'
        ).trigger("click");
    }

    // submit
    // -- button click
    $("body").on("submit", ".wcpt-save-data", controller.save_data);
    // -- keyboard: Ctrl/Cmd + s
    $(window).bind("keydown", function (e) {
        if (
            (e.ctrlKey || e.metaKey) &&
            String.fromCharCode(e.which).toLowerCase() === "s"
        ) {
            e.preventDefault();
            $(".wcpt-save-data").submit();
        }
    });
    // -- submit save from text
    // $('body').on('click', '.wcpt-save-keys', function(){
    //   $('.wcpt-save-data').submit();
    // });

    // floating save button
    if ($(".wcpt-editor, .wcpt-settings").length) {
        $(window).on("scroll", wcpt_maybe_floating_save_button);
        $("body").on(
            "click",
            ".wcpt-toggle-options",
            wcpt_maybe_floating_save_button
        );
        wcpt_maybe_floating_save_button();
    }

    function wcpt_maybe_floating_save_button() {
        var $save_clear = $(".wcpt-editor-save-table-clear");
        if (window.scrollY + window.innerHeight < $save_clear.offset().top) {
            $(".wcpt-editor-save-table").addClass(
                "wcpt-editor-save-table--floating"
            );
        } else {
            $(".wcpt-editor-save-table").removeClass(
                "wcpt-editor-save-table--floating"
            );
        }
    }

    // column device tabs

    // -- click tab
    $("body").on(
        "click",
        ".wcpt-editor-tab-columns__device-tabs__triggers__item",
        function (e) {
            var $panel = $(this),
                device = $panel.attr("data-wcpt-device");

            device_tabs__set_state({
                device: device
            });
        }
    );

    // -- scroll top
    var $scroll_to_top = $(
        ".wcpt-editor-tab-columns__device-tabs__scroll-to-top"
    );

    // -- -- display it after 800px scroll
    $(window).on("scroll", function () {
        if (window.scrollY > 800) {
            $scroll_to_top.addClass(
                "wcpt-editor-tab-columns__device-tabs__scroll-to-top--visible"
            );
        } else {
            $scroll_to_top.removeClass(
                "wcpt-editor-tab-columns__device-tabs__scroll-to-top--visible"
            );
        }
    });

    // -- -- scroll up on click
    $scroll_to_top.on("click", function (e) {
        e.preventDefault();

        var $page_title = $(".wcpt-page-title"),
            scroll_top =
                $page_title.offset().top - $("#wpadminbar").outerHeight() - 20;

        $("html, body").stop().animate(
            {
                scrollTop: scroll_top
            },
            300,
            "swing"
        );
    });

    // -- show all columns checkbox option
    $('[name="wcpt-show-all-columns"]').on("change", function () {
        var $this = $(this);
        device_tabs__set_state({
            focus_mode: !$this.is(":checked"),
            device: "laptop"
        });
    });

    // -- column links
    $("body").on(
        "click",
        ".wcpt-editor-tab-columns__device-tabs__panels__item a",
        function (e) {
            e.preventDefault();

            var $this = $(this),
                column_index = $this.attr("data-wcpt-index");

            // + add column button
            if ($this.is('[data-wcpt-index="add"]')) {
                var $tabs = device_tabs__get_container(),
                    tabs_state = device_tabs__get_state($tabs),
                    $device_container =
                        device_tabs__get_device_column_settings_container(
                            tabs_state.device
                        );

                // click editor > columns > device > + add column
                $(">.wcpt-button", $device_container).click();

                // regular column button
            } else {
                device_tabs__set_state({
                    column_index: column_index
                });
            }
        }
    );

    // -- remove focus mode html classes from editor > columns > ...
    function device_tabs__editor_remove_focus_mode_html_classes() {
        $.each(
            [
                // editor > columns tab
                "wcpt-editor-tab-columns--focus-mode",
                // editor > columns tab > device container
                "wcpt-editor-device-columns-container--focused",
                // editor > columns tab > device container > column settings
                "wcpt-column-settings--focused",
                "wcpt-column-settings--reveal-anim",
                // tabs > device panel > column button
                "wcpt-editor-tab-columns__device-tabs__column-link--focused",
                "wcpt-child-column-link-selected"
            ],
            function (index, html_class) {
                $("." + html_class).removeClass(html_class);
            }
        );
    }

    // set the state and view for the tabs
    function device_tabs__set_state(request_state, $tabs) {
        if (!$tabs) {
            $tabs = $(".wcpt-editor-tab-columns__device-tabs");
        }

        var prev_state = device_tabs__get_state($tabs),
            new_state = device_tabs__get_valid_new_state(
                request_state,
                prev_state,
                $tabs
            );

        // render state: manage html classes
        // -- focus_mode state
        device_tabs__render_focus_mode_state(new_state, prev_state, $tabs);

        // -- device state
        device_tabs__render_device_panel_state(new_state, prev_state, $tabs);

        // -- column_index state
        device_tabs__render_column_buttons_state(new_state, prev_state, $tabs);

        // -- scroll
        device_tabs__manage_scroll(request_state, new_state, prev_state);

        // save state
        $tabs.data("wcpt_tab_state", new_state);
    }

    // return valid new state
    function device_tabs__get_valid_new_state(
        request_state,
        prev_state,
        $tabs
    ) {
        // extending prev state ensures all required props are included
        var _new_state = $.extend({}, prev_state, request_state);

        // switch to first column index if device was changed
        _new_state = device_tabs__maybe_switch_to_first_column(
            _new_state,
            prev_state
        );

        // validate column index
        var valid_max_column_index = device_tabs__get_device_column_count(
            _new_state.device,
            $tabs
        );

        if (_new_state.column_index >= valid_max_column_index) {
            _new_state.column_index = null;
        }

        return _new_state;
    }

    window.device_tabs__set_state = device_tabs__set_state;

    // -- init
    var $device_tabs = $(".wcpt-editor-tab-columns__device-tabs");
    if ($device_tabs.length) {
        // -- -- add buttons
        device_tabs__update_all_column_buttons($device_tabs);
        // -- -- set state
        device_tabs__set_state(
            {
                focus_mode: true,
                device: "laptop",
                column_index: 0
            },
            $device_tabs
        );
    }

    // -- get the $tabs container
    function device_tabs__get_container() {
        return $(".wcpt-editor-tab-columns__device-tabs");
    }

    // -- get the current tab state object
    function device_tabs__get_state($tabs) {
        var default_stats = {
            focus_mode: null,
            device: null,
            column_index: null
        };
        return $tabs.data("wcpt_tab_state") || default_stats;
    }

    // -- toggle focus mode html classes
    function device_tabs__render_focus_mode_state(
        new_state,
        previous_state,
        $tabs
    ) {
        // focus mode
        if (new_state.focus_mode) {
            device_tabs__editor_add_focus_mode_html_classes(
                new_state,
                previous_state,
                $tabs
            );
            $(".wcpt-editor-columns-container").sortable("disable");

            // scroll mode
        } else {
            device_tabs__editor_remove_focus_mode_html_classes();
            $(".wcpt-editor-columns-container").sortable("enable");
        }

        // toggle the focus mode checkbox
        device_tabs__toggle_focus_mode_option(new_state, $tabs);
    }

    // -- add focus mode classes outside the tab, in rest of the editor
    function device_tabs__editor_add_focus_mode_html_classes(
        new_state,
        previous_state,
        $tabs
    ) {
        // editor > main columns tab
        var $main_columns_tab = $(".wcpt-editor-tab-columns");
        $main_columns_tab.addClass("wcpt-editor-tab-columns--focus-mode");

        // editor > main columns tab > device containers
        var $selected_device_container =
                device_tabs__get_selected_device_column_settings_container(
                    $tabs
                ),
            $target_device_container =
                device_tabs__get_device_column_settings_container(
                    new_state.device
                ),
            html_class = "wcpt-editor-device-columns-container--focused";

        $selected_device_container.removeClass(html_class);
        $target_device_container.addClass(html_class);

        // editor > main columns tab > device containers > column settings
        var $selected_column_settings =
            device_tabs__get_selected_device_column_settings($tabs);
        ($target_column_settings = device_tabs__get_device_column_settings(
            new_state.column_index,
            new_state.device
        )),
            (html_class = "wcpt-column-settings--focused");

        $selected_column_settings.removeClass(html_class);
        $target_column_settings.addClass(html_class);

        // -- reveal animation
        if (device_tabs__state_change_check(new_state, previous_state)) {
            var animation_html_class = "wcpt-column-settings--reveal-anim";

            $target_column_settings.removeClass(animation_html_class);
            setTimeout(function () {
                $target_column_settings.addClass(animation_html_class);
            }, 1);
        }
    }

    // -- compare new and previous state to check if something change
    function device_tabs__state_change_check(new_state, previous_state) {
        if (
            new_state.device !== previous_state.device ||
            new_state.column_index !== previous_state.column_index ||
            new_state.focus_mode !== previous_state.focus_mode
        ) {
            return true;
        }
    }

    // -- maybe check the focus checkbox
    function device_tabs__toggle_focus_mode_option(state, $tabs) {
        $('input[name="wcpt-show-all-columns"]', $tabs).prop(
            "checked",
            !state.focus_mode
        );
    }

    // -- get this: editor > main columns tab > selected device containers
    function device_tabs__get_selected_device_column_settings_container($tabs) {
        var state = device_tabs__get_state($tabs);
        return device_tabs__get_device_column_settings_container(state.device);
    }

    // -- get this: editor > main columns tab > selected device containers > selected column settings
    function device_tabs__get_selected_device_column_settings($tabs) {
        var state = device_tabs__get_state($tabs);
        return device_tabs__get_device_column_settings(
            state.column_index,
            state.device
        );
    }

    // -- switch $panel html classes to focus on new device
    function device_tabs__render_device_panel_state(
        new_state,
        prev_state,
        $tabs
    ) {
        if (new_state.device !== prev_state.device) {
            // select correct tab and panel
            var $selected_device_panel = device_tabs__get_selected_panel($tabs),
                $target_device_panel = device_tabs__get_panel(
                    new_state.device,
                    $tabs
                ),
                $selected_device_tab = device_tabs__get_selected_tab($tabs),
                $target_device_tab = device_tabs__get_tab(
                    new_state.device,
                    $tabs
                ),
                selected_panel_html_class =
                    "wcpt-editor-tab-columns__device-tabs__panels__item--selected",
                selected_tab_html_class =
                    "wcpt-editor-tab-columns__device-tabs__triggers__item--selected";

            $selected_device_panel.removeClass(selected_panel_html_class);
            $target_device_panel.addClass(selected_panel_html_class);

            $selected_device_tab.removeClass(selected_tab_html_class);
            $target_device_tab.addClass(selected_tab_html_class);

            // button reveal animation
            device_tabs__column_buttons_reveal_animation(
                new_state.device,
                $tabs
            );
        }

        // maybe enable the down arrow on this panel
        device_tabs__maybe_enable_down_arrow(new_state, $tabs);
    }

    // -- manage scroll from set_state
    function device_tabs__manage_scroll(request_state, new_state, prev_state) {
        // focus mode
        if (new_state.focus_mode) {
            // scroll to editor top if mode switched to focus from scroll
            if (!prev_state.focus_mode) {
                device_tabs__instant_scroll_to_editor_top();
            }

            // scroll mode
        } else {
            if (request_state.column_index) {
                device_tabs__scroll_to_column_settings(
                    new_state.column_index,
                    new_state.device
                );
            } else if (request_state.device) {
                device_tabs__scroll_to_device_container(new_state.device);
            }
        }
    }

    // -- scroll to device container
    function device_tabs__scroll_to_device_container(device) {
        var $device_column_container =
                device_tabs__get_device_column_settings_container(device),
            sticky_elms_height = device_tabs__get_sticky_offset(),
            scroll_top =
                $device_column_container.offset().top - sticky_elms_height - 20;

        $("html, body").stop().animate(
            {
                scrollTop: scroll_top
            },
            300,
            "swing"
        );
    }

    function device_tabs__scroll_to_column_settings(column_index, device) {
        var $column_settings = device_tabs__get_device_column_settings(
            column_index,
            device
        );

        var sticky_elms_height = device_tabs__get_sticky_offset(),
            scroll_top =
                $column_settings.offset().top - sticky_elms_height - 20;

        // animating in
        if ($column_settings.is(":hidden")) {
            var $animation_placeholder =
                $column_settings.prev(".wcpt-row-plc-hld");
            scroll_top += $animation_placeholder.offset().top;
        }

        $("html, body").stop().animate(
            {
                scrollTop: scroll_top
            },
            300,
            "swing"
        );
    }

    // -- scroll to editor top
    function device_tabs__instant_scroll_to_editor_top() {
        var sticky_elms_height = $("#wpadminbar").outerHeight(),
            scroll_top =
                $(".wcpt-editor").offset().top - sticky_elms_height - 20;

        window.scrollTo(0, scroll_top);
    }

    // -- column buttons reveal animation
    function device_tabs__column_buttons_reveal_animation(device, $tabs) {
        var defer_anim = 0,
            $column_buttons = device_tabs__get_device_column_buttons(
                device,
                $tabs
            );

        $column_buttons
            .removeClass("wcpt-column-link--reveal-anim")
            .each(function () {
                var $this = $(this);
                setTimeout(function () {
                    $this.addClass("wcpt-column-link--reveal-anim");
                }, defer_anim);
                defer_anim += 20;
            });
    }

    // -- condition panel for down arrow
    function device_tabs__maybe_enable_down_arrow(new_state, $tabs) {
        var $panel = device_tabs__get_panel(new_state.device, $tabs),
            device_column_names = device_tabs__get_device_column_names(
                new_state.device,
                $tabs
            ),
            status = false;

        if (
            new_state.column_index !== null &&
            new_state.column_index < device_column_names.length
        ) {
            status = true;
        }

        $panel.toggleClass("wcpt-child-column-link-selected", status);
    }

    // -- get selected tab trigger button
    function device_tabs__get_selected_tab($tabs) {
        var state = device_tabs__get_state($tabs);

        return device_tabs__get_tab(state.device, $tabs);
    }

    // -- get tab based on device name
    function device_tabs__get_tab(device, $tabs) {
        return $(
            '.wcpt-editor-tab-columns__device-tabs__triggers__item[data-wcpt-device="' +
                device +
                '"]',
            $tabs
        );
    }

    // -- switch to first column index if device switched
    function device_tabs__maybe_switch_to_first_column(new_state, prev_state) {
        if (
            new_state.device !== prev_state.device ||
            new_state.focus_mode !== prev_state.focus_mode
        ) {
            new_state.column_index = 0;
        }

        return new_state;
    }

    // -- switch html classes to focus on selected column index
    function device_tabs__render_column_buttons_state(
        new_state,
        prev_state,
        $tabs
    ) {
        if (
            new_state.column_index == prev_state.column_index &&
            new_state.device == prev_state.device
        ) {
            return;
        }

        var $selected_column_button =
                device_tabs__get_selected_column_button($tabs),
            $target_column_button = device_tabs__get_column_button(
                new_state.column_index,
                new_state.device,
                $tabs
            ),
            focused_html_class =
                "wcpt-editor-tab-columns__device-tabs__column-link--focused";

        $selected_column_button.removeClass(focused_html_class);
        $target_column_button.addClass(focused_html_class);
    }

    // -- get the currently selected column buttons
    function device_tabs__get_selected_column_button($tabs) {
        var state = device_tabs__get_state($tabs);

        return device_tabs__get_column_button(
            state.column_index,
            state.device,
            $tabs
        );
    }

    // -- get sticky offset to accomodate admin bar and device tabs
    function device_tabs__get_sticky_offset() {
        return (
            $("#wpadminbar").outerHeight() +
            $(".wcpt-editor-tab-columns__device-tabs").outerHeight()
        );
    }

    // -- get editor > columns > column device container based on device
    function device_tabs__get_device_column_settings_container(device) {
        return $(
            '.wcpt-editor-columns-container[data-wcpt-device="' + device + '"]'
        );
    }

    // -- get editor > columns > column device container > column settings based on device and colum index
    function device_tabs__get_device_column_settings(column_index, device) {
        if (null === column_index) {
            return $();
        }

        var $device_container =
            device_tabs__get_device_column_settings_container(device);

        return $("> .wcpt-column-settings", $device_container).eq(column_index);
    }

    // -- update $tabs based on changes in editor > columns. Ev triggered by dom_ui controller 'columns'. This is the columns model tick
    $("body").on(
        "update.wcpt",
        ".wcpt-editor-tab-columns__device-tabs",
        function () {
            var $tabs = $(this),
                trigger = device_tabs__get_last_column_trigger_record($tabs);

            if (trigger) {
                // rebuild $tabs > $panels (all) > $column_buttons
                device_tabs__update_all_column_buttons($tabs);

                switch (trigger.action) {
                    // removed a column
                    case "remove":
                        device_tabs__set_state({
                            column_index: null
                        });

                        break;

                    // added a new column
                    case "add":
                        device_tabs__set_state({
                            column_index: trigger.column_index,
                            device: trigger.device
                        });
                        break;

                    // duplicated a column
                    case "duplicate":
                        device_tabs__set_state({
                            column_index: parseFloat(trigger.column_index) + 1,
                            device: trigger.device
                        });

                        break;
                }

                // clear for next tick
                device_tabs__clear_last_column_trigger_record();
            }
        }
    );

    // -- handlers on editor > columns > $column_settings to record trigger. We using this in next columns model tick
    // -- -- name change
    $("body").on("keydown", 'input[type="text"].wcpt-column-name', function () {
        // 'keydown' event helps us catch this update before it passes the tick cycle
        var $row = $(this).closest(".wcpt-column-settings"),
            column_index = $row.attr("wcpt-model-key-index"),
            device = $row
                .closest(".wcpt-editor-columns-container")
                .attr("data-wcpt-device");

        device_tabs__update_last_column_trigger_record({
            action: "false",
            column_index: column_index,
            device: device
        });
    });

    // -- -- remove
    $("body").on(
        "dom_ui_before_remove_row",
        ".wcpt-column-settings",
        function () {
            var $row = $(this);
            (column_index = $row.attr("wcpt-model-key-index")),
                (device = $row
                    .closest(".wcpt-editor-columns-container")
                    .attr("data-wcpt-device"));

            device_tabs__update_last_column_trigger_record({
                action: "remove",
                column_index: column_index,
                device: device
            });
        }
    );
    // -- -- copy
    $("body").on(
        "dom_ui_before_duplicate_row",
        ".wcpt-column-settings",
        function () {
            var $row = $(this);
            (column_index = $row.attr("wcpt-model-key-index")),
                (device = $row
                    .closest(".wcpt-editor-columns-container")
                    .attr("data-wcpt-device"));

            device_tabs__update_last_column_trigger_record({
                action: "duplicate",
                column_index: column_index,
                device: device
            });
        }
    );
    // -- -- add
    $("body").on(
        "dom_ui_before_add_row",
        ".wcpt-editor-columns-container",
        function () {
            var $container = $(this);
            (column_index = $container.data("wcpt-data").length),
                (device = $container.attr("data-wcpt-device"));

            device_tabs__update_last_column_trigger_record({
                action: "add",
                column_index: column_index,
                device: device
            });
        }
    );

    // -- -- sort from editor > columns
    $("body").on(
        "sortupdate dom_ui_before_row_move_up dom_ui_before_row_move_down dom_ui_before_row_sortupdate dom_ui_before_row_sortreceive",
        ".wcpt-editor-columns-container",
        function () {
            // just want to update the column buttons
            device_tabs__update_last_column_trigger_record({
                action: false
            });
        }
    );

    // -- make a record of the last trigger action in editor > $column_settings
    function device_tabs__update_last_column_trigger_record(trigger) {
        var $tabs = device_tabs__get_container();

        $tabs.data("wcpt-tabs-last-column-trigger", trigger);
    }

    // -- get the last recorded trigger in editor > $column_settings
    function device_tabs__get_last_column_trigger_record(trigger) {
        var $tabs = device_tabs__get_container();

        return $tabs.data("wcpt-tabs-last-column-trigger");
    }

    // -- clear the last trigger record for editor > $column_settings
    function device_tabs__clear_last_column_trigger_record() {
        device_tabs__update_last_column_trigger_record(false);
    }

    // -- toggle display of the 'show all columns' buttons
    function device_tabs__toggle_show_all_columns_button($tabs) {
        // -- show all button
        var $show_all = $(
                ".wcpt-editor-tab-columns__device-tabs__show-all-columns",
                $tabs
            ),
            columns_exist = device_tabs__columns_exist($tabs);

        $show_all.toggle(columns_exist);
    }

    // -- check if any columns exist across all devices
    function device_tabs__columns_exist($tabs) {
        var columns = $tabs.data("wcpt-columns"),
            columns_exist = false;

        $.each(columns, function (device, device_columns) {
            if (device_columns.length) {
                columns_exist = true;
            }
        });

        return columns_exist;
    }

    // -- return object with devices and their column names
    function device_tabs__get_column_names($tabs) {
        var columns = $tabs.data("wcpt-columns"),
            column_names = {};

        $.each(["laptop", "tablet", "phone"], function (index, device) {
            column_names[device] = [];
            $.each(columns[device], function (index, column) {
                column_names[device].push(column.name);
            });
        });

        return column_names;
    }

    // -- return array of column names for a device
    function device_tabs__get_device_column_names(device, $tabs) {
        var column_names = device_tabs__get_column_names($tabs);

        return column_names[device];
    }

    // -- return count of column names for a device
    function device_tabs__get_device_column_count(device, $tabs) {
        var column_names = device_tabs__get_column_names($tabs);

        return column_names[device].length;
    }

    // -- return object with previous column names
    function device_tabs__get_previous_column_names($tabs) {
        return $tabs.data("wcpt-previous-column-names");
    }

    // -- update the previous column names
    function device_tabs__update_previous_column_names_record($tabs) {
        var column_names = device_tabs__get_column_names($tabs);
        return $tabs.data("wcpt-previous-column-names", column_names);
    }

    // -- return the currently selected device panel
    function device_tabs__get_selected_panel($tabs) {
        var state = device_tabs__get_state($tabs);

        return device_tabs__get_panel(state.device, $tabs);
    }

    // -- return requested device panel
    function device_tabs__get_panel(device, $tabs) {
        return $tabs.find(
            '.wcpt-editor-tab-columns__device-tabs__panels__item[data-wcpt-device="' +
                device +
                '"]'
        );
    }

    // -- return the currently selected device
    function device_tabs__get_selected_device($tabs) {
        var $selected_device_panel = device_tabs__get_selected_panel($tabs);
        return $selected_device_panel.attr("data-wcpt-device");
    }

    // -- get column button for device
    function device_tabs__get_column_button(column_index, device, $tabs) {
        if (column_index === null) {
            return $();
        }
        var $panel = device_tabs__get_panel(device, $tabs);
        return $panel.find(".wcpt-column-link").eq(column_index);
    }

    // -- get column button for device
    function device_tabs__get_device_column_buttons(device, $tabs) {
        var $panel = device_tabs__get_panel(device, $tabs);
        return $panel.find(".wcpt-column-link");
    }

    // -- refresh the column buttons in this device panel
    function device_tabs__update_all_column_buttons($tabs) {
        $.each(["laptop", "tablet", "phone"], function (index, device) {
            device_tabs__update_device_panel(device, $tabs);
        });

        // allow the 'show all columns' checkbox if columns exist
        device_tabs__toggle_show_all_columns_button($tabs);
    }

    // -- refresh the column buttons in this device panel
    function device_tabs__update_device_panel(device, $tabs) {
        device_tabs__rebuild_panel_column_buttons(device, $tabs);
        device_tabs__make_panel_column_buttons_sortable(device, $tabs);
    }

    // -- update the column buttons in a panel
    function device_tabs__rebuild_panel_column_buttons(device, $tabs) {
        var columns = $tabs.data("wcpt-columns"),
            $panel = device_tabs__get_panel(device, $tabs),
            state = device_tabs__get_state($tabs),
            selected_column_index =
                state.device === device ? state.column_index : -1;

        // clear out the column buttons in this panel
        $panel.empty();

        // rebuild its set of column buttons based on new data
        for (const [index, column] of Object.entries(columns[device])) {
            var name = column.name ? column.name : "Column",
                html_class =
                    "wcpt-editor-tab-columns__device-tabs__column-link wcpt-column-link wcpt-column-link--reveal-anim";

            if (index == selected_column_index) {
                html_class +=
                    " wcpt-editor-tab-columns__device-tabs__column-link--focused";
            }

            $panel.append(
                $(
                    '<a href="#" data-wcpt-index="' +
                        index +
                        '" class="' +
                        html_class +
                        '" ><span class="wcpt-column-link__count">' +
                        (parseInt(index) + 1) +
                        "</span>" +
                        name
                            .replace(/&/g, "&amp;")
                            .replace(/</g, "&lt;")
                            .replace(/>/g, "&gt;") +
                        "</a>"
                )
            );
        }

        // also add the '+ add column' button
        $panel.append(
            $(
                '<a href="#" class="wcpt-column-link--reveal-anim" data-wcpt-index="add">+ Add column</a>'
            )
        );
    }

    // -- make the column buttons sortable
    function device_tabs__make_panel_column_buttons_sortable(device, $tabs) {
        var $panel = device_tabs__get_panel(device, $tabs);

        $panel.sortable({
            containment: "parent",
            items: "> a:not([data-wcpt-index='add'])",
            start: function (e, ui) {
                ui.placeholder.height(ui.item.height());
                ui.placeholder.width(ui.item.width());
            },
            stop: function (event, ui) {
                // reorder the editor > device > column settings boxes based on new column button order
                var $column_buttons = device_tabs__get_device_column_buttons(
                        device,
                        $tabs
                    ),
                    $device_section =
                        device_tabs__get_device_column_settings_container(
                            device
                        ),
                    $device_section__add_new_button = $(
                        "> .wcpt-button",
                        $device_section
                    );

                $column_buttons.each(function () {
                    var index = $(this).attr("data-wcpt-index"),
                        $index_matching_row = $device_section.children(
                            '[wcpt-model-key-index="' + index + '"]'
                        );
                    $index_matching_row.insertBefore(
                        $device_section__add_new_button
                    );
                });

                // maintain selected column button index
                var state = device_tabs__get_state($tabs);
                if (state.focus_mode) {
                    var $selected_button = $panel.children(
                        ".wcpt-editor-tab-columns__device-tabs__column-link--focused"
                    );
                    if ($selected_button.length) {
                        device_tabs__set_state({
                            column_index: $selected_button.index()
                        });
                    }
                }

                $device_section.trigger("sortupdate");
            }
        });
    }

    function device_tabs__focus_mode_is_on($tabs) {
        var state = device_tabs__get_state($tabs);
        return state.focus_mode;
    }

    function device_tabs__device_already_selected(device, $tabs) {
        var $device_panel = device_tabs__get_panel(device, $tabs),
            $selected_panel = device_tabs__get_selected_panel($tabs);

        return $device_panel.is($selected_panel);
    }

    // // column name input toggle
    // //-- open
    // $('body').on('click', '.wcpt-column-index', function(){
    //   var $this = $(this);
    //   $this
    //     .addClass('wcpt-column-index--input-on')
    //     .find('input').focus()
    //     .end().find('.wcpt-diw input').focus(); // diw workaround
    // })
    // //-- close
    // $('body').on('click', '.wcpt-close-column-name-input', function(e){
    //   var $this = $(this),
    //       $input = $this.prev('input');

    //   $input.val('').change();
    //   e.stopPropagation();
    // })

    // columns toggle
    //-- central buttons
    // $('body').on('click', '.wcpt-device-columns-toggle', function(e){
    //   var $this = $(this),
    //       $device_columns = $this.closest('.wcpt-editor-columns-container'),
    //       $columns = $device_columns.find('.wcpt-column-settings'),
    //       $heading = $('> .wcpt-editor-light-heading', $device_columns),
    //       offset = 50;

    //   if( $(e.target).closest('.wcpt-device-columns-toggle__expand').length ){
    //     $columns.addClass('wcpt-toggle-column-expand');

    //   }else if( $(e.target).closest('.wcpt-device-columns-toggle__contract').length ){
    //     $columns.removeClass('wcpt-toggle-column-expand');

    //   }

    //   $([document.documentElement, document.body]).animate({
    //     scrollTop: $device_columns.offset().top - offset
    //   }, 300, 'linear');

    //   e.preventDefault();
    // })
    //-- column buttons
    $("body").on(
        "click",
        ".wcpt-editor-row-expand, .wcpt-editor-row-contract",
        function (e) {
            var $this = $(this),
                $column = $this.closest(".wcpt-column-settings");

            if ($this.hasClass("wcpt-editor-row-expand")) {
                $column.addClass("wcpt-toggle-column-expand");
            } else {
                $column.removeClass("wcpt-toggle-column-expand");
            }

            e.preventDefault();
        }
    );
    //-- column body
    $("body").on("click", ".wcpt-column-toggle-capture", function (e) {
        return;
        var $column = $(this).closest(".wcpt-column-settings");
        $column.toggleClass("wcpt-toggle-column-expand");
    });

    // select2 init
    $(".wcpt-select-icon").each(function () {
        var $this = $(this);
        $this.select2({
            templateResult: function (icon) {
                var img =
                        '<img class="wcpt-icon-rep" src="' +
                        wcpt_icons_url +
                        "/" +
                        icon.id +
                        '.svg">',
                    $icon = $(
                        "<span>" +
                            img +
                            '<span class="wcpt-icon-name">' +
                            icon.text +
                            "</span>" +
                            "</span>"
                    );
                return $icon;
            },
            templateSelection: function (icon) {
                var img =
                        '<img class="wcpt-icon-rep" src="' +
                        wcpt_icons_url +
                        "/" +
                        icon.id +
                        '.svg">',
                    $icon = $(
                        "<span>" +
                            img +
                            '<span class="wcpt-icon-name">' +
                            icon.text +
                            "</span>" +
                            "</span>"
                    );
                return $icon;
            },
            dropdownParent: $this.parent()
        });
    });

    // dev's little helper
    window.wcpt_duplicate_laptop_to_phone = function () {
        wcpt_duplicate_device("laptop", "phone");
    };

    window.wcpt_duplicate_laptop_to_tablet = function () {
        wcpt_duplicate_device("laptop", "tablet");
    };

    window.wcpt_duplicate_device = function (source, destination) {
        if (!source) {
            source = "laptop";
        }

        if (!destination) {
            destination = "phone";
        }

        $.each(data.columns[source], function (index, col) {
            data.columns[destination].push(
                dominator_ui.refresh_ids($.extend(true, {}, col))
            );
        });

        $(".wcpt-editor-save-button").click();

        window.location.reload();
    };

    // settings

    //-- reset settings

    $("body").on("click", ".wcpt-reset-global-settings", function (e) {
        if (
            window.confirm(
                "Are you sure you want to reset WCPT global settings? This will not delete your tables. It will only reset the global settings for this plugin."
            )
        ) {
            return;
        }
        e.preventDefault();
    });

    //-- license activation
    $("body").on(
        "click",
        ".wcpt-activate-license, .wcpt-deactivate-license",
        function () {
            var $this = $(this),
                $buttons = $this.siblings().addBack(),
                action = "wcpt_manage_license",
                purpose = $this.attr("data-wcpt-purpose"),
                nonce = $this.attr("data-wcpt-nonce"),
                $container = $this.closest(".wcpt-license-container"),
                $status = $container.find(
                    ">.wcpt-license-key-status",
                    $container
                ),
                license_key = $container.find('[wcpt-model-key="key"]').val(),
                addon_slug = $container.attr("wcpt-addon-slug")
                    ? $container.attr("wcpt-addon-slug")
                    : "",
                addon_item_id = $container.attr("wcpt-addon-item-id")
                    ? $container.attr("wcpt-addon-item-id")
                    : "",
                $feedback = $container.find(".wcpt-license-feedback");

            if (!license_key || license_key.length != 32) {
                alert(
                    "Please enter the valid 32 character license key received in your purchase email."
                );
                return;
            }

            $.ajax({
                type: "POST",
                url: ajaxurl,

                beforeSend: function () {
                    $(">span", $feedback).addClass("wcpt-hide");
                    $container.addClass("wcpt-verifying-license");
                    $buttons.prop("disabled", true);
                },

                data: {
                    action: action,
                    wcpt_nonce: nonce,
                    wcpt_purpose: purpose,
                    wcpt_key: license_key,
                    wcpt_addon_slug: addon_slug,
                    wcpt_addon_item_id: addon_item_id
                },

                success: function (data) {
                    $container.removeClass("wcpt-verifying-license");
                    $buttons.prop("disabled", false);

                    switch (data) {
                        case "deactivated":
                            $feedback
                                .find(".wcpt-response-deactivated")
                                .removeClass("wcpt-hide");
                            $buttons
                                .filter('[data-wcpt-purpose="deactivate"]')
                                .prop("disabled", true);
                            break;

                        case "activated":
                            $feedback
                                .find(".wcpt-response-activated")
                                .removeClass("wcpt-hide");
                            $buttons
                                .filter('[data-wcpt-purpose="activate"]')
                                .prop("disabled", true);
                            break;

                        case "active_elsewhere":
                            $feedback
                                .find(".wcpt-response-active-elsewhere")
                                .removeClass("wcpt-hide");
                            break;

                        case "invalid_key":
                            $feedback
                                .find(".wcpt-response-invalid-key")
                                .removeClass("wcpt-hide");
                            break;

                        default: // invalid response
                            $feedback
                                .find(".wcpt-response-invalid-response")
                                .removeClass("wcpt-hide");
                    }

                    if (data == "activated") {
                        $status.val("active");
                    } else {
                        $status.val("inactive");
                    }

                    $status.trigger("change");
                }
            });
        }
    );

    //-- pre-open license activation
    if (
        window.location.hash &&
        window.location.hash.substr(1) == "pro_license"
    ) {
        $('[wcpt-model-key="pro_license"]').addClass("wcpt-open");
    }

    // WCPT Lite - PRO feature arrangement

    // shortcode otions
    var $shortcode_ops = $(".wcpt-shortcode-info"),
        $pro_op_row = $("tr", $shortcode_ops).filter(function () {
            var $this = $(this);
            return $this.find(".wcpt-pro-badge").length;
        }),
        $pro_msg_row = $(
            '<tr><td colspan="2">Following are all <span class="wcpt-pro-badge">PRO</span> version options:</td></tr>'
        );

    if ($pro_op_row.length) {
        $("td", $pro_msg_row).css({
            "font-size": "22px",
            "font-weight": "bold",
            "text-transform": "capitalize",
            padding: "40px 10px"
        });

        $.merge($pro_msg_row, $pro_op_row).appendTo("tbody", $shortcode_ops);
    }

    // query v2 onChange
    $(document).on("wcptReactQueryAppUpdate", function (e) {
        wcpt.data.query_v2 = e.detail;
    });

    // ready
    $(window).trigger("wcpt_controller_ready");
});
