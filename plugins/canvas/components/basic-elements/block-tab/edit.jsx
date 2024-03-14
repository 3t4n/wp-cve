/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
const { Component, Fragment } = wp.element;

const {
	InnerBlocks,
} = wp.blockEditor;

/**
 * Component
 */
export default class TabBlockEdit extends Component {
	render() {
		let {
			className,
		} = this.props;

		const {
			canvasClassName,
		} = this.props.attributes;

		className = classnames(
			'cnvs-block-tab',
			canvasClassName,
			className
		);

		return (
			<Fragment>
				<div className={ className }>
					<InnerBlocks
						templateLock={ false }
					/>
				</div>
			</Fragment>
		);
	}
}
