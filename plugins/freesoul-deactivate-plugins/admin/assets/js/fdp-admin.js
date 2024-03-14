if (!window.jQuery)
    document.body.className += ' fdp-no-jquery';
function eos_dp_go_to_item() {
    void 0 !== window.fdp_backend_item && jQuery(".fdp-admin-menu-title").each(function() {
        if (window.fdp_backend_item === jQuery(this).text()) {
            window.location.href = window.location.href + "&item=" + encodeURI(window.fdp_backend_item);
            return
        }
    })
}
function eos_dp_update_chks(e) {
    e.first().is(":checked") ? e.closest("td").removeClass("eos-dp-active") : e.closest("td").addClass("eos-dp-active")
}
function eos_move_table_head() {
    if (!(null === table_head || 1 > parseInt(eos_dp_js.plugins_n)) && table && ("undefined" == typeof is_single_post || !0 !== is_single_post)) {
        var e = void 0 !== window.fdp_delta ? window.fdp_delta : -999999999
          , s = !!table && table.getElementsByTagName("tr");
        if (window.fdp_delta = parseInt(eos_dp_js.plugins_n) > 1 && s && s.length > 0 ? s[s.length - 1].getBoundingClientRect().top - firstRowTop : 0,
        direction = window.fdp_delta > e ? "up" : "down",
        table_head.className = table_head.className.replace("fdp-going-up", "").replace("fdp-going-down", "") + "fdp-going-" + direction,
        window.scrollY > 150) {
            window.fdp_tmoved = !0;
            var t = document.getElementsByClassName("eos-dp-post-row")[0].getElementsByTagName("td")[0].clientWidth + 2.5
              , t = "1" !== eos_dp_js.is_rtl ? t : -t;
            table_head.style.transform = "translateX(" + t + "px)",
            table.className = "fixed",
            eos_dpBody[0].className = eos_dpBody[0].className.replace(" fdp-table-fixed", "").replace("fdp-table-fixed", "") + " fdp-table-fixed";
            return
        }
        window.scrollY < 100 && void 0 !== window.fdp_tmoved && setTimeout(function() {
            if (window.scrollY < 80) {
                table.className = "",
                table_head.style.transform = "none",
                eos_dpBody[0].className = eos_dpBody[0].className.replace(" fdp-table-fixed", "").replace("fdp-table-fixed", ""),
                window.scrollTo(0, 0);
                return
            }
        }, 200)
    }
}
function eos_dp_update_chk_wrp(e, s) {
    !0 === s ? e.parent().removeClass("eos-dp-active-wrp").addClass("eos-dp-not-active-wrp") : e.parent().addClass("eos-dp-active-wrp").removeClass("eos-dp-not-active-wrp")
}
function eos_dp_go_to_post_type(e) {
    var s = jQuery("#eos-dp-setts.fixed").length < 1 ? parseInt(jQuery("#eos-dp-setts").offset().top) : 0;
    jQuery(".eos-dp-post-name").each(function() {
        if (jQuery(this).text().toLowerCase().split(" ").join("-") === e.toLowerCase().split(" ").join("-")) {
            var t = jQuery(this).closest(".eos-dp-filters-table");
            return void 0 !== t && jQuery("html,body").animate({
                scrollTop: parseInt(t.offset().top) - s - 40
            }, 500),
            !1
        }
    })
}
function eos_dp_posts_href(e, s) {
    var t = ""
      , o = document.getElementById("eos-dp-device");
    return o && (t = "&device=" + o.value),
    window.location.href.split("?")[0] + "?page=eos_dp_menu&eos_dp_post_type=" + s + "&orderby=" + document.getElementById("eos-dp-orderby-sel").value + "&order=" + document.getElementById("eos-dp-order-sel").value + "&posts_per_page=" + document.getElementById("eos-dp-posts-per-page").value + t
}
function eos_dp_set_horizontal_cell_width() {
    var e = (jQuery(".eos-dp-section").width() - jQuery(".eos-dp-post-name-wrp").outerWidth()) / plugsN - 10;
    jQuery(".eos-dp-horizontal .eos-dp-td-chk-wrp,.eos-dp-horizontal .eos-dp-plugin-name").css("width", e + "px")
}
function eos_dp_clone_row_options(e, s) {
    var t = eos_dp_row2setts(e);
    eos_dp_paste_setts(t, s)
}
function eos_dp_row2setts(e) {
    var s = [];
    return e.find(".eos-dp-td-chk-wrp").closest("td").each(function() {
        s.push(!jQuery(this).hasClass("eos-dp-active"))
    }),
    s
}
function eos_dp_paste_setts(e, s) {
    var t = s.find(".eos-dp-td-chk-wrp input");
    e.length === t.length && t.each(function(s, t) {
        var o = jQuery(t).closest("td")
          , a = jQuery(t);
        e[s] ? (o.removeClass("eos-dp-active"),
        a.attr("checked", !0)) : (o.addClass("eos-dp-active"),
        a.attr("checked", !1))
    })
}
function eos_dp_paste_last_copied_setts(e) {
    if (void 0 !== window.eos_dp_last_copied_row)
        var s = window.eos_dp_last_copied_row;
    else {
        var s = localStorage.getItem("eos_dp_last_copied_row");
        s && "" !== s && (s = JSON.parse(s))
    }
    s && eos_dp_paste_setts(s, e)
}
function eos_dp_send_ajax(e, s) {
    var t = e.next(".ajax-loader-img");
    t.removeClass("eos-not-visible"),
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: s,
        success: function(s) {
            t.addClass("eos-not-visible"),
            jQuery(".eos-dp-section table").removeClass("eos-dp-progress"),
            e.removeClass("eos-dp-progress"),
            1 == parseInt(s) ? jQuery(".eos-dp-opts-msg_success").removeClass("eos-hidden") : eos_dp_show_errors(s)
        }
    })
}
function eos_dp_send_ajax_popup(e, s) {
    var t = jQuery(e).closest("td");
    t.addClass("eos-dp-progress"),
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: s,
        success: function(s) {
            if (s) {
                var o = "";
                if ("" !== s)
                    try {
                        if (0 === s.indexOf("error-"))
                            o += s.substring(6, s.length),
                            jQuery("#eos-dp-popup-page-link").attr("href", e.dataset.url);
                        else {
                            var a = jQuery.parseJSON(s)
                              , d = a.disabled
                              , i = a.eos_dp_debug
                              , p = 0
                              , n = "log"
                              , r = ""
                              , l = [];
                            for (p in o += "<p>URL: " + a.url + "</p><br/>",
                            d.length > 0 ? o += "<p><strong>The following plugins are disabled:</strong></p>" : o += "<p><strong>No Plugins are disabled:</strong></p>",
                            d)
                                o += "<p>" + d[p] + "</p>";
                            for (n in o += "<p>----------------</p>",
                            i)
                                for (r in l = i[n])
                                    o += '<p class="eos-dp-' + n + '">' + l[r] + "</p>";
                            jQuery("#eos-dp-popup-page-link").attr("href", a.url)
                        }
                    } catch (c) {
                        o += c.message
                    }
                jQuery("#eos-dp-popup-txt").html(o),
                jQuery("#eos-dp-popup").show(),
                "function" == typeof jQuery.fn.draggable && jQuery("#eos-dp-popup").css("cursor", "move").draggable({
                    cursorAt: {
                        top: 50,
                        left: 50
                    }
                })
            }
            t.removeClass("eos-dp-progress"),
            jQuery(".eos-dp-debug").removeClass("eos-dp-progress")
        }
    })
}
function eos_dp_show_errors(e) {
    "0" !== e && "" !== e ? (jQuery(".eos-dp-opts-msg_warning").text(e),
    jQuery(".eos-dp-opts-msg_warning").removeClass("eos-hidden")) : jQuery(".eos-dp-opts-msg_failed").removeClass("eos-hidden")
}
function eos_dp_send_autosuggest_request(e, s) {
    jQuery(".eos-dp-plugin-name").slice(4 * window.eos_dp_autosuggest_counter, 4 * window.eos_dp_autosuggest_counter + 4).addClass("fdp-plugin-in-check"),
    jQuery(".eos-dp-name-th").slice(4 * window.eos_dp_autosuggest_counter, 4 * window.eos_dp_autosuggest_counter + 4).addClass("eos-dp-plugin-hover"),
    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: s,
        success: function(t) {
            if (jQuery(".eos-dp-plugin-name").removeClass("fdp-plugin-in-check"),
            jQuery(".eos-dp-name-th").removeClass("eos-dp-plugin-hover"),
            ++window.eos_dp_autosuggest_counter,
            "" !== t) {
                if (jQuery("#eos-dp-autosuggest-msg").addClass("eos-hidden"),
                "error" === t)
                    return jQuery("#eos-dp-autosuggest-msg-error").removeClass("eos-hidden"),
                    jQuery(".eos-dp-autochecked").removeClass("eos-dp-autochecked"),
                    jQuery(".eos-dp-plugin-name").removeClass("fdp-plugin-in-check"),
                    e.closest("tr").removeClass("eos-test-in-progress").removeClass("eos-active-test").closest("table").removeClass("eos-dp-progress").next(".ajax-loader-img").addClass("eos-hidden").addClass("eos-not-visible"),
                    !1;
                json = jQuery.parseJSON(t);
                var o = "undefined" != typeof is_single_post && is_single_post ? jQuery(".eos-dp-post-row") : e.closest(".eos-dp-post-row")
                  , a = "";
                if (o.find("input[type=checkbox]").filter(":visible").not(".eos-dp-global-chk-row").not(".eos-dp-lock-post").each(function(e, s) {
                    e + 1 > 4 * (window.eos_dp_autosuggest_counter - 1) && e < 4 * (window.eos_dp_autosuggest_counter - 1) + 4 && e < jQuery(".eos-dp-name-th").length + 1 && (a = (chk = jQuery(this)).closest("td").attr("data-path"),
                    json.indexOf(a) > -1 ? chk.attr("checked", 1).closest("td").addClass("eos-dp-autochecked").removeClass("eos-dp-active").trigger("change") : chk.removeAttr("checked").closest("td").addClass("eos-dp-autochecked").addClass("eos-dp-active").trigger("change"),
                    chk.trigger("change"))
                }),
                void 0 !== eos_dp_js.page && "eos_dp_admin" === eos_dp_js.page) {
                    var o = e.closest("tr");
                    jQuery('a.eos-dp-title[href="' + o.find(".eos-dp-title").attr("href") + '"]').each(function() {
                        eos_dp_clone_row_options(o, jQuery(this).closest("tr"))
                    })
                }
                if (s.offset = 4 * window.eos_dp_autosuggest_counter,
                parseInt(window.eos_dp_autosuggest_counter) < Math.ceil(jQuery(".eos-dp-name-th").length / 4))
                    eos_dp_send_autosuggest_request(e, s);
                else {
                    if ("eos_dp_admin" !== eos_dp_js.page && ("undefined" != typeof is_single_post ? jQuery("#eos-dp-lock-single-post").addClass("eos-post-locked") : o.addClass("eos-post-locked")),
                    s.stop = "1",
                    jQuery(".eos-dp-section table").removeClass("eos-dp-progress"),
                    jQuery(".eos-dp-autochecked").removeClass("eos-dp-autochecked"),
                    e.closest("tr").removeClass("eos-test-in-progress").removeClass("eos-active-test").closest("table").removeClass("eos-dp-progress").next(".ajax-loader-img").addClass("eos-hidden").addClass("eos-not-visible"),
                    o.addClass("eos-post-locked"),
                    void 0 !== window.eos_dp_actual_row_in_progress && null !== window.eos_dp_actual_row_in_progress) {
                        ++window.eos_dp_actual_row_in_progress;
                        var d = "eos_dp_admin" !== eos_dp_js.page ? o.next().find(".eos-dp-pro-autosettings") : o.nextAll(".eos-dp-admin-row").not(".fdp-edit-single").first().find(".eos-dp-pro-autosettings");
                        d.length > 0 ? (d.trigger("click"),
                        window.eos_dp_autosuggest_counter = -1) : window.eos_dp_actual_row_in_progress = null
                    }
                    return !1
                }
            }
        }
    })
}
function fdp_suggest_plugins(e, s) {
    var t = new XMLHttpRequest
      , o = jQuery(e).closest("tr")
      , a = new FormData
      , d = void 0 !== eos_dp_js.page && "eos_dp_admin" === eos_dp_js.page ? document.getElementById("eos_dp_pro_auto_settings_admin").value : document.getElementById("eos_dp_pro_auto_settings").value;
    s.counter = window.eos_dp_autosuggest_counter,
    o.find('td[data-path="' + s.plugins.split(",")[window.eos_dp_autosuggest_counter] + '"]').addClass("fdp-checking-this"),
    plugin_col = o.find('td[data-path="' + s.plugins.split(",")[window.eos_dp_autosuggest_counter] + '"]'),
    a.append("data", JSON.stringify(s)),
    a.append("nonce", d),
    t.open("POST", eos_dp_js.ajaxurl + "?action=eos_dp_auto_settings", !0),
    t.send(a),
    t.onprogress = function() {
        if (++window.eos_dp_autosuggest_counter,
        "error" === t.response)
            return alert("Something went wrong!"),
            !1;
        if (parseInt(window.eos_dp_autosuggest_counter) < parseInt(eos_dp_js.plugins_n) + 1)
            "" !== t.response ? "error" !== t.response ? o.find('td[data-path="' + JSON.parse(t.response)[0] + '"]').removeClass("eos-dp-active").find("input").attr("checked", 1).trigger("change") : plugin_col.addClass("eos-dp-active").addClass("fdp-error").find("input").removeAttr("checked").trigger("change") : plugin_col.addClass("eos-dp-active").find("innput").trigger("change"),
            plugin_col.removeClass("fdp-checking-this").addClass("eos-dp-autochecked"),
            fdp_suggest_plugins(e, s);
        else {
            if (o.removeClass("eos-test-in-progress").addClass("eos-post-locked"),
            void 0 !== window.eos_dp_actual_row_in_progress && null !== window.eos_dp_actual_row_in_progress) {
                ++window.eos_dp_actual_row_in_progress;
                var a = "eos_dp_admin" !== eos_dp_js.page ? o.next().find(".eos-dp-pro-autosettings") : o.nextAll(".eos-dp-admin-row").not(".fdp-edit-single").first().find(".eos-dp-pro-autosettings");
                a.length > 0 ? (a.trigger("click"),
                window.eos_dp_autosuggest_counter = -1) : window.eos_dp_actual_row_in_progress = null
            }
            return !1
        }
    }
}
function eos_dp_debug_options(e) {
    return jQuery(".eos-dp-debug").addClass("eos-dp-progress"),
    eos_dp_send_ajax_popup(e, {
        nonce: jQuery("#eos_dp_debug_options").val(),
        url: e.dataset.url,
        action: "eos_dp_debug_options"
    }),
    !1
}
function eos_dp_memorize_table(e) {
    var s = {};
    jQuery(".eos-dp-section").find("table").find(".eos-dp-post-row").each(function(e, t) {
        var o = [];
        jQuery(this).find(".eos-dp-td-chk-wrp").each(function(e, s) {
            var t = jQuery(this).closest("td");
            t.hasClass("eos-dp-active") && o.push(jQuery("#eos-dp-plugin-name-" + t.index()).attr("data-path"))
        }),
        s[jQuery(this).attr("data-row_id")] = o
    }),
    localStorage.setItem(e, JSON.stringify(s))
}
function eos_dp_get_table(e) {
    return jQuery.parseJSON(localStorage.getItem(e))
}
function eos_dp_remove_table(e) {
    return localStorage.removeItem(e)
}
function eos_dp_fill_table_by_storage(e) {
    var s = eos_dp_get_table(e)
      , t = jQuery(".eos-dp-section").find("table");
    for (row_id in s)
        for (var o = s[row_id], a = t.find("[data-row_id='" + row_id + "']").find(".eos-dp-td-chk-wrp"), d = 0; d < a.length; d += 1) {
            var i = jQuery(a[d].closest("td"))
              , p = jQuery("#eos-dp-plugin-name-" + i.index());
            i.removeClass("eos-dp-active"),
            th_path = p.attr("data-path"),
            -1 !== jQuery.inArray(th_path, o) && i.addClass("eos-dp-active")
        }
}
function eos_dp_plugins_row(e) {
    var s = "";
    return (e = e instanceof jQuery ? e : jQuery(e)).find(".eos-dp-td-chk-wrp input[type=checkbox]").each(function() {
        if ((chk = jQuery(this)).closest("td").hasClass("eos-dp-active") || chk.hasClass("eos-dp-global-chk-row"))
            s += ";pn:";
        else {
            var e = chk.closest("td").attr("data-path");
            colN = jQuery(this).index(),
            void 0 !== e && (s += ";pn:" + e)
        }
    }),
    s
}
function eos_dp_closest_tagname(e, s) {
    for (; e !== document.body; )
        if ((e = e.parentElement).tagName.toLowerCase() === s.toLowerCase())
            return e;
    return null
}
function eos_filter_rows() {
    var e = [];
    jQuery("#fdp-singles-filter .dashicons").each(function() {
        jQuery(this).hasClass("eos-dp-active") && e.push(jQuery(this).attr("data-class"))
    }),
    jQuery(".eos-dp-post-row").addClass("eos-hidden"),
    jQuery(e.join(",")).removeClass("eos-hidden")
}
function eos_dp_string_to_hash(e) {
    e += Date.now();
    var s, t, o = 0;
    if (0 === e.length)
        return o;
    for (s = 0; s < e.length; s += 1)
        o = (o << 5) - o + (t = e.charCodeAt(s)),
        o |= 0;
    return Math.abs(o).toString()
}
function eos_dp_update_table_by_local_storage() {
    var e = window.localStorage.getItem("fdp_table_temp");
    if (e && "" !== e) {
        var s = JSON.parse(e);
        jQuery(".eos-dp-post-row").each(function(e, t) {
            jQuery(this).find("td").not(".eos-dp-post-name-wrp").each(function(t, o) {
                1 === JSON.parse(s[e])[t] ? jQuery(this).addClass("eos-dp-active").find("input").removeAttr("checked") : jQuery(this).removeClass("eos-dp-active").find("input").attr("checked", 1)
            })
        })
    }
}
function eos_dp_invert_selection(e) {
    return window.fdp_inverting_selection = !0,
    jQuery.each(e.find("td").not(".eos-dp-post-name-wrp"), function() {
        jQuery(this).find("input").trigger("click")
    }),
    e.addClass("eos-post-locked"),
    window.fdp_inverting_selection = !1,
    !1
}
function eos_dp_trigger_copy_row(e) {
    jQuery(window.fdp_row_target).closest("tr").find(".eos-dp-copy").trigger("click")
}
function eos_dp_trigger_paste_row(e) {
    jQuery(window.fdp_row_target).closest("tr").find(".eos-dp-paste").trigger("click")
}
function eos_dp_show_all_plugins() {
    var e = jQuery("#fdp-plug-filter-all");
    e && e.length > 0 && (window.plugins_filter = jQuery(".fdp-plug-filter.eos-active"),
    e.trigger("click"))
}
function eos_dp_show_all_pages() {
    var e = jQuery(".fdp-filter-all");
    e && e.length > 0 && (window.pages_filter = jQuery("#fdp-singles-filter .eos-dp-active"),
    e.trigger("click"))
}
function eos_dp_restore_plugins_filter() {
    void 0 !== window.plugins_filter && window.plugins_filter.trigger("click")
}
function eos_dp_restore_pages_filter() {
    void 0 !== window.pages_filter && 1 === window.pages_filter.length && jQuery(window.pages_filter).trigger("click")
}
function eos_dp_remove_all_filters() {
    eos_dp_show_all_plugins(),
    eos_dp_show_all_pages()
}
function eos_dp_restore_all_filters() {
    eos_dp_restore_plugins_filter(),
    eos_dp_restore_pages_filter()
}
function eos_dp_removeClass(e) {
    for (var s = document.getElementsByClassName(e), t = 0; t < s.length; t += 1)
        s[t].className = s[t].className.replace(" " + e, "").replace(e, "")
}
function eos_dp_addClass(e, s) {
    if (e && void 0 === e.length)
        e.className = (e.className.replace(" " + s, "").replace(s, "") + " " + s).trim();
    else
        for (var t = 0; t < e.length; t += 1)
            e[t].className = (e[t].className.replace(" " + s, "").replace(s, "") + " " + s).trim()
}
function eos_dp_inViewport(e) {
    if (void 0 === e)
        return !1;
    var s = e.getBoundingClientRect();
    return !(s.top > innerHeight || s.bottom < 0)
}
function fdp_synchronize_dependencies() {
    if ((void 0 === window.fdp_bulk_selection || !window.fdp_bulk_selection) && void 0 !== eos_dp_js.dependencies && (void 0 === window.fdp_inverting_selection || !0 !== window.fdp_inverting_selection)) {
        var e = JSON.parse(eos_dp_js.dependencies)
          , s = void 0 !== eos_dp_js.page && ("eos_dp_mobile" === eos_dp_js.page || "eos_dp_desktop" === eos_dp_js.page || "eos_dp_search" === eos_dp_js.page);
        jQuery.each(e, function(e, t) {
            for (var o = t.strings, a = 0; a < o.length; a += 1) {
                var d = o[a];
                jQuery("#eos-dp-setts").on("change", '[data-path*="' + d + '"]', function() {
                    fdp_update_parent(this, e, d, s)
                })
            }
            jQuery("#eos-dp-setts").on("change", '[data-path="' + e + '"]', function() {
                fdp_update_add_ons(this, e, o, s)
            })
        })
    }
}
function fdp_update_parent(e, s, t, o) {
    if ((void 0 === window.fdp_bulk_selection || !window.fdp_bulk_selection) && (void 0 === window.fdp_inverting_selection || !0 !== window.fdp_inverting_selection) && s !== e.dataset.path && (-1 !== e.dataset.path.indexOf("-" + t) || -1 !== e.dataset.path.indexOf(t + "-"))) {
        var a = o ? jQuery('[data-path="' + s + '"]') : jQuery(e).closest("tr").find('[data-path="' + s + '"]')
          , d = void 0 !== eos_dp_js.page && ("eos_dp_mobile" === eos_dp_js.page || "eos_dp_desktop" === eos_dp_js.page || "eos_dp_search" === eos_dp_js.page);
        void 0 !== e.type && "checkbox" === e.type && jQuery(e).closest("td").hasClass("eos-dp-active") ? (a.closest("td").addClass("eos-dp-active"),
        d ? a.prop("checked", !0) : a.removeAttr("checked")) : -1 !== e.className.indexOf("eos-dp-active") && (a.addClass("eos-dp-active"),
        d ? a.find("input").prop("checked", !0) : a.find("input").removeAttr("checked"))
    }
}
function fdp_update_add_ons(e, s, t, o) {
    if (void 0 === window.fdp_bulk_selection || !window.fdp_bulk_selection) {
        var a = void 0 !== eos_dp_js.page && ("eos_dp_mobile" === eos_dp_js.page || "eos_dp_desktop" === eos_dp_js.page || "eos_dp_search" === eos_dp_js.page);
        if (void 0 !== e.type && "checkbox" === e.type) {
            if (!e.checked && !a)
                return
        } else if (-1 !== e.className.indexOf("eos-dp-active"))
            return;
        for (var d = o ? jQuery(e).closest("table") : jQuery(e).closest("tr"), i = 0; i < t.length; i += 1) {
            var p = t[i];
            jQuery(d.find('[data-path*="' + p + '"]')).each(function() {
                var e = this.dataset.path;
                (-1 !== e.indexOf("-" + p) || -1 !== e.indexOf(p + "-")) && (void 0 !== this.type && "checkbox" === this.type ? (jQuery(this).closest("td").removeClass("eos-dp-active"),
                a ? jQuery(this).removeAttr("checked") : jQuery(this).prop("checked", !0)) : (jQuery(this).removeClass("eos-dp-active"),
                a ? jQuery(this).removeAttr("checked") : jQuery(this).find("input").prop("checked", !0)))
            })
        }
    }
}
function fdp_update_add_ons_columns(e, s, t) {
    if ((void 0 === window.fdp_bulk_selection || !window.fdp_bulk_selection) && e.checked)
        for (var o = 0; o < t.length; o += 1) {
            var a = t[o]
              , d = "woocommerce/woocommerce.php" !== s ? jQuery('td[data-path*="' + a + '"]') : jQuery('td[data-path*="' + a + '"]').not(".eos-dp-woo-row td");
            jQuery(d).each(function() {
                var e = this.dataset.path;
                (-1 !== e.indexOf("-" + a) || -1 !== e.indexOf(a + "-")) && (jQuery(this).removeClass("eos-dp-active"),
                jQuery(this).find("input").prop("checked", !0))
            })
        }
}
function eos_dp_call_ajax(e) {
    return jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
            data: e.dataset.data,
            nonce: e.dataset.nonce,
            action: e.dataset.action
        }
    }),
    !1
}
function eos_cbi_copy_to_clipboard(e) {
    navigator.clipboard.writeText(e).then(function() {}, function(e) {
        console.log("Something went wrong trying to copy to the clipboard")
    })
}
jQuery(document).ready(function(e) {
    table = document.getElementById("eos-dp-setts"),
    table_head = document.getElementById("eos-dp-table-head"),
    plugsN = e(".eos-dp-plugin-name").length,
    eos_dpBody = e("body"),
    right = eos_dp_js.is_rtl ? "left" : "right",
    psiButtons = e(".eos-dp-psi-preview");
    var s = !!table && table.getElementsByTagName("tr");
    firstRowTop = table && parseInt(eos_dp_js.plugins_n) > 1 && s && s.length > 0 ? s[s.length - 1].getBoundingClientRect().top : null,
    plugsN > 15 && localStorage.setItem("eos_dp_orientation", "eos-dp-vertical");
    var t = localStorage.getItem("eos_dp_orientation")
      , o = (e("#advanced_help_email"),
    e("#advanced_help_username"))
      , a = e("#advanced_help_password");
    if (t && "eos-dp-horizontal" === t && (eos_dpBody.addClass("eos-dp-horizontal"),
    eos_dp_set_horizontal_cell_width()),
    e("#eos-dp-setts .eos-dp-post-name-wrp,#eos-dp-table-head th:first-child").css("background", e("body").css("background")),
    "eos_dp_admin" === eos_dp_js.page) {
        var d = document.getElementsByClassName("eos-dp-admin-main-menu-link")
          , i = 0;
        if (d) {
            var p = d.length;
            if (d.length > 0)
                for (var n = document.getElementsByClassName("menu-top-first"), r = n.length; i < r; i += 1) {
                    var l = n[i].getElementsByTagName("a")[0]
                      , c = 0;
                    if (void 0 !== l && void 0 !== l.href)
                        for (; c < p; c += 1)
                            l.href.length > 4 && void 0 !== d[c].href && l.href === d[c].href && "undefined" !== l.text && (d[c].getElementsByTagName("h4")[0].innerText = l.text)
                }
        }
    }
    e(".eos-dp-duplicated-url a.eos-dp-title").each(function() {
        var s = this.href;
        e('a.eos-dp-title[href="' + s + '"]').each(function() {
            e(this).closest("tr").find("td").on("click", function() {
                var t = e(this).closest("tr");
                setTimeout(function() {
                    e('a.eos-dp-title[href="' + s + '"]').each(function() {
                        eos_dp_clone_row_options(t, e(this).closest("tr"))
                    })
                }, 1e3)
            })
        })
    }),
    e("#eos-dp-setts").on("click", ".fdp-row-actions-ico", function() {
        var s = e(this).closest("tr");
        e("#eos-dp-setts tr").not(s).removeClass("fdp-actions-on"),
        s.toggleClass("fdp-actions-on"),
        e(this).closest("td").toggleClass("eos-dp-hover")
    }),
    e("#fdp-show-page-filters").on("click", function() {
        e("#fdp-singles-filter").toggleClass("eos-hidden"),
        e(this).find(".dashicons").toggleClass("dashicons-arrow-down").toggleClass("dashicons-arrow-up")
    }),
    e("#eos-dp-lock-single-post").on("click", function() {
        e(".eos-dp-post-name-wrp .eos-dp-lock-post").trigger("click"),
        e(this).toggleClass("eos-post-locked")
    }),
    "eos_dp_admin" === eos_dp_js.page && e("#eos-dp-by-admin-section .eos-dp-td-chk-wrp").on("click", function(s) {
        var t = e(this).closest("td")
          , o = t.hasClass("eos-dp-active")
          , a = s.target.className
          , d = e('#eos-dp-setts .eos-dp-view[href="' + e(this).closest("tr").find(".eos-dp-view").attr("href") + '"]')
          , i = t.attr("data-path");
        e(d).each(function() {
            var s = e(this).closest("tr").find('[data-path="' + i + '"]').find(".eos-dp-td-chk-wrp")
              , t = s.find("input")[0];
            t.className !== a && (t.checked = o,
            o ? s.closest("td").removeClass("eos-dp-active") : s.closest("td").addClass("eos-dp-active"))
        })
    }),
    e(".eos-dp-eos_dp_menu .eos-dp-td-chk-wrp").on("click", function() {
        e(this).closest("tr").addClass("eos-post-locked"),
        e("#eos-dp-lock-single-post").addClass("eos-post-locked")
    }),
    e("#eos-dp-lock-all").on("click", function() {
        e(".eos-dp-post-row").not(".eos-post-locked").each(function() {
            e(this).find(".eos-dp-lock-post").trigger("click")
        })
    }),
    e("#eos-dp-unlock-all").on("click", function() {
        e(".eos-post-locked").each(function() {
            e(this).find(".eos-dp-lock-post").trigger("click")
        })
    }),
    e("td").on("click", function() {
        e(this).closest("tr").find("td").removeClass("eos-dp-autochecked")
    }),
    e(".eos-dp-paste-post-types").on("click", function() {
        var s = e(this)
          , t = s.closest("tr");
        return s.addClass("eos-dp-progress"),
        t.find("td").addClass("eos-dp-progress"),
        e.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                nonce: e("#fdp_pro_deactivation_page").val(),
                post_type: e(this).closest("table").attr("data-post_type") || t.attr("data-post-type"),
                action: "eos_dp_pro_paste_from_post_types"
            },
            success: function(o) {
                if (o && "" !== o)
                    try {
                        var a = o.split(",");
                        for (idx in t.find('input[type="checkbox"]').each(function() {
                            var s = e(this);
                            s.removeAttr("checked"),
                            eos_dp_update_chks(s)
                        }),
                        a)
                            if ("" !== a[idx]) {
                                var d = t.find('[data-path="' + a[idx] + '"]');
                                d.removeClass("eos-dp-active"),
                                d.find('input[type="checkbox"]').prop("checked", !0)
                            }
                    } catch (i) {
                        throw "Something went wrong. Not possible to get the post type settings."
                    }
                s.removeClass("eos-dp-progress"),
                t.find("td").removeClass("eos-dp-progress")
            }
        }),
        !1
    }),
    e("#eos-dp-autosuggest-all").on("click", function() {
        window.eos_dp_actual_row_in_progress = 0,
        window.eos_dp_autosuggest_counter = 0,
        e(".eos-dp-pro-autosettings").first().trigger("click"),
        e("#eos-dp-lock-all").trigger("click")
    }),
    e(".eos-dp-pro-autosettings-all-from-row").on("click", function() {
        return window.eos_dp_actual_row_in_progress = 0,
        window.eos_dp_autosuggest_counter = 0,
        e(this).parent().find(".eos-dp-pro-autosettings").trigger("click"),
        !1
    }),
    e(".eos-dp-invert-selection").on("click", function() {
        return eos_dp_invert_selection(e(this).closest("tr")),
        !1
    }),
    e("#eos-dp-setts").on("click", ".eos-dp-copy", function() {
        var s = e(this).closest("tr")
          , t = e(this).find(".fdp-tooltip");
        if (window.eos_dp_last_copied_row = eos_dp_row2setts(s),
        localStorage.setItem("eos_dp_last_copied_row", JSON.stringify(window.eos_dp_last_copied_row)),
        localStorage.getItem("eos_dp_last_copied_row") === JSON.stringify(window.eos_dp_last_copied_row))
            var o = e(this).find(".fdp-msg-success");
        else
            var o = e(this).find(".fdp-msg-error");
        return t.attr("style", "display:none"),
        o.css("opacity", 1),
        setTimeout(function() {
            t.attr("style", ""),
            o.css("opacity", 0)
        }, 2e3),
        !1
    }),
    e("#eos-dp-setts").on("click", ".eos-dp-paste", function() {
        var s = e(this).closest("tr");
        return eos_dp_paste_last_copied_setts(s),
        !1
    }),
    e("#fdp-select-all-single-post").on("click", function(s) {
        window.fdp_bulk_selection = !0,
        e(".eos-dp-td-chk-wrp input").each(function() {
            var s = e(this);
            s.attr("checked", !1).trigger("click"),
            s.closest("td").addClass("eos-dp-active")
        }),
        window.fdp_bulk_selection = !1
    }),
    e("#fdp-unselect-all-single-post").on("click", function(s) {
        window.fdp_bulk_selection = !0,
        e(".eos-dp-td-chk-wrp input").each(function() {
            var s = e(this);
            s.attr("checked", !0).trigger("click"),
            s.closest("td").removeClass("eos-dp-active")
        }),
        window.fdp_bulk_selection = !1
    }),
    e(".eos-dp-preview").on("click", function(s) {
        var t = this
          , o = "undefined" != typeof is_single_post && is_single_post ? e(".eos-dp-themes-list").val() : e(t).closest("td").find(".eos-dp-themes-list").val()
          , a = e(t).hasClass("eos-dp-archive-preview") ? ".eos-dp-archive-row" : ".eos-dp-post-row"
          , d = "undefined" != typeof is_single_post && is_single_post ? e(a) : e(this).closest(a);
        e(t).addClass("eos-dp-progress"),
        microtime = Date.now();
        var i = e(this)
          , p = i.attr("data-page_speed_insights")
          , n = void 0 !== eos_dp_js.page && "eos_dp_admin" === eos_dp_js.page ? "_admin" : ""
          , r = {
            nonce: e("#eos_dp" + n + "_setts").val() || e("#eos_dp_arch_setts").val(),
            post_type: i.closest(".eos-dp-archive-row").attr("data-post-type"),
            tax: i.closest(".eos-dp-archive-row").attr("data-tax"),
            plugin_path: eos_dp_plugins_row(d),
            page_speed_insights: p,
            action: "eos_dp_preview"
        };
        return "_admin" === n ? r.admin_page = i.closest("tr").find(".eos-dp-title").attr("href") : r.post_id = i.closest(".eos-dp-actions").attr("data-post-id"),
        r.microtime = microtime,
        d.find(".eos-dp-post-name-wrp").addClass("eos-dp-progress"),
        e.ajax({
            type: "POST",
            url: ajaxurl,
            data: r,
            success: function(s) {
                if (1 == parseInt(s)) {
                    d.find(".eos-dp-post-name-wrp").removeClass("eos-dp-progress");
                    var a = t.href
                      , p = ""
                      , r = void 0 !== t.dataset.encode_url && "true" === t.dataset.encode_url;
                    return "_admin" !== n && ("dummy_html" !== o ? (p = r ? "%26theme%3D" + o : "&theme=" + o,
                    t.href = t.href.split("%26theme%3D")[0].replace("&", "%26") + "%26theme%3D" + o) : t.href = t.href.split("=http")[0].split("%3Dhttp")[0] + "=" + eos_dp_js.html_url),
                    r ? (t.href = t.href.split("%26theme%3D")[0].split("&").join("%26").split("=").join("%3D") + p,
                    t.href += "dummy_html" !== o ? "%26test_id%3D" + microtime : "") : (t.href = t.href.split("&theme=")[0].split("%26").join("&").split("%3D").join("="),
                    t.href += "dummy_html" !== o ? "&test_id=" + microtime : ""),
                    "_admin" === n && (t.href += "&backend_usage=true",
                    t.href += "&theme=" + i.closest("tr").find(".eos-dp-row-theme").closest("td").hasClass("eos-dp-active")),
                    t.href = t.href.replace("%3Dhttps", "=https").replace("%3Dhttp", "=http"),
                    window.open(t.href, "_blank"),
                    t.href = a,
                    e(t).removeClass("eos-dp-progress"),
                    !0
                }
                d.find(".eos-dp-post-name-wrp").removeClass("eos-dp-progress"),
                alert("Something went wrong"),
                e(t).removeClass("eos-dp-progress")
            }
        }),
        !1
    }),
    e(".eos-dp-post-name-wrp").on("mouseover", function() {
        e(".eos-dp-post-name-wrp").removeClass("eos-dp-next-row-hover"),
        e(this).removeClass("eos-dp-not-hover"),
        e(this).closest("tr").next().find(".eos-dp-post-name-wrp").addClass("eos-dp-next-row-hover")
    }),
    e(".eos-dp-themes-list").on("click", function() {
        return e(this).closest("td").addClass("eos-dp-hover").removeClass("eos-dp-not-hover"),
        !1
    }),
    e(".eos-dp-close-actions").not(".eos-dp-themes-list").on("click", function() {
        if (e(this).closest("td").removeClass("eos-dp-hover").addClass("eos-dp-not-hover"),
        e(this).closest("tr").removeClass("fdp-actions-on"),
        -1 !== this.className.indexOf("eos-dp-close-actions"))
            return !1
    }),
    e("#eos-dp-setts input[type=checkbox]").on("mouseenter", function() {
        e(this).eos_dp_shiftSelectable()
    }),
    e("#eos-dp-setts").on("click", "input[type=checkbox]", function() {
        e(this).closest("td").toggleClass("eos-dp-active").toggleClass("d");
        var s = e(this).closest("td").hasClass("eos-dp-active") ? 1 : -1;
        window.eos_dp_grouped = !1,
        window.eos_dp_last_modified_row = e(this).closest("tr"),
        window.eos_dp_last_modified_row.attr("data-active-plugins", parseInt(window.eos_dp_last_modified_row.attr("data-active-plugins")) + s),
        window.eos_dp_last_modified_row.attr("data-disabled-plugins", parseInt(window.eos_dp_last_modified_row.attr("data-disabled-plugins")) - s)
    }),
    e(".eos-dp-priority-post-type").on("click", function() {
        var s = e(this);
        if (s.hasClass("eos-dp-priority-post-type")) {
            s.is(":checked") ? s.closest(".eos-dp-priority-post-type-wrp").removeClass("eos-dp-priority-active") : s.closest(".eos-dp-priority-post-type-wrp").addClass("eos-dp-priority-active");
            return
        }
    }),
    e(".eos-dp-global-chk-col").on("click", function() {
        var s = e(this)
          , t = s.is(":checked")
          , o = s.attr("data-col")
          , a = s.closest("th").find(".eos-dp-plugin-name").attr("data-path")
          , d = ".eos-dp-col-" + o
          , i = void 0 !== eos_dp_js.dependencies && JSON.parse(eos_dp_js.dependencies);
        "theme" === o && (d = ".eos-dp-row-theme");
        var p = [];
        e(d).each(function() {
            p.push(e(this).is(":checked"))
        }),
        eos_dp_update_chk_wrp(s, t);
        var n = e(d).filter(":visible");
        n.attr("checked", t),
        s.is(":checked") ? (n.closest("td").removeClass("eos-dp-active"),
        e(d).filter(":visible").removeClass("eos-dp-active")) : (n.closest("td").addClass("eos-dp-active"),
        e(d).filter(":visible").addClass("eos-dp-active")),
        "woocommerce/woocommerce.php" === a && e('.eos-dp-woo-row [data-path="woocommerce/woocommerce.php"]').each(function() {
            e(this).addClass("eos-dp-active"),
            e(this).find('input[type="checkbox"]').attr("checked", !1)
        }),
        i && void 0 !== i[a] && fdp_update_add_ons_columns(s[0], a, i[a].strings),
        e(d).each(function(s, o) {
            var a = e(this).closest("tr");
            if (t !== p[s]) {
                var d = t ? -1 : 1
                  , a = e(this).closest("tr");
                a.attr("data-active-plugins", parseInt(a.attr("data-active-plugins")) + d),
                a.attr("data-disabled-plugins", parseInt(a.attr("data-disabled-plugins")) - d)
            }
        })
    }),
    e(".eos-dp-lock-post").on("click", function() {
        e(this).closest("tr").toggleClass("eos-post-locked")
    }),
    e("#eos-dp-setts").on("click", ".eos-dp-global-chk-row", function() {
        var s = e(this)
          , t = s.is(":checked")
          , o = s.closest(".eos-dp-post-row").find("input[type=checkbox]").not(".eos-dp-default-post-type,.eos-dp-lock-post")
          , a = s.closest("tr");
        o.attr("checked", t),
        eos_dp_update_chks(o),
        eos_dp_update_chk_wrp(s, t);
        var d = parseInt(e(".fdp-plug-filter").last().attr("data-max"))
          , i = s.closest("span").hasClass("eos-dp-active-wrp") ? [d, 0] : [0, d];
        a.attr("data-active-plugins", i[0]),
        a.attr("data-disabled-plugins", i[1]),
        void 0 !== eos_dp_js.page && "eos_dp_menu" === eos_dp_js.page && a.addClass("eos-post-locked")
    }),
    e(".eos-dp-reset-col").on("click", function() {
        e(".eos-dp-col-" + e(this).attr("data-col")).each(function() {
            var s = "checked" === e(this).attr("data-checked");
            e(this).attr("checked", s),
            eos_dp_update_chks(e(this))
        }),
        e(this).closest(".eos-dp-global-chk-col-wrp").find(".eos-dp-global-chk-col").attr("checked", !1)
    }),
    e(".eos-dp-reset-row").on("click", function() {
        e(this).closest(".eos-dp-post-row").find("input[type=checkbox]").each(function() {
            var s = "checked" === e(this).attr("data-checked");
            e(this).attr("checked", s),
            eos_dp_update_chks(e(this))
        }),
        e(this).closest("td").find(".eos-dp-global-chk-row").attr("checked", !1),
        eos_dp_update_chk_wrp(e(this), checked)
    }),
    e(".eos-dp-global-chk-post_type").on("click", function() {
        var s = e(this)
          , t = s.is(":checked");
        e(".eos-dp-post-" + s.attr("data-post_type")).find("input[type=checkbox]").attr("checked", t),
        eos_dp_update_chks(e(".eos-dp-post-" + s.attr("data-post_type")).find("input[type=checkbox]")),
        eos_dp_update_chk_wrp(s, t)
    }),
    e(".eos-dp-reset-post_type").on("click", function() {
        e(".eos-dp-post-" + e(this).attr("data-post_type") + " input[type=checkbox]").each(function() {
            var s = "checked" === e(this).attr("data-checked");
            e(this).attr("checked", s),
            eos_dp_update_chks(e(this))
        })
    }),
    e(".eos-dp-plugin-name span a").each(function() {
        var s = e(this);
        s.text().length > 37 && s.text(s.text().substring(0, 34) + " ...")
    }),
    e(".eos-dp-title .fdp-title-text").each(function() {
        var s = e(this)
          , t = s.text();
        t.length > 31 && t.substring(0, 28) !== t && s.text(t.substring(0, 28) + " ...")
    }),
    e("#eos-dp-add-url").on("click", function() {
        var s = e(".eos-dp-url.eos-hidden");
        return s.clone().insertAfter(s),
        s.removeClass("eos-hidden"),
        !1
    }),
    e(".eos-dp-default-post-type-wrp").on("click", function() {
        var s = e(this).find("input");
        s[0].hasAttribute("checked") ? s.attr("checked", null) : s.attr("checked", !0)
    }),
    e("#eos-dp-setts").on("click", ".eos-dp-delete-url", function() {
        e(this).closest("tr").remove()
    }),
    e(".eos-dp-setts-menu-item").on("click", function() {
        e(".eos-dp-setts-menu-item").removeClass("eos-active"),
        e(this).addClass("eos-active")
    }),
    e(".eos-dp-save-eos_dp_by_post_type").on("click", function() {
        e(".eos-dp-opts-msg").addClass("eos-hidden");
        var s = {}
          , t = document.getElementsByClassName("eos-dp-post-type");
        eos_dp_show_all_plugins();
        for (var o = 0; o < t.length; o += 1) {
            var a = []
              , d = t[o].getElementsByClassName("eos-dp-td-post-type-chk-wrp");
            for (c = 0; c < d.length; c += 1)
                e(d[c].getElementsByTagName("input")).closest("td").hasClass("eos-dp-active") || (a[c] = document.getElementById("eos-dp-plugin-name-" + (c + 1)).getAttribute("data-path"));
            var i = e(t[o]).find(".eos-dp-priority-post-type-wrp").hasClass("eos-dp-priority-active") ? "1" : "0"
              , p = e(t[o]).find(".eos-dp-default-post-type").is(":checked") ? "1" : "0";
            s[t[o].getAttribute("data-post-type")] = [i, a.join(","), p]
        }
        return eos_dp_restore_plugins_filter(),
        eos_dp_send_ajax(e(this), {
            nonce: e("#eos_dp_pt_setts").val(),
            eos_dp_pt_setts: JSON.stringify(s),
            action: "eos_dp_save_post_type_settings"
        }),
        !1
    }),
    e(".fdp-custom-rows-btn").on("click", function() {
        e(".eos-dp-opts-msg").addClass("eos-hidden");
        var s = {}
          , t = document.querySelectorAll("tr.eos-dp-url")
          , o = ""
          , a = e(".eos-dp-section ").attr("data-page_slug")
          , d = {};
        eos_dp_show_all_plugins();
        for (var i = 0; i < t.length - 1; i += 1) {
            var p = []
              , n = t[i].getElementsByClassName("eos-dp-td-url-chk-wrp");
            for (c = 0; c < n.length; c += 1)
                if (!e(n[c].getElementsByTagName("input")).closest("td").hasClass("eos-dp-active")) {
                    var r = document.getElementById("eos-dp-plugin-name-" + (c + 1));
                    r && (p[c] = r.getAttribute("data-path"))
                }
            o = e(t[i]).find(".eos-dp-url-input").val(),
            void 0 !== n[c - 1] && (d[o] = e(n[c - 1].getElementsByTagName("input")).closest("td").hasClass("eos-dp-active")),
            s[i] = {},
            s[i].url = o,
            s[i].plugins = p.join(","),
            s[i].f = e(t[i]).find(".fdp-exact-filter").hasClass("fdp-exact-filter-off") ? "0" : "1"
        }
        return eos_dp_restore_plugins_filter(),
        eos_dp_send_ajax(e(this), {
            nonce: e("#" + a + "_setts").val(),
            page_slug: a,
            theme_activation: JSON.stringify(d),
            headers: eos_dp_js.headers,
            setts: JSON.stringify(s),
            action: "eos_dp_save_url_settings"
        }),
        !1
    }),
    e(".eos-dp-save-eos_dp_admin_url").on("click", function() {
        e(".eos-dp-opts-msg").addClass("eos-hidden"),
        eos_dp_show_all_plugins();
        for (var s = {}, t = document.getElementsByClassName("eos-dp-url"), o = "", a = 0; a < t.length - 1; a += 1) {
            var d = []
              , i = t[a].getElementsByClassName("eos-dp-td-url-chk-wrp");
            for (c = 0; c < i.length; c += 1)
                e(i[c].getElementsByTagName("input")).closest("td").hasClass("eos-dp-active") || (d[c] = document.getElementById("eos-dp-plugin-name-" + (c + 1)).getAttribute("data-path"));
            o = e(t[a]).find(".eos-dp-url-input").val(),
            s[a] = {},
            s[a].url = o,
            s[a].plugins = d.join(",")
        }
        return eos_dp_restore_plugins_filter(),
        eos_dp_send_ajax(e(this), {
            nonce: e("#eos_dp_admin_url_setts").val(),
            eos_dp_admin_url_setts: JSON.stringify(s),
            action: "eos_dp_save_admin_url_settings"
        }),
        !1
    }),
    e(".eos-dp-save-eos_dp_menu").on("click", function() {
        e(".eos-dp-opts-msg").addClass("eos-hidden"),
        eos_dp_remove_all_filters();
        var s, t, o, a = "not checked", d = "", i = "", p = {}, n = [], r = {}, l = [], c = [], h = "", u = [], rows_order = [], f = 0, title = document.head.getElementsByTagName('title')[0].innerText;
        e(".eos-dp-post-row").each(function() {
            t = e(this),
            u = [],
            h = "",
            void 0 !== (i = t.attr("data-post-id")) && (r[i] = t.attr("data-url"),
            t.hasClass("eos-post-locked") ? l.push(i) : c.push(i),
            rows_order.push(i),
            o = e.map(t.find("td").not(".eos-dp-post-name-wrp"), function(s, t) {
                return e(s).hasClass("eos-dp-active") ? "1" : "0"
            }),
            t.find("input[type=checkbox]").filter(":visible").not(".eos-dp-global-chk-row").each(function() {
                a = (s = e(this)).attr("data-checked"),
                f = (d = s.is(":checked") ? "checked" : "not-checked") !== a ? "1" : "0",
                h += "checked" === d ? "," + s.closest("td").attr("data-path") : ","
            }),
            (o.join("") !== t.attr("data-bin") || t.hasClass("eos-post-locked") && "true" === t.find(".eos-dp-actions").attr("data-need-custom-url")) && (p["post_id_" + i] = h.substring(1, h.length)))
        }),
        "undefined" != typeof eos_dp_need_custom_url && (eos_dp_need_custom_url_dyn = JSON.parse(JSON.stringify(eos_dp_need_custom_url)),
        p.eos_dp_need_custom_url = JSON.stringify(eos_dp_need_custom_url_dyn)),
        e("#fdp-menu-singles .eos-dp-submenu-item").each(function() {
            n.push(e(this).attr("data-post-type"))
        }),
        p.ids_locked = l,
        p.ids_unlocked = c,
        p.post_type = e("#eos-dp-setts").attr("data-post_type"),
        p.eos_dp_post_types = JSON.stringify(n),
        p.eos_dp_urls = JSON.stringify(r);
        var g = e(this).next(".ajax-loader-img"),ws=window.location.search,page=ws.indexOf('eos_page=') > -1 ? ws.split('eos_page=')[1].split('=')[0] : 1,orderby = document.getElementById('eos-dp-orderby-sel').value,order = document.getElementById('eos-dp-order-sel').value;
        if('custom_order' === orderby){
            eos_dp_set_cookie('fdp_single_rows_order_' + page,rows_order,90);
        }
        eos_dp_set_cookie('fdp_posts_per_page',document.getElementById('eos-dp-posts-per-page').value,90);
        eos_dp_set_cookie('fdp_orderby',orderby,90);
        eos_dp_set_cookie('fdp_order',order,90);
        if(ws.indexOf('&orderby=') > -1){
            window.history.pushState('page2',title,window.location.href.replace('&orderby=' + ws.split('&orderby=')[1].split('&')[0],'&orderby=' + orderby));
        }
        if(ws.indexOf('&order=') > -1){
            window.history.pushState('page2',title,window.location.href.replace('&order=' + ws.split('&order=')[1].split('&')[0],'&order=' + order));
        }
        return g.removeClass("eos-not-visible"),
        eos_dp_restore_all_filters(),
        e.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                nonce: e("#eos_dp_setts").val(),
                eos_dp_setts: p,
                action: "eos_dp_save_settings"
            },
            success: function(s) {
                if (g.addClass("eos-not-visible"),
                1 == parseInt(s)) {
                    e(".eos-dp-opts-msg_success").removeClass("eos-hidden");
                    var t = "";
                    e("#eos-dp-setts input[type=checkbox]").each(function() {
                        t = e(this).is(":checked") ? "checked" : "not-checked",
                        e(this).attr("data-checked", t)
                    })
                } else
                    eos_dp_show_errors(s)
            }
        }),
        !1
    }),
    e(".eos-dp-save-eos_dp_by_archive,.eos-dp-save-eos_dp_by_term_archive").on("click", function() {
        e(".eos-dp-opts-msg").addClass("eos-hidden"),
        eos_dp_show_all_plugins();
        var s, t, o = {}, a = {}, d = "";
        e("#eos-dp-by-archive-section .eos-dp-archive-row").each(function() {
            d = "",
            (t = e(this)).find("input[type=checkbox]").not(".eos-dp-global-chk-row").each(function() {
                s = e(this),
                d += s.is(":checked") ? "," + s.closest("td").attr("data-path") : ","
            }),
            o[t.attr("data-href")] = d,
            a[t.attr("data-url")] = [t.attr("data-post-type"), d]
        });
        var i = e(this).next(".ajax-loader-img");
        return i.removeClass("eos-not-visible"),
        eos_dp_restore_plugins_filter(),
        e.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                nonce: e("#eos_dp_arch_setts").val(),
                archives: o,
                archivesUrls: a,
                action: "eos_dp_save_archives_settings"
            },
            success: function(s) {
                if (i.addClass("eos-not-visible"),
                1 == parseInt(s)) {
                    e(".eos-dp-opts-msg_success").removeClass("eos-hidden");
                    var t = "";
                    e("#eos-dp-setts input[type=checkbox]").each(function() {
                        t = e(this).is(":checked") ? "checked" : "not-checked",
                        e(this).attr("data-checked", t)
                    })
                } else
                    eos_dp_show_errors(s)
            }
        }),
        !1
    }),
    e(".fdp-one-col-sec+.eos-dp-btn-wrp input").on("click", function() {
        var s, t = e(".fdp-one-col-sec").attr("data-section").replace("eos_dp_", ""), o = "";
        return e(".eos-dp-opts-msg").addClass("eos-hidden"),
        e(".fdp-one-col-i").each(function() {
            s = e(this),
            o += s.is(":checked") ? "," : "," + e(this).attr("data-path")
        }),
        eos_dp_send_ajax(e(this), {
            nonce: e("#eos_dp_" + t + "_setts").val(),
            data: o,
            opt_name: t,
            action: "eos_dp_save_one_col_settings"
        }),
        !1
    }),
    e(".eos-dp-save-eos_dp_integration").on("click", function() {
        eos_dp_show_all_plugins(),
        e(".eos-dp-opts-msg").addClass("eos-hidden");
        for (var s = {}, t = {}, o = document.getElementsByClassName("eos-dp-integration-row"), a = "", d = 0; d < o.length; d += 1) {
            var i = []
              , p = o[d].getElementsByClassName("eos-dp-td-integration-chk-wrp");
            for (c = 0; c < p.length - 1; c += 1)
                e(p[c]).is(":visible") && !e(p[c].getElementsByTagName("input")).closest("td").hasClass("eos-dp-active") && (i[c] = document.getElementById("eos-dp-plugin-name-" + (c + 1)).getAttribute("data-path"));
            s[a = e(o[d]).attr("data-integration")] = i.join(","),
            t[a] = e(p[c].getElementsByTagName("input")).closest("td").hasClass("eos-dp-active")
        }
        return eos_dp_restore_plugins_filter(),
        eos_dp_send_ajax(e(this), {
            nonce: e("#eos_dp_integration_actions_setts").val(),
            integration_plugins: JSON.stringify(s),
            integration_theme: JSON.stringify(t),
            action: "eos_dp_save_integration_actions_settings"
        }),
        !1
    }),
    e(".eos-dp-save-eos_dp_admin").on("click", function() {
        eos_dp_show_all_plugins(),
        e(".eos-dp-opts-msg").addClass("eos-hidden");
        for (var s = {}, t = {}, o = (e("#eos-dp-by-admin-section"),
        document.getElementsByClassName("eos-dp-admin-row")), a = 0; a < o.length; a += 1) {
            for (var d = [], i = o[a].getElementsByClassName("eos-dp-td-admin-chk-wrp"), p = 0; p < i.length - 1; p += 1)
                e(i[p].getElementsByTagName("input")).closest("td").hasClass("eos-dp-active") || (d[p] = e("#eos-dp-plugin-name-" + (p + 1)).attr("data-path"));
            var n = o[a].getAttribute("data-admin");
            t[n] = e(i[p].getElementsByTagName("input")).closest("td").hasClass("eos-dp-active"),
            s[n] = d.join(",")
        }
        var r = {
            nonce: e("#eos_dp_admin_setts").val(),
            headers: eos_dp_js.headers,
            eos_dp_admin_setts: JSON.stringify(s),
            theme_activation: JSON.stringify(t),
            action: "eos_dp_save_admin_settings"
        };
        return eos_dp_restore_plugins_filter(),
        eos_dp_send_ajax(e(this), r),
        !1
    }),
    e(".eos-dp-save-eos_dp_firing_order").on("click", function() {
        e(".eos-dp-opts-msg").addClass("eos-hidden");
        var s = [];
        return e(".eos-dp-plugin.ui-sortable-handle").each(function() {
            s.push(e(this).attr("data-path"))
        }),
        eos_dp_send_ajax(e(this), {
            nonce: e("#eos_dp_firing_order_setts").val(),
            eos_dp_plugins: s,
            action: "eos_dp_save_firing_order"
        }),
        !1
    }),
    e(".current-page-selector").on("keypress", function(s) {
        if (13 == s.which) {
            if (parseInt(this.value) - this.value != 0)
                return !1;
            window.location.href = e(this).attr("data-url") + "&eos_page=" + this.value
        }
    }),
    e("#eos-dp-setts").on("mouseenter", ".eos-dp-post-row", function() {
        trHover = e(this)
    }),
    e(document).on("keydown", function(s) {
        if (!e("input,textarea").is(":focus")) {
            if (67 == s.keyCode)
                trHover.find(".eos-dp-copy").trigger("click");
            else if (86 == s.keyCode)
                trHover.find(".eos-dp-paste").trigger("click");
            else if (71 == s.keyCode)
                trHover.find(".eos-dp-global-chk-row").trigger("click");
            else if (73 == s.keyCode)
                trHover.find(".eos-dp-invert-selection").trigger("click");
            else if (80 == s.keyCode)
                trHover.find(".eos-dp-preview").trigger("click");
            else if (79 == s.keyCode)
                trHover.find(".fdp-row-actions-ico").trigger("click");
            else if (37 == s.keyCode) {
                var t = e(".fdp-plugins-slider");
                t.val(t.val() + 1).trigger("input")
            } else if (39 == s.keyCode) {
                var t = e(".fdp-plugins-slider");
                t.val(Math.max(0, t.val() - 1)).trigger("input")
            } else
                33 == s.keyCode ? window.scrollTo(0, 0) : 65 == s.keyCode ? e("#fdp-plug-filter-all").trigger("click") : s.keyCode > 48 && s.keyCode < e(".fdp-plug-filter").length + 48 && e(".fdp-plug-filter").eq(s.keyCode - 48).trigger("click")
        }
    }),
    e("#eos-dp-setts").on("mouseenter", "td", function() {
        if (!e(this).closest("tr").hasClass("fdp-row-separator")) {
            var s = e(this).parent().hasClass("eos-dp-active") ? " eos-dp-plugin-active" : " eos-dp-plugin-not-active"
              , t = e(this).index()
              , o = void 0 !== eos_dp_js.page && "eos_dp_admin" === eos_dp_js.page ? e(this).closest("tr").index(e(".eos-dp-post-row")) : e(this).closest("tr").index();
            e(this).find(".eos-dp-td-chk-wrp") && (e(".eos-dp-name-th").eq(t - 1).addClass("eos-dp-plugin-hover" + s),
            e('#eos-dp-setts td[data-path="' + this.dataset.path + '"]').addClass("eos-dp-col-hover")),
            void 0 !== eos_dp_js.page && "eos_dp_admin" === eos_dp_js.page ? e(this).closest("tr").addClass("eos-dp-row-hover") : e(".eos-dp-post-row").eq(o - 2).addClass("eos-dp-row-hover")
        }
    }),
    e("#eos-dp-setts").on("mouseleave", "td", function() {
        var s = e(this).index()
          , t = void 0 !== eos_dp_js.page && "eos_dp_admin" === eos_dp_js.page ? e(this).closest("tr").index(e(".eos-dp-post-row")) : e(this).closest("tr").index();
        e(this).find(".eos-dp-td-chk-wrp") && (e(".eos-dp-name-th").eq(s - 1).removeClass("eos-dp-plugin-hover").removeClass("eos-dp-plugin-active").removeClass("eos-dp-plugin-not-active"),
        e('#eos-dp-setts td[data-path="' + this.dataset.path + '"]').removeClass("eos-dp-col-hover")),
        void 0 !== eos_dp_js.page && "eos_dp_admin" === eos_dp_js.page ? e(this).closest("tr").removeClass("eos-dp-row-hover") : e(".eos-dp-post-row").eq(t - 2).removeClass("eos-dp-row-hover")
    }),
    e("#eos-dp-posts-per-page,#eos-dp-orderby-sel,#eos-dp-order-sel,#eos-dp-device").on("change", function() {
        var s = e("#eos-dp-singles-title").attr("data-post-type")
          , t = eos_dp_posts_href(this, s);
        return document.getElementById("eos-dp-order-refresh").href = t,
        !1
    }),
    e("#eos-dp-toggle-pagination").on("click", function() {
        var s = e("#eos-dp-order-wrp");
        s.toggleClass("eos-hidden"),
        s.hasClass("eos-hidden") || e(".eos-dp-search-wrp").addClass("eos-hidden")
    }),
    e("#eos-dp-toggle-search").on("click", function() {
        var s = e(".eos-dp-search-wrp");
        s.toggleClass("eos-hidden"),
        s.hasClass("eos-hidden") || e("#eos-dp-order-wrp").addClass("eos-hidden")
    }),
    e("#eos-dp-post-search-submit").on("click", function() {
        return window.location.href = e(this).attr("data-url") + "&eos_post_title=" + encodeURI(e(this).prev().val()) + "&posts_per_page=" + document.getElementById("eos-dp-posts-per-page").value,
        !1
    }),
    e("#eos-dp-by-cat-search-submit").on("click", function() {
        return window.location.href = e(this).attr("data-url") + "&eos_cat=" + e("#eos-dp-by-cat-search select").val() + "&posts_per_page=" + document.getElementById("eos-dp-posts-per-page").value,
        !1
    }),
    e("#eos-dp-plugins-comparison").length > 0 ? e("#eos-dp-show-comparison").on("click", function() {
        e([document.documentElement, document.body]).animate({
            scrollTop: e("#eos-dp-plugins-comparison").offset().top
        }, 1e3)
    }) : e("#eos-dp-show-comparison").remove(),
    e("#eos-dp-go-to-top").on("click", function() {
        e([document.documentElement, document.body]).animate({
            scrollTop: 0
        }, 500)
    }),
    e("#eos-dp-collapse-all").on("click", function() {
        e(".eos-dp-plugin-info-section").removeClass("open").addClass("close")
    }),
    e("#eos-dp-expand-all").on("click", function() {
        e(".eos-dp-plugin-info-section").removeClass("close").addClass("open")
    }),
    e(".eos-dp-toggle-div").on("click", function() {
        var s = e(this).closest(".eos-dp-plugin-info-section")
          , t = s.hasClass("open");
        e(".eos-dp-plugin-info-section").removeClass("open").addClass("close"),
        t ? s.removeClass("open").addClass("close") : s.addClass("open").removeClass("close")
    }),
    e("#eos-dp-popup-close").on("click", function(s) {
        e("#eos-dp-popup").hide()
    }),
    e("#wp-admin-bar-eos-dp-menu li>a").on("click", function(s) {
        if (s.stopPropagation(),
        s.stopImmediatePropagation(),
        e("#eos-dp-get-screen").hasClass("eos-dp-active")) {
            var t = this.href;
            return t && t.length > 4 && e("#eos-dp-setts a").each(function() {
                if (this.href === t) {
                    var s = e("#eos-dp-setts").hasClass("fixed") ? e("#eos-dp-table-head").height() : 2 * e("#eos-dp-table-head").height();
                    return e([document.documentElement, document.body]).animate({
                        scrollTop: e(this).closest("tr").offset().top - s - e("#wpadminbar").height() - 130
                    }, 2e3),
                    e("#eos-dp-get-screen").removeClass("eos-dp-active"),
                    !1
                }
            }),
            !1
        }
    }),
    e(".fdp-plugins-activation-filter").on("click", function() {
        e(".fdp-plugins-activation-filter").removeClass("fdp-current-link"),
        e(this).addClass("fdp-current-link"),
        e(".fdp-inactive-plugin,.fdp-active-plugin").removeClass("eos-hidden"),
        e("." + e(this).attr("data-state")).addClass("eos-hidden")
    }),
    e("#eos-dp-get-screen").on("click", function() {
        e(this).toggleClass("eos-dp-active"),
        e(this).hasClass("eos-dp-active"),
        e("#wp-admin-bar-eos-dp-menu").addClass("hover")
    }),
    e("#eos-dp-stop-process").on("click", function() {
        window.eos_dp_stop_process = !0;
        var s = {}
          , t = {};
        e(".eos-dp-post-row").each(function(o, a) {
            e(this).find("td").not(".eos-dp-post-name-wrp").each(function(s, o) {
                t[s] = e(this).hasClass("eos-dp-active") ? 1 : 0
            }),
            s[o] = JSON.stringify(t)
        }),
        window.localStorage.setItem("fdp_table_temp", JSON.stringify(s)),
        e(this).addClass("eos-dp-not-active"),
        window.location.href = window.location.href + "&fdp-process=stopped"
    }),
    window.location.href.indexOf("&fdp-process=stopped") > 0 && (eos_dp_update_table_by_local_storage(),
    history.pushState({}, null, window.location.href.replace("&fdp-process=stopped", ""))),
    e(".eos-dp-pro-autosettings").on("click", function() {
        if (window.eos_dp_autosuggest_counter = 0,
        void 0 !== window.eos_dp_stop_process && window.eos_dp_stop_process) {
            window.eos_dp_stop_process = !1;
            return
        }
        e("#eos-dp-stop-process").removeClass("eos-dp-not-active"),
        e("#eos-dp-autosuggest-msg").removeClass("eos-hidden"),
        e("#eos-dp-autosuggest-msg-error").addClass("eos-hidden");
        var s = "undefined" != typeof is_single_post && is_single_post ? e(".eos-dp-post-row td").first() : e(this)
          , t = void 0 !== eos_dp_js.page && "eos_dp_admin" === eos_dp_js.page
          , o = e(this).next(".ajax-loader-img")
          , a = []
          , d = {
            offset: 0,
            headers: eos_dp_js.headers,
            nonce: t ? e("#eos_dp_pro_auto_settings_admin").val() : e("#eos_dp_pro_auto_settings").val(),
            action: t ? "eos_dp_pro_auto_settings_admin" : "eos_dp_pro_auto_settings"
        };
        return e(".eos-dp-plugin-name").each(function() {
            a.push(e(this).attr("data-path"))
        }),
        d.plugins = a.join(","),
        t ? d.admin_page = s.closest("tr").find(".eos-dp-title").attr("href") : void 0 !== eos_dp_js.page && "eos_dp_by_archive" === eos_dp_js.page ? d.post_type = s.closest("tr").attr("data-post-type") : void 0 !== eos_dp_js.page && "eos_dp_by_term_archive" === eos_dp_js.page ? (d.term_type = s.closest("tr").attr("data-post-type"),
        d.tax = s.closest("tr").attr("data-tax"),
        d.href = s.closest("tr").attr("data-href")) : d.post_id = "undefined" != typeof is_single_post && is_single_post ? e(".eos-dp-actions").attr("data-post-id") : s.closest(".eos-dp-actions").attr("data-post-id"),
        void 0 !== eos_dp_js.dependencies && (d.dependencies = eos_dp_js.dependencies),
        window.eos_dp_row = s.closest("tr"),
        e(".eos-dp-section table").addClass("eos-dp-progress"),
        o.removeClass("eos-hidden").removeClass("eos-not-visible"),
        eos_dp_row.addClass("eos-test-in-progress"),
        s.addClass("eos-active-test").closest("table").addClass("eos-dp-progress"),
        e(".eos-dp-autochecked").removeClass("eos-dp-autochecked"),
        eos_dp_send_autosuggest_request(s, d),
        !1
    }),
    e("#eos-dp-storage-btns-set span").on("click", function() {
        eos_dp_memorize_table(e(this).attr("data-id"))
    }),
    "undefined" != typeof eos_dp_storage_page_id && eos_dp_memorize_table(eos_dp_storage_page_id),
    e("#eos-dp-storage-btns-get span,#eos-dp-restore-options").on("click", function() {
        eos_dp_fill_table_by_storage(e(this).attr("data-id"))
    }),
    setTimeout(function() {
        o.val(o.attr("data-value")),
        a.val(a.attr("data-value"))
    }, 1e3),
    e(".fdp-filter-all").on("click", function() {
        e("#fdp-singles-filter span").addClass("eos-dp-active"),
        e(".fdp-filter-hide-all").removeClass("eos-active"),
        e(this).addClass("eos-active"),
        e(".eos-dp-post-row").removeClass("eos-hidden")
    }),
    e("#eos-dp-setts").on("click", ".fdp-exact-filter", function() {
        e(this).toggleClass("fdp-exact-filter-off")
    }),
    e(".fdp-filter-hide-all").on("click", function() {
        e("#fdp-singles-filter span").removeClass("eos-dp-active"),
        e(".fdp-filter-all").removeClass("eos-active"),
        e(this).addClass("eos-active"),
        e(".eos-dp-post-row").addClass("eos-hidden")
    }),
    e("#fdp-singles-filter .dashicons").on("click", function() {
        e("#fdp-singles-filter .dashicons").removeClass("eos-dp-active"),
        e(this).addClass("eos-dp-active"),
        e(".fdp-filter-all").removeClass("eos-active"),
        jQuery(".eos-dp-post-row").addClass("eos-hidden"),
        jQuery(this.dataset.class).removeClass("eos-hidden")
    }),
    e("#eos-dp-ajax-slug").on("click", function() {
        e(".eos-dp-ajax-desc").addClass("eos-hidden"),
        e(".eos-dp-ajax-slug").removeClass("eos-hidden")
    }),
    e("#eos-dp-ajax-desc").on("click", function() {
        e(".eos-dp-ajax-slug").addClass("eos-hidden"),
        e(".eos-dp-ajax-desc").removeClass("eos-hidden")
    }),
    document.body.className.indexOf("fdp-sortable-page") > 0 && (e(".fdp-sortable-page #eos-dp-setts tbody").sortable({
        axis: "y",
        containment: "parent",
        items: ".eos-dp-post-row"
    }),
    e(".eos-dp-firing-order").sortable({
        axis: "y",
        containment: "parent",
        items: ".eos-dp-plugin"
    }),
    e(".eos-ui-sortable").disableSelection()),
    e("#fdp-create-plugin").on("click", function() {
        e("#fdp-success,#fdp-fail").addClass("eos-hidden");
        var s = {
            nonce: e("#fdp_create_plugin").val(),
            plugin_name: e("#fdp-create-plugin-name").val(),
            plugin_author: e("#fdp-create-plugin-author").val(),
            plugin_author_uri: e("#fdp-create-plugin-author_uri").val(),
            plugin_description: e("#fdp-create-plugin-description").val(),
            action: "eos_dp_create_plugin"
        }
          , t = e(this);
        return t.addClass("eos-dp-progress"),
        e.ajax({
            type: "POST",
            url: ajaxurl,
            data: s,
            success: function(s) {
                if (t.removeClass("eos-dp-progress"),
                "" !== s) {
                    var o = JSON.parse(s);
                    if (0 === o || void 0 !== o.error) {
                        var a = e("#fdp-fail");
                        a.text(""),
                        "" !== o.error ? a.text(o.error) : a.text(a.attr("data-default_msg")),
                        "" === a.text() && a.text(a.attr("data-default_msg")),
                        a.removeClass("eos-hidden");
                        return
                    }
                    void 0 !== o.edit && !1 !== o.edit ? e("#fdp-edit-new-plugin").attr("href", o.edit.split("&amp;").join("&")).removeClass("eos-hidden") : e("#fdp-edit-new-plugin").addClass("eos-hidden"),
                    void 0 !== o.activate && "false" !== o.activate ? e("#fdp-activate-new-plugin").attr("href", o.activate.split("&amp;").join("&")).removeClass("eos-hidden") : e("#fdp-activate-new-plugin").addClass("eos-hidden"),
                    e(".eos-dp-opts-msg_success").removeClass("eos-hidden")
                }
            }
        }),
        !1
    }),
    e(".fdp-resize-col").on("input", function() {
        var s = e(".eos-dp-post-name-wrp").first()
          , t = parseInt(this.value);
        return t < 354 ? (e(".fdp-resize-col").val(354),
        !1) : t > 574 ? (e(".fdp-resize-col").val(574),
        !1) : void (localStorage.setItem("fdp_first_col_width", t),
        s[0].style.width = t + "px",
        s[0].style.maxWidth = t + "px")
    }),
    e(".fdp-plugins-slider").on("input", function() {
        var s = -parseInt(this.value) * e(this).closest("td").width();
        void 0 !== eos_dp_js.is_rtl && "1" === eos_dp_js.is_rtl && (s = -s),
        e(".fdp-plugins-slider").val(this.value),
        e(".eos-dp-post-row td, #eos-dp-table-head .eos-dp-name-th").not(".eos-dp-post-name-wrp").css("transform", "translateX(" + s + "px)")
    }),
    e("#fdp-toggle-top-bar").on("click", function() {
        e("body").toggleClass("fdp-top-bar-open")
    }),
    e("#fdp-toggle-storage").on("click", function() {
        e("#eos-dp-storage-wrp").toggleClass("eos-hidden")
    }),
    e(".fdp-plug-filter").on("click", function() {
        var s = e(".eos-dp-name-th")
          , t = e(".eos-dp-post-row")
          , o = parseInt(this.dataset.min)
          , a = parseInt(this.dataset.max);
        e(".fdp-plug-filter").removeClass("eos-active"),
        e(this).addClass("eos-active"),
        s.removeClass("eos-hidden").removeClass("eos-prepare-hidden"),
        t.find("td").removeClass("eos-hidden").removeClass("eos-prepare-hidden"),
        "all" !== this.dataset.min && (s.addClass("eos-prepare-hidden"),
        t.find("td").not(".eos-dp-post-name-wrp").addClass("eos-prepare-hidden"),
        s.slice(o - 1, a).removeClass("eos-prepare-hidden"),
        t.each(function() {
            e(this).find("td").not("eos-dp-post-name-wrp").slice(o, a + 1).removeClass("eos-prepare-hidden")
        })),
        e(".eos-prepare-hidden").addClass("eos-hidden").removeClass("eos-prepare-hidden"),
        e(".fdp-plugins-slider").val(0).trigger("input")
    }),
    e(".fdp-dismiss-pro-notice").on("click", function() {
        var s = e(this);
        e.post(ajaxurl, {
            pointer: s.attr("data-pointer-id"),
            action: "dismiss-wp-pointer"
        }),
        e(this).closest(".fdp-pro-notice").fadeOut(300)
    }),
    e("#eos-dp-setts-nav-wrp").on("mouseenter", function() {
        var s = e("#fdp-main-style-prefetch");
        (null === s || s.length < 1) && e('<link id="fdp-main-style-prefetch" rel="prefetch" href="' + eos_dp_js.main_style + '" as="style" />').appendTo("body")
    }),
    "function" == typeof e.fn.draggable && jQuery(".fdp-draggable").css("cursor", "move").draggable({
        cursorAt: {
            top: 50,
            left: 50
        }
    }),
    e(".fdp-hooks-global-delete").on("click", function() {
        var s = e(this).closest(".fdp-hooks-global-actions");
        return e.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                post_id: s.attr("data-post_id"),
                hook_name: s.attr("data-hook_name"),
                function_name: s.attr("data-function_name"),
                nonce: e("#eos_dp_global_hooks_nonce").val(),
                action: "eos_dp_pro_global_hooks_delete_row"
            },
            success: function(e) {
                "1" === e ? s.closest("tr").css("background", "#ebbcbdf").fadeOut(500) : alert("Hook not removed. Refresh the pag and try again.")
            }
        }),
        !1
    }),
    psiButtons && psiButtons.length > 0 && setInterval(function() {
        var e = "";
        psiButtons.each(function() {
            e = this.href.split("%26eos_dp_preview%3D")[0],
            this.href = e + "%26eos_dp_preview%3D" + Date.now()
        })
    }, 5e3),
    window.onscroll = eos_move_table_head,
    window.onbeforeunload = function(s) {
        window.scrollTo(0, 0),
        e("html, body").css({
            overflow: "hidden",
            height: "100%"
        })
    }
    ,
    fdp_synchronize_dependencies()
}),
jQuery.fn.eos_dp_shiftSelectable = function() {
    var e, s = jQuery(this).attr("class");
    if (void 0 !== s) {
        var t = s.split(" ");
        try {
            $boxes = jQuery("." + t[0] + ",." + t[1])
        } catch (o) {
            throw "." + t[0] + ",." + t[1] + " is not a CSS selector"
        }
        $boxes.on("click", function(s) {
            if (s.shiftKey) {
                if (!e) {
                    e = this;
                    return
                }
                for (var t = jQuery(this).attr("class").split(" "), o = jQuery(e).attr("class"), a = "", d = [], i = 0; i < t.length; i += 1)
                    0 > o.indexOf(t[i]) ? a = t[i] : d.push(t[i]);
                if (sameClass = "." + d.join(","),
                "." === d || window.eos_dp_grouped)
                    e = null,
                    $boxes = null;
                else {
                    var p = jQuery(e).parent(".eos-dp-td-chk-wrp")
                      , n = p.parent().attr("class")
                      , r = jQuery(sameClass).parent(".eos-dp-td-chk-wrp").parent().index(p.parent())
                      , l = jQuery(sameClass).parent(".eos-dp-td-chk-wrp").parent().index(jQuery(this).parent(".eos-dp-td-chk-wrp").parent())
                      , c = jQuery(sameClass).slice(Math.max(0, Math.min(r, l)), Math.max(r, l) + 1);
                    if (n.indexOf("eos-dp-active") > 0)
                        var h = !1;
                    else
                        var h = !0;
                    c.attr("checked", h).trigger("change"),
                    c.parent(".eos-dp-td-chk-wrp").parent().attr("class", n),
                    window.eos_dp_grouped = !0
                }
            }
        }),
        $boxes.on("mouseleave", function(s) {
            s.shiftKey || (e = null,
            $boxes = null)
        })
    }
}
;
function eos_dp_set_cookie(e, s, o) {
    var t, n;
    o ? ((t = new Date).setTime(t.getTime() + 24 * o * 60 * 60 * 1e3),
    n = "; expires=" + t.toGMTString()) : n = "",
    document.cookie = e + "=" + s + n + "; path=/"
}
