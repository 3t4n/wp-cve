jQuery(document).ready(function() {

	try {

		if (! jQuery('#wpcf7-getresponse-cf-active').is(':checked'))

			jQuery('.getresponse-custom-fields').hide();

		jQuery('#wpcf7-getresponse-cf-active').click(function() {

			if (jQuery('.getresponse-custom-fields').is(':hidden')
			&& jQuery('#wpcf7-getresponse-cf-active').is(':checked')) {

				jQuery('.getresponse-custom-fields').slideDown('fast');
			}

			else if (jQuery('.getresponse-custom-fields').is(':visible')
			&& jQuery('#wpcf7-getresponse-cf-active').not(':checked')) {

				jQuery('.getresponse-custom-fields').slideUp('fast');
        jQuery(this).closest('form').find(".getresponse-custom-fields input[type=text]").val("");

			}

		});



		jQuery(".vcgr-trigger").click(function() {

			jQuery(".vcgr-support").slideToggle("fast");

      jQuery(this).text(function(i, text){
          return text === "Show advanced settings" ? "Hide advanced settings" : "Show advanced settings";
      })

			return false; //Prevent the browser jump to the link anchor

		});


    jQuery(".vcgr-trigger2").click(function() {
      jQuery(".vcgr-support2").slideToggle("fast");
      return false; //Prevent the browser jump to the link anchor
    });


    jQuery(".vcgr-trigger3").click(function() {
      jQuery(".vcgr-support3").slideToggle("fast");
      return false; //Prevent the browser jump to the link anchor
    });


	}

	catch (e) {

	}

});