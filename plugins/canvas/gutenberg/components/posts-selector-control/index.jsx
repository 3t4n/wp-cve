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

const cachedPosts = {};

function maybeCache( postType, newPosts ) {
	if ( ! newPosts || ! newPosts.length ) {
		return;
	}

	if ( ! cachedPosts[ postType ] ) {
		cachedPosts[ postType ] = {};
	}

	newPosts.forEach( ( postData ) => {
		if ( ! cachedPosts[ postType ][ postData.id ] ) {
			cachedPosts[ postType ][ postData.id ] = postData;
		}
	} );
}

/**
 * Component
 */
class ComponentPostsSelectorControl extends Component {
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
			allPosts,
			findPosts,
		} = this.props;

		const {
			searchTerm,
		} = this.state;

		const foundPosts = findPosts( searchTerm ) || [];

		return (
			<BaseControl
				label={ label }
				help={ help }
			>
				<ReactSelect
					options={
						foundPosts.map( ( postData ) => {
							return {
								value: postData.id,
								label: postData.title.raw,
							};
						} )
					}
					value={ ( () => {
						let result = value ? value.split(',') : [];
						if ( result && result.length ) {
							result = result.map( ( id ) => {
								let thisData = {
									value: id,
									label: id,
								};

								// get label from categories list
								if ( allPosts[ id ] ) {
									thisData.label = allPosts[ id ].title.raw;
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
		postType = 'post',
	} = props;

	let ids = value ? value.split(',') : [];

	// find non-cached posts and try to retrieve.
	ids = ids.filter( ( id ) => {
		return ! cachedPosts[ postType ] || ! cachedPosts[ postType ][ id ];
	} );

	if ( ids && ids.length ) {
		const newPosts = getEntityRecords( 'postType', postType, {
			include: ids,
			per_page: 100,
		} );

		maybeCache( postType, newPosts );
	}

	return {
		findPosts( search = '' ) {
			const searchPosts = getEntityRecords( 'postType', postType, {
				search,
				per_page: 20,
			} );

			maybeCache( postType, searchPosts );

			return searchPosts;
		},
		allPosts: cachedPosts[ postType ] || {},
	};
})( ComponentPostsSelectorControl );
