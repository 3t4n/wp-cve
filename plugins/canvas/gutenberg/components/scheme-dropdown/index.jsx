/**
 * Styles
 */
import './style.scss';

/**
 * WordPress dependencies
 */
import classnames from 'classnames';

const {
	canvasSchemes,
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
class ComponentSchemeDropdown extends Component {
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
	 * @param {string} name - scheme name.
	 * @param {function} onClick - click callback
	 *
	 * @return {JSX}
	 */
	getButton( name, onClick ) {
		const {
			scheme,
		} = this.props;

		name = name || 'default';

		if ( typeof canvasSchemes[ name ] === 'undefined' ) {
			return '';
		}

		const info = canvasSchemes[ name ];

		return (
			<button
				key={ name }
				className={ classnames(
					'cnvs-component-schemes-dropdown-item',
					scheme === name ? 'cnvs-component-schemes-dropdown-item-active' : ''
				) }
				onClick={ () => {
					onClick( name === 'default' ? '' : name );
				} }
				dangerouslySetInnerHTML={ { __html: info.icon } }
			/>
		);
	}

	render() {
		const {
			scheme,
			updateScheme,
		} = this.props;

		const {
			isOpened,
		} = this.state;

		return (
			<div
				className="cnvs-component-schemes"
				ref={ this.componentRef }
			>
				{ this.getButton( scheme, () => {
					this.setState( {
						isOpened: ! isOpened,
					} );
				} ) }
				{ isOpened ? (
					<div className="cnvs-component-schemes-dropdown">
						{ Object.keys( canvasSchemes ).map( ( name ) => {
							return (
								this.getButton( name, ( scheme ) => {

									updateScheme( scheme );

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
			getScheme,
		} = select( 'canvas/scheme' );

		return {
			scheme: getScheme(),
		};
	} ),
	withDispatch( ( dispatch ) => {
		const {
			updateScheme,
		} = dispatch( 'canvas/scheme' );

		return {
			updateScheme,
		};
	} ),
] )( ComponentSchemeDropdown );
