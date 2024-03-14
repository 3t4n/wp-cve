/**
 * Styles
 */
import './style.scss';

/**
 * WordPress dependencies
 */
import classnames from 'classnames';

const {
	canvasBreakpoints,
} = window;

const {
	Component,
	createRef,
} = wp.element;

const {
	compose,
} = wp.compose;

const {
	withSelect,
	withDispatch,
} = wp.data;

/**
 * Component
 */
class ComponentResponsiveDropdown extends Component {
	constructor() {
		super( ...arguments );

		this.state = {
			isOpened: false,
		};

		this.componentRef = createRef();

		this.handleClickOutside = this.handleClickOutside.bind(this);
		this.getButton = this.getButton.bind( this );
	}

	componentDidMount() {
		document.addEventListener( 'mousedown', this.handleClickOutside );
	}

	componentWillUnmount() {
		document.removeEventListener( 'mousedown', this.handleClickOutside );
	}

	/**
	 * Hide opened dropdown
	 */
	handleClickOutside( e ) {
		if ( this.componentRef && this.componentRef.current && this.componentRef.current.contains( e.target ) ) {
			return;
		}

		this.setState( {
			isOpened: false,
		} );
	}

	/**
	 * Get responsive button
	 *
	 * @param {string} name - breakpoint name.
	 * @param {function} onClick - click callback
	 *
	 * @return {JSX}
	 */
	getButton( name, onClick ) {
		const {
			breakpoint,
		} = this.props;

		name = name || 'desktop';

		if ( typeof canvasBreakpoints[ name ] === 'undefined' ) {
			return '';
		}

		const info = canvasBreakpoints[ name ];

		return (
			<button
				key={ name }
				className={ classnames(
					'cnvs-component-breakpoints-dropdown-item',
					breakpoint === name ? 'cnvs-component-breakpoints-dropdown-item-active' : ''
				) }
				onClick={ () => {
					onClick( name === 'desktop' ? '' : name );
				} }
				dangerouslySetInnerHTML={ { __html: info.icon } }
			/>
		);
	}

	render() {
		const {
			breakpoint,
			updateBreakpoint,
		} = this.props;

		const {
			isOpened,
		} = this.state;

		return (
			<div
				className="cnvs-component-breakpoints"
				ref={ this.componentRef }
			>
				{ this.getButton( breakpoint, () => {
					this.setState( {
						isOpened: ! isOpened,
					} );
				} ) }
				{ isOpened ? (
					<div className="cnvs-component-breakpoints-dropdown">
						{ Object.keys( canvasBreakpoints ).map( ( name ) => {
							return (
								this.getButton( name, ( breakpoint ) => {
									updateBreakpoint( breakpoint );

									this.setState( {
										isOpened: false,
									} );
								} )
							);
						} ) }
					</div>
				) : '' }
			</div>
		);
	}
}

export default compose( [
	withSelect( ( select ) => {
		const {
			getBreakpoint,
		} = select( 'canvas/breakpoint' );

		return {
			breakpoint: getBreakpoint(),
		};
	} ),
	withDispatch( ( dispatch ) => {
		const {
			updateBreakpoint,
		} = dispatch( 'canvas/breakpoint' );

		return {
			updateBreakpoint,
		};
	} ),
] )( ComponentResponsiveDropdown );
