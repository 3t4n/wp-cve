/* Define Global Variables */
var $targetElement;
var $listLevel = 1;
var $class = "level-" + $listLevel;
var expt = jQuery('<span class="wshs-post-exp"><br/></span>');
var filter_max_level = 0;

/* post-type post listing */
function displayPostsToListing(data) {

    jQuery.each(data, function(key, value) {
        $class = "level-" + $listLevel;
        if (filter_max_level == 0) {
            filter_max_level = 1;
        } else if ($listLevel > filter_max_level) {
            filter_max_level = $listLevel;
        }
        if ($listLevel == 1) {
            $post_id = value.ID;
        }
        cb = jQuery('<input />',
                {
                    type: 'checkbox',
                    id: 'cb' + value.ID,
                    value: value.ID,
                    class: "wshs-post-checkbox"
                }
        ).change(wshsOrganiseExcludeArray);

        if (value.post_date != '') {
            dat = '<em class="wshs-post-date">' + value.post_date + '</em>';
        }

        /* Display expert content in admin side */
        if (value.post_excerpt) {
            expt = '<span class="wshs-post-exp">' + value.post_excerpt + '</span>';
        } else if (value.post_content) {
            expt = '<span class="wshs-post-exp">' + value.post_content + '</span>';
        } else {
            expt = "";
        }

        /* Display featured image in admin side */
        if (value.post_image) {
            featureimg = jQuery('<img src="' + value.post_image + '" height="30" width="30" class="wshs-post-img">');
        } else {
            featureimg = jQuery('<img src="'+wshs_ajax_object.placeholder_image+'/images/placeholder.svg" height="30" width="30" class="wshs-post-img">');
        }

        li = jQuery('<li/>', {
            class: $class,
            id: "wshs_post_" + value.ID,
            "data-page-index": $listLevel,
            "data-page": $post_id,
        }).text(value.title);

        featureimg.appendTo(li);

        var div_data = jQuery("<div class='div-data-sitemap'>" + expt + " " + dat + "</div>");
        div_data.appendTo(li);

        cb.appendTo(li);
        li.appendTo($targetElement);

        if (jQuery("#wshs_display_date").is(':checked')) {
            jQuery('.wshs-post-date').show();
        }
        if (jQuery("#wshs_display_date_page").is(':checked')) {
            jQuery('.wshs-post-date').show();
        }

        if (jQuery("#wshs_display_excerpt").is(':checked')) {
            jQuery('.wshs-post-exp').show();
        }
        if (jQuery("#wshs_display_excerpt_page").is(':checked')) {
            jQuery('.wshs-post-exp').show();
        }

        if (jQuery("#wshs_display_image").is(':checked')) {
            jQuery('.wshs-post-img').show();
        }
        if (jQuery("#wshs_display_image_page").is(':checked')) {
            jQuery('.wshs-post-img').show();
        }
        if (typeof value.children != "undefined") {
            jQuery('<option />', {value: value.ID}).text(value.title).appendTo('#wshs_select_parent');
            $listLevel++;
            displayPostsToListing(value.children);
            $listLevel--;
            $class = "level-" + $listLevel;
        }
    });

}

/* Organise exclude post array */
function wshsOrganiseExcludeArray() {
    getShortCode();
}

/* Get list of posts */
function wshsGetList() {

    let excludeArray = [];
    jQuery(document).find(".wshs-post-checkbox:checked").each(function() {
        excludeArray.push(jQuery(this).val());
    });

    var postData = {
        action: 'wshs_get_posts_by_type',
        security: wshs_ajax_object.ajax_nonce,
        type: jQuery('.wshs_select_type').val(),
        taxonomyslug: wshsGetTaxonomyName(),
        termsslug: wshsGetTaxonomyTermsName(),
        orderby: jQuery('#wshs_select_order').val(),
        order: jQuery('#wshs_select_order_asc').val(),
        dateformate: wshsGetDateFormate()
    };
    jQuery(".loading-sitemap").show();
    jQuery.post(ajaxurl, postData, function(response) {
        $targetElement.html('');
        jQuery('#wshs_select_parent').html('<option value="">Select parent</option>');
        if (response.length > 0) {
            displayPostsToListing(response);
            jQuery(".loading-sitemap").hide();
        } else {
            jQuery('<li/>').text("No record available for this post type").appendTo($targetElement);
            jQuery(".loading-sitemap").hide();
        }
        jQuery.each(excludeArray, function(index, val){
            jQuery('.wshs-post-checkbox[value="'+val+'"]').prop('checked', true).trigger('change');
        });
        getShortCode();
        setTimeout(() => {
            jQuery(document).trigger('sitemaploaded');
        }, 500);
    });
}

