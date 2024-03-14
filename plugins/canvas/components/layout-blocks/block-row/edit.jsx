/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
const {
	Component,
	Fragment,
} = wp.element;

const {
	InnerBlocks,
} = wp.blockEditor;

const {
	compose,
} = wp.compose;
const {
	withSelect,
} = wp.data;

/**
 * Internal dependencies
 */
import getStyles from './styles';
import changeColumnSize from '../block-column/change-column-size';

const availableSizes = {
	1: [ 12 ],
	2: [ 6, 6 ],
	3: [ 4, 4, 4 ],
	4: [ 3, 3, 3, 3 ],
	5: [ 2, 2, 4, 2, 2 ],
	6: [ 2, 2, 2, 2, 2, 2 ],
};

/**
 * Component
 */
class RowBlockEdit extends Component {
	constructor() {
		super( ...arguments );

		this.state = {
			currentColumnCount: this.props.attributes.columns,
		};

		this.getLayoutTemplate = this.getLayoutTemplate.bind( this );
		this.updateColumnsSizes = this.updateColumnsSizes.bind( this );
	}

	componentDidUpdate( prevProps ) {
		if ( this.props.attributes.columns !== this.state.currentColumnCount ) {
			this.setState( {
				currentColumnCount: this.props.attributes.columns,
			} );
			this.updateColumnsSizes();
		}
	}

	/**
	 * Returns the template configuration for a given section layout.
	 *
	 * @return {Object[]} Layout configuration.
	 */
	getLayoutTemplate() {
		const {
			attributes,
		} = this.props;

		let {
			columns,
		} = attributes;

		const result = [];

		for ( let k = 0; k < columns; k++ ) {
			result.push( [
				'canvas/column',
				{
					size: availableSizes[ columns ][ k ],
				},
			] );
		}

		return result;
	}

	/**
	 * Update columns sizes.
	 */
	updateColumnsSizes() {
		const {
			block,
			attributes,
		} = this.props;

		let {
			columns,
		} = attributes;

		block.innerBlocks.forEach( ( colData, i ) => {
			if ( availableSizes[ columns ][ i ] ) {
				changeColumnSize( colData.clientId, availableSizes[ columns ][ i ] );
			}
		} );
	}

	render() {
		const {
			attributes,
		} = this.props;

		let {
			className,
		} = this.props;

		const {
			columns,
			canvasClassName,
		} = attributes;

		className = classnames(
			'cnvs-block-row',
			`cnvs-block-row-columns-${ columns }`,
			canvasClassName,
		);

		return (
			<Fragment>
				<div className={ className }>
					<div className="cnvs-block-row-inner">
						<InnerBlocks
							template={ this.getLayoutTemplate() }
							templateLock="all"
							allowedBlocks={ [ 'canvas/column' ] }
						/>
					</div>
				</div>
				<style>{ canvasClassName ? getStyles( attributes, canvasClassName ) : '' }</style>
			</Fragment>
		);
	}
}

export default compose( [
	withSelect( ( select, ownProps ) => {
		const {
			getBlock,
		} = select( 'core/block-editor' );

		return {
			block: getBlock( ownProps.clientId ),
		};
	} ),
] )( RowBlockEdit );
