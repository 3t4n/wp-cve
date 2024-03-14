/**
 * External dependencies
 */
import classnames from 'classnames';
import { throttle } from 'throttle-debounce';

/**
 * WordPress dependencies
 */
const $ = window.jQuery;

const { Component, Fragment } = wp.element;

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
import changeColumnSize from './change-column-size';

/**
 * Component
 */
class ColumnBlockEdit extends Component {
	constructor() {
		super( ...arguments );

		this.state = {
			resizing: false,
			resizingContainerWidth: 0,
			resizingCursorPosition: 0,
			resizingColSize: 0,
		};

		this.onResizeStart = this.onResizeStart.bind( this );
		this.onResizing = throttle( 150, this.onResizing.bind( this ) );
		this.onResizeEnd = this.onResizeEnd.bind( this );
	}

	componentDidMount() {
		document.addEventListener( 'mousemove', this.onResizing );
		document.addEventListener( 'mouseup', this.onResizeEnd );
	}
	componentWillUnmount() {
		document.removeEventListener( 'mousemove', this.onResizing );
		document.removeEventListener( 'mouseup', this.onResizeEnd );
	}

	/**
	 * On start column resize
	 *
	 * @param {Object} e event.
	 */
	onResizeStart( e ) {
		const {
			clientId,
			attributes,
		} = this.props;

		const $column = $(`[data-block="${ clientId }"`);
		const $parentRow = $column.closest('.cnvs-block-row');

		this.setState( {
			resizing: true,
			resizingContainerWidth: $parentRow.width(),
			resizingCursorPosition: e.clientX,
			resizingColSize: attributes.size,
		} );

		e.preventDefault();
	}

	/**
	 * On resizing column
	 *
	 * @param {Object} e event.
	 */
	onResizing( e ) {
		if ( ! this.state.resizing ) {
			return;
		}

		const {
			attributes,
			clientId,
		} = this.props;

		const mouseMoved = e.clientX - this.state.resizingCursorPosition;
		const oneColumnSize = this.state.resizingContainerWidth / 12;
		const columnsResized = Math.round( mouseMoved / oneColumnSize );
		const newSize = this.state.resizingColSize + columnsResized;

		if ( newSize !== attributes.size ) {
			changeColumnSize( clientId, newSize, true );
		}
	}

	/**
	 * On end column resize
	 *
	 * @param {Object} e event.
	 */
	onResizeEnd( e ) {
		if ( ! this.state.resizing ) {
			return;
		}

		e.preventDefault();

		this.setState( {
			resizing: false,
			resizingContainerWidth: 0,
			resizingCursorPosition: 0,
			resizingColSize: 0,
		} );
	}

	render() {
		const {
			hasChildBlocks,
			attributes,
			clientId,
		} = this.props;

		const {
			canvasClassName,
		} = attributes;

		let {
			className,
		} = this.props;

		className = classnames(
			'cnvs-block-column',
			canvasClassName,
			className
		);

		return (
			<Fragment>
				<div className={ className }>
					<div className="cnvs-block-column-inner" data-min-height={ attributes['minHeight'] }>
						<div>
							<InnerBlocks
								templateLock={ false }
								renderAppender={ (
									hasChildBlocks ?
										undefined :
										() => <InnerBlocks.ButtonBlockAppender />
								) }
							/>
						</div>
					</div>
				</div>
				<div
					className="cnvs-block-column-resizer"
					draggable="true"
					onMouseDown={ ( e ) => { this.onResizeStart( e ) } }
				>
					<span />
				</div>
				<style>{ canvasClassName && clientId ? getStyles( attributes, canvasClassName, clientId ) : '' }</style>
			</Fragment>
		);
	}
}

const ColumnBlockEditWithSelect = compose(
	withSelect( ( select, ownProps ) => {
		const {
			clientId,
		} = ownProps;

		const {
			getBlockRootClientId,
			getBlocks,
			getBlockOrder,
		} = select( 'core/block-editor' );

		return {
			getBlockRootClientId,
			getBlocks,
			hasChildBlocks: getBlockOrder( clientId ).length > 0,
		};
	} )
)( ColumnBlockEdit );

export default ColumnBlockEditWithSelect;
