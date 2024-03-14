jQuery(document).ready(function () {
    /* display missing alt list server side datatable */
    fn_iat_missing_alt_list_datatable();
    /* display existing alt list server side datatable */
    fn_iat_existing_alt_list_datatable();
});

/* add alt text */
jQuery(document).on("click", "#alt-text-btn", function (e) {
    e.preventDefault();
    var post_id = jQuery(this).data("post-id");
    var alt_text = jQuery("#alt-text-" + post_id + "").val();
    var data = {
        action: "iat_add_alt_txt_action",
        post_id: post_id,
        alt_text: alt_text,
    };
    jQuery.ajax({
        type: "POST",
        url: iat_obj.ajaxurl,
        data: data,
        beforeSend: function () {
            jQuery(".copy-name-loader-add-" + post_id + "").css("display", "inline-block");
        },
        success: function (res) {
            var res = JSON.parse(res);
            if (res.flg == 0) {
                jQuery(".copy-name-loader-add-" + post_id + "").css("display", "none");
                alert(res.message);
            } else if (res.flg == 1) {
                jQuery("#" + post_id + "").css("display", "none");
                jQuery(".alt-msg").css("display", "block");
                jQuery(".copy-name-loader-add-" + post_id + "").css("display", "none");
                jQuery("#print-alt-text-missing-" + post_id + "").text(alt_text);
                jQuery("#" + post_id + "").css("display", "none");
                jQuery('#alt-text-' + post_id + '').val('');
                var missingDatatableTable = jQuery("#ex-list-table").DataTable();
                missingDatatableTable.ajax.reload();
                fn_iat_missing_alt_list_datatable();
                jQuery('#alt-text-' + post_id + '').hide();
                jQuery('#alt-text-' + post_id + '').next('button').hide();
            }
            if(res.total == 0){
                jQuery('#copy-name-tp-alt-txt-btn').toggle();
            }
        },
    });
});

/* Copy name to alt text */
jQuery(document).on("click", ".copy-alt-text p", function (e) {
    e.preventDefault();
    var post_id = jQuery(this).data("post-id");
    var name_to_alt = jQuery("#name-to-alt-" + post_id + "").text();
    var data = {
        action: "iat_copy_name_to_alt_txt_action",
        post_id: post_id,
        name_to_alt: name_to_alt,
    };
    jQuery.ajax({
        type: "POST",
        url: iat_obj.ajaxurl,
        data: data,
        beforeSend: function () {
            jQuery(".copy-copy-name-loader-" + post_id + "").css("display", "inline-block");
        },
        success: function (res) {
            var res = JSON.parse(res);
            if (res.flg == 0) {
                alert(res.message);
                jQuery(".copy-copy-name-loader-" + post_id + "").css("display", "none");
            } else if (res.flg == 1) {
                jQuery(".copy-copy-name-loader-" + post_id + "").css("display", "none");
                jQuery("#display-copy-msg-" + post_id + "").css("display", "block");
                jQuery("#display-copy-msg-" + post_id + " b").text(name_to_alt);
                jQuery(".add-alt-div-" + post_id + "").css("display", "none");
                fn_iat_missing_alt_list_datatable();
                fn_iat_existing_alt_list_datatable();
                if(res.total == 0){
                    jQuery('#copy-name-tp-alt-txt-btn').toggle();
                }
            }
            
        },
    });
});

/* Copy all name to alt */
jQuery(document).on("click", "#copy-name-tp-alt-txt-btn", function (e) {
    e.preventDefault();
    if (confirm("Are you sure want to copy all missing media alt text with post media name?")) {
        var data = jQuery("#all-name-copy-text-to-alt-form").serialize();
        fn_iat_copy_all_name_to_alt_txt(data);
    }
});

function fn_iat_copy_all_name_to_alt_txt(data) {
    jQuery.ajax({
        type: "POST",
        url: iat_obj.ajaxurl,
        data: data,
        beforeSend: function () {
            jQuery(".copy-name-loader").css("display", "inline-block");
        },
        success: function (res) {
            var res = JSON.parse(res);
            var ajax_call = res.ajax_call;
            var total_ajax_call = res.total_ajax_call;
            if (res.flg == 0) {
                alert(res.message);
                location.reload();
            } else if (res.flg == 1) {
                var ajax_call = res.ajax_call;
                var total_ajax_call = res.total_ajax_call;
                if (total_ajax_call == ajax_call) {
                    jQuery(".copy-name-loader").css("display", "none");
                    fn_iat_missing_alt_list_datatable();
                    fn_iat_existing_alt_list_datatable();
                } else {
                    ajax_call++;
                    jQuery("#ajax_call").val(ajax_call);
                    var data = jQuery("#all-name-copy-text-to-alt-form").serialize();
                    fn_iat_copy_all_name_to_alt_txt(data);
                }
            }
            if(res.total == 0){
                jQuery('#copy-name-tp-alt-txt-btn').toggle();                
            }
        },
    });
}

