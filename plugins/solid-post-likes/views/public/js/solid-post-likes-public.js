(function($) {
  "use strict"
  $(document).on("click", ".oacs-spl-like-button", function() {
    // restricts scope to this single button instance and not all on the document. Also give me access to the button object.
    var button = $(this)
    /**
     * 1) on click give me class oacs-spl-like-button and save it as 'button'
     * 2) parse data out of it.
     * 3) check whether it is a comment and apply css class accordingly.
     * 4) check if post id is available and send a POST request via wp ajax (trigger loader) to oacs_spl_process_like() method.
     * 5) access response of 4) and output a html button via Javascript.
     * 6) add / remove liked class
     */

    // Parse button object for the values I need.
    var post_id = button.attr("data-post-id")
    var security = button.attr("data-nonce")
    var iscomment = button.attr("data-iscomment")

    var currentbutton

    if (iscomment == "1") {
      currentbutton = $(".oacs-spl-like-comment-button-" + post_id)
    } else {
      currentbutton = $(".oacs-spl-like-button-" + post_id)
    }

    if (post_id !== "") {
      $.ajax({
        type: "POST",
        // to WordPress standard 'ajaxurl' => admin_url( 'admin-ajax.php' ),
        url: oacs_spl_solid_likes.ajaxurl,
        data: {
          action: "oacs_spl_process_like",
          // the data is generated via the PHP that manages the button.
          post_id: post_id,
          // a nonce is supplied. And a post ID. oacs_spl_process_like() will only run if nonce is valid.
          nonce: security,
          // and whether it is a comment. This data is verified and the nonce is checked by oacs_spl_process_like
          is_comment: iscomment
        },
        beforeSend: () =>
          {
            $(this).children('.spinner').addClass("spl-is-active"); // add the spinner spl-is-active class to this instance only before the Ajax posting
          },
        success: function(response) {
          // define variables by accessing response object.
          var icon = response.icon
          var count = response.count
          var text = response.text

          var button_output = ['<span class="spinner"></span>']

          // Check if any is null and add to output array.

          if (icon !== null ){
            button_output.push(icon)
          }

          if (count !== null){
            button_output.push(count)
          }

          if (text !== null){
            button_output.push(text)
          }

          // render button with icon, count and text.
          currentbutton.html(button_output)

          if (response.status === "unliked") {
            currentbutton.removeClass("oacs-spl-liked")
          } else {
            currentbutton.addClass("oacs-spl-liked")
          }

          $(this).children('.spinner').removeClass("spl-is-active");
        }
      });
    }
    return false
  });
})(jQuery);
