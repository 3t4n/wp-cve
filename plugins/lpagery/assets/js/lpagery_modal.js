jQuery(function ($) {
    var canceled = false;
    var finished = false;
    let created = 0;
    let updated = 0;
    let ignored = 0;
    let ignored_data_not_changed = 0;
    let update_mode = false;
    let process_id;

    var last_index = null;
    var post_update_cache_container = new Map();

    var image_data_cache_container = new Map();
    var image_data_header_cache_container = new Map();
    var parent_post_cache_container = new Map();
    var author_cache_container = new Map();
    var categories_cache_container = new Map();
    var tags_cache_container = new Map();
    let processed_slugs = [];
    let sync_manually = false;
    var ram_protection_ignored = false;


    const retry_values = new Map();

    retry_values.set(1, 500);
    retry_values.set(2, 500);
    retry_values.set(3, 500);
    retry_values.set(4, 1000);
    retry_values.set(5, 1000);
    retry_values.set(6, 2000);
    retry_values.set(7, 5000);
    retry_values.set(8, 7000);
    retry_values.set(9, 10000);
    retry_values.set(10, 15000);

    $.clearModalData = function () {
        canceled = false;
        finished = false;
        created = 0;
        updated = 0;
        ignored = 0;
        update_mode = false;
        process_id = null;

        last_index = null;
        post_update_cache_container = new Map();

        image_data_cache_container = new Map();
        parent_post_cache_container = new Map();
        author_cache_container = new Map();
        categories_cache_container = new Map();
        tags_cache_container = new Map();
        processed_slugs = [];
        sync_manually = false;
        ram_protection_ignored = false;
    }

    $.validator.setDefaults({
        ignore: []
    });


    $('#lpagery_columnnamesubmit').on('click', function () {
        var fields = $("#lpagery_page_generation_grid").jsGrid("option", "fields");
        var columnTitle = $('#lpagery_columnname').val();
        fields.splice(fields.length - 1, 0, {"type": "text", "name": columnTitle})
        $("#lpagery_page_generation_grid").jsGrid("option", "fields", fields)
    })

    async function start_creation() {
        $.lpageryToggleRotating($('#lpagery_accept'), true);
        if ($.lpagery_save_pending_grid_data) {
            $.lpagery_save_pending_grid_data($("#lpagery_page_generation_grid"))
        }
        $('#lpagery_pagestatus').show();
        $('#lpagery_error-area').hide();
        $('#lpagery_pause_error').hide()
        $('#lpagery_pagestatus_ignored').hide()
        $('#lpagery_pagestatus_ignored_not_changed').hide()
        $('#lpagery_ignore_ram_protection').hide()
        $('#lpagery_page_generation_grid').css({pointerEvents: "none"})
        $('#lpagery_preview-mode').css({pointerEvents: "none"})
        created = 0;
        updated = 0;
        ignored = 0;
        ignored_data_not_changed = 0;
        if (!sync_manually) {
            let response_data = await $.upsert_lpagery_process();
            process_id = response_data.process_id
        }


        canceled = false;

        var data = $("#lpagery_page_generation_grid").jsGrid("option", "data");

        $('#lpagery_pagestatus_create').html("Created: <b>0</b>")
        $('#lpagery_pagestatus_update').html("Updated: <b>0</b>")

        let length = data.length;


        for (let index in data) {
            if (canceled) {
                break;
            }

            let element = data[index];
            if (index > 0 && index % 200 === 0) {
                $('#lpagery_pause').show()
                await new Promise(resolve => setTimeout(resolve, 7000));
            }

            $('#lpagery_pause').hide()
            let result = await createPage(element, index, process_id, null, length);
            if ((($("#lpagery_preview-mode").is(':checked')))) {
                break;
            }

            await wait_for_ram_if_needed(result.used_memory, false)
        }

        var ajaxDataPostType = {
            "action": 'lpagery_get_post_type',
            "post_id": $('#lpagery_template_path').val(),
            "process_id": process_id,
            "_ajax_nonce": lpagery_ajax_object_modal.nonce
        };
        jQuery.ajax({
            "method": "get",
            "url": lpagery_ajax_object_modal.ajax_url,
            "data": ajaxDataPostType,
            "success": function (response) {
                let parsed = JSON.parse(response);
                let first_creation_date = new Date(parsed.first_process_date);
                var difference = new Date() - first_creation_date;
                var hoursDifference = difference / (1000 * 60 * 60);

                finished = true;
                let url = 'edit.php?post_type=' + parsed.type + '&lpagery_process=' + process_id + '&orderby=modified&order=desc&lang=all'
                $('#lpagery_pagestatus').hide()
                $('#lpagery_error-area').hide();
                $.lpageryToggleRotating($('#lpagery_accept'), false);
                $('.lpagery_fadeout').fadeTo(700, 0.2);
                if (
                    lpagery_ajax_object_modal.is_free_plan &&
                    parsed.process_count >= 3 &&
                    parsed.first_process_date != null &&
                    !localStorage.getItem('lpagery_free_discount_shown') &&
                    hoursDifference >= 1) {
                    $('#lpagery_free_discount_dialog').css('pointer-events', 'none');
                    $('#lpagery_free_discount_dialog').fadeIn(1000, function () {
                        $(this).css('pointer-events', 'auto');
                    })
                    $('#lpagery_created_page_link_discount').prop('href', url)
                    localStorage.setItem('lpagery_free_discount_shown', 'true')
                } else {
                    $(".check-center").hide();
                    $('.check-center').show();
                    $('.lpagery_fadeout').css({pointerEvents: "none"})
                    $('#lpagery_created_page_link').prop('href', url)
                }

            }
        });


    }

    function getMode() {
        let mode = jQuery(".lpagery-create-update-tabs").find(".active")[0].id === "lpagery_anchor_update" ? "update" : "create";
        return mode;
    }

    $.upsert_lpagery_process = async function () {
        return new Promise((resolve, reject) => {
            if (getMode() === "update") {
                process_id = $('#lpagery_dashboard_process_select').val()
            }
            var ajax_init_creation = {
                "action": 'lpagery_upsert_process',
                "purpose": $('#lpagery_process_purpose').val(),
                "post_id": $('#lpagery_template_path').val(),
                "id": process_id,
                "data": {
                    "categories": $('#lpagery_categories').val(),
                    "tags": $('#lpagery_tags').val(),
                    "parent_path": $('#lpagery_parent_path').val(),
                    "slug": $('#lpagery_slug').val(),
                    "status": $('#lpagery_post_status').val(),
                },
                "google_sheet_data": {
                    "enabled": $("input[name$='modeRadio']:checked").val() === "googlesheet",
                    "sync_enabled": $('#lpagery_google_sync_enabled').val() === "sync",
                    "add": jQuery("#syncAdd").is(':checked'),
                    "update": jQuery("#syncUpdate").is(':checked'),
                    "delete": jQuery("#syncDelete").is(':checked'),
                    "url": encodeURI($('#lpagery_google_sheet_url').val())
                },

                "_ajax_nonce": lpagery_ajax_object_modal.nonce
            };
            jQuery.ajax({
                "method": "post",
                "url": lpagery_ajax_object_modal.ajax_url,
                "data": ajax_init_creation,
                success: function (response) {
                    let parse = JSON.parse(response);
                    if (!parse.success) {
                        onError(parse.exception);
                        return reject();
                    }
                    return resolve(parse)
                },
                error: function (response) {
                    console.error(response)
                    console.error(response.responseText)
                    onError("Status : " + response.status, response.responseText);
                    reject();
                }
            })
        })
    }

    $('#lpagery_accept').on('click', async function () {
        if (!await checkNotPassed()) {
            void start_creation();
        }
    })

    async function checkNotPassed() {
        $.lpageryToggleRotating($('#lpagery_accept'), true);
        const grid = $("#lpagery_page_generation_grid");
        const fields = grid.jsGrid("option", "fields");
        const slugInput = $('#lpagery_slug');
        const data = grid.jsGrid("option", "data");
        let duplicatedSlugs = []
        if (data.length <= 500) {
            duplicatedSlugs = await lpagery_get_duplicated_slugs(slugInput.val(), fields, data);
        }

        let confirmationMessages = [];

        const addConfirmationMessage = (value, slugs) => {
            if (!slugs) return;
            const maxSlugsToShow = 10;
            const slugsToShow = slugs.slice(0, maxSlugsToShow);
            const remainingCount = slugs.length - slugsToShow.length;
            const slugsHTML = slugsToShow.map(slug => `<li>${slug}</li>`).join('');
            const moreHTML = remainingCount > 0 ? `<li>...and ${remainingCount} more</li>` : '';

            value += `<ul>${slugsHTML}${moreHTML}</ul>`;
            confirmationMessages.push(value);
        };


        if (duplicatedSlugs.duplicates && duplicatedSlugs.duplicates.length > 0) {
            addConfirmationMessage("<strong>We found identical slugs in the current file. Make sure each slug is different. If there are duplicates, they won't be considered.</strong>", duplicatedSlugs.duplicates);
        }

        if (duplicatedSlugs.existing_slugs && duplicatedSlugs.existing_slugs.length > 0) {
            const slugs = duplicatedSlugs.existing_slugs.map(slug => slug.post_name);
            addConfirmationMessage("<strong>Detected duplicate slugs across the WordPress instance. Continuing will prompt WordPress to append numbers to the URLs for uniqueness. We recommend either removing the existing posts or modifying the slugs of the pages to be created.</strong>", slugs);
        }

        const notFoundImages = grid.find("img.lpagery_img_not_found").length;
        if (notFoundImages > 0) {
            addConfirmationMessage(notFoundImages + " images could not be found.", []);
        }

        function getFilenamesWithMissing(mapObj) {
            let filenames = new Set();

            // Iterate over each key-value pair in the Map
            for (const [filename, obj] of mapObj.entries()) {
                // Check if the object has lpagery_filename_missing property set to true
                if (obj && obj.lpagery_filename_missing === true) {
                    filenames.add(obj.template_file_name)
                }
            }
            return filenames;
        }

        var missingFilenames = getFilenamesWithMissing(image_data_cache_container);
        if (missingFilenames.size > 0) {
            addConfirmationMessage("The following images need to have an LPagery Download Filename with placeholders configured. Otherwise, they won't be downloaded. Please go ahead to the Media Library and add the names for the files to be downloaded", Array.from(missingFilenames));
        }

        if (confirmationMessages.length > 0) {
            let allMessages = confirmationMessages.join("<br>");
            allMessages = allMessages + "<strong>Do you want to proceed?</strong>"
            $('#lpagery_creation_info').html(allMessages);

            $('#lpagery_creation_info_modal').lpagery_modal({
                closeExisting: false, showClose: false
            });
            $.lpageryToggleRotating($('#lpagery_accept'), false);
            return true;
        }

        return false;
    }

    $('#lpagery_accept_creation_info').on('click', function () {
        $.lpagery_modal.close();
        void start_creation();
    })

    $('#lpagery_cancel_creation_info').on('click', function () {
        $.lpagery_modal.close();
    })

    function parseStringToBoolean(str) {
        if (typeof str === 'boolean') {
            return str;
        }

        const lowerCaseStr = str.toLowerCase();
        return lowerCaseStr === 'true' || lowerCaseStr === '1';

    }

    function prepare_data_element(element, keys_to_remove_add) {
        let filtered_element = {};
        for (const [key, value] of Object.entries(element)) {
            let filtered_key = String(key).replaceAll("\u2024", ".")
            filtered_key = filtered_key.replace(/(\r\n|\n|\r)/gm, "");
            let filtered_value = String(value).replace(/(\r\n|\n|\r)/gm, "");
            filtered_value = encodeURI(filtered_value);

            filtered_element[filtered_key] = filtered_value;
        }
        let keys_to_remove = ["", "undefined", "lpagery_existing_post"];
        if (keys_to_remove_add) {
            keys_to_remove = keys_to_remove.concat(keys_to_remove_add)
        }
        Object.keys(filtered_element).forEach(key => {
            if (keys_to_remove.includes(key)) {
                delete filtered_element[key];
            }
        })
        return (filtered_element);

    }

    function prepare_grid_data(fields, element, ...keys_to_remove_add) {
        fields.forEach(field => {
            if (!element[field.name]) {
                element[field.name] = ""
            }
        })

        return prepare_data_element(element, keys_to_remove_add);
    }


    function prepare_grid_data_trim(fields, element, ...keys_to_remove_add) {

        let prepareDataElement = prepare_data_element(element, keys_to_remove_add);
        for (const [key, value] of Object.entries(prepareDataElement)) {
            if ((typeof value === 'string' || value instanceof String) && value.length > 500) {
                prepareDataElement[key] = value.substring(0, 100)
            } else {
                prepareDataElement[key] = value;
            }

        }
        return prepareDataElement;
    }

    function prepare_element_trim(element) {

        let prepareDataElement = prepare_data_element(element);
        for (const [key, value] of Object.entries(prepareDataElement)) {
            if ((typeof value === 'string' || value instanceof String) && value.length > 500) {
                prepareDataElement[key] = value.substring(0, 100)
            } else {
                prepareDataElement[key] = value;
            }

        }
        return prepareDataElement;
    }

    function createPage(element, index, process_id, retry_count, total_count) {
        if (canceled) {
            return Promise.reject("Process canceled");
        }

        var fields = $("#lpagery_page_generation_grid").jsGrid("option", "fields");

        let prepared_data = prepare_grid_data(fields, element, "lpagery_row_id");

        let jsonString = JSON.stringify(prepared_data);

        var ajaxData = {
            "action": 'lpagery_create_posts',
            "data": jsonString,
            "categories": $('#lpagery_categories').val(),
            "tags": $('#lpagery_tags').val(),
            "templatePath": $('#lpagery_template_path').val(),
            "parent_path": $('#lpagery_parent_path').val(),
            "slug": $('#lpagery_slug').val(),
            "status": $('#lpagery_post_status').val(),
            "publish_timestamp": $('#lpagery_publish_date').val(),
            "process_id": process_id,
            "is_last_page": index >= total_count - 1,
            "sync_manually": sync_manually,
            "processed_slugs": processed_slugs,
            "_ajax_nonce": lpagery_ajax_object_modal.nonce
        };

        return new Promise((resolve, reject) => {
            jQuery.ajax({
                "method": "post",
                "url": lpagery_ajax_object_modal.ajax_url,
                "data": ajaxData,
                "error": function (response, textStatus) {
                    if (!retry_count) {
                        retry_count = 1
                    }

                    if (retry_values.get(retry_count)) {
                        let retry_millis = retry_values.get(retry_count)
                        if (retry_count >= 5) {
                            $('#lpagery_pause_error').html("Page creation failed with status code " + response.status + ". Retry attempt " + retry_count + "/" + retry_values.size)
                            $('#lpagery_pause_error').show()
                        }
                        setTimeout(async function () {
                            retry_count++
                            // Retry with incremented count
                            await createPage(element, index, process_id, retry_count, total_count)
                                .then(resolve) // Resolve with the result of retry
                                .catch(reject); // Propagate error if retry fails
                        }, retry_millis)

                    } else {
                        console.error(response)
                        console.error(response.responseText)
                        onError("Status : " + textStatus + " " + response.status, response.responseText);
                        reject("Retry limit exceeded"); // Reject if retry limit exceeded
                    }

                },
                "success": function (response) {
                    let parsed;
                    $('#lpagery_pause_error').hide()
                    try {
                        try {
                            parsed = JSON.parse(response)
                        } catch (e) {
                            const startIndex = response.indexOf('{');
                            const endIndex = response.lastIndexOf('}');

                            if (startIndex === -1 || endIndex === -1 || startIndex >= endIndex) {
                                throw e; // No braces found or invalid order
                            }

                            response = response.slice(startIndex, endIndex + 1);
                            parsed = JSON.parse(response)
                        }
                        if (parsed["new_nonce"]) {
                            lpagery_ajax_object_modal.nonce = parsed["new_nonce"];
                        }

                        if (!parsed.success) {
                            console.error(parsed.exception)
                            onError(parsed.exception);
                            reject("Operation failed: " + parsed.exception);
                            return;
                        }

                        if (parsed.slug) {
                            processed_slugs.push(parsed.slug)
                        }

                        if (parsed.mode === "created") {
                            created++;
                            $('#lpagery_pagestatus_create').html("Created: <b>" + created + " </b>")
                        }
                        if (parsed.mode === "updated") {
                            updated++;
                            $('#lpagery_pagestatus_update').html("Updated: <b>" + updated + " </b>")
                        }

                        if (parsed.mode === "ignored") {
                            $('#lpagery_pagestatus_ignored').show()
                            ignored++;
                            $('#lpagery_pagestatus_ignored').html("Ignored (Slug already processed): <b>" + ignored + " </b>")
                        }

                        if (parsed.mode === "ignored_data_not_changed") {
                            $('#lpagery_pagestatus_ignored_not_changed').show()
                            ignored_data_not_changed++;
                            $('#lpagery_pagestatus_ignored_not_changed').html("Ignored (Data not changed): <b>" + ignored_data_not_changed + " </b>")
                        }
                        let sum = created + updated + ignored + ignored_data_not_changed;
                        $('#lpagery_pagestatus_total').html("Processed <b>" + sum + "</b> of <b>" + total_count + "</b> Pages")

                        resolve(parsed); // Resolve with the result of successful operation
                    } catch (e) {
                        console.error(e)
                        console.error(response)
                        onError(e, response);
                        reject("Error processing response: " + e); // Reject with error if processing response fails
                    }
                }
            })
        });
    }


    function wait_for_ram_if_needed(used_memory, retry) {

        const USAGE_PERCENT_THRESHOLD = 85;
        const USAGE_PERCENT_THRESHOLD_RETRY = 75;

        if (used_memory && !retry) {
            if (canceled || finished || ram_protection_ignored || used_memory.percent < USAGE_PERCENT_THRESHOLD) {
                refresh_ram_info(used_memory, false);
                return Promise.resolve();
            }
            refresh_ram_info(used_memory, true);
        }


        var ajaxData = {
            "action": 'lpagery_get_ram_usage', "_ajax_nonce": lpagery_ajax_object_modal.nonce
        };

        return new Promise(function (resolve, reject) {
            jQuery.ajax({
                "method": "get", "url": lpagery_ajax_object_modal.ajax_url, "data": ajaxData
            }).done(function (response) {
                let ram_info = JSON.parse(response);
                if (ram_info.percent > USAGE_PERCENT_THRESHOLD_RETRY && !ram_protection_ignored) {
                    refresh_ram_info(ram_info, true);
                    setTimeout(function () {
                        resolve(wait_for_ram_if_needed(ram_info, true));
                    }, 1000);
                } else {
                    refresh_ram_info(ram_info, false);
                    resolve();
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                reject(errorThrown);
            });
        });
    }


    function refresh_ram_info(memory_data, exceeded) {
        if (!memory_data.limit || memory_data.limit <= 0 || !exceeded) {
            $('#lpagery_ram_status').hide()
            return
        }
        if (exceeded && !ram_protection_ignored) {
            $('#lpagery_ram_status').show()
            let ram_status_value = "Allocated Memory: <br>" + memory_data.pretty_usage + "/" + memory_data.pretty_limit + " (" + memory_data.percent + "%)"

            $('#lpagery_ignore_ram_protection').show()
            ram_status_value += " <br> Waiting for more memory to be deallocated.<br> " + "If this message persists, consider deactivating plugins which can cause high memory usage or increase memory on this wordpress installation.<br>" + "This can be done with changing the WP_MAX_MEMORY_LIMIT setting. If the error is still appearing, please speak to your hosting provider.<br> <br> You can proceed the creation in clicking the button below. (Not recommended)";
            $('#lpagery_ram_status').html(ram_status_value)
        }


    }

    $('#lpagery_ignore_ram_protection').on('click', function () {
        ram_protection_ignored = true;
        $(this).hide();
    })


    $('#lpagery_closeModal a').on('click', function () {
        $.lpagery_modal.close();
    });

    $('#lpagery_cancel').on('click', function (e) {
        canceled = true;
        $.lpagery_modal.close();
    })


    function trimForGrid(value) {
        const escapeHtml = (unsafe) => {
            return unsafe.replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;').replaceAll('"', '&quot;').replaceAll("'", '&#039;');
        }
        if (typeof value === 'string' || value instanceof String) {
            if (value) {
                value = escapeHtml(value)
            }
            value = value && value.length > 30 ? value.substring(0, 30) + "..." : value

            return value
        }
        return value;
    }

    async function fetch_settings() {
        return new Promise(function (resolve, reject) {
            var settings_payload = {
                'action': 'lpagery_get_settings',
                "_ajax_nonce": lpagery_ajax_object_modal.nonce
            };

            jQuery.ajax({
                "method": "get",
                "url": lpagery_ajax_object_modal.ajax_url,
                "data": settings_payload,
                "dataType": "json" // specify the expected data type
            }).done(function (response) {
                resolve(response); // resolve the promise with the response
            }).fail(function (jqXHR, textStatus, errorThrown) {
                reject(errorThrown); // reject the promise with the error
            });
        });
    }


    $.lpageryGetHeaders = function getHeaders(headers) {
        return new Promise(async (resolve, reject) => {


            headers = headers.map(oneHeader => {
                return oneHeader.replace(/(\r\n|\n|\r)/gm, "");
            })

            let ignore_fields;
            $('#lpagery_max_placeholders_free').hide();
            $('#lpagery_max_placeholders_standard').hide();
            let allowedPlaceholders = JSON.parse(lpagery_ajax_object_modal.allowed_placeholders);
            if (allowedPlaceholders) {
                if (allowedPlaceholders.plan === "free") {
                    ignore_fields = allowedPlaceholders.placeholders
                    if (headers.slice(allowedPlaceholders.placeholders)) {
                        $('#lpagery_max_placeholders_free').show()
                    }
                } else if (allowedPlaceholders.plan === "standard") {
                    ignore_fields = allowedPlaceholders.placeholders
                    if (headers.slice(allowedPlaceholders.placeholders)) {
                        $('#lpagery_max_placeholders_standard').show()
                    }
                }
            }

            if (!headers.some(value => value === "lpagery_row_id")) {
                headers.push("lpagery_row_id")
            }
            var parsed_settings = await fetch_settings();
            var image_processing_enabled = parsed_settings.image_processing;


            let count = 0;
            let image_column_count = headers.filter(isImageColumn).length;
            if (image_column_count > 0 && !image_processing_enabled) {
                Toastify({
                    text: 'It looks like you\'d like to use image processing. To get started, please enable it in your settings.',
                    duration: 6000,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "center", // `left`, `center` or `right`
                    style: {
                        background: "linear-gradient(to right, #4f87b7, #73c4b6)",
                    }
                }).showToast();
            }
            if ((image_column_count > 5) && image_processing_enabled) {
                Toastify({
                    text: 'To avoid loading issues caused by the abundance of images, previews won\'t be displayed, but processing will be executed accurately.',
                    duration: 6000,
                    close: true,
                    gravity: "top", // `top` or `bottom`
                    position: "center", // `left`, `center` or `right`
                    style: {
                        background: "linear-gradient(to right, #4f87b7, #73c4b6)",
                    }
                }).showToast();
            }
            let mapped_headers = headers.map(headerValue => {
                if (!lpagery_ajax_object_modal.is_free_plan && lpagery_ajax_object_modal.is_extended_plan) {
                    if (isImageColumn(headerValue)) {
                        if (image_processing_enabled) {
                            let replaced_header_value = headerValue.replaceAll(".", "\u2024")
                            return {
                                "name": replaced_header_value,
                                "editing": false,
                                "sorting": false,
                                headerTemplate: function () {
                                    let image_header_container = $("<div>");
                                    if (image_data_header_cache_container.has(headerValue)) {
                                        set_image_header_html(image_header_container, headerValue);
                                    } else {
                                        let loading_img = lpagery_ajax_object_modal.plugin_dir + "/../assets/img/loading.png"
                                        image_header_container.append($('<img class="lpagery_img_not_found" src="' + loading_img + '" alt="image" width="42" height="42" title="No image with name loading found">'))
                                        get_and_set_image_header(headerValue, image_header_container);
                                    }
                                    return image_header_container;
                                },
                                "type": "text",

                                itemTemplate: function (value, row_item) {
                                    let lpagery_row_id = row_item.lpagery_row_id;
                                    let image_cell_container = $("<div>");
                                    if (image_data_cache_container.has(lpagery_row_id + headerValue)) {
                                        set_image_cell_html(headerValue, row_item, image_cell_container)
                                    } else {
                                        let loading_img = lpagery_ajax_object_modal.plugin_dir + "/../assets/img/loading.png"
                                        image_cell_container.append($('<img class="lpagery_img_not_found" src="' + loading_img + '" alt="image" width="42" height="42" title="No image with name loading found">'))
                                        get_and_set_image_cell_value(headerValue, row_item, image_cell_container, value);
                                    }
                                    return image_cell_container
                                }
                            }
                        }
                    }
                    if (headerValue === "lpagery_parent") {

                        return {
                            "name": "lpagery_parent", "title": "Parent", itemTemplate: function (value, item, test) {

                                var existing_post_container = $("<div>");
                                let parent_post_cached = parent_post_cache_container.has(item?.lpagery_row_id);
                                if (parent_post_cached) {
                                    set_page_html(parent_post_cache_container.get(item?.lpagery_row_id), existing_post_container);
                                } else {
                                    existing_post_container.append(trimForGrid(value));
                                    fetch_and_set_parent_post(existing_post_container, item, value);
                                }

                                return existing_post_container;
                            }
                        }
                    }
                    if (headerValue === "lpagery_author") {
                        return {
                            "name": "lpagery_author", "title": "Author", itemTemplate: function (value, item, test) {
                                var author_container = $("<div>");
                                let author_cached = author_cache_container.has(item?.lpagery_row_id);
                                if (author_cached) {
                                    set_author_html(author_cache_container.get(item?.lpagery_row_id), author_container);
                                } else {
                                    author_container.append(trimForGrid(value));
                                    fetch_and_set_author(author_container, item, value);
                                }

                                return author_container;
                            }
                        }
                    }
                    if (headerValue === "lpagery_categories") {
                        return {
                            "name": "lpagery_categories",
                            "title": "Categories",
                            "width": "200px",
                            itemTemplate: function (value, item, test) {
                                var categories_container = $("<div>");
                                let categories_cached = categories_cache_container.has(item?.lpagery_row_id);
                                if (categories_cached) {
                                    set_categories_html(categories_cache_container.get(item?.lpagery_row_id), categories_container);
                                } else {
                                    categories_container.append(trimForGrid(value));
                                    fetch_and_set_categories(categories_container, item, value)
                                }
                                return categories_container
                            }
                        }
                    }
                    if (headerValue === "lpagery_tags") {
                        return {
                            "name": "lpagery_tags", "title": "Tags", itemTemplate: function (value, item, test) {
                                var tags_container = $("<div>");
                                let tags_cached = tags_cache_container.has(item?.lpagery_row_id);
                                if (tags_cached) {
                                    set_tags_html(tags_cache_container.get(item?.lpagery_row_id), tags_container);
                                } else {
                                    tags_container.append(trimForGrid(value));
                                    fetch_and_set_tags(tags_container, item, value)
                                }
                                return tags_container
                            }
                        }
                    }
                    if (headerValue === "lpagery_status") {
                        return {
                            "name": "lpagery_status", "title": "Status", itemTemplate: function (value, item, test) {
                                if (value) {
                                    if (["publish", "private", "draft", "future"].includes(value.toLowerCase())) {
                                        return value.toLowerCase()
                                    }
                                }
                                return "-"
                            }
                        }
                    }
                    if (headerValue === "lpagery_publish_date") {
                        return {
                            "name": "lpagery_publish_date",
                            "title": "Publish Date",
                            itemTemplate: function (value, item, test) {
                                if (value) {

                                    const utcDate = new Date(value);
                                    if (utcDate) {
                                        return utcDate.toLocaleString();
                                    }
                                }
                                return "Invalid Input"
                            }
                        }
                    }
                }
                if (headerValue === "lpagery_fifu_url") {
                    return {
                        "name": "lpagery_fifu_url",
                        "title": "Featured Image Url",
                        itemTemplate: function (value, item, test) {
                            return $('<img alt="Featured Image" width="42" height="42" src="' + value + '">')
                        }
                    }
                }
                if (headerValue === "lpagery_fifu_alt") {
                    return {
                        "name": "lpagery_fifu_alt", "title": "Featured Image Alt", "itemTemplate": function (value) {
                            return trimForGrid(value);
                        },
                    }
                }
                if (headerValue === "lpagery_ignore") {
                    return {
                        "name": "lpagery_ignore", "title": "Ignore", "type": "checkbox", width: 50
                    }
                }
                if (headerValue === "lpagery_content") {
                    return {
                        "name": "lpagery_content", "title": "Post Content", "itemTemplate": function (value) {
                            return trimForGrid(value);
                        },
                    }
                }
                if (headerValue === "lpagery_row_id") {
                    return {
                        "name": "lpagery_row_id", "visible": true,

                        "type": "number", insertTemplate: function () {
                            let data = $("#lpagery_page_generation_grid").jsGrid("option", "data");

                            var $result = jsGrid.fields.text.prototype.insertTemplate.call(this); // original input

                            if (last_index == null) {
                                last_index = data.length;
                            } else {
                                last_index++;
                            }
                            $result.val(last_index);

                            return $result;
                        }
                    }

                }

                if (ignore_fields && count >= ignore_fields) {
                    return {
                        "name": (headerValue.replaceAll(".", "\u2024")),
                        "title": headerValue.length > 30 ? headerValue.substring(0, 30) + "..." : headerValue,
                        "type": "text",
                        headerTemplate: function () {
                            let pro_img = lpagery_ajax_object_modal.plugin_dir + "/../assets/img/pro.svg"
                            $('<p>' + headerValue + '</p>')
                            return $('<span>' + headerValue + '</span><img class="img-pro" src="' + pro_img + '" alt="image" width="15" height="15" title="No image with name ' + headerValue + ' found">')

                        },
                        "itemTemplate": function (value) {
                            return trimForGrid(value);
                        },
                        "ignore": true,
                        "css": "license-needed"
                    }
                }

                count++;
                return {
                    "name": (headerValue.replaceAll(".", "\u2024")),
                    "width": 120,
                    "title": headerValue.length > 30 ? headerValue.substring(0, 30) + "..." : headerValue,
                    "itemTemplate": function (value) {
                        return trimForGrid(value);
                    },
                    "type": "text"
                }
            });
            resolve(mapped_headers);
        })
    }


    $.lpageryToggleRotating = function toggleRotating(element, rotate) {
        if (rotate) {
            element.addClass('button--loading').prop("disabled", true);
        } else {
            element.removeClass('button--loading').prop("disabled", false);
        }
    }

    $.lpageryToggleEnabled = function toggleEnabled(element, enabled) {
        element.prop("disabled", !enabled);
    }

    function fetch_and_set_page_to_be_updated(existing_post_container, item) {
        item = prepare_element_trim(item);
        let ajaxData = {
            "action": 'lpagery_get_posts_to_be_updated',
            "data": JSON.stringify(item),
            "process_id": process_id,
            "_ajax_nonce": lpagery_ajax_object_modal.nonce
        };
        jQuery.ajax({
            "method": "post",
            "url": lpagery_ajax_object_modal.ajax_url,
            "data": ajaxData,
            "success": function (response) {
                let page_to_be_updated = JSON.parse(response);
                let post = page_to_be_updated.post;
                post_update_cache_container.set(Number(item.lpagery_row_id), post)
                set_page_html(post, existing_post_container)
            }
        })
    }

    function fetch_and_set_parent_post(existing_post_container, item, value) {
        item = prepare_element_trim(item);
        let ajaxData = {
            "action": 'lpagery_search_posts_by_slug',
            "data": JSON.stringify(item),
            "template_post_id": $('#lpagery_template_path').val(),
            "parent_post_id_dashboard": $('#lpagery_parent_path').val(),
            "_ajax_nonce": lpagery_ajax_object_modal.nonce
        };
        jQuery.ajax({
            "method": "post",
            "url": lpagery_ajax_object_modal.ajax_url,
            "data": ajaxData,
            "success": function (response) {
                let parsed = JSON.parse(response);
                parent_post_cache_container.set(Number(item.lpagery_row_id), parsed.post)
                set_page_html(parsed.post, existing_post_container)
            },
            "error": function (response) {
                console.error(response);
                existing_post_container.append(trimForGrid(value))
            }
        })

    }


    function fetch_and_set_author(author_container, item, value) {
        item = prepare_element_trim(item);

        let ajaxData = {
            "action": 'lpagery_get_authors',
            "data": JSON.stringify(item),
            "_ajax_nonce": lpagery_ajax_object_modal.nonce
        };
        jQuery.ajax({
            "method": "post",
            "url": lpagery_ajax_object_modal.ajax_url,
            "data": ajaxData,
            "success": function (response) {
                let parsed = JSON.parse(response);
                author_cache_container.set(Number(item.lpagery_row_id), parsed.author)
                set_author_html(parsed.author, author_container)
            },
            "error": function (response) {
                console.error(response);
                author_container.append(trimForGrid(value))
            }
        })
    }

    function fetch_and_set_categories(category_container, item, value) {
        item = prepare_element_trim(item);

        let ajaxData = {
            "action": 'lpagery_get_hierarchical_categories',
            "data": JSON.stringify(item),
            "_ajax_nonce": lpagery_ajax_object_modal.nonce
        };
        jQuery.ajax({
            "method": "post", "url": lpagery_ajax_object_modal.ajax_url, "data": ajaxData,
            "success": function (response) {
                let parsed = JSON.parse(response);
                categories_cache_container.set(Number(item.lpagery_row_id), parsed.hierarchical_categories)
                set_categories_html(parsed.hierarchical_categories, category_container)
            },
            "error": function (response) {
                console.error(response);
                category_container.append(trimForGrid(value))
            }
        })
    }

    function fetch_and_set_tags(tag_container, item, value) {
        item = prepare_element_trim(item);

        let ajaxData = {
            "action": 'lpagery_generate_tag_exists_output',
            "data": JSON.stringify(item),
            "_ajax_nonce": lpagery_ajax_object_modal.nonce
        };
        jQuery.ajax({
            "method": "post", "url": lpagery_ajax_object_modal.ajax_url, "data": ajaxData,
            "success": function (response) {
                let parsed = JSON.parse(response);
                tags_cache_container.set(Number(item.lpagery_row_id), parsed.tags)
                set_tags_html(parsed.tags, tag_container)
            },
            "error": function (response) {
                console.error(response);
                tag_container.append(trimForGrid(value))
            }
        })
    }


    function load_grid(data, headersFields) {

        created = 0;
        updated = 0;
        jQuery('.lpagery_fadeout').css({pointerEvents: ""})

        let row_index = 1;
        data = data.map(element => {
            Object.keys(element).forEach(key => {
                if (key.includes(".")) {
                    let replaced_key = (key.replaceAll(".", "\u2024"))
                    let tmp = element[key]
                    delete element[key]
                    element[replaced_key] = tmp;
                }
            })
            element.lpagery_row_id = row_index
            if (element.hasOwnProperty('lpagery_ignore')) {
                element.lpagery_ignore = parseStringToBoolean(element.lpagery_ignore)
            }
            row_index++;

            return element
        });


        $('.lpagery_fadeout').css({pointerEvents: ""})
        ram_protection_ignored = false;

        $('#lpagery_error-area').hide();
        $('.lpagery_pagestatus').html('');
        $('.lpagery_pagestatus').show()
        $('#lpagery_ram_status').html('');
        $('#lpagery_ram_status').hide();
        $('#lpagery_pause').hide()
        $('#lpagery_pause_error').hide()
        $('#lpagery_ignore_ram_protection').hide()

        $(".check-center").hide();
        $('.lpagery_fadeout').css('opacity', '')
        $('#lpagery_page_generation_grid').css({pointerEvents: ""})
        $('#lpagery_preview-mode').css({pointerEvents: ""})
        $.lpageryToggleRotating($('#lpagery_next'), false)
        $.lpageryToggleRotating($('#lpagery_accept'), false)

        if (!lpagery_ajax_object_modal.is_free_plan) {
            let control_index = headersFields.findIndex(element => element.type === "control");

            if (control_index < 0) {
                headersFields.push({type: "control"});
            }
        }


        $("#lpagery_page_generation_grid").jsGrid({
            width: "100%",
            height: "450px",
            inserting: !lpagery_ajax_object_modal.is_free_plan,
            editing: !lpagery_ajax_object_modal.is_free_plan,
            sorting: true,
            selecting: true,
            autoload: false,
            confirmDeleting: false,
            pageIndex: 1,
            pageSize: 10,
            pageButtonCount: 3,
            paging: true,
            pagerFormat: "{first} {prev} {pages} {next} {last}    {pageIndex} of {pageCount}",
            pagePrevText: "Prev",
            pageNextText: "Next",
            pageFirstText: "First",
            pageLastText: "Last",
            data: data,

            onItemUpdating: function () {
                post_update_cache_container = new Map();

            },
            fields: headersFields
        });

        let lpagery_existing_post_index = headersFields.findIndex(element => element.name === "lpagery_existing_post");

        if (lpagery_existing_post_index < 0 && getMode() === "update") {
            let existing_post_column = {
                "name": "lpagery_existing_post",
                "title": "Post to be updated",
                itemTemplate: function (value, item, test) {
                    if (process_id < 0) {
                        return "-";
                    }
                    let existing_post_container = $("<div>");
                    let page_to_be_updated_cached = post_update_cache_container.has(item?.lpagery_row_id);
                    if (page_to_be_updated_cached) {
                        set_page_html(post_update_cache_container.get(item?.lpagery_row_id), existing_post_container);
                    } else {
                        fetch_and_set_page_to_be_updated(existing_post_container, item);
                    }
                    return existing_post_container;
                }

            }
            headersFields.splice(1, 0, existing_post_column);
            $("#lpagery_page_generation_grid").jsGrid("option", "fields", headersFields);

            set_update_columns_visible();
        }
    }

    function set_update_columns_visible() {
        $("#lpagery_page_generation_grid").jsGrid("fieldOption", "lpagery_existing_post", "visible", update_mode);
    }

    $.lpageryShowModal = async function showModal(headersFields, data, sync_manually_param, process_id_param) {
        function removeEmptyObjects(arr) {
            return arr.filter(obj => {
                return Object.keys(obj).some(key => {
                    if (key !== 'lpagery_row_id') {
                        return obj[key] !== null && obj[key] !== undefined && obj[key] !== '';
                    }
                    return true;
                });
            });
        }

        data = removeEmptyObjects(data);

        sync_manually = sync_manually_param
        if (sync_manually) {
            process_id = process_id_param
        }

        $('#lpagery_input_not_unique').hide();
        let distinct_names = [...new Set(headersFields.map(obj => obj.name.toLowerCase()))];
        if (headersFields.length !== distinct_names.length) {
            let duplicatedHeaders = [];
            headersFields.forEach(obj => {
                const lowerCaseName = obj.name.toLowerCase();
                if (distinct_names.indexOf(lowerCaseName) !== distinct_names.lastIndexOf(lowerCaseName)) {
                    duplicatedHeaders.push(obj.name);
                }
            });
            if (duplicatedHeaders.length > 0) {
                $('#lpagery_input_not_unique').show();
                $('#duplicated_headers_value').html(duplicatedHeaders.join(', '));
                $.lpageryToggleRotating($('#lpagery_accept'), false);
                $.lpageryToggleRotating($('#lpagery_next'), false)
                return;
            }


        }
        if ($(".lpagery-create-update-tabs").find(".active")[0].id === "lpagery_anchor_update" || sync_manually) {
            update_mode = true
            $('#lpagery_update_post_hint').show()
            $('#lpagery_create_update_text_header').html("updated")
            $('#lpagery_create_update_text_button').html("Update")
            $('#lpagery_create_update_text_preview').html("updated")

            if (!process_id) {
                process_id = $('#lpagery_dashboard_process_select').val();
            }

            var ajaxData = {
                action: 'lpagery_get_process_details', id: process_id, "_ajax_nonce": lpagery_ajax_object_root.nonce
            };
            jQuery.get(lpagery_ajax_object_modal.ajax_url, ajaxData, function (response) {
                let parsed = JSON.parse(response);
                $('#lpagery_process_purpose').val(parsed.process.raw_purpose)
            })
        } else {

            update_mode = false;
            $('#lpagery_create_update_text_header').html("created")
            $('#lpagery_create_update_text_button').html("Create")
            $('#lpagery_create_update_text_preview').html("created")

            $('#lpagery_update_post_hint').hide()
        }


        load_grid(data, headersFields);


        $('#lpagery_confirmModal').lpagery_modal({
            escapeClose: false, clickClose: false, showClose: false
        });
        let lpagery_id_header = $(".jsgrid-header-cell.jsgrid-align-right.jsgrid-header-sortable:contains('lpagery_id')");
        if (lpagery_id_header) {
            lpagery_id_header.click()
        }
        // hier walkthrough
        var lpageryIntroShowed = localStorage.getItem('lpagery_modal_intro_showed');
        if (!lpageryIntroShowed && window.location.href.includes("lpagery")) {
            introJs().setOptions({
                disableInteraction: true, showProgress: true, showBullets: false,

                steps: [{
                    element: document.querySelector('#lpagery_page_generation_grid'),
                    title: 'Check all pages that will be created',
                    intro: 'Every row represents one created page. In the first row you can see all placeholders that are created and can be used on your page',
                    position: 'bottom',

                },

                    {

                        element: document.querySelector('#lpagery_accept'),
                        title: 'Create the pages',
                        intro: 'When you are done with any adjustments, click the button and all pages will be created',
                        position: 'bottom',

                    }

                ]
            }).start();
            localStorage.setItem('lpagery_modal_intro_showed', true);
        }
        $('.jsgrid-header-cell.jsgrid-header-sortable').filter(function () {
            // Check if the text contains the specified text
            return $(this).text().includes('lpagery_row_id');
        }).click(); // Click on the matched element

        $("#lpagery_page_generation_grid").jsGrid("fieldOption", "lpagery_row_id", "visible", false);
    }

    function onError(...errors) {
        console.error(errors)
        $('#lpagery_pagestatus_update_update').hide();
        $('#lpagery_error-area').show();
        $.lpageryToggleRotating($('#lpagery_accept'), false);
        canceled = true;
        let errorsString = '\n' + errors.map(s => '\n' + s).toString();
        $('#lpagery_error-content').html(errorsString)
    }

    function isImageColumn(name) {
        return ['.png', '.jpg', '.jpeg', '.heic', '.gif', '.svg', '.webp'].some(ending => name.endsWith(ending))
    }

    $.lpagery_save_pending_grid_data = function (grid) {
        let insert_row = grid.find('.jsgrid-insert-row')
        let insert_value = insert_row.find(':input:text').val();
        if (insert_value) {
            insert_row.find('.jsgrid-insert-button').click()
        }
        let edit_row = grid.find('.jsgrid-edit-row')
        let edit_value = edit_row.find(':input:text').val();
        if (edit_value) {
            edit_row.find('.jsgrid-update-button').click()
        }
    }

    async function lpagery_get_duplicated_slugs(slug, headers, data) {
        return new Promise((resolve, reject) => {
            let prepared_data = data.map(element => prepare_grid_data_trim(headers, element));

            let ajaxData = {
                "action": 'lpagery_get_duplicated_slugs',
                "data": JSON.stringify(prepared_data),
                "slug": slug,
                "process_id": process_id,
                "_ajax_nonce": lpagery_ajax_object_modal.nonce
            };

            $.ajax({
                method: "post",
                url: lpagery_ajax_object_modal.ajax_url,
                data: ajaxData
            }).done(response => {
                resolve(JSON.parse(response));
            }).fail((jqXHR, textStatus, errorThrown) => {
                reject(errorThrown);
            });
        });
    }

    function set_page_html(page_to_be_updated, existing_post_container) {
        existing_post_container.empty()
        if (!page_to_be_updated) {
            existing_post_container.append("-");
        } else {
            existing_post_container.append(($("<a target='_blank'>").attr("href", page_to_be_updated.permalink).text(page_to_be_updated.title)));
        }
    }

    function set_author_html(author, author_container) {
        author_container.empty()
        if (!author) {
            author_container.append("-");
        } else {
            author_container.append($('<span>' + author.name + "</span>"));
        }
    }

    function set_categories_html(categories, category_container) {
        category_container.empty()
        if (!categories) {
            category_container.append("-");
        } else {
            const $ul = $('<ul>');
            $.each(categories, function (index, category) {
                if (category && category.categories) {
                    var categoryText = category.categories.join(' > ') + (!category.exists ? ' (+)' : '');
                    var $li = $('<li>').text(categoryText);
                    $ul.append($li);
                }
            });
            category_container.append($ul);
        }
    }

    function set_tags_html(tagOutput, tag_container) {
        tag_container.empty()
        var $ul = $('<ul>');
        if (!tagOutput) {
            tag_container.append("-");
        } else {
            $.each(tagOutput, function (index, tagElement) {
                if (tagElement) {
                    var tagText = tagElement.tag + (!tagElement.exists ? ' (+)' : '');
                    var $li = $('<li>').text(tagText);
                    $ul.append($li);
                }
            });
        }


        tag_container.append($ul);
    }

    function set_image_header_html(image_header_container, header_value) {
        image_header_container.empty()
        var image_data = image_data_header_cache_container.get(header_value);
        let first_result = image_data[0];
        if (!first_result) {
            return;
        }
        if (first_result && first_result.url) {
            var url = first_result.url;
            image_header_container.append($('<img  src="' + url + '" alt="image" "width="42" height="42" title="' + header_value + '">'))
        } else {
            let not_found_imd = lpagery_ajax_object_modal.plugin_dir + "/../assets/img/image-not-found-icon.png"
            image_header_container.append($('<img class="lpagery_img_not_found" src="' + not_found_imd + '" alt="image" width="42" height="42" title="No image with name ' + header_value + ' found">'))
        }
    }


    function get_and_set_image_header(header_value, image_header_container) {

        var ajaxData = {
            "action": 'lpagery_get_image_urls',
            "header_name": encodeURIComponent(header_value),
            "_ajax_nonce": lpagery_ajax_object_modal.nonce
        };
        jQuery.ajax({
            "method": "post",
            "url": lpagery_ajax_object_modal.ajax_url,
            "data": ajaxData,
            "success": function (response) {
                let image_data = JSON.parse(response)

                image_data_header_cache_container.set(header_value, image_data);
                set_image_header_html(image_header_container, header_value);
            },
            "error": function (response) {
                console.error(response);
            }
        })

    }

    function get_and_set_image_cell_value(header_value, data, image_container, value) {

        data = prepare_element_trim(data)
        var ajaxData = {
            "action": 'lpagery_get_image_urls',
            "header_name": encodeURIComponent(header_value),
            "data": JSON.stringify(data),
            "_ajax_nonce": lpagery_ajax_object_modal.nonce
        };
        jQuery.ajax({
            "method": "post",
            "url": lpagery_ajax_object_modal.ajax_url,
            "data": ajaxData,
            "success": function (response) {
                let image_data = JSON.parse(response)
                image_data_cache_container.set(data.lpagery_row_id + header_value, image_data);
                set_image_cell_html(header_value, data, image_container);
            },
            "error": function (response) {
                console.error(response);
            }
        })

    }

    function set_image_cell_html(header_value, data, image_container) {
        image_container.empty()
        let image_data = image_data_cache_container.get(data.lpagery_row_id + header_value);
        if (!image_data || !image_data.url) {
            let not_found_imd = lpagery_ajax_object_modal.plugin_dir + "/../assets/img/image-not-found-icon.png"
            image_container.append($('<img class="lpagery_img_not_found" style="opacity: 0.4" src="' + not_found_imd + '" alt="image" "width="42" height="42">'));
            return;
        }

        if (image_data && image_data.download) {
            if (image_data.image_exists) {
                image_container.append($('<div class="lpagery-badge-container">\n' + '        <a href="#">\n' + '            <span class="lpagery-badge"><i class="fa-solid fa-file" style=" color: #3242D7DB;" title="An image with the name ' + image_data.file_name + ' already exists in your media library. The existing image will be used"></i></span>\n' + '            <img src="' + image_data.url + '"  alt="" "width="42" height="42"/>\n' + '        </a>\n' + '    </div>'))
                return
            } else {
                image_container.append($('<div class="lpagery-badge-container">\n' + '        <a href="#">\n' + '            <span class="lpagery-badge"><i class="fa-solid fa-download" style=" color: #3242D7DB;" title="This image will be downloaded and added to your media library with the name' + image_data.file_name + '. The metadata of the image will be replaced accordingly to the defined placeholders"></i></span>\n' + '            <img src="' + image_data.url + '"  alt="" "width="42" height="42"/>\n' + '        </a>\n' + '    </div>'));
                return
            }
        }

        if (image_data.copy) {
            if (image_data.image_exists) {
                image_container.append($('<div class="lpagery-badge-container">\n' + '        <a href="#">\n' + '            <span class="lpagery-badge"><i class="fa-solid fa-file" style=" color: #3242D7DB;" title="An image with the name ' + image_data.file_name + ' already exists in your media library. The existing image will be used"></i></span>\n' + '            <img src="' + image_data.url + '"  alt="" "width="42" height="42"/>\n' + '        </a>\n' + '    </div>'));
                return
            } else {
                image_container.append($('<div class="lpagery-badge-container">\n' + '        <a href="#">\n' + '            <span class="lpagery-badge"><i class="fa-solid fa-copy" style=" color: #3242D7DB;" title="This image will be copied and placed to your media library having the name ' + image_data.file_name + '. The metadata of the image will be replaced accordingly to the defined placeholders"></i></span>\n' + '            <img src="' + image_data.url + '"  alt="" "width="42" height="42"/>\n' + '        </a>\n' + '    </div>'));
                return

            }
        }

        image_container.append($('<img src="' + image_data.url + '" alt="image" "width="42" height="42" style="margin-top:18px">'))
    }

    $('#lpagery_discount_dialog_close').on('click', function () {
        $('#lpagery_free_discount_dialog').hide();
        $.lpagery_modal.close();
    })
})