/**
 * BLOCK: Timeline.
 */

import classnames from "classnames"
import map from "lodash/map"
import times from "lodash/times"
import TimelineIcons from "../../../dist/blocks/controls/Icons.json"
import FontIconPicker from "@fonticonpicker/react-fonticonpicker"
import renderSVG from "../../../dist/blocks/controls/renderIcon"
import contentTimelineStyle from "./inline-styles"
import attributes from "./attributes"
import TmClasses from "./classes"
import AlignClass from "./align-classes"
import TypographyControl from "../../components/typography"
import { registerBlockType, __, PanelBody, RichText, Button, ColorPalette, SelectControl, TextControl, TabPanel, ToggleControl, PanelColorSettings, InspectorControls, RangeControl, MediaUpload, Component, Fragment } from '../../wp-imports'

const { apiFetch } = wp;
const { decodeEntities } = wp.htmlEntities
let svg_icons = Object.keys(TimelineIcons)


class Timeline extends Component {

    constructor() {
        super(...arguments)
        // this.splitBlock = this.splitBlock.bind(this)
        // this.getTimelineicon = this.getTimelineicon.bind(this)
        // this.toggleDisplayPostDate = this.toggleDisplayPostDate.bind(this)
    }

    componentDidMount() {
        //Store client id.
        this.props.setAttributes({ block_id: this.props.clientId })

        var id = this.props.clientId
        window.addEventListener("load", this.timelineContent_back(id))
        window.addEventListener("resize", this.timelineContent_back(id))
        var time = this
        jQuery(".edit-post-layout__content").scroll(function (event) {
            time.timelineContent_back(id)
        })

        // Pushing Style tag for this block css.
        const $style = document.createElement("style")
        $style.setAttribute("id", "twp-timeline-style-" + this.props.clientId)
        document.head.appendChild($style)


    }
    componentDidUpdate() {

    }
    splitBlock(before, after, ...blocks) {
        const {
            attributes,
            insertBlocksAfter,
            setAttributes,
            onReplace,
        } = this.props

        if (after) {
            // Append "After" content as a new paragraph block to the end of
            // any other blocks being inserted after the current paragraph.
            blocks.push(createBlock("core/paragraph", { content: after }))
        }

        if (blocks.length && insertBlocksAfter) {
            insertBlocksAfter(blocks)
        }

        const { content } = attributes
        if (!before) {
            // If before content is omitted, treat as intent to delete block.
            onReplace([])
        } else if (content !== before) {
            // Only update content if it has in-fact changed. In case that user
            // has created a new paragraph at end of an existing one, the value
            // of before will be strictly equal to the current content.
            setAttributes({ content: before })
        }


    }

