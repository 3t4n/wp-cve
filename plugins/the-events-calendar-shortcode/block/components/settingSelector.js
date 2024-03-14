import { Component } from '@wordpress/element';
import { SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

class SettingSelector extends Component {
	/**
	* @param {string} newSetting the selected setting to add
	*/
	handleChange = ( newSetting ) => {
		const { handleSelect } = this.props;

		if ( newSetting === 'other' ) {
			// handle keyValue setting
			handleSelect( newSetting, true );
		} else {
			// handle new normal setting
			handleSelect( newSetting );
		}
	}

	/**
	* @return {ReactElement} Setting Selector
	*/
	render() {
		const { settingsConfig, activeSettings } = this.props;

		// build options from config object
		const selectOptions = Object.keys( settingsConfig ).map( ( key ) => {
			return {
				value: key,
				label: settingsConfig[ key ].label,
			};
		} );

		// add default option
		selectOptions.unshift( {
			value: 'new-setting',
			label: __( 'Choose another option', 'the-events-calendar-shortcode' ),
			isDisabled: true,
		} );

		// generate the available options
		const availableOptions = selectOptions.filter( ( option ) => {
			return activeSettings.indexOf( option.value ) < 0;
		} );

		return (
			<SelectControl
				className={ 'ecs-select' }
				options={ availableOptions }
				value={ {
					value: 'new-setting',
					label: __( 'Choose another option', 'the-events-calendar-shortcode' ),
					isDisabled: true,
				} }
				onChange={ this.handleChange }
			/>
		);
	}
}

export default SettingSelector;

