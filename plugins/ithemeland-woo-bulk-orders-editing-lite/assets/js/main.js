"use strict";

var wobelWpEditorSettings = {
    mediaButtons: true,
    tinymce: {
        branding: false,
        theme: 'modern',
        skin: 'lightgray',
        language: 'en',
        formats: {
            alignleft: [
                { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: { textAlign: 'left' } },
                { selector: 'img,table,dl.wp-caption', classes: 'alignleft' }
            ],
            aligncenter: [
                { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: { textAlign: 'center' } },
                { selector: 'img,table,dl.wp-caption', classes: 'aligncenter' }
            ],
            alignright: [
                { selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles: { textAlign: 'right' } },
                { selector: 'img,table,dl.wp-caption', classes: 'alignright' }
            ],
            strikethrough: { inline: 'del' }
        },
        relative_urls: false,
        remove_script_host: false,
        convert_urls: false,
        browser_spellcheck: true,
        fix_list_elements: true,
        entities: '38,amp,60,lt,62,gt',
        entity_encoding: 'raw',
        keep_styles: false,
        paste_webkit_styles: 'font-weight font-style color',
        preview_styles: 'font-family font-size font-weight font-style text-decoration text-transform',
        end_container_on_empty_block: true,
        wpeditimage_disable_captions: false,
        wpeditimage_html5_captions: true,
        plugins: 'charmap,colorpicker,hr,lists,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpeditimage,wpemoji,wpgallery,wplink,wpdialogs,wptextpattern,wpview',
        menubar: false,
        wpautop: true,
        indent: false,
        resize: true,
        theme_advanced_resizing: true,
        theme_advanced_resize_horizontal: false,
        statusbar: true,
        toolbar1: 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,unlink,wp_adv',
        toolbar2: 'strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
        toolbar3: '',
        toolbar4: '',
        tabfocus_elements: ':prev,:next',
    },
    quicktags: {
        buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,more,close"
    }
}

