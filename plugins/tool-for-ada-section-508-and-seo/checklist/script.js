jQuery(document).ready(function($){
    
    $(".checklist input[type='checkbox']").change(function() {
        if($(this).is(":checked")) {
            $(this).parent().addClass("active-point");
        }else{
            $(this).parent().removeClass("active-point");
        }

        $("#save-notice").fadeIn();
    });

})