var wp = window.wp
var el = wp.element.createElement

function fca_ept_set_layout2_attributes( props ){
	
	props.setAttributes( { selectedLayout: 'layout2'} )
		
	// COLORS
	props.setAttributes( { layoutBGColor: '#f2f2f2' } ) 
	props.setAttributes( { layoutFontColor: '#333333' } )
	props.setAttributes( { layoutFontColor1: '#6236ff' } )
	props.setAttributes( { buttonColor: '#6236ff' } )
	props.setAttributes( { buttonFontColor: '#ffffff' } )
	props.setAttributes( { accentColor: '#6236ff' } )

	// FONT SETTINGS 
	props.setAttributes( { fontFamily: 'sans-serif' } )
	props.setAttributes( { popularFontSize: '70%' } ) 
	props.setAttributes( { planFontSize: '225%' } ) 
	props.setAttributes( { planSubtextFontSize: '90%' } ) 
	props.setAttributes( { priceFontSize: '300%' } ) 
	props.setAttributes( { pricePeriodFontSize: '90%' }) 
	props.setAttributes( { featuresFontSize: '90%' } ) 
	props.setAttributes( { buttonFontSize: '100%' } ) 
	props.setAttributes( { toggleFontSize: '112.5%' } ) 


} 

function fca_ept_layout2_block_edit( props ){

	var columnSettings = JSON.parse( props.attributes.columnSettings )
	
	// append extra variable css settings
	fca_ept_additional_styles( props )
		
	return el( 'div', {
		style: { fontFamily: props.attributes.fontFamily + ', sans-serif' },
		id: 'fca-ept-table-' + props.attributes.tableID,
		className: 'fca-ept-main'
	},
		( fcaEptEditorData.toggle_integration ? fca_ept_render_toggle( props ) : null ),

		// TABLE
		el( 'div', { 
			style: { 
				textDecoration: 'none',
			}, 
			className: 'fca-ept-layout2'
		},
			Array.from( columnSettings, function( x, i ){
				return el( 'div', { 
					key: i,
					style: { 
						backgroundColor: props.attributes.layoutBGColor,
						
						border: columnSettings[i].columnPopular ? '2px solid ' + props.attributes.accentColor : '0px solid'
					},
						className: fca_ept_column_class_name( props, i ),
						onClick: ( function(){ 
							props.setAttributes ( { selectedCol: i } )
						})
					},
					
					el( 'div', { 
						style: { 
							display: columnSettings[i].columnPopular ? 'block' : 'none',
							borderColor: props.attributes.accentColor
						},
						className: 'fca-ept-popular-div'
						},

						el( wp.blockEditor.RichText, { 
							style: { 
								fontSize: props.attributes.popularFontSize,
								color: props.attributes.buttonFontColor,
								backgroundColor: props.attributes.accentColor
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

					el( 'div', { 
						className: 'fca-ept-plan-div'
						},
						el( wp.blockEditor.RichText, { 
							style: { 
								fontSize: props.attributes.planFontSize,
								color: props.attributes.layoutFontColor1
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
						}),

						el( wp.blockEditor.RichText, { 
							style: { 
								display: props.attributes.showPlanSubtextToggle ? 'block' : 'none',
								color: props.attributes.layoutFontColor,
								fontSize: props.attributes.planSubtextFontSize
							}, 
							allowedFormats: fca_ept_allowed_formats,
							className: 'fca-ept-plan-subtext', 
							placeholder: 'To get started', 
							type: "text", 
							tagName: 'span',
							value: columnSettings[i].planSubText, 
							onClick: function(){ 
								fca_ept_update_ui_state( props, 'planSubtext' )
							},
							onChange: function( newValue ){ 
								var columnSettingsData = JSON.parse( props.attributes.columnSettings )
								columnSettingsData[props.attributes.selectedCol].planSubText = newValue
								props.setAttributes( { columnSettings: JSON.stringify( columnSettingsData ) } )
							}
						})
					),

					el( 'div', { 
						className: 'fca-ept-price-div'
						},

						el( 'div', { 
							className: 'fca-ept-price-container'
							},

							el( wp.blockEditor.RichText, { 
								className: 'fca-ept-price',
								style: { 
									fontSize: props.attributes.priceFontSize,
									color: props.attributes.layoutFontColor
								}, 
								allowedFormats: fca_ept_allowed_formats,
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
							}),

							el( 'div', { 
								style: { display: props.attributes.showPriceSubtextToggle ? 'block' : 'none' },
								className: 'fca-ept-price-subtext'
								},
								el ( 'svg', {
									className: 'fca-ept-price-svg',
									style: { 
										backgroundColor: props.attributes.buttonColor 
									}
								}),
								el( wp.blockEditor.RichText, { 
									style: { 
										fontSize: props.attributes.pricePeriodFontSize,
										color: props.attributes.layoutFontColor
									},
									allowedFormats: fca_ept_allowed_formats,
									className: 'fca-ept-price-period', 
									placeholder: 'per month', 
									type: "text", 
									tagName: 'span',
									value: props.attributes.togglePeriod ? columnSettings[i].pricePeriod2 : columnSettings[i].pricePeriod1,
									onClick: function(){ 
										fca_ept_update_ui_state( props, 'pricePeriod' )
									},
									onChange: function( newValue ){ 
										fca_ept_update_priceperiod( props, newValue )
									}
								})
							)
						)
					),


					el( 'div', { 
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
