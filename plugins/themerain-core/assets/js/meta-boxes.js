( function( $ ) {

  'use strict';

  $( document ).ready( function() {
    themerainMetaTemplate();
    themerainMetaCondition();
    themerainMetaToggle();
    themerainMetaGroup();
    themerainMetaColor();
    themerainMetaRange();
    themerainMetaMedia();
  } );

  function themerainMetaTemplate() {
    $( '.themerain-condition-template' ).each( function() {
      var data = $( this ).attr( 'data-template' );
      var meta_box = $( this ).attr( 'data-id' );

      $( '#' + meta_box ).addClass( 'themerain-hidden' );

      wp.data.subscribe( () => {
        var newPageTemplate = wp.data.select( 'core/editor' ).getEditedPostAttribute( 'template' );

        if ( data === newPageTemplate ) {
          $( '#' + meta_box ).removeClass( 'themerain-hidden' );
        } else {
          $( '#' + meta_box ).addClass( 'themerain-hidden' );
        }
      } );

      if ( $( '#page_template' ) ) {
        if ( data === $( '#page_template' ).val() ) {
          $( '#' + meta_box ).removeClass( 'themerain-hidden' );
        }

        $( '#page_template' ).on( 'input change', function() {
          if ( data === $( this ).val() ) {
            $( '#' + meta_box ).removeClass( 'themerain-hidden' );
          } else {
            $( '#' + meta_box ).addClass( 'themerain-hidden' );
          }
        } );
      }
    } );
  }

  function themerainMetaCondition() {
    $( '.themerain-meta-field[data-condition]' ).each( function() {
      var field = $( this );
      var data = field.attr( 'data-condition' ).split( ',' );
      var input = $( '[name="' + data[0] + '"]' );

      if ( input.parent().parent().parent().hasClass( 'themerain-meta-toggle' ) ) {

        input.on( 'click', function() {
          field.toggleClass( 'themerain-hidden' );
        } );

      } else if ( input.parent().parent().hasClass( 'themerain-meta-checkbox' ) ) {

        input.on( 'click', function() {
          field.toggleClass( 'themerain-hidden' );
        } );

      } else if ( input.parent().parent().hasClass( 'themerain-meta-select' ) ) {

        input.on( 'input change', function() {
          if ( $( this ).val() == data[1] ) {
            field.removeClass( 'themerain-hidden' );
          } else {
            field.addClass( 'themerain-hidden' );
          }
        } );

      } else if ( input.parent().parent().parent().hasClass( 'themerain-meta-group' ) ) {

        input.on( 'click', function() {
          if ( $( this ).val() == data[1] ) {
            field.removeClass( 'themerain-hidden' );
          } else {
            field.addClass( 'themerain-hidden' );
          }
        } );

      }
    } );
  }

  function themerainMetaToggle() {
    $( '.themerain-meta-toggle input[type="checkbox"]' ).on( 'click', function() {
      $( this ).parent().toggleClass( 'is-checked' );
    } );
  }

  function themerainMetaGroup() {
    $( '.themerain-meta-group input[type="radio"]' ).on( 'click', function() {
      $( this ).parent().parent().find( '.button-primary' ).removeClass( 'button-primary' );
      $( this ).parent( 'label' ).addClass( 'button-primary' );
    } );
  }

  function themerainMetaColor() {
    $( '.themerain-meta-color' ).find( 'input[type="text"]' ).wpColorPicker();
  }

  function themerainMetaRange() {
    $( '.themerain-meta-range' ).find( 'input[type="range"]' ).each( function() {
      var $this = $( this );
      var $output = $this.parent().find( '.themerain-meta-range__value' );

      $this.on( 'input change', function() {
        $output.html( $this.val() );
      } );
    } );
  }

  function themerainMetaMedia() {
    $( 'body' ).on( 'click', '.themerain-meta-media__upload, .themerain-meta-media__preview', function( e ) {
      e.preventDefault();

      var button = $( this );
      var input = $( this ).parent().find( 'input' );
      var preview = $( this ).parent().find('.themerain-meta-media__preview');

      var args = {
        title: 'Select Media',
        multiple: false
      };

      var data_type = input.attr('data-type').split(',');
      if (data_type.length > 0) {
        args.library = {type: data_type};
      }

      var uploader = wp.media(args);

      uploader.on( 'select', function() {
        var attachment = uploader.state().get( 'selection' ).first().toJSON();

        button.parent().addClass( 'has-value' );
        input.attr( 'value', attachment.id );
        if (data_type == 'image') {
          $( preview ).html( '<img src="'+ attachment.sizes.medium.url +'">' );
        } else if (data_type == 'video/mp4') {
          $( preview ).html( '<div></div>' );
        }
      } );

      uploader.on('open', function(e){
        var selection = uploader.state().get('selection');
        var selected = input.val();

        if (selected) {
          selection.add(wp.media.attachment(selected));
        }
      });

      uploader.open();
    } );

    $( 'body' ).on( 'click', '.themerain-meta-media__remove', function( e ) {
      e.preventDefault();

      $( this ).parent().removeClass( 'has-value' );
      $( this ).parent().find( 'input' ).attr( 'value', '' );
    } );
  }

} )( jQuery );
