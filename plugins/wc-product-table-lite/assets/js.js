jQuery(function ($) {
    var cart_button_selector =
        ".wcpt-button-cart_ajax, .wcpt-button-cart_redirect, .wcpt-button-cart_refresh, .wcpt-button-cart_checkout";

    // local cache
    window.wcpt_cache = {
        data: {},
        remove: function (url) {
            delete window.wcpt_cache.data[url];
        },
        exist: function (url) {
            return (
                window.wcpt_cache.data.hasOwnProperty(url) &&
                window.wcpt_cache.data[url] !== null
            );
        },
        get: function (url) {
            return window.wcpt_cache.data[url];
        },
        set: function (url, cachedData, callback) {
            window.wcpt_cache.remove(url);
            window.wcpt_cache.data[url] = cachedData;
            if ($.isFunction(callback)) callback(cachedData);
        }
    };

    window.wcpt_current_device = get_device();

    $(window).on("resize", function () {
        window.wcpt_cache.data = {};

        window.wcpt_previous_device = window.wcpt_current_device;
        window.wcpt_current_device = get_device();
        if (window.wcpt_previous_device !== window.wcpt_current_device) {
            $(window).trigger("wcpt_device_change", {
                previous_device: window.wcpt_previous_device,
                current_device: window.wcpt_current_device
            });
        }
    });

    window.wcpt_product_form = {}; // product form cache

    function get_device() {
        // device
        var device = "laptop"; // default

        if ($(window).width() <= wcpt_params.breakpoints.phone) {
            device = "phone";
        } else if ($(window).width() <= wcpt_params.breakpoints.tablet) {
            device = "tablet";
        }

        return device;
    }

    // html entity encode
    function htmlentity(string) {
        return string.replace(/[\u00A0-\u9999<>\&]/gim, function (i) {
            return "&#" + i.charCodeAt(0) + ";";
        });
    }

    // maintain scroll left upon column header sorting
    $("body").on("click", ".frzTbl .wcpt-heading.wcpt-sortable", function () {
        var $this = $(this),
            $container = $this.closest(".wcpt"),
            $scrollOverlay = $this
                .closest(".frzTbl-content-wrapper")
                .siblings(".frzTbl-scroll-overlay"),
            scrollLeft = $scrollOverlay[0].scrollLeft;

        $("body").one(
            "after_freeze_table_build",
            "#" + $container.attr("id") + " .frzTbl-table",
            function (e, frzTbl) {
                frzTbl.el.$scrollOverlay[0].scrollLeft = scrollLeft;
            }
        );
    });

    // layout handler
    $("body").on("wcpt_layout", ".wcpt", function layout(e, data) {
        var $wcpt = $(this),
            $wrap = $wcpt.find(".wcpt-table-scroll-wrapper:visible"),
            id = $wcpt.attr("data-wcpt-table-id"),
            sc_attrs = wcpt_util.get_sc_attrs($wcpt);

        if ($(">.wcpt-device-view-loading-icon", $wrap).length) {
            var url = window.location.href,
                hash = window.location.hash,
                query_exists = url.indexOf("?") !== -1,
                query = "",
                device = get_device();

            if (hash) {
                url = url.replace(hash, "");
            }

            if (query_exists) {
                var replace = "&*" + id + "_device=(laptop|phone|tablet)";
                re = new RegExp(replace, "gm");

                url = (
                    url.replace(re, "") +
                    "&" +
                    id +
                    "_device=" +
                    device
                ).replace("?&", "?");
            } else {
                url = url + "?" + id + "_device=" + device;
            }

            if (hash) {
                url = url + hash;
            }

            // archive oveerride + compatible nav filter plugin = requires page refresh
            if ($wcpt.attr("data-wcpt-sc-attrs").indexOf("_only_loop") !== -1) {
                window.location = url;
                return;
            }

            query = url.substr(url.indexOf("?") + 1);

            if (hash) {
                query = query.replace(hash, "");
            }

            attempt_ajax($wcpt, query, false, "device_view");

            return; // layout on AJAX response
        }

        // freeze tables
        if (!wcpt_is_module_disabled("freeze-table", $wcpt)) {
            if (!wcpt_util.get_freeze_table($wcpt)) {
                var options = { breakpoint: {} },
                    $table = $(".wcpt-table", $wcpt);

                ["laptop", "tablet", "phone"].forEach(device => {
                    var freeze_left_columns = sc_attrs[`${device}_freeze_left`],
                        freeze_right_columns =
                            sc_attrs[`${device}_freeze_right`],
                        freeze_heading = sc_attrs[`${device}_freeze_heading`],
                        freeze_heading_offset =
                            sc_attrs[`${device}_scroll_offset`],
                        grab_and_scroll = sc_attrs.grab_and_scroll,
                        table_width = sc_attrs[`${device}_table_width`],
                        breakpoint_settings = {
                            // columns
                            left: freeze_left_columns
                                ? parseInt(freeze_left_columns)
                                : 0,
                            right: freeze_right_columns
                                ? parseInt(freeze_right_columns)
                                : 0,

                            // heading
                            // -- toggle
                            heading: !!(
                                freeze_heading && freeze_heading !== "false"
                            ),
                            // -- offset
                            offset: freeze_heading_offset
                                ? freeze_heading_offset
                                : 0,

                            // grab and scroll
                            grab_and_scroll: !!grab_and_scroll,

                            // table width
                            tableWidth: !table_width ? false : table_width
                        };

                    if (device == "laptop") {
                        $.extend(options, breakpoint_settings);
                    } else {
                        options.breakpoint[wcpt_params.breakpoints[device]] =
                            breakpoint_settings;
                    }
                });

                $table.freezeTable(options, data.source);
            }
        }

        // convert sidebar to header
        var device = get_device(),
            $sidebar = $(
                ".wcpt-left-sidebar, .wcpt-was-left-sidebar",
                $wcpt
            ).not(".wcpt-nav-modal .wcpt-navigation");

        if (device == "laptop" && $sidebar.hasClass("wcpt-was-left-sidebar")) {
            $sidebar
                .removeClass("wcpt-header wcpt-was-left-sidebar")
                .addClass("wcpt-left-sidebar");
        } else if (
            device != "laptop" &&
            !$sidebar.hasClass("wcpt-was-left-sidebar")
        ) {
            // tablet / phone
            $sidebar
                .removeClass("wcpt-left-sidebar")
                .addClass("wcpt-header wcpt-was-left-sidebar");
        }

        // close open dropdowns
        $(".wcpt-was-left-sidebar .wcpt-dropdown", $wcpt).removeClass(
            "wcpt-open"
        );

        // checkboxes
        var $table = wcpt_get_container_original_table($wcpt);

        if (
            $table.data("wcpt_checked_rows") &&
            $table.data("wcpt_checked_rows").length
        ) {
            var $rows = $(".wcpt-row", $table);

            $rows.each(function () {
                var $this = $(this),
                    state = !!$this.data("wcpt_checked");

                $this.trigger("_wcpt_checkbox_change", state);
            });
        }
    });

    // resize
    var resize_timer,
        throttle = 250,
        window_width;

    // throttled window resize event listener
    $(window).on("resize", window_resize);

    function window_resize(e) {
        clearTimeout(resize_timer);
        var new_window_width = window.innerWidth;
        if (new_window_width != window_width) {
            window_width = new_window_width;
            resize_timer = setTimeout(function () {
                trigger_layout("resize");
                recent_orientationchange = false;
            }, throttle);
        }
    }

    // orientation change event listener
    var recent_orientationchange = false;
    $(window).on("orientationchange", function (e) {
        recent_orientationchange = true;
        // trigger_layout('orientationchange');
    });

    function trigger_layout(source) {
        $(".wcpt").trigger("wcpt_layout", { source: source });
    }

    // every load - ajax or page load, needs this
    function after_every_load($container) {
        // if( $container.find('.wcpt').length ){ // inner tables, depricated
        //   $container.find('.wcpt').each(function(){
        //     var $this = $(this);
        //     after_every_load($this);
        //   })
        // }

        // cache new rows
        var $new_rows = wcpt_util.get_uninit_rows($container, true);

        // cache sc attrs
        var sc_attrs = wcpt_util.get_sc_attrs($container, true);

        // sortable column headings
        // -- enable handler
        wcpt_util.do_once_on_container(
            $container,
            "wcpt-sortable-headings-init",
            function ($container) {
                $container.on(
                    "click.wcpt_sort_by_column_headings",
                    ".wcpt-heading.wcpt-sortable",
                    window.wcpt_column_heading_sort_handler
                );
            }
        );
        // -- add wcpt-sortable class
        $(".wcpt-heading", $container).each(function () {
            var $this = $(this);
            if ($this.find(".wcpt-sorting-icon").length) {
                $this.addClass("wcpt-sortable");
            }
        });

        // init cart form
        $(".cart", $new_rows).each(function () {
            var $this__form = $(this);

            // init wc variation form
            if ($this__form.hasClass("variations_form")) {
                $this__form.wc_variation_form();
            }

            // init product addons
            if (typeof WC_PAO === "object") {
                new WC_PAO.Form($this__form);
            } else {
                if ($.fn.init_addon_totals) {
                    $this__form.init_addon_totals();
                }
                if (typeof wcPaoInitAddonTotals === "object") {
                    wcPaoInitAddonTotals.init($this__form);
                }
            }

            // cart: action="*same page*"
            $this__form.attr("action", window.location.href);
        });

        // cart: wc measurement price calculator init
        if (typeof wcpt_wc_mc_init_cart !== "undefined") {
            $(".cart", $new_rows).each(wcpt_wc_mc_init_cart);

            $(".wc-measurement-price-calculator-input-help", $new_rows).tipTip({
                attribute: "title",
                defaultPosition: "left"
            });
        }

        // ultimate social media icons
        if (typeof wcpt_sfsi_init !== "undefined") {
            wcpt_sfsi_init();
        }

        // trigger variation
        prep_variation_options($new_rows);

        // cb select all: duplicate to footer
        wcpt_util.do_once_on_container(
            $container,
            "wcpt-select-all-init",
            duplicate_select_all
        );

        // dynamic filters lazy load
        wcpt_util.do_once_on_container(
            $container,
            "wcpt-dynamic-filters-lazy-load-init",
            dynamic_filters_lazy_load
        );

        // checkbox
        // -- add heading cb
        var $tables = wcpt_get_container_tables($container);

        $tables.each(function () {
            var $table = $(this),
                $heading_row = wcpt_get_table_element(
                    ".wcpt-heading-row",
                    $table
                ).last(),
                $cb = wcpt_get_table_element(
                    ".wcpt-cart-checkbox[data-wcpt-heading-enabled]",
                    $table
                ),
                col_index = [];

            $cb.each(function () {
                var $this = $(this),
                    _col_index = $this.closest(".wcpt-cell").index();

                if (-1 == col_index.indexOf(_col_index)) {
                    col_index.push(_col_index);
                }
            });

            $.each(col_index, function (key, index) {
                var $heading = $("th", $heading_row).eq(index);
                $heading_row.removeClass("wcpt-hide"); // in case it was disabled: had no elements
                if (!$(".wcpt-cart-checkbox-heading", $heading).length) {
                    $heading.prepend(
                        '<input type="checkbox" class="wcpt-cart-checkbox-heading" />'
                    );
                }
            });
        });

        // -- cb trigger
        // wcpt_checkbox_trigger_init();

        // background color
        if (sc_attrs.checked_row_background_color) {
            $("style", $container)
                .first()
                .append(
                    "#" +
                        $container.attr("id") +
                        " .wcpt-row--checked, #" +
                        $container.attr("id") +
                        " .wcpt-row--checked + .wcpt-child-row   {background: " +
                        sc_attrs.checked_row_background_color +
                        "! important;}"
                );
        }

        // multirange
        $(".wcpt-range-slider", $container).each(function () {
            wcpt__multirange(this);
        });

        // reset element: enable / didable it
        wcpt_util.do_once_on_container(
            $container,
            "wcpt-reset-permission-init",
            $container => {
                var query_string = $container.attr("data-wcpt-query-string")
                        ? $container.attr("data-wcpt-query-string")
                        : "",
                    parsed = wcpt_util.parse_query_string(
                        query_string.substring(1)
                    ),
                    table_id = wcpt_util.get_table_id($container),
                    permit_reset = false,
                    $reset = $(".wcpt-reset", $container);

                // check if table was filtered
                if ($reset.length) {
                    $.each(parsed, function (key, val) {
                        if (
                            -1 ==
                            $.inArray(key, [
                                table_id + "_device",
                                table_id + "_filtered"
                            ])
                        ) {
                            permit_reset = true;
                        }
                    });

                    if (permit_reset) {
                        $reset.removeClass("wcpt-disabled");
                    } else {
                        $reset.addClass("wcpt-disabled");
                    }
                }
            }
        );

        // wpc smart compare
        if (
            typeof wooscpGetCookie == "function" &&
            typeof wooscpVars == "object"
        ) {
            var compare_items__string = wooscpGetCookie(
                "wooscp_products_" + wooscpVars.user_id
            );
            if (compare_items__string) {
                var compare_items = compare_items__string.split(",");
                compare_items.forEach(function (item) {
                    $(".wooscp-btn-" + item, $new_rows).each(function () {
                        var $this = $(this);
                        $this.addClass("wooscp-btn-added");
                        $this.text(wooscpVars.button_text_added);
                    });
                });
            }
        }

        // @TODO: optimize for infinite scroll

        // #start

        // -- nav filter feedback
        nav_filter_feedback($container.find(".wcpt-navigation"));

        // -- sonaar integration
        sonaar_player_auto_status();

        // -- hide empty columns
        hide_empty_columns($container);

        // -- initialize wp media player
        if (window.wp && window.wp.mediaelement) {
            window.wp.mediaelement.initialize();
        }

        // #end

        // url update
        wcpt_util.update_url_by_container($container);

        // trigger after load event
        $container.trigger("wcpt_after_every_load").trigger("wcpt_after_ajax");

        // register init rows
        $new_rows.addClass("wcpt-row--init");

        // trigger cart and its view
        if (window.wcpt_cart_result_cache) {
            wcpt_cart({
                payload: {
                    use_cache: true
                }
            });
        }

        // layout
        $container.trigger("wcpt_layout", { source: "after_every_load" });
    }

    function wcpt_get_container_tables($container) {
        return wcpt_get_shell_element(
            ".wcpt-table:not(.frzTbl-clone-table)",
            ".wcpt",
            $container
        );
    }

    function wcpt_get_container_element(element_selector, $container) {
        return wcpt_get_shell_element(element_selector, ".wcpt", $container);
    }

    function wcpt_get_table_element(element_selector, $table) {
        return wcpt_get_shell_element(
            element_selector,
            ".wcpt-table:not(.frzTbl-clone-table)",
            $table
        );
    }

    function wcpt_get_shell_element(element_selector, shell_selector, $shell) {
        return $(element_selector, $shell).filter(function () {
            var $this = $(this);
            return $this.closest(shell_selector).is($shell);
        });
    }

    // hide empty columns
    function hide_empty_columns($container) {
        var sc_attrs = get_sc_attrs($container);

        if (!sc_attrs.hide_empty_columns) {
            return;
        }

        var $table = wcpt_get_container_original_table($container);

        $(".wcpt-cell", $table).removeClass("wcpt-hide");

        $table.each(function () {
            var column_count = $table.find(".wcpt-row").eq(0).children().length;

            while (column_count) {
                // check if all the cells in this column are empty
                var $column_cells = $table.find(
                    ".wcpt-cell:nth-child(" + column_count + ")"
                );

                if (
                    $column_cells.filter(":empty").length ==
                    $column_cells.length
                ) {
                    $column_cells
                        .add(
                            $table.find(
                                ".wcpt-heading:nth-child(" + column_count + ")"
                            )
                        )
                        .addClass("wcpt-hide wcpt-x");
                }

                --column_count;
            }
        });
    }

    // lazy load
    function lazy_load_start() {
        if (!window.wcpt_lazy_loaded) {
            $(".wcpt-lazy-load").each(function () {
                var $this = $(this);
                $this
                    .addClass("wcpt")
                    .removeClass("wcpt-lazy-load")
                    .attr("id", "wcpt-" + $this.attr("data-wcpt-table-id"));
                window.wcpt_attempt_ajax($this, false, false, "lazy_load");
            });
            window.wcpt_lazy_loaded = true;
        }
    }

    // get rows including freeze
    function get_product_rows($elm) {
        var $row = $elm.closest(".wcpt-row"),
            product_id = $row.attr("data-wcpt-product-id"),
            variation_id = $row.attr("data-wcpt-variation-id"),
            $scroll_wrapper = $elm.closest(".wcpt-table-scroll-wrapper"),
            row_selector;

        if (variation_id) {
            row_selector =
                '[data-wcpt-variation-id="' +
                variation_id +
                '"].wcpt-row.wcpt-product-type-variation';
        } else {
            row_selector =
                '[data-wcpt-product-id="' +
                product_id +
                '"].wcpt-row:not(.wcpt-product-type-variation)';
        }

        return $(row_selector, $scroll_wrapper);
    }

    // button click listener
    $("body").on("click", ".wcpt-button", button_click);

    function button_click(e) {
        var $button = $(this),
            link_code = $button.attr("data-wcpt-link-code"),
            $product_rows = get_product_rows($button),
            product_id = $product_rows.attr("data-wcpt-product-id"),
            is_variable = $product_rows.hasClass("wcpt-product-type-variable"),
            complete_match = $product_rows.data("wcpt_complete_match"),
            is_variation = $product_rows.hasClass(
                "wcpt-product-type-variation"
            ),
            is_composite = $product_rows.hasClass(
                "wcpt-product-type-composite"
            ),
            is_bundle = $product_rows.hasClass("wcpt-product-type-woosb"),
            has_addons = $product_rows.hasClass("wcpt-product-has-addons"),
            has_measurement = $product_rows.hasClass(
                "wcpt-product-has-measurement"
            ),
            has_nyp = $product_rows.hasClass(
                "wcpt-product-has-name-your-price"
            ),
            qty = "",
            params = {
                payload: {
                    products: {},
                    variations: {},
                    attributes: {},
                    addons: {},
                    measurement: {},
                    nyp: {} // name your price
                }
            };

        if ($("body").hasClass("wcpt-photoswipe-visible")) {
            e.preventDefault();
            return;
        }

        if (
            -1 !==
            $.inArray(link_code, [
                "product_link",
                "external_link",
                "custom_field",
                "custom_field_media_id",
                "custom_field_acf",
                "custom"
            ])
        ) {
            return;
        }

        e.preventDefault();

        // validation

        // -- variable product
        if (is_variable) {
            var variation_found = $product_rows.data("wcpt_variation_found"),
                variation_selected = $product_rows.data(
                    "wcpt_variation_selected"
                ),
                variation_available = $product_rows.data(
                    "wcpt_variation_available"
                );
            variation_ops = $product_rows.data("wcpt_variation_ops");

            // if row has variation selection options but customer did not make selections, show error
            if (variation_ops) {
                if (!variation_selected) {
                    alert(wcpt_i18n.i18n_make_a_selection_text);
                    return;
                }

                if (!variation_found) {
                    alert(wcpt_i18n.i18n_no_matching_variations_text);
                    return;
                }

                if (!variation_available) {
                    alert(wcpt_i18n.i18n_unavailable_text);
                    return;
                }
            }
        }

        // -- disabled
        if (!is_variable && $button.hasClass("wcpt-disabled")) {
            return;
        }

        // -- name your price
        if (has_nyp) {
            var $nyp = get_nyp_input_element($product_rows);
            if ($nyp.length) {
                var error = false,
                    name = $nyp.attr("data-wcpt-product-name"),
                    min = $nyp.attr("min"),
                    max = $nyp.attr("max");

                if (!$nyp.val()) {
                    error = wcpt_nyp_error_message_templates["empty"];
                } else if (min && $nyp.val() < parseFloat(min)) {
                    error = wcpt_nyp_error_message_templates[
                        "minimum_js"
                    ].replace(
                        "%%MINIMUM%%",
                        woocommerce_nyp_format_price(
                            min,
                            woocommerce_nyp_params.currency_format_symbol,
                            true
                        )
                    );
                } else if (max && $nyp.val() > parseFloat(max)) {
                    error = wcpt_nyp_error_message_templates[
                        "maximum_js"
                    ].replace(
                        "%%MAXIMUM%%",
                        woocommerce_nyp_format_price(
                            max,
                            woocommerce_nyp_params.currency_format_symbol,
                            true
                        )
                    );
                }

                if (error) {
                    alert(error);
                    return;
                }
            }
        }

        // prepare params

        // -- quantity
        var $wcpt_qty = $(
                ".wcpt-quantity input.qty, .wcpt-quantity > select.wcpt-qty-select",
                $product_rows
            ),
            $wc_qty = $(".cart .qty", $product_rows);

        if ($wc_qty.length) {
            // from WooCommerce form's qty field
            qty = $wc_qty.val();
        }

        if ($wcpt_qty.length) {
            // from WCPT's own qty element
            var val = parseFloat($wcpt_qty.val());
            if (isNaN(val) || !parseFloat($wcpt_qty.val())) {
                $wcpt_qty
                    .filter("input")
                    .first()
                    .each(function () {
                        var $this = $(this),
                            min = $this.attr("data-wcpt-min");

                        $this.val(min);
                        limit_qty_controller($this.parent("wcpt-quantity"));
                        val = $this.val();
                    });
            }

            qty = val;
        }

        params.payload.products[product_id] = qty;

        // -- addons
        if (has_addons) {
            var addons = wcpt_get_addons($product_rows);
            if (!$.isEmptyObject(addons)) {
                params.payload.addons[product_id] = addons;
            }
        }

        // -- measurement
        if (has_measurement) {
            var measurement = get_measurement($product_rows);
            if (!$.isEmptyObject(measurement)) {
                params.payload.measurement[product_id] = measurement;
            }
        }

        // -- name your price
        if (has_nyp) {
            var nyp = get_nyp($product_rows);
            if (nyp) {
                params.payload.nyp[product_id] = nyp;
            }
        }

        // -- variation
        if (is_variation) {
            var variation_id = $product_rows.attr("data-wcpt-variation-id"),
                variation_attributes = JSON.parse(
                    $product_rows.attr("data-wcpt-variation-attributes")
                ),
                $missing_attribute_select = $(
                    ".wcpt-select-variation-attribute-term",
                    $product_rows
                );

            if ($missing_attribute_select.length) {
                $missing_attribute_select.each(function () {
                    var $this = $(this),
                        attribute = $this.attr("data-wcpt-attribute"),
                        term = $this.val();

                    if (term) {
                        variation_attributes[attribute] = term;
                    }
                });
            }

            if (typeof params.payload.variations[product_id] === "undefined") {
                params.payload.variations[product_id] = {};
            }
            params.payload.variations[product_id][variation_id] = qty;
            params.payload.attributes[variation_id] = variation_attributes;
        } else if ($product_rows.hasClass("wcpt-product-type-variable")) {
            var variation_id = $product_rows.data("wcpt_variation_id"),
                variation_attributes = $product_rows.data("wcpt_attributes");

            if (variation_id) {
                if (
                    typeof params.payload.variations[product_id] === "undefined"
                ) {
                    params.payload.variations[product_id] = {};
                }
                params.payload.variations[product_id][variation_id] = qty;
            }

            if (variation_attributes) {
                params.payload.attributes[variation_id] = variation_attributes;
            }
        }

        // prepare 'ajax_data' (required for non-AJAX req, submit over POST)
        var ajax_data = {
            action: "wcpt_add_to_cart",
            "add-to-cart": $product_rows.attr("data-wcpt-product-id"),
            product_id: product_id,
            quantity: qty
        };

        // -- addons
        if (has_addons) {
            if (!$.isEmptyObject(addons)) {
                $.extend(ajax_data, addons);
            }
        }

        // -- measurement (submit via post)
        if (has_measurement) {
            var measurement = get_measurement($product_rows);
            if (!$.isEmptyObject(measurement)) {
                $.extend(ajax_data, measurement);
            }
        }

        // -- name your price (submit via post)
        if (has_nyp) {
            var nyp = get_nyp($product_rows);
            if (nyp) {
                ajax_data.nyp = nyp;
            }
        }

        // -- variation (submit via post)
        if (is_variable || is_variation) {
            if (variation_id) {
                ajax_data.variation_id = variation_id;
            }

            if (variation_attributes) {
                $.extend(ajax_data, variation_attributes);
            }
        }

        // receive notices from server?
        ajax_data.return_notice = link_code == "cart_ajax";

        // modal required
        if (
            is_composite ||
            is_bundle ||
            (is_variable && !complete_match) ||
            (is_variation && is_incomplete_variation(variation_attributes)) ||
            (has_addons && !params.payload.addons[product_id]) ||
            (has_measurement && !params.payload.measurement[product_id]) ||
            (has_nyp && !params.payload.nyp[product_id])
        ) {
            // deploy modal immediately if it's in cache
            if (typeof window.wcpt_product_form[product_id] !== "undefined") {
                deploy_product_form_modal(
                    window.wcpt_product_form[product_id],
                    $button,
                    ajax_data
                );

                // else fetch modal from server and deploy
            } else {
                ajax_data.action = "wcpt_get_product_form_modal";
                ajax_data.lang = wcpt_i18n.lang;
                delete ajax_data["add-to-cart"];

                $.ajax({
                    url: wcpt_params.wc_ajax_url.replace(
                        "%%endpoint%%",
                        "wcpt_get_product_form_modal"
                    ),
                    method: "POST",
                    beforeSend: function () {
                        window.wcpt_modal__last_requested_product_id =
                            product_id;
                        deploy_loading_modal();
                    },
                    data: ajax_data
                }).done(function (response) {
                    window.wcpt_product_form[product_id] = response;

                    if (
                        product_id ===
                        window.wcpt_modal__last_requested_product_id
                    ) {
                        // skip if req. superseded
                        $(".wcpt-product-form-loading-modal").trigger(
                            "wcpt_close"
                        ); // close loading modal
                        deploy_product_form_modal(response, $button, ajax_data);
                    }
                });
            }

            return false;
        }

        // all required info already available, no need for modal
        if (link_code == "cart_ajax") {
            wcpt_cart(params);
        } else {
            submit_via_post($button.attr("href"), ajax_data);
        }
    }

    function deploy_product_form_modal(markup, $button, ajax_data) {
        var $modal = $(markup);
        $modal.appendTo("body");
        $("body").addClass("wcpt-modal-on");
        prep_product_form($modal, $button, ajax_data);
        $("body").trigger("wcpt_product_modal_ready");
    }

    function deploy_loading_modal() {
        var $loading_modal = $(
            $("#tmpl-wcpt-product-form-loading-modal").html()
        );
        $("body").append($loading_modal);
        $loading_modal.on("wcpt_close", function () {
            $loading_modal.remove();
        });
    }

    function is_incomplete_variation(variation_attributes) {
        var is_incomplete_variation = false;
        $.each(variation_attributes, function (key, value) {
            if (!value) {
                is_incomplete_variation = true;
                return false;
            }
        });

        return is_incomplete_variation;
    }

    function submit_via_post(href, data) {
        // redirect by form
        var $form = $(
            '<form method="POST" action="' +
                href +
                '" style="display: none;"></form>'
        );
        $.each(data, function (key, val) {
            if (key == "action") return; // continue
            var $input = $(
                '<input type="hidden" name="' + key + '" value="" />'
            );
            $input.val(val);
            $form.append($input);
        });
        $form.append(
            '<input type="hidden" name="wcpt_request" value="true" />'
        );
        $form.appendTo($("body")).submit();
    }

    function prep_product_form($modal, $button, pre_select) {
        var link_code = $button.attr("data-wcpt-link-code"),
            href = link_code == "cart_ajax" ? "" : $button.attr("href");

        $modal.on("wcpt_close", function () {
            $modal.remove();
            $("body").removeClass("wcpt-modal-on");
        });

        $(".cart", $modal).each(function () {
            var $form = $(this);

            if ($form.hasClass("variations_form")) {
                $form.wc_variation_form();
            } else {
                // simple product (probably with addon or measurement)
                $form.append(
                    '<input name="add-to-cart" type="hidden" value="' +
                        pre_select["product_id"] +
                        '">'
                );
            }

            // init addons
            if (typeof WC_PAO === "object") {
                new WC_PAO.Form($form);
            } else {
                if ($.fn.init_addon_totals) {
                    $form.init_addon_totals();
                }
                if (typeof wcPaoInitAddonTotals === "object") {
                    wcPaoInitAddonTotals.init($form);
                }
            }

            // init measurement
            if (typeof wcpt_wc_mc_init_cart !== "undefined") {
                $form.each(wcpt_wc_mc_init_cart);
            }

            // cart: name your price
            if (typeof jQuery.fn.wc_nyp_form !== "undefined") {
                $form.wc_nyp_form();
            }

            $form.attr("action", href);

            $(".qty", $form).attr("autocomplete", "off");

            if (pre_select) {
                $.each(pre_select, function (key, val) {
                    var $control = $form.find("[name=" + key + "]");
                    if ($control.is("input.qty")) {
                        // working on input
                        val = parseFloat(val);
                        var min = $control.attr("min")
                            ? parseFloat($control.attr("min"))
                            : 0;
                        var max = $control.attr("max")
                            ? parseFloat($control.attr("max"))
                            : false;

                        // respect min
                        if (val < min || isNaN(val)) {
                            val = min;
                        }

                        // respect max
                        if (max && val > max) {
                            val = max;
                        }
                    }
                    $control.val(val);
                });
            }

            // try and apply quantity on default variation
            if (pre_select.quantity) {
                $form.one("show_variation", function () {
                    var $form_qty = $(".qty", $form),
                        min = $form_qty.attr("min"),
                        max = $form_qty.attr("max");
                    if (
                        (!min || min <= pre_select.quantity) &&
                        (!max || max >= pre_select.quantity)
                    ) {
                        $form_qty.val(pre_select.quantity);
                    }
                });
            }

            if (link_code == "cart_ajax") {
                $form.on("submit", function (e) {
                    e.preventDefault();

                    var external_payload = {};

                    $.each($form.serializeArray(), function (i, field) {
                        if (
                            typeof external_payload[field.name] === "undefined"
                        ) {
                            external_payload[field.name] = field.value;
                        } else {
                            // should be array
                            if (
                                typeof external_payload[field.name] !== "object"
                            ) {
                                external_payload[field.name] = [
                                    external_payload[field.name]
                                ];
                            }
                            external_payload[field.name].push(field.value);
                        }
                    });

                    wcpt_cart({
                        external_payload: external_payload,
                        payload: { variation_form: true }
                    });

                    $modal.trigger("wcpt_close");
                });
            }

            // reset qty in row
            var $rows = wcpt_get_sibling_rows($button.closest(".wcpt-row"));
            $rows
                .find(".qty[data-wcpt-return-to-initial=1]")
                .val(0)
                .first()
                .trigger("change");
        });
    }

    function disable_button($button, add_condition) {
        if (add_condition) {
            $button.addClass(add_condition);
        }

        $button.addClass("wcpt-disabled");
    }

    function enable_button($button, clear_condition) {
        if (clear_condition) {
            $button.removeClass(clear_condition);
        }

        if (
            // list of conditions
            !$button.hasClass("wcpt-all-variations-out-of-stock") &&
            !$button.hasClass("wcpt-variation-out-of-stock") &&
            !$button.hasClass("wcpt-no-variation-selected") &&
            !$button.hasClass("wcpt-quantity-input-error") &&
            !$button.hasClass("wcpt-out-of-stock")
        ) {
            $button.removeClass("wcpt-disabled");
        }
    }

    function loading_badge_on_button($button) {
        disable_button($button);
        if (!$button.find(".wcpt-cart-badge-refresh").length) {
            var svg =
                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader" color="#384047"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg>';
            $button.append(
                '<i class="wcpt-cart-badge-refresh">' + svg + "</i>"
            );
        }
    }

    function add_count_badge_to_button(in_cart, $button) {
        if (!parseFloat(in_cart)) {
            $(
                ".wcpt-cart-badge-number, .wcpt-cart-badge-refresh",
                $button
            ).remove();
            return;
        }

        // if( $button.closest('.wcpt-row').hasClass('wcpt-sold-individually') ){
        //   in_cart = '✓';
        // }

        if (!$button.find(".wcpt-cart-badge-number").length) {
            $button.append(
                '<i class="wcpt-cart-badge-number">' + in_cart + "</i>"
            );
        } else {
            $button.find(".wcpt-cart-badge-number").html(in_cart);
        }

        if ($button.find(".wcpt-cart-badge-refresh").length) {
            $button.find(".wcpt-cart-badge-refresh").remove();
        }
    }

    // search
    // -- submit
    $("body").on("click", ".wcpt-search-submit", search_submit);
    $("body").on("keydown", ".wcpt-search-input", search_submit);
    function search_submit(e) {
        var $this = $(this),
            $search = $this.closest(".wcpt-search"),
            $input = $search.find(".wcpt-search-input"),
            table_id = $search.attr("data-wcpt-table-id"),
            $container = $("#wcpt-" + table_id),
            $nav_modal = $this.closest(".wcpt-nav-modal"),
            $nav = $this.closest(".wcpt-navigation"),
            keyword = $input.val().trim();
        (query = $input.attr("name") + "=" + keyword),
            ($wrapper = $input.closest(".wcpt-search-wrapper")),
            (append = !$wrapper.hasClass("wcpt-search--reset-others"));

        if (
            // submit button is clicked
            ($(e.target).closest(".wcpt-search-submit").length &&
                e.type == "click") || // enter key pressed on input
            ($(e.target).is(".wcpt-search-input") &&
                e.type == "keydown" &&
                (e.keyCode == 13 || e.which == 13))
        ) {
            if ($nav_modal.length) {
                $(".wcpt-nm-apply").click();
                return;
            }

            if (append) {
                $nav.trigger("change");
            } else {
                // reset other filters
                attempt_ajax($container, query, append, "filter");
            }

            if ($nav_modal.length) {
                $nav_modal.trigger("wcpt_close");
            }
        }
    }
    // -- clear
    $("body").on("click", ".wcpt-search-clear", function (e) {
        var $this = $(this),
            $search = $this.closest(".wcpt-search"),
            $input = $search.find(".wcpt-search-input"),
            table_id = $search.attr("data-wcpt-table-id"),
            $container = $("#wcpt-" + table_id),
            $nav_modal = $this.closest(".wcpt-nav-modal"),
            query = "&" + $input.attr("name") + "=",
            append = true;
        $input.val("");

        if ($nav_modal.length) {
            $(".wcpt-nm-apply").click();
            return;
        }

        attempt_ajax($container, query, append, "filter");

        if ($nav_modal.length) {
            $nav_modal.trigger("wcpt_close");
        }
    });

    // download button - responsive fix
    if (wcpt_params.initial_device !== "laptop") {
        $("body").on("click", ".wcpt-button[download]", function (e) {
            e.preventDefault();
            var $this = $(this),
                url = $this.attr("href");

            if (url) {
                window.open(url, "_blank", false);
            }
        });
    }

    // dropdown / tooltip

    // currently assigned trigger ev
    // -- default
    window.wcpt_global_tooltip_trigger_mode = "hover"; // hover (default) | click
    // -- switch to click
    $(window).on("touchstart", function () {
        window.wcpt_global_tooltip_trigger_mode = "click";
    });
    // -- switch to hover
    $(window).on("resize", function () {
        window.wcpt_global_tooltip_trigger_mode = "hover";
    });

    var target_selector = ".wcpt-dropdown, .wcpt-tooltip",
        $body = $("body");

    $body.on("mouseenter", target_selector, dropdown_hover_open);
    $body.on("mouseleave", target_selector, dropdown_hover_close);
    $body.on("click", dropdown_touch_toggle);

    function get_event_type_for_dropdown($dropdown) {
        var is_tooltip = !!$dropdown.closest(".wcpt-tooltip").length,
            is_in_sidebar = !!$dropdown.closest(".wcpt-left-sidebar").length;

        // click ev type is forced
        if (
            // -- in dropdown options
            $dropdown.hasClass("wcpt-tooltip--open-on-click") ||
            // -- we are on touchscreen
            wcpt_global_tooltip_trigger_mode == "click"
        ) {
            return "click";
        }

        // filters in sidebar alway use click
        if (!is_tooltip && is_in_sidebar) {
            return "click";
        }

        return "hover";
    }

    function dropdown_hover_open(e) {
        var $this = $(this);

        if ("hover" !== get_event_type_for_dropdown($this)) {
            return;
        }

        // hover intent
        // if( $this.hasClass('wcpt-tooltip--hover-intent-enabled') ){
        var clear_timeout = setTimeout(function () {
            // if( ! $this.is(':hover') ) return;
            $this.addClass("wcpt-open");
            fix_tooltip_position($this);
        }, 50);

        $this.data("wcpt_hover_intent_clear_timeout", clear_timeout);

        //   return;
        // }

        // $this.addClass('wcpt-open');
        // fix_tooltip_position($this);
    }

    function dropdown_hover_close(e) {
        var $this = $(this);

        if ("hover" !== get_event_type_for_dropdown($this)) {
            return;
        }

        if (
            $this.hasClass("wcpt-tooltip--open-on-click") ||
            wcpt_global_tooltip_trigger_mode == "click"
        ) {
            return;
        }

        // hover intent
        // if( $this.hasClass('wcpt-tooltip--hover-intent-enabled') ){
        var clear_timeout = $this.data("wcpt_hover_intent_clear_timeout");
        if (clear_timeout) {
            clearTimeout(clear_timeout);
        }
        // }

        $this.removeClass("wcpt-open");
    }

    function dropdown_touch_toggle(e) {
        var $target = $(e.target),
            container_selector = ".wcpt-dropdown, .wcpt-tooltip",
            content_selector =
                "> .wcpt-dropdown-menu, > .wcpt-tooltip-content-wrapper > .wcpt-tooltip-content",
            $dropdown = $target.closest(container_selector),
            $content = $dropdown.find(content_selector),
            $body = $("body");

        if ("click" !== get_event_type_for_dropdown($dropdown)) {
            return;
        }

        // clicked outside any tootltip / filter
        if (!$dropdown.length) {
            // close all dropdowns
            if (!$target.closest(".wcpt-nav-modal").length) {
                $body.find(container_selector).removeClass("wcpt-open");
            }

            return;
        }

        // clicked in dropdown
        if ($dropdown.length) {
            // -- clicked in content
            if ($target.closest($content).length) {
                // return;
                // -- clicked on trigger
            } else {
                // close all others

                // -- except parents and other open sidebar filters
                var $parents = $dropdown.parents(container_selector),
                    $sidebar_filters = $(".wcpt-left-sidebar  .wcpt-dropdown");

                $body
                    .find(container_selector)
                    .not($dropdown.add($parents).add($sidebar_filters))
                    .removeClass("wcpt-open");

                $dropdown.toggleClass("wcpt-open");

                // close all children
                if (!$dropdown.hasClass("wcpt-open")) {
                    $dropdown.find(container_selector).removeClass("wcpt-open");
                }

                // popup enabled
                if ($dropdown.hasClass("wcpt-tooltip--popup-enabled")) {
                    if ($dropdown.hasClass("wcpt-open")) {
                        // need to run open logic earlier
                        $body.addClass("wcpt-tooltip-popup-displayed");
                    } else {
                        $body.removeClass("wcpt-tooltip-popup-displayed");
                    }
                }
            }

            fix_tooltip_position($dropdown);
        }
    }

    function fix_tooltip_position($tooltip) {
        // correct position
        var $content = $tooltip.find(
                " > .wcpt-dropdown-menu, > .wcpt-tooltip-content-wrapper > .wcpt-tooltip-content"
            ),
            content_width = $content.outerWidth(false),
            offset_left = $content.offset().left,
            page_width = $(window).width();

        if ($tooltip.hasClass("wcpt-tooltip")) {
            // tooltip

            // narrow width
            $content.css("max-width", "");
            var $container, margin;

            if ($tooltip.hasClass("wcpt-tooltip--popup-enabled")) {
                $container = $("body");
                margin = 40;
            } else {
                $container = $tooltip.closest(
                    ".wcpt-table-scroll-wrapper-outer"
                ).length
                    ? $tooltip.closest(".wcpt-table-scroll-wrapper-outer")
                    : $tooltip.closest(".wcpt-navigation");
                margin = 20;
            }

            var container_rect = $.extend(
                    {},
                    $container[0].getBoundingClientRect()
                ),
                content_rect = $content[0].getBoundingClientRect();

            var $freezeTable = $(".frzTbl-table", $container).not(
                ".frzTbl-clone-table"
            );

            if ($content.closest($freezeTable).length) {
                // target is inside the original freeze_table
                var $left_freeze_column =
                        $freezeTable.data("freezeTable").el.$frozenColumnsLeft,
                    $right_freeze_column =
                        $freezeTable.data("freezeTable").el.$frozenColumnsRight;

                container_rect.left += $left_freeze_column.width();
                container_rect.right -= $right_freeze_column.width();
                container_rect.width =
                    container_rect.width -
                    $left_freeze_column.width() -
                    $right_freeze_column.width();
            }

            if (
                container_rect.width <
                parseInt(content_rect.width) + margin * 2
            ) {
                $content.css("max-width", container_rect.width - margin * 2);
            }

            // vertical position
            var $content_wrapper = $content.parent(),
                content_wrapper_width = $content_wrapper.width();

            $content_wrapper.attr("data-wcpt-position", "");
            content_rect = $content[0].getBoundingClientRect(); // refresh

            if (container_rect.bottom < content_rect.bottom + 30) {
                $content.parent().attr("data-wcpt-position", "above");
            }

            // horizontal position
            $content.css({ left: "", right: "" });
            content_rect = $content[0].getBoundingClientRect(); // refresh

            var arrow_margin = 20; // how close arrow gets to left/ right edge

            if (content_rect.left - 15 < container_rect.left) {
                // excess left
                var left = 15 + container_rect.left - content_rect.left,
                    limit = content_wrapper_width / 2 - arrow_margin;

                if (left > limit) {
                    left = limit;
                }

                $content.css("left", left);
            } else if (content_rect.right + 15 > container_rect.right) {
                // excess right
                var right = content_rect.right - container_rect.right + 15,
                    limit = content_wrapper_width / 2 - arrow_margin;

                if (right > limit) {
                    right = limit;
                }

                $content.css("right", right);
            }
        } else {
            // dropdown
            if (content_width + 30 > page_width) {
                $content.outerWidth(page_width - 30);
                var content_width = $content.outerWidth(false);
            }

            if ($content.offset().left + content_width > page_width) {
                // offscreen right
                var offset_required =
                    $content.offset().left + content_width - page_width;
                $content.css("left", "-=" + (offset_required + 15));
            } else if ($content.offset().left < 0) {
                // offscreen left
                $content.css("left", Math.abs($content.offset().left - 15));
            }
        }

        // tooltip arrow
        if ($tooltip.hasClass("wcpt-tooltip")) {
            var $label = $tooltip.find("> .wcpt-tooltip-label"),
                offset_left = $label.offset().left,
                width = $label.outerWidth(),
                $arrow = $("> .wcpt-tooltip-arrow", $content);

            $arrow.css(
                "left",
                offset_left - $content.offset().left + width / 2 + "px"
            );
        }
    }

    // tooltip with content hover disabled
    $("body").on("click mouseover", ".wcpt-tooltip-content", function () {
        var $this = $(this),
            $tooltip = $this.closest(".wcpt-tooltip");

        if ($tooltip.hasClass("wcpt-tooltip--hover-disabled")) {
            $tooltip.removeClass("wcpt-open");
        }
    });

    // close dropdown when grab and scroll starts
    $("body").on("freeze_table__grab_and_scroll__start", function () {
        $(
            ".wcpt-navigation:not(.wcpt-left-sidebar) .wcpt-dropdown.wcpt-open"
        ).removeClass("wcpt-open");
    });

    // apply nav filters
    $("body").on("change", ".wcpt-navigation", apply_nav);
    function apply_nav(e) {
        var $target = $(e.target),
            $container = $target.closest(".wcpt"),
            $nav = $container.find(".wcpt-navigation");

        // skip search filter options input
        if ($target.closest(".wcpt-search-filter-options").length) {
            return;
        }

        // skip date pickers
        if ($target.closest(".wcpt-filter-date-picker").length) {
            return;
        }

        // taxonomy hierarchy
        if ($target.closest(".wcpt-hierarchy").length) {
            var checked = $target.prop("checked");

            // effect on child terms
            if ($target.hasClass("wcpt-hr-parent-term")) {
                var ct_selector = "input[type=checkbox], input[type=radio]",
                    $child_terms = $target
                        .closest("label")
                        .siblings(".wcpt-hr-child-terms-wrapper")
                        .find(ct_selector);
                $child_terms.prop("checked", false);
            }

            // effect on parent terms
            var $ancestors = $target.parents(".wcpt-hr-child-terms-wrapper");
            if ($ancestors.length) {
                $ancestors.each(function () {
                    var $parent_term = $(this)
                        .siblings("label")
                        .find(".wcpt-hr-parent-term");
                    $parent_term.prop("checked", false);
                });
            }
        }

        // range filter
        if ($target.closest(".wcpt-range-filter")) {
            // -- input boxes shouldn't propagate
            if (
                $target.hasClass("wcpt-range-input-min") ||
                $target.hasClass("wcpt-range-input-max") ||
                $target.hasClass("wcpt-range-slider")
            ) {
                return;
            }

            var min = $target.attr("data-wcpt-range-min") || "",
                max = $target.attr("data-wcpt-range-max") || "",
                $range_filter = $target.closest(".wcpt-range-filter"),
                $min = $range_filter.find(".wcpt-range-input-min"),
                $max = $range_filter.find(".wcpt-range-input-max"),
                $range_slider = $range_filter.find(
                    ".wcpt-range-slider.original"
                );

            $min.val(min);
            $max.val(max);
            if (!min) {
                min = $range_slider.attr("min");
            }
            if (!max) {
                max = $range_slider.attr("max");
            }

            $range_slider.val(min + "," + max);
        }

        // search
        if ($target.closest(".wcpt-search").length) {
            return;
        }

        // modal
        if ($target.closest(".wcpt-nav-modal").length) {
            return;
        }

        var $this = $(this),
            $nav = $this.add($this.siblings(".wcpt-navigation")), // combine query from all navs
            $container = $nav.closest(".wcpt"),
            table_id = $container.attr("id").substring(5),
            $nav_clone = $nav.clone();

        nav_clone_operations($nav_clone);

        // build query
        var query = $("<form>").append($nav_clone).serialize();

        // include column sort
        if (!$(e.target).closest('[data-wcpt-filter="sort_by"]').length) {
            var $table = wcpt_get_container_original_table($container),
                $sortable_headings = $(
                    ".wcpt-heading.wcpt-sortable:visible",
                    $table
                ),
                $current_sort_col = $sortable_headings.filter(function () {
                    return $(this).find(
                        ".wcpt-sorting-icons.wcpt-sorting-asc, .wcpt-sorting-icons.wcpt-sorting-desc"
                    ).length;
                });
            if ($current_sort_col.length) {
                var col_index = $current_sort_col.attr(
                        "data-wcpt-column-index"
                    ),
                    order = $current_sort_col.find(
                        ".wcpt-sorting-icons.wcpt-sorting-asc"
                    ).length
                        ? "ASC"
                        : "DESC";
                query +=
                    "&" +
                    table_id +
                    "_orderby=column_" +
                    col_index +
                    "&" +
                    table_id +
                    "_order=" +
                    order;
            }
        }

        // do not proceed if 'Apply' button is available, just give feedback
        if (
            $nav.find(".wcpt-apply").length &&
            !$(e.target).hasClass("wcpt-navigation")
        ) {
            nav_filter_feedback($nav);
            return;
        }

        attempt_ajax($container, query, false, "filter");
    }

    function nav_clone_operations($nav_clone) {
        var $reverse_check = $();

        $("[data-wcpt-reverse-value]:not(:checked)", $nav_clone).each(
            function () {
                var $this = $(this);
                $this.attr("value", $this.attr("data-wcpt-reverse-value"));
                $this.prop("checked", "checked");
                $reverse_check = $reverse_check.add($this.clone());
            }
        );
        $nav_clone = $nav_clone.add($reverse_check);

        // clean radio names
        $('input[type="radio"]', $nav_clone).each(function () {
            var $this = $(this),
                name = $this.attr("name");

            if (-1 !== name.indexOf("--")) {
                var is_array = name.indexOf("[]");
                name =
                    name.substr(0, name.indexOf("--")) + (is_array ? "[]" : "");
                $this.attr("name", name);
            }
        });
    }

    function nav_filter_feedback($nav) {
        // header nav
        $(".wcpt-filter", $nav.filter(".wcpt-header")).each(function () {
            var $this = $(this),
                filter = $this.attr("data-wcpt-filter"),
                $filter = $this.closest(".wcpt-filter"),
                format = $this.attr("data-wcpt-heading_format__op_selected"),
                radio =
                    $this.find("input[type=radio]").length ||
                    $this.hasClass("wcpt-range-filter"),
                checkbox = $this.find("input[type=checkbox]").length,
                $selected = $this.find("input[type=radio]:checked"),
                $checked = $this.find("input[type=checkbox]:checked"),
                checked_count = $checked.length,
                $active_count = $this.find(".wcpt-active-count"),
                radio_permit = false,
                label_append = "",
                $multi_range = $(".wcpt-range-options-main", $filter),
                $multi_range__min = $(
                    ".wcpt-range-options-main .wcpt-range-input-min",
                    $filter
                ),
                $multi_range__max = $(
                    ".wcpt-range-options-main .wcpt-range-input-max",
                    $filter
                );

            if ($this.hasClass("wcpt-options-row")) {
                return;
            }

            if (
                -1 ==
                $.inArray(filter, [
                    "custom_field",
                    "attribute",
                    "category",
                    "taxonomy",
                    "price_range",
                    "rating",
                    "sort_by",
                    "results_per_page",
                    "on_sale",
                    "availability"
                ])
            ) {
                return;
            }

            // mark active filters
            if (
                checked_count ||
                ($selected.val() &&
                    !$selected.closest(".wcpt-default-option").length) || // sort by
                ($multi_range.length &&
                    ($multi_range__min.val() != $multi_range__min.attr("min") ||
                        $multi_range__max.val() !=
                            $multi_range__max.attr("max")))
            ) {
                $this.closest(".wcpt-filter").addClass("wcpt-filter--active");
            } else {
                $this
                    .closest(".wcpt-filter")
                    .removeClass("wcpt-filter--active");
            }

            // modify dropdown heading

            // -- radio append option label
            if (radio && format !== "only_heading") {
                $this.find(".wcpt-radio-op-selected__heading-append").remove();

                if (
                    !$selected.length ||
                    !$selected.attr("value") // 'show all' op selected
                ) {
                    $this.removeClass("wcpt-radio-op-selected");
                } else {
                    // selected and has value
                    $this.addClass("wcpt-radio-op-selected");
                    label_append = $selected.next()[0].outerHTML;
                    radio_permit = true;
                }

                if (!$selected.length && filter == "price_range") {
                    var min =
                            wcpt_params.currency_symbol +
                                $(".wcpt-range-input-min", $this).val() || 0,
                        max =
                            wcpt_params.currency_symbol +
                                $(".wcpt-range-input-max", $this).val() || 0;

                    label_append = "<span>" + min + " - " + max + "<span>";

                    if (
                        $(".wcpt-range-input-min", $this).val() !=
                            $(".wcpt-range-input-min", $this).attr("min") ||
                        $(".wcpt-range-input-max", $this).val() !=
                            $(".wcpt-range-input-max", $this).attr("max")
                    ) {
                        $this.addClass("wcpt-radio-op-selected");
                        radio_permit = true;
                    }
                }

                if (!$selected.length && filter == "custom_field") {
                    var min = $(".wcpt-range-input-min", $this).val() || 0,
                        max = $(".wcpt-range-input-max", $this).val() || 0;

                    label_append = "<span>" + min + " - " + max + "<span>";

                    if (
                        min != $(".wcpt-range-input-min", $this).attr("min") ||
                        max != $(".wcpt-range-input-max", $this).attr("max")
                    ) {
                        $this.addClass("wcpt-radio-op-selected");
                        radio_permit = true;
                    }
                }

                if (radio_permit) {
                    $this
                        .find(".wcpt-dropdown-label")
                        .append(
                            '<div class="wcpt-radio-op-selected__heading-append">' +
                                label_append +
                                "</div>"
                        );
                }

                // -- checkbox append selected option count
            } else if (checkbox) {
                $active_count.remove();

                if (checked_count) {
                    $active_count = $(
                        '<span class="wcpt-active-count" style="margin-left: 6px">' +
                            checked_count +
                            "</span>"
                    );
                    $(".wcpt-filter-heading .wcpt-dropdown-label", $this).after(
                        $active_count
                    );
                }
            }
        });
    }

    // submit range by enter
    $("body").on(
        "keyup",
        ".wcpt-range-input-min, .wcpt-range-input-max",
        function (e) {
            var $this = $(this),
                $filters = $this.closest(".wcpt-navigation"),
                code = e.keyCode ? e.keyCode : e.which;

            if (code == 13) {
                $filters.trigger("change");
            }
        }
    );

    // submit range
    $("body").on("click", ".wcpt-range-submit-button", function (e) {
        var $this = $(this),
            $range_box = $this.closest(".wcpt-range-options-main"),
            $min = $(".wcpt-range-input-min", $range_box),
            $max = $(".wcpt-range-input-max", $range_box),
            $filters = $this.closest(".wcpt-navigation");

        // ensure max stays greater than min
        if (parseFloat($min.val()) > parseFloat($max.val())) {
            var max_val = $max.val(),
                min_val = $min.val();

            $max.val(min_val);
            $min.val(max_val);
        }

        $filters.trigger("change");
    });

    // date picker

    // -- submit
    $("body").on("click", ".wcpt-date-picker-submit-button", function (e) {
        var $this = $(this),
            $filter = $this.closest('[data-wcpt-filter="date_picker"]'),
            $start_date = $(".wcpt-filter-date-picker--start-date", $filter),
            $end_date = $(".wcpt-filter-date-picker--end-date", $filter),
            $filters = $this.closest(".wcpt-navigation");

        if ($start_date.val() && $end_date.val()) {
            var start_date = new Date($start_date.val()),
                end_date = new Date($end_date.val());

            if (start_date > end_date) {
                var start_date_val = $start_date.val(),
                    end_date_val = $end_date.val();

                $start_date.val(end_date_val);
                $end_date.val(start_date_val);
            }
        }

        $filters.trigger("change");
    });
    // -- -- enter key
    $("body").on("keydown", ".wcpt-filter-date-picker", function (e) {
        if (e.keyCode === 13 || e.which === 13) {
            var $this = $(this),
                $filters = $this.closest(".wcpt-navigation");

            $filters.trigger("change");
        }
    });

    // -- reset
    $("body").on("click", ".wcpt-date-picker-reset-button", function (e) {
        e.preventDefault();

        var $this = $(this),
            $filter = $this.closest('[data-wcpt-filter="date_picker"]'),
            $date_picker = $(".wcpt-filter-date-picker", $filter),
            $filters = $this.closest(".wcpt-navigation");

        $date_picker.val("");
        $filters.trigger("change");
    });

    // -- validate
    $("body").on("keyup", ".wcpt-filter-date-picker", function (e) {
        var $this = $(this);
        if (
            $this.val().length > 10 ||
            -1 == $.inArray($this.val()[0], ["1", "2"])
        ) {
            $this.css("border-color", "red");
        } else {
            $this.css("border-color", "");
        }
    });

    // clear filter
    $("body").on("click", ".wcpt-clear-filter", function (e) {
        var $clear_filter = $(this),
            $target = $(e.target);

        if ($target.closest(".wcpt-dropdown-menu")) {
            var $sub_option = $target.closest(".wcpt-dropdown-option");
        } else {
            $sub_option = false;
        }

        var $container = $clear_filter.closest(".wcpt"),
            filter = $clear_filter.attr("data-wcpt-filter"),
            $navs = $("> .wcpt-navigation", $container),
            $inputs = $();

        if (filter == "search") {
            var name = $clear_filter.attr("data-wcpt-search-name"),
                $inputs = $('.wcpt-search-input[name="' + name + '"]', $navs);
        } else if (
            filter == "attribute" ||
            filter == "category" ||
            filter == "taxonomy"
        ) {
            var taxonomy = $clear_filter.attr("data-wcpt-taxonomy"),
                term = $clear_filter.attr("data-wcpt-value"),
                $inputs = $navs
                    .find(
                        '.wcpt-filter[data-wcpt-filter="' +
                            filter +
                            '"][data-wcpt-taxonomy="' +
                            taxonomy +
                            '"]'
                    )
                    .find('input[value="' + term + '"]');
        } else if (filter == "custom_field") {
            var meta_key = $clear_filter.attr("data-wcpt-meta-key"),
                value = $clear_filter.attr("data-wcpt-value"),
                $filter = $navs.find(
                    '.wcpt-filter[data-wcpt-filter="' +
                        filter +
                        '"][data-wcpt-meta-key="' +
                        meta_key +
                        '"]'
                );

            if ($filter.hasClass("wcpt-range-filter")) {
                $inputs = $filter.find("input");
            } else {
                $inputs = $navs
                    .find(
                        '.wcpt-filter[data-wcpt-filter="' +
                            filter +
                            '"][data-wcpt-meta-key="' +
                            meta_key +
                            '"]'
                    )
                    .find('input[value="' + value + '"]');
            }
        } else if (filter == "price_range") {
            $inputs = $navs
                .find('.wcpt-filter[data-wcpt-filter="' + filter + '"]')
                .find("input");
        } else if (filter == "search") {
            $inputs = $navs.find(
                'input[type=search][data-wcpt-value="' +
                    htmlentity($clear_filter.attr("data-wcpt-value")) +
                    '"]'
            );
        } else if (filter == "rating") {
            $inputs = $navs
                .find('.wcpt-filter[data-wcpt-filter="rating"]')
                .find("input");
        } else if (filter == "date_picker") {
            var val = $clear_filter.attr("data-wcpt-value"),
                $inputs = $navs
                    .find('.wcpt-filter[data-wcpt-filter="date_picker"]')
                    .find('input[value="' + val + '"]');
        } else {
            var value = $clear_filter.attr("data-wcpt-value"),
                $inputs = $navs
                    .find('.wcpt-filter[data-wcpt-filter="' + filter + '"]')
                    .find('input[value="' + value + '"]');
        }

        $inputs
            .filter(":input[type=checkbox], :input[type=radio]")
            .prop("checked", false)
            .closest("label.wcpt-active")
            .removeClass("wcpt-active");

        $inputs
            .filter(
                ":input[type=text], :input[type=number], :input[type=search], :input[type=date]"
            )
            .val(""); // search and range input

        $navs.first().trigger("change");

        // remove clear filter
        if (!$clear_filter.siblings(".wcpt-clear-filter").length) {
            $clear_filter.closest(".wcpt-clear-filters-wrapper").remove();
        } else {
            $clear_filter.remove();
        }
    });

    // clear all filters
    $("body").on(
        "click",
        ".wcpt-clear-filters, .wcpt-clear-all-filters, .wcpt-reset",
        function (e) {
            e.preventDefault();
            var $this = $(this),
                $container = $this.closest(".wcpt"),
                query = "";

            if (!$this.hasClass("wcpt-disabled")) {
                attempt_ajax($container, query, false, "filter");
            }
        }
    );

    // sort by column heading
    window.wcpt_column_heading_sort_handler = function () {
        var $this = $(this),
            $sorting = $this.find(".wcpt-sorting-icons");

        if (!$sorting.length) {
            return;
        }

        var order = $sorting.hasClass("wcpt-sorting-asc") ? "desc" : "asc",
            col_index = $this.attr("data-wcpt-column-index"),
            $container = $this.closest(".wcpt"),
            table_id = $container.attr("id").substring(5),
            device = "laptop";

        if (
            $(".wcpt-sorting-" + order + "-icon", $sorting).hasClass(
                "wcpt-hide"
            )
        ) {
            if (
                $(".wcpt-sorting-" + order + "-icon", $sorting)
                    .siblings()
                    .hasClass("wcpt-active")
            ) {
                return;
            } else {
                order = order == "asc" ? "desc" : "asc";
            }
        }

        var query =
            table_id +
            "_paged=1&" +
            table_id +
            "_orderby=column_" +
            col_index +
            "&" +
            table_id +
            "_order=" +
            order +
            "&" +
            table_id +
            "_device=" +
            device;

        attempt_ajax($container, query, true, false);
    };

    // pagination
    $("body").on(
        "click",
        ".wcpt-pagination .page-numbers:not(.dots):not(.current)",
        function (e) {
            e.preventDefault();
            var $this = $(this),
                $container = $this.closest(".wcpt"),
                table_id = wcpt_util.get_table_id($container),
                url = $this.attr("href"),
                index = url.indexOf("?"),
                params =
                    index == -1
                        ? false
                        : wcpt_util.parse_query_string(url.slice(index + 1)),
                page = params ? params[table_id + "_paged"] : 1,
                query = table_id + "_paged=" + page;
            append = true;

            attempt_ajax($container, query, append, "paginate");
        }
    );

    // ajax
    var attempt_ajax = (window.wcpt_attempt_ajax = (
        $container,
        new_query,
        append,
        purpose
    ) => {
        var query = build_ajax_query_string(
            $container,
            new_query,
            append,
            purpose
        );

        // get markup over and apply success callback
        fetch_markup_and_apply_callback(
            $container,
            query,
            purpose,
            ajax_success
        );
    });

    // ajax successful
    function ajax_success(response, $container) {
        $("body").trigger("wcpt_before_ajax_container_replace", {
            response: response,
            $container: $container
        });

        // var $new_container = $(response);
        // $container.replaceWith( $new_container );
        // $container = $new_container;

        // replace $container inner html without losing refrence required by internal callback
        var $new_container = $(response);
        $container.html($new_container.html());

        // $container.attr('data-wcpt-query-string', $new_container.attr('data-wcpt-query-string'));
        [...$new_container[0].attributes].forEach(attr => {
            if (attr.nodeName.substr(0, 10) === "data-wcpt-") {
                $container[0].setAttribute(attr.nodeName, attr.nodeValue);
            }
        });

        // scroll
        // -- get params
        var sc_attrs_string = $container.attr("data-wcpt-sc-attrs"),
            sc_attrs =
                sc_attrs_string && sc_attrs_string !== "{}"
                    ? JSON.parse(sc_attrs_string)
                    : {},
            offset = {
                laptop:
                    typeof sc_attrs.laptop_scroll_offset == "undefined" ||
                    sc_attrs.laptop_scroll_offset == ""
                        ? 20
                        : sc_attrs.laptop_scroll_offset,
                tablet:
                    typeof sc_attrs.tablet_scroll_offset == "undefined" ||
                    sc_attrs.tablet_scroll_offset == ""
                        ? 20
                        : sc_attrs.tablet_scroll_offset,
                phone:
                    typeof sc_attrs.phone_scroll_offset == "undefined" ||
                    sc_attrs.phone_scroll_offset == ""
                        ? 20
                        : sc_attrs.phone_scroll_offset
            };

        // -- do scroll
        var device = wcpt_get_device_2($container);
        if (sc_attrs["_auto_scroll"] || sc_attrs[device + "_auto_scroll"]) {
            var offset = offset[device];
            if (isNaN(offset)) {
                if (typeof offset === "string") {
                    // selector
                    offset = $(offset).height();
                } else if (typeof offset === "object") {
                    // jQuery object
                    offset = offset.height();
                }
            }

            $("html, body").animate(
                {
                    scrollTop: $container.offset().top - parseFloat(offset)
                },
                200
            );
        }
    }

    var build_ajax_query_string = (window.wcpt_build_ajax_query_string = (
        $container,
        new_query,
        append,
        purpose
    ) => {
        var table_id = $container.attr("data-wcpt-table-id");

        if (typeof purpose == "undefined") {
            throw "WCPT: Define AJAX purpose";
        }

        // combine earlier query
        var query = "",
            earlier_query = $container.attr("data-wcpt-query-string");
        if (append && earlier_query) {
            earlier_query = earlier_query.substring(1);
            query = "?";

            $.each(
                $.extend(
                    {},
                    wcpt_util.parse_query_string(earlier_query),
                    wcpt_util.parse_query_string(new_query)
                ),
                function (key, val) {
                    if (val !== "undefined") {
                        query += key + "=" + encodeURIComponent(val) + "&";
                    }
                }
            );
            query = query.substring(0, query.length - 1);
        } else {
            query = "?" + new_query;
        }

        // lazy load
        if (purpose == "lazy_load") {
            query += "&" + window.location.search.substr(1);
        }

        // persist params
        var parsed_params = wcpt_util.parse_query_string(
            window.location.search.substring(1)
        );
        var query_obj = wcpt_util.parse_query_string(query.substring(1));

        if (typeof window.wcpt_persist_params !== "undefined") {
            $.each(wcpt_persist_params, function (index, i) {
                if (
                    parsed_params[i] !== "undefined" &&
                    typeof parsed_params[i] !== "undefined" &&
                    typeof query_obj[i] == "undefined"
                ) {
                    query += "&" + i + "=" + parsed_params[i];
                }
            });
        }

        // device
        var device = wcpt_get_device_2($container);

        var query_obj = wcpt_util.parse_query_string(query);
        if (query_obj[table_id + "_device"] !== device) {
            query += "&" + table_id + "_device=" + device;
        }

        // shortcode attributes
        var sc_attrs = get_sc_attrs($container);

        // search - orderby relevance
        var new_query_p = new_query
                ? wcpt_util.parse_query_string(new_query)
                : {},
            earlier_query_p = earlier_query
                ? wcpt_util.parse_query_string(earlier_query.substring(1))
                : {},
            search_orderby = sc_attrs.search_orderby
                ? "search_orderby"
                : "relevance";
        search_order = sc_attrs.search_order ? "search_order" : "";

        // detect if a new search is taking place in this query
        $.each(new_query_p, function (key, val) {
            if (
                key.indexOf("search") !== -1 &&
                val &&
                earlier_query_p[key] !== val.replace(/\+/g, " ")
            ) {
                // reset pagination and search orderby and order
                query +=
                    "&" +
                    table_id +
                    "_orderby=" +
                    search_orderby +
                    "&" +
                    table_id +
                    "_order=" +
                    search_order +
                    "&" +
                    table_id +
                    "_paged=1";
                return false;
            }
        });

        // form mode, hide form on submit
        if (sc_attrs.form_mode && sc_attrs.hide_form_on_submit) {
            query += "&hide_form=" + table_id;
        }

        // flag table has been filtered
        if (purpose == "filter") {
            query += "&" + table_id + "_filtered=true";
        }

        // coming from shop?
        if (parsed_params[table_id + "_from_shop"]) {
            query += "&" + table_id + "_from_shop=true";
        }

        // add shortcode attributes
        if (!$.isEmptyObject(sc_attrs)) {
            // applying fix % conflict with % -> %25
            // query += '&' + table_id + '_sc_attrs=' + encodeURIComponent( _sc_attrs.replaceAll('%', '%25') );
            query +=
                "&" +
                table_id +
                "_sc_attrs=" +
                encodeURIComponent(JSON.stringify(sc_attrs));
        }

        // form mode (redirect to shop with params)
        if (purpose == "filter" && sc_attrs.form_mode) {
            // switch table id
            query = query
                .split(table_id + "_")
                .join(wcpt_params.shop_table_id + "_");

            // don't let lang param repeat, lang=fr,fr error
            var i = wcpt_params.shop_url.indexOf("?lang=");
            if (i !== -1 && query.indexOf("lang=") !== -1) {
                wcpt_params.shop_url = wcpt_params.shop_url.substring(0, i);
            }

            if (wcpt_params.shop_url.indexOf("?") == -1) {
                query = wcpt_params.shop_url + query;
            } else {
                query = wcpt_params.shop_url + "&" + query.slice(1);
            }

            // disable device requirement
            query += "&" + wcpt_params.shop_table_id + "_device=";
        }

        // trigger body event to hook in
        window.wcpt_query = query;
        $("body").trigger("wcpt_before_table_query", {
            query: query,
            $wcpt: $container
        });
        query = window.wcpt_query;
        delete window.wcpt_query;

        return query;
    });

    function get_sc_attrs($container) {
        var _sc_attrs = $container.attr("data-wcpt-sc-attrs"),
            sc_attrs =
                _sc_attrs && _sc_attrs !== "{}" ? JSON.parse(_sc_attrs) : {};
        return sc_attrs;
    }

    // device being focused by table
    function wcpt_get_device_2($container) {
        var device = "laptop",
            $scroll_outer = $container.find(
                ".wcpt-table-scroll-wrapper-outer:visible"
            ),
            table_id = $container.attr("data-wcpt-table-id");

        if ($scroll_outer.length) {
            if ($scroll_outer.hasClass("wcpt-device-phone")) {
                device = "phone";
            } else if ($scroll_outer.hasClass("wcpt-device-tablet")) {
                device = "tablet";
            }
        } else if ($("body").hasClass("wcpt-nav-modal-on")) {
            device = $(".wcpt-nav-modal").attr("data-wcpt-device");
        } else if (
            $(
                ".wcpt-required-but-missing-nav-filter-message, .wcpt-no-results",
                $container
            ).length
        ) {
            device = $(
                ".wcpt-required-but-missing-nav-filter-message, .wcpt-no-results",
                $container
            ).attr("data-wcpt-device");
        }

        return device;
    }

    // request markup from sever and apply callback
    var fetch_markup_and_apply_callback =
        (window.wcpt_fetch_markup_and_apply_callback = (
            $container,
            query,
            purpose,
            callback
        ) => {
            var table_id = $container.attr("data-wcpt-table-id"),
                sc_attrs = wcpt_util.get_sc_attrs($container);

            // prep url
            var url =
                    wcpt_params.wc_ajax_url.replace(
                        "%%endpoint%%",
                        "wcpt_ajax"
                    ) +
                    "&" +
                    query.slice(1),
                data = {
                    id: table_id
                };

            // form mode / disable ajax redirect
            if (
                sc_attrs.form_mode ||
                (sc_attrs.disable_ajax && sc_attrs.disable_ajax !== "false")
            ) {
                window.location = query;
                return;
            }

            // allow table from cache @TODO -- convert to query param
            var permit_cache =
                purpose !== "refresh_table" &&
                typeof WavePlayer === "undefined";

            if (
                // use cache
                permit_cache &&
                window.wcpt_cache.exist(query)
            ) {
                callback(window.wcpt_cache.get(query), $container);
                internal_callback($container);
                return;
            } else {
                // else AJAX
                $.ajax({
                    url: url,
                    method: "GET",
                    beforeSend: function () {
                        $container.addClass("wcpt-loading");
                        $container.trigger("wcpt_before_ajax");
                        return true;
                    },
                    data: data
                }).done(function (response) {
                    // success
                    if (response && response.indexOf("wcpt-table") !== -1) {
                        window.wcpt_cache.set(query, response); // update cache
                        callback(window.wcpt_cache.get(query), $container);
                        internal_callback($container);

                        // fail
                    } else {
                        console.log("wcpt notice: query fail");
                    }

                    $container.removeClass("wcpt-loading");
                });
            }

            function internal_callback($container) {
                after_every_load($container);
            }
        });

    // variable product modal form
    //-- close modal
    $("body").on("click", ".wcpt-modal, .wcpt-close-modal", function (e) {
        var $target = $(e.target),
            $modal = $(this).closest(".wcpt-modal");
        if (
            $target.hasClass("wcpt-modal") ||
            $target.closest(".wcpt-close-modal").length
        ) {
            $modal.trigger("wcpt_close");
        }
    });

    window.wcpt_update_cart_items = function (cart) {
        var cart_products = {},
            total = 0;
        $.each(cart, function (key, item) {
            if (!cart_products[item.product_id]) {
                cart_products[item.product_id] = 0;
            }

            if (item.variation_id && !cart_products[item.variation_id]) {
                cart_products[item.variation_id] = 0;
            }

            cart_products[item.product_id] += item.quantity;

            if (item.variation_id) {
                cart_products[item.variation_id] += item.quantity;
            }

            total += item.quantity;
        });

        // -- update each product row
        $(".wcpt-row").each(function () {
            var $this = $(this),
                id = $this.attr("data-wcpt-variation-id")
                    ? $this.attr("data-wcpt-variation-id")
                    : $this.attr("data-wcpt-product-id"),
                qty = cart_products[id] ? cart_products[id] : 0,
                $badge = $this.find(".wcpt-cart-badge-number"),
                $remove = $this.find(".wcpt-remove");

            $this.attr("data-wcpt-in-cart", qty);

            if (qty) {
                add_count_badge_to_button(qty, $badge.closest(".wcpt-button"));
            } else {
                $badge.text("");
            }
        });
    };

    // anchor tag
    $("body").on("click touchstart", "[data-wcpt-href]", function () {
        window.location = $(this).attr("data-wcpt-href");
    });

    // accordion
    //-- filters
    // $('body').on('click', '.wcpt-left-sidebar .wcpt-filter > .wcpt-filter-heading', function(e){
    //   if( $(e.target).closest('.wcpt-tooltip').length ){
    //     return;
    //   }

    //   var $this = $(this),
    //       $filter = $this.closest('.wcpt-filter');
    //   $filter.toggleClass('wcpt-filter-open wcpt-open');
    // })
    // //-- filter clicked directly outside of heading
    // $('body').on('click', '.wcpt-left-sidebar .wcpt-filter:not(.wcpt-filter-open)', function(e){
    //   var $this = $(this);
    //   if( e.target === this ){
    //     $this.addClass('wcpt-filter-open');
    //   }
    // })
    //-- taxonomy parent
    $("body").on("click", ".wcpt-ac-icon", function (e) {
        var $this = $(this);
        $this.closest(".wcpt-accordion").toggleClass("wcpt-ac-open");
        e.stopPropagation();
        return false;
    });

    // nav modal
    function nav_modal(e) {
        var $button = $(e.target).closest(".wcpt-rn-button"),
            modal_type = $button.attr("data-wcpt-modal"),
            $wcpt = $button.closest(".wcpt"),
            wcpt_id = $wcpt.attr("id"),
            $nav_modal = $($wcpt.find(".wcpt-nav-modal-tpl").html()),
            $filters = $wcpt
                .find(".wcpt-filter")
                .not('[data-wcpt-filter="sort_by"]'),
            $search = $wcpt.find(".wcpt-search-wrapper"),
            $sort = $wcpt.find('[data-wcpt-filter="sort_by"].wcpt-filter'),
            radios = {};

        $(".wcpt-nm-sort-placeholder", $nav_modal).replaceWith($sort.clone());
        $(".wcpt-nm-filters-placeholder", $nav_modal).replaceWith(
            $search.clone().add($filters.clone())
        );

        $(
            '.wcpt-nm-sort-placeholder [data-wcpt-filter="sort_by"]',
            $nav_modal
        ).addClass("wcpt-open");

        if (modal_type == "sort") {
            $nav_modal
                .addClass("wcpt-show-sort")
                .removeClass("wcpt-show-filters");
        } else {
            // filter
            $nav_modal
                .addClass("wcpt-show-filters")
                .removeClass("wcpt-show-sort");
        }

        // record radios
        $wcpt.find("input[type=radio]:checked").each(function () {
            var $this = $(this);
            radios[$this.attr("name")] = $this.val();
        });
        $nav_modal.data("wcpt-radios", radios);

        // ':' at the end of row labels
        $nav_modal
            .find(
                ".wcpt-filter.wcpt-options-row > .wcpt-filter-heading > .wcpt-options-heading > .wcpt-item-row > .wcpt-text:last-child"
            )
            .each(function () {
                var $this = $(this),
                    text = $this.text().trim();

                if (text.substr(-1) === ":") {
                    $this.text(text.substr(0, text.length - 1));
                }
            });

        // duplicate header
        // if( ! $nav_modal.find('.wcpt-nm-heading--sticky').length ){
        //   var $header = $nav_modal.find('.wcpt-nm-heading');
        //   $header.clone().addClass('wcpt-nm-heading--sticky').insertAfter( $header );
        // }

        // append
        window.wcpt_nav_modal_scroll = window.scrollY;

        $("body")
            .trigger("wcpt-nav-modal-on")
            .addClass("wcpt-nav-modal-on")
            .append($nav_modal);

        // multirange
        $(".wcpt-range-slider-wrapper", $nav_modal).each(function () {
            var $this = $(this),
                $original = $this.children(".original"),
                $ghost = $this.children(".ghost"),
                $new_slider = $("<input/>").attr({
                    type: "range",
                    class: "wcpt-range-slider",
                    min: $original.attr("min"),
                    max: $original.attr("max"),
                    step: $original.attr("step"),
                    value: $original.attr("data-wcpt-initial-value")
                });

            $original.add($ghost).remove();

            $this.append($new_slider);
            wcpt__multirange($new_slider[0]);
        });

        // apply
        //-- filter
        $nav_modal.find(".wcpt-nm-apply").on("click", function () {
            var $nav_clone = $nav_modal.clone();

            nav_clone_operations($nav_clone);

            var query = $("<form>").append($nav_clone).serialize(),
                $container = $("#" + wcpt_id);

            $("body")
                .trigger("wcpt-nav-modal-off")
                .removeClass("wcpt-nav-modal-on");

            $nav_modal.remove();
            window.scroll(null, wcpt_nav_modal_scroll);

            attempt_ajax($container, query, false, "filter");
        });
        //-- sort
        $nav_modal.filter(".wcpt-show-sort").on("change", function () {
            var query = $("<form>").append($nav_modal.clone()).serialize(),
                $container = $("#" + wcpt_id);

            $nav_modal.trigger("wcpt_close");

            attempt_ajax($container, query, false, "filter");
        });

        // clear
        $nav_modal.find(".wcpt-nm-reset").on("click", function () {
            var query = $("<form>").append($nav_modal.clone()).serialize(),
                $container = $("#" + wcpt_id),
                query = "";

            $nav_modal.trigger("wcpt_close");

            attempt_ajax($container, query, false, "filter");
        });

        // close
        $nav_modal.find(".wcpt-nm-close").on("click", function (e) {
            e.preventDefault();

            var $container = $("#" + wcpt_id),
                radios = $.extend({}, $nav_modal.data("wcpt-radios"));

            $nav_modal.trigger("wcpt_close");

            $.each(radios, function (name, val) {
                $wcpt
                    .find(
                        'input[type=radio][name="' +
                            name +
                            '"][value="' +
                            val +
                            '"]'
                    )
                    .each(function () {
                        $(this).prop("checked", "checked");
                    });
            });
        });

        // scroll fix
        var prev_y = false;

        $(".wcpt-nav-modal")
            .on("touchstart", function (e) {
                prev_y = e.originalEvent.touches[0].clientY;
            })
            .on("touchmove", function (e) {
                if (
                    (e.originalEvent.touches[0].clientY > prev_y &&
                        !this.scrollTop) ||
                    (e.originalEvent.touches[0].clientY < prev_y &&
                        this.scrollTop ===
                            this.scrollHeight - this.offsetHeight)
                ) {
                    e.preventDefault();
                }
            });
    }

    $("body").on("wcpt_close", ".wcpt-nav-modal", function () {
        var $this = $(this),
            table_id = $this.attr("data-wcpt-table-id"),
            $container = $("#wcpt-" + table_id);

        $("body")
            .trigger("wcpt-nav-modal-off")
            .removeClass("wcpt-nav-modal-on");

        $this.remove();
        window.scroll(null, wcpt_nav_modal_scroll);
    });

    // row option change ev
    $("body").on("change", ".wcpt-options-row .wcpt-option input", function () {
        var $this = $(this),
            $label = $this.closest(".wcpt-option ");

        if ($this.is(":radio")) {
            if (this.checked) {
                $label
                    .addClass("wcpt-active")
                    .siblings()
                    .removeClass("wcpt-active");
            }
        } else {
            // checkbox
            if (this.checked) {
                $label.addClass("wcpt-active");
            } else {
                $label.removeClass("wcpt-active");
            }
        }
    });

    // toggle
    $("body").on("click", ".wcpt-tg-trigger", function () {
        var $this = $(this),
            $toggle = $this.closest(".wcpt-toggle"),
            $table = $this.closest(".wcpt-table");

        $toggle.toggleClass(" wcpt-tg-on wcpt-tg-off ");
    });

    $("body").on("click", ".wcpt-rn-filter, .wcpt-rn-sort", nav_modal);

    $("body").on("click", ".wcpt-accordion-heading", function () {
        $(this).closest(".wcpt-accordion").toggleClass("wcpt-open");
    });

    // apply filters
    $("body").on("click", ".wcpt-apply", function () {
        $(this).closest(".wcpt-navigation").trigger("change");
    });

    // photoswipe

    // -- open
    function init_photoswipe($this, index, append_item) {
        // photoswipe gallery
        if (
            typeof PhotoSwipe !== "undefined" &&
            typeof PhotoSwipeUI_Default !== "undefined"
        ) {
            var items = JSON.parse($this.attr("data-wcpt-photoswipe-items")),
                index =
                    typeof index == "undefined" || !index ? 0 : parseInt(index),
                color_theme =
                    $this.attr("data-wcpt-lightbox-color-theme") || "black";

            // append items
            if (append_item) {
                items.push(append_item);
            }

            var index_src = items[index].src;

            // remove duplicates
            var unique_src = [],
                _items = [];

            $.each(items, function (index2, item) {
                if (-1 === $.inArray(item.src, unique_src)) {
                    _items.push(item);
                    unique_src.push(item.src);
                }
            });
            items = _items;

            var options = JSON.parse(
                    $this.attr("data-wcpt-photoswipe-options")
                ),
                photoswipe = new PhotoSwipe(
                    $(".pswp")[0],
                    PhotoSwipeUI_Default,
                    items,
                    options
                );

            photoswipe.init();

            // add wcpt-photoswipe class and trigger selector attr
            var color_theme_class = "wcpt-photoswipe--theme-" + color_theme;
            $(".pswp").addClass("wcpt-photoswipe " + color_theme_class);

            // add wcpt class on photoswipe and trigger selector

            // reposition index in case item was deleted
            $.each(items, function (index3, item) {
                if (item.src === index_src) {
                    index = index3;
                }
            });

            photoswipe.goTo(index);

            var $body = $("body");

            $body.addClass("wcpt-photoswipe-visible");
            photoswipe.listen("close", function () {
                setTimeout(function () {
                    // fix photoswipe click through bug
                    $body.removeClass("wcpt-photoswipe-visible");
                }, 10);
            });

            // remove wcpt-photoswipe class and trigger selector attr after closing
            photoswipe.listen("destroy", function () {
                $(".pswp").removeClass("wcpt-photoswipe " + color_theme_class);
            });

            $(photoswipe.container).data("wcpt_photoswipe", photoswipe);

            return true;
        } else {
            return false;
        }
    }

    // -- close
    $("body").on("click", ".pswp__container", function (e) {
        var $this = $(this),
            photoswipe = $this.data("wcpt_photoswipe");

        if (window.innerWidth < 720 && photoswipe) {
            var $target = $(e.target);
            if (!$target.closest(".pswp__button").length) {
                photoswipe.close();
            }
        }
    });

    // gallery strip

    // -- image
    $("body").on("click", ".wcpt-gallery__item", function () {
        var $this = $(this),
            index = parseInt($this.attr("data-wcpt-gallery-item")),
            $gallery = $this.closest(".wcpt-gallery");

        if (!$gallery.hasClass("wcpt-gallery--include-featured")) {
            index += 1;
        }

        init_photoswipe($gallery, index);
    });

    // -- link
    $("body").on("click", ".wcpt-gallery a", function (e) {
        e.preventDefault();

        var $this = $(this),
            $gallery = $this.closest(".wcpt-gallery");

        init_photoswipe($gallery);
    });

    // image lightbox
    $("body").on("click", ".wcpt-lightbox-enabled", function () {
        destroy_offset_zoom_containers();

        var $this = $(this);
        if (
            $this
                .closest(".wcpt")
                .hasClass("wcpt-quick-view-trigger--product-image") &&
            !$this
                .closest(".wcpt-row")
                .hasClass("wcpt-quick-view-trigger__disabled-for-product")
        ) {
            return;
        }

        var index = 0,
            $row = get_product_rows($this),
            src = $this.attr("data-wcpt-lightbox"),
            pswp_items = JSON.parse($this.attr("data-wcpt-photoswipe-items")),
            append_item = false;

        if (
            $row.attr("data-wcpt-type") === "variable" &&
            $row.data("wcpt_variation_selected")
        ) {
            var variation = $row.data("wcpt_variation"),
                src = variation.image.full_src,
                found = false;

            $.each(pswp_items, function (_index, item) {
                if (item.src == src) {
                    index = _index;
                    found = true;
                    return false;
                }
            });

            if (!found) {
                append_item = {
                    src: variation.image.full_src,
                    w: variation.image.full_src_w,
                    h: variation.image.full_src_h,
                    title: variation.image.title
                };

                index = pswp_items.length;
            }
        } else {
            // get starting index
            if ($this.attr("data-wcpt-photoswipe-items")) {
                $.each(pswp_items, function (_index, item) {
                    if (item.src == src) {
                        index = _index;
                        return false;
                    }
                });
            }
        }

        if (!init_photoswipe($this, index, append_item)) {
            var $el = $(
                '<div class="wcpt-lightbox-screen"><div class="wcpt-lightbox-loader"></div><div class="wcpt-lightbox-close"></div><img class="wcpt-lightbox-image" src="' +
                    src +
                    '"></div>'
            );
            $("body").append($el);
            $el.on("click ", function () {
                $el.remove();
            });
        }
    });

    // image zoom
    //-- image hover
    $("body").on(
        "mouseenter",
        '.wcpt-zoom-enabled[data-wcpt-zoom-trigger="image_hover"]',
        function () {
            var $this = $(this),
                level = $this.attr("data-wcpt-zoom-level");
            if (!level) {
                level = "1.5";
            }

            if (
                $this.closest(".wcpt-device-tablet, .wcpt-device-phone").length
            ) {
                return;
            }

            $this.css({
                transform: "scale(" + level + ")",
                "z-index": "2"
            });

            $this.one("mouseleave", function () {
                $this.css({
                    transform: "",
                    "z-index": ""
                });
            });
        }
    );
    //-- row hover
    $("body").on("mouseenter", ".wcpt-row", function () {
        var $row = $(this);
        $row.find(
            '.wcpt-zoom-enabled[data-wcpt-zoom-trigger="row_hover"]'
        ).each(function () {
            var $zoom_me = $(this),
                level = $zoom_me.attr("data-wcpt-zoom-level");
            if (!level) {
                level = "1.5";
            }

            if (
                $zoom_me.closest(".wcpt-device-tablet, .wcpt-device-phone")
                    .length
            ) {
                return;
            }

            $zoom_me.css({
                transform: "scale(" + level + ")",
                "z-index": "2"
            });

            $row.one("mouseleave", function () {
                $zoom_me.css({
                    transform: "",
                    "z-index": ""
                });
            });
        });
    });

    // product image offset zoom
    // -- attach hover handler
    $("body").on(
        "mouseenter.wcpt_offset_zoom",
        ".wcpt-product-image-wrapper--offset-zoom-enabled, .wcpt-gallery--offset-zoom-enabled .wcpt-gallery__item-wrapper",
        function (e) {
            var $this = $(this),
                src = $this.attr("data-wcpt-offset-zoom-image-src"),
                $offset_zoom = $(
                    '<div class="wcpt-offset-zoom-container ' +
                        $this.attr("data-wcpt-offset-zoom-image-html-class") +
                        '"><img src="' +
                        src +
                        '" class="wcpt-offset-zoom-container__image" /></div>'
                ),
                $wcpt = $this.closest(".wcpt");

            if ($this.closest(".frzTbl--grab-and-scroll--grabbing").length) {
                return;
            }

            destroy_offset_zoom_containers();

            $wcpt
                .append($offset_zoom)
                .on("mousemove.wcpt_offset_zoom", function (e) {
                    position_offset_zoom_container(e, $offset_zoom, $this);
                });

            setTimeout(function () {
                $offset_zoom.addClass("wcpt-offset-zoom-container--fade-in");
            }, 50);

            $this.on("mouseleave", destroy_offset_zoom_containers);
        }
    );
    // -- turn off hover handler on touch screens
    $("body").on("touchstart", function () {
        $("body").off("mouseenter.wcpt_offset_zoom");
    });

    function position_offset_zoom_container(e, $offset_zoom, $trigger) {
        var left = e.originalEvent.clientX + 40,
            top = e.originalEvent.clientY,
            position = "right";

        $offset_zoom.css({
            left: left,
            top: top
        });

        var rect = $offset_zoom.get(0).getBoundingClientRect(),
            viewport_width =
                window.innerWidth || document.documentElement.clientWidth,
            viewport_height =
                window.innerHeight || document.documentElement.clientHeight;

        if (rect.right > viewport_width) {
            position = "left";
        }

        if (position == "left") {
            left =
                $trigger.get(0).getBoundingClientRect().left - 40 - rect.width;
        }

        if (rect.top < 0) {
            top = 0 + 0.25 * rect.height;
        } else if (rect.bottom > viewport_height) {
            top -= rect.bottom - viewport_height;
        }

        $offset_zoom.css({
            left: left,
            top: top
        });
    }

    function destroy_offset_zoom_containers() {
        $(".wcpt-offset-zoom-container").remove();
        $body.off("mousemove.wcpt_offset_zoom");
    }

    // uncheck variation radio
    $("body").on("click", ".wcpt-variation-radio", function (e) {
        var $this = $(this),
            $variation = $this.closest(".wcpt-select-variation"),
            $row = $this.closest(".wcpt-row");

        if (
            $variation.hasClass("wcpt-selected") &&
            window.navigator.userAgent.indexOf("Edge") == -1
        ) {
            $this.prop("checked", false);
            $this.change();

            $row.trigger("select_variation", {
                variation_id: false,
                complete_match: false,
                attributes: false,
                variation: false,
                variation_found: false,
                variation_selected: false,
                variation_available: false
            });
        }
    });

    // variation selected class toggle
    $("body").on("change", ".wcpt-variation-radio", function () {
        var $this = $(this),
            $others = $(
                '.wcpt-variation-radio[name="' + $(this).attr("name") + '"]'
            ).not($(this)),
            $variation = $this.closest(".wcpt-select-variation");

        if ($this.is(":checked")) {
            $variation.addClass("wcpt-selected");
        } else {
            $variation.removeClass("wcpt-selected");
        }

        $others
            .not(":checked")
            .closest(".wcpt-select-variation")
            .removeClass("wcpt-selected");
    });

    // select variation (main)
    //-- sync
    $("body").on(
        "select_variation",
        ".wcpt-product-type-variable",
        function (e, data) {
            var $row = get_product_rows($(this));

            // update dropdown
            var $variation_dropdown = $row.find(
                ".wcpt-select-variation-dropdown"
            );
            $variation_dropdown.val(data.variation_id ? data.variation_id : "");

            // update radio
            $row.find(
                '.wcpt-variation-radio[value="' + data.variation_id + '"]'
            ).prop("checked", true);

            // update form
            $row.find(".variations_form").each(function () {
                var $this = $(this);
                current_variation_id = $(".variation_id", $this).val();

                if (data.variation_id != current_variation_id) {
                    window.wcpt_form_reset_flag = true;
                    $(".reset_variations", $this).trigger(
                        "click.wc-variation-form"
                    );
                    window.wcpt_form_reset_flag = false;

                    // select variation in form
                    if (data.variation_id) {
                        $(".variations select", $this).each(function () {
                            var $this = $(this),
                                name = $this.attr("name");

                            if (typeof data.attributes[name] !== "undefined") {
                                $this.val(data.attributes[name]);
                            } else {
                                $this.val("");
                            }
                        });

                        $this.trigger("check_variations");
                    }
                }
            });

            // update row
            $row.data("wcpt_variation", data.variation);
            $row.data("wcpt_variation_id", data.variation_id);
            $row.data("wcpt_complete_match", data.complete_match);
            $row.data("wcpt_attributes", data.attributes);
            $row.data("wcpt_variation_found", data.variation_found);
            $row.data("wcpt_variation_selected", data.variation_selected);
            $row.data("wcpt_variation_available", data.variation_available);
            $row.data("wcpt_variation_qty", data.variation_qty);

            // update total
            update_row_total($row);
            update_table_add_selected_to_cart.call($row.get(0));

            // reset
            if (!data.variation_selected) {
                // -- add to cart buton
                var $button = $row.find('[data-wcpt-link-code^="cart"]');

                // -- -- no variation selected
                if (
                    $row.find(".wcpt-add-to-cart-wrapper").length || // cart form
                    $variation_dropdown.length || // select variation -- dropdown
                    $row.find(".wcpt-variation-radio").length // select variation -- radio
                ) {
                    disable_button($button, "wcpt-no-variation-selected");
                }

                // -- -- out of stock
                if ($row.hasClass("wcpt-all-variations-out-of-stock")) {
                    disable_button($button, "wcpt-all-variations-out-of-stock");
                } else {
                    enable_button($button, "wcpt-all-variations-out-of-stock");
                }

                // -- checkbox
                $row.first().trigger("_wcpt_checkbox_change", false);

                // -- qty input
                var $qty = $row.find(".wcpt-quantity input[type=number].qty");

                if ($qty.length) {
                    $qty.each(function () {
                        var $this = $(this),
                            inital_value = $this.attr(
                                "data-wcpt-initial-value"
                            ),
                            min = $this.attr("min") ? $this.attr("min") : 1,
                            value = "";

                        if (inital_value == "min") {
                            value = min;
                        } else if (inital_value === "0") {
                            value = 0;
                        } else {
                            value = "";
                        }

                        if (
                            inital_value === "min" &&
                            $this.attr("data-wcpt-reset-on-variation-change")
                        ) {
                            value = min;
                        }

                        $this.attr({
                            min: "",
                            max: "",
                            step: "",
                            value: value
                        });

                        $this.val(value);

                        limit_qty_controller($this.closest(".wcpt-quantity"));
                    });
                }

                // -- product image
                var $product_image_wrapper = $(
                        ".wcpt-product-image-wrapper",
                        $row
                    ),
                    $product_image = $(
                        ".wcpt-product-image-wrapper > img:not(.wcpt-product-image-on-hover)",
                        $row
                    ),
                    $original_row = wcpt_get_original_row($row);

                if ($product_image_wrapper.length) {
                    if (!$original_row.data("wcpt_default_image")) {
                        if ($product_image[0]) {
                            // lazy load fix
                            $original_row.data(
                                "wcpt_default_image",
                                $product_image[0].outerHTML
                            );
                        } else {
                            handle_product_image_lazy_load(
                                $product_image_wrapper
                            );
                        }
                    } else {
                        $product_image.replaceWith(
                            $original_row.data("wcpt_default_image")
                        );
                    }

                    if (
                        $product_image_wrapper.hasClass("wcpt-lightbox-enabled")
                    ) {
                        if (
                            !$product_image_wrapper.attr(
                                "data-wcpt-lightbox--original"
                            )
                        ) {
                            $product_image_wrapper.attr(
                                "data-wcpt-lightbox--original",
                                $product_image_wrapper.attr(
                                    "data-wcpt-lightbox"
                                )
                            );
                        } else {
                            $product_image_wrapper.attr(
                                "data-wcpt-lightbox",
                                $product_image_wrapper.attr(
                                    "data-wcpt-lightbox--original"
                                )
                            );
                        }
                    }

                    if (
                        $product_image_wrapper.hasClass(
                            "wcpt-product-image-wrapper--offset-zoom-enabled"
                        )
                    ) {
                        if (
                            !$product_image_wrapper.attr(
                                "data-wcpt-offset-zoom-image-src--original"
                            )
                        ) {
                            $product_image_wrapper.attr(
                                "data-wcpt-offset-zoom-image-src--original",
                                $product_image_wrapper.attr(
                                    "data-wcpt-offset-zoom-image-src"
                                )
                            );
                        } else {
                            $product_image_wrapper.attr(
                                "data-wcpt-offset-zoom-image-src",
                                $product_image_wrapper.attr(
                                    "data-wcpt-offset-zoom-image-src--original"
                                )
                            );
                        }
                    }
                }

                // -- sku
                $row.find(".wcpt-sku").each(function () {
                    var $sku = $(this),
                        sku = $sku.attr("data-wcpt-sku");
                    $sku.text(sku); // revert to variable product sku
                });

                // -- product id
                $row.find(".wcpt-product-id").each(function () {
                    var $product_id = $(this),
                        product_id = $product_id.attr("data-wcpt-product-id");
                    $product_id.text(product_id); // revert to variable product ID
                });

                // -- price

                // -- -- wcpt price element
                $row.filter(".wcpt-product-type-variable")
                    .find(".wcpt-price.wcpt-variable-switch")
                    .each(function () {
                        var $this = $(this),
                            id = $this.attr("data-wcpt-element-id"),
                            tpl = $this.attr("data-wcpt-variable-template"),
                            $html = $(
                                $(
                                    "[data-wcpt-element-id=" +
                                        id +
                                        "][data-wcpt-price-type=" +
                                        tpl +
                                        "]"
                                ).html()
                            ),
                            o = [
                                "highest-price",
                                "lowest-price",
                                "sale-price",
                                "regular-price"
                            ];

                        $.each(o, function (index, val) {
                            $(".wcpt-" + val + " .wcpt-amount", $html).text(
                                $this.attr("data-wcpt-" + val)
                            );
                        });

                        $this.html($html);

                        if (tpl == "sale") {
                            $this.addClass("wcpt-product-on-sale");
                        } else {
                            $this.removeClass("wcpt-product-on-sale");
                        }
                    });

                // -- -- default woocommerce template
                $row.filter(".wcpt-product-type-variable")
                    .find(".wcpt-variable-price-default-woocommerce-template")
                    .each(function () {
                        var $this = $(this);
                        $default = $(".wcpt-variable-switch__default", $this);
                        $default.show().next(".price").remove();
                    });

                // -- -- price attribute
                $row.attr("data-wcpt-price", "");

                // -- on sale
                $row.filter(".wcpt-product-type-variable")
                    .find(".wcpt-on-sale.wcpt-variable-switch")
                    .each(function () {
                        var $this = $(this);
                        $this.addClass("wcpt-hide");
                    });

                // -- availability
                $row.filter(".wcpt-product-type-variable")
                    .find(".wcpt-availability.wcpt-variable-switch")
                    .each(function () {
                        var $this = $(this),
                            id = $this.attr("data-wcpt-element-id"),
                            stock = $this.attr("data-wcpt-stock"),
                            message_tpl = $this.attr("data-wcpt-message_tpl"),
                            stock_class = $this.attr("data-wcpt-stock_class"),
                            message = $(
                                "[data-wcpt-element-id=" +
                                    id +
                                    '][data-wcpt-availability-message="' +
                                    message_tpl +
                                    '"]'
                            ).html();

                        $this
                            .html(
                                $(message)
                                    .find(".wcpt-stock-placeholder")
                                    .text(stock)
                            )
                            .removeClass(
                                "wcpt-in-stock wcpt-low-stock wcpt-out-of-stock wcpt-on-backorder"
                            )
                            .addClass(stock_class)
                            .hide();
                    });

                // -- stock
                $row.filter(".wcpt-product-type-variable")
                    .find(".wcpt-stock")
                    .each(function () {
                        var $this = $(this),
                            stock = $this.attr("data-wcpt-stock"),
                            rules = $this.attr("data-wcpt-stock-range-labels"),
                            parsed_rules =
                                !rules || rules == "{}"
                                    ? []
                                    : JSON.parse(rules),
                            label = stock;

                        var found_rule = false;

                        if (stock && parsed_rules.length) {
                            $.each(parsed_rules, function (index, rule) {
                                if (rule[0] <= stock && rule[1] >= stock) {
                                    label = rule[2];
                                    found_rule = true;
                                }
                            });
                        }

                        if (!found_rule && stock < 0) {
                            stock = "";
                            label = "";
                        }

                        $this.html((label + "").replace("[stock]", stock));
                    });

                // -- dimensions
                $row.filter(".wcpt-product-type-variable")
                    .find(".wcpt-dimensions")
                    .each(function () {
                        var $this = $(this);
                        $this.html($this.attr("data-wcpt-default-dimensions"));
                    });

                // -- custom field
                $row.filter(".wcpt-product-type-variable")
                    .find(".wcpt-custom-field.wcpt-variable-switch")
                    .each(function () {
                        var $this = $(this),
                            element_id = $this.attr("data-wcpt-element-id"),
                            product_id = $this
                                .closest(".wcpt-row")
                                .attr("data-wcpt-product-id"),
                            table_id = $this
                                .closest(".wcpt")
                                .attr("data-wcpt-table-id");

                        if (
                            "undefined" !==
                            typeof window[
                                "wcpt_" + table_id + "_variable_switch_cf"
                            ]
                        ) {
                            var cf_vals =
                                window[
                                    "wcpt_" + table_id + "_variable_switch_cf"
                                ];
                            if ("undefined" !== typeof cf_vals[element_id]) {
                                $this.html(cf_vals[element_id][product_id]);
                            }
                        }
                    });
            }

            if (!$.isEmptyObject(data.variation)) {
                // -- add to cart buton
                var $button = $row.find('[data-wcpt-link-code^="cart"]');

                // -- -- no variation selected
                enable_button($button, "wcpt-no-variation-selected");

                // -- -- out of stock
                if (data.variation.is_in_stock) {
                    enable_button($button, "wcpt-variation-out-of-stock");
                } else {
                    disable_button($button, "wcpt-variation-out-of-stock");
                }

                // -- qty input
                var $qty = $row.find(".wcpt-quantity input[type=number].qty");

                if ($qty.length) {
                    $qty.each(function () {
                        var $this = $(this),
                            $qty_wrapper = $this.closest(".wcpt-quantity"),
                            inital_value = $this.attr(
                                "data-wcpt-initial-value"
                            ),
                            min = data.variation.min_qty
                                ? parseFloat(data.variation.min_qty)
                                : 1,
                            max = data.variation.max_qty
                                ? parseFloat(data.variation.max_qty)
                                : "",
                            step = data.variation.step
                                ? parseFloat(data.variation.step)
                                : "",
                            value = "";

                        // validate val
                        var current_val = parseFloat($this.val());
                        if (current_val && current_val !== NaN) {
                            // neither empty, nor 0
                            if (current_val < min) {
                                value = min;
                            } else if (max && current_val > max) {
                                value = max;
                            } else {
                                value = current_val;
                            }
                        }

                        if (
                            inital_value === "min" &&
                            $this.attr("data-wcpt-reset-on-variation-change")
                        ) {
                            value = min;
                        }

                        var wcpt_min = min;

                        if (inital_value === "0") {
                            min = "0";
                        }

                        $this.attr({
                            value: value,
                            min: min,
                            "data-wcpt-min": wcpt_min,
                            max: max,
                            step: step
                        });

                        $this.val(value);

                        $.each(["min", "max", "step"], function (index, attr) {
                            $qty_wrapper
                                .find(
                                    ".wcpt-quantity-error-placeholder--" + attr
                                )
                                .text($this.attr(attr));
                        });

                        $this.trigger("change");
                    });
                }

                // -- qty select
                var $select = $row.find(
                    ".wcpt-quantity > select.wcpt-qty-select"
                );
                if ($select.length) {
                    // re-create select
                    var qty_label = $select.attr("data-wcpt-qty-label"),
                        max_qty = parseInt($select.attr("data-wcpt-max-qty")),
                        val = data.variation.min_qty,
                        options =
                            '<option value="' +
                            data.variation.min_qty +
                            '" selected="selected">' +
                            qty_label +
                            data.variation.min_qty +
                            "</option>";
                    if (data.variation.max_qty) {
                        max_qty = data.variation.max_qty;
                    }

                    while (val < max_qty) {
                        val += data.variation.step || 1;
                        options += "<option>" + val + "</option>";
                    }
                    $select.html(options);
                    $select.attr("min", data.variation.min_qty);
                }

                // -- product image
                var $product_image_wrapper = $(
                        ".wcpt-product-image-wrapper",
                        $row
                    ),
                    $product_image = $(
                        ".wcpt-product-image-wrapper > img:not(.wcpt-product-image-on-hover)",
                        $row
                    ),
                    $original_row = wcpt_get_original_row($row);

                if ($product_image[0]) {
                    if (!$original_row.data("wcpt_default_image")) {
                        $original_row.data(
                            "wcpt_default_image",
                            $product_image[0].outerHTML
                        );
                    }

                    if (
                        $product_image.length &&
                        data.variation.image &&
                        data.variation.image.src
                    ) {
                        $product_image.attr({
                            src: data.variation.image.src,
                            srcset: data.variation.image.srcset
                                ? data.variation.image.srcset
                                : ""
                        });

                        if (
                            $product_image_wrapper.hasClass(
                                "wcpt-lightbox-enabled"
                            )
                        ) {
                            if (
                                !$product_image_wrapper.attr(
                                    "data-wcpt-lightbox--original"
                                )
                            ) {
                                $product_image_wrapper.attr(
                                    "data-wcpt-lightbox--original",
                                    $product_image_wrapper.attr(
                                        "data-wcpt-lightbox"
                                    )
                                );
                            }

                            $product_image_wrapper.attr(
                                "data-wcpt-lightbox",
                                data.variation.image.full_src
                            );
                        }

                        if (
                            $product_image_wrapper.hasClass(
                                "wcpt-product-image-wrapper--offset-zoom-enabled"
                            )
                        ) {
                            if (
                                !$product_image_wrapper.attr(
                                    "data-wcpt-offset-zoom-image-src--original"
                                )
                            ) {
                                $product_image_wrapper.attr(
                                    "data-wcpt-offset-zoom-image-src--original",
                                    $product_image_wrapper.attr(
                                        "data-wcpt-offset-zoom-image-src"
                                    )
                                );
                            }

                            $product_image_wrapper.attr(
                                "data-wcpt-offset-zoom-image-src",
                                data.variation.image.full_src
                            );
                        }
                    }
                } else {
                    handle_product_image_lazy_load($product_image_wrapper);
                }

                // -- sku
                if (data.variation.sku) {
                    $row.find(".wcpt-sku").each(function () {
                        var $this = $(this);
                        if ($this.hasClass("wcpt-variable-switch")) {
                            $this.text(data.variation.sku);
                        }
                    });
                }

                // -- product id
                if (data.variation.variation_id) {
                    $row.find(".wcpt-product-id").each(function () {
                        var $this = $(this);
                        if ($this.hasClass("wcpt-variable-switch")) {
                            $this.text(data.variation.variation_id);
                        }
                    });
                }

                // -- price

                // -- -- wcpt price element
                $row.filter(".wcpt-product-type-variable")
                    .find(".wcpt-price.wcpt-variable-switch")
                    .each(function () {
                        var $this = $(this),
                            id = $this.attr("data-wcpt-element-id"),
                            tpl =
                                parseFloat(data.variation.display_price) <
                                parseFloat(data.variation.display_regular_price)
                                    ? "sale"
                                    : "regular",
                            $html = $(
                                $(
                                    "[data-wcpt-element-id=" +
                                        id +
                                        "][data-wcpt-price-type=" +
                                        tpl +
                                        "]"
                                ).html()
                            );

                        $html
                            .find(".wcpt-regular-price .wcpt-amount")
                            .text(data.variation.display_regular_price)
                            .end()
                            .find(".wcpt-sale-price .wcpt-amount")
                            .text(data.variation.display_price);
                        $this.html($html);

                        if (tpl == "sale") {
                            $this.addClass("wcpt-product-on-sale");
                        } else {
                            $this.removeClass("wcpt-product-on-sale");
                        }
                    });

                // -- -- default woocommerce template
                $row.filter(".wcpt-product-type-variable")
                    .find(".wcpt-variable-price-default-woocommerce-template")
                    .each(function () {
                        var $this = $(this);
                        $default = $(".wcpt-variable-switch__default", $this);

                        if (!data.variation.price_html) {
                            // single variation won't have price html
                            return;
                        }

                        $default
                            .hide()
                            .nextAll(".price")
                            .remove()
                            .end()
                            .after(data.variation.price_html);
                    });

                // -- -- price attribute
                $row.attr("data-wcpt-price", data.variation.display_price);

                // -- on sale
                $row.filter(".wcpt-product-type-variable")
                    .find(".wcpt-on-sale.wcpt-variable-switch")
                    .each(function () {
                        var $this = $(this),
                            precision = $this.attr("data-wcpt-precision"),
                            is_on_sale =
                                parseFloat(data.variation.display_price) <
                                parseFloat(
                                    data.variation.display_regular_price
                                ),
                            $price_diff = $this.find(
                                ".wcpt-on-sale__price-diff"
                            ),
                            $percent_diff = $this.find(
                                ".wcpt-on-sale__percent-diff"
                            );

                        if (is_on_sale) {
                            $this.removeClass("wcpt-hide");

                            price_diff =
                                data.variation.display_regular_price -
                                data.variation.display_price;
                            percent_diff = parseFloat(
                                (
                                    (price_diff /
                                        data.variation.display_regular_price) *
                                    100
                                ).toFixed(precision)
                            );

                            $price_diff.html(format_price(price_diff));
                            $percent_diff.text(percent_diff);
                        } else {
                            $this.addClass("wcpt-hide");
                        }
                    });

                // -- availability
                $row.filter(".wcpt-product-type-variable")
                    .find(".wcpt-availability.wcpt-variable-switch")
                    .each(function () {
                        var $this = $(this),
                            id = $this.attr("data-wcpt-element-id"),
                            message_tpl = "",
                            stock_class = "",
                            out_of_stock_message = $(
                                "[data-wcpt-element-id=" +
                                    id +
                                    '][data-wcpt-availability-message="out_of_stock_message"]'
                            ).html(),
                            low_stock_message = $(
                                "[data-wcpt-element-id=" +
                                    id +
                                    '][data-wcpt-availability-message="low_stock_message"]'
                            ).html(),
                            single_stock_message = $(
                                "[data-wcpt-element-id=" +
                                    id +
                                    '][data-wcpt-availability-message="single_stock_message"]'
                            ).html(),
                            in_stock_message = $(
                                "[data-wcpt-element-id=" +
                                    id +
                                    '][data-wcpt-availability-message="in_stock_message"]'
                            ).html(),
                            in_stock_managed_message = $(
                                "[data-wcpt-element-id=" +
                                    id +
                                    '][data-wcpt-availability-message="in_stock_managed_message"]'
                            ).html(),
                            on_backorder_message = $(
                                "[data-wcpt-element-id=" +
                                    id +
                                    '][data-wcpt-availability-message="on_backorder_message"]'
                            ).html(),
                            on_backorder_managed_message = $(
                                "[data-wcpt-element-id=" +
                                    id +
                                    '][data-wcpt-availability-message="on_backorder_managed_message"]'
                            ).html(),
                            low_stock_threshold = $this.attr(
                                "data-wcpt-low_stock_threshold"
                            );

                        $this.show();

                        if (!data.variation.is_in_stock) {
                            message_tpl = "out_of_stock_message";
                            stock_class = "wcpt-out-of-stock";
                        } else if (
                            ((data.variation.managing_stock &&
                                data.variation.is_on_backorder &&
                                data.variation
                                    .backorders_require_notification) ||
                                (!data.variation.managing_stock &&
                                    data.variation.is_on_backorder)) &&
                            on_backorder_message
                        ) {
                            message_tpl = "on_backorder_message";
                            stock_class = "wcpt-on-backorder";
                        } else if (data.variation.managing_stock) {
                            // in stock, managed
                            message_tpl = "in_stock_managed_message";
                            stock_class = "wcpt-in-stock";

                            if (!data.variation.backorders_allowed) {
                                if (
                                    // low stock
                                    data.variation.stock == 1 &&
                                    single_stock_message
                                ) {
                                    message_tpl = "single_stock_message";
                                    stock_class = "wcpt-low-stock";
                                } else if (
                                    // single stock
                                    low_stock_message &&
                                    low_stock_threshold &&
                                    data.variation.stock <= low_stock_threshold
                                ) {
                                    message_tpl = "low_stock_message";
                                    stock_class = "wcpt-low-stock";
                                }
                            } else if (
                                data.variation
                                    .backorders_require_notification &&
                                on_backorder_managed_message
                            ) {
                                // backorder allowed, managed stock, greater than 0
                                message_tpl = "on_backorder_managed_message";
                                stock_class = "wcpt-on-backorder";
                            } else if (data.variation.stock <= 0) {
                                // backorder allowed, managed stock, 0 or less
                                message_tpl = "on_backorder_message";
                                stock_class = "";
                                $this.hide();
                            } else {
                                // backorder allowed, managed stock, greater than 0
                                message_tpl = "in_stock_message";
                                stock_class = "wcpt-in-stock";
                            }
                        } else {
                            // in stock, not managed
                            message_tpl = "";
                            stock_class = "";

                            if (in_stock_message) {
                                message_tpl = "in_stock_message";
                                stock_class = "wcpt-in-stock";
                            }
                        }

                        var $message = $(
                            $(
                                "[data-wcpt-element-id=" +
                                    id +
                                    '][data-wcpt-availability-message="' +
                                    message_tpl +
                                    '"]'
                            ).html()
                        );

                        $this
                            .html(
                                $message
                                    .find(".wcpt-stock-placeholder")
                                    .text(
                                        data.variation.stock
                                            ? data.variation.stock
                                            : ""
                                    )
                                    .end()
                            )
                            .removeClass(
                                "wcpt-in-stock wcpt-low-stock wcpt-out-of-stock wcpt-on-backorder"
                            )
                            .addClass(stock_class);
                    });

                // -- stock
                $row.filter(".wcpt-product-type-variable")
                    .find(".wcpt-stock.wcpt-variable-switch")
                    .each(function () {
                        var $this = $(this),
                            stock = data.variation.stock,
                            rules = $this.attr("data-wcpt-stock-range-labels"),
                            parsed_rules =
                                !rules || rules == "{}"
                                    ? []
                                    : JSON.parse(rules),
                            label = stock;

                        if (
                            typeof label === "undefined" ||
                            label === null ||
                            label == NaN
                        ) {
                            $this.hide();
                            return;
                        } else {
                            $this.show();
                        }

                        var found_rule = false;
                        if (stock && parsed_rules.length) {
                            $.each(parsed_rules, function (index, rule) {
                                if (rule[0] <= stock && rule[1] >= stock) {
                                    label = rule[2];
                                    found_rule = true;
                                }
                            });
                        }

                        if (!found_rule && stock < 0) {
                            stock = "";
                            label = "";
                        }

                        $this.html((label + "").replace("[stock]", stock));
                    });

                // -- dimensions
                $row.filter(".wcpt-product-type-variable")
                    .find(".wcpt-dimensions.wcpt-variable-switch")
                    .each(function () {
                        var $this = $(this);
                        $this.html(data.variation.dimensions_html);
                    });

                // -- custom field
                $row.filter(".wcpt-product-type-variable")
                    .find(".wcpt-custom-field.wcpt-variable-switch")
                    .each(function () {
                        var $this = $(this),
                            element_id = $this.attr("data-wcpt-element-id"),
                            table_id = $this
                                .closest(".wcpt")
                                .attr("data-wcpt-table-id");

                        if (
                            "undefined" !==
                            typeof window[
                                "wcpt_" + table_id + "_variable_switch_cf"
                            ]
                        ) {
                            var cf_vals =
                                window[
                                    "wcpt_" + table_id + "_variable_switch_cf"
                                ];
                            if ("undefined" !== typeof cf_vals[element_id]) {
                                $this.html(
                                    cf_vals[element_id][
                                        data.variation.variation_id
                                    ]
                                );
                            }
                        }
                    });
            }
        }
    );

    //-- -- update from dropdown
    $("body").on("change", ".wcpt-select-variation-dropdown", function (e) {
        var $this = $(this),
            $selected = $this.find("option:selected"),
            $row = $this.closest(".wcpt-row");

        $row.trigger("select_variation", {
            variation_id: $this.val(),
            complete_match: $selected.hasClass("wcpt-complete_match"),
            attributes: $selected.attr("data-wcpt-attributes")
                ? JSON.parse($selected.attr("data-wcpt-attributes"))
                : "",
            variation: $selected.attr("data-wcpt-variation")
                ? JSON.parse($selected.attr("data-wcpt-variation"))
                : "",
            variation_found: !!$selected.attr("value"),
            variation_selected: !!$selected.attr("value"),
            variation_available:
                !$selected.is(":disabled") && !!$selected.attr("value")
        });
    });

    //-- -- update from radio
    $("body").on("change", ".wcpt-variation-radio", function (e) {
        var $this = $(this),
            $wrapper = $this.closest(".wcpt-select-variation"),
            $row = $this.closest(".wcpt-row");

        if ($this.is(":checked")) {
            $row.trigger("select_variation", {
                variation_id: $this.val(),
                complete_match: $wrapper.hasClass("wcpt-complete_match"),
                attributes: JSON.parse($wrapper.attr("data-wcpt-attributes")),
                variation: JSON.parse($wrapper.attr("data-wcpt-variation")),
                variation_found: true,
                variation_selected: true,
                variation_available: !$this.is(":disabled")
            });
        }
    });

    //-- -- update from form
    $("body").on(
        "woocommerce_variation_has_changed",
        ".wcpt-row .variations_form",
        function (e) {
            get_select_variation_from_cart_form($(this));
        }
    );

    function get_select_variation_from_cart_form($form) {
        if (window.wcpt_form_reset_flag) {
            // avoid infinite loop
            return;
        }

        var variations = JSON.parse($form.attr("data-product_variations")),
            $row = $form.closest(".wcpt-row"),
            $variation_id = $(".variation_id", $form),
            variation = {},
            attributes = {},
            selected_variation = $variation_id.val();

        $.each(variations, function (index, value) {
            if (parseInt(value["variation_id"]) == selected_variation) {
                variation = value;
                return false;
            }
        });

        var variation_selected = true;
        $(".variations select", $form).each(function () {
            var $this = $(this);
            attributes[$this.attr("name")] = $this.val();

            if (!$this.val()) {
                variation_selected = false;
            }
        });

        var variation_available = false;
        if (
            variation &&
            !$.isEmptyObject(variation) &&
            variation.is_purchasable &&
            variation.is_in_stock &&
            variation.variation_is_visible
        ) {
            variation_available = true;
        }

        $row.trigger("select_variation", {
            variation: variation,
            variation_id: selected_variation,
            complete_match: true,
            attributes: attributes,
            variation_found: !!selected_variation,
            variation_selected: variation_selected,
            variation_available: variation_available
        });
    }

    // prepare variation options
    function prep_variation_options($new_rows) {
        $($new_rows)
            .filter(".wcpt-product-type-variable")
            .each(function () {
                var $row = $(this),
                    $dropdown = $(".wcpt-select-variation-dropdown", $row),
                    $radio = $(".wcpt-variation-radio", $row),
                    $form = $(".variations_form", $row),
                    $options = $dropdown.add($radio).add($form);

                // flag availability of options in row
                if ($options.length) {
                    $row.data("wcpt_variation_ops", true);
                }

                // trigger 'select_variation'

                // cart form element
                if ($form.length) {
                    $form.each(function () {
                        var $form = $(this);

                        setTimeout(function () {
                            // need to match setTimeout in add-to-cart-variation.js that delays form init
                            // this init syncs the WC form with WCPT native controls including add-to-cart button
                            $form.find("select").first().change();
                        }, 200);
                    });

                    // select variation element

                    // -- dropdown
                } else if ($dropdown.length) {
                    $dropdown.trigger("change");

                    // -- radio
                } else if ($radio.length) {
                    $radio.filter(":checked").trigger("change");
                }
            });
    }

    // lazy loaded product image
    function handle_product_image_lazy_load($product_image_wrapper) {
        if (
            $product_image_wrapper.length &&
            !$product_image_wrapper.hasClass("wcpt-awaiting-image-lazy-load")
        ) {
            $product_image_wrapper.addClass("wcpt-awaiting-image-lazy-load");

            $product_image_wrapper[0].addEventListener(
                "load",
                function (event) {
                    if (
                        event.target.tagName === "IMG" &&
                        $product_image_wrapper.hasClass(
                            "wcpt-awaiting-image-lazy-load"
                        )
                    ) {
                        $product_image_wrapper.removeClass(
                            "wcpt-awaiting-image-lazy-load"
                        );

                        var $row = wcpt_get_original_row(
                                $product_image_wrapper.closest(".wcpt-row")
                            ),
                            data = $row.data(),
                            $product_image =
                                $product_image_wrapper.children("img");

                        $row.data(
                            "wcpt_default_image",
                            $product_image[0].outerHTML
                        );

                        if (
                            !$.isEmptyObject(data.wcpt_variation) &&
                            $product_image.length &&
                            data.wcpt_variation.image &&
                            data.wcpt_variation.image.src
                        ) {
                            $product_image.attr({
                                src: data.wcpt_variation.image.src,
                                srcset: data.wcpt_variation.image.srcset
                                    ? data.wcpt_variation.image.srcset
                                    : ""
                            });

                            if (
                                $product_image_wrapper.hasClass(
                                    "wcpt-lightbox-enabled"
                                )
                            ) {
                                $product_image_wrapper.attr(
                                    "data-wcpt-lightbox",
                                    data.wcpt_variation.image.full_src
                                );
                            }

                            if (
                                $product_image_wrapper.hasClass(
                                    "wcpt-product-image-wrapper--offset-zoom-enabled"
                                )
                            ) {
                                $product_image_wrapper.attr(
                                    "data-wcpt-offset-zoom-image-src",
                                    data.variation.image.full_src
                                );
                            }
                        }
                    }
                },
                true
            );
        }
    }

    // quantity controller

    // -- touchscreens - automatically move cursor to end of input
    $("body").on("touchend", ".wcpt-quantity .qty", function () {
        var $this = $(this),
            _ = this;
        if (!$this.is(":focus")) {
            $this.one("focus", function () {
                var val = _.value;
                _.value = "";
                setTimeout(function () {
                    _.value = val;
                }, 1);
            });
        }
    });

    // -- get rid of external influence
    if ($(".wcpt").length) {
        // Quantities and Units for WooCommerce breaks counter
        $(document).off("change", ".qty");
    }

    if ("ontouchstart" in document.documentElement) {
        var mousedown = "touchstart",
            mouseup = "touchend";
    } else {
        var mousedown = "mousedown",
            mouseup = "mouseup";
    }

    // -- controller mouseDown
    $("body").on(
        mousedown + (mousedown == "touchstart" ? " dblclick" : ""),
        ".wcpt-qty-controller:not(.wcpt-disabled)",
        function qty_controller_onMousedown(e) {
            $("body").addClass("wcpt-noselect--qty-increment");

            // prevent accidental zoom && extra click on phone
            if (e.type === "dblclick") {
                e.preventDefault();
                return;
            }

            var $this = $(this),
                $parent = $this.parent(),
                $qty = $parent.find(".qty"),
                min = $qty.attr("min") ? $qty.attr("min") : 0,
                max = $qty.attr("max") ? $qty.attr("max") : false,
                step = $qty.attr("step") ? $qty.attr("step") : 1,
                val = $qty.val() > -1 ? $qty.val() : min;

            if (!step % 1) {
                min = parseInt(min);
                max = parseInt(max);
                step = parseInt(step);
                val = parseInt(val);
            } else {
                min = parseFloat(min);
                max = parseFloat(max);
                step = parseFloat(step);
                val = parseFloat(val);
            }

            if (isNaN(val)) {
                val = 0;
            }

            if ($this.hasClass("wcpt-plus")) {
                $qty.val((val * 1e12 + step * 1e12) / 1e12).change();
            } else {
                // wcpt-minus
                var next = (val * 1e12 - step * 1e12) / 1e12;
                if (next < min) {
                    next = 0;
                }
                $qty.val(next).change();
            }

            // if( e.type !== 'dblclick' ){ // the stop events won't work for a dblclick
            var count = 0,
                clear = setInterval(function () {
                    count++;
                    if (count % 5 || count < 50) {
                        return;
                    }

                    if ($this.hasClass("wcpt-disabled")) {
                        return;
                    }

                    var val = $qty.val() ? $qty.val() : min;
                    if (!step % 1) {
                        val = parseInt(val);
                    } else {
                        val = parseFloat(val);
                    }

                    if ($this.hasClass("wcpt-plus")) {
                        $qty.val((val * 1e12 + step * 1e12) / 1e12).change();
                    } else {
                        // wcpt-minus
                        var next = (val * 1e12 - step * 1e12) / 1e12;
                        if (next < min) {
                            next = 0;
                        }
                        $qty.val(next).change();
                    }
                }, 10);

            $this.data("wcpt_clear", clear);

            // }

            // stop counter
            $this.one(mouseup + " mouseout", function () {
                var $this = $(this),
                    clear = $this.data("wcpt_clear");
                if (clear) {
                    clearInterval(clear);
                    $this.data("wcpt_clear", false);
                }

                // clear unnecesary selection
                $("body").removeClass("wcpt-noselect--qty-increment");
            });

            limit_qty_controller($parent);
        }
    );

    // -- validator
    $("body").on(
        "change",
        ".wcpt-quantity .qty",
        function qty_controller_validate(e, syncing) {
            var $this = $(this),
                min = $this.attr("min") ? parseFloat($this.attr("min")) : 0,
                max = $this.attr("max") ? parseFloat($this.attr("max")) : 1e12,
                initial = $this.attr("data-wcpt-initial-value"),
                step = $this.attr("step") ? parseFloat($this.attr("step")) : 1,
                val = parseFloat($this.val());

            // enforce initial
            if (!val) {
                if (initial === "min") {
                    $this.val(min);
                } else if (initial === "empty") {
                    $this.val("");
                } else if (initial === "0") {
                    $this.val("0");
                }
            }

            // enforce min
            if (val && val < min) {
                $this.val(min);
            }

            // enforce max
            if (val > max) {
                $this.val(max);
            }

            // enforce step
            if (val && (val * 1e12) % (step * 1e12)) {
                var _val = val * 1e12,
                    _step = step * 1e12,
                    new_val = (_val + _step - (val % step) * 1e12) / 1e12;

                $this.val(new_val);
            }

            if (!syncing) {
                $this.trigger("change", true);
            }

            limit_qty_controller($(this).parent());
        }
    );

    // -- click enter to add to cart
    $("body").on("keypress", ".wcpt-quantity .qty", function (e) {
        if (e.keyCode == 13) {
            var $rows = get_product_rows($(this));
            $rows
                .find('.wcpt-button[data-wcpt-link-code^="cart"]')
                .eq(0)
                .click();
        }
    });

    // -- toggle controllers
    function limit_qty_controller($qty_wrapper) {
        $qty_wrapper.each(function () {
            var $this = $(this),
                $minus = $this.children(".wcpt-minus"),
                $plus = $this.children(".wcpt-plus"),
                $qty = $this.find(".qty"),
                initial = $qty.attr("data-wcpt-initial-value"),
                min = $qty.attr("min") ? parseFloat($qty.attr("min")) : 1,
                max = $qty.attr("max") ? parseFloat($qty.attr("max")) : false,
                step = $qty.attr("step") ? parseFloat($qty.attr("step")) : 1,
                val = parseFloat($qty.val());

            if (!val || isNaN(val)) {
                val = 0;
            }

            if (-1 !== $.inArray(initial, ["empty", "0"])) {
                min = 0;
            }

            $minus.removeClass("wcpt-disabled");
            if (val - step < min) {
                $minus.addClass("wcpt-disabled");
            }

            $plus.removeClass("wcpt-disabled");
            if (false !== max && val + step > max) {
                $plus.addClass("wcpt-disabled");
            }
        });
    }

    // -- set to initial value
    $("body").on(
        "wcpt_after_every_load.wcpt_initial_qty_update",
        ".wcpt",
        function () {
            var $this__container = $(this),
                $target_qty_elms = $(
                    '.wcpt-quantity input[type="number"].qty',
                    wcpt_util.get_uninit_rows($this__container)
                );

            $target_qty_elms.each(function () {
                var $this__input = $(this),
                    initial_value = $this__input.attr(
                        "data-wcpt-initial-value"
                    );

                if (initial_value === "0") {
                    $this__input.val(0);
                } else if (initial_value === "empty") {
                    $this__input.val("");
                } else if (initial_value === "min") {
                    var min = $this__input.attr("min")
                        ? $this__input.attr("min")
                        : 1;
                    $this__input.val(min);
                }

                $this__input
                    .trigger("wcpt_updating_initial_quantity")
                    .trigger("change")
                    .trigger("wcpt_initial_quantity_updated");
            });
        }
    );

    // -- quantity error
    $("body").on("keyup change", ".wcpt-quantity .qty", function (e) {
        var $this = $(this),
            max = $this.attr("max") ? parseFloat($this.attr("max")) : 1e9,
            min = $this.attr("min") ? parseFloat($this.attr("min")) : 1,
            step = $this.attr("step") ? parseFloat($this.attr("step")) : 1,
            val = $this.val() ? parseFloat($this.val()) : 0,
            $wrapper = $this.closest(".wcpt-quantity");

        $wrapper.removeClass(
            "wcpt-quantity-error wcpt-quantity-error--min wcpt-quantity-error--max wcpt-quantity-error--step"
        );

        if (!val) {
            return;
        }

        if (val < min) {
            $wrapper.addClass("wcpt-quantity-error wcpt-quantity-error--min");
        } else if (val > max) {
            $wrapper.addClass("wcpt-quantity-error wcpt-quantity-error--max");
        } else if ((val * 1e12) % (step * 1e12)) {
            $wrapper.addClass("wcpt-quantity-error wcpt-quantity-error--step");
        }

        var $row = wcpt_get_sibling_rows($this.closest(".wcpt-row")),
            $button = $row.find('.wcpt-button[data-wcpt-link-code^="cart_"]');

        if ($wrapper.hasClass("wcpt-quantity-error")) {
            disable_button($button, "wcpt-quantity-input-error");
        } else {
            enable_button($button, "wcpt-quantity-input-error");
        }

        limit_qty_controller($wrapper);
    });

    // -- sync qty between sibling tables
    $("body").on(
        "change",
        ".wcpt-quantity input.qty, select.wcpt-qty-select",
        function (e, syncing) {
            var $input = $(this),
                $product_rows = get_product_rows($input),
                $siblings = $product_rows
                    .find("input.qty, select.wcpt-qty-select")
                    .not(this),
                val = $input.val();

            $siblings.val(val);

            if ($input.closest(".wcpt-add-to-cart-wrapper").length) {
                return;
            }

            var $wc_default_button = $(".add_to_cart_button", $product_rows);
            $wc_default_button.data("quantity", val);
            $wc_default_button.attr("data-quantity", val);

            if (!syncing) {
                syncing = true;
                $siblings.trigger("change", syncing);
            }
        }
    );

    if (window.innerWidth < 1200) {
        $("body").on("contextmenu", ".wcpt-noselect", function () {
            return false;
        });
    }

    // total
    $("body").on("keyup mouseup change", ".wcpt-quantity .qty", function () {
        update_row_total($(this).closest(".wcpt-row"));
    });

    // -- name your price: ensure qty if name your price is used
    $("body").on(
        "keyup change",
        ".wcpt .wcpt-name-your-price--input",
        function (e) {
            var $nyp = $(this),
                nyp_val = $nyp.val(),
                $rows = wcpt_get_sibling_rows($nyp.closest(".wcpt-row")),
                $qty = $(".wcpt-quantity input.qty", $rows),
                qty_val = $qty.val(),
                $cb = $(".wcpt-cart-checkbox ", $rows);

            nyp_validate($nyp);

            // added val - add qty
            if (nyp_val && !qty_val) {
                $qty.val($qty.attr("min"));

                // removed val - remove qty
            } else if (!nyp_val && qty_val) {
                var inital_val_type = $qty.attr("data-wcpt-nyp-initial-value"),
                    val = $qty.attr("min");

                if (inital_val_type === "empty") {
                    val = "";
                } else if (inital_val_type === "0") {
                    val = "0";
                }

                $qty.val(val);
            }

            update_row_total($(this).closest(".wcpt-row"));

            if ($qty.length) {
                $qty.change();
            } else if ($cb.length) {
                $rows.trigger("wcpt_checkbox_change", !!$nyp.val());
            }
        }
    );

    function update_row_total($row, force_qty) {
        var $rows = wcpt_get_sibling_rows($row),
            $qty = $rows.find(".qty").eq(0),
            qty = $qty.length ? parseFloat($qty.val() ? $qty.val() : 0) : 1,
            $total = $(".wcpt-total", $rows),
            $cb = $(".wcpt-cart-checkbox ", $rows),
            prev_total = parseFloat($total.attr("data-wcpt-in-cart-total")),
            price = $rows.attr("data-wcpt-price")
                ? parseFloat($rows.attr("data-wcpt-price"))
                : 0,
            total = 0;

        if (force_qty) {
            qty = force_qty;
        }

        // variable
        if ($rows.hasClass("wcpt-product-type-variable")) {
            if (
                $rows.data("wcpt_variation_found") &&
                $rows.data("wcpt_variation") &&
                $rows.data("wcpt_variation").display_price
            ) {
                price = unformat_price_figure(
                    $rows.data("wcpt_variation").display_price
                );
            } else {
                price = 0;
            }
        }

        // name your price
        if ($row.hasClass("wcpt-product-has-name-your-price")) {
            var $nyp = $(".wcpt-name-your-price--input", $rows);
            if ($nyp.filter(":visible").length) {
                // variable product compatibility
                price = $nyp.val() ? parseFloat($nyp.val()) : 0;
            }
        }

        total = (qty * price).toFixed(2);

        // if checkbox is unchecked and qty is not min then no total
        if (
            $cb.length &&
            !$rows.data("wcpt_checked") &&
            $qty.attr("data-wcpt-initial-value") !== "min"
        ) {
            total = 0;
        }

        $total.each(function () {
            var $this = $(this),
                _total = total;

            if ($this.hasClass("wcpt-total--include-total-in-cart")) {
                _total = _total + prev_total;
            }

            if (parseFloat(_total)) {
                $this
                    .removeClass("wcpt-total--empty")
                    .find(".wcpt-amount")
                    .text(format_price_figure(_total));
            } else {
                $this.addClass("wcpt-total--empty");
            }
        });

        $row.data("wcpt-total", total);
        $row.trigger("wcpt_total_updated");
    }

    // audio player
    $("body").on("click", ".wcpt-player__button", function () {
        var $button = $(this),
            $container = $button.closest(".wcpt-player"),
            src = $container.attr("data-wcpt-src"),
            loop = $container.attr("data-wcpt-loop"),
            $el = $container.data("wcpt-media-el");

        if (!$el) {
            $el = $('<audio class="wcpt-audio-elm" src="' + src + '"></audio>');
            $container.append($el);
            $container.data("wcpt-media-el", $el);

            if (loop) {
                $el.prop("loop", true);
            } else {
                $el.on("ended", function () {
                    $container.toggleClass("wcpt-player--playing");
                });
            }
        }

        if ($button.hasClass("wcpt-player__play-button")) {
            $el[0].play();

            if (!$container.hasClass("wcpt-media-loaded")) {
                $el.on("canplay", function () {
                    $container.addClass("wcpt-media-loaded");
                });
            }

            $("audio.wcpt-audio-elm")
                .not($el)
                .each(function () {
                    this.currentTime = 0;
                    this.pause();
                });

            // pause others
            var $other_players = $(".wcpt-player.wcpt-player--playing").not(
                $container
            );
            $other_players.find(".wcpt-player__pause-button").click();
        } else {
            $el[0].pause();
        }

        $container.toggleClass("wcpt-player--playing");
    });

    // term click
    // -- trigger filter
    $("body").on(
        "click",
        ".wcpt-trigger_filter > [data-wcpt-slug]",
        function () {
            var $this = $(this),
                slug = $this.attr("data-wcpt-slug"),
                taxonomy = $this.parent().attr("data-wcpt-taxonomy"),
                $container = $this.closest(".wcpt"),
                $nav = $container.find(".wcpt-navigation"),
                $option = $nav.find(
                    '[data-wcpt-taxonomy="' +
                        taxonomy +
                        '"] [data-wcpt-slug="' +
                        slug +
                        '"]'
                ),
                $input = $("input", $option);

            if (!$option.length) {
                return;
            }

            $nav.addClass("wcpt-force-hide-dropdown-menus");
            $input.prop("checked", !$input.prop("checked"));
            $nav.trigger("change");
        }
    );
    // -- archive redirect
    $("body").on(
        "click",
        ".wcpt-archive_redirect > [data-wcpt-slug]",
        function () {
            var $this = $(this),
                url = $this.attr("data-wcpt-archive-url");
            if (!$this.is("a")) {
                window.location = url;
            }
        }
    );

    // remove
    $("body").on(
        "click",
        ".wcpt-row:not(.wcpt-removing-product) .wcpt-remove:not(.wcpt-disabled)",
        function () {
            var $this = $(this),
                $row = ($product_row = get_product_rows($this)),
                product_id = $row.attr("data-wcpt-product-id"),
                variation_id = $row.attr("data-wcpt-variation-id"),
                params = {
                    payload: {
                        products: {},
                        variations: {},
                        overwrite_cart_qty: true
                    }
                };

            if ($this.hasClass("wcpt-refresh-enabled")) {
                params.redirect = window.location.href;
            }

            params.payload.products[product_id] = 0;

            // variation
            if (variation_id) {
                params.payload.variations[product_id] = {};
                params.payload.variations[product_id][variation_id] = 0;
            }

            // variable product
            if ($row.hasClass("wcpt-product-type-variable")) {
                params.payload.variations[product_id] = $.extend(
                    {},
                    wcpt_cart_result_cache.in_cart[product_id]
                );
                $.each(
                    params.payload.variations[product_id],
                    function (variation_id, qty) {
                        params.payload.variations[product_id][variation_id] = 0;
                    }
                );
            }

            wcpt_cart(params);
        }
    );

    // toggle content (show more / less)
    $("body").on("click", ".wcpt-toggle-trigger", function (e) {
        e.preventDefault();
        var $this = $(this);
        $this.closest(".wcpt-toggle-enabled").toggleClass("wcpt-toggle");
    });

    // freeze table selectors for originals
    function wcpt_get_container_original_table($container) {
        return $container
            .find(".wcpt-table:visible")
            .not(".frzTbl-clone-table");
    }

    // get sibling tables under freeze table
    function wcpt_get_sibling_tables($table) {
        var $freeze_table = $table.closest(".frzTbl");
        if (!$freeze_table.length) {
            return $table;
        }

        return $(".wcpt-table", $freeze_table);
    }

    // get sibling rows under freeze table
    window.wcpt_get_sibling_rows = function ($row) {
        var $freeze_table = $row.closest(".frzTbl");
        if (!$freeze_table.length) {
            return $row;
        }

        var product_id = $row.attr("data-wcpt-product-id"),
            variation_id = $row.attr("data-wcpt-variation-id"),
            row_selector;

        if (variation_id) {
            row_selector =
                '[data-wcpt-variation-id="' +
                variation_id +
                '"].wcpt-row.wcpt-product-type-variation';
        } else {
            row_selector =
                '[data-wcpt-product-id="' +
                product_id +
                '"].wcpt-row:not(.wcpt-product-type-variation)';
        }

        return $(row_selector, $freeze_table);
    };

    // get original row under freeze table
    function wcpt_get_original_row($row) {
        var $sibling_rows = wcpt_get_sibling_rows($row);

        $sibling_rows.each(function () {
            var $row = $(this);
            if (!$row.closest("table").hasClass("frzTbl-clone-table")) {
                $original = $row;
                return false;
            }
        });

        return $original;
    }

    // checkbox

    // -- $cb 'change' handler -> triggers '_wcpt_checkbox_change' on $row
    $("body").on("change", ".wcpt-cart-checkbox", function (e) {
        var $this = $(this),
            $row = $this.closest(".wcpt-row");

        $row.trigger("_wcpt_checkbox_change", $this.prop("checked"));
    });

    // -- $row '_wcpt_checkbox_change' handler -> triggers 'wcpt_checkbox_change' on original $row, sets same state on sibling $cb
    $("body").on("_wcpt_checkbox_change", ".wcpt-row", function (e, state) {
        var $this = $(this),
            $original_row = wcpt_get_original_row($this),
            $table = $original_row.closest("table"),
            $sibling_rows = wcpt_get_sibling_rows($this),
            $sibling_cbs = $(".wcpt-cart-checkbox", $sibling_rows);

        // unnecessary trigger
        if (!$sibling_cbs.length || !$sibling_cbs.not(":disabled").length) {
            return;
        }

        // $row .data()-> state
        $original_row.data("wcpt_checked", state);

        // $table .data()-> checked_rows
        var $table_checked_rows = $table.data("wcpt_checked_rows")
            ? $table.data("wcpt_checked_rows")
            : $();

        if (state) {
            $table_checked_rows = $table_checked_rows.add($original_row);
        } else {
            $table_checked_rows = $table_checked_rows.not($original_row);
        }

        $table.data("wcpt_checked_rows", $table_checked_rows);

        // publish event
        $original_row.trigger("wcpt_checkbox_change", state);
    });

    // -- 'wcpt_checkbox_change' handler -> set the same state on siblings rows' $cb and data on sibling rows
    $("body").on("wcpt_checkbox_change", ".wcpt-row", function (e, state) {
        var $this = $(this),
            $sibling_rows = wcpt_get_sibling_rows($this),
            $sibling_cbs = $(".wcpt-cart-checkbox", $sibling_rows);

        $sibling_rows.data("wcpt_checked", state);
        $sibling_cbs.prop("checked", state);
    });

    // -- row html class
    $("body").on("wcpt_checkbox_change", ".wcpt-row", function (e, state) {
        var $this = $(this),
            $original_row = wcpt_get_original_row($this),
            $sibling_rows = wcpt_get_sibling_rows($this),
            html_class = "wcpt-row--checked";

        if ($original_row.data("wcpt_checked")) {
            $sibling_rows.addClass(html_class);
        } else {
            $sibling_rows.removeClass(html_class);
        }
    });

    // -- select variation
    $("body").on("select_variation", ".wcpt-row", function (e) {
        var $this = $(this),
            selected_variation = $this.data("wcpt_variation"),
            $cb = $this.find(".wcpt-cart-checkbox");

        // reset
        $cb.removeAttr("disabled");
        $cb.removeClass("wcpt-cart-checkbox--disabled");

        if (
            !selected_variation ||
            $.isEmptyObject(selected_variation) ||
            (!selected_variation.is_in_stock &&
                !selected_variation.is_on_backorder)
        ) {
            $this.trigger("wcpt_checkbox_change", false);
            $cb.attr("disabled", true);
            $cb.addClass("wcpt-cart-checkbox--disabled");
        }
    });

    // -- qty
    // -- -- '.qty' change handler -> trigger '_wcpt_checkbox_change' on $row
    setTimeout(function () {
        // too many 3rd party modules trigger $qty at page load
        $("body").on(
            "change",
            ".wcpt input.qty, .wcpt select.wcpt-qty-select",
            function (e) {
                var $this = $(this),
                    $row = $this.closest(".wcpt-row"),
                    $original_row = wcpt_get_original_row($row);

                if (
                    $this.closest("form.cart").length ||
                    // don't permit auto-check when initial-val is min
                    ($this.attr("data-wcpt-initial-value") === "min" &&
                        !$original_row.data("wcpt_checked"))
                ) {
                    return;
                }

                $original_row.trigger(
                    "_wcpt_checkbox_change",
                    !!parseFloat($this.val())
                );
            }
        );
    }, 1);

    // -- -- raise qty to min or reduce to initial qty upon 'wcpt_checkbox_change'
    $("body").on("wcpt_checkbox_change", ".wcpt-row", function (e, state) {
        var $this = $(this),
            $sibling_rows = wcpt_get_sibling_rows($this),
            $sibling_qty = $(
                "input.qty, select.wcpt-qty-select",
                $sibling_rows
            ),
            $sibling_qty_wrappers = $(".wcpt-quantity", $sibling_rows);

        $sibling_qty.each(function () {
            var $this = $(this),
                val = $this.val(),
                min = $this.attr("data-wcpt-min")
                    ? parseFloat($this.attr("data-wcpt-min"))
                    : $this.attr("min"),
                initial_qty = $this.attr("data-wcpt-initial-value");

            if (state) {
                if (!val || val === "0" || isNaN(val)) {
                    val = min;
                }
            } else {
                if (initial_qty == "empty") {
                    val = "";
                } else if (initial_qty == "0") {
                    val = 0;
                } else {
                    val = min;
                }
            }

            $sibling_qty.val(val);
            limit_qty_controller($sibling_qty_wrappers);
        });
    });

    // -- -- update total upon 'wcpt_checkbox_change'
    $("body").on("wcpt_checkbox_change", ".wcpt-row", function (e, state) {
        var $this = $(this),
            $sibling_rows = wcpt_get_sibling_rows($this);

        update_row_total($sibling_rows);
    });

    // -- 'wcpt_checkbox_change' handler -> create / update / hide '.wcpt-cart-checkbox-trigger' button
    $("body").on(
        "wcpt_checkbox_change",
        ".wcpt-row",
        wcpt_checkbox_trigger_init
    );

    function wcpt_checkbox_trigger_init() {
        // setup $checkbox_trigger
        var $checkbox_trigger = $(".wcpt-cart-checkbox-trigger");

        if (!$checkbox_trigger.length) {
            var html = $("#tmpl-wcpt-cart-checkbox-trigger").html();
            $checkbox_trigger = $(html).appendTo("body");
        } else {
            $checkbox_trigger.removeClass("wcpt-hide");
        }

        var $checked_rows = $();

        $(".wcpt-table:visible").each(function () {
            var $this = $(this),
                $_checked_rows =
                    $this.data("wcpt_checked_rows") &&
                    $this.data("wcpt_checked_rows").length
                        ? $this.data("wcpt_checked_rows")
                        : $();
            $checked_rows = $checked_rows.add($_checked_rows);
        });

        // hide
        if (!$checked_rows.length) {
            $checkbox_trigger.hide();

            return;
        }

        // show & update count
        var qty = 0,
            cost = 0;
        $checked_rows.each(function () {
            var $this = $(this),
                $qty = $(".qty, .wcpt-qty-select", $this).first(),
                _qty = $qty.length ? parseFloat($qty.val()) : 1,
                _cost = $this.attr("data-wcpt-price");

            if ($this.attr("data-wcpt-type") == "variable") {
                if ($this.data("wcpt_variation")) {
                    _cost = $this.data("wcpt_variation").display_price;
                } else {
                    _cost = 0;
                }
            }

            if (!isNaN(_qty)) {
                qty = (_qty * 1e12 + qty * 1e12) / 1e12;
            }
            if (!isNaN(_cost) && !isNaN(_qty)) {
                cost = (_cost * _qty * 1e12 + cost * 1e12) / 1e12;
            }
        });

        $checkbox_trigger
            .data({
                wcpt_checked_rows: $checked_rows,
                wcpt_qty: qty,
                wcpt_cost: cost
            })
            .find(".wcpt-total-selected")
            .text(qty)
            .end()
            .find(".wcpt-total-selected-cost")
            .text(format_price_figure(cost));

        $checkbox_trigger
            .trigger("wcpt_checkbox_trigger_updating")
            .show()
            .trigger("wcpt_checkbox_trigger_updated");
    }

    // -- heading
    // -- -- check all $cb in table via $heading_cb '.wcpt-cart-checkbox-heading'
    $("body").on("click", ".wcpt-cart-checkbox-heading", function () {
        var $this = $(this),
            state = $this.prop("checked"),
            $container = $this.closest(".wcpt"),
            $table = wcpt_get_container_original_table($container),
            $rows = $(".wcpt-row", $table);

        $rows.trigger("_wcpt_checkbox_change", state);
        $("wcpt-cart-checkbox--last-clicked", $table).removeClass(
            "wcpt-cart-checkbox--last-clicked"
        );
    });

    // -- -- auto toggle heading checkbox
    $("body").on("wcpt_checkbox_change", ".wcpt-row", function (e, state) {
        // using setTimeout, bring down multiplee calls to just one
        clearTimeout(window.wcpt_cb_heading);
        window.wcpt_cb_heading = setTimeout(function () {
            $(".wcpt").each(function () {
                var $container = $(this),
                    $heading_cb = $container
                        .find(".wcpt-cart-checkbox-heading")
                        .filter(":visible"),
                    $cbs = $container.find(".wcpt-cart-checkbox"),
                    state = !$cbs
                        .filter(":visible")
                        .not(":disabled")
                        .not(":checked").length;
                $heading_cb.prop("checked", state);
            });
        }, 100);
    });

    // -- shift + click
    // -- -- record shift key
    $("body").on("keydown", function (e) {
        if (e.shiftKey) {
            wcpt_shiftKey = true;
            $("body").one("keyup", function (e) {
                wcpt_shiftKey = false;
            });
        }
    });

    // -- -- act upon $cb shift key selected by user
    $("body").on("change", ".wcpt-cart-checkbox", function (e, wcpt_sync) {
        if (wcpt_sync) {
            return false;
        }

        var $this = $(this),
            $table = $this.closest(".wcpt-table"),
            $cb = $(".wcpt-cart-checkbox", $table);
        ($last_clicked = $cb.filter(".wcpt-cart-checkbox--last-clicked")),
            (checked = $this.prop("checked"));

        $last_clicked.removeClass("wcpt-cart-checkbox--last-clicked");
        $this.addClass("wcpt-cart-checkbox--last-clicked");

        if (
            $last_clicked.length &&
            typeof wcpt_shiftKey !== "undefined" &&
            wcpt_shiftKey
        ) {
            var min = Math.min($cb.index($this), $cb.index($last_clicked)),
                max = Math.max($cb.index($this), $cb.index($last_clicked));

            $cb.filter(function () {
                var $this = $(this),
                    index = $cb.index($this);

                if ($this.prop("disabled")) {
                    return false;
                }

                if (index >= min && index <= max) {
                    return true;
                } else {
                    return false;
                }
            })
                .prop("checked", checked)
                .trigger("change", true);
        }
    });

    // -- select / clear all
    $("body").on(
        "click",
        ".wcpt-add-selected__select-all, .wcpt-add-selected__clear-all",
        function (e) {
            var $this = $(this),
                state = !!$this.hasClass("wcpt-add-selected__select-all");
            ($container = $this.closest(".wcpt")),
                ($table = wcpt_get_container_original_table($container)),
                ($rows = $(".wcpt-row", $table));

            $rows.trigger("_wcpt_checkbox_change", state);
            $("wcpt-cart-checkbox--last-clicked", $table).removeClass(
                "wcpt-cart-checkbox--last-clicked"
            );
        }
    );

    // -- -- toggle the buttons
    $("body").on(
        "wcpt_checkbox_change",
        ".wcpt-row",
        update_table_add_selected_to_cart
    );

    function update_table_add_selected_to_cart() {
        var _ = this;
        setTimeout(function () {
            var $this = $(_),
                $container = $this.closest(".wcpt"),
                $table = wcpt_get_container_original_table($container),
                $checked_rows = $table.data("wcpt_checked_rows")
                    ? $table.data("wcpt_checked_rows")
                    : $();
            $add_checked = $(".wcpt-add-selected:visible", $container);

            $add_checked.removeClass(
                "wcpt-add-selected--unselected wcpt-add-selected--single-item-selected"
            );

            if ($checked_rows.length) {
                var qty = 0,
                    cost = 0;

                $checked_rows.each(function () {
                    var $this = $(this),
                        $qty = $(".qty, .wcpt-qty-select", $this).first(),
                        val = $qty.length ? parseFloat($qty.val()) : 1;
                    if (!isNaN(val)) {
                        qty = (val * 1e12 + qty * 1e12) / 1e12;
                    }

                    if (!$this.data("wcpt-total")) {
                        update_row_total(wcpt_get_sibling_rows($this));
                    }

                    var product_total = $this.data("wcpt-total");

                    cost = (cost * 1e12 + product_total * 1e12) / 1e12;
                });

                $(".wcpt-total-selected", $add_checked).text(qty);
                $(".wcpt-total-selected-cost .wcpt-amount", $add_checked).text(
                    format_price_figure(cost)
                );

                if (qty == 1) {
                    $add_checked.addClass(
                        "wcpt-add-selected--single-item-selected"
                    );
                }
            } else {
                $add_checked.addClass("wcpt-add-selected--unselected");
            }
        }, 100);
    }

    // price decimal
    function format_price_figure(price) {
        price = parseFloat(price);

        // remove decimal if it is unnecessary ie .00
        if (price !== parseInt(price)) {
            price = parseFloat(price).toFixed(wcpt_params.price_decimals);
        } else {
            price = parseInt(price);
        }

        // decimal separator
        price = (price + "").replace(".", wcpt_params.price_decimal_separator);

        // thousands separator
        var decimal_split = price.split(wcpt_params.price_decimal_separator);
        (non_decimal = decimal_split[0].toString()),
            (decimal = decimal_split[1]);

        if (non_decimal.length > 3) {
            formatted_non_decimal =
                non_decimal.slice(0, -3) +
                wcpt_params.price_thousand_separator +
                non_decimal.slice(-3);
            price =
                formatted_non_decimal +
                (decimal ? wcpt_params.price_decimal_separator + decimal : "");
        }

        return price;
    }

    function unformat_price_figure(price) {
        price = price.toString();

        // remove thousands separator
        price = price.replace(wcpt_params.price_thousand_separator, "");

        // replace decimal separator
        price = price.replace(wcpt_params.price_decimal_separator, ".");

        return parseFloat(price);
    }

    // follow wc settings for price display
    function format_price(num) {
        if (!num && num !== "0" && num !== 0) {
            return "";
        }

        return wcpt_params.price_format
            .replace("%1$s", wcpt_params.currency_symbol)
            .replace("%2$s", format_price_figure(num));
    }

    // -- -- duplicate the buttons under table as well
    function duplicate_select_all($container) {
        var $add_checked = wcpt_get_container_element(
                ".wcpt-add-selected.wcpt-duplicate-enabled:visible",
                $container
            ),
            $pagination = wcpt_get_container_element(
                ".wcpt-pagination.wcpt-device-laptop",
                $container
            );

        if (
            $add_checked.length &&
            !$pagination.prev(".wcpt-add-selected").length
        ) {
            $pagination.before(function () {
                var $clone = $add_checked.clone();
                $clone.addClass("wcpt-add-selected--footer wcpt-in-footer");
                if ($add_checked.closest(".wcpt-right").length) {
                    $clone.addClass("wcpt-laptop__text-align--right");
                }
                return $clone;
            });
        }
    }

    // -- add to cart
    $("body").on(
        "click",
        ".wcpt-cart-checkbox-trigger, .wcpt-cart-checkbox-trigger--local",
        wcpt_cart_checkbox
    );
    function wcpt_cart_checkbox() {
        var $this = $(this),
            products = {},
            variations = {},
            attributes = {},
            addons = {},
            measurement = {},
            nyp = {}, // name your price
            $checked_rows = $(),
            $table = $();

        if ($this.hasClass("wcpt-cart-checkbox-trigger")) {
            // global
            var $container = $(".wcpt");

            $container.each(function () {
                var $this = $(this);
                $table = $table.add(wcpt_get_container_original_table($this));
            });

            $this.addClass("wcpt-hide");
        } else {
            // local
            var $container = $this.closest(".wcpt");
            $table = wcpt_get_container_original_table($container);
        }

        $table.each(function () {
            var $this = $(this);
            $table_checked_rows = $this.data("wcpt_checked_rows")
                ? $this.data("wcpt_checked_rows")
                : $();

            $checked_rows = $checked_rows.add($table_checked_rows);
        });

        $checked_rows.each(function () {
            var $row = $(this),
                product_id = $row.attr("data-wcpt-product-id"),
                variation_id = false,
                variation_attributes = false,
                $qty = $(".qty, .wcpt-qty-select", $row).first(),
                val = parseFloat($qty.length ? $qty.val() : 1);
            if (isNaN(val)) {
                val = 0;
            }

            if (typeof products[product_id] === "undefined") {
                products[product_id] = val;
            } else {
                products[product_id] += val; // variation
            }

            // variable
            if ($row.hasClass("wcpt-product-type-variable")) {
                var data = $row.data();

                if (
                    data.wcpt_variation_selected &&
                    data.wcpt_variation_available &&
                    data.wcpt_complete_match &&
                    data.wcpt_variation_id
                ) {
                    variation_id = data.wcpt_variation_id;

                    if (data.wcpt_attributes) {
                        variation_attributes = data.wcpt_attributes;
                    }
                }

                // variation
            } else if ($row.hasClass("wcpt-product-type-variation")) {
                variation_id = $row.attr("data-wcpt-variation-id");
                variation_attributes = JSON.parse(
                    $row.attr("data-wcpt-variation-attributes")
                );
            }

            if (variation_id) {
                if (!variations[product_id]) {
                    variations[product_id] = {};
                }

                if (!variations[product_id][variation_id]) {
                    variations[product_id][variation_id] = val;
                }

                if (variation_attributes) {
                    attributes[variation_id] = variation_attributes;
                }
            }

            // addons
            if ($row.hasClass("wcpt-product-has-addons")) {
                addons[product_id] = wcpt_get_addons($row);
            }

            // measurement
            if ($row.hasClass("wcpt-product-has-measurement")) {
                measurement[product_id] = get_measurement($row);
            }

            // name your price
            if ($row.hasClass("wcpt-product-has-name-your-price")) {
                nyp[product_id] = get_nyp($row);
            }
        });

        // uncheck before wcpt_cart else $total will be reset
        $checked_rows.trigger("_wcpt_checkbox_change", false);

        var payload = {
            products: products,
            addons: addons,
            measurement: measurement,
            variations: variations,
            attributes: attributes,
            nyp: nyp
        };

        wcpt_cart({
            payload: payload,
            redirect: $this.attr("data-wcpt-redirect-url")
        });
    }

    // addons
    window.wcpt_get_addons = function ($row) {
        var $form = $(
                ".wcpt-add-to-cart-wrapper form",
                wcpt_get_sibling_rows($row)
            ),
            addons = {};

        // WooCommerce Custom Product Addons
        var $wcpa_fields = $form.find(".wcpa_form_outer");
        if ($wcpa_fields.length) {
            var $fields = $wcpa_fields,
                $_form = $("<form>");

            $_form
                .append($fields.clone())
                .find("select")
                .each(function () {
                    // cloned select needs correct value
                    var $this = $(this),
                        name = $this.attr("name"),
                        value = $fields
                            .find('[name="' + name + '"]select')
                            .val();
                    $this.val(value);
                });

            $.each($_form.serializeArray(), function (i, field) {
                var field_name = field.name,
                    suffix_index = field_name.indexOf("--wcpt");

                if (-1 !== suffix_index) {
                    // must be checkbox or radio
                    field_name = field_name.substring(0, suffix_index);

                    if (field.name.slice(-1) == "]") {
                        // checkbox
                        if (!addons[field_name]) {
                            addons[field_name] = [];
                        }
                        addons[field_name].push(field.value);
                        return;
                    } else {
                        // radio
                        addons[field_name] = field.value;
                    }
                } else {
                    // other field types
                    addons[field_name] = field.value;
                }
            });

            // official WooCommerce Product Addons
        } else {
            $.each($form.serializeArray(), function (i, field) {
                var field_name = field.name;

                if (field_name.slice(-2) == "[]") {
                    field_name = field_name.substring(0, field_name.length - 2);
                }

                if (typeof addons[field_name] === "undefined") {
                    addons[field_name] = field.value; // add prop
                } else {
                    if (typeof addons[field_name] !== "object") {
                        // make it an array
                        addons[field_name] = [addons[field_name]];
                    }
                    addons[field_name].push(field.value); // update prop
                }
            });
        }

        return addons;
    };

    // measurement
    function get_measurement($row) {
        var $price_calculator = $(
                ".wcpt-add-to-cart-wrapper form #price_calculator",
                wcpt_get_sibling_rows($row)
            ),
            measurement = {};

        $("input", $price_calculator).each(function () {
            var $this = $(this);
            measurement[$this.attr("name")] = $this.val();
        });

        return measurement;
    }

    // name your price
    function get_nyp($row) {
        var $nyp = get_nyp_input_element($row),
            val = 0;

        if ($nyp.length) {
            val = $nyp.val();
        }

        return val;
    }

    function get_nyp_input_element($row) {
        return $(".wcpt-name-your-price--input", wcpt_get_sibling_rows($row));
    }

    // search through filter options
    $("body").on("wcpt_after_every_load", ".wcpt", function () {
        // @TODO - infinite load optimization
        var $this = $(this),
            $nav_dropdown = $(".wcpt-dropdown.wcpt-filter", $this);

        $nav_dropdown.each(function () {
            var $this = $(this);
            if (
                $this.hasClass("wcpt-filter--search-filter-options-enabled") &&
                !$(".wcpt-search-filter-options", $this).length
            ) {
                var $menu = $(".wcpt-dropdown-menu", $this),
                    placeholder = $this.attr(
                        "data-wcpt-search-filter-options-placeholder"
                    );

                $menu.prepend(
                    '<input type="search" class="wcpt-search-filter-options" placeholder="' +
                        placeholder +
                        '" />'
                );

                $(".wcpt-search-filter-options", $menu)
                    .nextAll()
                    .wrapAll(
                        $(
                            '<div class="wcpt-search-filter-option-set" style="max-height: ' +
                                $menu.css("max-height") +
                                ';"></div>'
                        )
                    );

                $menu.css("max-height", "none");
            }
        });
    });

    $("body").on("keyup input", ".wcpt-search-filter-options", function (e) {
        var $this = $(this),
            val = $this.val().toLowerCase().trim(),
            $filter = $this.closest(".wcpt-filter"),
            $option = $(".wcpt-dropdown-option", $filter);

        e.preventDefault();

        if (!val) {
            $option.show();

            $(".wcpt-ac-open", $filter).removeClass("wcpt-ac-open");

            $("input[type=radio], input[type=checkbox]", $option).each(
                function () {
                    var $this = $(this);
                    if ($this.is(":checked")) {
                        $this
                            .closest(".wcpt-dropdown-option.wcpt-accordion")
                            .addClass("wcpt-ac-open");
                    }
                }
            );
            return;
        }

        $option.each(function () {
            var $this = $(this),
                label = $this.text().toLowerCase().trim();
            if (label.indexOf(val) == -1) {
                $this.hide();
            } else {
                $this.show();
                $this
                    .closest(".wcpt-dropdown-option.wcpt-accordion")
                    .addClass("wcpt-ac-open");
            }
        });
    });

    // global product search shortcode
    $("body").on(
        "change keydown keyup",
        ".wcpt-global-search__keyword-input",
        function () {
            var $this = $(this),
                $search = $this.closest(".wcpt-global-search");

            if ($this.val()) {
                $search.removeClass("wcpt-global-search--empty");
            } else {
                $search.addClass("wcpt-global-search--empty");
            }
        }
    );

    $(".wcpt-global-search__keyword-input").trigger("change");

    // -- facade
    $("body").on(
        "change",
        ".wcpt-global-search__category-selector",
        function () {
            var $this = $(this),
                value = $this.val(),
                $option = $('option[value="' + value + '"]', $this),
                text = $option.text().trim(),
                $facade = $this.siblings(
                    ".wcpt-global-search__category-selector-facade"
                );

            $(
                ".wcpt-global-search__category-selector-facade__text",
                $facade
            ).text(text);
        }
    );

    // -- redirect empty
    $("body").on("submit", ".wcpt-global-search__form", function (e) {
        var $this = $(this),
            keyword = $this.find(".wcpt-global-search__keyword-input").val(),
            $select = $this.find(".wcpt-global-search__category-selector"),
            clear_redirect_url = $this.attr("data-wcpt-clear-redirect-url"),
            clear_redirect = $this.attr("data-wcpt-clear-redirect"),
            redirect = $this.attr("data-wcpt-redirect"),
            category = $(".wcpt-global-search__category-selector", $this).val(),
            action = $this.attr("action");

        // select 'All' category
        if (!keyword) {
            if (clear_redirect !== "category") {
                $select.val("").change();
            }

            // redirect
            if (clear_redirect_url) {
                e.preventDefault();
                window.location = clear_redirect_url;
            }
        } else if (redirect == "category" && category) {
            e.preventDefault();
            window.location =
                wcpt_product_category_links[category] + "?s=" + keyword;
        } else if (redirect == "shop") {
            e.preventDefault();
            var url = action + "?s=" + keyword;
            if (category) {
                url += "&wcpt_search_category=" + category;
            }
            window.location = url;
        } else if (redirect == "search") {
            e.preventDefault();
            var url = action + "?s=" + keyword + "&post_type=product";
            if (category) {
                url += "&wcpt_search_category=" + category;
            }
            window.location = url;
        }
    });

    // -- clear
    $("body").on("click", ".wcpt-global-search__clear", function () {
        var $this = $(this),
            $input = $this.siblings(".wcpt-global-search__keyword-input"),
            $form = $this.closest(".wcpt-global-search__form");
        $input.val("");
        $form.submit();
    });

    // -- focus / blur
    $("body")
        .on("focus", ".wcpt-global-search__keyword-input", function () {
            var $this = $(this),
                $wrapper = $this.parent();
            $wrapper.addClass(
                "wcpt-global-search__keyword-input-wrapper--focus"
            );
        })
        .on("blur", ".wcpt-global-search__keyword-input", function () {
            var $this = $(this),
                $wrapper = $this.parent();
            $wrapper.removeClass(
                "wcpt-global-search__keyword-input-wrapper--focus"
            );
        });

    // cart

    window.wcpt_cart = function (params) {
        if (!window.wcpt_cart_call_id) {
            window.wcpt_cart_call_id = 1;
        } else {
            ++window.wcpt_cart_call_id;
        }

        var _params = {
            payload: {
                wcpt_cart_call_id: window.wcpt_cart_call_id
            },
            before: false,
            always: false,
            redirect: false,
            external_payload: {}
        };
        params = $.extend({}, _params, params ? params : {});

        params.payload.wcpt_cart_call_id = window.wcpt_cart_call_id;
        params.payload.cart_widget_permitted = cart_widget_permitted();

        // view

        $(".wcpt-cart-widget").addClass("wcpt-cart-widget--loading"); // cart widget

        // product req data used by view
        var product_request = {
            products: params.payload.products
                ? $.extend({}, params.payload.products)
                : {},
            variations: params.payload.variations
                ? $.extend({}, params.payload.variations)
                : {}
        };

        if (
            // add in variation modal request data so it may be used by the view
            typeof params.payload !== "undefined" &&
            params.payload.variation_form &&
            typeof params.external_payload !== "undefined" &&
            params.external_payload["add-to-cart"]
        ) {
            var product_id = params.external_payload["add-to-cart"],
                variation_id = params.external_payload["variation_id"]
                    ? params.external_payload["variation_id"]
                    : "",
                quantity = parseFloat(params.external_payload["quantity"]);

            product_request.products[product_id] = quantity;

            if (variation_id) {
                product_request.variations[product_id] = {};
                product_request.variations[product_id][variation_id] = quantity;
            }
        }

        $(".wcpt-row:visible").each(function () {
            var $row = $(this),
                $sibling_rows = wcpt_get_sibling_rows($row),
                product_id = $row.attr("data-wcpt-product-id"),
                variation_id = $row.attr("data-wcpt-variation-id"),
                $button = $(cart_button_selector, $row),
                in_cart = parseFloat($row.attr("data-wcpt-in-cart"));

            // before request, update view like - add loading badge, animate Remove el
            if (product_request.products) {
                $.each(product_request.products, function (id, qty) {
                    if (id == product_id) {
                        if (
                            // product is a variation, and not part of the cart request
                            variation_id &&
                            product_request.variations &&
                            product_request.variations[product_id] &&
                            typeof product_request.variations[product_id][
                                variation_id
                            ] == "undefined"
                        ) {
                            return;
                        }

                        loading_badge_on_button($button);

                        if (0 === qty && in_cart) {
                            // removing
                            $row.addClass("wcpt-removing-product");
                        } else {
                            // adding
                            $row.addClass("wcpt-adding-product");

                            var $qty = $("input.qty", $row),
                                $wcpt_qty = $qty.not(".cart .qty"),
                                initial = $wcpt_qty.attr(
                                    "data-wcpt-initial-value"
                                ),
                                min = parseFloat(
                                    $wcpt_qty.attr("data-wcpt-min")
                                ),
                                return_to_initial = $wcpt_qty.attr(
                                    "data-wcpt-return-to-initial"
                                );

                            if (return_to_initial) {
                                $sibling_rows.trigger(
                                    "_wcpt_checkbox_change",
                                    false
                                );

                                if (initial == "min") {
                                    $qty.val(min);
                                } else if (initial === "0") {
                                    $qty.val(0);
                                } else if (initial === "empty") {
                                    $qty.val("");
                                }
                            }

                            var $wrapper = $qty.closest(".wcpt-quantity");
                            if ($wrapper.length) {
                                limit_qty_controller($wrapper);
                            }

                            // dropdown
                            var $qty = $("select.wcpt-qty-select", $row),
                                $first = $qty.children("option:first-child");

                            $qty.val($first.attr("value"));
                        }

                        // total
                        var $total = $(".wcpt-total", $sibling_rows),
                            force_total_qty = qty;

                        if (
                            variation_id &&
                            product_request.variations &&
                            product_request.variations[product_id] &&
                            product_request.variations[product_id][variation_id]
                        ) {
                            force_total_qty =
                                product_request.variations[product_id][
                                    variation_id
                                ];
                        }

                        // don't allow it to be reset by qty going to 0
                        if ($total.length) {
                            update_row_total($sibling_rows, force_total_qty);
                        }
                    }
                });
            }
        });

        // if( params.before ){
        //   params.before();
        // }

        var data = $.extend(
            {},
            {
                // action: 'wcpt_cart',
                wcpt_payload: params.payload,
                lang: wcpt_i18n.lang
            },
            params.external_payload ? params.external_payload : {}
        );

        $("body").trigger("wcpt_cart_request", data);

        // use cache
        if (params.payload.use_cache && window.wcpt_cart_result_cache) {
            window.wcpt_cart_result_cache.payload.wcpt_cart_call_id =
                window.wcpt_cart_call_id;
            $("body").trigger("wcpt_cart", window.wcpt_cart_result_cache);

            // fetch fresh
        } else {
            $.post(
                wcpt_params.wc_ajax_url.replace("%%endpoint%%", "wcpt_cart"),
                data,
                function (result) {
                    window.wcpt_cart_result_cache = $.extend({}, result, {
                        success: true,
                        notice: "",
                        is_cache: true
                    });
                    $("body").trigger("wcpt_cart", result);
                }
            ).always(function (result) {
                if (params.always) {
                    params.always(result);
                }

                if (params.redirect) {
                    if (
                        // redirect after user reads error
                        !result.success &&
                        result.notice
                    ) {
                        $("body").on(
                            "click touchstart",
                            ".wcpt-notice-wrapper",
                            function () {
                                window.location = params.redirect;
                            }
                        );
                    } else {
                        // redirect immediately
                        window.location = params.redirect;
                    }
                }
            });
        }
    };

    $("body").on("wcpt_cart", function (e, result) {
        // cart widget
        if (result.payload.wcpt_cart_call_id === window.wcpt_cart_call_id) {
            // latest call resolved
            $(".wcpt-cart-widget ").removeClass("wcpt-cart-widget--loading");
        }

        // error
        if (!result.success && result.notice) {
            var $body = $("body"),
                $notice = $(
                    '<div class="wcpt-notice-wrapper">' +
                        result.notice +
                        "</div>"
                );
            $body.append($notice);
            $body.one("click", function () {
                $notice.remove();
            });
        }

        if (result.cart_widget && cart_widget_permitted()) {
            var $body = $("body"),
                $old = $(".wcpt-cart-widget").not(
                    ".wcpt-cart-checkbox-trigger"
                ),
                $new = $(result.cart_widget);

            if ($new.hasClass("wcpt-hide")) {
                $body.removeClass("wcpt-cart-widget-visible");
            } else {
                $body.addClass("wcpt-cart-widget-visible");
            }

            $body.append($new);
            $old.remove();
        }

        // added / removed
        var added = (removed = false);

        if (result.payload.products && result.in_cart) {
            $.each(result.payload.products, function (product_id, product_qty) {
                var variation_id = (variation_qty = false);

                if (
                    result.payload.variations &&
                    typeof result.payload.variations[product_id] !== "undefined"
                ) {
                    variation_id = Object.keys(
                        result.payload.variations[product_id]
                    )[0];
                    variation_qty = Object.values(
                        result.payload.variations[product_id]
                    )[0];
                }

                if (product_qty === "0") {
                    // req. type: "remove"
                    if (
                        $.isEmptyObject(result.in_cart) ||
                        !result.in_cart.length ||
                        !result.in_cart[product_id] ||
                        (variation_id &&
                            !result.in_cart[product_id][variation_id])
                    ) {
                        removed = true;
                    }
                } else {
                    // req. type: "add"
                    if (
                        !$.isEmptyObject(result.in_cart) &&
                        result.in_cart[product_id] &&
                        (!variation_id ||
                            result.in_cart[product_id][variation_id])
                    ) {
                        added = true;
                    }
                }
            });
        }

        if (result.payload.variation_form) {
            added = true;
        }

        var in_cart_products = [];

        if (result.in_cart) {
            $.each(result.in_cart, function (key, val) {
                if (typeof val === "object") {
                    // variation
                    var qty = 0,
                        total = 0;

                    // qty
                    $.each(val, function (key2, val2) {
                        var _total = result.in_cart_total[key][key2];

                        // discreet entries for variations
                        in_cart_products.push({
                            id: key2,
                            type: "variation",
                            quantity: val2,
                            total: _total
                        });

                        qty += parseFloat(val2);
                        total += parseFloat(_total);
                    });

                    in_cart_products.push({
                        id: key,
                        type: "variable",
                        quantity: qty,
                        total: total
                    });
                } else {
                    // simple
                    in_cart_products.push({
                        id: key,
                        type: "simple",
                        quantity: val,
                        total: result.in_cart_total[key]
                    });
                }
            });
        }

        // view

        var $rows = $(".wcpt-row");

        $rows.each(function () {
            var $row = $(this),
                type = $row.attr("data-wcpt-type");
            (product_id = $row.attr("data-wcpt-product-id")),
                (variation_id = $row.attr("data-wcpt-variation-id")),
                (id =
                    type == "variation"
                        ? $row.attr("data-wcpt-variation-id")
                        : $row.attr("data-wcpt-product-id")),
                (cart_item = false),
                (min_call_id = $row.data("wcpt-min-cart-call-id"));

            if (min_call_id > result.payload.wcpt_cart_call_id) {
                return;
            }

            $.each(in_cart_products, function (key, item) {
                if (
                    (variation_id && variation_id == item.id) ||
                    (!variation_id && product_id == item.id)
                ) {
                    cart_item = item;
                    return false;
                }
            });

            // wcpt initiated adding / removing process is complete
            $row.removeClass("wcpt-adding-product wcpt-removing-product");

            // update 'in cart' qty
            var cart_qty = cart_item ? cart_item.quantity : 0;
            $row.attr("data-wcpt-in-cart", cart_qty);
            $in_cart = $(".wcpt-in-cart", $row).text(cart_qty);
            $in_cart.each(function () {
                var $this = $(this),
                    template = $this.attr("data-wcpt-template");

                $this.text(template.replace("{n}", cart_qty));
            });

            // update 'in cart' total
            var cart_total = cart_item ? cart_item.total : 0;
            $(".wcpt-total", $row).attr("data-wcpt-in-cart-total", cart_total);
            update_row_total($row);

            // -- enable
            if (cart_qty) {
                $in_cart.removeClass("wcpt-disabled");
                // -- disable
            } else {
                $in_cart.addClass("wcpt-disabled");
            }

            // badge

            if (!$row.hasClass("wcpt-adding-product")) {
                $button = $(cart_button_selector, $row);
                add_count_badge_to_button(cart_qty, $button);

                if (!$button.hasClass("wcpt-out-of-stock")) {
                    enable_button($button);
                }
            }

            // remove

            if (!$row.hasClass("wcpt-removing-product")) {
                var $remove = $(".wcpt-remove", $row);

                // -- enable
                if (cart_item) {
                    $remove.removeClass("wcpt-disabled");

                    // -- disable
                } else {
                    // $remove.addClass('wcpt-disabled').removeClass('wcpt-removing');
                    $remove.addClass("wcpt-disabled");

                    // wc button
                    $(".add_to_cart_button", $row)
                        .removeClass("added")
                        .next(".added_to_cart")
                        .remove();
                }
            }
        });

        if (
            result.fragments &&
            !result.payload.skip_cart_triggers &&
            !result.is_cache
        ) {
            var trigger_lock = true,
                $body = $("body"),
                $button = false;

            // fake button for single add / remove
            if (
                result.payload &&
                result.payload.products &&
                Object.keys(result.payload.products).length === 1
            ) {
                $.each(result.payload.products, function (product_id, qty) {
                    if (qty) {
                        // added
                        $button = $(
                            '<button data-product_id="' + product_id + '">'
                        );
                    } else {
                        // removed
                        $button = $(
                            '<a href="" data-product_id="' + product_id + '">'
                        );
                    }
                });
            }

            if (added || result.payload.force_refresh_cart) {
                $body.trigger("added_to_cart", [
                    result.fragments,
                    result.cart_hash,
                    $button,
                    trigger_lock
                ]);
            }

            if (removed) {
                $body.trigger("removed_from_cart", [
                    result.fragments,
                    result.cart_hash,
                    $button,
                    trigger_lock
                ]);
            }
        }
    });

    $("body").on(
        "added_to_cart removed_from_cart",
        function (e, fragment, cart_hash, $button, trigger_lock) {
            if (!trigger_lock) {
                wcpt_cart({
                    payload: { skip_cart_triggers: true }
                });
            }
        }
    );

    // PRO

    // table heading offset based on nav header
    $("body").on("wcpt_layout", ".wcpt", function (e, data) {
        var $this = $(this),
            $freeze_nav_sidebar = $(
                ".wcpt-navigation.wcpt-left-sidebar.wcpt-sticky:visible",
                $this
            ),
            $freeze_nav_header = $(
                ".wcpt-navigation.wcpt-header.wcpt-sticky:visible",
                $this
            ),
            $freeze_table_heading = $(
                ".frzTbl-fixed-heading-wrapper-outer:visible",
                $this
            ),
            sc_attrs = $this.data("wcpt-sc-attrs"),
            device = get_device(),
            offset = parseInt(
                sc_attrs[device + "_scroll_offset"]
                    ? sc_attrs[device + "_scroll_offset"]
                    : 0
            );

        $freeze_nav_sidebar.css("top", offset);

        if (device == "laptop") {
            $freeze_nav_header.css("top", offset);

            offset += parseInt($freeze_nav_header.outerHeight());

            $freeze_table_heading.css({ top: offset });
        } else {
            offset += parseInt($freeze_nav_sidebar.outerHeight());
            $freeze_nav_header.css({ top: offset });

            offset += $freeze_nav_header.outerHeight();
            $freeze_table_heading.css({ top: offset });
        }
    });

    // dynamic filters
    function dynamic_filters_lazy_load($container) {
        // dynamic recount / hide filters not enabled
        if (
            !$container.data("wcpt_sc_attrs").dynamic_filters_lazy_load ||
            (!$container.data("wcpt_sc_attrs").dynamic_recount &&
                !$container.data("wcpt_sc_attrs").dynamic_hide_filters)
        ) {
            return;
        }

        var key = $container.attr("data-wcpt--dynamic-filters-lazy-load--key"),
            _options = $container.attr(
                "data-wcpt--dynamic-filters-lazy-load--filter-options"
            );

        if (!key || !_options) {
            return;
        }

        var options = JSON.parse(_options),
            filters = [
                "category",
                "attribute",
                "availability",
                "on_sale",
                "taxonomy"
            ];

        $(".wcpt-filter", $container).each(function () {
            var $this = $(this),
                filter = $this.attr("data-wcpt-filter");

            if (-1 !== $.inArray(filter, filters)) {
                dynamic_filters_lazy_load__fetch($this, key, options);
            }
        });
    }

    function dynamic_filters_lazy_load__fetch($filter, key, options) {
        var filter = $filter.attr("data-wcpt-filter"),
            taxonomy = $filter.attr("data-wcpt-taxonomy"),
            filter_options = [],
            $container = $filter.closest(".wcpt");

        $.each(options, function (i, option) {
            if (
                filter == option["filter"] &&
                (filter !== "attribute" || taxonomy == option["taxonomy"])
            ) {
                filter_options.push(option);
            }
        });

        $.ajax({
            url: wcpt_params.wc_ajax_url.replace(
                "%%endpoint%%",
                "wcpt__dynamic_filter__lazy_load"
            ),
            method: "GET",
            data: {
                wcpt__dynamic_filter__key: key,
                wcpt__dynamic_filter__options: filter_options
            },
            success: function (result) {
                var result = JSON.parse(result),
                    $style = $("style#" + key, $container),
                    $script = $("script#" + key, $container);

                // append style and script
                if (!$style.length) {
                    $style = $('<style id="' + key + '"></style>').prependTo(
                        $container
                    );
                }
                $style.append($(result.style).html());

                if (!$script.length) {
                    $script = $('<script id="' + key + '"></script>').prependTo(
                        $container
                    );
                }
                $script.append($(result.style).html());

                // add count
                $filter = $filter.add(
                    '.wcpt-nav-modal .wcpt-filter[data-wcpt-filter="' +
                        filter +
                        '"]'
                );
                if (taxonomy) {
                    $filter = $filter.filter(
                        '[data-wcpt-taxonomy="' + taxonomy + '"]'
                    );
                }

                var add_count = false;
                sc_attrs = $container.attr("data-wcpt-sc-attrs");

                if (sc_attrs && sc_attrs.length > 2) {
                    sc_attrs = JSON.parse(sc_attrs);

                    if (sc_attrs.dynamic_recount) {
                        add_count = true;
                    }
                }

                if (add_count) {
                    $.each(result.options, function (i, option) {
                        var count =
                                '<span class="wcpt-count">(' +
                                option.count +
                                ")</span>",
                            $label = $(
                                'label[data-wcpt-value="' + option.value + '"]',
                                $filter
                            ),
                            $icon = $label.children(".wcpt-icon"),
                            $prev_count = $(".wcpt-count", $label);

                        $prev_count.remove(); // in case duplicate taxonomy filter is printed

                        if ($icon.length) {
                            $icon.before(count);
                        } else {
                            $label.append(count);
                        }
                    });
                }

                $filter.removeClass("wcpt--dynamic-filters--loading-filter");
            }
        });
    }

    // cart widget permission
    function cart_widget_permitted() {
        var url = window.location.href.split("?")[0],
            include_match = false,
            exclude_match = false,
            include_urls = false,
            exclude_urls = false,
            site_url = wcpt_params.site_url + "/";

        if (!wcpt_params.cart_widget_enabled_site_wide && !$(".wcpt").length) {
            return false;
        }

        if (wcpt_params.cart_widget_include_urls.trim()) {
            include_urls = wcpt_params.cart_widget_include_urls
                .trim()
                .replace(/\r\n/g, "\n")
                .split("\n");
        }

        if (wcpt_params.cart_widget_exclude_urls.trim()) {
            exclude_urls = wcpt_params.cart_widget_exclude_urls
                .trim()
                .replace(/\r\n/g, "\n")
                .split("\n");
        }

        if (include_urls.length) {
            include_match = false;
            $.each(include_urls, function (i, path) {
                path = path.trim();

                if (path.indexOf(site_url) === 0) {
                    path = path.substring(site_url.length, path.length);
                }

                if (path.trim() == "/") {
                    if (url === site_url) {
                        include_match = true;
                    }
                    return;
                }

                if (path.trim() == "/*") {
                    include_match = true;
                    return;
                }

                path = path.replace(/(^\s*\/)|(\/\s*$)/g, ""); // ensure no slash at start or end
                if ("*" === path.substr(-1)) {
                    var remove = 1;
                    if ("/*" === path.substr(-2)) {
                        remove = 2;
                    }
                    if (
                        -1 !==
                        url.indexOf(
                            site_url +
                                path.substring(0, path.length - remove) +
                                "/"
                        )
                    ) {
                        include_match = true;
                    }
                } else {
                    path += "/";
                    if (url == site_url + path) {
                        include_match = true;
                    }
                }
            });

            if (!include_match) {
                return false;
            }
        }

        if (exclude_urls.length) {
            exclude_match = false;

            $.each(exclude_urls, function (i, path) {
                path = path.trim();

                if (path.indexOf(site_url) === 0) {
                    path = path.substring(site_url.length, path.length);
                }

                if (path.trim() == "/") {
                    if (url === site_url) {
                        exclude_match = true;
                    }
                    return;
                }

                if (path.trim() == "/*") {
                    exclude_match = true;
                    return;
                }

                path = path.replace(/(^\s*\/)|(\/\s*$)/g, ""); // ensure no slash at start or end
                if ("*" === path.substr(-1)) {
                    var remove = 1;
                    if ("/*" === path.substr(-2)) {
                        remove = 2;
                    }

                    if (
                        -1 !==
                        url.indexOf(
                            site_url +
                                path.substring(0, path.length - remove) +
                                "/"
                        )
                    ) {
                        exclude_match = true;
                    }
                } else {
                    path += "/";
                    if (url == site_url + path) {
                        exclude_match = true;
                    }
                }
            });

            if (exclude_match) {
                return false;
            }
        }

        return true;
    }

    // cart widget close x
    $("body").on("click", ".wcpt-cart-checkbox-trigger__close", function (e) {
        e.stopPropagation();
        var $this = $(this),
            $cart_widget = $this.closest(".wcpt-cart-checkbox-trigger");
        $(".wcpt-cart-checkbox:checked").click();
        $cart_widget.remove();
    });

    // multirange

    // -- input[type=number] change
    $("body").on(
        "input change",
        ".wcpt-range-input-min, .wcpt-range-input-max",
        function () {
            var $this = $(this),
                $container = $this.closest(".wcpt-range-options-main"),
                $min = $container.find(".wcpt-range-input-min"),
                min_val = parseFloat($min.val()),
                $max = $container.find(".wcpt-range-input-max"),
                max_val = parseFloat($max.val());

            $range = $container.find(".wcpt-range-slider.original");

            if (!$range.length) {
                return;
            }

            // max step fix
            var actual_max = parseFloat($this.attr("data-wcpt-actual-max")),
                val = parseFloat($this.val());

            if (!isNaN(actual_max) && !isNaN(val) && val > actual_max) {
                $this.val(actual_max);
            }

            $range.val($min.val() + "," + $max.val());
        }
    );

    // -- input[type=range] change
    $("body").on("input change", ".wcpt-range-slider", function () {
        var $this = $(this),
            $container = $this.closest(".wcpt-range-options-main"),
            $min = $(".wcpt-range-input-min", $container),
            $max = $(".wcpt-range-input-max", $container),
            $range_original = $container.find(".wcpt-range-slider.original"),
            $range_ghost = $container.find(".wcpt-range-slider.ghost"),
            min = parseFloat($range_original.prop("valueLow")),
            max = parseFloat($range_original.prop("valueHigh")),
            permitted_max = parseFloat($this.attr("max")),
            step = parseFloat($this.attr("step"));

        // range input max fix
        // if( max + step > permitted_max ){
        //   max = permitted_max; // sending this to input[type="number"]
        // }

        $min.val(min);
        $max.val(max);
    });

    // variation switch
    $("body").on(
        "select_variation",
        ".wcpt-product-type-variable",
        function (e, data) {
            var $row = get_product_rows($(this)),
                $items = $(".wcpt-variation-description__item", $row);

            $items.hide();

            if ($row.data("wcpt_variation_selected")) {
                var variation_id = $row.data("wcpt_variation_id");
                $items
                    .filter("[data-wcpt-variation-id=" + variation_id + "]")
                    .show();
            }
        }
    );

    // name your price
    $("body").on(
        "select_variation",
        ".wcpt-product-type-variable",
        function (e, data) {
            var $row = get_product_rows($(this)),
                $input = $(".wcpt-name-your-price--input", $row),
                initial_value_field = $input.attr(
                    "data-wcpt-nyp-initial-value-field"
                ),
                input = false,
                min = false,
                max = false,
                suggested = false;

            // if price has no variable switch, always stays "$min - $max" then just replace it completely with "from $_min"
            // if it has variable switch
            // then hide it whenever selected variation has nyp

            $row.removeClass("wcpt-product-has-name-your-price");

            if ($row.data("wcpt_variation_selected")) {
                // variation selected
                var variation = $row.data("wcpt_variation");

                if (variation.is_nyp) {
                    input = true;
                    min = variation.minimum_price;
                    max = variation.maximum_price;
                    suggested = variation.suggested_price;

                    $row.addClass("wcpt-product-has-name-your-price");
                }
            } else {
                // variation not selected
                input = false;
            }

            // no nyp input field
            if (!input) {
                $input.parent().addClass("wcpt-hide");
                $row.find("wcpt-price.wcpt-variable-switch").removeClass(
                    "wcpt-hide--name-your-price"
                );

                // found nyp input field
            } else {
                $input.parent().removeClass("wcpt-hide");

                if (min) {
                    $input.attr({
                        min: min,
                        "data-wcpt-nyp-minimum-price": min
                    });
                } else {
                    $input.attr({
                        min: 0,
                        "data-wcpt-nyp-minimum-price": 0
                    });
                }

                if (max) {
                    $input.attr("max", max);

                    $input.attr({
                        max: max,
                        "data-wcpt-nyp-maximum-price": max
                    });
                } else {
                    $input.attr({
                        max: "",
                        "data-wcpt-nyp-maximum-price": ""
                    });
                }

                if (initial_value_field && !$input.val()) {
                    var value = variation[initial_value_field + "_price"]
                        ? variation[initial_value_field + "_price"]
                        : "";
                    $input.val(value);
                }

                $row.find("wcpt-price.wcpt-variable-switch").addClass(
                    "wcpt-hide--name-your-price"
                );
            }

            if (!min) {
                $row.find(".wcpt-name-your-price--minimum").addClass(
                    "wcpt-hide"
                );
            } else {
                $row.find(".wcpt-name-your-price--minimum").removeClass(
                    "wcpt-hide"
                );
                $row.find(".wcpt-name-your-price--minimum .wcpt-amount").text(
                    min
                );
            }

            if (!max) {
                $row.find(".wcpt-name-your-price--maximum").addClass(
                    "wcpt-hide"
                );
            } else {
                $row.find(".wcpt-name-your-price--maximum").removeClass(
                    "wcpt-hide"
                );
                $row.find(".wcpt-name-your-price--maximum .wcpt-amount").text(
                    max
                );
            }

            if (!suggested) {
                $row.find(".wcpt-name-your-price--suggested").addClass(
                    "wcpt-hide"
                );
            } else {
                $row.find(".wcpt-name-your-price--suggested").removeClass(
                    "wcpt-hide"
                );
                $row.find(".wcpt-name-your-price--suggested .wcpt-amount").text(
                    suggested
                );
            }

            $input.change();
        }
    );

    // -- checkbox
    $("body").on("wcpt_checkbox_change", ".wcpt-row", function (e, checked) {
        var $this = $(this),
            $rows = wcpt_get_sibling_rows($this.closest(".wcpt-row")),
            $nyp = get_nyp_input_element($rows);

        if (checked) {
            nyp_validate($nyp);
        } else if (!$nyp.val()) {
            nyp_hide_error($nyp);
        }
    });

    // -- validate
    function nyp_validate($nyp) {
        var $nyp_wrapper = $nyp.parent(),
            nyp_val = $nyp.val(),
            nyp_min = $nyp.attr("data-wcpt-nyp-minimum-price"),
            nyp_max = $nyp.attr("data-wcpt-nyp-maximum-price"),
            message_template = "",
            $error = "",
            $row = $nyp.closest(".wcpt-row"),
            checked = $row.data("wcpt_checked");

        if (
            (nyp_val && nyp_min && parseFloat(nyp_val) < parseFloat(nyp_min)) ||
            (checked && !nyp_val && nyp_min)
        ) {
            $nyp_wrapper.addClass(
                "wcpt-name-your-price-wrapper--input-error wcpt-name-your-price-wrapper--input-error--min-price"
            );

            $error = $(
                ".wcpt-name-your-price-input-error-message--min-price",
                $nyp_wrapper
            );
            message_template = $error.attr("data-wcpt-error-message-template");
            $error.text(
                message_template.replace("[min]", format_price_figure(nyp_min))
            );
        } else if (
            nyp_val &&
            nyp_max &&
            parseFloat(nyp_val) > parseFloat(nyp_max)
        ) {
            $nyp_wrapper.addClass(
                "wcpt-name-your-price-wrapper--input-error wcpt-name-your-price-wrapper--input-error--max-price"
            );

            $error = $(
                ".wcpt-name-your-price-input-error-message--max-price",
                $nyp_wrapper
            );
            message_template = $error.attr("data-wcpt-error-message-template");
            $error.text(
                message_template.replace("[max]", format_price_figure(nyp_max))
            );
        } else {
            $nyp_wrapper.removeClass(
                "wcpt-name-your-price-wrapper--input-error wcpt-name-your-price-wrapper--input-error--min-price wcpt-name-your-price-wrapper--input-error--max-price"
            );
        }
    }

    // -- hide validation errors
    function nyp_hide_error($nyp) {
        $nyp.parent().removeClass(
            "wcpt-name-your-price-wrapper--input-error wcpt-name-your-price-wrapper--input-error--min-price wcpt-name-your-price-wrapper--input-error--max-price"
        );
    }

    // trigger nav feedback from range slider and min max
    $("body").on("change", ".wcpt-range-options-main input", function () {
        var $this = $(this),
            $filter = $this.closest(".wcpt-filter");

        $filter.find("input[type=radio]").prop("checked", false);
        nav_filter_feedback($this.closest(".wcpt-navigation"));
    });

    // refresh / block table
    // -- lock
    $("body").on("wcpt_cart_request", function (e, data) {
        if (
            data.wcpt_payload.skip_cart_triggers ||
            data.wcpt_payload.use_cache ||
            data.is_cache
        ) {
            return;
        }

        $(".wcpt").each(function () {
            var $container = $(this),
                sc_attrs = JSON.parse($container.attr("data-wcpt-sc-attrs"));

            if (sc_attrs.refresh_table) {
                $container.addClass("wcpt-loading wcpt-refreshing");
            } else if (sc_attrs.block_table) {
                $container.addClass("wcpt-loading");
            }
        });
    });
    // -- refresh
    $("body").on("wcpt_cart", function (e, result) {
        if (
            result.payload.skip_cart_triggers ||
            result.payload.use_cache ||
            result.is_cache
        ) {
            return;
        }

        $(".wcpt").each(function () {
            var $container = $(this),
                sc_attrs = JSON.parse($container.attr("data-wcpt-sc-attrs")),
                table_id = $container.attr("data-wcpt-table-id");

            if (sc_attrs.refresh_table) {
                $.each(window.wcpt_cache.data, function (key, val) {
                    if (
                        key.indexOf("?" + table_id + "_") !== -1 ||
                        key.indexOf("&" + table_id + "_") !== -1
                    ) {
                        delete window.wcpt_cache.data[key];
                    }
                });

                attempt_ajax($container, "", true, "refresh_table");
            } else if (sc_attrs.block_table) {
                $container.removeClass("wcpt-loading");
            }
        });
    });

    // berocket
    $(document).on("berocket_ajax_filtering_end", function (e) {
        $(".wcpt").each(function () {
            after_every_load($(this));
        });
    });

    // waveplayer

    // -- holds the detached waveplayer instances
    window.$wcpt_waveplayer_preserve = $();

    //-- avoid losing functionality on cloned players during freeze table
    $("body").on("wcpt_layout", ".wcpt", function () {
        var $this = $(this);

        if (typeof WavePlayer !== "undefined") {
            WavePlayer.loadInstances();

            if (!$wcpt_waveplayer_preserve.length) {
                return;
            }

            var $replace = get_matching_waveplayer_elm($this);

            if ($replace.length) {
                $replace.replaceWith($wcpt_waveplayer_preserve);
                $wcpt_waveplayer_preserve
                    .find(".wvpl-waveform canvas")
                    .each(function () {
                        var $this = $(this);
                        $this.width($this.attr("data-wcpt-last-width"));
                        $this.height($this.attr("data-wcpt-last-height"));
                    });

                waveplayer_active_ui_feedback.call(
                    $wcpt_waveplayer_preserve.get(0)
                );

                $wcpt_waveplayer_preserve = $();

                WavePlayer.redrawAllInstances();
            }
        }
    });

    // -- check for and return waveplayer element in container that needs to be replaced
    function get_matching_waveplayer_elm($container) {
        if (WavePlayer.persistentTrack && WavePlayer.instances.length) {
            var instance = false,
                track_index = false;

            $.each(WavePlayer.instances, function (_instance_index, _instance) {
                var $_instances = $(_instance.node);
                if (
                    $_instances.is(":visible") &&
                    $_instances.parent().hasClass("wcpt-waveplayer-container")
                ) {
                    var $row = $_instances.closest(".wcpt-row");

                    if ($row.length) {
                        $.each(
                            _instance.tracks,
                            function (_track_index, _track) {
                                if (
                                    _track.file ===
                                        WavePlayer.persistentTrack.file &&
                                    _track.product_id ===
                                        WavePlayer.persistentTrack.product_id
                                ) {
                                    instance = _instance;
                                    track_index = _track_index;
                                }
                            }
                        );
                    }
                }
            });

            if (instance) {
                return $(instance.node).parent(".wcpt-waveplayer-container");
            }
        }

        return $();
    }

    // -- add to cart
    $("body").on("added_to_cart", function (e, fragment, cart_hash, $button) {
        if (typeof WavePlayer === "undefined" || !$button) {
            return;
        }

        var product_id = $button.attr("data-product_id");
        if (typeof WavePlayer.updateTrackCartStatus !== "undefined") {
            WavePlayer.updateTrackCartStatus(product_id, "add");
        }

        $(".wvpl-cart[data-product_id=" + product_id + "]")
            .attr("title", WavePlayer.__("Add to cart", "waveplayer"))
            .attr("data-event", "goToCart")
            .attr("data-callback", "goToCart")
            .removeClass("wvpl-add_to_cart")
            .addClass("wvpl-in_cart")
            .removeClass("wvpl-spin");
    });

    // -- row color
    $("body").on(
        "click",
        ".wcpt-row .wcpt-waveplayer-container",
        waveplayer_active_ui_feedback
    );

    // -- active feedback
    function waveplayer_active_ui_feedback() {
        var $this = $(this),
            active_row_background_color = $this.attr(
                "data-wcpt-waveplayer-active-row-background-color"
            ),
            active_row_outline_color = $this.attr(
                "data-wcpt-waveplayer-active-row-outline-color"
            )
                ? $this.attr("data-wcpt-waveplayer-active-row-outline-color")
                : "#4198de",
            active_row_outline_width = $this.attr(
                "data-wcpt-waveplayer-active-row-outline-width"
            )
                ? parseFloat(
                      $this.attr(
                          "data-wcpt-waveplayer-active-row-outline-width"
                      )
                  ) + "px"
                : "1px",
            $rows = wcpt_get_sibling_rows($this.closest(".wcpt-row"));

        $rows.addClass("wcpt-waveplayer-active").css({
            background: active_row_background_color
                ? active_row_background_color
                : "",
            outline:
                active_row_outline_width + " solid " + active_row_outline_color
        });

        $(".wcpt-row").not($rows).removeClass("wcpt-waveplayer-active").css({
            background: "",
            outline: ""
        });
    }

    // 3rd party tab and accordion compatibility

    // -- elementor accordion, tab & toggle
    $("body").on("click", ".elementor-tab-title", function () {
        var $this = $(this),
            $container = $this.closest(".elementor-widget-container"),
            $wcpt = $container.find(".wcpt");

        $wcpt.trigger("wcpt_layout", { source: "elementor__tab" });
    });

    // -- divi
    // -- -- tab
    $("ul.et_pb_tabs_controls > li").on("click", function () {
        var $this = $(this),
            index = $this.index(),
            $controls = $this.closest(".et_pb_tabs_controls"),
            $tabs = $controls.siblings(".et_pb_all_tabs"),
            $container = $tabs.children().eq(index),
            $wcpt = $container.find(".wcpt");

        setTimeout(function () {
            $wcpt.trigger("wcpt_layout", { source: "divi__tab" });
        }, 700);
    });
    // -- -- accordion
    $(".et_pb_toggle_title").on("click", function () {
        var $this = $(this),
            $content = $this.next(".et_pb_toggle_content"),
            $wcpt = $content.find(".wcpt");

        setTimeout(function () {
            $wcpt.trigger("wcpt_layout", { source: "divi__tab" });
        }, 700);
    });

    // -- beaver builder
    $("body").on("click", ".fl-accordion-button, .fl-tabs-label", function () {
        var $this = $(this),
            $container = $this.closest(".fl-accordion-item, .fl-tabs-panel"),
            $wcpt = $container.find(".wcpt");

        $wcpt.trigger("wcpt_layout", { source: "beaver_builder__tab" });
    });

    // -- shortcode ultimate
    // -- -- tab
    $("body").on("click", ".su-tabs-nav span", function () {
        var $this = $(this),
            index = $this.index(),
            $nav = $this.closest(".su-tabs-nav"),
            $panes = $nav.siblings(".su-tabs-panes"),
            $container = $panes.children().eq(index),
            $wcpt = $container.find(".wcpt");

        $wcpt.trigger("wcpt_layout", { source: "shortcode_ultimate__tab" });
    });
    // -- -- accordion
    $("body").on("click", ".su-spoiler-title", function () {
        var $this = $(this),
            $container = $this.closest(".su-spoiler"),
            $wcpt = $container.find(".wcpt");

        $wcpt.trigger("wcpt_layout", {
            source: "shortcode_ultimate__accordion"
        });
    });

    // -- wp bakery visual composer
    // -- -- tab
    $("body").on("click", ".vce-classic-tabs-tab", function () {
        var $this = $(this),
            index = $this.index(),
            $nav = $this.closest(".vce-classic-tabs-container"),
            $panes = $nav.siblings(".vce-classic-tabs-panels-container"),
            $container = $panes
                .children(".vce-classic-tabs-panels")
                .children()
                .eq(index),
            $wcpt = $container.find(".wcpt");

        setTimeout(function () {
            $wcpt.trigger("wcpt_layout", { source: "visual_composer__tab" });
        }, 1);
    });
    // -- -- accordion (1)
    $("body").on("click", ".vce-classic-accordion-panel-heading", function () {
        var $this = $(this),
            $container = $this.closest(".vce-classic-accordion-panel"),
            $wcpt = $container.find(".wcpt");
        setTimeout(function () {
            $wcpt.trigger("wcpt_layout", {
                source: "visual_composer__accordion"
            });
        }, 1);
    });
    // -- -- accordion (2)
    $("body").on("click", ".vc_tta-panel-heading", function () {
        var $this = $(this),
            $container = $this.closest(".vc_tta-panel"),
            $wcpt = $container.find(".wcpt");
        setTimeout(function () {
            $wcpt.trigger("wcpt_layout", {
                source: "visual_composer__accordion"
            });
        }, 1);
    });

    // -- responsive accordion and collapse
    $("body").on("click", ".wpsm_panel-heading", function () {
        var $this = $(this),
            $container = $this.closest(".wpsm_panel"),
            $content = $container.find(".wpsm_panel-collapse");

        $(".wcpt", $content).trigger("wcpt_layout", {
            source: "rac__accordion"
        });
    });

    // -- king composer
    $("body").on("click", ".kc_accordion_header", function () {
        var $this = $(this),
            $container = $this.closest(".kc_accordion_section"),
            $content = $container.find(".kc_accordion_content");

        $(".wcpt", $content).trigger("wcpt_layout", {
            source: "king_composer__accordion"
        });
    });

    // -- helpie faq
    $("body").on("mouseup touchend", ".accordion__header", function () {
        var $this = $(this),
            $container = $this.closest(".accordion__item"),
            $content = $container.find(".accordion__body");

        clearTimeout(window.wcpt_helpie_timeout);
        window.wcpt_helpie_timeout = setTimeout(function () {
            $(".wcpt:visible", $content).trigger("wcpt_layout", {
                source: "helpie_faq__accordion"
            });
        }, 200);
    });

    // -- tabs (wpshopmart)
    $("body").on("click", ".wpsm_nav li", function () {
        var $this = $(this),
            index = $this.index(),
            $nav = $this.closest(".wpsm_nav"),
            $panes = $nav.siblings(".tab-content"),
            $container = $panes.children().eq(index),
            $wcpt = $container.find(".wcpt");

        $wcpt.trigger("wcpt_layout", { source: "tabs_wpshopmart__tab" });
    });

    // -- tabby responsive tabs
    $("body").on("click", ".tabtitle", function () {
        var $this = $(this),
            $panel = $this.siblings(".tabcontent");

        $(".wcpt", $panel).trigger("wcpt_layout", { source: "tabby_tab" });
    });

    $("body").on("click", ".responsive-tabs__list__item", function () {
        var $this = $(this),
            index = $this.index(),
            $container = $this.closest(".responsive-tabs"),
            $panel = $(".tabcontent", $container).eq(index);

        $(".wcpt", $panel).trigger("wcpt_layout", { source: "tabby_tab" });
    });

    // -- kadence blocks
    $("body").on("click", ".kt-title-item", function () {
        var $this = $(this),
            $tab_container = $this.closest(".kt-tabs-wrap");

        setTimeout(function () {
            $tab_container.find(".wcpt:visible").trigger("wcpt_layout");
        }, 100);
    });

    // sonaar
    // -- audio player play / pause
    $("body").on("click", ".wcpt-player--sonaar", function () {
        var $this = $(this),
            playlist_id = $this.attr("data-wcpt-sonaar-playlist-id");

        if (typeof IRON == "undefined" || !playlist_id) {
            return;
        }

        $(".wcpt-player--sonaar")
            .not($this)
            .removeClass("wcpt-player--playing");

        // pause
        if (
            IRON.sonaar.player.isPlaying &&
            IRON.sonaar.player.playlistID == playlist_id
        ) {
            IRON.sonaar.player.pause();
            $this.removeClass("wcpt-player--playing wcpt-media-loaded");
        } else {
            $this.addClass("wcpt-player--playing wcpt-media-loaded");

            IRON.sonaar.player.setPlayer({
                id: playlist_id,
                autoplay: true,
                soundwave: true
            });
        }
    });

    // -- auto set play / pause status after every load
    function sonaar_player_auto_status() {
        if (typeof IRON == "undefined") {
            return;
        }

        var playlist_id = IRON.sonaar.player.playlistID;

        if (playlist_id) {
            $(
                '.wcpt-player--sonaar[data-wcpt-sonaar-playlist-id="' +
                    playlist_id +
                    '"]'
            ).addClass("wcpt-player--playing wcpt-media-loaded");
        }
    }

    // TI Wishlist integration

    if (typeof wcpt_ti_wishlist_ids !== "undefined") {
        // update counter widget
        setTimeout(function () {
            $(".wishlist_products_counter_number").text(
                wcpt_ti_wishlist_ids.length
            );
            if (wcpt_ti_wishlist_ids.length) {
                $(".wishlist_products_counter").addClass(
                    "wishlist-counter-with-products"
                );
            } else {
                $(".wishlist_products_counter").removeClass(
                    "wishlist-counter-with-products"
                );
            }
        }, 100);

        // wishlist button
        $("body").on("click", ".wcpt-wishlist", function (e) {
            var $this = $(this),
                $row = $this.closest(".wcpt-row"),
                $wcpt = $this.closest(".wcpt"),
                product_type = $row.attr("data-wcpt-type"),
                product_id = $row.attr("data-wcpt-product-id"),
                variation_id = $row.attr("data-wcpt-variation-id")
                    ? $row.attr("data-wcpt-variation-id")
                    : $row.data("wcpt_variation_id")
                      ? $row.data("wcpt_variation_id")
                      : false,
                variable_permitted = $this.attr("data-wcpt-variable-permitted");

            // variable product must have variation selected
            if (
                product_type == "variable" &&
                !variable_permitted &&
                !variation_id
            ) {
                alert("Please select some options first");
                return;
            }

            var list_id = variation_id ? variation_id : product_id;

            $this.addClass("wcpt-loading");

            var data = {
                tinv_wishlist_id: false,
                tinv_wishlist_name: false,
                product_type: $row.attr("data-wcpt-type"),
                product_id: $row.attr("data-wcpt-product-id"),
                product_variation: variation_id,
                redirect: false
            };

            if (variation_id) {
                var attributes =
                    data.product_type === "variable"
                        ? $row.data("wcpt_attributes")
                        : JSON.parse(
                              $row.attr("data-wcpt-variation-attributes")
                          );

                $.extend(data, {
                    form: $.extend(
                        {
                            quantity: 1,
                            product_id: product_id,
                            variation_id: variation_id
                        },
                        attributes
                    )
                });
            }

            // add
            if (-1 === wcpt_ti_wishlist_ids.indexOf(list_id)) {
                $.ajax({
                    url: wcpt_params.ajax_url,
                    method: "POST",
                    beforeSend: function () {
                        // variation added from variable product, refresh page to show it
                        if (
                            product_type === "variable" &&
                            $wcpt.data("wcpt_sc_attrs").ti_wishlist
                        ) {
                            $wcpt.addClass("wcpt-loading");
                        }
                    },
                    data: $.extend({}, { product_action: "addto" }, data)
                }).done(function (response) {
                    // variation added from variable product, refresh page to show it
                    if (
                        product_type === "variable" &&
                        $wcpt.data("wcpt_sc_attrs").ti_wishlist
                    ) {
                        window.location.reload();
                    }

                    wcpt_ti_wishlist_update_row_view($row);

                    wcpt_ti_wishlist_growler({
                        name: $this.attr("data-wcpt-product-name"),
                        view_wishlist_label: $this.attr(
                            "data-wcpt-view-wishlist-label"
                        ),
                        item_added_label: $this.attr(
                            "data-wcpt-item-added-label"
                        ),
                        icon: $this.attr("data-wcpt-icon"),
                        url: $this.attr("data-wcpt-custom-url"),
                        duration_seconds: $this.attr(
                            "data-wcpt-duration-seconds"
                        )
                    });

                    // update counter widget
                    if (
                        response &&
                        typeof response.wishlists_data !== "undefined"
                    ) {
                        $(".wishlist_products_counter_number").text(
                            response.wishlists_data.counter
                        );
                        if (response.wishlists_data.counter) {
                            $(".wishlist_products_counter").addClass(
                                "wishlist-counter-with-products"
                            );
                        } else {
                            $(".wishlist_products_counter").removeClass(
                                "wishlist-counter-with-products"
                            );
                        }
                    }
                });

                // add to maintained list as well
                wcpt_ti_wishlist_ids.push(list_id);

                // update UI
                $this.addClass("wcpt-active");

                // remove
            } else {
                $.ajax({
                    url: wcpt_params.ajax_url,
                    method: "POST",
                    data: $.extend({}, { product_action: "remove" }, data)
                }).done(function (response) {
                    // update counter widget
                    if (
                        response &&
                        typeof response.wishlists_data !== "undefined"
                    ) {
                        $(".wishlist_products_counter_number").text(
                            response.wishlists_data.counter
                        );
                        if (response.wishlists_data.counter) {
                            $(".wishlist_products_counter").addClass(
                                "wishlist-counter-with-products"
                            );
                        } else {
                            $(".wishlist_products_counter").removeClass(
                                "wishlist-counter-with-products"
                            );
                        }
                    }
                });

                // remove from maintained list as well
                var index = wcpt_ti_wishlist_ids.indexOf(list_id);
                if (index > -1) {
                    wcpt_ti_wishlist_ids.splice(index, 1);
                }

                wcpt_ti_wishlist_update_row_view($row);

                // update UI
                // $this.removeClass('wcpt-active');

                // remove product row from wishlist table
                if ($wcpt.data("wcpt_sc_attrs").ti_wishlist) {
                    var $remove_row = $row;

                    // don't remove variable product if it was being used to remove a variation only
                    if (variation_id) {
                        $remove_row = $wcpt.find(
                            '.wcpt-product-type-variation[data-wcpt-variation-id="' +
                                variation_id +
                                '"]'
                        );
                    }

                    $remove_row.addClass("wcpt-wishlist-removing-row");
                    setTimeout(function () {
                        $remove_row.remove();
                    }, 500);
                }
            }
        });

        function wcpt_ti_wishlist_growler(data) {
            var template = $("#wcpt-ti-wishlist-growler-template").html(),
                $growler = $(template.replace("{n}", '"' + data.name + '"')),
                reveal_class = "wcpt-ti-wishlist-growler--revealed",
                duration_ms = data.duration_seconds * 1000;

            $growler.attr("data-wcpt-icon", data.icon);
            $(".wcpt-ti-wishlist-growler").remove(); // remove previous

            if (data.item_added_label) {
                $(
                    ".wcpt-ti-wishlist-growler__label--item-added",
                    $growler
                ).text(
                    data.item_added_label.replace("{n}", '"' + data.name + '"')
                );
            }

            if (data.view_wishlist_label) {
                $(
                    ".wcpt-ti-wishlist-growler__label--view-wishlist",
                    $growler
                ).text(
                    data.view_wishlist_label.replace(
                        "{n}",
                        '"' + data.name + '"'
                    )
                );
            }

            if (data.url) {
                $growler.attr("href", data.url);
            } else if (wcpt_ti_wishlist_url) {
                $growler.attr("href", wcpt_ti_wishlist_url);
            }

            $("body").append($growler);

            setTimeout(function () {
                $growler.addClass(reveal_class);
            }, 100);

            setTimeout(function () {
                $growler.removeClass(reveal_class);
            }, duration_ms);

            setTimeout(function () {
                $growler.remove();
            }, duration_ms + 500);
        }

        $("body").on("wcpt_after_every_load", ".wcpt", function () {
            var $this__container = $(this),
                $visible_rows = wcpt_util
                    .get_uninit_rows($this__container)
                    .filter(":visible");

            // variable products in wishlist were definitely added without variation selected
            if (wcpt_util.get_sc_attrs($this__container).ti_wishlist) {
                $visible_rows
                    .filter(".wcpt-product-type-variable")
                    .trigger("select_variation", {
                        variation_id: false,
                        complete_match: false,
                        attributes: false,
                        variation: false,
                        variation_found: false,
                        variation_selected: false,
                        variation_available: false
                    });
            }

            $visible_rows.each(function () {
                var $this__container = $(this);
                wcpt_ti_wishlist_update_row_view($this__container);
            });
        });

        $("body").on("select_variation", ".wcpt-row", function () {
            var $this = $(this);
            wcpt_ti_wishlist_update_row_view($this);
        });

        function wcpt_ti_wishlist_update_row_view($row) {
            var product_type = $row.attr("data-wcpt-type"),
                product_id = $row.attr("data-wcpt-product-id"),
                variation_id = $row.data("wcpt_variation_id")
                    ? $row.data("wcpt_variation_id")
                    : $row.attr("data-wcpt-variation-id")
                      ? $row.attr("data-wcpt-variation-id")
                      : false,
                $buttons = $(".wcpt-wishlist", wcpt_get_sibling_rows($row)),
                variable_permitted = $buttons.attr(
                    "data-wcpt-variable-permitted"
                );

            if (
                product_type === "variable" &&
                !variable_permitted &&
                !variation_id
            ) {
                $buttons.removeClass("wcpt-active").addClass("wcpt-disabled");

                return;
            }

            $buttons.removeClass("wcpt-disabled wcpt-loading");

            var list_id = variation_id ? variation_id : product_id;

            if (-1 === wcpt_ti_wishlist_ids.indexOf(list_id + "")) {
                $buttons.removeClass("wcpt-active");
            } else {
                $buttons.addClass("wcpt-active");
            }
        }
    }

    // WooCommerce Wholesale Prices

    // -- select_variation handler to switch vals in wcpt_wholesale
    $("body").on("select_variation", ".wcpt-row", function () {
        var $this = $(this),
            variation = $this.data("wcpt_variation"),
            $wholesale_shortcode = $(".wcpt-wholesale", $this);

        $wholesale_shortcode.each(function () {
            var $this = $(this);

            // set the view: default or variation
            $this.removeClass(
                "wcpt-wholesale--variation-view-enabled wcpt-wholesale--default-view-enabled"
            );

            if (variation) {
                $this.addClass("wcpt-wholesale--variation-view-enabled");
            } else {
                $this.addClass("wcpt-wholesale--default-view-enabled");
            }

            // wholesale table
            if ($this.hasClass("wcpt-wholesale--wholesale-table")) {
                var variation_html = "-";

                if (variation && variation.wholesale_price) {
                    var table_html_match =
                        variation.price_html.match(/(<table.+table)>/s);
                    if (table_html_match) {
                        variation_html = table_html_match[0];
                    }
                }

                $this.html(variation_html);
            }

            // wholesale price
            if ($this.hasClass("wcpt-wholesale--wholesale-price")) {
                var variation_text = "-";

                if (variation) {
                    var wholesale_price = "";
                    if (variation.wholesale_price) {
                        wholesale_price = variation.wholesale_price;
                    }
                    variation_text = format_price(wholesale_price);
                }

                $(".wcpt-wholesale__variation-view", $this).text(
                    variation_text
                );
            }

            // original price
            if ($this.hasClass("wcpt-wholesale--original-price")) {
                var variation_text = "-";

                if (variation) {
                    var original_price = "";
                    if (variation.wholesale_price) {
                        original_price = variation.original_display_price; // cannot use display_price here because it is set to wholesale_price by WCPT
                    } else {
                        original_price = variation.display_price;
                    }
                    variation_text = format_price(original_price);
                }

                $(".wcpt-wholesale__variation-view", $this).text(
                    variation_text
                );
            }

            // wholesale label
            if ($this.hasClass("wcpt-wholesale--wholesale-label")) {
                $this.removeClass(
                    "wcpt-wholesale--variation-is-on-wholesale-view-enabled wcpt-wholesale--variation-is-not-on-wholesale-view-enabled"
                );
                if (variation) {
                    if (variation.wholesale_price) {
                        $this.addClass(
                            "wcpt-wholesale--variation-is-on-wholesale-view-enabled"
                        );
                    } else {
                        $this.addClass(
                            "wcpt-wholesale--variation-is-not-on-wholesale-view-enabled"
                        );
                    }
                }
            }
        });
    });

    // Variation Swatches
    $("body").on(
        "change",
        ".wcpt-row .variations_form .variation_id",
        function () {
            var $this = $(this),
                $form = $this.closest(".variations_form");

            if ($form.hasClass("wvs-loaded")) {
                get_select_variation_from_cart_form($form);
            }
        }
    );

    // WC Request a Quote (by Addify)

    if (typeof wcpt_afrfq_params == "undefined") {
        wcpt_afrfq_params = {
            product_ids: [],
            view_quote_url: "",
            view_quote_label: ""
        };
    }

    // -- button status & switch html class
    $("body").on("wcpt_after_every_load", ".wcpt", function () {
        var $this__container = $(this);

        // switch native html class to wcpt html class on plugin buttons
        $(
            ".afrfqbt, .afrfqbt_single_page",
            wcpt_util.get_uninit_rows($this__container)
        )
            .removeClass("afrfqbt afrfqbt_single_page")
            .addClass("wcpt-afrfqbt"); // override with WCPT handler

        // remove afrfqbt success message
        $(".added_quote_pro").remove();

        // set added status
        afrfqbt_status($this__container);
    });

    setTimeout(afrfqbt_status, 500); // some external script removes 'added' html class

    function afrfqbt_status($container) {
        // $container will be $('.wcpt-afrfqbt'), $row, $wcpt or $body
        if (typeof $container === "undefined") {
            $container = $("body");
        }

        var $raq_buttons = $container.is(".wcpt-afrfqbt")
            ? $container
            : $(".wcpt-afrfqbt", wcpt_util.get_uninit_rows($container));

        $raq_buttons.each(function () {
            var $raq_button = $(this),
                $row = $raq_button.closest(".wcpt-row"),
                id = false,
                product_type = $row.attr("data-wcpt-type"),
                $prev_view_link = $raq_button.siblings(
                    ".wcpt-afrfqbt-view-quote-wrapper"
                );

            if (product_type === "variable") {
                id = $row.data("wcpt_variation_id");
            } else if (product_type === "variation") {
                id = $row.attr("data-wcpt-variation-id");
            } else {
                id = $row.attr("data-wcpt-product-id");
            }

            if (-1 !== $.inArray(parseInt(id), wcpt_afrfq_params.product_ids)) {
                $raq_button.addClass("added");

                if (!$prev_view_link.length) {
                    $raq_button.after(
                        '<div class="wcpt-afrfqbt-view-quote-wrapper"><a href="' +
                            wcpt_afrfq_params.view_quote_url +
                            '" class="wcpt-afrfqbt-view-quote">' +
                            wcpt_afrfq_params.view_quote_label +
                            "</a></div>"
                    );
                }
            } else {
                $raq_button.removeClass("added");
                $prev_view_link.remove();
            }
        });
    }

    // -- ev: 'select_variation' - enable / disable button
    $("body").on(
        "select_variation",
        ".wcpt-product-type-variable",
        function () {
            var $this = $(this),
                variation_id = $this.data("wcpt_variation_id"),
                $raq_button = $(".wcpt-afrfqbt", $this);

            afrfqbt_status($this);

            if (variation_id) {
                // enable button
                $raq_button.removeClass("disabled");
            } else {
                // disable button
                $raq_button.addClass("disabled");
            }
        }
    );

    // -- AJAX req

    $("body").on("click", ".wcpt-afrfqbt", function (e) {
        e.preventDefault();

        var $this = $(this),
            $row = $this.closest(".wcpt-row"),
            product_id = $row.attr("data-wcpt-product-id"),
            variation_id = false,
            variation_attributes = false,
            qty = 0;

        if ($(".cart .qty", $row).length) {
            qty = $(".cart .qty", $row).val();
        } else {
            qty = $(".qty", $row).val();
        }

        if (!qty) {
            qty = 1;
        }

        if ($row.hasClass("wcpt-product-type-variable")) {
            variation_id = $row.data("wcpt_variation_id");
            variation_attributes = $row.data("wcpt_attributes");
        } else if ($row.hasClass("wcpt-product-type-variation")) {
            variation_id = $row.attr("data-wcpt-variation-id");
            variation_attributes = $row.data("wcptVariationAttributes");
        }

        if ($row.hasClass("wcpt-product-type-variable") && !variation_id) {
            alert(wcpt_i18n.i18n_make_a_selection_text);
            return;
        }

        if ($this.hasClass("disabled")) {
            return;
        }

        $this.removeClass("added"); // avoid conflict with .loading animation

        if (variation_id) {
            var ajax_data = {
                action: "add_to_quote_single_vari",
                form_data: {
                    product_id: product_id,
                    variation_id: variation_id,
                    "add-to-cart": product_id,
                    quantity: qty
                },
                nonce: afrfq_phpvars.nonce
            };

            ajax_data.form_data = $.param(ajax_data.form_data);

            $.extend(ajax_data.form_data, variation_attributes);
        } else {
            var ajax_data = {
                action: "add_to_quote_single",
                product_id: product_id,
                quantity: qty,
                woo_addons: false,
                woo_addons1: false,
                nonce: afrfq_phpvars.nonce
            };
        }

        $.ajax({
            url: afrfq_phpvars.admin_url,
            method: "POST",
            beforeSend: function () {
                $this.addClass("loading");
            },
            data: ajax_data
        }).done(function (response) {
            // keep list of products in raq
            // upon after every load, refer to this list
            $this.removeClass("loading");

            if (variation_id) {
                wcpt_afrfq_params.product_ids.push(parseInt(variation_id));
            } else {
                wcpt_afrfq_params.product_ids.push(parseInt(product_id));
            }

            afrfqbt_status($this);

            if (response !== "success") {
                // menu mini quote
                $(".quote-li").replaceWith(response["mini-quote"]);
            }
        });
    });

    // init tables
    var $wcpt = $(".wcpt");
    if ($wcpt.length) {
        $wcpt.each(function () {
            after_every_load($(this));
        });
    }

    // lazy load
    lazy_load_start();

    // init cart widget
    var cart_init_required = false;

    if (
        document.cookie.indexOf("woocommerce_items_in_cart") !== -1 ||
        (typeof wcpt_cart_result_cache !== "undefined" &&
            wcpt_cart_result_cache.cart_quantity)
    ) {
        cart_init_required = true;
    }

    if (cart_init_required) {
        wcpt_cart({
            payload: { skip_cart_triggers: true }
        });
    }

    $(window).on("pageshow", function (e) {
        if (e.originalEvent.persisted) {
            wcpt_cart({
                payload: { skip_cart_triggers: true }
            });
        }
    });
});

