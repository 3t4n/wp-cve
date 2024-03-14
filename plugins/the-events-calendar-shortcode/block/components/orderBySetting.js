import { Component, Fragment } from '@wordpress/element';
import { SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

class OrderBySetting extends Component {
	/**
	 * Handle selection change
	 *
	 * @param {Array} selectedOrderBy the selected option
	 */
	handleChange = ( selectedOrderBy ) => {
		this.props.setAttributes( { orderby: selectedOrderBy } );
	}

	/**
	 * @return {ReactElement} OrderBy Setting
	 */
	render() {
		const { orderby } = this.props.attributes;

		const orderByOptions = [
			{
				label: __( 'Start Date', 'the-events-calendar-shortcode' ),
				value: 'startdate',
			},
			{
				label: __( 'End Date', 'the-events-calendar-shortcode' ),
				value: 'enddate',
			},
            {
                label: __( 'Title', 'the-events-calendar-shortcode' ),
                value: 'title',
            },
        ];
        const selectedOrderBy = orderByOptions.filter( ( option ) => option.value === orderby ).pop()?.value;

        return (
			<Fragment>
				<SelectControl
					value={ selectedOrderBy }
					onChange={ this.handleChange }
					options={ orderByOptions }
				/>
			</Fragment>
		);
	}
}

export default OrderBySetting;

