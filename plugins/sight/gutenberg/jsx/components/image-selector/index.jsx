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
	RawHTML,
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
		super(...arguments);
	}

	render() {
		let {
			className,
			value,
		} = this.props;

		const {
			items,
			onChange,
		} = this.props;

		className = classnames(
			'sight-component-image-selector',
			className
		);

		const layouts = Object.keys(items).map((key) => {
			return {
				content: <RawHTML>{items[key].icon}</RawHTML>,
				value: key,
				label: items[key].name,
			};
		});

		return (
			<div className={className}>
				{ layouts && layouts.length ? (
					layouts.map((itemData, i) => {
						const itemClassName = classnames(
							'sight-component-image-selector-item',
							{
								'sight-component-image-selector-item-active': value === itemData.value,
							}
						);

						return (
							<div
								key={`sight-component-image-selector-item-${itemData.value}`}
								className={itemClassName}
							>
								<Button
									onClick={() => {
										onChange(itemData.value);
									}}
								>
									{itemData.content}
								</Button>
								<span>{itemData.label}</span>
							</div>
						);
					})
				) : null }
			</div>
		);
	}
}