/* Get list of Pages */
function wshsGetListPage() {
    let selected_parent = jQuery("#wshs_select_parent option:selected").val();
    let selected_depth = jQuery("#wshs_select_depth option:selected").val();
    let excludeArray = [];
    jQuery(document).find(".wshs-post-checkbox:checked").each(function() {
        excludeArray.push(jQuery(this).val());
    });
    
    var postData = {
        action: 'wshs_get_posts_by_type',
        security: wshs_ajax_object.ajax_nonce,
        type: wshsGetPostTypePage(),
        orderby: jQuery('#wshs_select_order_page').val(),
        order: jQuery('#wshs_select_order_asc_page').val(),
        dateformate: wshsGetDateFormatePage()
    };

    jQuery(".loading-sitemap").show();
    jQuery.post(ajaxurl, postData, function(response) {
        $targetElement.html('');
        jQuery('#wshs_select_parent').html('<option value="">Select parent</option>');
        if (response.length > 0) {
            displayPostsToListing(response);

            jQuery("#wshs_select_parent option[value='"+selected_parent+"']").prop('selected', true);
            jQuery("#wshs_select_depth option[value='"+selected_depth+"']").prop('selected', true);   
            set_filter_attribute();            
            jQuery(".loading-sitemap").hide();
        } else {
            jQuery('<li/>').text("No record available for this post type").appendTo($targetElement);
            jQuery(".loading-sitemap").hide();
        }
        jQuery(document).trigger('sitemaploaded');
        jQuery.each(excludeArray, function(index, val){
            jQuery('.wshs-post-checkbox[value="'+val+'"]').prop('checked', true).trigger('change');
        });
    });
}

/* Get list of Taxonomy */
function wshsGetTaxonomyList() {
    var postData = {
        action: 'wshs_get_posts_by_taxonomy',
        security: wshs_ajax_object.ajax_nonce,
        type: jQuery('.wshs_select_type').val()
    };
    jQuery.post(ajaxurl, postData, function(response) {
        result = response;
        if (result.data.length > 0) {
            jQuery('#wshs_taxonomy_list').html(result.data);
        }
        getShortCode();
    });
}

/* Get list of Taxonomy post list */
function wshsGetTaxonomyListPost() {
    if (jQuery('#wshs_taxonomy_list').val() != "") {
        let excludeArray = [];
        jQuery(document).find(".wshs-post-checkbox:checked").each(function() {
            excludeArray.push(jQuery(this).val());
        });
        var postData = {
            action: 'wshs_get_posts_by_taxonomy_post',
            security: wshs_ajax_object.ajax_nonce,
            type: jQuery('.wshs_select_type').val(),
            catslug: wshsGetTaxonomyName(),
            dateformate: wshsGetDateFormate(),
            orderby: jQuery('#wshs_select_order').val(),
            order: jQuery('#wshs_select_order_asc').val(),
        };
        jQuery(".loading-sitemap").show();
        jQuery.post(ajaxurl, postData, function(response) {
            $targetElement.html('');
            if (response.length > 0) {
                displayPostsToListing(response);
                jQuery(".loading-sitemap").hide();
            }
            jQuery.each(excludeArray, function(index, val){
                jQuery('.wshs-post-checkbox[value="'+val+'"]').prop('checked', true).trigger('change');
            });
            getShortCode();
        });
    }
}

/* Display Taxonomy Terms list */
function wshsGetTaxonomyTerms() {
    var postData = {
        action: 'wshs_get_posts_by_taxonomy_terms',
        security: wshs_ajax_object.ajax_nonce,
        taxonomyname: wshsGetTaxonomyName()
    };
    jQuery.post(ajaxurl, postData, function(response) {
        result = response;
        if (result.data.length > 0) {
            jQuery('#wshs_taxonomy_list_chield').html(result.data);
        }
        getShortCode();
    });

}

