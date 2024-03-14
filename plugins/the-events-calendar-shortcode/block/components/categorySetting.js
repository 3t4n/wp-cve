import { Component, Fragment } from '@wordpress/element';
import { FormTokenField } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import apiFetch from '@wordpress/api-fetch';

/**
 * Setting component for event categories
 */
class CategorySetting extends Component {
	constructor( props ) {
		super( props );

		this.state = {
			selectOptions: [],
			selectedCats: [],
			selectValues: [],
			isLoading: true,
		};
	}

	/**
	 * Load in event categories from tribe endpoint - CDM
	 */
	componentDidMount() {
		apiFetch( { path: '/tribe/events/v1/categories/?per_page=200' } ).then(
			( response ) => {
				const selectOptions = response.categories.map( ( category ) => {
					return { value: category.slug, label: category.name };
				} );

				const selectValues = selectOptions.map( ( option ) => {
					return option.value;
				} );

				const { cat } = this.props.attributes;
				const selectedCats =
					typeof cat === 'undefined' ? [] : cat.split( ', ' );

				this.setState( {
					selectOptions,
					selectedCats,
					selectValues,
					isLoading: false,
				} );
			}
		);
	}

	/**
	 * Handle selection change
	 *
	 * @param {Array} selectedCats the selected categories
	 */
	handleChange = ( selectedCats ) => {
		const validCats = selectedCats.filter( ( category ) => this.state.selectValues.includes( category ) );

		const stringSelection = validCats.join( ', ' );

		this.setState( { selectedCats: validCats } );
		this.props.setAttributes( { cat: stringSelection } );
	};

	displayTransform = ( suggestion ) => {
		return (
			this.state.selectOptions
				.filter( ( option ) => option.value === suggestion )
				.pop()?.label ?? suggestion
		);
	};

	/**
	 * @return {ReactElement} Category Setting
	 */
	render() {
		return (
			<Fragment>
				<FormTokenField
					suggestions={ this.state.selectValues }
					tokenizeOnSpace={ false }
					displayTransform={ this.displayTransform }
					onChange={ this.handleChange }
					disabled={ this.state.isLoading }
					value={ this.state.selectedCats.filter( Boolean ) }
					placeholder={ __(
						'Search Categories',
						'the-events-calendar-shortcode'
					) }
					label={ false }
					__experimentalShowHowTo={ false }
				/>
			</Fragment>
		);
	}
}

export default CategorySetting;
