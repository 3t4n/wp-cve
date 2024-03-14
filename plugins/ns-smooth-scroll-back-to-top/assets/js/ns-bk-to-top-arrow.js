jQuery(document).ready(function(){
     jQuery(window).scroll(function () {
            if (jQuery(this).scrollTop() > 50) {
                jQuery('#ns-back-to-top-arrow').fadeIn();
            } else {
                jQuery('#ns-back-to-top-arrow').fadeOut();
            }
        });
        // click on back to top
        jQuery('#ns-back-to-top-arrow').click(function () {
            // click counter
            var ns_btta_security = jQuery('#ns_btta_security').val();
            jQuery.ajax({
                url : ns_btta_ajax_hit.ajax_url,
                type : 'post',
                data : {
                    action : 'ns_btta_ajax_hit',
                    ns_btta_security : ns_btta_security
                },
                    success: function(response) {
                    console.log(response);
                },
                error:function (){
                    //console.log(user);
                }
            });            

            // scroll body to 0px on click
            jQuery('body,html').animate({
                scrollTop: 0
            }, speedScrool);
            return false;
        });        



});