/* -- modules -- */

// module: child row
(function ($) {
    // init
    $("body").on("wcpt_after_every_load", ".wcpt", init_child_rows);

    // init a individual child row
    $("body").on(
        "wcpt_init_child_row",
        ".wcpt-child-row",
        register_parent_child
    );
    $("body").on(
        "wcpt_init_child_row",
        ".wcpt-child-row",
        set_background_color
    );

    // child row toggle
    $("body").on("click", ".wcpt-child-row-toggle", child_row_toggle);
    $("body").on(
        "click",
        ".wcpt-has-child-row--click-anywhere",
        child_row_toggle_anywhere
    );

    // hover - identify group
    $("body").on("mouseenter", ".wcpt-has-child-row", parent_row_mouseenter);
    $("body").on("mouseleave", ".wcpt-has-child-row", parent_row_mouseleave);

    $("body").on("mouseenter", ".wcpt-child-row", child_row_mouseenter);
    $("body").on("mouseleave", ".wcpt-child-row", child_row_mouseleave);

    // handlers

    function init_child_rows() {
        var $this = $(this); // .wcpt
        $(".wcpt-child-row:not(.wcpt-child-row--init)", $this).each(
            function () {
                var $this = $(this); // child row

                $this
                    .trigger("wcpt_init_child_row")
                    .addClass("wcpt-child-row--init");
            }
        );
    }

    function register_parent_child() {
        var $this = $(this),
            $parent = $this.prev();

        $this.data("wcpt_parent_row", $parent);
        $parent.data("wcpt_child_row", $this);
    }

    function set_background_color() {
        var $this = $(this),
            $parent = $this.data("wcpt_parent_row"),
            $parent_bg = $parent.css("background-color"),
            $first_td = $("> td:first-child", $parent),
            $cell_bg = $first_td.css("background-color"),
            background_color =
                $cell_bg && $cell_bg !== "rgba(0, 0, 0, 0)"
                    ? $cell_bg
                    : $parent_bg;

        $(">td", $this).css({
            "background-color": background_color
        });
    }

    function child_row_toggle() {
        var $this = $(this),
            $row = $this.closest(".wcpt-row"),
            $wcpt = $this.closest(".wcpt");

        $this.toggleClass("wcpt-child-row-toggle--closed");

        if ($this.is("td")) {
            $row.data("wcpt_child_row").toggle();

            // heading status
            if (
                $(
                    "td.wcpt-child-row-toggle:not(.wcpt-child-row-toggle--closed)",
                    $wcpt
                ).length
            ) {
                $(
                    "th.wcpt-child-row-toggle.wcpt-child-row-toggle--closed",
                    $wcpt
                ).removeClass("wcpt-child-row-toggle--closed");
            } else {
                $("th.wcpt-child-row-toggle", $wcpt).addClass(
                    "wcpt-child-row-toggle--closed"
                );
            }
        } else {
            // th
            if ($this.hasClass("wcpt-child-row-toggle--closed")) {
                $(".wcpt-child-row-toggle", $wcpt).addClass(
                    "wcpt-child-row-toggle--closed"
                );
                $(".wcpt-child-row", $wcpt).hide();
            } else {
                $(".wcpt-child-row-toggle", $wcpt).removeClass(
                    "wcpt-child-row-toggle--closed"
                );
                $(".wcpt-child-row", $wcpt).show();
            }
        }

        // row class
        $("td.wcpt-child-row-toggle", $wcpt).each(function () {
            var $this = $(this),
                $row = $this.closest(".wcpt-row");

            if ($this.hasClass("wcpt-child-row-toggle--closed")) {
                $row.removeClass("wcpt-has-child-row--visible");
            } else {
                $row.addClass("wcpt-has-child-row--visible");
            }
        });
    }

    // -- click anywhere in row
    function child_row_toggle_anywhere() {
        var $this = $(this),
            $row = $this.closest(".wcpt-row"),
            action_elm_classes =
                ".wcpt-child-row-toggle, a, .wcpt-tooltip, input, button, .wcpt-button, .wcpt-link, .wcpt-quantity";

        if (!$(e.target).closest(action_elm_classes).length) {
            $(".wcpt-child-row-toggle", $row).click();
        }
    }

    // identify group hover

    // -- $parent
    // -- -- mouseenter
    function parent_row_mouseenter() {
        var $this__parent_row = $(this),
            $child_row = $this__parent_row.data("wcpt_child_row") || $();

        $child_row.addClass("wcpt-parent-row-hovered");
    }
    // -- -- mouseleave
    function parent_row_mouseleave() {
        var $this__parent_row = $(this),
            $child_row = $this__parent_row.data("wcpt_child_row") || $();

        $child_row.removeClass("wcpt-parent-row-hovered");
    }

    // -- $child
    // -- -- mouseenter
    function child_row_mouseenter() {
        var $this__child_row = $(this),
            $parent_row = $this__child_row.data("wcpt_parent_row") || $();

        $parent_row.addClass("wcpt-child-row-hovered");
    }
    // -- -- mouseleave
    function child_row_mouseleave() {
        var $this__child_row = $(this),
            $parent_row = $this__child_row.data("wcpt_parent_row") || $();

        $parent_row.removeClass("wcpt-child-row-hovered");
    }
})(jQuery);

