jQuery(function ($) {
    let db = {};
    let filter_changed = false;
    let delete_process_id;
    let deletion_started = false;
    let deletion_cancelled = false;
    let ajaxDataUsers = {
        "action": 'lpagery_get_users', "_ajax_nonce": lpagery_ajax_object_history.nonce
    };
    let ajaxDataPosts = {
        "action": 'lpagery_get_template_posts', "_ajax_nonce": lpagery_ajax_object_history.nonce
    };
    $(document).on("click", function (e) {
        if (!$(e.target).closest('.lpagery-menu-container').length && !$(e.target).hasClass('lpagery-hamburger-menu')) {
            $(".lpagery-menu-container").hide();
        }
    });
    $.when($.ajax({
        url: lpagery_ajax_object_history.ajax_url, data: ajaxDataUsers, success: function (users) {
            var parsed = JSON.parse(users);
            parsed.splice(0, 0, {"id": -1, "display_name": ""})
            db.users = parsed
        }
    }), $.ajax({
        url: lpagery_ajax_object_history.ajax_url, data: ajaxDataPosts, success: function (posts) {
            var parsed = JSON.parse(posts);
            parsed.splice(0, 0, {"id": -1, "post_title": ""})
            db.posts = parsed;
        }
    })).then(function () {

        let loaded = false;


        $("#lpagery_history_grid").jsGrid({
            width: "100%",
            height: "auto",
            filtering: true,

            autoload: true,

            selecting: true,

            editing: true,
            pageIndex: 1,
            pageSize: 15,
            pageButtonCount: 3,
            paging: true,
            pagerFormat: "{first} {prev} {pages} {next} {last}    {pageIndex} of {pageCount}",
            pagePrevText: "Prev",
            pageNextText: "Next",
            pageFirstText: "First",
            pageLastText: "Last",

            controller: {

                loadData: function (filter) {
                    var userId = filter.user_id;
                    var postId = filter.post_id;
                    if ((filter.user_id !== -1 || filter.post_id !== -1 || filter.purpose !== '') || filter_changed) {
                        localStorage.setItem('lpagery_history_filter', JSON.stringify(filter));
                    }

                    if (!userId || userId === 0) {
                        userId = -1;
                    }
                    if (!postId || postId === 0) {
                        postId = -1;
                    }
                    let ajaxData = {
                        "action": 'lpagery_search_processes',
                        "purpose": encodeURIComponent(filter.purpose),
                        "user_id": userId,
                        "post_id": postId,
                        "_ajax_nonce": lpagery_ajax_object_history.nonce
                    };
                    return $.ajax({
                        url: lpagery_ajax_object_history.ajax_url, data: ajaxData, dataType: "json"
                    }).then(function (result) {

                        if (!Array.isArray(result)) {
                            return [];
                        }
                        return result.map(element => {
                            return {
                                "user_id": element.user_id,
                                "post_id": element.post_id,
                                "id": element.id,
                                "deleted": element.post.deleted,
                                "google_sheet_sync_enabled": element.google_sheet_sync_enabled,
                                "google_sheet_sync_status": element.google_sheet_sync_status,
                                "google_sheet_url": element.google_sheet_url,
                                "post_count": element.post_count,
                                "purpose": element.display_purpose,
                                "created": element.created,
                                "post_title": element.post.title,
                                "user_name": element.user.name,
                                "post_permalink": element.post.permalink,
                                "post_type": element.post.type,
                            }
                        })
                    })
                }, updateItem: function (item) {
                    let ajaxData = {
                        "action": 'lpagery_upsert_process',
                        "purpose": encodeURIComponent(item.purpose),
                        "id": item.id,
                        "_ajax_nonce": lpagery_ajax_object_history.nonce
                    };
                    $.ajax({
                        url: lpagery_ajax_object_history.ajax_url, data: ajaxData, dataType: "json", method: "post"
                    });
                }
            },
            fields: [{
                name: "id", visible: false
            }, {
                name: "deleted", visible: false
            }, {
                name: "purpose",
                "title": "Purpose",
                type: "text",
                filtercss: "lpagery-history-filter lpagery-history-filter-purpose",

                width: 100,
                editing: true
            },

                {
                    name: "user_id",
                    editing: false,
                    width: 60,
                    title: "User",
                    filtercss: "lpagery-history-filter lpagery-history-filter-user",
                    type: "select",
                    valueType: "number",
                    items: db.users,
                    valueField: "id",
                    textField: "display_name"
                }, {
                    name: "post_id",
                    width: 100,
                    editing: false,
                    type: "select",
                    title: "Template",
                    filtercss: "lpagery-history-filter lpagery-history-filter-post",

                    items: db.posts,
                    valueField: "id",
                    textField: "post_title",
                    valueType: "number",
                    itemTemplate: function (value, item) {
                        if (item.deleted) {
                            return item.post_title;
                        }
                        return $("<a>").attr("href", item.post_permalink).text(item.post_title);
                    }
                }, {
                    name: "post_count", title: "#", width: 10, itemTemplate: function (value, item) {
                        return $("<a>").attr("href", 'edit.php?post_type=' + item.post_type + '&lpagery_process=' + item.id + '&orderby=date&order=desc&lang=all').text(value);

                    }
                }, {name: "created", width: 50, title: "Date"},

                {
                    name: "google_sheet_sync_enabled",
                    width: 20,
                    filtering: false,
                    editing: false,
                    title: "Google Sheet",
                    headerTemplate: function () {
                        let image_url = lpagery_ajax_object_history.plugin_dir + "/../assets/img/google-sheet-icon.png";
                        return $('<img width="20" height="20" src="' + image_url + '" alt="google sheet sync">')
                    },
                    itemTemplate: function (value, item) {
                        if (!item.google_sheet_sync_enabled) {
                            return "";
                        }
                        switch (item.google_sheet_sync_status) {

                            case "PLANNED":
                                return $('<i class="fa-solid fa-clock" title="Planned"></i>');
                            case "CRON_STARTED":
                                return $('<i class="fa-solid fa-hourglass-start" title="Sync Job Started"></i>');
                            case "RUNNING":
                                return $('<i class="fa-solid fa-rotate"  title="Running"></i>');
                            case "FINISHED":
                                return $('<i class="fa-solid fa-check"  title="Finished"></i>');
                            case "ERROR":
                                return $('<i class="fa-solid fa-xmark"  title="Error"></i>');
                            case "PAST_DUE":
                                return $('<i class="fa-solid fa-circle-exclamation"  title="Past Due"></i>');
                        }

                    }
                },

                {
                    type: "control",
                    editButton: false,
                    deleteButton: false,
                    width: 30,
                    itemTemplate: function (value, item) {
                        var $menuContainer = $("<div>").addClass("lpagery-menu-container").attr("tabindex", "0");

                        if (!item.deleted) {
                            // Customize the menu items as needed
                            $menuContainer.append($("<div>").addClass("lpagery-menu-item").html("Edit").on("click", function (e) {
                                e.stopPropagation();
                                $menuContainer.hide();
                                $.lpagery_load_update_process(item.id)
                                $('#lpagery_anchor_dashboard').click();
                            }));

                            $menuContainer.append($("<div>").addClass("lpagery-menu-item").html("Google Sheet Sync Settings").on("click", function (e) {
                                e.stopPropagation()
                                let ajaxData = {
                                    "action": 'lpagery_get_google_sheet_sync_overview',
                                    "process_id": item.id,
                                    "_ajax_nonce": lpagery_ajax_object_root.nonce
                                };
                                jQuery.ajax({
                                    "method": "post",
                                    "url": lpagery_ajax_object_modal.ajax_url,
                                    "data": ajaxData,
                                    "success": function (response) {
                                        var parsed = JSON.parse(response);
                                        let googleSheetData = parsed.google_sheet_data;
                                        $('#lpagery_process_id_modal').val(item.id)
                                        if(googleSheetData) {
                                            $('#lpagery_google_sheet_url_modal').val(decodeURI(googleSheetData.url));
                                            $('#lpagery_google_sheet_status').html(get_pretty_status(parsed.status));
                                            $('#syncAddModal').prop('checked', googleSheetData.add)
                                            $('#syncUpdateModal').prop('checked', googleSheetData.update)
                                            $('#syncDeleteModal').prop('checked', googleSheetData.delete)
                                            if(parsed.last_sync < 0) {
                                                $('#lpagery_google_sheet_last_sync').html("-");
                                            } else {
                                                $('#lpagery_google_sheet_last_sync').html(new Date(parsed.last_sync * 1000).toLocaleString());
                                            }
                                            let next_sync_date = new Date(parsed.next_sync * 1000);
                                            if(next_sync_date <= new Date()) {
                                                $('#lpagery_google_sheet_next_sync').html("now");
                                            } else {
                                                $('#lpagery_google_sheet_next_sync').html(next_sync_date.toLocaleString());
                                            }
                                            if (parsed.error) {
                                                $('#lpagery_cron_error').show();
                                                $('#lpagery_cron_error').val(parsed.error);
                                            } else {
                                                $('#lpagery_cron_error').hide();
                                            }
                                        }
                                        if (parsed.google_sheet_sync_enabled) {
                                            $('#lpagery_google_sheet_sync_details').show()
                                            $('#google_sync_enabled_modal').prop('checked', true)

                                            $('#lpagery_google_sheet_sync_config').removeClass('lpagery-disabled')
                                        } else {
                                            $('#lpagery_google_sheet_sync_details').hide()
                                            $('#lpagery_cron_error').hide();
                                            $('#google_sync_enabled_modal').prop('checked', false)
                                            $('#lpagery_google_sheet_sync_config').addClass('lpagery-disabled')
                                        }
                                        $('#lpagery_process_google_sheet_modal').lpagery_modal({
                                            closeExisting: false, showClose: false
                                        })
                                    }
                                })

                                $menuContainer.hide();
                            }));
                        }
                        if(item.google_sheet_sync_enabled) {

                            $menuContainer.append($("<div>").addClass("lpagery-menu-item lpagery-menu-item-sync").html("Sync Manually").on("click", async function (e) {
                                e.stopPropagation();
                                $(".lpagery-menu-container").hide();
                                $('#lpagery_skeleton_modal').lpagery_modal({
                                    showClose: false
                                });
                                await $.fetchGoogleSheet(item.google_sheet_url, item.id, true);

                            }));
                        }

                        $menuContainer.append($("<div>").addClass("lpagery-menu-item").html("Download Data").on("click", function (e) {
                            e.stopPropagation()
                            var ajaxData = {
                                action: 'lpagery_get_process_details',
                                id: item.id,
                                download: true,
                                "_ajax_nonce": lpagery_ajax_object_root.nonce
                            };
                            jQuery.get(lpagery_ajax_object_history.ajax_url, ajaxData, function (response) {
                                let parsed = JSON.parse(response);
                                let data = parsed.data;
                                let fields = Object.keys(data[0])
                                let csv = data.map(function (row) {

                                    return fields.map(function (fieldName) {
                                        let value = row[fieldName];
                                        return value == null ? '' : value;
                                    }).join(';')
                                })
                                csv.unshift(fields.join(';')) // add header column
                                csv = csv.join('\r\n');
                                let file_name = "lpagery_export_" + parsed.process.raw_purpose + "_" + parsed.process.created + ".csv";
                                file_name = file_name.toLowerCase();
                                file_name = file_name.replaceAll(" ", "_");

                                var blob = new Blob([csv], {type: 'text/csv;charset=utf-8;'});

                                var link = document.createElement("a");
                                if (link.download !== undefined) { // feature detection
                                    // Browsers that support HTML5 download attribute
                                    var url = URL.createObjectURL(blob);
                                    link.setAttribute("href", url);
                                    link.setAttribute("download", file_name);
                                    link.style.visibility = 'hidden';
                                    document.body.appendChild(link);
                                    link.click();
                                    document.body.removeChild(link);
                                }

                            })

                            $menuContainer.hide();
                        }));

                        $menuContainer.append($("<div>").addClass("lpagery-menu-item").html("Delete").on("click", function (e) {
                            e.stopPropagation();
                            $menuContainer.hide();
                            $(".check-center").hide();
                            $('.fadeout').css({pointerEvents: "auto"})
                            $('.fadeout').css('opacity', '')
                            delete_process_id = item.id;
                            deletion_cancelled = false;
                            deletion_started = false;
                            $('#lpagery_pagestatus_delete').html("");
                            $.lpageryToggleRotating($('#lpagery_accept_process_delete'), false)
                            $("#lpagery_delete_pages_check").prop('checked', false);
                            $('#lpagery_process_delete_modal').lpagery_modal({
                                closeExisting: false, showClose: false
                            });
                        }));

                        $menuContainer.append($("<div>").addClass("lpagery-menu-item").html("Copy Shortcode").on("click", function (e) {
                            e.stopPropagation();
                            var shortcode = '[lpagery_urls id="' + item.id + '"]';

                            var tempInput = $('<input>');
                            $('body').append(tempInput);
                            tempInput.val(shortcode).select();
                            document.execCommand('copy');
                            tempInput.remove();
                            Toastify({
                                text: 'Shortcode copied to clipboard: ' + shortcode,
                                duration: 2000,
                                close: true,
                                gravity: "top", // `top` or `bottom`
                                position: "center", // `left`, `center` or `right`
                                style: {
                                    background: "linear-gradient(to right, #4f87b7, #73c4b6)",
                                }
                            }).showToast();
                        }));

                        var $hamburgerIcon = $("<div>").addClass("lpagery-hamburger-menu").html('<i class="fas fa-bars"></i>').on("click", function (e) {
                            e.stopPropagation();
                            positionMenu($menuContainer, e.currentTarget);
                            $(window).scroll(function () {
                                // Assuming $menu is the reference to your menu element
                                positionMenu($menuContainer, e.currentTarget);
                            });
                            $(".lpagery-menu-container").not($menuContainer).hide(); // Hide other menus
                            $menuContainer.toggle();
                        });

                        var $cellContainer = $("<div>").append($hamburgerIcon).append($menuContainer);

                        return $cellContainer;
                    }
                }],
            onDataLoaded: function () {
                if (!loaded) {
                    var filter_string = localStorage.getItem('lpagery_history_filter');
                    if (filter_string) {
                        let filter = JSON.parse(filter_string);
                        if (filter.purpose) {
                            $('.lpagery-history-filter-purpose').find('input').val(filter.purpose)
                        }
                        if (filter.user_id) {
                            $('.lpagery-history-filter-user').find('select').val(filter.user_id)
                        }
                        if (filter.post_id) {
                            $('.lpagery-history-filter-post').find('select').val(filter.post_id)
                        }
                        $("#lpagery_history_grid").jsGrid("loadData");
                    }
                    $(".lpagery-history-filter").on("input", function () {
                        filter_changed = true
                    });
                }
                loaded = true;
            },
        });

    })

    $('#lpagery_cancel_process_delete').on('click', function () {
        deletion_cancelled = true;
        if (deletion_started) {
            $.lpageryToggleRotating($('#lpagery_accept_process_delete'), false)
            $("#lpagery_history_grid").jsGrid("loadData");
        }
        $.lpagery_modal.close();
    })

    function delete_process() {
        var ajaxData = {
            action: 'lpagery_delete_process',
            id: delete_process_id,
            delete_pages: $('#lpagery_delete_pages_check').is(':checked'),
            "_ajax_nonce": lpagery_ajax_object_root.nonce
        };
        jQuery.post(lpagery_ajax_object_history.ajax_url, ajaxData, function (response) {
            $.lpageryToggleRotating($('#lpagery_accept_process_delete'), false)
            $("#lpagery_history_grid").jsGrid("loadData");
        });
    }

    function deletePage(data, index) {
        if (data.length === 0) {
            delete_process()
            $.lpagery_modal.close();
            return;
        }
        let post_id = data[index].id;
        var ajaxData = {
            action: 'lpagery_delete_post',
            id: post_id,
            process_id: delete_process_id,
            "_ajax_nonce": lpagery_ajax_object_root.nonce
        };
        jQuery.post(lpagery_ajax_object_history.ajax_url, ajaxData, function (response) {

            $('#lpagery_pagestatus_delete').html("Deleted Page <b>" + (index + 1) + "</b> of <b>" + data.length + "</b>")
            if (index === (data.length - 1) || data.length === 0) {
                delete_process()
                jQuery('.fadeout').fadeTo(700, 0.2);
                $(".check-center").hide();
                $('.check-center').show();
                jQuery('.fadeout').css({pointerEvents: "none"})
                //$.lpagery_modal.close();
            } else if (!deletion_cancelled) {
                deletePage(data, ++index);
            }
        });

    }

    $('#lpagery_accept_process_delete').on('click', function () {
        $.lpageryToggleRotating($('#lpagery_accept_process_delete'), true)
        let delete_pages = $('#lpagery_delete_pages_check').is(':checked');
        if (!delete_pages) {
            delete_process();
            jQuery('.fadeout').fadeTo(700, 0.2);
            $(".check-center").hide();
            $('.check-center').show();
            jQuery('.fadeout').css({pointerEvents: "none"})

        } else {
            deletion_started = true;
            var ajaxData = {
                action: 'lpagery_get_posts_by_process_id',
                id: delete_process_id,
                "_ajax_nonce": lpagery_ajax_object_root.nonce
            };
            jQuery.get(lpagery_ajax_object_history.ajax_url, ajaxData, function (response) {
                let parsedResponse = JSON.parse(response);
                $('#lpagery_pagestatus_delete').show()
                deletePage(parsedResponse, 0)
            });
        }

    })

    $('#lpagery_close-modal-delete').on('click', function () {
        $.lpagery_modal.close();

    })

    function positionMenu($menu, target) {
        var $target = $(target);
        var targetOffset = $target.offset();
        var windowWidth = $(window).width();
        var scrollTop = $(window).scrollTop(); // Get the scroll position

        // Calculate the left position to keep the menu within the viewport
        var leftPosition = Math.min(targetOffset.left, windowWidth - $menu.outerWidth());

        $menu.css({
            left: leftPosition,
            top: targetOffset.top + $target.outerHeight() - scrollTop - $menu.height() + 30, // Adjust for scroll position
        });
    }

    $('#lpagery_save_google_sheet').on('click', function () {
        $.lpageryToggleRotating($('#lpagery_save_google_sheet'), true)
        var ajax_upsert_process = {
            "action": 'lpagery_upsert_process',

            "id": $('#lpagery_process_id_modal').val(),
            "google_sheet_data": {
                "add": jQuery("#syncAddModal").is(':checked'),
                "update": jQuery("#syncUpdateModal").is(':checked'),
                "delete": jQuery("#syncDeleteModal").is(':checked'),
                "enabled": true,
                "sync_enabled": $('#google_sync_enabled_modal').is(':checked'),
                "url": encodeURI($('#lpagery_google_sheet_url_modal').val())
            },

            "_ajax_nonce": lpagery_ajax_object_modal.nonce
        };
        jQuery.ajax({
            "method": "post",
            "url": lpagery_ajax_object_modal.ajax_url,
            "data": ajax_upsert_process,
            success: function () {
                setTimeout(function () {
                    $.lpageryToggleRotating($('#lpagery_save_google_sheet'), false)
                    $.lpagery_modal.close();
                    $("#lpagery_history_grid").jsGrid("loadData");
                }, 300);
            },
            error: function () {

            }
        })
    })
    $('#google_sync_enabled_modal').on('change', function () {
        if($(this).prop("checked")) {
            $('#lpagery_google_sheet_sync_config').removeClass('lpagery-disabled')
        } else {
            $('#lpagery_google_sheet_sync_config').addClass('lpagery-disabled')
        }
    })

    function get_pretty_status(status) {
        let result = "";
        let split = status.split("_");
        split.forEach(value => {
            value = value.toLowerCase()
            const firstLetter = value.charAt(0)
            const firstLetterCap = firstLetter.toUpperCase()
            const remainingLetters = value.slice(1)
            const capitalizedWord = firstLetterCap + remainingLetters
            result += capitalizedWord + " ";
        })
        return result.trim()
    }

})