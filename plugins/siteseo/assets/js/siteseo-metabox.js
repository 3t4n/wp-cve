document.addEventListener("DOMContentLoaded", function () {
    const $ = jQuery;

    $("#siteseo-tabs .hidden").removeClass("hidden");
    $("#siteseo-tabs").tabs();

    /**
     * Execute a function given a delay time
     *
     * @param {type} func
     * @param {type} wait
     * @param {type} immediate
     * @returns {Function}
     */
    var debounce = function (func, wait, immediate) {
        var timeout;
        return function () {
            var context = this,
                args = arguments;
            var later = function () {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };

    /**
     * Get Preview meta title
     */
    $(document).on(
        "change paste keyup",
        "#siteseo_titles_title_meta",
        debounce(function (e) {
            const template = $(this).val();
            const termId = $("#siteseo-tabs").data("term-id");
            const homeId = $("#siteseo-tabs").data("home-id");

            $.ajax({
                method: "GET",
                url: siteseoAjaxRealPreview.ajax_url,
                data: {
                    action: "get_preview_meta_title",
                    template: template,
                    post_id: $("#siteseo-tabs").attr("data_id"),
                    term_id: termId.length === 0 ? undefined : termId,
                    home_id: homeId.length === 0 ? undefined : homeId,
                    nonce: siteseoAjaxRealPreview.get_preview_meta_title,
                },
                success: function (response) {
                    const { data } = response;

                    if (data.length > 0) {
                        $(".snippet-title").hide();
                        $(".snippet-title-default").hide();
                        $(".snippet-title-custom").text(data);
                        $(".snippet-title-custom").show();
                        if ($("#siteseo_titles_title_counters").length > 0) {
                            $("#siteseo_titles_title_counters").text(
                                data.length
                            );
                        }
                        if ($("#siteseo_titles_title_pixel").length > 0) {
                            $("#siteseo_titles_title_pixel").text(
                                pixelTitle(data)
                            );
                        }
                    } else {
                        $(".snippet-title").hide();
                        $(".snippet-title-custom").hide();
                        $(".snippet-title-default").show();
                    }
                },
            });
        }, 300)
    );

    $(document).on('click', '#siteseo-tag-single-title', function(){
        $("#siteseo_titles_title_meta").val(
            siteseo_get_field_length($("#siteseo_titles_title_meta")) +
            $("#siteseo-tag-single-title").attr("data-tag")
        );
        $("#siteseo_titles_title_meta").trigger("paste");
    });
	
    $(document).on('click', '#siteseo-tag-single-site-title', function (){
        $("#siteseo_titles_title_meta").val(
            siteseo_get_field_length($("#siteseo_titles_title_meta")) +
            $("#siteseo-tag-single-site-title").attr("data-tag")
        );
        $("#siteseo_titles_title_meta").trigger("paste");
    });
	
    $(document).on('click', '#siteseo-tag-single-excerpt', function (){
        $("#siteseo_titles_desc_meta").val(
            siteseo_get_field_length($("#siteseo_titles_desc_meta")) +
            $("#siteseo-tag-single-excerpt").attr("data-tag")
        );
        $("#siteseo_titles_title_meta").trigger("paste");
    });
	
    $(document).on('click', '#siteseo-tag-single-sep',function (){
        $("#siteseo_titles_title_meta").val(
            siteseo_get_field_length($("#siteseo_titles_title_meta")) +
            $("#siteseo-tag-single-sep").attr("data-tag")
        );
        $("#siteseo_titles_title_meta").trigger("paste");
    });

    //All variables
    siteseo_universal_tag_dropdown();
});

function siteseo_get_field_length(e) {
	if (e.val().length > 0) {
		meta = e.val() + " ";
	} else {
		meta = e.val();
	}
	return meta;
}
	
// All variables
function siteseo_universal_tag_dropdown(){
	
    let alreadyBind = false;
	
    jQuery(".siteseo-tag-dropdown").each(function (item) {
        const _self = jQuery(this);

        var handleClickLi = function(current) {
            if (_self.hasClass("tag-title")) {
                jQuery("#siteseo_titles_title_meta").val(
                    siteseo_get_field_length(jQuery("#siteseo_titles_title_meta")) +
                    jQuery(current).attr("data-value")
                );
                jQuery("#siteseo_titles_title_meta").trigger("paste");
            }
            if (_self.hasClass("tag-description")) {
                jQuery("#siteseo_titles_desc_meta").val(
                    siteseo_get_field_length(jQuery("#siteseo_titles_desc_meta")) +
                    jQuery(current).attr("data-value")
                );
                jQuery("#siteseo_titles_desc_meta").trigger("paste");
            }
        }

        jQuery(this).on("click", function () {
            jQuery(this).next(".siteseo-wrap-tag-variables-list").toggleClass("open");

            jQuery(this)
                .next(".siteseo-wrap-tag-variables-list")
                .find("li")
                .on("click", function (e) {
                    handleClickLi(this);
                    e.stopImmediatePropagation();
                })
                .on("keyup", function (e) {
                    if (e.keyCode === 13) {
                        handleClickLi(this);
                        e.stopImmediatePropagation();
                    }
                });

            function closeItem(e) {
                if (
                    jQuery(e.target).hasClass("dashicons") ||
                    jQuery(e.target).hasClass("siteseo-tag-single-all")
                ) {
                    return;
                }

                alreadyBind = false;
                jQuery(document).off("click", closeItem);
                jQuery(".siteseo-wrap-tag-variables-list").removeClass("open");
            }

            if (!alreadyBind) {
                alreadyBind = true;
                jQuery(document).on("click", closeItem);
            }
        });
    });
}