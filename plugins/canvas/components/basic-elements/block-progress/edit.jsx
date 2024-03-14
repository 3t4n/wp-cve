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
	ResizableBox,
} = wp.components;

/**
 * Component
 */
export default class ProgressBlockEdit extends Component {
	render() {
		const {
			setAttributes,
			isSelected,
		} = this.props;

		let {
			className,
		} = this.props;

		const {
			height,
			width,
			striped,
			animated,
			displayPercent,
			canvasClassName,
		} = this.props.attributes;

		className = classnames(
			'cnvs-block-progress',
			{
				'cnvs-block-progress-striped': striped,
				'cnvs-block-progress-animated': striped && animated,
				'is-selected': isSelected,
			},
			canvasClassName,
			className
		);

		return (
			<Fragment>
				<ResizableBox
					className={ className }
					size={ {
						height,
					} }
					minHeight="1"
					maxHeight="20"
					enable={ { bottom: true } }
					onResizeStop={ ( event, direction, elt, delta ) => {
						setAttributes( {
							height: parseInt( height + delta.height, 10 ),
						} );
					} }
				>
					<ResizableBox
						className="cnvs-block-progress-bar"
						size={ {
							width: `${ width }%`,
						} }
						minHeight="1"
						maxHeight="100"
						enable={ { right: true } }
						onResizeStop={ ( event, direction, elt, delta ) => {
							setAttributes( {
								width: Math.min( 100, Math.max( 0, width + parseInt( 100 * delta.width / jQuery( elt ).parent().width(), 10 ) ) ),
							} );
						} }
					>
						{ displayPercent ? `${ width }%` : '' }
					</ResizableBox>
				</ResizableBox>
			</Fragment>
		);
	}
}