// module: instant search
(function ($) {
    $("body").on("wcpt_after_every_load", ".wcpt", init_instant_search);
    // $('body').on('wcpt_after_every_load', '.wcpt', function(){
    //   wcpt_util.do_once_on_container( $(this), 'wcpt-instant-search-init', init_instant_search );
    // });

    // -- add instant search html class on $search and stop 'enter' key handler
    function init_instant_search() {
        var $container = $(this),
            $search = $(".wcpt-search-wrapper", $container);

        if (
            wcpt_util.get_sc_attrs($container).instant_search &&
            !$search.hasClass("wcpt-instant-search")
        ) {
            $search.addClass("wcpt-instant-search");
            $(".wcpt-instant-search .wcpt-search-input", $container).on(
                "keydown",
                function (e) {
                    if (e.keyCode === 13 || e.which === 13) {
                        e.stopPropagation();
                    }
                }
            );
        } else {
            $search.removeClass("wcpt-instant-search");
        }
    }

    // -- search logic
    $("body").on(
        "keyup input",
        ".wcpt-instant-search .wcpt-search-input",
        function (e) {
            var $this = $(this),
                val = $this.val().toLowerCase().trim(),
                $wcpt = $this.closest(".wcpt"),
                $table = $(
                    ".wcpt-table:visible:not(.frzTbl-clone-table)",
                    $wcpt
                ),
                $rows = $(".wcpt-row", $table);

            if (!val) {
                $rows.removeClass("wcpt-row--instant-search-hidden");
            } else {
                $rows.each(function () {
                    var $this = $(this),
                        match = false;

                    // using indexed value
                    var text = $this.data("wcpt_instant_search_text");
                    if (!text) {
                        text = $this.text().toLowerCase().trim();
                        $this.data("wcpt_instant_search_text", text);
                    }

                    if (text.indexOf(val) !== -1) {
                        match = true;
                    }

                    if (match) {
                        $this.removeClass("wcpt-row--instant-search-hidden");
                    } else {
                        $this.addClass("wcpt-row--instant-search-hidden");
                    }

                    // child row -- reveal both if either has match
                    if ($this.hasClass("wcpt-child-row")) {
                        $parent_row = $this.data("wcpt_parent_row");

                        if (
                            !$this.hasClass(
                                "wcpt-row--instant-search-hidden"
                            ) ||
                            !$parent_row.hasClass(
                                "wcpt-row--instant-search-hidden"
                            )
                        ) {
                            $this
                                .add($parent_row)
                                .removeClass("wcpt-row--instant-search-hidden");
                        }
                    }
                });

                // show category group headings if it has search results
                $rows.filter(".wcpt-row--category-heading").each(function () {
                    var $this = $(this),
                        cat_slug = $this.attr(
                            "data-wcpt-group-by-category-slug"
                        );

                    if (
                        $rows.filter(
                            '[data-wcpt-group-by-category-slug="' +
                                cat_slug +
                                '"]:visible'
                        ).length
                    ) {
                        $this.removeClass("wcpt-row--instant-search-hidden");
                    }
                });
            }

            wcpt_util.assign_even_odd_row_classes($table);
        }
    );
})(jQuery);

