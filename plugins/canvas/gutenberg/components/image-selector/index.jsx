/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * Internal dependencies
 */
import './style.scss';

/**
 * WordPress dependencies
 */
const {
	Component,
} = wp.element;

const {
	Button,
	Popover,
} = wp.components;

/**
 * Component
 */
export default class ComponentImageSelector extends Component {
	constructor() {
		super( ...arguments );

		this.state = {
			showPopover: false,
		};
	}

	render() {
		let {
			className,
			value,
		} = this.props;

		const {
			items = [],
			onChange,
		} = this.props;

		className = classnames(
			'cnvs-component-image-selector',
			className
		);

		return (
			<div className={ className }>
				{ items && items.length ? (
					items.map( ( itemData, i ) => {
						const isDisabled = itemData.isDisabled;
						const disabledNotice = itemData.disabledNotice;

						const itemClassName = classnames(
							'cnvs-component-image-selector-item',
							{
								'cnvs-component-image-selector-item-active': value === itemData.value,
								'cnvs-component-image-selector-item-disabled': isDisabled,
							}
						);

						return (
							<div
								key={ `cnvs-component-image-selector-item-${ itemData.value }` }
								className={ itemClassName }
							>
								<Button
									onClick={ () => {
										if ( ! isDisabled ) {
											onChange( itemData.value );
										} else {
											this.setState( { showPopover: itemData.value } );
										}
									} }
								>
									{ itemData.content }
									{ this.state.showPopover === itemData.value && disabledNotice ? (
										<Popover
											className="cnvs-component-image-selector-item-popover"
											focusOnMount={ false }
											onClickOutside={ () => {
												this.setState( { showPopover: false } );
											} }
										>
											{ disabledNotice }
										</Popover>
									) : '' }
								</Button>
								<span>{ itemData.label }</span>
							</div>
						);
					} )
				) : '' }
			</div>
		);
	}
}
