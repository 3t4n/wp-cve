/**
 * Styles
 */
import './style.scss';

/**
 * WordPress dependencies
 */
const {
	Component,
} = wp.element;

const {
	TextControl,
	Button,
} = wp.components;

const allRadius = [ 'topLeft', 'topRight', 'bottomLeft', 'bottomRight'];

/**
 * Component
 */
export default class ComponentRadius extends Component {
	constructor() {
		super( ...arguments );

		this.updateRadius = this.updateRadius.bind( this );
	}

	/**
	 * Update number value.
	 *
	 * @param {String} name - number name.
	 * @param {String} prefix - type prefix.
	 * @param {String} suffix - responsive suffix.
	 * @param {String} val - new value.
	 */
	updateRadius( name, prefix = '', suffix = '', val ) {
		const {
			onChange,
		} = this.props;

		// TextControl return string value, we need to convert to int manually.
		val = parseInt( val, 10 );

		if ( Number.isNaN( val ) ) {
			val = undefined;
		}

		const updateAttrs = {
			[ prefix + name.charAt(0).toUpperCase() + name.slice(1) + suffix ]: val,
		};

		// Change all linked values.
		if ( this.props.link ) {
			allRadius.forEach( ( radius ) => {
				updateAttrs[ prefix + radius.charAt(0).toUpperCase() + radius.slice(1) + suffix ] = val;
			} )
		}

		onChange( updateAttrs );
	}

	render() {
		const {
			prefix = '',
			suffix = '',
			units = [ 'px', 'em' ],
			onChange,
		} = this.props;

		return (
			<div className="cnvs-component-radius">
				<div className="cnvs-component-radius-units">
					{ units.map( ( unit ) => {
						return (
							<Button
								isPrimary={ unit === 'px' ? ! this.props.unit : this.props.unit === unit }
								onClick={ () => {
									onChange( {
										[ prefix + 'Unit' + suffix ]: unit === 'px' ? '' : unit,
									} );
								} }
							>
								{ unit }
							</Button>
						);
					} ) }
				</div>
				<div className="cnvs-component-radius-wrap">
					{ allRadius.map( ( radius ) => {
						let val = this.props[ radius ];

						if ( typeof val == 'undefined' ) {
							val = '';
						}

						return (
							<TextControl
								key={ radius }
								type="number"
								value={ val }
								onChange={ ( val ) => this.updateRadius( radius, prefix, suffix, val ) }
								autoComplete="off"
							/>
						);
					} ) }
					<Button
						isDefault
						isPrimary={ this.props.link }
						onClick={ () => {
							if ( this.props.link ) {
								onChange( {
									[ prefix + 'Link' + suffix ]: false,
								} );
							} else {
								onChange( {
									[ prefix + 'TopRight' + suffix ]: this.props.topLeft,
									[ prefix + 'BottomLeft' + suffix ]: this.props.topLeft,
									[ prefix + 'BottomRight' + suffix ]: this.props.topLeft,
									[ prefix + 'Link' + suffix ]: true,
								} );
							}
						} }
					>
						<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M18.8379 8.23251L13.5352 13.5352C12.0703 15.0001 9.69727 15.0001 8.23242 13.5352L6.46484 11.7677L8.23242 10.0001L10 11.7677C10.4883 12.2547 11.2805 12.2559 11.7676 11.7677L17.0703 6.46493C17.5574 5.97727 17.5574 5.18442 17.0703 4.69672L15.3027 2.92914C14.8157 2.44207 14.0222 2.44207 13.5352 2.92914L11.6418 4.82247C10.7641 4.3061 9.76684 4.08454 8.7793 4.15106L11.7676 1.16157C13.2324 -0.302655 15.6067 -0.302655 17.0703 1.16157L18.8379 2.92914C20.3027 4.3934 20.3027 6.76829 18.8379 8.23251ZM8.35695 15.1783L6.46484 17.0704C5.97656 17.5587 5.18434 17.5575 4.69727 17.0704L2.92969 15.3028C2.44141 14.8158 2.44141 14.0235 2.92969 13.5352L8.23242 8.23251C8.71949 7.74544 9.51293 7.74544 10 8.23251L11.7676 10.0001L13.5352 8.23251L11.7676 6.46493C10.3027 5.00071 7.92969 5.00071 6.46484 6.46493L1.16211 11.7677C-0.302734 13.2325 -0.302734 15.6068 1.16211 17.0704L2.92969 18.838C4.39332 20.3028 6.76758 20.3028 8.23242 18.838L11.2207 15.8497C10.2332 15.9156 9.23828 15.6935 8.35695 15.1783Z" fill="currentColor"/></svg>
					</Button>
				</div>
			</div>
		);
	}
}
