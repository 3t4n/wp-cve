var $s = jQuery.noConflict();

$s(document).ready(function($) {
    $(".sendle_tracking_form button").click(function(){
        var ref = $(this).parent().find("input[name=sendle_reference]").val();
        ref = ref.trim()
        ref = ref.replace(/[^a-z0-9]+/gi, "");
        var curthis = $(this);
        $(this).parent().find("input[name=sendle_reference]").val(ref);

        if(ref.length != 6){
            alert("Your requested reference number was not found.");
        }else{
            var data = {'action': 'sendletrack','reference': ref};
            $.post(sendletracking.ajaxurl, data, function(response) {
 
                var response = $.parseJSON(response);
                if(response.result == 1){
                    curthis.parent().parent().find(".sendle_tracking_info").html(response.info);
                }

            });

        }
    });
});