function fn_iat_missing_alt_list_datatable() {
    var datatable = jQuery("#list-table").DataTable({
        bDestroy: true,
        bJQueryUI: true,
        paging: true,
        oLanguage: {
            sEmptyTable: "Great, All your images have alt text, Any images without alt text will appear here."
        },
        ajax: {
            type: "POST",
            url: iat_obj.ajaxurl,
            data: {
                action: "iat_get_missing_alt_media_list",
            },
        },
        columns: [
            { data: "post_image" },
            { data: "post_title" },
            { data: "post_url" },
            { data: "post_date" },
            { data: "post_id" },
            { data: "post_id" },
        ],
        columnDefs: [
            {
                targets: 0,
                width: '5%',
                render: function (data, type, row, meta) {
                    return (
                        '<a class="wat-tb-image" href="' +
                        data +
                        '" target="_blank"><img src="' +
                        data +
                        '" width="80" height="80"/></a>'
                    );
                },
            },
            {
                targets: 1,
                width: '25%',
                render: function (data, type, row, meta) {
                    var html = "";
                    html += '<span id="name-to-alt-' + row.post_id + '">' + data + "</span>";
                    html += '<span class="copy-alt-text">';
                    html += '<p class="mt-1" data-post-id="' + row.post_id + '" id="' + row.post_id + '"><i class="loader-1 copy-copy-name-loader-' + row.post_id + '" style="display:none;"></i>Copy name to Alt Text</p>';
                    html += '<span id="display-copy-msg-' + row.post_id + '" style="display:none">Alt text: <b style="color:green;font-weight:600;"></b></span>';
                    html += "<span>";
                    return html;
                },
            },
            {
                targets: 2,
                width: '25%',
                render: function (data, type, row, meta) {
                    var html = "";
                    html += '<span class="copy-url-span">';
                    html += '<p id="copy-url-' + row.post_id + '" data-post-id="' + row.post_id + '" data-url="' + data + '">' + data + "</p>";
                    html += "</span>";
                    return html;
                },
            },
            {
                targets: 3,
                width: '15%',
            },
            {
                targets: 4,
                width: '30%',
                render: function (data, type, row, meta) {
                    var html = "";
                    html += '<div class="add-alt-div-' + row.post_id + '">';
                    html += '<p class="alt-msg" style="display:none">Alt text: <b id="print-alt-text-missing-' + row.post_id + '" style="color:green"></b></p>';
                    html += '<div class="media-add-alt">';
                    html += '<input type="text" name="alt-text-' + row.post_id + '" id="alt-text-' + row.post_id + '" class="form-control" placeholder="Enter alt text" />';
                    html += '<button type="submit" name="alt-text-btn" id="alt-text-btn" class="btn btn-secondary" data-post-id="' + row.post_id + '"><i class="loader copy-name-loader-add-' + row.post_id + '" style="display:none;"></i>&nbsp;Add</button>';
                    html += "</div>";
                    html += "</div>";
                    return html;
                },
            },
            {
                targets: 5,
                width: '5%',
                render: function (data, type, row, meta) {
                    var html = "";
                    html += '<a href="' + iat_obj.admin_url + "upload.php?item=" + data + '" target="_blank"><span class="dashicons dashicons-edit-page icon-edit"></span></a>';
                    return html;
                },
            },
        ],
    });        
}

