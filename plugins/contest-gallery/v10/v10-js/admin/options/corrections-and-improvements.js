jQuery(document).ready(function($){

    $(document).on('click','.cg_corrections_action_submit',function () {
        $(this).closest('form').submit();
    });

    $(document).on('click','#cgCorrect7showExceptionsButton',function () {
        $('#cgCorrect7exceptions').removeClass('cg_hide').slideDown();
    });

});