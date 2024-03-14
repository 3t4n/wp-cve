document.addEventListener("DOMContentLoaded", function() {
    //Hide form when user in logged in and show the error message.
    var current_form = document.getElementsByName("_wpcf7");

    if ((current_form[0].value === cf7forms_data.reg_form_id) && (document.body.classList.contains('logged-in') == true)) {
        var fieldNameElement = document.getElementsByClassName('wpcf7');
        fieldNameElement[0].innerHTML = "You are already logged in.";
    }
});
//redrirect user to page after form success
document.addEventListener('wpcf7mailsent', function(event) {
    setTimeout(function() {
        if (cf7forms_data.reg_form_redirect != '') {
            var contactform_id = event.detail.contactFormId;
            if (cf7forms_data.reg_form_id == contactform_id) {
                window.location = cf7forms_data.reg_form_redirect
            }
        }
    }, 1000);

});