/* Get list of Taxonomy Terms post list */
function wshsGetTaxonomyTermsListPost() {
    let excludeArray = [];
    jQuery(document).find(".wshs-post-checkbox:checked").each(function() {
        excludeArray.push(jQuery(this).val());
    });
    jQuery.ajax({
        url: ajaxurl,
        data: {
            'action': 'wshs_get_posts_by_taxonomy_terms_posts',
            'security': wshs_ajax_object.ajax_nonce,
            'type': jQuery('.wshs_select_type').val(),
            'taxonomyslug': wshsGetTaxonomyName(),
            'termsslug': wshsGetTaxonomyTermsName(),
            'dateformate': wshsGetDateFormate(),
            'orderby': jQuery('#wshs_select_order').val(),
            'order': jQuery('#wshs_select_order_asc').val(),
        },
        type: "POST",
        beforeSend: function (xhr) {
                jQuery(".loading-sitemap").show();
        },
        success: function (response) {
            $targetElement.html('');
            if (response.length > 0) {
                displayPostsToListing(response);
                jQuery(".loading-sitemap").hide();
            }
            jQuery.each(excludeArray, function (index, val) {
                jQuery('.wshs-post-checkbox[value="' + val + '"]').prop('checked', true).trigger('change');
            });
            getShortCode();
        },
        error: function (xhr, status, error) {
            // Display an error message or perform any necessary actions
            console.error("AJAX Error: " + status + " - " + error);
            alert("AJAX Error: " + status + " - " + error);
            // You can also hide loading indicator or perform any cleanup here
            jQuery(".loading-sitemap").hide();
        }



    })
    // var postData = {
    //     action: 'wshs_get_posts_by_taxonomy_terms_post',
    //     security: wshs_ajax_object.ajax_nonce,
    //     type: jQuery('.wshs_select_type').val(),
    //     taxonomyslug: wshsGetTaxonomyName(),
    //     termsslug: wshsGetTaxonomyTermsName(),
    //     dateformate: wshsGetDateFormate(),
    //     orderby: jQuery('#wshs_select_order').val(),
    //     order: jQuery('#wshs_select_order_asc').val(),
    // };
    // jQuery(".loading-sitemap").show();
    // jQuery.post(ajaxurl, postData, function(response) {
    //     $targetElement.html('');
    //     if (response.length > 0) {
    //         displayPostsToListing(response);
    //         jQuery(".loading-sitemap").hide();
    //     }
    //     jQuery.each(excludeArray, function(index, val){
    //         jQuery('.wshs-post-checkbox[value="'+val+'"]').prop('checked', true).trigger('change');
    //     });
    //     getShortCode();
    // });
}

/* Get post-type date shortcode */
function wshsGetDateFormate() {
    return jQuery('#wshs_show_date_format').val();
}

/* Get page post-type date Shortcode */
function wshsGetDateFormatePage() {
    return jQuery('#wshs_show_date_format_page').val();
}

/* Get page post-type of Shortcode */
function wshsGetPostTypePage() {
    return jQuery('.wshs_select_type_page').val();
}

/* Get shortcode page title  */
function wshsGetPostTypePageTitle() {
    var element = jQuery('.wshs_select_type_page').find('option:selected');
    var pageTile = element.attr("data-title");
    return pageTile;
}

/* Get shortcode post title  */
function wshsGetPostTypePostTitle() {
    var element = jQuery('#wshs_select_type').find('option:selected');
    var pageTile = element.attr("data-title");
    return pageTile;
}

/* Get order by post of shortcode */
function wshsGetOrderBy() {
    return jQuery('#wshs_select_order').val();
}
/* Get order by post of shortcode */
function wshsGetOrder() {
    return jQuery('#wshs_select_order_asc').val();
}

/* Get Taxonomy name */
function wshsGetTaxonomyName() {
    return jQuery('#wshs_taxonomy_list').val();
}

/* Get Taxonomy Terms name */
function wshsGetTaxonomyTermsName() {
    return jQuery('#wshs_taxonomy_list_chield').val();
}

/* Get post-type exclude array shortcode */
function wshsExcludePosts() {
    $excludeArray = [];
    jQuery(document).find(".wshs-post-checkbox:checked").each(function() {
        $excludeArray.push(jQuery(this).val());
    });
    if ($excludeArray.length > 0) {
        return ' exclude="' + $excludeArray.join(', ') + '"';
    }
    return '';
}

/* Get image option post details of shortcode */
function wshsImageDetails() {
    if (jQuery('#wshs_display_image').is(":checked")) {
        jQuery('.wshs_image_size').fadeIn();

        imgWidthInput = jQuery('.wshs_image_size input[name="wshs_image_width"]').val();
        img_width = '';
        if (imgWidthInput !== '') {
            img_width = ' image_width="' + parseInt(imgWidthInput) + '" ';
        }
        imgHeightInput = jQuery('.wshs_image_size input[name="wshs_image_height"]').val();
        img_height = '';
        if (imgHeightInput !== '') {
            img_height = ' image_height="' + parseInt(imgHeightInput) + '" ';
        }
        return ' show_image="' + true + '"' + img_width + img_height;
    } else {
        jQuery('.wshs_image_size input').val("");
        jQuery('.wshs_image_size').fadeOut();
        return '';
    }
}

/* Get image option page details of shortcode */
function wshsImageDetailsPage() {
    if (jQuery('#wshs_display_image_page').is(":checked")) {
        jQuery('.wshs_image_size_page').fadeIn();
        imgWidthInput = jQuery('.wshs_image_size_page input[name="wshs_image_width_page"]').val();
        img_width = '';
        if (imgWidthInput !== '') {
            img_width = ' image_width="' + parseInt(imgWidthInput) + '" ';
        }
        imgHeightInput = jQuery('.wshs_image_size_page input[name="wshs_image_height_page"]').val();
        img_height = '';
        if (imgHeightInput !== '') {
            img_height = ' image_height="' + parseInt(imgHeightInput) + '" ';
        }
        return ' show_image="' + true + '"' + img_width + img_height;
    } else {
        jQuery('.wshs_image_size_page input').val("");
        jQuery('.wshs_image_size_page').fadeOut();
        return '';
    }
}

