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

const cachedTags = {};

function maybeCache( newTags ) {
	if ( ! newTags || ! newTags.length ) {
		return;
	}

	if ( ! cachedTags ) {
		cachedTags = {};
	}

	newTags.forEach( ( postData ) => {
		if ( ! cachedTags[ postData.id ] ) {
			cachedTags[ postData.id ] = postData;
		}
	} );
}

/**
 * Component
 */
class ComponentTagsSelectorControl extends Component {
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
			allTags,
			findTags,
		} = this.props;

		const {
			searchTerm,
		} = this.state;

		const foundTags = findTags( searchTerm ) || [];

		return (
			<BaseControl
				label={ label }
				help={ help }
			>
				<ReactSelect
					options={
						foundTags.map( ( catData ) => {
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

								// get label from tags list
								for (var id in allTags ) {
									if ( name === allTags[id].slug ) {
										thisData.label = allTags[id].name;
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

	// find non-cached tags and try to retrieve.
	slugs = slugs.filter( ( slug ) => {
		return ! cachedTags || ! cachedTags[ slug ];
	} );

	if ( slugs && slugs.length ) {
		const newTags = getEntityRecords( 'taxonomy', 'post_tag', {
			slug: slugs,
			per_page: 100,
		} );

		maybeCache( newTags );
	}

	return {
		findTags( search = '' ) {
			const searchTags = getEntityRecords( 'taxonomy', 'post_tag', {
				search,
				per_page: 20,
			} );

			maybeCache( searchTags );

			return searchTags;
		},
		allTags: cachedTags || {},
	};
})( ComponentTagsSelectorControl );
