(function ($) {
    'use strict';

    $(document).on('click', '.kcseo-group-duplicate', function () {
        var self = $(this),
            wrapper = self.parents('.kcseo-group-wrapper'),
            target = self.parents(".kcseo-group-item"),
            group_id = wrapper.attr('data-group-id'),
            group_index = target.attr('data-index'),
            count = wrapper.find(".kcseo-group-item").length,
            post_fix = "_" + count,
            html = $("<div class='kcseo-group-item' data-index='" + count + "' />");
        html.append('<div class="kc-top-toolbar"><span class="kcseo-remove-group"><span class="dashicons dashicons-trash"></span>Remove</span></div>');
        html.hide();
        target.find("> .field-container ").each(function () {
            var item = $(this).clone(),
                field = item.find(".field-content").find("input, select, textarea") || '',
                name = field.attr("name") || '',
                field_container = item.find(".field-content"),
                label = item.find("label.field-label"),
                label_for = label.attr("for") + post_fix;
            item.attr("id", item.attr("id") + post_fix);
            label.attr("for", label_for);
            field_container.attr("id", field_container.attr("id") + post_fix);
            if (name) {
                field.attr("id", label_for);
                field.attr("name", name.replace(group_id + "[" + group_index + "]", group_id + "[" + count + "]"));
            }
            html.append(item);
        });
        if (wrapper.data('duplicate') === 1) {
            html.append('<div class="kc-bottom-toolbar"><span class="button button-primary kcseo-group-duplicate">Duplicate Item</span></div>');
        }
        wrapper.append(html);
        html.slideDown(500);
    });

    $(document).on('click', 'span.kcseo-remove-group', function () {
        var self = $(this),
            wrapper = self.parents('.kcseo-group-wrapper'),
            target = self.parents(".kcseo-group-item"),
            group_id = wrapper.attr('data-group-id');
        target.slideUp(500, function () {
            $(this).remove();
            wrapper.find("> .kcseo-group-item ").each(function (count, v) {
                var group_index = $(this).attr('data-index'),
                    post_fix = "_" + count;
                $(this).attr('data-index', count);
                $(this).find("> .field-container ").each(function () {
                    var item = $(this),
                        field = item.find(".field-content").find("input, select, textarea") || '',
                        name = field.attr("name") || '',
                        field_container = item.find(".field-content"),
                        label = item.find("label.field-label"),
                        label_for = label.attr("for") + post_fix;
                    item.attr("id", item.attr("id") + post_fix);
                    label.attr("for", label_for);
                    field_container.attr("id", field_container.attr("id") + post_fix);
                    if (name) {
                        field.attr("id", label_for);
                        field.attr("name", name.replace(group_id + "[" + group_index + "]", group_id + "[" + count + "]"));
                    }
                });
            });
        });
    });

    wpSeoShowHideType();
    $("#site_type, #_schema_aggregate_rating_schema_type").change(function () {
        wpSeoShowHideType();
    });

    if ($("#kcseo-wordpres-seo-structured-data-schema-meta-box").length) {

        $("select.select2").select2({
            dropdownAutoWidth: true,
            width: '100%'
        });
    } else {
        $("select.select2").select2({
            dropdownAutoWidth: true
        });
    }


    $(document).on('click', ".social-remove", function () {
        if (confirm("Are you sure?")) {
            $(this).parent('.sfield').slideUp('slow', function () {
                $(this).remove();
            });
        }
    });

    $("#social-add").on('click', function () {
        var bindElement = $("#social-add");
        var count = $("#social-field-holder .sfield").length;
        var arg = 'id=' + count;
        AjaxCall(bindElement, 'newSocial', arg, function (data) {
            if (data.data) {
                $("#social-field-holder").append(data.data);
            }
        });
    });

    $('.schema-tooltip').each(function () { // Notice the .each() loop, discussed below
        $(this).qtip({
            content: {
                text: $(this).next('div') // Use the "div" element next to this for the content
            },
            hide: {
                fixed: true,
                delay: 300
            }
        });
    });

    $(".rt-tab-nav li").on('click', 'a', function (e) {
        e.preventDefault();
        var $this = $(this),
            li = $this.parent(),
            container = $this.parents('.rt-tab-container'),
            nav = container.children('.rt-tab-nav'),
            content = container.children(".rt-tab-content"),
            id = li.data('id');
        content.removeClass('active');
        nav.find('li').removeClass('active');
        li.addClass('active');
        container.find('#' + id).addClass('active');
        container.find('#_kcseo_ative_tab').val(id);
    });

    $(".kSeoImgAdd").on("click", function (e) {
        var file_frame,
            $this = $(this).parents('.kSeo-image-wrapper');
        if (undefined !== file_frame) {
            file_frame.open();
            return;
        }
        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select or Upload Media For your profile gallery',
            button: {
                text: 'Use this media'
            },
            multiple: false
        });
        file_frame.on('select', function () {
            var attachment = file_frame.state().get('selection').first().toJSON(),
                imgId = attachment.id,
                imgUrl = (typeof attachment.sizes.thumbnail === "undefined") ? attachment.url : attachment.sizes.thumbnail.url,
                imgInfo = "<span><strong>URL: </strong>" + attachment.sizes.full.url + "</span>",
                imgInfo = imgInfo + "<span><strong>Width: </strong>" + attachment.sizes.full.width + "px</span>",
                imgInfo = imgInfo + "<span><strong>Height: </strong>" + attachment.sizes.full.height + "px</span>";
            $this.find('input').val(imgId);
            $this.find('.kSeoImgRemove').removeClass('kSeo-hidden');
            $this.find('img').remove();
            $this.find('.kSeo-image-preview').append("<img src='" + imgUrl + "' />");
            $this.parents('.kSeo-image').find('.image-info').html(imgInfo);
        });
        // Now display the actual file_frame
        file_frame.open();
    });

    $(".kSeoImgRemove").on("click", function (e) {
        e.preventDefault();
        if (confirm("Are you sure?")) {
            var $this = $(this).parents('.kSeo-image-wrapper');
            $this.find('input').val('');
            $this.find('.kSeoImgRemove').addClass('kSeo-hidden');
            $this.find('img').remove();
            $this.parents('.kSeo-image').find('.image-info').html('');
        }
    });

    function wpSeoShowHideType() {
        if ($('#_schema_aggregate_rating_schema_type').length) {
            var id = $("#_schema_aggregate_rating_schema_type option:selected").val();
        }
        if ($('#site_type').length) {
            var id = $("#site_type option:selected").val();
        }

        if (id == "Person") {
            $(".form-table tr.person, .aggregate-person-holder").show();
        } else {
            $(".form-table tr.person, .aggregate-person-holder").hide();
        }
        if (id == "Organization") {
            $(".form-table tr.business-info,.form-table tr.all-type-data, .aggregate-except-organization-holder").hide();
        } else {
            $(".form-table tr.business-info,.form-table tr.all-type-data, .aggregate-except-organization-holder").show();
        }

        if ($.inArray(id, ['FoodEstablishment', 'Bakery', 'BarOrPub', 'Brewery', 'CafeOrCoffeeShop', 'FastFoodRestaurant', 'IceCreamShop', 'Restaurant', 'Winery']) >= 0) {
            $(".form-table tr.restaurant").show();
        } else {
            $(".form-table tr.restaurant").hide();
        }
    }

    $("#kcseo-option-settings").on('submit', function (e) {
        e.preventDefault();
        $('#response').hide();
        var arg = $(this).serialize(),
            bindElement = $('#tlpSaveButton');
        AjaxCall(bindElement, 'kcSeoWpSchemaSettings', arg, function (data) {
            $('#response').addClass('updated');
            if (!data.error) {
                $('#response').removeClass('error');
            } else {
                $('#response').addClass('error');
            }
            $('#response').show('slow').text(data.msg);
        });
    });
    $("#kcseo-main-settings").on('submit', function (e) {
        e.preventDefault();
        $('#response').hide();
        var arg = $(this).serialize(),
            bindElement = $('#tlpSaveButton');
        AjaxCall(bindElement, 'kcSeoMainSettings_action', arg, function (data) {
            $('#response').addClass('updated');
            if (!data.error) {
                $('#response').removeClass('error');
                $('#response').show('slow').text(data.msg);
            } else {
                $('#response').addClass('error');
                $('#response').show('slow').text(data.msg);
            }
        });
        return false;
    });


    function AjaxCall(element, action, arg, handle) {
        var data;
        if (action) data = "action=" + action;
        if (arg) data = arg + "&action=" + action;
        if (arg && !action) data = arg;
        data = data;

        $.ajax({
            type: "post",
            url: ajaxurl,
            data: data,
            beforeSend: function () {
                $("<span class='wseo_loading'></span>").insertAfter(element);
            },
            success: function (data) {
                $(".wseo_loading").remove();
                handle(data);
            }
        });
    }

})(jQuery);


