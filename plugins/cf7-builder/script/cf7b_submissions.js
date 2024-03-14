jQuery(document).ready(function () {
  jQuery(".cf7b-subm-row:not(.cf7b-subm-delete)").on("click", function() {
    if( jQuery(this).find(".cf7b-subm-content").hasClass('cf7b-hidden') ) {
        jQuery(this).find(".cf7b-subm-content").removeClass('cf7b-hidden');
        jQuery(this).find(".cf7b-subm-title .dashicons").removeClass("dashicons-arrow-down-alt2").addClass("dashicons-arrow-up-alt2");
    } else {
        jQuery(this).find(".cf7b-subm-content").addClass('cf7b-hidden');
        jQuery(this).find(".cf7b-subm-title .dashicons").removeClass("dashicons-arrow-up-alt2").addClass("dashicons-arrow-down-alt2");
    }
  });

  jQuery(".cf7b-subm-delete").on("click", function() {
    var r = confirm("Are you sure to delete the submission!");

    if (r === true) {
      return true;
    } else {
      return false;
    }
  });
});