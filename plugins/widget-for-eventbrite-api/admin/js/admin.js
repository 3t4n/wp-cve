(function ($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     *
     *
     */


    $(function () {

        function wfea_lookup_apikey(apikey) {
            if (apikey) {
                $('#widget-for-eventbrite-api-settings-api-key').removeClass("invalid").addClass("valid");
                $.ajax({
                    url: "https://www.eventbriteapi.com/v3/users/me/organizations/?token=" + apikey,
                    statusCode: {
                        401: function () {
                            $('#widget-for-eventbrite-api-settings-api-key').removeClass("valid").addClass("invalid");
                            $('.api-key-status').replaceWith('<p class="api-key-status error">' + wfea_data.StringInvalidAPI + '</p>');
                            $("#submit").prop("disabled", true);
                        }
                    }
                }).then(function (data) {
                    $("#submit").prop("disabled", false);
                    $('.api-key-status').replaceWith('<p class="api-key-status connected">' + wfea_data.StringConnected + '</p>');
                    let orgid = data.organizations[0].id;
                    $('.api-key-result').empty().append('<h4>' + wfea_data.StringOrganisations + '</h4><ul>')
                    var arrayLength = data.organizations.length;
                    for (var i = 0; i < arrayLength; i++) {
                        $('.api-key-result').append('<li class="api-key-org">' + data.organizations[i].name +
                            ' ID: ' + data.organizations[i].id
                            + '</li>');
                    }
                    $('.api-key-result').append('\<ul>')
                });
            } else {
                $('#widget-for-eventbrite-api-settings-api-key').removeClass("valid").addClass("invalid");
            }
        }
        wfea_lookup_apikey($('#widget-for-eventbrite-api-settings-api-key').val())  // onload
        $(document).on('click', '.wfea_notice .notice-dismiss', function (event) {
            let data = {
                action: 'wfea_dismiss_notice',
                nonce: wfea_data.nonce,
                id: $(this).closest('div').attr('id')
            };

            $.post(ajaxurl, data, function (response) {
                console.log(response, 'DONE!');
            });
        });


        $('#widget-for-eventbrite-api-settings-api-key').on('input', function () {
            let input = $(this);
            let apikey = input.val();
            wfea_lookup_apikey(apikey)
        });
        $('#widget-for-eventbrite-api-setup-api-key').on('input', function () {
            $('#wfea-wizard .api-key-result').replaceWith('<p class="api-key-result active connecting"><span class="progress"></span>' + wfea_data.StringConnecting + '</p>');
            console.log('input')
            let input = $(this);
            let apikey = input.val();
            let nonce = jQuery(this).attr("data-nonce");
            if (apikey) {
                $.ajax({
                    url: "https://www.eventbriteapi.com/v3/users/me/organizations/?token=" + apikey,
                    statusCode: {
                        401: function () {
                            $('.api-key-result').replaceWith('<p class="api-key-result active error">' + wfea_data.StringInvalidAPI + '</p>');
                        }
                    }
                }).then(function (data) {
                    $.ajax({
                        type: "post",
                        dataType: "json",
                        url: wfea_data.ajaxurl,
                        data: {action: "update_api_option", apikey: apikey, nonce: nonce},
                        success: function (response) {
                            if (response.result == true) {
                                $('#wfea-wizard .api-key-result').replaceWith('<p class="api-key-result active connected">' + wfea_data.StringConnected + '</p>');
                                window.location.href = wfea_data.redirectURL;
                            }
                        }
                    });
                });
            } else {
                input.removeClass("valid").addClass("invalid");
            }
        });


    });
})(jQuery);
