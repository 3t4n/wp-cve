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
 *
 * @returns {String}
 */
export default function( attributes, className ) {
	let result = '';
	let breakColumns = true;

	Object.keys( canvasBreakpoints ).forEach( ( name ) => {
		const data = canvasBreakpoints[ name ];
		let styles = '';
		let suffix = '';

		if ( 'desktop' !== name ) {
			suffix = `_${ name }`;
		}

		/**
		 * Break columns.
		 */
		if ( suffix && breakColumns ) {
			breakColumns = false;
			styles += `
				.${ className } > .cnvs-block-row-inner > .block-editor-inner-blocks > .block-editor-block-list__layout {
					-ms-flex-wrap: wrap;
					flex-wrap: wrap;
				}
			`;
		}

		/**
		 * Gap.
		 */
		if ( typeof attributes[ `gap${ suffix }` ] === 'number' ) {
			const gap = attributes[ `gap${ suffix }` ];
			styles += `
				.${ className } > .cnvs-block-row-inner > .block-editor-inner-blocks > .block-editor-block-list__layout {
					margin-left: ${ - gap / 2 }px;
					margin-right: ${ - gap / 2 }px;
				}
				.${ className } > .cnvs-block-row-inner > .block-editor-inner-blocks > .block-editor-block-list__layout > [data-type="canvas/column"] {
					padding-left: ${ gap / 2 }px;
					padding-right: ${ gap / 2 }px;
				}
				.${ className } > .cnvs-block-row-inner > .block-editor-inner-blocks > .block-editor-block-list__layout > [data-type="canvas/column"] > .canvas-component-custom-blocks > .cnvs-block-column-resizer {
					margin-right: ${ - gap / 2 }px;
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
