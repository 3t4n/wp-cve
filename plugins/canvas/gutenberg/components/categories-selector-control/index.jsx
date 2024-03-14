/**
 * External dependencies
 */
import ReactSelect from 'react-select';
import { debounce } from 'throttle-debounce';

/**
 * WordPress dependencies
 */
const {
	Component,
} = wp.element;

const {
	withSelect,
} = wp.data;

const {
	BaseControl,
} = wp.components;

const cachedCategories = {};

function maybeCache( newCategories ) {
	if ( ! newCategories || ! newCategories.length ) {
		return;
	}

	if ( ! cachedCategories ) {
		cachedCategories = {};
	}

	newCategories.forEach( ( postData ) => {
		if ( ! cachedCategories[ postData.id ] ) {
			cachedCategories[ postData.id ] = postData;
		}
	} );
}

/**
 * Component
 */
class ComponentCategoriesSelectorControl extends Component {
	constructor() {
		super( ...arguments );

		this.state = {
			searchTerm: '',
		};

		this.updateSearchTerm = debounce( 300, this.updateSearchTerm.bind( this ) );
	}

	/**
	 * Update search term with debounce.
	 *
	 * @param {String} search - search term.
	 */
	updateSearchTerm( search ) {
		this.setState( {
			searchTerm: search,
		} );
	}

	render() {
		const {
			value,
			label,
			help,
			onChange,
			allСategories,
			findСategories,
		} = this.props;

		const {
			searchTerm,
		} = this.state;

		const foundСategories = findСategories( searchTerm ) || [];

		return (
			<BaseControl
				label={ label }
				help={ help }
			>
				<ReactSelect
					options={
						foundСategories.map( ( catData ) => {
							return {
								value: catData.slug,
								label: catData.name,
							};
						} )
					}
					value={ ( () => {
						let result = value ? value.split(',') : [];
						if ( result && result.length ) {
							result = result.map( ( name ) => {
								let thisData = {
									value: name,
									label: name,
								};

								// get label from categories list
								for (var id in allСategories ) {
									if ( name === allСategories[id].slug ) {
										thisData.label = allСategories[id].name;
									}
								}

								return thisData;
							} );
							return result;
						}
						return [];
					} )() }
					isMulti
					name="filter-by-categories"
					className="basic-multi-select"
					classNamePrefix="select"
					components={ {
						IndicatorSeparator: () => null,
						ClearIndicator: () => null,
					} }
					onInputChange={ ( val ) => {
						this.updateSearchTerm( val );
					} }
					onChange={ ( val ) => {
						let result = '';
						let slug = '';

						if ( val ) {
							val.forEach( ( catData ) => {
								result += slug + catData.value;
								slug = ',';
							} );
						}

						onChange( result );
					} }
				/>
			</BaseControl>
		);
	}
}

export default withSelect( ( select, props, a, b, c ) => {
	const {
		getEntityRecords,
	} = select( 'core' );

	const {
		value,
	} = props;


	let slugs = value ? value.split(',') : [];

	// find non-cached categories and try to retrieve.
	slugs = slugs.filter( ( slug ) => {
		return ! cachedCategories || ! cachedCategories[ slug ];
	} );

	if ( slugs && slugs.length ) {
		const newСategories = getEntityRecords( 'taxonomy', 'category', {
			slug: slugs,
			per_page: 100,
		} );

		maybeCache( newСategories );
	}

	return {
		findСategories( search = '' ) {
			const searchСategories = getEntityRecords( 'taxonomy', 'category', {
				search,
				per_page: 20,
			} );

			maybeCache( searchСategories );

			return searchСategories;
		},
		allСategories: cachedCategories || {},
	};
})( ComponentCategoriesSelectorControl );