// module: instant sort
(function ($) {
    // -- init
    $("body").on("wcpt_after_every_load", ".wcpt", init_instant_sort);

    function init_instant_sort() {
        var $this = $(this),
            sc_attrs = $this.data("wcpt_sc_attrs");

        if (sc_attrs.instant_sort) {
            // attach data
            var sort_data = [];
            $(
                ".wcpt-row:not(.wcpt-child-row):not(.wcpt-row--category-heading)",
                $this
            ).each(function () {
                var $this = $(this),
                    id = $this.attr("data-wcpt-product-id"),
                    variation_id = $this.attr("data-wcpt-variation-id"),
                    product_sort_data = $.extend(
                        {},
                        {
                            id: id,
                            variation_id: variation_id
                        },
                        JSON.parse($this.attr("data-wcpt-instant-sort-props"))
                    );
                sort_data.push(product_sort_data);
            });

            $this.data("wcpt_sort_data", sort_data);

            // replace handlers
            wcpt_util.do_once_on_container(
                $this,
                "wcpt-instant-sort-init",
                function ($container) {
                    // -- column heading sort icons handler

                    // -- -- remove previous handlers and the init html class on container
                    $container
                        .off(
                            "click.wcpt_sort_by_column_headings",
                            ".wcpt-heading.wcpt-sortable"
                        )
                        .removeClass("wcpt-sortable-headings-init");

                    // -- -- attach new handlers
                    $container.on(
                        "click.wcpt_instant_sort",
                        ".wcpt-heading.wcpt-sortable",
                        function () {
                            var $this = $(this),
                                $sorting_icons = $(
                                    ".wcpt-sorting-icons",
                                    $this
                                ),
                                new_order = $sorting_icons.hasClass(
                                    "wcpt-sorting-asc"
                                )
                                    ? "desc"
                                    : "asc",
                                $wcpt = $this.closest(".wcpt"),
                                all_sort_params = JSON.parse(
                                    $wcpt.attr("data-wcpt-instant-sort-params")
                                ),
                                current_sort_params = {};

                            $.each(
                                all_sort_params.column_heading,
                                function (id, params) {
                                    if ($sorting_icons.hasClass("wcpt-" + id)) {
                                        current_sort_params = params;
                                        return false;
                                    }
                                }
                            );

                            $.extend(current_sort_params, { order: new_order });

                            wcpt_instant_sort(current_sort_params, $wcpt);

                            instant_sort_ui_feedback(
                                current_sort_params,
                                $wcpt
                            );
                        }
                    );

                    // -- 'Sort By' dropdown handler
                    $container.on(
                        "change.wcpt_instant_sort",
                        '[data-wcpt-filter="sort_by"] input[type="radio"]',
                        function (e) {
                            e.stopPropagation(); // block access to wcpt regular sort handler

                            var $this = $(this),
                                $option = $this.closest(
                                    ".wcpt-option, .wcpt-dropdown-option"
                                ), // @TODO include dropdown option
                                $wcpt = $this.closest(".wcpt"),
                                all_sort_params = JSON.parse(
                                    $wcpt.attr("data-wcpt-instant-sort-params")
                                ),
                                index = $option.index(),
                                current_sort_params =
                                    all_sort_params.dropdown[index];

                            wcpt_instant_sort(current_sort_params, $wcpt);

                            instant_sort_ui_feedback(
                                current_sort_params,
                                $wcpt
                            );
                        }
                    );
                }
            );
        } else {
            // turn off
            // -- remove the module's init html class
            $this.removeClass("wcpt-instant-sort-init");
            // -- turn off handlers
            $this.off("change.wcpt_instant_sort");
            $this.off("click.wcpt_instant_sort");
        }
    }

    function instant_sort_ui_feedback(current_sort_params, $wcpt) {
        var all_sort_params = JSON.parse(
            $wcpt.attr("data-wcpt-instant-sort-params")
        );

        if (
            -1 !==
            $.inArray(current_sort_params.orderby, ["rating", "price-desc"])
        ) {
            current_sort_params.order = "desc";
        }

        if (current_sort_params.orderby == "price-desc") {
            current_sort_params.orderby = "price";
        }

        // 'Sort By' dropdown (or row)
        $.each(
            all_sort_params["dropdown"],
            function (dropdown_option_index, option_sort_params) {
                if (
                    -1 !==
                    $.inArray(option_sort_params.orderby, [
                        "rating",
                        "price-desc"
                    ])
                ) {
                    option_sort_params.order = "desc";
                }

                if (option_sort_params.orderby == "price-desc") {
                    option_sort_params.orderby = "price";
                }

                if (
                    current_sort_params.orderby !==
                        option_sort_params.orderby ||
                    (current_sort_params.order &&
                        current_sort_params.order.toLowerCase() !==
                            option_sort_params.order.toLowerCase()) ||
                    (current_sort_params.orderby == "meta_key" &&
                        current_sort_params.meta_key &&
                        current_sort_params.meta_key.toLowerCase() !==
                            option_sort_params.meta_key.toLowerCase()) ||
                    (-1 !==
                        $.inArray(current_sort_params.orderby, [
                            "attribute",
                            "attribute_num"
                        ]) &&
                        current_sort_params.orderby_attribute &&
                        current_sort_params.orderby_attribute.toLowerCase() !==
                            option_sort_params.orderby_attribute.toLowerCase()) ||
                    (current_sort_params.orderby == "taxonomy" &&
                        current_sort_params.orderby_taxonomy &&
                        current_sort_params.orderby_taxonomy.toLowerCase() !==
                            option_sort_params.orderby_taxonomy.toLowerCase())
                ) {
                    return;
                }

                var $dropdown = $('[data-wcpt-filter="sort_by"]', $wcpt), // might be row
                    $selected_input = $dropdown
                        .find("input")
                        .eq(dropdown_option_index),
                    $selected_option = $selected_input.closest(
                        ".wcpt-dropdown-option, .wcpt-option"
                    ),
                    $heading_label = $(".wcpt-dropdown-label", $dropdown);

                $heading_label.text($("span", $selected_option).text());
                $selected_input.prop("checked", true);
                $selected_option
                    .addClass("wcpt-active")
                    .siblings()
                    .removeClass("wcpt-active");

                $dropdown.removeClass("wcpt-open");
            }
        );

        // 'Sorting' column heading icons
        $.each(
            all_sort_params["column_heading"],
            function (id, option_sort_params) {
                if (
                    current_sort_params.orderby !==
                        option_sort_params.orderby ||
                    (current_sort_params.orderby == "meta_key" &&
                        current_sort_params.meta_key &&
                        current_sort_params.meta_key.toLowerCase() !==
                            option_sort_params.meta_key.toLowerCase()) ||
                    (-1 !==
                        $.inArray(current_sort_params.orderby, [
                            "attribute",
                            "attribute_num"
                        ]) &&
                        current_sort_params.orderby_attribute &&
                        current_sort_params.orderby_attribute.toLowerCase() !==
                            option_sort_params.orderby_attribute.toLowerCase()) ||
                    (current_sort_params.orderby == "taxonomy" &&
                        current_sort_params.orderby_taxonomy &&
                        current_sort_params.orderby_taxonomy.toLowerCase() !==
                            option_sort_params.orderby_taxonomy.toLowerCase())
                ) {
                    return;
                }

                var $sorting_icons = $(".wcpt-" + id, $wcpt),
                    new_order = current_sort_params.order.toLowerCase();

                // UI feedback
                $(".wcpt-sorting-icons", $wcpt).removeClass(
                    "wcpt-sorting-asc wcpt-sorting-desc"
                );
                $(".wcpt-sorting-icon", $wcpt).removeClass(
                    "wcpt-active wcpt-inactive"
                );

                if (new_order == "asc") {
                    $sorting_icons
                        .addClass("wcpt-sorting-asc")
                        .removeClass("wcpt-sorting-desc");
                } else {
                    $sorting_icons
                        .addClass("wcpt-sorting-desc")
                        .removeClass("wcpt-sorting-asc");
                }

                $(
                    ".wcpt-sorting-" + new_order + "-icon",
                    $sorting_icons
                ).addClass("wcpt-active");
            }
        );
    }

    window.wcpt_instant_sort = function (params, $wcpt) {
        var sort_data = $wcpt.data("wcpt_sort_data");

        if (!params.order) {
            params.order = "asc";
        }

        params.order = params.order.toLowerCase();

        if (-1 !== $.inArray(params.orderby, ["price-desc", "rating"])) {
            params.order = "desc";
        }

        switch (params.orderby) {
            case "title":
                sort_data.sort(function (a, b) {
                    return params.order == "asc"
                        ? a.title.localeCompare(b.title)
                        : b.title.localeCompare(a.title);
                });

                break;

            case "sku": // as text
                sort_data.sort(function (a, b) {
                    return params.order == "asc"
                        ? a.sku.localeCompare(b.sku)
                        : b.sku.localeCompare(a.sku);
                });

                break;

            case "sku_num":
                sort_data.sort(function (a, b) {
                    var a_sku_num = isNaN(parseFloat(a.sku))
                            ? 0
                            : parseFloat(a.sku),
                        b_sku_num = isNaN(parseFloat(b.sku))
                            ? 0
                            : parseFloat(b.sku);

                    return params.order == "asc"
                        ? a_sku_num - b_sku_num
                        : b_sku_num - a_sku_num;
                });

                break;

            case "menu_order":
                sort_data.sort(function (a, b) {
                    return params.order == "asc"
                        ? a.menu_order - b.menu_order
                        : b.menu_order - a.menu_order;
                });

                break;

            case "price":
            case "price-desc":
                sort_data.sort(function (a, b) {
                    var a_price = a.price,
                        b_price = b.price;

                    if (params.order == "asc" && a.min_price) {
                        a_price = a.min_price;
                    }

                    if (params.order == "desc" && a.max_price) {
                        a_price = a.max_price;
                    }

                    if (params.order == "asc" && b.min_price) {
                        b_price = b.min_price;
                    }

                    if (params.order == "desc" && b.max_price) {
                        b_price = b.max_price;
                    }

                    return params.order == "asc"
                        ? a_price - b_price
                        : b_price - a_price;
                });

                break;

            case "meta_value": // as text
                sort_data.sort(function (a, b) {
                    var a_meta = a["meta_value__" + params.meta_key],
                        b_meta = b["meta_value__" + params.meta_key];

                    return params.order == "asc"
                        ? a_meta.localeCompare(b_meta)
                        : b_meta.localeCompare(a_meta);
                });

                break;

            case "meta_value_num":
                sort_data.sort(function (a, b) {
                    var a_meta = a["meta_value__" + params.meta_key],
                        b_meta = b["meta_value__" + params.meta_key];

                    var a_meta_num = isNaN(parseFloat(a_meta))
                            ? 0
                            : parseFloat(a_meta),
                        b_meta_num = isNaN(parseFloat(b_meta))
                            ? 0
                            : parseFloat(b_meta);

                    return params.order == "asc"
                        ? a_meta_num - b_meta_num
                        : b_meta_num - a_meta_num;
                });

                break;

            case "attribute": // as text
                sort_data.sort(function (a, b) {
                    var a_val = a["attribute__" + params.orderby_attribute],
                        b_val = b["attribute__" + params.orderby_attribute];

                    return params.order == "asc"
                        ? a_val.localeCompare(b_val)
                        : b_val.localeCompare(a_val);
                });

                break;

            case "attribute_num": // as number
                sort_data.sort(function (a, b) {
                    var a_val = a["attribute__" + params.orderby_attribute],
                        b_val = b["attribute__" + params.orderby_attribute];

                    var a_val_num = isNaN(parseFloat(a_val))
                            ? 0
                            : parseFloat(a_val),
                        b_val_num = isNaN(parseFloat(b_val))
                            ? 0
                            : parseFloat(b_val);

                    return params.order == "asc"
                        ? a_val_num - b_val_num
                        : b_val_num - a_val_num;
                });

                break;

            case "taxonomy": // as text
                sort_data.sort(function (a, b) {
                    var a_val = a["taxonomy__" + params.orderby_taxonomy],
                        b_val = b["taxonomy__" + params.orderby_taxonomy];

                    return params.order == "asc"
                        ? a_val.localeCompare(b_val)
                        : b_val.localeCompare(a_val);
                });

                break;

            case "taxonomy_num": // as number
                sort_data.sort(function (a, b) {
                    var a_val = a["taxonomy__" + params.orderby_taxonomy],
                        b_val = b["taxonomy__" + params.orderby_taxonomy];

                    var a_val_num = isNaN(parseFloat(a_val))
                            ? 0
                            : parseFloat(a_val),
                        b_val_num = isNaN(parseFloat(b_val))
                            ? 0
                            : parseFloat(b_val);

                    return params.order == "asc"
                        ? a_val_num - b_val_num
                        : b_val_num - a_val_num;
                });

                break;

            case "category":
                sort_data.sort(function (a, b) {
                    return params.order == "asc"
                        ? a.category.localeCompare(b.category)
                        : b.category.localeCompare(a.category);
                });

            case "date":
            case "popularity":
            case "id":
            case "rating":
                sort_data.sort(function (a, b) {
                    return params.order == "asc"
                        ? a[params.orderby] - b[params.orderby]
                        : b[params.orderby] - a[params.orderby];
                });

                break;

            default:
                break;
        }

        // render
        $.each(sort_data, function (index, product_sort_data) {
            var $row = false;
            if (product_sort_data.variation_id) {
                // variation
                $row = $wcpt.find(
                    '.wcpt-row[data-wcpt-variation-id="' +
                        product_sort_data.variation_id +
                        '"]'
                );
            } else {
                // other
                $row = $wcpt.find(
                    '.wcpt-row[data-wcpt-product-id="' +
                        product_sort_data.id +
                        '"]'
                );
            }

            $row.each(function () {
                var $this = $(this),
                    $tbody = $this.closest("tbody");
                $this.detach().appendTo($tbody);
            });
        });

        // -- group by category
        if (
            wcpt_util.get_sc_attrs($wcpt)[
                wcpt_util.get_device() + "_group_by_category"
            ]
        ) {
            $(
                $(".wcpt-row:not(.wcpt-row--category-heading)", $wcpt)
                    .get()
                    .reverse()
            ).each(function () {
                var $this = $(this),
                    cat_slug = $this.attr("data-wcpt-group-by-category-slug"),
                    $category_heading = $this.siblings(
                        '.wcpt-row--category-heading[data-wcpt-group-by-category-slug="' +
                            cat_slug +
                            '"]'
                    );
                $this.detach().insertAfter($category_heading);
            });
        }

        wcpt_util.assign_even_odd_row_classes($(".wcpt-table", $wcpt));
    };
})(jQuery);