/* Get horizontal view option details of shortcode */
function wshsHorizontalDetails() {
    if (jQuery('#wshs_display_horizontal').is(":checked")) {
        jQuery('.wshs_horizontal_view').fadeIn();
        SeparatorInput = jQuery('#wshs_horizontal_separator').val();
        Separator = '';
        if (SeparatorInput !== '') {
            Separator = ' separator="' + SeparatorInput + '" ';
        }
        return ' horizontal="' + true + '"' + Separator;
    } else {
        jQuery('.wshs_horizontal_view').fadeOut();
        return '';
    }
}

/* Get show date post option details of shortcode */
function wshsShowDateDetails() {
    if (jQuery('#wshs_display_date').is(":checked")) {
        jQuery('.wshs_show_date_view').fadeIn();
        //CreatedDate = 'created';
        //ShowDate = ' date="' + CreatedDate + '" ';
        FormatInput = jQuery('#wshs_show_date_format').val();
        FormatDate = '';
        if (FormatInput !== '') {
            FormatDate = ' date_format="' + FormatInput + '"';
        }
        return ' show_date="' + true + '"' + FormatDate;
        //return ' show_date="' + true + '"' + ShowDate + FormatDate;
    } else {
        jQuery('.wshs_show_date_view').fadeOut();
        return '';
    }
}

/* Get show date page option details of shortcode */
function wshsShowDateDetailsPage() {
    if (jQuery('#wshs_display_date_page').is(":checked")) {
        jQuery('.wshs_show_date_view_page').fadeIn();
        //CreatedDate = 'created';
        //ShowDate = ' date="' + CreatedDate + '" ';
        FormatInput = jQuery('#wshs_show_date_format_page').val();
        FormatDate = '';
        if (FormatInput !== '') {
            FormatDate = ' date_format="' + FormatInput + '"';
        }
        return ' show_date="' + true + '"' + FormatDate;
        //return ' show_date="' + true + '"' + ShowDate + FormatDate;
    } else {
        jQuery('.wshs_show_date_view_page').fadeOut();
        return '';
    }
}

/* Get excerpt option post details of shortcode */
function wshsExcerptDetails() {
    if (jQuery('#wshs_display_excerpt').is(":checked")) {
        jQuery('.wshs_excerpt_limit').fadeIn();
        excerptLimitInput = jQuery('.wshs_excerpt_limit input[name="wshs_excerpt_length"]').val();
        if (excerptLimitInput != '') {
            return ' content_limit="' + parseInt(excerptLimitInput) + '"';
        } else {
            return '';
        }
    } else {
        jQuery('.wshs_excerpt_limit').fadeOut();
        return '';
    }
}

/* Get excerpt option page details of shortcode */
function wshsExcerptDetailsPage() {
    if (jQuery('#wshs_display_excerpt_page').is(":checked")) {
        jQuery('.wshs_excerpt_limit_page').fadeIn();
        excerptLimitInput = jQuery('.wshs_excerpt_limit_page input[name="wshs_excerpt_length_page"]').val();
        if (excerptLimitInput != 0) {
            return ' content_limit="' + parseInt(excerptLimitInput) + '"';
        } else {
            return '';
        }
    } else {
        jQuery('.wshs_excerpt_limit_page').fadeOut();
        return '';
    }
}

/* Get the Taxonomy name of the shortcut */
function wshsTaxonomyNames() {
    TaxonomyName = jQuery('#wshs_taxonomy_list').val();
    if (TaxonomyName != '') {
        return ' taxonomy="' + TaxonomyName + '"';
    } else {
        return '';
    }
}

/* Get Taxonomy Terms name of the shortcut */
function wshsTaxonomyTermsNames() {
    TermsName = jQuery('#wshs_taxonomy_list_chield').val();
    if (TermsName != '') {
        return ' terms="' + TermsName + '"';
    } else {
        return '';
    }
}

/* Display only sub pages */
function wshsGetSubOnly() {
    if (jQuery('#wshs_select_parent').val() != '') {
        return ' child_of ="' + jQuery('#wshs_select_parent').val() + '"';
    }
    return '';
}

function set_filter_attribute() {
    jQuery("#wshs_admin_post_list ul li[data-page-index='1']").each(function() {
        jQuery(this).attr("data-parents", jQuery(this).attr("data-page"));
    });
    if (filter_max_level != 1) {
        /* When maximum length not greater than 1 */
        for (i = 2; i <= filter_max_level; i++) {
            jQuery("#wshs_admin_post_list ul li[data-page-index='" + i + "']").each(function() {
                data_parents = jQuery(this).prevAll("[data-page-index='" + (i - 1) + "']").attr("data-parents");
                jQuery(this).attr("data-parents", data_parents + "," + jQuery(this).attr("id").split("_")[2]);
            });
        }
    }
}

