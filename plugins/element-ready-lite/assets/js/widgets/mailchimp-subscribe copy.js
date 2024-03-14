(function($) {

   /*-------------------------------
        MAILCHIMP HANDLER
    --------------------------------*/
    var MailChimp_Subscribe_Form_Script_Handle = function($scope, $) {

        var mailchimp_data = $scope.find('.mailchimp_from__box').eq(0);
        var settings = mailchimp_data.data('value'); /*Data Value Also can get by attr().*/
        var random_id = settings['random_id'];
        var post_url = settings['post_url'];

        $("#mc__form__" + random_id).ajaxChimp({
            url: '' + post_url + '',
            callback: function(resp) {
                if (resp.result === "success") {
                    $("#mc__form__" + random_id + " input").hide();
                    $("#mc__form__" + random_id + " button").hide();
                }
            }
        });
    }




    $(window).on('elementor/frontend/init', function() {
        
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Subscriber_Widget.default', MailChimp_Subscribe_Form_Script_Handle);

       
    });
})(jQuery);