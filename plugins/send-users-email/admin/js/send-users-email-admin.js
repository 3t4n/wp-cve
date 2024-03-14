(function ($) {
    'use strict';

    /* Enable tooltips */
    initializeBSTooltip();

    $(document).ajaxComplete(function (event, request, settings) {
        initializeBSTooltip();
    });


    /* Custom filtering function which will search data in column four between two values */
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        var min = parseInt($('#minID').val(), 10);
        var max = parseInt($('#maxID').val(), 10);
        var id = parseFloat(data[1]) || 0; // use data for the ID column

        if (
            (isNaN(min) && isNaN(max)) ||
            (isNaN(min) && id <= max) ||
            (min <= id && isNaN(max)) ||
            (min <= id && id <= max)
        ) {
            return true;
        }
        return false;
    });

    // User email datatable initialization
    var sueUserTbl = $('#sue-user-email-tbl').DataTable({
        "scrollY": "330px",
        "scrollCollapse": true,
        "paging": false,
        "order": [[1, "asc"]],
        "orderClasses": false,
    });

    /* User email page - hide columns on user table - start */
    // Set hidden column on load
    var sueUserTblColsToHide = localStorage.getItem('sueUserTblColsToHide');
    if (sueUserTblColsToHide) {
        var colIdx = sueUserTblColsToHide.split(',');
        for (let i = 0; i < colIdx.length; i++) {
            var column = sueUserTbl.column(colIdx[i]);
            column.visible(!column.visible());
            $('.hideUserColumn').each(function () {
                if ($(this).val() == colIdx[i]) {
                    $(this).prop('checked', 'checked');
                }
            });
        }
    }

    // Handle hidden column checkbox check
    $('.hideUserColumn').change(function () {
        var sueUserTblColsToHide = [];
        var column = sueUserTbl.column($(this).attr('data-column'));
        column.visible(!column.visible());
        $('.hideUserColumn').each(function () {
            if ($(this).is(':checked')) {
                sueUserTblColsToHide.push($(this).val());
            }
        });
        localStorage.setItem('sueUserTblColsToHide', sueUserTblColsToHide);
    });
    /* User email page - hide columns on user table - end */

    // Event listener to the two range filtering inputs to redraw on input
    $('#minID, #maxID').keyup(function () {
        sueUserTbl.draw();
        var selectAllSel = $("#sueSelectAllUsers");
        if (selectAllSel.is(':checked')) {
            selectAllSel.trigger('click');
        } else {
            selectAllSel.trigger('click');
            selectAllSel.trigger('click');
        }
    });

    setTimeout(function () {
        $('.sue-user-email-datatable-wrapper').css("visibility", "visible");
    }, 800);


    // Initialise tinymce
    $("#sue-user-email-btn, #sue-roles-email-btn, #sue-email-premade-templates-btn").mousedown(function () {
        tinyMCE.triggerSave();
    });

    // Check all users
    $("#sueSelectAllUsers").click(function () {
        $('.sueUserCheck').prop('checked', this.checked);
    });

    // Track Email send progress
    var email_users_email_progress;

    // User email send ajax process
    $("#sue-users-email-form").submit(function () {
        $(".error-msg").remove();
        $(".is-invalid").removeClass("is-invalid");
        $("#sue-user-email-btn").attr('disabled', true);
        showSpinner();
        $('.progress').show();
        var postData = $(this).serialize();
        postData += "&action=sue_user_email_ajax&param=send_email_user";
        $.post(ajaxurl, postData, function (res) {
            if (res.success === true) {
                showToast(res.message);
                $(".sueUserCheck").removeAttr('checked');
                if (res.warning !== '') {
                    showWarningToast(res.warning);
                }
            }
        }).fail(function (res) {
            if (res.responseJSON !== undefined) {
                if (res.responseJSON.errors != null) {
                    var errors = res.responseJSON.errors;
                    for (var field in errors) {
                        var fieldSel = $("." + field);
                        fieldSel.addClass('is-invalid');
                        fieldSel.after('<div class="invalid-feedback error-msg">' + errors[field] + '</div>');
                    }
                    scrollToError();
                }
            }
            showToast('Oops! Something went wrong!', 'danger');
        }).always(function () {
            clearInterval(email_users_email_progress);
            $("#sue-user-email-btn").attr('disabled', false);
            showSpinner(false);
            updateProgress(0);
            $('.progress').hide();
        });

        // Get data to monitor email send progress
        email_users_email_progress = setInterval(function () {
            $.get(ajaxurl, {action: "sue_email_users_progress", "param": "send_email_user_progress"}, function (res) {
                updateProgress(res.progress);
                if (res.progress >= 100) clearInterval(email_users_email_progress);
            });
        }, 5000);

    });


    var email_roles_email_progress;

    // Role email send ajax process
    $("#sue-roles-email-form").submit(function () {
        $(".error-msg").remove();
        $(".is-invalid").removeClass("is-invalid");
        $("#sue-roles-email-btn").attr('disabled', true);
        showSpinner();
        $('.progress').show();
        var postData = $(this).serialize();
        postData += "&action=sue_role_email_ajax&param=send_email_role";
        $.post(ajaxurl, postData, function (res) {
            if (res.success === true) {
                showToast(res.message);
                $(".roleCheckbox").removeAttr('checked');
                if (res.warning !== '') {
                    showWarningToast(res.warning);
                }
            }
        }).fail(function (res) {
            if (res.responseJSON !== undefined) {
                if (res.responseJSON.errors != null) {
                    var errors = res.responseJSON.errors;
                    for (var field in errors) {
                        var fieldSel = $("." + field);
                        fieldSel.addClass('is-invalid');
                        fieldSel.after('<div class="invalid-feedback error-msg">' + errors[field] + '</div>');
                    }
                    scrollToError();
                }
            }
            showToast('Oops! Something went wrong!', 'danger');
        }).always(function () {
            clearInterval(email_roles_email_progress);
            $("#sue-roles-email-btn").attr('disabled', false);
            showSpinner(false);
            updateProgress(0);
            $('.progress').hide();
        });

        // Get data to monitor email send progress
        email_roles_email_progress = setInterval(function () {
            $.get(ajaxurl, {action: "sue_email_roles_progress", "param": "send_email_role_progress"}, function (res) {
                updateProgress(res.progress);
                if (res.progress >= 100) clearInterval(email_roles_email_progress);
            });
        }, 5000);

    });


    // Settings save send ajax process
    $("#sue-settings-form").submit(function () {
        $(".error-msg").remove();
        $(".is-invalid").removeClass("is-invalid");
        $("#sue-settings-btn").attr('disabled', true);
        showSpinner();
        var postData = $(this).serialize();
        postData += "&action=sue_settings_ajax&param=sue_settings";
        $.post(ajaxurl, postData, function (res) {
            if (res.success === true) {
                showToast('Settings save successfully!');
            }
        }).fail(function (res) {
            if (res.responseJSON !== undefined) {
                if (res.responseJSON.errors != null) {
                    var errors = res.responseJSON.errors;
                    for (var field in errors) {
                        var fieldSel = $("#" + field);
                        fieldSel.addClass('is-invalid');
                        fieldSel.after('<div class="invalid-feedback error-msg">' + errors[field] + '</div>');
                    }
                    scrollToError();
                }
            }
            showToast('Oops! Something went wrong!', 'danger');
        }).always(function () {
            $("#sue-settings-btn").attr('disabled', false);
            showSpinner(false);
        });

    });

    // Process view email log request
    $("#sue_view_email_log_file").change(function () {
        if ($(this).val() != 0) {
            $("#sue-view-log-btn").removeAttr('disabled');
        } else {
            $("#sue-view-log-btn").attr('disabled', 'disabled');
        }
    });

    $("#sue-view-email-log").submit(function () {
        $("#sue-view-log-btn").attr('disabled', 'disabled');
        $("#emailLogTextAreaContainer").css('display', 'none');
        var postData = $(this).serialize();
        postData += "&action=sue_view_email_log_ajax&param=sue_view_email_log";
        $.post(ajaxurl, postData, function (res) {
            if (res.success === true) {
                $("#email_log_view_area").text(res.message);
                $("#logFileSize").text('Log file size: ' + res.filesize + ' MB. Please note that logged content is stripped down version and does not contain any HTML tags.');
            }
        }).fail(function (res) {
            if (res.responseJSON !== undefined) {
                if (res.responseJSON.errors != null) {
                    alert('Oops! Something went wrong.');
                }
            }
            alert('Oops! Something went wrong.');
        }).always(function () {
            $("#sue-view-log-btn").removeAttr('disabled');
            $("#emailLogTextAreaContainer").css('display', 'block');
        });
    });


    


    function showSpinner(show = true) {
        var spinner = $('.sue-spinner');
        show ? spinner.show() : spinner.hide();
        if (show) $('.sue-messages').text('');
    }

    function updateProgress(val = 0) {
        var progressSel = $('.progress-bar');
        progressSel.attr("aria-valuenow", val);
        progressSel.text(val + "%");
        progressSel.css('width', val + "%");
    }

    function scrollToError() {
        var errorMsgSel = $(".error-msg");
        errorMsgSel.first().css('font-weight', 'bold');
        $('html, body').animate({
            scrollTop: errorMsgSel.first().offset().top - 125
        }, 500);
    }

    function initializeBSTooltip() {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }

    function showToast(message = 'Success', type = 'success') {
        const toastSel = document.getElementById('liveToast');
        $('#liveToast .toast-body').text(message);
        $('#liveToast').removeClass('text-bg-success text-bg-danger');
        $('#liveToast').addClass('text-bg-' + type);
        const toast = new bootstrap.Toast(toastSel);
        toast.show();
    }

    function showWarningToast(message = 'Warning', type = 'warning') {
        const toastSel = document.getElementById('warningToast');
        $('#warningToast .toast-body').text(message);
        $('#warningToast').removeClass('text-bg-success text-bg-danger');
        $('#warningToast').addClass('text-bg-' + type);
        const toast = new bootstrap.Toast(toastSel);
        toast.delay = 5000;
        toast.show();
    }

})(jQuery);
