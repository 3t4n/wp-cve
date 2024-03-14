jQuery(document).ready(function () {
     
   /**
    * verify the api code
    * @since 1.0
    */
    jQuery(document).on('click', '#save-wpform-gs-code', function (event) {
		event.preventDefault();
        jQuery( ".loading-sign" ).addClass( "loading" );
        var data = {
        action: 'verify_wpform_gs_integation',
        code: jQuery('#wpforms-setting-google-access-code').val(),
        security: jQuery('#gs-ajax-nonce').val()
      };
      jQuery.post(ajaxurl, data, function (response ) {
          if( ! response.success ) { 
            jQuery( ".loading-sign" ).removeClass( "loading" );
            jQuery( "#gs-validation-message" ).empty();
            jQuery("<span class='error-message'>Invalid access code entered.</span>").appendTo('#gs-validation-message');
          } else {
            jQuery( ".loading-sign" ).removeClass( "loading" );
            jQuery( "#gs-validation-message" ).empty();
            jQuery("<span class='gs-valid-message'>Your Google Access Code is Authorized or Saved.</span> <br/><br/><span class='wp-valid-notice'> Note: If you are getting any errors or not showing sheet in dropdown, then make sure to check the debug log. To contact us for any issues do send us your debug log.</span>").appendTo('#gs-validation-message');
			//setTimeout(function () { location.reload(); }, 9000);

         setTimeout(function () { 
            window.location.href = jQuery("#redirect_auth_wpforms").val();
         }, 1000);
		  }
      });
      
    });  
    
	function html_decode(input) {
      var doc = new DOMParser().parseFromString(input, "text/html");
      return doc.documentElement.textContent;
   }

	/**
     * On select wpform
     */
   jQuery('#wpforms_select').change(function (e) {
      e.preventDefault();
      var FormId = jQuery(this).val();
      jQuery(".loading-sign-select").addClass("loading-select");
      jQuery.ajax({
         type: "POST",
         url: ajaxurl,
         dataType: "json",
         data: {
            action: 'get_wpforms',
            wpformsId: FormId,
            security: jQuery('#wp-ajax-nonce').val(),
         },
         cache: false,
         success: function (data) {  
         // console.log(data);        
            if (data['data_result'] == '') {
               return;
            }
            else {
                window.location.href = data.data;
                // window.open(data.data, '_blank');
               // jQuery("#inside").empty();
               // jQuery("#inside").append(html_decode(data.data));
               // jQuery(".loading-sign-select").removeClass("loading-select");
            }
         }
      });
   });
   
    /**
     * Clear debug
     */
      jQuery(document).on('click', '.debug-clear-kk', function () {
         jQuery( ".clear-loading-sign" ).addClass( "loading" );
         var data = {
            action: 'wp_clear_logs',
            security: jQuery('#gs-ajax-nonce').val()
         };
         jQuery.post(ajaxurl, data, function (response ) {
             var clear_msg = response.data;
            if( response.success ) { 
               jQuery( ".clear-loading-sign" ).removeClass( "loading" );
               jQuery( "#gs-validation-message" ).empty();
               jQuery("<span class='gs-valid-message'>"+clear_msg+"</span>").appendTo('#gs-validation-message'); 
               setTimeout(function () {
                     location.reload();
                 }, 1000);
            }
         });
      });
	  
	   /**
    * deactivate the api code
    * @since 1.0
    */
    jQuery(document).on('click', '#wp-deactivate-log', function () {
        jQuery(".loading-sign-deactive").addClass( "loading" );
		var txt;
		var r = confirm("Are you sure you want to deactivate Google Sheet Integration ?");
		if (r == true) {
			var data = {
				action: 'deactivate_wpformgsc_integation',
				security: jQuery('#gs-ajax-nonce').val()
			};
			jQuery.post(ajaxurl, data, function (response ) {
				if ( response == -1 ) {
					return false; // Invalid nonce
				}
			 
				if( ! response.success ) {
					alert('Error while deactivation');
					jQuery( ".loading-sign-deactive" ).removeClass( "loading" );
					jQuery( "#deactivate-message" ).empty();
					
				} else {
					jQuery( ".loading-sign-deactive" ).removeClass( "loading" );
					jQuery( "#deactivate-message" ).empty();
					jQuery("</br><span class='gs-valid-message'>Your account is removed, now reauthenticate to configure WPForms to Google Sheet.</span>").appendTo('#deactivate-message');
		   		    setTimeout(function () { location.reload(); }, 5000);
				}
			});
		} else {
			jQuery( ".loading-sign-deactive" ).removeClass( "loading" );
		}
    });

    /**
     * Display Error logs
     */
   jQuery(document).ready(function($) {
       // Hide .wc-system-Error-logs initially
       $('.wp-system-Error-logs').hide();

       // Add a variable to track the state
       var isOpen = false;

       // Function to toggle visibility and button text
       function toggleLogs() {
           $('.wp-system-Error-logs').toggle();
           // Change button text based on visibility
           $('.wpgsc-logs').text(isOpen ? 'View' : 'Close');
           isOpen = !isOpen; // Toggle the state
       }

       // Toggle visibility and button text when clicking .wcgsc-logs button
       $('.wpgsc-logs').on('click', function() {
           toggleLogs();
       });

       // Toggle visibility and button text when clicking .wc-system-Error-logs element
       $('.wp-system-Error-logs').on('click', function() {
           toggleLogs();
       });
   });
   /**
    * Clear debug for system status tab
    */
   jQuery(document).on('click', '.clear-content-logs-wp', function () {

      jQuery(".clear-loading-sign-logs-wp").addClass("loading");
      var data = {
         action: 'wp_clear_debug_logs',
         security: jQuery('#gs-ajax-nonce').val()
      };
      jQuery.post(ajaxurl, data, function ( response ) {
         if (response == -1) {
            return false; // Invalid nonce
         }
         
         if (response.success) {
            jQuery(".clear-loading-sign-logs-wp").removeClass("loading");
            jQuery('.clear-content-logs-msg-wp').html('Logs are cleared.');
            setTimeout(function () {
                        location.reload();
                    }, 1000);
         }
      });
   });
 
   
});
