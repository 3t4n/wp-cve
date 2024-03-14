const {__, _x, _n, sprintf} = wp.i18n;
var qem_dont_cancel;


(function ($) {
    function pseudo_popup(content) {
        var popup = document.createElement("div");
        popup.innerHTML = content;
        var viewport_width = window.innerWidth;
        var viewport_height = window.innerHeight;

        function add_underlay() {
            var underlay = document.createElement("div");
            underlay.style.position = "fixed";
            popup.style.zIndex = "9997";
            underlay.style.top = "0px";
            underlay.style.left = "0px";
            underlay.style.width = viewport_width + "px";
            underlay.style.height = viewport_height + "px";
            underlay.style.background = "#7f7f7f";
            if (navigator.userAgent.match(/msie/i)) {
                underlay.style.background = "#7f7f7f";
                underlay.style.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity=50)";
            } else {
                underlay.style.background = "rgba(127, 127, 127, 0.5)";
            }
            underlay.onclick = function () {
                underlay.parentNode.removeChild(underlay);
                popup.parentNode.removeChild(popup);
            };
            document.body.appendChild(underlay);
        }

        add_underlay();
        var x = viewport_width / 2;
        var y = viewport_height / 2;
        popup.style.position = "fixed";
        document.body.appendChild(popup);
        x -= popup.clientWidth / 2;
        y -= popup.clientHeight / 2;
        popup.style.zIndex = "9998";
        popup.style.top = y + "px";
        popup.style.left = x + "px";
        return false;
    }

    function qem_toggle_state() {
        $(this).attr('clicked', 'clicked')
    }

    function qem_calendar_ajax(e) {
        /*
            Get calendar
        */
        var calendar = $(e).closest('.qem_calendar');
        var cid = Number(calendar.attr('id').replace('qem_calendar_', ''));
        var params = 'action=qem_ajax_calendar';

        /*
            URL Encode the atts array
        */

        let qem_calendar_atts = $('#qem_calendar_' + cid).data('qem_calendar_atts');
        for (property in qem_calendar_atts) {
            params += '&atts[' + encodeURIComponent(property) + ']=' + encodeURIComponent(qem_calendar_atts[property]);
        }
        let qem_month = $('#qem_calendar_' + cid).data('qem_month');
        let qem_year = $('#qem_calendar_' + cid).data('qem_year');
        let qem_category = $('#qem_calendar_' + cid).data('qem_category');
        params += "&qemmonth=" + qem_month + "&qemyear=" + qem_year + "&qemcalendar=" + cid;
        if (qem_category != '') params += '&category=' + qem_category;

        $.post(ajaxurl, params, function (v) {
            calendar.replaceWith($(v));
            qem_calnav();
        }, 'text');

    }

    function qem_handle_regular(e, f) {

        data = e;

        /*
            Handle Redirection
        */
        if (data.hasOwnProperty('redirect')) {
            if (data.redirect.redirect && !data.errors.length) {
                window.location.href = data.redirect.url;
                return;
            }
        }

        qem = f.closest('.qem');
        if ( !qem.length ) {
            qem = f.closest('.qem-columns');
        }
        /*
            Update whoscoming and places
        */

        qem.find('.whoscoming').html(data.coming);
        /* f.closest('.qem').find('.places').html(data.places); */

        /*
            Deactivate all current errors
        */
        qem.find('.qem-register').find('.qem-error,.qem-error-header').removeClass('qem-error qem-error-header');
        qem.find('.qem-register').find('h2').text(data.title);

        /*
            Hide the button!
        */
        qem.find('.toggle-qem').hide();

        if (data.blurb !== undefined) {
            var blurbText = qem.find('.qem-register').find('p').first();
            blurbText.show();
            blurbText.html(data.blurb);
        }

        /*
            If errors: Display
        */
        $('.qem-field-error').remove();
        console.log('---------------------')
        console.log(data)
        for (i in data.errors) {
            element = f.find('[name=' + data.errors[i].name + ']');
            console.log(element);
            element.addClass('qem-error');
            element.after('<p class="qem-error-header qem-field-error">' + data.errors[i].error + '</p>');
        }

        /*
            Change header class to reflect errors being present
        */
        if (data.errors.length) {
            qem.find('.qem-register').find('h2').addClass('qem-error-header');
            qem.find('.qem-register .places').hide();
            /* @since 9.2.3 remove as seems to hide the form for no good reason
            if (data.errors[i].name == 'alreadyregistered') {
                qem.find('.qem-register').children('p').first().hide();
                qem.find('.places').hide();
                qem.find('.qem-form').html(data.form || '');
            }
            */

        } else {
            /*
                Successful validation!
            */
            qem.find('.places').hide();
            var form = data.form;
            qem.find('.qem-form').html(form);
        }

        /*
            Scroll To Top
        */
        $('html,body').animate({
            scrollTop: Math.max(qem.find('.qem-register').offset().top - 25, 0),
        }, 200);
    }

    function qem_validate_form(ev) {
        var f = $(this);
        var formid = f.attr('id');

        // Intercept request and handle with AJAX
        var fd = $(this).serialize();
        var action = $('<input type="text" />');
        action.attr('name', 'action');
        action.val('qem_validate_form');

        fd += '&' + action.serialize() + '&action=qem_validate_form';
        $('input[name=qemregister' + formid + ']').prop("disabled", true);
        $('.qem_validating_form[data-form-id="' + formid + '"]').show(function () {
            $.post(ajaxurl,
                fd,
                function (e) {
                    qem_handle_regular(e, f);
                    $('.qem_validating_form[data-form-id="' + formid + '"]').hide();
                },
                'json'
            ).done(function () {
                // second success
            }).fail(function () {
                // ajax fail
            }).always(function () {
                $('input[name=qemregister' + formid + ']').prop("disabled", false);
                // alert("finished");
            });
        });
        ev.preventDefault();
        return false;
    }

    function qem_decide(e) {

        if (!e.ic) return 0;
        return 1;

    }


    $(document).ready(function () {

        $('.qem-register form').submit(qem_validate_form);
        $('.qem-register form input[type=submit], .qem-register form input[type=button]').click(qem_toggle_state);

        /*
            Set up calendar functionality
        */
        qem_calnav();

        $("#paylater").change(function () {
            var A = $('#submit').val(),
                B = $('#submit').attr('alt');

            $('#submit').attr('alt', A).val(B);
        });

        /*
            Setup payment buttons for in-context payments
        */
        if (typeof qem_ignore_ic === 'undefined') qem_ignore_ic = null;

        if (typeof qem_ic !== 'undefined' && qem_ignore_ic == false) {

            $('.qem-form form').submit(function (event) {
                event.preventDefault();
                return false;
            });

            $('.qem-form input[type=submit]').click(function (event) {

                event.preventDefault();

                /*
                    Collect Important Data
                */
                $target = event.target;
                c = $($target);
                $ = jQuery;
                form = c.closest('form');
                form.hide();

                var formid = form.attr('id');
                $('.qem_validating_form[data-form-id="' + formid + '"]').show();

                var fd = $(form).serialize();
                fd += '&' + c.attr('name') + '=' + c.val() + '&action=qem_validate_form';
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: fd,
                    success: function (e) {
                        data = e;

                        if (!data.success) {

                            $('.qem_validating[data-form-id="' + formid + '"]').hide();
                            $('.qem_processing[data-form-id="' + formid + '"]').hide();
                            form.show();
                            qem_handle_regular(data, form);

                            return;
                        }
                        
                    },
                    error: function (data) {
                        console.log(data);
                        alert(__('Server Error check console log', 'quick-event-manager'));
                    }
                });

                return false;
            });
            
        }

        $('.qem-multi-product').on('input', function () {
            var multiHolder = $(this).closest('.qem_multi_holder');
            var productInputs = multiHolder.find('input');
            var total = 0;
            var attending = 0;

            for (var i = 0; i < productInputs.length; i++) {
                var inputValue = $(productInputs[i]).val();

                // Remove all non-integer characters from the input value
                var cleanedValue = inputValue.replace(/\D/g, '');

                // Update the input field with the cleaned value
                $(productInputs[i]).val(cleanedValue);

                var quantity = parseInt(cleanedValue);
                var cost = parseFloat($(productInputs[i]).data('qem-cost'));
                if (!isNaN(quantity) ) {
                    attending += quantity;
                    total += quantity * cost;
                }
            }

            if (attending < 2) {
                $("#morenames").hide();
            } else {
                $("#morenames").show();
            }

            multiHolder.find('#total_price .qem_output').text(total.toFixed(2));
        });
        /*
	QEM Toggle
        */

        // settings preview
        $('.qem-preview').on('click', function (e) {
            alert(__('This is a preview of the event. To see the event go to the Events list.', 'quick-event-manager'));
            return false
        })

        //  lightbox action
        $("body").on('click', ".qem_linkpopup", function () {
            let lightBoxData = $(this).data('xlightbox');
            xlightbox(lightBoxData);
        });

        // fb and twitter share windows
        $("body").on('click', ".qem_fb_share, .qem_twitter_share", function () {
            window.open(this.href, 'targetWindow', 'titlebar=no,toolbar=no,location=0,status=no,menubar=no,scrollbars=yes,resizable=yes,width=600,height=250');
            return false;
        });


        $("#yourplaces").keyup(function () {
            var model = document.getElementById('yourplaces');
            var number = $('#yourplaces').val()
            if (number == 1)
                $("#morenames").hide();
            else {
                $("#morenames").show();
            }
        });

        $(".apply").hide();
        $(".toggle-qem").click(function (event) {
            $(this).next(".apply").slideToggle();
            event.preventDefault();
            return false;
        });

        /*
    Date Picker
*/

        datePickerOptions = {
            closeText: "Done",
            prevText: "Prev",
            nextText: "Next",
            currentText: "Today",
            monthNames: ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"],
            monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            dayNames: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
            dayNamesShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            dayNamesMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
            weekHeader: "Wk",
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: "",
            dateFormat: 'dd M yy'
        };

        $('#qemdate').datepicker(datePickerOptions);
        $('#qemenddate').datepicker(datePickerOptions);
        $('#qemcutoffdate').datepicker(datePickerOptions);
        $('#qem_reg_start_date').datepicker(datePickerOptions);

    });

    function qem_redirect(params) {


        var action = "?token=" + params.token;
        if (params.module == 'deferred') action = '';
        var form = $("<form method='POST' style='display:none;' action='" + action + "'></form>"), input;
        for (i in params) {

            if (i == "token") continue;
            input = $('<input type="hidden" />');
            input.attr('name', i);
            input.attr('value', params[i]);

            form.append(input);

        }

        form.append("<input type='submit' name='qem_submit' value='submitted' />");
        $('body').append(form);
        form.submit();

    }

    function qem_calendar_prep(e) {
        var calendar = $(e).closest('.qem_calendar');
        var cid = Number(calendar.attr('id').replace('qem_calendar_', ''));
        var params = e.href.split('#')[0].split('?')[1];
        if (params !== undefined) params = params.split('&');
        else params = [];
        var values = {};

        /*
            Form params into an object
        */
        for (i = 0; i < params.length; i++) {
            set = params[i].split('=');
            values[set[0]] = set[1];
        }

        /*
            Special case, no parameters at all = reset category
        */
        if (params.length == 0) values.category = '';

        /*
            Set the global variables if the link would have changed them!
        */
        if (values.qemmonth !== undefined) $('#qem_calendar_' + cid).data('qem_month', values.qemmonth);
        if (values.qemyear !== undefined) $('#qem_calendar_' + cid).data('qem_year', values.qemyear);
        if (values.category !== undefined) $('#qem_calendar_' + cid).data('qem_category', values.category);
    }

    function qem_calnav() {
        $('.qem_calendar .calnav').click(function (ev) {

            ev.preventDefault();

            qem_calendar_prep(this);
            qem_calendar_ajax(this);

            return false;

        });

        $('.qem_calendar .qem-category a').click(function (ev) {

            ev.preventDefault();

            qem_calendar_prep(this);
            qem_calendar_ajax(this);

            return false;

        });

    }

    /*
        LightBox
    */

    function xlightbox(insertContent, ajaxContentUrl) {

        // add lightbox/shadow <div/>'s if not previously added
        if ($('#xlightbox').length == 0) {
            var theLightbox = $('<div id="xlightbox"/>');
            var theShadow = $('<div id="xlightbox-shadow"/>');
            $(theShadow).click(function (e) {
                closeLightbox();
            });
            $('body').append(theShadow);
            $('body').append(theLightbox);
        }

        // remove any previously added content
        $('#xlightbox').empty();

        // insert HTML content
        if (insertContent != null) {
            $('#xlightbox').append(insertContent);
        }

        // insert AJAX content
        if (ajaxContentUrl != null) {
            // temporarily add a "Loading..." message in the lightbox
            $('#xlightbox').append('<p class="loading">Loading...</p>');

            // request AJAX content
            $.ajax({
                type: 'GET',
                url: ajaxContentUrl,
                success: function (data) {
                    // remove "Loading..." message and append AJAX content
                    $('#xlightbox').empty();
                    $('#xlightbox').append(data);
                },
                error: function (data) {
                    console.log(data);
                    alert(__('Server Error Check Console log!', 'quick-event-manager'));
                }
            });
        }

        // move the lightbox to the current window top + 100px
        $('#xlightbox').css('top', $(window).scrollTop() + 100 + 'px');

        // display the lightbox
        $('#xlightbox').show();
        $('#xlightbox-shadow').show();

    }

// close the lightbox

    function closeLightbox() {

        // hide lightbox and shadow <div/>'s
        $('#xlightbox').hide();
        $('#xlightbox-shadow').hide();

        // remove contents of lightbox in case a video or other content is actively playing
        $('#xlightbox').empty();
    }
})(jQuery);