    /* Render output at backend */
    get_content() {
        const { attributes, setAttributes, mergeBlocks, insertBlocksAfter, onReplace } = this.props
        
        const {
            headingTag,
            design,
            timelinAlignment,
            displayPostDate,
            icon,
            tm_content,
            timelineItem
        } = attributes
        
        // Add CSS.
		var element = document.getElementById( "twp-timeline-style-" + this.props.clientId )
		if( null != element && "undefined" != typeof element ) {
			element.innerHTML = contentTimelineStyle( this.props )
        }
        
        let hasItems = Array.isArray(tm_content) && tm_content.length


        if (!hasItems) {
            return (
                <Fragment>
                    <Placeholder
                        icon="admin-post"
                        label={__("Timeline")}
                    >
                    </Placeholder>
                </Fragment>
            )
        } else {

            let data_copy   = [ ...tm_content ]
            let content_align_class = AlignClass( this.props.attributes, 0 ) // Get classname for layout alignment
            return (
                <div className={classnames("main-timeline", design)}>
                    {
                        tm_content.map((post, index) => {

                            let image_icon_html = ""
                            if ( post.image_icon == "icon" ) {
                                if ( post.icon ) {
                                    image_icon_html = <span>{ renderSVG(post.icon) }</span>
                                }
                            } else {
                                if ( post.image ) {
                                    image_icon_html = <span><img src={post.image.url} /></span>
                                }
                            } 
                            if(timelinAlignment == "center"){
								content_align_class = AlignClass( this.props.attributes, index )
							}
                          

                            if ( timelineItem <= index ) {
								return
							}
                            const Tag = this.props.attributes.headingTag
                            const icon = this.props.attributes.icon

                            return (
                                <article className={ classnames( "timeline", "twp-" + content_align_class ) } key={index} >
                                    <div class="timeline-content">

                                        <div class="circle">
                                            { image_icon_html }
                                        </div>

                                        <div class="content">
                                            <RichText
                                                tagName={ __( "span" ) }
                                                value={ post.time_date }
                                                placeholder={ __( "Write a Heading" ) }
                                                className='year'
                                                onChange={ ( value ) => {
                                                    var p = {                                                         
                                                        "time_heading"   :   data_copy[index]["time_heading"],
                                                        "time_desc"      :   data_copy[index]["time_desc"],
                                                        "time_date"      :   value ,
                                                        "image_icon"     :   data_copy[index]["image_icon"] ,
                                                        "icon"           :   data_copy[index]["icon"] ,
                                                        "image"          :   data_copy[index]["image"]
                                                    }
                                                    data_copy[index] = p
                                                    setAttributes( { "tm_content": data_copy } )
                                                } }
                                                onMerge={ mergeBlocks }
                                                unstableOnSplit={
                                                    insertBlocksAfter ?
                                                        ( before, after, ...blocks ) => {
                                                            setAttributes( { content: before } )
                                                            insertBlocksAfter( [
                                                                ...blocks,
                                                                createBlock( "core/paragraph", { content: after } ),
                                                            ] )
                                                        } :
                                                        undefined
                                                }
                                                onRemove={ () => onReplace( [] ) }
                                            />
                                            <RichText
                                                tagName={ Tag }
                                                value={ post.time_heading }
                                                placeholder={ __( "Write a Heading" ) }
                                                className='title'
                                                onChange={ ( value ) => {
                                                    var p = { 
                                                        "time_heading"   :   value,
                                                        "time_desc"      :   data_copy[index]["time_desc"],
                                                        "time_date"      :   data_copy[index]["time_date"] ,
                                                        "image_icon"     :   data_copy[index]["image_icon"] ,
                                                        "icon"           :   data_copy[index]["icon"] ,
                                                        "image"          :   data_copy[index]["image"]

                                                    }
                                                    data_copy[index] = p
                                                    setAttributes( { "tm_content": data_copy } )
                                                } }
                                                onMerge={ mergeBlocks }
                                                unstableOnSplit={
                                                    insertBlocksAfter ?
                                                        ( before, after, ...blocks ) => {
                                                            setAttributes( { content: before } )
                                                            insertBlocksAfter( [
                                                                ...blocks,
                                                                createBlock( "core/paragraph", { content: after } ),
                                                            ] )
                                                        } :
                                                        undefined
                                                }
                                                onRemove={ () => onReplace( [] ) }
                                            />
                                            
                                            <RichText
                                                tagName= "p"
                                                value={ post.time_desc }
                                                placeholder={ __( "Write a Description" ) }
                                                className='description'
                                                onChange={ ( value ) => {
                                                    var p = { 
                                                        "time_heading"   :   data_copy[index]["time_heading"],
                                                        "time_desc"      :   value ,
                                                        "time_date"      :   data_copy[index]["time_date"] ,
                                                        "image_icon"     :   data_copy[index]["image_icon"] ,
                                                        "icon"           :   data_copy[index]["icon"] ,
                                                        "image"          :   data_copy[index]["image"]
                                                    }
                                                    data_copy[index] = p
                                                    setAttributes( { "tm_content": data_copy } )
                                                } }
                                                onMerge={ mergeBlocks }
                                                unstableOnSplit={ this.splitBlock }
                                                onRemove={ () => onReplace( [] ) }
                                            />
                                            <div class="icon"><span></span></div>
                                        </div>
                                    </div>
                                </article>
                            )

                        })
                    }
                </div>
            )
        }

    }