// module: download csv
(function ($) {
    $("body").on("click", ".wcpt-csv-download", function () {
        var $this = $(this),
            session_key = $this.attr("data-wcpt-csv-session-key"),
            include_all_products = $this.attr(
                "data-wcpt-csv-include-all-products"
            ),
            headings =
                window[$this.attr("data-wcpt-headings-js-var-name")].join(","),
            file_name = $this.attr("data-wcpt-file-name");

        if ($this.hasClass("wcpt-disabled")) {
            return;
        }

        $.ajax({
            url: wcpt_params.wc_ajax_url.replace(
                "%%endpoint%%",
                "wcpt_get_csv"
            ),
            method: "POST",
            beforeSend: function () {
                $this.addClass("wcpt-disabled wcpt-loading");
            },
            data: {
                wcpt_csv_session_key: session_key,
                wcpt_csv_include_all_products: include_all_products
            }
        }).done(function (json_data) {
            $this.removeClass("wcpt-disabled wcpt-loading");
            build_csv_and_download(json_data, headings, file_name);
        });
    });

    function build_csv_and_download(json_data, headings, file_name) {
        var csv = headings + "\n";
        $.each(json_data, function (key, product) {
            $.each(product, function (prop, val) {
                csv += val + ",";
            });
            csv = csv.slice(0, -1) + "\n";
        });

        var $pseudo_link = $("<a>", {
            href: "data:Application/octet-stream," + encodeURIComponent(csv),
            download: file_name + ".csv"
        });

        $pseudo_link.appendTo("body").get(0).click();
        $pseudo_link.remove();
    }
})(jQuery);