/* Display depth of child of the pages */
function wshsGetDepth() {
    jQuery('#wshs_admin_post_list li').addClass('disabled');
    var depth1 = jQuery('#wshs_select_depth').val() != "" ? parseInt(jQuery('#wshs_select_depth').val()) : 1;
    var childidselect = jQuery("#wshs_select_parent").val();

    if (childidselect != "" && depth1 != "") {
        var start_count = jQuery("#wshs_post_" + childidselect).attr("data-page-index");
        var post_id = jQuery("#wshs_post_" + childidselect).attr("data-page");
        var end_count = parseInt(depth1) + parseInt(start_count);
        for (count = start_count; count <= end_count; count++) {
            jQuery(document).find("[data-page='" + post_id + "']").each(function() {
                var current = jQuery(this);
                if (current.attr("data-page-index") == count) {
                    var parents = current.attr("data-parents");
                    parents = parents.split(",");
                    if (jQuery.inArray(childidselect, parents) >= 0) {
                        current.removeClass("disabled");
                    }
                }
            });
        }
    }
    if (childidselect == "") {
        jQuery("#wshs_admin_post_list ul li").each(function() {
            jQuery('#wshs_admin_post_list li').removeClass('disabled');
        });
    }
}

function wshsDisplayDepth() {
    var depth = jQuery('#wshs_select_depth').val();
    if (depth != '') {
        return ' depth="' + depth + '"';
    } else {
        return '';
    }
}

/* Display page column layout */
function wshsDisplayColumnPages() {
    //var layout = jQuery('#wshs_select_column_page').val();
    var layout = jQuery('#wshs_select_column_page').find('option:selected');
    var layoutTile = layout.attr("data-title");
    if (layoutTile != '') {
        return ' layout="' + layoutTile + '"';
    } else {
        return '';
    }
}
function wshsDisplayColumnPositionPages() {
    var position = jQuery('#wshs_select_column_position_page').val();
    if (position != '') {
        return ' position="' + position + '"';
    } else {
        return '';
    }
}

/* Display post column layout */
function wshsDisplayColumnPosts() {

    var layout = jQuery('#wshs_select_column_post').find('option:selected');
    var layoutTile = layout.attr("data-title");
    if (layoutTile != '') {
        return ' layout="' + layoutTile + '"';
    } else {
        return '';
    }
}
function wshsDisplayColumnPositionPosts() {
    var position = jQuery('#wshs_select_column_position_post').val();
    if (position != '') {
        return ' position="' + position + '"';
    } else {
        return '';
    }
}

function getShortCode() {
    shortcode = "[wshs_list ";
    if (wshsGetPostTypePage() == 'page') {
        shortcode += ' post_type="' + wshsGetPostTypePage() + '"';
        shortcode += ' name="' + wshsGetPostTypePageTitle() + '"';
        shortcode += ' order_by="' + jQuery('#wshs_select_order_page').val() + '"';
        shortcode += ' order="' + jQuery('#wshs_select_order_asc_page').val() + '"';
        shortcode += wshsImageDetailsPage();
        shortcode += wshsExcerptDetailsPage();
        shortcode += wshsShowDateDetailsPage();
        shortcode += wshsGetSubOnly();
        shortcode += wshsDisplayDepth();
        shortcode += wshsDisplayColumnPages();
        shortcode += wshsDisplayColumnPositionPages();
    } else {
        shortcode += ' post_type="' + jQuery("#wshs_select_type").val() + '"';
        shortcode += ' name="' + wshsGetPostTypePostTitle() + '"';
        shortcode += ' order_by="' + jQuery('#wshs_select_order').val() + '"';
        shortcode += ' order="' + jQuery('#wshs_select_order_asc').val() + '"';
        shortcode += wshsImageDetails();
        shortcode += wshsExcerptDetails();
        shortcode += wshsShowDateDetails();
        shortcode += wshsDisplayColumnPosts();
        shortcode += wshsDisplayColumnPositionPosts();
    }

    shortcode += wshsExcludePosts();
    if (wshsGetPostTypePage() != 'page') {
        shortcode += wshsTaxonomyNames();
        shortcode += wshsTaxonomyTermsNames();
    }
    shortcode += wshsHorizontalDetails();
    shortcode += "]";
    jQuery('#wshs_shortcode').text(shortcode);
}

