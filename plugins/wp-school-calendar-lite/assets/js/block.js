/* global WPSC_Block */

(function(wp) {
    var el = wp.element.createElement;
    var __ = wp.i18n.__;
    
    var InspectorControls = wp.blockEditor.InspectorControls;
    var SelectControl     = wp.components.SelectControl;
    var PanelBody         = wp.components.PanelBody;
    var ServerSideRender  = wp.serverSideRender; // WordPress 6.2
    
    wp.blocks.registerBlockType(
        'wp-school-calendar/wp-school-calendar', {
            title: __( 'WP School Calendar', 'wp-school-calendar' ),
            description: __( 'Display school calendar.', 'wp-school-calendar' ),
            icon: 'calendar-alt',
            category: 'widgets',
            attributes: {
                id: {
                    type: 'string'
                }
            },
            edit: function( props ) {
                return [
                    el(
                        InspectorControls,
                        {},
                        el(
                            PanelBody,
                            {
                                'title': __( 'Calendar Settings', 'wp-school-calendar' )
                            },
                            el(
                                SelectControl,
                                {
                                    label: __('Choose The Calendar', 'wp-school-calendar'),
                                    value: props.attributes.id ? parseInt(props.attributes.id) : '',
                                    onChange: function(value){
                                        props.setAttributes({
                                            id: value
                                        });
                                    },
                                    options: WPSC_Block.calendars
                                }
                            )
                        )
                    ),
                    el(ServerSideRender, {
                        block: "wp-school-calendar/wp-school-calendar",
                        attributes: props.attributes
                    })
                ];
            },
            save: function() {
                return null;
            }
        }
    );

})(
    window.wp
);