// module: infinite scroll

// @TODO: during infinite scroll skip re-printing the navigation section & re-evaluating dynamic filters
(function ($) {
    // controller
    // -- after_every_load handler - attach observer once
    $("body").on("wcpt_after_every_load", ".wcpt", function () {
        var $this__container = $(this),
            sc_attrs = wcpt_util.get_sc_attrs($this__container),
            $infinite_scroll_dots = $(
                ".wcpt-infinite-scroll-dots",
                $this__container
            );

        if (!$infinite_scroll_dots.length) {
            return;
        }

        // intersection observer for load trigger
        wcpt_util.do_once_on_container(
            $this__container,
            "wcpt-infinite-scroll-init",
            function ($container) {
                const intersectionObserver = new IntersectionObserver(
                    entries => {
                        if (entries[0].intersectionRatio <= 0) return;

                        if (
                            !$container.hasClass(
                                "wcpt-loading wcpt-infinite-scroll-loading-results"
                            )
                        ) {
                            $container.trigger("wcpt_infinite_scroll");
                        }
                    },
                    { rootMargin: "100px" }
                );
                // start observing
                intersectionObserver.observe($infinite_scroll_dots[0]);
                $this__container.data(
                    "wcpt-infinite-scroll-intersection-observer",
                    intersectionObserver
                );
            }
        );

        // fresh dots
        var intersection_observer = $this__container.data(
            "wcpt-infinite-scroll-intersection-observer"
        );
        intersection_observer.observe($infinite_scroll_dots[0]);

        // reset paged var in url to 1
        if (!sc_attrs.disable_url_update) {
            var query = $this__container.attr("data-wcpt-query-string");
            if (query) {
                var table_id = wcpt_util.get_table_id($this__container);
                wcpt_util.remove_param_from_url(`${table_id}_paged`);
            }
        }
    });

    // -- a separate trigger 'wcpt_infinite_scroll' that inits infinite scroll on $wcpt
    $("body").on("wcpt_infinite_scroll", ".wcpt", function () {
        var $this__container = $(this);
        append_next_page($this__container);
    });

    // -- helper: handler
    append_next_page = $container => {
        var table_id = wcpt_util.get_table_id($container),
            page_number = wcpt_util.get_current_page_number($container),
            new_query = `${table_id}_paged=${page_number + 1}`,
            append_new_query = true,
            purpose = "infinite_scroll",
            query = window.wcpt_build_ajax_query_string(
                $container,
                new_query,
                append_new_query,
                purpose
            );

        window.wcpt_fetch_markup_and_apply_callback(
            $container,
            query,
            purpose,
            ajax_success__infinite_scroll
        );
        $container.removeClass("wcpt-loading");
        $container.addClass("wcpt-infinite-scroll-loading-results");
    };

    // ajax success handler
    function ajax_success__infinite_scroll(response, $container) {
        var $new_container = $(response),
            $new_rows = $(".wcpt-row", $new_container);
        $(".wcpt-table > tbody", $container).append($new_rows);
        $container.attr(
            "data-wcpt-query-string",
            $new_container.attr("data-wcpt-query-string")
        );
        $container.removeClass("wcpt-infinite-scroll-loading-results");

        // remove infinit scroll dots if results exhausted
        if (!$new_container.find(".wcpt-infinite-scroll-dots").length) {
            $container.find(".wcpt-infinite-scroll-dots").hide();
        }
    }
})(jQuery);

