; (function ($) {
  "use strict";

  
  /**
   * Enteraddons ajax Admin data save
   *
   */

  var $FormSubmit = $( '#enteraddons_settings_from' ),
      $saveButton = $('.enteraddons_save-btn');

  $FormSubmit.on( 'submit', function( e ) {
    e.preventDefault();
    let activateTab = localStorage.getItem("eaSettingsTabActivation");
    
    $.ajax({

      type: 'POST',
      url: enteraddonsAdmin.ajaxurl,
      data: {
        action: 'settings_data_save_action',
        data: $(this).serialize(),
        nonce: enteraddonsAdmin.nonce
      },
      beforeSend: function(){
        $saveButton.html('<div class="enteraddons-loader"></div>');
      },
      success: function( res ) {
        if( res.success ) {
          var $success = setTimeout(function () {
                $saveButton
                  .attr('disabled', true)
                  .text( 'Save Changed' );
              clearTimeout( $success );

              if( activateTab == 'extensions' ) {
                location.reload();
              }

          }, 1500);

        }

      }

    })


  } )



})(jQuery);