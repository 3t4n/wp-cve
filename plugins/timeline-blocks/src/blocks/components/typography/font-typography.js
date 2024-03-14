/**
 * WordPress dependencies
 */
const { __ } = wp.i18n

const {
	SelectControl
} = wp.components

// Extend component
const { Component, Fragment } = wp.element

/**
 * Internal dependencies
 */
import map from "lodash/map"
// import googleFonts from "./fonts"
import Select from "react-select"

function FontFamilyControl( props ) {

	const fonts = [
		{ value: "", label: __( "Default" ), weight: [ "100", "200", "300", "400", "500", "600", "700", "800", "900" ], google: false },
		{ value: "Arial", label: "Arial", weight: [ "100", "200", "300", "400", "500", "600", "700", "800", "900" ], google: false },
		{ value: "Arial Black", label: "Arial Black", weight: [ "100", "200", "300", "400", "500", "600", "700", "800", "900" ], google: false },
		{ value: "Comic Sans MS", label: "Comic Sans MS", weight: [ "100", "200", "300", "400", "500", "600", "700", "800", "900" ], google: false },
		{ value: "Helvetica", label: "Helvetica", weight: [ "100", "200", "300", "400", "500", "600", "700", "800", "900" ], google: false },
		{ value: "Times New Roman", label: "Times New Roman", weight: [ "100", "200", "300", "400", "500", "600", "700", "800", "900" ], google: false },
		{ value: "Georgia", label: "Georgia", weight: [ "100", "200", "300", "400", "500", "600", "700", "800", "900" ], google: false },
		{ value: "Verdana", label: "Verdana", weight: [ "100", "200", "300", "400", "500", "600", "700", "800", "900" ], google: false },
		{ value: "Geneva", label: "Geneva", weight: [ "100", "200", "300", "400", "500", "600", "700", "800", "900" ], google: false },
		{ value: "sans-serif", label: "Sans Serif", weight: [ "100", "200", "300", "400", "500", "600", "700", "800", "900" ], google: false },
		{ value: "Trebuchet MS", label: "Trebuchet MS", weight: [ "100", "200", "300", "400", "500", "600", "700", "800", "900" ], google: false },
		{ value: "Palatino Linotype", label: "Palatino Linotype", weight: [ "100", "200", "300", "400", "500", "600", "700", "800", "900" ], google: false },
		{ value: "Book Antiqua", label: "Book Antiqua", weight: [ "100", "200", "300", "400", "500", "600", "700", "800", "900" ], google: false },
		{ value: "Lucida Console", label: "Lucida Console", weight: [ "100", "200", "300", "400", "500", "600", "700", "800", "900" ], google: false },
		{ value: "Lucida sans unicode", label: "Lucida sans unicode", weight: [ "100", "200", "300", "400", "500", "600", "700", "800", "900" ], google: false },
	]

	let fontWeight = ""
	let fontSubset = ""

	// check if the font is a system font and then apply the font weight accordingly.
	if ( fontWeight === "" ) {
		fontWeight = fonts[0].weight
	}

	const fontWeightObj = []

	fontWeight.forEach(function(item) {
		fontWeightObj.push(
			{ value: item, label: item }
		)
	})

	const fontSubsetObj = []

	if( typeof fontSubset == "object" ) {
		fontSubset.forEach(function(item) {
			fontSubsetObj.push(
				{ value: item, label: item }
			)
		})
	}

	const onFontfamilyChange = ( value ) => {
		const { fontFamily, fontWeight, fontSubset } = props
		props.setAttributes( { [ fontFamily.label ]: value.label } )
		onFontChange( fontWeight, fontSubset, value.label )
	}

	const onFontChange = ( fontWeight, fontSubset, fontFamily ) => {


	}

	return (
		<div className="uag-typography-font-family-options">
			<label class="uag-typography-font-family-label">{ __( "Font Family" ) }</label>
			<Select
				options={ fonts }
				value={ { value: props.fontFamily.value, label: props.fontFamily.value, weight: fontWeightObj } }
				isMulti={ false }
				maxMenuHeight={ 300 }
				onChange={ onFontfamilyChange }
				className="react-select-container" 
				classNamePrefix="react-select"
			/>
			<SelectControl
				label={ __( "Font Weight" ) }
				value={ props.fontWeight.value }
				onChange={ ( value ) => props.setAttributes( { [ props.fontWeight.label ]: value } ) }
				options={
					fontWeightObj
				}
			/>
			<SelectControl
				label={ __( "Font Subset" ) }
				value={ props.fontSubset.value }
				onChange={ ( value ) => props.setAttributes( { [ props.fontSubset.label ]: value } ) }
				options={
					fontSubsetObj
				}
			/>
		</div>
	)
}

export default FontFamilyControl
