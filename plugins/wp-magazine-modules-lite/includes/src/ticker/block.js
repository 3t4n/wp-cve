/**
 * Register banner layout block. 
 */
import Inspector from './components/inspector';
import Edit from './components/edit';
import Icons from './../block-base/icons';

const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { registerBlockType } = wp.blocks;

// ticker attributes
const tickerAttributes = {
    align: {
        type: 'string',
        default: 'wide'
    },
    blockID: {
        type: 'string',
        default: ''
    },
    blockTitle: {
        type: 'string',
        default: escapeHTML( __( 'Block Title', 'wp-magazine-modules-lite' ) )
    },
    blockTitleLayout: {
        type: 'string',
        default: 'default'
    },
    blockTitleAlign : {
        type : 'string',
        default : 'left'
    },
    tickerCaption: {
        type: 'string',
        default: escapeHTML( __( 'Highlights', 'wp-magazine-modules-lite' ) )
    },
    contentType: {
        type: 'string',
        default: 'post'
    },
    postCategory: {
        'type'      : 'string',
        'default'   : ''
    },
    postCount: {
        'type'      : 'integer',
        'default'   : 10
    },
    tickerRepeater: {
        type: 'array',
        default: [
            {
                ticker_image: '',
                ticker_title: escapeHTML( __( 'Highlight News', 'wp-magazine-modules-lite' ) )
            }
        ],
        items: {
            type: 'object'
        }
    },
    marqueeDirection: {
        type: 'string',
        default: 'left'
    },
    marqueeDuration: {
        type: 'integer',
        default: 80000
    },
    marqueeStart: {
        type: 'integer',
        default: 1000
    },
    blockLayout: {
        type: 'string',
        default: 'layout-default'
    },
    blockPrimaryColor: {
        type: 'string',
        default: '#D32F2F'
    },
    blockHoverColor: {
        type: 'string',
        default: '#A22121'
    },
    typographyOption: {
        type: 'boolean',
        default: true
    },
    blockTitleFontFamily : {
        type : 'string',
        default : 'Roboto'
    },
    blockTitleFontWeight : {
        type : 'string',
        default : '700'
    },
    blockTitleFontSize : {
        type : 'number',
        default : 18
    },
    blockTitleFontStyle : {
        type : 'string',
        default : 'normal'
    },
    blockTitleTextTransform : {
        type : 'string',
        default : 'Uppercase'
    },
    blockTitleTextDecoration : {
        type : 'string',
        default : 'none'
    },
    blockTitleColor : {
        type : 'string',
        default : '#3b3b3b'
    },
    blockTitleLineHeight : {
        type : 'number',
        default : 1.5
    },
    blockTitleBorderColor: {
        type : 'string',
        default : '#D32F2F'
    },
    captionTextAlign: {
        type: 'string',
        default: 'left'
    },
    captionFontFamily: {
        type: 'string',
        default: 'Roboto'
    },
    captionFontWeight: {
        type: 'string',
        default: '700'
    },
    captionFontSize: {
        type: 'number',
        default: 16
    },
    captionFontStyle: {
        type: 'string',
        default: 'normal'
    },
    captionTextTransform: {
        type: 'string',
        default: 'capitalize'
    },
    captionTextDecoration: {
        type: 'string',
        default: 'none'
    },
    captionBackgroundColor: {
        type: 'string',
        default: '#D32F2F'
    },
    captionFontColor: {
        type: 'string',
        default: '#ffffff'
    },
    captionHoverColor: {
        type: 'string',
        default: '#A22121'
    },
    captionlineHeight: {
        type: 'number',
        default: 1.5
    },
    contentTextAlign: {
        type: 'string',
        default: 'left'
    },
    contentFontFamily: {
        type: 'string',
        default: 'Roboto'
    },
    contentFontWeight: {
        type: 'string',
        default: '400'
    },
    contentFontSize: {
        type: 'number',
        default: 13
    },
    contentFontStyle: {
        type: 'string',
        default: 'normal'
    },
    contentTextTransform: {
        type: 'string',
        default: 'capitalize'
    },
    contentTextDecoration: {
        type: 'string',
        default: 'none'
    },
    contentFontColor: {
        type: 'string',
        default: '#333333'
    },
    contentHoverColor: {
        type: 'string',
        default: '#D32F2F'
    },
    contentlineHeight: {
        type: 'number',
        default: 1.5
    },
    blockDynamicCss: {
        type: 'string'
    }
}

registerBlockType( 'wpmagazine-modules/ticker', {
    title: escapeHTML( __( 'WP Magazine Ticker', 'wp-magazine-modules-lite' ) ),
    description: escapeHTML( __( 'Display ticker section', 'wp-magazine-modules-lite' ) ),
    icon: {
        background: '#fff',
        foreground: 'rgba(212,51,93,1)',
        src: Icons.Ticker,
    },
    keywords: [
        escapeHTML( __( 'ticker', 'wp-magazine-modules-lite' ) ),
        escapeHTML( __( 'highlights', 'wp-magazine-modules-lite' ) ),
    ],
    category: 'wpmagazine-modules-lite',
    attributes: tickerAttributes,
    supports: { align: ["wide","full"] },
    example: [],
    edit: props => {
        props.attributes.blockID = props.clientId
        return [
            <Inspector { ...props } />,
            <Edit { ...props } />
        ];
    },

    save: props => {
        return null;
    }
});