jQuery(document).ready(function() {
    $targetElement = jQuery('#wshs_admin_post_list ul');
    jQuery('#wshs_shortcode').text('');
    jQuery('#wshs_taxonomy_list').change(function() {
        wshsGetTaxonomyListPost();
        wshsTaxonomyNames();
        wshsGetTaxonomyTerms();
    });

    jQuery('#wshs_taxonomy_list_chield').change(function() {
        wshsGetTaxonomyTermsListPost();
        wshsTaxonomyTermsNames();
    });

    /* Change value in the show column page change time */
    jQuery("#wshs_select_column_page").change(function() {
        var colvalue = jQuery(this).val();
        if (colvalue == 'half') {
            jQuery(".position-page").show();
        } else {
            jQuery(".position-page").hide();
            jQuery("#wshs_select_column_position_page").val("").prop("selected", "selected");
        }
    });

    /* Change value in the show column post change time */
    jQuery("#wshs_select_column_post").change(function() {
        var colvalue = jQuery(this).val();
        if (colvalue == 'half') {
            jQuery(".position-post").show();
        } else {
            jQuery(".position-post").hide();
            jQuery("#wshs_select_column_position_post").val("");
        }
    });

    /* Use of post-type post select time */
    jQuery('.wshs_select_type').on('change', function() {
        if (jQuery('.wshs_select_type').val() != '') {
            wshsGetList();
            wshsGetTaxonomyList();
            jQuery(".short-code-main").show();
            jQuery("#wshs_admin_post_list").show();
            jQuery('#wshs_select_order').removeAttr('disabled');
            jQuery('#wshs_select_order_asc').removeAttr('disabled');
            jQuery('#wshs_taxonomy_list').removeAttr('disabled');
            jQuery('#wshs_taxonomy_list_chield').removeAttr('disabled');

            /* Image checkbox code */
            jQuery('#wshs_display_image').prop("checked", false).removeAttr('disabled');

            /* Excerpt checkbox code */
            jQuery('#wshs_display_excerpt').prop("checked", false).removeAttr('disabled');

            /* Date checkbox code */
            jQuery('#wshs_display_date').prop("checked", false).removeAttr('disabled');

            /* Child of selectbox */
            jQuery('#wshs_select_parent').attr('disabled', 'disabled');

            /* Column select box */
            jQuery("#wshs_select_column_post").removeAttr('disabled');
            getShortCode();
        } else {
            jQuery(".short-code-main").hide();
            jQuery("#wshs_admin_post_list").hide();
        }
    });

    jQuery('#wshs_select_order').on('change', function() {
        // jQuery("#wshs_taxonomy_list").val("");
        wshsGetList();
    });
    jQuery('#wshs_select_order_asc').on('change', function() {
        wshsGetList();
    });

    /* Use of post-type page select time */
    jQuery('.wshs_select_type_page').on('change', function() {
        if (jQuery('.wshs_select_type_page').val() != '') {
            wshsGetListPage();
            jQuery(".short-code-main").show();
            jQuery("#wshs_admin_post_list").show();

            jQuery('#wshs_select_order_page').removeAttr('disabled');
            jQuery('#wshs_select_order_asc_page').removeAttr('disabled');

            /* Image checkbox code */
            jQuery('#wshs_display_image_page').prop("checked", false).removeAttr('disabled');

            /* Excerpt checkbox code */
            jQuery('#wshs_display_excerpt_page').prop("checked", false).removeAttr('disabled');

            /* Date checkbox code */
            jQuery('#wshs_display_date_page').prop("checked", false).removeAttr('disabled');

            /* Child of selectbox */
            jQuery('#wshs_select_parent').removeAttr('disabled');

            /* Depth select box */
            jQuery("#wshs_select_depth").removeAttr('disabled');

            /* Column select box */
            jQuery("#wshs_select_column_page").removeAttr('disabled');
            getShortCode();
        } else {
            jQuery(".short-code-main").hide();
            jQuery("#wshs_admin_post_list").hide();
            jQuery("#wshs_select_order_page").attr("disabled", "disabled");
            jQuery("#wshs_select_order_asc_page").attr("disabled", "disabled");
            jQuery("#wshs_select_parent").attr("disabled", "disabled");
            jQuery("#wshs_select_depth").attr("disabled", "disabled");
            jQuery("#wshs_select_column_page").attr("disabled", "disabled");
        }
    });

    jQuery('#wshs_select_order_page').on('change', function() {
        wshsGetListPage();
        jQuery(document).find("input.wshs-post-checkbox").prop("checked", false);
        getShortCode();
    });
    jQuery('#wshs_select_order_asc_page').on('change', function() {
        wshsGetListPage();
        getShortCode();
    });
    jQuery('#wshs_show_date_format').on('change', wshsGetList);
    jQuery('#wshs_show_date_format_page').on('change', function() {
        wshsGetListPage();
        jQuery(document).find("input.wshs-post-checkbox").prop("checked", false);
        getShortCode();
    });
    jQuery('#wshs_display_image,#wshs_display_image_page').on('change', function() {
        if (jQuery(this).is(':checked')) {
            jQuery('.wshs-post-img').show();
        } else {
            jQuery('.wshs-post-img').hide();
            /* image check box uncheck time set image width */
            jQuery("#wshs_admin_post_list img").each(function() {
                jQuery(this).attr("width", 30);
                jQuery(this).attr("height", 30);
            });
        }
        getShortCode();
    });
    jQuery('#wshs_display_excerpt,#wshs_display_excerpt_page').on('change', function() {
        if (jQuery(this).is(':checked')) {
            jQuery('.wshs-post-exp').show();
        } else {
            jQuery('.wshs-post-exp').hide();
        }
        getShortCode();
    });
    jQuery('#wshs_select_parent,#wshs_select_depth').on('change', function() {
        // jQuery(document).find("input.wshs-post-checkbox").prop("checked", false);
        wshsGetDepth();
        getShortCode();
    });
    jQuery('.wshs_image_size input').on('blur', function() {
        $imgwidth = jQuery("[name='wshs_image_width']").val();
        $imgheight = jQuery("[name='wshs_image_height']").val();
        if ($imgwidth == "") {
            $imgwidth = "30";
        }
        if ($imgheight == "") {
            $imgheight = "30";
        }
        jQuery("#wshs_admin_post_list img").each(function() {
            jQuery(this).attr("width", $imgwidth);
            jQuery(this).attr("height", $imgheight);
        });
        getShortCode();
    });
    jQuery('.wshs_image_size_page input').on('blur', function() {
        $imgwidth = jQuery("[name='wshs_image_width_page']").val();
        $imgheight = jQuery("[name='wshs_image_height_page']").val();
        if ($imgwidth == "") {
            $imgwidth = "30";
        }
        if ($imgheight == "") {
            $imgheight = "30";
        }
        jQuery("#wshs_admin_post_list img").each(function() {
            jQuery(this).attr("width", $imgwidth);
            jQuery(this).attr("height", $imgheight);
        });
        getShortCode();
    });
    jQuery('.wshs_excerpt_limit input').on('blur', getShortCode);
    jQuery('.wshs_excerpt_limit_page input').on('blur', getShortCode);
    jQuery('#wshs_display_horizontal').on('change', getShortCode);
    jQuery('#wshs_horizontal_separator').on('change', getShortCode);
    jQuery('#wshs_display_date,#wshs_display_date_page').on('change', function() {
        if (jQuery(this).is(':checked')) {
            jQuery('.wshs-post-date').show();
        } else {
            jQuery('.wshs-post-date').hide();
        }
        getShortCode();
    });
    jQuery('#wshs_show_date_format').on('change', getShortCode);
    jQuery('#wshs_select_column_page').on('change', getShortCode);
    jQuery('#wshs_select_column_position_page').on('change', getShortCode);
    jQuery('#wshs_select_column_post').on('change', getShortCode);
    jQuery('#wshs_select_column_position_post').on('change', getShortCode);

    /* Copy shortcode */
    jQuery('.short-code-copy-btn').on('click', function(){
        let text = jQuery(this).parent().prev().text();
        let elm = jQuery(this);
        jQuery(elm).text('Copied');
        var copyElement = document.createElement('input');
        copyElement.setAttribute('type', 'text');
        copyElement.setAttribute('value', text);
        copyElement = document.body.appendChild(copyElement);
        copyElement.select();
        document.execCommand('copy');
        copyElement.remove();
        setTimeout(function(){
            jQuery(elm).text('Copy');
        },1000)
    });

    /* Save generated shortcode */
    jQuery('.short-code-save-btn').on('click', function(){
        if(jQuery('#wshs_code_title').val() == ''){
            alert('Please enter sitemap title.');
            return false;
        }
        var element = jQuery(this);
        jQuery(element).text('Processing...');
        var postData = {
            action: 'wshs_save_shortcode',
            security: wshs_ajax_object.ajax_nonce,
            code: jQuery(this).parent().prev().text(),
            type: jQuery(this).data('type'),
            id: jQuery(this).data('id'),
            title: jQuery('#wshs_code_title').val()
        };
        jQuery.post(ajaxurl, postData, function(response) {
            result = response;
            jQuery(element).data('id', result.id);
            jQuery(element).text('Save');
        });
    });

    
    if(typeof existing_atts != 'undefined'){
        // For pages tab
        if(existing_atts.post_type == 'page'){
            jQuery('#wshs_select_type option[value="'+existing_atts.post_type+'"]').prop('selected', true);

            if(typeof existing_atts.order_by != 'undefined'){
                jQuery('#wshs_select_order_page option[value="'+existing_atts.order_by+'"]').prop('selected', true);
            }

            if(typeof existing_atts.order != 'undefined'){
                jQuery('#wshs_select_order_asc_page option[value="'+existing_atts.order+'"]').prop('selected', true);
            }

            if(typeof existing_atts.depth != 'undefined'){
                jQuery('#wshs_select_depth option[value="'+existing_atts.depth+'"]').prop('selected', true);
            }
            if(typeof existing_atts.layout != 'undefined'){
                jQuery('#wshs_select_column_page option[data-title="'+existing_atts.layout+'"]').prop('selected', true);
                jQuery('#wshs_select_column_page').trigger('change');
            }

            if(typeof existing_atts.position != 'undefined'){
                jQuery('#wshs_select_column_position_page option[value="'+existing_atts.position+'"]').prop('selected', true);
            }

            setTimeout(function(){
                if(typeof existing_atts.show_image != 'undefined'){
                    jQuery('input#wshs_display_image_page').prop('checked', true).trigger('change');
                    if(typeof existing_atts.image_width != 'undefined'){
                        jQuery('.wshs_excerpt_limit_page input').val(existing_atts.image_width);    
                    }
                    if(typeof existing_atts.image_width != 'undefined'){
                        jQuery('input[name="wshs_image_width_page"]').val(existing_atts.image_width);    
                    }
                    if(typeof existing_atts.image_height != 'undefined'){
                        jQuery('input[name="wshs_image_height_page"]').val(existing_atts.image_height);    
                    }
                }
                if(typeof existing_atts.content_limit != 'undefined'){
                    jQuery('input#wshs_display_excerpt_page').prop('checked', true).trigger('change');
                    jQuery('.wshs_excerpt_limit_page input').val(existing_atts.content_limit);
                }
                if(typeof existing_atts.show_date != 'undefined'){
                    jQuery('input#wshs_display_date_page').prop('checked', true).trigger('change');
                    jQuery('#wshs_show_date_format_page option[value="'+existing_atts.date_format+'"]').prop('selected', true);
                }
            },100)
            jQuery('#wshs_select_type').trigger('change');
        }

        // For post tab
        if(existing_atts.post_type == 'post'){
            jQuery('#wshs_select_type option[value="'+existing_atts.post_type+'"]').prop('selected', true);

            if(typeof existing_atts.order_by != 'undefined'){
                jQuery('#wshs_select_order option[value="'+existing_atts.order_by+'"]').prop('selected', true);
            }

            if(typeof existing_atts.order != 'undefined'){
                jQuery('#wshs_select_order_asc option[value="'+existing_atts.order+'"]').prop('selected', true);
            }

            if(typeof existing_atts.layout != 'undefined'){
                jQuery('#wshs_select_column_post option[data-title="'+existing_atts.layout+'"]').prop('selected', true);
                jQuery('#wshs_select_column_post').trigger('change');
            }

            if(typeof existing_atts.position != 'undefined'){
                jQuery('#wshs_select_column_position_post option[value="'+existing_atts.position+'"]').prop('selected', true);
            }

            setTimeout(function(){
                if(typeof existing_atts.show_image != 'undefined'){
                    jQuery('input#wshs_display_image').prop('checked', true).trigger('change');
                    if(typeof existing_atts.image_width != 'undefined'){
                        jQuery('.wshs_excerpt_limit input').val(existing_atts.image_width);    
                    }
                    if(typeof existing_atts.image_width != 'undefined'){
                        jQuery('input[name="wshs_image_width"]').val(existing_atts.image_width);    
                    }
                    if(typeof existing_atts.image_height != 'undefined'){
                        jQuery('input[name="wshs_image_height"]').val(existing_atts.image_height);    
                    }
                }
                if(typeof existing_atts.content_limit != 'undefined'){
                    jQuery('input#wshs_display_excerpt').prop('checked', true).trigger('change');
                    jQuery('.wshs_excerpt_limit input').val(existing_atts.content_limit);
                }
                if(typeof existing_atts.show_date != 'undefined'){
                    jQuery('input#wshs_display_date').prop('checked', true).trigger('change');
                    jQuery('#wshs_show_date_format_page option[value="'+existing_atts.date_format+'"]').prop('selected', true);
                }
            },100)
            jQuery('#wshs_select_type').trigger('change');
        }
    }
    jQuery(document).on('sitemaploaded', function(){
        if(typeof existing_atts.child_of != 'undefined'){
            jQuery('#wshs_select_parent option[value="'+existing_atts.child_of+'"]').prop('selected', true).trigger('change');
        }

        if(typeof existing_atts.exclude != 'undefined'){
            let exclude = existing_atts.exclude.split(', ');
            jQuery.each(exclude, function(index, val){
                jQuery('.wshs-post-checkbox[value="'+val+'"]').prop('checked', true).trigger('change');
            })
        
        }
        if(existing_atts.post_type == 'post'){
            if(typeof existing_atts.taxonomy != 'undefined'){
                jQuery('#wshs_taxonomy_list option[value="'+existing_atts.taxonomy+'"]').prop('selected', true).trigger('change');
            }
            setTimeout(function(){
                if(typeof existing_atts.terms != 'undefined'){
                    jQuery('#wshs_taxonomy_list_chield option[value="'+existing_atts.terms+'"]').prop('selected', true).trigger('change');
                }
                existing_atts = {};
            },1000);

        } else {
            existing_atts = {};
        }

        
       
        jQuery('#wshs_select_parent').trigger('change');
    });
});
