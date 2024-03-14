jQuery(document).ready(function() {

    try {

        if (!jQuery('#wpcf7-Aweber-cf-active').is(':checked'))
            jQuery('.Aweber-custom-fields').hide();

        jQuery('#wpcf7-Aweber-cf-active').click(function() {

            if (jQuery('.Aweber-custom-fields').is(':hidden') &&
                jQuery('#wpcf7-Aweber-cf-active').is(':checked')) {

                jQuery('.Aweber-custom-fields').slideDown('fast');
            } else if (jQuery('.Aweber-custom-fields').is(':visible') &&
                jQuery('#wpcf7-Aweber-cf-active').not(':checked')) {

                jQuery('.Aweber-custom-fields').slideUp('fast');
                jQuery(this).closest('form').find(".Aweber-custom-fields input[type=text]").val("");
            }

        });


        jQuery(".awb-trigger").click(function() {
            jQuery(".awb-support").slideToggle("fast");

            jQuery(this).text(function(i, text) {
                return text === "Show advanced settings" ? "Hide advanced settings" : "Show advanced settings";
            })

            return false; //Prevent the browser jump to the link anchor
        });

        jQuery(".awb-trigger2").click(function() {
            jQuery(".awb-support2").slideToggle("fast");
            return false; //Prevent the browser jump to the link anchor
        });

        jQuery(".awb-trigger3").click(function() {
            jQuery(".awb-support3").slideToggle("fast");
            return false; //Prevent the browser jump to the link anchor
        });

        jQuery(".awb-trigger-sys").click(function() {

            jQuery("#toggleawb-sys").slideToggle(250);

        });


        jQuery(".awb-trigger-exp").click(function() {

            jQuery("#awb-export").slideToggle(250);

        });


        jQuery(".awb-trigger-log").click(function() {

            jQuery("#eventlogawb-sys").slideToggle(250);

        });

        jQuery(document).on('click', '.awb-trigger-log', function(event) {

            event.preventDefault(); // stop post action

            jQuery.ajax({
                type: "POST",
                url: ajaxurl, // or '<?php echo admin_url('admin-ajax.php'); ?>'

                data: {

                    action: 'aweber_logload',
                    //mce_idformxx: jQuery("#awb_txtcomodin").val(),
                    //mceapi: jQuery("#wpcf7-aweber-api").val(),

                },
                // error: function(e) {
                //   console.log(e);
                // },

                beforeSend: function() {

                    // jQuery("#log_reset").addClass("CHIMPLogger");

                },

                success: function(response) { // response //data, textStatus, jqXHR

                    jQuery('#logawb_panel').html(response);

                },

                error: function(data, textStatus, jqXHR) {

                    alert(jqXHR);

                },

            });

        });

        jQuery(document).on('click', '#logaw_reset', function(event) {

            event.preventDefault(); // stop post action

            jQuery.ajax({
                type: "POST",
                url: ajaxurl, // or '<?php echo admin_url('admin-ajax.php'); ?>'

                data: {

                    action: 'aweber_logreset',

                },
                // error: function(e) {
                //   console.log(e);
                // },

                beforeSend: function() {


                },

                success: function(response) { // response //data, textStatus, jqXHR

                    //alert(response);
                    jQuery('#logawb_panel').html(response);

                },

                error: function(data, textStatus, jqXHR) {

                    alert(jqXHR);

                },

            });

        });



    } catch (e) {

    }

});