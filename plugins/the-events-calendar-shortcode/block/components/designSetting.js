import { Component, Fragment } from '@wordpress/element';
import { SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
* Setting component for design
*/
class DesignSetting extends Component {
	/**
	 * @return {ReactElement} Design Setting
	 */
	render() {
		return (
			<Fragment>
				<SelectControl
					options={ [
						{ label: __( 'Standard', 'the-events-calendar-shortcode' ), value: 'standard' },
					] }
					value={ 'standard' }
				/>

				<div className={ 'ecs-setting-help' }>
					<a
						href={ 'https://eventcalendarnewsletter.com/the-events-calendar-shortcode/?utm_source=plugin&utm_medium=link&utm_campaign=block-design-help&utm_content=description#designs' }
						target={ '_blank' }
					>{ __( 'Upgrade to Pro', 'the-events-calendar-shortcode' ) }</a> { __( ' for more designs, or ', 'the-events-calendar-shortcode' ) }
					<a
						href={ 'https://demo.eventcalendarnewsletter.com/the-events-calendar-shortcode/?utm_source=plugin&utm_medium=link&utm_campaign=block-design-help&utm_content=description#designs' }
						target={ '_blank' }
					>{ __( 'view the demo', 'the-events-calendar-shortcode' ) }</a>
			</div>
			</Fragment>
		);
	}
}

export default DesignSetting;
