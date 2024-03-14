/**
 * Register timeline block. 
 */
import Inspector from './components/inspector';
import Edit from './components/edit';
import Icons from './../block-base/icons';

const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { registerBlockType } = wp.blocks;

// timeline attributes
const timelineAttributes = {
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
        default: 'post'
    },
    postCategory: {
        'type'      : 'string',
        'default'   : ''
    },
    postCount: {
        'type'      : 'integer',
        'default'   : 4
    },
    timelineRepeater: {
        type: 'array',
        default: [
            { 
                timeline_image: '',
                timeline_date: '',
                timeline_title: escapeHTML( __( 'Highlight News', 'wp-magazine-modules-lite' ) ),
                timeline_desc: escapeHTML( __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', 'wp-magazine-modules-lite' ) )
            }
        ],
        items: {
            type: 'object'
        }
    },
    thumbOption: {
        type: 'boolean',
        default: true
    },
    dateOption: {
        type: 'boolean',
        default: true
    },
    titleOption: {
        type: 'boolean',
        default: true
    },
    contentOption: {
        type: 'boolean',
        default: true
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
    dateTextAlign: {
        type: 'string',
        default: 'left'
    },
    dateFontFamily: {
        type: 'string',
        default: 'Yanone Kaffeesatz'
    },
    dateFontWeight: {
        type: 'string',
        default: '700'
    },
    dateFontSize: {
        type: 'number',
        default: 28
    },
    dateFontStyle: {
        type: 'string',
        default: 'normal'
    },
    dateTextTransform: {
        type: 'string',
        default: 'capitalize'
    },
    dateTextDecoration: {
        type: 'string',
        default: 'none'
    },
    dateBackgroundColor: {
        type: 'string',
        default: '#333333'
    },
    dateFontColor: {
        type: 'string',
        default: '#333333'
    },
    dateHoverColor: {
        type: 'string',
        default: '#f47e00'
    },
    datelineHeight: {
        type: 'number',
        default: 1.5
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
        default: 'Yanone Kaffeesatz'
    },
    descFontWeight: {
        type: 'string',
        default: '700'
    },
    descFontSize: {
        type: 'number',
        default: 28
    },
    descFontStyle: {
        type: 'string',
        default: 'normal'
    },
    descTextTransform: {
        type: 'string',
        default: 'capitalize'
    },
    descTextDecoration: {
        type: 'string',
        default: 'none'
    },
    descFontColor: {
        type: 'string',
        default: '#333333'
    },
    descHoverColor: {
        type: 'string',
        default: '#f47e00'
    },
    desclineHeight: {
        type: 'number',
        default: 1.5
    },
    blockDynamicCss: {
        type: 'string'
    }
}

registerBlockType( 'wpmagazine-modules/timeline', {
    title: escapeHTML( __( 'WP Magazine Timeline', 'wp-magazine-modules-lite' ) ),
    description: escapeHTML( __( 'Display timeline posts in order', 'wp-magazine-modules-lite' ) ),
    icon: {
        background: '#fff',
        foreground: 'rgba(212,51,93,1)',
        src: Icons.Timeline,
    },
    keywords: [
        escapeHTML( __( 'timeline', 'wp-magazine-modules-lite' ) ),
        escapeHTML( __( 'highlights', 'wp-magazine-modules-lite' ) ),
    ],
    category: 'wpmagazine-modules-lite',
    attributes: timelineAttributes,
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