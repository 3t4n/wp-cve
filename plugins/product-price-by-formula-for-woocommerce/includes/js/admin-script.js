jQuery(document).ready(function ( $ ){

jQuery(".prowc_ppbf_prowc_notice_review_yes").css("display","none");
jQuery( ".prowc_ppbf_prowc_notice .prowc_ppbf_yes" ).click(function() {
  jQuery(".prowc_ppbf_prowc_notice_review_yes").css("display","block");
});

//admin rating notice sweet alert - js
jQuery('.ppbf_starts_main_div .ppbf-star input[type=radio].ppbf').click( function( $ ){
  
  var notic_selected_rating = this.value;

    if( notic_selected_rating >= 4 ){
      swal({
        text: "That’s fantastic! Would you please help us out so we can keep improving the plugin? Please leave us a review here.",
        icon: "success",
        buttons: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          window.open('https://wordpress.org/support/plugin/product-price-by-formula-for-woocommerce/reviews/#new-post', '_blank');
        }
      });
    }else{
      swal({
        text: "Awww we would like to be doing better than that!  Would you please take the time to tell us how we can improve? Please fill out our contact form here.",
        icon: "warning",
        buttons: true,
      })
      .then((willDelete) => {
        if (willDelete) {
          window.open('https://prowcplugins.com/support/', '_blank');
        } else {
        }
      });
    }
    
  });

});