// module: freeze table
(function ($) {
    // @TODO - add init command here as well

    // destroy previous instance before replacing the table
    $("body").on("wcpt_before_ajax_container_replace", (ev, data) => {
        var $freeze_table = wcpt_util.get_freeze_table(data.$container);
        if ($freeze_table) {
            $freeze_table.freezeTable("destroy");
        }
    });

    $("body")
        .on("wcpt-nav-modal-on", function () {
            $(".frzTbl-table").freezeTable("pause");
        })
        .on("wcpt-nav-modal-off", function () {
            $(".frzTbl-table").freezeTable("unpause");
        });
})(jQuery);

/* -- /modules -- */

// module permission
// -- check if disabled
wcpt_is_module_disabled = function (module, $container) {
    var disabled_modules = $container.data("wcpt-disabled-modules");
    if (!disabled_modules) {
        return false;
    }

    var disabled = false;
    jQuery.each(disabled_modules, function (key, val) {
        if (val.module == module) {
            disabled = true;
        }
    });

    return disabled;
};

// -- disable
wcpt_disable_module = function (module, $container, source) {
    var disabled_modules = $container.data("wcpt-disabled-modules");
    if (!disabled_modules) {
        disabled_modules = [];
    }

    disabled_modules.push({
        module: module,
        source: source
    });

    $container.data("wcpt-disabled-modules", disabled_modules);
};

