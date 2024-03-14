/**
 * WordPress dependencies
 */

// Import registerBlockType() from block building libary
const { registerBlockType } = wp.blocks;

// React.createElement
const { Component, createElement, Fragment } = wp.element;

// Import __ from i18n internationalization library
const { __ } = wp.i18n;

// Import controls from block building library
const { AlignmentToolbar, BlockControls, InspectorControls, PanelColorSettings } = wp.blockEditor;

// Import components
const {
    Button,
    ButtonGroup,
    ColorPicker,
    Icon,
    PanelBody,
    PanelRow,
    RadioControl,
    RangeControl,
    SelectControl,
    TextControl,
    ToggleControl,
    Tooltip
    } = wp.components;

const { serverSideRender: ServerSideRender } = wp;

/**
 * Internal dependencies.
 */
import Margin from './margin';
import Padding from './padding';
import map from 'lodash/map';

const customEvent = new Event( 'gutenbergSlick' );

class SPTestimonialsSlider extends Component {

    constructor() {

        super( ...arguments );

		// Create toggles for each attribute; we create them here rather than
		// passing `this.createToggleAttribute( 'showarrows' )` directly to
		// `onChange` to avoid re-renders.
        this.toggleFilterPosts = this.createToggleAttribute( 'filterposts' );
        this.toggleShowFeaturedImages = this.createToggleAttribute( 'showfeaturedimages' );
        this.toggleShowQuotes = this.createToggleAttribute( 'showquotemarks' );
        this.toggleShowFeaturedImageBorder = this.createToggleAttribute( 'showimageborder' );

        this.toggleAutoplay = this.createToggleAttribute( 'autoplay' );
		this.toggleShowArrows = this.createToggleAttribute( 'showarrows' );
        this.toggleShowDots = this.createToggleAttribute( 'showdots' );
        this.toggleAdaptiveHeight = this.createToggleAttribute( 'adaptiveheight' );

        this.togglePaddingSync = this.createToggleAttribute( 'paddingsync' );
	}

    createToggleAttribute( propName ) {
		return () => {
			const value = this.props.attributes[ propName ];
			const { setAttributes } = this.props;

			setAttributes( { [ propName ]: ! value } );
            document.dispatchEvent( customEvent );
		};
	}

    /* TOOLTIPS
    --------------------------------------------------------------------- */

    // Tooltip - Slider - Filter Posts
    getFilterPostsHelp( checked ) {
        return checked ? __( 'Testimonials will be filtered.', 'socialproofslider' ) : __( 'Enable this setting to filter the testimonials.', 'socialproofslider' );
    }

    // Tooltip - Slider - Featured Images
    getShowFeaturedImagesHelp( checked ) {
        return checked ? __( 'Showing the Featured Images.', 'socialproofslider' ) : __( 'Toggle to show the Featured Image for each testimonial.', 'socialproofslider' );
    }

    // Tooltip - Slider - Show Quotation Marks
    getShowQuotesHelp( checked ) {
        return checked ? __( 'Showing quotation marks.', 'socialproofslider' ) : __( 'Toggle to show quotation marks around the testimonial text.', 'socialproofslider' );
    }

    // Tooltip - Slider - Show Quotation Marks
    getShowImageBorderHelp( checked ) {
        return checked ? __( 'Showing a border around featured images.', 'socialproofslider' ) : __( 'Toggle to show a border around the featured images.', 'socialproofslider' );
    }

    // Tooltip - Slider - Autoplay
    getAutoplayHelp( checked ) {
        return checked ? __( 'Autplay is enabled.', 'socialproofslider' ) : __( 'Toggle to advance the slides automatically.', 'socialproofslider' );
    }

    // Tooltip - Slider - Show Arrows
    getShowArrowsHelp( checked ) {
        return checked ? __( 'Showing the arrows on the Slider.', 'socialproofslider' ) : __( 'Toggle to show the arrows on both sides of the Slider.', 'socialproofslider' );
    }

