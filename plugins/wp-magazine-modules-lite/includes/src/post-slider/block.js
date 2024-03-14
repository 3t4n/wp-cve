/**
 * Register post slider block.
 */
import Inspector from './components/inspector';
import Edit from './components/edit';
import Icons from './../block-base/icons';

const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { registerBlockType } = wp.blocks;

// block attributes
const postsliderAttributes = {
    align: {
        type: 'string',
        default: 'wide'
    },
    blockID: {
        type: 'string',
        default: ''
    },
    align: {
        type: 'string',
        default: 'wide'
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
    socialShareOption: {
        type: 'boolean',
        default: true
    },
    socialShareLayout: {
        type    : 'string',
        default : 'default'
    },
    sliderposttype: {
        type: 'string',
        default: 'post'
    },
    sliderpostCategory: {
        type: 'array',
        default: [],
        items: {
            type: 'integer'
        }
    },
    sliderpostCount: {
        type: 'integer',
        default: 10
    },
    slidercontentOption: {
        type: 'boolean',
        default: false
    },
    slidercontentType: {
        type: 'string',
        default: 'excerpt'
    },
    sliderwordCount: {
        type: 'integer',
        default: 10
    },
    sliderbuttonOption: {
        'type'  : 'boolean',
        'default': true
    },
    sliderbuttonLabel: {
        type: 'string',
        default: escapeHTML( __( 'Read more', 'wp-magazine-modules-lite' ) )
    },
    sliderorderBy: {
        type: 'string',
        default: 'date'
    },
    sliderorder: {
        type: 'string',
        default: 'desc'
    },
    sliderdateOption: {
        type: 'boolean',
        default: false
    },
    sliderauthorOption: {
        type: 'boolean',
        default: false
    },
    slidercategoryOption: {
        type: 'boolean',
        default: true
    },
    slidercategoriesCount: {
        type: 'integer',
        default: 2
    },
    slidertagsOption: {
        type: 'boolean',
        default: false
    },
    slidertagsCount: {
        type: 'integer',
        default: 2
    },
    slidercommentOption: {
        type: 'boolean',
        default: false
    },
    permalinkTarget: {
        type: 'string',
        default: '_blank'
    },
    carouselType: {
        type: 'boolean',
        default: false
    },
    carouselDots: {
        type: 'boolean',
        default: false
    },
    carouselControls: {
        type: 'boolean',
        default: true
    },
    carouselAuto: {
        type: 'boolean',
        default: true
    },
    carouselLoop: {
        type: 'boolean',
        default: true
    },
    carouselSpeed: {
        type: 'string',
        default: '2500'
    },
    carouselAutoplaySpeed: {
        type: 'string',
        default: '4500'
    },
    fallbackImage: {
        type: 'string',
    },
    blockLayout: {
        type: 'string',
        default: 'layout-default'
    },
    postFormatIcon: {
        type: 'boolean',
        default: false
    },
    postMetaIcon: {
        type: 'boolean',
        default: true
    },
    postButtonIcon: {
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
    metaTextAlign: {
        type: 'string',
        default: 'left'
    },
    metaFontFamily: {
        type: 'string',
        default: 'Roboto'
    },
    metaFontWeight: {
        type: 'string',
        default: '400'
    },
    metaFontSize: {
        type: 'number',
        default: 14
    },
    metaFontStyle: {
        type: 'string',
        default: 'normal'
    },
    metaTextTransform: {
        type: 'string',
        default: 'capitalize'
    },
    metaTextDecoration: {
        type: 'string',
        default: 'none'
    },
    metaFontColor: {
        type: 'string',
        default: '#434343'
    },
    metaHoverColor: {
        type: 'string',
        default: '#f47e00'
    },
    metalineHeight: {
        type: 'number',
        default: 1.8
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
    buttonTextAlign: {
        type: 'string',
        default: 'left'
    },
    buttonFontFamily: {
        type: 'string',
        default: 'Roboto'
    },
    buttonFontWeight: {
        type: 'string',
        default: '400'
    },
    buttonFontSize: {
        type: 'number',
        default: 15
    },
    buttonTextTransform: {
        type: 'string',
        default: 'none'
    },
    buttonFontColor: {
        type: 'string',
        default: '#3b3b3b'
    },
    buttonHoverColor: {
        type: 'string',
        default: '#3b3b3b'
    },
    buttonBackgroundColor: {
        type: 'string',
        default: 'transparent'
    },
    buttonBackgroundHoverColor: {
        type: 'string',
        default: '#f47e00'
    },
    buttonPaddingTop: {
        type: 'string',
        default: '2'
    },
    buttonPaddingRight: {
        type: 'string',
        default: '10'
    },
    buttonPaddingBottom: {
        type: 'string',
        default: '2'
    },
    buttonPaddingLeft: {
        type: 'string',
        default: '10'
    },
    buttonBorderType: {
        type: 'string',
        default: 'solid'
    },
    buttonBorderWeight: {
        type: 'string',
        default: '1'
    },
    buttonBorderColor: {
        type: 'string',
        default: 'transparent'
    },
    buttonBorderHoverColor: {
        type: 'string',
        default: '#f47e00'
    },
    blockDynamicCss: {
        type: 'string'
    },
    blockGooglefonts: {
        type: 'string'
    }
}

registerBlockType( 'wpmagazine-modules/post-slider', {
    title: escapeHTML( __( 'WP Magazine Post Slider', 'wp-magazine-modules-lite' ) ),
    description: escapeHTML( __( 'Post collection with post slider', 'wp-magazine-modules-lite' ) ),
    icon: {
        background: '#fff',
        foreground: 'rgba(212,51,93,1)',
        src: Icons.Slider,
    },
    keywords: [
        escapeHTML( __( 'slider', 'wp-magazine-modules-lite' ) )
    ],
    category: 'wpmagazine-modules-lite',
    attributes: postsliderAttributes,
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