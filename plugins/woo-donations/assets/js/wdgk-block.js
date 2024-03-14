jQuery(document).ready(function ($) {
    /** Gutenberg block for Woo donation form */
    var blocks = wp.blocks;
    var element = wp.element;
    var el = element.createElement;

    blocks.registerBlockType( 'woo-donations-block/woo-donations', {
        apiVersion: 3,
        title: 'Woo Donations',
        icon: 'money-alt',
        category: 'common',
    
        edit: function ( props ) {
            return el(wp.serverSideRender, {
                block: "woo-donations-block/woo-donations"    
            });
        }
    } );
});