    // Tooltip - Slider - Show Dots
    getShowDotsHelp( checked ) {
        return checked ? __( 'Showing the dot indicators below the Slider.', 'socialproofslider' ) : __( 'Toggle to show the dot indicators below the Slider.', 'socialproofslider' );
    }

    // Tooltip - Slider - Adaptive Height
    getAdaptiveHeightHelp( checked ) {
        return checked ? __( 'The slider will dynamically adjust height based on each slide\'s height.', 'socialproofslider' ) : __( 'Toggle to dynamically adjust slider height based on each slide\'s height.', 'socialproofslider' );
    }

    render() {

        const attributes = this.props.attributes;
        const setAttributes = this.props.setAttributes;

        document.dispatchEvent( customEvent )

        // Update DisplayTime attr
        function updateDisplayTime(value) {
            setAttributes({ displaytime: value });
            document.dispatchEvent( customEvent )
        }

        // Update BG Color attr
        function updateBGColor(value) {
            setAttributes({ bgcolor: value });
            document.dispatchEvent( customEvent )
        }

        // Update Arrows Color
        function updateArrowsColor(value) {
            setAttributes({ arrowscolor: value });
            document.dispatchEvent( customEvent )
        }

        // Update Arrows Hover Color
        function updateArrowsHoverColor(value) {
            setAttributes({ arrowshovercolor: value });
            document.dispatchEvent( customEvent )
        }

        // Update Dots Color
        function updateDotsColor(value) {
            setAttributes({ dotscolor: value });
            document.dispatchEvent( customEvent )
        }

        // Update Dots Hover Color
        function updateDotsHoverColor(value) {
            setAttributes({ dotshovercolor: value });
            document.dispatchEvent( customEvent )
        }

        // Update Image Border Color
        function updateImageBorderColor(value) {
            setAttributes({ imagebordercolor: value });
            document.dispatchEvent( customEvent )
        }

        // Update Testimonials Text Color
        function updateTestimonialTextColor(value) {
            setAttributes({ testimonialtextcolor: value });
            document.dispatchEvent( customEvent )
        }

        // Update Author name Color
        function updateAuthorNameColor(value) {
            setAttributes({ authornamecolor: value });
            document.dispatchEvent( customEvent )
        }

        // Update Author Title Color
        function updateAuthorTitleColor(value) {
            setAttributes({ authortitlecolor: value });
            document.dispatchEvent( customEvent )
        }

        /* Sort Posts By options. */
		const sortByOptions = [
			{ value: 'RAND', label: __( 'Random', 'socialproofslider' ) },
			{ value: 'DESC', label: __( 'Date DESC', 'socialproofslider' ) },
			{ value: 'ASC', label: __( 'Date ASC', 'socialproofslider' ) }
        ];

        /* CSS Units. */
		const cssUnits = [
			{ value: 'px', label: __( 'Pixel (px)', 'socialproofslider' ) },
			{ value: '%', label: __( 'Percent (%)', 'socialproofslider' ) },
			{ value: 'em', label: __( 'Em (em)', 'socialproofslider' ) }
        ];

        let selectedStyle = 1;

		if ( attributes.arrowstyle ) {
			selectedStyle = parseInt( attributes.arrowstyle.toString() );
		}

        const arrowStyles = [
            { name: __( 'Style 1', 'socialproofslider' ), key: 'angle'  },
            { name: __( 'Style 2', 'socialproofslider' ), key: 'angle-double'  },
            { name: __( 'Style 3', 'socialproofslider' ), key: 'arrow-circle'  },
            { name: __( 'Style 4', 'socialproofslider' ), key: 'arrow-circle-o'  },
            { name: __( 'Style 5', 'socialproofslider' ), key: 'arrow'  },
            { name: __( 'Style 6', 'socialproofslider' ), key: 'caret'  },
            { name: __( 'Style 7', 'socialproofslider' ), key: 'caret-square-o'  },
            { name: __( 'Style 8', 'socialproofslider' ), key: 'chevron-circle'  },
            { name: __( 'Style 9', 'socialproofslider' ), key: 'chevron'  }
        ];

        /* ------------------------------------------------------------------ */

        // Display block preview and UI
        return (
            <>
                <ServerSideRender
                    block="social-proof-slider/main"
                    attributes={ attributes }
                />
                <BlockControls>
                    <AlignmentToolbar
                        value={ attributes.textalign }
                        onChange={ ( nextAlign ) => {
                            setAttributes( { textalign: nextAlign } );
                            document.dispatchEvent( customEvent )
                        } }
                    />
                </BlockControls>
                <InspectorControls key="inspector">
                    <PanelBody
                        title={ __( 'Post Settings', 'socialproofslider' ) }
                        initialOpen={ false }
                        className={ 'spslider-inspector-panel' }>
                        <PanelRow>
                            <SelectControl
                                label={ __( 'Sort Posts By:', 'socialproofslider' ) }
                                help={ __( 'Choose between Random, Date DESC, or Date ASC.', 'socialproofslider' ) }
                                options={ sortByOptions }
                                value={ attributes.sortpostsby }
                                onChange={ ( value ) => this.props.setAttributes({ sortpostsby: value }) }
                            />
                        </PanelRow>
                        <hr />
                        <PanelRow>
                            <ToggleControl
                            label={ __( 'Filter Posts', 'socialproofslider' ) }
                            checked={ !! attributes.filterposts }
                            onChange={ this.toggleFilterPosts }
                            help={ this.getFilterPostsHelp }
                            />
                        </PanelRow>
                        { attributes.filterposts ?
                            <Fragment>
                                <RadioControl
                                    label={ __( 'Show or Hide', 'socialproofslider' ) }
                                    help={ __( 'Choose to show or hide specific testimonials.', 'socialproofslider' ) }
                                    options={ [
                                        { label: __( 'Show Only These Testimonials', 'socialproofslider' ), value: 'show' },
                                        { label: __( 'Hide These Testimonials', 'socialproofslider' ), value: 'hide' },
                                    ] }
                                    onChange={ ( value ) => this.props.setAttributes({ filtershowhide: value }) }
                                    selected={ attributes.filtershowhide }
                                />
                                <RadioControl
                                    label={ __( 'Filter By', 'socialproofslider' ) }
                                    help={ __( 'Choose how to filter the testimonials.', 'socialproofslider' ) }
                                    options={ [
                                        { label: __( 'Post ID', 'socialproofslider' ), value: 'postid' },
                                        { label: __( 'Category', 'socialproofslider' ), value: 'cat' },
                                    ] }
                                    onChange={ ( value ) => this.props.setAttributes({ filterby: value }) }
                                    selected={ attributes.filterby }
                                />
                                { attributes.filterby == 'postid' ?
                                    <TextControl
                                        label="Post ID"
                                        value={ attributes.postids }
                                        help={ __( 'Can be a comma separated list.', 'socialproofslider' ) }
                                        onChange={ ( value ) => this.props.setAttributes({ postids: value }) }
                                    />
                                    :
                                    null
                                }
                                { attributes.filterby == 'cat' ?
                                    <TextControl
                                        label="Category Slug"
                                        value={ attributes.catslug }
                                        help={ __( 'Can be a comma separated list.', 'socialproofslider' ) }
                                        onChange={ ( value ) => this.props.setAttributes({ catslug: value }) }
                                    />
                                    :
                                    null
                                }
                            </Fragment>
                            :
                            null
                        }
                        <hr />
                        <PanelRow>
                            <ToggleControl
                            label={ __( 'Show Featured Images', 'socialproofslider' ) }
                            checked={ !! attributes.showfeaturedimages }
                            onChange={ this.toggleShowFeaturedImages }
                            help={ this.getShowFeaturedImagesHelp }
                            />
                        </PanelRow>
                        { attributes.showfeaturedimages ?
                            <Fragment>
                                <RangeControl
                                    label={ __( 'Image Border Radius (%)', 'socialproofslider' ) }
                                    value={ attributes.imageborderradius }
                                    min={ 0 }
                                    max={ 50 }
                                    step={ 1 }
                                    initialPosition={ 25 }
                                    onChange={ ( value ) => this.props.setAttributes({ imageborderradius: value }) }
                                    allowReset={ true }
                                    help={ __( 'Define the image border radius.', 'socialproofslider' ) }
                                />
                                <ToggleControl
                                label={ __( 'Image Border', 'socialproofslider' ) }
                                checked={ !! attributes.showimageborder }
                                onChange={ this.toggleShowFeaturedImageBorder }
                                help={ this.getShowImageBorderHelp }
                                />
                                { attributes.showimageborder ?
                                    <RangeControl
                                        label={ __( 'Border Size (px)', 'socialproofslider' ) }
                                        value={ attributes.imagebordersize }
                                        min={ 0 }
                                        max={ 50 }
                                        step={ 1 }
                                        initialPosition={ 5 }
                                        onChange={ ( value ) => this.props.setAttributes({ imagebordersize: value }) }
                                        allowReset={ true }
                                        help={ __( 'Define the thickness of the border.', 'socialproofslider' ) }
                                    />
                                    :
                                    null
                                }
                            </Fragment>
                            :
                            null
                        }
                        <hr />
                        <PanelRow>
                            <ToggleControl
                            label={ __( 'Show Quotation Marks', 'socialproofslider' ) }
                            checked={ !! attributes.showquotemarks }
                            onChange={ this.toggleShowQuotes }
                            help={ this.getShowQuotesHelp }
                            />
                        </PanelRow>
                    </PanelBody>
                    <PanelBody
                        title={ __( 'Slider Settings', 'socialproofslider' ) }
                        initialOpen={ false }
                        className={ 'spslider-inspector-panel' }>
                        <PanelRow>
                            <ToggleControl
                            label={ __( 'Autoplay', 'socialproofslider' ) }
                            checked={ !! attributes.autoplay }
                            onChange={ this.toggleAutoplay }
                            help={ this.getAutoplayHelp }
                            />
                        </PanelRow>
                        { attributes.autoplay ?
                            <RangeControl
            					label={ __( 'Display Time (seconds)', 'socialproofslider' ) }
                                beforeIcon={ 'clock' }
            					value={ attributes.displaytime }
            					min={ 1 }
            					max={ 10 }
                                step={ 0.5 }
                                initialPosition={ 3 }
            					onChange={ ( value ) => this.props.setAttributes({ displaytime: value }) }
                                allowReset={ true }
                                help={ __( 'How long to display each slide before advancing to the next.', 'socialproofslider' ) }
            				/>
                            :
                            null
                        }
                        <hr />
                        <PanelRow>
                            <RadioControl
                                label={ __( 'Animation Style', 'socialproofslider' ) }
                                help={ __( 'Choose how to animate each slide.', 'socialproofslider' ) }
                                options={ [
                                    { label: __( 'Fade', 'socialproofslider' ), value: 'fade' },
                                    { label: __( 'Slide', 'socialproofslider' ), value: 'slide' },
                                ] }
                                onChange={ ( value ) => this.props.setAttributes({ animationstyle: value }) }
                                selected={ attributes.animationstyle }
                            />
                        </PanelRow>
                        <hr />
                        <PanelRow>
                            <ToggleControl
                            label={ __( 'Show Arrows', 'socialproofslider' ) }
                            checked={ !! attributes.showarrows }
                            onChange={ this.toggleShowArrows }
                            help={ this.getShowArrowsHelp }
                            />
                        </PanelRow>
                        { attributes.showarrows ?
                            <Fragment>
								<p><strong>{ __( 'Arrows Style', 'socialproofslider' ) }</strong></p>
								<ButtonGroup aria-label={ __( 'Arrows Style', 'socialproofslider' ) }>
									{ map( arrowStyles, ({ name, key }) => (
										<Tooltip text={ name } key={ key }>
											{
                                                attributes.arrowstyle == key ?

                                                <Button
    												key={ key }
    												className="spslider-arrow-selector-button selected"
    												isSmall
    												onClick={ () => {
    													this.props.setAttributes({ arrowstyle: key });
    													// this.setState({ 'arrowstyle': false });
    												} }
    											>
                                                <span className={"icon icon-" + key }><i class={ "fa fa-" + key + "-left" }></i> <i class={ "fa fa-" + key + "-right" }></i></span>
    											</Button>

                                                :

                                                <Button
    												key={ key }
    												className="spslider-arrow-selector-button"
    												isSmall
    												onClick={ () => {
    													this.props.setAttributes({ arrowstyle: key });
    													// this.setState({ 'arrowstyle': false });
    												} }
    											>
                                                <span className={"icon icon-" + key }><i class={ "fa fa-" + key + "-left" }></i> <i class={ "fa fa-" + key + "-right" }></i></span>
    											</Button>

                                            }
										</Tooltip>
									) ) }
								</ButtonGroup>
								<p><i>{ __( 'Change the style of the slider arrows.', 'socialproofslider' ) }</i></p>
							</Fragment>
                            :
                            null
                        }
                        <hr />
                        <PanelRow>
                            <ToggleControl
                            label={ __( 'Show Dots', 'socialproofslider' ) }
                            checked={ !! attributes.showdots }
                            onChange={ this.toggleShowDots }
                            help={ this.getShowDotsHelp }
                            />
                        </PanelRow>
                        <hr />
                        <PanelRow>
                            <ToggleControl
                            label={ __( 'Adaptive Height', 'socialproofslider' ) }
                            checked={ !! attributes.adaptiveheight }
                            onChange={ this.toggleAdaptiveHeight }
                            help={ this.getAdaptiveHeightHelp }
                            />
                        </PanelRow>
                        { ! attributes.adaptiveheight ?
                            <RadioControl
                                label={ __( 'Vertical Align', 'socialproofslider' ) }
                                help={ __( 'Choose how to vertically align each slide.', 'socialproofslider' ) }
                                options={ [
                                    { label: __( 'Top', 'socialproofslider' ), value: 'align_top' },
                                    { label: __( 'Middle', 'socialproofslider' ), value: 'align_middle' },
                                    { label: __( 'Bottom', 'socialproofslider' ), value: 'align_bottom' },
                                ] }
                                onChange={ ( value ) => this.props.setAttributes({ verticalalign: value }) }
                                selected={ attributes.verticalalign }
                            />
                            :
                            null
                        }
                    </PanelBody>
                    <PanelBody
    					title={ __( 'Padding', 'socialproofslider' ) }
    					initialOpen={ false }
                        className={ 'spslider-inspector-panel' }>
                        <SelectControl
                            label={ __( 'Padding Unit', 'socialproofslider' ) }
                            help={ __( 'Choose between pixel, percent, or em units.', 'socialproofslider' ) }
                            options={ cssUnits }
                            value={ attributes.paddingunit }
                            onChange={ ( value ) => this.props.setAttributes({ paddingunit: value }) }
                        />
                        <ToggleControl
                            label={ __( 'Sync Padding', 'socialproofslider' ) }
                            checked={ !! attributes.paddingsync }
                            onChange={ this.togglePaddingSync }
                            help={ __( 'Padding on all sides will have the same value.', 'socialproofslider' ) }
                        />
                        { ! attributes.paddingsync ?
                            <Padding
                                /* Padding top. */
                                paddingEnableTop={ true }
                                paddingTop={ attributes.paddingtop }
                                paddingTopMin="0"
                                paddingTopMax="200"
                                onChangePaddingTop={ paddingtop => setAttributes({ paddingtop }) }

                                /* Padding right. */
                                paddingEnableRight={ true }
                                paddingRight={ attributes.paddingright }
                                paddingRightMin="0"
                                paddingRightMax="200"
                                onChangePaddingRight={ paddingright => setAttributes({ paddingright }) }

                                /* Padding bottom. */
                                paddingEnableBottom={ true }
                                paddingBottom={ attributes.paddingbottom }
                                paddingBottomMin="0"
                                paddingBottomMax="200"
                                onChangePaddingBottom={ paddingbottom => setAttributes({ paddingbottom }) }

                                /* Padding left. */
                                paddingEnableLeft={ true }
                                paddingLeft={ attributes.paddingleft }
                                paddingLeftMin="0"
                                paddingLeftMax="200"
                                onChangePaddingLeft={ paddingleft => setAttributes({ paddingleft }) }
                            />
                            :
                            <Padding
                                /* Padding. */
                                paddingEnable={ true }
                                padding={ attributes.padding }
                                paddingMin="0"
                                paddingMax="200"
                                onChangePadding={ padding => setAttributes({ padding }) }
                            />
                        }
                        { attributes.showimageborder ?
                            <Fragment>
                                <hr />
                                <RangeControl
                                    label={ __( 'Image Border Padding (px)', 'socialproofslider' ) }
                                    value={ attributes.imageborderpadding }
                                    min={ 1 }
                                    max={ 100 }
                                    step={ 1 }
                                    initialPosition={ 4 }
                                    onChange={ ( value ) => this.props.setAttributes({ imageborderpadding: value }) }
                                    allowReset={ true }
                                    help={ __( 'Padding between the featured image and its border.', 'socialproofslider' ) }
                                />
                            </Fragment>
                            :
                            null
                        }
                    </PanelBody>
                    <PanelBody
                        title={ __( 'Colors', 'socialproofslider' ) }
                        initialOpen={ false }
                        className={ 'spslider-inspector-panel' }>
                        <PanelColorSettings
                        title={ __( 'Background Color', 'socialproofslider' ) }
                        initialOpen={ false }
                        colorSettings={[{
                            value: attributes.bgcolor,
                            onChange: updateBGColor,
                            label: __( 'Slider Background Color', 'socialproofslider' )
                        }]}
                        />
                        <PanelColorSettings
                        title={ __('Slider Colors') }
                        initialOpen={ false }
                        colorSettings={[
                            {
                            value: attributes.arrowscolor,
                            onChange: updateArrowsColor,
                            label: __( 'Arrows Color', 'socialproofslider' ),
                            },
                            {
                            value: attributes.arrowshovercolor,
                            onChange: updateArrowsHoverColor,
                            label: __( 'Arrows Hover Color', 'socialproofslider' ),
                            },
                            {
                            value: attributes.dotscolor,
                            onChange: updateDotsColor,
                            label: __( 'Dots Color', 'socialproofslider' ),
                            },
                            {
                            value: attributes.dotshovercolor,
                            onChange: updateDotsHoverColor,
                            label: __( 'Dots Hover Color', 'socialproofslider' ),
                            },
                            {
                            value: attributes.imagebordercolor,
                            onChange: updateImageBorderColor,
                            label: __( 'Image Border Color', 'socialproofslider' ),
                            },
                        ]}
                        />
                        <PanelColorSettings
                        title={ __('Text Colors') }
                        initialOpen={ false }
                        colorSettings={[
                            {
                            value: attributes.testimonialtextcolor,
                            onChange: updateTestimonialTextColor,
                            label: __( 'Testimonial Text Color', 'socialproofslider' ),
                            },
                            {
                            value: attributes.authornamecolor,
                            onChange: updateAuthorNameColor,
                            label: __( 'Author Name Color', 'socialproofslider' ),
                            },
                            {
                            value: attributes.authortitlecolor,
                            onChange: updateAuthorTitleColor,
                            label: __( 'Author Title Color', 'socialproofslider' ),
                            },
                        ]}
                        />
                    </PanelBody>
                </InspectorControls>
            </>
        );

    }

}


export default SPTestimonialsSlider;
