jQuery( function ( $ ) {
  /**
   * All delete links get a confirmation alert
   */
  $( '.inventorywrap a.delete' ).click(
    function () {
      var prompt     = wpinventory.delete_prompt;
      var prompt_qm  = wpinventory.prompt_qm;
      var name       = $( this ).attr( "data-name" );
      var alt_prompt = $( this ).attr( "data-prompt" );
      if ( alt_prompt ) {
        prompt    = alt_prompt;
        prompt_qm = '';
      }

      if ( typeof name !== "undefined" && name !== "" ) {
        prompt += ' ' + wpinventory.delete_named + '"' + name + '"' + prompt_qm;
      } else if ( prompt_qm ) {
        prompt += ' ' + wpinventory.delete_general + prompt_qm;
      }

      return confirm( prompt );
    }
  );

  $( ".inventory_wrap form.filter select" ).change(
    function () {
      this.form.submit();
    }
  );

  /**
   * Light visual feedback on the "is used" setting for Manage Labels screen
   */
  $( 'input.is_used' ).change(
    function () {
      toggleIsUsed( this );
    }
  ).each(
    function () {
      toggleIsUsed( this );
    }
  );

  function toggleIsUsed( el ) {
    if ( $( el ).is( ":checked" ) ) {
      $( el ).closest( "tr" ).removeClass( "not_used" );
    } else {
      $( el ).closest( "tr" ).addClass( "not_used" );
    }
  }

  $( 'a.wpim_show_debug' ).click(
    function () {
      $( this ).next().slideDown();
    }
  );

  var hash = window.location.hash.substr( 1 );
  if ( hash ) {
    hash = '.' + hash;
  }

  $( '.wpim-toggle-section' ).on( 'click', function () {
    var section = $( this ).data( 'section' );
    $( section ).slideToggle();
  } ).each( function () {
    var section = $( this ).data( 'section' );
    if ( hash == section ) {
      $( section ).show();
      var top = $( section ).offset().top;
      $( 'html, body' ).animate( { scrollTop: top }, 250 );
    } else {
      $( section ).hide();
    }
  } );

  $( 'form#inventory_item_form' ).on( 'submit', function ( event ) {
    var nameInput   = $( this ).find( '[name="inventory_name"]' );
    var numberInput = $( this ).find( '[name="inventory_number"]' );
    var name        = nameInput.val();
    var number      = numberInput.val();
    if ( !name.trim() && !number.trim() ) {
      event.preventDefault();
      var nameLabel   = nameInput.closest( 'tr' ).find( 'label' ).text();
      var numberLabel = numberInput.closest( 'tr' ).find( 'label' ).text();
      $( '.inventorywrap' ).find( '.inventory-error' ).remove();
      var message = wpinventory.save_error;
      message     = message.replace( '%s', nameLabel );
      message     = message.replace( '%s', numberLabel );
      $( '.inventorywrap form' ).before( '<div class="error inventory-error"><p>' + message + '</p>' );
    }
  } );

  var supportOriginal;

  $( 'form#inventory_support' ).on( 'submit', function ( event ) {
    event.preventDefault();

    supportOriginal  = $( 'input[type="submit"]' ).val();
    var messageInput = $( this ).find( '[name="message"]' );
    var email        = $( this ).find( '[name="email"]' ).val();
    var message      = messageInput.val();
    var nonce        = $( this ).find( '[name="_support_nonce"]' ).val();

    if ( !message || message.length < 10 ) {
      messageInput.css( 'border', '2px solid red' );
      messageInput.closest( 'p' ).find( 'p.wpim_error' ).remove();
      messageInput.closest( 'p' ).append( '<p class="wpim_notice wpim_error">' + wpinventory.support_message_error + '</p>' );
      return;
    }

    $( 'input[type="submit"]' ).val( wpinventory.support_sending ).attr( 'disabled', true );

    $.ajax( wpinventory.ajaxUrl,
      {
        type: 'POST',
        data: {
          action: 'wpim_send_support',
          email: email,
          message: message,
          nonce: nonce
        },
        success: function ( data ) {
          data = $.parseJSON( data );
          if ( data.success ) {
            $( 'form#inventory_support' ).before( '<div class="wpim_notice wpim_success">' + data.messages + '</div>' );
            $( 'form#inventory_support' ).slideUp();
            checkWPIMVersions( data );
          } else {
            $( 'input[type="submit"]' ).val( supportOriginal ).removeAttr( 'disabled' );
          }
        }
      }
    );
  } );

  if ( $( '.inventorywrap .lists' ).length ) {
    $( ".inventorywrap .lists" ).css( { "min-height": $( "div.list_available" ).height() } );

    $( ".list_selected h3" ).on( "click change", function () {
      $( ".lists .list" ).each( function () {
        $( this ).removeClass( "current_selection" );
      } );

      $( this ).closest( ".lists" ).addClass( "has_selection" );
      $( this ).closest( ".list" ).toggleClass( "current_selection" );
    } );
  }

  function checkWPIMVersions( data ) {
    //console.log( data );
    var versions = data.version_info;
    $.each( versions, function ( i, v ) {
      console.log( v );
    } );
  }

  /**
   * Initialize the class for inventory upload
   */
  WPInventoryUpload.init();
} );

