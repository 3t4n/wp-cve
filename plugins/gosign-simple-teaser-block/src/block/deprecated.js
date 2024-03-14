const { Fragment } = wp.element;

const {
	RichText,
	InnerBlocks,
} = wp.editor;

const blockAttributes = {
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
	}
}
export default [

	//save code before event tracking.
	{
		attributes: blockAttributes,
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
							<a href={link_url} target={link_target} className="readmore" style={{ textAlign: linkAlignment }} rel="noopener noreferrer">{link_text}</a>
						)}
					</div>
				</Fragment>
			);
		},
	},
	{
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
							<a href={link_url} target={link_target} className="readmore" style={{ textAlign: linkAlignment }}>{link_text}</a>
						)}
					</div>
				</Fragment>
			);
		},
	}
];