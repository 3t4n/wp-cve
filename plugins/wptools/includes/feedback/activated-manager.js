jQuery(document).ready(function($){
        //console.log('js activated...');
        jQuery('#adminmenumain').css('opacity', '.1');
        jQuery('.wrap').css('opacity', '.1');
        // jQuery('.wp-pointer').css('opacity', '0');
        jQuery('.bill-activate-modal-wptools').slideDown(); 
        jQuery('.bill-activate-modal-wptools').css('opacity', '1');
        jQuery('#imagewait').hide();

        // Close
       jQuery('#wptools-activate-close-dialog').on('click', function() {
           //  console.log('clicked close...');
           jQuery('.bill-activate-modal-wptools').slideUp(); 
           jQuery('#adminmenumain').css('opacity', '1');
           jQuery('.wrap').css('opacity', '1');
           // jQuery('.wp-pointer').css('opacity', '1');


           jQuery.ajax({
              url       : 'https://billminozzi.com/httpapi/httpapi.php',
                  withCredentials: true,
                  timeout: 15000,
              method    : 'POST',
                  crossDomain: true,
              data      : {
                  status: '17'
              },
              complete  : function () {
                    // console.log('ok');
                  }
           }); // end ajax 
           
          location.reload();


        });


        jQuery('#wptools-activate-install').on('click', function(evt) {
            evt.preventDefault();
            var showroom = $("#showroom").val();

            
            jQuery.ajax({
              url       : 'https://billminozzi.com/httpapi/httpapi.php',
                  withCredentials: true,
                  timeout: 15000,
              method    : 'POST',
                  crossDomain: true,
              data      : {
                  status: '18'
              },
              complete  : function () {
                    // console.log('ok');
                  }
            }); // end ajax
            
          
          
          $('.bill-activate-modal-wptools').slideUp(); 
          $('#adminmenumain').css('opacity', '1');
          jQuery('.wrap').css('opacity', '1');
          // jQuery('.wp-pointer').css('opacity', '1');
          window.location.href = showroom;

        });
});  