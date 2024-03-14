/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
const {
	__,
} = wp.i18n;

const {
	Component,
	Fragment,
} = wp.element;

const {
	InnerBlocks,
} = wp.blockEditor;

const {
	withSelect,
} = wp.data;

/**
 * Component
 */
class AlertBlockEdit extends Component {
	constructor() {
		super(...arguments);

		this.state = {
			// fix for WP 5.2
			// styles control generates error
			showInnerBlocks: !!this.props.clientId,
		};
	}

	render() {
		const {
			setAttributes,
			hasChildBlocks,
		} = this.props;

		let {
			className,
			attributes,
		} = this.props;

		const {
			dismissible,
			canvasClassName,
		} = attributes;

		className = classnames(
			'cnvs-block-alert',
			{
				'cnvs-block-alert-dismissible': dismissible,
			},
			canvasClassName,
			className
		);

		return (
			<Fragment>
				<div className={className}>
					<div className="cnvs-block-alert-inner">
						{this.state.showInnerBlocks ? (
							<InnerBlocks
								templateLock={false}
								renderAppender={(
									hasChildBlocks ?
										undefined :
										() => <InnerBlocks.ButtonBlockAppender />
								)}
							/>
						) : __('Alert content')}
					</div>
					{dismissible ? (
						<button className="cnvs-close" type="button" data-dismiss="alert" aria-label={__('Close')}>
							<i className="cnvs-icon-x" />
						</button>
					) : ''}
				</div>
			</Fragment>
		);
	}
}

const AlertBlockEditWithSelect = withSelect((select, ownProps) => {
	const { clientId } = ownProps;
	const blockEditor = select('core/block-editor');

	return {
		hasChildBlocks: blockEditor ? blockEditor.getBlockOrder(clientId).length > 0 : false,
	};
})(AlertBlockEdit);

export default AlertBlockEditWithSelect;
