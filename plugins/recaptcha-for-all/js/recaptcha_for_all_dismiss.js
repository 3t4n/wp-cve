jQuery(document).ready(function ($) {
    // console.log('loaded dismiss!');
    // console.log(window.location.hostname);


    jQuery(document).on('click', '#recaptcha_for_all_an1 .notice-dismiss', function( event ) {
        jQuery.ajax({
            url: ajaxurl,
            data: {
              action : 'recaptcha_for_all_dismissible_notice',
            },
            success: function (data) {
                // This outputs the result of the ajax request
                //console.log('OK');
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });
    });


    

    jQuery('#recaptcha_an2 .notice-dismiss').click(function( event ) {

        //alert('1');
        //console.log('OK111111!');
        //console.log(ajaxurl);

        recaptcha_setCookie('recaptcha_dismiss', '1', '1');

        /*
        jQuery.ajax({
            type:"post",
            url:ajaxurl,
            data:{
            action:'recaptcha_dismiss_notice2'
            },
            success: function (data) {
                // This outputs the result of the ajax request
                //console.log('OK');
            },
            error: function (errorThrown) {
                console.log(errorThrown);
            }
        });
        */

        jQuery.ajax({
            url: ajaxurl,
            data: {
              action : 'recaptcha_dismiss_notice2',
            },
            success: function (data) {
                // This outputs the result of the ajax request
                //console.log('OK');
            },
            /*
            error: function (errorThrown) {
                // console.log(errorThrown);
                console.log('error');
            }
            */
            error: function (request, status, error) {
                /*
                console.log(request.responseText);
                console.log(error);
                console.log(status);
                */
            }
        });

    });

    function recaptcha_setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        let expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
      }


});