"use strict";
!(function ($) {
    var wready_product_compare = {
        modal_body: $("#woo-ready-product-compare .wready-md-body"),
        wready_products_obj_key: "wready_compare_products_obj",
        wready_products_id_key: "wready_compare_products",
        products_obj: [],
        init: function () {
            $(document.body).on("click", ".wready-product-compare", this.compare_popup_open),
                $(document.body).on("click", ".shop-ready-remove-product a", this.compare_remove_widget_item),
                $(document).on("click", ".e-show-coupon-form", this.default_coupon),
                this.product_newslatter();
        },
        product_newslatter: function () {
            if ("undefined" != typeof newslatter_service && newslatter_service.active) {
                var $newslatter_popup = $("#shop-ready-pro-sr-newslatter-popup-modal"),
                    $autoclose_time = parseInt(newslatter_service.autoclose_time) || 0,
                    $load_after_time = parseInt(newslatter_service.load_after_time) || 1e3;
                setTimeout(function () {
                    $newslatter_popup.nifty("show"), $newslatter_popup.addClass("opened-newslatter");
                }, $load_after_time),
                    "yes" == newslatter_service.auto_close &&
                    $autoclose_time > 0 &&
                    setTimeout(function () {
                        $newslatter_popup.nifty("hide");
                    }, $autoclose_time);
            }
        },
        default_coupon: function (e) {
            e.preventDefault(), $(".woocommerce-form-coupon").toggle("slow"), $(".e-coupon-anchor").toggle("slow");
        },
        compare_remove_widget_item: function (event) {
            event.preventDefault();
            let product_id = parseInt($(this).data("product_id")),
                product_objs = null != localStorage.getItem("wready_compare_products_obj") ? JSON.parse(localStorage.getItem("wready_compare_products_obj")) : [],
                compare_products = null != localStorage.getItem("wready_compare_products") ? JSON.parse(localStorage.getItem("wready_compare_products")) : [];
            (product_objs.products = _.filter(product_objs.products, function (item) {
                return item.id !== product_id;
            })),
                (compare_products = _.reject(compare_products, function (num) {
                    return num == product_id;
                })),
                localStorage.setItem("wready_compare_products_obj", JSON.stringify(product_objs)),
                localStorage.setItem("wready_compare_products", JSON.stringify(compare_products)),
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: wp.ajax.settings.url,
                    data: { action: "wready_compare_products", posts_id: JSON.stringify(compare_products) },
                    success: function (wmsg) {
                        window.location.reload();
                    },
                });
        },
        compare_popup_open: function (event) {
            let prodduct_id = parseInt($(this).data("product_id"));
            return $("#woo-ready-product-compare").nifty("show"), wready_product_compare.add_comare_busket(prodduct_id), !1;
        },
        add_inner_preload_html: function (msg) {
            var genereted_template = wp.template("shop-ready-pro-product-direct-html-compare");
            wready_product_compare.modal_body.html(genereted_template(wready_product_compare.products_obj));
        },
        add_inner_html: function (msg) {
            var template = void 0 !== wready_product_compare.products_obj.template && wready_product_compare.products_obj.template,
                product_objs = JSON.parse(localStorage.getItem("wready_compare_products_obj"));
            if (!template) {
                product_objs.filled = msg;
                var template = wp.template("wready-product-compare");
                wready_product_compare.modal_body.html(template(product_objs));
            }
            setTimeout(() => {
                $(".wready-cmp-table-msg").fadeOut();
            }, 3e3);
        },
        add_comare_busket: function (_wready_product_id) {
            if ("undefined" != typeof localStorage) {
                let compare_products = null != localStorage.getItem("wready_compare_products") ? JSON.parse(localStorage.getItem("wready_compare_products")) : [];
                compare_products.push(_wready_product_id),
                    localStorage.setItem("wready_compare_products", JSON.stringify(compare_products)),
                    void 0 !== wp.ajax.settings.url &&
                    (wready_product_compare.add_inner_preload_html("Add To Basket"),
                        $(".woo-ready-product-compare-modal").addClass("sr-compare-overly-preloading"),
                        $.ajax({
                            type: "post",
                            dataType: "json",
                            url: wp.ajax.settings.url,
                            data: { action: "wready_compare_products", posts_id: JSON.stringify(compare_products) },
                            success: function (wmsg) {
                                (wready_product_compare.products_obj = wmsg.data), localStorage.setItem("wready_compare_products_obj", JSON.stringify(wmsg.data));
                                var iframe = $("<iframe></iframe>");
                                iframe.attr("src", wmsg.data.iframe_content_url),
                                    iframe
                                        .hide()
                                        .on("load", function () {
                                            $(".woo-ready-product-compare-modal").removeClass("sr-compare-overly-preloading");
                                        })
                                        .fadeIn("2500"),
                                    wready_product_compare.modal_body.html(iframe);
                            },
                        }));
            }
        },
    };
    wready_product_compare.init();
    var wready_product_wishlist = {
        modal_body: $("#woo-ready-product-wishlist .wready-md-body"),
        init: function () {
            $(document.body).on("click", ".wready-product-wishlist", this.wishlist_popup_open),
                $(document.body).on("click", ".wready-product-wishlist-only", this.Open_wish_list),
                $(document.body).on("click", ".wready-product-wishlist-remove", this.wishlist_popup_remove_item);
        },
        wishlist_popup_open: function (e) {
            e.preventDefault();
            let product_id = parseInt($(this).data("product_id"));
            var wishlist_container;
            return $("#woo-ready-product-wishlist").nifty("show"), $("#woo-ready-product-wishlist").find(".wready-md-body").html(""), wready_product_wishlist.add_wishlist_busket(product_id), !1;
        },
        add_wishlist_busket: function (_wready_product_id) {
            var wishlist_container = $("#woo-ready-product-wishlist");
            wishlist_container.addClass("woo-ready-product-wishlist-modal-overlay"),
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: wp.ajax.settings.url,
                    data: { action: "shop_ready_wishlist_products", post_id: _wready_product_id },
                    success: function (wmsg) {
                        if (wmsg.success) {
                            var iframe = $("<iframe></iframe>");
                            iframe.attr("src", wmsg.data.html_content_iframe_url),
                                iframe
                                    .on("load", function () {
                                        wishlist_container.removeClass("woo-ready-product-wishlist-modal-overlay");
                                    })
                                    .fadeIn(2500),
                                wishlist_container.find(".wready-md-body").html(iframe);
                        } else wishlist_container.removeClass("woo-ready-product-wishlist-modal-overlay");
                    },
                });
        },
        wishlist_popup_remove_item: function (event) {
            event.preventDefault();
            let product_id = parseInt($(this).data("product_id")),
                this_element = $(this);
            $(".shop-ready-wishlist-common-identifier-widget").addClass("shop-wishlist-overlay"),
                $(".wishlist-product-grid").addClass("shop-wishlist-overlay"),
                console.log(product_id),
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: wp.ajax.settings.url,
                    data: { action: "shop_ready_wishlist_products_remove", product_id: product_id },
                    success: function (wmsg) {
                        $(".shop-ready-wishlist-common-identifier-widget").removeClass("shop-wishlist-overlay"),
                            $(".wishlist-product-grid").removeClass("shop-wishlist-overlay"),
                            wmsg.success && (this_element.parent().parent("tr").remove(), this_element.parent().parent().parent(".wooready_product_components").remove());
                    },
                });
        },
        Open_wish_list: function () {
            $("#woo-ready-product-wishlist").nifty("show");
            var wishlist_container = $("#woo-ready-product-wishlist");
            wishlist_container.addClass("woo-ready-product-wishlist-modal-overlay"),
                wishlist_container.find(".wready-md-body").html(""),
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: wp.ajax.settings.url,
                    data: { action: "shop_ready_wishlist_products_open" },
                    success: function (wmsg) {
                        if (wmsg.success) {
                            var iframe = $("<iframe></iframe>");
                            iframe.attr("src", wmsg.data.html_content_iframe_url),
                                iframe
                                    .hide()
                                    .on("load", function () {
                                        $("#woo-ready-product-wishlist").removeClass("woo-ready-product-wishlist-modal-overlay");
                                    })
                                    .fadeIn("2500"),
                                wishlist_container.find(".wready-md-body").html(iframe);
                        }
                    },
                });
        },
    };
    wready_product_wishlist.init();
    var wready_product_quickview = {
        modal_body: $("#woo-ready-product-quickview .wready-md-body"),
        wready_products_obj_key: "wready_quickview_products_obj",
        wready_products_id_key: "wready_quickview_products",
        products_obj: [],
        image_slide_on_hover: !0,
        init: function () {
            $(document.body).on("click", ".wready-product-quickview", this.quickview_popup_open);
        },
        quickview_popup_open: function (event) {
            $(".woo-ready-product-quickview-modal .woo-ready-product-quickview-content").addClass("woo-ready-product-quickview-modal-preloader-overlay");
            let product_id = parseInt($(this).data("product_id"));
            return $("#woo-ready-product-quickview").nifty("show"), wready_product_quickview.get_product_content(product_id), !1;
        },
        get_product_content: function (_wready_product_id) {
            void 0 !== wp.ajax.settings.url &&
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: wp.ajax.settings.url,
                    data: { action: "wready_quickview_products", post_id: _wready_product_id },
                    success: function (wmsg) {
                        var iframe = $("<iframe></iframe>");
                        iframe.attr("src", wmsg.data.template_content_url),
                            iframe
                                .hide()
                                .on("load", function () {
                                    $(".woo-ready-product-quickview-modal .woo-ready-product-quickview-content").removeClass("woo-ready-product-quickview-modal-preloader-overlay");
                                })
                                .fadeIn("2500"),


                            console.log(iframe)
                        wready_product_quickview.modal_body.html(iframe);
                    },
                });
        },
    };
    wready_product_quickview.init(),
        $(document).on("change", ".variation-radios input", function () {
            $(".variation-radios input:checked").each(function (index, element) {
                var $el = $(element),
                    thisName = $el.attr("name"),
                    thisVal = $el.attr("value");
                $('select[name="' + thisName + '"]')
                    .val(thisVal)
                    .trigger("change");
            });
        }),
        $(document).on("woocommerce_update_variation_values", function () {
            $(".variation-radios input").each(function (index, element) {
                var $el = $(element),
                    thisName = $el.attr("name"),
                    thisVal = $el.attr("value");
                $el.removeAttr("disabled"), $('select[name="' + thisName + '"] option[value="' + thisVal + '"]').is(":disabled") && $el.prop("disabled", !0);
            });
        }),
        $(document).on("wc_cart_button_updated", function () {
            $(".wready-product-loop-cart-link .ajax_add_to_cart.added").html(" added ");
        }),
        document.querySelectorAll(".elementor-widget .woo-ready-ele-widget-container").forEach(function (element, i) {
            element.hasChildNodes() || ("" === element.textContent.trim() && (element.closest(".elementor-widget").style.display = "none"));
        }),
        $(document).on("click", ".woo-ready-qty-add", function () {
            let prev_input = parseInt($(this).prev().val()) || 0;
            $(this)
                .prev()
                .val(prev_input + 1)
                .trigger("change");
        }),
        $(document).on("click", ".woo-ready-qty-sub", function () {
            let next_input = parseInt($(this).next().val()) || 0;
            next_input > 1 &&
                next_input > 1 &&
                $(this)
                    .next()
                    .val(next_input - 1)
                    .trigger("change");
        }),
        $(document).on("click", ".shop-ready-mini-cart-update-button", function (event) {
            event.preventDefault();
            let store_sr_mini_cart_keys = [];
            var _this_elemet;
            let cart_keys = $(this).parents(".woo-ready-mini-cart-container").find("input.qty");
            $(".woo-ready-mini-cart-container.ajax-fragemnt").addClass("shop-ready-content-overlay-area"),
                cart_keys.each(function (index) {
                    var sr__cart_keys = {};
                    (sr__cart_keys.key = $(this).attr("item_key")), (sr__cart_keys.value = $(this).val()), store_sr_mini_cart_keys.push(sr__cart_keys);
                }),
                jQuery.ajax({
                    type: "POST",
                    url: wp.ajax.settings.url,
                    data: { action: "shop_ready_update_mini_cart_item", sr_cart_item_keys: store_sr_mini_cart_keys },
                    success: function (response) {
                        $(document.body).trigger("added_to_cart", [response.fragments, response.cart_hash]),
                            $(document.body).trigger("wc_fragment_refresh"),
                            $(".woo-ready-mini-cart-container.ajax-fragemnt").removeClass("shop-ready-content-overlay-area");
                    },
                });
        }),
        $(document).on("click", ".remove_from_cart_button", function () {
            $(".woo-ready-mini-cart-container").append('<div class="wr-random-overlay"></div>');
        });
    var wready_shopping_cart = {
        init: function () {
            $(document.body).on("click", ".ajax_add_to_cart ", this.cart_checkout_update),
                $(document.body).on("click", ".single_add_to_cart_button", this.product_details_cart_checkout_update),
                $(document.body).on("click", ".woo-ready-mini-cart-item .remove", this.product_remove_from_cart),
                $(document.body).on("click", ".wr-checkout-cart-table .product-remove .remove", this.product_remove_from_cart);
        },
        shop_ready_fly_to_element: function (flyer, flyingTo, add_to_cart) {
            var flyerClone = $(flyer).clone();
            $(flyerClone).css({ position: "absolute", top: add_to_cart.offset().top - 20 + "px", left: add_to_cart.offset().left + 15 + "px", opacity: 1, "z-index": 588000055 }), $("body").append($(flyerClone));
            var gotoX = $(flyingTo).offset().left,
                gotoY = $(flyingTo).offset().top;
            $(flyerClone).animate({ opacity: parseFloat(shop_ready_obj.animation_opacity), left: gotoX, top: gotoY }, shop_ready_obj.custom_animation_time, function () {
                $(flyingTo).addClass("shop-raedy-shopping-cart-added-shake"),
                    $(flyingTo).fadeOut("fast", function () {
                        $(flyingTo).fadeIn("fast", function () {
                            $(flyerClone).fadeOut("fast", function () {
                                $(flyerClone).remove(), $(flyingTo).removeClass("shop-raedy-shopping-cart-added-shake");
                            });
                        });
                    });
            });
        },
        flying_cart_animation_style: function (add_to_cart) {
            if ("yes" == shop_ready_obj.add_to_cart_animation) {
                var widget_area = "yes" == shop_ready_obj.animation_custom_area ? shop_ready_obj.cart_custom_area_cls : shop_ready_obj.animation_widget_area;
                if ($("." + widget_area).length || $("#" + widget_area).length) {
                    var flying_content =
                        '<span class="shop-ready-add-to-cart-animation-content" style="width:40px; height:40px; background:black; border-radius:100%; line-height:1; color:#fff;padding:10px">' +
                        shop_ready_obj.animation_icon_content +
                        "</span>";
                    wready_shopping_cart.shop_ready_fly_to_element($(flying_content), "." + widget_area, add_to_cart);
                }
            }
        },
        cart_checkout_update: function (event) {
            event.preventDefault();
            var __that_obj = $(this),
                p_id = $(this).attr("data-product_id"),
                formData = [];
            formData.push({ name: "product_id", value: p_id }),
                $(document.body).trigger("adding_to_cart", [formData]),
                (__that_obj.hasClass("ajax_add_to_cart") || __that_obj.hasClass("add_to_cart_button")) && wready_shopping_cart.flying_cart_animation_style(__that_obj),
                $.ajax({
                    type: "POST",
                    url: woocommerce_params.wc_ajax_url.toString().replace("%%endpoint%%", "add_to_cart"),
                    data: formData,
                    success: function (response) {
                        response && ($("body").trigger("update_checkout"), $(document.body).trigger("added_to_cart", [response.fragments, response.cart_hash]));
                    },
                    complete: function () { },
                });
        },
        product_remove_from_cart: function (event) {
            event.preventDefault();
            var _this_elemet = $(this);
            $(".woo-ready-mini-cart-container.ajax-fragemnt").addClass("shop-ready-content-overlay-area"),
                $.ajax({
                    type: "POST",
                    url: wp.ajax.settings.url,
                    data: { action: "remove_item_from_cart", cart_item_key: $(this).data("cart_item_key") },
                    success: function (response) {
                        response && (_this_elemet.parents("tr").remove(), $(document.body).trigger("removed_from_cart", [response.fragments, response.cart_hash, $(this)]), $("body").trigger("update_checkout"));
                    },
                });
        },
        product_details_cart_checkout_update: function (event) {
            if ("yes" == sr_woo_obj.ajax_add_to_cart_enable) {
                event.preventDefault();
                var $thisbutton = $(this),
                    p_id = $thisbutton.val(),
                    $form = $thisbutton.closest("form.cart"),
                    product_qty = $form.find("input[name=quantity]").val(),
                    variation_id = parseInt($form.find("[name='variation_id']").val()) || 0,
                    product_id,
                    form_data,
                    data = { action: "woocommerce_ajax_add_to_cart", product_id: $form.find("input[name=product_id]").val() || p_id, product_sku: "", quantity: product_qty, variation_id: variation_id };
                (data = $(this).parent().serialize() + "&" + $.param(data)),
                    $(document.body).trigger("adding_to_cart", [$thisbutton, data]),
                    $.ajax({
                        type: "POST",
                        url: woocommerce_params.wc_ajax_url.toString().replace("%%endpoint%%", "add_to_cart"),
                        data: data,
                        success: function (response) {
                            response && ($("body").trigger("update_checkout"), $(document.body).trigger("added_to_cart", [response.fragments, response.cart_hash, $thisbutton]), $(".woocommerce-product-page-notice-wrapper").show());
                        },
                        complete: function () { },
                    });
            }
        },
    };
    wready_shopping_cart.init();
    var wready_shop_filter = {
        request_val: { page_number: 1, page_count: 10, sr_attributes: !1, attribute_filter: !1, sr_category: [], orderby: "ASC", sr_meta_orderby: "", min_price: 0, max_price: 1e6 },
        init: function () {
            if ($(".shop-ready-ajax-product-wrapper").length) {
                let page_count = 10;
                (page_count = parseInt($(".shop-ready-ajax-product-wrapper").attr("data-page-count"))), (wready_shop_filter.request_val.page_count = page_count > 0 ? page_count : 6);
            }
            "undefined" != typeof shop_ready_shop_filter_obj &&
                shop_ready_shop_filter_obj.active &&
                ($(".shop-ready-widget-rating-filter li a").on("click", this.rating_filter),
                    $(document).on("change", ".shop-ready--grid-ordering select", this.catelog_ordering),
                    $(".woo-ready-attribute-filter input[type=checkbox], .shop-ready-filter-attribute-single").on("click", this.attribute_filter),
                    $(".shop-ready-sidebar-category-filter-js .cat-item input").on("click", this.product_category_filter),
                    $(document).on("click", ".shop-ready-auto-product-load .shop-ready-mod-lbtn-js", this.loadmore_btn),
                    $(document.body).on("price_slider_change", this.price_filter_update));
        },
        loadmore_btn: function (e) {
            e.preventDefault();
            let button = $(this),
                $current_page = parseInt(button.attr("current"));
            return (wready_shop_filter.request_val.page_number = ++$current_page), wready_shop_filter.fetch_grid(), !1;
        },
        loadmore_pagination: function (e) {
            let current_page;
            e.preventDefault();
            const urlParams = $(this).attr("href").split("?product-page=");
            return void 0 !== urlParams[1] && (wready_shop_filter.request_val.page_number = parseInt(urlParams[1])), wready_shop_filter.fetch_grid(), !1;
        },
        product_category_filter: function (event) {
            event.preventDefault(), (wready_shop_filter.request_val.page_number = 1);
            let cats_list = [];
            var __this_cat_li = $(this).parent("li");
            __this_cat_li.hasClass("chosen")
                ? (__this_cat_li.removeClass("chosen"), $(this).removeAttr("checked"), $(this).removeAttr("data-selected"))
                : (__this_cat_li.addClass("chosen"), $(this).attr("checked", "checked"), $(this).attr("data-selected", "yes"));
            let _all_cat_elements = $(".shop-ready-sidebar-category-filter-js .cat-item.chosen");
            return (
                _all_cat_elements.length
                    ? (_all_cat_elements.each(function (index) {
                        cats_list.push($(this).find("input").val());
                    }),
                        (wready_shop_filter.request_val.sr_category = cats_list))
                    : delete wready_shop_filter.request_val.sr_category,
                wready_shop_filter.fetch_grid(),
                !1
            );
        },
        rating_filter: function (event) {
            event.preventDefault();
            var rating_count = [];
            wready_shop_filter.request_val.page_number = 1;
            var __this_rating_li = $(this).parent();
            __this_rating_li.hasClass("chosen") ? __this_rating_li.removeClass("chosen") : __this_rating_li.addClass("chosen");
            let all_elements = $(".wc-layered-nav-rating.chosen");
            all_elements.length
                ? (all_elements.each(function (index) {
                    rating_count.push($(this).attr("data-rating_count"));
                }),
                    (wready_shop_filter.request_val.rating_filter = rating_count.join(",")))
                : delete wready_shop_filter.request_val.rating_filter,
                wready_shop_filter.fetch_grid();
        },
        price_filter_update: function (event, min, max) {
            (wready_shop_filter.request_val.min_price = min), (wready_shop_filter.request_val.max_price = max), wready_shop_filter.fetch_grid();
        },
        catelog_ordering: function (e) {
            return e.preventDefault(), (wready_shop_filter.request_val.page_number = 1), (wready_shop_filter.request_val.orderby = $(this).val()), wready_shop_filter.fetch_grid(), !1;
        },
        attribute_filter: function (event) {
            return (
                event.preventDefault(),
                (wready_shop_filter.request_val.page_number = 1),
                "checked" == $(this).attr("checked") ? ($(this).removeAttr("checked"), $(this).removeAttr("data-selected")) : ($(this).attr("checked", "checked"), $(this).attr("data-selected", "yes")),
                wready_shop_filter.fetch_grid(),
                !1
            );
        },
        get_all_selected_attr() {
            let all_elements = $(".shop-ready-filter-attribute-single"),
                selected_attr = [];
            if (all_elements.length) {
                all_elements.each(function (index) {
                    var _attr_type = $(this).attr("data-type"),
                        _attr_selected = $(this).attr("data-selected"),
                        _attr_taxonomy = $(this).attr("data-taxonomy");
                    "yes" == _attr_selected && selected_attr.push({ type: _attr_type, value: _attr_taxonomy });
                });
                var groups = _.groupBy(selected_attr, "type");
                wready_shop_filter.request_val.sr_attributes = groups;
            }
        },
        fetch_grid: function () {
            wready_shop_filter.get_all_selected_attr(), $(".woo-ready-products").addClass("shop-reay-pro-grid-overlay");
            var formData = wready_shop_filter.request_val;
            (formData.action = "shop_ready_shop_product_refresh_content"),
                $(".shop-ready-shop-grid").addClass("shop-ready-shop-grid-overlay"),
                $.ajax({
                    type: "GET",
                    url: shop_ready_shop_filter_obj.ajax_url,
                    data: formData,
                    success: function (response) {
                        response && $(".shop-ready-ajax-product-wrapper").html(response.woo_ready_products);
                    },
                    complete: function () { },
                });
        },
    };
    wready_shop_filter.init();
})(jQuery);
