'use strict';

const { createElement, Fragment } = wp.element;
const { registerBlockType } = wp.blocks;
const { useBlockProps } = wp.blockEditor || wp.editor;
const { SelectControl, ToggleControl, PanelBody, Placeholder } = wp.components;

const sccIcon = createElement( 'svg', { width: 20, height: 20, viewBox: '0 0 612 612', className: 'dashicon' },
    createElement( 'path', {
        fill: 'currentColor',
        d: 'M405.333 85.333h-128v-64C277.333 9.551 267.782 0 256 0c-11.782 0-21.333 9.551-21.333 21.333v64h-128c-11.782 0-21.333 9.551-21.333 21.333v384c0 11.782 9.551 21.333 21.333 21.333h298.667c11.782 0 21.333-9.551 21.333-21.333v-384c0-11.781-9.552-21.333-21.334-21.333zm-21.333 384H128V128h106.667v21.333c0 11.782 9.551 21.333 21.333 21.333 11.782 0 21.333-9.551 21.333-21.333V128H384v341.333z',
    } ),
    createElement( 'path', {
        fill: 'currentColor',
        d: 'M256 213.333c-35.355 0-64 28.645-64 64s28.645 64 64 64c11.791 0 21.333 9.542 21.333 21.333S267.791 384 256 384h-42.667C201.551 384 192 393.551 192 405.333c0 11.782 9.551 21.333 21.333 21.333H256c35.355 0 64-28.645 64-64s-28.645-64-64-64c-11.791 0-21.333-9.542-21.333-21.333S244.209 256 256 256h42.667c11.782 0 21.333-9.551 21.333-21.333 0-11.782-9.551-21.333-21.333-21.333H256z',
    } )
);

registerBlockType( 'stylish-cost-calculator/calc-picker', {
	title: "Stylish Cost Calculator",
	description: "Add a Stylish Cost Calculator form to your page.",
	icon: sccIcon,
	keywords: [ 'stylish cost calculator', 'calculator', 'form' ],
	category: 'widgets',
attributes: {
    calcId: {
      type: "string"
    },
  },
  example: {
    attributes: {
      calcId: "1"
    }
  },
	edit: function( props ) {
		const { attributes: { calcId = '' }, setAttributes } = props;
		let jsx = [<Placeholder
			key="df-scc-gutenberg--wrap"
			className="df-scc-gutenberg--wrap">
			<img src={ "https://stylishcostcalculator.com/wp-content/uploads/2020/04/scc-logo209-721.png" }/>
			<SelectControl
				key="df-scc-gutenberg--select-control"
				value={ calcId }
				options={ [{ value: null, label: "Select A Calculator" },...stylish_cost_calculator_calculator_data.map(x => ({label: x.formname, value: x.id}))] }
				onChange={ selectCalc }
			/>
		</Placeholder>];

		function selectCalc( value ) {
			setAttributes( { calcId: value } );
		}
		return jsx;
    },
	save() {
		return null;
	},
} );
