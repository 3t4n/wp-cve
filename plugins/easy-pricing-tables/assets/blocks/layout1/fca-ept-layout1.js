var wp = window.wp
var el = wp.element.createElement

function fca_ept_set_layout1_attributes( props ){
	
	props.setAttributes( { selectedLayout: 'layout1'} )
	
	// COLORS
	props.setAttributes( { layoutBGColor: '#ffffff' } ) 
	props.setAttributes( { layoutBGTint2: '#eeeeee' } )
	props.setAttributes( { layoutBGTint3: '#dddddd' } )
	props.setAttributes( { layoutBGTint4: '#7f8c8d' } )
	props.setAttributes( { layoutFontColor: '#333333' } )
	props.setAttributes( { buttonColor: '#3498db' } )
	props.setAttributes( { buttonFontColor: '#ffffff' } )
	props.setAttributes( { buttonBorderColor: '#2980b9' } )
	props.setAttributes( { buttonBorderColorPop: '#c0392b' } )
	props.setAttributes( { accentColor: '#dd4632' } )
		
	// FONT SETTINGS
	props.setAttributes( { fontFamily: fcaEptEditorData.edition === 'Free' ? 'sans-serif' : 'Montserrat' } )
	props.setAttributes( { popularFontSize: '125%' } ) 
	props.setAttributes( { planFontSize: '137.5%' } ) 
	props.setAttributes( { priceFontSize: '175%' } ) 
	props.setAttributes( { featuresFontSize: '125%' } ) 
	props.setAttributes( { buttonFontSize: '112.5%' } ) 
	props.setAttributes( { toggleFontSize: '112.5%' } ) 
}

function fca_ept_layout1_block_edit( props ){
	
	var columnSettings = JSON.parse( props.attributes.columnSettings ) 

	// append extra variable css settings
	fca_ept_layout1_additional_styles( props )
	
	return el( 'div', {
		style: { fontFamily: props.attributes.fontFamily + ', sans-serif' },
		id: 'fca-ept-table-' + props.attributes.tableID,
		className: 'fca-ept-main'
	},
		( fcaEptEditorData.toggle_integration ? fca_ept_render_toggle( props ) : null ),

		// TABLE
		el( 'div', { 
			className: 'fca-ept-layout1'
		},
			Array.from( columnSettings, function( x, i ){
				
				return el( 'div', { 
						key: i,
						className: fca_ept_column_class_name( props, i ),
						style: { backgroundColor: props.attributes.layoutBGTint2 },
						onClick: ( function(){ 
							props.setAttributes ( { selectedCol: i } )
						})
					},

					el( 'div', { 
						style: { 
							backgroundColor: props.attributes.layoutBGTint4,
							color: props.attributes.buttonFontColor
						},
						className: columnSettings[i].columnPopular ? 'fca-ept-popular fca-ept-most-popular' : 'fca-ept-popular'
						},

						el( wp.blockEditor.RichText, { 
							style: { 
								fontSize: props.attributes.popularFontSize
							},
							allowedFormats: fca_ept_allowed_formats,
							className: 'fca-ept-popular-text',
							placeholder: 'Most Popular', 
							type: "text", 
							tagName: 'span',
							value: columnSettings[i].popularText, 
							onClick: function(){ 
								fca_ept_update_ui_state( props, 'popular' )
							},
							onChange: function( newValue ){ 
								fca_ept_update_populartext( props, newValue )
							}
						})
					),

					el( 'div', {
						style: { backgroundColor: props.attributes.layoutBGTint3 },
						className: 'fca-ept-plan-div'
					},
						el( 'div', {
							style: { display: props.attributes.showImagesToggle ? 'block' : 'none' },
							className: fca_ept_has_woo_image( props, i ) ?  'fca-ept-plan-image fca-ept-woo-image' :  'fca-ept-plan-image',
							onClick: function(){
								document.querySelectorAll( '.fca-ept-mediaOpen' )[0].click()
							}
						},
							el( 'img', {
								src: fca_ept_get_planImage( props, i )
							})
						),
						el( wp.blockEditor.RichText, { 
							style: { 
								fontSize: props.attributes.planFontSize,
								color: props.attributes.layoutFontColor
							}, 
							allowedFormats: fca_ept_allowed_formats,
							className: 'fca-ept-plan', 
							placeholder: 'Plan name', 
							type: "text", 
							tagName: 'span',
							value: fca_ept_get_plantext( props, i ),
							onClick: function(){ 
								fca_ept_update_ui_state( props, 'plan' )
								
							}, 
							onChange: function( newValue ){
								fca_ept_update_plantext( props, newValue )
							},
						})
					),

					el( 'div', { 
						style: { backgroundColor: props.attributes.layoutBGTint2 },
						className: 'fca-ept-price-div'
						},
						el( wp.blockEditor.RichText, { 
							style: { 
								fontSize: props.attributes.priceFontSize,
								color: props.attributes.layoutFontColor
							}, 
							allowedFormats: fca_ept_allowed_formats,
							className: 'fca-ept-price',
							placeholder: '$29', 
							type: "text", 
							tagName: 'span',
							value: fca_ept_get_pricetext( props, i ),
							onClick: function(){ 
								fca_ept_update_ui_state( props, 'price' )
							}, 
							onChange: function( newValue ){ 
								fca_ept_update_pricetext( props, newValue )
							},
						})
					),

					el( 'div', { 
						style: { backgroundColor: props.attributes.layoutBGColor },
						className: 'fca-ept-features-div'
						},
						el( wp.blockEditor.RichText, { 
							style: {
								fontSize: props.attributes.featuresFontSize,
								color: props.attributes.layoutFontColor
							}, 
							allowedFormats: fca_ept_allowed_formats,
							className: 'fca-ept-features', 
							tagName: 'ul', 
							multiline: 'li', 
							placeholder: 'features offered', 
							type: "text", 
							value: columnSettings[i].featuresText, 
							onClick: function(){ 
								fca_ept_update_ui_state( props, 'features' )
							},
							onChange: function( newValue ){
								fca_ept_update_featurestext( props, newValue )									
							}
						})
					),
					el( 'div', { 
							className: 'fca-ept-button-div',
							onClick: function(){
								fca_ept_handle_cta_button_click( props )
							},
						},
						fca_ept_button_content( props, columnSettings, i )
					)

				) // end column div
			}) // end array
		) // end table div
	) // end main div
}

function fca_ept_layout1_additional_styles( props ){

	var id = props.attributes.tableID

	$( '#' + id ).remove()

	$( 'body' ).append( 
		"<style class='fca-ept-extra-styles' id='" + id + "'>" +
			"#fca-ept-table-" + id + " div.fca-ept-column a.fca-ept-button:hover { background-color: " + props.attributes.buttonBorderColor + " !important;}" +
			"#fca-ept-table-" + id + " div.fca-ept-column.fca-ept-most-popular a.fca-ept-button:hover { background-color: " + props.attributes.buttonBorderColorPop + " !important;}" +
			"#fca-ept-table-" + id + " div.fca-ept-column div.fca-ept-features li { border-bottom: dotted 1px " + props.attributes.layoutBGTint3 + ";}" +
			props.attributes.customCSS +
		"</style>" 
	)

}