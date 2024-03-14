import { Component, Fragment } from '@wordpress/element';
import { SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

class OrderSetting extends Component {
	/**
	 * Handle selection change
	 *
	 * @param {Array} selectedOrder the selected categories
	 */
	handleChange = ( selectedOrder ) => {
		this.props.setAttributes( { order: selectedOrder } );
	}

	/**
	 * @return {ReactElement} Order Setting
	 */
	render() {
		const { order } = this.props.attributes;

		const orderOptions = [
			{
				label: __( 'Ascending', 'the-events-calendar-shortcode' ),
				value: 'ASC',
			},
			{
				label: __( 'Descending', 'the-events-calendar-shortcode' ),
				value: 'DESC',
			},
        ];
        const selectedOrder = orderOptions.filter( ( option ) => option.value === order ).pop()?.value;

        return (
			<Fragment>
				<SelectControl
					value={ selectedOrder }
					onChange={ this.handleChange }
					options={ orderOptions }
				/>
			</Fragment>
		);
	}
}

export default OrderSetting;

