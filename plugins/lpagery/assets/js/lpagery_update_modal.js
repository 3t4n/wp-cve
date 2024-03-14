jQuery(function ($) {
    const params = new Proxy(new URLSearchParams(window.location.search), {
        get: (searchParams, prop) => searchParams.get(prop),
    });
    let update_post_id = params.update_post_id;
    $('#lpagery_error-area-update').hide()
    if (update_post_id) {
        $(".check-center").hide();
        $('.fadeout').css('opacity', '')
        var ajaxDataGetPosts = {
            "action": 'lpagery_get_pages_by_template',
            "template_id": update_post_id,
            "_ajax_nonce": lpagery_ajax_object_update_modal.nonce
        };
        jQuery.ajax({
            "method": "get",
            "url": lpagery_ajax_object_modal.ajax_url,
            "data": ajaxDataGetPosts,
            "success": function (response) {
                let parsed_response = JSON.parse(response);
                let header = [
                    {
                        "name": "id",
                        "type": "text",
                        "title": "ID",
                        width: 40
                    },
                    {
                        "name": "process_id",
                        "type": "text",
                        "visible": false
                    },
                    {
                        "name": "title",
                        "title": "Title",
                        "type": "text"
                    },

                    {
                        "name": "permalink",
                        "title": "Permalink",

                        itemTemplate: function (value) {
                            return $("<a  target='_blank'>").attr("href", value).text(value);
                        }
                    },
                    {
                        width: 20,
                        "name": "modified",
                        "title": "Manually Changed",
                        "type": "checkbox"
                    },
                    {
                        "type": "control",
                        width: 20,
                        editButton: false,
                        deleteButtonTooltip: "Ignore Post when Updating",
                    }
                ]
                $("#lpagery_page_update_grid").jsGrid({
                    width: "100%",
                    height: "400px",

                    inserting: false,
                    editing: false,
                    sorting: true,
                    paging: false,
                    selecting: true,
                    confirmDeleting: false,
                    data: parsed_response,
                    fields: header
                });
                $('#lpagery_confirm_modal_update').lpagery_modal({
                    escapeClose: false,
                    clickClose: false,
                    showClose: false
                });
            }
        });

    }

    var canceled = false;
    $('#lpagery_accept_update').on('click', async function () {
        canceled = false;
        $.lpageryToggleRotating($('#lpagery_accept_update'), true);

        var data = $("#lpagery_page_update_grid").jsGrid("option", "data");
        for (let index in data) {
            if (canceled) {
                break;
            }
            let current = data[index];

            if (index > 0 && index % 200 === 0) {
                $('#lpagery_pause').show()
                await new Promise(resolve => setTimeout(resolve, 7000));
            }

            $('#lpagery_pause').hide()
             await createPage(current, index++, data.length)

            if ((($("#lpagery_preview-mode").is(':checked')))) {
                break;
            }
        }
        uploadFinished()
        var ajaxDataPostType = {
            "action": 'lpagery_get_post_type',
            "post_id": update_post_id,
            "_ajax_nonce": lpagery_ajax_object_update_modal.nonce
        };
        jQuery.ajax({
            "method": "get",
            "url": lpagery_ajax_object_update_modal.ajax_url,
            "data": ajaxDataPostType,
            "success": function (response) {
                $('#lpagery_updated_page_link').prop('href', 'edit.php?post_type=' + response + '&lpagery_template=' + update_post_id + '&orderby=date&order=desc&lang=all')
            }
        });


    })

    function createPage(current_data, index, total) {
        if (canceled) {
            return false;
        }

        let id = current_data.id;

        var ajax_data_update = {
            "action": 'lpagery_update_posts',
            "post_id": id,
            "_ajax_nonce": lpagery_ajax_object_update_modal.nonce
        };
        return new Promise((resolve, reject) => {
            jQuery.ajax({
                "method": "post",
                "url": lpagery_ajax_object_modal.ajax_url,
                "data": ajax_data_update,
                "success": function (response) {
                    try {
                        let parsed = JSON.parse(response)
                        if (!parsed.success) {
                            console.error(parsed.exception)
                            onError(parsed.exception);
                            reject();
                        }
                        resolve();
                    } catch (e) {
                        console.error(e)
                        console.error(response)
                        onError(e, response);
                        reject(e);
                        throw e;
                    }
                    let index_to_show = index + 1;

                    $('#lpagery_pagestatus_update_update').html("Updated <b>" + index_to_show + "</b> of <b>" + total + "</b> Pages")
                }
            })
        });
    }

    function uploadFinished() {
        canceled = true;
        $('#lpagery_error-area-update').hide();
        $.lpageryToggleRotating($('#lpagery_accept_update'), false);
        jQuery('.fadeout').fadeTo(700, 0.2);
        $(".check-center").hide();
        $('.check-center').show();
        jQuery('.fadeout').css({pointerEvents: "none"})
    }

    $('#lpagery_cancel_update').on('click', function (e) {
        canceled = true;
        $.lpagery_modal.close();
        location.href = "admin.php?page=lpagery";
    })

    function onError(...errors) {
        $('.lpagery_pagestatus').hide();
        $('#lpagery_error-area-update').show();
        $.lpageryToggleRotating($('#lpagery_accept_update'), false);
        canceled = true;
        let errorsString = errors.map(s => '\n' + s).toString();
        $('#lpagery_error-content-update').html(errorsString)
    }
})