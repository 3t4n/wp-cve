/**
 * General Tab Tnspector controls wrapper controls.
 * 
 */
const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { PanelBody, TextControl, SelectControl, RangeControl, ToggleControl, Button, Spinner, CheckboxControl, Disabled } = wp.components;
const { MediaUpload, MediaUploadCheck  } = wp.blockEditor;
const { withSelect } = wp.data
const ALLOWED_MEDIA_TYPES = [ 'image' ];
const { hasFilter, applyFilters } = wp.hooks

class GeneralInspector extends Component {
    constructor( props ) {
        super( ...arguments );
    }

    render() {
        const { blockTitle, blockTitleLayout, blockTitleAlign, posttype, postCategory, postCount, contentOption, contentType, wordCount, buttonOption, buttonLabel, orderBy, order, thumbOption, titleOption, dateOption, authorOption, categoryOption, categoriesCount, tagsOption, tagsCount, commentOption, permalinkTarget, carouselType, carouselAuto, carouselDots, carouselControls, carouselLoop, carouselSpeed, carouselColumn, fallbackImage } = this.props.attributes;
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
                        checked = { postCategory.includes( category.value ) }
                        onChange = { ( checkboxValue ) => {
                                let data = postCategory
                                if( checkboxValue ) {
                                    data = data.concat( category.value )
                                    setAttributes( {
                                        postCategory : data
                                    })
                                } else {
                                    data.splice( data.indexOf(category.value), 1 )
                                    var newdata = JSON.parse( JSON.stringify( data ) )
                                    setAttributes( {
                                        postCategory : newdata
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
                    <ToggleControl
                        label={ escapeHTML( __( 'Show read more button', 'wp-magazine-modules-lite' ) ) }
                        checked={ buttonOption }
                        onChange={ ( newbuttonOption ) => setAttributes( { buttonOption: newbuttonOption } ) }
                    />
                    <TextControl
                        label={ escapeHTML( __( 'Button Label', 'wp-magazine-modules-lite' ) ) }
                        value={ buttonLabel }
                        placeholder={ escapeHTML( __( 'Add label here..', 'wp-magazine-modules-lite' ) ) }
                        onChange={ ( newbuttonLabel ) => setAttributes( { buttonLabel: newbuttonLabel } ) }
                    />
                </PanelBody>

                <PanelBody title={ escapeHTML( __( 'Query Settings', 'wp-magazine-modules-lite' ) ) } initialOpen = { false }>
                    <RangeControl
                        label={ escapeHTML( __( 'Post Count ( pro )', 'wp-magazine-modules-lite' ) ) }
                        value={ postCount }
                        onChange={ ( newpostCount ) => setAttributes( { postCount: newpostCount } ) }
                        min={ 1 }
                        max={ 12 }
                    />
                    <SelectControl
                        label = { escapeHTML( __( 'Order By', 'wp-magazine-modules-lite' ) ) }
                        value={ orderBy }
                        options={ [
                            { value: 'date', label: 'Date' },
                            { value: 'title', label: 'Title' },
                            { value: 'id', label: 'ID ( pro )', disabled: true }
                        ] }
                        onChange={ ( neworderBy ) => setAttributes( { orderBy: neworderBy } ) }
                    />
                    <SelectControl
                        label = { escapeHTML( __( 'Order', 'wp-magazine-modules-lite' ) ) }
                        value={ order }
                        options={ [
                            { value: 'asc', label: 'Ascending' },
                            { value: 'desc', label: 'Descending' }
                        ] }
                        onChange={ ( neworder ) => setAttributes( { order: neworder } ) }
                    />
                    <ToggleControl
                        label={ escapeHTML( __( 'Show thumbnail', 'wp-magazine-modules-lite' ) ) }
                        checked={ thumbOption }
                        onChange={ ( newthumbOption ) => setAttributes( { thumbOption: newthumbOption } ) }
                    />
                    <ToggleControl
                        label={ escapeHTML( __( 'Show title', 'wp-magazine-modules-lite' ) ) }
                        checked={ titleOption }
                        onChange={ ( newtitleOption ) => setAttributes( { titleOption: newtitleOption } ) }
                    />
                    <ToggleControl
                        label={ escapeHTML( __( 'Show date', 'wp-magazine-modules-lite' ) ) }
                        checked={ dateOption }
                        onChange={ ( newdateOption ) => setAttributes( { dateOption: newdateOption } ) }
                    />
                    <ToggleControl
                        label={ escapeHTML( __( 'Show author', 'wp-magazine-modules-lite' ) ) }
                        checked={ authorOption }
                        onChange={ ( newauthorOption ) => setAttributes( { authorOption: newauthorOption } ) }
                    />
                    <ToggleControl
                        label={ escapeHTML( __( 'Show categories', 'wp-magazine-modules-lite' ) ) }
                        checked={ categoryOption }
                        onChange={ ( newcategoryOption ) => setAttributes( { categoryOption: newcategoryOption } ) }
                    />
                    { categoryOption === true &&
                        <RangeControl
                            label={ escapeHTML( __( 'Categories Count ( pro )', 'wp-magazine-modules-lite' ) ) }
                            value={ categoriesCount }
                            onChange={ ( newcategoriesCount ) => setAttributes( { categoriesCount: newcategoriesCount } ) }
                            min={ 1 }
                            max={ 5 }
                            disabled ={ true }
                        />
                    }
                    <ToggleControl
                        label={ escapeHTML( __( 'Show tags', 'wp-magazine-modules-lite' ) ) }
                        checked={ tagsOption }
                        onChange={ ( newtagsOption ) => setAttributes( { tagsOption: newtagsOption } ) }
                    />
                    { tagsOption === true &&
                        <RangeControl
                            label={ escapeHTML( __( 'Tags Count ( pro )', 'wp-magazine-modules-lite' ) ) }
                            value={ tagsCount }
                            onChange={ ( newtagsCount ) => setAttributes( { tagsCount: newtagsCount } ) }
                            min={ 1 }
                            max={ 5 }
                            disabled ={ true }
                        />
                    }
                    <ToggleControl
                        label={ escapeHTML( __( 'Show comments number', 'wp-magazine-modules-lite' ) ) }
                        checked={ commentOption }
                        onChange={ ( newcommentOption ) => setAttributes( { commentOption: newcommentOption } ) }
                    />
                    <ToggleControl
                        label={ escapeHTML( __( 'Show content', 'wp-magazine-modules-lite' ) ) }
                        checked={ contentOption }
                        onChange={ ( newcontentOption ) => setAttributes( { contentOption: newcontentOption } ) }
                    />
                    { contentOption === true &&
                        <SelectControl
                            label = { escapeHTML( __( 'Content Type ( pro )', 'wp-magazine-modules-lite' ) ) }
                            value={ contentType }
                            options={ [
                                { value: 'excerpt', label: 'Excerpt' },
                                { value: 'content', label: 'Content' }
                            ] }
                            disabled ={ true }
                            onChange={ ( newcontentType ) => setAttributes( { contentType: newcontentType } ) }
                        />
                    }
                    { contentOption === true &&
                        <RangeControl
                            label={ escapeHTML( __( 'Content Length ( pro )', 'wp-magazine-modules-lite' ) ) }
                            value={ wordCount }
                            onChange={ ( newwordCount ) => setAttributes( { wordCount: newwordCount } ) }
                            min={ 1 }
                            max={ 500 }
                            disabled ={ true }
                        />
                    }
                </PanelBody>
                { hasFilter( 'CodevibrantSocialshareEditorSettings', 'codevibrant-socialshare' ) &&
                    applyFilters( 'CodevibrantSocialshareEditorSettings', this.props )
                }
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

                <PanelBody title={ escapeHTML( __( 'Carousel Options', 'wp-magazine-modules-lite' ) ) } initialOpen = { false }>
                    <Disabled>
                        <ToggleControl
                            label={ escapeHTML( __( 'Enable fade animation ( pro )', 'wp-magazine-modules-lite' ) ) }
                            checked={ carouselType }
                            onChange={ ( newcarouselType ) => setAttributes( { carouselType: newcarouselType } ) }
                        />
                    </Disabled>
                    <Disabled>
                        <ToggleControl
                            label={ escapeHTML( __( 'Enable auto slide ( pro )', 'wp-magazine-modules-lite' ) ) }
                            checked={ carouselAuto }
                            onChange={ ( newcarouselAuto ) => setAttributes( { carouselAuto: newcarouselAuto } ) }
                        />
                    </Disabled>
                    <Disabled>
                        <ToggleControl
                            label={ escapeHTML( __( 'Show dots ( pro )', 'wp-magazine-modules-lite' ) ) }
                            checked={ carouselDots }
                            onChange={ ( newcarouselDots ) => setAttributes( { carouselDots: newcarouselDots } ) }
                        />
                    </Disabled>
                    <ToggleControl
                        label={ escapeHTML( __( 'Show control buttons', 'wp-magazine-modules-lite' ) ) }
                        checked={ carouselControls }
                        onChange={ ( newcarouselControls ) => setAttributes( { carouselControls: newcarouselControls } ) }
                    />
                    <Disabled>
                        <ToggleControl
                            label={ escapeHTML( __( 'Enable items loop ( pro )', 'wp-magazine-modules-lite' ) ) }
                            checked={ carouselLoop }
                            onChange={ ( newcarouselLoop ) => setAttributes( { carouselLoop: newcarouselLoop } ) }
                            disabled= { true }
                        />
                    </Disabled>
                    <Disabled>
                        <TextControl
                            label={ escapeHTML( __( 'Speed ( pro )', 'wp-magazine-modules-lite' ) ) }
                            type="number"
                            min={ 200 }
                            max={ 3000 }
                            step={ 100 }
                            disabled= { true }
                            value={ carouselSpeed }
                            onChange={ ( newcarouselSpeed ) => setAttributes( { carouselSpeed: newcarouselSpeed } ) }
                        />
                    </Disabled>
                    <Disabled>
                        <RangeControl
                            label={ escapeHTML( __( 'Carousel Column ( pro )', 'wp-magazine-modules-lite' ) ) }
                            value={ carouselColumn }
                            onChange={ ( newcarouselColumn ) => setAttributes( { carouselColumn: newcarouselColumn } ) }
                            min={ 1 }
                            max={ 5 }
                            disabled= { true }
                        />
                    </Disabled>
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
    const { posttype } = props.attributes
    const { getEntityRecords, getPostTypes } = select( 'core' );
    let allpostTypesList = getPostTypes()

    let taxonomnyName;
    if( allpostTypesList ) {
        allpostTypesList.forEach( ( allpostType ) => {
            if( allpostType.slug != 'page' && allpostType.slug != 'wp_block' && allpostType.slug != 'attachment' ) {
                if( allpostType.slug == posttype ) {
                    taxonomnyName = allpostType.taxonomies[0]
                }
            }
        });
    }
    
    const taxonomyQuery = {
        hide_empty: true,
        per_page: 100
    }
    return {
        categoriesList: getEntityRecords( 'taxonomy', taxonomnyName, taxonomyQuery ),
    };
} )( GeneralInspector );