jQuery('document').ready(function () {

  jQuery(".cf7b-content").each(function () {
    var ind = 1;
    jQuery(this).find(".cf7b-page").each(function () {
      jQuery(this).attr("data-page",ind);
      if( ind !== 1) {
        jQuery(this).addClass("cf7b-hidden");
      } else {
        jQuery(this).addClass("cf7b-active");
      }
      ind++;
    });

    if( jQuery(this).find(".cf7b-page").length > 1 ) {
      jQuery(this).append("<div class='cf7b-pagination-row'><span class='cf7b-prev'>Prev</span><span class='cf7b-next'>Next</span></div>");
    }

  });


  jQuery(".cf7b-next").on("click", function() {
    var content = jQuery(this).closest(".cf7b-content");
    var active = parseInt(content.find(".cf7b-active").attr("data-page"));
    var next = active+1;
    if( content.find(".cf7b-page[data-page="+next+"]").length > 0 ) {
      content.find(".cf7b-active").addClass("cf7b-hidden");
      content.find(".cf7b-page").removeClass("cf7b-active");
      content.find(".cf7b-page[data-page="+next+"]").addClass("cf7b-active").removeClass("cf7b-hidden");
    }
  });

  jQuery(".cf7b-prev").on("click", function() {
    var content = jQuery(this).closest(".cf7b-content");
    var active = parseInt(content.find(".cf7b-active").attr("data-page"));
    var prev = active-1;
    if( content.find(".cf7b-page[data-page="+prev+"]").length > 0 ) {
      content.find(".cf7b-active").addClass("cf7b-hidden");
      content.find(".cf7b-page").removeClass("cf7b-active");
      content.find(".cf7b-page[data-page="+prev+"]").addClass("cf7b-active").removeClass("cf7b-hidden");
    }
  });

  /* Action after submit part */
  cf7b_action_after_submit();
});

function cf7b_action_after_submit() {
  document.addEventListener( 'wpcf7submit', function( event ) {
    var cf7b_action_type = cf7b_settings.action_type;
    var cf7b_action_value = cf7b_settings.action_value;

    if( cf7b_action_type == 0 || cf7b_action_value === '' ) {
      return;
    }

    if( cf7b_action_type == 1 || cf7b_action_type == 2 || cf7b_action_type == 4) {
      location = cf7b_action_value;
    } else {
      $success_msg = "<p class='success'>"+cf7b_action_value+"</p>";
      jQuery(".wpcf7-form").before($success_msg);
    }
  }, false );

}