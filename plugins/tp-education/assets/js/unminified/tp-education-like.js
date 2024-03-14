jQuery(document).ready( function() {

   jQuery(".user_like").click( function(e) {
      e.preventDefault();
      post_id  = jQuery(this).attr("data-post_id")
      nonce    = jQuery(this).attr("data-nonce")

      jQuery.ajax({
         type : "post",
         dataType : "json",
         url : tp_education_ajax.ajaxurl,
         data : {action: "tp_education_like", post_id: post_id, nonce: nonce},
         success: function(response) {
            if(response.type == "success") {
               jQuery("#like_counter").html(response.like_count)
            }
            else {
               alert("You have already liked this post.")
            }
         }
      })   

   })

});