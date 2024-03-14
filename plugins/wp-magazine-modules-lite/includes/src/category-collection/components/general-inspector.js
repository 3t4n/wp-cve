/**
 * General Tab Inspector controls wrapper controls.
 * 
 */
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { PanelBody, TextControl, SelectControl, ToggleControl, Button, Spinner, CheckboxControl, Disabled } = wp.components;
const { MediaUpload, MediaUploadCheck  } = wp.blockEditor;
const { withSelect } = wp.data
const ALLOWED_MEDIA_TYPES = [ 'image' ];

class GeneralInspector extends Component {
    constructor( props ) {
        super( ...arguments );
    }

    render() {
        const { blockTitle, blockTitleLayout, blockTitleAlign, blockCategories, titleOption, descOption, catcountOption, permalinkTarget, fallbackImage } = this.props.attributes;
        const { setAttributes, categoriesList } = this.props;

        const hascategoriesList = Array.isArray(categoriesList) && categoriesList.length

        const allCategories = [];
        if( hascategoriesList ) {
            categoriesList.forEach( ( category ) => {
                allCategories.push({ label: category.name + ' (' + category.count + ')', value: category.id });
            });
        }

        const onUpdateImage = ( newImage ) => {
            setAttributes( {
                fallbackImage: newImage.url,
            } );
        };

        const onRemoveImage = () => {
            setAttributes( {
                fallbackImage: undefined,
            } );
        };

        let postCategoryCheckboxes = [];
        if( Array.isArray( allCategories ) && allCategories.length ) {
            allCategories.forEach( ( category, index  ) => {
                postCategoryCheckboxes.push( 
                    <CheckboxControl
                        key = { index }
                        label = { category.label }
                        value = { category.value }
                        checked = { blockCategories.includes( category.value ) }
                        onChange = { ( checkboxValue ) => {
                                let data = blockCategories
                                if( checkboxValue ) {
                                    data = data.concat( category.value )
                                    setAttributes( {
                                        blockCategories : data
                                    })
                                } else {
                                    data.splice( data.indexOf(category.value), 1 )
                                    var newdata = JSON.parse( JSON.stringify( data ) )
                                    setAttributes( {
                                        blockCategories : newdata
                                    })
                                }
                            }
                        }
                    />
                )
            });
        }

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
                    { blockTitle &&
                        <SelectControl
                            label={ escapeHTML( __( 'Text Align', 'wp-magazine-modules-lite' ) ) }
                            value={ blockTitleAlign }
                            options={ [
                                { value: 'left', label: escapeHTML( __( 'Left', 'wp-magazine-modules-lite' ) ) },
                                { value: 'center', label: escapeHTML( __( 'Center', 'wp-magazine-modules-lite' ) ) },
                                { value: 'right', label: escapeHTML( __( 'Right', 'wp-magazine-modules-lite' ) ) }
                            ] }
                            onChange={ ( newblockTitleAlign ) => setAttributes( { blockTitleAlign: newblockTitleAlign } ) }
                        />
                    }
                    <div class="wpmagazine-modules-post-choose-multicheckbox-control">
                        <label class="components-base-control__label">{ escapeHTML( __( 'Post Categories:', 'wp-magazine-modules-lite' ) ) }</label>
                        <div className={ "wpmagazine-modules-post-choose-multicheckbox-control__content" }>
                            { postCategoryCheckboxes }
                        </div>
                    </div>
                </PanelBody>

                <PanelBody title={ escapeHTML( __( 'Query Settings', 'wp-magazine-modules-lite' ) ) } initialOpen = { false }>
                    <ToggleControl
                        label={ escapeHTML( __( 'Show title', 'wp-magazine-modules-lite' ) ) }
                        checked={ titleOption }
                        onChange={ ( newtitleOption ) => setAttributes( { titleOption: newtitleOption } ) }
                    />
                    <Disabled>
                        <ToggleControl
                            label={ escapeHTML( __( 'Show description ( pro )', 'wp-magazine-modules-lite' ) ) }
                            checked={ descOption }
                            disabled={ true }
                            onChange={ ( newdescOption ) => setAttributes( { descOption: newdescOption } ) }
                        />
                    </Disabled>
                    <ToggleControl
                        label={ escapeHTML( __( 'Show category count', 'wp-magazine-modules-lite' ) ) }
                        checked={ catcountOption }
                        onChange={ ( newcatcountOption ) => setAttributes( { catcountOption: newcatcountOption } ) }
                    />
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
                
                <PanelBody title={ escapeHTML( __( 'Fallback Image', 'wp-magazine-modules-lite' ) ) } initialOpen = { false }>
                    <MediaUploadCheck>
                        <MediaUpload
                            onSelect={ onUpdateImage }
                            allowedTypes={ ALLOWED_MEDIA_TYPES }
                            value={ fallbackImage }
                            render={ ( { open } ) => (
                                <Button
                                    className={ ! fallbackImage ? 'editor-post-featured-image__toggle' : 'editor-post-featured-image__preview' }
                                    onClick={ open }>
                                    { !fallbackImage && escapeHTML( __( 'Set fallback image', 'wp-magazine-modules-lite' ) ) }
                                    { ( !!fallbackImage && !fallbackImage ) && <Spinner /> }
                                    {  ( fallbackImage ) &&
                                        <img src={ fallbackImage } alt={ escapeHTML( __( 'Image', 'wp-magazine-modules-lite' ) ) } />
                                    }
                                </Button>
                            ) }
                    />
                    </MediaUploadCheck>
                    { fallbackImage &&
                        <MediaUploadCheck>
                            <MediaUpload
                                onSelect={ onUpdateImage }
                                allowedTypes={ ALLOWED_MEDIA_TYPES }
                                value={ fallbackImage }
                                render={ ( { open } ) => (
                                    <Button onClick={ open } isDefault isLarge>
                                        { escapeHTML( __( 'Replace fallback image', 'wp-magazine-modules-lite' ) ) }
                                    </Button>
                                ) }
                            />
                        </MediaUploadCheck>
                    }
                    { fallbackImage &&
                        <MediaUploadCheck>
                            <Button onClick={ onRemoveImage } isLink isDestructive>
                                { escapeHTML( __( 'Remove fallback image', 'wp-magazine-modules-lite' ) ) }
                            </Button>
                        </MediaUploadCheck>
                    }
                </PanelBody>
            </Fragment>
        )
    }
}

export default withSelect( ( select, props ) => {
    const { getEntityRecords } = select( 'core' );

    const taxonomyQuery = {
        hide_empty: true,
        per_page: 100
    }
    return {
        categoriesList: getEntityRecords( 'taxonomy', 'category', taxonomyQuery ),
    };
} )( GeneralInspector );