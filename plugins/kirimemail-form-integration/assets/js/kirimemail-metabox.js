(function () {
    var H = jQuery(window).height() - 80,
        W = jQuery(window).width() - 115;
    let save_metabox_url = 'admin.php?page=kirimemail-wordpress-form&save_metabox=1';
    let select_url = 'admin.php?page=kirimemail-wordpress-form&get_select_option=1';
    let metabox_saved = jQuery('#ke-metabox-saved');
    let metabox_error = jQuery('#ke-metabox-error');
    metabox_saved.hide();
    metabox_error.hide();

    function show_saved() {
        metabox_saved.show();
        setTimeout(function () {
            metabox_saved.hide();
        }, 2000)
    }

    function show_error() {
        metabox_error.show();
        setTimeout(function () {
            metabox_error.hide();
        }, 2000)
    }

    jQuery('.ke-metabox-select-form').select2({
        ajax: {
            url: select_url
        }
    });
    jQuery('#ke-widget').on('change', function () {
        jQuery('#widget-selected').val(jQuery('#ke-widget').val());
    });
    jQuery('#ke-bar').on('change', function () {
        jQuery('#bar-selected').val(jQuery('#ke-bar').val());
    });
    jQuery('#ke-widget-edit').on('click', function () {
        try {
            let selected_val = JSON.parse(jQuery('#widget-selected').val());
            let edit_url = jQuery('#ke-metabox-edit-url').attr('data-url').replace(/%id%/g, selected_val.id).replace(/%width%/g, W)
                .replace(/%height%/g, H);
            tb_show('Edit Widget Form', edit_url)
        } catch (e) {

        }
    });
    jQuery('#ke-bar-edit').on('click', function () {
        try {
            let selected_val = JSON.parse(jQuery('#bar-selected').val());
            let edit_url = jQuery('#ke-metabox-edit-url').attr('data-url').replace(/%id%/g, selected_val.id).replace(/%width%/g, W)
                .replace(/%height%/g, H);
            tb_show('Edit Bar Form', edit_url)
        } catch (e) {

        }
    });
    jQuery('#ke-save-metabox').on('click', function () {
        jQuery.ajax({
            type: 'post',
            url: save_metabox_url,
            data: {
                post_id: jQuery(this).attr('data-id'),
                widget_selected: jQuery('#widget-selected').val(),
                bar_selected: jQuery('#bar-selected').val()
            }
        }).done(function (result) {
            show_saved();
            console.log(result);
        }).fail(function (result) {
            show_error();
            console.log(result);
        });
    });
})();
