/**
 * Register category collection block.
 */
import Inspector from './components/inspector';
import Edit from './components/edit';
import Icons from './../block-base/icons';

const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { registerBlockType } = wp.blocks;

// block attributes
const categorycollectionAttributes = {
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
    blockCategories: {
        type: 'array',
        default: [],
        items: {
            type: 'integer'
        }
    },
    titleOption: {
        type: 'boolean',
        default: true
    },
    descOption: {
        type: 'boolean',
        default: false
    },
    catcountOption: {
        type: 'boolean',
        default: true
    },
    permalinkTarget: {
        type: 'string',
        default: '_blank'
    },
    fallbackImage: {
        type: 'string',
    },
    blockLayout: {
        type: 'string',
        default: 'layout-default'
    },
    blockColumn: {
        type: 'string',
        default: 'four'
    },
    postMargin: {
        type: 'boolean',
        default: true
    },
    imageSize: {
        type: 'string',
        default: 'full'
    },
    blockPrimaryColor: {
        type: 'string',
        default: '#029FB2'
    },
    blockHoverColor: {
        type: 'string',
        default: '#029FB2'
    },
    typographyOption: {
        type: 'boolean',
        default: true
    },
    blockTitleFontFamily : {
        type : 'string',
        default : 'Yanone Kaffeesatz'
    },
    blockTitleFontWeight : {
        type : 'string',
        default : '700'
    },
    blockTitleFontSize : {
        type : 'number',
        default : 32
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
        default : '#f47e00'
    },
    titleTextAlign: {
        type: 'string',
        default: 'left'
    },
    titleFontFamily: {
        type: 'string',
        default: 'Yanone Kaffeesatz'
    },
    titleFontWeight: {
        type: 'string',
        default: '700'
    },
    titleFontSize: {
        type: 'number',
        default: 28
    },
    titleFontStyle: {
        type: 'string',
        default: 'normal'
    },
    titleTextTransform: {
        type: 'string',
        default: 'capitalize'
    },
    titleTextDecoration: {
        type: 'string',
        default: 'none'
    },
    titleFontColor: {
        type: 'string',
        default: '#333333'
    },
    titleHoverColor: {
        type: 'string',
        default: '#f47e00'
    },
    titlelineHeight: {
        type: 'number',
        default: 1.5
    },
    descTextAlign: {
        type: 'string',
        default: 'left'
    },
    descFontFamily: {
        type: 'string',
        default: 'Roboto'
    },
    descFontWeight: {
        type: 'string',
        default: '400'
    },
    descFontSize: {
        type: 'number',
        default: 15
    },
    descFontStyle: {
        type: 'string',
        default: 'normal'
    },
    descTextTransform: {
        type: 'string',
        default: 'none'
    },
    descTextDecoration: {
        type: 'string',
        default: 'none'
    },
    descFontColor: {
        type: 'string',
        default: '#3b3b3b'
    },
    desclineHeight: {
        type: 'number',
        default: 2
    },
    blockDynamicCss: {
        type: 'string'
    },
    blockGooglefonts: {
        type: 'string'
    }
}

registerBlockType( 'wpmagazine-modules/category-collection', {
    title: escapeHTML( __( 'WP Magazine Category Collection', 'wp-magazine-modules-lite' ) ),
    description: escapeHTML( __( 'Default category collection', 'wp-magazine-modules-lite' ) ),
    icon: {
        background: '#fff',
        foreground: 'rgba(212,51,93,1)',
        src: Icons.CategoryCollection,
    },
    keywords: [
        escapeHTML( __( 'category', 'wp-magazine-modules-lite' ) ),
        escapeHTML( __( 'grid', 'wp-magazine-modules-lite' ) ),
        escapeHTML( __( 'carousel', 'wp-magazine-modules-lite' ) )
    ],
    category: 'wpmagazine-modules-lite',
    attributes: categorycollectionAttributes,
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