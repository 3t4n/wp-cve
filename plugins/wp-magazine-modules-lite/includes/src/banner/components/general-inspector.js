/**
 * Includes the settings of general tab.
 * 
 */
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { PanelBody, TextControl, TextareaControl, SelectControl, ToggleControl, Spinner, Button } = wp.components;
const { withSelect } = wp.data
const { MediaUpload, MediaUploadCheck  } = wp.blockEditor;
const ALLOWED_MEDIA_TYPES = [ 'image' ];

class GeneralInspector extends Component {
    constructor( props ) {
        super( ...arguments )
    }

    render() {
        const { blockTitle, blockTitleLayout, contentType, bannerPage, bannerImage, titleOption, bannerTitle, bannerTitleLink, descOption, bannerDesc, button1Option, button1Label, button1Link, button2Option, button2Label, button2Link, permalinkTarget } = this.props.attributes
        const { setAttributes, pagesList } = this.props
        
        const onUpdateImage = ( newImage ) => {
            setAttributes( {
                bannerImage: newImage.url,
            } );
        };

        const onRemoveImage = () => {
            setAttributes( {
                bannerImage: undefined,
            } );
        };

        return (
            <Fragment>
                <PanelBody title={ escapeHTML( __( 'Basic Settings', 'wp-magazine-modules-lite' ) ) }>
                    <TextControl
                        label={ escapeHTML( __( 'Block Title', 'wp-magazine-modules-lite' ) ) }
                        value={ blockTitle }
                        placeholder={ escapeHTML( __( 'Add title here..', 'wp-magazine-modules-lite' ) ) }
                        onChange={ ( newblockTitle ) => setAttributes( { blockTitle: newblockTitle } ) }
                    />
                    { blockTitle &&
                        <SelectControl
                            label = { escapeHTML( __( 'Block Title Layout', 'wp-magazine-modules-lite' ) ) }
                            value={ blockTitleLayout }
                            options={ [
                                { value: 'default', label: 'Default' },
                                { value: 'one', label: 'One' },
                                { value: 'two', label: 'Two' },
                                { value: 'three', label: 'Three ( pro )', disabled: true },
                                { value: 'four', label: 'Four ( pro )', disabled: true },
                                { value: 'five', label: 'Five ( pro )', disabled: true }
                            ] }
                            onChange={ ( newblockTitleLayout ) => setAttributes( { blockTitleLayout: newblockTitleLayout } ) }
                        />
                    }
                    <SelectControl
                        label = { escapeHTML( __( 'Content Type', 'wp-magazine-modules-lite' ) ) }
                        value={ contentType }
                        options={ [
                            { value: 'page', label: 'Page' },
                            { value: 'custom', label: 'Custom ( pro )', disabled: true }
                        ] }
                        onChange={ ( newcontentType ) => setAttributes( { contentType: newcontentType } ) }
                    />
                    { contentType === 'page' &&
                        <SelectControl
                            label = { escapeHTML( __( 'Select a page', 'wp-magazine-modules-lite' ) ) }
                            value={ bannerPage }
                            options={ pagesList }
                            onChange={ ( newbannerPage ) => setAttributes( { bannerPage: newbannerPage } ) }
                        />
                    }
                    { contentType === 'custom' &&
                        <Fragment>
                            <MediaUploadCheck>
                                <MediaUpload
                                    onSelect={ onUpdateImage }
                                    allowedTypes={ ALLOWED_MEDIA_TYPES }
                                    value={ bannerImage }
                                    render={ ( { open } ) => (
                                        <Button
                                            className={ ! bannerImage ? 'editor-post-featured-image__toggle' : 'editor-post-featured-image__preview' }
                                            onClick={ open }>
                                            { !bannerImage && escapeHTML( __( 'Upload image', 'wp-magazine-modules-lite' ) ) }
                                            { ( !!bannerImage && !bannerImage ) && <Spinner /> }
                                            {  ( bannerImage ) &&
                                                <img src={ bannerImage } alt={ escapeHTML( __( 'Image', 'wp-magazine-modules-lite' ) ) } />
                                            }
                                        </Button>
                                    ) }
                                />
                            </MediaUploadCheck>
                            { bannerImage &&
                                <MediaUploadCheck>
                                    <MediaUpload
                                        onSelect={ onUpdateImage }
                                        allowedTypes={ ALLOWED_MEDIA_TYPES }
                                        value={ bannerImage }
                                        render={ ( { open } ) => (
                                            <Button onClick={ open } isSecondary isLarge>
                                                { escapeHTML( __( 'Replace image', 'wp-magazine-modules-lite' ) ) }
                                            </Button>
                                        ) }
                                    />
                                </MediaUploadCheck>
                            }
                            { bannerImage &&
                                <MediaUploadCheck>
                                    <Button onClick={ onRemoveImage } isLink isDestructive>
                                        { escapeHTML( __( 'Remove image', 'wp-magazine-modules-lite' ) ) }
                                    </Button>
                                </MediaUploadCheck>
                            }
                        </Fragment>
                    }
                    <ToggleControl
                        label={ escapeHTML( __( 'Show Title', 'wp-magazine-modules-lite' ) ) }
                        checked={ titleOption }
                        onChange={ ( newtitleOption ) => setAttributes( { titleOption: newtitleOption } ) }
                    />
                    { titleOption && ( contentType === 'custom' ) &&
                        <TextControl
                            label={ escapeHTML( __( 'Title', 'wp-magazine-modules-lite' ) ) }
                            value={ bannerTitle }
                            placeholder={ escapeHTML( __( 'Add title here..', 'wp-magazine-modules-lite' ) ) }
                            onChange={ ( newbannerTitle ) => setAttributes( { bannerTitle: newbannerTitle } ) }
                        />
                    }
                    { titleOption && ( contentType === 'custom' ) &&
                        <TextControl
                            label={ escapeHTML( __( 'Title Link', 'wp-magazine-modules-lite' ) ) }
                            value={ bannerTitleLink }
                            placeholder={ escapeHTML( __( 'Add link here..', 'wp-magazine-modules-lite' ) ) }
                            onChange={ ( newbannerTitleLink ) => setAttributes( { bannerTitleLink: newbannerTitleLink } ) }
                        />
                    }
                    <ToggleControl
                        label={ escapeHTML( __( 'Show description', 'wp-magazine-modules-lite' ) ) }
                        checked={ descOption }
                        onChange={ ( newdescOption ) => setAttributes( { descOption: newdescOption } ) }
                    />
                    { descOption && ( contentType === 'custom' ) &&
                        <TextareaControl
                            label={ escapeHTML( __( 'Description', 'wp-magazine-modules-lite' ) ) }
                            value={ bannerDesc }
                            placeholder={ escapeHTML( __( 'Add desc here..', 'wp-magazine-modules-lite' ) ) }
                            onChange={ ( newbannerDesc ) => setAttributes( { bannerDesc: newbannerDesc } ) }
                        />
                    }
                    <ToggleControl
                        label={ escapeHTML( __( 'Show button One', 'wp-magazine-modules-lite' ) ) }
                        checked={ button1Option }
                        onChange={ ( newbutton1Option ) => setAttributes( { button1Option: newbutton1Option } ) }
                    />
                    { button1Option &&
                        <Fragment>
                            <TextControl
                                label={ escapeHTML( __( 'Button Text', 'wp-magazine-modules-lite' ) ) }
                                value={ button1Label }
                                onChange={ ( newbutton1Label ) => setAttributes( { button1Label: newbutton1Label } ) }
                            />
                            <TextControl
                                label={ escapeHTML( __( 'Button Link', 'wp-magazine-modules-lite' ) ) }
                                value={ button1Link }
                                onChange={ ( newbutton1Link ) => setAttributes( { button1Link: newbutton1Link } ) }
                            />
                        </Fragment>
                    }
                    <ToggleControl
                        label={ escapeHTML( __( 'Show button Two', 'wp-magazine-modules-lite' ) ) }
                        checked={ button2Option }
                        onChange={ ( newbutton2Option ) => setAttributes( { button2Option: newbutton2Option } ) }
                    />
                    { button2Option &&
                        <Fragment>
                            <TextControl
                                label={ escapeHTML( __( 'Button Text', 'wp-magazine-modules-lite' ) ) }
                                value={ button2Label }
                                onChange={ ( newbutton2Label ) => setAttributes( { button2Label: newbutton2Label } ) }
                            />
                            <TextControl
                                label={ escapeHTML( __( 'Button Link', 'wp-magazine-modules-lite' ) ) }
                                value={ button2Link }
                                onChange={ ( newbutton2Link ) => setAttributes( { button2Link: newbutton2Link } ) }
                            />
                        </Fragment>
                    }
                </PanelBody>
                <PanelBody title={ escapeHTML( __( 'Extra Settings', 'wp-magazine-modules-lite' ) ) } initialOpen = { false }>
                    <SelectControl
                        label = { escapeHTML( __( 'Links open in', 'wp-magazine-modules-lite' ) ) }
                        value={ permalinkTarget }
                        options={ [
                            { value: '_self', label: 'Same Tab' },
                            { value: '_blank', label: 'New Tab' }
                        ] }
                        onChange={ ( newpermalinkTarget ) => setAttributes( { permalinkTarget: newpermalinkTarget } ) }
                    />
                </PanelBody>
            </Fragment>
        )
    }
}

export default withSelect( ( select, props ) => {
    const { getEntityRecords } = select( 'core' );
    const pageQuery = {
        per_page: -1,
        status: 'publish'
    }

    let filterpagesList = [];
    filterpagesList.push({ label: escapeHTML( __( 'Select a page', 'wp-magazine-modules-lite' ) ), value: '' });
    let allpagesList = getEntityRecords( 'postType', 'page', pageQuery )
    if( allpagesList ) {
        allpagesList.map( ( page, index ) => {
            filterpagesList.push({ label: escapeHTML( page.title.rendered ), value: page.slug });
        });
    }

    return {
        pagesList: filterpagesList,
    };
} )( GeneralInspector );