function fn_iat_existing_alt_list_datatable() {
    var ex_datatable = jQuery("#ex-list-table").DataTable({
        bDestroy: true,
        bJQueryUI: true,
        paging: true,
        ajax: {
            type: "POST",
            url: iat_obj.ajaxurl,
            data: {
                action: "iat_get_existing_alt_media_list",
            },
        },
        columns: [
            { data: "post_image" },
            { data: "post_title" },
            { data: "post_url" },
            { data: "post_date" },
            { data: "post_id" },
            { data: "post_id" },
        ],
        columnDefs: [
            {
                targets: 0,
                width: '5%',
                render: function (data, type, row, meta) {
                    return (
                        '<a class="wat-tb-image" href="' + data + '" target="_blank"><img src="' + data + '" width="80" height="80" /></a>'
                    );
                },
            },
            {
                targets: 1,
                width: '15%',
                render: function (data, type, row, meta) {
                    var html = "";
                    html += "<span>" + data + "</span>";
                    html += '<input type="hidden" name="name-to-alt" id="name-to-alt" value="' + row.post_title + '" />';
                    return html;
                },
            },
            {
                targets: 3,
                width: '15%',
            },
            {
                targets: 2,
                width: '15%',
                render: function (data, type, row, meta) {
                    var html = "";
                    html += '<span class="copy-url-span">';
                    html += '<p id="copy-url-' + row.post_id + '" data-post-id="' + row.post_id + '" data-url="' + data + '">' + data + "</p>";
                    html += "</span>";
                    return html;
                },
            },
            {
                targets: 4,
                width: '35%',
                render: function (data, type, row, meta) {
                    var html = "";
                    html += '<p>Alt text: <b id="print-alt-text">' + row.alt_text + "</b></p>";
                    html += '<p class="update-alt-text-' + row.post_id + '" style="display:none">Updated text: <b style="color:green;font-weight:600;"></b></p>';
                    html += '<div class="media-add-alt">';
                    html += '<input type="text" name="ex-alt-text-' + row.post_id + '" id="ex-alt-text-' + row.post_id + '" class="form-control" placeholder="Enter alt text" />';
                    html += '<button type="submit" name="ex-alt-text-btn" id="ex-alt-text-btn" class="btn btn-secondary" data-post-id="' + row.post_id + '"><i class="loader copy-name-loader-update-' + row.post_id + '" style="display:none;"></i>&nbsp;Update</button>';
                    html += '</div>';
                    return html;
                },
            },
            {
                targets: 5,
                width: '5%',
                render: function (data, type, row, meta) {
                    var html = "";
                    html += '<a href="' + iat_obj.admin_url + "upload.php?item=" + data + '" target="_blank"><span class="dashicons dashicons-edit-page icon-edit"></span></a>';
                    return html;
                },
            },
        ],
    });
}

/* update alt text in existind list */
jQuery(document).on("click", "#ex-alt-text-btn", function (e) {
    e.preventDefault();
    var post_id = jQuery(this).data("post-id");
    var ex_alt_text = jQuery("#ex-alt-text-" + post_id + "").val();
    var data = {
        action: "iat_update_alt_txt_action",
        post_id: post_id,
        ex_alt_text: ex_alt_text,
    };
    jQuery.ajax({
        type: "POST",
        url: iat_obj.ajaxurl,
        data: data,
        beforeSend: function () {
            jQuery(".copy-name-loader-update-" + post_id + "").css("display", "inline-block");
        },
        success: function (res) {
            var res = JSON.parse(res);
            if (res.flg == 0) {
                jQuery(".copy-name-loader-update-" + post_id + "").css("display", "none");
                alert(res.message);
            } else if (res.flg == 1) {
                jQuery(".copy-name-loader-update-" + post_id + "").css("display", "none");
                jQuery(".update-alt-text-" + post_id + "").css("display", "block");
                jQuery(".update-alt-text-" + post_id + " b").text(ex_alt_text);
                jQuery("#ex-alt-text-" + post_id + "").val('');
            }
        },
    });
});

/* copy url */
jQuery(document).on("click", ".copy-url-span p", function (e) {
    e.preventDefault();
    var post_id = jQuery(this).data("post-id");
    var url = jQuery(this).data("url");
    var copied = fn_iat_copy_text(url);
    if (copied) {
        var html = '<p style="color:green;">Copied&nbsp<span class="dashicons dashicons-saved"></span></p>';
        jQuery("#copy-url-" + post_id + "").html(html);
        setTimeout(function () {
            jQuery("#copy-url-" + post_id + "").html(url);
        }, 1000);
    }
});

/* function about to copy text. */
function fn_iat_copy_text(text) {
    var copyText = text.trim();
    let input = document.createElement("input");
    input.setAttribute("type", "text");
    input.value = copyText;
    document.body.appendChild(input);
    input.select();
    document.execCommand("copy");
    return document.body.removeChild(input);
}