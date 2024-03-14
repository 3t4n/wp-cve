jQuery(document).ready(function ($) {
    // Catch event
    $('.wpms-cat-meta-title').on('change', function (e) {
        wpmsUpdateCatMetaTitle(this.id);
    });
    $('.wpms-cat-meta-desc').on('change', function (e) {
        wpmsUpdateCatMetaDesc(this.id);
    });
    $('.wpms-cat-meta-title').on('keyup', function (e) {
        wpmsUpdateTitleLen(this.id);
    });
    // Title counter
    $('.wpms-cat-meta-title').each(function () {
        wpmsUpdateTitleLen(this.id);
    });
    $('.wpms-cat-meta-title').on('keyup', function (e) {
        wpmsUpdateTitleLen(this.id);
    });
    // Description counter
    $('.wpms-cat-meta-desc').each(function () {
        wpmsUpdateDescLen(this.id);
    });
    $('.wpms-cat-meta-desc').on('keyup', function (e) {
        wpmsUpdateDescLen(this.id);
    });

    function wpmsUpdateCatMetaTitle(elementID) {
        const termID = elementID.replace('wpms-cat-meta-title-', '');
        const metaTitle = metaseo_clean($('#' + elementID).val()).trim();
        const dataType = 'wpms-cat-meta-title';
        updateCategoryContent(termID, dataType, metaTitle);
    }
    function wpmsUpdateCatMetaDesc(elementID) {
        const termID = elementID.replace('wpms-cat-meta-desc-', '');
        const metaDesc = metaseo_clean($('#' + elementID).val()).trim();
        const dataType = 'wpms-cat-meta-desc';
        updateCategoryContent(termID, dataType, metaDesc);
    }

    function wpmsUpdateTitleLen(elementID) {
        var title = (metaseo_clean($('#' + elementID).val())).trim();
        const termID = elementID.replace('wpms-cat-meta-title-', '');
        const counterID = 'wpms-cat-title-len' + termID;
        $('#' + counterID).text(title_max_len - title.length);
        if (title.length >= title_max_len) {
            $('#' + counterID).removeClass('word-74B6FC').addClass('word-exceed');//#FEFB04
        } else if (title.length <= 50) {
            $('#' + counterID).removeClass('word-exceed').addClass('word-74B6FC');//#74B6FC
        } else {
            $('#' + counterID).removeClass('word-exceed word-74B6FC');
        }
    }

    function wpmsUpdateDescLen(elementID) {
        var desc = metaseo_clean($('#' + elementID).val()).trim();
        const termID = elementID.replace('wpms-cat-meta-desc-', '');
        const counterID = 'wpms-cat-desc-len' + termID;
        $('#' + counterID).text(desc_max_len - desc.length);
        if (desc.length >= desc_max_len) {
            $('#' + counterID).removeClass('word-74B6FC').addClass('word-exceed');//#FEFB04
        } else if (desc.length <= 120) {
            $('#' + counterID).removeClass('word-exceed').addClass('word-74B6FC');//#74B6FC
        } else {
            $('#' + counterID).removeClass('word-exceed word-74B6FC');
        }
    }

    // update category meta content, the main function to save data
    function updateCategoryContent(termID, dataType, data) {
        const postData = {
            'action': 'wpms',
            'task': 'updateCategoryContent',
            'termID': termID,
            'dataType': dataType,
            'data': data,
            'wpms_nonce': wpms_localize.wpms_nonce
        }
        // call ajax
        jQuery.ajax({
            url: wpms_localize.ajax_url,
            type: 'post',
            data: postData,
            dataType: 'json',
            beforeSend: function () {
                $('#wpms-cat-imgloader-' + termID).show();
            },
            success: function (response) {
                $('#wpms-cat-imgloader-' + termID).hide();
               if (response && response.updated) {
                   $('#wpms-cat-return-msg-' + termID).text(response.msg);
                   $('#wpms-cat-return-msg-' + termID).css('visibility', 'visible');
                   setTimeout(() => {
                       $('#wpms-cat-return-msg-' + termID).css('visibility', 'hidden');
                   }, 1500);
                } else {
                   alert(response.msg);
               }
            },
        });
    }

    // Bulk copy action
    $('.btn_do_cat_copy').on('click', function(e) {
        const $this = $(this);
        var sl_bulk = $('.mbulk_copy:checked').val();
        if (typeof sl_bulk === "undefined" || $('.wpms-bulk-action:checked').length === 0) { // no select
            return;
        }
        var msCatSelected = [];
        var action = $this.data('action');
        if (typeof action !== 'undefined' && action === 'bulk_cat_copy') {
            if (sl_bulk === 'only-selection') {
                $(".wpms_cat_cb").each(function () {
                    if ($(this).is(':checked')) {
                        msCatSelected.push($(this).val()); // push term id into array
                    }
                });
            }
            $('.wpms-bulk-action:checked').each(function (i, v) {
                let action_name = $(v).val();
                wpmsAjaxDoCopy(action_name, sl_bulk, msCatSelected);
            });
        }
    });

    function wpmsAjaxDoCopy(action_name, sl_bulk, msCatSelected) {
        const postData = {
            'action': 'wpms',
            'task': 'wpmsBulkCatCopy',
            'catData': {
                'action_name': action_name,
                'sl_bulk': sl_bulk,
                'msCatSelected': msCatSelected
            },
            'wpms_nonce': wpms_localize.wpms_nonce
        }

        // send ajax
        $.ajax({
            url: wpms_localize.ajax_url,
            type: 'post',
            data: postData,
            dataType: 'json',
            beforeSend: function () {
                $('.wpms-spinner-cat-copy').css('visibility', 'visible');
            },
            success: function (response) {
                if (response.updated) {
                    $('.wpms-spinner-cat-copy').css('visibility', 'hidden');
                    $('.bulk-msg').fadeIn(100).delay(1000);
                } else {
                    alert('Something went wrong');
                }
            }
        });
    }
});