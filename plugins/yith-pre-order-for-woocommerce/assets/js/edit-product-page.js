jQuery( function( $ ) {
  // Dependencies.
  $( document ).on( 'change', '#preorder_product_data :input, .show_if_variation_pre_order :input', function () {
    var $t  = $( this ),
      name  = $t.attr( 'name' ),
      field = $t.closest( '.ywpo_options_group' ).find( '.form-field[data-deps-on="' + name + '"]' );

    if ( $t.is( ':checked' ) || $t.is( 'select' ) || $t.is( ':checkbox' ) ) {
      field.each( function( i, e ) {
        if ( $t.val() === $( e ).data( 'deps-val' ) ) {
          $( e ).slideDown( 'slow' );
        } else {
          $( e ).slideUp( 'slow' );
        }
      } );
    }
  } );

  // Availability date label and No date label dependencies for availability date mode.
  $( document ).on( 'change', 'fieldset.ywpo_availability_date_mode input', function() {
    var $override_labels         = $( this ).closest( '.ywpo_options_group' ).find( 'fieldset._ywpo_override_labels input' );
    var $availability_date_label = $( this ).closest( '.ywpo_options_group' ).find( 'fieldset._ywpo_preorder_availability_date_label, p._ywpo_preorder_availability_date_label-description' );
    var $no_date_label           = $( this ).closest( '.ywpo_options_group' ).find( 'fieldset._ywpo_preorder_no_date_label, p._ywpo_preorder_no_date_label-description' );
    if ( $( this ).is( ':checked' ) && $override_labels.is( ':checked' ) ) {
      if ( 'date' === $( this ).val() || 'dynamic' === $( this ).val() ) {
        $availability_date_label.show();
        $no_date_label.hide();
      } else if ( 'no_date' === $( this ).val() ) {
        $availability_date_label.hide();
        $no_date_label.show();
      }
    }
  } );

  // Dependencies for the override labels checkbox, connected with Availability date label and No date label dependencies.
  $( document ).on( 'change', 'fieldset._ywpo_override_labels input', function() {
    if ( $( this ).is( ':checked' ) ) {
      $( this ).closest( '.ywpo_options_group' ).find( '.ywpo_availability_date_mode input' ).trigger( 'change' );
    }
  } );

  // Initialize datetimepickers
  $( document ).on( 'ywpo_init_datetimepickers', function() {
    var now = new Date();
    $( '.ywpo_datetimepicker' ).datetimepicker( {
      defaultDate: '',
      dateFormat: 'yy-mm-dd',
      timeFormat: 'HH:mm:ss',
      showSecond: false,
      pickerTimeFormat: 'HH:mm',
      secondMax: 0,
      minDate: now,
      beforeShow: function ( input, instance ) {
        instance.dpDiv.addClass( 'yith-plugin-fw-datepicker-div yith-plugin-fw-datetimepicker-div' );
      },
      onClose: function ( selectedDate, instance ) {
        instance.dpDiv.removeClass( 'yith-plugin-fw-datepicker-div yith-plugin-fw-datetimepicker-div' );
      }
    } );
  } );

  // Initialize fields when variations are loaded.
  $( this ).on( 'woocommerce_variations_loaded', function() {
    // Initialize the TinyMCE on variation fields.
    $( '.woocommerce_variable_attributes .show_if_variation_pre_order textarea.wp-editor-area' ).each( function( index, element ) {
      _initTinyMCE( $( element ).attr( 'id' ) );
    } );

    // Initialize the rest of the fields.
    initialize_pre_order_fields();
    var need_update = $( '#variable_product_options' ).find( '.woocommerce_variations .variation-needs-update' );
    need_update.removeClass( 'variation-needs-update' );
  } );

  // Triggers the events for initializing fields and dependencies.
  function initialize_pre_order_fields() {
    $( document ).trigger( 'ywpo_init_datetimepickers' );
    $( '#preorder_product_data :input, .show_if_variation_pre_order :input' ).trigger( 'change' );
    $( 'fieldset.ywpo_availability_date_mode input' ).trigger( 'change' );
  }

  $( '#variable_product_options' ).on( 'woocommerce_variations_save_variations_button', function() {
    var ids = Object.keys( tinyMCEPreInit.mceInit );
    $.each( ids, function( key, value ) {
      if (
        value.startsWith( '_ywpo_start_date_label' )||
        value.startsWith( '_ywpo_preorder_availability_date_label' ) ||
        value.startsWith( '_ywpo_preorder_no_date_label' )
      ) {
        tinyMCE.execCommand( 'mceRemoveEditor', true, value );
        tinyMCE.execCommand( 'mceAddEditor', true, value );
      }
    }) ;
  } );

  // Initialize TinyMCE, for Variations tab.
  function _initTinyMCE( id ) {
    // get tinymce options
    let key_to_copy = '_ywpo_preorder_availability_date_label',
      mce = tinyMCEPreInit.mceInit[key_to_copy],
      qt = tinyMCEPreInit.qtInit[key_to_copy];

    // change id
    mce.selector = id;
    mce.body_class = mce.body_class.replace( key_to_copy, id );
    qt.id = id;

    tinyMCEPreInit.mceInit[id] = mce;
    tinyMCEPreInit.qtInit[id] = qt;

    tinyMCE.init( tinyMCEPreInit.mceInit[id] );
    tinyMCE.execCommand( 'mceRemoveEditor', true, id );
    tinyMCE.execCommand( 'mceAddEditor', true, id );

    quicktags( qt );
    QTags._buttonsInit();
  }

  // Initialize fields and dependencies on loading complete.
  initialize_pre_order_fields();
} );
