var el = wp.element.createElement;
 
var withInspectorControls = wp.compose.createHigherOrderComponent( function( BlockEdit ) {
    return function( props ) { console.log( props );

        return el(
            wp.element.Fragment,
            {},
            el(
                BlockEdit,
                props
            ),
            el(
                wp.blockEditor.InspectorControls,
                {},
                el(
                    wp.components.PanelBody,
                    {},
                    'Lightbox?'
                )
            )
        );
    };
}, 'withInspectorControls' );
 
wp.hooks.addFilter( 'editor.BlockEdit', 'my-plugin/with-inspector-controls', withInspectorControls );
