(function ($) {

    $(document).ready(function(){

        // alert(table_object.tab_active);

        if(table_object.tab_active == '' || table_object.tab_active == 'settings'){
            var active = "settings";
        }
        else{
            var active = table_object.tab_active;
        }

        $('div.form-section#'+active).show();

        $('.nav-tab-wrapper a').on('click', function(e){

            e.preventDefault();
            var target = $(this).data('target');

            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');

            $('div.form-section').hide();
            $('div.form-section#'+target).show();


            $('div.form-section input[name=pvtfw_variant_table_tab]').val(target);

        });

        // Sortable table header

        $('.sortable').sortable({
          items: 'li',
          cursor: 'move',
          axis: 'y',
          // handle: '.pvt-item-reorder-nav',
          scrollSensitivity: 100,
          helper: function( event, ui ) {
            ui.children().each( function() {
              $( this ).width( $( this ).width() );
            });
            // ui.css( 'left', '0' );
            return ui;
          },
          start: function( event, ui ) {
            ui.item.css( 'background-color', '#f6f6f6' );
          },
          stop: function( event, ui ) {
            ui.item.removeAttr( 'style' );
            ui.item.trigger( 'updateMoveButtons' );
          }
        });

        // Movable Table Header
        $( '.pvt-item-reorder-nav').find( '.pvt-move-up, .pvt-move-down' ).on( 'click', function() {
            var moveBtn = $( this ),
              $row    = moveBtn.closest( 'li' );
      
            moveBtn.focus();
      
            var isMoveUp = moveBtn.is( '.pvt-move-up' ),
              isMoveDown = moveBtn.is( '.pvt-move-down' );
      
            if ( isMoveUp ) {
              var $previewRow = $row.prev( 'li' );
      
              if ( $previewRow && $previewRow.length ) {
                $previewRow.before( $row );
                wp.a11y.speak( 'Moved up' );
              }
            } else if ( isMoveDown ) {
              var $nextRow = $row.next( 'li' );
      
              if ( $nextRow && $nextRow.length ) {
                $nextRow.after( $row );
                wp.a11y.speak( 'Moved down' );
              }
            }
      
            moveBtn.focus(); // Re-focus after the container was moved.
            moveBtn.closest( 'table' ).trigger( 'updateMoveButtons' );
          } );
      
          $( '.pvt-item-reorder-nav').closest( 'table' ).on( 'updateMoveButtons', function() {
            var table    = $( this ),
              lastRow  = $( this ).find( 'tbody li:last' ),
              firstRow = $( this ).find( 'tbody li:first' );
      
            table.find( '.pvt-item-reorder-nav .pvt-move-disabled' ).removeClass( 'pvt-move-disabled' )
              .attr( { 'tabindex': '0', 'aria-hidden': 'false' } );
            firstRow.find( '.pvt-item-reorder-nav .pvt-move-up' ).addClass( 'pvt-move-disabled' )
              .attr( { 'tabindex': '-1', 'aria-hidden': 'true' } );
            lastRow.find( '.pvt-item-reorder-nav .pvt-move-down' ).addClass( 'pvt-move-disabled' )
              .attr( { 'tabindex': '-1', 'aria-hidden': 'true' } );
          } );
      
          $( '.pvt-item-reorder-nav').closest( 'table' ).trigger( 'updateMoveButtons' );

          // Show/Hide table row based on parent 

          if(table_object.scroll_table_x == 'on'){
            $('tr[data-child=scrollbar-child').show();
          }
          else{
            $('tr[data-child=scrollbar-child').hide();
          }

          $('input[type=checkbox]').on('click', function(){
            var parent = $(this).data('parent');
            if($(this).is(':checked')){
                $('tr[data-child='+parent+'-child'+']').show();
            }
            else{
                $('tr[data-child='+parent+'-child'+']').hide();
            }
        });

    });
}(jQuery));