const { ServerSideRender, PanelBody, SelectControl }  = wp.components;

const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.editor;
const { __ } 				= wp.i18n;


/**
 * Block inspector controls options
 *
 */

// The options for the Calendars dropdown
var calendars = [];

calendars[0] = { value : 0, label : __( 'Select Calendar...', 'wp-simple-booking-calendar' ) };

for( var i = 0; i < wpsbc_calendars.length; i++ ) {

    calendars.push( { value : wpsbc_calendars[i].id, label : wpsbc_calendars[i].name } );

}


// The option for the Language dropdown
var languages = [];

languages[0] = { value : 'auto', label : __( 'Auto', 'wp-simple-booking-calendar' ) };

for( var i = 0; i < wpsbc_languages.length; i++ ) {

    languages.push( { value : wpsbc_languages[i].code, label : wpsbc_languages[i].name } );

}


// Register the block
registerBlockType( 'wp-simple-booking-calendar/single-calendar', {

    // The block's title
    title : 'Single Calendar',

    // The block's icon
    icon : 'calendar-alt',

    // The block category the block should be added to
    category : 'wp-simple-booking-calendar',

    // The block's attributes, needed to save the data
    attributes : {

        id : {
            type : 'string'
        },

        title : {
            type : 'string'
        },

        legend : {
            type : 'string'
        },

        legend_position : {
            type : 'string'
        },

        language : {
            type    : 'string',
            default : 'auto'
        }

    },

    edit : function( props ) {

        return [

            <ServerSideRender 
                block      = "wp-simple-booking-calendar/single-calendar"
                attributes = { props.attributes } />,

            <InspectorControls key="inspector">

                <PanelBody
                    title       = { __( 'Calendar', 'wp-simple-booking-calendar' ) }
                    initialOpen = { true } >

                    <SelectControl
                        value    = { props.attributes.id }
                        options  = { calendars }
                        onChange = { (new_value) => props.setAttributes( { id : new_value } ) } />

                </PanelBody>

                <PanelBody
                    title       = { __( 'Calendar Basic Options', 'wp-simple-booking-calendar' ) }
                    initialOpen = { true } >

                    <SelectControl
                        label   = { __( 'Display Calendar Title', 'wp-simple-booking-calendar' ) }
                        value   = { props.attributes.title }
                        options = {[
                            { value : 'yes', label : __( 'Yes', 'wp-simple-booking-calendar' ) },
                            { value : 'no',  label : __( 'No', 'wp-simple-booking-calendar' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { title : new_value } ) } />

                    <SelectControl
                        label   = { __( 'Display Legend', 'wp-simple-booking-calendar' ) }
                        value   = { props.attributes.legend }
                        options = {[
                            { value : 'yes', label : __( 'Yes', 'wp-simple-booking-calendar' ) },
                            { value : 'no',  label : __( 'No', 'wp-simple-booking-calendar' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { legend : new_value } ) } />

                    <SelectControl
                        label   = { __( 'Legend Position', 'wp-simple-booking-calendar' ) }
                        value   = { props.attributes.legend_position }
                        options = {[
                            { value : 'side', label : __( 'Side', 'wp-simple-booking-calendar' ) },
                            { value : 'top', label : __( 'Top', 'wp-simple-booking-calendar' ) },
                            { value : 'bottom',  label : __( 'Bottom', 'wp-simple-booking-calendar' ) }
                        ]}
                        onChange = { (new_value) => props.setAttributes( { legend_position : new_value } ) } />
                    
                    <SelectControl
                        label   = { __( 'Language', 'wp-simple-booking-calendar' ) }
                        value   = { props.attributes.language }
                        options = { languages }
                        onChange = { (new_value) => props.setAttributes( { language : new_value } ) } />

                </PanelBody>

            </InspectorControls>
        ];
    },

    save : function() {
        return null;
    }

});


jQuery( function($) {

    /**
     * Runs every 250 milliseconds to check if a calendar was just loaded
     * and if it was, trigger the window resize to show it
     *
     */
    setInterval( function() {

        $('.wpsbc-container-loaded').each( function() {

            if( $(this).attr( 'data-just-loaded' ) == '1' ) {
                $(window).trigger( 'resize' );
                $(this).attr( 'data-just-loaded', '0' );
            }

        });

    }, 250 );

});