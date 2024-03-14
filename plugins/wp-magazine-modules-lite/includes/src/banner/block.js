/**
 * Register banner layout block. 
 */
import Inspector from './components/inspector';
import Edit from './components/edit';
import Icons from './../block-base/icons';

const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { registerBlockType } = wp.blocks;

// block attributes
const bannerAttributes = {
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
    contentType: {
        type: 'string',
        default: 'page'
    },
    bannerPage: {
        type: 'string',
        default: ''
    },
    bannerImage: {
        type: 'string',
        default: ''
    },
    titleOption: {
        type: 'boolean',
        default: true
    },
    bannerTitle: {
        type: 'string',
        default: escapeHTML( __( 'WP Magazine Modules Lite', 'wp-magazine-modules-lite' ) )
    },
    bannerTitleLink: {
        type: 'string',
        default: '#'
    },
    descOption: {
        type: 'boolean',
        default: true
    },
    bannerDesc: {
        type: 'string',
        default: escapeHTML( __( 'Complete Magazine Plugin', 'wp-magazine-modules-lite' ) )
    },
    button1Option: {
        type: 'boolean',
        default: true
    },
    button1Label: {
        type: 'string',
        default: escapeHTML( __( 'Button One', 'wp-magazine-modules-lite' ) )
    },
    button1Link: {
        type: 'string',
        default: '#'
    },
    button2Option: {
        type: 'boolean',
        default: true
    },
    button2Label: {
        type: 'string',
        default: escapeHTML( __( 'Button Two', 'wp-magazine-modules-lite' ) )
    },
    button2Link: {
        type: 'string',
        default: '#'
    },
    permalinkTarget: {
        type: 'string',
        default: '_blank'
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
        default : '#f47e00'
    },
    titleTextAlign: {
        type: 'string',
        default: 'left'
    },
    titleFontFamily: {
        type: 'string',
        default: 'Roboto'
    },
    titleFontWeight: {
        type: 'string',
        default: '700'
    },
    titleFontSize: {
        type: 'number',
        default: 24
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
        default: '#D32F2F'
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
    button1TextAlign: {
        type: 'string',
        default: 'left'
    },
    button1FontFamily: {
        type: 'string',
        default: 'Roboto'
    },
    button1FontWeight: {
        type: 'string',
        default: '400'
    },
    button1FontSize: {
        type: 'number',
        default: 15
    },
    button1TextTransform: {
        type: 'string',
        default: 'none'
    },
    button1FontColor: {
        type: 'string',
        default: '#ffffff'
    },
    button1HoverColor: {
        type: 'string',
        default: '#ffffff'
    },
    button1BackgroundColor: {
        type: 'string',
        default: '#D32F2F'
    },
    button1BackgroundHoverColor: {
        type: 'string',
        default: '#A22121'
    },
    button1PaddingTop: {
        type: 'string',
        default: '2'
    },
    button1PaddingRight: {
        type: 'string',
        default: '10'
    },
    button1PaddingBottom: {
        type: 'string',
        default: '2'
    },
    button1PaddingLeft: {
        type: 'string',
        default: '10'
    },
    button1BorderType: {
        type: 'string',
        default: 'solid'
    },
    button1BorderWeight: {
        type: 'string',
        default: '1'
    },
    button1BorderColor: {
        type: 'string',
        default: '#D32F2F'
    },
    button1BorderHoverColor: {
        type: 'string',
        default: '#A22121'
    },
    button2TextAlign: {
        type: 'string',
        default: 'left'
    },
    button2FontFamily: {
        type: 'string',
        default: 'Roboto'
    },
    button2FontWeight: {
        type: 'string',
        default: '400'
    },
    button2FontSize: {
        type: 'number',
        default: 15
    },
    button2TextTransform: {
        type: 'string',
        default: 'none'
    },
    button2FontColor: {
        type: 'string',
        default: '#ffffff'
    },
    button2HoverColor: {
        type: 'string',
        default: '#ffffff'
    },
    button2BackgroundColor: {
        type: 'string',
        default: '#D32F2F'
    },
    button2BackgroundHoverColor: {
        type: 'string',
        default: '#A22121'
    },
    button2PaddingTop: {
        type: 'string',
        default: '2'
    },
    button2PaddingRight: {
        type: 'string',
        default: '10'
    },
    button2PaddingBottom: {
        type: 'string',
        default: '2'
    },
    button2PaddingLeft: {
        type: 'string',
        default: '10'
    },
    button2BorderType: {
        type: 'string',
        default: 'solid'
    },
    button2BorderWeight: {
        type: 'string',
        default: '1'
    },
    button2BorderColor: {
        type: 'string',
        default: '#D32F2F'
    },
    button2BorderHoverColor: {
        type: 'string',
        default: '#A22121'
    },
    blockDynamicCss: {
        type: 'string'
    },
    blockDynamicCss: {
        type: 'string'
    }
}

registerBlockType( 'wpmagazine-modules/banner', {
    title: escapeHTML( __( 'WP Magazine Banner', 'wp-magazine-modules-lite' ) ),
    description: escapeHTML( __( 'Display banner section', 'wp-magazine-modules-lite' ) ),
    icon: {
        background: '#fff',
        foreground: 'rgba(212,51,93,1)',
        src: Icons.Banner,
    },
    keywords: [
        escapeHTML( __( 'banner', 'wp-magazine-modules-lite' ) ),
        escapeHTML( __( 'image', 'wp-magazine-modules-lite' ) ),
    ],
    category: 'wpmagazine-modules-lite',
    attributes: bannerAttributes,
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