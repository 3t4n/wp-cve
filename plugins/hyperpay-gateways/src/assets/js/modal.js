function openModal(text) {

    var modal = jQuery("#hyperpayModal");
    var span = jQuery(".close")[0];

    modal.find("p").html(text)

    modal.fadeIn();

    span.onclick = function () {
        modal.fadeOut();
    }

    // When the user clicks anywhere outside of the modal, close it
    jQuery(window).click(function(event){
        if (event.target == modal[0]) {
            modal.fadeOut();
        }
    })

}