/**
 * Internal dependencies
 */
const {
	canvasBreakpoints,
} = window;

/**
 * Row block styles for Editor
 *
 * @param {Object} attributes block attributes
 * @param {String} className block class name
 * @param {String} clientId block client id
 *
 * @returns {String}
 */
export default function( attributes, className, clientId ) {
	let result = '';
	let hideResizer = true;

	Object.keys( canvasBreakpoints ).forEach( ( name ) => {
		const data = canvasBreakpoints[ name ];
		let styles = '';
		let suffix = '';

		if ( 'desktop' !== name ) {
			suffix = `_${ name }`;
		}

		/**
		 * Hide resizer.
		 */
		if ( suffix && hideResizer ) {
			hideResizer = false;
			styles += `
				.${ className } + .cnvs-block-column-resizer {
					display: none;
				}
			`;
		}

		/**
		 * Size.
		 */
		if ( attributes[ `size${ suffix }` ] ) {
			const size = attributes[ `size${ suffix }` ];
			styles += `
				#block-${ clientId }[data-type="canvas/column"] {
					-ms-flex-preferred-size: ${ 100 * size / 12 }%;
					flex-basis: ${ 100 * size / 12 }%;
					width: ${ 100 * size / 12 }%;
				}
			`;
		}

		/**
		 * Order.
		 */
		if ( attributes[ `order${ suffix }` ] ) {
			const order = attributes[ `order${ suffix }` ];
			styles += `
				#block-${ clientId }[data-type="canvas/column"] {
					-ms-flex-order: ${ order };
					order: ${ order };
				}
			`;
		}

		/**
		 * Min Height.
		 */
		if ( attributes[ `minHeight${ suffix }` ] ) {
			const minHeight = attributes[ `minHeight${ suffix }` ];
			styles += `
				.${ className } > .cnvs-block-column-inner {
					min-height: ${ minHeight };
				}
			`;
		}

		/**
		 * Vertical Align.
		 */
		if ( attributes[ `verticalAlign${ suffix }` ] ) {
			let verticalAlign = attributes[ `verticalAlign${ suffix }` ];

			if ( 'top' === verticalAlign ) {
				verticalAlign = 'flex-start';
			} else if ( 'bottom' === verticalAlign ) {
				verticalAlign = 'flex-end';
			}

			styles += `
				#block-${ clientId }[data-type="canvas/column"] {
					justify-content: ${ verticalAlign };
				}
				.${ className } > .cnvs-block-column-inner {
					align-items: ${ verticalAlign };
				}
			`;
		}

		// add media query.
		if ( suffix && styles ) {
			styles = `@media (max-width: ${ data.width }px) { ${ styles } } `;
		}

		if ( styles ) {
			result += styles;
		}
	} );

	return result;
}
