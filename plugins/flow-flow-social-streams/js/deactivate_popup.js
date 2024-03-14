(function ($) {
    var deactivated = false;
    var reason = "not_set";
    var additionalInfo = "";
    var btnVal = 3;

    function sendDataToServer(data, lh) {
        var url = window["FF_Deactivate"].admin_url

        $.post(url, data, function(data, status){
            console.log(data, status);

            if (lh) {
                location.href = lh;
            }
        })
    }

    function LA_Deactivator(prefix) {
        $(document).on("click", "[data-slug='flow-flow-social-streams'] .deactivate a", function () {
            $(".ff-opacity").show();
            $(".ff-deactivate-popup").show();
            if ($(this).attr("data-uninstall") == "1") {
                btnVal = 2;
            }
            return false;
        });

        $(document).on("change", "[name='ff_reasons']", function () {
            reason = $(this).val()
            var $wrap = $(".ff-additional-details-wrap");
            var $popup = $(".ff-deactivate-popup");

            $wrap.html("");
            $popup.removeClass("ff-popup-active1 ff-popup-active2 ff-popup-active3");

            if (reason == "plugin_is_hard_to_use_technical_problems") {
                additionalInfo = '<div class="ff-additional-active"><div><strong>Please describe your issue.</strong></div><br>' +
                    '<textarea name="' + prefix + '_additional_details" rows = "4"></textarea><br>' +
                    '<div>Our support will contact <input type="text" name="' + prefix + '_email" value="' + window["FF_Deactivate"].email + '"> regarding this.</div>' +
                    '<br><div><button class="button button-primary ff-submit-ticket" data-val="' + btnVal + '">Submit support ticket</button></div></div>';

                $wrap.append(additionalInfo);
                $popup.addClass("ff-popup-active1");
            }
            else if (reason == "free_version_limited") {
                additionalInfo = '<div class="ff-additional-active">' +
                    '<div><strong>We believe our premium version will fit your needs.</strong></div>' +
                    '<div><a href="' + window["FF_Deactivate"].premium_url + '" target="_blank">Try with 100% money back guarantee.</a></div>';

                $wrap.append(additionalInfo);
                $popup.addClass("ff-popup-active2");
            }
            else if (reason == "premium_expensive") {
                additionalInfo = '<div class="ff-additional-active">' +
                    '<div><strong>We have a special offer for you.</strong></div>' +
                    '<div>Submit this form to get the offer to <input type="text" name="' + prefix + '_email" value="' + window["FF_Deactivate"].email + '"></div>' +
                    '</div>';

                $wrap.append(additionalInfo);
                $popup.addClass("ff-popup-active3");
            }

            $("#ff-deactivate").hide();
            $("#ff-submit-and-deactivate").show();
        });

        $(document).on("keyup", "[name=" + prefix + "_additional_details]", function () {
            if ($(this).val().trim() || jQuery("[name=" + prefix + "_reasons]:checked").length > 0) {
                $("#ff-deactivate").hide();
                $("#ff-submit-and-deactivate").show();
            }
            else {
                $("#ff-deactivate").show();
                $("#ff-submit-and-deactivate").hide();
            }
        });

        $(document).on("click", ".ff-deactivate", function (e) {
            $(".ff-deactivate-popup-opacity").show();

            var $t =  $(this);

            var additional = $('[name="ff_additional_details"]').val()
            var email = $('[name="ff_email"]').val()
            var data = {
                action: window["FF_Deactivate"].slug_down + '_deactivate',
                plugin: window["FF_Deactivate"].slug_down,
                reason: reason,
                email: email ? email : window["FF_Deactivate"].admin_email,
                version: window["FF_Deactivate"].version,
                additional_info: additional ? additional : ''
            }

            if ($t.hasClass("ff-clicked") == false) {
                $t.addClass("ff-clicked");
                $("[name=ff_submit_and_deactivate]").val($t.attr("data-val"));
            }

            sendDataToServer(data, $(this).attr('href'))

            e.preventDefault()
        });

        $(document).on("click", ".ff-submit-ticket", function () {
            var message = $('[name="ff_additional_details"]').val()
            var email = $('[name="ff_email"]').val()
            var data = {
                action: window["FF_Deactivate"].slug_down + '_deactivate_ticket',
                plugin: window["FF_Deactivate"].slug_down,
                reason: reason,
                email: email ? email : window["FF_Deactivate"].admin_email,
                version: window["FF_Deactivate"].version,
                message: message ? message : ''
            }

            sendDataToServer(data)

            if ($(this).hasClass("ff-clicked") == false) {
                $(this).addClass("ff-clicked");
                $(".ff-submit-ticket").text('Ticket sent!');
                setTimeout(function () {
                    $(".ff-submit-ticket").text('Submit support ticket');
                    $('[name="ff_additional_details"]').val('')
                }, 5000)
            }
        });

        $(document).on("click", ".ff-cancel, .ff-opacity", function () {
            $(".ff-opacity").hide();
            $(".ff-deactivate-popup").hide();
            return false;
        });

        // applying correct links
        var deactivate_hr = $("[data-slug='flow-flow-social-streams'] .deactivate a").attr('href')
        $('#ff-submit-and-deactivate, #ff-deactivate').each(function(){
            $(this).attr('href',deactivate_hr)
        })
    }

    $(document).ready(function () {
        LA_Deactivator('ff')
    })
})(jQuery)