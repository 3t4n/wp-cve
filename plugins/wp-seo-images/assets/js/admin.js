jQuery(document).ready(function () {

    jQuery('#wp-seo-images-update-submit').click(function() {
        jQuery("#wp-seo-images-update-message").show();       
        var data = {
            action: 'wp_seo_images_action_update',
            wgi_text: jQuery('#wgi_text').val()
        };            

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajaxurl, data, function(response) {
                if (response=="ko"){
                    jQuery("#wp-seo-images-update-message").hide();
                    alert("Sorry, can't save your changes");
                } else {
                    jQuery("#wp-seo-images-update-message").hide();
                    jQuery("#id_modify_ok").show();	  
                }
        });
    });      
    
});