/**
 * BLOCK: Shortcode
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';
import { icon } from '../../icons'
import times from "lodash/times"
import classnames from "classnames"
import contentTimelineStyle from "./inline-styles"
import TimelineIcons from "../../../dist/blocks/controls/Icons.json"
import FontIconPicker from "@fonticonpicker/react-fonticonpicker"
import renderSVG from "../../../dist/blocks/controls/renderIcon"
import attributes from "./attributes"
import edit from "./edit"
import TmClasses from "./classes"
const { apiFetch } = wp;
const { decodeEntities } = wp.htmlEntities
import TypographyControl from "../../components/typography"
import { registerBlockType, __, PanelBody, RichText, Button, ColorPalette, SelectControl, TextControl, TabPanel, ToggleControl, PanelColorSettings, InspectorControls, RangeControl, MediaUpload, Component, Fragment } from '../../wp-imports'

const {
	withSelect
} = wp.data

let svg_icons = Object.keys( TimelineIcons )



export const save = ( props ) => {

	const {
		block_id,
		headingTag,
		timelinAlignment,
		design,
		displayPostDate,
		icon,
		tm_content,
		t_date,
		date_icon,
		stack,
		timelineItem
	} = props.attributes

	// Setup the attributes.

	/* Style for elements */
	var front_style = contentTimelineStyle( props )
	const hasItems = Array.isArray( tm_content ) && tm_content.length


	/*
	* Event to set Image as while adding.
	*/
	return (

		<div className={classnames( props.className, "twp-timeline-wrapper")} id={`twp-ctm-${block_id}`} >
			<style>
				{front_style}
			</style>
			<div className={classnames( "timeline-content-wrap", ...TmClasses( props.attributes ) )} >
				<div className={classnames("main-timeline", design)}>
					{
						tm_content.map((post,index) => {

							if ( timelineItem <= index ) {
								return
							}

							let image_icon_html = ""
							const icon = props.attributes.icon
                            if ( post.image_icon == "icon" ) {
                                if ( post.icon ) {
                                    image_icon_html = <span>{ renderSVG(post.icon) }</span>
                                }
                            } else {
                                if ( post.image ) {
                                    image_icon_html = <span><img src={post.image.url} /></span>
                                }
                            } 
                          

							return (
								<article className="timeline" key={index} >
                                    <div class="timeline-content">

                                        <div class="circle">
                                            { image_icon_html }
                                        </div>

                                        <div class="content">
                                            <RichText.Content
                                                tagName={ __( "span" ) }
                                                value={ post.time_date }
                                                className='year'
                                            />
                                            <RichText.Content
                                                tagName={ __( "h4" ) }
                                                value={ post.time_heading }
                                                className='title'
                                              />
                                            
                                            <RichText.Content
                                                tagName= "p"
                                                value={ post.time_desc }
                                                placeholder={ __( "Write a Description" ) }
                                                className='description'
                                            />
                                            <div class="icon"><span></span></div>
                                        </div>
                                    </div>
                                </article>
							)
						})
					}
				</div>
			</div>
		</div>
	)
}


/**
 * Register: Shortcode Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @param  Shortcode        	Block name.
 * @param  {Object}   settings 	Block settings.
 * @return {?WPBlock}          	The block, if it has been successfully
 *                             	registered; otherwise `undefined`.
 */

registerBlockType( 'cgb/timeline', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'Timeline Instant Bulider' ), // Block title.
	// icon: QuoteIcon, // Block icon from Dasht_date → https://developer.wordpress.org/resource/dasht_date/.
	category: 'timeline', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	icon: icon ,
	keywords: [
		__( 'Timeline WP Builder' ),
		__( 'Timeline Example' ),
	],
	attributes,
	edit,
	save,
} );