jQuery(document).ready(function ($) {
    $(document).on('click', '.wobel-timepicker, .wobel-datetimepicker, .wobel-datepicker', function () {
        $(this).attr('data-val', $(this).val());
    });

    wobelReInitDatePicker();
    wobelReInitColorPicker();

    // Select2
    if ($.fn.select2) {
        let wobelSelect2 = $(".wobel-select2");
        if (wobelSelect2.length) {
            wobelSelect2.select2({
                placeholder: "Select ..."
            });
        }
    }

    $(document).on("click", ".wobel-tabs-list li a.wobel-tab-item", function (event) {
        if ($(this).attr('data-disabled') !== 'true') {
            event.preventDefault();

            if ($(this).closest('.wobel-tabs-list').attr('data-type') == 'url') {
                window.location.hash = $(this).attr('data-content');
            }

            wobelOpenTab($(this));
        }
    });

    // Modal
    $(document).on("click", '[data-toggle="modal"]', function () {
        wobelOpenModal($(this).attr("data-target"));
    });

    $(document).on("click", '[data-toggle="modal-close"]', function () {
        wobelCloseModal();
    });

    // Float side modal
    $(document).on("click", '[data-toggle="float-side-modal"]', function () {
        wobelOpenFloatSideModal($(this).attr("data-target"));
    });

    $(document).on("click", '[data-toggle="float-side-modal-close"]', function () {
        if ($('.wobel-float-side-modal:visible').length && $('.wobel-float-side-modal:visible').hasClass('wobel-float-side-modal-close-with-confirm')) {
            swal({
                title: 'Are you sure?',
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
                confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
                confirmButtonText: iwbveTranslate.iAmSure,
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $('.wobel-float-side-modal:visible').removeClass('wobel-float-side-modal-close-with-confirm');
                    wobelCloseFloatSideModal();
                }
            });
        } else {
            wobelCloseFloatSideModal();
        }
    });

    $(document).on("keyup", function (e) {
        if (e.keyCode === 27) {
            if (jQuery('.wobel-modal:visible').length > 0) {
                wobelCloseModal();
            } else {
                if ($('.wobel-float-side-modal:visible').length && $('.wobel-float-side-modal:visible').hasClass('wobel-float-side-modal-close-with-confirm')) {
                    swal({
                        title: ($('.wobel-float-side-modal:visible').attr('data-confirm-message') && $('.wobel-float-side-modal:visible').attr('data-confirm-message') != '') ? $('.wobel-float-side-modal:visible').attr('data-confirm-message') : 'Are you sure?',
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
                        confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
                        confirmButtonText: iwbveTranslate.iAmSure,
                        closeOnConfirm: true
                    }, function (isConfirm) {
                        if (isConfirm) {
                            $('.wobel-float-side-modal:visible').removeClass('wobel-float-side-modal-close-with-confirm');
                            wobelCloseFloatSideModal();
                        }
                    });
                } else {
                    wobelCloseFloatSideModal();
                }
            }

            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });

            if ($("#wobel-filter-form-content").css("display") === "block") {
                $("#wobel-bulk-edit-filter-form-close-button").trigger("click");
            }
        }
    });

    // Color Picker Style
    $(document).on("change", "input[type=color]", function () {
        this.parentNode.style.backgroundColor = this.value;
    });

    $(document).on('click', '#wobel-full-screen', function () {
        if ($('#adminmenuback').css('display') === 'block') {
            openFullscreen();
        } else {
            exitFullscreen();
        }
    });

    if (document.addEventListener) {
        document.addEventListener('fullscreenchange', wobelFullscreenHandler, false);
        document.addEventListener('mozfullscreenchange', wobelFullscreenHandler, false);
        document.addEventListener('MSFullscreenChange', wobelFullscreenHandler, false);
        document.addEventListener('webkitfullscreenchange', wobelFullscreenHandler, false);
    }

    $(document).on("click", ".wobel-top-nav-duplicate-button", function () {
        let itemIds = $("input.wobel-check-item:visible:checkbox:checked").map(function () {
            if ($(this).attr('data-item-type') === 'variation') {
                swal({
                    title: "Duplicate for variations product is disabled!",
                    type: "warning"
                });
                return false;
            }
            return $(this).val();
        }).get();

        if (!itemIds.length) {
            swal({
                title: (WOBEL_DATA.strings && WOBEL_DATA.strings['please_select_one_item']) ? WOBEL_DATA.strings['please_select_one_item'] : "Please select one item",
                type: "warning"
            });
            return false;
        } else {
            wobelOpenModal('#wobel-modal-item-duplicate');
        }
    });

    // Select Items (Checkbox) in table
    $(document).on("change", ".wobel-check-item-main", function () {
        let checkbox_items = $(".wobel-check-item");
        if ($(this).prop("checked") === true) {
            checkbox_items.prop("checked", true);
            $("#wobel-items-list tr").addClass("wobel-tr-selected");
            checkbox_items.each(function () {
                $("#wobel-export-items-selected").append("<input type='hidden' name='item_ids[]' value='" + $(this).val() + "'>");
            });
            wobelShowSelectionTools();
            $("#wobel-export-only-selected-items").prop("disabled", false);
        } else {
            checkbox_items.prop("checked", false);
            $("#wobel-items-list tr").removeClass("wobel-tr-selected");
            $("#wobel-export-items-selected").html("");
            wobelHideSelectionTools();
            $("#wobel-export-only-selected-items").prop("disabled", true);
            $("#wobel-export-all-items-in-table").prop("checked", true);
        }
    });

    $(document).on("change", ".wobel-check-item", function () {
        if ($(this).prop("checked") === true) {
            $("#wobel-export-items-selected").append("<input type='hidden' name='item_ids[]' value='" + $(this).val() + "'>");
            if ($(".wobel-check-item:checked").length === $(".wobel-check-item").length) {
                $(".wobel-check-item-main").prop("checked", true);
            }
            $(this).closest("tr").addClass("wobel-tr-selected");
        } else {
            $("#wobel-export-items-selected").find("input[value=" + $(this).val() + "]").remove();
            $(this).closest("tr").removeClass("wobel-tr-selected");
            $(".wobel-check-item-main").prop("checked", false);
        }

        // Disable and enable "Only Selected items" in "Import/Export"
        if ($(".wobel-check-item:checkbox:checked").length > 0) {
            $("#wobel-export-only-selected-items").prop("disabled", false);
            wobelShowSelectionTools();
        } else {
            wobelHideSelectionTools();
            $("#wobel-export-only-selected-items").prop("disabled", true);
            $("#wobel-export-all-items-in-table").prop("checked", true);
        }
    });

    $(document).on("click", "#wobel-bulk-edit-unselect", function () {
        $("input.wobel-check-item").prop("checked", false);
        $("input.wobel-check-item-main").prop("checked", false);
        wobelHideSelectionTools();
    });

    // Start "Column Profile"
    $(document).on("change", "#wobel-column-profiles-choose", function () {
        let preset = $(this).val();
        $('.wobel-column-profiles-fields input[type="checkbox"]').prop('checked', false);
        $('#wobel-column-profile-select-all').prop('checked', false);
        $('.wobel-column-profile-select-all span').text('Select All');
        $("#wobel-column-profiles-apply").attr("data-preset-key",);
        if (defaultPresets && $.inArray(preset, defaultPresets) === -1) {
            $("#wobel-column-profiles-update-changes").show();
        } else {
            $("#wobel-column-profiles-update-changes").hide();
        }

        if (columnPresetsFields && columnPresetsFields[preset]) {
            columnPresetsFields[preset].forEach(function (val) {
                $('.wobel-column-profiles-fields input[type="checkbox"][value="' + val + '"]').prop('checked', true);
            });
        }
    });

    $(document).on("keyup", "#wobel-column-profile-search", function () {
        let wobelSearchFieldValue = $(this).val().toLowerCase().trim();
        $(".wobel-column-profile-fields ul li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wobelSearchFieldValue) > -1);
        });
    });

    $(document).on('change', '#wobel-column-profile-select-all', function () {
        if ($(this).prop('checked') === true) {
            $(this).closest('label').find('span').text('Unselect');
            $('.wobel-column-profile-fields input:checkbox:visible').prop('checked', true);
        } else {
            $(this).closest('label').find('span').text('Select All');
            $('.wobel-column-profile-fields input:checkbox').prop('checked', false);
        }
        $(".wobel-column-profile-save-dropdown").show();
    });
    // End "Column Profile"

    // Calculator for numeric TD
    $(document).on({
        mouseenter: function () {
            $(this)
                .children(".wobel-calculator")
                .show();
        },
        mouseleave: function () {
            $(this)
                .children(".wobel-calculator")
                .hide();
        }
    },
        "td[data-content-type=regular_price], td[data-content-type=sale_price], td[data-content-type=numeric]"
    );

    // delete items button
    $(document).on("click", ".wobel-bulk-edit-delete-item", function () {
        $(this).find(".wobel-bulk-edit-delete-item-buttons").slideToggle(200);
    });

    $(document).on("change", ".wobel-column-profile-fields input:checkbox", function () {
        $(".wobel-column-profile-save-dropdown").show();
    });

    $(document).on("click", ".wobel-column-profile-save-dropdown", function () {
        $(this).find(".wobel-column-profile-save-dropdown-buttons").slideToggle(200);
    });

    $('#wp-admin-bar-root-default').append('<li id="wp-admin-bar-wobel-col-view"></li>');

    $(document).on({
        mouseenter: function () {
            $('#wp-admin-bar-wobel-col-view').html('#' + $(this).attr('data-item-id') + ' | ' + $(this).attr('data-item-title') + ' [<span class="wobel-col-title">' + $(this).attr('data-col-title') + '</span>] ');
        },
        mouseleave: function () {
            $('#wp-admin-bar-wobel-col-view').html('');
        }
    },
        "#wobel-items-list td"
    );

    $(document).on("click", ".wobel-open-uploader", function (e) {
        let target = $(this).attr("data-target");
        let element = $(this).closest('div');
        let type = $(this).attr("data-type");
        let mediaUploader;
        let wobelNewImageElementID = $(this).attr("data-id");
        let wobelProductID = $(this).attr("data-item-id");
        e.preventDefault();
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        if (type === "single") {
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: "Choose Image",
                button: {
                    text: "Choose Image"
                },
                multiple: false
            });
        } else {
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: "Choose Images",
                button: {
                    text: "Choose Images"
                },
                multiple: true
            });
        }

        mediaUploader.on("select", function () {
            let attachment = mediaUploader.state().get("selection").toJSON();
            switch (target) {
                case "inline-file":
                    $("#url-" + wobelNewImageElementID).val(attachment[0].url);
                    break;
                case "inline-file-custom-field":
                    $("#wobel-file-url").val(attachment[0].url);
                    $('#wobel-file-id').val(attachment[0].id)
                    break;
                case "inline-edit":
                    $("#" + wobelNewImageElementID).val(attachment[0].url);
                    $("[data-image-preview-id=" + wobelNewImageElementID + "]").html("<img src='" + attachment[0].url + "' alt='' />");
                    $("#wobel-modal-image button[data-item-id=" + wobelProductID + "][data-button-type=save]").attr("data-image-id", attachment[0].id).attr("data-image-url", attachment[0].url);
                    break;
                case "variations-inline-edit":
                    $("#iwbve-variation-thumbnail-modal .iwbve-inline-image-preview").html("<img src='" + attachment[0].url + "' alt='' />");
                    $('#iwbve-variation-thumbnail-modal .iwbve-variations-table-thumbnail-inline-edit-button[data-button-type="save"]').attr("data-image-id", attachment[0].id).attr("data-image-url", attachment[0].url);
                    break;
                case "inline-edit-gallery":
                    attachment.forEach(function (item) {
                        $("#wobel-modal-gallery-items").append('<div class="wobel-inline-edit-gallery-item"><img src="' + item.url + '" alt=""><input type="hidden" class="wobel-inline-edit-gallery-image-ids" value="' + item.id + '"></div>');
                    });
                    break;
                case "bulk-edit-image":
                    element.find(".wobel-bulk-edit-form-item-image").val(attachment[0].id);
                    element.find(".wobel-bulk-edit-form-item-image-preview").html('<div><img src="' + attachment[0].url + '" width="43" height="43" alt=""><button type="button" class="wobel-bulk-edit-form-remove-image"><i class="wobel-icon-x"></i></button></div>');
                    break;
                case "variations-bulk-actions-image":
                    element.find(".iwbve-variations-bulk-actions-image").val(attachment[0].id);
                    element.find(".iwbve-variations-bulk-actions-image-preview").html('<div><img src="' + attachment[0].url + '" width="43" height="43" alt=""><button type="button" class="iwbve-variations-bulk-actions-remove-image"><i class="wobel-icon-x"></i></button></div>');
                    break;
                case "variations-bulk-actions-file":
                    element.find(".iwbve-variation-bulk-actions-file-item-url-input").val(attachment[0].url);
                    break;
                case "bulk-edit-file":
                    element.find(".wobel-bulk-edit-form-item-file").val(attachment[0].id);
                    break;
                case "bulk-edit-gallery":
                    attachment.forEach(function (item) {
                        $(".wobel-bulk-edit-form-item-gallery").append('<input type="hidden" value="' + item.id + '" data-field="value">');
                        $(".wobel-bulk-edit-form-item-gallery-preview").append('<div><img src="' + item.url + '" width="43" height="43" alt=""><button type="button" data-id="' + item.id + '" class="wobel-bulk-edit-form-remove-gallery-item"><i class="wobel-icon-x"></i></button></div>');
                    });
                    break;
            }
        });
        mediaUploader.open();
    });

    $(document).on("click", ".wobel-inline-edit-gallery-image-item-delete", function () {
        $(this).closest("div").remove();
    });

    $(document).on("change", ".wobel-column-manager-check-all-fields-btn input:checkbox", function () {
        if ($(this).prop("checked")) {
            $(this).closest("label").find("span").addClass("selected").text("Unselect");
            $(".wobel-column-manager-available-fields[data-action=" + $(this).closest("label").attr("data-action") + "] li:visible").each(function () {
                $(this).find("input:checkbox").prop("checked", true);
            });
        } else {
            $(this).closest("label").find("span").removeClass("selected").text("Select All");
            $(".wobel-column-manager-available-fields[data-action=" + $(this).closest("label").attr("data-action") + "] li:visible input:checked").prop("checked", false);
        }
    });

    $(document).on("click", ".wobel-column-manager-add-field", function () {
        let fieldName = [];
        let fieldLabel = [];
        let action = $(this).attr("data-action");
        let checked = $(".wobel-column-manager-available-fields[data-action=" + action + "] input[data-type=field]:checkbox:checked");
        if (checked.length > 0) {
            $('.wobel-column-manager-empty-text').hide();
            if (action === 'new') {
                $('.wobel-column-manager-added-fields-wrapper .wobel-box-loading').show();
            } else {
                $('#wobel-modal-column-manager-edit-preset .wobel-box-loading').show();
            }
            checked.each(function (i) {
                fieldName[i] = $(this).attr("data-name");
                fieldLabel[i] = $(this).val();
            });
            wobelColumnManagerAddField(fieldName, fieldLabel, action);
        }
    });

    $(".wobel-column-manager-delete-preset").on("click", function () {
        var $this = $(this);
        $("#wobel_column_manager_delete_preset_key").val($this.val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
            confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wobel-column-manager-delete-preset-form").submit();
            }
        });
    });

    $(document).on("keyup", ".wobel-column-manager-search-field", function () {
        let wobelSearchFieldValue = $(this).val().toLowerCase().trim();
        $(".wobel-column-manager-available-fields[data-action=" + $(this).attr("data-action") + "] ul li[data-added=false]").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wobelSearchFieldValue) > -1);
        });
    });

    $(document).on("click", ".wobel-column-manager-remove-field", function () {
        $(".wobel-column-manager-available-fields[data-action=" + $(this).attr("data-action") + "] li[data-name=" + $(this).attr("data-name") + "]").attr("data-added", "false").show();
        $(this).closest(".wobel-column-manager-right-item").remove();
        if ($('.wobel-column-manager-added-fields-wrapper .wobel-column-manager-right-item').length < 1) {
            $('.wobel-column-manager-empty-text').show();
        }
    });

    if ($.fn.sortable) {
        let wobelColumnManagerFields = $(".wobel-column-manager-added-fields .items");
        wobelColumnManagerFields.sortable({
            handle: ".wobel-column-manager-field-sortable-btn",
            cancel: ""
        });
        wobelColumnManagerFields.disableSelection();

        let wobelMetaFieldItems = $(".wobel-meta-fields-right");
        wobelMetaFieldItems.sortable({
            handle: ".wobel-meta-field-item-sortable-btn",
            cancel: ""
        });
        wobelMetaFieldItems.disableSelection();
    }

    $(document).on("click", "#wobel-add-meta-field-manual", function () {
        $(".wobel-meta-fields-empty-text").hide();
        let input = $("#wobel-meta-fields-manual_key_name");
        wobelAddMetaKeysManual(input.val());
        input.val("");
    });

    $(document).on("click", "#wobel-add-acf-meta-field", function () {
        let input = $("#wobel-add-meta-fields-acf");
        if (input.val()) {
            $(".wobel-meta-fields-empty-text").hide();
            wobelAddACFMetaField(input.val(), input.find('option:selected').text(), input.find('option:selected').attr('data-type'));
            input.val("").change();
        }
    });

    $(document).on("click", ".wobel-meta-field-remove", function () {
        $(this).closest(".wobel-meta-fields-right-item").remove();
        if ($(".wobel-meta-fields-right-item").length < 1) {
            $(".wobel-meta-fields-empty-text").show();
        }
    });

    $(document).on("click", ".wobel-history-delete-item", function () {
        $("#wobel-history-clicked-id").attr("name", "delete").val($(this).val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
            confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wobel-history-items").submit();
            }
        });
    });

    $(document).on("click", "#wobel-history-clear-all-btn", function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
            confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wobel-history-clear-all").submit();
            }
        });
    });

    $(document).on("click", ".wobel-history-revert-item", function () {
        $("#wobel-history-clicked-id").attr("name", "revert").val($(this).val());
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
            confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $("#wobel-history-items").submit();
            }
        });
    });

    $(document).on('click', '.wobel-modal', function (e) {
        if ($(e.target).hasClass('wobel-modal') || $(e.target).hasClass('wobel-modal-container') || $(e.target).hasClass('wobel-modal-box')) {
            wobelCloseModal();
        }
    });

    $(document).on("change", 'select[data-field="operator"]', function () {
        if ($(this).val() === "number_formula") {
            $(this).closest("div").find("input[type=number]").attr("type", "text");
        }
    });

    $(document).on('change', '#wobel-filter-form-content [data-field=value], #wobel-filter-form-content [data-field=from], #wobel-filter-form-content [data-field=to]', function () {
        wobelCheckFilterFormChanges();
    });

    $(document).on('change', 'input[type=number][data-field=to]', function () {
        let from = $(this).closest('.wobel-form-group').find('input[type=number][data-field=from]');
        if (parseFloat($(this).val()) < parseFloat(from.val())) {
            from.val('').addClass('wobel-input-danger').focus();
        }
    });

    $(document).on('change', 'input[type=number][data-field=from]', function () {
        let to = $(this).closest('.wobel-form-group').find('input[type=number][data-field=to]');
        if (parseFloat($(this).val()) > parseFloat(to.val())) {
            $(this).val('').addClass('wobel-input-danger');
        } else {
            $(this).removeClass('wobel-input-danger')
        }
    });

    $(document).on('change', '#wobel-switcher', function () {
        wobelLoadingStart();
        $('#wobel-switcher-form').submit();
    });

    $(document).on('click', 'span[data-target="#wobel-modal-image"]', function () {
        let tdElement = $(this).closest('td');
        let modal = $('#wobel-modal-image');
        let col_title = tdElement.attr('data-col-title');
        let id = $(this).attr('data-id');
        let image_id = $(this).attr('data-image-id');
        let item_id = tdElement.attr('data-item-id');
        let full_size_url = $(this).attr('data-full-image-src');
        let field = tdElement.attr('data-field');
        let field_type = tdElement.attr('data-field-type');

        $('#wobel-modal-image-item-title').text(col_title);
        modal.find('.wobel-open-uploader').attr('data-id', id).attr('data-item-id', item_id);
        modal.find('.wobel-inline-image-preview').attr('data-image-preview-id', id).html('<img src="' + full_size_url + '" />');
        modal.find('.wobel-image-preview-hidden-input').attr('id', id);
        modal.find('button[data-button-type="save"]').attr('data-item-id', item_id).attr('data-field', field).attr('data-image-url', full_size_url).attr('data-image-id', image_id).attr('data-field-type', field_type).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
        modal.find('button[data-button-type="remove"]').attr('data-item-id', item_id).attr('data-field', field).attr('data-field-type', field_type).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
    });

    $(document).on('click', 'button[data-target="#wobel-modal-file"]', function () {
        let modal = $('#wobel-modal-file');
        modal.find('#wobel-modal-select-file-item-title').text($(this).closest('td').attr('data-col-title'));
        modal.find('#wobel-modal-file-apply').attr('data-item-id', $(this).attr('data-item-id')).attr('data-field', $(this).attr('data-field')).attr('data-field-type', $(this).attr('data-field-type'));
        modal.find('#wobel-file-id').val($(this).attr('data-file-id'));
        modal.find('#wobel-file-url').val($(this).attr('data-file-url'));
    });

    $(document).on('click', '#wobel-modal-file-clear', function () {
        let modal = $('#wobel-modal-file');
        modal.find('#wobel-file-id').val(0).change();
        modal.find('#wobel-file-url').val('').change();
    });

    $(document).on('click', '.wobel-sub-tab-title', function () {
        $(this).closest('.wobel-sub-tab-titles').find('.wobel-sub-tab-title').removeClass('active');
        $(this).addClass('active');

        $(this).closest('div').find('.wobel-sub-tab-content').hide();
        $(this).closest('div').find('.wobel-sub-tab-content[data-content="' + $(this).attr('data-content') + '"]').show();
    });

    if ($('.wobel-sub-tab-titles').length > 0) {
        $('.wobel-sub-tab-titles').each(function () {
            $(this).find('.wobel-sub-tab-title').first().trigger('click');
        });
    }

    $(document).on("mouseenter", ".wobel-thumbnail", function () {
        let position = $(this).offset();
        let imageHeight = $(this).find('img').first().height();
        let top = ((position.top - imageHeight) > $('#wpadminbar').offset().top) ? position.top - imageHeight : position.top + 15;

        $('.wobel-thumbnail-hover-box').css({
            top: top,
            left: position.left - 100,
            display: 'block',
            height: imageHeight
        }).html($(this).find('.wobel-original-thumbnail').clone());
    });

    $(document).on("mouseleave", ".wobel-thumbnail", function () {
        $('.wobel-thumbnail-hover-box').hide();
    });

    setTimeout(function () {
        $('#wobel-column-profiles-choose').trigger('change');
    }, 500);

    $(document).on('click', '.wobel-filter-form-action', function () {
        wobelFilterFormClose();
    });

    $(document).on('click', '#wobel-license-renew-button', function () {
        $(this).closest('#wobel-license').find('.wobel-license-form').slideDown();
    });

    $(document).on('click', '#wobel-license-form-cancel', function () {
        $(this).closest('#wobel-license').find('.wobel-license-form').slideUp();
    });

    $(document).on('click', '#wobel-license-deactivate-button', function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
            confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                $('#wobel-license-deactivation-form').submit();
            }
        });
    });

    wobelSetTipsyTooltip();

    $(window).on('resize', function () {
        wobelDataTableFixSize();
    });

    $(document).on('click', 'body', function (e) {
        if (!$(e.target).hasClass('wobel-status-filter-button') && $(e.target).closest('.wobel-status-filter-button').length == 0) {
            $('.wobel-top-nav-status-filter').hide();
        }

        if (!$(e.target).hasClass('wobel-quick-filter') && $(e.target).closest('.wobel-quick-filter').length == 0) {
            $('.wobel-top-nav-filters').hide();
        }

        if (!$(e.target).hasClass('wobel-post-type-switcher') && $(e.target).closest('.wobel-post-type-switcher').length == 0) {
            $('.wobel-top-nav-filters-switcher').hide();
        }

        if (!$(e.target).hasClass('wobel-float-side-modal') &&
            !$(e.target).closest('.wobel-float-side-modal-box').length &&
            !$('.sweet-overlay:visible').length &&
            !$('.wobel-modal:visible').length &&
            $(e.target).attr('data-toggle') != 'float-side-modal' &&
            !$(e.target).closest('.select2-container').length &&
            !$(e.target).is('i') &&
            !$(e.target).closest('.media-modal').length &&
            !$(e.target).closest('.sweet-alert').length &&
            !$(e.target).closest('[data-toggle="float-side-modal"]').length &&
            !$(e.target).closest('[data-toggle="float-side-modal-after-confirm"]').length) {
            if ($('.wobel-float-side-modal:visible').length && $('.wobel-float-side-modal:visible').hasClass('wobel-float-side-modal-close-with-confirm')) {
                swal({
                    title: ($('.wobel-float-side-modal:visible').attr('data-confirm-message') && $('.wobel-float-side-modal:visible').attr('data-confirm-message') != '') ? $('.wobel-float-side-modal:visible').attr('data-confirm-message') : 'Are you sure?',
                    type: "warning",
                    showCancelButton: true,
                    cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
                    confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
                    confirmButtonText: iwbveTranslate.iAmSure,
                    closeOnConfirm: true
                }, function (isConfirm) {
                    if (isConfirm) {
                        $('.wobel-float-side-modal:visible').removeClass('wobel-float-side-modal-close-with-confirm');
                        wobelCloseFloatSideModal();
                    }
                });
            } else {
                wobelCloseFloatSideModal();
            }
        }
    });

    $(document).on('click', '.wobel-status-filter-button', function () {
        $(this).closest('.wobel-status-filter-container').find('.wobel-top-nav-status-filter').toggle();
    });

    $(document).on('click', '.wobel-quick-filter > a', function (e) {
        if (!$(e.target).closest('.wobel-top-nav-filters').length) {
            $('.wobel-top-nav-filters').slideToggle(150);
        }
    });
    $(document).on('click', '.wobel-post-type-switcher > a', function (e) {
        if (!$(e.target).closest('.wobel-top-nav-filters-switcher').length) {
            $('.wobel-top-nav-filters-switcher').slideToggle(150);
        }
    });

    $(document).on('click', '.wobel-bind-edit-switch', function () {
        if ($('#wobel-bind-edit').prop('checked') === true) {
            $('#wobel-bind-edit').prop('checked', false);
            $(this).removeClass('active');
        } else {
            $('#wobel-bind-edit').prop('checked', true);
            $(this).addClass('active');
        }
    });

    if ($('#wobel-bind-edit').prop('checked') === true) {
        $('.wobel-bind-edit-switch').addClass('active');
    } else {
        $('.wobel-bind-edit-switch').removeClass('active');
    }

    if ($('.wobel-flush-message').length) {
        setTimeout(function () {
            $('.wobel-flush-message').slideUp();
        }, 3000);
    }

    wobelDataTableFixSize();

    // Inline edit
    $(document).on("click", "td[data-action=inline-editable]", function (e) {
        if ($(e.target).attr("data-type") !== "edit-mode" && $(e.target).find("[data-type=edit-mode]").length === 0) {
            // Close All Inline Edit
            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });
            // Open Clicked Inline Edit
            switch ($(this).attr("data-content-type")) {
                case "text":
                case "select":
                case "password":
                case "url":
                case "email":
                    $(this).children("span").html("<textarea data-item-id='" + $(this).attr("data-item-id") + "' data-field='" + $(this).attr("data-field") + "' data-field-type='" + $(this).attr("data-field-type") + "' data-type='edit-mode' data-val='" + $(this).text().trim() + "'>" + $(this).text().trim() + "</textarea>").children("textarea").focus().select();
                    break;
                case "numeric":
                case "regular_price":
                case "sale_price":
                    $(this).children("span").html("<input type='number' min='-1' data-item-id='" + $(this).attr("data-item-id") + "' data-field='" + $(this).attr("data-field") + "' data-field-type='" + $(this).attr("data-field-type") + "' data-type='edit-mode' data-val='" + $(this).text().trim() + "' value='" + $(this).text().trim() + "'>").children("input[type=number]").focus().select();
                    break;
            }
        }
    });

    // Discard Save
    $(document).on("click", function (e) {
        if ($(e.target).attr("data-action") !== "inline-editable" && $(e.target).attr("data-type") !== "edit-mode") {
            $("[data-type=edit-mode]").each(function () {
                $(this).closest("span").html($(this).attr("data-val"));
            });
        }
    });

    // Save Inline Edit By Enter Key
    $(document).on("keypress", "[data-type=edit-mode]", function (event) {
        let wobelKeyCode = event.keyCode ? event.keyCode : event.which;
        if (wobelKeyCode === 13) {
            let orderData = [];
            let orderIds = [];
            let tdElement = $(this).closest('td');

            if ($('#wobel-bind-edit').prop('checked') === true) {
                orderIds = wobelGetOrdersChecked();
            }
            orderIds.push($(this).attr("data-item-id"));

            orderData.push({
                name: tdElement.attr('data-name'),
                sub_name: (tdElement.attr('data-sub-name')) ? tdElement.attr('data-sub-name') : '',
                type: tdElement.attr('data-update-type'),
                value: $(this).val(),
                operation: 'inline_edit'
            });

            $(this).closest("span").html($(this).val());
            wobelOrderEdit(orderIds, orderData);
        }
    });

    // fetch order data by click to bulk edit button
    $(document).on("click", "#wobel-bulk-edit-bulk-edit-btn", function () {
        if ($(this).attr("data-fetch-order") === "yes") {
            let orderID = $("input.wobel-check-item:checkbox:checked");
            if (orderID.length === 1) {
                wobelGetOrderData(orderID.val());
            } else {
                wobelResetBulkEditForm();
            }
        }
    });

    $(document).on('click', '.wobel-inline-edit-color-action', function () {
        $(this).closest('td').find('input.wobel-inline-edit-action').trigger('change');
    });

    $(document).on("change", ".wobel-inline-edit-action", function (e) {
        let $this = $(this);
        setTimeout(function () {
            if ($('div.xdsoft_datetimepicker:visible').length > 0) {
                e.preventDefault();
                return false;
            }

            if ($this.hasClass('wobel-datepicker') || $this.hasClass('wobel-timepicker') || $this.hasClass('wobel-datetimepicker')) {
                if ($this.attr('data-val') == $this.val()) {
                    e.preventDefault();
                    return false;
                }
            }

            let orderData = [];
            let orderIds = [];
            let tdElement = $this.closest('td');
            if ($('#wobel-bind-edit').prop('checked') === true) {
                orderIds = wobelGetOrdersChecked();
            }
            orderIds.push($this.attr("data-item-id"));
            let wobelValue;
            switch (tdElement.attr("data-content-type")) {
                case 'checkbox_dual_mode':
                    wobelValue = $this.prop("checked") ? "yes" : "no";
                    break;
                case 'checkbox':
                    let checked = [];
                    tdElement.find('input[type=checkbox]:checked').each(function () {
                        checked.push($(this).val());
                    });
                    wobelValue = checked;
                    break;
                default:
                    wobelValue = $this.val();
                    break;
            }

            orderData.push({
                name: tdElement.attr('data-name'),
                sub_name: (tdElement.attr('data-sub-name')) ? tdElement.attr('data-sub-name') : '',
                type: tdElement.attr('data-update-type'),
                value: wobelValue,
                operation: 'inline_edit'
            });

            wobelOrderEdit(orderIds, orderData);
        }, 250)
    });

    $(document).on("click", ".wobel-inline-edit-clear-date", function () {
        let orderData = [];
        let orderIds = [];
        let tdElement = $(this).closest('td');

        if ($('#wobel-bind-edit').prop('checked') === true) {
            orderIds = wobelGetOrdersChecked();
        }
        orderIds.push($(this).attr("data-item-id"));
        orderData.push({
            name: tdElement.attr('data-name'),
            sub_name: (tdElement.attr('data-sub-name')) ? tdElement.attr('data-sub-name') : '',
            type: tdElement.attr('data-update-type'),
            value: '',
            operation: 'inline_edit'
        });

        wobelOrderEdit(orderIds, orderData);
    });

    $(document).on("click", ".wobel-edit-action-price-calculator", function () {
        let orderId = $(this).attr("data-item-id");
        let fieldName = $(this).attr("data-field");
        let orderIds = [];
        let orderData = [];

        if ($('#wobel-bind-edit').prop('checked') === true) {
            orderIds = wobelGetOrdersChecked();
        }
        orderIds.push(orderId);
        orderData.push({
            name: fieldName,
            sub_name: '',
            type: $(this).attr('data-update-type'),
            operator: $("#wobel-" + fieldName + "-calculator-operator-" + orderId).val(),
            value: $("#wobel-" + fieldName + "-calculator-value-" + orderId).val(),
            operator_type: $("#wobel-" + fieldName + "-calculator-type-" + orderId).val(),
            round: $("#wobel-" + fieldName + "-calculator-round-" + orderId).val()
        });

        wobelOrderEdit(orderIds, orderData);
    });

    $(document).on("click", ".wobel-bulk-edit-delete-action", function () {
        let deleteType = $(this).attr('data-delete-type');
        let OrderIds = wobelGetOrdersChecked();

        if (!OrderIds.length && deleteType != 'all') {
            swal({
                title: "Please select one order",
                type: "warning"
            });
            return false;
        }

        let alertMessage = "Are you sure?";

        if (deleteType == 'all') {
            alertMessage = ($('.wobel-reset-filter-form:visible').length) ? "All of filtered orders will be delete. Are you sure?" : "All of orders will be delete. Are you sure?";
        }

        swal({
            title: alertMessage,
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
            confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                if (OrderIds.length > 0 || deleteType == 'all') {
                    wobelDeleteOrder(OrderIds, deleteType);
                } else {
                    swal({
                        title: "Please Select Order !",
                        type: "warning"
                    });
                }
            }
        });
    });

    $(document).on("click", "#wobel-bulk-edit-duplicate-start", function () {
        let orderIDs = $("input.wobel-check-item:checkbox:checked").map(function () {
            if ($(this).attr('data-item-type') === 'variation') {
                swal({
                    title: "Duplicate for variations order is disabled!",
                    type: "warning"
                });
                return false;
            }
            return $(this).val();
        }).get();
        wobelDuplicateOrder(orderIDs, parseInt($("#wobel-bulk-edit-duplicate-number").val()));
    });

    $(document).on("click", "#wobel-create-new-item", function () {
        let count = $("#wobel-new-item-count").val();
        wobelCreateNewOrder(count);
    });

    $(document).on("click", "#wobel-column-profiles-save-as-new-preset", function () {
        let presetKey = $("#wobel-column-profiles-choose").val();
        let items = $(".wobel-column-profile-fields input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();
        wobelSaveColumnProfile(presetKey, items, "save_as_new");
    });

    $(document).on("click", "#wobel-column-profiles-update-changes", function () {
        let presetKey = $("#wobel-column-profiles-choose").val();
        let items = $(".wobel-column-profile-fields input:checkbox:checked").map(function () {
            return $(this).val();
        }).get();
        wobelSaveColumnProfile(presetKey, items, "update_changes");
    });

    $(document).on("click", ".wobel-bulk-edit-filter-profile-load", function () {
        wobelLoadFilterProfile($(this).val());
        if ($(this).val() !== "default") {
            $("#wobel-bulk-edit-reset-filter").show();
        }
        $(".wobel-filter-profiles-items tr").removeClass("wobel-filter-profile-loaded");
        $(this).closest("tr").addClass("wobel-filter-profile-loaded");

        if (WOBEL_DATA.wobel_settings.close_popup_after_applying == 'yes') {
            wobelCloseFloatSideModal();
        }
    });

    $(document).on("click", ".wobel-bulk-edit-filter-profile-delete", function () {
        let presetKey = $(this).val();
        let item = $(this).closest("tr");
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
            confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wobelDeleteFilterProfile(presetKey);
                if (item.hasClass('wobel-filter-profile-loaded')) {
                    $('.wobel-filter-profiles-items tbody tr:first-child').addClass('wobel-filter-profile-loaded').find('input[type=radio]').prop('checked', true);
                    $('#wobel-bulk-edit-reset-filter').trigger('click');
                }
                item.remove();
            }
        });
    });

    $(document).on("change", "input.wobel-filter-profile-use-always-item", function () {
        if ($(this).val() !== "default") {
            $("#wobel-bulk-edit-reset-filter").show();
        } else {
            $("#wobel-bulk-edit-reset-filter").hide();
        }
        wobelFilterProfileChangeUseAlways($(this).val());
    });

    $(document).on("click", ".wobel-filter-form-action", function (e) {
        let data = wobelGetCurrentFilterData();
        let page;
        let action = $(this).attr("data-search-action");
        if (action === "pagination") {
            page = $(this).attr("data-index");
        }
        if (action === "quick_search" && $('#wobel-quick-search-text').val() !== '') {
            wobelResetFilterForm();
        }
        if (action === "pro_search") {
            $('#wobel-bulk-edit-reset-filter').show();
            wobelResetQuickSearchForm();
            $(".wobel-filter-profiles-items tr").removeClass("wobel-filter-profile-loaded");
            $('input.wobel-filter-profile-use-always-item[value="default"]').prop("checked", true).closest("tr");
            wobelFilterProfileChangeUseAlways("default");
        }
        wobelOrdersFilter(data, action, null, page);

        if (WOBEL_DATA.wobel_settings.close_popup_after_applying == 'yes') {
            wobelCloseFloatSideModal();
        }

        wobelCheckResetFilterButton();
    });

    $(document).on('click', '.wobel-reset-filter-form', function () {
        wobelResetFilters();
    });

    $(document).on("click", "#wobel-filter-form-reset", function () {
        wobelResetFilters();
    });

    $(document).on("click", "#wobel-bulk-edit-reset-filter", function () {
        wobelResetFilters();
    });

    $(document).on('click', '.wobel-reload-table', function () {
        wobelReloadOrders();
    });

    $(document).on('change', '#wobel-filter-form-order-status', function () {
        if ($.inArray('trash', $(this).val()) !== -1) {
            $('.wobel-trash-options').closest('li').show();
        } else {
            $('.wobel-trash-options').closest('li').hide();
        }
    });

    $(document).on("change", "#wobel-quick-search-field", function () {
        let options = $("#wobel-quick-search-operator option");
        switch ($(this).val()) {
            case "title":
                options.each(function () {
                    $(this).closest("select").prop("selectedIndex", 0);
                    $(this).prop("disabled", false);
                });
                break;
            case "id":
                options.each(function () {
                    $(this).closest("select").prop("selectedIndex", 1);
                    if ($(this).attr("value") === "exact") {
                        $(this).prop("disabled", false);
                    } else {
                        $(this).prop("disabled", true);
                    }
                });
                break;
        }
    });

    // Quick Per Page
    $("#wobel-quick-per-page").on("change", function () {
        wobelChangeCountPerPage($(this).val());
    });

    $(document).on("click", ".wobel-edit-action-with-button", function () {
        let orderIds = [];
        let orderData = [];

        if ($('#wobel-bind-edit').prop('checked') === true) {
            orderIds = wobelGetOrdersChecked();
        }
        orderIds.push($(this).attr("data-item-id"));

        let wobelValue;
        switch ($(this).attr("data-content-type")) {
            case "textarea":
                wobelValue = tinymce.get("wobel-text-editor").getContent();
                break;
            case "select_orders":
                wobelValue = $('#wobel-select-orders-value').val();
                break;
            case "select_files":
                let names = $('.wobel-inline-edit-file-name').map(function () {
                    return $(this).val();
                }).get();

                let urls = $('.wobel-inline-edit-file-url').map(function () {
                    return $(this).val();
                }).get();

                wobelValue = {
                    files_name: names,
                    files_url: urls,
                };
                break;
            case "file":
                wobelValue = $('#wobel-modal-file #wobel-file-id').val();
                break;
            case "image":
                wobelValue = $(this).attr("data-image-id");
                break;
            case "gallery":
                wobelValue = $("#wobel-modal-gallery-items input.wobel-inline-edit-gallery-image-ids").map(function () {
                    return $(this).val();
                }).get();
                break;
        }

        orderData.push({
            name: $(this).attr('data-name'),
            sub_name: ($(this).attr('data-sub-name')) ? $(this).attr('data-sub-name') : '',
            type: $(this).attr('data-update-type'),
            value: wobelValue,
            operation: 'inline_edit'
        });

        wobelOrderEdit(orderIds, orderData);
    });

    $(document).on("click", ".wobel-load-text-editor", function () {
        let orderId = $(this).attr("data-item-id");
        let field = $(this).attr("data-field");
        let fieldType = $(this).attr("data-field-type");
        $('#wobel-modal-text-editor-item-title').text($(this).attr('data-item-name'));
        $("#wobel-text-editor-apply").attr("data-field", field).attr("data-field-type", fieldType).attr("data-item-id", orderId);
        $.ajax({
            url: WOBEL_DATA.ajax_url,
            type: "post",
            dataType: "json",
            data: {
                action: "wobel_get_text_editor_content",
                order_id: orderId,
                field: field,
                field_type: fieldType
            },
            success: function (response) {
                if (response.success) {
                    tinymce.get("wobel-text-editor").setContent(response.content);
                    tinymce.execCommand('mceFocus', false, 'wobel-text-editor');
                }
            },
            error: function () { }
        });
    });

    //Search
    $(document).on("keyup", ".wobel-search-in-list", function () {
        let wobelSearchValue = this.value.toLowerCase().trim();
        $($(this).attr("data-id") + " .wobel-order-items-list li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(wobelSearchValue) > -1);
        });
    });

    $(document).on('click', 'button[data-target="#wobel-modal-select-orders"]', function () {
        let childrenIds = $(this).attr('data-children-ids').split(',');
        $('#wobel-modal-select-orders-item-title').text($(this).attr('data-item-name'));
        $('#wobel-modal-select-orders .wobel-edit-action-with-button').attr('data-item-id', $(this).attr('data-item-id')).attr('data-field', $(this).attr('data-field')).attr('data-field-type', $(this).attr('data-field-type'));
        let orders = $('#wobel-select-orders-value');
        if (orders.length > 0) {
            orders.val(childrenIds).change();
        }
    });

    $(document).on('click', '#wobel-modal-select-files-add-file-item', function () {
        wobelAddNewFileItem();
    });

    $(document).on('click', 'button[data-toggle=modal][data-target="#wobel-modal-select-files"]', function () {
        $('#wobel-modal-select-files-apply').attr('data-item-id', $(this).attr('data-item-id')).attr('data-field', $(this).attr(('data-field')));
        $('#wobel-modal-select-files-item-title').text($(this).closest('td').attr('data-col-title'));
        wobelGetOrderFiles($(this).attr('data-item-id'));
    });

    $(document).on('click', '.wobel-inline-edit-file-remove-item', function () {
        $(this).closest('.wobel-modal-select-files-file-item').remove();
    });

    if ($.fn.sortable) {
        let wobelSelectFiles = $(".wobel-inline-select-files");
        wobelSelectFiles.sortable({
            handle: ".wobel-select-files-sortable-btn",
            cancel: ""
        });
        wobelSelectFiles.disableSelection();
    }

    $(document).on("change", ".wobel-bulk-edit-form-variable", function () {
        let newVal = $(this).val() ? $(this).closest("div").find("input[type=text]").val() + "{" + $(this).val() + "}" : "";
        $(this).closest("div").find("input[type=text]").val(newVal).change();
    });

    $(document).on("change", "select[data-field=operator]", function () {
        let id = $(this).closest(".wobel-form-group").find("label").attr("for");
        if ($(this).val() === "text_replace") {
            $(this).closest(".wobel-form-group").append('<div class="wobel-bulk-edit-form-extra-field"><select id="' + id + '-sensitive" data-field="sensitive"><option value="yes">Same Case</option><option value="no">Ignore Case</option></select><input type="text" id="' + id + '-replace" data-field="replace" placeholder="Text ..."><select class="wobel-bulk-edit-form-variable" title="Select Variable" data-field="variable"><option value="">Variable</option><option value="title">Title</option><option value="id">ID</option><option value="sku">SKU</option><option value="menu_order">Menu Order</option><option value="parent_id">Parent ID</option><option value="parent_title">Parent Title</option><option value="parent_sku">Parent SKU</option><option value="regular_price">Regular Price</option><option value="sale_price">Sale Price</option></select></div>');
        } else if ($(this).val() === "number_round") {
            $(this).closest(".wobel-form-group").append('<div class="wobel-bulk-edit-form-extra-field"><select id="' + id + '-round-item"><option value="5">5</option><option value="10">10</option><option value="19">19</option><option value="29">29</option><option value="39">39</option><option value="49">49</option><option value="59">59</option><option value="69">69</option><option value="79">79</option><option value="89">89</option><option value="99">99</option></select></div>');
        } else {
            $(this).closest(".wobel-form-group").find(".wobel-bulk-edit-form-extra-field").remove();
        }
        if ($(this).val() === "number_clear") {
            $(this).closest(".wobel-form-group").find('input[data-field=value]').prop('disabled', true);
        } else {
            $(this).closest(".wobel-form-group").find('input[data-field=value]').prop('disabled', false);
        }
        changedTabs($(this));
    });

    $("#wobel-float-side-modal-bulk-edit .wobel-tab-content-item").on("change", "[data-field=value]", function () {
        changedTabs($(this));
    });

    $(document).on("change", ".wobel-date-from", function () {
        let field_to = $('#' + $(this).attr('data-to-id'));
        let datepicker = true;
        let timepicker = false;
        let format = 'Y/m/d';

        if ($(this).hasClass('wobel-datetimepicker')) {
            timepicker = true;
            format = 'Y/m/d H:i'
        }

        if ($(this).hasClass('wobel-timepicker')) {
            datepicker = false;
            timepicker = true;
            format = 'H:i'
        }

        field_to.val("");
        field_to.datetimepicker("destroy");
        field_to.datetimepicker({
            format: format,
            datepicker: datepicker,
            timepicker: timepicker,
            minDate: $(this).val(),
        });
    });

    $(document).on("click", ".wobel-bulk-edit-form-remove-image", function () {
        $(this).closest("div").remove();
        $("#wobel-bulk-edit-form-order-image").val("");
    });

    $(document).on("click", ".wobel-bulk-edit-form-remove-gallery-item", function () {
        $(this).closest("div").remove();
        $("#wobel-bulk-edit-form-order-gallery input[value=" + $(this).attr("data-id") + "]").remove();
    });

    var sortType = 'DESC'
    $(document).on('click', '.wobel-sortable-column', function () {
        if (sortType === 'DESC') {
            sortType = 'ASC';
            $(this).find('i.wobel-sortable-column-icon').text('d');
        } else {
            sortType = 'DESC';
            $(this).find('i.wobel-sortable-column-icon').text('u');
        }
        wobelSortByColumn($(this).attr('data-column-name'), sortType);
    });

    $(document).on("click", ".wobel-column-manager-edit-field-btn", function () {
        $('#wobel-modal-column-manager-edit-preset .wobel-box-loading').show();
        let presetKey = $(this).val();
        $('#wobel-modal-column-manager-edit-preset .items').html('');
        $("#wobel-column-manager-edit-preset-key").val(presetKey);
        $("#wobel-column-manager-edit-preset-name").val($(this).attr("data-preset-name"));
        wobelColumnManagerFieldsGetForEdit(presetKey);
    });

    $(document).on("click", "#wobel-get-meta-fields-by-order-id", function () {
        $(".wobel-meta-fields-empty-text").hide();
        let input = $("#wobel-add-meta-fields-order-id");
        wobelAddMetaKeysByOrderID(input.val());
        input.val("");
    });

    $(document).on("click", "#wobel-bulk-edit-undo", function () {
        wobelHistoryUndo();
    });

    $(document).on("click", "#wobel-bulk-edit-redo", function () {
        wobelHistoryRedo();
    });

    $(document).on("click", "#wobel-history-filter-apply", function () {
        let filters = {
            operation: $("#wobel-history-filter-operation").val(),
            author: $("#wobel-history-filter-author").val(),
            fields: $("#wobel-history-filter-fields").val(),
            date: {
                from: $("#wobel-history-filter-date-from").val(),
                to: $("#wobel-history-filter-date-to").val()
            }
        };
        wobelHistoryFilter(filters);
    });

    $(document).on("click", "#wobel-history-filter-reset", function () {
        $(".wobel-history-filter-fields input").val("");
        $(".wobel-history-filter-fields select").val("").change();
        wobelHistoryFilter();
    });

    $(document).on("change", ".wobel-meta-fields-main-type", function () {
        let item = $(this).closest('.wobel-meta-fields-right-item');
        if ($(this).val() === "textinput") {
            item.find(".wobel-meta-fields-sub-type").show();
        } else {
            item.find(".wobel-meta-fields-sub-type").hide();
        }

        if ($.inArray($(this).val(), ['select', 'array']) !== -1) {
            item.find(".wobel-meta-fields-key-value").show();
        } else {
            item.find(".wobel-meta-fields-key-value").hide();
        }
    });

    $("#wobel-column-manager-add-new-preset").on("submit", function (e) {
        if ($(this).find(".wobel-column-manager-added-fields .items .wobel-column-manager-right-item").length < 1) {
            e.preventDefault();
            swal({
                title: "Please Add Columns !",
                type: "warning"
            });
        }
    });

    $(document).on("click", "#wobel-bulk-edit-form-reset", function () {
        wobelResetBulkEditForm();
        $("nav.wobel-tabs-navbar li a").removeClass("wobel-tab-changed");
    });

    $(document).on("click", "#wobel-filter-form-save-preset", function () {
        let presetName = $("#wobel-filter-form-save-preset-name").val();
        if (presetName !== "") {
            let data = wobelGetProSearchData();
            wobelSaveFilterPreset(data, presetName);
        } else {
            swal({
                title: "Preset name is required !",
                type: "warning"
            });
        }
    });

    $(document).on("click", "#wobel-bulk-edit-form-do-bulk-edit", function (e) {
        let orderIds = wobelGetOrdersChecked();
        let orderData = [];

        $("#wobel-float-side-modal-bulk-edit .wobel-form-group").each(function () {
            let value;
            if ($(this).find("[data-field=value]").length > 1) {
                value = $(this).find("[data-field=value]").map(function () {
                    if ($(this).val() !== '') {
                        return $(this).val();
                    }
                }).get();
            } else {
                value = $(this).find("[data-field=value]").val();
            }

            if (($.isArray(value) && value.length > 0) || (!$.isArray(value) && value)) {
                let name = $(this).attr('data-name');
                let type = $(this).attr('data-type');

                orderData.push({
                    name: name,
                    sub_name: ($(this).attr('data-sub-name')) ? $(this).attr('data-sub-name') : '',
                    type: type,
                    operator: $(this).find("[data-field=operator]").val(),
                    value: value,
                    replace: $(this).find("[data-field=replace]").val(),
                    sensitive: $(this).find("[data-field=sensitive]").val(),
                    round: $(this).find("[data-field=round]").val(),
                    operation: 'bulk_edit'
                });
            }
        });

        if (orderIds.length > 0) {
            if (WOBEL_DATA.wobel_settings.close_popup_after_applying == 'yes') {
                wobelCloseFloatSideModal();
            }

            wobelOrderEdit(orderIds, orderData);
            if (WOBEL_DATA.wobel_settings.keep_filled_data_in_bulk_edit_form == 'yes') {
                wobelResetBulkEditForm();
            }
        } else {
            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
                confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
                confirmButtonText: "Yes, i'm sure",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    if (WOBEL_DATA.wobel_settings.close_popup_after_applying == 'yes') {
                        wobelCloseFloatSideModal();
                    }
                    wobelOrderEdit(orderIds, orderData);
                }
            });
        }
    });

    $(document).on('click', '[data-target="#wobel-modal-new-item"]', function () {
        $('#wobel-new-item-title').html("New Order");
        $('#wobel-new-item-description').html("Enter how many new order(s) to create!");
    });

    // keypress: Enter
    $(document).on("keypress", function (e) {
        if (e.keyCode === 13) {
            if ($("#wobel-float-side-modal-filter").attr("data-visibility") === "visible") {
                wobelReloadOrders();
                $("#wobel-bulk-edit-reset-filter").show();
                wobelFilterFormClose();
            }
            if ($('#wobel-quick-search-text').val() !== '' && $($('#wobel-last-modal-opened').val()).css('display') !== 'block' && $('.wobel-tabs-list a[data-content=bulk-edit]').hasClass('selected')) {
                wobelReloadOrders();
                $('#wobel-quick-search-reset').show();
            }
            if ($("#wobel-modal-new-order-taxonomy").css("display") === "block") {
                $("#wobel-create-new-order-taxonomy").trigger("click");
            }
            if ($("#wobel-modal-new-item").css("display") === "block") {
                $("#wobel-create-new-item").trigger("click");
            }
            if ($("#wobel-modal-item-duplicate").css("display") === "block") {
                $("#wobel-bulk-edit-duplicate-start").trigger("click");
            }

            let metaFieldManualInput = $("#wobel-meta-fields-manual_key_name");
            let metaFieldOrderId = $("#wobel-add-meta-fields-order-id");
            if (metaFieldManualInput.val() !== "") {
                $(".wobel-meta-fields-empty-text").hide();
                wobelAddMetaKeysManual(metaFieldManualInput.val());
                metaFieldManualInput.val("");
            }
            if (metaFieldOrderId.val() !== "") {
                $(".wobel-meta-fields-empty-text").hide();
                wobelAddMetaKeysByOrderID(metaFieldOrderId.val());
                metaFieldOrderId.val("");
            }

            // filter form
            if ($('#wobel-float-side-modal-filter:visible').length) {
                $('#wobel-float-side-modal-filter:visible').find('.wobel-filter-form-action').trigger('click');
            }
        }
    });

    let query;
    $(".wobel-get-orders-ajax").select2({
        ajax: {
            type: "post",
            delay: 800,
            url: WOBEL_DATA.ajax_url,
            dataType: "json",
            data: function (params) {
                query = {
                    action: "wobel_get_orders_name",
                    search: params.term
                };
                return query;
            }
        },
        placeholder: "Order Name ...",
        minimumInputLength: 3
    });

    $(document).on("change", "input:radio[name=create_variation_mode]", function () {
        if ($(this).attr("data-mode") === "all_combination") {
            $("#wobel-variation-bulk-edit-individual").hide();
            $("#wobel-variation-bulk-edit-generate").show();
        } else {
            $("#wobel-variation-bulk-edit-generate").hide();
            $("#wobel-variation-bulk-edit-individual").show();
        }
    }
    );

    $(document).on("select2:select", ".wobel-select2-ajax", function (e) {
        if ($(".wobel-variation-bulk-edit-individual-items div[data-id=" + $(this).attr("id") + "]").length === 0) {
            $(".wobel-variation-bulk-edit-individual-items").append('<div data-id="' + $(this).attr("id") + '"><select class="wobel-variation-bulk-edit-manual-item" data-attribute-name="' + $(this).attr("data-attribute-name") + '"></select></div>');
        }
        $(".wobel-variation-bulk-edit-individual-items div[data-id=" + $(this).attr("id") + "]").find("select").append('<option value="' + e.params.data.id + '">' + e.params.data.id + "</option>");
        $("#wobel-variation-bulk-edit-manual-add").prop("disabled", false);
        $("#wobel-variation-bulk-edit-generate").prop("disabled", false);
    });

    $(document).on("select2:unselect", ".wobel-select2-ajax", function (e) {
        $(".wobel-variation-bulk-edit-individual-items div[data-id=" + $(this).attr("id") + "]").find("option[value=" + e.params.data.id + "]").remove();
        if ($(".wobel-variation-bulk-edit-attribute-item").find(".select2-selection__choice").length === 0) {
            $("#wobel-variation-bulk-edit-manual-add").attr("disabled", "disabled");
            $("#wobel-variation-bulk-edit-generate").attr("disabled", "disabled");
        }
        if ($(this).val() === null) {
            $("div[data-id=wobel-variation-bulk-edit-attribute-item-" + $(this).attr("data-attribute-name") + "]").remove();
        }


    });

    $(document).on("change", "input:radio[name=delete_variation_mode]", function () {
        if ($(this).attr("data-mode") === "delete_all") {
            $("#wobel-variation-delete-single-delete").hide();
            $("#wobel-variation-delete-delete-all").show();
        } else {
            $("#wobel-variation-delete-delete-all").hide();
            $("#wobel-variation-delete-single-delete").show();
        }
    });

    $(document).on("click", ".wobel-inline-edit-add-new-taxonomy", function () {
        $("#wobel-create-new-order-taxonomy").attr("data-field", $(this).attr("data-field")).attr("data-item-id", $(this).attr("data-item-id")).attr('data-closest-id', $(this).attr('data-closest-id'));
        $('#wobel-modal-new-order-taxonomy-order-title').text($(this).attr('data-item-name'));
        wobelGetTaxonomyParentSelectBox($(this).attr("data-field"));
    });

    $(document).on("click", 'button.wobel-calculator[data-target="#wobel-modal-numeric-calculator"]', function () {
        let btn = $("#wobel-modal-numeric-calculator .wobel-edit-action-numeric-calculator");
        let tdElement = $(this).closest('td');
        btn.attr("data-name", tdElement.attr("data-name")).attr('data-update-type', tdElement.attr('data-update-type')).attr("data-item-id", $(this).attr("data-item-id")).attr("data-field", $(this).attr("data-field")).attr("data-field-type", $(this).attr("data-field-type"));
        $('#wobel-modal-numeric-calculator #wobel-numeric-calculator-type').show();
        $('#wobel-modal-numeric-calculator #wobel-numeric-calculator-round').show();
        $('#wobel-modal-numeric-calculator-item-title').text($(this).attr('data-item-name'));
    });

    $(document).on("click", ".wobel-edit-action-numeric-calculator", function () {
        let orderId = $(this).attr("data-item-id");
        let orderIds = [];
        let orderData = [];

        if ($('#wobel-bind-edit').prop('checked') === true) {
            orderIds = wobelGetOrdersChecked();
        }
        orderIds.push(orderId);

        orderData.push({
            name: $(this).attr("data-name"),
            sub_name: ($(this).attr("data-name")) ? $(this).attr("data-name") : '',
            type: $(this).attr('data-update-type'),
            operator: $("#wobel-numeric-calculator-operator").val(),
            value: $("#wobel-numeric-calculator-value").val(),
            operator_type: ($("#wobel-numeric-calculator-type").val()) ? $("#wobel-numeric-calculator-type").val() : 'n',
            round: $("#wobel-numeric-calculator-round").val()
        });

        wobelOrderEdit(orderIds, orderData);
    });

    $(document).on('keyup', 'input[type=number][data-field=download_limit], input[type=number][data-field=download_expiry]', function () {
        if ($(this).val() < -1) {
            $(this).val(-1);
        }
    });

    $(document).on('click', '#wobel-quick-search-button', function () {
        if ($('#wobel-quick-search-text').val() !== '') {
            $('#wobel-quick-search-reset').show();
        }
    });

    $(document).on('click', '#wobel-quick-search-reset', function () {
        wobelResetFilters()
    });

    $(document).on('click', '.wobel-order-details-button', function () {
        $('#wobel-modal-order-details-item-title').text($(this).attr('data-item-name'));
        wobelGetOrderDetails($(this).attr('data-item-id'));
    });

    $(document).on('click', '.wobel-order-notes-button', function () {
        let tdElement = $(this).closest('td');
        $('#wobel-modal-order-notes-item-title').text($(this).attr('data-item-name'));
        $('#wobel-modal-order-notes-add').attr('data-order-id', $(this).attr('data-item-id')).attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
        $('#wobel-modal-order-notes-items').html('');
        wobelGetOrderNotes($(this).attr('data-item-id'));
    });

    $(document).on('click', '.wobel-order-billing-button', function () {
        wobelClearInputs($('#wobel-modal-order-billing'));
        let orderId = $(this).attr('data-item-id');
        $('#wobel-modal-order-billing-item-title').text($(this).attr('data-item-name'));
        $('.wobel-modal-load-billing-address').attr('data-customer-id', $(this).attr('data-customer-id'));
        $('.wobel-modal-order-billing-save-changes-button').attr('data-order-id', orderId);
        wobelGetOrderBilling(orderId);
    });

    $(document).on('click', '.wobel-order-shipping-button', function () {
        wobelClearInputs($('#wobel-modal-order-shipping'));
        let orderId = $(this).attr('data-item-id');
        $('#wobel-modal-order-shipping-item-title').text($(this).attr('data-item-name'));
        $('.wobel-modal-load-billing-address').attr('data-customer-id', $(this).attr('data-customer-id'));
        $('.wobel-modal-load-shipping-address').attr('data-customer-id', $(this).attr('data-customer-id'));
        $('.wobel-modal-order-shipping-save-changes-button').attr('data-order-id', orderId);
        wobelGetOrderShipping(orderId);
    });

    $(document).on('change', '.wobel-order-country', function () {
        if (wobelShippingStates) {
            let selectElement = $('select' + $(this).attr('data-state-target'));
            let textElement = $('input' + $(this).attr('data-state-target'));
            let country = $(this).val();
            selectElement.html('');
            selectElement.val('').change();
            textElement.val('');
            if (wobelShippingStates[country] && (typeof (wobelShippingStates[country].length) == undefined || wobelShippingStates[country].length !== 0)) {
                textElement.hide().prop('disabled', true);
                selectElement.show().prop('disabled', false);
                selectElement.append('<option value="">Select</option>');
                jQuery.each(wobelShippingStates[country], function (key, value) {
                    selectElement.append('<option value="' + key + '">' + value + '</option>');
                });
            } else {
                selectElement.val('').change().hide().prop('disabled', true);
                selectElement.html('');
                textElement.show().prop('disabled', false);
            }
        }
    });

    $(document).on('click', '.wobel-modal-order-billing-save-changes-button', function () {
        let billingModal = $(this).closest('#wobel-modal-order-billing');
        let billingData = [];

        billingData.push({
            name: 'billing_first_name',
            type: 'woocommerce_field',
            value: billingModal.find('[data-order-field="first-name"]').val(),
        });
        billingData.push({
            name: 'billing_last_name',
            type: 'woocommerce_field',
            value: billingModal.find('[data-order-field="last-name"]').val(),
        });
        billingData.push({
            name: 'billing_email',
            type: 'woocommerce_field',
            value: billingModal.find('[data-order-field="email"]').val(),
        });
        billingData.push({
            name: 'billing_phone',
            type: 'woocommerce_field',
            value: billingModal.find('[data-order-field="phone"]').val(),
        });
        billingData.push({
            name: 'billing_postcode',
            type: 'woocommerce_field',
            value: billingModal.find('[data-order-field="postcode"]').val(),
        });
        billingData.push({
            name: 'billing_company',
            type: 'woocommerce_field',
            value: billingModal.find('[data-order-field="company"]').val(),
        });
        billingData.push({
            name: 'billing_address_1',
            type: 'woocommerce_field',
            value: billingModal.find('[data-order-field="address-1"]').val(),
        });
        billingData.push({
            name: 'billing_address_2',
            type: 'woocommerce_field',
            value: billingModal.find('[data-order-field="address-2"]').val(),
        });
        billingData.push({
            name: 'billing_city',
            type: 'woocommerce_field',
            value: billingModal.find('[data-order-field="city"]').val(),
        });
        billingData.push({
            name: 'billing_country',
            type: 'woocommerce_field',
            value: billingModal.find('[data-order-field="country"]').val(),
        });
        billingData.push({
            name: 'billing_state',
            type: 'woocommerce_field',
            value: billingModal.find('[data-order-field="state"]').val(),
        });
        billingData.push({
            name: 'payment_method',
            type: 'woocommerce_field',
            value: billingModal.find('[data-order-field="payment-method"]').val(),
        });
        billingData.push({
            name: 'transaction_id',
            type: 'woocommerce_field',
            value: billingModal.find('[data-order-field="transaction-id"]').val(),
        });

        wobelOrderEdit([$(this).attr('data-order-id')], billingData);
    });

    $(document).on('click', '.wobel-modal-order-shipping-save-changes-button', function () {
        let shippingModal = $(this).closest('#wobel-modal-order-shipping');
        let shippingData = [];

        shippingData.push({
            name: 'shipping_first_name',
            type: 'woocommerce_field',
            value: shippingModal.find('[data-order-field="first-name"]').val(),
        });
        shippingData.push({
            name: 'shipping_last_name',
            type: 'woocommerce_field',
            value: shippingModal.find('[data-order-field="last-name"]').val(),
        });
        shippingData.push({
            name: 'shipping_postcode',
            type: 'woocommerce_field',
            value: shippingModal.find('[data-order-field="postcode"]').val(),
        });
        shippingData.push({
            name: 'shipping_company',
            type: 'woocommerce_field',
            value: shippingModal.find('[data-order-field="company"]').val(),
        });
        shippingData.push({
            name: 'shipping_address_1',
            type: 'woocommerce_field',
            value: shippingModal.find('[data-order-field="address-1"]').val(),
        });
        shippingData.push({
            name: 'shipping_address_2',
            type: 'woocommerce_field',
            value: shippingModal.find('[data-order-field="address-2"]').val(),
        });
        shippingData.push({
            name: 'shipping_city',
            type: 'woocommerce_field',
            value: shippingModal.find('[data-order-field="city"]').val(),
        });
        shippingData.push({
            name: 'shipping_country',
            type: 'woocommerce_field',
            value: shippingModal.find('[data-order-field="country"]').val(),
        });
        shippingData.push({
            name: 'shipping_state',
            type: 'woocommerce_field',
            value: shippingModal.find('[data-order-field="state"]').val(),
        });
        shippingData.push({
            name: 'payment_method',
            type: 'woocommerce_field',
            value: shippingModal.find('[data-order-field="payment-method"]').val(),
        });
        shippingData.push({
            name: 'customer_note',
            type: 'woocommerce_field',
            value: shippingModal.find('[data-order-field="customer-note"]').val(),
        });

        wobelOrderEdit([$(this).attr('data-order-id')], shippingData);
    });

    $(document).on('click', '.wobel-modal-load-billing-address', function () {
        wobelLoadCustomerBillingAddress($(this).attr('data-customer-id'), $(this).attr('data-target'));
    });

    $(document).on('click', '.wobel-modal-load-shipping-address', function () {
        wobelLoadCustomerShippingAddress($(this).attr('data-customer-id'), $(this).attr('data-target'));
    });

    $(document).on('change', '#wobel-bulk-edit-change-status', function () {
        let orderIds = wobelGetOrdersChecked();
        if ($(this).val() && orderIds.length > 0) {
            let orderData = [{
                name: 'order_status',
                type: 'woocommerce_field',
                value: $(this).val(),
                operation: 'inline_edit'
            }];
            wobelOrderEdit(orderIds, orderData);
        }
    });

    $(document).on('click', '.wobel-customer-details', function () {
        $('#wobel-modal-customer-details-item-title').text($(this).attr('data-item-name'));
        wobelLoadCustomerDetails($(this).attr('data-customer-id'), '#wobel-modal-customer-details');
    });

    $(document).on(
        {
            mouseenter: function () {
                $(this).addClass('wobel-disabled-column');
            },
            mouseleave: function () {
                $(this).removeClass('wobel-disabled-column');
            }
        },
        "td[data-editable=no]"
    );

    $(document).on('click', '#wobel-modal-order-notes-add', function () {
        if ($('#wobel-modal-order-notes-content').val()) {
            let orderData = [{
                name: 'order_notes',
                type: 'order_notes',
                value: $('#wobel-modal-order-notes-content').val(),
                operator: $('#wobel-modal-order-notes-type').val()
            }];

            wobelAddOrderNote($(this).attr('data-order-id'), orderData);
        } else {
            swal({
                title: "Note is required !",
                type: "warning"
            });
        }
    });

    $(document).on('click', '#wobel-modal-order-notes .delete-note', function () {
        let noteId = $(this).attr('data-note-id');
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
            confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
            confirmButtonText: "Yes, I'm sure !",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wobelDeleteOrderNote(noteId);
            }
        });
    });

    $(document).on('click', '.wobel-order-address', function () {
        let tdElement = $(this).closest('td');
        $('#wobel-modal-order-address-title').text($(this).attr('data-item-name'));
        $('div.wobel-modal-order-address-text').html('');
        $('input.wobel-modal-order-address-text').val('');
        $('.wobel-order-address-save-button').attr('data-order-id', $(this).attr('data-item-id')).attr('data-field', $(this).attr('data-field'));
        if ($(this).attr('data-field') == 'billing_address_index' || $(this).attr('data-field') == 'shipping_address_index') {
            $('.wobel-order-address-save-button').hide();
            $('input.wobel-modal-order-address-text').prop('disabled', true).hide();
            $('div.wobel-modal-order-address-text').show();
        } else {
            $('.wobel-order-address-save-button').show().attr('data-name', tdElement.attr('data-name')).attr('data-update-type', tdElement.attr('data-update-type'));
            $('input.wobel-modal-order-address-text').prop('disabled', false).show();
            $('div.wobel-modal-order-address-text').hide();
        }
        wobelGetAddress($(this).attr('data-item-id'), $(this).attr('data-field'));
    });

    $(document).on('click', '.wobel-order-items', function () {
        $('.wobel-order-items-loading').show();
        $('.wobel-order-items-table tbody').html('');
        $('#wobel-modal-order-items-title').text($(this).attr('data-item-name'));
        wobelGetOrderItems($(this).attr('data-item-id'));
    });

    $(document).on('click', '.wobel-bulk-edit-status-filter-item', function () {
        $('.wobel-top-nav-status-filter').hide();

        $('.wobel-bulk-edit-status-filter-item').removeClass('active');
        $(this).addClass('active');
        $('.wobel-status-filter-selected-name').text(' - ' + $(this).text());

        if ($(this).attr('data-status') === 'all') {
            $('#wobel-filter-form-reset').trigger('click');
        } else {
            $('#wobel-filter-form-order-status').val($(this).attr('data-status')).change();
            setTimeout(function () {
                $('#wobel-filter-form-get-orders').trigger('click');
            }, 250);
        }
    });

    $(document).on('click', '.wobel-order-address-save-button', function () {
        let orderIds = [];
        if ($('#wobel-bind-edit').prop("checked") === true) {
            orderIds = wobelGetOrdersChecked();
        } else {
            orderIds.push($(this).attr("data-order-id"));
        }

        let orderData = [{
            name: $(this).attr('data-name'),
            type: $(this).attr('data-update-type'),
            value: $('input.wobel-modal-order-address-text').val(),
            operation: 'inline_edit'
        }];
        wobelOrderEdit(orderIds, orderData);
    });

    if (itemIdInUrl && itemIdInUrl > 0) {
        wobelResetFilterForm();
        setTimeout(function () {
            $('#wobel-filter-form-order-ids').val(itemIdInUrl);
            $('#wobel-filter-form-get-orders').trigger('click');
        }, 500);
    }

    $(document).on('click', '.wobel-delete-item-btn', function () {
        let orderIds = [];
        orderIds.push($(this).attr('data-item-id'));
        let deleteType = $(this).attr('data-delete-type');
        swal({
            title: 'Are you sure?',
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
            confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
            confirmButtonText: "Yes, i'm sure",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wobelDeleteOrder(orderIds, deleteType);
            }
        });
    });

    $(document).on('click', '.wobel-restore-item-btn', function () {
        let orderIds = [];
        orderIds.push($(this).attr('data-item-id'));
        swal({
            title: 'Are you sure?',
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
            confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
            confirmButtonText: "Yes, i'm sure",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wobelRestoreOrder(orderIds);
            }
        });
    });

    $(document).on('change', '#wobel-filter-form-order-status', function () {
        if ($.isArray($(this).val()) && $.inArray('trash', $(this).val()) !== -1) {
            $('.wobel-top-navigation-trash-buttons').show();
        } else {
            $('.wobel-top-navigation-trash-buttons').hide();
        }
    });

    $(document).on('click', '#wobel-bulk-edit-trash-empty', function () {
        swal({
            title: 'Are you sure?',
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
            confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
            confirmButtonText: "Yes, i'm sure",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wobelEmptyTrash();
            }
        });
    });

    $(document).on('click', '#wobel-bulk-edit-trash-restore', function () {
        let orderIds = wobelGetOrderChecked();
        wobelRestoreOrder(orderIds);
    });

    $(document).on('click', '.wobel-history-pagination-item', function () {
        $('.wobel-history-pagination-loading').show();

        let filters = {
            operation: $("#wobel-history-filter-operation").val(),
            author: $("#wobel-history-filter-author").val(),
            fields: $("#wobel-history-filter-fields").val(),
            date: {
                from: $("#wobel-history-filter-date-from").val(),
                to: $("#wobel-history-filter-date-to").val()
            }
        };

        wobelHistoryChangePage($(this).attr('data-index'), filters);
    });

    $(document).on('click', '.wobel-trash-option-restore-selected-items', function () {
        let orderIds = wobelGetOrdersChecked();
        if (!orderIds.length) {
            swal({
                title: "Please select one order",
                type: "warning"
            });
            return false;
        } else {
            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
                confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
                confirmButtonText: "Yes, i'm sure",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    wobelRestoreOrder(orderIds);
                }
            });
        }
    });

    $(document).on('click', '.wobel-trash-option-restore-all', function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
            confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
            confirmButtonText: "Yes, i'm sure",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wobelRestoreOrder([]);
            }
        });
    });

    $(document).on('click', '.wobel-trash-option-delete-selected-items', function () {
        let orderIds = wobelGetOrdersChecked();
        if (!orderIds.length) {
            swal({
                title: "Please select one order",
                type: "warning"
            });
            return false;
        } else {
            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
                confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
                confirmButtonText: "Yes, i'm sure",
                closeOnConfirm: true
            }, function (isConfirm) {
                if (isConfirm) {
                    wobelDeleteOrder(orderIds, 'permanently');
                }
            });
        }
    });

    $(document).on('click', '.wobel-trash-option-delete-all', function () {
        swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            cancelButtonClass: "wobel-button wobel-button-lg wobel-button-white",
            confirmButtonClass: "wobel-button wobel-button-lg wobel-button-green",
            confirmButtonText: "Yes, i'm sure",
            closeOnConfirm: true
        }, function (isConfirm) {
            if (isConfirm) {
                wobelEmptyTrash()
            }
        });
    });

    wobelGetProducts();
    wobelGetTaxonomies();
    wobelGetTags();
    wobelGetCategories();
    wobelGetDefaultFilterProfileOrders();
});