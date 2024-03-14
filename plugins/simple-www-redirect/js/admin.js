;(function($) {

    // after document ready
    $(document).ready(function(){

    	// the radio change event
        $("[name='swr_force_type']").on("change", function() {
            $(".swr-radio").removeClass("checked");
            $("[name='swr_force_type']:checked").parents(".swr-radio").addClass("checked");
        });

        // radio division click updating the radio value
        $(".swr-radio").on("click", function() {
            $(this).find("input").prop("checked", true);
            $(".swr-radio").removeClass("checked");
            $("[name='swr_force_type']:checked").parents(".swr-radio").addClass("checked");
        });

    });

}(jQuery));