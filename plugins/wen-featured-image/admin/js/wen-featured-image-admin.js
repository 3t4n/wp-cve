var wfi_file_frame;
(function( $ ) {
	'use strict';

  // Replace AJAX content
  function wen_featured_image_replace_ajax_content( post_id, block_html ){

    var target_id = 'wfi-block-wrap-' + post_id;
    jQuery('#'+target_id).hide().html(block_html).fadeIn();

  }

  jQuery(document).ready(function($) {

    // Delete
    jQuery(document).on('click', 'a.wfi-btn-remove', function( event ){

      var $this = $(this);
      event.preventDefault();

      var confirmation = confirm( WFI_OBJ.lang.are_you_sure );
      if ( ! confirmation) {
        return false;
      }
      jQuery.post(
          WFI_OBJ.ajaxurl,
          {
              action : 'wfi-remove-featured-image',
              security : $this.data('security'),
              post_id : $this.data('post')
          },
          function( response ) {
            if( 1 == response.status ){
              wen_featured_image_replace_ajax_content(response.post_id, response.html);
            }
          }
      );


    });
    ///////////////////////

    // Add Handling
    jQuery(document).on('click', 'a.wfi-btn-add', function( event ){

      var $this = $(this);

      event.preventDefault();

      // Create the media frame.
      wfi_file_frame = wp.media.frames.wfi_file_frame = wp.media({
        title: jQuery( this ).data( 'uploader_title' ),
        button: {
          text: jQuery( this ).data( 'uploader_button_text' ),
        },
        library: {
            type: 'image'
        },
        multiple: false  // Set to true to allow multiple files to be selected
      });



      // When an image is selected, run a callback.
      wfi_file_frame.on( 'select', function() {
        // We set multiple to false so only get one image from the uploader
        var attachment = wfi_file_frame.state().get('selection').first().toJSON();
        jQuery.post(
            WFI_OBJ.ajaxurl,
            {
                action : 'wfi-add-featured-image',
                post_id : $this.data('post'),
                security : $this.data('security'),
                attachment_ID : attachment.id
            },
            function( response ) {
              if( 1 == response.status ){
                wen_featured_image_replace_ajax_content(response.post_id, response.html);
              }
            }
        );
        return;

      });

      // Finally, open the modal
      wfi_file_frame.open();
    }); //end add handling

    // Change Handling
    jQuery(document).on('click', 'a.wfi-btn-change', function( event ){

      var $this = $(this);

      event.preventDefault();

      // Create the media frame.
      wfi_file_frame = wp.media.frames.wfi_file_frame = wp.media({
        title: jQuery( this ).data( 'uploader_title' ),
        button: {
          text: jQuery( this ).data( 'uploader_button_text' ),
        },
        library: {
            type: 'image'
        },
        multiple: false  // Set to true to allow multiple files to be selected
      });

      wfi_file_frame.on('open', function(){
          var selection = wfi_file_frame.state().get('selection');
          var selected = $this.data('previous_attachment'); // the id of the image
          if (selected) {
              selection.add(wp.media.attachment(selected));
          }
      });

      // When an image is selected, run a callback.
      wfi_file_frame.on( 'select', function() {
        // We set multiple to false so only get one image from the uploader
        var attachment = wfi_file_frame.state().get('selection').first().toJSON();
        jQuery.post(
            WFI_OBJ.ajaxurl,
            {
                action : 'wfi-change-featured-image',
                security : $this.data('security'),
                post_id : $this.data('post'),
                attachment_ID : attachment.id
            },
            function( response ) {
              if( 1 == response.status ){
                wen_featured_image_replace_ajax_content(response.post_id, response.html);
              }
            }
        );
        return;

      });

      // Finally, open the modal
      wfi_file_frame.open();
    }); //end change handling

  });

})( jQuery );
