(function ($) {
    'use strict';
    $(document).ready(function ($) {
        // Initialize Code Mirror if the 'code_snippets_textarea' element exists.
        if ($('#code_snippets_textarea').length > 0) {
            // console.log(cm_settings);
            // // Your new mode settings
            // let newModeSettings = {
            // 	name: 'php',
            // 	startOpen: true
            // };

            // // Update the codeEditor.codemirror.mode property with the new settings
            // cm_settings.codeEditor.codemirror.mode = newModeSettings;

            // wp.codeEditor.initialize($('#code_snippets_textarea'), cm_settings);
        }

        $('.bdstfw_swiss_toolkit_framework .bdstfw_switch_toggle td .switch-options').each(function () {
            const switchToggle = $(this);
            const optionsHtml = '<div class="bdstfw_swiss_options"><span class="bdstfw_swiss_options_ball"></span></div>';
            switchToggle.append(optionsHtml);

            const optionsElement = switchToggle.find('.bdstfw_swiss_options');
            const enableButton = switchToggle.find('.cb-enable');
            const disableButton = switchToggle.find('.cb-disable');

            // Reference to the description span
            const descriptionSpan = switchToggle.closest('tr').find('.description');
            // Reference to the <a> tag
            const clickHereLink = descriptionSpan.find('a');
            const access_snippets = descriptionSpan.find('p.access_snippets');

            // Check the initial state on page load
            if (disableButton.hasClass('selected')) {
                optionsElement.removeClass('bdstfw_switch_toggle_active');
                clickHereLink.hide();
                access_snippets.hide();
            } else {
                optionsElement.addClass('bdstfw_switch_toggle_active');
                clickHereLink.show();
                access_snippets.show();
            }

            // Toggle switch logic
            optionsElement.on('click', function () {
                if (disableButton.hasClass('selected')) {
                    enableButton.trigger('click');
                    clickHereLink.show();
                    access_snippets.show();
                } else {
                    disableButton.trigger('click');
                    clickHereLink.hide();
                    access_snippets.hide();
                }
            });

            // Additional logic to handle enableButton click
            enableButton.on('click', function () {
                optionsElement.addClass('bdstfw_switch_toggle_active');
                clickHereLink.show();
                access_snippets.show();
            });

            // Additional logic to handle disableButton click
            disableButton.on('click', function () {
                optionsElement.removeClass('bdstfw_switch_toggle_active');
                clickHereLink.hide();
                access_snippets.hide();
            });
        });

        $('#redux-sub-footer').prependTo('#redux-footer');

        // Trigger the 'click' event on the 'publish' button when 'link_publish' is clicked.
        if ($('#link_publish').length > 0) {
            $('#link_publish').on('click', function () {
                $('.post-type-swiss_generate_url #publish').trigger('click');
            });
        }

        // Close the Swiss Toolkit notice when the close button is clicked.
        if ($('.swiss-toolkit-notice-close').length > 0) {
            $('.swiss-toolkit-notice-close').on('click', function () {
                $('.swiss-toolkit-top-notice').hide();
            });
        }

        // Append increase and decrease arrows for file size and execution time settings.
        if ($('.maximum_upload_file_size td fieldset, .maximum_execution_time td fieldset').length > 0) {
            const html = '<div class="increase-decrease-arrows"><span class="increase-arrow"><svg width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.25 4.75V8H4.75V4.75H8V3.25H4.75V0H3.25V3.25H0V4.75H3.25Z" fill="#135E96"/></svg></span><span class="decrease-arrow"><svg width="8" height="2" viewBox="0 0 8 2" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 1H8" stroke="#135E96" stroke-width="1.5"/></svg></span></div>';
            $('.maximum_upload_file_size td fieldset, .maximum_execution_time td fieldset').append(html);
        }

        // Increase the value when the increase arrow is clicked for file size.
        if ($('.maximum_upload_file_size .increase-arrow').length > 0) {
            $('.maximum_upload_file_size .increase-arrow').on('click', function () {
                let value = $('.maximum_upload_file_size input').val();
                let newValue = parseInt(value) + 1;
                $('.maximum_upload_file_size input').val(newValue);
            });
        }

        // Decrease the value when the decrease arrow is clicked for file size.
        if ($('.maximum_upload_file_size .decrease-arrow').length > 0) {
            $('.maximum_upload_file_size .decrease-arrow').on('click', function () {
                let value = $('.maximum_upload_file_size input').val();
                let newValue = parseInt(value) - 1;
                $('.maximum_upload_file_size input').val(newValue);
            });
        }

        // Increase the value when the increase arrow is clicked for execution time.
        if ($('.maximum_execution_time .increase-arrow').length > 0) {
            $('.maximum_execution_time .increase-arrow').on('click', function () {
                let value = $('.maximum_execution_time input').val();
                let newValue = parseInt(value) + 1;
                $('.maximum_execution_time input').val(newValue);
            });
        }

        // Decrease the value when the decrease arrow is clicked for execution time.
        if ($('.maximum_execution_time .decrease-arrow').length > 0) {
            $('.maximum_execution_time .decrease-arrow').on('click', function () {
                let value = $('.maximum_execution_time input').val();
                let newValue = parseInt(value) - 1;
                $('.maximum_execution_time input').val(newValue);
            });
        }

        // Reload the page after clicking the CSF Save Button with a delay of 1 second.
        // if ($('.boomdevs_swiss_toolkit_framework #redux_bottom_save, .boomdevs_swiss_toolkit_framework #redux_top_save').length > 0) {
        //     $('.boomdevs_swiss_toolkit_framework #redux_bottom_save, .boomdevs_swiss_toolkit_framework #redux_top_save').on('click', function () {
        //         setTimeout(() => {
        //             window.location.reload();
        //         }, 1000)
        //     });
        // }

        // Copy the generated URL when the 'copy_generate_url' button is clicked.
        if ($('.copy_generate_url').length > 0) {
            $('.copy_generate_url').on('click', function () {
                if ($(this).parent().find(".generate_url_copied").length !== 1) {
                    $(this).parents('.type-swiss_generate_url').find('.copy_generate_url').css({
                        color: 'rgba(80, 87, 94, 0.5)',
                    });
                    let $temp = $("<input>");
                    $("body").append($temp);
                    $temp.val($(this).html()).select();
                    document.execCommand("copy");
                    $temp.remove();
                    $("<span class='generate_url_copied'>Copied</span>").appendTo(this).fadeIn('slow');
                    setTimeout(function () {
                        $('.generate_url_copied').remove().fadeOut(5000);
                        $('.copy_generate_url').css({
                            color: '#50575e',
                        });
                    }, 1500);
                } else {
                    alert('This token has already been copied.');
                }
            });
        }

        if ($('#swiss-toolkit-for-wp-preview_content_url').length > 0) {
            console.log('fdsf')
            $('#swiss-toolkit-for-wp-preview_content_url').on('click', function () {
                if ($(this).parent().find(".generate_url_copied").length !== 1) {
                    $(this).parents('#1_box_redux-swiss-toolkit-for-wp-metabox-generate_url_preview_meta_section_group').find('#swiss-toolkit-for-wp-preview_content_url').css({
                        color: 'rgba(80, 87, 94, 0.5)',
                    });
                    let $temp = $("<input>");
                    $("body").append($temp);
                    $temp.val($(this).html()).select();
                    document.execCommand("copy");
                    $temp.remove();
                    $("<span class='generate_url_copied'>Copied</span>").appendTo(this).fadeIn('slow');
                    setTimeout(function () {
                        $('.generate_url_copied').remove().fadeOut(5000);
                        $('#swiss-toolkit-for-wp-preview_content_url').css({
                            color: '#50575e',
                        });
                    }, 1500);
                } else {
                    alert('This token has already been copied.');
                }
            });
        }

        // Copy the URL token when the 'copy_url_token' button is clicked.
        if ($('.copy_url_token').length > 0) {
            $('.copy_url_token').on('click', function (e) {
                e.preventDefault();
                $(this).parents('.type-swiss_generate_url').find('.copy_url_token a').css({
                    color: 'rgba(34, 113, 177, 0.5)',
                });
                const token = $(this).parents('.type-swiss_generate_url').find('.copy_generate_url').text();
                let $temp = $("<input>");
                $("body").append($temp);
                $temp.val(token).select();
                document.execCommand("copy");
                $temp.remove();
                setTimeout(function () {
                    $('.copy_url_token a').css({
                        color: '#2271b1',
                    });
                }, 1500);
            });
        }

        // Copy the generated token inside the preview URL when the 'copy_preview_url' button is clicked.
        if ($('.copy_preview_url').length > 0) {
            $('.copy_preview_url').on('click', function () {
                if ($(this).parent().find(".preview_url_copied").length !== 1) {
                    $('.copy_preview_url').css({
                        color: 'rgba(60, 67, 74, 0.5)',
                    });
                    let $temp = $("<input>");
                    $("body").append($temp);
                    $temp.val($(this)[0].textContent).select();
                    document.execCommand("copy");
                    $temp.remove();
                    $("<span class='preview_url_copied'>Copied</span>").appendTo(this).fadeIn('slow');
                    setTimeout(function () {
                        $('.preview_url_copied').remove().fadeOut(5000);
                        $('.copy_preview_url').css({
                            color: '#3c434a',
                        });
                    }, 1500);
                } else {
                    alert('This token has already been copied.');
                }
            });
        }

        // Functionality to import a JSON file into WP Swiss Toolkit
        if ($('#import_json_file').length > 0) {
            $('#import_json_file').on('click', function (e) {
                e.preventDefault();
                let fileInput = document.getElementById('json_file_importer');
                let file = fileInput.files[0];

                if (file) {
                    const reader = new FileReader();
                    const loader = $('.swiss_import_button img');

                    reader.onload = function (e) {
                        try {
                            // Parse the JSON content from the file
                            const fileContent = JSON.parse(e.target.result);

                            // Display a loading spinner
                            loader.css({
                                display: 'block'
                            });

                            // Trigger a click on the save button
                            $('.boomdevs_swiss_toolkit_framework .csf-save').trigger('click');

                            // Perform an AJAX post request to import the data
                            $.ajax({
                                type: 'POST',
                                url: localize_object.ajax_url,
                                data: {
                                    action: 'swiss_knife_import',
                                    formData: fileContent,
                                    nonce: localize_object.nonce,
                                },
                                dataType: 'json',
                                success: function (response) {
                                    // Hide the loading spinner on success
                                    loader.css({
                                        display: 'none'
                                    });

                                    // Refresh the page after a short delay
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 5000)
                                },
                            });

                        } catch (error) {
                            console.error('Error parsing JSON:', error);
                        }
                    };

                    // Read the file as text
                    reader.readAsText(file);
                }
            });
        }

        // Adding a reset button below the default reset button
        if ($('.boomdevs_swiss_toolkit_framework .csf-footer .csf-buttons').length > 0) {
            $('.boomdevs_swiss_toolkit_framework .csf-footer .csf-buttons').prepend('<button type="button" class="button csf-warning-primary csf-confirm swiss_custom_reset">Reset</button>');
        }

        // Handling click on the custom reset button
        if ($('.swiss_custom_reset').length > 0) {
            $('.swiss_custom_reset').on('click', function () {
                $('.boomdevs_swiss_toolkit_framework .csf-reset').trigger('click')
            })
        }

        // Adding CSF help icon to the title
        if ($('.swiss_help_icon').length > 0) {
            $('.swiss_help_icon').each(function () {
                const csfTitle = $(this).find('.csf-title h4');
                const csfHelpIcon = $(this).find('.csf-help');
                csfHelpIcon.appendTo(csfTitle);
            });
        }

        // Setting a default image for the user
        if ($('.csf--preview.hidden').length > 0) {
            const csfPreviewImage = $('.csf--preview.hidden');
            csfPreviewImage.css("display", "block");
            csfPreviewImage.find('.csf--src').attr('src', localize_object.default_avatar);
        }

        // Custom file uploader for importing JSON files
        if ($('.swiss_import_file .custom_import_uploader span').length > 0) {
            $('.swiss_import_file .custom_import_uploader span').on('click', function () {
                $('.swiss_import_file #json_file_importer').trigger('click')
            })
        }

        // Displaying the selected file's name in the input field
        if ($('.swiss_import_file #json_file_importer').length > 0) {
            $('.swiss_import_file #json_file_importer').change(function (e) {
                $('.swiss_import_file .custom_import_uploader input').val(e.target.files[0].name)
            })
        }

        // Checking if the import-export tab is active and hiding/showing save buttons accordingly
        function importExportSaveButtons() {
            let currentUrl = window.location.href;
            let newParams = '';
            let searchParams = new URLSearchParams(currentUrl);
            searchParams.forEach(function (value, key) {
                newParams = value;
            });

            if (newParams === 'boomdevs-swiss-toolkit-settings#tab=import-export') {
                $('.boomdevs_swiss_toolkit_framework .csf-footer').hide();
            } else {
                $('.boomdevs_swiss_toolkit_framework .csf-footer').show();
            }
        }

        // Initial check for import-export tab status
        importExportSaveButtons();

        // Handling click on tab items and toggling save button visibility
        if ($('.boomdevs_swiss_toolkit_framework .csf-tab-item a').length > 0) {
            $('.boomdevs_swiss_toolkit_framework .csf-tab-item a').on('click', function () {
                let currentUrl = window.location.href;
                let newParams = '';
                let searchParams = new URLSearchParams(currentUrl);
                searchParams.forEach(function (value, key) {
                    newParams = value;
                });

                if (newParams === 'boomdevs-swiss-toolkit-settings#tab=features') {
                    $('.boomdevs_swiss_toolkit_framework .csf-footer').hide();
                } else {
                    $('.boomdevs_swiss_toolkit_framework .csf-footer').show();
                }
            })
        }

        // Replacing the CSF Help Icon with a custom icon
        if ($('.swiss_help_icon .csf-help').length > 0) {
            $('.swiss_help_icon .csf-help').append('<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_230_381)"><path d="M7.99998 1.60001C11.536 1.60001 14.4 4.46401 14.4 8.00001C14.4 11.536 11.536 14.4 7.99998 14.4C4.46398 14.4 1.59998 11.536 1.59998 8.00001C1.59998 4.46401 4.46398 1.60001 7.99998 1.60001ZM8.79998 4.80001C8.79998 4.36001 8.43998 4.00001 7.99998 4.00001C7.55998 4.00001 7.19998 4.36001 7.19998 4.80001C7.19998 5.24001 7.55998 5.60001 7.99998 5.60001C8.43998 5.60001 8.79998 5.24001 8.79998 4.80001ZM8.79998 12V7.20001H7.19998V12H8.79998Z" fill="black"/></g><defs><clipPath id="clip0_230_381"><rect width="16" height="16" fill="white"/></clipPath></defs></svg>')
        }
    });

    if ($('#swiss_knife_lang_switch').length > 0) {
        $('#swiss_knife_lang_switch').change(function () {
            const lang = $(this).val();
            $('.swiss_knife_snippet').attr("data-code-type", lang);
            $('#publish').trigger('click');
        });
    }

    // Duplicate post functionality
    if ($('.duplicate_post').length > 0) {
        $('.duplicate_post').click(function (e) {
            e.preventDefault();
            const postId = $(this).data('postid');
            $.ajax({
                type: 'POST',
                url: localize_object.ajax_url,
                data: {
                    action: 'swiss_knife_duplicate_post',
                    postid: postId,
                    nonce: localize_object.nonce,
                },
                dataType: 'json',
                success: function (response) {
                    if (response.duplicate_id) {
                        var location = window.location.href;
                        if (location.split('?').length > 1) {
                            location = location + '&duplicated=' + response.duplicate_id;
                        } else {
                            location = location + '?duplicated=' + response.duplicate_id;
                        }
                        window.location.href = location;
                    }
                },
            });
        });
    }
})(jQuery);