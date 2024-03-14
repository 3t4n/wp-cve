/**
 * BLOCK: Gosign - Simple Teaser Block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';
import icon from './icon.js';
import deprecated from './deprecated';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

const { Component, Fragment } = wp.element;

const {
	InspectorControls,
	BlockAlignmentToolbar,
	PanelColorSettings,
	BlockControls,
	RichText,
	InnerBlocks,
	AlignmentToolbar,
} = wp.editor;

const {
	PanelBody,
	Placeholder,
	QueryControls,
	RangeControl,
	Spinner,
	ToggleControl,
	BaseControl,
	SelectControl,
	Toolbar,
	TextControl
} = wp.components;

const matomoTrackingEnable = MATOMOJSOBJECT_GST.SimpleTeaserMatomo === "1" ? true : false;

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType('gstb/block-gosign-simple-teaser-block', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __('Gosign - Simple Teaser Block'), // Block title.
	icon: icon.simple, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__('Gosign'),
		__('Simple Teaser Block')
	],
	attributes: {
		headline: {
			type: "string"
		},
		headlineSize: {
			type: "string",
			default: ""
		},
		alignment: {
			type: "string",
			default: "center"
		},
		linkAlignment: {
			type: "string",
			default: "left"
		},
		textAlignment: {
			type: "string",
			default: "left"
		},
		align: {
			type: "string",
			default: "full"
		},
		textColor: {
			type: "string",
			default: "#000000"
		},
		bodytext: {
			type: "string"
		},
		link_text: {
			type: "string",
			default: ""
		},
		link_target: {
			type: "string",
			default: "_self"
		},
		link_url: {
			type: "string",
			default: ""
		},
		btn_mouseover: {
			type: "boolean",
			default: false
		},
		teaser_layout: {
			type: "string",
			default: "0"
		},
		image: {
			type: "string"
		},
		disable_headline: {
			type: "boolean",
			default: false
		},
		disable_text: {
			type: "boolean",
			default: false
		},
		disable_image: {
			type: "boolean",
			default: false
		},
		matomoTracking: {
			type: "boolean",
	      	default: false,
		},
		matomoEventName: {
			type: 'string',
			default: ''
		},
		matomoEventValue: {
			type: 'string',
			default: 'click'
		}
	},

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	edit: function (props) {

		const { attributes, setAttributes } = props;
		const {
			headline,
			bodytext,
			image,
			link_text,
			link_url,
			link_target,
			disable_headline,
			disable_text,
			disable_image,
			teaser_layout,
			btn_mouseover,
			headlineSize,
			align,
			alignment,
			linkAlignment,
			textAlignment,
			textColor,
			matomoTracking,
			matomoEventName,
			matomoEventValue
		} = attributes;

		const inspectorControls = (
			<InspectorControls>
				<PanelBody title={__("Teaser Settings")}>
					<PanelColorSettings
						title={__("Color Settings for headline")}
						colorSettings={[
							{
								value: textColor,
								onChange: colorValue =>
									props.setAttributes({ textColor: colorValue }),
								label: __("Text Color")
							}
						]}
					/>

					<ToggleControl
						label="Disable Headline"
						help={disable_headline ? 'Disable Headline' : 'Not Disable Headline'}
						checked={disable_headline}
						onChange={function (content) {
							setAttributes({ disable_headline: content });
						}}
					/>
					<ToggleControl
						label="Disable Text"
						help={disable_text ? 'Disable Text' : 'Not Disable Text'}
						checked={disable_text}
						onChange={function (content) {
							setAttributes({ disable_text: content });
						}}
					/>
					<h3>Text Alignment</h3>
					<AlignmentToolbar
						value={textAlignment}
						onChange={function (alignment) {
							props.setAttributes({ textAlignment: alignment });
						}}
					/>
					<ToggleControl
						label="Disable Image"
						help={disable_image ? 'Disable Image' : 'Not Disable Image'}
						checked={disable_image}
						onChange={function (content) {
							setAttributes({ disable_image: content });
						}}
					/>
					<ToggleControl
						label="Show Button on MouseOver"
						help={btn_mouseover ? 'Show Button on MouseOver' : 'Not Show Button on MouseOver'}
						checked={btn_mouseover}
						onChange={function (content) {
							setAttributes({ btn_mouseover: content });
						}}
					/>
					<h3>Link Alignment</h3>
					<AlignmentToolbar
						value={linkAlignment}
						onChange={function (alignment) {
							props.setAttributes({ linkAlignment: alignment });
						}}
					/>

					<SelectControl
						label="Layout Options"
						value={teaser_layout}
						options={[
							{ label: "Headline over Image", value: "0" },
							{ label: "Headline under Image", value: "1" }
						]}
						onChange={content => {
							setAttributes({ teaser_layout: content });
						}}
					/>
					<TextControl
						label="Link Text"
						value={link_text}
						onChange={(content) => setAttributes({ link_text: content })}
					/>
					<TextControl
						label="Link URL"
						value={link_url}
						onChange={(content) => setAttributes({ link_url: content })}
					/>
					<SelectControl
						label="Link Target"
						value={link_target}
						options={[
							{ label: "Current Page", value: "_self" },
							{ label: "New Page", value: "_blank" }
						]}
						onChange={content => {
							setAttributes({ link_target: content });
						}}
					/>

					{/* Matomo Tracking */}
					{matomoTrackingEnable &&
						<ToggleControl
							label={__("Enable Matomo Tracking")}
							checked={matomoTracking}
							onChange={value => setAttributes({ matomoTracking: value })}
						/>
					}
					{matomoTrackingEnable && matomoTracking &&
						<PanelBody
							title="Matomo Tracking"
							icon=""
							initialOpen={false}>
								<TextControl
									label={ __( 'Event Name' ) }
									value={ matomoEventName }
									onChange={value => setAttributes({ matomoEventName: value })}
									placeholder="Event Name"
									rel="noopener noreferrer"
								/>
								<SelectControl
									label="Event Type"
									value={matomoEventValue}
									options={[
										{ label: "OnClick", value: "click" },
										{ label: "OnMouseOver", value: "hover" },
									]}
									onChange={matomoEventValue => {
										setAttributes({ matomoEventValue });
									}}
								/>
						</PanelBody>
					}

				</PanelBody>
			</InspectorControls>
		);

		// Creates a <p class='wp-block-cgb-block-gosign-simple-teaser-block'></p>.
		return (
			<Fragment>
				{inspectorControls}
				<BlockControls>
					{/* <BlockAlignmentToolbar
						value={align}
						onChange={nextAlign => {
							setAttributes({ align: nextAlign });
						}}
						controls={["center", "full"]}
					/> */}
					<AlignmentToolbar
						value={alignment}
						onChange={function (alignment) {
							props.setAttributes({ alignment: alignment });
						}}
					/>
					<Toolbar>
						<button
							className={
								"heading1 components-button components-icon-button" +
								(headlineSize == "h1" ? " is-active" : "")
							}
							onClick={function () {
								props.setAttributes({ headlineSize: "h1" });
							}}
						>
							H1
						</button>
						<button
							className={
								"heading2 components-button components-icon-button" +
								(headlineSize == "h2" ? " is-active" : "")
							}
							onClick={function () {
								props.setAttributes({ headlineSize: "h2" });
							}}
						>
							H2
						</button>
						<button
							className={
								"heading3 components-button components-icon-button" +
								(headlineSize == "h3" ? " is-active" : "")
							}
							onClick={function () {
								props.setAttributes({ headlineSize: "h3" });
							}}
						>
							H3
						</button>
					</Toolbar>
				</BlockControls>

				<div className={props.className}>
					{teaser_layout == "0" && (
						<Fragment>

							{disable_headline != true && (
								<header>
									<RichText tagName={headlineSize ? headlineSize : "h1"} value={headline} placeholder="Heading"
										style={{ textAlign: alignment, color: textColor }}
										onChange={function (content) {
											setAttributes({ headline: content });
										}}
									/>
								</header>
							)}
							{disable_image != true && (
								<InnerBlocks
									template={[["core/image", {}]]}
									templateLock="all"
								/>
							)}
						</Fragment>
					)}
					{teaser_layout == "1" && (
						<Fragment>
							{disable_image != true && (
								<InnerBlocks
									template={[["core/image", {}]]}
									templateLock="all"
								/>
							)}
							{disable_headline != true && (
								<header>
									<RichText tagName={headlineSize ? headlineSize : "h1"} value={headline} placeholder="Heading"
										style={{ textAlign: alignment, color: textColor }}
										onChange={function (content) {
											setAttributes({ headline: content });
										}}
									/>
								</header>
							)}
						</Fragment>
					)}
					{disable_text != true && (
						<RichText value={bodytext} tagName="p" placeholder="Text"
							style={{ textAlign: textAlignment }}
							onChange={function (content) {
								setAttributes({ bodytext: content });
							}}
						/>
					)}
					{btn_mouseover != true && (
						<a href={link_url} className="readmore" target={link_target} style={{ textAlign: linkAlignment }}>{link_text}</a>
					)}


				</div>
			</Fragment>
		);
	},

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	save: function (props) {
		const { attributes, setAttributes } = props;
		const {
			headline,
			bodytext,
			image,
			link_text,
			link_url,
			link_target,
			disable_headline,
			disable_text,
			disable_image,
			teaser_layout,
			btn_mouseover,
			headlineSize,
			textAlignment,
			linkAlignment,
			align,
			alignment,
			textColor,
			matomoTracking,
			matomoEventName,
			matomoEventValue
		} = attributes;
		return (
			<Fragment>
				<div className="gosign-simple-teaser-block">
					{teaser_layout == "0" && (
						<Fragment>
							{disable_headline != true && (
								<RichText.Content style={{ textAlign: alignment, color: textColor }} tagName={headlineSize ? headlineSize : "h1"} value={headline} />
							)}
							{disable_image != true && (
								<InnerBlocks.Content />
							)}
						</Fragment>
					)}
					{teaser_layout == "1" && (
						<Fragment>
							{disable_image != true && (
								<InnerBlocks.Content />
							)}
							{disable_headline != true && (
								<RichText.Content style={{ textAlign: alignment, color: textColor }} tagName={headlineSize ? headlineSize : "h1"} value={headline} />
							)}
						</Fragment>
					)}
					{disable_text != true && (
						<RichText.Content style={{ textAlign: textAlignment }} value={bodytext} tagName="p" />
					)}
					{btn_mouseover != true && (
						<a href={link_url}
							target={link_target}
							className="readmore"
							style={{ textAlign: linkAlignment }}
							{...(matomoTrackingEnable && matomoTracking ? {['data-MatomoEventName']: matomoEventName, ['data-MatomoEventValue']: matomoEventValue} : {})}
							{...(!matomoTrackingEnable && matomoTracking ? {['data-MatomoEventName']: matomoEventName, ['data-MatomoEventValue']: matomoEventValue} : {})}
							rel="noopener noreferrer"
						>{link_text}</a>
					)}
				</div>
			</Fragment>
		);
	},
	deprecated,
});
