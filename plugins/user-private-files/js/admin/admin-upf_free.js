jQuery(document).ready(function ($) {

    //Code for multiple selection
    $(".chosen-select").chosen({
        no_results_text: "Oops, nothing found!"
    });

    //Code for toggle buttons
    $('.upfp_toggle_setting').on('click', function () {
        $(this).toggleClass("parent_toggle");
        $(this).find('.upfp_round').toggleClass("child_toggle");
        if ($(this).find('input[type="checkbox"]').is(":checked")) {
            $(this).find('input[type="checkbox"]').attr('checked', false);
        }
        else {
            $(this).find('input[type="checkbox"]').attr('checked', true);
        }
    });

    //code for showing pop-ups
    $('#upfp_icon1').on('click', function () {
        $("#upfp_popup-container-1").css("display", "flex");
    });

    $('#upfp_icon2').on('click', function () {
        $("#upfp_popup-container-2").css("display", "flex");
    });

    //code for closing pop-ups
    $('#upfp_close-popup1, #upfp_popup-container-1').on('click', function () {
        $("#upfp_popup-container-1").css("display", "none");
    });

    $('#upfp_close-popup2, #upfp_popup-container-2').on('click', function () {
        $("#upfp_popup-container-2").css("display", "none");
    });

    
});