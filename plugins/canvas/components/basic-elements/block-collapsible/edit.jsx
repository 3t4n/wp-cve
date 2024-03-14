/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
const { Component, Fragment } = wp.element;

const { __ } = wp.i18n;

const {
	RichText,
	InnerBlocks,
} = wp.blockEditor;

const {
	Button,
} = wp.components;

/**
 * Component
 */
export default class CollapsibleBlockEdit extends Component {
	render() {
		const {
			attributes,
			setAttributes,
		} = this.props;

		const {
			title,
			opened,
			canvasClassName,
		} = attributes;

		let {
			className,
		} = this.props;

		className = classnames(
			'cnvs-block-collapsible',
			{
				'cnvs-block-collapsible-opened': opened,
			},
			canvasClassName,
			className
		);

		return (
			<Fragment>
				<div className={ className }>
					<div className="cnvs-block-collapsible-title">
						<h6>
							<RichText
								placeholder={ __( 'Add collapsible title...' ) }
								value={ title }
								onChange={ ( val ) => setAttributes( { title: val } ) }
								keepPlaceholderOnFocus
							/>
						</h6>
						<Button
							className="cnvs-block-collapsible-toggle"
							onClick={ () => setAttributes( { opened: ! opened } ) }
						>
							<span className="cnvs-icon-chevron-right" />
						</Button>
					</div>
					<div className="cnvs-block-collapsible-content">
						<InnerBlocks
							templateLock={ false }
						/>
					</div>
				</div>
			</Fragment>
		);
	}
}
