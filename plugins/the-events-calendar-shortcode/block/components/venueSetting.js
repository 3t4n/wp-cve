import { Component, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

/**
* Setting component for past
*/
class VenueSetting extends Component {
	handleChange = ( event ) => {
		this.props.setAttributes( { venue: ( event.target.checked ) ? 'true' : 'false' } );
	}

	/**
	 * @return {ReactElement} Venue Setting
	 */
	render() {
        let { venue } = this.props.attributes;
        venue = typeof venue === 'undefined' ? '' : venue;
        venue = ( venue === '' || venue === 'false' ) ? false : true;

		return (
			<Fragment>
				<input
					id={ 'ecs-setting-venue' }
					type={ 'checkbox' }
					checked={ venue }
					onChange={ this.handleChange }
				/>
				<label
					className={ 'components-base-control__label' }
					htmlFor={ 'ecs-setting-venue' }
				>{ __( 'Show venue information', 'the-events-calendar-shortcode' ) }</label>
			</Fragment>
		);
	}
}

export default VenueSetting;
