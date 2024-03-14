jQuery( document ).ready(function( $ ){

  // create range field #1.
  if( $( '#size1' ) && ObjCursor.cursor_file1 ) {
    $( '#size1' ).slider({
      min: 1,
      max: ObjCursor.cursor_file1.size > 60 ? 60 : ObjCursor.cursor_file1.size, 
      value: ObjCursor.cursor_file1.size_new,
      slide: function( event, ui ) {
        $( '#amount1' ).html( ui.value );
        $( '#cursor-size1' ).val( ui.value );
        $( '#img-adm-cursor1' ).css({
          'width': ui.value, 
          'height': 'auto'
        });
      }
    });
    $( '#amount1' ).html( ObjCursor.cursor_file1.size_new );
    $( '#cursor-size1' ).val( ObjCursor.cursor_file1.size_new );
  }


  // create range field #2.
  if( $( '#size2' ) && ObjCursor.cursor_file2 ) {
    $( '#size2' ).slider({
      min: 1,
      max: ObjCursor.cursor_file2.size > 60 ? 60 : ObjCursor.cursor_file2.size, 
      value: ObjCursor.cursor_file2.size_new,
      slide: function( event, ui ) {
        $( '#amount2' ).html( ui.value );
        $( '#cursor-size2' ).val( ui.value );
        $( '#img-adm-cursor2').css({
          'width': ui.value, 
          'height': 'auto'
        });
      }
    });
    $( '#amount2' ).html( ObjCursor.cursor_file2.size_new );
    $( '#cursor-size2' ).val( ObjCursor.cursor_file2.size_new );
  }

        
  // create range field #3.
  if( $( '#size3' ) && ObjCursor.cursor_file3 ){
    $( '#size3' ).slider({
      min: 1,
      max: ObjCursor.cursor_file3.size > 60 ? 60 : ObjCursor.cursor_file3.size, 
      value: ObjCursor.cursor_file3.size_new,
      slide: function( event, ui ) {
        $( '#amount3' ).html( ui.value );
        $( '#cursor-size3' ).val( ui.value );
        $( '#img-adm-cursor3' ).css({
          'width': ui.value, 
          'height': 'auto'
        });
      }
    });
    $( '#amount3' ).html( ObjCursor.cursor_file3.size_new );
    $( '#cursor-size3' ).val( ObjCursor.cursor_file3.size_new );
  }


  // show/hide input files #2 and #3
  $( '.subinput-togler' ).on( 'click', function(event){
    event.preventDefault();
    $( '.subinput' ).toggleClass('img-loaded');
    //$(this).next().fadeToggle();
  });


});





