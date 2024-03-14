/**
 * Equal Heights Admin JS
 *
 * @since  1.0.0
 */
( function( $ ) {
    'use strict';

    $( document ).ready( function() {

        // First, if there is only one row, disable the 'Remove' button.
        doRemoveButtonCheck();

        /**
         * Add an element group.
         *
         * @since  1.0.0
         */
        $( '.add-group' ).on( 'click', function() {

            // Disable buttons to prevent wonkiness during fade.
            disableButtons();

            // Get the last settings row.
            var $lastRow = $(this).prev( 'table' ).find( 'tr' ).last();
            var $newRow = $lastRow.clone( true )
            var $input = $newRow.find( 'input' );

            // Get index for new row.
            var newIndex = Number( $input.attr( 'data-index' ) ) + 1;

            // Increment label text.
            $newRow.find( '.index-number' ).text( newIndex );

            // Increment input name index.
            $input.attr( 'name', function(index, name) {

                return name.replace(/\[(\d+)\]/, function(fullMatch, n) {
                    return '[' + newIndex.toString() + ']';
                });

            });

            // Increment input data attribute.
            $input.attr( 'data-index', newIndex);

            // Remove copied value from new input.
            $input.val( function() {
                if ( $(this).attr( 'id' ).indexOf( 'breakpoint' ) >= 0 ) {
                    return 768;
                } else {
                    return '';
                }
            })

            // Insert new row at end of list.
            $newRow.hide().insertAfter( $lastRow ).fadeIn( 800, function() {

                // Enable buttons again to prevent wonkiness during fade.
                enableButtons();

            });

        });

        /**
         * Remove an element group.
         *
         * @since  1.0.0
         */
        $( '.remove-group' ).on( 'click', function() {

            // Disable buttons to prevent wonkiness during fade.
            disableButtons();

            // Get the row containing the current setting.
            var $currentRow = $(this).parents( 'tr' );

            // Remove original row.
            $currentRow.fadeOut( 800, function() {
                updateSubsequentRows();
                $currentRow.remove();

                // Enable buttons again to prevent wonkiness during fade.
                enableButtons();

            });

            function updateSubsequentRows( ) {
                // Change index of all subsequent settings rows.
                var $subsequentRows = $currentRow.nextAll( 'tr' );
                $subsequentRows.each( function() {

                    var $row = $(this);
                    var $input = $row.find( 'input' )

                    // Get new index for row.
                    var newIndex = Number( $input.attr( 'data-index' ) ) - 1;

                    // Increment label text.
                    $row.find( '.index-number' ).text( newIndex );

                    // Decrement setting index.
                    $row.find( 'input' ).attr( 'name', function(index, name) {
                        return name.replace(/\[(\d+)\]/, function(fullMatch, n) {
                            var newIndex = Number(n) - 1;
                            return '[' + newIndex.toString() + ']';
                        });
                    });

                    // Decrement input data attribute.
                    $input.attr( 'data-index', newIndex);

                });

            }

        });

    });

    // Disable all buttons.
    function disableButtons() {
        disableAddButtons();
        disableRemoveButtons();
    }

    // Disable only 'Add' buttons.
    function disableAddButtons() {
        $( '.add-group' ).attr( 'disabled', true );
    }

    // Disable only 'Remove' buttons.
    function disableRemoveButtons() {
        $( '.remove-group' ).attr( 'disabled', true );
    }

    // Enable all buttons.
    function enableButtons() {
        enableAddButtons();
        enableRemoveButtons();
    }

    // Enable only 'Add' buttons.
    function enableAddButtons() {
        $( '.add-group' ).attr( 'disabled', false );
    }

    // Enable only 'Remove' buttons.
    function enableRemoveButtons() {
        $( '.remove-group' ).attr( 'disabled', false );
        doRemoveButtonCheck();
    }

    // Disable remove buttons if there is only one left.
    function doRemoveButtonCheck() {
        if ( $( 'button.remove-group' ).length <= 1 ) {
            disableRemoveButtons();
        }
    }

})( jQuery );
