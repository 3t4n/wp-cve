const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

import edit from './edit';

registerBlockType( 'custom-related-posts/related-posts', {
    title: __( 'Custom Related Posts' ),
    description: __( 'Display a list of your custom related posts.' ),
    icon: 'list-view',
    keywords: [ 'crp' ],
    category: 'widgets',
    supports: {
		html: false,
    },
    transforms: {
        from: [
            {
                type: 'shortcode',
                tag: 'custom-related-posts',
                attributes: {
                    title: {
                        type: 'string',
                        shortcode: ( { named: { title = '' } } ) => {
                            return title.replace( 'title', '' );
                        },
                    },
                    order_by: {
                        type: 'string',
                        shortcode: ( { named: { order_by = '' } } ) => {
                            return order_by.replace( 'order_by', '' );
                        },
                    },
                    order: {
                        type: 'string',
                        shortcode: ( { named: { order = '' } } ) => {
                            return order.replace( 'order', '' );
                        },
                    },
                    none_text: {
                        type: 'string',
                        shortcode: ( { named: { none_text = '' } } ) => {
                            return none_text.replace( 'none_text', '' );
                        },
                    },
                },
            },
        ]
    },
    edit: edit,
    save: (props) => {
        return null;
    },
} );