    /**
     * [getTimelineicon description]
     * @param  {[type]} value [description]
     * @return {[type]}       [description]
     */
    getTimelineicon(value) {
        this.props.setAttributes( { icon: value } )
    }

    
    saveTimeline = (value, index) => {

        const { attributes, setAttributes } = this.props
        const { tm_content } = attributes
        
        const newItems = tm_content.map((item, thisIndex) => {
            if (index === thisIndex) {
                item = { ...item, ...value }
            }

            return item
        })

        setAttributes({
            tm_content: newItems,
        })
    }

    /*  Js for timeline line and inner line filler*/
    timelineContent_back() {

    }
    render() {


        const { className, setAttributes, insertBlocksAfter, mergeBlocks, onReplace, attributes: { tm_content, design, headingAlign, separatorHeight, headSpace, separatorSpace, accentColor, iconColor,dateColor, headingColor,dateFontSize, subHeadingColor, backgroundColor, separatorColor, separatorFillColor, separatorBg, separatorBorder, borderFocus, headingTag, headFontSizeType, headFontSize, dateFontSizeType , headFontSizeTablet, headFontSizeMobile,dateFontSizeTablet, dateFontSizeMobile, headFontFamily, headFontWeight, headFontSubset, headLineHeightType, headLineHeight, headLineHeightTablet, headLineHeightMobile, headLoadGoogleFonts, timelineItem, timelinAlignment, arrowlinAlignment, subHeadFontSizeType, subHeadFontSize, subHeadFontSizeTablet, subHeadFontSizeMobile, subHeadFontFamily, subHeadFontWeight, subHeadFontSubset, subHeadLineHeightType, subHeadLineHeight, subHeadLineHeightTablet, subHeadLineHeightMobile, subHeadLoadGoogleFonts, verticalSpace, horizontalSpace, separatorwidth, borderwidth, connectorBgsize, dateBottomspace, align, icon,  dateFontsizeType, dateFontsize, dateFontsizeTablet, dateFontsizeMobile, dateFontFamily, dateFontWeight, dateFontSubset, dateLineHeightType, dateLineHeight, dateLineHeightTablet, dateLineHeightMobile, dateLoadGoogleFonts, iconSize, borderRadius, bgPadding, block_id, iconFocus, iconBgFocus,  displayPostDate, stack }, } = this.props

        const renderDateSettings = ( index ) => {
			return (
				<Fragment key ={index}>
					<TextControl
						label= { __( "Date" ) + " " + ( index + 1 ) + " " + __( "Settings" ) }
						value= { tm_content[ index ].title }
						onChange={ value => {
							this.saveDate( { title: value }, index )
						} }
					/>
				</Fragment>
			)
        }
        
        const sizeTypes = [
            { key: "px", name: __("px") },
            { key: "em", name: __("em") },
        ]

        let loadHeadGoogleFonts
        let loadSubHeadGoogleFonts
        let loadDateGoogleFonts

        if (headLoadGoogleFonts == true) {

            const headconfig = {
                google: {
                    families: [headFontFamily + (headFontWeight ? ":" + headFontWeight : "")],
                },
            }

            loadHeadGoogleFonts = (
                <WebfontLoader config={headconfig}>
                </WebfontLoader>
            )
        }

        if (subHeadLoadGoogleFonts == true) {

            const subHeadconfig = {
                google: {
                    families: [subHeadFontFamily + (subHeadFontWeight ? ":" + subHeadFontWeight : "")],
                },
            }

            loadSubHeadGoogleFonts = (
                <WebfontLoader config={subHeadconfig}>
                </WebfontLoader>
            )
        }

        if (dateLoadGoogleFonts == true) {

            const dateconfig = {
                google: {
                    families: [dateFontFamily + (dateFontWeight ? ":" + dateFontWeight : "")],
                },
            }

            loadDateGoogleFonts = (
                <WebfontLoader config={dateconfig}>
                </WebfontLoader>
            )
        }


        const timelineControls = ( index ) => {

            let timeline_control = ""
            let timeline_control_hover = ""
            console.log( tm_content[index].image_icon )
    
            if ("image" == tm_content[index].image_icon) {
    
                timeline_control = (
                    <Fragment>
                        <p className="uagb-setting-label">{__("Text Color")}<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: tm_content[index].label_color }} ></span></span></p>
                        <ColorPalette
                            value={tm_content[index].label_color}
                            onChange={(value) => this.saveTimeline({ label_color: value }, index)}
                            allowReset
                        />
                        <p className="uagb-setting-label">{__("Image Background Color")}<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: tm_content[index].icon_bg_color }} ></span></span></p>
                        <ColorPalette
                            value={tm_content[index].icon_bg_color}
                            onChange={(value) => this.saveTimeline({ icon_bg_color: value }, index)}
                            allowReset
                        />
                        <p className="uagb-setting-label">{__("Image Border Color")}<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: tm_content[index].icon_border_color }} ></span></span></p>
                        <ColorPalette
                            value={tm_content[index].icon_border_color}
                            onChange={(value) => this.saveTimeline({ icon_border_color: value }, index)}
                            allowReset
                        />
                    </Fragment>
                )
                timeline_control_hover = (
                    <Fragment>
                        <p className="uagb-setting-label">{__("Text Hover Color")}<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: tm_content[index].label_hover_color }} ></span></span></p>
                        <ColorPalette
                            value={tm_content[index].label_hover_color}
                            onChange={(value) => this.saveTimeline({ label_hover_color: value }, index)}
                            allowReset
                        />
                        <p className="uagb-setting-label">{__("Image Background Hover Color")}<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: tm_content[index].icon_bg_hover_color }} ></span></span></p>
                        <ColorPalette
                            value={tm_content[index].icon_bg_hover_color}
                            onChange={(value) => this.saveTimeline({ icon_bg_hover_color: value }, index)}
                            allowReset
                        />
                        <p className="uagb-setting-label">{__("Image Border Hover Color")}<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: tm_content[index].icon_border_hover_color }} ></span></span></p>
                        <ColorPalette
                            value={tm_content[index].icon_border_hover_color}
                            onChange={(value) => this.saveTimeline({ icon_border_hover_color: value }, index)}
                            allowReset
                        />
                    </Fragment>
                )
            } else {
    
                timeline_control = (
                    <Fragment>
                        <p className="uagb-setting-label">{__("Text Color")}<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: tm_content[index].label_color }} ></span></span></p>
                        <ColorPalette
                            value={tm_content[index].label_color}
                            onChange={(value) => this.saveTimeline({ label_color: value }, index)}
                            allowReset
                        />
                        <p className="uagb-setting-label">{__("Icon Color")}<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: tm_content[index].icon_color }} ></span></span></p>
                        <ColorPalette
                            value={tm_content[index].icon_color}
                            onChange={(value) => this.saveTimeline({ icon_color: value }, index)}
                            allowReset
                        />
                        <p className="uagb-setting-label">{__("Icon Background Color")}<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: tm_content[index].icon_bg_color }} ></span></span></p>
                        <ColorPalette
                            value={tm_content[index].icon_bg_color}
                            onChange={(value) => this.saveTimeline({ icon_bg_color: value }, index)}
                            allowReset
                        />
                        <p className="uagb-setting-label">{__("Icon Border Color")}<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: tm_content[index].icon_border_color }} ></span></span></p>
                        <ColorPalette
                            value={tm_content[index].icon_border_color}
                            onChange={(value) => this.saveTimeline({ icon_border_color: value }, index)}
                            allowReset
                        />
                    </Fragment>
                )
                timeline_control_hover = (
                    <Fragment>
                        <p className="uagb-setting-label">{__("Text Hover Color")}<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: tm_content[index].label_hover_color }} ></span></span></p>
                        <ColorPalette
                            value={tm_content[index].label_hover_color}
                            onChange={(value) => this.saveTimeline({ label_hover_color: value }, index)}
                            allowReset
                        />
                        <p className="uagb-setting-label">{__("Icon Hover Color")}<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: tm_content[index].icon_hover_color }} ></span></span></p>
                        <ColorPalette
                            value={tm_content[index].icon_hover_color}
                            onChange={(value) => this.saveTimeline({ icon_hover_color: value }, index)}
                            allowReset
                        />
                        <p className="uagb-setting-label">{__("Icon Background Hover Color")}<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: tm_content[index].icon_bg_hover_color }} ></span></span></p>
                        <ColorPalette
                            value={tm_content[index].icon_bg_hover_color}
                            onChange={(value) => this.saveTimeline({ icon_bg_hover_color: value }, index)}
                            allowReset
                        />
                        <p className="uagb-setting-label">{__("Icon Border Hover Color")}<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: tm_content[index].icon_border_hover_color }} ></span></span></p>
                        <ColorPalette
                            value={tm_content[index].icon_border_hover_color}
                            onChange={(value) => this.saveTimeline({ icon_border_hover_color: value }, index)}
                            allowReset
                        />
                    </Fragment>
                )
            }
    
            return (
                <PanelBody key={index}
                    title={__("Timeline ") + " " + (index + 1) + " " + __("Settings")}
                    initialOpen={false}
                >
                    <SelectControl
                        label={__("Image / Icon")}
                        value={tm_content[index].image_icon}
                        options={[
                            { value: "icon", label: __("Icon") },
                            { value: "image", label: __("Image") },
                        ]}
                        onChange={value => {
                            this.saveTimeline({ image_icon: value }, index)
                        }}
                    />
                    {"icon" == tm_content[index].image_icon &&
                        <Fragment>
                            <p className="components-base-control__label">{__("Icon")}</p>
                            <FontIconPicker
                                icons={svg_icons}
                                renderFunc={renderSVG}
                                theme="default"
                                value={tm_content[index].icon}
                                onChange={value => {
                                    this.saveTimeline({ icon: value }, index)
                                }}
                                isMulti={false}
                                noSelectedPlaceholder={__("Select Icon")}
                            />
                        </Fragment>
                    }
                    {"image" == tm_content[index].image_icon &&
                        <Fragment>
                            <MediaUpload
                                title={__("Select Image")}
                                onSelect={value => {
                                    this.saveTimeline({ image: value }, index)
                                }}
                                allowedTypes={["image"]}
                                value={tm_content[index].image}
                                render={({ open }) => (
                                    <Button isDefault onClick={open}>
                                        {!tm_content[index].image ? __("Select Image") : __("Replace image")}
                                    </Button>
                                )}
                            />
                            {tm_content[index].image &&
                                <Button
                                    className="uagb-rm-btn"
                                    onClick={value => {
                                        this.saveTimeline({ image: null }, index)
                                    }}
                                    isLink isDestructive>
                                    {__("Remove Image")}
                                </Button>
                            }
                        </Fragment>
                    }
                </PanelBody>
            )

        }

        const renderSettings = (
            <PanelBody title={__("Date Settings")} initialOpen={false} >
                <ToggleControl
                    label={__("Display Post Date")}
                    checked={displayPostDate}
                    onChange={this.toggleDisplayPostDate}
                />
    
                {displayPostDate && times(timelineItem, n => renderDateSettings(n))}
    
                {displayPostDate && (timelinAlignment !== "center") && <RangeControl
                    label={__("Date Bottom Spacing")}
                    value={dateBottomspace}
                    onChange={(value) => setAttributes({ dateBottomspace: value })}
                    min={0}
                    max={50}
                    allowReset
                />
                }
    
                {displayPostDate && <Fragment>
                    <hr className="uagb-editor__separator" />
                    <p className="uagb-setting-label">{__("Date Color")}<span className="components-base-control__label"><span className="component-color-indicator" style={{ backgroundColor: dateColor }} ></span></span></p>
                    <ColorPalette
                        value={dateColor}
                        onChange={(colorValue) => setAttributes({ dateColor: colorValue })}
                        allowReset
                    />
                    <hr className="uagb-editor__separator" />
                    <h2>{__("Date Typography")}</h2>
                    <TypographyControl
                        label={__("Typography")}
                        attributes={this.props.attributes}
                        setAttributes={setAttributes}
                        loadGoogleFonts={{ value: dateLoadGoogleFonts, label: __("dateLoadGoogleFonts") }}
                        fontFamily={{ value: dateFontFamily, label: __("dateFontFamily") }}
                        fontWeight={{ value: dateFontWeight, label: __("dateFontWeight") }}
                        fontSubset={{ value: dateFontSubset, label: __("dateFontSubset") }}
                        fontSizeType={{ value: dateFontsizeType, label: __("dateFontsizeType") }}
                        fontSize={{ value: dateFontsize, label: __("dateFontsize") }}
                        fontSizeMobile={{ value: dateFontsizeMobile, label: __("dateFontsizeMobile") }}
                        fontSizeTablet={{ value: dateFontsizeTablet, label: __("dateFontsizeTablet") }}
                        lineHeightType={{ value: dateLineHeightType, label: __("dateLineHeightType") }}
                        lineHeight={{ value: dateLineHeight, label: __("dateLineHeight") }}
                        lineHeightMobile={{ value: dateLineHeightMobile, label: __("dateLineHeightMobile") }}
                        lineHeightTablet={{ value: dateLineHeightTablet, label: __("dateLineHeightTablet") }}
                    />
                </Fragment>
                }
            </PanelBody>
        )
    
        const content_control = (
            <InspectorControls>
                <PanelBody title={__("General")} initialOpen={true} >                    
                    <SelectControl
                        label={__("Design")}
                        value={design}
                        onChange={(value) => setAttributes({ design: value })}
                        options={[
                            { value: "default", label: __("Default") },
                            {/* { value: "designe-2", label: __("Another") }, */}
                        ]}
                    />
                    <RangeControl
                        label={__("Number of Items")}
                        value={timelineItem}
                        onChange={(newCount) => {
    
                            let cloneDate = [...tm_content]
                            let cloneContent = [...tm_content]
    
                            if (cloneDate.length < newCount) {
    
                                const incAmount = Math.abs(newCount - cloneDate.length)
    
                                // Save date.
                                {
                                    times(incAmount, n => {
                                        cloneDate.push({
                                            title: cloneDate[0].title,
                                        })
                                    })
                                }
    
                                setAttributes({ tm_content: cloneDate })
    
                                //Save content
                                {
                                    times(incAmount, n => {
                                        cloneContent.push({
                                            time_heading: __("Timeline Heading ") + (cloneContent.length + 1),
                                            time_desc: cloneContent[0].time_desc,
                                        })
                                    })
                                }
    
                                setAttributes({ tm_content: cloneContent })
    
                            }
    
                            setAttributes({ timelineItem: newCount })
    
                        }}
                        min={1}
                        max={20}
                        allowReset
                    />
                </PanelBody>
                <PanelBody	title={ __( "Layout" ) } initialOpen={ false }>
					<SelectControl
						label={ __( "Orientation" ) }
						value={ timelinAlignment }
						onChange={ ( value ) => setAttributes( { timelinAlignment: value } ) }
						options={ [
							{ value: "left", label: __( "Left" ) },
							{ value: "right", label: __( "Right" ) },
							{ value: "center", label: __( "Center" ) },
						] }
					/>
					<SelectControl
						label={ __( "Stack on" ) }
						value={ stack }
						options={ [
							{ value: "none", label: __( "None" ) },
							{ value: "tablet", label: __( "Tablet" ) },
							{ value: "mobile", label: __( "Mobile" ) },
						] }
						help={ __( "Note: Choose on what breakpoint the Content Timeline will stack." ) }
						onChange={ ( value ) => setAttributes( { stack: value } ) }
					/>
				</PanelBody>
    
                {times(timelineItem, n => timelineControls(n))}
                {/* {renderSettings} */}
    
                {/* <PanelBody title={__("Spacing")} initialOpen={false} >
                    <RangeControl
                        label={__("Horizontal Space")}
                        value={horizontalSpace}
                        onChange={(value) => setAttributes({ horizontalSpace: value })}
                        min={1}
                        max={50}
                        allowReset
                    />
                    <RangeControl
                        label={__("Vertical Space")}
                        value={verticalSpace}
                        onChange={(value) => setAttributes({ verticalSpace: value })}
                        min={1}
                        max={100}
                        allowReset
                    />
                    <RangeControl
                        label={__("Heading Bottom Spacing")}
                        value={headSpace}
                        onChange={(value) => setAttributes({ headSpace: value })}
                        min={0}
                        max={50}
                        allowReset
                    />
                </PanelBody> */}
                <PanelBody title={__("Timeline Design And Typography")} initialOpen={false} >
                    <SelectControl
                        label={__("Typography")}
                        value={headingTag}
                        onChange={(value) => setAttributes({ headingTag: value })}
                        options={[
                            { value: "h1", label: __("H1") },
                            { value: "h2", label: __("H2") },
                            { value: "h3", label: __("H3") },
                            { value: "h4", label: __("H4") },
                            { value: "h5", label: __("H5") },
                            { value: "h6", label: __("H6") },
                            { value: "p", label: __("P") },
                            { value: "span", label: __("SPAN") },
                        ]}
                    />
                    <hr className="uagb-editor__separator" />
                    <h2>{__("Date")}</h2>
                    <TypographyControl
                        label={__("Typography")}
                        attributes={this.props.attributes}
                        setAttributes={setAttributes}
                        loadGoogleFonts={{ value: dateLoadGoogleFonts, label: __("dateLoadGoogleFonts") }}
                        fontFamily={{ value: dateFontFamily, label: __("dateFontFamily") }}
                        fontWeight={{ value: dateFontWeight, label: __("dateFontWeight") }}
                        fontSubset={{ value: dateFontSubset, label: __("dateFontSubset") }}
                        fontSizeType={{ value: dateFontSizeType, label: __("dateFontSizeType") }}
                        fontSize={{ value: dateFontSize, label: __("dateFontSize") }}
                        fontSizeMobile={{ value: dateFontSizeMobile, label: __("dateFontSizeMobile") }}
                        fontSizeTablet={{ value: dateFontSizeTablet, label: __("dateFontSizeTablet") }}
                        lineHeightType={{ value: dateLineHeightType, label: __("dateLineHeightType") }}
                        lineHeight={{ value: dateLineHeight, label: __("dateLineHeight") }}
                        lineHeightMobile={{ value: dateLineHeightMobile, label: __("dateLineHeightMobile") }}
                        lineHeightTablet={{ value: dateLineHeightTablet, label: __("dateLineHeightTablet") }}
                    />
                    <h2>{__("Heading")}</h2>
                    <TypographyControl
                        label={__("Typography")}
                        attributes={this.props.attributes}
                        setAttributes={setAttributes}
                        loadGoogleFonts={{ value: headLoadGoogleFonts, label: __("headLoadGoogleFonts") }}
                        fontFamily={{ value: headFontFamily, label: __("headFontFamily") }}
                        fontWeight={{ value: headFontWeight, label: __("headFontWeight") }}
                        fontSubset={{ value: headFontSubset, label: __("headFontSubset") }}
                        fontSizeType={{ value: headFontSizeType, label: __("headFontSizeType") }}
                        fontSize={{ value: headFontSize, label: __("headFontSize") }}
                        fontSizeMobile={{ value: headFontSizeMobile, label: __("headFontSizeMobile") }}
                        fontSizeTablet={{ value: headFontSizeTablet, label: __("headFontSizeTablet") }}
                        lineHeightType={{ value: headLineHeightType, label: __("headLineHeightType") }}
                        lineHeight={{ value: headLineHeight, label: __("headLineHeight") }}
                        lineHeightMobile={{ value: headLineHeightMobile, label: __("headLineHeightMobile") }}
                        lineHeightTablet={{ value: headLineHeightTablet, label: __("headLineHeightTablet") }}
                    />
    
                    <hr className="uagb-editor__separator" />
                    <h2>{__("Content")}</h2>
                    <TypographyControl
                        label={__("Content Tag")}
                        attributes={this.props.attributes}
                        setAttributes={setAttributes}
                        loadGoogleFonts={{ value: subHeadLoadGoogleFonts, label: __("subHeadLoadGoogleFonts") }}
                        fontFamily={{ value: subHeadFontFamily, label: __("subHeadFontFamily") }}
                        fontWeight={{ value: subHeadFontWeight, label: __("subHeadFontWeight") }}
                        fontSubset={{ value: subHeadFontSubset, label: __("subHeadFontSubset") }}
                        fontSizeType={{ value: subHeadFontSizeType, label: __("subHeadFontSizeType") }}
                        fontSize={{ value: subHeadFontSize, label: __("subHeadFontSize") }}
                        fontSizeMobile={{ value: subHeadFontSizeMobile, label: __("subHeadFontSizeMobile") }}
                        fontSizeTablet={{ value: subHeadFontSizeTablet, label: __("subHeadFontSizeTablet") }}
                        lineHeightType={{ value: subHeadLineHeightType, label: __("subHeadLineHeightType") }}
                        lineHeight={{ value: subHeadLineHeight, label: __("subHeadLineHeight") }}
                        lineHeightMobile={{ value: subHeadLineHeightMobile, label: __("subHeadLineHeightMobile") }}
                        lineHeightTablet={{ value: subHeadLineHeightTablet, label: __("subHeadLineHeightTablet") }}
                    />
                </PanelBody>
                <PanelColorSettings title={__("Color Settings")} initialOpen={false}
                    colorSettings={[
                        {
                            value: accentColor,
                            onChange: (colorValue) => setAttributes({ accentColor: colorValue }),
                            label: __("Theme Color"),
                        },
                        {
                            value: dateColor,
                            onChange: (colorValue) => setAttributes({ dateColor: colorValue }),
                            label: __("Date Color"),
                        },
                        {
                            value: headingColor,
                            onChange: (colorValue) => setAttributes({ headingColor: colorValue }),
                            label: __("Heading Color"),
                        },
                        {
                            value: iconColor,
                            onChange: (colorValue) => setAttributes({ iconColor: colorValue }),
                            label: __("Icon Color"),
                        },
                        {
                            value: subHeadingColor,
                            onChange: (colorValue) => setAttributes({ subHeadingColor: colorValue }),
                            label: __("Content Color"),
                        },
                    ]}
                >
                </PanelColorSettings>
            </InspectorControls>
        )
    
        return(
            <Fragment>

            { content_control }

            <div className={classnames(className, "twp-timeline-wrapper")} id={`twp-ctm-${this.props.clientId}`} >
                <div className={classnames("timeline-content-wrap")} >
                    {this.get_content()}
                </div>
            </div>
    
            </Fragment>
        )


    }
}


export default Timeline