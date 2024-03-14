/**
 * Internal dependencies
 */
import isFieldVisible from '../../utils/is-field-visible';

/**
 * WordPress dependencies
 */
const {
	Component,
} = wp.element;

const {
	canvasBreakpoints,
	canvasAllBreakpoints,
} = window;

/**
 * Component
 */
export default class ComponentFieldsCSSOutput extends Component {
	constructor() {
		super( ...arguments );

		this.prepareStylesFromParams = this.prepareStylesFromParams.bind( this );
	}

	/**
	 * Get current field value. If value doesn't exist, use default value.
	 *
	 * @param {Object} fieldData field data.
	 * @param {String} breakpoint breakpoint name.
	 *
	 * @returns {Mixed} field value.
	 */
	getFieldValue( fieldData, breakpoint = '' ) {
		const {
			attributes = {},
		} = this.props;

		let suffix = '';

		if ( breakpoint ) {
			suffix = `_${ breakpoint }`;
		}

		if ( typeof attributes[ fieldData.key + suffix ] !== 'undefined' ) {
			return attributes[ fieldData.key + suffix ];
		} else if ( typeof fieldData[ 'default' + suffix ] !== 'undefined' ) {
			return fieldData[ 'default' + suffix ];
		}

		return null;
	}

	/**
	 * Prepare styles from params
	 * Params example:
		{
			'element'       : '$',
			'property'      : 'height',
			'value_pattern' : 'linear-gradient(to bottom, $ 14%,#7db9e8 77%)',
			'media_query'   : '@media ( min-width: 760px )',
			'units'         : 'px',
			'prefix'        : 'calc(1px + ',
			'suffix'        : ') !important',
		}
	 *
	 * @param {String} selector CSS selector.
	 * @param {Mixed} value Property value.
	 * @param {Object} params Output params.
	 *
	 * @returns {String}
	 */
	prepareStylesFromParams( selector, value, params ) {
		let result = '';

		if ( ! selector || typeof value === 'undefined' || '' === value || null === value || ! params.property ) {
			return result;
		}

		// check for context
		if ( params.context && params.context.indexOf( 'editor' ) === -1 ) {
			return result;
		}

		// Reverse smart selector.
		if ( params.reverse && params.reverse_max ) {
			let multi_selector = '';
			for (let iteration = 1; iteration < params.reverse_max; iteration++) {
				if ( iteration <= value ) {
					continue;
				}
				multi_selector += ( multi_selector ? ', ' : '' ) + params.reverse.replace( /\$numb/g, iteration );
			}

			selector = multi_selector;
		}

		// value pattern
		if ( params.value_pattern ) {
			value = params.value_pattern.replace( /\$/g, value );
		}

		// prepare CSS
		result = `
			${ params.element ? params.element.replace( /\$/g, selector ) : selector } {
				${ params.property }: ${ params.prefix || '' }${ value }${ params.units || '' }${ params.suffix || '' };
			}
		`;

		// add media query
		if ( params.media_query ) {
			result = `
				${ params.media_query } {
					${ result }
				}
			`;
		}

		return result;
	}

	render() {
		const {
			selector = '',
			fields = [],
			attributes,
		} = this.props;

		let result = '';

		fields
			.filter( ( fieldData ) => {
				if ( ! fieldData || ! fieldData.type ) {
					return false;
				}

				// check active_callback
				return isFieldVisible( fieldData, attributes, fields );
			})
			.forEach( ( fieldData, i ) => {
				if ( selector && fieldData.output ) {
					fieldData.output.forEach( ( outputData ) => {
						// general styles.
						result += this.prepareStylesFromParams( selector, this.getFieldValue( fieldData ), outputData );

						// Dark styles.
						if ( canvasSchemes && ( 'color' === fieldData.type ) ) {
							Object.keys( canvasSchemes ).forEach( ( name ) => {
								if ( name && 'default' !== name ) {

									let rule = `[data-scheme="${name}"] ${selector}`;

									if ( canvasSupportInverseScheme && 'dark' === name ) {
										rule = `[data-scheme="inverse"] ${selector}, [data-scheme="${name}"] ${selector}`;
									}

									result += this.prepareStylesFromParams( rule, this.getFieldValue( fieldData, name ), {
										...outputData
									} );
								}
							} );
						}

						// Responsive styles.
						if ( fieldData.responsive ) {
							Object.keys( canvasAllBreakpoints ).forEach( ( name ) => {
								if ( name && 'desktop' !== name ) {
									let rule = selector;

									// If exist scheme.
									if ( canvasAllBreakpoints[ name ].scheme ) {
										rule = `[data-scheme="${name}"] ${canvasAllBreakpoints[ name ].scheme}`;

										if ( canvasSupportInverseScheme && 'dark' === name ) {
											rule = `[data-scheme="inverse"] ${canvasAllBreakpoints[ name ].scheme}, [data-scheme="${name}"] ${canvasAllBreakpoints[ name ].scheme}`;
										}
									}

									result += this.prepareStylesFromParams( rule, this.getFieldValue( fieldData, name ), {
										...outputData,
										media_query: `@media (max-width: ${ canvasAllBreakpoints[ name ].width }px)`,
									} );
								}
							} );
						}
					} );
				}
			} );

		return (
			result ? (
				<style>
					{ result }
				</style>
			) : ''
		);
	}
}
