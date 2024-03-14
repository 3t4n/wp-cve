jQuery(function ($) {

    let lpagery_create_update_mode = "create"
    let update_data_mode = "existing";
    $("#lpagery_form_invalid").hide();
    $('#lpagery_publish_date-section').hide();
    $("#lpagery_dashboard_container ").show();
    $("#lpagery_settings_container").hide();
    $("#lpagery_pro_container").hide();
    $("#lpagery_presets_container").hide();
    $("#lpagery_history_container").hide();
    $("#lpagery-sidebar").show();
    $('#lpagery_input_not_unique').hide()
    $('#lpagery_csv_invalid').hide()
    $('#lpagery_error_span').hide()
    $('#lpagery_google_sheet_url-error').hide()

    let now = new Date(),
        minDate = now.toISOString().substring(0, 16);
    $('#lpagery_publish_date').prop('min', minDate);
    jQuery.validator.setDefaults({
        success: "valid"
    });
    jQuery.validator.addMethod("validate_csv", function (value, element, options) {
        if (getMode() === "update" && update_data_mode === "existing") {
            return true;
        }
        if (!$('#lpagery_modeCsv:checked').val()) {
            return true;
        }
        let filledInputs = $('.csv_input:input').filter((i, element) => {
            return $(element).val().length > 0
        })

        return filledInputs.length === 1;

    }, "Please fill out at least {0} of these fields.");

    var isHeadlessChrome = window.navigator.userAgent.includes("HeadlessChrome");
    //Intro Free Version
    var lpageryIntroShowed = localStorage.getItem('lpagery_intro_showed_free');
    if (!isHeadlessChrome && !lpageryIntroShowed && window.location.href.includes("lpagery") && lpagery_ajax_object_root.is_free_plan && $('#lpagery_dashboard_container').length) {
        let image_url = lpagery_ajax_object_root.plugin_dir + "/../assets/img/LP_Logo_horizontal_2022_RZ.png";
        introJs().setOptions({
            disableInteraction: true,
            showProgress: true,
            showBullets: false,

            steps: [{
                title: 'Welcome to <br><img src="' + image_url + '" width="160px" height="auto" style="padding-top:10px;">',
                intro: 'Let us give you a quick tour around so you can start using LPagery!'
            },
                {
                    element: document.querySelector('.template-path-section'),
                    title: 'Step 1-1: Select a Template Page',
                    intro: 'In the <b>first step</b> you will have to choose your template page.',
                    position: 'right',

                },
                {
                    element: document.querySelector('#lpagery_menu-pages'),
                    title: 'Step 1-2: Creating a template',
                    intro: 'You can use a regular page as template page, you just have to insert certain placeholders. Check out our step-by-step guide on <a href="https://lpagery.io/docs/create-a-template-page/" target="_blank">How to Create a Template Page</a>',
                    position: 'right',

                },


                {
                    element: document.querySelector('.column-left'),
                    title: 'Step 2',
                    intro: 'Next up you have to upload a CSV/XLSX File with your data. Take a look at our tutorial on <a href="https://lpagery.io/docs/set-up-a-csv-file-with-custom-placeholders/">How to set up a CSV File</a>',
                    position: 'right',

                },


                {
                    element: document.querySelector('#lpagery_docs-link'),
                    title: 'More Information',
                    intro: 'For detailed tutorials and more help, make sure to check out our <a href="https://lpagery.io/documentation/">documentation</a>',
                    position: 'bottom',

                },

                {
                    element: document.querySelector('#lpagery_pro-link'),
                    title: 'Go Pro!',
                    intro: 'If you want to have full control over the created pages and use all features, check out our Pro plans',
                    position: 'bottom',

                },

            ]
        }).start();
        localStorage.setItem('lpagery_intro_showed_free', true);
    }


    var tabs = $('.tabs');
    var selector = $('.tabs').find('a').length;
    //var selector = $(".tabs").find(".selector");
    var activeItem = tabs.find('.active');
    var activeWidth = activeItem.innerWidth();
    $(".selector").css({
        "left": activeItem.position.left + "px",
        "width": activeWidth + "px"
    });

    $(".tabs").on("click", "a", function (e) {
        e.preventDefault();
        $('.tabs a').removeClass("active");
        $(this).addClass('active');
        var activeWidth = $(this).innerWidth();
        var itemPos = $(this).position();
        $(".selector").css({
            "left": itemPos.left + "px",
            "width": activeWidth + "px"
        });
    });

    var tabs = $('.lpagery-create-update-tabs');
    var selector = $('.lpagery-create-update-tabs').find('a').length;
    //var selector = $(".tabs").find(".selector");
    var activeItem = tabs.find('.active');
    var activeWidth = activeItem.innerWidth();
    $(".lpagery-create-update-tabs-selector").css({
        "left": activeItem.position.left + "px",
        "width": activeWidth + "px"
    });

    $(".lpagery-create-update-tabs").on("click", "a", function (e) {
        e.preventDefault();
        $('.lpagery-create-update-tabs a').removeClass("active");
        $(this).addClass('active');
        var activeWidth = $(this).innerWidth();
        var itemPos = $(this).position();
        $(".lpagery-create-update-tabs-selector").css({
            "left": itemPos.left + "px",
            "width": activeWidth + "px"
        });
    });

    $('#lpagery_process-select-section').hide()

    $('.js-example-basic-single').select2();

    function load_page_select(select, selected_value) {



        select.select2({
            ajax: {
                url: lpagery_ajax_object_root.ajax_url,
                data: function (params) {
                    return {
                        "_ajax_nonce": lpagery_ajax_object_root.nonce,
                        action: 'lpagery_get_pages',
                        mode: getMode(),
                        select: this.attr("id"),
                        term: params.term,
                        post_id: $('#lpagery_template_path').val()
                    };

                },
                processResults: function (data) {
                    return {
                        results: $.map(JSON.parse(data), function (item) {
                            return {
                                text: item.title,
                                id: item.id
                            }
                        })
                    };
                }
            }
        });
        if (selected_value) {
            var ajaxData = {
                action: 'lpagery_get_post_title',
                post_id: selected_value,
                "_ajax_nonce": lpagery_ajax_object_root.nonce
            };
            jQuery.get(
                lpagery_ajax_object_root.ajax_url,
                ajaxData,
                function (response) {
                    title = new DOMParser().parseFromString(response, "text/html").documentElement.textContent;
                    var option = new Option(title, selected_value, true, true);
                    select.append(option).trigger('change');

                    // manually trigger the `select2:select` event
                    select.trigger({
                        type: 'select2:select'
                    });
                })

        }
    }

    load_page_select($('#lpagery_template_path'));
    load_page_select($('#lpagery_parent_path'));

    $('.js-example-basic-multiple').select2();

    function reload_dashboad_process_select(process) {
        let post_id;
        if (process) {
            post_id = process.post_id
        }
        var select = $('#lpagery_dashboard_process_select');
        select.select2({
            ajax: {
                url: lpagery_ajax_object_root.ajax_url,
                data: function (params) {
                    return {
                        "_ajax_nonce": lpagery_ajax_object_root.nonce,
                        action: 'lpagery_get_processes_by_post',
                        term: params.term,
                        post_id: post_id || $('#lpagery_template_path').val()
                    };

                },
                processResults: function (data) {
                    return {
                        results: $.map(JSON.parse(data), function (item) {
                            return {
                                text: item.display_purpose,
                                id: item.id,
                                data: item.data
                            }
                        })
                    };
                }
            }
        });
        if (process) {
            var option = new Option(process.display_purpose, process.id, true, true);
            select.append(option).trigger('change');

            // manually trigger the `select2:select` event
            select.trigger({
                type: 'select2:select'
            });
        }

    }

    reload_dashboad_process_select();

    function set_update_process_data(parsed) {
        let configData = parsed.config_data;
        $('#lpagery_existing_input').click();
        $('#lpagery_modeCsv').click()
        $('#lpagery_google_sheet_url').val(null).trigger("change")
        $('#lpagery_google_sync_enabled').val('no_sync').trigger('change')
        $('#syncAdd').prop('checked',false)
        $('#syncUpdate').prop('checked',false)
        $('#syncDelete').prop('checked',false)
        if (configData) {
            $('#lpagery_post_status').val(configData.status).val("-1").trigger('change')
            $('#lpagery_slug').val(configData.slug)
            load_page_select($("#lpagery_parent_path"), configData.parent_path)
            $('#lpagery_parent_path').val(configData.parent_path).trigger('change');
            $('#lpagery_tags').val(configData.tags).trigger('change')
            $('#lpagery_categories').val(configData.categories).trigger('change')
        }
        let googleSheetData = parsed.google_sheet_data;
        if(googleSheetData) {
            $('#lpagery_new_input').click();
            $('#lpagery_modeGoogleSheet').click();
            $('#lpagery_google_sheet_url').val(googleSheetData.url)
            $('#lpagery_google_sync_enabled').val(parsed.google_sheet_sync_enabled !== '0' ? 'sync' : 'no_sync').trigger('change')
            $('#syncAdd').prop('checked', googleSheetData.add)
            $('#syncUpdate').prop('checked', googleSheetData.update)
            $('#syncDelete').prop('checked', googleSheetData.delete)
        }
    }

    $('#lpagery_dashboard_process_select').on('change', function () {
        var ajaxData = {
            action: 'lpagery_get_process_details',
            id: $(this).val(),
            "_ajax_nonce": lpagery_ajax_object_root.nonce
        };
        jQuery.get(
            lpagery_ajax_object_root.ajax_url,
            ajaxData,
            function (response) {
                let parsed = JSON.parse(response);
                set_update_process_data(parsed);
            }
        );
    })

    $('#lpagery_slug').bind('input keyup', function () {
        var $this = $(this);
        var delay = 300; // 2 seconds delay after last input

        clearTimeout($this.data('timer'));
        $this.data('timer', setTimeout(function () {
            $this.removeData('timer');
            refreshSlug();
            // Do your stuff after 2 seconds of last user input
        }, delay));
    });

    $('#lpagery_parent_path').on('change', function (e) {
        refreshSlug();
    })

    $('#lpagery_template_path').on('change', function (e) {
        let slug = $('#lpagery_slug');
        if(!$(this).val()) {
            return;
        }
        let selectedName = $("#lpagery_template_path option:selected").text();
        if ((!slug.val() || lpagery_ajax_object_root.is_free_plan) && getMode() === 'create') {
            var ajaxData = {
                action: 'lpagery_custom_sanitize_title',
                slug: encodeURIComponent(selectedName),
                "_ajax_nonce": lpagery_ajax_object_root.nonce
            };
            jQuery.get(
                lpagery_ajax_object_root.ajax_url,
                ajaxData,
                function (response) {
                    slug.val(response)
                    refreshSlug();
                }
            );
        }

    })

    function handleCsvError(error) {
        $('#lpagery_csv_invalid').show()
        $('#lpagery_csv_details').html(error);

        $.lpageryToggleRotating($('#lpagery_next'), false)
        $.lpagery_modal.close();
    }

    function handleError(value) {

        $('#lpagery_error_value').html(value);
        $('#lpagery_error_span').show();
        $.lpageryToggleRotating($('#lpagery_next'), false)
        $.lpagery_modal.close();
    }

    function handleCsv(file) {
        var reader = new FileReader();
        reader.readAsText(file);
        reader.onload = function (e) {
            try {
                var csvContent = e.target.result;
                Papa.parse(csvContent, {
                    header: true,
                    dynamicTyping: true,
                    complete: async function (results) {
                        // Save result in a globally accessible var
                        let errors = results.errors
                        let headers = results.meta.fields;
                        let data = results.data
                        console.error(errors)

                        $.lpageryShowModal(await $.lpageryGetHeaders(headers), data);
                    },
                    error: function (err, file, inputElem, reason) {
                        handleCsvError(err + "\n" + reason)
                    }
                });
            } catch (e) {
                handleCsvError(e.stack)
            }
        }
    }

    function handleExcel(file) {
        var reader = new FileReader();
        reader.readAsArrayBuffer(file);
        reader.onload = async function (e) {
            var fileContent = e.target.result;
            var workbook = XLSX.read(fileContent);
            let jsonSheet = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[0]]);


            var headers = getHeaders(workbook.Sheets[workbook.SheetNames[0]]);
            let headersFields = await $.lpageryGetHeaders(headers);
            $.lpageryShowModal(headersFields, jsonSheet);
        }
    }

    $('#lpagery_next').on('click', async function () {
        $('#lpagery_error_span').hide();
        $('#lpagery_form_invalid').hide();
        $('#lpagery_csv_invalid').hide();
        $.clearModalData();

        try {

            let lpageryForm = $('#lpagery_form');
            let valid = lpageryForm.validate({
                errorPlacement: function (error, element) {
                    if (element.attr("name") == "template_path")
                        error.insertAfter("#lpagery_template_error");

                    if (element.attr("name") == "lat")
                        error.insertAfter("#lpagery_location_error");
                    if (element.attr("name") == "upload_csv" || element.attr("name") == "validate_csv")
                        error.insertAfter("#lpagery_location_error");
                    if (element.attr("name") == "slug") {
                        error.insertAfter("#lpagery_slug_preview")

                    }
                    if (element.attr("name") == "lpagery_dashboard_process_select") {
                        error.insertAfter("#lpagery_process_error")
                    }
                    if (element.attr("name") == "publish_date") {
                        error.insertAfter("#lpagery_publish_date_error")
                    }
                },
                rules: {
                    lpagery_dashboard_process_select: {
                        required: function (element) {
                            return !!$("#lpagery_mode_switch").is(':checked');
                        }
                    },
                    publish_date: {
                        required: function (element) {
                            return $('#lpagery_post_status').val() === "future"
                        }
                    },

                    lat: {
                        required: '#lpagery_modeRadius:checked'
                    },
                    google_sheet_url: {
                        validate_csv: true,
                        required: '#lpagery_modeGoogleSheet:checked'


                    },
                    upload_csv: {
                        validate_csv: true,

                    }
                }
            }).form()

            if (!valid) {
                $("#lpagery_form_invalid").show();

                return
            }
            $.lpageryToggleRotating($('#lpagery_next'), true)


            let mode = $("input[name$='modeRadio']:checked").val()

            if (getMode() === "update" && update_data_mode === "existing") {
                var ajaxData = {
                    action: 'lpagery_get_process_with_data',
                    id: $('#lpagery_dashboard_process_select').val(),
                    "_ajax_nonce": lpagery_ajax_object_root.nonce
                };
                jQuery.get(
                    lpagery_ajax_object_root.ajax_url,
                    ajaxData,
                    async function (response) {
                        let parsed = JSON.parse(response);
                        let data = parsed.data;
                        let keys = Object.keys(data[0])
                        let headersFields = await $.lpageryGetHeaders(keys);

                        keys.forEach(key => {
                            if (key.includes(".")) {
                                data = data.map(element => {
                                    delete Object.assign(element, {[key.replaceAll(".", "\u2024")]: element[key]})[key];
                                    return element;
                                })
                            }
                        })

                        $.lpageryShowModal(headersFields, data);
                    }
                );

            } else if (mode === 'csv') {
                var file = $('#lpagery_upload_csv').prop('files')[0];
                if (file) {
                    if (file.name.endsWith('.xlsx') || file.name.endsWith('.xls')) {
                        handleExcel(file);
                    } else {
                        handleCsv(file);
                    }
                }


            } else if (mode === 'location') {
                if ($.fetchCities) {
                    $.fetchCities();
                }
            } else {
                $('#lpagery_google_sheet_url-error').hide();
                let googleSheetUrl = $('#lpagery_google_sheet_url').val();

                if (googleSheetUrl) {
                    var googleSheetExists = await $.googleSheetExists($('#lpagery_google_sheet_url').val());
                    if(!googleSheetExists) {
                        $('#lpagery_google_sheet_url-error').show();
                        $.lpageryToggleRotating($('#lpagery_next'), false)
                    } else {
                        await $.fetchGoogleSheet($('#lpagery_google_sheet_url').val());
                    }

                }
            }
        } catch (e) {
            handleError(e.stack);
        }
    })

    function refreshSlug() {
        let slugValue = $('#lpagery_slug').val();
        if (slugValue) {
            var ajaxData = {
                "_ajax_nonce": lpagery_ajax_object_root.nonce,
                action: 'lpagery_fetch_permalink',
                post_id: $('#lpagery_parent_path').val(),
                slug: slugValue
            };
            jQuery.get(
                lpagery_ajax_object_root.ajax_url,
                ajaxData,
                function (response) {
                    $('#lpagery_slug_preview').text(response)
                }
            );
        } else {
            $('#lpagery_slug_preview').text('')
        }
    }

    if (lpagery_ajax_object_root.is_permalink_structure_disabled) {
        $('#lpagery_slug_disabled').show();
        $('#lpagery_slug_preview').hide();
    } else {
        $('#lpagery_slug_disabled').hide();
        $('#lpagery_slug_preview').show();
    }

    $('#lpagery_post_status').on('change', function () {
        if ($('#lpagery_post_status').val() === "future") {
            $('#lpagery_publish_date-section').show();
        } else {
            $('#lpagery_publish_date-section').hide();
        }
    })

    $('.lpagery-create-update-tabs > a').on("click", function (e) {
        let element = $(this)[0];
        switch (element.id) {
            case "lpagery_anchor_create": {
                $('#create_update_title').html("Create");

                $("#lpagery_post_status option[value='-1']").remove();

                lpagery_create_update_mode = "create";
                $('#lpagery_process-select-section').hide()
                $('#lpagery_update_existing_data').hide();
                $('#lpagery_modeRadio').show();

                break;
            }
            case "lpagery_anchor_update": {
                $('#lpagery_template_path').val(null).trigger('change')
                lpagery_create_update_mode = "update";
                $('#create_update_title').html("Update");
                //$('#lpagery_template_path').val(null).trigger('change');
                $('#lpagery_process-select-section').show()
                $('#lpagery_update_existing_data').show();
                if (update_data_mode === 'new') {
                    $('#lpagery_modeRadio').show();
                } else {
                    $('#lpagery_modeRadio').hide();
                }
                var newStatusOption = new Option("Keep Status", "-1", true, true);
                $('#lpagery_post_status').append(newStatusOption)

                break;
            }
        }
    })

    $('.tabs > a').on("click", function (e) {
        let element = $(this)[0];
        localStorage.setItem("lpagery_selected_tab", element.id);
        switch (element.id) {
            case "lpagery_anchor_settings": {
                $("#lpagery_dashboard_container ").hide();
                $("#lpagery_settings_container").show();
                $("#lpagery_pro_container").hide();
                $("#lpagery_presets_container").hide();
                $("#lpagery_history_container").hide();
                $("#lpagery-sidebar").show();

                break;

            }
            case "lpagery_anchor_dashboard": {
                $("#lpagery_dashboard_container ").show();
                $("#lpagery_settings_container").hide();
                $("#lpagery_pro_container").hide();
                $("#lpagery_presets_container").hide();
                $("#lpagery_history_container").hide();
                $("#lpagery-sidebar").show();

                break;
            }
            case "lpagery_anchor_pro": {
                $("#lpagery_dashboard_container ").hide();
                $("#lpagery_settings_container").hide();
                $("#lpagery_pro_container").show();
                $("#lpagery_presets_container").hide();
                $("#lpagery_history_container").hide();
                $("#lpagery-sidebar").show();

                break;
            }
            case "lpagery_anchor_history": {
                $("#lpagery_dashboard_container ").hide();
                $("#lpagery_settings_container").hide();
                $("#lpagery_pro_container").hide();
                $("#lpagery_presets_container").hide();
                $("#lpagery_history_container").show();
                $("#lpagery-sidebar").hide();
                break;
            }
        }
    });

    function guessDelimiters(text) {
        let possibleDelimiters = [',', ';', '\t', '|'];
        return possibleDelimiters.filter(weedOut);

        function weedOut(delimiter) {
            var cache = -1;
            return text.split('\n').every(checkLength);

            function checkLength(line) {
                if (!line) {
                    return true;
                }

                var length = line.split(delimiter).length;
                if (cache < 0) {
                    cache = length;
                }
                return cache === length && length > 1;
            }
        }
    }

    function getHeaders(sheet) {
        var header = 0, offset = 1;
        var hdr = [];
        var o = {};
        if (sheet == null || sheet["!ref"] == null) return [];
        var range = o.range !== undefined ? o.range : sheet["!ref"];
        var r;
        if (o.header === 1) header = 1;
        else if (o.header === "A") header = 2;
        else if (Array.isArray(o.header)) header = 3;
        switch (typeof range) {
            case 'string':
                r = safe_decode_range(range);
                break;
            case 'number':
                r = safe_decode_range(sheet["!ref"]);
                r.s.r = range;
                break;
            default:
                r = range;
        }
        if (header > 0) offset = 0;
        var rr = XLSX.utils.encode_row(r.s.r);
        var cols = new Array(r.e.c - r.s.c + 1);
        for (var C = r.s.c; C <= r.e.c; ++C) {
            cols[C] = XLSX.utils.encode_col(C);
            var val = sheet[cols[C] + rr];
            switch (header) {
                case 1:
                    hdr.push(C);
                    break;
                case 2:
                    hdr.push(cols[C]);
                    break;
                case 3:
                    hdr.push(o.header[C - r.s.c]);
                    break;
                default:
                    if (val === undefined) continue;
                    hdr.push(XLSX.utils.format_cell(val));
            }
        }
        return hdr;
    }


    function safe_decode_range(range) {
        var o = {s: {c: 0, r: 0}, e: {c: 0, r: 0}};
        var idx = 0, i = 0, cc = 0;
        var len = range.length;
        for (idx = 0; i < len; ++i) {
            if ((cc = range.charCodeAt(i) - 64) < 1 || cc > 26) break;
            idx = 26 * idx + cc;
        }
        o.s.c = --idx;

        for (idx = 0; i < len; ++i) {
            if ((cc = range.charCodeAt(i) - 48) < 0 || cc > 9) break;
            idx = 10 * idx + cc;
        }
        o.s.r = --idx;

        if (i === len || range.charCodeAt(++i) === 58) {
            o.e.c = o.s.c;
            o.e.r = o.s.r;
            return o;
        }

        for (idx = 0; i != len; ++i) {
            if ((cc = range.charCodeAt(i) - 64) < 1 || cc > 26) break;
            idx = 26 * idx + cc;
        }
        o.e.c = --idx;

        for (idx = 0; i != len; ++i) {
            if ((cc = range.charCodeAt(i) - 48) < 0 || cc > 9) break;
            idx = 10 * idx + cc;
        }
        o.e.r = --idx;
        return o;
    }

    if (lpagery_ajax_object_root.is_free_plan) {
        $('.license-needed').find($('.select2-selection__arrow')).remove()
    } else {
        $('.img-pro').remove();
    }


    let reset_filter_button = $('#lpagery_reset_filter');
    if (reset_filter_button) {
        let url = new URL(location.href);
        var lpagery_process = url.searchParams.get("lpagery_process");
        var lpagery_template = url.searchParams.get("lpagery_template");
        if (lpagery_process == null && lpagery_template == null) {
            reset_filter_button.remove();
        } else {
            reset_filter_button.show();
        }

        reset_filter_button.on('click', function () {
            let url = new URL(location.href);
            url.searchParams.delete('lpagery_process');
            url.searchParams.delete('lpagery_template');
            window.location = url;
        })
    }


    let url = new URL(location.href);
    $.lpagery_load_update_process = function (update_process_id) {
        if (update_process_id) {

            $('#update_process_id').val(update_process_id).change();
            var ajaxData = {
                action: 'lpagery_get_process_details',
                id: update_process_id,
                "_ajax_nonce": lpagery_ajax_object_root.nonce
            };
            jQuery.get(
                lpagery_ajax_object_root.ajax_url,
                ajaxData,
                function (response) {
                    let parsed = JSON.parse(response);
                    load_page_select($('#lpagery_template_path'), parsed.process.post_id)
                    reload_dashboad_process_select(parsed.process);
                    $('#lpagery_anchor_update').click()
                }
            );
        }
    }
    $('#lpagery_update_existing_data').hide();


    $('input[type=radio][name=modeExistingInput]').change(function () {
        update_data_mode = this.value;
        if (this.value === "new") {
            $('#lpagery_modeRadio').show();
        } else {
            $('#lpagery_modeRadio').hide();

        }
    })

    let selected_tab = localStorage.getItem("lpagery_selected_tab");
    if (selected_tab) {
        let url = new URL(location.href);
        let lpagery_process = url.searchParams.get("lpagery_update_process");
        let lpagery_template = url.searchParams.get("update_post_id");
        if (!lpagery_template && !lpagery_process) {
            $('#' + selected_tab).click();
        } else {
            localStorage.removeItem("lpagery_selected_tab");
        }
    }

    function getMode() {
        let mode = jQuery(".lpagery-create-update-tabs").find(".active")[0].id === "lpagery_anchor_update" ? "update" : "create";
        return mode;
    }

    function validateCSV(csv, delimiter) {
        // split the CSV into rows
        const rows = csv.split('\n');

        // check that all rows have the same number of columns
        const expectedNumColumns = rows[0].split(delimiter).length;
        for (let i = 1; i < rows.length; i++) {
            const numColumns = rows[i].split(delimiter).length;
            if (numColumns !== expectedNumColumns) {
                return false;
            }
        }

        // additional rules could be added here, such as checking that certain columns
        // have valid data or that certain rows meet specific criteria

        // if all rules pass, the CSV is considered valid
        return true;
    }


    $('a[href*="lpagery-pricing"]').attr("href", "https://lpagery.io/pricing/").attr("target", "_blank")

    $('#lpagery_google_sync_enabled').on('change', function () {
        let value = $(this).val();
        if(value === "no_sync") {
            $('#lpagery_input-radio').removeClass("lpagery-disabled")
            $('#lpagery_update_existing_data_radio').removeClass("lpagery-disabled")
            $('#google_sheet_sync_settings').hide()
        } else {
            $('#google_sheet_sync_settings').show()
            $('#lpagery_input-radio').addClass("lpagery-disabled")
            $('#lpagery_update_existing_data_radio').addClass("lpagery-disabled")
        }
    })

    var $tooltips = $('.lpagery-container .tooltip');

    $tooltips.on('click', function (event) {
        event.stopPropagation();
        $tooltips.not(this).removeClass('clicked'); // Close other tooltips
        $(this).toggleClass('clicked');
    });

    $(document).on('click', function () {
        $tooltips.removeClass('clicked');
    });
    $('#lpagery_google_sync_enabled').val('no_sync').trigger('change')
});
