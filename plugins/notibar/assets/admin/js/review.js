jQuery(document).ready(function () {
    jQuery("#njt-nofi-review a").on("click", function () {
        const thisElement = this;
        const fieldValue = jQuery(thisElement).attr("data");
        const freeLink = "https://wordpress.org/support/plugin/notibar/reviews/#new-post";
        let hidePopup = false;
        if (fieldValue == "rateNow") {
            window.open(freeLink, "_blank");
        } else {
            hidePopup = true;
        }

        jQuery
        .ajax({
          dataType: 'json',
          url: wpDataNofi.admin_ajax,
          type: "post",
          data: {
            action: "njt_nofi_save_review",
            field: fieldValue,
            nonce: wpDataNofi.nonce,
          },
        })
        .done(function (result) {
            if (hidePopup == true) {
                jQuery( "#njt-nofi-review .notice-dismiss" ).trigger( "click" );
            }
        })
        .fail(function (res) {
            if (hidePopup == true) {
                console.log(res.responseText);
                jQuery( "#njt-nofi-review .notice-dismiss" ).trigger( "click" );
            }
        });
    })
})