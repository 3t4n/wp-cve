// Internationalization
const { __ } = wp.i18n;

import MultiSelect from "@khanacademy/react-multi-select";
// import Select from 'react-select';

// Extend component
const { Component, Fragment } = wp.element;

// import Inspector components
const { QueryControls, PanelBody,  RangeControl, ToggleControl, SelectControl, TextControl, TextareaControl, Button, ButtonGroup, Tooltip } = wp.components;

const { InspectorControls, AlignmentToolbar, BlockControls, URLInput} = wp.blockEditor;
export default class LayoutInspector extends Component{
    constructor(props){
        super(props);
    }


    render(){

        const { attributes, categoriesList, tagsList, setAttributes, latestPosts, className, postTypes, media, authors} = this.props;
        const { post_type, categories, order, orderBy, postOffset, postscount, columns, postLayout, displayPostImage, displayPostDate, displayPostAuthor, displayPostExcerpt, displayPostReadMoreButton, postReadMoreButtonText, align, postImageSizes, carouselLayoutStyle, gridLayoutStyle, postBlockWidth, slidesToShow, autoPlay, navigation, columnGap, linkTarget, equalHeight, imageHeight,

            // cta attrs
            postCtaButtonAlign,
            displayPostCtaButton,
            postCtaButtonText,
            CtaLinkTarget,
            postCtaButtonLink,
            postCtaButtonStyle,
            displayCtaButtonIcon,

            // heading attrs
            displayPostHeading,
            postHeadingStyle,
            postHeadingText,
            postHeadingLink,
            postHeadingLinkTarget,
            postHeadingAlign,

            // sub heading attrs
            displayPostSubHeading,
            postSubHeadingText,

            // pagination attrs
            displayPagination,
            paginationType,
            paginationStyle,
            navigationPosition,
            paginationAlign,

            // filter attrs
            displayFilter,
            displayAllButton,
            allButtonText,
            filterBy,
            filterTags,
            filterCats,
            maxTaxDisplay,


        } = attributes;

        const orderByOptions = [
            { value: 'id', label: __( 'ID' ) },
            { value: 'date', label: __( 'Date' ) },
            { value: 'title', label: __( 'Title' ) },
            { value: 'author', label: __( 'Author' ) },
            { value: 'rand', label: __( 'Random' ) },
            { value: 'menu_order', label: __( 'Menu Order' ) },
            { value: 'parent', label: __( 'Parent' ) },
            { value: 'modified', label: __( 'Modified' ) },
        ];

        const orderOptions = [
            { value: 'asc', label: __( 'Ascending' ) },
            { value: 'desc', label: __( 'Descending' ) },
        ];

        const postImageDefaultSizes = [
            { value: 'full', label: __( 'Full' ) },
            { value: 'guten_post_layout_landscape_large', label: __( 'Landscape Large' ) },
            { value: 'guten_post_layout_portrait_large', label: __( 'Portrait Large' ) },
            { value: 'guten_post_layout_square_large', label: __( 'Square Large' ) },
            { value: 'guten_post_layout_landscape', label: __( 'Landscape Small' ) },
            { value: 'guten_post_layout_portrait', label: __( 'Portrait Small' ) },
            { value: 'guten_post_layout_square', label: __( 'Square Small' ) },
            { value: 'thumbnail', label: __( 'Thumbnail' ) },
        ];

        const defaultCarouselLayoutStyles =  [
            { label: __('Select Your Layout'), value: 'skin_empty'  },
            { label: __('Skin 1'), value: 'skin1'  },
            { label: __('Skin 2'), value: 'skin2'  },
            { label: __('Skin 3'), value: 'skin3'  },
        ];

        const defaultGridLayoutStyles =  [
            { label: __('Select Your Layout'), value: 'g_skin_empty'},
            { label: __('Skin 1'), value: 'g_skin1'  },
            { label: __('Skin 2'), value: 'g_skin2'  },
            { label: __('Skin 3'), value: 'g_skin3'  },
        ];

        const defaultNavigationsStyle =  [
            { label: __('Dots'), value: 'dots'  },
            { label: __('Arrows'), value: 'arrows'  },
            { label: __('None'), value: 'none'  },
        ];

        const postHeadingStyleOption =  [
            { label: __('Style 1'), value: 'style1'  },
            { label: __('Style 2'), value: 'style2'  },
            { label: __('Style 3'), value: 'style3'  },
        ];

        const postPaginationTypeOption =  [
            { label: __('Navigation'), value: 'gpl-navigation'  },
            { label: __('Pagination'), value: 'gpl-pagination'  },
        ];

        const postNavigationPositionOption =  [
            { label: __('Top Right'), value: 'gpl-nav-top-right'  },
            { label: __('Bottom'), value: 'gpl-nav-bottom'  },
        ];

        const postPaginationStyleOption =  [
            { label: __('Text & Arrow'), value: 'gpl-text-arrow'  },
            { label: __('Only Arrow'), value: 'gpl-only-arrow'  },
        ];

        const FilterByOption = [
            { value: 'categories', label: __( 'Categories' ) },
            { value: 'tags', label: __( 'Tags' ) },
        ];

        const layoutTypes = [
            { value: 'grid', label: __( 'Grid' ) },
            { value: 'list', label: __( 'List' ) },
            { value: 'slides', label: __( 'Slider' ) },
        ];


        const postQueryCats = [];
        const postCats = [];
        const postTags = [];

        if( categoriesList ) {
            [...categoriesList].map(
                val => val.count !== 0 && postCats.push({label: val ? val.name : '', value: val ? val.slug : ''})
            );
        }

        if( categoriesList ) {
            [...categoriesList].map(
                val => val.count !== 0 && postQueryCats.push({label: val ? val.name : '', value: val ? val.id : ''})
            );
        }

        if( tagsList ){
            [...tagsList].map(val => val.count !== 0 && postTags.push({ label: val ? val.name : '', value: val ? val.slug : '' }));
        }

        return(
            <Fragment>
                <PanelBody title={ __( 'Layout Settings' ) } initialOpen={ false }>

                    <SelectControl
                        label={__('Layout Type')}
                        options={layoutTypes}
                        value={postLayout}
                        onChange={(value) => {
                            setAttributes({postLayout: value})
                        }}
                    />

                    {postLayout === 'slides' &&
                    <SelectControl
                        label={__('Carousel Skin')}
                        options={defaultCarouselLayoutStyles}
                        value={carouselLayoutStyle}
                        onChange={(newValue) => {
                            setAttributes({carouselLayoutStyle: newValue, gridLayoutStyle : null })
                        }}
                    />
                    }
                    { (postLayout === 'grid' || postLayout === 'list') &&
                    <SelectControl
                        label={__('Grid Skin')}
                        options={defaultGridLayoutStyles}
                        value={gridLayoutStyle}
                        onChange={(newValue) => {
                            setAttributes({gridLayoutStyle: newValue, carouselLayoutStyle: null })
                        }}
                    />
                    }

                    { postLayout === 'grid' &&
                    <RangeControl
                        label={__('Number of columns')}
                        value={columns}
                        onChange={(value) => setAttributes({columns: value})}
                        min={1}
                        max={6}
                    />
                    }

                    <RangeControl
                        label = { __('Column & Row Gaps' ) }
                        value = { columnGap }
                        min = { 0.01 }
                        max = { 21 }
                        onChange = { ( value ) => setAttributes({ columnGap: value }) }
                    />


                    {postLayout === 'slides' &&
                    <RangeControl
                        label={__('Slides To Show')}
                        value={slidesToShow}
                        min={1}
                        max={3}
                        onChange={(value) => setAttributes({slidesToShow: value})}
                    />
                    }
                    {postLayout === 'slides' &&
                    <ToggleControl
                        label={__('Autoplay')}
                        checked={!!autoPlay}
                        onChange={(value) => setAttributes({autoPlay: value})}
                    />
                    }
                    {postLayout === 'slides' &&
                    <SelectControl
                        label={__('Navigation')}
                        options={defaultNavigationsStyle}
                        value={navigation}
                        onChange={(newValue) => {
                            setAttributes({navigation: newValue})
                        }}
                    />
                    }

                    {(gridLayoutStyle === 'g_skin3') &&
                    <ToggleControl
                        label={__('Equal Height')}
                        checked={!!equalHeight}
                        onChange={(value) => setAttributes({equalHeight: value})}
                        initialPosition={1}
                    />
                    }

                    <RangeControl
                        label={__('Image Height')}
                        value={imageHeight}
                        min={100}
                        max={2000}
                        onChange={(value) => setAttributes({imageHeight: value})}
                    />

                </PanelBody>

                <PanelBody title={ __( 'Query Settings' ) }>
                    <SelectControl
                        label = { __( 'Post Types' ) }
                        options={ postTypes && postTypes.map(({ slug, name }) => ( { value: slug, label:name})) }
                        value={ post_type}
                        onChange={(newValue) => { setAttributes({
                            post_type: newValue,
                            categories: ''
                        }) }}
                    />


                    <div className={'gpl-select-panel gpl-mb-10'}>
                        <span className={'gpl-pb-5'}>{__('Get Posts From Categories')}</span>
                        <MultiSelect
                            options={postQueryCats}
                            selected={categories}
                            onSelectedChanged={(value) => {setAttributes({
                                categories: '' !== value ? value : undefined,
                            })}}
                            overrideStrings={{
                                selectSomeItems: __('Select Category'),
                            }}
                        />
                    </div>


                    <QueryControls
                        numberOfItems={postscount}
                        // categoriesList={ postQueryCats ? postQueryCats : [] }
                        // selectedCategoryId = {categories}
                        // onCategoryChange={ ( value ) => setAttributes( {
                        //     categories: '' !== value ? value : undefined
                        // }) }
                        onNumberOfItemsChange={ (value) => setAttributes({ postscount: value }) }
                    />

                    <div className={'gpl-select-panel gpl-mb-10'}>
                        <span className={'gpl-pb-5'}>{ __('Order By') }</span>
                        <SelectControl
                            options={orderByOptions}
                            value={orderBy}
                            onChange={(value) => {setAttributes({  orderBy: '' !== value ? value : 'date' })
                            }}
                        />
                    </div>

                    <div className={'gpl-select-panel gpl-mb-10'}>
                        <span className={'gpl-pb-5'}>{ __('Order') }</span>
                        <SelectControl
                            options={orderOptions}
                            value={order}
                            onChange={(value) => { setAttributes({  order: '' !== value ? value : 'desc' })
                            }}
                        />
                    </div>

                    <RangeControl
                        label={__('Offset Post')}
                        value={postOffset}
                        min={0}
                        max={100}
                        onChange={(value) => setAttributes({postOffset: value})}
                    />

                </PanelBody>


                <PanelBody title={ __( 'Additional Settings' ) } initialOpen={ false }>

                    <ToggleControl
                        label = { __('Display Featured Image') }
                        checked = { !!displayPostImage }
                        onChange = { (value) => setAttributes( { displayPostImage: value } ) }
                    />


                    { displayPostImage &&
                    <SelectControl
                        label={__('Image Size')}
                        options={postImageDefaultSizes}
                        value={postImageSizes}
                        onChange={(newValue) => {setAttributes({ postImageSizes: newValue})
                        }}
                    />
                    }
                    {
                        ( (carouselLayoutStyle === 'skin2' || carouselLayoutStyle === 'skin3' ) || (gridLayoutStyle === 'g_skin1' || gridLayoutStyle === 'g_skin3' )) &&
                        <ToggleControl
                            label={__('Display Post Author')}
                            checked={!!displayPostAuthor}
                            onChange={(value) => setAttributes({displayPostAuthor: value})}
                        />
                    }

                    {
                        (( carouselLayoutStyle === 'skin2' || carouselLayoutStyle === 'skin3' ) || ( gridLayoutStyle === 'g_skin1' || gridLayoutStyle === 'g_skin2' || gridLayoutStyle === 'g_skin3') ) &&
                        <ToggleControl
                            label={__('Display Post Date')}
                            checked={!!displayPostDate}
                            onChange={(value) => setAttributes({displayPostDate: value})}
                        />

                    }

                    {
                        (gridLayoutStyle !== 'g_skin1' && gridLayoutStyle !== 'g_skin2') &&
                        <ToggleControl
                            label={__('Display Post Excerpt')}
                            checked={!!displayPostExcerpt}
                            onChange={(value) => setAttributes({displayPostExcerpt: value})}
                        />

                    }

                    {
                        ( (carouselLayoutStyle === 'skin1' || carouselLayoutStyle === 'skin2' || carouselLayoutStyle === 'skin3' ) || (gridLayoutStyle === 'g_skin3')) &&
                        <ToggleControl
                            label={__('Display Post Read More Button')}
                            checked={!!displayPostReadMoreButton}
                            onChange={(value) => setAttributes({displayPostReadMoreButton: value})}
                        />
                    }

                    {
                        ( (carouselLayoutStyle === 'skin1' || carouselLayoutStyle === 'skin2' || carouselLayoutStyle === 'skin3') || (gridLayoutStyle === 'g_skin3')) &&
                        <TextControl
                            label={__('Read More Button Text')}
                            type="text"
                            value={postReadMoreButtonText}
                            onChange={(value) => setAttributes({postReadMoreButtonText: value})}
                        />
                    }
                    {
                        displayPostReadMoreButton &&
                        <ToggleControl
                            label = { __('Open Links in New Tab?') }
                            checked = { !!linkTarget }
                            onChange = { (value) => setAttributes( { linkTarget: value } ) }
                        />
                    }
                </PanelBody>

                <PanelBody title={ __( 'Heading Settings' ) } initialOpen={ false }>
                    {
                        <ToggleControl
                            label={ __( 'Display Heading' ) }
                            checked={ !! displayPostHeading }
                            onChange={ ( value ) => setAttributes( { displayPostHeading: value } ) }
                        />
                    }

                    { displayPostHeading &&
                    <SelectControl
                        label={__('Heading Style')}
                        options={postHeadingStyleOption}
                        value={postHeadingStyle}
                        onChange={(newValue) => {
                            setAttributes({postHeadingStyle: newValue})
                        }}
                    />
                    }


                    { displayPostHeading &&
                    <TextControl
                        label={ __( 'Heading Text' ) }
                        type="text"
                        value={ postHeadingText }
                        onChange={ ( value ) => setAttributes( { postHeadingText: value } ) }
                    />
                    }

                    {
                        displayPostHeading &&
                        <div className={ 'gpl-input-panel gpl-mb-10' }>
                            <span className={ 'gpl-pb-5' }>{ __( 'Heading URL' ) }</span>
                            <URLInput
                                className="box-top guten-post-layout-flex-1"
                                value={ postHeadingLink }
                                onChange={ ( value ) => {
                                    setAttributes( { postHeadingLink: value } )
                                } }
                            />
                        </div>
                    }
                    {
                        displayPostHeading &&
                        <ToggleControl
                            label={ __( 'Open Links in New Tab?' ) }
                            checked={ !! postHeadingLinkTarget }
                            onChange={ ( value ) => setAttributes( { postHeadingLinkTarget: value } ) }
                        />
                    }
                    {
                        displayPostHeading &&
                        <div className="alignment gpl-mb-20">
                            <p className="title">{ __( 'Heading Align' ) }</p>
                            <AlignmentToolbar
                                value={ postHeadingAlign }
                                onChange={ value => setAttributes( { postHeadingAlign: value } ) }
                            />
                        </div>
                    }

                    {
                        <ToggleControl
                            label={ __( 'Sub Heading' ) }
                            checked={ !! displayPostSubHeading }
                            onChange={ ( value ) => setAttributes( { displayPostSubHeading: value } ) }
                        />
                    }

                    { displayPostSubHeading &&
                    <TextareaControl
                        label={ __( 'Sub Heading Text' ) }
                        type="text"
                        value={ postSubHeadingText }
                        onChange={ ( value ) => setAttributes( { postSubHeadingText: value } ) }
                    />
                    }


                </PanelBody>
                {
                    postLayout !== 'slides' &&
                    <PanelBody title={ __('Filter Settings') } initialOpen={ false }>
                        {
                            <ToggleControl
                                label={ __('Display Filter?') }
                                help={__('Attention: The post filter will only display on your site\'s preview/live pages/posts not while you\'re in editing mode in the WordPress Editor.')}
                                checked={ !!displayFilter }
                                onChange={ (value) => setAttributes({displayFilter: value}) }
                            />
                        }

                        { displayFilter &&
                        <ToggleControl
                            label={ __("Display 'All' Button in start of Filter?") }
                            checked={ !!displayAllButton }
                            onChange={ (value) => setAttributes({displayAllButton: value}) }
                        />
                        }

                        { displayAllButton && displayFilter &&
                        <TextControl
                            label={ __(" 'All' Button Text") }
                            type="text"
                            value={ allButtonText }
                            onChange={ (value) => setAttributes({allButtonText: value}) }
                        />
                        }
                        { displayFilter &&
                        <RangeControl
                            label={__('Max terms to show')}
                            value={maxTaxDisplay}
                            onChange={(value) => setAttributes({maxTaxDisplay: value})}
                            min={1}
                            max={200}
                        />
                        }
                        { displayFilter &&
                        <div className={'gpl-select-panel gpl-mb-10'}>
                            <span className={'gpl-pb-5'}>{ __('Filter By') }</span>
                            <SelectControl
                                options={FilterByOption}
                                value={filterBy}
                                onChange={(value) => {
                                    setAttributes({
                                        filterBy: '' !== value ? value : '',
                                        filterCats: '',
                                        filterTags: '',
                                    })
                                }}
                            />
                        </div>
                        }

                        { displayFilter && filterBy === 'categories' &&
                        <div className={'gpl-select-panel gpl-mb-10'}>
                            <span className={'gpl-pb-5'}>{__('Categories')}</span>
                            <MultiSelect
                                options={postCats}
                                selected={filterCats}
                                onSelectedChanged={(value) => {
                                    setAttributes({
                                        filterCats: '' !== value ? value : undefined,
                                    })
                                }}
                                overrideStrings={{
                                    selectSomeItems: __('Select Category'),
                                }}
                            />
                        </div>
                        }

                        { displayFilter && filterBy === 'tags' &&
                        <div className={'gpl-select-panel gpl-mb-10'}>
                            <span className={'gpl-pb-5'}>{__('Tags')}</span>
                            <MultiSelect
                                options={postTags}
                                selected={filterTags}
                                onSelectedChanged={(value) => {
                                    setAttributes({
                                        filterTags: '' !== value ? value : undefined,
                                    })
                                }}
                                overrideStrings={{
                                    selectSomeItems: __('Select Tag'),
                                }}
                            />

                        </div>
                        }


                    </PanelBody>
                }
                {
                    postLayout !== 'slides' &&
                    <PanelBody title={ __('Pagination Settings') } initialOpen={ false }>
                        {
                            <ToggleControl
                                label={ __('Display Pagination?') }
                                help={__('Attention: The post pagination will only display on your site\'s preview/live pages/posts not while you\'re in editing mode in the WordPress Editor.')}
                                checked={ !!displayPagination }
                                onChange={ (value) => setAttributes({displayPagination: value}) }
                            />
                        }

                        { displayPagination &&
                        <SelectControl
                            label={__('Pagination Type')}
                            options={postPaginationTypeOption}
                            value={paginationType}
                            onChange={(newValue) => {
                                setAttributes({paginationType: newValue})
                            }}
                        />
                        }

                        { displayPagination &&
                        <SelectControl
                            label={__('Pagination Style')}
                            options={postPaginationStyleOption}
                            value={paginationStyle}
                            onChange={(newValue) => {
                                setAttributes({paginationStyle: newValue})
                            }}
                        />
                        }

                        { displayPagination && paginationType === 'gpl-navigation' &&
                        <SelectControl
                            label={__('Navigation Position')}
                            options={postNavigationPositionOption}
                            value={navigationPosition}
                            onChange={(newValue) => {
                                setAttributes({navigationPosition: newValue})
                            }}
                        />
                        }

                        {
                            displayPagination &&
                            <div className="alignment gpl-mb-20">
                                <p className="title">{ __('Pagination Alignment') }</p>
                                <AlignmentToolbar
                                    value={ paginationAlign }
                                    onChange={ value => setAttributes({paginationAlign: value}) }
                                />
                            </div>
                        }
                    </PanelBody>

                }

				{
					postLayout !== 'slides' &&
					<PanelBody title={ __( 'CTA Settings' ) } initialOpen={ false }>
						{
							<ToggleControl
								label={ __( 'Display CTA Button' ) }
								checked={ !! displayPostCtaButton }
                                help={__('Attention: The post CTA button will only display on your site\'s preview/live pages/posts not while you\'re in editing mode in the WordPress Editor.')}
								onChange={ ( value ) => setAttributes( { displayPostCtaButton: value } ) }
							/>
						}

						{
							displayPostCtaButton &&
							<div className={ 'gpl-input-panel gpl-mb-10' }>
								<span className={ 'gpl-pb-5' }>{ __( 'CTA Button URL' ) }</span>
								<URLInput
									className="box-top guten-post-layout-flex-1"
									value={ postCtaButtonLink }
									onChange={ ( value ) => {
										setAttributes( { postCtaButtonLink: value } )
									} }
								/>
							</div>
						}

						{ displayPostCtaButton &&
						<TextControl
							label={ __( 'CTA Button Text' ) }
							type="text"
							value={ postCtaButtonText }
							onChange={ ( value ) => setAttributes( { postCtaButtonText: value } ) }
						/>
						}
						{
							displayPostCtaButton &&
							<ToggleControl
								label={ __( 'Open Links in New Tab?' ) }
								checked={ !! CtaLinkTarget }
								onChange={ ( value ) => setAttributes( { CtaLinkTarget: value } ) }
							/>
						}
						{
							displayPostCtaButton &&
							<ToggleControl
								label={ __( 'Active Button View?' ) }
								checked={ !! postCtaButtonStyle }
								onChange={ ( value ) => setAttributes( { postCtaButtonStyle: value } ) }
							/>
						}
						{
							displayPostCtaButton &&
							<ToggleControl
								label={ __( 'Display CTA Button Icon' ) }
								checked={ !! displayCtaButtonIcon }
								onChange={ ( value ) => setAttributes( { displayCtaButtonIcon: value } ) }
							/>
						}

						{
							displayPostCtaButton &&
							<div className="alignment gpl-mb-20">
								<p className="title">{ __( 'Button Align' ) }</p>
								<AlignmentToolbar
									value={ postCtaButtonAlign }
									onChange={ value => setAttributes( { postCtaButtonAlign: value } ) }
								/>
							</div>
						}

					</PanelBody>
				}

            </Fragment>
        );
    }

}
