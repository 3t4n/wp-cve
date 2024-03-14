/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
const {
	Component,
	Fragment,
} = wp.element;

const {
	InnerBlocks,
} = wp.blockEditor;

/**
 * Component
 */
export default class CollapsiblesBlockEdit extends Component {
	constructor() {
		super( ...arguments );

		this.getLayoutTemplate = this.getLayoutTemplate.bind( this );
	}

	/**
	 * Returns the template configuration for a given section layout.
	 *
	 * @return {Object[]} Layout configuration.
	 */
	getLayoutTemplate() {
		const {
			attributes,
		} = this.props;

		let {
			count,
		} = attributes;

		const result = [];

		for ( let k = 0; k < count; k++ ) {
			result.push( [
				'canvas/collapsible',
			] );
		}

		return result;
	}

	render() {
		const {
			setAttributes,
		} = this.props;

		let {
			className,
		} = this.props;

		const {
			count,
			canvasClassName,
		} = this.props.attributes;

		className = classnames(
			'cnvs-block-collapsibles',
			`cnvs-block-collapsibles-${ count }`,
			canvasClassName
		);

		return (
			<Fragment>
				<div className={ className }>
					<InnerBlocks
						template={ this.getLayoutTemplate() }
						templateLock="all"
						allowedBlocks={ [ 'canvas/collapsible' ] }
					/>
				</div>
			</Fragment>
		);
	}
}
