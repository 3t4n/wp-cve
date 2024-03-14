/**
 * Register post grid block. 
 */
import Inspector from './components/inspector';
import Edit from './components/edit';
import Icons from './../block-base/icons';

const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { registerBlockType } = wp.blocks;

// block attributes
const postgridAttributes = {
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
    posttype: {
        type: 'string',
        default: 'post'
    },
    postCategory: {
        type: 'array',
        default: [],
        items: {
            type: 'integer'
        }
    },
    socialShareOption: {
        type: 'boolean',
        default: true
    },
    socialShareLayout: {
        type    : 'string',
        default : 'default'
    },
    postCount: {
        type: 'integer',
        default: 4
    },
    contentOption: {
        type: 'boolean',
        default: false
    },
    contentType: {
        type: 'string',
        default: 'excerpt'
    },
    wordCount: {
        type: 'integer',
        default: 15
    },
    buttonOption: {
        'type'  : 'boolean',
        'default': false
    },
    buttonLabel: {
        type: 'string',
        default: escapeHTML( __( 'Read more', 'wp-magazine-modules-lite' ) )
    },
    orderBy: {
        type: 'string',
        default: 'date'
    },
    order: {
        type: 'string',
        default: 'desc'
    },
    thumbOption: {
        type: 'boolean',
        default: true
    },
    titleOption: {
        type: 'boolean',
        default: true
    },
    dateOption: {
        type: 'boolean',
        default: false
    },
    authorOption: {
        type: 'boolean',
        default: false
    },
    categoryOption: {
        type: 'boolean',
        default: false
    },
    categoriesCount: {
        type: 'integer',
        default: 2
    },
    tagsOption: {
        type: 'boolean',
        default: false
    },
    tagsCount: {
        type: 'integer',
        default: 2
    },
    commentOption: {
        type: 'boolean',
        default: false
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
    postFormatIcon: {
        type: 'boolean',
        default: false
    },
    postMetaIcon: {
        type: 'boolean',
        default: false
    },
    postButtonIcon: {
        type: 'boolean',
        default: false
    },
    blockColumn: {
        type: 'string',
        default: 'two'
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
        default: '#D32F2F'
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
        default: '#ffffff'
    },
    buttonHoverColor: {
        type: 'string',
        default: '#ffffff'
    },
    buttonBackgroundColor: {
        type: 'string',
        default: '#D32F2F'
    },
    buttonBackgroundHoverColor: {
        type: 'string',
        default: '#A22121'
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
        default: '#D32F2F'
    },
    buttonBorderHoverColor: {
        type: 'string',
        default: '#A22121'
    },
    blockDynamicCss: {
        type: 'string'
    },
    blockGooglefonts: {
        type: 'string'
    }
}

registerBlockType( 'wpmagazine-modules/post-grid', {
    title: escapeHTML( __( 'WP Magazine Post Grid', 'wp-magazine-modules-lite' ) ),
    description: escapeHTML( __( 'Post collection with grid layout', 'wp-magazine-modules-lite' ) ),
    icon: {
        background: '#fff',
        foreground: 'rgba(212,51,93,1)',
        src: Icons.Grid,
    },
    keywords: [
        escapeHTML( __( 'grid', 'wp-magazine-modules-lite' ) ),
        escapeHTML( __( 'post', 'wp-magazine-modules-lite' ) )
    ],
    category: 'wpmagazine-modules-lite',
    attributes: postgridAttributes,
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