/**
 * External dependencies
 */
import classnames from 'classnames';
import ExtendAlignmentToolbar from './aligntoolbar';
import ExtendTagToolbar from './tagtoolbar';

/**
 * WordPress dependencies
 */

const {
	createBlock
} = wp.blocks;

const {
	__,
} = wp.i18n;

const {
	Component,
} = wp.element;

const {
	BlockControls,
} = wp.blockEditor;

const { RichText } = wp.blockEditor;

/**
 * Component
 */
class SectionHeadingBlockEdit extends Component {
	constructor() {
		super(...arguments);

		this.state = {
			tagName: canvasBSHLocalize.sectionHeadingTag
		};
	}

	render() {
		const {
			setAttributes
		} = this.props;

		let {
			className,
			attributes,
		} = this.props;

		const {
			halign,
			tag,
			content,
			canvasClassName,
		} = attributes;

		this.state.tagName = tag ? tag : '';

		// If tag default.
		if ( 'default' === tag || 'none' === tag || ! tag ) {
			this.state.tagName = canvasBSHLocalize.sectionHeadingTag;
		}

		let classAlign = halign ? `halign${halign}` : '';

		// If align default.
		if ( 'default' === halign || 'none' === halign || ! halign ) {
			classAlign = canvasBSHLocalize.sectionHeadingAlign;
		}

		className = classnames(
			'cnvs-block-section-heading',
			classAlign,
			canvasClassName,
			className
		);
		return (
			<div>
				<BlockControls>
					<ExtendAlignmentToolbar
						value={halign}
						onChange={(val) => {
							let align= (val === undefined) ? 'default' : val;

							setAttributes({ halign: align })
						}}
					/>
					<ExtendTagToolbar
						value={tag}
						onChange={(val) => {
							let type= (val === undefined) ? 'default' : val;

							setAttributes({ tag: type })
						}}
					/>
				</BlockControls>

				<this.state.tagName className={className}>
					<div className="cnvs-section-title">
						<RichText
							tagName="span"
							className="cnvs-section-plain"
							onChange={(newContent) => {
								setAttributes({ content: newContent });
							}}
							value={content}
							placeholder={__('Write heading…')}
						/>
					</div>
				</this.state.tagName>
			</div>
		);
	}
}

jQuery(() => {
	function sectionHeadingTransforms(settings) {
		if (settings.name !== 'canvas/section-heading') {
			return settings;
		}

		settings.transforms = {
			from: [
				{
					type: 'block',
					blocks: ['core/heading', 'core/paragraph'],
					transform: function (attributes) {
						return createBlock('canvas/section-heading', {
							content: attributes.content,
						});
					},
				},
			],
		};

		return settings;
	}

	wp.hooks.addFilter('canvas.customBlock.registerData', 'canvas/section-heading/transforms', sectionHeadingTransforms);
});

export default SectionHeadingBlockEdit;
