jQuery(document).ready(function () {
    jQuery("#wp_keyword_tool_search_btn").click(function () {
        var e = jQuery("#rbkeyword_search_txt").val();
        return "" == e ? (alert("Write Keyword First"), !1) : (jQuery.ajax({
            url: jQuery("#wp_keyword_tool_ajax_src").val() + "&key=" + encodeURIComponent(e),
            context: document.body,
            success: function (e) {
                jQuery("#wp_keyword_tool_ajax-loading").addClass("ajax-loading"), jQuery("#wp_keyword_tool_search_btn").removeAttr("disabled"), jQuery("#wp_keyword_tool_search_btn").removeClass("disabled");
                var r = jQuery.parseJSON(e);
                if ("success" == r.status) {
                    for (var o = r.words, t = r.volume, d = 0; d < o.length; d++) jQuery("#rbkeyword_keywords").append('<div class="wp_keyword_tool_itm "><input type="checkbox" value="' + o[d] + '"><div class="wp_keyword_tool_keyword">' + o[d] + '</div><div class="wp_keyword_tool_volume">' + t[d] + '</div><div class="clear"></div></div>');
                    jQuery("#rbkeyword_body").slideDown()
                } else if ("Error" == r.status) {
                    var y = r.error;
                    jQuery("#suggestionContain").prepend('<a href="#" title="error" class="box errors corners" style="margin-top: 0pt ! important;"><span class="close">&nbsp;</span>' + y + " .</a>"), activate_close()
                }
            },
            beforeSend: function () {
                jQuery("#wp_keyword_tool_ajax-loading").removeClass("ajax-loading"), jQuery("#wp_keyword_tool_search_btn").addClass("disabled"), jQuery("#wp_keyword_tool_search_btn").attr("disabled", "disabled")
            }
        }), !1)
    }), jQuery("#rbkeyword_clean").click(function () {
        return jQuery("#rbkeyword_body").slideUp(), jQuery("#rbkeyword_keywords").slideUp(), jQuery("#rbkeyword_keywords").empty(), jQuery("#rbkeyword_keywords").slideDown(), !1
    });
    var e = 0, r = "", o = "a", t = "";
    jQuery("#rbkeyword_more").click(function () {
        e = 0;
        var d = jQuery("#rbkeyword_search_txt").val();
        for (r = d, jQuery("#rbkeyword_body").show(), letters = rbkeyword_letters, e; e < letters.length; e++) {
            o = letters[e], t = r + " " + o;
            var y = "http://suggestqueries.google.com/complete/search?output=toolbar&hl=en&q=" + rbkeyword_google;
            "https:" === location.protocol && (y = "https://suggestqueries.google.com/complete/search?output=toolbar&hl=en&q=" + rbkeyword_google), jQuery.get(y, "output=json&q=" + t + "&client=firefox", function (e) {
                var r = e[1];
                if (0 == r.length) ; else {
                    jQuery(".rbkeyword_keyword_status").html(jQuery("#rbkeyword_search_txt").val());
                    for (var o = 0; o < r.length; o++) jQuery("#rbkeyword_keywords").append('<label class="wp_keyword_tool_itm "><input type="checkbox" value="' + r[o] + '">' + r[o] + "</label><br>"), jQuery(".rbkeyword_count").html(jQuery("label.wp_keyword_tool_itm").length)
                }
            }, "jsonp")
        }
    }), jQuery("#rbkeyword-list-wrap").dialog({
        autoOpen: !1,
        dialogClass: "wp-dialog",
        position: "center",
        draggable: !1,
        width: 400,
        title: "Keyword List (Copy and Paste)"
    }), jQuery("#rbkeyword_list_btn").click(function () {
        var e = "";
        jQuery("#rbkeyword-list").text(""), jQuery('#rbkeyword_keywords input[type="checkbox"]:checked').each(function () {
            e = e + jQuery(this).val() + "\n"
        }), jQuery("#rbkeyword-list").text(e), jQuery("#rbkeyword-list-wrap").dialog("open")
    }), jQuery("#rbkeyword_search_txt").gcomplete({
        style: "default",
        effect: !1,
        pan: "#rbkeyword_search_txt"
    }), jQuery("#rbkeyword_check").click(function () {
        jQuery('input:checkbox').not(this).prop('checked', this.checked);
    })
});