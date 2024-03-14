/**
 * External dependencies
 */
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { __, _n, sprintf } from '@wordpress/i18n';
import { Component } from '@wordpress/element';
import PropTypes from 'prop-types';

/**
 * Internal dependencies
 */
import * as ProductControlActions from './state/actions';
import SearchListControl from './search-list-control';

const messages = {
	clear: __( 'Clear all products', 'enhanced-ajax-add-to-cart-wc' ),
	list: __( 'Products', 'enhanced-ajax-add-to-cart-wc' ),
	noItems: __(
		"Your store doesn't have any products.",
		'enhanced-ajax-add-to-cart-wc'
	),
	search: __(
		'Search for products to display',
		'enhanced-ajax-add-to-cart-wc'
	),
	selected: ( n ) =>
		sprintf(
			_n(
				'%d product selected',
				'%d products selected',
				n,
				'enhanced-ajax-add-to-cart-wc'
			),
			n
		),
	updated: __(
		'Product search results updated.',
		'enhanced-ajax-add-to-cart-wc'
	),
};

export class ProductControler extends Component {

	constructor( props ) {
		super(props);

		this.state = {
			// list: this.props.products,
		}
	}

	componentDidMount() {
		// console.log( "ProductController Mounted." );
	}

	render() {
		const { error, multiple, isLoading, products, variations, selected, onSearch, onChange, dispatch } = this.props;
		const { list } = this.props;

		// if ( ! list ) {
		// 	this.setState({ list: products });
		// }
		// console.log( "in render view" );
		if ( error ) {
			return <p>error { error.status }</p>;
		}


		return (
			<div className="wrapper">
				<SearchListControl
					className="woocommerce-products"
					isSingle={ multiple === true ? false : true }
					list={ list && list.length > 0 ? list : products }
					products={ products }
					variations={ variations }
					isLoading={ isLoading }
					selected={ selected }
					/*selected={ products.filter( ( product ) => {
							// const selectedIds = Object.keys( selected ).map(function(key, index) {
							// 	if ( key === 'id' ) {
							// 		return selected[key];
							// 	}
							// })
							const selectedIds = selected.map( ( { id } ) => id );
							return selectedIds.includes( product.id );
						}
					) }*/
					onSearch={ onSearch }
					onChange={ onChange }
					messages={ messages }
					dispatch={ dispatch }
				/>
			</div>
		);
	}
}

ProductControler.propTypes = {
	onChange: PropTypes.func.isRequired,
	onSearch: PropTypes.func,
	selected: PropTypes.array,
	products: PropTypes.array,
	variations: PropTypes.object,
	isLoading: PropTypes.bool,
	multiple: PropTypes.bool,
	dispatch: PropTypes.func.isRequired,
	onListRequest: PropTypes.func
};

ProductControler.defaultProps = {
	selected: [],
	products: [],
	variations: {},
	isLoading: true,
	multiple: false,
	// list: [],
};

const mapStateToProps = state => ({
	selected: state.selected,
	products: state.products,
	variations: state.variations,
	isLoading: state.isLoading,
	list: state.list,
  });
  
const mapDispatchToProps = dispatch => bindActionCreators( ProductControlActions, dispatch );
  
export default connect(mapStateToProps, mapDispatchToProps)(ProductControler);