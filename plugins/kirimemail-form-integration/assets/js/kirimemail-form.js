jQuery(document).ready(function () {
    var H = jQuery(window).height() - 80,
        W = jQuery(window).width() - 115;

    jQuery("#show-kesetting").on("click", function (e) {
        jQuery("#kesetting").slideDown(100);
        jQuery(this).addClass("active");
        e.preventDefault();
    });

    jQuery("#close-kesetting").on("click", function () {
        jQuery("#kesetting").slideUp(100);
        jQuery("#show-kesetting").removeClass("active");
    });

    jQuery('body').on('click', 'a#kebutton', function () {
        let item = jQuery(this);
        tb_show(item.attr('data-caption'), item.attr('data-url'));
    });

    jQuery(document).on('change', '[name*="popup"], [name*="bar"]', function () {
        jQuery.ajax({
            type: 'post',
            url: kirimemail_wpform.admin_url + '?page=kirimemail-wordpress-form&save_object=1',
            data: {object: jQuery(this).attr('data-object'), id: jQuery(this).val(), active: jQuery(this).is(':checked')}
        }).always(function (data, textStatus, jqXHR) {
            table.draw();
        });
    });

    var table = jQuery('#ke-table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        lengthChange: false,
        pageLength: 100,
        responsive: true,
        ajax: {
            url: kirimemail_wpform.admin_url + 'admin.php?page=kirimemail-wordpress-form&get_form_listener=1',
            method: 'POST'
        },
        columns: [{
            data: null,
            bSearchable: false,
            bSortable: false,
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
            {
                data: 'name',
                name: 'name',
                bSearchable: false,
                bSortable: false
            },
            {
                data: 'viewed',
                name: 'viewed',
                bSearchable: false,
                bSortable: false
            },
            {
                data: 'submitted',
                name: 'submitted',
                bSearchable: false,
                bSortable: false
            },
            {
                data: null,
                bSearchable: false,
                bSortable: false,
                render: function (data, type, full, meta) {
                    var checked = '';
                    if (full.popup_checked == 1) {
                        checked = 'checked';
                    }
                    var html = jQuery('#template-popup').html()
                        .replace(/%url%/g, full.url)
                        .replace(/%checked%/g, checked);
                    return html;
                }
            },
            {
                data: null,
                bSearchable: false,
                bSortable: false,
                render: function (data, type, full, meta) {
                    var checked = '';
                    if (full.bar_checked == 1) {
                        checked = 'checked';
                    }
                    var html = jQuery('#template-bar').html()
                        .replace(/%url%/g, full.url)
                        .replace(/%checked%/g, checked);
                    return html;
                }
            },
            {
                data: null,
                bSearchable: false,
                bSortable: false,
                render: function (data, type, full, meta) {
                    var html = jQuery('#template-action').html()
                        .replace(/%id%/g, full.id)
                        .replace(/%width%/g, W)
                        .replace(/%height%/g, H);
                    return html;
                }
            },
        ]
    });

});
