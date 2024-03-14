jQuery(document).ready(function($) {
    $('.cms30_callback_form button').click(function() {
        var cms30_phone = $("#cms30_call_me .cms30_phone").val();
        var cms30_msg = $("#cms30_call_me .cms30_msg").val();
        
        //phone check
        if(cms30_phone == ''){
            $("#cms30_call_me .cms30_phone").css({'border' : '#e86363 solid 1px'});
            return false;
        } else {
            $("#cms30_call_me .cms30_phone").css({'border' : '#DDDDDD solid 1px'});
        }

        jQuery.getJSON(
            ajax_object.ajax_url,
            {'action':'cms30_send', 'phone': cms30_phone},
            function(data) {
                $("#cms30_call_me .cms30_callback_form").html('<a href="#close" class="cms30_close_modal_min"></a><p>'+cms30_msg+'</p>');
            }
        );
        
        return false;
    });

    $('.cms30_callback_form').on('keydown', function (){
        $("#cms30_call_me .cms30_phone").css({'border' : '#DDDDDD solid 1px'});
        $("#cms30_call_me .cms30_phone").mask('+7(999)-999-99-99');
    });
});