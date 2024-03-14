(function ($) {


    // next step click from sif pop
    $("#ninja_forms_save_data_top").click(function () {

        var setting_type = $("#settings-type option:selected").text();

        if (setting_type == 'Esignature') {

            var esig_error = false;

            if ($("#settings-name").val() == "") {
                esig_error = true;
                $("#settings-name").css("border", "1px solid red");
            }
            if ($("#settings-signer_name").val() == "") {
                esig_error = true;
                $("#settings-signer_name-tokenfield").css("border", "1px solid red");
            }
            if ($("#settings-signer_email_address").val() == "") {
                esig_error = true;
                $("#settings-signer_email_address-tokenfield").css("border", "1px solid red");
            }
            if ($("#select_sad").val() == "") {
                esig_error = true;
                $("#select_sad").css("border", "1px solid red");
            }


            if (esig_error) {
                if ($("#esig-ninja-error").hasClass("error")) {
                    return false;
                }
                $("#notification_id").after("<div id='esig-ninja-error' class='error below-h2'><p>There was an error updating this feed. Please review all errors below and try again.</p></div>");
                return false;
            }


        }



    });

    // onchange events . 
    $('#settings-name').on('change', function (e) {

        if ($("#settings-name").val() != "") {
            //esig_error = true;
            $("#settings-name").css("border", "0px solid red");
        }
    });
    // signer name onchange events 
    $('#settings-signer_name').on('change', function (e) {

        if ($("#settings-signer_name").val() != "") {

            $("#settings-signer_name-tokenfield").css("border", "0px solid red");
        }
    });
    // eamil address onchagne events 
    $('#settings-signer_email_address').on('change', function (e) {

        if ($("#settings-signer_email_address").val() != "") {

            $("#settings-signer_email_address-tokenfield").css("border", "0px solid red");
        }
    });
    // sad select onchange events 
    $('#select_sad').on('change', function (e) {
        if ($("#select_sad").val() != "") {
           
            $("#select_sad").css("border", "0px solid red");
        }
    });

})(jQuery);