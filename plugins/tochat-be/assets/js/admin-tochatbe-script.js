( function( $ ) {
    "use strict";

    $( document ).ready( function() {

        function dynamic_agent_type_fields() {
            var type = $( '[name="agent_type"]:checked' ).val();
            
            if ( 'number' === type ) {
                $( '[name="agent_number"]' ).closest( 'tr' ).show();
                $( '[name="agent_group_id"]' ).closest( 'tr' ).hide();
                $( '[name="pre_defined_message"]' ).closest( 'tr' ).show();
            } else {
                $( '[name="agent_number"]' ).closest( 'tr' ).hide();
                $( '[name="agent_group_id"]' ).closest( 'tr' ).show();
                $( '[name="pre_defined_message"]' ).closest( 'tr' ).hide();
            }
        }

        $( '.tochatbe-color-picker' ).wpColorPicker();
        $( '.tochatbe-select2' ).select2();

        $( '.timepicker' ).timepicker({
            timeFormat: 'HH:mm:ss',
            interval: 30,
        });

        $( '#tochatbe-generate-shortcode' ).on( 'click', function( e ) {
            e.preventDefault();
            
            var bgColor             = jQuery( '#tochatbe-button-bg-color' ).val();
            var textColor           = jQuery( '#tochatbe-button-text-color' ).val();
            var text                = jQuery( '#tochatbe-button-text' ).val();
            var agantNumber         = jQuery( '#tochatbe-agent-number' ).val();
            var preDefinedMessgae   = jQuery( '#tochatbe-pre-defined-message' ).val();

            var shortcode = '';
            shortcode += '[tochatbe_whatsapp ';
            shortcode += 'bg_color="' + bgColor + '" ';
            shortcode += 'text_color="' + textColor + '" ';
            shortcode += 'text="' + text + '" ';
            shortcode += 'number="' + agantNumber + '" ';
            shortcode += 'message="' + preDefinedMessgae + '" ';
            shortcode += ']';

            jQuery( '#tochatbe-shortcode' ).empty();
            jQuery( '#tochatbe-shortcode' ).html( '<textarea style="height:120px; width:300px;">' + shortcode + '</textarea>' );
        } );

        $( '[name="agent_type"]' ).on( 'click', function() {
            dynamic_agent_type_fields();
        } );

        dynamic_agent_type_fields();

    } ); // Document ready end.

} )( jQuery );