/**
 * The image / media upload class
 */
var WPInventoryUpload = {};

WPInventoryUpload = (function () {
  var $;
  var container;
  var type;
  var word;
  var word_singular;
  var formfield;
  var custom_media = true;
  var button_update;
  var send_attachment;

  function inventoryUpload( el ) {
    // Using the count value as unique identifier
    formfield                 = $( el ).attr( 'data-count' );
    custom_media              = true;
    var _orig_send_attachment = wp.media.editor.send.attachment;
    var _orig_editor_insert   = wp.media.editor.insert;
    var _orig_string_image    = wp.media.string.image;

    // This function is required to return a "clean" URL for the "Insert from URL"
    wp.media.string.image = function ( embed ) {
      if ( custom_media ) {
        send_attachment = false;
        return embed.url;
      }

      return _orig_string_image.apply( embed );
    };

    // This function handles passing the URL in for the "Insert from URL"
    wp.media.editor.insert = function ( html ) {
      if ( custom_media ) {
        if ( send_attachment ) {
          return;
        }

        renderUpload( formfield, html );
        return;
      }

      return _orig_editor_insert.apply( html );
    };

    // This function handles passing in the image url from an uploaded image
    wp.media.editor.send.attachment = function ( props, attachment ) {
      send_attachment = true;
      if ( custom_media ) {
        formfield = renderUpload( formfield, attachment.url );
      } else {
        return _orig_send_attachment.apply( this, [props, attachment] );
      }
      clearInterval( button_update );
    };

    wp.media.editor.open( 1 );

    button_update = setInterval( function () {
        $( 'div.media-modal .media-button-insert' ).html( wpinventory.insert_button );
      }
      , 300 );
    return false;
  }

  /* function to load the image url into the correct input box */
  function renderUpload( field, src ) {
    if ( type == 'image' ) {
      return renderImage( field, src );
    } else {
      return renderMedia( field, src );
    }
  }

  function renderImage( field, src ) {
    if ( container.find( "img#inventory-image-" + field ).length <= 0 ) {
      container.find( "div#inventory-div-" + field ).prepend( '<img id="inventory-image-' + field + '" src="" />' );
      container.find( "div#inventory-div-" + field ).prepend( '<a class="delete" href="javascript:void(0);">X</a>' );
      container.find( 'div#inventory-div' ).attr( 'data-count', field );
    }
    container.find( "img#inventory-image-" + field ).attr( "src", src );
    container.find( "input#inventory-field-" + field ).val( src );
    container.find( "a#inventory-link-" + field ).html( "Change " + word_singular );
    return ensureNewImage();
  }

  function renderMedia( field, src ) {
    // Always add new, so just get count here
    field = (container.find( 'div.mediacontainer' ).length);
    container.append( '<div class="mediacontainer mediawrap" id="inventory-media-' + field + '" data-count="' + field + '"></div>' );
    var html = '<p><label>' + wpinventory.title_label + ':</label><input type="text" class="widefat" name="media_title[' + field + ']" value="" /></p>';
    html += '<p class="media_url"><label>' + wpinventory.url_label + '</label><span>' + src + '</span></p><a href="javascript:void(0);" data-count="' + field + '" class="delete">X</a>';
    html += '<input type="hidden" name="media[' + field + ']" value="' + src + '" />';
    container.find( 'div#inventory-media-' + field ).html( html );
    updateOrder();
    return field;
  }

  function ensureNewImage() {
    var empty  = 0;
    var count  = 0;
    var retval = 0;
    container.find( "div.imagecontainer" ).each(
      function () {
        count++;
        if ( $( this ).find( "img" ).length <= 0 ) {
          empty = 1;
        }
      }
    );
    if ( !empty ) {
      var td   = container.find( "div.imagecontainer" ).parents( "div.mediasortable" );
      var html = '<div class="imagewrapper mediawrap" data-count="' + count + '">';
      html += '<div class="imagecontainer" id="inventory-div-' + count + '"></div>';
      html += '<a href="media-upload.php?post_id=0&type=image&TB_iframe=1&width=640&height=673" data-count="' + count + '" id="inventory-link-' + count + '" class="wpinventory-upload">Add New Image</a>';
      html += '<input type="hidden" name="image[' + count + ']" value="" id="inventory-field-' + count + '" />';
      html += '</div>';
      td.append( html );
      /* $('#inventory-link-' + count).click(function() {
       inventoryUpload($(this));
       return false;
       }); */
      retval = count;
    }
    updateOrder();
    return retval;
  }

  function removeMedia( el ) {
    container = $( el ).closest( '.mediawrap' );
    container.addClass( 'media-deleted' );
    type = $( el ).closest( 'td' ).find( '.media-container' ).attr( 'data-type' );
    container.fadeOut( 500, function () {
      $( this ).remove();
      if ( type == 'image' ) {
        ensureNewImage();
      } else {
        updateOrder();
      }
    } );
  }

  function updateOrder() {
    $( '.mediasortable' ).each( function () {
      var str   = "";
      var count = 0;
      var otype = $( this ).attr( 'data-type' );
      $( this ).find( ".mediawrap" ).each( function () {
        count++;
        str += $( this ).attr( "data-count" ) + ",";
      } );
      jQuery( "input#" + otype + "sort" ).val( str );
      if ( count > 1 ) {
        if ( $( this ).next( ".sortnotice" ).length <= 0 ) {
          word = ($( this ).attr( "data-type" ) == 'media') ? wpinventory.media_label : wpinventory.image_label;
          $( this ).after( '<p class="sortnotice">Drag and drop ' + word + ' to change sort order</p>' );
        }
      }
    } );
  }

  return {
    init: function () {
      $ = jQuery;
      // Media upload functionality. Use live method, because add / edit can be created dynamically
      $( document ).on( 'click', '.wpinventory-upload', function () {
        // Set the container element to ensure actions take place within container
        container     = $( this ).closest( 'td' ).find( '.media-container' );
        // Set the type.  media or image
        type          = container.attr( "data-type" );
        word          = (type == 'media') ? wpinventory.media_label : wpinventory.image_label;
        word_singular = (type == 'media') ? wpinventory.media_label_singular : wpinventory.image_label_singular;
        inventoryUpload( $( this ) );
        return false;
      } );

      $( document ).on( 'click', '.media-container .delete', function () {
        removeMedia( $( this ) );
      } );

      if ( $( "div.mediasortable" ).length > 0 ) {
        $( "div.mediasortable" ).sortable( {
          items: '.mediawrap',
          helper: 'clone',
          forcePlaceholderSize: true,
          stop: function () {
            updateOrder();
          }
        } );
        updateOrder();
      }
    }
  }

})();

/**
 * Utility function for getting query parameters.
 * @param q
 * @returns
 */
function $_GET( q ) {
  var query = window.location.search.substring( 1 );
  var vars  = query.split( "&" );
  for ( var i = 0; i < vars.length; i++ ) {
    var pair = vars[ i ].split( "=" );
    if ( pair[ 0 ] == q ) {
      return unescape( pair[ 1 ] );
    }
  }
  return false;
}



