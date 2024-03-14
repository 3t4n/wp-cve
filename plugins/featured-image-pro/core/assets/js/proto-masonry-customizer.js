
// Holds the status of whether or not the rest of the code should be run
var cstmzr_multicat_js_run = true;

// All this really does is change a text field so that the customizer automatically updates.

jQuery(function ($) {

    // Prevents code from running twice due to live preview window.load firing in addition to the main customizer window.
    if (true === cstmzr_multicat_js_run) {
        cstmzr_multicat_js_run = false;
    } else {
        return;
    }

    var api = wp.customize;
   // Sets listeners for checkboxes
    $(".cstmzr-checkbox").live("change", function() {

        var elem = $(this).closest(".categorychecklistparent").find(".cstmzr-hidden-categories");

        if ($(this).prop("checked") === true) {
            elem.val("1");
        } else {
			elem.val("3");
        }

    });


});