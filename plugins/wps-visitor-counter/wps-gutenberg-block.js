( function( blocks, components, i18n, element ) {
    var el = wp.element.createElement,
    registerBlockType = wp.blocks.registerBlockType,
    blockStyle = { backgroundColor: '#fff', color: '#000' };





registerBlockType( 'wps/shortcodeblock', {

    title: 'WPS Visitor Counter Block',

 

    icon: 'screenoptions',

 

    category: 'common',



    keywords: [ ],
    attributes: {},
 

    edit: function( props ) {

        return [ 

            el( 'p', { style: blockStyle }, 'WPS Visitor Counter Shortcode Block' ),       

        ];

    },

 

    save: function( props ) {

        return null;

    },

} );



} )(

    window.wp.blocks,

    window.wp.components,

    window.wp.i18n,

    window.wp.element,

);