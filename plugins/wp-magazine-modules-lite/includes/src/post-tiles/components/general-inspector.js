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
        const { blockTitle, blockTitleLayout, blockTitleAlign, featuredSectionOption, sliderposttype, sliderpostCategory, sliderpostCount, slidercontentOption, slidercontentType, sliderwordCount, sliderbuttonOption, sliderbuttonLabel, sliderorderBy, sliderorder, sliderdateOption, sliderauthorOption, slidercategoryOption, slidercategoriesCount, slidertagsOption, slidertagsCount, slidercommentOption, featuredpostCategory, featuredcontentOption, featuredcontentType, featuredwordCount, featuredorderBy, featuredorder, featureddateOption, featuredauthorOption, featuredcategoryOption, featuredcategoriesCount, featuredtagsOption, featuredtagsCount, featuredcommentOption, permalinkTarget, carouselType, carouselAuto, carouselDots, carouselControls, carouselLoop, carouselSpeed, fallbackImage } = this.props.attributes;
        const { setAttributes, slidercategoriesList, featuredcategoriesList } = this.props;

        const sliderhascategoriesList = Array.isArray(slidercategoriesList) && slidercategoriesList.length
        const featuredhascategoriesList = Array.isArray(featuredcategoriesList) && featuredcategoriesList.length

        const sliderallCategories = [];
        if( sliderhascategoriesList ) {
            slidercategoriesList.forEach( ( category ) => {
                sliderallCategories.push({ label: category.name + ' (' + category.count + ')', value: category.id });
            });
        }

        const featuredallCategories = [];
        if( featuredhascategoriesList ) {
            featuredcategoriesList.forEach( ( category ) => {
                featuredallCategories.push({ label: category.name + ' (' + category.count + ')', value: category.id });
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

        let sliderpostCategoryCheckboxes = [];
        if( Array.isArray( sliderallCategories ) && sliderallCategories.length ) {
            sliderallCategories.forEach( ( category, index  ) => {
                sliderpostCategoryCheckboxes.push( 
                    <CheckboxControl
                        key = { index }
                        label = { category.label }
                        value = { category.value }
                        checked = { sliderpostCategory.includes( category.value ) }
                        onChange = { ( checkboxValue ) => {
                                let data = sliderpostCategory
                                if( checkboxValue ) {
                                    data = data.concat( category.value )
                                    setAttributes( {
                                        sliderpostCategory : data
                                    })
                                } else {
                                    data.splice( data.indexOf(category.value), 1 )
                                    var newdata = JSON.parse( JSON.stringify( data ) )
                                    setAttributes( {
                                        sliderpostCategory : newdata
                                    })
                                }
                            }
                        }
                    />
                )
            });
        }

        let featuredpostCategoryCheckboxes = [];
        if( Array.isArray( featuredallCategories ) && featuredallCategories.length ) {
            featuredallCategories.forEach( ( category, index  ) => {
                featuredpostCategoryCheckboxes.push( 
                    <CheckboxControl
                        key = { index }
                        label = { category.label }
                        value = { category.value }
                        checked = { featuredpostCategory.includes( category.value ) }
                        onChange = { ( checkboxValue ) => {
                                let data = featuredpostCategory
                                if( checkboxValue ) {
                                    data = data.concat( category.value )
                                    setAttributes( {
                                        featuredpostCategory : data
                                    })
                                } else {
                                    data.splice( data.indexOf(category.value), 1 )
                                    var newdata = JSON.parse( JSON.stringify( data ) )
                                    setAttributes( {
                                        featuredpostCategory : newdata
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
                    <ToggleControl
                        label={ escapeHTML( __( 'Show featured section', 'wp-magazine-modules-lite' ) ) }
                        checked={ featuredSectionOption }
                        onChange={ ( newfeaturedSectionOption ) => setAttributes( { featuredSectionOption: newfeaturedSectionOption } ) }
                    />
                    <PanelBody title={ escapeHTML( __( 'Basic - Slider section settings', 'wp-magazine-modules-lite' ) ) } initialOpen={ false }>
                        <div class="wpmagazine-modules-post-choose-multicheckbox-control">
                            <label class="components-base-control__label">{ escapeHTML( __( 'Post Categories:', 'wp-magazine-modules-lite' ) ) }</label>
                            { sliderpostCategoryCheckboxes }
                        </div>
                        <ToggleControl
                            label={ escapeHTML( __( 'Show read more button', 'wp-magazine-modules-lite' ) ) }
                            checked={ sliderbuttonOption }
                            onChange={ ( newsliderbuttonOption ) => setAttributes( { sliderbuttonOption: newsliderbuttonOption } ) }
                        />
                        <TextControl
                            label={ escapeHTML( __( 'Button Label', 'wp-magazine-modules-lite' ) ) }
                            value={ sliderbuttonLabel }
                            placeholder={ escapeHTML( __( 'Add label here..', 'wp-magazine-modules-lite' ) ) }
                            onChange={ ( newsliderbuttonLabel ) => setAttributes( { sliderbuttonLabel: newsliderbuttonLabel } ) }
                        />
                    </PanelBody>
                    <PanelBody title={ escapeHTML( __( 'Basic - Featured section settings', 'wp-magazine-modules-lite' ) ) } initialOpen={ false }>
                        <div class="wpmagazine-modules-post-choose-multicheckbox-control">
                            <label class="components-base-control__label">{ escapeHTML( __( 'Post Categories:', 'wp-magazine-modules-lite' ) ) }</label>
                            { featuredpostCategoryCheckboxes }
                        </div>
                    </PanelBody>
                </PanelBody>

                <PanelBody title={ escapeHTML( __( 'Query Settings', 'wp-magazine-modules-lite' ) ) } initialOpen = { false }>
                    <PanelBody title={ escapeHTML( __( 'Query - Slider section settings', 'wp-magazine-modules-lite' ) ) } initialOpen = { false }>
                        <RangeControl
                            label={ escapeHTML( __( 'Post Count ( pro )', 'wp-magazine-modules-lite' ) ) }
                            value={ sliderpostCount }
                            onChange={ ( newsliderpostCount ) => setAttributes( { sliderpostCount: newsliderpostCount } ) }
                            min={ 1 }
                            max={ 10 }
                        />
                        <SelectControl
                            label = { escapeHTML( __( 'Order By', 'wp-magazine-modules-lite' ) ) }
                            value={ sliderorderBy }
                            options={ [
                                { value: 'date', label: 'Date' },
                                { value: 'title', label: 'Title' },
                                { value: 'id', label: 'ID ( pro )', disabled: true }
                            ] }
                            onChange={ ( newsliderorderBy ) => setAttributes( { sliderorderBy: newsliderorderBy } ) }
                        />
                        <SelectControl
                            label = { escapeHTML( __( 'Order', 'wp-magazine-modules-lite' ) ) }
                            value={ sliderorder }
                            options={ [
                                { value: 'asc', label: 'Ascending' },
                                { value: 'desc', label: 'Descending' }
                            ] }
                            onChange={ ( newsliderorder ) => setAttributes( { sliderorder: newsliderorder } ) }
                        />
                        <ToggleControl
                            label={ escapeHTML( __( 'Show date', 'wp-magazine-modules-lite' ) ) }
                            checked={ sliderdateOption }
                            onChange={ ( newsliderdateOption ) => setAttributes( { sliderdateOption: newsliderdateOption } ) }
                        />
                        <ToggleControl
                            label={ escapeHTML( __( 'Show author', 'wp-magazine-modules-lite' ) ) }
                            checked={ sliderauthorOption }
                            onChange={ ( newsliderauthorOption ) => setAttributes( { sliderauthorOption: newsliderauthorOption } ) }
                        />
                        <ToggleControl
                            label={ escapeHTML( __( 'Show categories', 'wp-magazine-modules-lite' ) ) }
                            checked={ slidercategoryOption }
                            onChange={ ( newslidercategoryOption ) => setAttributes( { slidercategoryOption: newslidercategoryOption } ) }
                        />
                        { slidercategoryOption === true &&
                            <RangeControl
                                label={ escapeHTML( __( 'Categories Count ( pro )', 'wp-magazine-modules-lite' ) ) }
                                value={ slidercategoriesCount }
                                onChange={ ( newslidercategoriesCount ) => setAttributes( { slidercategoriesCount: newslidercategoriesCount } ) }
                                min={ 1 }
                                max={ 5 }
                                disabled= { true }
                            />
                        }
                        <ToggleControl
                            label={ escapeHTML( __( 'Show tags', 'wp-magazine-modules-lite' ) ) }
                            checked={ slidertagsOption }
                            onChange={ ( newslidertagsOption ) => setAttributes( { slidertagsOption: newslidertagsOption } ) }
                        />
                        { slidertagsOption === true &&
                            <RangeControl
                                label={ escapeHTML( __( 'Tags Count ( pro )', 'wp-magazine-modules-lite' ) ) }
                                value={ slidertagsCount }
                                onChange={ ( newslidertagsCount ) => setAttributes( { slidertagsCount: newslidertagsCount } ) }
                                min={ 1 }
                                max={ 5 }
                                disabled= { true }
                            />
                        }
                        <ToggleControl
                            label={ escapeHTML( __( 'Show comments number', 'wp-magazine-modules-lite' ) ) }
                            checked={ slidercommentOption }
                            onChange={ ( newslidercommentOption ) => setAttributes( { slidercommentOption: newslidercommentOption } ) }
                        />
                        <ToggleControl
                            label={ escapeHTML( __( 'Show content', 'wp-magazine-modules-lite' ) ) }
                            checked={ slidercontentOption }
                            onChange={ ( newslidercontentOption ) => setAttributes( { slidercontentOption: newslidercontentOption } ) }
                        />
                        { slidercontentOption === true &&
                            <SelectControl
                                label = { escapeHTML( __( 'Content Type ( pro )', 'wp-magazine-modules-lite' ) ) }
                                value={ slidercontentType }
                                options={ [
                                    { value: 'excerpt', label: 'Excerpt' },
                                    { value: 'content', label: 'Content' }
                                ] }
                                disabled= { true }
                                onChange={ ( newslidercontentType ) => setAttributes( { slidercontentType: newslidercontentType } ) }
                            />
                        }
                        { slidercontentOption === true &&
                            <RangeControl
                                label={ escapeHTML( __( 'Content Length ( pro )', 'wp-magazine-modules-lite' ) ) }
                                value={ sliderwordCount }
                                onChange={ ( newsliderwordCount ) => setAttributes( { sliderwordCount: newsliderwordCount } ) }
                                min={ 1 }
                                max={ 500 }
                                disabled= { true }
                            />
                        }
                    </PanelBody>
                    <PanelBody title={ escapeHTML( __( 'Query - Featured section settings', 'wp-magazine-modules-lite' ) ) } initialOpen = { false }>
                        <SelectControl
                            label = { escapeHTML( __( 'Order By', 'wp-magazine-modules-lite' ) ) }
                            value={ featuredorderBy }
                            options={ [
                                { value: 'date', label: 'Date' },
                                { value: 'title', label: 'Title' },
                                { value: 'id', label: 'ID ( pro )', disabled: true }
                            ] }
                            onChange={ ( newfeaturedorderBy ) => setAttributes( { featuredorderBy: newfeaturedorderBy } ) }
                        />
                        <SelectControl
                            label = { escapeHTML( __( 'Order', 'wp-magazine-modules-lite' ) ) }
                            value={ featuredorder }
                            options={ [
                                { value: 'asc', label: 'Ascending' },
                                { value: 'desc', label: 'Descending' }
                            ] }
                            onChange={ ( newfeaturedorder ) => setAttributes( { featuredorder: newfeaturedorder } ) }
                        />
                        <ToggleControl
                            label={ escapeHTML( __( 'Show date', 'wp-magazine-modules-lite' ) ) }
                            checked={ featureddateOption }
                            onChange={ ( newfeatureddateOption ) => setAttributes( { featureddateOption: newfeatureddateOption } ) }
                        />
                        <ToggleControl
                            label={ escapeHTML( __( 'Show author', 'wp-magazine-modules-lite' ) ) }
                            checked={ featuredauthorOption }
                            onChange={ ( newfeaturedauthorOption ) => setAttributes( { featuredauthorOption: newfeaturedauthorOption } ) }
                        />
                        <ToggleControl
                            label={ escapeHTML( __( 'Show categories', 'wp-magazine-modules-lite' ) ) }
                            checked={ featuredcategoryOption }
                            onChange={ ( newfeaturedcategoryOption ) => setAttributes( { featuredcategoryOption: newfeaturedcategoryOption } ) }
                        />
                        { featuredcategoryOption === true &&
                            <RangeControl
                                label={ escapeHTML( __( 'Categories Count ( pro )', 'wp-magazine-modules-lite' ) ) }
                                value={ featuredcategoriesCount }
                                onChange={ ( newfeaturedcategoriesCount ) => setAttributes( { featuredcategoriesCount: newfeaturedcategoriesCount } ) }
                                min={ 1 }
                                max={ 5 }
                                disabled= { true }
                            />
                        }
                        <ToggleControl
                            label={ escapeHTML( __( 'Show tags', 'wp-magazine-modules-lite' ) ) }
                            checked={ featuredtagsOption }
                            onChange={ ( newfeaturedtagsOption ) => setAttributes( { featuredtagsOption: newfeaturedtagsOption } ) }
                        />
                        { featuredtagsOption === true &&
                            <RangeControl
                                label={ escapeHTML( __( 'Tags Count ( pro )', 'wp-magazine-modules-lite' ) ) }
                                value={ featuredtagsCount }
                                onChange={ ( newfeaturedtagsCount ) => setAttributes( { featuredtagsCount: newfeaturedtagsCount } ) }
                                min={ 1 }
                                max={ 5 }
                                disabled= { true }
                            />
                        }
                        <ToggleControl
                            label={ escapeHTML( __( 'Show comments number', 'wp-magazine-modules-lite' ) ) }
                            checked={ featuredcommentOption }
                            onChange={ ( newfeaturedcommentOption ) => setAttributes( { featuredcommentOption: newfeaturedcommentOption } ) }
                        />
                        <ToggleControl
                            label={ escapeHTML( __( 'Show content', 'wp-magazine-modules-lite' ) ) }
                            checked={ featuredcontentOption }
                            onChange={ ( newfeaturedcontentOption ) => setAttributes( { featuredcontentOption: newfeaturedcontentOption } ) }
                        />
                        { featuredcontentOption === true &&
                            <SelectControl
                                label = { escapeHTML( __( 'Content Type ( pro )', 'wp-magazine-modules-lite' ) ) }
                                value={ featuredcontentType }
                                options={ [
                                    { value: 'excerpt', label: 'Excerpt' },
                                    { value: 'content', label: 'Content' }
                                ] }
                                disabled= { true }
                                onChange={ ( newfeaturedcontentType ) => setAttributes( { featuredcontentType: newfeaturedcontentType } ) }
                            />
                        }
                        { featuredcontentOption === true &&
                            <RangeControl
                                label={ escapeHTML( __( 'Content Length ( pro )', 'wp-magazine-modules-lite' ) ) }
                                value={ featuredwordCount }
                                onChange={ ( newfeaturedwordCount ) => setAttributes( { featuredwordCount: newfeaturedwordCount } ) }
                                min={ 1 }
                                max={ 500 }
                                disabled= { true }
                            />
                        }
                    </PanelBody>
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
                        />
                    </Disabled>
                    <Disabled>
                        <TextControl
                            label={ escapeHTML( __( 'Speed ( pro )', 'wp-magazine-modules-lite' ) ) }
                            type="number"
                            min={ 200 }
                            max={ 3000 }
                            step={ 100 }
                            value={ carouselSpeed }
                            onChange={ ( newcarouselSpeed ) => setAttributes( { carouselSpeed: newcarouselSpeed } ) }
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
    const { sliderposttype, featuredposttype } = props.attributes
    const { getEntityRecords, getPostTypes } = select( 'core' );
    let allpostTypesList = getPostTypes()

    let slidertaxonomnyName, featuredtaxonomnyName;
    if( allpostTypesList ) {
        allpostTypesList.forEach( ( allpostType ) => {
            if( allpostType.slug != 'page' && allpostType.slug != 'wp_block' && allpostType.slug != 'attachment' ) {
                if( allpostType.slug == sliderposttype ) {
                    slidertaxonomnyName = allpostType.taxonomies[0]
                }
                if ( allpostType.slug == featuredposttype ) {
                    featuredtaxonomnyName = allpostType.taxonomies[0]
                }
            }
        });
    }

    const slidertaxonomnyQuery = {
        hide_empty: true,
        per_page: 100
    }
    const featuredtaxonomnyQuery = {
        hide_empty: true,
        per_page: 100
    }
    return {
        slidercategoriesList: getEntityRecords( 'taxonomy', slidertaxonomnyName, slidertaxonomnyQuery ),
        featuredcategoriesList: getEntityRecords( 'taxonomy', featuredtaxonomnyName, featuredtaxonomnyQuery ),
    };
} )( GeneralInspector );