// -- permit
wcpt_permit_module = function (module, $container, source) {
    var disabled_modules = $container.data("wcpt-disabled-modules");
    if (!disabled_modules) {
        disabled_modules = [];
    }

    var i = disabled_modules.length;
    while (i) {
        --i;
        if (
            disabled_modules[i].module == module &&
            disabled_modules[i].source == source
        ) {
            disabled_modules.splice(i, 1);
        }
    }

    $container.data("wcpt-disabled-modules", disabled_modules);
};

// util functions
var wcpt_util;
(function ($) {
    window.wcpt_util = {
        get_table_id: $wcpt => {
            return $wcpt.attr("data-wcpt-table-id");
        },

        get_current_page_number: $wcpt => {
            var params = wcpt_util.get_table_parsed_query($wcpt),
                table_id = wcpt_util.get_table_id($wcpt),
                current_page = params[`${table_id}_paged`]
                    ? params[`${table_id}_paged`]
                    : 1;
            return parseInt(current_page);
        },

        get_table_parsed_query: $wcpt => {
            var query_string = $wcpt.attr("data-wcpt-query-string"),
                _index = query_string.indexOf("?"),
                params =
                    _index == -1
                        ? {}
                        : wcpt_util.parse_query_string(
                              query_string.slice(_index + 1)
                          );

            return params;
        },

        parse_query_string: query => {
            var vars = query.split("&");
            var query_string = {};
            for (var i = 0; i < vars.length; i++) {
                var pair = vars[i].split("="),
                    key = decodeURIComponent(pair[0]),
                    value = decodeURIComponent(pair[1]);

                // If first entry with this name
                if (typeof query_string[key] === "undefined") {
                    query_string[key] = value;

                    // If second entry with this name
                } else if (typeof query_string[key] === "string") {
                    var arr = [query_string[key], value];
                    query_string[key] = arr;

                    // If third or later entry with this name
                } else {
                    query_string[key].push(value);
                }
            }
            return query_string;
        },

        get_sc_attrs: ($container, refresh_cache) => {
            if (!refresh_cache) {
                if ($container.data("wcpt_sc_attrs")) {
                    return $container.data("wcpt_sc_attrs");
                } else {
                    return {};
                }
            }

            var sc_attrs = {};
            var sc_attrs_string = $container.attr("data-wcpt-sc-attrs");
            if (
                sc_attrs_string &&
                -1 == $.inArray(sc_attrs_string, ["[]", "{}"])
            ) {
                sc_attrs = JSON.parse(sc_attrs_string);
            }
            $container.data("wcpt_sc_attrs", sc_attrs); // access sc attrs from here

            return sc_attrs;
        },

        get_device: () => {
            // device
            var device = "laptop"; // default

            if ($(window).width() <= wcpt_params.breakpoints.phone) {
                device = "phone";
            } else if ($(window).width() <= wcpt_params.breakpoints.tablet) {
                device = "tablet";
            }

            return device;
        },

        get_uninit_rows: ($container, refresh_cache) => {
            if (!refresh_cache) {
                return $container.data("wcpt_new_rows");
            }

            var $new_rows = $container.find(".wcpt-row:not(.wcpt-row--init)");
            $container.data("wcpt_new_rows", $new_rows); // access sc attrs from here

            return $new_rows;
        },

        do_once_on_container: ($container, init_class, callback) => {
            if (!$container.hasClass(init_class)) {
                callback($container);
                $container.addClass(init_class);
            }
        },

        get_freeze_table: $container => {
            var $freeze_table = false;
            $container.find(".frzTbl-table").each(function () {
                var $this = $(this);
                if ($this.data("freezeTable")) {
                    $freeze_table = $this;
                }
            });
            return $freeze_table;
        },

        assign_even_odd_row_classes: $table => {
            var $rows = $(
                    ".wcpt-row:not(.wcpt-child-row, .wcpt-row--category-heading):visible",
                    $table
                ),
                $child_rows = $(".wcpt-child-row", $table);

            $rows.each(function () {
                var $this = $(this);

                if ($rows.index($this) % 2) {
                    $this.addClass("wcpt-even").removeClass("wcpt-odd");
                } else {
                    $this.addClass("wcpt-odd").removeClass("wcpt-even");
                }
            });

            $child_rows.each(function () {
                var $this = $(this),
                    $parent_row = $this.data("wcpt_parent_row");

                if ($parent_row.hasClass("wcpt-even")) {
                    $this.removeClass("wcpt-odd").addClass("wcpt-even");
                } else {
                    // odd
                    $this.removeClass("wcpt-even").addClass("wcpt-odd");
                }
            });
        },

        update_url_by_container: $container => {
            var sc_attrs = wcpt_util.get_sc_attrs($container);

            if (!sc_attrs.disable_url_update) {
                var query = $container.attr("data-wcpt-query-string");
                wcpt_util.update_url_by_query(query);
            }
        },

        update_url_by_query: query => {
            if (typeof window.history !== "undefined") {
                // remove unnecessary params from url
                var search_params_object = new URLSearchParams(query);
                search_params_object.forEach(function (val, key) {
                    // -- device
                    if (key.slice(-7) == "_device") {
                        search_params_object.delete(key);
                    }

                    // -- paged=1
                    if (key.slice(-6) == "_paged" && val == "1") {
                        search_params_object.delete(key);
                    }
                });

                // append param to url
                query = search_params_object.toString()
                    ? "?" + search_params_object.toString()
                    : window.location.pathname;
                history.replaceState({}, "", query);
            }
        },

        remove_param_from_url: param => {
            if (
                param &&
                typeof window.history !== "undefined" &&
                window.location.search
            ) {
                var url_object = new URL(window.location);
                url_object.searchParams.delete(param);
                history.replaceState({}, "", url_object.href);
            }
        }
    };
})(jQuery);
