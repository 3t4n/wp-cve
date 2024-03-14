
jQuery('#settings_first_section').hide();
jQuery(".elex-gpf-steps-navigator").hide();

function elex_remove_file(file, file_name) {
    var delete_file = confirm('Do you want to delete the feed "' + file_name + '"?');
    if (!delete_file == true) {
        return
    }
    jQuery.ajax({
        type: 'post',
        url: ajaxurl,
        data: {
            _ajax_elex_gpf_manage_feed_nonce: jQuery('#_ajax_elex_gpf_manage_feed_nonce').val(),
            action: 'elex_gpf_manage_feed_remove_file',
            file_to_delete: file

        },
        success: function (response) {
            window.location.reload();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}

function update_file_to_latest(file, file_name) {
    var refresh_file = confirm('Do you want to refresh the feed of the project "' + file_name + '" with latest data?');
    if (!refresh_file == true) {
        return
    }
    jQuery(".elex-gpf-loader").css("display", "block");
    jQuery.ajax({
        type: 'post',
        url: ajaxurl,
        data: {
            _ajax_elex_gpf_manage_feed_nonce: jQuery('#_ajax_elex_gpf_manage_feed_nonce').val(),
            action: 'elex_gpf_manage_feed_refresh_file',
            file_to_refresh: file

        },
        success: function (response) {
            window.location.reload();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}
function elex_gpf_search_feed_fun() {
    var search = jQuery('#elex_gpf_search_feed').val();
    window.location.replace(window.location.href+"&search="+search+"&feed_page=1");
}