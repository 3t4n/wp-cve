jQuery(document).ready(function($) {
    $.validate();
    $(document).find('.ultimate-subscribe-overlay').each(function(index, object) {
        var id = $(this).attr('id');
        var delay_s = parseInt($(this).data('delay'));
        var delay_ms = delay_s * 1000;
        setTimeout(function() {
            $(object).show();
        }, delay_ms);

        var hide = $(this).data('overlayhide');
        if (hide) {
            $(this).on('click', function(event) {
                if ($(event.target).hasClass('ultimate-subscribe-overlay')) {
                    $(this).hide();
                }
            });
        }

    });
    var outshow = true;
    var leave_form_id = ultimate_subscribe_object.leave_form_id;
    $(document).mouseleave(function(event) {
        if (outshow && leave_form_id) {
            $('#' + leave_form_id + '.ultimate-subscribe-overlay').show();
        }
        outshow = false;
    });
    $(document).on("click", ".ultimate-subscribe-close", function() {
        $(this).parents('.ultimate-subscribe-overlay').hide();
    });

    $(document).on('submit', '.ultimate-subscribe-form', function(e) {
        e.preventDefault();
        var submit_button = $(this).find('.ultimate-subscribe-submit');
        var submit_icon = $(this).find('.ultimate-subscribe-submit-icon.fa');
        var response_div = $(this).parents('.ultimate-subscribe-form-con').siblings('.ultimate-subscribe-res');
        var this_form = $(this);
        submit_icon.removeClass("fa-paper-plane-o");
        submit_icon.addClass("fa-circle-o-notch fa-spin");
        submit_button.prop('disabled', 'disabled');

        var email = $(this).find('#ultimate-subscribe-email').val();
        var fname = $(this).find('#ultimate-subscribe-fname').val();
        var lname = $(this).find('#ultimate-subscribe-lname').val();
        var birthday = $(this).find('#ultimate-subscribe-birthday').val();
        var form_id = $(this).find('#ultimate-subscribe-form-id').val();

        var data = {};
        data.action = 'ultimate_subscribe_submit';
        data.form_id = form_id;

        if (email) {
            data.email = email;
        }
        if (fname) {
            data.fname = fname;
        }
        if (lname) {
            data.lname = lname;
        }
        if (birthday) {
            data.birthday = birthday;
        }
        console.log(data);

        $.ajax({
            url: ultimate_subscribe_object.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(res) {
                console.log(res);
                this_form[0].reset();
                if (res.status == 'success') {
                    response_div.html('<div class="success">' + res.message + '</div>');
                } else if (res.status == 'warning') {
                    response_div.html('<div class="warning">' + res.message + '</div>');
                } else {
                    response_div.html('<div class="error">' + res.message + '</div>');
                }
            },
            error: function(a, b, c) {
                console.log(a, b, c);
            },
            complete: function() {
                submit_icon.removeClass("fa-circle-o-notch fa-spin");
                submit_icon.addClass("fa-check-square-o");
            }

        });
    });
    $(document).on('click', '.ultimate-subscribe-confirm-overlay', function(event) {
        if ($(event.target).hasClass('ultimate-subscribe-confirm-overlay')) {
            $(this).hide();
        }
    });
    $(document).on('click', '.us-confirm-close-btn', function(event) {
        $(this).parents('.ultimate-subscribe-confirm-overlay').hide();
    });
});
